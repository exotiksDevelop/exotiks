<?php

namespace YandexTaxi\Delivery\Entities\Claim;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use DateTime;
use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\ClaimItem\Money;
use YandexTaxi\Delivery\Entities\RoutePoint\Contact;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePointVisitStatus;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePoint;
use RuntimeException;

/**
 * Class Claim
 *
 * @package YandexTaxi\Delivery\Entities\Claim
 */
class Claim
{
    /** @var string|null */
    private $id;

    /** @var ClaimItem[] */
    private $items;

    /** @var RoutePoint */
    private $source;

    /** @var RoutePoint[] */
    private $destinations;

    /** @var DateTime|null */
    private $due;

    /** @var int */
    private $version;

    /** @var Status|null */
    private $status;

    /** @var Driver|null */
    private $driver;

    /** @var AvailableCancelStatus|null */
    private $availableCancelStatus = null;

    /** @var string|null */
    private $tariffName;

    /** @var array */
    private $clientRequirements;

    /** @var Money|null */
    private $price;

    /** @var DateTime|null */
    private $updatedAt;

    /** @var string[]|null */
    private $warnings = null;

    /**
     * Claim constructor.
     *
     * @param ClaimItem[]   $items
     * @param RoutePoint    $source
     * @param RoutePoint[]  $destinations
     * @param DateTime|null $due
     * @param int           $version
     */
    public function __construct(
        array $items,
        RoutePoint $source,
        array $destinations,
        ?DateTime $due,
        int $version = 1
    ) {
        $this->assertAtLeastOneDestination($destinations);
        $this->assertDestinationsHaveCorrectType($destinations);

        $this->items = $items;
        $this->source = $source;
        $this->destinations = $destinations;
        $this->due = $due;
        $this->version = $version;
    }

    public function getEmergencyContact(): Contact
    {
        return $this->source->getContact();
    }

    /**
     * @return ClaimItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getSource(): RoutePoint
    {
        return $this->source;
    }

    /**
     * @return RoutePoint[]
     */
    public function getDestinations(): array
    {
        return $this->destinations;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getDue(): ?DateTime
    {
        return $this->due;
    }

    public function setDue(?DateTime $due): void
    {
        $this->due = $due;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(Driver $driver): void
    {
        $this->driver = $driver;
    }

    public function getAvailableCancelStatus(): ?AvailableCancelStatus
    {
        return $this->availableCancelStatus;
    }

    public function setAvailableCancelStatus(?AvailableCancelStatus $availableCancelStatus): void
    {
        $this->availableCancelStatus = $availableCancelStatus;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getTariffName(): ?string
    {
        return $this->tariffName;
    }

    public function setTariffName(?string $name): void
    {
        $this->tariffName = $name;
    }

    public function getPrice(): ?Money
    {
        return $this->price;
    }

    public function setPrice(?Money $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string[]|null
     */
    public function getWarnings(): ?array
    {
        return $this->warnings;
    }

    /**
     * @param string[] $warnings
     *
     * @return void
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
    }

    public function setClientRequirements(array $clientRequirements): void
    {
        $this->clientRequirements = $clientRequirements;
    }

    public function getClientRequirements(): array
    {
        return $this->clientRequirements;
    }

    public function getRoutePointStatus(int $orderId): RoutePointVisitStatus
    {
        foreach ($this->destinations as $destination) {
            if (intval($destination->getOrderId()) === $orderId) {
                return $destination->getStatus();
            }
        }

        throw new RuntimeException("Order: {$orderId} not found in Claim {$this->getId()}");
    }

    public function isMulti(): bool
    {
        return count($this->destinations) > 1;
    }

    private function assertAtLeastOneDestination(array $destinations): void
    {
        if (empty($destinations)) {
            throw new RuntimeException('Claim should have at least one destination route point');
        }
    }

    private function assertDestinationsHaveCorrectType(array $destinations): void
    {
        foreach ($destinations as $destination) {
            if (!$destination instanceof RoutePoint) {
                throw new RuntimeException('Destination route point has wrong type: ' . get_class($destination));
            }
        }
    }
}
