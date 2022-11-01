<?php

namespace YandexTaxi\Repositories;

use DateTime;
use YandexTaxi\Delivery\Entities\Claim\Claim;
use YandexTaxi\Delivery\Entities\Claim\Driver;
use YandexTaxi\Delivery\Entities\Claim\Status;
use YandexTaxi\Delivery\Entities\DriverPhone\DriverPhone;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePointVisitStatus;
use YandexTaxi\Entities\Shipment\Shipment;
use YandexTaxi\Entities\Shipment\ShipmentPoint;
use YandexTaxi\Services\OrderStatusService;

/**
 * Class ShipmentRepository
 *
 * @package YandexTaxi\Repositories
 */
class ShipmentRepository extends BaseRepository
{
    private const SHIPMENT_TABLE_NAME = 'yandextaxi_shipment';
    private const SHIPMENT_ORDER_TABLE_NAME = 'yandextaxi_shipment_order';

    public function getByClaimId(string $claim_id): ?Shipment {
        $row = $this->findOne(['claim_id' => $claim_id]);
        if (is_null($row)) {
            return null;
        }

        return $this->buildShipment($row);
    }

    public function getForOrder(int $order_id): ?Shipment {
        $row = $this->getShipmentRowsForOrderId($order_id);
        if (is_null($row)) {
            return null;
        }

        return $this->buildShipment($row);
    }

    private function buildShipment(array $row): Shipment {
        $shipment = new Shipment(
            (int)$row['id'],
            $row['claim_id'],
            $row['tariff']
        );

        if ($row['status']) {
            $shipment->setStatus(Status::fromCode($row['status']));
        }
        if ($row['status_updated_at']) {
            $shipment->setStatusUpdatedAt(new DateTime($row['status_updated_at']));
        }
        if (!empty($row['driver_name'])
            && !empty($row['car_model'])
            && !empty($row['car_number'])) {
            $shipment->setDriver(new Driver(
                $row['driver_name'],
                $row['car_model'],
                $row['car_number']
            ));
        }

        if (!empty($row['driver_phone'])) {
            $shipment->setDriverPhone(new DriverPhone($row['driver_phone'], $row['driver_phone_ext']));
        }

        if (!empty($row['price'])) {
            $shipment->setPrice($row['price']);
        }

        $shipment->setPoints($this->buildShipmentPoints($row['id']));

        return $shipment;
    }

    /**
     * @param int $shipment_id
     *
     * @return ShipmentPoint[]
     */
    private function buildShipmentPoints(int $shipment_id): array {
        return array_map(function (array $orderRow) {
            $point = new ShipmentPoint(
                intval($orderRow['id']),
                intval($orderRow['shipment_id']),
                intval($orderRow['order_id'])
            );
            if (!empty($orderRow['visit_status'])) {
                $point->setVisitStatus(RoutePointVisitStatus::fromCode($orderRow['visit_status']));
            }

            return $point;
        }, $this->getShipmentOrderRows($shipment_id));
    }

    /**
     * @param int $order_id
     *
     * @return array|null
     */
    private function getShipmentRowsForOrderId(int $order_id): ?array {
        $sql = "SELECT s.* FROM `{$this->getTableName()}` AS s "
            . "INNER JOIN (SELECT * FROM `{$this->getShipmentOrderTable()}` "
            . "WHERE id IN (SELECT MAX(id) FROM `{$this->getShipmentOrderTable()}`"
            . $this->prepareCondition(['order_id' => $order_id]) . ' GROUP BY order_id)'
            . ') AS so ON so.shipment_id = s.id GROUP BY s.id LIMIT 0,1';

        return $this->db->query($sql)->rows[0] ?? null;
    }

    private function getShipmentOrderRows(int $shipment_id): array {
        $sql = "SELECT * FROM {$this->getShipmentOrderTable()} WHERE shipment_id = {$shipment_id}";

        return $this->db->query($sql)->rows;
    }


    public function create(string $claim_id, string $tariff, array $order_ids): void {
        $this->insert([
            'claim_id' => $claim_id,
            'tariff' => $tariff,
        ]);

        $shipment_id = $this->db->getLastId();
        $params = array_map(function ($order_id) use ($shipment_id) {
            return "($shipment_id, $order_id)";
        }, $order_ids);
        $query = implode(',', $params);

        $this->db->query("INSERT INTO `{$this->getShipmentOrderTable()}` (shipment_id, order_id) VALUES {$query}");
    }

    public function updateOrdersVisitStatus(Claim $claim, ?OrderStatusService $orderStatusService = null): void {
        $shipment = $this->getByClaimId($claim->getId());

        if (is_null($shipment)) {
            return;
        }

        foreach ($claim->getDestinations() as $destination) {
            if (is_null($destination->getOrderId()) || is_null($destination->getStatus())) {
                continue;
            }

            $order_id = intval($destination->getOrderId());
            $status = $destination->getStatus()->getCode();

            $sql = "UPDATE `{$this->getShipmentOrderTable()}` "
                . 'SET ' . $this->prepareItemsForQuery(['visit_status' => $status])
                . $this->prepareCondition(['order_id' => $order_id, 'shipment_id' => $shipment->getId()]);
            $this->db->query($sql);

            if (!is_null($orderStatusService)) {
                $orderStatusService->changeIfNeeded($destination->getOrderId(), $shipment, $this->getForOrder($order_id));
            }
        }
    }

    public function updateStatus(string $claim_id, Status $status, DateTime $updated_at): void {
        $this->update(
            [
                'status' => $status->getCode(),
                'status_updated_at' => $updated_at->format('Y-m-d H:i:s'),
            ],
            ['claim_id' => $claim_id]
        );
    }

    public function updateDriver(string $claim_id, Driver $driver): void {
        $this->update(
            [
                'driver_name' => $driver->getName(),
                'car_model' => $driver->getCarModel(),
                'car_number' => $driver->getCarNumber(),
            ],
            ['claim_id' => $claim_id]
        );
    }

    public function updateDriverPhone(string $claim_id, ?DriverPhone $driverPhone): void {
        $this->update(
            [
                'driver_phone' => !is_null($driverPhone) ? $driverPhone->getPhone(): null,
                'driver_phone_ext' =>  !is_null($driverPhone) ? $driverPhone->getExt(): null,
            ],
            ['claim_id' => $claim_id]
        );
    }

    public function updatePrice(string $claim_id, float $price): void {
        $this->update(['price' => $price * 100], ['claim_id' => $claim_id]);
    }

    protected function getTableName(): string {
        return DB_PREFIX . self::SHIPMENT_TABLE_NAME;
    }

    private function getShipmentOrderTable(): string {
        return DB_PREFIX . self::SHIPMENT_ORDER_TABLE_NAME;
    }
}
