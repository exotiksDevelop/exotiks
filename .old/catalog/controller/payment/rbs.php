<?php
class ControllerPaymentRbs extends Controller {
    /**
     * Инициализация языкового пакета
     * @param $registry
     */
    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('payment/rbs');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/rbs.tpl')) {
            $this->have_template = true;
        }
    }

    /**
     * Рендеринг кнопки-ссылки для перехода в метод payment()
     * @return mixed Шаблон кнопки
     */
    public function index() {
        $data['action'] = $this->url->link('payment/rbs/payment','','SSL');
        $data['button_confirm'] = $this->language->get('button_confirm');
        return $this->get_template('payment/rbs', $data);
    }

    /**
     * Регистрация заказа.
     * Переадресация покупателя при успешной регистрации.
     * Вывод ошибки при неуспешной регистрации.
     */
    public function payment() {
        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $order_number = $this->session->data['order_id'];
        $amount = (int) $order_info['total'] * 100;
        $return_url = $this->url->link('payment/rbs/callback','','SSL');

        $this->initializeRbs();
        $response = $this->rbs->register_order($order_number, $amount, $return_url);
        if (isset($response['errorCode'])) {
            $this->document->setTitle($this->language->get('error_title'));

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['button_continue'] = $this->language->get('error_continue');

            $data['heading_title'] = $this->language->get('error_title') . ' #' . $response['errorCode'];
            $data['text_error'] = $response['errorMessage'];
            $data['continue'] = $this->url->link('checkout/cart','','SSL');

            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->get_template('error/rbs', $data));
        } else {
            $this->response->redirect($response['formUrl']);
        }
    }

    /**
     * Колбек для возвращения покупателя из ПШ в магазин.
     */
    public function callback() {
        if (isset($this->request->get['orderId'])) {
            $order_id = $this->request->get['orderId'];
        } else {
            die('Illegal Access');
        }

        $this->load->model('checkout/order');
        $order_number = $this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($order_number);

        if ($order_info) {
            $this->initializeRbs();

            $response = $this->rbs->get_order_status($order_id);
            if(($response['errorCode'] == 0) && (($response['orderStatus'] == 1) || ($response['orderStatus'] == 2))) {
                $this->model_checkout_order->addOrderHistory($order_number, $this->config->get('config_order_status_id'));
                $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
            } else {
                $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
            }
        }
    }

    /**
     * Инициализация библиотеки RBS
     */
    private function initializeRbs() {
        $this->library('rbs');
        $this->rbs = new RBS();
        $this->rbs->login = $this->config->get('rbs_merchantLogin');
        $this->rbs->password = $this->config->get('rbs_merchantPassword');
        $this->rbs->stage = $this->config->get('rbs_stage');
        $this->rbs->mode = $this->config->get('rbs_mode');
        $this->rbs->logging = $this->config->get('rbs_logging');
        $this->rbs->currency = $this->config->get('rbs_currency');
    }

    /**
     * В версии 2.1 нет метода Loader::library()
     * Своя реализация
     * @param $library
     */
    private function library($library) {
        $file = DIR_SYSTEM . 'library/' . str_replace('../', '', (string)$library) . '.php';

        if (file_exists($file)) {
            include_once($file);
        } else {
            trigger_error('Error: Could not load library ' . $file . '!');
            exit();
        }
    }

    /**
     * Отрисовка шаблона
     * @param $template     Шаблон вместе с корневой папкой
     * @param $data         Данные
     * @return mixed        Отрисованный шаблон
     */
    private function get_template($template, $data) {
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $template . '.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/' . $template . '.tpl', $data);
        } else {
            return $this->load->view('default/template/' . $template . '.tpl', $data);
        }
    }
}