<?php
class ClassRussianpost2 extends ClassLicense {
	
	/* start 1410 */
	private $tarif_url = "https://softpodkluch.ru/API/RP2/DATA4.php";
	private $version_url = "https://softpodkluch.ru/API/RP2/data4_version.xml";
	private $config;
	private $db;
	/* end 1410 */
	
	private $nds;
	
	private $tables = array(
		"russianpost2_city2city",
		"russianpost2_config",
		"russianpost2_countries",
		"russianpost2_delivery_types",
		"russianpost2_formuls",
		"russianpost2_services",
		"russianpost2_options",
		"russianpost2_regions",
		"russianpost2_regions2regions",
		"russianpost2_remote"
		/* start 112 */ ,
		"russianpost2_packs",
		"russianpost2_cities" , 
		"russianpost2_indexes"
	);
	
	
	public function __construct($registry) {
		/* start 1410 */
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		parent::__construct($this->db, $this->config, $registry->get('url'), 'russianpost2');
		/* end 1410 */
	}
	
	public function getPriceWithNDS($price)
	{
		
	}
	
	public function getCountPvz()
	{
		$sql = "SELECT COUNT(*) as cn 
				FROM `" . DB_PREFIX . "russianpost2_pvz` ";
		$query = $this->db->query($sql);
			
		
		return $query->row ? $query->row['cn'] : 0;
	}
	
	public function uploadPvz($is_force = 0)
	{
		$this->checkPvzDB(); 
		
		if( !$is_force && !$this->isNeedUploadPvz() ) 
		{
			return;
		}
		
		$request_headers = array(
			"Content-Type: application/json",
			"Accept: application/json;charset=UTF-8",
			"Authorization: AccessToken " . $this->config->get('russianpost2_api_otpravka_token'),
			//	"X-User-Authorization: Basic " . $russianpost2_api_otpravka_key
		);
		
		$url = 'https://otpravka-api.pochta.ru/1.0/delivery-point/findAll';
		$result = $this->getCurl(
			$url, 
			'GET', 
			'', 
			$request_headers, 
			$this->config->get('russianpost2_otpravka_pvz_curl_lifetime')
		);
		
		if( !$result )
			return false;
		
		$json = json_decode($result, 1);
		
		if( !empty($json[0]) )
		{
			$limit = 100;
			$sql_list = array();
			
			$i = 0;
			$e = 0;
			
			foreach($json as $item)
			{
				$i++;
				if( $i == $limit )
				{
					$i = 0;
					$e++;
					$sql_list[$e] = array();
				}
				
				// ---------
				
				
				$item['pre'] = '';
				$item['city'] = '';
				
				if( !empty($item['address']['place']) )
				{
					$ar = explode(" ", $item['address']['place']);
					
					if( !empty($ar[1]) )
					{
						$item['pre'] = trim($ar[0]);
						$item['pre'] = preg_replace("/\.$/", "", $item['pre']);
						
						unset($ar[0]);
						$item['city'] = implode(" ", $ar); 
					}
				}
				
				$city_row = $this->getCityByOtpravkaPvz($item); 
				
				//echo $item['city']." --- ".$city_row['id']."<br>";
				
				$row = array(
				
					'id' 					=> isset($item['id']) ? $item['id'] : '',
					'region_id' 			=> isset($city_row['region_id']) ? $city_row['region_id'] : '',
					'city_id' 				=> isset($city_row['id']) ? $city_row['id'] : '',
					'pre' 					=> isset($item['pre']) ? $item['pre'] : '',
					'city' 					=> isset($item['city']) ? $item['city'] : '',
					'address_addressType' 	=> isset($item['address']['addressType']) ? $item['address']['addressType'] : '',
					'address_index' 		=> isset($item['address']['index']) ? $item['address']['index'] : '',
					'address_region'		=> isset($item['address']['region']) ? $item['address']['region'] : '',
					'address_place' 		=> isset($item['address']['place']) ? $item['address']['place'] : '',
					'address_street' 		=> isset($item['address']['street']) ? $item['address']['street'] : '',
					'address_house' 		=> isset($item['address']['house']) ? $item['address']['house'] : '',
					'address_manualInput' 	=> isset($item['address']['manualInput']) ? $item['address']['manualInput'] : '',
					'brand_id' 				=> isset($item['brand-id']) ? $item['brand-id'] : '',
					'brand_name'			=> isset($item['brand-name']) ? $item['brand-name'] : '',
					'closed' 				=> isset($item['closed']) ? $item['closed'] : '',
					'delivery_point_index' 	=> isset($item['delivery-point-index']) ? $item['delivery-point-index'] : '',
					'delivery_point_type' 	=> isset($item['delivery-point-type']) ? $item['delivery-point-type'] : '',
					'latitude' 				=> isset($item['latitude']) ? $item['latitude'] : '',
					'longitude' 			=> isset($item['longitude']) ? $item['longitude'] : '',
					'legal_name'			=> isset($item['legal-name']) ? $item['legal-name'] : '',
					'legal_short_name' 		=> isset($item['legal-short-name']) ? $item['legal-short-name'] : '', 
					'temporary_closed' 		=> isset($item['temporary-closed']) ? $item['temporary-closed'] : '',
					'work_time' 			=> isset($item['work-time']) ? serialize( $item['work-time'] ) : '',
					
					'card_payment' 			=> isset($item['card-payment']) ? $item['card-payment'] : '',
					'cash_payment' 			=> isset($item['cash-payment']) ? $item['cash-payment'] : '',
					'contents_checking' 	=> isset($item['contents-checking']) ? $item['contents-checking'] : '',
					'dimension_limit' 		=> isset($item['dimension-limit']) ? $item['dimension-limit'] : '',
					'functionality_checking' => isset($item['functionality-checking']) ? $item['functionality-checking'] : '',
					'getto' 				=> isset($item['getto']) ? $item['getto'] : '',
					'partial_redemption' 	=> isset($item['partial-redemption']) ? $item['partial-redemption'] : '',
					'weight_limit' 			=> isset($item['weight-limit']) ? $item['weight-limit'] : '',
					'with_fitting' 			=> isset($item['with-fitting']) ? $item['with-fitting'] : '', 
				);
				
				// --------
				
				$fields = array();
				
				foreach($row as $key=>$val)
				{
					$fields[] = " '".$this->db->escape($val)."' ";
				}
				
				$sql_list[$e][] = "( ".implode(",", $fields)." )";
			}
			
			
			
			if( $sql_list )
			{
				$keys = array(
					'`id`',
					'`region_id`',
					'`city_id`',
					'`pre`',
					'`city`',
					'`address_addressType`',
					'`address_index`',
					'`address_region`',
					'`address_place`',
					'`address_street`',
					'`address_house`',
					'`address_manualInput`',
					'`brand_id`',
					'`brand_name`',
					'`closed`',
					'`delivery_point_index`',
					'`delivery_point_type`',
					'`latitude`',
					'`longitude`',
					'`legal_name`',
					'`legal_short_name`',
					'`temporary_closed`',
					'`work_time`',
					'`card_payment`',
					'`cash_payment`',
					'`contents_checking`',
					'`dimension_limit`',
					'`functionality_checking`',
					'`getto`',
					'`partial_redemption`',
					'`weight_limit`',
					'`with_fitting`'
				);
				
				$this->db->query("DELETE FROM `" . DB_PREFIX . "russianpost2_pvz`");
			
				
				$sql_start = 'INSERT INTO `' . DB_PREFIX . 'russianpost2_pvz` ('.implode(", ", $keys).') VALUES ';
				
				foreach($sql_list as $rows)
				{
					$sql = $sql_start.' '.implode(", ", $rows).'; ';
					
					$this->db->query($sql);
				}
				
				$this->updateOneSetting('russianpost2_optravka_pvz_last_upload_date', date("Y-m-d") );
				return true;
			}
		}
		
	}
	
