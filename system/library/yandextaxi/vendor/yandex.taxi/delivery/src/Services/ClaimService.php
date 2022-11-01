<?php

namespace YandexTaxi\Delivery\Services;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\ClaimLink\ClaimLink;
use YandexTaxi\Delivery\ClaimLink\ClaimLinkRepository;
use YandexTaxi\Delivery\Entities\Claim\AvailableCancelStatus;
use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\Order\Order;
use YandexTaxi\Delivery\Exceptions\ClaimNotFoundException;
use YandexTaxi\Delivery\Exceptions\ValidationError;
use YandexTaxi\Delivery\GeoCoding\Exceptions\GeoCodingException;
use YandexTaxi\Delivery\GeoCoding\GeoCoderInterface;
use YandexTaxi\Delivery\GeoCoding\Point;
use YandexTaxi\Delivery\Entities\Claim\Claim;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePoint;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Resources\Claims;
use YandexTaxi\Delivery\ClaimLink\ClaimLinkMetaHashBuilder;
use DateTime;

/**
 * Class ClaimService
 *
 * @package YandexTaxi\Delivery\Services
 */
class ClaimService
{
    /** @var ClaimLinkRepository */
    private $claimLinkRepository;

    /** @var Claims */
    private $claims;

    /** @var GeoCoderInterface */
    private $geoCoder;

    /** @var ClaimLinkMetaHashBuilder */
    private $claimLinkMetaHash;

    public function __construct(
        ClaimLinkRepository $claimLinkRepository,
        Claims $claims,
        GeoCoderInterface $geoCoder
    ) {
        $this->claimLinkRepository = $claimLinkRepository;
        $this->claims = $claims;
        $this->geoCoder = $geoCoder;
        $this->claimLinkMetaHash = new ClaimLinkMetaHashBuilder();
    }

    /**
     * @param string $id
     *
     * @return Claim
     * @throws YandexApiException
     */
    public function get(string $id): Claim
    {
        return $this->claims->get($id);
    }

    /**
     * @param string $key
     *
     * @return Claim
     * @throws ClaimNotFoundException
     * @throws YandexApiException
     */
    public function getByKey(string $key): Claim
    {
        $claimLink = $this->claimLinkRepository->get($key);


        if (is_null($claimLink)) {
            throw new ClaimNotFoundException('Claim link was not found');
        }

        return $this->claims->get($claimLink->getClaimId());
    }

    /**
     * @param string[] $ids
     *
     * @return Claim[]
     * @throws YandexApiException
     */
    public function getBulk(array $ids): array
    {
        return $this->claims->getBulk($ids);
    }

    /**
     * @param string                $id
     * @param int                   $version
     * @param AvailableCancelStatus $status
     *
     * @throws YandexApiException
     */
    public function cancel(string $id, int $version, AvailableCancelStatus $status): void
    {
        $this->claims->cancel($id, $version, $status);
    }

