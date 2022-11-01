<?php
class ModelShippingRussianPost2 extends Model {
	
	
	public function updateOneSetting($key, $value, $group='russianpost22') 
	{ 
		$groupField = 'group';
		if( $this->getVersion()  >= 2010 ) $groupField = 'code';
		
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
	
	public function getVersion()
	{
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
		
		return $VERSION;
	}
	
	// =====================================
	
	/* start 112 */
	private function isTableExists( $table_key )
	{
		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $this->db->escape($table_key). "'");
		
		return empty($query->row) ? false : true;
	}
	
	public function getPacks()
	{
		if( !$this->isTableExists('russianpost2_packs') )
			return array();
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "russianpost2_packs`";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	/* end 112 */
	public function getDeliveryTypes() 
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "russianpost2_config` WHERE config_key = 'delivery_types'");
		
		
		$column_hash = explode(":", $query->row['value']);
		
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "russianpost2_delivery_types` dt");
		
		$result = array();
		
		foreach( $query->rows as $row )
		{
			$data_ar = explode("|", $row['data']);
			
			foreach($data_ar as $i=>$value)
			{
				$row['data_'.$column_hash[$i]] = $value;
			}
			
			$result[] = $row;
		}
		
		return $result;
	}
	
	public function getRussianZones() {
	
		$query = $this->db->query("SELECT z.* 
		FROM `" . DB_PREFIX . "country` c JOIN `" . DB_PREFIX . "zone` z ON c.`country_id`=z.`country_id`
		WHERE c.`iso_code_2` = 'RU' AND z.status=1 ORDER BY z.`name` ASC");

		return $query->rows;
	}
	
	public function getMoscowRegion() {
	
		$query = $this->db->query("SELECT z.*, rr.ems_code
		FROM `" . DB_PREFIX . "country` c 
		JOIN `" . DB_PREFIX . "zone` z ON c.`country_id`=z.`country_id`
		JOIN `" . DB_PREFIX . "russianpost2_regions` rr ON rr.`id_oc`=z.`zone_id`
		
		WHERE c.`iso_code_2` = 'RU' AND z.status=1 
		AND rr.ems_code = 'city--moskva'
		ORDER BY z.`name` ASC");

		return $query->row;
	}
	
	
	
	public function updateExtentions( $data=array() ) {
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type`='shipping' AND `code` LIKE 'russianpost2f%'");
		
		if($data)
		{
			foreach($data as $method)
			{
				if( $method['code'] == 'russianpost2' ) continue;
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "extension (`type`, `code`) 
				VALUES ('shipping', '".$this->db->escape($method['code'])."')");
			}
		}
	}
	
	public function getChildServices($service_parent)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "russianpost2_services`  WHERE service_parent = '".$this->db->escape($service_parent)."'";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getEditableConfig()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "russianpost2_config`  WHERE is_editable = 1";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	/* start 2801 */
	public function getCustoms()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "russianpost2_customs` ";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	
	public function saveCustoms( $customs )
	{
		$updated_ids = array();
		
		foreach( $customs as $row )
		{
			if( !empty($row['custom_id']) )
			{
				$sql = "UPDATE `" . DB_PREFIX . "russianpost2_customs` SET 
					`name` = '".$this->db->escape($row['name'])."',
					`price` = '".(float)$row['price']."',
					`currency` = '".$this->db->escape( $row['currency'] )."',
					`type` = '".$this->db->escape( $row['type'] )."',  
					`status` = '".(int)$row['status']."'
				WHERE custom_id = '".(int)$row['custom_id']."'";
				$this->db->query($sql);
				
				$updated_ids[] = (int)$row['custom_id'];
			}
			else 
			{
				$sql = "INSERT INTO `" . DB_PREFIX . "russianpost2_customs` SET 
					`name` = '".$this->db->escape($row['name'])."',
					`price` = '".(float)$row['price']."',
					`currency` = '".$this->db->escape( $row['currency'] )."',
					`type` = '".$this->db->escape( $row['type'] )."',  
					`status` = '".(int)$row['status']."'";
				$this->db->query($sql);	
				
				$updated_ids[] = $this->db->getLastId();
			}
			
			
		}
		
		if( !empty($updated_ids) )
			$this->db->query("DELETE FROM `" . DB_PREFIX . "russianpost2_customs` WHERE custom_id NOT IN (".implode(",", $updated_ids).");");
		else
			$this->db->query("DELETE FROM `" . DB_PREFIX . "russianpost2_customs`");
		
	}
	
	/* end 2801 */
	
	public function getDedicatedServices()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "russianpost2_services` ";
		
		/* start metka 112 */
		$sql .= " ORDER BY sort_order";
		/* end metka 112 */
		
		$query = $this->db->query($sql);
		
		$results = array();
		
		$russianpost2_options = $this->config->get('russianpost2_options');
		
		foreach( $query->rows as $row ) 
		{
			//echo $row['service_key']."<br>";
			
			if( $row['service_key'] == 'parcel_20_insured' || 
				$row['service_key'] == 'parcel_50_insured' || 
				$row['service_key'] == 'parcel_20_insured_avia' || 
				$row['service_key'] == 'parcel_50_insured_avia' 
			)
			continue;
			
			if( !empty( $russianpost2_options[$row['service_parent']]['is_split']['status'] ) 
				||
				!empty( $russianpost2_options[$row['service_key']]['is_split']['status'] )
			)
				$row['is_split'] = 1;
			else
				$row['is_split'] = 0;
			
			if( !empty( $russianpost2_options[$row['service_parent']]['is_courier']['status'] ) 
				||
				!empty( $russianpost2_options[$row['service_key']]['is_courier']['status'] )
			)
				$row['is_courier'] = 1;
			else
				$row['is_courier'] = 0;
			
			
			if( empty( $row['service_parent'] ) )
			{
				$results[] = $row;
				//echo "m1: ".$row['service_key']."<br>";
			}
			elseif( strstr( $row['service_key'], 'registered' ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_registered']['status'] ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_registered']['is_dedicated'] )
			)
			{
				//echo "m2: ".$row['service_key']."<br>";
				$results[] = $row;
			}
			elseif( strstr( $row['service_key'], 'avia' ) && 
					strstr( $row['service_key'], 'insured' ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_avia_insured']['status'] ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_avia_insured']['is_dedicated'] )
			)
			{
				//echo "m3: ".$row['service_key']."<br>";
				$results[] = $row;
			}
			elseif( strstr( $row['service_key'], 'insured' ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_insured']['status'] ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_insured']['is_dedicated'] )
			)
			{
				//echo "m4: ".$row['service_key']."<br>";
				$results[] = $row;
			}
			elseif( strstr( $row['service_key'], 'avia' ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_avia']['status'] ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_avia']['is_dedicated'] )
			)
			{
				//echo "m5: ".$row['service_key']."<br>";
				$results[] = $row;
			}
			elseif( strstr( $row['service_key'], 'ems_optimal_courier' ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_insured']['status'] ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_insured']['is_dedicated'] )
			)
			{
				//echo "m4: ".$row['service_key']."<br>";
				$results[] = $row;
			}
			
			elseif( strstr( $row['service_key'], 'ecom' ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_compulsory']['status'] ) && 
					!empty( $russianpost2_options[$row['service_parent']]['is_compulsory']['is_dedicated'] )
			)
			{
				//echo "m5: ".$row['service_key']."<br>";
				$results[] = $row;
			}
			elseif( !$russianpost2_options )
			{
				//echo "m6: ".$row['service_key']."<br>";
				$results[] = $row;
			}
			
			
			//echo "<hr>";
		}
		
		return $results;
	}
	
	public function getServices($filter="")
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "russianpost2_services` ";
		
		if( $filter == 'top' )
		{
			$sql .= " WHERE service_parent = ''";
		}
		elseif( $filter == 'notop' )
		{
			$sql .= " WHERE service_parent != ''";
		}
		
		/* start metka 112 */
		$sql .= " ORDER BY sort_order";
		/* end metka 112 */
		
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getServiceOptions($service_key)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "russianpost2_options` WHERE service_key = '".$this->db->escape($service_key)."'";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	
	
	public function saveRegions($regions)
	{
		foreach( $regions as $zone_id=>$ems_code )
		{
			if( empty($zone_id) ) continue;
			
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` 
									   WHERE zone_id='".(int)$zone_id."'");
			
			if( empty($query->row) ) continue;
			
			$sql = "UPDATE `" . DB_PREFIX . "russianpost2_regions` 
							  SET 
								id_oc = '".(int)$zone_id."'
							  WHERE
								ems_code = '".$this->db->escape($ems_code)."'";
								
			$this->db->query($sql);

		}
		
	}
	
	public function getFilters($type = '')
	{
		$where = '';
		if( $type )
		{
			$where .= " WHERE `type` = '".$this->db->escape($type)."' ";
		}
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "russianpost2_filters` ".$where." ORDER BY sort_order");
		
		$result = array();
		
		foreach($query->rows as $row)
		{
			$data = unserialize($row['data']);
			$data['filter_id'] = $row['filter_id'];
			$data['filtername'] = $row['filtername'];
				
			$result[] = $data;
		}
		
		return $result;
	}
	
	public function saveFilters( $type, $filters )
	{
		$updated_ids = array();
		
		foreach( $filters as $filter )
		{
			if( !isset($filter['productfilter']) )
			$filter['productfilter'] = 0;
			
			
			if( !empty($filter['filter_id']) )
			{
				$sql = "UPDATE `" . DB_PREFIX . "russianpost2_filters` SET 
					`type` = '".$this->db->escape($filter['type'])."',  
					`productfilter` = '".$this->db->escape($filter['productfilter'])."',  
					
					filtername = '".$this->db->escape($filter['filtername'])."',  
					`data` = '".$this->db->escape( serialize($filter) )."',  
					`sort_order` = '".(int)$filter['sort_order']."'
				WHERE filter_id = '".(int)$filter['filter_id']."' 
				AND `type` = '".$this->db->escape($type)."'";
				$this->db->query($sql);
				
				$updated_ids[] = (int)$filter['filter_id'];
			}
			else 
			{
				$sql = "INSERT INTO `" . DB_PREFIX . "russianpost2_filters` SET 
					`productfilter` = '".$this->db->escape($filter['productfilter'])."',  
					filtername = '".$this->db->escape($filter['filtername'])."',  
					`type` = '".$this->db->escape($filter['type'])."',  
					`data` = '".$this->db->escape( serialize($filter) )."',  
					`sort_order` = '".(int)$filter['sort_order']."'"; 
				$this->db->query($sql);	
				
				$updated_ids[] = $this->db->getLastId();
			}
			
			
		}
		
		// ----------
		
		$alert = array();
		
		$where = '';
		if( !empty($updated_ids) )
		{
			$where = " filter_id NOT IN (".implode(",", $updated_ids).") AND ";
		}
		
		$todel_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "russianpost2_filters` WHERE ".$where." `type` = '".$this->db->escape($type)."';");
		
		if( !empty($todel_query->rows) )
		{
			
			foreach($todel_query->rows as $row)
			{
				list($count_adds, $count_methods, $count_filters) = $this->isFilterNoAvailableToDelete($row['filter_id'], $row['type']);
				
				if( $count_adds || $count_methods || $count_filters )
				{
					$updated_ids[] = (int)$row['filter_id'];
					$alert[] = array(
						"name" => $row['filtername'],
						"count_adds" => $count_adds,
						"count_methods" => $count_methods,
						"count_filters" => $count_filters,
					);
				}
			}
		}
		
		
		
		
		if( !empty($updated_ids) )
			$this->db->query("DELETE FROM `" . DB_PREFIX . "russianpost2_filters` WHERE filter_id NOT IN (".implode(",", $updated_ids).") AND `type` = '".$this->db->escape($type)."';");
		else
			$this->db->query("DELETE FROM `" . DB_PREFIX . "russianpost2_filters` WHERE `type` = '".$this->db->escape($type)."'");
		
		return $alert;
	}
	
	public function isFilterNoAvailableToDelete($filter_id, $type)
	{
		$count_adds = 0;
		$count_methods = 0;
		$count_filters = 0;
		
		$query = $this->db->query("SELECT COUNT(*) as cn FROM `" . DB_PREFIX . "russianpost2_adds` WHERE filters LIKE '%,".(int)$filter_id.",%'");
		$count_adds = $query->row['cn'];
		
		$query = $this->db->query("SELECT COUNT(*) as cn FROM `" . DB_PREFIX . "russianpost2_current_methods` WHERE filters LIKE '%,".(int)$filter_id.",%'");
		$count_methods = $query->row['cn'];
		
		if( $type == 'product' )
		{
			$query = $this->db->query("SELECT COUNT(*) as cn FROM `" . DB_PREFIX . "russianpost2_filters` WHERE productfilter = '".(int)$filter_id."'");
			$count_filters = $query->row['cn'];
		}
		
		return array(
			$count_adds, 
			$count_methods,
			$count_filters
		);
	}
	
	public function getAdds($type = '')
	{
		$where = '';
		if( $type )
		{
			$where .= " WHERE `type` = '".$this->db->escape($type)."' ";
		}
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "russianpost2_adds` ".$where." ORDER BY sort_order");
		
		$result = array();
		
		foreach($query->rows as $row)
		{
			$data = unserialize($row['data']);
			$data['adds_id'] = $row['adds_id'];
				
			$result[] = $data;
		}
		
		return $result;
	}
	
	public function saveCurrentMethods($methods)
	{
		
		$updated_ids = array();
		
		foreach( $methods as $row )
		{
			$filter_ids = array();
			
			if( !empty($row['filters']) ) 
			{
				foreach($row['filters'] as $filter_id)
				{
					$filter_ids[] = (int)$filter_id;
				}
			}
			
			if( !empty($row['submethods']) ) {
				foreach($row['submethods'] as $row2 ) {
					
					if( !empty($row2['filters']) ) 
					{
						foreach($row2['filters'] as $filter_id)
						{
							$filter_ids[] = (int)$filter_id;
						}
					}
					
					if( !empty($row2['services']) ) 
					{
						foreach($row2['services'] as $row3)
						{
							if( !empty($row3['filter']) )
							$filter_ids[] = (int)$row3['filter'];
						}
					}
				}
			}
			
			$filters = ','.implode(",", $filter_ids).',';
			
			$check_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "russianpost2_current_methods` WHERE code = '".$this->db->escape( $row['code'] )."'");
			
			if( !empty($check_query->row) )
			{
				
				$sql = "UPDATE `" . DB_PREFIX . "russianpost2_current_methods` SET 
					`filters` = '".$this->db->escape($filters)."',
					`data` = '".$this->db->escape( serialize($row) )."',  
					`sort_order` = '".(int)$row['sort_order']."'
				WHERE code = '".$this->db->escape( $row['code'] )."'";
				$this->db->query($sql);
			}
			else 
			{
				$sql = "INSERT INTO `" . DB_PREFIX . "russianpost2_current_methods` SET 
					`filters` = '".$this->db->escape($filters)."',
					`data` = '".$this->db->escape( serialize($row) )."',  
					`sort_order` = '".(int)$row['sort_order']."',
					`code` = '".$this->db->escape( $row['code'] )."'";
				$this->db->query($sql);	
			}
		
				
			$updated_ids[] = "'".$this->db->escape( $row['code'] )."'";	
			
		}
		
		if( !empty($updated_ids) )
			$this->db->query("DELETE FROM `" . DB_PREFIX . "russianpost2_current_methods` WHERE `code` NOT IN (".implode(",", $updated_ids).");");
		else
			$this->db->query("DELETE FROM `" . DB_PREFIX . "russianpost2_current_methods`");
		
	}
	
	
	public function saveAdds( $type, $adds )
	{
		$updated_ids = array();
		
		foreach( $adds as $row )
		{
			$filters = '';
			
			if( !empty($row['filters']) ) 
			{
				$filters .= ','.implode(",", $row['filters']).',';
			}
					
			if( !empty($row['adds_id']) )
			{
				
				$sql = "UPDATE `" . DB_PREFIX . "russianpost2_adds` SET 
					`filters` = '".$this->db->escape($filters)."',
					`type` = '".$this->db->escape($row['type'])."',
					`data` = '".$this->db->escape( serialize($row) )."',  
					`sort_order` = '".(int)$row['sort_order']."'
				WHERE adds_id = '".(int)$row['adds_id']."' 
				AND `type` = '".$this->db->escape($type)."'";
				$this->db->query($sql);
				
				$updated_ids[] = (int)$row['adds_id'];
			}
			else 
			{
				$sql = "INSERT INTO `" . DB_PREFIX . "russianpost2_adds` SET 
					`filters` = '".$this->db->escape($filters)."',
					`type` = '".$this->db->escape($row['type'])."',  
					`data` = '".$this->db->escape( serialize($row) )."',  
					`sort_order` = '".(int)$row['sort_order']."'"; 
				$this->db->query($sql);	
				
				$updated_ids[] = $this->db->getLastId();
			}
			
			
		}
		
		if( !empty($updated_ids) )
			$this->db->query("DELETE FROM `" . DB_PREFIX . "russianpost2_adds` WHERE adds_id NOT IN (".implode(",", $updated_ids).") AND `type` = '".$this->db->escape($type)."';");
		else
			$this->db->query("DELETE FROM `" . DB_PREFIX . "russianpost2_adds` WHERE `type` = '".$this->db->escape($type)."'");
		
	}
	
	public function getGramm()
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE TRIM(unit)='g' OR TRIM(unit)='g.' OR TRIM(unit)='г' OR TRIM(unit)='г.' OR TRIM(unit)='грам' OR TRIM(unit)='грамм' OR TRIM(unit)='gramm' OR TRIM(unit)='gram' 
		OR
		TRIM(title)='g' OR TRIM(title)='g.' OR TRIM(title)='г' OR TRIM(title)='г.' OR TRIM(title)='грам' OR TRIM(title)='грамм' OR TRIM(title)='gramm' OR TRIM(title)='gram' 
		");
		
		return $query->row;
	}
	
	public function getCm()
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE TRIM(unit)='Centimeter' OR TRIM(unit)='Cm' OR TRIM(unit)='см' OR TRIM(unit)='см.' OR TRIM(unit)='cm.' OR TRIM(unit)='сантиметр'
		OR TRIM(title)='Centimeter' OR TRIM(title)='Cm' OR TRIM(title)='см' OR TRIM(title)='см.' OR TRIM(title)='cm.' OR TRIM(title)='сантиметр'
		");
		
		return $query->row;
	}
	
	public function getVersions()
	{
		$files = glob(DIR_SYSTEM . 'library/russianpost2/version.*.txt');
		
		/* start 812 */
		$file = 0;
		
		
		foreach($files as $filename)
		{
			if( strstr($filename, 'version') )
			{
				$filename = str_replace(DIR_SYSTEM . 'library/russianpost2/version.', "", $filename);
				$filename = (float)str_replace('.txt', "", $filename);
				
				$ar = explode(".", $filename);
				
				$filename = $ar[0];
				
				if( !empty($ar[1]) )
				{
					if( (float)$ar[1] < 10 )
						$ar[1] = '0'.$ar[1];
					
					$filename .= '.'.$ar[1];
				}
				
				if( (float)$file <= (float)$filename )
				{
					$file = (float)$filename;
				}
			}
		}
		/* end 812 */
		
		
		return array( 
			$file,  
			$this->config->get('russianpost2_version'),
			$this->config->get('russianpost2_min_module_version_for_sfp'),
			$this->config->get('russianpost2_min_module_version_for_work')
		);
	}
	
	/* start metka-1 */
	public function clearCache()
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "russianpost2_cache");
	}
	/* end metka-1 */
	
	public function getRubCode()
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency 
		WHERE   code='RUB' OR 
				code='RUR' OR 
				TRIM(title)='Рубль' OR 
				TRIM(title)='Руб'   OR 
				TRIM(title)='руб.'  OR 
				TRIM(title)='rub.'  
		");
		
		if( empty($query->row['code']) )
		{
			return false;
		}
		else
		{
			return $query->row['code'];
		}
	}
	
	/* start 20092 */
	
	public function saveCountries($countries)
	{
		foreach( $countries as $country_id=>$iso_code_2 )
		{
			if( empty($query->row) ) continue;
			
			$sql = "UPDATE `" . DB_PREFIX . "russianpost2_countries` 
							  SET 
								id_oc = '".(int)$country_id."'
							  WHERE
								iso_code = '".$this->db->escape($iso_code_2)."'";
								
			$this->db->query($sql);
		}
	}
	
	/* end 20092 */	

	
	/* start 812 */
	public function customEditSetting($group, $values)
	{
		$groupField = 'group';
		if( $this->getVersion()  >= 2010 ) $groupField = 'code';
		
		foreach($values as $key=>$val)
		{
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE 
				`".$groupField."` = '" . $this->db->escape($group) . "' AND 
				`key` = '" . $this->db->escape($key) . "'");
				
			$serialized = 0;
			if( is_array($val) )
			{
				if(  version_compare(VERSION, '2.1.0.0') >= 0 )
				{
					$val = json_encode($val, true);
				}
				else
				{
					$val = serialize($val);
				} 
				$serialized = 1;
			}
			
			$sql = '';
			
			if( $query->row )
			{
				$sql = "UPDATE `" . DB_PREFIX . "setting` 
				SET 
					`value` = '" . $this->db->escape($val) . "',
					`serialized` = '".(int)$serialized."'
				WHERE 
					`".$groupField."` = '" . $this->db->escape($group) . "' AND 
					`key` = '" . $this->db->escape($key) . "'
				";
			}
			else
			{
				$sql = "INSERT INTO " . DB_PREFIX . "setting 
				SET 
					`value` = '" . $this->db->escape($val) . "',
					`".$groupField."` = '" . $this->db->escape($group) . "',
					`key` = '" . $this->db->escape($key) . "',
					`serialized` = '".(int)$serialized."'
				";
				
			}
			
			$this->db->query($sql);
		}
	}
	/* end 812 */
	
}

?>