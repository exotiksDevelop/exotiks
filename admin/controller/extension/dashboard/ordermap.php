<?php 
class ControllerExtensionDashboardOrdermap extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/ordermap');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_setting_setting->editSetting('dashboard_ordermap', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_zoom'] = $this->language->get('entry_zoom');
		$data['entry_center'] = $this->language->get('entry_center');
		$data['entry_apikey'] = $this->language->get('entry_apikey');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/ordermap', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/ordermap', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_ordermap_width'])) {
			$data['dashboard_ordermap_width'] = $this->request->post['dashboard_ordermap_width'];
		} else {
			$data['dashboard_ordermap_width'] = $this->config->get('dashboard_ordermap_width');
		}

		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		if (isset($this->request->post['dashboard_ordermap_status'])) {
			$data['dashboard_ordermap_status'] = $this->request->post['dashboard_ordermap_status'];
		} else {
			$data['dashboard_ordermap_status'] = $this->config->get('dashboard_ordermap_status');
		}

		if (isset($this->request->post['dashboard_ordermap_sort_order'])) {
			$data['dashboard_ordermap_sort_order'] = $this->request->post['dashboard_ordermap_sort_order'];
		} else {
			$data['dashboard_ordermap_sort_order'] = $this->config->get('dashboard_ordermap_sort_order');
		}
		
		if (isset($this->request->post['dashboard_ordermap_zoom'])) {
			$data['dashboard_ordermap_zoom'] = $this->request->post['dashboard_ordermap_zoom'];
		} else {
			$data['dashboard_ordermap_zoom'] = $this->config->get('dashboard_ordermap_zoom');
		}
		
		if (isset($this->request->post['dashboard_ordermap_center'])) {
			$data['dashboard_ordermap_center'] = $this->request->post['dashboard_ordermap_center'];
		} else {
			$data['dashboard_ordermap_center'] = $this->config->get('dashboard_ordermap_center');
		}
		
		if (isset($this->request->post['dashboard_ordermap_apikey'])) {
			$data['dashboard_ordermap_apikey'] = $this->request->post['dashboard_ordermap_apikey'];
		} else {
			$data['dashboard_ordermap_apikey'] = $this->config->get('dashboard_ordermap_apikey');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/ordermap_form', $data));
	}

	public function dashboard() {
		$this->load->language('extension/dashboard/ordermap');

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['entry_count'] = $this->language->get('entry_count');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_period'] = $this->language->get('entry_period');
		$data['entry_status'] = $this->language->get('entry_status');
		
		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_save'] = $this->language->get('button_save');
		
		$data['text_order'] = $this->language->get('text_order');
		$data['text_sale'] = $this->language->get('text_sale');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if ($this->config->get('maporder_order_status')) {
			$data['maporder_order_status'] = explode(',',$this->config->get('maporder_order_status'));
		} else {
			$data['maporder_order_status'] = array();
		}
		
		$data['order_quantities'] = array(50,200,500,700,1000,1500,2000,2500,3000,3500,4000,4500,5000,5500,6000);
		if ($this->config->get('maporder_order_qty')) {
			$data['maporder_order_qty'] = $this->config->get('maporder_order_qty');
		} else {
			$data['maporder_order_qty'] = 50;
		}

		if ($this->config->get('maporder_order_min')) {
			$data['maporder_order_min'] = $this->config->get('maporder_order_min');
		} else {
			$data['maporder_order_min'] = '';
		}
		
		if ($this->config->get('maporder_order_max')) {
			$data['maporder_order_max'] = $this->config->get('maporder_order_max');
		} else {
			$data['maporder_order_max'] = '';
		}
		
		if ($this->config->get('maporder_order_start')) {
			$data['maporder_order_start'] = $this->config->get('maporder_order_start');
		} else {
			$data['maporder_order_start'] = '';
		}

		if ($this->config->get('maporder_order_end')) {
			$data['maporder_order_end'] = $this->config->get('maporder_order_end');
		} else {
			$data['maporder_order_end'] = '';
		}
		
		if ($this->config->get('dashboard_ordermap_zoom')) {
			$data['maporder_order_zoom'] = $this->config->get('dashboard_ordermap_zoom');
		} else {
			$data['maporder_order_zoom'] = 4;
		}
		
		if ($this->config->get('dashboard_ordermap_apikey')) {
			$data['apikey'] = $this->config->get('dashboard_ordermap_apikey');
		} else {
			$data['apikey'] = '';
		}
		
		$data['center_latitude'] = '55.76';
		$data['center_longitude'] = '37.64';
		
		if ($this->config->get('dashboard_ordermap_center')) {
			$config_centers = explode(',', $this->config->get('dashboard_ordermap_center'));
			
			if(!empty($config_centers[0]) && !empty($config_centers[1])) {
				$data['center_latitude'] = trim($config_centers[0]);
				$data['center_longitude'] = trim($config_centers[1]);
			}
		}
		
		$data['map_setting'] = array(
			'filter_order_status_id' => $data['maporder_order_status'],
			'filter_shipping'        => '',
			'filter_payment'         => '',
			'filter_date_start'      => $data['maporder_order_start'],
			'filter_date_end'        => $data['maporder_order_end'],
			'filter_total_min'       => $data['maporder_order_min'],
			'filter_total_max'       => $data['maporder_order_max'],
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $data['maporder_order_qty']
		);		
		
		return $this->load->view('extension/dashboard/ordermap_info', $data);
	}

	public function getMapOrders($data = array()) {
		$orders = array();
		
		$variant = md5(serialize($data));
		
		$cache = array();
		
		$cache = $this->cache->get('map.orders_' . $variant);

		if (!empty($cache)) {
			$orders = $cache;
		} else {
			$sql = "SELECT o.* FROM `" . DB_PREFIX . "order` o";

			if (!empty($data['filter_order_status_id'])) {
				$implode = array();
				
				$statuses = $data['filter_order_status_id'];
				
				foreach ($statuses as $status_id) {
					if(trim($status_id) != ''){
						$implode[] = (int)$status_id;
					}
				}
				
				if($implode){
					$sql .= " WHERE o.order_status_id IN (" . implode(',', $implode) . ")";
				} else {
					$sql .= " WHERE o.order_status_id > '0'";
				}

			} else {
				$sql .= " WHERE o.order_status_id > '0'";
			}

			if (isset($data['filter_order_id']) && !empty($data['filter_order_id'])) {
				$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
			}

			if (isset($data['filter_shipping']) && !empty($data['filter_shipping'])) {
				$sql .= " AND o.shipping_method = '" . $this->db->escape($data['filter_shipping']) . "'";
			}

			if (isset($data['filter_payment']) && !empty($data['filter_payment'])) {
				$sql .= " AND o.payment_method = '" . $this->db->escape($data['filter_payment']) . "'";
			}
			
			if (isset($data['filter_date_start']) && !empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_start']) . "')";
			}
			
			if (isset($data['filter_date_end']) && !empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
			}
			
			if (isset($data['filter_total_min']) && !empty($data['filter_total_min'])) {
				$sql .= " AND o.total >= '" . (float)$data['filter_total_min'] . "'";
			}
			
			if (isset($data['filter_total_max']) && !empty($data['filter_total_max'])) {
				$sql .= " AND o.total <= '" . (float)$data['filter_total_max'] . "'";
			}

			$cimplode = array();
			
			$countries = array(176,220,20,109,80,11,15,140,216,207,226,115,215,0);
			
			foreach ($countries as $country_id) {
				if(trim($country_id) != ''){
					$cimplode[] = (int)$country_id;
				}
			}
			
			if($cimplode){
				$sql .= " AND o.shipping_country_id IN (" . implode(',', $cimplode) . ")";
			}

			$sort_data = array(
				'o.order_id',
				'o.date_added',
				'o.total'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY o.date_added";
			}

			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
			}

			if (isset($data['start']) && (isset($data['limit']) && !empty($data['limit']))) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 50;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);
			
			$orders = $query->rows;
			
			$cache = $orders;

			$this->cache->set('map.orders_' . $variant, $cache);
		}

		return $orders;
	}
	
	public function install() {
		$query = $this->db->query("DESCRIBE `" . DB_PREFIX . "order`");
		
		foreach ($query->rows as $result) {
			$fields[] = $result['Field'];
		}
		
		if (!in_array('shipping_longitude', $fields)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `shipping_longitude` FLOAT(9,6) NOT NULL AFTER `shipping_code`");
		}

		if (!in_array('shipping_latitude', $fields)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `shipping_latitude` FLOAT(9,6) NOT NULL AFTER `shipping_code`");
		}
		
		$this->load->model('setting/setting');
		
		$first_setting = array(
			'dashboard_ordermap_width' => 8,
			'dashboard_ordermap_status' => 1,
			'dashboard_ordermap_sort_order' => 5,
			'dashboard_ordermap_zoom' => 4,
			'dashboard_ordermap_center' => '55.76, 37.64'
		);
		$this->model_setting_setting->editSetting('dashboard_ordermap', $first_setting);
		
		$map_setting = array(
			'dashboard_map_width' => 6,
			'dashboard_map_status' => 0,
			'dashboard_map_sort_order' => 5
		);
		$this->model_setting_setting->editSetting('dashboard_map', $map_setting);
		
		$chart_setting = array(
			'dashboard_chart_width' => 4,
			'dashboard_chart_status' => 1,
			'dashboard_chart_sort_order' => 6
		);
		$this->model_setting_setting->editSetting('dashboard_chart', $chart_setting);
	}
	
	public function saveMapSetting() {
		$json = array();
		
		$this->load->language('extension/dashboard/ordermap');
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/order')) {
			$data_setting = $this->request->post;
			
			if (isset($data_setting['maporder_order_status']) && !empty($data_setting['maporder_order_status'])) {
				$data_setting['maporder_order_status'] = implode(',', $data_setting['maporder_order_status']);
			}
			
			$this->request->post = $data_setting;
			
			$this->model_setting_setting->editSetting('maporder', $this->request->post);
			$this->cache->delete('orders');

			$json['success'] = $this->language->get('text_success');

		} else {
			$json['error'] = $this->language->get('error_permission');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getNewMapSetting() {
		$json = array();

		if (isset($this->request->post) && !empty($this->request->post)) {
			$setting = $this->request->post;

			if (isset($setting['maporder_order_status']) && !empty($setting['maporder_order_status'])) {
				$status = $setting['maporder_order_status'];
			} else {
				$status = array();
			}

			$data = array(
				'filter_order_status_id' => $status,
				'filter_shipping'   => '',
				'filter_payment'    => '',
				'filter_date_start' => $setting['maporder_order_start'],
				'filter_date_end'   => $setting['maporder_order_end'],
				'filter_total_min'  => $setting['maporder_order_min'],
				'filter_total_max'  => $setting['maporder_order_max'],
				'sort'  => 'o.date_added',
				'order' => 'DESC',
				'start' => 0,
				'limit' => $setting['maporder_order_qty']
			);
			
			$json['success'] = urlencode(json_encode($data));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getMapsData() {
		$mapdata = array();
		
		if (isset($this->request->post['map_setting']) && !empty($this->request->post['map_setting'])) {
			$setting = json_decode(urldecode($this->request->post['map_setting']), true);

			$filter_data = array(
				'filter_order_status_id' => $setting['filter_order_status_id'],
				'filter_shipping'   => '',
				'filter_payment'    => '',
				'filter_date_start' => $setting['filter_date_start'],
				'filter_date_end'   => $setting['filter_date_end'],
				'filter_total_min'  => $setting['filter_total_min'],
				'filter_total_max'  => $setting['filter_total_max'],
				'sort'  => 'o.date_added',
				'order' => 'DESC',
				'start' => 0,
				'limit' => $setting['limit']
			);
		} else {
			$filter_data = array(
				'sort'  => 'o.date_added',
				'order' => 'DESC',
				'start' => 0,
				'limit' => 50
			);
		}
	
		$points = array();
	
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
	
		$results = $this->getMapOrders($filter_data);
		
		foreach($results as $result) {
			if ($result['shipping_country'] && !empty($result['shipping_country'])) {
				$country = $result['shipping_country'];
			} else {
				$config_country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
				$country = $config_country['name'];
			}
			
			if ($result['shipping_zone'] && !empty($result['shipping_zone'])) {
				$zone = $result['shipping_zone'];
			} else {
				$config_zone = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));
				$zone = $config_zone['name'];
			}
			
			$address_data = array(
				'country'  => $country,
				'zone'     => $zone,
				'city'     => $result['shipping_city'],
				'street'   => $result['shipping_address_1']
			);
			
			$order_id = $result['order_id'];
			
			$preset = 'islands#dotIcon';
			
			if (in_array($result['order_status_id'], $this->config->get('config_complete_status'))) {
				$iconColor = '#56DB40';
			} elseif (($result['order_status_id'] != $this->config->get('config_order_status_id')) && !in_array($result['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				$iconColor = '#B5B5B5';
			} elseif ($result['order_status_id'] == $this->config->get('config_order_status_id')) {
				$iconColor = '#EE0000';
			} else {
				$iconColor = '#0095B6';
			}

			if((isset($result['shipping_latitude']) && ($result['shipping_latitude'] != '0.000000')) && (isset($result['shipping_longitude']) && ($result['shipping_longitude'] != '0.000000'))) {
				$coordinates = array($result['shipping_latitude'],$result['shipping_longitude']);
			} else {
				$coordinates = $this->getCoordinats($address_data, $order_id);
			}

			if ($coordinates) {
				$geometry = array(
					'type'        => 'Point',
					'coordinates' => $coordinates
				);
				
				$properties = array(
					'balloonContent' => '',
					'clusterCaption' => 'Заказ № ' . $order_id,
					'hintContent'    => $result['shipping_city']
				);

				$options = array(
					'preset' => $preset,
					'iconColor' => $iconColor
				);

				$points[] = array(
					'type'       => 'Feature',
					'id'         => $order_id,
					'geometry'   => $geometry,
					'properties' => $properties,
					'options'    => $options
				);
			}
		}
		
		$mapdata = array(
			'type' => 'FeatureCollection',
			'features' => $points
		);
	
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($mapdata));
	}
	
	public function getCoordinats($address_data, $order_id) {
		$coords = array();
		$apikey = $this->config->get('dashboard_ordermap_apikey');
	
		if ($apikey) {
			if ($this->config->get('config_geocode')) {
				$config_geocodes = explode(',', $this->config->get('config_geocode'));
				if(!empty($config_geocodes[0]) && !empty($config_geocodes[1])) {
					$coords = array($config_geocodes[0], $config_geocodes[1]);
				}
			}
	
			if (!$coords) {
				$coords = array('55.755814','37.617634');
			}
	
			$limit = 1;
	
			$address = implode(', ', $address_data);
			
			$xml = simplexml_load_file('https://geocode-maps.yandex.ru/1.x/?apikey='.$apikey.'&geocode='.urlencode($address).'&results='.$limit);
	
			$found = $xml->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found;
	
			if ($found > 0) {
				$coords = explode(' ', $xml->GeoObjectCollection->featureMember->GeoObject->Point->pos);
				list($coords[0], $coords[1]) = array($coords[1], $coords[0]);
	
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET shipping_latitude = '" . (float)$coords[0] . "', shipping_longitude = '" . (float)$coords[1] . "' WHERE order_id = '" . (int)$order_id . "'");
			}
		}
	
		return $coords;
	}
	
	public function getMapBalloonData() {
		$json = array();
		
		if (isset($this->request->get['order_id']) && !empty($this->request->get['order_id'])) {
			
			$order_id = $this->request->get['order_id'];
			$order_href = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, 'SSL');
			
			$ballooncontent = '';
			
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($order_id);
			
			if(!empty($order_info)) {
				$ballooncontent .= '<a style="text-decoration:underline;" href="' . $order_href . '" target="_blank">Заказ № ' . $order_id . '</a><br />Сумма: ' . $this->currency->format($order_info['total'], $this->config->get('config_currency')) . '<br />Доставка: ' . $order_info['shipping_method'] . '<br />Оплата: ' . $order_info['payment_method'];
			}
			
			$products = array();

			$order_products = $this->model_sale_order->getOrderProducts($order_id);
			
			if(!empty($order_products)) {
				foreach ($order_products as $product) {
					$products[] = array(
						'product_id' => $product['product_id'],
						'name'       => $product['name'],
						'quantity'   => $product['quantity'],
						'price'      => $this->currency->format($product['price'], $this->config->get('config_currency'))
					);
				}
			}
			
			if(!empty($products)) {
				$ballooncontent .= '<table style="border-top:1px solid #DDDDDD;border-left:1px solid #DDDDDD;border-collapse:collapse;width:100%;margin-top:5px;"><tbody>';
				foreach ($products as $product) {
					$ballooncontent .= '<tr>';
					$ballooncontent .= '<td style="text-align:center;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;padding:0px 3px;">' . $product['product_id'] . '</td>';
					$ballooncontent .= '<td style="text-align:left;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;line-height:1;padding:0px 3px;">' . $product['name'] . '</td>';
					$ballooncontent .= '<td style="text-align:right;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;white-space:nowrap;padding:0px 3px;">' . $product['price'] . '</td>';
					$ballooncontent .= '<td style="text-align:right;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;padding:0px 3px;">' . $product['quantity'] . '</td>';
					$ballooncontent .= '</tr>';
				}
				$ballooncontent .= '</tbody></table>';
			}
			
			$json = $ballooncontent;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}