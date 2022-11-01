<?php

namespace YandexTaxi\Repositories;

use YandexTaxi\Entities\Warehouse\Warehouse;

/**
 * Class WarehouseRepository
 *
 * @package YandexTaxi\Repositories
 */
class WarehouseRepository extends BaseRepository
{
    private const TABLE_NAME = 'yandextaxi_warehouse';

    /**
     * @return Warehouse[]
     */
    public function all(): array {
        $rawItems = $this->findAll(null, ['id' => 'ASC']);

        return array_map(function (array $raw) {
            return $this->mapWarehouse($raw);
        }, $rawItems);
    }

    public function get(string $id): ?Warehouse {
        $item = $this->findByPk($id);

        if (is_null($item)) {
            return null;
        }

        return $this->mapWarehouse($item);
    }

    public function store(Warehouse $warehouse): void {
        $params = [
            'address' => $warehouse->getAddress(),
            'lat' => $warehouse->getLat(),
            'lon' => $warehouse->getLon(),
            'contact_email' => $warehouse->getContactEmail(),
            'contact_name' => $warehouse->getContactName(),
            'contact_phone' => $warehouse->getContactPhone(),
            'startTime' => $warehouse->getStartTime(),
            'endTime' => $warehouse->getEndTime(),
            'comment' => $warehouse->getComment(),
            'flat' => $warehouse->getFlat(),
            'porch' => $warehouse->getPorch(),
            'floor' => $warehouse->getFloor(),
        ];

        if (is_null($warehouse->getId())) {
            $this->insert($params);
            $warehouse->setId($this->db->getLastId());
            return;
        }

        $this->update($params, ['id' => $warehouse->getId()]);
    }

    private function mapWarehouse($raw): Warehouse {
        return new Warehouse(
            $raw['id'],
            $raw['address'],
            $raw['lat'],
            $raw['lon'],
            $raw['contact_email'],
            $raw['contact_name'],
            $raw['contact_phone'],
            $raw['startTime'],
            $raw['endTime'],
            $raw['comment'],
            $raw['flat'],
            $raw['porch'],
            $raw['floor']
        );
    }

    protected function getTableName(): string{
        return DB_PREFIX . self::TABLE_NAME;
    }
}
