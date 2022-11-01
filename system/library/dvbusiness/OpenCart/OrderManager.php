<?php

namespace DvBusiness\OpenCart;

use DB;

class OrderManager
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
                CREATE TABLE IF NOT EXISTS `dvbusiness_oc_order_details` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `opencart_order_id` INT(11) NOT NULL,
                    `required_date` VARCHAR(255) DEFAULT '',
                    `required_start_time` VARCHAR(5) DEFAULT '16:00',
                    `required_finish_time` VARCHAR(5) DEFAULT '20:00',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `idx_opencart_order_id` (`opencart_order_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            "
        );
    }

    public function getOpenCartOrderRows(array $data, int $languageId): array
    {
        $sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $languageId . "') AS order_status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
            }
        } else if (isset($data['filter_order_status_id']) && $data['filter_order_status_id'] !== '') {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_shipping_code'])) {
            $sql .= " AND o.shipping_code = '" . $this->db->escape($data['filter_shipping_code']) . "'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'order_status',
            'o.date_added',
            'o.date_modified',
            'o.total'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY o.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function updateOrderStatus(int $orderId, int $statusId)
    {
        $this->db->query(
            "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $statusId . "', date_modified = NOW() WHERE order_id = '" . (int) $orderId . "'"
        );
    }

    public function saveShippingDetails(int $orderId, string $requiredDate, string $requiredStartTime, string $requiredFinishTime)
    {
        $shippingDetails = $this->getShippingDetails($orderId);
        if ($shippingDetails) {
            $this->db->query(
                "
                    UPDATE `dvbusiness_oc_order_details`
                    SET 
                        `required_date` = '" . $this->db->escape($requiredDate) . "',
                        `required_start_time` = '" . $this->db->escape($requiredStartTime) . "',
                        `required_finish_time` = '" . $this->db->escape($requiredFinishTime) . "'
                    WHERE `opencart_order_id` = " . (int) $orderId . "
                "
            );
        } else {
            $this->db->query(
                "
                    INSERT INTO `dvbusiness_oc_order_details` 
                      (`required_date`, `required_start_time`, `required_finish_time`, `opencart_order_id`)
                    VALUES (
                        '" . $this->db->escape($requiredDate) . "',
                        '" . $this->db->escape($requiredStartTime) . "',
                        '" . $this->db->escape($requiredFinishTime) . "',
                        " . (int) $orderId . "
                    )
                "
            );
        }
    }

    public function getShippingDetails(int $orderId): array
    {
        $queryResult = $this->db->query("SELECT * FROM `dvbusiness_oc_order_details` WHERE `opencart_order_id` = {$orderId}");
        return $queryResult->rows[0] ?? [];
    }
}