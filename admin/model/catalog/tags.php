<?php
class ModelCatalogTags extends Model {
	public function addTag($data) {
		$this->event->trigger('pre.admin.tag.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "tag SET status = '" . (int)$data['status'] . "'");

		$tag_id = $this->db->getLastId();

		foreach ($data['tag_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tag_description SET 
				tag_id = '" . (int)$tag_id . "', 
				language_id = '" . (int)$language_id . "', 
				name = '" . $this->db->escape($value['name']) . "', 
				name_short = '" . $this->db->escape($value['name_short']) . "', 
				description_top = '" . $this->db->escape($value['description_top']) . "',
				description_bottom = '" . $this->db->escape($value['description_bottom']) . "',
				h1 = '" . $this->db->escape($value['h1']) . "', 
				meta_title = '" . $this->db->escape($value['meta_title']) . "', 
				meta_description = '" . $this->db->escape($value['meta_description']) . "', 
				meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['tag_store'])) {
			foreach ($data['tag_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_store SET tag_id = '" . (int)$tag_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'tag_id=" . (int)$tag_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		/* if (isset($data['category_id'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_category SET tag_id = '" . (int)$tag_id . "', category_id = '" . $data['category_id'] . "'");
		} */
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_category SET tag_id = '" . (int)$tag_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->cache->delete('tag');

		$this->event->trigger('post.admin.tag.add', $tag_id);

		return $tag_id;
	}
	
	public function getCategoryTags($category_id) {
		$query = $this->db->query("SELECT *, IF(td.name_short IS NULL or td.name_short = '', td.name, td.name_short) as name
			FROM " . DB_PREFIX . "tag_to_category pc
			INNER JOIN " . DB_PREFIX . "tag t ON (t.tag_id = pc.tag_id)
			INNER JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id)
			INNER JOIN " . DB_PREFIX . "tag_to_store ts ON (pc.tag_id = ts.tag_id)
			WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND ts.store_id = '" . (int)$this->config->get('config_store_id') . "'
				AND t.status = '1'
				AND (pc.category_id = ".(int)$category_id.")
			group by t.tag_id");

		return $query->rows;
	}

	public function editTag($tag_id, $data) {
		$this->event->trigger('pre.admin.tag.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "tag SET 
			status = '" . (int)$data['status'] . "'
			WHERE tag_id = '" . (int)$tag_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_description WHERE tag_id = '" . (int)$tag_id . "'");

		foreach ($data['tag_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tag_description SET 
				tag_id = '" . (int)$tag_id . "', 
				language_id = '" . (int)$language_id . "', 
				name = '" . $this->db->escape($value['name']) . "', 
				name_short = '" . $this->db->escape($value['name_short']) . "', 
				description_top = '" . $this->db->escape($value['description_top']) . "',
				description_bottom = '" . $this->db->escape($value['description_bottom']) . "',
				h1 = '" . $this->db->escape($value['h1']) . "', 
				meta_title = '" . $this->db->escape($value['meta_title']) . "', 
				meta_description = '" . $this->db->escape($value['meta_description']) . "', 
				meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_to_store WHERE tag_id = '" . (int)$tag_id . "'");

		if (isset($data['tag_store'])) {
			foreach ($data['tag_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_store SET tag_id = '" . (int)$tag_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'tag_id=" . (int)$tag_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'tag_id=" . (int)$tag_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		/* $this->db->query("DELETE FROM " . DB_PREFIX . "tag_to_category WHERE tag_id = '" . (int)$tag_id . "'");
		
		if (isset($data['category_id'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_category SET tag_id = '" . (int)$tag_id . "', category_id = '" . $data['category_id'] . "'");
		} */
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_to_category WHERE tag_id = '" . (int)$tag_id . "'");

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tag_to_category SET tag_id = '" . (int)$tag_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->cache->delete('tag');

		$this->event->trigger('post.admin.tag.edit', $tag_id);
	}

	public function deleteTags($tag_id) {
		$this->event->trigger('pre.admin.tag.delete', $tag_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "tag WHERE tag_id = '" . (int)$tag_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_description WHERE tag_id = '" . (int)$tag_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_tag WHERE tag_id = '" . (int)$tag_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_to_store WHERE tag_id = '" . (int)$tag_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'tag_id=" . (int)$tag_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tag_to_category WHERE tag_id = '" . (int)$tag_id . "'");

		$this->cache->delete('tag');

		$this->event->trigger('post.admin.tag.delete', $tag_id);
	}

	public function getTag($tag_id) {
		$query = $this->db->query("SELECT DISTINCT *, 
			 						(SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'tag_id=" . (int)$tag_id . "') AS keyword
			 	FROM " . DB_PREFIX . "tag t 
			 	LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id) 
			 	WHERE t.tag_id = '" . (int)$tag_id . "' AND 
			 		td.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getTags($data = array()) {
		$sql = "SELECT *, (select count(*) from " . DB_PREFIX . "product_to_tag where tag_id = t.tag_id)   as count
				FROM " . DB_PREFIX . "tag t 
				LEFT JOIN " . DB_PREFIX . "tag_description td ON (t.tag_id = td.tag_id)  
				WHERE td.language_id = '" . (int)$this->config->get('config_language_id')."'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND td.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY t.tag_id";

		$sort_data = array(
			'td.name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY t.tag_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTagDescriptions($tag_id) {
		$tag_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tag_description WHERE tag_id = '" . (int)$tag_id . "'");

		foreach ($query->rows as $result) {
			$tag_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'name_short'             => $result['name_short'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description_top'      => $result['description_top'],
				'description_bottom'      => $result['description_bottom'],
				'h1'				=> $result['h1']
			);
		}

		return $tag_description_data;
	}

	public function getTagStores($tag_id) {
		$tag_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tag_to_store WHERE tag_id = '" . (int)$tag_id . "'");

		foreach ($query->rows as $result) {
			$tag_store_data[] = $result['store_id'];
		}

		return $tag_store_data;
	}
	
	public function getTagCategories($tag_id) {
		$tag_categ_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tag_to_category WHERE tag_id = '" . (int)$tag_id . "'");

		foreach ($query->rows as $result) {
			$tag_categ_data[] = $result['category_id'];
		}

		return $tag_categ_data;
	}

	public function getTotalTags() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tag");

		return $query->row['total'];
	}

	public function setSettings($data){ 
		$query = $this->db->query("UPDATE " . DB_PREFIX . "setting set value = '".$data['setting_etopd']."' where `key` = 'newtags_etopd'");
		$query = $this->db->query("UPDATE " . DB_PREFIX . "setting set value = '".$data['setting_ebottomd']."' where `key` = 'newtags_ebottomd'");
		$query = $this->db->query("UPDATE " . DB_PREFIX . "setting set value = '".$data['setting_only']."' where `key` = 'newtags_only'");
		$query = $this->db->query("UPDATE " . DB_PREFIX . "setting set value = '".$data['setting_ajax']."' where `key` = 'newtags_ajax'");
		$query = $this->db->query("UPDATE " . DB_PREFIX . "setting set value = '".$data['setting_scategory']."' where `key` = 'newtags_category'");
		$query = $this->db->query("UPDATE " . DB_PREFIX . "setting set value = '".$data['setting_count']."' where `key` = 'newtags_count'");
		$query = $this->db->query("UPDATE " . DB_PREFIX . "setting set value = '".$data['setting_related']."' where `key` = 'newtags_related'");
	}

	public function updateTags($data){
		$result = 0;
		if ($data['tag_id']){
			$sql = "select p.product_id 
					from " . DB_PREFIX . "product p
					INNER JOIN " . DB_PREFIX . "product_to_category pc on p.product_id = pc.product_id 
					LEFT JOIN " . DB_PREFIX . "product_attribute pa on p.product_id = pa.product_id 
					LEFT JOIN " . DB_PREFIX . "product_option_value pov on p.product_id = pov.product_id 
					WHERE 1=1 ";
			if ($data['category_id']){
				$sql .= " AND pc.category_id = ".$data['category_id'];
			}

			$attributes = array();
			$attributes_values = array();
			if (isset($data['filtera'])){
				foreach ($data['filtera'] as $key => $item) {
					if ($item != ''){
						if ($data['filterva'][$key] != ''){
							$attributes[] = $item;
							$attributes_values[] = "'".$data['filterva'][$key]."'";
						}
					}
				}
			}
			if ($attributes && $attributes_values){
				$attributes = implode(',', $attributes);
				$attributes_values = implode(',', $attributes_values);
				$sql .= " and pa.attribute_id in ($attributes) and pa.text in ($attributes_values)";
			}

			$options = array();
			$options_values = array();
			if (isset($data['filter'])){
				foreach ($data['filter'] as $key => $item) {
					if ($item != ''){
						if ($data['filterv'][$key] != ''){
							$options[] = $item;
							$options_values[] = $data['filterv'][$key];
						}
					}
				}
			}
			if ($options && $options_values){
				$options = implode(',', $options);
				$options_values = implode(',', $options_values);
				$sql .= " and pov.option_id in ($options) and pov.option_value_id in ($options_values)";
			}

			$sql .= " group by p.product_id";
			
			$query = $this->db->query($sql);
			foreach ($query->rows as $key => $row) {
				$sql = "select * from " . DB_PREFIX . "product_to_tag where product_id = ".$row['product_id']." and tag_id = ".$data['tag_id'];
				$q = $this->db->query($sql);
				if (!$q->num_rows){
					$sql = "insert into " . DB_PREFIX . "product_to_tag set product_id = ".$row['product_id'].", tag_id = ".$data['tag_id'];
					if ($this->db->query($sql))
						$result++;
				}
			}
			
		}

		return $result;
	}
}
