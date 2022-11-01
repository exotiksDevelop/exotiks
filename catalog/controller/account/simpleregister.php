<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerAccountSimpleRegister extends SimpleController {
    private $_templateData = array();

    public function index($args = null) {

        $this->loadLibrary('simple/simpleregister');

        $this->simpleregister = SimpleRegister::getInstance($this->registry);

        if ($this->customer->isLogged()) {
            $this->simpleregister->redirect($this->url->link('account/account','','SSL'));
        }

        $this->language->load('account/register');
        $this->language->load('account/simpleregister');

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
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/simpleregister', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['action'] = 'index.php?'.$this->simpleregister->getAdditionalParams().'route=account/simpleregister';

        $this->_templateData['heading_title']        = $this->language->get('heading_title');
        $this->_templateData['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
        $this->_templateData['login_link'] = $this->url->link('account/login', '', 'SSL');
        $this->_templateData['button_continue']      = $this->language->get('button_continue');

        $this->_templateData['error_warning'] = '';
        $this->_templateData['error_agreement'] = '';

        $this->simpleregister->clearSimpleSession();

        $this->simpleregister->init();

        $this->_templateData['rows'] = $this->simpleregister->getRows();
        $this->_templateData['hidden_rows'] = $this->simpleregister->getHiddenRows();

        $this->_templateData['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->_templateData['agreements'] = !empty($this->request->post['agreements']) ? $this->request->post['agreements'] : array();
        } else {
            if ($this->simpleregister->getSettingValue('agreementCheckboxInit')) {
                if ($this->simpleregister->getSettingValue('agreementType') == 2) {
                    $agreement_id = $this->simpleregister->getSettingValue('agreementId');  
                    $agreements = $this->simpleregister->getSettingValue('agreementIds');  

                    if ($agreement_id) {
                        $this->_templateData['agreements'] = array($agreement_id);
                    } elseif (!empty($agreements) && is_array($agreements)) {
                        $this->_templateData['agreements'] = $agreements;
                    } else {
                        $this->_templateData['agreements'] = array();
                    }
                } else {
                    $this->_templateData['agreements'] = array('all');
                }
            } else {
                $this->_templateData['agreements'] = array();
            }            
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/customer');
            $this->load->model('account/address');

            $this->simpleregister->clearUnusedFields();

            $info = $this->session->data['simple']['register'];

            if (empty($info['password'])) {
                $this->load->model('tool/simpleapimain');

                if (method_exists($this->model_tool_simpleapimain, 'getRandomPassword') || property_exists($this->model_tool_simpleapimain, 'getRandomPassword') || (method_exists($this->model_tool_simpleapimain, 'isExistForSimple') && $this->model_tool_simpleapimain->isExistForSimple('getRandomPassword'))) {
                    $info['password'] = $this->model_tool_simpleapimain->getRandomPassword();
                }
            }

            // fix for old versions
            $tmpCustomerGroupId = $this->config->get('config_customer_group_id');
            $this->config->set('config_customer_group_id', $info['customer_group_id']);

            $this->model_account_customer->addCustomer($info);

            $this->config->set('config_customer_group_id', $tmpCustomerGroupId);

            if ($this->simpleregister->getOpencartVersion() > 200) {
                $this->model_account_customer->deleteLoginAttempts($info['email']);
            }

            $this->customer->login($info['email'], $info['password']);

            $customerId = 0;
            $addressId = 0;

            if ($this->simpleregister->getOpencartVersion() < 300) {
                if ($this->customer->isLogged()) {
                    $customerId = $this->customer->getId();
                    $addressId = $this->customer->getAddressId();
                } else {
                    $customerInfo = $this->simpleregister->getCustomerInfoByEmail($info['email']);
                    $customerId = $customerInfo['customer_id'];
                    $addressId = $customerInfo['address_id'];
                }
            } else {
                if ($this->customer->isLogged()) {
                    $customerId = $this->customer->getId();
                } else {
                    $customerInfo = $this->simpleregister->getCustomerInfoByEmail($info['email']);
                    $customerId = $customerInfo['customer_id'];
                }

                if (!$this->simpleregister->isAddressEmpty($info)) {
                    $info['default'] = true;
                    $addressId = $this->model_account_address->addAddress($customerId, $info);
                }
            }

            $this->simpleregister->saveCustomFields(array('register'), 'customer', $customerId);

            if ($addressId) {
                $this->simpleregister->saveCustomFields(array('register'), 'address', $addressId);
            }

            if (($this->simpleregister->getOpencartVersion() > 200 && $this->simpleregister->getOpencartVersion() < 230) || ($this->simpleregister->getOpencartVersion() >= 230 && $this->simpleregister->getOpencartVersion() < 300 && $this->config->get('config_customer_activity'))) {
                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $customerId,
                    'name'        => $info['firstname'] . ' ' . $info['lastname']
                );

                $this->model_account_activity->addActivity('register', $activity_data);
            }

            // Default Shipping Address
            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_country_id'] = $info['country_id'];
                $this->session->data['shipping_zone_id']    = $info['zone_id'];
                $this->session->data['shipping_postcode']   = $info['postcode'];
            }

            // Default Payment Address
            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_country_id'] = $info['country_id'];
                $this->session->data['payment_zone_id']    = $info['zone_id'];
            }

            if ($this->simpleregister->isAjaxRequest()) {
                $this->_templateData['redirect'] = $this->url->link('account/success');
            } else {
                $this->simpleregister->redirect($this->url->link('account/success'));
            }
        }

        $this->_templateData['ajax']                       = $this->simpleregister->isAjaxRequest();
        $this->_templateData['additional_path']            = $this->simpleregister->getAdditionalPath();
        $this->_templateData['additional_params']          = $this->simpleregister->getAdditionalParams();
        $this->_templateData['display_agreement_checkbox'] = $this->simpleregister->getSettingValue('displayAgreementCheckbox');
        $this->_templateData['use_autocomplete']           = $this->simpleregister->getCommonSetting('useAutocomplete');
        $this->_templateData['use_google_api']             = $this->simpleregister->getCommonSetting('useGoogleApi');
        
        $this->_templateData['scroll_to_error']            = $this->simpleregister->getCommonSetting('scrollingChanged') ? $this->simpleregister->getCommonSetting('scrollToError') : $this->simpleregister->getSettingValue('scrollToError');

        $this->_templateData['notification_default']       = $this->simpleregister->getCommonSetting('notificationChanged') ? $this->simpleregister->getCommonSetting('notificationDefault') : true;
        $this->_templateData['notification_toasts']        = $this->simpleregister->getCommonSetting('notificationToasts');
        $this->_templateData['notification_position']      = $this->simpleregister->getCommonSetting('notificationPosition');
        $this->_templateData['notification_timeout']       = $this->simpleregister->getCommonSetting('notificationTimeout');
        $this->_templateData['notification_check_form']    = $this->simpleregister->getCommonSetting('notificationCheckForm');

        $this->_templateData['notification_check_form_text'] = '';

        $notification_check_form_text = $this->simpleregister->getCommonSetting('notificationCheckFormText');

        $language_code = $this->simpleregister->getCurrentLanguageCode();

        if (!empty($notification_check_form_text) && !empty($notification_check_form_text[$language_code])) {
            $this->_templateData['notification_check_form_text'] = $notification_check_form_text[$language_code];
        }

        $this->_templateData['javascript_callback']        = $this->simpleregister->getJavascriptCallback();

        $this->_templateData['display_error']              = $this->simpleregister->displayError();

        $this->_templateData['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->_templateData['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        if ($this->_templateData['display_agreement_checkbox']) {
            $disable_popup = $this->_templateData['popup'] ? true : $this->simpleregister->getSettingValue('agreementDisablePopup');

            if (!$disable_popup) {
                $seo_url = $this->config->get('config_seo_url');
                $this->config->set('config_seo_url', false);
            }

            $agreement_id = $this->simpleregister->getSettingValue('agreementId');
            $lang_id = ($this->config->get('config_template') == 'shoppica' || $this->config->get('config_template') == 'shoppica2') ? 'text_agree_shoppica' : 'text_agree';
            $agreement_text = $this->language->get($lang_id);  

            $disable_popup = $this->_templateData['popup'] ? true : $this->simpleregister->getSettingValue('agreementDisablePopup');

            if ($disable_popup) {
                $agreement_text = str_replace('href=', 'target="_blank" href=', $agreement_text);
                $agreement_text = preg_replace('/colorbox|fancybox|agree/', '', $agreement_text);
            }

            $this->_templateData['text_agreements'] = array();

            if ($agreement_id) {
                $title = $this->simpleregister->getInformationTitle($agreement_id);
                
                if ($this->simpleregister->getSettingValue('agreementType') == 2) {
                    $this->_templateData['text_agreements'][$agreement_id] = sprintf($agreement_text, $this->url->link($this->simpleregister->getInformationRoute($disable_popup), $this->simpleregister->getAdditionalParams() . 'information_id=' . $agreement_id, 'SSL'), $title, $title);
                } else {
                    $this->_templateData['text_agreements']['all'] = sprintf($agreement_text, $this->url->link($this->simpleregister->getInformationRoute($disable_popup), $this->simpleregister->getAdditionalParams() . 'information_id=' . $agreement_id, 'SSL'), $title, $title);
                }

                $errors[] = '<div class="agreement_' . $agreement_id . '">' . sprintf($this->language->get('error_agree'), $title) . '</div>';

                $this->_templateData['error_warning_agreement'] = implode('', $errors);
            } else {
                $agreements = $this->simpleregister->getSettingValue('agreementIds');   

                if (!empty($agreements) && is_array($agreements)) {
                    if ($this->simpleregister->getSettingValue('agreementType') == 2) {
                        $errors = array();

                        foreach ($agreements as $agreement_id) {
                            $title = $this->simpleregister->getInformationTitle($agreement_id);
                            
                            $this->_templateData['text_agreements'][$agreement_id] = sprintf($agreement_text, $this->url->link($this->simpleregister->getInformationRoute($disable_popup), $this->simpleregister->getAdditionalParams() . 'information_id=' . $agreement_id, 'SSL'), $title, $title);
                            
                            $errors[] = '<div class="agreement_' . $agreement_id . '">' . sprintf($this->language->get('error_agree'), $title) . '</div>';
                        }

                        $this->_templateData['error_warning_agreement'] = implode('', $errors);
                    } else {
                        $agreement_link = '';

                        if (@preg_match('/<a.+a>/', $agreement_text, $matches)) {
                            $agreement_link = $matches[0];
                            
                            $agreement_text = @preg_replace('/<a.+a>/', '%s', $agreement_text);
                        }

                        $links = array();
                        $errors = array();

                        foreach ($agreements as $agreement_id) {
                            $title = $this->simpleregister->getInformationTitle($agreement_id);
                            
                            $links[] = sprintf($agreement_link, $this->url->link($this->simpleregister->getInformationRoute($disable_popup), $this->simpleregister->getAdditionalParams() . 'information_id=' . $agreement_id, 'SSL'), $title, $title);
                            
                            $errors[] = '<div>' . sprintf($this->language->get('error_agree'), $title) . '</div>';
                        }

                        $this->_templateData['text_agreements']['all'] = sprintf($agreement_text, implode(', ', $links));

                        $this->_templateData['error_warning_agreement'] = implode('', $errors);
                    }
                }
            }

            if (!$disable_popup) {
                $this->config->set('config_seo_url', $seo_url);
            }
        }

        $childrens = array();   

        if (!$this->simpleregister->isAjaxRequest() && !$this->_templateData['popup'] && !$this->_templateData['as_module']) {
            $childrens = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );

            $this->_templateData['simple_header'] = $this->simpleregister->getLinkToHeaderTpl();
            $this->_templateData['simple_footer'] = $this->simpleregister->getLinkToFooterTpl();
        }

        $this->setOutputContent(trim($this->renderPage('account/simpleregister', $this->_templateData, $childrens)));
    }

    private function validate() {
        $error = false;

        if ($this->simpleregister->getSettingValue('displayAgreementCheckbox')) {
            $agreement_id = $this->simpleregister->getSettingValue('agreementId');  
            $agreements = $this->simpleregister->getSettingValue('agreementIds');  

            if ($agreement_id) {
                $agreements = array($agreement_id);
            } elseif (!empty($agreements) && is_array($agreements)) {
                $agreements = $agreements;
            } else {
                $agreements = array();
            }

            foreach ($agreements as $agreement_id) {
                $find = $agreement_id;

                if ($this->simpleregister->getSettingValue('agreementType') != 2) {
                    $find = 'all';
                }

                if (!in_array($find, $this->_templateData['agreements'])) {
                    $this->_templateData['error_agreement'] = true;
                    $error = true;
                }            
            }
        }

        if (!$this->simpleregister->validateFields()) {
            $error = true;
        }

        return !$error;
    }
}
