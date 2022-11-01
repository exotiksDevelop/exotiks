<?php
class ControllerExtensionModuleCdekIntegrator extends Controller {

	const VERSION = 1.0;
	private $log;
	private $api;
	private $error = array();
	private $time_execute;
	private $new_application;
	private $setting;
	private $limits = array(15, 30, 45, 60, 75);

	public function __construct($registry) {

		parent::__construct($registry);
		$this->init();
	}

	public function index() {

		$this->load->model('tool/cdektool');

		$this->checkInstall();

		if(!$this->model_tool_cdektool->check())
		{
			$this->response->redirect($this->url->link('tool/cdektool', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$this->load->language('extension/module/cdek_integrator');
		$this->load->model('extension/module/cdek_integrator');

		$rdata['heading_title'] = $this->language->get('heading_title_main');

		$rdata['text_enabled'] = $this->language->get('text_enabled');
		$rdata['text_disabled'] = $this->language->get('text_disabled');
		$rdata['text_no_results'] = $this->language->get('text_no_results');

		$rdata['entry_layout'] = $this->language->get('entry_layout');
		$rdata['entry_position'] = $this->language->get('entry_position');
		$rdata['entry_status'] = $this->language->get('entry_status');
		$rdata['entry_sort_order'] = $this->language->get('entry_sort_order');

		$rdata['column_dispatch_number'] = $this->language->get('column_dispatch_number');
		$rdata['column_dispatch_total_orders'] = $this->language->get('column_dispatch_total_orders');
		$rdata['column_dispatch_date'] = $this->language->get('column_dispatch_date');

		$rdata['button_cancel'] = $this->language->get('button_cancel');
		$rdata['button_option'] = $this->language->get('button_option');
		$rdata['button_new_order'] = $this->language->get('button_new_order');

 		if (isset($this->error['warning'])) {
			$rdata['error_warning'] = $this->error['warning'];
		} else {
			$rdata['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$rdata['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$rdata['success'] = '';
		}

  		$rdata['breadcrumbs'] = array();

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
   		);

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_extension'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
   		);


		$title = $this->language->get('heading_title_bk_main');

		$new_orders = 0;

		if (!$this->new_application) {

			$exdata = array(
				'filter_dispatch' => true
			);

			if (!empty($this->setting['new_order_status_id'])) {
				$exdata['filter_order_status_id']	= $this->setting['new_order_status_id'];
			}

			if (!empty($this->setting['new_order'])) {
				$exdata['filter_new_order'] = $this->setting['new_order'];
			}

			if (!empty($this->setting['shipping_method'])) {
				$exdata['filter_shipping'] = $this->setting['shipping_method'];
			}

			if (!empty($this->setting['payment_method'])) {
				$exdata['filter_payment'] = $this->setting['payment_method'];
			}

			$new_orders = $this->model_extension_module_cdek_integrator->getTotalOrders($exdata);

			if ($new_orders)  {
				$title .= ' (' . $new_orders . ')';
			}

		} else { // first load
			$rdata['attention'] = 'Для работы модуля необходимо выполнить настройку.';
		}

		$rdata['total'] = $new_orders;

		$this->document->setTitle($title);

		$rdata['dispatches'] = array();

		$exdata = array(
			'limit' => 6,
			'sort'	=> 'd.date',
			'order' => 'DESC'
		);

		$results = $this->model_extension_module_cdek_integrator->getDispatchList($exdata);

		foreach ($results as $dispatch_info) {

			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('extension/module/cdek_integrator/dispatchView', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $dispatch_info['order_id'], 'SSL')
			);

			$rdata['dispatches'][] = array(
				'order_id'				=> $dispatch_info['order_id'],
				'dispatch_number'		=> $dispatch_info['dispatch_number'],
				'act_number'			=> $dispatch_info['act_number'],
				'date'					=> $this->formatDate($dispatch_info['date']),
				'city_name'				=> $dispatch_info['city_name'],
				'recipient_city_name'	=> $dispatch_info['recipient_city_name'],
				'status'				=> $dispatch_info['status_description'],
				'status_date'			=> $this->formatDate($dispatch_info['status_date']),
				'cost'					=> (float)$dispatch_info['delivery_cost'] ? $this->currency->format($dispatch_info['delivery_cost'], $this->config->get('config_currency')) : 0,
				'sync'					=> $this->url->link('extension/module/cdek_integrator/dispatchSync', 'user_token=' . $this->session->data['user_token'] . '&target=list&order_id=' . $dispatch_info['order_id'], 'SSL'),
				'action'				=> $action
			);

		}

		$rdata['dispatch_list'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$rdata['order'] = $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$rdata['option'] = $this->url->link('extension/module/cdek_integrator/option', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$rdata['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$rdata['header'] = $this->load->controller('common/header');
		$rdata['column_left'] = $this->load->controller('common/column_left');
		$rdata['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->templateOutput('cdek_integrator', $rdata));
	}

	private function formatDate($timestamp, $time = TRUE, $correct = TRUE) {

		$send_time = date('d.m.Y', $timestamp);

		if (date('d.n.Y') == $send_time) {
			$date = $this->language->get('text_today');
		} elseif (date('d.n.Y', strtotime('-1 day')) == $send_time) {
			$date = $this->language->get('text_yesterday');
		} elseif (date('Y') == date('Y', $timestamp)) {
			$date = date('j', $timestamp) . ' ' . $this->getMonth(date('n', $timestamp));
		} else {
			$date = date('j', $timestamp) . ' ' . $this->getMonth(date('n', $timestamp)) . ' ' . date('Y', $timestamp);
		}

		if ($time) {
			$date .= ', ' . date('H:i', $timestamp);
		}

		if ($correct && date('Z', $timestamp)) {
			$date .= ' <strong>UTC' . date('P', $timestamp) . '</strong>';
		}

		return $date;
	}

	public function getMonth($number) {

		$month = '';

		switch ($number) {
			case 1:
				$month = 'января';
				break;
			case 2:
				$month = 'февраля';
				break;
			case 3:
				$month = 'марта';
				break;
			case 4:
				$month = 'апреля';
				break;
			case 5:
				$month = 'мая';
				break;
			case 6:
				$month = 'июня';
				break;
			case 7:
				$month = 'июля';
				break;
			case 8:
				$month = 'августа';
				break;
			case 9:
				$month = 'сентября';
				break;
			case 10:
				$month = 'октября';
				break;
			case 11:
				$month = 'ноября';
				break;
			case 12:
				$month = 'декабря';
				break;
		}

		return $month;
	}


	public function order() {

		$this->document->setTitle($this->language->get('heading_title_order'));

		$this->load->model('extension/module/cdek_integrator');

		$this->orderList();
	}

	private function orderList() {

		if ($this->new_application) {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = null;
		}

		if (!empty($this->setting['new_order_status_id']) && (!$filter_order_status_id || !in_array($filter_order_status_id, $this->setting['new_order_status_id']))) {
			$filter_order_status_id = $this->setting['new_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit']) && in_array($this->request->get['limit'], $this->limits)) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = reset($this->limits);
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

  		$rdata['breadcrumbs'] = array();

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
   		);

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_extension'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
   		);

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_bk_main'),
			'href'      => $this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'),
   		);

		$rdata['create'] = html_entity_decode($this->url->link('extension/module/cdek_integrator/createOrder', 'user_token=' . $this->session->data['user_token'], 'SSL'), ENT_QUOTES, 'UTF-8');
		$rdata['cancel'] = $this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$rdata['orders'] = array();

		$exdata = array(
			'filter_order_id'			=> $filter_order_id,
			'filter_customer'			=> $filter_customer,
			'filter_order_status_id'	=> $filter_order_status_id,
			'filter_total'				=> $filter_total,
			'filter_date_added'			=> $filter_date_added,
			'filter_new_order'			=> $this->setting['new_order'],
			'filter_dispatch'			=> TRUE,
			'sort'						=> $sort,
			'order'						=> $order,
			'start'						=> ($page - 1) * $limit,
			'limit'						=> $limit
		);

		if (!empty($this->setting['shipping_method'])) {
			$exdata['filter_shipping'] = $this->setting['shipping_method'];
		}

		if (!empty($this->setting['payment_method'])) {
			$exdata['filter_payment'] = $this->setting['payment_method'];
		}

		$results = $this->model_extension_module_cdek_integrator->getOrders($exdata);

		$order_total = $this->model_extension_module_cdek_integrator->getTotalOrders($exdata);

		/*if (!$order_total) {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}*/

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('button_create'),
				'icon' => '<i class="fa fa-truck"></i>',
				'href' => $this->url->link('extension/module/cdek_integrator/createOrder', 'user_token=' . $this->session->data['user_token'] . '&orders[]=' . $result['order_id'] . $url, 'SSL')
			);

			$action[] = array(
				'text' => $this->language->get('text_view'),
				'icon' => '<i class="fa fa-info"></i>',
				'href' => $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
			);

			$rdata['orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$rdata['heading_title'] = $this->language->get('heading_title_order');

		$rdata['text_no_results'] = $this->language->get('text_no_results');
		$rdata['text_missing'] = $this->language->get('text_missing');

		$rdata['column_order_id'] = $this->language->get('column_order_id');
    	$rdata['column_customer'] = $this->language->get('column_customer');
		$rdata['column_status'] = $this->language->get('column_status');
		$rdata['column_total'] = $this->language->get('column_total');
		$rdata['column_date_added'] = $this->language->get('column_date_added');
		$rdata['column_action'] = $this->language->get('column_action');

		$rdata['button_create'] = $this->language->get('button_create');
		$rdata['button_cancel'] = $this->language->get('button_cancel');
		$rdata['button_filter'] = $this->language->get('button_filter');

		$rdata['token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$rdata['error_warning'] = $this->error['warning'];
		} else {
			$rdata['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$rdata['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$rdata['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$rdata['sort_order'] = $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.order_id' . $url, 'SSL');
		$rdata['sort_customer'] = $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url, 'SSL');
		$rdata['sort_status'] = $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, 'SSL');
		$rdata['sort_total'] = $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.total' . $url, 'SSL');
		$rdata['sort_date_added'] = $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$rdata['limits'] = array();

		foreach ($this->limits as $item) {
			$rdata['limits'][$item] = $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'] . '&limit=' . $item . $url, 'SSL');
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$rdata['pagination'] = $pagination->render();

		$rdata['filter_order_id'] = $filter_order_id;
		$rdata['filter_customer'] = $filter_customer;
		$rdata['filter_order_status_id'] = $filter_order_status_id;
		$rdata['filter_total'] = $filter_total;
		$rdata['filter_date_added'] = $filter_date_added;

		$this->load->model('localisation/order_status');

    	$rdata['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (!empty($this->setting['new_order_status_id'])) {

			foreach ($rdata['order_statuses'] as $key => $order_status) {
				if (!in_array($order_status['order_status_id'], $this->setting['new_order_status_id'])) {
					unset($rdata['order_statuses'][$key]);
				}
			}

		}

		$rdata['sort'] = $sort;
		$rdata['order'] = strtolower($order);
		$rdata['limit'] = $limit;

		$rdata['header'] = $this->load->controller('common/header');
		$rdata['column_left'] = $this->load->controller('common/column_left');
		$rdata['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->templateOutput('order_list', $rdata));
	}

	public function createOrder() {

		if (empty($this->request->get['orders']) || !array_filter($this->request->get['orders'])) {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$this->load->model('extension/module/cdek_integrator');
		$this->load->model('sale/order');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateOrderFrom()) {

			$component = $this->api->loadComponent('orders');

			$component->setNumber($this->request->post['number']);

			$cdek_orders_post = $this->request->post['cdek_orders'];

			foreach ($cdek_orders_post as $order_id => $order_data) {

				$telephone = $order_data['recipient_telephone'];
				$telephone = trim($telephone);
				$telephone = preg_replace('/[^0-9+]/isu', '', $telephone);

				if (strpos($telephone, '8') !== 0) {
					$telephone = preg_replace('/^(?:\+7|7)/isu', '', $telephone);
					$telephone = '8' . $telephone;
				}

				$cdek_orders_post[$order_id]['recipient_telephone']	= $telephone;

				$this->request->post['cdek_orders'][$order_id]['currency'] = $this->setting['currency'];

				foreach ($order_data['package'] as $package_id => $package) {

					$additional_weight = $this->getPackingWeight($package['weight']);

					if ((float)$additional_weight['weight']) {

						if ($this->setting['packing_prefix'] == '+') {
							$cdek_orders_post[$order_id]['package'][$package_id]['weight'] += $additional_weight['weight'];
						} else {
							$cdek_orders_post[$order_id]['package'][$package_id]['weight'] -= (float)min($additional_weight['weight'], $package['weight']);
						}


					}

				}

			}

			$component->setOrders($cdek_orders_post);
			$response = $this->api->sendData($component);
			if ($this->api->error) {

				foreach ($this->api->error as $order_id => $order_errors) {

					foreach ($order_errors as $error_key => $error_message) {

						switch($error_key) {
							case 'ERR_ORDER_DUBL_EXISTS':
								$this->error['warning'][] = sprintf($this->language->get('error_order_dubl_exists'),$order_id);
								break;
							case 'ERR_NOT_FOUND_TARIFFTYPECODE':
								$this->error['cdek_orders'][$order_id]['tariff_id'] = $this->language->get('error_not_found_tarifftypecode');
								break;
							case 'ERR_INVALID_INTAKESERVICE_TOCITY':
								$this->error['cdek_orders'][$order_id]['add_service'] = $error_message . '!';
								break;
							case 'ERR_INVALID_INTAKESERVICE':
								$this->error['cdek_orders'][$order_id]['add_service'] = $error_message . '!';
								break;
							case 'ERR_INVALID_SERVICECODE':
								$this->error['cdek_orders'][$order_id]['add_service'] = $this->language->get('error_invalid_srvicecode');
								break;
							case 'ERR_SENDCITYCODE':
								$this->error['cdek_orders'][$order_id]['city_id'] = $this->language->get('error_sendcitycode');
								break;
							case 'ERR_DATABASE':
								$this->error['warning']['warning'] = $this->language->get('error_database');
								break;
							case 'ERR_AUTH':
								$this->error['warning']['warning'] = $this->language->get('error_auth');
								break;
							case 'ERR_CALLCOURIER_CITY':
								$this->error['cdek_orders'][$order_id]['courier']['city_id'] = $this->language->get('error_callcourier_city');
								break;
							case 'ERR_CALLCOURIER_DATETIME':
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_callcourier_datetime');
								break;
							case 'ERR_CALLCOURIER_DATE_DUBL':
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_callcourier_date_dubl');
								break;
							case 'ERR_CALLCOURIER_DATE_EXISTS':
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_callcourier_date_exists');
								break;;
							case 'ERR_CALLCOURIER_TIME':
								$this->error['cdek_orders'][$order_id]['courier']['time'] = $this->language->get('error_callcourier_time');
								break;
							case 'ERR_CALLCOURIER_TIMELUNCH':
								$this->error['cdek_orders'][$order_id]['courier']['lunch'] = $this->language->get('error_callcourier_timelunch');
								break;
							case 'ERR_CALLCOURIER_TIME_INTERVAL':
								$this->error['cdek_orders'][$order_id]['courier']['time'] = $this->language->get('error_callcourier_time_interval');
								break;
							case 'ERR_CALL_DUBL':
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_call_dubl');
								break;
							case 'ERR_CASH_NO':
								$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $this->language->get('error_cash_no'));
								break;
							case 'ERR_INVALID_ADDRESS_DELIVERY':
								$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $this->language->get('error_invalid_address_delivery'));
								break;
							case 'ERR_INVALID_SIZE':
								$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $this->language->get('error_invalid_size'));
								break;
							case 'ERR_PVZ_WEIGHT_LIMIT':
								$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $this->language->get('error_pvz_weigt_limit'));
								break;
							default:
								if ($error_message != '') {
									$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $error_message);
								}
						}

					}
				}
			}

