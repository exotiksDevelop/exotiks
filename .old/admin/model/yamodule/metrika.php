<?php

Class ModelYamoduleMetrika extends Model
{
	public $url_api = 'https://api-metrika.yandex.ru/management/v1/';
	
	public function processCounter($number, $token)
	{
		$data = array(
			'YA_METRIKA_CART' =>  array(
					'name' => 'YA_METRIKA_CART',
					'flag' => 'basket',
					'type' => 'action',
					'class' => 1,
					'depth' => 0,
					'conditions' => array(
						array(
							'url' => 'metrikaCart',
							'type' => 'exact'
						)
					)

			),
			'YA_METRIKA_ORDER' => array(
					'name' => 'YA_METRIKA_ORDER',
					'flag' => 'order',
					'type' => 'action',
					'class' => 1,
					'depth' => 0,
					'conditions' => array(
						array(
							'url' => 'metrikaOrder',
							'type' => 'exact'
						)
					)

			),
			'YA_METRIKA_WISHLIST' => array(
					'name' => 'YA_METRIKA_WISHLIST',
					'flag' => '',
					'type' => 'action',
					'class' => 1,
					'depth' => 0,
					'conditions' => array(
						array(
							'url' => 'metrikaWishlist',
							'type' => 'exact'
						)
					)

			),
		);
		
		$otvet = false;
		$counter = $this->SendResponse('counter/'.$number, $token, array(), array(), 'GET');
		if(!empty($counter->counter->code))
		{
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('ya_metrika_code', array('ya_metrika_code' => $counter->counter->code));
			$otvet = $this->editCounter($number, $token);
			if($otvet)
			{
				$goals = array();
				$tmp_goals = $this->getCounterGoals($number, $token);
					foreach($tmp_goals->goals as $goal)
						$goals[$goal->name] = $goal;

				if($this->Cget('ya_metrika_cart'))
					$res['cart'] = $this->addCounterGoal($number, $token, array('goal' => $data['YA_METRIKA_CART']));
				elseif (isset($goals['YA_METRIKA_CART']))
					$res['cart'] = $this->deleteCounterGoal($number, $token, $goals['YA_METRIKA_CART']->id);
					
				if($this->Cget('ya_metrika_order'))
					$res['order'] = $this->addCounterGoal($number, $token, array('goal' => $data['YA_METRIKA_ORDER']));
				elseif (isset($goals['YA_METRIKA_ORDER']))
					$res['order'] = $this->deleteCounterGoal($number, $token, $goals['YA_METRIKA_ORDER']->id);
				
				$this->session->data['metrika_status'][] = $this->success_alert('Данные метрики отправлены на сервер!');
			}
			else
				$this->session->data['metrika_status'][] = $this->errors_alert('Ошибка редактирования счётчика');
		}
		else
			$this->session->data['metrika_status'][] = $this->errors_alert('Проверьте настройки метрики. Получен ответ с ошибкой.');

		return isset($res) ? $res : $otvet;
	}

	// Все цели счётчика
	public function getCounterGoals($number, $token)
	{
		return $this->SendResponse('counter/'.$number.'/goals', $token, array(), array(), 'GET');
	}
	
	// Добавление цели
	public function addCounterGoal($number, $token, $params)
	{
		return $this->SendResponse('counter/'.$number.'/goals', $token, array(), $params, 'POSTJSON');
	}
	
	// Удаление цели
	public function deleteCounterGoal($number, $token, $goal)
	{
		return $this->SendResponse('counter/'.$number.'/goal/'.$goal, $token, array(), array(), 'DELETE');
	}

	public function Cget($name)
	{
		$query = $this->db->query('SELECT * FROM '.DB_PREFIX.'setting WHERE `key` = "'.$name.'"');
		return $query->row['value'];
	}

	public function editCounter($number, $token)
	{
		$params = array('counter'=>array(
			'goals_remove' => 0,
			'code_options' => array(
				'clickmap' => $this->Cget('ya_metrika_clickmap'),
				'external_links' => $this->Cget('ya_metrika_out'),
				'visor' => $this->Cget('ya_metrika_webvizor'),
				'denial' => $this->Cget('ya_metrika_otkaz'),
				'track_hash' => $this->Cget('ya_metrika_hash'),
			)
		));

		if(count($params)){
			return $this->SendResponse('counter/'.$number, $token, array(), $params, 'PUT');
		}
	}
	
	public function SendResponse($to, $token, $headers, $params, $type, $pretty = 1)
	{
		$response = $this->post($this->url_api.$to.'?pretty=1&oauth_token='.$token, $headers, $params, $type);
		$data = json_decode($response->body);
		if($response->status_code == 200){
			return $data;
		}else{
			$this->log_save($response->body);
			$this->session->data['metrika_status'][] = $this->errors_alert($response->body);
		}
	}

	public static function log_save($logtext){
		$error_log = new Log('error.log');
		$error_log->write($logtext.PHP_EOL);
		$error_log = null;
	}

	public function errors_alert($text)
	{
		$html = '<div class="alert alert-danger">
						<i class="fa fa-exclamation-circle"></i> '.$text.'
							<button type="button" class="close" data-dismiss="alert">×</button>
					</div>';
		return $html;
	}

	public function success_alert($text)
	{
		$html = ' <div class="alert alert-success">
					<i class="fa fa-check-circle"></i> '.$text.'
						<button type="button" class="close" data-dismiss="alert">×</button>
					</div>';
		return $html;
	}

	public static function post($url, $headers, $params, $type){
		$curlOpt = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 80,
            CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_USERAGENT => 'php-market',
        );
		
		switch (strtoupper($type)){
            case 'DELETE':
                $curlOpt[CURLOPT_CUSTOMREQUEST] = "DELETE";
            case 'GET':
                if (!empty($params))
                    $url .= (strpos($url, '?')===false ? '?' : '&') . http_build_query($params);
            break;
            case 'PUT':
				$headers[] = 'Content-Type: application/x-yametrika+json';
                $body = json_encode($params);
                $fp = fopen('php://temp/maxmemory:256000', 'w');
                if (!$fp)
                    throw new YandexApiException('Could not open temp memory data');
                fwrite($fp, $body);
                fseek($fp, 0);
                $curlOpt[CURLOPT_PUT] = 1;
                $curlOpt[CURLOPT_BINARYTRANSFER] = 1;
                $curlOpt[CURLOPT_INFILE] = $fp; // file pointer
                $curlOpt[CURLOPT_INFILESIZE] = strlen($body);
            break;
            case 'POST':
				$headers[] = 'Content-Type: application/x-www-form-urlencoded';
                $curlOpt[CURLOPT_HTTPHEADER] = $headers;
                $curlOpt[CURLOPT_POST] = true;
                $curlOpt[CURLOPT_POSTFIELDS] = http_build_query($params);
            break;
            case 'POSTJSON':
				$headers[] = 'Content-Type: application/x-yametrika+json';
                $curlOpt[CURLOPT_HTTPHEADER] = $headers;
                $curlOpt[CURLOPT_POST] = true;
                $curlOpt[CURLOPT_POSTFIELDS] = json_encode($params);
            break;
            default:
                throw new YandexApiException("Unsupported request method '$method'");
        }
        $curl = curl_init($url);
        curl_setopt_array($curl, $curlOpt);
        $rbody = curl_exec($curl);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // Tools::d(curl_getinfo($curl, CURLINFO_HEADER_OUT));
		curl_close($curl);
		$result = new stdClass();
		$result->status_code = $rcode;
		$result->body = $rbody;
		return $result;
	}
}

Class ModelExtensionYamoduleMetrika extends ModelYamoduleMetrika{}