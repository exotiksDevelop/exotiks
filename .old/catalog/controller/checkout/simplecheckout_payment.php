<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutPayment extends SimpleController {
    private $_templateData = array();

    public function index() {
        $this->loadLibrary('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);

        $this->language->load('checkout/simplecheckout');

        $get_route = isset($_GET['route']) ? $_GET['route'] : (isset($_GET['_route_']) ? $_GET['_route_'] : '');

        if ($get_route == 'checkout/simplecheckout_payment') {
            $this->simplecheckout->init('payment_address');
            $this->simplecheckout->init('payment');
        }

        $address = !empty($this->session->data['simple']['payment_address']) ? $this->session->data['simple']['payment_address'] : array(
            'address_id' => '',
            'firstname' => '',
            'lastname' => '',
            'company' => '',
            'address_1' => '',
            'address_2' => '',
            'city' => '',
            'postcode' => '',
            'zone_id' => '',
            'country_id' => '', 
        );

        $this->_templateData['address_empty'] = $this->simplecheckout->isPaymentAddressEmpty();

        $totals = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $sort_order = array();

        if ($this->simplecheckout->getOpencartVersion() < 200 || $this->simplecheckout->getOpencartVersion() >= 300) {
            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('total');
        } else {
            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('total');
        }

        foreach ($results as $key => $value) {
            if ($this->simplecheckout->getOpencartVersion() < 300) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            } else {
                $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
            }            
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->simplecheckout->getOpencartVersion() < 300) {
                $status = $this->config->get($result['code'] . '_status');
            } else {
                $status = $this->config->get('total_' . $result['code'] . '_status');
            }

            if ($status) {
                $this->simplecheckout->loadModel('total/' . $result['code']);

                if ($this->simplecheckout->getOpencartVersion() < 220) {
                    $this->{'model_total_' . $result['code']}->getTotal($totals, $total, $taxes);
                } else {
                    $this->{'model_total_' . $result['code']}->getTotal($total_data);
                }
            }
        }

        $method_data = array();

        if ($stubs = $this->simplecheckout->getPaymentStubs()) {
            foreach ($stubs as $stub) {
                $method_data[$stub['code']] = $stub;
            }
        }

        $version = $this->simplecheckout->simplecheckout->getOpencartVersion();

        $cartHasReccuringProducts = 0;

        if ($version >= 156) {
            $cartHasReccuringProducts = $this->cart->hasRecurringProducts();
        }

        if ($this->simplecheckout->getOpencartVersion() < 200 || $this->simplecheckout->getOpencartVersion() >= 300) {
            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('payment');
        } else {
            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('payment');
        }

        foreach ($results as $result) {
            $display = true;
            
            if ($this->_templateData['address_empty']) {
                $display = $this->simplecheckout->displayPaymentMethodForEmptyAddress($result['code']);
            }

            if ($this->simplecheckout->getOpencartVersion() < 300) {
                $status = $this->config->get($result['code'] . '_status');
            } else {
                $status = $this->config->get('payment_' . $result['code'] . '_status');
            }

            if ($status && $display) {
                $this->simplecheckout->loadModel('payment/' . $result['code']);

                $method = $this->{'model_payment_' . $result['code']}->getMethod($address, $total);

                if ($method) {
                    if (!$cartHasReccuringProducts || ($cartHasReccuringProducts > 0 && (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') || property_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') || (method_exists($this->{'model_payment_' . $result['code']}, 'isExistForSimple') && $this->{'model_payment_' . $result['code']}->isExistForSimple('recurringPayments'))) && $this->{'model_payment_' . $result['code']}->recurringPayments() == true)) {
                        if (!empty($method['quote']) && is_array($method['quote'])) {
                            foreach ($method['quote'] as $quote) {
                                $this->simplecheckout->exportPaymentMethod($quote);
                                $quote = $this->simplecheckout->preparePaymentMethod($quote);
                                if (!empty($quote)) {
                                    $method_data[$quote['code']] = $quote;
                                }
                            }
                        } else {
                            $this->simplecheckout->exportPaymentMethod($method);
                            $method = $this->simplecheckout->preparePaymentMethod($method);
                            if (!empty($method)) {
                                $method_data[$result['code']] = $method;
                            }
                        }
                    }
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->_templateData['payment_methods']   = $method_data;
        $this->_templateData['payment_method']    = null;
        $this->_templateData['error_payment']     = $this->language->get('error_payment');
        $this->_templateData['has_error_payment'] = false;

        $this->_templateData['code'] = '';
        $this->_templateData['checked_code'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['payment_method_checked']) && !empty($this->_templateData['payment_methods'][$this->request->post['payment_method_checked']]) && empty($this->_templateData['payment_methods'][$this->request->post['payment_method_checked']]['dummy'])) {
            $this->_templateData['checked_code'] = $this->request->post['payment_method_checked'];
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['payment_method']) && !empty($this->_templateData['payment_methods'][$this->request->post['payment_method']]) && empty($this->_templateData['payment_methods'][$this->request->post['payment_method']]['dummy'])) {
            $this->_templateData['payment_method'] = $this->_templateData['payment_methods'][$this->request->post['payment_method']];

            if (isset($this->request->post['payment_method_current']) && $this->request->post['payment_method_current'] != $this->request->post['payment_method']) {
                $this->_templateData['checked_code'] = $this->request->post['payment_method'];
            }
        }

        if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->session->data['payment_method'])) {
            $user_checked = false;
            if (!empty($this->session->data['payment_method']['code'])) {
                $payment_code = $this->session->data['payment_method']['code'];
                $user_checked = true;
            }

            if (!empty($payment_code) && isset($this->_templateData['payment_methods'][$payment_code]) && empty($this->_templateData['payment_methods'][$payment_code]['dummy'])) {
                $this->_templateData['payment_method'] = $this->_templateData['payment_methods'][$payment_code];
                if ($user_checked) {
                    $this->_templateData['checked_code'] = $this->session->data['payment_method']['code'];
                }
            }
        }

        $selectFirst = $this->simplecheckout->getSettingValue('selectFirst', 'payment');
        $hide = $this->simplecheckout->isBlockHidden('payment');

        if ($hide) {
            $selectFirst = true;
        }

        if (!empty($this->_templateData['payment_methods']) && ($hide || ($selectFirst && $this->_templateData['checked_code'] == ''))) {
            foreach ($this->_templateData['payment_methods'] as $method) {
                if (empty($method['dummy'])) {
                    $this->_templateData['payment_method'] = $method;
                    break;
                }
            }

        }

        if (!empty($this->_templateData['payment_method']['code'])) {
            $this->_templateData['code'] = $this->_templateData['payment_method']['code'];
        } else {
            $this->_templateData['has_error_payment'] = true;
            $this->simplecheckout->addError('payment');
        }

        $this->session->data['payment_methods'] = $this->_templateData['payment_methods'];
        $this->session->data['payment_method'] = $this->_templateData['payment_method'];

        if (empty($this->session->data['payment_methods'])) {
            unset($this->session->data['payment_method']);
        }

        $this->_templateData['rows'] = $this->simplecheckout->getRows('payment');

        if (!$this->simplecheckout->validateFields('payment')) {
            $this->simplecheckout->addError('payment');
        }

        $this->_templateData['display_header']        = $this->simplecheckout->getSettingValue('displayHeader', 'payment');
        $this->_templateData['display_error']         = $this->simplecheckout->displayError('payment');
        $this->_templateData['display_address_empty'] = $this->simplecheckout->getSettingValue('displayAddressEmpty', 'payment');
        $this->_templateData['has_error']             = $this->simplecheckout->hasError('payment');
        $this->_templateData['hide']                  = $this->simplecheckout->isBlockHidden('payment');
        $this->_templateData['display_for_selected']  = $this->simplecheckout->getSettingValue('displayDescriptionOnlyForSelected', 'payment');
        
        $this->_templateData['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
        $this->_templateData['text_payment_address']         = $this->language->get('text_payment_address');
        $this->_templateData['error_no_payment']             = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        $this->_templateData['display_type']                 = $this->simplecheckout->getPaymentDisplayType();

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_payment', $this->_templateData));
    }
}
