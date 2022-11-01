<?php  
class order_print extends cdek_integrator implements exchange {
	
	private $xml;
	
	protected $method = 'orders_print.php';
	
	public function setData($data) {
		$this->xml = $this->createXML($data);
	}
	
	public function getData(){
		return array(
			'xml_request' => $this->xml
		);
	}
	
	public function prepareResponse($data, &$error) {
		
		if (strpos($data, '<?xml') === 0) {
			
			$response = new SimpleXMLElement($data);
			
			if (isset($response->Order)) {
			
				foreach ($response->Order as $order) {
					
					$attributes = $order->attributes();
					
					if (isset($attributes->ErrorCode) ) {
						$error[(string)$attributes->ErrorCode] = mb_convert_encoding((string)($attributes->Msg), 'UTF-8', 'auto');
					}
					
				}
				
			}
			
		}
		
		return $data;
	}
	
	public function getParser() {
		return new parser_original();
	}
	
	private function createXML($data = array()) {
		
		$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
		
		if (!empty($data)) {
			
			if (!empty($data['order'])) {
				
				$copy_count = isset($data['copy_count']) ? (int)$data['copy_count'] : 1;
				
				$xml .= '<OrdersPrint Date="' . $this->date . '" Account="' . $this->account . '" Secure="' . $this->getSecure() . '" OrderCount="' . count($data['order']) . '" CopyCount="' . $copy_count . '">';
				
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
				
				$xml .= '</OrdersPrint>';
				
			} else {
				throw new Exception('Component "order_print" invalid argument.');
			}
		
		}
		
		return $xml;
	}
	
}

?>