<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

class ModelModuleSimpleCustom extends Model {
    static $_objects = array(
        'order'    => 1,
        'customer' => 2,
        'address'  => 3
    );

    static $_fields;

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

    private function getFieldValues($object, $objectId, $set, $fieldSettings, $langCode) {
        if (!empty($fieldSettings['values']['source']) && $fieldSettings['values']['source'] == 'saved' && !empty($fieldSettings['values']['saved'])) {
            $valuesText = !empty($fieldSettings['values']['saved'][$langCode]) ? $fieldSettings['values']['saved'][$langCode] : '';

            return $this->convertValues($valuesText);
        }

        if (!empty($fieldSettings['values']['source']) && $fieldSettings['values']['source'] == 'model' && !empty($fieldSettings['values']['method'])) {
            $method = $fieldSettings['values']['method'];

            $filter = '';
            if (!empty($fieldSettings['values']['filter'])) {
                $info = $this->getObjectInfo($object, $objectId);

                if ($set == 'payment_address' && isset($info['payment_'.$fieldSettings['values']['filter']])) {
                    $filter = $info['payment_'.$fieldSettings['values']['filter']];
                } elseif ($set == 'shipping_address' && isset($info['shipping_'.$fieldSettings['values']['filter']])) {
                    $filter = $info['shipping_'.$fieldSettings['values']['filter']];
                } elseif (isset($info[$fieldSettings['values']['filter']])) {
                    $filter = $info[$fieldSettings['values']['filter']];
                }
            }

            return $method.'|'.$filter;
        }

        return array();
    }

