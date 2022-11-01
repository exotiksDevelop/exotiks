<?php
class ModelModuleGiftTeaser extends Model {
  
	public function getCurrentGifts() {
		return $this->db->query("SELECT *, gt.description AS description 
								 FROM `". DB_PREFIX . "gift_teaser` gt
								 JOIN " . DB_PREFIX . "product p ON gt.item_id=p.product_id
								 JOIN " . DB_PREFIX . "product_description pd ON p.product_id=pd.product_id
								 WHERE start_date<" . time(). " 
								 AND end_date>" . time() ." 
								 AND store_id=" . $this->config->get('config_store_id') . " 
								 AND language_id=" . $this->config->get('config_language_id') . 
							 	 " ORDER BY gt.sort_order ASC")->rows;	
	}

	public function updateGiftsInCart($key = null) {
		$setting = $this->getSetting('giftTeaser', $this->config->get('config_store_id'));
		if(isset($setting['giftTeaser']['Enabled']) && $setting['giftTeaser']['Enabled'] == 'yes') {
			$eligibleGifts = $this->getEligibleGifts(50, 50);
			$key = explode(':', $key); 
			if(isset($key[1])) {
				$option = (unserialize(base64_decode($key[1])));
				if(isset($option['giftTeaser']) && $option['giftTeaser'] == 'giftTeaser') {
					$this->session->data['excludedGifts'][] = $key[0];  
				}
			}	
			$giftCountBeforeUpdate = count($this->getGiftsFromCart());
			$this->removeGiftsFromCart();
			if(!empty($this->session->data['excludedGifts'])){
				foreach ($eligibleGifts as $key => $gift) { 
					if(in_array($gift['item_id'], $this->session->data['excludedGifts'])){
						unset($eligibleGifts[$key]);
					}
				} 
			}
			$this->addGiftsToCart($eligibleGifts);	
	
			if($giftCountBeforeUpdate < count($this->getGiftsFromCart())){
				$this->session->data['gift_added_to_cart'] = TRUE;
			}	
		}
	}
	
	public function addGiftsToCart($gifts) {
		foreach ($gifts as $key => $gift) {
			$this->cart->add($gift['item_id'], 1, array('giftTeaser' => 'giftTeaser'));		
			$this->session->data['cartGifts'][] = $gift['item_id']; 	
		}
	}
	
	public function removeGiftsFromCart(){
		$giftsInCart = $this->getGiftsFromCart(); 			
		if(!empty($giftsInCart)) {
			foreach ($giftsInCart as $key => $gift) {
				$this->cart->remove($gift);
			}
		}
	}
	
	public function getGiftsFromCart() { 
		$gifts = array();
		foreach ($this->session->data['cart'] as $key => $k) {
			$explode = explode(':', $key); 
			$option = unserialize(base64_decode($explode[1]));
			if(!empty($option) && in_array('giftTeaser', $option)) {
				$gifts[] = $key;
			}
		}
		return $gifts;
	}
	
	public function getEligibleGifts($width = 50, $height = 50){
		$eligibleGifts = array(); 
		$cart = array_map(create_function('$a', 'return $a[\'product_id\'];'), $this->cart->getProducts());
		foreach ($this->getCurrentGifts() as $gift) {
			$properties =  unserialize($gift['condition_properties']);
			switch ($gift['condition_type']) {
				case '1': $properties['total'] < $this->cart->getTotal() - $this->session->data['giftTeaserTotal'] ? $eligibleGifts[] = $gift : false; break;
				case '2': isset($properties['certain']) && array_intersect($properties['certain'], $cart) === $properties['certain'] ? $eligibleGifts[] = $gift : false; break;
				case '3': isset($properties['some']) && count(array_intersect($properties['some'], $cart)) > 0 ? $eligibleGifts[] = $gift : false; break; 
				case '4': isset($properties['categories']) && count(array_intersect($this->getProductsFromCategories($properties['categories']), $cart)) > 0 ? $eligibleGifts[] = $gift : false; break;; 
				default: $description = ''; break;
			}
		}
		$this->resizeGiftImages($eligibleGifts, $width, $height);
		$this->session->data['eligibleGifts'] = $eligibleGifts;
		return $eligibleGifts;
	}
	
