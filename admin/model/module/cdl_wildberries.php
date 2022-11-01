<?php
/*
* OpenCart 2.1
* @author Dmitri Cheremisin
*/
class ModelModuleCdlWildberries extends Model
{
  // UPDATE 160 ++
  public function checkStat()
  {
    $query = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . DB_PREFIX . "cdl_wildberries_orders'");
    return $query->rows;
  }

  public function updateCdlStat()
  {
    $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "cdl_wildberries_orders ADD stat tinyint(1) NOT NULL AFTER rid");
  }
  // UPDATE 160 --

  // UPDATE 150 ++
  public function checkCdlRid()
  {
    $query = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . DB_PREFIX . "cdl_wildberries_orders'");
    return $query->rows;
  }

  public function updateCdlRid()
  {
    $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "cdl_wildberries_orders ADD rid VARCHAR(64) NOT NULL AFTER sticker_bc");
  }
  // UPDATE 150 --

  // UPDATE 140 ++
  public function checkCdl()
  {
    $query = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . DB_PREFIX . "cdl_wildberries_attributes_to_shop'");
    return $query->rows;
  }
  public function updateCdl()
  {
    $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "cdl_wildberries_attributes_to_shop ADD shop_category INT(11) NOT NULL AFTER sub_category");
    $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "cdl_wildberries_attributes_to_shop DROP PRIMARY KEY, ADD PRIMARY KEY (sub_category, type, shop_category) USING BTREE");
    $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary ADD shop_category INT(11) NOT NULL AFTER sub_category, ADD wb_attr_name VARCHAR(128) NOT NULL AFTER shop_category");
  }
  public function updateCdlAttr($sub_category, $shop_category)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_attributes_to_shop SET shop_category = '" . (int)($shop_category) . "' WHERE sub_category = '" . $this->db->escape($sub_category) . "'");
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary SET shop_category = '" . (int)($shop_category) . "' WHERE sub_category = '" . $this->db->escape($sub_category) . "'");
  }
  public function updateCdlTsd($sub_category, $shop_category, $shop_attribute_id, $wb_attr_name)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary SET wb_attr_name = '" . $this->db->escape($wb_attr_name) . "' WHERE sub_category = '" . $this->db->escape($sub_category) . "' AND shop_category = '" . (int)($shop_category) . "' AND shop_attribute_id = '" . $this->db->escape($shop_attribute_id) . "'");
  }
  public function updateCdlDToS($sub_category, $shop_category, $shop_id)
  {
		$query = $this->db->query("SELECT shop_attribute_id FROM " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary WHERE sub_category = '" . $sub_category . "' AND shop_category = '" . (int)$shop_category . "' AND shop_attribute_id = '" . (int)$shop_id . "'");
		return $query->rows;
	}
  public function updateCdlDelNull()
  {
    $query = $this->db->query("DELETE FROM " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary WHERE wb_attr_name = ''");
  }
  // UPDATE 140 --

  public function getCategoryShop()
  {
    $category_export = $this->db->query("SELECT DISTINCT category_shop FROM " . DB_PREFIX . "cdl_wildberries_products");
    $categorys = array();
    if (!empty($category_export->rows)) {
      foreach ($category_export->rows as $category) {
        $query = $this->db->query("SELECT name FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category['category_shop'] . "'");
        if (!empty($query->rows)) {
          $categorys[$category['category_shop']] = $query->rows[0]['name'];
        }
      }
    }
    return $categorys;
  }

  public function searchWbCategory($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "cdl_wildberries_category";
		if (!empty($data['filter_name'])) {
			$sql .= " WHERE sub_category LIKE '%" . $this->db->escape($data['filter_name']) . "%' OR sub_category LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		$sort_data = array(
			'category'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY category";
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

  public function getAttrWbBySubCategory($sub_category)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_attributes WHERE sub_category = '" . $this->db->escape($sub_category) . "' ORDER BY required DESC, type ASC");
    return $query->rows;
  }

  public function attributeDelete($sub_category)
  {
    $query = $this->db->query("DELETE FROM " . DB_PREFIX . "cdl_wildberries_attributes WHERE sub_category = '" . $this->db->escape($sub_category) . "'");
  }

  public function deleteAttributesToShop($sub_category, $shop_category)
  {
    $query = $this->db->query("DELETE FROM " . DB_PREFIX . "cdl_wildberries_attributes_to_shop WHERE sub_category = '" . $this->db->escape($sub_category) . "' AND shop_category = '" . (int)$shop_category . "'");
  }

  public function saveAttributesToShop($sub_category, $shop_category, $type, $shop_id, $is_defined, $value, $nomenclature, $nomenclature_variation, $number, $required, $id, $units, $dictionary, $only_dictionary, $action, $action_value)
  {
    $query = $this->db->query("INSERT INTO " . DB_PREFIX . "cdl_wildberries_attributes_to_shop SET sub_category = '" . $this->db->escape($sub_category) . "', shop_category = '" . (int)$shop_category . "', type = '" . $this->db->escape($type) . "', shop_id = '" . $this->db->escape($shop_id) . "', is_defined = '" . $this->db->escape($is_defined) . "', value = '" . $this->db->escape($value) . "', nomenclature = '" . $this->db->escape($nomenclature) . "', nomenclature_variation = '" . $this->db->escape($nomenclature_variation) . "', number = '" . $this->db->escape($number) . "', required = '" . $this->db->escape($required) . "', id = '" . $this->db->escape($id) . "', units = '" . $this->db->escape($units) . "', dictionary = '" . $this->db->escape($dictionary) . "', only_dictionary = '" . $this->db->escape($only_dictionary) . "', action = '" . $this->db->escape($action) . "', action_value = '" . $this->db->escape($action_value) . "'");
  }

  public function getAttributesToShop($sub_category, $shop_category)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_attributes_to_shop WHERE sub_category = '" . $this->db->escape($sub_category) . "' AND shop_category = '" . (int)$shop_category . "'");
    return $query->rows;
  }

  public function getAttributesByType($type, $sub_category)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_attributes WHERE type = '" . $this->db->escape($type) . "' AND sub_category = '" . $this->db->escape($sub_category) . "'");
    return $query->rows;
  }

  public function getShopDictionary($shop_id, $shop_category)
  {
    $query = $this->db->query("SELECT DISTINCT text FROM " . DB_PREFIX . "product_to_category c LEFT JOIN " . DB_PREFIX . "product_attribute a ON (c.product_id = a.product_id) WHERE c.category_id = '" . (int)$shop_category . "' AND a.attribute_id = '" . (int)$shop_id . "' ORDER BY text ASC");
    return $query->rows;
	}

  public function saveDictionary($sub_category, $shop_category, $shop_attribute_id, $dictionary_value, $text_shop_attribute, $type) {
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary SET sub_category = '" . $this->db->escape($sub_category) . "', shop_category = '" . (int)$shop_category . "', shop_attribute_id = '" . (int)$shop_attribute_id . "', dictionary_value = '" . $dictionary_value . "', text_shop_attribute = '" . $this->db->escape($text_shop_attribute) . "', wb_attr_name = '" . $this->db->escape($type) . "'");
	}

  public function deleteSaveDictionary($sub_category, $shop_category, $shop_attribute_id, $type)
  {
    $query = $this->db->query("DELETE FROM " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary WHERE sub_category = '" . $this->db->escape($sub_category) . "' AND shop_category = '" . (int)$shop_category . "' AND shop_attribute_id = '" . (int)$shop_attribute_id . "' AND wb_attr_name = '" . $this->db->escape($type) . "'");
  }

  public function getDictionaryToShop($sub_category, $shop_category, $shop_id, $type)
  {
		$query = $this->db->query("SELECT dictionary_value, text_shop_attribute FROM " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary WHERE sub_category = '" . $sub_category . "' AND shop_category = '" . (int)$shop_category . "' AND shop_attribute_id = '" . (int)$shop_id . "' AND wb_attr_name = '" . $this->db->escape($type) . "'");
		return $query->rows;
	}

  public function deleteManufacture()
  {
    $query = $this->db->query("TRUNCATE " . DB_PREFIX . "cdl_wildberries_manufacturer");
  }

  public function saveManufacture($shop_id, $dictionary_value, $country)
  {
    $query = $this->db->query("INSERT INTO " . DB_PREFIX . "cdl_wildberries_manufacturer SET shop_id = '" . (int)$shop_id . "', dictionary_value = '" . $this->db->escape($dictionary_value) . "', country = '" . $this->db->escape($country) . "'");
	}

  public function getManufacture()
  {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_manufacturer");
		return $query->rows;
	}

  public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "cdl_wildberries_products p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (!empty($data['filter_barcode'])) {
			$sql .= " AND p.barcode = '" . $this->db->escape($data['filter_barcode']) . "'";
		}
		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
		if (!empty($data['filter_sku'])) {
			$sql .= " AND p.sku LIKE '" . $this->db->escape($data['filter_sku']) . "%'";
		}
		if (!empty($data['filter_nm'])) {
			$sql .= " AND p.nm_id LIKE '" . (int)$data['filter_nm'] . "%'";
		}
		if (!empty($data['filter_status'])) {
			$sql .= " AND p.status = '" . $this->db->escape($data['filter_status']) . "'";
		}
    if (!empty($data['filter_category'])) {
			$sql .= " AND p.category_shop = '" . (int)$data['filter_category'] . "'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "cdl_wildberries_products p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
    if (!empty($data['filter_barcode'])) {
			$sql .= " AND p.barcode = '" . $this->db->escape($data['filter_barcode']) . "'";
		}
		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
		if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
			$sql .= " AND p.sku LIKE '" . $this->db->escape($data['filter_sku']) . "%'";
		}
		if (isset($data['filter_nm']) && !is_null($data['filter_nm'])) {
			$sql .= " AND p.nm_id LIKE '" . (int)$data['filter_nm'] . "%'";
		}
		if (!empty($data['filter_status'])) {
			$sql .= " AND p.status = '" . $this->db->escape($data['filter_status']) . "'";
		}
    if (!empty($data['filter_category'])) {
			$sql .= " AND p.category_shop = '" . (int)$data['filter_category'] . "'";
		}
		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.sku',
			'p.imt_id',
			'p.nm_id',
			'p.model',
			'p.status',
      'p.category_shop',
			'p.date',
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

  public function deleteNoCreate()
  {
    $query = $this->db->query("DELETE FROM " . DB_PREFIX . "cdl_wildberries_products WHERE status = 'Не создан'");
  }

  public function getLanguage($code)
  {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE code = '" . $code . "'");
		return $query->row['language_id'];
	}

  public function getCategoryBySub($sub_category)
  {
    $query = $this->db->query("SELECT category FROM " . DB_PREFIX . "cdl_wildberries_category WHERE sub_category = '" . $this->db->escape($sub_category) . "'");
    return $query->rows;
  }

  public function getProductMainCategoryId($product_id) {
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1' LIMIT 1");
		return ($query->num_rows ? (int)$query->row['category_id'] : 0);
	}

  public function getProductPreLoad($product_id)
  {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

    if ($this->config->get('cdl_wildberries_export_category')) {
			$main_category = $this->getProductMainCategoryId($product_id);
			if ($main_category > 0) {
				$category_id = $main_category;
			} else {
        if (!empty($query->row['category_id'])) {
  				$category_id = $query->row['category_id'];
  			} else {
  				$category_id = '';
  			}
			}
		} else {
      if (!empty($query->row['category_id'])) {
				$category_id = $query->row['category_id'];
			} else {
				$category_id = '';
			}
		}

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'category_id'      => $category_id,
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'quantity'         => $query->row['quantity'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => $query->row['price'],
				'special'          => $query->row['special'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getProductsPreLoad($data = array())
  {
    if (!empty($this->config->get('cdl_wildberries_price_export_ot'))) {
      $ot = ' AND price > ' . $this->config->get('cdl_wildberries_price_export_ot');
    } else {
      $ot = '';
    }
    if (!empty($this->config->get('cdl_wildberries_price_export_do'))) {
      $do = ' AND price < ' . $this->config->get('cdl_wildberries_price_export_do');
    } else {
      $do = '';
    }
		$sql = "SELECT p.product_id, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))" . $ot . $do . " ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}
			$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		if (!empty($this->config->get('cdl_wildberries_export_stock_null'))) {
			$quantity = '>=';
		} else {
			$quantity = '>';
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.quantity " . $quantity . " 0 AND image IS NOT NULL AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND NOT p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "cdl_wildberries_products)";

    if ($data['blacklist']) {
      $sql .= " AND p.product_id NOT IN (" . implode(", ", $data['blacklist']) . ")";
    }

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}
    if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		if (!empty($data['filter_stop_manufacturer'])) {
			$sql .= " AND p.manufacturer_id NOT IN (" . $data['filter_stop_manufacturer'] . ")";
		}

		$sql .= $ot . $do . " GROUP BY p.product_id";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$product_data = array();
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProductPreLoad($result['product_id']);
		}
		return $product_data;
	}

  public function getProductAttribute($product_id, $shop_attribute_id, $language_id)
  {
    $query = $this->db->query("SELECT text FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$shop_attribute_id . "' AND language_id = '" . (int)$language_id . "'");
    return $query->rows;
  }

  public function getProductOcByBarcodeWb($barcode)
  {
  	$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "cdl_wildberries_products WHERE barcode = '" . $this->db->escape($barcode) . "'");
    $product_data = $this->getProductPreLoad($query->rows[0]['product_id']);
    return $product_data;
  }

  public function getManufactureRima($shop_id)
  {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_manufacturer WHERE shop_id = '" . (int)$shop_id . "'");
		return $query->rows;
	}

  public function getCategoryRate()
  {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_rate");
		return $query->rows;
	}

  public function saveCategoryRate($category_wb, $rub, $rate)
  {
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "cdl_wildberries_rate SET category_wb = '" . $this->db->escape($category_wb) . "', rub = '" . (int)$rub . "', rate = '" . $this->db->escape($rate) . "'");
	}

  public function clearCategoryRate()
  {
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "cdl_wildberries_rate");
	}

  public function getRateByCategory($category_wb)
  {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_rate WHERE category_wb = '" . $this->db->escape($category_wb) . "'");
		return $query->rows;
	}

  public function getDictionarExport($sub_category, $shop_category, $shop_attribute_id, $text_shop_attribute, $type)
  {
    $query = $this->db->query("SELECT dictionary_value FROM " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary WHERE sub_category = '" . $this->db->escape($sub_category) . "' AND shop_category = '" . (int)$shop_category . "' AND shop_attribute_id = '" . (int)$shop_attribute_id . "' AND text_shop_attribute = '" . $this->db->escape($text_shop_attribute) . "' AND wb_attr_name = '" . $this->db->escape($type) . "'");
    return $query->rows;
  }

  public function getTotalOrders($data = array())
  {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE wb_order_id";
    if (!empty($data['filter_wb_order_id'])) {
			$sql .= " LIKE '%" . $this->db->escape($data['filter_wb_order_id']) . "%'";
		} else {
    	$sql .= " != ''";
    }
    if ($data['filter_status'] != '100') {
			$sql .= " AND status = '" . (int)$data['filter_status'] . "'";
		}
    if ($data['filter_user_status']) {
			$sql .= " AND user_status = '" . (int)$data['filter_user_status'] . "'";
		}
		if (!empty($data['filter_barcode'])) {
			$sql .= " AND sticker_bc = '" . $this->db->escape($data['filter_barcode']) . "'";
		}
    if (!empty($data['filter_sticker'])) {
			$sql .= " AND sticker LIKE '%" . $this->db->escape($data['filter_sticker']) . "%'";
		}
    if (!empty($data['filter_supplies'])) {
			$sql .= " AND supplie LIKE '%" . $this->db->escape($data['filter_supplies']) . "%'";
		}
		if (!empty($data['filter_shipment_date'])) {
			$sql .= " AND DATE(shipment_date) = DATE('" . $this->db->escape($data['filter_shipment_date']) . "')";
		}
    if (!empty($data['filter_date_created'])) {
			$sql .= " AND DATE(date_created) = DATE('" . $this->db->escape($data['filter_date_created']) . "')";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

  public function getOrders($data = array())
  {
		$sql = "SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE wb_order_id";

		if (!empty($data['filter_wb_order_id'])) {
			$sql .= " LIKE '%" . $this->db->escape($data['filter_wb_order_id']) . "%'";
		} else {
    	$sql .= " != ''";
    }
    if ($data['filter_status'] != '100') {
			$sql .= " AND status = '" . (int)$data['filter_status'] . "'";
		}
    if (!empty($data['filter_user_status'])) {
			$sql .= " AND user_status = '" . (int)$data['filter_user_status'] . "'";
		}
    if (!empty($data['filter_barcode'])) {
			$sql .= " AND sticker_bc = '" . $this->db->escape($data['filter_barcode']) . "'";
		}
    if (!empty($data['filter_sticker'])) {
			$sql .= " AND sticker LIKE '%" . $this->db->escape($data['filter_sticker']) . "%'";
		}
    if (!empty($data['filter_supplies'])) {
			$sql .= " AND supplie LIKE '%" . $this->db->escape($data['filter_supplies']) . "%'";
		}
		if (!empty($data['filter_shipment_date'])) {
			$sql .= " AND DATE(shipment_date) = DATE('" . $this->db->escape($data['filter_shipment_date']) . "')";
		}
    if (!empty($data['filter_date_created'])) {
			$sql .= " AND DATE(date_created) = DATE('" . $this->db->escape($data['filter_date_created']) . "')";
		}
		$sort_data = array(
			'wb_order_id',
			'status',
			'user_status',
			'shipment_date',
      'date_created',
			'supplie'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_created";
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

  public function changeName($name, $product_id)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "product_description SET name = '" . $this->db->escape($name) . "' WHERE product_id = '" . (int)$product_id . "'");
  }

  public function productInCategory($category_id)
  {
    $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		return $query->rows;
  }

  public function install()
  {
    $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdl_wildberries_category` (
        `category` varchar(128) NOT NULL,
        `sub_category` varchar(128) NOT NULL
      ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
    ");
    $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdl_wildberries_attributes` (
        `id` varchar(128) NOT NULL,
        `sub_category` varchar(128) NOT NULL,
        `number` tinyint(1) DEFAULT NULL,
        `required` tinyint(1) DEFAULT NULL,
        `only_dictionary` tinyint(1) DEFAULT NULL,
        `type` varchar(128) NOT NULL,
        `units` varchar(128) NOT NULL,
        `dictionary` varchar(128) NOT NULL,
        `nomenclature` tinyint(1) DEFAULT NULL,
        `nomenclature_variation` tinyint(1) DEFAULT NULL,
        `description` varchar(128) NOT NULL,
				PRIMARY KEY (`sub_category`, `type`)
      ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
    ");
    $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdl_wildberries_attributes_to_shop` (
        `sub_category` varchar(128) NOT NULL,
        `shop_category` INT(11) NOT NULL,
        `type` varchar(128) NOT NULL,
        `shop_id` varchar(128) NOT NULL,
        `is_defined` tinyint(1) NOT NULL,
        `value` MEDIUMTEXT NOT NULL,
        `nomenclature` tinyint(1) DEFAULT NULL,
        `nomenclature_variation` tinyint(1) DEFAULT NULL,
        `number` tinyint(1) DEFAULT NULL,
        `required` tinyint(1) DEFAULT NULL,
        `id` varchar(128) NOT NULL,
        `units` varchar(128) NOT NULL,
        `dictionary` tinyint(1) DEFAULT NULL,
        `only_dictionary` tinyint(1) DEFAULT NULL,
        `action` varchar(128) NOT NULL,
        `action_value` varchar(128) NOT NULL,
				PRIMARY KEY (`sub_category`, `shop_category`, `type`)
      ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
    ");
    $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdl_wildberries_to_shop_dictionary` (
        `sub_category` varchar(128) NOT NULL,
        `shop_category` INT(11) NOT NULL,
        `wb_attr_name` varchar(128) NOT NULL,
        `shop_attribute_id` INT(11) NOT NULL,
        `dictionary_value` MEDIUMTEXT NOT NULL,
        `text_shop_attribute` MEDIUMTEXT NOT NULL
      ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
    ");
    $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdl_wildberries_manufacturer` (
        `shop_id` INT(11) NOT NULL,
        `dictionary_value` varchar(128) NOT NULL,
        `country` varchar(128) NOT NULL
      ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
    ");
    $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdl_wildberries_products` (
				`product_id` INT(11) NOT NULL,
				`model` varchar(64) NOT NULL,
				`sku` varchar(64) NOT NULL,
				`date` datetime NOT NULL,
				`imt_id` INT(11) NOT NULL,
        `nm_id` INT(11) NOT NULL,
        `barcode` varchar(64) NOT NULL,
        `status` varchar(128) NOT NULL,
				`error` MEDIUMTEXT NOT NULL,
        `sub_category` varchar(128) NOT NULL,
				`category` varchar(128) NOT NULL,
        `category_shop` INT(11) NOT NULL,
        `chrt_id` INT(11) NOT NULL,
				PRIMARY KEY (`product_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");
    $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdl_wildberries_orders` (
				`wb_order_id` varchar(64) NOT NULL,
				`order_id` INT(11) NOT NULL,
				`store_id` INT(11) NOT NULL,
				`date_created` datetime NOT NULL,
				`shipment_date` datetime NOT NULL,
				`date_update` datetime NOT NULL,
				`user_id` INT(11) NOT NULL,
        `phone` varchar(64) NOT NULL,
        `fio` varchar(64) NOT NULL,
        `barcode` varchar(64) NOT NULL,
        `status` INT(11) NOT NULL,
        `user_status` INT(11) NOT NULL,
				`total_price` INT(11) NOT NULL,
				`address` varchar(255) NOT NULL,
				`guid` varchar(255) NOT NULL,
        `sticker` varchar(64) NOT NULL,
        `sticker_bc` varchar(64) NOT NULL,
        `rid` varchar(64) NOT NULL,
        `stat` tinyint(1) NOT NULL,
				PRIMARY KEY (`wb_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");
    $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdl_wildberries_rate` (
				`category_wb` varchar(128) NOT NULL,
				`rub` INT(11) NOT NULL,
        `rate` varchar(64) NOT NULL
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");
  }
  public function uninstall()
  {
    // $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "cdl_wildberries_category");
    // $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "cdl_wildberries_attributes");
    // $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "cdl_wildberries_attributes_to_shop");
    // $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary");
    // $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "cdl_wildberries_manufacturer");
    // $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "cdl_wildberries_products");
    // $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "cdl_wildberries_orders");
    // $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "cdl_wildberries_rate");
  }
}
