<?php
	class ModelFeedAvitoexport extends Model {
		public function getRegions(){
			$query = $this->db->query("SELECT DISTINCT RegionName,RegionID FROM " . DB_PREFIX . "avitoexport_locations");
			return $query->rows;
		}
		
		public function getCities($region_id){
			$query = $this->db->query("SELECT DISTINCT CityName,CityID FROM " . DB_PREFIX . "avitoexport_locations WHERE RegionID = " . (int)$region_id);
			return $query->rows;
		}
		
		public function getCChilds($city_id){
			$query = $this->db->query("SELECT DISTINCT CityChildID,CityChildType,CityChildName FROM " . DB_PREFIX . "avitoexport_locations WHERE CityID = " . (int)$city_id . " AND (CityChildType IN('District','Subway'))");
			return $query->rows;
		}
		
		public function getCategories($parent_id = 0) {
			$category_data = array();
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.status = 1 AND c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
			
			foreach ($query->rows as $result) {
					$category_data[] = array(
						'category_id' => $result['category_id'],
						'name'        => $result['name'],
						'status'  	  => $result['status']
					);
			}	
			return $category_data;
		}
		
		public function createTable(){
			$query = $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "avitoexport_locations` (`RegionName` VARCHAR(60) NOT NULL,`RegionID` INT(11) NOT NULL,`CityID` INT(11) NOT NULL,`CityName` VARCHAR(60) NOT NULL,`CityChildID` INT(11) NOT NULL,`CityChildType` VARCHAR(60) NOT NULL,`CityChildName` VARCHAR(60) NOT NULL) COLLATE='utf8_general_ci'");
			return $query;
		}
		
		public function deleteTable(){
			$query = $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "avitoexport_locations");
		}
		
		public function put($regionName,$regionID,$cityID,$cityName,$ccID,$ccType,$ccName){
			$regionName = $this->db->escape($regionName);
			$cityName 	= $this->db->escape($cityName);
			$ccType 	= $this->db->escape($ccType);
			$ccName 	= $this->db->escape($ccName);
			
			$regionID = (int)$regionID;
			$cityID   = (int)$cityID;
			$ccID 	  = (int)$ccID;

			$query = $this->db->query("INSERT INTO " . DB_PREFIX . "avitoexport_locations  (RegionName,RegionID,CityID,CityName,CityChildID,CityChildType,CityChildName) VALUES ('$regionName','$regionID','$cityID','$cityName','$ccID','$ccType','$ccName')");
			return $query;
		}
	}
?>