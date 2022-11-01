<?php
/*
* CDL Ozon Seller for OpenCart 2.1
* @author Dmitri Cheremisin
*/
class ControllerModuleOzonSeller extends Controller
{
	// Получение категорий маркетплейса
	public function getCategoryOzon()
	{
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {
			$this->load->model('module/ozon_seller');
			$url = 'https://api-seller.ozon.ru/v2/category/tree';
			$response = $this->makeRequest($url, $request = 'POST', $data = '', $api = 'ozon');
			if (!empty($response['result'])) {
				$this->model_module_ozon_seller->saveOzonCategory($response);
				$this->log('Категории загружены', 0);
			} else {
				$this->log($response['error']['message'], 0);
			}
		}
	}

	/* Получение списка не обработанных заказов */
	public function getNewOrdersOzon() {

		header('Content-Type: text/html; charset=utf-8');
		$status_ms = $this->config->get('ozon_seller_chek_ms');
		echo ($status_ms == 0) ? 'Интеграция с Мой склад отключена<br />' : false;
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {
			$this->load->model('module/ozon_seller');
			$response = $this->getOrdersOzonByStatus('awaiting_packaging');
			if (!empty($response)) {
				foreach ($response as $info) {
					if (empty($info['products']) || empty($info['products'][0]['offer_id'])) {
						continue;
					}
					$posting_number = $info['posting_number'];
					$shipment_date = date('Y-m-d H:i:s', strtotime($info['shipment_date']));
					$products = $info['products'];
					$status = htmlspecialchars($info['status']);
					$barcodes = $info['barcodes'];
					$analytics_data = $info['analytics_data'];

					$order = $this->model_module_ozon_seller->getMyOrder($posting_number);

					if (empty($order)) {
						$guid = '';
						$order_id = '';
						// cоздать заказ в OC
						if ($this->config->get('ozon_seller_status_order_oc')) {
							$order_id = $this->createOrderOc($info);
						}
						// создать заказ в МС
						if ($status_ms) {
							$guid = $this->creatOrderMoysklad($posting_number, $shipment_date, $products, $barcodes, $status, $analytics_data);
						} else {
							$this->createOrderWithoutMs($posting_number, $shipment_date, $status);
						}
						// интеграция с модулем администрирования
						if (!empty($this->config->get('packing_order_version'))) {
							$this->saveAdminMarketplace($info, $guid, $order_id);
						}
					} else {
						// ozon может изменить дату доставки, значит меняется дата отгрузки
						if ($order[0]['shipment_date'] != $shipment_date) {
							if ($status_ms) {
								$guid = $order[0]['guid'];
								$this->replaceShipmentDate($posting_number, $guid, $shipment_date);
							} else {
								$this->model_module_ozon_seller->updateShipmentDate($posting_number, $shipment_date);
								$this->log($posting_number . ' дата отгрузки изменена на ' . $shipment_date, 0);
							}
						}
						echo 'Отправление ' . $posting_number . ' пропущено, т.к уже создано <br />';
					}
				}
			} else {
				echo 'Нет новых заказов<br />';
			}
			//трекинг закзов порядок выполнения важен
			$this->statusAwaitingDeliver();
			$this->checkCancelledReturn();
			$this->finalCheckReturnsOzon();
			$this->statusCancelled();
			$this->getReturnsOzon();
			$this->statusDelivering();
			//акт и ттн
			if ($this->config->get('ozon_seller_act')) {
				$this->getActId();
			}
		}
	}

	//Создать заказ без МС
	private function createOrderWithoutMs($posting_number, $shipment_date, $status)
	{
		$this->load->model('module/ozon_seller');
		$this->model_module_ozon_seller->saveOrder($posting_number, $shipment_date, $status);
		echo $posting_number . ' успешно создан<br />';
	}

