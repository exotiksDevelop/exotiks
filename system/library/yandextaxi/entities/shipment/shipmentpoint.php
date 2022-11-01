<?php

namespace YandexTaxi\Entities\Shipment;

use YandexTaxi\Delivery\Entities\RoutePoint\RoutePointVisitStatus as VisitStatus;

/**
 * Class ShipmentPoint
 *
 * @package YandexTaxi\Entities\Shipment
 */
class ShipmentPoint {
    /** @var int */
    private $id;

    /** @var int */
    private $shipment_id;

    /** @var int */
    private $order_id;

    /** @var VisitStatus|null */
    private $visit_status;

    public function __construct(int $id, int $shipment_id, int $order_id) {
        $this->shipment_id = $shipment_id;
        $this->order_id = $order_id;
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getShipmentId(): int {
        return $this->shipment_id;
    }

    public function getOrderId(): int {
        return $this->order_id;
    }

    public function setVisitStatus(VisitStatus $visit_status): void {
        $this->visit_status = $visit_status;
    }

    public function getVisitStatus(): ?VisitStatus {
        return $this->visit_status;
    }
}
