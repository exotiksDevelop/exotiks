<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
class ControllerShippingLLOzon extends Controller {
	private $m = 'll_ozon';
	private $version = '1.5.4';
	private $email = 'support@lutylab.ru';
	private $site = 'https://lutylab.ru';
	private $module_docs = 'https://docs.lutylab.ru/ll_ozon';
	private $delivery = 'https://rocket.ozon.ru';
	private $api_docs = 'https://docs.ozon.ru/api/rocket';
	private $variants = [
		['code' => 'pickpoint', 'name' => 'Самовывоз'],
		['code' => 'postamat', 'name' => 'Постамат'],
		['code' => 'courier', 'name' => 'Курьер'],
	];
	private $variants_map = ['pickpoint', 'postamat'];
	private $controls = [
		'geolocationControl',
		'searchControl',
		'routeButtonControl',
		'trafficControl',
		'typeSelector',
		'fullscreenControl',
		'zoomControl',
		'rulerControl',
	];
	private $error = [];

	public function index() {
		$this->load->language('shipping/' . $this->m);

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post[$this->m . '_license']) && isset($this->request->server['HTTP_HOST']) && base64_encode(hash_hmac('sha256',ltrim($this->request->server['HTTP_HOST'], 'www.').$this->m,'3.1415926535898',true)) == $this->request->post[$this->m . '_license']) {
				$this->load->model('setting/setting');

				$this->model_setting_setting->editSetting($this->m, $this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');

				$this->response->redirect($this->url->link('shipping/' . $this->m, 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->load->model('extension/extension');

				$this->model_extension_extension->uninstall('shipping', $this->m);

				$this->session->data['warning'] = $this->language->get('error_license');

				$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$this->load->model('shipping/' . $this->m);
		$this->load->model('localisation/weight_class');
		$this->load->model('localisation/length_class');
		$this->load->model('localisation/tax_class');
		$this->load->model('localisation/geo_zone');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_license'] = $this->language->get('heading_license');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_shipping'] = $this->language->get('button_shipping');
		$data['button_exchange'] = $this->language->get('button_exchange');
		$data['button_order'] = $this->language->get('button_order');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_update'] = $this->language->get('button_update');
		$data['tab_api'] = $this->language->get('tab_api');
		$data['tab_log'] = $this->language->get('tab_log');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_delivery'] = $this->language->get('tab_delivery');
		$data['tab_stop'] = $this->language->get('tab_stop');
		$data['tab_cost'] = $this->language->get('tab_cost');
		$data['tab_map'] = $this->language->get('tab_map');
		$data['tab_cap'] = $this->language->get('tab_cap');
		$data['tab_support'] = $this->language->get('tab_support');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_developer'] = $this->language->get('text_developer');
		$data['text_site'] = $this->language->get('text_site');
		$data['text_module_docs'] = $this->language->get('text_module_docs');
		$data['text_delivery'] = $this->language->get('text_delivery');
		$data['text_api_docs'] = $this->language->get('text_api_docs');
		$data['text_kg'] = $this->language->get('text_kg');
		$data['text_sm'] = $this->language->get('text_sm');
		$data['text_rub'] = $this->language->get('text_rub');
		$data['text_dni'] = $this->language->get('text_dni');
		$data['text_product_one'] = $this->language->get('text_product_one');
		$data['text_product_all'] = $this->language->get('text_product_all');
		$data['text_width'] = $this->language->get('text_width');
		$data['text_length'] = $this->language->get('text_length');
		$data['text_height'] = $this->language->get('text_height');
		$data['text_map_overall'] = $this->language->get('text_map_overall');
		$data['text_map_individual'] = $this->language->get('text_map_individual');
		$data['text_total_countries'] = $this->language->get('text_total_countries');
		$data['text_total_regions'] = $this->language->get('text_total_regions');
		$data['text_total_cities'] = $this->language->get('text_total_cities');
		$data['text_total_pvzs'] = $this->language->get('text_total_pvzs');
		$data['text_total_places'] = $this->language->get('text_total_places');
		$data['text_total_pickups'] = $this->language->get('text_total_pickups');
		$data['text_select_country'] = $this->language->get('text_select_country');
		$data['text_select_place'] = $this->language->get('text_select_place');
		$data['text_no_data'] = $this->language->get('text_no_data');
		$data['text_region_shipping'] = $this->language->get('text_region_shipping');
		$data['text_region_opencart'] = $this->language->get('text_region_opencart');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_from'] = $this->language->get('text_from');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_only_from'] = $this->language->get('text_only_from');
		$data['text_only_exclude'] = $this->language->get('text_only_exclude');
		$data['text_percent_order'] = $this->language->get('text_percent_order');
		$data['text_percent_product'] = $this->language->get('text_percent_product');
		$data['text_percent_shipping'] = $this->language->get('text_percent_shipping');
		$data['text_dostavka'] = $this->language->get('text_dostavka');
		$data['text_total'] = $this->language->get('text_total');
		$data['column_variant'] = $this->language->get('column_variant');
		$data['column_weight'] = $this->language->get('column_weight');
		$data['column_cost_order'] = $this->language->get('column_cost_order');
		$data['column_cost'] = $this->language->get('column_cost');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_geo_zone'] = $this->language->get('column_geo_zone');
		$data['column_city'] = $this->language->get('column_city');
		$data['column_mod'] = $this->language->get('column_mod');
		$data['column_position'] = $this->language->get('column_position');
		$data['entry_client_id'] = $this->language->get('entry_client_id');
		$data['entry_client_secret'] = $this->language->get('entry_client_secret');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_round'] = $this->language->get('entry_round');
		$data['entry_cash'] = $this->language->get('entry_cash');
		$data['entry_cache'] = $this->language->get('entry_cache');
		$data['entry_timeout'] = $this->language->get('entry_timeout');
		$data['entry_logging'] = $this->language->get('entry_logging');
		$data['entry_data'] = $this->language->get('entry_data');
		$data['entry_pickup_cities'] = $this->language->get('entry_pickup_cities');
		$data['entry_consider'] = $this->language->get('entry_consider');
		$data['entry_matching'] = $this->language->get('entry_matching');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_default_type'] = $this->language->get('entry_default_type');
		$data['entry_default_weight'] = $this->language->get('entry_default_weight');
		$data['entry_default_dimension'] = $this->language->get('entry_default_dimension');
		$data['entry_default_length'] = $this->language->get('entry_default_length');
		$data['entry_default_width'] = $this->language->get('entry_default_width');
		$data['entry_default_height'] = $this->language->get('entry_default_height');
		$data['entry_box_weight'] = $this->language->get('entry_box_weight');
		$data['entry_box_dimension'] = $this->language->get('entry_box_dimension');
		$data['entry_calc_type'] = $this->language->get('entry_calc_type');
		$data['entry_custom_sizes'] = $this->language->get('entry_custom_sizes');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_code'] = $this->language->get('entry_code');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_add_day'] = $this->language->get('entry_add_day');
		$data['entry_list'] = $this->language->get('entry_list');
		$data['entry_map_key'] = $this->language->get('entry_map_key');
		$data['entry_map_type'] = $this->language->get('entry_map_type');
		$data['entry_map_controls'] = $this->language->get('entry_map_controls');
		$data['entry_map_button'] = $this->language->get('entry_map_button');
		$data['entry_cap_error'] = $this->language->get('entry_cap_error');
		$data['entry_cap_cost'] = $this->language->get('entry_cap_cost');
		$data['entry_license'] = $this->language->get('entry_license');

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/' . $this->m, 'token=' . $this->session->data['token'], 'SSL'),
		];

		$data['action'] = $this->url->link('shipping/' . $this->m, 'token=' . $this->session->data['token'], 'SSL');
		$data['exchange'] = $this->url->link('module/' . $this->m . '_exchange', 'token=' . $this->session->data['token'], 'SSL');
		$data['order'] = $this->url->link('module/' . $this->m . '_exchange/order', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post[$this->m . '_client_id'])) {
			$data[$this->m . '_client_id'] = $this->request->post[$this->m . '_client_id'];
		} else {
			$data[$this->m . '_client_id'] = $this->config->get($this->m . '_client_id');
		}

		if (isset($this->request->post[$this->m . '_client_secret'])) {
			$data[$this->m . '_client_secret'] = $this->request->post[$this->m . '_client_secret'];
		} else {
			$data[$this->m . '_client_secret'] = $this->config->get($this->m . '_client_secret');
		}

		if (isset($this->request->post[$this->m . '_test'])) {
			$data[$this->m . '_test'] = $this->request->post[$this->m . '_test'];
		} else {
			$data[$this->m . '_test'] = $this->config->get($this->m . '_test');
		}

		if (isset($this->request->post[$this->m . '_round'])) {
			$data[$this->m . '_round'] = $this->request->post[$this->m . '_round'];
		} else {
			$data[$this->m . '_round'] = $this->config->get($this->m . '_round');
		}

		if (isset($this->request->post[$this->m . '_cash'])) {
			$data[$this->m . '_cash'] = $this->request->post[$this->m . '_cash'];
		} else {
			$data[$this->m . '_cash'] = $this->config->get($this->m . '_cash');
		}

		if (isset($this->request->post[$this->m . '_cache'])) {
			$data[$this->m . '_cache'] = $this->request->post[$this->m . '_cache'];
		} else {
			$data[$this->m . '_cache'] = $this->config->get($this->m . '_cache');
		}

		if (isset($this->request->post[$this->m . '_timeout'])) {
			$data[$this->m . '_timeout'] = $this->request->post[$this->m . '_timeout'];
		} elseif ($this->config->has($this->m . '_timeout') && is_numeric($this->config->get($this->m . '_timeout'))) {
			$data[$this->m . '_timeout'] = $this->config->get($this->m . '_timeout');
		} else {
			$data[$this->m . '_timeout'] = 10;
		}

		if (isset($this->request->post[$this->m . '_logging'])) {
			$data[$this->m . '_logging'] = $this->request->post[$this->m . '_logging'];
		} else {
			$data[$this->m . '_logging'] = $this->config->get($this->m . '_logging');
		}

		if (isset($this->request->post[$this->m . '_pickup_cities'])) {
			$data[$this->m . '_pickup_cities'] = $this->request->post[$this->m . '_pickup_cities'];
		} elseif ($this->config->has($this->m . '_pickup_cities')) {
			$data[$this->m . '_pickup_cities'] = $this->config->get($this->m . '_pickup_cities');
		} else {
			$data[$this->m . '_pickup_cities'] = [];
		}

		if (isset($this->request->post[$this->m . '_consider'])) {
			$data[$this->m . '_consider'] = $this->request->post[$this->m . '_consider'];
		} else {
			$data[$this->m . '_consider'] = $this->config->get($this->m . '_consider');
		}

		if (isset($this->request->post[$this->m . '_title'])) {
			$data[$this->m . '_title'] = $this->request->post[$this->m . '_title'];
		} elseif ($this->config->has($this->m . '_title')) {
			$data[$this->m . '_title'] = $this->config->get($this->m . '_title');
		} else {
			$data[$this->m . '_title'] = 'Ozon Rocket';
		}

		if (isset($this->request->post[$this->m . '_sort_order'])) {
			$data[$this->m . '_sort_order'] = $this->request->post[$this->m . '_sort_order'];
		} else {
			$data[$this->m . '_sort_order'] = $this->config->get($this->m . '_sort_order');
		}

		if (isset($this->request->post[$this->m . '_weight_class_id'])) {
			$data[$this->m . '_weight_class_id'] = $this->request->post[$this->m . '_weight_class_id'];
		} else {
			$data[$this->m . '_weight_class_id'] = $this->config->get($this->m . '_weight_class_id');
		}

		if (isset($this->request->post[$this->m . '_length_class_id'])) {
			$data[$this->m . '_length_class_id'] = $this->request->post[$this->m . '_length_class_id'];
		} else {
			$data[$this->m . '_length_class_id'] = $this->config->get($this->m . '_length_class_id');
		}

		if (isset($this->request->post[$this->m . '_default_type'])) {
			$data[$this->m . '_default_type'] = $this->request->post[$this->m . '_default_type'];
		} else {
			$data[$this->m . '_default_type'] = $this->config->get($this->m . '_default_type');
		}

		if (isset($this->request->post[$this->m . '_default_weight'])) {
			$data[$this->m . '_default_weight'] = $this->request->post[$this->m . '_default_weight'];
		} elseif ($this->config->has($this->m . '_default_weight')) {
			$data[$this->m . '_default_weight'] = $this->config->get($this->m . '_default_weight');
		} else {
			$data[$this->m . '_default_weight'] = 1;
		}

		if (isset($this->request->post[$this->m . '_default_length'])) {
			$data[$this->m . '_default_length'] = $this->request->post[$this->m . '_default_length'];
		} elseif ($this->config->has($this->m . '_default_length')) {
			$data[$this->m . '_default_length'] = $this->config->get($this->m . '_default_length');
		} else {
			$data[$this->m . '_default_length'] = 10;
		}

		if (isset($this->request->post[$this->m . '_default_width'])) {
			$data[$this->m . '_default_width'] = $this->request->post[$this->m . '_default_width'];
		} elseif ($this->config->has($this->m . '_default_width')) {
			$data[$this->m . '_default_width'] = $this->config->get($this->m . '_default_width');
		} else {
			$data[$this->m . '_default_width'] = 10;
		}

		if (isset($this->request->post[$this->m . '_default_height'])) {
			$data[$this->m . '_default_height'] = $this->request->post[$this->m . '_default_height'];
		} elseif ($this->config->has($this->m . '_default_height')) {
			$data[$this->m . '_default_height'] = $this->config->get($this->m . '_default_height');
		} else {
			$data[$this->m . '_default_height'] = 10;
		}

		if (isset($this->request->post[$this->m . '_box_weight'])) {
			$data[$this->m . '_box_weight'] = $this->request->post[$this->m . '_box_weight'];
		} else {
			$data[$this->m . '_box_weight'] = $this->config->get($this->m . '_box_weight');
		}

		if (isset($this->request->post[$this->m . '_box_length'])) {
			$data[$this->m . '_box_length'] = $this->request->post[$this->m . '_box_length'];
		} else {
			$data[$this->m . '_box_length'] = $this->config->get($this->m . '_box_length');
		}

		if (isset($this->request->post[$this->m . '_box_width'])) {
			$data[$this->m . '_box_width'] = $this->request->post[$this->m . '_box_width'];
		} else {
			$data[$this->m . '_box_width'] = $this->config->get($this->m . '_box_width');
		}

		if (isset($this->request->post[$this->m . '_box_height'])) {
			$data[$this->m . '_box_height'] = $this->request->post[$this->m . '_box_height'];
		} else {
			$data[$this->m . '_box_height'] = $this->config->get($this->m . '_box_height');
		}

		if (isset($this->request->post[$this->m . '_calc_type'])) {
			$data[$this->m . '_calc_type'] = $this->request->post[$this->m . '_calc_type'];
		} else {
			$data[$this->m . '_calc_type'] =$this->config->get($this->m . '_calc_type');
		}

		if (isset($this->request->post[$this->m . '_custom_sizes'])) {
			$data[$this->m . '_custom_sizes'] = $this->request->post[$this->m . '_custom_sizes'];
		} else {
			$data[$this->m . '_custom_sizes'] = $this->config->get($this->m . '_custom_sizes');
		}

		if (isset($this->request->post[$this->m . '_tax_class_id'])) {
			$data[$this->m . '_tax_class_id'] = $this->request->post[$this->m . '_tax_class_id'];
		} else {
			$data[$this->m . '_tax_class_id'] = $this->config->get($this->m . '_tax_class_id');
		}

		if (isset($this->request->post[$this->m . '_status'])) {
			$data[$this->m . '_status'] = $this->request->post[$this->m . '_status'];
		} else {
			$data[$this->m . '_status'] = $this->config->get($this->m . '_status');
		}

		foreach ($this->variants as $key => $variant) {
			if (isset($this->request->post[$this->m . '_quote_title_' . $variant['code']])) {
				$data[$this->m . '_quote_title_' . $variant['code']] = $this->request->post[$this->m . '_quote_title_' . $variant['code']];
			} elseif ($this->config->has($this->m . '_quote_title_' . $variant['code'])) {
				$data[$this->m . '_quote_title_' . $variant['code']] = $this->config->get($this->m . '_quote_title_' . $variant['code']);
			} else {
				if (in_array($variant['code'], $this->variants_map)) {
					$data[$this->m . '_quote_title_' . $variant['code']] = '{{logo}} ' . $variant['name'] . ': {{address}} ({{days}})';
				} else {
					$data[$this->m . '_quote_title_' . $variant['code']] = '{{logo}} ' . $variant['name'] . ' ({{days}})';
				}
			}

			if (isset($this->request->post[$this->m . '_quote_description_' . $variant['code']])) {
				$data[$this->m . '_quote_description_' . $variant['code']] = $this->request->post[$this->m . '_quote_description_' . $variant['code']];
			} elseif ($this->config->has($this->m . '_quote_description_' . $variant['code'])) {
				$data[$this->m . '_quote_description_' . $variant['code']] = $this->config->get($this->m . '_quote_description_' . $variant['code']);
			} else {
				$data[$this->m . '_quote_description_' . $variant['code']] = '';
			}

			if (isset($this->request->post[$this->m . '_add_day_' . $variant['code']])) {
				$data[$this->m . '_add_day_' . $variant['code']] = $this->request->post[$this->m . '_add_day_' . $variant['code']];
			} else {
				$data[$this->m . '_add_day_' . $variant['code']] = $this->config->get($this->m . '_add_day_' . $variant['code']);
			}

			if (isset($this->request->post[$this->m . '_sort_order_' . $variant['code']])) {
				$data[$this->m . '_sort_order_' . $variant['code']] = $this->request->post[$this->m . '_sort_order_' . $variant['code']];
			} else {
				$data[$this->m . '_sort_order_' . $variant['code']] = $this->config->get($this->m . '_sort_order_' . $variant['code']);
			}

			if (isset($this->request->post[$this->m . '_list_' . $variant['code']])) {
				$data[$this->m . '_list_' . $variant['code']] = $this->request->post[$this->m . '_list_' . $variant['code']];
			} else {
				$data[$this->m . '_list_' . $variant['code']] = $this->config->get($this->m . '_list_' . $variant['code']);
			}

			if (isset($this->request->post[$this->m . '_status_' . $variant['code']])) {
				$data[$this->m . '_status_' . $variant['code']] = $this->request->post[$this->m . '_status_' . $variant['code']];
			} else {
				$data[$this->m . '_status_' . $variant['code']] = $this->config->get($this->m . '_status_' . $variant['code']);
			}
		}

		if (isset($this->request->post[$this->m . '_stops'])) {
			$data[$this->m . '_stops'] = $this->request->post[$this->m . '_stops'];
		} elseif ($this->config->has($this->m . '_stops')) {
			$data[$this->m . '_stops'] = $this->config->get($this->m . '_stops');
		} else {
			$data[$this->m . '_stops'] = [];
		}

		if (isset($this->request->post[$this->m . '_costs'])) {
			$data[$this->m . '_costs'] = $this->request->post[$this->m . '_costs'];
		} elseif ($this->config->has($this->m . '_costs')) {
			$data[$this->m . '_costs'] = $this->config->get($this->m . '_costs');
		} else {
			$data[$this->m . '_costs'] = [];
		}

		if (isset($this->request->post[$this->m . '_map_key'])) {
			$data[$this->m . '_map_key'] = $this->request->post[$this->m . '_map_key'];
		} else {
			$data[$this->m . '_map_key'] = $this->config->get($this->m . '_map_key');
		}

		if (isset($this->request->post[$this->m . '_map_status'])) {
			$data[$this->m . '_map_status'] = $this->request->post[$this->m . '_map_status'];
		} else {
			$data[$this->m . '_map_status'] = $this->config->get($this->m . '_map_status');
		}

		if (isset($this->request->post[$this->m . '_map_type'])) {
			$data[$this->m . '_map_type'] = $this->request->post[$this->m . '_map_type'];
		} else {
			$data[$this->m . '_map_type'] = $this->config->get($this->m . '_map_type');
		}

		foreach ($this->controls as $control) {
			$data['map_controls'][] = [
				'code' => $control,
				'name' => $this->language->get('text_' . $control),
			];
		}

		if (isset($this->request->post[$this->m . '_map_control'])) {
			$data[$this->m . '_map_control'] = $this->request->post[$this->m . '_map_control'];
		} elseif ($this->config->has($this->m . '_map_control')) {
			$data[$this->m . '_map_control'] = $this->config->get($this->m . '_map_control');
		} else {
			$data[$this->m . '_map_control'] = [];
		}

		if (isset($this->request->post[$this->m . '_map_button'])) {
			$data[$this->m . '_map_button'] = $this->request->post[$this->m . '_map_button'];
		} elseif ($this->config->has($this->m . '_map_button')) {
			$data[$this->m . '_map_button'] = $this->config->get($this->m . '_map_button');
		} else {
			$data[$this->m . '_map_button'] = 'Изменить пункт выдачи';
		}

		if (isset($this->request->post[$this->m . '_cap_status'])) {
			$data[$this->m . '_cap_status'] = $this->request->post[$this->m . '_cap_status'];
		} else {
			$data[$this->m . '_cap_status'] = $this->config->get($this->m . '_cap_status');
		}

		if (isset($this->request->post[$this->m . '_cap_error'])) {
			$data[$this->m . '_cap_error'] = $this->request->post[$this->m . '_cap_error'];
		} else {
			$data[$this->m . '_cap_error'] = $this->config->get($this->m . '_cap_error');
		}

		if (isset($this->request->post[$this->m . '_cap_title'])) {
			$data[$this->m . '_cap_title'] = $this->request->post[$this->m . '_cap_title'];
		} elseif ($this->config->has($this->m . '_cap_title')) {
			$data[$this->m . '_cap_title'] = $this->config->get($this->m . '_cap_title');
		} else {
			$data[$this->m . '_cap_title'] = '{{logo}} Укажите город для расчета доставки';
		}

		if (isset($this->request->post[$this->m . '_cap_cost'])) {
			$data[$this->m . '_cap_cost'] = $this->request->post[$this->m . '_cap_cost'];
		} else {
			$data[$this->m . '_cap_cost'] = $this->config->get($this->m . '_cap_cost');
		}

		if (isset($this->request->post[$this->m . '_license'])) {
			$data[$this->m . '_license'] = $this->request->post[$this->m . '_license'];
		} else {
			$data[$this->m . '_license'] = $this->config->get($this->m . '_license');
		}

		if (!$this->config->has($this->m . '_update') || version_compare($this->version, $this->config->get($this->m . '_update'), '<')) {
			$data[$this->m . '_update'] = false;

			$this->checkUpdate();
		}

		$data[$this->m . '_update'] = $this->version;

		if (version_compare(VERSION, '2.1', '<')) {
			$this->load->model('sale/customer_group');

			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		} else {
			$this->load->model('customer/customer_group');

			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		}

		$data['total_countries'] = $this->{'model_shipping_' . $this->m}->getTotalCountries();
		$data['total_regions'] = $this->{'model_shipping_' . $this->m}->getTotalRegions();
		$data['total_cities'] = $this->{'model_shipping_' . $this->m}->getTotalCities();
		$data['total_pvzs'] = $this->{'model_shipping_' . $this->m}->getTotalPvzs();
		$data['total_places'] = $this->{'model_shipping_' . $this->m}->getTotalPlaces();
		$data['total_pickups'] = $this->{'model_shipping_' . $this->m}->getTotalPikups();
		$data['places'] = $this->{'model_shipping_' . $this->m}->getPlaces();
		$data['countries'] = $this->{'model_shipping_' . $this->m}->getCountries();
		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		$data['variants'] = $this->variants;
		$data['variants_map'] = $this->variants_map;
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		$data['m'] = $this->m;
		$data['version'] = $this->version;
		$data['email'] = $this->email;
		$data['site'] = $this->site;
		$data['module_docs'] = $this->module_docs;
		$data['delivery'] = $this->delivery;
		$data['api_docs'] = $this->api_docs;
		$data['token'] = $this->session->data['token'];
		$data['host'] = isset($this->request->server['HTTP_HOST']) ? $this->request->server['HTTP_HOST'] : '';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/' . $this->m . '.tpl', $data));
	}

	public function updateData() {
		if ($this->validate() && isset($this->request->post['current']) && isset($this->request->post['type'])) {
			$this->load->language('shipping/' . $this->m);
			$this->load->model('shipping/' . $this->m);

			// current: пагинатор
			// type: 0 - склады PickUp, 1 - склады DropOff, 2 - города, 3 - пвз
			if ($this->request->post['type'] == 0) {
				$this->{'model_shipping_' . $this->m}->clearData();

				$this->cache->delete($this->m . '.token.' . base64_encode(''));
				$pickups = $this->{'model_shipping_' . $this->m}->getApi('pickup_places');

				if (isset($pickups) && count($pickups) > 0) {
					$total = count($pickups);

					foreach ($pickups as $pickup) {
						$this->{'model_shipping_' . $this->m}->addPickup($pickup);
					}

					$json['finish'] = sprintf($this->language->get('text_success_update_pickups'), $total);
					$json['current'] = $this->request->post['current'];
					$json['type'] = $this->request->post['type'] + 1;
				} else {
					$json['finish'] = sprintf($this->language->get('text_success_update_pickups'), 0);
					$json['current'] = $this->request->post['current'];
					$json['type'] = $this->request->post['type'] + 1;
				}
			} elseif ($this->request->post['type'] == 1) {
				$this->cache->delete($this->m . '.token.' . base64_encode(''));

				$places = $this->{'model_shipping_' . $this->m}->getApi('from_places');

				if (isset($places) && count($places) > 0) {
					$total = count($places);

					foreach ($places as $place) {
						$this->{'model_shipping_' . $this->m}->addPlace($place);
					}

					$json['finish'] = sprintf($this->language->get('text_success_update_places'), $total);
					$json['current'] = $this->request->post['current'];
					$json['type'] = $this->request->post['type'] + 1;
				} else {
					$json['finish'] = sprintf($this->language->get('text_success_update_places'), 0);
					$json['current'] = $this->request->post['current'];
					$json['type'] = $this->request->post['type'] + 1;
				}
			} elseif ($this->request->post['type'] == 2) {
				$this->cache->delete($this->m . '.token.' . base64_encode(''));
				$cities = $this->{'model_shipping_' . $this->m}->getApi('cities');

				if (isset($cities) && count($cities) > 0) {
					$total = count($cities);

					foreach ($cities as $city) {
						$this->{'model_shipping_' . $this->m}->prepareCity($city);
					}

					$json['finish'] = sprintf($this->language->get('text_success_update_cities'), $total);
					$json['current'] = $this->request->post['current'];
					$json['type'] = $this->request->post['type'] + 1;
				} else {
					$json['error'] = $this->language->get('error_update_cities');
				}
			} elseif ($this->request->post['type'] == 3) {
				$current = (int)$this->request->post['current'] + 500;
				$next = $this->request->post['next'];
				
				if (!$next) {
					$this->cache->delete($this->m . '.token.' . base64_encode(''));
					$pvzs = $this->{'model_shipping_' . $this->m}->getApi('variants', ['payloadIncludes.includeWorkingHours' => 'true', 'payloadIncludes.includePostalCode' => 'true', 'pagination.size' => 500]);
				} else {
					$pvzs = $this->{'model_shipping_' . $this->m}->getApi('variants', ['payloadIncludes.includeWorkingHours' => 'true', 'payloadIncludes.includePostalCode' => 'true', 'pagination.size' => 500, 'pagination.token' => $next]);
				}

				if (isset($pvzs['totalCount'])) {
					$total = $pvzs['totalCount'];
					$next = $pvzs['nextPageToken'];
					$pvzs = $pvzs['data'];
				} else {
					$next = 0;
				}

				if (!empty($pvzs)) {
					foreach ($pvzs as $pvz) {
						if (isset($pvz['settlement'])) {
							$city_name = str_replace(['г. ', 'рп. ', 'п. ', 'пгт. ', 'д. ', 'с. ', 'дер ', 'ст-ца ', 'х. ', 'гп ', 'аул ', 'дп. '], '', $pvz['settlement']);

							$city_id = $this->{'model_shipping_' . $this->m}->getCity($city_name);

							if (!$city_id) {
								$city_id = $this->{'model_shipping_' . $this->m}->addCity($city_name);
							}
						} else {
							continue;
						}

						if (isset($pvz['region'])) {
							$region_id = $this->{'model_shipping_' . $this->m}->getRegion($pvz['region']);

							if (!$region_id) {
								$region_id = $this->{'model_shipping_' . $this->m}->addRegion($pvz['region']);
							}
						} else {
							$region_id = $this->{'model_shipping_' . $this->m}->getRegion($city_name);

							if (!$region_id) {
								$region_id = $this->{'model_shipping_' . $this->m}->addRegion($city_name);
							}
						}

						$this->{'model_shipping_' . $this->m}->updateCity($city_id, $region_id);
						$this->{'model_shipping_' . $this->m}->addPvz($pvz, $region_id, $city_id);
					}

					if (!$next) {
						$json['finish'] = $this->language->get('text_success_update_pvzs');
					} else {
						$json['success'] = sprintf($this->language->get('text_success_updating_pvzs'), $current, $total);
						$json['current'] = $current;
						$json['next'] = $next;
						$json['total'] = $total;
						$json['type'] = $this->request->post['type'];
					}
				} else {
					$json['error'] = $this->language->get('error_update_pvzs');
				}
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getRegionsTable() {
		if ($this->validate() && isset($this->request->get['country_id']) && $this->request->get['country_id'] > 0) {
			$this->load->model('shipping/' . $this->m);
			$this->load->model('localisation/zone');

			$country_id = (int)$this->request->get['country_id'];
			$json['regions'] = $this->{'model_shipping_' . $this->m}->getRegionsToZones($country_id);
			$json['zones'] = $this->model_localisation_zone->getZonesByCountryId($country_id);

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function updateRegionToZone() {
		$this->load->language('shipping/' . $this->m);

		if ($this->validate() && isset($this->request->get['country_id']) && isset($this->request->get[$this->m . '_regions']) && !empty($this->request->get[$this->m . '_regions'])) {
			$this->load->model('shipping/' . $this->m);

			// проставляем полученные соответствия
			foreach ($this->request->get[$this->m . '_regions'] as $region_id => $values) {
				$region_check = $this->{'model_shipping_' . $this->m}->getRegionById($region_id);

				if ($region_check) {
					$this->{'model_shipping_' . $this->m}->updateRegionToZone($region_id, $values);
				}
			}

			// чистим пустые соответствия
			$regions = $this->{'model_shipping_' . $this->m}->getRegionsToZones($this->request->get['country_id']);

			if ($regions && !empty($regions)) {
				foreach ($regions as $region) {
					if (!array_key_exists($region['region_id'], $this->request->get[$this->m . '_regions'])) {
						$this->{'model_shipping_' . $this->m}->clearRegionToZone($region['region_id']);
					}
				}
			}

			$json['success'] = $this->language->get('text_success_matching');
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function checkUpdate() {
		if ($this->validate()) {
			$this->load->model('shipping/' . $this->m);

			$this->{'model_shipping_' . $this->m}->checkUpdate();
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/' . $this->m)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/event');
		$this->model_extension_event->addEvent($this->m . '_checkout_js', 'catalog/controller/common/header/before', 'shipping/' . $this->m . '/addCheckoutJs');

		$this->load->model('shipping/' . $this->m);
		$this->{'model_shipping_' . $this->m}->install();
	}

	public function uninstall() {
		$this->load->model('extension/event');
		$this->model_extension_event->deleteEvent($this->m . '_checkout_js');

		$this->load->model('shipping/' . $this->m);
		$this->{'model_shipping_' . $this->m}->uninstall();
	}
}
