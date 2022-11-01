<?php

class ControllerPaymentCryptocloud extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/cryptocloud');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')  && $this->validate()) {
			$this->model_setting_setting->editSetting('cryptocloud', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
		}


        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_cryptocloud'] = $this->language->get('text_cryptocloud');
        $data['text_success'] = $this->language->get('text_success');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_pay'] = $this->language->get('text_pay');
        $data['text_response_description'] = $this->language->get('text_response_description');
        $data['text_enot_order_status'] = $this->language->get('text_enot_order_status');
        $data['text_response_code'] = $this->language->get('text_response_code');
        $data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
        $data['entry_apikey'] = $this->language->get('entry_apikey');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['error_permission'] = $this->language->get('error_permission');
        $data['error_merchant_id'] = $this->language->get('error_merchant_id');
        $data['error_apikey'] = $this->language->get('error_apikey');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

		$arr = array("warning", "merchant_id", "apikey");
		foreach ($arr as $v) $data['error_' . $v] = (isset($this->error[$v])) ? $this->error[$v] : "";

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'separator' => false
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true),
			'separator' => ' :: '
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/cryptocloud', 'token=' . $this->session->data['token'], true),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('payment/cryptocloud', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$array_data = array(
			'cryptocloud_apikey',
			'cryptocloud_merchant_id',
			'cryptocloud_title',
			'cryptocloud_order_status_id',
			'cryptocloud_status',
			'cryptocloud_sort_order',
		);

		foreach ($array_data as $v) {
			$data[$v] = (isset($this->request->post[$v])) ? $this->request->post[$v] : $this->config->get($v);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/cryptocloud.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/cryptocloud')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['cryptocloud_apikey']) {
			$this->error['apikey'] = $this->language->get('error_apikey');
		}
		if (!$this->request->post['cryptocloud_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}
		return (!$this->error) ? true : false;
	}
}

?>