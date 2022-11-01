<?php

namespace DvBusiness\Warehouses;

use DB;

class WarehouseManager
{
    /** @var DB */
    private $db;

    /**
     * @param DB $db
     */
    public function __construct($db)
    {
        $this->db = $db;
        $this->createTablesIfNotExists();
    }

    public function createTablesIfNotExists()
    {
        $this->db->query(
            "
                CREATE TABLE IF NOT EXISTS `dvbusiness_warehouses` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) DEFAULT '',
                    `address` VARCHAR(255) DEFAULT '',
                    `work_start_time` VARCHAR(5) DEFAULT '08:00',
                    `work_finish_time` VARCHAR(5) DEFAULT '20:00',
                    `contact_name` VARCHAR(255) DEFAULT '',
                    `contact_phone` VARCHAR(100) DEFAULT '',
                    `note` TEXT DEFAULT '',
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            "
        );

        // Alter table with update
        $queryResult = $this->db->query("SHOW COLUMNS FROM `dvbusiness_warehouses` LIKE 'city'");
        if (empty($queryResult->rows)) {
            $this->db->query("ALTER TABLE `dvbusiness_warehouses` ADD COLUMN `city` VARCHAR(255) DEFAULT ''");
        }

        // Alter table with update
        $queryResult = $this->db->query("SHOW COLUMNS FROM `dvbusiness_warehouses` LIKE 'workdays'");
        if (empty($queryResult->rows)) {
            $this->db->query("ALTER TABLE `dvbusiness_warehouses` ADD COLUMN `workdays` VARCHAR(255) DEFAULT ''");
        }
    }

    public function save(Warehouse $warehouse)
    {
        if ($warehouse->id) {
            $this->db->query(
                "
                    UPDATE `dvbusiness_warehouses`
                    SET 
                        `name` = '" . $this->db->escape($warehouse->name) . "',
                        `city` = '" . $this->db->escape($warehouse->city) . "',
                        `address` = '" . $this->db->escape($warehouse->address) . "',
                        `work_start_time` = '" . $this->db->escape($warehouse->workStartTime) . "',
                        `work_finish_time` = '" . $this->db->escape($warehouse->workFinishTime) . "',
                        `contact_name` = '" . $this->db->escape($warehouse->contactName) . "',
                        `contact_phone` = '" . $this->db->escape($warehouse->contactPhone) . "',
                        `note` = '" . $this->db->escape($warehouse->note) . "',
                        `workdays` = '" . $this->db->escape($warehouse->workdays) . "'
                    WHERE `id` = " . (int) $warehouse->id . "
                "
            );
        } else {
            $this->db->query(
                "
                    INSERT INTO `dvbusiness_warehouses` 
                      (`name`, `city`, `address`, `work_start_time`, `work_finish_time`, `contact_name`, `contact_phone`, `note`, `workdays`)
                    VALUES (
                        '" . $this->db->escape($warehouse->name) . "',
                        '" . $this->db->escape($warehouse->city) . "',
                        '" . $this->db->escape($warehouse->address) . "',
                        '" . $this->db->escape($warehouse->workStartTime) . "',
                        '" . $this->db->escape($warehouse->workFinishTime) . "',
                        '" . $this->db->escape($warehouse->contactName) . "',
                        '" . $this->db->escape($warehouse->contactPhone) . "',
                        '" . $this->db->escape($warehouse->note) . "',
                        '" . $this->db->escape($warehouse->workdays) . "'
                    ) 
                "
            );

            $warehouse->id = $this->db->getLastId();
        }
    }

    /**
     * @param int $id
     * @return Warehouse|null
     */
    public function getById(int $id)
    {
        $warehouses = $this->getByIds([$id]);
        if ($warehouses) {
            return $warehouses[0];
        }

        return null;
    }

    /**
     * @param array $ids
     * @return Warehouse[]
     */
    public function getByIds(array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $warehouses = [];

        $idsWhereSql = join(',', $ids);
        $queryResult = $this->db->query("SELECT * FROM `dvbusiness_warehouses` WHERE `id` IN ({$idsWhereSql}) ORDER BY `id` ASC");
        if ($queryResult && !empty($queryResult->rows)) {
            foreach ($queryResult->rows as $row) {
                $warehouses[] = $this->populateModel($row);
            }
        }

        return $warehouses;
    }

    /**
     * @return Warehouse[]
     */
    public function getList(): array
    {
        $warehouses = [];

        $queryResult = $this->db->query("SELECT * FROM `dvbusiness_warehouses` ORDER BY `id` ASC");
        if ($queryResult && !empty($queryResult->rows)) {
            foreach ($queryResult->rows as $row) {
                $warehouses[] = $this->populateModel($row);
            }
        }

        return $warehouses;
    }

    public function delete(int $id): bool
    {
        $warehouse = $this->getById($id);
        if (!$warehouse) {
            return false;
        }

        $this->db->query("DELETE FROM `dvbusiness_warehouses` WHERE `id` = " . $warehouse->id);

        return true;
    }

    private function populateModel(array $row): Warehouse
    {
        $warehouse = new Warehouse;
        $warehouse->id             = (int) $row['id'];
        $warehouse->name           = $row['name'];
        $warehouse->city           = $row['city'];
        $warehouse->address        = $row['address'];
        $warehouse->workStartTime  = date('H:i', strtotime($row['work_start_time']));
        $warehouse->workFinishTime = date('H:i', strtotime($row['work_finish_time']));
        $warehouse->contactName    = $row['contact_name'];
        $warehouse->contactPhone   = $row['contact_phone'];
        $warehouse->note           = $row['note'];
        $warehouse->workdays       = $row['workdays'];

        return $warehouse;
    }

    /**
     * @return Warehouse|null
     */
    public function getFirstItemByCityName(string $cityName)
    {
        $queryResult = $this->db->query("SELECT * FROM `dvbusiness_warehouses` WHERE city = '{$cityName}' LIMIT 1");
        if ($queryResult && !empty($queryResult->rows)) {
            return $this->populateModel($queryResult->rows[0]);
        }
        return null;
    }
}
