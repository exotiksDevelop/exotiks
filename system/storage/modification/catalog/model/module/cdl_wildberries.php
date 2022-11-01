<?php
/*
* OpenCart 2.1
* @author Dmitri Cheremisin
*/
class ModelModuleCdlWildberries extends Model
{
  public function deleteCategory()
  {
    $this->db->query("DELETE FROM " . DB_PREFIX . "cdl_wildberries_category");
  }

  public function saveCategory($category, $sub_category)
  {
    $this->db->query("INSERT INTO " . DB_PREFIX . "cdl_wildberries_category SET category = '" . $this->db->escape($category) . "', sub_category = '" . $this->db->escape($sub_category) . "'");
  }

  public function getCategoryBySub($sub_category)
  {
    $query = $this->db->query("SELECT category FROM " . DB_PREFIX . "cdl_wildberries_category WHERE sub_category = '" . $this->db->escape($sub_category) . "'");
    return $query->rows;
  }

  public function getAttrWbBySubCategory($sub_category)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_attributes WHERE sub_category = '" . $this->db->escape($sub_category) . "'");
    return $query->rows;
  }

  public function saveWbAttributes($id, $sub_category, $number, $required, $only_dictionary, $type, $units, $dictionary, $nomenclature, $nomenclature_variation, $description)
  {
    $this->db->query("INSERT INTO " . DB_PREFIX . "cdl_wildberries_attributes SET id = '" . $this->db->escape($id) . "', sub_category = '" . $this->db->escape($sub_category) . "', number = '" . $this->db->escape($number) . "', required = '" . $this->db->escape($required) . "', only_dictionary = '" . $this->db->escape($only_dictionary) . "', type = '" . $this->db->escape($type) . "', units = '" . $this->db->escape($units) . "', dictionary = '" . $this->db->escape($dictionary) . "', nomenclature = '" . $this->db->escape($nomenclature) . "', nomenclature_variation = '" . $this->db->escape($nomenclature_variation) . "', description = '" . $this->db->escape($description) . "'");
  }

  public function getProductMainCategoryId($product_id) {
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1' LIMIT 1");
		return ($query->num_rows ? (int)$query->row['category_id'] : 0);
	}

  public function getImages($product_id)
  {
    $query = $this->db->query("SELECT p.image AS general, po.image FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_image po ON (p.product_id = po.product_id) WHERE p.product_id = '" . (int)$product_id . "'");
		return $query->rows;
  }

  public function updateStatusProduct($status, $product_id)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_products SET date = NOW(), status = '" . $this->db->escape($status) . "', error = '' WHERE product_id = '" . $this->db->escape($product_id) . "'");
  }

  public function getProduct($product_id)
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

	public function getProducts($data = array())
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
      $sql .= " AND p.product_id IN (" . implode(", ", $data['blacklist']) . ")";
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
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}
		return $product_data;
	}

  public function getManufacture($shop_id)
  {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_manufacturer WHERE shop_id = '" . (int)$shop_id . "'");
		return $query->rows;
	}

  public function getAttributesToShop($sub_category, $shop_category)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_attributes_to_shop WHERE sub_category = '" . $this->db->escape($sub_category) . "' AND shop_category = '" . (int)$shop_category . "'");
    return $query->rows;
  }

  public function getLanguage($code)
  {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE code = '" . $code . "'");
		return $query->row['language_id'];
	}

  public function getProductAttribute($product_id, $shop_attribute_id, $language_id)
  {
    $query = $this->db->query("SELECT text FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$shop_attribute_id . "' AND language_id = '" . (int)$language_id . "'");
    return $query->rows;
  }

  public function getDictionarExport($sub_category, $shop_category, $shop_attribute_id, $text_shop_attribute, $type)
  {
    $query = $this->db->query("SELECT dictionary_value FROM " . DB_PREFIX . "cdl_wildberries_to_shop_dictionary WHERE sub_category = '" . $this->db->escape($sub_category) . "' AND shop_category = '" . (int)$shop_category . "' AND shop_attribute_id = '" . (int)$shop_attribute_id . "' AND text_shop_attribute = '" . $this->db->escape($text_shop_attribute) . "' AND wb_attr_name = '" . $this->db->escape($type) . "'");
    return $query->rows;
  }

  public function saveExportProduct($product_id, $model, $sku, $imt_id, $nm_id, $barcode, $status, $error, $category, $sub_category, $category_shop, $chrt_id)
  {
    $this->db->query("INSERT INTO " . DB_PREFIX . "cdl_wildberries_products SET product_id = '" . (int)$product_id . "', model = '" . $this->db->escape($model) . "', sku = '" . $this->db->escape($sku) . "', date = NOW(), imt_id = '" . (int)$imt_id . "', nm_id = '" . (int)$nm_id . "', barcode = '" . $this->db->escape($barcode) . "', status = '" . $this->db->escape($status) . "', error = '" . $this->db->escape($error) . "', category = '" . $this->db->escape($category) . "', sub_category = '" . $this->db->escape($sub_category) . "', category_shop = '" . (int)$category_shop . "', chrt_id = '" . (int)$chrt_id . "'");
  }

  public function getNoImtProduct($data = array())
  {
    $query = $this->db->query("SELECT product_id, model, sku FROM " . DB_PREFIX . "cdl_wildberries_products WHERE nm_id = '0' OR status = 'Создается' OR status = 'Без фото' LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);
    return $query->rows;
  }

  public function updateNewProduct($search_db, $imt_id, $nm_id, $barcode, $status, $error, $chrt_id)
  {
    $relations = array('mpn', 'upc', 'ean', 'jan', 'isbn');
    $colomn = $this->config->get('cdl_wildberries_relations');
    if (in_array($colomn, $relations)) {
      $search = $this->getProductDbByVendorCode($search_db);
      $search_db = $search[0]['product_id'];
      $colomn = 'product_id';
    }
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_products SET date = NOW(), imt_id = '" . (int)$imt_id . "', nm_id = '" . (int)$nm_id . "', barcode = '" . $this->db->escape($barcode) . "', status = '" . $this->db->escape($status) . "', error = '" . $this->db->escape($error) . "', chrt_id = '" . (int)$chrt_id . "' WHERE " . $colomn . " = '" . $this->db->escape($search_db) . "'");
  }

  public function getProductDbByVendorCode($search_db)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE " .  $this->config->get('cdl_wildberries_relations') . " = '" . $search_db . "'");
    return $query->rows;
  }

  public function getImportedProduct($product_id)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_products WHERE product_id = '" . $product_id . "'");
    return $query->rows;
  }

  public function getProductByVendorCode($search_db)
  {
    $colomn = $this->config->get('cdl_wildberries_relations');
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_products WHERE " . $colomn . " = '" . $this->db->escape($search_db) . "'");
    return $query->rows;
  }

  public function getShopProductByVendorCode($search_db)
  {
    $colomn = $this->config->get('cdl_wildberries_relations');
    $query = $this->db->query("SELECT product_id, model, sku FROM " . DB_PREFIX . "product WHERE " . $colomn . " = '" . $this->db->escape($search_db) . "'");
    return $query->rows;
  }

  public function getShopProductByBarcode($barcode)
  {
    $barcode = $this->db->escape($barcode);
    $query = $this->db->query("SELECT product_id, model, sku FROM " . DB_PREFIX . "product WHERE ean = '" . $barcode . "' OR upc = '" . $barcode . "' OR jan = '" . $barcode . "' OR isbn = '" . $barcode . "' OR mpn = '" . $barcode . "'");
    return $query->rows;
  }

  public function getProductByBarcode($barcode)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_products WHERE barcode = '" . $this->db->escape($barcode) . "'");
    return $query->rows;
  }

  public function getExportProducts($data = array())
  {
		$sql = "SELECT * FROM " . DB_PREFIX . "cdl_wildberries_products WHERE nm_id != '' GROUP BY product_id";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 1000;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProductLite($result['product_id']);
      $product_data[$result['product_id']]['nmId'] = $result['nm_id'];
      $product_data[$result['product_id']]['barcode'] = $result['barcode'];
      $product_data[$result['product_id']]['sub_category'] = $result['sub_category'];
		}
		return $product_data;
	}

  public function getProductLite($product_id)
  {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

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
				'category_id'      => $category_id,
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'quantity'         => $query->row['quantity'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'price'            => $query->row['price'],
				'special'          => $query->row['special'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'minimum'          => $query->row['minimum'],
				'status'           => $query->row['status']
			);
		} else {
			return false;
		}
  }

  public function getOrder($wb_order_id)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE wb_order_id = '" . $wb_order_id . "'");
    return $query->rows;
  }

  public function getDeliveredOrders()
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE user_status = 2 AND date_created >= DATE_SUB(CURRENT_DATE, INTERVAL 60 DAY)");
    return $query->rows;
  }

  public function getTrackingOrders()
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE user_status = 0 OR user_status = 2 OR user_status = 4 AND date_update >= DATE_SUB(CURRENT_DATE, INTERVAL 100 DAY)");
    return $query->rows;
  }

  public function getOrderLite($wb_order_id)
  {
    $query = $this->db->query("SELECT status, order_id FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE wb_order_id = '" . $wb_order_id . "'");
    return $query->rows;
  }

  public function getOrderGuid($wb_order_id)
  {
    $query = $this->db->query("SELECT guid FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE wb_order_id = '" . $wb_order_id . "'");
    return $query->rows;
  }

  public function saveNewOrder($wb_order_id, $store_id, $date_created, $shipment_date, $user_id, $phone, $fio, $barcode, $status, $total_price, $address, $rid)
  {
    $this->db->query("INSERT INTO " . DB_PREFIX . "cdl_wildberries_orders SET wb_order_id = '" . $this->db->escape($wb_order_id) . "', store_id = '" . (int)$store_id . "', date_created = '" . $this->db->escape($date_created) . "', shipment_date = '" . $this->db->escape($shipment_date) . "', user_id = '" . (int)$user_id . "', phone = '" . $this->db->escape($phone) . "', fio = '" . $this->db->escape($fio) . "', barcode = '" . $this->db->escape($barcode) . "', status = '" . (int)$status . "', total_price = '" . (int)$total_price . "', address = '" . $this->db->escape($address) . "', rid = '" . $this->db->escape($rid) . "'");
  }

  public function getEmptyGuidOrders()
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE guid = ''");
    return $query->rows;
  }

  public function saveGuidOrderMs($wb_order_id, $guid)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_orders SET guid = '" . $this->db->escape($guid) . "' WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function getOrdersByStatus($status)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE status = '" . (int)$status . "'");
    return $query->rows;
  }

  public function changeOrderStatus($wb_order_id, $status)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_orders SET status = '" . (int)$status . "', date_update = NOW() WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function changeOrderUserStatus($wb_order_id, $user_status)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_orders SET user_status = '" . (int)$user_status . "', date_update = NOW() WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function addSupplieDb($wb_order_id, $supplie)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_orders SET supplie = '" . $this->db->escape($supplie) . "', date_update = NOW() WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function deleteOrder($wb_order_id)
  {
    $this->db->query("DELETE FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function addStickerOrder($wb_order_id, $sticker, $sticker_bc)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_orders SET sticker = '" . $this->db->escape($sticker) . "', sticker_bc = '" . $this->db->escape($sticker_bc) . "', date_update = NOW() WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function addOrderIdOC($wb_order_id, $order_id)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_orders SET order_id = '" . (int)$order_id . "', date_update = NOW() WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function getOrdersByUserStatus($user_status)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE user_status = '" . (int)$user_status . "'");
    return $query->rows;
  }

  public function updateRid($wb_order_id, $rid)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_orders SET rid = '" . $this->db->escape($rid) . "' WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function getOrdersNoRid()
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE rid = ''");
  }

  public function getRateByCategory($category_wb)
  {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_rate WHERE category_wb = '" . $this->db->escape($category_wb) . "'");
		return $query->rows;
	}

  public function getOrderByRid($rid)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE rid = '" . $this->db->escape($rid) . "'");
    return $query->rows;
  }

  public function getOrderByShk($sticker)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE sticker = '" . $this->db->escape($sticker) . "'");
    return $query->rows;
  }

  public function updateStat($wb_order_id)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_orders SET stat = 1 WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function discrepancyOc($price, $order_id)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "order SET total = '" . $this->db->escape($price) . "' WHERE order_id = '" . (int)$order_id . "'");
    $query = $this->db->query("UPDATE " . DB_PREFIX . "order_product SET total = '" . $this->db->escape($price) . "', price = '" . $this->db->escape($price) . "' WHERE order_id = '" . (int)$order_id . "'");
    $query = $this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = '" . $this->db->escape($price) . "' WHERE order_id = '" . (int)$order_id . "' AND (code = 'sub_total' OR code = 'total')");
  }

  public function discrepancyModule($price, $wb_order_id)
  {
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_orders SET total_price = '" . $this->db->escape($price) . "' WHERE wb_order_id = '" . $this->db->escape($wb_order_id) . "'");
  }

  public function getOrdersByGuid($guid)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_orders WHERE guid = '" .  $this->db->escape($guid) . "'");
    return $query->rows;
  }

  // UPDATE 180 ++
  public function checkCdlSupplieColomnOrder()
  {
    $query = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . DB_PREFIX . "cdl_wildberries_orders'");
    return $query->rows;
  }

  public function addSupplieColomnOrder()
  {
    $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "cdl_wildberries_orders ADD supplie VARCHAR(128) NOT NULL AFTER stat");
  }
  // UPDATE 180 --

  // UPDATE 170 Valentain ++
  public function checkCdlProductColomnCategory()
  {
    $query = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . DB_PREFIX . "cdl_wildberries_products'");
    return $query->rows;
  }

  public function updateCdlColomnCategory()
  {
    $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "cdl_wildberries_products ADD sub_category VARCHAR(128) NOT NULL AFTER error, ADD category VARCHAR(128) NOT NULL AFTER sub_category, ADD category_shop INT(11) NOT NULL AFTER category");
  }

  public function updateValentainCheckNoCategoryProducts()
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cdl_wildberries_products WHERE category = '' LIMIT 0, 1000");
    return $query->rows;
  }

  public function getProductCategory($product_id)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
    return $query->rows;
  }

  public function updateCategoryProduct($search_db, $category, $nm_id, $sub_category, $shop_category_id)
  {
    $colomn = $this->config->get('cdl_wildberries_relations');
    $query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_wildberries_products SET category = '" . $this->db->escape($category) . "', sub_category = '" . $this->db->escape($sub_category) . "', category_shop = '" . (int)$shop_category_id . "' WHERE " . $colomn . " = '" . $this->db->escape($search_db) . "' AND nm_id = '" . (int)$nm_id . "'");
  }
  // UPDATE 170 Valentain --

  // UPDATE 190 ++
  public function checkColomnProduct()
  {
    $query = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . DB_PREFIX . "cdl_wildberries_products'");
    return $query->rows;
  }

  public function addChrtIdColomn()
  {
    $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "cdl_wildberries_products ADD chrt_id INT(11) NOT NULL AFTER category_shop");
  }
  // UPDATE 190 --
}
