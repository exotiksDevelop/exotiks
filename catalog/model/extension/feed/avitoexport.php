<?php
	class ModelExtensionFeedAvitoexport extends Model {
		public function getProductsByCategoryId($category_id,$stock) {
			if($stock){
				$stock = "p.quantity > 0 AND ";
			} else {
				$stock = "";
			}
			$query = $this->db->query("SELECT DISTINCT p.product_id,p.model,p.price,p.image,pd.name,pd.description,p2c.category_id,cd.name AS category_name, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (p2c.category_id = cd.category_id) WHERE " . $stock . "pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.name ASC");
									
			return $query->rows;
		}
		
		public function getRegion($id){
			$query = $this->db->query("SELECT DISTINCT RegionName FROM " . DB_PREFIX . "avitoexport_locations WHERE RegionID = " . (int)$id);
			return $query->row['RegionName'];
		}

		public function getCity($id){
			$query = $this->db->query("SELECT DISTINCT CityName FROM " . DB_PREFIX . "avitoexport_locations WHERE CityID = " . (int)$id);
			return $query->row['CityName'];
		}
		
		public function getCityChild($id){
			$query = $this->db->query("SELECT DISTINCT CityChildName FROM " . DB_PREFIX . "avitoexport_locations WHERE CityChildID = " . (int)$id);
			return $query->row['CityChildName'];
		}
	}
?>