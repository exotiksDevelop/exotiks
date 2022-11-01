<?php
class ControllerModuleWildberries extends Controller {
	private $error = array();
	private $is_connected;

	public function index() {
		// phpinfo();die();
		$this->load->language('module/wildberries');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!empty($this->request->post['wb_settings'])) {
				$this->request->post['wb_settings'] = json_decode(htmlspecialchars_decode($this->request->post['wb_settings']));
				$this->model_setting_setting->editSetting('wb_settings', [
					'wb_settings' => $this->request->post['wb_settings'],
				]);
			}
			if (empty($this->request->post['wb_order_status'])) {
				$this->request->post['wb_order_status'] = '[]';
			} else {
				$this->request->post['wb_order_status'] = json_encode($this->request->post['wb_order_status']);
			}
			if (!empty($this->request->post['wb_upload_order'])) {
				$this->request->post['wb_order_cron'] = '* * * * * ' . '/usr/bin/php '.DIR_SYSTEM.'cron/sync_order.php > /dev/null 2>&1';
			}
			if (!empty($this->request->post['wb_product_cron'])) {
				$this->request->post['wb_product_cron'] = '* * * * * ' . '/usr/bin/php '.DIR_SYSTEM.'cron/sync_product.php > /dev/null 2>&1';

			}
			$this->request->post['wb_http_host'] = $_SERVER['HTTP_HOST'];
			$this->model_setting_setting->editSetting('wb', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/wildberries', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		// LANGUAGES
		$data_translates = [
			'heading_title', 'text_edit', 'text_enabled', 'text_disabled', 'text_production', 'text_test', 'auth_head', 'content_api_head', 'product_sync_head', 'order_sync_head', 'order_log_head', 'status_sync_head', 
			'button_auth_phone', 'button_auth_phone_code', 'button_refresh_token', 'button_reauth_phone', 'button_clear', 'button_status_add', 'button_remove', 'entry_sort_order', 'entry_import_compare_field', 'entry_import_profile', 'entry_supplier_uuid', 'entry_import_excel_compare_field', 'entry_status', 'entry_store', 'work_status', 'order_status_list', 'entry_w_token', 'entry_t_token', 'entry_upload_order', 'entry_upload_product', 'entry_upload_sync_product', 'entry_order_cron', 'entry_product_cron', 'entry_sync_cron', 'entry_phone', 'entry_phone_code', 'entry_order_status_id', 'entry_order_status_name', 'entry_registration_token', 'button_save', 'button_cancel', 'entry_registration_email', 'entry_percent_product', 'entry_store_name', 'entry_state_attribute',
			'entry_wb_token_phone', 'entry_create_products', 'entry_create_group', 
		];

		foreach ($data_translates as $d_translate) {
			$data[$d_translate] = $this->language->get($d_translate);
		}

		$data['phone_token_status'] = $this->config->get('phone_token_status') == 1 && !empty($this->config->get('wb_token_phone')) ? $this->language->get('phone_token_status_ok') : $this->language->get('phone_token_status_no');
		$data['phone_token_value'] = !empty($this->config->get('phone_token_status')) ? $this->config->get('phone_token_status') : 0;
		$data['is_connected'] = !!$this->is_connected;
		// ERRORS
		$data_errors = [
			'store', 't_token', 'w_token', 'warning',
		];
		foreach($data_errors as $d_error) {
			if(isset($this->error[$d_error])) {
				$data['error_' . $d_error] = $this->error[$d_error];
			} else {
				$data['error_' . $d_error] = '';
			}
		}

		// BREADCRUMBS
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/wildberries', 'token=' . $this->session->data['token'], true)
		);
		// Routes and URLS
		$data_urls = [
			'action'	=>	'module/wildberries',
			'clear'		=>	'module/wildberries/clear',
			'auth'		=>	'module/wildberries/auth',
			'auth_code'	=>	'module/wildberries/authcode',
			'import'	=>	'module/wildberries/import',
			'r_token'	=>	'module/wildberries/refreshtoken',
		];
		foreach ($data_urls as $d_route => $d_url) {
			$data[$d_route] = $this->url->link($d_url, 'token=' . $this->session->data['token'], true);
		}
		$data['cancel'] = $this->url->link('catalog/wildberries', 'token=' . $this->session->data['token'] . '&type=module', true);
		// DATA VARIABLES FROM DB
		$form_array = [
			'wb_status', 'wb_registration_token', 'wb_supplier_uuid', 'wb_store', 'wb_import_compare_field', 'wb_import_excel_compare_field', 'wb_work_status',
			'wb_w_token', 'wb_t_token', 'wb_phone', 'wb_phone_code', 'wb_token_phone', 'wb_refresh_token', 'wb_upload_order', 'wb_upload_product', 'wb_registration_email', 
			'wb_percent_product', 'wb_state_attribute', 'wb_product_create', 'wb_product_group',
		];
		foreach ($form_array as $item) {
			if (isset($this->request->post[$item])) {
				$data[$item] = $this->request->post[$item];
			} else {
				$data[$item] = $this->config->get($item);
			}
		}
		
