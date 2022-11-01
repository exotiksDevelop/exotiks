<?php
class ControllerShippingRussianpost2 extends Controller {
	 
	private $data = array(); 
	private $error = array(); 
	private $default_width = 50;
	private $default_height = 24;
	/* start 1705 */
	private $default_image = 'catalog/russianpost.gif';
	/* end 1705 */
	private $RP2;
	
	private $source_hash = array(
		/* start 2810 */
		"tariff" => "Tariff.pochta.ru (рекомендуется)",
		"postcalc" => "PostCalc.ru",
		"otpravka" => "Otpravka.Pochta.ru"
		/* end 2810 */
	);
	
	/* start 1410 */
	public function getPhpVersion()
	{
		if( file_exists( DIR_SYSTEM."library/russianpost2/license.php" ) )
			return '';
		$raw = phpversion();
		
		$ar = explode('.', $raw);
		
		if( $ar[0] == 7 )
		{
			if( empty($ar[1]) || (int)$ar[1] == 0 )
				return 70;
			elseif( (int)$ar[1] == 1 )
				return 71;
			else
				return 72;
		}
		elseif($ar[0] == 5 && !empty($ar[1]) && $ar[1] == 3)
		{
			return 53;
		}
		elseif($ar[0] == 5 && !empty($ar[1]) && $ar[1] == 4)
		{
			return 54;
		}
		elseif($ar[0] == 5 && !empty($ar[1]) && $ar[1] == 5)
		{
			return 55;
		}
		else
		{
			return 5;
		}
		
	}
	/* end 1410 */
	
	public function initClass() 
	{
		include_once( DIR_SYSTEM."library/russianpost2/license".$this->getPhpVersion().".php" );
		include_once( DIR_SYSTEM."library/russianpost2/russianpost2.php" );
		$this->RP2 = new ClassRussianpost2($this->registry);
	}
	
	public function setlicense()
	{
		$this->initClass();
		$this->load->model('shipping/russianpost2');
		$this->load->language('shipping/russianpost2');
		
		if( empty($this->request->post['code']) )
		{
			$this->error[] = $this->language->get('empty_license_code');
			$this->licensePage($lstatus);
			return;
		}
		
		/* start 1410 */
		$lstatus = $this->RP2->checkLicenseCode($this->request->post['code']);
		
		if( !$lstatus || $lstatus < 0 )
		{
			$this->error[] = $this->language->get('error_license_code');
		}
		
		if($this->error)
		{	
			$this->licensePage($lstatus);
			return;
		}
		
		$this->RP2->saveLicense($this->request->post['code']);
		
		if( !$this->RP2->checkFirst() )
		{
			$this->RP2->uploadData();
		}
		/* end 1410 */
		
		
		$this->session->data['success'] = $this->language->get('text_license_success');
		
		
		$this->response->redirect($this->url->link('shipping/russianpost2', 'token=' . $this->session->data['token'], 'SSL') );
	}
	
	private function licensePage( $lstatus = 0 )
	{
		$this->load->language('shipping/russianpost2');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		$this->load->model('shipping/russianpost2');
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      =>  $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      =>  $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_shipping'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('shipping/russianpost2', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if( !empty($this->session->data['success']) )
		{
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		else
		{
			$this->data['success'] = '';
		}
		
		// ---------
		
		if( !$this->model_shipping_russianpost2->getRubCode() )
		{
			$this->error['warning'] = $this->language->get('err_not_rub');
		}			

		if (isset($this->error))  {
			$this->data['errors'] = $this->error;
		} else {
			$this->data['errors'] = '';
		}
		
		
		if( !empty($this->session->data['error']) )
		{
			$this->data['errors'][] = $this->session->data['error'];
			unset($this->session->data['error']);
		}
		
		// ---------
		if( $lstatus && $lstatus < 0 )
		{
			$this->data['errors'][] = $this->language->get('text_statuserror_'.($lstatus * -1));
		}
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['action'] = $this->url->link('shipping/russianpost2/setlicense', 'token=' . $this->session->data['token'], 'SSL');
			
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_license_notice'] = $this->language->get('entry_license_notice');
		$this->data['button_setlicense'] = $this->language->get('button_setlicense');
		$this->data['entry_license'] = $this->language->get('entry_license');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		
		$this->response->setOutput($this->load->view('shipping/russianpost2_license.tpl', $this->data));
	}
	
	
	/* start 812 */
	public function saveTab()
	{
		$tab_id = $this->request->get['tab_id'];
		
		$this->load->language('shipping/russianpost2');
		$this->load->model('setting/setting');
		$this->load->model('shipping/russianpost2');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'shipping/russianpost2')) {
			
			$this->session->data['error'] = $this->language->get('error_permission');
			
			$tab = 'link-tab-general';
			$subtab = '';
			$subtab2 = '';
				
			if( !empty($this->request->get['tab']) )
			{
				$tab = $this->request->get['tab'];
			}
				
			if( !empty($this->request->get['subtab']) )
			{
				$subtab = $this->request->get['subtab'];
			}
				
			if( !empty($this->request->get['subtab2']) )
			{
				$subtab2 = $this->request->get['subtab2'];
			}
			
			$json['redirect'] = $this->url->link('shipping/russianpost2', 
					'token=' . $this->session->data['token'].'&tab='.$tab.
					'&subtab='.$subtab.'&subtab2='.$subtab2, 'SSL');
			
			exit( json_encode($json) );
		}
		/* start 2412 */
		if( $tab_id == 'tab-packs' )
		{
			if( !isset( $this->request->post['russianpost2_custom_packs'] ) )
				$this->request->post['russianpost2_custom_packs'] = array();
		}
		elseif( $tab_id == 'tab-customsrok' )
		{
			if( !isset( $this->request->post['russianpost2_customsrok'] ) )
				$this->request->post['russianpost2_customsrok'] = array();
		}	
		elseif( $tab_id == 'tab-filters' )
		{
			if( !isset( $this->request->post['russianpost2_product_filters'] ) )
				$this->request->post['russianpost2_product_filters'] = array();
			
			if( !isset( $this->request->post['russianpost2_order_filters'] ) )
				$this->request->post['russianpost2_order_filters'] = array();
		}	
		elseif( $tab_id == 'tab-adds' )
		{
			if( !isset( $this->request->post['russianpost2_product_adds'] ) )
				$this->request->post['russianpost2_product_adds'] = array();
			
			if( !isset( $this->request->post['russianpost2_order_adds'] ) )
				$this->request->post['russianpost2_order_adds'] = array();
			
			if( !isset( $this->request->post['russianpost2_method_adds'] ) )
				$this->request->post['russianpost2_method_adds'] = array();
		}
		/* end 2412 */
		
		if( $tab_id == 'tab-methods' )
		{
			/* start 1810 */
			$i = 0;
			foreach( $this->request->post['russianpost2_methods'] as $i=>$method )
			{
				if( $i == 0 )
				{
					$this->request->post['russianpost2_methods'][$i]['code'] = 'russianpost2';
				}
				else
				{
					$this->request->post['russianpost2_methods'][$i]['code'] = 'russianpost2f'.$i;
				}
				
				$e = 0;
				foreach($this->request->post['russianpost2_methods'][$i]['submethods'] as $k=>$submethod)
				{
					$e++;
					if( $i == 0 )
						$this->request->post['russianpost2_methods'][$i]['submethods'][$k]['code'] = 'russianpost2.rp'.$e;
					else
						$this->request->post['russianpost2_methods'][$i]['submethods'][$k]['code'] = 'russianpost2f'.$i.'.rp'.$e;
				} 
			}
			/* end 1810 */
			
			foreach( $this->request->post['russianpost2_methods'] as $i=>$method )
			{
				if( $i == 0 )
				{
					continue;
				}
				
				$sort_order = $this->config->get('russianpost2_sort_order');
				
				if( !empty($method['sort_order']) )
				$sort_order += (int)$method['sort_order'] / 10;
				
				$this->model_setting_setting->editSetting('russianpost2f'.$i, array(
					"russianpost2f".$i."_status" => 1,
					"russianpost2f".$i."_sort_order" => $sort_order,
				) );
			}
			
			
			$this->model_shipping_russianpost2->updateExtentions( 
				isset($this->request->post['russianpost2_methods']) ? 
				$this->request->post['russianpost2_methods'] : array() 
			);
		}
		
		$this->model_shipping_russianpost2->customEditSetting('russianpost2', 
			$this->request->post);
		
		if( $tab_id == 'tab-methods' )
		{
			$this->model_shipping_russianpost2->saveCurrentMethods( 
				isset($this->request->post['russianpost2_methods']) ? 
				$this->request->post['russianpost2_methods'] : array() 
			);
		}
		
		/* start 2801 */
		if( $tab_id == 'tab-customs' )
		{
			$this->model_shipping_russianpost2->saveCustoms( 
				isset($this->request->post['russianpost2_customs']) ? 
				$this->request->post['russianpost2_customs'] : array() 
			);
		}
		/* end 2801 */
		
		if( $tab_id == 'tab-filters' )
		{
			$alert1 = $this->model_shipping_russianpost2->saveFilters( 'order', 
				isset($this->request->post['russianpost2_order_filters']) ? 
				$this->request->post['russianpost2_order_filters'] : array() 
			);
			
			if( !empty($alert1) )
			{
				foreach($alert1 as $alert) 
				{
					$this->session->data['warning_list'][] = sprintf( 
						$this->language->get('text_nodelete_filter_order'), 
						$alert['name'], 
						$alert['count_adds'], 
						$alert['count_methods']
					);
				}
			}
			
			$alert2 = $this->model_shipping_russianpost2->saveFilters( 'product', 
				isset($this->request->post['russianpost2_product_filters']) ? 
				$this->request->post['russianpost2_product_filters'] : array() 
			);
			
			if( !empty($alert2) )
			{
				foreach($alert2 as $alert) 
				{
					$this->session->data['warning_list'][] = sprintf( 
						$this->language->get('text_nodelete_filter_product'), 
						$alert['name'], 
						$alert['count_adds'], 
						$alert['count_filters'] 
					);
				}
			}
		}
		
		if( $tab_id == 'tab-adds' )
		{
			$this->model_shipping_russianpost2->saveAdds( 'product', 
				isset($this->request->post['russianpost2_product_adds']) ? 
				$this->request->post['russianpost2_product_adds'] : array() 
			);
			
			$this->model_shipping_russianpost2->saveAdds( 'method', 
				isset($this->request->post['russianpost2_method_adds']) 
				? 
				$this->request->post['russianpost2_method_adds'] : array() 
			);
			
			$this->model_shipping_russianpost2->saveAdds( 'order', 
				isset($this->request->post['russianpost2_order_adds']) ? 
				$this->request->post['russianpost2_order_adds'] : array() 
			);
		}
		
		if( $tab_id == 'tab-regions' )
		{
			$this->model_shipping_russianpost2->saveCountries( 
				isset( $this->request->post['russianpost2_countries'] ) ? 
				$this->request->post['russianpost2_countries'] : array()
			);
		}
		
