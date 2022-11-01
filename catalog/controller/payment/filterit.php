<?php
/*
@author  Dmitriy Kubarev
@link  http://www.simpleopencart.com
*/

class ControllerPaymentFilterit extends Controller {
    public function index() {
        $payment_method = $this->session->data['payment_method']['code'];
    	$settings = $this->config->get('filterit_payment');
        $created = isset($settings['created']) ? $settings['created'] : array();

        $module_info = isset($created[$payment_method]) ? $created[$payment_method] : array();
        $language = $this->getCurrentLanguageCode();

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['header'] = !empty($module_info['payment_form_header'][$language]) ? trim($module_info['payment_form_header'][$language]) : '';
        $data['instruction'] = !empty($module_info['payment_form'][$language]) ? trim($module_info['payment_form'][$language]) : '';
        $data['continue'] = $this->url->link('checkout/success');

        if ($this->config->get('filterit_module_used')) {
            $data['instruction'] = '';
        }

        if (!empty($data['instruction'])) {
            $all_totals = $this->getAllTotals();   
            $totals = $this->getTotals();   
            $replace = array();
            $find = array();     

            foreach ($all_totals as $key) {
                $place = '{'.$key.'}';
                $find[] = $place;
                $replace[$place] = isset($totals[$key]) ? $totals[$key] : '';
            }

            $data['instruction'] = trim(str_replace($find, $replace, $data['instruction']));
        }

        $opencart_version = $this->getOpenCartVersion();

        if ($opencart_version < 230) {
            $data['route'] = 'payment/filterit/confirm';
        } else {
            $data['route'] = 'extension/payment/filterit/confirm';
        }
        
        if ($opencart_version < 200) {
            $this->data = $data;

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/filterit.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/payment/filterit.tpl';
            } else {
                $this->template = 'default/template/payment/filterit.tpl';
            }    
            
            $this->render();
        } elseif ($opencart_version < 220) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/filterit.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/payment/filterit.tpl', $data);
            } else {
                return $this->load->view('default/template/payment/filterit.tpl', $data);
            }
        }  elseif ($opencart_version < 230) {
            return $this->load->view('payment/filterit', $data);
        } else {
            return $this->load->view('extension/payment/filterit', $data);
        }
    }
    
    public function confirm() {
        $order_id = $this->session->data['order_id'];
    	$payment_method = $this->session->data['payment_method']['code'];
    	$settings = $this->config->get('filterit_payment');
        $created = isset($settings['created']) ? $settings['created'] : array();
        $module_info = isset($created[$payment_method]) ? $created[$payment_method] : array();
        
        $language = $this->getCurrentLanguageCode();

        $comment = !empty($module_info['payment_mail'][$language]) ? trim($module_info['payment_mail'][$language]) : '';

        if (!empty($comment)) {
            $all_totals = $this->getAllTotals();   
            $totals = $this->getTotalsFromDb($order_id);  
            $replace = array();
            $find = array();     

            foreach ($all_totals as $key) {
                $place = '{'.$key.'}';
                $find[] = $place;
                $replace[$place] = isset($totals[$key]) ? $totals[$key] : '';
            }

            $comment = trim(str_replace($find, $replace, $comment));
        }

        $this->load->model('checkout/order');

        if ($this->getOpenCartVersion() < 200) { 
            $this->model_checkout_order->confirm($order_id, isset($module_info['order_status_id']) ? $module_info['order_status_id'] : 0, $comment, $comment ? true : false);
        } else {
            $this->model_checkout_order->addOrderHistory($order_id, isset($module_info['order_status_id']) ? $module_info['order_status_id'] : 0, $comment, $comment ? true : false);
        }
    }

    private function getCurrentLanguageCode() {
        return isset($this->session->data['language']) && strlen($this->session->data['language']) < 6 ? $this->session->data['language'] : $this->config->get('config_language');
    }

    private function getOpenCartVersion() {
        $opencartVersion = explode('.', VERSION);
        return floatval($opencartVersion[0].$opencartVersion[1].$opencartVersion[2].'.'.(isset($opencartVersion[3]) ? $opencartVersion[3] : 0));
    }

    private function getAllTotals() {
        $opencart_version = $this->getOpencartVersion();

        if ($opencart_version < 200 || $opencart_version >= 300) {
            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('total');
        } else {
            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('total');
        }

        $totals = array();

        foreach ($results as $key => $result) {
            $totals[] = $result['code'];
        }

        return $totals;
    }

    private function getTotals() {
        $opencart_version = $this->getOpencartVersion();

        $totals = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $sort_order = array();

        if ($opencart_version < 200 || $opencart_version >= 300) {
            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('total');
        } else {
            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('total');
        }

        foreach ($results as $key => $result) {
            if ($opencart_version < 300) {
                $sort_order[$key] = $this->config->get($result['code'] . '_sort_order');
            } else {
                $sort_order[$key] = $this->config->get('total_' . $result['code'] . '_sort_order');
            }                
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($opencart_version < 300) {
                $status = $this->config->get($result['code'] . '_status');
            } else {
                $status = $this->config->get('total_' . $result['code'] . '_status');
            }

            if ($status) {
                if ($opencart_version < 230) {
                    $this->load->model('total/' . $result['code']);

                    if ($opencart_version < 220) {
                        $this->{'model_total_' . $result['code']}->getTotal($totals, $total, $taxes);
                    } else {
                        $this->{'model_total_' . $result['code']}->getTotal($total_data);
                    }

                } else {
                    $this->load->model('extension/total/' . $result['code']);

                    $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                }
            }
        }

        $result = array();

        foreach ($totals as $key => $value) {
            if (!isset($value['text'])) {
                $totals[$key]['text'] = $this->formatCurrency($value['value']);
            }

            $result[$totals[$key]['code']] = $totals[$key]['text'];
        }

        return $result;
    }

    private function formatCurrency($value) {
        if ($this->getOpencartVersion() < 220) {
            return $this->currency->format($value);
        } else {
            return $this->currency->format($value, $this->session->data['currency']);
        }
    }

    private function getTotalsFromDb($order_id) {
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
        $total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");

        $result = array();

        foreach ($total_query->rows as $total) {
            $text = '';

            if (!isset($total['text'])) {
                if ($this->getOpencartVersion() < 220) {
                    $text = $this->currency->format($total['value']);
                } else {
                    $text = $this->currency->format($total['value'], $order_query->row['currency_code']);
                }
            } else {
                $text = $total['text'];
            }

            $result[$total['code']] = $text;
        }

        return $result;
    }
}

class ControllerExtensionPaymentFilterit extends ControllerPaymentFilterit {
}