			$date = '';
			$cdek_orders = array();

			if (isset($response->Order)) {

				foreach ($response->Order as $order) {

					$attributes = $order->attributes();

					if (isset($attributes->DispatchNumber)) {

						$order_id = (int)$attributes->Number;

						if (array_key_exists($order_id, $this->request->post['cdek_orders'])) {

							$order_info = $this->request->post['cdek_orders'][$order_id];

							$order_info += array(
								'dispatch_number'	=> (string)$attributes->DispatchNumber,
							);

							$cdek_orders[$order_id] = $order_info;

						}


					}

				}

			} elseif(!$this->api->error) {
				$this->error['warning'][] = 'Не удалось получить ответ от сервера СДЭК.';
			}

			if (count($cdek_orders)) {

				$count = count($cdek_orders);
				$all = ($count == count($this->request->post['cdek_orders']));
				$orders = $all ? $cdek_orders : array_intersect_key($cdek_orders, $this->request->post['cdek_orders']);

				if (!$date) $date = time();

				$default_timezone = date_default_timezone_get();
				date_default_timezone_set('UTC');

				$defoult_time = time();

				date_default_timezone_set($default_timezone);

				foreach ($orders as $order_id => $order_data) {

					if (!isset($order_data['status_id'])) {

						$orders[$order_id]['status_id'] = 1;

						$status_history = array();
						$status_history[] = array(
							'date'			=> $defoult_time,
							'status_id'		=> $orders[$order_id]['status_id'],
							'description'	=> 'Создан',
							'city_code'		=> $this->setting['city_id'],
							'city_name'		=> $this->setting['city_name']
						);

						$orders[$order_id]['status_history'] = $status_history;
					}

				}

				$exdata = array(
					'number'	=> $this->request->post['number'],
					'date'		=> $date,
					'orders'	=> $orders
				);

				$this->model_extension_module_cdek_integrator->addDispatch($exdata);

				$this->session->data['success'] = 'Отгружен' . $this->declination($count, array('', 'о', 'о')) . ' ' . $count . ' заказ' . $this->declination($count, array('', 'а', 'ов')) . '!';

				if ($all) {

					$url = '';

					if (isset($this->request->get['filter_order_id'])) {
						$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
					}

					if (isset($this->request->get['filter_customer'])) {
						$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['filter_order_status_id'])) {
						$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
					}

					if (isset($this->request->get['filter_total'])) {
						$url .= '&filter_total=' . $this->request->get['filter_total'];
					}

					if (isset($this->request->get['filter_date_added'])) {
						$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
					}

					if (isset($this->request->get['filter_date_modified'])) {
						$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
					}

					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}

					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}

					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}

					if (isset($this->request->get['limit'])) {
						$url .= '&limit=' . $this->request->get['limit'];
					}

					$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
				}

			}

		}

		$this->document->setTitle($this->language->get('heading_title_new_order'));

		$rdata['heading_title'] = $this->language->get('heading_title_new_order');

		$rdata['text_order_n'] = $this->language->get('text_order_n');
		$rdata['text_city'] = $this->language->get('text_city');
		$rdata['text_order_date'] = $this->language->get('text_order_date');
		$rdata['api_ajax_url'] = $this->getInfo()->getAjaxUrl();
		$rdata['text_order_count_items'] = $this->language->get('text_order_count_items');
		$rdata['text_select'] = $this->language->get('text_select');
		$rdata['text_order_id'] = $this->language->get('text_order_id');
		$rdata['text_order_total'] = $this->language->get('text_order_total');
		$rdata['text_city'] = $this->language->get('text_city');
		$rdata['text_customer_shipping_method'] = $this->language->get('text_customer_shipping_method');
		$rdata['text_shipping_address'] = $this->language->get('text_shipping_address');
		$rdata['text_customer_shipping_address'] = $this->language->get('text_customer_shipping_address');
		$rdata['text_courier_address'] = $this->language->get('text_courier_address');
		$rdata['text_courier'] = $this->language->get('text_courier');
		$rdata['text_from'] = $this->language->get('text_from');
		$rdata['text_to'] = $this->language->get('text_to');
		$rdata['text_short_length'] = $this->language->get('text_short_length');
		$rdata['text_short_width'] = $this->language->get('text_short_width');
		$rdata['text_short_height'] = $this->language->get('text_short_height');
		$rdata['text_attention'] = $this->language->get('text_attention');
		$rdata['text_courier_day'] = $this->language->get('text_courier_day');
		$rdata['text_courier_hour_range'] = $this->language->get('text_courier_hour_range');
		$rdata['text_title_schedule'] = $this->language->get('text_title_schedule');
		$rdata['text_title_orders'] = $this->language->get('text_title_orders');
		$rdata['text_help_shedule'] = $this->language->get('text_help_shedule');
		$rdata['text_help_shedule_detail'] = $this->language->get('text_help_shedule_detail');
		$rdata['text_package_n'] = $this->language->get('text_package_n');
		$rdata['text_user_comment'] = $this->language->get('text_user_comment');
		$rdata['text_none'] = $this->language->get('text_none');

		$rdata['entry_tariff'] = $this->language->get('entry_tariff');
		$rdata['entry_delivery_recipient_cost'] = $this->language->get('entry_delivery_recipient_cost');
		$rdata['entry_delivery_recipient_vat_rate'] = $this->language->get('entry_delivery_recipient_vat_rate');
		$rdata['entry_delivery_recipient_vat_sum'] = $this->language->get('entry_delivery_recipient_vat_sum');
		$rdata['entry_seller_name'] = $this->language->get('entry_seller_name');
		$rdata['entry_comment'] = $this->language->get('entry_comment');
		$rdata['entry_recipient_name'] = $this->language->get('entry_recipient_name');
		$rdata['entry_recipient_telephone'] = $this->language->get('entry_recipient_telephone');
		$rdata['entry_recipient_email'] = $this->language->get('entry_recipient_email');
		$rdata['entry_recipient_city'] = $this->language->get('entry_recipient_city');
		$rdata['entry_street'] = $this->language->get('entry_street');
		$rdata['entry_house'] = $this->language->get('entry_house');
		$rdata['entry_flat'] = $this->language->get('entry_flat');
		$rdata['entry_pvz'] = $this->language->get('entry_pvz');
		$rdata['entry_brcode'] = $this->language->get('entry_brcode');
		$rdata['entry_pack'] = $this->language->get('entry_pack');
		$rdata['entry_package'] = $this->language->get('entry_package');
		$rdata['entry_order_weight'] = $this->language->get('entry_order_weight');
		$rdata['entry_courier_call'] = $this->language->get('entry_courier_call');
		$rdata['entry_courier_date'] = $this->language->get('entry_courier_date');
		$rdata['entry_courier_time'] = $this->language->get('entry_courier_time');
		$rdata['entry_courier_lunch'] = $this->language->get('entry_courier_lunch');
		$rdata['entry_courier_send_phone'] = $this->language->get('entry_courier_send_phone');
		$rdata['entry_courier_sender_name'] = $this->language->get('entry_courier_sender_name');
		$rdata['entry_add_service'] = $this->language->get('entry_add_service');
		$rdata['entry_attempt_new_address'] = $this->language->get('entry_attempt_new_address');
		$rdata['entry_attempt_recipient_name'] = $this->language->get('entry_attempt_recipient_name');
		$rdata['entry_attempt_phone'] = $this->language->get('entry_attempt_phone');
		$rdata['entry_cod'] = $this->language->get('entry_cod');
		$rdata['entry_currency'] = $this->language->get('entry_currency');
		$rdata['entry_currency_cod'] = $this->language->get('entry_currency_cod');

		$rdata['column_title'] = $this->language->get('column_title');
		$rdata['column_weight'] = $this->language->get('column_weight');
		$rdata['column_price'] = $this->language->get('column_price');
		$rdata['column_payment'] = $this->language->get('column_payment');
		$rdata['column_amount'] = $this->language->get('column_amount');
		$rdata['column_cost'] = $this->language->get('column_cost');
		$rdata['column_date'] = $this->language->get('column_date');
		$rdata['column_time'] = $this->language->get('column_time');
		$rdata['column_additional'] = $this->language->get('column_additional');

		$rdata['tab_data'] = $this->language->get('tab_data');
		$rdata['tab_recipient'] = $this->language->get('tab_recipient');
		$rdata['tab_package'] = $this->language->get('tab_package');
		$rdata['tab_schedule'] = $this->language->get('tab_schedule');
		$rdata['tab_courier'] = $this->language->get('tab_courier');
		$rdata['tab_additional'] = $this->language->get('tab_additional');

		$rdata['button_send'] = $this->language->get('button_send');
		$rdata['button_cancel'] = $this->language->get('button_cancel');
		$rdata['button_delete'] = $this->language->get('button_delete');
		$rdata['button_add_attempt'] = $this->language->get('button_add_attempt');

		$rdata['boolean_variables'] = array($this->language->get('text_no'), $this->language->get('text_yes'));

		if (isset($this->session->data['success'])) {
			$rdata['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$rdata['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$rdata['error_warning'] = $this->error['warning'];
		} else {
			$rdata['error_warning'] = array();
		}

		$rdata['error'] = $this->error;

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$rdata['breadcrumbs'] = array();

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
   		);

   		$rdata['breadcrumbs'][] =array(
       		'text'      => $this->language->get('text_extension'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
   		);

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_bk_main'),
			'href'      => $this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'),
   		);

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_order'),
			'href'      => $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
   		);


		$rdata['token'] = $this->session->data['user_token'];

		$rdata['currency_list'] = $this->getInfo()->getCurrencyList();

		$url = '';

		foreach ($this->request->get['orders'] as $order_id) {
			$url .= '&orders[]=' . $order_id;
		}

		$rdata['action'] = $this->url->link('extension/module/cdek_integrator/createOrder', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		$rdata['cancel'] = $this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$rdata['city_default'] = $this->setting['city_default'];

		if ($rdata['city_default']) {

			$rdata['city_id'] = $this->setting['city_id'];
			$rdata['city_name'] = $this->setting['city_name'];

		}

		$rdata['cdek_orders'] = array();
		$additional_cost_totals = array('shipping', 'cdek');

		foreach ($this->request->get['orders'] as $order_id)
		{
			$order_to_sdek = $this->model_extension_module_cdek_integrator->getOrderToSdek($order_id);

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info && !$this->model_extension_module_cdek_integrator->orderExists($order_id)) {

				$additional_cost = 0;

				$totals = $this->model_sale_order->getOrderTotals($order_id);

				foreach ($totals as $totals_key => $totals_value) {
					$totals[$totals_key]['text'] = $this->currency->format($totals_value['value'], $order_info['currency_code']);

					if (in_array($totals_value['code'], $additional_cost_totals)) {
						$additional_cost+= $totals_value['value'];
					}
				}

				$post_data = !empty($this->request->post['cdek_orders'][$order_id]) ? $this->request->post['cdek_orders'][$order_id] : array();

				if (isset($post_data['currency'])) {
					$currency = $post_data['currency'];
				} elseif (in_array($order_info['currency_code'], $rdata['currency_list'])) {
					$currency = $order_info['currency_code'];
				} else {
					$currency = $this->setting['currency'];
				}

				$exdata = array(
					'cod'					=> isset($post_data['cod']) ? $post_data['cod'] : $this->setting['cod'],
					'currency_cod'			=> isset($post_data['currency_cod']) ? $post_data['currency_cod'] : $this->setting['currency_agreement'],
					'currency'				=> $currency,
					'city_id'				=> $this->setting['city_id'],
					'city_name'				=> $this->setting['city_name'],
					'recipient_name'		=> isset($post_data['recipient_name']) ? $post_data['recipient_name'] : $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'],
					'recipient_telephone'	=> isset($post_data['recipient_telephone']) ? $post_data['recipient_telephone'] : $order_info['telephone'],
					'recipient_email'		=> isset($post_data['recipient_email']) ? $post_data['recipient_email'] : $order_info['email'],
					'shipping_address'		=> $this->fomatAddress($order_info),
					'total'					=> $rdata['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']),
					'packages'				=> array(),
					'totals'				=> $totals
				);

				$telephone = $exdata['recipient_telephone'];
				$telephone = trim($telephone);
				$telephone = preg_replace('/[^0-9+]/isu', '', $telephone);

				if (strpos($telephone, '8') !== 0) {
					$telephone = preg_replace('/^(?:\+7|7)/isu', '', $telephone);
					$telephone = '8' . $telephone;
				}

				$exdata['recipient_telephone'] = $telephone;

				$packages = array(
					1 => $this->model_extension_module_cdek_integrator->getOrderProducts($order_id)
				);

				foreach ($packages as $package_id => $products) {

					$package_post = isset($post_data['package'][$package_id]) ? $post_data['package'][$package_id] : array();

					$exdata['packages'][$package_id] = array(
						'item'				=> array(),
						'weight'			=> 0
					);

					foreach (array('brcode', 'pack', 'size_a', 'size_b', 'size_c') as $item) {
						$exdata['packages'][$package_id][$item] = isset($package_post[$item]) ? $package_post[$item] : '';
					}

					if (!empty($this->setting['replace_items'])) {

						$total_weight = 0;

						foreach ($products as $product_row => $order_product) {

							if (isset($order_product['weight'])) {

								if ($order_product['weight_class_id'] != $this->setting['weight_class_id']) {
									$weight = $this->weight->convert($order_product['weight'], $order_product['weight_class_id'], $this->setting['weight_class_id']);
								} else {
									$weight = $order_product['weight'];
								}

							} else {
								$weight = 0;
							}

							$product_options = $this->model_extension_module_cdek_integrator->getOrderProductOptions($order_product['order_product_id']);

							foreach ($product_options as $product_option) {

								if (!empty($product_option['weight'])) {

									if ($order_product['weight_class_id'] != $this->setting['weight_class_id']) {
										$option_weight = $this->weight->convert($product_option['weight'], $order_product['weight_class_id'], $this->setting['weight_class_id']);
									} else {
										$option_weight = $product_option['weight'];
									}

									if ($product_option['weight_prefix'] == '+') {
										$weight += $option_weight;
									} else {
										$weight -= $option_weight;
									}

									if ($weight < 0) {
										$weight = 0;
									}

								}

							}

							$total_weight += $weight * $order_product['quantity'];
						}

						$products = array();
						$products[] = array(
							'order_product_id'	=> 1,
							'product_id'		=> 1,
							'name'				=> $this->setting['replace_item_name'],
							'model'				=> '',
							'weight'			=> $total_weight,
							'weight_class_id'	=> $this->setting['weight_class_id'],
							'option'			=> array(),
							'quantity'			=> ($this->setting['replace_item_amount'] ? $this->setting['replace_item_amount'] : 1),
							'price'				=> $this->setting['replace_item_cost'],
							'payment'			=> $this->setting['replace_item_payment'],
							'payment_vat_rate'	=> 0,
							'payment_vat_sum'	=> 0,
							'total'				=> '',
							'tax'				=> 0
						);

					}



					$total_weight = 0;

					foreach ($products as $product_row => $order_product) {

						$package_item_post = isset($package_post['item'][$product_row]) ? $package_post['item'][$product_row] : array();

						if (isset($order_product['weight'])) {

							if ($order_product['weight_class_id'] != $this->setting['weight_class_id']) {
								$weight = $this->weight->convert($order_product['weight'], $order_product['weight_class_id'], $this->setting['weight_class_id']);
							} else {
								$weight = $order_product['weight'];
							}

						} else {
							$weight = 0;
						}

						$product_options = $this->model_extension_module_cdek_integrator->getOrderProductOptions($order_product['order_product_id']);

						$product_name = $order_product['name'];

						$option_values = array();

						foreach ($product_options as $product_option) {

							if (!empty($product_option['weight'])) {

								if ($order_product['weight_class_id'] != $this->setting['weight_class_id']) {
									$option_weight = $this->weight->convert($product_option['weight'], $order_product['weight_class_id'], $this->setting['weight_class_id']);
								} else {
									$option_weight = $product_option['weight'];
								}

								if ($product_option['weight_prefix'] == '+') {
									$weight += $option_weight;
								} else {
									$weight -= $option_weight;
								}

								if ($weight < 0) {
									$weight = 0;
								}

							}

							if ($product_option['type'] != 'file') {
								$option_values[] = $product_option['name'] . ': ' . $product_option['value'];
							}

						}

						if (!empty($option_values)) {
							$product_name .= '(' . implode(', ', $option_values) . ')';
						}

						$total_weight += $weight * $order_product['quantity'];

						$item_data = array(
							'order_product_id'	=> $order_product['order_product_id'],
							'product_id'		=> $order_product['product_id'],
							'name'				=> $product_name,
							'weight'			=> isset($package_item_post['weight']) ? $package_item_post['weight'] : $weight,
							'option'			=> $this->model_sale_order->getOrderOptions($order_id, $order_product['order_product_id']),
							'quantity'			=> isset($package_item_post['amount']) ? $package_item_post['amount'] : $order_product['quantity'],
							'price'				=> isset($package_item_post['cost']) ? $package_item_post['cost'] : $order_product['price'],
							'payment'			=> isset($package_item_post['payment']) ? $package_item_post['payment'] : (isset($order_product['payment']) ? $order_product['payment'] : $order_product['price']),
							'payment_vat_rate'			=> isset($package_item_post['payment_vat_rate']) ? $package_item_post['payment_vat_rate'] : (isset($order_product['payment_vat_rate']) ? $order_product['payment_vat_rate'] : 0),
							'payment_vat_sum'			=> isset($package_item_post['payment_vat_sum']) ? $package_item_post['payment_vat_sum'] : (isset($order_product['payment_vat_sum']) ? $order_product['payment_vat_sum'] : 0),
							'total'				=> $order_product['total'],
							'tax'				=> $order_product['tax']
						);

						$item_data['total'] = $this->currency->format(((int)$item_data * $item_data['price']), $order_info['currency_code'], $order_info['currency_value']) . ' / ' . $this->currency->format(((int)$item_data * $item_data['payment']), $order_info['currency_code'], $order_info['currency_value']);

						$exdata['packages'][$package_id]['item'][] = $item_data;

					}

					if (isset($package_post['weight'])) {
						$exdata['packages'][$package_id]['weight'] = $package_post['weight'];
					} else {
						$exdata['packages'][$package_id]['weight'] = $total_weight;
					}

					$exdata['packages'][$package_id]['additional_weight'] = $this->getPackingWeight($exdata['packages'][$package_id]['weight']);
				}

				if (isset($post_data['courier'])) {
					$exdata['courier'] = $post_data['courier'];
				} else {
					$exdata['courier'] = array(
						'city_id'	=> $this->setting['city_id'],
						'city_name'	=> $this->setting['city_name'],
						'send_phone'	=> $this->config->get('config_telephone'),
						'sender_name'	=> $this->config->get('config_owner')
					);
				}

				if (!empty($post_data)) {

					foreach (array('city_id', 'city_name', 'tariff_id', 'mode_id', 'recipient_city_id', 'recipient_city_name', 'delivery_recipient_cost', 'delivery_recipient_vat_rate', 'delivery_recipient_vat_sum', 'seller_name', 'cdek_comment', 'package', 'schedule', 'add_service') as $item) {

						if (isset($post_data[$item])) {
							$exdata[$item] = $post_data[$item];
						}

					}

					foreach (array('street', 'house', 'flat', 'pvz_code') as $item) {

						if (isset($post_data['address'][$item])) {
							$exdata['address'][$item] = $post_data['address'][$item];
						}

					}

					if (!empty($exdata['recipient_city_id'])) {

						$pvz_list = $this->getPVZ($exdata['recipient_city_id']);

						if (isset($pvz_list['List'])) {
							$exdata['pvz_list'] = $pvz_list['List'];
						}

					}

				} elseif ($order_info['shipping_city'] != '') {

					if (!empty($this->setting['delivery_recipient_cost'])) {
						$exdata['delivery_recipient_cost'] = $this->setting['delivery_recipient_cost'];
					} elseif ($additional_cost) {
						$exdata['delivery_recipient_cost'] = $additional_cost;
					}

					if (!empty($this->setting['seller_name'])) {
						$exdata['seller_name'] = $this->setting['seller_name'];
					}

					if (!empty($this->setting['add_service'])) {
						$exdata['add_service'] = array_flip($this->setting['add_service']);
					}

					$city_info = $this->getCity($order_info['shipping_city'], $order_info['shipping_country_id'], $order_info['shipping_zone_id']);

					if (isset($city_info['id'])) {

						$exdata += array(
							'recipient_city_id'		=> $city_info['id'],
							'recipient_city_name'	=> $city_info['name']
						);

						$pvz_list = $this->getPVZ($city_info['id']);

						if (!empty($pvz_list['List'])) {
							$exdata['pvz_list'] = $pvz_list['List'];
						}

					}

					if($order_to_sdek['cityId'])
					{
						$city_info = $this->model_extension_module_cdek_integrator->getCityById((int)$order_to_sdek['cityId']);
						$exdata += array(
							'recipient_city_id'		=> $order_to_sdek['cityId'],
							'recipient_city_name'	=> $city_info['name']
						);

						$pvz_list = $this->getPVZ($order_to_sdek['cityId']);

						if (!empty($pvz_list['List'])) {
							$exdata['pvz_list'] = $pvz_list['List'];
						}
					}

					if (isset($order_info['shipping_code'])) {

						$parts = explode('.', $order_info['shipping_code']);

						if ($parts[0] == 'cdek' && !empty($parts[1])) {

							$tariff_parts = explode('_', $parts[1]);

							if (count($tariff_parts) == 3) {

								list(,$tariff_id, $pvz_code) = $tariff_parts;

								$tariff_info = $this->getInfo()->getTariffInfo($tariff_id);
								if($order_to_sdek['pvz_code'])
								{
									$pvz_code=$order_to_sdek['pvz_code'];
								}
								if ($tariff_info) {

									$exdata += array(
										'tariff_id' => $tariff_id,
										'mode_id'	=> $tariff_info['mode_id'],
										'address'	=> array(
											'pvz_code'	=> $pvz_code
										)
									);

								}

							}

						}

					}

				}

				if (!empty($exdata['pvz_list']) && !empty($exdata['address']['pvz_code'])) {

					foreach ($pvz_list['List'] as $pvz_info) {

						if ($pvz_info['Code'] == $exdata['address']['pvz_code']) {

							$exdata['pvz_info'] = $pvz_info;

							break;
						}

					}

				}

				$rdata['cdek_orders'][] = $exdata + $order_info;
			}

		}

		$rdata['tariff_list'] = $this->getInfo()->getTariffList();
		$rdata['vat_rate_list'] = $this->getInfo()->getVatRates();
		$rdata['add_cervices'] = $this->getInfo()->getAddServices();

		$rdata['date'] = $this->getDateExecuted('Y-m-d');

		$rdata['number'] = uniqid();

		$rdata['login'] = $this->setting['account'];

		$default_timezone = date_default_timezone_get();
		date_default_timezone_set('UTC');

		$rdata['pass'] = $this->setting['secure_password'];

		date_default_timezone_set($default_timezone);

		$rdata['total'] = count($rdata['cdek_orders']);

		if (!$rdata['total']) {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator/order', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$rdata['header'] = $this->load->controller('common/header');
		$rdata['column_left'] = $this->load->controller('common/column_left');
		$rdata['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->templateOutput('order_form', $rdata));
	}

	public function option() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateOption()) {

			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('cdek_integrator_setting', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->get['redirect'])) {
				$redirect = $this->url->link('extension/module/cdek_integrator/option', 'user_token=' . $this->session->data['user_token'], 'SSL');
			} else {
				$redirect = $this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL');
			}

			$this->response->redirect($redirect);
		}

		$this->load->model('localisation/order_status');
		$this->load->model('localisation/weight_class');
		$this->load->model('localisation/length_class');

		$this->document->setTitle($this->language->get('heading_title_option'));

		$rdata['heading_title'] = $this->language->get('heading_title_option');

		$rdata['text_city_from'] = $this->language->get('text_city_from');
		$rdata['text_tokens'] = $this->language->get('text_tokens');
		$rdata['text_token_dispatch_number'] = $this->language->get('text_token_dispatch_number');
		$rdata['text_token_order_id'] = $this->language->get('text_token_order_id');
		$rdata['text_help_status_rule'] = $this->language->get('text_help_status_rule');
		$rdata['text_none'] = $this->language->get('text_none');

		$rdata['entry_city'] = $this->language->get('entry_city');
		$rdata['entry_copy_count'] = $this->language->get('entry_copy_count');
		$rdata['entry_weight_class_id'] = $this->language->get('entry_weight_class_id');
		$rdata['entry_length_class_id'] = $this->language->get('entry_length_class_id');
		$rdata['entry_account'] = $this->language->get('entry_account');
		$rdata['entry_secure_password'] = $this->language->get('entry_secure_password');
		$rdata['entry_new_order_status_id'] = $this->language->get('entry_new_order_status_id');
		$rdata['entry_shipping_methods'] = $this->language->get('entry_shipping_methods');
		$rdata['entry_payment_methods'] = $this->language->get('entry_payment_methods');
		$rdata['entry_new_order'] = $this->language->get('entry_new_order');
		$rdata['entry_city_default'] = $this->language->get('entry_city_default');
		$rdata['entry_packing_min_weight'] = $this->language->get('entry_packing_min_weight');
		$rdata['entry_packing_additional_weight'] = $this->language->get('entry_packing_additional_weight');
		$rdata['entry_cod_default'] = $this->language->get('entry_cod_default');
		$rdata['entry_delivery_recipient_cost'] = $this->language->get('entry_delivery_recipient_cost');
		$rdata['entry_seller_name'] = $this->language->get('entry_seller_name');
		$rdata['entry_add_service'] = $this->language->get('entry_add_service');
		$rdata['entry_replace_items'] = $this->language->get('entry_replace_items');
		$rdata['entry_replace_item_name'] = $this->language->get('entry_replace_item_name');
		$rdata['entry_replace_item_cost'] = $this->language->get('entry_replace_item_cost');
		$rdata['entry_replace_item_payment'] = $this->language->get('entry_replace_item_payment');
		$rdata['entry_replace_item_amount'] = $this->language->get('entry_replace_item_amount');
		$rdata['entry_use_cron'] = $this->language->get('entry_use_cron');
		$rdata['entry_currency'] = $this->language->get('entry_currency');
		$rdata['entry_currency_agreement'] = $this->language->get('entry_currency_agreement');

		$rdata['text_testing_api_keys'] = sprintf($this->language->get('text_testing_api_keys'), cdek_integrator::TEST_ACCOUNT, cdek_integrator::TEST_SECURE_PASSWORD);

		$rdata['column_token'] = $this->language->get('column_token');
		$rdata['column_value'] = $this->language->get('column_value');
		$rdata['column_cdek_status'] = $this->language->get('column_cdek_status');
		$rdata['column_new_status'] = $this->language->get('column_new_status');
		$rdata['column_notify'] = $this->language->get('column_notify');
		$rdata['column_comment'] = $this->language->get('column_comment');
		$rdata['column_action'] = $this->language->get('column_action');

		$rdata['tab_data'] = $this->language->get('tab_data');
		$rdata['tab_auth'] = $this->language->get('tab_auth');
		$rdata['tab_order'] = $this->language->get('tab_order');
		$rdata['tab_package'] = $this->language->get('tab_additional_weight');
		$rdata['tab_status'] = $this->language->get('tab_status');
		$rdata['tab_currency'] = $this->language->get('tab_currency');
		$rdata['tab_additional'] = $this->language->get('tab_additional');

		$rdata['button_save'] = $this->language->get('button_save');
		$rdata['button_apply'] = $this->language->get('button_apply');
		$rdata['button_cancel'] = $this->language->get('button_cancel');

		$rdata['boolean_variables'] = array($this->language->get('text_no'), $this->language->get('text_yes'));

		$rdata['additional_weight_mode'] = array(
			'fixed'			=> $this->language->get('text_weight_fixed'),
			'all_percent'	=> $this->language->get('text_weight_all')
		);

		$rdata['currency_list'] = $this->getInfo()->getCurrencyList();

		if (isset($this->error['warning'])) {
			$rdata['error_warning'] = $this->error['warning'];
		} else {
			$rdata['error_warning'] = '';
		}

		$rdata['error'] = $this->error;

		if (isset($this->session->data['success'])) {
			$rdata['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$rdata['success'] = '';
		}

		$rdata['breadcrumbs'] = array();

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
   		);

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_extension'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
   		);

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_bk_main'),
			'href'      => $this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'),
   		);

		if (isset($this->request->post['cdek_integrator_setting'])) {
			$rdata['setting'] = $this->request->post['cdek_integrator_setting'];
		} else {
			$rdata['setting'] = $this->setting;
		}

		if (!isset($rdata['setting']['cod'])) {
			$rdata['setting']['cod'] = 1;
		}

		$rdata['action'] = $this->url->link('extension/module/cdek_integrator/option', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$rdata['cancel'] = $this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$rdata['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$rdata['cdek_statuses'] = $this->getInfo()->getOrderStatuses();

		$rdata['show_filter'] = version_compare(VERSION, '1.5.1.3', '>') || (strpos(VERSION, '1.5.1') !== FALSE);

		$rdata['payment_methods'] = $this->getPaymentMethods();
		$rdata['shipping_methods'] = $this->getShippingMethods();
		$rdata['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		$rdata['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
		$rdata['add_cervices'] = $this->getInfo()->getAddServices();

		$rdata['header'] = $this->load->controller('common/header');
		$rdata['column_left'] = $this->load->controller('common/column_left');
		$rdata['footer'] = $this->load->controller('common/footer');

		$rdata['token'] = $this->request->get['user_token'];

		$this->response->setOutput($this->templateOutput('option', $rdata));
	}

	public function dispatch() {

		if ($this->new_application) {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$this->document->setTitle($this->language->get('heading_title_dispatch'));

		$this->load->model('extension/module/cdek_integrator');

		$this->dispatchList();

	}

	private function dispatchList() {

		if ($this->new_application) {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = NULL;
		}

		if (isset($this->request->get['filter_dispatch_number'])) {
			$filter_dispatch_number = $this->request->get['filter_dispatch_number'];
		} else {
			$filter_dispatch_number = NULL;
		}

		if (isset($this->request->get['filter_act_number'])) {
			$filter_act_number = $this->request->get['filter_act_number'];
		} else {
			$filter_act_number = NULL;
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = NULL;
		}

		if (isset($this->request->get['filter_city_from'])) {
			$filter_city_from = $this->request->get['filter_city_from'];
		} else {
			$filter_city_from = NULL;
		}

		if (isset($this->request->get['filter_city_to'])) {
			$filter_city_to = $this->request->get['filter_city_to'];
		} else {
			$filter_city_to = NULL;
		}

		if (isset($this->request->get['filter_status_id'])) {
			$filter_status_id = $this->request->get['filter_status_id'];
		} else {
			$filter_status_id = NULL;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = NULL;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'd.date';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit']) && in_array($this->request->get['limit'], $this->limits)) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = reset($this->limits);
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_dispatch_number'])) {
			$url .= '&filter_dispatch_number=' . $this->request->get['filter_dispatch_number'];
		}

		if (isset($this->request->get['filter_act_number'])) {
			$url .= '&filter_act_number=' . $this->request->get['filter_act_number'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_city_from'])) {
			$url .= '&filter_city_from=' . $this->request->get['filter_city_from'];
		}

		if (isset($this->request->get['filter_city_to'])) {
			$url .= '&filter_city_to=' . $this->request->get['filter_city_to'];
		}

		if (isset($this->request->get['filter_status_id'])) {
			$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$rdata['breadcrumbs'] = array();

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
   		);

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_extension'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
   		);

   		$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_bk_main'),
			'href'      => $this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
   		);

		$rdata['cancel'] = $this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$rdata['dispatches'] = array();

		$exdata = array(
			'filter_order_id'			=> $filter_order_id,
			'filter_dispatch_number'	=> $filter_dispatch_number,
			'filter_act_number'			=> $filter_act_number,
			'filter_date'				=> $filter_date,
			'filter_city_from'			=> $filter_city_from,
			'filter_city_to'			=> $filter_city_to,
			'filter_status_id'			=> $filter_status_id,
			'filter_total'				=> $filter_total,
			'sort'						=> $sort,
			'order'						=> $order,
			'start'						=> ($page - 1) * $limit,
			'limit'						=> $limit
		);

		$results = $this->model_extension_module_cdek_integrator->getDispatchList($exdata);

		$order_total = $this->model_extension_module_cdek_integrator->getDispatchTotal($exdata);

		foreach ($results as $dispatch_info) {

			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('extension/module/cdek_integrator/dispatchView', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $dispatch_info['order_id'] . $url, 'SSL')
			);

			$action[] = array(
				'text'	=> $this->language->get('text_sync'),
				'href'	=> $this->url->link('extension/module/cdek_integrator/dispatchSync', 'user_token=' . $this->session->data['user_token'] . '&target=list&order_id=' . $dispatch_info['order_id'] . $url, 'SSL'),
				'class'	=> 'js sync'
			);

			if ($dispatch_info['status_id'] == 1) {

				$action[] = array(
					'text' => $this->language->get('text_delete'),
					'href' => $this->url->link('extension/module/cdek_integrator/dispatchDelete', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $dispatch_info['order_id'] . $url, 'SSL'),
					'class'	=> 'delete'
				);

			}

			if (is_null($dispatch_info['act_number'])) {
				$dispatch_info['act_number'] = FALSE;
			}

			$rdata['dispatches'][] = array(
				'order_id'				=> $dispatch_info['order_id'],
				'dispatch_number'		=> $dispatch_info['dispatch_number'],
				'act_number'			=> $dispatch_info['act_number'],
				'date'					=> $this->formatDate($dispatch_info['date']),
				'city_name'				=> $dispatch_info['city_name'],
				'recipient_city_name'	=> $dispatch_info['recipient_city_name'],
				'status'				=> $dispatch_info['status_description'],
				'status_date'			=> $this->formatDate($dispatch_info['status_date']),
				'cost'					=> (float)$dispatch_info['delivery_cost'] ? $this->currency->format($dispatch_info['delivery_cost'], $this->config->get('config_currency')) : '',
				'action'				=> $action
			);

		}

		$rdata['heading_title'] = $this->language->get('heading_title_dispatch');

		$rdata['text_no_results'] = $this->language->get('text_no_results');
		$rdata['text_missing'] = $this->language->get('text_missing');

		/*$rdata['column_order_id'] = $this->language->get('column_order_id');
    	$rdata['column_customer'] = $this->language->get('column_customer');*/
		$rdata['column_dispatch_number'] = $this->language->get('column_dispatch_number');
		$rdata['column_dispatch_total_orders'] = $this->language->get('column_dispatch_total_orders');
		$rdata['column_dispatch_date'] = $this->language->get('column_dispatch_date');
		$rdata['column_action'] = $this->language->get('column_action');

		$rdata['button_cancel'] = $this->language->get('button_cancel');
		$rdata['button_filter'] = $this->language->get('button_filter');

		if (isset($this->session->data['warning'])) {
			$rdata['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} elseif (isset($this->error['warning'])) {
			$rdata['error_warning'] = $this->error['warning'];
		} else {
			$rdata['error_warning'] = '';
		}

		$rdata['token'] = $this->session->data['user_token'];

		if (isset($this->session->data['success'])) {
			$rdata['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$rdata['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_dispatch_number'])) {
			$url .= '&filter_dispatch_number=' . $this->request->get['filter_dispatch_number'];
		}

		if (isset($this->request->get['filter_act_number'])) {
			$url .= '&filter_act_number=' . $this->request->get['filter_act_number'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_city_from'])) {
			$url .= '&filter_city_from=' . $this->request->get['filter_city_from'];
		}

		if (isset($this->request->get['filter_city_to'])) {
			$url .= '&filter_city_to=' . $this->request->get['filter_city_to'];
		}

		if (isset($this->request->get['filter_status_id'])) {
			$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$rdata['sort_order_id'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . '&sort=o.order_id' . $url, 'SSL');
		$rdata['sort_dispatch_number'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . '&sort=d.dispatch_number' . $url, 'SSL');
		$rdata['sort_act_number'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . '&sort=o.act_number' . $url, 'SSL');
		$rdata['sort_date'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . '&sort=d.date' . $url, 'SSL');
		$rdata['sort_city_from'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . '&sort=o.city_name' . $url, 'SSL');
		$rdata['sort_city_to'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . '&sort=o.recipient_city_name' . $url, 'SSL');
		$rdata['sort_status'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . '&sort=o.status_id' . $url, 'SSL');
		$rdata['sort_total'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . '&sort=o.delivery_cost' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_dispatch_number'])) {
			$url .= '&filter_dispatch_number=' . $this->request->get['filter_dispatch_number'];
		}

		if (isset($this->request->get['filter_act_number'])) {
			$url .= '&filter_act_number=' . $this->request->get['filter_act_number'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_city_from'])) {
			$url .= '&filter_city_from=' . $this->request->get['filter_city_from'];
		}

		if (isset($this->request->get['filter_city_to'])) {
			$url .= '&filter_city_to=' . $this->request->get['filter_city_to'];
		}

		if (isset($this->request->get['filter_status_id'])) {
			$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		foreach ($this->limits as $item) {
			$rdata['limits'][$item] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . '&limit=' . $item . $url, 'SSL');
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_dispatch_number'])) {
			$url .= '&filter_dispatch_number=' . $this->request->get['filter_dispatch_number'];
		}

		if (isset($this->request->get['filter_act_number'])) {
			$url .= '&filter_act_number=' . $this->request->get['filter_act_number'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_city_from'])) {
			$url .= '&filter_city_from=' . $this->request->get['filter_city_from'];
		}

		if (isset($this->request->get['filter_city_to'])) {
			$url .= '&filter_city_to=' . $this->request->get['filter_city_to'];
		}

		if (isset($this->request->get['filter_status_id'])) {
			$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$rdata['statuses'] = $this->getInfo()->getOrderStatuses();

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$rdata['pagination'] = $pagination->render();

		$rdata['filter_order_id'] = $filter_order_id;
		$rdata['filter_dispatch_number'] = $filter_dispatch_number;
		$rdata['filter_act_number'] = $filter_act_number;
		$rdata['filter_date'] = $filter_date;
		$rdata['filter_city_from'] = $filter_city_from;
		$rdata['filter_city_to'] = $filter_city_to;
		$rdata['filter_status_id'] = $filter_status_id;
		$rdata['filter_total'] = $filter_total;

		$rdata['sort'] = $sort;
		$rdata['order'] = strtolower($order);
		$rdata['limit'] = $limit;

		$rdata['header'] = $this->load->controller('common/header');
		$rdata['column_left'] = $this->load->controller('common/column_left');
		$rdata['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->templateOutput('dispatch_list', $rdata));
	}

	public function dispatchView() {

		$this->load->model('extension/module/cdek_integrator');

		if ($this->new_application) {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		} elseif (empty($this->request->get['order_id'])) {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$dispatch_info = $this->model_extension_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);

		if ($dispatch_info) {

			$this->load->model('localisation/weight_class');
			$this->load->model('localisation/length_class');
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($dispatch_info['order_id']);

			$rdata['heading_title'] = 'Детали заказа';

			$this->document->setTitle($rdata['heading_title']);

			$rdata['button_sync'] = $this->language->get('button_sync');
			$rdata['button_print'] = $this->language->get('button_print');
			$rdata['button_cancel'] = $this->language->get('button_cancel');

			$url = '';

			$rdata['breadcrumbs'] = array();

			$rdata['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
			);

			$rdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_extension'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
   		);

			$rdata['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title_bk_main'),
				'href'      => $this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
			);

			$rdata['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title_dispatch'),
				'href'      => $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
			);

			if (isset($this->session->data['warning'])) {
				$rdata['error_warning'][] = $this->session->data['warning'];

				unset($this->session->data['warning']);
			} elseif (isset($this->error['warning'])) {
				$rdata['error_warning'] = $this->error['warning'];
			} else {
				$rdata['error_warning'] = '';
			}

			$rdata['token'] = $this->session->data['user_token'];

			if (isset($this->session->data['success'])) {
				$rdata['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$rdata['success'] = '';
			}

			$rdata['sync'] = $this->url->link('extension/module/cdek_integrator/dispatchSync', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'], 'SSL');
			$rdata['print'] = $this->url->link('extension/module/cdek_integrator/dispatchPrint', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'], 'SSL');
			$rdata['cancel'] = $this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'], 'SSL');

			if (empty($dispatch_info['currency'])) $dispatch_info['currency'] = 'RUB';

			$rdata['dispatch_info'] = $dispatch_info;

			$rdata['date'] = $this->formatDate($dispatch_info['date']);

			$rdata['last_exchange'] = $this->formatDate($dispatch_info['last_exchange'], TRUE, FALSE);

			if ($this->config->get('config_currency') != 'RUB') {

				$dispatch_info['delivery_cost'] = $this->currency->convert($dispatch_info['delivery_cost'], 'RUB', $this->config->get('config_currency'));

				if ($dispatch_info['delivery_recipient_cost']) {
					$dispatch_info['delivery_recipient_cost'] = $this->currency->convert($dispatch_info['delivery_recipient_cost'], 'RUB', $this->config->get('config_currency'));
				}
			}

			if ((float)$dispatch_info['delivery_cost']) {
				$rdata['delivery_cost'] = $this->currency->format($dispatch_info['delivery_cost'], $this->config->get('config_currency'));
			} else {
				$rdata['delivery_cost'] = 0;
			}

			if ((float)$dispatch_info['delivery_recipient_cost']) {
				$rdata['delivery_recipient_cost'] = $this->currency->format($dispatch_info['delivery_recipient_cost'], $this->config->get('config_currency'));
			}

			if ((float)$dispatch_info['cod'] > 0 || (float)$dispatch_info['cod_fact']) {

				$rdata['currency_cod'] = $this->getInfo()->getCurrency($dispatch_info['currency_cod']);

				if ((float)$dispatch_info['cod'] > 0) {
					$rdata['cod'] = $this->clearCurrencyFormat($dispatch_info['cod']);
				}

				if ((float)$dispatch_info['cod_fact'] > 0) {
					$rdata['cod_fact'] = $this->clearCurrencyFormat($dispatch_info['cod_fact']);
				}

			}

			if ($dispatch_info['delivery_last_change']) {
				$rdata['delivery_last_change'] = $this->formatDate($dispatch_info['delivery_last_change']);
			}

			if ($dispatch_info['reason_status']) {
				$rdata['reason_status'] = $dispatch_info['reason_status'];
			}

			$rdata['status_history'] = array();

			foreach($this->model_extension_module_cdek_integrator->getStatusHistory($this->request->get['order_id']) as $status_info) {

				$status_description = $this->getInfo()->getOrderStatus($status_info['status_id']);

				if (!empty($status_description)) {
					$description = $status_description['description'];
				} else {
					$description = '';
				}

				$rdata['status_history'][] = array(
					'status_id'		=> $status_info['status_id'],
					'name'			=> $status_info['description'],
					'description'	=> $description,
					'date'			=> $this->formatDate($status_info['date']),
					'city'			=> $status_info['city_name']
				);
			}

			$rdata['status'] = array(
				'title'	=> $rdata['status_history'][0]['name'],
				'date'	=> $rdata['status_history'][0]['date']
			);

			if ($dispatch_info['delay_id']) {
				$rdata['delay'] = array(
					'title'	=> $dispatch_info['delay_description'],
					'date'	=> $this->formatDate($dispatch_info['delay_date'])
				);
			} else {
				$rdata['delay'] = array();
			}

			$rdata['delay_history'] = array();

			foreach($this->model_extension_module_cdek_integrator->getDelayHistory($this->request->get['order_id']) as $delay_info) {


				$rdata['delay_history'][] = array(
					'delay_id'		=> $delay_info['delay_id'],
					'name'			=> $delay_info['description'],
					'date'			=> $this->formatDate($delay_info['date'])
				);
			}

			if (file_exists(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf') && is_file(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf')) {
				$rdata['pdf'] = $this->url->link('extension/module/cdek_integrator/showPdf', 'user_token=' . $this->session->data['user_token'].'&order_id='.$this->request->get['order_id'], 'SSL');
			}

			$tariff_info = $this->getInfo()->getTariffInfo($dispatch_info['tariff_id']);

			$pvz_list = array();

			if ($tariff_info) {

				$rdata['tariff'] = $tariff_info;

				if (in_array((int)$tariff_info['mode_id'], array(2, 4)) && !empty($dispatch_info['address_pvz_code'])) {

					$pvz_list = $this->getPVZ($dispatch_info['recipient_city_id']);

					if (!empty($pvz_list['List'])) {

						foreach ($pvz_list['List'] as $pvz_info) {

							if ($dispatch_info['address_pvz_code'] == $pvz_info['Code']) {

								$rdata['dispatch_info']['pvz_info'] = $pvz_info;

								break;
							}

						}

					}

				}

			} else {
				$rdata['tariff'] = array('title'	=> '<span class="error">Тариф не определен!</span>');
			}

			$courier_call = $this->model_extension_module_cdek_integrator->getCourierCall($this->request->get['order_id']);

			if ($courier_call) {

				$courier_call['date'] = $this->formatDate($courier_call['date'], FALSE);

				foreach (array('time_beg', 'time_end', 'lunch_beg', 'lunch_end') as $time_key) {
					if ($courier_call[$time_key]) $courier_call[$time_key] = date('H:i', strtotime($courier_call[$time_key]));
				}

				$rdata['courier'] = $courier_call;
			}

			$rdata['schedule'] = array();

			$schedule = $this->model_extension_module_cdek_integrator->getChedule($this->request->get['order_id']);

			foreach ($schedule as $attempt_info) {

				$attempt_info['date'] = $this->formatDate($attempt_info['date'], FALSE);

				foreach (array('time_beg', 'time_end') as $time_key) {
					$attempt_info[$time_key] = date('H:i', strtotime($attempt_info[$time_key]));
				}

				if (!empty($attempt_info['address_pvz_code'])) {

					if (!empty($pvz_list['List'])) {

						foreach ($pvz_list['List'] as $pvz_info) {

							if ($attempt_info['address_pvz_code'] == $pvz_info['Code']) {

								$attempt_info['pvz_info'] = $pvz_info;

								break;
							}

						}

					}

				}

				if ($attempt_info['phone'] != '' || $attempt_info['recipient_name'] != '') {

					$attempt_info['recipient_info'] = array();

					if ($attempt_info['phone'] != '') {
						$attempt_info['recipient_info']['phone'] = $attempt_info['phone'];
					}

					if ($attempt_info['recipient_name'] != '') {
						$attempt_info['recipient_info']['name'] = $attempt_info['recipient_name'];
					}

				}

				if ($attempt_info['address_street'] != '' && $attempt_info['address_house'] != '' || !empty($attempt_info['pvz_info'])) {

					$attempt_info['address_info'] = array();

					if ($attempt_info['address_street'] != '') {
						$attempt_info['address_info']['street'] = $attempt_info['address_street'];
					}

					if ($attempt_info['address_house'] != '') {
						$attempt_info['address_info']['house'] = $attempt_info['address_house'];
					}

					if ($attempt_info['address_flat'] != '') {
						$attempt_info['address_info']['flat'] = $attempt_info['address_flat'];
					}

					if (!empty($attempt_info['pvz_info'])) {
						$attempt_info['address_info']['pvz_info'] = $attempt_info['pvz_info'];
					}

				}

				$attempt_info['show_more'] = (!empty($attempt_info['recipient_info']) || !empty($attempt_info['address_info']) || $attempt_info['comment'] != '' || $attempt_info['delay'] != '');

				$rdata['schedule'][] = $attempt_info;
			}

			$rdata['call_history'] = array();

			$call_history_good = $this->model_extension_module_cdek_integrator->getCallHistoryGood($this->request->get['order_id']);

			if (!empty($call_history_good)) {

				$rdata['call_history']['good'] = array();

				foreach($call_history_good as $call_good_info) {

					$rdata['call_history']['good'][] = array(
						'date'			=> $this->formatDate($call_good_info['date']),
						'date_deliv'	=> $this->formatDate($call_good_info['date_deliv'])
					);

				}

			}

			$call_history_fail = $this->model_extension_module_cdek_integrator->getCallHistoryFail($this->request->get['order_id']);

			if (!empty($call_history_fail)) {

				$rdata['call_history']['fail'] = array();

				foreach($call_history_fail as $call_fail_info) {

					$rdata['call_history']['fail'][] = array(
						'fail_id'		=> (int)$call_fail_info['fail_id'],
						'date'			=> $this->formatDate($call_fail_info['date']),
						'description'	=> $call_fail_info['description']
					);

				}

			}

			$call_history_delay = $this->model_extension_module_cdek_integrator->getCallHistoryDelay($this->request->get['order_id']);

			if (!empty($call_history_delay)) {

				$rdata['call_history']['delay'] = array();

				foreach($call_history_delay as $call_delay_info) {

					$rdata['call_history']['delay'][] = array(
						'date'		=> $this->formatDate($call_delay_info['date']),
						'date_next'	=> $this->formatDate($call_delay_info['date_next'])
					);

				}

			}

			$rdata['currency'] = $this->getInfo()->getCurrency($dispatch_info['currency']);

			$rdata['packages'] = array();

			$packages = $this->model_extension_module_cdek_integrator->getPackages($this->request->get['order_id']);

			$weight_class_info = $this->model_localisation_weight_class->getWeightClass($this->config->get('config_weight_class_id'));

			if ($weight_class_info) {
				$rdata['weight_class'] = $weight_class_info['title'];
			} else {
				$rdata['weight_class'] = 'Граммы';
			}

			$length_class_info = $this->model_localisation_length_class->getLengthClass($this->config->get('config_length_class_id'));

			if ($length_class_info) {
				$rdata['length_class'] = $length_class_info['title'];
			} else {
				$rdata['length_class'] = 'Сантиметры';
			}

			$change_weight = ($this->setting['weight_class_id'] != $this->config->get('config_weight_class_id'));
			$change_length = ($this->setting['length_class_id'] != $this->config->get('config_length_class_id'));

			foreach ($packages as $package_info) {

				$items = array();

				if ($change_weight) {
					$package_info['weight'] = $this->weight->convert($package_info['weight'], $this->setting['weight_class_id'], $this->config->get('config_weight_class_id'));
				}

				if ((float)$package_info['size_a'] > 0 && (float)$package_info['size_b'] > 0 && (float)$package_info['size_c'] > 0) {

					$package_size = array($package_info['size_a'], $package_info['size_b'], $package_info['size_c']);

					foreach ($package_size as &$size_item) {

						if ($change_length) {
							$size_item = $this->length->convert($size_item, $this->setting['length_class_id'], $this->config->get('config_length_class_id'));
						}

						$size_item = (float)round($size_item, 2);
					}

					$package_info['package_size'] = implode(' x ', $package_size);
				}

				$package_items = $this->model_extension_module_cdek_integrator->getPackageItems($package_info['package_id'], $this->request->get['order_id']);

				$package_info['items'] = array();

				if (!$order_info || !$this->currency->getId($order_info['currency_code'])) {
					$order_info['currency_code'] = 'RUB';
				}

				foreach ($package_items as $package_item) {

					if ($change_weight) {
						$package_item['weight'] = $this->weight->convert($package_item['weight'], $this->setting['weight_class_id'], $this->config->get('config_weight_class_id'));
					}

					$package_item['weight'] = (float)round($package_item['weight'], 4);

					if ($this->config->get('config_currency') != $order_info['currency_code']) {

						$package_item['cost'] = $this->currency->convert($package_item['cost'], $order_info['currency_code'], $this->config->get('config_currency'));
						$package_item['payment'] = $this->currency->convert($package_item['payment'], $order_info['currency_code'], $this->config->get('config_currency'));

					}

					$package_item['total'] = $this->currency->format($package_item['cost'] * $package_item['amount'], $this->config->get('config_currency'));
					$package_item['cost'] = $this->currency->format($package_item['cost'], $this->config->get('config_currency'));
					$package_item['payment'] = $this->currency->format($package_item['payment'], $this->config->get('config_currency'));

					$package_info['items'][] = $package_item;
				}

				$rdata['packages'][] = $package_info;

			}

			$rdata['add_service_total'] = 0;

			$rdata['add_service'] = array();

			$add_service = $this->model_extension_module_cdek_integrator->getAddService($this->request->get['order_id']);

			foreach ($add_service as $service_info) {

				$cdek_service_info = $this->getInfo()->getAddService($service_info['service_id']);

				if ($cdek_service_info) {
					$service_info['service_description'] = $cdek_service_info['description'];
				}

				if ($this->config->get('config_currency') == 'RUB') {
					$service_info['price'] = $this->currency->convert($service_info['price'], 'RUB', $this->config->get('config_currency'));
				}

				$rdata['add_service_total'] += $service_info['price'];

				$service_info['price'] = $this->currency->format($service_info['price'], $this->config->get('config_currency'));

				$rdata['add_service'][] = $service_info;

			}

			if ($rdata['add_service_total']) {
				$rdata['add_service_total'] = $this->currency->format($rdata['add_service_total'], $this->config->get('config_currency'));
			}

		} else {

			$rdata['success'] = 'Отправление #' . $this->request->get['order_id'] . ' не найдено!';
			$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'], 'SSL'));

		}

		if($this->isAjax()) {
			$this->response->setOutput($this->templateOutput('dispatch_info', $rdata));
		} else {
			$rdata['header'] = $this->load->controller('common/header');
			$rdata['column_left'] = $this->load->controller('common/column_left');
			$rdata['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->templateOutput('dispatch_info_full', $rdata));
		}
	}

	private function renderPage($render = TRUE) {

		if ($render) {

			$rdata['content'] = $this->render();

			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->template = 'extension/module/cdek_integrator/page';
		}

		$this->response->setOutput($this->render());
	}

	public function dispatchPrint()
	{
		if (!file_exists(DIR_DOWNLOAD . 'cdek')) {
    		mkdir(DIR_DOWNLOAD . 'cdek', 0777, true);
		}

		if (file_exists(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf'))
		{
			if ($this->isAjax())
			{
				$json['message'] = $this->session->data['success'];
				$json['file'] = $this->url->link('extension/module/cdek_integrator/showPdf', 'user_token=' . $this->session->data['user_token'].'&order_id='.$this->request->get['order_id'], 'SSL');
				$this->response->setOutput(json_encode($json));
				exit;
			} else {
				$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatchView', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'], 'SSL'));
			}
		}

		$this->load->model('extension/module/cdek_integrator');

		if ($this->isAjax()) {

			$json = array(
				'status' => 'OK'
			);

			if ($this->new_application || empty($this->request->get['order_id'])) {
				$json['status'] = 'error';
				$json['message'] = 'Не удалось загрузить квитанцию.';
			}

		} else {

			if ($this->new_application) {
				$this->response->redirect($this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			} elseif (empty($this->request->get['order_id'])) {
				$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}

		}

		$dispatch_info = $this->model_extension_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);

		if ($dispatch_info) {

			$component = $this->api->loadComponent('order_print');

			$exdata = array(
				'copy_count'	=> (isset($this->setting['copy_count']) ? (int)$this->setting['copy_count'] : 1),
				'order'			=> array(
					array('dispatch_number' => $dispatch_info['number'])
				)
			);

			$component->setData($exdata);

			$pdf = $this->api->sendData($component);

			if (!empty($this->api->error)) {

				foreach ($this->api->error as $error_key => $error_message) {

					switch($error_key) {
						case 'ERR_AUTH':
							$this->session->data['warning'] = $this->language->get('error_auth');
							break;
						case 'ERR_INVALID_DISPACHNUMBER':
							$this->session->data['warning'] = 'Отправление #' . $dispatch_info['number'] . ' не найдено в базе СДЭК!';
							break;
						case 'ERR_ORDER_NOTFIND':
							$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';
							break;
						default:
							$this->session->data['warning'] = $error_message;
							break;
					}

				}

			} else {

				if ($pdf != '') {
					file_put_contents(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf', $pdf);
					$this->session->data['success'] = 'Квитанция для заказа #' . $this->request->get['order_id'] . ' успешно загружена!';
				} else {
					$this->session->data['warning'] = 'Не удалось загрузить квитанцию, попробуйте ещё!';
				}

			}

		} else {

			$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';

		}

		if ($this->isAjax()) {

			if (!empty($this->session->data['warning'])) {

				$json['status'] = 'error';
				$json['message'] = $this->session->data['warning'];

				unset($this->session->data['warning']);

			} else {

				$json['message'] = $this->session->data['success'];
				$json['file'] = $this->url->link('extension/module/cdek_integrator/showPdf', 'user_token=' . $this->session->data['user_token'].'&order_id='.$this->request->get['order_id'], 'SSL');
				unset($this->session->data['success']);

			}

			$this->response->setOutput(json_encode($json));

		} else {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatchView', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'], 'SSL'));
		}
	}

	public function dispatchDelete() {

		$this->load->model('extension/module/cdek_integrator');

		if ($this->isAjax()) {

			$json = array(
				'status' => 'OK'
			);

			if ($this->new_application || empty($this->request->get['order_id'])) {
				$json['status'] = 'error';
				$json['message'] = 'Не удалось загрузить квитанцию.';
			}

		} else {

			if ($this->new_application) {
				$this->response->redirect($this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			} elseif (empty($this->request->get['order_id'])) {
				$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}

		}

		$dispatch_info = $this->model_extension_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);

		if ($dispatch_info && $dispatch_info['status_id'] == 1) { // Удалить можно только новый заказ

			$forced = (isset($this->request->get['forced']));

			$this->api->setDate(date('Y-m-d', $dispatch_info['date']));
			$component = $this->api->loadComponent('order_delete');
			$component->setNumber($dispatch_info['dispatch_number']);
			$component->setData(array($this->request->get['order_id']));
			$this->api->sendData($component);

			if ($forced || empty($this->api->error)) {

				if (file_exists(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf') && is_file(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf')) {
					@unlink(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf');
				}

				$this->model_extension_module_cdek_integrator->deleteDispatch($this->request->get['order_id']);
				$this->session->data['success'] = 'Заказ #' . $dispatch_info['number'] . ' успешно удален.';
				$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'], 'SSL'));

			} else {

				$error_list = reset($this->api->error);

				$forced_delete = $this->url->link('extension/module/cdek_integrator/dispatchDelete', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $dispatch_info['order_id'] . '&forced', 'SSL');

				foreach ($error_list as $error_key => $error_message) {

					switch($error_key) {
						case 'ERR_AUTH':
							$this->session->data['warning'] = $this->language->get('error_auth');
							break;
						case 'ERR_INVALID_DISPACHNUMBER':
							$this->session->data['warning'] = 'Отправление #' . $dispatch_info['number'] . ' не найдено в базе СДЭК! <a href="' . $forced_delete . '">Удалить принудительно</a>?';
							break;
						case 'ERR_ORDER_NOTFIND':
							$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК! <a href="' . $forced_delete . '">Удалить принудительно</a>?';
							break;
						default:
							$this->session->data['warning'] = $error_message;
							break;
					}

				}

			}

		} else {
			$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';
		}

		if ($this->isAjax()) {

			/*if (!empty($this->session->data['warning'])) {

				$json['status'] = 'error';
				$json['message'] = $this->session->data['warning'];

				unset($this->session->data['warning']);

			} else {*/

				$json['message'] = $this->session->data['success'];
				unset($this->session->data['success']);

			/*}*/

			$this->response->setOutput(json_encode($json));

		} else {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
	}

	private function sync($orders = array()) {

		if (!$orders) return FALSE;

		$update = array();

		$component = $this->api->loadComponent('order_info');

		$exdata = array(
			'order'	=> $orders
		);

		$component->setData($exdata);

		$info = $this->api->sendData($component);

		if (!empty($this->api->error)) {
			return FALSE;
		}


		if (isset($info->Order)) {
			foreach ($info->Order as $item) {

				$attributes = $item->attributes();
				$city_attributes = $item->SendCity->attributes();
				$recipient_city_postcode = $item->RecCity->attributes();

				$order_id = (int)$attributes->Number;

				$update[$order_id]['delivery_cost'] = (string)$attributes->DeliverySum;
				$update[$order_id]['city_postcode'] = (string)$city_attributes->PostCode;
				$update[$order_id]['recipient_city_postcode'] = (string)$recipient_city_postcode->PostCode;

				$update[$order_id]['cod'] = (string)$recipient_city_postcode->CashOnDeliv;
				$update[$order_id]['cod_fact'] = (string)$recipient_city_postcode->CachOnDelivFac;

				if ($attributes->DateLastChange != '') {
					$update[$order_id]['delivery_last_change'] = (string)strtotime($attributes->DateLastChange);
				}

			}

		}

		$component = $this->api->loadComponent('order_status');

		$exdata = array(
			'show_history'	=> 1,
			'order'			=> $orders
		);

		$component->setData($exdata);

		$status = $this->api->sendData($component);

		if (isset($status->Order)) {

			foreach ($status->Order as $item) {

				$attributes = $item->attributes();

				$order_id = (int)$attributes->Number;

				if (!array_key_exists($order_id, $orders)) {
					continue;
				}

				$dispatch_info = $orders[$order_id];

				if ((string)$attributes->ActNumber != '') {
					$update[$order_id]['act_number'] = (string)$attributes->ActNumber;
				}

				if ((string)$attributes->DeliveryDate != '') {
					$update[$order_id]['delivery_date'] = (string)strtotime($attributes->DeliveryDate);
				}

				if ((string)$attributes->RecipientName != '') {
					$update[$order_id]['delivery_recipient_name'] = (string)$attributes->RecipientName;
				}

				$status_attributes = $item->Status->attributes();

				$status_id = (string)$status_attributes->Code;

				$status_attributes = $item->Status->attributes();

				$status_id = (string)$status_attributes->Code;

				if ($dispatch_info['status_id']!= $status_id) {

					$status_history = array();

					foreach ($item->Status->State as $status_info) {

						$status_attributes = $status_info->attributes();

						$status_history[] = array(
							'date'			=> (string)strtotime($status_attributes->Date),
							'status_id'		=> (int)$status_attributes->Code,
							'description'	=> (string)$status_attributes->Description,
							'city_code'		=> (string)$status_attributes->CityCode,
							'city_name'		=> (string)$status_attributes->CityName
						);
					}

					$status_attributes = $item->Status->attributes();

					$update[$order_id] += array(
						'status_id'			=> (string)$status_attributes->Code,
						'status_history'	=> $status_history
					);

				}

				$reason_attributes = $item->Reason->attributes();

				if ((int)$reason_attributes->Code) {

					$reason_history = array();

					$reason_history[] = array(
						'reason_id' 	=> (int)$reason_attributes->Code,
						'date'			=> (string)strtotime($reason_attributes->Date),
						'description'	=> (string)$reason_attributes->Description
					);

					$update[$order_id] += array(
						'reason_id'			=> (int)$reason_attributes->Code,
						'reason_history'	=> $reason_history
					);
				}

				$delay_history = array();

				if (isset($item->DelayReason->State)) {

					foreach ($item->DelayReason->State as $delay_info) {

						$delay_attributes = $delay_info->attributes();

						$delay_history[] = array(
							'date'			=> (string)strtotime($delay_attributes->Date),
							'delay_id'		=> (int)$delay_attributes->Code,
							'description'	=> (string)$delay_attributes->Description,
						);
					}

				}

				$delay_attributes = $item->DelayReason->attributes();

				$update[$order_id] += array(
					'delay_id'			=> (int)$delay_attributes->Code,
					'delay_history'		=> $delay_history
				);

				if (isset($item->Attempt)) {

					$update[$order_id]['attempt'] = array();

					foreach ($item->Attempt as $attempt_info) {

						$attempt_attributes = $attempt_info->attributes();

						$attempt_id = (int)$attributes->ID;

						$update[$order_id]['attempt'][] = array(
							'attempt_id'	=> $attempt_id,
							'delay_id'		=> (int)$attributes->ScheduleCode,
							'description'	=> (string)$status_attributes->ScheduleDescription
						);
					}

				}

				if (isset($item->Call)) {

					$update[$order_id]['call'] = array();

					if (isset($item->Call->CallGood->Good)) {

						$update[$order_id]['call']['good'] = array();

						foreach ($item->Call->CallGood->Good as $call_good_info) {

							$call_good_attributes = $call_good_info->attributes();

							$update[$order_id]['call']['good'][] = array(
								'date'			=> (string)strtotime($call_good_attributes->Date),
								'date_deliv'	=> (string)strtotime($call_good_attributes->DateDeliv)
							);

						}

					}

					if (isset($item->Call->CallFail->Fail)) {

						$update[$order_id]['call']['fail'] = array();

						foreach ($item->Call->CallFail->Fail as $call_fail_info) {

							$call_fail_attributes = $call_fail_info->attributes();

							$update[$order_id]['call']['fail'][] = array(
								'date'			=> (string)strtotime($call_fail_attributes->Date),
								'fail_id'		=> (int)$call_fail_attributes->ReasonCode,
								'description'	=> (string)$call_fail_attributes->ReasonDescription
							);

						}

					}

					if (isset($item->Call->CallDelay->Delay)) {

						$update[$order_id]['call']['delay'] = array();

						foreach ($item->Call->CallDelay->Delay as $call_delay_info) {

							$call_delay_attributes = $call_delay_info->attributes();

							$update[$order_id]['call']['delay'][] = array(
								'date'		=> (string)strtotime($call_delay_attributes->Date),
								'date_next'	=> (string)strtotime($call_delay_attributes->DateNext)
							);

						}

					}

				}
			}

		}
		return $update;
	}

	public function dispatchSync() {

		$this->load->model('extension/module/cdek_integrator');

		if ($this->isAjax()) {

			$json = array(
				'status' => 'OK'
			);

			if ($this->new_application || empty($this->request->get['order_id'])) {
				$json['status'] = 'error';
				$json['message'] = 'Не удалось загрузить квитанцию.';
			}

		} else {

			if ($this->new_application) {
				$this->response->redirect($this->url->link('extension/module/cdek_integrator', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			} elseif (empty($this->request->get['order_id'])) {
				$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatch', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}

		}

		$dispatch_info = $this->model_extension_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);

		if ($dispatch_info) {

			$orders = array();

			$dispatch_info['dispatch_number'] = $dispatch_info['number'];

			$orders[$this->request->get['order_id']] = $dispatch_info;

			$update = $this->sync($orders);

			if (!empty($this->api->error)) {

				$error_list = reset($this->api->error);

				foreach ($error_list as $error_key => $error_message) {

					switch($error_key) {
						case 'ERR_AUTH':
							$this->session->data['warning'] = $this->language->get('error_auth');
							break;
						case 'ERR_INVALID_DISPACHNUMBER':
							$this->session->data['warning'] = 'Отправление #' . $dispatch_info['number'] . ' не найдено в базе СДЭК!';
							break;
						case 'ERR_ORDER_NOTFIND':
							$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';
							break;
						default:
							$this->session->data['warning'] = $error_message;
							break;
					}

				}


			} elseif (array_key_exists($this->request->get['order_id'], $update)) {

				$exdata = reset($update);

				$this->model_extension_module_cdek_integrator->editDispatch($this->request->get['order_id'], $exdata);

				$this->session->data['success'] = 'Заказ #' . $this->request->get['order_id'] . ' обновлен!<span class="help">Дата синхронизации: ' . $this->formatDate(time(), TRUE, FALSE) . '</span>';

			}

		} else {
			$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';
		}

		if ($this->isAjax()) {

			if (!empty($this->session->data['warning'])) {

				$json['status'] = 'error';
				$json['message'] = $this->session->data['warning'];

				unset($this->session->data['warning']);

			} else {

				$json['message'] = $this->session->data['success'];
				unset($this->session->data['success']);

				if (isset($this->request->get['target']) && $this->request->get['target'] == 'list') {

					$dispatch_info = $this->model_extension_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);

					$json += array(
						'order_id'				=> $dispatch_info['order_id'],
						'dispatch_number'		=> $dispatch_info['number'],
						'act_number'			=> $dispatch_info['act_number'],
						'date'					=> $this->formatDate($dispatch_info['date']),
						'city_name'				=> $dispatch_info['city_name'],
						'recipient_city_name'	=> $dispatch_info['recipient_city_name'],
						'status_title'			=> $dispatch_info['status_description'],
						'status_date'			=> $this->formatDate($dispatch_info['status_date']),
						'cost'					=> $this->currency->format($dispatch_info['delivery_cost'], $this->config->get('config_currency'))
					);

				} else {
					$this->load->controller('extension/module/cdek_integrator/dispatchView');
				}

			}

			$this->response->setOutput(json_encode($json));

		} else {
			$this->response->redirect($this->url->link('extension/module/cdek_integrator/dispatchView', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'], 'SSL'));
		}

	}

	public function getPVZByCity() {

		$json = array();

		if (isset($this->request->get['city_code']) && $pvz_list = $this->getPVZ($this->request->get['city_code'])) {
			$json = $pvz_list;
		}

		$this->response->setOutput(json_encode($json));
	}

	private function getPVZ($city_code) {

		$pvz_list = $this->getPVZList();
		return array_key_exists($city_code, $pvz_list) ? $pvz_list[$city_code] : FALSE;
	}

	private function getPVZList() {

		$exdata = $this->getInfo()->getPVZData();

		if (!$exdata) {
			$this->error['warning'] = $this->language->get('error_load_pvz');
		}

		return $exdata;
	}

	private function getCity($cityName, $country_id, $zone_id) {

		$city_info = array();

		if (!$cityName) return '';

		if (!is_numeric($zone_id)) $zone_id = 0;

		if (!is_numeric($country_id) || !$country_id) $country_id = $this->config->get('config_country_id');

		$countries = $regions = array();
		$empty_country = $empty_zone = $from_db = FALSE;

		if (!$country_id) {
			$empty_country = TRUE;
		} else {

			$this->load->model('localisation/country');
			$country_info = $this->model_localisation_country->getCountry($country_id);

			if ($country_info) {
				$countries = $this->prepareCountry($country_info['name']);

				$empty_country = $from_db = in_array('россия', $countries);

			} else {
				return FALSE;
			}

		}

		if (!$zone_id) {
			$empty_zone = TRUE;
		} else {

			$this->load->model('localisation/zone');
			$zone_info = $this->model_localisation_zone->getZone($zone_id);

			if ($zone_info) {
				$regions = $this->prepareRegion($zone_info['name']);
			} else {
				return FALSE;
			}

		}

		$cityName = $this->_clear($cityName);

		if ($cityName) {

			if ($from_db) {

				$this->load->model('extension/module/cdek_integrator');
				$cdek_cities = $this->model_extension_module_cdek_integrator->getCity($cityName);

			} else {
				$cdek_cities = $this->getCityByName($cityName);
			}

			if ($cdek_cities) {

				$available = array();

				foreach ($cdek_cities as $cdek_city) {

					if (!$empty_country && !in_array($this->_clear($cdek_city['countryName']), $countries)) {
						continue;
					}

					if (!$empty_zone) {

						list($region) = explode(' ', str_replace('обл.', '', trim($cdek_city['regionName'])));

						if (!in_array($this->_clear($region), $regions)) {
							continue;
						}
					}

					list($city)= explode(',', $cdek_city['name']);

					if (mb_strpos($this->_clear($city), $cityName) === 0) {
						$available[] = $cdek_city;
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

					$city_info = reset($available);

				}

			} else {
				return FALSE;
			}

		} else {
			return FALSE;
		}

		return $city_info;
	}

	private function prepareCountry($name = '') {

		$countries = array();

		$name = $this->_clear($name);

		if (in_array($name, array('российская федерация', 'россия', 'russian', 'russian federation'))) {
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
		} else {
			$regions = $parts;
		}

		return $regions;
	}

	private function _clear($value) {
		$value = mb_convert_case($value, MB_CASE_LOWER, "UTF-8");
		return trim($value);
	}

	private function fomatAddress($exdata) {

		$address = '';

		if (!empty($exdata['shipping_lastname'])) $address .= $exdata['shipping_lastname'];

		if (!empty($exdata['shipping_firstname'])) {

			if ($address) $address .= " ";

			$address .= $exdata['shipping_firstname'];
		}

		if (!empty($exdata['shipping_company'])) $address .= ', ' . $exdata['shipping_company'];

		if (!empty($exdata['shipping_address_1'])) $address .= ', ' . $exdata['shipping_address_1'];

		if (!empty($exdata['shipping_address_2'])) $address .= ', ' . $exdata['shipping_address_2'];

		if (!empty($exdata['shipping_city'])) $address .= ', ' . $exdata['shipping_city'];

		if (!empty($exdata['shipping_zone'])) $address .= ', ' . $exdata['shipping_zone'];

		if (!empty($exdata['shipping_postcode'])) $address .= ', ' . $exdata['shipping_postcode'];

		if (!empty($exdata['shipping_country'])) $address .= ', ' . $exdata['shipping_country'];

		return $address;
	}

	private function getPaymentMethods() {

		$this->load->model('setting/extension');

        $payment_extensions = $this->model_setting_extension->getInstalled('payment');

        foreach ($payment_extensions as $key => $method) {
            if (!$this->config->get('payment_'. $method . '_status')) {
				unset($payment_extensions[$key]);
            }
        }

        $payment_methods = array();

        $files = glob(DIR_APPLICATION . 'controller/extension/payment/*.php');

        if ($files) {

            foreach ($files as $file) {

                $method = basename($file, '.php');

                if (in_array($method, $payment_extensions)) {
                    $this->load->language('extension/payment/' . $method);
                    $payment_methods[$method] = $this->language->get('heading_title');

                }
            }

        }

		return $payment_methods;
	}

	public function getAjaxPackingWeight() {

		if ($this->isAjax()) {

			$json = array();
			$json['packing_weight'] = $this->getPackingWeight((float)$this->request->get['weight']);
			$this->response->setOutput(json_encode($json));

		} else {
			$this->request->get['route'] = 'error/not_found';
			return $this->forward($this->request->get['route']);
		}

	}

	private function getPackingWeight($weight) {

		$packing_min_weight = $this->weight->convert((float)$this->setting['packing_min_weight'], $this->setting['packing_weight_class_id'], $this->setting['weight_class_id']);

		$packing_weight = 0;

		$packing_value = (float)$this->setting['packing_value'];

		if ($packing_value) {

			switch ($this->setting['packing_mode']) {
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

		} elseif ($packing_min_weight) {
			$packing_weight = $packing_min_weight;
		}

		return array(
			'weight'	=> $packing_weight,
			'prefix'	=> $this->setting['packing_prefix']
		);
	}

	private function getShippingMethods() {

		$this->load->model('setting/extension');

        $shipping_extensions = $this->model_setting_extension->getInstalled('shipping');

        foreach ($shipping_extensions as $key => $method) {
            if (!$this->config->get('shipping_' . $method . '_status')) {
				unset($shipping_extensions[$key]);
            }
        }

        $shipping_methods = array();

        $files = glob(DIR_APPLICATION . 'controller/extension/shipping/*.php');

        if ($files) {

            foreach ($files as $file) {

                $method = basename($file, '.php');

                if (in_array($method, $shipping_extensions)) {

                    $this->load->language('extension/shipping/' . $method);
                    $shipping_methods[$method] = $this->language->get('heading_title');

                }
            }

        }

		return $shipping_methods;
	}

	private function getInfo() {

		static $instance;

		if (!$instance) {
			$instance = $this->api->loadComponent('info');
		}

		return $instance;
	}

	private function validateOption() {

		if (!$this->setting['edit_mode']) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$post = $this->request->post;

		$post = !empty($this->request->post['cdek_integrator_setting']) ? $this->request->post['cdek_integrator_setting'] : array();

		foreach (array('city_id', 'weight_class_id', 'length_class_id', 'account', 'secure_password') as $item) {

			if (empty($post[$item])) {
				$this->error['setting'][$item] = $this->language->get('error_empty');
			}

		}

		if (!empty($post['new_order'])) {

			if (!is_numeric($post['new_order'])) {
				$this->error['setting']['new_order'] = $this->language->get('error_numeric');
			} elseif ($post['new_order'] < 0) {
				$this->error['setting']['new_order'] = $this->language->get('error_positive_numeric');
			}

		}

		if ($post['replace_items']) {

			if ($post['replace_item_name'] == '') {
				$this->error['setting']['replace_item_name'] = $this->language->get('error_empty');
			}

			foreach (array('replace_item_cost', 'replace_item_payment', 'replace_item_amount') as $item) {
				if ($post[$item] != '' && !is_numeric($post[$item])) {
					$this->error['setting'][$item] = $this->language->get('error_numeric');
				}
			}

		}

		if ($post['delivery_recipient_cost'] != '' && !is_numeric($post['delivery_recipient_cost'])) {
			$this->error['setting']['delivery_recipient_cost'] = $this->language->get('error_numeric');
		}

		if ($this->error && empty($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return (!$this->error);
	}

	private function validateOrderFrom() {

		if (!isset($this->setting['edit_mode'])) {
			$this->error['warning'][] = $this->language->get('error_permission');
		} else {

			$post = $this->request->post;

			if (!empty($post['cdek_orders'])) {

				$attempt_exists = $courier_exists = array();

				$tariff_list = $this->getInfo()->getTariffList();

				foreach ($post['cdek_orders'] as $order_info) {

					$order_id = $order_info['order_id'];

					if (!$order_info['city_id']) {
						$this->error['cdek_orders'][$order_id]['city_id'] = $this->language->get('error_empty');
					}

					if (!$order_info['recipient_city_id']) {
						$this->error['cdek_orders'][$order_id]['recipient_city_id'] = $this->language->get('error_empty');
					}

					if (utf8_strlen($order_info['recipient_name']) < 1) {
						$this->error['cdek_orders'][$order_id]['recipient_name'] = $this->language->get('error_empty');
					}

					if ($order_info['recipient_telephone'] == '') {
						$this->error['cdek_orders'][$order_id]['recipient_telephone'] = $this->language->get('error_empty');
					}

					if ($order_info['recipient_email'] != '') {

						if (!filter_var($order_info['recipient_email'], FILTER_VALIDATE_EMAIL)) {
							$this->error['cdek_orders'][$order_id]['recipient_email'] = $this->language->get('error_email');
						} else {

							$valid = true;

							$domain = rtrim(substr($order_info['recipient_email'], strpos($order_info['recipient_email'],'@')+1), '>');

							if (function_exists('checkdnsrr')) {
								$valid = checkdnsrr($domain, 'MX');
							} elseif (function_exists('getmxrr')) {
								$valid = getmxrr($domain);
							}

							if (!$valid) {
								$this->error['cdek_orders'][$order_id]['recipient_email'] = sprintf($this->language->get('error_domain'), $domain);
							}
						}

					}

					if ($order_info['cod'] && $order_info['currency_cod'] != $this->setting['currency_agreement']) {
						$this->error['cdek_orders'][$order_id]['currency_cod'] = $this->language->get('error_currency_cod');
					}

					if ($order_info['delivery_recipient_cost'] != '' && !is_numeric($order_info['delivery_recipient_cost'])) {
						$this->error['cdek_orders'][$order_id]['delivery_recipient_cost'] = $this->language->get('error_numeric');
					}

					if ($order_info['cdek_comment'] != '' && utf8_strlen($order_info['cdek_comment']) > 255) {
						$this->error['cdek_orders'][$order_id]['cdek_comment'] = $this->language->get('error_maxlength_255');
					}

					foreach ($order_info['package'] as $package_id => $package_info) {

						if ($package_info['weight'] == '' || !is_numeric($package_info['weight'])) {
							$this->error['cdek_orders'][$order_id]['package'][$package_id]['weight'] = $this->language->get('error_numeric');
						} elseif ($package_info['weight'] <= 0) {
							$this->error['cdek_orders'][$order_id]['package'][$package_id]['weight'] = $this->language->get('error_positive_numeric');
						}

						if ($package_info['pack']) {

							foreach (array('size_a', 'size_b', 'size_c') as $size) {
								if ($package_info[$size] == '' || !is_numeric($package_info[$size])) {

									$this->error['cdek_orders'][$order_id]['package'][$package_id]['size'] = $this->language->get('error_numeric');

									break;
								}
							}

						}

						if (!empty($package_info['item'])) {

							foreach ($package_info['item'] as $item_row => $package_item) {

								if ($package_item['weight'] == '' || !is_numeric($package_item['weight'])) {
									$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['weight'] = $this->language->get('error_numeric');
								}

								if ($package_item['cost'] == '' || !is_numeric($package_item['cost'])) {
									$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['cost'] = $this->language->get('error_numeric');
								}  elseif ($package_item['cost'] < 0) {
									$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['cost'] = $this->language->get('error_positive_numeric');
								}

								if (!empty($package_item['payment'])) {

									if (!is_numeric($package_item['payment'])) {
										$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['payment'] = $this->language->get('error_numeric');
									} elseif ($package_item['payment'] <= 0) {
										$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['payment'] = $this->language->get('error_positive_numeric');
									}

								}

								if ($package_item['amount'] == '' || !is_numeric($package_item['amount'])) {
									$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['amount'] = $this->language->get('error_numeric');
								} elseif ($package_item['amount'] <= 0) {
									$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['amount'] = $this->language->get('error_positive_numeric');
								}

							}

						} else {
							$this->error['cdek_orders'][$order_id]['package'][$package_id]['warning'] = 'Список вложений пуст';
						}

					}

					if (!$order_info['tariff_id'] || !isset($tariff_list[$order_info['tariff_id']])) {
						$this->error['cdek_orders'][$order_id]['tariff_id'] = $this->language->get('error_tariff_id');
					} else {

						$tariff_info = $tariff_list[$order_info['tariff_id']];

						if (in_array($tariff_info['mode_id'], array(1, 3))) { // Д-Д, С-Д

							if ($order_info['address']['street'] == '') {
								$this->error['cdek_orders'][$order_id]['address']['street'] = $this->language->get('error_empty');
							}

							if ($order_info['address']['house'] == '') {
								$this->error['cdek_orders'][$order_id]['address']['house'] = $this->language->get('error_empty');
							}

							if ($order_info['address']['flat'] == '') {
								$this->error['cdek_orders'][$order_id]['address']['flat'] = $this->language->get('error_empty');
							}

						} else { // C-C, C-Д

							if ($order_info['address']['pvz_code'] == '') {
								$this->error['cdek_orders'][$order_id]['address']['pvz_code'] = $this->language->get('error_empty');
							}

						}

						if (!empty($order_info['schedule'])) {

							$attempt_exists = array();

							foreach ($order_info['schedule'] as $attempt_row => $attempt_info) {

								if ($attempt_info['date'] == '' || !$this->validateDate($attempt_info['date'], FALSE)) {
									$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['date'] = $this->language->get('error_date');
								} elseif (!$this->validateDate($attempt_info['date'], TRUE, 'Y-m-d')) {
									$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['date'] = $this->language->get('error_date_futured');
								} else {

									$timestamp = strtotime(date('Y-m-d', strtotime($attempt_info['date'])));

									if (in_array($timestamp, $attempt_exists)) {
										$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['date'] = $this->language->get('error_attempt_date_exists');
									} else {
										$attempt_exists[] = strtotime(date('Y-m-d', strtotime($attempt_info['date'])));
									}

								}

								if ($attempt_info['time_beg'] == '' || !$this->validateTime($attempt_info['time_beg']) || $attempt_info['time_end'] == '' || !$this->validateTime($attempt_info['time_end'])) {
									$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['time'] = $this->language->get('error_time');
								} elseif ((strtotime($attempt_info['time_end']) - strtotime($attempt_info['time_beg'])) < 10800) {
									$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['time'] = $this->language->get('error_time_interval_3');
								}

								if ($attempt_info['comment'] != '' && utf8_strlen($attempt_info['comment']) > 255) {
									$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['comment'] = $this->language->get('error_maxlength_255');
								}

								if ($attempt_info['new_address']) {

									if (in_array($tariff_info['mode_id'], array(1, 3))) { // Д-Д, С-Д

										if ($attempt_info['street'] == '') {
											$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['street'] = $this->language->get('error_empty');
										}

										if ($attempt_info['house'] == '') {
											$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['house'] = $this->language->get('error_empty');
										}

										if ($attempt_info['flat'] == '') {
											$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['flat'] = $this->language->get('error_empty');
										}

									} else { // C-C, C-Д

										if ($attempt_info['pvz_code'] == '') {
											$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['pvz_code'] = $this->language->get('error_pvz_code');
										}

									}

								}

							}

						}

						if ($order_info['courier']['call']) {

							if ($order_info['courier']['date'] == '' || !$this->validateDate($order_info['courier']['date'], FALSE)) {
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_date');
							} elseif (!$this->validateDate($order_info['courier']['date'], TRUE, 'Y-m-d')) {
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_date_futured');
							} else {

								$timestamp = strtotime(date('Y-m-d', strtotime($order_info['courier']['date'])));

								if (in_array($timestamp, $courier_exists)) {
									$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_courier_date_exists');
								} else {
									$courier_exists[] = strtotime(date('Y-m-d', strtotime($order_info['courier']['date'])));
								}

							}

							if ($order_info['courier']['time_beg'] == '' || !$this->validateTime($order_info['courier']['time_beg']) || $order_info['courier']['time_end'] == '' || !$this->validateTime($order_info['courier']['time_end'])) {
								$this->error['cdek_orders'][$order_id]['courier']['time'] = $this->language->get('error_time');
							} elseif ((strtotime($order_info['courier']['time_end']) - strtotime($order_info['courier']['time_beg'])) < 10800) {
								$this->error['cdek_orders'][$order_id]['courier']['time'] = $this->language->get('error_time_interval_3');
							}

							if ($order_info['courier']['lunch_beg'] != '' || $order_info['courier']['lunch_end'] != '') {

								if ($order_info['courier']['lunch_beg'] == '' || !$this->validateTime($order_info['courier']['lunch_beg']) || ($order_info['courier']['lunch_end'] == '' || !$this->validateTime($order_info['courier']['lunch_end']))) {
									$this->error['cdek_orders'][$order_id]['courier']['lunch'] = $this->language->get('error_time');
								}

							}

							if ($order_info['courier']['city_id'] == '') {
								$this->error['cdek_orders'][$order_id]['courier']['city_id'] = $this->language->get('error_empty');
							}

							if ($order_info['courier']['street'] == '') {
								$this->error['cdek_orders'][$order_id]['courier']['street'] = $this->language->get('error_empty');
							}

							if ($order_info['courier']['house'] == '') {
								$this->error['cdek_orders'][$order_id]['courier']['house'] = $this->language->get('error_empty');
							}

							if ($order_info['courier']['flat'] == '') {
								$this->error['cdek_orders'][$order_id]['courier']['flat'] = $this->language->get('error_empty');
							}

							if ($order_info['courier']['send_phone'] == '') {
								$this->error['cdek_orders'][$order_id]['courier']['send_phone'] = $this->language->get('error_empty');
							}

							if ($order_info['courier']['sender_name'] == '') {
								$this->error['cdek_orders'][$order_id]['courier']['sender_name'] = $this->language->get('error_empty');
							}

							if ($order_info['courier']['comment'] != '' && utf8_strlen($order_info['courier']['comment']) > 255) {
								$this->error['cdek_orders'][$order_id]['courier']['comment'] = $this->language->get('error_maxlength_255');
							}

						}

					}
				}

			} else {
				$this->error['warning'][] = $this->language->get('error_empty_order_list');
			}

		}

		if ($this->error && empty($this->error['warning'])) {
			$this->error['warning'][] = $this->language->get('error_warning');
		}

		return (!$this->error);
	}

	private function validateDate($str, $current = TRUE, $format = 'Y-m-d') {

		$status = TRUE;

		if (!$str) {
			$status = FALSE;
		} elseif (date($format, strtotime($str)) != trim($str)) {
			$status = FALSE;
		} elseif ($current && strtotime($str) <= strtotime(date($format))) {
			$status = FALSE;
		}

		return $status;
	}

	private function validateTime($time, $format = 'H:i') {

		$status = TRUE;

		if (!$time) {
			$status = FALSE;
		} elseif (strtotime($time) === FALSE) {
			$status = FALSE;
		}

		return (bool)$status;
	}

	private function declination($number, $titles) {
		$cases = array (2, 0, 1, 1, 1, 2);
		return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
	}

	private function getDateExecuted($format = 'Y-m-d\TH:i:sP') {
		return gmdate($format, $this->time_execute);
	}

	private function isAjax() {
		return (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && $this->request->server['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}

	private function getSetting() {
		return $this->config->get('cdek_integrator_setting');
	}

	private function init() {

		$this->setting = $this->getSetting();
		$this->time_execute = time();
		$this->new_application = empty($this->setting);

		if(php_sapi_name() != "cli") {
			$this->setting['edit_mode'] = $this->user->hasPermission('modify', 'extension/module/cdek_integrator');
		}

		$this->load->language('extension/module/cdek_integrator');

		$this->document->addStyle('view/stylesheet/cdek_integrator.css');
		$this->document->addScript('view/javascript/jquery/cdek_integrator.js');

		require_once DIR_SYSTEM . 'library/cdek_integrator/class.app.php';
		app::registry()->create($this->registry);

		require_once DIR_SYSTEM . 'library/cdek_integrator/class.cdek_integrator.php';
		$account = !empty($this->setting['account']) ? $this->setting['account'] : '';
		$secure = !(empty($this->setting['secure_password'])) ? $this->setting['secure_password'] : '';
		$this->api = new cdek_integrator($account, $secure);

		if (!empty($this->setting['account']) && !empty($this->setting['secure_password'])) {
			$this->api->setAuth($this->setting['account'], $this->setting['secure_password']);
		}

	}

	public function checkInstall() {
		$status = $this->model_tool_cdektool->checkInstalled('module', 'cdek_integrator');

		if(!$status) {
			$this->install();
		}
	}

	public function install() {

		$this->load->model('extension/module/cdek_integrator');
		$this->model_extension_module_cdek_integrator->install();

	}

	public function uninstall() {

		$this->load->model('extension/module/cdek_integrator');
		$this->model_extension_module_cdek_integrator->uninstall();

	}

	public function cron()
	{
		$this->load->model('extension/module/cdek_integrator');
		$this->load->model('sale/order');

		if(!isset($this->setting['order_status_rule'])) {
			$this->log->write('Не заданы правила соответствия статусов');
			return;
		}

		$_order_status_rule = $this->setting['order_status_rule'];
		$status_rules = array();

		foreach ($_order_status_rule as $_rule) {
			$status_rules[(int)$_rule['cdek_status_id']] = $_rule;
		}

		$_dispatches = $this->model_extension_module_cdek_integrator->getDispatchesToSync(false);

		$dispatches = array();
		$orders = array();
		foreach ($_dispatches as $key => $dispatch_info)
		{
			$orders[] = array(
				'dispatch_number' => $dispatch_info['dispatch_number']
			);

			$dispatches[$dispatch_info['dispatch_number']] = $dispatch_info;
		}

		if (!$orders) {
			echo "Not found dispatches to check."; return;
		}

		$component = $this->api->loadComponent('order_status');

		$component->setData(array('order' => $orders));

		$status = $this->api->sendData($component);

		if (!isset($status->Order)) {
			echo "ERROR: cant get cdek statuses."; return;
		}



		foreach ($status->Order as $key => $order)
		{

			$dispatch_number = (string)$order->attributes()->DispatchNumber;

			if (isset($order->attributes()->ErrorCode)){
                echo $order->attributes()->Msg .$dispatch_number.PHP_EOL;
                continue;
            }
			if(!isset($dispatches[$dispatch_number])) {
                echo "WARNING: Not isses dispatch ".$dispatch_number.PHP_EOL;
                continue;
            }

			$status_id = (int)$order->Status->attributes()->Code;


			$dispatch = $dispatches[$dispatch_number];

			if((int)$dispatch['status_id'] == (int)$status_id) {
				echo "Order with dispatch ".$dispatch_number." not chenged".PHP_EOL;
				continue;
			}

			echo "Working with ".$dispatch_number." status ".$status_id.PHP_EOL;

			$filter_data = array(
				$dispatch_info['order_id'] => $dispatch
			);

			$update = $this->sync($filter_data);

			if (isset($update[$dispatch['order_id']]))
			{
				$this->model_extension_module_cdek_integrator->editDispatch($dispatch['order_id'], $update[$dispatch['order_id']]);

			}

			echo PHP_EOL;
		}
	}

	private function clearCurrencyFormat($value, $decimal_place = 2, $decimal_point = '.', $thousand_point = ' ') {
		return number_format(round($value, (int)$decimal_place), (int)$decimal_place, $decimal_point, $thousand_point);
	}

	public function showPdf() {
		$order_id = 0;
		if(isset($this->request->get['order_id']) && (int)$this->request->get['order_id']) {
			$order_id = (int)$this->request->get['order_id'];
		}

		$file = DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf';

		if (file_exists($file) && is_file($file))
		{
			$content = file_get_contents($file);

	        header('Content-Type: application/pdf');
	        header('Content-Length: ' . $content);
	        header('Content-Disposition: inline; filename="invoice-'.$order_id.'.pdf"');
	        header('Cache-Control: private, max-age=0, must-revalidate');
	        ini_set('zlib.output_compression','0');

	        die($content);

			$rdata['pdf'] = HTTP_CATALOG . 'download/cdek/order-' . $this->request->get['order_id'] . '.pdf';
		} else {
			echo 'not found pdf for order '.$order_id;
		}
	}

	public function getCityByName($cityName = null) {
		$cdek_cities = array();

		if($cityName) {
			$cdek_cities = $this->getInfo()->getCityByName($cityName);
			return $cdek_cities;
		}

		if(isset($this->request->get['q'])) {
			$cityName = $this->request->get['q'];
		} elseif(isset($this->request->get['name_startsWith'])) {
			$cityName = $this->request->get['name_startsWith'];
		} elseif(isset($this->request->get['search'])) {
			$cityName = $this->request->get['search'];
		}

		if($cityName) {
			$cdek_cities = $this->getInfo()->getCityByName($cityName);
		}

		echo json_encode($cdek_cities);
	}

	private function templateOutput($tpl, $data) {
		return $this->load->view('extension/module/cdek_integrator/'.$tpl, $data);
	}
}

?>