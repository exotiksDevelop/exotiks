<?php
class ModelShippingPochtaros extends Model {
    private $error = array();
    private $pretype = 'extension';
    private $type = 'shipping';
    private $name = 'pochtaros';
    private $common = array();
    private $methods = '';

    private function convertCurrency($cost, $invert = false, $invert2 = false, $toRUB = false) {

        if (version_compare(VERSION, '2.2.0.0', '<')) {
            $user_currency = $this->currency->getCode(); // User currency
        }
        else {
            $user_currency = $this->session->data['currency']; // User currency
        }

        $config_currency = $this->config->get('config_currency'); // Default site currency


        //echo "user_currency=".$user_currency."--- config_currency=".$config_currency;

        if ($user_currency == 'RUB' and $config_currency == 'RUB') {
            return $cost;
        }

        if ($toRUB == true) {
            return $local_cost = $this->currency->convert($cost, $config_currency, "RUB");
        }


        if ($user_currency == $config_currency and $invert2 == true) {
            return $cost;
        }

        if ($invert == false and $invert2 == true) {
            if ($user_currency == "RUB" and $user_currency != $config_currency) {
                return $local_cost = $this->currency->convert($cost, $config_currency, $user_currency);
            }

            if ($user_currency != 'RUB' and $user_currency == $config_currency) {
                return $local_cost = $this->currency->convert($cost, $user_currency, "RUB");
            }

            if ($user_currency != 'RUB' and $user_currency != $config_currency) {
                return $local_cost = $this->currency->convert($cost, $config_currency, $user_currency);
            }
        }
        elseif ($invert == false) {
            $local_cost = $this->currency->convert($cost, "RUB", $user_currency);
        }
        else {
            if ($user_currency != $config_currency) {
                $local_cost = $this->currency->convert($cost, "RUB", $user_currency);
                $local_cost = $this->currency->convert($local_cost, $user_currency, $config_currency);
            }
            else {
                $local_cost = $this->currency->convert($cost, "RUB", $config_currency);
            }
        }

        if (!isset($local_cost)) {
            $local_cost = $cost;
        }

        return $local_cost;                      
    }


