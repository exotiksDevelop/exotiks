<?php
class ControllerModuleB24Apipro extends Controller {
	private $error = array();
	private $minMaxParentIds = [];
	const CONFIG_UF_LANG = 'ru';
	
	public function __construct($registry){
		parent::__construct($registry);
		
        $this->load->model('setting/setting');
        $b24_setting = $this->model_setting_setting->getSetting('b24');
		
		if ($registry->has('b24')){
			$this->b24->setFields($b24_setting);
		} 
	}
	
	public function index() {
        set_time_limit(9999);

		$this->load->language('module/b24_apipro');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/module');
        $this->load->model('module/b24_category');
        $this->load->model('module/b24_product');
		$this->load->model('module/b24_order');
        $this->load->model('module/b24_customer');
		$this->load->model('localisation/order_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$authData = $this->parseHookURL(isset($this->request->post['b24_in_hook']) ? $this->request->post['b24_in_hook'] : '');
			if(!empty($authData)){
				$this->model_setting_setting->editSetting('b24', array_merge($this->request->post, $authData));
			} else {
				$this->model_setting_setting->editSetting('b24', $this->request->post);
			}
			
			$this->session->data['success'] = 'Настройки сохранены';
			$this->response->redirect($this->url->link('module/b24_apipro', 'token=' . $this->session->data['token'], 'SSL'));	
		}
		

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/b24_apipro', 'token=' . $data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/b24_apipro', 'token=' . $data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/b24_apipro', 'token=' . $data['token'], 'SSL');
            $data['action_web_hook'] = $this->url->link('module/b24_apipro/setwebhookkey', 'token=' . $data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/b24_apipro', 'token=' . $data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
            $data['action_web_hook'] = $this->url->link('module/b24_apipro/setwebhookkey', 'token=' . $data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $data['token'], 'SSL');

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['module_description'])) {
			$data['module_description'] = $this->request->post['module_description'];
		} elseif (!empty($module_info)) {
			$data['module_description'] = $module_info['module_description'];
		} else {
			$data['module_description'] = array();
		}

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['lang'] = $this->language->get('lang');

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		//Получение каталога
		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
		
		// Получение входящих хуков
		$data['b24_key_api'] = $this->config->get('b24_key_api');
		$data['b24_key_domain'] = $this->config->get('b24_key_domain');
		$data['b24_key_id'] = $this->config->get('b24_key_id');
		$data['b24_in_hook'] = isset($data['b24_key_domain']) ? 'https://'.$data['b24_key_domain'].'/rest/'.$data['b24_key_id'].'/'.$data['b24_key_api'].'/profile/' : 'Вставьте ссылку входящего вебхука';
		
		// Получение исходящих хуков 
		$data['out_hooks'] = [ 
			'ONCRMLEADADD' 		=> 'Создание лида', 
			'ONCRMLEADUPDATE' 	=> 'Обновление лида', 
			'ONCRMLEADDELETE' 	=> 'Удаление лида', 
			'ONCRMDEALADD' 		=> 'Создание сделки', 
			'ONCRMDEALUPDATE' 	=> 'Обновление сделки', 
			'ONCRMDEALDELETE' 	=> 'Удаление сделки',  
			'ONCRMCONTACTADD' 	=> 'Создание контакта', 
			'ONCRMCONTACTUPDATE' => 'Обновление контакта', 
			'ONCRMCONTACTDELETE' => 'Удаление контакта',  
			'ONCRMPRODUCTADD' 	=> 'Создание товара', 
			'ONCRMPRODUCTUPDATE' => 'Обновление товара', 
			'ONCRMPRODUCTDELETE' => 'Удаление товара', 
			//'ONCRMACTIVITYADD' => 'Создание дела', 
			//'ONCRMACTIVITYUPDATE' => 'Обновление дела', 
			//'ONCRMACTIVITYDELETE' => 'Удаление дела', 
			//'ONCRMINVOICEADD' => 	'Создание счета', 
			//'ONCRMINVOICEUPDATE' => 'Обновление счета', 
			//'ONCRMINVOICEDELETE' => 'Удаление счета', 
			//'ONCRMINVOICESETSTATUS' => 'Обновление статуса счета',
			//'ONCRMCURRENCYADD' => 'Создание валюты', 
			//'ONCRMCURRENCYUPDATE' => 'Обновление валюты', 
			//'ONCRMCURRENCYDELETE' => 'Удаление валюты',
			//'ONCRMCOMPANYADD' => 'Создание компании', 
			//'ONCRMCOMPANYUPDATE' => 'Обновление компании', 
			//'ONCRMCOMPANYDELETE' => 'Удаление компании',
		];
		$data['b24_out_hooks'] = $this->config->get('b24_out_hooks');
		