		if (isset($this->request->post['wb_order_cron'])) {
			$data['wb_order_cron'] = '* * * * * ' . '/usr/bin/php '.DIR_SYSTEM.'cron/sync_order.php > /dev/null 2>&1';
		} else {
			$data['wb_order_cron'] = '* * * * * ' . '/usr/bin/php '.DIR_SYSTEM.'cron/sync_order.php > /dev/null 2>&1';
		}

		if (isset($this->request->post['wb_product_cron'])) {
			$data['wb_product_cron'] = '* * * * * ' . '/usr/bin/php '.DIR_SYSTEM.'cron/sync_product.php > /dev/null 2>&1';
		} else {
			$data['wb_product_cron'] = '* * * * * ' . '/usr/bin/php '.DIR_SYSTEM.'cron/sync_product.php > /dev/null 2>&1';
		}

		if (isset($this->request->post['wb_sync_cron'])) {
			$data['wb_sync_cron'] = '* * * * * ' . '/usr/bin/php '.DIR_SYSTEM.'cron/sync_nm_crt.php > /dev/null 2>&1';
		} else {
			$data['wb_sync_cron'] = '* * * * * ' . '/usr/bin/php '.DIR_SYSTEM.'cron/sync_nm_crt.php > /dev/null 2>&1';
		}
		

		// Лог загрузки заказов
		$data['order_log'] = '';

		$file = DIR_LOGS . 'wb_order.log';

		if (file_exists($file)) {
			$size = filesize($file);

			if ($size >= 5242880) {
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);

				$i = 0;

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				$data['error_warning'] = sprintf($this->language->get('error_warning'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			} else {
				$data['order_log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
			}
		}
		// settings
		if (isset($this->request->post['wb_settings'])) {
			$data['wb_settings'] = $this->request->post['wb_settings'];
		} else {
			$data['wb_settings'] = $this->config->get('wb_settings');
		}

		if (empty($data['wb_settings'])) {
			$data['wb_settings'] = [[
				'wb_supplier_uuid'	=> 	'',
				'wb_work_status'	=>	0,
				'wb_w_token'		=>	'',
				'wb_t_token'		=>	'',
				'wb_status'			=>	0,
				'wb_phone'			=>	'',
				'wb_token_phone'	=>	'',
				'phone_token_value'	=>	'',
				'wb_uuid'			=>	time(),
			]];
		}
		
		// Status
		if (isset($this->request->post['wb_order_status'])) {
			$wb_order_status = $this->request->post['wb_order_status'];
		} else {
			$wb_order_status = $this->config->get('wb_order_status') ? json_decode($this->config->get('wb_order_status'), true) : [];
		}

		$data['wb_order_status'] = array();

		foreach ($wb_order_status as $status) {

			$data['wb_order_status'][] = array(
				'id'      => $status['id'],
				'name'      => $status['name'],
			);
		}

		/** attributes */
		$this->load->model('module/wildberries');
		$data['attributes'] = $this->model_module_wildberries->getAttributes();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('module/wildberries.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/wildberries')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if(empty($this->request->post['wb_registration_token']) && empty($this->request->post['wb_registration_email'])) {
			if(empty($this->request->post['wb_store'])) {
				$this->error['store'] = $this->language->get('error_store');
			}
			
			if(empty($this->request->post['wb_t_token'])) {
				$this->error['t_token'] = $this->language->get('error_t_token');
			}
			
			if(empty($this->request->post['wb_w_token'])) {
				$this->error['w_token'] = $this->language->get('error_w_token');
			}
		}

		return !$this->error;
	}

	public function __construct($registry) {
		parent::__construct($registry);
		
		// $this->load->model('module/wildberries');
		// $this->model_module_wildberries->refreshtoken();
	}
	
	public function install() {
		//$this->load->model('extension/event');
		//$this->model_extension_event->addEvent('wb_create_product_after', 'admin/model/catalog/product/addProduct/after', 'extension/module/wildberries/addProduct');
		//$this->model_extension_event->addEvent('wb_product_sync', 'catalog/model/checkout/order/addOrderHistory/after', 'extension/module/wildberries/productSync');
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `wb_product` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`oc_product_id` VARCHAR(64) NULL DEFAULT NULL,
			`nmId` VARCHAR(64) NULL DEFAULT NULL,
			`chrtId` INT(11) NULL DEFAULT NULL,
			`imtId` INT(11) NOT NULL DEFAULT 0,
			`wb_store_id` INT(11) NULL DEFAULT NULL,
			`wb_product_id` varchar(64) NULL DEFAULT NULL,
			`barcode` varchar(64) NULL DEFAULT NULL,
			`vendor_code` varchar(190) NULL DEFAULT NULL,
			`wb_name` varchar(500) NOT NULL DEFAULT '',
			`quantity_flag` tinyint(1) NOT NULL DEFAULT 0,
			`date_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`))
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;");
			
