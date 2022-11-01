<?php
class ModelTotalRpcodtotal2 extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) 
	{
		$this->handleError("rpcodtotal2-DEBUG: start");
		$this->handleError("rpcodtotal2-DEBUG: russianpost2_is_cod_included: ".$this->config->get('russianpost2_is_cod_included'));
		
		$this->handleError("rpcodtotal2-DEBUG: russianpost2_cod_script: ".$this->config->get('russianpost2_cod_script'));
		
		if( !empty( $this->session->data['simple'] ) )
			$this->handleError("rpcodtotal2-DEBUG: simple: yes");
		else
			$this->handleError("rpcodtotal2-DEBUG: simple: no");
		
		if( isset($this->session->data['shipping_method']['code']) )
		{
			$this->handleError("rpcodtotal2-DEBUG: shipping_method code: ".$this->session->data['shipping_method']['code']);
		
			$this->handleError("rpcodtotal2-DEBUG: checkIsCodShippingMethod: ".
				$this->checkIsCodShippingMethod($this->session->data['shipping_method']['code'])
			);
		}
		else
			$this->handleError("rpcodtotal2-DEBUG: shipping_method code: no");
		
		if( isset($this->session->data['shipping_method']['code']) )
			$this->handleError("rpcodtotal2-DEBUG: shipping_method code: ".$this->session->data['shipping_method']['code']);
		else
			$this->handleError("rpcodtotal2-DEBUG: shipping_method code: no");
		
		
		
		if( isset($this->session->data['payment_method']['code']) )
		{
			$this->handleError("rpcodtotal2-DEBUG: payment_method code: ".$this->session->data['payment_method']['code']);
		
		}
		else
			$this->handleError("rpcodtotal2-DEBUG: payment_method code: no");
		
		if( 
				$this->config->get('russianpost2_is_cod_included') == 'inmod' &&
				(
					(
						!empty( $this->session->data['simple'] ) && 
						isset($this->session->data['shipping_method']['code']) &&
						strstr($this->session->data['shipping_method']['code'], 'russianpost2') && 
						$this->config->get('russianpost2_cod_script') == 'onlyshipping' &&
						$this->checkIsCodShippingMethod($this->session->data['shipping_method']['code'])
					)
					||
					(
						isset($this->session->data['payment_method']['code']) && 
						(
							$this->session->data['payment_method']['code'] == 'rpcod2ems' ||
							$this->session->data['payment_method']['code'] == 'rpcod2' ||
							(
								isset($this->session->data['shipping_method']['code']) &&
								strstr($this->session->data['shipping_method']['code'], 'russianpost2') && 
								$this->config->get('russianpost2_cod_script') == 'onlyshipping' &&
								$this->checkIsCodShippingMethod($this->session->data['shipping_method']['code'])
							)
						)
					)
					||
					(
						!empty( $this->request->get['route'] ) && 
						$this->request->get['route'] == 'checkout/oct_fastorder/cart' && 
						!empty( $this->request->post['payment_method'] ) && 
						(
							$this->request->post['payment_method'] == 'rpcod2' || 
							$this->request->post['payment_method'] == 'rpcod2ems'
						)
					)
				)
		)
		{
			$this->handleError("rpcodtotal2-DEBUG: M1");
			$ar = $this->config->get('russianpost2_rpcodtotal_title');
			$title = html_entity_decode($ar[ $this->config->get('config_language_id') ]);
			
			/* start 2402 */
			$commission = '';
			
				$this->load->model('shipping/russianpost2');
				$RUB = $this->model_shipping_russianpost2->getRubCode();
				$config_currency = $this->model_shipping_russianpost2->getConfigCurrency();
				$rub_total = $this->currency->convert($total, $config_currency, $RUB);
				
				$commission_rub = $this->model_shipping_russianpost2->getTariffCodPrice(
									$rub_total,
									$this->config->get('russianpost2_from_postcode') 
									/* start 1202-2 */
									,
									isset( $this->session->data['shipping_method']['service_key'] ) ? 
									$this->session->data['shipping_method']['service_key'] : ''
									/* end 1202-2 */
								);
								
			$this->handleError("rpcodtotal2-DEBUG: commission_rub: ".$commission_rub);
								
				$commission = $this->currency->convert($commission_rub, $RUB, $config_currency);
			$commission = $this->model_shipping_russianpost2->getOkrugl($commission);
			
			
			
			if( $this->config->get('russianpost2_is_cod_included') == 'inmod' )
			{
				$this->handleError("rpcodtotal2-DEBUG: commission_rub: M2");
				$title = str_replace("{price}", $this->currency->format($commission), $title);
				
				$total_data[] = array( 
					'code'       => 'rpcodtotal2',
					'title'      => $title,
					'text'       => $this->currency->format($commission),
					'value'      => $commission,
					'sort_order' => $this->config->get('rpcodtotal2_sort_order')
				);
				
				$this->handleError("rpcodtotal2-DEBUG: result: ".print_r(
					$total_data[ count($total_data)-1 ], 1
				) );
				
				$total += $commission;
			}
		}			
	}
	
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

	
	private function checkIsCodShippingMethod($code)
	{
		list($code1, $code2) = explode(".", $code);
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "russianpost2_current_methods`
		WHERE code = '".$this->db->escape($code1)."'");
		
		if( empty($query->row['data']) )
			return false;
		
		$data = unserialize($query->row['data']);
		
		foreach($data['submethods'] as $submethod )
		{
			if( $submethod['code'] == $code )
			{
				if( !empty($submethod['is_show_cod']) )
					return true;
				else
					return false;
			}
		}
		
		return false;
	}
}
?>