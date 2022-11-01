<?php
include_once(DIR_SYSTEM . 'zrcode/library/forgotten_cart.php');

class ControllerModuleForgottenCart extends Controller {
    private $error = array();
    private $core = null;

    public function index() {
        $this->load->language('module/forgotten_cart');

        $this->document->setTitle(strip_tags($this->language->get('heading_title')));

        $this->load->model('module/forgotten_cart');
        $this->load->model('localisation/language');
        $this->load->model('setting/setting');

        if ($this->config->get('forgotten_cart_updated') != ZR_code\ForgottenCart::$version) {
            $this->model_module_forgotten_cart->db_update();
            $this->set_updated();
        }

        $db_data = $this->model_setting_setting->getSetting('forgotten_cart');
        $db_data['forgotten_cart_messages'] = $this->model_module_forgotten_cart->getMessages();

        $this->core = ZR_code\ForgottenCart::setData($db_data, $this->request->get, $this->request->post, 'forgotten_cart_', 'opencart');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_module_forgotten_cart->editSetting($this->core->getSettings());
            
            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('module/forgotten_cart', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_remove'] = $this->language->get('text_remove');
        $data['text_and'] = $this->language->get('text_and');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_percent'] = $this->language->get('text_percent');
        $data['text_sum'] = $this->language->get('text_sum');
        $data['text_customer_edit'] = $this->language->get('text_customer_edit');
        $data['text_send_message'] = $this->language->get('text_send_message');
        $data['text_send_message_repeated'] = $this->language->get('text_send_message_repeated');
        $data['text_subject'] = $this->language->get('text_subject');
        $data['text_subject_manager'] = $this->language->get('text_subject_manager');
        $data['text_message'] = $this->language->get('text_message');
        $data['text_message_manager'] = $this->language->get('text_message_manager');
        $data['text_template_products'] = $this->language->get('text_template_products');
        $data['text_template_shipping'] = $this->language->get('text_template_shipping');
        $data['text_template_coupon'] = $this->language->get('text_template_coupon');
        $data['text_template_coupon_manager'] = $this->language->get('text_template_coupon_manager');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_hour'] = $this->language->get('text_hour');
        $data['text_discount_setting'] = $this->language->get('text_discount_setting');
        $data['text_discount_message'] = $this->language->get('text_discount_message');
        $data['text_discount_repeated_message'] = $this->language->get('text_discount_repeated_message');
        $data['text_discount_messages'] = $this->language->get('text_discount_messages');
        $data['text_percent'] = $this->language->get('text_percent');
        $data['text_amount'] = $this->language->get('text_amount');
        $data['text_from'] = $this->language->get('text_from');
        $data['text_message_title'] = $this->language->get('text_message_title');
        $data['text_message_repeated_title'] = $this->language->get('text_message_repeated_title');
        $data['text_help_sitename'] = $this->language->get('text_help_sitename');
        $data['text_help_sitephone'] = $this->language->get('text_help_sitephone');
        $data['text_help_customername'] = $this->language->get('text_help_customername');
        $data['text_help_customer_email'] = $this->language->get('text_help_customer_email');
        $data['text_help_customer_phone'] = $this->language->get('text_help_customer_phone');
        $data['text_help_logo'] = $this->language->get('text_help_logo');
        $data['text_help_cart_link'] = $this->language->get('text_help_cart_link');
        $data['text_help_product_name'] = $this->language->get('text_help_product_name');
        $data['text_help_product_model'] = $this->language->get('text_help_product_model');
        $data['text_help_product_options'] = $this->language->get('text_help_product_options');
        $data['text_help_product_image'] = $this->language->get('text_help_product_image');
        $data['text_help_product_quantity'] = $this->language->get('text_help_product_quantity');
        $data['text_help_product_price'] = $this->language->get('text_help_product_price');
        $data['text_help_product_sum'] = $this->language->get('text_help_product_sum');
        $data['text_help_product_link'] = $this->language->get('text_help_product_link');
        $data['text_help_coupon_code'] = $this->language->get('text_help_coupon_code');
        $data['text_help_coupon_discount'] = $this->language->get('text_help_coupon_discount');
        $data['text_help_products_count'] = $this->language->get('text_help_products_count');
        $data['text_help_el'] = $this->language->get('text_help_el');
        $data['text_cron_link'] = $this->language->get('text_cron_link');
        $data['text_related_template_setting'] = $this->language->get('text_related_template_setting');
        $data['text_related_block'] = $this->language->get('text_related_block');
        $data['text_template_products_related'] = $this->language->get('text_template_products_related');
        $data['text_total'] = $this->language->get('text_total');
        $data['text_first_visit'] = $this->language->get('text_first_visit');
        $data['text_last_visit'] = $this->language->get('text_last_visit');
        $data['text_time_spent'] = $this->language->get('text_time_spent');
        $data['text_activation'] = $this->language->get('text_activation');
        $data['text_how_get_license'] = $this->language->get('text_how_get_license');
        $data['text_license'] = $this->language->get('text_license');
        
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_repeated_message'] = $this->language->get('entry_repeated_message');
        $data['entry_auto_send_message'] = $this->language->get('entry_auto_send_message');
        $data['entry_subject'] = $this->language->get('entry_subject');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_template_products'] = $this->language->get('entry_template_products');
        $data['entry_template_shipping'] = $this->language->get('entry_template_shipping');
        $data['entry_template_coupon'] = $this->language->get('entry_template_coupon');
        $data['entry_general_message_time'] = $this->language->get('entry_general_message_time');
        $data['entry_repeated_message_time'] = $this->language->get('entry_repeated_message_time');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_discount'] = $this->language->get('entry_discount');
        $data['entry_shipping'] = $this->language->get('entry_shipping');
        $data['entry_sum'] = $this->language->get('entry_sum');
        $data['entry_manager_notifi'] = $this->language->get('entry_manager_notifi');
        $data['entry_manager_notifi_email'] = $this->language->get('entry_manager_notifi_email');
        $data['entry_manager_notifi_time'] = $this->language->get('entry_manager_notifi_time');
        $data['entry_customer_notifi'] = $this->language->get('entry_customer_notifi');
        $data['entry_related_status'] = $this->language->get('entry_related_status');
        $data['entry_related_attribute'] = $this->language->get('entry_related_attribute');
        $data['entry_related_attribute_condition'] = $this->language->get('entry_related_attribute_condition');
        $data['entry_related_option'] = $this->language->get('entry_related_option');
        $data['entry_related_option_condition'] = $this->language->get('entry_related_option_condition');
        $data['entry_related_price_step'] = $this->language->get('entry_related_price_step');
        $data['entry_related_limit'] = $this->language->get('entry_related_limit');
        $data['entry_related_block'] = $this->language->get('entry_related_block');
        $data['entry_license_key'] = $this->language->get('entry_license_key');
        
        $data['help_sum'] = $this->language->get('help_sum');
        $data['help_javascript'] = $this->language->get('help_javascript');
        $data['help_cron'] = $this->language->get('help_cron');
        $data['help_manager_email'] = $this->language->get('help_manager_email');
        $data['help_price_step'] = $this->language->get('help_price_step');
        $data['help_condition'] = $this->language->get('help_condition');
        
        $data['button_discount_add'] = $this->language->get('button_discount_add');
        $data['button_discount_remove'] = $this->language->get('button_discount_remove');
        
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_cart'] = $this->language->get('column_cart');
        $data['column_date_time'] = $this->language->get('column_date_time');
        $data['column_last_page'] = $this->language->get('column_last_page');
        $data['column_action'] = $this->language->get('column_action');
        
        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_messages'] = $this->language->get('tab_messages');
        $data['tab_related'] = $this->language->get('tab_related');
        $data['tab_customers'] = $this->language->get('tab_customers');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['open'])) {
            $data['open_tab'] = $this->request->get['open'];
        } else {
            $data['open_tab'] = 'tab-general';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['errors']) && !$this->error) {
            $this->error = $this->session->data['errors'];
            unset($this->session->data['errors']);
        }

        foreach ($this->error as $key => $error) {
            $data[ 'error_' . $key ] = $error;
        }
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => strip_tags($this->language->get('heading_title')),
            'href' => $this->url->link('module/forgotten_cart', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('module/forgotten_cart', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_mail'] = $this->url->link('module/forgotten_cart/send_mail', 'token=' . $this->session->data['token'] . '&page=' . $page, 'SSL');
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $data['token'] = $this->session->data['token'];
        $data['cron_link'] = HTTP_CATALOG . "index.php?route=module/forgotten_cart/cron";
        $data['currency_code'] = $this->config->get('config_currency');
        $data['languages'] = $this->model_localisation_language->getLanguages();

        foreach ($data['languages'] as $key => $language) {
            if (!isset($language['image'])) {
                $data['languages'][$key]['image'] = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
            } else {
                $data['languages'][$key]['image'] = 'view/image/flags/' . $language['image'];
            }
        }

        $settings_data = $this->core->getSettingsData();
        $data = array_merge($data, $settings_data);

        $data['related_attributes'] = $this->model_module_forgotten_cart->getRelatedAttributes($data['forgotten_cart_related_attribute']);
        $data['related_options'] = $this->model_module_forgotten_cart->getRelatedOptions($data['forgotten_cart_related_option']);
        
        $ver_arr = explode(".", VERSION);
        
        if ($ver_arr['1'] == 0) {
            $customer_route = "sale/customer/edit";
        } else {
            $customer_route = "customer/customer/edit";
        }
        
        $customer_start = ($page - 1) * $this->config->get('config_limit_admin');
        $customer_limit = $this->config->get('config_limit_admin');
        $customers = $this->model_module_forgotten_cart->getCustomers($customer_start, $customer_limit);
        $customers_total = $this->model_module_forgotten_cart->getCustomersTotal();
        
        $data['customers'] = array();
        foreach ($customers as $customer) {
            $customer_info = $this->core->getCustomerInfo($customer);
            $products = array();
            $total = $customer_info['total'];

            foreach ($customer_info['products'] as $product) {
                $options = '';

                foreach ($product['options'] as $option) {
                    $options .= ' - ' . $option . '<br>';
                }

                $products[] = array(
                    'name'     => $product['name'],
                    'quantity' => $product['quantity'],
                    'options'  => $options,
                    'total'    => $this->currency->format($product['total'], $this->config->get('config_currency')),
                    'href'     => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
                );
            }

            $data['customers'][] = array(
                'customer_id'    => $customer['customer_id'],
                'name'           => $customer['firstname'] . " " . $customer['lastname'],
                'email'          => $customer['email'],
                'telephone'      => $customer['telephone'],
                'mail_status'    => $customer_info['mail_status'],
                'first_visit'    => ($customer_info['start_session_time']) ? date($this->language->get('forgotten_cart_date_format'), $customer_info['start_session_time']) : "",
                'last_visit'     => date($this->language->get('forgotten_cart_date_format'), $customer_info['last_activity']),
                'time_spent'     => $customer_info['time_spent'],
                'products'       => $products,
                'total'          => $this->currency->format($total, $this->config->get('config_currency')),
                'last_page'      => $customer_info['last_page'],
                'edit'           => $this->url->link($customer_route, 'token=' . $this->session->data['token'] . '&customer_id=' . $customer['customer_id'], 'SSL'),
                'remove'         => $this->url->link('module/forgotten_cart/remove_customer', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer['customer_id'] . '&page=' . $page, 'SSL')
            );
        }
        
        $pagination = new Pagination();
        $pagination->total = $customers_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('module/forgotten_cart', 'token=' . $this->session->data['token'] . '&open=tab-customers&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($customers_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customers_total - $this->config->get('config_limit_admin'))) ? $customers_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customers_total, ceil($customers_total / $this->config->get('config_limit_admin')));
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        if ($this->core->isLicensed()) {
            $this->response->setOutput($this->load->view('module/forgotten_cart.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('module/forgotten_cart_license.tpl', $data));
        }
    }

    public function send_mail() {
        $this->load->language('module/forgotten_cart');
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        if (isset($this->request->post['customer_id']) && isset($this->request->post['mail_type']) && $this->config->get('forgotten_cart_status')) {
            if ($this->validate_permission()) {
                $send_url = HTTP_CATALOG . "index.php?route=module/forgotten_cart/send&forgotten_cart_customer_id=" . (int)$this->request->post['customer_id'] . "&forgotten_cart_mail_type=" . (int)$this->request->post['mail_type'];
                $status = file_get_contents($send_url, false);

                if (empty($status)) {
                    $this->session->data['success'] = $this->language->get('text_success_message');
                } else {
                    $this->session->data['errors']['warning'] = $status;
                }
            } else {
                $this->session->data['errors'] = $this->error;
            }
        }

        $this->response->redirect($this->url->link('module/forgotten_cart', 'token=' . $this->session->data['token'] . '&open=tab-customers' . '&page=' . $page, 'SSL'));
    }
    
    public function remove_customer() {
        $this->load->language('module/forgotten_cart');
        $this->load->model('module/forgotten_cart');
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        if (isset($this->request->get['customer_id']) && $this->validate_permission()) {
            $this->model_module_forgotten_cart->delete_cart($this->request->get['customer_id']);
            $this->session->data['success'] = $this->language->get('text_success_remove_customer');
        } else {
            $this->session->data['errors'] = $this->error;
        }
        
        $this->response->redirect($this->url->link('module/forgotten_cart', 'token=' . $this->session->data['token'] . '&open=tab-customers' . '&page=' . $page, 'SSL'));
    }
    
    public function attribute_autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('module/forgotten_cart');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_name'  => $filter_name,
                'start'        => 0,
                'limit'        => $limit
            );

            $results = $this->model_module_forgotten_cart->getAttributes($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'attribute_id' => $result['attribute_id'],
                    'name'         => strip_tags(html_entity_decode($result['attribute_group'], ENT_QUOTES, 'UTF-8')) . " > " . strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function option_autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('module/forgotten_cart');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_name'  => $filter_name,
                'start'        => 0,
                'limit'        => $limit
            );

            $results = $this->model_module_forgotten_cart->getOptions($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'option_id' => $result['option_id'],
                    'name'      => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function install() {
        $this->load->model('module/forgotten_cart');
        $this->model_module_forgotten_cart->db_create();
    }

    protected function set_updated() {
        $module_setting = $this->model_setting_setting->getSetting('forgotten_cart');
        $module_setting['forgotten_cart_updated'] = ZR_code\ForgottenCart::$version;
        if ($this->config->get("forgotten_cart_аuto_send")) {
            $module_setting['forgotten_cart_auto_send'] = $this->config->get("forgotten_cart_аuto_send");
            $this->config->set("forgotten_cart_auto_send", $this->config->get("forgotten_cart_аuto_send"));
        }
        $this->model_setting_setting->editSetting('forgotten_cart', $module_setting);
        $this->config->set("forgotten_cart_updated", ZR_code\ForgottenCart::$version);
    }

    protected function validate_permission() {
        if(!$this->user->hasPermission('modify', 'module/forgotten_cart')){
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
    
    protected function validate() {
        $this->validate_permission();

        $errors = $this->core->validateSettingsData();

        foreach ($errors as $key => $error) {
            if (is_array($error)) {
                foreach ($error as $error_key => $error_code) {
                    $this->error[$key][$error_key] = $this->language->get($error_code);
                }
            } else {
                $this->error[$key] = $this->language->get($error);
            }
        }
            
        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if (isset($errors['related'])) {
            $this->error['warning'] = $this->language->get($errors['related']);
            $this->request->get['open'] = "tab-related";
        }
            
        return !$this->error;
    }
}