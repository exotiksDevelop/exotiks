<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
class ModelShippingLLOzon extends Model {
	private $m = 'll_ozon';
	private $api_auth_url = 'https://xapi.ozon.ru/principal-auth-api/connect/token';
	private $api_auth_url_test = 'https://api-stg.ozonru.me/principal-auth-api/connect/token';
	private $api_url = 'https://xapi.ozon.ru/principal-integration-api/v1/delivery/calculate/information';
	private $api_url_test = 'https://api-stg.ozonru.me/principal-integration-api/v1/delivery/calculate/information';
	private $variants = [
		['code' => 'pickpoint', 'name' => 'Самовывоз'],
		['code' => 'postamat', 'name' => 'Постамат'],
		['code' => 'courier', 'name' => 'Курьер'],
	];
	private $variants_map = ['pickpoint', 'postamat'];
	private $pickup_city = null;
	private $delivery_city = null;
	private $city = null;
	private $address = null;
	private $total = 0;
	private $params = null;
	private $preparies = null;
	private $calcs = null;
	private $prices = null;
	private $pvz = null;
	private $active_id = null;
	private $active_address = null;
	private $active_full_address = null;
	private $active_phone = null;
	private $map_prefix = null;
	private $map = null;

	function getQuote($address) {
		$status = true;
		$method_data = [];
		$this->total = $this->cart->getTotal();
		$this->sub_total = $this->cart->getSubTotal();
		$weight = $this->cart->getWeight();
		// конвертируем в кг из дефолтных единиц магазина
		$weight = $this->weight->convert($weight, $this->config->get('config_weight_class_id'), $this->config->get($this->m . '_weight_class_id'));

		if (!$this->config->get($this->m . '_client_id') || !$this->config->get($this->m . '_client_secret') || !isset($address['city'])) {
			$status = false;
		}

		if ($status && !empty($this->config->get($this->m . '_stops'))) {
			foreach ($this->config->get($this->m . '_stops') as $stop) {
				if (empty($stop['variant']) || count($stop['variant']) == count($this->variants)) {
					if (!empty($stop['customer_group']) && (!$this->customer->isLogged() || ($this->customer->isLogged() && !in_array($this->customer->getGroupId(), $stop['customer_group'])))) {
						$status = false;
						break;
					}

					if (!empty($stop['geo_zone'])) {
						$geo_zone_rows = 0;

						foreach ($stop['geo_zone'] as $geo_zone_id) {
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

							$geo_zone_rows += $query->num_rows;
						}

						if (!$geo_zone_rows) {
							$status = false;
							break;
						}
					}

					$city = mb_strtolower(trim($address['city']));

					if ($stop['city_only'] != '' && !in_array($city, explode(',', $stop['city_only']))) {
						$status = false;
						break;
					}

					if ($stop['city_exclude'] != '' && in_array($city, explode(',', $stop['city_exclude']))) {
						$status = false;
						break;
					}

					if ($stop['weight_min'] != '' && $stop['weight_min'] != 0 && (float)$weight < (float)$stop['weight_min']) {
						$status = false;
						break;
					}

					if ($stop['weight_max'] != '' && $stop['weight_max'] != 0 && (float)$weight > (float)$stop['weight_max']) {
						$status = false;
						break;
					}

					if ($stop['total_min'] != '' && $stop['total_min'] != 0 && (float)$this->total < (float)$stop['total_min']) {
						$status = false;
						break;
					}

					if ($stop['total_max'] != '' && $stop['total_max'] != 0 && (float)$this->total > (float)$stop['total_max']) {
						$status = false;
						break;
					}
				}
			}
		}

		if ($status) {
			$this->load->language('shipping/' . $this->m);

			$this->address = $address;
			$this->pickup_city = $this->getPickupCity();
			$this->delivery_city = $this->getCity();

			foreach ($this->variants_map as $variant) {
				$this->active_id[$variant] = $this->getActivePvzId($variant);
			}

			$weight = $product_weight = 0;
			$length = $product_length = 0;
			$width = $product_width = 0;
			$height = $product_height = 0;
			$start = true;
			$this->map_prefix = $this->config->has('ll_shipping_mapper_methods') && in_array($this->m, $this->config->get('ll_shipping_mapper_methods')) ? 'll_map' : $this->m;

			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$product_weight = $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get($this->m . '_weight_class_id'));
					$product_length = $this->length->convert($product['length'], $product['length_class_id'], $this->config->get($this->m . '_length_class_id'));
					$product_width = $this->length->convert($product['width'], $product['length_class_id'], $this->config->get($this->m . '_length_class_id'));
					$product_height = $this->length->convert($product['height'], $product['length_class_id'], $this->config->get($this->m . '_length_class_id'));

					$weight += (float)$product_weight;

					if ($product_weight == 0 && !$this->config->get($this->m . '_default_type')) {
						$weight += (float)$this->config->get($this->m . '_default_weight') * $product['quantity'];
					}

					$product_length = $product_length == 0 && !$this->config->get($this->m . '_default_type') ? (float)$this->config->get($this->m . '_default_length') : $product_length;
					$product_width = $product_width == 0 && !$this->config->get($this->m . '_default_type') ? (float)$this->config->get($this->m . '_default_width') : $product_width;
					$product_height = $product_height == 0 && !$this->config->get($this->m . '_default_type') ? (float)$this->config->get($this->m . '_default_height') : $product_height;

					if ($this->config->get($this->m . '_calc_type') == 1) {
						$length += $product_length * $product['quantity'];

						if ($product_width > $width) {
							$width = $product_width;
						}

						if ($product_height > $height) {
							$height = $product_height;
						}
					} elseif ($this->config->get($this->m . '_calc_type') == 2) {
						if ($product_length > $length) {
							$length = $product_length;
						}

						if ($product_width > $width) {
							$width = $product_width;
						}

						$height += $product_height * $product['quantity'];
					} else {
						if ($product_length > $length) {
							$length = $product_length;
						}

						$width += $product_width * $product['quantity'];

						if ($product_height > $height) {
							$height = $product_height;
						}
					}
				}
			}

			if ($this->config->get($this->m . '_default_type')) {
				if ($weight == 0) {
					$weight = (float)$this->config->get($this->m . '_default_weight');
				}

				if ($product_length == 0) {
					$length = (float)$this->config->get($this->m . '_default_length');
				}

				if ($product_width == 0) {
					$width = (float)$this->config->get($this->m . '_default_width');
				}

				if ($product_height == 0) {
					$height = (float)$this->config->get($this->m . '_default_height');
				}
			}

			if ($this->config->get($this->m . '_custom_sizes')) {
				$helper = DIR_SYSTEM . 'helper/' . $this->m . '.php';

				if (file_exists($helper) && is_file($helper)) {
					require_once($helper);

					$custom_sizes = getCustomSizes($this->registry);
					$weight = isset($custom_sizes['weight']) ? $custom_sizes['weight'] : $weight;
					$length = isset($custom_sizes['length']) ? $custom_sizes['length'] : $length;
					$width = isset($custom_sizes['width']) ? $custom_sizes['width'] : $width;
					$height = isset($custom_sizes['height']) ? $custom_sizes['height'] : $height;
				}
			}

			if ((float)$this->config->get($this->m . '_box_weight') > 0) {
				$weight += (float)$this->config->get($this->m . '_box_weight');
			}

			if ((float)$this->config->get($this->m . '_box_length') > 0) {
				$length += (float)$this->config->get($this->m . '_box_length');
			}

			if ((float)$this->config->get($this->m . '_box_width') > 0) {
				$width += (float)$this->config->get($this->m . '_box_width');
			}

			if ((float)$this->config->get($this->m . '_box_height') > 0) {
				$height += (float)$this->config->get($this->m . '_box_height');
			}

			// переводим в г и мм для api
			$weight *= 1000;
			$length *= 10;
			$width *= 10;
			$height *= 10;

			$this->params = [
				'fromPlaceId'        => (float)$this->pickup_city,
				'destinationAddress' => $this->delivery_city['name'],
				'packages'           => [
					0 => [
						'count'      => 1,
						'dimensions' => [
							'weight'  => (int)(ceil($weight)),
							'length'  => (int)(ceil($length)),
							'height'  => (int)(ceil($height)),
							'width'   => (int)(ceil($width)),
						],
						'price'      => $this->total,
					],

				],
			];

			$this->session->data[$this->m]['from_city'] = $this->params['fromPlaceId'];
			$this->session->data[$this->m]['to_city'] = $this->delivery_city['city_id'];
			$this->session->data[$this->m]['weight'] = $this->params['packages'][0]['dimensions']['weight'];

			if ($start && $this->pickup_city && $this->delivery_city) {
				$results = $this->getCost();
			}

			if (!empty($results)) {
				$quote_data = [];

				foreach ($results as $result) {
					$map = null;

					if (in_array($result['code'], $this->variants_map)) {
						// это в админке
						if (isset($this->session->data['api_id'])) {
							$map .= '<div class="input-group hidden ll_shipping" data-shipping="' . $this->m . '.' . $this->m . '_' . $result['code'] . '"><div class="input-group-addon">' . $this->config->get($this->m . '_map_button') . '</div><select class="form-control" onchange="ll_shipping_set_pickup_id(this.value, \'' . $result['code'] . '\');">';

							foreach ($this->pvz[$result['code']] as $point) {
								$map .= '<option value="' . $point['external_id'] . '" ' . ($point['external_id'] == $this->active_id[$result['code']] ? 'selected="selected"' : '') . '>' . ($point['placement'] == '' || is_numeric($point['placement']) ? $point['address'] : $point['placement']) . '</option>';
							}

							$map .= '</select></div>';
						} else {
							if ($this->config->get($this->m . '_list_' . $result['code']) && !empty($this->pvz[$result['code']])) {
								$map .= '<div style="max-width: 100%;"><select class="ll_change_point" data-onchange="reloadAll" onchange="' . $this->map_prefix . '_set_pickup_id(this.value, \'' . $result['code'] . '\');" style="max-width: 300px;">';

								foreach ($this->pvz[$result['code']] as $pvz) {
									$map .= '<option value="' . $pvz['external_id'] . '" ' . ($pvz['external_id'] == $this->active_id[$result['code']] ? 'selected="selected"' : '') . '>' . ($pvz['placement'] == '' || is_numeric($pvz['placement']) ? $pvz['address'] : $pvz['placement']) . '</option>';
								}

								$map .= '</select>';
							}

							if ($this->config->get($this->m . '_map_type') && $this->config->get($this->m . '_map_status')) {
								$map .= '<a class="btn btn-primary ll_open_map" style="padding: 1px;" onclick="' . $this->map_prefix . '_show_modal(\'' . ($this->config->get('ll_shipping_mapper_filter') != 0 ? $this->config->get('ll_shipping_mapper_filter') : $this->m) . '\', \'' . $result['code'] . '\'); return false;">' . $this->config->get($this->m . '_map_button') . '</a>';
							}

							if ($this->config->get($this->m . '_list_' . $result['code']) && !empty($this->pvz[$result['code']])) {
								$map .= '</div>';
							}
						}
					}

					$quote_data[$this->m . '_' . $result['code']] = [
						'code'         => $this->m . '.' . $this->m . '_' . $result['code'],
						'title'        => $result['title'],
						'cost'         => $this->config->get('ll_total_status') ? $result['cost_total'] : $result['cost'],
						'cost_total'   => $this->config->get('ll_total_status') ? $result['cost'] : $result['cost_total'],
						'tax_class_id' => $this->config->get($this->m . '_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($result['cost_total'], $this->config->get($this->m . '_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']),
						'description'  => $result['description'] . $map,
					];

					if (!isset($this->session->data['shipping_method']['code']) && isset($this->session->data['ll_shipping_widget_active']) && $this->session->data['ll_shipping_widget_active'] == $quote_data[$this->m . '_' . $result['code']]['code']) {
						$this->session->data['shipping_method']['code'] = $quote_data[$this->m . '_' . $result['code']]['code'];
						$this->session->data['shipping_method']['title'] = $quote_data[$this->m . '_' . $result['code']]['title'];
						$this->session->data['shipping_method']['cost'] = $quote_data[$this->m . '_' . $result['code']]['cost'];
						$this->session->data['shipping_method']['tax_class_id'] = $quote_data[$this->m . '_' . $result['code']]['tax_class_id'];
						$this->session->data['shipping_method']['text'] = $quote_data[$this->m . '_' . $result['code']]['text'];
					}
				}

				$map = null;

				if (!empty($this->map) && !isset($this->session->data['api_id'])) {
					$this->map['data']['type'] = 'FeatureCollection';

					if ($this->config->has('ll_shipping_mapper_methods') && in_array($this->m, $this->config->get('ll_shipping_mapper_methods'))) {
						$this->map['controls'] = $this->config->get('ll_shipping_mapper_map_control') ? $this->config->get('ll_shipping_mapper_map_control') : [];
					} else {
						$this->map['controls'] = $this->config->get($this->m . '_map_control') ? $this->config->get($this->m . '_map_control') : [];

						if ($this->config->get($this->m . '_map_status')) {
							$map .= '<script>' . $this->map_prefix . '_init(' . json_encode($this->map) . ', ' . json_encode($this->config->get('ll_shipping_mapper_methods')) . ');</script>';
						}
					}

					if (!$this->config->get($this->m . '_map_type') && $this->config->get($this->m . '_map_status')) {
						$map .= '<p><a class="btn btn-primary ll_open_map" style="padding: 1px;" onclick="' . $this->map_prefix . '_show_modal(\'' . ($this->config->get('ll_shipping_mapper_filter') != 0 ? $this->config->get('ll_shipping_mapper_filter') : '') . '\');">' . $this->config->get($this->m . '_map_button') . '</a></p>';
					}
				}

				$method_data = [
					'code'       => $this->m,
					'title'      => $this->setTitle($this->config->get($this->m . '_title')) . $map,
					'quote'      => $quote_data,
					'sort_order' => $this->config->get($this->m . '_sort_order'),
					'error'      => false,
				];
			}
		}

		if (empty($method_data)) {
			$method_data = $this->getCap();
		}

		return $method_data;
	}

	protected function getCap() {
		if ($this->config->get($this->m . '_cap_status')) {
			$cost = $this->config->get($this->m . '_cap_cost') > 0 ? $this->config->get($this->m . '_cap_cost') : 0;

			$quote_data[$this->m . '_empty'] = [
				'code'         => $this->m . '.' . $this->m . '_empty',
				'title'        => $this->setTitle($this->config->get($this->m . '_cap_title')),
				'cost'         => $cost,
				'tax_class_id' => $this->config->get($this->m . '_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get($this->m . '_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']),
			];

			$method_data = [
				'code'       => $this->m,
				'title'      => $this->setTitle($this->config->get($this->m . '_title')),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get($this->m . '_sort_order'),
				'error'      => $this->config->get($this->m . '_cap_error') ? $this->setTitle($this->config->get($this->m . '_cap_title')) : false,
			];

			return $method_data;
		}
	}

	protected function getPickupCity() {
		$pickup_cities = $this->config->get($this->m . '_pickup_cities');

		if (!empty($pickup_cities)) {
			$pickup_city = $pickup_cities['0'];
		} else {
			$pickup_city = 0;
		}

		return $pickup_city;
	}

	protected function getPickupCityName($id) {
		$query = $this->db->query("SELECT city FROM `" . DB_PREFIX . $this->m . "_place` WHERE id LIKE '" . $this->db->escape($id) . "'");

		return isset($query->row['city']) ? $query->row['city'] : '';
	}

	protected function getCity() {
		$data = false;
		$city = trim($this->address['city']);
		$zone = $this->address['zone_id'];

		if ($city != '') {
			if ($zone > 0) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_city` as c LEFT JOIN `" . DB_PREFIX . $this->m . "_region_to_zone` rz ON rz.zone_id = '" . (int)$zone . "' WHERE c.name LIKE '" . $this->db->escape($city) . "' AND c.region_id = rz.region_id ORDER BY c.city_id ASC");

				if ($query->num_rows) {
					if ($query->num_rows > 1) {
						foreach ($query->rows as $row) {
							if ($row['name'] == $city) {
								$data = $row;

								break;
							}
						}
					} else {
						$data = $query->row;
					}

					$this->addLog(1, 'getCity', ['city' => $city, 'zone_id' => $zone], $query->rows);
				} elseif (!$this->config->get($this->m . '_consider')) {
					$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_city` WHERE name LIKE '" . $this->db->escape($city) . "' ORDER BY city_id ASC");

					if ($query->num_rows) {
						$data = $query->row;

						$this->addLog(1, 'getCity', ['city' => $city, 'zone_id' => $zone], $query->rows);
					} else {
						$this->addLog(0, 'getCity', ['city' => $city, 'zone_id' => $zone]);
					}
				} else {
					$this->addLog(0, 'getCity', ['city' => $city, 'zone_id' => $zone]);
				}
			} elseif ($zone == 0 && !$this->config->get($this->m . '_consider')) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_city` WHERE name LIKE '" . $this->db->escape($city) . "' ORDER BY city_id ASC");

				if ($query->num_rows) {
					if ($query->num_rows > 1) {
						foreach ($query->rows as $row) {
							if ($row['name'] == $city) {
								$data = $row;

								break;
							}
						}
					} else {
						$data = $query->row;
					}

					$this->addLog(1, 'getCity', ['city' => $city, 'zone_id' => $zone], $query->rows);
				} else {
					$this->addLog(0, 'getCity', ['city' => $city, 'zone_id' => $zone]);
				}
			}

			return $data;
		}
	}

	public function getCities($city) {
		$query = $this->db->query("SELECT c.city_id AS id, c.name AS city, c.name AS full, r.zone_id AS zone_id, c.country_id AS country_id FROM `" . DB_PREFIX . $this->m . "_city` c LEFT JOIN `" . DB_PREFIX . $this->m . "_region_to_zone` r ON (c.region_id = r.region_id) WHERE c.name LIKE '" . $this->db->escape($city) . "%' ORDER BY c.name ASC LIMIT 0,7");

		return $query->rows;
	}

	protected function getCost() {
		$data = $this->getCache('calculate' . $this->getCacheName(), $this->params['destinationAddress']);

		if (!$data) {
			$curl = $this->curl($this->params);

			$data = json_decode($curl['data'], true);

			if ($curl['info']['http_code'] < 200 || $curl['info']['http_code'] >= 300 || isset($data['errorCode']) || !isset($data['deliveryInfos'])) {
				$this->addLog(0, 'calculate', $this->params, $curl);

				return;
			} else {
				$this->addLog(1, 'calculate', $this->params, $curl);

				$data = $data['deliveryInfos'];

				$this->setCache('calculate' . $this->getCacheName(), $this->params['destinationAddress'], $data);
			}
		}

		if ($data) {
			$this->calcs = $data;
		}

		// готовим методы после кэша, чтобы иметь возможность выбирать нужный пвз
		if (!empty($this->calcs)) {
			return $this->prepareVariants($this->calcs);
		}
	}

	protected function prepareVariants($results) {
		$data = [];
		$city = mb_strtolower(trim($this->address['city']));

		foreach ($results as $key => $result) {
			$code = mb_strtolower($result['deliveryType']);
			$status = true;

			if (!empty($this->config->get($this->m . '_stops'))) {
				foreach ($this->config->get($this->m . '_stops') as $stop) {
					if (!empty($stop['variant']) && in_array($code, $stop['variant'])) {
						if (!empty($stop['customer_group']) && (!$this->customer->isLogged() || ($this->customer->isLogged() && !in_array($this->customer->getGroupId(), $stop['customer_group'])))) {
							$status = false;
							break;
						}

						if (!empty($stop['geo_zone'])) {
							$geo_zone_rows = 0;

							foreach ($stop['geo_zone'] as $geo_zone_id) {
								$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "' AND country_id = '" . (int)$this->address['country_id'] . "' AND (zone_id = '" . (int)$this->address['zone_id'] . "' OR zone_id = '0')");

								$geo_zone_rows += $query->num_rows;
							}

							if (!$geo_zone_rows) {
								$status = false;
								break;
							}
						}

						if ($stop['city_only'] != '' && !in_array($city, explode(',', $stop['city_only']))) {
							$status = false;
							break;
						}

						if ($stop['city_exclude'] != '' && in_array($city, explode(',', $stop['city_exclude']))) {
							$status = false;
							break;
						}

						$weight = $this->cart->getWeight();
						// конвертируем в кг из дефолтных единиц магазина
						$weight = $this->weight->convert($weight, $this->config->get('config_weight_class_id'), $this->config->get($this->m . '_weight_class_id'));

						if ($stop['weight_min'] != '' && $stop['weight_min'] != 0 && (float)$weight < (float)$stop['weight_min']) {
							$status = false;
							break;
						}

						if ($stop['weight_max'] != '' && $stop['weight_max'] != 0 && (float)$weight > (float)$stop['weight_max']) {
							$status = false;
							break;
						}

						if ($stop['total_min'] != '' && $stop['total_min'] != 0 && (float)$this->total < (float)$stop['total_min']) {
							$status = false;
							break;
						}

						if ($stop['total_max'] != '' && $stop['total_max'] != 0 && (float)$this->total > (float)$stop['total_max']) {
							$status = false;
							break;
						}
					}
				}
			}

			if (!$status || !$this->config->get($this->m . '_status_' . $code)) {
				continue;
			}

			if (in_array($code, $this->variants_map) && empty($this->pvz[$code])) {
				$this->prepareMap($code);

				if ($this->getActivePvzId($code) === 0) {
					continue;
				}
			}

			$cost = $this->prepareCost($result);

			if ((int)$result['deliveryTermInDays'] == 0) {
				$result['deliveryTermInDays']++;
			}

			$data[] = [
				'code'        => $code,
				'title'       => $this->prepareTitle($result),
				'description' => $this->prepareTitle($result, 'description'),
				'cost'        => $cost['cost'],
				'cost_total'  => $cost['cost_total'],
				'sort'        => $this->config->get($this->m . '_sort_order_' . $code),
			];
		}

		$keys = array_column($data, 'sort');
		array_multisort($keys, SORT_ASC, $data);

		return $data;
	}

	protected function prepareCost($result) {
		$code = mb_strtolower($result['deliveryType']);
		$sub_total_cost = $this->sub_total; //стоимость товаров + всего Итого до блока доставки
		$total_cost = $this->total; //стоимость заказа
		$shipping_cost = $result['price']; //стоимость доставки
		$shipping_cost = $this->config->get($this->m . '_round') ? round($shipping_cost, 0) : $shipping_cost; //стоимость доставки
		$price = $shipping_cost; //модифицированная стоимость доставки
		$price_total = $shipping_cost; //модифицированная стоимость доставки в итого
		$costs = $this->config->get($this->m . '_costs');

		if (isset($this->prices[$code][$shipping_cost])) {
			return $this->prices[$code][$shipping_cost];
		}

		if (!empty($costs)) {
			foreach ($costs as $cost) {
				if (empty($cost['variant']) || !in_array($code, $cost['variant'])) {
					continue;
				}

				switch ($cost['cost_type']) {
					case 0:
						$mod_price = $sub_total_cost;
						break;
					case 1:
						$mod_price = $total_cost;
						break;
					case 2:
						$mod_price = $shipping_cost;
						break;
				}

				if ($cost['cost_from'] > 0 && $mod_price < $cost['cost_from']) {
					continue;
				}

				if ($cost['cost_to'] > 0 && $mod_price > $cost['cost_to']) {
					continue;
				}

				if (!empty($cost['customer_group']) && $this->customer->isLogged() && in_array($this->customer->getGroupId(), $cost['customer_group'])) {
					continue;
				}

				if (!empty($cost['geo_zone'])) {
					$geo_zone_rows = 0;

					foreach ($cost['geo_zone'] as $geo_zone_id) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "' AND country_id = '" . (int)$this->address['country_id'] . "' AND (zone_id = '" . (int)$this->address['zone_id'] . "' OR zone_id = '0')");

						$geo_zone_rows += $query->num_rows;
					}

					if (!$geo_zone_rows) {
						continue;
					}
				}

				$city = mb_strtolower(trim($this->address['city']));

				if ($cost['city_only'] != '' && !in_array($city, explode(',', $cost['city_only']))) {
					continue;
				}

				if ($cost['city_exclude'] != '' && in_array($city, explode(',', $cost['city_exclude']))) {
					continue;
				}

				switch ($cost['source']) {
					case 0:
						$value = (float)$cost['value'];
						break;
					case 1:
						$value = (float)($total_cost * $cost['value'] / 100);
						break;
					case 2:
						$value = (float)($sub_total_cost * $cost['value'] / 100);
						break;
					case 3:
						$value = (float)($shipping_cost * $cost['value'] / 100);
						break;
				}

				switch ($cost['action']) {
					case '+':
						$price += $value;
						break;
					case '-':
						$price -= $value;
						break;
					case '=':
						$price = $value;
						break;
				}

				if (!$cost['position']) {
					$price_total = $price;
				}
			}
		}

		$this->prices[$code][$shipping_cost] = [
			'cost'       => $price < 0 ? 0 : $price,
			'cost_total' => $price_total < 0 ? 0 : $price_total,
		];

		return $this->prices[$code][$shipping_cost];
	}

	protected function prepareTitle($result, $type = 'title') {
		$code = mb_strtolower($result['deliveryType']);
		$img = explode('_', $code);

		$input = [
			'{{logo}}',
			'{{logo_short}}',
			'{{from_city}}',
			'{{to_city}}',
			'{{days}}',
			'{{date}}',
			'{{address}}',
			'{{full_address}}',
			'{{phone}}',
			'{{code}}',
		];

		$output = [
			'logo'         => '<img src="' . ($this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER) . 'image/catalog/' . $this->m . '/logo.png" / >',
			'logo_short'   => '<img src="' . ($this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER) . 'image/catalog/' . $this->m . '/logo_short.png" / >',
			'from_city'    => $this->getPickupCityName($this->getPickupCity()),
			'to_city'      => $this->upperFirst($this->delivery_city['name']),
			'days'         => $this->prepareDays($result),
			'date'         => $this->prepareDate($result),
			'address'      => isset($this->active_address[$code]) ? $this->active_address[$code] : '',
			'full_address' => isset($this->active_full_address[$code]) ? $this->active_full_address[$code] : '',
			'phone'        => isset($this->active_phone[$code]) ? $this->active_phone[$code] : '',
			'code'         => isset($this->active_id[$code]) ? $this->active_id[$code] : '',
		];

		return html_entity_decode(str_replace($input, $output, $this->config->get($this->m . '_quote_' . $type . '_' . $code)));
	}

	protected function prepareDays($result) {
		$code = mb_strtolower($result['deliveryType']);
		$add = (int)$this->config->get($this->m . '_add_day_' . $code);
		$days = $result['deliveryTermInDays'];

		if (stripos($days, '-')) {
			$numbers = explode('-', $days);

			$last = $numbers[1];
			$days = implode('-', $numbers);
		} elseif ($this->config->get($this->m . '_daymodifier')) {
			$last = $days + 1;
			$days = $days . '-' . $last;
		} else {
			$last = $days;
		}

		if ($add > 0) {
			if (isset($numbers)) {
				$numbers[0] += $add;
				$numbers[1] += $add;
				
				if ($numbers[0] == $numbers[1]) {
					$days = $numbers[0];
				} else {
					$days = implode('-', $numbers);
				}

				$last = $numbers[1];
			} elseif (is_numeric($days)) {
				$days += $add;

				$last = $days;
			}
		}

		return $days . $this->numericСases($last);
	}

	protected function prepareDate($result) {
		$code = mb_strtolower($result['deliveryType']);
		$add = $this->config->get($this->m . '_add_day_' . $code) ? $this->config->get($this->m . '_add_day_' . $code) : 0;

		$date = date('d M', strtotime('+' . ((int)$result['deliveryTermInDays'] + (int)$add) . ' day', strtotime(date('H:i:s'))));

		$months_input = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		$months_output = ['янв', 'фев', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'];

		$date = str_replace($months_input, $months_output, $date);

		return $date;
	}

	protected function setTitle($title) {
		$input = [
			'{{logo}}',
			'{{logo_short}}',
		];

		$output = [
			'logo'         => '<img src="' . ($this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER) . 'image/catalog/' . $this->m . '/logo.png" />',
			'logo_short'   => '<img src="' . ($this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER) . 'image/catalog/' . $this->m . '/logo_short.png" / >',
		];

		return html_entity_decode(str_replace($input, $output, $title));
	}

	protected function getActivePvzId($variant) {
		if (isset($this->session->data[$this->m][$variant])) {
			return $this->session->data[$this->m][$variant];
		} else {
			return 0;
		}
	}

	protected function prepareMap($code) {
		$active_id = $this->getActivePvzId($code);
		$pvzs = $this->getPvzs($code);
		$pvz_ids = [];

		if (!empty($pvzs)) {
			foreach ($pvzs as $key => $pickup) {
				if ((float)$pickup['minWeight'] > 0 && $pickup['minWeight'] > $this->params['packages'][0]['dimensions']['weight']) {
					continue;
				}

				if ((float)$pickup['maxWeight'] > 0 && $pickup['maxWeight'] < $this->params['packages'][0]['dimensions']['weight']) {
					continue;
				}

				if ((float)$pickup['restrictionLength'] > 0 && $pickup['restrictionLength'] < $this->params['packages'][0]['dimensions']['length']) {
					continue;
				}

				if ((float)$pickup['restrictionWidth'] > 0 && $pickup['restrictionWidth'] < $this->params['packages'][0]['dimensions']['width']) {
					continue;
				}

				if ((float)$pickup['restrictionHeight'] > 0 && $pickup['restrictionHeight'] < $this->params['packages'][0]['dimensions']['height']) {
					continue;
				}

				if ((float)$pickup['minPrice'] > 0 && $pickup['minPrice'] > $this->params['packages'][0]['price']) {
					continue;
				}

				if ((float)$pickup['maxPrice'] > 0 && $pickup['maxPrice'] < $this->params['packages'][0]['price']) {
					continue;
				}

				$this->pvz[$code][] = $pickup;

				$id = $pickup['external_id'];
				$img = $code == 'pickpoint' ? 'fa fa-home' : 'fa fa-cubes';
				$color = $code == 'pickpoint' ? 'islands#nightIcon' : 'islands#darkBlueIcon';
				$color_active = $code == 'pickpoint' ? 'islands#nightDotIcon': 'islands#darkBlueDotIcon';
				$title = '<i class="' . $img . '" aria-hidden="true"></i> ' . $pickup['placement'] == '' || is_numeric($pickup['placement']) ? $pickup['address'] : $pickup['placement'] . ' <span class="label label-default">' . $id . '</span>';

				if ($active_id === $id) {
					$this->active_address[$code] = $pickup['placement'] == '' || is_numeric($pickup['placement']) ? $pickup['address'] : $pickup['placement'];
					$this->active_full_address[$code] = $pickup['address'];
					$this->active_phone[$code] = $pickup['phone'];
				}

				$hintContent = $title;
				$balloonContentHeader = $title;
				$balloonContentBody = '<a class="btn btn-primary btn-block ll_set_point" onclick="' . $this->map_prefix . '_set_pickup_id(\'' . $id . '\', \'' . $code . '\');">' . $this->language->get('text_choose_pickup') . '</a>';
				$balloonContentBody .= '<i class="fa fa-map-marker fa-fw" style="color: blue;"></i> ' . $pickup['address'];
				$balloonContentBody .= $pickup['phone'] == '' ? '' : '<br><i class="fa fa-phone fa-fw" style="color: blue;"></i> ' . $pickup['phone'];
				$balloonContentBody .= '<br>' . (!$pickup['isCashForbidden'] ? '<i class="fa fa-rub fa-fw" style="color: blue;"></i> ' . $this->language->get('text_cash') : '<i class="fa fa-money fa-fw" style="color: red;"></i> ' . $this->language->get('text_cash_no'));
				$balloonContentBody .= '<br>' . ($pickup['cardPaymentAvailable'] ? '<i class="fa fa-credit-card fa-fw" style="color: blue;"></i> ' . $this->language->get('text_card') : '<i class="fa fa-credit-card fa-fw" style="color: red;"></i> ' . $this->language->get('text_card_no'));
				$balloonContentBody .= '<br>' . ($pickup['partialGiveOutAvailable'] ? '<i class="fa fa-pie-chart fa-fw" style="color: blue;"></i> ' . $this->language->get('text_partial') : '<i class="fa fa-pie-chart" style="color: red;"></i> ' . $this->language->get('text_partial_no'));
				$balloonContentBody .= '<br>' . ($pickup['returnAvailable'] ? '<i class="fa fa-reply fa-fw" style="color: blue;"></i> ' . $this->language->get('text_return') : '<i class="fa fa-reply" style="color: red;"></i> ' . $this->language->get('text_return_no'));
				$balloonContentBody .= '<br>' . ($pickup['fittingClothesAvailable'] ? '<i class="fa fa-female fa-fw" style="color: blue;"></i> ' . $this->language->get('text_clothes') : '<i class="fa fa-female" style="color: red;"></i> ' . $this->language->get('text_clothes_no'));
				$balloonContentBody .= $pickup['howToGet'] == '' ? '' : '<br><i class="fa fa-map-o fa-fw" style="color: blue;"></i> ' . $pickup['howToGet'];
				$balloonContentBody .= $pickup['workingHours'] == '' ? '' : '<br><i class="fa fa-clock-o fa-fw" style="color: blue;"></i>' . $this->language->get('text_work') . '<br>' . $pickup['workingHours'];

				$this->map['data']['features'][] = [
					'type'     => 'Feature',
					'id'       => $id,
					'geometry' => [
						'type'        => 'Point',
						'coordinates' => [
							$pickup['lat'],
							$pickup['long'],
						]
					],
					'properties' => [
						'hintContent'          => $hintContent,
						'balloonContentHeader' => $balloonContentHeader,
						'balloonContentBody'   => $balloonContentBody,
					],
					'options' => [
						'preset' => $id === $active_id ? $color_active : $color,
					],
					'params' => [
						'code' => $code,
					]
				];

				$pvz_ids[] = $id;

				if (!isset($this->map['delivery'][$code])) {
					$this->map['delivery'][$code] = [
						'code'    => $code,
						'content' => '<img src="' . ($this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER) . 'image/catalog/' . $this->m . '/logo.png" id="' . $this->m . '_filter_' . $code . '" />',
						'content' => '<i class="' . $img . '" aria-hidden="true" id="' . $this->m . '_filter_' . $code . '"></i> ' . $this->language->get('text_filter_' . $code),
						'title'   => $this->language->get('text_filter_' . $code),
					];
				}
			}

			// устанавливаем активный постамат, если его еще нет или он из другого города
			// чтобы адрес отображался в названии варианта самовывоза
			// оставляем ===
			if ($active_id === 0 || !in_array($active_id, $pvz_ids)) {
				$this->active_id[$code] = $this->session->data[$this->m][$code] = $this->pvz[$code][0]['external_id'];
				$this->active_address[$code] = $this->pvz[$code][0]['placement'] == '' || is_numeric($this->pvz[$code][0]['placement']) ? $this->pvz[$code][0]['address'] : $this->pvz[$code][0]['placement'];
				$this->active_full_address[$code] = $this->pvz[$code][0]['address'];
				$this->active_phone[$code] = $this->pvz[$code][0]['phone'];
			}
		} else {
			$this->session->data[$this->m][$code] = 0;
		}
	}

	protected function getPvzs($code) {
		$type = [
			'pickpoint' => 'Самовывоз',
			'postamat'  => 'Постамат',
		];

		$data = $this->getCache('pvz.' . $type[$code] . $this->getCacheName(), $this->delivery_city['city_id']);

		if (!$data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . $this->m . "_pvz` WHERE city_id = '" . (int)$this->delivery_city['city_id'] . "' AND objectTypeName LIKE '" . $this->db->escape($type[$code]) . "'";

			if ($this->config->get($this->m . '_cash')) {
				$sql .= " AND isCashForbidden = 0";
			}

			$sql .= " ORDER BY address ASC";

			$query = $this->db->query($sql);

			$data = $query->rows;

			$params = ['type' => $type[$code], 'city_id' => $this->delivery_city['city_id']];

			if ($this->config->get($this->m . '_cash')) {
				$params['isCashForbidden'] = 0;
			}

			if ($data) {
				$this->addLog(1, 'pvz', $params, count($data));

				$this->setCache('pvz' . $type[$code] . $this->getCacheName(), $this->delivery_city['city_id'], $data);
			} else {
				$this->addLog(0, 'pvz', $params, $data);

				return;
			}
		}

		return $data;
	}
	protected function numericСases($num, $word = [' раб. день', ' раб. дня', ' раб. дней']) {
		return $word[ ($num%100>4 && $num%100<20)? 2: [2, 0, 1, 1, 1, 2][min($num%10, 5)] ];
	}

	protected function upperFirst($str) {
		return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1, mb_strlen($str));
	}

	protected function getCacheName() {
		$cache_name = '.';

		$data = array_merge(
			[
				$this->config->get('config_store_id'),
				$this->config->get($this->m . '_test'),
				$this->config->get($this->m . '_round'),
				$this->config->get($this->m . '_cash'),
				$this->config->get($this->m . '_timeout'),
				$this->config->get($this->m . '_consider'),
				$this->config->get($this->m . '_title'),
				$this->config->get($this->m . '_sort_order'),
				$this->config->get($this->m . '_weight_class_id'),
				$this->config->get($this->m . '_length_class_id'),
				$this->config->get($this->m . '_default_type'),
				$this->config->get($this->m . '_default_weight'),
				$this->config->get($this->m . '_default_length'),
				$this->config->get($this->m . '_default_width'),
				$this->config->get($this->m . '_default_height'),
				$this->config->get($this->m . '_box_weight'),
				$this->config->get($this->m . '_box_length'),
				$this->config->get($this->m . '_box_width'),
				$this->config->get($this->m . '_box_height'),
				$this->config->get($this->m . '_calc_type'),
				$this->config->get($this->m . '_custom_sizes'),
				$this->config->get($this->m . '_tax_class_id'),
				$this->config->get($this->m . '_stops'),
				$this->config->get($this->m . '_costs'),
				$this->config->get($this->m . '_map_status'),
				$this->config->get($this->m . '_map_type'),
				$this->config->get($this->m . '_cap_status'),
				$this->config->get($this->m . '_cap_error'),
				$this->config->get($this->m . '_cap_title'),
				$this->config->get($this->m . '_cap_cost'),
				$this->params['fromPlaceId'],
				$this->params['packages'][0]['count'],
				$this->params['packages'][0]['price'],
				$this->params['packages'][0]['dimensions']['weight'],
				$this->params['packages'][0]['dimensions']['length'],
				$this->params['packages'][0]['dimensions']['height'],
				$this->params['packages'][0]['dimensions']['width'],
			]
		);

		foreach ($data as $item) {
			if (is_numeric($item)) {
				$cache_name .= $item . '.';
			} else {
				$cache_name .= (int)$item . '.';
			}
		}

		return $cache_name;
	}

	protected function getApiToken() {
		$data = $this->cache->get($this->m . '.token.' . base64_encode(''));

		if ($data && $data['expire'] > date('Y-m-d H:i:s')) {
			$token = $data['token'];
		} else {
			$params = [
				'grant_type'    => 'client_credentials',
				'client_id'     => $this->config->get($this->m . '_client_id'),
				'client_secret' => $this->config->get($this->m . '_client_secret'),
			];

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
			curl_setopt($ch, CURLOPT_URL, ($this->config->get($this->m . '_test') ? $this->api_auth_url_test : $this->api_auth_url));
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

			$result = json_decode(curl_exec($ch), true);

			curl_close($ch);

			if (isset($result['access_token'])) {
				$token = $result['access_token'];
				$date = new \DateTime('+' . $result['expires_in'] - 10 . ' seconds');
				$expire = $date->format('Y-m-d H:i:s');

				$this->addLog(1, 'token', $params, 'Успешное получение токена api');

				$this->cache->set($this->m . '.token.' . base64_encode(''), ['token' => $token, 'expire' => $expire]);
			} else {
				$this->addLog(0, 'token', $params, $result);

				return;
			}
		}

		return $token;
	}

	public function curl($params = []) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->getApiToken(), 'Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_URL, ($this->config->get($this->m . '_test') ? $this->api_url_test : $this->api_url));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

		$result['data'] = curl_exec($ch);
		$result['info'] = curl_getinfo($ch);

		curl_close($ch);

		return $result;
	}

	protected function getCache($method, $postfix = '') {
		if ($this->config->get($this->m . '_cache')) {
			return $this->cache->get($this->m . '.' . $method . '.' . base64_encode($postfix));
		}
	}

	protected function setCache($method, $postfix = '', $data) {
		if ($this->config->get($this->m . '_cache')) {
			$this->cache->set($this->m . '.' . $method . '.' . base64_encode($postfix), $data);
		}
	}

	protected function addLog($type, $method, $request, $response = []) {
		if ($this->config->get($this->m . '_logging')) {
			switch ($type) {
				case 0:
					$type = 'error';
					break;
				case 1:
					$type = 'success';
					break;
				case 2:
					$type = 'info';
					break;
			}

			$this->log->write('[' . $this->m . '][' . $type . '][' . $method . '][request:' . serialize($request) . '][response:' . serialize($response) . ']');
		}
	}
}
