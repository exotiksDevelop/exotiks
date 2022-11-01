<?php
/*
* OpenCart 2.1
* @author Dmitri Cheremisin
*/
class ControllerModuleCdlWildberriesStatistics extends Controller
{
  private $error_request;
  public function index()
  {
    if (isset($this->request->get['pass']) && $this->request->get['pass'] == $this->config->get('cdl_wildberries_pass')) {
      if (empty($this->config->get('cdl_wildberries_api_statistics_key'))) {
        echo 'В настройках модуля не указан Ключ API статистики x64<br />';
      }
      //URL
      $data['url_statistics'] = 'index.php?route=module/cdl_wildberries_statistics&pass=' . $this->config->get('cdl_wildberries_pass');
      $data['url_payment'] = 'index.php?route=module/cdl_wildberries_statistics/payment&pass=' . $this->config->get('cdl_wildberries_pass');
      $data['url_create_fbo'] = 'index.php?route=module/cdl_wildberries_order/pass&request=createfbo&pass=' . $this->config->get('cdl_wildberries_pass');
      $data['url_fbo_retuns'] = 'index.php?route=module/cdl_wildberries_order/pass&request=returnsfbo&pass=' . $this->config->get('cdl_wildberries_pass');
      $data['url_discrepancy'] = 'index.php?route=module/cdl_wildberries_statistics/discrepancy&pass=' . $this->config->get('cdl_wildberries_pass');

      $this->load->model('module/cdl_wildberries');

      if (isset($this->request->post['datefrom']) && isset($this->request->post['dateto'])) {
        $date_from = date('Y-m-d', strtotime($this->request->post['datefrom']));
        $date_to = date('Y-m-d', strtotime($this->request->post['dateto']));
        $data['date_from'] = $this->request->post['datefrom'];
        $data['date_to'] = $this->request->post['dateto'];
        $responses = $this->getReport($date_from, $date_to);
        $button_fbo = false;
        $data['fbo_new_orders'] = array();
        $data['fbs_orders'] = array();
        $data['fbo_orders'] = array();
        $data['fbo_return'] = array();
        $data['rid_orders'] = array();
        $data['discrepancy_orders'] = array();
        $rid_returns = array();
        $rid_no_orders = array();
        $data['sales'] = 0;
        $data['summa'] = 0;
        $data['komissions'] = 0;
        $data['pay'] = 0;
        $data['costs'] = 0;
        $data['returns'] = 0;
        $data['counts'] = 0;
        $data['counts_pay_orders'] = 0;
        $data['count_order_db'] = 0;
        $data['count_no_order_db'] = 0;
        $data['return'] = 0;
        $data['discrepancy_summ'] = 0;
        $data['penalty'] = 0;
        $data['additional_payment'] = 0;
        if (!empty($responses)) {
          $check_stat = false;
          // проверим есть ли заказы без rid
          $check_no_rid_bd = $this->model_module_cdl_wildberries->getOrdersNoRid();
          if (!empty($check_no_rid_bd)) {
            $data['btn_rid'] = '<a href="index.php?route=module/cdl_wildberries_statistics/checkrid&pass=' . $this->config->get('cdl_wildberries_pass') . '" class="btn btn-warning" target="_blank">Проверить RID не найденных заказов</a>';
          }
          foreach ($responses as $response) {
            if (!empty($response['ppvz_for_pay'])) {
              $data['counts_pay_orders']++;
            }
            if ($response['doc_type_name'] == 'Продажа') {
              $data['sales'] += $response['retail_amount'];
            } elseif ($response['doc_type_name'] == 'Возврат') {
              $data['sales'] -= $response['retail_amount'];
              $data['returns'] += $response['retail_amount'];
            }
            if ($response['doc_type_name'] == 'Продажа') {
              $data['summa'] += $response['ppvz_for_pay'];
            } elseif ($response['doc_type_name'] == 'Возврат') {
              $data['summa'] -= $response['ppvz_for_pay'];
            }
            $data['komissions'] += $response['delivery_rub'];
            if (!empty($response['penalty'])) {
              $data['penalty'] += $response['penalty'];
            }
            if (!empty($response['additional_payment'])) {
              $data['additional_payment'] += $response['additional_payment'];
            }
            if (empty($response['ppvz_for_pay'])) {
              continue;
            }

            $order_dt = date('d.m.Y', strtotime($response['order_dt']));
            $db_order = $this->model_module_cdl_wildberries->getOrderByRid($response['rid']);
            if (empty($db_order) && !empty($response['shk_id'])) {
              $shk = substr($response['shk_id'], 0, -4) . ' ' . substr($response['shk_id'], 6);
              $db_order = $this->model_module_cdl_wildberries->getOrderByShk($shk);
            }

            $discrepancy = '';
            $db_order_id = '';
            $status = '';
            $db_price = '';
            $guid = '';
            $stat = false;

            if (!empty($db_order)) {
              $order_id = $db_order[0]['wb_order_id'];
              $db_order_id = $db_order[0]['order_id'];
              $status = $db_order[0]['status'];
              $guid = $db_order[0]['guid'];
              $db_price = $db_order[0]['total_price'] / 100;
              if ($db_order[0]['stat']) {
                $stat = true;
              } else {
                if ($db_order[0]['user_status'] != 3) {
                  $check_stat = true;
                }
              }
              if ($db_order[0]['user_status'] == 3) {
                $rid_returns[] = $response['rid'];
              }
              if ($db_price != $response['retail_amount'] && $response['doc_type_name'] != 'Возврат') {
                $discrepancy = round($db_price - $response['retail_amount'], 2);
                $data['discrepancy_summ'] += $discrepancy;
                $btn_discrepancy = true;
                $data['discrepancy_orders'][] = array(
                  'order_id' => $order_id,
                  'order_oc' => $db_order_id,
                  'retail_amount' => $response['retail_amount'],
                  'discrepancy' => $discrepancy,
                  'guid' => $guid
                );
              }
              $data['count_order_db']++;
            } elseif (empty($db_order) && !empty($response['office_name'])) {
              $order_id = $response['rid'];
              $status = 99;
              $check_stat = true;
              $rid_no_orders[] = $response['rid'];
              $data['count_no_order_db']++;
            } else {
              $order_id = 'Rid заказа не найден';
              $check_stat = true;
              $rid_no_orders[] = $response['rid'];
              $data['count_no_order_db']++;
            }

            if (!empty($db_order) && empty($db_order[0]['user_status']) && $response['doc_type_name'] == 'Возврат') {
              $return = true;
            } else {
              $return = '';
            }

            if ($status != 99) {
              $data['fbs_orders'][] = array(
                'order_id' => $order_id,
                'order_oc' => $db_order_id,
                'order_dt' => $order_dt,
                'stat' => $stat,
                'status' => $status,
                'rrd_id' => $response['rrd_id'],
                'rid' => $response['rid'],
                'doc_type_name' => $response['doc_type_name'],
                'retail_amount' => $response['retail_amount'],
                'ppvz_for_pay' => $response['ppvz_for_pay'],
                'db_price' => $db_price,
                'discrepancy' => $discrepancy,
                'guid' => $guid
              );
            } else {
              $data['fbo_orders'][] = array(
                'order_id' => $order_id,
                'order_dt' => $order_dt,
                'stat' => $stat,
                'status' => $status,
                'rrd_id' => $response['rrd_id'],
                'rid' => $response['rid'],
                'doc_type_name' => $response['doc_type_name'],
                'retail_amount' => $response['retail_amount'],
                'ppvz_for_pay' => $response['ppvz_for_pay'],
                'return' => $return,
                'guid' => $guid,
                'order_oc' => $db_order_id,
                'db_price' => $db_price,
                'discrepancy' => $discrepancy
              );
              if (empty($db_price)) {
                $data['fbo_new_orders'][] = array(
                  'barcode' => $response['barcode'],
                  'wb_order_id' => $response['rid'],
                  'total_price' => $response['retail_amount'] * 100,
                  'fio' => 'FBO FBO',
                  'phone' => $response['rid'],
                  'address' => ' ',
                  'shipment_date' => $response['order_dt'],
                  'odid' => $response['rid']
                );
                $button_fbo = true;
              }
            }

            if ($response['doc_type_name'] == 'Продажа' && $stat == false) {
              $data['rid_orders'][] = array(
                'rid' => $response['rid'],
                'guid' => $guid,
                'stat' => $stat,
                'order_id' => $order_id,
                'db_order_id' => $db_order_id
              );
            } elseif ($response['doc_type_name'] == 'Возврат' && $stat == false) {
              $rid_returns[] = $response['rid'];
              $data['return']++;
            }
          }
          foreach ($data['rid_orders'] as $key => $rid_orders) {
            if (in_array($rid_orders['rid'], $rid_returns)) {
              unset($data['rid_orders'][$key]);
            }
          }
          $data['pay'] = $data['summa'] - $data['komissions'] - $data['penalty'] - $data['additional_payment'];
          $data['costs'] = $data['sales'] - $data['pay'];
          $data['counts'] = $data['sales'] / count($data['rid_orders']);
          $data['counts'] = (int)$data['counts'];

          if (!empty($data['fbo_orders'])) {
            foreach ($data['fbo_orders'] as $fbo_returns) {
              if (!empty($fbo_returns['return'])) {
                $data['fbo_return'][] = array('order_id' => $fbo_returns['order_id'], 'guid' => $fbo_returns['guid'], 'order_oc' => $fbo_returns['order_oc']);
              }
            }
          }
          // кнопка исправить цены в заказах
          if ($btn_discrepancy && empty($button_fbo)) {
            $data['btn_discrepancy'] = '<button type="button" class="btn btn-danger discrepancy">Исправить цены в заказах</button>';
          }
          // кнопка обработать возвраты FBO
          if (!empty($data['fbo_return'])) {
            $data['btn_fbo_return'] = '<button type="button" class="btn btn-danger fbo-return">Обработать FBO возвраты</button>';
          }
          // кнопка создать заказы FBO
          if ($button_fbo) {
            $data['btn_fbo_oc'] = '<button type="button" class="btn btn-success fbo">Создать FBO заказы</button>';
          }
          // кнопка обработать заказы из отчета
          if (!$button_fbo && $check_stat && empty($check_no_rid_bd) && empty($rid_no_orders)) {
            $data['btn_payment'] = '<button type="button" class="btn btn-success payment">Обработать заказы из отчета (' . count($data['rid_orders']) . ')</button>';
          }
          if (empty($button_fbo) && !empty($rid_no_orders)) {
            $data['warning_message'] = 'В отчете есть идентификатор начисления за товар, который не привязан к FBS и FBO заказам в модуле. Обработайте его вручную в своей системе учета.';
            $data['btn_payment'] = '<button type="button" class="btn btn-success payment">Обработать заказы из отчета</button>';
          }
        }
      }
      if (!empty($this->error_request)) {
        $data['warning_message'] = $this->error_request;
      }
      $this->response->setOutput($this->load->view('module/cdl_wildberries_statistics.tpl', $data));
    } else {
      echo 'В настройках модуля не указан Пароль защиты экспорта<br />';
    }
  }

