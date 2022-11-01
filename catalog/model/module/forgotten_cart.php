<?php
class ModelModuleForgottenCart extends Model {
    public function getSetting() {
        $setting_data = array();
        $messages = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `code` = 'forgotten_cart'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $setting_data[$result['key']] = $result['value'];
            } else {
                $ver_arr = explode(".", VERSION);

                if ($ver_arr['1'] == 0) {
                    $setting_data[$result['key']] = unserialize($result['value']);
                } else {
                    $setting_data[$result['key']] = json_decode($result['value'], true);
                }
            }
        }

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "forgotten_cart_messages`");

        foreach ($query->rows as $message) {
            $language_id = $message['language_id'];
            $code = $message['code'];

            $messages[$language_id][$code] = $message['message'];
        }

        $setting_data['forgotten_cart_messages'] = $messages;

        return $setting_data;
    }

    public function updateSettings($data) {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "', serialized = '0'  WHERE `code` = 'forgotten_cart' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '0'");
            } else {
                $ver_arr = explode(".", VERSION);

                if($ver_arr['1'] == 0) {
                    $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1' WHERE `code` = 'forgotten_cart' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '0'");
                } else {
                    $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1' WHERE `code` = 'forgotten_cart' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '0'");
                }
            }
        }
    }
    
    public function updateCustomer($customer_id, $language_id, $currency_code, $cart, $last_page) {
        $query = $this->db->query("SELECT customer_id, cart, mail_status, manager_notifi, last_activity, start_session_time FROM `" . DB_PREFIX . "customer_forgotten_cart` WHERE customer_id = '" . (int)$customer_id . "'");

        if (isset($query->row['customer_id'])) {
            $start_session_time = $query->row['start_session_time'];
            $mail_status = 0;
            $manager_notifi = 0;
            if (($query->row['mail_status'] || $query->row['manager_notifi']) && $query->row['cart'] == json_encode($cart)) {
                $mail_status = $query->row['mail_status'];
                $manager_notifi = $query->row['manager_notifi'];
            }
            
            if (((int)$query->row['last_activity']+1800) < time()) {
                $start_session_time = time();
            }
            
            $this->db->query("UPDATE `" . DB_PREFIX . "customer_forgotten_cart` SET last_activity = '" . (int)time() . "', start_session_time = '" . (int)$start_session_time . "', language_id = '" . (int)$language_id . "', currency_code = '" . $this->db->escape($currency_code) . "', mail_status = '" . (int)$mail_status . "', manager_notifi = '" . (int)$manager_notifi . "', `cart` = '" . $this->db->escape(json_encode($cart)) . "', `last_page` = '" . $this->db->escape($last_page) . "' WHERE customer_id = '" . (int)$customer_id . "'");
        } else {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_forgotten_cart` SET customer_id = '" . (int)$customer_id . "', language_id = '" . (int)$language_id . "', currency_code = '" . $this->db->escape($currency_code) . "', start_session_time = '" . (int)time() . "', last_activity = '" . (int)time() . "', mail_status = '0', manager_notifi = '0', `cart` = '" . $this->db->escape(json_encode($cart)) . "', `last_page` = '" . $this->db->escape($last_page) . "'");
        }
    }
    
    public function getCustomer($customer_id = 0, $data) {
        $sql = "SELECT cfc.customer_id, cfc.mail_status, cfc.manager_notifi, cfc.language_id, cfc.currency_code, cfc.cart, cfc.start_session_time, cfc.last_activity, cfc.last_page, c.firstname, c.lastname, c.email, c.telephone, c.customer_group_id FROM `" . DB_PREFIX . "customer_forgotten_cart` cfc LEFT JOIN `" . DB_PREFIX . "customer` c ON(cfc.customer_id = c.customer_id) WHERE";
        
        if ($customer_id) {
            $sql .= " cfc.customer_id = '" . (int)$customer_id . "' AND";
        } else {
            $manager_notifi_sql = "";
            if ($data['manager_notifi']) {
                $manager_notifi_sql = "OR (cfc.last_activity <= '" . (int)(time() - $data['manager_notifi_time']) . "' AND cfc.manager_notifi = '0')";
            }
            
            if ($data['customer_notifi']) {
                if ($data['repeated_message']){
                    $sql .= " ((cfc.last_activity <= '" . (int)(time() - $data['time']) . "' AND cfc.mail_status = '0') OR (cfc.last_activity <= '" . (int)(time() - $data['repeated_time']) . "' AND cfc.mail_status = '1') " . $manager_notifi_sql . ") AND";
                } else {
                    $sql .= " (cfc.last_activity <= '" . (int)(time() - $data['time']) . "' AND cfc.mail_status = '0' " . $manager_notifi_sql . ") AND";
                }
            } elseif($data['manager_notifi']) {
                $sql .= " cfc.last_activity <= '" . (int)(time() - $data['manager_notifi_time']) . "' AND cfc.manager_notifi = '0' AND";
            } else {
                return false;
            }
        }
        
        $sql .= " cfc.cart <> '"  . $this->db->escape(json_encode(array())) . "' AND cfc.cart <> '' AND cfc.cart IS NOT NULL";
        
        if (!$customer_id) {
            $sql .= " ORDER BY cfc.last_activity ASC LIMIT 1";
        }
        
        $query = $this->db->query($sql);
        
        return $query->row;
    }
    
    public function addCoupon($customer_id, $data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET `name` = 'Forgotten Cart', `code` = '" . $this->db->escape($data['code']) . "', `discount` = '" . (float)$data['discount'] . "', `type` = '" . $this->db->escape($data['type']) . "', `total` = '0', `logged` = '1', `shipping` = '" . (int)$data['shipping'] . "', `date_start` = '" . $this->db->escape(date("Y-m-d")) . "', `date_end` = '" . $this->db->escape(date("Y-m-d", strtotime("+4 week"))) . "', `uses_total` = '1', `uses_customer` = '1', `status` = '1', `date_added` = NOW()");

        $coupon_id = $this->db->getLastId();

        if (isset($data['coupon_product'])) {
            foreach ($data['coupon_product'] as $product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
            }
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_forgotten_cart SET coupon_id = '" . (int)$coupon_id . "', customer_id = '" . (int)$customer_id . "'");
        
        return $coupon_id;
    }
    
    public function updateCoupon($customer_id, $coupon_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "coupon SET name = 'Forgotten Cart', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '0', logged = '1', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape(date("Y-m-d")) . "', date_end = '" . $this->db->escape(date("Y-m-d", strtotime("+2 week"))) . "', uses_total = '1', uses_customer = '1', status = '1', date_added = NOW() WHERE coupon_id = '" . (int)$coupon_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
        if (isset($data['coupon_product'])) {
            foreach ($data['coupon_product'] as $product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
            }
        }
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_forgotten_cart WHERE coupon_id = '" . (int)$coupon_id . "'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_forgotten_cart SET coupon_id = '" . (int)$coupon_id . "', customer_id = '" . (int)$customer_id . "'");
    }
    
    public function check_coupon($code) {
        $query = $this->db->query("SELECT coupon_id FROM " . DB_PREFIX . "coupon WHERE code = '" . $this->db->escape($code) . "'");
        if (isset($query->row['coupon_id'])) {
            return false;
        } else {
            return true;
        }
    }
    
    public function get_old_coupon($customer_id) {
        $query = $this->db->query("SELECT cfc.coupon_id, c.code FROM " . DB_PREFIX . "coupon_forgotten_cart cfc LEFT JOIN " . DB_PREFIX . "coupon c ON(cfc.coupon_id = c.coupon_id) WHERE cfc.customer_id = '" . (int)$customer_id . "' AND cfc.coupon_id NOT IN (SELECT ch.coupon_id FROM " . DB_PREFIX . "coupon_history ch WHERE ch.coupon_id = cfc.coupon_id) LIMIT 1");

        if ($query->row) {
            return $query->row;
        } else {
            $query = $this->db->query("SELECT cfc.coupon_id FROM " . DB_PREFIX . "coupon_forgotten_cart cfc LEFT JOIN " . DB_PREFIX . "coupon c ON(cfc.coupon_id = c.coupon_id) WHERE c.date_end < '" . $this->db->escape(date("Y-m-d")) . "' OR c.status = '0' OR cfc.coupon_id IN (SELECT ch.coupon_id FROM " . DB_PREFIX . "coupon_history ch WHERE ch.coupon_id = cfc.coupon_id) LIMIT 1");

            if ($query->row) {
                return $query->row;
            }
        }

        return array();
    }
    
    public function getMessages($language_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "forgotten_cart_messages` WHERE language_id = '" . (int)$language_id . "'");
        
        $messages = array();
        foreach ($query->rows as $message) {
            $code = $message['code'];
            
            $messages[$code] = $message['message'];
        }
        
        return $messages;
    }
    
    public function confirm_mail($customer_id, $mail_type, $manager_notifi) {
        $this->db->query("UPDATE `" . DB_PREFIX . "customer_forgotten_cart` SET mail_status = '" . (int)$mail_type . "', manager_notifi = '" . (int)$manager_notifi . "' WHERE customer_id = '" . (int)$customer_id . "'");
    }
    
    public function delete_cart($customer_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "customer_forgotten_cart` WHERE customer_id = '" . (int)$customer_id . "'");
    }
    
    public function sendMail($to, $from, $sender, $subject, $message) {
        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($to);
        $mail->setFrom($from);
        $mail->setSender(html_entity_decode($sender, ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml($message);
        $mail->send();
    }
    
    public function getRelatedProducts($product_id, $product_options, $price, $language_id, $attributes, $attribute_condition, $options, $option_condition, $price_step, $limit) {
        $attributes_sql = "";
        $options_sql = "";
        
        if ($attributes) {
            $query = $this->db->query("SELECT attribute_id, text FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "' AND attribute_id IN (" . implode(",", $attributes) . ")");
            
            if ($query->rows) {
                $attribute_sql = array();
                foreach ($query->rows as $attribute) {
                    $attribute_sql[] = "(pa.attribute_id = '" . (int)$attribute['attribute_id'] . "' AND  pa.text LIKE '" . $this->db->escape($attribute['text']) . "')";
                }
                
                if ($attribute_condition) {
                    $attributes_sql = "p.product_id IN (SELECT pa.product_id FROM " . DB_PREFIX . "product_attribute pa WHERE pa.product_id <> '" . (int)$product_id . "' AND (" . implode(" OR ", $attribute_sql) . "))";
                } elseif(count($query->rows) == count($attributes)) {
                    $attributes_sql = "p.product_id IN (SELECT pa.product_id FROM " . DB_PREFIX . "product_attribute pa WHERE pa.product_id <> '" . (int)$product_id . "' AND (" . implode(" AND ", $attribute_sql) . "))";
                }
            }
        }
        
        if ($options) {
            $option_sql = array();
            foreach ($product_options as $option_key => $product_option) {
                $key_exp = explode(':', $option_key);

                if (in_array($key_exp[0], $options)) {
                    $option_sql[] = "(pov.option_id = '" . (int)$key_exp[0]. "' AND  pov.option_value_id = '" . (int)$key_exp[1] . "')";
                }
            }
            
            if ($option_condition && $option_sql) {
                $options_sql = "p.product_id IN (SELECT pov.product_id FROM " . DB_PREFIX . "product_option_value pov WHERE pov.product_id <> '" . (int)$product_id . "' AND (" . implode(" OR ", $option_sql) . "))";
            } elseif(count($option_sql) == count($options)) {
                $options_sql = "p.product_id IN (SELECT pov.product_id FROM " . DB_PREFIX . "product_option_value pov WHERE pov.product_id <> '" . (int)$product_id . "' AND (" . implode(" AND ", $option_sql) . "))";
            }
        }
        
        if (!$attributes_sql && !$options_sql) {
            return array();
        }
        
        $sql = "SELECT DISTINCT *, pd.name AS name, p.image, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id <> '" . (int)$product_id . "' AND pd.language_id = '" . (int)$language_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND (";
        
        if ($attributes_sql) {
            $sql .= $attributes_sql;
        }

        if ($attributes_sql && $options_sql) {
            $sql .= " OR ";
        }

        if ($options_sql) {
            $sql .= $options_sql;
        }
        
        $sql .=")";
        
        if ($price_step) {
            $min_price = $price-$price_step;
            $max_price = $price+$price_step;
            $sql .= " AND p.price >= '" . (float)$min_price . "' AND  p.price <= '" . (float)$max_price . "'";
        }
        
        $sql .= " ORDER BY RAND() LIMIT 0," . (int)$limit;
        
        $query = $this->db->query($sql);
        
        $products = array();
        
        foreach ($query->rows as $product) {
            $products[ (int)$product['product_id'] ] = array(
                'product_id'   => $product['product_id'],
                'model'        => $product['model'],
                'name'         => $product['name'],
                'image'        => $product['image'],
                'price'        => $product['price'],
                'special'      => $product['special'],
                'tax_class_id' => $product['tax_class_id']
            );
        }
        
        return $products;
    }
}
