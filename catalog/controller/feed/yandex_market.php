<?php
class ControllerFeedYandexMarket extends Controller {
	private $shop = array();
	private $currencies = array();
	private $categories = array();
	private $offers = array();
	private $from_charset = 'utf-8';
	private $eol = "\n";

	public function index() {
		if ($this->config->get('yandex_market_status')) {

			if (!($allowed_categories = $this->config->get('yandex_market_categories'))) exit();

			$this->load->model('export/yandex_market');
			$this->load->model('localisation/currency');
			$this->load->model('tool/image');

			// Магазин
			$this->setShop('name', $this->config->get('yandex_market_shopname'));
			$this->setShop('company', $this->config->get('yandex_market_company'));
			$this->setShop('url', HTTP_SERVER);
			$this->setShop('phone', $this->config->get('config_telephone'));
			$this->setShop('platform', 'Opencart');
			$this->setShop('version', VERSION);

			// Валюты
			// TODO: Добавить возможность настраивать проценты в админке.
			$offers_currency = $this->config->get('yandex_market_currency');
			if (!$this->currency->has($offers_currency)) exit();

			$decimal_place = $this->currency->getDecimalPlace($offers_currency);

			$shop_currency = $this->config->get('config_currency');

			$this->setCurrency($offers_currency, 1);

			$currencies = $this->model_localisation_currency->getCurrencies();

			$supported_currencies = array('RUR', 'RUB', 'USD', 'BYR', 'BYN', 'KZT', 'EUR', 'UAH');

			$currencies = array_intersect_key($currencies, array_flip($supported_currencies));

			foreach ($currencies as $currency) {
				if ($currency['code'] != $offers_currency && $currency['status'] == 1) {
					$this->setCurrency($currency['code'], number_format(1/$this->currency->convert($currency['value'], $offers_currency, $shop_currency), 4, '.', ''));
				}
			}

			// Категории
			$categories = $this->model_export_yandex_market->getCategory();
			$allowed_cat_array = explode(',', $allowed_categories);
			foreach ($categories as $category) {
				if(in_array($category['category_id'],$allowed_cat_array)) {
					$this->setCategory($category['name'], $category['category_id'], $category['parent_id']);
				}
			}

			// Товарные предложения
			$in_stock_id = $this->config->get('yandex_market_in_stock'); // id статуса товара "В наличии"
			$out_of_stock_id = $this->config->get('yandex_market_out_of_stock'); // id статуса товара "Нет на складе"
			$vendor_required = false; // true - только товары у которых задан производитель, необходимо для 'vendor.model' 
			$products = $this->model_export_yandex_market->getProduct($allowed_categories, $out_of_stock_id, $vendor_required);
			$this->load->model('catalog/product');
			foreach ($products as $product) {
				$data = array();

				// Атрибуты товарного предложения
				$data['id'] = $product['product_id'];
//				$data['type'] = 'vendor.model';
				$data['available'] = ($product['quantity'] > 0 || $product['stock_status_id'] == $in_stock_id);
//				$data['bid'] = 10;
//				$data['cbid'] = 15;

				// Параметры товарного предложения
				$data['url'] = $this->url->link('product/product', 'path=' . $this->getPath($product['category_id']) . '&product_id=' . $product['product_id']);
				$data['price'] = number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
				if ($this->config->get('yandex_market_sales_notes')) {
					$data['sales_notes'] = $this->config->get('yandex_market_sales_notes');
				}
				$data['currencyId'] = $offers_currency;
				$data['categoryId'] = $product['category_id'];
				$data['delivery'] = 'true';
//				$data['local_delivery_cost'] = 100;
				$data['name'] = $product['name'];
				$data['vendor'] = $product['manufacturer'];
				$data['vendorCode'] = $product['model'];
				$data['model'] = $product['name'];
				$data['description'] = $product['description'];
//				$data['manufacturer_warranty'] = 'true';
//				$data['barcode'] = $product['sku'];

				$data['pictures'] = array();
				if ($product['image']) {
					$data['pictures'][]  = array( 
						'picture' => ($this->config->get('yandex_market_image_size') == 1) ? $this->model_tool_image->resize($product['image'], 600, 600) : HTTPS_SERVER.'image/'.$product['image']
					);
				}
				if ($this->config->get('yandex_market_image') == '2') {
					$images = $this->model_catalog_product->getProductImages($product['product_id']);
					foreach ($images as $image) {
						$data['pictures'][] = array(
							'picture' => ($this->config->get('yandex_market_image_size') == 1) ? $this->model_tool_image->resize($image['image'], 600, 600) : HTTPS_SERVER.'image/'.$image['image']
						);
					}
				}
				$attribute_groups = $this->model_catalog_product->getProductAttributes($product['product_id']);
				if ($attribute_groups) {
					$data['param'] = array();
	            	foreach ($attribute_groups as $attribute_group) {
		               	foreach ($attribute_group['attribute'] as $attribute) {
		               		$data['param'][] = array(
			                	'name'	=>	$attribute['name'], 
			                	'value'	=>	$attribute['text']
			                );
		                }
	                }
	          	}
	          	
				$this->setOffer($data);
			}

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($this->getYml());
		}
	}