  // Получить отчет
  private function getReport($date_from, $date_to)
  {
    $rrid = 0;
    $data = array();
    do {
      $url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/reportDetailByPeriod?dateFrom=' . $date_from . '&key=' . $this->config->get('cdl_wildberries_api_statistics_key') . '&limit=1000&rrdid=' . $rrid . '&dateto=' . $date_to;
      $res = $this->makeRequest($url, $request = 'GET', $api = 'wb', $dat = '');
      if (!empty($res)) {
        $data =  array_merge($data, $res);
        $rrid = end($res)['rrd_id'];
      }
      sleep(2);
    } while (count($res) == 1000);
    return $data;
  }

  // Проверить rid заказов с начала продажи модуля
  public function checkRid()
  {
    if (isset($this->request->get['pass']) && $this->request->get['pass'] == $this->config->get('cdl_wildberries_pass')) {
      $this->load->model('module/cdl_wildberries');
      $date = new DateTime();
      $skip = 0;
      do {
        $url = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start=2021-11-01T00%3A00%3A01.365%2B03%3A00&date_end=' . urlencode($date->format(DATE_ATOM)) . '&take=1000&skip=' . $skip;
        $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');
        if (!empty($response['orders'])) {
          foreach ($response['orders'] as $order) {
            $db_order = $this->model_module_cdl_wildberries->getOrderByRid($order['rid']);
            if (empty($db_order)) {
              $this->model_module_cdl_wildberries->updateRid($order['orderId'], $order['rid']);
              echo 'Order ' . $order['orderId'] . ' add rid ' . $order['rid'] .  '<br />';
            }
          }
        }
        if (!empty($response['error'])) {
          return $response['errorText'];
        }
        $skip += 1000;
      } while ($response['total'] == 1000);
    }
  }

