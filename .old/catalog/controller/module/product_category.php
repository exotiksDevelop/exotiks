<?php
class ControllerModuleProductCategory extends Controller {
	public function index($setting) {
		$this->load->language('module/product_category');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/category');

		$this->load->model('tool/image');

		$data['products'] = array();

		//$categories = explode(',', $this->config->get('product_category_product'));

		if (empty($setting['limit'])) {
			$setting['limit'] = 4;
		}

		//$categories = array_slice($categories, 0, (int)$setting['limit']);
		
		$categories = $this->config->get('product_category_module');
		
		//var_dump($categories);die;
		
		$k = 0;
		
		foreach ($categories as $category) {

            //var_dump($category);die;
			
			$products = $this->model_catalog_product->getProducts(array('filter_category_id' => $category['category'], 'start' => 0, 'limit' => $setting['limit']));
			//var_dump($products);die;
			foreach($products as $i => $product_info)
			{
				//var_dump($product_info);die;
				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], 116, 110);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', 116, 110);
					}
	
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = false;
					}
	
					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}
	
					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
					} else {
						$tax = false;
					}
	
					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}
	
					$data['products'][$k][] = array(
						'title'       => $category['name'],
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
			
			$k++;
		}
		
		//var_dump($data['products']);die;

		if ($data['products']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/product_category.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/product_category.tpl', $data);
			} else {
				return $this->load->view('magazin/template/module/product_category.tpl', $data);
			}
		}
	}
}