		if( !empty($this->request->get['is_end']) )
		{
			if( empty($this->request->get['stay']) )
			{
				$json['redirect'] = $this->url->link('extension/shipping', 
													 'token=' . $this->session->data['token'], true);
			}
			else
			{
				$tab = 'link-tab-general';
				$subtab = '';
				$subtab2 = '';
				
				if( !empty($this->request->get['tab']) )
				{
					$tab = $this->request->get['tab'];
				}
				
				if( !empty($this->request->get['subtab']) )
				{
					$subtab = $this->request->get['subtab'];
				}
				
				if( !empty($this->request->get['subtab2']) )
				{
					$subtab2 = $this->request->get['subtab2'];
				}
				
				
				$json['redirect'] = $this->url->link('shipping/russianpost2', 
					'token=' . $this->session->data['token'].'&tab='.$tab.
					'&subtab='.$subtab.'&subtab2='.$subtab2, 'SSL');
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		exit( json_encode($json) );
	}
	/* end 812 */
	
	public function index() 
	{
		$this->initClass();
		
		$this->load->model('shipping/russianpost2');
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		
		/* start 1410 */
		$lstatus = $this->RP2->checkLicense();
		
		if( !$lstatus || $lstatus < 0 )
		{
			$this->licensePage($lstatus);
			return;
		}
		/* end 1410 */
		
		
		
		/* start 0611 */
		$this->RP2->checkDB();
		/* end 0611 */
		
		
		if( !$this->RP2->checkFirst() )
		{
			$this->RP2->uploadData();
		}
		
		$this->load->language('shipping/russianpost2');
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		// ---------
		
		$this->data['errors'] = array();
		if( !empty($this->session->data['error']) )
		{
			$this->data['errors'][] = $this->session->data['error'];
			$this->error = $this->data['errors'];
			unset($this->session->data['error']);
		}
		/* start metka-1 */
		$this->data['clear_cache'] = $this->url->link('shipping/russianpost2/clearCache', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['button_clear_cache'] = $this->language->get('button_clear_cache');
		/* end metka-1 */
		
		$this->data['text_support'] = $this->language->get('text_support');
		$this->data['entry_articles'] = $this->language->get('entry_articles');
		$this->data['text_russianpost2_cod'] = $this->language->get('text_russianpost2_cod');
		$this->data['text_text_russianpost2_cod'] = $this->language->get('text_text_russianpost2_cod');
		$this->data['text_russianpost2_api'] = $this->language->get('text_russianpost2_api');
		$this->data['text_russianpost2_filters_dops'] = $this->language->get('text_russianpost2_filters_dops');
		$this->data['text_russianpost2_method_service'] = $this->language->get('text_russianpost2_method_service');
		
		$this->data['text_tags_notice'] = $this->language->get('text_tags_notice');
		$this->data['text_tags_notice2'] = $this->language->get('text_tags_notice2');
		
		/* start 2602 */
		$this->data['button_add_sort'] = $this->language->get('button_add_sort');
		$this->data['entry_sort_order_relative'] = $this->language->get('entry_sort_order_relative');
		$this->data['entry_sort_order_absolute'] = $this->language->get('entry_sort_order_absolute');

		if (isset($this->request->post['russianpost2_sort_order_type'])) {
			$this->data['russianpost2_sort_order_type'] = $this->request->post['russianpost2_sort_order_type']; 
		}  
		else {
			$this->data['russianpost2_sort_order_type'] = $this->config->get('russianpost2_sort_order_type');
		}
		/* end 2602 */
		/* start 20092 */
		$this->data['col_russianpost_country_name'] = $this->language->get('col_russianpost_country_name');
		$this->data['col_russianpost_country_code'] = $this->language->get('col_russianpost_country_code');
		$this->data['col_russianpost_country_select'] = $this->language->get('col_russianpost_country_select');
		$this->data['text_russianpost_country_notice'] = $this->language->get('text_russianpost_country_notice');
		$this->data['text_no_defined'] = $this->language->get('text_no_defined');
		$this->data['text_set_country'] = $this->language->get('text_set_country');
		$this->data['text_skipped_notice'] = $this->language->get('text_skipped_notice');
		$this->data['col_russianpost_country_name2'] = $this->language->get('col_russianpost_country_name2');
		
		list( $this->data['current_countries'], 
			  $current_countries_hash ) = $this->RP2->getCurrentCountries();
		list( $russianpost2_countries_hash, 
			  $russianpost2_countries_id_hash ) = $this->RP2->getCountriesGeo2EmsHash();
		$this->data['russianpost2_countries_id_hash'] = $russianpost2_countries_id_hash;
		
		$this->data['skipped_countries'] = array();
		
		foreach($russianpost2_countries_hash as $iso_code_2=>$row)
		{
			if( empty($current_countries_hash[ $iso_code_2 ]) )
			{
				$this->data['skipped_countries'][] = $row;
			}
		}
		
		
		$this->data['russianpost2_countries_list'] = array();
		if( $this->config->has('russianpost2_countries_list') )
		{
			$this->data['russianpost2_countries_list'] = $this->config->get('russianpost2_countries_list');
			
			foreach( $this->data['russianpost2_countries_list'] as $country_id=>$id )
			{
				if( !empty($id) && 
					!empty($this->data['current_countries'][$country_id]['iso_code_2'])  && 
					!empty($russianpost2_countries_id_hash[ $id ]['country_name']) 
				)
				{
					$this->data['russianpost2_countries_list'][$country_id] = array(
						"country_id" => $country_id,
						"name" => $russianpost2_countries_id_hash[ $id ]['country_name'],
						"iso_code_2" => $this->data['current_countries'][$country_id]['iso_code_2'],
						"id" => $id
					);
				}
			}
		}
		else
		{
			foreach( $this->data['current_countries'] as $row )
			{
				/* start 2709 */
				if( $row['iso_code_2'] == 'IC' ) // Канарские острова
				{
					$row['iso_code_2'] = 'ES'; // Испания
				}
				/* end 2709 */
				
				if( !empty( $russianpost2_countries_hash[ $row['iso_code_2'] ] ) )
				{
					$this->data['russianpost2_countries_list'][$row['country_id']] = array(
						"country_id" => $row['country_id'],
						"name" => $russianpost2_countries_hash[ $row['iso_code_2'] ]['country_name'],
						"iso_code_2" => $row['iso_code_2'],
						"id" => $russianpost2_countries_hash[ $row['iso_code_2'] ]['id']
					);
				}
			}
		}
		
		/* end 20092 */

		/* start 2401 */
		$this->data['text_order_adds_cost_fix2products'] = $this->language->get('text_order_adds_cost_fix2products');
		/* end 2401 */
		
		/* start 2009 */
		$this->data['text_order_adds_cost_fix'] = $this->language->get('text_order_adds_cost_fix');
		$this->data['text_order_adds_cost_products_perc'] = $this->language->get('text_order_adds_cost_products_perc');
		$this->data['text_order_adds_cost_delivery_perc'] = $this->language->get('text_order_adds_cost_delivery_perc');
		/* end 2009 */
		$this->data['entry_icons_format'] = $this->language->get('entry_icons_format'); 
		$this->data['entry_icons_format_inname'] = $this->language->get('entry_icons_format_inname');
		$this->data['entry_icons_format_inimage'] = $this->language->get('entry_icons_format_inimage'); 
		
		if (isset($this->request->post['russianpost2_icons_format'])) {
			$this->data['russianpost2_icons_format'] = $this->request->post['russianpost2_icons_format'];
		} elseif( $this->config->has('russianpost2_icons_format') ) {
			$this->data['russianpost2_icons_format'] = $this->config->get('russianpost2_icons_format'); 
		} else {
			$this->data['russianpost2_icons_format'] = 'inname'; 
		}
		
		/* start 0711 */
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		
		/* start 1701 */
		$results = array();
		if(  version_compare(VERSION, '2.1.0.0') <= 0 )
		{	
			$this->load->model('sale/customer_group');
			$results = $this->model_sale_customer_group->getCustomerGroups( );
		}
		else
		{
			$this->load->model('customer/customer_group');
			$results = $this->model_customer_customer_group->getCustomerGroups( );
		}
		/* end 1701 */
		
		/// model_sale_customer_group
		$this->data['customer_groups'] = array();
		
		foreach ($results as $result) {
			$this->data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : null),
			);
		}
		/* end 0711 */
		
		
		$this->data['text_tarif_link'] = $this->language->get('text_tarif_link');
		
		$this->data['text_delivery_types_header'] = $this->language->get('text_delivery_types_header');
		$this->data['text_col_deliverytypes_type_key'] = $this->language->get('text_col_deliverytypes_type_key');
		$this->data['text_col_deliverytypes_type_name'] = $this->language->get('text_col_deliverytypes_type_name');
		$this->data['text_col_deliverytypes_type_name_z'] = $this->language->get('text_col_deliverytypes_type_name_z');
		
		$this->data['text_col_deliverytypes_content'] = $this->language->get('text_col_deliverytypes_content');
		
		$this->data['tab_delivery_types'] = $this->language->get('tab_delivery_types');
		
		$this->data['text_col_deliverytypes_maxlength'] = $this->language->get('text_col_deliverytypes_maxlength');
		
		$this->data['text_col_deliverytypes_maxweight'] = $this->language->get('text_col_deliverytypes_maxweight');
		
		$this->data['entry_module_version'] = $this->language->get('entry_module_version');
		$this->data['entry_sfp_version'] = $this->language->get('entry_sfp_version');
		
		
		$this->data['text_col_deliverytypes_maxsum'] = $this->language->get('text_col_deliverytypes_maxsum');
		$this->data['text_formula_width_by_country'] = $this->language->get('text_formula_width_by_country');
		$this->data['text_col_deliverytypes_status'] = $this->language->get('text_col_deliverytypes_status');
		$this->data['text_product_adds_notice'] = $this->language->get('text_product_adds_notice');
		$this->data['text_bydefault'] = $this->language->get('text_bydefault');
		$this->data['text_general_options'] = $this->language->get('text_general_options');
		$this->data['text_mm'] = $this->language->get('text_mm');
		$this->data['text_gramm'] = $this->language->get('text_gramm');
		$this->data['text_no_assigned'] = $this->language->get('text_no_assigned');
		$this->data['text_tarif_auto'] = $this->language->get('text_tarif_auto');
		$this->data['text_tarif_custom'] = $this->language->get('text_tarif_custom');
		$this->data['text_delivery_types_notice'] = $this->language->get('text_delivery_types_notice');
		$this->data['entry_update_status'] = $this->language->get('entry_update_status');
		
		
		$this->data['entry_ems_cache_lifetime'] = $this->language->get('entry_ems_cache_lifetime');
		$this->data['entry_postcalc_cache_lifetime'] = $this->language->get('entry_postcalc_cache_lifetime');
		
		$this->data['col_service_name'] = $this->language->get('col_service_name');
		$this->data['col_service_name_z'] = $this->language->get('col_service_name_z');
		$this->data['col_source'] = $this->language->get('col_source');
		
		$delivery_types_info = $this->config->get("russianpost2_delivery_types");
	
		$delivery_types = $this->model_shipping_russianpost2->getDeliveryTypes();
		
		foreach($delivery_types as $row)
		{
			$result = array(
				"maxlength_mode" => isset($delivery_types_info[ $row['type_key'] ]['maxlength_mode']) ? $delivery_types_info[ $row['type_key'] ]['maxlength_mode'] : 'auto',
				"maxweight_mode" => isset($delivery_types_info[ $row['type_key'] ]['maxweight_mode']) ? $delivery_types_info[ $row['type_key'] ]['maxweight_mode'] : 'auto',
				"maxsum_mode" => isset($delivery_types_info[ $row['type_key'] ]['maxsum_mode']) ? $delivery_types_info[ $row['type_key'] ]['maxsum_mode'] : 'auto',
				
				"maxsumokrugl" => isset($delivery_types_info[ $row['type_key'] ]['maxsumokrugl']) ? $delivery_types_info[ $row['type_key'] ]['maxsumokrugl'] : 0,
				
				"data_maxweight" => $row["data_maxweight"],
				"data_maxlength" => $row["data_maxlength"],
				"data_maxwidth" => $row["data_maxwidth"],
				"data_maxheight" => $row["data_maxheight"],
				"data_maxsum" => $row["data_maxsum"],
				
				"maxweight" => $row["data_maxweight"],
				"maxlength" => $row["data_maxlength"],
				"maxwidth" => $row["data_maxwidth"],
				"maxheight" => $row["data_maxheight"],
				"maxsum" => $row["data_maxsum"],
				
				"type_name" => $row["type_name"],
				"type_key" => $row["type_key"],
				"content" => $row["content"],
				"doclink" => $row["doclink"],
				"type_name_z" => $row["type_name_z"],
				"status" => 1
			);
			
			if( strstr($row["data_maxlength"], "formula") )
			{
				$result['maxlength'] = '';
				$result['maxwidth'] = '';
				$result['maxheight'] = '';
			}
			
			if( strstr($row["data_maxsum"], "formula") )
			{
				$result['maxsum'] = '';
			}
			
			if( strstr($row["data_maxweight"], "formula") )
			{
				$result['maxweight'] = '';
			}
			
			
			if( !empty( $delivery_types_info[ $row['type_key'] ] ) ) 
			{
				$result['type_name_z']  = $delivery_types_info[ $row['type_key'] ]['type_name_z'];
				
				
				if( $delivery_types_info[ $row['type_key'] ]['maxlength_mode'] == 'custom' )
				{
					$result['maxwidth']  = $delivery_types_info[ $row['type_key'] ]['maxwidth'];
					$result['maxheight'] = $delivery_types_info[ $row['type_key'] ]['maxheight'];
					$result['maxlength'] = $delivery_types_info[ $row['type_key'] ]['maxlength'];
				}
				else
				{
					$result['maxwidth']  = $row['data_maxwidth'];
					$result['maxheight'] = $row['data_maxheight'];
					$result['maxlength'] = $row['data_maxlength'];
					
					if( strstr($row["data_maxlength"], "formula") )
					{
						$result['maxlength'] = '';
						$result['maxwidth'] = '';
						$result['maxheight'] = '';
					}
				}
				
				if( $delivery_types_info[ $row['type_key'] ]['maxweight_mode'] == 'custom' )
				{
					$result['maxweight'] = isset( $delivery_types_info[ $row['type_key'] ]['maxweight'] ) ? $delivery_types_info[ $row['type_key'] ]['maxweight'] : '';
				}
				else
				{
					$result['maxweight'] = $row['data_maxweight'];
					
					if( strstr($row["data_maxweight"], "formula") )
					{
						$result['maxweight'] = '';
					}
				}
				
				
				if( $delivery_types_info[ $row['type_key'] ]['maxsum_mode'] == 'custom' )
				{
					$result['maxsum'] = isset( $delivery_types_info[ $row['type_key'] ]['maxsum'] ) ? $delivery_types_info[ $row['type_key'] ]['maxsum'] : '';
				}
				else
				{
					$result['maxsum'] = $row['data_maxsum'];
					
					if( strstr($row["data_maxsum"], "formula") )
					{
						$result['maxsum'] = '';
					}
				}
				
				
				$result['status']  = $delivery_types_info[ $row['type_key'] ]['status'];
				
			}
			
			// ---------
			
			if( $row['data_maxlength'] == "formula_width_by_country"  )
			{
				$result['text_maxlength_bydefault'] = $this->language->get('text_bydefault').' '. $this->language->get('text_formula_width_by_country');
			}
			elseif( strstr( $row['data_maxlength'], "formula" ) )
			{
				$result['text_maxlength_bydefault'] = $this->language->get('text_bydefault').' '. $this->language->get('text_byformula');
			}
			elseif( !empty($row['data_maxlength']) )
			{
				$result['text_maxlength_bydefault'] = $this->language->get('text_bydefault').' '. $row['data_maxlength'].' x '. $row['data_maxwidth'].' x '. $row['data_maxheight'].' '.$this->language->get('text_mm');
			}
			else
			{
				$result['text_maxlength_bydefault'] = $this->language->get('text_bydefault').' '.$this->language->get('text_no_assigned');
			}
			
			// ---
			
			if( strstr( $row['data_maxweight'], "formula" ) )
			{
				$result['text_maxweight_bydefault'] = $this->language->get('text_bydefault').' '. $this->language->get('text_byformula');
			}
			elseif( !empty($row['data_maxweight']) )
			{
				$result['text_maxweight_bydefault'] = $this->language->get('text_bydefault').' '. $row['data_maxweight'].' '.$this->language->get('text_gramm');
			}
			else
			{
				$result['text_maxweight_bydefault'] = $this->language->get('text_bydefault').' '.$this->language->get('text_no_assigned');
			}
			
			// ---
			
			if( strstr( $row['data_maxsum'], "formula" ) )
			{
				$result['text_maxsum_bydefault'] = $this->language->get('text_bydefault').' '. $this->language->get('text_byformula');
			}
			elseif( !empty($row['data_maxsum']) )
			{
				$result['text_maxsum_bydefault'] = $this->language->get('text_bydefault').' '. $row['data_maxsum'].' '.$this->language->get('text_mm');
			}
			else
			{
				$result['text_maxsum_bydefault'] = $this->language->get('text_bydefault').' '.$this->language->get('text_no_assigned');
			}
			
			
			
			$this->data['russianpost2_delivery_types'][] = $result;
		}
		
		// ---------
		
		list($this->data['module_version'], $this->data['sfp_version'], $min_module_version_for_sfp, $min_module_version_for_work) = $this->model_shipping_russianpost2->getVersions();
		
		#exit($this->data['module_version'].', '.$this->data['sfp_version'].', '.$min_module_version_for_sfp.', '.$min_module_version_for_work);
		
		if( $this->data['module_version'] < $min_module_version_for_work )
		{
			$this->data['update_status'] = $this->language->get('text_update_status_needanyway');
		}
		elseif( $this->data['module_version'] < $min_module_version_for_sfp )
		{
			$this->data['update_status'] = $this->language->get('text_update_status_need');
		}
		else 
		{
			$this->data['update_status'] = $this->language->get('text_update_status_noneed');
		}
		
		$this->data['zones'] = $this->model_shipping_russianpost2->getRussianZones();
		
		
		$this->load->model('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		 
		$this->load->model('tool/image');
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$default_hash = array();

		foreach ($this->data['languages'] as $i=>$result) {
			
			if( isset($result['image']) ) // OC 2.1
				$this->data['languages'][$i]['image'] = 'view/image/flags/'.$result['image'];
			else // OC 2.2
				$this->data['languages'][$i]['image'] = 'language/'.$result['code'].'/'.$result['code'].'.png';
			
			$directory = '';
			if( isset($result['directory']) )
				$directory = $result['directory'];
			else
				$directory = $result['code'];
			
			
			$language = new Language($directory);
			$language->load('shipping/russianpost2');	
			
			$default_hash['default_title'][ $result['language_id'] ] = $language->get('default_title');
			
			$default_hash['default_tag_insurance_block'][ $result['language_id'] ] = $language->get('default_tag_insurance_block');
			
			$default_hash['default_tag_commission_block'][ $result['language_id'] ] = $language->get('default_tag_commission_block');
			$default_hash['default_tag_srok_block'][ $result['language_id'] ] = $language->get('default_tag_srok_block');
			
			$default_hash['default_tag_country_block'][ $result['language_id'] ] = $language->get('default_tag_country_block');
			
			// -----------
			$default_hash['default_rpcod_ems_title'][ $result['language_id'] ] = $language->get('default_rpcod_ems_title');
			
			$default_hash['default_rpcod_title'][ $result['language_id'] ] = $language->get('default_rpcod_title');
			
			$default_hash['default_rpcodtotal_title'][ $result['language_id'] ] = $language->get('default_rpcodtotal_title');
			
			$default_hash['default_rpcodonly_title'][ $result['language_id'] ] = $language->get('default_rpcodonly_title');
			$default_hash['default_pvz_selectblock'][ $result['language_id'] ] = $language->get('default_pvz_selectblock');
			$default_hash['default_pvz_descblock'][ $result['language_id'] ] = $language->get('default_pvz_descblock');
			$default_hash['default_pvz_selecttitle'][ $result['language_id'] ] = $language->get('default_pvz_selecttitle');
			
			$default_hash['default_pvz_worktime_block'][ $result['language_id'] ] = $language->get('default_pvz_worktime_block');
			$default_hash['default_pvz_worktime_workline_withdinner'][ $result['language_id'] ] = $language->get('default_pvz_worktime_workline_withdinner');
			$default_hash['default_pvz_worktime_workline_nodinner'][ $result['language_id'] ] = $language->get('default_pvz_worktime_workline_nodinner');
			$default_hash['default_pvz_worktime_weekendline'][ $result['language_id'] ] = $language->get('default_pvz_worktime_weekendline');
			
			$default_hash['default_tag_pvz_block'][ $result['language_id'] ] = $language->get('default_tag_pvz_block');
			$default_hash['default_tag_ops_block'][ $result['language_id'] ] = $language->get('default_tag_ops_block');
			$default_hash['default_ops_selectblock'][ $result['language_id'] ] = $language->get('default_ops_selectblock');
			$default_hash['default_ops_descblock'][ $result['language_id'] ] = $language->get('default_ops_descblock');
			$default_hash['default_ops_selecttitle'][ $result['language_id'] ] = $language->get('default_ops_selecttitle'); 
			$default_hash['default_ops_worktime_block'][ $result['language_id'] ] = $language->get('default_ops_worktime_block');
			
			$default_hash['default_pvz_cod'][ $result['language_id'] ] = $language->get('default_pvz_cod');
			$default_hash['default_pvz_cod_all'][ $result['language_id'] ] = $language->get('default_pvz_cod_all');
			$default_hash['default_pvz_cod_none'][ $result['language_id'] ] = $language->get('default_pvz_cod_none');
			$default_hash['default_pvz_cod_cashonly'][ $result['language_id'] ] = $language->get('default_pvz_cod_cashonly');
			$default_hash['default_pvz_cod_cardonly'][ $result['language_id'] ] = $language->get('default_pvz_cod_cardonly');
			$default_hash['default_rpcod_ecom_title'][ $result['language_id'] ] = $language->get('default_rpcod_ecom_title');
		
			$default_hash['default_tag_pvz_rupost_block'][ $result['language_id'] ] = $language->get('default_tag_pvz_rupost_block');
			$default_hash['default_tag_pvz_partners_block'][ $result['language_id'] ] = $language->get('default_tag_pvz_partners_block');
		
		}
		
		$this->data['header_otprvka_pvz']             =  $this->language->get('header_otprvka_pvz');
		$this->data['entry_optravka_pvz_mode']             =  $this->language->get('entry_optravka_pvz_mode');
		$this->data['entry_otpravka_pvz_curl_lifetime']             =  $this->language->get('entry_otpravka_pvz_curl_lifetime');

		$this->data['entry_optravka_pvz_mode_each_day']		=  $this->language->get('entry_optravka_pvz_mode_each_day');
		$this->data['entry_optravka_pvz_mode_each_week']		=  $this->language->get('entry_optravka_pvz_mode_each_week');
		$this->data['entry_optravka_pvz_mode_each_month']	=  $this->language->get('entry_optravka_pvz_mode_each_month');
		$this->data['entry_optravka_pvz_mode_button']		=  $this->language->get('entry_optravka_pvz_mode_button');
		$this->data['entry_optravka_pvz_mode_cron']		=  $this->language->get('entry_optravka_pvz_mode_cron');

		$this->data['entry_otpravka_pvz_cron']             =  $this->language->get('entry_otpravka_pvz_cron');
		$this->data['entry_otpravka_pvz_date']             =  $this->language->get('entry_otpravka_pvz_date');
		$this->data['entry_optravka_pvz']             =  $this->language->get('entry_optravka_pvz');
		$this->data['button_pvz']             =  $this->language->get('button_pvz');
		$this->data['entry_pvz_selecttitle']             =  $this->language->get('entry_pvz_selecttitle');
		$this->data['text_general_instruction']             =  $this->language->get('text_general_instruction');
		
		$this->data['text_pvz_header']             =  $this->language->get('text_pvz_header');
		$this->data['text_pvz_notice']             =  $this->language->get('text_pvz_notice');
		$this->data['entry_pvz_selectblock']             =  $this->language->get('entry_pvz_selectblock');
		$this->data['entry_pvz_descblock']             =  $this->language->get('entry_pvz_descblock');
		
		$this->data['text_tag_pvz_block']             =  $this->language->get('text_tag_pvz_block');
		
		
		$this->data['entry_pvz_worktime_block']             =  $this->language->get('entry_pvz_worktime_block');
		$this->data['entry_pvz_worktime_workline_nodinner']             =  $this->language->get('entry_pvz_worktime_workline_nodinner');
		$this->data['entry_pvz_worktime_workline_withdinner']             =  $this->language->get('entry_pvz_worktime_workline_withdinner');
		$this->data['entry_pvz_worktime_weekendline']             =  $this->language->get('entry_pvz_worktime_weekendline');
			
		$this->data['update_pvz_action'] = $this->url->link('shipping/russianpost2/uploadPvz', 'token=' . $this->session->data['token'], 'SSL');
		
		
		if (isset($this->request->post['russianpost2_pvz_selectblock'])) {
			$this->data['russianpost2_pvz_selectblock'] = $this->request->post['russianpost2_pvz_selectblock'];
		} elseif( $this->config->get('russianpost2_pvz_selectblock') ) {
			$this->data['russianpost2_pvz_selectblock'] = $this->config->get('russianpost2_pvz_selectblock');
		} else {
			$this->data['russianpost2_pvz_selectblock'] = $default_hash['default_pvz_selectblock'];
		}
		
		if (isset($this->request->post['russianpost2_pvz_selecttitle'])) {
			$this->data['russianpost2_pvz_selecttitle'] = $this->request->post['russianpost2_pvz_selecttitle'];
		} elseif( $this->config->get('russianpost2_pvz_selecttitle') ) {
			$this->data['russianpost2_pvz_selecttitle'] = $this->config->get('russianpost2_pvz_selecttitle');
		} else {
			$this->data['russianpost2_pvz_selecttitle'] = $default_hash['default_pvz_selecttitle'];
		}
		
		
		if (isset($this->request->post['russianpost2_pvz_worktime_block'])) {
			$this->data['russianpost2_pvz_worktime_block'] = $this->request->post['russianpost2_pvz_worktime_block'];
		} elseif( $this->config->get('russianpost2_pvz_worktime_block') ) {
			$this->data['russianpost2_pvz_worktime_block'] = $this->config->get('russianpost2_pvz_worktime_block');
		} else {
			$this->data['russianpost2_pvz_worktime_block'] = $default_hash['default_pvz_worktime_block'];
		}
		
		
		
		if (isset($this->request->post['russianpost2_pvz_descblock'])) {
			$this->data['russianpost2_pvz_descblock'] = $this->request->post['russianpost2_pvz_descblock'];
		} elseif( $this->config->get('russianpost2_pvz_descblock') ) {
			$this->data['russianpost2_pvz_descblock'] = $this->config->get('russianpost2_pvz_descblock');
		} else {
			$this->data['russianpost2_pvz_descblock'] = $default_hash['default_pvz_descblock'];
		}
		
		
		if (isset($this->request->post['russianpost2_pvz_worktime_block'])) {
			$this->data['russianpost2_pvz_worktime_block'] = $this->request->post['russianpost2_pvz_worktime_block'];
		} elseif( $this->config->get('russianpost2_pvz_worktime_block') ) {
			$this->data['russianpost2_pvz_worktime_block'] = $this->config->get('russianpost2_pvz_worktime_block');
		} else {
			$this->data['russianpost2_pvz_worktime_block'] = $default_hash['default_pvz_worktime_block'];
		}
		
		if (isset($this->request->post['russianpost2_pvz_worktime_workline_nodinner'])) {
			$this->data['russianpost2_pvz_worktime_workline_nodinner'] = $this->request->post['russianpost2_pvz_worktime_workline_nodinner'];
		} elseif( $this->config->get('russianpost2_pvz_worktime_workline_nodinner') ) {
			$this->data['russianpost2_pvz_worktime_workline_nodinner'] = $this->config->get('russianpost2_pvz_worktime_workline_nodinner');
		} else {
			$this->data['russianpost2_pvz_worktime_workline_nodinner'] = $default_hash['default_pvz_worktime_workline_nodinner'];
		}
		
		
		if (isset($this->request->post['russianpost2_pvz_worktime_workline_withdinner'])) {
			$this->data['russianpost2_pvz_worktime_workline_withdinner'] = $this->request->post['russianpost2_pvz_worktime_workline_withdinner'];
		} elseif( $this->config->get('russianpost2_pvz_worktime_workline_withdinner') ) {
			$this->data['russianpost2_pvz_worktime_workline_withdinner'] = $this->config->get('russianpost2_pvz_worktime_workline_withdinner');
		} else {
			$this->data['russianpost2_pvz_worktime_workline_withdinner'] = $default_hash['default_pvz_worktime_workline_withdinner'];
		}

		if (isset($this->request->post['russianpost2_pvz_worktime_weekendline'])) {
			$this->data['russianpost2_pvz_worktime_weekendline'] = $this->request->post['russianpost2_pvz_worktime_weekendline'];
		} elseif( $this->config->get('russianpost2_pvz_worktime_weekendline') ) {
			$this->data['russianpost2_pvz_worktime_weekendline'] = $this->config->get('russianpost2_pvz_worktime_weekendline');
		} else {
			$this->data['russianpost2_pvz_worktime_weekendline'] = $default_hash['default_pvz_worktime_weekendline'];
		}
		$this->data['entry_rpcod_ecom_title']             =  $this->language->get('entry_rpcod_ecom_title');
		
		$this->data['entry_pvz_sorttype']             =  $this->language->get('entry_pvz_sorttype');
		$this->data['entry_pvz_sorttype_abc']             =  $this->language->get('entry_pvz_sorttype_abc');
		$this->data['entry_pvz_sorttype_brand']             =  $this->language->get('entry_pvz_sorttype_brand');
		
		if (isset($this->request->post['russianpost2_rpcod_ecom_title'])) {
			$this->data['russianpost2_rpcod_ecom_title'] = $this->request->post['russianpost2_rpcod_ecom_title'];
		} elseif( $this->config->get('russianpost2_rpcod_ecom_title') ) {
			$this->data['russianpost2_rpcod_ecom_title'] = $this->config->get('russianpost2_rpcod_ecom_title');
		} else { 
			$this->data['russianpost2_rpcod_ecom_title'] = $default_hash['default_rpcod_ecom_title'];
		}
		
		if (isset($this->request->post['russianpost2_pvz_sorttype'])) {
			$this->data['russianpost2_pvz_sorttype'] = $this->request->post['russianpost2_pvz_sorttype'];
		} elseif( $this->config->get('russianpost2_pvz_sorttype') ) {
			$this->data['russianpost2_pvz_sorttype'] = $this->config->get('russianpost2_pvz_sorttype');
		} else {
			$this->data['russianpost2_pvz_sorttype'] = 'abc';
		}
		
		$this->data['entry_pvz_cod']             =  $this->language->get('entry_pvz_cod');
		$this->data['entry_pvz_cod_all']             =  $this->language->get('entry_pvz_cod_all');
		$this->data['entry_pvz_cod_none']             =  $this->language->get('entry_pvz_cod_none');
		$this->data['entry_pvz_cod_cashonly']             =  $this->language->get('entry_pvz_cod_cashonly');
		$this->data['entry_pvz_cod_cardonly']             =  $this->language->get('entry_pvz_cod_cardonly');
		
		if (isset($this->request->post['russianpost2_pvz_cod'])) {
			$this->data['russianpost2_pvz_cod'] = $this->request->post['russianpost2_pvz_cod'];
		} elseif( $this->config->get('russianpost2_pvz_cod') ) {
			$this->data['russianpost2_pvz_cod'] = $this->config->get('russianpost2_pvz_cod');
		} else {
			$this->data['russianpost2_pvz_cod'] = $default_hash['default_pvz_cod'];
		}
		
		if (isset($this->request->post['russianpost2_pvz_cod_all'])) {
			$this->data['russianpost2_pvz_cod_all'] = $this->request->post['russianpost2_pvz_cod_all'];
		} elseif( $this->config->get('russianpost2_pvz_cod_all') ) {
			$this->data['russianpost2_pvz_cod_all'] = $this->config->get('russianpost2_pvz_cod_all');
		} else {
			$this->data['russianpost2_pvz_cod_all'] = $default_hash['default_pvz_cod_all'];
		}
		
		if (isset($this->request->post['russianpost2_pvz_cod_none'])) {
			$this->data['russianpost2_pvz_cod_none'] = $this->request->post['russianpost2_pvz_cod_none'];
		} elseif( $this->config->get('russianpost2_pvz_cod_none') ) {
			$this->data['russianpost2_pvz_cod_none'] = $this->config->get('russianpost2_pvz_cod_none');
		} else {
			$this->data['russianpost2_pvz_cod_none'] = $default_hash['default_pvz_cod_none'];
		}
		
		
		if (isset($this->request->post['russianpost2_pvz_cod_cashonly'])) {
			$this->data['russianpost2_pvz_cod_cashonly'] = $this->request->post['russianpost2_pvz_cod_cashonly'];
		} elseif( $this->config->get('russianpost2_pvz_cod_cashonly') ) {
			$this->data['russianpost2_pvz_cod_cashonly'] = $this->config->get('russianpost2_pvz_cod_cashonly');
		} else {
			$this->data['russianpost2_pvz_cod_cashonly'] = $default_hash['default_pvz_cod_cashonly'];
		}
		if (isset($this->request->post['russianpost2_pvz_cod_cardonly'])) {
			$this->data['russianpost2_pvz_cod_cardonly'] = $this->request->post['russianpost2_pvz_cod_cardonly'];
		} elseif( $this->config->get('russianpost2_pvz_cod_cardonly') ) {
			$this->data['russianpost2_pvz_cod_cardonly'] = $this->config->get('russianpost2_pvz_cod_cardonly');
		} else {
			$this->data['russianpost2_pvz_cod_cardonly'] = $default_hash['default_pvz_cod_cardonly'];
		}
		
		
		if (isset($this->request->post['russianpost2_optravka_pvz_mode'])) {
			$this->data['russianpost2_optravka_pvz_mode'] = $this->request->post['russianpost2_optravka_pvz_mode'];
		} elseif( $this->config->get('russianpost2_optravka_pvz_mode') ) {
			$this->data['russianpost2_optravka_pvz_mode'] = $this->config->get('russianpost2_optravka_pvz_mode');
		} else {
			$this->data['russianpost2_optravka_pvz_mode'] = 'by_button';
		}
		
		if (isset($this->request->post['russianpost2_otpravka_pvz_curl_lifetime'])) {
			$this->data['russianpost2_otpravka_pvz_curl_lifetime'] = $this->request->post['russianpost2_otpravka_pvz_curl_lifetime'];
		} elseif( $this->config->get('russianpost2_otpravka_pvz_curl_lifetime') ) {
			$this->data['russianpost2_otpravka_pvz_curl_lifetime'] = $this->config->get('russianpost2_otpravka_pvz_curl_lifetime');
		} else {
			$this->data['russianpost2_otpravka_pvz_curl_lifetime'] = 5;
		}
		
		$dir = preg_replace("/catalog\/$/", "", DIR_CATALOG);
		
		$this->data['russianpost2_pvz_cron_command'] = '/usr/bin/php '.$dir.'russianpost2/pvz.php';
		$this->data['russianpost2_pvz_date'] = '';
		if( $this->config->get('russianpost2_optravka_pvz_last_upload_date') )
		{
			$this->data['russianpost2_pvz_date'] = preg_replace("/^(\d+)\-(\d+)\-(\d+)$/", "$3.$2.$1", $this->config->get('russianpost2_optravka_pvz_last_upload_date') );
		}
		
		if (isset($this->request->post['russianpost2_tag_pvz_block'])) {
			$this->data['russianpost2_tag_pvz_block'] = $this->request->post['russianpost2_tag_pvz_block'];
		} elseif( $this->config->has('russianpost2_tag_pvz_block') ) {
			$this->data['russianpost2_tag_pvz_block'] = $this->config->get('russianpost2_tag_pvz_block');
		} else {
			$this->data['russianpost2_tag_pvz_block'] = $default_hash['default_tag_pvz_block'];
		}
		
		$this->data['text_tag_pvz_partners_block'] = $this->language->get('text_tag_pvz_partners_block');
		$this->data['text_tag_pvz_rupost_block'] = $this->language->get('text_tag_pvz_rupost_block');
		
		if (isset($this->request->post['russianpost2_tag_pvz_rupost_block'])) {
			$this->data['russianpost2_tag_pvz_rupost_block'] = $this->request->post['russianpost2_tag_pvz_rupost_block'];
		} elseif( $this->config->has('russianpost2_tag_pvz_rupost_block') ) {
			$this->data['russianpost2_tag_pvz_rupost_block'] = $this->config->get('russianpost2_tag_pvz_rupost_block');
		} else {
			$this->data['russianpost2_tag_pvz_rupost_block'] = $default_hash['default_tag_pvz_rupost_block'];
		}
		
		if (isset($this->request->post['russianpost2_tag_pvz_partners_block'])) {
			$this->data['russianpost2_tag_pvz_partners_block'] = $this->request->post['russianpost2_tag_pvz_partners_block'];
		} elseif( $this->config->has('russianpost2_tag_pvz_partners_block') ) {
			$this->data['russianpost2_tag_pvz_partners_block'] = $this->config->get('russianpost2_tag_pvz_partners_block');
		} else {
			$this->data['russianpost2_tag_pvz_partners_block'] = $default_hash['default_tag_pvz_partners_block'];
		}
		
		/* start 1607 */
		$this->data['text_ops_header'] = $this->language->get('text_ops_header');
		$this->data['entry_ops_selectblock'] = $this->language->get('entry_ops_selectblock');
		$this->data['text_ops_notice'] = $this->language->get('text_ops_notice');
		$this->data['entry_ops_descblock'] = $this->language->get('entry_ops_descblock');
		$this->data['entry_ops_selecttitle'] = $this->language->get('entry_ops_selecttitle');
		$this->data['entry_ops_worktime_block'] = $this->language->get('entry_ops_worktime_block'); 
		$this->data['text_tag_ops_block'] = $this->language->get('text_tag_ops_block');
		
		
		
		if (isset($this->request->post['russianpost2_ops_selectblock'])) {
			$this->data['russianpost2_ops_selectblock'] = $this->request->post['russianpost2_ops_selectblock'];
		} elseif( $this->config->get('russianpost2_ops_selectblock') ) {
			$this->data['russianpost2_ops_selectblock'] = $this->config->get('russianpost2_ops_selectblock');
		} else {
			$this->data['russianpost2_ops_selectblock'] = $default_hash['default_ops_selectblock'];
		}
		
		if (isset($this->request->post['russianpost2_ops_selecttitle'])) {
			$this->data['russianpost2_ops_selecttitle'] = $this->request->post['russianpost2_ops_selecttitle'];
		} elseif( $this->config->get('russianpost2_ops_selecttitle') ) {
			$this->data['russianpost2_ops_selecttitle'] = $this->config->get('russianpost2_ops_selecttitle');
		} else {
			$this->data['russianpost2_ops_selecttitle'] = $default_hash['default_ops_selecttitle'];
		}
		
		
		if (isset($this->request->post['russianpost2_ops_worktime_block'])) {
			$this->data['russianpost2_ops_worktime_block'] = $this->request->post['russianpost2_ops_worktime_block'];
		} elseif( $this->config->get('russianpost2_ops_worktime_block') ) {
			$this->data['russianpost2_ops_worktime_block'] = $this->config->get('russianpost2_ops_worktime_block');
		} else {
			$this->data['russianpost2_ops_worktime_block'] = $default_hash['default_ops_worktime_block'];
		}
		
		
		
		if (isset($this->request->post['russianpost2_ops_descblock'])) {
			$this->data['russianpost2_ops_descblock'] = $this->request->post['russianpost2_ops_descblock'];
		} elseif( $this->config->get('russianpost2_ops_descblock') ) {
			$this->data['russianpost2_ops_descblock'] = $this->config->get('russianpost2_ops_descblock');
		} else {
			$this->data['russianpost2_ops_descblock'] = $default_hash['default_ops_descblock'];
		} 
		
		
		if (isset($this->request->post['russianpost2_tag_ops_block'])) {
			$this->data['russianpost2_tag_ops_block'] = $this->request->post['russianpost2_tag_ops_block'];
		} elseif( $this->config->has('russianpost2_tag_ops_block') ) {
			$this->data['russianpost2_tag_ops_block'] = $this->config->get('russianpost2_tag_ops_block');
		} else {
			$this->data['russianpost2_tag_ops_block'] = $default_hash['default_tag_ops_block'];
		} 
		
		$this->data['entry_tariff_curl_lifetime'] = $this->language->get('entry_tariff_curl_lifetime');
		$this->data['entry_postcalc_curl_lifetime'] = $this->language->get('entry_postcalc_curl_lifetime');
		$this->data['entry_otpravka_curl_lifetime'] = $this->language->get('entry_otpravka_curl_lifetime');
		
		if (isset($this->request->post['russianpost2_tariff_curl_lifetime'])) {
			$this->data['russianpost2_tariff_curl_lifetime'] = $this->request->post['russianpost2_tariff_curl_lifetime'];
		} elseif( $this->config->get('russianpost2_tariff_curl_lifetime') ) {
			$this->data['russianpost2_tariff_curl_lifetime'] = $this->config->get('russianpost2_tariff_curl_lifetime');
		} else {
			$this->data['russianpost2_tariff_curl_lifetime'] = 1;
		}
		
		if (isset($this->request->post['russianpost2_postcalc_curl_lifetime'])) {
			$this->data['russianpost2_postcalc_curl_lifetime'] = $this->request->post['russianpost2_postcalc_curl_lifetime'];
		} elseif( $this->config->get('russianpost2_postcalc_curl_lifetime') ) {
			$this->data['russianpost2_postcalc_curl_lifetime'] = $this->config->get('russianpost2_postcalc_curl_lifetime');
		} else {
			$this->data['russianpost2_postcalc_curl_lifetime'] = 1;
		}
		
		if (isset($this->request->post['russianpost2_otpravka_curl_lifetime'])) {
			$this->data['russianpost2_otpravka_curl_lifetime'] = $this->request->post['russianpost2_otpravka_curl_lifetime'];
		} elseif( $this->config->get('russianpost2_otpravka_curl_lifetime') ) {
			$this->data['russianpost2_otpravka_curl_lifetime'] = $this->config->get('russianpost2_otpravka_curl_lifetime');
		} else {
			$this->data['russianpost2_otpravka_curl_lifetime'] = 1;
		}
		/* end 1607 */
		
		// ---------
		$this->data['button_del_region'] = $this->language->get('button_del_region');
		$this->data['button_add_region'] = $this->language->get('button_add_region');
		$this->data['text_select_region'] = $this->language->get('text_select_region');
		
		$this->data['entry_printpost_api_status'] = $this->language->get('entry_printpost_api_status');
		$this->data['russianpost2_printpost_api_status'] = $this->language->get('russianpost2_printpost_api_status');
		
		
		$this->data['text_product_filters_notice'] = $this->language->get('text_product_filters_notice');
		$this->data['text_order_filters_notice'] = $this->language->get('text_order_filters_notice');
		$this->data['text_methods_notice'] = $this->language->get('text_methods_notice');
		
		
		$this->data['col_text_region'] = $this->language->get('col_text_region');
		$this->data['col_text_city'] = $this->language->get('col_text_city');
		$this->data['col_adds_srok'] = $this->language->get('col_adds_srok');
		$this->data['col_method_filter'] = $this->language->get('col_method_filter');
		
		$this->data['col_option_name'] = $this->language->get('col_option_name');
		$this->data['col_option_status'] = $this->language->get('col_option_status');
		$this->data['col_option_ismethod'] = $this->language->get('col_option_ismethod');
		$this->data['col_option_cost'] = $this->language->get('col_option_cost');
		$this->data['col_option_condition'] = $this->language->get('col_option_condition');
		$this->data['col_option_comment'] = $this->language->get('col_option_comment');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_methods'] = $this->language->get('tab_methods');
		$this->data['tab_service'] = $this->language->get('tab_service');
		$this->data['tab_support'] = $this->language->get('tab_support');
		$this->data['tab_regions'] = $this->language->get('tab_regions');
		$this->data['tab_synx'] = $this->language->get('tab_synx');
		$this->data['col_packs_weight'] = $this->language->get('col_packs_weight'); 
		
		$this->data['button_save_go'] = $this->language->get('button_save_go');
		$this->data['button_save_stay'] = $this->language->get('button_save_stay');
		$this->data['entry_upload'] = $this->language->get('entry_upload');
		
		$this->data['text_services'] = $this->language->get('text_services');
		$this->data['entry_debug'] = $this->language->get('entry_debug');
		/* start 2012 */
		$this->data['entry_debug_print'] = $this->language->get('entry_debug_print');
		$this->data['entry_debug_log'] = $this->language->get('entry_debug_log');
		/* end 2012 */
		
		$this->data['button_add_filter'] = $this->language->get('button_add_filter');
		
		$this->data['text_filters_product'] = $this->language->get('text_filters_product');
		$this->data['text_filters_order'] = $this->language->get('text_filters_order');
		$this->data['entry_ems_cache'] = $this->language->get('entry_ems_cache');
		$this->data['entry_postcalc_cache'] = $this->language->get('entry_postcalc_cache');
		$this->data['entry_postcalc_email'] = $this->language->get('entry_postcalc_email');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['text_method_group'] = $this->language->get('text_method_group');
		$this->data['button_groupmethod_add'] = $this->language->get('button_groupmethod_add');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['col_method_geozone'] = $this->language->get('col_method_geozone');
		$this->data['col_method_code'] = $this->language->get('col_method_code');
		$this->data['col_method_title'] = $this->language->get('col_method_title');
		$this->data['col_method_status'] = $this->language->get('col_method_status');
		$this->data['col_method_image'] = $this->language->get('col_method_image');
		$this->data['col_method_sort_order'] = $this->language->get('col_method_sort_order');
		$this->data['entry_method_title'] = $this->language->get('entry_method_title');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_method_add'] = $this->language->get('button_method_add');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_service'] = $this->language->get('button_add_service');
		$this->data['button_del_service'] = $this->language->get('button_del_service');
		$this->data['text_add_service'] = $this->language->get('text_add_service');
		$this->data['text_none_code'] = $this->language->get('text_none_code');
		$this->data['entry_version'] = $this->language->get('entry_version');
		
		$this->data['entry_product_default_weight'] = $this->language->get('entry_product_default_weight');
		$this->data['entry_order_default_weight'] = $this->language->get('entry_order_default_weight');
		
		$this->data['entry_from_region'] = $this->language->get('entry_from_region');
		$this->data['entry_from_city'] = $this->language->get('entry_from_city');
		$this->data['entry_from_postcode'] = $this->language->get('entry_from_postcode');
		
		$this->data['entry_product_nullweight'] = $this->language->get('entry_product_nullweight');
		$this->data['entry_order_nullweight'] = $this->language->get('entry_order_nullweight');
		
		$this->data['text_product_nullweight_setnull'] = $this->language->get('text_product_nullweight_setnull');
		$this->data['text_product_nullweight_setdefault'] = $this->language->get('text_product_nullweight_setdefault');
		
		$this->data['text_nullweight_hide'] = $this->language->get('text_nullweight_hide');
		$this->data['text_nullweight_show'] = $this->language->get('text_nullweight_show');
		$this->data['entry_default_weight'] = $this->language->get('entry_default_weight');
		$this->data['entry_ifnocountry'] = $this->language->get('entry_ifnocountry');
		$this->data['entry_ifnoregion'] = $this->language->get('entry_ifnoregion');
		$this->data['entry_default_region'] = $this->language->get('entry_default_region');
		$this->data['entry_ifnocity'] = $this->language->get('entry_ifnocity');
		$this->data['text_use_default_city'] = $this->language->get('text_use_default_city');
		$this->data['text_hide_method'] = $this->language->get('text_hide_method');
		$this->data['text_use_default_region'] = $this->language->get('text_use_default_region');
		$this->data['text_use_default_country'] = $this->language->get('text_use_default_country');
		$this->data['entry_default_city'] = $this->language->get('entry_default_city');
		
		/* start 1606 */
		$this->data['text_autoselect'] = $this->language->get('text_autoselect');
		/* end 1606 */
		
		$this->data['text_description'] = $this->language->get('text_description');
		$this->data['text_description_link'] = $this->language->get('text_description_link');
		
		$this->data['text_submethods'] = $this->language->get('text_submethods');
		
		$this->data['col_submethod_code'] = $this->language->get('col_submethod_code');
		$this->data['col_submmethod_image'] = $this->language->get('col_submmethod_image');
		$this->data['col_submmethod_title'] = $this->language->get('col_submmethod_title');
		$this->data['col_submmethod_sort_order'] = $this->language->get('col_submmethod_sort_order');
		$this->data['col_submmethod_status'] = $this->language->get('col_submmethod_status');
		
		$this->data['text_services_sorttype'] = $this->language->get('text_services_sorttype');
		$this->data['text_services_sorttype_minprice'] = $this->language->get('text_services_sorttype_minprice');
		$this->data['text_services_sorttype_minsrok'] = $this->language->get('text_services_sorttype_minsrok');
		$this->data['text_services_sorttype_order'] = $this->language->get('text_services_sorttype_order');
		$this->data['col_filter_sizes'] = $this->language->get('col_filter_sizes');
		$this->data['text_product_price'] = $this->language->get('text_product_price');
		$this->data['text_count_products'] = $this->language->get('text_count_products');
		$this->data['text_product_weight'] = $this->language->get('text_product_weight');
		$this->data['text_product_length'] = $this->language->get('text_product_length');
		$this->data['text_product_width'] = $this->language->get('text_product_width');
		$this->data['text_product_height'] = $this->language->get('text_product_height');
		
		$this->data['text_less'] = $this->language->get('text_less');
		$this->data['text_more'] = $this->language->get('text_more');
		$this->data['text_product_sizes'] = $this->language->get('text_product_sizes');
		
		$this->data['text_status'] = $this->language->get('text_status');
		$this->data['text_sort_order'] = $this->language->get('text_sort_order');
		$this->data['text_show_image'] = $this->language->get('text_show_image');
		
		$this->data['entry_method_image_html'] = $this->language->get('entry_method_image_html');
		$this->data['entry_submethod_image_html'] = $this->language->get('entry_submethod_image_html');

		$this->data['text_tag_srok_date']	= $this->language->get('text_tag_srok_date');
		$this->data['text_tag_srok_date_example']	= $this->language->get('text_tag_srok_date_example');
		$this->data['text_tag_srok_block']	= $this->language->get('text_tag_srok_block');
		
		

		$this->data['tab_regions']	= $this->language->get('tab_regions');
		$this->data['text_russianpost_regions_notice']	= $this->language->get('text_russianpost_regions_notice');
		$this->data['col_russianpost_region_name']	= $this->language->get('col_russianpost_region_name');
		$this->data['col_russianpost_region_code']	= $this->language->get('col_russianpost_region_code');
		$this->data['col_russianpost_region_select']	= $this->language->get('col_russianpost_region_select');
		
		$this->data['header_pvz'] = $this->language->get('header_pvz');
		$this->data['header_pvz_notice'] = $this->language->get('header_pvz_notice');
		$this->data['entry_hide_map_js'] = $this->language->get('entry_hide_map_js');
		$this->data['entry_hide_map_js_notice'] = $this->language->get('entry_hide_map_js_notice');
		
		if (isset($this->request->post['russianpost2_hide_map_js'])) {
			$this->data['russianpost2_hide_map_js'] = $this->request->post['russianpost2_hide_map_js'];
		} elseif( $this->config->has('russianpost2_hide_map_js') ) {
			$this->data['russianpost2_hide_map_js'] = $this->config->get('russianpost2_hide_map_js');
		} else {
			$this->data['russianpost2_hide_map_js'] = 0;
		}
		
		$this->data['russianpost2_regions_hash'] = $this->RP2->getGeo2EmsHash();
		$this->data['current_regions'] = $this->RP2->getCurrentRegions();
		
		/* start 0503 */
		$this->data['text_from'] = $this->language->get('text_from');
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_set'] = $this->language->get('text_set');
		$this->data['tab_customsrok'] = $this->language->get('tab_customsrok');
		$this->data['text_customsrok_header'] = $this->language->get('text_customsrok_header');
		$this->data['col_russianpost_region_name'] = $this->language->get('col_russianpost_region_name');
		$this->data['col_avia_srok_capital'] = $this->language->get('col_avia_srok_capital');
		$this->data['col_avia_srok_region'] = $this->language->get('col_avia_srok_region');
		$this->data['col_surface_srok_capital'] = $this->language->get('col_surface_srok_capital');
		$this->data['col_surface_srok_region'] = $this->language->get('col_surface_srok_region');
		$this->data['text_customsrok_notice'] = $this->language->get('text_customsrok_notice');
		
		if (isset($this->request->post['russianpost2_customsrok'])) {
			$this->data['russianpost2_customsrok'] = $this->request->post['russianpost2_customsrok'];
		} elseif( $this->config->get('russianpost2_customsrok') ) {
			$this->data['russianpost2_customsrok'] = $this->config->get('russianpost2_customsrok');
		} else {
			$this->data['russianpost2_customsrok'] = array();
		}
		/* end 0503 */
		
		/* start 3110 */
		$this->data['zones_hash'] = array();
		foreach($this->data['current_regions'] as $i=>$region ) 
		{
			foreach($region['zones'] as $zone) 
			{
				$this->data['zones_hash'][ $zone['zone_id'] ] = 1;
			}
			
			if( !empty($this->data['russianpost2_customsrok'][ $region['ems_code'] ]['avia_capital_from']) )
				$this->data['current_regions'][ $i ]['avia_capital_from'] = $this->data['russianpost2_customsrok'][ $region['ems_code'] ]['avia_capital_from'];
			else
				$this->data['current_regions'][ $i ]['avia_capital_from'] = '';
				
			
			if( !empty($this->data['russianpost2_customsrok'][ $region['ems_code'] ]['surface_capital_from']) )
				$this->data['current_regions'][ $i ]['surface_capital_from'] = $this->data['russianpost2_customsrok'][ $region['ems_code'] ]['surface_capital_from'];
			else
				$this->data['current_regions'][ $i ]['surface_capital_from'] = '';
				
			
			if( !empty($this->data['russianpost2_customsrok'][ $region['ems_code'] ]['avia_region_from']) )
				$this->data['current_regions'][ $i ]['avia_region_from'] = $this->data['russianpost2_customsrok'][ $region['ems_code'] ]['avia_region_from'];
			else
				$this->data['current_regions'][ $i ]['avia_region_from'] = '';
				
			
			if( !empty($this->data['russianpost2_customsrok'][ $region['ems_code'] ]['surface_region_from']) )
				$this->data['current_regions'][ $i ]['surface_region_from'] = $this->data['russianpost2_customsrok'][ $region['ems_code'] ]['surface_region_from'];
			else
				$this->data['current_regions'][ $i ]['surface_region_from'] = '';
			
			// ---------
			
			
			if( !empty($this->data['russianpost2_customsrok'][ $region['ems_code'] ]['avia_capital_to']) )
				$this->data['current_regions'][ $i ]['avia_capital_to'] = $this->data['russianpost2_customsrok'][ $region['ems_code'] ]['avia_capital_to'];
			else
				$this->data['current_regions'][ $i ]['avia_capital_to'] = '';
				
			
			if( !empty($this->data['russianpost2_customsrok'][ $region['ems_code'] ]['surface_capital_to']) )
				$this->data['current_regions'][ $i ]['surface_capital_to'] = $this->data['russianpost2_customsrok'][ $region['ems_code'] ]['surface_capital_to'];
			else
				$this->data['current_regions'][ $i ]['surface_capital_to'] = '';
				
			
			if( !empty($this->data['russianpost2_customsrok'][ $region['ems_code'] ]['avia_region_to']) )
				$this->data['current_regions'][ $i ]['avia_region_to'] = $this->data['russianpost2_customsrok'][ $region['ems_code'] ]['avia_region_to'];
			else
				$this->data['current_regions'][ $i ]['avia_region_to'] = '';
				
			
			if( !empty($this->data['russianpost2_customsrok'][ $region['ems_code'] ]['surface_region_to']) )
				$this->data['current_regions'][ $i ]['surface_region_to'] = $this->data['russianpost2_customsrok'][ $region['ems_code'] ]['surface_region_to'];
			else
				$this->data['current_regions'][ $i ]['surface_region_to'] = '';
			
		}
		
		$this->data['zones_to_select'] = array();
		$this->data['zones_to_select_hash'] = array();
		
		foreach( $this->data['zones'] as $zone )
		{
			if( empty( $this->data['zones_hash'][ $zone['zone_id'] ] ) )
			{
				$this->data['zones_to_select'][] = $zone;
				$this->data['zones_to_select_hash'][$zone['zone_id']] = $zone;
			}
		}
		
		/* end 3110 */
		$this->data['tab_filters']	= $this->language->get('tab_filters');
		$this->data['tab_adds']	= $this->language->get('tab_adds');
		
		/* start 2801 */
		$this->data['tab_customs']	= $this->language->get('tab_customs');
		$this->data['tab_adds']	= $this->language->get('tab_adds');
		$this->data['text_customs_notice']	= $this->language->get('text_customs_notice');
		$this->data['col_customs_name']	= $this->language->get('col_customs_name');
		$this->data['col_customs_price']	= $this->language->get('col_customs_price');
		$this->data['col_customs_type']	= $this->language->get('col_customs_type');
		$this->data['col_customs_status']	= $this->language->get('col_customs_status');
		$this->data['text_customs_type_single']	= $this->language->get('text_customs_type_single');
		$this->data['text_customs_type_bycount']	= $this->language->get('text_customs_type_bycount');
		$this->data['button_customs_add']	= $this->language->get('button_customs_add');
		$this->data['text_free_service']	= $this->language->get('text_free_service');
		$this->data['russianpost2_customs'] = $this->model_shipping_russianpost2->getCustoms(); 
		/* end 2801 */
		
		/* start synx */
		
		$dir = preg_replace("/catalog\/$/", "", DIR_CATALOG);
		
		$this->data['entry_russianpost2_synx_cron'] = '/usr/bin/php '.$dir.'russianpost2/upload.php';
		
		$this->data['russianpost2_synx_date'] 	   = '';
		
		if( $this->config->get('russianpost2_last_upload_date') )
		{
			$this->data['russianpost2_synx_date'] = preg_replace("/^(\d+)\-(\d+)\-(\d+)$/", "$3.$2.$1", $this->config->get('russianpost2_last_upload_date') );
		}
		
		$this->data['synx_link'] =  $this->url->link('shipping/russianpost2/synx', 'token=' . $this->session->data['token'], 'SSL');
		
		/* start 0310 */
		$this->data['text_order_adds_cost_minvalue'] = $this->language->get('text_order_adds_cost_minvalue');
		/* end 0110 */
		$this->data['entry_synx_mode']	= $this->language->get('entry_synx_mode');
		
		$this->data['entry_api_info']	= $this->language->get('entry_api_info');
		$this->data['entry_api_condition']	= $this->language->get('entry_api_condition');
		$this->data['entry_api_status']	= $this->language->get('entry_api_status');
		$this->data['entry_api_synx_status']	= $this->language->get('entry_api_synx_status');
		$this->data['entry_api_synx_mode']	= $this->language->get('entry_api_synx_mode');
		$this->data['entry_api_sort_order']	= $this->language->get('entry_api_sort_order');
		$this->data['entry_api_cron']	= $this->language->get('entry_api_cron');
		
		$this->data['text_filters_product_header']	= $this->language->get('text_filters_product_header');
		$this->data['text_filters_order_header']	= $this->language->get('text_filters_order_header');
		$this->data['text_no_filter']	= $this->language->get('text_no_filter');
		$this->data['entry_synx_mode_each_day']	= $this->language->get('entry_synx_mode_each_day');
		$this->data['entry_synx_mode_each_week'] = $this->language->get('entry_synx_mode_each_week');
		$this->data['entry_synx_mode_each_month'] = $this->language->get('entry_synx_mode_each_month');
		$this->data['entry_synx_mode_by_button'] = $this->language->get('entry_synx_mode_by_button');
		$this->data['entry_synx_mode_by_cron']	= $this->language->get('entry_synx_mode_by_cron');
		
		
		$this->data['tab_api']	= $this->language->get('tab_api');
		$this->data['tab_cod']	= $this->language->get('tab_cod');
		$this->data['text_cod_notice']	= $this->language->get('text_cod_notice');
		$this->data['entry_cod_script']	= $this->language->get('entry_cod_script');
		
		$this->data['text_cod_script_full']	= $this->language->get('text_cod_script_full');
		$this->data['text_cod_script_full_notice']	= $this->language->get('text_cod_script_full_notice');
		
		$this->data['text_cod_script_onlyshipping']	= $this->language->get('text_cod_script_onlyshipping');
		$this->data['text_cod_script_onlyshipping_notice']	= $this->language->get('text_cod_script_onlyshipping_notice');
		
		$this->data['col_cod_name']	= $this->language->get('col_cod_name');
		$this->data['col_cod_instruction']	= $this->language->get('col_cod_instruction');
		$this->data['entry_cod_is_double']	= $this->language->get('entry_cod_is_double');
		$this->data['entry_cod_is_cod_included']	= $this->language->get('entry_cod_is_cod_included');
		
		
		$this->data['entry_cod_is_double_notice']	= $this->language->get('entry_cod_is_double_notice');
		$this->data['entry_cod_is_cod_included']	= $this->language->get('entry_cod_is_cod_included');
		$this->data['entry_cod_is_cod_included_incost']	= $this->language->get('entry_cod_is_cod_included_incost');
		$this->data['entry_cod_is_cod_included_inmod']	= $this->language->get('entry_cod_is_cod_included_inmod');
		$this->data['entry_cod_is_cod_included_none']	= $this->language->get('entry_cod_is_cod_included_none');
		$this->data['text_is_show_cod']	= $this->language->get('text_is_show_cod');
		$this->data['entry_cod_tariftype_default']	= $this->language->get('entry_cod_tariftype_default');
		$this->data['entry_cod_tariftype_set']	= $this->language->get('entry_cod_tariftype_set');
		
		$this->data['text_service_notice']	= $this->language->get('text_service_notice');
		$this->data['text_add_button'] = $this->language->get('text_add_button');
		$this->data['text_del_button'] = $this->language->get('text_del_button');
		
		/* start 901 */
		$this->data['entry_okrugl_10ceil'] = $this->language->get('entry_okrugl_10ceil');
		/* end 901 */
		/* start 510 */
		$this->data['entry_okrugl'] = $this->language->get('entry_okrugl');
		$this->data['entry_okrugl_no'] = $this->language->get('entry_okrugl_no');
		$this->data['entry_okrugl_round'] = $this->language->get('entry_okrugl_round');
		$this->data['entry_okrugl_ceil'] = $this->language->get('entry_okrugl_ceil');
		$this->data['entry_okrugl_floor'] = $this->language->get('entry_okrugl_floor');
		
		if (isset($this->request->post['russianpost2_okrugl'])) {
			$this->data['russianpost2_okrugl'] = $this->request->post['russianpost2_okrugl'];
		} elseif( $this->config->get('russianpost2_okrugl') ) {
			$this->data['russianpost2_okrugl'] = $this->config->get('russianpost2_okrugl');
		} else {
			$this->data['russianpost2_okrugl'] = '';
		}
		/* end 510 */
		$this->data['entry_rpcod_title']	= $this->language->get('entry_rpcod_title');
		$this->data['entry_rpcodtotal_title']	= $this->language->get('entry_rpcodtotal_title');
		$this->data['entry_rpcodonly_title']	= $this->language->get('entry_rpcodonly_title');
		
		$this->data['entry_cod_tariftype']	= $this->language->get('entry_cod_tariftype');
		$this->data['entry_cod_tariftype_percent']	= $this->language->get('entry_cod_tariftype_percent');
		$this->data['entry_cod_mintotal']	= $this->language->get('entry_cod_mintotal');
		$this->data['entry_cod_maxtotal']	= $this->language->get('entry_cod_maxtotal');
		
		/* start metka-407 */
		
		
		$this->data['text_services_adds']	= $this->language->get('text_services_adds');
		
		$this->data['text_adds_method_header']	= $this->language->get('text_adds_method_header');
		$this->data['text_adds_method_header_notice']	= $this->language->get('text_adds_method_header_notice');
		$this->data['col_adds_method_name']	= $this->language->get('col_adds_method_name');
		$this->data['col_adds_method_cost']	= $this->language->get('col_adds_method_cost');
		$this->data['col_adds_method_weight']	= $this->language->get('col_adds_method_weight');
		$this->data['col_adds_method_sizes']	= $this->language->get('col_adds_method_sizes');
		$this->data['col_adds_method_srok']	= $this->language->get('col_adds_method_srok');
		$this->data['col_adds_method_status']	= $this->language->get('col_adds_method_status');
		
		$russianpost2_method_adds = $this->model_shipping_russianpost2->getAdds('method');
		
		if (isset($this->request->post['russianpost2_method_adds'])) {
			$this->data['russianpost2_method_adds'] = $this->request->post['russianpost2_method_adds'];
		} elseif( $russianpost2_method_adds ) {
			$this->data['russianpost2_method_adds'] = $russianpost2_method_adds;
		} else {
			$this->data['russianpost2_method_adds'] = array();
		}
		
		/* end metka-407 */
		
		if (isset($this->request->post['russianpost2_postcalc_cache_lifetime'])) {
			$this->data['russianpost2_postcalc_cache_lifetime'] = $this->request->post['russianpost2_postcalc_cache_lifetime'];
		} elseif( $this->config->get('russianpost2_postcalc_cache_lifetime') ) {
			$this->data['russianpost2_postcalc_cache_lifetime'] = $this->config->get('russianpost2_postcalc_cache_lifetime');
		} else {
			$this->data['russianpost2_postcalc_cache_lifetime'] = 10;
		}
		
		if (isset($this->request->post['russianpost2_ems_cache_lifetime'])) {
			$this->data['russianpost2_ems_cache_lifetime'] = $this->request->post['russianpost2_ems_cache_lifetime'];
		} elseif( $this->config->get('russianpost2_ems_cache_lifetime') ) {
			$this->data['russianpost2_ems_cache_lifetime'] = $this->config->get('russianpost2_ems_cache_lifetime');
		} else {
			$this->data['russianpost2_ems_cache_lifetime'] = 10;
		}
		
		
		if (isset($this->request->post['russianpost2_rpcod_title'])) {
			$this->data['russianpost2_rpcod_title'] = $this->request->post['russianpost2_rpcod_title'];
		} elseif( $this->config->get('russianpost2_rpcod_title') ) {
			$this->data['russianpost2_rpcod_title'] = $this->config->get('russianpost2_rpcod_title');
		} else {
			$this->data['russianpost2_rpcod_title'] = $default_hash['default_rpcod_title'];
		}
		
		if (isset($this->request->post['russianpost2_rpcodtotal_title'])) {
			$this->data['russianpost2_rpcodtotal_title'] = $this->request->post['russianpost2_rpcodtotal_title'];
		} elseif( $this->config->get('russianpost2_rpcodtotal_title') ) {
			$this->data['russianpost2_rpcodtotal_title'] = $this->config->get('russianpost2_rpcodtotal_title');
		} else {
			$this->data['russianpost2_rpcodtotal_title'] = $default_hash['default_rpcodtotal_title'];
		}
		
		/* start 1202-3 */
		$this->data['entry_rpcod_ems_title']	= $this->language->get('entry_rpcod_ems_title');
		
		if (isset($this->request->post['russianpost2_rpcod_ems_title'])) {
			$this->data['russianpost2_rpcod_ems_title'] = $this->request->post['russianpost2_rpcodtotal_title'];
		} elseif( $this->config->get('russianpost2_rpcod_ems_title') ) {
			$this->data['russianpost2_rpcod_ems_title'] = $this->config->get('russianpost2_rpcod_ems_title');
		} else { 
			$this->data['russianpost2_rpcod_ems_title'] = $default_hash['default_rpcod_ems_title'];
		}
		
		/* end 1202-3 */
		
		if (isset($this->request->post['russianpost2_rpcodonly_title'])) {
			$this->data['russianpost2_rpcodonly_title'] = $this->request->post['russianpost2_rpcodonly_title'];
		} elseif( $this->config->get('russianpost2_rpcodonly_title') ) {
			$this->data['russianpost2_rpcodonly_title'] = $this->config->get('russianpost2_rpcodonly_title');
		} else {
			$this->data['russianpost2_rpcodonly_title'] = $default_hash['default_rpcodonly_title'];
		}
		
		if (isset($this->request->post['russianpost2_cod_mintotal'])) {
			$this->data['russianpost2_cod_mintotal'] = $this->request->post['russianpost2_cod_mintotal'];
		} else {
			$this->data['russianpost2_cod_mintotal'] = $this->config->get('russianpost2_cod_mintotal');
		}
		
		/* start 2901 */
		$this->data['entry_cod_tariftype_minvalue']	= $this->language->get('entry_cod_tariftype_minvalue');

		if (isset($this->request->post['russianpost2_cod_tariftype_minvalue'])) {
			$this->data['russianpost2_cod_tariftype_minvalue'] = $this->request->post['russianpost2_cod_tariftype_minvalue'];
		} else {
			$this->data['russianpost2_cod_tariftype_minvalue'] = $this->config->get('russianpost2_cod_tariftype_minvalue');
		}
		/* end 2901 */
		
		/* start 1201 */
		$this->data['entry_cod_tariftype_ems_minvalue']	= $this->language->get('entry_cod_tariftype_ems_minvalue');
		$this->data['entry_cod_tariftype_ems_percent']	= $this->language->get('entry_cod_tariftype_ems_percent');
		$this->data['entry_cod_tariftype_ems_percent_notice']	= $this->language->get('entry_cod_tariftype_ems_percent_notice');

		if (isset($this->request->post['russianpost2_cod_tariftype_ems_minvalue'])) {
			$this->data['russianpost2_cod_tariftype_ems_minvalue'] = $this->request->post['russianpost2_cod_tariftype_ems_minvalue'];
		} else {
			$this->data['russianpost2_cod_tariftype_ems_minvalue'] = $this->config->get('russianpost2_cod_tariftype_ems_minvalue');
		}
		
		if (isset($this->request->post['russianpost2_cod_tariftype_ems_percent'])) {
			$this->data['russianpost2_cod_tariftype_ems_percent'] = $this->request->post['russianpost2_cod_tariftype_ems_percent'];
		} else {
			$this->data['russianpost2_cod_tariftype_ems_percent'] = $this->config->get('russianpost2_cod_tariftype_ems_percent');
		}
		/* end 1201 */
		
		if (isset($this->request->post['russianpost2_cod_maxtotal'])) {
			$this->data['russianpost2_cod_maxtotal'] = $this->request->post['russianpost2_cod_maxtotal'];
		} else {
			$this->data['russianpost2_cod_maxtotal'] = $this->config->get('russianpost2_cod_maxtotal');
		}
		
		if (isset($this->request->post['russianpost2_cod_maxtotal'])) {
			$this->data['russianpost2_cod_maxtotal'] = $this->request->post['russianpost2_cod_maxtotal'];
		} else {
			$this->data['russianpost2_cod_maxtotal'] = $this->config->get('russianpost2_cod_maxtotal');
		}

		if (isset($this->request->post['russianpost2_cod_tariftype'])) {
			$this->data['russianpost2_cod_tariftype'] = $this->request->post['russianpost2_cod_tariftype'];
		} else {
			$this->data['russianpost2_cod_tariftype'] = $this->config->get('russianpost2_cod_tariftype');
		}

		if (isset($this->request->post['russianpost2_cod_tariftype_min'])) {
			$this->data['russianpost2_cod_tariftype_min'] = $this->request->post['russianpost2_cod_tariftype_min'];
		} else {
			$this->data['russianpost2_cod_tariftype_min'] = $this->config->get('russianpost2_cod_tariftype_min');
		}

		if (isset($this->request->post['russianpost2_cod_tariftype_percent'])) {
			$this->data['russianpost2_cod_tariftype_percent'] = $this->request->post['russianpost2_cod_tariftype_percent'];
		} else {
			$this->data['russianpost2_cod_tariftype_percent'] = $this->config->get('russianpost2_cod_tariftype_percent');
		}
		
		
		
		
		
		
		
		
		if (isset($this->request->post['russianpost2_cod_maxtotal'])) {
			$this->data['russianpost2_cod_maxtotal'] = $this->request->post['russianpost2_cod_maxtotal'];
		} else {
			$this->data['russianpost2_cod_maxtotal'] = $this->config->get('russianpost2_cod_maxtotal');
		}
		
		
		
		
		
		if (isset($this->request->post['russianpost2_cod_script'])) {
			$this->data['russianpost2_cod_script'] = $this->request->post['russianpost2_cod_script'];
		} elseif( $this->config->has('russianpost2_cod_script') ) {
			$this->data['russianpost2_cod_script'] = $this->config->get('russianpost2_cod_script');
		} else {
			$this->data['russianpost2_cod_script'] = 'full';
		}
		
		if (isset($this->request->post['russianpost2_cod_is_double'])) {
			$this->data['russianpost2_cod_is_double'] = $this->request->post['russianpost2_cod_is_double'];
		} elseif( $this->config->has('russianpost2_cod_is_double') ) {
			$this->data['russianpost2_cod_is_double'] = $this->config->get('russianpost2_cod_is_double');
		} else {
			$this->data['russianpost2_cod_is_double'] = 0;
		}
		
		if (isset($this->request->post['russianpost2_is_cod_included'])) {
			$this->data['russianpost2_is_cod_included'] = $this->request->post['russianpost2_is_cod_included'];
		} elseif( $this->config->has('russianpost2_is_cod_included') ) {
			$this->data['russianpost2_is_cod_included'] = $this->config->get('russianpost2_is_cod_included');
		} else {
			$this->data['russianpost2_is_cod_included'] = 'inmod';
		}
		
		// -------
		$this->data['entry_is_no_insurance_limit']	= $this->language->get('entry_is_no_insurance_limit');
		$this->data['entry_is_no_insurance_limit_hide']	= $this->language->get('entry_is_no_insurance_limit_hide');
		$this->data['entry_is_no_insurance_limit_show']	= $this->language->get('entry_is_no_insurance_limit_show');
		$this->data['entry_is_no_insurance_limit_show2']	= $this->language->get('entry_is_no_insurance_limit_show2');
		$this->data['entry_is_pack_limit']	= $this->language->get('entry_is_pack_limit');
		$this->data['entry_is_pack_limit_nopack']	= $this->language->get('entry_is_pack_limit_nopack');
		$this->data['entry_is_pack_limit_hide']	= $this->language->get('entry_is_pack_limit_hide');
		
		
		if (isset($this->request->post['russianpost2_is_no_insurance_limit'])) {
			$this->data['russianpost2_is_no_insurance_limit'] = $this->request->post['russianpost2_is_no_insurance_limit'];
		} elseif( $this->config->has('russianpost2_is_no_insurance_limit') ) {
			$this->data['russianpost2_is_no_insurance_limit'] = $this->config->get('russianpost2_is_no_insurance_limit');
		} else {
			$this->data['russianpost2_is_no_insurance_limit'] = 0;
		}
		
		if (isset($this->request->post['russianpost2_is_pack_limit'])) {
			$this->data['russianpost2_is_pack_limit'] = $this->request->post['russianpost2_is_pack_limit'];
		} elseif( $this->config->has('russianpost2_is_pack_limit') ) {
			$this->data['russianpost2_is_pack_limit'] = $this->config->get('russianpost2_is_pack_limit');
		} else {
			$this->data['russianpost2_is_pack_limit'] = 'nopack';
		}
		
		
		if (isset($this->request->post['russianpost2_method_image_html'])) {
			$this->data['russianpost2_method_image_html'] = $this->request->post['russianpost2_method_image_html'];
		} elseif( $this->config->has('russianpost2_method_image_html') ) {
			$this->data['russianpost2_method_image_html'] = $this->config->get('russianpost2_method_image_html');
		} else {
			$this->data['russianpost2_method_image_html'] = htmlentities( '<img src="{image_url}" width="{width}" height="{height}"  style="margin-right: 10px;">{title}' );
		}
		
		if (isset($this->request->post['russianpost2_submethod_image_html'])) {
			$this->data['russianpost2_submethod_image_html'] = $this->request->post['russianpost2_submethod_image_html'];
		} elseif( $this->config->has('russianpost2_submethod_image_html') ) {
			$this->data['russianpost2_submethod_image_html'] = $this->config->get('russianpost2_submethod_image_html');
		} else {
			$this->data['russianpost2_submethod_image_html'] = htmlentities( '<img src="{image_url}" width="{width}" height="{height}" style="margin-right: 10px;">{title}' );
		}
		
		$this->data['text_image_header'] = $this->language->get('text_image_header');
		$this->data['text_image_notice'] = $this->language->get('text_image_notice');
		
		
		$this->data['entry_method_image_html'] = $this->language->get('entry_method_image_html');
		$this->data['entry_submethod_image_html'] = $this->language->get('entry_submethod_image_html');
		
		// -------
		
		$this->data['col_filter_geozone']	= $this->language->get('col_filter_geozone');
		$this->data['col_filter_region']	= $this->language->get('col_filter_region');

		$this->data['text_order_price']	= $this->language->get('text_order_price');
		$this->data['text_order_weight']	= $this->language->get('text_order_weight');
		$this->data['text_order_sizes']	= $this->language->get('text_order_sizes');
		
		$this->data['entry_manufacturer']	= $this->language->get('entry_manufacturer');
		$this->data['entry_category']	= $this->language->get('entry_category');
		$this->data['button_filter_add']	= $this->language->get('button_filter_add');
		$this->data['col_filter_filtername']	= $this->language->get('col_filter_filtername');
		$this->data['col_text_region_geozone']	= $this->language->get('col_text_region_geozone');
		$this->data['col_filter_name']	= $this->language->get('col_filter_name');
		$this->data['col_filter_category']	= $this->language->get('col_filter_category');
		$this->data['col_filter_manufacturer']	= $this->language->get('col_filter_manufacturer');
		$this->data['col_filter_sort_order']	= $this->language->get('col_filter_sort_order');
		$this->data['text_select_region_geozone']	= $this->language->get('text_select_region_geozone');
		$this->data['text_productname_header']	= $this->language->get('text_productname_header');
		$this->data['text_productmodel_header']	= $this->language->get('text_productmodel_header');
		$this->data['col_filter_filterproduct']	= $this->language->get('col_filter_filterproduct');
		$this->data['text_select_region_geozone']	= $this->language->get('text_select_region_geozone');
		$this->data['text_geozone']	= $this->language->get('text_geozone');
		
		$this->data['col_adds_name'] = $this->language->get('col_adds_name');
		$this->data['col_adds_cost'] = $this->language->get('col_adds_cost');
		$this->data['text_all_product'] = $this->language->get('text_all_product');
		$this->data['text_one_product'] = $this->language->get('text_one_product');
		
		$this->data['text_search_sub']	= $this->language->get('text_search_sub');
		$this->data['text_search_sub_noright']	= $this->language->get('text_search_sub_noright');
		$this->data['text_search_sub_noleft']	= $this->language->get('text_search_sub_noleft');
		$this->data['text_search_strict']	= $this->language->get('text_search_strict');
		
		$this->data['text_default_weight_type_order']	= $this->language->get('text_default_weight_type_order');
		$this->data['text_default_weight_type_product']	= $this->language->get('text_default_weight_type_product');
		
		$this->data['entry_product_adds_type']	= $this->language->get('entry_product_adds_type');
		$this->data['entry_order_adds_type']	= $this->language->get('entry_order_adds_type');
		$this->data['text_adds_type_all']	= $this->language->get('text_adds_type_all');
		$this->data['text_adds_type_one']	= $this->language->get('text_adds_type_one');
		$this->data['text_adds_type_byfilter']	= $this->language->get('text_adds_type_byfilter');
		$this->data['text_except_product']	= $this->language->get('text_except_product');
		
		$this->data['text_tag_to_country']	= $this->language->get('text_tag_to_country');
		$this->data['text_tag_to_country_example']	= $this->language->get('text_tag_to_country_example');
		
		$this->data['text_tag_insurance']	= $this->language->get('text_tag_insurance');
		$this->data['text_tag_insurance_example']	= $this->language->get('text_tag_insurance_example');
		$this->data['text_tag_commission']	= $this->language->get('text_tag_commission');
		$this->data['text_tag_commission_example']	= $this->language->get('text_tag_commission_example');
		
		$this->data['text_tag_insurance_block']	= $this->language->get('text_tag_insurance_block');
		$this->data['text_tag_srok']	= $this->language->get('text_tag_srok');
		$this->data['text_tag_srok_example']	= $this->language->get('text_tag_srok_example');
		$this->data['text_tag_commission_block']	= $this->language->get('text_tag_commission_block');
		
		/* start 0510 */
		$this->data['text_tag_weight_kg']	= $this->language->get('text_tag_weight_kg');
		$this->data['text_tag_weight_g']	= $this->language->get('text_tag_weight_g');
		$this->data['text_tag_dimensions_cm']	= $this->language->get('text_tag_dimensions_cm');
		
		$this->data['text_tag_weight_kg_example']	= $this->language->get('text_tag_weight_kg_example');
		$this->data['text_tag_weight_g_example']	= $this->language->get('text_tag_weight_g_example');
		$this->data['text_tag_dimensions_cm_example']	= $this->language->get('text_tag_dimensions_cm_example');
		/* end 0510 */
		
		/* start 2308 */
		$this->data['text_tag_shipping_cost']	= $this->language->get('text_tag_shipping_cost');
		$this->data['text_tag_shipping_cost_example']	= $this->language->get('text_tag_shipping_cost_example');
		/* end 2308 */
		
		$this->data['entry_order_nullsize']	= $this->language->get('entry_order_nullsize');
		$this->data['text_order_nullsize_setdefault']	= $this->language->get('text_order_nullsize_setdefault');
		$this->data['entry_product_nullsize']	= $this->language->get('entry_product_nullsize');
		$this->data['entry_order_nullweight']	= $this->language->get('entry_order_nullweight');
		$this->data['text_product_nullsize_setnull']	= $this->language->get('text_product_nullsize_setnull');
		$this->data['text_product_nullsize_setdefault']	= $this->language->get('text_product_nullsize_setdefault');
		$this->data['text_nullsize_hide']	= $this->language->get('text_nullsize_hide');
		$this->data['text_nullsize_show']	= $this->language->get('text_nullsize_show');
		$this->data['entry_product_default_size']	= $this->language->get('entry_product_default_size');
		$this->data['entry_order_default_size']	= $this->language->get('entry_order_default_size');
		$this->data['entry_tax_class']	= $this->language->get('entry_tax_class');
		$this->data['entry_insurance_base']	= $this->language->get('entry_insurance_base');
		$this->data['entry_insurance_base_total']	= $this->language->get('entry_insurance_base_total');
		$this->data['entry_insurance_base_products']	= $this->language->get('entry_insurance_base_products');
		$this->data['text_none']	= $this->language->get('text_none');
		$this->data['entry_sort_order']	= $this->language->get('entry_sort_order');
		
		$this->data['entry_is_nds']	= $this->language->get('entry_is_nds');
		
		/* start 1511 */
		$this->data['entry_from_postcode_notice']	= $this->language->get('entry_from_postcode_notice');
		/* end 1511 */
		
		$this->data['update_action'] = $this->url->link('shipping/russianpost2/uploadt', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['russianpost2_tax_class_id'])) {
			$this->data['russianpost2_tax_class_id'] = $this->request->post['russianpost2_tax_class_id'];
		} else {
			$this->data['russianpost2_tax_class_id'] = $this->config->get('russianpost2_tax_class_id');
		}

