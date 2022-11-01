<?php
class info extends cdek_integrator {

	public function getTariffMode() {
		return array(
			1 => 'дверь-дверь (Д-Д)',
			2 => 'дверь-склад (Д-С)',
			3 => 'склад-дверь (С-Д)',
			4 => 'склад-склад (С-С)'
		);
	}

	public function getVatRates() {

		$vatRates = array();
		$vatRates['VATX'] = 'Без НДС';
		$vatRates['VAT0'] = '0%';
		$vatRates['VAT10'] = '10%';
		$vatRates['VAT18'] = '18%';

		return $vatRates;
	}

	public function getTariffInfo($tariff_id) {

		$list = $this->getTariffList();
		return isset($list[$tariff_id]) ? $list[$tariff_id] : FALSE;
	}

	public function getTariffList() {

		return array(
			'1'	=> array(
				'title'		=> 'Экспресс лайт (Д-Д)',
				'mode_id'	=> 1
			),
			'3' => array(
				'title'		=> 'Супер-экспресс до 18 (Д-Д)',
				'mode_id'	=> 1
			),
			'5' => array(
				'title'		=> 'Экономичный экспресс (С-С)',
				'mode_id'	=> 4
			),
			'7' => array(
				'title'		=> 'Международный экспресс документы (Д-Д)',
				'mode_id'	=> 1
			),
			'8' => array(
				'title'		=> 'Международный экспресс грузы (Д-Д)',
				'mode_id'	=> 1
			),
			'10' => array(
				'title'		=> 'Экспресс лайт (С-С)',
				'mode_id'	=> 4
			),
			'11' => array(
				'title'		=> 'Экспресс лайт (С-Д)',
				'mode_id'	=> 3
			),
			'12' => array(
				'title'		=> 'Экспресс лайт (Д-С)',
				'mode_id'	=> 2
			),
			'15' => array(
				'title'		=> 'Экспресс тяжеловесы (С-С)',
				'mode_id'	=> 4
			),
			'16' => array(
				'title'		=> 'Экспресс тяжеловесы (С-Д)',
				'mode_id'	=> 3
			),
			'17' => array(
				'title'		=> 'Экспресс тяжеловесы (Д-С)',
				'mode_id'	=> 2
			),
			'18' => array(
				'title'		=> 'Экспресс тяжеловесы (Д-Д)',
				'mode_id'	=> 1
			),
			'57' => array(
				'title'		=> 'Супер-экспресс до 9 (Д-Д)',
				'mode_id'	=> 1
			),
			'58' => array(
				'title'		=> 'Супер-экспресс до 10 (Д-Д)',
				'mode_id'	=> 1
			),
			'59' => array(
				'title'		=> 'Супер-экспресс до 12 (Д-Д)',
				'mode_id'	=> 1
			),
			'60' => array(
				'title'		=> 'Супер-экспресс до 14 (Д-Д)',
				'mode_id'	=> 1
			),
			'61' => array(
				'title'		=> 'Супер-экспресс до 16 (Д-Д)',
				'mode_id'	=> 1
			),
			'62' => array(
				'title'		=> 'Магистральный экспресс (С-С)',
				'mode_id'	=> 4
			),
			'63' => array(
				'title'		=> 'Магистральный супер-экспресс (С-С)',
				'mode_id'	=> 4
			),

			'136' => array(
				'title'		=> 'Посылка (С-С)',
				'mode_id'	=> 4,
				'im'		=> 1
			),
			'137' => array(
				'title'		=> 'Посылка (С-Д)',
				'mode_id'	=> 3,
				'im'		=> 1
			),
			'138' => array(
				'title'		=> 'Посылка (Д-С)',
				'mode_id'	=> 2,
				'im'		=> 1
			),
			'139' => array(
				'title'		=> 'Посылка (Д-Д)',
				'mode_id'	=> 1,
				'im'		=> 1
			),
			'233' => array(
				'title'		=> 'Экономичная посылка (С-Д)',
				'mode_id'	=> 3,
				'im'		=> 1
			),
			'234' => array(
				'title'		=> 'Экономичная посылка (С-С)',
				'mode_id'	=> 4,
				'im'		=> 1
			),
			'291' => array(
				'title'		=> 'CDEK Express (С-С)',
				'mode_id'	=> 4,
				'im'		=> 1
			),
			'293' => array(
				'title'		=> 'CDEK Express (Д-Д)',
				'mode_id'	=> 1,
				'im'		=> 1
			),
			'294' => array(
				'title'		=> 'CDEK Express (С-Д)',
				'mode_id'	=> 3,
				'im'		=> 1
			),
			'295' => array(
				'title'		=> 'CDEK Express (Д-С)',
				'mode_id'	=> 2,
				'im'		=> 1
			),
			'243' => array(
				'title'		=> 'Китайский экспресс (С-С)',
				'mode_id'	=> 4,
			),
			'245' => array(
				'title'		=> 'Китайский экспресс (Д-Д)',
				'mode_id'	=> 1,
			),
			'246' => array(
				'title'		=> 'Китайский экспресс (С-Д)',
				'mode_id'	=> 3,
			),
			'247' => array(
				'title'		=> 'Китайский экспресс (Д-С)',
				'mode_id'	=> 2,
			),

		);
	}

