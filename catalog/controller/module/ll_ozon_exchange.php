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

	public function addOrderAfter($route, $data = null, $order_id = null) {
		if (version_compare(VERSION, '2.2', '<')) {
			$this->load->model('checkout/order');

			$order_id = $route;
			$data = $this->model_checkout_order->getOrder($order_id);

			$code = explode('.', $data['shipping_code']);
		} elseif (version_compare(VERSION, '2.3', '<')) {
			$new_data = $order_id;
			$order_id = $data;
			$data = $new_data;
			$code = explode('.', $data[0]['shipping_code']);
		} else {
			$code = explode('.', $data[0]['shipping_code']);
		}

		$this->load->model($this->ll->getRoute());
		$order = $this->{$this->ll->getModel()}->getExportOrder($order_id);

		if ($code[0] == 'll_ozon') {
			$tariff = str_replace('ll_ozon_', '', $code[1]);

			$export = [
				'order_id'  => $order_id,
				'from_city' => isset($this->session->data[$code[0]]['from_city']) ? $this->session->data[$code[0]]['from_city'] : null,
				'to_city'   => isset($this->session->data[$code[0]]['to_city']) ? $this->session->data[$code[0]]['to_city'] : null,
				'tariff'    => $tariff,
				'pvz'       => isset($this->session->data[$code[0]][$tariff]) ? $this->session->data[$code[0]][$tariff] : null,
				'weight'    => isset($this->session->data[$code[0]]['weight']) ? $this->session->data[$code[0]]['weight'] : null,
			];
		}

		if (!$order && $code[0] == 'll_ozon') {
			$this->{$this->ll->getModel()}->addExportOrder($export);
		} else {
			if ($code[0] == 'll_ozon') {
				$this->{$this->ll->getModel()}->updateExportOrder($export);
			} else {
				$this->{$this->ll->getModel()}->deleteExportOrder($order_id);
			}
		}
	}

	public function editOrderAfter($route, $data = null) {
		if (version_compare(VERSION, '2.2', '<')) {
			$this->load->model('checkout/order');

			$order_id = $route;
			$data = $this->model_checkout_order->getOrder($order_id);

			$code = explode('.', $data['shipping_code']);
		} else {
			$order_id = $data[0];
			$code = explode('.', $data[1]['shipping_code']);
		}

		$this->load->model($this->ll->getRoute());
		$order = $this->{$this->ll->getModel()}->getExportOrder($order_id);

		if ($code[0] == 'll_ozon') {
			$tariff = str_replace('ll_ozon_', '', $code[1]);

			$export = [
				'order_id'  => $order_id,
				'from_city' => isset($this->session->data[$code[0]]['from_city']) ? $this->session->data[$code[0]]['from_city'] : null,
				'to_city'   => isset($this->session->data[$code[0]]['to_city']) ? $this->session->data[$code[0]]['to_city'] : null,
				'tariff'    => $tariff,
				'pvz'       => isset($this->session->data[$code[0]][$tariff]) ? $this->session->data[$code[0]][$tariff] : null,
				'weight'    => isset($this->session->data[$code[0]]['weight']) ? $this->session->data[$code[0]]['weight'] : null,
			];
		}

		if (!$order && $code[0] == 'll_ozon') {
			$this->{$this->ll->getModel()}->addExportOrder($export);
		} else {
			if ($code[0] == 'll_ozon') {
				$this->{$this->ll->getModel()}->updateExportOrder($export);
			} else {
				$this->{$this->ll->getModel()}->deleteExportOrder($order_id);
			}
		}
	}

	public function trackOrderStatus() {
		if (isset($this->request->get['cron_key']) && $this->request->get['cron_key'] == $this->config->get($this->ll->getPrefix() . '_cron_key')) {
			$this->load->language($this->ll->getRoute());

			$this->load->model($this->ll->getRoute());

			$order_ids = $this->{$this->ll->getModel()}->getTrackingOrders($this->config->get($this->ll->getPrefix() . '_update_statuses'), $this->config->get($this->ll->getPrefix() . '_update_day'));

			if (empty($order_ids)) {
				return;
			}

			foreach ($order_ids as $key => $o) {
				$order = $this->{$this->ll->getModel()}->getOrderInfo((int)$o['order_id']);

				if (!$order['postingNumber']) {
					if ($key == count($order_ids)) {
						return;
					} else {
						continue;
					}
				}

				$articles['articles'][] = $order['postingNumber'];
				$posting_numbers[$o['order_id']] = $order['postingNumber'];
				$orders_info[$o['order_id']] = $order;
			}

			if (!isset($articles)) {
				return;
			}

			$results = $this->api->update_status($articles);
			$return = [];

			if (!$results) {
				return;
			}

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

										$this->notifyOrderStatus($orders_info[$order_id], $params);

										if ($params['notify_email']) {
											$this->notifyCustomerEmail($orders_info[$order_id], $params);
										}

										if ($params['notify_sms']) {
											$this->notifyCustomerSMS($orders_info[$order_id], $params);
										}
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

									if ($params['notify_email']) {
										$this->notifyCustomerEmail($orders_info[$order_id], $params);
									}

									if ($params['notify_sms']) {
										$this->notifyCustomerSMS($orders_info[$order_id], $params);
									}
								}
							}
						}
					}
				}
			}
		}
	}

	public function trackingOrderStatus() {
		if (isset($this->request->get['cron_key']) && $this->request->get['cron_key'] == $this->config->get($this->ll->getPrefix() . '_cron_key')) {
			$this->load->model($this->ll->getRoute());

			$order = $this->{$this->ll->getModel()}->getOrderInfo((int)$this->request->post['order_id']);
			$data = $this->request->post;

			$this->notifyOrderStatus($order, $data);

			if ($data['notify_email']) {
				$this->notifyCustomerEmail($order, $data);
			}

			if ($data['notify_sms']) {
				$this->notifyCustomerSMS($order, $data);
			}
		}
	}

	protected function notifyOrderStatus($order, $data) {
		$this->load->model('checkout/order');

		$comment = $this->getOrderMacros($order, $data, $data['comment']);

		$this->model_checkout_order->addOrderHistory($order['order_id'], $data['order_status'], $comment, $data['notify']);
	}

	protected function notifyCustomerEmail($order, $data) {
		$subject = 'Изменение статуса заказа Ozon Rocket';
		$message = $this->getOrderMacros($order, $data, $data['email']);

		$this->ll->sendMail($order['email'], $subject, $message);
	}

	protected function notifyCustomerSMS($order, $data) {
		$options = [
			'to'       => $order['telephone'],
			'sender'   => $this->config->get($this->ll->getPrefix() . '_sms_sender'),
			'login'    => $this->config->get($this->ll->getPrefix() . '_sms_login'),
			'password' => $this->config->get($this->ll->getPrefix() . '_sms_password'),
			'message'  => $this->getOrderMacros($order, $data, $data['sms']),
		];

		$this->ll->sms($options);
	}

	protected function getOrderMacros($order, $data, $message) {
		$this->load->model($this->ll->getRoute());

		$from_data = $this->{$this->ll->getModel()}->getCity($order['from_city']);
		$to_data = $this->{$this->ll->getModel()}->getCity($order['to_city']);
		$pvz = $this->{$this->ll->getModel()}->getPvz($order['pvz']);

		$input = [
			'{{exchange_number}}',
			'{{exchange_from_city}}',
			'{{exchange_to_city}}',
			'{{exchange_tariff}}',
			'{{exchange_pvz}}',
			'{{delivery_status_name}}',
			'{{delivery_status_date}}',
			'{{order_id}}',
			'{{invoice_no}}',
			'{{invoice_prefix}}',
			'{{store_name}}',
			'{{store_url}}',
			'{{firstname}}',
			'{{lastname}}',
			'{{email}}',
			'{{telephone}}',
			'{{fax}}',
			'{{payment_firstname}}',
			'{{payment_lastname}}',
			'{{payment_company}}',
			'{{payment_address_1}}',
			'{{payment_address_2}}',
			'{{payment_postcode}}',
			'{{payment_city}}',
			'{{payment_zone}}',
			'{{payment_country}}',
			'{{payment_method}}',
			'{{payment_code}}',
			'{{shipping_firstname}}',
			'{{shipping_lastname}}',
			'{{shipping_company}}',
			'{{shipping_address_1}}',
			'{{shipping_address_2}}',
			'{{shipping_postcode}}',
			'{{shipping_city}}',
			'{{shipping_zone}}',
			'{{shipping_country}}',
			'{{shipping_method}}',
			'{{shipping_code}}',
			'{{comment}}',
			'{{total}}',
			'{{commission}}',
			'{{currency_code}}',
			'{{date_added}}',
			'{{date_modified}}',
		];

		$output = [
			'exchange_number'         => $order['logisticOrderNumber'],
			'exchange_from_city'      => $from_data['name'],
			'exchange_to_city'        => $to_data['name'],
			'exchange_variant'        => $this->statics->get('variants')[$order['tariff']]['name'],
			'exchange_pvz'            => isset($pvz['address']) ? $pvz['address'] : $pvz['fullAddress'],
			'delivery_status_name'    => $data['delivery_status_name'],
			'delivery_status_date'    => $data['delivery_status_date'],
			'order_id'                => $order['order_id'],
			'invoice_no'              => $order['invoice_no'],
			'invoice_prefix'          => $order['invoice_prefix'],
			'store_name'              => $order['store_name'],
			'store_url'               => $order['store_url'],
			'firstname'               => $order['firstname'],
			'lastname'                => $order['lastname'],
			'email'                   => $order['email'],
			'telephone'               => $order['telephone'],
			'fax'                     => $order['fax'],
			'payment_firstname'       => $order['payment_firstname'],
			'payment_lastname'        => $order['payment_lastname'],
			'payment_company'         => $order['payment_company'],
			'payment_address_1'       => $order['payment_address_1'],
			'payment_address_2'       => $order['payment_address_2'],
			'payment_postcode'        => $order['payment_postcode'],
			'payment_city'            => $order['payment_city'],
			'payment_zone'            => $order['payment_zone'],
			'payment_country'         => $order['payment_country'],
			'payment_method'          => $order['payment_method'],
			'payment_code'            => $order['payment_code'],
			'shipping_firstname'      => $order['shipping_firstname'],
			'shipping_lastname'       => $order['shipping_lastname'],
			'shipping_company'        => $order['shipping_company'],
			'shipping_address_1'      => $order['shipping_address_1'],
			'shipping_address_2'      => $order['shipping_address_2'],
			'shipping_postcode'       => $order['shipping_postcode'],
			'shipping_city'           => $order['shipping_city'],
			'shipping_zone'           => $order['shipping_zone'],
			'shipping_country'        => $order['shipping_country'],
			'shipping_method'         => $order['shipping_method'],
			'shipping_code'           => $order['shipping_code'],
			'comment'                 => $order['comment'],
			'total'                   => $order['total'],
			'commission'              => $order['commission'],
			'currency_code'           => $order['currency_code'],
			'date_added'              => $order['date_added'],
			'date_modified'           => $order['date_modified'],
		];

		return html_entity_decode(str_replace($input, $output, $message));
	}

	public function updatePVZ() {
		if (isset($this->request->get['cron_key']) && $this->request->get['cron_key'] == $this->config->get($this->ll->getPrefix() . '_cron_key')) {
			$this->load->language($this->ll->getRoute());

			$this->load->model($this->ll->getRoute());

			$this->{$this->ll->getModel()}->clearData();

			$this->getData();
		}
	}

	protected function getData($current = 500, $next = false) {
		if (!$next) {
			$pvzs = $this->{$this->ll->getModel()}->getApi('delivery/variants', ['payloadIncludes.includeWorkingHours' => 'true', 'payloadIncludes.includePostalCode' => 'true', 'pagination.size' => 500]);
		} else {
			$pvzs = $this->{$this->ll->getModel()}->getApi('delivery/variants', ['payloadIncludes.includeWorkingHours' => 'true', 'payloadIncludes.includePostalCode' => 'true', 'pagination.size' => 500, 'pagination.token' => $next]);
		}

		if (isset($pvzs['totalCount'])) {
			$current = $current + 500;
			$next = $pvzs['nextPageToken'];
			$pvzs = $pvzs['data'];
		} else {
			return;
		}

		if (!empty($pvzs)) {
			foreach ($pvzs as $pvz) {
				if (isset($pvz['settlement'])) {
					$city_name = str_replace(['г. ', 'рп. ', 'п. ', 'пгт. ', 'д. ', 'с. ', 'дер ', 'ст-ца ', 'х. ', 'гп ', 'аул ', 'дп. '], '', $pvz['settlement']);

					$city_id = $this->{$this->ll->getModel()}->getCityByName($city_name);

					if (!$city_id) {
						$city_id = $this->{$this->ll->getModel()}->addCity($city_name);
					}
				} else {
					continue;
				}

				if (isset($pvz['region'])) {
					$region_id = $this->{$this->ll->getModel()}->getRegion($pvz['region']);

					if (!$region_id) {
						$region_id = $this->{$this->ll->getModel()}->addRegion($pvz['region']);
					}
				} else {
					$region_id = $this->{$this->ll->getModel()}->getRegion($city_name);

					if (!$region_id) {
						$region_id = $this->{$this->ll->getModel()}->addRegion($city_name);
					}
				}

				$this->{$this->ll->getModel()}->updateCity($city_id, $region_id);
				$this->{$this->ll->getModel()}->addPvz($pvz, $region_id, $city_id);
			}

			$this->getData($current, $next);
		}
	}
}

class ControllerModuleLLOzonExchange extends ControllerExtensionModuleLLOzonExchange {}
