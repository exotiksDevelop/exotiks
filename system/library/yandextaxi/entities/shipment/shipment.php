<?php

namespace YandexTaxi\Entities\Shipment;

use DateTime;
use RuntimeException;
use YandexTaxi\Delivery\Entities\Claim\Driver;
use YandexTaxi\Delivery\Entities\Claim\Status;
use YandexTaxi\Delivery\Entities\DriverPhone\DriverPhone;

/**
 * Class Shipment
 *
 * @package YandexTaxi\Entities\Shipment
 */
class Shipment {
    /** @var int */
    private $id;

    /** @var string */
    private $tariff;

    /** @var string */
    private $claim_id;

    /** @var Status|null */
    private $status;

    /** @var DateTime|null */
    private $status_updated_at;

    /** @var Driver|null */
    private $driver;

    /** @var DriverPhone|null */
    private $driverPhone;

    /** @var ShipmentPoint[] */
    private $points;

    /** @var float|null */
    private $price;

    public function __construct(int $id, string $claim_id, string $tariff) {
        $this->id = $id;
        $this->claim_id = $claim_id;
        $this->tariff = $tariff;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTariff(): string {
        return $this->tariff;
    }

    public function getClaimId(): string {
        return $this->claim_id;
    }

    public function setStatus(Status $status): void {
        $this->status = $status;
    }

    public function getStatus(): ?Status {
        return $this->status;
    }

    public function setStatusUpdatedAt(DateTime $status_updated_at): void {
        $this->status_updated_at = $status_updated_at;
    }

    public function getStatusUpdatedAt(): ?DateTime {
        return $this->status_updated_at;
    }

    public function setDriver(Driver $driver): void {
        $this->driver = $driver;
    }

    public function getDriver(): ?Driver {
        return $this->driver;
    }

    /**
     * @return DriverPhone|null
     */
    public function getDriverPhone(): ?DriverPhone {
        return $this->driverPhone;
    }

    public function setDriverPhone(?DriverPhone $driverPhone): void {
        $this->driverPhone = $driverPhone;
    }

    /**
     * @param ShipmentPoint[] $points
     */
    public function setPoints(array $points): void {
        $this->points = $points;
    }

    /**
     * @return ShipmentPoint[]
     */
    public function getPoints(): array {
        return $this->points;
    }

    public function hasPointForOrder(int $order_id): bool {
        foreach ($this->points as $point) {
            if ($point->getOrderId() === $order_id) {
                return true;
            }
        }

        return false;
    }

    public function getPointForOrder(int $order_id): ShipmentPoint {
        foreach ($this->points as $point) {
            if ($point->getOrderId() === $order_id) {
                return $point;
            }
        }

        throw new RuntimeException('Shipment point not found');
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }
}