		if (isset($this->request->post['russianpost2_is_nds'])) {
			$this->data['russianpost2_is_nds'] = $this->request->post['russianpost2_is_nds'];
		} elseif( $this->config->has('russianpost2_is_nds') ) {
			$this->data['russianpost2_is_nds'] = $this->config->get('russianpost2_is_nds');
		} else {
			$this->data['russianpost2_is_nds'] = 1;
		}
		
		if (isset($this->request->post['russianpost2_sort_order'])) {
			$this->data['russianpost2_sort_order'] = $this->request->post['russianpost2_sort_order'];
		} elseif( $this->config->has('russianpost2_sort_order') ) {
			$this->data['russianpost2_sort_order'] = $this->config->get('russianpost2_sort_order');
		} else {
			$this->data['russianpost2_sort_order'] = 1;
		}
		
		$this->data['token'] = $this->session->data['token'];
		
		// --------
		
		if (isset($this->request->post['russianpost2_from_region'])) {
			$this->data['russianpost2_from_region'] = $this->request->post['russianpost2_from_region'];
		} elseif( $this->config->has('russianpost2_from_region') ) {
			$this->data['russianpost2_from_region'] = $this->config->get('russianpost2_from_region');
		} else {
			$moscow = $this->model_shipping_russianpost2->getMoscowRegion();
			
			if( !empty($moscow['zone_id']) )
				$this->data['russianpost2_from_region'] = $moscow['zone_id'];
			else
				$this->data['russianpost2_from_region'] = '';
		}
		
