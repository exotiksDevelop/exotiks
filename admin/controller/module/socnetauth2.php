<?php
class ControllerModulesocnetauth2 extends Controller {
	private $error = array(); 
	private $data = array(); 
	
	
	public function install()
	{
		$this->load->model('module/socnetauth2');
		$this->model_module_socnetauth2->addFields();
		
		$this->load->model('extension/extension');
		$this->model_extension_extension->install('module', 'socnetauth2_popup');
		
		$this->load->model('user/user_group');
		
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'module/socnetauth2_popup');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'module/socnetauth2_popup');
			
			
	}
	
	public function uninstall()
	{
		$this->load->model('extension/extension');
		$this->model_extension_extension->uninstall('module', 'socnetauth2_popup');
	}
	
	public function index() {   
		$this->load->language('module/socnetauth2');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('module/socnetauth2');
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) 
		{
			foreach($this->request->post as $key=>$val)
			{
				if( is_array($val) && $key != 'socnetauth2_module' && $key != 'socnetauth2_popup_module'  )
				{
					$this->request->post[$key] = serialize($this->request->post[$key]);
				}
			}
			
			$this->request->post['socnetauth2_vkontakte_appid'] 	 		 = trim( $this->request->post['socnetauth2_vkontakte_appid'] );
			$this->request->post['socnetauth2_vkontakte_appsecret'] 		 = trim( $this->request->post['socnetauth2_vkontakte_appsecret'] );
			$this->request->post['socnetauth2_facebook_appid'] 				 = trim( $this->request->post['socnetauth2_facebook_appid'] );
			$this->request->post['socnetauth2_facebook_appsecret'] 			 = trim( $this->request->post['socnetauth2_facebook_appsecret'] );
			$this->request->post['socnetauth2_twitter_consumer_key'] 		 = trim( $this->request->post['socnetauth2_twitter_consumer_key'] );
			$this->request->post['socnetauth2_twitter_consumer_secret'] 	 = trim( $this->request->post['socnetauth2_twitter_consumer_secret'] );
			$this->request->post['socnetauth2_odnoklassniki_application_id'] = trim( $this->request->post['socnetauth2_odnoklassniki_application_id'] );
			$this->request->post['socnetauth2_odnoklassniki_public_key'] 	 = trim( $this->request->post['socnetauth2_odnoklassniki_public_key'] );
			$this->request->post['socnetauth2_odnoklassniki_secret_key']  	 = trim( $this->request->post['socnetauth2_odnoklassniki_secret_key'] );
			$this->request->post['socnetauth2_gmail_client_id'] 			 = trim( $this->request->post['socnetauth2_gmail_client_id'] );
			$this->request->post['socnetauth2_gmail_client_secret'] 		 = trim( $this->request->post['socnetauth2_gmail_client_secret'] );
			$this->request->post['socnetauth2_mailru_id'] 					 = trim( $this->request->post['socnetauth2_mailru_id'] );
			$this->request->post['socnetauth2_mailru_private'] 				 = trim( $this->request->post['socnetauth2_mailru_private'] );
			$this->request->post['socnetauth2_mailru_secret'] 				 = trim( $this->request->post['socnetauth2_mailru_secret'] );
			
			
			/*
			if( !empty( $this->request->post['socnetauth2_popup_module'] ) )
			{
				$this->model_module_socnetauth2->updateSetting('socnetauth2_popup_module', 
															'socnetauth2_popup_status', 
															1);
				
			}
			else
			{
				$this->model_module_socnetauth2->updateSetting('socnetauth2_popup', 
															'socnetauth2_popup_status', 
															0);
			}
			*/
			
			$this->model_setting_setting->editSetting('socnetauth2', $this->request->post);		
			
			
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			if( !empty($this->request->post['stay']) )
			{
				$tab = 'link-tab-general';
				
				if( !empty($this->request->post['tab']) )
				{
					$tab = $this->request->post['tab'];
				}
				
				$this->response->redirect($this->url->link('module/socnetauth2', 'token=' . $this->session->data['token'].'&tab='.$tab, 'SSL'));
			}
			else
			{
				$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));			
			}
		}
				
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$default_hash = array();
		
		foreach($this->data['languages'] as $i=>$lang)
		{
			if( isset($lang['image']) ) // OC 2.1
				$this->data['languages'][$i]['image'] = 'view/image/flags/'.$lang['image'];
			else // OC 2.2
				$this->data['languages'][$i]['image'] = 'language/'.$lang['code'].'/'.$lang['code'].'.png';
				
				
			
			if( isset($lang['directory']) )
				$directory = $lang['directory'];
			else
				$directory = $lang['code'];
				
			$Lang = new Language( $directory );
			$Lang->load('module/socnetauth2');
				
			$default_hash['default_label'][ $lang['language_id'] ] = $Lang->get('default_label');
			$default_hash['default_widget_name'][ $lang['language_id'] ] = $Lang->get('default_widget_name');
		}
		
		
		
		if( isset( $this->request->post['socnetauth2_widget_name'] ) )
		{
			$this->data['socnetauth2_widget_name'] = $this->request->post['socnetauth2_widget_name'];
		}
		elseif( $this->config->has('socnetauth2_widget_name') )
		{
			if( !is_array($this->config->get('socnetauth2_widget_name')) && 
				stristr($this->config->get('socnetauth2_label'), '{' ) != false &&
				stristr($this->config->get('socnetauth2_label'), '}' ) != false &&
				stristr($this->config->get('socnetauth2_label'), ';' ) != false &&
				stristr($this->config->get('socnetauth2_label'), ':' ) != false
			)
			{
				$this->data['socnetauth2_widget_name'] = $this->custom_unserialize( $this->config->get('socnetauth2_widget_name') );
			}
			else
			$this->data['socnetauth2_widget_name'] = $default_hash['default_widget_name'];
		}
		else
		{
			
			$this->data['socnetauth2_widget_name'] = $default_hash['default_widget_name'];
		}
		
		if( !empty($this->request->get['tab']) )
		{
			$this->data['tab'] = $this->request->get['tab'];
		}
		else
		{
			$this->data['tab'] = 'link-tab-general';
		}
		
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_show_standart_auth'] = $this->language->get('entry_show_standart_auth');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		/* start r3 */
		$this->data['entry_debug'] = $this->language->get('entry_debug');
		
		if (isset($this->request->post['socnetauth2_vkontakte_debug'])) 
		{
			$this->data['socnetauth2_vkontakte_debug'] = $this->request->post['socnetauth2_vkontakte_debug'];
		} 
		else
		{ 
			$this->data['socnetauth2_vkontakte_debug'] = $this->config->get('socnetauth2_vkontakte_debug');
		}
		
		if (isset($this->request->post['socnetauth2_facebook_debug'])) 
		{
			$this->data['socnetauth2_facebook_debug'] = $this->request->post['socnetauth2_facebook_debug'];
		} 
		else
		{ 
			$this->data['socnetauth2_facebook_debug'] = $this->config->get('socnetauth2_facebook_debug');
		}
		
		if (isset($this->request->post['socnetauth2_twitter_debug'])) 
		{
			$this->data['socnetauth2_twitter_debug'] = $this->request->post['socnetauth2_twitter_debug'];
		} 
		else
		{ 
			$this->data['socnetauth2_twitter_debug'] = $this->config->get('socnetauth2_twitter_debug');
		}
		
		if (isset($this->request->post['socnetauth2_gmail_debug'])) 
		{
			$this->data['socnetauth2_gmail_debug'] = $this->request->post['socnetauth2_gmail_debug'];
		} 
		else
		{ 
			$this->data['socnetauth2_gmail_debug'] = $this->config->get('socnetauth2_gmail_debug');
		}
		
		if (isset($this->request->post['socnetauth2_odnoklassniki_debug'])) 
		{
			$this->data['socnetauth2_odnoklassniki_debug'] = $this->request->post['socnetauth2_odnoklassniki_debug'];
		} 
		else
		{ 
			$this->data['socnetauth2_odnoklassniki_debug'] = $this->config->get('socnetauth2_odnoklassniki_debug');
		}
		
		if (isset($this->request->post['socnetauth2_mailru_debug'])) 
		{
			$this->data['socnetauth2_mailru_debug'] = $this->request->post['socnetauth2_mailru_debug'];
		} 
		else
		{ 
			$this->data['socnetauth2_mailru_debug'] = $this->config->get('socnetauth2_mailru_debug');
		}
		
		/* end r3 */
		
		/* start metka: r1 */
		
		$this->data['text_download_link'] = $this->language->get('text_download_link');
		$this->data['entry_vkontakte_retargeting'] = $this->language->get('entry_vkontakte_retargeting');
		$this->data['entry_facebook_retargeting'] = $this->language->get('entry_facebook_retargeting');

		$this->data['vkontakte_retargeting'] = $this->url->link('module/socnetauth2/vkontakte_retargeting', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['facebook_retargeting'] = $this->url->link('module/socnetauth2/facebook_retargeting', 'token=' . $this->session->data['token'], 'SSL');
		
		/* end metka: r1 */
		
		
		$this->data['text_design_row_socnetauth2_reg'] = $this->language->get('text_design_row_socnetauth2_reg');
		
		$this->data['text_format_account_page'] = $this->language->get('text_format_account_page');
		$this->data['text_format_checkout_page'] = $this->language->get('text_format_checkout_page');
		$this->data['text_format_simple_page'] = $this->language->get('text_format_simple_page');
		$this->data['text_format_simplereg_page'] = $this->language->get('text_format_simplereg_page');
		$this->data['text_format_reg_page'] = $this->language->get('text_format_reg_page');
		
		
		$this->data['text_showtype_notice'] = $this->language->get('text_showtype_notice');
		
		$this->data['entry_shop_folder'] = $this->language->get('entry_shop_folder');
		
		$this->data['tab_vkontakte'] = $this->language->get('tab_vkontakte');
		$this->data['tab_facebook'] = $this->language->get('tab_facebook');
		$this->data['tab_twitter'] = $this->language->get('tab_twitter');
		$this->data['tab_odnoklassniki'] = $this->language->get('tab_odnoklassniki');
		
		$this->data['entry_version'] = $this->language->get('entry_version');
		
		$this->data['entry_vkontakte_status'] = $this->language->get('entry_vkontakte_status');
		$this->data['entry_vkontakte'] = $this->language->get('entry_vkontakte');
		$this->data['entry_vkontakte_appid'] = $this->language->get('entry_vkontakte_appid');
		$this->data['entry_vkontakte_appsecret'] = $this->language->get('entry_vkontakte_appsecret');
		
		$this->data['entry_twitter_status'] = $this->language->get('entry_twitter_status');
		$this->data['entry_twitter'] = $this->language->get('entry_twitter');
		$this->data['entry_twitter_consumer_key'] = $this->language->get('entry_twitter_consumer_key');
		$this->data['entry_twitter_consumer_secret'] = $this->language->get('entry_twitter_consumer_secret');
		$this->data['entry_twitter_callback'] = $this->language->get('entry_twitter_callback');
		$this->data['entry_twitter_website'] = $this->language->get('entry_twitter_website');
		
		$this->data['entry_facebook_status'] = $this->language->get('entry_facebook_status');
		$this->data['entry_facebook'] = $this->language->get('entry_facebook');
		$this->data['entry_facebook_appid'] = $this->language->get('entry_facebook_appid');
		$this->data['entry_facebook_appsecret'] = $this->language->get('entry_facebook_appsecret');
		$this->data['entry_facebook_link'] = $this->language->get('entry_facebook_link');
		
		$this->data['entry_odnoklassniki_status'] = $this->language->get('entry_odnoklassniki_status');
		$this->data['entry_odnoklassniki'] = $this->language->get('entry_odnoklassniki');
		$this->data['entry_odnoklassniki_application_id'] = $this->language->get('entry_odnoklassniki_application_id');
		$this->data['entry_odnoklassniki_public_key'] = $this->language->get('entry_odnoklassniki_public_key');
		$this->data['entry_odnoklassniki_secret_key'] = $this->language->get('entry_odnoklassniki_secret_key');
		
		
		$this->data['entry_dobortype'] = $this->language->get('entry_dobortype');
		$this->data['entry_dobortype_one'] = $this->language->get('entry_dobortype_one');
		$this->data['entry_dobortype_every'] = $this->language->get('entry_dobortype_every');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_dobor'] = $this->language->get('tab_dobor');
		$this->data['tab_socseti'] = $this->language->get('tab_socseti');
		$this->data['tab_widget'] = $this->language->get('tab_widget');
		$this->data['tab_support'] = $this->language->get('tab_support');
		
		$this->data['tab_design'] = $this->language->get('tab_design');
		$this->data['text_design_notice2'] = $this->language->get('text_design_notice2');
		
		
		$this->data['text_design_col_element'] = $this->language->get('text_design_col_element');
		$this->data['text_design_col_file'] = $this->language->get('text_design_col_file');
		$this->data['text_design_col_comment'] = $this->language->get('text_design_col_comment');
		$this->data['text_design_row_socnetauth2_account'] = $this->language->get('text_design_row_socnetauth2_account');
		$this->data['text_design_notice'] = $this->language->get('text_design_notice');
		$this->data['text_design_row_socnetauth2_checkout'] = $this->language->get('text_design_row_socnetauth2_checkout');
		$this->data['text_design_row_socnetauth2_simple'] = $this->language->get('text_design_row_socnetauth2_simple');
		$this->data['text_design_row_socnetauth2_simplereg'] = $this->language->get('text_design_row_socnetauth2_simplereg');
		$this->data['text_design_row_socnetauth2_popup'] = $this->language->get('text_design_row_socnetauth2_popup');
		$this->data['text_design_row_socnetauth2_confirm'] = $this->language->get('text_design_row_socnetauth2_confirm');
		$this->data['text_design_row_socnetauth2_frame'] = $this->language->get('text_design_row_socnetauth2_frame');
		$this->data['text_design_row_socnetauth2_frame_success'] = $this->language->get('text_design_row_socnetauth2_frame_success');
		$this->data['text_design_row_module_socnetauth2'] = $this->language->get('text_design_row_module_socnetauth2');
		
		
		
		/* start update: a1 */
		
		$this->data['entry_confirm_data'] = $this->language->get('entry_confirm_data');
		$this->data['entry_confirm_data_notice'] = $this->language->get('entry_confirm_data_notice');
		$this->data['entry_confirm_firstname'] = $this->language->get('entry_confirm_firstname');
		$this->data['entry_confirm_lastname']  = $this->language->get('entry_confirm_lastname');
		$this->data['entry_confirm_email']     = $this->language->get('entry_confirm_email');
		$this->data['entry_confirm_phone']     = $this->language->get('entry_confirm_phone');
		$this->data['text_confirm_disable']    = $this->language->get('text_confirm_disable');
		$this->data['text_confirm_none']       = $this->language->get('text_confirm_none');
		$this->data['text_confirm_allways']    = $this->language->get('text_confirm_allways');
		/* end update: a1 */
		
		$this->data['entry_admin'] = $this->language->get('entry_admin');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_widget_name'] = $this->language->get('entry_widget_name');
		
		$this->data['entry_popup_name']	= $this->language->get('entry_popup_name');
		$this->data['tab_popup']	= $this->language->get('tab_popup');
		$this->data['text_popup_notice']	= $this->language->get('text_popup_notice');
		
		$this->data['entry_format']	= $this->language->get('entry_format');
		$this->data['entry_label']	= $this->language->get('entry_label');
		
		$this->data['entry_save_to_addr']	= $this->language->get('entry_save_to_addr');
		$this->data['text_customer_addr']	= $this->language->get('text_customer_addr');
		$this->data['text_customer_only']	= $this->language->get('text_customer_only');
		
		$this->data['entry_admin_header']	= $this->language->get('entry_admin_header');
		$this->data['entry_admin_customer']	= $this->language->get('entry_admin_customer');
		$this->data['entry_admin_customer_list']	= $this->language->get('entry_admin_customer_list');
		$this->data['entry_admin_order']	= $this->language->get('entry_admin_order');
		$this->data['entry_admin_order_list']	= $this->language->get('entry_admin_order_list');
		
		$this->data['text_format_kvadrat']	= $this->language->get('text_format_kvadrat');
		$this->data['text_format_bline']	= $this->language->get('text_format_bline');
		$this->data['text_format_lline']	= $this->language->get('text_format_lline');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		
		$this->data['entry_widget_layout'] = $this->language->get('entry_widget_layout');
		$this->data['entry_widget_position'] = $this->language->get('entry_widget_position');
		$this->data['entry_widget_status'] = $this->language->get('entry_widget_status');
		$this->data['entry_widget_sort_order'] = $this->language->get('entry_widget_sort_order');
		
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_country'] = $this->language->get('text_country');
		$this->data['text_regions'] = $this->language->get('text_regions');
		
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['entry_showtype'] = $this->language->get('entry_showtype');
		$this->data['entry_widget_showtype'] = $this->language->get('entry_widget_showtype');
		$this->data['text_showtype_window'] = $this->language->get('text_showtype_window');
		$this->data['text_showtype_redirect'] = $this->language->get('text_showtype_redirect');
		
		$this->data['text_frame'] = $this->language->get('text_frame');
		$this->data['text_contact'] = $this->language->get('text_contact');
		
		$this->data['entry_widget_after'] = $this->language->get('entry_widget_after');
		$this->data['text_widget_after_show'] = $this->language->get('text_widget_after_show');
		$this->data['text_widget_after_hide'] = $this->language->get('text_widget_after_hide');
		
		$this->data['entry_widget_format'] = $this->language->get('entry_widget_format');
		 
		$this->data['col_enable'] = $this->language->get('col_enable');
		
		$this->data['col_sort_order'] = $this->language->get('col_sort_order');
		
		/* start metka: a1 */
		$this->data['tab_gmail'] = $this->language->get('tab_gmail');
		$this->data['entry_gmail_status'] = $this->language->get('entry_gmail_status');
		$this->data['entry_gmail_client_id'] = $this->language->get('entry_gmail_client_id');
		$this->data['entry_gmail_client_secret'] = $this->language->get('entry_gmail_client_secret');
		
		
		$this->data['entry_email_auth'] = $this->language->get('entry_email_auth');
		$this->data['entry_email_auth_none'] = $this->language->get('entry_email_auth_none');
		$this->data['entry_email_auth_confirm'] = $this->language->get('entry_email_auth_confirm');
		$this->data['entry_email_auth_noconfirm'] = $this->language->get('entry_email_auth_noconfirm');
		
		$this->data['tab_mailru'] = $this->language->get('tab_mailru');
		$this->data['entry_mailru_status'] = $this->language->get('entry_mailru_status');
		$this->data['entry_mailru_id'] = $this->language->get('entry_mailru_id');
		$this->data['entry_mailru_private'] = $this->language->get('entry_mailru_private');
		$this->data['entry_mailru_secret'] = $this->language->get('entry_mailru_secret');
		
		/* end metka: a1 */
		
		
		/* start update: n1 */
		$this->data['entry_vkontakte_customer_group_id'] = $this->language->get('entry_vkontakte_customer_group_id');
		$this->data['entry_facebook_customer_group_id'] = $this->language->get('entry_facebook_customer_group_id');
		$this->data['entry_odnoklassniki_customer_group_id'] = $this->language->get('entry_odnoklassniki_customer_group_id');
		$this->data['entry_gmail_customer_group_id'] = $this->language->get('entry_gmail_customer_group_id');
		$this->data['entry_mailru_customer_group_id'] = $this->language->get('entry_mailru_customer_group_id');
		$this->data['entry_twitter_customer_group_id'] = $this->language->get('entry_twitter_customer_group_id');
		/* end update: n1 */
		
		$this->data['entry_mobile_control'] = $this->language->get('entry_mobile_control');
		
		$this->data['button_save_and_go'] = $this->language->get('button_save_and_go');
		$this->data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
		
		if( !empty( $this->session->data['success'] ) )
		{
			$this->session->data['success'] = 0;
			$this->data['success'] = $this->language->get('text_success');
		}
		else
		{
			$this->data['success'] = '';
		}
		
		
		/* kin insert metka: f1 */
		if (isset($this->request->post['socnetauth2_mobile_control'])) 
		{
			$this->data['socnetauth2_mobile_control'] = $this->request->post['socnetauth2_mobile_control'];
		} 
		else
		{ 
			$this->data['socnetauth2_mobile_control'] = $this->config->get('socnetauth2_mobile_control');
		}
		/* end kin metka: f1 */
		
		
		if (isset($this->request->post['socnetauth2_vkontakte_status'])) 
		{
			$this->data['socnetauth2_vkontakte_status'] = $this->request->post['socnetauth2_vkontakte_status'];
		} 
		else
		{ 
			$this->data['socnetauth2_vkontakte_status'] = $this->config->get('socnetauth2_vkontakte_status');
		}
		
		if (isset($this->request->post['socnetauth2_facebook_status'])) 
		{
			$this->data['socnetauth2_facebook_status'] = $this->request->post['socnetauth2_facebook_status'];
		} 
		else
		{ 
			$this->data['socnetauth2_facebook_status'] = $this->config->get('socnetauth2_facebook_status');
		}
		
		if (isset($this->request->post['socnetauth2_twitter_status'])) 
		{
			$this->data['socnetauth2_twitter_status'] = $this->request->post['socnetauth2_twitter_status'];
		} 
		else
		{ 
			$this->data['socnetauth2_twitter_status'] = $this->config->get('socnetauth2_twitter_status');
		}
		
		if (isset($this->request->post['socnetauth2_odnoklassniki_status'])) 
		{
			$this->data['socnetauth2_odnoklassniki_status'] = $this->request->post['socnetauth2_odnoklassniki_status'];
		} 
		else
		{ 
			$this->data['socnetauth2_odnoklassniki_status'] = $this->config->get('socnetauth2_odnoklassniki_status');
		}
		
		
		
		
		if (isset($this->request->post['socnetauth2_popup_status'])) {
			$this->data['socnetauth2_popup_status'] = $this->request->post['socnetauth2_popup_status'];
		} elseif ($this->config->get('socnetauth2_popup_status')) { 
			$this->data['socnetauth2_popup_status'] = $this->config->get('socnetauth2_popup_status');
		} else {
			$this->data['socnetauth2_popup_status'] = 0;
		}
		
		
		
		/* start metka: a1 */
		
		if (isset($this->request->post['socnetauth2_gmail_status'])) 
		{
			$this->data['socnetauth2_gmail_status'] = $this->request->post['socnetauth2_gmail_status'];
		} 
		else
		{ 
			$this->data['socnetauth2_gmail_status'] = $this->config->get('socnetauth2_gmail_status');
		}
		
		if (isset($this->request->post['socnetauth2_gmail_client_id'])) 
		{
			$this->data['socnetauth2_gmail_client_id'] = $this->request->post['socnetauth2_gmail_client_id'];
		} 
		else
		{ 
			$this->data['socnetauth2_gmail_client_id'] = $this->config->get('socnetauth2_gmail_client_id');
		}
		
		if (isset($this->request->post['socnetauth2_gmail_client_secret'])) 
		{
			$this->data['socnetauth2_gmail_client_secret'] = $this->request->post['socnetauth2_gmail_client_secret'];
		} 
		else
		{ 
			$this->data['socnetauth2_gmail_client_secret'] = $this->config->get('socnetauth2_gmail_client_secret');
		}
		
		// ----------
		/* start update: n1 */ 
		
		if (isset($this->request->post['socnetauth2_vkontakte_customer_group_id'])) {
			$this->data['socnetauth2_vkontakte_customer_group_id'] = $this->request->post['socnetauth2_vkontakte_customer_group_id'];
		} elseif ($this->config->get('socnetauth2_vkontakte_customer_group_id')) { 
			$this->data['socnetauth2_vkontakte_customer_group_id'] = $this->config->get('socnetauth2_vkontakte_customer_group_id');
		} else {
			$this->data['socnetauth2_vkontakte_customer_group_id'] = '';
		}
		
		if (isset($this->request->post['socnetauth2_facebook_customer_group_id'])) {
			$this->data['socnetauth2_facebook_customer_group_id'] = $this->request->post['socnetauth2_facebook_customer_group_id'];
		} elseif ($this->config->get('socnetauth2_facebook_customer_group_id')) { 
			$this->data['socnetauth2_facebook_customer_group_id'] = $this->config->get('socnetauth2_facebook_customer_group_id');
		} else {
			$this->data['socnetauth2_facebook_customer_group_id'] = '';
		}
		
		if (isset($this->request->post['socnetauth2_gmail_customer_group_id'])) {
			$this->data['socnetauth2_gmail_customer_group_id'] = $this->request->post['socnetauth2_gmail_customer_group_id'];
		} elseif ($this->config->get('socnetauth2_gmail_customer_group_id')) { 
			$this->data['socnetauth2_gmail_customer_group_id'] = $this->config->get('socnetauth2_gmail_customer_group_id');
		} else {
			$this->data['socnetauth2_gmail_customer_group_id'] = '';
		}
		
		if (isset($this->request->post['socnetauth2_twitter_customer_group_id'])) {
			$this->data['socnetauth2_twitter_customer_group_id'] = $this->request->post['socnetauth2_twitter_customer_group_id'];
		} elseif ($this->config->get('socnetauth2_twitter_customer_group_id')) { 
			$this->data['socnetauth2_twitter_customer_group_id'] = $this->config->get('socnetauth2_twitter_customer_group_id');
		} else {
			$this->data['socnetauth2_twitter_customer_group_id'] = '';
		}
		
		if (isset($this->request->post['socnetauth2_odnoklassniki_customer_group_id'])) {
			$this->data['socnetauth2_odnoklassniki_customer_group_id'] = $this->request->post['socnetauth2_odnoklassniki_customer_group_id'];
		} elseif ($this->config->get('socnetauth2_odnoklassniki_customer_group_id')) { 
			$this->data['socnetauth2_odnoklassniki_customer_group_id'] = $this->config->get('socnetauth2_odnoklassniki_customer_group_id');
		} else {
			$this->data['socnetauth2_odnoklassniki_customer_group_id'] = '';
		}
		
		if (isset($this->request->post['socnetauth2_mailru_customer_group_id'])) {
			$this->data['socnetauth2_mailru_customer_group_id'] = $this->request->post['socnetauth2_mailru_customer_group_id'];
		} elseif ($this->config->get('socnetauth2_mailru_customer_group_id')) { 
			$this->data['socnetauth2_mailru_customer_group_id'] = $this->config->get('socnetauth2_mailru_customer_group_id');
		} else {
			$this->data['socnetauth2_mailru_customer_group_id'] = '';
		}
		
		/* end update: n1 */ 
		
		if (isset($this->request->post['socnetauth2_mailru_status'])) 
		{
			$this->data['socnetauth2_mailru_status'] = $this->request->post['socnetauth2_mailru_status'];
		} 
		else
		{ 
			$this->data['socnetauth2_mailru_status'] = $this->config->get('socnetauth2_mailru_status');
		}
		
		if (isset($this->request->post['socnetauth2_mailru_id'])) 
		{
			$this->data['socnetauth2_mailru_id'] = $this->request->post['socnetauth2_mailru_id'];
		} 
		else
		{ 
			$this->data['socnetauth2_mailru_id'] = $this->config->get('socnetauth2_mailru_id');
		}
		
		if (isset($this->request->post['socnetauth2_mailru_private'])) 
		{
			$this->data['socnetauth2_mailru_private'] = $this->request->post['socnetauth2_mailru_private'];
		} 
		else
		{ 
			$this->data['socnetauth2_mailru_private'] = $this->config->get('socnetauth2_mailru_private');
		}
		
		if (isset($this->request->post['socnetauth2_mailru_secret'])) 
		{
			$this->data['socnetauth2_mailru_secret'] = $this->request->post['socnetauth2_mailru_secret'];
		} 
		else
		{ 
			$this->data['socnetauth2_mailru_secret'] = $this->config->get('socnetauth2_mailru_secret');
		}
		
		
		if (isset($this->request->post['socnetauth2_email_auth'])) 
		{
			$this->data['socnetauth2_email_auth'] = $this->request->post['socnetauth2_email_auth'];
		} 
		elseif( $this->config->has('socnetauth2_email_auth') )
		{ 
			$this->data['socnetauth2_email_auth'] = $this->config->get('socnetauth2_email_auth');
		}
		else
		{
			$this->data['socnetauth2_email_auth'] = 'none';
		}		
		/* end metka: a1 */
		
		
		if (isset($this->request->post['socnetauth2_shop_folder'])) 
		{
			$this->data['socnetauth2_shop_folder'] = $this->request->post['socnetauth2_shop_folder'];
		} 
		else
		{ 
			$this->data['socnetauth2_shop_folder'] = $this->config->get('socnetauth2_shop_folder');
		}
		
		if (isset($this->request->post['socnetauth2_vkontakte_appid'])) 
		{
			$this->data['socnetauth2_vkontakte_appid'] = $this->request->post['socnetauth2_vkontakte_appid'];
		} 
		else
		{ 
			$this->data['socnetauth2_vkontakte_appid'] = $this->config->get('socnetauth2_vkontakte_appid');
		}
		
		if (isset($this->request->post['socnetauth2_vkontakte_appsecret'])) 
		{
			$this->data['socnetauth2_vkontakte_appsecret'] = $this->request->post['socnetauth2_vkontakte_appsecret'];
		} 
		else
		{ 
			$this->data['socnetauth2_vkontakte_appsecret'] = $this->config->get('socnetauth2_vkontakte_appsecret');
		}
		
		
		if (isset($this->request->post['socnetauth2_facebook_appid'])) 
		{
			$this->data['socnetauth2_facebook_appid'] = $this->request->post['socnetauth2_facebook_appid'];
		} 
		else
		{ 
			$this->data['socnetauth2_facebook_appid'] = $this->config->get('socnetauth2_facebook_appid');
		}
		
		if (isset($this->request->post['socnetauth2_facebook_appsecret'])) 
		{
			$this->data['socnetauth2_facebook_appsecret'] = $this->request->post['socnetauth2_facebook_appsecret'];
		} 
		else
		{ 
			$this->data['socnetauth2_facebook_appsecret'] = $this->config->get('socnetauth2_facebook_appsecret');
		}
		
		if (isset($this->request->post['socnetauth2_twitter_consumer_key'])) 
		{
			$this->data['socnetauth2_twitter_consumer_key'] = $this->request->post['socnetauth2_twitter_consumer_key'];
		} 
		else
		{ 
			$this->data['socnetauth2_twitter_consumer_key'] = $this->config->get('socnetauth2_twitter_consumer_key');
		}
		
		if (isset($this->request->post['socnetauth2_twitter_consumer_secret'])) 
		{
			$this->data['socnetauth2_twitter_consumer_secret'] = $this->request->post['socnetauth2_twitter_consumer_secret'];
		} 
		else
		{ 
			$this->data['socnetauth2_twitter_consumer_secret'] = $this->config->get('socnetauth2_twitter_consumer_secret');
		}
		
		if (isset($this->request->post['socnetauth2_odnoklassniki_application_id'])) 
		{
			$this->data['socnetauth2_odnoklassniki_application_id'] = $this->request->post['socnetauth2_odnoklassniki_application_id'];
		} 
		else
		{ 
			$this->data['socnetauth2_odnoklassniki_application_id'] = $this->config->get('socnetauth2_odnoklassniki_application_id');
		}
		
		if (isset($this->request->post['socnetauth2_odnoklassniki_public_key'])) 
		{
			$this->data['socnetauth2_odnoklassniki_public_key'] = $this->request->post['socnetauth2_odnoklassniki_public_key'];
		} 
		else
		{ 
			$this->data['socnetauth2_odnoklassniki_public_key'] = $this->config->get('socnetauth2_odnoklassniki_public_key');
		}
		
		if (isset($this->request->post['socnetauth2_odnoklassniki_secret_key'])) 
		{
			$this->data['socnetauth2_odnoklassniki_secret_key'] = $this->request->post['socnetauth2_odnoklassniki_secret_key'];
		} 
		else
		{ 
			$this->data['socnetauth2_odnoklassniki_secret_key'] = $this->config->get('socnetauth2_odnoklassniki_secret_key');
		}
		
		
		
		
		
		
		if (isset($this->request->post['socnetauth2_status'])) {
			$this->data['socnetauth2_status'] = $this->request->post['socnetauth2_status'];
		} elseif ($this->config->get('socnetauth2_status')) { 
			$this->data['socnetauth2_status'] = $this->config->get('socnetauth2_status');
		} else {
			$this->data['socnetauth2_status'] = 0;
		}
		
		// ----------------------
		
		if (isset($this->request->post['socnetauth2_account_format'])) {
			$this->data['socnetauth2_account_format'] = $this->request->post['socnetauth2_account_format'];
		} elseif ($this->config->get('socnetauth2_account_format')) { 
			$this->data['socnetauth2_account_format'] = $this->config->get('socnetauth2_account_format');
		} else {
			$this->data['socnetauth2_account_format'] = 'lline';
		}
		
		if (isset($this->request->post['socnetauth2_checkout_format'])) {
			$this->data['socnetauth2_checkout_format'] = $this->request->post['socnetauth2_checkout_format'];
		} elseif ($this->config->get('socnetauth2_checkout_format')) { 
			$this->data['socnetauth2_checkout_format'] = $this->config->get('socnetauth2_checkout_format');
		} else {
			$this->data['socnetauth2_checkout_format'] = 'lline';
		}
		
		if (isset($this->request->post['socnetauth2_simple_format'])) {
			$this->data['socnetauth2_simple_format'] = $this->request->post['socnetauth2_simple_format'];
		} elseif ($this->config->get('socnetauth2_simple_format')) { 
			$this->data['socnetauth2_simple_format'] = $this->config->get('socnetauth2_simple_format');
		} else {
			$this->data['socnetauth2_simple_format'] = 'lline';
		}
		
		if (isset($this->request->post['socnetauth2_reg_format'])) {
			$this->data['socnetauth2_reg_format'] = $this->request->post['socnetauth2_reg_format'];
		} elseif ($this->config->get('socnetauth2_reg_format')) { 
			$this->data['socnetauth2_reg_format'] = $this->config->get('socnetauth2_reg_format');
		} else {
			$this->data['socnetauth2_reg_format'] = 'lline';
		}
		
		
		if (isset($this->request->post['socnetauth2_simplereg_format'])) {
			$this->data['socnetauth2_simplereg_format'] = $this->request->post['socnetauth2_simplereg_format'];
		} elseif ($this->config->get('socnetauth2_simplereg_format')) { 
			$this->data['socnetauth2_simplereg_format'] = $this->config->get('socnetauth2_simplereg_format');
		} else {
			$this->data['socnetauth2_simplereg_format'] = 'lline';
		}
		
		
		if( isset( $this->request->post['socnetauth2_popup_name'] ) )
		{
			$this->data['socnetauth2_popup_name'] = $this->request->post['socnetauth2_popup_name'];
		}
		elseif( $this->config->has('socnetauth2_popup_name') )
		{
			if( !is_array($this->config->get('socnetauth2_popup_name')) && 
				stristr($this->config->get('socnetauth2_popup_name'), '{' ) != false &&
				stristr($this->config->get('socnetauth2_popup_name'), '}' ) != false &&
				stristr($this->config->get('socnetauth2_popup_name'), ';' ) != false &&
				stristr($this->config->get('socnetauth2_popup_name'), ':' ) != false
			)
			{
				$this->data['socnetauth2_popup_name'] = $this->custom_unserialize( $this->config->get('socnetauth2_popup_name') );
			}
			else
			$this->data['socnetauth2_popup_name'] = $this->config->get('socnetauth2_popup_name');
		}
		else
		{
			foreach($this->data['languages'] as $language)
			{
				if( isset($language['directory']) )
					$directory = $language['directory'];
				else
					$directory = $language['code'];
				
				$Lang = new Language( $directory );
				$Lang->load('module/socnetauth2');
				
			
				$this->data['socnetauth2_popup_name'][$language['language_id']] = $Lang->get('socnetauth2_popup_name_default');
			}
		}
		
		
		if (isset($this->request->post['socnetauth2_popups'])) {
			$this->data['socnetauth2_popups'] = $this->request->post['socnetauth2_popups'];
		} elseif ($this->config->get('socnetauth2_popups')) { 
		
			if( !is_array($this->config->get('socnetauth2_popups')) && 
				stristr($this->config->get('socnetauth2_popups'), '{' ) != false &&
				stristr($this->config->get('socnetauth2_popups'), '}' ) != false &&
				stristr($this->config->get('socnetauth2_popups'), ';' ) != false &&
				stristr($this->config->get('socnetauth2_popups'), ':' ) != false
			)
			{
				$this->data['socnetauth2_popups'] = $this->custom_unserialize( $this->config->get('socnetauth2_popups') );
			}
			else
			{
				$this->data['socnetauth2_popups'] = $this->config->get('socnetauth2_popups');
			}
			
		} else {
			$this->data['socnetauth2_popups'] = array();
		}
		
	   	   
		   
		
		if (isset($this->request->post['socnetauth2_save_to_addr'])) {
			$this->data['socnetauth2_save_to_addr'] = $this->request->post['socnetauth2_save_to_addr'];
		} elseif ($this->config->get('socnetauth2_save_to_addr')) { 
			$this->data['socnetauth2_save_to_addr'] = $this->config->get('socnetauth2_save_to_addr');
		} else {
			$this->data['socnetauth2_save_to_addr'] = '';
		}
		
		if (isset($this->request->post['socnetauth2_widget_format'])) {
			$this->data['socnetauth2_widget_format'] = $this->request->post['socnetauth2_widget_format'];
		} elseif ($this->config->get('socnetauth2_widget_format')) { 
			$this->data['socnetauth2_widget_format'] = $this->config->get('socnetauth2_widget_format');
		} else {
			$this->data['socnetauth2_widget_format'] = 'lline';
		}
		
		
		if (isset($this->request->post['socnetauth2_show_standart_auth'])) {
			$this->data['socnetauth2_show_standart_auth'] = $this->request->post['socnetauth2_show_standart_auth'];
		} elseif ($this->config->has('socnetauth2_show_standart_auth')) { 
			$this->data['socnetauth2_show_standart_auth'] = $this->config->get('socnetauth2_show_standart_auth');
		} else {
			$this->data['socnetauth2_show_standart_auth'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_showtype'])) {
			$this->data['socnetauth2_showtype'] = $this->request->post['socnetauth2_showtype'];
		} elseif ($this->config->has('socnetauth2_showtype')) { 
			$this->data['socnetauth2_showtype'] = $this->config->get('socnetauth2_showtype');
		} else {
			$this->data['socnetauth2_showtype'] = 'window';
		}
		
		
		
		if (isset($this->request->post['socnetauth2_sort'])) {
			$this->data['socnetauth2_sort'] = $this->request->post['socnetauth2_sort'];
		} elseif ($this->config->has('socnetauth2_sort')) { 
			$this->data['socnetauth2_sort'] = $this->custom_unserialize( $this->config->get('socnetauth2_sort') );
		} else {
			$this->data['socnetauth2_sort'] = array();
		}
		
		if (isset($this->request->post['socnetauth2_dobortype'])) {
			$this->data['socnetauth2_dobortype'] = $this->request->post['socnetauth2_dobortype'];
		} elseif ($this->config->has('socnetauth2_dobortype')) { 
			$this->data['socnetauth2_dobortype'] = $this->config->get('socnetauth2_dobortype');
		} else {
			$this->data['socnetauth2_dobortype'] = 'one';
		}
		
		
		
		
		if (isset($this->request->post['socnetauth2_widget_showtype'])) {
			$this->data['socnetauth2_widget_showtype'] = $this->request->post['socnetauth2_widget_showtype'];
		} elseif ($this->config->has('socnetauth2_widget_showtype')) { 
			$this->data['socnetauth2_widget_showtype'] = $this->config->get('socnetauth2_widget_showtype');
		} else {
			$this->data['socnetauth2_widget_showtype'] = 'window';
		}
		
		if (isset($this->request->post['socnetauth2_modules'])) {
			$this->data['socnetauth2_modules'] = $this->request->post['socnetauth2_modules'];
		} elseif ($this->config->get('socnetauth2_modules')) { 
		
			if( !is_array($this->config->get('socnetauth2_modules')) && 
				stristr($this->config->get('socnetauth2_modules'), '{' ) != false &&
				stristr($this->config->get('socnetauth2_modules'), '}' ) != false &&
				stristr($this->config->get('socnetauth2_modules'), ';' ) != false &&
				stristr($this->config->get('socnetauth2_modules'), ':' ) != false
			)
			{
				$this->data['socnetauth2_modules'] = $this->custom_unserialize( $this->config->get('socnetauth2_modules') );
			}
			else
			{
				$this->data['socnetauth2_modules'] = $this->config->get('socnetauth2_modules');
			}
			
		} else {
			$this->data['socnetauth2_modules'] = array();
		}
		
		
		if (isset($this->request->post['socnetauth2_widget_after'])) {
			$this->data['socnetauth2_widget_after'] = $this->request->post['socnetauth2_widget_after'];
		} elseif ($this->config->get('socnetauth2_widget_after')) { 
			$this->data['socnetauth2_widget_after'] = $this->config->get('socnetauth2_widget_after');
		} else {
			$this->data['socnetauth2_widget_after'] = '';
		}
		
		
		
		/* start update: a1 */ 
		if (isset($this->request->post['socnetauth2_confirm_firstname_status'])) {
			$this->data['socnetauth2_confirm_firstname_status'] = $this->request->post['socnetauth2_confirm_firstname_status'];
		} elseif ($this->config->get('socnetauth2_confirm_firstname_status')) { 
			$this->data['socnetauth2_confirm_firstname_status'] = $this->config->get('socnetauth2_confirm_firstname_status');
		} else {
			$this->data['socnetauth2_confirm_firstname_status'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_confirm_lastname_status'])) {
			$this->data['socnetauth2_confirm_lastname_status'] = $this->request->post['socnetauth2_confirm_lastname_status'];
		} elseif ($this->config->get('socnetauth2_confirm_lastname_status')) { 
			$this->data['socnetauth2_confirm_lastname_status'] = $this->config->get('socnetauth2_confirm_lastname_status');
		} else {
			$this->data['socnetauth2_confirm_lastname_status'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_confirm_email_status'])) {
			$this->data['socnetauth2_confirm_email_status'] = $this->request->post['socnetauth2_confirm_email_status'];
		} elseif ($this->config->get('socnetauth2_confirm_email_status')) { 
			$this->data['socnetauth2_confirm_email_status'] = $this->config->get('socnetauth2_confirm_email_status');
		} else {
			$this->data['socnetauth2_confirm_email_status'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_confirm_telephone_status'])) {
			$this->data['socnetauth2_confirm_telephone_status'] = $this->request->post['socnetauth2_confirm_telephone_status'];
		} elseif ($this->config->get('socnetauth2_confirm_telephone_status')) { 
			$this->data['socnetauth2_confirm_telephone_status'] = $this->config->get('socnetauth2_confirm_telephone_status');
		} else {
			$this->data['socnetauth2_confirm_telephone_status'] = 0;
		}
		
		/* end update: a1 */ 
		
		
		/* start update: c1 */
		$this->data['entry_confirm_company'] = $this->language->get('entry_confirm_company');
		$this->data['entry_confirm_address_1'] = $this->language->get('entry_confirm_address_1');
		$this->data['entry_confirm_postcode'] = $this->language->get('entry_confirm_postcode');
		$this->data['entry_confirm_city'] = $this->language->get('entry_confirm_city');
		$this->data['entry_confirm_zone'] = $this->language->get('entry_confirm_zone');
		$this->data['entry_confirm_country'] = $this->language->get('entry_confirm_country');
		$this->data['text_required'] = $this->language->get('text_required');
		
		if (isset($this->request->post['socnetauth2_confirm_company_status'])) {
			$this->data['socnetauth2_confirm_company_status'] = $this->request->post['socnetauth2_confirm_company_status'];
		} elseif ($this->config->get('socnetauth2_confirm_company_status')) { 
			$this->data['socnetauth2_confirm_company_status'] = $this->config->get('socnetauth2_confirm_company_status');
		} else {
			$this->data['socnetauth2_confirm_company_status'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_confirm_address_1_status'])) {
			$this->data['socnetauth2_confirm_address_1_status'] = $this->request->post['socnetauth2_confirm_address_1_status'];
		} elseif ($this->config->get('socnetauth2_confirm_address_1_status')) { 
			$this->data['socnetauth2_confirm_address_1_status'] = $this->config->get('socnetauth2_confirm_address_1_status');
		} else {
			$this->data['socnetauth2_confirm_address_1_status'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_confirm_postcode_status'])) {
			$this->data['socnetauth2_confirm_postcode_status'] = $this->request->post['socnetauth2_confirm_postcode_status'];
		} elseif ($this->config->get('socnetauth2_confirm_postcode_status')) { 
			$this->data['socnetauth2_confirm_postcode_status'] = $this->config->get('socnetauth2_confirm_postcode_status');
		} else {
			$this->data['socnetauth2_confirm_postcode_status'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_confirm_city_status'])) {
			$this->data['socnetauth2_confirm_city_status'] = $this->request->post['socnetauth2_confirm_city_status'];
		} elseif ($this->config->get('socnetauth2_confirm_city_status')) { 
			$this->data['socnetauth2_confirm_city_status'] = $this->config->get('socnetauth2_confirm_city_status');
		} else {
			$this->data['socnetauth2_confirm_city_status'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_confirm_zone_status'])) {
			$this->data['socnetauth2_confirm_zone_status'] = $this->request->post['socnetauth2_confirm_zone_status'];
		} elseif ($this->config->get('socnetauth2_confirm_zone_status')) { 
			$this->data['socnetauth2_confirm_zone_status'] = $this->config->get('socnetauth2_confirm_zone_status');
		} else {
			$this->data['socnetauth2_confirm_zone_status'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_confirm_country_status'])) {
			$this->data['socnetauth2_confirm_country_status'] = $this->request->post['socnetauth2_confirm_country_status'];
		} elseif ($this->config->get('socnetauth2_confirm_country_status')) { 
			$this->data['socnetauth2_confirm_country_status'] = $this->config->get('socnetauth2_confirm_country_status');
		} else {
			$this->data['socnetauth2_confirm_country_status'] = 0;
		}
		
		if (isset($this->request->post['socnetauth2_confirm_firstname_required'])) {
			$this->data['socnetauth2_confirm_firstname_required'] = $this->request->post['socnetauth2_confirm_firstname_required'];
		} else { 
			$this->data['socnetauth2_confirm_firstname_required'] = $this->config->get('socnetauth2_confirm_firstname_required');
		}
		
		
		$this->data['data_nets'] = $this->data_nets;
		
		
		
		if (isset($this->request->post['socnetauth2_confirm_lastname_required'])) {
			$this->data['socnetauth2_confirm_lastname_required'] = $this->request->post['socnetauth2_confirm_lastname_required'];
		} else { 
			$this->data['socnetauth2_confirm_lastname_required'] = $this->config->get('socnetauth2_confirm_lastname_required');
		}
		
		if (isset($this->request->post['socnetauth2_confirm_email_required'])) {
			$this->data['socnetauth2_confirm_email_required'] = $this->request->post['socnetauth2_confirm_email_required'];
		} else { 
			$this->data['socnetauth2_confirm_email_required'] = $this->config->get('socnetauth2_confirm_email_required');
		}
		
		if (isset($this->request->post['socnetauth2_confirm_telephone_required'])) {
			$this->data['socnetauth2_confirm_telephone_required'] = $this->request->post['socnetauth2_confirm_telephone_required'];
		} else { 
			$this->data['socnetauth2_confirm_telephone_required'] = $this->config->get('socnetauth2_confirm_telephone_required');
		}
		
		if (isset($this->request->post['socnetauth2_confirm_company_required'])) {
			$this->data['socnetauth2_confirm_company_required'] = $this->request->post['socnetauth2_confirm_company_required'];
		} else { 
			$this->data['socnetauth2_confirm_company_required'] = $this->config->get('socnetauth2_confirm_company_required');
		}
		
		if (isset($this->request->post['socnetauth2_confirm_address_1_required'])) {
			$this->data['socnetauth2_confirm_address_1_required'] = $this->request->post['socnetauth2_confirm_address_1_required'];
		} else { 
			$this->data['socnetauth2_confirm_address_1_required'] = $this->config->get('socnetauth2_confirm_address_1_required');
		}
		
		if (isset($this->request->post['socnetauth2_confirm_postcode_required'])) {
			$this->data['socnetauth2_confirm_postcode_required'] = $this->request->post['socnetauth2_confirm_postcode_required'];
		} else { 
			$this->data['socnetauth2_confirm_postcode_required'] = $this->config->get('socnetauth2_confirm_postcode_required');
		}
		
		if (isset($this->request->post['socnetauth2_confirm_city_required'])) {
			$this->data['socnetauth2_confirm_city_required'] = $this->request->post['socnetauth2_confirm_city_required'];
		} else { 
			$this->data['socnetauth2_confirm_city_required'] = $this->config->get('socnetauth2_confirm_city_required');
		}
		
		if (isset($this->request->post['socnetauth2_confirm_zone_required'])) {
			$this->data['socnetauth2_confirm_zone_required'] = $this->request->post['socnetauth2_confirm_zone_required'];
		} else { 
			$this->data['socnetauth2_confirm_zone_required'] = $this->config->get('socnetauth2_confirm_zone_required');
		}
		
		
		if (isset($this->request->post['socnetauth2_confirm_country_required'])) {
			$this->data['socnetauth2_confirm_country_required'] = $this->request->post['socnetauth2_confirm_country_required'];
		} else { 
			$this->data['socnetauth2_confirm_country_required'] = $this->config->get('socnetauth2_confirm_country_required');
		}
		
		/* 
		lastname
		email
		phone
		company
		address_1		
		postcode
		city
		zone_id
		country_id
		end update: c1 */
		
		
		
		
		if (isset($this->request->post['socnetauth2_format'])) {
			$this->data['socnetauth2_format'] = $this->request->post['socnetauth2_format'];
		} elseif ($this->config->get('socnetauth2_format')) { 
			$this->data['socnetauth2_format'] = $this->config->get('socnetauth2_format');
		} else {
			$this->data['socnetauth2_format'] = 'kvadrat';
		}
		
		if (isset($this->request->post['socnetauth2_label'])) {
			$this->data['socnetauth2_label'] = $this->request->post['socnetauth2_label'];
		} elseif ($this->config->get('socnetauth2_label')) { 
			$this->data['socnetauth2_label'] = $this->custom_unserialize($this->config->get('socnetauth2_label'));
		} else {
			$this->data['socnetauth2_label'] = $default_hash['default_label'];
		}
		
		if (isset($this->request->post['socnetauth2_admin_customer'])) {
			$this->data['socnetauth2_admin_customer'] = $this->request->post['socnetauth2_admin_customer'];
		} elseif ($this->config->has('socnetauth2_admin_customer')) { 
			$this->data['socnetauth2_admin_customer'] = $this->config->get('socnetauth2_admin_customer');
		} else {
			$this->data['socnetauth2_admin_customer'] = 1;
		}
		
		if (isset($this->request->post['socnetauth2_admin_customer_list'])) {
			$this->data['socnetauth2_admin_customer_list'] = $this->request->post['socnetauth2_admin_customer_list'];
		} elseif ($this->config->has('socnetauth2_admin_customer_list')) { 
			$this->data['socnetauth2_admin_customer_list'] = $this->config->get('socnetauth2_admin_customer_list');
		} else {
			$this->data['socnetauth2_admin_customer_list'] = 1;
		}
		
		if (isset($this->request->post['socnetauth2_admin_order'])) {
			$this->data['socnetauth2_admin_order'] = $this->request->post['socnetauth2_admin_order'];
		} elseif ($this->config->has('socnetauth2_admin_order')) { 
			$this->data['socnetauth2_admin_order'] = $this->config->get('socnetauth2_admin_order');
		} else {
			$this->data['socnetauth2_admin_order'] = 1;
		}
		
		if (isset($this->request->post['socnetauth2_admin_order_list'])) {
			$this->data['socnetauth2_admin_order_list'] = $this->request->post['socnetauth2_admin_order_list'];
		} elseif ($this->config->has('socnetauth2_admin_order_list')) { 
			$this->data['socnetauth2_admin_order_list'] = $this->config->get('socnetauth2_admin_order_list');
		} else {
			$this->data['socnetauth2_admin_order_list'] = 1;
		}
		
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/socnetauth2', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/socnetauth2', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['socnetauth2_admin'])) {
			$this->data['socnetauth2_admin'] = $this->request->post['socnetauth2_admin'];
		} else {
			$this->data['socnetauth2_admin'] = $this->config->get('socnetauth2_admin');
		}	
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		if( substr(preg_replace("/[^0-9]/", "", VERSION) , 0, 4 ) >=2100 )
		{
			$this->load->model('customer/customer_group');
			$this->data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups(array());
		}
		else
		{
			$this->load->model('sale/customer_group');
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(array());
		}
		
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/socnetauth2.tpl', $this->data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/socnetauth2')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if( !empty( $this->request->post['socnetauth2_sort'] ) )
		{
			$this->request->post['socnetauth2_sort'] = serialize($this->request->post['socnetauth2_sort']);
		}
		
		
		
		if (!$this->error) {
			
			
			$VERSION = VERSION;
			$VERSION = str_replace(".", "", $VERSION);
		
			if( strlen($VERSION) == 3 )
			{
				$VERSION .= '0';
			}
			elseif( strlen($VERSION) > 4 )
			{
				$VERSION = substr($VERSION, 0, 4);
			}
			
			if( $VERSION <= 1500 )
			{
				if( $this->request->post['socnetauth2_modules'] )
				{
					$ar = array();
					foreach( $this->request->post['socnetauth2_modules'] as $key=>$val )
					{
						foreach($val as $k=>$v)
						{
							$this->request->post['socnetauth2_'.$key.'_'.$k] = $v;
							
						}
						
						$ar[] = $key;
					}
					
					$this->request->post['socnetauth2_module'] = implode(",", $ar);
				}
				else
				{
					//$this->request->post['socnetauth2_module'] = '';
				}
			}
			else
			{
				if( !empty( $this->request->post['socnetauth2_modules'] ) )
				{
					$this->request->post['socnetauth2_module'] = $this->request->post['socnetauth2_modules'];
				}
				else
				{
					//$this->request->post['socnetauth2_module'] = '';
				}
			}
			
			
			if( $VERSION <= 1500 )
			{
				if( $this->request->post['socnetauth2_popups'] )
				{
					$ar = array();
					foreach( $this->request->post['socnetauth2_popups'] as $key=>$val )
					{
						foreach($val as $k=>$v)
						{
							$this->request->post['socnetauth2_'.$key.'_'.$k] = $v;
							
						}
						
						$ar[] = $key;
					}
					
					$this->request->post['socnetauth2_popup_module'] = implode(",", $ar);
				}
				else
				{
					//$this->request->post['socnetauth2_popup_module'] = '';
				}
			}
			else
			{
				if( !empty( $this->request->post['socnetauth2_popups'] ) )
				{
					for($i=0; $i<count($this->request->post['socnetauth2_popups']); $i++ )
					{
						$this->request->post['socnetauth2_popups'][$i]['position']   = 'content_top';
						$this->request->post['socnetauth2_popups'][$i]['sort_order'] = 1;
					}
					
					$this->request->post['socnetauth2_popup_module'] = $this->request->post['socnetauth2_popups'];
				}
				else
				{
					//$this->request->post['socnetauth2_popup_module'] = '';
				}
			}
			
			
			
			
			if( empty($this->request->post['socnetauth2_admin_customer']) )
			$this->request->post['socnetauth2_admin_customer'] = 0;
			
			if( empty($this->request->post['socnetauth2_admin_customer_list']) )
			$this->request->post['socnetauth2_admin_customer_list'] = 0;
			
			if( empty($this->request->post['socnetauth2_admin_order']) )
			$this->request->post['socnetauth2_admin_order'] = 0;
			
			if( empty($this->request->post['socnetauth2_admin_order_list']) )
			$this->request->post['socnetauth2_admin_order_list'] = 0;
			
			if( empty($this->request->post['socnetauth2_show_standart_auth']) )
			$this->request->post['socnetauth2_show_standart_auth'] = 0;
			
			if( !empty($this->request->post['socnetauth2_label']) )
			$this->request->post['socnetauth2_label'] = serialize( $this->request->post['socnetauth2_label'] );
			
			/*
			$code = '<!-- start socnetauth --><!-- end socnetauth -->';
			*/
			
			
			
			$this->request->post = $this->model_module_socnetauth2->makeCode($this->request->post);
			
		/*
			if( $this->request->post['socnetauth2_showtype']=='window' && $this->config->get('socnetauth2_showtype')!='window' )
			{ 
				$this->request->post['config_google_analytics'] = $this->config->get('config_google_analytics').$code;
			}
			elseif( $this->request->post['socnetauth2_showtype']=='window' && $this->config->get('socnetauth2_showtype')=='window' )
			{
				//none
			}
			elseif( $this->request->post['socnetauth2_showtype']!='window' && $this->config->get('socnetauth2_showtype')=='window' )
			{
				$this->request->post['config_google_analytics'] = str_replace($code, "", 
																  $this->config->get('config_google_analytics'));
			}
			elseif( $this->request->post['socnetauth2_showtype']!='window' && !$this->config->get('socnetauth2_showtype')!='window' )
			{
				//none
			}
		*/
			return true;
		} else {
			return false;
		}	
	}
	
	/* start r1 */
	public function vkontakte_retargeting()
	{
		$this->load->model('module/socnetauth2');
		$this->model_module_socnetauth2->checkDB();
		
		$filename = 'vkontakte_retargeting.txt'; // of course find the exact filename....        
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false); // required for certain browsers 
		header('Content-Type: text/plain');

		header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
		header('Content-Transfer-Encoding: binary');
		#header('Content-Length: ' . filesize($filename));

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "socnetauth2_customer2account 
								   WHERE provider = 'vkontakte'");
		if( $query->rows )
		{
			foreach($query->rows as $row)
			{
				echo $row['identity']."\r\n";
			}
		}
		else
		{
			echo "empty list :(";
		}
		
		exit;
		
	}
	
	public function facebook_retargeting()
	{
		$this->load->model('module/socnetauth2');
		$this->model_module_socnetauth2->checkDB();
		
		$filename = 'facebook_retargeting.txt'; // of course find the exact filename....        
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false); // required for certain browsers 
		header('Content-Type: text/plain');

		header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
		header('Content-Transfer-Encoding: binary');
		#header('Content-Length: ' . filesize($filename));

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "socnetauth2_customer2account 
								   WHERE provider = 'facebook'");
		if( $query->rows )
		{
			foreach($query->rows as $row)
			{
				echo $row['identity']."\r\n";
			}
		}
		else
		{
			echo "empty :(";
		}
		
		exit;
	}
	/* end r1 */
	private function custom_unserialize($s)
	{
		if( is_array($s) ) return $s;
	
		if(
			stristr($s, '{' ) != false &&
			stristr($s, '}' ) != false &&
			stristr($s, ';' ) != false &&
			stristr($s, ':' ) != false
		){
			return unserialize($s);
		}else{
			return $s;
		}

	}

}
?>