<?php

namespace DvBusiness\DostavistaOrders;

use DB;

class DostavistaOrderManager
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
                CREATE TABLE IF NOT EXISTS `dvbusiness_dv_orders` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `dostavista_order_id` INT(11) NOT NULL,
                    `courier_name` VARCHAR(255) DEFAULT '',
                    `courier_phone` VARCHAR(100) DEFAULT '',
                    `created_datetime` DATETIME NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `idx_dostavista_order_id` (`dostavista_order_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            "
        );

        $this->db->query(
            "
                CREATE TABLE IF NOT EXISTS `dvbusiness_dv_oc_orders` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `dostavista_order_id` INT(11) NOT NULL,
                    `opencart_order_id` INT(11) NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `idx_dostavista_order_id_opencart_order_id` (`dostavista_order_id`, `opencart_order_id`),
                    KEY `idx_opencart_order_id` (`opencart_order_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
            "
        );
    }

    public function save(DostavistaOrder $dostavistaOrder)
    {
        if ($dostavistaOrder->id) {
            $this->db->query(
                "
                    UPDATE `dvbusiness_dv_orders`
                    SET 
                        `dostavista_order_id` = " . (int) $dostavistaOrder->dostavistaOrderId . ",
                        `courier_name` = '" . $this->db->escape($dostavistaOrder->courierName) . "',
                        `courier_phone` = '" . $this->db->escape($dostavistaOrder->courierPhone) . "'
                    WHERE `id` = " . (int) $dostavistaOrder->id . "    
                "
            );

            $this->db->query("DELETE FROM `dvbusiness_dv_oc_orders` WHERE `dostavista_order_id` = ". (int) $dostavistaOrder->dostavistaOrderId);
        } else {
            $this->db->query(
                "
                    INSERT INTO `dvbusiness_dv_orders` (`dostavista_order_id`, `courier_name`, `courier_phone`, `created_datetime`)
                    VALUES (
                        " . (int) $dostavistaOrder->dostavistaOrderId . ", 
                        '" . $this->db->escape($dostavistaOrder->courierName) . "',
                        '" . $this->db->escape($dostavistaOrder->courierPhone) . "',
                        '" . $this->db->escape(date('Y-m-d H:i:s')) . "'
                    ) 
                "
            );

            $dostavistaOrder->id = $this->db->getLastId();

            if ($dostavistaOrder->openCartOrderIds) {
                $openCartOrderIdWhereSql = join(',', $dostavistaOrder->openCartOrderIds);
                $this->db->query("DELETE FROM `dvbusiness_dv_oc_orders` WHERE `opencart_order_id` IN ({$openCartOrderIdWhereSql})");
            }
        }

        foreach ($dostavistaOrder->openCartOrderIds as $openCartOrderId) {
            $this->db->query(
                "
                   INSERT INTO `dvbusiness_dv_oc_orders` (`dostavista_order_id`, `opencart_order_id`)
                   VALUES (" . (int) $dostavistaOrder->dostavistaOrderId . ", " . (int) $openCartOrderId. ")
               "
            );
        }
    }

    /**
     * @param int $id
     * @return DostavistaOrder|null
     */
    public function getByDostavistaOrderId(int $id)
    {
        $dostavistaOrders = $this->getByDostavistaOrderIds([$id]);
        if ($dostavistaOrders) {
            return $dostavistaOrders[0];
        }

        return null;
    }

    /**
     * @param array $ids
     * @return DostavistaOrder[]
     */
    public function getByDostavistaOrderIds(array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $dostavistaOrders = [];

        $idsWhereSql = join(',', $ids);
        $queryResult = $this->db->query("SELECT * FROM `dvbusiness_dv_orders` WHERE `dostavista_order_id` IN ({$idsWhereSql})");
        if ($queryResult && !empty($queryResult->rows)) {
            $queryOpenCartOrdersResult = $this->db->query("SELECT * FROM `dvbusiness_dv_oc_orders` WHERE `dostavista_order_id` IN ({$idsWhereSql})");

            foreach ($queryResult->rows as $dostavistaOrderRow) {
                $dostavistaOrder                    = new DostavistaOrder();
                $dostavistaOrder->id                = $dostavistaOrderRow['id'];
                $dostavistaOrder->dostavistaOrderId = $dostavistaOrderRow['dostavista_order_id'];
                $dostavistaOrder->courierName       = $dostavistaOrderRow['courier_name'];
                $dostavistaOrder->courierPhone      = $dostavistaOrderRow['courier_phone'];
                $dostavistaOrder->createdDatetime   = $dostavistaOrderRow['created_datetime'];

                if ($queryOpenCartOrdersResult && !empty($queryOpenCartOrdersResult->rows)) {
                    foreach ($queryOpenCartOrdersResult->rows as $dostavistaOpenCartOrderRow) {
                        if ($dostavistaOpenCartOrderRow['dostavista_order_id'] == $dostavistaOrderRow['dostavista_order_id']) {
                            $dostavistaOrder->openCartOrderIds[] = (int) $dostavistaOpenCartOrderRow['opencart_order_id'];
                        }
                    }
                }

                $dostavistaOrders[] = $dostavistaOrder;
            }
        }

        return $dostavistaOrders;
    }

    /**
     * @param int[] $ids
     * @return DostavistaOrder[]
     */
    public function getByOpenCartOrderIds(array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $idsWhereSql = join(',', $ids);
        $queryResult = $this->db->query("SELECT * FROM `dvbusiness_dv_oc_orders` WHERE `opencart_order_id` IN ({$idsWhereSql})");
        if ($queryResult && !empty($queryResult->rows)) {
            $dostavistaOrderIds = array_column($queryResult->rows, 'dostavista_order_id');
            return $this->getByDostavistaOrderIds($dostavistaOrderIds);
        }

        return [];
    }
}