		// Получение статусов заказов
		$data['oc_statuses'] = $this->model_localisation_order_status->getOrderStatuses(array());
		$data['statuses'] = $this->config->get('b24_status');
		$data['stages'] = $this->config->get('b24_stage');
		
		// Получение списка статусов из Битрикс24
		$data['b24_statuses'] = $this->getStatusList('STATUS');
		$data['b24_stages'] = $this->getStatusList('DEAL_STAGE');
		$data['b24_source'] = $this->getStatusList('SOURCE');
		$data['b24_contact_type'] = $this->getStatusList('CONTACT_TYPE');
		$data['b24_deal_type'] = $this->getStatusList('DEAL_TYPE');
		$data['pay_field'] = isset($this->config->get('b24_order')['fieldpay']) ? $this->config->get('b24_order')['fieldpay'] : '';
		$data['order_source_id'] = isset($this->config->get('b24_order')['source']) ? $this->config->get('b24_order')['source'] : '';
		$data['customer_source_id'] = isset($this->config->get('b24_customer')['settings']['SOURCE']) ? $this->config->get('b24_customer')['settings']['SOURCE'] : '';
		$data['retail_id'] = isset($this->config->get('b24_customer')['settings']['RETAIL']) ? $this->config->get('b24_customer')['settings']['RETAIL'] : '';
		
		//Получаем пользовательские поля
		$data['b24_uf_lists'] = $this->getUFList();
		
		//Получение свойств товаров
		$data['b24_propertys'] = $this->getPropertyList();
		$data['oc_propertys'] = [
			'PROPERTY_SKU' => 'Артикул',
			'PROPERTY_MODEL' => 'Модель',
			'PROPERTY_QUANTITY' => 'Количество',
			'PROPERTY_WAREHOUSE_STATUS' => 'Статус на складе',
			'PROPERTY_MANUFACTURER' => 'Производитель',
			'PROPERTY_OPTION' => 'Опция',
		];
		
		$data['b24_productprops'] = $this->config->get('b24_productprops');
		
		//Проверка валидности хуков
		$data['connectB24'] = $this->getScopeList();
		
		//Статусы заказов при которых происходит отправка 
		if (isset($this->request->post['b24_order'])) {
			$data['b24_order_status'] = $this->request->post['b24_order'];
		} elseif (isset($this->config->get('b24_order')['status'])) {
			$data['b24_order_status'] = $this->config->get('b24_order')['status'];
		} else {
			$data['b24_order_status'] = array();
		}
		
		// Получение user из Битрикс24
		$data['user_list'] = $this->GetUser();
		
		// Менеджер  
		$data['manager_id'] = isset($this->config->get('b24_manager')['manager']) ? $this->config->get('b24_manager')['manager'] : 1;
		$data['created_id'] = isset($this->config->get('b24_manager')['created']) ? $this->config->get('b24_manager')['created'] : 1;
		$data['order_open'] = isset($this->config->get('b24_manager')['order_open']) ? $this->config->get('b24_manager')['order_open'] : 'Y';
		
