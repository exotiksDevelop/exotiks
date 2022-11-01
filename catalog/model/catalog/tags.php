<?php
class ModelCatalogTags extends Model {
	public function getTag($tag_id) {
		$query = $this->db->query("SELECT DISTINCT *, (select category_id from " . DB_PREFIX . "tag_to_category where tag_id = t.tag_id) as category_id 
			FROM " . DB_PREFIX . "tag t 
			INNER JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) 
			LEFT JOIN " . DB_PREFIX . "tag_to_store ts ON (t.tag_id = ts.tag_id) 
			WHERE t.tag_id = '" . (int)$tag_id . "' 
				AND td.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND ts.store_id = '" . (int)$this->config->get('config_store_id') . "' 
				AND t.status = '1'");

		return $query->row;
	}

	public function getProductTags($product_id) {
		$query = $this->db->query("SELECT *, IF(td.name_short IS NULL or td.name_short = '', td.name, td.name_short) as name,
			(select category_id from " . DB_PREFIX . "tag_to_category where tag_id = t.tag_id) as category_id,
			(select count(*) from " . DB_PREFIX . "product_to_tag where tag_id = pt.tag_id) as kol  
			FROM " . DB_PREFIX . "product_to_tag pt 
			INNER JOIN " . DB_PREFIX . "tag t ON (t.tag_id = pt.tag_id) 
			INNER JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) 
			INNER JOIN " . DB_PREFIX . "tag_to_store ts ON (pt.tag_id = ts.tag_id) 
			WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND ts.store_id = '" . (int)$this->config->get('config_store_id') . "'  
				AND t.status = '1'
				AND pt.product_id = ".(int)$product_id." 
			group by t.tag_id ORDER BY kol desc");

		return $query->rows;
	}

	public function getTags($data = array()){
		$sql = "SELECT *, IF(td.name_short IS NULL or td.name_short = '', td.name, td.name_short) as name, 
			(select count(*) from " . DB_PREFIX . "product_to_tag where tag_id = t.tag_id) as count,
			(select category_id from " . DB_PREFIX . "tag_to_category where tag_id = t.tag_id) as category_id 
			FROM " . DB_PREFIX . "tag t 
			LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) 
			LEFT JOIN " . DB_PREFIX . "tag_to_store t2s ON (t.tag_id = t2s.tag_id) 
			WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND 
			t2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND 
			t.status = '1' ORDER BY LCASE(td.name)";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCloudTags($data = array()){
		$tags = false;
		if (isset($data['category_id'])){
			$sql = "SELECT a.tag_id 
					FROM " . DB_PREFIX . "product_to_tag a
					INNER JOIN " . DB_PREFIX . "product p on a.product_id = p.product_id
					INNER JOIN " . DB_PREFIX . "product_to_category pc on p.product_id = pc.product_id
					WHERE pc.category_id = ".$data['category_id']."
					GROUP BY a.tag_id";
			$query = $this->db->query($sql);
			foreach ($query->rows as $key => $row) {
				$tags[] = $row['tag_id'];
			}
			if ($tags)
				$tags = implode(",", $tags);
		}
		$sql = "SELECT *, IF(td.name_short IS NULL or td.name_short = '', td.name, td.name_short) as name, (select category_id from " . DB_PREFIX . "tag_to_category where tag_id = t.tag_id) as category_id, (select count(*) from " . DB_PREFIX . "product_to_tag where tag_id = t.tag_id) as count 
			FROM " . DB_PREFIX . "tag t 
			LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) 
			LEFT JOIN " . DB_PREFIX . "tag_to_store t2s ON (t.tag_id = t2s.tag_id) 
			WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND 
			t2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND 
			t.status = '1'"; 
		
		if (isset($data['category_id'])){
			if ($tags)
				$sql .= " AND t.tag_id in ($tags)";
			else
				return array();
		}

		$sql .= " ORDER BY count LIMIT 0,15";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCategoryTags($category_id) {
		$query = $this->db->query("SELECT *, IF(td.name_short IS NULL or td.name_short = '', td.name, td.name_short) as name,
			(select count(*) from " . DB_PREFIX . "product_to_tag where tag_id = t.tag_id) as kol
			FROM " . DB_PREFIX . "tag_to_category pc
			INNER JOIN " . DB_PREFIX . "tag t ON (t.tag_id = pc.tag_id)
			INNER JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) 
			INNER JOIN " . DB_PREFIX . "tag_to_store ts ON (pc.tag_id = ts.tag_id)
			WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND ts.store_id = '" . (int)$this->config->get('config_store_id') . "'  
				AND t.status = '1'
				AND (pc.category_id = ".(int)$category_id.")
			group by t.tag_id ORDER BY kol desc");

		return $query->rows;
	}

	public function getRelatedTags($tag_id) {
		/*$sql = "select tag_id 
				from " . DB_PREFIX . "product_to_tag
				WHERE product_id in (select product_id from " . DB_PREFIX . "product_to_tag where tag_id = $tag_id) and tag_id != $tag_id group by tag_id";*/
		$sql = "select tag_id from " . DB_PREFIX . "tag_to_category WHERE category_id in (select category_id from " . DB_PREFIX . "tag_to_category WHERE tag_id = $tag_id) and tag_id != $tag_id group by tag_id";
		$query = $this->db->query($sql);
		$tags = '0';
		if ($query->rows){
			$tags = array();
			foreach ($query->rows as $key => $row) {
				$tags[] = $row['tag_id'];
			}
			$tags = implode(',', $tags);
		}	
		$query = $this->db->query("SELECT *, IF(td.name_short IS NULL or td.name_short = '', td.name, td.name_short) as name, 
			(select category_id from " . DB_PREFIX . "tag_to_category where tag_id = t.tag_id) as category_id,
			(select count(*) from " . DB_PREFIX . "product_to_tag where tag_id = t.tag_id) as kol 
			FROM " . DB_PREFIX . "tag t
			INNER JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) 
			INNER JOIN " . DB_PREFIX . "tag_to_store ts ON (t.tag_id = ts.tag_id) 
			WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND ts.store_id = '" . (int)$this->config->get('config_store_id') . "'  
				AND t.status = '1'
				AND t.tag_id in ($tags)
			group by t.tag_id ORDER BY kol desc");

		return $query->rows;
	}
}