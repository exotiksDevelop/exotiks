<?php

class ControllerExtensionModuleB24Apipro extends Controller
{
	private $error = array();

    private $minMaxParentIds = [];

	const CONFIG_USER_LIST = 'user_list';
	const CONFIG_MANAGER_ID = 'manager_id';
	const CONFIG_DEAL_UF = 'deal_uf';

	public function install(){		
		$delimiter = '$registry = new Registry();';
		$class = '$b24 = new B24();$registry->set(\'b24\', $b24);';
			
		$filename = $_SERVER['DOCUMENT_ROOT'] . '/system/framework.php';
		$indexArr = explode($delimiter, file_get_contents($filename, true));
		$index = $indexArr[0] . $delimiter . $class . $indexArr[1];
		file_put_contents($filename, $index);	
		
		$this->load->model('extension/event');

		$this->model_extension_event->addEvent('b24_status_edit', 'catalog/controller/api/order/history/after', 'module/b24_apipro/editOrderStatus');
		
		$this->model_extension_event->addEvent('b24_order_add', 'catalog/controller/checkout/success/before', 'module/b24_apipro/addOrder');
		//$this->model_extension_event->addEvent('b24_order_edit', 'post.order.edit', 'module/b24_apipro/editOrder');

		$this->model_extension_event->addEvent('b24_customer_add', 'catalog/model/account/customer/addCustomer/after', 'module/b24_apipro/addCustomer');
		$this->model_extension_event->addEvent('b24_customer_edit', 'catalog/model/account/customer/editCustomer/after', 'module/b24_apipro/editCustomer');

		$this->model_extension_event->addEvent('b24_customer_add_address', 'catalog/model/account/address/addAddress/after', 'module/b24_apipro/addAddress');
		$this->model_extension_event->addEvent('b24_customer_edit_address', 'catalog/model/account/address/editAddress/after', 'module/b24_apipro/editAddress');

        $this->model_extension_event->addEvent('b24_category_edit', 'admin/model/catalog/category/editCategory/after', 'extension/module/b24_apipro/edit');
        $this->model_extension_event->addEvent('b24_category_add',  'admin/model/catalog/category/addCategory/after', 'extension/module/b24_apipro/add');
        $this->model_extension_event->addEvent('b24_category_delete', 'admin/model/catalog/category/deleteCategory/after', 'extension/module/b24_apipro/delete');

        $this->model_extension_event->addEvent('b24_product_add', 'admin/model/catalog/product/addProduct/after', 'extension/module/b24_apipro/addproduct');
        $this->model_extension_event->addEvent('b24_product_edit', 'admin/model/catalog/product/editProduct/after', 'extension/module/b24_apipro/editproduct');
        $this->model_extension_event->addEvent('b24_product_delete', 'admin/model/catalog/product/deleteProduct/after', 'extension/module/b24_apipro/deleteproduct');

		$createTableSql = "CREATE TABLE IF NOT EXISTS `b24_order` (
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`oc_order_id` INT(11) NULL DEFAULT NULL,
					`b24_order_id` INT(11) NULL DEFAULT NULL,
					`date_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB
				;"
		;

		$createConfigTableSql = "CREATE TABLE IF NOT EXISTS `b24_order_config` (
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`name` VARCHAR(255) NULL DEFAULT NULL,
						`value` TEXT NULL,
						PRIMARY KEY (`id`),
						UNIQUE INDEX `name` (`name`)
					)
					COLLATE='utf8_general_ci'
					ENGINE=InnoDB
;"
		;

		$createCustomer = "CREATE TABLE IF NOT EXISTS `b24_customer` (
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`oc_customer_id` INT(11) NOT NULL,
						`b24_contact_id` INT(11) NOT NULL,
						`phone` VARCHAR(16) NOT NULL,
						`b24_contact_field` TEXT NULL,
						`date_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
						PRIMARY KEY (`id`),
						UNIQUE INDEX `b24_contact_id` (`b24_contact_id`)
					)
					COLLATE='utf8_general_ci'
					ENGINE=InnoDB				  
					;"
		;

