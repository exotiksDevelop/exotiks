<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutLogin extends SimpleController {
    private $_templateData = array();

    public function index() {
        $this->loadLibrary('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);

        if ($this->customer->isLogged()) {
            if ($this->simplecheckout->isAjaxRequest()) {
                return;
            } else {
                $this->simplecheckout->redirect($this->url->link('checkout/simplecheckout','','SSL'));
                return;
            }
        }

        $this->load->model('account/customer');

        $this->language->load('checkout/simplecheckout');

        $this->_templateData['error_login'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $error = false;

            if ($this->simplecheckout->getOpencartVersion() > 200) {
                $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

                if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
                    $this->_templateData['error_login'] = $this->language->get('error_attempts');
                    $this->simplecheckout->addError('login');
                    $error = true;
                }
            }

            if (!$error) {
                if (!empty($this->request->post['email']) && !empty($this->request->post['password']) && $this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                    unset($this->session->data['guest']);

                    if ($this->simplecheckout->getOpencartVersion() > 200) {
                        $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
                    }

                    if (($this->simplecheckout->getOpencartVersion() > 200 && $this->simplecheckout->getOpencartVersion() < 230) || ($this->simplecheckout->getOpencartVersion() >= 230 && $this->config->get('config_customer_activity'))) {
                        $this->load->model('account/activity');

                        $activity_data = array(
                            'customer_id' => $this->customer->getId(),
                            'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
                        );

                        $this->model_account_activity->addActivity('login', $activity_data);
                    }
                } else {
                    $this->_templateData['error_login'] = $this->language->get('error_login');
                    $this->simplecheckout->addError('login');

                    if ($this->simplecheckout->getOpencartVersion() > 200) {
                        $this->model_account_customer->addLoginAttempt($this->request->post['email']);
                    }
                }
            }
        }

        $this->_templateData['text_checkout_customer']        = $this->language->get('text_checkout_customer');
        $this->_templateData['text_checkout_customer_login']  = $this->language->get('text_checkout_customer_login');
        $this->_templateData['text_checkout_customer_cancel'] = $this->language->get('text_checkout_customer_cancel');
        $this->_templateData['text_forgotten']                = $this->language->get('text_forgotten');
        $this->_templateData['entry_email']                   = $this->language->get('entry_email');
        $this->_templateData['entry_password']                = $this->language->get('entry_password');
        $this->_templateData['button_login']                  = $this->language->get('button_login');

        $this->_templateData['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        if (isset($this->request->post['email'])) {
            $this->_templateData['email'] = trim($this->request->post['email']);
        } else {
            $this->_templateData['email'] = '';
        }

        $this->_templateData['additional_path'] = $this->simplecheckout->getAdditionalPath();
        $this->_templateData['has_error'] = $this->simplecheckout->hasError('login');

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_login', $this->_templateData));
    }
}
?>