<?php
/**
* Ozon API for OpenCart 2.1
* @author Dmitri Cheremisin
*/
class ControllerModuleOzonSeller extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/ozon_seller');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ozon_seller', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], 'SSL'));
		}

		// Тексты
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['product'] = $this->language->get('product');
		$data['order_ozon'] = $this->language->get('order_ozon');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_log'] = $this->language->get('text_log');
		$data['text_type'] = $this->language->get('text_type');
		$data['text_length'] = $this->language->get('text_length');
		$data['text_width'] = $this->language->get('text_width');
		$data['text_height'] = $this->language->get('text_height');
		$data['text_weight'] = $this->language->get('text_weight');
		$data['text_stop_export'] = $this->language->get('text_stop_export');
		$data['text_stocks_null'] = $this->language->get('text_stocks_null');
		$data['text_highway'] = $this->language->get('text_highway');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_ruble'] = $this->language->get('text_ruble');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_price_fbo'] = $this->language->get('text_price_fbo');
		$data['text_last_mile_fbo'] = $this->language->get('text_last_mile_fbo');
		$data['text_last_mile'] = $this->language->get('text_last_mile');
		$data['text_min_last_mile'] = $this->language->get('text_min_last_mile');
		$data['text_limit'] = $this->language->get('text_limit');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_atrribute'] = $this->language->get('text_atrribute');
		$data['text_type_to_attr'] = $this->language->get('text_type_to_attr');
		$data['button_type_to_attr'] = $this->language->get('button_type_to_attr');
		$data['attributes_update'] = $this->language->get('attributes_update');
		$data['cron_url_update'] = $this->language->get('cron_url_update');
		$data['cron_url_order_in_ms'] = $this->language->get('cron_url_order_in_ms');
		$data['cron_url_order_fbo'] = $this->language->get('cron_url_order_fbo');
		$data['cron_url_final_status'] = $this->language->get('cron_url_final_status');
		$data['chek_order_list'] = $this->language->get('chek_order_list');
		$data['cron_pass'] = $this->language->get('cron_pass');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_category'] = $this->language->get('tab_category');
		$data['tab_manufacturer'] = $this->language->get('tab_manufacturer');
		$data['tab_attribute'] = $this->language->get('tab_attribute');
		$data['tab_setting'] = $this->language->get('tab_setting');
		$data['tab_price'] = $this->language->get('tab_price');
		$data['tab_warehouse'] = $this->language->get('tab_warehouse');
		$data['tab_order'] = $this->language->get('tab_order');
		$data['tab_moysklad'] = $this->language->get('tab_moysklad');
		$data['tab_fbo'] = $this->language->get('tab_fbo');
		$data['tab_cron'] = $this->language->get('tab_cron');
		$data['tab_about'] = $this->language->get('tab_about');
		$data['payment_ms'] = $this->language->get('payment_ms');
		$data['webhook'] = $this->language->get('webhook');
		$data['reestr_period'] = $this->language->get('reestr_period');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_filter_att'] = $this->language->get('button_filter_att');
		$data['manufacturer_stop'] = $this->language->get('manufacturer_stop');
		$data['manufacturer_download'] = $this->language->get('manufacturer_download');
		$data['manufacturer_compare'] = $this->language->get('manufacturer_compare');
		$data['text_oc'] = $this->language->get('text_oc');
		$data['text_sku'] = $this->language->get('text_sku');
		$data['text_connect_prod'] = $this->language->get('text_connect_prod');
		$data['text_sku_ms'] = $this->language->get('text_sku_ms');
		$data['text_code_ms'] = $this->language->get('text_code_ms');
		$data['text_external_сode'] = $this->language->get('text_external_сode');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_reload_attribute'] = $this->language->get('text_reload_attribute');
		$data['text_autoreturn'] = $this->language->get('text_autoreturn');
		$data['text_status_order_oc'] = $this->language->get('text_status_order_oc');
		$data['text_status_new'] = $this->language->get('text_status_new');
		$data['awaiting_deliver'] = $this->language->get('awaiting_deliver');
		$data['cancelled'] = $this->language->get('cancelled');
		$data['delivering'] = $this->language->get('delivering');
		$data['delivered'] = $this->language->get('delivered');
		$data['returned'] = $this->language->get('returned');
		$data['text_author'] = $this->language->get('text_author');
		$data['author'] = $this->language->get('author');
		$data['text_author_email'] = $this->language->get('text_author_email');
		$data['author_email'] = $this->language->get('author_email');
		$data['text_doc_api_ozon'] = $this->language->get('text_doc_api_ozon');
		$data['doc_api_ozon'] = $this->language->get('doc_api_ozon');
		$data['text_doc_api_ms'] = $this->language->get('text_doc_api_ms');
		$data['doc_api_ms'] = $this->language->get('doc_api_ms');
		$data['text_nds'] = $this->language->get('text_nds');
		$data['text_fictitious_price'] = $this->language->get('text_fictitious_price');
		$data['text_product_blacklist'] = $this->language->get('text_product_blacklist');
		$data['text_warehouse_ozon'] = $this->language->get('text_warehouse_ozon');
		$data['text_price_round'] = $this->language->get('text_price_round');
		$data['text_product_price_oc'] = $this->language->get('text_product_price_oc');
		$data['text_act'] = $this->language->get('text_act');
		$data['text_export_stock_null'] = $this->language->get('text_export_stock_null');
		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_instruction_url'] = $this->language->get('text_instruction_url');
		$data['text_name'] = $this->language->get('text_name');
		$data['text_weight_sklad'] = $this->language->get('text_weight_sklad');
		$data['text_price_sklad'] = $this->language->get('text_price_sklad');
		$data['text_volume_sklad'] = $this->language->get('text_volume_sklad');
		$data['text_download_sklad'] = $this->language->get('text_download_sklad');
		$data['text_delete_sklad'] = $this->language->get('text_delete_sklad');
		$data['text_sklad_info'] = $this->language->get('text_sklad_info');
		$data['text_stok_min'] = $this->language->get('text_stok_min');
		$data['text_ot'] = $this->language->get('text_ot');
		$data['text_do'] = $this->language->get('text_do');
		$data['text_white_list'] = $this->language->get('text_white_list');
		$data['text_no_price_update'] = $this->language->get('text_no_price_update');
		$data['text_blacklist_category'] = $this->language->get('text_blacklist_category');
		$data['text_prices'] = $this->language->get('text_prices');
		$data['text_action'] = $this->language->get('text_action');
		$data['text_value'] = $this->language->get('text_value');
		$data['text_rate'] = $this->language->get('text_rate');
		$data['text_no_stock_update'] = $this->language->get('text_no_stock_update');
		$data['text_url_price'] = $this->language->get('text_url_price');
		$data['text_min_price'] = $this->language->get('text_min_price');
		$data['text_export_category'] = $this->language->get('text_export_category');
		$data['text_menu'] = $this->language->get('text_menu');
		$data['text_export'] = $this->language->get('text_export');
		$data['text_download_update'] = $this->language->get('text_download_update');
		$data['url_update'] = $this->language->get('url_update');
		$data['text_lastname'] = $this->language->get('text_lastname');
		$data['text_card'] = $this->language->get('text_card');
		$data['text_my'] = $this->language->get('text_my');
		$data['text_no_export'] = $this->language->get('text_no_export');
		$data['text_my_text'] = $this->language->get('text_my_text');

		$data['minus'] = $this->language->get('minus');
		$data['entry_client_id'] = $this->language->get('entry_client_id');
		$data['entry_api_key'] = $this->language->get('entry_api_key');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_g'] = $this->language->get('entry_g');
		$data['entry_sm'] = $this->language->get('entry_sm');
		$data['entry_chek_ms'] = $this->language->get('entry_chek_ms');
		$data['entry_login_ms'] = $this->language->get('entry_login_ms');
		$data['entry_key_ms'] = $this->language->get('entry_key_ms');
		$data['entry_organization'] = $this->language->get('entry_organization');
		$data['entry_agent'] = $this->language->get('entry_agent');
		$data['entry_project'] = $this->language->get('entry_project');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_attributes'] = $this->language->get('entry_attributes');
		$data['entry_payment_date'] = $this->language->get('entry_payment_date');
		$data['entry_status_new_order_ms'] = $this->language->get('entry_status_new_order_ms');
		$data['entry_status_awaiting_deliver'] = $this->language->get('entry_status_awaiting_deliver');
		$data['entry_status_print'] = $this->language->get('entry_status_print');
		$data['entry_status_delivering'] = $this->language->get('entry_status_delivering');
		$data['entry_status_delivered'] = $this->language->get('entry_status_delivered');
		$data['entry_status_cancelled'] = $this->language->get('entry_status_cancelled');
		$data['entry_status_returned'] = $this->language->get('entry_status_returned');
		$data['get_metadata_attributes'] = $this->language->get('get_metadata_attributes');
		$data['komission'] = $this->language->get('komission');
		$data['get_product_ms'] = $this->language->get('get_product_ms');
		$data['dictionary'] = $this->language->get('dictionary');
		$data['entry_offer_id'] = $this->language->get('entry_offer_id');
		$data['entry_saleschannel'] = $this->language->get('entry_saleschannel');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_download'] = $this->language->get('button_download');
		$data['button_add'] = $this->language->get('button_add');

		$data['category_shop'] = $this->language->get('category_shop');
		$data['category_ozon'] = $this->language->get('category_ozon');
		$data['att_category'] = $this->language->get('att_category');
		$data['att_shop'] = $this->language->get('att_shop');
		$data['att_ozon'] = $this->language->get('att_ozon');
		$data['get_category_ozon'] = $this->language->get('get_category_ozon');
		$data['get_metadata_order'] = $this->language->get('get_metadata_order');

		$data['help_get_category_ozony'] = $this->language->get('help_get_category_ozony');
		$data['help_id_ms'] = $this->language->get('help_id_ms');
		$data['help_chek_ms'] = $this->language->get('help_chek_ms');
		$data['help_komission'] = $this->language->get('help_komission');
		$data['help_webhook'] = $this->language->get('help_webhook');
		$data['help_reestr'] = $this->language->get('help_reestr');
		$data['help_default_size_ozon'] = $this->language->get('help_default_size_ozon');
		$data['help_attribute'] = $this->language->get('help_attribute');
		$data['help_limit'] = $this->language->get('help_limit');
		$data['help_last_mile'] = $this->language->get('help_last_mile');
		$data['help_last_mile_fbo'] = $this->language->get('help_last_mile_fbo');
		$data['help_price_fbo'] = $this->language->get('help_price_fbo');
		$data['help_price'] = $this->language->get('help_price');
		$data['help_entry_offer_id'] = $this->language->get('help_entry_offer_id');
		$data['help_autoreturn'] = $this->language->get('help_autoreturn');
		$data['help_chek_fbo'] = $this->language->get('help_chek_fbo');
		$data['help_cron'] = $this->language->get('help_cron');
		$data['help_nds'] = $this->language->get('help_nds');
		$data['help_fictitious_price'] = $this->language->get('help_fictitious_price');
		$data['help_product_blacklist'] = $this->language->get('help_product_blacklist');
		$data['help_act'] = $this->language->get('help_act');
		$data['help_prices'] = $this->language->get('help_prices');
		$data['help_lastname'] = $this->language->get('help_lastname');

		$data['entry_chek_fbo'] = $this->language->get('entry_chek_fbo');
		$data['entry_store_fbo'] = $this->language->get('entry_store_fbo');

		// Обработаем ошибки
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['ozon_seller_client_id'])) {
			$data['error_client_id'] = $this->error['ozon_seller_client_id'];
		} else {
			$data['error_client_id'] = '';
		}

		if (isset($this->error['ozon_seller_api_key'])) {
			$data['error_api_key'] = $this->error['ozon_seller_api_key'];
		} else {
			$data['error_api_key'] = '';
		}

		if (isset($this->error['ozon_seller_cron_pass'])) {
			$data['error_cron_pass'] = $this->error['ozon_seller_cron_pass'];
		} else {
			$data['error_cron_pass'] = '';
		}

		//Крошки
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['action'] = $this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&type=module', 'SSL');

		// Запросы
		if (isset($this->request->post['ozon_seller_client_id'])) {
			$data['ozon_seller_client_id'] = $this->request->post['ozon_seller_client_id'];
		} else {
			$data['ozon_seller_client_id'] = $this->config->get('ozon_seller_client_id');
		}
		if (isset($this->request->post['ozon_seller_api_key'])) {
			$data['ozon_seller_api_key'] = $this->request->post['ozon_seller_api_key'];
		} else {
			$data['ozon_seller_api_key'] = $this->config->get('ozon_seller_api_key');
		}
		if (isset($this->request->post['ozon_seller_status'])) {
			$data['ozon_seller_status'][] =  $this->request->post['ozon_seller_status'];
		} else {
			$data['ozon_seller_status'] = $this->config->get('ozon_seller_status');
		}
		if (isset($this->request->post['ozon_seller_category'])) {
			$data['ozon_seller_category'] = $this->request->post['ozon_seller_category'];
		} else {
			$data['ozon_seller_category'] = $this->config->get('ozon_seller_category');
		}
		if (isset($this->request->post['ozon_seller_cron_pass'])) {
			$data['ozon_seller_cron_pass'] = $this->request->post['ozon_seller_cron_pass'];
		} else {
			$data['ozon_seller_cron_pass'] = $this->config->get('ozon_seller_cron_pass');
		}
		if (isset($this->request->post['ozon_seller_login_ms'])) {
			$data['ozon_seller_login_ms'] = $this->request->post['ozon_seller_login_ms'];
		} else {
			$data['ozon_seller_login_ms'] = $this->config->get('ozon_seller_login_ms');
		}
		if (isset($this->request->post['ozon_seller_key_ms'])) {
			$data['ozon_seller_key_ms'] = $this->request->post['ozon_seller_key_ms'];
		} else {
			$data['ozon_seller_key_ms'] = $this->config->get('ozon_seller_key_ms');
		}
		if (isset($this->request->post['ozon_seller_chek_ms'])) {
			$data['ozon_seller_chek_ms'] = $this->request->post['ozon_seller_chek_ms'];
		} else {
			$data['ozon_seller_chek_ms'] = $this->config->get('ozon_seller_chek_ms');
		}
		if (isset($this->request->post['ozon_seller_organization'])) {
			$data['ozon_seller_organization'] = $this->request->post['ozon_seller_organization'];
		} else {
			$data['ozon_seller_organization'] = $this->config->get('ozon_seller_organization');
		}
		if (isset($this->request->post['ozon_seller_agent'])) {
			$data['ozon_seller_agent'] = $this->request->post['ozon_seller_agent'];
		} else {
			$data['ozon_seller_agent'] = $this->config->get('ozon_seller_agent');
		}
		if (isset($this->request->post['ozon_seller_project'])) {
			$data['ozon_seller_project'] = $this->request->post['ozon_seller_project'];
		} else {
			$data['ozon_seller_project'] = $this->config->get('ozon_seller_project');
		}
		if (isset($this->request->post['ozon_seller_store'])) {
			$data['ozon_seller_store'] = $this->request->post['ozon_seller_store'];
		} else {
			$data['ozon_seller_store'] = $this->config->get('ozon_seller_store');
		}
		if (isset($this->request->post['ozon_seller_sticker'])) {
			$data['ozon_seller_sticker'] = $this->request->post['ozon_seller_sticker'];
		} else {
			$data['ozon_seller_sticker'] = $this->config->get('ozon_seller_sticker');
		}
		if (isset($this->request->post['ozon_seller_payment_date'])) {
			$data['ozon_seller_payment_date'] = $this->request->post['ozon_seller_payment_date'];
		} else {
			$data['ozon_seller_payment_date'] = $this->config->get('ozon_seller_payment_date');
		}
		if (isset($this->request->post['ozon_seller_status_new_order_ms'])) {
			$data['ozon_seller_status_new_order_ms'] = $this->request->post['ozon_seller_status_new_order_ms'];
		} else {
			$data['ozon_seller_status_new_order_ms'] = $this->config->get('ozon_seller_status_new_order_ms');
		}
		if (isset($this->request->post['ozon_seller_status_awaiting_deliver'])) {
			$data['ozon_seller_status_awaiting_deliver'] = $this->request->post['ozon_seller_status_awaiting_deliver'];
		} else {
			$data['ozon_seller_status_awaiting_deliver'] = $this->config->get('ozon_seller_status_awaiting_deliver');
		}
		if (isset($this->request->post['ozon_seller_status_print'])) {
			$data['ozon_seller_status_print'] = $this->request->post['ozon_seller_status_print'];
		} else {
			$data['ozon_seller_status_print'] = $this->config->get('ozon_seller_status_print');
		}
		if (isset($this->request->post['ozon_seller_status_delivering'])) {
			$data['ozon_seller_status_delivering'] = $this->request->post['ozon_seller_status_delivering'];
		} else {
			$data['ozon_seller_status_delivering'] = $this->config->get('ozon_seller_status_delivering');
		}
		if (isset($this->request->post['ozon_seller_status_delivered'])) {
			$data['ozon_seller_status_delivered'] = $this->request->post['ozon_seller_status_delivered'];
		} else {
			$data['ozon_seller_status_delivered'] = $this->config->get('ozon_seller_status_delivered');
		}
		if (isset($this->request->post['ozon_seller_status_cancelled'])) {
			$data['ozon_seller_status_cancelled'] = $this->request->post['ozon_seller_status_cancelled'];
		} else {
			$data['ozon_seller_status_cancelled'] = $this->config->get('ozon_seller_status_cancelled');
		}
		if (isset($this->request->post['ozon_seller_status_returned'])) {
			$data['ozon_seller_status_returned'] = $this->request->post['ozon_seller_status_returned'];
		} else {
			$data['ozon_seller_status_returned'] = $this->config->get('ozon_seller_status_returned');
		}
		if (isset($this->request->post['ozon_seller_payment_ms'])) {
			$data['ozon_seller_payment_ms'] = $this->request->post['ozon_seller_payment_ms'];
		} else {
			$data['ozon_seller_payment_ms'] = $this->config->get('ozon_seller_payment_ms');
		}
		if (isset($this->request->post['ozon_seller_attribute'])) {
			$data['ozon_seller_attribute'] = $this->request->post['ozon_seller_attribute'];
		} else {
			$data['ozon_seller_attribute'] = $this->config->get('ozon_seller_attribute');
		}
		if (isset($this->request->post['ozon_seller_manufacturer_stop'])) {
			$data['ozon_seller_manufacturer_stop'] = $this->request->post['ozon_seller_manufacturer_stop'];
		} elseif (!empty($this->config->get('ozon_seller_manufacturer_stop'))) {
			$data['ozon_seller_manufacturer_stop'] = $this->config->get('ozon_seller_manufacturer_stop');
		} else {
			$data['ozon_seller_manufacturer_stop'] = array();
		}
		if (isset($this->request->post['ozon_seller_highway'])) {
			$data['ozon_seller_highway'] = $this->request->post['ozon_seller_highway'];
		} else {
			$data['ozon_seller_highway'] = $this->config->get('ozon_seller_highway');
		}
		if (isset($this->request->post['ozon_seller_percent'])) {
			$data['ozon_seller_percent'] = $this->request->post['ozon_seller_percent'];
		} else {
			$data['ozon_seller_percent'] = $this->config->get('ozon_seller_percent');
		}
		if (isset($this->request->post['ozon_seller_ruble'])) {
			$data['ozon_seller_ruble'] = $this->request->post['ozon_seller_ruble'];
		} else {
			$data['ozon_seller_ruble'] = $this->config->get('ozon_seller_ruble');
		}
		if (isset($this->request->post['ozon_seller_highway_fbo'])) {
			$data['ozon_seller_highway_fbo'] = $this->request->post['ozon_seller_highway_fbo'];
		} else {
			$data['ozon_seller_highway_fbo'] = $this->config->get('ozon_seller_highway_fbo');
		}
		if (isset($this->request->post['ozon_seller_percent_fbo'])) {
			$data['ozon_seller_percent_fbo'] = $this->request->post['ozon_seller_percent_fbo'];
		} else {
			$data['ozon_seller_percent_fbo'] = $this->config->get('ozon_seller_percent_fbo');
		}
		if (isset($this->request->post['ozon_seller_ruble_fbo'])) {
			$data['ozon_seller_ruble_fbo'] = $this->request->post['ozon_seller_ruble_fbo'];
		} else {
			$data['ozon_seller_ruble_fbo'] = $this->config->get('ozon_seller_ruble_fbo');
		}
		if (isset($this->request->post['ozon_seller_last_mile_fbo'])) {
			$data['ozon_seller_last_mile_fbo'] = $this->request->post['ozon_seller_last_mile_fbo'];
		} else {
			$data['ozon_seller_last_mile_fbo'] = $this->config->get('ozon_seller_last_mile_fbo');
		}
		if (isset($this->request->post['ozon_seller_min_last_mile_fbo'])) {
			$data['ozon_seller_min_last_mile_fbo'] = $this->request->post['ozon_seller_min_last_mile_fbo'];
		} else {
			$data['ozon_seller_min_last_mile_fbo'] = $this->config->get('ozon_seller_min_last_mile_fbo');
		}
		if (isset($this->request->post['ozon_seller_limit'])) {
			$data['ozon_seller_limit'] = $this->request->post['ozon_seller_limit'];
		} else {
			$data['ozon_seller_limit'] = $this->config->get('ozon_seller_limit');
		}
		if (isset($this->request->post['ozon_seller_description'])) {
			$data['ozon_seller_description'] = $this->request->post['ozon_seller_description'];
		} else {
			$data['ozon_seller_description'] = $this->config->get('ozon_seller_description');
		}
		if (isset($this->request->post['ozon_seller_attribute_description'])) {
			$data['ozon_seller_attribute_description'] = $this->request->post['ozon_seller_attribute_description'];
		} else {
			$data['ozon_seller_attribute_description'] = $this->config->get('ozon_seller_attribute_description');
		}
		if (isset($this->request->post['ozon_seller_min_last_mile'])) {
			$data['ozon_seller_min_last_mile'] = $this->request->post['ozon_seller_min_last_mile'];
		} else {
			$data['ozon_seller_min_last_mile'] = $this->config->get('ozon_seller_min_last_mile');
		}
		if (isset($this->request->post['ozon_seller_last_mile'])) {
			$data['ozon_seller_last_mile'] = $this->request->post['ozon_seller_last_mile'];
		} else {
			$data['ozon_seller_last_mile'] = $this->config->get('ozon_seller_last_mile');
		}
		if (isset($this->request->post['ozon_seller_stocks_null'])) {
			$data['ozon_seller_stocks_null'] = $this->request->post['ozon_seller_stocks_null'];
		} else {
			$data['ozon_seller_stocks_null'] = $this->config->get('ozon_seller_stocks_null');
		}
		if (isset($this->request->post['ozon_seller_test_export'])) {
			$data['ozon_seller_test_export'][] =  $this->request->post['ozon_seller_test_export'];
		} else {
			$data['ozon_seller_test_export'] = $this->config->get('ozon_seller_test_export');
		}
		if (isset($this->request->post['ozon_seller_chek_fbo'])) {
			$data['ozon_seller_chek_fbo'][] =  $this->request->post['ozon_seller_chek_fbo'];
		} else {
			$data['ozon_seller_chek_fbo'] = $this->config->get('ozon_seller_chek_fbo');
		}
		if (isset($this->request->post['ozon_seller_store_fbo'])) {
			$data['ozon_seller_store_fbo'] = $this->request->post['ozon_seller_store_fbo'];
		} else {
			$data['ozon_seller_store_fbo'] = $this->config->get('ozon_seller_store_fbo');
		}
		if (isset($this->request->post['ozon_seller_komission_fbo'])) {
			$data['ozon_seller_komission_fbo'] = $this->request->post['ozon_seller_komission_fbo'];
		} else {
			$data['ozon_seller_komission_fbo'] = $this->config->get('ozon_seller_komission_fbo');
		}
		if (isset($this->request->post['ozon_seller_entry_offer_id'])) {
			$data['ozon_seller_entry_offer_id'][] =  $this->request->post['ozon_seller_entry_offer_id'];
		} else {
			$data['ozon_seller_entry_offer_id'] = $this->config->get('ozon_seller_entry_offer_id');
		}
		if (isset($this->request->post['ozon_seller_connect_prod_shop'])) {
			$data['ozon_seller_connect_prod_shop'] = $this->request->post['ozon_seller_connect_prod_shop'];
		} else {
			$data['ozon_seller_connect_prod_shop'] = $this->config->get('ozon_seller_connect_prod_shop');
		}
		if (isset($this->request->post['ozon_seller_connect_prod_ms'])) {
			$data['ozon_seller_connect_prod_ms'] = $this->request->post['ozon_seller_connect_prod_ms'];
		} else {
			$data['ozon_seller_connect_prod_ms'] = $this->config->get('ozon_seller_connect_prod_ms');
		}
		if (isset($this->request->post['ozon_seller_autoreturn'])) {
			$data['ozon_seller_autoreturn'] = $this->request->post['ozon_seller_autoreturn'];
		} else {
			$data['ozon_seller_autoreturn'] = $this->config->get('ozon_seller_autoreturn');
		}
		if (isset($this->request->post['ozon_seller_autoreturn_fbs'])) {
			$data['ozon_seller_autoreturn_fbs'] = $this->request->post['ozon_seller_autoreturn_fbs'];
		} else {
			$data['ozon_seller_autoreturn_fbs'] = $this->config->get('ozon_seller_autoreturn_fbs');
		}
		if (isset($this->request->post['ozon_seller_status_order_oc'])) {
			$data['ozon_seller_status_order_oc'] = $this->request->post['ozon_seller_status_order_oc'];
		} else {
			$data['ozon_seller_status_order_oc'] = $this->config->get('ozon_seller_status_order_oc');
		}
		if (isset($this->request->post['ozon_seller_status_new'])) {
			$data['ozon_seller_status_new'] = $this->request->post['ozon_seller_status_new'];
		} else {
			$data['ozon_seller_status_new'] = $this->config->get('ozon_seller_status_new');
		}
		if (isset($this->request->post['ozon_seller_status_deliver'])) {
			$data['ozon_seller_status_deliver'] = $this->request->post['ozon_seller_status_deliver'];
		} else {
			$data['ozon_seller_status_deliver'] = $this->config->get('ozon_seller_status_deliver');
		}
		if (isset($this->request->post['ozon_seller_status_cancel'])) {
			$data['ozon_seller_status_cancel'] = $this->request->post['ozon_seller_status_cancel'];
		} else {
			$data['ozon_seller_status_cancel'] = $this->config->get('ozon_seller_status_cancel');
		}
		if (isset($this->request->post['ozon_seller_status_shipping'])) {
			$data['ozon_seller_status_shipping'] = $this->request->post['ozon_seller_status_shipping'];
		} else {
			$data['ozon_seller_status_shipping'] = $this->config->get('ozon_seller_status_shipping');
		}
		if (isset($this->request->post['ozon_seller_status_delevered'])) {
			$data['ozon_seller_status_delevered'] = $this->request->post['ozon_seller_status_delevered'];
		} else {
			$data['ozon_seller_status_delevered'] = $this->config->get('ozon_seller_status_delevered');
		}
		if (isset($this->request->post['ozon_seller_status_return'])) {
			$data['ozon_seller_status_return'] = $this->request->post['ozon_seller_status_return'];
		} else {
			$data['ozon_seller_status_return'] = $this->config->get('ozon_seller_status_return');
		}
		if (isset($this->request->post['ozon_seller_status_order_fbo_oc'])) {
			$data['ozon_seller_status_order_fbo_oc'] = $this->request->post['ozon_seller_status_order_fbo_oc'];
		} else {
			$data['ozon_seller_status_order_fbo_oc'] = $this->config->get('ozon_seller_status_order_fbo_oc');
		}
		if (isset($this->request->post['ozon_seller_nds'])) {
			$data['ozon_seller_nds'] = $this->request->post['ozon_seller_nds'];
		} elseif (!empty($this->config->get('ozon_seller_nds'))) {
			$data['ozon_seller_nds'] = $this->config->get('ozon_seller_nds');
		} else {
			$data['ozon_seller_nds'] = '0';
		}
		if (isset($this->request->post['ozon_seller_fictitious_price'])) {
			$data['ozon_seller_fictitious_price'] = $this->request->post['ozon_seller_fictitious_price'];
		} else {
			$data['ozon_seller_fictitious_price'] = $this->config->get('ozon_seller_fictitious_price');
		}
		if (isset($this->request->post['ozon_seller_product_blacklist'])) {
			$data['ozon_seller_product_blacklist'] = $this->request->post['ozon_seller_product_blacklist'];
		} elseif (!empty($this->config->get('ozon_seller_product_blacklist'))) {
			$data['ozon_seller_product_blacklist'] = $this->config->get('ozon_seller_product_blacklist');
		} else {
			$data['ozon_seller_product_blacklist'] = array();
		}
		if (isset($this->request->post['ozon_seller_warehouse_ozon'])) {
			$data['ozon_seller_warehouse_ozon'] = $this->request->post['ozon_seller_warehouse_ozon'];
		} else {
			$data['ozon_seller_warehouse_ozon'] = $this->config->get('ozon_seller_warehouse_ozon');
		}
		if (isset($this->request->post['ozon_seller_price_round'])) {
			$data['ozon_seller_price_round'] = $this->request->post['ozon_seller_price_round'];
		} else {
			$data['ozon_seller_price_round'] = $this->config->get('ozon_seller_price_round');
		}
		if (isset($this->request->post['ozon_seller_product_price_oc'])) {
			$data['ozon_seller_product_price_oc'] = $this->request->post['ozon_seller_product_price_oc'];
		} else {
			$data['ozon_seller_product_price_oc'] = $this->config->get('ozon_seller_product_price_oc');
		}
		if (isset($this->request->post['ozon_seller_act'])) {
			$data['ozon_seller_act'] = $this->request->post['ozon_seller_act'];
		} else {
			$data['ozon_seller_act'] = $this->config->get('ozon_seller_act');
		}
		if (isset($this->request->post['ozon_seller_export_stock_null'])) {
			$data['ozon_seller_export_stock_null'] = $this->request->post['ozon_seller_export_stock_null'];
		} else {
			$data['ozon_seller_export_stock_null'] = $this->config->get('ozon_seller_export_stock_null');
		}
		if (isset($this->request->post['ozon_seller_warehouses'])) {
			$data['ozon_seller_warehouses'] = $this->request->post['ozon_seller_warehouses'];
		} else {
			$data['ozon_seller_warehouses'] = $this->config->get('ozon_seller_warehouses');
		}
		if (isset($this->request->post['ozon_seller_sklad'])) {
			$data['ozon_seller_sklad'] = $this->request->post['ozon_seller_sklad'];
		} else {
			$data['ozon_seller_sklad'] = $this->config->get('ozon_seller_sklad');
		}
		if (isset($this->request->post['ozon_seller_product_npu'])) {
			$data['ozon_seller_product_npu'] = $this->request->post['ozon_seller_product_npu'];
		} elseif (!empty($this->config->get('ozon_seller_product_npu'))) {
			$data['ozon_seller_product_npu'] = $this->config->get('ozon_seller_product_npu');
		} else {
			$data['ozon_seller_product_npu'] = array();
		}
		if (isset($this->request->post['ozon_seller_saleschannel_ms'])) {
			$data['ozon_seller_saleschannel_ms'] = $this->request->post['ozon_seller_saleschannel_ms'];
		} else {
			$data['ozon_seller_saleschannel_ms'] = $this->config->get('ozon_seller_saleschannel_ms');
		}
		if (isset($this->request->post['ozon_seller_prices'])) {
			$data['ozon_seller_prices'] = $this->request->post['ozon_seller_prices'];
		} else {
			$data['ozon_seller_prices'] = $this->config->get('ozon_seller_prices');
		}
		if (isset($this->request->post['ozon_seller_min_price'])) {
			$data['ozon_seller_min_price'] = $this->request->post['ozon_seller_min_price'];
		} else {
			$data['ozon_seller_min_price'] = $this->config->get('ozon_seller_min_price');
		}
		if (isset($this->request->post['ozon_seller_export_category'])) {
			$data['ozon_seller_export_category'] = $this->request->post['ozon_seller_export_category'];
		} else {
			$data['ozon_seller_export_category'] = $this->config->get('ozon_seller_export_category');
		}
		if (isset($this->request->post['ozon_seller_lastname'])) {
			$data['ozon_seller_lastname'] = $this->request->post['ozon_seller_lastname'];
		} else {
			$data['ozon_seller_lastname'] = $this->config->get('ozon_seller_lastname');
		}
		if (isset($this->request->post['ozon_seller_my_description'])) {
			$data['ozon_seller_my_description'] = $this->request->post['ozon_seller_my_description'];
		} else {
			$data['ozon_seller_my_description'] = $this->config->get('ozon_seller_my_description');
		}

		$data['prices_elses'] = array(
			'price'						=> 'Цена',
			'manufacturer_id' => 'ID производителя',
			'category_id' 		=> 'ID категории',
			'product_id' 			=> 'ID товара'
		);

		$data['prices_action'] = array(
			'+' => '+',
			'-' => '-',
			'*' => '*'
		);

		$data['relations'] = array(
      'sku'        => 'по артикулу',
      'model'      => 'по коду (модель)',
      'product_id' => 'по ID товара'
    );

		// Округление цен
		$data['price_round'] = array(
			'0'			=> $this->language->get('text_round_price_null'),
			'st'		=> $this->language->get('text_round_price_st'),
			'ten'		=> $this->language->get('text_round_price_ten'),
			'st_so'	=> $this->language->get('text_round_price_st_so'),
			'so'		=> $this->language->get('text_round_price_so'),
			'fifty'	=> $this->language->get('text_round_price_fifty'),
		);

		// НДС
		$data['nalog_nds'] = array(
			'0' => $this->language->get('text_nds_null'),
			'0.1' => $this->language->get('text_nds_ten'),
			'0.2' => $this->language->get('text_nds_twenty')
		);

		// Черный список
		$data['blacklist'] = array();
		if (!empty($this->config->get('ozon_seller_product_blacklist'))) {
			$this->load->model('catalog/product');
			$blacklist = $this->config->get('ozon_seller_product_blacklist');
			foreach ($blacklist as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				if ($product_info) {
					$data['blacklist'][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
		}

		// Черный список цен
		$data['products_npu'] = array();
		if (!empty($this->config->get('ozon_seller_product_npu'))) {
			$this->load->model('catalog/product');
			$products_npu = $this->config->get('ozon_seller_product_npu');
			foreach ($products_npu as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				if ($product_info) {
					$data['products_npu'][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
		}

		// Белый список складов
		$data['sklad_white_list'] = array();
		if (!empty($this->config->get('ozon_seller_sklad'))) {
			$this->load->model('catalog/product');
			foreach ($this->config->get('ozon_seller_sklad') as $key => $sklad) {
				if (!empty($sklad['white_list'])) {
					$white_list = $sklad['white_list'];
					foreach ($white_list as $product_id) {
						$product_info = $this->model_catalog_product->getProduct($product_id);
						if ($product_info) {
							$data['sklad_white_list'][] = array(
								'sklad' => $key,
								'product_id' => $product_info['product_id'],
								'name'       => $product_info['name']
							);
						}
					}
				}
			}
		}

		// Черный список складов
		$data['sklad_black_list'] = array();
		if (!empty($this->config->get('ozon_seller_sklad'))) {
			$this->load->model('catalog/product');
			foreach ($this->config->get('ozon_seller_sklad') as $key => $sklad) {
				if (!empty($sklad['black_list'])) {
					$black_list = $sklad['black_list'];
					foreach ($black_list as $product_id) {
						$product_info = $this->model_catalog_product->getProduct($product_id);
						if ($product_info) {
							$data['sklad_black_list'][] = array(
								'sklad' => $key,
								'product_id' => $product_info['product_id'],
								'name'       => $product_info['name']
							);
						}
					}
				}
			}
		}

		// Не обновлять остатки на складах
		$data['sklad_no_update'] = array();
		if (!empty($this->config->get('ozon_seller_sklad'))) {
			$this->load->model('catalog/product');
			foreach ($this->config->get('ozon_seller_sklad') as $key => $sklad) {
				if (!empty($sklad['no_update'])) {
					$no_update = $sklad['no_update'];
					foreach ($no_update as $product_id) {
						$product_info = $this->model_catalog_product->getProduct($product_id);
						if ($product_info) {
							$data['sklad_no_update'][] = array(
								'sklad' => $key,
								'product_id' => $product_info['product_id'],
								'name'       => $product_info['name']
							);
						}
					}
				}
			}
		}

		// Категории
		$this->load->model('module/ozon_seller');
		$data['categories'] = array();
		$data['ozon_categories'] = array();
		if (!empty($data['ozon_seller_category'])) {
			$ozon_categories = array();
			$shop_category_id = array();
			foreach ($data['ozon_seller_category'] as $ozon_category_title) {
				if (!in_array($ozon_category_title['ozon'], $ozon_categories)) {
					$ozon_categories[] = $ozon_category_title['ozon'];
				}
				if (!in_array($ozon_category_title['shop'], $shop_category_id)) {
					$shop_category_id[] = $ozon_category_title['shop'];
				}
			}
			if (!empty($shop_category_id)) {
				$data['categories'] = $this->model_module_ozon_seller->getCategories($shop_category_id);
			}
			if (!empty($ozon_categories)) {
				$data['ozon_categories'] = $this->model_module_ozon_seller->getOzonCategory($ozon_categories);
			}
		}
		$data['dictionarys_ozon'] = $this->model_module_ozon_seller->getOzonDictionaryNoCategory();

		// Атрибуты
		$this->load->model('catalog/attribute');
		$data['button_download_attr'] = $this->buttonDownloadAttr();
		$data['attributes_shop'] = array();
		if (!empty($this->config->get('ozon_seller_attribute'))) {
			foreach ($this->config->get('ozon_seller_attribute') as $ozon_seller_attribute) {
				$data['attributes_shop'][] = $this->model_catalog_attribute->getAttribute($ozon_seller_attribute);
			}
		}
		$data['attributes_shop'][] = array('attribute_id' => 'sku', 'name' => 'Артикул');
		$data['attributes_shop'][] = array('attribute_id' => 'model', 'name' => 'Модель');
		$data['attributes_shop'][] = array('attribute_id' => 'mpn', 'name' => 'MPN');
		$data['attributes_shop'][] = array('attribute_id' => 'isbn', 'name' => 'ISBN');
		$data['attributes_shop'][] = array('attribute_id' => 'ean', 'name' => 'EAN');
		$data['attributes_shop'][] = array('attribute_id' => 'jan', 'name' => 'JAN');
		$data['attributes_shop'][] = array('attribute_id' => 'upc', 'name' => 'UPC');
		$data['attributes_ozon'] = $this->model_module_ozon_seller->getOzonAttribute();//DB table ozon_atribute*/

		$attr_ozon_descr = $this->model_module_ozon_seller->getOzonAttributeDescription();
		$data['attributes_ozon_description'] = array();
		foreach ($attr_ozon_descr as $attr_ozon) {
			if ($attr_ozon['ozon_attribute_id'] == 8229) {
				continue;
			} else {
				$data['attributes_ozon_description'][] = $attr_ozon;
			}
		}

		// Производители
		$this->load->model('catalog/manufacturer');
		$filter_data = array('sort' => 'name');
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers($filter_data);

		// Статусы заказа OC
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		// Связка товара с МС
		$data['connect_shop'] = array('sku' => $data['text_sku'], 'model' => $data['text_model']);
		$data['connect_ms'] = array('article' => $data['text_sku_ms'], 'code' => $data['text_code_ms'], 'ex_code' => $data['text_external_сode']);

		// URL
		$data['url_price'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/updateprice&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['update_url'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/updateozonproduct&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['url_order_in_ms'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/getnewordersozon&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['url_order_fbo'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/checkfboorders&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['url_final_status'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/statusdelivered&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['url_chek_order_list'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/chekorderlist&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['get_metadata'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/getmetadataorder&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['get_attributes'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/getmetadataattributes&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['download_product_ms'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/productupdate&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['webhook_create'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/webhookcreate';

		$data['webhook_delete'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/webhookdelete&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['url_manufacturer_download'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/manufacturerdownload&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['url_product'] = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'], true);

		$data['url_order'] = $this->url->link('module/ozon_seller/order', 'token=' . $this->session->data['token'], true);

		$data['url_warehouse'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/warehouseozon&cron_pass=' . $data['ozon_seller_cron_pass'];

		$data['url_export'] = $this->url->link('module/ozon_seller/export', 'token=' . $this->session->data['token'], true);

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['ozon_seller_length'])) {
			$data['ozon_seller_length'] = $this->request->post['ozon_seller_length'];
		} elseif (!empty('ozon_seller_length')) {
			$data['ozon_seller_length'] = $this->config->get('ozon_seller_length');
		} else {
			$data['ozon_seller_length'] = $this->config->get('config_length_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['ozon_seller_weight'])) {
			$data['ozon_seller_weight'] = $this->request->post['ozon_seller_weight'];
		} elseif (!empty('ozon_seller_weight')) {
			$data['ozon_seller_weight'] = $this->config->get('ozon_seller_weight');
		} else {
			$data['ozon_seller_weight'] = $this->config->get('config_weight_class_id');
		}

		$data['clear'] = $this->url->link('module/ozon_seller/clear', 'token=' . $this->session->data['token'], 'SSL');

		$data['log'] = '';
		$file = DIR_LOGS . 'ozon_seller.log';
		if (file_exists($file)) {
			$size = filesize($file);

			if ($size >= 5242880) {
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);

				$i = 0;

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				$data['error_warning'] = sprintf($this->language->get('error_warning'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			} else {
				$data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
			}
		}

		// Отображение
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['token'] = $this->session->data['token'];
		$this->response->setOutput($this->load->view('module/ozon_seller.tpl', $data));
	}

	/* Кнопка скачать атрибуты */
	public function buttonDownloadAttr() {

		$this->load->model('setting/setting');
		$this->load->model('module/ozon_seller');
		$categorys = $this->config->get('ozon_seller_category');
		$attributes = $this->model_module_ozon_seller->getOzonAttribute();
		$output = '';
		if (isset($categorys)) {
			if (!empty($attributes)) {
				$category_attr = array();
				foreach ($attributes as $attribute) {
					$category_attr[] = $attribute['ozon_category_id'];
				}
				foreach ($categorys as $category) {
					$category_cat = $category['ozon'];
					if (!in_array($category_cat, $category_attr)) {
						$check = true;
						break;
					}
				}
			} else {
				$check = true;
			}

			if (isset($check)) {
				$output = '<button type="button" class="btn btn-warning load-attribute">' . $this->language->get('attributes_update') . '</button>';
			}
		}
		return $output;
	}

	/* Формирование модального окна для сопоставления производителей */
	public function manufacturerSet() {

		if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
		  exit();
		}
		$this->load->model('module/ozon_seller');
		$this->load->model('catalog/manufacturer');
		$filter_data = array('sort' => 'name');
		$shop_manufacturers = $this->model_catalog_manufacturer->getManufacturers($filter_data);
		$body = '<form action="" method="post" id="manufacturer-form">
			<div class="table-responsive">
				<table class="table table-sm table-striped table-bordered table-hover">
					<thead>
						<tr>
							<td>Производители в магазине</td>
							<td>Производители в Ozon</td>
							<td>Изображение</td>
						</tr>
					</thead>
					<tbody>';

		foreach ($shop_manufacturers as $shop_manufacturer) {
			$body .= '<tr><td><input type="hidden" name="manufacturer[' . $shop_manufacturer['manufacturer_id'] . '][shop_id]" value="' . $shop_manufacturer['manufacturer_id'] . '" />' . $shop_manufacturer['name'] . '</td>';

			$ozon_manufacturer = $this->model_module_ozon_seller->getManufacturer($shop_manufacturer['manufacturer_id']);

			if (empty($ozon_manufacturer)) {
				$ozon_img = '';
				$ozon_value = '';
				$ozon_id = '';
			} else {
				$ozon_img = '<img src="' . $ozon_manufacturer[0]['picture'] . '" height="30" />';
				$ozon_value = $ozon_manufacturer[0]['value'];
				$ozon_id = $ozon_manufacturer[0]['ozon_id'];
			}

			$body .= '<td>
			<input type="text" class="form-control manufacturer-ozon" data-id="' . $shop_manufacturer['manufacturer_id'] . '" value="' . $ozon_value . '" />
			<input type="hidden" name="manufacturer[' . $shop_manufacturer['manufacturer_id'] . '][ozon_id]" value="' . $ozon_id . '" /></td>';

			$body .= '<td><div class="img-manufacturer' . $shop_manufacturer['manufacturer_id'] . '">' . $ozon_img . '</div></td></tr>';
		}
		$body .= '</tbody></table></div></form>';
		$body .= "<script>
			$('.manufacturer-ozon').focus(function() {
				manufacturer = $(this).attr('data-id');
			});

				var inputOzonId = 'manufacturer[' + window.manufacturer + '][ozon_id]';

				$('.manufacturer-ozon').autocomplete({
					'source': function(request, response) {
						$.ajax({
							url: 'index.php?route=module/ozon_seller/manufacturerautocomplete&token=" . $this->session->data['token'] . "&filter_name=' +  encodeURIComponent(request),
							dataType: 'json',
							success: function(json) {
								json.unshift({
									ozon_id: 'delete',
									picture: '',
									value: '-- очистить --'
								});
								response($.map(json, function(item) {
									return {
										label: item['value'] ,
										icon: item['picture'],
										value: item['ozon_id']
									}
								}));
							}
						});
					},
					'select': function(item) {
						$('input[data-id=\'' + window.manufacturer + '\']').val(item['label']);
						$('input[name=\'manufacturer[' + window.manufacturer + '][ozon_id]\']').val(item['value']);
						$('.img-manufacturer' + window.manufacturer).html(item['icon']);
					}
				});
			</script>";

		$footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button><button type="button" class="btn btn-success save-manufacturer">Сохранить изменения</button>';

		$output['title'] = 'Сопоставьте производителей Ozon и магазина';
		$output['body'] = $body;
		$output['footer'] = $footer;
		echo json_encode($output);
	}

	/* Живой поиск производителей Ozon */
	public function manufacturerAutocomplete() {
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('module/ozon_seller');
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 10
			);

			$results = $this->model_module_ozon_seller->searchOzonManufacturer($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'ozon_id' => $result['ozon_id'],
					'value'   => strip_tags(html_entity_decode($result['value'], ENT_QUOTES, 'UTF-8')),
					'picture' => '<img src="' . $result['picture'] . '" height="30" />'
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['value'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/* Сохранить сопоставленных производителей */
	public function saveManufacturer() {

		$this->load->model('module/ozon_seller');
		if (isset($_POST['manufacturer'])) {
			foreach ($_POST['manufacturer'] as $manufacture) {
				if (!empty($manufacture['ozon_id'])) {
					$shop_id = $manufacture['shop_id'];
					$ozon_id = $manufacture['ozon_id'];
					$response = $this->model_module_ozon_seller->updateManufacturer($shop_id, $ozon_id);
				}
			}
		}
	}

	/* Формирование модального окна для типа товара */
	public function modalType() {

		if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
		  exit();
		}
		$this->load->language('module/ozon_seller');
		$this->load->model('module/ozon_seller');

		if (isset($this->request->get['category_ozon'])) {
			$category_ozon = $this->request->get['category_ozon'];
			$types_ozon = $this->model_module_ozon_seller->getOzonDictionaryType($category_ozon);
			if (!empty($types_ozon)) {
				$body = '<form action="" method="post" id="type-form">
					<div class="table-responsive" style="padding:15px;">
						<table class="table table-sm table-striped table-bordered table-hover">
							<thead>
								<tr>
									<td>Типы товара этой категории на Ozon</td>
									<td>Значение атрибута магазина</td>
								</tr>
							</thead>
							<tbody>';

				$dictionary_row = 0;

				foreach ($types_ozon as $type_ozon) {
					$body .= '<tr><td>' . $type_ozon['text'] . '<input type="hidden" name="type[' . $dictionary_row . '][value]" value="' . $type_ozon['text'] . '" /><input type="hidden" name="type[' . $dictionary_row . '][dictionary_value_id]" value="' . $type_ozon['attribute_value_id'] . '" />
					</td>';

					$types_shop = $this->model_module_ozon_seller->getDictionaryShoptoOzonDictionaryId($type_ozon['attribute_value_id']);

					if (!empty($types_shop)) {
						foreach ($types_shop as $type_shop) {
							$type_text_shop = $type_shop['text_shop_attribute'];
						}
					} else {
						$type_text_shop = '';
					}

					$body .= '<td>
						<input name="type[' . $dictionary_row . '][text_shop_attribute]" value="' . $type_text_shop . '" title="Можно указать несколько значений разделяя их ++. Пример: 1++2++3" />
					</td></tr>';

					$dictionary_row++;
				}

				$body .= '</tbody></table></div></form>';
				$body .= $this->language->get('help_attribute');

				$footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button><button type="button" class="btn btn-success save-type">Сохранить изменения</button>';

				$output['title'] = 'Сопоставьте тип товара этой категории на Ozon с атрибутами в магазине';
				$output['body'] = $body;
				$output['footer'] = $footer;
				echo json_encode($output);
			}
		}
	}

	// формирование модального окна с атрибутами
	public function modalDictionary()
	{
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
		  exit();
		}
		if (!empty($this->request->get['dictionary']) && !empty($this->request->get['shop_id'])) {
			$this->load->model('module/ozon_seller');
			$ozon_attribute_id = $this->request->get['dictionary'];
			$shop_attribute_id = $this->request->get['shop_id'];
			$attributes_ozon_description = $this->model_module_ozon_seller->ozonAttributeDescription($ozon_attribute_id);
		} else {
			exit('error');
		}

		$body = '<form action="" method="post" id="dictionary-form">
			<input type="hidden" name="ozon-attribute-id" value="' . $ozon_attribute_id . '" />
			<input type="hidden" name="ozon-dictionary-id" value="' . $attributes_ozon_description[0]['ozon_dictionary_id'] . '" />
			<div class="table-responsive" style="padding:15px;">
				<table class="table table-sm table-striped table-bordered table-hover">
					<thead>
						<tr>
							<td>Атрибут магазина</td>
							<td>Атрибуты Ozon</td>
							<td>Поиск атрибутов Ozon</td>
						</tr>
					</thead>
					<tbody>';

		$dictionary_shop = $this->model_module_ozon_seller->getShopDictionary($shop_attribute_id);
		$dictionarys_ozon_to_shop = $this->model_module_ozon_seller->dictionaryShoptoOzon($ozon_attribute_id, $shop_attribute_id);
		$dictionary_row = 0;
		foreach ($dictionary_shop as $dictionar_shop) {
			$body .= '<tr><td>' . $dictionar_shop['text'] . '<input type="hidden" name="dictionary[' . $dictionary_row . '][text-shop-attribute]" value="' . $dictionar_shop['text'] . '" /></td>';
			$text = '';
			$body .= '<td><div data-attr-list="' . $dictionary_row . '">';
			foreach ($dictionarys_ozon_to_shop as $dictionary_ozon_to_shop) {
				if (empty($dictionary_ozon_to_shop['dictionary_value_id'])) {
					continue;
				}
				if ($dictionary_ozon_to_shop['text_shop_attribute'] == $dictionar_shop['text']) {
					$attribute_value_id = explode('^', $dictionary_ozon_to_shop['dictionary_value_id']);
					$attribute_value = explode('^', $dictionary_ozon_to_shop['value']);
					foreach ($attribute_value_id as $key => $attribut_value_id) {
						$body .= '<div data-attr="' . $dictionary_row . $attribut_value_id . '"><i class="fa fa-minus-circle"></i> ' . $attribute_value[$key] . '<input type="hidden" name="dictionary[' . $dictionary_row . '][dictionary-value-id][]" value="' . $attribut_value_id . '" /><input type="hidden" name="dictionary[' . $dictionary_row . '][value][]" value="' . $attribute_value[$key] . '" /></div>';
					}
				}
			}
			$body .= '</div></td><td><input type="text" class="form-control dictionary-search" data-dictionary-id="' . $attributes_ozon_description[0]['ozon_dictionary_id'] . '" data-row="' . $dictionary_row . '" /></td></tr>';
			$dictionary_row++;
		}
		$body .= '</tbody></table></div>
		<input type="hidden" name="shop-attribute-id" value="' . $shop_attribute_id . '" /></form>';

		$body .="<script>
		$('.dictionary-search').focus(function() {
			rows = $(this).attr('data-row');
			dictionary_id = $(this).attr('data-dictionary-id');
		});

		$('.dictionary-search').autocomplete({
			'multiselect': true,
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=module/ozon_seller/autocompletedictionary&dictionary=' + dictionary_id + '&token=" . $this->session->data['token'] . "&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item.text,
								value: item.attribute_value_id
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('.dictionary-search').val('');

				$('[data-attr=\'' + window.rows + item['value'] + '\']').remove();

				$('[data-attr-list=\'' + window.rows + '\']').append('<div data-attr=\'' + window.rows + item['value'] + '\'><i class=\'fa fa-minus-circle\'></i> ' + item['label'] + '<input type=\'hidden\' name=\'dictionary[' + window.rows +  '][value][]\' value=\'' + item['label'] + '\' />' + '<input type=\'hidden\' name=\'dictionary[' + window.rows +  '][dictionary-value-id][]\' value=\'' + item['value'] + '\' /></div>');
			}
		});
		$('[data-attr-list]').delegate('.fa-minus-circle', 'click', function() {
			$(this).parent().remove();
		});</script>";

		$footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button><button type="button" class="btn btn-success save-dictionary">Сохранить изменения</button>';
		$data['dictionary_shop'] = array();
		$output['title'] = $attributes_ozon_description[0]['ozon_attribute_name'];
		$output['body'] = $body;
		$output['footer'] = $footer;
		echo json_encode($output);
	}

	/* Сохранить сопоставленные значения типа товара */
	public function saveType() {

		$this->load->model('module/ozon_seller');
		foreach ($_POST['type'] as $type) {
			$value = $type['value'];
			$dictionary_value_id = $type['dictionary_value_id'];
			$text_shop_attribute = trim($type['text_shop_attribute']);

			if (empty($text_shop_attribute)) {
				$this->model_module_ozon_seller->deleteDictionaryValue($dictionary_value_id);
			} else {
				$response = $this->model_module_ozon_seller->saveDictionary($ozon_attribute_id = 8229, $shop_attribute_id = 0, $dictionary_value_id, $value, $text_shop_attribute);
			}
		}
	}

	/* Сохранить сопоставленные значения словарей */
	public function saveDictionary() {

		$this->load->model('module/ozon_seller');
		$ozon_attribute_id = $_POST['ozon-attribute-id'];
		$shop_attribute_id = $_POST['shop-attribute-id'];
		$this->model_module_ozon_seller->unsetDictionary($ozon_attribute_id, $shop_attribute_id);
		foreach ($_POST['dictionary'] as $dictionary) {
			if (!empty($dictionary['dictionary-value-id']) && !empty($dictionary['value'])) {
				$dictionary_value_id = implode('^', $dictionary['dictionary-value-id']);
				$value = implode('^', $dictionary['value']);
				$text_shop_attribute = htmlspecialchars($dictionary['text-shop-attribute']);

				$response = $this->model_module_ozon_seller->saveDictionary($ozon_attribute_id, $shop_attribute_id, $dictionary_value_id, $value, $text_shop_attribute);
			}
		}
	}

	public function clear() {
		if (!$this->user->hasPermission('modify', 'module/ozon_seller')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			$file = DIR_LOGS . 'ozon_seller.log';
			$handle = fopen($file, 'w+');
			fclose($handle);
			$this->session->data['success'] = 'Лог очищен';
		}
		$this->response->redirect($this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], 'SSL'));
	}

	/* Живой поиск категорий Ozon */
	public function autocomplete() {
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('module/ozon_seller');
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 70
			);
			$results = $this->model_module_ozon_seller->searchOzonCategory($filter_data);
			foreach ($results as $result) {
				$json[] = array(
					'ozon_category_id' => $result['ozon_category_id'],
					'title'            => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}
		$sort_order = array();
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['title'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/* Живой поиск справочника Ozon */
	public function autocompleteDictionary() {
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('module/ozon_seller');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'ozon_dictionary_id' => $this->request->get['dictionary'],
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_module_ozon_seller->searchOzonDictionary($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'attribute_value_id' => $result['attribute_value_id'],
					'text' => strip_tags(html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}
		$sort_order = array();
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['text'];
		}
		array_multisort($sort_order, SORT_ASC, $json);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Удалить товары в статусе
	public function clearProductInStatus()
	{
		if (isset($this->request->get['status'])) {
			$status = $this->request->get['status'];
			$this->load->model('module/ozon_seller');
			$this->model_module_ozon_seller->deletedExportProductStatus($status);
			$this->response->redirect($this->url->link('module/ozon_seller/product&filter_status=' . $status, 'token=' . $this->session->data['token'], true));
		}
	}

	/* Список товаров в Ozon */
	public function product() {
		$this->load->language('module/ozon_seller');
		$this->document->setTitle($this->language->get('heading_title_my_product'));
		$this->load->model('module/ozon_seller');
		$this->load->model('setting/setting');
		$this->getList();
	}

	protected function getList() {

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_sku'])) {
			$filter_sku = $this->request->get['filter_sku'];
		} else {
			$filter_sku = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.date';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		$data['btn_update_status'] = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));

			$url_update_status = $this->url->link('module/ozon_seller/clearproductinstatus&status=' . $this->request->get['filter_status'], 'token=' . $this->session->data['token'], true);

			$data['btn_update_status'] = '<a href="' . $url_update_status . '" type="button" class="btn btn-danger status-update">Удалить из таблицы товары в статусе ' . $this->request->get['filter_status'] . '</a>';
		}

		$data['token'] = $this->session->data['token'];

		// URL
		$data['url_order'] = $this->url->link('module/ozon_seller/order', 'token=' . $this->session->data['token'], true);
		$data['url_general'] = $this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], true);
		$data['url_export'] = $this->url->link('module/ozon_seller/export', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);
		$data['ozon_seller_cron_pass'] = $this->config->get('ozon_seller_cron_pass');
		$data['url_update_status_product'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/updatenewozonproduct&cron_pass=' . $data['ozon_seller_cron_pass'];

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('product'),
			'href' => $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'], true)
		);

		$data['statuses'] = array(
			'fail' => 'fail',
			'pending' => 'pending',
			'processing' => 'processing',
			'imported' => 'imported',
			'processed' => 'processed'
		);

		$data['products'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_sku'	  => $filter_sku,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_module_ozon_seller->getTotalProducts($filter_data);

		$results = $this->model_module_ozon_seller->getProducts($filter_data);

		foreach ($results as $result) {

			$status = '';

			switch ($result['status']) {
				case 'posting':
					$status .= '<span class="label label-warning">' . $result['status'] . '</span>';
					break;

				case 'imported':
					$status .= '<span class="label label-warning">' . $result['status'] . '</span>';
					break;

				case 'pending':
					$status .= '<span class="label label-warning">' . $result['status'] . '</span>';
					break;

				case 'processing':
					$status .= '<span class="label label-info">' . $result['status'] . '</span>';
					break;

				case 'processed':
					$status .= '<span class="label label-success">' . $result['status'] . '</span>';
					break;

				default:
					$status .= '<span class="label label-danger">' . $result['status'] . '</span>';
					break;
			}

			$view_ozon = '';

			if (!empty($result['ozon_sku'])) {
				$view_ozon .= '<a href="https://www.ozon.ru/context/detail/id/' . $result['ozon_sku'] . '" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>';
			}

			if (!empty($result['special'])) {
				$price = $result['special'];
			} else {
				$price = $result['price'];
			}

			$data['products'][] = array(
				'product_id'      => $result['product_id'],
				'name'            => $result['name'],
				'sku'             => $result['sku'],
				'model'           => $result['model'],
				'ozon_product_id' => $result['ozon_product_id'],
				'date'            => $result['date'],
				'error'           => $result['error'],
				'stock_fbs'       => $result['stock_fbs'],
				'stock_fbo'       => $result['stock_fbo'],
				'price_oz'        => $result['price_oz'],
				'price_oc'        => $price,
				'komission_fbo'   => $result['komission_fbo'],
				'komission_fbs'   => $result['komission_fbs'],
				'view_ozon'       => $view_ozon,
				'status'          => $status
			);
		}

		$data['heading_title_my_product'] = $this->language->get('heading_title_my_product');
		$data['product'] = $this->language->get('product');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_error'] = $this->language->get('text_error');
		$data['text_download_product'] = $this->language->get('text_download_product');
		$data['text_status_product'] = $this->language->get('text_status_product');
		$data['text_menu'] = $this->language->get('text_menu');
		$data['text_export'] = $this->language->get('text_export');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['order_ozon'] = $this->language->get('order_ozon');
		$data['text_fbs'] = $this->language->get('text_fbs');
		$data['text_fbo'] = $this->language->get('text_fbo');
		$data['text_price_oc'] = $this->language->get('text_price_oc');
		$data['text_price_oz'] = $this->language->get('text_price_oz');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sku'] = $this->language->get('column_sku');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date'] = $this->language->get('column_date');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_update_ozon'] = $this->language->get('button_update_ozon');
		$data['update_products_ozon'] = $this->language->get('update_products_ozon');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete_ozon'] = $this->language->get('button_delete_ozon');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . urlencode(html_entity_decode($this->request->get['filter_sku'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_sku'] = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'] . '&sort=p.sku' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$data['sort_date'] = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'] . '&sort=p.date' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . urlencode(html_entity_decode($this->request->get['filter_sku'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_sku'] = $filter_sku;
		$data['filter_model'] = $filter_model;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/ozon_seller_product.tpl', $data));
	}

	public function updateProductOzon() {
		$this->load->model('module/ozon_seller');
		if (isset($this->request->get['product_shop_id'])) {
			$product_id = $this->request->get['product_shop_id'];
			$this->model_module_ozon_seller->deletedExportProduct($product_id);
		}
		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_module_ozon_seller->deletedExportProduct($product_id);
			}
		}
	}

	//Список заказов Ozon
	public function order() {

		$this->load->language('module/ozon_seller');
		$this->document->setTitle($this->language->get('heading_title_order'));
		$this->load->model('module/ozon_seller');
		$this->load->model('setting/setting');
		$this->document->addScript('view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
    $this->document->addStyle('view/javascript/jquery/magnific/magnific-popup.css');

		if (isset($this->request->get['filter_posting_number'])) {
			$filter_posting_number = $this->request->get['filter_posting_number'];
		} else {
			$filter_posting_number = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_shipment_date'])) {
			$filter_shipment_date = $this->request->get['filter_shipment_date'];
		} else {
			$filter_shipment_date = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'shipment_date';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_posting_number'])) {
			$url .= '&filter_posting_number=' . urlencode(html_entity_decode($this->request->get['filter_posting_number'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_shipment_date'])) {
			$url .= '&filter_shipment_date=' . urlencode(html_entity_decode($this->request->get['filter_shipment_date'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('order_ozon'),
			'href' => $this->url->link('module/ozon_seller/order', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title_order'] = $this->language->get('heading_title_order');
		$data['order_ozon'] = $this->language->get('order_ozon');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_error'] = $this->language->get('text_error');
		$data['text_posting_number'] = $this->language->get('text_posting_number');
		$data['text_barcode'] = $this->language->get('text_barcode');
		$data['text_alert_ms_admin'] = $this->language->get('text_alert_ms_admin');
		$data['text_alert_ms_del'] = $this->language->get('text_alert_ms_del');
		$data['text_menu'] = $this->language->get('text_menu');
		$data['text_export'] = $this->language->get('text_export');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['product'] = $this->language->get('product');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_shipment_date'] = $this->language->get('entry_shipment_date');

		$data['column_status'] = $this->language->get('column_status');

		$data['acceptance_in_progress'] = $this->language->get('acceptance_in_progress');
		$data['awaiting_approve'] = $this->language->get('awaiting_approve');
		$data['awaiting_packaging'] = $this->language->get('awaiting_packaging');
		$data['awaiting_deliver'] = $this->language->get('awaiting_deliver');
		$data['arbitration'] = $this->language->get('arbitration');
		$data['cancelled'] = $this->language->get('cancelled');
		$data['client_arbitration'] = $this->language->get('client_arbitration');
		$data['delivering'] = $this->language->get('delivering');
		$data['driver_pickup'] = $this->language->get('driver_pickup');
		$data['delivered'] = $this->language->get('delivered');
		$data['not_accepted'] = $this->language->get('not_accepted');
		$data['returned'] = $this->language->get('returned');
		$data['return_fbs'] = $this->language->get('return_fbs');

		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$data['ozon_seller_cron_pass'] = $this->config->get('ozon_seller_cron_pass');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// URL
		$data['url_product'] = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'], true);
		$data['url_general'] = $this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], true);
		$data['url_export'] = $this->url->link('module/ozon_seller/export', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);

		$data['statuses'] = array(
			'awaiting_packaging' => $data['awaiting_packaging'],
			'awaiting_deliver' => $data['awaiting_deliver'],
			'awaiting_deliver' => $data['awaiting_deliver'],
			'cancelled' => $data['cancelled'],
			'delivering' => $data['delivering'],
			'delivered' => $data['delivered'],
			'return_fbs' => $data['return_fbs'],
			'returned' => $data['returned']
		);

		$filter_data = array(
			'filter_posting_number'	=> $filter_posting_number,
			'filter_shipment_date'	=> $filter_shipment_date,
			'filter_status'   			=> $filter_status,
			'sort'           				=> $sort,
			'order'           			=> $order,
			'start'           			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           			=> $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_module_ozon_seller->getTotalOrders($filter_data);

		$results = $this->model_module_ozon_seller->getOrders($filter_data);

		$data['postings'] = array();

		foreach ($results as $result) {

			if ($result['shipment_date'] != '0000-00-00 00:00:00') {
				$shipment_date = date('d-m-Y', strtotime($result['shipment_date']));
			} else {
				$shipment_date = 'FBO';
			}

			$data['postings'][] = array(
				'posting_number'  => $result['posting_number'],
				'status' 					=> $data[$result['status']],
				'shipment_date'   => $shipment_date
			);
		}

		$data['token'] = $this->session->data['token'];

		// $data['ozon_seller_cron_pass'] = $this->config->get('ozon_seller_cron_pass');

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_posting_number'])) {
			$url .= '&filter_posting_number=' . urlencode(html_entity_decode($this->request->get['filter_posting_number'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_shipment_date'])) {
			$url .= '&filter_shipment_date=' . urlencode(html_entity_decode($this->request->get['filter_shipment_date'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_posting_number'] = $this->url->link('module/ozon_seller/order', 'token=' . $this->session->data['token'] . '&sort=posting_number' . $url, true);
		$data['sort_status'] = $this->url->link('module/ozon_seller/order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);
		$data['sort_shipment_date'] = $this->url->link('module/ozon_seller/order', 'token=' . $this->session->data['token'] . '&sort=sort_shipment_date' . $url, true);
		$data['sort_order'] = $this->url->link('module/ozon_seller/order', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_posting_number'])) {
			$url .= '&filter_posting_number=' . urlencode(html_entity_decode($this->request->get['filter_posting_number'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_shipment_date'])) {
			$url .= '&filter_shipment_date=' . urlencode(html_entity_decode($this->request->get['filter_shipment_date'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('module/ozon_seller/order', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_posting_number'] = $filter_posting_number;
		$data['filter_shipment_date'] = $filter_shipment_date;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('module/ozon_seller_order.tpl', $data));

	}

	// Информация по заказу Озон
	public function viewOrder()
	{
		if (isset($this->request->post['posting'])) {

			if (isset($this->request->post['posting']['error'])) {
				$output_local = '<tr data-id="' . $this->request->get['postingnumber'] . '">';
				$output_product = $this->request->post['posting']['error']['message'];
			} else {

				$this->load->model('module/ozon_seller');
				$this->load->model('tool/image');

				$posting = $this->request->post['posting']['result'];
				$created = 'Создан: ' . date('d-m-Y H:i:s', strtotime($posting['in_process_at']));
				$city = '<br />Город: ' . $posting['analytics_data']['city'];
				$region = '<br />Регион: ' . $posting['analytics_data']['region'];
				$delivery = '<br />Доставка: ' . $posting['analytics_data']['delivery_type'];

				if ($posting['analytics_data']['is_premium'] == 'false') {
					$premium = '<br />Премиум: Нет';
				} else {
					$premium = '<br />Премиум: Да';
				}
				$status = '<br />Текущий статус: ' . $posting['status'];
				$output_local = '<tr data-id="' . $posting['posting_number'] . '"><td></td><td>' . $created . $city . $region . $delivery . $premium . $status . '</td>';

				$products = $posting['products'];
				$output_product = '';

				foreach ($products as $product) {
					$prod = $this->model_module_ozon_seller->getExportProduct($product['offer_id']);
					// if ($this->config->get('ozon_seller_entry_offer_id')) {
					// 	$prod = $this->model_module_ozon_seller->getProductByModel($product['offer_id']);
					// } else {
					// 	$prod = $this->model_module_ozon_seller->getProductBySku($product['offer_id']);
					// }

					if (empty($prod)) {
						$prod[0]['image'] = 'image/no_image.png';
					}

					if ($prod[0]['image']) {
						$thumb = $this->model_tool_image->resize($prod[0]['image'], 50, 50);
					 } else {
						$thumb = '';
					 }

					 if ($prod[0]['image']) {
			 		 	$image = $this->model_tool_image->resize($prod[0]['image'], 500, 500);
					 } else {
						$image = '';
					 }

					$output_product .= '<a class="image-popup" href="' . $image . '"><img src="' . $thumb . '"> </a>' . ' ' . $product['offer_id'] . ' - ' . $product['name'] .  ' - <b>' . $product['quantity'] . ' шт.</b> - ' .  round($product['price']) . ' руб.<br /><br />';
				}
			}
			echo $output_local . '<td colspan="3">' . $output_product . '</td></tr>';
		}
	}

	public function getAttributeRequired() {

		if (isset($this->request->get['category'])) {
			$this->load->model('module/ozon_seller');
			$category_id = htmlspecialchars($this->request->get['category']);
			$required = $this->model_module_ozon_seller->getAttributeRequired($category_id);
			echo json_encode($required);
		}
	}

	public function truncateAttribute() {
		$this->load->model('module/ozon_seller');
		$this->model_module_ozon_seller->truncateAttribute();
		echo 'OK';
	}

	public function downloadAct()
	{
		$check_download_act = glob(DIR_UPLOAD . date('Y-m-d') . '_act_ozon_seller*.pdf');
		foreach ($check_download_act as $download_act) {
			if (file_exists($download_act) && is_file($download_act)) {
				header("Content-type: application/pdf");
				@readfile($download_act);
			}
		}
	}

	// Таблица экспорта товаров
	public function export()
	{
		$this->load->language('module/ozon_seller');
		$this->document->setTitle($this->language->get('heading_title_export'));
		$this->load->model('module/ozon_seller');
		$this->load->model('setting/setting');
		$this->load->model('catalog/product');

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&type=module', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_export'),
			'href' => $this->url->link('module/ozon_seller/export', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title_export'] = $this->language->get('heading_title_export');
		$data['text_entry_category'] = $this->language->get('text_entry_category');
		$data['text_no_products'] = $this->language->get('text_no_products');
		$data['text_menu'] = $this->language->get('text_menu');
		$data['order_ozon'] = $this->language->get('order_ozon');
		$data['product'] = $this->language->get('product');
		$data['text_edit'] = $this->language->get('text_edit');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// Язык по-умолчанию
    $language_config = $this->model_setting_setting->getSetting('config', $this->config->get('config_store_id'));
    $language = $language_config['config_language'];
    $language_id = $this->model_module_ozon_seller->getLanguage($language);

		// URL
		$data['url_export'] = $this->url->link('module/ozon_seller/export&token=' . $this->session->data['token'] . '&filter_category=');
		$data['url_general'] = $this->url->link('module/ozon_seller', 'token=' . $this->session->data['token'], true);
		$data['url_order'] = $this->url->link('module/ozon_seller/order', 'token=' . $this->session->data['token'], true);
		$data['url_product'] = $this->url->link('module/ozon_seller/product', 'token=' . $this->session->data['token'], true);
		$data['url_product_export'] = HTTPS_CATALOG . 'index.php?route=module/ozon_seller/importproduct&cron_pass=' . $this->config->get('ozon_seller_cron_pass') . '&category=';

		if ($this->config->get('ozon_seller_test_export')) {
			$data['success'] = 'Включен тест экспорта. Запрос не будет отправлен в Ozon';
		}

		if (!empty($this->config->get('ozon_seller_manufacturer_stop'))) {
			$stop_manufacturer = implode(",", $this->config->get('ozon_seller_manufacturer_stop'));
		} else {
			$stop_manufacturer = 0;
		}

		if (!empty($this->request->get['filter_category']) && $this->request->get['filter_category'] != 'all') {
			$data['filter_category'] = $this->request->get['filter_category'];
		} else {
			$data['filter_category'] = '';
		}

		// Категории
		$ozon_seller_category = $this->config->get('ozon_seller_category');
		$data['categorys'][] = array('category_id' => 'all', 'name' => $data['text_entry_category']);

		// Товары
		$data['items'] = array();
		$i = 0;
		$error = '';

		if (!empty($ozon_seller_category)) {
			$ozon_seller_attribute = $this->config->get('ozon_seller_attribute');
			// сопоставленные значения справочника
			$dictionary_shop_to_ozon = $this->model_module_ozon_seller->getDictionaryShoptoOzon();

			foreach ($ozon_seller_category as $category_import) {
				if (!empty($data['filter_category'])) {
					if ($category_import['shop'] != $data['filter_category']) {
	          continue;
	        }
				}
				if (!empty($category_import['stop'])) {
					continue;
				}
				$filter_data = array(
					'filter_category_id' => $category_import['shop'],
					'filter_manufacturer_id' => $stop_manufacturer,
					'filter_sub_category'=> false,
					'start'	=> 0,
					'limit' => $this->config->get('ozon_seller_limit')
				);

				$products = $this->model_module_ozon_seller->getProductsPreLoad($filter_data);

				if (!empty($products)) {
					$shop_category_name = $this->model_module_ozon_seller->getNameShopCategory(array($category_import['shop']), $language_id);
					$ozon_category_name = $this->model_module_ozon_seller->getOzonCategory(array($category_import['ozon']));
					$data['items'][$i]['category'] = $shop_category_name[0]['name'] . ' => ' . $ozon_category_name[0]['title'];

					// Тип товара
					if (!empty($category_import['type']) && $category_import['type'] != 'attr') {
						$type = $this->model_module_ozon_seller->getDictionaryByCategoryAndAttributeId($category_import['ozon'], $category_import['type']);
					}

					// атрибуты для категории Ozon (oc_ozon_attribute)
					$category_attr = $this->model_module_ozon_seller->getOzonAttributeByCategory($category_import['ozon']);

					$p = 0;
					foreach ($products as $product) {
						//черный список
						if ($this->config->get('ozon_seller_product_blacklist')) {
							$blacklist = $this->config->get('ozon_seller_product_blacklist');
							if (in_array($product['product_id'], $blacklist)){
								continue;
							}
						}
						//$offer_id
						$offer_id = $product[$this->config->get('ozon_seller_entry_offer_id')];
						if (empty($offer_id)) {
							$offer_id = 'error';
						}
						// Размеры
						if ($product['weight_class_id'] == $this->config->get('ozon_seller_weight')) {
							if ($product['weight'] == 0) {
								$weight = $category_import['weight'];
							} else {
								$weight = $product['weight'];
							}
						} else {
							if ($product['weight'] == 0) {
								$weight = $category_import['weight'];
							} else {
								$weight = $product['weight'] * 1000;
							}
						}
						$price_weight = $weight / 1000;

						if ($product['length_class_id'] == $this->config->get('ozon_seller_length')) {
							if ($product['length'] == 0) {
								$length = $category_import['length'];
							} else {
								$length = $product['length'];
							}
							if ($product['width'] == 0) {
								$width = $category_import['width'];
							} else {
								$width = $product['width'];
							}
							if ($product['height'] == 0) {
								$height = $category_import['height'];
							} else {
								$height = $product['height'];
							}
						} else {
							if ($product['length'] == 0) {
								$length = $category_import['length'];
							} else {
								$length = $product['length'] / 10;
							}
							if ($product['width'] == 0) {
								$width = $category_import['width'];
							} else {
								$width = $product['width'] / 10;
							}
							if ($product['height'] == 0) {
								$height = $category_import['height'];
							} else {
								$height = $product['height'] / 10;
							}
						}

						// Цена
						if ($product['special']) {
							$price = $product['special'];
						} else {
							$price = $product['price'];
						}
						if (!empty($this->config->get('ozon_seller_min_price')) && $price < $this->config->get('ozon_seller_min_price')) {
							continue;
						}
						$price = $this->price($price, $product['manufacturer_id'], $product['category_id'], $product['product_id'], $length, $width, $height, $price_weight);

						// Бренд
						$ozon_manufacturer = $this->model_module_ozon_seller->getManufacturer($product['manufacturer_id']);
						if (!empty($ozon_manufacturer)) {
							$manufacturer = $ozon_manufacturer[0]['value'];
						} else {
							$manufacturer = $product['manufacturer'];
						}

						$name = str_replace(array('«', '»', '"', '°', '\''), '', $product['name']);
						$name = '<a href="' . $this->url->link('catalog/product/edit&product_id=' . $product['product_id'], 'token=' . $this->session->data['token'], 'SSL') . '" target="_blank">' . htmlspecialchars_decode($name) . '</a>';

						$data['items'][$i]['products'][$p]['Наименование'] = $name;
						$data['items'][$i]['products'][$p]['Артикул Озон'] = $offer_id;
						$data['items'][$i]['products'][$p]['Бренд'] = $manufacturer;
						$data['items'][$i]['products'][$p]['Цена'] = $price;
						$data['items'][$i]['products'][$p]['НДС'] = $this->config->get('ozon_seller_nds');
						$data['items'][$i]['products'][$p]['ШК'] = $product['ean'];
						$data['items'][$i]['products'][$p]['Вес с уп.'] = (int)ceil($weight);
						$data['items'][$i]['products'][$p]['Вес(гр)'] = (int)ceil($weight);
						$data['items'][$i]['products'][$p]['Д(см)'] = (int)ceil($length);
						$data['items'][$i]['products'][$p]['Ш(см)'] = (int)ceil($width);
						$data['items'][$i]['products'][$p]['В(см)'] = (int)ceil($height);

						// значения атрибутов у товара в магазине
						$dictionary_shop = $this->model_module_ozon_seller->getShopDictionaryPreLoad($product['product_id'], $language_id);

						// итерация всех атрибутов категории Озон для соблюдения последовательности в таблице
						foreach ($category_attr as $categ_attr) {
							if (!empty($categ_attr['required'])) {
								$data['items'][$i]['products'][$p][$categ_attr['ozon_attribute_name']] = 'error';
							} else {
								$data['items'][$i]['products'][$p][$categ_attr['ozon_attribute_name']] = '';
							}
							// Тип товара соответствует всей категории
							if ($categ_attr['ozon_attribute_id'] == 8229 && $category_import['type'] != 'attr') {
								if (!empty($type)) {
									$data['items'][$i]['products'][$p][$categ_attr['ozon_attribute_name']] = $type['text'];
								}
								continue;
							}

							if (!empty($dictionary_shop)) {
								foreach ($dictionary_shop as $dictionar_shop) {
									foreach ($dictionary_shop_to_ozon as $dictionar_shop_to_ozon) {
										// соблюдение последовательности в таблице
										if ($categ_attr['ozon_attribute_id'] == $dictionar_shop_to_ozon['ozon_attribute_id']) {
											// Тип товара соответствует атрибутам
											if ($dictionar_shop_to_ozon['ozon_attribute_id'] == 8229 && $category_import['type'] == 'attr') {
												$text_shop_attr = $dictionar_shop_to_ozon['text_shop_attribute'];
												$find_delimiter = strpos($text_shop_attr, '++');
												if ($find_delimiter === false) {
													$text_shop_attr = array($text_shop_attr);
												} else {
													$text_shop_attr = explode('++', $text_shop_attr);
												}
												if (in_array($dictionar_shop['text'], $text_shop_attr)) {
													// Проверить, что сопоставленный атрибут типа товара принадлежит категории Озон
													$type_attr = $this->model_module_ozon_seller->getDictionaryByCategoryAndAttributeId($category_import['ozon'], $dictionar_shop_to_ozon['dictionary_value_id']);
													if ($type_attr['attribute_value_id'] != $dictionar_shop_to_ozon['dictionary_value_id']) {
														unset($data['items'][$i]['products'][$p]);
														continue 4;
													}

													$data['items'][$i]['products'][$p][$categ_attr['ozon_attribute_name']] = $dictionar_shop_to_ozon['value'];
												}
												continue;
											}

											// Добавляем в выгрузку сопоставленные атрибуты магазина со справочником Ozon из таблицы ozon_to_shop_dictionary
											if ($dictionar_shop['attribute_id'] == $dictionar_shop_to_ozon['shop_attribute_id']) {
												$translit_shop = $this->translit($dictionar_shop['text']);
												$translit_ozon = $this->translit($dictionar_shop_to_ozon['text_shop_attribute']);
												if (strcasecmp($translit_shop, $translit_ozon) == 0) {
													$data['items'][$i]['products'][$p][$categ_attr['ozon_attribute_name']] =  $dictionar_shop_to_ozon['value'];
												}
											}
											continue;
										}
									}
									// Добавляем в выгрузку сопоставленные атрибуты магазина без справочника
									if (empty($categ_attr['ozon_dictionary_id'])) {
										$attributes_ozon_to_shop = array_keys($ozon_seller_attribute, $dictionar_shop['attribute_id']);
										if (in_array($categ_attr['ozon_attribute_id'], $attributes_ozon_to_shop)) {
											$val = str_replace(array('mm', 'мм', 'см', 'кг'), '', $dictionar_shop['text']);
											$data['items'][$i]['products'][$p][$categ_attr['ozon_attribute_name']] = $val;
										}
									}
								}
							}
							//если в админке модуля присвоили атрибутам поля OpenCart
							$oc_input = array('sku', 'model', 'mpn', 'isbn', 'ean', 'jan', 'upc');
							foreach ($ozon_seller_attribute as $k => $ozon_attr_seller) {
								// соблюдение последовательности в таблице
								if ($categ_attr['ozon_attribute_id'] == $k) {
									if (in_array($ozon_attr_seller, $oc_input)) {
										$value = $product[$ozon_attr_seller];
										$data['items'][$i]['products'][$p][$categ_attr['ozon_attribute_name']] = $value;
									}
								}
							}
						}
						if (in_array('error', $data['items'][$i]['products'][$p])) {
							$error = true;
						}
						++$p;
					} // <-- конец итерации массива товаров
					if (empty($data['items'][$i]['products'])) {
						unset($data['items'][$i]);
						continue;
					}
					// добавим категорию в выпадающий список
					$data['categorys'][] = array(
						'category_id' => $shop_category_name[0]['category_id'],
						'name' => $shop_category_name[0]['name']
					);
				}
				++$i;
			} // <-- конец итерации массива категорий
			if (empty($error)) {
				$data['btn_export'] = '<button type="button" class="btn btn-success btn-export">Выгрузить в Озон</button>';
			} else {
				$data['btn_export'] = '<button type="button" class="btn btn-success" disabled>Экспорт не доступен</button>';
				$data['error'] = 'Есть товары с незаполненными обязательными атрибутами';
			}
		}
		$this->response->setOutput($this->load->view('module/ozon_seller_export.tpl', $data));
	}

	// Расчет цены на товар
	private function price($price, $manufacturer_id, $category_id, $product_id, $length, $width, $height, $weight)
	{
		// Дополнительные наценки
		if (!empty($this->config->get('ozon_seller_prices'))) {
			$new_price = 0;
			foreach ($this->config->get('ozon_seller_prices') as $prices) {
				$values = explode('-', $prices['value']);
				if ($prices['els'] == 'price' && !empty($values[1])) {
					if ($price >= $values[0] && $price <= $values[1]) {
						$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
					}
				} elseif ($prices['els'] == 'price' && empty($values[1])) {
					if ($price = $values[0]) {
						$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
					}
				} elseif ($prices['els'] == 'manufacturer_id' && $manufacturer_id == $values[0]) {
					$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
				} elseif ($prices['els'] == 'category_id' && $category_id == $values[0]) {
					$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
				} elseif ($prices['els'] == 'product_id' && $product_id == $values[0]) {
					$new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
				}
			}
			$price += $new_price;
		}
		// Магистраль
		if (!empty($this->config->get('ozon_seller_highway'))) {
			$volume_weight = (int)ceil($length) * (int)ceil($width) * (int)ceil($height) / 5000;
			if ($weight > $volume_weight) {
				$volume_weight = $weight;
			}
			$highway = $volume_weight * $this->config->get('ozon_seller_highway');
			if ($highway < 8 ) {
				$highway = 8;
			} else if ($highway > 700) {
				$highway = 700;
			}
			$price += $highway;
		}
		// Фикс
		if ($this->config->get('ozon_seller_ruble')) {
			$price += $this->config->get('ozon_seller_ruble');
		}
		// Последняя миля
		if ($this->config->get('ozon_seller_last_mile')) {
			$last_mile = $price * 5 / 100;
			if ($last_mile < 60) {
				$last_mile = 60;
			} else if ($last_mile > 350) {
				$last_mile = 350;
			}
			if ($this->config->get('ozon_seller_min_last_mile') && $price < $this->config->get('ozon_seller_min_last_mile')) {
				$price += $last_mile;
			}
			if (empty($this->config->get('ozon_seller_min_last_mile'))) {
				$price += $last_mile;
			}
		}
		// Процент
		if ($this->config->get('ozon_seller_percent')) {
			$percent = $this->config->get('ozon_seller_percent');
			$price_up = $price * $percent / 100;
			$price += $price_up;
		}
		// Округление цен
		switch ($this->config->get('ozon_seller_price_round')) {
			case 'st':
				$price = round($price, -1);
				break;
			case 'ten':
				$price = ceil($price / 10) * 10;
				break;
			case 'st_so':
				$price = round($price, -2);
				break;
			case 'so':
				$price = ceil($price / 100) * 100;
				break;
			case 'fifty':
				$price = ceil($price / 50) * 50;
				break;
			default:
				$price;
				break;
		}
		return $price;
	}

	// Действие с наценкой
	private function actionPrice($action, $price, $rate)
	{
		switch ($action) {
			case '*':
				$action_price = $price * $rate;
				$action_price = $action_price - $price;
				break;
			case '-':
				$action_price = $price - $rate;
				$action_price = $price - $action_price;
				break;
			case '+':
				$action_price = $price + $rate;
				$action_price = $action_price - $price;
				break;
			default:
				false;
				break;
		}
		return $action_price;
	}

	private function translit($str)
	{
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
  }

	protected function validate()
	{
		if (!$this->user->hasPermission('modify', 'module/ozon_seller')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['ozon_seller_client_id']) {
			$this->error['ozon_seller_client_id'] = $this->language->get('error_client_id');
		}
		if (!$this->request->post['ozon_seller_api_key']) {
			$this->error['ozon_seller_api_key'] = $this->language->get('error_api_key');
		}
		if (!$this->request->post['ozon_seller_cron_pass']) {
			$this->error['ozon_seller_cron_pass'] = $this->language->get('error_cron_pass');
		}
		$this->checkUpdate();
		return !$this->error;
	}

	public function install() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('ozon_seller', array('ozon_seller_status' => 0));
		$this->load->model('module/ozon_seller');
		$this->model_module_ozon_seller->install();
	}

	public function uninstall() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('ozon_seller', array('ozon_seller_status' => 0));
		$this->load->model('module/ozon_seller');
		$this->model_module_ozon_seller->uninstall();
	}

	private function checkUpdate()
  {
    $url = 'https://shop.cdl-it.ru/index.php?route=extension/module/cdl_license/pass&pass=dmitricheremisin&ver=' . urlencode($this->config->get('ozon_seller_version')) . '&domain=' . HTTPS_SERVER . '&mod=50';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = @json_decode($response, true);
    return $response;
  }
}