		if (isset($this->request->post['russianpost2_printpost_api_status'])) {
			$this->data['russianpost2_printpost_api_status'] = $this->request->post['russianpost2_printpost_api_status'];
		} elseif( $this->config->has('russianpost2_printpost_api_status') ) {
			$this->data['russianpost2_printpost_api_status'] = $this->config->get('russianpost2_printpost_api_status');
		} else {
			$this->data['russianpost2_printpost_api_status'] = 1;
		}
		
		
		if (isset($this->request->post['russianpost2_api_sfp_synx_status'])) {
			$this->data['russianpost2_api_sfp_synx_status'] = $this->request->post['russianpost2_api_sfp_synx_status'];
		} elseif( $this->config->has('russianpost2_api_sfp_synx_status') ) {
			$this->data['russianpost2_api_sfp_synx_status'] = $this->config->get('russianpost2_api_sfp_synx_status');
		} else {
			$this->data['russianpost2_api_sfp_synx_status'] = 1;
		}
		
		
		
		if (isset($this->request->post['russianpost2_from_postcode'])) {
			$this->data['russianpost2_from_postcode'] = $this->request->post['russianpost2_from_postcode'];
		} elseif( $this->config->has('russianpost2_from_postcode') ) {
			$this->data['russianpost2_from_postcode'] = $this->config->get('russianpost2_from_postcode');
		} else {
			$this->data['russianpost2_from_postcode'] = '101000';
		}
		
