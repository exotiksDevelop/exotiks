<?php
class ModelCatalogQuickproduct extends Model {
	public function addProduct($data) {
		$this->event->trigger('pre.admin.product.add', $data);

		if($this->config->get('quick_product_edit_tabs')) {
			$quick_product_edit_tabs = $this->config->get('quick_product_edit_tabs');
		}else{
			$quick_product_edit_tabs = array();
		}

		$sql = "INSERT INTO " . DB_PREFIX . "product SET date_added = NOW()";
		
		if (isset($data['model'])) {
			$sql .= ", model = '" .  $this->db->escape($data['model']) . "'";	
		}
		
		if (isset($data['sku'])) {
			$sql .= ", sku = '" . $this->db->escape($data['model']) . "'";	
		}
		
		if (isset($data['upc'])) {
			$sql .= ", upc = '" . $this->db->escape($data['upc']) . "'";	
		}
		
		if (isset($data['ean'])) {
			$sql .= ", ean = '" . $this->db->escape($data['ean']) . "'";	
		}
		
		if (isset($data['jan'])) {
			$sql .= ", jan = '" . $this->db->escape($data['jan']) . "'";	
		}
		
		if (isset($data['isbn'])) {
			$sql .= ", isbn = '" . $this->db->escape($data['isbn']) . "'";	
		}
		
		if (isset($data['mpn'])) {
			$sql .= ", mpn = '" . $this->db->escape($data['mpn']) . "'";	
		}
		
		if (isset($data['location'])) {
			$sql .= ", location = '" . $this->db->escape($data['location']) . "'";	
		}
		
		if (isset($data['quantity'])) {
			$sql .= ", quantity = '" . (int)$data['quantity'] . "'";	
		}
		
		if (isset($data['minimum'])) {
			$sql .= ", minimum = '" . (int)$data['minimum'] . "'";	
		}
		
		if (isset($data['subtract'])) {
			$sql .= ", subtract = '" . (int)$data['subtract'] . "'";	
		}
		
		if (isset($data['stock_status_id'])) {
			$sql .= ", stock_status_id = '" . (int)$data['stock_status_id'] . "'";	
		}
		
		if (isset($data['date_available'])) {
			$sql .= ", date_available = '" . $this->db->escape($data['date_available']) . "'";	
		}
		
		if (isset($data['manufacturer_id'])) {
			$sql .= ", manufacturer_id = '" . $this->db->escape($data['manufacturer_id']) . "'";	
		}
		
		if (isset($data['shipping'])) {
			$sql .= ", shipping = '" . (int)$data['shipping'] . "'";	
		}
		
		if (isset($data['price'])) {
			$sql .= ", price = '" . (float)$data['price'] . "'";	
		}
		
		if (isset($data['points'])) {
			$sql .= ", points = '" . (int)$data['points'] . "'";	
		}
		
		if (isset($data['weight'])) {
			$sql .= ", weight = '" . (float)$data['weight'] . "'";	
		}
		
		if (isset($data['weight_class_id'])) {
			$sql .= ", weight_class_id = '" . (int)$data['weight_class_id'] . "'";	
		}
		
		if (isset($data['length'])) {
			$sql .= ", length = '" . (float)$data['length'] . "'";	
		}
		
		if (isset($data['width'])) {
			$sql .= ", width = '" . (float)$data['width'] . "'";	
		}
		
		if (isset($data['height'])) {
			$sql .= ", height = '" . (float)$data['height'] . "'";	
		}
		
		if (isset($data['length_class_id'])) {
			$sql .= ", length_class_id = '" . (int)$data['length_class_id'] . "'";	
		}
		
		if (isset($data['status'])) {
			$sql .= ", status = '" . (int)$data['status'] . "'";	
		}
		
		if (isset($data['tax_class_id'])) {
			$sql .= ", tax_class_id = '" . (int)$data['tax_class_id'] . "'";	
		}
		
		if (isset($data['sort_order'])) {
			$sql .= ", sort_order = '" . (int)$data['sort_order'] . "'";	
		}
		
		if (isset($data['image'])) {
			$sql .= ", image = '" . $this->db->escape($data['image']) . "'";	
		}
		
		$this->db->query($sql);
		
		$product_id = $this->db->getLastId();
		
		if(!empty($quick_product_edit_tabs['general']['status'])) {
			if(isset($data['product_description'])) {
				foreach ($data['product_description'] as $language_id => $value) {
					$sql = "INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '". (int)$language_id ."'";
					
					if (isset($value['name'])) {
						$sql .= ", name = '" . $this->db->escape($value['name']) . "'";	
					}
					
					if (isset($value['description'])) {
						$sql .= ", description = '" . $this->db->escape($value['description']) . "'";	
					}
					
					if (isset($value['tag'])) {
						$sql .= ", tag = '" . $this->db->escape($value['tag']) . "'";	
					}
					
					if (isset($value['meta_title'])) {
						$sql .= ", meta_title = '" . $this->db->escape($value['meta_title']) . "'";	
					}
					
					if (isset($value['meta_description'])) {
						$sql .= ", meta_description = '" . $this->db->escape($value['meta_description']) . "'";	
					}
					
					if (isset($value['meta_keyword'])) {
						$sql .= ", meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'";	
					}
					
					$this->db->query($sql);
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['store'])) {
			if (isset($data['product_store'])) {
				foreach ($data['product_store'] as $store_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
		}
		
		if(!empty($quick_product_edit_tabs['attribute']['status'])) {
			if (!empty($data['product_attribute'])) {
				foreach ($data['product_attribute'] as $product_attribute) {
					if ($product_attribute['attribute_id']) {
						foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
						}
					}
				}
			}
		}

		if(!empty($quick_product_edit_tabs['option']['status'])) {
			if (isset($data['product_option'])) {
				foreach ($data['product_option'] as $product_option) {
					if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
						if (isset($product_option['product_option_value'])) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

							$product_option_id = $this->db->getLastId();

							foreach ($product_option['product_option_value'] as $product_option_value) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
							}
						}
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
					}
				}
			}
		}

