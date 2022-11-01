<?php  
class order_delete extends cdek_integrator implements exchange {
	
	protected $method = 'delete_orders.php';
	
	private $xml;
	private $number;
	
	public function setData($data) {
		$this->xml = $this->createXML($data);
	}
	
	public function setNumber($number) {
		$this->number = $number;
	}
	
	public function getData(){
		return array(
			'xml_request' => $this->xml
		);
	}
	
	public function prepareResponse($data, &$error) {
		
		if (isset($data->DeleteRequest)) {

			foreach ($data->DeleteRequest as $order) {
				
				$attributes = $order->attributes();
				
				if (isset($attributes->ErrorCode) ) {
					
					if (isset($attributes->Number)) {
						$error[(int)$attributes->Number][(string)$attributes->ErrorCode] = mb_convert_encoding((string)$attributes->Msg, 'UTF-8', 'auto');
					} elseif (isset($attributes->DispatchNumber)) {
						$error[(string)$attributes->DispatchNumber][(string)$attributes->ErrorCode] = mb_convert_encoding((string)$attributes->Msg, 'UTF-8', 'auto');
					} else {
						$error[][(string)$attributes->ErrorCode] = mb_convert_encoding((string)($attributes->Msg), 'UTF-8', 'auto');
					}
					
				}
				
			}
			
		} elseif (is_scalar($data)) {
			$error[]['error_response'] = 'Ошибка сервера СДЭК: неверный формат ответа!';
		}
		
		return $data;
	}
	
	private function createXML($orders = array()) {
		
		$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
		
		if (!empty($orders)) {
			
			$xml .= '<DeleteRequest Number="' . $this->number . '" Date="' . $this->date . '" Account="' . $this->account . '" Secure="' . $this->getSecure() . '" OrderCount="' . count($orders) . '">';
			
			foreach ($orders as $order_id) {
				$xml .= '<Order Number="' . $order_id . '" />';
			}
			
			$xml .= '</DeleteRequest>';
			
		}
		
		return $xml;
	}
	
}

?>