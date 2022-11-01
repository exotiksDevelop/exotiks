<?php
/*
* OpenCart 2.1
* @author Dmitri Cheremisin
*/
class ControllerModuleCdlWildberriesSupplies extends Controller {
  private $api = 'https://suppliers-api.wildberries.ru/api/';
  // Проверка пароля Supplies
  public function pass()
  {
    if (isset($this->request->get['pass']) && $this->request->get['pass'] == $this->config->get('cdl_wildberries_pass_supplies')) {
      switch ($this->request->get['request']) {
        case 'create':
          $create = $this->createSupplies();
          if (!empty($create['supplyId'])) {
            echo $create['supplyId'];
          }
          if (!empty($create['error'])) {
            echo $create['error'];
          }
          break;
        case 'add':
          if (!empty($this->request->get['selected']) && !empty($this->request->get['id']) && !empty($this->request->get['supplie_id'])) {
            $this->addSuppliesOrder($this->request->get['selected'], $this->request->get['id']);
            $this->addSupplieDb($this->request->get['selected'], $this->request->get['supplie_id']);
            $this->index();
          }
          break;
        case 'clouse':
          if (!empty($this->request->get['id'])) {
            $this->clouseSupplies($this->request->get['id']);
            $this->index();
          }
          break;
        case 'print':
          if (!empty($this->request->get['id'])) {
            $this->supplies_print($this->request->get['id']);
          }
          break;
        default:
          $this->index();
          break;
      }
    }
  }

  // Получить открытые поставки
  private function getActive()
  {
    $url = $this->api . 'v2/supplies?status=ACTIVE';
    $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');
    return $response;
  }

  // Создать поставку
  private function createSupplies()
  {
    $url = $this->api . 'v2/supplies';
    $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data = '');
    return $response;
  }

  // Закрыть поставку
  private function clouseSupplies($id)
  {
    $url = $this->api . 'v2/supplies/' . $id . '/close';
    $a = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data = '');
  }

  // Печать ШК поставки
  private function supplies_print($id)
  {
    $url = $this->api . 'v2/supplies/' . $id . '/barcode?type=pdf';
    $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');
    if (!empty($response['error'])) {
      header('Content-Type: text/html; charset=utf-8');
      echo $response['errorText'];
    } else {
      $this->supplies_print_output($response);
    }
  }

  private function supplies_print_output($value)
  {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=' . $value['name'] . '.pdf');
    echo base64_decode($value['file']);
  }

  // Получить заказ в WB по статусу
  private function getOrdersByStatus($status = 1, $day = 10)
  {
    $this->load->model('module/cdl_wildberries');
    $date = new DateTime();
		$date->modify('-' . $day . ' day');
    $skip = 0;
    $orders = array();
    do {
      $url = $this->api . 'v2/orders?date_start=' . urlencode($date->format(DATE_ATOM)) . '&date_end=' . urlencode(date(DATE_ATOM)) . '&status=' . $status . '&take=1000&skip=' . $skip;
      $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');

      if (!empty($response['orders'])) {
        foreach ($response['orders'] as $order) {
          if ($order['userStatus'] == 4 || $order['userStatus'] == 0) {
            $db_order = $this->model_module_cdl_wildberries->getOrder($order['orderId']);
            if (!empty($db_order)) {
              $orders[] = $db_order[0];
            }
          }
        }
      }
      if (!empty($response['error'])) {
        return $response['errorText'];
      }
      $skip += 1000;
    } while ($response['total'] == 1000);
    return $orders;
  }

  private function getSuppliesOrders($supplies)
  {
    $url = $this->api . 'v2/supplies/' . $supplies . '/orders';
    $orders = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');
    return $orders;
  }

  private function addSuppliesOrder($orders, $id)
  {
    $url = $this->api . 'v2/supplies/' . $id;
    $data['orders'] = $orders;
    $response = $this->makeRequest($url, $request = 'PUT', $api = 'wb', $data);
  }

  private function addSupplieDb($orders, $supplie)
  {
    $this->load->model('module/cdl_wildberries');
    foreach ($orders as $order) {
      $this->model_module_cdl_wildberries->addSupplieDb($order, $supplie);
    }
  }

  public function index()
  {
    $this->load->language('module/cdl_wildberries');
		$this->document->setTitle($this->language->get('heading_title_rima'));
    $this->load->model('module/cdl_wildberries');
    $packing_orders = $this->getOrdersByStatus();
    $supplies = $this->getActive();
    if (!empty($supplies['supplies'][0]['supplyId'])) {
      $data['supplies_orders'] = $this->getSuppliesOrders($supplies['supplies'][0]['supplyId']);
      $data['supplies'] = $supplies['supplies'][0]['supplyId'];
      foreach ($data['supplies_orders']['orders'] as $key => $supplies_order) {
        $db_order = $this->model_module_cdl_wildberries->getOrder($supplies_order['orderId']);
        if (!empty($db_order[0]['sticker_bc'])) {
          $data['supplies_orders']['orders'][$key]['sticker_bc'] = $db_order[0]['sticker_bc'];
          $data['supplies_orders']['orders'][$key]['sticker'] = $db_order[0]['sticker'];
        } else {
          $data['supplies_orders']['orders'][$key]['sticker_bc'] = '';
          $data['supplies_orders']['orders'][$key]['sticker'] = '';
        }
        foreach ($packing_orders as $key_p => $packing_order) {
          if ($supplies_order['orderId'] == $packing_order['wb_order_id']) {
            unset($packing_orders[$key_p]);
          }
        }
      }
    } else {
      $data['supplies'] = '';
    }
    $data['packing_orders'] = $packing_orders;
    $data['text_supplies'] = $this->language->get('text_supplies');
    $data['text_no_supplies'] = $this->language->get('text_no_supplies');
    $data['text_create_supplies'] = $this->language->get('text_create_supplies');
    $data['text_barcode'] = $this->language->get('text_barcode');
    $data['text_packing_orders'] = $this->language->get('text_packing_orders');
    $data['text_hk'] = $this->language->get('text_hk');
    $data['text_add_to_supplies'] = $this->language->get('text_add_to_supplies');
    $data['text_orders_supplies'] = $this->language->get('text_orders_supplies');
    $data['text_skaning'] = $this->language->get('text_skaning');
    $data['text_log'] = $this->language->get('text_log');
    $data['text_clouse_supplies'] = $this->language->get('text_clouse_supplies');
    $data['text_print'] = $this->language->get('text_print');

    //url
    $data['url_create_supplie'] = 'index.php?route=module/cdl_wildberries_supplies/pass&request=create&pass=' . $this->config->get('cdl_wildberries_pass_supplies');
    $data['url_add_supplie'] = 'index.php?route=module/cdl_wildberries_supplies/pass&request=add&pass=' . $this->config->get('cdl_wildberries_pass_supplies');
    $data['url_clouse_supplie'] = 'index.php?route=module/cdl_wildberries_supplies/pass&request=clouse&id=' . $data['supplies'] . '&pass=' . $this->config->get('cdl_wildberries_pass_supplies');
    $data['url_print_supplie'] = 'index.php?route=module/cdl_wildberries_supplies/pass&request=print&id=' . $data['supplies'] . '&pass=' . $this->config->get('cdl_wildberries_pass_supplies');

    $this->response->setOutput($this->load->view('default/template/module/cdl_wildberries_supplies.tpl', $data));
  }

  private function makeRequest($url, $request, $api, $data)
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