	public function resizeGiftImages(&$gifts = array(), $width = 50, $height = 50) {
		$this->load->model('tool/image');
		foreach ($gifts as $key => $gift) {
			$gifts[$key]['image'] = $this->model_tool_image->resize($gift['image'], $width, $height);  
		}
	}
	
	private function getProductsFromCategories($categories = array()) {
		if(!empty($categories)) {
		 	$products = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id IN (" . implode(',', $categories) . ")")->rows;
			return array_map(create_function('$a', 'return $a[\'product_id\'];'), $products);
		}
	}
	
	public function checkGifts() {

          $this->load->model('setting/setting');
          $giftTeaser = $this->model_setting_setting->getSetting('giftTeaser', $this->config->get('config_store_id'));
		  $json = array();	
			
          if (empty($giftTeaser['giftTeaser']['Enabled']) || $giftTeaser['giftTeaser']['Enabled'] == 'no') { 
		  		return $json['gifts_with_options'] = false;
		  } else {

              $prods = $this->cart->getProducts();
              $addition = '';
              $gifts = $this->cart->getAllGifts();
              $cart_products = array_map(create_function('$a', 'return $a["product_id"];'), $this->cart->getProducts());

            if (empty($cart_products)) return;
            $eligible_gifts = array();

            foreach ($gifts as $gift) {
              $properties = unserialize($gift['condition_properties']);
              $to_add = null;

              	if((int)$gift['minimum'] > 0) {
					$to_add_quantity = (int)$gift['minimum'];
				} else {
					$to_add_quantity = 1;
				}

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
                      if(isset($properties['certain'])) {
                          $eligible_products = array_intersect($properties['certain'], $cart_products);
                      }
                      $add_gift= array();
                      if(isset($eligible_products)) {
                          foreach($eligible_products as $eligible_product) {
                              unset($quantity);
                              $quantity=array();
                              foreach($prods as $key => $value) {
                                   $exp_key = $value['product_id'];
                                   if($exp_key == $eligible_product){
                                       $quantity[] = $value['quantity'];
                                  }
                              }
                              if(count($quantity) > 1 ) {
                                  $quantity = array_sum($quantity);
                              } else {
                                  $quantity = $quantity[0];
                              }

                              if($quantity >= $properties['certain_product_quantity'] ) {
                                  $add_gift[] = 'true';
                              } else {
                                  $add_gift[] = 'false';
                              }
                          } 
                      }
                      if (in_array("false", $add_gift)) {
                          $quantity_reached = false;
                      } else {
                          $quantity_reached = true;
                      }

                      if (
                        isset($properties['certain']) &&
                        array_values(array_intersect($properties['certain'], $cart_products)) === array_values($properties['certain']) &&
                        $quantity_reached
                      ) {
                        $to_add = $gift['product_id'];
                      }

                    } break;

                    case '3' : {
                      if(isset($properties['some'])) {
                          $eligible_products = array_intersect($properties['some'], $cart_products);
                      }
                      $add_gift= array();
                      foreach($eligible_products as $eligible_product) {
                          unset($quantity);
                          $quantity=array();
                          foreach($prods as $key => $value) {
                                   $exp_key = $value['product_id'];
                                   if($exp_key == $eligible_product){
                                       $quantity[] = $value['quantity'];
                                  }
                              }
                              if(count($quantity) > 1 ) {
                                  $quantity = array_sum($quantity);
                              } else {

                                  $quantity = $quantity[0];
                              }

                              if($quantity >= $properties['some_product_quantity'] ) {
                              $add_gift[] = 'true';
                          } else {
                              $add_gift[] = 'false';
                          }
                      } 
                      if (in_array("true", $add_gift)) {
                          $quantity_reached = true;
                      } else {
                          $quantity_reached = false;
                      }
                      if (
                        isset($properties['some']) &&
                        count(array_intersect($properties['some'], $cart_products)) &&
                        $quantity_reached 
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
		 
		 }
		 
		 return($json);
	}
}
?>