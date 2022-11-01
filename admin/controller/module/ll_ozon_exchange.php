<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
class ControllerExtensionModuleLLOzonExchange extends Controller {
	protected $code = false;
	protected $statics = false;
	protected $ll = false;
	protected $api = false;

	public function __construct($registry) {
		$this->registry = $registry;

		$this->code = basename(__FILE__, '.php');

		$this->statics = new \Config();
		$this->statics->load($this->code);

		$this->ll = new LL\Core($this->registry, $this->code, $this->statics->get('type'));
		$this->api = new LL\OZON\API($this->ll);
	}

	public function index() {
		$this->load->language($this->ll->getRoute());

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->ll->validate()) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting($this->ll->getPrefix(), $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->ll->getLinkExtension());
		}

		if (!$this->ll->validate()) {
			$this->session->data['warning'] = $this->language->get('error_permission');
		}

		$this->load->model($this->ll->getRoute());
		$this->load->model('localisation/order_status');
		$this->load->model('localisation/language');
		$this->load->model($this->ll->getExtensionModel() . '/extension');

		$translations = [
			'heading_title',
			'heading_license',
			'button_order',
			'button_exchange',
			'button_shipping',
			'button_cancel',
			'button_save',
			'button_add',
			'button_edit',
			'button_load',
			'button_download',
			'button_clear',
			'button_check',
			'tab_api',
			'tab_setting',
			'tab_preset',
			'tab_pickup',
			'tab_merge',
			'tab_tracking',
			'tab_alert',
			'tab_cron',
			'tab_sms',
			'tab_log',
			'tab_support',
			'text_yes',
			'text_no',
			'text_enabled',
			'text_disabled',
			'text_email',
			'text_site',
			'text_docs',
			'text_service',
			'text_api_docs',
			'text_notify',
			'text_notify_email',
			'text_notify_sms',
			'text_day',
			'entry_client_id',
			'entry_client_secret',
			'entry_test',
			'entry_timezone',
			'entry_cod',
			'entry_list_order_status',
			'entry_sms_gate',
			'entry_sms_login',
			'entry_sms_password',
			'entry_sms_sender',
			'entry_cron_key',
			'entry_update_statuses',
			'entry_update_day',
			'entry_update_url',
			'entry_update_pvz_url',
			'entry_logging',
			'entry_license',
			'entry_merge',
			'entry_merge_name',
			'entry_merge_model',
			'entry_merge_vat',
			'entry_merge_danger',
			'entry_merge_inn',
			'entry_merge_from_model',
			'help_log',
			'column_status',
			'column_order_status',
			'column_notify',
			'column_start',
			'column_stop',
			'column_delay',
		];

		if (version_compare(VERSION, '3.0', '<')) {
			foreach ($translations as $translation) {
				$data[$translation] = $this->language->get($translation);
			}
		}

		$variables = [
			'logging'                                                      => 1,
			'client_id'                                                    => '',
			'client_secret'                                                => '',
			'test'                                                         => 0,
			'timezone'                                                     => 'Europe/Moscow',
			'list_order_status'                                            => [],
			'default_order_id'                                             => '{{order_id}}', 
			'default_firstMileTransfer_type'                               => 'DropOff',
			'default_allowPartialDelivery'                                 => 0,
			'default_allowUncovering'                                      => 0,
			'default_order_comment'                                        => '{{comment}}', 
			'default_buyer_type'                                           => 'NaturalPerson',
			'default_buyer_firstname'                                      => '',
			'default_buyer_lastname'                                       => '',
			'default_buyer_email'                                          => 0,
			'default_recipient_type'                                       => 'NaturalPerson',
			'default_recipient_firstname'                                  => '',
			'default_recipient_lastname'                                   => '',
			'default_recipient_email'                                      => 0,
			'default_address'                                              => '',
			'default_deliveryInformation_desiredDeliveryTimeInterval_from' => '10:00',
			'default_deliveryInformation_desiredDeliveryTimeInterval_to'   => '18:00',
			'default_product_article'                                      => 'model',
			'default_pickup'                                               => '',
			'default_pickup_from'                                          => '10:00',
			'default_pickup_to'                                            => '18:00',
			'default_pickup_name'                                          => '',
			'default_pickup_phone'                                         => '',
			'cod'                                                          => [],
			'merge'                                                        => 0,
			'merge_name'                                                   => $this->config->get('config_name'),
			'merge_model'                                                  => $this->config->get('config_name'),
			'merge_vat'                                                    => 0,
			'merge_danger'                                                 => 0,
			'merge_inn'                                                    => '',
			'merge_from_model'                                             => 0,
			'trackings'                                                    => [],
			'alerts'                                                       => [],
			'sms_gate'                                                     => '',
			'sms_login'                                                    => '',
			'sms_password'                                                 => '',
			'sms_sender'                                                   => '',
			'cron_key'                                                     => $this->generate_cron_key(),
			'update_statuses'                                              => [],
			'update_day'                                                   => 30,
			'license'                                                      => '',
		];

		foreach ($variables as $variable => $default) {
			$data[$this->ll->getPrefix() . '_' . $variable] = $this->ll->getValue($variable, $default);
		}

		$data['breadcrumbs'] = $this->ll->getBreadcrumbs();
		$data['success'] = $this->ll->getSuccess();
		$data['error_warning'] = $this->ll->getWarning();
		$data['action'] = $this->ll->getLinkExtension();
		$data['order'] = $this->ll->getLinkExtension('order');
		$data['exchange'] = $this->ll->getLinkExtension();
		$data['shipping'] = $this->ll->getLink($this->ll->getExt() . 'shipping/ll_ozon');
		$data['cancel'] = $this->ll->getLinkExtensions();
		$data['get_cron_key'] = $this->ll->getLinkExtension('get_cron_key');
		$data['m'] = $this->code;
		$data['version'] = $this->statics->get('version');
		$data['email'] = $this->statics->get('email');
		$data['site'] = $this->statics->get('site');
		$data['docs'] = $this->statics->get('docs');
		$data['api_service'] = $this->statics->get('api_service');
		$data['api_docs'] = $this->statics->get('api_docs');
		$data['host'] = isset($this->request->server['HTTP_HOST']) ? $this->request->server['HTTP_HOST'] : '';
		$data['pickups'] = $this->{$this->ll->getModel()}->getPickups();
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['statuses'] = $this->statics->get('statuses');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['timezones'] = DateTimeZone::listIdentifiers();
		$data['payments'] = [];
		$data['firstname_fields'] = ['firstname', 'payment_firstname', 'shipping_firstname'];
		$data['lastname_fields'] = ['lastname', 'payment_lastname', 'shipping_lastname'];
		$data['address_fields'] = ['shipping_address_1', 'shipping_address_2', 'payment_address_1', 'payment_address_2'];
		$data['variants'] = $this->statics->get('variants');
		$data['sms_gates'] = $this->ll->getSMSGates();
		$cron_key = $data[$this->ll->getPrefix() . '_cron_key'];
		$data['update_url'] = (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG) . 'index.php?route=' . $this->ll->getRoute() . '/trackOrderStatus&cron_key=' . $cron_key;
		$data['update_pvz_url'] = (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG) . 'index.php?route=' . $this->ll->getRoute() . '/updatePVZ&cron_key=' . $cron_key;
		$data['load_log'] = $this->ll->getLinkExtension('load_log');
		$data['download_log'] = $this->ll->getLinkExtension('download_log');
		$data['clear_log'] = $this->ll->getLinkExtension('clear_log');
		$data['ll_invoice'] = $this->config->get($this->ll->getPrefix('ll_invoice', 'module') . '_status');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$payments = $this->{'model_' . $this->ll->getExtensionModel() . '_extension'}->getInstalled('payment');

		$files = glob(DIR_APPLICATION . 'controller/' . $this->ll->getExt() . 'payment/*.php', GLOB_BRACE);

		if ($files) {
			foreach ($files as $file) {
				$payment = basename($file, '.php');

				$this->load->language($this->ll->getExt() . 'payment/' . $payment);

				if (in_array($payment, $payments)) {
					$data['payments'][] = [
						'code' => $payment,
						'name' => '[' . $payment . '] ' . $this->language->get('heading_title'),
					];
				}
			}
		}

		// перебиваем переводы других модулей
		$this->load->language($this->ll->getRoute());

		if (!$this->filterit) {
			$filterit_payments = isset($this->config->get('filterit_payment')['created']) ? $this->config->get('filterit_payment')['created'] : [];

			foreach ($filterit_payments as $code => $info) {
				$data['payments'][] = [
					'code' => $code,
					'name' => !empty($info['title'][$this->config->get('config_admin_language')]) ? '[' . $code . '] ' . $info['title'][$this->config->get('config_admin_language')] : '[' . $code . ']',
				];
			}
		}

		$this->response->setOutput($this->load->view($this->ll->getView(), $data));
	}

	public function order() {
		$this->load->language($this->ll->getRoute());

		$this->document->setTitle($this->language->get('heading_title_order'));

		$this->load->model($this->ll->getRoute());
		$this->load->model('localisation/order_status');

		$translations = [
			'heading_title_order',
			'button_order',
			'button_exchange',
			'button_shipping',
			'button_cancel',
			'button_create',
			'button_request',
			'button_dropoff',
			'button_pickup',
			'button_update',
			'button_print',
			'button_change',
			'button_view',
			'button_delete',
			'button_clear',
			'button_filter',
			'button_check',
			'button_label',
			'button_canceled',
			'column_id',
			'column_to',
			'column_total',
			'column_date',
			'column_order_status',
			'column_customer',
			'column_tariff',
			'column_logistic',
			'column_shipment',
			'column_status',
			'entry_pvz',
			'entry_customer',
			'text_no_results',
			'text_remove_confirm',
			'text_canceled_confirm',
		];

		if (version_compare(VERSION, '3.0', '<')) {
			foreach ($translations as $translation) {
				$data[$translation] = $this->language->get($translation);
			}
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = [];
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$params = '';

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
			$params .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
			$params .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_pvz'])) {
			$filter_pvz = $this->request->get['filter_pvz'];
			$params .= '&filter_pvz=' . urlencode(html_entity_decode($this->request->get['filter_pvz'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filter_pvz = null;
		}

		if (isset($this->request->get['filter_to'])) {
			$filter_to = $this->request->get['filter_to'];
			$params .= '&filter_to=' . urlencode(html_entity_decode($this->request->get['filter_to'], ENT_QUOTES, 'UTF-8'));
			$filter_to_name = $this->{$this->ll->getModel()}->getCity($filter_to)['name'];
		} else {
			$filter_to = null;
			$filter_to_name = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
			$params .= '&filter_total=' . $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
			$params .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
			$params .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_tariff'])) {
			$filter_tariff = $this->request->get['filter_tariff'];
			$params .= '&filter_tariff=' . $this->request->get['filter_tariff'];
		} else {
			$filter_tariff = null;
		}

		if (isset($this->request->get['filter_logisticOrderNumber'])) {
			$filter_logisticOrderNumber = $this->request->get['filter_logisticOrderNumber'];
			$params .= '&filter_logisticOrderNumber=' . urlencode(html_entity_decode($this->request->get['filter_logisticOrderNumber'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filter_logisticOrderNumber = null;
		}

		if (isset($this->request->get['filter_shipment_id'])) {
			$filter_shipment_id = $this->request->get['filter_shipment_id'];
			$params .= '&filter_shipment_id=' . $this->request->get['filter_shipment_id'];
		} else {
			$filter_shipment_id = null;
		}

		if (isset($this->request->get['filter_delivery_status'])) {
			$filter_delivery_status = $this->request->get['filter_delivery_status'];
			$params .= '&filter_delivery_status=' . $this->request->get['filter_delivery_status'];
		} else {
			$filter_delivery_status = null;
		}

		$filter_data = [
			'filter_order_id'            => $filter_order_id,
			'filter_customer'	         => $filter_customer,
			'filter_pvz'	             => $filter_pvz,
			'filter_to'	                 => $filter_to,
			'filter_total'               => $filter_total,
			'filter_date_added'          => $filter_date_added,
			'filter_order_status'        => $filter_order_status,
			'filter_tariff'              => $filter_tariff,
			'filter_logisticOrderNumber' => $filter_logisticOrderNumber,
			'filter_shipment_id'         => $filter_shipment_id,
			'filter_delivery_status'     => $filter_delivery_status,
			'start'                      => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin'),
		];

		$total = $this->{$this->ll->getModel()}->getTotalOrders($filter_data);

		$results = $this->{$this->ll->getModel()}->getOrders($filter_data);

		$breadcrumbs[] = [
			'text' => $this->language->get('heading_title_order'),
			'href' => $this->ll->getLinkExtension('order'),
		];

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_pvz'] = $filter_pvz;
		$data['filter_to'] = $filter_to;
		$data['filter_to_name'] = $filter_to_name;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_tariff'] = $filter_tariff;
		$data['filter_logisticOrderNumber'] = $filter_logisticOrderNumber;
		$data['filter_shipment_id'] = $filter_shipment_id;
		$data['filter_delivery_status'] = $filter_delivery_status;
		$data['variants'] = $this->statics->get('variants');
		$data['statuses'] = $this->statics->get('statuses');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['breadcrumbs'] = $this->ll->getBreadcrumbs($breadcrumbs);
		$data['success'] = $this->ll->getSuccess();
		$data['error_warning'] = $this->ll->getWarning();
		$data['send'] = $this->ll->getLinkExtension('send');
		$data['dropoff'] = $this->ll->getLinkExtension('dropoff');
		$data['pickup'] = $this->ll->getLinkExtension('pickup');
		$data['label'] = $this->ll->getLinkExtension('label');
		$data['print'] = $this->ll->getLinkExtension('get_print');
		$data['update'] = $this->ll->getLinkExtension('update');
		$data['change'] = $this->ll->getLinkExtension('change');
		$data['orderr'] = $this->ll->getLinkExtension('order');
		$data['route'] = $this->ll->getRoute();
		$data['exchange'] = $this->ll->getLinkExtension();
		$data['shipping'] = $this->ll->getLink($this->ll->getExt() . 'shipping/ll_ozon');
		$data['cancel'] = $this->ll->getLinkExtensions();
		$data['token'] = $this->ll->getToken();
		$data['m'] = $this->code;
		$data['version'] = $this->statics->get('version');;
		$data['orders'] = [];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['pagination'] = $this->ll->getPagination($total, $page, $this->ll->getLinkExtension('order', $params . '&page={page}'));
		$data['results'] = $this->ll->getPaginationText($total, $page, $this->language->get('text_pagination'));
		$data['customer_autocomplete'] = $this->ll->getLink('customer/customer/autocomplete');
		$data['to_autocomplete'] = $this->ll->getLinkExtension('autocomplete');
		$data['check_phone_url'] = $this->ll->getLink($this->ll->getExt() . 'module/ll_checkclient/checkPhone');
		$data['pickup_date'] = date('Y-m-d', strtotime('+ 1 day'));
		$data['pickup_from'] = $this->config->get($this->ll->getPrefix() . '_default_pickup_from');
		$data['pickup_to'] = $this->config->get($this->ll->getPrefix() . '_default_pickup_to');
		$data['pickup_sklad'] = $this->config->get($this->ll->getPrefix() . '_default_pickup');
		$data['pickups'] = $this->{$this->ll->getModel()}->getPickups();
		$data['pickup_name'] = $this->config->get($this->ll->getPrefix() . '_default_pickup_name');
		$data['pickup_phone'] = $this->config->get($this->ll->getPrefix() . '_default_pickup_phone');

		foreach ($results as $result) {
			if (!$result['tariff']) {
				$result['status'] = -1;
			}

			$to_data = $this->{$this->ll->getModel()}->getCity($result['to_city']);
			$to = '<b>' . $to_data['name'] . '</b><br>';

			if (in_array($result['tariff'], $this->statics->get('variants_map')) && $result['pvz']) {
				$pvz = $this->{$this->ll->getModel()}->getPvz($result['pvz']);

				if ($pvz) {
					$to .= $pvz['address'] . ' <span class="label label-default">' . $result['pvz'] . '</span><br>';
				} else {
					$to .= ' <span class="label label-danger">' . $result['pvz'] . $this->language->get('error_pvz') . '</span><br>';
				}
			}

			$to .= $to_data['region_name'] . '<br>' . $to_data['country_name'];

			$check = null;

			if ($this->config->has($this->ll->getPrefix('ll_checkclient', 'module') . '_status')) {
				$this->load->model($this->ll->getExt() . 'module/ll_checkclient');

				$check = $this->{$this->ll->getModel($this->ll->getExt() . 'module/ll_checkclient')}->checkPhone($result['telephone'], $result['customer_id']);
			} else {
				$check['error'] = $this->language->get('error_checkclient');
			}

			$check_color = 'default';

			if (isset($check['success'])) {
				$check_color = $this->language->get('text_color_' . $check['rating']);
				$check = htmlspecialchars($check['success']);
			} elseif (isset($check['error'])) {
				$check = false;
			} else {
				$check = htmlspecialchars('<a class="btn btn-success" onclick="checkPhone(\'' . $result['order_id'] . '\', \'' . $result['telephone'] . '\', \'' . $result['customer_id'] . '\');">' . $this->language->get('button_check') . '</a></div>');
			}

			if ($result['tariff'] == 'courier') {
				$icon = 'truck';
			} elseif ($result['tariff'] == 'pickpoint') {
				$icon = 'home';
			} else {
				$icon = 'archive';
			}

			$data['orders'][] = [
				'order_id'            => $result['order_id'],
				'orderId'             => $result['orderId'],
				'logisticOrderNumber' => $result['logisticOrderNumber'],
				'shipment_id'         => $result['shipment_id'],
				'order_view'          => $this->ll->getLink('sale/order/info', '&order_id=' . $result['order_id']),
				'to'                  => $to,
				'total'               => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'shipping_cost'       => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'          => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'order_status'        => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'customer_id'         => $result['customer_id'],
				'customer'            => $result['customer'],
				'check_color'         => $check_color,
				'check'               => $check,
				'telephone'           => $result['telephone'],
				'tariff'              => $this->statics->get('variants')[$result['tariff']]['name'],
				'icon'                => $icon,
				'date'                => $result['date'] ? date($this->language->get('datetime_format'), strtotime($result['date'])) : '',
				'status_id'           => $result['status'],
				'status'              => $this->statics->get('statuses')[$result['status']]['title'],
				'description'         => $this->statics->get('statuses')[$result['status']]['description'],
				'color'               => $this->statics->get('statuses')[$result['status']]['color'],
				'view'                => $this->ll->getLinkExtension('view', '&order_id=' . $result['order_id']),
				'request'             => $this->ll->getLinkExtension('request', '&order_id=' . $result['order_id']),
				'change'              => $this->ll->getLinkExtension('change', '&order_id=' . $result['order_id']),
				'update'              => $this->ll->getLinkExtension('update', '&order_id=' . $result['order_id']),
				'canceled'            => $this->ll->getLinkExtension('canceled', '&order_id=' . $result['order_id']),
				'remove'              => $this->ll->getLinkExtension('remove', '&order_id=' . $result['order_id']),
				'print'               => $this->ll->getLinkExtension('get_print', '&order_id=' . $result['order_id']),
			];
		}

		$this->response->setOutput($this->load->view($this->ll->getView('order'), $data));
	}

	public function send() {
		$this->load->language($this->ll->getRoute());

		$this->document->setTitle($this->language->get('heading_title_send'));

		$this->load->model($this->ll->getRoute());
		$this->load->model('localisation/tax_class');

		if (isset($this->request->post['selected']) && $this->ll->validate()) {
			foreach ($this->request->post['selected'] as $order_id) {
				$order = $this->{$this->ll->getModel()}->getOrder((int)$order_id);

				if ($order) {
					$orders[] = $order;
				}
			}

			if (!isset($orders) || empty($orders)) {
				$this->session->data['warning'] = $this->language->get('error_send');

				$this->response->redirect($this->ll->getLinkExtension('order'));
			}
		} else {
			$this->session->data['warning'] = $this->language->get('error_send');

			$this->response->redirect($this->ll->getLinkExtension('order'));
		}

		$translations = [
			'heading_title_send',
			'button_order',
			'button_exchange',
			'button_shipping',
			'button_cancel',
			'button_export',
			'button_edit',
		];

		if (version_compare(VERSION, '3.0', '<')) {
			foreach ($translations as $translation) {
				$data[$translation] = $this->language->get($translation);
			}
		}

		$breadcrumbs[] = [
			'text' => $this->language->get('heading_title_order'),
			'href' => $this->ll->getLinkExtension('order'),
		];

		$breadcrumbs[] = [
			'text' => $this->language->get('heading_title_send'),
			'href' => $this->ll->getLinkExtension('send'),
		];

		$data['breadcrumbs'] = $this->ll->getBreadcrumbs($breadcrumbs);
		$data['success'] = $this->ll->getSuccess();
		$data['error_warning'] = $this->ll->getWarning();
		$data['action'] = $this->ll->getLinkExtension('export');
		$data['order'] = $this->ll->getLinkExtension('order');
		$data['exchange'] = $this->ll->getLinkExtension();
		$data['shipping'] = $this->ll->getLink($this->ll->getExt() . 'shipping/ll_ozon');
		$data['cancel'] = $this->ll->getLinkExtensions();
		$data['token'] = $this->ll->getToken();
		$data['m'] = $this->code;
		$data['version'] = $this->statics->get('version');;
		$data['orders'] = $orders;
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->ll->getView('send'), $data));
	}

	public function change() {
		$this->load->language($this->ll->getRoute());

		$this->document->setTitle($this->language->get('heading_title_change'));

		$this->load->model($this->ll->getRoute());

		if (isset($this->request->post['selected']) && $this->ll->validate()) {
			foreach ($this->request->post['selected'] as $order_id) {

				$order = $this->{$this->ll->getModel()}->getOrder((int)$order_id);

				if ($order) {
					if ($order['order_id']) {
						$orders[] = $order;
					}
				}
			}

			if (!isset($orders) || empty($orders)) {
				$this->session->data['warning'] = $this->language->get('error_update');

				$this->response->redirect($this->ll->getLinkExtension('order'));
			}
		} else {
			$this->session->data['warning'] = $this->language->get('error_update');

			$this->response->redirect($this->ll->getLinkExtension('order'));
		}

		$translations = [
			'heading_title_change',
			'button_order',
			'button_exchange',
			'button_shipping',
			'button_cancel',
			'button_export',
			'button_edit',
		];

		if (version_compare(VERSION, '3.0', '<')) {
			foreach ($translations as $translation) {
				$data[$translation] = $this->language->get($translation);
			}
		}

		$breadcrumbs[] = [
			'text' => $this->language->get('heading_title_order'),
			'href' => $this->ll->getLinkExtension('order'),
		];

		$breadcrumbs[] = [
			'text' => $this->language->get('heading_title_change'),
			'href' => $this->ll->getLinkExtension('send'),
		];

		$data['heading_title_send'] = $this->language->get('heading_title_change');
		$data['breadcrumbs'] = $this->ll->getBreadcrumbs($breadcrumbs);
		$data['success'] = $this->ll->getSuccess();
		$data['error_warning'] = $this->ll->getWarning();
		$data['action'] = $this->ll->getLinkExtension('export');
		$data['order'] = $this->ll->getLinkExtension('order');
		$data['exchange'] = $this->ll->getLinkExtension();
		$data['shipping'] = $this->ll->getLink($this->ll->getExt() . 'shipping/ll_ozon');
		$data['cancel'] = $this->ll->getLinkExtensions();
		$data['token'] = $this->ll->getToken();
		$data['m'] = $this->code;
		$data['version'] = $this->statics->get('version');;
		$data['orders'] = $orders;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->ll->getView('send'), $data));
	}

	public function view() {
		$this->load->language($this->ll->getRoute());

		$this->document->setTitle($this->language->get('heading_title_view'));

		$this->load->model($this->ll->getRoute());

		if (isset($this->request->get['order_id']) && $this->ll->validate()) {
			$result = $this->{$this->ll->getModel()}->getOrderInfo((int)$this->request->get['order_id']);

			if (!$result) {
				$this->response->redirect($this->ll->getLinkExtension('order'));
			}
		} else {
			$this->response->redirect($this->ll->getLinkExtension('order'));
		}

		$translations = [
			'heading_title_view',
			'button_order',
			'button_exchange',
			'button_shipping',
			'button_cancel',
			'button_update',
			'button_delete',
			'button_label',
			'button_print',
			'text_remove_confirm',
		];

		if (version_compare(VERSION, '3.0', '<')) {
			foreach ($translations as $translation) {
				$data[$translation] = $this->language->get($translation);
			}
		}

		$breadcrumbs[] = [
			'text' => $this->language->get('heading_title_order'),
			'href' => $this->ll->getLinkExtension('order'),
		];

		$breadcrumbs[] = [
			'text' => $this->language->get('heading_title_view'),
			'href' => $this->ll->getLinkExtension('send'),
		];

		$pvz = null;

		$to_data = $this->{$this->ll->getModel()}->getCity($result['to_city']);

		if (in_array($result['tariff'], $this->statics->get('variants_map')) && $result['pvz']) {
			$pvz_info = $this->{$this->ll->getModel()}->getPvz($result['pvz']);

			if ($pvz_info) {
				$pvz = isset($pvz_info['address']) ? $pvz_info['address'] : $pvz_info['fullAddress'];
			} else {$pvz = '<span class="label label-danger">' . $result['pvz'] . $this->language->get('error_pvz') . '</span><br>';$to .= ' <span class="label label-danger">' . $result['pvz'] . $this->language->get('error_pvz') . '</span><br>';
			}
		}

		$data['order'] = [
			'order_id'        => $result['order_id'],
			'order_link'      => $this->ll->getLink('sale/order/info', '&order_id=' . $result['order_id']),
			'orderId'         => $result['orderId'],
			'logistic'        => $result['logisticOrderNumber'],
			'shipment_id'     => $result['shipment_id'],
			'tariff'          => $this->statics->get('variants')[$result['tariff']]['name'],
			'customer'        => $result['firstname'] . ' ' . $result['lastname'],
			'customer_link'   => $result['customer_id'] ? $this->ll->getLink('customer/customer/edit', '&customer_id=' . $result['customer_id']) : false,
			'country'         => $to_data['country_name'],
			'zone'            => $to_data['region_name'],
			'city'            => $to_data['name'],
			'pvz'             => $pvz,
			'status_id'       => $result['status'],
			'update'          => $this->ll->getLinkExtension('update', '&order_id=' . $result['order_id']),
			'label'           => $this->ll->getLinkExtension('label', '&order_id=' . $result['order_id']),
			'print'           => $this->ll->getLinkExtension('get_print', '&order_id=' . $result['order_id']),
			'remove'          => $this->ll->getLinkExtension('remove', '&order_id=' . $result['order_id']),
		];

		$histories = $this->{$this->ll->getModel()}->getOrderHistories($result['order_id']);

		foreach ($histories as $history) {
			$data['histories'][] = [
				'status'     => $history['status'],
				'comment'    => nl2br($history['comment']),
				'date_added' => date($this->language->get('datetime_format'), strtotime($history['date_added'])),
				'notify'     => $history['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
			];
		}

		$statuses = $this->{$this->ll->getModel()}->getOrderStatuses($result['order_id']);

		if ($statuses) {
			foreach ($statuses as $status) {
				$data['statuses'][] = [
					'status'      => $this->statics->get('statuses')[$status['code']]['title'],
					'description' => $this->statics->get('statuses')[$status['code']]['description'],
					'color'       => $this->statics->get('statuses')[$status['code']]['color'],
					'date'        => date($this->language->get('datetime_format'), strtotime($status['date'])),
				];
			}
		} else {
			$data['statuses'][] = [
				'status'      => $this->statics->get('statuses')[$result['status']]['title'],
				'description' => $this->statics->get('statuses')[$result['status']]['description'],
				'color'       => $this->statics->get('statuses')[$result['status']]['color'],
				'date'        => date($this->language->get('datetime_format'), strtotime($result['date'])),
			];
		}

		$data['breadcrumbs'] = $this->ll->getBreadcrumbs($breadcrumbs);
		$data['success'] = $this->ll->getSuccess();
		$data['error_warning'] = $this->ll->getWarning();
		$data['orders'] = $this->ll->getLinkExtension('order');
		$data['exchange'] = $this->ll->getLinkExtension();
		$data['shipping'] = $this->ll->getLink($this->ll->getExt() . 'shipping/ll_ozon');
		$data['ll_invoice'] = $this->config->get($this->ll->getPrefix('ll_invoice', 'module') . '_status');
		$data['cancel'] = $this->ll->getLinkExtensions();
		$data['token'] = $this->ll->getToken();
		$data['m'] = $this->code;
		$data['version'] = $this->statics->get('version');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->ll->getView('view'), $data));
	}

	public function export() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->ll->validate()) {
			$this->load->language($this->ll->getRoute());

			$this->load->model($this->ll->getRoute());

			$params = $this->request->post;
			$success = false;
			$warning = false;

			foreach ($params['order'] as $key => $order) {
				$this->{$this->ll->getModel()}->updateOrder($order);

				$params['order'][$key]['allowPartialDelivery'] = boolval($order['allowPartialDelivery']);
				$params['order'][$key]['allowUncovering'] = boolval($order['allowUncovering']);
				$params['order'][$key]['payment']['prepaymentAmount'] = floatval($order['payment']['prepaymentAmount']);
				$params['order'][$key]['payment']['recipientPaymentAmount'] = floatval($order['payment']['recipientPaymentAmount']);
				$params['order'][$key]['payment']['deliveryPrice'] = floatval($order['payment']['deliveryPrice']);
				$params['order'][$key]['payment']['deliveryVat']['rate'] = (int)$order['payment']['deliveryVat']['rate'];
				$params['order'][$key]['payment']['deliveryVat']['sum'] = floatval($order['payment']['deliveryVat']['sum']);
				$params['order'][$key]['deliveryInformation']['deliveryVariantId'] = (string)$order['deliveryInformation']['deliveryVariantId'];

				if ($order['deliveryInformation']['desiredDeliveryTimeInterval']['from'] == '') {
					unset($params['order'][$key]['deliveryInformation']['desiredDeliveryTimeInterval']['from']);
				}

				if ($order['deliveryInformation']['desiredDeliveryTimeInterval']['to'] == '') {
					unset($params['order'][$key]['deliveryInformation']['desiredDeliveryTimeInterval']['to']);
				}

				if ($order['deliveryInformation']['desiredDeliveryTimeInterval']['from'] == '' && $order['deliveryInformation']['desiredDeliveryTimeInterval']['to'] == '') {
					unset($params['order'][$key]['deliveryInformation']['desiredDeliveryTimeInterval']);
				}

				if ($order['deliveryInformation']['deliveryType'] == 'courier') {
					$params['order'][$key]['deliveryInformation']['deliveryType'] = 'Courier';
				}

				if ($order['deliveryInformation']['deliveryType'] == 'pickpoint') {
					$params['order'][$key]['deliveryInformation']['deliveryType'] = 'PickPoint';
				}

				if ($order['deliveryInformation']['deliveryType'] == 'postamat') {
					$params['order'][$key]['deliveryInformation']['deliveryType'] = 'Postamat';
				}

				$order['packages']['dimensions']['weight'] = (int)$order['packages']['dimensions']['weight'];
				$order['packages']['dimensions']['length'] = (int)$order['packages']['dimensions']['length'];
				$order['packages']['dimensions']['height'] = (int)$order['packages']['dimensions']['height'];
				$order['packages']['dimensions']['width'] = (int)$order['packages']['dimensions']['width'];

				$params['order'][$key]['packages'] = [];
				$params['order'][$key]['packages'][] = $order['packages'];

				foreach ($order['orderLines'] as $orderLine_key => $orderLine) {
					$params['order'][$key]['orderLines'][$orderLine_key]['sellingPrice'] = floatval($orderLine['sellingPrice']);
					$params['order'][$key]['orderLines'][$orderLine_key]['estimatedPrice'] = floatval($orderLine['estimatedPrice']);
					$params['order'][$key]['orderLines'][$orderLine_key]['quantity'] = (int)$orderLine['quantity'];
					$params['order'][$key]['orderLines'][$orderLine_key]['vat']['rate'] = floatval($orderLine['vat']['rate']);
					$params['order'][$key]['orderLines'][$orderLine_key]['vat']['sum'] = floatval($orderLine['vat']['sum']);
					$params['order'][$key]['orderLines'][$orderLine_key]['attributes']['isDangerous'] = boolval($orderLine['attributes']['isDangerous']);
					$params['order'][$key]['orderLines'][$orderLine_key]['resideInPackages'] = [(string)$orderLine['resideInPackages']];

					if ($orderLine['supplierTin'] == '') {
						unset($params['order'][$key]['orderLines'][$orderLine_key]['supplierTin']);
					}
				}

				if (isset($params['order'][$key]['orderId'])) {
					unset($params['order'][$key]['orderNumber']);
					$params['order'][$key]['orderId'] = (int)$params['order'][$key]['orderId'];
				}

				if (isset($params['order'][$key]['orderId'])) {
					$result = $this->api->update_order($params['order'][$key]);
					$change_status = true;
				} else {
					$result = $this->api->send_order($params['order'][$key]);
					$change_status = false;
				}

				$order_id = $params['order'][$key]['order_id'];

				if (isset($result['id'])) {
					$this->{$this->ll->getModel()}->updateOrderInner($order_id, $result);

					$success .= sprintf($this->language->get('text_export_success'), $order_id) . ' ';
				} else {
					$warning .= sprintf($this->language->get('error_export_order'), $order_id, $result) . ' ';
				}
			}

			$this->session->data['success'] = $success;
			$this->session->data['warning'] = $warning;

			$this->response->redirect($this->ll->getLinkExtension('order'));
		}
	}

	public function label() {
		$this->load->language($this->ll->getRoute());

		if ($this->ll->validate()) {
			$this->load->model($this->ll->getRoute());

			if (isset($this->request->get['order_id'])) {
				$orderIds['orderId'][] = $this->{$this->ll->getModel()}->getOrderId($this->request->get['order_id']);
			} elseif (isset($this->request->post['selected']) && !empty($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $order_id) {
					$orderId = $this->{$this->ll->getModel()}->getOrderId($order_id);

					if ($orderId) {
						$orderIds['orderId'][] = $orderId;
					}
				}
			} else {
				$this->session->data['warning'] = $this->language->get('error_label');

				$this->response->redirect($this->ll->getLinkExtension('order'));
			}

			if (!isset($orderIds) || empty($orderIds)) {
				$this->session->data['warning'] = $this->language->get('error_label');

				$this->response->redirect($this->ll->getLinkExtension('order'));
			} else {
				$this->api->get_label($orderIds);

				$filename = $this->code . '_label.pdf';
				$file = DIR_UPLOAD . $filename;

				if (file_exists($file) && is_file($file)) {
					header("Content-Type: application/pdf");
					header("Content-Disposition: inline; filename=$filename");
					@readfile($file);
				} else {
					$this->session->data['warning'] = $this->language->get('error_label');
				}
			}
		} else {
			$this->session->data['warning'] = $this->language->get('error_permission');

			$this->response->redirect($this->ll->getLinkExtension('order'));
		}
	}

	public function dropoff() {
		if (isset($this->request->post['selected']) && $this->ll->validate()) {
			$this->load->language($this->ll->getRoute());

			$this->load->model($this->ll->getRoute());

			foreach ($this->request->post['selected'] as $order_id) {
				$orderId = $this->{$this->ll->getModel()}->getOrderId((int)$order_id);

				if ($orderId) {
					$orderIds['orderIds'][] = (int)$orderId;
				}
			}

			if (isset($orderIds) && !empty($orderIds)) {
				$result = $this->api->send_dropoff($orderIds);
			}

			if (isset($result['id'])) {
				$this->{$this->ll->getModel()}->updateOrderShipment($result);

				$this->session->data['success'] = sprintf($this->language->get('text_request_success'), $result['id']);
			} else {
				$this->session->data['warning'] = sprintf($this->language->get('error_request'), $result);
			}

			$this->response->redirect($this->ll->getLinkExtension('order'));
		}
	}

	public function pickup() {
		if (isset($this->request->post['selected']) && $this->ll->validate()) {
			$this->load->language($this->ll->getRoute());

			$this->load->model($this->ll->getRoute());

			foreach ($this->request->post['selected'] as $order_id) {
				$orderId = $this->{$this->ll->getModel()}->getOrderId((int)$order_id);

				if ($orderId) {
					$orderIds['orderIds'][] = (int)$orderId;
				}
			}

			if (isset($orderIds) && !empty($orderIds)) {
				$orderIds['desiredPickupTimeInterval']['date'] = $this->request->post['pickup_date'];
				$orderIds['desiredPickupTimeInterval']['from'] = $this->request->post['pickup_from'];
				$orderIds['desiredPickupTimeInterval']['to'] = $this->request->post['pickup_to'];
				
				$place = $this->{$this->ll->getModel()}->getPickup($this->request->post['pickup_sklad']);

				$orderIds['place']['placeId'] = $place['id'];
				$orderIds['place']['address'] = $place['address'];
				$orderIds['place']['contactName'] = $this->request->post['pickup_name'];
				$orderIds['place']['contactPhone'] = $this->request->post['pickup_phone'];

				$result = $this->api->send_pickup($orderIds);
			}

			if (isset($result['id'])) {
				$this->{$this->ll->getModel()}->updateOrderShipment($result);

				$this->session->data['success'] = sprintf($this->language->get('text_request_success'), $result['id']);
			} else {
				$this->session->data['warning'] = sprintf($this->language->get('error_request'), $result);
			}

			$this->response->redirect($this->ll->getLinkExtension('order'));
		}
	}

	public function get_print() {
		$this->load->language($this->ll->getRoute());

		if ($this->ll->validate()) {
			$this->load->model($this->ll->getRoute());

			if (isset($this->request->get['order_id'])) {
				$shipmentRequestIds[] = $this->{$this->ll->getModel()}->getOrderShipmentId($this->request->get['order_id']);
			} elseif (isset($this->request->post['selected']) && !empty($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $order_id) {
					$shipmentRequestId = $this->{$this->ll->getModel()}->getOrderShipmentId($order_id);

					if ($shipmentRequestId) {
						$shipmentRequestIds[] = $shipmentRequestId;
					}
				}
			} else {
				$this->session->data['warning'] = $this->language->get('error_print');

				$this->response->redirect($this->ll->getLinkExtension('order'));
			}

			if (!isset($shipmentRequestIds) || empty($shipmentRequestIds)) {
				$this->session->data['warning'] = $this->language->get('error_print');

				$this->response->redirect($this->ll->getLinkExtension('order'));
			} else {
				$this->api->get_documents($shipmentRequestIds);

				$filename = $this->code . '_print.pdf';
				$file = DIR_UPLOAD . $filename;

				if (file_exists($file) && is_file($file)) {
					header("Content-Type: application/pdf");
					header("Content-Disposition: inline; filename=$filename");
					@readfile($file);
				} else {
					$this->session->data['warning'] = $this->language->get('error_print');
				}
			}
		} else {
			$this->session->data['warning'] = $this->language->get('error_permission');

			$this->response->redirect($this->ll->getLinkExtension('order'));
		}
	}

	public function canceled() {
		$this->load->language($this->ll->getRoute());

		if ($this->ll->validate()) {
			$this->load->model($this->ll->getRoute());

			if (isset($this->request->get['order_id'])) {
				$orderIds['ids'][] = (int)$this->{$this->ll->getModel()}->getOrderId($this->request->get['order_id']);
			} elseif (isset($this->request->post['selected']) && !empty($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $order_id) {
					$orderId = $this->{$this->ll->getModel()}->getOrderId($order_id);

					if ($orderId) {
						$orderIds['ids'][] = (int)$orderId;
					}
				}
			} else {
				$this->session->data['warning'] = $this->language->get('error_cancel');

				$this->response->redirect($this->ll->getLinkExtension('order'));
			}

			if (!isset($orderIds) || empty($orderIds)) {
				$this->session->data['warning'] = $this->language->get('error_cancel');

				$this->response->redirect($this->ll->getLinkExtension('order'));
			} else {
				$result = $this->api->canceled($orderIds);

				if (!empty($result['responses'])) {
					$success = '';

					foreach ($result['responses'] as $order) {
						if ($order['success']) {
							$success .= sprintf($this->language->get('text_canceled_success'), $order['id']);
						} else {
							$errors = '';

							if (!empty($order['errors'])) {
								foreach ($order['errors'] as $error) {
									$errors .= $error['message'];
								}
							}

							$success .= sprintf($this->language->get('text_canceled_error'), $order['id'], $errors);
						}
					}

					$this->session->data['success'] = $success;
				} else {
					$this->session->data['warning'] = sprintf($this->language->get('error_canceled'), $result);
				}
			}
		} else {
			$this->session->data['warning'] = $this->language->get('error_permission');
		}

		$this->response->redirect($this->ll->getLinkExtension('order'));
	}

	public function update() {
		if ($this->ll->validate()) {
			$this->load->language($this->ll->getRoute());

			$this->load->model($this->ll->getRoute());

			if (isset($this->request->get['order_id'])) {
				$order_ids[] = $this->request->get['order_id'];
			} elseif (isset($this->request->post['selected']) && !empty($this->request->post['selected'])) {
				$order_ids = $this->request->post['selected'];
			} else {
				$this->session->data['warning'] = $this->language->get('error_update');

				$this->response->redirect($this->ll->getLinkExtension('order'));
			}

			$result = $this->updateOrdersStatus($order_ids);

			if (!empty($result)) {
				$this->session->data['success'] = sprintf($this->language->get('text_update_success'), implode(', ', $result));

				$diff = array_diff($order_ids, $result);
				
				if (!empty($diff)) {
					$this->session->data['warning'] = sprintf($this->language->get('error_update_order'), implode(', ', $diff));
				}
			} else {
				$this->session->data['warning'] = sprintf($this->language->get('error_update_order'), implode(', ', $order_ids));
			}
		} else {
			$this->session->data['warning'] = $this->language->get('error_permission');
		}

		$this->response->redirect($this->ll->getLinkExtension('order'));
	}

	protected function updateOrdersStatus($order_ids) {
		$catalog = isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG;
		$url = $catalog . 'index.php?route=' . $this->ll->getRoute() . '/trackingOrderStatus&cron_key=' . $this->config->get($this->ll->getPrefix() . '_cron_key');

		$this->load->language($this->ll->getRoute());

		$this->load->model($this->ll->getRoute());

		foreach ($order_ids as $key => $order_id) {
			$order = $this->{$this->ll->getModel()}->getOrderInfo((int)$order_id);

			if (!$order['postingNumber']) {
				if ($key == count($order_ids)) {
					$this->response->redirect($this->ll->getLinkExtension('order'));
				} else {
					continue;
				}
			}

			$articles['articles'][] = $order['postingNumber'];
			$posting_numbers[$order_id] = $order['postingNumber'];
			$orders_info[$order_id] = $order;
		}

		$results = $this->api->update_status($articles);
		$return = [];

		if (isset($results['items'])) {
			foreach ($results['items'] as $order) {
				$order_id = array_search($order['postingNumber'], $posting_numbers);
				$return[] = $order_id;

				foreach ($order['events'] as $event) {
					foreach ($this->statics->get('statuses') as $static_status) {
						if (isset($event['eventId']) && $event['eventId'] == $static_status['code']) {
							$statuses[$order_id][] = [
								'code' => $event['eventId'],
								'date' => $event['moment'],
							];

							break;
						}
					}
				}
			}

			foreach ($statuses as $order_id => $result) {
				foreach ($result as $status) {
					// проверяем, трекался ли уже этот статус
					$is_status = $this->{$this->ll->getModel()}->getOrderStatus($order_id, $status['code'], $status['date']);

					if (!$is_status) {
						$this->{$this->ll->getModel()}->addOrderStatus($order_id, $status);

						if ($this->config->get($this->ll->getPrefix() . '_trackings')) {
							foreach ($this->config->get($this->ll->getPrefix() . '_trackings') as $tracking) {
								if ($tracking['shipping_status'] == $status['code']) {
									$params = [
										'order_id'             => $order_id,
										'delivery_status_name' => $this->statics->get('statuses')[$status['code']]['title'],
										'delivery_status_date' => date('Y-m-d H:i:s', strtotime($status['date'])),
										'shipping_status'      => $tracking['shipping_status'],
										'order_status'         => $tracking['order_status'],
										'notify'               => $tracking['notify'],
										'comment'              => $tracking['comment'][$orders_info[$order_id]['language_id']],
										'notify_email'         => $tracking['notify_email'],
										'email'                => $tracking['email'][$orders_info[$order_id]['language_id']],
										'notify_sms'           => $tracking['notify_sms'],
										'sms'                  => $tracking['sms'][$orders_info[$order_id]['language_id']],
									];

									$ch = curl_init();

									curl_setopt($ch, CURLOPT_URL, $url);
									curl_setopt($ch, CURLOPT_POST, true);
									curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

									curl_exec($ch);
									curl_close($ch);
								}
							}
						}
					} else {
						$this->{$this->ll->getModel()}->updateOrderStatus($order_id, $status);
					}

					if ($this->config->get($this->ll->getPrefix() . '_alerts')) {
						foreach ($this->config->get($this->ll->getPrefix() . '_alerts') as $alert) {
							if ($alert['start_status'] == $status['code']) {
								$status_date = new DateTime(date('Y-m-d H:i:s', strtotime($status['date'])));
								$action_date = new DateTime();
								$action_date->modify('+' . (int)$alert['delay'] . ' day');
								$date_diff = date_diff($status_date, $action_date)->days;

								// разрешаем только если прошло N дней задержки
								if ($date_diff > 0) {
									continue;
								}

								if (is_array($alert['stop_status']) && !empty($alert['stop_status'])) {
									$is_status = false;

									// проверяем не получен-ли уже stop статус
									foreach ($alert['stop_status'] as $stop_status) {
										$is_status = $this->{$this->ll->getModel()}->getOrderStatus($order_id, $stop_status);

										if ($is_status) {
											break;
										}
									}

									// если получен хоть один стоп статус, то не отправляем
									if ($is_status) {
										continue;
									}
								}

								$params = [
									'order_id'             => $order_id,
									'delivery_status_name' => $this->statics->get('statuses')[$status['code']]['title'],
									'delivery_status_date' => date('Y-m-d H:i:s', strtotime($status['date'])),
									'shipping_status'      => $alert['shipping_status'],
									'notify_email'         => $alert['notify_email'],
									'email'                => $alert['email'][$orders_info[$order_id]['language_id']],
									'notify_sms'           => $alert['notify_sms'],
									'sms'                  => $alert['sms'][$orders_info[$order_id]['language_id']],
								];

								$ch = curl_init();

								curl_setopt($ch, CURLOPT_URL, $url);
								curl_setopt($ch, CURLOPT_POST, true);
								curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

								curl_exec($ch);
								curl_close($ch);
							}
						}
					}
				}
			}

			return $return;
		}
	}

	public function remove() {
		if (isset($this->request->get['order_id']) && $this->ll->validate()) {
			$this->load->language($this->ll->getRoute());

			$this->load->model($this->ll->getRoute());

			$this->{$this->ll->getModel()}->removeOrderFromModule((int)$this->request->get['order_id']);

			$this->session->data['success'] = sprintf($this->language->get('text_remove_success'), $this->request->get['order_id']);

			$this->response->redirect($this->ll->getLinkExtension('view', '&order_id=' . $this->request->get['order_id']));
		} else {
			$this->response->redirect($this->ll->getLinkExtension('order'));
		}
	}

	public function get_cron_key() {
		if ($this->ll->validate()) {
			$json['success']['cron_key'] = $this->generate_cron_key();
			$json['success']['update_url'] = (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG) . 'index.php?route=' . $this->ll->getRoute() . '/trackOrderStatus&cron_key=' . $json['success']['cron_key'];
			$json['success']['update_pvz_url'] = (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG) . 'index.php?route=' . $this->ll->getRoute() . '/updatePVZ&cron_key=' . $json['success']['cron_key'];
		} else {
			$this->load->language($this->ll->getRoute());

			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function generate_cron_key() {
		if ($this->ll->validate()) {
			return str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
		}
	}

	public function load_log() {
		$this->load->language($this->ll->getRoute());

		if ($this->ll->validate()) {
			$file = DIR_LOGS . $this->code . '.log';

			if (file_exists($file)) {
				$size = filesize($file);

				if ($size >= 5242880) {
					$json['error'] = $this->language->get('error_log_weight');
				} else {
					$log = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);

					if ($log == '') {
						$json['error'] = $this->language->get('error_log_file');
					} else {
						$json['success'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
					}
				}
			} else {
				$json['error'] = $this->language->get('error_log_file');
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function download_log() {
		$this->load->language($this->ll->getRoute());

		if ($this->ll->validate()) {
			$file = DIR_LOGS . $this->code . '.log';

			if (file_exists($file) && filesize($file) > 0) {
				$json['success'] = $this->ll->getLinkExtension('download_log_file');
			} else {
				$json['error'] = $this->language->get('error_log_file');
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function download_log_file() {
		if ($this->ll->validate()) {
			$file = DIR_LOGS . $this->code . '.log';

			if (file_exists($file) && filesize($file) > 0) {
				$this->response->addheader('Pragma: public');
				$this->response->addheader('Expires: 0');
				$this->response->addheader('Content-Description: File Transfer');
				$this->response->addheader('Content-Type: application/octet-stream');
				$this->response->addheader('Content-Disposition: attachment; filename="' . $this->ll->getPrefix() . '_' . date('Y-m-d_H-i-s', time()) . '.log"');
				$this->response->addheader('Content-Transfer-Encoding: binary');

				$this->response->setOutput(file_get_contents($file, FILE_USE_INCLUDE_PATH, null));
			}
		}
	}

	public function clear_log() {
		$this->load->language($this->ll->getRoute());

		if ($this->ll->validate()) {
			$file = DIR_LOGS . $this->code . '.log';

			$handle = fopen($file, 'w+');

			fclose($handle);

			$json['success'] = $this->language->get('text_success_clear');
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$this->load->model($this->ll->getRoute());

		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		$results = $this->{$this->ll->getModel()}->getCities($filter_name);

		foreach ($results as $result) {
			$json[] = [
				'id'   => $result['id'],
				'city' => $result['city'],
				'full' => $result['full'],
			];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {
		$this->load->model($this->ll->getExtensionModel() . '/event');

		if (version_compare(VERSION, '2.2', '>=')) {
			$event_order_add = 'catalog/model/checkout/order/addOrder/after';
			$event_order_edit = 'catalog/model/checkout/order/editOrder/after';
			$event_order_status = 'catalog/model/checkout/order/addOrderHistory/after';
		} else {
			$event_order_add = 'post.order.add';
			$event_order_edit = 'post.order.edit';
			$event_order_status = 'post.order.history.add';
		}

		$this->{'model_' . $this->ll->getExtensionModel() . '_event'}->addEvent($this->code . '_order_add', $event_order_add, $this->ll->getExt() . 'module/' . $this->code . '/addOrderAfter');
		$this->{'model_' . $this->ll->getExtensionModel() . '_event'}->addEvent($this->code . '_order_edit', $event_order_edit, $this->ll->getExt() . 'module/' . $this->code . '/editOrderAfter');
		$this->{'model_' . $this->ll->getExtensionModel() . '_event'}->addEvent($this->code . '_order_status', $event_order_status, $this->ll->getExt() . 'module/' . $this->code . '/addOrderStatusAfter');

		$this->load->model($this->ll->getRoute());

		$this->{$this->ll->getModel()}->install();
	}

	public function uninstall() {
		$this->load->model($this->ll->getExtensionModel() . '/event');

		$this->{'model_' . $this->ll->getExtensionModel() . '_event'}->deleteEvent($this->code . '_order_add');
		$this->{'model_' . $this->ll->getExtensionModel() . '_event'}->deleteEvent($this->code . '_order_edit');
		$this->{'model_' . $this->ll->getExtensionModel() . '_event'}->deleteEvent($this->code . '_order_status');

		$this->load->model($this->ll->getRoute());

		$this->{$this->ll->getModel()}->uninstall();
	}
}

class ControllerModuleLLOzonExchange extends ControllerExtensionModuleLLOzonExchange {}
