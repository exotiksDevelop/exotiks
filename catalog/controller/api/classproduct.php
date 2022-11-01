<?php
require_once('classbase.php');
class ControllerApiClassproduct extends ControllerApiClassbase {

	public function index() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	
		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/product');

			$json['success'] = 'ok';

			$params = [
				'limit' => 100,
			];
			$params['start'] = ((isset($this->request->get['page'])) ? $this->request->get['page']:0) * $params['limit'];
			$params['sort'] = 'p.sort_order';
			$json['items'] = [];
			foreach ($this->model_admin_catalog_product->getProducts($params) as $value) {
				$value['categories'] = $this->getProductCats($value['product_id']);
				$value['options'] = $this->getProductOption($value['product_id']);
				$value['gal'] = $this->getProductImages($value['product_id']);
				$json['items'][] = $value;
			}

		}
		$this->JSON = $json;
	}
	
	public function productGallery() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/product');
			if (isset($this->request->get['product_id'])) {
				$p_id = $this->request->get['product_id'];

				$json['success'] = 'ok';
				$json['items'] = (array)$this->getProductImages($p_id);

			} else {
				$json = ['error' => 'fail'];
			}
		}
		$this->JSON = $json;
	}

	public function quick_update() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/product');

			$product_id = $this->request->get['product_id'];
			if (!$product_id) {
				$json = ['error' => 'invalid query'];
			} else if ( !$this->model_admin_catalog_product->getProduct($product_id) ) { 
				$json = ['error' => 'product not found'];
			} else {
				$data = $this->request->post;

				$product_fields = [];
				foreach($data as $key => $value) {
					if ( in_array(gettype($value), ['string', 'boolean', 'integer', 'double']) ) {
						$product_fields[$key] = $value;
					}
				}
				if (!empty($product_fields)) {
					$this->db->query($this->sql_update(DB_PREFIX.'product', $product_fields, "product_id={$product_id}"));
				}

				$options = isset($data['product_option'])? $data['product_option'] : [];
				foreach($options as $option) {
					if (isset($option['product_option_value'])) {
						foreach($option['product_option_value'] as $product_option_value) {
							if (!empty($product_option_value['product_option_value_id'])) {
								$this->db->query($this->sql_update(DB_PREFIX.'product_option_value', [
									'price'			=> $product_option_value['price'],
									'price_prefix'	=> $product_option_value['price_prefix'],
									'quantity'		=> $product_option_value['quantity'],
								], "product_id={$product_id} AND product_option_value_id=".(int)$product_option_value['product_option_value_id']));
							}
						}
					}
				}

				$json['success'] = 'ok';
				$json['data'] = [];
			}
		}
		$this->JSON = $json;
	}

	public function get() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/product');
			if (isset($this->request->get['product_id'])) {
				$p_id = $this->request->get['product_id'];

				$json['success'] = 'ok';
				$json['items'] = $this->model_admin_catalog_product->getProduct($p_id);
				if (!empty($json['items'])) {
					$json['items']['categories'] = $this->getProductCats($p_id);
					$json['items']['options'] = $this->getProductOption($p_id);
					$json['items']['gal'] = $this->getProductImages($p_id);
				} else {
					$json = ['error' => 'not found'];
				}

			} else {
				$json = ['error' => 'fail'];
			}
		}
		$this->JSON = $json;
	}

	public function upd() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// $this->loadAdminModel('catalog/product');
			if (isset($this->request->get['product_id'])) {

				$p_id = $this->request->get['product_id'];

				$data = $this->request->post;
				$data['date_modified'] = date('c');

				$sql = '';
				foreach ($data as $key => $value) {
					if ($value != '' || !empty($value)) {
						$sql .= $key."='".$value."', ";
					}
				}
				$sql = trim(trim($sql, ', '));
				// var_dump($sql);

				$this->db->query('UPDATE '.DB_PREFIX.'product
					SET '.$sql.'
					WHERE product_id="'.(int)$p_id.'"');

				$json['success'] = 'ok';
				$json['items']['id'] = [];

			} else {
				$json = ['error' => 'fail'];
			}
		}
		$this->JSON = $json;
	}	

	public function add() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {

			$this->loadAdminModel('catalog/product');
			if (!empty($this->request->post)) {
				$json = ['success' => 'ok'];

				$data = $this->request->post;
				// На всякий удаляем image
				unset($data['image']);
				// Model
				$data['model'] = ((string)$data['model'] == '') ? uniqid() : $data['model'];  

				$data['product_store'][] = (int)$this->config->get('config_store_id');

				$tmpDescript = [];
				if(isset($data['name'])) {
					$tmpDescript['name'] = $data['name'];
					$tmpDescript['meta_title'] = $data['name'];
				}
				if(isset($data['desc'])) $tmpDescript['description'] = $data['desc'];
				unset($data['name'], $data['desc']);

				// Pic
				if (isset($this->request->post['image'])) {
					$data['image'] = $this->loadImage($this->request->post['image']['file']);
				}

				// Attr
				if (!empty($data['attributes'])) {
					$data['product_attribute'] = $this->genAttr($data['attributes']);
					unset($data['attributes']);
				}

				foreach ($this->getArrLang() as $lang_id)
					$data['product_description'][$lang_id] = array_merge([], $tmpDescript);

				// Modification
				if (!empty($data['Qoptions'])) {
					if (isset($data['Qoptions'][0]['site_option_id']) && !$this->checkYetOption($data['Qoptions'][0]['site_option_id']))
						unset($data['Qoptions'][0]['site_option_id']);
						
					if (isset($data['Qoptions'][0]['site_option_id'])) {
						$data['product_option'][] = $this->createOptions($data['Qoptions'][0], $data['Qoptions'][0]['site_option_id']);
					} else {
						$data['product_option'][] = $this->createOptions($data['Qoptions'][0]);
						// Чисто для опции, если создали, то возвращаем ид
						$json['items']['option_id'] = $data['product_option'][0]['option_id'];
					}
				}
				

				$json['items']['id'] = $this->model_admin_catalog_product->addProduct($data);

			} else {
				$json = ['error' => 'fail'];
			}
		}
		$this->JSON = $json;
	}

	public function edit() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {

			$this->loadAdminModel('catalog/product');
			if (isset($this->request->get['product_id'])) {
				$json = ['success' => 'ok'];

				$data = $this->request->post;

				$open_data = $this->model_admin_catalog_product->getProduct($this->request->get['product_id']);

				$productData = [
					'product_related'	=> 'getProductRelated',
					'product_discount'	=> 'getProductDiscounts',
					'product_special'	=> 'getProductSpecials',
					'product_download'	=> 'getProductSpecials',
					// 'product_category'	=> 'getProductSpecials',
					'product_filter'	=> 'getProductFilters',
					'product_related'	=> 'getProductRelated',
					'product_reward'	=> 'getProductRewards',
					'product_layout'	=> 'getProductLayouts',
					'product_recurring'	=> 'getRecurrings',
				];

				foreach ($productData as $key => $func) {
					$open_data[$key] = $this->model_admin_catalog_product->$func($this->request->get['product_id']);
				}


				$data = array_merge($open_data, $data);

				// На всякий удаляем image
				unset($data['image']);
				$data['product_store'][] = (int)$this->config->get('config_store_id');

				// Model
				$data['model'] = ( empty($data['model']) ) ? uniqid() : $data['model']; 

				$tmpDescript = $open_data;
				if(isset($data['name'])) {
					$tmpDescript['name'] = $data['name'];
					$tmpDescript['meta_title'] = $data['name'];
				}
				if(isset($data['desc'])) $tmpDescript['description'] = $data['desc'];
				unset($data['name'], $data['desc'], $data['meta_title']);

				// Pic
				if (isset($this->request->post['image'])) {
					$data['image'] = $this->loadImage($this->request->post['image']['file']);
				}

				// Attr
				if (!empty($data['attributes'])) {
					$data['product_attribute'] = $this->genAttr($data['attributes']);
					unset($data['attributes']);
				}

				foreach ($this->getArrLang() as $lang_id)
					$data['product_description'][$lang_id] = array_merge([], $tmpDescript);

				// Modification
				if (!empty($data['Qoptions'])) {
					if (isset($data['Qoptions'][0]['site_option_id']) && !$this->checkYetOption($data['Qoptions'][0]['site_option_id']))
						unset($data['Qoptions'][0]['site_option_id']);
	
					$this->removeValueFromProductOption($this->request->get['product_id']);

					if (isset($data['Qoptions'][0]['site_option_id'])) {
						$data['product_option'][] = $this->createOptions($data['Qoptions'][0], $data['Qoptions'][0]['site_option_id']);
					} else {
						$data['product_option'][] = $this->createOptions($data['Qoptions'][0]);
						// Чисто для опции, если создали, то возвращаем ид
						$json['items']['option_id'] = $data['product_option'][0]['option_id'];
					}
				}

				// $this->removeOptionBeforeUpd($this->request->get['product_id']);
				$this->model_admin_catalog_product->editProduct($this->request->get['product_id'], $data);
				$json['items']['id'] = $this->request->get['product_id'];

			} else {
				$json = ['error' => 'fail'];
			}
		}
		$this->JSON = $json;
	}

	public function del() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {

			$this->loadAdminModel('catalog/product');
			if (isset($this->request->get['product_id'])) {

				$this->model_admin_catalog_product->deleteProduct($this->request->get['product_id']);
				$json = ['success' => 'ok'];

			} else {
				$json = ['error' => 'fail'];
			}
		}
		$this->JSON = $json;
	}

	public function test() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$json = ['success' => 'ok'];
			var_dump($this->request->post);
			// $this->checkYetOption(4);
		}
		$this->JSON = $json;
	}

	public function giadd() {

		$this->disableError();
		$this->load->language('api/businessruapi');
		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {

			// $this->loadAdminModel('catalog/product');
			if (isset($this->request->get['product_id']) && isset($this->request->post['pics'])) {

				// $post = $this->request->post;
				$p_id = $this->request->get['product_id'];
				$json = ['success' => 'ok'];
				// $json['items']['id'] = $this->addProductGalImage($this->request->get['product_id'], $this->request->post['file']);

				$this->deletePrevImages($p_id);
				foreach ($this->request->post['pics'] as $image) {
					
					$this->addProductGalImage($p_id, $image['file']);
				}
				// $json['items']['dir'] = DIR_IMAGE;
				// $json['items']['post'] = $this->request->post['file'];
				/*if (isset($post['file'])) {
					
				} else {
					$json = ['error' => 'empty file field'];
				}*/

			} else {
				$json = ['error' => 'empty fields'];
			}
		}
		$this->JSON = $json;
	}

	// Получение категорий по ид продукта
	protected function getProductCats($product_id = false) {
		if (!$product_id) return [];
		
		return $this->model_admin_catalog_product->getProductCategories($product_id);
	}

	// Поулчение опций по ид продукта
	protected function getProductOption($product_id = false) {
		if (!$product_id) return [];

		return $this->model_admin_catalog_product->getProductOptions($product_id);
	}

	protected function createOptions($data = false, $option_id = false) {

		if (!$data) return [];
		/*$data = [
			'type' => 'select',
			'name' => 'Варианты',
			'required' => false,
			'choices' => [
				[
					'text' => 'Размер: 42',
					'priceModifier' => 400,
					'priceModifierType' => 'ABSOLUTE',
				],
				[
					'text' => 'Размер: 44',
					'priceModifier' => 200,
					'priceModifierType' => 'ABSOLUTE',
				]
			]
		];*/
		$this->loadAdminModel('catalog/option');
		if (!$option_id) {

			$option['type'] = $data['type'];
			$option['sort_order'] = 0;
			/*$option['option_description'][(int)$this->config->get('config_language_id')] = [
				'name' => $data['name'],
			];*/
			foreach ($this->getArrLang() as $lang_id)
				$option['option_description'][$lang_id] = [
					'name' => $data['name'],
				];

			$return['option_id'] = $this->model_admin_catalog_option->addOption($option);
		} else {
			$return['option_id'] = $option_id;
		}

		$option_val_ids = [];
		foreach ($data['choices'] as $value) {
			/*$arrVal = [
				'option_value_description' => [
					(int)$this->config->get('config_language_id')  => [
						'name' => $value['text'],
					]
				]
			];*/
			foreach ($this->getArrLang() as $lang_id)
				$arrVal['option_value_description'][$lang_id] = [
					'name' => $value['text'],
				];
			
			$val_opt_id = $this->addValuesToOption($return['option_id'], $arrVal);
			$option_val_ids[] = [
				'name' => $value['text'],
				'option_value_id' => $val_opt_id,
			];
		}
		// print_r($option_val_ids);

		$return['type'] = $data['type'];
		foreach ($option_val_ids as $value) {
			$return['product_option_value'][$value['name']] = [
				'option_value_id' => $value['option_value_id'],

			];
			// echo 123;
		}
		foreach ($data['choices'] as $value) {
			$return['product_option_value'][$value['text']]['price'] = $value['priceModifier'];
			$return['product_option_value'][$value['text']]['quantity'] = $value['quantity'];
		}
		// print_r($return);
		return $return;
	}

	protected function genAttr($attrs = []) {

		if (empty($attrs)) return [];
		/*$attrs = [
			array (
				'good_attr_id' => '20157729',
				'name' => 'Лоляндр',
				'value' => '77777',
			),
			array (
				'good_attr_id' => '20157729',
				'name' => 'Материал',
				'value' => 'Вискоза',
			),
			array (
				'good_attr_id' => '20157729',
				'name' => 'misery',
				'value' => 'Вискоза',
			),
		];*/
		$return = [];

		$attr_group_id = (int)$this->db->query("SELECT attribute_group_id FROM ".DB_PREFIX."attribute_group_description WHERE name = 'class365Import' AND language_id = '".$this->config->get('config_language_id')."'")->row['attribute_group_id'];

		// print_r($attr_group_id);
		// return;
		if (empty($attr_group_id)) {
			$this->db->query("INSERT INTO ".DB_PREFIX."attribute_group SET sort_order = '0'");
			$attr_group_id = $this->db->getLastId();
			foreach ($this->getArrLang() as $lang_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET language_id = '".$lang_id."', name = 'class365Import', attribute_group_id = '".$attr_group_id."'");
			}
		}
		// echo "Find group....".$attr_group_id;
		foreach ($attrs as $attr) {
			
			$attrId = (int)$this->db->query("SELECT a.attribute_id FROM ".DB_PREFIX."attribute_description  ad
				LEFT JOIN
				".DB_PREFIX."attribute a ON ad.attribute_id = a.attribute_id
				WHERE ad.name = '".$attr['name']."' AND a.attribute_group_id = '".$attr_group_id."' ")->row['attribute_id'];

			if (empty($attrId)) {
				$this->loadAdminModel('catalog/attribute');
				$tmpNewAttr = [];
				$tmpNewAttr['attribute_group_id'] = $attr_group_id;
				foreach ($this->getArrLang() as $lang_id)
					$tmpNewAttr['attribute_description'][$lang_id] = ['name' => $attr['name']];
					
				$attrId = $this->model_admin_catalog_attribute->addAttribute($tmpNewAttr);
			}

			$goods_attr_desc_arr = [];
			foreach ($this->getArrLang() as $lang_id) {
				$goods_attr_desc_arr[$lang_id] = [
					'text' => $attr['value'],
				];
			}
			$return[] = [
				'attribute_id' => $attrId,
				'product_attribute_description' => $goods_attr_desc_arr,
			];
		}
		// print_r($return);
		return $return;
	}

	protected function addValuesToOption($option_id = false, $option_value) {

		if ($option_value['option_value_id']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_value_id = '" . (int)$option_value['option_value_id'] . "', option_id = '" . (int)$option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$option_value['sort_order'] . "'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int)$option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$option_value['sort_order'] . "'");
		}

		$option_value_id = $this->db->getLastId();

		foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape($option_value_description['name']) . "'");
		}
		return $option_value_id;
	}

	protected function removeValueFromProductOption($product_id = false) {

		// print_r($this->getProductOption($product_id));
		foreach ($this->getProductOption($product_id) as $value) {
			foreach ($value['product_option_value'] as $val) {
				# code...
				$option_value = (int)$val['option_value_id'];
				// echo $option_value;
				$this->db->query("DELETE FROM " . DB_PREFIX . "option_value WHERE option_value_id = '" . $option_value . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE option_value_id = '" . $option_value . "'");
			}
		}
	}

	protected function addProductGalImage($productId = false, $image) {

		$path = $this->loadImage($image);

		if (!$path) return false;

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$productId . "', image = '".$path."' ");

		return $this->db->getLastId();
	}

	protected function loadImage($file = false) {

		if (!$file) return false;

		$file = base64_decode($file);
		$file_info = new finfo(FILEINFO_MIME);
		$mime_type = $file_info->buffer($file);

		if (!file_exists(DIR_IMAGE.'catalog/classimport')) {

			mkdir(DIR_IMAGE.'catalog/classimport', 0777, true);
		}

		$nPath = DIR_IMAGE;
		$newFilePath = 'catalog/classimport/'.uniqid();
		$nPath .= $newFilePath;
		switch ($mime_type) {
			case (preg_match('/^image\\/jpeg;/', $mime_type) ? true : false):
				$nPath .= ".jpg";
				$newFilePath .= ".jpg";
				break;
			case (preg_match('/^image\\/png;/', $mime_type) ? true : false):
				$nPath .= ".png";
				$newFilePath .= ".png";
				break;
			case (preg_match('/^image\\/gif;/', $mime_type) ? true : false):
				$nPath .= ".gif";
				$newFilePath .= ".gif";
				break;
			default:
				return false;
		}

		$fp = fopen($nPath, "w");
		fwrite($fp, $file);
		fclose($fp);

		return $newFilePath;
	}

	protected function checkYetOption($option_id) {
		return (bool)$this->db->query("SELECT * FROM ".DB_PREFIX."option WHERE option_id = '".(int)$option_id."'")->row;
	}

	protected function getProductImages($productId) {
		return (array)$this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . $productId . "'")->rows;
	}

	protected function deletePrevImages($product_id) {

		foreach ((array)$this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . $product_id . "'")->rows as $image) {
			// var_dump($image);
			unlink(DIR_IMAGE.$image['image']);
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = '" . $image['product_image_id'] . "'");
		}
	}
}