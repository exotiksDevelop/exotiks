<?php
/*
* OpenCart 2.1
* @author Dmitri Cheremisin
*/
class ControllerModuleCdlWildberriesOrder extends Controller
{
  private $api = 'https://suppliers-api.wildberries.ru/api/';
  private $api_ms = 'https://online.moysklad.ru/api/remap/1.2/';

  // Проверка пароля
  public function pass()
  {
    if (isset($this->request->post['pass']) && $this->request->post['pass'] == $this->config->get('cdl_wildberries_pass')) {
      switch ($this->request->get['request']) {
        case 'packorders':
          if (!empty($this->request->post['selected'])) {
            $this->packOrders($this->request->post['selected']);
          }
          break;
        default:
          false;
          break;
      }
    }
    if (isset($this->request->get['pass']) && $this->request->get['pass'] == $this->config->get('cdl_wildberries_pass')) {
      switch ($this->request->get['request']) {
        case 'orders':
          $this->orders();
          break;
        case 'deleteorder':
          $this->deleteorder($this->request->get['posting']);
          break;
        case 'createfbo':
          $orders = json_decode(file_get_contents("php://input"), true);
          $this->createFbo($orders);
          break;
        case 'returnsfbo':
          $orders = json_decode(file_get_contents("php://input"), true);
          $return = $this->returnsFbo($orders);
          echo $return;
          break;
        default:
          false;
          break;
      }
    }
  }

  // Работа с заказами
  private function orders()
  {
    // получение новых заказов и добавление их в БД
    $this->newOrder();
    // создание заказа в OC
    if ($this->config->get('cdl_wildberries_order_oc')) {
      $this->creatOrderOc();
    }
    // создание заказов в МС у которых нет guid
    if ($this->config->get('cdl_wildberries_ms_status')) {
      $this->creatOrderMs();
    }
    // смена статуса у заказов в БД, что на упаковке в ВБ
    $this->chekPacking();
    // смена статуса у заказа в МС к сборке
    if ($this->config->get('cdl_wildberries_ms_status')) {
      $this->packingStatusOrderMs();
    }
    // трекинг
    $this->tracking($status = 0);
    $this->tracking($status = 1);
    $this->tracking($status = 2);
    $this->tracking($status = 3);
    // интеграция с модулем администрирования
    // if (!empty($this->config->get('packing_order_version'))) {
    //   $this->load->model('module/packing_order');
    //   $this->model_module_packing_order->saveNewOrderWb();
    // }
  }

  // Создать FBO заказы
  private function createFbo($orders)
  {
    $this->load->model('module/cdl_wildberries');
    foreach ($orders as $order) {
      $wb_order_id = $order['wb_order_id'];
      $date_created = $order['shipment_date'];
      $shipment_date = $order['shipment_date'];
      $phone = $order['wb_order_id'];
      $barcode = $order['barcode'];
      $total_price = $order['total_price'];
      $rid = $order['odid'];
      $address = $order['address'];
      if (!empty($order['fio'])) {
        $fio = $order['fio'];
      } else {
        $fio = 'Wildberries' . ' ' . 'Wildberries';
      }
      $this->model_module_cdl_wildberries->saveNewOrder($wb_order_id, $store_id = '', $date_created, $shipment_date, $user_id = '', $phone, $fio, $barcode, $status = '99', $total_price, $address, $rid);
      if ($this->config->get('cdl_wildberries_order_oc')) {
        $this->addOrder($order);
      }
      if ($this->config->get('cdl_wildberries_ms_status')) {
        $this->creatOrderFboMs($order);
      }
    }
  }

  // Создать FBO заказы в МС
  public function creatOrderFboMs($order)
  {
    $this->load->model('module/cdl_wildberries');
    $date = new DateTime(date('Y-m-d H:i:s', strtotime($order['shipment_date'])));
    $shipping_date = $date->format('Y-m-d H:00:00');
    $data_order = array(
			'name' => (string)$order['wb_order_id'],
			'description' => 'FBO',
			'deliveryPlannedMoment' => $shipping_date,
			'organization' => array(
				'meta' => array(
					'href' => $this->api_ms . 'entity/organization/' . $this->config->get('cdl_wildberries_organization_ms'),
					'type' => 'organization',
					'mediaType' => 'application/json'
				)
			),
			'agent' => array(
				'meta' => array(
					'href' => $this->api_ms . 'entity/counterparty/' . $this->config->get('cdl_wildberries_agent_ms'),
					'type' => 'counterparty',
					'mediaType' => 'application/json'
				)
			),
			'state' => array(
				'meta' => array(
   				'href' => $this->api_ms . 'entity/customerorder/metadata/states/' . $this->config->get('cdl_wildberries_status_delivered_ms'),
    			'type' => 'state',
   				'mediaType' => 'application/json'
				)
			),
			'store' => array(
				'meta' => array(
      		'href' => $this->api_ms . 'entity/store/' . $this->config->get('cdl_wildberries_return_store_ms'),
      		'type' => 'store',
      		'mediaType' => 'application/json'
				)
			),
			'project' => array(
				'meta' => array(
    			'href' => $this->api_ms . 'entity/project/' . $this->config->get('cdl_wildberries_project_ms'),
    			'type' => 'project',
    			'mediaType' => 'application/json'
  			)
	  	)
		);
    $product = $this->model_module_cdl_wildberries->getProductByBarcode($order['barcode']);
    $article = $product[0][$this->config->get('cdl_wildberries_connect_prod_shop')];

    if (empty($article)) {
      $this->log('Заказ ' . $order['wb_order_id'] . ' ошибка: товар с ШК ' . $order['barcode'] . ' не найден в товарах Opencart. Заказ будет перевыгружен.');
      return false;
    }
    $filter = $this->config->get('cdl_wildberries_connect_prod_ms');
    $ms_product = $this->getMsProduct($article, $filter);
    $prod_type = 'product';

    if (empty($ms_product['rows'])) {
      $ms_product = $this->getMsBundle($article, $filter);
      $prod_type = 'bundle';
    }

    if (empty($ms_product['rows'])) {
      $this->log('Задание ' . $order['wb_order_id'] . ' ошибка: товар или комплект ' . urlencode($article) . ' не найден в Мой склад. Заказ будет перевыгружен.');
      return false;
    } else {
      foreach ($ms_product['rows'] as $ms_prod) {
        $prod_url = $ms_prod['meta']['href'];
      }
      $price = $order['total_price'];
      $data_product = array(
        'quantity' => 1,
        'price' => floatval($price),
        'discount' => 0,
        'vat' => 0,
        'assortment' => array(
          'meta' => array(
            'href' => $prod_url,
            'type' => $prod_type,
            'mediaType' => 'application/json'
          )
        ),
        'reserve' => 1
      );
    }

    $url = $this->api_ms . 'entity/customerorder';
    $response = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data_order);

