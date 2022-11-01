<?php

namespace YandexTaxi\Delivery\YandexApi\Resources;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\Claim\AvailableCancelStatus;
use YandexTaxi\Delivery\Entities\Claim\Driver;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePointVisitStatus;
use YandexTaxi\Delivery\Entities\Claim\Status;
use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\Entities\RoutePoint\Address;
use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\RoutePoint\Contact;
use YandexTaxi\Delivery\Entities\Claim\Claim;
use YandexTaxi\Delivery\Entities\ClaimItem\Money;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePoint;
use YandexTaxi\Delivery\Entities\ClaimItem\Size;
use YandexTaxi\Delivery\YandexApi\Exceptions\ClaimNotEstimated;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use DateTime;

/**
 * Class Claim
 *
 * @package YandexTaxi\Delivery\YandexApi\Resources
 */
class Claims extends Resource
{
    protected function getBasePath(): string
    {
        return 'cargo/integration';
    }

    /**
     * @param Claim $claim
     *
     * @throws YandexApiException
     */
    public function create(Claim $claim): void
    {
        $result = $this->call('claims/create', Client::API_V2, [
            'json' => $this->claimToArray($claim),
        ]);

        $claim->setId($result['id']);
    }

    /**
     * @param Claim $claim
     * @param int   $version
     *
     * @throws YandexApiException
     */
    public function update(Claim $claim, int $version): void
    {
        $this->call('claims/edit', Client::API_V2, [
            'json' => $this->claimToArray($claim),
            'query' => [
                'claim_id' => $claim->getId(),
                'version' => $version,
            ],
        ]);
    }

    /**
     * @param string $id
     * @param bool   $recall
     *
     * @return Claim
     * @throws ClaimNotEstimated
     * @throws YandexApiException
     */
    public function getUntilReadyForApproval(string $id, $recall = false): Claim
    {
        $raw = $this->getRawInfo($id);
        $this->checkHasNoError($raw);

        if ($recall && $this->isNotStatusReadyForApproval($raw['status'])) {

            $i = 1;
            do {
                sleep(0.5);
                $i++;
                $raw = $this->getRawInfo($id);
                $this->checkHasNoError($raw);
            } while ($this->isNotStatusReadyForApproval($raw['status']) && $i < 15);
        }

        if ($this->isNotStatusReadyForApproval($raw['status'])) {
            throw new ClaimNotEstimated("Current status: {$raw['status']}");
        }

        return $this->mapClaim($raw);
    }

    /**
     * @param string $id
     * @param int    $version
     *
     * @throws YandexApiException
     */
    public function accept(string $id, int $version = 1): void
    {
        $this->call('claims/accept', Client::API_V1, [
            'query' => ['claim_id' => $id],
            'json' => [
                'version' => $version,
            ],
        ]);
    }

    /**
     * @param string $id
     *
     * @return Claim
     * @throws YandexApiException
     */
    public function get(string $id): Claim
    {
        $raw = $this->getRawInfo($id);
        $this->checkHasNoError($raw);
        return $this->mapClaim($raw);
    }

    /**
     * @param string[] $ids
     *
     * @return Claim[]
     * @throws YandexApiException
     */
    public function getBulk(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $raw = $this->call('claims/bulk_info', Client::API_V2, ['json' => ['claim_ids' => $ids]]);

        $claims = [];
        foreach ($raw['claims'] as $rawClaim) {
            $claims[] = $this->mapClaim($rawClaim);
        }

        return $claims;
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
        $this->call('claims/cancel', Client::API_V1, [
            'json' => [
                'version' => $version,
                'cancel_state' => (string)$status,
            ],
            'query' => [
                'claim_id' => $id,
            ],
        ]);
    }

    private function mapClaim(array $raw): Claim
    {
        $items = [];

        $dropOffPointOrderIdMap = [];
        $destinationPoints = [];

        foreach ($raw['route_points'] as $rawPoint) {
            if ($rawPoint['type'] === 'source') {
                $sourcePoint = $this->mapRoutePoint($rawPoint);
            }
            if ($rawPoint['type'] === 'destination') {
                $destinationPoints[] = $this->mapRoutePoint($rawPoint);

                if (isset($rawPoint['external_order_id'])) {
                    $dropOffPointOrderIdMap[$rawPoint['id']] = $rawPoint['external_order_id'];
                }
            }
        }

        foreach ($raw['items'] as $rawItem) {
            $extraId = $rawItem['extra_id'] ?? null;
            $id = intval(str_replace('Product-', '', $extraId));

            $item = new ClaimItem(
                $id,
                $extraId,
                $dropOffPointOrderIdMap[$rawItem['droppof_point']] ?? null,
                $rawItem['title'],
                new Size(
                    $rawItem['size']['width'],
                    $rawItem['size']['length'],
                    $rawItem['size']['height']
                ),
                new Money(
                    $rawItem['cost_value'],
                    $rawItem['cost_currency']
                ),
                $rawItem['weight'],
                $rawItem['quantity']
            );

            $item->setDropOffPointId($rawItem['droppof_point']);

            $items[] = $item;
        }

        $due = (isset($raw['due'])) ? new DateTime($raw['due']) : null;

        $claim = new Claim($items, $sourcePoint, $destinationPoints, $due, intval($raw['version']));
        $claim->setId($raw['id']);
        $claim->setStatus(Status::fromCode($raw['status']));
        $claim->setUpdatedAt(new DateTime($raw['updated_ts']));

        $claim->setAvailableCancelStatus(
            isset($raw['available_cancel_state']) ? AvailableCancelStatus::fromCode($raw['available_cancel_state']) : null
        );

        if (isset($raw['performer_info'])) {
            $driver = new Driver(
                $raw['performer_info']['courier_name'],
                $raw['performer_info']['car_model'],
                $raw['performer_info']['car_number']
            );
            $claim->setDriver($driver);
        }

        if (isset($raw['matched_cars'][0]['taxi_class'])) { // now in doc only 1 available
            $claim->setTariffName($raw['matched_cars'][0]['taxi_class']);
        }

        if (isset($raw['pricing']['offer'])) {
            $claim->setPrice(new Money($raw['pricing']['offer']['price'], $raw['pricing']['currency']));
        }

        if (isset($raw['warnings'])) {
            $claim->setWarnings(array_column($raw['warnings'], 'message'));
        }

        $claim->setClientRequirements($raw['client_requirements'] ?? []);

        return $claim;
    }

