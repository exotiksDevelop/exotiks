<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

class ModelToolSimpleCustom extends Model {
    static $_objects = array(
        'order'    => 1,
        'customer' => 2,
        'address'  => 3
    );

    static $_fields;

    private function loadFieldsSettings() {
        if (empty(self::$_fields)) {
            $settings = @json_decode($this->config->get('simple_settings'), true);

            $result = array();

            if (!empty($settings['fields'])) {
                foreach ($settings['fields'] as $fieldSettings) {
                    if ($fieldSettings['custom']) {
                        $result[$fieldSettings['id']] = $fieldSettings;
                    }
                }
            }

            self::$_fields = $result;
        }

        return self::$_fields;
    }

    public function getFieldLabel($fieldId, $langCode = '') {
        $this->loadFieldsSettings();

        if (empty($langCode)) {
            $langCode = $this->config->get('config_language');
        }

        $langCode = trim(str_replace('-', '_', strtolower($langCode)), '.');

        return !empty(self::$_fields[$fieldId]['label'][$langCode]) ? self::$_fields[$fieldId]['label'][$langCode] : $fieldId;
    }

    public function getCustomFields($object, $id, $langCode = '', $loadFieldValues = true) {
        if ($this->checkFieldInOldFormat($id)) {
            $oldInfo = $this->getDataFromOldFormat($object, $id);
            if (!empty($oldInfo)) {
                return $oldInfo;
            }
        }

        if (array_key_exists($object, self::$_objects)) {
            $this->loadFieldsSettings();

            if (empty($langCode)) {
                $langCode = $this->config->get('config_language');
            }

            $langCode = trim(str_replace('-', '_', strtolower($langCode)), '.');
            $result = array();
            $fields = array();

            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . $object . '_simple_fields` WHERE `'.$object.'_id` = \'' . $id . '\' LIMIT 1');

            if (!$query->num_rows) {
                return array();
            }
            
            foreach (self::$_fields as $fieldSettings) {
                if ($fieldSettings['custom']) {
                    if ($object == 'order') {
                        if ($fieldSettings['object'] == 'address') {
                            $result['payment_'.$fieldSettings['id']] = '';
                            $result['shipping_'.$fieldSettings['id']] = '';

                            $fields['payment_'.$fieldSettings['id']] = $fieldSettings;
                            $fields['shipping_'.$fieldSettings['id']] = $fieldSettings;
                        } else {
                            $result[$fieldSettings['id']] = '';
                        }
                    } else {
                        if ($fieldSettings['object'] == $object) {
                            $result[$fieldSettings['id']] = '';
                        }
                    }

                    $fields[$fieldSettings['id']] = $fieldSettings;
                }
            }

            foreach ($result as $key => $value) {
                $value = isset($query->row[$key]) ? $query->row[$key] : '';

                if ($fields[$key]['type'] == 'radio' || $fields[$key]['type'] == 'select') {
                    $values = $loadFieldValues ? $this->getFieldValues($object, $id, $key, $fields[$key], $langCode) : array();
                    if ($loadFieldValues) {
                        $value = $value !== '' && isset($values[$value]) ? $values[$value] : '';
                    }
                } elseif ($fields[$key]['type'] == 'checkbox') {
                    $values = $loadFieldValues ? $this->getFieldValues($object, $id, $key, $fields[$key], $langCode) : array();

                    if ($loadFieldValues) {
                        $tmp = explode(',', $value);
                        $value = array();

                        foreach ($tmp as $v) {
                            $value[] = isset($values[$v]) ? $values[$v] : '';
                        }

                        $value = implode(', ', $value);
                    }
                }

                $result[$key] = $value;
            }

            return $result;
        }