    private function getObjectInfo($object, $objectId) {
        $opencartVersion = explode('.', VERSION);
        $opencartVersion = floatval($opencartVersion[0].$opencartVersion[1].$opencartVersion[2].'.'.(isset($opencartVersion[3]) ? $opencartVersion[3] : 0));

        if ($object == 'customer') {
            if ($opencartVersion < 210) {
                $this->load->model('sale/customer');
                $mainInfo = $this->model_sale_customer->getCustomer($objectId);
            } else {
                $this->load->model('customer/customer');
                $mainInfo = $this->model_customer_customer->getCustomer($objectId);
            }

            $customInfo = $this->getCustomFields('customer', $objectId, '', false);

            $mainInfo = is_array($mainInfo) ? $mainInfo : array();
            $customInfo = is_array($customInfo) ? $customInfo : array();

            return array_merge($customInfo, $mainInfo);
        } elseif ($object == 'address') {
            if ($opencartVersion < 210) {
                $this->load->model('sale/customer');
                $mainInfo = $this->model_sale_customer->getAddress($objectId);
            } else {
                $this->load->model('customer/customer');
                $mainInfo = $this->model_customer_customer->getAddress($objectId);
            }

            $customInfo = $this->getCustomFields('address', $objectId, '', false);

            $mainInfo = is_array($mainInfo) ? $mainInfo : array();
            $customInfo = is_array($customInfo) ? $customInfo : array();

            return array_merge($customInfo, $mainInfo);
        } elseif ($object == 'order') {
            $this->load->model('sale/order');

            $mainInfo = $this->model_sale_order->getOrder($objectId);
            $customInfo = $this->getCustomFields('order', $objectId, '', false);

            $mainInfo = is_array($mainInfo) ? $mainInfo : array();
            $customInfo = is_array($customInfo) ? $customInfo : array();

            return array_merge($customInfo, $mainInfo);
        }

        return array();
    }

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
            $langCode = $this->config->get('config_admin_language');
        }

        $langCode = trim(str_replace('-', '_', strtolower($langCode)), '.');

        return !empty(self::$_fields[$fieldId]['label'][$langCode]) ? self::$_fields[$fieldId]['label'][$langCode] : $fieldId;
    }

    public function getInfo($object, $id, $set, $langCode = '') {
        if ($this->checkFieldInOldFormat($id)) {
            $oldInfo = $this->getDataFromOldFormat($object, $id, $set);
            if (!empty($oldInfo)) {
                return $oldInfo;
            }
        }

        if (array_key_exists($object, self::$_objects)) {
            $this->loadFieldsSettings();

            if (empty($langCode)) {
                $langCode = $this->config->get('config_admin_language');
            }

            $langCode = trim(str_replace('-', '_', strtolower($langCode)), '.');

            $result = array();

            foreach (self::$_fields as $fieldSettings) {
                if ($object == 'order') {
                    if ($fieldSettings['object'] == 'address') {
                        if ($set == 'payment_address') {
                            $result['payment_'.$fieldSettings['id']] = array(
                                'label'  => !empty($fieldSettings['label'][$langCode]) ? $fieldSettings['label'][$langCode] : $fieldSettings['id'],
                                'id'     => 'payment_'.$fieldSettings['id'],
                                'type'   => $fieldSettings['type'],
                                'value'  => '',
                                'values' => $this->getFieldValues($object, $id, $set, $fieldSettings, $langCode)
                            );
                        }
                        if ($set == 'shipping_address') {
                            $result['shipping_'.$fieldSettings['id']] = array(
                                'label'  => !empty($fieldSettings['label'][$langCode]) ? $fieldSettings['label'][$langCode] : $fieldSettings['id'],
                                'id'     => 'shipping_'.$fieldSettings['id'],
                                'type'   => $fieldSettings['type'],
                                'value'  => '',
                                'values' => $this->getFieldValues($object, $id, $set, $fieldSettings, $langCode)
                            );
                        }
                    } else {
                        if ($set != 'payment_address' && $set != 'shipping_address') {
                            $result[$fieldSettings['id']] = array(
                                'label'  => !empty($fieldSettings['label'][$langCode]) ? $fieldSettings['label'][$langCode] : $fieldSettings['id'],
                                'id'     => $fieldSettings['id'],
                                'type'   => $fieldSettings['type'],
                                'value'  => '',
                                'values' => $this->getFieldValues($object, $id, $set, $fieldSettings, $langCode)
                            );
                        }
                    }
                } else {
                    if ($fieldSettings['object'] == $object) {
                        $result[$fieldSettings['id']] = array(
                            'label'  => !empty($fieldSettings['label'][$langCode]) ? $fieldSettings['label'][$langCode] : $fieldSettings['id'],
                            'id'     => $fieldSettings['id'],
                            'type'   => $fieldSettings['type'],
                            'value'  => '',
                            'values' => $this->getFieldValues($object, $id, $set, $fieldSettings, $langCode)
                        );
                    }
                }
            }

            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . $object . '_simple_fields` WHERE `'.$object.'_id` = \'' . $id . '\' LIMIT 1');

            foreach ($query->row as $key => $value) {
                if (isset($result[$key])) {
                    if ($result[$key]['type'] != 'checkbox') {
                        $result[$key]['value'] = $value;
                    } else {
                        $result[$key]['value'] = explode(',', $value);
                    }
                }
            };

            if (isset($query->row['metadata'])) {
                $metadata = explode(',', $query->row['metadata']);

                foreach ($result as $key => $info) {
                    if (!in_array($key, $metadata)) {
                        unset($result[$key]);
                    }
                }
            }

            return $result;
        }

        return array();
    }

    public function getInfoWithValues($object, $id, $set, $langCode = '') {
        $result = $this->getInfo($object, $id, $set, $langCode);

        foreach ($result as $key => $info) {
            if (($info['type'] == 'radio' || $info['type'] == 'select' || $info['type'] == 'checkbox') && !is_array($info['values'])) {
                $info['values'] = $this->getConnector($info['values']);
            }

            $result[$key] = $info;
        }

        return $result;
    }

    public function updateData($object, $id, $set, $post) {
        $text = array();

        $text[] = '`'.$object.'_id`=\''.$id.'\'';

        foreach ($post as $key => $value) {
            if (is_array($value)) {
                $tmp = array();
                foreach ($value as $v) {
                    if ($v !== '') {
                        $tmp[] = $v;
                    }
                }
                $value = implode(',', $tmp);
            }
            $text[] = '`'.$key.'`=\''.$value.'\'';
        }

        $text = implode(',', $text);

        $this->db->query('INSERT INTO `' . DB_PREFIX . $object . '_simple_fields` SET ' . $text . ' ON DUPLICATE KEY UPDATE ' . $text);
    }

    public function getDataFromOldFormat($object, $id, $set) {
        $object = !empty(self::$_objects[$object]) ? self::$_objects[$object] : 0;

        if (!$object || !$id) {
            return array();
        }

        $query = $this->db->query("SELECT DISTINCT data FROM simple_custom_data WHERE object_type = '" . (int)$object . "' AND object_id = '" . (int)$id . "'");

        $result = array();

        if ($query->num_rows) {
            $data = unserialize($query->row['data']);

            foreach ($data as $key => $item) {
                if (empty($item['set']) || (!empty($item['set']) && $item['set'] == $set)) {
                    $result[$key] = $item;
                }
            }
        }

        return $result;
    }

    public function getCustomFields($object, $objectId, $langCode = '', $loadFieldValues = true) {
        if (array_key_exists($object, self::$_objects)) {
            $this->loadFieldsSettings();

            if (empty($langCode)) {
                $langCode = $this->config->get('config_admin_language');
            }

            $langCode = trim(str_replace('-', '_', strtolower($langCode)), '.');
            $result = array();
            $fields = array();

            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . $object . '_simple_fields` WHERE `'.$object.'_id` = \'' . $objectId . '\' LIMIT 1');

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

            foreach ($result as $id => $value) {
                $value = isset($query->row[$id]) ? $query->row[$id] : '';

                $set = '';
                if (strpos($id, $fields[$id]['id'])) {
                    $set = str_replace($fields[$id]['id'], '', $id) . 'address';
                }

                if ($fields[$id]['type'] == 'radio' || $fields[$id]['type'] == 'select') {
                    $values = $loadFieldValues ? $this->getFieldValues($object, $objectId, $set, $fields[$id], $langCode) : array();
                    if (!is_array($values)) {
                        $values = $this->getConnector($values);
                    }
                    if ($loadFieldValues) {
                        $value = $value !== '' && isset($values[$value]) ? $values[$value] : '';
                    }
                } elseif ($fields[$id]['type'] == 'checkbox') {
                    $values = $loadFieldValues ? $this->getFieldValues($object, $objectId, $set, $fields[$id], $langCode) : array();

                    if (!is_array($values)) {
                        $values = $this->getConnector($values);
                    }

                    if ($loadFieldValues) {
                        $tmp = explode(',', $value);
                        $value = array();

                        foreach ($tmp as $v) {
                            $value[] = isset($values[$v]) ? $values[$v] : '';
                        }

                        $value = implode(', ', $value);
                    }
                }

                $result[$id] = $value;
            }

            return $result;
        }

        return array();
    }

    private function getConnector($params) {
        $tmp = explode('|', $params);

        $curl = curl_init(HTTP_CATALOG.'index.php?route=common/simple_connector&method='.$tmp[0].'&filter='.$tmp[1].'&custom=1');

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = json_decode(curl_exec($curl), false);

        $ret = array();

        foreach ($result as $info) {
            $ret[$info->id] = $info->text;
        }

        return $ret;
    }
}
class ModelExtensionModuleSimpleCustom extends ModelModuleSimpleCustom {
}