    /**
     * @param string        $key
     * @param Order[]       $orders
     * @param RoutePoint    $source
     * @param string|null   $tariff
     * @param DateTime|null $due
     * @param boolean       $waitUntilReadyForApproval
     *
     * @return Claim
     * @throws ValidationError
     * @throws YandexApiException
     */
    public function calculateShippingPrice(
        string $key,
        RoutePoint $source,
        array $orders,
        ?string $tariff,
        array $clientRequirements,
        ?DateTime $due = null,
        $waitUntilReadyForApproval = true
    ): Claim {
        $items = $this->getItemsFromOrders($orders);
        $destinations = $this->getDestinationsFromOrders($orders);

        $this->checkIsFull($items, $source, $destinations);

        $claimLink = $this->claimLinkRepository->get($key);

        if (is_null($claimLink)) {
            $claim = $this->createClaim($key, $items, $source, $destinations, $tariff, $clientRequirements, $due);
            return $waitUntilReadyForApproval ?
                $this->claims->getUntilReadyForApproval($claim->getId(), $recall = true) :
                $this->claims->get($claim->getId());
        }

        $currentMetaHash = $this->claimLinkMetaHash->generate(
            $items,
            $source,
            $destinations,
            $tariff,
            $clientRequirements
        );

        if (!$claimLink->metaWasUpdated($currentMetaHash)) {
            return $waitUntilReadyForApproval ?
                $this->claims->getUntilReadyForApproval($claimLink->getClaimId()) :
                $this->claims->get($claimLink->getClaimId());
        }

        if (count($destinations) === 1) { // if single point destination use point cache
            $destinationAddress = $destinations[0]->getAddress()->getFullName();
            if ($claimLink->addressWasUpdated($destinationAddress)) {
                $point = $this->decodeAddress($destinationAddress);
                $claimLink->setGeo($destinationAddress, $point->getLat(), $point->getLon());
            }
            $destinations[0]->getAddress()->setPoint($claimLink->getLat(), $claimLink->getLon());
        }

        $claim = $this->prepareClaim($items, $source, $destinations, $tariff, $clientRequirements, $due);
        $claim->setId($claimLink->getClaimId());

        $this->claims->update($claim, $claimLink->getVersion());

        $claimLink->incrementVersion();
        $claimLink->setMetaHash($currentMetaHash);
        $this->claimLinkRepository->store($claimLink);

        return $waitUntilReadyForApproval ?
            $this->claims->getUntilReadyForApproval($claim->getId(), $recall = true) :
            $this->claims->get($claimLink->getClaimId());
    }

    /**
     * @param string        $key
     * @param DateTime|null $due
     *
     * @return string
     * @throws ClaimNotFoundException
     * @throws YandexApiException
     */
    public function updateDue(string $key, ?DateTime $due = null): string
    {
        $claimLink = $this->claimLinkRepository->get($key);

        if (is_null($claimLink)) {
            throw new ClaimNotFoundException('Claim link not found in repository');
        }

        $claim = $this->get($claimLink->getClaimId());
        $claim->setDue($due);
        $this->claims->update($claim, $claimLink->getVersion());
        $this->claims->getUntilReadyForApproval($claimLink->getClaimId(), $recall = true); // wait status is updated

        $claimLink->incrementVersion();
        $this->claimLinkRepository->store($claimLink);

        return $claimLink->getClaimId();
    }

    /**
     * @param string $key
     *
     * @return string
     * @throws ClaimNotFoundException
     * @throws YandexApiException
     */
    public function confirm(string $key): string
    {
        $claimLink = $this->claimLinkRepository->get($key);

        if (is_null($claimLink)) {
            throw new ClaimNotFoundException('Claim link not found in repository');
        }

        $this->claims->accept($claimLink->getClaimId(), $claimLink->getVersion());

        $this->claimLinkRepository->delete($key);

        return $claimLink->getClaimId();
    }

    /**
     * @param string        $key
     * @param ClaimItem[]   $items
     * @param RoutePoint    $source
     * @param RoutePoint[]  $destinations
     * @param string|null   $tariff
     * @param array         $clientRequirements
     * @param DateTime|null $due
     *
     * @return Claim
     * @throws ValidationError
     * @throws YandexApiException
     */
    private function createClaim(
        string $key,
        array $items,
        RoutePoint $source,
        array $destinations,
        ?string $tariff = null,
        array $clientRequirements = [],
        ?DateTime $due = null
    ): Claim {
        $claim = $this->prepareClaim($items, $source, $destinations, $tariff, $clientRequirements, $due);
        $this->claims->create($claim);

        $currentMetaHash = $this->claimLinkMetaHash->generate(
            $items,
            $source,
            $destinations,
            $tariff,
            $clientRequirements
        );

        if (count($destinations) === 1) {
            $destination = array_pop($destinations);

            $link = new ClaimLink(
                $key,
                $currentMetaHash,
                $destination->getAddress()->getFullName(),
                $destination->getAddress()->getLat(),
                $destination->getAddress()->getLon(),
                $claim->getId()
            );
        } else {
            $link = new ClaimLink(
                $key,
                $currentMetaHash,
                'multi point address',
                null,
                null,
                $claim->getId()
            );
        }

        $this->claimLinkRepository->store($link);

        return $claim;
    }

