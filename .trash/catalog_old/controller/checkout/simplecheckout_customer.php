<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutCustomer extends SimpleController {
    private $_templateData = array();

    private function init() {
        $this->loadLibrary('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);

        $this->language->load('checkout/simplecheckout');

        $get_route = isset($_GET['route']) ? $_GET['route'] : (isset($_GET['_route_']) ? $_GET['_route_'] : '');

        if ($get_route == 'checkout/simplecheckout_customer') {
            $this->simplecheckout->init('customer');
        }
    }

    public function index() {
        $this->init();

        if ($this->simplecheckout->isBlockHidden('customer')) {
            return;
        }

        $this->_templateData['text_checkout_customer']       = $this->language->get('text_checkout_customer');
        $this->_templateData['text_checkout_customer_login'] = $this->language->get('text_checkout_customer_login');
        $this->_templateData['text_you_will_be_registered']  = $this->language->get('text_you_will_be_registered');
        $this->_templateData['text_account_created']         = $this->language->get('text_account_created');
        $this->_templateData['entry_address_same']           = $this->language->get('entry_address_same');

        $this->_templateData['display_login']               = !$this->customer->isLogged() && $this->simplecheckout->getSettingValue('displayLogin', 'customer');
        $this->_templateData['display_registered']          = !empty($this->session->data['simple']['registered']) ? true : false;

        $this->_templateData['rows'] = $this->simplecheckout->getRows('customer');

        if (!$this->simplecheckout->validateFields('customer')) {
            $this->simplecheckout->addError('customer');
        }

        unset($this->session->data['simple']['registered']);

        $this->_templateData['display_header']              = $this->simplecheckout->getSettingValue('displayHeader', 'customer');
        $this->_templateData['display_you_will_registered'] = !$this->customer->isLogged() && $this->simplecheckout->getSettingValue('displayYouWillRegistered', 'customer') && $this->session->data['simple']['customer']['register'] && !$this->simplecheckout->isFieldUsed('register', 'customer');
        $this->_templateData['display_error']               = $this->simplecheckout->displayError('customer');
        $this->_templateData['has_error']                   = $this->simplecheckout->hasError('customer');
        $this->_templateData['hide']                        = $this->simplecheckout->isBlockHidden('customer');

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_customer', $this->_templateData));
    }

    public function update_session() {
        $this->init();

        if (empty($this->session->data['simple']['customer'])) {
            return;
        }

        $version = $this->simplecheckout->getOpencartVersion();

        $customer = $this->session->data['simple']['customer'];
        
        if (!$this->customer->isLogged()) {
            foreach ($customer as $key => $value) {
                if ($key == 'register') {
                    continue;
                }

                $this->session->data['guest'][$key] = $value;
            }
        }
        
        if (empty($this->session->data['guest']['customer_group_id'])) {
            $this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
        }
    }
}
?>