<?php
class ControllerPaymentSbrfOnline extends Controller {    
    private $data = array(); 

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('payment/sbrf_online');
    }

    public function index() {
        $this->load->model('checkout/order');
        $this->load->model('payment/sbrf_online');

        $success_page = $this->config->get('sbrf_online_page_success');
        $standart_success = !trim(strip_tags($success_page[$this->config->get('config_language_id')]));

        $data = $this->_setData(array(
            'button_confirm',
            'text_instruction',
            'text_description',
            'text_payment',
            'continue'  => $standart_success ? $this->url->link('checkout/success') : $this->url->link('payment/sbrf_online/sbrf_online_success')
        ));

        $bank = $this->config->get('sbrf_online_bank');
        $bank = nl2br($bank[$this->config->get('config_language_id')]);
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $input = array(
            '{order_id}',
            '{total}',
            '{shipping_total}'
        );

        $output = array(
            'order_id'        => $this->session->data['order_id'],
            'total'           => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']),
            'shipping_total'  => $this->currency->format($this->model_payment_sbrf_online->getShippingTotal($order_info['order_id']), $order_info['currency_code'], $order_info['currency_value'])
        );

        $data['bank'] = $this->session->data['bank'] = html_entity_decode(trim(str_replace($input, $output, $bank)), ENT_QUOTES, 'UTF-8');

        return file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/sbrf_online.tpl') ?
                $this->load->view($this->config->get('config_template') . '/template/payment/sbrf_online.tpl', $data) :
                $this->load->view('default/template/payment/sbrf_online.tpl', $data);
    }

    public function confirm() {
        if ($this->session->data['payment_method']['code'] == 'sbrf_online') {
          $this->load->model('checkout/order');
          
          $comment  = $this->language->get('text_instruction') . "\n\n";
          $comment .= $this->session->data['bank'] . "\n\n";
          $comment .= $this->language->get('text_payment');
          
          $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('sbrf_online_order_status_id'), $comment, true);
          unset($this->session->data['bank']);
        }
    }

    public function sbrf_online_success() {
        if (isset($this->session->data['order_id'])) {
          $this->cart->clear();

          // Add to activity log
          $this->load->model('account/activity');

          if ($this->customer->isLogged()) {
            $activity_data = array(
              'customer_id' => $this->customer->getId(),
              'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
              'order_id'    => $this->session->data['order_id']
            );

            $this->model_account_activity->addActivity('order_account', $activity_data);
          } else {
            $activity_data = array(
              'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
              'order_id' => $this->session->data['order_id']
            );

            $this->model_account_activity->addActivity('order_guest', $activity_data);
          }

          unset($this->session->data['shipping_method']);
          unset($this->session->data['shipping_methods']);
          unset($this->session->data['payment_method']);
          unset($this->session->data['payment_methods']);
          unset($this->session->data['guest']);
          unset($this->session->data['comment']);
          unset($this->session->data['order_id']);
          unset($this->session->data['coupon']);
          unset($this->session->data['reward']);
          unset($this->session->data['voucher']);
          unset($this->session->data['vouchers']);
          unset($this->session->data['totals']);
        }
                         
        $this->document->setTitle($this->language->get('heading_title_success'));

        $data['breadcrumbs'] = array(); 

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/home'),
            'text'      => $this->language->get('text_home')
        ); 
        
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('checkout/cart'),
            'text'      => $this->language->get('text_basket')
        );
            
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
            'text'      => $this->language->get('text_checkout')
        );
              
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('payment/sbrf_online/sbrf_online_success'),
            'text'      => $this->language->get('text_success')
        );

        $text_message = $this->config->get('sbrf_online_page_success');
        
        $data = array_merge($data, $this->_setData(
            array(
                'button_continue',
                'heading_title'   => $this->language->get('heading_title_success'),
                'text_message'    => html_entity_decode(trim($text_message[$this->config->get('config_language_id')]), ENT_QUOTES, 'UTF-8'),
                'continue'        => $this->url->link('common/home'),
                'column_left'     => $this->load->controller('common/column_left'),
                'column_right'    => $this->load->controller('common/column_right'),
                'content_top'     => $this->load->controller('common/content_top'),
                'content_bottom'  => $this->load->controller('common/content_bottom'),
                'footer'          => $this->load->controller('common/footer'),
                'header'          => $this->load->controller('common/header')
            )
        ));
            
        $this->response->setOutput(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/sbrf_online_success.tpl') ?
            $this->load->view($this->config->get('config_template') . '/template/payment/sbrf_online_success.tpl', $data) :
            $this->load->view('default/template/payment/sbrf_online_success.tpl', $data)
        );
    }

    protected function _setData($values) {
        $data = array();
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $data[$value] = $this->language->get($value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
?>