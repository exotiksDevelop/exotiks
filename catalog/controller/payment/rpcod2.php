<?php
class ControllerPaymentrpcod2 extends Controller {
	public function index() {
    	$data['button_confirm'] = $this->language->get('button_confirm');

		$data['continue'] = $this->url->link('checkout/success');
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/rpcod2.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/rpcod2.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/rpcod2.tpl', $data);
		}
	}
	
	public function confirm() 
	{
	
		if ($this->session->data['payment_method']['code'] == 'rpcod2') 
		{
			$this->load->model('checkout/order');
			
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('rpcod2_order_status'));
		}
		
	}
	
	public function setTerminal()
	{
		$code = $this->request->post['code'];
		$title = $this->request->post['title'];
		$cost = $this->request->post['cost'];
		$text = $this->request->post['text'];
		$type = isset($this->request->post['type']) ? $this->request->post['type'] : 'pvz';
		
		if( empty($code) ) exit("ERROR-1");
		
		$code_ar = explode("_", $code);
		$title =  strip_tags( html_entity_decode($title, ENT_QUOTES, 'UTF-8') );

		if( $type == 'pvz' )
		{
			$this->session->data['rp_delivery_point_index'] = $this->request->post['delivery_point_index'];
			$this->session->data['rp_delivery_point_index_all'] = $this->request->post['delivery_point_index'];
		}
		elseif( $type == 'pvz_partners' )
		{
			$this->session->data['rp_delivery_point_index'] = $this->request->post['delivery_point_index'];
			$this->session->data['rp_delivery_point_index_partners'] = $this->request->post['delivery_point_index'];
		}
		elseif( $type == 'pvz_rupost' )
		{
			$this->session->data['rp_delivery_point_index'] = $this->request->post['delivery_point_index'];
			$this->session->data['rp_delivery_point_index_rupost'] = $this->request->post['delivery_point_index'];
		}
		else	
		{
			if( !isset($this->session->data['rp_ops_index']) || !is_array($this->session->data['rp_ops_index']) )
				$this->session->data['rp_ops_index'] = array();
			$this->session->data['rp_ops_index'][$this->request->post['service_key']] = $this->request->post['delivery_point_index'];
			setcookie('rp_ops_index___'.$this->request->post['service_key'], $this->request->post['delivery_point_index'], time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
			/*
			if( !empty($this->session->data['shipping_address']['postcode']) )
				$this->session->data['shipping_address']['postcode'] = $this->request->post['delivery_point_index'];
			
			if( !empty($this->session->data['payment_address']['postcode']) )
				$this->session->data['payment_address']['postcode'] = $this->request->post['delivery_point_index'];
			
			if( !empty($this->session->data['simple']) )
			{
				if( !empty($this->session->data['simple']['shipping_address']['postcode']) )
					$this->session->data['simple']['shipping_address']['postcode'] = $this->request->post['delivery_point_index'];
			
				if( !empty($this->session->data['simple']['payment_address']['postcode']) )
					$this->session->data['simple']['payment_address']['postcode'] = $this->request->post['delivery_point_index'];
			}
			*/
		}
		
		
		$code = $code_ar[0].'.'.$code_ar[1];
		 
		$this->session->data['shipping_method']['code'] = $code;
		$this->session->data['shipping_method']['title'] = $title;
		$this->session->data['shipping_method']['cost'] = $cost;
		$this->session->data['shipping_method']['text'] = $text;
		$this->session->data['shipping_method']['tax_class_id'] = false;
		
		$this->session->data['shipping_methods'][$code_ar[0]]['quote'][ $code_ar[1] ]['title'] = $title;
		$this->session->data['shipping_methods'][$code_ar[0]]['quote'][ $code_ar[1] ]['cost'] = $cost;
		$this->session->data['shipping_methods'][$code_ar[0]]['quote'][ $code_ar[1] ]['text'] = $text;
		$this->session->data['shipping_methods'][$code_ar[0]]['quote'][ $code_ar[1] ]['tax_class_id'] = false; 
		
		$json = array(
			"status" => 'OK',
			"address_1" => '',
			"address_2" => '',
			"comment" => '',
		);
		
		$is_simple = !empty($this->session->data['simple']) ? 1 : 0;
		$this->request->post['address'] = html_entity_decode($this->request->post['address'], ENT_QUOTES, 'UTF-8');
		$this->request->post['brand_name'] = html_entity_decode($this->request->post['brand_name'], ENT_QUOTES, 'UTF-8');
		
		$this->request->post['address'] .= ', '.html_entity_decode($this->request->post['brand_name'], ENT_QUOTES, 'UTF-8');
		
		exit( json_encode($json) );
	}
}
?>