		if (isset($this->request->post['russianpost2_from_city'])) {
			$this->data['russianpost2_from_city'] = $this->request->post['russianpost2_from_city'];
		} elseif( $this->config->has('russianpost2_from_city') ) {
			$this->data['russianpost2_from_city'] = $this->config->get('russianpost2_from_city');
		} else {
			$this->data['russianpost2_from_city'] = $this->language->get('text_default_city');
		}
		
		if (isset($this->request->post['russianpost2_product_nullsize'])) {
			$this->data['russianpost2_product_nullsize'] = $this->request->post['russianpost2_product_nullsize'];
		} else {
			$this->data['russianpost2_product_nullsize'] = $this->config->get('russianpost2_product_nullsize');
		}	
		
		if (isset($this->request->post['russianpost2_order_nullsize'])) {
			$this->data['russianpost2_order_nullsize'] = $this->request->post['russianpost2_order_nullsize'];
		} else {
			$this->data['russianpost2_order_nullsize'] = $this->config->get('russianpost2_order_nullsize');
		}	
		
		
		
		if (isset($this->request->post['russianpost2_product_default_width'])) {
			$this->data['russianpost2_product_default_width'] = $this->request->post['russianpost2_product_default_width'];
		} elseif( $this->config->has('russianpost2_product_default_width') ) {
			$this->data['russianpost2_product_default_width'] = $this->config->get('russianpost2_product_default_width');
		} else {
			$this->data['russianpost2_product_default_width'] = 10;
		}	
		
		if (isset($this->request->post['russianpost2_product_default_height'])) {
			$this->data['russianpost2_product_default_height'] = $this->request->post['russianpost2_product_default_height'];
		} elseif( $this->config->has('russianpost2_product_default_height') ) {
			$this->data['russianpost2_product_default_height'] = $this->config->get('russianpost2_product_default_height');
		} else {
			$this->data['russianpost2_product_default_height'] = 10;
		}	
		
		if (isset($this->request->post['russianpost2_product_default_length'])) {
			$this->data['russianpost2_product_default_length'] = $this->request->post['russianpost2_product_default_length'];
		} elseif( $this->config->has('russianpost2_product_default_length') ) {
			$this->data['russianpost2_product_default_length'] = $this->config->get('russianpost2_product_default_length');
		} else {
			$this->data['russianpost2_product_default_length'] = 8;
		}	
		
		// ------------
		
		
		if (isset($this->request->post['russianpost2_order_default_width'])) {
			$this->data['russianpost2_order_default_width'] = $this->request->post['russianpost2_order_default_width'];
		} elseif( $this->config->has('russianpost2_order_default_width') ) {
			$this->data['russianpost2_order_default_width'] = $this->config->get('russianpost2_order_default_width');
		} else {
			$this->data['russianpost2_order_default_width'] = 10;
		}	
		
		if (isset($this->request->post['russianpost2_order_default_height'])) {
			$this->data['russianpost2_order_default_height'] = $this->request->post['russianpost2_order_default_height'];
		} elseif( $this->config->has('russianpost2_order_default_height') ) {
			$this->data['russianpost2_order_default_height'] = $this->config->get('russianpost2_order_default_height');
		} else {
			$this->data['russianpost2_order_default_height'] = 10;
		}	
		
		if (isset($this->request->post['russianpost2_order_default_length'])) {
			$this->data['russianpost2_order_default_length'] = $this->request->post['russianpost2_order_default_length'];
		} elseif( $this->config->has('russianpost2_order_default_length') ) {
			$this->data['russianpost2_order_default_length'] = $this->config->get('russianpost2_order_default_length');
		} else {
			$this->data['russianpost2_order_default_length'] = 8;
		}	
		
		
		$this->data['entry_use_max_product_weight']	= $this->language->get('entry_use_max_product_weight');
		if (isset($this->request->post['russianpost2_use_max_product_weight'])) {
			$this->data['russianpost2_use_max_product_weight'] = $this->request->post['russianpost2_use_max_product_weight'];
		} else {
			$this->data['russianpost2_use_max_product_weight'] = $this->config->get('russianpost2_use_max_product_weight');
		}	
		