		//Заказы
		$data['b24_order'] = $this->config->get('b24_order');
		
		// Клиенты 
		$data['b24_customer'] = $this->config->get('b24_customer')['settings'];
		$data['customer_open'] = isset($this->config->get('b24_customer')['settings']['customer_open']) ? $this->config->get('b24_customer')['settings']['customer_open'] : 'Y';
		
		//Кнопки синхронизации товаров
        $data['button_synchronizationproducts'] = $this->url->link('module/b24_apipro/SyncProductToB24', 'token=' . $data['token'], 'SSL');
		$data['getProductForSync'] = count($this->model_module_b24_product->getProductForSync());
		$data['button_updateproduct'] = $this->url->link('module/b24_apipro/UpdateProductToB24', 'token=' . $data['token'], 'SSL');
		
		// Синхронизация клиентов
		$data['button_synchronizationcontacts'] = $this->url->link('module/b24_apipro/SyncCustomerToB24', 'token=' . $data['token'], 'SSL');
		$data['getContactForSync'] = count($this->model_module_b24_customer->getCustomerForSync());
		
		// Отправка заказов в битрикс 24
        $data['SendOrderToBitrix'] = $this->url->link('module/b24_apipro/SendOrderToBitrix', 'token=' . $data['token'], 'SSL');
		$data['getOrderForSync'] = count($this->model_module_b24_order->getOrderForSend());