    private function isNotStatusReadyForApproval(string $statusCode): bool
    {
        return !Status::fromCode($statusCode)->equals(Status::readyForApproval());
    }

    private function mapRoutePoint(array $rawPoint): RoutePoint
    {
        $address = new Address(
            $rawPoint['address']['fullname'],
            $rawPoint['address']['coordinates'][1],
            $rawPoint['address']['coordinates'][0],
            $rawPoint['address']['comment'] ?? ''
        );

        $address->setFlat($rawPoint['address']['sflat'] ?? null);
        $address->setFloor($rawPoint['address']['sfloor'] ?? null);
        $address->setPorch($rawPoint['address']['porch'] ?? null);

        $point = new RoutePoint(
            new Contact(
                $rawPoint['contact']['name'],
                $rawPoint['contact']['phone'],
                $rawPoint['contact']['email'] ?? ''
            ),
            $address,
            !$rawPoint['skip_confirmation'], // send == !skip
            $rawPoint['external_order_id'] ?? null
        );
        $point->setId($rawPoint['id']);
        $point->setStatus(RoutePointVisitStatus::fromCode($rawPoint['visit_status']));

        return $point;
    }

    /**
     * @param $id
     *
     * @return array
     * @throws YandexApiException
     */
    private function getRawInfo($id): array
    {
        return $this->call('claims/info', Client::API_V2, ['query' => ['claim_id' => $id]]);
    }

    /**
     * @param array $raw
     *
     * @return void
     * @throws YandexApiException
     */
    private function checkHasNoError(array $raw): void
    {
        if (isset($raw['error_messages'])) {
            if ($raw['error_messages'][0]['code'] === 'errors.default_performer_not_found') {
                return;
            }

            throw new YandexApiException($raw['error_messages'][0]['message']);
        }
    }

    private function claimToArray(Claim $claim): array
    {
        $items = [];

        foreach ($claim->getItems() as $item) {
            $itemArray = [
                'pickup_point' => $claim->getSource()->getId(),
                'droppof_point' => $item->getDropOffPointId(),
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'size' =>
                    [
                        'length' => $item->getSize()->getLength(),
                        'width' => $item->getSize()->getWidth(),
                        'height' => $item->getSize()->getHeight(),
                    ],
                'cost_value' => $item->getCost()->getValue(),
                'cost_currency' => $item->getCost()->getCurrency(),
                'weight' => $item->getWeight(),
                'quantity' => $item->getQuantity(),
            ];

            if (!empty($item->getExtraId())) {
                $itemArray['extra_id'] = $item->getExtraId();
            }

            $items[] = $itemArray;
        }

        $routePoints = [
            $this->prepareRoutePoint($claim->getSource(), 1, 'source'),
        ];

        foreach ($claim->getDestinations() as $key => $destination) {
            $routePoints[] = $this->prepareRoutePoint($destination, $key + 2, 'destination');
        }

        $params = [
            'emergency_contact' => $this->prepareContact($claim->getEmergencyContact()),
            'items' => $items,
            'route_points' => $routePoints,
            'referral_source' => $this->getReferralSourceName(),
        ];

        if (!empty($claim->getDue())) {
            $params['due'] = $claim->getDue()->format(DateTime::ATOM);
        }

        if (!empty($claim->getClientRequirements())) {
            $params['client_requirements'] = $claim->getClientRequirements();
        }

        if (!empty($claim->getTariffName())) {
            $params['client_requirements']['taxi_class'] = (string) $claim->getTariffName();
        }

        return $params;
    }

    private function prepareRoutePoint(RoutePoint $point, int $visitOrder, string $type): array
    {
        $params = [
            'point_id' => $point->getId(),
            'visit_order' => $visitOrder,
            'type' => $type,
            'contact' => $this->prepareContact($point->getContact()),
            'address' => $this->prepareAddress($point->getAddress()),
            'skip_confirmation' => !$point->sendConfirmationSms(),
        ];

        if (!is_null($point->getOrderId())) {
            $params['external_order_id'] = $point->getOrderId();
        }

        return $params;
    }

    private function prepareContact(Contact $contact): array
    {
        $fields = [
            'name' => $contact->getName(),
            'phone' => $contact->getPhone(),
        ];

        if (!empty($contact->getEmail())) {
            $fields['email'] = $contact->getEmail();
        }

        return $fields;
    }

    private function prepareAddress(Address $address): array
    {
        $fields = [
            'fullname' => $address->getFullName(),
            'coordinates' => [$address->getLon(), $address->getLat()],
        ];

        if (!is_null($address->getPorch())) {
            $fields['porch'] = $address->getPorch();
        }

        if (!is_null($address->getFloor())) {
            $fields['sfloor'] = $address->getFloor();
        }

        if (!is_null($address->getFloor())) {
            $fields['sflat'] = $address->getFlat();
        }

        if (!empty($address->getComment())) {
            $fields['comment'] = $address->getComment();
        }

        return $fields;
    }
}