  // Если заказа нет в БД, но есть в отчете то проверим FBO заказы
  private function getFbo($rid_no_orders)
  {
    $date = new DateTime();
		$date->modify('-' . 100 . ' day');
    $url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/orders?dateFrom=' . urlencode($date->format(DATE_ATOM)) . '&flag=0&key=' . $this->config->get('cdl_wildberries_api_statistics_key');
    $responses = $this->makeRequest($url, $request = 'GET', $api = 'wb', $dat = '');
    $orders = array();
    if (!empty($responses)) {
      foreach ($responses as $order) {
        foreach ($rid_no_orders as $rid) {
          if ($order['odid'] == $rid) {
            $orders[] = $order;
          }
        }
      }
    }
    return $orders;
  }

  public function payment()
  {
    if (isset($this->request->get['pass']) && $this->request->get['pass'] == $this->config->get('cdl_wildberries_pass')) {
      $rids = json_decode(file_get_contents("php://input"), true);
      $msg = '';
      $ms = '';
      if ($this->config->get('cdl_wildberries_order_oc')) {
        $this->ocPayment($rids);
        $msg = 'Статус заказов в OC изменен. ';
      }
      if ($this->config->get('cdl_wildberries_ms_status')) {
        $ms = $this->msPayment($rids);
      }
      echo $msg . $ms;
    }
  }