	/**
	 * Методы формирования YML
	 */

	/**
	 * Формирование массива для элемента shop описывающего магазин
	 *
	 * @param string $name - Название элемента
	 * @param string $value - Значение элемента
	 */
	private function setShop($name, $value) {
		$allowed = array('name', 'company', 'url', 'phone', 'platform', 'version', 'agency', 'email');
		if (in_array($name, $allowed)) {
			$this->shop[$name] = $this->prepareField($value);
		}
	}

	/**
	 * Валюты
	 *
	 * @param string $id - код валюты (RUR, RUB, USD, BYR, KZT, EUR, UAH)
	 * @param float|string $rate - курс этой валюты к валюте, взятой за единицу.
	 *	Параметр rate может иметь так же следующие значения:
	 *		CBRF - курс по Центральному банку РФ.
	 *		NBU - курс по Национальному банку Украины.
	 *		NBK - курс по Национальному банку Казахстана.
	 *		СВ - курс по банку той страны, к которой относится интернет-магазин
	 * 		по Своему региону, указанному в Партнерском интерфейсе Яндекс.Маркета.
	 * @param float $plus - используется только в случае rate = CBRF, NBU, NBK или СВ
	 *		и означает на сколько увеличить курс в процентах от курса выбранного банка
	 * @return bool
	 */
	private function setCurrency($id, $rate = 'CBRF', $plus = 0) {
		$allow_id = array('RUR', 'RUB', 'USD', 'BYR', 'BYN', 'KZT', 'EUR', 'UAH');
		if (!in_array($id, $allow_id)) {
			return false;
		}
		$allow_rate = array('CBRF', 'NBU', 'NBK', 'CB');
		if (in_array($rate, $allow_rate)) {
			$plus = str_replace(',', '.', $plus);
			if (is_numeric($plus) && $plus > 0) {
				$this->currencies[] = array(
					'id'=>$this->prepareField(strtoupper($id)),
					'rate'=>$rate,
					'plus'=>(float)$plus
				);
			} else {
				$this->currencies[] = array(
					'id'=>$this->prepareField(strtoupper($id)),
					'rate'=>$rate
				);
			}
		} else {
			$rate = str_replace(',', '.', $rate);
			if (!(is_numeric($rate) && $rate > 0)) {
				return false;
			}
			$this->currencies[] = array(
				'id'=>$this->prepareField(strtoupper($id)),
				'rate'=>(float)$rate
			);
		}

		return true;
	}

	/**
	 * Категории товаров
	 *
	 * @param string $name - название рубрики
	 * @param int $id - id рубрики
	 * @param int $parent_id - id родительской рубрики
	 * @return bool
	 */
	private function setCategory($name, $id, $parent_id = 0) {
		$id = (int)$id;
		if ($id < 1 || trim($name) == '') {
			return false;
		}
		if ((int)$parent_id > 0) {
			$this->categories[$id] = array(
				'id'=>$id,
				'parentId'=>(int)$parent_id,
				'name'=>$this->prepareField($name)
			);
		} else {
			$this->categories[$id] = array(
				'id'=>$id,
				'name'=>$this->prepareField($name)
			);
		}

		return true;
	}

