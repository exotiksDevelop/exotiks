<?php  
class order_status extends cdek_integrator implements exchange {
	
	protected $method = 'status_report_h.php';
	private $xml;
	
	public function setData($data) {
		$this->xml = $this->createXML($data);
		/*print_r($this->xml);*/
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
				
				if (isset($attributes->ErrorCode) ) {
					
					if (isset($attributes->Number)) {
						$error[(int)$attributes->Number][(string)$attributes->ErrorCode] = mb_convert_encoding((string)($attributes->Msg), 'UTF-8', 'auto');
					} elseif (isset($attributes->DispatchNumber)) {
						$error[(string)$attributes->DispatchNumber][(string)$attributes->ErrorCode] = mb_convert_encoding((string)($attributes->Msg), 'UTF-8', 'auto');
					} else {
						$error[][(string)$attributes->ErrorCode] = mb_convert_encoding((string)($attributes->Msg), 'UTF-8', 'auto');
					}
					
				} elseif (isset($data->StatusReport)) {
			
					$attributes = $data->StatusReport->attributes();
					
					if (isset($attributes->ErrorCode)) {
						
						$error[][(string)$attributes->ErrorCode] = mb_convert_encoding((string)($attributes->Msg), 'UTF-8', 'auto');
						
					}
					
				} elseif (is_scalar($data)) {
					$error[]['error_response'] = 'Ошибка сервера СДЭК: неверный формат ответа!';
				}
				
			}
			
		}
		
		return $data;
	}

	private function createXML($data = array()) {
		
		$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
		
		if (!empty($data)) {
			
			$xml .= '<StatusReport Date="' . $this->date . '" Account="' . $this->account . '" Secure="' . $this->getSecure() . '" ShowHistory="' . (int)isset($data['show_history']) . '">';
			
			if (!empty($data['change_period']['date_beg']) && !empty($data['change_period']['date_end'])) {
				$xml .= '<ChangePeriod DateBeg="' . $data['change_period']['date_beg'] . '" DateEnd="' . $data['change_period']['date_end'] . '" />';
			}
			
			if (!empty($data['order'])) {
				
				foreach ($data['order'] as $order_info) {
					
					$attribute = array();
					
					if (isset($order_info['dispatch_number'])) {
						$attribute[] =	'DispatchNumber="' . $order_info['dispatch_number'] . '"';
					} else {
						$attribute[] =	'Number="' . $order_info['order_id'] . '"';
						$attribute[] =	'Date="' . $this->date . '"';
					}
					
					$xml .= '<Order ' . implode(' ', $attribute) . ' />';
				}
				
			}
			
			$xml .= '</StatusReport>';
			
			
		
		}
		
		/*echo $xml;*/
		
		return $xml;
	}
	
}

?>