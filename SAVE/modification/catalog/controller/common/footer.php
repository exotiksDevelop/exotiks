<?php
class ControllerCommonFooter extends Controller
{
	public function index()
	{
		
		$this->load->language('common/footer');
		$data['back_to_top'] = $this->config->get('oca_back_to_top_status');
		if ($data['back_to_top']) {
			$this->document->addStyle('catalog/view/javascript/oca_back_to_top/oca_back_to_top.php');
			$this->document->addScript('catalog/view/javascript/oca_back_to_top/oca_back_to_top.js');
		}
		$data['scripts'] = $this->document->getScripts('footer');

		$data['text_information'] = $this->language->get('text_information');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_extra'] = $this->language->get('text_extra');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_sitemap'] = $this->language->get('text_sitemap');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_special'] = $this->language->get('text_special');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_newsletter'] = $this->language->get('text_newsletter');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

					//microdatapro 7.3 start - 1 - main
					$data['microdatapro'] = $this->load->controller('module/microdatapro/company');
					$microdatapro_main_flag = 1;
					//microdatapro 7.3 end - 1 - main
					
		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', 'SSL');
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
		$data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
$data['yandex_metrika'] = $this->config->get('ya_metrika_code') ? html_entity_decode($this->config->get('ya_metrika_code'), ENT_QUOTES, 'UTF-8') : '';
			$data['ya_metrika_active'] = $this->config->get('ya_metrika_active') ? true : false;
			$data['ya_kassa_show_in_footer'] = $this->config->get('ya_kassa_active') && $this->config->get('ya_kassa_show_in_footer');
			
$data['yandex_metrika'] = $this->config->get('yandex_money_metrika_code') ? html_entity_decode($this->config->get('yandex_money_metrika_code'), ENT_QUOTES, 'UTF-8') : '';
            $data['yandex_money_metrika_active'] = $this->config->get('yandex_money_metrika_active') ? true : false;
            $data['yandex_money_kassa_show_in_footer'] = $this->config->get('yandex_money_kassa_enabled') && $this->config->get('yandex_money_kassa_show_in_footer');
            $data['yandex_money_product_info_url'] = 'index.php?route='.(version_compare(VERSION, "2.3.0", '>=')?"extension/":"").'payment/yandex_money/productInfo';
            

					//microdatapro 7.3 start - 1 - main
					$data['microdatapro'] = $this->load->controller('module/microdatapro/company');
					$microdatapro_main_flag = 1;
					//microdatapro 7.3 end - 1 - main
					
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['email'] = $this->config->get('config_email');
		$data['name'] = $this->config->get('config_name');
		$data['home'] = $this->url->link('common/home');

					//microdatapro 7.3 start - 2 - extra
					if(!isset($microdatapro_main_flag)){
						$data['microdatapro'] = $this->load->controller('module/microdatapro/company');
					}
					//microdatapro 7.3 end - 2 - extra
					
		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));
		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}
		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/footer.tpl', $data);
		} else {
			return $this->load->view('magazin/template/common/footer.tpl', $data);
		}		
	}
}
