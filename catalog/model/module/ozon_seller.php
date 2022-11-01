<?php
class ModelModuleOzonSeller extends Model {
	public function saveOzonCategory($response) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "ozon_category");

		foreach ($response as $respons) {
			header('Content-Type: text/html; charset=utf-8');
			foreach ($respons as $respon) {
				if (!empty($respon['children'])) {
					$subcategory = $respon['children'];
					foreach ($subcategory as $resp) {
						if (!empty($resp['children'])) {
							$subcategory1 = $resp['children'];
							foreach ($subcategory1 as $res) {
								if (!empty($res['children'])) {
									$subcategory2 = $res['children'];
										foreach ($subcategory2 as $re) {
											$title = $respon['title'] . ' > ' . $resp['title'] . ' > ' . $res['title'] . ' > ' . $re['title'];
											$ozon_category_id = $re['category_id'];
											$this->db->query("INSERT INTO " . DB_PREFIX . "ozon_category SET ozon_category_id = '" . (int)$ozon_category_id . "', title = '" . $this->db->escape($title) . "'");
										}
								} else {
									$title = $respon['title'] . ' > ' . $resp['title'] . ' > ' . $res['title'];
									$ozon_category_id = $res['category_id'];
									$this->db->query("INSERT INTO " . DB_PREFIX . "ozon_category SET ozon_category_id = '" . (int)$ozon_category_id . "', title = '" . $this->db->escape($title) . "'");
								}
							}
						} else {
							$title = $respon['title'] . ' > ' . $resp['title'];
							$ozon_category_id = $resp['category_id'];
							$this->db->query("INSERT INTO " . DB_PREFIX . "ozon_category SET ozon_category_id = '" . (int)$ozon_category_id . "', title = '" . $this->db->escape($title) . "'");
						}
					}
				}
			}
		}
	}

	public function deleteOrder($posting_number) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "ozon_ms_order_guid WHERE posting_number = '" . $this->db->escape($posting_number) . "'");
	}

	public function getMyOrder($posting_number) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_ms_order_guid WHERE posting_number = '". $this->db->escape($posting_number) . "'");

		return $query->rows;
	}
	public function getMyOrders($postings) {

		$postings = implode("','", $postings);

		$query = $this->db->query("SELECT guid FROM " . DB_PREFIX . "ozon_ms_order_guid WHERE posting_number IN ('". $postings . "')");

		return $query->rows;
	}

	public function saveOrder($posting_number, $shipment_date, $status) {

		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "ozon_ms_order_guid SET posting_number = '" . $this->db->escape($posting_number) . "', shipment_date = '" . $this->db->escape($shipment_date) . "', status = '" . $this->db->escape($status) . "'");
	}

	public function saveOrderFull($posting_number, $shipment_date, $status, $guid) {

		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "ozon_ms_order_guid SET posting_number = '" . $this->db->escape($posting_number) . "', shipment_date = '" . $this->db->escape($shipment_date) . "', status = '" . $this->db->escape($status) . "', guid = '" . $this->db->escape($guid) . "'");
	}

	public function updateShipmentDate($posting_number, $shipment_date)
	{
		$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_ms_order_guid SET shipment_date = '" . $this->db->escape($shipment_date) . "' WHERE posting_number = '" . $this->db->escape($posting_number) . "'");
		// интеграция с модулем администрирования
		if (!empty($this->config->get('packing_order_version'))) {
			$query = $this->db->query("UPDATE " . DB_PREFIX . "cdl_packing_order SET shipment_date = '" . $this->db->escape($shipment_date) . "' WHERE order_number = '" . $this->db->escape($posting_number) . "'");
		}
	}

	public function saveOrderGuid($posting_number, $guid) {

		$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_ms_order_guid SET guid = '" . $this->db->escape($guid) . "' WHERE posting_number = '" . $this->db->escape($posting_number) . "'");
	}

	public function getOrderByStatus($status) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_ms_order_guid WHERE status = '" . $this->db->escape($status) . "'");

		return $query->rows;
	}

	public function getOrderByStatusMonthOld($data = array()) {
		$date = new DateTime();
		$date->modify($data['month']);
		$date = $date->format('Y-m-d');

		$sql = "SELECT * FROM " . DB_PREFIX . "ozon_ms_order_guid WHERE status = '" . $this->db->escape($data['status']) . "' AND shipment_date > '" . $date . "'";

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

	public function getOrder($guid) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_ms_order_guid WHERE guid = '". $this->db->escape($guid) . "'");

		return $query->rows;
	}

	public function updateStatusOzon($posting_number, $status_ozon) {

		$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_ms_order_guid SET status = '" . $this->db->escape($status_ozon) . "' WHERE posting_number = '" . $this->db->escape($posting_number) . "'");

	}

	public function saveAttributeRequired($category_id, $attribute_id) {

		$sql = "INSERT IGNORE INTO " . DB_PREFIX . "ozon_attribute_required SET category_id = '" . (int)$category_id . "', attribute_id = '" . (int)$attribute_id . "'";

		$this->db->query($sql);
	}

	public function saveAttributeDescription($ozon_attribute_id, $ozon_attribute_name, $ozon_attribute_description, $ozon_dictionary_id, $required) {

		$sql = "INSERT IGNORE INTO " . DB_PREFIX . "ozon_attribute_description SET ozon_attribute_id = '" . (int)$ozon_attribute_id . "', ozon_attribute_name = '" . $this->db->escape($ozon_attribute_name) . "', ozon_attribute_description = '" . $this->db->escape($ozon_attribute_description) . "', ozon_dictionary_id = '" . $this->db->escape($ozon_dictionary_id) . "', required = '" . $this->db->escape($required) . "'";

		$this->db->query($sql);
	}

	public function getOzonAttributeDescription() {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_attribute_description");
		return $query->rows;
	}

	public function saveAttribute($ozon_category_id, $ozon_attribute_id) {

		$id = $ozon_category_id . $ozon_attribute_id;

		$sql = "INSERT IGNORE INTO " . DB_PREFIX . "ozon_attribute SET id = '" . (int)$id . "', ozon_category_id = '" . (int)$ozon_category_id . "', ozon_attribute_id = '" . (int)$ozon_attribute_id . "'";

		$this->db->query($sql);
	}

	public function getOzonAttribute($ozon_category_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_attribute WHERE ozon_category_id = '" . $ozon_category_id . "'");

		return $query->rows;
	}

	public function getDictionaryShoptoOzon() {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_to_shop_dictionary");
		return $query->rows;
	}

	public function getShopDictionary($product_id, $language_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . $product_id . "' AND language_id = '" . $language_id . "'");
		return $query->rows;
	}

	public function getLanguage($code) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE code = '" . $code . "'");
		return $query->row['language_id'];
	}

	public function saveDictonary($ozon_dictionary_id, $attribute_value_id, $ozon_category_id, $ozon_attribute_id, $text) {

		if ($ozon_attribute_id != 8229 && $ozon_attribute_id != 9461) {
			$ozon_category_id = '';
		}

		$query = $this->db->query("REPLACE INTO " . DB_PREFIX . "ozon_dictionary SET dictionary_id = '" . (int)$ozon_dictionary_id . "', attribute_value_id = '" . (int)$attribute_value_id . "', category_id = '" . (int)$ozon_category_id . "', attribute_group_id = '" . (int)$ozon_attribute_id . "', text = '" . $this->db->escape($text) . "'");
	}

	public function getDictionaryByCategoryAndAttributeId($category_id, $attribute_value_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_dictionary WHERE category_id = '" . $this->db->escape($category_id) . "' AND attribute_value_id = '" . $this->db->escape($attribute_value_id) . "'");
		return $query->rows[0];
	}

	public function saveExportProduct($task_id, $export_table = array(), $status = 'posting') {
		foreach ($export_table as $data) {
			$query = $this->db->query("REPLACE INTO " . DB_PREFIX . "ozon_products SET product_id = '" . (int)$data['product_id'] . "', model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', status = '" . $this->db->escape($status) . "', date = NOW(), task_id = '" . $this->db->escape($task_id) . "'");
		}
	}

	public function getExportProduct($ozon_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_products WHERE ozon_product_id = '" . $this->db->escape($ozon_product_id) . "'");
		if (empty($query->rows)) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_products WHERE ozon_sku = '" . $this->db->escape($ozon_product_id) . "'");
		}
		return $query->rows;
	}

	public function searchExportProduct($search) {
		$colomn = $this->config->get('ozon_seller_entry_offer_id');
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE " . $colomn . " = '" . $this->db->escape($search) . "'");
		return $query->rows;
	}

	public function chekTaskId() {
		$query = $this->db->query("SELECT DISTINCT task_id FROM " . DB_PREFIX . "ozon_products WHERE status != 'processed'");
		return $query->rows;
	}

	public function updateExportProduct($status, $offer_id, $ozon_sku, $ozon_product_id, $error) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_products SET status = '" . $this->db->escape($status) . "', ozon_sku = '" . (int)$ozon_sku . "', ozon_product_id = '" . (int)$ozon_product_id . "', error = '" . $this->db->escape($error) . "' WHERE " . $this->config->get('ozon_seller_entry_offer_id') . " = '" . $this->db->escape($offer_id) . "'");
		// if ($this->config->get('ozon_seller_entry_offer_id')) {
		// 	$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_products SET status = '" . $this->db->escape($status) . "', ozon_sku = '" . (int)$ozon_sku . "', ozon_product_id = '" . (int)$ozon_product_id . "', error = '" . $this->db->escape($error) . "' WHERE model = '" . $this->db->escape($offer_id) . "'");
		// } else {
		// 	$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_products SET status = '" . $this->db->escape($status) . "', ozon_sku = '" . (int)$ozon_sku . "', ozon_product_id = '" . (int)$ozon_product_id . "', error = '" . $this->db->escape($error) . "' WHERE sku = '" . $this->db->escape($offer_id) . "'");
		// }
	}

	public function downloadProduct($product_id, $model, $sku, $ozon_product_id)
	{
		$query = $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "ozon_products SET product_id = '" . (int)$product_id . "', model = '" . $this->db->escape($model) . "', sku = '" . $this->db->escape($sku) . "', status = 'processed', date = NOW(), ozon_product_id = '" . (int)$ozon_product_id . "'");

	}

	public function deletedExportProduct($product_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "ozon_products WHERE product_id = '" . $this->db->escape($product_id) . "'");
	}

	public function getProductByModel($model) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE model = '" . $this->db->escape($model) . "'");
		return $query->rows;
	}

	public function getProductBySku($sku) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE sku = '" . $this->db->escape($sku) . "'");
		return $query->rows;
	}

	public function getProductMainCategoryId($product_id) {
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1' LIMIT 1");
		return ($query->num_rows ? (int)$query->row['category_id'] : 0);
	}

	public function getProductCategorys($product_id) {
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		return $query->rows;
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if (!empty($query->row['category_id'])) {
			$category_id = $query->row['category_id'];
		} else {
			$category_id = '';
		}

		if ($this->config->get('ozon_seller_export_category')) {
			$main_category = $this->getProductMainCategoryId($product_id);
			if ($main_category > 0) {
				$category_id = $main_category;
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

	public function getProducts($data = array()) {

		$sql = "SELECT p.product_id, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

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

		if ($this->config->get('ozon_seller_export_stock_null')) {
			$quantity = '>=';
		} else {
			$quantity = '>';
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.quantity " . $quantity . " 0 AND image IS NOT NULL AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND NOT p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "ozon_products)";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id NOT IN (" . $data['filter_manufacturer_id'] . ")";
		}

		$sql .= " GROUP BY p.product_id";

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

	public function updateProducts($data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "ozon_products WHERE status = 'processed' AND product_id != 0 GROUP BY product_id";
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
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			$product_data[$result['product_id']]['stock_fbo'] = $result['stock_fbo'];
		}
		return $product_data;
	}

	public function saveManufacturer($ozon_id, $value, $picture) {
		$query = $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "ozon_manufacturer SET ozon_id = '" . (int)$ozon_id . "', value = '" . $this->db->escape($value) . "', picture = '" . $this->db->escape($picture) . "'");
	}

	public function getManufacturer($shop_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_manufacturer WHERE shop_id = '" . $this->db->escape($shop_id) . "'" );
		return $query->rows;
	}

	public function getOrderOc($posting_number) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE lastname = '" . $this->db->escape($posting_number) . "'" );
		return $query->rows;
	}

	public function saveAdminMarketplace($order, $guid, $order_id)
	{
		$date_created = date('Y-m-d H:i:s', strtotime($order['in_process_at']));
		$shipment_date = date('Y-m-d H:i:s', strtotime($order['shipment_date']));
		$query = $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "cdl_packing_order SET marketplace = 'ozon', order_number = '" . $this->db->escape($order['posting_number']) . "', order_id = '" . (int)$order_id . "', date_created = '" . $this->db->escape($date_created) . "', shipment_date = '" . $this->db->escape($shipment_date) . "', barcode_1 = '" . $this->db->escape($order['barcodes']['upper_barcode']) . "', barcode_2 = '" . $this->db->escape($order['barcodes']['lower_barcode']) . "', guid = '" . $this->db->escape($guid) . "', status = 'Новый'");
		foreach ($order['products'] as $product) {
			$db_product = $this->getExportProduct($product['sku']);
			if (!empty($product)) {
				$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "cdl_packing_order_product SET marketplace = 'ozon', product_id = '" . (int)$db_product[0]['product_id'] . "', order_number = '" . $this->db->escape($order['posting_number']) . "', sku = '" . $this->db->escape($db_product[0]['sku']) . "', model = '" . $this->db->escape($db_product[0]['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "'");
			}
		}
		$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "cdl_packing_order_status SET marketplace = 'ozon', order_number = '" . $this->db->escape($order['posting_number']) . "', status = 'Новый', date = NOW()");
	}

	public function updateInfoStocks($fbs, $fbo, $offer_id)
	{
		$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_products SET stock_fbs = '" . (int)$fbs . "', stock_fbo = '" . (int)$fbo . "' WHERE " . $this->config->get('ozon_seller_entry_offer_id') . " = '" . $this->db->escape($offer_id) . "'");
	}

	public function updateInfoPrice($price, $komission_fbo, $komission_fbs, $offer_id)
	{
		$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_products SET price_oz = '" . (float)$price . "', komission_fbo = '" . (float)$komission_fbo . "', komission_fbs = '" . (float)$komission_fbs . "' WHERE " . $this->config->get('ozon_seller_entry_offer_id') . " = '" . $this->db->escape($offer_id) . "'");
	}

	// update240 ++
	public function checkColomnProduct()
	{
		$query = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . DB_PREFIX . "ozon_products'");
    return $query->rows;
	}

	public function addStockColomn()
  {
    $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "ozon_products ADD stock_fbs INT(11) AFTER error, ADD stock_fbo INT(11) AFTER stock_fbs, ADD price_oz DECIMAL(7,1) AFTER stock_fbo, ADD komission_fbo DECIMAL(7,1) AFTER price_oz, ADD komission_fbs DECIMAL(7,1) AFTER komission_fbo");
  }
	// update240 --
}
