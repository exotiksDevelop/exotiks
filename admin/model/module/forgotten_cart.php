<?php
class ModelModuleForgottenCart extends Model {
    public function editSetting($data) {
        $this->load->model('setting/setting');

        $messages = $data['forgotten_cart_messages'];
        unset($data['forgotten_cart_messages']);

        $this->db->query("DELETE FROM `" . DB_PREFIX . "forgotten_cart_messages`");

        foreach ($messages as $language_id => $message) {
            foreach ($message as $code => $text) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "forgotten_cart_messages` SET language_id = '" . (int)$language_id . "', code = '" . $this->db->escape($code) . "', message = '" . $this->db->escape($text) . "'");
            }
        }

        $this->model_setting_setting->editSetting('forgotten_cart', $data);

        $this->db->query("DELETE FROM " . DB_PREFIX . "layout_module WHERE code = 'forgotten_cart'");
        
        $query = $this->db->query("SELECT layout_id FROM " . DB_PREFIX . "layout");
        foreach ($query->rows as $layout) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "layout_module SET layout_id = '" . (int)$layout['layout_id'] . "', code = 'forgotten_cart', position = 'content_top', sort_order = '0'");
        }
    }
    
    public function getCustomers($start = 0, $limit = 0) {
        $sql = "SELECT DISTINCT SQL_CALC_FOUND_ROWS cfc.customer_id, cfc.last_activity, cfc.cart, c.firstname, c.lastname, c.email, c.telephone, cfc.mail_status, cfc.last_page, cfc.start_session_time, cfc.manager_notifi FROM `" . DB_PREFIX . "customer_forgotten_cart` cfc LEFT JOIN `" . DB_PREFIX . "customer` c ON(cfc.customer_id = c.customer_id) WHERE cfc.last_activity <= '" . (int)(time() - 900) . "' AND cfc.cart <> '"  . $this->db->escape(json_encode(array())) . "' AND cfc.cart <> '' AND cfc.cart IS NOT NULL ORDER BY IF(cfc.mail_status<2, 0, 1) ASC, cfc.last_activity DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$start . "," . (int)$limit;
        }
        
        $query = $this->db->query($sql);
        
        $num_query = $this->db->query("SELECT FOUND_ROWS() AS `found_rows`");
        $this->config->set('FOUND_ROWS', intval($num_query->row['found_rows']));
        
        return $query->rows;
    }

    public function getCustomersTotal() {
        return $this->config->get('FOUND_ROWS');
    }
    
    public function updateCustomer($customer_id, $status = 1) {
        $this->db->query("UPDATE `" . DB_PREFIX . "customer_forgotten_cart` SET mail_status = '" . (int)$status . "' WHERE customer_id = '" . (int)$customer_id . "'");
    }

    public function delete_cart($customer_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "customer_forgotten_cart` SET `mail_status` = '0', `cart` = '"  . $this->db->escape(json_encode(array())) . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
    }
    
    public function getMessages() {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "forgotten_cart_messages`");
        
        $messages = array();
        foreach ($query->rows as $message) {
            $language_id = $message['language_id'];
            $code = $message['code'];
            
            $messages[$language_id][$code] = $message['message'];
        }
        
        return $messages;
    }
    
    public function getAttributes($data = array()) {
        $sql = "SELECT a.attribute_id, ad.name, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sort_data = array(
            'ad.name',
            'attribute_group',
            'a.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY attribute_group, ad.name";
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

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }
    
    public function getRelatedAttributes($related_attributes) {
        $attributes = array();
        
        foreach ($related_attributes as $attribute_id) {
            $query = $this->db->query("SELECT a.attribute_id, ad.name, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE a.attribute_id = '" . (int)$attribute_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");
            
            if ($query->row) {
                $attributes[] = $query->row;
            }
        }
        
        return $attributes;
    }
    
    public function getOptions($data = array()) {
        $sql = "SELECT o.option_id, od.name FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND od.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sort_data = array(
            'od.name',
            'o.type',
            'o.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY od.name";
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

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }
    
    public function getRelatedOptions($related_options) {
        $attributes = array();
        
        foreach($related_options as $option_id) {
            $query = $this->db->query("SELECT o.option_id, od.name FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE o.option_id = '" . (int)$option_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
            
            if ($query->row) {
                $attributes[] = $query->row;
            }
        }
        
        return $attributes;
    }
    
    public function db_create() {
          $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "forgotten_cart_messages` (
            `language_id` int(11) NOT NULL,
            `code` varchar(32) NOT NULL,
            `message` text NOT NULL,
            PRIMARY KEY (`language_id`, `code`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
            
          $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customer_forgotten_cart` (
            `customer_id` int(11) NOT NULL,
            `language_id` int(11) NOT NULL,
            `currency_code` varchar(3) NOT NULL,
            `cart` text NOT NULL,
            `start_session_time` int(11) NOT NULL,
            `last_activity` int(11) NOT NULL,
            `last_page` text NOT NULL,
            `mail_status` tinyint(1) NOT NULL,
            `manager_notifi` tinyint(1) NOT NULL,
            PRIMARY KEY (`customer_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
            
          $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "coupon_forgotten_cart` (
            `coupon_id` int(11) NOT NULL,
            `customer_id` int(11) NOT NULL,
            PRIMARY KEY (`coupon_id`),
            INDEX (`customer_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
    }
    
    public function db_update() {
        $colums = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer_forgotten_cart` WHERE  `Field` =  'cart'");
        
        if ($colums->num_rows == 0) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_forgotten_cart` ADD `cart` text NOT NULL");
        }
        
        $colums = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer_forgotten_cart` WHERE  `Field` =  'manager_notifi'");
        
        if ($colums->num_rows == 0) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_forgotten_cart` ADD `manager_notifi` tinyint(1) NOT NULL");
        }
        
        $colums = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer_forgotten_cart` WHERE  `Field` =  'last_page'");
        
        if ($colums->num_rows == 0) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_forgotten_cart` ADD `last_page` text NOT NULL");
        }
        
        $colums = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer_forgotten_cart` WHERE  `Field` =  'start_session_time'");
        
        if ($colums->num_rows == 0) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_forgotten_cart` ADD `start_session_time` int(11) NOT NULL");
        }

        $colums = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "coupon_forgotten_cart` WHERE  `Field` =  'status'");

        if ($colums->num_rows != 0) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "coupon_forgotten_cart` DROP COLUMN `status`");
        }

        $colums = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "coupon_forgotten_cart` WHERE  `Field` =  'customer_id'");

        if ($colums->num_rows == 0) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "coupon_forgotten_cart` ADD `customer_id` int(11) NOT NULL");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "coupon_forgotten_cart` ADD INDEX(`customer_id`)");
        }
    }
}