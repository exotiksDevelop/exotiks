<?php

namespace YandexTaxi\Repositories;

/**
 * Class ProductRepository
 *
 * @package YandexTaxi\Repositories
 */
class ProductRepository extends BaseRepository
{
    private const TABLE_NAME = 'product';

    public function findByIds(array $ids): array {
        $ids = array_filter($ids, 'intval');
        $sql = 'SELECT p.*, w.unit as weight_unit, l.unit as length_unit '
                . 'FROM `' . $this->getTableName() . '` as p '
                . 'LEFT JOIN (SELECT weight_class_id, unit FROM `' . DB_PREFIX . 'weight_class_description` GROUP BY weight_class_id, unit) AS w
                   ON p.weight_class_id = w.weight_class_id '
                . 'LEFT JOIN (SELECT length_class_id, unit FROM `' . DB_PREFIX . 'length_class_description` GROUP BY length_class_id, unit) AS l
                   ON p.length_class_id = l.length_class_id '
                . 'WHERE p.product_id IN (' . $this->db->escape(implode(',', $ids)) . ')';

        return $this->db->query($sql)->rows;
    }

    protected function getTableName(): string
    {
        return DB_PREFIX . self::TABLE_NAME;
    }
}