		$this->db->query("CREATE TABLE IF NOT EXISTS `wb_order` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`order_id` VARCHAR(255) NULL DEFAULT NULL,
			`order_status_id` INT(1) NULL DEFAULT NULL,
			`items` VARCHAR(500) NULL DEFAULT NULL,
			`date_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`))
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;");
	}

	public function uninstall() {
		//$this->load->model('extension/event');
		//$this->model_extension_event->deleteEvent('wb_create_product_after');
		//$this->model_extension_event->deleteEvent('wb_product_sync');
	}
	
	public function write($message) {
		$file = fopen(DIR_LOGS . 'wb_order.log', 'a');
		fwrite($file, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
	}

	public function addProduct($path, $data, $id) {
		$this->load->model('module/wildberries');
		$this->model_module_wildberries->createNewProduct([
			'data'			=>	$data,
			'oc_product_id'	=>	$id,
		]);
	}

	public function auth() {
		$this->write('Авторизация');
		$this->load->model('module/wildberries');
		$wb_index = $this->request->get['wb_index'];
		$this->load->model('setting/setting');
		$response = [];
		if (!empty($this->request->get['wb_phone']) && $phone = $this->request->get['wb_phone']) {
			$result = $this->model_module_wildberries->setToken($phone);
			$this->model_setting_setting->editSetting('wb_phone', ['wb_phone'	=>	$result['wb_phone']]);
			$this->model_setting_setting->editSetting('phone_token_value', ['phone_token_value'	=>	$result['token']]);
			$response['phone_token_value'] = $result['token'];
			$response['wb_index'] = $wb_index;
			$response['status'] = true;
			$response['till_next_request'] = $result['till_next_request'];
		}
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
	}

	public function refreshtoken() {
		$this->write('Обновление токена');
		$this->load->model('module/wildberries');
		$status = $this->model_module_wildberries->refreshtoken();
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($status));
	}

	public function authcode() {
		$this->write('Отправка кода');
		$this->load->model('module/wildberries');
		$phone_token_value = $this->request->get['wb_phone_token_value'];
		$this->load->model('setting/setting');
		$wb_setting_index = $this->request->get['wb_index'];
		$response = [];
		if (!empty($this->request->get['wb_phone_code']) && $phone_code = $this->request->get['wb_phone_code']) {
			$result = $this->model_module_wildberries->login($phone_code, $phone_token_value);
			$response = $result;
		}
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
	}
	
	public function syncOrder() {
		$this->write('syncOrder');
		$this->load->model('module/wildberries');
		// if (isset($this->session->data['token'])){
			$this->model_module_wildberries->syncOrders();
		// }
	}
	
	public function syncProduct($arrProducts = '') {
		$this->write('syncProduct');
		$this->load->model('module/wildberries');
		$this->model_module_wildberries->syncProduct($arrProducts);
		$this->response->redirect($this->url->link('catalog/wildberries', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function import() {
		if (!empty($this->request->files['file']) && !empty($this->request->get['wb_profile'])) {
			$this->load->model('module/wildberries');
			$wb_import_compare_field = !empty($this->request->get['wb_import_compare_field']) ? $this->request->get['wb_import_compare_field'] : 'sku';
			$wb_import_excel_compare_field = !empty($this->request->get['wb_import_excel_compare_field']) ? $this->request->get['wb_import_excel_compare_field'] : 6;
			$wb_profile_id = $this->request->get['wb_profile'];
			$this->model_module_wildberries->import($this->request->files['file'], $wb_import_compare_field, $wb_import_excel_compare_field, $wb_profile_id);
		} else {
			echo json_encode(['status'	=>	false]);die();
		}
	}
	
	public function addProducts() {
		if (!empty($this->request->post['selected'])){
			$this->load->model('module/wildberries');
			$this->load->model('catalog/product');
			$select_products = $this->request->post['selected'];
			$wb_products = $this->model_module_wildberries->getWbProduct();
			$diff = array_diff($this->request->post['selected'], $wb_products);
			
			foreach ($diff as $id){
				$product_data = $this->model_catalog_product->getProduct($id);
				$this->addProduct('', $product_data, $id);
			}
		}
		$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function createWbProduct() {
		$schema = $this->request->post['schema'];
		$nom_schema = !empty($this->request->post['nom_schema']) ? $this->request->post['nom_schema'] : '{}';
		$object_name = $this->request->post['object_name'];
		$schema = json_decode(htmlspecialchars_decode($schema), true, 512, JSON_ERROR_UTF8);
		$nom_schema = json_decode(htmlspecialchars_decode($nom_schema), true, 512, JSON_ERROR_UTF8);
		$addin = [];
		$nomenclatures = [];
		$err = [];
		$post = $this->request->post;
		$result = [];
		$ids = !empty($post['ids']) ? $post['ids'] : $err[] = ['type' => 'general', 'error' => 'Не выбрано ни одного профиля.'];
		// wb_stores
		$wb_stores = !empty($post['wb_stores']) ? $post['wb_stores'] : [];
		foreach($schema as $sh) {
			$slashed_type = str_replace(' ', '_', $sh['type']);
			if(!empty($post[$slashed_type])) {
				$type = $sh['type'];
				$params = ['value'	=>	(string)htmlspecialchars_decode($post[$slashed_type])];
				if (!empty($sh['units'])) {
					$params['unites'] = $sh['units'][0];
				}
				if (!empty($sh['isNumber'])) {
					$params['count'] = (int)htmlspecialchars_decode($post[$slashed_type]);
				}
				$addin[] = [
					'type'		=>	$type,
					'params'	=>	[$params],
				];
			} else if (!empty($sh['required'])) {
				$err[] = [
					'type'	=>	$sh['type'],
					'error'	=>	'Поле обязательно к заполнению',
				];
			}
		}
		if (!empty($nom_schema)) {
			foreach($nom_schema as $n_sh) {
				$nom_addin = [];
				$slashed_type = str_replace(' ', '_', $n_sh['type']);
				if(!empty($post[$slashed_type])) {
					$type = $n_sh['type'];
					$params = ['value'	=>	(string)htmlspecialchars_decode($post[$slashed_type])];
					if (!empty($n_sh['units'])) {
						$params['unites'] = $n_sh['units'][0];
					}
					if (!empty($n_sh['isNumber'])) {
						$params['count'] = (int)htmlspecialchars_decode($post[$slashed_type]);
					}
					$nom_addin[] = [
						'type'		=>	$type,
						'params'	=>	[$params],
					];
				} else if (!empty($n_sh['required'])) {
					$err[] = [
						'type'	=>	$n_sh['type'],
						'error'	=>	'Поле обязательно к заполнению',
					];
				}
			}
			$nomenclatures[] = [
				'addin'	=>	$nom_addin
			];
		}
		$post['Штрих_код'] = substr(str_replace(['.',' '], ['', ''], microtime()), 1, 13);
		if (empty($ids) && !empty($post['Розничная_цена'])) {
			$nomenclatures[0]['variations'] = [];
			if (empty($post['Штрих_код'])) {
				$err[] = [
					'type'	=>	'Штрих код',
					'error'	=>	'Поле обязательно к заполнению',
				];
			}
			$nomenclatures[0]['variations'][0] = [
				'addin'		=>	[],
				'barcode'	=> empty($post['Штрих_код']) ? substr(str_replace(['.',' '], ['', ''], microtime()), 1, 13) : $post['Штрих_код'],
			];
			$p_params = [];
			$kef = $this->config->get('wb_percent_product') ? intval($this->config->get('wb_percent_product')) : 0;
			$retailer_price = $kef > 0 ? (int)$post['Розничная_цена'] + ((int)$post['Розничная_цена'] / 100 * $kef): (int)$post['Розничная_цена'];
			$p_params[] = [
				'count'	=>	round($retailer_price),
				"units"	=>	"рублей",
			];
			$nomenclatures[0]['variations'][0]['addin'][] = [
				"type"		=>	"Розничная цена",
				"params"	=>	$p_params
			];
		} else if (empty($ids) && isset($post['Розничная_цена']) && empty($post['Розничная_цена'])) {
			$err[] = [
				'type'	=>	'Розничная цена',
				'error'	=>	'Поле обязательно к заполнению',
			];
		}
		if (empty($ids) && isset($post['Артикул_поставщика'])) {
			$nomenclatures[0]['vendorCode'] = $post['Артикул_поставщика'];
		} else if (empty($ids)){
			$err[] = [
				'type'	=>	'Артикул поставщика',
				'error'	=>	'Поле обязательно к заполнению',
			];
		}

		if(empty($err)) {
			$this->load->model('module/wildberries');
			$results = $this->model_module_wildberries->createWBApiProduct($addin, $object_name, $nomenclatures, $ids, $wb_stores);
			foreach($results as $wb_store_id => $result) {
				if(isset($result['result']) && !empty($result['error'])) {
					$err[] = [
						'type'	=>	'global',
						'error'	=>	$result['error']
					];
				} else if (isset($result['result'])) {
					$rresult = [];
					try {
						if (!empty($ids)) {

							$barcodes = $result['barcodes'];
							$vendorCodes = $result['vendorCodes'];
							// $res = $result['result']['createdCards'];
							$query = [];
							foreach($ids as $i => $id) {
								$nmId = NULL;
								$chrt_id = NULL;
								// try {
								// 	$nmId = $res[$i]['nomenclatures'][0]['nmId'];
								// 	$chrt_id = $res[$i]['nomenclatures'][0]['variations'][0]['chrtId'];
								// } catch(Exception $e) {}
								//$wb_product_id = $res[$i]['id'];
								$wb_product_id = NULL;
								$barcode = $barcodes[$id];
								$vendorCode = $vendorCodes[$id];
								$query[] = "('" . implode("', '", [$id, $nmId, $chrt_id, $wb_product_id, $wb_store_id, $barcode, $vendorCode]) . "')";
							}
							if(!empty($query)) {
								$this->model_module_wildberries->createBatchWBProducts($query, true);
							}
							$rresult = ['success'=>true];
						} else {
							$nmId = NULL;
							$chrt_id = NULL;
							try {
								if(!empty($result['result']['createdCard']['nomenclatures'])) {
									$nmId = $result['result']['createdCard']['nomenclatures'][0]['nmId'];
									$chrt_id = $result['result']['createdCard']['nomenclatures'][0]['variations'][0]['chrtId'];
								}
							} catch(\Exception $e) {}
							$rresult = [
								'nmId'		=>	$nmId,
								'chrt_id'	=>	$chrt_id,
								'wb_product_id'	=>	$result['result']['createdCard']['id'],
							];
						}
					} catch(Exception $e) {}
					$result = $rresult;
				}
			}
		}
		// $schema = json_decode($schema, true);
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['result'=>$result, 'error'=>$err]));
	}
	
	public function updateProducts() { // пока не работает. нужно думать как обновить только выделенные элементы
		if (!empty($this->request->post['selected'])){
			$this->load->model('module/wildberries');
			$this->load->language('module/wildberries');
			if(!empty($this->request->post['selected'])){
				//$this->syncProduct($this->request->post['selected']);
			}
			$this->log->write(print_r('updateProducts',true));
			//$this->log->write(print_r($products,true));
		}
		$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function clear() {
		$this->load->language('module/wildberries');

		if (!$this->user->hasPermission('modify', 'module/wildberries')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			$file = DIR_LOGS . 'wb_order.log';

			$handle = fopen($file, 'w+');

			fclose($handle);

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->response->redirect($this->url->link('module/wildberries', 'token=' . $this->session->data['token'], true));
	}

	public function updateWBProduct() {
		$schema = $this->request->post['schema'];
		$nom_schema = !empty($this->request->post['nom_schema']) ? $this->request->post['nom_schema'] : '{}';
		$object_name = $this->request->post['object_name'];
		$schema = json_decode(htmlspecialchars_decode($schema), true, 512, JSON_ERROR_UTF8);
		$nom_schema = json_decode(htmlspecialchars_decode($nom_schema), true, 512, JSON_ERROR_UTF8);
		$addin = [];
		$nomenclatures = [];
		$err = [];
		$post = $this->request->post;
		$result = [];
		$ids = !empty($post['ids']) ? $post['ids'] : [];
		$object_id = $post['object_id'];
		$this->load->model('module/wildberries');
		$wb_product_db = !is_null($object_id) ? $this->model_module_wildberries->getProductByWbId($object_id) : [];
		$oc_product_db = !empty($wb_product_db['oc_product_id']) ? $this->model_module_wildberries->getProductById($object_id): [];
		foreach($schema as $sh) {
			$slashed_type = str_replace(' ', '_', $sh['type']);
			if(!empty($post[$slashed_type])) {
				$type = $sh['type'];
				$params = ['value'	=>	(string)htmlspecialchars_decode($post[$slashed_type])];
				if (!empty($sh['units'])) {
					$params['unites'] = $sh['units'][0];
				}
				if (!empty($sh['isNumber'])) {
					$params['count'] = (int)htmlspecialchars_decode($post[$slashed_type]);
				}
				$addin[] = [
					'type'		=>	$type,
					'params'	=>	[$params],
				];
			} else if (!empty($sh['required'])) {
				$err[] = [
					'type'	=>	$sh['type'],
					'error'	=>	'Поле обязательно к заполнению',
				];
			}
		}
		if (!empty($nom_schema)) {
			foreach($nom_schema as $n_sh) {
				$nom_addin = [];
				$slashed_type = str_replace(' ', '_', $n_sh['type']);
				if(!empty($post[$slashed_type])) {
					$type = $n_sh['type'];
					$params = ['value'	=>	(string)htmlspecialchars_decode($post[$slashed_type])];
					if (!empty($n_sh['units'])) {
						$params['unites'] = $n_sh['units'][0];
					}
					if (!empty($n_sh['isNumber'])) {
						$params['count'] = (int)htmlspecialchars_decode($post[$slashed_type]);
					}
					$nom_addin[] = [
						'type'		=>	$type,
						'params'	=>	[$params],
					];
				} else if (!empty($n_sh['required'])) {
					$err[] = [
						'type'	=>	$n_sh['type'],
						'error'	=>	'Поле обязательно к заполнению',
					];
				}
			}
			$nomenclatures[] = [
				'addin'	=>	$nom_addin
			];
		}
		if (!empty($post['Розничная_цена'])) {
			$nomenclatures[0]['variations'] = [];
			if (empty($post['Штрих_код'])) {
				$err[] = [
					'type'	=>	'Штрих код',
					'error'	=>	'Поле обязательно к заполнению',
				];
			}
			$r_dt = [
				'addin'		=>	[],
				'barcode'	=> $post['Штрих_код']
			];
			if (!empty($wb_product_db)) {
				$r_dt['chrtId']	= !is_null($wb_product_db['chrtId']) ? (int)$wb_product_db['chrtId'] : 0;
			}
			$nomenclatures[0]['variations'][0] = $r_dt;
			$p_params = [];
			$clean_price = intval($post['Розничная_цена']);
			$retailer_price = $clean_price;
			$kef = $this->config->get('wb_percent_product') ? intval($this->config->get('wb_percent_product')) : 0;
			$retailer_price = !empty($oc_product_db['price']) && intval($oc_product_db['price']) > 0 && $kef > 0 ? (int)$oc_product_db['price'] + ((int)$oc_product_db['price'] / 100 * $kef) : (int)$oc_product_db['price'];
			$p_params[] = [
				'count'	=>	round($retailer_price),
				"units"	=>	"рублей",
			];
			$nomenclatures[0]['variations'][0]['addin'][] = [
				"type"		=>	"Розничная цена",
				"params"	=>	$p_params
			];
		} else if (isset($post['Розничная_цена']) && empty($post['Розничная_цена'])) {
			$err[] = [
				'type'	=>	'Розничная цена',
				'error'	=>	'Поле обязательно к заполнению',
			];
		}
		if (empty($ids) && isset($post['Артикул_поставщика'])) {
			if (!empty($wb_product_db)) {
				$nomenclatures[0]['nmId'] = is_null($wb_product_db['nmId']) ? $wb_product_db['nmId'] : 0;
			}
			$nomenclatures[0]['vendorCode'] = $post['Артикул_поставщика'].substr(str_replace(['.',' '], ['', ''], microtime()), 1, 2);
		} else if (empty($ids)){
			$err[] = [
				'type'	=>	'Артикул поставщика',
				'error'	=>	'Поле обязательно к заполнению',
			];
		}
		if(empty($err)) {
			$result = $this->model_module_wildberries->updateWBApiProduct($addin, $object_name, $nomenclatures, $ids, $object_id);
			if(empty($result['result']) && !empty($result['error'])) {
				$err[] = [
					'type'	=>	'global',
					'error'	=>	$result['error']
				];
			} else if (!empty($result['result'])) {
				$rresult = [];
				try {
					if (!empty($ids)) {
						// $res = $result['result']['createdCards'];
						// $query = [];
						// foreach($ids as $i => $id) {
						// 	$nmId = NULL;
						// 	$chrt_id = NULL;
						// 	try {
						// 		$nmId = $res[$i]['nomenclatures'][0]['nmId'];
						// 		$chrt_id = $res[$i]['nomenclatures'][0]['variations'][0]['chrtId'];
						// 	} catch(\Exception $e) {}
						// 	$wb_product_id = $res[$i]['id'];
						// 	$query[] = "('" . implode("', '", [$id, $nmId, $chrt_id, $wb_product_id]) . "')";
						// }
						// if(!empty($query)) {
						// 	$this->model_module_wildberries->createBatchWBProducts($query, true);
						// }
						// $rresult = ['success'=>true];
					} else {
						$nmId = NULL;
						$chrt_id = NULL;
						try {
							$nmId = $result['result']['result']['nomenclatures'][0]['nmId'];
							$chrt_id = $result['result']['result']['nomenclatures'][0]['variations'][0]['chrtId'];
						} catch(\Exception $e) {}
						$rresult = [
							'nmId'		=>	$nmId,
							'chrt_id'	=>	$chrt_id,
							'wb_product_id'	=>	$result['result']['result']['id'],
						];
					}
				} catch(Exception $e) {}
				$result = $rresult;
			}
		}
		// $schema = json_decode($schema, true);
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['result'=>$result, 'error'=>$err]));
	}

	public function getWbProductById()
	{
		$err = [];
		$result = [];
		$post = $this->request->post;
		if(!empty($post['wb_product_id'])) {
			$this->load->model('module/wildberries');
			$result = $this->model_module_wildberries->getWBProductById($post['wb_product_id']);
		}
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['result'=>$result, 'error'=>$err]));
	}

	public function syncNmCrt() {
		$this->write('syncNmCrt');
		$this->load->model('module/wildberries');
		$this->model_module_wildberries->syncNmCrt();
	}
}