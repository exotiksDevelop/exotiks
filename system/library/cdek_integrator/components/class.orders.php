<?php  
class orders extends cdek_integrator implements exchange {
	
	protected $method = 'new_orders.php';
	
	private $correct_direction = '+';
	private $correct_time = '00:00';
	
	private $number;
	private $xml;
	
	public function setNumber($number) {
		$this->number = $number;
	}
	
	public function setOrders($data) {
		$this->xml = $this->createXML($data);
	}
	
	public function setCorrrectTime($time) {
		$this->correct_time = $time;
	}
	
	public function setCorrrectdirection($direction) {
		$this->correct_direction = $direction;
	}
	
	public function getData(){
		return array(
			'xml_request' => $this->xml
		);
	}
	
	public function prepareResponse($data, &$error) {
		
		if (isset($data->Order)) {

			foreach ($data->Order as $order) {
				
				$attributes = $order->attributes();
				
				if (isset($attributes->Number)) {
					$error[(int)$attributes->Number][(string)$attributes->ErrorCode] = mb_convert_encoding((string)($attributes->Msg), 'UTF-8', 'auto');
				}
				
			}
			
		} elseif (isset($data->DeliveryRequest)) {
			
			$attributes = $data->DeliveryRequest->attributes();
			
			if (isset($attributes->ErrorCode)) {
				
				$error[][(string)$attributes->ErrorCode] = mb_convert_encoding((string)($attributes->Msg), 'UTF-8', 'auto');
				
			}
			
		} elseif (is_scalar($data)) {
			$error[]['error_response'] = 'Ошибка сервера СДЭК: неверный формат ответа!';
		}
		
		return $data;
	}
	
