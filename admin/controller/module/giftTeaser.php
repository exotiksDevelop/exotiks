<?php
//ADMIN
class ControllerModuleGiftTeaser extends Controller {
  	private $moduleName;
    private $modulePath;
	private $moduleModel;
	private $moduleVersion;
    private $extensionsLink;
    private $callModel;
    private $error = array(); 
    private $data = array();

    public function __construct($registry) {
        parent::__construct($registry);
        
        // Config Loader
        $this->config->load('ocmod/giftteaser');
        
        // Module Constants
        $this->moduleName           = $this->config->get('giftteaser_name');
        $this->moduleNameSmall      = $this->config->get('giftteaser_name_small');
        $this->callModel            = $this->config->get('giftteaser_model');
        $this->modulePath           = $this->config->get('giftteaser_path');
	    $this->moduleVersion        = $this->config->get('giftteaser_version');   
		$this->moduleData_module    = $this->config->get('giftteaser_module_data');        
        $this->extensionsLink       = $this->url->link($this->config->get('giftteaser_link'), 'token=' . $this->session->data['token'].$this->config->get('giftteaser_link_params'), 'SSL');

        // Load Language
        $this->load->language($this->modulePath);
        
        // Load Model
        $this->load->model($this->modulePath);
		        
        // Model Instance
        $this->moduleModel          = $this->{$this->callModel};
		

        // Global Variables      
        $this->data['moduleName']  		 = $this->moduleName;
		$this->data['moduleNameSmall']   = $this->moduleNameSmall;
        $this->data['modulePath']   	 = $this->modulePath;
		$this->data['feedPath']   	 	 = $this->feedPath;
		$this->data['moduleData_module'] = $this->moduleData_module;
		$this->data['moduleModel'] 		 = $this->moduleModel;
	   
        $this->data['limit']        = 15;
    }	
	
