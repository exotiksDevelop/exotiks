<?php
class ModelModuleSimple extends Model {
    public function alterTableOfSettings() {
        $query = $this->db->query('SHOW COLUMNS FROM `' . DB_PREFIX . 'setting`');

        $change = false;

        foreach ($query->rows as $column) {
            if ($column['Field'] == 'value' && $column['Type'] != 'mediumtext') {
                $change = true;
            }
        }

        if ($change) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "setting` CHANGE `value` `value` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
        }
    }

    public function createTableForCustomerFields() {
        $this->db->query('CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'customer_simple_fields` (
                          `customer_id` int(11) NOT NULL,
                          `metadata` text NULL,
                          PRIMARY KEY (`customer_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    }

    public function createTableForAddressFields() {
        $this->db->query('CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'address_simple_fields` (
                          `address_id` int(11) NOT NULL,
                          `metadata` text NULL,
                          PRIMARY KEY (`address_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    }

    public function createTableForOrderFields() {
        $this->db->query('CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'order_simple_fields` (
                          `order_id` int(11) NOT NULL,
                          `metadata` text NULL,
                          PRIMARY KEY (`order_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    }

    public function createTableForAbandonedCarts() {
        $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "simple_cart'");

        if ($query->rows) {
            $query = $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . 'simple_cart');

            $result = array();

            foreach ($query->rows as $column) {
                if (empty($column['Key'])) {
                    $result[] = strtolower($column['Field']);
                }
            }

            if (!in_array('store_id', $result)) {
                $this->db->query('ALTER TABLE `' . DB_PREFIX . 'simple_cart` ADD `store_id` int(11) NULL');
            }

            if (in_array('cart', $result)) {
                $this->db->query('ALTER TABLE `' . DB_PREFIX . 'simple_cart` DROP `cart`');
                $this->db->query('ALTER TABLE `' . DB_PREFIX . 'simple_cart` ADD `products` text NULL');
            }
        } else {
            $this->db->query('CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'simple_cart` (
                          `simple_cart_id` int(11) NOT NULL AUTO_INCREMENT,
                          `store_id` int(11) NULL,
                          `customer_id` int(11) NULL,
                          `email` varchar(96) NULL,
                          `firstname` varchar(32) NULL,
                          `lastname` varchar(32) NULL,
                          `telephone` varchar(32) NULL,
                          `products` text NULL,
                          `date_added` datetime NOT NULL,
                          PRIMARY KEY (`simple_cart_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
        }
    }

    public function getTotalAbandonedCarts($data) {
        $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "simple_cart'");

        if (!$query->rows) {
            return 0;
        }

        $query = $this->db->query('SELECT COUNT(*) AS count FROM `'.DB_PREFIX.'simple_cart`');

        return $query->row['count'];
    }

    public function getAbandonedCarts($data) {
        $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "simple_cart'");

        if (!$query->rows) {
            return array();
        }

        $sql = 'SELECT 
                    sc.*,
                    CONCAT(sc.firstname, \' \', sc.lastname) AS name,
                    CONCAT(c.firstname, \' \', c.lastname) AS customer
                FROM 
                    `'.DB_PREFIX.'simple_cart` sc 
                LEFT JOIN 
                    `'.DB_PREFIX.'customer` c 
                ON 
                    sc.customer_id = c.customer_id
                ';

        $sql .= ' ORDER BY sc.date_added DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function deleteAbandonedCarts($ids) {
        $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "simple_cart'");

        if (!$query->rows) {
            return;
        }

        $ids_cleared = array();

        foreach ($ids as $id) {
            $ids_cleared[] = (int)$id;
        }

        if (count($ids_cleared)) {
            $this->db->query('DELETE FROM `'.DB_PREFIX.'simple_cart` WHERE simple_cart_id IN (' . implode(',', $ids_cleared) . ')');
        }
    }

    public function clearAbandonedCarts() {
        $this->db->query('TRUNCATE `'.DB_PREFIX.'simple_cart`');
    }

    public function alterTableOfCustomer($fields) {
        $this->alterTable('customer_simple_fields', $fields);
    }

    public function alterTableOfAddress($fields) {
        $this->alterTable('address_simple_fields', $fields);
    }

    public function alterTableOfOrder($fields) {
        $this->alterTable('order_simple_fields', $fields);
    }

    public function createUrlAliases() {
        $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "url_alias'");

        if ($query->rows) {
            $checkQuery = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'checkout/simplecheckout'");

            if (!$checkQuery->num_rows) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'checkout/simplecheckout', `keyword` = 'simplecheckout'");
            }

            $checkQuery = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'account/simpleregister'");

            if (!$checkQuery->num_rows) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'account/simpleregister', `keyword` = 'simpleregister'");
            }
        }
    }

    public function deleteUrlAliases() {
        $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "url_alias'");

        if ($query->rows) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` = 'checkout/simplecheckout' AND `keyword` = 'simplecheckout'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` = 'account/simpleregister' AND `keyword` = 'simpleregister'");
        }
    }

    public function getRuZones() {
        return array(
            2721 => 'Республика Хакасия',
            2722 => 'Московская область',
            2723 => 'Чукотский АО',
            2724 => 'Архангельская область',
            2725 => 'Астраханская область',
            2726 => 'Алтайский край',
            2727 => 'Белгородская область',
            2728 => 'Еврейская АО',
            2729 => 'Амурская область',
            2730 => 'Брянская область',
            2731 => 'Чувашская Республика',
            2732 => 'Челябинская область',
            2733 => 'Карачаево-Черкеcсия',
            2734 => 'Забайкальский край',
            2735 => 'Ленинградская область',
            2736 => 'Республика Калмыкия',
            2737 => 'Сахалинская область',
            2738 => 'Республика Алтай',
            2739 => 'Чеченская Республика',
            2740 => 'Иркутская область',
            2741 => 'Ивановская область',
            2742 => 'Удмуртская Республика',
            2743 => 'Калининградская область',
            2744 => 'Калужская область',
            2746 => 'Республика Татарстан',
            2747 => 'Кемеровская область',
            2748 => 'Хабаровский край',
            2749 => 'Ханты-Мансийский АО - Югра',
            2750 => 'Костромская область',
            2751 => 'Краснодарский край',
            2752 => 'Красноярский край',
            2754 => 'Курганская область',
            2755 => 'Курская область',
            2756 => 'Республика Тыва',
            2757 => 'Липецкая область',
            2758 => 'Магаданская область',
            2759 => 'Республика Дагестан',
            2760 => 'Республика Адыгея',
            2761 => 'Москва',
            2762 => 'Мурманская область',
            2763 => 'Республика Кабардино-Балкария',
            2764 => 'Ненецкий АО',
            2765 => 'Республика Ингушетия',
            2766 => 'Нижегородская область',
            2767 => 'Новгородская область',
            2768 => 'Новосибирская область',
            2769 => 'Омская область',
            2770 => 'Орловская область',
            2771 => 'Оренбургская область',
            2773 => 'Пензенская область',
            2774 => 'Пермский край',
            2775 => 'Камчатский край',
            2776 => 'Республика Карелия',
            2777 => 'Псковская область',
            2778 => 'Ростовская область',
            2779 => 'Рязанская область',
            2780 => 'Ямало-Ненецкий АО',
            2781 => 'Самарская область',
            2782 => 'Республика Мордовия',
            2783 => 'Саратовская область',
            2784 => 'Смоленская область',
            2785 => 'Санкт-Петербург',
            2786 => 'Ставропольский край',
            2787 => 'Республика Коми',
            2788 => 'Тамбовская область',
            2789 => 'Томская область',
            2790 => 'Тульская область',
            2792 => 'Тверская область',
            2793 => 'Тюменская область',
            2794 => 'Республика Башкортостан',
            2795 => 'Ульяновская область',
            2796 => 'Республика Бурятия',
            2798 => 'Республика Северная Осетия',
            2799 => 'Владимирская область',
            2800 => 'Приморский край',
            2801 => 'Волгоградская область',
            2802 => 'Вологодская область',
            2803 => 'Воронежская область',
            2804 => 'Кировская область',
            2805 => 'Республика Саха',
            2806 => 'Ярославская область',
            2807 => 'Свердловская область',
            2808 => 'Республика Марий Эл',
            3483 => 'Республика Крым',           
            3498 => 'Севастополь'
        );
    }

    public function getUaZones() {
        return array(
            3480 => 'Черкасская область',
            3481 => 'Черниговская область',
            3482 => 'Черновицкая область',
            3483 => 'Автономная Республика Крым',
            3484 => 'Днепропетровская область',
            3485 => 'Донецкая область',
            3486 => 'Ивано-Франковская область',
            3487 => 'Харьковская область',
            3488 => 'Хмельницкая область',
            3489 => 'Кировоградская область',
            3490 => 'Киев',
            3491 => 'Киевская область',
            3492 => 'Луганская область',
            3493 => 'Львовская область',
            3494 => 'Николаевская область',
            3495 => 'Одесская область',
            3496 => 'Полтавская область',
            3497 => 'Ровненская область',
            3498 => 'Севастополь',
            3499 => 'Сумская область',
            3500 => 'Тернопольская область',
            3501 => 'Винницкая область',
            3502 => 'Волынская область',
            3503 => 'Закарпатская область',
            3504 => 'Запорожская область',
            3505 => 'Житомирская область',
            3970 => 'Херсонская область'
        );
    }

    private function alterTable($table, $fields) {
        $fields[] = 'metadata';

        $tmp = array();
        $existFields = $this->getColumnsFrom($table);

        foreach ($fields as $field) {
            if (!in_array(strtolower($field), $existFields)) {
                $tmp[] = 'ADD `' . $field . '` TEXT NULL';
            }
        }

        if (count($tmp) > 0) {
            $this->db->query('ALTER TABLE `' . DB_PREFIX . $table . '` ' . implode(',', $tmp));
        }
    }

    private function getColumnsFrom($table) {
        $query = $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . $table);

        $result = array();

        foreach ($query->rows as $column) {
            if (empty($column['Key'])) {
                $result[] = strtolower($column['Field']);
            }
        }

        return $result;
    }

    public function addModifications() {
        $version = explode('.', VERSION);
        $version = floatval($version[0].$version[1].$version[2].'.'.(isset($version[3]) ? $version[3] : 0));

        $data = array(
            'code' => '1 simple url rewrite',
            'name' => '1 simple url rewrite',
            'author' => 'deeman',
            'version' => '1.0.0',
            'link' => 'http://simpleopencart.com',
            'xml' => '
            <modification>
                <name>1 simple url rewrite</name>
                <code>1 simple url rewrite</code>
                <version>1.0.0</version>
                <author>deeman</author>
                <link>http://simpleopencart.com</link>

                <file path="catalog/controller/startup/startup.php">
                    <operation error="skip">
                        <search><![CDATA[new Url]]></search>
                        <add position="after"><![CDATA[$this->url->addRewrite(new Simple\Rewrite($this->config));]]></add>
                    </operation>

                    <operation error="skip">
                        <search><![CDATA[newUrl]]></search>
                        <add position="after"><![CDATA[$this->url->addRewrite(new Simple\Rewrite($this->config));]]></add>
                    </operation>
                </file>
            </modification>',
            'status' => 1
        );

        if ($version < 300) {
            $this->load->model('extension/modification');

            $mod_old = $this->model_extension_modification->getModificationByCode('simple url rewrite');
            $mod_new = $this->model_extension_modification->getModificationByCode('1 simple url rewrite');

            if (empty($mod_old) && empty($mod_new)) {
                $this->model_extension_modification->addModification($data);
            }
        } else {
            $this->load->model('setting/modification');

            $mod_old = $this->model_setting_modification->getModificationByCode('simple url rewrite');
            $mod_new = $this->model_setting_modification->getModificationByCode('1 simple url rewrite');

            if (empty($mod_old) && empty($mod_new)) {
                $this->model_setting_modification->addModification($data);
            }
        }        
    }

    public function deleteModifications() {
        $this->load->model('extension/modification');
        $modification = $this->model_extension_modification->getModificationByCode('simple url rewrite');
        $this->model_extension_modification->deleteModification($modification['modification_id']);
    }
}

class ModelExtensionModuleSimple extends ModelModuleSimple {
}