        return array();
    }

    public function getDataFromOldFormat($object, $id) {
        $object = !empty(self::$_objects[$object]) ? self::$_objects[$object] : 0;

        if (!$object || !$id) {
            return array();
        }

        $query = $this->db->query("SELECT DISTINCT data FROM simple_custom_data WHERE object_type = '" . (int)$object . "' AND object_id = '" . (int)$id . "'");

        $result = array();

        if ($query->num_rows) {
            $data = unserialize($query->row['data']);

            foreach ($data as $key => $item) {
                $result[$key] = !empty($item['text']) ? $item['text'] : '';
            }
        }

        return $result;
    }

    private function checkFieldInOldFormat($id) {
        $query = $this->db->query("SHOW TABLES LIKE 'simple_custom_data'");

        if ($query->rows) {
            $query = $this->db->query("SELECT DISTINCT data FROM simple_custom_data WHERE object_id = '" . (int)$id . "'");

            $result = array();

            if ($query->num_rows) {
                return true;
            }
        }

        return false;
    }

    private function convertValues($text) {
        if (is_array($text)) {
            $result = array();

            foreach ($text as $item) {
                $result[$item['id']] = $item['text'];
            }

            return $result;
        } else {
            $result = array();
            $rows = explode(';', $text);

            foreach ($rows as $row) {
                $pair = explode('=', $row);
                if (count($pair) == 2) {
                    $result[trim($pair[0])] = trim($pair[1]);
                }
            }

            return $result;
        }
    }

    private function getFieldValues($object, $id, $fieldKey, $fieldSettings, $langCode) {
        if (!empty($fieldSettings['values']['source']) && $fieldSettings['values']['source'] == 'saved' && !empty($fieldSettings['values']['saved'])) {
            $valuesText = !empty($fieldSettings['values']['saved'][$langCode]) ? $fieldSettings['values']['saved'][$langCode] : '';

            return $this->convertValues($valuesText);
        }

        if (!empty($fieldSettings['values']['source']) && $fieldSettings['values']['source'] == 'model' && !empty($fieldSettings['values']['method'])) {
            $method = $fieldSettings['values']['method'];

            $filter = '';
            if (!empty($fieldSettings['values']['filter'])) {
                $info = $this->getObjectInfo($object, $id);
                if (strpos($fieldKey, 'payment_') === 0 && isset($info['payment_'.$fieldSettings['values']['filter']])) {
                    $filter = $info['payment_'.$fieldSettings['values']['filter']];
                } elseif (strpos($fieldKey, 'shipping_') === 0 && isset($info['shipping_'.$fieldSettings['values']['filter']])) {
                    $filter = $info['shipping_'.$fieldSettings['values']['filter']];
                } elseif (isset($info[$fieldSettings['values']['filter']])) {
                    $filter = $info[$fieldSettings['values']['filter']];
                }
            }

            $this->load->model('tool/simpleapicustom');

            if (method_exists($this->model_tool_simpleapicustom, $method) || property_exists($this->model_tool_simpleapicustom, $method) || (method_exists($this->model_tool_simpleapicustom, 'isExistForSimple') && $this->model_tool_simpleapicustom->isExistForSimple($method))) {
                $tmp = $this->model_tool_simpleapicustom->{$method}($filter);
                $values = array();

                foreach ($tmp as $info) {
                    $values[$info['id']] = $info['text'];
                }

                return $values;
            }
        }

        return array();
    }

    private function getObjectInfo($object, $id) {
        if ($object == 'customer') {
            $this->load->model('account/customer');

            $mainInfo = $this->model_account_customer->getCustomer($id);
            $customInfo = $this->getCustomFields('customer', $id, '', false);

            $mainInfo = is_array($mainInfo) ? $mainInfo : array();
            $customInfo = is_array($customInfo) ? $customInfo : array();

            return array_merge($customInfo, $mainInfo);
        } elseif ($object == 'address') {
            $this->load->model('account/address');

            $mainInfo = $this->model_account_address->getAddress($id);
            $customInfo = $this->getCustomFields('address', $id, '', false);

            $mainInfo = is_array($mainInfo) ? $mainInfo : array();
            $customInfo = is_array($customInfo) ? $customInfo : array();

            return array_merge($customInfo, $mainInfo);
        } elseif ($object == 'order') {
            $this->load->model('checkout/order');

            $mainInfo = $this->model_checkout_order->getOrder($id);
            $customInfo = $this->getCustomFields('order', $id, '', false);

            $mainInfo = is_array($mainInfo) ? $mainInfo : array();
            $customInfo = is_array($customInfo) ? $customInfo : array();

            return array_merge($customInfo, $mainInfo);
        }

        return array();
    }

    public function getAddressFormat() {
        if ($this->customer->isLogged()) {
            $settings = @json_decode($this->config->get('simple_settings'), true);

            $languageCode = trim(str_replace('-', '_', strtolower($this->config->get('config_language'))), '.');

            $version = explode('.', VERSION);
            $version = floatval($version[0].$version[1].$version[2].'.'.(isset($version[3]) ? $version[3] : 0));

            if ($version < 200) {
                $customerGroupId = $this->customer->getCustomerGroupId();
            } else {
                $customerGroupId = $this->customer->getGroupId();
            }

            if (!empty($settings['addressFormats'])) {
                if ($customerGroupId && $languageCode && isset($settings['addressFormats'][$customerGroupId]) && isset($settings['addressFormats'][$customerGroupId][$languageCode]) && $settings['addressFormats'][$customerGroupId][$languageCode]) {
                    return $settings['addressFormats'][$customerGroupId][$languageCode];
                }
            }
        }

        return '';
    }
}
?>