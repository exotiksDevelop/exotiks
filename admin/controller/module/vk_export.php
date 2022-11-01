<?php
/**
 * Экспорт товаров ВКонтакте для Opencart 2.0-2.2
 * Официальный сайт vkexport.allex-p.ru
 * Официальная страница дополнения https://opencartforum.com/files/file/600-экспорт-товаров-вконтакте/
 * 
 * Данная копия модуля зарегистрирована на admin@exotiks.ru
 * 
 */
 
class ControllerModuleVKExport extends Controller {
    private $error = array(); 
    
    public function install() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('vk_export', array(
            'vk_export_mode' => 2,
            'vk_export_user_id' => '',
            'vk_export_access_token' => '',
            'vk_export_group_id' => '',
            'vk_export_mode_desc' => 1,
            'vk_export_desc_tpl' => "{name} {model}\n{price}\n{link}",
            'vk_export_wallpost_tpl' => "{name} {model}\n{price}\n{link}",
            'vk_export_market_product_desc_tpl' => "{name} {model}\n{price}\n{link}",
            'vk_export_mode_comment' => 1,
            'vk_export_comment_tpl' => "{desc}",
            'vk_export_image_mode' => 1,
            'vk_export_album_name_mode' => 1,
            'vk_export_group_wallpost_from' => 2,
            'vk_export_wallpost_photos_count' => 1,
            'vk_export_market_photos_count' => 1,
            'vk_export_cron_user' => '',
            'vk_export_cron_pass' => '',
            'vk_export_autoexport' => 0,
            'vk_export_debug_mode' => 0,
            'vk_export_products_per_page' => 20,
            'vk_export_only_instock' => 1,
            'vk_export_only_enabled' => 1,
            'vk_export_num_products_for_cron' => 20,
            'vk_export_market_num_products_for_cron' => 20,
            'vk_export_http_catalog' => '',
            'vk_export_cron_wallpost_max' => 2,
            'vk_export_attributes_tpl' => '{name}: {value}',
            'vk_export_attributes_delimeter' => ',',
            'vk_export_vkcc' => 0,
            'vk_export_autoexport_category' => '',
            'vk_export_autoexport_category_wall' => '',
            'vk_export_market_autoexport_category' => '',
            'vk_export_group_photo_comment_from' => 1,
            'vk_export_user_replacements_keys' => '',
            'vk_export_user_replacements_values' => '',
            'vk_export_db_version' => '4.7.5',
            'vk_export_wall_unique' => 0,
            'vk_export_album_only' => 1,
            'vk_export_photos_count' => 1,
            'vk_export_cron_delete_out_of_stock' => 0,
            'vk_export_market_cron_action_out_of_stock' => 'not_avaible',
            'vk_export_cron_delete_disabled' => 0,
            'vk_export_market_cron_action_disabled' => 'not_avaible',
            'vk_export_wall_export_services' => '',
            'vk_export_root_cat' => 0,
            'vk_export_license_status' => 0,
            'vk_export_ckapseci' => '',
            'vk_export_cekfnbr' => '',
            'vk_export_register_key' => '',
            'vk_export_license_key' => 'b7b4042e77f400ab8ef4adc283209a04',
            'vk_export_license_login' => 'vildan90',
            'vk_export_license_email' => 'admin@exotiks.ru',
            'vk_export_create_market_albums' => 1,
            'vk_export_num_products_for_cron_market_update' => 20,
            'vk_export_num_products_for_cron_albums_update' => 20,
            'vk_export_delete_market_copies' => 1,
            'vk_export_column_albums' => 1,
            'vk_export_column_wall' => 1,
            'vk_export_column_market' => 1,
            'vk_export_price_coef' => 1,
        ));
        
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "vk_export_album` (
                              `category_id` int(11) NOT NULL, 
                              `vk_album_id` varchar(32) NOT NULL, 
                              `mode` ENUM('user','group') NOT NULL, 
                              `vk_market_category_id` int(11) NOT NULL,
                              `vk_market_album_id` int(11) NOT NULL,
                              PRIMARY KEY (`category_id`,`vk_album_id`,`mode`)
                            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "vk_export_market` (
                              `category_id` int(11) NOT NULL, 
                              `vk_market_category_id` int(11) NOT NULL, 
                              `vk_market_album_id` int(11) NOT NULL,
                              PRIMARY KEY (`category_id`)
                            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
                           
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "vk_export_photo` (
                              `product_id` int(11) NOT NULL, 
                              `vk_photo_id` varchar(32) NOT NULL,
                              `category_id` int(11) NOT NULL,
                              `date` DATETIME NOT NULL,
                              `location` ENUM( 'albums', 'wall', 'market' ) NOT NULL,
                              PRIMARY KEY (`product_id`,`vk_photo_id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8");                                                         
    }
    
    public function index() {
        $this->load->language('module/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        
        // сохранение настроек
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->post['vk_export_mode_desc'])) {
                $this->request->post['vk_export_mode_desc'] = 0;
            }
            if (!isset($this->request->post['vk_export_mode_comment'])) {
                $this->request->post['vk_export_mode_comment'] = 0;
            }
            if (!isset($this->request->post['vk_export_autoexport'])) {
                $this->request->post['vk_export_autoexport'] = 0;
            }
            if (!isset($this->request->post['vk_export_only_instock'])) {
                $this->request->post['vk_export_only_instock'] = 0;
            }
            if (!isset($this->request->post['vk_export_only_enabled'])) {
                $this->request->post['vk_export_only_enabled'] = 0;
            }
            if (!isset($this->request->post['vk_export_vkcc'])) {
                $this->request->post['vk_export_vkcc'] = 0;
            }
            if (!isset($this->request->post['vk_export_wall_only_specials'])) {
                $this->request->post['vk_export_wall_only_specials'] = 0;
            }
            if (!isset($this->request->post['vk_export_albums_only_specials'])) {
                $this->request->post['vk_export_albums_only_specials'] = 0;
            }
            if (!isset($this->request->post['vk_export_market_only_specials'])) {
                $this->request->post['vk_export_market_only_specials'] = 0;
            }
            if (!isset($this->request->post['vk_export_wall_unique'])) {
                $this->request->post['vk_export_wall_unique'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_model'])) {
                $this->request->post['vk_export_column_model'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_price'])) {
                $this->request->post['vk_export_column_price'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_quantity'])) {
                $this->request->post['vk_export_column_quantity'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_status'])) {
                $this->request->post['vk_export_column_status'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_date_added'])) {
                $this->request->post['vk_export_column_date_added'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_producer'])) {
                $this->request->post['vk_export_column_producer'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_albums'])) {
                $this->request->post['vk_export_column_albums'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_wall'])) {
                $this->request->post['vk_export_column_wall'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_market'])) {
                $this->request->post['vk_export_column_market'] = 0;
            }
            if (!isset($this->request->post['vk_export_column_id'])) {
                $this->request->post['vk_export_column_id'] = 0;
            }
            if (!isset($this->request->post['vk_export_album_only'])) {
                $this->request->post['vk_export_album_only'] = 0;
            }
            if (!isset($this->request->post['vk_export_cron_delete_out_of_stock'])) {
                $this->request->post['vk_export_cron_delete_out_of_stock'] = 0;
            }
            if (!isset($this->request->post['vk_export_cron_delete_disabled'])) {
                $this->request->post['vk_export_cron_delete_disabled'] = 0;
            }
            if (!isset($this->request->post['vk_export_market_cron_action_out_of_stock'])) {
                $this->request->post['vk_export_market_cron_action_out_of_stock'] = 'not_avaible';
            }
            if (!isset($this->request->post['vk_export_market_cron_action_disabled'])) {
                $this->request->post['vk_export_market_cron_action_disabled'] = 'not_avaible';
            }
            if (!isset($this->request->post['vk_export_root_cat'])) {
                $this->request->post['vk_export_root_cat'] = 0;
            }
            if (!isset($this->request->post['vk_export_create_market_albums'])) {
                $this->request->post['vk_export_create_market_albums'] = 0;
            }
            if (!isset($this->request->post['vk_export_delete_market_copies'])) {
                $this->request->post['vk_export_delete_market_copies'] = 0;
            }
            if (!isset($this->request->post['vk_export_access_token']) || (isset($this->request->post['vk_export_access_token']) && !trim($this->request->post['vk_export_access_token']))) {
               $this->request->post['vk_export_access_token'] = $this->config->get('vk_export_access_token');
            }
            else {
                if (preg_match('/access_token=(.*?)&/', $this->request->post['vk_export_access_token'], $m)) {
                    $this->request->post['vk_export_access_token'] = $m[1];
                }
            }
            
            if (!trim($this->request->post['vk_export_cron_pass'])) {
               $this->request->post['vk_export_cron_pass'] = $this->config->get('vk_export_cron_pass');
            }
            
            $this->request->post['vk_export_license_key'] = $this->config->get('vk_export_license_key');
            $this->request->post['vk_export_license_login'] = $this->config->get('vk_export_license_login');
            $this->request->post['vk_export_license_status'] = $this->config->get('vk_export_license_status');
            $this->request->post['vk_export_license_email'] = $this->config->get('vk_export_license_email');
            $this->request->post['vk_export_ckapseci'] = $this->config->get('vk_export_ckapseci');
            $this->request->post['vk_export_cekfnbr'] = $this->config->get('vk_export_cekfnbr');
            $this->request->post['vk_export_register_key'] = $this->config->get('vk_export_register_key');
            
            $this->model_setting_setting->editSetting('vk_export', $this->request->post);        
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            // удалить куки
            if (file_exists(DIR_SYSTEM . 'cache/.vkPhotoLoaderCookie')) {
                unlink(DIR_SYSTEM . 'cache/.vkPhotoLoaderCookie');
            }
            // удалить лог
            if (!$this->request->post['vk_export_debug_mode']) {
                if (file_exists(DIR_SYSTEM . 'logs/vkExportLog.zip')) {
                    unlink(DIR_SYSTEM . 'logs/vkExportLog.zip');
                }
                $files = glob(DIR_SYSTEM . 'logs/.vkExportLog_*');
                if ($files) {
                    foreach ($files as $file) {
                        unlink($file);
                    }
                }
            }
            
            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));

        }
                
        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['warning_geo'])) {
            $data['error_warning_geo'] = $this->error['warning_geo'];
        } else {
            $data['error_warning_geo'] = '';
        }
            
          $data['breadcrumbs'] = array();

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => false
           );

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => ' :: '
           );
        
           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/vk_export', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => ' :: '
           );
        
        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_albums'] = $this->language->get('tab_albums');
        $data['tab_wall'] = $this->language->get('tab_wall');
        $data['tab_market'] = $this->language->get('tab_market');
        $data['tab_cron'] = $this->language->get('tab_cron');
        $data['tab_vk'] = $this->language->get('tab_vk');
        $data['tab_license'] = $this->language->get('tab_license');
        $data['text_attributes_delimeter_help'] = $this->language->get('text_attributes_delimeter_help');
        $data['text_var_list'] = $this->language->get('text_var_list');
        $data['export_tpl_info'] = $this->language->get('export_tpl_info');
        $data['text_cron_notice'] = $this->language->get('text_cron_notice');
        $data['entry_shared_settings'] = $this->language->get('entry_shared_settings');
        $data['entry_albums_settings'] = $this->language->get('entry_albums_settings');
        $data['entry_wall_settings'] = $this->language->get('entry_wall_settings');
        $data['entry_market_settings'] = $this->language->get('entry_market_settings');
        $data['entry_cron_setup'] = $this->language->get('entry_cron_setup');
        $data['entry_market_product_desc_tpl'] = $this->language->get('entry_market_product_desc_tpl');
        $data['entry_market_photos_count'] = $this->language->get('entry_market_photos_count');
        
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['entry_export_mode'] = $this->language->get('entry_export_mode');
        $data['text_export_mode_user'] = $this->language->get('text_export_mode_user');
        $data['text_export_mode_group'] = $this->language->get('text_export_mode_group');
        $data['text_export_mode_both'] = $this->language->get('text_export_mode_both');
        $data['entry_user_id'] = $this->language->get('entry_user_id');
        $data['text_user_id_example'] = $this->language->get('text_user_id_example');
        $data['entry_group_id'] = $this->language->get('entry_group_id');
        $data['text_group_owner'] = $this->language->get('text_group_owner');
        $data['entry_desc_tpl'] = $this->language->get('entry_desc_tpl');
        $data['text_desc_tpl'] = $this->language->get('text_desc_tpl');
        $data['entry_mode_desc'] = $this->language->get('entry_mode_desc');
        $data['entry_mode_comment'] = $this->language->get('entry_mode_comment');
        $data['entry_comment_tpl'] = $this->language->get('entry_comment_tpl');
        $data['entry_phone_number'] = $this->language->get('entry_phone_number');
        $data['text_phone_number'] = $this->language->get('text_phone_number');
        $data['entry_image_mode'] = $this->language->get('entry_image_mode');
        $data['text_image_orig'] = $this->language->get('text_image_orig');
        $data['text_image_resize'] = $this->language->get('text_image_resize');
        $data['entry_album_name_mode'] = $this->language->get('entry_album_name_mode');
        $data['entry_wallpost_tpl'] = $this->language->get('entry_wallpost_tpl');
        $data['text_album_name_orig'] = $this->language->get('text_album_name_orig');
        $data['text_album_name_path'] = sprintf($this->language->get('text_album_name_path'), $this->language->get('text_separator'));
        $data['entry_group_wallpost_from'] = $this->language->get('entry_group_wallpost_from');
        $data['text_wallpost_from_group'] = $this->language->get('text_wallpost_from_group');
        $data['text_wallpost_from_user'] = $this->language->get('text_wallpost_from_user');
        $data['entry_wallpost_photos_count'] = $this->language->get('entry_wallpost_photos_count');
        $data['entry_photos_count'] = $this->language->get('entry_photos_count');
        $data['text_all'] = $this->language->get('text_all');
        $data['entry_turn_on_autoexport'] = $this->language->get('entry_turn_on_autoexport');
        $data['text_cron_user_help'] = $this->language->get('text_cron_user_help');
        $data['entry_cron_user'] = $this->language->get('entry_cron_user');
        $data['entry_cron_pass'] = $this->language->get('entry_cron_pass');
        $data['entry_debug_mode'] = $this->language->get('entry_debug_mode');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_products_per_page'] = $this->language->get('entry_products_per_page');
        $data['entry_export_only_instock'] = $this->language->get('entry_export_only_instock');
        $data['entry_export_only_enabled'] = $this->language->get('entry_export_only_enabled');
        $data['entry_num_products_for_cron'] = $this->language->get('entry_num_products_for_cron');
        $data['entry_http_catalog'] = $this->language->get('entry_http_catalog');
        $data['text_desc_http_catalog'] = $this->language->get('text_desc_http_catalog');
        $data['entry_num_wallpost_for_cron'] = $this->language->get('entry_num_wallpost_for_cron');
        $data['entry_attributes_tpl'] = $this->language->get('entry_attributes_tpl');
        $data['text_attributes_tpl'] = $this->language->get('text_attributes_tpl');
        $data['entry_attributes_delimeter'] = $this->language->get('entry_attributes_delimeter');
        $data['entry_vkcc'] = $this->language->get('entry_vkcc');
        $data['text_vkcc_help'] = $this->language->get('text_vkcc_help');
        $data['entry_category_autoexport'] = $this->language->get('entry_category_autoexport');
        $data['entry_category_autoexport_wall'] = $this->language->get('entry_category_autoexport_wall');
        $data['text_select_all'] = $this->language->get('text_select_all');
        $data['text_unselect_all'] = $this->language->get('text_unselect_all');
        $data['text_desc_tpl_wall'] = $this->language->get('text_desc_tpl_wall');
        $data['entry_group_photo_comment_from'] = $this->language->get('entry_group_photo_comment_from');
        $data['entry_user_replacements'] = $this->language->get('entry_user_replacements');
        $data['text_search'] = $this->language->get('text_search');
        $data['text_replacement'] = $this->language->get('text_replacement');
        $data['text_replacements_desc'] = $this->language->get('text_replacements_desc');
        $data['entry_export_wall_only_specials'] = $this->language->get('entry_export_wall_only_specials');
        $data['entry_export_albums_only_specials'] = $this->language->get('entry_export_albums_only_specials');
        $data['entry_export_market_only_specials'] = $this->language->get('entry_export_market_only_specials');
        $data['entry_export_wall_unique'] = $this->language->get('entry_export_wall_unique');
        $data['entry_colums'] = $this->language->get('entry_colums');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_status'] = $this->language->get('text_status');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_producer'] = $this->language->get('text_producer');
        $data['text_albums'] = $this->language->get('text_albums');
        $data['text_wall'] = $this->language->get('text_wall');
        $data['text_market'] = $this->language->get('text_market');
        $data['entry_album_only'] = $this->language->get('entry_album_only');
        $data['entry_delete_out_of_stock'] = $this->language->get('entry_delete_out_of_stock');
        $data['entry_delete_disabled'] = $this->language->get('entry_delete_disabled');
        $data['entry_wall_export_services'] = $this->language->get('entry_wall_export_services');
        $data['entry_wall_export_services_help'] = $this->language->get('entry_wall_export_services_help');
        $data['entry_photos_count_help'] = $this->language->get('entry_photos_count_help');
        $data['text_account_step1'] = $this->language->get('text_account_step1');
        $data['text_account_step1_1'] = $this->language->get('text_account_step1_1');
        $data['text_account_step2'] = $this->language->get('text_account_step2');
        $data['text_account_step3'] = $this->language->get('text_account_step3');
        $data['text_account_step4'] = $this->language->get('text_account_step4');
        $data['text_account_step5'] = $this->language->get('text_account_step5');
        $data['text_account_step6'] = $this->language->get('text_account_step6');
        $data['text_account_step7'] = $this->language->get('text_account_step7');
        $data['text_account_setup'] = $this->language->get('text_account_setup');
        $data['entry_export_root_cat'] = $this->language->get('entry_export_root_cat');
        $data['text_account_setup_desc'] = $this->language->get('text_account_setup_desc');
        $data['text_buy_module'] = $this->language->get('text_buy_module');
        $data['text_setup_done'] = $this->language->get('text_setup_done');
        $data['entry_delete_out_of_stock_desc'] = $this->language->get('entry_delete_out_of_stock_desc');
        $data['entry_create_market_albums'] = $this->language->get('entry_create_market_albums');
        $data['entry_market_category_autoexport'] = $this->language->get('entry_market_category_autoexport');
        $data['entry_market_action_out_of_stock'] = $this->language->get('entry_market_action_out_of_stock');
        $data['entry_market_action_out_of_stock_desc'] = $this->language->get('entry_market_action_out_of_stock_desc');
        $data['entry_status_not_avaible'] = $this->language->get('entry_status_not_avaible');
        $data['entry_delete'] = $this->language->get('entry_delete');
        $data['entry_market_action_disabled'] = $this->language->get('entry_market_action_disabled');
        $data['entry_num_products_for_cron_market_update'] = $this->language->get('entry_num_products_for_cron_market_update');
        $data['entry_num_products_for_cron_albums_update'] = $this->language->get('entry_num_products_for_cron_albums_update');
        $data['entry_delete_market_copies'] = $this->language->get('entry_delete_market_copies');
        $data['entry_price_coef'] = $this->language->get('entry_price_coef');
        
        $data['action'] = $this->url->link('module/vk_export', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $data['ckapseci'] = $this->url->link('extension/vk_export/ckapseci', 'token=' . $this->session->data['token'], 'SSL');
        $data['cekfnbr'] = $this->url->link('extension/vk_export/cekfnbr', 'token=' . $this->session->data['token'], 'SSL');

        $data['token'] = $this->session->data['token'];
        
        $this->load->model('setting/setting');

        
        if (isset($this->request->post['vk_export_mode'])) {
            $data['vk_export_mode'] = $this->request->post['vk_export_mode'];
        } else {
            $data['vk_export_mode'] = $this->config->get('vk_export_mode');
        }
        
        if (isset($this->request->post['vk_export_user_id'])) {
            $data['vk_export_user_id'] = $this->request->post['vk_export_user_id'];
        } else {
            $data['vk_export_user_id'] = $this->config->get('vk_export_user_id');
        }
        
        if (isset($this->request->post['vk_export_group_id'])) {
            $data['vk_export_group_id'] = $this->request->post['vk_export_group_id'];
        } else {
            $data['vk_export_group_id'] = $this->config->get('vk_export_group_id');
        }
        
        if (isset($this->request->post['vk_export_mode_desc'])) {
            $data['vk_export_mode_desc'] = $this->request->post['vk_export_mode_desc'];
        } else {
            $data['vk_export_mode_desc'] = $this->config->get('vk_export_mode_desc');
        }
        
        if (isset($this->request->post['vk_export_desc_tpl'])) {
            $data['vk_export_desc_tpl'] = $this->request->post['vk_export_desc_tpl'];
        } else {
            $data['vk_export_desc_tpl'] = $this->config->get('vk_export_desc_tpl');
        }
        
        if (isset($this->request->post['vk_export_mode_comment'])) {
            $data['vk_export_mode_comment'] = $this->request->post['vk_export_mode_comment'];
        } else {
            $data['vk_export_mode_comment'] = $this->config->get('vk_export_mode_comment');
        }
        
        if (isset($this->request->post['vk_export_comment_tpl'])) {
            $data['vk_export_comment_tpl'] = $this->request->post['vk_export_comment_tpl'];
        } else {
            $data['vk_export_comment_tpl'] = $this->config->get('vk_export_comment_tpl');
        }
        
        if (isset($this->request->post['vk_export_image_mode'])) {
            $data['vk_export_image_mode'] = $this->request->post['vk_export_image_mode'];
        } else {
            $data['vk_export_image_mode'] = $this->config->get('vk_export_image_mode');
        }
        
        if (isset($this->request->post['vk_export_album_name_mode'])) {
            $data['vk_export_album_name_mode'] = $this->request->post['vk_export_album_name_mode'];
        } else {
            $data['vk_export_album_name_mode'] = $this->config->get('vk_export_album_name_mode');
        }
        
        if (isset($this->request->post['vk_export_wallpost_tpl'])) {
            $data['vk_export_wallpost_tpl'] = $this->request->post['vk_export_wallpost_tpl'];
        } else {
            $data['vk_export_wallpost_tpl'] = $this->config->get('vk_export_wallpost_tpl');
        }
        
        if (isset($this->request->post['vk_export_market_product_desc_tpl'])) {
            $data['vk_export_market_product_desc_tpl'] = $this->request->post['vk_export_market_product_desc_tpl'];
        } else {
            $data['vk_export_market_product_desc_tpl'] = $this->config->get('vk_export_market_product_desc_tpl');
        }
        
        if (isset($this->request->post['vk_export_group_wallpost_from'])) {
            $data['vk_export_group_wallpost_from'] = $this->request->post['vk_export_group_wallpost_from'];
        } else {
            $data['vk_export_group_wallpost_from'] = $this->config->get('vk_export_group_wallpost_from');
        }
        
        if (isset($this->request->post['vk_export_wallpost_photos_count'])) {
            $data['vk_export_wallpost_photos_count'] = $this->request->post['vk_export_wallpost_photos_count'];
        } else {
            $data['vk_export_wallpost_photos_count'] = $this->config->get('vk_export_wallpost_photos_count');
        }
        
        if (isset($this->request->post['vk_export_market_photos_count'])) {
            $data['vk_export_market_photos_count'] = $this->request->post['vk_export_market_photos_count'];
        } else {
            $data['vk_export_market_photos_count'] = $this->config->get('vk_export_market_photos_count');
        }
        
        if (isset($this->request->post['vk_export_autoexport'])) {
            $data['vk_export_autoexport'] = $this->request->post['vk_export_autoexport'];
        } else {
            $data['vk_export_autoexport'] = $this->config->get('vk_export_autoexport');
        }
        
        if (isset($this->request->post['vk_export_cron_user'])) {
            $data['vk_export_cron_user'] = $this->request->post['vk_export_cron_user'];
        } else {
            $data['vk_export_cron_user'] = $this->config->get('vk_export_cron_user');
        }
        
        if (isset($this->request->post['vk_export_debug_mode'])) {
            $data['vk_export_debug_mode'] = $this->request->post['vk_export_debug_mode'];
        } else {
            $data['vk_export_debug_mode'] = $this->config->get('vk_export_debug_mode');
        }
        
        if (isset($this->request->post['vk_export_products_per_page'])) {
            $data['vk_export_products_per_page'] = $this->request->post['vk_export_products_per_page'];
        } else {
            $data['vk_export_products_per_page'] = $this->config->get('vk_export_products_per_page');
        }
        
        if (isset($this->request->post['vk_export_only_instock'])) {
            $data['vk_export_only_instock'] = $this->request->post['vk_export_only_instock'];
        } else {
            $data['vk_export_only_instock'] = $this->config->get('vk_export_only_instock');
        }
        
        if (isset($this->request->post['vk_export_only_new'])) {
            $data['vk_export_only_new'] = $this->request->post['vk_export_only_new'];
        } else {
            $data['vk_export_only_new'] = $this->config->get('vk_export_only_new');
        }
        
        if (isset($this->request->post['vk_export_only_enabled'])) {
            $data['vk_export_only_enabled'] = $this->request->post['vk_export_only_enabled'];
        } else {
            $data['vk_export_only_enabled'] = $this->config->get('vk_export_only_enabled');
        }
        
        if (isset($this->request->post['vk_export_num_products_for_cron'])) {
            $data['vk_export_num_products_for_cron'] = $this->request->post['vk_export_num_products_for_cron'];
        } else {
            $data['vk_export_num_products_for_cron'] = $this->config->get('vk_export_num_products_for_cron');
        }
        
        if (isset($this->request->post['vk_export_market_num_products_for_cron'])) {
            $data['vk_export_market_num_products_for_cron'] = $this->request->post['vk_export_market_num_products_for_cron'];
        } else {
            $data['vk_export_market_num_products_for_cron'] = $this->config->get('vk_export_market_num_products_for_cron');
        }
        
        if (isset($this->request->post['vk_export_http_catalog'])) {
            $data['vk_export_http_catalog'] = $this->request->post['vk_export_http_catalog'];
        } else {
            $data['vk_export_http_catalog'] = $this->config->get('vk_export_http_catalog');
        }
        
        if (isset($this->request->post['vk_export_cron_wallpost_max'])) {
            $data['vk_export_cron_wallpost_max'] = $this->request->post['vk_export_cron_wallpost_max'];
        } else {
            $data['vk_export_cron_wallpost_max'] = $this->config->get('vk_export_cron_wallpost_max');
        }
        
        if (isset($this->request->post['vk_export_attributes_tpl'])) {
            $data['vk_export_attributes_tpl'] = $this->request->post['vk_export_attributes_tpl'];
        } else {
            $data['vk_export_attributes_tpl'] = $this->config->get('vk_export_attributes_tpl');
        }
        
        if (isset($this->request->post['vk_export_attributes_delimeter'])) {
            $data['vk_export_attributes_delimeter'] = $this->request->post['vk_export_attributes_delimeter'];
        } else {
            $data['vk_export_attributes_delimeter'] = $this->config->get('vk_export_attributes_delimeter');
        }
        
        if (isset($this->request->post['vk_export_vkcc'])) {
            $data['vk_export_vkcc'] = $this->request->post['vk_export_vkcc'];
        } else {
            $data['vk_export_vkcc'] = $this->config->get('vk_export_vkcc');
        }
        
        if (isset($this->request->post['vk_export_albums_only_specials'])) {
            $data['vk_export_albums_only_specials'] = $this->request->post['vk_export_albums_only_specials'];
        } else {
            $data['vk_export_albums_only_specials'] = $this->config->get('vk_export_albums_only_specials');
        }
        
        if (isset($this->request->post['vk_export_market_only_specials'])) {
            $data['vk_export_market_only_specials'] = $this->request->post['vk_export_market_only_specials'];
        } else {
            $data['vk_export_market_only_specials'] = $this->config->get('vk_export_market_only_specials');
        }
        
        if (isset($this->request->post['vk_export_wall_only_specials'])) {
            $data['vk_export_wall_only_specials'] = $this->request->post['vk_export_wall_only_specials'];
        } else {
            $data['vk_export_wall_only_specials'] = $this->config->get('vk_export_wall_only_specials');
        }
        
        if (isset($this->request->post['vk_export_group_photo_comment_from'])) {
            $data['vk_export_group_photo_comment_from'] = $this->request->post['vk_export_group_photo_comment_from'];
        } else {
            $data['vk_export_group_photo_comment_from'] = $this->config->get('vk_export_group_photo_comment_from');
        }
        
        if (isset($this->request->post['vk_export_user_replacements_keys'])) {
            $data['vk_export_user_replacements_keys'] = $this->request->post['vk_export_user_replacements_keys'];
        } else {
            $data['vk_export_user_replacements_keys'] = $this->config->get('vk_export_user_replacements_keys');
        }
        
        if (isset($this->request->post['vk_export_user_replacements_values'])) {
            $data['vk_export_user_replacements_values'] = $this->request->post['vk_export_user_replacements_values'];
        } else {
            $data['vk_export_user_replacements_values'] = $this->config->get('vk_export_user_replacements_values');
        }
        
        if (isset($this->request->post['vk_export_wall_unique'])) {
            $data['vk_export_wall_unique'] = $this->request->post['vk_export_wall_unique'];
        } else {
            $data['vk_export_wall_unique'] = $this->config->get('vk_export_wall_unique');
        }
        
        if (isset($this->request->post['vk_export_column_model'])) {
            $data['vk_export_column_model'] = $this->request->post['vk_export_column_model'];
        } else {
            $data['vk_export_column_model'] = $this->config->get('vk_export_column_model');
        }
        
        if (isset($this->request->post['vk_export_column_price'])) {
            $data['vk_export_column_price'] = $this->request->post['vk_export_column_price'];
        } else {
            $data['vk_export_column_price'] = $this->config->get('vk_export_column_price');
        }
        
        if (isset($this->request->post['vk_export_column_quantity'])) {
            $data['vk_export_column_quantity'] = $this->request->post['vk_export_column_quantity'];
        } else {
            $data['vk_export_column_quantity'] = $this->config->get('vk_export_column_quantity');
        }
        
        if (isset($this->request->post['vk_export_column_status'])) {
            $data['vk_export_column_status'] = $this->request->post['vk_export_column_status'];
        } else {
            $data['vk_export_column_status'] = $this->config->get('vk_export_column_status');
        }
        
        if (isset($this->request->post['vk_export_column_id'])) {
            $data['vk_export_column_id'] = $this->request->post['vk_export_column_id'];
        } else {
            $data['vk_export_column_id'] = $this->config->get('vk_export_column_id');
        }
        
        if (isset($this->request->post['vk_export_column_date_added'])) {
            $data['vk_export_column_date_added'] = $this->request->post['vk_export_column_date_added'];
        } else {
            $data['vk_export_column_date_added'] = $this->config->get('vk_export_column_date_added');
        }
        
        if (isset($this->request->post['vk_export_column_producer'])) {
            $data['vk_export_column_producer'] = $this->request->post['vk_export_column_producer'];
        } else {
            $data['vk_export_column_producer'] = $this->config->get('vk_export_column_producer');
        }
        
        if (isset($this->request->post['vk_export_column_albums'])) {
            $data['vk_export_column_albums'] = $this->request->post['vk_export_column_albums'];
        } else {
            $data['vk_export_column_albums'] = $this->config->get('vk_export_column_albums');
        }
        
        if (isset($this->request->post['vk_export_column_wall'])) {
            $data['vk_export_column_wall'] = $this->request->post['vk_export_column_wall'];
        } else {
            $data['vk_export_column_wall'] = $this->config->get('vk_export_column_wall');
        }
        
        if (isset($this->request->post['vk_export_column_market'])) {
            $data['vk_export_column_market'] = $this->request->post['vk_export_column_market'];
        } else {
            $data['vk_export_column_market'] = $this->config->get('vk_export_column_market');
        }
        
        if (isset($this->request->post['vk_export_album_only'])) {
            $data['vk_export_album_only'] = $this->request->post['vk_export_album_only'];
        } else {
            $data['vk_export_album_only'] = $this->config->get('vk_export_album_only');
        }
        
        if (isset($this->request->post['vk_export_photos_count'])) {
            $data['vk_export_photos_count'] = $this->request->post['vk_export_photos_count'];
        } else {
            $data['vk_export_photos_count'] = $this->config->get('vk_export_photos_count');
        }
        
        if (isset($this->request->post['vk_export_cron_delete_out_of_stock'])) {
            $data['vk_export_cron_delete_out_of_stock'] = $this->request->post['vk_export_cron_delete_out_of_stock'];
        } else {
            $data['vk_export_cron_delete_out_of_stock'] = $this->config->get('vk_export_cron_delete_out_of_stock');
        }
        
        if (isset($this->request->post['vk_export_cron_delete_disabled'])) {
            $data['vk_export_cron_delete_disabled'] = $this->request->post['vk_export_cron_delete_disabled'];
        } else {
            $data['vk_export_cron_delete_disabled'] = $this->config->get('vk_export_cron_delete_disabled');
        }
        
        if (isset($this->request->post['vk_export_market_cron_action_disabled'])) {
            $data['vk_export_market_cron_action_disabled'] = $this->request->post['vk_export_market_cron_action_disabled'];
        } else {
            $data['vk_export_market_cron_action_disabled'] = $this->config->get('vk_export_market_cron_action_disabled');
        }
        
        if (isset($this->request->post['vk_export_market_cron_action_out_of_stock'])) {
            $data['vk_export_market_cron_action_out_of_stock'] = $this->request->post['vk_export_market_cron_action_out_of_stock'];
        } else {
            $data['vk_export_market_cron_action_out_of_stock'] = $this->config->get('vk_export_market_cron_action_out_of_stock');
        }
        
        if (isset($this->request->post['vk_export_wall_export_services'])) {
            $data['vk_export_wall_export_services'] = $this->request->post['vk_export_wall_export_services'];
        } else {
            $data['vk_export_wall_export_services'] = $this->config->get('vk_export_wall_export_services');
        }
        
        if (isset($this->request->post['vk_export_root_cat'])) {
            $data['vk_export_root_cat'] = $this->request->post['vk_export_root_cat'];
        } else {
            $data['vk_export_root_cat'] = $this->config->get('vk_export_root_cat');
        }
        
        if (isset($this->request->post['vk_export_create_market_albums'])) {
            $data['vk_export_create_market_albums'] = $this->request->post['vk_export_create_market_albums'];
        } else {
            $data['vk_export_create_market_albums'] = $this->config->get('vk_export_create_market_albums');
        }
        
        if (isset($this->request->post['vk_export_delete_market_copies'])) {
            $data['vk_export_delete_market_copies'] = $this->request->post['vk_export_delete_market_copies'];
        } else {
            $data['vk_export_delete_market_copies'] = $this->config->get('vk_export_delete_market_copies');
        }
        
        if (isset($this->request->post['vk_export_num_products_for_cron_market_update'])) {
            $data['vk_export_num_products_for_cron_market_update'] = $this->request->post['vk_export_num_products_for_cron_market_update'];
        } else {
            $data['vk_export_num_products_for_cron_market_update'] = $this->config->get('vk_export_num_products_for_cron_market_update');
        }
        
        if (isset($this->request->post['vk_export_num_products_for_cron_albums_update'])) {
            $data['vk_export_num_products_for_cron_albums_update'] = $this->request->post['vk_export_num_products_for_cron_albums_update'];
        } else {
            $data['vk_export_num_products_for_cron_albums_update'] = $this->config->get('vk_export_num_products_for_cron_albums_update');
        }
        
        if (isset($this->request->post['vk_export_price_coef'])) {
            $data['vk_export_price_coef'] = $this->request->post['vk_export_price_coef'];
        } else {
            $data['vk_export_price_coef'] = $this->config->get('vk_export_price_coef');
        }
        
        if (isset($this->request->post['vk_export_mm_class_id'])) {
            $data['vk_export_mm_class_id'] = $this->request->post['vk_export_mm_class_id'];
        } else {
            $data['vk_export_mm_class_id'] = $this->config->get('vk_export_mm_class_id');
        }
        
        if (isset($this->request->post['vk_export_gramm_class_id'])) {
            $data['vk_export_gramm_class_id'] = $this->request->post['vk_export_gramm_class_id'];
        } else {
            $data['vk_export_gramm_class_id'] = $this->config->get('vk_export_gramm_class_id');
        }
        
        // категории 
        $this->load->model('catalog/category');
        
        $categories = $this->model_catalog_category->getCategories(0);
        $categories_tmp = array('' => '');
        foreach ($categories as $category) {
            $categories_tmp[$category['category_id']] = $category['name'];
        }
        $categories = $categories_tmp;
        unset($categories_tmp);
        $data['categories'] = $categories;
        
        // категории автоэкспорта в альбомы
        if (isset($this->request->post['vk_export_autoexport_category'])) {
            $data['vk_export_autoexport_category'] = $this->request->post['vk_export_autoexport_category'];
        } else {
            $data['vk_export_autoexport_category'] = $this->config->get('vk_export_autoexport_category');
            if (!is_array($data['vk_export_autoexport_category'])) {
                $data['vk_export_autoexport_category'] = array();
            }
        }
        
        // категории автоэкспорта в товары
        if (isset($this->request->post['vk_export_market_autoexport_category'])) {
            $data['vk_export_market_autoexport_category'] = $this->request->post['vk_export_market_autoexport_category'];
        } else {
            $data['vk_export_market_autoexport_category'] = $this->config->get('vk_export_market_autoexport_category');
            if (!is_array($data['vk_export_market_autoexport_category'])) {
                $data['vk_export_market_autoexport_category'] = array();
            }
        }
        
        // категории автоэкспорта на стену
        if (isset($this->request->post['vk_export_autoexport_category_wall'])) {
            $data['vk_export_autoexport_category_wall'] = $this->request->post['vk_export_autoexport_category_wall'];
        } else {
            $data['vk_export_autoexport_category_wall'] = $this->config->get('vk_export_autoexport_category_wall');
            if (!is_array($data['vk_export_autoexport_category_wall'])) {
                $data['vk_export_autoexport_category_wall'] = array();
            }
        }
        
        // установка единиц измерений и веса
        $this->load->model('localisation/length_class');
        $this->load->model('localisation/weight_class');
        $data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
        
        // license
        $data['get_license_url'] = $this->url->link('module/vk_export/get_license', '', 'SSL');
        $data['license_key'] = $this->config->get('vk_export_license_key');
        $data['license_login'] = $this->config->get('vk_export_license_login');
        $data['license_status'] = $this->config->get('vk_export_license_status');
        $data['license_email'] = $this->config->get('vk_export_license_email');
                
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
                
        $this->response->setOutput($this->load->view('module/vk_export.tpl', $data));
    }
    
    public function get_license() {
        
        $url = 'https://vkexport.allex-p.ru/api/?action=register_license';
        $url .= '&hash=' . urlencode($this->config->get('vk_export_license_key'));
        $url .= '&site=' . urlencode($this->url->link('', '', 'SSL'));
        $url .= '&codesalt=' . urlencode(md5(HTTP_CATALOG . HTTPS_CATALOG));
        
        $res = miniCurl::getPage($url);
        
        $data = json_decode($res);
        
        if ($data->result == 'ok') {
            
            $this->db->query("UPDATE " . DB_PREFIX . "setting SET value = 1
                WHERE `code` = 'vk_export' AND `key` = 'vk_export_license_status'
            ");
            $this->db->query("UPDATE " . DB_PREFIX . "setting SET value = '" . $this->db->escape($data->vk_export_ckapseci) . "'
                WHERE `code` = 'vk_export' AND `key` = 'vk_export_ckapseci'
            ");
            $this->db->query("UPDATE " . DB_PREFIX . "setting SET value = '" . $this->db->escape($data->vk_export_cekfnbr) . "'
                WHERE `code` = 'vk_export' AND `key` = 'vk_export_cekfnbr'
            ");
            $this->db->query("UPDATE " . DB_PREFIX . "setting SET value = '" . $this->db->escape($data->register_key) . "'
                WHERE `code` = 'vk_export' AND `key` = 'vk_export_register_key'
            ");
            
        }
        echo json_encode(array('result' => $data->result, 'message' => $data->message));
    }
    
    private function getAllCategories($categories, $parent_id = 0, $parent_name = '') {
        $output = array();

        if (array_key_exists($parent_id, $categories)) {
            if ($parent_name != '') {
                $parent_name .= ' &gt; ';
            }

            foreach ($categories[$parent_id] as $category) {
                $output[$category['category_id']] = array(
                    'category_id' => $category['category_id'],
                    'name'        => $parent_name . $category['name']
                );

                $output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
            }
        }

        return $output;
    }
    
    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/vk_export')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
            
        if (!$this->error) {
            return true;
        } else {
            return false;
        }    
    }
    
    
}

class miniCurl {
    
    public static function build_query($array) {
        //return http_build_query($array);
        $data = array();
        foreach ($array as $key => $value) {
            $data[] = $key . '=' . urlencode($value);
        }
        return implode($data, '&');
    }
   
    /*
     * Получить страницу
     * @param   string  url страницы
     * @param   string  post данные
     * @param   bool    загрузка файла
     * @return  string  результат запроса
     */
    public static function getPage($url, $post = '', $upload = false, $options = array()) {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, DIR_SYSTEM . 'cache/.vkExportCookie');
        curl_setopt($ch, CURLOPT_COOKIEFILE, DIR_SYSTEM . 'cache/.vkExportCookie');
        if (self::curlFollowLocationAllowed()) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        
        if (array_key_exists('CURLOPT_HEADER', $options) && $options['CURLOPT_HEADER']) {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        
        if ($upload) {
            curl_setopt($ch, CURLOPT_UPLOAD, true);
        }
        
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true); // set POST method  
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // add POST fields
        }
        $res = curl_exec($ch); 
        
        if (!self::curlFollowLocationAllowed()) {
            $res = self::curlFollowLocation($ch, $res, $url);
        }
        
        if ($res === false) {
            die ('error! can\'t get url ' . $url . PHP_EOL . curl_error($ch));
        }
        if (is_resource($ch)) {
            curl_close($ch); 
        }
        
        return $res;
    }
    
    public static function curlFollowLocationAllowed() {
        if (ini_get('open_basedir') || ini_get('safe_mode' != 'Off')) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public static function curlFollowLocation(&$ch, $res, $url) {
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code == 301 || $code == 302) {
            if (preg_match('/Location:(.*?)\n/', $res, $matches)) {
                $newurl = trim(array_pop($matches));
                if (strpos($newurl, 'http') === false) {
                    preg_match('/(https?:\/\/.*?)\//', $url, $matches);
                    $newurl = $matches[1] . $newurl;
                }
                if ($newurl !== $url) {
                    curl_close($ch);
                    return self::getPage($newurl);
                }
            }
        } 
        return $res;
    }
}

// b7b4042e77f400ab8ef4adc283209a04
?>
