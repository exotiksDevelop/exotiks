<?php
/*
* OpenCart 2.1
* @author Dmitri Cheremisin
*/
class ControllerModuleCdlWildberries extends Controller
{
  private $error = array();
  public function index()
  {
    $this->load->language('module/cdl_wildberries');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
    $this->load->model('module/cdl_wildberries');

		if (($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate())) {
			$this->model_setting_setting->editSetting('cdl_wildberries', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'] . '&type=module', true));
		}
    // UPDATE 150 ++
        $check_rid_update = $this->model_module_cdl_wildberries->checkCdlRid();
        foreach ($check_rid_update as $check_rid) {
          if ($check_rid['COLUMN_NAME'] == 'rid') {
            $check_rid_true = true;
          }
        }
        if (!isset($check_rid_true)) {
          $this->model_module_cdl_wildberries->updateCdlRid();
        }
    // UPDATE 150 --

    // UPDATE 140 ++
    $check_colomn = $this->model_module_cdl_wildberries->checkCdl();
    foreach ($check_colomn as $colomn) {
      if ($colomn['COLUMN_NAME'] == 'shop_category') {
        $check = true;
      }
    }
    if (!isset($check)) {
      $this->model_module_cdl_wildberries->updateCdl();
      if (!empty($this->config->get('cdl_wildberries_category'))) {
        foreach ($this->config->get('cdl_wildberries_category') as $category_update) {
          $this->model_module_cdl_wildberries->updateCdlAttr($category_update['wb'], $category_update['shop']);
          $update_attributes_to_shop = $this->model_module_cdl_wildberries->getAttributesToShop($category_update['wb'], $category_update['shop']);
          if (!empty($update_attributes_to_shop)) {
            foreach ($update_attributes_to_shop as $update_attribut) {
              if (!empty($update_attribut['shop_id'])) {
                $update_dictionary_to_shop = $this->model_module_cdl_wildberries->updateCdlDToS($category_update['wb'], $category_update['shop'], $update_attribut['shop_id']);
                if (!empty($update_dictionary_to_shop[0]['shop_attribute_id']) && $update_attribut['shop_id'] == $update_dictionary_to_shop[0]['shop_attribute_id']) {
                  $this->model_module_cdl_wildberries->updateCdlTsd($category_update['wb'], $category_update['shop'], $update_attribut['shop_id'], $update_attribut['type']);
                }
              }
            }
          }
        }
        $this->model_module_cdl_wildberries->updateCdlDelNull();
      }
    }
// UPDATE 140 --

// UPDATE 160 ++
    $check_stat_update = $this->model_module_cdl_wildberries->checkStat();
    foreach ($check_stat_update as $check_stat) {
      if ($check_stat['COLUMN_NAME'] == 'stat') {
        $check_stat_true = true;
      }
    }
    if (!isset($check_stat_true)) {
      $this->model_module_cdl_wildberries->updateCdlStat();
    }
// UPDATE 160 --
    // Тексты
    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_edit'] = $this->language->get('text_edit');
    $data['text_extension'] = $this->language->get('text_extension');
    $data['tab_general'] = $this->language->get('tab_general');
    $data['tab_category'] = $this->language->get('tab_category');
    $data['tab_manufacturer'] = $this->language->get('tab_manufacturer');
    $data['tab_export'] = $this->language->get('tab_export');
    $data['tab_price'] = $this->language->get('tab_price');
    $data['tab_warehouses'] = $this->language->get('tab_warehouses');
    $data['tab_ms'] = $this->language->get('tab_ms');
    $data['tab_order'] = $this->language->get('tab_order');
    $data['tab_url'] = $this->language->get('tab_url');
    $data['tab_about'] = $this->language->get('tab_about');
    $data['text_general_token'] = $this->language->get('text_general_token');
    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    $data['text_pass'] = $this->language->get('text_pass');
    $data['text_relations'] = $this->language->get('text_relations');
    $data['entry_g'] = $this->language->get('entry_g');
    $data['entry_sm'] = $this->language->get('entry_sm');
    $data['text_log'] = $this->language->get('text_log');
    $data['category_shop'] = $this->language->get('category_shop');
    $data['category_wb'] = $this->language->get('category_wb');
    $data['button_add'] = $this->language->get('button_add');
    $data['button_remove'] = $this->language->get('button_remove');
    $data['download_category'] = $this->language->get('download_category');
    $data['button_download'] = $this->language->get('button_download');
    $data['text_length'] = $this->language->get('text_length');
		$data['text_width'] = $this->language->get('text_width');
		$data['text_height'] = $this->language->get('text_height');
		$data['text_weight'] = $this->language->get('text_weight');
		$data['text_stop_export'] = $this->language->get('text_stop_export');
    $data['button_remove'] = $this->language->get('button_remove');
    $data['text_rima'] = $this->language->get('text_rima');
    $data['text_attributes'] = $this->language->get('text_attributes');
    $data['manufacturer_stop'] = $this->language->get('manufacturer_stop');
    $data['text_select_all'] = $this->language->get('text_select_all');
    $data['text_unselect_all'] = $this->language->get('text_unselect_all');
    $data['text_manufacturer'] = $this->language->get('text_manufacturer');
    $data['text_url_price'] = $this->language->get('text_url_price');
    $data['text_test'] = $this->language->get('text_test');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_filter'] = $this->language->get('text_filter');
    $data['text_description'] = $this->language->get('text_description');
    $data['text_barcode'] = $this->language->get('text_barcode');
    $data['text_product'] = $this->language->get('text_product');
    $data['text_delimiter'] = $this->language->get('text_delimiter');
    $data['text_menu'] = $this->language->get('text_menu');
    $data['text_product_blacklist'] = $this->language->get('text_product_blacklist');
    $data['text_percentage'] = $this->language->get('text_percentage');
    $data['text_price_round'] = $this->language->get('text_price_round');
    $data['text_entry_warehouse'] = $this->language->get('text_entry_warehouse');
    $data['text_reload_input'] = $this->language->get('text_reload_input');
    $data['text_dont_add'] = $this->language->get('text_dont_add');
    $data['text_name'] = $this->language->get('text_name');
    $data['text_stocks_null'] = $this->language->get('text_stocks_null');
    $data['text_stok_min'] = $this->language->get('text_stok_min');
    $data['text_price_sklad'] = $this->language->get('text_price_sklad');
    $data['text_ot'] = $this->language->get('text_ot');
    $data['text_do'] = $this->language->get('text_do');
    $data['text_weight_sklad'] = $this->language->get('text_weight_sklad');
    $data['text_sklad_info'] = $this->language->get('text_sklad_info');
    $data['text_white_list'] = $this->language->get('text_white_list');
    $data['text_delete_sklad'] = $this->language->get('text_delete_sklad');
    $data['text_url_stock'] = $this->language->get('text_url_stock');
    $data['text_log'] = $this->language->get('text_log');
    $data['text_ms_status'] = $this->language->get('text_ms_status');
    $data['text_login_ms'] = $this->language->get('text_login_ms');
    $data['text_key_ms'] = $this->language->get('text_key_ms');
    $data['text_organization_ms'] = $this->language->get('text_organization_ms');
    $data['text_agent_ms'] = $this->language->get('text_agent_ms');
    $data['text_project_ms'] = $this->language->get('text_project_ms');
    $data['text_store_ms'] = $this->language->get('text_store_ms');
    $data['text_status_order_ms'] = $this->language->get('text_status_order_ms');
    $data['text_status_new_order_ms'] = $this->language->get('text_status_new_order_ms');
    $data['text_status_packing'] = $this->language->get('text_status_packing');
    $data['text_status_print'] = $this->language->get('text_status_print');
    $data['text_status_delivering'] = $this->language->get('text_status_delivering');
    $data['text_status_cancelled'] = $this->language->get('text_status_cancelled');
    $data['text_status_return'] = $this->language->get('text_status_return');
    $data['text_status_delivered'] = $this->language->get('text_status_delivered');
    $data['text_metadata_attributes'] = $this->language->get('text_metadata_attributes');
    $data['text_sticker_id_ms'] = $this->language->get('text_sticker_id_ms');
    $data['text_payment_ms'] = $this->language->get('text_payment_ms');
    $data['text_connect_prod'] = $this->language->get('text_connect_prod');
    $data['text_oc'] = $this->language->get('text_oc');
    $data['text_moysklad'] = $this->language->get('text_moysklad');
    $data['text_webhook'] = $this->language->get('text_webhook');
    $data['text_webhook_create'] = $this->language->get('text_webhook_create');
    $data['text_webhook_delete'] = $this->language->get('text_webhook_delete');
    $data['text_url_orders'] = $this->language->get('text_url_orders');
    $data['text_time'] = $this->language->get('text_time');
    $data['text_work_time'] = $this->language->get('text_work_time');
    $data['text_blacklist_category'] = $this->language->get('text_blacklist_category');
    $data['text_orders_wb'] = $this->language->get('text_orders_wb');
    $data['text_status_order_oc'] = $this->language->get('text_status_order_oc');
    $data['new'] = $this->language->get('new');
    $data['awaiting_packaging'] = $this->language->get('awaiting_packaging');
    $data['cancelled'] = $this->language->get('cancelled');
    $data['return'] = $this->language->get('return');
    $data['packaging'] = $this->language->get('packaging');
    $data['delivering'] = $this->language->get('delivering');
    $data['delivered'] = $this->language->get('delivered');
    $data['text_email_order'] = $this->language->get('text_email_order');
    $data['text_brendlist'] = $this->language->get('text_brendlist');
    $data['text_go_wb'] = $this->language->get('text_go_wb');
    $data['text_value'] = $this->language->get('text_value');
    $data['text_rate'] = $this->language->get('text_rate');
    $data['text_action'] = $this->language->get('text_action');
    $data['text_pass_supplies'] = $this->language->get('text_pass_supplies');
    $data['text_supplies'] = $this->language->get('text_supplies');
    $data['text_export_stock_null'] = $this->language->get('text_export_stock_null');
    $data['text_no_price_update'] = $this->language->get('text_no_price_update');
    $data['text_product_price_oc'] = $this->language->get('text_product_price_oc');
    $data['text_category_price'] = $this->language->get('text_category_price');
    $data['text_category_wb'] = $this->language->get('text_category_wb');
    $data['text_price_range'] = $this->language->get('text_price_range');
    $data['btn_no_category_save'] = $this->language->get('btn_no_category_save');
    $data['text_api_statistics_key'] = $this->language->get('text_api_statistics_key');
    $data['text_payment_date'] = $this->language->get('text_payment_date');
    $data['text_return_store_ms'] = $this->language->get('text_return_store_ms');
    $data['text_auto_return'] = $this->language->get('text_auto_return');
    $data['status_payment'] = $this->language->get('status_payment');
    $data['text_statistics'] = $this->language->get('text_statistics');
    $data['text_author'] = $this->language->get('text_author');
		$data['author'] = $this->language->get('author');
		$data['text_author_email'] = $this->language->get('text_author_email');
		$data['author_email'] = $this->language->get('author_email');
		$data['text_doc_api_wb'] = $this->language->get('text_doc_api_wb');
		$data['doc_api_wb'] = $this->language->get('doc_api_wb');
		$data['text_doc_api_ms'] = $this->language->get('text_doc_api_ms');
		$data['doc_api_ms'] = $this->language->get('doc_api_ms');
    $data['text_attributes_description'] = $this->language->get('text_attributes_description');
    $data['text_export_category'] = $this->language->get('text_export_category');
    $data['text_firstname_customer'] = $this->language->get('text_firstname_customer');
    $data['text_lastname_customer'] = $this->language->get('text_lastname_customer');
    $data['text_update_cdl'] = $this->language->get('text_update_cdl');
    $data['text_url_update_cdl'] = $this->language->get('text_url_update_cdl');
    $data['text_name_crop'] = $this->language->get('text_name_crop');
    $data['status_discrepancy'] = $this->language->get('status_discrepancy');
    $data['text_sklad_attribute'] = $this->language->get('text_sklad_attribute');
    $data['text_val'] = $this->language->get('text_val');
    $data['text_start_typing'] = $this->language->get('text_start_typing');
    $data['text_card_version'] = $this->language->get('text_card_version');
    $data['text_check'] = $this->language->get('text_check');

    // Help
    $data['help_download_category'] = $this->language->get('help_download_category');
    $data['help_default_size'] = $this->language->get('help_default_size');
    $data['help_delimiter'] = $this->language->get('help_delimiter');
    $data['help_product_blacklist'] = $this->language->get('help_product_blacklist');
    $data['help_barcode'] = $this->language->get('help_barcode');
    $data['help_log'] = $this->language->get('help_log');
    $data['help_id_ms'] = $this->language->get('help_id_ms');
    $data['help_webhook'] = $this->language->get('help_webhook');
    $data['help_lastname'] = $this->language->get('help_lastname');
    $data['help_soglasie'] = $this->language->get('help_soglasie');

    //Крошки
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
    $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], 'SSL')
		);

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

    // Запросы
		if (isset($this->request->post['cdl_wildberries_general_token'])) {
			$data['cdl_wildberries_general_token'] = $this->request->post['cdl_wildberries_general_token'];
		} else {
			$data['cdl_wildberries_general_token'] = $this->config->get('cdl_wildberries_general_token');
		}
		if (isset($this->request->post['cdl_wildberries_api_statistics_key'])) {
			$data['cdl_wildberries_api_statistics_key'] = $this->request->post['cdl_wildberries_api_statistics_key'];
		} else {
			$data['cdl_wildberries_api_statistics_key'] = $this->config->get('cdl_wildberries_api_statistics_key');
		}
		if (isset($this->request->post['cdl_wildberries_pass'])) {
			$data['cdl_wildberries_pass'] = $this->request->post['cdl_wildberries_pass'];
		} else {
			$data['cdl_wildberries_pass'] = $this->config->get('cdl_wildberries_pass');
		}
		if (isset($this->request->post['cdl_wildberries_relations'])) {
			$data['cdl_wildberries_relations'] = $this->request->post['cdl_wildberries_relations'];
		} else {
			$data['cdl_wildberries_relations'] = $this->config->get('cdl_wildberries_relations');
		}
		if (isset($this->request->post['cdl_wildberries_category'])) {
			$data['cdl_wildberries_category'] = $this->request->post['cdl_wildberries_category'];
		} else {
			$data['cdl_wildberries_category'] = $this->config->get('cdl_wildberries_category');
		}
    if (isset($this->request->post['cdl_wildberries_manufacturer_stop'])) {
			$data['cdl_wildberries_manufacturer_stop'] = $this->request->post['cdl_wildberries_manufacturer_stop'];
		} else if (empty($this->config->get('cdl_wildberries_manufacturer_stop'))){
			$data['cdl_wildberries_manufacturer_stop'] = array();
		} else {
			$data['cdl_wildberries_manufacturer_stop'] = $this->config->get('cdl_wildberries_manufacturer_stop');
    }
    if (isset($this->request->post['cdl_wildberries_test_export'])) {
			$data['cdl_wildberries_test_export'] = $this->request->post['cdl_wildberries_test_export'];
		} else {
			$data['cdl_wildberries_test_export'] = $this->config->get('cdl_wildberries_test_export');
		}
    if (isset($this->request->post['cdl_wildberries_description'])) {
			$data['cdl_wildberries_description'] = $this->request->post['cdl_wildberries_description'];
		} else {
			$data['cdl_wildberries_description'] = $this->config->get('cdl_wildberries_description');
		}
    if (isset($this->request->post['cdl_wildberries_version'])) {
			$data['cdl_wildberries_version'] = $this->request->post['cdl_wildberries_version'];
		} else {
			$data['cdl_wildberries_version'] = $this->config->get('cdl_wildberries_version');
		}
    if (isset($this->request->post['cdl_wildberries_barcode'])) {
			$data['cdl_wildberries_barcode'] = $this->request->post['cdl_wildberries_barcode'];
		} else {
			$data['cdl_wildberries_barcode'] = $this->config->get('cdl_wildberries_barcode');
		}
    if (isset($this->request->post['cdl_wildberries_delimiter'])) {
			$data['cdl_wildberries_delimiter'] = $this->request->post['cdl_wildberries_delimiter'];
		} else {
			$data['cdl_wildberries_delimiter'] = $this->config->get('cdl_wildberries_delimiter');
		}
    if (isset($this->request->post['cdl_wildberries_blacklist'])) {
			$data['cdl_wildberries_blacklist'] = $this->request->post['cdl_wildberries_blacklist'];
		} elseif (!empty($this->config->get('cdl_wildberries_blacklist'))) {
			$data['cdl_wildberries_blacklist'] = $this->config->get('cdl_wildberries_blacklist');
		} else {
			$data['cdl_wildberries_blacklist'] = array();
		}
    if (isset($this->request->post['cdl_wildberries_prices'])) {
			$data['cdl_wildberries_prices'] = $this->request->post['cdl_wildberries_prices'];
		} else {
			$data['cdl_wildberries_prices'] = $this->config->get('cdl_wildberries_prices');
		}
    if (isset($this->request->post['cdl_wildberries_price_round'])) {
			$data['cdl_wildberries_price_round'] = $this->request->post['cdl_wildberries_price_round'];
		} else {
			$data['cdl_wildberries_price_round'] = $this->config->get('cdl_wildberries_price_round');
		}
    if (isset($this->request->post['cdl_wildberries_warehouses'])) {
			$data['cdl_wildberries_warehouses'] = $this->request->post['cdl_wildberries_warehouses'];
		} else {
			$data['cdl_wildberries_warehouses'] = $this->config->get('cdl_wildberries_warehouses');
		}
    if (isset($this->request->post['cdl_wildberries_log'])) {
			$data['cdl_wildberries_log'] = $this->request->post['cdl_wildberries_log'];
		} else {
			$data['cdl_wildberries_log'] = $this->config->get('cdl_wildberries_log');
		}
    if (isset($this->request->post['cdl_wildberries_ms_status'])) {
			$data['cdl_wildberries_ms_status'] = $this->request->post['cdl_wildberries_ms_status'];
		} else {
			$data['cdl_wildberries_ms_status'] = $this->config->get('cdl_wildberries_ms_status');
		}
    if (isset($this->request->post['cdl_wildberries_login_ms'])) {
			$data['cdl_wildberries_login_ms'] = $this->request->post['cdl_wildberries_login_ms'];
		} else {
			$data['cdl_wildberries_login_ms'] = $this->config->get('cdl_wildberries_login_ms');
		}
    if (isset($this->request->post['cdl_wildberries_key_ms'])) {
			$data['cdl_wildberries_key_ms'] = $this->request->post['cdl_wildberries_key_ms'];
		} else {
			$data['cdl_wildberries_key_ms'] = $this->config->get('cdl_wildberries_key_ms');
		}
    if (isset($this->request->post['cdl_wildberries_organization_ms'])) {
			$data['cdl_wildberries_organization_ms'] = $this->request->post['cdl_wildberries_organization_ms'];
		} else {
			$data['cdl_wildberries_organization_ms'] = $this->config->get('cdl_wildberries_organization_ms');
		}
    if (isset($this->request->post['cdl_wildberries_project_ms'])) {
			$data['cdl_wildberries_project_ms'] = $this->request->post['cdl_wildberries_project_ms'];
		} else {
			$data['cdl_wildberries_project_ms'] = $this->config->get('cdl_wildberries_project_ms');
		}
    if (isset($this->request->post['cdl_wildberries_agent_ms'])) {
			$data['cdl_wildberries_agent_ms'] = $this->request->post['cdl_wildberries_agent_ms'];
		} else {
			$data['cdl_wildberries_agent_ms'] = $this->config->get('cdl_wildberries_agent_ms');
		}
    if (isset($this->request->post['cdl_wildberries_store_ms'])) {
			$data['cdl_wildberries_store_ms'] = $this->request->post['cdl_wildberries_store_ms'];
		} else {
			$data['cdl_wildberries_store_ms'] = $this->config->get('cdl_wildberries_store_ms');
		}
    if (isset($this->request->post['cdl_wildberries_status_new_order_ms'])) {
			$data['cdl_wildberries_status_new_order_ms'] = $this->request->post['cdl_wildberries_status_new_order_ms'];
		} else {
			$data['cdl_wildberries_status_new_order_ms'] = $this->config->get('cdl_wildberries_status_new_order_ms');
		}
    if (isset($this->request->post['cdl_wildberries_status_packing_ms'])) {
			$data['cdl_wildberries_status_packing_ms'] = $this->request->post['cdl_wildberries_status_packing_ms'];
		} else {
			$data['cdl_wildberries_status_packing_ms'] = $this->config->get('cdl_wildberries_status_packing_ms');
		}
    if (isset($this->request->post['cdl_wildberries_status_print_ms'])) {
			$data['cdl_wildberries_status_print_ms'] = $this->request->post['cdl_wildberries_status_print_ms'];
		} else {
			$data['cdl_wildberries_status_print_ms'] = $this->config->get('cdl_wildberries_status_print_ms');
		}
    if (isset($this->request->post['cdl_wildberries_status_delivering_ms'])) {
			$data['cdl_wildberries_status_delivering_ms'] = $this->request->post['cdl_wildberries_status_delivering_ms'];
		} else {
			$data['cdl_wildberries_status_delivering_ms'] = $this->config->get('cdl_wildberries_status_delivering_ms');
		}
    if (isset($this->request->post['cdl_wildberries_status_cancelled_ms'])) {
			$data['cdl_wildberries_status_cancelled_ms'] = $this->request->post['cdl_wildberries_status_cancelled_ms'];
		} else {
			$data['cdl_wildberries_status_cancelled_ms'] = $this->config->get('cdl_wildberries_status_cancelled_ms');
		}
    if (isset($this->request->post['cdl_wildberries_status_return_ms'])) {
			$data['cdl_wildberries_status_return_ms'] = $this->request->post['cdl_wildberries_status_return_ms'];
		} else {
			$data['cdl_wildberries_status_return_ms'] = $this->config->get('cdl_wildberries_status_return_ms');
		}
    if (isset($this->request->post['cdl_wildberries_status_delivered_ms'])) {
			$data['cdl_wildberries_status_delivered_ms'] = $this->request->post['cdl_wildberries_status_delivered_ms'];
		} else {
			$data['cdl_wildberries_status_delivered_ms'] = $this->config->get('cdl_wildberries_status_delivered_ms');
		}
    if (isset($this->request->post['cdl_wildberries_sticker_id_ms'])) {
			$data['cdl_wildberries_sticker_id_ms'] = $this->request->post['cdl_wildberries_sticker_id_ms'];
		} else {
			$data['cdl_wildberries_sticker_id_ms'] = $this->config->get('cdl_wildberries_sticker_id_ms');
		}
    if (isset($this->request->post['cdl_wildberries_payment_date'])) {
			$data['cdl_wildberries_payment_date'] = $this->request->post['cdl_wildberries_payment_date'];
		} else {
			$data['cdl_wildberries_payment_date'] = $this->config->get('cdl_wildberries_payment_date');
		}
    if (isset($this->request->post['cdl_wildberries_connect_prod_shop'])) {
			$data['cdl_wildberries_connect_prod_shop'] = $this->request->post['cdl_wildberries_connect_prod_shop'];
		} else {
			$data['cdl_wildberries_connect_prod_shop'] = $this->config->get('cdl_wildberries_connect_prod_shop');
		}
    if (isset($this->request->post['cdl_wildberries_connect_prod_ms'])) {
			$data['cdl_wildberries_connect_prod_ms'] = $this->request->post['cdl_wildberries_connect_prod_ms'];
		} else {
			$data['cdl_wildberries_connect_prod_ms'] = $this->config->get('cdl_wildberries_connect_prod_ms');
		}
    if (isset($this->request->post['cdl_wildberries_order_oc'])) {
			$data['cdl_wildberries_order_oc'] = $this->request->post['cdl_wildberries_order_oc'];
		} else {
			$data['cdl_wildberries_order_oc'] = $this->config->get('cdl_wildberries_order_oc');
		}
    if (isset($this->request->post['cdl_wildberries_status_new_oc'])) {
			$data['cdl_wildberries_status_new_oc'] = $this->request->post['cdl_wildberries_status_new_oc'];
		} else {
			$data['cdl_wildberries_status_new_oc'] = $this->config->get('cdl_wildberries_status_new_oc');
		}
    if (isset($this->request->post['cdl_wildberries_awaiting_packaging_oc'])) {
			$data['cdl_wildberries_awaiting_packaging_oc'] = $this->request->post['cdl_wildberries_awaiting_packaging_oc'];
		} else {
			$data['cdl_wildberries_awaiting_packaging_oc'] = $this->config->get('cdl_wildberries_awaiting_packaging_oc');
		}
    if (isset($this->request->post['cdl_wildberries_cancelled_oc'])) {
			$data['cdl_wildberries_cancelled_oc'] = $this->request->post['cdl_wildberries_cancelled_oc'];
		} else {
			$data['cdl_wildberries_cancelled_oc'] = $this->config->get('cdl_wildberries_cancelled_oc');
		}
    if (isset($this->request->post['cdl_wildberries_return_oc'])) {
			$data['cdl_wildberries_return_oc'] = $this->request->post['cdl_wildberries_return_oc'];
		} else {
			$data['cdl_wildberries_return_oc'] = $this->config->get('cdl_wildberries_return_oc');
		}
    if (isset($this->request->post['cdl_wildberries_packaging_oc'])) {
			$data['cdl_wildberries_packaging_oc'] = $this->request->post['cdl_wildberries_packaging_oc'];
		} else {
			$data['cdl_wildberries_packaging_oc'] = $this->config->get('cdl_wildberries_packaging_oc');
		}
    if (isset($this->request->post['cdl_wildberries_delivering_oc'])) {
			$data['cdl_wildberries_delivering_oc'] = $this->request->post['cdl_wildberries_delivering_oc'];
		} else {
			$data['cdl_wildberries_delivering_oc'] = $this->config->get('cdl_wildberries_delivering_oc');
		}
    if (isset($this->request->post['cdl_wildberries_delevered_oc'])) {
			$data['cdl_wildberries_delevered_oc'] = $this->request->post['cdl_wildberries_delevered_oc'];
		} else {
			$data['cdl_wildberries_delevered_oc'] = $this->config->get('cdl_wildberries_delevered_oc');
		}
    if (isset($this->request->post['cdl_wildberries_email_order'])) {
			$data['cdl_wildberries_email_order'] = $this->request->post['cdl_wildberries_email_order'];
		} else {
			$data['cdl_wildberries_email_order'] = $this->config->get('cdl_wildberries_email_order');
		}
    if (isset($this->request->post['cdl_wildberries_pass_supplies'])) {
			$data['cdl_wildberries_pass_supplies'] = $this->request->post['cdl_wildberries_pass_supplies'];
		} else {
			$data['cdl_wildberries_pass_supplies'] = $this->config->get('cdl_wildberries_pass_supplies');
		}
    if (isset($this->request->post['cdl_wildberries_export_stock_null'])) {
			$data['cdl_wildberries_export_stock_null'] = $this->request->post['cdl_wildberries_export_stock_null'];
		} else {
			$data['cdl_wildberries_export_stock_null'] = $this->config->get('cdl_wildberries_export_stock_null');
		}
    if (isset($this->request->post['cdl_wildberries_product_npu'])) {
			$data['cdl_wildberries_product_npu'] = $this->request->post['cdl_wildberries_product_npu'];
		} elseif (!empty($this->config->get('cdl_wildberries_product_npu'))) {
			$data['cdl_wildberries_product_npu'] = $this->config->get('cdl_wildberries_product_npu');
		} else {
			$data['cdl_wildberries_product_npu'] = array();
		}
    if (isset($this->request->post['cdl_wildberries_product_price_oc'])) {
			$data['cdl_wildberries_product_price_oc'] = $this->request->post['cdl_wildberries_product_price_oc'];
		} else {
			$data['cdl_wildberries_product_price_oc'] = $this->config->get('cdl_wildberries_product_price_oc');
		}
    if (isset($this->request->post['cdl_wildberries_price_export_ot'])) {
			$data['cdl_wildberries_price_export_ot'] = $this->request->post['cdl_wildberries_price_export_ot'];
		} else {
			$data['cdl_wildberries_price_export_ot'] = $this->config->get('cdl_wildberries_price_export_ot');
		}
    if (isset($this->request->post['cdl_wildberries_price_export_do'])) {
			$data['cdl_wildberries_price_export_do'] = $this->request->post['cdl_wildberries_price_export_do'];
		} else {
			$data['cdl_wildberries_price_export_do'] = $this->config->get('cdl_wildberries_price_export_do');
		}
    if (isset($this->request->post['cdl_wildberries_return_store_ms'])) {
			$data['cdl_wildberries_return_store_ms'] = $this->request->post['cdl_wildberries_return_store_ms'];
		} else {
			$data['cdl_wildberries_return_store_ms'] = $this->config->get('cdl_wildberries_return_store_ms');
		}
    if (isset($this->request->post['cdl_wildberries_auto_return'])) {
			$data['cdl_wildberries_auto_return'] = $this->request->post['cdl_wildberries_auto_return'];
		} else {
			$data['cdl_wildberries_auto_return'] = $this->config->get('cdl_wildberries_auto_return');
		}
    if (isset($this->request->post['cdl_wildberries_payment_oc'])) {
			$data['cdl_wildberries_payment_oc'] = $this->request->post['cdl_wildberries_payment_oc'];
		} else {
			$data['cdl_wildberries_payment_oc'] = $this->config->get('cdl_wildberries_payment_oc');
		}
    if (isset($this->request->post['cdl_wildberries_attributes_description'])) {
			$data['cdl_wildberries_attributes_description'] = $this->request->post['cdl_wildberries_attributes_description'];
		} else {
			$data['cdl_wildberries_attributes_description'] = $this->config->get('cdl_wildberries_attributes_description');
		}
    if (isset($this->request->post['cdl_wildberries_export_category'])) {
			$data['cdl_wildberries_export_category'] = $this->request->post['cdl_wildberries_export_category'];
		} else {
			$data['cdl_wildberries_export_category'] = $this->config->get('cdl_wildberries_export_category');
		}
    if (isset($this->request->post['cdl_wildberries_name_customer'])) {
			$data['cdl_wildberries_name_customer'] = $this->request->post['cdl_wildberries_name_customer'];
		} else {
			$data['cdl_wildberries_name_customer'] = $this->config->get('cdl_wildberries_name_customer');
		}
    if (isset($this->request->post['cdl_wildberries_lastname_customer'])) {
			$data['cdl_wildberries_lastname_customer'] = $this->request->post['cdl_wildberries_lastname_customer'];
		} else {
			$data['cdl_wildberries_lastname_customer'] = $this->config->get('cdl_wildberries_lastname_customer');
		}
    if (isset($this->request->post['cdl_wildberries_name_crop'])) {
			$data['cdl_wildberries_name_crop'] = $this->request->post['cdl_wildberries_name_crop'];
		} else {
			$data['cdl_wildberries_name_crop'] = $this->config->get('cdl_wildberries_name_crop');
		}
    if (isset($this->request->post['cdl_wildberries_discrepancy_oc'])) {
			$data['cdl_wildberries_discrepancy_oc'] = $this->request->post['cdl_wildberries_discrepancy_oc'];
		} else {
			$data['cdl_wildberries_discrepancy_oc'] = $this->config->get('cdl_wildberries_discrepancy_oc');
		}
    if (isset($this->request->post['cdl_wildberries_card_version'])) {
			$data['cdl_wildberries_card_version'] = $this->request->post['cdl_wildberries_card_version'];
		} else {
			$data['cdl_wildberries_card_version'] = $this->config->get('cdl_wildberries_card_version');
		}

    // Статусы заказа OC
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    // Таблица наценок
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

    // Дни доступности товара на складе
    $data['works_day'] = array(
      '1' => 'Пн',
      '2' => 'Вт',
      '3' => 'Ср',
      '4' => 'Чт',
      '5' => 'Пт',
      '6' => 'Сб',
      '7' => 'Вс'
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

    // Черный список
		$data['blacklist'] = array();
		if (!empty($this->config->get('cdl_wildberries_blacklist'))) {
			$this->load->model('catalog/product');
			$blacklist = $this->config->get('cdl_wildberries_blacklist');
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

    // Белый список складов
		$data['sklad_white_list'] = array();
		if (!empty($this->config->get('cdl_wildberries_warehouses'))) {
			$this->load->model('catalog/product');
			foreach ($this->config->get('cdl_wildberries_warehouses') as $sklad) {
				if (!empty($sklad['white_list'])) {
					$white_list = $sklad['white_list'];
					foreach ($white_list as $product_id) {
						$product_info = $this->model_catalog_product->getProduct($product_id);
						if ($product_info) {
							$data['sklad_white_list'][] = array(
								'sklad' 		 => $sklad['sklad_id'],
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
		if (!empty($this->config->get('cdl_wildberries_warehouses'))) {
			$this->load->model('catalog/product');
			foreach ($this->config->get('cdl_wildberries_warehouses') as $sklad_bl) {
				if (!empty($sklad_bl['black_list'])) {
					$black_list = $sklad_bl['black_list'];
					foreach ($black_list as $product_id) {
						$product_info = $this->model_catalog_product->getProduct($product_id);
						if ($product_info) {
							$data['sklad_black_list'][] = array(
								'sklad' 		 => $sklad_bl['sklad_id'],
								'product_id' => $product_info['product_id'],
								'name'       => $product_info['name']
							);
						}
					}
				}
			}
		}

    // Черный список цен
		$data['products_npu'] = array();
		if (!empty($this->config->get('cdl_wildberries_product_npu'))) {
			$this->load->model('catalog/product');
			$products_npu = $this->config->get('cdl_wildberries_product_npu');
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

    // Связка товара с МС
		$data['connect_shop'] = array(
      'sku'        => 'Артикул',
      'model'      => 'Код (модель)',
      'product_id' => 'ID товара'
    );
		$data['connect_ms'] = array(
      'article' => 'Артикул',
      'code'    => 'Код',
      'ex_code' => 'Внешний код'
    );

    // Связка товара с WB
    $data['relations'] = array(
      'sku'        => 'по артикулу',
      'model'      => 'по коду (модель)',
      'product_id' => 'по ID товара',
      'upc'        => 'по upc',
      'ean'        => 'по ean',
      'jan'        => 'по jan',
      'isbn'       => 'по isbn',
      'mpn'        => 'по mpn'
    );

    // Категории
    $this->load->model('catalog/category');
		$filter_data = array(
			'sort'        => 'name',
			'order'       => 'ASC'
		);
		$data['categories'] = $this->model_catalog_category->getCategories($filter_data);

    // Производители
		$this->load->model('catalog/manufacturer');
		$filter_data = array('sort' => 'name');
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers($filter_data);

    // URL
    $data['action'] = $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension', 'token=' . $this->session->data['token'] . '&type=module', 'SSL');
		$data['url_rima'] = $this->url->link('module/cdl_wildberries/rima', 'token=' . $this->session->data['token'], 'SSL');
		$data['url_attributes'] = $this->url->link('module/cdl_wildberries/attributes', 'token=' . $this->session->data['token'], 'SSL');
		$data['url_product'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'], 'SSL');
		$data['url_orders_wb'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_no_category_save'] = $this->url->link('module/cdl_wildberries/nocategorysave&token=' . $this->session->data['token'], 'SSL');
    $data['clear'] = $this->url->link('module/cdl_wildberries/clear', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_download_category'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=getparentlist';
    $data['url_price'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=price&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_stock'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=stock&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_orders'] =  HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_order/pass&request=orders&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_get_warehouse'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=warehouse';
    $data['get_metadata'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=statusms&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_get_input_id'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=inputidms&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_webhook_create'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=webhookcreate&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_webhook_delete'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=webhookdelete&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_brendlist'] = 'https://www.wildberries.ru/wildberries/brandlist.aspx?letter=a';
    $data['url_supplies'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_supplies/pass&request=0&pass=' . $this->config->get('cdl_wildberries_pass_supplies');
    $data['url_statistics'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_statistics&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_check_token'] = 'https://suppliers-api.wildberries.ru/content/v1/object/all?top=1';

    // Единицы измерения
    $this->load->model('localisation/length_class');
		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
		if (isset($this->request->post['cdl_wildberries_length'])) {
			$data['cdl_wildberries_length'] = $this->request->post['cdl_wildberries_length'];
		} elseif (!empty('cdl_wildberries_length')) {
			$data['cdl_wildberries_length'] = $this->config->get('cdl_wildberries_length');
		} else {
			$data['cdl_wildberries_length'] = $this->config->get('config_length_class_id');
		}
		$this->load->model('localisation/weight_class');
		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		if (isset($this->request->post['cdl_wildberries_weight'])) {
			$data['cdl_wildberries_weight'] = $this->request->post['cdl_wildberries_weight'];
		} elseif (!empty('cdl_wildberries_weight')) {
			$data['cdl_wildberries_weight'] = $this->config->get('cdl_wildberries_weight');
		} else {
			$data['cdl_wildberries_weight'] = $this->config->get('config_weight_class_id');
		}

    // Штрихкод
    $data['input_barcodes'] = array(
      // 'ean'   => 'EAN',
      // 'upc'   => 'UPC',
      // 'jan'   => 'JAN',
      // 'isbn'  => 'ISBN',
      // 'mpn'   => 'MPN',
      'gener' => 'Получить в WB'
    );

    // Фильтр экспорта товаров из категории
    $data['export_filters'] = array(
      'all' => $this->language->get('text_all_product_export'),
      'attr' => $this->language->get('text_attr_product_export')
    );

    // Лог
		$data['log'] = '';
		$file = DIR_LOGS . 'cdl_wildberries.log';
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
    $this->checkUpdate();
		$this->response->setOutput($this->load->view('module/cdl_wildberries.tpl', $data));
  }

  // Живой поиск категорий WB
	public function autocompleteCategoryWb() {
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('module/cdl_wildberries');
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 70
			);
			$results = $this->model_module_cdl_wildberries->searchWbCategory($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'sub_category' => $result['sub_category'],
					'title' => $result['category'] . ' > ' . $result['sub_category']
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

  // Список не сопоставленных категорий магазина
  public function noCategorySave()
  {
    // Категории
    $this->load->model('catalog/category');
    $this->load->model('module/cdl_wildberries');
		$filter_data = array(
			'sort'        => 'name',
			'order'       => 'ASC'
		);
		$shop_categories = $this->model_catalog_category->getCategories($filter_data);
    $wb_categorys = $this->config->get('cdl_wildberries_category');
    $save = array();
    foreach ($wb_categorys as $wb_cat) {
      $save[] = $wb_cat['shop'];
    }
    $msg = '';
    foreach ($shop_categories as $shop_cat) {
      if (!in_array($shop_cat['category_id'], $save)) {
        $products = $this->model_module_cdl_wildberries->productInCategory($shop_cat['category_id']);
        if (!empty($products)) {
          $msg .= $shop_cat['name'] . "\n";
        }
      }
    }
    echo html_entity_decode($msg);
  }

  // Атрибуты
  public function attributes()
  {
    $this->load->language('module/cdl_wildberries');
		$this->document->setTitle($this->language->get('heading_title_attributes'));
    $this->load->model('module/cdl_wildberries');
    $this->document->addScript('view/javascript/jquery/select2/js/select2.min.js');
    $this->document->addStyle('view/javascript/jquery/select2/css/select2.min.css');

    // Тексты
    $data['heading_title_attributes'] = $this->language->get('heading_title_attributes');
    $data['button_return_module'] = $this->language->get('button_return_module');
    $data['button_save'] = $this->language->get('button_save');
    $data['text_edit_attribute'] = $this->language->get('text_edit_attribute');
    $data['text_rima'] = $this->language->get('text_rima');
    $data['text_select_catetory_wb'] = $this->language->get('text_select_catetory_wb');
    $data['text_attributes_wb'] = $this->language->get('text_attributes_wb');
    $data['text_attributes_shop'] = $this->language->get('text_attributes_shop');
    $data['text_dictionary'] = $this->language->get('text_dictionary');
    $data['text_is_defined'] = $this->language->get('text_is_defined');
    $data['text_reload_attribute'] = $this->language->get('text_reload_attribute');
    $data['text_only_dictionary'] = $this->language->get('text_only_dictionary');
    $data['text_product'] = $this->language->get('text_product');
    $data['text_menu'] = $this->language->get('text_menu');
    $data['text_placeholder_defined'] = $this->language->get('text_placeholder_defined');
    $data['text_orders_wb'] = $this->language->get('text_orders_wb');
    $data['text_action'] = $this->language->get('text_action');
    $data['text_supplies'] = $this->language->get('text_supplies');
    $data['help_action'] = $this->language->get('help_action');
    $data['help_attribut_is_defined'] = $this->language->get('help_attribut_is_defined');

    //Крошки
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
    $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_attributes'),
			'href' => $this->url->link('module/cdl_wildberries/attributes', 'token=' . $this->session->data['token'], 'SSL')
		);

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

    // Запросы
    if (isset($this->request->get['category'])) {
      $filter_category = explode('-', urldecode($this->request->get['category']));
      $sub_category = $filter_category[0];
      $shop_category = $filter_category[1];
      $data['wb_attributes'] = $this->model_module_cdl_wildberries->getAttrWbBySubCategory($sub_category);
      $data['filter_category'] = urldecode($this->request->get['category']);
    } else {
      $data['filter_category'] = '';
      $sub_category = '';
    }

    // Действие с значением атрибута
    $data['actions'] = array(
      ''  => '',
      '/' => 'делить',
      '*' => 'умножить',
      '-' => 'вычесть',
      '+' => 'сложить'
    );

		if (isset($this->request->post['attributes']) && !empty($data['filter_category'])) {
      $attributes = $this->request->post['attributes'];
      $this->model_module_cdl_wildberries->deleteAttributesToShop($sub_category, $shop_category);
      foreach ($attributes as $key => $attribute) {
        $type = htmlspecialchars($key);
        $shop_id = htmlspecialchars($attribute['shop']);
        if (!empty($attribute['value'])) {
          $value = htmlspecialchars($attribute['value']);
        } else {
          $value = '';
        }
        if (isset($attribute['defined'])) {
          $is_defined = true;
          $shop_id = '';
        } else {
          $is_defined = false;
        }
        if (empty($attribute['nomenclature'])) {
          $nomenclature = '';
        } else {
          $nomenclature = true;
        }
        if (empty($attribute['nomenclature_variation'])) {
          $nomenclature_variation = '';
        } else {
          $nomenclature_variation = true;
        }
        if (empty($attribute['number'])) {
          $number = '';
        } else {
          $number = true;
        }
        if (empty($attribute['required'])) {
          $required = '';
        } else {
          $required = true;
        }
        if (empty($attribute['id'])) {
          $id = '';
        } else {
          $id = htmlspecialchars($attribute['id']);
        }
        if (empty($attribute['units'])) {
          $units = '';
        } else {
          $units = htmlspecialchars($attribute['units']);
        }
        if (empty($attribute['dictionary'])) {
          $dictionary = '';
        } else {
          $dictionary = true;
        }
        if (empty($attribute['only_dictionary'])) {
          $only_dictionary = '';
        } else {
          $only_dictionary = true;
        }
        if (!isset($attribute['defined']) && !empty($attribute['action']) && !empty($attribute['action_value'])) {
          $action = $attribute['action'];
          $action_value = $attribute['action_value'];
        } else {
          $action = '';
          $action_value = '';
        }
        if (!empty($shop_id) || !empty($is_defined)) {
          $this->model_module_cdl_wildberries->saveAttributesToShop($sub_category, $shop_category, $type, $shop_id, $is_defined, $value, $nomenclature, $nomenclature_variation, $number, $required, $id, $units, $dictionary, $only_dictionary, $action, $action_value);
        }
      }
    }
    if (!empty($sub_category) && !empty($shop_category)) {
      $attributes_to_shop = $this->model_module_cdl_wildberries->getAttributesToShop($sub_category, $shop_category);
    }

    $shop_product_input = array(
      'weight'  => 'Вес, г.',
      'height'  => 'Высота, см.',
      'length0' => 'Длина, см.',
      'width'   => 'Ширина, см.',
      'sku'     => 'Артикул',
      'model'   => 'Модель',
      'mpn'     => 'MPN',
      'isbn'    => 'ISBN',
      'ean'     => 'EAN',
      'jan'     => 'JAN',
      'upc'     => 'UPC',
      'name'    => 'Наименование'
    );

    if (!empty($attributes_to_shop)) {
      $this->load->model('catalog/attribute');
      foreach ($attributes_to_shop as $attr_to_shop) {
        foreach ($data['wb_attributes'] as $key => $wb_attribut) {
          if ($wb_attribut['type'] == $attr_to_shop['type']) {
            if (array_key_exists($attr_to_shop['shop_id'], $shop_product_input)) {
              $data['wb_attributes'][$key]['shop_name'] = $shop_product_input[$attr_to_shop['shop_id']];
            } else {
              $shop_attribute = $this->model_catalog_attribute->getAttribute($attr_to_shop['shop_id']);
              if (!empty($shop_attribute['name'])) {
                $data['wb_attributes'][$key]['shop_name'] = $shop_attribute['name'];
              } else {
                $data['wb_attributes'][$key]['shop_name'] = '';
              }
            }
            $data['wb_attributes'][$key]['shop_id'] = $attr_to_shop['shop_id'];
            if (!empty($attr_to_shop['is_defined'])) {
              $data['wb_attributes'][$key]['is_defined'] = $attr_to_shop['is_defined'];
            }
            if (!empty($attr_to_shop['value'])) {
              $data['wb_attributes'][$key]['value'] = $attr_to_shop['value'];
            }
            if (!empty($attr_to_shop['action'])) {
              $data['wb_attributes'][$key]['action'] = $attr_to_shop['action'];
              $data['wb_attributes'][$key]['action_value'] = $attr_to_shop['action_value'];
            }
          }
        }
      }
    } else {
      if (!empty($data['wb_attributes'])) {
        foreach ($data['wb_attributes'] as $key => $wb_attribut) {
          switch ($wb_attribut['type']) {
            case 'Высота упаковки':
              $data['wb_attributes'][$key]['shop_name'] = $shop_product_input['height'];
              $data['wb_attributes'][$key]['shop_id'] = 'height';
            break;
            case 'Глубина упаковки':
              $data['wb_attributes'][$key]['shop_name'] = $shop_product_input['length0'];
              $data['wb_attributes'][$key]['shop_id'] = 'length0';
            break;
            case 'Ширина упаковки':
              $data['wb_attributes'][$key]['shop_name'] = $shop_product_input['width'];
              $data['wb_attributes'][$key]['shop_id'] = 'width';
            break;
            case 'Ширина упаковки':
              $data['wb_attributes'][$key]['shop_name'] = $shop_product_input['width'];
              $data['wb_attributes'][$key]['shop_id'] = 'width';
            break;
            case 'Вес товара с упаковкой (г)':
              $data['wb_attributes'][$key]['shop_name'] = $shop_product_input['weight'];
              $data['wb_attributes'][$key]['shop_id'] = 'weight';
            break;
            case 'Вес с упаковкой (кг)':
              $data['wb_attributes'][$key]['shop_name'] = $shop_product_input['weight'];
              $data['wb_attributes'][$key]['shop_id'] = 'weight';
              $data['wb_attributes'][$key]['action'] = '/';
              $data['wb_attributes'][$key]['action_value'] = '1000';
            break;
          }
        }
      }
    }

    // URL
    $data['action'] = $this->url->link('module/cdl_wildberries/attributes&category=' . $data['filter_category'], 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_get_attr'] = $this->url->link('module/cdl_wildberries/attributes&token=' . $this->session->data['token']);
    $data['url_download_attr'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=downloadattributes';
    $data['url_rima'] = $this->url->link('module/cdl_wildberries/rima', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_modal'] = $this->url->link('module/cdl_wildberries/modaldictionary&token=' . $this->session->data['token']);
    $data['url_reload_attribute'] = $this->url->link('module/cdl_wildberries/attributedelete&token=' . $this->session->data['token'] . '&category=' . urlencode($sub_category));
    $data['url_product'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_orders_wb'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_supplies'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_supplies/pass&request=0&pass=' . $this->config->get('cdl_wildberries_pass_supplies');
    $data['url_statistics'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_statistics&pass=' . $this->config->get('cdl_wildberries_pass');

    $data['cdl_wildberries_pass'] = $this->config->get('cdl_wildberries_pass');

    // Категории WB
    $this->load->model('catalog/category');
    $data['cdl_wildberries_category'] = array();
    $wb_to_shop_categorys = $this->config->get('cdl_wildberries_category');
    foreach ($wb_to_shop_categorys as $key => $wb_to_shop_category) {
      $data['cdl_wildberries_category'][$key]['wb'] = $wb_to_shop_category['wb'];
      $data['cdl_wildberries_category'][$key]['filter_value'] = $wb_to_shop_category['wb'] . '-' . $wb_to_shop_category['shop'];
      $shop_category_name = $this->model_catalog_category->getCategory($wb_to_shop_category['shop']);
      $data['cdl_wildberries_category'][$key]['name'] = $wb_to_shop_category['wb'] . ' - ' . $shop_category_name['name'] . ' (id ' . $wb_to_shop_category['shop'] . ')';
    }

    // Кнопка скачать атрибуты
    $data['btn_download_attr'] = '';
    if (!empty($data['cdl_wildberries_category'])) {
      foreach ($data['cdl_wildberries_category'] as $category) {
        if (!empty($category['wb'])) {
          $check = $this->model_module_cdl_wildberries->getAttrWbBySubCategory($category['wb']);
          if (empty($check)) {
            $data['btn_download_attr'] = '<button type="button" class="btn btn-warning download-attr"><i class="fa fa-download"></i> ' . $this->language->get('text_download_attr') . '</button>';
            break;
          }
        }
      }
    }

    // Отображение
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['token'] = $this->session->data['token'];

		$this->response->setOutput($this->load->view('module/cdl_wildberries_attributes.tpl', $data));
  }

  // Удалить атрибуты
  public function attributeDelete()
  {
    if (isset($this->request->get['category'])) {
      $this->load->model('module/cdl_wildberries');
      $sub_category = htmlspecialchars($this->request->get['category']);
      $this->model_module_cdl_wildberries->attributeDelete($sub_category);
    }
  }

  // Модальное окно атрибутов
  public function modaldictionary()
  {
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
		  exit();
		}
    $this->load->model('module/cdl_wildberries');
    $pass = $this->config->get('cdl_wildberries_pass');

    if (isset($this->request->get['type']) && isset($this->request->get['category'])) {
      $type = htmlspecialchars($this->request->get['type']);
      $title = $type;
      $request_category = explode('-', $this->request->get['category']);
      $sub_category = $request_category[0];
      $shop_category = $request_category[1];
      $wb_attributes = $this->model_module_cdl_wildberries->getAttributesByType($type, $sub_category);

      // URL запроса к справочнику
      $url = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/autocompletedictionary&dictionary=' . rawurlencode($wb_attributes[0]['dictionary']);
      if ($wb_attributes[0]['dictionary'] == 'tnved') {
        $url .= '&objectName=' . rawurlencode($sub_category);
      }
      if ($wb_attributes[0]['dictionary'] == 'colors') {
        $url = 'https://shop.cdl-it.ru/index.php?route=extension/module/cdl_request_wb_dictionary/get&dictionary=' . rawurlencode($wb_attributes[0]['dictionary']);
      }
    }

    if (isset($this->request->get['shop_id'])) {
      $shop_id = $this->request->get['shop_id'];
      $shop_dictionarys = $this->model_module_cdl_wildberries->getShopDictionary($shop_id, $shop_category);

      if (!empty($shop_id)) {
        $this->load->model('catalog/attribute');
        $shop_attribute = $this->model_catalog_attribute->getAttribute($shop_id);
        $shop_attribute_name = $shop_attribute['name'];
      }

      $this->load->model('catalog/category');
      $shop_categorys_name = $this->model_catalog_category->getCategory($shop_category);
      $shop_category_name = $shop_categorys_name['name'];
    }

    $body = '<form action="" method="post" id="dictionary-form">';
      if (!empty($shop_id)) {
        $body .= '<input type="hidden" name="shop-attribute-id" value="' . $shop_id . '" /><input type="hidden" name="sub_category" value="' . $sub_category . '" /><input type="hidden" name="shop_category" value="' . $shop_category . '" /><input type="hidden" name="type" value="' . $type . '" />';
      }
  		$body .= '<div class="table-responsive" style="padding:15px;">
  			<table class="table table-sm table-striped table-bordered table-hover">
  				<thead>
  					<tr>
  						<td style="width:35%;">Атрибут магазина</td>
  						<td style="width:30%;">Выбранные атрибуты WB (не более 3)</td>
  						<td style="width:35%;"><span style="background-color:yellow;">Онлайн</span> поиск атрибутов в WB</td>
  					</tr>
  				</thead>
  				<tbody>';

    if (!empty($shop_id)) {
      $row = 0;
      if (!empty($shop_dictionarys)) {
        foreach ($shop_dictionarys as $shop_dictionary) {
          $body .= '<tr><td>' . $shop_dictionary['text'] . '<input type="hidden" name="dictionary[' . $row . '][text-shop-attribute]" value="' . $shop_dictionary['text'] . '" /><button type="button" class="btn btn-sm pull-right" data-shop-text-row="' . $row . '" data-shop-text="' . $shop_dictionary['text'] . '" data-toggle="tooltip" title="Вставить значение из магазина"><i class="fa fa-caret-right"></i></button></td>
          <td><div data-attr-list="' . $row . '"></div>';
          $dictionary_to_shop = $this->model_module_cdl_wildberries->getDictionaryToShop($sub_category, $shop_category, $shop_id, $type);

          foreach ($dictionary_to_shop as $dict_to_shop) {

            if ($dict_to_shop['text_shop_attribute'] == $shop_dictionary['text']) {
              $wb_dictionary_value = explode('^', $dict_to_shop['dictionary_value']);

              foreach ($wb_dictionary_value as $wb_dict_value) {
                $body .= '<div data-attr-list="' . $row . $wb_dict_value . '"><i class="fa fa-minus-circle"></i> ' . $wb_dict_value . '<input type="hidden" name="dictionary[' . $row . '][wb][]" value="' . $wb_dict_value . '" /></div>';
              }
              break;
            }
          }
          $body .= '</td><td><div class="input-group">
            <input type="text" class="form-control search input-sm" data-row="' . $row . '" name="" value="" />
            <span class="input-group-btn"><button type="button" data-btn="' . $row . '" data-attr-shop="' . $shop_dictionary['text'] . '" class="btn btn-primary btn-sm search-btn" data-toggle="tooltip" title="Быстрая вставка"><i class="fa fa-clone"></i></button></span>
          </div></td>
          </tr>';
          $row++;
        }
      } else {
        $body .= '<tr><td>В категории магазина: ' . $shop_category_name . ', нет товаров с атрибутом '. $shop_attribute_name . '</td><td><div data-attr-list="0"></div></td><td>
          <input type="text" class="form-control search input-sm" data-row="0" name="" value="" />
        </td></tr>';
      }

    } else {
      $body .= '<tr><td></td><td><div data-attr-list="0"></div></td><td>
        <input type="text" class="form-control search input-sm" data-row="0" name="" value="" />
      </td></tr>';
    }
    $body .= '</tbody></table></div></form>';

    $random = rand();

		$body .="<script>
    $('.search-btn').click(function() {
      attr_shop = $(this).attr('data-attr-shop');
      btn_id = $(this).attr('data-btn');
      $('[data-row=\'' + window.btn_id + '\']').val(attr_shop);
      $('[data-row=\'' + window.btn_id + '\']').focus();
    });

    $('.search').focus(function() {
			rows = $(this).attr('data-row');
			// dictionary_id = $(this).attr('data-dictionary-id');
		});

		$('.search').autocomplete({
			'multiselect': true,
			'source': function(request, response) {
				$.ajax({
					url: '" . $url . "&pattern=' +  encodeURIComponent(request),
          type: 'post',
          data: 'pass=" . $pass . "',
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item,
                value: item.toString().replace(/\"/g, 'quotes')
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('.search').val('');
				$('[data-attr=\'' + window.rows + item['label'] + '\']').remove();
				$('[data-attr-list=\'' + window.rows + '\']').append('<div data-attr=\'' + window.rows + item['label'] + '\'><i class=\'fa fa-minus-circle\'></i> ' + item['label'] + '<input type=\'hidden\' name=\'dictionary[' + window.rows +  '][wb][]\' value=\'' + item['label'] + '\' /></div>');
			}
		});

    $('.dropdown-menu').addClass('scrollable');
		$('.dropdown-menu').css({'left':'auto','right':'0'});
		$('.scrollable').css({'height':'auto','max-height':'30em','overflow-x':'hidden'});

		$('[data-attr-list]').delegate('.fa-minus-circle', 'click', function() {
			$(this).parent().remove();
		});

    // Вставить атрибут из магазина
    $('[data-shop-text]').click(function() {
      var row_text =$(this).attr('data-shop-text-row');
			var attr_text = $(this).attr('data-shop-text');
      $('[data-attr=\'' + row_text + attr_text + '\']').remove();
      $('[data-attr-list=\'' + row_text + '\']').append('<div data-attr=\'' + row_text + attr_text + '\'><i class=\'fa fa-minus-circle\'></i> ' + attr_text + '<input type=\'hidden\' name=\'dictionary[' + row_text +  '][wb][]\' value=\'' + attr_text + '\' /></div>');
		});

    $(document).on('click', '.save', function(e) {
    	e.preventDefault();
    	$.ajax({
    		url: 'index.php?route=module/cdl_wildberries/savedictionary&token=". $this->session->data['token'] . "',
    		type: 'POST',
    		dataType: 'html',
    		data: $('#dictionary-form').serialize(),
    		success: function(response) {
    			$('.modal').modal('hide');
    		}
    	});
    });

    // Копировать выбранное значение из справочника
    $(document).on('click', '.copy-" . $random . "', function() {
      var variants = $('input[name^=dictionary]');
      var value = [];
      $(variants).each(function() {
        value.push(this.value);
      });
      var value = value.join(',');
      $('input[data-val=\'" . $type . "\']').val(value);
      $('.modal').modal('hide');
    });
    </script>";

    if (!empty($shop_id)) {
      $footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button><button type="button" class="btn btn-success save">Сохранить изменения</button>';
    } else {
      $footer = '<div class="alert alert-info" style="text-align:center;">Вы хотите установить значение по умолчанию для всех товаров в категории.<br />Для этого скопируйте найденное значение в справочнике, закройте всплывающее окно и вставьте значение в поле напротив чекбокса.</div><button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button><button type="button" class="btn btn-success copy-' . $random . '">Использовать выбранное значение</button>';
    }
		$data['dictionary_shop'] = array();
		$output['title'] = $title;
		$output['body'] = $body;
		$output['footer'] = $footer;
		echo json_encode($output);
  }

  // Модальное окно наценок для категорий
  public function categoryRate()
  {
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
		  exit();
		}
    $this->load->model('module/cdl_wildberries');
    $rates = $this->model_module_cdl_wildberries->getCategoryRate();
    $categories = $this->config->get('cdl_wildberries_category');
    $category_filter = array();
    if (!empty($categories)) {
      foreach ($categories as $category) {
        if (!in_array($category['wb'], $category_filter)) {
          $category_filter[] = $category['wb'];
        }
      }
    }
    $body = '<form action="" method="post" id="category-price-form">
    <div class="table-responsive" style="padding:15px;">
      <table class="table table-sm table-striped table-bordered table-hover table-condensed">
        <thead>
          <tr>
            <td>Категория WB</td>
            <td>Надбавка в рублях</td>
            <td>Коэффицент через точку</td>
          </tr>
        </thead>
        <tbody>';
    if (!empty($category_filter)) {
      foreach ($category_filter as $category) {
        if (!empty($rates)) {
          foreach ($rates as $rate) {
            if ($rate['category_wb'] == $category) {
              $value_rub = $rate['rub'];
              $value_rate = $rate['rate'];
              break;
            } else {
              $value_rub = '';
              $value_rate = '';
            }
          }
        } else {
          $value_rub = '';
          $value_rate = '';
        }
        $body .= '<tr><td>' . $category . '</td><td><input type="text" name="rates[' . $category . '][rub]" value="' . $value_rub . '" /></td><td><input type="text" name="rates[' . $category . '][rate]" value="' . $value_rate . '" /></td></tr>';
      }
    }
    $body .= '</tbody></table></div></form>';
    $body .= "<script>
    $(document).on('click', '.save', function(e) {
    	e.preventDefault();
    	$.ajax({
    		url: 'index.php?route=module/cdl_wildberries/savecategoryrate&token=". $this->session->data['token'] . "',
    		type: 'POST',
    		dataType: 'html',
    		data: $('#category-price-form').serialize(),
    		success: function(response) {
    			$('.modal').modal('hide');
          $('#category-price-form').remove();
    		}
    	});
    });
    </script>";
    if (!empty($category_filter)) {
      $footer = '<div class="alert alert-info" style="text-align:center;">Если надбавка не нужна, то оставьте поле пустым. Надбавка в рублях и коэффицент суммируются.</div><button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button><button type="button" class="btn btn-success save">Сохранить изменения</button>';
    } else {
      $footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>';
    }
		$output['title'] = 'Надбавка по категориям';
		$output['body'] = $body;
		$output['footer'] = $footer;
		echo json_encode($output);
  }

  // Сохранить надбавки для категорий
  public function saveCategoryRate()
  {
    if (!empty($this->request->post['rates'])) {
      $this->load->model('module/cdl_wildberries');
      $this->model_module_cdl_wildberries->clearCategoryRate();
      foreach ($this->request->post['rates'] as $key => $rates) {
				$category_wb = $key;
				$rub = $rates['rub'];
				$rate = $rates['rate'];
				$this->model_module_cdl_wildberries->saveCategoryRate($category_wb, $rub, $rate);
  		}
    }
  }

  // Сохранить сопоставленные значения словарей
  public function saveDictionary()
  {
    if (!empty($this->request->post['dictionary'])) {
      $this->load->model('module/cdl_wildberries');
      $shop_attribute_id = $this->request->post['shop-attribute-id'];
      $sub_category = $this->request->post['sub_category'];
      $shop_category = $this->request->post['shop_category'];
      $type = $this->request->post['type'];
      $this->model_module_cdl_wildberries->deleteSaveDictionary($sub_category, $shop_category, $shop_attribute_id, $type);
      foreach ($this->request->post['dictionary'] as $dictionary) {
  			if (!empty($dictionary['wb'])) {
  				$dictionary_value= implode('^', $dictionary['wb']);
  				$text_shop_attribute = $dictionary['text-shop-attribute'];
  				$this->model_module_cdl_wildberries->saveDictionary($sub_category, $shop_category, $shop_attribute_id, $dictionary_value, $text_shop_attribute, $type);
  			}
  		}
    }
  }

  // Модальное окно производителей
	public function manufacturerSet()
  {
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
		  exit();
		}
		$this->load->model('module/cdl_wildberries');
    $pass = $this->config->get('cdl_wildberries_pass');

    // URL запроса к справочнику
    $url = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/autocompletedictionary&dictionary=brands';
    $url_country = 'https://shop.cdl-it.ru/index.php?route=extension/module/cdl_request_wb_dictionary/get&dictionary=countries';

		$this->load->model('catalog/manufacturer');
		$filter_data = array('sort' => 'name');
		$shop_manufacturers = $this->model_catalog_manufacturer->getManufacturers($filter_data);
    $wb_manufacturers = $this->model_module_cdl_wildberries->getManufacture();

		$body = '<form action="" method="post" id="manufacturer-form">
			<div class="table-responsive">
				<table class="table table-sm table-striped table-bordered table-hover">
					<thead>
						<tr>
							<td>Производители в магазине</td>
							<td>Страна производства</td>
              <td>Выбранные производители в WB</td>
							<td><span style="background-color:yellow;">Онлайн</span> поиск производителей в WB</td>
						</tr>
					</thead>
					<tbody>';
    $row = 0;
		foreach ($shop_manufacturers as $shop_manufacturer) {
			$body .= '<tr><td><input type="hidden" name="manufacturer[' . $row . '][shop_id]" value="' . $shop_manufacturer['manufacturer_id'] . '" />' . $shop_manufacturer['name'] . '<button type="button" class="btn btn-sm pull-right" data-shop-mf-row="' . $row . '" data-mf-name="' . $shop_manufacturer['name'] . '" data-toggle="tooltip" title="Вставить значение из магазина. Только если не нашли в WB."><i class="fa fa-caret-right"></i></button></td><td>';

      $country = '';
      foreach ($wb_manufacturers as $wb_country) {
        if ($wb_country['shop_id'] == $shop_manufacturer['manufacturer_id']) {
          $country = $wb_country['country'];
          break;
        }
      }
      $body .= '<input type="text" class="form-control search-country input-sm" data-row-country="' . $row . '" name="manufacturer[' . $row . '][country]" value="' . $country . '" />';

      $body .= '</td><td><div data-list="' . $row . '">';
      foreach ($wb_manufacturers as $wb_manufact) {
        if ($wb_manufact['shop_id'] == $shop_manufacturer['manufacturer_id']) {
          $body .= '<div data-list-manuf="' . $row . '"><i class="fa fa-minus-circle"></i> ' . $wb_manufact['dictionary_value'] . '<input type="hidden" name="manufacturer[' . $row . '][wb]" value="' . $wb_manufact['dictionary_value'] . '" />';
          break;
        }
      }
			$body .= '</div></td><td><div class="input-group">
        <input type="text" class="form-control search input-sm" data-search="' . $row . '" value="" />
        <span class="input-group-btn"><button type="button" data-row="' . $row . '" data-manuf-shop="' . $shop_manufacturer['name'] . '" class="btn btn-primary btn-sm search-btn" title="Быстрая вставка"><i class="fa fa-clone"></i></button></span>
      </div></td></tr>';
      $row++;
		}
		$body .= '</tbody></table></div></form>';
		$body .= "<script>
      $('.search-btn').click(function() {
        manuf_shop = $(this).attr('data-manuf-shop');
        row = $(this).attr('data-row');
        $('[data-search=\'' + window.row + '\']').val(manuf_shop);
        $('[data-search=\'' + window.row + '\']').focus();
      });

      $('.search').click(function() {
        row = $(this).attr('data-search');
      });

			$('.search').autocomplete({
				'source': function(request, response) {
					$.ajax({
						url: '" . $url . "&pattern=' +  encodeURIComponent(request),
            type: 'post',
            data: 'pass=" . $pass . "',
						dataType: 'json',
						success: function(json) {
							response($.map(json, function(item) {
								return {
									label: item,
									value: item
								}
							}));
						}
					});
				},
				'select': function(item) {
          $('.search').val('');
  				$('[data-list-manuf=\'' + window.row + '\']').remove();
          $('[data-list=\'' + window.row + '\']').append('<div data-list-manuf=\'' + window.row + '\'><i class=\'fa fa-minus-circle\'></i> ' + item['label'] + '<input type=\'hidden\' name=\'manufacturer[' + window.row +  '][wb]\' value=\'' + item['label'] + '\' /></div>');
				}
			});

      $('.search-country').click(function() {
        row = $(this).attr('data-row-country');
      });

			$('.search-country').autocomplete({
				'source': function(request, response) {
					$.ajax({
						url: '" . $url_country . "&pattern=' +  encodeURIComponent(request),
            type: 'post',
            data: 'pass=" . $pass . "',
						dataType: 'json',
						success: function(json) {
							response($.map(json, function(item) {
								return {
									label: item,
									value: item
								}
							}));
						}
					});
				},
				'select': function(item) {
          $('input[name=\'manufacturer[' + window.row + '][country]\']').val(item['label']);
				}
			});

      $('.dropdown-menu').addClass('scrollable');
  		$('.dropdown-menu').css({'left':'auto','right':'0', 'width':'300px'});
  		$('.scrollable').css({'height':'auto','max-height':'30em','overflow-x':'hidden'});

      $('[data-list]').delegate('.fa-minus-circle', 'click', function() {
  			$(this).parent().remove();
  		});

      $(document).on('click', '.save', function(e) {
      	e.preventDefault();
      	$.ajax({
      		url: 'index.php?route=module/cdl_wildberries/savemanufacure&token=". $this->session->data['token'] . "',
      		type: 'POST',
      		dataType: 'html',
      		data: $('#manufacturer-form').serialize(),
      		success: function(response) {
      			$('.modal').modal('hide');
      		}
      	});
      });

      // Вставить атрибут из магазина
      $('[data-shop-mf-row]').click(function() {
        var row = $(this).attr('data-shop-mf-row');
  			var mf_name = $(this).attr('data-mf-name');
        $('[data-list-manuf=\'' + row + '\']').remove();
        $('[data-list=\'' + row + '\']').append('<div data-list-manuf=\'' + row + '\'><i class=\'fa fa-minus-circle\'></i> ' + mf_name + '<input type=\'hidden\' name=\'manufacturer[' + row +  '][wb]\' value=\'' + mf_name + '\' /></div>');
  		});
		</script>";

		$footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button><button type="button" class="btn btn-success save">Сохранить изменения</button>';

		$output['title'] = 'Сопоставьте производителей Wildberries и магазина';
		$output['body'] = $body;
		$output['footer'] = $footer;
		echo json_encode($output);
	}

  // Сохранить сопоставленных производителей
  public function saveManufacure()
  {
    if (!empty($this->request->post['manufacturer'])) {
      $this->load->model('module/cdl_wildberries');
      $this->model_module_cdl_wildberries->deleteManufacture();
      foreach ($this->request->post['manufacturer'] as $manufacturer) {
  			if (!empty($manufacturer['wb'])) {
          $shop_id = htmlspecialchars($manufacturer['shop_id']);
  				$dictionary_value = htmlspecialchars($manufacturer['wb']);
          if (!empty($manufacturer['country'])) {
            $country = htmlspecialchars($manufacturer['country']);
          } else {
            $country = 'Китай';
          }
  				$this->model_module_cdl_wildberries->saveManufacture($shop_id, $dictionary_value, $country);
  			}
  		}
    }
  }

  // Таблица перед экспортом
  public function rima()
  {
    $this->load->language('module/cdl_wildberries');
		$this->document->setTitle($this->language->get('heading_title_rima'));
    $this->load->model('module/cdl_wildberries');
    $this->load->model('catalog/product');
    $this->load->model('catalog/attribute');

    // Тексты
    $data['heading_title_rima'] = $this->language->get('heading_title_rima');
    $data['button_return_module'] = $this->language->get('button_return_module');
    $data['text_rima'] = $this->language->get('text_rima');
    $data['category_wb'] = $this->language->get('category_wb');
    $data['text_attributes_wb'] = $this->language->get('text_attributes_wb');
    $data['text_vendor_code'] = $this->language->get('text_vendor_code');
    $data['text_size'] = $this->language->get('text_size');
    $data['text_brend'] = $this->language->get('text_brend');
    $data['text_country'] = $this->language->get('text_country');
    $data['text_barcode'] = $this->language->get('text_barcode');
    $data['text_export_no_product'] = $this->language->get('text_export_no_product');
    $data['text_menu'] = $this->language->get('text_menu');
    $data['text_product'] = $this->language->get('text_product');
    $data['text_attributes'] = $this->language->get('text_attributes');
    $data['text_price'] = $this->language->get('text_price');
    $data['text_not_matched'] = $this->language->get('text_not_matched');
    $data['text_orders_wb'] = $this->language->get('text_orders_wb');
    $data['column_name'] = $this->language->get('column_name');
    $data['text_name_strlen'] = $this->language->get('text_name_strlen');
    $data['text_supplies'] = $this->language->get('text_supplies');
    $data['text_replace_name'] = $this->language->get('text_replace_name');
    $data['text_description'] = $this->language->get('text_description');
    $data['text_start'] = $this->language->get('text_start');
    $data['button_filter'] = $this->language->get('button_filter');

    //Крошки
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
    $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_rima'),
			'href' => $this->url->link('module/cdl_wildberries/rima', 'token=' . $this->session->data['token'], 'SSL')
		);

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
		if (!empty($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

    // URL
    $data['cancel'] = $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_attributes'] = $this->url->link('module/cdl_wildberries/attributes', 'token=' . $this->session->data['token'], 'SSL');
		$data['url_product'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'], 'SSL');
		$data['url_product_export'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=export&filter_category=';
    $data['url_orders_wb'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_rima'] = $this->url->link('module/cdl_wildberries/rima&token=' . $this->session->data['token'] . '&filter_category=');
    $data['url_change_name'] = $this->url->link('module/cdl_wildberries/changename&token=' . $this->session->data['token']);
    $data['url_supplies'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_supplies/pass&request=0&pass=' . $this->config->get('cdl_wildberries_pass_supplies');
    $data['url_statistics'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_statistics&pass=' . $this->config->get('cdl_wildberries_pass');

    $data['cdl_wildberries_pass'] = $this->config->get('cdl_wildberries_pass');
    $data['cdl_wildberries_category'] = $this->config->get('cdl_wildberries_category');

    if ($this->config->get('cdl_wildberries_test_export')) {
      $data['success'] = $this->language->get('text_export_test_aviable');
    }

    // Язык по-умолчанию
    $this->load->model('setting/setting');
    $language_config = $this->model_setting_setting->getSetting('config', $this->config->get('config_store_id'));
    $language = $language_config['config_language'];
    $language_id = $this->model_module_cdl_wildberries->getLanguage($language);

    if (!empty($this->config->get('cdl_wildberries_manufacturer_stop'))) {
      $manufacturer_stop = $this->config->get('cdl_wildberries_manufacturer_stop');
      $stop_manufacturer = implode(",", $manufacturer_stop);
    } else {
      $manufacturer_stop = array();
      $stop_manufacturer = 0;
    }

    // Производители
		$this->load->model('catalog/manufacturer');
		$filter_data = array('sort' => 'name');
		$manufacturers = $this->model_catalog_manufacturer->getManufacturers($filter_data);
    $data['manufacturers'][] = array('manufacturer_id' => '', 'name' => 'Все производители');
    foreach ($manufacturers as $manufacturer) {
      if (!in_array($manufacturer['manufacturer_id'], $manufacturer_stop)) {
        $data['manufacturers'][] = $manufacturer;
      }
    }

    // Количество товаров в экспорте
    $data['export_limit'] = array(
      '1' => 1,
      '2' => 2,
      '5' => 5,
      '10' => 10,
      '25' => 25,
      '50' => 50,
      '100' => 100
    );

    $shop_product_input = array(
      'weight'  => 'Вес, г.',
      'height'  => 'Высота, см.',
      'length0' => 'Длина, см.',
      'width'   => 'Ширина, см.',
      'sku'     => 'Артикул',
      'model'   => 'Модель',
      'mpn'     => 'MPN',
      'isbn'    => 'ISBN',
      'ean'     => 'EAN',
      'jan'     => 'JAN',
      'upc'     => 'UPC',
      'name'    => 'Наименование'
    );

    if (isset($this->request->get['filter_limit'])) {
      $data['filter_limit'] = $this->request->get['filter_limit'];
    } else {
      $data['filter_limit'] = 1;
    }
    if (isset($this->request->get['filter_start'])) {
      $data['filter_start'] = $this->request->get['filter_start'];
    } else {
      $data['filter_start'] = '';
    }
    if (isset($this->request->get['filter_manufacturer'])) {
      $data['filter_manufacturer'] = $this->request->get['filter_manufacturer'];
    } else {
      $data['filter_manufacturer'] = '';
    }

    $wb_categorys = $this->config->get('cdl_wildberries_category');
    $data['filter_category'] = $this->language->get('text_all_category');
    $data['rima_categorys'] = array();
    $data['rima_categorys'][] = $this->language->get('text_all_category');
    $error = false;
    $data['products'] = array();

    foreach ($wb_categorys as $wb_category) {
      if (isset($wb_category['stop'])) {
        continue;
      }

      // Проверка загрузки атрибутов
      $check = $this->model_module_cdl_wildberries->getAttrWbBySubCategory($wb_category['wb']);
      if (empty($check)) {
        $error = true;
      }
      // Фильтр товаров из категории
      if ($wb_category['filter_select'] != 'all') {
        if ($wb_category['filter_select'] == 'attr') {
          $filter_export_id = $wb_category['filter-attr-id'];
          $filter_export_value = $wb_category['filter-value'];
        }
      }
      $shop_category_id = $wb_category['shop'];
      $sub_category = $wb_category['wb'];

      if (!empty($this->config->get('cdl_wildberries_blacklist'))) {
        $blacklist = $this->config->get('cdl_wildberries_blacklist');
      } else {
        $blacklist = array();
      }

      $filter_data = array(
        'filter_category_id' => $shop_category_id,
        'filter_manufacturer_id' => $data['filter_manufacturer'],
        'filter_stop_manufacturer' => $stop_manufacturer,
        'blacklist' => $blacklist,
        'filter_sub_category'=> false, //выводить товары из подкатегорий
        'start'	=> $data['filter_start'],
        'limit' => $data['filter_limit']
      );

      $products = $this->model_module_cdl_wildberries->getProductsPreLoad($filter_data);
      if (empty($products)) {
        continue;
      }

      if (!empty($this->request->get['filter_category']) && $this->request->get['filter_category'] != 'Все категории') {
        $data['filter_category'] = $this->request->get['filter_category'];
        if ($sub_category != $data['filter_category']) {
          continue;
        }
      }

      $attr_wb = $this->model_module_cdl_wildberries->getAttrWbBySubCategory($sub_category);

      foreach ($products as $product) {

        if ($wb_category['filter_select'] != 'all') {
          if ($wb_category['filter_select'] == 'attr') {
            $product_filter = $this->model_module_cdl_wildberries->getProductAttribute($product['product_id'], $filter_export_id, $language_id);
            if (isset($product_filter[0]) && $product_filter[0]['text'] != $filter_export_value) {
              continue;
            }
          }
        }

        if (empty($product['image'])) {
          continue;
        }

        // vendorCode
        $vendor_code = $product[$this->config->get('cdl_wildberries_relations')];
        if (empty($vendor_code)) {
          $error = true;
        }

        // Размеры
        if ($product['weight_class_id'] == $this->config->get('cdl_wildberries_weight')) {
          if ($product['weight'] == 0) {
            $weight = $wb_category['weight'];
          } else {
            $weight = $product['weight'];
          }
        } else {
          if ($product['weight'] == 0) {
            $weight = $wb_category['weight'];
          } else {
            $weight = $product['weight'] * 1000;
          }
        }

        if ($product['length_class_id'] == $this->config->get('cdl_wildberries_length')) {
          if ($product['length'] == 0) {
            $length = $wb_category['length'];
          } else {
            $length = $product['length'];
          }
          if ($product['width'] == 0) {
            $width = $wb_category['width'];
          } else {
            $width = $product['width'];
          }
          if ($product['height'] == 0) {
            $height = $wb_category['height'];
          } else {
            $height = $product['height'];
          }
        } else {
          if ($product['length'] == 0) {
            $length = $wb_category['length'];
          } else {
            $length = $product['length'] / 10;
          }
          if ($product['width'] == 0) {
            $width = $wb_category['width'];
          } else {
            $width = $product['width'] / 10;
          }
          if ($product['height'] == 0) {
            $height = $wb_category['height'];
          } else {
            $height = $product['height'] / 10;
          }
        }

        // Бренд
        $wb_manufacturer = $this->model_module_cdl_wildberries->getManufactureRima($product['manufacturer_id']);

        if (!empty($wb_manufacturer)) {
          $brend = $wb_manufacturer[0]['dictionary_value'];
          $country = $wb_manufacturer[0]['country'];
        } else {
          $brend = '';
          $country = '';
          $error = true;
        }

        // Название
        $name = '<a href="' . $this->url->link('catalog/product/edit&product_id=' . $product['product_id'], 'token=' . $this->session->data['token'], 'SSL') . '" target="_blank">' . $product['name'] . '</a>';


        // Штрих-код
        if ($this->config->get('cdl_wildberries_barcode') == 'gener') {
          $barcode = 'Из WB';
        } else {
          $barcode = $product[$this->config->get('cdl_wildberries_barcode')];
        }
        if (empty($barcode)) {
          $error = true;
        }

        // Описание
        if ($this->config->get('cdl_wildberries_description')) {
          $description = $this->description($product['description']);
        } else {
          $description = ' ';
        }

        if ($this->config->get('cdl_wildberries_attributes_description')) {
          $attributes_in_description = ' ' . $product['name'];
          $attributes_product = $this->model_catalog_product->getProductAttributes($product['product_id']);
          if (!empty($attributes_product)) {
            foreach ($attributes_product as $attribute_product) {
              $attribute_name = $this->model_catalog_attribute->getAttribute($attribute_product['attribute_id']);
              $attribute_name = html_entity_decode($attribute_name['name'], ENT_NOQUOTES);
              $attribute_text = html_entity_decode($attribute_product['product_attribute_description'][1]['text'], ENT_NOQUOTES);
              $attributes_in_description .= ' | ' . str_replace('&quot;', '\'', $attribute_name) . ': ' . str_replace('&quot;', '\'', $attribute_text);
            }
          }

          $attributes_in_description = mb_strimwidth($attributes_in_description, 0, 4990, '... ');
          $width_description = mb_strwidth($description);
          $width_attributes_in_description = mb_strwidth($attributes_in_description);
          $width_summ_description = $width_description + $width_attributes_in_description;
          if ($width_summ_description > 5000) {
            $width_delete = $width_summ_description - 5000;
            $width_delete = $width_description - $width_delete;
            if ($width_attributes_in_description >= 4990) {
              $description = $attributes_in_description;
            } else {
              $description = mb_strimwidth($description, 0, $width_delete, '... ');
              $description = $description . $attributes_in_description;
              $description = mb_strlen($description,'UTF-8') . ' симв.';
            }
          } else {
            $description = $description . $attributes_in_description;
            $description = mb_strlen($description,'UTF-8') . ' симв.';
          }
        }

        // Сопоставленные атрибуты
        $attributes_to_shop = $this->model_module_cdl_wildberries->getAttributesToShop($sub_category, $shop_category_id);

        $attribute_export = array();

        foreach ($attr_wb as $att_wb) {
          if ($att_wb['required']) {
            $required = '&nbsp;';
          } else {
            $required = '';
          }
          $attribute_export[$att_wb['type']] = array('required' => $required);

          foreach ($attributes_to_shop as $attr_to_shop) {
            if ($attr_to_shop['type'] != $att_wb['type']) {
              continue;
            }
            if (empty($attr_to_shop['shop_id']) && empty($attr_to_shop['value'])) {
              continue;
            } else {
              $value = array();
              if ($attr_to_shop['is_defined']) {
                $pre_value = explode(',', $attr_to_shop['value']);
                foreach ($pre_value as $pre_val) {
                  if ($attr_to_shop['number']) {
                    $pre_val = (float)$pre_val;
                  }
                  if ($att_wb['type'] == 'Наименование') {
                    $name_attr = $this->nameProduct($pre_val, $brend);
                    $value[] = (string)$name_attr;
                  } else {
                    $value[] = (string)$pre_val;
                  }
                }
              } else {
                if (array_key_exists($attr_to_shop['shop_id'], $shop_product_input)) {
                  switch ($attr_to_shop['shop_id']) {
                    case 'weight':
                      $pre_value = round($weight, PHP_ROUND_HALF_UP);
                      break;
                    case 'height':
                      $pre_value = round($height, PHP_ROUND_HALF_UP);
                      break;
                    case 'length0':
                      $pre_value = round($length, PHP_ROUND_HALF_UP);
                      break;
                    case 'width':
                      $pre_value = round($width, PHP_ROUND_HALF_UP);
                      break;
                    default:
                      $pre_value = $product[$attr_to_shop['shop_id']];
                      break;
                  }
                  if (!empty($attr_to_shop['action'])) {
                    $value[] = $this->action($pre_value, $attr_to_shop['action'], $attr_to_shop['action_value']);
                  } elseif ($att_wb['type'] == 'Наименование') {
                    $name_attr = $this->nameProduct($pre_value, $brend);
                    $value[] = (string)$name_attr;
                  } else {
                    $value[] = $pre_value;
                  }
                }

                $product_attr = $this->model_module_cdl_wildberries->getProductAttribute($product['product_id'], $attr_to_shop['shop_id'], $language_id);

                if (!empty($product_attr)) {
                  // Значения атрибутов магазина и WB из справочника
                  if ($attr_to_shop['dictionary'] || $attr_to_shop['only_dictionary']) {
                    $dictionary_value = $this->model_module_cdl_wildberries->getDictionarExport($sub_category, $shop_category_id, $attr_to_shop['shop_id'], $product_attr[0]['text'], $att_wb['type']);
                    if (!empty($dictionary_value)) {
                      $pre_value = explode('^', $dictionary_value[0]['dictionary_value']);
                      foreach ($pre_value as $pre_val) {
                        if ($attr_to_shop['number']) {
                          $pre_val = (float)$pre_val;
                        } else {
                          $pre_val = htmlspecialchars_decode($pre_val, ENT_COMPAT);
                        }
                        $value[] = (string)$pre_val;
                      }
                    } elseif (empty($dictionary_value) && empty($attr_to_shop['only_dictionary'])) {
                      // Не сопоставленные значения атрибутов не с обязательным справочником берем напрямую из карточки
                      $pre_value = explode('^', $product_attr[0]['text']);
                      foreach ($pre_value as $pre_val) {
                        if ($attr_to_shop['number']) {
                          $pre_val = preg_replace("/[^0-9\.\,]/", '', $pre_val);
                          $pre_val = str_replace(',', '.', $pre_val);
                          $pre_val = (float)$pre_val;
                        } else {
                          $pre_val = htmlspecialchars_decode($pre_val, ENT_COMPAT);
                        }
                        if (!empty($attr_to_shop['action'])) {
                          $pre_val = $this->action($pre_val, $attr_to_shop['action'], $attr_to_shop['action_value']);
                        }
                        $value[] = (string)$pre_val;
                      }
                    }
                  } elseif (empty($attr_to_shop['only_dictionary'] && empty($dictionary_value))) {
                    // Значения атрибутов магазина без справочника
                    if (!empty($this->config->get('cdl_wildberries_delimiter'))) {
                      $delimiter = $this->config->get('cdl_wildberries_delimiter');
                      $pre_value = explode($delimiter, $product_attr[0]['text']);
                      foreach ($pre_value as $pre_val) {
                        if ($attr_to_shop['number']) {
                          $pre_val = preg_replace("/[^0-9\.\,]/", '', $pre_val);
                          $pre_val = str_replace(',', '.', $pre_val);
                          $pre_val = (float)$pre_val;
                        } else {
                          $pre_val = $pre_val;
                        }
                        if (!empty($attr_to_shop['action'])) {
                          $pre_val = $this->action($pre_val, $attr_to_shop['action'], $attr_to_shop['action_value']);
                        }
                        $value[] = (string)$pre_val;
                      }
                    } else {
                      if ($attr_to_shop['number']) {
                        $value = preg_replace("/[^0-9\.\,]/", '', $product_attr[0]['text']);
                        $value = str_replace(',', '.', $value);
                        $value = (float)$value;
                        if (!empty($attr_to_shop['action'])) {
                          $value = $this->action($value, $attr_to_shop['action'], $attr_to_shop['action_value']);
                        }
                        $value = array('value' => (string)$value);
                      } else {
                        if (!empty($attr_to_shop['action'])) {
                          $value = $this->action($product_attr[0]['text'], $attr_to_shop['action'], $attr_to_shop['action_value']);
                          $product_attr[0]['text'] = $value;
                        }
                        $value[] = $product_attr[0]['text'];
                      }
                    }
                  }
                }
              }
            }
            $attribute_export[$att_wb['type']] = $value;
          }
        }

        // Цена
        if ($product['special']) {
          $price = $this->price($product['special'], $product['category_id'], $product['manufacturer_id'], $product['product_id']);
        } else {
          $price = $this->price($product['price'], $product['category_id'], $product['manufacturer_id'], $product['product_id']);
        }

        $data['products'][$sub_category]['product'][] = array(
          'product_id' => $product['product_id'],
          'category' => $sub_category,
          'name' => $name,
          'vendor_code' => $vendor_code,
          'brend' => $brend,
          'country' => $country,
          'barcode' => $barcode,
          'price' => $price,
          'description' => $description,
          'attributes' =>$attribute_export
        );
        foreach ($attribute_export as $check_error) {
          if (!empty($check_error['required'])) {
            $error = true;
          }
        }
        // Конец итерации массива товаров
      }
      if (!empty($data['products'][$sub_category]['product'])) {
        $data['products'][$sub_category]['attr_wb'] = $attr_wb;
        if (!in_array($sub_category, $data['rima_categorys'])) {
          $data['rima_categorys'][] = $sub_category;
        }
      }
      // Конец итерации массива категорий
    }

    // Кнопка экспорта
    if (empty($data['products'])) {
      $data['button_export'] = '<button type="button" class="btn btn-warning" disabled>' . $this->language->get('text_btn_export_disable')  . '</button>';
    } elseif (!$error) {
      $data['button_export'] = '<button type="button" class="btn btn-success export">' . $this->language->get('text_rima')  . '</button>';
    } else {
      $data['button_export'] = '<button type="button" class="btn btn-warning" disabled>' . $this->language->get('text_btn_export_disable')  . '</button>';
      $data['error_warning'] = $this->language->get('text_export_disable');
    }
    // Отображение
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['token'] = $this->session->data['token'];

		$this->response->setOutput($this->load->view('module/cdl_wildberries_rima.tpl', $data));
  }

  // Подготовка наименования
  private function nameProduct($name, $manufacturer)
  {
    $name = $this->description($name);
    $name = str_replace($manufacturer, '', $name);
    $name_strlen = mb_strlen($name,'UTF-8');
    if ($name_strlen > 40) {
      if (!empty($this->config->get('cdl_wildberries_name_crop'))) {
        $name = mb_strimwidth($name, 0, 39, '');
      }
    }
    return $name;
  }

  // Изменить имя товара в магазине
  public function changeName()
  {
    if (!empty($this->request->get['name']) && !empty($this->request->get['product_id'])) {
      $this->load->model('module/cdl_wildberries');
      $name = $this->description($this->request->get['name']);
      $this->model_module_cdl_wildberries->changeName(htmlspecialchars($name), $this->request->get['product_id']);
      $name_strlen = mb_strlen($name,'UTF-8');
      if ($name_strlen > 1000) {
        $name_sum = $name_strlen - 1000;
        $name_s = '(лишних символов: ' . $name_sum . ') ';
      } else {
        $name_s = '';
      }
      $name = '<a href="' . $this->url->link('catalog/product/edit&product_id=' . $this->request->get['product_id'], 'token=' . $this->session->data['token'], 'SSL') . '" target="_blank">' . $name_s . $name . '</a>';
      echo $name;
    }
  }

  // Действие со значением атрибута
  private function action($value, $action, $action_value)
  {
    switch ($action) {
      case '/':
        $value /= $action_value;
        break;
      case '*':
        $value *= $action_value;
        break;
      case '-':
        $value -= $action_value;
        break;
      case '+':
        $value += $action_value;
        break;

      default:
        false;
        break;
    }
    return $value;
  }

  // Расчет цены
  private function price($price, $category_id, $manufacturer_id, $product_id)
  {
    // Надбавки по категориям
    $this->load->model('module/cdl_wildberries');
    $categories = $this->config->get('cdl_wildberries_category');
    foreach ($categories as $category) {
      if ($category['shop'] == $category_id) {
        $category_wb = $category['wb'];
        break;
      }
    }
    if (!empty($category_wb)) {
      $category_rate = $this->model_module_cdl_wildberries->getRateByCategory($category_wb);
      if (!empty($category_rate[0])) {
        $price += $category_rate[0]['rub'];
        if (!empty($category_rate[0]['rate'])) {
          $price *= $category_rate[0]['rate'];
        }
      }
    }

    // Дополнительные наценки
    if (!empty($this->config->get('cdl_wildberries_prices'))) {
      $new_price = 0;
			foreach ($this->config->get('cdl_wildberries_prices') as $prices) {
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
    switch ($this->config->get('cdl_wildberries_price_round')) {
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

  // Подготовка текста
  private function description($str)
  {
    $del = array("\r\n", "\n", "\r", "：", "`", "«", "»", "°", "`", "…", "ø", "●", "Ø", "®");
    $str = strip_tags(html_entity_decode($str, ENT_NOQUOTES), '</p> <br> <style> </style> <li> <td>');
    $str = preg_replace('/\s?<style>*?>.*?<\/style>\s?/', ' ', $str);
    $str = preg_replace('/<li(?:([\'"]).*?\1|.)*?>/', '<li>', $str);
    $str = preg_replace('/<td(?:([\'"]).*?\1|.)*?>/', '<td>', $str);
    $str = str_replace('&quot;','"', $str);
    $str = str_replace('=','-', $str);
    $str = str_replace(array('&ndash;', '–'), '-', $str);
    $str = str_replace('*','x', $str);
    $str = str_replace(array('&nbsp;', '&bull;', '<li>', '<td>'), ' ', $str);
    $str = str_replace($del, '', $str);
    $str = str_replace(array('</p>', "<br>", '<br />', '</li>', '</td>'), " ", $str);
    $str = preg_replace('/[\t]+/','', $str);
    $str = trim($str);
    $str = mb_strimwidth($str, 0, 990, '... ');
    return $str;
  }

  // Список товаров
  public function product()
  {
    $this->load->language('module/cdl_wildberries');
    $this->document->setTitle($this->language->get('heading_title_my_product'));
    $this->load->model('module/cdl_wildberries');
    $this->load->model('setting/setting');

    $data['heading_title_my_product'] = $this->language->get('heading_title_my_product');
    $data['text_product'] = $this->language->get('text_product');
    $data['text_no_results'] = $this->language->get('text_no_results');
    $data['text_error'] = $this->language->get('text_error');
    $data['text_check_product'] = $this->language->get('text_check_product');
    $data['text_attributes'] = $this->language->get('text_attributes');
    $data['text_rima'] = $this->language->get('text_rima');
    $data['text_menu'] = $this->language->get('text_menu');
    $data['text_update_product'] = $this->language->get('text_update_product');
    $data['text_orders_wb'] = $this->language->get('text_orders_wb');
    $data['text_barcode'] = $this->language->get('text_barcode');
    $data['text_download_product'] = $this->language->get('text_download_product');
    $data['text_supplies'] = $this->language->get('text_supplies');
    $data['text_check_img'] = $this->language->get('text_check_img');
    $data['category_shop'] = $this->language->get('category_shop');

    $data['button_return_module'] = $this->language->get('button_return_module');

    $data['column_name'] = $this->language->get('column_name');
    $data['column_sku'] = $this->language->get('column_sku');
    $data['column_imt'] = $this->language->get('column_imt');
    $data['column_nm'] = $this->language->get('column_nm');
    $data['column_model'] = $this->language->get('column_model');
    $data['column_status'] = $this->language->get('column_status');
    $data['column_date'] = $this->language->get('column_date');

    $data['entry_name'] = $this->language->get('entry_name');
    $data['entry_sku'] = $this->language->get('entry_sku');
    $data['entry_model'] = $this->language->get('entry_model');
    $data['entry_status'] = $this->language->get('entry_status');

    $data['button_edit'] = $this->language->get('button_edit');
    $data['button_filter'] = $this->language->get('button_filter');

    $data['token'] = $this->session->data['token'];
    $data['pass'] = $this->config->get('cdl_wildberries_pass');

    // URL
    $data['url_update_status_product'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=checknoimt';
    $data['url_download_product'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=download_products';
    $data['url_update_products'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=updateproducts';
    $data['url_check_img'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries/pass&request=checkimg';
    $data['url_delete_no_create'] = $this->url->link('module/cdl_wildberries/deletenocreate', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_attributes'] = $this->url->link('module/cdl_wildberries/attributes', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_rima'] = $this->url->link('module/cdl_wildberries/rima', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_orders_wb'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'], 'SSL');

    $data['export_categorys'] = $this->model_module_cdl_wildberries->getCategoryShop();

    if (isset($this->request->get['filter_name'])) {
      $filter_name = $this->request->get['filter_name'];
    } else {
      $filter_name = null;
    }
    if (isset($this->request->get['filter_barcode'])) {
      $filter_barcode = $this->request->get['filter_barcode'];
    } else {
      $filter_barcode = null;
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
    if (isset($this->request->get['filter_nm'])) {
      $filter_nm = $this->request->get['filter_nm'];
    } else {
      $filter_nm = null;
    }

    if (isset($this->request->get['filter_status'])) {
      $filter_status = $this->request->get['filter_status'];
    } else {
      $filter_status = null;
    }

    if (isset($this->request->get['filter_category'])) {
      $filter_category = $this->request->get['filter_category'];
    } else {
      $filter_category = null;
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
    if (isset($this->request->get['filter_barcode'])) {
      $url .= '&filter_barcode=' . urlencode(html_entity_decode($this->request->get['filter_barcode'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_model'])) {
      $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_sku'])) {
      $url .= '&filter_sku=' . $this->request->get['filter_sku'];
    }
    if (isset($this->request->get['filter_nm'])) {
      $url .= '&filter_nm=' . $this->request->get['filter_nm'];
    }
    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_category'])) {
      $url .= '&filter_category=' . $this->request->get['filter_category'];
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
      'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], true)
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_product'),
      'href' => $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'], true)
    );

    $data['statuses'] = array(
      'Не создан' => 'Не создан',
      'Без фото'  => 'Без фото',
      'Создается' => 'Создается',
      'Создан'    => 'Создан'
    );

    $data['products'] = array();

    $filter_data = array(
      'filter_name'	  	=> $filter_name,
      'filter_barcode'	=> $filter_barcode,
      'filter_model'	  => $filter_model,
      'filter_sku'	  	=> $filter_sku,
      'filter_nm'	  	  => $filter_nm,
      'filter_status'   => $filter_status,
      'filter_category' => $filter_category,
      'sort'            => $sort,
      'order'           => $order,
      'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
      'limit'           => $this->config->get('config_limit_admin')
    );

    $product_total = $this->model_module_cdl_wildberries->getTotalProducts($filter_data);
    $results = $this->model_module_cdl_wildberries->getProducts($filter_data);

    $button_delete_no_create = false;
    $data['button_check_product'] = '';

    foreach ($results as $result) {
      $status = '';
      switch ($result['status']) {
        case 'Не создан':
          $status .= '<span class="label label-warning">' . $result['status'] . '</span>';
          $button_delete_no_create = true;
          break;
        case 'Создается':
          $status .= '<span class="label label-info">' . $result['status'] . '</span>';
          $data['button_check_product'] = true;
          break;
        case 'Создан':
          $status .= '<span class="label label-success">' . $result['status'] . '</span>';
          break;
        default:
          $status .= '<span class="label label-danger">' . $result['status'] . '</span>';
          break;
      }

      $view_wb = '';
      if (!empty($result['nm_id']) && $result['status'] == 'Создан') {
        $view_wb = '<a href="https://www.wildberries.ru/catalog/' . $result['nm_id'] . '/detail.aspx?targetUrl=XS" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>';
      }

      $data['products'][] = array(
        'product_id'      => $result['product_id'],
        'name'            => $result['name'],
        'sku'             => $result['sku'],
        'barcode'         => $result['barcode'],
        'imt_id'          => $result['imt_id'],
        'nm_id'           => $result['nm_id'],
        'model'           => $result['model'],
        'date'            => $result['date'],
        'error'           => $result['error'],
        'view'            => $view_wb,
        'status'          => $status
      );
    }

    if ($button_delete_no_create) {
      $data['button_delete_no_create'] = '<a href="' . $data['url_delete_no_create'] . '" class="btn btn-warning" data-toggle="tooltip" title="' . $this->language->get("text_delete_no_create") . '"  onclick="return confirm(\'' . $this->language->get('text_delete_no_created') . '\');"><i class="fa fa-trash"></i></a>';
    } else {
      $data['button_delete_no_create'] = '';
    }

    if (isset($this->request->post['selected'])) {
      $data['selected'] = (array)$this->request->post['selected'];
    } else {
      $data['selected'] = array();
    }

    $url = '';

    if (isset($this->request->get['filter_name'])) {
      $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_barcode'])) {
      $url .= '&filter_barcode=' . urlencode(html_entity_decode($this->request->get['filter_barcode'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_sku'])) {
      $url .= '&filter_sku=' . urlencode(html_entity_decode($this->request->get['filter_sku'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_nm'])) {
      $url .= '&filter_nm=' . urlencode(html_entity_decode($this->request->get['filter_nm'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_model'])) {
      $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_category'])) {
      $url .= '&filter_category=' . $this->request->get['filter_category'];
    }

    if ($order == 'ASC') {
      $url .= '&order=DESC';
    } else {
      $url .= '&order=ASC';
    }

    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }

    $data['sort_name'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, true);
    $data['sort_sku'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'] . '&sort=p.sku' . $url, true);
    $data['sort_model'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, true);
    $data['sort_status'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, true);
    $data['sort_date'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'] . '&sort=p.date' . $url, true);
    $data['sort_order'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, true);
    $data['url_supplies'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_supplies/pass&request=0&pass=' . $this->config->get('cdl_wildberries_pass_supplies');
    $data['url_statistics'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_statistics&pass=' . $this->config->get('cdl_wildberries_pass');

    $url = '';

    if (isset($this->request->get['filter_name'])) {
      $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_barcode'])) {
      $url .= '&filter_barcode=' . urlencode(html_entity_decode($this->request->get['filter_barcode'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_model'])) {
      $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_sku'])) {
      $url .= '&filter_sku=' . urlencode(html_entity_decode($this->request->get['filter_sku'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_nm'])) {
      $url .= '&filter_nm=' . urlencode(html_entity_decode($this->request->get['filter_nm'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_category'])) {
      $url .= '&filter_category=' . $this->request->get['filter_category'];
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
    $pagination->url = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

    $data['filter_name'] = $filter_name;
    $data['filter_barcode'] = $filter_barcode;
    $data['filter_sku'] = $filter_sku;
    $data['filter_nm'] = $filter_nm;
    $data['filter_model'] = $filter_model;
    $data['filter_status'] = $filter_status;
    $data['filter_category'] = $filter_category;

    $data['sort'] = $sort;
    $data['order'] = $order;

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('module/cdl_wildberries_product.tpl', $data));
  }

  public function deleteNoCreate()
  {
    $this->load->model('module/cdl_wildberries');
    $this->model_module_cdl_wildberries->deleteNoCreate();
    $this->response->redirect($this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'], 'SSL'));
  }

  public function clear() {
		if (!$this->user->hasPermission('modify', 'module/cdl_wildberries')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			$file = DIR_LOGS . 'cdl_wildberries.log';
			$handle = fopen($file, 'w+');
			fclose($handle);
			$this->session->data['success'] = 'Лог очищен';
		}
		$this->response->redirect($this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], 'SSL'));
	}

  // Список заказов
	public function orders() {

		$this->load->language('module/cdl_wildberries');
		$this->document->setTitle($this->language->get('heading_title_order'));
		$this->load->model('module/cdl_wildberries');
		$this->load->model('setting/setting');
    $this->load->model('tool/image');
    $this->document->addScript('view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
    $this->document->addStyle('view/javascript/jquery/magnific/magnific-popup.css');

		if (isset($this->request->get['filter_wb_order_id'])) {
			$filter_wb_order_id = $this->request->get['filter_wb_order_id'];
		} else {
			$filter_wb_order_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = 100;
		}
		if (isset($this->request->get['filter_user_status'])) {
			$filter_user_status = $this->request->get['filter_user_status'];
		} else {
			$filter_user_status = null;
		}
		if (isset($this->request->get['filter_barcode'])) {
			$filter_barcode = $this->request->get['filter_barcode'];
		} else {
			$filter_barcode = null;
		}
    if (isset($this->request->get['filter_sticker'])) {
			$filter_sticker = $this->request->get['filter_sticker'];
		} else {
			$filter_sticker = null;
		}
		if (isset($this->request->get['filter_shipment_date'])) {
			$filter_shipment_date = $this->request->get['filter_shipment_date'];
		} else {
			$filter_shipment_date = null;
		}
    if (isset($this->request->get['filter_date_created'])) {
			$filter_date_created = $this->request->get['filter_date_created'];
		} else {
			$filter_date_created = null;
		}
    if (isset($this->request->get['filter_supplies'])) {
			$filter_supplies = $this->request->get['filter_supplies'];
		} else {
			$filter_supplies = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_created';
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

		if (isset($this->request->get['filter_wb_order_id'])) {
			$url .= '&filter_wb_order_id=' . urlencode(html_entity_decode($this->request->get['filter_wb_order_id'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_shipment_date'])) {
			$url .= '&filter_shipment_date=' . urlencode(html_entity_decode($this->request->get['filter_shipment_date'], ENT_QUOTES, 'UTF-8'));
		}
    if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . urlencode(html_entity_decode($this->request->get['filter_date_created'], ENT_QUOTES, 'UTF-8'));
		}
    if (isset($this->request->get['filter_supplies'])) {
			$url .= '&filter_supplies=' . urlencode(html_entity_decode($this->request->get['filter_supplies'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		} else {
      $url .= '&filter_status=100';
    }
		if (isset($this->request->get['filter_user_status'])) {
			$url .= '&filter_user_status=' . urlencode(html_entity_decode($this->request->get['filter_user_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_barcode'])) {
			$url .= '&filter_barcode=' . urlencode(html_entity_decode($this->request->get['filter_barcode'], ENT_QUOTES, 'UTF-8'));
		}
    if (isset($this->request->get['filter_sticker'])) {
			$url .= '&filter_sticker=' . urlencode(html_entity_decode($this->request->get['filter_sticker'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_orders_wb'),
			'href' => $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title_order'] = $this->language->get('heading_title_order');
		$data['text_orders_wb'] = $this->language->get('text_orders_wb');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_error'] = $this->language->get('text_error');
		$data['text_wb_order_id'] = $this->language->get('text_wb_order_id');
		$data['text_barcode'] = $this->language->get('text_barcode');
		$data['text_alert_ms_admin'] = $this->language->get('text_alert_ms_admin');
		$data['text_alert_ms_del'] = $this->language->get('text_alert_ms_del');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_menu'] = $this->language->get('text_menu');
		$data['button_return_module'] = $this->language->get('button_return_module');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_attributes'] = $this->language->get('text_attributes');
		$data['text_rima'] = $this->language->get('text_rima');
		$data['text_packing'] = $this->language->get('text_packing');
		$data['text_supplies'] = $this->language->get('text_supplies');
    $data['text_sticker_number'] = $this->language->get('text_sticker_number');
    $data['text_supplies'] = $this->language->get('text_supplies');
    $data['text_created'] = $this->language->get('text_created');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_user_status'] = $this->language->get('entry_user_status');
		$data['entry_shipment_date'] = $this->language->get('entry_shipment_date');

    $data['column_date'] = $this->language->get('column_date');
    $data['column_name'] = $this->language->get('column_name');
		$data['column_image'] = $this->language->get('column_image');
    $data['column_status'] = $this->language->get('column_status');
    $data['column_user_status'] = $this->language->get('column_user_status');

		$data['new'] = $this->language->get('new');
		$data['awaiting_packaging'] = $this->language->get('awaiting_packaging');
		$data['packaging'] = $this->language->get('packaging');
		$data['cancelled'] = $this->language->get('cancelled');
		$data['delivering'] = $this->language->get('delivering');
		$data['delivered'] = $this->language->get('delivered');
		$data['return'] = $this->language->get('return');
		$data['defect'] = $this->language->get('defect');
		$data['pvz'] = $this->language->get('pvz');
    $data['fbo'] = $this->language->get('fbo');

		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$data['pass'] = $this->config->get('cdl_wildberries_pass');

    $data['url_packing'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_order/pass&request=packorders';
    $data['url_orders'] =  HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_order/pass&request=orders&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_sticker'] =  HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_order/printsticker';
    $data['url_delete'] =  HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_order/pass&request=deleteorder&pass=' . $this->config->get('cdl_wildberries_pass');
    $data['url_rima'] = $this->url->link('module/cdl_wildberries/rima', 'token=' . $this->session->data['token'], 'SSL');
		$data['url_attributes'] = $this->url->link('module/cdl_wildberries/attributes', 'token=' . $this->session->data['token'], 'SSL');
		$data['url_product'] = $this->url->link('module/cdl_wildberries/product', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('module/cdl_wildberries', 'token=' . $this->session->data['token'], 'SSL');
    $data['url_supplies'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_supplies/pass&request=0&pass=' . $this->config->get('cdl_wildberries_pass_supplies');
    $data['url_statistics'] = HTTPS_CATALOG . 'index.php?route=module/cdl_wildberries_statistics&pass=' . $this->config->get('cdl_wildberries_pass');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['statuses'] = array(
      '100'        => '',
			'0'          => $data['new'],
			'1'          => $data['awaiting_packaging'],
      '2'          => $data['packaging'],
      '3'          => $data['cancelled'],
      '99'         => $data['fbo']
		);

		$data['user_statuses'] = array(
      ''           => '',
      // 'delivering' => $data['delivering'],
			'1'          => $data['cancelled'],
			'2'          => $data['delivered'],
			'3'          => $data['return'],
			'4'          => $data['pvz'],
			'5'          => $data['defect']
		);

		$filter_data = array(
			'filter_wb_order_id'   	=> $filter_wb_order_id,
			'filter_shipment_date'	=> $filter_shipment_date,
      'filter_date_created'	  => $filter_date_created,
      'filter_supplies'	      => $filter_supplies,
			'filter_status'   			=> $filter_status,
			'filter_user_status'    => $filter_user_status,
			'filter_barcode'   			=> $filter_barcode,
      'filter_sticker'   			=> $filter_sticker,
			'sort'           				=> $sort,
			'order'           			=> $order,
			'start'           			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           			=> $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_module_cdl_wildberries->getTotalOrders($filter_data);
		$results = $this->model_module_cdl_wildberries->getOrders($filter_data);

		$data['postings'] = array();
		foreach ($results as $result) {

			if ($result['shipment_date']) {
				$shipment_date = date('d-m-Y H:i', strtotime($result['shipment_date']));
			}
      $date_created = date('d-m-Y H:i', strtotime($result['date_created']));
      if ($result['date_update'] != '0000-00-00 00:00:00') {
        $date_update = date('d-m-Y H:i', strtotime($result['date_update']));
      } else {
        $date_update = '';
      }

      $product_wb = $this->model_module_cdl_wildberries->getProductOcByBarcodeWb($result['barcode']);

      if (empty($product_wb['image'])) {
        $product_wb['image'] = 'image/no_image.png';
      }
      if ($product_wb['image']) {
        $thumb = $this->model_tool_image->resize($product_wb['image'], 30, 30);
      } else {
        $thumb = '';
      }
      if ($product_wb['image']) {
        $image = $this->model_tool_image->resize($product_wb['image'], 500, 500);
      } else {
        $image = '';
      }

      if ($result['user_status'] == 0) {
        $result['user_status'] = '';
      }
      if (empty($product_wb['name'])) {
        $name = 'Товар с ШК ' . $result['barcode'] . ' не найден!';
      } else {
        $name = $product_wb['name'];
      }

			$data['postings'][] = array(
				'wb_order_id'   => $result['wb_order_id'],
        'image'         => $image,
        'thumb'         => $thumb,
        'product_name'  => $name,
        'price'         => $result['total_price'] / 100,
				'status' 			  => $data['statuses'][$result['status']],
				'user_status' 	=> $data['user_statuses'][$result['user_status']],
				'shipment_date' => $shipment_date,
        'date_created'  => $date_created,
        'date_update'   => $date_update,
        'supplies'      => $result['supplie']
			);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_wb_order_id'])) {
			$url .= '&filter_wb_order_id=' . urlencode(html_entity_decode($this->request->get['filter_wb_order_id'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_shipment_date'])) {
			$url .= '&filter_shipment_date=' . urlencode(html_entity_decode($this->request->get['filter_shipment_date'], ENT_QUOTES, 'UTF-8'));
		}
    if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . urlencode(html_entity_decode($this->request->get['filter_date_created'], ENT_QUOTES, 'UTF-8'));
		}
    if (isset($this->request->get['filter_supplies'])) {
			$url .= '&filter_supplies=' . urlencode(html_entity_decode($this->request->get['filter_supplies'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		} else {
      $url .= '&filter_status=100';
    }
		if (isset($this->request->get['filter_user_status'])) {
			$url .= '&filter_user_status=' . urlencode(html_entity_decode($this->request->get['filter_user_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_barcode'])) {
			$url .= '&filter_barcode=' . urlencode(html_entity_decode($this->request->get['filter_barcode'], ENT_QUOTES, 'UTF-8'));
		}
    if (isset($this->request->get['filter_sticker'])) {
			$url .= '&filter_sticker=' . urlencode(html_entity_decode($this->request->get['filter_sticker'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_wb_order_id'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'] . '&sort=wb_order_id' . $url, true);
		$data['sort_status'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);
		$data['sort_user_status'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'] . '&sort=user_status' . $url, true);
		$data['sort_shipment_date'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'] . '&sort=shipment_date' . $url, true);
		$data['sort_order'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'] . '&sort=order' . $url, true);
    $data['sort_supplies'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'] . '&sort=supplie' . $url, true);
    $data['sort_date_created'] = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'] . '&sort=date_created' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_wb_order_id'])) {
			$url .= '&filter_wb_order_id=' . urlencode(html_entity_decode($this->request->get['filter_wb_order_id'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_shipment_date'])) {
			$url .= '&filter_shipment_date=' . urlencode(html_entity_decode($this->request->get['filter_shipment_date'], ENT_QUOTES, 'UTF-8'));
		}
    if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . urlencode(html_entity_decode($this->request->get['filter_date_created'], ENT_QUOTES, 'UTF-8'));
		}
    if (isset($this->request->get['filter_supplies'])) {
			$url .= '&filter_supplies=' . urlencode(html_entity_decode($this->request->get['filter_supplies'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		} else {
      $url .= '&filter_status=100';
    }
		if (isset($this->request->get['filter_user_status'])) {
			$url .= '&filter_user_status=' . urlencode(html_entity_decode($this->request->get['filter_user_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_barcode'])) {
			$url .= '&filter_barcode=' . urlencode(html_entity_decode($this->request->get['filter_barcode'], ENT_QUOTES, 'UTF-8'));
		}
    if (isset($this->request->get['filter_sticker'])) {
			$url .= '&filter_sticker=' . urlencode(html_entity_decode($this->request->get['filter_sticker'], ENT_QUOTES, 'UTF-8'));
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
		$pagination->url = $this->url->link('module/cdl_wildberries/orders', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_wb_order_id'] = $filter_wb_order_id;
		$data['filter_shipment_date'] = $filter_shipment_date;
    $data['filter_date_created'] = $filter_date_created;
    $data['filter_supplies'] = $filter_supplies;
		$data['filter_status'] = $filter_status;
		$data['filter_user_status'] = $filter_user_status;
		$data['filter_barcode'] = $filter_barcode;
    $data['filter_sticker'] = $filter_sticker;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('module/cdl_wildberries_orders.tpl', $data));
	}

  protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/cdl_wildberries')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		// if (!$this->request->post['cdl_market_campaign_id']) {
		// 	$this->error['error_campaign_id'] = $this->language->get('error_campaign_id');
		// }
		return !$this->error;
	}

  public function install()
  {
		$this->load->model('module/cdl_wildberries');
		$this->model_module_cdl_wildberries->install();
	}

	public function uninstall()
  {
    $this->load->model('module/cdl_wildberries');
		$this->model_module_cdl_wildberries->uninstall();
	}

  private function checkUpdate()
  {
    $url = 'https://shop.cdl-it.ru/index.php?route=extension/module/cdl_license/pass&pass=dmitricheremisin&ver=' . urlencode($this->config->get('cdl_wildberries_version')) . '&domain=' . HTTPS_SERVER . '&mod=51';
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
