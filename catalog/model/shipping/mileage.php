<?php 
class ModelShippingMileage extends Model {    
  	public function getQuote($address) {
		$this->load->language('shipping/mileage');
		
		$quote_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('mileage_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!$this->config->get('mileage_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		if (!$status) return array();
		
		$error = false;
		$cost = '';
		$cities = explode(',', $this->config->get('mileage_city'));
		$ctys = array();
		foreach ($cities as $city) {
			$ctys[] = mb_strtolower(trim($city), 'UTF-8');
			
		}
		if (in_array(mb_strtolower($address['city'], 'UTF-8'), $ctys)) {
			$is_city = true;
		}
		else {
			$is_city = false;
		}
		
		
		if (!$address['city'] || !$address['address_1']) {
			$road = 0;
			$shipping_title = $this->language->get('mileage_noaddress');
			$error = true;
		}
		else {
			$sess_data = $this->getCurrent($is_city);
			$road = (is_array($sess_data) ? $sess_data['distance'] : 0);
			
			if ($road === 'error') {
				$road = 0;
				$shipping_title = $this->getErrTitle();
				$this->session->data['shipping_methods']['mileage']['quote'][($is_city ? 'city' : 'oblast')]['distance'] = 0;
				$error = true;
			}
			else {
				$show_weight = false;
				$weight = $this->cart->getWeight();
				$cost = $this->getCost($weight, $road, $is_city, $show_weight);
				$shipping_title = $this->getTitle($is_city, $weight, $road, $show_weight);
			}
		}
		
		if ((string)$cost == 'toofar') {
			$quote_data[($is_city ? 'city' : 'oblast')] = array(
				'code'         => 'mileage.' . ($is_city ? 'city' : 'oblast'),
				'title'        => sprintf($this->language->get('moleage_toofar'), (int)$this->config->get('mileage_max_distance')),
				'cost'         => '',
				'distance'	   => $road,
				'tax_class_id' => $this->config->get('moleage_tax_class_id'),
				'text'         => ''
			);
		} else {
			$quote_data[($is_city ? 'city' : 'oblast')] = array(
				'code'         => 'mileage.' . ($is_city ? 'city' : 'oblast'),
				'title'        => $shipping_title,
				'cost'         => $cost,
				'distance'	   => $road,
				'tax_class_id' => $this->config->get('mileage_tax_class_id'),
				'text'         => ($error ? '' : $this->currency->format($this->tax->calculate($cost, $this->config->get('mileage_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']))
			);
			
		}
		$method_title = $this->language->get('text_title')
			.(!$error ? $this->getRouteMap($this->config->get('mileage_store'), $address, intval($is_city)) : '');
		
		$method_data = array();
	
		if ($quote_data) {
      		$method_data = array(
        		'code'       => 'mileage',
        		'title'      => $method_title,
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('mileage_sort_order'),
        		'error'      => false //$error
      		);
		}
	
		return $method_data;
  	}

  	public function getErrTitle() {
		return $this->language->get('mileage_fault');
	}
  	
  	public function getTitle($is_city, $weight, $distance, $show_weight) {
		return ($is_city ? $this->language->get('text_title_mileage_city') : $this->language->get('text_title_mileage_oblast'))
			. ', '. $this->language->get('mileage_description')
			. ' '. number_format($distance, 2, ',', '') . $this->language->get('km')
			. ($show_weight ? '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')' : '');
	}
  	
  	public function getCost($weight, $distance, $is_city, &$show_weght) {
		if ($this->config->get('mileage_max_distance') && $distance > $this->config->get('mileage_max_distance')) {
			$show_weght = false;
			return 'toofar';
		}
		$weight = $this->cart->getWeight();
		
		$cost_settings = ($is_city ? $this->config->get('mileage_city_rate') : $this->config->get('mileage_oblast_rate'));
		
		if (strpos($cost_settings, ':') === false) {
			$cost = floatval($cost_settings);
			$show_weght = $show_weght || false;
		}
		else {
			$cost = 0;
			$rates = explode(',', $cost_settings);
			
			$rate_data = array();
			foreach ($rates as $i => $rate) {
				$data = explode(':', $rate);
				$rate_data[$i] = $data;
				
				if (isset($data[1])) {
					$cost = $data[1];
				}
				if ($data[0] >= $weight) {
					break;
				}
			}
			$show_weght = $show_weght || (count($rates) > 1);
		}
		return $cost * $distance;
	}
	
	public function getCurrent($is_city) {
		if (isset($this->session->data['shipping_methods']['mileage']) && isset($this->session->data['shipping_methods']['mileage']['quote']) && isset($this->session->data['shipping_methods']['mileage']['quote'][($is_city ? 'city' : 'oblast')])) {
			return $this->session->data['shipping_methods']['mileage']['quote'][($is_city ? 'city' : 'oblast')];
		}
		return false;
	}
	
  	public function getRouteMap($store, $address, $is_city) {
		$toaddr = array();
		$toaddr[] = $address['country'];
		$toaddr[] = $address['zone'];
		$toaddr[] = $address['city'];
		$toaddr[] = $address['address_1'];
		$toaddr[] = $address['address_2'];
		$toaddr = array_filter($toaddr, 'strlen');
		$toaddr = implode(', ', $toaddr);
		$toaddr = htmlspecialchars_decode($toaddr);
		
		
		$route_fault = $this->language->get('mileage_fault');
		$weight = $this->cart->getWeight();
		$shippinstring = $is_city ? $this->language->get('text_title_mileage_city') : $this->language->get('text_title_mileage_oblast');
		$hide_map = ($this->config->get('mileage_hide_map') ? ' display: none;' : '');
		return <<<EOD
		
<script type="text/javascript" defer>
$(document).ready(function() {
// Как только будет загружен API и готов DOM, выполняем инициализацию
ymaps.ready(initMap);

function initMap() {
	var myMap = new ymaps.Map("map", {
		center:[55.753559,37.609218],
		zoom:8
	});

	//Построим полный маршрут и затем добавим его на карту.
	ymaps.route([
		'$store',
		'$toaddr'
	], {
		mapStateAutoApply:true
	}).then(function (route) {
		myMap.geoObjects.add(route);
		var way = route.getPaths().get(0);

		road = way.getLength()/1000;
		
		console.log('Distance: ', road);
		$.getJSON('index.php?route=module/shipping_mileage/mileage_cost&weight=$weight&mileage_city=$is_city&mileage_distance=' + road, function(data) {
		   $('label[for="mileage.city"]').find('span[class="title"]').html(data.title);
		   $('label[for="mileage.city"]').find('span[class="text"]').html(data.text);
		   $('label[for="mileage.oblast"]').find('span[class="title"]').html(data.title);
		   $('label[for="mileage.oblast"]').find('span[class="text"]').html(data.text);
		   
		  var td = $('table.simplecheckout-cart td.price').find("b:contains('$shippinstring')");
		  td.html(data.title);
		  td.parents('tr').find('td.total nobr').html(data.text);
		})
	}, function (error) {
		$.getJSON('index.php?route=module/shipping_mileage/fault', function(data) {
		  $('label[for="mileage.city"]:last').html('$route_fault');
		  $('label[for="mileage.oblast"]:last').html('$route_fault');

		  $('label[for="mileage.city"]:last').html('');
		  $('label[for="mileage.oblast"]:last').html('');

		  var is_checked1 = $('input[value="mileage.oblast"]').is(':checked');
		  $('input[value="mileage.oblast"]').removeAttr('checked');
		  if (is_checked1) $('input[value="mileage.oblast"]').trigger('change');

		  var is_checked2 = $('input[value="mileage.city"]').is(':checked');
		  $('input[value="mileage.city"]').removeAttr('checked');
		  if (is_checked2) $('input[value="mileage.city"]').trigger('change');
		})
	});
}
})
</script>
<div id="map" style="width: 100%; height: 350px;$hide_map"> </div>
EOD;
	}
}
?>
