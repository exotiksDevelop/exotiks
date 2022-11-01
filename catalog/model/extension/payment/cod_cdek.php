<?php
class ModelExtensionPaymentCodCdek extends Model {

	private $api;

  	public function getMethod($address, $total) {
 		require_once DIR_SYSTEM . 'library/cdek_integrator/class.cdek_integrator.php';
		$this->api = new cdek_integrator($this->config->get('shipping_cdek_login'), $this->config->get('shipping_cdek_password'));


		$method_data = array();

		if (!empty($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'cod_cdek') {
			unset($this->session->data['payment_method']['code']);
		}

		if (!is_array($this->config->get('payment_cod_cdek_store')) || !in_array($this->config->get('config_store_id'), $this->config->get('payment_cod_cdek_store'))) {
			return $method_data;
		}

		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		if ($this->config->get('payment_cod_cdek_customer_group_id') && !in_array($customer_group_id, $this->config->get('payment_cod_cdek_customer_group_id'))) {
			return $method_data;
		}

		if (!empty($address['city'])) {
			$city = $this->clearText($address['city']);
		} else {
			$city = '';
		}

		$city_ignore = array();

		if ($this->config->get('payment_cod_cdek_city_ignore')) {

			$city_ignore = explode(', ', $this->config->get('payment_cod_cdek_city_ignore'));
			$city_ignore = array_map('trim', $city_ignore);
			$city_ignore = array_filter($city_ignore);
			$city_ignore = array_map(array($this, 'clearText'), $city_ignore);

		}



		if (in_array($city, $city_ignore)) {
			return $method_data;
		}

		if ($this->config->get('payment_cod_cdek_geo_zone_id')) {

			$cod_cdek_geo_zone_id = $this->config->get('payment_cod_cdek_geo_zone_id');

			if (!is_array($cod_cdek_geo_zone_id)) {
				$cod_cdek_geo_zone_id = array($cod_cdek_geo_zone_id);
			}

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id IN(" . implode(',', $cod_cdek_geo_zone_id) . ") AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if (!$query->num_rows) {
				return $method_data;
			}

		}

		$total = $this->cart->getTotal();

		$min_total = (float)$this->config->get('payment_cod_cdek_min_total');
		$max_total = (float)$this->config->get('payment_cod_cdek_max_total');


		if (($min_total > 0 && $total < $min_total) || ($max_total > 0 && $total > $max_total)) {
			return $method_data;
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			list($shipping_method, $code) = explode('.', $this->session->data['shipping_method']['code']);
		} else {
			$shipping_method = '';
		}

		// Ограничение наложки только для СДЭКа
		if ($this->config->get('payment_cod_cdek_cache_on_delivery') && $shipping_method == 'cdek') {

			if (empty($this->session->data['shipping_method']['cod'])) {
				return $method_data;
			}

		}

		// Ограничения наложенного платежа для постоматов
        if (!empty($this->session->data['shipping_method']['code'])){
            $codeParts = explode('.', $this->session->data['shipping_method']['code']);
            if(isset($codeParts[1])) {
                $tariffParts = explode('_', $codeParts[1]);
                if(isset($tariffParts[1])) {
                    $tariffId = (int)$tariffParts[1];
                    if(in_array((string)$tariffId, $this->getInpostTariffs())) {
                        return $method_data;
                    }
                }

            }
        }


		if ($this->config->get('payment_cod_cdek_mode') == 'cdek') {

			if ($shipping_method != 'cdek') {
				return $method_data;
			}

			if ($this->config->get('payment_cod_cdek_mode_cdek') != 'all') {

				$status = FALSE;

				$tariff_parts = explode('_', $code);

				if (count($tariff_parts) == 3) {

					list(,$tariff_id) = $tariff_parts;

					$tariff_info = $this->getTariffInfo($tariff_id);

					if ($tariff_info) {

						switch ($this->config->get('payment_cod_cdek_mode_cdek')) {
							case 'courier':

								if (in_array($tariff_info['mode_id'], array(1, 3))) {
									$status = TRUE;
								}

								break;
							case 'pvz':

								if (in_array($tariff_info['mode_id'], array(2, 4))) {
									$status = TRUE;
								}

								break;

						}

					}

				}

				if (!$status) {
					return $method_data;
				}

			}
		}

		if ($this->config->get('payment_cod_cdek_active')) {
			$this->session->data['payment_method']['code'] = 'cod_cdek';
		}

		$title_info = $this->config->get('payment_cod_cdek_title');

		if (!empty($title_info[$this->config->get('config_language_id')])) {
			$title = $title_info[$this->config->get('config_language_id')];
		} else {
			$this->load->language('extension/payment/cod_cdek');
			$title = $this->language->get('text_title');
		}

		$method_data = array(
			'code'       => 'cod_cdek',
			'title'      => $title,
			'terms'      => '',
			'sort_order' => $this->config->get('payment_cod_cdek_sort_order')
		);

    	return $method_data;
  	}

	private function clearText($value) {
		return trim(mb_convert_case($value, MB_CASE_LOWER, "UTF-8"));
	}

	private function getTariffInfo($tariff_id) {

		$all = $this->getTariffList();

		return array_key_exists($tariff_id, $all) ? $all[$tariff_id] : FALSE;
	}

	private function getTariffList() {
		$tariffList = $this->getInfo()->getTariffList();
		return $tariffList;
	}

	private function getInpostTariffs() {
		$list = $this->getInfo()->getInpostTariffs();

		return $list;
	}

	private function getInfo() {

		static $instance;

		if (!$instance) {
			$instance = $this->api->loadComponent('info');
		}

		return $instance;
	}
}
?>