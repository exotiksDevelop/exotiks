<?php

namespace YandexTaxi\Repositories;

/**
 * Class BaseRepository
 *
 * @package YandexTaxi\Repositories
 */
abstract class BaseRepository {
    /** @var \Db */
    protected $db;

    /** @var string */
    protected $primary_key = 'id';

    /**
     * BaseRepository constructor.
     *
     * @param \Db $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    public function findByPk(string $id): ?array {
        return $this->findOne([$this->primary_key => $id]);
    }

    public function findOne(?array $condition = null, ?array $sort_by = null): ?array {
        $item = $this->findAll($condition, $sort_by, 0, 1);

        return !empty($item) ? $item[0] : null;
    }

    public function findAll(?array $condition = null, ?array $sort_by = null, int $start = 0, int $limit = 0): array {
        $sql = "SELECT * FROM `{$this->getTableName()}`" . $this->prepareCondition($condition)
            . $this->prepareOrderBy($sort_by) . (($limit > 0) ? " LIMIT $start,$limit" : '');

        return $this->db->query($sql)->rows;
    }

    public function insert(array $item): void {
        $sql = "INSERT INTO `{$this->getTableName()}` SET {$this->prepareItemsForQuery($item)}";

        $this->db->query($sql);
    }

    public function update(array $item, array $condition): void {
        $sql = "UPDATE `{$this->getTableName()}`"
                . ' SET ' . $this->prepareItemsForQuery($item) . $this->prepareCondition($condition);

        $this->db->query($sql);
    }

    public function deleteByPk(string $id): void {
        $sql = "DELETE from `{$this->getTableName()}` " . $this->prepareCondition([$this->primary_key => $id]);

        $this->db->query($sql);
    }

    abstract protected function getTableName(): string;

    protected function prepareItemsForQuery(array $item): string {
        $params = [];

        foreach ($item as $field => $value) {
            $value = ($value === null) ? $value : $this->db->escape($value);

            $params[] = ($value === null) ? "`$field` = NULL" : "`$field` = '$value'";
        }

        return implode(',', $params);
    }

    protected function prepareCondition(?array $condition = null): string {
        if (empty($condition)) {
            return '';
        }

        $where = [];
        foreach ($condition as $field => $value) {
            if ($value === null) {
                $where[] = "`$field` IS NULL";
            } elseif (is_array($value)) {
                $value = $this->db->escape(implode(',', $value));
                $where[] = "`$field` IN ($value)";
            } else {
                $where[] = "`$field` = '{$this->db->escape($value)}'";
            }
        }

        return ' WHERE ' . implode(' AND ', $where);
    }

    protected function prepareOrderBy(?array $order_by = null): string {
        if (empty($order_by)) {
            return '';
        }

        $sort = [];
        foreach ($order_by as $field => $order) {
            $sort[] = "`$field` $order";
        }

        return ' ORDER BY ' . implode(', ', $sort);
    }
}
