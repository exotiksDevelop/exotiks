<?php

/**
 * Class ModelExtensionShippingYandexTaxi
 *
 * @property-read \Db $db
 */
class ModelExtensionShippingYandexTaxi extends Model {
    public function install(): void {
        $this->migrate();
    }

    private function migrate(): void {
        $this->createClaimLinksTable();
        $this->createShipmentTable();
        $this->createShipmentOrderTable();
        $this->createJournalCursorsTable();
        $this->addVisitStatusToShipmentOrderTable();
        $this->addDriverPhoneToShipmentTable();
        $this->createWarehousesTable();
        $this->addPriceToShipmentTable();
    }

    private function createClaimLinksTable(): void
    {
        $this->db->query(
            'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'yandextaxi_claim_links` (
                `id` VARCHAR(255) NOT NULL,
                `meta_hash` VARCHAR(255) NOT NULL,
                `address` VARCHAR(255) NOT NULL,
                `lat` DOUBLE,
                `lon` DOUBLE,
                `claim_id` VARCHAR(255) DEFAULT NULL,
                `version` INT(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;'
        );
    }

    private function createShipmentTable(): void
    {
        $this->db->query(
            'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'yandextaxi_shipment` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `tariff` VARCHAR(255),
                `claim_id` VARCHAR(255) NOT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `status_updated_at` TIMESTAMP NULL DEFAULT NULL,
                `driver_name` VARCHAR(255) DEFAULT NULL,
                `car_model` VARCHAR(255) DEFAULT NULL,
                `car_number` VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;'
        );

    }

    private function createShipmentOrderTable(): void
    {
        $this->db->query(
            'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'yandextaxi_shipment_order` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `shipment_id` INT(11) NOT NULL,
                `order_id` INT(11) NOT NULL,
                `visit_status` VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;'
        );
    }

    private function createJournalCursorsTable(): void {
        $this->db->query(
            'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'yandextaxi_journal_cursors` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `value` VARCHAR(255) NOT NULL,
                `datetime` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;'
        );
    }

    private function addVisitStatusToShipmentOrderTable(): void {
        $query = $this->db->query('SHOW COLUMNS FROM `'. DB_PREFIX . "yandextaxi_shipment_order` LIKE 'visit_status'");
        if (!$query->num_rows) {
            $this->db->query(
                'ALTER TABLE `' . DB_PREFIX . 'yandextaxi_shipment_order`
                    ADD COLUMN `visit_status` VARCHAR(255) DEFAULT NULL'
            );
        }
    }

    private function addDriverPhoneToShipmentTable(): void {
        $query = $this->db->query('SHOW COLUMNS FROM `'. DB_PREFIX . "yandextaxi_shipment` LIKE 'driver_phone'");

        if (!$query->num_rows) {
            $this->db->query(
                'ALTER TABLE `' . DB_PREFIX . 'yandextaxi_shipment`
                    ADD COLUMN `driver_phone` VARCHAR(255) DEFAULT NULL'
            );

            $this->db->query(
                'ALTER TABLE `' . DB_PREFIX . 'yandextaxi_shipment`
                    ADD COLUMN `driver_phone_ext` VARCHAR(255) DEFAULT NULL'
            );
        }
    }
    private function createWarehousesTable(): void
    {
       $this->db->query(
            'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'yandextaxi_warehouse` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `address` VARCHAR(255) NOT NULL,
                    `lat` DOUBLE,
                    `lon` DOUBLE,
                    `contact_email` VARCHAR(255) NOT NULL,
                    `contact_name` VARCHAR(255) NOT NULL,
                    `contact_phone` VARCHAR(255) NOT NULL,
                    `startTime` VARCHAR(255) NOT NULL,
                    `endTime` VARCHAR(255) NOT NULL,
                    `comment` VARCHAR(255) NOT NULL,
                    `flat` VARCHAR(255) NOT NULL,
                    `porch` VARCHAR(255) NOT NULL,
                    `floor` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;'
        );
    }

    private function addPriceToShipmentTable(): void {
        $query = $this->db->query('SHOW COLUMNS FROM `'. DB_PREFIX . "yandextaxi_shipment` LIKE 'price'");

        if (!$query->num_rows) {
            $this->db->query(
                'ALTER TABLE `' . DB_PREFIX . 'yandextaxi_shipment`
                    ADD COLUMN `price` INTEGER DEFAULT null'
            );
        }
    }
}