	private function getCityByOtpravkaPvz($pvz)
	{
		$region_id = '';
		
		$region = '';
		if( !empty($pvz['address']['region']) )
		{
			$ar = explode(" ", $pvz['address']['region']);
			
			if( !empty($ar[1]) )
			{
				$pre = trim($ar[0]);
				
				if( $pre != 'г.' )
					unset($ar[0]);
				
				$region = implode(" ", $ar); 
				
				if( strstr($region, "Башк") )
					$region = 'Башкортостан';
				elseif( strstr($region, "Бурят") )
					$region = 'Бурятия';
				elseif( strstr($region, "Кабард") )
					$region = 'Кабардино-Балкарская';
				elseif( strstr($region, "Кабард") )
					$region = 'Кабардино-Балкарская';
				elseif( strstr($region, "Калмык") )
					$region = 'Калмыкия'; 
				elseif( strstr($region, "Карачаев") )
					$region = 'Карачаево-Черкесская'; 
				elseif( strstr($region, "Марий") )
					$region = 'Марий Эл';  
				elseif( strstr($region, "Якут") )
					$region = 'Якутия';   
				elseif( strstr($region, "Осет") )
					$region = 'Северная Осетия-Алания';   
				elseif( strstr($region, "Алания") )
					$region = 'Северная Осетия-Алания';   
				elseif( strstr($region, "Татар") )
					$region = 'Татарстан';    
				elseif( strstr($region, "Тува") )
					$region = 'Тыва';    
				elseif( strstr($region, "Тыва") )
					$region = 'Тыва';  
				elseif( strstr($region, "Хант") )
					$region = 'Ханты-Мансийский-Югра'; 
				elseif( strstr($region, "Чечен") )
					$region = 'Чеченская';  
				elseif( strstr($region, "Чечн") )
					$region = 'Чеченская';  
				elseif( strstr($region, "Чуваш") )
					$region = 'Чувашская';   
				elseif( strstr($region, "Чукот") )
					$region = 'Чукотский';   
				elseif( strstr($region, "Байконур") )
					$region = 'Байконур';    
				elseif( strstr($region, "Адыг") )
					$region = 'Адыгея';    	
				elseif( !strstr($region, "Алтайский") && strstr($region, "Алтай") )
					$region = 'Алтай';    
				
			}
			
			$sql = "SELECT * 
				FROM `" . DB_PREFIX . "russianpost2_regions` 
				WHERE ems_name LIKE '".$this->db->escape($region)."'
					OR 
					ems_name LIKE '".$this->db->escape($region)." %'
				";
			$query = $this->db->query($sql);
			
			if( $query->row )
			{
				$region = " AND region_id = ".(int)$query->row['id'];
			}
		}
		
		
		if( !empty($pvz['address']['place']) )
		{
			$city = $pvz['address']['place'];
			$pre = '';
			
			$ar = explode(" ", $pvz['address']['place']);
			
			if( !empty($ar[1]) )
			{
				$pre = trim($ar[0]);
				$pre = preg_replace("/\.$/", "", $pre);
				$pre = " AND `pre` = '".$this->db->escape($pre)."' ";
				
				unset($ar[0]);
				$city = implode(" ", $ar);
			}	
			
			$sql = "SELECT * 
				FROM `" . DB_PREFIX . "russianpost2_cities` 
				WHERE city = '".$this->db->escape($city)."' ".$pre;
				
			
			$query = $this->db->query($sql);
			
			if( $query->row )
				return $query->row;
			
			if( $pvz['address']['region'] == 'г. Москва')
			{
				$city = 'Москва';
				$pre = " AND `pre` = 'г' ";
			}
			elseif( $pvz['address']['region'] == 'г. Санкт-Петербург')
			{
				$city = 'Санкт-Петербург';
				$pre = " AND `pre` = 'г' ";
			}
			elseif( $pvz['address']['region'] == 'г. Севастополь')
			{
				$city = 'Севастополь';
				$pre = " AND `pre` = 'г' ";
			}
			
			$sql = "SELECT * 
				FROM `" . DB_PREFIX . "russianpost2_cities` 
				WHERE city = '".$this->db->escape($city)."' ".$pre;
			 
			$query = $this->db->query($sql);
			
			return $query->row;
		}
	}
	