	/* Обработка заказов с FBO */
	public function checkFboOrders()
	{
		header('Content-Type: text/html; charset=utf-8');
		$status_ms = $this->config->get('ozon_seller_chek_ms');
		echo ($status_ms == 0) ? 'Интеграция с Мой склад отключена' : false;
		$status_fbo = $this->config->get('ozon_seller_chek_fbo');
		echo ($status_fbo == 0) ? 'Включите FBO в настройках модуля' : false;

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass') && $status_fbo) {
			//проверим возвраты
			if ($this->config->get('ozon_seller_autoreturn')) {
				$this->fboReturn();
			}
			$response = $this->getOrdersFbo($status_ozon = 'delivered');
			if (!empty($response)) {
				$this->load->model('module/ozon_seller');
				$new_orders = array();
				foreach ($response as $info) {
					//иногда озон не сразу подгружает товары к новым заказам
					if (empty($info['products'])) {
						$this->log($info['posting_number'] . ' ошибка: ozon вернул пустой массив товаров для отправления. Заказ будет выгружен в следующий раз.', 0);
						continue;
					}
					foreach ($info['products'] as $product) {
						if (empty($product['offer_id'])) {
							$this->log($info['posting_number'] . ' ошибка: ozon не указал offer_id товаров для отправления. Заказ будет выгружен в следующий раз.', 0);
							continue;
						}
					}
					$order = $this->model_module_ozon_seller->getMyOrder($info['posting_number']);
					if (empty($order)) {
						$new_orders[] = $info;
					}
				}
				if ($status_ms) {
					$this->creatFboOrdersMoysklad($new_orders);
				} else {
					$shipment_date = date('Y-m-d H:i:s', strtotime($info['shipment_date']));
					$this->createOrderWithoutMs($info['posting_number'], $shipment_date, $info['status']);
				}
				if ($this->config->get('ozon_seller_status_order_fbo_oc')) {
					$order_oc = $this->model_module_ozon_seller->getOrderOc($posting_number);
					if (!empty($order_oc[0]['order_id'])) {
						$this->changeOrderStatusOC($order_oc[0]['order_id'], $this->config->get('ozon_seller_status_delevered'));
					}
				}
			} else {
				echo 'Нет новых заказов<br />';
			}
		}
	}

	/* Получение списка заказов FBO */
	private function getOrdersFbo($status_ozon) {

		$url = 'https://api-seller.ozon.ru/v2/posting/fbo/list';
		$date = new DateTime();
		$date->modify('-1 month');
		$offset = 0;
		$orders = array();
		do {
			$data = array(
				'dir' => 'asc',
				'limit' => 50,
				'offset' => $offset,
				'filter' => array(
					'since' => $date->format(DATE_ATOM),
					'status' => $status_ozon,
					'to' => date(DATE_ATOM),
				),
				'with' => array(
					'financial_data' => true
				)
			);
			$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
			foreach ($response['result'] as $order) {
				$orders[] = $order;
			}
			$offset += 50;
		} while (count($response['result']) == 50);
		return $orders;
	}

	/* Передаем заказы FBO в Мой склад */
	private function creatFboOrdersMoysklad($orders) {

		$this->load->model('module/ozon_seller');
		foreach ($orders as $order) {
			$posting_number = htmlspecialchars($order['posting_number']);
			$data_order = array(
				'name' => $posting_number,
				'description' => 'FBO',
				'organization' => array(
					'meta' => array(
						'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/organization/' . $this->config->get('ozon_seller_organization'),
						'type' => 'organization',
						'mediaType' => 'application/json'
					)
				),
				'agent' => array(
					'meta' => array(
						'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/counterparty/' . $this->config->get('ozon_seller_agent'),
						'type' => 'counterparty',
						'mediaType' => 'application/json'
					)
				),
				'state' => array(
					'meta' => array(
	     				'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/' . $this->config->get('ozon_seller_status_delivered'),
	      				'type' => 'state',
	     				'mediaType' => 'application/json'
					)
				),
				'store' => array(
					'meta' => array(
	            		'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/store/' . $this->config->get('ozon_seller_store_fbo'),
	            		'type' => 'store',
	            		'mediaType' => 'application/json'
	    			)
				),
				'project' => array(
					'meta' => array(
      			'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/project/' . $this->config->get('ozon_seller_project'),
      			'type' => 'project',
      			'mediaType' => 'application/json'
    			)
	    	),
				'salesChannel' => array(
					'meta' => array(
						'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/saleschannel/' . $this->config->get('ozon_seller_saleschannel_ms'),
						'type' => 'saleschannel',
						'mediaType' => 'application/json'
					)
				)
			);
			$data_products = array(); //кладем товары в массив
			$stop = '';
			foreach ($order['products'] as $product) {
				if ($this->config->get('ozon_seller_entry_offer_id') == $this->config->get('ozon_seller_connect_prod_shop')) {
					$article = $product['offer_id'];
				} else {
					$art = $this->model_module_ozon_seller->searchExportProduct($product['offer_id']);
					$article = $art[0][$this->config->get('ozon_seller_connect_prod_shop')];
				}
				if (empty($article) || !isset($article)) {
					$this->log($posting_number . ' ошибка: товар или комплект ' . $product['offer_id'] . ' не найден в товарах Opencart. Заказ будет перевыгружен.', 0);
					$stop = 'stop';
	 				break;
				}
				$filter = $this->config->get('ozon_seller_connect_prod_ms');
				$ms_product = $this->getMsProduct($article, $filter);
				$prod_type = 'product';

				if (empty($ms_product['rows'])) {
					$ms_product = $this->getMsBundle($article, $filter);
					$prod_type = 'bundle';
				}

				if (empty($ms_product['rows'])) {
					$this->log($posting_number . ' ошибка: товар или комплект ' . urlencode($article) . ' не найден в Мой склад. Заказ будет перевыгружен.', 0);
	 				$stop = 'stop';
	 				break;
				}

				foreach ($ms_product['rows'] as $ms_prod) {
					$prod_url = $ms_prod['meta']['href'];
				}

				$price = $product['price'];
				if ($this->config->get('ozon_seller_komission_fbo') == 1) {
					foreach ($order['financial_data']['products'] as $fin_product) {
						if ($fin_product['product_id'] == $product['sku']) {
							if (empty($fin_product['item_services']['marketplace_service_item_fulfillment'])) {
								$stop = 'stop';
								break;
							} else {
								$packaging = $fin_product['item_services']['marketplace_service_item_fulfillment']; //сборка товаров
								$highway = $fin_product['item_services']['marketplace_service_item_direct_flow_trans']; //магистраль
								$last_mile = $fin_product['item_services']['marketplace_service_item_deliv_to_customer']; //последняя миля
								$komission = ($packaging + $highway + $last_mile) / $product['quantity']; //комиссия одной ед. товара
								$price = $fin_product['payout'] + $komission;
								$price = round($price, 2);
							}
						}
					}
				}
				$data_products[] = array(
					'quantity' => $product['quantity'],
					'price' => floatval($price) * 100,
					'discount' => 0,
					'vat' => 0,
					'assortment' => array(
						'meta' => array(
							'href' => $prod_url,
							'type' => $prod_type,
							'mediaType' => 'application/json'
						)
					),
					'reserve' => $product['quantity']
				);
			}

			if ($stop == 'stop') {
				//заказ не будет создан если есть стоп
				continue;
			} else {
				$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder';
				$response = $this->makeRequest($url, $request = 'POST', $data_order, $api = 'ms');

				if (!empty($response['errors'])) {
				 	$this->log($posting_number . ' ошибка при создании заказов FBO: '. json_encode($response['errors'], JSON_UNESCAPED_UNICODE), 0);
				} else {
					$guid = $response['id']; //id заказа в МС
					$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid . '/positions';
					$respons = $this->makeRequest($url, $request = 'POST', $data_products, $api = 'ms');

					if (!empty($respons['errors'])) {
					 	$this->log($posting_number . ' ' . $url . ' ошибка: '. json_encode($respons['errors'], JSON_UNESCAPED_UNICODE), 0);
					} else {
						$payment = $this->paymentOrder($guid);
						if (!empty($payment['errors'])) {
							$this->log($posting_number . ' ошибка: '. json_encode($payment['errors'], JSON_UNESCAPED_UNICODE), 0);
						}
						$demand = $this->demandOrder($guid);
						if (!empty($demand['errors'])) {
							$this->log($posting_number . ' ошибка: '. json_encode($demand['errors'], JSON_UNESCAPED_UNICODE), 0);
						}
						$status = htmlspecialchars($order['status']);
						$this->model_module_ozon_seller->saveOrderFull($posting_number, $shipment_date = '', $status, $guid);
						echo $posting_number . ' успешно создан в МС <br />';
					}
				}
			}
		}
	}

	//возвраты FBO
	private function fboReturn() {

    $this->load->model('module/ozon_seller');
    //получим заказы оформленные на возврат, но еще не поступившие на склад fbo
    $url = 'https://api-seller.ozon.ru/v2/returns/company/fbo';
    $offset = 0;
    $returns = array();
    do {
      $data = array(
        'filter' => array(
          'status' => array('Created')
        ),
        'limit' => 50,
        'offset' => $offset
      );
      $response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
      $returns = array_merge($returns, $response['returns']);
      $offset += 50;
    } while ($offset < $response['count'] );
    //сменим статус заказа в БД
    if (empty($returns)) {
      echo 'Нет новых возвратов FBO для обработки<br />';
    } else {
      foreach ($returns as $return) {
        $posting_number = $return['posting_number'];
        $order_info = $this->model_module_ozon_seller->getMyOrder($posting_number);
        if (!empty($order_info)) {
          if ($order_info[0]['status'] == 'delivered') {
            $this->model_module_ozon_seller->updateStatusOzon($posting_number, $status = 'return_fbo');
          }
          echo $posting_number . ' возвращается на склад FBO<br />';
        }
      }
    }

    //проверим пришел ли заказ на склад FBO
    $returns = $this->model_module_ozon_seller->getOrderByStatus('return_fbo');

    if (!empty($returns)) {
      foreach ($returns as $return) {
        $posting_number = $return['posting_number'];
        $order_info_ozon = $this->getFboPosting($posting_number);

				if (isset($order_info_ozon['error']['code']) &&
				 $order_info_ozon['error']['code'] == 'NOT_FOUND_ERROR') {
					echo 'FBO: ' . $posting_number . ' обработка возврата пропущена, т.к он не найден в Ozon';
					$this->log('FBO: ' . $posting_number . ' обработка возврата пропущена, т.к он не найден в Ozon', 0);
					continue;
				}
        $status = $order_info_ozon['result']['status'];

        if ($status == 'cancelled') {
          echo $posting_number . ' возврат принят на склад FBO<br />';
          //если включена интеграция с МС создадим возврат и исходящий платеж
          if ($this->config->get('ozon_seller_chek_ms')) {
            $guid = $return['guid'];
            $order_info_ms = $this->getOrderMoyskald($guid);
						if (empty($order_info_ms['demands'])) {
							$this->log('Автоматическая обработка возврата в МС: ' . $posting_number . ' не обнаружена отгрузка!', 0);
							echo 'Автоматическая обработка возврата в МС: ' . $posting_number . ' не обнаружена отгрузка!<br />';
							continue;
						}
            if (count($order_info_ms['demands']) > 1) {
              $this->log('ВНИМАНИЕ: ' . $posting_number . ' обнаружено несколько отгрузок в МС!', 0);
            }
						//получаем предзаполненный возврат
            $demand = $this->getSalesReturnMs($order_info_ms);
            $positions_return = array();

            foreach ($order_info_ozon['result']['products'] as $product_ozon) {
              foreach ($demand['positions']['rows'] as $product_ms) {
                //проверим, что товар в отгрузке и отправлении совпадают
                $url = $product_ms['assortment']['meta']['href'];
                $check_product_ms = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

                if ($check_product_ms['code'] == $product_ozon['offer_id'] ||
                $check_product_ms['externalCode'] == $product_ozon['offer_id'] ||
                $check_product_ms['article'] == $product_ozon['offer_id']) {
                  $quantity = $product_ozon['quantity'];
                  $product_ms['quantity'] = $quantity;
                  $positions_return[] = $product_ms;
                }
              }
            }

            $demand['positions']['rows'] = $positions_return;
						$created_return = $this->newSalesReturnMs($demand);

						if (!empty($created_return['errors'])) {
							$this->log($posting_number . ' ошибка при создании возврата FBO: '. json_encode($created_return['errors'], JSON_UNESCAPED_UNICODE), 0);
						} else {
	            //получим предзаполненный исходящий платеж на основании возврата
							$get_paymentout = $this->getPaymentoutMs($created_return);

							if (!empty($get_paymentout['errors'])) {
								$this->log($posting_number . ' ошибка при получении предзаполненного исходящего платежа FBO: '. json_encode($get_paymentout['errors'], JSON_UNESCAPED_UNICODE), 0);
							} else {
								//создаем исходящий платеж
								$created_paymentout = $this->newPaymentoutMs($get_paymentout);

								if (!empty($created_paymentout['errors'])) {
									$this->log($posting_number . ' ошибка при создании исходящего платежа FBO: '. json_encode($created_paymentout['errors'], JSON_UNESCAPED_UNICODE), 0);
								} else {
									//изменить статус заказа в мс и бд
									$status = $this->config->get('ozon_seller_status_returned');
									$this->replaceOrderStatus($guid, $status, $posting_number, $status_ozon = 'returned');

									if ($this->config->get('ozon_seller_status_order_fbo_oc')) {
										$order_oc = $this->model_module_ozon_seller->getOrderOc($posting_number);
										if (!empty($order_oc[0]['order_id'])) {
											$this->changeOrderStatusOC($order_oc[0]['order_id'], $this->config->get('ozon_seller_status_return'));
										}
									}
								}
							}
						}
          } else {
						$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status = 'returned');

						if ($this->config->get('ozon_seller_status_order_fbo_oc')) {
							$order_oc = $this->model_module_ozon_seller->getOrderOc($posting_number);
							if (!empty($order_oc[0]['order_id'])) {
								$this->changeOrderStatusOC($order_oc[0]['order_id'], $this->config->get('ozon_seller_status_return'));
							}
						}
					}
        }
      }
    }
	}

	//получить заказ FBO
	private function getFboPosting($posting_number) {
		$url = 'https://api-seller.ozon.ru/v2/posting/fbo/get';
		$data = array('posting_number' => $posting_number, 'with' => array('analytics_data' => true));
    $response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
		return $response;
	}

	//получить предзаполненный возврат на основании отгрузки в МС. Принимается заказ МС
	private function getSalesReturnMs($order) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/salesreturn/new';
		unset($order['demands'][0]['meta']['uuidHref']);
		$data = array('demand' => array('meta' => $order['demands'][0]['meta']));
		$response = $this->makeRequest($url, $request = 'PUT', $data, $api = 'ms');
		return $response;
	}

	//создать возврат в МС. Принимает предзаполненный возврат на основании отгрузки из getSalesReturnMs
	private function newSalesReturnMs($data) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/salesreturn';
		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ms');
		return $response;
	}

	//получить предзаполненный исходящий платеж в МС на основании возврата. Принимает возврат из newSalesReturn
	private function getPaymentoutMs($return) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/paymentout/new';
		unset($return['meta']['uuidHref']);
		$meta['meta'] = $return['meta'];
		$data = array('operations' => array($meta));
		$response = $this->makeRequest($url, $request = 'PUT', $data, $api = 'ms');
		return $response;
	}

	//создать исходящий платеж в МС. Принимает предзаполненный исходящий платеж из getPaymentoutMs
	private function newPaymentoutMs($data) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/paymentout';
		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ms');
		return $response;
	}

	private function replaceShipmentDate($posting_number, $guid, $shipment_date)
	{
		$this->load->model('module/ozon_seller');
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid;
		$data = array('deliveryPlannedMoment' => $shipment_date);
		$response = $this->makeRequest($url, $request = 'PUT', $data, $api = 'ms');
		$this->model_module_ozon_seller->updateShipmentDate($posting_number, $shipment_date);
		$this->log($posting_number . ' дата отгрузки изменена на ' . $shipment_date, 0);
	}

	/* Передаем новый заказ FBS в Мой склад */
	private function creatOrderMoysklad($posting_number, $shipment_date, $products, $barcodes, $status, $analytics_data) {

		$this->load->model('module/ozon_seller');

		if ($analytics_data['is_premium']) {
			$premium = 'Премиум клиент';
		} else {
			$premium = '';
		}

		$data_order = array(
			'name' => $posting_number,
			'description' => $analytics_data['city'] . "\n" . $barcodes['upper_barcode'] . "\n" . $barcodes['lower_barcode'] . "\n" . $premium,
			'deliveryPlannedMoment' => $shipment_date,
			'organization' => array(
				'meta' => array(
					'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/organization/' . $this->config->get('ozon_seller_organization'),
					'type' => 'organization',
					'mediaType' => 'application/json'
				)
			),
			'agent' => array(
				'meta' => array(
					'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/counterparty/' . $this->config->get('ozon_seller_agent'),
					'type' => 'counterparty',
					'mediaType' => 'application/json'
				)
			),
			'state' => array(
				'meta' => array(
					'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/' . $this->config->get('ozon_seller_status_new_order_ms'),
					'type' => 'state',
					'mediaType' => 'application/json'
				)
			),
			'store' => array(
				'meta' => array(
					'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/store/' . $this->config->get('ozon_seller_store'),
					'type' => 'store',
					'mediaType' => 'application/json'
				)
			),
			'project' => array(
				'meta' => array(
					'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/project/' . $this->config->get('ozon_seller_project'),
					'type' => 'project',
					'mediaType' => 'application/json'
				)
			),
			'salesChannel' => array(
				'meta' => array(
    			'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/saleschannel/' . $this->config->get('ozon_seller_saleschannel_ms'),
    			'type' => 'saleschannel',
    			'mediaType' => 'application/json'
  			)
	  	),
			'attributes' => array(
				array(
					'meta' => array(
						'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/' . $this->config->get('ozon_seller_sticker'),
						'type' => 'attributemetadata',
						'mediaType' => 'application/json'
					),
					'value' => HTTPS_SERVER . 'index.php?route=module/ozon_seller/printsticker&post=' . $posting_number
				)
			)
		);

		// $total_quantity = 0;
		// $total_price = 0;
		//
		// foreach ($products as $t_product) {
		// 	$total_quantity += $t_product['quantity'];
		// 	$total_price += $t_product['price'] * $t_product['quantity'];
		// }
		//
		// $pre_last_mile = $total_price * 4.4 / 100;
		//
		// if ($pre_last_mile < 50) {
		// 	$pre_last_mile = 50;
		// } else if ($pre_last_mile > 200) {
		// 	$pre_last_mile = 200;
		// }
		// $last_mile = $pre_last_mile / $total_quantity; // разделим последнюю милю на каждый товар
		// $shipment_processing = 45 / $total_quantity; //обработка всего заказа 45р разделим на каждый товар
		$data_products = array();
		$stop = '';

		foreach ($products as $product) {
			if (empty($product['offer_id'])) {
				$this->log($posting_number . 'ошибка: ozon вернул пустой массив товаров для отправления. Заказ будет перевыгружен.', 0);
				$stop = 'stop';
				break;
			}

			if ($this->config->get('ozon_seller_entry_offer_id') == $this->config->get('ozon_seller_connect_prod_shop')) {
				$article = $product['offer_id'];
			} else {
				$art = $this->model_module_ozon_seller->searchExportProduct($product['offer_id']);
				$article = $art[0][$this->config->get('ozon_seller_connect_prod_shop')];
			}

			if (empty($article) || !isset($article)) {
				$this->log($posting_number . ' ошибка: товар или комплект ' . $product['offer_id'] . ' не найден в товарах Opencart. Заказ будет перевыгружен.', 0);
				$stop = 'stop';
 				break;
			}

			$filter = $this->config->get('ozon_seller_connect_prod_ms');
			$ms_product = $this->getMsProduct($article, $filter);
			$prod_type = 'product';

			if (empty($ms_product['rows'])) {
				$ms_product = $this->getMsBundle($article, $filter);
				$prod_type = 'bundle';
			}

			if (empty($ms_product['rows'])) {
				$this->log($posting_number . ' ошибка: товар или комплект ' . urlencode($article) . ' не найден в Мой склад. Заказ будет перевыгружен.', 0);
 				$stop = 'stop';
 				break;
			} else {
				$prod_url = $ms_product['rows'][0]['meta']['href'];
				$price = $product['price'];

				// if ($this->config->get('ozon_seller_komission') == 1) {
				// 	$ozon_product = $this->getOzonProduct($product['offer_id']);//отправка запроса на получении комиссии на товар
				// 	if (empty($ozon_product)) {
				// 		$this->log($posting_number . ' ошибка при расчете комиссии заказа FBS - товар не получен в озон. Заказ будет перевыгружен.');
				// 		$stop = 'stop';
		 		// 		break;
				// 	}
				// 	$volume_weight = $ozon_product['result']['volume_weight'];
				// 	$highway = $volume_weight * $this->config->get('ozon_seller_highway'); //магистраль
				// 	if ($highway < 5 ) {
				// 		$highway = 5;
				// 	} else if ($highway > 500) {
				// 		$highway = 500;
				// 	}
				// 	$commissions = $ozon_product['result']['commissions'];
				// 	foreach ($commissions as $commission) {
				// 		if (in_array('fbs', $commission, true)) {
				// 			$price = $product['price'] - $commission['value'] - $highway - $last_mile - $shipment_processing;
				// 			$price = round($price, 2);
				// 		}
				// 	}
				// }

				$data_products[] = array(
					'quantity' => $product['quantity'],
					'price' => floatval($price) * 100,
					'discount' => 0,
					'vat' => 0,
					'assortment' => array(
						'meta' => array(
							'href' => $prod_url,
							'type' => $prod_type,
							'mediaType' => 'application/json'
						)
					),
					'reserve' => $product['quantity']
				);
			}
		}

		if ($stop == 'stop') {
			//заказ не будет создан если есть стоп
		} else {
			$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder';
			$response = $this->makeRequest($url, $request = 'POST', $data_order, $api = 'ms');

			if (!empty($response['errors'])) {
				$this->log($posting_number . ' ошибка при создании: ' . json_encode($response['errors'], JSON_UNESCAPED_UNICODE), 0);
			} else {
				$guid = htmlspecialchars($response['id']);
				$this->model_module_ozon_seller->saveOrderFull($posting_number, $shipment_date, $status, $guid);
				$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid . '/positions';
				$respons = $this->makeRequest($url, $request = 'POST', $data_products, $api = 'ms');

				if (!empty($respons['errors'])) {
				 	$this->log($posting_number . ' ' . $url . ' ошибка: '. json_encode($respons['errors'], JSON_UNESCAPED_UNICODE), 0);
				} else {
					echo $posting_number . ' успешно создан в МС <br />';
					return $guid;
				}
			}
		}
	}

	/* Получить товар в МС по артикулу */
	private function getMsProduct($article, $filter) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/product?filter=' . $filter . '=' . urlencode($article) . ';archived=false';

		$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

		return $response;
	}

	/* Получить комплект в МС по артикулу */
	private function getMsBundle($article, $filter) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/bundle?filter=' . $filter . '=' . urlencode($article) . ';archived=false';

		$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

		return $response;
	}

	// Обработаем заказы, что в нашей БД еще новые, а в Ozon уже собраны
	private function statusAwaitingDeliver()
	{
		$this->load->model('module/ozon_seller');
		$status_in_shop = 'awaiting_packaging';
		$orders = $this->model_module_ozon_seller->getOrderByStatus($status_in_shop);

		if (!empty($orders)) {
			$status = $this->config->get('ozon_seller_status_awaiting_deliver');
			$status_ozon = 'awaiting_deliver';
			$date = new DateTime();
			$date->modify('-10 day');
			$offset = 0;
			$url = 'https://api-seller.ozon.ru/v3/posting/fbs/list';
			do {
				$data = array(
					'dir' => 'asc',
					'filter' => array(
						'since' => $date->format(DATE_ATOM),
						'status' => $status_ozon,
						'to' => date(DATE_ATOM),
					),
					'sort_by' => 'order_created_at',
					'limit' => 1000,
					'offset' => $offset
				);
				$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

				if (!empty($response['result']['postings'])) {
					foreach ($response['result']['postings'] as $info) {
						$posting_number = $info['posting_number'];
						foreach ($orders as $order) {

							if ($posting_number == $order['posting_number'] && $order['status'] != $status_ozon) {
								if ($this->config->get('ozon_seller_chek_ms')) {
									$guid = $order['guid'];
									$this->replaceOrderStatus($guid, $status, $posting_number, $status_ozon);
								} else {
									$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status_ozon);
								}
								if ($this->config->get('ozon_seller_status_order_oc')) {
									$order_oc = $this->model_module_ozon_seller->getOrderOc($posting_number);
									if (!empty($order_oc[0]['order_id'])) {
										$this->changeOrderStatusOC($order_oc[0]['order_id'], $this->config->get('ozon_seller_status_deliver'));
									}
								}
							}
						}
					}
				}
				$offset += 1000;
			} while ($response['result']['has_next'] == true);
		}
	}

	// Обработаем заказы, которые мы отвезли в Ozon
	private function statusDelivering()
	{
		$this->load->model('module/ozon_seller');
		$status_in_shop = 'awaiting_deliver';
		$orders = $this->model_module_ozon_seller->getOrderByStatus($status_in_shop);

		if (!empty($orders)) {
			$status = $this->config->get('ozon_seller_status_delivering');
			$status_ozon = 'delivering';
			$date = new DateTime();
			$date->modify('-10 day');
			$offset = 0;
			$url = 'https://api-seller.ozon.ru/v3/posting/fbs/list';
			do {
				$data = array(
					'dir' => 'desc',
					'filter' => array(
						'since' => $date->format(DATE_ATOM),
						'status' => $status_ozon,
						'to' => date(DATE_ATOM),
					),
					'sort_by' => 'updated_at',
					'limit' => 1000,
					'offset' => $offset
				);
				$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

				if (!empty($response['result']['postings'])) {
					foreach ($orders as $order) {
						foreach ($response['result']['postings'] as $info) {
							$posting_number = $info['posting_number'];

							if ($posting_number == $order['posting_number'] && $order['status'] != $status_ozon) {
								if ($this->config->get('ozon_seller_chek_ms')) {
									$guid = $order['guid'];
									$this->replaceOrderStatus($guid, $status, $posting_number, $status_ozon);
								} else {
									$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status_ozon);
								}
								if ($this->config->get('ozon_seller_status_order_oc')) {
									$order_oc = $this->model_module_ozon_seller->getOrderOc($posting_number);
									if (!empty($order_oc[0]['order_id'])) {
										$this->changeOrderStatusOC($order_oc[0]['order_id'], $this->config->get('ozon_seller_status_shipping'));
									}
								}
							}
						}
					}
				}
				$offset += 1000;
			} while ($response['result']['has_next'] == true);
		}
	}

	// Обработаем отмененные заказы из Ozon
	private function statusCancelled()
	{
		$this->load->model('module/ozon_seller');
		$order_cancelled = array();
		$status = $this->config->get('ozon_seller_status_cancelled');
		$status_ozon = 'cancelled';
		$date = new DateTime();
		$date->modify('-2 month'); //для премиальных клиентов Ozon дает гарантию и возврат на 60 дней
		$offset = 0;
		$url = 'https://api-seller.ozon.ru/v3/posting/fbs/list';
		do {
			$data = array(
				'dir' => 'desc',
				'filter' => array(
					'since' => $date->format(DATE_ATOM),
					'status' => $status_ozon,
					'to' => date(DATE_ATOM),
				),
				'sort_by' => 'updated_at',
				'limit' => 1000,
				'offset' => $offset
			);
			$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

			if (!empty($response['result']['postings'])) {
				foreach ($response['result']['postings'] as $info) {
					$posting_number = $info['posting_number'];
					$order = $this->model_module_ozon_seller->getMyOrder($posting_number);
					if (!empty($order)) {
						if ($order[0]['status'] != $status_ozon &&
						$order[0]['status'] != 'returned' &&
						$order[0]['status'] != 'return_fbs') {
							$guid = $order[0]['guid'];
							$order_cancelled[] = array('guid' => $guid, 'posting_number' => $posting_number);
						}
					}
				}
			}
			$offset += 1000;
		} while ($response['result']['has_next'] == true);

		if (!empty($order_cancelled)) {
			foreach ($order_cancelled as $order_cancel) {
				$guid = $order_cancel['guid'];
				$posting_number = $order_cancel['posting_number'];

				if ($this->config->get('ozon_seller_chek_ms')) {
					$this->replaceOrderStatus($guid, $status, $posting_number, $status_ozon);
				} else {
					$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status_ozon);
				}
				if ($this->config->get('ozon_seller_status_order_oc')) {
					$order_oc = $this->model_module_ozon_seller->getOrderOc($posting_number);
					if (!empty($order_oc[0]['order_id'])) {
						$this->changeOrderStatusOC($order_oc[0]['order_id'], $this->config->get('ozon_seller_status_cancel'));
					}
				}
			}
		}
	}

	// Получим номера отправлений с возвратами в финансовом отчете
	private function getReturnsOzon()
	{
		$this->load->model('module/ozon_seller');
		$url = 'https://api-seller.ozon.ru/v3/finance/transaction/list';
		$date = new DateTime();
		$date->modify('-2 day');
		$data = array(
			'filter' => array(
				'date' => array(
					'from' => $date->format(DATE_ATOM),
					'to' => date(DATE_ATOM),
				),
				'transaction_type' => 'returns'
			),
			'page' => 1,
			'page_size' => 1000
		);
		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

		if (!empty($response)) {
			$postings = array();
			foreach ($response['result']['operations'] as $posting) {
				$postings[] = $posting['posting']['posting_number'];
			}
			//получим информацию по возвратам
			$returns = $this->checkReturnsOzon($postings);

			if ($this->config->get('ozon_seller_chek_ms')) {
				if (!empty($returns['result'])) {
					$ms_change_orders = array();
					foreach ($returns['result']['returns'] as $return) {
						if ($return['status'] != 'returned_to_seller') {
							$posting_number = htmlspecialchars($return['posting_number']);
							$info = $this->model_module_ozon_seller->getMyOrder($posting_number);
							if (empty($info) || $info[0]['status'] == 'return_fbs') {
								continue;
							}
							$ms_change_orders[] = array(
								'posting_number' => $posting_number,
								'guid' => $info[0]['guid']
							);
						}
					}
				}

				if (!empty($ms_change_orders)) {
					$data_change = array();
					foreach ($ms_change_orders as $change_order) {
						$data_change[] = array(
							'meta'=> array(
								'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $change_order['guid'],
								'type' => 'customerorder',
								'mediaType' => 'application/json'
							),
							'state' => array(
								'meta' => array(
									'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/' . $this->config->get('ozon_seller_status_cancelled'),
									'type' => 'state',
									'mediaType' => 'application/json'
							))
						);
					}

					$respons = $this->changeOrdersMs($data_change);

					if (!empty($respons['errors'])) {
						$this->log('Массовое обновление возвратов в МС ошибка: '. json_encode($respons['errors'], JSON_UNESCAPED_UNICODE), 0);
					} else {
						foreach ($ms_change_orders as $change_order) {
							$this->model_module_ozon_seller->updateStatusOzon($change_order['posting_number'], $status = 'return_fbs');
						}
					}
				}

			} else {
				if (!empty($returns['result'])) {
					foreach ($returns['result']['returns'] as $return) {
						if ($return['status'] != 'returned_to_seller') {
							$posting_number = htmlspecialchars($return['posting_number']);
							$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status = 'return_fbs');
						}
					}
				}
			}
		}
	}

	//информация по возвратам FBS
	private function checkReturnsOzon($postings)
	{
		$url = 'https://api-seller.ozon.ru/v2/returns/company/fbs';
		$data = array(
			'filter' => array('posting_number' => $postings),
			'limit' => 1000
		);
		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
		return $response;
	}

	//проверим выдан ли возврат продавцу
	private function finalCheckReturnsOzon()
	{
		$this->load->model('module/ozon_seller');
		$returns_db = $this->model_module_ozon_seller->getOrderByStatus($status = 'return_fbs');
		foreach ($returns_db as $return) {
			$posting_number = $return['posting_number'];
			//получим информацию по возвратам
			$returns_ozon = $this->checkReturnsOzon($postings = array($posting_number));

			foreach ($returns_ozon['result']['returns'] as $return_ozon) {
				if ($return_ozon['status'] != 'returned_to_seller') {
					continue;
				}
				if ($this->config->get('ozon_seller_chek_ms') && $this->config->get('ozon_seller_autoreturn_fbs')) {
					$status = $this->config->get('ozon_seller_status_returned');
					// подсчитаем, сколько у одного отпрвления выдано возвратов
					$check_count_returned_to_seller = 0;
					foreach ($returns_ozon['result']['returns'] as $count_return_posting) {
						if ($count_return_posting['posting_number'] == $return_ozon['posting_number'] && $count_return_posting['status'] == 'returned_to_seller') {
							$check_count_returned_to_seller++;
						}
					}

					$product_db = $this->model_module_ozon_seller->getExportProduct($return_ozon['product_id']);

					if (!empty($product_db)) {
						if ($this->config->get('ozon_seller_connect_prod_shop') == 'sku') {
							$product = $product_db[0]['sku'];
						} elseif ($this->config->get('ozon_seller_connect_prod_shop') == 'model') {
							$product = $product_db[0]['model'];
						}

						$guid = $return['guid'];
						$order_info_ms = $this->getOrderMoyskald($guid);

						if (count($order_info_ms['demands']) > 1) {
	            $this->log('ВНИМАНИЕ: ' . $posting_number . ' обнаружено несколько отгрузок в МС!', 0);
	          }
						//проверим, что количество возвратов в МС не превышает количество возвратов в Озон
						$url = $order_info_ms['demands'][0]['meta']['href'];
						$get_demand = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

						if (!empty($get_demand['returns'])) {
							if (count($get_demand['returns']) >= $check_count_returned_to_seller) {
								continue;
							}
						}

						if (!empty($get_demand['returns']) && count($get_demand['returns']) >= count($returns_ozon['result']['returns'])) {
							$this->log($posting_number . ' возврат FBS с id ' . $return_ozon['id'] . ' пропущен, т.к. количество возвратов в МС (' . count($get_demand['returns']) . ') не может превышать количество возвратов из Озон (' . count($returns_ozon['result']['returns']) . ')', 0);

							$theme = 'Возврат ' . $posting_number;
							$message = $posting_number . '<br />Возврат FBS с id ' . $return_ozon['id'] . ' не будет обработан, т.к. количество возвратов в МС (' . count($get_demand['returns']) . ') не может превышать количество возвратов из Озон (' . count($returns_ozon['result']['returns']) . ').<br />Пожалуйста проверьте заказ в МС.<br />Если все в порядке, игнорируйте это сообщение.';
							$this->sendMail($theme, $message);

							$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status = 'returned');
							continue;
						}
						//получаем предзаполненный возврат
	          $demand = $this->getSalesReturnMs($order_info_ms);
						$positions_return = array();

	          foreach ($demand['positions']['rows'] as $product_ms) {
	            //проверим, что товар в отгрузке и возврате совпадают
	            $url = $product_ms['assortment']['meta']['href'];
	            $check_product_ms = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');
							$prod_ms = $this->config->get('ozon_seller_connect_prod_ms');

							if (($prod_ms == 'article' && $check_product_ms['article'] == $product) ||
							($prod_ms == 'externalCode' && $check_product_ms['externalCode'] == $product) ||
							($prod_ms == 'code' && $check_product_ms['code'] == $product)) {
							  $product_ms['quantity'] = $return_ozon['quantity'];
							  $positions_return[] = $product_ms;
								$sum = $product_ms['price'];
							}
	          }
						//если нет товара для возврата, проверим был ли ранее создан возврат с ним
						if (empty($positions_return)) {
							if (!empty($get_demand['returns'])) {
								$url = $get_demand['returns'][0]['meta']['href'];
								$get_returns = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');
								$url = $get_returns['positions']['meta']['href'];
								$get_positions = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');
								foreach ($get_positions['rows'] as $position) {
									$url = $position['assortment']['meta']['href'];
									$check_product_ms = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');
									if (($prod_ms == 'article' && $check_product_ms['article'] == $product) ||
									($prod_ms == 'externalCode' && $check_product_ms['externalCode'] == $product) ||
									($prod_ms == 'code' && $check_product_ms['code'] == $product)) {
										$this->log($posting_number . ' возврат FBS: Возврат уже был создан ранее.', 0);
										$this->replaceOrderStatus($guid, $status, $posting_number, $status_ozon = 'returned');
										break 2;
									}
								}
								$this->log($posting_number . ' ошибка при создании возврата FBS: нет подходящего товара для создания возврата. Возможно, что связь товаров между МС и OC по ' . $prod_ms . ' не корректна. Либо в заказе несколько позиций и вы приняли их одним возвратом.', 0);

								$theme = 'Возврат ' . $posting_number;
								$message = $posting_number . '<br />Ошибка при создании возврата FBS: нет подходящего товара для создания возврата.<br />Возможно, что связь товаров между МС и OC не корректна.<br />Либо в заказе несколько позиций и Вы приняли их одним возвратом.<br />Пожалуйста проверьте заказ в МС.<br />Если все в порядке, игнорируйте это сообщение.';
								$this->sendMail($theme, $message);

								$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status = 'returned');
								continue;
							}
						}

	          $demand['positions']['rows'] = $positions_return;
						$demand['sum'] = $sum;
						$demand['description'] = $return_ozon['return_reason_name'] . "\n" . "Нижний штрих-код возврата:\n" . $return_ozon['id'] . "\n" . $return_ozon['clearing_id'];
						$created_return = $this->newSalesReturnMs($demand);

						if (!empty($created_return['errors'])) {
							$this->log($posting_number . ' ошибка при создании возврата FBS: '. json_encode($created_return['errors'], JSON_UNESCAPED_UNICODE), 0);
							continue;
						} else {
	            //получим предзаполненный исходящий платеж на основании возврата
							if (!empty($order_info_ms['payments'])) {
								$get_paymentout = $this->getPaymentoutMs($created_return);
								//создаем исходящий платеж
								$created_paymentout = $this->newPaymentoutMs($get_paymentout);
								if (!empty($created_paymentout['errors'])) {
									$this->log($posting_number . ' ошибка при создании исходящего платежа FBS: ' . json_encode($created_paymentout['errors'], JSON_UNESCAPED_UNICODE), 0);
								}
							}
							//изменить статус заказа в мс и бд
							$this->replaceOrderStatus($guid, $status, $posting_number, $status_ozon = 'returned');
							echo $posting_number . ' возврат успешно обработан<br />';

							if ($this->config->get('ozon_seller_status_order_oc')) {
								$order_oc = $this->model_module_ozon_seller->getOrderOc($posting_number);
								if (!empty($order_oc[0]['order_id'])) {
									$this->changeOrderStatusOC($order_oc[0]['order_id'], $this->config->get('ozon_seller_status_return'));
								}
							}
						}
					}
				} else {
					echo $posting_number . ' возврат успешно обработан<br />';
					$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status = 'returned');

					if ($this->config->get('ozon_seller_status_order_oc')) {
						$order_oc = $this->model_module_ozon_seller->getOrderOc($posting_number);
						if (!empty($order_oc[0]['order_id'])) {
							$this->changeOrderStatusOC($order_oc[0]['order_id'], $this->config->get('ozon_seller_status_return'));
						}
					}
				}
			}
		}
	}

	//получим отмененные заказы за 2 месяца и проверим есть ли по ним возврат в Озон
	private function checkCancelledReturn()
	{
		$this->load->model('module/ozon_seller');
		$start = 0;
		$limit = 900;
		do {
			$filter_data = array(
				'status' => 'cancelled',
				'month' => '-2 month',
				'start' => $start,
				'limit' => $limit
			);
			$orders_db = $this->model_module_ozon_seller->getOrderByStatusMonthOld($filter_data);
			if (!empty($orders_db)) {
				$posting = array();
				foreach ($orders_db as $order_db) {
					$posting[] = $order_db['posting_number'];
				}
				$returns = $this->checkReturnsOzon($posting);
				if (!empty($returns['result']['returns'])) {
					foreach ($returns['result']['returns'] as $return) {
						if (isset($return['id'])) {
							$posting_number = htmlspecialchars($return['posting_number']);
							$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status = 'return_fbs');
						}
					}
				}
			}
			$start += $limit;
		} while (count($orders_db) == $limit);
	}

	// Обработаем доставленные заказы
	public function statusDelivered()
	{
		header('Content-Type: text/html; charset=utf-8');
		$status_ms = $this->config->get('ozon_seller_chek_ms');
		echo ($status_ms == 0) ? 'Интеграция с Мой склад отключена' : false;

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {
			$this->load->model('module/ozon_seller');
			$status_in_shop = 'delivering';
			$orders = $this->model_module_ozon_seller->getOrderByStatus($status_in_shop);
			$delevered_orders = 0;
			if (!empty($orders)) {
				$status = $this->config->get('ozon_seller_status_delivered');
				$status_ozon = 'delivered';
				$date = new DateTime();
				$date->modify('-1 month');
				$offset = 0;
				do {
					$data = array(
						'dir' => 'asc',
						'filter' => array(
							'since' => $date->format(DATE_ATOM),
							'status' => $status_ozon,
							'to' => date(DATE_ATOM),
						),
						'sort_by' => 'updated_at',
						'limit' => 1000,
						'offset' => $offset,
						'with' => array(
							'analytics_data' => true
						)
					);
					$url = 'https://api-seller.ozon.ru/v3/posting/fbs/list';
					$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

					if (!empty($response['result']['postings'])) {
						foreach ($response['result']['postings'] as $info) {
							$posting_number = $info['posting_number'];
							foreach ($orders as $order) {
								if ($order['posting_number'] == $posting_number) {
									$guid = $order['guid'];
									if ($this->config->get('ozon_seller_payment_ms')) {
										$this->paymentOrder($guid);
									}
									if ($this->config->get('ozon_seller_chek_ms')) {
										$this->replaceOrderStatus($guid, $status, $posting_number, $status_ozon);
									} else {
										$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status_ozon);
									}
									if ($this->config->get('ozon_seller_status_order_oc')) {
										$order_oc = $this->model_module_ozon_seller->getOrderOc($posting_number);
										if (!empty($order_oc[0]['order_id'])) {
											$this->changeOrderStatusOC($order_oc[0]['order_id'], $this->config->get('ozon_seller_status_delevered'));
										}
									}
									echo $posting_number . ' доставлен <br />';
									$delevered_orders++;
								}
							}
						}
					}
					$offset += 1000;
				} while ($response['result']['has_next'] == true);
			}
			echo 'Всего доставленных заказов: ' . $delevered_orders;
		}
	}


	/* Изменить статус заказа в БД на выполнен, если в МС выполнен */
	public function checkStatusDeliveredInMs() {

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {

			$this->load->model('module/ozon_seller');
			$status_in_shop = 'delivering';

			$orders = $this->model_module_ozon_seller->getOrderByStatus($status_in_shop);

			foreach ($orders as $order) {
				if (!empty($order['guid'])) {

					$order_ms = $this->getOrderMoyskald($order['guid']);
					$posting_number = $order['posting_number'];
					$status_new = 'delivered';

					if ($order_ms['state']['meta']['href'] == 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/60e92af8-bbf2-11e6-7a31-d0fd0022a742') {

						$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status_new);
					}
				}

				echo $order['posting_number'] . '<br />';
			}
		}
	}

	/* Реестр платежей */
	public function reestr()
	{
		$status_ms = $this->config->get('ozon_seller_chek_ms');
		echo ($status_ms == 0) ? 'Интеграция с Мой склад отключена' : false;
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass') && $status_ms) {

			if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
				$start_date = date('Y-m-d', strtotime($_GET['startDate']));
				$end_date = date('Y-m-d', strtotime($_GET['endDate']));
				$this->load->model('module/ozon_seller');
				$url = 'https://api-seller.ozon.ru/v3/finance/transaction/list';
				$page = 1;
				$postings = 0;
				do {
					$data = array(
						'filter' => array(
							'date' => array(
								'from' => $start_date . 'T00:00:00.496Z',
								'to' => $end_date . 'T23:59:59.496Z'
							),
							'transaction_type' => 'orders'
						),
						'page' => $page,
						'page_size' => 999
					);
					$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

					$orders = array();
					foreach ($response['result']['operations'] as $posting) {
						$orders[] = $posting['posting']['posting_number'];
					}
					$guids = $this->model_module_ozon_seller->getMyOrders($orders);

					$data_change = array();
					foreach ($guids as $guid) {
						$data_change[] = array(
							'meta'=> array(
								'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid['guid'],
								'type' => 'customerorder',
								'mediaType' => 'application/json'
							),
							'attributes' => array(array(
								'meta' => array(
									'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/' . $this->config->get('ozon_seller_payment_date'),
									'type' => 'attributemetadata',
									'mediaType' => 'application/json'
								),
							'value' => $end_date . ' 00:00:00'
							))
						);
					}
					$this->changeOrdersMs($data_change);

					$page++;
					$postings += count($orders);
				} while ($response['result']['page_count'] >= $page);

				echo $postings . " отправлений в начислениях за период\nДата оплаты в этих заказах была изменена на " . $_GET['endDate'];
			}
		}
	}

	/* Получить список заказов в Ozon по статусу */

	private function getOrdersOzonByStatus($status)
	{
		$url = 'https://api-seller.ozon.ru/v3/posting/fbs/list';
		$date = new DateTime();
		$date->modify('-10 day');
		$offset = 0;
		$packings = array();
		do {
			$data = array(
				'dir' => 'asc',
				'filter' => array(
					'since' => $date->format(DATE_ATOM),
					'status' => $status,
					'to' => date(DATE_ATOM),
				),
				'sort_by' => 'order_created_at',
				'limit' => 50,
				'offset' => $offset,
				'with' => array(
					'barcodes' => true,
					'analytics_data' => true
				)
			);
			$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

			if (!empty($response['result']['postings'])) {
				foreach ($response['result']['postings'] as $packing) {
					$packings[] = $packing;
				}
			}
			$offset += 50;
		} while ($response['result']['has_next'] == 'true');

		return $packings;
	}

	/* Подборка заказов штрих-кодом */

	public function chekOrderList() {

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {

			$packings_deliver = $this->getOrdersOzonByStatus('awaiting_deliver');
			$packings_packaging = $this->getOrdersOzonByStatus('awaiting_packaging');

			$html = '<head>
			<meta charset="utf-8">
			<title>Отгружаемые заказы</title>
			<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
			<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>
			<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js"></script>
			<style>
				.brd {font-size:13px;display:table;border-left-width:1px;border-top-width:1px;border-bottom-width:1px;border-right-width:1px;border-style:solid;}
				.info {font-size:30px;display:ruby-text-container;}
				.find {background-color:#7bdd68;}
				.success {color:green;}
				.error {color:red;}
				.count-posting {position:absolute;top:35px;right:0;width:100px;font-size:30px;color:green;}
			</style>
			</head>
			<body>
			<audio id="ok" src="_ok.mp3" preload="auto"></audio>
			<audio id="danger" src="_danger.mp3" preload="auto"></audio>
			<div class="col-xs-12"> <h4>Заказы с отгрузкой на ' . date('d-m-Y') . '</h4></div>
			<div class="col-xs-4">
				<input class="form-control search" type="text" placeholder="Штрих-код" autofocus />
			</div>
			<div class="info"></div>
			<div class="count-posting"></div>';

			if (!empty($packings_deliver)) {

				$html .= '
				<div class="col-xs-12"><h4>Ожидают отгрузки:<h4></div>
				<div class="col-xs-12">';

				foreach ($packings_deliver as $order) {

					$shipment_date = date('Y-m-d', strtotime($order['shipment_date']));//крайний срок сборки заказа

					if ($shipment_date == date('Y-m-d')) {

						$upper = $order['barcodes']['upper_barcode'];
						$lower = $order['barcodes']['lower_barcode'];

						$html .= '<div class="col-xs-1 brd" data-upper="' . $upper . '" data-lower="' . $lower . '">' . $order['posting_number'] . '</div>';
					}

				}

				$html .= '</div>';

			} else {

				$html .= '<div class="col-xs-12"><h2>Нет отгружаемых заказов</h2></div>';
			}

			if (!empty($packings_packaging)) {

				$html .= '
				<div class="col-xs-12"><h4>Ожидают упаковки:</h4></div>
				<div class="col-xs-12">';

				foreach ($packings_packaging as $order) {

					$shipment_date = date('Y-m-d', strtotime($order['shipment_date']));

					if ($shipment_date == date('Y-m-d')) {

						$upper = $order['barcodes']['upper_barcode'];
						$lower = $order['barcodes']['lower_barcode'];

						$html .= '<div class="col-xs-1 brd" data-upper="' . $upper . '" data-lower="' . $lower . '">' . $order['posting_number'] . '</div>';
					}

				}

				$html .= '</div>';
			}

			$html .= '
				<div class="col-xs-12">
					<h4>Лог:</h4>
					<div id="log"></div>
				</div>
			</body>
			<script>
				$(".search").each(function() {
					var elem = $(this);
					// Save current value of element
					elem.data("oldVal", elem.val());
					// Look for changes in the value
					elem.bind("propertychange change click keyup input paste", function(event){
						// If value has changed...
						if (elem.data("oldVal") != elem.val()) {
							// Updated stored value
							elem.data("oldVal", elem.val());
							// Do action

							// текущее время
							var date = new Date();

							if (elem.val().length == 14) {

								// отправление
								if ($(".brd[data-upper=\'" + elem.val() + "\']").text()) {
									posting = $(".brd[data-upper=\'" + elem.val() + "\']").text();
								}
								if ($(".brd[data-lower=\'" + elem.val() + "\']").text()) {
									posting = $(".brd[data-lower=\'" + elem.val() + "\']").text();
								}

								// проверим существования класса find у элемента
								if ($($(".brd[data-lower=\'" + elem.val() + "\']")).hasClass("find") || $($(".brd[data-upper=\'" + elem.val() + "\']")).hasClass("find")) {
									document.getElementById("danger").play();
									$("div.info").empty();
									$(".info").append("<div class=\"error\">" + posting + " повтор!</div>");
									$("#log").prepend("<div class=\"error\">[" + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() + "] " + posting + " уже было отсканировано</div>");
									$(".search").val("");

								} else if ($(".brd[data-upper=\'" + elem.val() + "\']").not(".find") || $(".brd[data-lower=\'" + elem.val() + "\']").not(".find")) {

									$(".brd[data-upper=\'" + elem.val() + "\']").addClass("find");
									$(".brd[data-lower=\'" + elem.val() + "\']").addClass("find");

									if ($($(".brd[data-upper=\'" + elem.val() + "\']")).hasClass("find")) {
										var result = true;
									}

									if ($($(".brd[data-lower=\'" + elem.val() + "\']")).hasClass("find")) {
										var result = true;
									}

									if (result) {
										document.getElementById("ok").play();
										$("div.info").empty();
										$(".info").append("<div class=\"success\">" + posting + "</div>");
										$("#log").prepend("<div class=\"success\">[" + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() + "] " + posting + " ОК</div>");

									} else if (!result) {
										document.getElementById("danger").play();
										$("div.info").empty();
										$(".info").append("<div class=\"error\"> штрих-код " + elem.val() + " не найден!</div>");
										$("#log").prepend("<div class=\"error\">[" + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() + "] штрих-код " + elem.val() + " не найден!</div>");
									}

									$(".search").val("");

									// счетчик
									$("div.count-posting").empty();
									$(".count-posting").append($(".find").length);

									if ($(".find").length === $(".brd").length) {

										let timerId = setInterval(() => { document.getElementById("ok").play(); }, 100);

										setTimeout(() => { clearInterval(timerId); }, 2000);

										$("#log").prepend("<div class=\"success\">" + $(".find").length + " из " + $(".brd").length + " отсканировано</div>");
									}
								}
							}
						}
					});
				});
			</script>';

			echo $html;
		}
	}

	/* Изменить заказ в МС */
	private function changeOrderMs($guid, $data) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid;
		$response = $this->makeRequest($url, $request = 'PUT', $data, $api = 'ms');
	}

	/* Изменить заказ в МС массово - POST */
	private function changeOrdersMs($data) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder';
		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ms');
		return $response;
	}

	/* Создать входящий платеж в МС */
	private function paymentOrder($guid) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/paymentin/new';
		$data = array(
			'operations' => array(
				array(
					'meta' => array(
					'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid,
					'metadataHref' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata',
					'type' => 'customerorder',
					'mediaType' => 'application/json'
                	)
                )
            )
		);

		$response = $this->makeRequest($url, $request = 'PUT', $data, $api = 'ms');

		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/paymentin';
		$respon = $this->makeRequest($url, $request = 'POST', $data = $response, $api = 'ms');

		return $respon;
	}

	/* Создать отгрузку в МС */
	private function demandOrder($guid) {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/demand/new';
		$data = array(
			'customerOrder' => array(
				'meta' => array(
					'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid,
					'metadataHref' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata',
					'type' => 'customerorder',
					'mediaType' => 'application/json'
      	)
      )
		);

		$response = $this->makeRequest($url, $request = 'PUT', $data, $api = 'ms');

		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/demand';
		$respon = $this->makeRequest($url, $request = 'POST', $response, $api = 'ms');

		return $respon;
	}

	/* Получить позиции отргузки в МС */
	private function getDemandMoysklad($guid) {

		$order_info = $this->getOrderMoyskald($guid);

		foreach ($order_info['demands'] as $demand) {
			$url = $demand['meta']['href'];//ссылка на отгрузку в МС
		}

		$demand_info = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

		$guid = $demand_info['id'];

		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/demand/' . $guid . '/positions';

		$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

		return $response['rows'];

	}

	/* Получить позиции заказа в МС */
	private function getPositionMoysklad($guid) {

		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid . '/positions';

		$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

		return $response['rows'];

	}

	/* Изменить статус заказа в МС и БД */
	private function replaceOrderStatus($guid, $status, $posting_number, $status_ozon) {

		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid;

		$data = array(
			'state' => array(
				'meta' => array(
					'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/' . $status,
					'type' => 'state',
					'mediaType' => 'application/json'
				)
			)
		);

		$respon = $this->makeRequest($url, $request = 'PUT', $data, $api = 'ms');

		if (!empty($respon['errors'])) {
			$this->log($posting_number .' ошибка при смене статуса в МС' . json_encode($respon['errors'], JSON_UNESCAPED_UNICODE), 0);
		} else {
			$this->load->model('module/ozon_seller');
			$this->model_module_ozon_seller->updateStatusOzon($posting_number, $status_ozon);
		}
	}

	/* Получить информацию о товаре продавца в Ozon по артикулу продавца */
	private function getOzonProduct($offer_id) {

		$url = 'https://api-seller.ozon.ru/v2/product/info';
		$data = array('offer_id' => $offer_id);
		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
		return $response;
	}

	/* Получить информацию о товаре продавца в Ozon по sku маркетплейса */
	private function getOzonProductId($sku) {

		$url = 'https://api-seller.ozon.ru/v2/product/info';

		$data = array('sku' => $sku);

		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

		return $response;

	}

	// Собрать заказ в Ozon
	private function packOrder($guid)
	{
		$this->load->model('module/ozon_seller');
		$packings = $this->model_module_ozon_seller->getOrder($guid);
		if (!empty($packings['0']) && $packings['0']['status'] != 'delivered') {
			$posting_number = $packings['0']['posting_number'];
			$response = $this->getOrderOzon($posting_number);
			foreach ($response as $info) {
				$items = array();
				$products = $info['products'];
				foreach ($products as $product) {
					$items[] = array(
						'exemplar_info' => array(array(
              'is_gtd_absent' => true
            )),
						'quantity' => $product['quantity'],
						'product_id' => $product['sku']
					);
				}

				$url = 'https://api-seller.ozon.ru/v3/posting/fbs/ship';
				$data = array(
					'packages' => array(
	        	array('products' => $items)
	        ),
			    'posting_number' => $posting_number
				);
				$respon = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

				if (!empty($respon['code']) && $respon['message'] != 'POSTING_ALREADY_SHIPPED') {
					$this->log($posting_number . ' ошибка при сборке: ' . json_encode($respon, JSON_UNESCAPED_UNICODE), 0);
					return 'error';
				} else {
					return 'OK';
				}
			}
		} else {
			return 'OK';
		}
	}

	// Получить заказ из админки
	public function getOrderOzonAdmin() {
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {

			if (isset($this->request->get['posting'])) {
				$posting = $this->getOrderOzon($this->request->get['posting']);

				if (isset($posting['error']['code']) && $posting['error']['code'] == 'NOT_FOUND_ERROR') {
					$posting = $this->getFboPosting($this->request->get['posting']);
				}

				echo json_encode($posting, JSON_UNESCAPED_UNICODE);
			}
		}
	}

	// Собрать заказ в Ozon из админки
	public function packOrderAdmin()
	{
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {
			if (isset($this->request->get['posting'])) {
				$response = $this->getOrderOzon($this->request->get['posting']);
				$info = $response['result'];
				$items = array();
				$products = $info['products'];
				foreach ($products as $product) {
					$items[] = array(
						'exemplar_info' => array(array(
              'is_gtd_absent' => true
            )),
						'quantity' => $product['quantity'],
						'product_id' => $product['sku']
					);
				}
				$url = 'https://api-seller.ozon.ru/v3/posting/fbs/ship';
				$data = array(
					'packages' => array(
			      array('products' => $items)
			    ),
			    'posting_number' => $info['posting_number']
				);
				$respon = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
				if (!empty($respon['code']) && $respon['message'] != 'POSTING_ALREADY_SHIPPED') {
					echo $info['posting_number'] . ' ошибка при сборке: ' . json_encode($respon, JSON_UNESCAPED_UNICODE);
				} elseif (!empty($respon['code']) && $respon['message'] == 'POSTING_ALREADY_SHIPPED') {
					echo 'Отправление уже было собрано';
				} else {
					echo 'OK';
				}
			}
		}
	}

	// Удалить заказ в БД
	public function deleteOrder() {

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass') && isset($this->request->get['posting'])) {

			$posting_number = htmlspecialchars($this->request->get['posting']);

			$this->load->model('module/ozon_seller');

			if ($this->config->get('ozon_seller_chek_ms')) {

				$order_info = $this->model_module_ozon_seller->getMyOrder($posting_number);

				if (isset($order_info[0]['guid'])) {

					$this->deleteOrderMoyskald($order_info[0]['guid']);
				}
			}

			$this->model_module_ozon_seller->deleteOrder($posting_number);
		}
	}

	// Получить заказ в Ozon
	private function getOrderOzon($posting_number)
	{
		$url = 'https://api-seller.ozon.ru/v3/posting/fbs/get';
		$data = array('posting_number' => $posting_number, 'with' => array('analytics_data' => true));
		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
		return $response;
	}

	/* Получить заказ в МС по id */
	private function getOrderMoyskald($guid) {

		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid;

		$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

		return $response;
	}

	/* Удалить заказ в МС по id */
	private function deleteOrderMoyskald($guid) {

		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid;

		$response = $this->makeRequest($url, $request = 'DELETE', $data = '', $api = 'ms');

		return $response;
	}

	/* Напечатать этикетку Ozon */
	public function printSticker() {

		$posting_number = $this->request->get['post'];
		$url =  'https://api-seller.ozon.ru/v2/posting/fbs/package-label';
		$data = array('posting_number' => array($posting_number));

		$response = $this->request2($url, $data);
		$error = json_decode($response, true);

		if (isset($error['error'])) {
			echo $error['error']['message'];
		} else {
			header('Content-Type: application/pdf');
			echo $response;
			//смена статуса в МС
			if ($this->config->get('ozon_seller_chek_ms')) {
				$status = $this->config->get('ozon_seller_status_print');
				$this->changeStatusOrderMs($posting_number, $status);
			}
		}
	}

	/* Смена статуса в МС */
	private function changeStatusOrderMs($posting_number, $status) {

		$this->load->model('module/ozon_seller');

		$order = $this->model_module_ozon_seller->getMyOrder($posting_number);

		$guid = $order[0]['guid'];

		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid;

		$data = array(
			'state' => array(
				'meta' => array(
					'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/' . $status,
					'type' => 'state',
					'mediaType' => 'application/json'
				)
			)
		);

		$response = $this->makeRequest($url, $request = 'PUT', $data, $api = 'ms');

		if (!empty($response['errors'])) {
			$this->log($posting_number .' ошибка при смене статуса в МС после печати' . json_encode($response['errors'], JSON_UNESCAPED_UNICODE), 0);
		}
	}

	/* Вывести метаданные статусов в админке */
	public function getMetadataOrder() {

		header('Content-Type: text/html; charset=utf-8');
		$status_ms = $this->config->get('ozon_seller_chek_ms');
		echo ($status_ms == 0) ? 'Интеграция с Мой склад отключена' : false;

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass') && $status_ms) {

			$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata';
			$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');
			foreach ($response['states'] as $resp) {
				echo $resp['name'] . ' (' . $resp['id'] . ')<br /><br />';
			}
		}
	}

	/* Вывести метаданные доп полей в МС*/
	public function getMetadataAttributes() {

		header('Content-Type: text/html; charset=utf-8');
		$status_ms = $this->config->get('ozon_seller_chek_ms');
		echo ($status_ms == 0) ? 'Интеграция с Мой склад отключена' : false;

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass') && $status_ms) {

			$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes';
			$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');
			foreach ($response['rows'] as $resp) {
				echo $resp['name'] . ' (' . $resp['id'] . ')<br /><br />';
			}
		}
	}

	// Создаем Webhook
	public function webhookCreate()
	{
		$input_url = HTTPS_SERVER . 'index.php?route=module/ozon_seller/webhookinput';
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/webhook';
		$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

		if (!empty($response['rows'])) {
			foreach ($response['rows'] as $value) {
				if ($value['url'] == $input_url) {
					$check = true;
					echo 'Webhook отгрузки для модуля Ozon уже создан!';
				}
			}
		}
		if (!isset($check)) {
			$data = array(
				'url' => $input_url,
				'action' => 'CREATE',
				'entityType' => 'demand'
			);

			$respon = $this->makeRequest($url, $request = 'POST', $data, $api = 'ms');

			if ($respon['enabled'] == true) {
				echo 'Webhook demand id ' . $respon['id'];
			} else {
				echo 'Webhook demand not created';
			}
		}
	}

	/* Принимаем webhook */
	public function webhookInput() {

		if (isset($this->request->post)) {

			$inputJSON = file_get_contents('php://input');
			$hook = json_decode($inputJSON, true);

			if (array_key_exists('events', $hook)) {
				foreach ($hook['events'] as $meta) {
					$url = $meta['meta']['href'];//url отгрузки в МС
					$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

					//проверяем принадлежность отгрузки к Ozon
					if ($response['agent']['meta']['href'] == 'https://online.moysklad.ru/api/remap/1.2/entity/counterparty/' . $this->config->get('ozon_seller_agent')) {

						$url = $response['customerOrder']['meta']['href'];
						$respon = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');
						$guid = $respon['id'];

						//отправляем заказ на сборку в Ozon
						$resp = $this->packOrder($guid);

						if ($resp = 'OK') {

							header("HTTP/1.1 200 OK");

						} elseif ($resp = 'error') {

							header("HTTP/1.1 501 Not Implemented");
						}
					}
				}
			}
		}
	}

	/* Webhook del */
	public function webhookDelete() {

		$status_ms = $this->config->get('ozon_seller_chek_ms');

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass') && $status_ms) {

			$url = 'https://online.moysklad.ru/api/remap/1.2/entity/webhook';
			$response = $this->makeRequest($url, $request = 'GET', $data = '', $api = 'ms');

			if (empty($response['rows'])) {
				echo 'No webhook';
			} else {
				foreach ($response['rows'] as $value) {
					$url = 'https://online.moysklad.ru/api/remap/1.2/entity/webhook/' . $value['id'];
					$respon = $this->makeRequest($url, $request = 'DELETE', $data = '', $api = 'ms');

					echo 'delete <br />';
				}
			}
		}
	}

	public function loadAttributes()
	{
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {
			$this->load->model('module/ozon_seller');
			$pass_id = array(85, 88, 95, 121, 4194, 4074, 4080, 4159, 4180, 4191, 4195, 4497, 8789, 8790, 8863, 9054, 9461, 11254, 44283970);
			$categorys = $this->config->get('ozon_seller_category');
			foreach ($categorys as $category) {
				$chek_load = $this->model_module_ozon_seller->getOzonAttribute($category['ozon']);
				if (empty($chek_load)) {
					$this->log_process('Начинаю загрузку...');
					$attributes = $this->getAttribute($category['ozon']);
					$count_attributes = count($attributes['result']);
					$i = 0;
					foreach ($attributes['result'][0]['attributes'] as $attribute) {
						if ($attribute['id'] != in_array($attribute['id'], $pass_id, true)) {
							$ozon_attribute_id = htmlspecialchars($attribute['id']);
							$ozon_attribute_name = htmlspecialchars($attribute['name']);
							$ozon_attribute_description = htmlspecialchars($attribute['description']);
							$ozon_dictionary_id  = htmlspecialchars($attribute['dictionary_id']);
							$required = htmlspecialchars($attribute['is_required']);
							$this->model_module_ozon_seller->saveAttributeDescription($ozon_attribute_id, $ozon_attribute_name, $ozon_attribute_description, $ozon_dictionary_id, $required);
							if ($required) {
								$this->model_module_ozon_seller->saveAttributeRequired($category['ozon'], $ozon_attribute_id);
							}
							$this->model_module_ozon_seller->saveAttribute($category['ozon'], $ozon_attribute_id);
							if ($ozon_dictionary_id) {
								$this->log_process('[' . $i . '/' . $count_attributes . '] Загружаю справочник характеристик для атрибута "' . $ozon_attribute_name . '", категория ' . $category['ozon'] . '. Ожидайте... ');
								$this->getValueAttribute($category['ozon'], $ozon_attribute_id, $ozon_dictionary_id);
							}
						}
						$i++;
					}
				}
			}
			$this->log_process('Готово. Перезагрузите страницу!');
		}
	}

	// Получение списка атрибутов для категории Ozon
	private function getAttribute($ozon_category_id)
	{
		$url = 'https://api-seller.ozon.ru/v3/category/attribute';
		$data = array(
			'attribute_type' => 'ALL',
			'category_id' => array($ozon_category_id),
			'language' => 'DEFAULT'
		);
		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
		return $response;
	}

	// Загружаем справочник атрибутов и его значения
	public function getValueAttribute($ozon_category_id, $ozon_attribute_id, $ozon_dictionary_id)
	{
		$url = 'https://api-seller.ozon.ru/v2/category/attribute/values';
		$data = array('category_id' => $ozon_category_id, 'attribute_id' => $ozon_attribute_id, 'limit' => 5000);
		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
		foreach ($response['result'] as $result) {
			$attribute_value_id = $result['id'];
			$text = htmlspecialchars($result['value']);
			$this->model_module_ozon_seller->saveDictonary($ozon_dictionary_id, $attribute_value_id, $ozon_category_id, $ozon_attribute_id, $text);
		}
		if ($response['has_next']) {
			do {
				$last_key = end($response['result']); //php < 7.3
				$last_value_id = $last_key['id']; //php < 7.3
				$data = array('category_id' => $ozon_category_id, 'attribute_id' => $ozon_attribute_id, 'limit' => 5000, 'last_value_id' => $last_value_id);
				$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
				foreach ($response['result'] as $result) {
					$attribute_value_id = $result['id'];
					$text = htmlspecialchars($result['value']);
					$this->model_module_ozon_seller->saveDictonary($ozon_dictionary_id, $attribute_value_id, $ozon_category_id, $ozon_attribute_id, $text);
				}
			} while ($response['has_next']);
		}
	}

	// Импорт и обновление товаров в Ozon
	public function importProduct()
	{
		header('Content-Type: text/html; charset=utf-8');
		$status_module = $this->config->get('ozon_seller_status');

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass') && $status_module) {

			if ($this->config->get('ozon_seller_category')) {
				$this->load->model('catalog/product');
				$this->load->model('tool/image');
				$this->load->model('module/ozon_seller');

				$ozon_seller_category = $this->config->get('ozon_seller_category');
				$ozon_seller_attribute = $this->config->get('ozon_seller_attribute');
				$dictionary_shop_to_ozon = $this->model_module_ozon_seller->getDictionaryShoptoOzon();// сопоставленные значения справочника

				$ozon_attribute_description = $this->model_module_ozon_seller->getOzonAttributeDescription();

				if (!empty($this->config->get('ozon_seller_manufacturer_stop'))) {
					$stop_manufacturer = implode(",", $this->config->get('ozon_seller_manufacturer_stop'));
				} else {
					$stop_manufacturer = 0;
				}

				foreach ($ozon_seller_category as $category_import) {
					if (!empty($this->request->get['category']) && $this->request->get['category'] != 'all') {
						if ($this->request->get['category'] != $category_import['shop']) {
							continue;
						}
					}
					if (empty($category_import['type'])) {
						continue;
					}
					if (isset($category_import['stop'])) {
						continue;
					}

					$filter_data = array(
						'filter_category_id' => $category_import['shop'],
						'filter_manufacturer_id' => $stop_manufacturer,
						'filter_sub_category'=> false, //выводить товары из подкатегорий
						'start'				 => 0,
						'limit'              => $this->config->get('ozon_seller_limit') //лимит количества товаров на выгрузку за один раз
					);

					$products = $this->model_module_ozon_seller->getProducts($filter_data);

					if ($products) {
						$data['items'] = array(); //кладем товары в массив
						$export_table = array();
						foreach ($products as $product) {
							//черный список
							if ($this->config->get('ozon_seller_product_blacklist')) {
								$blacklist = $this->config->get('ozon_seller_product_blacklist');
								if (!in_array($product['product_id'], $blacklist)){
									continue;
								}
							}

							if (empty($product['image'])) {
								echo $product['name'] . ' * нет картинки - пропускаю *<br />';
								continue;
							}

							//$offer_id
							$offer_id = $product[$this->config->get('ozon_seller_entry_offer_id')];
							if (empty($offer_id)) {
								echo $product['name'] . ' не заполнен ' . $this->config->get('ozon_seller_entry_offer_id') . ' - пропускаю<br />';
								$this->log('Выгрузка товаров: ' . $product['name'] . ' не заполнен ' . $this->config->get('ozon_seller_entry_offer_id'), 0);
								continue;
							}

							// Размеры
							if ($product['weight_class_id'] == $this->config->get('ozon_seller_weight')) {
								if ($product['weight'] == 0) {
									$weight = $category_import['weight'];
								} else {
									$weight = $product['weight'];
								}
							} else {
								if ($product['weight'] == 0) {
									$weight = $category_import['weight'];
								} else {
									$weight = $product['weight'] * 1000;
								}
							}
							$price_weight = $weight / 1000;

							if ($product['length_class_id'] == $this->config->get('ozon_seller_length')) {

								if ($product['length'] == 0) {
									$length = $category_import['length'];
								} else {
									$length = $product['length'];
								}
								if ($product['width'] == 0) {
									$width = $category_import['width'];
								} else {
									$width = $product['width'];
								}
								if ($product['height'] == 0) {
									$height = $category_import['height'];
								} else {
									$height = $product['height'];
								}

							} else {

								if ($product['length'] == 0) {
									$length = $category_import['length'];
								} else {
									$length = $product['length'] / 10;
								}
								if ($product['width'] == 0) {
									$width = $category_import['width'];
								} else {
									$width = $product['width'] / 10;
								}
								if ($product['height'] == 0) {
									$height = $category_import['height'];
								} else {
									$height = $product['height'] / 10;
								}
							}

							// Цена
							if ($product['special']) {
								$price = $product['special'];
							} else {
								$price = $product['price'];
							}
							if (!empty($this->config->get('ozon_seller_min_price')) && $price < $this->config->get('ozon_seller_min_price')) {
								continue;
							}
							$price = $this->price($price, $product['manufacturer_id'], $product['category_id'], $product['product_id'], $length, $width, $height, $price_weight);

							// Изображения товара
							$top_image = $this->model_tool_image->resize($product['image'], 1200, 1200);
							$images = array();
							$images[] = $top_image;
							$get_images = $this->model_catalog_product->getProductImages($product['product_id']);
							$images_count = 0;
							if (!empty($get_images)) {
								foreach ($get_images as $get_image) {
									$resize_images = $this->model_tool_image->resize($get_image['image'], 1200, 1200);
									if (!empty($resize_images)) {
										$images[] = $resize_images;
										$images_count++;
										if ($images_count == 13) {
											break;
										}
									} else {
										continue;
									}
								}
							}

							// Rich-контент
							$rich_content['content'] = array();

							if ($this->config->get('ozon_seller_description') != 'no') {
								if ($this->config->get('ozon_seller_description') == 'card') {
									$description = $product['description'];
								} elseif ($this->config->get('ozon_seller_description') == 'my') {
									$description = $this->config->get('ozon_seller_my_description');
								}
								$description = str_replace('&lt;p&gt;', '', $description);
								$description = str_replace('&lt;/p&gt;', '^', $description);
								$description = htmlspecialchars_decode($description, ENT_NOQUOTES);
								$description = strip_tags($description, '<br> <style> </style>');
								$description = preg_replace('/\s?<style>*?>.*?<\/style>\s?/', ' ', $description);
								$description = str_replace('&quot;', '\'', $description);
								$description = str_replace(array('–', '&ndash;'), '-', $description);
								$description = str_replace(array('&nbsp;', '&bull;'), ' ', $description);
								$description = preg_replace('/([\r\n]){2,}/s', '\1', $description);
								$description = preg_replace('/[\t]+/','', $description);
								$description = preg_replace('/[\n]+/','^', $description);
								$description = str_replace(array('<br/>', '<br />', '<br>'), '^', $description);
								$descrip = explode('^', $description);
								$description = array();
								foreach ($descrip as $desc) {
									if (!empty($desc)) {
										$description[]= $desc;
									}
								}
								if (!empty($description)) {
									$rich_content['content'][] = array(
										'widgetName' => 'raTextBlock',
										'theme' => 'default',
										'gapSize' => 'm',
										'text' => array(
											'size' => 'size2',
											'align' => 'left',
											'color' => 'color1',
											'content' => $description
										)
									);
								}
							}

							if ($this->config->get('ozon_seller_attribute_description') == 'on') {
								$attributes_product = $this->model_catalog_product->getProductAttributes($product['product_id']); //массив с атрибутами для вставки в описание
								if ($attributes_product) {
									$table_attr = array();
									foreach ($attributes_product as $attribute_product) {
										foreach ($attribute_product['attribute'] as $attribute) {
											$attribute_name = html_entity_decode($attribute['name'], ENT_NOQUOTES);
											$attribute_text = html_entity_decode($attribute['text'], ENT_NOQUOTES);
											$table_attr[] = array(
												array(str_replace('&quot;', '\'', $attribute_name)),
												array(str_replace('&quot;', '\'', $attribute_text))
											);
										}
									}
									$rich_content['content'][] = array(
										'widgetName' => 'raTable',
										'title' => array(
											'content' => array('Характеристики товара от продавца'),
											'size' => 'size4'
										),
										'table' => array(
											'body' => $table_attr,
											'head' => array(
												array(
													'text' => array('Наименование')
												),
												array(
													'text' => array('Значение')
												),
											)
										)
									);
								}
							}
							$version_rich_content = array('version' => round(0.3, 1));
							$rich_content = $rich_content + $version_rich_content;
							ini_set('serialize_precision', -1); //патч бага php 7.3

							$attribute_export = array(
								/* Вес с упаковкой в гр. */
								array(
									'complex_id' => (int)0,
									'id' => (int)4497,
									'values' => array(
										array(
											'dictionary_value_id' => (int)0,
											'value' => (string)$weight
										)
									)
								),
								/* Rich контент */
								array(
									'complex_id' => (int)0,
									'id' => (int)11254,
									'values' => array(
										array(
											'dictionary_value_id' => (int)0,
											'value' => json_encode($rich_content, JSON_UNESCAPED_UNICODE)
										)
									)
								)
							);

							// Бренд
							$ozon_manufacturer = $this->model_module_ozon_seller->getManufacturer($product['manufacturer_id']);

							if (!empty($ozon_manufacturer)) {
								$attribute_export[] = array(
									'complex_id' => (int)0,
									'id' => (int)85,
									'values' => array(
										array(
											'dictionary_value_id' => (int)$ozon_manufacturer[0]['ozon_id'],
											'value' => (string)$ozon_manufacturer[0]['value']
										)
									)
								);
							} else {
								$attribute_export[] = array(
									'complex_id' => (int)0,
									'id' => (int)85,
									'values' => array(
										array(
											'dictionary_value_id' => (int)0,
											'value' => (string)$product['manufacturer']
										)
									)
								);
							}

							// Тип товара соответствует всей категории
							if ($category_import['type'] != 'attr') {

								$value = $this->model_module_ozon_seller->getDictionaryByCategoryAndAttributeId($category_import['ozon'], $category_import['type']);

								$attribute_export[] = array(
									'complex_id' => (int)0,
									'id' => (int)8229,
									'values' => array(
										array(
											'dictionary_value_id' => (int)$category_import['type'],
											'value' => (string)$value['text']
										)
									)
								);
							}

							// группы атрибутов категории Ozon (oc_ozon_attribute)
							$attribute_ozon = $this->model_module_ozon_seller->getOzonAttribute($category_import['ozon']);

							// язык по-умолчанию
							$this->load->model('setting/setting');
							$language_config = $this->model_setting_setting->getSetting('config', $this->config->get('config_store_id'));
							$language = $language_config['config_language'];
							$language_id = $this->model_module_ozon_seller->getLanguage($language);

							// значения атрибутов товара в магазине
							$dictionary_shop = $this->model_module_ozon_seller->getShopDictionary($product['product_id'], $language_id);

							if ($dictionary_shop) {
								unset($type_stop);
								foreach ($dictionary_shop as $dictionar_shop) {
									foreach ($dictionary_shop_to_ozon as $dictionar_shop_to_ozon) {

										// Тип товара соответствует атрибутам
										if ($dictionar_shop_to_ozon['ozon_attribute_id'] == 8229
										&& $category_import['type'] == 'attr'
										&& !isset($type_stop)) {

											$text_shop_attr = $dictionar_shop_to_ozon['text_shop_attribute'];
											$delimiter = '++';
											$find_delimiter = strpos($text_shop_attr, $delimiter);

											if ($find_delimiter === false) {
												$text_shop_attr = array($text_shop_attr);
											} else {
												$text_shop_attr = explode($delimiter, $text_shop_attr);
											}
										}

										if ($dictionar_shop_to_ozon['ozon_attribute_id'] == 8229 && $category_import['type'] != 'attr') {
											continue;
										} elseif ($dictionar_shop_to_ozon['ozon_attribute_id'] == 8229
										&& $category_import['type'] == 'attr'
										&& in_array($dictionar_shop['text'], $text_shop_attr)
										&& !isset($type_stop)) {

											// Проверить, что сопоставленный атрибут типа товара принадлежит категории Озон
											$type_attr = $this->model_module_ozon_seller->getDictionaryByCategoryAndAttributeId($category_import['ozon'], $dictionar_shop_to_ozon['dictionary_value_id']);
											if ($type_attr['attribute_value_id'] != $dictionar_shop_to_ozon['dictionary_value_id']) {
												continue 3;
											}

											$attribute_export[] = array(
												'complex_id' => (int)0,
												'id' => (int)8229,
												'values' => array(
													array(
														'dictionary_value_id' => (int)$dictionar_shop_to_ozon['dictionary_value_id'],
														'value' => (string)$dictionar_shop_to_ozon['value']
													)
												)
											);
											$type_stop = true;
										}

										// Добавляем в выгрузку сопоставленные атрибуты магазина со справочником Ozon из таблицы ozon_to_shop_dictionary
										if ($dictionar_shop['attribute_id'] == $dictionar_shop_to_ozon['shop_attribute_id']) {
											$translit_shop = $this->translit($dictionar_shop['text']);
											$translit_ozon = $this->translit($dictionar_shop_to_ozon['text_shop_attribute']);
											if (strcasecmp($translit_shop, $translit_ozon) == 0) {
												$dictionar_value_id = explode('^', $dictionar_shop_to_ozon['dictionary_value_id']);
												$value_attr = explode('^', $dictionar_shop_to_ozon['value']);
												$output_attr = array_combine($dictionar_value_id, $value_attr);
												$values = array();
												foreach ($output_attr as $key => $out_attr) {
													$values[] = array(
														'dictionary_value_id' => $key,
													 	'value' => $out_attr
													);
												}
												$attribute_export[] = array(
													'complex_id' => (int)0,
													'id' => (int)$dictionar_shop_to_ozon['ozon_attribute_id'],
													'values' => $values
												);
											}
										}
									}

									$attributes_ozon_to_shop = array_keys($ozon_seller_attribute, $dictionar_shop['attribute_id']);

									foreach ($ozon_attribute_description as $ozon_attr_description) {

										// Добавляем в выгрузку сопоставленные атрибуты магазина без справочника
										if (empty($ozon_attr_description['ozon_dictionary_id'])) {
											if (in_array($ozon_attr_description['ozon_attribute_id'], $attributes_ozon_to_shop)) {
												$replace = array(' mm', ' мм', ' см', ' кг');
												$value = str_replace($replace, '', $dictionar_shop['text']);

												$attribute_export[] = array(
													"complex_id" => (int)0,
													'id' => (int)$ozon_attr_description['ozon_attribute_id'],
													'values' => array(
														array(
															'dictionary_value_id' => (int)0,
															'value' => (string)$value
														)
													)
												);
											}
										}
									}
								}
							}
							//если в админке модуля присвоили атрибутам поля OpenCart
							$oc_input = array('sku', 'model', 'mpn', 'isbn', 'ean', 'jan', 'upc');
							foreach ($ozon_seller_attribute as $k => $ozon_attr_seller) {
								if (in_array($ozon_attr_seller, $oc_input)) {
									foreach ($attribute_ozon as $attr_ozon) {
										if ($attr_ozon['ozon_attribute_id'] == $k) {
											$value = $product[$ozon_attr_seller];

											$attribute_export[] = array(
												"complex_id" => (int)0,
												'id' => (int)$k,
												'values' => array(
													array(
														'dictionary_value_id' => (int)0,
														'value' => (string)$value
													)
												)
											);
										}
									}
								}
							}

							$laquo = array('«', '»', '"', '°', '\'');
							$name = str_replace($laquo, '', $product['name']);
							$name = htmlspecialchars_decode($name);

							// штрих-код
							$barcode = $product['ean'];

							if (empty($product['ean'])) {
								$barcode = $product['upc'];
							} /*else {
								$barcode = mt_rand(1000000000000, 9999999999999);
							}*/

							$data['items'][] = array(
						  	'name' => $name,
						    'category_id' => (int)$category_import['ozon'],
								'offer_id' => $offer_id,
								'price' => (string)$price,
								'vat' => $this->config->get('ozon_seller_nds'),
								'barcode' => (string)$barcode,
								'weight' => (int)$weight,
								'weight_unit' => 'g',
								'dimension_unit' => 'cm',
								'depth' => (int)ceil($length),
								'width' => (int)ceil($width),
								'height' => (int)ceil($height),
								'primary_image' => $top_image,
								'images' => $images,
								'attributes' => $attribute_export
							);

							$export_table[] = array(
								'product_id' => $product['product_id'],
								'model' => $product['model'],
								'sku' => $product['sku']
							);
						}

						if ($this->config->get('ozon_seller_test_export') && !empty($data['items'])) {
							echo '<pre>';
							echo '=== ' . $this->config->get('ozon_seller_version') . ' ===<br />';
							echo '=== Включен тест экспорта товаров ===<br />';
							echo '=== Запрос не будет отправлен в Ozon ===<br />';
							echo '=== Чтобы выгрузить товары отключите тест ===<br />';
							ini_set('serialize_precision', -1); //патч бага php 7.3
							print_r(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
						} elseif (!empty($data['items'])) {
							$url = 'https://api-seller.ozon.ru/v2/product/import';
							$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

							if (isset($response['error']) || isset($response['message'])) {
								$this->log('Выгрузка товаров категория ID ' . $category_import['shop'] . ' ' . json_encode($response, JSON_UNESCAPED_UNICODE), 0);
								echo 'Категория ID ' . $category_import['shop'] . ': ' . json_encode($response, JSON_UNESCAPED_UNICODE) . '<br />';
							}

							if (!empty($response['result']['task_id'])) {
								$task_id = $response['result']['task_id'];
								$this->model_module_ozon_seller->saveExportProduct($task_id, $export_table);
								echo '++ Категория ID ' . $category_import['shop'] . ': товары успешно выгружены!<br />';
							}
						}
					}
				}
				if (!$this->config->get('ozon_seller_test_export')) {
		      echo '<br />=== Не забудьте обновить статусы товаров! ===<br />';
		    }
			}
		}
	}

	// Обновление остатков
	public function updateOzonProduct()
	{
		$status_module = $this->config->get('ozon_seller_status');
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass') && $status_module) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<pre>';
			echo '=== ' . $this->config->get('ozon_seller_version') . ' ===<br />';
			if (!empty($this->config->get('ozon_seller_sklad'))) {
				$this->updateStocks();
				$this->infoStocksUpdate();
			} else {
				echo 'Не могу обновить остатки, т.к не настроены склады<br />';
			}
		}
	}

	// Обновить цены
	public function updatePrice()
	{
		$status_module = $this->config->get('ozon_seller_status');
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass') && $status_module) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<pre>';
			echo '=== ' . $this->config->get('ozon_seller_version') . ' ===<br />';
			$update =	$this->priceUpdate();
			echo '[ЦЕНЫ] Товаров передано: ' . $update;
			if (empty($this->config->get('ozon_seller_test_export'))) {
				$this->infoPriceUpdate();
			}
		}
	}

	// Обновить цены
	private function priceUpdate()
	{
		if ($this->config->get('ozon_seller_test_export')) {
			$data['test'] = true;
		}
		if (!empty($this->config->get('ozon_seller_fictitious_price'))) {
			$data['fictitious'] = $this->config->get('ozon_seller_fictitious_price');
		}
		$this->load->model('module/ozon_seller');
		$ozon_seller_category = $this->config->get('ozon_seller_category');
		$price_product_update = 0;
		$start = 0;
		$limit = 1000;
		do {
			$filter_data = array(
				'start' => $start,
				'limit' => $limit
			);
			$products = $this->model_module_ozon_seller->updateProducts($filter_data);
			$start += $limit;
			$data['prices'] = array();

			foreach ($products as $product) {
				if (empty($product)) {
					continue;
				}
				foreach ($ozon_seller_category as $seller_category) {
					if ($product['category_id'] == $seller_category['shop']) {
						$default_weight = $seller_category['weight'] / 1000;
						$default_length = $seller_category['length'];
						$default_width = $seller_category['width'];
						$default_height = $seller_category['height'];
					}
				}

				if (empty($product['weight']) || empty($product['length']) || empty($product['width']) || empty($product['height'])) {
					if (!isset($default_height)) {
						$this->log('Цены. Модель: ' . $product['model'] . ' Артикул: ' . $product['sku'] . ' ошибка в размере по умолчанию. Скорее всего у товара в Opencart назначена главная категория, которая не сопоставлена в модуле с категорие Ozon. Чтобы ошибка не повторялась заполните размеры у товара или сопоставьте категории в модуле.', 0);

						echo 'Цены. Модель: ' . $product['model'] . ' Артикул: ' . $product['sku'] . ' ошибка в размере по умолчанию. Скорее всего у товара в Opencart назначена главная категория, которая не сопоставлена в модуле с категорие Ozon. Чтобы ошибка не повторялась заполните размеры у товара или сопоставьте категории в модуле.<br />';
						continue;
					}
				}

				if ($product['weight_class_id'] == $this->config->get('ozon_seller_weight')) {
					if ($product['weight'] == 0) {
						$weight = $default_weight;
					} else {
						$weight = $product['weight'] / 1000;
					}
				} else {
					if ($product['weight'] == 0) {
						$weight = $default_weight;
					} else {
						$weight = $product['weight'];
					}
				}

				if ($product['length_class_id'] == $this->config->get('ozon_seller_length')) {
					if ($product['length'] == 0) {
						$length = $default_length;
					} else {
						$length = $product['length'];
					}
					if ($product['width'] == 0) {
						$width = $default_width;
					} else {
						$width = $product['width'];
					}
					if ($product['height'] == 0) {
						$height = $default_height;
					} else {
						$height = $product['height'];
					}
				} else {
					if ($product['length'] == 0) {
						$length = $default_length;
					} else {
						$length = $product['length'] / 10;
					}
					if ($product['width'] == 0) {
						$width = $default_width;
					} else {
						$width = $product['width'] / 10;
					}
					if ($product['height'] == 0) {
						$height = $default_height;
					} else {
						$height = $product['height'] / 10;
					}
				}

				if ($product['special']) {
					$price = $product['special'];
				} else {
					$price = $product['price'];
				}

				$price = $this->price($price, $product['manufacturer_id'], $product['category_id'], $product['product_id'], $length, $width, $height, $weight, $product['stock_fbo']);

				// $offer_id
				$offer_id = $product[$this->config->get('ozon_seller_entry_offer_id')];
				if (empty($offer_id)) {
					continue;
				}

				// Черный список цен
				if ($this->config->get('ozon_seller_product_npu')) {
					$products_npu = $this->config->get('ozon_seller_product_npu');
				}

				if (empty($products_npu) || !in_array($product['product_id'], $products_npu)) {
					$data['prices'][] = array(
						'offer_id' => $offer_id,
						'price' => (string)ceil($price)
					);
				}
			}
			$this->cdlRequest($data, $request = 'price');
			$price_product_update += count($data['prices']);
		} while (count($products) == $limit);
		return $price_product_update;
	}

	// Обновить остатки
	private function updateStocks()
	{
		$url_stocks = 'https://api-seller.ozon.ru/v2/products/stocks';
		if ($this->config->get('ozon_seller_test_export')) {
			echo '=== Включен тест формирования остатков ===<br />';
			echo '=== Запрос не будет отправлен в Ozon ===<br />';
			echo '=== Чтобы выгрузить данные отключите тест ===<br />';
			echo '=== ' . $url_stocks . ' ===<br />';
		}
		$this->load->model('module/ozon_seller');
		$ozon_seller_category = $this->config->get('ozon_seller_category');
		$warehouses = $this->config->get('ozon_seller_sklad');
		foreach ($warehouses as $sklad_id => $warehous) {
			$stok_product_update = 0;
			$stok_in = 0;
			$start = 0;
			$limit = 100;
			do {
				$filter_data = array(
					'start' => $start,
					'limit' => $limit
				);
				$products = $this->model_module_ozon_seller->updateProducts($filter_data);
				$start += $limit;
				$stocks['stocks'] = array();

				foreach ($products as $product) {
					if (empty($product)) {
						continue;
					}
					if (!empty($warehous['weight_do']) || !empty($warehous['weight'])) {
						foreach ($ozon_seller_category as $seller_category) {
							if ($product['category_id'] == $seller_category['shop']) {
								$default_weight = $seller_category['weight'] / 1000;
							}
						}
						if ($product['weight_class_id'] == $this->config->get('ozon_seller_weight')) {
							if ($product['weight'] == 0) {
								$weight = $default_weight;
							} else {
								$weight = $product['weight'] / 1000;
							}
						} else {
							if ($product['weight'] == 0) {
								$weight = $default_weight;
							} else {
								$weight = $product['weight'];
							}
						}
					}
					// $offer_id
					$offer_id = $product[$this->config->get('ozon_seller_entry_offer_id')];
					if (empty($offer_id)) {
						$this->log('Остатки: ' . $this->config->get('ozon_seller_entry_offer_id') . ' ' . $offer_id . ' не найден в товарах Opencart');
						continue;
					}

					if ($product['special']) {
						$price = $product['special'];
					} else {
						$price = $product['price'];
					}

					$product_stock = $product['quantity'];

					if ($product_stock < 0) {
						$product_stock = 0;
					}
					// Черный список
					if ($this->config->get('ozon_seller_product_blacklist')) {
						$blacklist = $this->config->get('ozon_seller_product_blacklist');
						if (!in_array($product['product_id'], $blacklist)){
							$product_stock = 0;
						}
					}
					// Фильтр настройки складов
					if (!empty($warehous['null'])) {
						$product_stock = 0;
					}
					if (!empty($warehous['stock']) && $product_stock < $warehous['stock']) {
						$product_stock = 0;
					}
					if (!empty($warehous['price']) && $price < $warehous['price']) {
						$product_stock = 0;
					}
					if (!empty($warehous['price_do']) && $price > $warehous['price_do']) {
						$product_stock = 0;
					}
					if (!empty($warehous['weight']) && $weight < $warehous['weight']) {
						$product_stock = 0;
					}
					if (!empty($warehous['weight_do']) && $weight > $warehous['weight_do']) {
						$product_stock = 0;
					}
					if (!empty($warehous['manufacture']) && in_array($product['manufacturer_id'], $warehous['manufacture'])) {
						$product_stock = 0;
					}
					if (!empty($warehous['white_list']) && in_array($product['product_id'], $warehous['white_list'])) {
						$product_stock = $product['quantity'];
					}
					if (!empty($warehous['black_list']) && in_array($product['product_id'], $warehous['black_list'])) {
						$product_stock = 0;
					}
					if (!empty($warehous['black_list_category'])) {
						$p_categorys = $this->model_module_ozon_seller->getProductCategorys($product['product_id']);
						if (!empty($p_categorys)) {
							foreach ($p_categorys as $p_category) {
								if (in_array($p_category['category_id'], $warehous['black_list_category'])) {
									$product_stock = 0;
									break;
								}
							}
						}
					}
					if (!empty($warehous['no_update']) && in_array($product['product_id'], $warehous['no_update'])) {
						continue;
					}
					if ($product['status'] == 0) {
						$product_stock = 0;
					}
					if ($product_stock > 0) {
						$stok_in++;
					}

					$stocks['stocks'][] = array(
						'offer_id' => $offer_id,
						'stock' => (int)$product_stock,
						'warehouse_id' => (float)$sklad_id
					);
				}
				if ($this->config->get('ozon_seller_test_export')) {
          print_r(json_encode($stocks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
				} else {
					$response = $this->makeRequest($url_stocks, $request = 'POST', $stocks, $api = 'ozon');
				}
				$stok_product_update += count($stocks['stocks']);
			} while (count($products) == $limit);

			echo '[ОСТАТКИ ' . $warehous['name'] . '] Товаров передано: ' . $stok_product_update . '. В наличии: ' . $stok_in . '<br />';
		}
	}

	// Расчет цены на товар
	private function price($price, $manufacturer_id, $category_id, $product_id, $length, $width, $height, $weight, $fbo = 0)
	{
		// Дополнительные наценки
		if (!empty($this->config->get('ozon_seller_prices'))) {
			$new_price = 0;
			foreach ($this->config->get('ozon_seller_prices') as $prices) {
				$values = explode('-', $prices['value']);
				if ($prices['els'] == 'price' && !empty($values[1])) {
					if ($price >= $values[0] && $price <= $values[1]) {
						$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
					}
				} elseif ($prices['els'] == 'price' && empty($values[1])) {
					if ($price = $values[0]) {
						$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
					}
				} elseif ($prices['els'] == 'manufacturer_id' && $manufacturer_id == $values[0]) {
					$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
				} elseif ($prices['els'] == 'category_id' && $category_id == $values[0]) {
					$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
				} elseif ($prices['els'] == 'product_id' && $product_id == $values[0]) {
					$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
				}
			}
			$price += $new_price;
		}

		// FBS и FBO надбавки
		if (empty($fbo)) {
			// Магистраль
			if (!empty($this->config->get('ozon_seller_highway'))) {
				$volume_weight = (int)ceil($length) * (int)ceil($width) * (int)ceil($height) / 5000;
				if ($weight > $volume_weight) {
					$volume_weight = $weight;
				}
				$highway = $volume_weight * $this->config->get('ozon_seller_highway');
				if ($highway < 38 ) {
					$highway = 38;
				} else if ($highway > 100) {
					$highway = 700;
				}
				$price += $highway;
			}
			// Фикс
			if ($this->config->get('ozon_seller_ruble')) {
				$price += $this->config->get('ozon_seller_ruble');
			}
			// Последняя миля
			if ($this->config->get('ozon_seller_last_mile')) {
				$last_mile = $price * 5 / 100;
				if ($last_mile < 60) {
					$last_mile = 60;
				} else if ($last_mile > 350) {
					$last_mile = 350;
				}
				if ($this->config->get('ozon_seller_min_last_mile') && $price < $this->config->get('ozon_seller_min_last_mile')) {
					$price += $last_mile;
				}
				if (empty($this->config->get('ozon_seller_min_last_mile'))) {
					$price += $last_mile;
				}
			}
			// Процент
			if ($this->config->get('ozon_seller_percent')) {
				$percent = $this->config->get('ozon_seller_percent');
				$price_up = $price * $percent / 100;
				$price += $price_up;
			}
		} else {
			// Магистраль
			if (!empty($this->config->get('ozon_seller_highway'))) {
				$volume_weight = (int)ceil($length) * (int)ceil($width) * (int)ceil($height) / 5000;
				if ($weight > $volume_weight) {
					$volume_weight = $weight;
				}
				$highway = $volume_weight * $this->config->get('ozon_seller_highway');
				if ($highway < 38 ) {
					$highway = 38;
				} else if ($highway > 700) {
					$highway = 700;
				}
				$price += $highway;
			}
			// Фикс
			if ($this->config->get('ozon_seller_ruble_fbo')) {
				$price += $this->config->get('ozon_seller_ruble_fbo');
			}
			// Последняя миля
			if ($this->config->get('ozon_seller_last_mile_fbo')) {
				$last_mile = $price * 5 / 100;
				if ($last_mile < 13) {
					$last_mile = 13;
				} else if ($last_mile > 250) {
					$last_mile = 250;
				}
				if ($this->config->get('ozon_seller_min_last_mile_fbo') && $price < $this->config->get('ozon_seller_min_last_mile_fbo')) {
					$price += $last_mile;
				}
				if (empty($this->config->get('ozon_seller_min_last_mile_fbo'))) {
					$price += $last_mile;
				}
			}
			// Процент
			if ($this->config->get('ozon_seller_percent_fbo')) {
				$percent = $this->config->get('ozon_seller_percent_fbo');
				$price_up = $price * $percent / 100;
				$price += $price_up;
			}
		}

		// Округление цен
		switch ($this->config->get('ozon_seller_price_round')) {
			case 'st':
				$price = round($price, -1);
				break;
			case 'ten':
				$price = ceil($price / 10) * 10;
				break;
			case 'st_so':
				$price = round($price, -2);
				break;
			case 'so':
				$price = ceil($price / 100) * 100;
				break;
			case 'fifty':
				$price = ceil($price / 50) * 50;
				break;
			default:
				$price;
				break;
		}
		return $price;
	}

	// Действие с наценкой
	private function actionPrice($action, $price, $rate)
	{
		switch ($action) {
			case '*':
				$action_price = $price * $rate;
				$action_price = $action_price - $price;
				break;
			case '-':
				$action_price = $price - $rate;
				$action_price = $price - $action_price;
				break;
			case '+':
				$action_price = $price + $rate;
				$action_price = $action_price - $price;
				break;

			default:
				false;
				break;
		}
		return $action_price;
	}

	// Проверить статус товара
	public function updateNewOzonProduct()
	{
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {
			$this->load->model('module/ozon_seller');
			$ozon_attribute_description = $this->model_module_ozon_seller->getOzonAttributeDescription();
			$ozon_task_id = $this->model_module_ozon_seller->chekTaskId();
			$i = 0;

			if (!empty($ozon_task_id)) {
				$this->log_process('Начинаю проверку...');
				foreach ($ozon_task_id as $task_id) {
					$products_export = $this->checkImportProduct($task_id['task_id']);

					if (!empty($products_export['result']['items'])) {
						foreach ($products_export['result']['items'] as $product_export) {
							$status = htmlspecialchars($product_export['status']);
							$offer_id = htmlspecialchars($product_export['offer_id']);
							$ozon_product_id = htmlspecialchars($product_export['product_id']);
							$my_product = $this->model_module_ozon_seller->getExportProduct($ozon_product_id);
							if (!empty($my_product) && $my_product[0]['status'] == 'processed') {
								continue;
							}
							$ozon_sku = 0;
							$error = '';

							if ($status == 'imported') {
								$chek_product = $this->getOzonProduct($offer_id);
								if (!empty($chek_product['result'])){
									$status = htmlspecialchars($chek_product['result']['status']['validation_state']);
									if ($status == 'success') {
										$status = 'processed';
									}
									foreach ($chek_product['result']['sources'] as $ozon_prod) {
										if ($ozon_prod['source'] == 'fbs') {
											$ozon_sku = htmlspecialchars($ozon_prod['sku']);
											break;
										}
									}
									if (empty($ozon_sku) && $status == 'processed') {
										$status = 'imported';
									}
								}
								if ($status != 'processed') {
									$error .= htmlspecialchars($chek_product['result']['status']['state_description']) . '<br />';
									if (!empty($product_export['errors'])) {
										$product_errors = $product_export['errors'];
										foreach ($product_errors as $product_error) {
											$error .= htmlspecialchars($product_error['attribute_name']) . '<br />' . htmlspecialchars($product_error['description'])  . '<br />';
										}
									}
								}
							}
							$i++;
							$this->log_process('Проверяю товары со статусом не processed. Проверено: ' . $i);
							$this->model_module_ozon_seller->updateExportProduct($status, $offer_id, $ozon_sku, $ozon_product_id, $error);
						}
					}
				}
				$this->log_process('Готово. Проверено ' . $i . '. Перезагрузите страницу');
			}
		}
	}

	/* Обновить данные товара */
	public function changeOzonProducts() {

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {

			if (isset($this->request->get['product_shop_id'])) {

				$this->load->model('module/ozon_seller');

				$this->model_module_ozon_seller->deletedExportProduct($this->request->get['product_shop_id']);
			}

		}
	}

	/* Метод - проверить статус товара после иморта */
	private function checkImportProduct($task_id) {

		$url = 'https://api-seller.ozon.ru/v1/product/import/info';

		$data = array('task_id' => $task_id);

		$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

		return $response;

	}

	// Перенести товар в архив и удалить
	public function archive()
	{
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {

			if (isset($this->request->get['product_id'])) {
				$this->load->model('module/ozon_seller');
				$url = 'https://api-seller.ozon.ru/v1/product/archive';
				$data = array('product_id' => array($this->request->get['product_id']));
				$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

				if ($response['result']) {
					$ozon_product = $this->model_module_ozon_seller->getExportProduct($this->request->get['product_id']);

					if (!empty($ozon_product)) {
						$offer_id = $ozon_product[0][$this->config->get('ozon_seller_entry_offer_id')];
						// if ($this->config->get('ozon_seller_entry_offer_id')) {
						//
						// 	$offer_id = $ozon_product[0]['model'];
						//
						// } else {
						//
						// 	$offer_id = $ozon_product[0]['sku'];
						// }

						$url = 'https://api-seller.ozon.ru/v2/products/delete';
						$data = array('products' => array(array('offer_id' => $offer_id)));
						$respon = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

						if ($respon['statuses'][0]['is_deleted']) {
							$this->model_module_ozon_seller->deletedExportProduct($ozon_product['product_id']);
							echo $respon['statuses'][0]['is_deleted'];
						} else {
							echo 'Ошибка при добавлении товара в архив: ' . json_encode($respon);
						}
					}
				} else {
					echo $response['error']['data'][0]['message'];
				}
			}
		}
	}

	/* Скачать производителей с Ozon */
	public function manufacturerDownload() {

		header('Content-Type: text/html; charset=utf-8');

		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {

			$this->load->model('module/ozon_seller');

			$url = 'https://api-seller.ozon.ru/v2/category/attribute/values';

			$category = $this->request->get['category'];

			$count_response = 5000;

			$this->log_process('Загружаю производителей для категории ' . $category . '. Загружено: ' . $count_response);

			$data = array('category_id' => $category, 'attribute_id' => 85, 'limit' => 5000);

			$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

			foreach ($response['result'] as $result) {
				$ozon_id = htmlspecialchars($result['id']);
				$value = htmlspecialchars($result['value']);

				if (!empty($result['picture'])) {
					$picture = $result['picture'];
				} else {
					$picture = '';
				}

				$this->model_module_ozon_seller->saveManufacturer($ozon_id, $value, $picture);
			}

			if ($response['has_next']) {
				do {
					$last_key = end($response['result']); //php < 7.3
					$last_value_id = $last_key['id']; //php < 7.3
					//$last_key = array_key_last($response['result']); //php 7.3
					//$last_value_id = $response['result'][$last_key]['id']; //php 7.3
					$data = array('category_id' => $category, 'attribute_id' => 85, 'limit' => 5000, 'last_value_id' => $last_value_id);

					$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

					foreach ($response['result'] as $result) {
						$ozon_id = htmlspecialchars($result['id']);
						$value = htmlspecialchars($result['value']);

						if (!empty($result['picture'])) {
							$picture = $result['picture'];
						} else {
							$picture = '';
						}

						$this->model_module_ozon_seller->saveManufacturer($ozon_id, $value, $picture);
					}

					$count_response += count($response['result']);

					$this->log_process('Загружаю производителей для категории ' . $category . '. Загружено: ' . $count_response);

				} while ($response['has_next']);
			}

			$this->log_process('Производители успешно загружены!');
		}
	}

	//отправка письма на почту
	private function sendMail($theme, $message)
	{
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($theme, ENT_QUOTES, 'UTF-8'));
		$sended_message = html_entity_decode($message, ENT_QUOTES, 'UTF-8');
		$mail->setHtml($sended_message);
		$mail->send();
	}

	private function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
  }

	private function createOrderOc($info)
	{
		$posting_number = $info['posting_number'];
		$shipment_date = date('Y-m-d H:i:s', strtotime($info['shipment_date']));
		$products = $info['products'];
		$status = htmlspecialchars($info['status']);
		$barcodes = $info['barcodes'];
		$analytics_data = $info['analytics_data'];

		$this->load->model('module/ozon_seller');
		$this->load->model('checkout/order');

		$stop = '';
		$subtotal = 0;
		$total = 0;
		$totals = array();
		$order_data['products'] = array();

		foreach ($products as $product) {
			$get_product_id = $this->model_module_ozon_seller->getExportProduct($product['sku']);

			if (empty($get_product_id)) {
				$get_product_id = $this->model_module_ozon_seller->searchExportProduct($product['offer_id']);
				// if ($this->config->get('ozon_seller_entry_offer_id')) {
				// 	$get_product_id = $this->model_module_ozon_seller->getProductByModel($product['offer_id']);
				// }
				// if (empty($this->config->get('ozon_seller_entry_offer_id'))) {
				// 	$get_product_id = $this->model_module_ozon_seller->getProductBySku($product['offer_id']);
				// }
			}
			$product_id = $get_product_id[0]['product_id'];

			if (empty($product_id)) {
				$this->log($posting_number . ' ошибка: товар или комплект ' . $product['offer_id'] . ' не найден в товарах Opencart. Заказ будет перевыгружен.', 0);
				$stop = 'stop';
 				break;
			}

			$product_info = $this->model_module_ozon_seller->getProduct($product_id);

			if ($this->config->get('ozon_seller_product_price_oc')) {
				if ($product_info['special']) {
					$product_price = $product_info['special'];
				} else {
					$product_price = $product_info['price'];
				}
			} else {
				$product_price = $product['price'];
			}

			$order_data['products'][] = array(
				'product_id' => $product_info['product_id'],
				'name'       => $product_info['name'],
				'model'      => $product_info['model'],
				'option'     => array(),
				'download'   => array(),
				'quantity'   => $product['quantity'],
				//'subtract'   => $product['subtract'],
				'price'      => $product_price,
				'total'      => $product_price * $product['quantity'],
				'tax'        => 0,
				'reward'     => 0
			);
			$subtotal += $product_price * $product['quantity'];
		}

		$total = $subtotal;
		$id_ozon_client = substr($posting_number, 0, 8);
		$currency = 'RUB';

		if (!empty($this->config->get('ozon_seller_lastname'))) {
			$lastname = $this->config->get('ozon_seller_lastname');
		} else {
			$lastname = $posting_number;
		}

		if (empty($info['customer'])) {
			$firstname = 'Ozon FBS';
			$telephone = $id_ozon_client;
			$zip_code = '';
			$adress = $analytics_data['delivery_type'];
			$delivery_method = $analytics_data['delivery_type'];
		} else {
			$fio = explode(' ', $info['customer']['name']);
			$firstname = $fio[0] . ' ' . $fio[1];
			$telephone = '+' . $info['customer']['phone'];
			$zip_code = $info['customer']['address']['zip_code'];
			$adress = $info['customer']['address']['address_tail'];
			$delivery_method = $info['delivery_method']['name'];
		}

		$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$order_data['store_id'] = $this->config->get('config_store_id');
		$order_data['store_name'] = $this->config->get('config_name');
		$order_data['store_url'] = ($this->config->get('config_store_id') ? $this->config->get('config_url') : HTTP_SERVER);
		$order_data['customer_id'] = 0;
		$order_data['customer_group_id'] = 1;
		$order_data['firstname'] = $firstname;
		$order_data['lastname'] = $lastname;
		$order_data['email'] = $id_ozon_client . '@ozon.ru';
		$order_data['telephone'] = $telephone;
		$order_data['fax'] = '';

		$order_data['payment_firstname'] = $firstname;
		$order_data['payment_lastname'] = $lastname;
		$order_data['payment_company'] = '';
		$order_data['payment_company_id'] = '';
		$order_data['payment_tax_id'] = '';
		$order_data['payment_address_1'] = $adress;
		$order_data['payment_address_2'] = '';
		$order_data['payment_city'] = $analytics_data['city'];
		$order_data['payment_postcode'] = $zip_code;
		$order_data['payment_country'] = 'Российская Федерация';
		$order_data['payment_country_id'] = 176;
		$order_data['payment_zone'] = '';
		$order_data['payment_zone_id'] = '';
		$order_data['payment_address_format'] = '';
		$order_data['payment_method'] = $analytics_data['payment_type_group_name'];
		$order_data['payment_code'] = 'cod';

		$order_data['shipping_firstname'] = $firstname;
		$order_data['shipping_lastname'] = $lastname;
		$order_data['shipping_company'] = '';
		$order_data['shipping_address_1'] = $adress;
		$order_data['shipping_address_2'] = '';
		$order_data['shipping_city'] = $analytics_data['city'];
		$order_data['shipping_postcode'] = $zip_code;
		$order_data['shipping_country'] = 'Российская Федерация';
		$order_data['shipping_country_id'] = 176;
		$order_data['shipping_zone'] = '';
		$order_data['shipping_zone_id'] = '';
		$order_data['shipping_address_format'] = '';
		$order_data['shipping_method'] = $delivery_method;
		$order_data['shipping_code'] = '';

		$order_data['comment'] = 'Дата отгрузки: ' . $shipment_date . '<br />';
		if (!empty($info['customer'])) {
			$order_data['comment'] .= $posting_number . '<br />';
		}
		if (!empty($barcodes)) {
			$order_data['comment'] .= $barcodes['upper_barcode'] . '<br />' . $barcodes['lower_barcode'] . '<br />';
		}
		if (!empty($info['customer']['address']['comment'])) {
			$order_data['comment'] .= $info['customer']['address']['comment'] . '<br />';
		}

		$order_data['total'] = $total;
		$order_data['order_status_id'] = 0;
		// $order_data['order_status'] = 'Ошибочные заказы';
		$order_data['affiliate_id'] = 0;
		$order_data['commission'] = 0;
		$order_data['language_id'] = $this->config->get('config_language_id');
		$order_data['currency_id'] = $this->currency->getId($currency);
		$order_data['currency_code'] = $currency;
		$order_data['currency_value'] = $this->currency->getValue($currency);
		$order_data['ip'] = $this->request->server['REMOTE_ADDR'];
		$order_data['forwarded_ip'] = (isset($this->request->server['HTTP_X_FORWARDED_FOR']) ? $this->request->server['HTTP_X_FORWARDED_FOR'] : $this->request->server['REMOTE_ADDR']);
		$order_data['user_agent'] = 'Yandex Robot';
		$order_data['accept_language'] = (isset($this->request->server['HTTP_ACCEPT_LANGUAGE']) ? $this->request->server['HTTP_ACCEPT_LANGUAGE'] : '');
		$order_data['vouchers'] = array();
    $order_data['marketing_id'] = 0;
    $order_data['tracking'] = '';

		$order_data['payment_custom_field'] = array(
			'name' => 'Способ оплаты',
			'value' => $analytics_data['payment_type_group_name'],
			'sort_order' => 1
		);

		$order_data['totals'][] = array('code' => 'sub_total', 'title' => 'Сумма', 'text' => $this->currency->format($subtotal, $currency), 'value' => $subtotal, 'sort_order' => 1);
		//доставка бесплатная
		$shipping_price = 0;
		$total += $shipping_price;
		$order_data['totals'][] = array('code' => 'shipping', 'title' => 'Доставка', 'text' => $this->currency->format($shipping_price, $currency), 'value' => 0, 'sort_order'=>2);
		$order_data['totals'][] = array('code' => 'total', 'title' => 'Итого', 'text' => $this->currency->format($total, $currency), 'value' => $total, 'sort_order' => 3);

		if (empty($stop)) {
			$order_id = $this->model_checkout_order->addOrder($order_data);
			$this->changeOrderStatusOC($order_id, $this->config->get('ozon_seller_status_new'));
			return $order_id;
		}
	}

	private function changeOrderStatusOC($order_id, $order_status_id)
	{
		$this->load->model('checkout/order');
		$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', false);
	}

	// Получить склады в Озон
	public function warehouseOzon()
	{
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {
			$url = 'https://api-seller.ozon.ru/v1/warehouse/list';
			$response = $this->makeRequest($url, $request = 'POST', $data = '', $api = 'ozon');
			if (!empty($response['result'])) {
				echo json_encode($response['result']);
			} else {
				echo 'error';
			}
		}
	}

	// Связать товары из Озона с товарами в магазине
	public function downloadProduct()
	{
		if (isset($this->request->get['cron_pass']) && $this->request->get['cron_pass'] == $this->config->get('ozon_seller_cron_pass')) {
			$url = 'https://api-seller.ozon.ru/v2/product/list';
			$limit = 1000;
			$last_id = '';
			$products = array();
			do {
				$data = array(
					'filter' => array('visibility' => 'VISIBLE'),
					'last_id' => $last_id,
					'limit' => $limit
				);
				$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
				if (!empty($response['result']['items'])) {
					$products = array_merge($products, $response['result']['items']);
					$last_id = $response['result']['last_id'];
				}
				$count_product = count($products) + 1;
			} while ($count_product < $response['result']['total']);

			$i = 0;
			if (!empty($products)) {
				$this->load->model('module/ozon_seller');
				$skip = '';
				$add = '';
				foreach ($products as $product) {
					$ozon_product_id = $product['product_id'];
					$check = $this->model_module_ozon_seller->getExportProduct($ozon_product_id);
					if (empty($check[0])) {
						$product_info = $this->model_module_ozon_seller->searchExportProduct($product['offer_id']);
						// if ($this->config->get('ozon_seller_entry_offer_id')) {
						// 	$product_info = $this->model_module_ozon_seller->getProductByModel($product['offer_id']);
						// } else {
						// 	$product_info = $this->model_module_ozon_seller->getProductBySku($product['offer_id']);
						// }
						if (!empty($product_info[0])) {
							$product_id = $product_info[0]['product_id'];
							$model = $product_info[0]['model'];
							$sku = $product_info[0]['sku'];
							$this->model_module_ozon_seller->downloadProduct($product_id, $model, $sku, $ozon_product_id);
							$add .= $model . ', ';
							$i++;
						} else {
							$skip .= '     ' . $product['offer_id'] . ', ';
						}
					}
				}
			}
			if (!empty($skip)) {
				$skip_alert = "\nПропущенные смотрите в логе модуля.";
				$this->log('Связка товаров. Не найден в OC: ' . $skip, 0);
			} else {
				$skip_alert = '';
			}

			if (!empty($add)) {
				$this->log('Связка товаров. Связаны: ' . $add, 0);
			}
			echo 'Товаров связано: ' . $i . '.' . $skip_alert;
		}
	}

	// Создать акт приема передачи и накладной
	private function getActId()
	{
		$date = date('Y-m-d');
		$inf_file = 'act_ozon_seller.txt';

		if (file_exists(DIR_UPLOAD . $inf_file)) {
			$act_data = unserialize(file_get_contents(DIR_UPLOAD . $inf_file));

			if ($act_data['ozon_seller']['act']['date'] != $date) {
				unset($act_data['ozon_seller']['act']);
				$act_data['ozon_seller']['act']['date'] = $date;
			}
		} else {
			$act_data['ozon_seller']['act']['date'] = $date;
		}

		$check_download_act = glob(DIR_UPLOAD . $date . '_act_ozon_seller*.pdf');

		if (empty($check_download_act) && isset($act_data['ozon_seller']['act']['id'])) {
			$this->getAct($act_data['ozon_seller']['act']['id']);
		}

		if (!empty($check_download_act)) {
			$act_data['ozon_seller']['act']['print'] = 1;
		} else if (empty($check_download_act) && empty($act_data['ozon_seller']['act']['id'])) {
			$url = 'https://api-seller.ozon.ru/v3/posting/fbs/unfulfilled/list';
			$delivery_method_id = array();
			$stop = 0;
			$offset = 0;
			do {
				$data = array(
					'dir' => 'asc',
					'filter' => array(
						'cutoff_from' => $date . 'T00:00:01Z',
						'cutoff_to' => $date . 'T23:59:59Z',
						'warehouse_id' => $this->config->get('ozon_seller_warehouses'),
					),
					'limit' => '50',
					'offset' => $offset
				);
				$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

				if (!empty($response['result']['postings'])) {
					foreach ($response['result']['postings'] as $posting) {
						if ($posting['status'] == 'awaiting_packaging') {
							$act_data['ozon_seller']['act']['error'] = 'Не все заказы собраны';
							$stop = 1;
							break 2;
						} elseif ($posting['status'] == 'delivering') {
							$act_data['ozon_seller']['act']['error'] = 'Заказы уже сданы';
							$stop = 1;
							break 2;
						} else {
							if (!in_array($posting['delivery_method']['id'], $delivery_method_id)) {
								$delivery_method_id[] = $posting['delivery_method']['id'];
							}
						}
					}
				} else {
					$stop = 1;
					$act_data['ozon_seller']['act']['error'] = 'На сегодня нет заказов';
				}
				$offset += 50;
			} while ($response['result']['count'] == 50);

			if ($stop == 0) {
				$url = 'https://api-seller.ozon.ru/v2/posting/fbs/act/create';
				$id = array();
				foreach ($delivery_method_id as $method_id) {
					$data = array('delivery_method_id' => (int)$method_id);
					$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

					if (isset($response['result']['id'])) {
						$id[] = $response['result']['id'];
					}
				}

				if (!empty($id)) {
					$act_data['ozon_seller']['act']['id'] = $id;
					unset($act_data['ozon_seller']['act']['error']);
				}
			}
		}
		$act_data = serialize($act_data);
		file_put_contents(DIR_UPLOAD . $inf_file, $act_data);
	}

	private function getAct($ids)
	{
		$date = date('Y-m-d');
		foreach ($ids as $id) {
			$url = 'https://api-seller.ozon.ru/v2/posting/fbs/act/check-status';
			$data = array('id' => (int)$id);
			$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');

			if (!empty($response['result']['status'])) {
				if ($response['result']['status'] == 'ready') {
					$url = 'https://api-seller.ozon.ru/v2/posting/fbs/act/get-pdf';
					$respon = $this->request2($url, $data);
					file_put_contents(DIR_UPLOAD . $date . '_act_ozon_seller' . $id . '.pdf', $respon);
				}

				if ($response['result']['status'] == 'error' || $response['result']['status'] == "The next postings aren't ready") {
					$this->log('Акт и ТТН: в Озоне произошла ошибка при формировании документов', 0);
				}
			}
		}
	}

	// модуль администрирования заказов
	private function saveAdminMarketplace($order, $guid, $order_id)
	{
		$this->load->model('module/ozon_seller');
		if (!empty($order['products'])) {
			$this->model_module_ozon_seller->saveAdminMarketplace($order, $guid, $order_id);
		}
	}

	// Обновить остатки из Озон
	private function infoStocksUpdate()
	{
		$stocks = $this->infoStocks();
		if (!empty($stocks)) {
			$this->load->model('module/ozon_seller');
			foreach ($stocks as $stock) {
				foreach ($stock['stocks'] as $stoc) {
					if ($stoc['type'] == 'fbs') {
						$fbs = $stoc['present'];
					} elseif ($stoc['type'] == 'fbo') {
						$fbo = $stoc['present'];
					}
				}
				$this->model_module_ozon_seller->updateInfoStocks($fbs, $fbo, $stock['offer_id']);
			}
			echo '<br />[STOCK INFO] OK<br />';
		}
	}

	// Получить остатки на складах
	private function infoStocks()
	{
		$url = 'https://api-seller.ozon.ru/v3/product/info/stocks';
		$last_id = '';
		$stocks = array();
		do {
			$data = array(
				'filter' => array(
					'visibility' => 'VISIBLE'
		    ),
		    'last_id' => $last_id,
		    'limit' => 1000
			);
			$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
			if (!empty($response['result']['items'])) {
				$stocks = array_merge($stocks, $response['result']['items']);
			}
			$last_id = $response['result']['last_id'];
		} while (!empty($response['result']['items']));
		return $stocks;
	}

	// Обновить цены из Озон
	private function infoPriceUpdate()
	{
		$prices = $this->infoPrice();
		if (!empty($prices)) {
			$this->load->model('module/ozon_seller');
			foreach ($prices as $price) {
				$komission = $price['price']['price'] * $price['commissions']['sales_percent'] / 100;
				$fbs = $komission + $price['commissions']['fbs_first_mile_max_amount'] + $price['commissions']['fbs_direct_flow_trans_max_amount'] + $price['commissions']['fbs_deliv_to_customer_amount'];
				$fbo = $komission + $price['commissions']['fbo_deliv_to_customer_amount'] + $price['commissions']['fbo_direct_flow_trans_max_amount'];
				$price_oz = (float)$price['price']['price'];
				$komission_fbo = (float)$fbo;
				$komission_fbs = (float)$fbs;
				$this->model_module_ozon_seller->updateInfoPrice($price_oz, $komission_fbo, $komission_fbs, $price['offer_id']);
			}
			echo '<br />[PRICE INFO] OK<br />';
		}
	}

	// Получить цены Озон
	private function infoPrice()
	{
		$url = 'https://api-seller.ozon.ru/v4/product/info/prices';
		$last_id = '';
		$prices = array();
		do {
			$data = array(
				'filter' => array(
					'visibility' => 'VISIBLE'
		    ),
		    'last_id' => $last_id,
		    'limit' => 1000
			);
			$response = $this->makeRequest($url, $request = 'POST', $data, $api = 'ozon');
			if (!empty($response['result']['items'])) {
				$prices = array_merge($prices, $response['result']['items']);
				$last_id = $response['result']['last_id'];
			}
		} while (count($response['result']['items']) >= 1000);
		return $prices;
	}

	private function makeRequest($url, $request, $data, $api)
	{
		ini_set('serialize_precision', -1); //патч бага php7.3
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($api == 'ozon') {
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Host: api-seller.ozon.ru',
				'Client-Id: ' . $this->config->get('ozon_seller_client_id'),
				'Api-Key: ' . $this->config->get('ozon_seller_api_key')
			));
		} else {
			$login = $this->config->get('ozon_seller_login_ms');
	    $pass = $this->config->get('ozon_seller_key_ms');
	    curl_setopt($ch, CURLOPT_USERPWD, $login . ":" . $pass);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	      	'Content-Type: application/json'
	    ));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		switch (mb_strtoupper($request)){
			case "GET":
				break;
			case "PUT":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				$data = json_encode($data);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
			case "POST":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				if (!empty($data)) {
					$data = json_encode($data);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				}
				break;
			case "DELETE":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
		}
		$response = curl_exec($ch);
		if (!curl_errno($ch)) {
		  switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
		    case 200:
		      break;
		    default:
		      echo "\n", 'Неожиданный код HTTP: ', $http_code, "\n";
		  }
		}
		curl_close($ch);
		$response = @json_decode($response, true);
		return $response;
	}

	// CDL
	private function cdlRequest($data, $request)
	{
		$url = 'https://shop.cdl-it.ru/index.php?route=extension/module/cdl_request_ozon/pass&domain=' . HTTPS_SERVER . '&ver=2.1&request=' . $request;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, true);
		$data = json_encode($data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($ch);
		curl_close($ch);
	}

	/* Этикетка */
	private function request2($url, $data) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Host: api-seller.ozon.ru',
			'Client-Id: ' . $this->config->get('ozon_seller_client_id'),
			'Api-Key: ' . $this->config->get('ozon_seller_api_key')
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		$data = json_encode($data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}


	/**
	* Писать в журнал ошибки и сообщения
	* @param str $msg запись
	* @param int $level приоритет ошибки/сообщения. Если приоритет больше $this->LOG_LEVEL, то он записан не будет
	**/
	private function log($msg, $level = 0) {
		if ($level > $this->LOG_LEVEL) return;
		$fp = fopen(DIR_LOGS.'ozon_seller.log', 'a');
		fwrite($fp, date('Y-m-d H:i:s').': '.str_replace("\n", '', $msg)."\n");
		if ($this->ECHO) echo nl2br(htmlspecialchars($msg))."<br/>\n";
		fclose($fp);
	}

	private function log_process($msg) {
		$fp = fopen(DIR_SYSTEM . 'ozon_seller_process.txt', 'w+');
		fwrite($fp, str_replace("\n", '', $msg)."\n");
		if ($this->ECHO) echo nl2br(htmlspecialchars($msg))."<br/>\n";
		fclose($fp);
	}

	// update240 ++
	public function update240()
	{
		$this->load->model('module/ozon_seller');
		$colomns = $this->model_module_ozon_seller->checkColomnProduct();
		foreach ($colomns as $colomn) {
			if ($colomn['COLUMN_NAME'] == 'stock_fbo') {
				$check = true;
			}
		}
		if (!isset($check)) {
			$this->model_module_ozon_seller->addStockColomn();
			echo 'update 240 success';
		} else {
			echo 'no update required';
		}
	}
	// update240 --
}
