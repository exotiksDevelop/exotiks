<?php  
class ControllerModuleShippingMileage extends Controller {
	public function mileage_cost() {
		$this->load->language('shipping/mileage');
		$weight = floatval($this->request->get['weight']);
		$distance = floatval($this->request->get['mileage_distance']);
		$is_city = intval($this->request->get['mileage_city']) > 0;
		if ($distance > 0) {
			$this->load->model('shipping/mileage');
			$show_weight = false;
			$cost = $this->model_shipping_mileage->getCost($weight, $distance, $is_city, $show_weight);

			if ($cost == 'toofar') {
				$ret = array(
						'code'         => 'mileage.' . ($is_city ? 'city' : 'oblast'),
						'title'        => sprintf($this->language->get('mileage_toofar'), (int)$this->config->get('mileage_max_distance')),
						'cost'         => 0,
						'distance'	   => 'error',
						'tax_class_id' => $this->config->get('mileage_tax_class_id'),
						'text'         => '');
			}
			else {
				$text = $this->currency->format($this->tax->calculate($cost, $this->config->get('mileage_tax_class_id'), $this->config->get('config_tax')),$this->session->data['currency']);
				
				$ret = array(
						'code'         => 'mileage.' . ($is_city ? 'city' : 'oblast'),
						'title'        => $this->model_shipping_mileage->getTitle($is_city, $weight, $distance, $show_weight),
						'cost'         => $cost,
						'distance'	   => $distance,
						'tax_class_id' => $this->config->get('mileage_tax_class_id'),
						'text'         => $text);
			}
			if (isset($this->session->data['shipping_methods']['mileage']) && isset($this->session->data['shipping_methods']['mileage']['quote']) && isset($this->session->data['shipping_methods']['mileage']['quote'][($is_city ? 'city' : 'oblast')])) {
				$this->session->data['shipping_methods']['mileage']['quote'][($is_city ? 'city' : 'oblast')] = $ret;
			}
			
			$this->response->setOutput(json_encode($ret));
		}
	}

	public function mileage_fault() {
		$this->load->language('shipping/mileage');
		$this->load->model('shipping/mileage');
		
		$ret = array(
				'code'         => 'mileage.oblast',
				'title'        => $this->model_shipping_mileage->getErrTitle(),
				'cost'         => 0,
				'distance'	   => 'error',
				'tax_class_id' => $this->config->get('mileage_tax_class_id'),
				'text'         => '');
						
		if (isset($this->session->data['shipping_methods']['mileage']) && isset($this->session->data['shipping_methods']['mileage']['quote']) && isset($this->session->data['shipping_methods']['mileage']['quote']['oblast'])) {
			$this->session->data['shipping_methods']['mileage']['quote']['oblast'] = $ret;
		}
		$this->response->setOutput(json_encode($ret));
	}
}
?>