		// ------------
		
		if (isset($this->request->post['russianpost2_product_nullweight'])) {
			$this->data['russianpost2_product_nullweight'] = $this->request->post['russianpost2_product_nullweight'];
		} else {
			$this->data['russianpost2_product_nullweight'] = $this->config->get('russianpost2_product_nullweight');
		}	
		
		/* start 2401 */
		$this->data['entry_weight_source']	= $this->language->get('entry_weight_source');
		$this->data['entry_sizes_source']	= $this->language->get('entry_sizes_source');
		
		$this->data['entry_weight_source_cart']	= $this->language->get('entry_weight_source_cart');
		$this->data['entry_weight_source_product']	= $this->language->get('entry_weight_source_product');
		
		if (isset($this->request->post['russianpost2_weight_source'])) {
			$this->data['russianpost2_weight_source'] = $this->request->post['russianpost2_weight_source'];
		} elseif( $this->config->get('russianpost2_weight_source') ) {
			$this->data['russianpost2_weight_source'] = $this->config->get('russianpost2_weight_source');
		} else {
			$this->data['russianpost2_weight_source'] = 'product';
		}	
		
		if (isset($this->request->post['russianpost2_sizes_source'])) {
			$this->data['russianpost2_sizes_source'] = $this->request->post['russianpost2_sizes_source'];
		} elseif( $this->config->get('russianpost2_sizes_source') ) {
			$this->data['russianpost2_sizes_source'] = $this->config->get('russianpost2_sizes_source');
		} else {
			$this->data['russianpost2_sizes_source'] = 'product';
		}		
			
		
		/* end 2401 */
		
		if (isset($this->request->post['russianpost2_order_nullweight'])) {
			$this->data['russianpost2_order_nullweight'] = $this->request->post['russianpost2_order_nullweight'];
		} else {
			$this->data['russianpost2_order_nullweight'] = $this->config->get('russianpost2_order_nullweight');
		}				
		
		/* start 3005 */
		$this->data['entry_product_replace_weight']	= $this->language->get('entry_product_replace_weight');
		$this->data['entry_product_replace_size']	= $this->language->get('entry_product_replace_size');
		
		$this->data['entry_order_replace_weight']	= $this->language->get('entry_order_replace_weight');
		$this->data['entry_order_replace_weight_notice']	= $this->language->get('entry_order_replace_weight_notice');
		$this->data['entry_order_replace_size']	= $this->language->get('entry_order_replace_size');
		$this->data['entry_order_replace_size_notice']	= $this->language->get('entry_order_replace_size_notice');
		
		if (isset($this->request->post['russianpost2_product_replace_weight'])) {
			$this->data['russianpost2_product_replace_weight'] = $this->request->post['russianpost2_product_replace_weight'];
		} else {
			$this->data['russianpost2_product_replace_weight'] = $this->config->get('russianpost2_product_replace_weight');
		}
		
		if (isset($this->request->post['russianpost2_product_replace_size'])) {
			$this->data['russianpost2_product_replace_size'] = $this->request->post['russianpost2_product_replace_size'];
		} else {
			$this->data['russianpost2_product_replace_size'] = $this->config->get('russianpost2_product_replace_size');
		}
		
		if (isset($this->request->post['russianpost2_order_replace_weight'])) {
			$this->data['russianpost2_order_replace_weight'] = $this->request->post['russianpost2_order_replace_weight'];
		} else {
			$this->data['russianpost2_order_replace_weight'] = $this->config->get('russianpost2_order_replace_weight');
		}
		
		if (isset($this->request->post['russianpost2_order_replace_size'])) {
			$this->data['russianpost2_order_replace_size'] = $this->request->post['russianpost2_order_replace_size'];
		} else {
			$this->data['russianpost2_order_replace_size'] = $this->config->get('russianpost2_order_replace_size');
		}
		
		/* end 3005 */
		
		$this->data['entry_is_custom_calc_function'] = $this->language->get('entry_is_custom_calc_function');
		$this->data['entry_is_custom_calc_function_notice'] = $this->language->get('entry_is_custom_calc_function_notice');
		
		if (isset($this->request->post['russianpost2_is_custom_calc_function'])) {
			$this->data['russianpost2_is_custom_calc_function'] = $this->request->post['russianpost2_is_custom_calc_function'];
		} elseif( $this->config->has('russianpost2_is_custom_calc_function') ) {
			$this->data['russianpost2_is_custom_calc_function'] = $this->config->get('russianpost2_is_custom_calc_function');
		} else {
			$this->data['russianpost2_is_custom_calc_function'] = 0;
		}
		
		if (isset($this->request->post['russianpost2_product_default_weight'])) {
			$this->data['russianpost2_product_default_weight'] = $this->request->post['russianpost2_product_default_weight'];
		} elseif( $this->config->has('russianpost2_product_default_weight') ) {
			$this->data['russianpost2_product_default_weight'] = $this->config->get('russianpost2_product_default_weight');
		} else {
			$this->data['russianpost2_product_default_weight'] = 1000;
		}	
		
		if (isset($this->request->post['russianpost2_order_default_weight'])) {
			$this->data['russianpost2_order_default_weight'] = $this->request->post['russianpost2_order_default_weight'];
		} elseif( $this->config->has('russianpost2_order_default_weight') ) {
			$this->data['russianpost2_order_default_weight'] = $this->config->get('russianpost2_order_default_weight');
		} else {
			$this->data['russianpost2_order_default_weight'] = 1000;
		}	
		
		if (isset($this->request->post['russianpost2_product_nullweight'])) {
			$this->data['russianpost2_default_weight_type'] = $this->request->post['russianpost2_default_weight_type'];
		} elseif( $this->config->has('russianpost2_default_weight_type') ) {
			$this->data['russianpost2_default_weight_type'] = $this->config->get('russianpost2_default_weight_type');
		} else {
			$this->data['russianpost2_default_weight_type'] = 'product';
		}
		
		
		if (isset($this->request->post['russianpost2_ifnocountry'])) {
			$this->data['russianpost2_ifnocountry'] = $this->request->post['russianpost2_ifnocountry'];
		} elseif( $this->config->has('russianpost2_ifnocountry') ) {
			$this->data['russianpost2_ifnocountry'] = $this->config->get('russianpost2_ifnocountry');
		} else {
			$this->data['russianpost2_ifnocountry'] = 'default';
		}
		
		if (isset($this->request->post['russianpost2_ifnoregion'])) {
			$this->data['russianpost2_ifnoregion'] = $this->request->post['russianpost2_ifnoregion'];
		} elseif( $this->config->has('russianpost2_ifnoregion') ) {
			$this->data['russianpost2_ifnoregion'] = $this->config->get('russianpost2_ifnoregion');
		} else {
			$this->data['russianpost2_ifnoregion'] = 'default';
		}
		
		if (isset($this->request->post['russianpost2_default_region'])) {
			$this->data['russianpost2_default_region'] = $this->request->post['russianpost2_default_region'];
		} elseif( $this->config->has('russianpost2_default_region') ) {
			$this->data['russianpost2_default_region'] = $this->config->get('russianpost2_default_region');
		} else {
			$this->data['russianpost2_default_region'] = '';
		}
		
		if (isset($this->request->post['russianpost2_default_region'])) {
			$this->data['russianpost2_default_region'] = $this->request->post['russianpost2_default_region'];
		} elseif( $this->config->has('russianpost2_default_region') ) {
			$this->data['russianpost2_default_region'] = $this->config->get('russianpost2_default_region');
		} else {
			$moscow = $this->model_shipping_russianpost2->getMoscowRegion();
			
			if( !empty($moscow['zone_id']) )
				$this->data['russianpost2_default_region'] = $moscow['zone_id'];
			else
				$this->data['russianpost2_default_region'] = '';
		}
		
		/* start 0802 */
		$this->data['entry_calc_by_region_for_remote']	= $this->language->get('entry_calc_by_region_for_remote');
		$this->data['entry_calc_by_region_for_remote_notice']	= $this->language->get('entry_calc_by_region_for_remote_notice');
		
		if (isset($this->request->post['russianpost2_calc_by_region_for_remote'])) {
			$this->data['russianpost2_calc_by_region_for_remote'] = $this->request->post['russianpost2_calc_by_region_for_remote'];
		} elseif( $this->config->has('russianpost2_calc_by_region_for_remote') ) {
			$this->data['russianpost2_calc_by_region_for_remote'] = $this->config->get('russianpost2_calc_by_region_for_remote');
		} else {
			$this->data['russianpost2_calc_by_region_for_remote'] = 0;
		}
		
		/* start 0605 */
		$this->data['entry_russianpost2_ifnopostcode']	= $this->language->get('entry_russianpost2_ifnopostcode');
		$this->data['entry_russianpost2_ifnopostcode_on']	= $this->language->get('entry_russianpost2_ifnopostcode_on');
		$this->data['entry_russianpost2_ifnopostcode_off']	= $this->language->get('entry_russianpost2_ifnopostcode_off');
		
		if (isset($this->request->post['russianpost2_ifnopostcode'])) {
			$this->data['russianpost2_ifnopostcode'] = $this->request->post['russianpost2_ifnopostcode'];
		} elseif( $this->config->has('russianpost2_ifnopostcode') ) {
			$this->data['russianpost2_ifnopostcode'] = $this->config->get('russianpost2_ifnopostcode');
		} else {
			$this->data['russianpost2_ifnopostcode'] = 'on';
		}
		/* end 0605 */
		
		/* start 1510 */
		$this->data['entry_russianpost2_ifnouserpostcode']	= $this->language->get('entry_russianpost2_ifnouserpostcode');
		$this->data['entry_russianpost2_ifnouserpostcode_notice']	= $this->language->get('entry_russianpost2_ifnouserpostcode_notice');
		$this->data['entry_russianpost2_ifnouserpostcode_usedetected']	= $this->language->get('entry_russianpost2_ifnouserpostcode_usedetected');
		$this->data['entry_russianpost2_ifnouserpostcode_skip']	= $this->language->get('entry_russianpost2_ifnouserpostcode_skip');
		
		if (isset($this->request->post['russianpost2_ifnouserpostcode'])) {
			$this->data['russianpost2_ifnouserpostcode'] = $this->request->post['russianpost2_ifnouserpostcode'];
		} elseif( $this->config->has('russianpost2_ifnouserpostcode') ) {
			$this->data['russianpost2_ifnouserpostcode'] = $this->config->get('russianpost2_ifnouserpostcode');
		} else {
			$this->data['russianpost2_ifnouserpostcode'] = '';
		}
		/* end 1510 */
		
		$this->data['entry_is_ignore_user_postcode']	= $this->language->get('entry_is_ignore_user_postcode');
		$this->data['entry_is_ignore_user_postcode_byregion']	= $this->language->get('entry_is_ignore_user_postcode_byregion');
		
		if (isset($this->request->post['russianpost2_is_ignore_user_postcode'])) {
			$this->data['russianpost2_is_ignore_user_postcode'] = $this->request->post['russianpost2_is_ignore_user_postcode'];
		} elseif( $this->config->has('russianpost2_is_ignore_user_postcode') ) {
			$this->data['russianpost2_is_ignore_user_postcode'] = $this->config->get('russianpost2_is_ignore_user_postcode');
		} else {
			$this->data['russianpost2_is_ignore_user_postcode'] = '';
		}
		
		/* end 0802 */
		if (isset($this->request->post['russianpost2_ifnocity'])) {
			$this->data['russianpost2_ifnocity'] = $this->request->post['russianpost2_ifnocity'];
		} elseif( $this->config->has('russianpost2_ifnocity') ) {
			$this->data['russianpost2_ifnocity'] = $this->config->get('russianpost2_ifnocity');
		} else {
			$this->data['russianpost2_ifnocity'] = 'default';
		}
		
		if (isset($this->request->post['russianpost2_default_city'])) {
			$this->data['russianpost2_default_city'] = $this->request->post['russianpost2_default_city'];
		} elseif( $this->config->has('russianpost2_default_city') ) {
			$this->data['russianpost2_default_city'] = $this->config->get('russianpost2_default_city');
		} else {
			$this->data['russianpost2_default_city'] = $this->language->get('text_default_city');
		}
		
		
		if (isset($this->request->post['russianpost2_product_adds_type'])) {
			$this->data['russianpost2_product_adds_type'] = $this->request->post['russianpost2_product_adds_type'];
		} elseif( $this->config->has('russianpost2_product_adds_type') ) {
			$this->data['russianpost2_product_adds_type'] = $this->config->get('russianpost2_product_adds_type');
		} else {
			$this->data['russianpost2_product_adds_type'] = 'byfilter';
		}
		
		if (isset($this->request->post['russianpost2_order_adds_type'])) {
			$this->data['russianpost2_order_adds_type'] = $this->request->post['russianpost2_order_adds_type'];
		} elseif( $this->config->has('russianpost2_order_adds_type') ) {
			$this->data['russianpost2_order_adds_type'] = $this->config->get('russianpost2_order_adds_type');
		} else {
			$this->data['russianpost2_order_adds_type'] = 'byfilter';
		}
		
		/* start 112 */
		
		$this->data['tab_packs']	= $this->language->get('tab_packs');
		$this->data['text_russianpost2_packs']	= $this->language->get('text_russianpost2_packs');
		$this->data['text_custom_packs_header']	= $this->language->get('text_custom_packs_header');
		$this->data['text_russianpost_packs_header']	= $this->language->get('text_russianpost_packs_header');
		
		$this->data['text_is_pack']	= $this->language->get('text_is_pack');
		$this->data['col_packs_name']	= $this->language->get('col_packs_name');
		$this->data['col_packs_sizes']	= $this->language->get('col_packs_sizes');
		$this->data['col_packs_price']	= $this->language->get('col_packs_price');
		$this->data['col_packs_status']	= $this->language->get('col_packs_status');
		$this->data['button_custom_pack_add']	= $this->language->get('button_custom_pack_add');
		
		
		$this->data['packs'] = array();
		$russianpost2_packs = $this->config->get('russianpost2_packs');
		
		$packs = $this->model_shipping_russianpost2->getPacks();
		
		foreach( $packs as $pack )
		{
			$status = $pack['default_value'];
			$price = $pack['price'];
			
			if( isset( $russianpost2_packs[ $pack['pack_key'] ] ) )
			{
				$status = isset( $russianpost2_packs[ $pack['pack_key'] ]['status'] ) ? 
					$russianpost2_packs[ $pack['pack_key'] ]['status'] : '';
				$price =  isset( $russianpost2_packs[ $pack['pack_key'] ]['price'] ) ?
					$russianpost2_packs[ $pack['pack_key'] ]['price'] : '';
			}
			
			$this->data['packs'][] = array(
				"pack_key" => $pack['pack_key'],
				"name" => $pack['name'],
				"length" => $pack['length']/10,
				"width" => $pack['width']/10,
				"height" => $pack['height']/10,
				"tariff_pack_id" => $pack['tariff_pack_id'],
				"price" => $price,
				"status" => $status,
			);
		}
		
		$this->data['custom_packs'] = array();
		
		$russianpost2_custom_packs = $this->config->get('russianpost2_custom_packs');
		
		if( $russianpost2_custom_packs )
		{
			foreach( $russianpost2_custom_packs as $custom_pack )
			{
				$this->data['custom_packs'][] = $custom_pack;
			}
		}
		
		$this->data['entry_tariff_cache_lifetime']	= $this->language->get('entry_tariff_cache_lifetime');
		$this->data['entry_tariff_cache']	= $this->language->get('entry_tariff_cache');
		
		if (isset($this->request->post['russianpost2_api_tariff_status'])) {
			$this->data['russianpost2_api_tariff_status'] = $this->request->post['russianpost2_api_tariff_status'];
		} elseif( $this->config->has('russianpost2_api_tariff_status') ) {
			$this->data['russianpost2_api_tariff_status'] = $this->config->get('russianpost2_api_tariff_status');
		} else {
			$this->data['russianpost2_api_tariff_status'] = '1';
		}
		
		if (isset($this->request->post['russianpost2_api_tariff_sort_order'])) {
			$this->data['russianpost2_api_tariff_sort_order'] = $this->request->post['russianpost2_api_tariff_sort_order'];
		} elseif( $this->config->has('russianpost2_api_tariff_sort_order') ) {
			$this->data['russianpost2_api_tariff_sort_order'] = $this->config->get('russianpost2_api_tariff_sort_order');
		} else {
			$this->data['russianpost2_api_tariff_sort_order'] = '1';
		}
		
		if (isset($this->request->post['russianpost2_tariff_cache_lifetime'])) {
			$this->data['russianpost2_tariff_cache_lifetime'] = $this->request->post['russianpost2_tariff_cache_lifetime'];
		} elseif( $this->config->has('russianpost2_tariff_cache_lifetime') ) {
			$this->data['russianpost2_tariff_cache_lifetime'] = $this->config->get('russianpost2_tariff_cache_lifetime');
		} else {
			$this->data['russianpost2_tariff_cache_lifetime'] = '10';
		}
		
		if (isset($this->request->post['russianpost2_api_tariff_cache'])) {
			$this->data['russianpost2_api_tariff_cache'] = $this->request->post['russianpost2_api_tariff_cache'];
		} elseif( $this->config->has('russianpost2_api_tariff_cache') ) {
			$this->data['russianpost2_api_tariff_cache'] = $this->config->get('russianpost2_api_tariff_cache');
		} else {
			$this->data['russianpost2_api_tariff_cache'] = '1';
		}
		
		/* end 112 */
		
		
		if (isset($this->request->post['russianpost2_api_sfp_sort_order'])) {
			$this->data['russianpost2_api_sfp_sort_order'] = $this->request->post['russianpost2_api_sfp_sort_order'];
		} elseif( $this->config->has('russianpost2_api_sfp_sort_order') ) {
			$this->data['russianpost2_api_sfp_sort_order'] = $this->config->get('russianpost2_api_sfp_sort_order');
		} else {
			$this->data['russianpost2_api_sfp_sort_order'] = '1';
		}
		
		if (isset($this->request->post['russianpost2_api_ems_sort_order'])) {
			$this->data['russianpost2_api_ems_sort_order'] = $this->request->post['russianpost2_api_ems_sort_order'];
		} elseif( $this->config->has('russianpost2_api_ems_sort_order') ) {
			$this->data['russianpost2_api_ems_sort_order'] = $this->config->get('russianpost2_api_ems_sort_order');
		} else {
			$this->data['russianpost2_api_ems_sort_order'] = '2';
		}
		
		if (isset($this->request->post['russianpost2_api_russianpost_sort_order'])) {
			$this->data['russianpost2_api_russianpost_sort_order'] = $this->request->post['russianpost2_api_russianpost_sort_order'];
		} elseif( $this->config->has('russianpost2_api_russianpost_sort_order') ) {
			$this->data['russianpost2_api_russianpost_sort_order'] = $this->config->get('russianpost2_api_russianpost_sort_order');
		} else {
			$this->data['russianpost2_api_russianpost_sort_order'] = '3';
		}
		
		if (isset($this->request->post['russianpost2_api_postcalc_sort_order'])) {
			$this->data['russianpost2_api_postcalc_sort_order'] = $this->request->post['russianpost2_api_postcalc_sort_order'];
		} elseif( $this->config->has('russianpost2_api_postcalc_sort_order') ) {
			$this->data['russianpost2_api_postcalc_sort_order'] = $this->config->get('russianpost2_api_postcalc_sort_order');
		} else {
			$this->data['russianpost2_api_postcalc_sort_order'] = '4';
		}
		
		/* start 0110 */
		$this->data['button_add_adds'] = $this->language->get('button_add_adds');
		$this->data['text_select_adds'] = $this->language->get('text_select_adds');
		$this->data['text_order_adds_cost_total_perc'] = $this->language->get('text_order_adds_cost_total_perc');
		/* end 0110 */
		
		/* start metka-2006 */
		$this->data['entry_otpravka_cache'] = $this->language->get('entry_otpravka_cache');
		$this->data['entry_otpravka_cache_lifetime'] = $this->language->get('entry_otpravka_cache_lifetime');
		
		$this->data['entry_api_otpravka_token'] = $this->language->get('entry_api_otpravka_token');
		$this->data['entry_api_otpravka_token_notice'] = $this->language->get('entry_api_otpravka_token_notice');
		$this->data['entry_api_otpravka_key'] = $this->language->get('entry_api_otpravka_key');
		$this->data['entry_api_otpravka_key_notice'] = $this->language->get('entry_api_otpravka_key_notice');