		$this->response->setOutput($this->load->view('module/b24_apipro.tpl', $data));
	}

	public function install() {
		$delimiter = '$registry = new Registry();';
		$class = '$b24 = new B24();$registry->set(\'b24\', $b24);';
			
		$filename = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
		$indexArr = explode($delimiter, file_get_contents($filename, true));
		$index = $indexArr[0] . $delimiter . $class . $indexArr[1];
		file_put_contents($filename, $index);
			
		$filename = $_SERVER['DOCUMENT_ROOT'] . '/admin/index.php';
		$indexArr = explode($delimiter, file_get_contents($filename, true));
		$index = $indexArr[0] . $delimiter . $class . $indexArr[1];
		file_put_contents($filename, $index);

		$this->load->model('extension/event');
		$this->model_extension_event->addEvent('b24_order_add', 'post.order.history.add', 'module/b24_apipro/addOrder');
		$this->model_extension_event->addEvent('b24_customer_add', 'post.customer.add', 'module/b24_apipro/addCustomer');
		$this->model_extension_event->addEvent('b24_customer_edit', 'post.customer.edit', 'module/b24_apipro/editCustomer');
		$this->model_extension_event->addEvent('b24_customer_add_address', 'post.customer.add.address', 'module/b24_apipro/addAddress');
		$this->model_extension_event->addEvent('b24_customer_edit_address', 'post.customer.edit.address', 'module/b24_apipro/editAddress');
        $this->model_extension_event->addEvent('b24_category_edit', 'post.admin.category.edit', 'module/b24_apipro/edit');
        $this->model_extension_event->addEvent('b24_category_add', 'post.admin.category.add', 'module/b24_apipro/add');
        $this->model_extension_event->addEvent('b24_category_delete', 'post.admin.category.delete', 'module/b24_apipro/delete');

        $this->model_extension_event->addEvent('b24_product_add', 'post.admin.product.add', 'module/b24_apipro/addproduct');
        $this->model_extension_event->addEvent('b24_product_edit', 'post.admin.product.edit', 'module/b24_apipro/editproduct');
        $this->model_extension_event->addEvent('b24_product_delete', 'post.admin.product.delete', 'module/b24_apipro/deleteproduct');

		$this->db->query("CREATE TABLE IF NOT EXISTS `b24_customer` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`oc_customer_id` INT(11) NOT NULL,
			`b24_contact_id` INT(11) NOT NULL,
			`phone` VARCHAR(16) NOT NULL,
			`b24_contact_field` TEXT NULL,
			`date_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			UNIQUE INDEX `b24_contact_id` (`b24_contact_id`))
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;");
						
		$this->db->query("Create table if not exists b24_category(
            `id` INT(11) AUTO_INCREMENT,
            `oc_category_id` INT(11)  NOT NULL,
            `b24_category_id` INT(11) NOT NULL,
            PRIMARY KEY(id),
            UNIQUE b24_category_id (b24_category_id))
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;");
			
        $this->db->query("CREATE TABLE if not exists `b24_product` (
			`id` INT(11) NOT NULL AUTO_INCREMENT, 
			`oc_product_id` INT(11) NOT NULL,
			`b24_product_id` INT(11) NOT NULL,
			`option` INT(11) NULL DEFAULT NULL,
			`date_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (`id`), 
			UNIQUE INDEX `option_product_id` (`option`, `oc_product_id`)) 
			COLLATE='utf8_general_ci' 
			ENGINE=InnoDB;");
		$this->db->query("CREATE TABLE IF NOT EXISTS `b24_order` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`oc_order_id` INT(11) NULL DEFAULT NULL,
			`b24_order_id` INT(11) NULL DEFAULT NULL,
			`type` INT(1) NOT NULL DEFAULT '1',
			`date_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`))
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;");
	}

	public function uninstall() {		
		$delimiter = '$b24 = new B24();$registry->set(\'b24\', $b24);';
			
		$filename = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
		$indexArr = explode($delimiter, file_get_contents($filename, true));
		$index = $indexArr[0] . $indexArr[1];
		file_put_contents($filename, $index);
			
		$filename = $_SERVER['DOCUMENT_ROOT'] . '/admin/index.php';
		$indexArr = explode($delimiter, file_get_contents($filename, true));
		$index = $indexArr[0] . $indexArr[1];
		file_put_contents($filename, $index);

		$this->load->model('extension/event');
		$this->model_extension_event->deleteEvent('b24_order_add');
		$this->model_extension_event->deleteEvent('b24_customer_add');
		$this->model_extension_event->deleteEvent('b24_customer_edit');
		$this->model_extension_event->deleteEvent('b24_customer_add_address');
		$this->model_extension_event->deleteEvent('b24_customer_edit_address');

		$this->model_extension_event->deleteEvent('b24_category_add');
        $this->model_extension_event->deleteEvent('b24_category_edit');
        $this->model_extension_event->deleteEvent('b24_category_delete');
		
		$this->model_extension_event->deleteEvent('b24_product_add');
        $this->model_extension_event->deleteEvent('b24_product_edit');
        $this->model_extension_event->deleteEvent('b24_product_delete');
	}


	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/b24_apipro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	// Добавление товаров которых нет в Б24
	public function SyncProductToB24() {
		$this->load->model('module/b24_category');
		$this->load->model('module/b24_product');	
		$rootCat = $this->model_module_b24_category->getById(0);
		$getCat = $this->model_module_b24_category->getCatNoSync();
		if(empty($rootCat) || $getCat != 0){
			$this->model_module_b24_category->synchronizationcategories();
		} else {
			$productSync = $this->model_module_b24_product->addProductsBatch();
		}
		
		$this->session->data['success'] = 'Добавлено '.$productSync['add'].' товаров в Битрикс 24';
		$this->response->redirect($this->url->link('module/b24_apipro', 'token=' . $this->session->data['token'], 'SSL'));	
	}
	
	//Обновление товаров по крону или кнопке 
	public function UpdateProductToB24(){
		$this->load->model('module/b24_product');
		$pushUpdate = $this->model_module_b24_product->updateProductsBatch();
		$this->session->data['success'] = 'Добавлено '.$pushUpdate['add'].', обновлено '.$pushUpdate['update'].', удалено '.$pushUpdate['delete'].' товаров в Битрикс 24';
		$this->response->redirect($this->url->link('module/b24_apipro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	// синхронизация клиентов
	public function SyncCustomerToB24(){
		$this->load->model('module/b24_customer');
		$getCustomerForSync = $this->model_module_b24_customer->getCustomerForSync();
		if (!empty($getCustomerForSync)) {
			$this->model_module_b24_customer->AddCustomerToB24($getCustomerForSync);
			$this->session->data['success'] = ''.count($getCustomerForSync).' клиентов перенесено в Битрикс 24';
		} else {
			$this->session->data['success'] = 'Все клиенты синхронизированы с Битрикс 24';
		}
		$this->response->redirect($this->url->link('module/b24_apipro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	//Отправка заказов в битрикс 24
	public function SendOrderToBitrix(){
		$this->load->model('module/b24_order');
		$orderForSend = $this->model_module_b24_order->getOrderForSend();
		if (!empty($orderForSend)) {
			$this->model_module_b24_order->SendOrderToBitrix($orderForSend);
			$this->session->data['success'] = 'Отправлено '.count($orderForSend).' заказов в Битрикс 24';
		} else {
			$this->session->data['success'] = 'Синхронизация не произведена. Все заказы синхронизированы.';
		}
		$this->response->redirect($this->url->link('module/b24_apipro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	// Получение статуса по фильтру
	public function getStatusList($ENTITY_ID) {		
		$params = [
			'type' => 'crm.status.list',
			'params' => [
				'filter' => ["ENTITY_ID" => "$ENTITY_ID"]
			]
		];
		$result = $this->b24->callHook($params);
		
		if (!empty($result['error'])) {
			$this->b24->writeLog('Ошибка получения списка статусов лидов Битрикс24');
			return false;
        } else {
			return $result['result'];
		}
	}
	
	// Проверка вебхуков
	public function getScopeList() {
		$params = [
			'type' => 'scope',
			'params' => []
		];

		$result = $this->b24->callHook($params);
		if(!empty($result) && in_array('crm', $result['result']) && in_array('im', $result['result']) && in_array('user', $result['result'])){
			return true;
		} else {
			return false;
		} 		
	}
	
	// Получение пользователей Битрикс 24
	public function GetUser(){
		$params = [
			'type' => 'user.get',
			'params' => [
				'filter' => [
					'ACTIVE' => true,
				],
				'select' => [
				'ID', 'NAME', 'LAST_NAME'
				]
			],
			
		];
		$result = $this->b24->callHook($params);
		return $result['result'];
	}

    public function add($categoryId) {
        $this->load->model('catalog/category');
        $this->load->model('module/b24_category');
        $minMaxParentIds = $this->model_module_b24_category->getMinMaxParentIds();
        $lastId = 0;
        $this->minMaxParentIds = $minMaxParentIds;

        $minParentId = min($minMaxParentIds);

        if (isset($minParentId['parent_id']) && is_numeric($minParentId['parent_id']) && 0 <= $minParentId['parent_id']) {
            $fields = $this->model_module_b24_category->getById($minParentId['parent_id']);
            $lastId = isset($fields['b24_category_id']) ? $fields['b24_category_id'] : 0;
        }

        $category = $this->model_catalog_category->getCategory($categoryId);
        $categoryName = $category['name'];
        $recod = $this->model_module_b24_category->getById($category['parent_id']);
        $parentId = !empty($recod) ? $recod['b24_category_id'] : $lastId;

        //Создаем
        $params = [
            'type' => 'crm.productsection.add',
            'params' => [
                'fields' => [
                    'CATALOG_ID' => $categoryId,
                    'NAME'=> $categoryName,
                    'SECTION_ID'=> $parentId,
                ]
            ]
        ];

        $result = $this->b24->callHook($params);

        $lastId = $result['result'];

        $data = ['oc_category_id' => $categoryId, 'b24_category_id' => $lastId];
        $this->model_module_b24_category->addToDB($data);

        return $lastId;
    }

    public function edit($categoryId) {
        $this->load->model('catalog/category');
        $this->load->model('module/b24_category');

        $category = $this->model_catalog_category->getCategory($categoryId);
        $categoryName = $category['name'];

        // Извлекаем родительскую категорию из Кастомной таблицы
        $parentId = $category['parent_id'];     // id = 34
        if (!empty($parentId)) {
            $parentCategory = $this->model_module_b24_category->getById($parentId);
            if (isset($parentCategory['b24_category_id'])) {
                $parentId = $parentCategory['b24_category_id'];
            } else {
                $parentId = $this->add($parentId);
            }
        } else {
            $minMaxParentIds = $this->model_module_b24_category->getMinMaxParentIds();
            $minParentId = min($minMaxParentIds);

            if (isset($minParentId['parent_id']) && is_numeric($minParentId['parent_id']) && 0 <= $minParentId['parent_id']) {
                $fields = $this->model_module_b24_category->getById($minParentId['parent_id']);
                $parentId = isset($fields['b24_category_id']) ? $fields['b24_category_id'] : 0;
            }
        }

        $b24_category_id = $this->model_module_b24_category->getById($categoryId);
        $b24_category_id = isset($b24_category_id['b24_category_id']) ? $b24_category_id['b24_category_id'] : '';

        // Создаем или обновляем категорию
        if (empty($b24_category_id)) {
            $this->add($categoryId);
        } else {
            $params = [
                'type' => 'crm.productsection.update',
                'params' => [
                    'id' => $b24_category_id,
                    'fields' => [
                        'NAME'=> $categoryName,
                        'SECTION_ID'=> $parentId,
                    ]
                ]
            ];

            $result = $this->b24->callHook($params);

            if (!empty($result['error_description'])) {
                $txt = "Ошибка при редактировании Раздела на Битрикс24 " . $result['error_description'];
                throw new Exception($txt);
            }
        }
    }

    public function delete($categoryId) {
        $this->load->model('catalog/category');
        $this->load->model('module/b24_category');

        $categoryRow = $this->model_module_b24_category->getById($categoryId);
        $b24_category_id = $categoryRow['b24_category_id'];

        if (!empty($b24_category_id)) {
            $typeApi = 'crm.productsection.delete';
            $params = [
                'type' => $typeApi,
                'params' => [
                    'id' => $b24_category_id,
                ]
            ];
            $result = $this->b24->callHook($params);
        }
    }

    public function addproduct($productId) {
		if (!isset($productId)) {
            return;
        }
        $this->load->model('module/b24_product');
        $dataToAdd = $this->model_module_b24_product->prepareDataToB24($productId);
        $this->model_module_b24_product->addProductBatch($productId, $dataToAdd);
    }

    public function editproduct($productId) {
        if (!isset($productId)) {
            return;
        }
		$this->load->model('module/b24_product');
        $dataToUpdate = $this->model_module_b24_product->prepareDataToB24($productId);
        $this->model_module_b24_product->editProductBatch($productId, $dataToUpdate);
    }

    public function deleteproduct($productId) {
        if (!isset($productId)) {
            return;
        }
		$this->load->model('module/b24_product');
        $this->model_module_b24_product->deleteProduct($productId);
    }
	
	// Получаем своства товаров
	public function getPropertyList() {		
		$params = [
			'type' => 'crm.product.property.list',
			'params' => [
				'order' => ["SORT" => "ASC"],
				'filter' => ["MANDATORY"=> "N"]
			]
		];
		$result = $this->b24->callHook($params);
		return $result['result'];
	}
	
	public function exportListOrder() {
		if (!empty($this->request->post['selected'])){
			foreach ($this->request->post['selected'] as $key => $id){
				$order_id[][$key] = $id;
			}
			$this->load->model('module/b24_order');
			$response = $this->model_module_b24_order->SendOrderToBitrix($order_id);
			if (!empty($response)){
				$this->session->data['success'] = 'Заказы '.implode(", ", $response).' успешно отправлены';
			} else {
				$this->session->data['success'] = 'Выбранные заказы не отправлены так как уже есть в Битрикс 24';
			}
			
		}
		
		$this->response->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function exportListCustomer() {
		$this->load->model('module/b24_customer');
		if (!empty($this->request->post['selected'])){
			foreach ($this->request->post['selected'] as $key => $id){
				if(empty($this->model_module_b24_customer->getById($id))){
					$customer_id[][$key] = $id;
				}	
			}
			$response = !empty($customer_id) ? $this->model_module_b24_customer->AddCustomerToB24($customer_id) : '';
			if (!empty($response)){
				$this->session->data['success'] = 'Клиенты '.implode(", ", $response).' успешно синхронизированы';
			} else {
				$this->session->data['success'] = 'Выбранные клиенты не отправлены так как они уже есть в Битрикс 24';
			}
			
		}
		
		$this->response->redirect($this->url->link('customer/customer', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function exportOneOrder() {
        $this->load->model('module/b24_order');
		if (!empty($this->model_module_b24_order->getById($this->request->get['order_id']))){
			$this->response->setOutput(
                json_encode(
                    array(
                        'status_code' => 400,
                        'error_msg' => 'Заказ '.$this->request->get['order_id'].' не отправлен так как уже есть в Битрикс 24'
                    )
                )
            );
			return;
		}
		
		$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : '';
		if(!empty($order_id)){
			$response = $this->model_module_b24_order->addOrder($order_id);
		}
	if (empty($response)){
			$this->response->setOutput(
                json_encode(
                    array(
                        'status_code' => 400,
                        'error_msg' => ' Ошибка отправки заказа в Битрикс 24 - '.$order_id
                    )
                )
            );
		} else {
            $this->response->setOutput(
                json_encode(
                    array(
                        'status_code' => 200,
						'success_id' => 'Номер заказа в Битрикс 24 - '.$response
                    )
                )
            );
        }
    }
	
	public function parseHookURL($url) {
		if(empty($url)){
			return;
		}
		$output = explode("/", $url);
		$auth = [
			'b24_key_domain' => $output[2],
			'b24_key_id' => $output[4],
			'b24_key_api' => $output[5],
		];
		return $auth;
	}
	
	// Получаем пользовательские поля Битрикс24
	public function getUFList() {		
		
		$lead_uf = 'lead_uf_list';
		$contact_uf = 'contact_uf_list';
		$company_uf = 'company_uf_list';
		$deal_uf = 'deal_uf_list';
		$quote_uf = 'quote_uf_list';
		$invoice_uf = 'invoice_uf_list';
		
		$params = [
			'type' => 'batch',
			'params' => [
				'cmd' => [
					$lead_uf => 'crm.lead.userfield.list',
					$contact_uf => 'crm.contact.userfield.list',
					$company_uf => 'crm.company.userfield.list',
					$deal_uf => 'crm.deal.userfield.list',
					$quote_uf => 'crm.quote.userfield.list',
					$invoice_uf => 'crm.invoice.userfield.list',
				]
			]
		];
		$result = $this->b24->callHook($params);
		
		
		$lead_uf_list = $result['result']['result'][$lead_uf];
		if(isset($lead_uf_list)){
			foreach($lead_uf_list as $k => $v) {
				$uf['cmd'][$v['ID']] = 'crm.lead.userfield.get?'. http_build_query(['id' => $v['ID']]);
			}
		}
		
		$contact_uf_list = $result['result']['result'][$contact_uf];
		if (isset($contact_uf_list)){
			foreach($contact_uf_list as $k => $v) {
				$uf['cmd'][$v['ID']] = 'crm.contact.userfield.get?'. http_build_query(['id' => $v['ID']]);
			}
		}
		
		$company_uf_list = $result['result']['result'][$company_uf];
		if (isset($company_uf_list)){
			foreach($company_uf_list as $k => $v) {
				$uf['cmd'][$v['ID']] = 'crm.company.userfield.get?'. http_build_query(['id' => $v['ID']]);
			}
		}
		
		$deal_uf_list = $result['result']['result'][$deal_uf];
		if (isset($deal_uf_list)){
			foreach($deal_uf_list as $k => $v) {
				$uf['cmd'][$v['ID']] = 'crm.deal.userfield.get?'. http_build_query(['id' => $v['ID']]);
			}
		}
		
		$quote_uf_list = $result['result']['result'][$quote_uf];
		if (isset($quote_uf_list)){
			foreach($quote_uf_list as $k => $v) {
				$uf['cmd'][$v['ID']] = 'crm.quote.userfield.get?'. http_build_query(['id' => $v['ID']]);
		}
		}
		
		$invoice_uf_list = $result['result']['result'][$invoice_uf];
		if (isset($invoice_uf_list)){
			foreach($invoice_uf_list as $k => $v) {
				$uf['cmd'][$v['ID']] = 'crm.invoice.userfield.get?'. http_build_query(['id' => $v['ID']]);
		}
		}
		
		if (isset($uf)){
			$params_uf = [
                    'type' => 'batch',
                    'params' => $uf
                ];
		
		$result2 = $this->b24->callHook($params_uf);
		
		foreach ($result2['result']['result'] as $key => $value) {
			
			$v_id = isset($value['ID']) ? $value['ID'] : 0;
			$v_f_name = isset($value['FIELD_NAME']) ? $value['FIELD_NAME'] : 0;
			$v_f_label = isset($value['EDIT_FORM_LABEL'][''.self::CONFIG_UF_LANG.'']) ? $value['EDIT_FORM_LABEL'][''.self::CONFIG_UF_LANG.''] : 0;
			
			if ($value['ENTITY_ID'] == 'CRM_LEAD') {
				$uf[$lead_uf][$key]['ID'] = $v_id;
				$uf[$lead_uf][$key]['NAME'] = $v_f_name;
				$uf[$lead_uf][$key]['LABEL'] = $v_f_label;
			}elseif ($value['ENTITY_ID'] == 'CRM_CONTACT') {
				$uf[$contact_uf][$key]['ID'] = $v_id;
				$uf[$contact_uf][$key]['NAME'] = $v_f_name;
				$uf[$contact_uf][$key]['LABEL'] = $v_f_label;
			}elseif ($value['ENTITY_ID'] == 'CRM_COMPANY') {
				$uf[$company_uf][$key]['ID'] = $v_id;
				$uf[$company_uf][$key]['NAME'] = $v_f_name;
				$uf[$company_uf][$key]['LABEL'] = $v_f_label;
			}elseif ($value['ENTITY_ID'] == 'CRM_DEAL') {
				$uf[$deal_uf][$key]['ID'] = $v_id;
				$uf[$deal_uf][$key]['NAME'] = $v_f_name;
				$uf[$deal_uf][$key]['LABEL'] = $v_f_label;
			}elseif ($value['ENTITY_ID'] == 'CRM_QUOTE') {
				$uf[$quote_uf][$key]['ID'] = $v_id;
				$uf[$quote_uf][$key]['NAME'] = $v_f_name;
				$uf[$quote_uf][$key]['LABEL'] = $v_f_label;
			}elseif ($value['ENTITY_ID'] == 'CRM_INVOICE') {
				$uf[$invoice_uf][$key]['ID'] = $v_id;
				$uf[$invoice_uf][$key]['NAME'] = $v_f_name;
				$uf[$invoice_uf][$key]['LABEL'] = $v_f_label;
			}
		}
		return $uf;
		}
		
	}
}