    public function getQuote($address) {
        if (version_compare(VERSION, '2', '>=')) {
            $this->registry->set('louise170', new Louise170($this->registry));
            $this->registry->set($this->name, new Pochtaros($this->registry));
        }
        
        if (version_compare(VERSION, '2.3.0.0', '<'))  {
            $this->language->load($this->type . '/' . $this->name);
        }
        else {
            $this->language->load($this->pretype . '/' . $this->type . '/' . $this->name);
        }

        $this->methods = $this->pochtaros->getShippingMethods();

        $arr_bibb_text = array();
        
        $error = '';
        $method_data = array();

        if ((version_compare(VERSION, '3', '>=') and $this->config->get('shipping_'.$this->name.'_status') == true)
        or (version_compare(VERSION, '3', '<') and $this->config->get($this->name.'_status') == true)
        ) {
            $this->load->model('localisation/zone_dv');
            $this->load->model('localisation/country');

            if (!isset($this->session->data['currency'])) {
                $this->session->data['currency'] = $this->config->get('config_currency');
            }

            if (!isset($address['city'])) {
                $address['city'] = '';
            }

            if (!isset($address['country_id'])) {
                $address['country_id'] = 176;
            }

            if (!isset($address['iso_code_2'])) {
                $country_info = $this->model_localisation_country->getCountry($address['country_id']);
                if (isset($country_info['iso_code_2'])) {
                    $address['iso_code_2'] = $country_info['iso_code_2'];
                }
                else {
                    $address['iso_code_2'] = 'RU';
                }
            }

            if (is_array($this->methods) and count($this->methods) > 0) {
                $pochtaros_title_tab = $this->config->get($this->name . '_title_tab');
                foreach ($this->methods as $key => $method) {
                    if (isset($pochtaros_title_tab[$method['key']]) and !empty($pochtaros_title_tab[$method['key']])) {
                        $this->methods[$key]['title'] = $pochtaros_title_tab[$method['key']];
                    }
                    else {
                        $this->methods[$key]['title'] = $this->language->get('text_' . $method['key']);
                    }
                }
            }

            $show_product_groups = false;
            
            if (version_compare(VERSION, '2.3.0.0', '<'))  {
                if (file_exists(DIR_APPLICATION.'/controller/module/product_groups.php') ) {
                    $show_product_groups = true;
                    $this->load->model('module/product_groups');
                }
            }
            else {
                if (file_exists(DIR_APPLICATION.'/controller/extension/module/product_groups.php') ) {
                    $show_product_groups = true;
                    $this->load->model('extension/module/product_groups');
                }
            }
            
            $cart_products = $this->cart->getProducts();
            $total_products = $this->cart->countProducts();

            if (version_compare(VERSION, '2.2.0.0', '<'))  {
                $total_data = array();
                $order_total = 0;
                $taxes = $this->cart->getTaxes();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'total'");
                $order_totals = $query->rows;
                $sort_order = array();

                foreach ($order_totals as $k => $value) {
                    $sort_order[$k] = $this->config->get($value['code'] . '_sort_order');
                }
                array_multisort($sort_order, SORT_ASC, $order_totals);

                foreach ($order_totals as $ot) {
                    if ($ot['code'] != $this->type) {
                        if ($this->config->get($ot['code'] . '_status')) {
                            $this->load->model('total/' . $ot['code']);
                            $this->{'model_total_' . $ot['code']}->getTotal($total_data, $order_total, $taxes);
                        }
                    }

                    if ($ot['code'] == $this->config->get('pochtaros_total_value')) {
                        break;
                    }
                }
            }
            else {
                if (version_compare(VERSION, '3.0', '<')) {
                    $this->load->model('extension/extension');
                }
                else {
                    $this->load->model('setting/extension');
                }


                $totals = array();
                $taxes = $this->cart->getTaxes();
                $order_total = 0;

                $total_data = array(
                    'totals' => &$totals,
                    'taxes'  => &$taxes,
                    'total'  => &$order_total
                );


                $sort_order = array();

                if (version_compare(VERSION, '3.0', '<')) {
                    $results = $this->model_extension_extension->getExtensions('total');
                }
                else {
                    $results = $this->model_setting_extension->getExtensions('total');
                }

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                //print_r($results);

                foreach ($results as $ot) {
                    if (version_compare(VERSION, '3', '<')) {
                        if ($this->config->get($ot['code'] . '_status')) {
                            //echo $ot['code'] . '====' . 'model_total_' . $ot['code'] . "\n";

                            if (version_compare(VERSION, '2.3.0.0', '<')) {
                                $this->load->model('total/' . $ot['code']);
                                $this->{'model_total_' . $ot['code']}->getTotal($total_data);
                            } else {
                                $this->load->model('extension/total/' . $ot['code']);
                                $this->{'model_extension_total_' . $ot['code']}->getTotal($total_data);
                            }
                        }
                    }
                    else {
                        if ($this->config->get('total_'.$ot['code'] . '_status')) {
                            if ($ot['code'] != 'shipping') {
                                $this->load->model('extension/total/' . $ot['code']);
                                $this->{'model_extension_total_' . $ot['code']}->getTotal($total_data);
                            }
                        }
                    }


                    if ($ot['code'] == $this->config->get('pochtaros_total_value')) {
                        break;
                    }
                }
            }


            $total = $order_total;

            if (is_array($this->config->get('pochtaros_store')) and in_array((int)$this->config->get('config_store_id'), $this->config->get('pochtaros_store'))) {
                $status = true;
            } else {
                return $method_data;
            }


            if ($status) {
                if ($this->config->get('pochtaros_image')) {
                    $image = $this->config->get('pochtaros_image');
                } else {
                    $image = '';
                }

                $path1 = str_replace("/system/", "", DIR_SYSTEM);
                $path2 = str_replace($path1, "", DIR_IMAGE);

                if (!isset($address['country_id']) or (isset($address['country_id']) and $address['country_id'] == '')) {
                    $address['country_id'] = 176;
                }

                if (!isset($address['iso_code_2']) or (isset($address['iso_code_2']) and $address['iso_code_2'] == '')) {
                    $address['iso_code_2'] = "RU";
                }

                if (!isset($address['zone_id']) or (isset($address['zone_id']) and $address['zone_id'] == '')) {
                    $address['zone_id'] = $this->config->get('pochtaros_zone_id');

                    if ((int)$address['zone_id'] > 0) {
                        $this->load->model('localisation/zone');
                        $zone_info = $this->model_localisation_zone->getZone($address['zone_id']);

                        if (isset($zone_info['name'])) {
                            $address['zone'] = $zone_info['name'];
                        }
                    }
                }

                if (isset($address['zone_id']) and $address['zone_id'] != '' and (!isset($address['zone']) or $address['zone'] == ''))  {
                    if ((int)$address['zone_id'] > 0) {
                        $this->load->model('localisation/zone');
                        $zone_info = $this->model_localisation_zone->getZone($address['zone_id']);

                        if (isset($zone_info['name'])) {
                            $address['zone'] = $zone_info['name'];
                        }
                    }
                }
                
                $status = true;
            }
            else {
                return $method_data;
            }


            $weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('pochtaros_weight_class_id'));

            if ($status) {
                if ($this->config->get('pochtaros_pmid_weight') and (float)$this->config->get('pochtaros_pmid_weight') > 0) {
                    foreach ($cart_products as $product) {
                        if ($product['weight'] == 0) {
                            $weight += $this->config->get('pochtaros_pmid_weight')*$product['quantity'];
                        }
                    }
                }

                if ($weight == 0 and $this->config->get('pochtaros_mid_weight') and (float)$this->config->get('pochtaros_mid_weight') > 0) {
                    $weight += $this->config->get('pochtaros_mid_weight');
                }

                if ($this->config->get('pochtaros_upakovka') and (float)$this->config->get('pochtaros_upakovka') > 0) {
                    if ($this->config->get('pochtaros_upakovka_type') == '+') {
                        $weight += $this->config->get('pochtaros_upakovka');
                    } elseif ($this->config->get('pochtaros_upakovka_type') == '%') {
                        $plus_weight = $weight / 100 * $this->config->get('pochtaros_upakovka');
                        $weight += $plus_weight;
                    }
                }

                if ($this->config->get('pochtaros_min_weight') and $weight < $this->config->get('pochtaros_min_weight')) {
                    $status = false;
                }
                
                
                $fragmentation_weight = 0;
                $total_fragments = 1;
                $fragmentation_total = $total;

                if ($this->config->get('pochtaros_fragmentation') and $this->config->get('pochtaros_max_weight')
                    and $weight > $this->config->get('pochtaros_max_weight')) {
                    $fragmentation_weight = $this->weight->convert($this->config->get('pochtaros_max_weight'), $this->config->get('config_weight_class_id'), $this->config->get('pochtaros_weight_class_id'));
                    $total_fragments = ceil($weight/$this->config->get('pochtaros_max_weight'));
                    $fragmentation_total = $total/$total_fragments;
                }
                elseif ($this->config->get('pochtaros_max_weight') and $weight > $this->config->get('pochtaros_max_weight')) {
                    $status = false;
                }

                if ((float)$this->config->get('pochtaros_min_total') > 0 and $total < (float)$this->config->get('pochtaros_min_total')) {
                    $status = false;
                }

                if ((float)$this->config->get('pochtaros_max_total') > 0 and $total > (float)$this->config->get('pochtaros_max_total')) {
                    $status = false;
                }
            }

            if ($status == false) {
                return $method_data;
            }

            $cart_weight = $weight;

            if ($status) {
                if ($fragmentation_weight > 0) {
                    $local_weight = $fragmentation_weight;
                }
                else {
                    $local_weight = $weight;
                }

                $local_weight = $this->weight->convert($local_weight, $this->config->get('pochtaros_weight_class_id'), $this->config->get('config_weight_class_id'));

                $weight_name = trim($this->weight->getUnit($this->config->get('config_weight_class_id')));
                $weight_name = str_replace('.','',$weight_name);

                if (function_exists('mb_strtolower')) {
                    mb_strtolower($weight_name);
                } elseif (function_exists('strtolower')) {
                    strtolower($weight_name);
                }


                if ($weight_name == 'кг' or $weight_name == 'kg') {
                    $local_weight = $local_weight * 1000;
                }

                $region = array();
                $region['from'] = $this->config->get('pochtaros_city');
                $region['to'] = '';

                if (isset($address['postcode']) and $address['postcode'] != '') {
                    $region['to'] = trim($address['postcode']);

                    if (is_array($this->config->get('pochtaros_zipcode')) and count($this->config->get('pochtaros_zipcode')) > 0) {
                        foreach ($this->config->get('pochtaros_zipcode') as $zip) {
                            $arr_local_zip = explode(',',$zip['inzip']);
                            if (in_array($region['to'],$arr_local_zip)) {
                                $region['to'] = $zip['outzip'];
                                break;
                            }
                        }
                    }
                }
                elseif (isset($address['zone']) and $address['zone'] != '') {
                    $region['to'] = $address['zone'];

                    $new_region = $this->model_localisation_zone_dv->getZone($region);

                    if (isset($new_region['to']) and $new_region['to']) {
                        $region['to'] = $new_region['to'];
                    }
                }

                $from = urlencode($region['from']);

                if ($address['iso_code_2'] != 'RU' or $address['country_id'] != 176) {
                    $is_russia = false;
                }
                else {
                    $is_russia = true;
                }

                if (!empty($region['to']) and (($is_russia == true and ctype_digit($region['to']) and strlen($region['to']) == 6)
                        or ($is_russia == false) )
                ) {
                    $to = urlencode($region['to']);

                    $server = str_replace("http:", '', HTTP_SERVER);
                    $server = str_replace("https:", '', $server);
                    $server = str_replace("www.", '', $server);
                    $server = str_replace("/", '', $server);

                    if ($this->config->get('pochtaros_procent_price') == '' or $this->config->get('pochtaros_procent_price') == 100) {
                        $local_total = $fragmentation_total;
                    }
                    else {
                        $local_total = $fragmentation_total / 100 * $this->config->get('pochtaros_procent_price');
                    }

                    $arr_p = array();

                    $arr_active_methods = array();
                    if (is_array($this->config->get('pochtaros_mstatus')) and count($this->config->get('pochtaros_mstatus')) > 0) {
                        foreach ($this->config->get('pochtaros_mstatus') as $key => $value) {
                            if ($value[$this->config->get('config_language_id')] == 1) {
                                $arr_active_methods[] = $key;
                            }
                        }
                    }
                    //print_r($arr_active_methods);

                    if (count($arr_active_methods) > 0) {
                        foreach ($arr_active_methods as $key => $value) {
                            foreach ($this->methods as $k => $v) {
                                if ($v['key'] == $value) {
                                    $arr_p[] = $v['p'];
                                }
                            }
                        }
                    }

                    $arr_p = array_unique($arr_p);

                    $arr_data = array(
                        'address' => $address, 
                        'from' => $from, 
                        'to' => $to, 
                        'weight' => $local_weight, 
                        'local_total' => $this->convertCurrency($local_total, false, false, true), 
                        'server' => $server,
                        'co' => ($this->config->get('pochtaros_corp_tarif') == 1) ? 1 : 0,
                        'p' => implode(',', $arr_p)
                    );


                    if (
                        (isset($this->request->post['payment_method']) and $this->config->get('pochtaros_payment') != '' and $this->request->post['payment_method'] == $this->config->get('pochtaros_payment')) or 
                        (isset($this->request->post['payment_code']) and $this->config->get('pochtaros_payment') != '' and $this->request->post['payment_code'] == $this->config->get('pochtaros_payment'))     
                    ) {

                    } 
                    elseif (isset($this->session->data['payment_method']['code']) and $this->config->get('pochtaros_payment') != '' and $this->session->data['payment_method']['code'] == $this->config->get('pochtaros_payment') ) {

                    }
                    else {
                        $arr_data['local_total'] = 0;
                    }

                    
                    $Request = $this->model_localisation_zone_dv->getRequest($arr_data);

                    if ($total < (float)$this->config->get('pochtaros_total')) {
                        $error_text = html_entity_decode(sprintf($this->language->get('error_description'), $this->currency->format($this->tax->calculate($this->config->get('pochtaros_total'), $this->config->get($this->name . '_tax_class_id'), $this->config->get('config_tax')),$this->session->data['currency'],1), $this->currency->format($this->tax->calculate($this->config->get('pochtaros_total') - $total, $this->config->get($this->name . '_tax_class_id'), $this->config->get('config_tax')),$this->session->data['currency'],1)), ENT_QUOTES, 'UTF-8');
                    }

                    if (isset($error_text)) {
                        $error = $error_text;
                    }
                    else {
                        $error = false;
                    }

                    $arrResponse = array();

                    if ($error == false) {
                        $Response = $this->cache->get($this->name.'.' . md5($Request));
                        if (!$Response) {
                            $curl = curl_init();

                            curl_setopt($curl, CURLOPT_URL, $Request);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);

                            $Response = curl_exec($curl);

                            if ($Response === false) {
                                $curl_error_text = 'Curl error: ' . curl_error($curl);
                                mail($this->config->get('config_email'),'Ошибка соединения c API в модуле Почта России', $curl_error_text);
                            }
                            else {
                                $this->cache->set($this->name.'.' . md5($Request), $Response);
                            }

                            curl_close($curl);
                        }

                        if (isset($Response)) {
                            $arrResponse = $this->model_localisation_zone_dv->getResponse($Response);
                        }

                        if (isset($arrResponse['Status']) and $arrResponse['Status'] != 'OK') {
                            $error = $arrResponse['Message'] . '<br>' . html_entity_decode($this->language->get('error_postcode'));
                        }
                    }

                    //print_r($arrResponse);

                    $quote_data = array();

                    $active = false;

                    if (is_array($this->config->get('pochtaros_mstatus')) and count($this->config->get('pochtaros_mstatus')) > 0) {
                        foreach ($this->config->get('pochtaros_mstatus') as $val) {
                            if (isset($val[$this->config->get('config_language_id')]) and $val[$this->config->get('config_language_id')] == 1) {
                                $active = true;
                            }
                        }
                    }

                    if ($active == true and $error == false) {
                        $arr_cart_product_gabarit = array();
                        $arr_cart_product_gabarit_more = array();

                        if (count($cart_products) > 0) {
                            foreach ($cart_products as $value) {
                                if (isset($value['product_gabarit_id'])) {
                                    if (!in_array($value['product_gabarit_id'], $arr_cart_product_gabarit)) {
                                        $arr_cart_product_gabarit[] = $value['product_gabarit_id'];
                                    }

                                    if (!isset($arr_cart_product_gabarit_more[$value['product_gabarit_id']])) {
                                        $arr_cart_product_gabarit_more[$value['product_gabarit_id']] = $value['quantity'];
                                    } else {
                                        $arr_cart_product_gabarit_more[$value['product_gabarit_id']] += $value['quantity'];
                                    }
                                }
                            }
                        }

                        if ($this->config->get('pochtaros_gabarit')) {
                            $arr_pochtaros_gabarit = $this->config->get('pochtaros_gabarit');

                            $total_cart_gabarit = count($arr_cart_product_gabarit);
                        }

                        if (is_array($this->config->get('pochtaros_mstatus')) and count($this->config->get('pochtaros_mstatus')) > 0) {
                            foreach ($this->config->get('pochtaros_mstatus') as $key => $value) {
                                $local = '';

                                if (is_array($this->methods) and count($this->methods) > 0) {
                                    foreach ($this->methods as $val) {
                                        //print_r($value);
                                        if ($value[$this->config->get('config_language_id')] == 1 and $val['key'] == $key) {
                                            $local = $val;
                                            break;
                                        }
                                    }
                                }


                                if ($this->config->get('pochtaros_gabarit')) {
                                    $natsenka = false;

                                    $arr_intersect = array();
                                    if (isset($arr_pochtaros_gabarit[$key]) and count($arr_pochtaros_gabarit[$key]) > 0) {
                                        $arr_intersect = array_intersect($arr_pochtaros_gabarit[$key], $arr_cart_product_gabarit);
                                    }

                                    if (count($arr_intersect) < $total_cart_gabarit) {
                                        $local = '';
                                    }

                                    $discount = $this->config->get('pochtaros_discount');

                                    if ($local != '' and isset($discount[$key]['gabarit_id']) and isset($discount[$key]['gtotal'])) {
                                        if ($discount[$key]['gabarit_id'] > 0 and (int)$discount[$key]['gtotal'] > 0) {
                                            $total_in = 0;

                                            if (count($arr_cart_product_gabarit_more)>0) {
                                                foreach ($arr_cart_product_gabarit_more as $k => $v) {
                                                    if ($k == $discount[$key]['gabarit_id']) {
                                                        $total_in += $v;
                                                    }
                                                }
                                            }

                                            if ($total_in >= (int)$discount[$key]['gtotal']) {
                                                $natsenka = true;
                                            }
                                        }
                                    }
                                }


                                if ($show_product_groups == true and is_array($this->config->get($this->name.'_pgroups')) and
                                    count($this->config->get($this->name.'_pgroups')) > 0) {

                                    foreach ($this->config->get($this->name.'_pgroups') as $pgroup) {
                                        //print_r($pgroup);
                                        $pgroup_total = 0;

                                        if ( (isset($pgroup['key']) and count($pgroup['key']) > 0) and in_array($key, $pgroup['key'])
                                            and isset($pgroup['filter_group_id']) and $pgroup['filter_group_id'] > 0 ) {

                                            //echo $key.'--'.$pgroup['logic'];

                                            foreach ($cart_products as $product) {
                                                if (version_compare(VERSION, '2.3.0.0', '<')) {
                                                    $locals_in_group = $this->model_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                }
                                                else {
                                                    $locals_in_group = $this->model_extension_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                }

                                                if ($locals_in_group == true) {
                                                    $pgroup_total += $product['price']*$product['quantity'];
                                                }
                                            }

                                            if (!isset($pgroup['min_total'])) {
                                                $pgroup['min_total'] = 0;
                                            }

                                            if (!isset($pgroup['max_total'])) {
                                                $pgroup['max_total'] = 0;
                                            }

                                            //echo '$pgroup_total='.$pgroup_total."\n";
                                            
                                            if (($pgroup['min_total'] > 0 and $pgroup['max_total'] > 0 and $pgroup_total >= $pgroup['min_total'] and $pgroup_total < $pgroup['max_total']) or
                                                ($pgroup['min_total'] > 0 and $pgroup['max_total'] == 0 and $pgroup_total >= $pgroup['min_total']) or
                                                ($pgroup['max_total'] > 0 and $pgroup['min_total'] == 0 and $pgroup_total < $pgroup['max_total']) or
                                                ($pgroup['max_total'] == 0 and $pgroup['min_total'] == 0)) {

                                                $local_status = true;
                                            }
                                            else {
                                                $local_status = false;
                                                $local = '';
                                            }

                                            //echo $key.'=$local_status='.$local_status."\n\n\n";

                                            if ($local_status == true) {
                                                if ($pgroup['logic'] == 'all') {

                                                    $in_group = true;

                                                    if (count($cart_products) > 0) {
                                                        foreach ($cart_products as $product) {
                                                            if (version_compare(VERSION, '2.3.0.0', '<')) {
                                                                $locals_in_group = $this->model_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }
                                                            else {
                                                                $locals_in_group = $this->model_extension_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }

                                                            if ($locals_in_group == false) {
                                                                $in_group = false;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                elseif ($pgroup['logic'] == 'any') {
                                                    $in_group = false;

                                                    if (count($cart_products) > 0) {
                                                        foreach ($cart_products as $product) {
                                                            if (version_compare(VERSION, '2.3.0.0', '<')) {
                                                                $locals_in_group = $this->model_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }
                                                            else {
                                                                $locals_in_group = $this->model_extension_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }

                                                            if ($locals_in_group == true) {
                                                                $in_group = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                elseif ($pgroup['logic'] == 'spec_number') {
                                                    $in_group = false;

                                                    if (count($cart_products) > 0) {
                                                        $snum = 0;
                                                        $cproducts_local = array();

                                                        foreach ($cart_products as $product) {
                                                            if (version_compare(VERSION, '2.3.0.0', '<')) {
                                                                $locals_in_group = $this->model_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }
                                                            else {
                                                                $locals_in_group = $this->model_extension_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }
                                                            if ($locals_in_group == true) {
                                                                if (isset($cproducts_local[$product['product_id']])) {
                                                                    $cproducts_local[$product['product_id']] += $product['quantity'];
                                                                } else {
                                                                    $cproducts_local[$product['product_id']] = $product['quantity'];
                                                                }

                                                                $snum += $product['quantity'];
                                                            }
                                                        }

                                                        if ($snum > $pgroup['limit']) {
                                                            $in_group = true;
                                                        } else {
                                                            $in_group = false;
                                                        }
                                                    }
                                                }
                                                elseif ($pgroup['logic'] == 'spec_number2') {
                                                    $in_group = false;

                                                    if (count($cart_products) > 0) {
                                                        $snum = 0;
                                                        $cproducts_local = array();

                                                        foreach ($cart_products as $product) {
                                                            if (version_compare(VERSION, '2.3.0.0', '<')) {
                                                                $locals_in_group = $this->model_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }
                                                            else {
                                                                $locals_in_group = $this->model_extension_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }
                                                            if ($locals_in_group == true) {
                                                                if (isset($cproducts_local[$product['product_id']])) {
                                                                    $cproducts_local[$product['product_id']] += $product['quantity'];
                                                                } else {
                                                                    $cproducts_local[$product['product_id']] = $product['quantity'];
                                                                }

                                                                $snum += $product['quantity'];
                                                            }
                                                        }

                                                        if ($snum < $pgroup['limit']) {
                                                            $in_group = true;
                                                        } else {
                                                            $in_group = false;
                                                        }
                                                    }
                                                }
                                                else {
                                                    $in_group = true;

                                                    if (count($cart_products) > 0) {
                                                        foreach ($cart_products as $product) {
                                                            if (version_compare(VERSION, '2.3.0.0', '<')) {
                                                                $locals_in_group = $this->model_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }
                                                            else {
                                                                $locals_in_group = $this->model_extension_module_product_groups->isProductInGroup($product['product_id'], $pgroup['filter_group_id']);
                                                            }

                                                            if ($locals_in_group == true) {
                                                                $in_group = false;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }

                                                if ($in_group == false) {
                                                    $local = '';
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }


                                $arr_pochtaros_customer_group = $this->config->get('pochtaros_customer_group');

                                if (version_compare(VERSION, '2.0', '<'))  {
                                    $customer_id = (int)$this->customer->getCustomerGroupId();
                                }
                                else {
                                    $customer_id = (int)$this->customer->getGroupId();
                                }
                                
                                if (isset($local['name']) and
                                    (isset($arr_pochtaros_customer_group[$key]) and is_array($arr_pochtaros_customer_group[$key])) and in_array($customer_id, $arr_pochtaros_customer_group[$key]) and
                                    isset($arrResponse['Отправления'][$local['name']]['Тариф']) and $arrResponse['Отправления'][$local['name']]['Тариф'] > 0 and
                                    isset($arrResponse['Отправления'][$local['name']]['Доставка']) and $arrResponse['Отправления'][$local['name']]['Доставка'] > 0 and
                                    ($this->config->get('pochtaros_fragmentation') or (!$this->config->get('pochtaros_fragmentation') and
                                            isset($arrResponse['Отправления'][$local['name']]['ПредельныйВес']) and 
                                            $weight <= $arrResponse['Отправления'][$local['name']]['ПредельныйВес']))
                                ) {

                                    $price = $this->model_localisation_zone_dv->getPrice($arrResponse, $local);

                                    if (is_array($this->methods) and count($this->methods) > 0) {
                                        foreach ($this->methods as $loc_key => $loc_method) {
                                            if ($loc_method['key'] == $key) {
                                                $local['title_more'] = $loc_method['title'];
                                            }
                                        }
                                    }
                                    
                                    $price = $price*$total_fragments;

                                    if ($this->config->get('pochtaros_cost_type') == '+') {
                                        $price += (float)$this->config->get('pochtaros_cost');
                                    }
                                    elseif ($this->config->get('pochtaros_cost_type') == '%') {
                                        $plus_cost = $price / 100 * (float)$this->config->get('pochtaros_cost');
                                        $price += $plus_cost;
                                    }

                                    $arr_tara_price = $this->config->get('pochtaros_price');

                                    if ($total_fragments > 1) {
                                        $price += (float)$arr_tara_price[$key] * $total_fragments;
                                    }
                                    else {
                                        $price += (float)$arr_tara_price[$key] * $arrResponse['Отправления'][$local['name']]['Количество'];
                                    }


                                    if (isset($natsenka) and $natsenka == true and isset($discount[$key]['mode']) and isset($discount[$key]['znak'])) {
                                        $tarif = 'Тариф';

                                        if ($discount[$key]['znak']) {
                                            if ($discount[$key]['mode']) {
                                                $natsenka_number = -$price * $discount[$key]['number'] / 100;
                                            } // -%
                                            else {
                                                $natsenka_number = -$discount[$key]['number'];
                                            } // -ed
                                        } else {
                                            if ($discount[$key]['mode']) {
                                                $natsenka_number = $price * $discount[$key]['number'] / 100;
                                            } // +%
                                            else {
                                                $natsenka_number = $discount[$key]['number'];
                                            } // +ed
                                        }

                                        $price += $natsenka_number;
                                    }

                                    if ($this->config->get($this->name . '_min_delivery')) {
                                        $pochtaros_min_delivery = $this->config->get($this->name . '_min_delivery');

                                        if ((float)$pochtaros_min_delivery[$key] > 0 and $price < $pochtaros_min_delivery[$key]) {
                                            $price = $pochtaros_min_delivery[$key];
                                        }
                                    }

                                    if (is_array($this->config->get('pochtaros_discounts')) and count($this->config->get('pochtaros_discounts')) > 0) {
                                        foreach ($this->config->get('pochtaros_discounts') as $discount) {

                                            if ((empty($discount['max_total']) or (!empty($discount['max_total']) and $discount['max_total'] >= $total)) and
                                                (empty($discount['min_total']) or (!empty($discount['min_total']) and $discount['min_total'] <= $total)) and 
                                                (isset($discount['key']) and is_array($discount['key']) and in_array($local['key'], $discount['key'])) and isset($discount['customer_group_id']) and 
                                                is_array($discount['customer_group_id']) and in_array($customer_id, $discount['customer_group_id'])
                                            ) {
                                                if (isset($discount['geo_zone'])) {
                                                    $sql = "SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone
                                                    WHERE geo_zone_id IN (" . implode(',', $discount['geo_zone']) . ") AND country_id = " . (int)$address['country_id'] . "
                                                    AND (zone_id = " . (int)$address['zone_id'] . " OR zone_id = 0)";

                                                    $query = $this->db->query($sql);
                                                    if ($query->num_rows) {
                                                        $right_geo_zone = true;
                                                    }
                                                    else {
                                                        $right_geo_zone = false;
                                                    }
                                                }
                                                else {
                                                    $right_geo_zone = true;
                                                }

                                                
                                                if ($right_geo_zone == true) {
                                                    $natsenka_number = 0;

                                                    if ($discount['prefix'] == '-') {
                                                        if ($discount['mode'] == 'percent') {
                                                            $natsenka_number = -$total * $discount['value'] / 100;
                                                        } // -%
                                                        elseif ($discount['mode'] == 'percent_shipping') {
                                                            $natsenka_number = -$price * $discount['value'] / 100;
                                                        }
                                                        elseif ($discount['mode'] == 'factor') {
                                                            $natsenka_number = -($price + $total) * $discount['value'];
                                                        }
                                                        else {
                                                            $natsenka_number = -$discount['value'];
                                                        } // -ed
                                                    }
                                                    elseif ($discount['prefix'] == '+') {
                                                        if ($discount['mode'] == 'percent') {
                                                            $natsenka_number = $total * $discount['value'] / 100;
                                                        } // +%
                                                        elseif ($discount['mode'] == 'percent_shipping') {
                                                            $natsenka_number = $price * $discount['value'] / 100;
                                                        }
                                                        elseif ($discount['mode'] == 'factor') {
                                                            $natsenka_number = ($price + $total) * $discount['value'];
                                                        }
                                                        else {
                                                            $natsenka_number = $discount['value'];
                                                        } // +ed
                                                    }

                                                    //echo '$natsenka_number='.$natsenka_number;
                                                    $price += $natsenka_number;
                                                }
                                            }
                                        }
                                    }


                                    if ((isset($price) and (float)$price > 0) or $this->config->get('pochtaros_zero_price') == 1) {
                                        if (!isset($price) or (isset($price) and $price <= 0)) {
                                            $price = 0;
                                        }

                                        if (!isset($arrResponse['Отправления'][$local['name']]['Количество'])) {
                                            $arrResponse['Отправления'][$local['name']]['Количество'] = 1;
                                        }

                                        $cost = $price;

                                        if (strstr($local['key'], 'obyavlennaya_stennost') and $this->config->get('pochtaros_nalozhka') > 0 and
                                            ((isset($this->request->post['payment_method']) and $this->config->get('pochtaros_payment') != '' and
                                                    $this->request->post['payment_method'] == $this->config->get('pochtaros_payment')) 

                                                or

                                                (isset($this->session->data['payment_method']['code']) and $this->config->get('pochtaros_payment') != '' and $this->session->data['payment_method']['code'] == $this->config->get('pochtaros_payment') ) 

                                                or

                                                (isset($this->request->post['payment_code']) and $this->config->get('pochtaros_payment') != '' and
                                                    $this->request->post['payment_code'] == $this->config->get('pochtaros_payment'))
                                                or $this->config->get('pochtaros_payment') == '') and
                                            isset($arrResponse['Отправления'][$local['name']]['НаложенныйПлатеж'])
                                        ) {
                                            $cost = $cost * $this->config->get('pochtaros_nalozhka');
                                        }

                                        if ($this->config->get('pochtaros_round') != '') {
                                            $cost = $this->louise170->roundPriceValue($cost, $this->config->get('pochtaros_round'));
                                        }

                                        $local_cost = $this->convertCurrency($cost);

                                        $text = $this->currency->format($this->tax->calculate($local_cost, $this->config->get($this->name . '_tax_class_id'), $this->config->get('config_tax')),$this->session->data['currency'],1);
                                    }
                                    else {
                                        $cost = '';
                                        $text = '';
                                    }


                                    if ($this->config->get('pochtaros_fragmentation')) {
                                        if ($total_fragments > 1) {
                                            $local['title_more'] .= ' (' . $total_fragments . ' ' . $this->language->get('text_items') . ')';
                                        }
                                        elseif ($arrResponse['Отправления'][$local['name']]['Количество'] > 1) {
                                            $local['title_more'] .= ' (' . $arrResponse['Отправления'][$local['name']]['Количество'] . ' ' . $this->language->get('text_items') . ')';
                                        }
                                    }


                                    $dilivery_period = '';

                                    if ($this->config->get('pochtaros_time')) {
                                        if ($arrResponse['Отправления'][$local['name']]['СрокДоставки']) {
                                            $srok = $arrResponse['Отправления'][$local['name']]['СрокДоставки'];
                                            
                                            $arr_srok = explode('-',$srok);                                         
                                            foreach ($arr_srok as $asr) {
                                                $last = $asr;
                                            }
                                            
                                            if ($this->config->get('pochtaros_time_more') > 0) {
                                                foreach ($arr_srok as $asv => $asr) {
                                                    $arr_srok[$asv] = $asr + $this->config->get('pochtaros_time_more');
                                                    $last = $arr_srok[$asv];
                                                }
                        
                                                    $srok = implode('-',$arr_srok);
                                            }

                                            $end = $this->louise170->endings($last, $this->language->get('text_day1'), $this->language->get('text_day2'), $this->language->get('text_day3'), $this->language->get('text_day4'));
                                            $dilivery_period = $this->language->get('text_time') . ' - ' . $srok . ' ' . $end . '.';
                                        }
                                    }

                                    $description = $this->config->get('pochtaros_description');
                                    $show_description = $this->config->get('pochtaros_show_description');
                                    $text_description = '';

                                    $show_text_description = 2;

                                    if (!empty($description[$key])) {
                                        $text_description = $description[$key];
                                        if (!empty($dilivery_period) and $this->config->get('pochtaros_time') == 1) {
                                            $text_description .= "<br/>";
                                        }

                                        if (isset($show_description[$key]) and $show_description[$key] == 1) {
                                            $show_text_description = 1;
                                        } else {
                                            $show_text_description = 2;
                                        }
                                    }

                                    $nalozhka2 = '';
                                    if (strstr($local['key'], 'obyavlennaya_stennost') and $this->config->get('pochtaros_nalozhka2') and
                                        ((isset($this->request->post['payment_method']) and $this->config->get('pochtaros_payment') != '' and
                                                $this->request->post['payment_method'] == $this->config->get('pochtaros_payment')) 

                                            or

                                            (isset($this->session->data['payment_method']['code']) and $this->config->get('pochtaros_payment') != '' and $this->session->data['payment_method']['code'] == $this->config->get('pochtaros_payment') ) 

                                            or

                                            (isset($this->request->post['payment_code']) and $this->config->get('pochtaros_payment') != '' and
                                                $this->request->post['payment_code'] == $this->config->get('pochtaros_payment'))
                                            or $this->config->get('pochtaros_payment') == '') and
                                        isset($arrResponse['Отправления'][$local['name']]['НаложенныйПлатеж'])
                                    ) {
                                        if ((!empty($dilivery_period) and $this->config->get('pochtaros_time') == 2) or !empty($text_description)) {
                                            $nalozhka2 .= "<br/>";
                                        }

                                        $nalozhka2_value = $this->model_localisation_zone_dv->getPriceNalozhka($arrResponse, $local);

                                        $nalozhka2_value = $this->convertCurrency($nalozhka2_value, false);

                                        $nalozhka2 .= sprintf($this->language->get('text_nalozhka'), 
                                        $this->currency->format($nalozhka2_value, $this->session->data['currency'], 1));
                                    }

                                    $lactive = true;

                                    $arr_max_order = $this->config->get('pochtaros_max_order');
                                    $arr_min_order = $this->config->get('pochtaros_min_order');
                                    
                                    if ((float)$arr_max_order[$key] > 0 and $total > (float)$arr_max_order[$key]) {
                                        $lactive = false;
                                    }
                                    elseif ((float)$arr_min_order[$key] > 0 and $total < (float)$arr_min_order[$key]) {
                                        $lactive = false;
                                    }

                                    unset($error_text2);
                                    
                                    $arr_bubbles = $this->config->get('pochtaros_bubbles');

                                    if (count($arr_bubbles) > 0) {
                                        foreach ($arr_bubbles as $bubble) {
                                            if (isset($bubble['key']) and in_array($key, $bubble['key'])) {
                                                if (isset($bubble['geo_zone'])) {
                                                    $sql = "SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone
                                                        WHERE geo_zone_id IN (" . implode(',', $bubble['geo_zone']) . ") AND country_id = " . (int)$address['country_id'] . "
                                                        AND (zone_id = " . (int)$address['zone_id'] . " OR zone_id = 0)";

                                                    $query = $this->db->query($sql);
                                                    if ($query->num_rows) {
                                                        $right_geo_zone = true;
                                                    }
                                                    else {
                                                        $right_geo_zone = false;
                                                    }
                                                }
                                                else {
                                                    $right_geo_zone = false;
                                                }

                                                if ($bubble['logic'] == 'region' and !isset($arr_bibb_text[$key]) and $right_geo_zone == true) {
                                                    $error_text2 = $bubble['bubble_title'];

                                                    $arr_bibb_text[$key] = $error_text2;

                                                    $cost = 0;
                                                    $text = '';
                                                }
                                                elseif ($bubble['logic'] == 'cost' and !isset($arr_bibb_text[$key]) and $right_geo_zone == true) {
                                                    $bubble['min_cost'] = (int)$bubble['min_cost'];
                                                    $bubble['max_cost'] = (int)$bubble['max_cost'];

                                                    if (($bubble['min_cost'] > 0 and $total < $bubble['min_cost'] and $bubble['max_cost'] > 0 and $total > $bubble['max_cost']) or
                                                        ($bubble['min_cost'] > 0 and $total < $bubble['min_cost'] and $bubble['max_cost'] == 0) or
                                                        ($bubble['max_cost'] > 0 and $total > $bubble['max_cost'] and $bubble['min_cost'] == 0)
                                                    ) {
                                                        $max_cost = '';
                                                        $min_cost = '';

                                                        if ($bubble['max_cost'] > 0){
                                                            $max_cost = $this->currency->format($this->tax->calculate($bubble['max_cost'],
                                                                $this->config->get($this->name . '_tax_class_id'),
                                                                $this->config->get('config_tax')),$this->session->data['currency'], 1);                                                        
                                                        }

                                                        if ($bubble['min_cost'] > 0){
                                                            $min_cost = $this->currency->format($this->tax->calculate($bubble['min_cost'],
                                                                $this->config->get($this->name . '_tax_class_id'),
                                                                $this->config->get('config_tax')),$this->session->data['currency'], 1);
                                                        }

                                                        $error_text2 = str_replace("{max_cost}", $max_cost, $bubble['bubble_title']);
                                                        $error_text2 = str_replace("{min_cost}", $min_cost, $error_text2);

                                                        $add = $bubble['min_cost'] - $total;
                                                        $del = $total - $bubble['max_cost'];

                                                        if ($add > 0) {
                                                            $add_cost = $this->currency->format($this->tax->calculate($add,
                                                                $this->config->get($this->name . '_tax_class_id'),
                                                                $this->config->get('config_tax')),$this->session->data['currency'], 1);

                                                            $error_text2 = str_replace("{add_cost}",$add_cost,$error_text2);
                                                        }
                                                        elseif ($del > 0) {
                                                            $del_cost = $this->currency->format($this->tax->calculate($del,
                                                                $this->config->get($this->name . '_tax_class_id'),
                                                                $this->config->get('config_tax')),$this->session->data['currency'], 1);

                                                            $error_text2 = str_replace("{del_cost}",$del_cost,$error_text2);
                                                        }
                                                        else $error_text2 = '';

                                                        if (!empty($error_text2)) {
                                                            $arr_bibb_text[$key] = $error_text2;
                                                        }

                                                        $cost = 0;
                                                        $text = '';
                                                    }
                                                }
                                                elseif ($bubble['logic'] == 'weight' and !isset($arr_bibb_text[$key]) and $right_geo_zone == true) {
                                                    $bubble['min_weight'] = (float)$bubble['min_weight'];
                                                    $bubble['max_weight'] = (float)$bubble['max_weight'];

                                                    $bubble['min_weight'] = $this->weight->convert($bubble['min_weight'], $this->config->get('pochtaros_weight_class_id'), $this->config->get('config_weight_class_id'));
                                                    $bubble['max_weight'] = $this->weight->convert($bubble['max_weight'], $this->config->get('pochtaros_weight_class_id'), $this->config->get('config_weight_class_id'));


                                                    $weight_mod = false;
                                                    if ($weight_name == 'кг' or $weight_name == 'kg') {
                                                        $bubble['min_weight'] = $bubble['min_weight'] * 1000;
                                                        $bubble['max_weight'] = $bubble['max_weight'] * 1000;
                                                        $weight_mod = true;
                                                    }

                                                    if (($bubble['min_weight'] > 0 and $local_weight < $bubble['min_weight'] and $bubble['max_weight'] > 0 and $local_weight > $bubble['max_weight']) or
                                                        ($bubble['min_weight'] > 0 and $local_weight < $bubble['min_weight'] and $bubble['max_weight'] == 0) or
                                                        ($bubble['max_weight'] > 0 and $local_weight > $bubble['max_weight'] and $bubble['min_weight'] == 0)
                                                    ) {
                                                        $max_weight = '';
                                                        $min_weight = '';

                                                        $add = $bubble['min_weight'] - $local_weight;
                                                        $del = $local_weight - $bubble['max_weight'];

                                                        if ($weight_mod == true) {
                                                            $bubble['min_weight'] = $bubble['min_weight'] / 1000;
                                                            $bubble['max_weight'] = $bubble['max_weight'] / 1000;

                                                            $add = $add/1000;
                                                            $del = $del/1000;
                                                        }

                                                        if ($bubble['max_weight'] > 0){
                                                            $max_weight = $this->weight->format($bubble['max_weight'], $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
                                                        }

                                                        if ($bubble['min_weight'] > 0){
                                                            $min_weight = $this->weight->format($bubble['min_weight'], $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
                                                        }

                                                        $error_text2 = str_replace("{max_weight}", $max_weight, $bubble['bubble_title']);
                                                        $error_text2 = str_replace("{min_weight}", $min_weight, $error_text2);

                                                        if ($add > 0) {
                                                            $add_weight = $this->weight->format($add, $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));

                                                            $error_text2 = str_replace("{add_weight}",$add_weight,$error_text2);
                                                        }
                                                        elseif ($del > 0) {
                                                            $del_weight = $this->weight->format($del, $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));

                                                            $error_text2 = str_replace("{del_weight}",$del_weight,$error_text2);
                                                        }
                                                        else $error_text2 = '';

                                                        if (!empty($error_text2)) {
                                                            $arr_bibb_text[$key] = $error_text2;
                                                        }

                                                        $cost = 0;
                                                        $text = '';
                                                    }
                                                }
                                                elseif ($bubble['logic'] == 'all_total' and !isset($arr_bibb_text[$key])) {
                                                    $bubble['min_all_total'] = (int)$bubble['min_all_total'];
                                                    $bubble['max_all_total'] = (int)$bubble['max_all_total'];

                                                    if (($bubble['min_all_total'] > 0 and $total_products < $bubble['min_all_total'] and $bubble['max_all_total'] > 0 and $total_products > $bubble['max_all_total']) or
                                                        ($bubble['min_all_total'] > 0 and $total_products < $bubble['min_all_total'] and $bubble['max_all_total'] == 0) or
                                                        ($bubble['max_all_total'] > 0 and $total_products > $bubble['max_all_total'] and $bubble['min_all_total'] == 0)
                                                    ) {
                                                        $max_all_total = '';
                                                        $min_all_total = '';

                                                        if ($bubble['max_all_total'] > 0) {
                                                            $max_all_total = $bubble['max_all_total'];
                                                        }

                                                        if ($bubble['min_all_total'] > 0) {
                                                            $min_all_total = $bubble['min_all_total'];
                                                        }

                                                        $error_text2 = str_replace("{max_all_total}", $max_all_total, $bubble['bubble_title']);
                                                        $error_text2 = str_replace("{min_all_total}", $min_all_total, $error_text2);

                                                        $add = $bubble['min_all_total'] - $total_products;
                                                        $del = $total_products - $bubble['max_all_total'];

                                                        if ($add > 0) {
                                                            $add_all_total = $add.' '.$this->louise170->endings($add, $txt="товар", $e1="", $e2="а", $e3="ов", $begin=true);

                                                            $error_text2 = str_replace("{add_all_total}",$add_all_total,$error_text2);
                                                        }
                                                        elseif ($del > 0) {
                                                            $del_all_total = $del.' '.$this->louise170->endings($del, $txt="товар", $e1="", $e2="а", $e3="ов", $begin=true);

                                                            $error_text2 = str_replace("{del_all_total}",$del_all_total,$error_text2);
                                                        }
                                                        else $error_text2 = '';

                                                        if (!empty($error_text2)) {
                                                            $arr_bibb_text[$key] = $error_text2;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if (((float)$cost > 0 or $this->config->get('pochtaros_zero_price') == 1) and $lactive == true) {
                                        $arr_lock = array();
                                        $arr_unlock = array();

                                        $arr_pochtaros_incity = $this->config->get('pochtaros_incity');
                                        $arr_pochtaros_outcity = $this->config->get('pochtaros_outcity');
                                        $arr_pochtaros_geo_zone = $this->config->get('pochtaros_geo_zone');

                                        if (isset($arr_pochtaros_geo_zone[$key]) and count($arr_pochtaros_geo_zone[$key]) > 0) {
                                            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone
                                            WHERE geo_zone_id IN (" . implode(',', $arr_pochtaros_geo_zone[$key]) . ") AND
                                            country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

                                            if ($query->num_rows == 0) {
                                                $arr_lock[] = $key;
                                            }
                                        }
                                        elseif ((isset($arr_pochtaros_geo_zone[$key]) and count($arr_pochtaros_geo_zone[$key]) == 0) or !isset($arr_pochtaros_geo_zone[$key])) {
                                            $arr_lock[] = $key;
                                        }


                                        if (isset($arr_pochtaros_incity[$key]) and $arr_pochtaros_incity[$key] != '') {
                                            $rates = explode(';', $arr_pochtaros_incity[$key]);

                                            if (count($rates) > 0) {
                                                foreach ($rates as $rate) {
                                                    $data = trim($rate);

                                                    if (mb_strtolower($data, 'UTF-8') == mb_strtolower(trim($address['city']), 'UTF-8')) {
                                                        $arr_lock[] = $key;
                                                    }
                                                }
                                            }
                                        }

                                        if (isset($arr_pochtaros_outcity[$key]) and $arr_pochtaros_outcity[$key] != '') {
                                            $rates = explode(';', $arr_pochtaros_outcity[$key]);

                                            if (count($rates) > 0) {
                                                foreach ($rates as $rate) {
                                                    $data = trim($rate);

                                                    if (mb_strtolower($data, 'UTF-8') == mb_strtolower(trim($address['city']), 'UTF-8')) {
                                                        $arr_unlock[] = $key;
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if ((((float)$cost > 0 or $this->config->get('pochtaros_zero_price') == 1) and $lactive == true)
                                        and !in_array($key, $arr_lock) and isset($arr_pochtaros_outcity[$key])
                                        and (empty($arr_pochtaros_outcity[$key]) or (!empty($arr_pochtaros_outcity[$key]) and in_array($key, $arr_unlock)))
                                    ) {

                                        $show = true;

                                        if ($this->config->get('pochtaros_min_weight2')) {
                                            $pochtaros_min_weight2 = $this->config->get('pochtaros_min_weight2');

                                            if ($pochtaros_min_weight2[$key] and $cart_weight < $pochtaros_min_weight2[$key]) {
                                                $show = false;
                                            }
                                        }

                                        if ($this->config->get('pochtaros_max_weight2') and $show == true) {
                                            $pochtaros_max_weight2 = $this->config->get('pochtaros_max_weight2');

                                            if ($pochtaros_max_weight2[$key] and $cart_weight > $pochtaros_max_weight2[$key]) {
                                                $show = false;
                                            }
                                        }

                                        if ($this->config->get('pochtaros_min_products') and $show == true) {
                                            $pochtaros_min_products = $this->config->get('pochtaros_min_products');

                                            if ($pochtaros_min_products[$key] and $total_products < $pochtaros_min_products[$key]) {
                                                $show = false;
                                            }
                                        }
                                        
                                        if ($this->config->get('pochtaros_max_products') and $show == true) {
                                            $pochtaros_max_products = $this->config->get('pochtaros_max_products');

                                            if ($pochtaros_max_products[$key] and $total_products > $pochtaros_max_products[$key]) {
                                                $show = false;
                                            }
                                        }

                                        if ($show == true) {
                                            $cost = $this->convertCurrency($cost, true);

                                            $msort_order = $this->config->get('pochtaros_msort_order');
                                            
                                            if (strstr($key,'obyavlennaya_stennost') and $this->config->get($this->name . '_show_nalozh_size') == 1) {
                                                $local_otsenka = $local_total;

                                                $local_otsenka = $this->convertCurrency($local_otsenka, false, true);

                                                $local['title_more'] .= ' ' . $this->currency->format($this->tax->calculate($local_otsenka, $this->config->get($this->name . '_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'], 1);
                                            }


                                            $text_description = html_entity_decode($text_description);

                                            if ($this->config->get('pochtaros_time') == 1) {
                                                $text_description .= $dilivery_period;
                                            }
                                            elseif ($this->config->get('pochtaros_time') == 2) {
                                                $local['title_more'] .= ' ' . $dilivery_period;
                                            }


                                            if ($this->config->get('pochtaros_nalozhka2') == 1) {
                                                $text_description .= ' ' . $nalozhka2;
                                                $text_description = trim($text_description);
                                            }
                                            elseif ($this->config->get('pochtaros_nalozhka2') == 2) {
                                                $local['title_more'] .= ' ' . $nalozhka2;
                                            }

                                            $quote_data[$key] = array(
                                                'code' => $this->name . '.' . $key,
                                                'title' => strip_tags($local['title_more']),
                                                'dummy' => isset($error_text2) ? $error_text2 : '',
                                                'description' => isset($error_text2) ? $error_text2 : $text_description,
                                                'show_description' => $show_text_description,
                                                'tax_class_id' => $this->config->get($this->name . '_tax_class_id'),
                                                'image' => $image ? $path2 . $image : '',
                                                'cost' => $cost,
                                                'sort_order' => $msort_order[$key],
                                                'text' => isset($error_text2) ? '' : strip_tags($text),
                                                'error' => isset($error_text2) ? true : false
                                            );
                                        }   
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (isset($quote_data) and count($quote_data) > 0) {
                $sort_by = array();
                foreach ($quote_data as $key => $value) $sort_by[$key] = $value['sort_order'];
                array_multisort($sort_by, SORT_ASC, $quote_data);
            }
            

            if ($arr_bibb_text) {
                foreach ($arr_bibb_text as $k => $v) {
                    $title_tab = $this->config->get($this->name . '_title_tab');
                    
                    $quote_data[$k] = array(
                        'code' => 'pochtaros.'.$k,
                        'title' => !empty($title_tab[$k]) ? $title_tab[$k] : $this->language->get('text_' . $k),
                        'cost' => 0,
                        'tax_class_id' => $this->config->get($this->name . '_tax_class_id'),
                        'text' => '',
                        'error' => true,
                        'warning' => $v,
                        'dummy' => $v
                    );
                }
            }
            
            
            if (((isset($quote_data) and count($quote_data) == 0) or !isset($quote_data)) and $this->config->get('pochtaros_zaglushka')) {
                if ($error == false) {
                    $bibbtext = $this->config->get($this->name . '_bibbtext');
                    if ($bibbtext[$this->config->get('config_language_id')]) {
                        $error = $bibbtext[$this->config->get('config_language_id')];
                    } else {
                        $error = sprintf($this->language->get('error_description3'), $this->config->get('config_telephone'));
                    }
                }
            }
            

            if ((isset($quote_data) and count($quote_data) > 0) or $error) {
                if (version_compare(VERSION, '3.0', '<')) {
                    $sort_order = (int)$this->config->get('pochtaros_sort_order');
                }
                else {
                    $sort_order = (int)$this->config->get('shipping_pochtaros_sort_order');
                }

                $title = $this->config->get($this->name . '_name');

                if ($error and $this->config->get('pochtaros_zaglushka_type') == 1) {
                    $quote_data = array('empty' => array(
                        'code' => 'pochtaros.empty',
                        'title' => html_entity_decode($title[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8') . ' - ' . $error,
                        'cost' => 0,
                        'image' => $image ? $path2 . $image : '',
                        'tax_class_id' => $this->config->get($this->name . '_tax_class_id'),
                        'text' => '',
                    ));

                    $method_data = array(
                        'code' => $this->name,
                        'title' => html_entity_decode($title[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'),
                        'quote' => isset($quote_data) ? $quote_data : array(),
                        'sort_order' => ($error & $this->config->get('pochtaros_zaglushka_vniz')) ? ($sort_order + 100) : $sort_order,
                        'error' => ''
                    );
                }
                else {
                    $method_data = array(
                        'code' => $this->name,
                        'title' => html_entity_decode($title[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'),
                        'quote' => isset($quote_data) ? $quote_data : array(),
                        'sort_order' => ($error & $this->config->get('pochtaros_zaglushka_vniz')) ? ($sort_order + 100) : $sort_order,
                        'error' => $error
                    );
                }
            }
        }
        //print_r($method_data);

        return $method_data;
    }

}
?>