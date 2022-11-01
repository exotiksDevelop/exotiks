<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerAccountSimpleaddress extends SimpleController {
    private $_templateData = array();

    public function insert($args = null) {

        $this->loadLibrary('simple/simpleaddress');

        $this->simpleaddress = Simpleaddress::getInstance($this->registry);

        if (!$this->customer->isLogged()) {
            $this->simpleaddress->redirect($this->url->link('account/login','','SSL'));
        }

        $this->language->load('account/address');

        if (empty($args)) {
            $this->document->setTitle($this->language->get('heading_title'));
        }

        $this->_templateData['breadcrumbs'] = array();

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/address', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit_address'),
            'href'      => $this->url->link('account/simpleaddress/insert', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['action'] = 'index.php?'.$this->simpleaddress->getAdditionalParams().'route=account/simpleaddress/insert';

        $this->_templateData['heading_title']   = $this->language->get('heading_title');
        $this->_templateData['button_continue'] = $this->language->get('button_continue');

        $this->_templateData['error_warning'] = '';

        $this->request->get['address_id'] = 0;

        $this->simpleaddress->clearSimpleSession();

        $this->simpleaddress->init();

        $this->_templateData['rows'] = $this->simpleaddress->getRows();
        $this->_templateData['hidden_rows'] = $this->simpleaddress->getHiddenRows();

        $this->_templateData['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/address');

            $this->simpleaddress->clearUnusedFields();

            $addressInfo = $this->session->data['simple']['address'];

            if ($this->simpleaddress->getOpencartVersion() < 300) {
                $addressId = $this->model_account_address->addAddress($addressInfo);
            } else {
                $addressId = $this->model_account_address->addAddress($this->customer->getId(), $addressInfo);
            }
            

            $this->simpleaddress->saveCustomFields(array('address'), 'address', $addressId);

            if (($this->simpleaddress->getOpencartVersion() > 200 && $this->simpleaddress->getOpencartVersion() < 230) || ($this->simpleaddress->getOpencartVersion() >= 230 && $this->simpleaddress->getOpencartVersion() < 300 && $this->config->get('config_customer_activity'))) {
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->customer->getId(),
                    'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
                );

                $this->model_account_activity->addActivity('address_add', $activity_data);
            }

            if ($this->simpleaddress->getOpencartVersion() < 200) {
                $this->session->data['success'] = $this->language->get('text_insert');
            } else {
                $this->session->data['success'] = $this->language->get('text_add');
            }

            if ($this->simpleaddress->isAjaxRequest()) {
                $this->_templateData['redirect'] = $this->url->link('account/address', '', 'SSL');
            } else {
                $this->simpleaddress->redirect($this->url->link('account/address','','SSL'));
            }
        }

        $this->_templateData['ajax']                = $this->simpleaddress->isAjaxRequest();
        $this->_templateData['additional_path']     = $this->simpleaddress->getAdditionalPath();
        $this->_templateData['additional_params']   = $this->simpleaddress->getAdditionalParams();
        $this->_templateData['use_autocomplete']    = $this->simpleaddress->getCommonSetting('useAutocomplete');
        $this->_templateData['use_google_api']      = $this->simpleaddress->getCommonSetting('useGoogleApi');
        
        $this->_templateData['scroll_to_error']            = $this->simpleaddress->getCommonSetting('scrollingChanged') ? $this->simpleaddress->getCommonSetting('scrollToError') : $this->simpleaddress->getSettingValue('scrollToError');

        $this->_templateData['notification_default']       = $this->simpleaddress->getCommonSetting('notificationChanged') ? $this->simpleaddress->getCommonSetting('notificationDefault') : true;
        $this->_templateData['notification_toasts']        = $this->simpleaddress->getCommonSetting('notificationToasts');
        $this->_templateData['notification_position']      = $this->simpleaddress->getCommonSetting('notificationPosition');
        $this->_templateData['notification_timeout']       = $this->simpleaddress->getCommonSetting('notificationTimeout');
        $this->_templateData['notification_check_form']    = $this->simpleaddress->getCommonSetting('notificationCheckForm');

        $this->_templateData['notification_check_form_text'] = '';

        $notification_check_form_text = $this->simpleaddress->getCommonSetting('notificationCheckFormText');

        $language_code = $this->simpleaddress->getCurrentLanguageCode();

        if (!empty($notification_check_form_text) && !empty($notification_check_form_text[$language_code])) {
            $this->_templateData['notification_check_form_text'] = $notification_check_form_text[$language_code];
        }

        $this->_templateData['javascript_callback'] = $this->simpleaddress->getJavascriptCallback();

        $this->_templateData['display_error']       = $this->simpleaddress->displayError();

        $this->_templateData['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->_templateData['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        $childrens = array();

        if (!$this->simpleaddress->isAjaxRequest() && !$this->_templateData['popup'] && !$this->_templateData['as_module']) {
            $childrens = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );

            $this->_templateData['simple_header'] = $this->simpleaddress->getLinkToHeaderTpl();
            $this->_templateData['simple_footer'] = $this->simpleaddress->getLinkToFooterTpl();
        }

        $this->setOutputContent(trim($this->renderPage('account/simpleaddress', $this->_templateData, $childrens)));
    }

    public function update($args = null) {

        $this->loadLibrary('simple/simpleaddress');

        $this->simpleaddress = Simpleaddress::getInstance($this->registry);

        if (!$this->customer->isLogged()) {
            $this->simpleaddress->redirect($this->url->link('account/login','','SSL'));
        }

        $addressId = $this->request->get['address_id'];

        $this->language->load('account/address');

        if (empty($args)) {
            $this->document->setTitle($this->language->get('heading_title'));
        }

        $this->_templateData['breadcrumbs'] = array();

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/address', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit_address'),
            'href'      => $this->url->link('account/simpleaddress/update', 'address_id=' . $addressId, 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['action'] = 'index.php?'.$this->simpleaddress->getAdditionalParams().'route=account/simpleaddress/update&address_id=' . $addressId;

        $this->_templateData['heading_title']   = $this->language->get('heading_title');
        $this->_templateData['button_continue'] = $this->language->get('button_continue');

        $this->_templateData['error_warning'] = '';

        $this->simpleaddress->clearSimpleSession();

        $this->simpleaddress->init();

        $this->_templateData['rows'] = $this->simpleaddress->getRows();
        $this->_templateData['hidden_rows'] = $this->simpleaddress->getHiddenRows();

        $this->_templateData['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/address');

            $this->simpleaddress->clearUnusedFields();

            $addressInfo = $this->session->data['simple']['address'];

            $this->model_account_address->editAddress($addressId, $addressInfo);

            $this->simpleaddress->saveCustomFields(array('address'), 'address', $addressId);

            // Default Shipping Address
            if (isset($this->session->data['shipping_address_id']) && ($addressId == $this->session->data['shipping_address_id'])) {
                $this->session->data['shipping_country_id'] = $addressInfo['country_id'];
                $this->session->data['shipping_zone_id'] = $addressInfo['zone_id'];
                $this->session->data['shipping_postcode'] = $addressInfo['postcode'];

                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
            }

            // Default Payment Address
            if (isset($this->session->data['payment_address_id']) && ($addressId == $this->session->data['payment_address_id'])) {
                $this->session->data['payment_country_id'] = $addressInfo['country_id'];
                $this->session->data['payment_zone_id'] = $addressInfo['zone_id'];

                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
            }

            if (($this->simpleaddress->getOpencartVersion() > 200 && $this->simpleaddress->getOpencartVersion() < 230) || ($this->simpleaddress->getOpencartVersion() >= 230 && $this->simpleaddress->getOpencartVersion() < 300 && $this->config->get('config_customer_activity'))) {
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->customer->getId(),
                    'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
                );

                $this->model_account_activity->addActivity('address_edit', $activity_data);
            }

            if ($this->simpleaddress->getOpencartVersion() < 200) {
                $this->session->data['success'] = $this->language->get('text_update');
            } else {
                $this->session->data['success'] = $this->language->get('text_edit');
            }

            if ($this->simpleaddress->isAjaxRequest()) {
               $this->_templateData['redirect'] = $this->url->link('account/address', '', 'SSL');
            } else {
                $this->simpleaddress->redirect($this->url->link('account/address','','SSL'));
            }
        }

        $this->_templateData['ajax']                = $this->simpleaddress->isAjaxRequest();
        $this->_templateData['additional_path']     = $this->simpleaddress->getAdditionalPath();
        $this->_templateData['additional_params']   = $this->simpleaddress->getAdditionalParams();
        $this->_templateData['use_autocomplete']    = $this->simpleaddress->getCommonSetting('useAutocomplete');
        $this->_templateData['use_google_api']      = $this->simpleaddress->getSettingValue('useGoogleApi');
        
        $this->_templateData['scroll_to_error']            = $this->simpleaddress->getCommonSetting('scrollingChanged') ? $this->simpleaddress->getCommonSetting('scrollToError') : $this->simpleaddress->getSettingValue('scrollToError');

        $this->_templateData['notification_default']       = $this->simpleaddress->getCommonSetting('notificationChanged') ? $this->simpleaddress->getCommonSetting('notificationDefault') : true;
        $this->_templateData['notification_toasts']        = $this->simpleaddress->getCommonSetting('notificationToasts');
        $this->_templateData['notification_position']      = $this->simpleaddress->getCommonSetting('notificationPosition');
        $this->_templateData['notification_timeout']       = $this->simpleaddress->getCommonSetting('notificationTimeout');
        $this->_templateData['notification_check_form']    = $this->simpleaddress->getCommonSetting('notificationCheckForm');

        $this->_templateData['notification_check_form_text'] = '';

        $notification_check_form_text = $this->simpleaddress->getCommonSetting('notificationCheckFormText');

        $language_code = $this->simpleaddress->getCurrentLanguageCode();

        if (!empty($notification_check_form_text) && !empty($notification_check_form_text[$language_code])) {
            $this->_templateData['notification_check_form_text'] = $notification_check_form_text[$language_code];
        }

        $this->_templateData['javascript_callback'] = $this->simpleaddress->getJavascriptCallback();

        $this->_templateData['display_error']       = $this->simpleaddress->displayError();

        $this->_templateData['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->_templateData['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        $childrens = array();

        if (!$this->simpleaddress->isAjaxRequest() && !$this->_templateData['popup'] && !$this->_templateData['as_module']) {
            $childrens = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );

            $this->_templateData['simple_header'] = $this->simpleaddress->getLinkToHeaderTpl();
            $this->_templateData['simple_footer'] = $this->simpleaddress->getLinkToFooterTpl();
        }

        $this->setOutputContent(trim($this->renderPage('account/simpleaddress', $this->_templateData, $childrens)));
    }

    private function validate() {
        $error = false;

        if (!$this->simpleaddress->validateFields()) {
            $error = true;
        }

        return !$error;
    }
}
