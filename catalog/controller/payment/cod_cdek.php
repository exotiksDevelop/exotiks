<?php
class ControllerPaymentCodCdek extends Controller {
	
	public function index() {
		
    	$data['button_confirm'] = $this->language->get('button_confirm');

		$data['continue'] = $this->url->link('checkout/success');

		if (version_compare(VERSION, '2.2') >= 0) 
		{
			$tpl = $this->load->view('payment/cod_cdek.tpl', $data);
		} 
		else 
		{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod_cdek.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/payment/cod_cdek.tpl', $data);
			} else {
				return $this->load->view('default/template/payment/cod_cdek.tpl', $data);
			}
		}
	}
	
	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'cod_cdek') {
			$this->load->model('checkout/order');
			
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('cod_cdek_order_status_id'));
		}
	}
}
?>