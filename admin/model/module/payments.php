<?php 
class ModelModulePayments extends Model {

    public function getAllPaymentNames() {
        $this->load->model('extension/extension');
        $results = $this->model_extension_extension->getInstalled('payment');

        $payments = array();
        $arr_filter_code = array();

        if (count($results) > 0) {
            foreach ($results as $result) {
                $modules = $this->config->get($result . '_module');

                $this->load->language('payment/' . $result);

                //print_r($modules);
                if (isset($modules) and is_array($modules)) {
                    foreach ($modules as $k => $module) {
                        if (isset($module['title'])) {
                            if ($result == 'transfer_plus') {
                                $real_module_name = $this->language->get('heading_title') . ' - ';
                            } else {
                                $real_module_name = '';
                            }


                            if (!in_array($result . '.' . $k, $arr_filter_code)) {
                                if (is_array($module['title'])) {
                                    $payments[] = array('payment_code' => $result . '.' . $k,
                                        'code' => $result,
                                        'name' => $real_module_name . $module['title'][$this->config->get('config_language_id')]);
                                } else {
                                    $payments[] = array('payment_code' => $result . '.' . $k,
                                        'code' => $result,
                                        'name' => $real_module_name . $module['title']);
                                }

                                $arr_filter_code[] = $result . '.' . $k;
                            }
                        }
                    }
                } elseif ($result == 'yamodule') {
                    $types = array(
                        array('key' => 'PC', 'config' => 'wallet', 'text' => 'ym'),
                        array('key' => 'AC', 'config' => 'card', 'text' => 'cards'),
                        array('key' => 'GP', 'config' => 'terminal', 'text' => 'cash'),
                        array('key' => 'MC', 'config' => 'mobile', 'text' => 'mobile'),
                        array('key' => 'WM', 'config' => 'wm', 'text' => 'wm'),
                        array('key' => 'SB', 'config' => 'sber', 'text' => 'sber'),
                        array('key' => 'AB', 'config' => 'alfa', 'text' => 'alfa'),
                        array('key' => 'MA', 'config' => 'ma', 'text' => 'ma'),
                        array('key' => 'PB', 'config' => 'pb', 'text' => 'pb'),
                        array('key' => 'QW', 'config' => 'qp', 'text' => 'qp'),
                        array('key' => 'QP', 'config' => 'qw', 'text' => 'qw')
                    );

                    $_['text_method_PC'] = 'Оплата из кошелька в Яндекс.Деньгах';
                    $_['text_method_WM'] = 'Оплата из кошелька в системе WebMoney';
                    $_['text_method_MC'] = 'Платеж со счета мобильного телефона';
                    $_['text_method_AC'] = 'Оплата с произвольной банковской карты';
                    $_['text_method_GP'] = 'Оплата наличными через кассы и терминалы';
                    $_['text_method_SB'] = 'Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн';
                    $_['text_method_AB'] = 'Оплата через Альфа-Клик';
                    $_['text_method_MA'] = 'Оплата через MasterPass';
                    $_['text_method_PB'] = 'Оплата через Промсвязьбанк';
                    $_['text_method_QW'] = 'Оплата через QIWI Wallet';
                    $_['text_method_QP'] = 'Оплата через доверительный платеж (Куппи.ру)';


                    foreach ($types as $type) {
                        if (!in_array('yamodule.' . $type['key'], $arr_filter_code)) {
                            $payments[] = array(
                                'code' => $result,
                                'payment_code' => 'yamodule.' . $type['key'],
                                'name' => $_['text_method_' . $type['key']]
                            );

                            $arr_filter_code[] = 'yamodule.' . $type['key'];
                        }
                    }

                } elseif ($result == 'weight') {
                    $this->load->model('localisation/geo_zone');

                    $geo_zones = $this->model_localisation_geo_zone->getGeoZones();

                    if (count($geo_zones) > 0) {
                        foreach ($geo_zones as $geo_zone) {
                            if (!in_array($result . '.weight_' . $geo_zone['geo_zone_id'], $arr_filter_code)) {
                                $payments[] = array(
                                    'code' => $result,
                                    'payment_code' => $result . '.weight_' . $geo_zone['geo_zone_id'],
                                    'name' => 'Доставка в зависимости от веса - ' . $geo_zone['name']
                                );

                                $arr_filter_code[] = $result . '.weight_' . $geo_zone['geo_zone_id'];
                            }
                        }
                    }
                } else {
                    if ($this->language->get('heading_title')) {
                        $heading_title = $this->language->get('heading_title');
                    } elseif ($this->language->get('text_title')) {
                        $heading_title = $this->language->get('text_title');
                    } else {
                        $heading_title = 'No name!';
                    }

                    if (!in_array($result, $arr_filter_code)) {
                        $payments[] = array('payment_code' => $result, 'code' => $result,
                            'name' => $heading_title);
                        $arr_filter_code[] = $result;
                    }

                }
            }
        }

        return $payments;
    }
}
?>