	/**
	 * Товарные предложения
	 *
	 * @param array $data - массив параметров товарного предложения
	 */
	private function setOffer($data) {
		$offer = array();

		$attributes = array('id', 'type', 'available', 'bid', 'cbid', 'param');
		$attributes = array_intersect_key($data, array_flip($attributes));

		foreach ($attributes as $key => $value) {
			switch ($key)
			{
				case 'id':
				case 'bid':
				case 'cbid':
					$value = (int)$value;
					if ($value > 0) {
						$offer[$key] = $value;
					}
					break;

				case 'type':
					if (in_array($value, array('vendor.model', 'book', 'audiobook', 'artist.title', 'tour', 'ticket', 'event-ticket'))) {
						$offer['type'] = $value;
					}
					break;

				case 'available':
					$offer['available'] = ($value ? 'true' : 'false');
					break;

				case 'param':
					if (is_array($value)) {
						$offer['param'] = $value;
					}
					break;

				default:
					break;
			}
		}

		$type = isset($offer['type']) ? $offer['type'] : '';

		$allowed_tags = array('url'=>0, 'buyurl'=>0, 'price'=>1, 'sales_notes'=>0, 'wprice'=>0, 'currencyId'=>1, 'xCategory'=>0, 'categoryId'=>1, 'pictures'=>0, 'store'=>0, 'pickup'=>0, 'delivery'=>0, 'deliveryIncluded'=>0, 'local_delivery_cost'=>0, 'orderingTime'=>0);

		switch ($type) {
			case 'vendor.model':
				$allowed_tags = array_merge($allowed_tags, array('typePrefix'=>0, 'vendor'=>1, 'vendorCode'=>0, 'model'=>1, 'provider'=>0, 'tarifplan'=>0));
				break;

			case 'book':
				$allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'binding'=>0, 'page_extent'=>0, 'table_of_contents'=>0));
				break;

			case 'audiobook':
				$allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'table_of_contents'=>0, 'performed_by'=>0, 'performance_type'=>0, 'storage'=>0, 'format'=>0, 'recording_length'=>0));
				break;

			case 'artist.title':
				$allowed_tags = array_merge($allowed_tags, array('artist'=>0, 'title'=>1, 'year'=>0, 'media'=>0, 'starring'=>0, 'director'=>0, 'originalName'=>0, 'country'=>0));
				break;

			case 'tour':
				$allowed_tags = array_merge($allowed_tags, array('worldRegion'=>0, 'country'=>0, 'region'=>0, 'days'=>1, 'dataTour'=>0, 'name'=>1, 'hotel_stars'=>0, 'room'=>0, 'meal'=>0, 'included'=>1, 'transport'=>1, 'price_min'=>0, 'price_max'=>0, 'options'=>0));
				break;

			case 'event-ticket':
				$allowed_tags = array_merge($allowed_tags, array('name'=>1, 'place'=>1, 'hall'=>0, 'hall_part'=>0, 'date'=>1, 'is_premiere'=>0, 'is_kids'=>0));
				break;

			default:
				$allowed_tags = array_merge($allowed_tags, array('name'=>1, 'vendor'=>0, 'vendorCode'=>0));
				break;
		}

		$allowed_tags = array_merge($allowed_tags, array('aliases'=>0, 'additional'=>0, 'description'=>0, 'sales_notes'=>0, 'promo'=>0, 'manufacturer_warranty'=>0, 'country_of_origin'=>0, 'downloadable'=>0, 'adult'=>0, 'barcode'=>0));

		$required_tags = array_filter($allowed_tags);

		if (sizeof(array_intersect_key($data, $required_tags)) != sizeof($required_tags)) {
			return;
		}

		$data = array_intersect_key($data, $allowed_tags);
