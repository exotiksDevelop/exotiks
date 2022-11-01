<?php

namespace YandexTaxi\Services;

use YandexTaxi\Delivery\Entities\Claim\Status;
use YandexTaxi\Repositories\ShipmentRepository;

/**
 * Class OrderService
 *
 * @package YandexTaxi\Services
 */
class OrderService {
    /** @var ShipmentRepository */
    private $shipment_repository;

    public function __construct(ShipmentRepository $shipment_repository) {
        $this->shipment_repository = $shipment_repository;
    }

    public function checkAvailableForShipping(int $order_id): bool {
        $shipment = $this->shipment_repository->getForOrder($order_id);
        if (is_null($shipment)) {
            return true;
        }

        return $shipment->getStatus()->in(...$this->statusesAllowsShipping());
    }

    /**
     * @param int[] $order_ids
     *
     * @return int[]
     */
    public function getAvailableForShipping(array $order_ids): array {
        return array_filter($order_ids, function (int $order_id) {
            $shipment = $this->shipment_repository->getForOrder($order_id);
            if (is_null($shipment)) {
                return true;
            }

            return $shipment->getStatus()->in(...$this->statusesAllowsShipping());
        });
    }

    /**
     * @param int[] $order_ids
     *
     * @return int[]
     */
    public function getAvailableForCancel(array $order_ids): array {
        return array_filter($order_ids, function (int $order_id) {
            $shipment = $this->shipment_repository->getForOrder($order_id);
            if (is_null($shipment)) {
                return false;
            }

            return !$shipment->getStatus()->in(...$this->cancellationStatuses());
        });
    }

    /**
     * @return Status[]
     */
    private function statusesAllowsShipping(): array {
        return [
            Status::performerNotFound(),
            Status::cancelled(),
            Status::cancelledWithPayment(),
            Status::cancelledByTaxi(),
            Status::cancelledWithItemsOnHands(),
            Status::failed(),
        ];
    }

    /**
     * @return Status[]
     */
    private function cancellationStatuses(): array {
        return [
            Status::performerNotFound(),
            Status::cancelled(),
            Status::cancelledWithPayment(),
            Status::cancelledByTaxi(),
            Status::cancelledWithItemsOnHands(),
        ];
    }
}
