<?php
class ModelModuleWildberries extends Model {
	const WILDBERRIES_API_URL = 'https://suppliers-api.wildberries.ru/';
	// const WILDBERRIES_API_URL = 'https://content-suppliers.wildberries.ru/';
	const PROD_ORDER_API_URL = 'https://suppliers-orders.wildberries.ru/';
	const TEST_ORDER_API_URL = 'https://suppliers-orders-test.wildberries.ru/';
	const PROD_PRODUCT_API_URL = 'https://wbxgate.wildberries.ru/';
	const TEST_PRODUCT_API_URL = 'https://wbxgate-test.wildberries.ru/';
	const SUPPLIERS_PORTAL_URL	= 'https://suppliers-portal.wildberries.ru/';
	const MIN_DELAY = 15;
	const WB_ORDER_TABLE = 'wb_order';
	const WB_PRODUCT_TABLE = 'wb_product';

	protected $token;
	protected $api_url;
	protected $wb_token;
	protected $attributes = array();
	protected $categories = array();
	protected $options = array();
	protected $manufactures = array();
	protected $store = array();
	protected $order_ids = array();

	public function __construct($registry) {
		parent::__construct($registry);
		$this->setRoutes();
	}

	public function setToken($wb_phone) {
		$data = [
			'phone'								=>	$wb_phone,
			'is_terms_and_conditions_accepted'	=>	true,
		];
		$result = json_decode($this->sendData('passport/api/v2/auth/login_by_phone', $data, false), true);
		$token = isset($result['token']) ? $result['token'] : '';
		$till_next_request = isset($result['till_next_request']) ? $result['till_next_request'] : false;
		return compact('token', 'wb_phone', 'till_next_request');
	}

	public function sendData($path, $data = [], $withCookie = true, $returnCookie = false) {
		$curl = curl_init();
		$url = self::WILDBERRIES_API_URL . $path;
		$body = !empty($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : '{}';
		curl_setopt_array($curl, array(
			CURLOPT_URL				=>	$url,
			CURLOPT_RETURNTRANSFER	=>	true,
			CURLOPT_ENCODING		=>	"",
			CURLOPT_MAXREDIRS		=>	10,
			CURLOPT_TIMEOUT			=>	2,
			CURLOPT_FOLLOWLOCATION	=>	true,
			CURLOPT_HTTP_VERSION	=>	CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	=>	"POST",
			CURLOPT_POSTFIELDS		=>	$body,
			CURLOPT_HEADER			=>	$returnCookie ? 1 : 0,
			CURLOPT_SSL_VERIFYPEER	=>	0,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"User-Agent: PostmanRuntime/7.26.1",
				"Authorization: " . ($this->wb_token ? $this->wb_token : $this->config->get('wb_token_phone')),
				// 'Content-Length: ' . strlen($body)
			),
		));
		// if ($withCookie) {
		// 	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		// 		"Content-Type: application/json",
		// 		"Cookie: WBToken=" . ($this->wb_token ? $this->wb_token : $this->config->get('wb_token_phone')),
		// 	));
		// }

		$response = curl_exec($curl);

		$err = curl_error($curl);
		
		curl_close($curl);
		if ($returnCookie) {
			preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
			$cookies = array();
			foreach($matches[1] as $item) {
				parse_str($item, $cookie);
				$cookies = array_merge($cookies, $cookie);
			}
			return $cookies;
		}

		return $response;
	}

	public function getAllCards() {
		return $this->sendData('card/list');
	}

	

	public function login($phone_code, $token) {
		$data = [
			'token'		=>	$token,
			'options'	=>	[
				'notify_code' => $phone_code,
			]
		];
		$cookies = $this->sendData('passport/api/v2/auth/login', $data, false, true);
		if (!empty($cookies['WBToken'])) {
			$this->wb_token = $cookies['WBToken'];
			//$this->getWBgetProfile();
			$result = json_decode($this->sendData('passport/api/v2/auth/grant', [], true, false), true);
			if (isset($result['token'])) {
				$this->load->model('setting/setting');
				$res = [
					'phone_token_status'	=>	true,
					'wb_token_phone'		=>	$this->wb_token,
					'wb_refresh_token'		=>	$result['token'],
					'wb_token_created'		=>	time(),
				];
				$this->model_setting_setting->editSetting('phone_token_status', ['phone_token_status' => 1]);
				$this->model_setting_setting->editSetting('wb_token_phone', ['wb_token_phone' => $this->wb_token]);
				$this->model_setting_setting->editSetting('wb_refresh_token', ['wb_refresh_token'	=>	$result['token']]);
				$this->model_setting_setting->editSetting('wb_token_created', ['wb_token_created' => time()]);
				return $res;
			}
		}
		return false;
	}

	public function checkModel($order) {
		if ($order) {
			$this->api_url = $this->config->get('wb_work_status') ? self::PROD_ORDER_API_URL: self::TEST_ORDER_API_URL;
		} else {
			$this->api_url = $this->config->get('wb_work_status') ? self::PROD_PRODUCT_API_URL: self::TEST_PRODUCT_API_URL;
		}
		$this->token = $this->config->get('wb_work_status') ? $this->config->get('wb_w_token'): $this->config->get('wb_t_token');
	}

