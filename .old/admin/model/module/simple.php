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
            2721 => '???????????????????? ??????????????',
            2722 => '???????????????????? ??????????????',
            2723 => '?????????????????? ????',
            2724 => '?????????????????????????? ??????????????',
            2725 => '???????????????????????? ??????????????',
            2726 => '?????????????????? ????????',
            2727 => '???????????????????????? ??????????????',
            2728 => '?????????????????? ????',
            2729 => '???????????????? ??????????????',
            2730 => '???????????????? ??????????????',
            2731 => '?????????????????? ????????????????????',
            2732 => '?????????????????????? ??????????????',
            2733 => '??????????????????-??????????c??????',
            2734 => '?????????????????????????? ????????',
            2735 => '?????????????????????????? ??????????????',
            2736 => '???????????????????? ????????????????',
            2737 => '?????????????????????? ??????????????',
            2738 => '???????????????????? ??????????',
            2739 => '?????????????????? ????????????????????',
            2740 => '?????????????????? ??????????????',
            2741 => '???????????????????? ??????????????',
            2742 => '???????????????????? ????????????????????',
            2743 => '?????????????????????????????? ??????????????',
            2744 => '?????????????????? ??????????????',
            2746 => '???????????????????? ??????????????????',
            2747 => '?????????????????????? ??????????????',
            2748 => '?????????????????????? ????????',
            2749 => '??????????-???????????????????? ???? - ????????',
            2750 => '?????????????????????? ??????????????',
            2751 => '?????????????????????????? ????????',
            2752 => '???????????????????????? ????????',
            2754 => '???????????????????? ??????????????',
            2755 => '?????????????? ??????????????',
            2756 => '???????????????????? ????????',
            2757 => '???????????????? ??????????????',
            2758 => '?????????????????????? ??????????????',
            2759 => '???????????????????? ????????????????',
            2760 => '???????????????????? ????????????',
            2761 => '????????????',
            2762 => '???????????????????? ??????????????',
            2763 => '???????????????????? ??????????????????-????????????????',
            2764 => '???????????????? ????',
            2765 => '???????????????????? ??????????????????',
            2766 => '?????????????????????????? ??????????????',
            2767 => '???????????????????????? ??????????????',
            2768 => '?????????????????????????? ??????????????',
            2769 => '???????????? ??????????????',
            2770 => '?????????????????? ??????????????',
            2771 => '???????????????????????? ??????????????',
            2773 => '???????????????????? ??????????????',
            2774 => '???????????????? ????????',
            2775 => '???????????????????? ????????',
            2776 => '???????????????????? ??????????????',
            2777 => '?????????????????? ??????????????',
            2778 => '???????????????????? ??????????????',
            2779 => '?????????????????? ??????????????',
            2780 => '??????????-???????????????? ????',
            2781 => '?????????????????? ??????????????',
            2782 => '???????????????????? ????????????????',
            2783 => '?????????????????????? ??????????????',
            2784 => '???????????????????? ??????????????',
            2785 => '??????????-??????????????????',
            2786 => '???????????????????????????? ????????',
            2787 => '???????????????????? ????????',
            2788 => '???????????????????? ??????????????',
            2789 => '?????????????? ??????????????',
            2790 => '???????????????? ??????????????',
            2792 => '???????????????? ??????????????',
            2793 => '?????????????????? ??????????????',
            2794 => '???????????????????? ????????????????????????',
            2795 => '?????????????????????? ??????????????',
            2796 => '???????????????????? ??????????????',
            2798 => '???????????????????? ???????????????? ????????????',
            2799 => '???????????????????????? ??????????????',
            2800 => '???????????????????? ????????',
            2801 => '?????????????????????????? ??????????????',
            2802 => '?????????????????????? ??????????????',
            2803 => '?????????????????????? ??????????????',
            2804 => '?????????????????? ??????????????',
            2805 => '???????????????????? ????????',
            2806 => '?????????????????????? ??????????????',
            2807 => '???????????????????????? ??????????????',
            2808 => '???????????????????? ?????????? ????',
            3483 => '???????????????????? ????????',           
            3498 => '??????????????????????'
        );
    }

    public function getUaZones() {
        return array(
            3480 => '???????????????????? ??????????????',
            3481 => '???????????????????????? ??????????????',
            3482 => '?????????????????????? ??????????????',
            3483 => '???????????????????? ???????????????????? ????????',
            3484 => '???????????????????????????????? ??????????????',
            3485 => '???????????????? ??????????????',
            3486 => '??????????-?????????????????????? ??????????????',
            3487 => '?????????????????????? ??????????????',
            3488 => '?????????????????????? ??????????????',
            3489 => '???????????????????????????? ??????????????',
            3490 => '????????',
            3491 => '???????????????? ??????????????',
            3492 => '?????????????????? ??????????????',
            3493 => '?????????????????? ??????????????',
            3494 => '???????????????????????? ??????????????',
            3495 => '???????????????? ??????????????',
            3496 => '???????????????????? ??????????????',
            3497 => '???????????????????? ??????????????',
            3498 => '??????????????????????',
            3499 => '?????????????? ??????????????',
            3500 => '?????????????????????????? ??????????????',
            3501 => '?????????????????? ??????????????',
            3502 => '?????????????????? ??????????????',
            3503 => '???????????????????????? ??????????????',
            3504 => '?????????????????????? ??????????????',
            3505 => '?????????????????????? ??????????????',
            3970 => '???????????????????? ??????????????'
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