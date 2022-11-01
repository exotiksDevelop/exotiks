<?php
class ControllerEventCdekshipping extends Controller {
	public function addScripts() {
		$this->document->addStyle('catalog/view/theme/default/stylesheet/sdek.css');
	    $this->document->addScript('//api-maps.yandex.ru/2.1/?lang=ru_RU&ns=cdekymap');
	    $this->document->addScript('catalog/view/javascript/sdek.js');
	}

	public function successOrder() {
		if(isset($this->session->data['cdek'])) {
          	unset($this->session->data['cdek']);
        }
	}

	public function orderCreate($route, $input_data, $order_id) {
		if($route = "checkout/order/addOrder" && (int)$order_id) {
			$this->rememberCdek($order_id);
		}
	}

	public function orderHistory($route, $input_data) {
		if($route = "checkout/order/addOrderHistory" && $input_data && isset($input_data[0])) {
			$order_id = (int)$input_data[0];
			if($order_id) {
				$this->rememberCdek($order_id);
			}
		}
	}

	private function rememberCdek($order_id) {
		if(!isset($this->session->data['cdek'])) {
			return;
		}

		if(!isset($this->session->data['cdek']['pvz'])) {
			return;
		}

		$data['pvz'] = $this->session->data['cdek']['pvz'];

		$data['city'] = 0;
		if(isset($this->session->data['cdek']['city']) && $this->session->data['cdek']['city']) {
			$data['city'] = (int)$this->session->data['cdek']['city'];
		}

		$sql = "INSERT INTO `" . DB_PREFIX . "order_to_sdek` (`order_to_sdek_id`, `order_id`, `cityId`, `pvz_code`)
		VALUES (NULL, '".(int)$order_id."', '".$data['city']."','".$this->db->escape($data['pvz'])."') ON DUPLICATE KEY UPDATE
		cityId = '".$data['city']."', pvz_code='".$this->db->escape($data['pvz'])."'";
		$this->db->query($sql);


		$comment = '';

        if(isset($this->session->data['cdek']['pvzinfo']) && $this->session->data['cdek']['pvzinfo']) {
            $pvz_comment = $this->session->data['cdek']['pvzinfo'];

            $queryComment = $this->db->query("SELECT comment FROM `" . DB_PREFIX . "order` WHERE order_id = '".$order_id."'");
            $comment = $queryComment->row['comment'];

            $comment = preg_replace("/\[CDEK\].*\[\/CDEK\]/m", "", $comment);

            if($comment) {
                $newComment = $comment . "\n" . '[CDEK]Выбранный ПВЗ: '.$pvz_comment . "[/CDEK]";
            } else {
                $newComment = '[CDEK]Выбранный ПВЗ: '.$pvz_comment . "[/CDEK]";
            }

            $this->db->query("UPDATE `" . DB_PREFIX . "order`
                SET comment = '" . $this->db->escape($newComment) ."'
                WHERE order_id = '".(int)$order_id."'");
        }
	}

	public function checkTariffPvz() {
		$json['error']['warning'] = false;

		$tariff = $this->request->post['shipping_method'];
		if(stripos($tariff, 'MRG') !== false)
		{
			if(!isset($this->session->data['cdek'])) {
				$json['error']['warning'] = 'Для выбранного тарифа нужно выбрать пвз';
			}

			if(!isset($this->session->data['cdek']['pvz']) || !$this->session->data['cdek']['pvz']) {
				$json['error']['warning'] = 'Для выбранного тарифа нужно выбрать пвз';
			}
		}

		if($json['error']['warning']) {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return false;
		}
	}
}