	public function isNeedUploadPvz()
	{
		if( !$this->config->get('russianpost2_optravka_pvz_last_upload_date') )
			return true;
		
		if( $this->config->get('russianpost2_optravka_pvz_mode') == 'each_day' && 
			$this->config->get('russianpost2_optravka_pvz_last_upload_date') != date("Y-m-d")
		)
		{
			return true;
		}
		elseif(
			$this->config->get('russianpost2_optravka_pvz_mode') == 'each_week' && 
			$this->config->get('russianpost2_optravka_pvz_last_upload_date') != date("Y-m-d")
		)
		{
			$diff = abs(strtotime(date("Y-m-d")) - strtotime($this->config->get('russianpost2_optravka_pvz_last_upload_date') ) );
			
			$days = ceil($diff / (60*60*24));
			
			if( $days > 7 )
			{
				return true;
			}
		}
		elseif(
			$this->config->get('russianpost2_optravka_pvz_mode') == 'each_month' && 
			$this->config->get('russianpost2_optravka_pvz_last_upload_date') != date("Y-m-d")
		)
		{
			$diff = abs(strtotime(date("Y-m-d")) - strtotime($this->config->get('russianpost2_optravka_pvz_last_upload_date') ) );
			
			$days = ceil($diff / (60*60*24));
			
			if( $days > 30 )
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function getCurl($url, $method='GET', $destination='', $request_headers='', $timeout = 1)
	{ 
		$c = curl_init( $url  );
			
		if( $method == 'POST' )                                       
		curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");     
		
		if( $destination )                              
		curl_setopt($c, CURLOPT_POSTFIELDS, $destination );    
		
		if( $request_headers )                              
		curl_setopt($c, CURLOPT_HTTPHEADER, $request_headers);   
		
		if( strstr($url, 'https://') )
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
			
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, $timeout); 
		curl_setopt($c, CURLOPT_TIMEOUT, $timeout); //timeout in seconds
		$res = curl_exec($c);
		
		$inf = curl_getinfo($c);
		
		if( !$res )
		{
			return false;
		}
		
		curl_close($c);
		
		return $res;
	}
	
	public function checkPvzDB()
	{
		##$this->checkColumn("order", "russianpost2_delivery_point_index", "VARCHAR(100)");
		
		if( !$this->isTableExists( 'russianpost2_pvz' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS 
				`" . DB_PREFIX . "russianpost2_pvz` (
				`id` int(11) NOT NULL,
				`region_id` int(11) NOT NULL,
				`city_id` int(11) NOT NULL,
				
				`pre` varchar(100) NOT NULL,
				`city` varchar(300) NOT NULL,
			  
				`address_addressType` varchar(300) NOT NULL,
				`address_house` varchar(100) NOT NULL,
				`address_index` varchar(100) NOT NULL,
				`address_manualInput` varchar(100) NOT NULL,
				`address_place` varchar(100) NOT NULL,
				`address_region` varchar(100) NOT NULL,
				`address_street` varchar(100) NOT NULL,
			  
				`brand_id` varchar(100) NOT NULL,
				`brand_name` varchar(100) NOT NULL,
				`closed` varchar(100) NOT NULL,
				`delivery_point_index` varchar(100) NOT NULL,
				`delivery_point_type` varchar(100) NOT NULL,
				`latitude` varchar(100) NOT NULL,
				`longitude` varchar(100) NOT NULL,
				`legal_name` varchar(100) NOT NULL,
				`legal_short_name` varchar(300) NOT NULL,
				`temporary_closed` varchar(300) NOT NULL, 
				`work_time` TEXT NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		}
		$this->checkColumn("russianpost2_pvz", "card_payment", "INT(1)");
		$this->checkColumn("russianpost2_pvz", "cash_payment", "INT(1)");
		$this->checkColumn("russianpost2_pvz", "contents_checking", "INT(1)");
		$this->checkColumn("russianpost2_pvz", "dimension_limit", "VARCHAR(100)");
		$this->checkColumn("russianpost2_pvz", "functionality_checking", "INT(1)");
		$this->checkColumn("russianpost2_pvz", "getto", "VARCHAR(300)");
		$this->checkColumn("russianpost2_pvz", "partial_redemption", "VARCHAR(300)");
		$this->checkColumn("russianpost2_pvz", "weight_limit", "INT(11)");
		$this->checkColumn("russianpost2_pvz", "with_fitting", "INT(1)"); 
	}
	/* start 20092 */
	public function getCurrentCountries()
	{
		$query = $this->db->query("SELECT *
		FROM `" . DB_PREFIX . "country` c 
		WHERE c.`iso_code_2` != 'RU' AND c.status=1 ORDER BY c.`name` ASC");
		
		$result = array();
		$result2 = array();
		
		foreach($query->rows as $row)
		{
			$result[$row['country_id']] = array(
				"iso_code_2" 	=> $row['iso_code_2'],
				"name" 		=> $row['name'],
				"country_id" 	=> $row['country_id']
			);
			
			$result2[$row['iso_code_2']] = array(
				"iso_code_2" 	=> $row['iso_code_2'],
				"name" 		=> $row['name'],
				"country_id" 	=> $row['country_id']
			);
		}
		
		return array($result, $result2);
	}
	
	public function getCountriesGeo2EmsHash()
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "russianpost2_countries
		 ORDER BY `country_name` ASC");
		
		$hash = array();
		$hash2 = array();
		
		foreach( $query->rows as $row ) {
			$hash[ $row['iso_code'] ] = $row;
			$hash2[ $row['id'] ] = $row;
		}
		
		return array($hash, $hash2);
	}
	
	/* end 20092 */
	
	public function uploadDataURL($is_version_only = 0)
	{
		/* start 112 */
		$this->checkDB();
		/* end 112 */
		$page = '';
		
		if( $is_version_only ) 
			$page = $this->uploadURL( $this->version_url );
		else
			$page = $this->uploadURL( $this->tarif_url );
		
		
		if( !empty($page) )
		{
			if( $page == 'ERROR-1' )
			{
				return -1;
			}
			
			if( $page == 'ERROR-2' )
			{
				return -2;
			}
			
			if( $page == 'ERROR-3' )
			{
				return -3;
			}
			
			if( $page == 'ERROR-4' )
			{
				return -4;
			}
		}
		
		if( empty($page) || !strstr($page, 'starttag') ) return false;
		/* end 1410 */
		
		return $page;
	}
	
	public function getVersionCode($db)
	{
			$query = $db->query("SELECT * FROM information_schema.COLUMNS
									   WHERE TABLE_NAME = '" . DB_PREFIX . "setting'");
			   
			$column_hash = array();
			
			foreach($query->rows as $row )
			{
				if( $row['TABLE_SCHEMA'] == DB_PREFIX.DB_DATABASE || $row['TABLE_SCHEMA'] == DB_DATABASE )
				{
					$column_hash[ $row['COLUMN_NAME'] ] = 1;
					//echo $row['COLUMN_NAME']."<br>";
				}
			}
			
			
			if( isset($column_hash['group']) ) return 'group';
			else return 'code';
	}

	public function updateOneSetting($key, $value, $group='russianpost2') 
	{ 
		$groupField = $this->getVersionCode($this->db);
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE 
				`".$groupField."` = '" . $this->db->escape($group) . "' AND 
				`key` = '" . $this->db->escape($key) . "'");
				
		if( count($query->rows)>0 ) 
		{
			$this->db->query("UPDATE " . DB_PREFIX . "setting 
			SET 
				`value` = '" . $this->db->escape($value) . "'
			WHERE 
				`".$groupField."` = '" . $this->db->escape($group) . "' AND 
				`key` = '" . $this->db->escape($key) . "'
			");
		}
		else
		{
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting 
			SET 
				`value` = '" . $this->db->escape($value) . "',
				`".$groupField."` = '" . $this->db->escape($group) . "',
				`key` = '" . $this->db->escape($key) . "'
			");
		}
	}
	
	function checkUploadStatus()
	{
		#if( !$this->config->has('russianpost2_version')  )
		#return false;
	
		$page = $this->uploadDataURL();
		
		/* start 1410 */
		if( empty($page) || (int)$page < 0 ) 
			return $page;
		
		/* end 1410 */
		
		
		$version = $this->getSubBlock('version', $page);
		if( empty($version) ) return false;
		
		if( $this->config->get('russianpost2_version')==$version )
		return true;
		
		return false;
	}
	
	private function getSubBlock($tag, $page)
	{
		$block = explode("<".$tag.">", $page);
		$block = explode("</".$tag.">", $block[1]);
		$block = $block[0];
		
		return $block;
	}
	
	public function checkDB()
	{
		/* start 0611 */
		if( !$this->isTableExists( 'russianpost2_customs' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_customs` (
			  `custom_id` INT(11) NOT NULL auto_increment,
			  `name` varchar(300) NOT NULL,
			  `price` FLOAT NOT NULL, 
			  `currency` varchar(3) NOT NULL,
			  `type` varchar(300) NOT NULL,
			  `status` INT(11) NOT NULL,
			  PRIMARY KEY  (`custom_id`)	
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
		}
		/* end 0611 */
		
		
		if( !$this->isTableExists( 'russianpost2_cache' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_cache` (
			  `id` INT(11) NOT NULL auto_increment,
			  `md5_key` varchar(300) NOT NULL,
			  `source` varchar(300) NOT NULL,
			  `key_data` TEXT NOT NULL,
			  `response_data` TEXT NOT NULL,
			  `cdate` DATETIME NOT NULL,
			  PRIMARY KEY  (`id`)	
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
		}
		
		if( !$this->isTableExists( 'russianpost2_current_methods' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_current_methods` (
			  `code` varchar(100) NOT NULL,
			  `filters` varchar(300) NOT NULL,
			  `data` TEXT NOT NULL,
			  `sort_order` int(11) NOT NULL,
			  PRIMARY KEY  (`code`)	
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
		}
		
		if( !$this->isTableExists( 'russianpost2_filters' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_filters` (
			  `filter_id` int(11) NOT NULL auto_increment,
			  `productfilter` int(11) NOT NULL,
			  `filtername` varchar(300) NOT NULL,
			  `type` varchar(100) NOT NULL,
			  `data` TEXT NOT NULL,
			  `sort_order` int(11) NOT NULL,
			  PRIMARY KEY  (`filter_id`)	
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
		}
		
		if( !$this->isTableExists( 'russianpost2_adds' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_adds` (
			  `adds_id` int(11) NOT NULL AUTO_INCREMENT,
			  `filters` varchar(300) NOT NULL,
			  `type` varchar(100) NOT NULL,
			  `data` TEXT NOT NULL,
			  `sort_order` int(11) NOT NULL,
			  PRIMARY KEY  (`adds_id`)	
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
		}
		
		// --------------
		
		if( !$this->isTableExists( 'russianpost2_city2city' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_city2city` (
			  `id` int(11) NOT NULL auto_increment,
			  `from_city` varchar(300) NOT NULL,
			  `from_id` int(11) NOT NULL,
			  `to_city` varchar(300) NOT NULL,
			  `to_id` int(11) NOT NULL,
			  `from_code` varchar(300) NOT NULL,
			  `to_code` varchar(300) NOT NULL,
			  `data` text NOT NULL,
			  PRIMARY KEY  (`id`)	
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
		}

		if( !$this->isTableExists( 'russianpost2_config' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_config` (
			  `id` int(11) NOT NULL auto_increment,
			  `type` varchar(300) NOT NULL,
			  `name` varchar(300) NOT NULL,
			  `config_key` varchar(300) NOT NULL,
			  `value` text NOT NULL,
			  `default_value` text NOT NULL,
			  `values` text NOT NULL,
			  `is_editable` tinyint(1) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
		}

		if( !$this->isTableExists( 'russianpost2_countries' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_countries` (
			  `id` int(11) NOT NULL auto_increment,
			  `iso_code` varchar(2) NOT NULL,
			  `country_name` varchar(300) NOT NULL,
			  `data` text NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
		}

		if( !$this->isTableExists( 'russianpost2_delivery_types' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_delivery_types` (
			  `id` int(11) NOT NULL auto_increment,
			  `doclink` varchar(300) NOT NULL,
			  `type_key` varchar(300) NOT NULL,
			  `type_name` varchar(300) NOT NULL,
			  `type_name_z` varchar(300) NOT NULL,
			  `content` varchar(300) NOT NULL,
			  `data` text NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
		}

		if( !$this->isTableExists( 'russianpost2_formuls' ) )
		{

			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_formuls` (
			  `id` int(11) NOT NULL auto_increment,
			  `formula_key` varchar(300) NOT NULL,
			  `formula_group` varchar(300) NOT NULL,
			  `param_key` varchar(300) NOT NULL,
			  `value` text NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
		}

		if( !$this->isTableExists( 'russianpost2_services' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_services` (
			  `id` int(11) NOT NULL auto_increment,
			  `doclink` varchar(300) NOT NULL,
			  `source` varchar(300) NOT NULL,
			  `postcalc` varchar(300) NOT NULL,
			  `postcalc_key` varchar(300) NOT NULL,
			  `service_name` varchar(300) NOT NULL,
			  `service_name_z` varchar(300) NOT NULL,
			  `service_key` varchar(300) NOT NULL,
			  `service_parent` varchar(300) NOT NULL,
			  `formula_key` varchar(300) NOT NULL,
			  `formula_min_srok` varchar(300) NOT NULL,
			  `formula_max_srok` varchar(300) NOT NULL,
			  `declared_koef` varchar(300) NOT NULL,
			  `declared_koef_nds` varchar(300) NOT NULL,
			  `limitcost` varchar(300) NOT NULL,
			  `type_key` varchar(300) NOT NULL,
			  `insured_type` varchar(300) NOT NULL,
			  `service` varchar(300) NOT NULL,
			  `transport_type` varchar(300) NOT NULL,
			  `delivery_type` varchar(300) NOT NULL,
			  `sender_type` varchar(300) NOT NULL,
			  `area_type` varchar(300) NOT NULL,
			  `data` text NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
		}

		if( !$this->isTableExists( 'russianpost2_options' ) )
		{

			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_options` (
			  `id` int(11) NOT NULL auto_increment,
			  `service_name` varchar(300) NOT NULL,
			  `service_key` varchar(300) NOT NULL,
			  `dedicated_key` varchar(300) NOT NULL,
			  `option_name` varchar(300) NOT NULL,
			  `option_cost` varchar(300) NOT NULL,
			  `fieldname` varchar(300) NOT NULL,
			  `fieldtype` varchar(300) NOT NULL,
			  `values` varchar(300) NOT NULL,
			  `default_value` varchar(300) NOT NULL,
			  `condition` varchar(300) NOT NULL,
			  `comment` varchar(300) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
		}

		if( !$this->isTableExists( 'russianpost2_regions' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_regions` (
			  `id` int(11) NOT NULL auto_increment,
			  `ems_code` varchar(300) NOT NULL,
			  `ems_ems` varchar(300) NOT NULL,
			  `id_oc` int(11) NOT NULL,
			  `korens` varchar(300) NOT NULL,
			  `ems_name` varchar(300) NOT NULL,
			  `capital` varchar(300) NOT NULL,
			  `data` text NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
		}

		if( !$this->isTableExists( 'russianpost2_regions2regions' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_regions2regions` (
			  `id` int(11) NOT NULL auto_increment,
			  `from_id` int(11) NOT NULL,
			  `to_id` int(11) NOT NULL,
			  `from_region` varchar(300) NOT NULL,
			  `to_region` varchar(300) NOT NULL,
			  `magistral` int(11) NOT NULL,
			  `data` text NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
		}

		if( !$this->isTableExists( 'russianpost2_remote' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "russianpost2_remote` (
			  `id` int(11) NOT NULL auto_increment,
			  `ems_code` varchar(100) NOT NULL,
			  `postcode` int(10) NOT NULL,
			  `city` varchar(100) NOT NULL,
			  `start` varchar(20) NOT NULL,
			  `end` varchar(20) NOT NULL,
			  `limit_type` varchar(10) NOT NULL,
			  `is_ems` int(1) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");
		}
		/* start 112 */
		if( !$this->isTableExists( 'russianpost2_packs' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS 
			  `" . DB_PREFIX . "russianpost2_packs` (
			  `id` int(11) NOT NULL auto_increment,
			  `tariff_pack_id` int(11) NOT NULL,
			  `pack_key` varchar(100) NOT NULL,
			  `name` varchar(300) NOT NULL,
			  `length` int(11) NOT NULL,
			  `width` int(11) NOT NULL,
			  `height` int(11) NOT NULL,
			  `price` float NOT NULL,
			  `data` text NOT NULL,
			  `status` int(11) NOT NULL,
			  `default_value` int(11) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		}
		
		if( !$this->isTableExists( 'russianpost2_cities' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS 
			  `" . DB_PREFIX . "russianpost2_cities` (
			  `id` int(11) NOT NULL auto_increment,
			  `region_id` int(11) NOT NULL,
			  `pre` varchar(100) NOT NULL,
			  `city` varchar(300) NOT NULL,
			  `postcalc_city` varchar(300) NOT NULL,
			  `start` int(11) NOT NULL,
			  `end` int(11) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		}
		if( !$this->isTableExists( 'russianpost2_indexes' ) )
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS 
				`" . DB_PREFIX . "russianpost2_indexes` (
				`id` int(11) NOT NULL auto_increment,
				`region_id` int(11) NOT NULL,
				`postcode` varchar(100) NOT NULL,
				`address` varchar(300) NOT NULL,
				`wtime` varchar(300) NOT NULL,
				`city` varchar(300) NOT NULL,
				`lat` varchar(100) NOT NULL,
				`lon` varchar(100) NOT NULL,
				`is_online_parcel` int(11) NOT NULL,
				`is_online_courier` int(11) NOT NULL,
				`is_ems_optimal` int(11) NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		}
		
		//$this->checkColumn("setting", "value", "LONGTEXT");
		$this->checkColumn("russianpost2_services", "available_packs", "TEXT");
		$this->checkColumn("russianpost2_services", "is_pack_required", "INT(11)");
		$this->checkColumn("russianpost2_services", "tariff_key", "VARCHAR(100)");
		$this->checkColumn("russianpost2_services", "sort_order", "FLOAT");
		
		$this->checkColumn("russianpost2_options", "tariff_service_id", "VARCHAR(100)");
		$this->checkColumn("russianpost2_options", "available_tariff_key", "VARCHAR(300)");
		$this->checkColumn("russianpost2_cities", "postcalc_city", "VARCHAR(300)");
		
		/* start 0611 */
		$this->checkColumn("order", "shipping_method", "TEXT");
		$this->checkColumn("order_total", "title", "VARCHAR(300)");
		/* end 0611 */
		$this->checkColumn("russianpost2_customs", "price", "FLOAT");
		$this->checkColumn("russianpost2_customs", "currency", "VARCHAR(3)");
		
		
		$this->checkPvzDB(); 
		/* end 112 */
	}
	
	/* start 112 */
	private function checkColumn($table, $column, $type)
	{
		$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX . $table."`");
		
		$hash = array();
		
		foreach($query->rows as $row)
		{
			$hash[ $row['Field'] ] = $row['Type'];
		}
		
		if( !isset($hash[ $column ]) )
		{
			$sql = "ALTER TABLE `" . DB_PREFIX . $table . "` 
			ADD `".$column."` ". $type ." NOT NULL";
			$this->db->query($sql);
		}
		elseif( strtoupper( $hash[ $column ] ) != strtoupper($type) )
		{
			if( strtoupper($type) == 'TEXT' &&  
				(
					strtoupper( $hash[ $column ] ) == 'LONGTEXT' ||
					strtoupper( $hash[ $column ] ) == 'MEDIUMTEXT' 
				)
			) 
			{
				// none
			}
			else
			{
				$this->db->query("ALTER TABLE  `" . DB_PREFIX . $table  . "` 
				CHANGE  `".$column."`  `".$column."` ".$type." NOT NULL");
			}
		}
	}
	/* end 112 */
	
	private function isTableExists( $table_key )
	{
		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $this->db->escape($table_key). "'");
		
		return empty($query->row) ? false : true;
	}
	
	public function dropDB()
	{
		foreach($this->tables as $table_key)
		{
			$this->db->query("DROP TABLE `" . DB_PREFIX . $this->db->escape($table_key). "`");
		}
		
		$this->db->query("DROP TABLE `" . DB_PREFIX . "russianpost2_filters`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "russianpost2_adds`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "russianpost2_current_methods`");
	}
	
	public function checkFirst()
	{
		if( $this->isTableExists( 'russianpost2_city2city' ) )
		{
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "russianpost2_city2city` LIMIT 1");
			
			if( $query->row ) return true;
		}
		
		return false;
	}
	
	
	public function uploadData()
	{
		$this->checkDB();
		
		// ----
		
		$page = $this->uploadDataURL();
		
		/* start 1410 */
		if( !$page || (int)$page < 0 )
		{
			return $page;
		}
		/* end 1410 */
		
		$struct_block = $this->getSubBlock("struct", $page);
		$data_block = $this->getSubBlock("data", $page);
		
		//$sql_ar = array();
		
		foreach($this->tables as $table_key)
		{
			$this->db->query("DELETE FROM `" . DB_PREFIX .$table_key . "`");
			
			$table_struct_ar = explode("|", $this->getSubBlock($table_key, $struct_block) );
			
			$table_lines_ar = explode(";;;", $this->getSubBlock($table_key, $data_block) );
			
			foreach($table_lines_ar as $line)
			{
				$values = explode("#", $line);
				
				$data_ar = array();
				
				for($i = 0; $i < count( $values ); $i++ )
				{
					if( !isset($table_struct_ar[$i]) ) exit("NO KEY: ".$this->db->escape($table_key)." count values: ".count($values)."<hr>".$line);
					
					$data_ar[] = " `".$this->db->escape($table_struct_ar[$i])."` = '".$this->db->escape($values[$i])."' ";
				}
				
				$sql =  "INSERT INTO `" . DB_PREFIX . $table_key . "` SET ".implode(", ", $data_ar);
				
				//echo $sql."<hr>";
				$this->db->query($sql);
				
				//$sql_ar[] = $sql;
			}
		}
		
		// ---------
		
		$ems_regions = $this->getEmsRegions();
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "russianpost2_regions`");
		
		foreach($query->rows as $row)
		{
			$korens_ar = explode(":", $row['korens']);
			
			$stop_str = "";
			$search_list = array();
			
			foreach($korens_ar as $koren)
			{
				if( preg_match("/^\-/", $koren) )
				{
					$stop_str .= " AND z.name NOT LIKE '%".$this->db->escape( preg_replace("/^\-/", "", $koren) )."%' "; 
				}
				else
				{
					$search_list[] = $koren;
				}
			}
			
			foreach($korens_ar as $koren)
			{
				$current_ems_code = '';
				
				foreach($ems_regions as $ems_code=>$region_name)
				{
					$stop_str2 = str_replace("z.name", "'".$this->db->escape($region_name)."'", $stop_str);
					
					$check_query = $this->db->query("SELECT IF('".$this->db->escape($region_name)."' LIKE '%".$this->db->escape($koren)."%' ".$stop_str2.", 1, 0) as is_in");
					
					if( $check_query->row['is_in'] )
					{
						$current_ems_code = $ems_code;
					}
				}
				
				if( $current_ems_code )
				{
					$this->db->query("UPDATE `" . DB_PREFIX . "russianpost2_regions` 
						SET 
							ems_ems = '".$this->db->escape($current_ems_code)."' 
						WHERE 
							id = '".(int)$row['id']."'");
				}
				
				// ----------
				
				$search_query = $this->db->query("SELECT z.* 
												  FROM `" . DB_PREFIX . "zone` z 
												  JOIN `" . DB_PREFIX . "country` c 
												  ON c.`country_id`=z.`country_id`
												  WHERE  z.status=1 AND 
												  c.`iso_code_2` = 'RU' AND 
												  z.name LIKE '%".$this->db->escape($koren)."%' ".$stop_str);
				if( $search_query->row )
				{
					$this->db->query("UPDATE `" . DB_PREFIX . "russianpost2_regions` 
						SET 
							id_oc = '".(int)$search_query->row['zone_id']."' 
						WHERE id = '".(int)$row['id']."'");
				}
			}
		}
		
		
		// -----
		
		$version = $this->getSubBlock('version', $page);
		$min_module_version_for_sfp = $this->getSubBlock('min_module_version_for_sfp', $page);
		$min_module_version_for_work = $this->getSubBlock('min_module_version_for_work', $page);
		
		$this->updateOneSetting('russianpost2_version', $version );
		$this->updateOneSetting('russianpost2_min_module_version_for_sfp', $min_module_version_for_sfp );
		$this->updateOneSetting('russianpost2_min_module_version_for_work', $min_module_version_for_work );
		
		return true;
	}
	
	public function checkNotify()
	{
		if( !$this->config->get('russianpost2_notifyme') || !$this->config->get('russianpost2_notifyme_email') )
		{
			return;
		}
		
		list($module_version, $sfp_version, $min_module_version_for_sfp, $min_module_version_for_work) = $this->getVersions();
		
		if( (float)$module_version < (float)$min_module_version_for_work )
		{
			$this->notifyToEmail($this->config->get('russianpost2_notifyme_email'));
			echo "notified<br>";
		}
	}
	
	public function notifyToEmail($email)
	{
		$subject = "Необходимо обновить модуль";
		$html = "Здравствуйте, <br><br>
		
		Необходимо обновить модуль Почта России 2.0<br><br>
		
		Для этого выполните следующие действия:<br>
		1. Скачайте последнюю версию модуля Почта России 2.0 на сайте где Вы его купили.<br>
		Вы могли купить модуль на следующих сайтах:<br>
		- <a href=\"https://opencartforum.com/files/file/1420-%D0%BF%D0%BE%D1%87%D1%82%D0%B0-%D1%80%D0%BE%D1%81%D1%81%D0%B8%D0%B8-%D0%BD%D0%B0%D0%BB%D0%BE%D0%B6%D0%B5%D0%BD%D0%BD%D1%8B%D0%B9-%D0%BF%D0%BB%D0%B0%D1%82%D0%B5%D0%B6/\">https://opencartforum.com</a> <br>
		- <a href=\"https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=14551\">https://www.opencart.com/</a> <br>
		- <a href=\"http://softpodkluch.ru\">http://softpodkluch.ru</a> (авторизуйтесь в личном кабинете) <br><br>
		
		2. Перезалейте файлы из папки upload в корень сайта с заменой старых файлов. <br><br>
		
		3. Зайдите в настройки модуля в админке в Дополнения -> Доставка и сохраните настройки.
		
		<br><br>-------------------<br>
		Вы получили это сообщение потому что у Вас на сайте ".HTTP_SERVER." установлен модуль Почта России 2.0 и в настройках модуля включена опция \"Уведомлять о необходимости обновить модуль на e-mail\"";
		
		$text = "Здравствуйте, 
		
		Необходимо обновить модуль Почта России 2.0
		
		Для этого выполните следующие действия:
		1. Скачайте последнюю версию модуля Почта России 2.0 на сайте где Вы его купили.
		Вы могли купить модуль на следующих сайтах:
		- https://opencartforum.com/files/file/1420-%D0%BF%D0%BE%D1%87%D1%82%D0%B0-%D1%80%D0%BE%D1%81%D1%81%D0%B8%D0%B8-%D0%BD%D0%B0%D0%BB%D0%BE%D0%B6%D0%B5%D0%BD%D0%BD%D1%8B%D0%B9-%D0%BF%D0%BB%D0%B0%D1%82%D0%B5%D0%B6/
		- https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=14551
		- http://softpodkluch.ru (авторизуйтесь в личном кабинете)
		
		2. Перезалейте файлы из папки upload в корень сайта с заменой старых файлов. 
		
		3. Зайдите в настройки модуля в админке в Дополнения -> Доставка и сохраните настройки.
		
		-------------------
		Вы получили это сообщение потому что у Вас на сайте ".HTTP_SERVER." установлен модуль Почта России 2.0 и в настройках модуля включена опция \"Уведомлять о необходимости обновить модуль на e-mail\"";
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($html); 
		$mail->setText($text);
		$mail->send();
	}
	
	public function getVersions()
	{
		$files = glob(DIR_SYSTEM . 'library/russianpost2/version.*.txt');
		 
		$file = $files[0];
		
		$file = str_replace(DIR_SYSTEM . 'library/russianpost2/version.', "", $file);
		$file = str_replace('.txt', "", $file);
		
		return array( 
			$file,  
			$this->config->get('russianpost2_version'),
			$this->config->get('russianpost2_min_module_version_for_sfp'),
			$this->config->get('russianpost2_min_module_version_for_work')
		);
	}
	
	
	public function getGeo2EmsHash()
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "russianpost2_regions");
		
		$hash = array();
		
		foreach( $query->rows as $row ) {
			$hash[ $row['ems_code'] ] = $row['ems_name'];
		}
		
		return $hash;
	}
	
	/* start 3110 */
	
	private function cmp($a, $b)
	{
		if ($a['name'] == $b['name']) {
			return 0;
		}
		return ($a['name'] < $b['name']) ? -1 : 1;
	}

	public function getCurrentRegions()
	{
		if( $this->config->get('russianpost2_regions2zones') )
		{
			$russianpost2_regions2zones = $this->config->get('russianpost2_regions2zones');
			
			$result = array();
			foreach($russianpost2_regions2zones as $ems_code => $zones)
			{
				/* start 3007 */
				$ems_code = str_replace("__", ".", $ems_code);
				/* end 3007 */
				
				$query = $this->db->query("SELECT rr.*
				FROM `" . DB_PREFIX . "russianpost2_regions` rr
				WHERE ems_code = '".$this->db->escape($ems_code)."'
				ORDER BY rr.`ems_name` ASC");
			
				$zones_arr = array();
				
				foreach( $zones as $zone_id )
				{
					$query2 = $this->db->query("SELECT z.*
					FROM `" . DB_PREFIX . "zone` z
					JOIN `" . DB_PREFIX . "country` c ON c.country_id = z.country_id
					AND c.iso_code_2 = 'RU'
					WHERE z.status=1 
					AND z.zone_id = '".(int)$zone_id."'
					ORDER BY z.`name` ASC");
					
					if( !empty($query2->row) )
					$zones_arr[] = $query2->row;
				}
				
				$result[] = array(
						"name" 		=> $query->row['ems_name'],
						"ems_code" 	=> $query->row['ems_code'],
						/* start 3007 */
						"ems_code_formatted" 	=> str_replace(".", "__", $query->row['ems_code']),
						/* end 3007 */
						"zones" => $zones_arr
				);	
			}
			
			
			$query = $this->db->query("SELECT rr.*
			FROM `" . DB_PREFIX . "russianpost2_regions` rr
			ORDER BY rr.`ems_name` ASC");
			
			foreach($query->rows as $row)
			{
				if( empty( $russianpost2_regions2zones[ $row['ems_code'] ] )
					&&
					empty( $russianpost2_regions2zones[ str_replace(".", "__", $row['ems_code']) ] ) )
				{
					$result[] = array(
						"name" 		=> $row['ems_name'],
						"ems_code" 	=> $row['ems_code'],
						/* start 3007 */
						"ems_code_formatted" 	=> str_replace(".", "__", $row['ems_code']),
						/* end 3007 */
						"zones" => array()
					);
				}
			}
			
			
			usort($result, array($this, "cmp"));
			
			return $result;
		}
		else
		{
			$query = $this->db->query("SELECT rr.*
			FROM `" . DB_PREFIX . "russianpost2_regions` rr
			ORDER BY rr.`ems_name` ASC");
			
			$result = array();
			
			foreach($query->rows as $row)
			{
				$query2 = $this->db->query("SELECT z.*
				FROM `" . DB_PREFIX . "zone` z
				JOIN `" . DB_PREFIX . "country` c ON c.country_id = z.country_id
				AND c.iso_code_2 = 'RU'
				WHERE z.status=1 
				AND z.zone_id = '".(int)$row['id_oc']."'
				ORDER BY z.`name` ASC");
				
				$result[] = array(
					"name" 		=> $row['ems_name'],
					"ems_code" 	=> $row['ems_code'],
					/* start 3007 */
					"ems_code_formatted" 	=> str_replace(".", "__", $row['ems_code']),
					/* end 3007 */
					"zones" => $query2->rows
				);
			}
			
			return $result;
		}
	}
	/* end 3110 */
	
	
	public function getEmsRegions()
	{
		//$url = "http://emspost.ru/api/rest/?method=ems.get.locations&type=regions&plain=true";
		
		//$region_data = $this->uploadURL($url);
		$region_data = '{"rsp":{"stat":"ok","locations":[{"value":"region--respublika-adygeja","name":"АДЫГЕЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--respublika-altaj","name":"АЛТАЙ РЕСПУБЛИКА","type":"regions"},{"value":"region--altajskij-kraj","name":"АЛТАЙСКИЙ КРАЙ","type":"regions"},{"value":"region--amurskaja-oblast","name":"АМУРСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--arhangelskaja-oblast","name":"АРХАНГЕЛЬСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--astrahanskaja-oblast","name":"АСТРАХАНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-bashkortostan","name":"БАШКОРТОСТАН РЕСПУБЛИКА","type":"regions"},{"value":"region--belgorodskaja-oblast","name":"БЕЛГОРОДСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--brjanskaja-oblast","name":"БРЯНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-burjatija","name":"БУРЯТИЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--vladimirskaja-oblast","name":"ВЛАДИМИРСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--volgogradskaja-oblast","name":"ВОЛГОГРАДСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--vologodskaja-oblast","name":"ВОЛОГОДСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--voronezhskaja-oblast","name":"ВОРОНЕЖСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-dagestan","name":"ДАГЕСТАН РЕСПУБЛИКА","type":"regions"},{"value":"region--evrejskaja-ao","name":"ЕВРЕЙСКАЯ АВТОНОМНАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--zabajkalskij-kraj","name":"ЗАБАЙКАЛЬСКИЙ КРАЙ","type":"regions"},{"value":"region--ivanovskaja-oblast","name":"ИВАНОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-ingushetija","name":"ИНГУШЕТИЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--irkutskaja-oblast","name":"ИРКУТСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--kabardino-balkarskaja-respublika","name":"КАБАРДИНО-БАЛКАРСКАЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--kaliningradskaja-oblast","name":"КАЛИНИНГРАДСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-kalmykija","name":"КАЛМЫКИЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--kaluzhskaja-oblast","name":"КАЛУЖСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--kamchatskij-kraj","name":"КАМЧАТСКИЙ КРАЙ","type":"regions"},{"value":"region--karachaevo-cherkesskaja-respublika","name":"КАРАЧАЕВО-ЧЕРКЕССКАЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--respublika-karelija","name":"КАРЕЛИЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--kemerovskaja-oblast","name":"КЕМЕРОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--kirovskaja-oblast","name":"КИРОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-komi","name":"КОМИ РЕСПУБЛИКА","type":"regions"},{"value":"region--kostromskaja-oblast","name":"КОСТРОМСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--krasnodarskij-kraj","name":"КРАСНОДАРСКИЙ КРАЙ","type":"regions"},{"value":"region--krasnojarskij-kraj","name":"КРАСНОЯРСКИЙ КРАЙ","type":"regions"},{"value":"region--kurganskaja-oblast","name":"КУРГАНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--kurskaja-oblast","name":"КУРСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--leningradskaja-oblast","name":"ЛЕНИНГРАДСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--lipeckaja-oblast","name":"ЛИПЕЦКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--magadanskaja-oblast","name":"МАГАДАНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-marij-el","name":"МАРИЙ ЭЛ РЕСПУБЛИКА","type":"regions"},{"value":"region--respublika-mordovija","name":"МОРДОВИЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--moskovskaja-oblast","name":"МОСКОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--murmanskaja-oblast","name":"МУРМАНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--neneckij-ao","name":"НЕНЕЦКИЙ АВТОНОМНЫЙ ОКРУГ","type":"regions"},{"value":"region--nizhegorodskaja-oblast","name":"НИЖЕГОРОДСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--novgorodskaja-oblast","name":"НОВГОРОДСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--novosibirskaja-oblast","name":"НОВОСИБИРСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"663300","name":"НОРИЛЬСКИЙ ПРОМЫШЛЕНННЫЙ РАЙОН","type":"regions"},{"value":"region--omskaja-oblast","name":"ОМСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--orenburgskaja-oblast","name":"ОРЕНБУРГСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--orlovskaja-oblast","name":"ОРЛОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--penzenskaja-oblast","name":"ПЕНЗЕНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--permskij-kraj","name":"ПЕРМСКИЙ КРАЙ","type":"regions"},{"value":"region--primorskij-kraj","name":"ПРИМОРСКИЙ КРАЙ","type":"regions"},{"value":"region--pskovskaja-oblast","name":"ПСКОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--rostovskaja-oblast","name":"РОСТОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--rjazanskaja-oblast","name":"РЯЗАНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--samarskaja-oblast","name":"САМАРСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--saratovskaja-oblast","name":"САРАТОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-saha-yakutija","name":"САХА (ЯКУТИЯ) РЕСПУБЛИКА","type":"regions"},{"value":"region--sahalinskaja-oblast","name":"САХАЛИНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--sverdlovskaja-oblast","name":"СВЕРДЛОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-sev.osetija-alanija","name":"СЕВЕРНАЯ ОСЕТИЯ-АЛАНИЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--smolenskaja-oblast","name":"СМОЛЕНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--stavropolskij-kraj","name":"СТАВРОПОЛЬСКИЙ КРАЙ","type":"regions"},{"value":"region--tajmyrskij-ao","name":"ТАЙМЫРСКИЙ ДОЛГАНО-НЕНЕЦКИЙ РАЙОН","type":"regions"},{"value":"region--tambovskaja-oblast","name":"ТАМБОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-tatarstan","name":"ТАТАРСТАН РЕСПУБЛИКА","type":"regions"},{"value":"region--tverskaja-oblast","name":"ТВЕРСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--tomskaja-oblast","name":"ТОМСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--tulskaja-oblast","name":"ТУЛЬСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--respublika-tyva","name":"ТЫВА РЕСПУБЛИКА","type":"regions"},{"value":"region--tjumenskaja-oblast","name":"ТЮМЕНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--udmurtskaja-respublika","name":"УДМУРТСКАЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--uljanovskaja-oblast","name":"УЛЬЯНОВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--khabarovskij-kraj","name":"ХАБАРОВСКИЙ КРАЙ","type":"regions"},{"value":"region--respublika-khakasija","name":"ХАКАСИЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--khanty-mansijskij-ao","name":"ХАНТЫ-МАНСИЙСКИЙ-ЮГРА АВТОНОМНЫЙ ОКРУГ","type":"regions"},{"value":"region--cheljabinskaja-oblast","name":"ЧЕЛЯБИНСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--chechenskaya-respublika","name":"ЧЕЧЕНСКАЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--chuvashskaja-respublika","name":"ЧУВАШСКАЯ РЕСПУБЛИКА","type":"regions"},{"value":"region--chukotskij-ao","name":"ЧУКОТСКИЙ АВТОНОМНЫЙ ОКРУГ","type":"regions"},{"value":"region--yamalo-neneckij-ao","name":"ЯМАЛО-НЕНЕЦКИЙ АВТОНОМНЫЙ ОКРУГ","type":"regions"},{"value":"region--yaroslavskaja-oblast","name":"ЯРОСЛАВСКАЯ ОБЛАСТЬ","type":"regions"},{"value":"region--kazahstan","name":"КАЗАХСТАН","type":"regions"},{"value":"region--crimea","name":"КРЫМ РЕСПУБЛИКА","type":"regions"}]}}';
		
		$obj = json_decode( $region_data );
		$regions_hash = array();
			
		foreach($obj->{'rsp'}->{'locations'} as $val)
		{
			$regions_hash[$val->{'value'}] = $val->{'name'};
		}
		
		//-------------
		$region_data = '{"rsp":{"stat":"ok","locations":[{"value":"city--abakan","name":"АБАКАН","type":"cities"},{"value":"city--anadyr","name":"АНАДЫРЬ","type":"cities"},{"value":"city--anapa","name":"АНАПА","type":"cities"},{"value":"city--arhangelsk","name":"АРХАНГЕЛЬСК","type":"cities"},{"value":"city--astrahan","name":"АСТРАХАНЬ","type":"cities"},{"value":"city--bajkonur","name":"БАЙКОНУР","type":"cities"},{"value":"city--barnaul","name":"БАРНАУЛ","type":"cities"},{"value":"city--belgorod","name":"БЕЛГОРОД","type":"cities"},{"value":"city--birobidzhan","name":"БИРОБИДЖАН","type":"cities"},{"value":"city--blagoveshhensk","name":"БЛАГОВЕЩЕНСК","type":"cities"},{"value":"city--brjansk","name":"БРЯНСК","type":"cities"},{"value":"city--velikij-novgorod","name":"ВЕЛИКИЙ НОВГОРОД","type":"cities"},{"value":"city--vladivostok","name":"ВЛАДИВОСТОК","type":"cities"},{"value":"city--vladikavkaz","name":"ВЛАДИКАВКАЗ","type":"cities"},{"value":"city--vladimir","name":"ВЛАДИМИР","type":"cities"},{"value":"city--volgograd","name":"ВОЛГОГРАД","type":"cities"},{"value":"city--vologda","name":"ВОЛОГДА","type":"cities"},{"value":"city--vorkuta","name":"ВОРКУТА","type":"cities"},{"value":"city--voronezh","name":"ВОРОНЕЖ","type":"cities"},{"value":"city--gorno-altajsk","name":"ГОРНО-АЛТАЙСК","type":"cities"},{"value":"city--groznyj","name":"ГРОЗНЫЙ","type":"cities"},{"value":"city--dudinka","name":"ДУДИНКА","type":"cities"},{"value":"city--ekaterinburg","name":"ЕКАТЕРИНБУРГ","type":"cities"},{"value":"city--elizovo","name":"ЕЛИЗОВО","type":"cities"},{"value":"city--ivanovo","name":"ИВАНОВО","type":"cities"},{"value":"city--izhevsk","name":"ИЖЕВСК","type":"cities"},{"value":"city--irkutsk","name":"ИРКУТСК","type":"cities"},{"value":"city--ioshkar-ola","name":"ЙОШКАР-ОЛА","type":"cities"},{"value":"city--kazan","name":"КАЗАНЬ","type":"cities"},{"value":"city--kaliningrad","name":"КАЛИНИНГРАД","type":"cities"},{"value":"city--kaluga","name":"КАЛУГА","type":"cities"},{"value":"city--kemerovo","name":"КЕМЕРОВО","type":"cities"},{"value":"city--kirov","name":"КИРОВ","type":"cities"},{"value":"city--kostomuksha","name":"КОСТОМУКША","type":"cities"},{"value":"city--kostroma","name":"КОСТРОМА","type":"cities"},{"value":"city--krasnodar","name":"КРАСНОДАР","type":"cities"},{"value":"city--krasnojarsk","name":"КРАСНОЯРСК","type":"cities"},{"value":"city--kurgan","name":"КУРГАН","type":"cities"},{"value":"city--kursk","name":"КУРСК","type":"cities"},{"value":"city--kyzyl","name":"КЫЗЫЛ","type":"cities"},{"value":"city--lipeck","name":"ЛИПЕЦК","type":"cities"},{"value":"city--magadan","name":"МАГАДАН","type":"cities"},{"value":"city--magnitogorsk","name":"МАГНИТОГОРСК","type":"cities"},{"value":"city--majkop","name":"МАЙКОП","type":"cities"},{"value":"city--mahachkala","name":"МАХАЧКАЛА","type":"cities"},{"value":"city--mineralnye-vody","name":"МИНЕРАЛЬНЫЕ ВОДЫ","type":"cities"},{"value":"city--mirnyj","name":"МИРНЫЙ","type":"cities"},{"value":"city--moskva","name":"МОСКВА","type":"cities"},{"value":"city--murmansk","name":"МУРМАНСК","type":"cities"},{"value":"city--mytishhi","name":"МЫТИЩИ","type":"cities"},{"value":"city--naberezhnye-chelny","name":"НАБЕРЕЖНЫЕ ЧЕЛНЫ","type":"cities"},{"value":"city--nadym","name":"НАДЫМ","type":"cities"},{"value":"city--nazran","name":"НАЗРАНЬ","type":"cities"},{"value":"city--nalchik","name":"НАЛЬЧИК","type":"cities"},{"value":"city--narjan-mar","name":"НАРЬЯН-МАР","type":"cities"},{"value":"city--nerjungri","name":"НЕРЮНГРИ","type":"cities"},{"value":"city--neftejugansk","name":"НЕФТЕЮГАНСК","type":"cities"},{"value":"city--nizhnevartovsk","name":"НИЖНЕВАРТОВСК","type":"cities"},{"value":"city--nizhnij-novgorod","name":"НИЖНИЙ НОВГОРОД","type":"cities"},{"value":"city--novokuzneck","name":"НОВОКУЗНЕЦК","type":"cities"},{"value":"city--novorossijsk","name":"НОВОРОССИЙСК","type":"cities"},{"value":"city--novosibirsk","name":"НОВОСИБИРСК","type":"cities"},{"value":"city--novyj-urengoj","name":"НОВЫЙ УРЕНГОЙ","type":"cities"},{"value":"city--norilsk","name":"НОРИЛЬСК","type":"cities"},{"value":"city--nojabrsk","name":"НОЯБРЬСК","type":"cities"},{"value":"city--omsk","name":"ОМСК","type":"cities"},{"value":"city--orel","name":"ОРЁЛ","type":"cities"},{"value":"city--orenburg","name":"ОРЕНБУРГ","type":"cities"},{"value":"city--penza","name":"ПЕНЗА","type":"cities"},{"value":"city--perm","name":"ПЕРМЬ","type":"cities"},{"value":"city--petrozavodsk","name":"ПЕТРОЗАВОДСК","type":"cities"},{"value":"city--petropavlovsk-kamchatskij","name":"ПЕТРОПАВЛОВСК-КАМЧАТСКИЙ","type":"cities"},{"value":"city--pskov","name":"ПСКОВ","type":"cities"},{"value":"city--rostov-na-donu","name":"РОСТОВ-НА-ДОНУ","type":"cities"},{"value":"city--rjazan","name":"РЯЗАНЬ","type":"cities"},{"value":"city--salehard","name":"САЛЕХАРД","type":"cities"},{"value":"city--samara","name":"САМАРА","type":"cities"},{"value":"city--sankt-peterburg","name":"САНКТ-ПЕТЕРБУРГ","type":"cities"},{"value":"city--saransk","name":"САРАНСК","type":"cities"},{"value":"city--saratov","name":"САРАТОВ","type":"cities"},{"value":"city--smolensk","name":"СМОЛЕНСК","type":"cities"},{"value":"city--sochi","name":"СОЧИ","type":"cities"},{"value":"city--stavropol","name":"СТАВРОПОЛЬ","type":"cities"},{"value":"city--strezhevoj","name":"СТРЕЖЕВОЙ","type":"cities"},{"value":"city--surgut","name":"СУРГУТ","type":"cities"},{"value":"city--syktyvkar","name":"СЫКТЫВКАР","type":"cities"},{"value":"city--tambov","name":"ТАМБОВ","type":"cities"},{"value":"city--tver","name":"ТВЕРЬ","type":"cities"},{"value":"city--toljatti","name":"ТОЛЬЯТТИ","type":"cities"},{"value":"city--tomsk","name":"ТОМСК","type":"cities"},{"value":"city--tula","name":"ТУЛА","type":"cities"},{"value":"city--tynda","name":"ТЫНДА","type":"cities"},{"value":"city--tjumen","name":"ТЮМЕНЬ","type":"cities"},{"value":"city--ulan-udje","name":"УЛАН-УДЭ","type":"cities"},{"value":"city--uljanovsk","name":"УЛЬЯНОВСК","type":"cities"},{"value":"city--usinsk","name":"УСИНСК","type":"cities"},{"value":"city--ufa","name":"УФА","type":"cities"},{"value":"city--uhta","name":"УХТА","type":"cities"},{"value":"city--khabarovsk","name":"ХАБАРОВСК","type":"cities"},{"value":"city--khanty-mansijsk","name":"ХАНТЫ-МАНСИЙСК","type":"cities"},{"value":"city--kholmsk","name":"ХОЛМСК","type":"cities"},{"value":"city--cheboksary","name":"ЧЕБОКСАРЫ","type":"cities"},{"value":"city--cheljabinsk","name":"ЧЕЛЯБИНСК","type":"cities"},{"value":"city--cherepovec","name":"ЧЕРЕПОВЕЦ","type":"cities"},{"value":"city--cherkessk","name":"ЧЕРКЕССК","type":"cities"},{"value":"city--chita","name":"ЧИТА","type":"cities"},{"value":"city--elista","name":"ЭЛИСТА","type":"cities"},{"value":"city--yuzhno-sahalinsk","name":"ЮЖНО-САХАЛИНСК","type":"cities"},{"value":"city--yakutsk","name":"ЯКУТСК","type":"cities"},{"value":"city--yaroslavl","name":"ЯРОСЛАВЛЬ","type":"cities"},{"value":"city--sevastopol","name":"СЕВАСТОПОЛЬ","type":"cities"},{"value":"city--simferopol","name":"СИМФЕРОПОЛЬ","type":"cities"}]}}';
		
		$obj = json_decode( $region_data );
			
		foreach($obj->{'rsp'}->{'locations'} as $val)
		{
			if( $val->{'value'}=='city--sankt-peterburg' 
			 || $val->{'value'}=='city--moskva' 
			 || $val->{'value'}=='city--sevastopol' 
			)
			$regions_hash[$val->{'value'}] = $val->{'name'};
		}
		
		return $regions_hash;
	}
	
}
?>