	public function getInpostTariffs() {
		$postomats_id = array('301','302');
		return $postomats_id;
	}

	public function getAddService($service_id) {

		$all = $this->getAddServices();
		return array_key_exists($service_id, $all) ? $all[$service_id] : FALSE;
	}

	public function getAddServices() {

		return array(
			3 => array(
				'title'			=> 'Доставка в выходной день',
				'description'	=> 'Осуществление доставки заказа в выходные и нерабочие дни.'
			),
			16 => array(
				'title'			=> 'Забор в городе отправителя', // Только для тарифов от склада
				'description'	=> 'Дополнительная услуга забора груза в городе отправителя'
			),
			17 => array(
				'title'			=> 'Доставка в городе получателя',
				'description'	=> 'Дополнительная услуга доставки груза в городе получателя'  // Только для тарифов до склада (только для тарифов «Магистральный», «Магистральный супер-экспресс»)
			),
			30 => array(
				'title'			=> 'Примерка на дому',
				'description'	=> 'Курьер доставляет покупателю несколько единиц товара (одежда, обувь и пр.) для примерки. Время ожидания курьера в этом случае составляет 30 минут.'
			),
			36 => array(
				'title'			=> 'Частичная доставка',
				'description'	=> 'Во время доставки товара покупатель может отказаться от одной или нескольких позиций, и выкупить только часть заказа.'
			),
			37 => array(
				'title'			=> 'Осмотр вложения',
				'description'	=> 'Проверка покупателем содержимого заказа до его оплаты (вскрытие посылки).'
			),
			2 => array(
				'title'			=> 'Страхование',
				'hide'			=> TRUE,
				'description'	=> 'Обеспечение страховой защиты посылки. Размер дополнительного сбора страхования вычисляется от размера объявленной стоимости отправления. Важно: Услуга начисляется автоматически для всех заказов ИМ.'
			)
		);

	}

	public function getOrderStatus($status_id) {

		$all = $this->getOrderStatuses();
		return array_key_exists($status_id, $all) ? $all[$status_id] : FALSE;
	}

