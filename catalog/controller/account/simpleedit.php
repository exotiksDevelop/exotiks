<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerAccountSimpleEdit extends SimpleController {
    private $_templateData = array();

    public function index($args = null) {

        $this->loadLibrary('simple/simpleedit');

        $this->simpleedit = SimpleEdit::getInstance($this->registry);

        if (!$this->customer->isLogged()) {
            $this->simpleedit->redirect($this->url->link('account/login','','SSL'));
        }

        $this->language->load('account/edit');

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
            'href'      => $this->url->link('account/simpleedit', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['action'] = 'index.php?'.$this->simpleedit->getAdditionalParams().'route=account/simpleedit';

        $this->_templateData['heading_title']   = $this->language->get('heading_title');
        $this->_templateData['button_continue'] = $this->language->get('button_continue');

        $this->_templateData['error_warning'] = '';

        $this->simpleedit->clearSimpleSession();

        $this->simpleedit->init();

        $this->_templateData['rows'] = $this->simpleedit->getRows();

        $this->_templateData['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/customer');

            $this->simpleedit->clearUnusedFields();

            if ($this->simpleedit->isFieldUsed('password') && $this->session->data['simple']['edit']['password']) {
                $this->model_account_customer->editPassword($this->customer->getEmail(), $this->session->data['simple']['edit']['password']);
            }

            // hack for mijoshop
            unset($this->session->data['simple']['edit']['password']);

            if ($this->simpleedit->getOpencartVersion() < 300) {
                $this->model_account_customer->editCustomer($this->session->data['simple']['edit']);
            } else {
                $this->model_account_customer->editCustomer($this->customer->getId(), $this->session->data['simple']['edit']);
            }            

            $this->simpleedit->saveCustomFields(array('edit'), 'customer', $this->customer->getId());

            if ($this->simpleedit->isFieldUsed('customer_group_id')) {
                $this->simpleedit->editCustomerGroupId($this->session->data['simple']['edit']['customer_group_id']);
            }

            if ($this->simpleedit->isFieldUsed('newsletter')) {
                $this->model_account_customer->editNewsletter($this->session->data['simple']['edit']['newsletter']);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if (($this->simpleedit->getOpencartVersion() > 200 && $this->simpleedit->getOpencartVersion() < 230) || ($this->simpleedit->getOpencartVersion() >= 230 && $this->simpleedit->getOpencartVersion() < 300 && $this->config->get('config_customer_activity'))) {
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->customer->getId(),
                    'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
                );

                $this->model_account_activity->addActivity('edit', $activity_data);
            }

            if ($this->simpleedit->isAjaxRequest()) {
                $this->_templateData['redirect'] = $this->url->link('account/account', '', 'SSL');
            } else {
                $this->simpleedit->redirect($this->url->link('account/account','','SSL'));
            }
        }

        $this->_templateData['ajax']                = $this->simpleedit->isAjaxRequest();
        $this->_templateData['additional_path']     = $this->simpleedit->getAdditionalPath();
        $this->_templateData['additional_params']   = $this->simpleedit->getAdditionalParams();
        
        $this->_templateData['scroll_to_error']            = $this->simpleedit->getCommonSetting('scrollingChanged') ? $this->simpleedit->getCommonSetting('scrollToError') : $this->simpleedit->getSettingValue('scrollToError');

        $this->_templateData['notification_default']       = $this->simpleedit->getCommonSetting('notificationChanged') ? $this->simpleedit->getCommonSetting('notificationDefault') : true;
        $this->_templateData['notification_toasts']        = $this->simpleedit->getCommonSetting('notificationToasts');
        $this->_templateData['notification_position']      = $this->simpleedit->getCommonSetting('notificationPosition');
        $this->_templateData['notification_timeout']       = $this->simpleedit->getCommonSetting('notificationTimeout');
        $this->_templateData['notification_check_form']    = $this->simpleedit->getCommonSetting('notificationCheckForm');

        $this->_templateData['notification_check_form_text'] = '';

        $notification_check_form_text = $this->simpleedit->getCommonSetting('notificationCheckFormText');

        $language_code = $this->simpleedit->getCurrentLanguageCode();

        if (!empty($notification_check_form_text) && !empty($notification_check_form_text[$language_code])) {
            $this->_templateData['notification_check_form_text'] = $notification_check_form_text[$language_code];
        }
        
        $this->_templateData['javascript_callback'] = $this->simpleedit->getJavascriptCallback();

        $this->_templateData['display_error']       = $this->simpleedit->displayError();

        $this->_templateData['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->_templateData['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        $childrens = array();

        if (!$this->simpleedit->isAjaxRequest() && !$this->_templateData['popup'] && !$this->_templateData['as_module']) {
            $childrens = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );

            $this->_templateData['simple_header'] = $this->simpleedit->getLinkToHeaderTpl();
            $this->_templateData['simple_footer'] = $this->simpleedit->getLinkToFooterTpl();
        }

        $this->setOutputContent(trim($this->renderPage('account/simpleedit', $this->_templateData, $childrens)));
    }

    private function validate() {
        $error = false;

        if (!$this->simpleedit->validateFields()) {
            $error = true;
        }

        return !$error;
    }
}
