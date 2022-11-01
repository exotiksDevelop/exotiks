<?php
class ModelShippingCdek extends Model {

	private $length_class_id = 1; // cm
	private $weight_class_id = 1; // kg
	private $api;

	function __construct($registry) {

		parent::__construct($registry);

		$this->length_class_id = $this->config->get('cdek_length_class_id');
		$this->weight_class_id = $this->config->get('cdek_weight_class_id');
	}

	function getQuote($address)
	{
		if(!isset($address['zone_id'])) {
			$address['zone_id'] = '';
		}
		if(!isset($address['country_id'])) {
			$address['country_id'] = '';
		}
		if(!isset($address['city'])) {
			$address['city'] = '';
		}
		if(!isset($address['zone'])) {
			$address['zone'] = '';
		}
		if(!isset($address['postcode'])) {
			$address['postcode'] = '';
		}
		if(!isset($address['address_1'])) {
			$address['address_1'] = '';
		}

		require_once DIR_SYSTEM . 'library/cdek_integrator/class.cdek_integrator.php';
		$this->api = new cdek_integrator($this->config->get('cdek_login'), $this->config->get('cdek_password'));

		if(isset($this->session->data['sdek'])) {
			unset($this->session->data['sdek']);
		}

		$this->load->language('shipping/cdek');

		$quote_data = array();

		$renderData = array();

		$extendedContent = '';

		$status = TRUE;

		if (!is_array($this->config->get('cdek_store')) || !in_array($this->config->get('config_store_id'), $this->config->get('cdek_store'))) {

			if ($this->config->get('cdek_log')) {
				$this->log->write('СДЭК: не выбран магазин!');
			}

			$status = FALSE;
		}

		if (!$this->currency->getId('RUB')) {

			if ($this->config->get('cdek_log')) {
				$this->log->write('СДЭК: в системе не найдена валюта "RUB"!');
			}

			$status = FALSE;
		}

		if (!$city_from = $this->config->get('cdek_city_from_id')) {

			if ($this->config->get('cdek_log')) {
				$this->log->write('СДЭК: не выбран город отправки!');
			}

			$status = FALSE;
		}

		if (empty($address['zone_id'])) {
			$address['zone_id'] = 0;
		}

		if (empty($address['country_id'])) {
			$address['country_id'] = 0;
		}

		$products = $this->cart->getProducts();

		$cdek_default_weight = $this->config->get('cdek_default_weight');

		$weight = 0;

		if ($cdek_default_weight['use']) {

			$default_weight = (float)$this->weight->convert($cdek_default_weight['value'], $this->weight_class_id, $this->config->get('config_weight_class_id'));

			switch ($cdek_default_weight['work_mode']) {
				case 'order':
					$weight = $default_weight;
					break;
				case 'all':
				case 'optional':

					foreach ($products as $product) {

						if ($cdek_default_weight['work_mode'] == 'all') {
							$weight += $default_weight;
						} else {

							if ((float)$product['weight'] > 0) {
								$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
							} else {
								$weight += $default_weight;
							}

						}

					}

					break;
			}

		} else {
			$weight = $this->cart->getWeight();
		}

		if ($this->config->get('config_weight_class_id') != $this->weight_class_id) {
			$weight = $this->weight->convert($weight, $this->config->get('config_weight_class_id'), $this->weight_class_id);
		}

		$packing_min_weight = $this->weight->convert((float)$this->config->get('cdek_packing_min_weight'), $this->config->get('cdek_packing_weight_class_id'), $this->weight_class_id);
		$packing_value = $this->weight->convert((float)$this->config->get('cdek_packing_value'), $this->config->get('cdek_packing_weight_class_id'), $this->weight_class_id);

		if ($packing_value) {

			$packing_weight = 0;

			switch ($this->config->get('cdek_packing_mode')) {
				case 'fixed':
					$packing_weight = $packing_value;
					break;
				case 'all_percent':
					$packing_weight = ($weight / 100) * $packing_value;
					break;
			}

			if ($packing_min_weight && $packing_min_weight > $packing_weight) {
				$packing_weight = $packing_min_weight;
			}

			if ($this->config->get('cdek_packing_prefix') == '+') {
				$weight += $packing_weight;
			} else {
				$weight -= (float)min($packing_weight, $weight);
			}

		} elseif ($packing_min_weight) {
			$weight += $packing_min_weight;
		}



		$min_weight = (float)$this->config->get('cdek_min_weight');
		$max_weight = (float)$this->config->get('cdek_max_weight');

		if ($status && (($min_weight > 0 && $weight < $min_weight) || ($max_weight > 0 && $weight > $max_weight))) {

			if ($this->config->get('cdek_log')) {
				$this->log->write('СДЭК: превышены ограничения по весу!');
			}

			$status = FALSE;
		}

		if ((float)$this->config->get('cdek_additional_weight')) {
			$weight += (float)$this->config->get('cdek_additional_weight');
		}

		if ($this->config->get('cdek_log')) {
			$this->log->write('СДЭК: вес заказа ' . $weight);
		}

		$total = $this->cart->getTotal();

		$min_total = (float)$this->config->get('cdek_min_total');
		$max_total = (float)$this->config->get('cdek_max_total');

		if ($status && (($min_total > 0 && $total < $min_total) || ($max_total > 0 && $total > $max_total))) {

			if ($this->config->get('cdek_log')) {
				$this->log->write('СДЭК: превышены ограничения по стоимости!');
			}

			$status = FALSE;
		}


		$countries = array();
		$empty_country = FALSE;

		if (empty($address['country_id'])) {
			$address['country_id'] = $this->config->get('config_country_id');
		}

		$to_data = $this->db->query("SELECT name FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$address['country_id'] . "' LIMIT 1")->row;

		if ($to_data) {
			$countries = $this->prepareCountry($to_data['name']);
		} else {
			$empty_country = TRUE;
		}

		$empty_zone = FALSE;

		if (empty($address['zone_id'])) {
			$empty_zone = TRUE;
		}

		/*if (empty($address['zone_id'])) {
			$empty_zone = TRUE;
		} elseif (in_array('россия', $countries)) {

			if (!$this->config->get('cdek_use_region_russia')) {
				$empty_zone = TRUE;
			}

		} else {

			if (!$this->config->get('cdek_use_region')) {
				$empty_zone = TRUE;
			}

		}*/

		if (!$empty_zone) {
			$to_data = $this->db->query("SELECT name FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$address['zone_id'] . "' LIMIT 1")->row;
		}

		if(!$address['city'] && $address['zone'])
		{
			$zoneNameParts = explode(' ', $address['zone']);
			$cdekZoneData = $this->db->query("SELECT cityName FROM `" . DB_PREFIX . "cdek_city` WHERE (`regionName` LIKE '%". $this->db->escape($zoneNameParts[0])."%' OR `cityName` LIKE '%". $this->db->escape($zoneNameParts[0])."%') AND `center` = '1' LIMIT 1")->row;
			if($cdekZoneData) {
				$address['city'] = $cdekZoneData['cityName'];
			}

		}

		if ($to_data && $address['city']) {

			$regions = array();

			if (!$empty_zone) {
				$regions = $this->prepareRegion($to_data['name']);
			}

			$cities = array($address['city']);
			$cities[] = preg_replace('|[^a-zа-яё]|isu', ' ', $address['city']);
			$cities[] = preg_replace('|[^a-zа-яё]|isu', '-', $address['city']);

			foreach ($cities as $city) {

				$cdek_cities = $this->getURL('https://api.cdek.ru/city/getListByTerm/json.php?q=' . urlencode($city) . '&name_startsWith=' . urlencode($city), new parser_json());

				if (is_array($cdek_cities) && isset($cdek_cities['geonames']) && count($cdek_cities['geonames'])) {


					$address['city'] = $city;

					break;
				}

			}

			if (is_array($cdek_cities) && isset($cdek_cities['geonames']) && count($cdek_cities['geonames'])) {

				$available = array();
				$address['city'] = $this->_clear($address['city']);

				$city_ignore = array();

				if ($this->config->get('cdek_city_ignore')) {

					$city_ignore = explode(', ', $this->config->get('cdek_city_ignore'));
					$city_ignore = array_map('trim', $city_ignore);
					$city_ignore = array_filter($city_ignore);
					$city_ignore = array_map(array($this, '_clear'), $city_ignore);

				}

				if (in_array($address['city'], $city_ignore)) {
					$status = FALSE;
				}

				if ($status) {

					foreach ($cdek_cities['geonames'] as $city_info) {

						if (!$empty_country && !in_array($this->_clear($city_info['countryName']), $countries)) {
							continue;
						}

						/*if (!$empty_zone) {

							list($region) = explode(' ', str_replace('обл.', '', trim($city_info['regionName'])));

							if (!in_array($this->_clear($region), $regions)) {
								continue;
							}
						}*/

						list($city)= explode(',', $city_info['name']);

						if (mb_strpos($this->_clear($city), $address['city']) === 0) {
							$available[] = $city_info;
						}

					}

				}

				if ($this->config->get('cdek_use_region_russia'))
				{
					$address['zone'] = $this->ocToCdek($address['zone']);
					$sql = "SELECT regionName FROM  `" . DB_PREFIX . "cdek_city` WHERE `regionName` = '".$this->db->escape($address['zone'])."' OR cityName = '".$this->db->escape($address['zone'])."' LIMIT 1";
					$zoneQuery = $this->db->query($sql);
					if($zoneQuery->num_rows)
					{
						$newAvaible = array();

						foreach ($available as $key => $available_value)
						{
							if(!isset($available_value['regionName']))
							{
								continue;
							}

							if($available_value['regionName'] == $zoneQuery->row['regionName'])
							{
								$newAvaible[] = $available_value;
							}
						}

						if(count($newAvaible)) {
							$available = $newAvaible;
						} else {
							if ($this->config->get('cdek_log')) {
								$this->log->write('СДЭК WARNING: не удалось отфильтровать города по региону!');
								$this->log->write('СДЭК WARNING: город: '.$address['city']);
								$this->log->write('СДЭК WARNING: регион: '.$address['zone']);
							}
						}
					}
					else
					{
						if ($this->config->get('cdek_log')) {
							$this->log->write('СДЭК WARNING: не найден регион '.$address['zone']);
						}
					}
				}

				if($this->config->get('cdek_use_postcode') && $address['postcode'])
				{
					$newAvaible = array();
					foreach ($available as $key => $available_value)
					{
						$postCodeArray = $available_value['postCodeArray'];
						if($postCodeArray && in_array($address['postcode'], $postCodeArray))
						{
							$newAvaible[] = $available_value;
						}
					}

					if(count($newAvaible)) {
						$available = $newAvaible;
					} else {
						if ($this->config->get('cdek_log')) {
							$this->log->write('СДЭК WARNING: не удалось отфильтровать города по почтовому коду!');
							$this->log->write('СДЭК WARNING: город: '.$address['city']);
							$this->log->write('СДЭК WARNING: индекс: '.$address['postcode']);
							$this->log->write('СДЭК WARNING: регион: '.$address['zone']);
						}
					}
				}

				if ($count = count($available)) {

					if ($count > 1) {

						$sort_order = array();

						foreach ($available as $key => $value) {
							$sort_order[$key] = (int)($this->_clear($value['name']) == $this->_clear($value['cityName']));
						}

						array_multisort($sort_order, SORT_DESC, $available);

						$available = array($available[0]);
					}

					$available_city = reset($available);

					$city_to = $available_city['id'];

					if ($this->config->get('cdek_log'))
					{
						$this->log->write('СДЭК INFO: город (покупатель): '.$address['city']);
						$this->log->write('СДЭК INFO: регион (покупатель): '.$address['zone']);

						$zoneQuerySql_debug = "SELECT * FROM  `" . DB_PREFIX . "cdek_city` WHERE `id` = '".$city_to."'";
						$zoneQuery_debug = $this->db->query($zoneQuerySql_debug);

						if($zoneQuery_debug->num_rows) {
							$this->log->write('СДЭК INFO: город (определен): '.$zoneQuery_debug->row['cityName'].', '.$zoneQuery_debug->row['regionName']);
						} else {
							$this->log->write('СДЭК INFO: город (определен): '.$city_to);
						}
					}

					if (!file_exists(DIR_APPLICATION . 'model' . DIRECTORY_SEPARATOR . 'shipping' . DIRECTORY_SEPARATOR . 'CalculatePriceDeliveryCdek.php')) {

						if ($this->config->get('cdek_log')) {
							$this->log->write('СДЭК: file CalculatePriceDeliveryCdek.php not found!');
						}

						return;
					}

					require_once DIR_APPLICATION . 'model' . DIRECTORY_SEPARATOR . 'shipping' . DIRECTORY_SEPARATOR . 'CalculatePriceDeliveryCdek.php';

					if (!class_exists('CalculatePriceDeliveryCdek')) {

						if ($this->config->get('cdek_log')) {
							$this->log->write('СДЭК: class CalculatePriceDeliveryCdek not found!');
						}

						return;
					}

					$calc = new CalculatePriceDeliveryCdek($this);

					$calc->setSenderCityId($city_from);
					$calc->setReceiverCityId($city_to);

					$day = (is_numeric($this->config->get('cdek_append_day'))) ? trim($this->config->get('cdek_append_day')) : 0;
					$date = date('Y-m-d', strtotime('+' . (float)$day . ' day'));
					$calc->setDateExecute($date);

					if ($this->config->get('cdek_login') != '' && $this->config->get('cdek_password') != '') {
						$calc->setAuth($this->config->get('cdek_login'), $this->config->get('cdek_password'));
					}

					$cdek_default_size = $this->config->get('cdek_default_size');

					$volume = 0;

					if ($cdek_default_size['use']) {

						$default_volume = 0;

						switch ($cdek_default_size['type']) {
							case 'volume':
								$default_volume = (float)$cdek_default_size['volume'];
								break;
							case 'size':
								$default_volume = $this->_getVolume(array($cdek_default_size['size_a'], $cdek_default_size['size_b'], $cdek_default_size['size_c']), $this->length_class_id);
								break;
						}

						switch ($cdek_default_size['work_mode']) {
							case 'order':
								$volume = $default_volume;
								break;
							case 'all':
							case 'optional':

								foreach ($products as $product) {

									$product_volume = 0;

									if ($cdek_default_size['work_mode'] == 'all') {
										$product_volume = $default_volume;
									} else {

										$product_volume = $this->_getVolume(array($product['length'], $product['width'], $product['height']), $product['length_class_id']);

										if (!$product_volume) {
											$product_volume = $default_volume;
										}

									}

									$volume += $product['quantity'] * (float)$product_volume;

								}

								break;
						}

					} else {

						foreach ($products as $product) {

							$product_volume = $this->_getVolume(array($product['length'], $product['width'], $product['height']), $product['length_class_id']);

							if (!$product_volume) {
								$product_volume = 0;
							}

							$volume += $product['quantity'] * $product_volume;
						}

					}

					if ($this->config->get('cdek_log')) {
						$this->log->write('СДЭК: объем ' . $volume);
					}

					if (!$volume) {

						if ($this->config->get('cdek_log')) {
							$this->log->write('СДЭК: не удалось рассчитать объем, возможно не заданы размеры товара или не установлено значение по умолчанию в настройках модуля!');
						}

						$status = FALSE;
					}

					if (!$weight) {

						if ($this->config->get('cdek_log')) {
							$this->log->write('СДЭК: не заполнен вес у товара!');
						}

						$status = FALSE;
					}

					if ($status) {

						$calc->addGoodsItemByVolume($weight, $volume);

						if (!$this->config->get('cdek_custmer_tariff_list')) {

							if ($this->config->get('cdek_log')) {
								$this->log->write('СДЭК: список тарифов пуст!');
							}

							$status = FALSE;
						}

						if ($status) {

							$geo_zones = array();

							$query = $this->db->query("SELECT DISTINCT geo_zone_id FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

							if ($query->num_rows) {
								foreach ($query->rows as $row) {
									$geo_zones[$row['geo_zone_id']] = $row['geo_zone_id'];
								}

							}

							if ($this->customer->isLogged()) {
								$customer_group_id = $this->customer->getGroupId();
							} else {
								$customer_group_id = $this->config->get('config_customer_group_id');
							}

							$cdek_tariff_list = $this->config->get('cdek_tariff_list');

							$results = $tariff_list = array();

							foreach ($this->config->get('cdek_custmer_tariff_list') as $key => $tariff_info) {

								if (empty($cdek_tariff_list[$tariff_info['tariff_id']])) continue;

								$tariff_title = !empty($tariff_info['title'][$this->config->get('config_language_id')]) ? $tariff_info['title'][$this->config->get('config_language_id')] : $cdek_tariff_list[$tariff_info['tariff_id']]['title'];

								if (!empty($tariff_info['customer_group_id']) && !in_array($customer_group_id, $tariff_info['customer_group_id'])) continue;

								$min_weight = (float)$tariff_info['min_weight'];
								$max_weight = (float)$tariff_info['max_weight'];

								if (($min_weight > 0 && $weight < $min_weight) || ($max_weight > 0 && $weight > $max_weight)) {

									if ($this->config->get('cdek_log')) {
										$this->log->write('СДЭК: Тариф «' . $tariff_title . '» превышены ограничения по весу!');
									}

									continue;
								}

								$min_total = (float)$tariff_info['min_total'];
								$max_total = (float)$tariff_info['max_total'];

								if (($min_total > 0 && $total < $min_total) || ($max_total > 0 && $total > $max_total)) {

									if ($this->config->get('cdek_log')) {
										$this->log->write('СДЭК: Тариф «' . $tariff_title . '» превышены ограничения по стоимости!');
									}

									continue;
								}

								if (!empty($tariff_info['geo_zone'])) {

									$intersect = array_intersect($tariff_info['geo_zone'], $geo_zones);

									if (!$intersect) {
										continue;
									}

								} else {
									$key = 'all';
								}

								$tariff_list[$tariff_info['tariff_id']][$key] = $tariff_info;
							}

							if (!$tariff_list) {

								if ($this->config->get('cdek_log')) {
									$this->log->write('СДЭК: Не сформирован список тарифов для текущей географической зоны!');
								}

								$status = FALSE;
							}

							if ($status) {

								foreach ($tariff_list as $tariff_id => &$items) {

									if (count($items) > 1) {

										if (array_key_exists('all', $items)) unset($items['all']);

										$sort_order = array();

										foreach ($items as $key => $item) {
											$sort_order[$key] = $item['sort_order'];
										}

										array_multisort($sort_order, SORT_ASC, $items);

										$items = reset($items);

									} elseif (count($items) == 1)  {
										$items = reset($items);
									} else {
										continue;
									}

									if ($this->config->get('cdek_work_mode') == 'single') {
										$calc->addTariffPriority($tariff_id, $items['sort_order']);
									}
								}

								if ($this->config->get('cdek_work_mode') == 'single') {

									$exTariffList = array();
									$exTariffList['dver'] = array();
									$exTariffList['sklad'] = array();

									foreach ($tariff_list as $tariff_info)
									{

										$calc->setTariffId($tariff_info['tariff_id']);

										if ($result = $this->getResult($calc, $total))
										{
											if($tariff_info['mode_id'] == 1 || $tariff_info['mode_id'] == 3)
												$exTariffList['dver'][] = $result;
											else
												$exTariffList['sklad'][] = $result;
										}

									}

									$set_dver = false;
									foreach ($exTariffList['dver'] as $mtfkey => $mtfvalue)
									{
										if(!$set_dver || (float)$mtfvalue['priceByCurrency'] < (float)$set_dver['priceByCurrency'])
											$set_dver = $mtfvalue;
									}

									$set_sklad = false;
									foreach ($exTariffList['sklad'] as $mtfkey => $mtfvalue)
									{
										if(!$set_sklad || (float)$mtfvalue['priceByCurrency'] < (float)$set_sklad['priceByCurrency'])
											$set_sklad = $mtfvalue;
									}

									if($set_dver)
										$results[] = $set_dver;
									if($set_sklad)
										$results[] = $set_sklad;

								} else {

									foreach ($tariff_list as $tariff_info) {

										$calc->setTariffId($tariff_info['tariff_id']);

										if ($result = $this->getResult($calc, $total)) {
											$results[] = $result;
										}

									}

								}

								if (!empty($results)) {

									$pvz_list = array();

									//$usePVZ = ($this->config->get('cdek_weight_limit') || $this->config->get('cdek_show_pvz'));
									$usePVZ = 1;

									if ($usePVZ) {
										$pvz_list = $this->getPVZList($city_to, $weight);
									}

									$exPvzList = '';
									foreach ($pvz_list as $pvz_list_content) {
										$exPvzList = json_encode($pvz_list_content['info']);
									}
									$extendedContent .= "<div style=\"display: none;\" id=\"sdek_pvzlist\">".$exPvzList."</div>";

									$exMap = '<div id="sdek_modal1" class="sdek_modal_div">
									<span class="sdek_modal_close"><a>X</a></span>
									<div id="sdek_map"></div>
									</div>
									<div id="sdek_overlay"></div>';
									$extendedContent .= $exMap;

									$extendedContent .= '<div id="cdek_data">';
									$extendedContent .= '<input type="hidden" name="sdek_pvz" id="sdek_pvz_input" value="">';
									$extendedContent .= '<input type="hidden" name="sdek_pvzinfo" id="sdek_pvzinfo_input" value="">';
									$extendedContent .= '<input type="hidden" name="sdek_city" id="sdek_city_input" value="'.$city_to.'">';
									$extendedContent .= '</div>';

									$sub_total = $this->cart->getSubTotal();

									foreach ($results as $shipping_info) {

										if (array_key_exists($shipping_info['tariffId'], $cdek_tariff_list)) {

											$tariff_info = $cdek_tariff_list[$shipping_info['tariffId']];

											if (!$this->config->get('cdek_empty_address') && trim($address['address_1']) == '' && in_array($tariff_info['mode_id'], array(1, 3))) {

												if ($this->config->get('cdek_log')) {
													$this->log->write('СДЭК: пустой адрес доставки для тарифа ' . $shipping_info['tariffId']);
												}

												continue;
											}

											$price = $shipping_price = ($this->config->get('config_currency') == 'RUB') ? $shipping_info['price'] : $this->currency->convert($shipping_info['price'], 'RUB', $this->config->get('config_currency'));

											$customer_tariff_info = $tariff_list[$shipping_info['tariffId']];

											$discounts = $this->getDiscount($sub_total, $shipping_info['tariffId'], $geo_zones);

											foreach ($discounts as $discount_info) {

												$markup = (float)$discount_info['value'];

												switch ($discount_info['mode']) {
													case 'percent':
														$markup = ($sub_total / 100) * $markup;
														break;
													case 'percent_shipping':
														$markup = ($shipping_price / 100) * $markup;
														break;
													case 'percent_cod':
														$markup = ($sub_total + $price / 100) * $markup;
														break;
												}

												$markup = $this->tax->calculate($markup, $discount_info['tax_class_id'], $this->config->get('config_tax'));

												if ($discount_info['prefix'] == '+') {
													$price += (float)$markup;
												} else {
													$price -= (float)min($markup, $price);
												}

											}

											if (!empty($customer_tariff_info['title'][$this->config->get('config_language_id')])) {
												$description = $customer_tariff_info['title'][$this->config->get('config_language_id')];
											} else {
												$description = $tariff_info['title'];
											}

											/*if ($this->config->get('cdek_period') || (!empty($pvz_list) && in_array($tariff_info['mode_id'], array(2, 4)))) {
												$description .= ':';
											}*/

											if(in_array((string)$shipping_info['tariffId'], $this->getInpostTariffs())) {
												$renderData['pvzType'] = 'POSTOMAT';
											} else {
												$renderData['pvzType'] = 'PVZ';
											}

											$renderData['period'] = '';

											if ($this->config->get('cdek_period')) {

												$period = array_unique(array($shipping_info['deliveryPeriodMin'], $shipping_info['deliveryPeriodMax']));

												if ((float)$this->config->get('cdek_more_days')) {
													foreach ($period as &$period_item) $period_item += (float)$this->config->get('cdek_more_days');
												}

												$renderData['period'] = ' Срок доставки ' . implode('–', $period) . ' ' . $this->declination(max($period), array('день', 'дня', 'дней')) . '.';

												//$description .= ' Срок доставки ' . implode('–', $period) . ' ' . $this->declination(max($period), array('день', 'дня', 'дней')) . '.';

											}


											$renderData['delivery_data'] = '';

											if ($this->config->get('cdek_delivery_data')) {

												$period = array_unique(array_map(array($this, '_normalizeDate'), array($shipping_info['deliveryDateMin'], $shipping_info['deliveryDateMax'])));

												if ((float)$this->config->get('cdek_more_days')) {
													foreach ($period as &$delivery_date) $delivery_date = date('Y.m.d', strtotime('+' . (int)$this->config->get('cdek_more_days') . ' day', strtotime(implode('.', array_reverse(explode('.', $delivery_date))))));
												}

												if (count($period) == 1) {
													$renderData['delivery_data'] = ' Планируемая дата доставки ' . $period[0] . '.';
													//$description .= ' Планируемая дата доставки ' . $period[0] . '.';
												} else {
													$renderData['delivery_data'] = ' Планируемая дата доставки с ' . $period[0] . ' по ' . $period[1] . '.';
													//$description .= ' Планируемая дата доставки с ' . $period[0] . ' по ' . $period[1] . '.';
												}
											}

											$names = array();

											$renderData['usePvz'] = false;

											if (in_array($tariff_info['mode_id'], array(2, 4))) {

												if ($usePVZ && !$pvz_list) continue;

												if($usePVZ && $pvz_list)
												{
													$renderData['usePvz'] = true;
												}

												if ($this->config->get('cdek_show_pvz')) {

													foreach ($pvz_list as $pvz_info) {
														//$names[$pvz_info['code']/* . '_' . $key*/] = $description . ' Пункт выдачи заказов: ' . $pvz_info['address'];
														$names[$pvz_info['code']/* . '_' . $key*/] = $description;
													}

												} else {
													$names[] = $description;
												}

											} else {
												$names[] = $description;
											}

											$cod = !isset($shipping_info['cashOnDelivery']) || ((float)$shipping_info['cashOnDelivery'] && $total >= (float)$shipping_info['cashOnDelivery']);

											foreach ($names as $key => $description) {

												$code = 'tariff_' . $shipping_info['tariffId'] . '_' . $key;
												$renderData['code'] = $code;

												$currency = '';
												if($this->session->data['currency']) {
													$currency = $this->session->data['currency'];
												}

												$extendedDescription = $this->renderTpl($renderData);

												$quote_data[$code] = array(
													'code'			=> 'cdek.' . $code,
													'cod'			=> $cod,
													'title'			=> $description,
													'description'		=> $extendedDescription,
													'cost'			=> $price,
													'tax_class_id'	=> $this->config->get('cdek_tax_class_id'),
													'text'			=> $this->currency->format($this->tax->calculate($price, $this->config->get('cdek_tax_class_id'), $this->config->get('config_tax')),$currency)
												);

											}

										}

									}

								} else {

									if ($this->config->get('cdek_log')) {
										$this->log->write('СДЭК: нет результатов для вывода!');
									}

								}
							}
						}
					}

				} else {

					if ($this->config->get('cdek_log')) {
						$this->log->write('СДЭК: не определен подходящий город!');
					}

				}

			} else {

				if ($this->config->get('cdek_log')) {
					$this->log->write('СДЭК: город доставки не определен!');
				}

			}

		} else {

			if ($this->config->get('cdek_log')) {
				$this->log->write('СДЭК: город доставки не найден!');
			}

		}

		$method_data = array();

		$empty_info = $this->config->get('cdek_empty');

		if (!$quote_data && !empty($empty_info['use'])) {

			if (!empty($empty_info['title'][$this->config->get('config_language_id')])) {
				$title = $empty_info['title'][$this->config->get('config_language_id')];
			} else {
				$title = $this->language->get('text_title');
			}

			if (!empty($empty_info['cost'])) {
				$price = (float)$empty_info['cost'];
			} else {
				$price = 0;
			}

			$quote_data['empty'] = array(
				'code'         => 'cdek.empty',
				'title'        => $title,
				'cost'         => $price,
				'tax_class_id' => $this->config->get('cdek_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($price, $this->config->get('cdek_tax_class_id'), $this->config->get('config_tax')))
			);

		}

		if ($quote_data) {

			$title_info = $this->config->get('cdek_title');

			if (!empty($title_info[$this->config->get('config_language_id')])) {
				$title = $title_info[$this->config->get('config_language_id')];
			} else {
				$title = $this->language->get('text_title');
			}

      		$method_data = array(
        		'code'       => 'cdek',
        		'title'      => $title,
        		'quote'      => $quote_data,
        		'more_data'      => $extendedContent,
				'sort_order' => (int)$this->config->get('cdek_sort_order'),
        		'error'      => false
      		);
		}

		return $method_data;
	}

	private function getInpostTariffs() {
		$list = $this->getInfo()->getInpostTariffs();

		return $list;
	}

	private function renderTpl($data)
	{
		require_once(DIR_APPLICATION."/controller/shipping/cdek.php");
		$controller = new ControllerShippingCdek($this->registry);
		$tpl = $controller->renderTpl($data);
		return $tpl;
	}

  	private function ocToCdek($region_name = ''){
		if ($region_name){
			$region_name = str_replace('область', 'обл.', $region_name);
			$region_name = str_replace('АО', 'авт. округ', $region_name);
			if (strpos($region_name, 'Республика') !== FALSE){
				$region_name =str_replace('Республика', '', $region_name);
				$region_name .= " респ.";
			}
		}

		return trim($region_name);
	}

	private function getResult(CalculatePriceDeliveryCdek &$calc, $total = 0) {

		$result = FALSE;

		if ($calc->calculate() === true) {

			$response = $calc->getResult();

			if (!$this->config->get('cdek_cache_on_delivery') || !array_key_exists('cashOnDelivery', $response['result']) || ($this->config->get('cdek_cache_on_delivery') && (float)$response['result']['cashOnDelivery'] && $total >= (float)$response['result']['cashOnDelivery'])) {
				$result = $response['result'];
			}

		} else {

			$error = $calc->getError();

			if (isset($error['error']) && !empty($error) && $this->config->get('cdek_log')) {
				foreach($error['error'] as $error_info) {
					$this->log->write('СДЭК: ' . $error_info['text']);
				}
			}

		}

		return $result;
	}

	private function getPVZList($city_id, $weight = 0) {

		$pvz_list = array();

		$pvz_list_data = $this->getURL('http://int.cdek.ru/pvzlist.php?cityid=' . $city_id.'&type=ALL', new parser_xml());

		if (isset($pvz_list_data->Pvz)) {

			$use_limit = $this->config->get('cdek_weight_limit');
			$full_info = array();
			foreach ($pvz_list_data->Pvz as $pvz_info)
			{
				if (empty($pvz_info['City']) || empty($pvz_info['Address'])) {
					continue;
				}

				$key = md5($pvz_info['Address']);

				if (array_key_exists($key, $pvz_list)) continue;

				if ($use_limit && isset($pvz_info->WeightLimit)) {

					$min_weight = (float)$pvz_info->WeightLimit['WeightMin'];
					$max_weight = (float)$pvz_info->WeightLimit['WeightMax'];

					if (($min_weight > 0 && $weight < $min_weight) || ($max_weight > 0 && $weight > $max_weight)) {

						if ($this->config->get('cdek_log')) {
							$this->log->write('СДЭК: превышены ограничения по весу для ПВЗ ' . $pvz_info['Name'] . ' по адресу: ' . $pvz_info['Address'] . '!');
						}

						continue;
					}

				}

				$pvz_address = 'г. ' . $pvz_info['City'] . ', ' . $this->mb_ucfirst($pvz_info['Address']) . '.';

				if (!empty($pvz_info['WorkTime']) && trim($pvz_info['WorkTime']) != '-') {
					$pvz_address .= ' Режим работы: ' . $pvz_info['WorkTime'] . '.';
				}

				if (!empty($pvz_info['Phone']) && trim($pvz_info['Phone']) != '-') {
					$pvz_address .= ' Телефон: ' . $pvz_info['Phone'] . '.';
				}

				$addarray['Code'] = ''.$pvz_info['Code'];
				$addarray['Name'] = ''.$pvz_info['Name'];
				$addarray['CityCode'] = ''.$pvz_info['CityCode'];
				$addarray['City'] = ''.$pvz_info['City'];
				$addarray['WorkTime'] = ''.$pvz_info['WorkTime'];
				$addarray['Address'] = ''.$pvz_info['Address'];
				$addarray['Phone'] = ''.$pvz_info['Phone'];
				$addarray['Note'] = ''.$pvz_info['Note'];
				$addarray['coordX'] = ''.$pvz_info['coordX'];
				$addarray['coordY'] = ''.$pvz_info['coordY'];
				$addarray['Type'] = ''.$pvz_info['Type'];
				$addarray['ownerCode'] = ''.$pvz_info['ownerCode'];

				$full_info[] = $addarray;

				$pvz_list[$key] = array(
					'address'	=> $pvz_address,
					'code'		=> (string)$pvz_info['Code'],
					'info'	=> $full_info
				);

				/*if ($this->config->get('cdek_pvz_more_one') == 'first') {
					break;
				}*/
			}

			/*if ($this->config->get('cdek_pvz_more_one') == 'merge') {

				$pvz_list_tmp = array();

				foreach ($pvz_list as $pvz_info) $pvz_list_tmp[] = $pvz_info['address'];

				$pvz_list = array(
					'MRG' => array(
						'address'	=> implode('; ', $pvz_list_tmp),
						'code'		=> 'MRG',
						'info'		=> $full_info
					)
				);
			}*/
			$pvz_list_tmp = array();

			foreach ($pvz_list as $pvz_info) $pvz_list_tmp[] = $pvz_info['address'];

			$pvz_list = array(
				'MRG' => array(
					'address'	=> implode('; ', $pvz_list_tmp),
					'code'		=> 'MRG',
					'info'		=> $full_info
				)
			);

		} elseif (!empty($pvz_list_data['ErrorCode'])) {

			if ($this->config->get('cdek_log')) {
				$this->log->write('СДЭК: В выбранном городе ПВЗ отсутствуют!');
			}

		}

		return $pvz_list;
	}

	private function getDiscount($total, $tariff_Id = 0, $geo_zones = array()) {

		$discounts = array();

		$cdek_discounts = $this->config->get('cdek_discounts');

		if (!empty($cdek_discounts)) {

			if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getGroupId();
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			foreach ($cdek_discounts as $key => $discount_info) {

				$item_status = TRUE;

				if ((!empty($discount_info['customer_group_id']) && !in_array($customer_group_id, $discount_info['customer_group_id']))) {
					$item_status = FALSE;
				}

				if (!empty($discount_info['geo_zone'])) {

					$intersect = array_intersect($discount_info['geo_zone'], $geo_zones);

					if (!$intersect) {
						$item_status = FALSE;
					}

				}

				if (!isset($discount_info['tariff_id'])) $discount_info['tariff_id'] = array();

				if ($item_status && (float)$discount_info['total'] <= $total && (!$discount_info['tariff_id'] || in_array($tariff_Id, $discount_info['tariff_id']))) {
					$discounts[$discount_info['prefix'] . '_' . $discount_info['mode']] = $discount_info;
				}

			}

		}

		return $discounts;
	}

	private function prepareRegion($name = '') {
		$regions = array();

		$parts = explode(' ', $name);
		$parts = array_map(array($this, '_clear'), $parts);

		if (in_array($parts[0], array('московская', 'москва'))) {
			$regions[] = 'москва';
			$regions[] = 'московская';
		} elseif (in_array($parts[0], array('ленинградская', 'санкт-петербург'))) {
			$regions[] = 'санкт-петербург';
			$regions[] = 'ленинградская';
		} elseif (mb_strpos($parts[0], 'респ') === 0) {
			$regions[] = $parts[1];
		} elseif (in_array($parts[0], array('киев', 'киевская'))) { // Украина
			$regions[] = 'киевская';
			$regions[] = 'киев';
		} elseif (in_array($parts[0], array('винница', 'винницкая'))) { // Украина
			$regions[] = 'винница';
			$regions[] = 'винницкая';
		} elseif (in_array($parts[0], array('днепропетровск', 'днепропетровская'))) { // Украина
			$regions[] = 'днепропетровск';
			$regions[] = 'днепропетровская';
		} elseif (in_array($parts[0], array('чувашская'))) {
			$regions[] = 'чувашия';
		} elseif (in_array($parts[0], array('удмуртская'))) {
			$regions[] = 'удмуртия';
		} else {
			$regions = $parts;
		}

		return $regions;
	}

	private function prepareCountry($name = '') {

		$countries = array();

		$name = $this->_clear($name);

		if (in_array($name, array('российская федерация', 'россия', 'russia', 'russian', 'russian federation'))) {
			$countries[] = 'россия';
		} elseif(in_array($name, array('украина', 'ukraine'))) {
			$countries[] = 'украина';
		} elseif(in_array($name, array('белоруссия', 'белоруссия (беларусь)', 'беларусь', '(беларусь)', 'belarus'))) {
			$countries[] = 'белоруссия (беларусь)';
		} elseif(in_array($name, array('казахстан', 'kazakhstan'))) {
			$countries[] = 'казахстан';
		} elseif(in_array($name, array('сша', 'соединенные штаты америки', 'соединенные штаты', 'usa', 'united states'))) {
			$countries[] = 'сша';
		} elseif(in_array($name, array('aзербайджан', 'azerbaijan'))) {
			$countries[] = 'aзербайджан';
		} elseif(in_array($name, array('узбекистан', 'uzbekistan'))) {
			$countries[] = 'узбекистан';
		} elseif(in_array($name, array('китайская народная республика', 'сhina'))) {
			$countries[] = 'китай (кнр)';
		} else {
			$countries[] = $name;
		}

		return $countries;
	}

	private function _normalizeDate($value = '') {
		return str_replace('-', '.', $value);
	}

	private function _clear($value) {
		$value = mb_convert_case($value, MB_CASE_LOWER, "UTF-8");
		return trim($value);
	}

	private function declination($number, $titles) {
		$cases = array (2, 0, 1, 1, 1, 2);
		return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
	}

	private function _getVolume($data, $length_class_id) {

		if ($length_class_id != $this->length_class_id) {
			array_walk($data, array($this, '_convertItem'), $length_class_id);
		}

		$p = 1;

		foreach ($data as $value) {
			$p *= (float)$value;
		}

		return $p / 1000000;
	}

	private function _convertItem(&$value, $key, $length_class_id) {
		$value = $this->length->convert($value, $length_class_id, $this->length_class_id);
	}

	private function getURL($url, response_parser $parser, $data = array()) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 3000);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 3000);

		if (!empty($data)) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}

		$out = curl_exec($ch);

		$parser->setData($out);

		return $parser->getData();
	}

	private function mb_ucfirst($str, $enc = 'utf-8') {
		return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc) . mb_substr($str, 1, mb_strlen($str, $enc), $enc);
	}

	public function getInfo() {

		static $instance;

		if (!$instance) {
			$instance = $this->api->loadComponent('info');
		}

		return $instance;
	}

}

abstract class response_parser {

	protected $data;

	public function setData($data) {
		$this->data = $data;
	}

	abstract protected function getData();
}

class parser_json extends response_parser {

	public function getData() {
		return json_decode($this->data, TRUE);
	}

}

class parser_xml extends response_parser {

	public function getData() {
		return (strpos($this->data, '<?xml') === 0) ? new SimpleXMLElement($this->data) : '';
	}

}
?>