  // Обработать заказы в МС
  private function msPayment($rids)
  {
    if (!empty($this->request->get['date'])) {
      $date = date('Y-m-d', strtotime($this->request->get['date']));
      $this->load->model('module/cdl_wildberries');
      $guids = array();
      $error = '';
      foreach ($rids as $rid) {
        if (!empty($rid['guid']) && empty($rid['stat'])) {
          $payment = $this->paymentOrder($rid['guid']);
          if (!empty($payment['errors'])) {
            $error .= '<br />Заказ ' . $rid['order_id'] . ' - ' . $payment['errors'][0]['error'];
          } else {
            $guids[] = $rid['guid'];
            $this->model_module_cdl_wildberries->updateStat($rid['order_id']);
          }
        }
      }
      if (!empty($guids)) {
        $this->changePaymentDate($date, $guids);
      }
      $msg = 'Было обработанно ' . count($guids) . ' из ' . count($rids) . '. Дата оплаты у заказов в МС установлена ' . $this->request->get['date'];
      return $msg . $error;
    }
  }

  // Обработать заказы в OC
  private function ocPayment($rids)
  {
    $this->load->model('module/cdl_wildberries');
    foreach ($rids as $rid) {
      if (empty($rid['stat']) && !empty($rid['db_order_id'])) {
        $this->changeOrderStatusOC($rid['db_order_id'], $this->config->get('cdl_wildberries_payment_oc'));
        $this->model_module_cdl_wildberries->updateStat($rid['order_id']);
      }
    }
  }

  // Смена статуса в OC
  private function changeOrderStatusOC($order_id, $order_status_id)
	{
		$this->load->model('checkout/order');
		$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', false);
	}

