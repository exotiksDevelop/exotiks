<?php 
class ModelPaymentrpcod2ecom extends Model {
	
	private $RUB = 'RUB';
	private $total_without_shipping;
	private $total_without_shipping_rub;
	private $total;
	private $ORDER;
		
  	public function getMethod($address, $total) 
	{
		$this->handleError("rpcod2ecom-DEBUG: start");
		$this->load->model('shipping/russianpost2');
		
		if( !$this->config->get('rpcod2ecom_status') )
		{
			$this->handleError("rpcod2ecom-DEBUG: modul oplati otkluchon");
			return false;
		}
		
			
		if( $this->config->get('russianpost2_cod_script') == 'onlyshipping' )
		{
			$this->handleError("rpcod2ecom-DEBUG: onlyshipping");
			
			return false;
		}
		
		
		if( empty($this->session->data['shipping_method']['code'])
			&& 
			(
				empty($this->request->get['route']) 
				||
				(
					$this->request->get['route'] != 'api/payment/methods'
					&&
					$this->request->get['route'] != 'checkout/recalculate/getMethods'
					&&
					$this->request->get['route'] != 'extension/module/lightshop/shippay_method'
					&&
					$this->request->get['route'] != 'module/lightshop/shippay_method'
				)
			)  
		)
		{
			$this->handleError("rpcod2ecom-DEBUG: shipping method ne vybran");
			return false;
		}
		
		
		if( $this->config->get('rpcod2ecom_geo_zone_id') )
		{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('rpcod2ecom_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if( !$query->num_rows ) {
				$this->handleError("rpcod2ecom-DEBUG: filter po geo-zone");
				return false;
			}
		}
		
		$address['is_cod'] = 1;
		/* start 2402 */
		if( !empty($this->session->data['shipping_method']['code'])
			&&
			empty( $this->session->data['simple'] ) )
		{
			list($address['code1'], $address['code2']) = explode('.', $this->session->data['shipping_method']['code']);
		}
		else
		{
			$address['code1'] = 'any';
			$address['code2'] = 'ecom';
		}
		/* end 2402 */
		
		if( !empty($this->session->data['rp_delivery_point_index']) &&
			!$this->model_shipping_russianpost2->checkIsCompulsoryPvz(
				$this->session->data['rp_delivery_point_index']
			)
		)
		{
			$this->handleError("rpcod2ecom-DEBUG: no payment PVZ");
			return;
		}
		
		list(
			$cost_rub, 
			$shippping_cost_rub
		) = $this->model_shipping_russianpost2->getQuote($address);
		
		if( !$cost_rub ) {
			$this->handleError("rpcod2ecom-DEBUG: cost_rub");
			return;
		}
		
		$rpcod2_order_filters = $this->config->get('rpcod2ecom_order_filters');
		
		$filters = array();
		
		if( $rpcod2_order_filters )
		{
			foreach( $rpcod2_order_filters as $filter_id=>$val )
			{
				if( $this->model_shipping_russianpost2->checkFilterFromPayment(
						$filter_id, $address
					) 
				)
				{
					$filters[] = $filter_id;
				}
			}
			
			if( !empty($filters) )
			{
				$this->handleError("rpcod2-DEBUG: filters");
				return false;
			}
		} 
		
		$this->RUB = $this->model_shipping_russianpost2->getRubCode();
		$config_currency = $this->model_shipping_russianpost2->getConfigCurrency();
		
		$user_currency = $this->session->data['currency'];
		
		
		
		$cost = $this->currency->convert($cost_rub, $this->RUB, $config_currency);
		 
		$cost = $this->model_shipping_russianpost2->getOkrugl($cost); 
		
		$shippping_cost = $this->currency->convert($shippping_cost_rub, $this->RUB, $config_currency);
		$full_cost = $this->currency->convert($shippping_cost_rub+$cost_rub, $this->RUB, $config_currency);
		
		$full_cost = $this->model_shipping_russianpost2->getOkrugl($full_cost);
		$shippping_cost = $this->model_shipping_russianpost2->getOkrugl($shippping_cost);
		
		$title = $this->format_data('russianpost2_rpcod_ecom_title', 
						array(
								"price" => $this->currency->format($cost, $user_currency),
								"full_price" => $this->currency->format($full_cost, $user_currency),
								"shipping_price" => $this->currency->format($shippping_cost, $user_currency)
								
						)
		);	
		
      	$method_data = array( 
        		'code'       => 'rpcod2ecom',
        		'cost'       => $cost,
        		'title'      => html_entity_decode($title, ENT_QUOTES, 'UTF-8'),
				'terms'      => '',
				'sort_order' => $this->config->get('rpcod2ecom_sort_order'),
				'tax_class_id' => false
      	);
    	
   
    	return $method_data;
  	}
	
	
	/* start 2012 */
	private function handleError($message)
	{
		if( $this->config->get('russianpost2_debug') == 'log' )
		{
			$this->log->write($message);
		}
		elseif( $this->config->get('russianpost2_debug') )
		{
			echo $message."<hr>";
		}
		else
		{
			//none
		}
	}
	/* end 2012 */
	
	private function format_data($key, $array)
	{
		$ar = $this->config->get($key);
		$text = $ar[ $this->config->get('config_language_id') ];
		
		if( !empty($array) )
		{
			foreach($array as $key=>$val)
			{
				$text = str_replace("{".$key."}", $val, $text);
			}
		}
		
		return $text;
	}
}
?>