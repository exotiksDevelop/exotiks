<?php
class Modelmodulesocnetauth2 extends Model 
{
	public $socnets = array(
		"vkontakte" => array(
			"key" => "vkontakte",
			"short" => "vk",
			"name" => "ВКонтакте"
		),
		"odnoklassniki" => array(
			"key" => "odnoklassniki",
			"short" => "od",
			"name" => "Одноклассники"
		),
		"facebook" => array(
			"key" => "facebook",
			"short" => "fb",
			"name" => "FaceBook"
		),
		"twitter" => array(
			"key" => "twitter",
			"short" => "tw",
			"name" => "Twitter"
		),
		/* start metka: a1 */
		"gmail" => array(
			"key" => "gmail",
			"short" => "gm",
			"name" => "Gmail.com"
		),
		"mailru" => array(
			"key" => "mailru",
			"short" => "mr",
			"name" => "Mail.ru"
		),
		/* end metka: a1 */
	);

	public function addFields() 
	{
	
		// ..................................................................
	
	
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "socnetauth2_records` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`state` varchar(100) NOT NULL,
				`redirect` varchar(300) NOT NULL,
				`cdate` datetime NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
				
		$this->db->query($sql);
		
		
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS
								   WHERE TABLE_NAME = '" . DB_PREFIX . "customer'");
								   
		$column_hash = array();
		
		// .........
		/*
		if( !isset( $column_hash['socnetauth2_facebook_id'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_facebook_id` VARCHAR( 100 ) NOT NULL";
			$this->db->query($sql);
		}
		
		if( !isset( $column_hash['socnetauth2_facebook_profile'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_facebook_profile` VARCHAR( 200 ) NOT NULL";
			$this->db->query($sql);
		}
		
		if( !isset( $column_hash['socnetauth2_vkontakte_id'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_vkontakte_id` VARCHAR( 100 ) NOT NULL";
			$this->db->query($sql);
		}
		
		if( !isset( $column_hash['socnetauth2_vkontakte_profile'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_vkontakte_profile` VARCHAR( 200 ) NOT NULL";
			$this->db->query($sql);
		}
		
		if( !isset( $column_hash['socnetauth2_twitter_id'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_twitter_id` VARCHAR( 100 ) NOT NULL";
			$this->db->query($sql);
		}
		
		if( !isset( $column_hash['socnetauth2_twitter_profile'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_twitter_profile` VARCHAR( 200 ) NOT NULL";
			$this->db->query($sql);
		}
		
		if( !isset( $column_hash['socnetauth2_odnoklassniki_id'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_odnoklassniki_id` VARCHAR( 100 ) NOT NULL";
			$this->db->query($sql);
		}
		
		if( !isset( $column_hash['socnetauth2_odnoklassniki_profile'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_odnoklassniki_profile` VARCHAR( 100 ) NOT NULL";
			$this->db->query($sql);
		}
		*/
		// .........
		
		foreach($query->rows as $row )
		{
			if( $row['TABLE_SCHEMA'] == DB_DATABASE || $row['TABLE_SCHEMA'] == DB_PREFIX.DB_DATABASE )
			{
				$column_hash[ $row['COLUMN_NAME'] ] = 1;
			}
		}
		
		if( !isset( $column_hash['socnetauth2_identity'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_identity` VARCHAR( 300 ) NOT NULL";
			$this->db->query($sql);
		}
		
		/* start metka a1 */
		if( !isset( $column_hash['socnetauth2_link'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_link` VARCHAR( 100 ) NOT NULL";
			$this->db->query($sql);
		}
		/* end metka a1 */
		
		if( !isset( $column_hash['socnetauth2_provider'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_provider` VARCHAR( 100 ) NOT NULL";
			$this->db->query($sql);
		}
		
		if( !isset( $column_hash['socnetauth2_data'] ) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `socnetauth2_data` TEXT NOT NULL";
			$this->db->query($sql);
		}
	}
	
	
	protected function cmp($a, $b)
	{
		if($a['sort'] == $b['sort']) {
			return 0;
		}
	
		return ($a['sort'] < $b['sort']) ? -1 : 1;
	}
	
	public function sortMethods($socnetauth2_methods)
	{
		$sortable_arr = array();
		
		foreach($socnetauth2_methods as $key=>$val)
		{
			$val['k'] = $key;
			$sortable_arr[] = $val;
		}
		
		usort($sortable_arr, array($this, "cmp"));
		
		$sorted_socnetauth2_methods = array();
		
		foreach($sortable_arr as $key=>$val)
		{
			$sorted_socnetauth2_methods[ $val['k'] ] = $val;
		}
		
		return $sorted_socnetauth2_methods;
	}
	
	public function makeCode($data)
	{
		if( !$data['socnetauth2_status'] )
		{
			$data['socnetauth2_simple_code'] = '';
			$data['socnetauth2_account_top_code'] = '';
			$data['socnetauth2_account_bottom_code'] = '';
			$data['socnetauth2_checkout_top_code'] = '';
			$data['socnetauth2_checkout_bottom_code'] = '';
			
			return $data;
		}
		
		$template_data = array();
		#$template_data['socnetauth2_default'] = $data['socnetauth2_default'];
		#$template_data['socnetauth2_format'] = $data['socnetauth2_format'];
		$template_data['socnetauth2_label'] = $data['socnetauth2_label'];
		$template_data['socnetauth2_showtype'] = $data['socnetauth2_showtype'];
		
		
		if( $data['socnetauth2_showtype']=='window' )
		{
			$template_data['classname'] = 'socnetauth';	
		}
		else
		{
			$template_data['classname'] = '';
		}
		
		$template_data['socnetauth2_shop_folder'] = $this->config->get('socnetauth2_shop_folder');
		$template->data['socnetauth2_socnets'] = array();
		
		foreach($this->socnets as $socnet)
		{
			if( !$data['socnetauth2_'.$socnet['key'].'_status'] ) continue;
			
			$template_data['socnetauth2_socnets'][] = $socnet;
		}
		
		if( $template_data['socnetauth2_shop_folder'] )
		$template_data['socnetauth2_shop_folder'] = '/'.$template_data['socnetauth2_shop_folder'];
		
		$data['socnetauth2_confirm_block'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_confirm.tpl', $template_data);
		
		$template_data['socnetauth2_format'] = 'kvadrat';
		
		$data['socnetauth2_reg_code_kvadrat'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_reg.tpl', $template_data);
		$data['socnetauth2_simplereg_code_kvadrat'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_simplereg.tpl', $template_data);
		$data['socnetauth2_simple_code_kvadrat']    = $this->load->view('module/socnetauth2_blocks/socnetauth2_simple.tpl', $template_data);
		$data['socnetauth2_account_code_kvadrat']   = $this->load->view('module/socnetauth2_blocks/socnetauth2_account.tpl', $template_data);
		$data['socnetauth2_checkout_code_kvadrat']  = $this->load->view('module/socnetauth2_blocks/socnetauth2_checkout.tpl', $template_data);
		
		$template_data['socnetauth2_format'] = 'bline';
		
		$data['socnetauth2_reg_code_bline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_reg.tpl', $template_data);
		$data['socnetauth2_simplereg_code_bline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_simplereg.tpl', $template_data);
		$data['socnetauth2_simple_code_bline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_simple.tpl', $template_data);
		$data['socnetauth2_account_code_bline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_account.tpl', $template_data);
		$data['socnetauth2_checkout_code_bline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_checkout.tpl', $template_data);
		
		$template_data['socnetauth2_format'] = 'lline';
		
		$data['socnetauth2_reg_code_lline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_reg.tpl', $template_data);
		$data['socnetauth2_simplereg_code_lline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_simplereg.tpl', $template_data);
		$data['socnetauth2_simple_code_lline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_simple.tpl', $template_data);
		$data['socnetauth2_account_code_lline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_account.tpl', $template_data);
		$data['socnetauth2_checkout_code_lline'] = $this->load->view('module/socnetauth2_blocks/socnetauth2_checkout.tpl', $template_data);
		
		
		return $data;
	}
	
	public function updateSetting($group='', $key, $value)
	{
		$check = $this->db->query("SELECT * FROM ".DB_PREFIX."setting
								   WHERE
									`group`='".$group."' AND `key`='".$key."'");
		
		if( empty($check->rows) )
		{
			$this->db->query("INSERT INTO ".DB_PREFIX."setting
								   SET 
									value = '".$this->db->escape($value)."',
									`group`='".$group."', 
									`key`='".$key."'");
		}
		else
		{
			$this->db->query("UPDATE ".DB_PREFIX."setting
								   SET 
									value = '".$this->db->escape($value)."'
								   WHERE
									`group`='".$group."' AND `key`='".$key."'");
		}
	}
	
	public function showData()
	{
		$tab = 'customer';
		$customer_id = 0;
		
		if( !empty($this->request->get['customer_id']) )
		{
			$customer_id = $this->request->get['customer_id'];
			
			if( !$this->config->get('socnetauth2_admin_customer') ) return;			
		}
		elseif( !empty($this->request->get['order_id']) )
		{
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
			
			if( !empty($order_info['customer_id']) )
			{
				$customer_id = $order_info['customer_id'];
				
				if( $this->request->get['route']=='sale/order/info' )
				$tab = 'order';
			}
			
			if( empty($customer_id) ) return;
			if( !$this->config->get('socnetauth2_admin_order') ) return;
		}
		elseif( $this->request->get['route']=='sale/customer' )
		{
			$tab = 'customer_list';
			$customer_id = '';
			if( !$this->config->get('socnetauth2_admin_customer_list') ) return;
		}
		elseif( $this->request->get['route']=='sale/order' )
		{
			$tab = 'order_list';
			$customer_id = '';
			if( !$this->config->get('socnetauth2_admin_order_list') ) return;
		}
				
		
		$hash_values = array(
			"identity" => "Идентификатор:",
			"provider" => "Провайдер:",
			"uid" => "uid:",
			"nickname" => "Логин:",
			"email" => "E-mail:",
			"country" => 'Страна:',
			"postal_code" => 'Почтовый индекс:',
			"state" => 'Область/регион:',
			"city" => 'Город:',
			"street_address" => 'Адрес:',
			"gender" => "Пол:",
			"photo" => "Фото:",
			"name" => "Имя:",
			"full_name" => "ФИО:",
			"first_name" => "Имя:",
			"last_name" => "Фамилия:",
			"middle_name" => "Отчество:",
			"dob" => "Дата рождения:",
			"work" => "Работа:",
			"company" => "Название компании:",
			"job" => "Профессия или должность:",
			"home" => "Домашний адрес:",
			"business" => "Рабочий адрес:",
			"phone" => "Телефон:",
			"preferred" => "Номер телефона указанный по умолчанию:",
			"home" => "Домашний телефон:",
			"work" => "Рабочий телефон:",
			"mobile" => "Мобильный телефон:",
			"fax" => "Факс:",
			"im" => "Массив с аккаунтами для связи:",
			"icq" => "Номер ICQ аккаунта:",
			"jabber" => "Jabber аккаунт:",
			"skype" => "Skype аккаунт:",
			"web" => "Массив содержащий адреса сайтов пользователя:",
			"default" => "Адрес профиля или персональной страницы:",
			"blog" => "Адрес блога:",
			"language" => "Язык:",
			"timezone" => "Временная зона:",
			"biography" => "Другая информация о пользователе и его интересах:"
		);
		
		$this->checkDB();
		
		
		
		if( $customer_id )
		{ 
			$data = '';
			$customer = $this->model_sale_customer->getCustomer($customer_id);
			
			$customer['socnetauth2_data'] = $this->getDataByCustomer($customer_id);
			
			
			if( !empty($customer['socnetauth2_data']) )
			{
				$data = '<script>';
				
				$socnetauth2_data = '';
				$i = 0;
				foreach($customer['socnetauth2_data'] as $row)
				{
					$i++;
					$row['data'] = html_entity_decode($row['data'], ENT_QUOTES, 'UTF-8');
					
					$data_arr = unserialize( $row['data'] );
					#$data_arr['provider'] = $row['provider'];
					$data_arr['link'] = $row['link'];
					$socnetauth2_data .= '<b>'.strtoupper($row['provider'])."</b><br>";
			
					foreach($data_arr as $key=>$val)
					{
						if( !is_array($val) )
						{
							if( $key=='photo' && $val )
							{
								$val = '<img src="'.$val.'">';
							}
							elseif( preg_match("/^http/", $val) )
							{
								$val = '<a href="'.$val.'" target=_blank>'.$val.'</a>';
							}
						
							if($val=='')
							{
								$val = '(пусто)';
							}
						
							if( !empty($hash_values[$key]) )
							$key = $hash_values[$key];
						
							$socnetauth2_data .= '<b>'.$key."</b> ".$val."<br>";
						}
						else
						{
							foreach($val as $k=>$v)
							{
								if( !is_array($v) )
								{
									if( $v=='' )
									{
										$v = '(пусто)';
									}
									elseif( preg_match("/^http/", $v) )
									{
										$v = '<a href="'.$v.'" target=_blank>'.$v.'</a>';
									}
						
									if( !empty($hash_values[$k]) )
									$k = $hash_values[$k];
								
									$socnetauth2_data .= '<b>'.$k."</b> ".$v."<br>";
								}
								else
								{
									foreach($v as $k2=>$v2)
									{
										if( $v2=='' )
										{
											$v2 = '(пусто)';
										}
										elseif( preg_match("/^http/", $v2) )
										{
											$v2 = '<a href="'.$v2.'" target=_blank>'.$v2.'</a>';
										}
								
										if( !empty($hash_values[$k2]) )
										$k2 = $hash_values[$k2];
									
										$socnetauth2_data .= '<b>'.$k2."</b> ".$v2."<br>";
									}
								}
							}
						}
						
					}
					
					if( count($customer['socnetauth2_data'])>1 && $i!=count($customer['socnetauth2_data']) )  
					$socnetauth2_data .= "--------<br>";
						
				}
				
			
				$text = "<tbody><tr><td>socnetauth:</td><td>".$socnetauth2_data."</td></tr></tbody>";
				
				$data .= "$('#tab-".$tab." .form tbody').after('".$text."');";
			
				
				
				$data .= "var ID = '';
					$('.list').find('tr').each(function(e) {
					
					
					if( e==0 )
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 2)
							{
								$(this).after('<td>Провайдер</td>');
							}
						});
					}
	
					if( e==1 )
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 2)
							{
								$(this).after('<td></td>');
							}
						});
					}
	
					if(e>1)
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 0 )
							{
								ID = $(this).find('input').attr('value');
								//alert( $(this).find('input').attr('value') );
							}
		
							if( i == 2)
							{
								//var cur = $(this).text();
				
								//$(this).after('<td>1212</td>');
				
								//$(this).text( cur + ID );
							}
						});
					}
				});
				</script>   ";
				
			}
			
			
			return $data;
		}
		elseif( $tab == 'customer_list' )
		{
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = null;
			}
		
			if (isset($this->request->get['filter_customer_group_id'])) {
				$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
			} else {
				$filter_customer_group_id = null;
			}

			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {
				$filter_status = null;
			}
		
			if (isset($this->request->get['filter_approved'])) {
				$filter_approved = $this->request->get['filter_approved'];
			} else {
				$filter_approved = null;
			}
			
			if (isset($this->request->get['filter_ip'])) {
				$filter_ip = $this->request->get['filter_ip'];
			} else {
				$filter_ip = null;
			}
				
			if (isset($this->request->get['filter_date_added'])) {
				$filter_date_added = $this->request->get['filter_date_added'];
			} else {
				$filter_date_added = null;
			}		
		
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'name'; 
			}
		
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}
		
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
		
			$data_filter = array(
				'filter_name'              => $filter_name, 
				'filter_email'             => $filter_email, 
				'filter_customer_group_id' => $filter_customer_group_id, 
				'filter_status'            => $filter_status, 
				'filter_approved'          => $filter_approved, 
				'filter_date_added'        => $filter_date_added,
				'filter_ip'                => $filter_ip,
				'sort'                     => $sort,
				'order'                    => $order,
				'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                    => $this->config->get('config_admin_limit')
			);
		
			$customer_total = $this->model_sale_customer->getTotalCustomers($data_filter);
	
			$results = $this->model_sale_customer->getCustomers($data_filter);
			
		
			$data = '<script>';
			
			$data .= "var ID = '';
					$('.list').find('tr').each(function(e) {
					
					var items_hash = new Array();";
			
			foreach($results as $res)
			{
				$socnetdata = $this->getDataByCustomer($res['customer_id']);
				
				if( !empty($socnetdata) )
				{
					$value = array();
					foreach($socnetdata as $row)
					{
						if( empty($row['link']) )
						{
							$value[] = $this->socnets[ $row['provider'] ]['name'];
						}
						else
						{
							$value[] = "<a href=\"".$row['link']."\" target=_blank>".$this->socnets[ $row['provider'] ]['name']."</a>";
						}
					}
					
					$data .= " items_hash[".$res['customer_id']."] = '".implode(" ", $value)."';";
				}
				else
				{
					$data .= " items_hash[".$res['customer_id']."] = '';";
				}
			}
					
			/* start metka: a1 */
			$data .= "
					
					if( e==0 )
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 2)
							{
								$(this).after('<td>Провайдер</td>');
							}
						});
					}
	
					if( e==1 )
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 2)
							{
								$(this).after('<td></td>');
							}
						});
					}
	
					if(e>1)
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 0 )
							{
								ID = $(this).find('input').attr('value');
								//alert( $(this).find('input').attr('value') );
							}
		
							if( i == 2)
							{
								$(this).after('<td>'+items_hash[ID]+'</td>');
							}
						});
					}
				});
				</script>   ";
			/* end metka: a1 */
				
			return $data;
		}
		elseif( $tab == 'order_list' )
		{
			if (isset($this->request->get['filter_order_id'])) {
				$filter_order_id = $this->request->get['filter_order_id'];
			} else {
				$filter_order_id = null;
			}

			if (isset($this->request->get['filter_customer'])) {
				$filter_customer = $this->request->get['filter_customer'];
			} else {
				$filter_customer = null;
			}

			if (isset($this->request->get['filter_order_status_id'])) {
				$filter_order_status_id = $this->request->get['filter_order_status_id'];
			} else {
				$filter_order_status_id = null;
			}
		
			if (isset($this->request->get['filter_total'])) {
				$filter_total = $this->request->get['filter_total'];
			} else {
				$filter_total = null;
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$filter_date_added = $this->request->get['filter_date_added'];
			} else {
				$filter_date_added = null;
			}
		
			if (isset($this->request->get['filter_date_modified'])) {
				$filter_date_modified = $this->request->get['filter_date_modified'];
			} else {
				$filter_date_modified = null;
			}

			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'o.order_id';
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
			
			
			$data_filter = array(
				'filter_order_id'        => $filter_order_id,
				'filter_customer'	     => $filter_customer,
				'filter_order_status_id' => $filter_order_status_id,
				'filter_total'           => $filter_total,
				'filter_date_added'      => $filter_date_added,
				'filter_date_modified'   => $filter_date_modified,
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                  => $this->config->get('config_admin_limit')
			);

			$this->load->model('sale/order');

			$results = $this->model_sale_order->getOrders($data_filter);
			
			$data = '<script>';
			
			$data .= "var ID = '';
					$('.list').find('tr').each(function(e) {
					
					var items_hash = new Array();";
			
			foreach($results as $res)
			{
				$order = $this->model_sale_order->getOrder( $res['order_id'] );
				
				if( !empty($order['customer_id']) )
				{
					$socnetdata = $this->getDataByCustomer($order['customer_id']);
				
					if( !empty($socnetdata) )
					{
						$value = array();
						foreach($socnetdata as $row)
						{				
							if( empty($row['link']) )
							{
								$value[] = $this->socnets[ $row['provider'] ]['name'];
							}
							else
							{
								$value[] = "<a href=\"".$row['link']."\" target=_blank>".$this->socnets[ $row['provider'] ]['name']."</a>";
							}
						}
					
						$data .= " items_hash[".$res['order_id']."] = '".implode(" ", $value)."';";
					}
					else
					{
						$data .= " items_hash[".$res['order_id']."] = '';";
					}
				
				
				
				/*
					$cust = $this->model_sale_customer->getCustomer( $order['customer_id'] );
					
					if( !empty( $cust['socnetauth2_provider'] ) )
					{
						if( empty( $cust['socnetauth2_link'] ) )
						$data .= " items_hash[".$res['order_id']."] = '".$this->socnets[ $cust['socnetauth2_provider'] ]['name']."';";
						else
						$data .= " items_hash[".$res['order_id']."] = '<a href=\"".$cust['socnetauth2_link']."\" target=_blank>".$this->socnets[ $cust['socnetauth2_provider'] ]['name']."</a>';";
					}
					else
					{
						$data .= " items_hash[".$res['order_id']."] = '';";
					}
				*/
				}
				else
				{
					$data .= " items_hash[".$res['order_id']."] = '';";
				}
			}
			
			/* start metka: a1 */		
			$data .= "
					
					if( e==0 )
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 2)
							{
								$(this).after('<td>Провайдер</td>');
							}
						});
					}
	
					if( e==1 )
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 2)
							{
								$(this).after('<td></td>');
							}
						});
					}
	
					if(e>1)
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 0 )
							{
								ID = $(this).find('input').attr('value');
								//alert( $(this).find('input').attr('value') );
							}
		
							if( i == 2)
							{
								$(this).after('<td>'+items_hash[ID]+'</td>');
							}
						});
					}
				});
				</script>   ";
			/* end metka: a1 */
				
			return $data;
		}
	}
	
	
	
	public function showData2()
	{
		$tab = 'customer';
		$customer_id = 0;
		
		if( !empty($this->request->get['customer_id']) )
		{
			$customer_id = $this->request->get['customer_id'];
			
			if( !$this->config->get('socnetauth2_admin_customer') ) return;			
		}
		elseif( !empty($this->request->get['order_id']) )
		{
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
			
			if( !empty($order_info['customer_id']) )
			{
				$customer_id = $order_info['customer_id'];
				
				if( $this->request->get['route']=='sale/order/info' )
				$tab = 'order';
			}
			
			if( empty($customer_id) ) return;
			if( !$this->config->get('socnetauth2_admin_order') ) return;
		}
		elseif( $this->request->get['route']=='sale/customer' )
		{
			$tab = 'customer_list';
			$customer_id = '';
			if( !$this->config->get('socnetauth2_admin_customer_list') ) return;
		}
		elseif( $this->request->get['route']=='sale/order' )
		{
			$tab = 'order_list';
			$customer_id = '';
			if( !$this->config->get('socnetauth2_admin_order_list') ) return;
		}
				
		
		$hash_values = array(
			"identity" => "Идентификатор:",
			"provider" => "Провайдер:",
			"uid" => "uid:",
			"nickname" => "Логин:",
			"email" => "E-mail:",
			"country" => 'Страна:',
			"postal_code" => 'Почтовый индекс:',
			"state" => 'Область/регион:',
			"city" => 'Город:',
			"street_address" => 'Адрес:',
			"gender" => "Пол:",
			"photo" => "Фото:",
			"name" => "Имя:",
			"full_name" => "ФИО:",
			"first_name" => "Имя:",
			"last_name" => "Фамилия:",
			"middle_name" => "Отчество:",
			"dob" => "Дата рождения:",
			"work" => "Работа:",
			"company" => "Название компании:",
			"job" => "Профессия или должность:",
			"home" => "Домашний адрес:",
			"business" => "Рабочий адрес:",
			"phone" => "Телефон:",
			"preferred" => "Номер телефона указанный по умолчанию:",
			"home" => "Домашний телефон:",
			"work" => "Рабочий телефон:",
			"mobile" => "Мобильный телефон:",
			"fax" => "Факс:",
			"im" => "Массив с аккаунтами для связи:",
			"icq" => "Номер ICQ аккаунта:",
			"jabber" => "Jabber аккаунт:",
			"skype" => "Skype аккаунт:",
			"web" => "Массив содержащий адреса сайтов пользователя:",
			"default" => "Адрес профиля или персональной страницы:",
			"blog" => "Адрес блога:",
			"language" => "Язык:",
			"timezone" => "Временная зона:",
			"biography" => "Другая информация о пользователе и его интересах:"
		);
		
		$this->checkDB();
		
		if( $customer_id )
		{ 
			$data = '';
			$customer = $this->model_sale_customer->getCustomer($customer_id);
			
			if( !empty($customer['socnetauth2_data']) )
			{
				$data = '<script>';
			
				$data_arr = unserialize( $customer['socnetauth2_data'] );
				$socnetauth2_data = '';
				
				$data_arr['provider'] = $customer['socnetauth2_provider'];
			
				foreach($data_arr as $key=>$val)
				{
					if( !is_array($val) )
					{
						if( $key=='photo' && $val )
						{
							$val = '<img src="'.$val.'">';
						}
						elseif( preg_match("/^http/", $val) )
						{
							$val = '<a href="'.$val.'" target=_blank>'.$val.'</a>';
						}
						
						if($val=='')
						{
							$val = '(пусто)';
						}
						
						if( !empty($hash_values[$key]) )
						$key = $hash_values[$key];
						
						$socnetauth2_data .= '<b>'.$key."</b> ".$val."<br>";
					}
					else
					{
						foreach($val as $k=>$v)
						{
							if( !is_array($v) )
							{
								if( $v=='' )
								{
									$v = '(пусто)';
								}
								elseif( preg_match("/^http/", $v) )
								{
									$v = '<a href="'.$v.'" target=_blank>'.$v.'</a>';
								}
						
								if( !empty($hash_values[$k]) )
								$k = $hash_values[$k];
								
								$socnetauth2_data .= '<b>'.$k."</b> ".$v."<br>";
							}
							else
							{
								foreach($v as $k2=>$v2)
								{
									if( $v2=='' )
									{
										$v2 = '(пусто)';
									}
									elseif( preg_match("/^http/", $v2) )
									{
										$v2 = '<a href="'.$v2.'" target=_blank>'.$v2.'</a>';
									}
								
									if( !empty($hash_values[$k2]) )
									$k2 = $hash_values[$k2];
									
									$socnetauth2_data .= '<b>'.$k2."</b> ".$v2."<br>";
								}
							}
						}
					}
				}
			
				$text = '<div class="form-group"><label class="col-sm-2 control-label" for="input-safe">Socnetauth:</label><div class="col-sm-10">'.$socnetauth2_data.'</div></div>';
				
				$data .= "$('#tab-customer').html( $('#tab-customer').html() + '".$text."');";
			
				
				$data .= "var ID = '';
					$('.list').find('tr').each(function(e) {
					
					
					if( e==0 )
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 2)
							{
								$(this).after('<td>Провайдер</td>');
							}
						});
					}
	
	
					if(e>0)
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 0 )
							{
								ID = $(this).find('input').attr('value');
								//alert( $(this).find('input').attr('value') );
							}
		
							if( i == 2)
							{
								//var cur = $(this).text();
				
								//$(this).after('<td>1212</td>');
				
								//$(this).text( cur + ID );
							}
						});
					}
				});
				</script>   ";
				
			}
			
			
			return $data;
		}
		elseif( $tab == 'customer_list' )
		{
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = null;
			}
		
			if (isset($this->request->get['filter_customer_group_id'])) {
				$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
			} else {
				$filter_customer_group_id = null;
			}

			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {
				$filter_status = null;
			}
		
			if (isset($this->request->get['filter_approved'])) {
				$filter_approved = $this->request->get['filter_approved'];
			} else {
				$filter_approved = null;
			}
			
			if (isset($this->request->get['filter_ip'])) {
				$filter_ip = $this->request->get['filter_ip'];
			} else {
				$filter_ip = null;
			}
				
			if (isset($this->request->get['filter_date_added'])) {
				$filter_date_added = $this->request->get['filter_date_added'];
			} else {
				$filter_date_added = null;
			}		
		
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'name'; 
			}
		
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}
		
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
		
			$data_filter = array(
				'filter_name'              => $filter_name, 
				'filter_email'             => $filter_email, 
				'filter_customer_group_id' => $filter_customer_group_id, 
				'filter_status'            => $filter_status, 
				'filter_approved'          => $filter_approved, 
				'filter_date_added'        => $filter_date_added,
				'filter_ip'                => $filter_ip,
				'sort'                     => $sort,
				'order'                    => $order,
				'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                    => $this->config->get('config_admin_limit')
			);
		
			$customer_total = $this->model_sale_customer->getTotalCustomers($data_filter);
	
			$results = $this->model_sale_customer->getCustomers($data_filter);
			
		
			$data = '<script>';
			
			$data .= "var ID = '';
					$('.table-responsive').find('tr').each(function(e) {
					
					var items_hash = new Array();";
			
			foreach($results as $res)
			{

				if( !empty($res['socnetauth2_provider']) )
				{
					if( empty($res['socnetauth2_link']) )
					{
						$data .= " items_hash[".$res['customer_id']."] = '".$this->socnets[ $res['socnetauth2_provider'] ]['name']."';";
					}
					else
					{
						$data .= " items_hash[".$res['customer_id']."] = '<a href=\"".$res['socnetauth2_link']."\" target=_blank>".$this->socnets[ $res['socnetauth2_provider'] ]['name']."</a>';";
					}					
				}
				else
					$data .= " items_hash[".$res['customer_id']."] = '';";
			}
					
			/* start metka: a1 */
			$data .= "
					
					if( e==0 )
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 2)
							{
								$(this).after('<td>Провайдер</td>');
							}
						});
					}
	
					if(e>0)
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 0 )
							{
								ID = $(this).find('input').attr('value');
								//alert( $(this).find('input').attr('value') );
							}
		
							if( i == 2)
							{
								$(this).after('<td>'+items_hash[ID]+'</td>');
							}
						});
					}
				});
				</script>   ";
			/* end metka: a1 */
				
			return $data;
		}
		elseif( $tab == 'order_list' )
		{
			if (isset($this->request->get['filter_order_id'])) {
				$filter_order_id = $this->request->get['filter_order_id'];
			} else {
				$filter_order_id = null;
			}

			if (isset($this->request->get['filter_customer'])) {
				$filter_customer = $this->request->get['filter_customer'];
			} else {
				$filter_customer = null;
			}

			if (isset($this->request->get['filter_order_status_id'])) {
				$filter_order_status_id = $this->request->get['filter_order_status_id'];
			} else {
				$filter_order_status_id = null;
			}
		
			if (isset($this->request->get['filter_total'])) {
				$filter_total = $this->request->get['filter_total'];
			} else {
				$filter_total = null;
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$filter_date_added = $this->request->get['filter_date_added'];
			} else {
				$filter_date_added = null;
			}
		
			if (isset($this->request->get['filter_date_modified'])) {
				$filter_date_modified = $this->request->get['filter_date_modified'];
			} else {
				$filter_date_modified = null;
			}

			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'o.order_id';
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
			
			
			$data_filter = array(
				'filter_order_id'        => $filter_order_id,
				'filter_customer'	     => $filter_customer,
				'filter_order_status_id' => $filter_order_status_id,
				'filter_total'           => $filter_total,
				'filter_date_added'      => $filter_date_added,
				'filter_date_modified'   => $filter_date_modified,
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                  => $this->config->get('config_admin_limit')
			);

			$this->load->model('sale/order');

			$results = $this->model_sale_order->getOrders($data_filter);
			
			$data = '<script>';
			
			$data .= "var ID = '';
					$('.table-responsive').find('tr').each(function(e) {
					
					var items_hash = new Array();";
			
			foreach($results as $res)
			{
				$order = $this->model_sale_order->getOrder( $res['order_id'] );
				
				if( !empty($order['customer_id']) )
				{
					$cust = $this->model_sale_customer->getCustomer( $order['customer_id'] );
					
					if( !empty( $cust['socnetauth2_provider'] ) )
					{
						if( empty( $cust['socnetauth2_link'] ) )
						$data .= " items_hash[".$res['order_id']."] = '".$this->socnets[ $cust['socnetauth2_provider'] ]['name']."';";
						else
						$data .= " items_hash[".$res['order_id']."] = '<a href=\"".$cust['socnetauth2_link']."\" target=_blank>".$this->socnets[ $cust['socnetauth2_provider'] ]['name']."</a>';";
					}
					else
					{
						$data .= " items_hash[".$res['order_id']."] = '';";
					}
				}
				else
				{
					$data .= " items_hash[".$res['order_id']."] = '';";
				}
			}
			
			/* start metka: a1 */		
			$data .= "
					
					if( e==0 )
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 2)
							{
								$(this).after('<td>Провайдер</td>');
							}
						});
					}
		
					if(e>0)
					{
						$(this).find('td').each(function(i) 
						{
							if( i == 0 )
							{
								ID = $(this).find('input').attr('value');
								//alert( $(this).find('input').attr('value') );
							}
		
							if( i == 2)
							{
								$(this).after('<td>'+items_hash[ID]+'</td>');
							}
						});
					}
				});
				</script>   ";
			/* end metka: a1 */
				
			return $data;
		}
	}
	
	
	public function checkDB()
	{
		$res = $this->db->query("SHOW TABLES");
		$installed = 0;
		foreach($res->rows as $key=>$val)
		{
			foreach($val as $k=>$v)
			{
				if( $v == DB_PREFIX . 'socnetauth2_customer2account' )
				{
					$installed = 1;
				}
			}
		}
		
		if( $installed == 0 )
		{
			$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "socnetauth2_customer2account` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_id` varchar(100) NOT NULL,
				`identity` varchar(300) NOT NULL,
				`link` varchar(300) NOT NULL,
				`provider` varchar(300) NOT NULL,
				`email` varchar(300) NOT NULL,
				`data` TEXT NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
				
			$this->db->query($sql);
			
			
			$query = $this->db->query("SELECT * 
							   FROM `" . DB_PREFIX . "customer` 
							   WHERE socnetauth2_identity!=''");
			if( !empty($query->rows) )				   
			{
				foreach($query->rows as $customer)
				{
					$this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account`
									SET 
									`customer_id` = '".(int)$customer['customer_id']."',
									`identity` = '".$this->escape($customer['socnetauth2_identity'])."',
									`link` = '".$this->escape($customer['socnetauth2_link'])."',
									`provider` = '".$this->escape($customer['socnetauth2_provider'])."',
									`data` = '".$this->escape($customer['socnetauth2_data'])."',
									`email` = '".$this->escape($customer['email'])."'");
				}
			}
		}
		else
		{
			$todel = $this->db->query("SELECT sc.id, c.customer_id 
								  FROM `" . DB_PREFIX . "socnetauth2_customer2account` sc
								  LEFT JOIN `" . DB_PREFIX . "customer` c
								  ON sc.customer_id=c.customer_id
								  WHERE c.customer_id IS NULL");
			
			if( !empty($todel->rows) )
			{
				foreach($todel->rows as $item)
				{
					$this->db->query("DELETE FROM `" . DB_PREFIX . "socnetauth2_customer2account` 
								  WHERE id=".(int)$item['id'] );
				}
			}
			
		}
	}
	
	public function getDataByCustomer($customer_id)
	{
		$query = $this->db->query("SELECT * 
								  FROM `" . DB_PREFIX . "socnetauth2_customer2account`
								  WHERE customer_id='".(int)$customer_id."'");
		
		if( !empty($query->rows) ) return $query->rows;
		else return false;
	}
	
	
}
?>