    public function index() { 

        $this->load->model('setting/store');
        $this->load->model('localisation/language');
        $this->load->model('design/layout');
        $this->load->model('setting/setting');
		
        $catalogURL = $this->getCatalogURL();
		$this->document->addScript('view/javascript/'.$this->moduleName.'/timepicker.js');
		$this->document->addScript('view/javascript/'.$this->moduleName.'/bootbox.js');
		$this->document->addScript('view/javascript/'.$this->moduleName.'/modal.js');
		$this->document->addScript('view/javascript/'.$this->moduleName.'/modal-manager.js');
		$this->document->addStyle('view/stylesheet/'.$this->moduleName.'/modal.css');
		$this->document->addStyle('view/stylesheet/'.$this->moduleName.'/timepicker.css');
		$this->document->addStyle('view/javascript/'.$this->moduleName.'/colorpicker/css/colorpicker.css');
		$this->document->addScript('view/javascript/'.$this->moduleName.'/colorpicker/js/colorpicker.js');
	    $this->document->addScript('view/javascript/'.$this->moduleName.'/main.js');
		$this->document->addStyle('view/stylesheet/'.$this->moduleName.'/giftTeaser.css');
	    $this->document->setTitle($this->language->get('heading_title'));

        if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] = 0; 
        }
	
        $store = $this->getCurrentStore($this->request->get['store_id']);
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) { 
            if (!$this->user->hasPermission('modify', $this->modulePath)) {
                $this->response->redirect($this->extensionsLink);
            }

            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post[$this->moduleName]['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }

            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post[$this->moduleName]['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }
			
       	 	$this->load->model('setting/setting');

            $this->model_setting_setting->editSetting($this->moduleName, $this->request->post, $this->request->post['store_id']);
            $this->session->data['success'] = $this->language->get('text_success');
			
            $this->response->redirect($this->url->link($this->modulePath, 'store_id='.$this->request->post['store_id'] . '&token=' . $this->session->data['token'], 'SSL'));
        }
 		
		$this->data['error_code'] = '';
        
 		if (isset($this->session->data['success'])) {     
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->error['warning'])) { 
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['breadcrumbs']   = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->extensionsLink,
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($this->modulePath, 'token=' . $this->session->data['token'], 'SSL'),
        );
		
        $languageVariables = array(
            'entry_code',
            'error_input_form',
            'text_yes',
            'text_no',
            'text_default',
            'text_enabled',
            'text_disabled',
            'text_text',
            'save_changes',
            'button_cancel',
            'text_settings',
            'button_add',
            'button_edit',
            'button_remove',
            'text_special_duration',
            'entry_layout_options',
            'entry_position_options',
            'entry_layout',         
            'entry_position',       
            'entry_status',         
            'entry_sort_order',     
            'entry_layout_options',  
            'entry_position_options',
            'text_content_top', 
            'text_content_bottom',
            'text_column_left', 
            'text_column_right',
            'button_add_module',
            'button_remove',
            'default_heading_title',
            'custom_design',
            'widget_help',
            'store_front_widget',
            'text_custom',
            'text_text',
            'text_background',
            'text_border',
            'custom_css',
            'text_condition',
            'custom_colors',
            'wrap_in_widget',
            'heading_background',
            'gift_image_size',
            'gift_image_size_help',
            'entry_default',
            'add_new_gift',
            'entry_free_gift_label',
			'show_free_gifts',
			'show_free_gifts_help',
			'free_gifts_message_title',
			'free_gifts_message_title_help',
			'entry_customer_group',
          );
       
        foreach ($languageVariables as $languageVariable) {
            $this->data[$languageVariable] = $this->language->get($languageVariable);
        }
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['maxGiftId'] = $this->moduleModel->getMaxGiftId();
        $this->data['stores'] = array_merge(array(0 => array('store_id' => '0', 'name' => $this->config->get('config_name') . ' ' . $this->data['text_default'], 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER)), $this->model_setting_store->getStores());
        $this->data['error_warning']          = '';  
        $this->data['languages']              = $this->model_localisation_language->getLanguages();
        $this->data['store']                  = $store;
        $this->data['token']                  = $this->session->data['token'];
        $this->data['action']                 = $this->url->link($this->modulePath, 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel']                 = $this->extensionsLink;
        $this->data['data']                   = $this->model_setting_setting->getSetting($this->moduleName, $store['store_id']);
		$this->data['language']               = $this->config->get('config_language');


		//2.2.0.0 language flag image fix
		foreach ($this->data['languages'] as $key => $value) {
			if(version_compare(VERSION, '2.2.0.0', "<")) {
				$this->data['languages'][$key]['flag_url'] = 'view/image/flags/'.$this->data['languages'][$key]['image'];

			} else {
				$this->data['languages'][$key]['flag_url'] = 'language/'.$this->data['languages'][$key]['code'].'/'.$this->data['languages'][$key]['code'].'.png"';
			}
		}
		
        $this->data['catalog_url']            = $catalogURL;
	    
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		  if ($this->config->get($this->moduleName.'_status')) { 
            $this->data[$this->moduleName.'_status'] = $this->config->get($this->moduleName.'_status'); 
        } else {
            $this->data[$this->moduleName.'_status'] = '0';
        }
 
        $this->data['header']                 = $this->load->controller('common/header');
        $this->data['column_left']            = $this->load->controller('common/column_left');
        $this->data['footer']                 = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view($this->modulePath.'.tpl', $this->data)); 
		
	  }

	public function autocompleteProduct() {
		$json = array();
		$this->load->model('tool/image');
		if (isset($this->request->post['filter_name']) &&  isset($this->request->post['store_id']) ) {
			if (isset($this->request->post['filter_name'])) {
				$filter_name = $this->request->post['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = $this->data['limit'];	
			}			

			$data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit,
				'store_id'	   => $this->request->post['store_id']
			);

			$results = $this->moduleModel->getProducts($data);
			
			foreach ($results as $result) {
	
				$option_data = array();
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),	
					'model'      => $result['model'],
					'price'      => $result['price'],
					'quantity' 	 => $result['quantity'],
					'viewed' 	 => $result['viewed'],
					'date_added' => $result['date_added'],
					'image'		 => $this->model_tool_image->resize($result['image'], 100, 100),
					'link'       => $this->getCatalogURL().'index.php?route=product/product&product_id=' . $result['product_id'],
					'date_added' => $result['date_added'],
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}
	
	public function autocompleteCategory() {
		$json = array();
		if (isset($this->request->post['filter_name']) && isset($this->request->post['store_id'])) {
			$data = array('filter_name' => $this->request->post['filter_name'], 'store_id' => $this->request->post['store_id']);
			$results = $this->moduleModel->getCategories($data);
			if(isset($results)) {
				foreach ($results as $result) {
					$json[] = array(
						'category_id' 	=> $result['category_id'], 
						'name'        	=> strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
						'link'       	=> $this->getCatalogURL().'index.php?route=product/category&path=' . $result['path_id'],
						'date_added' 	=> $result['date_added'],	
					);
				}	
			}
		}
		$sort_order = array();
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		array_multisort($sort_order, SORT_ASC, $json);
		$this->response->setOutput(json_encode($json));
	}	

    public function giftList() { 		
    	$this->load->model('tool/image');

        $this->data['currencyRight'] = $this->currency->getSymbolRight($this->config->get('config_currency'));
        $this->data['currencyLeft'] =  $this->currency->getSymbolLeft($this->config->get('config_currency'));
		 
      
		$sort = 'start_date';
    	$order = 'ASC';
    	$page = 1;
        
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        }

        if (isset($this->request->get['page']) && $this->request->get['page'] != 0) {
            $page = $this->request->get['page'];
        }
            
        $url = '';
		
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data   = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->data['limit'],//$this->config->get('config_admin_limit'),
            'limit' => $this->data['limit'],//$this->config->get('config_admin_limit'),
            'store_id' => $this->request->get['store_id']
        );    
		
        $gifts = $this->moduleModel->getGifts($data); 

        $url = '';
        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        $this->data['name']    = 'index.php?route='.$this->modulePath.'/giftList&store_id='.$this->request->get['store_id'] . '&token=' . $this->session->data['token'] . '&sort=pd.name' . $url;
		$this->data['quantity']     = 'index.php?route='.$this->modulePath.'/giftList&store_id='.$this->request->get['store_id'] . '&token=' . $this->session->data['token'] . '&sort=p.quantity' . $url;
		$this->data['start_date']   = 'index.php?route='.$this->modulePath.'/giftList&store_id='.$this->request->get['store_id'] . '&token=' . $this->session->data['token'] . '&sort=gt.start_date' . $url;
		$this->data['end_date'] 	= 'index.php?route='.$this->modulePath.'/giftList&store_id='.$this->request->get['store_id'] . '&token=' . $this->session->data['token'] . '&sort=gt.end_date' . $url; 
   		$this->data['sort_order'] 	= 'index.php?route='.$this->modulePath.'/giftList&store_id='.$this->request->get['store_id'] . '&token=' . $this->session->data['token'] . '&sort=gt.sort_order' . $url;
		$this->data['condition_type'] 	= 'index.php?route='.$this->modulePath.'/giftList&store_id='.$this->request->get['store_id'] . '&token=' . $this->session->data['token'] . '&sort=gt.condition_type' . $url;
        $url = '';
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) { 
            $url .= '&order=' . $this->request->get['order'];
        } 
		foreach ($gifts as $key => $gift) {
			$gifts[$key]['image'] = $this->model_tool_image->resize($gift['image'], 100, 100);
		} 
        $pagination               = new Pagination();
        $pagination->total        = $this->moduleModel->getTotalGifts($this->request->get['store_id']); 
        $pagination->page         = $page;
        $pagination->limit        = $this->data['limit']; 
        $pagination->text         = $this->language->get('text_pagination');
        $pagination->url          = 'index.php?route='.$this->modulePath.'/giftList&store_id='.$this->request->get['store_id'].'&token=' . $this->session->data['token'] . $url . '&page={page}';
        $this->data['pagination'] = $pagination->render();
        $this->data['sort']       = $sort;
        $this->data['order']      = $order;
        $this->data['gifts'] 	  = $gifts;
  
  		$languageVariables = array (
            'text_product',
            'text_quantity',
			'text_viewed',
			'text_total',
			'text_price',
			'text_changing',
			'text_start_date',
			'text_end_date',
			'text_added_on',
			'text_condition',
			'button_add',
            'button_edit',            
            'button_remove',
            'text_remains',
            'text_actions',
            'tooltip_quantity',
			'tooltip_start_date',
			'tooltip_end_date',
			'tooltip_sort_order',
			'tooltip_total',
			'text_sort_order',
			'entry_customer_group',
        );
        foreach ($languageVariables as $languageVariable) {
            $this->data[$languageVariable] = $this->language->get($languageVariable);
        }

        $this->response->setOutput($this->load->view($this->modulePath.'/giftList.tpl', $this->data)); 
    }
    
	public function removeGift() {
		if ($this->user->hasPermission('modify', $this->modulePath)) {
			$this->moduleModel->removeGift($this->request->post['gift_id']);
		}
	}
    
    private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_CATALOG;
        } else {
            $storeURL = HTTP_CATALOG;
        } 
        return $storeURL;
    }

    private function getServerURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }

    private function getCurrentStore($store_id) {
	    $this->load->model('setting/store');
		    
        if($store_id && $store_id != 0) {
            $store = $this->model_setting_store->getStore($store_id);
        } else {
            $store['store_id'] = 0;
            $store['name'] = $this->config->get('config_name');
            $store['url'] = $this->getCatalogURL(); 
        }
        return $store;
    }
    
    public function install() {
    	$this->load->model('setting/setting');	

	    $this->moduleModel->install();
    }
    
    public function uninstall() {
    	$this->load->model('setting/setting');	
		$this->load->model('setting/store');
		$this->model_setting_setting->deleteSetting($this->moduleName, 0);
		$stores=$this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$this->model_setting_setting->deleteSetting($this->moduleName, $store['store_id']);
		}
        $this->moduleModel->uninstall();
    }
	
	public function giftForm() {	
		$this->load->model('tool/image');
		$this->load->model('localisation/language');
		$languageVariables = array(
			'entry_product',
			'entry_category', 
			'entry_manufacturer', 
			'valid_from', 
			'valid_to', 
			'get_gift_when',
			'entry_sort_order',
			'condition_total',
			'condition_some',
			'condition_certain',
			'condition_category',
			'condition_manufacturer',
            'total_amount',
            'gift_options',
            'gift_description',
            'button_save',
            'button_cancel',
			'certain_product_help',
			'some_product_help',
			'entry_customer_group',
			'text_total_subtotal',
			'text_total_subtotal_help',
			'text_total',
			'text_subtotal'
		);
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) { 	
            if (!$this->user->hasPermission('modify', $this->modulePath)) {
                $this->response->redirect($this->extensionsLink);
            }
			$this->moduleModel->saveGift($this->request->post);	
		}
		
		foreach ($languageVariables as $key => $v) {
			$this->data[$v] = $this->language->get($v);
		}
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		//2.2.0.0 language flag image fix
		foreach ($this->data['languages'] as $key => $value) {
			if(version_compare(VERSION, '2.2.0.0', "<")) {
				$this->data['languages'][$key]['flag_url'] = 'view/image/flags/'.$this->data['languages'][$key]['image'];

			} else {
				$this->data['languages'][$key]['flag_url'] = 'language/'.$this->data['languages'][$key]['code'].'/'.$this->data['languages'][$key]['code'].'.png"';
			}
		}
		
		if(VERSION >= '2.1.0.1'){
			$this->load->model('customer/customer_group');
			$this->data['customerGroups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');
			$this->data['customerGroups'] = $this->model_sale_customer_group->getCustomerGroups();
		}
		
		if(isset($this->request->get['gift_id'])){
			$this->data['gift_id'] = $this->request->get['gift_id'];
			$this->data['item_id'] = $this->request->get['item_id'];
			$item = $this->moduleModel->getProductsByID(array($this->request->get['item_id']));
			$item = $item[0];
			$this->data['item_name'] = $item['name'];
			$this->data['image'] = $this->model_tool_image->resize($item['image'], 50, 50);
			$this->data['gift'] =$this->moduleModel->getGift($this->request->get['gift_id']);
			
			$properties = isset($this->data['gift']['condition_properties'])?unserialize($this->data['gift']['condition_properties']):array();
		
			$this->data['select_total_subtotal'] = !empty($properties['select_total'])?$properties['select_total']:'';
			$this->data['total'] = !empty($properties['total'])?$properties['total']:'';
			$this->data['total_max'] = !empty($properties['total_max'])?$properties['total_max']:'';
			$this->data['some_product_quantity'] = !empty($properties['some_product_quantity'])?$properties['some_product_quantity']:'1';
			$this->data['certain_product_quantity'] = !empty($properties['certain_product_quantity'])?$properties['certain_product_quantity']:'1';
			$this->data['certain'] = !empty($properties['certain'])?$this->moduleModel->getProductsByID($properties['certain']):array();
			$this->data['some'] = !empty($properties['some'])?$this->moduleModel->getProductsByID($properties['some']):array();
			$this->data['categories'] = !empty($properties['categories'])?$this->moduleModel->getCategoriesByID($properties['categories']):array();
			$this->data['customer_group'] = !empty($properties['customer_group'])?$properties['customer_group']:array();		
			$this->data['manufacturers'] = !empty($properties['manufacturer'])?$this->moduleModel->getManufacturersByID($properties['manufacturer']):array();
		} else {
			$this->data['gift_id'] = '-1';
			$this->data['gift'] = array(); 
		}
		
		$this->response->setOutput($this->load->view($this->modulePath.'/giftForm.tpl', $this->data)); 

	}
	
	public function saveGift() {
		$this->moduleModel->saveGift($this->request->post);	
	}
    
	public function saveCondition(){
		$this->moduleModel->saveCondition($this->request->post);
	}	
}

?>