	private function createXML($data = array()) {
		
		$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
		
		if (!empty($data)) {
			
			$cdek_info = $this->loadComponent('info');
			
			$xml .= '<DeliveryRequest Number="' . $this->number . '" Date="' . $this->date . '" Account="' . $this->account . '" Secure="' . $this->getSecure() . '" OrderCount="' . count($data) . '" DeveloperKey="c6e7304995e8e1f513f1ec380ff89779">';
			
			foreach ($data as $order_info) {
				
				$attribute = array();
				$attribute[] = 'Number="' . $order_info['order_id'] . '"';
				$attribute[] = 'SendCityCode="' . $order_info['city_id'] . '"';
				$attribute[] = 'RecCityCode="' . $order_info['recipient_city_id'] . '"';
				$attribute[] = 'RecipientName="' . $order_info['recipient_name'] . '"';
				
				if ($order_info['recipient_email'] != '') {
					$attribute[] =	'RecipientEmail="' . $order_info['recipient_email'] . '"';
				}
				
				if ($order_info['seller_name'] != '') {
					$attribute[] =	'SellerName="' . $order_info['seller_name'] . '"';
				}
				
				if ($order_info['delivery_recipient_cost'] != '') {
					$attribute[] =	'DeliveryRecipientCost="' . $order_info['delivery_recipient_cost'] . '"';
				}
				
				$attribute[] =	'Phone="' . preg_replace('/[^\d]+/isu', '', $order_info['recipient_telephone']) . '"';
				$attribute[] =	'TariffTypeCode="' . $order_info['tariff_id'] . '"';

				if ($order_info['delivery_recipient_vat_rate'] != '') {
					$attribute[] =	'DeliveryRecipientVATRate="' . $order_info['delivery_recipient_vat_rate'] . '"';
				}

				if ($order_info['delivery_recipient_vat_sum'] != '') {
					$attribute[] =	'DeliveryRecipientVATSum="' . $order_info['delivery_recipient_vat_sum'] . '"';
				}
				
				if ($order_info['cdek_comment'] != '') {
					$attribute[] =	'Comment="' . $order_info['cdek_comment'] . '"';
				}
				
				if (!empty($order_info['currency'])) {
					$attribute[] =	'ItemsCurrency="' . $order_info['currency'] . '"';
				}
				
				if (!empty($order_info['cod'])) {
					$attribute[] =	'RecipientCurrency="' . $order_info['currency_cod'] . '"';
				}
				
				$xml .= '<Order ' . implode(' ', $attribute) . ' >';
				
				$tariff_info = $cdek_info->getTariffInfo($order_info['tariff_id']);
				
				$attribute = array();
				
				$attribute[] = 'Street="' . $order_info['address']['street'] . '"';
				$attribute[] = 'House="' . $order_info['address']['house'] . '"';
				$attribute[] = 'Flat="' . $order_info['address']['flat'] . '"';
				
				if (in_array($tariff_info['mode_id'], array(2, 4))) {
					$attribute[] =	'PvzCode="' . $order_info['address']['pvz_code'] . '"';
				}
				
				$xml .= '<Address ' . implode(' ', $attribute) . ' />';
				
				foreach ($order_info['package'] as $package_id => $package_info) {
					
					$attribute = array();
					$attribute[] = 'Number="' . $package_id . '"';
					$attribute[] = 'BarCode="' . ($package_info['brcode'] != '' ? $package_info['brcode'] : $package_id) . '"';
					
					if ($package_info['pack']) {
						
						$attribute[] = 'SizeA="' . $package_info['size_a'] . '"';
						$attribute[] = 'SizeB="' . $package_info['size_b'] . '"';
						$attribute[] = 'SizeC="' . $package_info['size_c'] . '"';
						
					}
					
					$attribute[] = 'Weight="' . $package_info['weight'] . '"';
					
					$xml .= '<Package  ' . implode(' ', $attribute) . '>';
					
					foreach ($package_info['item'] as $item) {
						
						$item['comment'] = trim(strip_tags(html_entity_decode($item['comment'], ENT_QUOTES, 'UTF-8')));
						$xml .= '<Item 
						WareKey="' . $item['ware_key'] . '" 
						Cost="' . $this->normalizePrice($item['cost']) . '" 
						Payment="' . $this->normalizePrice($item['payment']) . '" 
						PaymentVATRate="' . $item['payment_vat_rate'] . '" 
						PaymentVATSum="' . $this->normalizePrice($item['payment_vat_sum']) . '" 
						Weight="' . (float)$item['weight'] . '" 
						Amount="' . (int)$item['amount'] . '" 
						Comment="' . htmlspecialchars($item['comment']) . '" />';
					}
					
					$xml .= '</Package>';
					
				}
				
				if (!empty($order_info['add_service'])) {
					
					foreach ($order_info['add_service'] as $code => $info) {
						$xml .= '<AddService ServiceCode="' . (int)$code . '" />';
					}
					
				}
				
				if (!empty($order_info['schedule'])) {
					
					$xml .= '<Schedule>';
					
					foreach ($order_info['schedule'] as $attempt_id => $attempt_info) {
						
						$attribute = array();
						$attribute[] = 'ID="' . ($order_info['order_id'] . $attempt_id) . '"';
						$attribute[] = 'Date="' . $attempt_info['date'] . '"';
						$attribute[] = 'TimeBeg="' . $attempt_info['time_beg'] . '"';
						$attribute[] = 'TimeEnd="' . $attempt_info['time_end'] . '"';
						$attribute[] = 'Comment="' . htmlspecialchars($attempt_info['comment'], ENT_QUOTES, 'UTF-8') . '"';
						
						if ($attempt_info['recipient_name'] != '') {
							$attribute[] = 'RecipientName="' . $attempt_info['recipient_name'] . '"';
						}
						
						if ($attempt_info['phone'] != '') {
							$attribute[] = 'Phone="' . $attempt_info['phone'] . '"';
						}
						
						$xml .= '<Attempt ' . implode(' ', $attribute) . '>';
						
						if ($attempt_info['new_address']) {
							
							$attribute = array();
							
							$attribute[] = 'Street="' . $attempt_info['street'] . '"';
							$attribute[] = 'House="' . $attempt_info['house'] . '"';
							$attribute[] = 'Flat="' . $attempt_info['flat'] . '"';
							
							if (in_array($tariff_info['mode_id'], array(2, 4))) {
								$attribute[] =	'PvzCode="' . $attempt_info['pvz_code'] . '"';
							}
							
							$xml .= '<Address ' . implode(' ', $attribute) . '/>';
						}
						
						$xml .= '</Attempt>';
					}
					
					$xml .= '</Schedule>';
					
				}
				
				if ($order_info['courier']['call']) {
					
					$xml .= '<CallCourier>';
					
					$attribute = array();
					$attribute[] = 'Date="' . $order_info['courier']['date'] . '"';
					$attribute[] = 'TimeBeg="' . $order_info['courier']['time_beg'] . '"';
					$attribute[] = 'TimeEnd="' . $order_info['courier']['time_end'] . '"';
					$attribute[] = 'SendCityCode="' . $order_info['courier']['city_id'] . '"';
					
					if ($order_info['courier']['lunch_beg'] != '' && $order_info['courier']['lunch_end'] != '') {
						$attribute[] = 'LunchBeg="' . $order_info['courier']['lunch_beg'] . '"';
						$attribute[] = 'LunchEnd="' . $order_info['courier']['lunch_end'] . '"';
					}
					
					$xml .= '<Call ' . implode(' ', $attribute) . '>';
					
					$attribute = array();
					$attribute[] = 'Street="' . $order_info['courier']['street'] . '"';
					$attribute[] = 'House="' . $order_info['courier']['house'] . '"';
					$attribute[] = 'Flat="' . $order_info['courier']['flat'] . '"';
					$attribute[] = 'SendPhone="' . $order_info['courier']['send_phone'] . '"';
					$attribute[] = 'SenderName="' . $order_info['courier']['sender_name'] . '"';
					
					if (trim($order_info['courier']['comment']) != '') {
						$attribute[] = 'Comment="' . trim($order_info['courier']['comment']) . '"';
					}
					
					$xml .= '<SendAddress  ' . implode(' ', $attribute) . '/>';
					
					
					$xml .= '</Call>';
					$xml .= '</CallCourier>';
					
				}
				
				$xml .= '</Order>';
			}
			
			$xml .= '</DeliveryRequest>';
			
		}
		
		/*echo $xml;*/
		/*exit;*/
		
		return $xml;
	}
	
	private function normalizePrice($price) {
		return (float)round(str_replace(',', '.', $price), 4);
	}
}

?>