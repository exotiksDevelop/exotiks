<?php
class ModelShippingRussianpost2f1 extends Model {
	
	private $method_number = 1;
	
	function getQuote($address)
	{
		$address['method_number'] = $this->method_number;
		
		$this->load->model('shipping/russianpost2');
		
		return $this->model_shipping_russianpost2->getQuote($address);
	}
}
?>