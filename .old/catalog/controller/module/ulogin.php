<?php

class ControllerModuleUlogin extends Controller {

    public function index($setting) {
        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }
        $this->load->language('module/ulogin');
        $this->load->model('module/ulogin');

        require_once 'lib-ulogin/ulogin.php';

        $config = array(
            'providers' => $this->config->get('ulogin_providers'),
            'hidden' => $this->config->get('ulogin_hidden'),
            'type' => $this->config->get('ulogin_type'),
            'redirect_uri' => '/ulogin.php',
            'lang' => $this->language->get('code'),
        );

        $data['ulogin'] = Ulogin::factory($config);
        
        $data['text_register_login'] = $this->language->get('text_register_login');

        if (isset($this->session->data['ulogin_token'])) {
            $token = $this->session->data['ulogin_token'];
            $this->login($token);
            unset($this->session->data['ulogin_token']);
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ulogin.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/ulogin.tpl', $data);
        }
        else {
            return $this->load->view('default/template/module/ulogin.tpl', $data);
        }
    }

    public function login($token) {
        if (!($domain = parse_url(HTTPS_SERVER, PHP_URL_HOST))) {
            $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        }

        $s = file_get_contents('http://ulogin.ru/token.php?token=' . $token['token'] . '&host=' . $domain);
        $user = json_decode($s, true);
        //Debug::vars($user);die;
        //$user['network'] - соц. сеть, через которую авторизовался пользователь
        //$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
        //$user['first_name'] - имя пользователя
        //$user['last_name'] - фамилия пользователя 

        if (isset($user['email'])) {
            if ($this->customer->login($user['email'], '', true)) {
                unset($this->session->data['guest']);

                // Default Shipping Address
                $this->load->model('account/address');

                if ($this->config->get('config_tax_customer') == 'payment') {
                    $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                if ($this->config->get('config_tax_customer') == 'shipping') {
                    $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
                );

                $this->model_account_activity->addActivity('login', $activity_data);

                // Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
                if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
                    $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
                }
                else {
                    $this->response->redirect($this->url->link('account/account', '', 'SSL'));
                }
            }
            else {

                $data=array(
                    'customer_group_id'=>$this->config->get('config_customer_group_id'),                  
                    'firstname'=>(isset($user['first_name']))?$user['first_name']:'',                    
                    'lastname'=>(isset($user['last_name']))?$user['last_name']:'',                    
                    'telephone'=>(isset($user['phone']))?$user['phone']:'',                  
                    'city'=>(isset($user['city']))?$user['city']:'',                 
                    'email'=>$user['email'],                  
                    'password'=>md5(uniqid(rand(),true)),                  
                );
                
                $this->load->model('account/customer');
                $this->model_account_customer->addCustomer($data);

                $this->customer->login($this->request->post['email'], $this->request->post['password']);

                unset($this->session->data['guest']);

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->request->post['firstname'] . ' ' . $this->request->post['lastname']
                );

                $this->model_account_activity->addActivity('register', $activity_data);

                $this->response->redirect($this->url->link('account/success'));
            }
        }
    }

}