		if (isset($this->request->post['russianpost2_api_otpravka_key'])) {
			$this->data['russianpost2_api_otpravka_key'] = $this->request->post['russianpost2_api_otpravka_key'];
		} elseif( $this->config->has('russianpost2_api_otpravka_key') ) {
			$this->data['russianpost2_api_otpravka_key'] = $this->config->get('russianpost2_api_otpravka_key');
		} else {
			$this->data['russianpost2_api_otpravka_key'] = '';
		}
		
		if (isset($this->request->post['russianpost2_api_otpravka_token'])) {
			$this->data['russianpost2_api_otpravka_token'] = $this->request->post['russianpost2_api_otpravka_token'];
		} elseif( $this->config->has('russianpost2_api_otpravka_token') ) {
			$this->data['russianpost2_api_otpravka_token'] = $this->config->get('russianpost2_api_otpravka_token');
		} else {
			$this->data['russianpost2_api_otpravka_token'] = '';
		}
		
		if (isset($this->request->post['russianpost2_api_otpravka_sort_order'])) {
			$this->data['russianpost2_api_otpravka_sort_order'] = $this->request->post['russianpost2_api_otpravka_sort_order'];
		} elseif( $this->config->has('russianpost2_api_otpravka_sort_order') ) {
			$this->data['russianpost2_api_otpravka_sort_order'] = $this->config->get('russianpost2_api_otpravka_sort_order');
		} else {
			$this->data['russianpost2_api_otpravka_sort_order'] = '3';
		}
		
		if (isset($this->request->post['russianpost2_api_otpravka_status'])) {
			$this->data['russianpost2_api_otpravka_status'] = $this->request->post['russianpost2_api_otpravka_status'];
		} elseif( $this->config->has('russianpost2_api_otpravka_status') ) {
			$this->data['russianpost2_api_otpravka_status'] = $this->config->get('russianpost2_api_otpravka_status');
		} else {
			$this->data['russianpost2_api_otpravka_status'] = 1;
		}
		
		if (isset($this->request->post['russianpost2_api_otpravka_cache'])) {
			$this->data['russianpost2_api_otpravka_cache'] = $this->request->post['russianpost2_api_otpravka_cache'];
		} elseif( $this->config->has('russianpost2_api_otpravka_cache') ) {
			$this->data['russianpost2_api_otpravka_cache'] = $this->config->get('russianpost2_api_otpravka_cache');
		} else {
			$this->data['russianpost2_api_otpravka_cache'] = 1;
		}
		
		if (isset($this->request->post['russianpost2_otpravka_cache_lifetime'])) {
			$this->data['russianpost2_otpravka_cache_lifetime'] = $this->request->post['russianpost2_otpravka_cache_lifetime'];
		} elseif( $this->config->get('russianpost2_otpravka_cache_lifetime') ) {
			$this->data['russianpost2_otpravka_cache_lifetime'] = $this->config->get('russianpost2_otpravka_cache_lifetime');
		} else {
			$this->data['russianpost2_otpravka_cache_lifetime'] = 10;
		}
		/* end metka-2006 */
		
		// -------
		
		if (isset($this->request->post['russianpost2_api_synx_mode'])) {
			$this->data['russianpost2_api_synx_mode'] = $this->request->post['russianpost2_api_synx_mode'];
		} elseif( $this->config->has('russianpost2_api_synx_mode') ) {
			$this->data['russianpost2_api_synx_mode'] = $this->config->get('russianpost2_api_synx_mode');
		} else {
			$this->data['russianpost2_api_synx_mode'] = 'each_month';
		}
		
		if (isset($this->request->post['russianpost2_api_sfp_status'])) {
			$this->data['russianpost2_api_sfp_status'] = $this->request->post['russianpost2_api_sfp_status'];
		} elseif( $this->config->has('russianpost2_api_sfp_status') ) {
			$this->data['russianpost2_api_sfp_status'] = $this->config->get('russianpost2_api_sfp_status');
		} else {
			$this->data['russianpost2_api_sfp_status'] = 1;
		}
		
		if (isset($this->request->post['russianpost2_api_postcalc_status'])) {
			$this->data['russianpost2_api_postcalc_status'] = $this->request->post['russianpost2_api_postcalc_status'];
		} elseif( $this->config->has('russianpost2_api_postcalc_status') ) {
			$this->data['russianpost2_api_postcalc_status'] = $this->config->get('russianpost2_api_postcalc_status');
		} else {
			$this->data['russianpost2_api_postcalc_status'] = 1;
		}
		
		if (isset($this->request->post['russianpost2_insurance_base'])) {
			$this->data['russianpost2_insurance_base'] = $this->request->post['russianpost2_insurance_base'];
		} elseif( $this->config->has('russianpost2_insurance_base') ) {
			$this->data['russianpost2_insurance_base'] = $this->config->get('russianpost2_insurance_base');
		} else {
			$this->data['russianpost2_insurance_base'] = 'total';
		}
		
		/* start 0805 */
		$this->data['entry_if_nosrok'] = $this->language->get('entry_if_nosrok');
		$this->data['entry_if_nosrok_notice'] = $this->language->get('entry_if_nosrok_notice');
		$this->data['entry_if_nosrok_postcalc'] = $this->language->get('entry_if_nosrok_postcalc');
		$this->data['entry_if_nosrok_none'] = $this->language->get('entry_if_nosrok_none');
		$this->data['entry_if_nosrok_tariff'] = $this->language->get('entry_if_nosrok_tariff');
		
		if (isset($this->request->post['russianpost2_if_nosrok'])) {
			$this->data['russianpost2_if_nosrok'] = $this->request->post['russianpost2_if_nosrok'];
		} elseif( $this->config->has('russianpost2_if_nosrok') ) {
			$this->data['russianpost2_if_nosrok'] = $this->config->get('russianpost2_if_nosrok');
		} else {
			$this->data['russianpost2_if_nosrok'] = 'tariff';
		}
		
		/* end 0805 */
		
		
		if (isset($this->request->post['russianpost2_api_postcalc_cache'])) {
			$this->data['russianpost2_api_postcalc_cache'] = $this->request->post['russianpost2_api_postcalc_cache'];
		} elseif( $this->config->has('russianpost2_api_postcalc_cache') ) {
			$this->data['russianpost2_api_postcalc_cache'] = $this->config->get('russianpost2_api_postcalc_cache');
		} else {
			$this->data['russianpost2_api_postcalc_cache'] = 1;
		}
		
		if (isset($this->request->post['russianpost2_api_ems_cache'])) {
			$this->data['russianpost2_api_ems_cache'] = $this->request->post['russianpost2_api_ems_cache'];
		} elseif( $this->config->has('russianpost2_api_ems_cache') ) {
			$this->data['russianpost2_api_ems_cache'] = $this->config->get('russianpost2_api_ems_cache');
		} else {
			$this->data['russianpost2_api_ems_cache'] = 1;
		}
		
		
		/* start 2712 */
		$this->data['entry_postcalc_key'] = $this->language->get('entry_postcalc_key');
		$this->data['entry_postcalc_key_notice'] = $this->language->get('entry_postcalc_key_notice');
		
		if (isset($this->request->post['russianpost2_api_postcalc_key'])) {
			$this->data['russianpost2_api_postcalc_key'] = $this->request->post['russianpost2_api_postcalc_key'];
		} elseif( $this->config->has('russianpost2_api_postcalc_key') ) {
			$this->data['russianpost2_api_postcalc_key'] = $this->config->get('russianpost2_api_postcalc_key');
		} else {
			$this->data['russianpost2_api_postcalc_key'] = '';
		}
		/* end 2712 */
		if (isset($this->request->post['russianpost2_api_postcalc_email'])) {
			$this->data['russianpost2_api_postcalc_email'] = $this->request->post['russianpost2_api_postcalc_email'];
		} elseif( $this->config->has('russianpost2_api_postcalc_email') ) {
			$this->data['russianpost2_api_postcalc_email'] = $this->config->get('russianpost2_api_postcalc_email');
		} else {
			$this->data['russianpost2_api_postcalc_email'] = $this->config->get('config_email');
		}
		
		if (isset($this->request->post['russianpost2_api_ems_status'])) {
			$this->data['russianpost2_api_ems_status'] = $this->request->post['russianpost2_api_ems_status'];
		} elseif( $this->config->has('russianpost2_api_ems_status') ) {
			$this->data['russianpost2_api_ems_status'] = $this->config->get('russianpost2_api_ems_status');
		} else {
			$this->data['russianpost2_api_ems_status'] = 1;
		}
		
		if (isset($this->request->post['russianpost2_api_russianpost_status'])) {
			$this->data['russianpost2_api_russianpost_status'] = $this->request->post['russianpost2_api_russianpost_status'];
		} elseif( $this->config->has('russianpost2_api_russianpost_status') ) {
			$this->data['russianpost2_api_russianpost_status'] = $this->config->get('russianpost2_api_russianpost_status');
		} else {
			$this->data['russianpost2_api_russianpost_status'] = 1;
		}
		
		$this->data['api_list'] = array(
			/* start 112 */
			'tariff' => array(
				'api_key' => 'tariff',
				'api_name' => $this->language->get('entry_api_tariff_name'),
				'info' => $this->language->get('entry_api_tariff_info'),
				'condition' => $this->language->get('entry_api_tariff_condition'),
				'site' => 'https://tariff.pochta.ru',
			),
			'postcalc' => array(
				'api_key' => 'postcalc',
				'api_name' => $this->language->get('entry_api_postcalc_name'),
				'info' => $this->language->get('entry_api_postcalc_info'),
				'condition' => $this->language->get('entry_api_postcalc_condition'),
				'site' => 'http://postcalc.ru',
			),
			'otpravka' => array(
				'api_key' => 'otpravka',
				'api_name' => $this->language->get('entry_api_otpravka_name'),
				'info' => $this->language->get('entry_api_otpravka_info'),
				'condition' => $this->language->get('entry_api_otpravka_condition'),
				'site' => 'http://otpravka.pochta.ru',
			),
		);
				
				
		$this->data['text_tags_header'] = $this->language->get('text_tags_header');
		$this->data['col_tag_name'] = $this->language->get('col_tag_name');
		$this->data['col_tag_description'] = $this->language->get('col_tag_description');
		$this->data['col_tag_field'] = $this->language->get('col_tag_field');
		$this->data['text_tag_service_name'] = $this->language->get('text_tag_service_name');
		$this->data['text_tag_service_name_example'] = $this->language->get('text_tag_service_name_example');
		$this->data['text_tag_service_name_z'] = $this->language->get('text_tag_service_name_z');
		$this->data['text_tag_service_name_z_example'] = $this->language->get('text_tag_service_name_z_example');
		$this->data['text_tag_from_region'] = $this->language->get('text_tag_from_region');
		$this->data['text_tag_from_region_example'] = $this->language->get('text_tag_from_region_example');
		$this->data['text_tag_from_city'] = $this->language->get('text_tag_from_city');
		$this->data['text_tag_from_city_example'] = $this->language->get('text_tag_from_city_example');
		$this->data['text_tag_to_region'] = $this->language->get('text_tag_to_region');
		$this->data['text_tag_to_region_example'] = $this->language->get('text_tag_to_region_example');
		$this->data['text_tag_to_city'] = $this->language->get('text_tag_to_city');
		$this->data['text_tag_to_city_example'] = $this->language->get('text_tag_to_city_example');
		$this->data['text_tag_insurance'] = $this->language->get('text_tag_insurance');
		$this->data['text_tag_commission'] = $this->language->get('text_tag_commission');
		$this->data['text_tag_srok'] = $this->language->get('text_tag_srok');
		
		$this->data['text_adds_order_header'] = $this->language->get('text_adds_order_header');
		
		$this->data['text_select_filter'] = $this->language->get('text_select_filter');
		$this->data['col_adds_weight'] = $this->language->get('col_adds_weight');
		$this->data['col_adds_sizes'] = $this->language->get('col_adds_sizes');
		$this->data['button_add_adds'] = $this->language->get('button_add_adds');
		$this->data['col_adds_filter'] = $this->language->get('col_adds_filter');
		$this->data['col_adds_service'] = $this->language->get('col_adds_service');
		$this->data['col_adds_weight_sizes'] = $this->language->get('col_adds_weight_sizes');
		$this->data['col_adds_caution'] = $this->language->get('col_adds_caution');
		$this->data['col_adds_price'] = $this->language->get('col_adds_price');
		$this->data['col_adds_status'] = $this->language->get('col_adds_status');
		$this->data['col_adds_sort_order'] = $this->language->get('col_adds_sort_order');
		$this->data['text_adds_product_header'] = $this->language->get('text_adds_product_header');
		$this->data['text_tag_country_block'] = $this->language->get('text_tag_country_block');
		$this->data['button_update_tarif'] = $this->language->get('button_update_tarif');
		$this->data['text_update_tarif_yes'] = $this->language->get('text_update_tarif_yes');
		$this->data['text_update_tarif_no'] = $this->language->get('text_update_tarif_no');
		
		$this->data['entry_notifyme'] = $this->language->get('entry_notifyme');
		$this->data['entry_notifyme_email'] = $this->language->get('entry_notifyme_email');
		$this->data['entry_notifyme_notice'] = $this->language->get('entry_notifyme_notice');
		
		
		/* end synx */
		// ---------
		/* start metka-707 */
		$this->data['text_filter_regions_type'] = $this->language->get('text_filter_regions_type');
		$this->data['text_filter_regions_type_include_only'] = $this->language->get('text_filter_regions_type_include_only');
		$this->data['text_filter_regions_type_exclude'] = $this->language->get('text_filter_regions_type_exclude');
		/* end metka-707 */
		
		
		if (isset($this->request->post['russianpost2_status'])) {
			$this->data['russianpost2_status'] = $this->request->post['russianpost2_status'];
		} else {
			$this->data['russianpost2_status'] = $this->config->get('russianpost2_status');
		}
		
		if (isset($this->request->post['russianpost2_debug'])) {
			$this->data['russianpost2_debug'] = $this->request->post['russianpost2_debug'];
		} else {
			$this->data['russianpost2_debug'] = $this->config->get('russianpost2_debug');
		}
		
		if (isset($this->request->post['russianpost2_notifyme'])) {
			$this->data['russianpost2_notifyme'] = $this->request->post['russianpost2_notifyme'];
		} else {
			$this->data['russianpost2_notifyme'] = $this->config->get('russianpost2_notifyme');
		}
		
		if (isset($this->request->post['russianpost2_notifyme_email'])) {
			$this->data['russianpost2_notifyme_email'] = $this->request->post['russianpost2_notifyme_email'];
		} elseif( $this->config->has('russianpost2_notifyme_email') ) {
			$this->data['russianpost2_notifyme_email'] = $this->config->get('russianpost2_notifyme_email');
		} else {
			$this->data['russianpost2_notifyme_email'] = $this->config->get('config_email');
		}
		
		
		if (isset($this->request->post['russianpost2_tag_country_block'])) {
			$this->data['russianpost2_tag_country_block'] = $this->request->post['russianpost2_tag_country_block'];
		} elseif( $this->config->has('russianpost2_tag_country_block') ) {
			$this->data['russianpost2_tag_country_block'] = $this->config->get('russianpost2_tag_country_block');
		} else {
			$this->data['russianpost2_tag_country_block'] = $default_hash['default_tag_country_block'];
		}
		
		
		if (isset($this->request->post['russianpost2_tag_insurance_block'])) {
			$this->data['russianpost2_tag_insurance_block'] = $this->request->post['russianpost2_tag_insurance_block'];
		} elseif( $this->config->has('russianpost2_tag_insurance_block') ) {
			$this->data['russianpost2_tag_insurance_block'] = $this->config->get('russianpost2_tag_insurance_block');
		} else {
			$this->data['russianpost2_tag_insurance_block'] = $default_hash['default_tag_insurance_block'];
		}
		
		if (isset($this->request->post['russianpost2_tag_commission_block'])) {
			$this->data['russianpost2_tag_commission_block'] = $this->request->post['russianpost2_tag_commission_block'];
		} elseif( $this->config->has('russianpost2_tag_commission_block') ) {
			$this->data['russianpost2_tag_commission_block'] = $this->config->get('russianpost2_tag_commission_block');
		} else {
			$this->data['russianpost2_tag_commission_block'] = $default_hash['default_tag_commission_block'];
		}
		
		if (isset($this->request->post['russianpost2_tag_srok_block'])) {
			$this->data['russianpost2_tag_srok_block'] = $this->request->post['russianpost2_tag_srok_block'];
		} elseif( $this->config->has('russianpost2_tag_srok_block') ) {
			$this->data['russianpost2_tag_srok_block'] = $this->config->get('russianpost2_tag_srok_block');
		} else {
			$this->data['russianpost2_tag_srok_block'] = $default_hash['default_tag_srok_block'];
		}
		
		// ------------
		
		$this->data['russianpost2_product_filters'] = array();
		$russianpost2_product_filters = array();
		
		$russianpost2_product_filters_data = $this->model_shipping_russianpost2->getFilters('product');
		
		if( !empty($this->request->post['russianpost2_product_filters']) )
		{
			$russianpost2_product_filters = $this->request->post['russianpost2_product_filters'];
		}
		elseif( $russianpost2_product_filters_data )
		{
			$russianpost2_product_filters = $russianpost2_product_filters_data;
		}
		else
		{
			$this->data['russianpost2_product_filters'] = array();
		}
		
		if( $russianpost2_product_filters )
		{
			foreach($russianpost2_product_filters as $filter)
			{
				if( !empty( $filter['filter_category'] ) ) 
				{
					$filter_category = array();
					
					foreach( $filter['filter_category'] as $category_id )
					{
						$category_info = $this->model_catalog_category->getCategory($category_id);
						
						if( $category_info )
						{
							$filter_category[] = array(
								"category_id" => $category_id, 
								"name" => $category_info['name'], 
							);
						}
					}
					
					$filter['filter_category'] = $filter_category;
				}
				
				// ----
				
				if( !empty( $filter['filter_manufacturer'] ) ) 
				{
					$filter_manufacturer = array();
					
					foreach( $filter['filter_manufacturer'] as $manufacturer_id )
					{
						$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
						
						if( $manufacturer_info )
						{
							$filter_manufacturer[] = array(
								"manufacturer_id" => $manufacturer_id, 
								"name" => $manufacturer_info['name'], 
							);
						}
					}
					
					$filter['filter_manufacturer'] = $filter_manufacturer;
				}
				
				$this->data['russianpost2_product_filters'][] = $filter;
			}
			
		}
		
		// ------------
		
		$this->data['russianpost2_order_filters'] = array();
		$russianpost2_order_filters = array();
		
		$russianpost2_order_filters_data = $this->model_shipping_russianpost2->getFilters('order');
		
		if( !empty($this->request->post['russianpost2_order_filters']) )
		{
			$russianpost2_order_filters = $this->request->post['russianpost2_order_filters'];
		}
		elseif( $russianpost2_order_filters_data )
		{
			$russianpost2_order_filters = $russianpost2_order_filters_data;
		}
		else
		{
			$this->data['russianpost2_order_filters'] = array();
		}
		
		if( $russianpost2_order_filters )
		{
			foreach($russianpost2_order_filters as $filter)
			{				
				$this->data['russianpost2_order_filters'][] = $filter;
			}
			
		}
		
		// ------------
		
		$this->data['russianpost2_product_adds'] = array();
		$russianpost2_product_adds = array();
		
		$russianpost2_product_adds_data = $this->model_shipping_russianpost2->getAdds('product');
		
		if( !empty($this->request->post['russianpost2_product_adds']) )
		{
			$russianpost2_product_adds = $this->request->post['russianpost2_product_adds'];
		}
		elseif( $russianpost2_product_adds_data )
		{
			$russianpost2_product_adds = $russianpost2_product_adds_data;
		}
		else
		{
			$this->data['russianpost2_product_adds'] = array();
		}
		
		if( $russianpost2_product_adds )
		{
			foreach($russianpost2_product_adds as $filter)
			{				
				$this->data['russianpost2_product_adds'][] = $filter;
			}
			
		}
		
		// ------------
		
		$this->data['russianpost2_order_adds'] = array();
		$russianpost2_order_adds = array();
		
		$russianpost2_order_adds_data = $this->model_shipping_russianpost2->getAdds('order');
		
		if( !empty($this->request->post['russianpost2_order_adds']) )
		{
			$russianpost2_order_adds = $this->request->post['russianpost2_order_adds'];
		}
		elseif( $russianpost2_order_adds_data )
		{
			$russianpost2_order_adds = $russianpost2_order_adds_data;
		}
		else
		{
			$this->data['russianpost2_order_adds'] = array();
		}
		