		if(!empty($quick_product_edit_tabs['discount']['status'])) {
			if (isset($data['product_discount'])) {
				foreach ($data['product_discount'] as $product_discount) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['special']['status'])) {
			if (isset($data['product_special'])) {
				foreach ($data['product_special'] as $product_special) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['image']['status'])) {
			if (isset($data['product_image'])) {
				foreach ($data['product_image'] as $product_image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['download'])) {
			if (isset($data['product_download'])) {
				foreach ($data['product_download'] as $download_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['category'])) {
			if (isset($data['product_category'])) {
				foreach ($data['product_category'] as $category_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['filter'])) {
			if (isset($data['product_filter'])) {
				foreach ($data['product_filter'] as $filter_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['product_related'])) {
			if (isset($data['product_related'])) {
				foreach ($data['product_related'] as $related_id) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['reward']['status'])) {
			if (isset($data['product_reward'])) {
				foreach ($data['product_reward'] as $customer_group_id => $value) {
					if ((int)$value['points'] > 0) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
					}
				}
			}
		}


		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.add', $product_id);

		return $product_id;
	}

	public function editProduct($product_id, $data) {
		$this->event->trigger('pre.admin.product.edit', $data);
		
		if($this->config->get('quick_product_edit_tabs')) {
			$quick_product_edit_tabs = $this->config->get('quick_product_edit_tabs');
		}else{
			$quick_product_edit_tabs = array();
		}

		$sql = "UPDATE " . DB_PREFIX . "product SET date_modified = NOW()";
		
		if (isset($data['model'])) {
			$sql .= ", model = '" .  $this->db->escape($data['model']) . "'";	
		}
		
		if (isset($data['sku'])) {
			$sql .= ", sku = '" . $this->db->escape($data['model']) . "'";	
		}
		
		if (isset($data['upc'])) {
			$sql .= ", upc = '" . $this->db->escape($data['upc']) . "'";	
		}
		
		if (isset($data['ean'])) {
			$sql .= ", ean = '" . $this->db->escape($data['ean']) . "'";	
		}
		
		if (isset($data['jan'])) {
			$sql .= ", jan = '" . $this->db->escape($data['jan']) . "'";	
		}
		
		if (isset($data['isbn'])) {
			$sql .= ", isbn = '" . $this->db->escape($data['isbn']) . "'";	
		}
		
		if (isset($data['mpn'])) {
			$sql .= ", mpn = '" . $this->db->escape($data['mpn']) . "'";	
		}
		
		if (isset($data['location'])) {
			$sql .= ", location = '" . $this->db->escape($data['location']) . "'";	
		}
		
		if (isset($data['quantity'])) {
			$sql .= ", quantity = '" . (int)$data['quantity'] . "'";	
		}
		
		if (isset($data['minimum'])) {
			$sql .= ", minimum = '" . (int)$data['minimum'] . "'";	
		}
		
		if (isset($data['subtract'])) {
			$sql .= ", subtract = '" . (int)$data['subtract'] . "'";	
		}
		
		if (isset($data['stock_status_id'])) {
			$sql .= ", stock_status_id = '" . (int)$data['stock_status_id'] . "'";	
		}
		
		if (isset($data['date_available'])) {
			$sql .= ", date_available = '" . $this->db->escape($data['date_available']) . "'";	
		}
		
		if (isset($data['manufacturer_id'])) {
			$sql .= ", manufacturer_id = '" . $this->db->escape($data['manufacturer_id']) . "'";	
		}
		
		if (isset($data['shipping'])) {
			$sql .= ", shipping = '" . (int)$data['shipping'] . "'";	
		}
		
		if (isset($data['price'])) {
			$sql .= ", price = '" . (float)$data['price'] . "'";	
		}
		
		if (isset($data['points'])) {
			$sql .= ", points = '" . (int)$data['points'] . "'";	
		}
		
		if (isset($data['weight'])) {
			$sql .= ", weight = '" . (float)$data['weight'] . "'";	
		}
		
		if (isset($data['weight_class_id'])) {
			$sql .= ", weight_class_id = '" . (int)$data['weight_class_id'] . "'";	
		}
		
		if (isset($data['length'])) {
			$sql .= ", length = '" . (float)$data['length'] . "'";	
		}
		
		if (isset($data['width'])) {
			$sql .= ", width = '" . (float)$data['width'] . "'";	
		}
		
		if (isset($data['height'])) {
			$sql .= ", height = '" . (float)$data['height'] . "'";	
		}
		
		if (isset($data['length_class_id'])) {
			$sql .= ", length_class_id = '" . (int)$data['length_class_id'] . "'";	
		}
		
		if (isset($data['status'])) {
			$sql .= ", status = '" . (int)$data['status'] . "'";	
		}
		
		if (isset($data['tax_class_id'])) {
			$sql .= ", tax_class_id = '" . (int)$data['tax_class_id'] . "'";	
		}
		
		if (isset($data['sort_order'])) {
			$sql .= ", sort_order = '" . (int)$data['sort_order'] . "'";	
		}
		
		if (isset($data['image'])) {
			$sql .= ", image = '" . $this->db->escape($data['image']) . "'";	
		}
		
		$sql .= " WHERE product_id = '" . (int)$product_id . "'";
		
		$this->db->query($sql);
		
		
		if(!empty($quick_product_edit_tabs['general']['status'])) {
			if(isset($data['product_description'])) {
				foreach ($data['product_description'] as $language_id => $value) {
					$product_description_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE language_id = '". (int)$language_id ."' AND product_id = '" . (int)$product_id . "'");
					
					if($product_description_info) {
						$sql = "UPDATE " . DB_PREFIX . "product_description SET language_id = '". (int)$language_id ."'";
						
						if (isset($value['name'])) {
							$sql .= ", name = '" . $this->db->escape($value['name']) . "'";	
						}
						
						if (isset($value['description'])) {
							$sql .= ", description = '" . $this->db->escape($value['description']) . "'";	
						}
						
						if (isset($value['tag'])) {
							$sql .= ", tag = '" . $this->db->escape($value['tag']) . "'";	
						}
						
						if (isset($value['meta_title'])) {
							$sql .= ", meta_title = '" . $this->db->escape($value['meta_title']) . "'";	
						}
						
						if (isset($value['meta_description'])) {
							$sql .= ", meta_description = '" . $this->db->escape($value['meta_description']) . "'";	
						}
						
						if (isset($value['meta_keyword'])) {
							$sql .= ", meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'";	
						}
						
						$sql .= " WHERE language_id = '". (int)$language_id ."' AND product_id = '" . (int)$product_id . "'";
						
						$this->db->query($sql);
					}else{
						$sql = "INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '". (int)$language_id ."'";
						
						if (isset($value['name'])) {
							$sql .= ", name = '" . $this->db->escape($data['name']) . "'";	
						}
						
						if (isset($value['description'])) {
							$sql .= ", description = '" . $this->db->escape($data['description']) . "'";	
						}
						
						if (isset($value['tag'])) {
							$sql .= ", tag = '" . $this->db->escape($data['tag']) . "'";	
						}
						
						if (isset($value['meta_title'])) {
							$sql .= ", meta_title = '" . $this->db->escape($data['meta_title']) . "'";	
						}
						
						if (isset($value['meta_description'])) {
							$sql .= ", meta_description = '" . $this->db->escape($data['meta_description']) . "'";	
						}
						
						if (isset($value['meta_keyword'])) {
							$sql .= ", meta_keyword = '" . $this->db->escape($data['meta_keyword']) . "'";	
						}
						
						$this->db->query($sql);
					}
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['store'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

			if (isset($data['product_store'])) {
				foreach ($data['product_store'] as $store_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
		}
		
		if(!empty($quick_product_edit_tabs['attribute']['status'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

			if (!empty($data['product_attribute'])) {
				foreach ($data['product_attribute'] as $product_attribute) {
					if ($product_attribute['attribute_id']) {
						foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
						}
					}
				}
			}
		}

		if(!empty($quick_product_edit_tabs['option']['status'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
					if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
						if (isset($product_option['product_option_value'])) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

							$product_option_id = $this->db->getLastId();

							foreach ($product_option['product_option_value'] as $product_option_value) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
							}
						}
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
					}
				}
			}
		}

		if(!empty($quick_product_edit_tabs['discount']['status'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

			if (isset($data['product_discount'])) {
				foreach ($data['product_discount'] as $product_discount) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['special']['status'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

			if (isset($data['product_special'])) {
				foreach ($data['product_special'] as $product_special) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['image']['status'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

			if (isset($data['product_image'])) {
				foreach ($data['product_image'] as $product_image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['download'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

			if (isset($data['product_download'])) {
				foreach ($data['product_download'] as $download_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['category'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

			if (isset($data['product_category'])) {
				foreach ($data['product_category'] as $category_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['filter'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

			if (isset($data['product_filter'])) {
				foreach ($data['product_filter'] as $filter_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['links']['status']) && !empty($quick_product_edit_tabs['links']['product_related'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

			if (isset($data['product_related'])) {
				foreach ($data['product_related'] as $related_id) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
				}
			}
		}

		if(!empty($quick_product_edit_tabs['reward']['status'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

			if (isset($data['product_reward'])) {
				foreach ($data['product_reward'] as $customer_group_id => $value) {
					if ((int)$value['points'] > 0) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
					}
				}
			}
		}


		if (isset($data['keyword'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.edit', $product_id);
	}
}
