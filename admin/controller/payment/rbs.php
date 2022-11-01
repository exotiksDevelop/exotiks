<?php
class ControllerPaymentRbs extends Controller {
    private $error = array();

    /**
     * Вывод и сохранение настроек
     */
    public function index() {
        $this->load->language('payment/rbs');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        // Сохранение настроек через POST запрос
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('rbs', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('payment/rbs', 'token=' . $this->session->data['token'], 'SSL'));
        }

        // Заголовок страницы
        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        // Хлебные крошки
        $data['breadcrumbs'] = array();
        array_push($data['breadcrumbs'],
            array(  // Главная
                'text'  => $this->language->get('text_home'),
                'href'  => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
            ),
            array(  // Оплата
                'text'  => $this->language->get('text_payment'),
                'href'  => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
            ),
            array(  // Оплата через {{банк}}
                'text'  => $this->language->get('heading_title'),
                'href'  => $this->url->link('payment/rbs', 'token=' . $this->session->data['token'], 'SSL')
            )
        );

        // Кнопки сохранения и отмены
        $data['action'] = $this->url->link('payment/rbs', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
        
        // Заголовок панели
        $data['text_settings'] = $this->language->get('text_settings');

        // Статус модуля: Включен/Выключен
        $data['entry_status'] = $this->language->get('status');
        $data['status_enabled'] = $this->language->get('status_enabled');
        $data['status_disabled'] = $this->language->get('status_disabled');
        $data['rbs_status'] = $data['rbs_status'] = $this->config->get('rbs_status');
        
        // Логин мерчанта
        $data['entry_merchantLogin'] = $this->language->get('merchantLogin');
        $data['rbs_merchantLogin'] = $this->config->get('rbs_merchantLogin');

        // Логин мерчанта
        $data['entry_merchantPassword'] = $this->language->get('merchantPassword');
        $data['rbs_merchantPassword'] = $this->config->get('rbs_merchantPassword');

        // Режим работы модуля: Тестовый/Боевой
        $data['entry_mode'] = $this->language->get('mode');
        $data['mode_test'] = $this->language->get('mode_test');
        $data['mode_prod'] = $this->language->get('mode_prod');
        $data['rbs_mode'] = $this->config->get('rbs_mode');

        // Стадийность платежа
        $data['entry_stage'] = $this->language->get('stage');
        $data['stage_one'] = $this->language->get('stage_one');
        $data['stage_two'] = $this->language->get('stage_two');
        $data['rbs_stage'] = $this->config->get('rbs_stage');

        // Логирование
        $data['entry_logging'] = $this->language->get('logging');
        $data['logging_enabled'] = $this->language->get('logging_enabled');
        $data['logging_disabled'] = $this->language->get('logging_disabled');
        $data['rbs_logging'] = $this->config->get('rbs_logging');

        // Валюта
        $data['entry_currency'] = $this->language->get('currency');
        $data['currency_list'] = array_merge(
            array(
                array(
                    'numeric'       => 0,
                    'alphabetic'    => 'По умолчанию'
                )
            ), // Валюта по умолчанию в платежном шлюзе
            $this->getCurrencies()  // Список валют
        );
        $data['rbs_currency'] = $this->config->get('rbs_currency');

        // Хедер, футер, левое меню для отрисовки страницы настроек модуля
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        // Рендеринг шаблона
        $this->response->setOutput($this->load->view('payment/rbs.tpl', $data));
    }

    /**
     * Валидация данных.
     * В данном случае проверка прав на редактирование настроек платежного модуля.
     * @return bool
     */
    private function validate() {
        if (!$this->user->hasPermission('modify', 'payment/rbs')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    /**
     * Список валют в ISO 4217
     * @return array
     */
    private function getCurrencies() {
        return array(
            array(
                'numeric'       => 643,
                'alphabetic'    => 'RUR'
            ),
            array(
                'numeric'       => 810,
                'alphabetic'    => 'RUB'
            ),
            array(
                'numeric'       => 840,
                'alphabetic'    => 'USD'
            ),
            array(
                'numeric'       => 978,
                'alphabetic'    => 'EUR'
            ),
        );
    }
}