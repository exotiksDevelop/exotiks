<?php 
class ControllerModuleGiftTeaser extends Controller  {
	private $moduleName;
	private $moduleTotal;
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
        $this->moduleTotal      	= $this->config->get('giftteaser_total');
		$this->moduleName           = $this->config->get('giftteaser_name');
        $this->callModel            = $this->config->get('giftteaser_model');
        $this->modulePath           = $this->config->get('giftteaser_path');
		$this->totalPath         	= $this->config->get('giftteaser_total_path');
	    $this->moduleVersion        = $this->config->get('giftteaser_version');   
		$this->moduleData_module    = $this->config->get('giftteaser_module_data');        

        // Load Language
        $this->load->language($this->totalPath);
        
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
    }	
	
	public function index() { 
		//$this->load->model($this->modulePath);
		$this->load->model('setting/setting');

		$setting = $this->model_setting_setting->getSetting($this->moduleName, $this->config->get('config_store_id'));
		if($setting[$this->moduleName]['Enabled'] && $setting[$this->moduleName]['Enabled'] == 'yes' ) {
			$this->load->model('tool/image');
			
			if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/giftTeaser.css')) {
				$this->document->addStyle('catalog/view/theme/'.$this->config->get('config_template').'/stylesheet/giftTeaser.css');
			} else {
				$this->document->addStyle('catalog/view/theme/default/stylesheet/giftTeaser.css');
			}
		
			$data['cart_total'] = $this->cart->getTotal();	
			$data['data'] = $setting[$this->moduleName];
			$gifts = $this->moduleModel->getCurrentGifts();	
			foreach ($gifts as $key => $gift) {
				$gifts[$key]['description'] = $this->buildDescription($gift);	
 				$gifts[$key]['url'] = $this->url->link("product/product&product_id=".$gift['product_id']);				
				$gifts[$key]['image'] = $this->model_tool_image->resize($gift['image'], $setting[$this->moduleName]['giftImageWidth'], $setting[$this->moduleName]['giftImageHeight']);
			}
			$data['gifts']= $gifts;
			$data['language']= $this->config->get('config_language');
			$data['current_template'] = $this->config->get('config_template');
			
			if(version_compare(VERSION, '2.2.0.0', "<")) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template'). '/template/'.$this->modulePath.'/giftTeaser.tpl')) {
					return $this->load->view($this->config->get('config_template'). '/template/'.$this->modulePath.'/giftTeaser.tpl', $data);
				 } else {
					return $this->load->view('default/template/'.$this->modulePath.'/giftTeaser.tpl', $data);
				 }  
			} else {
				   return $this->load->view($this->modulePath.'/giftTeaser.tpl', $data);
			}
		}
			
	}
	
	public function refresh_gifts() {
		$this->cart->restartGifts();
	}

	public function buildDescription($gift = array()) {
		$properties = unserialize($gift['condition_properties']);
		$descriptions = unserialize(base64_decode($gift['description']));
		$description = html_entity_decode($descriptions['desc_' . $this->config->get('config_language')]);
		return $description;
	}
	
	 public function checkGifts() { 
          $this->load->model('setting/setting');
          $giftTeaser = $this->model_setting_setting->getSetting($this->moduleName, $this->config->get('config_store_id'));

          if (empty($giftTeaser[$this->moduleName]['Enabled']) || $giftTeaser[$this->moduleName]['Enabled'] == 'no') { } else {

              $prods = $this->cart->getProducts();
              $addition = '';
              $gifts = $this->cart->getAllGifts();
              $cart_products = array_map(create_function('$a', 'return $a["product_id"];'), $this->cart->getProducts());

            if (empty($cart_products)) return;
            $eligible_gifts = array();

            foreach ($gifts as $gift) {
              $properties = unserialize($gift['condition_properties']);
              $to_add = null;
              $to_add_quantity = (int)$gift['minimum'];

              if(isset($this->session->data['customer_id'])) {
               	$query = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "'") ->row;
               	$customer_group_id = $query['customer_group_id'];
              } else {
               	$customer_group_id = '0';
              }

              if(isset($properties['customer_group']) && !empty($properties['customer_group']) && in_array($customer_group_id, $properties['customer_group'])) {

					if(isset($properties['select_total']) && $properties['select_total'] == 'subtotal') {
						$cart_total = $this->cart->getSubTotal();
					} else {
						$cart_total = $this->cart->getTotal();
					}

                switch ($gift['condition_type']) {
                  case '1' : {
                     if ((int)$properties['total'] < $cart_total && (int)$properties['total_max'] > $cart_total) {
                      $to_add = $gift['product_id'];
                    }

                  } break;

                  case '2' : {
                    if (
                      isset($properties['certain']) &&
                      array_values(array_intersect($properties['certain'], $cart_products)) === array_values($properties['certain'])
                    ) {
                      $to_add = $gift['product_id'];
                    }

                  } break;

                  case '3' : {
                    if (
                      isset($properties['some']) &&
                      count(array_intersect($properties['some'], $cart_products))
                    ) {
                      $to_add = $gift['product_id'];
                    }

                  } break; 

                  case '4' : {
                    if (
                      isset($properties['categories']) &&
                      $this->cart->categoriesHaveProducts($properties['categories'], $cart_products)
                    ) {
                      $to_add = $gift['product_id'];
                    }

                  } break;

					case '5' : {
					
                      if (
                        isset($properties['manufacturer']) &&
                        $this->cart->manufacturersHaveProducts($properties['manufacturer'], $cart_products)
                      ) {
                        $to_add = $gift['product_id'];
                      }

                    } break;
                }
			  }

              if (
                !empty($this->session->data['gift_teaser_exclude']) && 
                in_array($to_add, $this->session->data['gift_teaser_exclude'])
              ) {
                continue;
              }

              if (!empty($to_add)) {
                for ($i = 0; $i < $to_add_quantity; $i++) {
                  $eligible_gifts[] = $to_add;
                }
              }
            }
            $option_key = 'gift_teaser';
            $json['gifts_with_options'] = false;

				foreach ($this->cart->getProducts() as $key => $val) {
                    if(empty($eligible_gifts)) {
                        if (!empty($val['gift_teaser'])) {
                          if(!empty($val['option'])) {
							  if(VERSION < '2.1.0.1') {
								 if (isset($this->session->data['cart'][$key])) {
								   unset($this->session->data['cart'][$key]);
								   $data = array();
								 }
							  } else {
								$this->db->query("DELETE FROM ".DB_PREFIX."cart WHERE gift_teaser = true");
							  }
							  
                          }
                        }
                    } else {
                        if (!empty($val['gift_teaser'])) {
                          if(!empty($val['option']) && !in_array($val['product_id'], $eligible_gifts)) {
							  if(VERSION < '2.1.0.1') {
								 if (isset($this->session->data['cart'][$key])) {
								   unset($this->session->data['cart'][$key]);
								   $data = array();
								 }
							  } else {
								$this->db->query("DELETE FROM ".DB_PREFIX."cart WHERE gift_teaser = true AND product_id=".$val['product_id']);
							  }
                          } else {
                              continue;
                          }
                        } 
                    }
                }

            foreach ($eligible_gifts as $eligible_gift) {
                  $product_options = $this->db->query("SELECT po.product_id, po.option_value_id as option_index, ovp.name FROM `". DB_PREFIX . "product_option_value` po LEFT JOIN `" . DB_PREFIX . "option_value_description` ovp ON (po.option_value_id = ovp.option_value_id) WHERE po.product_id=".(int)$eligible_gift)->rows;
                  if(!empty($product_options)) {
                        $containts_gift = array();
                        foreach($this->cart->getProducts() as $k => $v){
                            if ($v['gift_teaser'] !== false) {
                                $contains_gift[]='true';
                            } else {
                                $contains_gift[]='false';
                            }
                        }
					 

                      if (!in_array('true', $contains_gift)) {
                          $json['gifts_with_options'] = $eligible_gift;
                      } else {
                          $json['gifts_with_options'] = false;
                      }
                  } else {
                      $json['gifts_with_options'] = false;

                  }
            }

              $this->response->setOutput(json_encode($json));   
          }
      }

      public function GiftAdd() { 
          $this->language->load('checkout/cart');
          $json = array();
          if (isset($this->request->post['product_id'])) {
              $product_id = $this->request->post['product_id'];
          } else {
              $product_id = 0;
          }

          $this->load->model('catalog/product');
          $product_info = $this->model_catalog_product->getProduct($product_id);

          if ($product_info) {            
              if (isset($this->request->post['quantity'])) {
                  $quantity = $this->request->post['quantity'];
              } else {
                  $quantity = 1;
              }

              if (isset($this->request->post['option'])) {
                  $option = array_filter($this->request->post['option']);
              } else {
                  $option = array();  
              }

              if (isset($this->request->post['recurring_id'])) {
                  $recurring_id = $this->request->post['recurring_id'];
              } else {
                  $recurring_id = 0;
              }

              $product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

              foreach ($product_options as $product_option) {
                  if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
                      $json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
                  }
              }

             $recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

			if ($recurrings) {
				$recurring_ids = array();

				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}

				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring_required');
				}
			}

              if (!$json) { 
                  $this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id, $gift = true);
                  $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

          //$this->load->model($this->modulePath);
          $this->load->model('setting/setting');

		  $giftTeaser = $this->model_setting_setting->getSetting($this->moduleName, $this->config->get('config_store_id'));

          if (empty($giftTeaser[$this->moduleName]['Enabled']) || $giftTeaser[$this->moduleName]['Enabled'] == 'no') { } else {
              $prods = $this->cart->getProducts(); 
              $addition = '';

              foreach ($prods as $prod) {
                  if ($prod['gift_teaser']==true && !isset($this->session->data['gift_teaser_success'])) {
                      $addition = $this->language->get('gift_added_to_cart');
                      $this->session->data['gift_teaser_success'] = true;
                  }
              }
              if(isset($json['success'])) {
                  $json['success'] .= $addition;
              }   
           }
                  unset($this->session->data['shipping_method']);
                  unset($this->session->data['shipping_methods']);
                  unset($this->session->data['payment_method']);
                  unset($this->session->data['payment_methods']);

              } else {
                  $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
              }
          }

          $this->response->setOutput(json_encode($json));       
      }
	  
	
	public function emptyExcludedGifts(){
		unset($this->session->data['excludedGifts']);
		$this->moduleModel->updateGiftsInCart();			
	}
	
	private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTP_SERVER;
        } else {
            $storeURL = HTTPS_SERVER;
        } 
        return $storeURL;
    }
}
?>