//		if (isset($data['tarifplan']) && !isset($data['provider'])) {
//			unset($data['tarifplan']);
//		}

		$allowed_tags = array_intersect_key($allowed_tags, $data);

		// Стандарт XML учитывает порядок следования элементов,
		// поэтому важно соблюдать его в соответствии с порядком описанным в DTD
		$offer['data'] = array();
		foreach ($allowed_tags as $key => $value) {
			$offer['data'][$key] = $this->prepareField($data[$key]);
		}

		$this->offers[] = $offer;
	}

	/**
	 * Формирование YML файла
	 *
	 * @return string
	 */
	private function getYml() {
		$yml  = '<?xml version="1.0" encoding="windows-1251"?>' . $this->eol;
		$yml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . $this->eol;
		$yml .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . $this->eol;
		$yml .= '<shop>' . $this->eol;

		// информация о магазине
		$yml .= $this->array2Tag($this->shop);

		// валюты
		$yml .= '<currencies>' . $this->eol;
		foreach ($this->currencies as $currency) {
			$yml .= $this->getElement($currency, 'currency');
		}
		$yml .= '</currencies>' . $this->eol;

		// категории
		$yml .= '<categories>' . $this->eol;
		foreach ($this->categories as $category) {
			$category_name = $category['name'];
			unset($category['name']);
			$yml .= $this->getElement($category, 'category', $category_name);
		}
		$yml .= '</categories>' . $this->eol;

		// товарные предложения
		$yml .= '<offers>' . $this->eol;
		foreach ($this->offers as $offer) {
			$tags = $this->array2Tag($offer['data']);
			unset($offer['data']);
			if (isset($offer['param'])) {
				$tags .= $this->array2Param($offer['param']);
				unset($offer['param']);
			}
			$yml .= $this->getElement($offer, 'offer', $tags);
		}
		$yml .= '</offers>' . $this->eol;

		$yml .= '</shop>';
		$yml .= '</yml_catalog>';

		return $yml;
	}

	/**
	 * Фрмирование элемента
	 *
	 * @param array $attributes
	 * @param string $element_name
	 * @param string $element_value
	 * @return string
	 */
	private function getElement($attributes, $element_name, $element_value = '') {
		$retval = '<' . $element_name . ' ';
		foreach ($attributes as $key => $value) {
			$retval .= $key . '="' . $value . '" ';
		}
		$retval .= $element_value ? '>' . $this->eol . $element_value . '</' . $element_name . '>' : '/>';
		$retval .= $this->eol;

		return $retval;
	}

	/**
	 * Преобразование массива в теги
	 *
	 * @param array $tags
	 * @return string
	 */
	private function array2Tag($tags) {
		$retval = '';
		foreach ($tags as $key => $value) {
			if ($key == 'pictures'){
				foreach ($value as $image) {
					$retval .= '<picture>' . $image['picture'] . '</picture>' . $this->eol;
				}
			} else {
				$retval .= '<' . $key . '>' . $value . '</' . $key . '>' . $this->eol;
			}
		}

		return $retval;
	}

	/**
	 * Преобразование массива в теги параметров
	 *
	 * @param array $params
	 * @return string
	 */
	private function array2Param($params) {
		$retval = '';
		foreach ($params as $param) {
			$retval .= '<param name="' . $this->prepareField($param['name']);
			if (isset($param['unit'])) {
				$retval .= '" unit="' . $this->prepareField($param['unit']);
			}
			$retval .= '">' . $this->prepareField($param['value']) . '</param>' . $this->eol;
		}

		return $retval;
	}

	/**
	 * Подготовка текстового поля в соответствии с требованиями Яндекса
	 * Запрещаем любые html-тэги, стандарт XML не допускает использования в текстовых данных
	 * непечатаемых символов с ASCII-кодами в диапазоне значений от 0 до 31 (за исключением
	 * символов с кодами 9, 10, 13 - табуляция, перевод строки, возврат каретки). Также этот
	 * стандарт требует обязательной замены некоторых символов на их символьные примитивы.
	 * @param string $text
	 * @return string
	 */
	private function utf8_to_cp1251($s) {

		$tbl = array(
			0x0402 => "\x80",
			0x0403 => "\x81",
			0x201A => "\x82",
			0x0453 => "\x83",
			0x201E => "\x84",
			0x2026 => "\x85",
			0x2020 => "\x86",
			0x2021 => "\x87",
			0x20AC => "\x88",
			0x2030 => "\x89",
			0x0409 => "\x8A",
			0x2039 => "\x8B",
			0x040A => "\x8C",
			0x040C => "\x8D",
			0x040B => "\x8E",
			0x040F => "\x8F",
			0x0452 => "\x90",
			0x2018 => "\x91",
			0x2019 => "\x92",
			0x201C => "\x93",
			0x201D => "\x94",
			0x2022 => "\x95",
			0x2013 => "\x96",
			0x2014 => "\x97",
			0x2122 => "\x99",
			0x0459 => "\x9A",
			0x203A => "\x9B",
			0x045A => "\x9C",
			0x045C => "\x9D",
			0x045B => "\x9E",
			0x045F => "\x9F",
			0x00A0 => "\xA0",
			0x040E => "\xA1",
			0x045E => "\xA2",
			0x0408 => "\xA3",
			0x00A4 => "\xA4",
			0x0490 => "\xA5",
			0x00A6 => "\xA6",
			0x00A7 => "\xA7",
			0x0401 => "\xA8",
			0x00A9 => "\xA9",
			0x0404 => "\xAA",
			0x00AB => "\xAB",
			0x00AC => "\xAC",
			0x00AD => "\xAD",
			0x00AE => "\xAE",
			0x0407 => "\xAF",
			0x00B0 => "\xB0",
			0x00B1 => "\xB1",
			0x0406 => "\xB2",
			0x0456 => "\xB3",
			0x0491 => "\xB4",
			0x00B5 => "\xB5",
			0x00B6 => "\xB6",
			0x00B7 => "\xB7",
			0x0451 => "\xB8",
			0x2116 => "\xB9",
			0x0454 => "\xBA",
			0x00BB => "\xBB",
			0x0458 => "\xBC",
			0x0405 => "\xBD",
			0x0455 => "\xBE",
			0x0457 => "\xBF",
			0x0410 => "\xC0",
			0x0411 => "\xC1",
			0x0412 => "\xC2",
			0x0413 => "\xC3",
			0x0414 => "\xC4",
			0x0415 => "\xC5",
			0x0416 => "\xC6",
			0x0417 => "\xC7",
			0x0418 => "\xC8",
			0x0419 => "\xC9",
			0x041A => "\xCA",
			0x041B => "\xCB",
			0x041C => "\xCC",
			0x041D => "\xCD",
			0x041E => "\xCE",
			0x041F => "\xCF",
			0x0420 => "\xD0",
			0x0421 => "\xD1",
			0x0422 => "\xD2",
			0x0423 => "\xD3",
			0x0424 => "\xD4",
			0x0425 => "\xD5",
			0x0426 => "\xD6",
			0x0427 => "\xD7",
			0x0428 => "\xD8",
			0x0429 => "\xD9",
			0x042A => "\xDA",
			0x042B => "\xDB",
			0x042C => "\xDC",
			0x042D => "\xDD",
			0x042E => "\xDE",
			0x042F => "\xDF",
			0x0430 => "\xE0",
			0x0431 => "\xE1",
			0x0432 => "\xE2",
			0x0433 => "\xE3",
			0x0434 => "\xE4",
			0x0435 => "\xE5",
			0x0436 => "\xE6",
			0x0437 => "\xE7",
			0x0438 => "\xE8",
			0x0439 => "\xE9",
			0x043A => "\xEA",
			0x043B => "\xEB",
			0x043C => "\xEC",
			0x043D => "\xED",
			0x043E => "\xEE",
			0x043F => "\xEF",
			0x0440 => "\xF0",
			0x0441 => "\xF1",
			0x0442 => "\xF2",
			0x0443 => "\xF3",
			0x0444 => "\xF4",
			0x0445 => "\xF5",
			0x0446 => "\xF6",
			0x0447 => "\xF7",
			0x0448 => "\xF8",
			0x0449 => "\xF9",
			0x044A => "\xFA",
			0x044B => "\xFB",
			0x044C => "\xFC",
			0x044D => "\xFD",
			0x044E => "\xFE",
			0x044F => "\xFF",
		);
				$uc = 0;
				$bits = 0;
				$r = "";
				$l = strlen($s);
				for($i = 0;  $i < $l; $i++) {
				$c = $s{$i};
				$b = ord($c);
				if($b & 0x80) {
				if($b & 0x40) {
				if($b & 0x20) {
				$uc = ($b & 0x0F) << 12;
				$bits = 12;
				}
				else {
				$uc = ($b & 0x1F) << 6;
				$bits = 6;
				}
				}
				else {
				$bits -= 6;
				if($bits) {
				$uc |= ($b & 0x3F) << $bits;
				}
				else {
				$uc |= $b & 0x3F;
				if($cc = @$tbl[$uc]) {
				$r .= $cc;
				}
				else {
				$r .= '?';
				}
				}
				}
				}
				else {
				$r .= $c;
				}
				}
				return $r;
	}
	
	private function prepareField($field) {
		if (!is_array($field)){
			$field = htmlspecialchars_decode($field);
			$field = strip_tags($field);
			$from = array('"', '&', '>', '<', '\'');
			$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;');
			$field = str_replace($from, $to, $field);
			if ($this->from_charset != 'windows-1251') {
				$field = $this->utf8_to_cp1251($field);	
			}
			$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

			return trim($field);
		} else {
			return $field;
		}
	}

	protected function getPath($category_id, $current_path = '') {
		if (isset($this->categories[$category_id])) {
			if (!$current_path) {
				$new_path = $this->categories[$category_id]['id'];
			} else {
				$new_path = $this->categories[$category_id]['id'] . '_' . $current_path;
			}	

			if (isset($this->categories[$category_id]['parentId'])) {
				return $this->getPath($this->categories[$category_id]['parentId'], $new_path);
			} else {
				return $new_path;
			}

		}
	}

}
?>