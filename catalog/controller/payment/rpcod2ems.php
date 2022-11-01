<?php
class ControllerPaymentrpcod2ems extends Controller {
	public function index() {
    	$data['button_confirm'] = $this->language->get('button_confirm');

		$data['continue'] = $this->url->link('checkout/success');
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/rpcod2ems.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/rpcod2ems.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/rpcod2ems.tpl', $data);
		}
	}
	
	public function confirm() 
	{
	
		if ($this->session->data['payment_method']['code'] == 'rpcod2ems') 
		{
			$this->load->model('checkout/order');
			
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('rpcod2ems_order_status'));
		}
		
	}
}
?>