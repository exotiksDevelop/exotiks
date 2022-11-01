<?php

class ControllerPaymentCryptocloud extends Controller {

	public function index() 
	{
		$this->language->load('payment/cryptocloud');
		$order_id = $this->session->data['order_id'];
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$merchant_id = $this->config->get('cryptocloud_merchant_id');
		$apikey = $this->config->get('cryptocloud_apikey');
		$amount = number_format($order_info['total'], 2, '.', '');
		$desc = $this->language->get('order_description') . $order_id;

		$data_request = array(
			'shop_id'	=> $merchant_id,
			'amount'	=> $amount,
			'currency'	=> $this->session->data['currency'],
			'order_id'	=> $order_id
		);

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_request);

		$headers = array();
		$headers[] = "Authorization: Token " . $apikey;

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_URL, 'https://cryptocloud.pro/api/v2/invoice/create');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);

		curl_close($curl);

		$json_data = json_decode($result, true);
		$data['url'] = $json_data['pay_url'];
        $data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cryptocloud.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/cryptocloud.tpl', $data);
		} else {
			return $this->load->view('/extension/payment/cryptocloud.tpl', $data);
		}


	}
	
	public function response() 
	{
		$this->load->model('checkout/order');

		if (isset($this->request->get['fail']) AND $this->request->get['fail']) {
			$this->response->redirect($this->url->link('checkout/confirm', '', true));
		} else {
			$this->cart->clear();
			$this->response->redirect($this->url->link('checkout/success', '', true));
		}
	}

	public function callback()
	{
		$order_id = isset($this->request->post['order_id'])
            ? (int) $this->request->post['order_id']
            : 0;
			
		if (!$order_id) {
            exit;
        }

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$amount = number_format($order_info['total'], 2, '.', '');
		$comment = 'CryptoCloud Transaction id: ' . $this->request->post['invoice_id'];
		$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cryptocloud_order_status_id'), $comment, $notify = true, $override = false);

	}
}