  // Создать входящий платеж в МС
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
		$response = $this->makeRequest($url, $request = 'PUT', $api = 'ms', $data);
    if (!empty($response['errors'])) {
      return $response;
    }
		$url = 'https://online.moysklad.ru/api/remap/1.2/entity/paymentin';
		$respon = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data = $response);
		return $respon;
	}

  // Изменить дату оплаты в МС
  public function changePaymentDate($date, $guids)
  {
    $data_change = array();
    foreach ($guids as $guid) {
      $data_change[] = array(
        'meta'=> array(
          'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid,
          'type' => 'customerorder',
          'mediaType' => 'application/json'
        ),
        'attributes' => array(array(
          'meta' => array(
            'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/' . $this->config->get('cdl_wildberries_payment_date'),
            'type' => 'attributemetadata',
            'mediaType' => 'application/json'
          ),
        'value' => $date . ' 00:00:00'
        ))
      );
    }
    $this->changeOrdersMs($data_change);
  }

  // Изменить заказ в МС массово
  private function changeOrdersMs($data) {
    $url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder';
    $response = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data);
    return $response;
  }

  // Исправить цены в заказах
  public function discrepancy()
  {
    if (isset($this->request->get['pass']) && $this->request->get['pass'] == $this->config->get('cdl_wildberries_pass')) {
      $orders = json_decode(file_get_contents("php://input"), true);
      $orders_count = 0;
      $oc = 0;
      $ms = 0;
      $db = 0;
      if (!empty($orders)) {
        $this->load->model('module/cdl_wildberries');
        foreach ($orders as $order) {
          $error = false;
          if (!empty($order['discrepancy'])) {
            // изменим в заказе ОС
            if (!empty($order['order_oc'])) {
              $this->model_module_cdl_wildberries->discrepancyOc($order['retail_amount'], $order['order_oc']);
              $this->changeOrderStatusOC($order['order_oc'], $this->config->get('cdl_wildberries_discrepancy_oc'));
              $oc++;
            }
            // изменим в заказе МС
            if (!empty($order['guid'])) {
              $price = $order['retail_amount'] * 100;
              $order_ms = $this->getOrderMs($order['guid']);
              if (!empty($order_ms['positions'])) {
                $url_positions = $order_ms['positions']['meta']['href'];
                $positions = $this->makeRequest($url_positions, $request = 'GET', $api = 'ms');
                $url_position = $positions['rows'][0]['meta']['href'];
                $data = array('price' => $price);
                $resp_pos_order = $this->makeRequest($url_position, $request = 'PUT', $api = 'ms', $data);
                if (!empty($resp_pos_order['errors'])) {
                  $error = true;
                }

                if (!empty($order_ms['demands'])) {
                  $url_demand = $order_ms['demands'][0]['meta']['href'];
                  $demand = $this->makeRequest($url_demand, $request = 'GET', $api = 'ms');
                  if (!empty($demand['positions'])) {
                    $url_demand_positions = $demand['positions']['meta']['href'];
                    $demand = $this->makeRequest($url_demand_positions, $request = 'GET', $api = 'ms');
                    $url_demand_position = $demand['rows'][0]['meta']['href'];
                    $data = array('price' => $price);
                    $resp_pos_demand = $this->makeRequest($url_demand_position, $request = 'PUT', $api = 'ms', $data);
                    if (!empty($resp_pos_demand['errors'])) {
                      $error = true;
                    }
                  }
                }
                if (empty($error)) {
                  $ms++;
                }
              } else {
                echo 'Заказ ' . $order['order_id'] . 'не найден в МС';
              }
            }
            if (empty($error)) {
              $this->model_module_cdl_wildberries->discrepancyModule($price, $order['order_id']);
              $db++;
            }
            $orders_count++;
          }
        }
      }
      $mg = "Исправление цен:\n";
      $mg .= "Всего заказов: " . $orders_count . "\n";
      if (!empty($oc)) {
        $mg .= "Обработал в ОС: " . $oc . "\n";
      }
      if (!empty($ms)) {
        $mg .= "Обработал в МС: " . $ms . "\n";
      }
      if (!empty($db)) {
        $mg .= "Обработал в модуле: " . $db . "\n";
      }
      $mg .= "Заново загрузите отчет";
      echo $mg;
    }
  }

  // Получить заказ в МС
  private function getOrderMs($guid)
  {
    $url = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/' . $guid;
    $response = $this->makeRequest($url, $request = 'GET', $api = 'ms');
    return $response;
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
    if (!curl_errno($ch)) {
      switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
        case 200:
          break;
        case 429:
          $this->error_request = 'Ошибка 429 при получении отчета. Превышен лимит запросов к серверу. Пробуйте до тех пор, пока не пропадет это сообщение!';
          break;
        default:
          $this->error_request = 'Неожиданная ошибка при получении отчета: ' . $http_code;
          break;
      }
    }
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