    /**
     * @param ClaimItem[]   $items
     * @param RoutePoint    $source
     * @param RoutePoint[]  $destinations
     * @param string|null   $tariff
     * @param array         $clientRequirements
     * @param DateTime|null $due
     *
     * @return Claim
     * @throws ValidationError
     */
    private function prepareClaim(
        array $items,
        RoutePoint $source,
        array $destinations,
        ?string $tariff = null,
        array $clientRequirements = [],
        ?DateTime $due = null
    ): Claim {
        $source = $this->prepareRoutePointAddress($source);

        foreach ($destinations as $destination) {
            $this->prepareRoutePointAddress($destination);
        }

        $claim = new Claim($items, $source, $destinations, $due);
        $claim->setTariffName($tariff);
        $claim->setClientRequirements($clientRequirements);

        return $claim;
    }

    /**
     * @param RoutePoint $routePoint
     *
     * @return RoutePoint
     * @throws ValidationError
     */
    private function prepareRoutePointAddress(RoutePoint $routePoint): RoutePoint
    {
        $address = $routePoint->getAddress();

        if ($address->hasNoPoint()) {
            $point = $this->decodeAddress($address->getFullName());
            $address->setPoint($point->getLat(), $point->getLon());
        }

        return $routePoint;
    }

    /**
     * @param ClaimItem[]  $items
     * @param RoutePoint   $source
     * @param RoutePoint[] $destinations
     *
     * @return bool
     * @throws ValidationError
     */
    private function checkIsFull(
        array $items,
        RoutePoint $source,
        array $destinations
    ): bool {
        $emptyFields = [];

        if (empty($items)) {
            $emptyFields[] = 'товары в корзине';
        }
        if (empty($source->getAddress()->getFullName())) {
            $emptyFields[] = 'адрес склада';
        }
        if (empty($source->getContact()->getPhone())) {
            $emptyFields[] = 'номер телефона склада';
        }
        if (empty($source->getContact()->getName())) {
            $emptyFields[] = 'имя контакта от склада';
        }
        if (empty($source->getContact()->getEmail())) {
            $emptyFields[] = 'email склада';
        }

        foreach ($destinations as $destination) {
            if (empty($destination->getAddress()->getFullName())) {
                $emptyFields[] = 'адрес доставки';
            }
            if (empty($destination->getContact()->getPhone())) {
                $emptyFields[] = 'номер телефона доставки';
            }
            if (empty($destination->getContact()->getName())) {
                $emptyFields[] = 'имя получателя';
            }
        }

        if (!empty($emptyFields)) {
            $message = 'Для рассчета Я доставки неоходимо заполнить поля: ' . implode(',', $emptyFields);

            throw new ValidationError($message);
        }

        return true;
    }

    /**
     * @param Order[] $orders
     *
     * @return ClaimItem[]
     */
    private function getItemsFromOrders(array $orders): array
    {
        $items = [];

        foreach ($orders as $order) {
            $items = array_merge($items, $order->getItems());
        }
        return $items;
    }

    /**
     * @param Order[] $orders
     *
     * @return ClaimItem[]
     */
    private function getDestinationsFromOrders(array $orders): array
    {
        $destinations = [];

        foreach ($orders as $order) {
            $destinations[] = $order->getDestination();
        }

        return $destinations;
    }

    /**
     * @param string $address
     *
     * @return Point
     * @throws ValidationError
     */
    private function decodeAddress(string $address): Point
    {
        try {
            return $this->geoCoder->decode($address);
        } catch (GeoCodingException $exception) {
            throw new ValidationError('Не удалось расшифровать адрес доставки');
        }
    }
}
