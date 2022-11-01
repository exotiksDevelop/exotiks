<?php
class ModelModuleOzonSeller extends Model
{
	public function getCategories($categorys)
	{
		$query = $this->db->query("SELECT category_id, name FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND category_id IN (" . implode(', ', $categorys) . ")");
		return $query->rows;
	}

	public function getDictionaryByCategoryAndAttributeId($category_id, $attribute_value_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_dictionary WHERE category_id = '" . (int)$category_id . "' AND attribute_value_id = '" . (int)$attribute_value_id . "'");
		if (!empty($query->rows[0])) {
			return $query->rows[0];
		}
	}
	public function getShopDictionaryPreLoad($product_id, $language_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . $product_id . "' AND language_id = '" . $language_id . "'");
		return $query->rows;
	}

	public function getOzonAttributeByCategory($ozon_category_id)
	{
		$query = $this->db->query("SELECT oad.ozon_attribute_id, oad.ozon_attribute_name, oad.ozon_dictionary_id, oad.required FROM " . DB_PREFIX . "ozon_attribute_description oad INNER JOIN " . DB_PREFIX . "ozon_attribute oa ON oad.ozon_attribute_id = oa.ozon_attribute_id WHERE oa.ozon_category_id = '" . $ozon_category_id . "'");
		return $query->rows;
	}

	public function getNameShopCategory($categorys, $language_id)
	{
		$shop_id = implode(',', $categorys);
		$query = $this->db->query("SELECT name, category_id FROM " . DB_PREFIX . "category_description WHERE category_id IN (" . $shop_id . ") AND language_id = '" . (int)$language_id . "'");
		return $query->rows;
	}

	public function getLanguage($code)
  {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE code = '" . $code . "'");
		return $query->row['language_id'];
	}

	public function getProductMainCategoryId($product_id)
	{
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1' LIMIT 1");
		return ($query->num_rows ? (int)$query->row['category_id'] : 0);
	}

	public function getProduct($product_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

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

	public function getProductsPreLoad($data = array())
	{
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

	public function getOzonCategory($ozon_category_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_category WHERE ozon_category_id IN (" . implode(',', $ozon_category_id) . ")");
		return $query->rows;
	}

	public function searchOzonCategory($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ozon_category";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE title LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'title'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY title";
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

	public function searchOzonDictionary($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ozon_dictionary";

		if (!empty($data['ozon_dictionary_id'])) {
			$sql .= " WHERE dictionary_id = '" . $this->db->escape($data['ozon_dictionary_id']) . "' AND text LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'text'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY text";
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

	public function getOzonDictionaryNoCategory() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_dictionary WHERE category_id != 0 ORDER BY text ASC");
		return $query->rows;
	}

	public function getOzonDictionary($ozon_dictionary_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_dictionary WHERE dictionary_id = '" . $ozon_dictionary_id . "' ORDER BY text ASC");
		return $query->rows;
	}

	public function getOzonDictionaryType($category_ozon) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ozon_dictionary` WHERE `category_id` = '" . $category_ozon . "'");
		return $query->rows;
	}

	public function getShopDictionary($shop_attribute_id) {

		$query = $this->db->query("SELECT MIN(`product_id`) `product_id`, `attribute_id`, `text` FROM `" . DB_PREFIX . "product_attribute` WHERE `attribute_id` = '" . $shop_attribute_id . "' GROUP BY `attribute_id`, `text` ORDER BY `text` ASC");
		return $query->rows;
	}

	public function getOzonAttribute() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ozon_attribute`");
		return $query->rows;
	}

	public function getOzonAttributeDescription() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_attribute_description ORDER BY ozon_attribute_name");
		return $query->rows;
	}

	public function ozonAttributeDescription($ozon_attribute_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_attribute_description WHERE ozon_attribute_id = '" . (int)$ozon_attribute_id . "'");
		return $query->rows;
	}

	public function truncateAttribute() {
		$query = $this->db->query("TRUNCATE " . DB_PREFIX . "ozon_attribute");
		$query = $this->db->query("TRUNCATE " . DB_PREFIX . "ozon_attribute_description");
		$query = $this->db->query("TRUNCATE " . DB_PREFIX . "ozon_attribute_required");
		$query = $this->db->query("TRUNCATE " . DB_PREFIX . "ozon_dictionary");
	}

	public function saveDictionary($ozon_attribute_id, $shop_attribute_id, $dictionary_value_id, $value, $text_shop_attribute) {
		$query = $this->db->query("REPLACE INTO `" . DB_PREFIX . "ozon_to_shop_dictionary` SET `ozon_attribute_id` = '" . $this->db->escape($ozon_attribute_id) . "', `dictionary_value_id` = '" . $dictionary_value_id . "', `value` = '" . $this->db->escape($value) . "', `text_shop_attribute` = '" . $this->db->escape($text_shop_attribute) . "', `shop_attribute_id` = '" . $this->db->escape($shop_attribute_id) . "'");
	}

	public function unsetDictionary($ozon_attribute_id, $shop_attribute_id) {
		$query = $this->db->query("DELETE FROM  " . DB_PREFIX . "ozon_to_shop_dictionary WHERE ozon_attribute_id = '" . $ozon_attribute_id . "' AND shop_attribute_id = '" . $shop_attribute_id . "'");
	}

	public function deleteDictionaryValue($dictionary_value_id) {
		$query = $this->db->query("DELETE FROM  " . DB_PREFIX . "ozon_to_shop_dictionary WHERE dictionary_value_id = '" . $dictionary_value_id . "'");
	}

	public function getDictionaryShoptoOzon() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ozon_to_shop_dictionary`");
		return $query->rows;
	}

	public function dictionaryShoptoOzon($ozon_attribute_id, $shop_attribute_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_to_shop_dictionary WHERE ozon_attribute_id = '" . (int)$ozon_attribute_id . "' AND shop_attribute_id = '" . (int)$shop_attribute_id . "'");
		return $query->rows;
	}

	public function getDictionaryShoptoOzonDictionaryId($value_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ozon_to_shop_dictionary` WHERE dictionary_value_id = '" . $this->db->escape($value_id) . "'");
		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "ozon_products p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_sku'])) {
			$sql .= " AND p.sku LIKE '" . $this->db->escape($data['filter_sku']) . "%'";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND p.status = '" . $this->db->escape($data['filter_status']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProducts($data = array()) {
		$sql = "SELECT *, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT price FROM " . DB_PREFIX . "product pr WHERE pr.product_id = p.product_id) AS price FROM " . DB_PREFIX . "ozon_products p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
			$sql .= " AND p.sku LIKE '" . $this->db->escape($data['filter_sku']) . "%'";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND p.status = '" . $this->db->escape($data['filter_status']) . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.sku',
			'p.model',
			'p.status',
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

	public function deletedExportProduct($product_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "ozon_products WHERE product_id = '" . $product_id . "'");
	}

	public function deletedExportProductStatus($status) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "ozon_products WHERE status = '" . $status . "'");
	}

	public function getManufacturer($shop_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ozon_manufacturer WHERE shop_id = '" . $this->db->escape($shop_id) . "'" );
		return $query->rows;
	}

	public function updateManufacturer($shop_id, $ozon_id) {

		if ($ozon_id == 'delete') {

			$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_manufacturer SET shop_id = '' WHERE shop_id = '" . $this->db->escape($shop_id) . "'");

		} else {

			$query = $this->db->query("UPDATE " . DB_PREFIX . "ozon_manufacturer SET shop_id = '" . $this->db->escape($shop_id) . "' WHERE ozon_id = '" . (int)$ozon_id . "'");
		}
	}

	public function searchOzonManufacturer($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ozon_manufacturer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE value LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'value'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY value";
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

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ozon_ms_order_guid";

		if (isset($data['filter_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "status = '" . $order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE status != ''";
		}

		if (!empty($data['filter_posting_number'])) {
			$sql .= " AND posting_number  LIKE '%" . $this->db->escape($data['filter_posting_number']) . "%'";
		}

		if (!empty($data['filter_shipment_date'])) {
			$sql .= " AND DATE(shipment_date) = DATE('" . $this->db->escape($data['filter_shipment_date']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOrders($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ozon_ms_order_guid";

		if (isset($data['filter_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "status = '" . $order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE status != ''";
		}

		if (!empty($data['filter_posting_number'])) {
			$sql .= " AND posting_number  LIKE '%" . $this->db->escape($data['filter_posting_number']) . "%'";
		}

		if (!empty($data['filter_shipment_date'])) {
			$sql .= " AND DATE(shipment_date) = DATE('" . $this->db->escape($data['filter_shipment_date']) . "')";
		}

		$sort_data = array(
			'posting_number',
			'status',
			'shipment_date'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY shipment_date";
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

	public function getAttributeRequired($category_id) {

		$query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "ozon_attribute_required WHERE category_id = '" . (int)$category_id . "'");

		return $query->rows;
	}

	public function getProductByModel($model) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE model = '" . $this->db->escape($model) . "'");
		return $query->rows;
	}

	public function getProductBySku($sku) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE sku = '" . $this->db->escape($sku) . "'");
		return $query->rows;
	}

	public function getExportProduct($search) {
		$colomn = $this->config->get('ozon_seller_entry_offer_id');
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE " . $colomn . " = '" . $this->db->escape($search) . "'");
		return $query->rows;
	}

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ozon_category` (
			 	`ozon_category_id` INT(11) NOT NULL AUTO_INCREMENT,
				`title` varchar(255) NOT NULL,
				PRIMARY KEY (`ozon_category_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ozon_attribute` (
				`id` varchar(255) NOT NULL,
				`ozon_category_id` INT(11) NOT NULL,
				`ozon_attribute_id` INT(11) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ozon_attribute_description` (
				`ozon_attribute_id` INT(11) NOT NULL,
				`ozon_attribute_name` varchar(128) NOT NULL,
				`ozon_attribute_description` text(255),
				`ozon_dictionary_id` INT(11) NOT NULL,
				`shop_attribute_id` varchar(10) NOT NULL,
				`required` varchar(10) NOT NULL,
				PRIMARY KEY (`ozon_attribute_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ozon_dictionary` (
				`dictionary_id` INT(11) NOT NULL,
				`attribute_value_id` INT(11) NOT NULL,
				`category_id` INT(11) NOT NULL,
				`attribute_group_id` INT(11) NOT NULL,
				`text` varchar(255) NOT NULL,
				PRIMARY KEY (`attribute_value_id`, `category_id`, `attribute_group_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ozon_ms_order_guid` (
				`posting_number` varchar(128) NOT NULL,
				`guid` varchar(128) NOT NULL,
				`status` varchar(128) NOT NULL,
				`shipment_date` DATETIME NOT NULL,
				PRIMARY KEY (`posting_number`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ozon_to_shop_dictionary` (
				`ozon_attribute_id` INT(11) NOT NULL,
				`dictionary_value_id` varchar(128) NOT NULL,
				`value` varchar(128) NOT NULL,
				`text_shop_attribute` varchar(128) NOT NULL,
				`shop_attribute_id` INT(11) NOT NULL,
				PRIMARY KEY (`text_shop_attribute`, `shop_attribute_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ozon_products` (
				`product_id` INT(11) NOT NULL,
				`model` varchar(64) NOT NULL,
				`sku` varchar(64) NOT NULL,
				`status` varchar(128) NOT NULL,
				`date` datetime NOT NULL,
				`ozon_sku` INT(11) NOT NULL,
				`ozon_product_id` INT(11) NOT NULL,
				`task_id` INT(11) NOT NULL,
				`error` varchar(255) NOT NULL,
				`stock_fbs` INT(11),
				`stock_fbo` INT(11),
				`price_oz` DECIMAL(7,1),
				`komission_fbo` DECIMAL(7,1),
				`komission_fbs` DECIMAL(7,1),
				PRIMARY KEY (`product_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ozon_manufacturer` (
				`ozon_id` INT(11) NOT NULL,
				`value` varchar(128) NOT NULL,
				`picture` varchar(255) NOT NULL,
				`shop_id` varchar(128) NOT NULL,
				PRIMARY KEY (`ozon_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ozon_attribute_required` (
				`category_id` INT(11) NOT NULL,
				`attribute_id` INT(11) NOT NULL,
				PRIMARY KEY (`category_id`, `attribute_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");
	}

	public function uninstall() {
		// $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "ozon_category");
		// $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "ozon_attribute");
		// $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "ozon_attribute_description");
		// $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "ozon_dictionary");
		// $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "ozon_ms_order_guid");
		// $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "ozon_to_shop_dictionary");
		// $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "ozon_products");
		// $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "ozon_manufacturer");
		// $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "ozon_attribute_required");
	}
}
