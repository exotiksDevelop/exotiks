<?php 
Class SocAuth extends DB
{
	public $group_name = 'code';
	
	public function setGroupName()
	{		
		$this->group_name = 'code';
	}
	
	
	public function getRecord($state)
	{
		$result = $this->query_row("
			SELECT * FROM `" . DB_PREFIX . "socnetauth2_records` 
			WHERE state='".$this->escape($state)."'");
		
		return $result;
	}
	
	public function setRecord($state, $redirect)
	{
		$this->query_run("DELETE FROM `" . DB_PREFIX . "socnetauth2_records` 
			WHERE DATE_ADD(cdate, INTERVAL 15 MINUTE)<NOW()");
			
		$this->query_run("INSERT INTO `" . DB_PREFIX . "socnetauth2_records` 
			SET 
				`state` = '".$this->escape($state)."',
				`redirect` = '".$this->escape($redirect)."',
				`cdate` = NOW()");
	}
	
	public function checkByEmail($data, $is_add=0)
	{
		$result = $this->query_row("SELECT * 
									FROM `" . DB_PREFIX . "customer` 
									WHERE email='".$this->escape($data['email'])."'");
		
		if( $result )
		{
			if( $is_add )
			{
				$this->query_row("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account`
								   SET 
								    `customer_id` = '".(int)$result['customer_id']."',
									`identity` = '".$this->escape($data['identity'])."',
									`link` = '".$this->escape($data['link'])."',
									`provider` = '".$this->escape($data['provider'])."',
									`data` = '".$this->escape($data['data'])."',
									`email` = '".$this->escape($data['email'])."'");
			}
			
			return $result['customer_id'];
		}
		else
		{
			return false;
		}
	}
	
		
	public function checkDB()
	{
		$res = $this->query_rows("SHOW TABLES");
		$installed = 0;
		foreach($res as $key=>$val)
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
				
			$this->query_run($sql);
			
			
			$query = $this->query_rows("SELECT * 
							   FROM `" . DB_PREFIX . "customer` 
							   WHERE socnetauth2_identity!=''");
			if( !empty($query) )				   
			{
				foreach($query as $customer)
				{
					$this->query_run("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account`
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
			$todel = $this->query_rows("SELECT sc.id, c.customer_id 
								  FROM `" . DB_PREFIX . "socnetauth2_customer2account` sc
								  LEFT JOIN `" . DB_PREFIX . "customer` c
								  ON sc.customer_id=c.customer_id
								  WHERE c.customer_id IS NULL");
			
			if( !empty($todel) )
			{
				foreach($todel as $item)
				{
					$this->query_run("DELETE FROM `" . DB_PREFIX . "socnetauth2_customer2account` 
								  WHERE id=".(int)$item['id'] );
				}
			}
			
		}
	}
	
	public function sendConfirmEmail($data)
	{
		$res = $this->query_rows("SHOW TABLES");
		$installed = 0;
		foreach($res as $key=>$val)
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
			$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "socnetauth2_precode` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`identity` varchar(300) NOT NULL,
				`code` varchar(300) NOT NULL,
				`cdate` DATETIME NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			$this->query_run($sql);
		}
		
		$code = md5( rand() );
		$this->query_run("INSERT INTO `" . DB_PREFIX . "socnetauth2_precode`
						  SET 
							`identity` = '".$this->escape($data['identity'])."',
							`code` = '".$this->escape($code)."',
							`cdate`=NOW()");
		
		$_SESSION['controlled_email'] = $data['email'];
		
		if( isset($_COOKIE['language']) && $_COOKIE['language']=='en' )
		{
			include(DIR_LANGUAGE.'english/account/socnetauth2.php');
		}
		else
		{
			include(DIR_LANGUAGE.'russian/account/socnetauth2.php');
		}
					
		
		$subject = $_['text_mail_subject'];
		$message = $_['text_mail_body'];
		
		$message = str_replace("%", $code, $message);
		
		$mail = new Mail();
		$mail->protocol = $this->get_config_param('config_mail_protocol', 'config');
		$mail->parameter = $this->get_config_param('config_mail_parameter', 'config');
		$mail->hostname = $this->get_config_param('config_smtp_host', 'config');
		$mail->username = $this->get_config_param('config_smtp_username', 'config');
		$mail->password = $this->get_config_param('config_smtp_password', 'config');
		$mail->port = $this->get_config_param('config_smtp_port', 'config');
		$mail->timeout = $this->get_config_param('config_smtp_timeout', 'config');		
		$mail->setTo($data['email']);
		$mail->setFrom( $this->get_config_param('config_email', 'config') );
		$mail->setSender( $this->get_config_param('config_name', 'config') );
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	}
	
	public function checkNew($data)
	{
		if( empty($data['identity']) ) exit('EMPTY ID');
		$identitis = array();
		
		$identitis[] = $data['identity'];
		$identitis[] = str_replace("http://", "https://", $data['identity']);
		$identitis[] = str_replace("https://", "http://", $data['identity']);
		$identitis[] = str_replace("https://", "https://www.", $data['identity']);
		$identitis[] = str_replace("http://", "http://www.", $data['identity']);
		$identitis[] = str_replace("http://www.", "http://", $data['identity']);
		$identitis[] = str_replace("https://www.", "https://", $data['identity']);
		$identitis[] = str_replace("https://www.", "", $data['identity']);
		$identitis[] = str_replace("https://", "", $data['identity']);
		$identitis[] = str_replace("http://www.", "", $data['identity']);
		$identitis[] = str_replace("http://", "", $data['identity']);
		$identitis[] = str_replace("https://www.", "http://", $data['identity']);
		
		
		
		for($i=0; $i<count($identitis); $i++)
		{
			$identitis[$i] = " sc.identity='".$this->escape($identitis[$i])."' ";
		}
		
		$wh = implode(" OR ", $identitis);
		
		/*
		`id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_id` varchar(100) NOT NULL,
				`identity` varchar(300) NOT NULL,
				`link` varchar(300) NOT NULL,
				`provider` varchar(300) NOT NULL,
				`data` TEXT NOT NULL,
		*/
		
		
		$check = $this->query_row("SELECT * FROM " . DB_PREFIX . "socnetauth2_customer2account sc
									JOIN " . DB_PREFIX . "customer c ON sc.customer_id=c.customer_id
								   WHERE ".$wh);
				
								   
		if( empty($check) && $this->get_config_param('socnetauth2_dobortype') == 'one' )
		{
			return false;
		}
		elseif( !empty( $check ) )
		{
		/*
			$upd = '';
			
			if( !empty($data['firstname']) )
			{
				$upd .= " firstname = '".$this->escape($data['firstname'])."', ";
			}
			
			if( !empty($data['lastname']) )
			{
				$upd .= " lastname = '".$this->escape($data['lastname'])."', ";
			}
			
			if( !empty($data['telephone']) )
			{
				$upd .= " telephone = '".$this->escape($data['telephone'])."', ";
			}
			
			if( !empty($data['email']) )
			{
				$upd .= " email = '".$this->escape($data['email'])."', ";
			}
		*/	
			$this->query_run("UPDATE `" . DB_PREFIX . "socnetauth2_customer2account` 
							  SET
								data = '".$this->escape($data['data'])."'
							  WHERE
							    identity = '" .$this->escape($data['identity']) . "'");
			
			$this->query_run("UPDATE `" . DB_PREFIX . "customer` 
							  SET
							  		ip = '" . $this->escape($_SERVER['REMOTE_ADDR']) . "'
							  WHERE
							    customer_id = '" .$this->escape($check['customer_id']) . "'");
							
			/* start specific block: system/library/customer.php */
			if( !empty($check['cart']) && is_string($check['cart']) ) {
				$cart = unserialize($check['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $_SESSION['cart'])) {
						$_SESSION['cart'][$key] = $value;
					} else {
						$_SESSION['cart'][$key] += $value;
					}
				}			
			}

			if ( !empty($check['wishlist']) && is_string($check['wishlist'])) {
				if (!isset($_SESSION['wishlist'])) {
					$_SESSION['wishlist'] = array();
				}
								
				$wishlist = unserialize($check['wishlist']);
			
				foreach ($wishlist as $product_id) {
					if (!in_array($product_id, $_SESSION['wishlist'])) {
						$_SESSION['wishlist'][] = $product_id;
					}
				}			
			}
			/* end specific block */

			
			return $check['customer_id'];
		}
		else
		{
			return false;
		}
	}
	
	
	public function get_config_param($param_name, $group='socnetauth2')
	{
		$row = $this->query_row("SELECT * FROM `" . DB_PREFIX . "setting` 
		WHERE `".$this->group_name."`='".$group."' AND `key`='".$param_name."'");
		
		return $row['value'];
	}
	
	public function setSessionData($key, $value)
	{
		if( isset($_SESSION['default']) )
		{
			$_SESSION['default'][$key] = $value;
		}
		else
		{
			$_SESSION[$key] = $value;
		}
	}
	
	public function getVersion()
	{
		$query = $this->query_rows("SELECT * FROM information_schema.COLUMNS
								   WHERE TABLE_NAME = '" . DB_PREFIX . "setting'");
		   
		$column_hash = array();
		
		foreach($query as $row )
		{
			if( $row['TABLE_SCHEMA'] == DB_PREFIX.DB_DATABASE || $row['TABLE_SCHEMA'] == DB_DATABASE )
			{
				$column_hash[ $row['COLUMN_NAME'] ] = 1;
				//echo $row['COLUMN_NAME']."<br>";
			}
		}
		
		
		if( isset($column_hash['group']) ) return 2000;
		elseif( isset($_SESSION['default']['language']) ) return 2100;
		else return 2010;
	}
	
	
	public function isNeedConfirm($data)
	{
		
		$confirm_data = array();
		
		if( $this->get_config_param('socnetauth2_confirm_firstname_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_firstname_status') == 1 && empty($data['firstname'])
			) )
		{
			$confirm_data['firstname'] = $data['firstname'];
		}  
		
		if( $this->get_config_param('socnetauth2_confirm_lastname_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_lastname_status') == 1 && empty($data['lastname'])
		) )
		{
			$confirm_data['lastname'] = $data['lastname'];
		}
		
		if( $this->get_config_param('socnetauth2_confirm_email_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_email_status') == 1 && empty($data['email'])
			) )
		{
			$confirm_data['email'] = $data['email'];
		}
		
		if( $this->get_config_param('socnetauth2_confirm_telephone_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_telephone_status') == 1 && empty($data['telephone'])
		) )
		{
			$confirm_data['telephone'] = $data['telephone'];
		}
		
		/* kin insert metka: c1 */
		if( $this->get_config_param('socnetauth2_confirm_company_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_company_status') == 1 && empty($data['company'])
		) )
		{
			$confirm_data['company'] = '';
		}
		
		if( $this->get_config_param('socnetauth2_confirm_address_1_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_address_1_status') == 1 && empty($data['address_1'])
		) )
		{
			$confirm_data['address_1'] = '';
		}
		
		if( $this->get_config_param('socnetauth2_confirm_postcode_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_postcode_status') == 1 && empty($data['postcode'])
		) )
		{
			$confirm_data['postcode'] = '';
		}
		
		if( $this->get_config_param('socnetauth2_confirm_city_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_city_status') == 1 && empty($data['city'])
		) )
		{
			$confirm_data['city'] = '';
		}
		
		if( $this->get_config_param('socnetauth2_confirm_zone_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_zone_status') == 1 && empty($data['zone'])
		) )
		{
			$confirm_data['zone'] = '';
		}
		
		if( $this->get_config_param('socnetauth2_confirm_country_status') == 2 || (
			$this->get_config_param('socnetauth2_confirm_country_status') == 1 && empty($data['country'])
		) )
		{
			$confirm_data['country'] = '';
		}
		/* end kin metka: c1 */
		
		if( !$confirm_data )
		{	
			return false;
		}
		else
		{		
			return $confirm_data;
		}
	}
	
	
	public function addCustomer($data)
	{
		$fields = array("firstname", "lastname", "email", "telephone", "company", "postcode",
		"country", "zone", "city", "address_1", "link" );
		
		foreach($fields as $field)
		{
			if( !isset($data[$field]) )
			{
				$data[$field] = '';
			}
		}
		
		$customer_group_id = $this->get_config_param('socnetauth2_'.$data['provider'].'_customer_group_id', 'socnetauth2');
		
		
		if( !$customer_group_id )
		$customer_group_id = $this->get_config_param('config_customer_group_id', 'config');
		
		$customer_id = '';
		if( empty($data['customer_id']) )
		{
			$this->query_run("INSERT INTO " . DB_PREFIX . "customer 
							  SET
								firstname = '".$this->escape($data['firstname'])."',
								lastname = '".$this->escape($data['lastname'])."',
								email = '".$this->escape($data['email'])."',
								telephone = '".$this->escape($data['telephone'])."',
								ip = '" . $this->escape($_SERVER['REMOTE_ADDR']) . "',
								approved = 1,
								customer_group_id = '" . (int)$customer_group_id . "', 
								status = '1', 
								date_added = NOW()");
								
			$customer_id = $this->get_last_insert_id();
			
			
			$this->query_run("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account` 
							  SET
								 identity = '" .$this->escape($data['identity']) . "',
								 provider = '".$this->escape($data['provider']) ."',
								 data = '".$this->escape($data['data'])."',
								 link = '".$this->escape($data['link'])."',
								 email = '".$this->escape($data['email'])."',
								 customer_id = '".(int)$customer_id."'");
			
		}
		else
		{
			$this->query_run("UPDATE " . DB_PREFIX . "customer 
							  SET
								firstname = '".$this->escape($data['firstname'])."',
								lastname = '".$this->escape($data['lastname'])."',
								email = '".$this->escape($data['email'])."',
								telephone = '".$this->escape($data['telephone'])."',
							    ip = '" . $this->escape($_SERVER['REMOTE_ADDR']) . "'
							  WHERE 
								customer_id='".(int)$data['customer_id']."'");
			$customer_id = $data['customer_id'];
		}
		
		if( $this->get_config_param('socnetauth2_save_to_addr')!='customer_only' )
		{
			if( empty($data['customer_id']) )
			{
			
				$this->query_run("INSERT INTO " . DB_PREFIX . "address 
				SET 
				customer_id = '" . (int)$customer_id . "', 
				firstname = '" . $this->escape($data['firstname']) . "', 
				lastname = '" . $this->escape($data['lastname']) . "', 
				company = '" . $this->escape($data['company']) . "', 
				address_1 = '" . $this->escape($data['address_1']) . "', 
				postcode = '" . $this->escape($data['postcode']) . "', 
				city = '" . $this->escape($data['city']) . "', 
				zone_id = '" . (int)$data['zone'] . "', 
				country_id = '" . (int)$data['country'] . "'");
		
				$address_id = $this->get_last_insert_id();
		
				$this->query_run("UPDATE " . DB_PREFIX . "customer 
						  SET address_id = '" . (int)$address_id . "' 
						  WHERE customer_id = '" . (int)$customer_id . "'");
			}
			else
			{
				$query = $this->query_row("SELECT * FROM " . DB_PREFIX . "customer 
										   WHERE customer_id='".(int)$data['customer_id']."'");
				
				if( !empty( $query->row['address_id'] ) )
				{
					$this->query_run("UPDATE " . DB_PREFIX . "address 
						SET  
							firstname = '" . $this->escape($data['firstname']) . "', 
							lastname = '" . $this->escape($data['lastname']) . "', 
							company = '" . $this->escape($data['company']) . "', 
							address_1 = '" . $this->escape($data['address_1']) . "', 
							postcode = '" . $this->escape($data['postcode']) . "', 
							city = '" . $this->escape($data['city']) . "', 
							zone_id = '" . (int)$data['zone'] . "', 
							country_id = '" . (int)$data['country'] . "'
						WHERE
							address_id = '" . (int)$query->row['address_id'] . "'");
				}
			}
		}
		
		return $customer_id;
	}
	
}

?>