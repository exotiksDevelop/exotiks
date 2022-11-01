<?php
class ModelNewsBlogArticle extends Model {
	public function addArticle($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article SET date_available = '" . $this->db->escape($data['date_available']) . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$article_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "newsblog_article SET image = '" . $this->db->escape($data['image']) . "' WHERE article_id = '" . (int)$article_id . "'");
		}

		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', preview = '" . $this->db->escape($value['preview']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_to_store SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['article_attribute'])) {
			foreach ($data['article_attribute'] as $article_attribute) {
				if ($article_attribute['attribute_id']) {
					foreach ($article_attribute['article_attribute_description'] as $language_id => $article_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_attribute SET article_id = '" . (int)$article_id . "', attribute_id = '" . (int)$article_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($article_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['article_image'])) {
			foreach ($data['article_image'] as $article_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_image SET article_id = '" . (int)$article_id . "', image = '" . $this->db->escape($article_image['image']) . "', sort_order = '" . (int)$article_image['sort_order'] . "'");
			}
		}

		if (isset($data['article_category'])) {
			foreach ($data['article_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_to_category SET article_id = '" . (int)$article_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if(isset($data['main_category_id']) && $data['main_category_id'] > 0) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_to_category WHERE article_id = '" . (int)$article_id . "' AND category_id = '" . (int)$data['main_category_id'] . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_to_category SET article_id = '" . (int)$article_id . "', category_id = '" . (int)$data['main_category_id'] . "', main_category = 1");
		} elseif(isset($data['article_category'][0])) {
			$this->db->query("UPDATE " . DB_PREFIX . "newsblog_article_to_category SET main_category = 1 WHERE article_id = '" . (int)$article_id . "' AND category_id = '" . (int)$data['article_category'][0] . "'");
		}

		if (isset($data['article_related'])) {
			foreach ($data['article_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$article_id . "' AND related_id = '" . (int)$related_id . "' AND type=1");
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_related SET article_id = '" . (int)$article_id . "', related_id = '" . (int)$related_id . "', type=1");
				$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$related_id . "' AND related_id = '" . (int)$article_id . "' AND type=1");
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_related SET article_id = '" . (int)$related_id . "', related_id = '" . (int)$article_id . "', type=1");
			}
		}

		if (isset($data['article_related_products'])) {
			foreach ($data['article_related_products'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$article_id . "' AND related_id = '" . (int)$related_id . "' AND type=2");
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_related SET article_id = '" . (int)$article_id . "', related_id = '" . (int)$related_id . "', type=2");
				$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$related_id . "' AND related_id = '" . (int)$article_id . "' AND type=2");
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_related SET article_id = '" . (int)$related_id . "', related_id = '" . (int)$article_id . "', type=2");
			}
		}

		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_to_layout SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'newsblog_article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('article');

		return $article_id;

	}

	public function editArticle($article_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "newsblog_article SET date_available = '" . $this->db->escape($data['date_available']) . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE article_id = '" . (int)$article_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "newsblog_article SET image = '" . $this->db->escape($data['image']) . "' WHERE article_id = '" . (int)$article_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_description WHERE article_id = '" . (int)$article_id . "'");

		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', preview = '" . $this->db->escape($value['preview']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_to_store WHERE article_id = '" . (int)$article_id . "'");

		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_to_store SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_attribute WHERE article_id = '" . (int)$article_id . "'");

		if (!empty($data['article_attribute'])) {
			foreach ($data['article_attribute'] as $article_attribute) {
				if ($article_attribute['attribute_id']) {
					foreach ($article_attribute['article_attribute_description'] as $language_id => $article_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_attribute SET article_id = '" . (int)$article_id . "', attribute_id = '" . (int)$article_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($article_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_image WHERE article_id = '" . (int)$article_id . "'");

		if (isset($data['article_image'])) {
			foreach ($data['article_image'] as $article_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_image SET article_id = '" . (int)$article_id . "', image = '" . $this->db->escape($article_image['image']) . "', sort_order = '" . (int)$article_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_to_category WHERE article_id = '" . (int)$article_id . "'");

		if (isset($data['article_category'])) {
			foreach ($data['article_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_to_category SET article_id = '" . (int)$article_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if(isset($data['main_category_id']) && $data['main_category_id'] > 0) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_to_category WHERE article_id = '" . (int)$article_id . "' AND category_id = '" . (int)$data['main_category_id'] . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_to_category SET article_id = '" . (int)$article_id . "', category_id = '" . (int)$data['main_category_id'] . "', main_category = 1");
		} elseif(isset($data['article_category'][0])) {
			$this->db->query("UPDATE " . DB_PREFIX . "newsblog_article_to_category SET main_category = 1 WHERE article_id = '" . (int)$article_id . "' AND category_id = '" . (int)$data['article_category'][0] . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE related_id = '" . (int)$article_id . "'");

		if (isset($data['article_related'])) {
			foreach ($data['article_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$article_id . "' AND related_id = '" . (int)$related_id . "' AND type=1");
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_related SET article_id = '" . (int)$article_id . "', related_id = '" . (int)$related_id . "', type=1");
				$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$related_id . "' AND related_id = '" . (int)$article_id . "' AND type=1");
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_related SET article_id = '" . (int)$related_id . "', related_id = '" . (int)$article_id . "', type=1");
			}
		}

		if (isset($data['article_related_products'])) {
			foreach ($data['article_related_products'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$article_id . "' AND related_id = '" . (int)$related_id . "' AND type=2");
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_related SET article_id = '" . (int)$article_id . "', related_id = '" . (int)$related_id . "', type=2");
				$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$related_id . "' AND related_id = '" . (int)$article_id . "' AND type=2");
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_related SET article_id = '" . (int)$related_id . "', related_id = '" . (int)$article_id . "', type=2");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_to_layout WHERE article_id = '" . (int)$article_id . "'");

		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsblog_article_to_layout SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'newsblog_article_id=" . (int)$article_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'newsblog_article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('article');

	}

	public function copyarticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "newsblog_article p
		LEFT JOIN " . DB_PREFIX . "newsblog_article_description pd ON (p.article_id = pd.article_id)
		WHERE p.article_id = '" . (int)$article_id . "' AND
		pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['article_attribute'] = $this->getArticleAttributes($article_id);
			$data['article_description'] = $this->getArticleDescriptions($article_id);
			$data['article_image'] = $this->getArticleImages($article_id);
			$data['article_related'] = $this->getArticleRelated($article_id);
			$data['article_related_products'] = $this->getArticleRelatedProducts($article_id);
			$data['article_category'] = $this->getArticleCategories($article_id);
			$data['article_layout'] = $this->getArticleLayouts($article_id);
			$data['article_store'] = $this->getArticleStores($article_id);
			$data['main_category_id'] = $this->getArticleMainCategoryId($article_id);

			$this->addArticle($data);
		}
	}

	public function deletearticle($article_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_attribute WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_description WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_image WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_related WHERE related_id = '" . (int)$article_id . "' and type=1");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_to_category WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_to_layout WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsblog_article_to_store WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'newsblog_article_id=" . (int)$article_id . "'");

		$this->cache->delete('article');

	}

	public function getArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT *,
		(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'newsblog_article_id=" . (int)$article_id . "') AS keyword

		FROM " . DB_PREFIX . "newsblog_article p
		LEFT JOIN " . DB_PREFIX . "newsblog_article_description pd ON (p.article_id = pd.article_id)

		WHERE p.article_id = '" . (int)$article_id . "' AND
		pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getArticles($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "newsblog_article p LEFT JOIN " . DB_PREFIX . "newsblog_article_description pd ON (p.article_id = pd.article_id)";

		if (!empty($data['filter_category'])) {
        	$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article_to_category p2c ON (p.article_id = p2c.article_id)";
		}

			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";


		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}


    if (!empty($data['filter_category'])) {
			if (!empty($data['filter_sub_category'])) {
				$implode_data = array();

				$implode_data[] = "category_id = '" . (int)$data['filter_category'] . "'";

				$this->load->model('catalog/category');

				$categories = $this->model_catalog_category->getCategories($data['filter_category']);

				foreach ($categories as $category) {
					$implode_data[] = "p2c.category_id = '" . (int)$category['category_id'] . "'";
				}

				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
			}
		}

		$sql .= " GROUP BY p.article_id";

		$sort_data = array(
			'pd.name',
			'p.status',
			'p.date_available',
			'p.date_modified',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

	public function getArticlesByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article p LEFT JOIN " . DB_PREFIX . "newsblog_article_description pd ON (p.article_id = pd.article_id) LEFT JOIN " . DB_PREFIX . "newsblog_article_to_category p2c ON (p.article_id = p2c.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getArticleDescriptions($article_id) {
		$article_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_description WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'preview'      	   => $result['preview'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $article_description_data;
	}

	public function getArticleCategories($article_id) {
		$article_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_to_category WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_category_data[] = $result['category_id'];
		}

		return $article_category_data;
	}

	public function getArticleAttributes($article_id) {
		$article_attribute_data = array();

		$article_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "newsblog_article_attribute WHERE article_id = '" . (int)$article_id . "' GROUP BY attribute_id");

		foreach ($article_attribute_query->rows as $article_attribute) {
			$article_attribute_description_data = array();

			$article_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_attribute WHERE article_id = '" . (int)$article_id . "' AND attribute_id = '" . (int)$article_attribute['attribute_id'] . "'");

			foreach ($article_attribute_description_query->rows as $article_attribute_description) {
				$article_attribute_description_data[$article_attribute_description['language_id']] = array('text' => $article_attribute_description['text']);
			}

			$article_attribute_data[] = array(
				'attribute_id'                  => $article_attribute['attribute_id'],
				'article_attribute_description' => $article_attribute_description_data
			);
		}

		return $article_attribute_data;
	}

	public function getArticleImages($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_image WHERE article_id = '" . (int)$article_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getArticleStores($article_id) {
		$article_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_to_store WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_store_data[] = $result['store_id'];
		}

		return $article_store_data;
	}

	public function getArticleLayouts($article_id) {
		$article_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_to_layout WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $article_layout_data;
	}

	public function getArticleMainCategoryId($article_id) {
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "newsblog_article_to_category WHERE article_id = '" . (int)$article_id . "' AND main_category = '1' LIMIT 1");

		return ($query->num_rows ? (int)$query->row['category_id'] : 0);
	}

	public function getArticleRelated($article_id) {
		$article_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$article_id . "' and type = 1");

		foreach ($query->rows as $result) {
			$article_related_data[] = $result['related_id'];
		}

		return $article_related_data;
	}

	public function getArticleRelatedProducts($article_id) {
		$article_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_related WHERE article_id = '" . (int)$article_id . "' and type = 2");

		foreach ($query->rows as $result) {
			$article_related_data[] = $result['related_id'];
		}

		return $article_related_data;
	}

	public function getTotalArticles($data = array()) {
	    $sql = "SELECT COUNT(DISTINCT p.article_id) AS total FROM " . DB_PREFIX . "newsblog_article p LEFT JOIN " . DB_PREFIX . "newsblog_article_description pd ON (p.article_id = pd.article_id) LEFT JOIN " . DB_PREFIX . "newsblog_article_to_category p2c ON (p.article_id = p2c.article_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalArticlesByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsblog_article_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalArticlesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsblog_article_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