	public function getOrderStatuses() {

		return array(
			1 => array(
				'title'			=> 'Создан',
				'description'	=> 'Заказ зарегистрирован в базе данных СДЭК'
			),
			2 => array(
				'title'			=> 'Удален',
				'description'	=> 'Заказ отменен ИМ после регистрации в системе до прихода груза на склад СДЭК в городе-отправителе'
			),
			3 => array(
				'title'			=> 'Принят на склад отправителя',
				'description'	=> 'Оформлен приход на склад СДЭК в городе-отправителе. '
			),
			6 => array(
				'title'			=> 'Выдан на отправку в г.-отправителе',
				'description'	=> 'Оформлен расход со склада СДЭК в городе-отправителе. Груз подготовлен к отправке (консолидирован с другими посылками)'
			),
			16 => array(
				'title'			=> 'Возвращен на склад отправителя',
				'description'	=> 'Повторно оформлен приход в городе-отправителе (не удалось передать перевозчику по какой-либо причине)'
			),
			7 => array(
				'title'			=> 'Сдан перевозчику в г.-отправителе',
				'description'	=> 'Зарегистрирована отправка в городе-отправителе. Консолидированный груз передан на доставку (в аэропорт/загружен машину)'
			),
			21 => array(
				'title'			=> 'Отправлен в г.-транзит',
				'description'	=> 'Зарегистрирована отправка в город-транзит. Проставлены дата и время отправления у перевозчика'
			),
			22 => array(
				'title'			=> 'Встречен в г.-транзите',
				'description'	=> 'Зарегистрирована встреча в городе-транзите'
			),
			13 => array(
				'title'			=> 'Принят на склад транзита',
				'description'	=> 'Оформлен приход в городе-транзите'
			),
			17 => array(
				'title'			=> 'Возвращен на склад транзита',
				'description'	=> 'Повторно оформлен приход в городе-транзите (груз возвращен на склад)'
			),
			19 => array(
				'title'			=> 'Выдан на отправку в г.-транзите',
				'description'	=> 'Оформлен расход в городе-транзите'
			),
			20 => array(
				'title'			=> 'Сдан перевозчику в г.-транзите',
				'description'	=> 'Зарегистрирована отправка у перевозчика в городе-транзите'
			),
			8 => array(
				'title'			=> 'Отправлен в г.-получатель',
				'description'	=> 'Зарегистрирована отправка в город-получатель, груз в пути.'
			),
			9 => array(
				'title'			=> 'Встречен в г.-получателе',
				'description'	=> 'Зарегистрирована встреча груза в городе-получателе'
			),
			10 => array(
				'title'			=> 'Принят на склад доставки',
				'description'	=> 'Оформлен приход на склад города-получателя., ожидает доставки до двери'
			),
			12 => array(
				'title'			=> 'Принят на склад до востребования',
				'description'	=> 'Оформлен приход на склад города-получателя. Доставка до склада, посылка ожидает забора клиентом - покупателем ИМ'
			),
			11 => array(
				'title'			=> 'Выдан на доставку',
				'description'	=> 'Добавлен в курьерскую карту, выдан курьеру на доставку'
			),
			18 => array(
				'title'			=> 'Возвращен на склад доставки',
				'description'	=> 'Оформлен повторный приход на склад в городе-получателе. Доставка не удалась по какой-либо причине, ожидается очередная попытка доставки'
			),
			4 => array(
				'title'			=> 'Вручен',
				'description'	=> 'Успешно доставлен и вручен адресату'
			),
			5 => array(
				'title'			=> 'Не вручен, возврат',
				'description'	=> 'Покупатель отказался от покупки, возврат в ИМ'
			)
		);

	}

	public function getCurrencyList() {

		return array(
			'RUB' => 'Российский рубль',
			'USD' => 'Доллар США',
			'EUR' => 'Евро',
			'KZT' => 'Тенге',
			'GBP' => 'Фунт стерлингов',
			'CNY' => 'Юань',
			'BYN' => 'Белорусский рубль',
			'UAH' => 'Гривна'
		);

	}

	public function getCurrency($currency) {

		$all = $this->getCurrencyList();
		return array_key_exists($currency, $all) ? $all[$currency] : $currency['RUB'];
	}

	public function getPVZData() {

		$data = array();

		$pvz_list = $this->getURL($this->base_url . 'pvzlist.php?type=ALL', new parser_xml());

		if (isset($pvz_list->Pvz)) {

			foreach ($pvz_list->Pvz as $pvz_info) {

				if (empty($pvz_info['City']) || empty($pvz_info['Address'])) {
					continue;
				}

				$key = md5($pvz_info['Address']);

				if (array_key_exists($key, $data)) continue;

				$info = array(
					'Code'		=> (string)$pvz_info['Code'],
					'City'		=> (string)$pvz_info['City'],
					'CityCode'	=> (string)$pvz_info['CityCode'],
					'Address'	=> (string)$pvz_info['Address'],
					'Name'		=> (string)$pvz_info['Name'],
					'WorkTime'	=> (string)$pvz_info['WorkTime'],
					'Phone'		=> (string)$pvz_info['Phone'],
					'Note'		=> (string)$pvz_info['Note'],
					'x'			=> (string)$pvz_info['coordX'],
					'y'			=> (string)$pvz_info['coordY']
				);

				if (isset($pvz_info->WeightLimit)) {

					$info['WeightLimit'] = array(
						'WeightMin' => (float)$pvz_info->WeightLimit['WeightMin'],
						'WeightMax' => (float)$pvz_info->WeightLimit['WeightMax']
					);

				}

				if (empty($data[(int)$pvz_info['CityCode']])) {

					$data[(int)$pvz_info['CityCode']] = array(
						'City'	=> $info['City'],
						'List'	=> array()
					);

				}

				$data[(int)$pvz_info['CityCode']]['List'][$key] = $info;
			}

		}

		return $data;
	}

	public function getBaseUrl() {
		return $this->base_url;
	}

	public function getAjaxUrl() {
		return $this->ajax_url;
	}

	public function getCityByName($city) {

		$response = $this->getURL('https://api.cdek.ru/city/getListByTerm/json.php?q=' . urlencode($city) . '&name_startsWith=' . urlencode($city), new parser_json());

		return isset($response['geonames']) ? $response['geonames'] : FALSE;
	}


}

?>