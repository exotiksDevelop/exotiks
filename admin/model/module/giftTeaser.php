<?php 
class ModelModuleGiftTeaser extends Model {

	public function getProducts($data = array()) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` p 
				LEFT JOIN `" . DB_PREFIX . "product_description` AS pd ON (p.product_id = pd.product_id)
				JOIN `" . DB_PREFIX . "product_to_store` AS ps ON (p.product_id = ps.product_id)
				WHERE pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%' 
				AND  store_id=" . $data['store_id'] . "
				GROUP BY p.product_id
				ORDER BY pd.name ASC LIMIT 0, 10")->rows;
  	}
	
	public function getProductsByID($products = array()){
		
		$whereClause = '';
		if(!empty($products)) {
			$whereClause = "WHERE p.product_id IN (" . implode(',', $products) . ') AND language_id=' . $this->config->get('config_language_id');  
			return $this->db->query("SELECT * FROM " . DB_PREFIX ."product p JOIN "  . DB_PREFIX ."product_description pd on p.product_id=pd.product_id " . $whereClause)->rows;
		} else {
			return NULL;
		}
	}
	
	public function getCategories($data) {
		if (!empty($data['filter_name'])) {	
			$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order, cp.path_id, c.date_added 
					FROM " . DB_PREFIX . "category_path cp 
					LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) 
					LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) 
					LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id)
					WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' 
					AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'
					AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%' GROUP BY cp.category_id ORDER BY name LIMIT 0,20";
			return $this->db->query($sql)->rows;
		}
	}	
	public function getCategoriesByID($categories = array()){ 
		$whereClause = '';
		if(!empty($categories)) {
			$whereClause = "WHERE c.category_id IN (" . implode(',', $categories) . ') AND language_id=' . $this->config->get('config_language_id');  
			return $this->db->query("SELECT c.category_id, name FROM " . DB_PREFIX ."category c JOIN "  . DB_PREFIX ."category_description cd on c.category_id=cd.category_id " . $whereClause)->rows;
		} else {
			return NULL;
		}
	}
	
	public function getManufacturersByID($manufacturers = array()){ 
		$whereClause = '';
		if(!empty($manufacturers)) {
			$whereClause = "WHERE manufacturer_id IN (" . implode(',', $manufacturers) . ')';  
			return $this->db->query("SELECT manufacturer_id, name FROM " . DB_PREFIX ."manufacturer " . $whereClause)->rows;
		} else {
			return NULL;
		}
	}
	public function getGifts($data) {
		$sql =  "SELECT *, gt.sort_order AS sort_order
				 FROM `". DB_PREFIX . "gift_teaser` AS gt 
				 LEFT JOIN `" . DB_PREFIX . "product_description` pd ON gt.item_id=pd.product_id
				 LEFT JOIN `" . DB_PREFIX . "product` p ON gt.item_id=p.product_id
				 WHERE gt.store_id=".(int)$data['store_id'] . " 
				 AND pd.language_id=" . $this->config->get('config_language_id') . "
				 ORDER BY " . $this->db->escape($data['sort']) . " " . $this->db->escape($data['order']) . " 
				 LIMIT " . (int)$data['start'] .", " . (int)$data['limit']; 										
		
		
		return $this->db->query($sql)->rows;
  	}
  
   	public function saveGift($data = array()) { 
   		if($this->db->query("SELECT * FROM `" . DB_PREFIX . "gift_teaser` WHERE `gift_id`=" . (int)$data['gift_id'])->num_rows > 0) {
   			$this->updateGift($data);
   		} else {
   			$this->addGift($data);
   		}
   	} 
   		
 	public function addGift($data = array()) { 
		$this->db->query($sql = "INSERT INTO `". DB_PREFIX ."gift_teaser` 
						  SET `item_id`=" . (int)$data['item_id'] . ",
						  `start_date`=" . (int)strtotime($data['start_date']) . ",
						  `end_date`=" . (int)strtotime($data['end_date']) . ",
						  `condition_type`='" . (int)$data['type'] . "', 
						  `condition_properties`='" . $this->db->escape(serialize($data['properties'])) . "',
						  `description`='" . $this->db->escape(base64_encode(serialize($data['descriptions']))) ."',
						  `sort_order`=" . (int)$data['sort_order'] . ",
						  `store_id`=" . (int)$data['store_id']);

	}

 	public function updateGift($data = array()) { 
	
	  	$this->db->query("UPDATE `". DB_PREFIX ."gift_teaser` 
	  					  SET
	  					  `start_date`=" . (int)strtotime($data['start_date']) . ",
	  					  `end_date`=" . (int)strtotime($data['end_date']) . ",
	  					  `condition_type`=" . (int)$data['type'] . ", 
						  `condition_properties`='" . $this->db->escape(serialize($data['properties'])) . "',
	  					  `description`='" . $this->db->escape(base64_encode(serialize($data['descriptions']))) ."',
	  					  `sort_order`=" . (int)$data['sort_order'] . "
	  					  WHERE `gift_id`=" . (int)$data['gift_id']);
  	} 	
  	
  	public function removeGift($gift_id) {
  		$this->db->query("DELETE FROM `" . DB_PREFIX . "gift_teaser` WHERE `gift_id`=" . (int)$gift_id);
  	}
	
	public function getGift($gift_id){
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "gift_teaser` WHERE `gift_id`=" . (int)$gift_id)->row;
	}  
  	
  	public function deleteExpiredGifts(){
  		$this->db->query("DELETE FROM `" . DB_PREFIX . "gift_teaser` WHERE `end_date`<" . time());  
  	}
  
 	public function getTotalGifts($store_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS count FROM `" . DB_PREFIX . "gift_teaser` WHERE `store_id`=" . (int)$store_id);
	  	return $query->row['count'];
  	}
  	
	public function getMaxGiftId() {
		$maxID = $this->db->query("SELECT MAX(gift_id) AS m FROM `" . DB_PREFIX . "gift_teaser`")->row;		
		if(isset($maxID['m'])) {
			$maxID = $maxID['m'];
		} else {
			$maxID = 0;
		}
		return $maxID;
	}
  	 
  	public function install() {
	    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX ."gift_teaser` (
	                      `gift_id` int(11) NOT NULL AUTO_INCREMENT,
	                      `item_id` INT(11) NOT NULL, 
	                      `start_date` INT(11),
	                      `end_date` INT(11),
	                      `store_id` INT(11) NOT NULL,
	                      `condition_type` INT(11) NOT NULL,
	                      `condition_properties` TEXT,
	                      `description` TEXT, 
	                      `sort_order` INT, 
	                       PRIMARY KEY (`gift_id`))");
						   			   
  	} 
  
  	public function uninstall() {
  		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX ."gift_teaser`");
  	}
  	
  
  
}
?>