		if( $russianpost2_order_adds )
		{
			foreach($russianpost2_order_adds as $filter)
			{				
				$this->data['russianpost2_order_adds'][] = $filter;
			}
			
		}
		
		// ------------
		
		$configs = $this->config->get('russianpost2_configs');
		$this->data['russianpost2_configs'] = array();
		
		$configs_list = $this->model_shipping_russianpost2->getEditableConfig();
		
		foreach($configs_list as $item)
		{
			if( $item['type'] == 'hidden' )
			{
				$item['value'] = $item['default_value'];
			}
			elseif( isset( $configs[ $item['config_key'] ] ) ) 
			{
				$item['value'] = $configs[ $item['config_key'] ];
			}
			elseif( !$this->config->has('russianpost2_configs') ) 
			{
				$item['value'] = $item['default_value'];
			}
			else
			{
				$item['value'] = '';
			}
			
			$this->data['russianpost2_configs'][] = $item;
		}
		
		
		// ---------
		
		$this->data['services_list'] = array();
		$services = $this->model_shipping_russianpost2->getDedicatedServices();
		
		$russianpost2_services2api_list = $this->config->get("russianpost2_services2api_list");
		
		foreach($services as $service)
		{
			#if( empty($service['service_name']) ) continue;
			
			$this->data['services_list'][] = $service;
			
			
			if( !empty($service['is_split']) )
			{
				
				$this->data['services_list'][] = array(
					"service_key" => 'split_'.$service['service_key'],
					"service_name" => $service['service_name'].$this->language->get('text_split'),
					"service_name_z" =>  $service['service_name'],
					"source" => isset( $russianpost2_services2api_list[$service['service_parent']]['source'] ) ? 
						$russianpost2_services2api_list[$service['service_parent']]['source'] : 'tariff'
				);
			} 
			
			$ar = explode("_", $service['source']);
			$sources = array();
			foreach($ar as $source_key)
			{
				if( $source_key == 'sfp' || $source_key == 'ems' ) continue;
				$sources[] = array("source_key" => $source_key, "name" => isset($this->source_hash[$source_key] ) ? $this->source_hash[$source_key] : '' );
			}
			
			$this->data['services2api_list'][ $service['service_key'] ] = array(
				"service_key" => $service['service_key'],
				"service_name" => !empty( $service['service_name'] ) ? $service['service_name'] : $service['service_key'],
				
				"service_name_z" => isset( $russianpost2_services2api_list[$service['service_key']]['service_name_z'] ) && is_array($russianpost2_services2api_list[$service['service_key']]) ? $russianpost2_services2api_list[$service['service_key']]['service_name_z'] : $service['service_name_z'],
				"postcode" => isset( $russianpost2_services2api_list[$service['service_key']]['postcode'] ) && is_array($russianpost2_services2api_list[$service['service_key']]) ? $russianpost2_services2api_list[$service['service_key']]['postcode'] : '',
				
				"sources" => $sources,
				"source" => isset( $russianpost2_services2api_list[$service['service_key']]['source'] ) ? $russianpost2_services2api_list[$service['service_key']]['source'] : ''
			);
		}
		$this->data['col_service_postcode'] = $this->language->get('col_service_postcode');
		
		
		/* start 1202 */
		$this->data['text_split_notice'] = $this->language->get('text_split_notice');
		/* end 1202 */
		
		
		$options = $this->config->get('russianpost2_options');

		$this->data['top_services_list'] = array();
		$top_services_list = $this->model_shipping_russianpost2->getServices( "top" );
		
		foreach($top_services_list as $service)
		{
			$service['options'] = $this->model_shipping_russianpost2->getServiceOptions( $service['service_key'] );
			
			foreach($service['options'] as $i=>$opt)
			{
				//echo $opt['service_key']." --- ".$opt['fieldname']."<hr>";
				
				if( $opt['option_cost'] )
				{
					$service['options'][$i]['option_cost'] *= 1.18;
					$service['options'][$i]['option_cost'] = round($service['options'][$i]['option_cost'], 2);
				}
				
				if( $options && 
					isset( $options[ $opt['service_key'] ][ $opt['fieldname'] ] ) 
				)
				{
					if( isset($options[ $opt['service_key'] ][ $opt['fieldname'] ]['status']) )
						$service['options'][$i]['value'] = $options[ $opt['service_key'] ][ $opt['fieldname'] ]['status'];
					else
						$service['options'][$i]['value'] = $opt['default_value'];
					
					if( isset($options[ $opt['service_key'] ][ $opt['fieldname'] ]['is_dedicated']) )
						$service['options'][$i]['is_dedicated'] = $options[ $opt['service_key'] ][ $opt['fieldname'] ]['is_dedicated'];
					else
						$service['options'][$i]['is_dedicated'] = 0;
					
				}
				/* start 112 */
				elseif( $options && $this->config->has('russianpost2_options') && 
						!isset( $options[ $opt['service_key'] ][ $opt['fieldname'] ] ) )
				{
					$service['options'][$i]['value'] = $opt['default_value'];
					$service['options'][$i]['is_dedicated'] = 1;
				}
				/* end 112 */
				elseif( !$this->config->has('russianpost2_options') )
				{
					$service['options'][$i]['value'] = $opt['default_value'];
					$service['options'][$i]['is_dedicated'] = 1;
				}
				else 
				{
					$service['options'][$i]['value'] = 0;
					$service['options'][$i]['is_dedicated'] = 0;
				}
				
				
				
				if( !empty($service['options'][$i]['option_cost']) && strstr($service['options'][$i]['option_cost'], "IF(") )
				{
					$service['options'][$i]['option_cost'] = '';
				}
				elseif( !empty($opt['option_cost']) )
				{
					$service['options'][$i]['option_cost'] .= ' '.$this->language->get('text_rub');
				}
				
				if( !empty(  $service['options'][$i]['values'] ) )
				{
					$service['options'][$i]['values'] = explode(",", $service['options'][$i]['values']);
					
					foreach($service['options'][$i]['values'] as $e=>$val)
					{
						$service['options'][$i]['values'][$e] = trim($val);
					}
					
				}
				
				
			}
			
			
			$this->data['top_services_list'][] = $service;
		}
		
		// ---------
		
		$this->data['default_width'] = $this->default_width;
		$this->data['default_height'] = $this->default_height;
		
		if( is_file(DIR_IMAGE . 'catalog/russianpost.gif') ) 
		{
			$this->data['default_image'] = 'catalog/russianpost.gif';
			$this->data['default_thumb'] = $this->model_tool_image->resize($this->data['default_image'], $this->default_width, $this->default_height);
		}
		else
		{
			$this->data['default_image'] = 'no_image.png';
			$this->data['default_thumb'] = $this->model_tool_image->resize($this->data['default_image'], $this->default_width, $this->default_height);
		}
		
		$this->data['russianpost2_methods'] = array();
		$russianpost2_methods = array();
		
		if( !empty($this->request->post['russianpost2_methods']) )
		{
			$russianpost2_methods = $this->request->post['russianpost2_methods'];
		}
		elseif( $this->config->get('russianpost2_methods') )
		{
			$russianpost2_methods = $this->config->get('russianpost2_methods');
		}
		else
		{
			$this->data['russianpost2_methods'] = array(
				"0" => array(
					"code" => "russianpost2",
					"name" 	   => $default_hash['default_title'][ $this->config->get('config_language_id') ],
					"title" 	   => $default_hash['default_title'],
					"image" 	   => $this->data['default_image'],
					"thumb" 	   => $this->data['default_thumb'],
					"status" 	   => 1,
					"sort_order"   => 1,
					"image_width"  => $this->default_width,
					"image_height" => $this->default_height,
					"submethods" => array(
						"0" => array(
							"code" => "russianpost2.rp1",
							"image" 	   => $this->data['default_image'],
							"thumb" 	   => $this->data['default_thumb'],
							"title" 	   => "",
							"status" 	   => 1,
							"sort_order"   => 1,
							"image_width"  => $this->default_width,
							"image_height" => $this->default_height,
							"services_sorttype" => 'minprice',
							"services" => array()
						)
					)
				)
			);
		}
		
		if( $russianpost2_methods )
		{
			foreach($russianpost2_methods as $method)
			{
				if( empty( $method['image'] ) )
				{
					$method['image'] = $this->default_image;
				}
				
				
				if(empty($method['image_width']) ||  (int)$method['image_width'] == 0 )
				{
					$method['image_width'] = $this->default_width;
				}
				
				if( empty($method['image_height']) || (int)$method['image_height'] == 0 )
				{
					$method['image_height'] = $this->default_height;
				}
				
				
				$method['thumb'] = $this->model_tool_image->resize($method['image'], $method['image_width'], $method['image_height'] );
				
				if( isset($method['title'][ $this->config->get('config_language_id') ]) )
					$method["name"] = $method['title'][ $this->config->get('config_language_id') ];
				else
					$method["name"] = '';
				
				// --------
						
				if( !empty($method['submethods']) )
				{
					$sort_order = array();
					foreach ($method['submethods']  as $i=>$submethod) 
					{
						$sort_order[$i] = !empty($submethod['sort_order']) ? $submethod['sort_order'] : '';
					}

					array_multisort($sort_order, SORT_ASC, $method['submethods']);
					
					foreach($method['submethods'] as $e=>$submethod)
					{
						/* start 0110 */
						if( !empty($submethod['adds_id']) )
						{
							$method['submethods'][$e]['adds'] = array($submethod['adds_id']); 
						}
						/* end 0110 */
						
						/* start 1705 */
						if( empty( $submethod['image'] ) )
						{
							$method['submethods'][$e]['image'] = $this->default_image;
						}
						
						if( empty($submethod['image_width']) || (int)$submethod['image_width'] == 0 )
						{
							$method['submethods'][$e]['image_width'] = $this->default_width;
						}
						
						if( empty($submethod['image_height']) || (int)$submethod['image_height'] == 0 )
						{
							$method['submethods'][$e]['image_height'] = $this->default_height;
						}
						
						$method['submethods'][$e]['thumb'] = $this->model_tool_image->resize($method['submethods'][$e]['image'], $method['submethods'][$e]['image_width'], $method['submethods'][$e]['image_height'] );
						/* end 1705 */
					}
					
					
				}
				// ---------
				
				
				$this->data['russianpost2_methods'][] = $method;
			}
		}
		
		$sort_order = array();
		foreach ($this->data['russianpost2_methods']  as $i=>$method) 
		{
			$sort_order[$i] = !empty($method['sort_order']) ? $method['sort_order'] : 0;
		}

		array_multisort($sort_order, SORT_ASC, $this->data['russianpost2_methods']);

		
		
		// ---------
		
		if( !empty($this->request->get['tab']) )
		{
			$this->data['tab'] = $this->request->get['tab'];
		}
		else
		{
			$this->data['tab'] = 'link-tab-general';
		}
		
		if( !empty($this->request->get['subtab']) )
		{
			$this->data['subtab'] = $this->request->get['subtab'];
		}
		else
		{
			$this->data['subtab'] = '';
		}
		
		if( !empty($this->request->get['subtab2']) )
		{
			$this->data['subtab2'] = $this->request->get['subtab2'];
		}
		else
		{
			$this->data['subtab2'] = '';
		}
		
		
		if( !empty( $this->session->data['success'] ) )
		{
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		else
		{
			$this->data['success'] = '';
		}
		
		$this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();
		
		if( !$this->model_shipping_russianpost2->getRubCode() )
		{
			$this->error['warning'] = $this->language->get('err_not_rub');
		}
		
		
		if( !$this->model_shipping_russianpost2->getGramm() )
		{
			$this->error['warning2'] = $this->language->get('err_not_gramm');
		}
		
		if( !$this->model_shipping_russianpost2->getCm() )
		{
			$this->error['warning3'] = $this->language->get('err_not_cm');
		}
		

		if (isset($this->error))  {
			$this->data['errors'] = $this->error;
		} else {
			$this->data['errors'] = array();
		}
		
		if( !empty($this->session->data['warning_list']) ) 
		{
			foreach($this->session->data['warning_list'] as $i=>$item)
			{
				$this->data['errors']['w'.$i] = $item;
			}
			
			unset($this->session->data['warning_list']);
		}
		
		
		// ------------
		
		$this->data['action'] = $this->url->link('shipping/russianpost2', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/russianpost', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		// -----------
		
		$this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();
		
		$this->data['currencies'] = array();
		
		$this->data['currencies']['RUB'] = array("code" => "RUB");
		
		foreach($results as $cur)
		{
			if(
				strtolower($cur['code']) != 'rub' && strtolower($cur['code']) != 'rur'
			)
			{
				$this->data['currencies'][ $cur['code'] ] = array("code" => $cur['code']);
			}
		}
		
		foreach($this->data['russianpost2_customs'] as $i=>$val )
		{
			if( !isset($this->data['russianpost2_customs'][ $i ]['currency']) )
				$this->data['russianpost2_customs'][ $i ]['currency'] = 'RUB';
		}
		
		
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/russianpost2.tpl', $this->data));
	}
	
	public function uploadPvz()
	{
		$this->initClass();
		$this->load->language('shipping/russianpost2');
		
		if( !$this->config->get('russianpost2_api_otpravka_token') )
		{
			$this->session->data['error'] = $this->language->get('error_pvz_upload_notoken');
			$this->response->redirect(
				str_replace("&amp;", "&", $this->url->link('shipping/russianpost2', 
				'token=' . $this->session->data['token'], 'SSL') )
			);
			return;
		}
	
		$status = $this->RP2->uploadPvz(1);
		
		if( !$status )
		{
			$this->session->data['error'] = $this->language->get('error_pvz_upload');
			$this->response->redirect(
				str_replace("&amp;", "&", $this->url->link('shipping/russianpost2', 
				'token=' . $this->session->data['token'], 'SSL') )
			);
			return;
		}
		
		$count = $this->RP2->getCountPvz();
		
		$this->session->data['success'] = $this->language->get('success_pvz_upload').' '.$count;
		
		$this->response->redirect(
			str_replace("&amp;", "&", $this->url->link('shipping/russianpost2', 
			'token=' . $this->session->data['token'], 'SSL') )
		);
	}
	
	public function uploadt()
	{
		$this->initClass();
	
		$this->load->language('shipping/russianpost2');
		/* start 1410 */
		$lstatus = $this->RP2->uploadData();
		
		if( !$lstatus ) 
		{
			$this->session->data['error_warning'] = $this->language->get('error_upload');
		}
		elseif( (int)$lstatus < 0 )
		{ 
			$this->session->data['error_warning'] = $this->language->get('text_statuserror_'.($lstatus * -1));
		}
		else
		{
			$this->RP2->updateOneSetting('russianpost2_last_update_date', date("Y-m-d"), 'russianpost2' ); 
			$this->session->data['success'] = $this->language->get('success_upload');
		}
		/* end 1410 */
		
		$this->response->redirect($this->url->link('shipping/russianpost2', 'token=' . $this->session->data['token']) );
	}
	
	public function install()
	{ 
	}
	
	public function uninstall()
	{
		$this->initClass();
		$this->RP2->dropDB();
	}
	

	protected function validate() {
			
		if (!$this->user->hasPermission('modify', 'shipping/russianpost2')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->request->post['russianpost2_version'] = $this->config->get('russianpost2_version');
		
		foreach($this->request->post as $key=>$val)
		{
			if( !is_array($val) )
			{
				$this->request->post[ $key ] = trim($val);
			}
			else
			{
				foreach($this->request->post[$key] as $key2=>$val2)
				{
					if( !is_array($val2) )
					{
						$this->request->post[ $key ][ $key2 ] = trim($val2);
					}
				}
			}
		}
		
		
		
		foreach( $this->request->post['russianpost2_delivery_types'] as $i=>$row )
		{
			if( !isset( $row['maxweight'] ) || $row['maxweight_mode'] == 'auto' )
				$this->request->post['russianpost2_delivery_types'][ $i ]['maxweight'] = $row['data_maxweight'];
			
			if( !isset( $row['maxlength'] ) || $row['maxlength_mode'] == 'auto' )
				$this->request->post['russianpost2_delivery_types'][ $i ]['maxlength'] = $row['data_maxlength'];
			
			if( !isset( $row['maxwidth'] ) || $row['maxlength_mode'] == 'auto' )
				$this->request->post['russianpost2_delivery_types'][ $i ]['maxwidth'] = $row['data_maxwidth'];
			
			if( !isset( $row['maxheight'] ) || $row['maxlength_mode'] == 'auto' )
				$this->request->post['russianpost2_delivery_types'][ $i ]['maxheight'] = $row['data_maxheight'];
			
			if( !isset( $row['maxsum'] ) || $row['maxsum_mode'] == 'auto' )
				$this->request->post['russianpost2_delivery_types'][ $i ]['maxsum'] = $row['data_maxsum'];
			
			
		}
		
		/*
		$options = $this->config->get('russianpost2_options');

		$notop_services_list = $this->model_shipping_russianpost2->getServices( "notop" );
		$notop_hash = array();
		
		foreach( $notop_services_list as $row )
		{
			$notop_hash[ $row['service_key'] ] = $row['service_parent'];
		}
		
		if( !empty($this->request->post['russianpost2_methods']) ) 
		{
			foreach( $this->request->post['russianpost2_methods'] as $key=>$method )
			{
				if( !empty( $method['submethods'] ) )
				{
					foreach( $method['submethods'] as $key2=>$submethod )
					{
						if( !empty( $submethod['services'] ) )
						{
							foreach( $submethod['services'] as $key3=>$service )
							{
								///////////////////////
								if( !empty($notop_hash[$service['service']]) )
								{
									if( 
										(
											strstr($service['service'], "registered") && 
											(
												empty( $this->request->post['russianpost2_options'][$notop_hash[$service['service']] ]['is_registered']['status'] )
												||
												empty( $this->request->post['russianpost2_options'][$notop_hash[$service['service']] ]['is_registered']['is_dedicated'] )
											)
										)
										|| 
										(
											strstr($service['service'], "avia") && 
											strstr($service['service'], "insured") && 
											(
												empty( $this->request->post['russianpost2_options'][$notop_hash[$service['service']] ]['is_avia_insured']['status'] )
												||
												empty( $this->request->post['russianpost2_options'][$notop_hash[$service['service']] ]['is_avia_insured']['is_dedicated'] )
											)
										)
										|| 
										(
											strstr($service['service'], "insured") && 
											(
												empty( $this->request->post['russianpost2_options'][$notop_hash[$service['service']] ]['is_insured']['status'] )
												||
												empty( $this->request->post['russianpost2_options'][$notop_hash[$service['service']] ]['is_insured']['is_dedicated'] )
											)
										)
										|| 
										(
											strstr($service['service'], "avia") && 
											(
												empty( $this->request->post['russianpost2_options'][$notop_hash[$service['service']] ]['is_avia']['status'] )
												||
												empty( $this->request->post['russianpost2_options'][$notop_hash[$service['service']] ]['is_avia']['is_dedicated'] )
											)
										)
									)
									{
										unset($this->request->post['russianpost2_methods'][$key]['submethods'][$key2]['services'][$key3]);
									}
									
								}
								///////////////////////
							}
						}
					}
				}
			}
		}
		*/
		
		#unset($this->request->post['russianpost2_options']);
		#print_r($this->request->post['russianpost2_options']);
		#exit();
		
		
		return !$this->error;
	}
	
	/* start metka-1 */
	public function clearCache()
	{
		$this->load->model('shipping/russianpost2');
		$this->load->language('shipping/russianpost2');
		
		$this->model_shipping_russianpost2->clearCache();
		
		$this->session->data['success'] = $this->language->get('text_clear_cache_success');
		
		$this->response->redirect($this->url->link('shipping/russianpost2', 'token=' . $this->session->data['token'], 'SSL') );
	}
	/* end metka-1 */

	
	
	/* start 0310 */
	public function getVersionStatus()
	{
		$this->load->language('shipping/russianpost2');
		
		$this->initClass();
		
		/* start 1410 */
		$lstatus = $this->RP2->checkUploadStatus();
		
		if( (int)$lstatus < 0 )
		{
			exit( $this->language->get('text_statuserror_'.($lstatus * -1)) );
		}
		elseif( !$lstatus )
		{		
			exit( $this->language->get('text_update_tarif_no') );
		}
		else
		{
			exit( $this->language->get('text_update_tarif_yes') );
		}
		/* end 1410 */
	}
	/* end 0310 */
}

?>