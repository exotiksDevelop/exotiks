<?php

namespace YandexTaxi\Repositories;

/**
 * Class OrderRepository
 *
 * @package YandexTaxi\Repositories
 */
class OrderRepository extends BaseRepository
{
    private const TABLE_NAME = 'order';

    public function getOrderById(int $id): ?array {
        return $this->findOne(['order_id' => $id]);
    }
    /**
     * @param int[] $ids
     *
     * @return array
     */
    public function getOrdersByIds(array $ids): array {
        return $this->findAll(['order_id' => $ids]);
    }

    public function getOrdersProductsByOrderIds(array $ids): array {
        $sql = 'SELECT op.*, p.weight, p.length, p.width, p.height, w.unit as weight_unit, l.unit as length_unit '
                . 'FROM `' . DB_PREFIX . 'order_product` as op '
                . 'INNER JOIN `' . DB_PREFIX . 'product` as p ON op.product_id = p.product_id '
                . 'LEFT JOIN (SELECT weight_class_id, unit FROM `' . DB_PREFIX . 'weight_class_description` GROUP BY weight_class_id, unit) AS w
                   ON p.weight_class_id = w.weight_class_id '
                . 'LEFT JOIN (SELECT length_class_id, unit FROM `' . DB_PREFIX . 'length_class_description` GROUP BY length_class_id, unit) AS l
                   ON p.length_class_id = l.length_class_id '
                . 'WHERE op.order_id IN (' . $this->db->escape(implode(',', $ids)) . ')';

        return $this->db->query($sql)->rows;
    }

    protected function getTableName(): string
    {
        return DB_PREFIX . self::TABLE_NAME;
    }
}