    if (!empty($response['errors'])) {
      $this->log('Задание ' . $order['wb_order_id'] . ' ошибка при создании в МС: ' . json_encode($response['errors'], JSON_UNESCAPED_UNICODE));
    } else {
      $this->model_module_cdl_wildberries->saveGuidOrderMs($order['wb_order_id'], $response['id']);
      $url = $this->api_ms . 'entity/customerorder/' . $response['id'] . '/positions';
      $respons = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data_product);

      if (!empty($respons['errors'])) {
        $this->log('Задание ' . $order['wb_order_id'] . ' ' . $url . ' ошибка: '. json_encode($respons['errors'], JSON_UNESCAPED_UNICODE));
      } else {
        $this->log('FBO заказ ' . $order['wb_order_id'] . ' успешно создан в МС');
        $this->addDemond($response['id']);
      }
    }
  }

  // Создание отгрузки в МС
	private function addDemond($guid)
  {
		//получаем предзаполненную отгрузку на основе заказа
		$data = array(
			'customerOrder' => array(
				'meta' => array(
					'href' => $this->api_ms . 'entity/customerorder/' . $guid,
					'metadataHref' => $this->api_ms . 'entity/customerorder/metadata',
					'type' => "customerorder",
					'mediaType' => "application/json"
				)
			)
		);
		$url = $this->api_ms . 'entity/demand/new';
		$response = $this->makeRequest($url, $request = 'PUT', $api = 'ms', $data);
		if (!empty($response['errors'])) {
			$this->log('Ошибка при получении предзаполненной отгрузки из МС: ' . $response['errors']);
		} else {
			$url = $this->api_ms . 'entity/demand';
			$data = $response;
			$finish_response = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data);
  		if (!empty($finish_response['errors'])) {
  			foreach ($finish_response['errors'] as $errors) {
  				$error = $errors['error'];
  			}
  			$this->log('Ошибка при создании отгрузки в МС: ' . $error);
  		} else {
  			$this->log('Отгрузка FBO заказа в МС успешно создана');
  		}
    }
	}

  // Обработка новых заказов
  private function newOrder()
  {
    header('Content-Type: text/html; charset=utf-8');
    echo '=== ' . $this->config->get('cdl_wildberries_version') . ' ===<br />';
    echo '=== Новые задания ===<br />';
    $this->load->model('module/cdl_wildberries');
    $warehouses = $this->config->get('cdl_wildberries_warehouses');
    $orders = $this->getNewOrder();
    if (!empty($orders)) {
      foreach ($orders as $order) {
        $wb_order_id = $order['orderId'];
        $barcode = $order['barcode'];
        $check_barcode = $this->model_module_cdl_wildberries->getProductByBarcode($barcode);
        if (empty($check_barcode)) {
          echo 'Задание ' . $wb_order_id . ' пропущенно, т.к товар с ШК ' . $barcode . ' не найден в таблице Наши товары<br />';
          continue;
        }
        $db_order = $this->model_module_cdl_wildberries->getOrder($wb_order_id);
        if (!empty($db_order)) {
          echo 'Задание №' . $wb_order_id . ' уже есть в базе<br />';
          continue;
        } else {
          $store_id = $order['storeId'];
          $date_created = $order['dateCreated'];
          $fio = '';
          if (!empty($order['userInfo']['fio'])) {
            $fio .= $order['userInfo']['fio'];
          } else {
            if (!empty($this->config->get('cdl_wildberries_name_customer'))) {
              $fio .= $this->config->get('cdl_wildberries_name_customer') . ' ';
            } else {
              $fio .= 'Wildberries ';
            }
            if (!empty($this->config->get('cdl_wildberries_lastname_customer'))) {
              $fio .= $this->config->get('cdl_wildberries_lastname_customer');
            } else {
              $fio .= $wb_order_id;
            }
          }
      		if (!empty($order['userInfo']['phone'])) {
            $phone = $order['userInfo']['phone'];
          } else {
            $phone = $wb_order_id;
          }
          if (!empty($order['userInfo']['userId'])) {
            $user_id = $order['userInfo']['userId'];
          } else {
            $user_id = '';
          }
          $status = $order['status'];
          $total_price = $order['convertedPrice'];
          if (!empty($order['deliveryAddress'])) {
            $address = $order['deliveryAddress'];
          } elseif (empty($order['deliveryAddress'])) {
            $address = $order['officeAddress'];
          } else {
            $address = '';
          }
          $rid = $order['rid'];
          foreach ($warehouses as $warehous) {
            if ($warehous['sklad_id'] == $store_id) {
              $time = $warehous['time'];
              $date = new DateTime(date('Y-m-d H:i:s', strtotime($date_created)));
              $date = $date->modify('+' . $time . 'hours');
              $shipment_date = $date->format('Y-m-d H:00:00');
              break;
            }
          }

          $this->model_module_cdl_wildberries->saveNewOrder($wb_order_id, $store_id, $date_created, $shipment_date, $user_id, $phone, $fio, $barcode, $status, $total_price, $address, $rid);
          echo 'Задание №' . $wb_order_id . ' добавлено<br />';
        }
      }
    } else {
      echo 'Нет новых заданий<br />';
    }
  }

  // Получение списка новых заказов
  private function getNewOrder()
  {
    $date = new DateTime();
		$date->modify('-3 day');
    $skip = 0;
    $orders = array();
    do {
      $url = $this->api . 'v2/orders?date_start=' . urlencode($date->format(DATE_ATOM)) . '&date_end=' . urlencode(date(DATE_ATOM)) . '&status=0&take=1000&skip=' . $skip;
      $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');

      if (!empty($response['orders'])) {
        foreach ($response['orders'] as $order) {
          $orders[] = $order;
        }
      }
      if (!empty($response['error'])) {
        return $response['errorText'];
      }
      $skip += 1000;
    } while ($response['total'] == 1000);
    return $orders;
  }

  // Проверка заказов на сборке в WB
  private function chekPacking()
  {
    $this->load->model('module/cdl_wildberries');
    $status = 1;
    $orders_wb = $this->getOrdersByStatus($status, $day = 5);
    foreach ($orders_wb as $order_wb) {
      $order_db = $this->model_module_cdl_wildberries->getOrderLite($order_wb['orderId']);
      if (empty($order_db[0]['status'])) {
        $this->model_module_cdl_wildberries->changeOrderStatus($order_wb['orderId'], $status);
        if ($this->config->get('cdl_wildberries_order_oc') && !empty($order_db[0]['order_id'])) {
          $this->changeOrderStatusOC($order_db[0]['order_id'], $this->config->get('cdl_wildberries_awaiting_packaging_oc'));
        }
        echo 'Задание №' . $order_wb['orderId'] . ' статус изменен на "На сборке"<br />';
      }
    }
  }

  // Трекинг заказов FBS
  private function tracking($status)
  {
    $this->load->model('module/cdl_wildberries');
    $orders_db = $this->model_module_cdl_wildberries->getTrackingOrders();
    if (!empty($orders_db)) {
      $orders_wb = $this->getOrdersByStatus($status, $day = 100);
      $data_orders = array();
      $return_orders = array();
      foreach ($orders_db as $key => $order_db) {
        foreach ($orders_wb as $order_wb) {
          if ($order_wb['orderId'] == $order_db['wb_order_id']) {

            // Отмена клиента
            if ($order_wb['userStatus'] == 1) {
              $data_orders[$key]['user_status'] = 1;
              $data_orders[$key]['wb_order_id'] = $order_db['wb_order_id'];
              if ($this->config->get('cdl_wildberries_ms_status')) {
                $data_orders[$key]['ms'] = array(
                  'guid' => $order_db['guid'],
                  'status_ms' => $this->config->get('cdl_wildberries_status_cancelled_ms'),
                );
              }
              if ($this->config->get('cdl_wildberries_order_oc')) {
                $data_orders[$key]['oc'] = array(
                  'status' => $this->config->get('cdl_wildberries_cancelled_oc'),
                  'order_id' => $order_db['order_id']
                );
              }
              echo $order_db['wb_order_id'] . ' отменен клиентом<br />';
              // если есть отгрузка на ВБ, то возврат в МС
              if (!empty($order_db['supplie'])) {
                if (!empty($this->config->get('cdl_wildberries_auto_return'))) {
                  $return_orders[] = $order_db['guid'];
                }
              }

            // Доставлен
            } elseif ($order_wb['userStatus'] == 2 && $order_db['user_status'] != 2) {
              $data_orders[$key]['user_status'] = 2;
              $data_orders[$key]['wb_order_id'] = $order_db['wb_order_id'];
              if ($this->config->get('cdl_wildberries_ms_status')) {
                $data_orders[$key]['ms'] = array(
                  'guid' => $order_db['guid'],
                  'status_ms' => $this->config->get('cdl_wildberries_status_delivered_ms'),
                );
              }
              if ($this->config->get('cdl_wildberries_order_oc')) {
                $data_orders[$key]['oc'] = array(
                  'status' => $this->config->get('cdl_wildberries_delevered_oc'),
                  'order_id' => $order_db['order_id']
                );
              }
              echo $order_db['wb_order_id'] . ' доставлен<br />';

            // Возврат
            } elseif ($order_wb['userStatus'] == 3) {
              $data_orders[$key]['user_status'] = 3;
              $data_orders[$key]['wb_order_id'] = $order_db['wb_order_id'];
              if ($this->config->get('cdl_wildberries_ms_status')) {
                $data_orders[$key]['ms'] = array(
                  'guid' => $order_db['guid'],
                  'status_ms' => $this->config->get('cdl_wildberries_status_return_ms'),
                );
                if (!empty($this->config->get('cdl_wildberries_auto_return'))) {
                  $return_orders[] = $order_db['guid'];
                }
              }
              if ($this->config->get('cdl_wildberries_order_oc')) {
                $data_orders[$key]['oc'] = array(
                  'status' => $this->config->get('cdl_wildberries_return_oc'),
                  'order_id' => $order_db['order_id']
                );
              }
              if ($order_wb['status'] == 3) {
                $this->model_module_cdl_wildberries->changeOrderStatus($order_db['wb_order_id'], $order_wb['status']);
              }
              echo $order_db['wb_order_id'] . ' возврат<br />';

            // Ожидает
            } elseif ($order_wb['userStatus'] == 4 && $order_db['user_status'] != 4) {
              $this->model_module_cdl_wildberries->changeOrderUserStatus($order_db['wb_order_id'], $order_wb['userStatus']);
              echo $order_db['wb_order_id'] . ' в статусе ожидает<br />';

            // Брак
            } elseif ($order_wb['userStatus'] == 5) {
              $data_orders[$key]['user_status'] = 5;
              $data_orders[$key]['wb_order_id'] = $order_db['wb_order_id'];
              if ($this->config->get('cdl_wildberries_ms_status')) {
                $data_orders[$key]['ms'] = array(
                  'guid' => $order_db['guid'],
                  'status_ms' => $this->config->get('cdl_wildberries_status_return_ms'),
                );
              }
              if ($this->config->get('cdl_wildberries_order_oc')) {
                $data_orders[$key]['oc'] = array(
                  'status' => $this->config->get('cdl_wildberries_return_oc'),
                  'order_id' => $order_db['order_id']
                );
              }
              echo $order_db['wb_order_id'] . ' в статусе брак<br />';
            }
          }
        }
      }

      if ($this->config->get('cdl_wildberries_ms_status') && !empty($data_orders)) {
        $chunks = array_chunk($data_orders, 999);
        foreach ($chunks as $chunk) {
          $change_status_ms = $this->changeStatusOrdersMs($chunk);
          if (!empty($change_status_ms)) {
            foreach ($chunk as $chun) {
              if ($this->config->get('cdl_wildberries_order_oc')) {
                if (!empty($chun['oc']['order_id'])) {
                  $this->changeOrderStatusOC($chun['oc']['order_id'], $chun['oc']['status']);
                }
              }
              $this->model_module_cdl_wildberries->changeOrderUserStatus($chun['wb_order_id'], $chun['user_status']);
            }
          }
        }
        // Обработаем возвраты в МС
        if (!empty($return_orders)) {
          $this->createReturnMs($return_orders);
        }
      } elseif (!empty($data_orders)) {
        foreach ($data_orders as $data_order) {
          if ($this->config->get('cdl_wildberries_order_oc')) {
            if (!empty($data_order['oc']['order_id'])) {
              $this->changeOrderStatusOC($data_order['oc']['order_id'], $data_order['oc']['status']);
            }
          }
          $this->model_module_cdl_wildberries->changeOrderUserStatus($data_order['wb_order_id'], $data_order['user_status']);
        }
      }
    }
  }

  // Получить заказ в WB по статусу
  private function getOrdersByStatus($status, $day)
  {
    $date = new DateTime();
		$date->modify('-' . $day . ' day');
    $skip = 0;
    $orders = array();
    do {
      $url = $this->api . 'v2/orders?date_start=' . urlencode($date->format(DATE_ATOM)) . '&date_end=' . urlencode(date(DATE_ATOM)) . '&status=' . $status . '&take=1000&skip=' . $skip;
      $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');

      if (!empty($response['orders'])) {
        foreach ($response['orders'] as $order) {
          $orders[] = $order;
        }
      }
      if (!empty($response['error'])) {
        return $response['errorText'];
      }
      $skip += 1000;
    } while ($response['total'] > $skip);
    return $orders;
  }

  // Поставить заказ к сборке
	private function packOrder($wb_order_id)
  {
    $url = $this->api . 'v2/orders';
    $data = array(array('orderId' => $wb_order_id, 'status' => (int)1));
		$response = $this->makeRequest($url, $request = 'PUT', $api = 'wb', $data);
    if (!empty($response['error'])) {
      $this->log('Задание ' . $wb_order_id . ' ошибка постановки к сборке: ' . $response['errorText']);
      echo $response['errorText'];
    }
    echo 'OK';
	}

  // Поставить заказы к сборке массово
	private function packOrders($wb_orders)
  {
		$this->load->model('module/cdl_wildberries');
    $url = $this->api . 'v2/orders';
    $data_orders = array();
    foreach ($wb_orders as $wb_order) {
      $data_orders[] = array(
        'orderId' => $wb_order,
        'status' => (int)1
      );
    }
    $data = $data_orders;
		$response = $this->makeRequest($url, $request = 'PUT', $api = 'wb', $data);
    if (!empty($response['error'])) {
      $this->log('Ошибка постановки к сборке: ' . $response['errorText']);
      echo $response['errorText'];
    } else {
      echo 'OK';
    }
	}

  // Создать заказ в МС
  private function creatOrderMs()
  {
    $this->load->model('module/cdl_wildberries');
    $orders = $this->model_module_cdl_wildberries->getEmptyGuidOrders();
    if (!empty($orders)) {
      foreach ($orders as $order) {
        if ($order['status'] != 99) {
          $this->creatFbsOrderMs($order);
        }
      }
    }
  }

  // Подготовка данных к созданию заказа в МС
  private function creatFbsOrderMs($order)
  {
    $this->load->model('module/cdl_wildberries');
    $warehouses = $this->config->get('cdl_wildberries_warehouses');
    foreach ($warehouses as $warehous) {
      if ($warehous['sklad_id'] == $order['store_id']) {
        $time = $warehous['time'];
        break;
      }
    }

    $date = new DateTime(date('Y-m-d H:i:s', strtotime($order['date_created'])));
    $date = $date->modify('+' . $time . 'hours');
    $shipping_date = $date->format('Y-m-d H:00:00');
    $data_order = array(
			'name' => $order['wb_order_id'],
			'description' => 'Крайнее время отправки: ' . $date->format('H:i d-m-Y') . "\n" . $order['fio'] . "\n" . $order['phone'] . "\n" . $order['address'],
			'deliveryPlannedMoment' => $shipping_date,
			'organization' => array(
				'meta' => array(
					'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/organization/' . $this->config->get('cdl_wildberries_organization_ms'),
					'type' => 'organization',
					'mediaType' => 'application/json'
				)
			),
			'agent' => array(
				'meta' => array(
					'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/counterparty/' . $this->config->get('cdl_wildberries_agent_ms'),
					'type' => 'counterparty',
					'mediaType' => 'application/json'
				)
			),
			'state' => array(
				'meta' => array(
   				'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/' . $this->config->get('cdl_wildberries_status_new_order_ms'),
    			'type' => 'state',
   				'mediaType' => 'application/json'
				)
			),
			'store' => array(
				'meta' => array(
      		'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/store/' . $this->config->get('cdl_wildberries_store_ms'),
      		'type' => 'store',
      		'mediaType' => 'application/json'
				)
			),
			'project' => array(
				'meta' => array(
    			'href' =>  'https://online.moysklad.ru/api/remap/1.2/entity/project/' . $this->config->get('cdl_wildberries_project_ms'),
    			'type' => 'project',
    			'mediaType' => 'application/json'
  			)
	  	),
	  	'attributes' => array(
	  		array(
	    		'meta' => array(
	      		'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/' . $this->config->get('cdl_wildberries_sticker_id_ms'),
	      		'type' => 'attributemetadata',
	      		'mediaType' => 'application/json'
	    		),
	      	'value' => HTTPS_SERVER . 'index.php?route=module/cdl_wildberries_order/printsticker&order=' . $order['wb_order_id']
				)
	  	)
		);
    $product = $this->model_module_cdl_wildberries->getProductByBarcode($order['barcode']);
    $article = $product[0][$this->config->get('cdl_wildberries_connect_prod_shop')];

    if (empty($article)) {
      $this->log('Заказ ' . $order['wb_order_id'] . ' ошибка: товар с ШК ' . $order['barcode'] . ' не найден в товарах Opencart. Заказ будет перевыгружен.');
      return false;
    }
    $filter = $this->config->get('cdl_wildberries_connect_prod_ms');
    $ms_product = $this->getMsProduct($article, $filter);
    $prod_type = 'product';

    if (empty($ms_product['rows'])) {
      $ms_product = $this->getMsBundle($article, $filter);
      $prod_type = 'bundle';
    }

    if (empty($ms_product['rows'])) {
      $this->log('Задание ' . $order['wb_order_id'] . ' ошибка: товар или комплект ' . urlencode($article) . ' не найден в Мой склад. Заказ будет перевыгружен.');
      return false;
    } else {
      foreach ($ms_product['rows'] as $ms_prod) {
        $prod_url = $ms_prod['meta']['href'];
      }
      $price = $order['total_price'];
      $data_product = array(
        'quantity' => 1,
        'price' => floatval($price),
        'discount' => 0,
        'vat' => 0,
        'assortment' => array(
          'meta' => array(
            'href' => $prod_url,
            'type' => $prod_type,
            'mediaType' => 'application/json'
          )
        ),
        'reserve' => 1
      );
    }

    $url = $this->api_ms . 'entity/customerorder';
    $response = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data_order);

    if (!empty($response['errors'])) {
      $this->log('Задание ' . $order['wb_order_id'] . ' ошибка при создании: ' . json_encode($response['errors'], JSON_UNESCAPED_UNICODE));
    } else {
      $this->model_module_cdl_wildberries->saveGuidOrderMs($order['wb_order_id'], $response['id']);
      $url = $this->api_ms . 'entity/customerorder/' . $response['id'] . '/positions';
      $respons = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data_product);

      if (!empty($respons['errors'])) {
        $this->log('Задание ' . $order['wb_order_id'] . ' ' . $url . ' ошибка: '. json_encode($respons['errors'], JSON_UNESCAPED_UNICODE));
      } else {
        echo 'Задание №' . $order['wb_order_id'] . ' успешно создано в МС <br />';
      }
    }
  }

  // Получить товар в МС по артикулу
	private function getMsProduct($article, $filter)
  {
		$url = $this->api_ms . 'entity/product?filter=' . $filter . '=' . urlencode($article) . ';archived=false';
		$response = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');
		return $response;
	}

  // Получить комплект в МС по артикулу
	private function getMsBundle($article, $filter)
  {
		$url = $this->api_ms . 'entity/bundle?filter=' . $filter . '=' . urlencode($article) . ';archived=false';
		$response = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');
		return $response;
	}

  // Принимаем webhook
	public function webhookInput()
  {
		if (isset($this->request->post)) {
			$inputJSON = file_get_contents('php://input');
			$hook = json_decode($inputJSON, true);

			if (array_key_exists('events', $hook)) {
        $this->load->model('module/cdl_wildberries');
				foreach ($hook['events'] as $meta) {
					$url = $meta['meta']['href'];//url отгрузки в МС
					$response = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');
					//проверяем принадлежность отгрузки к WB
					if ($response['agent']['meta']['href'] == $this->api_ms . 'entity/counterparty/' . $this->config->get('cdl_wildberries_agent_ms')) {
            $guid = str_replace($this->api_ms . 'entity/customerorder/', '', $response['customerOrder']['meta']['href']);
            $order_db = $this->model_module_cdl_wildberries->getOrdersByGuid($guid);
            if ($order_db[0]['status'] == 99) {
              continue;
            }
						$url = $response['customerOrder']['meta']['href'];
						$respon = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');
						//отправляем заказ на сборку в WB
						$resp = $this->packOrder($respon['name']);
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

  // Смена статуса заказа в МС на к сборке массово
  private function packingStatusOrderMs()
  {
    $this->load->model('module/cdl_wildberries');
    $id = $this->config->get('cdl_wildberries_status_new_order_ms');
    $orders_ms = $this->filterOrderMsByStatus($id);
    $data = array();
    if (!empty($orders_ms)) {
      foreach ($orders_ms as $order_ms) {
        $order_db = $this->model_module_cdl_wildberries->getOrderLite($order_ms['name']);
        if (!empty($order_db) && $order_db[0]['status'] == 1) {
          $data[] = array(
            'meta'=> array(
              'href' => $this->api_ms . 'entity/customerorder/' . $order_ms['id'],
              'type' => 'customerorder',
              'mediaType' => 'application/json'
            ),
            'state' => array(
              'meta' => array(
                'href' => $this->api_ms . 'entity/customerorder/metadata/states/' . $this->config->get('cdl_wildberries_status_packing_ms'),
                'type' => 'state',
                'mediaType' => 'application/json'
            ))
          );
        }
      }
    }
    if (!empty($data)) {
      $response = $this->changeOrdersMs($data);
      if (!empty($response['errors'])) {
        $this->log('Ошибка массового обновления статуса к сборке в МС: '. json_encode($response['errors'], JSON_UNESCAPED_UNICODE));
      } else {
        echo 'Массовое изменение статуса собираемых заказов в МС - успешно<br />';
      }
    }
  }

  // Смена статуса заказа в МС массово
  private function changeStatusOrdersMs($orders)
  {
    $data = array();
    foreach ($orders as $order) {
      $data[] = array(
        'meta'=> array(
          'href' => $this->api_ms . 'entity/customerorder/' . $order['ms']['guid'],
          'type' => 'customerorder',
          'mediaType' => 'application/json'
        ),
        'state' => array(
          'meta' => array(
            'href' => $this->api_ms . 'entity/customerorder/metadata/states/' . $order['ms']['status_ms'],
            'type' => 'state',
            'mediaType' => 'application/json'
        ))
      );
    }
    $response = $this->changeOrdersMs($data);
    if (!empty($response['errors'])) {
      $this->log('Ошибка массового обновления статуса отслеживаемых заказов в МС: '. json_encode($response['errors'], JSON_UNESCAPED_UNICODE));
      return false;
    } else {
      return true;
    }
  }

  // Изменить заказ в МС массово
	private function changeOrdersMs($data) {
		$url = $this->api_ms . 'entity/customerorder';
		$response = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data);
		return $response;
	}

  // Изменить один заказ в МС
	private function changeOrderMs($data) {
		$url = $data['meta']['href'];
		$response = $this->makeRequest($url, $request = 'PUT', $api = 'ms', $data);
		return $response;
	}

  // Фильтр заказов в МС по статусу
  private function filterOrderMsByStatus($status_id)
  {
    $url = $this->api_ms .'entity/customerorder?filter=state=' . $this->api_ms . 'entity/customerorder/metadata/states/' . $status_id . ';isDeleted=false;&filter=agent=' . $this->api_ms . 'entity/counterparty/' . $this->config->get('cdl_wildberries_agent_ms');
    $response = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');
    if (!empty($response['rows'])) {
      return $response['rows'];
    } else {
      return false;
    }
  }

  // Подготовка данных к изменению заказа в МС после печати этикетки
  private function changeOrdersMsAfterPrint($wb_order_id)
  {
    $this->load->model('module/cdl_wildberries');
    $order_db = $this->model_module_cdl_wildberries->getOrder($wb_order_id);
    if (!empty($order_db[0]['sticker'])) {
      $warehouses = $this->config->get('cdl_wildberries_warehouses');
      foreach ($warehouses as $warehous) {
        if ($warehous['sklad_id'] == $order_db[0]['store_id']) {
          $time = $warehous['time'];
          break;
        }
      }
      $date = new DateTime(date('Y-m-d H:i:s', strtotime($order_db[0]['date_created'])));
      $date = $date->modify('+' . $time . 'hours');

      $data = array(
        'meta'=> array(
          'href' => $this->api_ms . 'entity/customerorder/' . $order_db[0]['guid'],
          'type' => 'customerorder',
          'mediaType' => 'application/json'
        ),
        'state' => array(
          'meta' => array(
            'href' => $this->api_ms . 'entity/customerorder/metadata/states/' . $this->config->get('cdl_wildberries_status_print_ms'),
            'type' => 'state',
            'mediaType' => 'application/json'
        )),
        'description' => 'ШК: ' . $order_db[0]['sticker'] . ', ' . $order_db[0]['sticker_bc'] . "\n" . 'Крайнее время отправки: ' . $date->format('H:i d-m-Y') . "\n" . $order_db[0]['fio'] . "\n" . $order_db[0]['phone'] . "\n" . $order_db[0]['address']
      );
      return $data;
    } else {
      echo 'Не могу сменить статус заказа в МС после печати этикетки. Не найден заказ в БД модуля.';
    }
  }

  // Обработка FBO возвратов
  private function returnsFbo($orders)
  {
    $this->load->model('module/cdl_wildberries');
    $return_orders = array();
    $change_status_ms = array();
    foreach ($orders as $order) {
      if ($this->config->get('cdl_wildberries_ms_status')) {
        if (!empty($order['guid'])) {
          $return_orders[] = $order['guid'];
          $change_status_ms[]['ms'] = array('guid' => $order['guid'], 'status_ms' => $this->config->get('cdl_wildberries_status_return_ms'));
        }
      }
      if ($this->config->get('cdl_wildberries_order_oc')) {
        if (!empty($order['order_oc'])) {
          $this->changeOrderStatusOC($order['order_oc'], $this->config->get('cdl_wildberries_return_oc'));
        }
      }
      $this->model_module_cdl_wildberries->changeOrderUserStatus($order['order_id'], 3);
    }
    // Обработаем возвраты в МС
    if ($this->config->get('cdl_wildberries_ms_status') && !empty($return_orders)) {
      $this->createReturnMs($return_orders);
      $this->changeStatusOrdersMs($change_status_ms);
    }
    return count($orders);
  }

  // Создать возвраты в МС
  private function createReturnMs($return_orders)
  {
    foreach ($return_orders as $guid) {
      $order_info_ms = $this->getOrderMoyskald($guid);
      if (count($order_info_ms['demands']) > 1) {
        $this->log('ВНИМАНИЕ: ' . $order_info_ms['name'] . ' обнаружено несколько отгрузок в МС!');
      }
      // получаем предзаполненный возврат
      $demand = $this->getSalesReturnMs($order_info_ms);
      // подставим склад FBO
      $demand['store']['meta']['href'] = 'https://online.moysklad.ru/api/remap/1.2/entity/store/' . $this->config->get('cdl_wildberries_return_store_ms');
      $demand['store']['meta']['uuidHref'] = 'https://online.moysklad.ru/app/#warehouse/edit?id=' . $this->config->get('cdl_wildberries_return_store_ms');
      // создадим возврат
      $created_return = $this->newSalesReturnMs($demand);
      if (!empty($created_return['errors'])) {
        $this->log($order_info_ms['name'] . ' ошибка при создании возврата в МС: '. json_encode($created_return['errors'], JSON_UNESCAPED_UNICODE));
      } else {
        //получим предзаполненный исходящий платеж на основании возврата
        if (!empty($order_info_ms['payments'])) {
          $get_paymentout = $this->getPaymentoutMs($created_return);
          //создаем исходящий платеж
          $created_paymentout = $this->newPaymentoutMs($get_paymentout);
          if (!empty($created_paymentout['errors'])) {
            $this->log($order_info_ms['name'] . ' ошибка при создании исходящего платежа возврата: ' . json_encode($created_paymentout['errors'], JSON_UNESCAPED_UNICODE));
          }
        }
      }
    }
  }

  // Получить заказ в МС
	private function getOrderMoyskald($guid)
  {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid;
		$response = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');
		return $response;
	}

  // Получить предзаполненный возврат на основании отгрузки в МС. Принимается заказ МС
	private function getSalesReturnMs($order)
  {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/salesreturn/new';
		unset($order['demands'][0]['meta']['uuidHref']);
		$data = array('demand' => array('meta' => $order['demands'][0]['meta']));
		$response = $this->makeRequest($url, $request = 'PUT', $api = 'ms', $data);
		return $response;
	}

  // Cоздать возврат в МС. Принимает предзаполненный возврат на основании отгрузки из getSalesReturnMs
	private function newSalesReturnMs($data)
  {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/salesreturn';
		$response = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data);
		return $response;
	}

  // Получить предзаполненный исходящий платеж в МС на основании возврата. Принимает возврат из newSalesReturn
  private function getPaymentoutMs($return)
  {
    $url = 'https://online.moysklad.ru/api/remap/1.2/entity/paymentout/new';
    unset($return['meta']['uuidHref']);
    $meta['meta'] = $return['meta'];
    $data = array('operations' => array($meta));
    $response = $this->makeRequest($url, $request = 'PUT', $api = 'ms', $data);
    return $response;
  }

  // Создать исходящий платеж в МС. Принимает предзаполненный исходящий платеж из getPaymentoutMs
	private function newPaymentoutMs($data)
  {
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/paymentout';
		$response = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data);
		return $response;
	}

  // Напечатать этикетку
	public function printSticker()
  {
		$wb_order_id = $this->request->get['order'];
		$response = $this->getNumberSticker($wb_order_id);
		if ($response['error']) {
      header('Content-Type: text/html; charset=utf-8');
			echo $response['errorText'];
		} else {
      if (empty($response['data'])) {
        $respons = $this->getOrderById($wb_order_id, $day = '- 20 day');
        if (!empty($respons) && $respons['orders'][0]['status'] == 0) {
          $this->rePackOrder($wb_order_id);
          $response = $this->getNumberSticker($wb_order_id);
        }
      }
      if (empty($response['data'][0]['sticker']['wbStickerIdParts']['A'])) {
        header('Content-Type: text/html; charset=utf-8');
        exit('Обновите страницу!');
      }
      $this->load->model('module/cdl_wildberries');
      $sticker = $response['data'][0]['sticker']['wbStickerIdParts']['A'] . ' ' . $response['data'][0]['sticker']['wbStickerIdParts']['B'];
      $sticker_bc = $response['data'][0]['sticker']['wbStickerEncoded'];
      $this->model_module_cdl_wildberries->addStickerOrder($wb_order_id, $sticker, $sticker_bc);

      $response = $this->getPdfSticker($wb_order_id);
      if ($response['error']) {
        header('Content-Type: text/html; charset=utf-8');
  			echo $response['errorText'];
      } else {
        if ($this->config->get('cdl_wildberries_ms_status')) {
          $data_change_ms_after_print = $this->changeOrdersMsAfterPrint($wb_order_id);
          $response_ms = $this->changeOrderMs($data_change_ms_after_print);
          if (!empty($response_ms['errors'])) {
            header('Content-Type: text/html; charset=utf-8');
      			echo 'Не могу сменить статус заказа в МС после печати этикетки. ' . json_encode($response_ms['errors'], JSON_UNESCAPED_UNICODE);
      		} elseif ($response_ms['state']['meta']['href'] == $this->api_ms . 'entity/customerorder/metadata/states/' . $this->config->get('cdl_wildberries_status_print_ms')) {
            $this->model_module_cdl_wildberries->changeOrderStatus($wb_order_id, $status = 2);
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename=' . $wb_order_id . '.pdf');
            echo base64_decode($response['data']['file']);
          }
        } else {
          $this->model_module_cdl_wildberries->changeOrderStatus($wb_order_id, $status = 2);
          header('Content-Type: application/pdf');
          header('Content-Disposition: attachment; filename=' . $wb_order_id . '.pdf');
          echo base64_decode($response['data']['file']);
        }
      }
		}
	}

  // Поставить заказ к сборке повтроно
	private function rePackOrder($wb_order_id)
  {
    $url = $this->api . 'v2/orders';
    $data = array(array('orderId' => $wb_order_id, 'status' => (int)1));
		$response = $this->makeRequest($url, $request = 'PUT', $api = 'wb', $data);
    if (!empty($response['error'])) {
      header('Content-Type: text/html; charset=utf-8');
      $this->log('Задание ' . $wb_order_id . ' ошибка постановки к сборке: ' . $response['errorText']);
      echo $response['errorText'];
    }
	}

  // Получить номер ШК
  private function getNumberSticker($wb_order_id)
  {
    $url =  $this->api .'v2/orders/stickers';
		$data = array(
      'orderIds' => array((int)$wb_order_id),
      'type' => 'code128');
		$response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
    return $response;
  }

  // Получить стикер PDF
  private function getPdfSticker($wb_order_id)
  {
    $url =  $this->api .'v2/orders/stickers/pdf';
		$data = array(
      'orderIds' => array((int)$wb_order_id),
      'type' => 'code128');
		$response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
    return $response;
  }

  // Получить заказ в WB по номеру
  private function getOrderById($wb_order_id, $day)
  {
    $date = new DateTime();
    $date->modify($day);
    $url =  $this->api .'v2/orders?date_start=' . urlencode($date->format(DATE_ATOM)) . '&take=1&skip=0&id=' . $wb_order_id;
    $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = array());
    return $response;
  }

  // Удалить заказ в БД
	private function deleteOrder($wb_order_id)
  {
		$this->load->model('module/cdl_wildberries');
		if ($this->config->get('cdl_wildberries_ms_status')) {
			$order_info = $this->model_module_cdl_wildberries->getOrderGuid($wb_order_id);
			if (!empty($order_info[0]['guid'])) {
				$this->deleteOrderMoyskald($order_info[0]['guid']);
			}
		}
		$this->model_module_cdl_wildberries->deleteOrder($wb_order_id);
	}

  // Удалить заказ в МС по id
	private function deleteOrderMoyskald($guid)
  {
		$url = $this->api_ms . 'entity/customerorder/' . $guid;
		$response = $this->makeRequest($url, $request = 'DELETE', $api = 'ms', $data = '');
		return $response;
	}

  // Создать заказ в OC
  private function creatOrderOc()
  {
    $this->load->model('module/cdl_wildberries');
    $orders = $this->model_module_cdl_wildberries->getOrdersByStatus($status = 0);
    if (!empty($orders)) {
      foreach ($orders as $order) {
        if (empty($order['order_id'])) {
          $this->addOrder($order);
        }
      }
    }
  }

  private function addOrder($order)
  {
    $this->load->model('module/cdl_wildberries');
		$this->load->model('checkout/order');

		$stop = '';
		$totals = array();

		$get_product_id = $this->model_module_cdl_wildberries->getProductByBarcode($order['barcode']);
		$product_id = $get_product_id[0]['product_id'];

		if (empty($product_id)) {
			$this->log($order['wb_order_id'] . ' ошибка: товар с ШК ' . $order['barcode'] . ' не найден в товарах Opencart. Заказ будет перевыгружен.');
			$stop = 'stop';
		}

    $price = $order['total_price'] / 100;

		$product_info = $this->model_module_cdl_wildberries->getProduct($product_id);

    if ($this->config->get('cdl_wildberries_product_price_oc')) {
      if ($product_info['special']) {
        $price = $product_info['special'];
      } else {
        $price = $product_info['price'];
      }
    }

		$order_data['products'][] = array(
			'product_id' => $product_info['product_id'],
			'name'       => $product_info['name'],
			'model'      => $product_info['model'],
			'option'     => array(),
			'download'   => array(),
			'quantity'   => 1,
			//'subtract'   => $product['subtract'],
			'price'      => $price,
			'total'      => $price,
			'tax'        => 0,
			'reward'     => 0
		);
		$subtotal = $price;
		$total = $subtotal;
		$currency = 'RUB';

		$fio = explode(' ', $order['fio']);
		$firstname = $fio[0];
		$lastname = $fio[1];
		$telephone = '+' . $order['phone'];
		$zip_code = '';
		$adress = $order['address'];
		$delivery_method = 'Wildberries';

		$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$order_data['store_id'] = $this->config->get('config_store_id');
		$order_data['store_name'] = $this->config->get('config_name');
		$order_data['store_url'] = ($this->config->get('config_store_id') ? $this->config->get('config_url') : HTTP_SERVER);
		$order_data['customer_id'] = 0;
		$order_data['customer_group_id'] = 1;
		$order_data['firstname'] = $firstname;
		$order_data['lastname'] = $lastname;
		$order_data['email'] = $this->config->get('cdl_wildberries_email_order');
		$order_data['telephone'] = $telephone;
		$order_data['fax'] = '';

		$order_data['payment_firstname'] = $firstname;
		$order_data['payment_lastname'] = $lastname;
		$order_data['payment_company'] = '';
		$order_data['payment_company_id'] = '';
		$order_data['payment_tax_id'] = '';
		$order_data['payment_address_1'] = $adress;
		$order_data['payment_address_2'] = '';
		$order_data['payment_city'] = '';
		$order_data['payment_postcode'] = $zip_code;
		$order_data['payment_country'] = 'Российская Федерация';
		$order_data['payment_country_id'] = 176;
		$order_data['payment_zone'] = '';
		$order_data['payment_zone_id'] = '';
		$order_data['payment_address_format'] = '';
		$order_data['payment_method'] = 'Wildberries';
		$order_data['payment_code'] = 'cod';

		$order_data['shipping_firstname'] = $firstname;
		$order_data['shipping_lastname'] = $lastname;
		$order_data['shipping_company'] = '';
		$order_data['shipping_address_1'] = $adress;
		$order_data['shipping_address_2'] = '';
		$order_data['shipping_city'] = '';
		$order_data['shipping_postcode'] = $zip_code;
		$order_data['shipping_country'] = 'Российская Федерация';
		$order_data['shipping_country_id'] = 176;
		$order_data['shipping_zone'] = '';
		$order_data['shipping_zone_id'] = '';
		$order_data['shipping_address_format'] = '';
		$order_data['shipping_method'] = $delivery_method;
		$order_data['shipping_code'] = '';

		$order_data['comment'] = 'Крайняя дата отгрузки: ' . date('H:i d-m-Y', strtotime($order['shipment_date'])) . '<br />Задание: ' . $order['wb_order_id'];

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
			'value' => 'Wildberries',
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
      $this->model_module_cdl_wildberries->addOrderIdOC($order['wb_order_id'], $order_id);
			$this->changeOrderStatusOC($order_id, $this->config->get('cdl_wildberries_status_new_oc'));
		}
	}

  private function changeOrderStatusOC($order_id, $order_status_id)
	{
		$this->load->model('checkout/order');
		$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', false);
	}

  private function makeRequest($url, $request, $api, $data = array())
  {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($api == 'wb') {
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: ' . $this->config->get('cdl_wildberries_general_token')
			));
		} else {
			$login = $this->config->get('cdl_wildberries_login_ms');
	    $pass = $this->config->get('cdl_wildberries_key_ms');
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
		curl_close($ch);
		$response = @json_decode($response, true);
		return $response;
	}

  // Лог
  private function log($msg) {
		$fp = fopen(DIR_LOGS . 'cdl_wildberries.log', 'a');
		fwrite($fp, date('Y-m-d H:i:s').': '.str_replace("\n", '', $msg)."\n");
		fclose($fp);
	}

	private function log_process($msg) {
		$fp = fopen(DIR_SYSTEM . 'cdl_wildberries_process.txt', 'w+');
		fwrite($fp, str_replace("\n", '', $msg)."\n");
		fclose($fp);
	}
}