		$createConfigCustomer = "CREATE TABLE IF NOT EXISTS `b24_customer_config` (
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`name` VARCHAR(255) NULL DEFAULT NULL,
						`value` TEXT NULL,
						PRIMARY KEY (`id`),
						UNIQUE INDEX `name` (`name`)
					)
					COLLATE='utf8_general_ci'
					ENGINE=InnoDB
;"
		;

        $creatCategory = "Create table if not exists b24_category(
              `id` INT(11) AUTO_INCREMENT,
              `oc_category_id` INT(11)  NOT NULL,
              `b24_category_id` INT(11) NOT NULL,
              PRIMARY KEY(id),
              UNIQUE b24_category_id (b24_category_id)
              )
              COLLATE='utf8_general_ci'
              ENGINE=InnoDB
;";

        $sqlProduct = "CREATE TABLE if not exists `b24_product` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `oc_product_id` INT(11) NOT NULL,
            `b24_product_id` INT(11) NOT NULL,
            `size` VARCHAR(255) NULL DEFAULT NULL,
            `date_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE INDEX `size_product_id` (`size`, `oc_product_id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
;";

        $sqlStatus = "CREATE TABLE if not exists `b24_status_map` (
            `oc_status_id` INT(11) NOT NULL,
            `b24_status_id` VARCHAR(32) NULL DEFAULT NULL,
            `b24_stage_id` VARCHAR(32) NULL DEFAULT NULL
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
;";

		$this->db->query($createTableSql);
		$this->db->query($createConfigTableSql);
		$this->db->query($createCustomer);
		$this->db->query($createConfigCustomer);
        $this->db->query($creatCategory);
        $this->db->query($sqlProduct);
        $this->db->query($sqlStatus);
	}

	public function uninstall(){	
		$delimiter = '$b24 = new B24();$registry->set(\'b24\', $b24);';
			
		$filename = $_SERVER['DOCUMENT_ROOT'] . '/system/framework.php';
		$indexArr = explode($delimiter, file_get_contents($filename, true));
		$index = $indexArr[0] . $indexArr[1];
		file_put_contents($filename, $index);
		
		$this->load->model('extension/event');
		
		$this->model_extension_event->deleteEvent('b24_status_edit');

		$this->model_extension_event->deleteEvent('b24_order_add');
		$this->model_extension_event->deleteEvent('b24_order_edit');

		$this->model_extension_event->deleteEvent('b24_customer_edit');
		$this->model_extension_event->deleteEvent('b24_customer_add');

		$this->model_extension_event->deleteEvent('b24_customer_add_address');
		$this->model_extension_event->deleteEvent('b24_customer_edit_address');

        $this->model_extension_event->deleteEvent('b24_category_edit');
        $this->model_extension_event->deleteEvent('b24_category_add');
        $this->model_extension_event->deleteEvent('b24_category_delete');

        $this->model_extension_event->deleteEvent('b24_product_edit');
        $this->model_extension_event->deleteEvent('b24_product_add');
        $this->model_extension_event->deleteEvent('b24_product_delete');
	}

	public function __construct( $registry ){
		parent::__construct($registry);
		
        $this->load->model('setting/setting');

        $b24_setting = $this->model_setting_setting->getSetting('b24_hook_key');
		
		if ($registry->has('b24')){
			$this->b24->setFields($b24_setting);
		} 
	}

	public function index() {
        set_time_limit(9999);

		$this->load->language('extension/module/b24_apipro');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');
        $this->load->model('extension/module/b24_category');
        $this->load->model('extension/module/b24_product');
		$this->load->model('localisation/order_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (isset($this->request->post['refresh-user-list']) && $this->request->post['refresh-user-list'] != '') {
				$this->refreshUserList();
			} elseif (isset($this->request->post['set-manager']) && $this->request->post['set-manager'] != '') {
				$managerId = $this->request->post['manager'];
				$this->setManager($managerId);
			} elseif (isset($this->request->post['save-statuses']) && $this->request->post['save-statuses'] != '') {
				$this->saveStatuses($this->request->post['statuses'],$this->request->post['stages']);
			} elseif (isset($this->request->post['save-stageses']) && $this->request->post['save-stageses'] != '') {
				$this->saveStages($this->request->post['stages']);				
			} elseif (isset($this->request->post['save-hooks']) && $this->request->post['save-hooks'] != '') {
				$this->model_setting_setting->editSetting('b24_out_hooks', $this->request->post['b24_out_hooks']);
			}

            if (isset($this->request->post['b24_hook_key_id']) && isset($this->request->post['b24_hook_key_api']) && isset($this->request->post['b24_hook_key_domain'])) {
                $b24_hook_key_id = $this->request->post['b24_hook_key_id'];
                $b24_hook_key_api = $this->request->post['b24_hook_key_api'];
                $b24_hook_key_domain = $this->request->post['b24_hook_key_domain'];

                // update b24_hook_key_id
                if (isset($b24_setting['b24_hook_key_id'])) {
                    if (!empty($b24_hook_key_id)) {
                        $this->model_setting_setting->editSettingValue('b24_hook_key', 'b24_hook_key_id', $b24_hook_key_id);
                    }
                } else {
                    // add b24_hook_key_id
                    $b24_setting['b24_hook_key_id'] = $b24_hook_key_id;
                    $this->model_setting_setting->editSetting('b24_hook_key', $b24_setting);
                }

                // update b24_hook_key_api
                if (isset($b24_setting['b24_hook_key_api'])) {
                    if (!empty($b24_hook_key_api)) {
                        $this->model_setting_setting->editSettingValue('b24_hook_key', 'b24_hook_key_api', $b24_hook_key_api);
                    }
                } else {
                    // add b24_hook_key_api
                    $b24_setting['b24_hook_key_api'] = $b24_hook_key_api;
                    $this->model_setting_setting->editSetting('b24_hook_key', $b24_setting);
                }

                // update b24_hook_key_domain
                if (isset($b24_setting['b24_hook_key_domain'])) {
                    if (!empty($b24_hook_key_domain)) {
                        $this->model_setting_setting->editSettingValue('b24_hook_key', 'b24_hook_key_domain', $b24_hook_key_domain);
                    }
                } else {
                    // add b24_hook_key_domain
                    $b24_setting['b24_hook_key_domain'] = $b24_hook_key_domain;
                    $this->model_setting_setting->editSetting('b24_hook_key', $b24_setting);
                }
				
				if (!isset($b24_setting['b24_hook_key_id']) && !isset($b24_setting['b24_hook_key_api']) && !isset($b24_setting['b24_hook_key_domain'])){
                    // delete
                    $this->model_setting_setting->deleteSetting('b24_hook_key');
                }
            }
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
			'href' => $this->url->link('extension/extension', 'token=' . $data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/b24_apipro', 'token=' . $data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/b24_apipro', 'token=' . $data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/b24_apipro', 'token=' . $data['token'], 'SSL');
            $data['action_web_hook'] = $this->url->link('module/b24_apipro/setwebhookkey', 'token=' . $data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/b24_apipro', 'token=' . $data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
            $data['action_web_hook'] = $this->url->link('module/b24_apipro/setwebhookkey', 'token=' . $data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
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

        $data['b24_hook_key_id'] = '';
        $data['b24_hook_key_api'] = '';
        $data['b24_hook_key_domain'] = '';
		
        $b24_setting = $this->model_setting_setting->getSetting('b24_hook_key');

        if (!empty($b24_setting)) {
			if (isset($b24_setting['b24_hook_key_api'])){	
				$data['b24_hook_key_api'] = $b24_setting['b24_hook_key_api'];		
			}
			
			if (isset($b24_setting['b24_hook_key_id'])){
				$data['b24_hook_key_id'] = $b24_setting['b24_hook_key_id'];		
			}
			
			if (isset($b24_setting['b24_hook_key_domain'])){
				$data['b24_hook_key_domain'] = $b24_setting['b24_hook_key_domain'];			
			}
        }

		$data['scope_list'] = @$this->getScopeList();

		$data['user_list'] = $this->getUserList();
		$data['manager_id'] = $this->getManagerId();
		$this->refreshDealUF();

        $data['synchronizationcategories'] = $this->model_extension_module_b24_category->getCategoryRows();
        $data['button_synchronizationcategories'] = $this->url->link('extension/module/b24_apipro/synchronizationcategories', 'token=' . $data['token'], 'SSL');

        $data['synchronizationproducts'] = $this->model_extension_module_b24_product->getProductRows();
        $data['button_synchronizationproducts'] = $this->url->link('extension/module/b24_apipro/synchronizationproductsbatch', 'token=' . $data['token'], 'SSL');
		
        $data['button_synchronizationcontacts'] = $this->url->link('extension/module/b24_apipro/synchronizationcontacts', 'token=' . $data['token'], 'SSL');
		
		$data['b24_statuses'] = $this->getStatusList();
		$data['b24_stages'] = $this->getStagesList();
		$data['oc_statuses'] = $this->model_localisation_order_status->getOrderStatuses(array());
		$data['statuses'] = $this->savedStatuses();		
		//$data['stages'] = $this->savedStageses();		
		$data['b24_out_hooks'] = $this->model_setting_setting->getSetting('b24_out_hooks');

		$this->response->setOutput($this->load->view('extension/module/b24_apipro.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/b24_apipro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		//if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
		//	$this->error['name'] = $this->language->get('error_name');
		//}

		return !$this->error;
	}
	
	public function saveStatuses($statuses, $stages) {
		$this->load->model('localisation/order_status');
		$results = $this->model_localisation_order_status->getOrderStatuses();
		$this->db->query("DELETE FROM `b24_status_map` WHERE 1");
		
		foreach ($results as $result){
			$this->db->query("INSERT INTO `b24_status_map`(`oc_status_id`, `b24_status_id`, `b24_stage_id`) VALUES (" . (int)$result['order_status_id'] . ", '', '')");
		}
		
		foreach ($statuses as $b24_status => $oc_status){
			$this->db->query("UPDATE `b24_status_map` SET `b24_status_id` = '" . $b24_status . "' WHERE oc_status_id = '" . $oc_status . "'");
		}
		foreach ($stages as $b24_stage => $oc_status){
			$this->db->query("UPDATE `b24_status_map` SET `b24_stage_id` = '" . $b24_stage . "' WHERE oc_status_id = '" . $oc_status . "'");
		}
	}
	public function savedStatuses() {
		$result = array();
		
		$q = $this->db->query("SELECT * FROM `b24_status_map` WHERE 1");
		
		foreach ($q->rows as $row){
			$result['statuses'][$row['b24_status_id']] = $row['oc_status_id'];
			$result['stages'][$row['b24_stage_id']] = $row['oc_status_id'];
		}
		
		return $result;
	}
	
	public function getStagesList() {		
		$params = [
			'type' => 'crm.status.list',
			'params' => [
				'filter' => ["ENTITY_ID" => "DEAL_STAGE"]
			]
		];

		$result = $this->b24->callHook($params);

		return $result['result'];
	}
	
	public function getStatusList() {		
		$params = [
			'type' => 'crm.status.list',
			'params' => [
				'filter' => ["ENTITY_ID" => "STATUS"]
			]
		];

		$result = $this->b24->callHook($params);

		return $result['result'];
	}

	public function getScopeList()
	{
		$params = [
			'type' => 'scope',
			'params' => ['full' => true]
		];

		$result = $this->b24->callHook($params);

		return $result['result'];
	}

	public function getUserList()
	{
		$sql = 'SELECT value  FROM b24_customer_config WHERE `name` = "' . self::CONFIG_USER_LIST . '";';
		$query = $this->db->query($sql);

        if (isset($query->row['value'])) {
            return json_decode($query->row['value'], 1);
        } else {
            return array();
        }
	}

	public function getManagerId()
	{
		$sql = 'SELECT value  FROM b24_customer_config WHERE `name` = "' . self::CONFIG_MANAGER_ID . '";';
		$query = $this->db->query($sql);

        if (isset($query->row['value'])) {
            return $query->row['value'];
        } else {
            return array();
        }
	}

	function refreshDealUF()
	{
		$params = [
			'type' => 'crm.deal.userfield.list',
			'params' => [
				'filter' => ["MANDATORY"=> "N", 'LANG' => 'ru']
			]
		];

		$result = $this->b24->callHook($params);

		if (!empty($result['result'])) {
			$newUF = [];
			foreach ($result['result'] as $uf) {
				$label = trim($uf['EDIT_FORM_LABEL']);
				$newuf[$label]  = $uf;
				if ($uf['USER_TYPE_ID'] == 'enumeration') {
					foreach ($uf['LIST'] as $option) {
						$optLabel = trim($option['VALUE']);
						$newuf[$label]['OPTION'][$optLabel] = $option;
					}
				}
			}

			$newuf = json_encode($newuf, JSON_UNESCAPED_UNICODE);
			$sql = "REPLACE into b24_customer_config SET `value` = '{$newuf}', `name` = '" . self::CONFIG_DEAL_UF . "';";

			$this->db->query($sql);
		}
	}

	public function refreshUserList()
	{
		$params = [
			'type' => 'user.get',
			'params' => []
		];

		$result = $this->b24->callHook($params);
		$userList = json_encode($result['result'], JSON_UNESCAPED_UNICODE);

		$sql = "REPLACE into b24_customer_config SET `value` = '{$userList}', `name` = '" . self::CONFIG_USER_LIST . "';";

		$this->db->query($sql);
	}

	public function setManager($managerId)
	{
		if(abs($managerId) <= 0 ){ trigger_error('Manager ID must be integer', E_USER_WARNING);}
		$sql = "REPLACE into b24_customer_config SET `value` = '{$managerId}', `name` = '" . self::CONFIG_MANAGER_ID . "';";

		$this->db->query($sql);
	}

    // Category
    public function synchronizationcategories(){
        $this->load->model('catalog/category');
        $this->load->model('extension/module/b24_category');

        $minMaxParentIds = $this->model_extension_module_b24_category->getMinMaxParentIds();

        $this->minMaxParentIds = $minMaxParentIds;

        $minParentId = min($minMaxParentIds);

        if (isset($minParentId['parent_id']) && is_numeric($minParentId['parent_id']) && (int)$minParentId['parent_id'] >= 0) /*{
            $fields = $this->model_extension_module_b24_category->getById($minParentId['parent_id']);

            $lastId = isset($fields['b24_category_id']) ? $fields['b24_category_id'] : 0;
        } else*/ {
            // Корневая папка товаров - папка с названием магазина. Подпапки - это категории магазина.
            $params = [
                'type' => 'crm.productsection.add',
                'params' => [
                    'fields' => [
                        'NAME' => html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . ' (' . $this->request->server['SERVER_NAME'] . ')',
                    ]
                ]
            ];

            $result = $this->b24->callHook($params);

            $lastId = $result['result'];

            $data = ['oc_category_id' => $minParentId['parent_id'], 'b24_category_id' => $lastId];
            $this->model_extension_module_b24_category->addToDB($data);
        }

        $this->addCategoriesBatch($lastId);

        $this->response->redirect($this->url->link('extension/module/b24_apipro', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function addCategoriesBatch($parentCategory = 0){
        $build = [];

        $minMaxParentId = $this->minMaxParentIds;

        foreach ($minMaxParentId as $minMaxIds) {
            $parentId = $minMaxIds['parent_id'];
            $categoriesInfo = $this->model_catalog_category->getCategoriesByParentId($parentId);

            $recod = $this->model_extension_module_b24_category->getById($parentId);

            $parentId = !empty($recod)? $recod['b24_category_id'] : $parentCategory;

            foreach ($categoriesInfo as $info) {
                $categoryId = $info['category_id'];
                $categoryName = $info['name'];

                $fields = [
                    'CATALOG_ID' => $categoryId,
                    'NAME'=> $categoryName,
                    'SECTION_ID'=> $parentId,
                ];

                $build['cmd'][$categoryId] = 'crm.productsection.add?'. http_build_query(['fields' => $fields]);
            }

            if (!empty($build['cmd'])) {
                $params = [
                    'type' => 'batch',
                    'params' => $build
                ];

                $result = $this->b24->callHook($params);

                if (!empty($result['result']['result_error'])) {
                    trigger_error('Ошибка при запросе addCategoriesBatch ' . print_r($result['result']['result_error'], 1));
                } else {
                    $ids = $result['result']['result'];
                    $this->model_extension_module_b24_category->addBatchToDB($ids);
                }

                $build['cmd'] = [];
            }
        }
    }

    public function add($name = '', $data = [], $categoryId = 0){
        if (!isset($data[0])) {
            return;
        }

        $categoryId = !empty($data[0]) ? $data[0] : 0;
		
		$this->load->model('catalog/category');
        $this->load->model('extension/module/b24_category');

        $minMaxParentIds = $this->model_extension_module_b24_category->getMinMaxParentIds();

        $lastId = 0;

        $this->minMaxParentIds = $minMaxParentIds;

        $minParentId = min($minMaxParentIds);

        if (isset($minParentId['parent_id']) && is_numeric($minParentId['parent_id']) && 0 <= $minParentId['parent_id']) {
            $fields = $this->model_extension_module_b24_category->getById($minParentId['parent_id']);
            $lastId = isset($fields['b24_category_id']) ? $fields['b24_category_id'] : 0;
        }

        $category = $this->model_catalog_category->getCategory($categoryId);
        $categoryName = $category['name'];
        $recod = $this->model_extension_module_b24_category->getById($category['parent_id']);
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
        $this->model_extension_module_b24_category->addToDB($data);

        // Для первого запуска. Создает вложенные разделы
        //$childrens = $this->model_catalog_category->getCategoriesByParentId($categoryId);
        //foreach ( $childrens as $children )
        //{
        //	$categoryId = $children['category_id'];
        //	$parentId = $lastId;
        //	$categoryName = $children['name'];
        //
        //	$params = [
        //		'type' => $typeApi,
        //		'params' => [
        //			'fields' => [
        //				'CATALOG_ID'=> $categoryId,
        //				'NAME'=> $categoryName,
        //				'SECTION_ID'=> $parentId,
        //			]
        //		]
        //	];
        //
        //	$result = $this->b24->callHook($params);
        //	sleep(1);
        //	$b24Id = $result['result'];
        //
        //	$this->db->query("UPDATE " . DB_PREFIX . "category_description SET id_category_b24 = '" . $this->db->escape($b24Id) . "' WHERE category_id = '" . (int)$categoryId . "'");
        //
        //}

        $txt = print_r($params,1) . print_r($result, 1);
        add2Log($txt);

        return $lastId;
    }

    public function edit($name = '', $data = [], $param3 = 0){		
        if (!isset($data[0])) {
            return;
        }

        $categoryId = !empty($data[0]) ? $data[0] : 0;

        $this->load->model('catalog/category');
        $this->load->model('extension/module/b24_category');

        $category = $this->model_catalog_category->getCategory($categoryId);
        $categoryName = $category['name'];

        // Извлекаем родительскую категорию из Кастомной таблицы
        $parentId = $category['parent_id'];

        if (!empty($parentId)) {
            //$categoryRow = $this->model_catalog_category->getCategory($parentId);
            $parentCategory = $this->model_extension_module_b24_category->getById($parentId);
            if (isset($parentCategory['b24_category_id'])) {
                $parentId = $parentCategory['b24_category_id'];
            } else {
                $parentId = $this->add('', [], $parentId);
            }
        } else {
            $minMaxParentIds = $this->model_extension_module_b24_category->getMinMaxParentIds();
            $minParentId = min($minMaxParentIds);

            if (isset($minParentId['parent_id']) && is_numeric($minParentId['parent_id']) && 0 <= $minParentId['parent_id']) {
                $fields = $this->model_extension_module_b24_category->getById($minParentId['parent_id']);
                $parentId = isset($fields['b24_category_id']) ? $fields['b24_category_id'] : 0;
            }
        }

        $b24_category_id = $this->model_extension_module_b24_category->getById($categoryId);
        $b24_category_id = isset($b24_category_id['b24_category_id']) ? $b24_category_id['b24_category_id'] : '';

        // Создаем или обновляем категорию
        if (empty($b24_category_id)) {
            $this->add('', [], $categoryId);
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

            //$lastId = $result['result'];

            // При редактировании ИД раздела не меняется
            //$data = ['oc_category_id' => $categoryId, 'b24_category_id' => $lastId];
            //$this->addToDB($data);

            $txt = 'Обновление: ' . print_r($params,1) . print_r($result, 1);
            add2Log($txt);
        }
    }

    public function delete($name = '', $data = [], $param3 = 0) {		
        if (!isset($data[0])) {
            return;
        }

        $categoryId = !empty($data[0]) ? $data[0] : '';

        $this->load->model('catalog/category');
        $this->load->model('extension/module/b24_category');

        $categoryRow = $this->model_extension_module_b24_category->getById($categoryId);
        $b24_category_id = isset($categoryRow['b24_category_id']) ? $categoryRow['b24_category_id'] : '';

        if (!empty($b24_category_id)) {
            $typeApi = 'crm.productsection.delete';
            $params = [
                'type' => $typeApi,
                'params' => [
                    'id' => $b24_category_id,
                ]
            ];
            $result = $this->b24->callHook($params);
            $txt = 'Удаление: ' . print_r($params,1) . print_r($result, 1);
            add2Log($txt);
        }
    }
    // Category

    // Product
    public function synchronizationproductsbatch()
    {
        $this->load->model('extension/module/b24_product');
        $this->model_extension_module_b24_product->addProductsBatch();

        $this->response->redirect($this->url->link('extension/module/b24_apipro', 'token=' . $this->session->data['token'], 'SSL'));
    }
	
    // Contacts
    public function synchronizationcontacts(){
        $this->load->model('extension/module/b24_customer');
		
        $this->model_extension_module_b24_customer->cynchContacts($this->model_extension_module_b24_customer->getContacts());

        $this->response->redirect($this->url->link('extension/module/b24_apipro', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function addproduct($name = '', $data = [], $productId = 0)
    {
	if (!isset($data[0])) {
            return;
        }

        $productId = !empty($data[0]) ? $data[0] : 0;
        $this->load->model('extension/module/b24_product');

        $dataToAdd = $this->model_extension_module_b24_product->prepareDataToB24($productId);
        $this->model_extension_module_b24_product->addProductBatch($productId, $dataToAdd);
    }

    public function editproduct($name = '', $data = [], $param3 = 0)
    {
        if (!isset($data[0])) {
            return;
        }

        $productId = !empty($data[0]) ? $data[0] : 0;

        $this->load->model('extension/module/b24_product');

        $dataToUpdate = $this->model_extension_module_b24_product->prepareDataToB24($productId);
        $this->model_extension_module_b24_product->editProductBatch($productId, $dataToUpdate);
    }

    public function deleteproduct($name = '', $data = [], $param3 = 0)
    {
        if (!isset($data[0])) {
            return;
        }

        $productId = !empty($data[0]) ? $data[0] : 0;

        $this->load->model('extension/module/b24_product');

        $this->model_extension_module_b24_product->deleteProduct($productId);
    }

    public function getFileToImport()
    {
        $this->load->model('catalog/product');
        $this->load->model('module/b24_product');
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/b24_api/product_import.csv';
        $file = fopen($filename, 'w+');

        $productList = $this->model_catalog_product->getProducts(['sort' => 'product_id']);
        //$index = 1;
        $columnNames = [];
        foreach ($productList as $key => $product) {
            $dataToCSV = $this->model_extension_module_b24_product->prepareDataToB24($product['product_id']);

            foreach ($dataToCSV as $sizeName => $newData) {
                unset($newData['DESCRIPTION']);

                if (empty($columnNames)) {
                    $columnNames = array_keys($newData);
                    fputcsv($file, $columnNames, ';', '"');
                }

                $newData['MEASURE'] = 'шт';
                //$newData['ID'] = $index;
                fputcsv($file, $newData, ';', '"');
                //$index++;
            }

            //if($key == 10){
            //	break;
            //}
        }

        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filename).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
        }
    }

    public function exportToB24()
    {
        $this->load->model('catalog/product');
        $this->load->model('module/b24_product');

        //$filename = $_SERVER['DOCUMENT_ROOT'] . '/b24_api/product_import.csv';
        //$file = fopen($filename, 'r');
        //$columnNames = fgetcsv($file, false, ';');

        //$columnNames = array_flip($columnNames);
        //$PROPERTY_OC_ID = $this->model_extension_module_b24_product->PROPERTY_OC_ID;
        $index = 0;
        //$productList = $this->model_catalog_product->getProducts(['limit' => 1]);
        $productList = $this->model_catalog_product->getProducts();

        foreach ($productList as $product) {
            $dataToB24 = $this->model_extension_module_b24_product->prepareDataToB24($product['product_id']);

            foreach ($dataToB24 as $sizeName => $newData) {
                //$dataFromCsv = array_combine($columnNames, $dataFromCsv);

                $PROPERTY_OC_ID = $this->model_extension_module_b24_product->PROPERTY_OC_ID;
                $PROPERTY_SIZE = $this->model_extension_module_b24_product->PROPERTY_SIZE;
                $name = $newData[$PROPERTY_OC_ID] . ';' . $newData[$PROPERTY_SIZE];

                $b24Row = $this->model_extension_module_b24_product->getList([
                    'oc_product_id' => $newData[$PROPERTY_OC_ID],
                    'size' => $newData[$PROPERTY_SIZE],
                ]);

                $isAlreadyAdded = !empty($b24Row['b24_product_id'][0]);
                if ($isAlreadyAdded) {
                    $dataToUpdate[$name] = "crm.product.update?id={$b24Row['b24_product_id']}&" . http_build_query(['fields' => $newData]);
                } else {
                    $dataToAdd[$name] = 'crm.product.add?' . http_build_query(['fields' => $newData]);
                }

                $index++;

                if (isset($dataToUpdate) && count($dataToUpdate) > 45) {
                    $result = $this->model_extension_module_b24_product->sendBatchQuery($dataToUpdate);
                    $this->model_extension_module_b24_product->updateToDB(1, $result['result']);
                    $this->printMessage('update', $dataToUpdate);
                    $dataToUpdate = [];
                    usleep(50000);
                }

                if (isset($dataToAdd) && count($dataToAdd) > 45) {
                    $result = $this->model_extension_module_b24_product->sendBatchQuery($dataToAdd);
                    $this->model_extension_module_b24_product->addToDB(1, $result['result']);
                    $this->printMessage('add', $dataToAdd);
                    $dataToAdd = [];
                    usleep(50000);
                }
            }

            if ($index >= 10) break;
        }

        //while ( $dataFromCsv = fgetcsv($file, false, ';') )
        //{
        //
        //}

        // остаточные товары
        if (!empty($dataToUpdate)) {
            $result = $this->model_extension_module_b24_product->sendBatchQuery($dataToUpdate);
            $this->model_extension_module_b24_product->updateToDB(1, $result['result']);
            $this->printMessage('update', $dataToUpdate);
        }

        if (!empty($dataToAdd)) {
            $result = $this->model_extension_module_b24_product->sendBatchQuery($dataToAdd);
            $this->model_extension_module_b24_product->addToDB(1, $result['result']);
            $this->printMessage('add', $dataToAdd);
        }

        $index = 0;
    }

    public function printMessage($type, array $data)
    {
        foreach ( $data as $sizeName => $item )
        {
            list( $productId, $size) = explode(';', $sizeName);
            $msg = $type == 'add'
                ? "<br> Добавлен товар с ИД $productId размер $size"
                : "<br> Обновлен товар с ИД $productId размер $size"
            ;

            echo $msg;
            flush();
        }

    }

    public function crmProductList(){		
        $this->load->model('extension/module/b24_product');
        $propertyProductId = $this->model_extension_module_b24_product->PROPERTY_OC_ID;
        $propertySize = $this->model_extension_module_b24_product->PROPERTY_SIZE;

        $params = [
            'type' => 'crm.product.list',
            'params' => [
                'start' => 0,
                'order' => ['ID' => "ASC"],
                'select' => ['ID', 'PROPERTY_*']
            ],
        ];

        do {
            $result = $this->b24->callHook($params);
            foreach ($result['result'] as $product) {
                $fields = [ 'b24_product_id' => $product['ID'],
                    'oc_product_id' => $product[$propertyProductId]['value'],
                    'size' => $product[$propertySize]['value']
                ];

                $this->model_extension_module_b24_product->insertToDB('b24_product', $fields);
            }

            $params['params']['start'] = $result['next'];
        } while(!empty($result['next']));

        return $result;
    }

    // Product
}