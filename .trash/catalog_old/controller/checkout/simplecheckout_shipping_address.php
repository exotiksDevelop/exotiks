<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutShippingAddress extends SimpleController {
    private $_templateData = array();

    private function init() {
        $this->loadLibrary('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);

        $this->language->load('checkout/simplecheckout');

        $get_route = isset($_GET['route']) ? $_GET['route'] : (isset($_GET['_route_']) ? $_GET['_route_'] : '');

        if ($get_route == 'checkout/simplecheckout_shipping_address') {
            $this->simplecheckout->init('shipping_address');
        }
    }

    public function index() {
        if (!$this->cart->hasShipping()) {
            return;
        }

        $this->init();

        if ($this->simplecheckout->isBlockHidden('shipping_address') || (!$this->simplecheckout->isBlockHidden('shipping_address') && !$this->simplecheckout->isBlockHidden('payment_address') && $this->simplecheckout->isAddressSame())) {
            return;
        }

        $this->_templateData['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
        $this->_templateData['text_select']                    = $this->language->get('text_select');
        $this->_templateData['text_add_new']                   = $this->language->get('text_add_new');
        $this->_templateData['text_select_address']            = $this->language->get('text_select_address');

        $this->_templateData['rows']                           = $this->simplecheckout->getRows('shipping_address');
        $this->_templateData['hidden_rows']                    = $this->simplecheckout->getHiddenRows('shipping_address');

        if (!$this->simplecheckout->validateFields('shipping_address')) {
            $this->simplecheckout->addError('shipping_address');
        }

        $this->_templateData['display_header'] = $this->simplecheckout->getSettingValue('displayHeader', 'shipping_address');
        $this->_templateData['display_error']  = $this->simplecheckout->displayError('shipping_address');
        $this->_templateData['has_error']      = $this->simplecheckout->hasError('shipping_address');
        $this->_templateData['hide']           = $this->simplecheckout->isBlockHidden('shipping_address');

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_shipping_address', $this->_templateData));
    }

    public function update_session() {
        if (!$this->cart->hasShipping()) {
            return;
        }

        $this->init();

        if (empty($this->session->data['simple']['shipping_address'])) {
            return;
        }

        $version = $this->simplecheckout->getOpencartVersion();

        $address = $this->session->data['simple']['shipping_address'];

        if ($version >= 200) {
            $this->session->data['shipping_address'] = $address;
        } else if (!$this->customer->isLogged()) {
            $this->session->data['guest']['shipping'] = $address;
        }

        unset($this->session->data['shipping_address_id']);
        unset($this->session->data['shipping_country_id']);
        unset($this->session->data['shipping_zone_id']);
        unset($this->session->data['shipping_postcode']);

        if (!empty($address['address_id'])) {
            $this->session->data['shipping_address_id'] = $address['address_id'];
        }

        if (!empty($address['country_id'])) {
            $this->session->data['shipping_country_id'] = $address['country_id'];
        } else {
            $this->session->data['shipping_country_id'] = 0;
        }

        if (!empty($address['zone_id'])) {
            $this->session->data['shipping_zone_id'] = $address['zone_id'];
        } else {
            $this->session->data['shipping_zone_id'] = 0;
        }

        if (!empty($address['postcode'])) {
            $this->session->data['shipping_postcode'] = $address['postcode'];
        }

        if ($version == 152 && !empty($this->session->data['guest']['shipping']) && is_array($this->session->data['guest']['shipping'])) {
            $clear = true;
            foreach ($this->session->data['guest']['shipping'] as $key => $value) {
                if ($value) {
                    $clear = false;
                    break;
                }
            }
            if ($clear) {
                unset($this->session->data['guest']['shipping']);
            }
        }

        if ($this->session->data['shipping_country_id'] || $this->session->data['shipping_zone_id']) {
            if ($version > 151) {
                $this->tax->setShippingAddress($this->session->data['shipping_country_id'], $this->session->data['shipping_zone_id']);
            } else {
                $this->tax->setZone($this->session->data['shipping_country_id'], $this->session->data['shipping_zone_id']);

                $this->session->data['country_id'] = $this->session->data['shipping_country_id'];
                $this->session->data['zone_id'] = $this->session->data['shipping_zone_id'];

                if (isset($this->session->data['shipping_postcode'])) {
                    $this->session->data['postcode'] = $this->session->data['shipping_postcode'];
                }
            }
        } else {
            unset($this->session->data['shipping_country_id']);
            unset($this->session->data['shipping_zone_id']);

            if ($version > 151) {
                $this->tax->setShippingAddress(0, 0);
            } else {
                $this->tax->setZone(0, 0);
            }

            if (!$this->customer->isLogged() && $this->config->get('config_tax_default') == 'shipping') {
                if ($version > 151) {
                    $this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
                } else {
                    $this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
                }
            }
        }

        if (!empty($address['postcode'])) {
            $this->session->data['shipping_postcode'] = $address['postcode'];
        }
    }
}