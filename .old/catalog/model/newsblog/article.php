<?php
class ModelNewsBlogArticle extends Model {
	public function updateViewed($article_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "newsblog_article SET viewed = (viewed + 1) WHERE article_id = '" . (int)$article_id . "'");
	}

	public function getArticleCategory($article_id) {
		$query = $this->db->query("SELECT ptc.category_id, ptc.article_id, c.image as image, d.name as category_name,
		(select price from " . DB_PREFIX . "newsblog_article where article_id='" . (int)$article_id . "') as price,
		(select name from " . DB_PREFIX . "newsblog_article_description where article_id='" . (int)$article_id . "') as name,
        (SELECT price FROM " . DB_PREFIX . "newsblog_article_special ps WHERE ps.article_id = '" . (int)$article_id . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special

		FROM " . DB_PREFIX . "newsblog_article_to_category ptc

		LEFT JOIN " . DB_PREFIX . "category c ON (c.category_id = ptc.category_id)
		LEFT JOIN " . DB_PREFIX . "category_description d ON (d.category_id = ptc.category_id)

		WHERE ptc.article_id = '" . (int)$article_id . "'");

		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image,
		p.sort_order

		FROM " . DB_PREFIX . "newsblog_article p
		LEFT JOIN " . DB_PREFIX . "newsblog_article_description pd ON (p.article_id = pd.article_id)
		LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store p2s ON (p.article_id = p2s.article_id)

		WHERE p.article_id = '" . (int)$article_id . "' AND
		pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND
		p.status = '1' AND
		p.date_available <= NOW() AND
		p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'article_id'       => $query->row['article_id'],
				'name'             => $query->row['name'],
				'preview'      	   => $query->row['preview'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_h1'          => $query->row['meta_h1'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'image'            => $query->row['image'],
				'date_available'   => $query->row['date_available'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
				'attributes'	   => $this->getArticleAttributes($article_id)
			);
		} else {
			return false;
		}
	}

	public function getArticles($data = array()) {
		$sql = "SELECT a.article_id";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "newsblog_category_path cp LEFT JOIN " . DB_PREFIX . "newsblog_article_to_category a2c ON (cp.category_id = a2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "newsblog_article_to_category a2c";
			}

				$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article a ON (a2c.article_id = a.article_id)";

		} else {
			$sql .= " FROM " . DB_PREFIX . "newsblog_article a";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article_description ad ON (a.article_id = ad.article_id)
		LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store a2s ON (a.article_id = a2s.article_id)

		WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND
		a.status = '1' AND
		a.date_available <= NOW() AND
		a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_date'])) {
			$sql .= " AND a.date_available LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}

		if (!empty($data['filter_tag'])) {
			$sql .= "AND ad.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
		}

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND a2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}

		$sql .= " GROUP BY a.article_id";

		if (isset($data['sort'])) {
			if ($data['sort'] == 'ad.name') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY a.sort_order";
		}

		if (isset($data['order'])) {
			$sql .= " ".$data['order'];
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$article_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$article_data[$result['article_id']] = $this->getArticle($result['article_id']);
		}

		return $article_data;
	}

	public function getArticleSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.article_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.article_id = ps.article_id AND r1.status = '1' GROUP BY r1.article_id) AS rating FROM " . DB_PREFIX . "newsblog_article_special ps LEFT JOIN " . DB_PREFIX . "newsblog_article p ON (ps.article_id = p.article_id) LEFT JOIN " . DB_PREFIX . "newsblog_article_description pd ON (p.article_id = pd.article_id) LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store p2s ON (p.article_id = p2s.article_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.article_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$article_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			//$article_data[$result['article_id']] = $this->getArticle($result['article_id']);
			$article_data[$result['article_id']] = $this->getArticleCategory($result['article_id']);
		}

		return $article_data;
	}

	public function getLatestarticles($limit) {
		$article_data = $this->cache->get('article.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$article_data) {
			$query = $this->db->query("SELECT p.article_id FROM " . DB_PREFIX . "newsblog_article p LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store p2s ON (p.article_id = p2s.article_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				//$article_data[$result['article_id']] = $this->getArticle($result['article_id']);
				$article_data[$result['article_id']] = $this->getArticleCategory($result['article_id']);
			}

			$this->cache->set('article.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $article_data);
		}

		return $article_data;
	}

	public function getPopulararticles($limit) {
		$article_data = array();

		$query = $this->db->query("SELECT p.article_id FROM " . DB_PREFIX . "newsblog_article p LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store p2s ON (p.article_id = p2s.article_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) {
			$article_data[$result['article_id']] = $this->getArticle($result['article_id']);
		}

		return $article_data;
	}

	public function getBestSellerarticles($limit) {
		$article_data = $this->cache->get('article.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$article_data) {
			$article_data = array();

			$query = $this->db->query("SELECT op.article_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_article op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "newsblog_article` p ON (op.article_id = p.article_id) LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store p2s ON (p.article_id = p2s.article_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.article_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				//$article_data[$result['article_id']] = $this->getArticle($result['article_id']);
				$article_data[$result['article_id']] = $this->getArticleCategory($result['article_id']);
			}

			$this->cache->set('article.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $article_data);
		}

		return $article_data;
	}

	public function getArticleAttributes($article_id) {
		$article_attribute_group_data = array();

		$article_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "newsblog_article_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.article_id = '" . (int)$article_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($article_attribute_group_query->rows as $article_attribute_group) {
			$article_attribute_data = array();

			$article_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "newsblog_article_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.article_id = '" . (int)$article_id . "' AND a.attribute_group_id = '" . (int)$article_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($article_attribute_query->rows as $article_attribute) {
				$article_attribute_data[] = array(
					'attribute_id' => $article_attribute['attribute_id'],
					'name'         => $article_attribute['name'],
					'text'         => $article_attribute['text']
				);
			}

			$article_attribute_group_data[] = array(
				'attribute_group_id' => $article_attribute_group['attribute_group_id'],
				'name'               => $article_attribute_group['name'],
				'attribute'          => $article_attribute_data
			);
		}

		return $article_attribute_group_data;
	}

	public function getArticleOptions($article_id) {
		$article_option_data = array();

		$article_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.article_id = '" . (int)$article_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($article_option_query->rows as $article_option) {
			$article_option_value_data = array();

			$article_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.article_id = '" . (int)$article_id . "' AND pov.article_option_id = '" . (int)$article_option['article_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($article_option_value_query->rows as $article_option_value) {
				$article_option_value_data[] = array(
					'article_option_value_id' => $article_option_value['article_option_value_id'],
					'option_value_id'         => $article_option_value['option_value_id'],
					'name'                    => $article_option_value['name'],
					'image'                   => $article_option_value['image'],
					'quantity'                => $article_option_value['quantity'],
					'subtract'                => $article_option_value['subtract'],
					'price'                   => $article_option_value['price'],
					'price_prefix'            => $article_option_value['price_prefix'],
					'weight'                  => $article_option_value['weight'],
					'weight_prefix'           => $article_option_value['weight_prefix']
				);
			}

			$article_option_data[] = array(
				'article_option_id'    => $article_option['article_option_id'],
				'article_option_value' => $article_option_value_data,
				'option_id'            => $article_option['option_id'],
				'name'                 => $article_option['name'],
				'type'                 => $article_option['type'],
				'value'                => $article_option['value'],
				'required'             => $article_option['required']
			);
		}

		return $article_option_data;
	}

	public function getArticleDiscounts($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_discount WHERE article_id = '" . (int)$article_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getArticleImages($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_image WHERE article_id = '" . (int)$article_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getArticleRelated($article_id) {
		$article_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_related pr
		LEFT JOIN " . DB_PREFIX . "newsblog_article p ON (pr.related_id = p.article_id)
		LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store p2s ON (p.article_id = p2s.article_id)
		WHERE pr.article_id = '" . (int)$article_id . "' AND pr.type=1 AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$article_data[$result['related_id']] = $this->getArticle($result['related_id']);
		}

		return $article_data;
	}

	public function getArticleRelatedProducts($article_id) {
		$product_data = array();

		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "newsblog_article_related pr
		LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id)
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
		WHERE pr.article_id = '" . (int)$article_id . "' AND pr.type=2 AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getArticleLayoutId($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_to_layout WHERE article_id = '" . (int)$article_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsblog_article_to_category WHERE article_id = '" . (int)$article_id . "'");

		return $query->rows;
	}

	public function getTotalArticles($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.article_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "newsblog_article_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "newsblog_article_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article_filter pf ON (p2c.article_id = pf.article_id) LEFT JOIN " . DB_PREFIX . "newsblog_article p ON (pf.article_id = p.article_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article p ON (p2c.article_id = p.article_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "newsblog_article p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article_description pd ON (p.article_id = pd.article_id) LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store p2s ON (p.article_id = p2s.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_date'])) {
			$sql .= " AND p.date_available LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			$sql .= ")";
		}


		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfile($article_id, $recurring_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "newsblog_article_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`article_id` = " . (int)$article_id . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get('config_customer_group_id'))->row;
	}

	public function getProfiles($article_id) {
		return $this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "newsblog_article_recurring` `pp` JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`recurring_id` = `pp`.`recurring_id` JOIN `" . DB_PREFIX . "recurring` `p` ON `p`.`recurring_id` = `pd`.`recurring_id` WHERE `article_id` = " . (int)$article_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$this->config->get('config_customer_group_id') . " ORDER BY `sort_order` ASC")->rows;
	}

	public function getTotalarticleSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.article_id) AS total FROM " . DB_PREFIX . "newsblog_article_special ps LEFT JOIN " . DB_PREFIX . "newsblog_article p ON (ps.article_id = p.article_id) LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store p2s ON (p.article_id = p2s.article_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getArticleMainCategoryId($article_id) {
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "newsblog_article_to_category WHERE article_id = '" . (int)$article_id . "' order by main_category desc LIMIT 1");

		return ($query->num_rows ? (int)$query->row['category_id'] : 0);
	}

	public function getArticlesTags($data = array()) {
		$sql = "SELECT ad.tag";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "newsblog_category_path cp LEFT JOIN " . DB_PREFIX . "newsblog_article_to_category a2c ON (cp.category_id = a2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "newsblog_article_to_category a2c";
			}

				$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article a ON (a2c.article_id = a.article_id)";

		} else {
			$sql .= " FROM " . DB_PREFIX . "newsblog_article a";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article_description ad ON (a.article_id = ad.article_id)
		LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store a2s ON (a.article_id = a2s.article_id)

		WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND
		a.status = '1' AND
		a.date_available <= NOW() AND
		a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_date'])) {
			//$sql .= " AND a.date_available LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}

		if (!empty($data['filter_tag'])) {
			//$sql .= "AND ad.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
		}

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND a2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}


		$articletags_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$tmp=explode(',',$result['tag']);

			for ($i=0;$i<count($tmp);$i++) {				$w=trim($tmp[$i]);
				if ($w)
				@$articletags_data[$w]++;
			}
		}

		return $articletags_data;
	}

	public function getArticlesDates($data = array()) {
		$sql = "SELECT a.date_available";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "newsblog_category_path cp LEFT JOIN " . DB_PREFIX . "newsblog_article_to_category a2c ON (cp.category_id = a2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "newsblog_article_to_category a2c";
			}

				$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article a ON (a2c.article_id = a.article_id)";

		} else {
			$sql .= " FROM " . DB_PREFIX . "newsblog_article a";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "newsblog_article_description ad ON (a.article_id = ad.article_id)
		LEFT JOIN " . DB_PREFIX . "newsblog_article_to_store a2s ON (a.article_id = a2s.article_id)

		WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND
		a.status = '1' AND
		a.date_available <= NOW() AND
		a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_date'])) {
			//$sql .= " AND a.date_available LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
		}

		if (!empty($data['filter_tag'])) {
			//$sql .= " AND ad.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
		}

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND a2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}


		$articledates_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			preg_match("/([0-9]{4}-[0-9]{2})/",$result['date_available'],$a);

			@$articledates_data[$a[1]]++;
		}

		return $articledates_data;
	}
}
