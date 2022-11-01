<?php
class ControllerCommonHeader extends Controller {
	public function index() {

    $checkouts = [
      'checkout/checkout',
      'checkout/simplecheckout',
      'checkout/uni_checkout',
      'checkout/oct_fastorder',
      'checkout/newstorecheckout',
      'extension/module/qnec',
      'extension/quickcheckout/checkout',
    ];

    if ($this->config->get('ll_ozon_status') && isset($this->request->get['route']) && in_array((string)$this->request->get['route'], $checkouts)) {
      if ($this->config->get('ll_ozon_map_status')) {
        $this->document->addScript('https://api-maps.yandex.ru/2.1?lang=ru_RU&apikey=' . $this->config->get('ll_ozon_map_key'));
      }

      $this->document->addScript('catalog/view/javascript/ll_ozon/ll_ozon.js');

      if ($this->config->has('ll_shipping_mapper_methods') && in_array('ll_ozon', $this->config->get('ll_shipping_mapper_methods'))) {
        $this->document->addScript('catalog/view/javascript/ll_map.js');
      }
    }
      
//$this->document->addStyle('catalog/view/theme/default/stylesheet/sdek.css');
    //$this->document->addScript('//api-maps.yandex.ru/2.1/?lang=ru_RU&ns=cdekymap');
    //$this->document->addScript('catalog/view/javascript/sdek.js');
	   
		// Analytics
		$this->load->model('extension/extension');

		$data['analytics'] = array();

		$analytics = $this->model_extension_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get($analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('analytics/' . $analytic['code']);
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}


					//microdatapro 7.3 start - 1 - main
					$data['tc_og'] = $this->document->getTc_og();
					$data['tc_og_prefix'] = $this->document->getTc_og_prefix();
					$microdatapro_main_flag = 1;
					//microdatapro 7.3 end - 1 - main
					
		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');


					//microdatapro 7.3 start - 2 - extra
					if(!isset($microdatapro_main_flag)){
						$data['tc_og'] = $this->document->getTc_og();
						$data['tc_og_prefix'] = $this->document->getTc_og_prefix();
					}
					//microdatapro 7.3 end - 2 - extra
					
		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
    $data['open_hours'] = nl2br($this->config->get('config_open'));
    $data['open_days'] = nl2br($this->config->get('config_comment'));

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
		
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['count_goods'] = $this->load->controller('common/count_goods');
		$data['cart'] = $this->load->controller('common/cart');

                $data['config'] = $this->config;
            
		$data['theme_name'] = $this->config->get('config_template');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}
        /*category*/
        $this->load->model('catalog/category');

		$data['category'] = array();

		foreach ($this->model_catalog_category->getCategories() as $result) {
			/*if (!$result['bottom']) {*/
				$data['category'][] = array(
					'name'     => $result['name'],
					'href'  => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			/*}*/
		}

        /**/
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('magazin/template/common/header.tpl', $data);
		}
	}
}