	public function apiSend($path, $method = "GET", $data = []) {
		$url = $this->api_url . $path;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_HTTPHEADER => array(
				"X-Auth-Token: " . $this->token,
			),
		));

		if (!empty($data)) {
			curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $data ) );
		}
		// curl_setopt($curl, CURLOPT_HEADER, 1);
		$response = curl_exec($curl);
		$erno = curl_errno($curl);
		$err = curl_error($curl);

		curl_close($curl);
		return $response;
	}

	public function refreshtoken() {
		$wb_stores = $this->getStores();
		if(!empty($wb_stores)) {
			$stores = array_map(function($el) {
				return (array)$el;
			}, $wb_stores);
			$old_stores = json_encode($stores);
			foreach($stores as $key => $store) {
				if (!is_array($store)) $store = (array)$store;
				$now = time();
				$created = intval($store['wb_token_created']);
				if (($now - $created) > (6 * 24 * 60 * 60)) {
					$wb_refresh_token = $store['wb_refresh_token'];
	
					$data = [
						"country"	=>	"RU",
						"device"	=>	"MacBookPro16",
						"token"		=>	$wb_refresh_token,
					];
					$cookies = $this->sendData('passport/api/v2/auth/login', $data, false, true);
					if (!empty($cookies['WBToken'])) {
						if(is_array($stores[$key])) {
							$stores[$key]['wb_token_phone'] = $cookies['WBToken'];
							$stores[$key]['wb_token_created'] = time();
						} else if (is_object($stores[$key])) {
							$stores[$key]->wb_token_phone = $cookies['WBToken'];
							$stores[$key]->wb_token_created = time();
						}
					}
				}
			}
			if(json_encode($stores) !== $old_stores) {
				$this->load->model('setting/setting');
				$this->log->write('Обновление токенов');
				$this->log->write($old_stores);
				$this->log->write('Новые токены');
				$this->log->write(json_encode($stores));
				$sql = "UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . addslashes(json_encode($stores)) . "' WHERE `code` LIKE 'wb' AND `key` LIKE 'wb_settings';";
				$this->db->query($sql);
				$this->model_setting_setting->editSetting('wb_settings', ['wb_settings'	=>	$stores]);
			}
		}
		return [
			'status'	=>	true,
		];
	}

	public function syncOrders() {
		$this->checkModel(true);
		$wb_upload_order = $this->config->get('wb_upload_order');
		$time = !empty($wb_upload_order) && !is_null($wb_upload_order) ? strtotime('now -'.$wb_upload_order.' minutes') : strtotime('now -1 year');
		$last_cron = $this->config->get('wb_upload_order_last_cron');
		if (empty($last_cron) || ($time - $last_cron) > (empty($wb_upload_order) || is_null($wb_upload_order) ? 0 : ($wb_upload_order * 60) )) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('wb_upload_order_last_cron', ['wb_upload_order_last_cron'	=>	strtotime('now')]);
			$date = preg_replace('/\d{2}\+\d{2}\:\d{2}/', '00Z', date('c', $time));
			$result = json_decode($this->apiSend('api/v1/orders?date_start=' . $date));
			$this->updateOrderStatuses();
			$this->multiUpdateOrders();
			$order_statuses = !empty($this->config->get('wb_order_status')) ? json_decode($this->config->get('wb_order_status'), true) : [];
			$last = end($order_statuses);
			if (is_array($result) && $last) {
				$data_query = [];
				foreach($result as $res) {
					if (!in_array($res->order_id, $this->order_ids) && $res->items[0]->status !== $last['id']) {
						$items = array_map(function($el) {
							return [
								"rid"		=>	$el->rid,
								"status"	=>	$el->status
							];
						}, $res->items);
						$data_query[] = "('" . $res->order_id . "', " . $res->items[0]->status .", '" . json_encode($items) . "')";
					}
				}
				if (!empty($data_query)) {
					$this->createOrders($data_query);
				}
			}
		}
	}

	public function getOrders($where = '', $select = '*') {
		return $this->db->query("SELECT " . $select . " FROM " . self::WB_ORDER_TABLE  . $where . ";");
	}

	public function updateOrders($new_status, $old_status) {
		$this->db->query("UPDATE " . self::WB_ORDER_TABLE . " SET `order_status_id`=" . $new_status . " WHERE `order_status_id` = " . $old_status . ";");
	}

	public function deleteOrders($status) {
		$this->db->query("DELETE FROM " . self::WB_ORDER_TABLE . " WHERE order_status_id=" . $status .";");
	}

	public function createOrders($orders) {
		$this->db->query("INSERT INTO " . self::WB_ORDER_TABLE . " (order_id, order_status_id, items) VALUES " . implode(',', $orders) . ";");
	}

	public function getProduct($product_id) {
		$nmId = $this->db->query("SELECT nmId FROM " . self::WB_PRODUCT_TABLE . " WHERE oc_product_id = ".$product_id.";");
		if (0 < $nmId->num_rows) {
            return $nmId->row['nmId'];
        }
	}

	public function getNmIds($product_id, $stores = []) {
		$query = "SELECT id, nmId, wb_store_id, quantity_flag, barcode FROM " . self::WB_PRODUCT_TABLE . " WHERE oc_product_id = ".$product_id." ";
		if (!empty($stores)) {
			$query .= " AND wb_store_id IN ( '" . implode("', '", $stores) . "' )";
		}
		$nmId = $this->db->query($query);
		$result = [];
		foreach($nmId->rows as $row) {
			$result[] = [
				'id'	   		=> $row['id'],
				'quantity_flag'	=> $row['quantity_flag'],
				'wb_store' 		=> $row['wb_store_id'],
				'nmId'	   		=> $row['nmId'],
				'barcode'		=> $row['barcode'],
			];
		}
		return $result;
	}

	public function getProductById($wb_id) {
		$product = $this->db->query("SELECT * FROM " .DB_PREFIX . "product WHERE product_id IN (SELECT oc_product_id FROM " . self::WB_PRODUCT_TABLE .  " WHERE wb_product_id = '".$wb_id."');");
		return $product->row;
	}

	public function getProductByWbId($wb_id) {
		$product = $this->db->query("SELECT * FROM " . self::WB_PRODUCT_TABLE . " WHERE wb_product_id = '".$wb_id."';");
		return $product->row;
	}
	
	public function getWbProduct() {
		$wb_products = $this->db->query("SELECT oc_product_id FROM " . self::WB_PRODUCT_TABLE . ";");
		if (0 < $wb_products->num_rows) {
            foreach($wb_products->rows as $el){
				$i[] = $el['oc_product_id'];
			}
			return $i;
        } else {
			return [];
		}
	}
	
	public function getTotalWbProduct() {
		$wb_products = $this->db->query("SELECT oc_product_id FROM " . self::WB_PRODUCT_TABLE . ";");
		if (0 < $wb_products->num_rows) {
			return $wb_products->num_rows;
		} else {
			return 0;
		}
	}
	
	public function getProducts($ids = [], $without_not_null = false) {
		$sql = "SELECT " . self::WB_PRODUCT_TABLE . ".nmId as nmId, " . self::WB_PRODUCT_TABLE . ".quantity_flag as quantity_flag, " . self::WB_PRODUCT_TABLE . ".barcode as barcode, " . self::WB_PRODUCT_TABLE . ".chrtId as chrtId, " . DB_PREFIX . "product.price as price, " . DB_PREFIX . "product.quantity as quantity FROM " . self::WB_PRODUCT_TABLE . " INNER JOIN " . DB_PREFIX . "product ON " . self::WB_PRODUCT_TABLE . ".oc_product_id = " . DB_PREFIX . "product.product_id WHERE 1=1";
		if (!empty($ids)) {
			$sql .= ' AND ' . self::WB_PRODUCT_TABLE . ".wb_store_id IN (" . implode(',', $ids) . ")";
		}
		if ($without_not_null) {
			$sql .= ' AND ' .self::WB_PRODUCT_TABLE . '.oc_product_id IS NOT NULL'; 
		}
		return $this->db->query($sql);
	}

	public function syncProduct($arrProducts) {
		$this->checkModel(false);
		$wb_upload_product = $this->config->get('wb_upload_product');
		$time = !empty($wb_upload_product) && !is_null($wb_upload_product) ? strtotime('now -'.$wb_upload_product.' minutes') : strtotime('now -15 minutes');
		$last_cron = $this->config->get('wb_upload_product_last_cron');
		if (empty($last_cron) || ($time - $last_cron) > (empty($wb_upload_product) || is_null($wb_upload_product) ? 0 : ($wb_upload_product * 60) )) {
			$kef = $this->config->get('wb_percent_product') ? intval($this->config->get('wb_percent_product')) : 0;
			$wb_stores = $this->getStores();
			if (!empty($wb_stores)) {
				foreach($wb_stores as $wb_store) {
					if (!is_array($wb_store)) $wb_store = (array)$wb_store;
					$this->wb_token = $wb_store['wb_token_phone'];
					// $this->token = $wb_store['wb_work_status'] == '1' ? $wb_store['wb_w_token']: $wb_store['wb_t_token'];
					// $this->wb_token = $this->token;
					// $this->api_url = $wb_store['wb_work_status'] == '1' ? self::PROD_PRODUCT_API_URL : self::TEST_PRODUCT_API_URL;
					$stocks = [];
					$prices = [];
					if (!empty($wb_store['wb_uuid'])) {
						$storeId = $wb_store['wb_store'];
						$products = $this->getProducts([$wb_store['wb_uuid']], true);
						if(!empty($products->num_rows) && $storeId) {
							$post_data = [
								'token'	=>	$this->token,
								'data'	=>	[]
							];
							$data = [];
							$storeId = intval($storeId);
							foreach($products->rows as $product) {
								$price = $kef > 0 ? intval(round(intval($product['price']) + (intval($product['price']) / 100 * $kef))) : intval($product['price']);
								if (intval($product['nmId']) > 0) { 
									$prices[] = [
										"nmId"		=>	intval($product['nmId']),
										"price"		=>	intval($price),
									];
			
								}
							}
							if (intval($product['barcode']) > 0 && intval($wb_store['wb_store']) > 0) {
									$stocks[] = [
										"barcode"		=>	$product['barcode'],
										"stock"			=>	$product['quantity_flag'] == '1' ? 0 : intval($product['quantity']),
										"warehouseId"	=>	intval($wb_store['wb_store'])
									];
							}
						}
					}
				}
			}
		}
		if (!empty($stocks)) {
			var_dump($this->sendData("api/v2/stocks", $stocks));
		}
		if (!empty($prices)) {
			var_dump($this->sendData("public/api/v1/prices", $prices));
		}
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('wb_upload_product_last_cron', ['wb_upload_product_last_cron'	=>	strtotime('now')]);
		die();
	}

	public function multiUpdateOrders() {
		$orders = $this->getOrders();
		$orders_for_update = [];
		foreach($orders->rows as $order) {
			$status = $order['order_status_id'];
			$orders_for_update[] = [
				'orderId'	=>	$order['order_id'],
				'status'	=>	intval($status),
			];
			$this->order_ids[] = $order['order_id'];
		}
		if (!empty($orders_for_update)) $this->apiSend('api/v2/orders', 'PUT', $orders_for_update);
	}

	public function updateOrderStatuses() {
		$order_statuses = !empty($this->config->get('wb_order_status')) ? json_decode($this->config->get('wb_order_status'), true) : [];
		$new = $this->getOrders(' WHERE date_update > DATE_SUB(NOW(),INTERVAL ' . self::MIN_DELAY . ' MINUTE)');
		if ($new->num_rows) {
			if ($last = end($order_statuses)) {
				$this->deleteOrders($last['id']);
			}
			for($i = 0; $i < count($order_statuses); $i++) {
				if (isset($order_statuses[$i + 1])) $this->updateOrders($order_statuses[$i + 1]['id'], $order_statuses[$i]['id']);
			}
		}
	}

	public function import($file, $compare_field, $excel_field, $wb_profile_id) {
		require_once(DIR_SYSTEM . 'SimpleXLSXWB/Classes/SimpleXLSX.php');
		$inputFileName = $file['tmp_name'];
		$excel_field = intval($excel_field) > 0 ? intval($excel_field) - 1 : 0;
		try {
			if ( $xlsx = SimpleXLSX::parse($inputFileName) ) {
				$rows = $xlsx->rows();
				$titles = array_shift($rows);
				$chrt_id = NULL;
				$article_wb = NULL;
				$barcode = NULL;
				$vendorCodeIndex = NULL;
				$products = [];
				$wb_products = [];
				foreach($titles as $key => $title) {
					if(preg_match('/\(chrt_id\)/',$title)) {
						$chrt_id = $key;
					} else if( preg_match('/Артикул WB/',$title) ) {
						$article_wb = $key;
					} else if ( preg_match('/Баркод/', $title) ) {
						$barcode = $key;
					} else if ( preg_match('/Артикул ИМТ/', $title)) {
						$vendorCodeIndex = $key;
					}
				}
				if(count(array_filter([$chrt_id, $article_wb, $barcode]))) {
					$article_wb_s = array_column($rows, $excel_field);
					$article_wb_s = array_filter($article_wb_s);
					$fields = $this->db->query("SELECT product_id, " . $compare_field . " FROM " . DB_PREFIX . "product  WHERE " .$compare_field." IN ('". implode("', '", $article_wb_s) ."');");
					if ($fields->num_rows) {
						foreach($fields->rows as $row) {
							$products[$row[$compare_field]] = $row['product_id'];
						}
						$product_id_keys = array_keys($products);
						$wb_oc_products_ids = $this->db->query("SELECT oc_product_id  FROM " . self::WB_PRODUCT_TABLE . " WHERE wb_store_id = '" . $wb_profile_id . "';");
						$oc_product_ids = array_map(function ($el) {
							return $el['oc_product_id'];
						}, $wb_oc_products_ids->rows);
						foreach($rows as $product) {
							if (in_array($product[$excel_field], $product_id_keys) && !in_array($products[$product[$excel_field]], $oc_product_ids)) {
								// $wb_products[] = [
								// 	'oc_product_id'	=>	$products[$product[$article_wb]],
								// 	'nmId'			=>	$product[$article_wb],
								// 	'chrt'			=>	$product[$chrt_id],
								// ];
								$wb_products[] = "('" . $products[$product[$excel_field]] ."', '" .$product[$article_wb] . "','" . $product[$chrt_id] . "','" . $product[$barcode] . "', '" . $wb_profile_id . "', '" . $product[$vendorCodeIndex] . "')";
							}
						}
						if(!empty($wb_products)) {
							$this->db->query("INSERT INTO  " . self::WB_PRODUCT_TABLE . " (oc_product_id, nmId, chrtId, barcode, wb_store_id, vendor_code) VALUES " . implode(',', $wb_products) . ";");
						}
					}
					echo json_encode( ['status' => true] );die();
				} 
			} 
		} catch(Exception $e) {
			echo json_encode( $e->getMessage() );die();	
		}
		echo json_encode( ['status' => false] );die();
	}

	public function createNewProduct($product)
	{
		$oc_product_id = $product['oc_product_id'];
		$nmId = !empty($this->request->post['wb_nmId']) ? $this->request->post['wb_nmId'] : NULL;
		$chrt_id = !empty($this->request->post['wb_chrt_id']) ? $this->request->post['wb_chrt_id']: NULL;
		$wb_product_id = !empty($this->request->post['wb_product_id']) ? $this->request->post['wb_product_id'] : NULL;
		$wb_import_compare_field = $this->config->get('wb_import_compare_field') ? $this->config->get('wb_import_compare_field') : 'sku';
		$exist = $this->db->query('SELECT * FROM ' . self::WB_PRODUCT_TABLE . " WHERE oc_product_id = " . $oc_product_id);
		if(!$exist->num_rows) {
			$this->db->query("INSERT INTO " . self::WB_PRODUCT_TABLE . " (oc_product_id, nmId, chrtId, wb_product_id) VALUES ('" . implode("', '", [$oc_product_id, $nmId, $chrt_id, $wb_product_id]) . "');");
			if(!is_null($nmId)) $this->db->query("UPDATE " . DB_PREFIX . "product SET " . $wb_import_compare_field . " = '" . $nmId . "' WHERE product_id = " . $oc_product_id);
		}
	}

	public function createWBApiProduct($addin, $object, $nomenclatures = [], $ids = [], $wb_stores = []) {
		if(!empty($addin)) {
			$wb_res = [];
			$products = $this->db->query("SELECT product_id, image, price, sku, model, wb_product_id FROM " . DB_PREFIX . "product LEFT JOIN " . self::WB_PRODUCT_TABLE . " ON " . self::WB_PRODUCT_TABLE . ".oc_product_id = " .  DB_PREFIX . "product.product_id WHERE product_id IN (" . implode(", ", $ids) . ");");
			$p_products = [];
			$uniq_ids = [];
			if ($products->num_rows) {
				foreach ($products->rows as $key => $producti) {
					if (!in_array($producti['product_id'], $uniq_ids)) {
						$uniq_ids[] = $producti['product_id'];
						$producti['barcode'] = '462'.substr(str_replace(['.',' '], ['', ''], microtime()), 1, 10);
						$p_products[] = $producti;
					}
				}
			}

			foreach($wb_stores as $wb_store) {
				$store = $this->getStoreByUid($wb_store);
				$wb_supplier_uuid = !empty($store['wb_supplier_uuid']) ? $store['wb_supplier_uuid'] : '{{supplierUUID}}';
				$d_data = [];
				$d_vendorCodes = [];

				if (!empty($ids)) {
					$this->wb_token = $store['wb_token_phone'];

					$barcodes = [];
					
					for($b_i = 0; $b_i < ceil(count($ids) / 5); $b_i++) {
						$barcodes = array_merge($barcodes, $this->getBarcodes(5, $wb_supplier_uuid));
					}
					$card = [];
					$kef = $this->config->get('wb_percent_product') ? intval($this->config->get('wb_percent_product')) : 0;
					foreach($p_products as $key => $product) {
						// if(is_null($product['wb_product_id'])) {
							//getCountry
							if (!empty($barcodes[$key])) {
								$product['barcode'] = $barcodes[$key];
							}
							$state = $this->getProductCountry($product['product_id']);
							
							$photo = [];
							if (!empty($product['image'])) $photo = $this->sendPhoto($product['image'], $wb_supplier_uuid);
							if (!empty($photo)) {
								if (!empty($nomenclatures[0])) $nomenclatures[0]['addin'][] = $photo;
								else $nomenclatures[0] = ['addin' => [$photo]];
							}
							$vendorCode = !empty($product['sku']) ? $product['sku'] : 'From-Oc-ID-'.$product['product_id'];
							$nmks = [
								'vendorCode'	=>	$vendorCode,
								'variations'	=>	[
									[
										"barcode"	=>	$product['barcode'],
										"addin"		=>	[
											[
												"type"	=> "Розничная цена",
												"params"	=>	[
													[
														"count" => $kef > 0 ? round((int)$product['price'] + ((int)$product['price'] / 100 * $kef)) : (int)$product['price'],
														"units"	=> "рублей"
													]
												]
											]
										]
									]
								],
							];
							if (!empty($nomenclatures)) {
								$nmks['addin']	=	$nomenclatures[0]['addin'];
							}
							$card[] = [
								'supplierVendorCode'	=>	!empty($product['sku']) ? str_replace(' ', '', $product['sku']).'-'.substr(str_replace(['.',' '], ['', ''], microtime()), 1, 2) : 'From-Oc-ID-'.$product['product_id'],
								'object'				=>	$object,
								'countryProduction'		=>	$state,
								'addin'					=>	$addin,
								'nomenclatures'			=>	[$nmks]
							];
							$d_data[$product['product_id']] = $product['barcode'];
							$d_vendorCodes[$product['product_id']] = $vendorCode;
						// }
					}
				} else {
					$card = [
						'supplierVendorCode'	=>	substr(str_replace(['.',' '], ['', ''], microtime()), 1, 13),
						'object'				=>	$object,
						'countryProduction'		=>	"Россия",
						'addin'					=>	$addin,
						'nomenclatures'			=>	$nomenclatures
					];
				}
				$wb_supplier_uuid = !empty($store['wb_supplier_uuid']) ? $store['wb_supplier_uuid'] : '{{supplierUUID}}';
				$data = [
					'id'		=>	uniqid('16'),
					'jsonrpc'	=>	'2.0',
					'params'	=>	[
						'supplierID'	=>	$wb_supplier_uuid,
						'card'			=>	$card,
					],
				];
				$path = !empty($ids) ? 'card/batchCreate' : 'card/create';
				if(!empty($store['wb_token_phone'])) {
					$this->wb_token = $store['wb_token_phone'];
					$dres = $this->sendData($path, $data, true, false);
					$wb_res[$store['wb_uuid']] = json_decode($dres, true);
					$wb_res[$store['wb_uuid']]['barcodes'] = $d_data;
					$wb_res[$store['wb_uuid']]['vendorCodes'] = $d_vendorCodes;
				}
			}
			return $wb_res;
		}
	}

	public function updateWBApiProduct($addin, $object, $nomenclatures = [], $ids = [], $object_id = null) {
		if(!empty($addin)) {
			$wb_supplier_uuid = $this->config->get('wb_supplier_uuid') ? $this->config->get('wb_supplier_uuid') : '{{supplierUUID}}';
			$product = $this->getProductById($object_id);
			$sku = !empty($product['sku']) ? str_replace(" ", '', $product['sku']) : substr(str_replace(['.',' '], ['', ''], microtime()), 1, 6);
			$card = [
				'id'					=>	is_null($object_id) ? uniqid('16') : $object_id,
				'supplierVendorCode'	=>	$sku . substr(str_replace(['.',' '], ['', ''], microtime()), 1, 2),
				'object'				=>	$object,
				'countryProduction'		=>	"Россия",
				'addin'					=>	$addin,
				'nomenclatures'			=>	$nomenclatures
			];
			$wb_supplier_uuid = $this->config->get('wb_supplier_uuid') ? $this->config->get('wb_supplier_uuid') : '{{supplierUUID}}';
			$data = [
				'id'		=>	is_null($object_id) ? uniqid('16') : $object_id,
				'jsonrpc'	=>	'2.0',
				'params'	=>	[
					'supplierID'	=>	$wb_supplier_uuid,
					'card'			=>	$card,
				],
			];
			$path = 'card/update';
			return json_decode($this->sendData($path, $data, true, false), true);
		}
	}

	public function createBatchProducts($addin, $object, $nomenclatures = [], $ids)
	{
		if(!empty($addin)) {
			$wb_supplier_uuid = $this->config->get('wb_supplier_uuid') ? $this->config->get('wb_supplier_uuid') : '{{supplierUUID}}';
			$cards = [];
			$data = [
				'id'		=>	uniqid('16'),
				'jsonrpc'	=>	'2.0',
				'params'	=>	[
					'supplierID'	=>	$wb_supplier_uuid,
					'card'			=>	[
						'supplierVendorCode'	=>	$wb_supplier_uuid,
						'object'				=>	$object,
						'countryProduction'		=>	"Россия",
						'addin'					=>	$addin
					],
				],
			];
			if (!empty($nomenclatures)) {
				$data['params']['card']['nomenclatures'] = $nomenclatures;
			}
			return json_decode($this->sendData('card/create', $data, true, false), true);
		}
	}

	public function getWBgetProfile() {
		$data = [
			"method"	=>	"getUserSuppliers",
			"jsonrpc"	=>	"2.0",
			"id"		=>	"json-rpc_1",
		];
		$this->sendPortal('ns/suppliers/suppliers-portal-eu/suppliers/getUserSuppliers', $data);
	}
	public function sendPortal($path, $data = "{}", $method = "POST") {
		$curl = curl_init();
		$url = self::SUPPLIERS_PORTAL_URL . $path;
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				"Cookie: WBToken=" . ($this->wb_token ? $this->wb_token : $this->config->get('wb_token_phone')),
				"Content-Type: application/json"
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		if (!empty($response)) {
			$result = json_decode($response);
			if (!empty($result['result']) && !empty($result['result']['suppliers']) && !empty($result['result']['suppliers'][0])) {
				$supplier = $result['result']['suppliers'][0];
				$this->load->model('setting/setting');
				$this->model_setting_setting->editSetting('wb_supplier_uuid', ['wb_supplier_uuid' => $supplier['id']]);
			}
		}
	}

	public function createBatchWBProducts($wb_products, $withWBProductId){
		return $this->db->query("INSERT INTO  " . self::WB_PRODUCT_TABLE . " (oc_product_id, nmId, chrtId " . ($withWBProductId ? ", wb_product_id" : '') . ", wb_store_id, barcode, vendor_code) VALUES " . implode(',', $wb_products) . ";");
	}

	public function getWBProductById($id) {
		$wb_supplier_uuid = $this->getWBStoreByWBProductId($id);
		$supplier_id = !empty($wb_supplier_uuid) ? $wb_supplier_uuid['wb_supplier_uuid'] : '{{supplierUUID}}';
		return $this->sendData('card/cardById', [
		//return $this->sendData('card/delete', [
			"id"		=>	uniqid('16'),
			"jsonrpc"	=>	"2.0",
			"params"	=>	[
				"cardID"		=>	$id,
				"supplierID"	=>	$supplier_id,
			]
		]);
	}

	public function deleteWBProduct($product, $store) {
		$supplier_id = $store['wb_supplier_uuid'];
		$this->token = $store['wb_token_phone'];
		$res = json_decode($this->sendData('card/deleteNomenclature', [
			"id"		=>	uniqid('16'),
			"jsonrpc"	=>	"2.0",
			"params"	=>	[
				"nomenclatureID"	=>	$product['nmId'],
				"supplierID"		=>	$supplier_id,
			]
		]), true);
		// if (!isset($res['error']) && isset($res['result'])) {
			$sql = "DELETE FROM " . self::WB_PRODUCT_TABLE . " WHERE wb_product_id = '" . $product['wb_product_id'] . "';";
			$this->db->query($sql);
		// }
	}

	public function getWBProductByImtId($store, $imtId) {
		$supplier_id = $store['wb_supplier_uuid'];
		$this->wb_token = $store['wb_token_phone'];
		$res = json_decode($this->sendData('card/cardByImtID', [
			"id"		=>	uniqid('16'),
			"jsonrpc"	=>	"2.0",
			"params"	=>	[
				"imtID"				=>	intval($imtId),
				"supplierID"		=>	$supplier_id,
			]
		]), true);
		return $res;
	}


	//Удаление всех карточек из WB найденных в БД
	public function delWBProductById() {
		$db_cards = $this->db->query("SELECT wb_product_id FROM " . self::WB_PRODUCT_TABLE . ";");
		foreach($db_cards->rows as $db_card){
			$wb_supplier_uuid = $this->getWBStoreByWBProductId($db_card['wb_product_id']);
			$supplier_id = !empty($wb_supplier_uuid) ? $wb_supplier_uuid['wb_supplier_uuid'] : '{{supplierUUID}}';
			$this->sendData('card/delete', [
				"id"		=>	uniqid('16'),
				"jsonrpc"	=>	"2.0",
				"params"	=>	[
					"cardID"		=>	$db_card['wb_product_id'],
					"supplierID"	=>	$supplier_id,
				]
			]);
		}
	}
	
	public function syncNmCrt() {
		// $this->refreshtoken();
		$wb_stores = $this->getStores();
		$this->attributes = $this->getAttributes();
		$this->load->model('catalog/category');
		$this->categories = $this->model_catalog_category->getCategories();
		$this->load->model('catalog/option');
		$this->options = $this->model_catalog_option->getOptions();
		$this->load->model('catalog/manufacturer');
		$this->manufactures = $this->model_catalog_manufacturer->getManufacturers();
		$wb_compare_field = $this->config->get('wb_import_compare_field');
		if (!empty($wb_stores)) {
			foreach($wb_stores as $store) {
				if(!is_array($store)) $store = (array)$store;
				if (!empty($store['wb_supplier_uuid']) && !empty($store['wb_token_phone'])) {
					$this->token = $store['wb_token_phone'];
					$this->wb_token = $this->token;
					$wb_supplier_uuid = $store['wb_supplier_uuid'];
					$wb_product_create = !empty($store['wb_product_create']) && $store['wb_product_create'] == 'on';
					$wb_product_group = !empty($store['wb_product_group']) && $store['wb_product_group'] == 'on';
					$data = [
						"id"		=>	1,
						"jsonrpc"	=>	"2.0",
						"params"	=>	[
							"supplierID"	=> $wb_supplier_uuid,
							"query" 		=> [
								"limit" => 1000
							],
						]
					];
					$data = json_decode($this->sendData('card/list', $data, true, false), true);
					if(!empty($data['result']) && !empty($data['result']['cards'])) {
						$cards = $data['result']['cards'];
						$cards = array_filter($cards, function($el){
							return !empty($el['nomenclatures']);
						});
						if (!empty($cards)) {
							$barcodes = [];
							$vendorCodes = [];
							$c_cards = [];
							$nmId_vendorCode = [];
							foreach($cards as $c_cart) {
								$n_nomenclatures = array_filter($c_cart['nomenclatures'], function($eli) {
									return !empty($eli['variations']);
								});
								$b_codes = [];
								foreach($n_nomenclatures as $nomenc) {
									$v_code = $nomenc['vendorCode'];
									$vendorCodes[] = $v_code;
									$nomenc['wb_id'] = $c_cart['id'];
									$nomenc['imtId'] = $c_cart['imtId'];
									$nomenc['supplierVendorCode'] = $c_cart['supplierVendorCode'];
									$nomenc['object'] = $c_cart['object'];
									$nomenc['parent'] = $c_cart['parent'];
									$nomenc['options'] = [];
									$nomenc['country']	= $c_cart['countryProduction'];
									foreach($c_cart['addin'] as $addin_d) {
										if ($addin_d['type'] == 'Бренд') $nomenc['brand'] = $addin_d['params'][0]['value'];
										if ($addin_d['type'] == 'Наименование') $nomenc['name'] = $addin_d['params'][0]['value'];
										if ($addin_d['type'] == 'Описание') $nomenc['description'] = $addin_d['params'][0]['value'];
										if ($addin_d['type'] == 'Глубина упаковки') $nomenc['length'] = $addin_d['params'][0]['count'];
										if ($addin_d['type'] == 'Ширина упаковки') $nomenc['width'] = $addin_d['params'][0]['count'];
										if ($addin_d['type'] == 'Высота упаковки') $nomenc['height'] = $addin_d['params'][0]['count'];
									}
									if(!empty($nomenc['addin'])) {
										foreach($nomenc['addin'] as $nomenc_addin) {
											if ($nomenc_addin['type'] == 'Фото') {
												$nomenc['images'] = [];
												foreach($nomenc_addin['params'] as $ki => $photo) {
													if ($ki == 0) {
														$nomenc['photo'] = $photo['value'];
													} else {
														$nomenc['images'][] = $photo['value'];
													}
												}
											} else if ($nomenc_addin['type'] === 'Основной цвет') {
												$nomenc['color'] = $nomenc_addin['params'][0]['value'];
											} 
											$nomenc['options'][$nomenc_addin['type']] = $nomenc_addin['params'];
										}
									}
									$variations = $nomenc['variations'];
									$addin_nds = array_column($variations, 'addin');
									$b_codes = array_column($variations, 'barcodes');
									$nomenc['barcodes'] = [];
									foreach($b_codes as $b_code) {
										$nomenc['barcodes'] = array_merge($nomenc['barcodes'], $b_code);
									}
									foreach($addin_nds as $addin_nd) {
										foreach($addin_nd as $ad_nd) {
											if ($ad_nd['type'] == 'Розничная цена') {
												$nomenc['price'] = $ad_nd['params'][0]['count'];
											}
											if ($ad_nd['type'] == 'Размер') {
												$nomenc['size'] = $ad_nd['params'][0]['value'];
												$nomenc['options'][$ad_nd['type']] = $ad_nd['params'];
											}
										}	
									}
									$nmId_vendorCode[$nomenc['nmId']] = $v_code;
									$c_cards[$v_code] = $nomenc;
									$b_codes = array_column($nomenc['variations'], 'barcode');
								}
								$barcodes = array_merge($barcodes, $b_codes);
							}
							if (!empty($vendorCodes)) {
								$cards_ids = array_column($c_cards, 'id');
								$sql = "SELECT * FROM " . self::WB_PRODUCT_TABLE . " WHERE vendor_code IN ('" . implode("', '", $vendorCodes) . "') AND wb_store_id = '" . $store['wb_uuid'] . "';";
								$db_cards = $this->db->query($sql);
								if ($db_cards->num_rows > 0) {
									$update_data = [];
									foreach($db_cards->rows as $db_card) {
										$wb_card = NULL;
										$vendorcode = $db_card['vendor_code'];
										if (!empty($nmId_vendorCode[$db_card['nmId']])) {
											$wb_card = !empty($c_cards[$nmId_vendorCode[$db_card['nmId']]]) ? $c_cards[$nmId_vendorCode[$db_card['nmId']]] : NULL;
										} else if (!empty($c_cards[$db_card['vendor_code']])) {
											$wb_card = $c_cards[$vendorcode];
										}
										if($wb_card) {
											$vendorcode = $db_card['vendor_code'];
											$wb_card = $c_cards[$vendorcode];
											$wb_product_id = $wb_card['wb_id'];
											$nmId = $wb_card['nmId'];
											$imtId = $wb_card['imtId'];
											$barcode = $wb_card['barcodes'][0];
											$brand = !empty($wb_card['brand']) ? $wb_card['brand'] : '';
											$wb_name = !empty($wb_card['name']) ? $wb_card['name'] : '';
											$chrt_id = 0;
											if(!empty($wb_card['variations']) && !empty($wb_card['variations'][0]['chrtId'])) {
												$chrt_id = $wb_card['variations'][0]['chrtId'];
											}
											$update_data[$db_card['id']] = [
												'wb_product_id'	=>	$wb_product_id,
												'nmId'			=>	$nmId,
												'chrtId'		=>	$chrt_id,
												'wb_name'		=>	$brand . ' ' . $wb_name,
												'imtId'		    =>	$imtId,
												'barcode'		=>	$barcode,
											];
											unset($c_cards[$vendorcode]);
										}
									}
									if (!empty($update_data)) {
										foreach($update_data as $wb_id => $u_data) {
											$update_query = "UPDATE " . self::WB_PRODUCT_TABLE . " SET nmId = '" . $u_data['nmId'] . "', chrtId = '" . $u_data['chrtId'] . "',imtId = '" . $u_data['imtId'] . "', wb_product_id = '" . $u_data['wb_product_id'] . "', wb_name = '" . $u_data['wb_name'] ."', barcode = '" . $u_data['barcode'] .  "' WHERE id = '" . $wb_id . "' AND wb_store_id = '" . $store['wb_uuid'] . "'; ";
											$this->db->query($update_query);
										}
									}
								}
								if (!empty($c_cards)) {
									$to_create = [];
									$productListSQL = "SELECT product_id, {$wb_compare_field} FROM " . DB_PREFIX . "product ";
									if ($db_cards->num_rows > 0) {
										$oc_ids = array_filter(array_unique(array_column($db_cards->rows, 'oc_product_id')));
										if (!empty($oc_ids)) {
											$productListSQL .= "WHERE product_id NOT IN ('". implode("','", $oc_ids) ."')";
										}
									}
									$productListQuery = $this->db->query($productListSQL);
									$productListData = [];
									foreach($productListQuery->rows as $rrow) {
										$productListData[$rrow[$wb_compare_field]] = $rrow['product_id'];
									}
									if($wb_product_group) {
									// GROUP CARDS BY $WB_CARD_ID START
										$grouped = array();
										foreach ($c_cards as $element) {
											$grouped[$element['wb_id']][] = $element;
										}
										$existSupplierVendorCodeProduct = array();
										$productModelsQuery = $this->db->query("SELECT model, product_id FROM " .DB_PREFIX. "product WHERE model");
										if ($productModelsQuery->num_rows) {
											$productModelRows = $productModelsQuery->rows;
											foreach($productModelRows as $row) {
												$existSupplierVendorCodeProduct[$row['model']] = $row['product_id'];
											}
										}
										foreach($grouped as $wb_key => $group) {
											if (!empty($group)) {
												if (in_array($group[0]['supplierVendorCode'],array_keys($existSupplierVendorCodeProduct))) {
													$product_id = $existSupplierVendorCodeProduct[$group[0]['supplierVendorCode']];
												} else {
													$product_id = 'NULL';
												}
												if ($wb_product_create && $product_id == 'NULL') {
													$options = array();
													$images = array();
													foreach($group as $g_element) {
														if (!empty($g_element['options'])) {
															foreach($g_element['options'] as $opt_name => $opt) {
																if (empty($options[$opt_name])) $options[$opt_name] = [];
																foreach($opt as $oopt_key => $oopt) {
																	$options[$opt_name][] = $oopt;
																}
															}
														}
														if (!empty($g_element['images'])) {
															foreach($g_element['images'] as $img) {
																$images[] = $img;
															}
														}
													}
													if(count($group)> 1) {
														$dddd = 4;
													}
													if (!empty($options)) $group[0]['options'] = $options;
													if (!empty($images)) $group[0]['images'] = $images;
													
													$product_id = $this->createNewOcProduct($group[0]);
												}
												$wb_product_id = $wb_key;
												$imtId = $group[0]['imtId'];
												foreach($group as $group_element) {
													$nmId = $group_element['nmId'];
													$chrt_id = 0;
													if(!empty($group_element['variations']) && !empty($group_element['variations'][0]['chrtId'])) {
														$chrt_id = $group_element['variations'][0]['chrtId'];
													}
													$brand = !empty($group_element['brand']) ? $group_element['brand'] : '';
													$wb_name = !empty($group_element['name']) ? $group_element['name'] : '';
													$w_wb_name = $brand . ' ' . $wb_name;
													$vendorcode = $group[0]['vendorCode'];
													$to_create[] = "(NULLIF('{$product_id}', 'NULL'), '{$nmId}', '{$chrt_id}', '{$imtId}', '{$store['wb_uuid']}', '{$wb_product_id}', '{$vendorcode}', '{$w_wb_name}')";
												}
											}
										}
									// GROUP CARDS BY $WB_CARD_ID END
									} else {
										foreach($c_cards as $vendorcode => $wb_card) {
											$wb_product_id = $wb_card['wb_id'];
											$imtId = $wb_card['imtId'];
											$nmId = $wb_card['nmId'];
											$chrt_id = 0;
											$brand = !empty($wb_card['brand']) ? $wb_card['brand'] : '';
											$wb_name = !empty($wb_card['name']) ? $wb_card['name'] : '';
											$w_wb_name = $brand . ' ' . $wb_name;
											$barcode = $wb_card['barcodes'][0];
											$product_id = !empty($productListData[$vendorcode]) ? $productListData[$vendorcode] : 'NULL';
											if ($wb_product_create && $product_id == 'NULL') {
												$product_id = $this->createNewOcProduct($wb_card);
											}
											if(!empty($wb_card['variations']) && !empty($wb_card['variations'][0]['chrtId'])) {
												$chrt_id = $wb_card['variations'][0]['chrtId'];
											}
											$to_create[] = "(NULLIF('{$product_id}', 'NULL'), '{$nmId}', '{$chrt_id}', '{$imtId}', '{$store['wb_uuid']}', '{$wb_product_id}', '{$vendorcode}', '{$w_wb_name}, '{$barcode}')";
										}
									}
									if (!empty($to_create)) {
										$insertSQL = "INSERT INTO " . self::WB_PRODUCT_TABLE . " (`oc_product_id`, `nmId`, `chrtId`, `imtId`, `wb_store_id`, `wb_product_id`, `vendor_code`, `wb_name`, `barcode`) VALUES " . implode(',', $to_create);
										$this->db->query($insertSQL);
									}
								}
							}
						}
					}
				}
			}
		}
	}

	public function sendPhoto($path, $wb_supplier_uuid) {
		$image_url = DIR_IMAGE . $path;
		$url = self::WILDBERRIES_API_URL . 'card/upload/file/multipart';
		$image_uuid = $this->v4();
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('data'=> new CURLFILE($image_url)),
			CURLOPT_HTTPHEADER => array(
			  'Cookie: WBToken=' . ($this->wb_token ? $this->wb_token : $this->config->get('wb_token_phone')),
			  'X-Supplier-ID: ' . $wb_supplier_uuid,
			  'X-File-Id: ' . $image_uuid
			),
		  ));
		$result = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		return $httpcode == '200' ? [
			"type"		=>	"Фото",
			"params"	=>	[
				[
					"value"	=>	$image_uuid,
					"units"	=>	mime_content_type(DIR_IMAGE . $path)
				]
			]
		] : [];
	}

	public function v4() {
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	
		  // 32 bits for "time_low"
		  mt_rand(0, 0xffff), mt_rand(0, 0xffff),
	
		  // 16 bits for "time_mid"
		  mt_rand(0, 0xffff),
	
		  // 16 bits for "time_hi_and_version",
		  // four most significant bits holds version number 4
		  mt_rand(0, 0x0fff) | 0x4000,
	
		  // 16 bits, 8 bits for "clk_seq_hi_res",
		  // 8 bits for "clk_seq_low",
		  // two most significant bits holds zero and one for variant DCE1.1
		  mt_rand(0, 0x3fff) | 0x8000,
	
		  // 48 bits for "node"
		  mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}

	public function getProductCountry($product_id) {
		$this->load->model('catalog/product');
		$attributes = $this->model_catalog_product->getProductAttributes($product_id);
		$wb_state_attribute = $this->config->get('wb_state_attribute');
		$attrs = [];
		foreach($attributes as $group_data) {
			if(!empty($group_data['attribute'])) {
				foreach($group_data['attribute'] as $attr) {
					$attrs[$attr['name']] = $attr['text'];
				}
			}
		}
		return !empty($attrs) && !empty($attrs[$wb_state_attribute]) ? $attrs[$wb_state_attribute] : 'Россия';
	}

	public function getStores() {
		$stores = $this->config->get('wb_settings');
		return !empty($stores) ? $fstores = array_filter($stores, function($el) {
			if (!is_array($el)) $el = (array)$el;
			return $el['wb_status'] !== '0';
		}) : [];
	}

	public function getStoreByUid($id) {
		$stores = $this->getStores();
		if (!empty($stores)) {
			$fstores = array_filter($stores, function($el) use ($id) {
				return $el['wb_uuid'] === $id;
			});
			if (!empty($fstores)) return array_shift($fstores);
		};
		return [];
	}

	public function getWBProductsByStoreID($stores) {
		if (is_string($stores)) {
			$stores = [$stores];
		}
		$sql = 'SELECT * FROM wb_product WHERE wb_store_id IN (' . implode(',', $stores) . ')';
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getWBStoreByWBProductId($id) {
		$sql = "SELECT wb_store_id FROM wb_product WHERE wb_product_id = '" . $id . "';";
		$store = $this->db->query($sql);
		$stores = $this->config->get('wb_settings');
		$res = [];
		if (!empty($store) && !empty($stores)) {
			$strs = array_filter($stores, function($el) use ($store) {
				return $el['wb_uuid'] == $store['wb_store_id'];
			});
			if(!empty($strs)) $res = $strs[0];
		}
		return $res;
	}


	public function getBarcodes($count, $wb_supplier_uuid) {
		$url = 'card/getBarcodes';
		$params = [
			"id" 		=>	$this->v4(),
			"jsonrpc"	=>	"2.0",
			"params"	=>	[
				"quantity"		=>	$count,
				"supplierId"	=>	$wb_supplier_uuid,
			],
		];
		$res = json_decode($this->sendData($url, $params), true);
		return !json_last_error() && !empty($res['result']) && !empty($res['result']['barcodes']) ? $res['result']['barcodes'] : [];
	}

	public function getAttributes($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(ad.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
	
		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}
	
		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);  
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];   
		} else {
			$sql .= " ORDER BY attribute_group, ad.name";   
		}   
	
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}               
	
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}   
	
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}   
	
		$query = $this->db->query($sql);
	
		return $query->rows;
	}
	public function createNewOcProduct($data) {
		$this->load->model('localisation/language');
		$this->load->model('catalog/product');
		$languages = $this->model_localisation_language->getLanguages();
		$field = $this->config->get('wb_import_compare_field') ? $this->config->get('wb_import_compare_field') : 'sku';
		$vendorCode = $data['vendorCode'];
		$object = $data['object'];
		$parent = $data['parent'];
		$options = !empty($data['options']) ? $data['options'] : [];
		$country = !empty($data['country']) ? $data['country'] : '';
		$size = !empty($data['size']) ? $data['size'] : '';
		$color = !empty($data['color']) ? $data['color'] : '';
		$supplierVendorCode = $data['supplierVendorCode'];
		$brand = !empty($data['brand']) ? $data['brand'] : ' ';
		$name = !empty($data['name']) ? $data['name'] : ' ';
		$price = !empty($data['price']) ? $data['price'] : 0;
		$length = !empty($data['length']) ? intval($data['length']) : 0;
		$width = !empty($data['width']) ? intval($data['width']) : 0;
		$height = !empty($data['height']) ? intval($data['height']) : 0;
		$description = !empty($data['description']) ? $data['description'] : ' ';
		if (!empty($data['photo'])) {
			$image = $this->upload($data['photo']);
		} else {
			$image = 'no_image.png';
		}
		foreach(['model', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'sku',] as $ff) {
			if ($ff != $field) $data_fields[$ff] = '';
		}
		/** PRODUCT MANUFACTURER START */
		$exist_manufacturer = array_filter($this->manufactures, function($man) use ($brand) {
			return $man['name'] === $brand;
		});
		if (!empty($exist_manufacturer)) {
			$mfirst = array_keys($exist_manufacturer)[0];
			$manufacturer_id = $exist_manufacturer[$mfirst]['manufacturer_id'];
		} else {
			$this->load->model('catalog/manufacturer');
			if(!empty($brand)) {
				$manufacturer_id = $this->model_catalog_manufacturer->addManufacturer([
					'manufacturer_description'	=> [
						'1' => ['name'	=>	$brand, 'description' => '', 'meta_title' => '', 'meta_h1' => '', 'meta_description' => '', 'meta_keyword' => '', ],
						'2' => ['name'	=>	$brand, 'description' => '', 'meta_title' => '', 'meta_h1' => '', 'meta_description' => '', 'meta_keyword' => '', ],
					],
					'name'					=>  $brand,
					'sort_order'			=>	0,
				]);
				$this->manufactures[] = ['manufacturer_id' => $manufacturer_id, 'name' => $brand];
			} else {
				$manufacturer_id = 0;
			}
		}
		/** PRODUCT MANUFACTURER END */
		$productData = array_merge(array(
			'name'					=>	$name,
			$field					=>	$vendorCode,
			'price'					=>	$price,
			'location'				=>	'',
			'quantity'				=>	1,
			'minimum'				=>	1,
			'subtract'				=>	0,
			'stock_status_id'		=>	0,
			'date_available'		=>	date('Y-m-d'),
			'manufacturer_id'		=>	$manufacturer_id,
			'shipping'				=>	1,
			'points'				=>	0,
			'weight'				=>	0,
			'weight_class_id'		=>	1,
			'length'				=>	$length,
			'width'					=>	$width,
			'height'				=>	$height,
			'length_class_id'		=>	1,
			'status'				=>	1,
			'tax_class_id'			=>	0,
			'sort_order'			=>	0,
			'keyword'				=>	false,
			'image'					=>	$image,
			'product_store'			=>	[0],
		), $data_fields);
		$productData['model'] = $supplierVendorCode;
		if (empty($productData['sku'])) $productData['sku'] = $vendorCode;
		foreach($languages as $lan) {
			$productData['product_description'][$lan['language_id']] = [
				'name'				=>	$brand . ' ' . $name,
				'description'		=>	$description,
				'meta_title'		=>	$name,
				'meta_h1'			=>	$name,
				'meta_description'	=>	'',
				'meta_keyword'		=>	'',
				'tag'				=>	'',
			];
		}
		/** PRODUCT OPTIONS START */
		if (!empty($options)) {
			$this->load->model('catalog/option');
			$productData['product_option'] = [];
			if (!is_array($this->options)) $this->options = [];
			foreach($options as $name_option => $value_option) {
				if ($name_option == 'Фото') continue;
				$exist_option = array_filter($this->options, function($opt) use ($name_option) {
					return $opt['name'] === $name_option;
				});
				if (!empty($exist_option)) {
					$ofirst = array_keys($exist_option)[0];
					$option_id = $exist_option[$ofirst]['option_id'];
				} else {
					$option_id = $this->model_catalog_option->addOption([
						'option_description'	=> [
							'1' => ['name'	=>	$name_option, ],
							'2' => ['name'	=>	$name_option, ],
						],
						'type'					=>	$name_option == 'Фото' ? 'image' : 'radio',
						'sort_order'			=>	0,
					]);
					$this->options[] = ['option_id' => $option_id, 'name' => $name_option];
				}
				$opt = [
					'type'		=> 	$name_option == 'Фото' ? 'image' : 'radio',
					'option_id'	=>	$option_id,
					'product_option_value'	=> [],
					'required'	=>	0,
				];
				$optiin = $this->model_catalog_option->getOptionValues(1);

				foreach($value_option as $keyo => $par) {
					$ovi_params = !empty($par['count']) ? $par['count'] : $par['value'];
					$exist_option_value = array_filter($optiin, function ($ovi) use($ovi_params) {
						return $ovi['name'] === $ovi_params;
					});

					if (!empty($exist_option_value)) {
						$ovifirst = array_keys($exist_option_value)[0];
						$option_value_id = $exist_option_value[$ovifirst]['option_value_id'];
					} else {
						$option_value_id = $this->addOptionValues($option_id, [
							'image'	=>	'',
							'sort_order'	=> 0,
							'option_value_description'	=>	[
								'1'	=>	['name'	=>	$ovi_params,],
								'2'	=>	['name'	=>	$ovi_params,] 
							],
						]);
					}
					$opt['product_option_value'][]	= [
						'option_value_id'	=>	$option_value_id,
						'quantity'			=>	1,
						'subtract'			=>	1,
						'price'				=>	0.0000,
						'price_prefix'		=>	'+',
						'points'			=>	0,
						'points_prefix'		=>	'+',
						'weight'			=>	0.00,
						'weight_prefix'		=>	'+',
					];
				}
				$productData['product_option'][] = $opt;
			}
		}
		/** PRODUCT OPTION END */
		/** PRODUCT CATEGORY START */
		$this->load->model('catalog/category');
		$exist_main_category = array_filter($this->categories, function($cat) use ($parent) {
			return $cat['name'] == $parent;
		});
		if (empty($exist_main_category)) {
			$main_category = $this->model_catalog_category->addCategory([
				'parent_id' 	=>	'0',
				'column'		=>	'1',
				'sort_order'	=>	'0',
				'status'		=> 	'1',
				'top'			=>	'1',
				'category_store'	=> [0],
				'category_description'	=> [
					'1' => ['name'	=>	$parent, 'description' => '', 'meta_title' => $parent, 'meta_h1' => '', 'meta_description' => '', 'meta_keyword' => ''],
					'2' => ['name'	=>	$parent, 'description' => '', 'meta_title' => $parent, 'meta_h1' => '', 'meta_description' => '', 'meta_keyword' => ''],
				],
			]);
			$this->categories[] = ['category_id' => $main_category, 'name' => $parent];
		} else {
			$mfirst = array_keys($exist_main_category)[0];
			$main_category = $exist_main_category[$mfirst]['category_id'];
		}
		$exist_c_category = array_filter($this->categories, function($cat) use ($object) {
			return $cat['name'] == $object;
		});
		if (empty($exist_c_category)) {
			$c_category = $this->model_catalog_category->addCategory([
				'parent_id' 	=>	$main_category,
				'column'		=>	'1',
				'sort_order'	=>	'0',
				'status'		=> 	'1',
				'top'			=>	'1',
				'category_description'	=> [
					'1' => ['name'	=>	$object, 'description' => '', 'meta_title' => $object, 'meta_h1' => '', 'meta_description' => '', 'meta_keyword' => ''],
					'2' => ['name'	=>	$object, 'description' => '', 'meta_title' => $object, 'meta_h1' => '', 'meta_description' => '', 'meta_keyword' => ''],
				],
			]);
			$this->categories[] = ['category_id' => $c_category, 'name' => $object];
		} else {
			$ffirst = array_keys($exist_c_category)[0];
			$c_category = $exist_c_category[$ffirst]['category_id'];
		}
		$productData['product_category'] = [$main_category, $c_category];
		/** PRODUCT CATEGORY END */
		/** PRODUCT ATTRIBUTES START */
		$attrs = [];
		$compare_attrs = array(
			// 'Размер'	=>	$size,
			'Предмет'	=>	$object . ' / ' . $parent,
			'Цвет'		=>	$color,
			'Страна производитель' => $country,
		);
		foreach ($compare_attrs as $c_key => $c_attr) {
			if (!empty($c_attr)) {
				$exist_attribute = array_filter($this->attributes, function($atr) use ($c_key) {
					return $atr['name'] === $c_key;
				});
				if (!empty($exist_attribute)) {
					$afirst = array_keys($exist_attribute)[0];
					$attribute_id = $exist_attribute[$afirst]['attribute_id'];
				} else {
					$this->load->model('catalog/attribute');
					$attribute_id = $this->model_catalog_attribute->addAttribute([
						'attribute_group_id'	=>	1,	
						'sort_order'			=>	0,
						'attribute_description'	=>	[
							'1' => ['name'	=>	$c_key, ],
							'2' => ['name'	=>	$c_key, ],
						],
					]);
					$this->attributes[] = ['name' => $c_key, 'attribute_id' => $attribute_id];
				}
				$attrs[] = [
					'attribute_id'	=>	$attribute_id,
					'product_attribute_description' => [
						'1' => ['text' => $c_attr],
						'2'	=> ['text' => $c_attr],
					],
				];
			}
		}
		if (!empty($attrs)) {
			$productData['product_attribute'] = $attrs;
		}
		/** PRODUCT ATTRIBUTES END */
		// Attempt to pass the assoc array to the add Product method
		$oc_product = $this->model_catalog_product->addProduct($productData);
		if ($oc_product > 0 && !empty($data['images'])) {
			$imageList = [];
			foreach($data['images'] as $img) {
				$image_path = $this->upload($img);
				if (strlen($image_path)) {
					$imageList[] = "('". implode("','",array($oc_product, $image_path)) . "')";
				}
			}
			if (!empty($imageList)) {
				$imageSql = 'INSERT INTO ' . DB_PREFIX . "product_image (product_id, image) VALUES " . implode(",", $imageList);
				$this->db->query($imageSql);
			}
		}
		return $oc_product > 0 ? $oc_product : 'NULL';
	}
	public function upload($image) {
		try {
			$exp_image = explode('/', $image);
			$filename = end($exp_image);
			$load_path = DIR_IMAGE . 'catalog/' . $filename;
			if (copy($image, $load_path)) return 'catalog/' . $filename;
		} catch(\Exception $e) {}
		return '';
	}
}