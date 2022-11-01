<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
class ModelExtensionModuleLLOzonExchange extends Model {
	protected $code = false;
	protected $statics = false;
	protected $ll = false;

	public function __construct($registry) {
		$this->registry = $registry;

		$this->code = basename(__FILE__, '.php');

		$this->statics = new \Config();
		$this->statics->load($this->code);

		$this->ll = new LL\Core($this->registry, $this->code, $this->statics->get('type'));
		$this->api = new LL\OZON\API($this->ll);
	}

	public function addExportOrder($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . $this->code . "_order` SET 
			order_id = '" . (int)$data['order_id'] . "',
			from_city = '" .  (int)$data['from_city'] . "',
			to_city = '" .  (int)$data['to_city'] . "',
			tariff = '" .  $this->db->escape($data['tariff']) . "',
			pvz = '" .  $this->db->escape($data['pvz']) . "',
			weight = '" .  (int)$data['weight'] . "'
		");
	}

	public function updateExportOrder($data) {
		$this->db->query("UPDATE `" . DB_PREFIX . $this->code . "_order` SET 
			from_city = '" .  (int)$data['from_city'] . "',
			to_city = '" .  (int)$data['to_city'] . "',
			tariff = '" .  $this->db->escape($data['tariff']) . "',
			pvz = '" .  $this->db->escape($data['pvz']) . "',
			weight = '" .  (int)$data['weight'] . "'
			WHERE order_id = '" . (int)$data['order_id'] . "'
		");
	}

	public function addOrderStatus($order_id, $data) {
		date_default_timezone_set($this->config->get($this->ll->getPrefix() . '_timezone'));

		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . $this->code . "_status` SET order_id = '" . (int)$order_id . "', date = '" . $this->db->escape(date('Y-m-d H:i:s', strtotime($data['date']))) . "', code = '" . (int)$data['code'] . "'");
		$this->updateOrderStatus($order_id, $data);
	}

	public function updateOrderStatus($order_id, $data) {
		date_default_timezone_set($this->config->get($this->ll->getPrefix() . '_timezone'));

		$this->db->query("UPDATE `" . DB_PREFIX . $this->code . "_order` SET status = '" . (int)$data['code'] . "', date = '" . $this->db->escape(date('Y-m-d H:i:s', strtotime($data['date']))) . "' WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getExportOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->code . "_order` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}

	public function getTrackingOrders($statuses, $days) {
		date_default_timezone_set($this->config->get($this->ll->getPrefix() . '_timezone'));

		$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . $this->db->escape(implode(',', $statuses)) . ") AND date_added > '" . $this->db->escape(date('Y-m-d H:i:s', strtotime('now - ' . $days . ' day'))) . "'");

		return $query->rows;
	}

	public function getOrderInfo($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->code . "_order` as e LEFT JOIN `" . DB_PREFIX . "order` o ON e.order_id = o.order_id LEFT JOIN `" . DB_PREFIX . $this->code . "_package` p ON e.order_id = p.order_id WHERE e.order_id = '" . (int)$order_id . "'");

		if (!$query->num_rows) {
			return;
		}

		return $query->row;
	}

	public function getOrderStatus($order_id, $code, $date = false) {
		date_default_timezone_set($this->config->get($this->ll->getPrefix() . '_timezone'));

		$sql = "SELECT code FROM " . DB_PREFIX . $this->code . "_status WHERE order_id = '" . (int)$order_id . "' AND code = '" . (int)$code . "'";

		if ($date) {
			$sql .= " AND date = '" . date('Y-m-d H:i:s', strtotime($date)) . "'";
		}

		$query = $this->db->query($sql);

		if (isset($query->row['code'])) {
			return $query->row['code'];
		}
	}

	public function deleteExportOrder($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . $this->code . "_order` WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getCity($city_id) {
		$query = $this->db->query("SELECT c.name, r.name AS region_name, co.name AS country_name FROM `" . DB_PREFIX . "ll_ozon_city` as c LEFT JOIN `" . DB_PREFIX . "ll_ozon_region` r ON c.region_id = r.region_id LEFT JOIN `" . DB_PREFIX . "ll_ozon_country` co ON c.country_id = co.country_id WHERE c.city_id = '" . (int)$city_id . "'");

		if (!$query->num_rows) {
			return;
		}

		return $query->row;
	}

	public function getPvz($external_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ll_ozon_pvz` WHERE external_id LIKE '" . $this->db->escape($external_id) . "'");

		return $query->row;
	}

	public function addPvz($pvz, $region_id, $city_id) {
		$workingHours = null;

		if (isset($pvz['workingHours']) && is_array($pvz['workingHours']) && !empty($pvz['workingHours'])) {
			foreach ($pvz['workingHours'] as $wh) {
				$workingHours .= date('d.m.Y', strtotime($wh['date'])) 
					. ' (' . $wh['periods'][0]['min']['hours'] 
					. ':' 
					. ($wh['periods'][0]['min']['minutes'] == 0 ? '00' : $wh['periods'][0]['min']['minutes']) 
					. ' - ' 
					. $wh['periods'][0]['max']['hours'] 
					. ':' 
					. ($wh['periods'][0]['max']['minutes'] == 0 ? '00' : $wh['periods'][0]['max']['minutes']) 
					. ')</br>';
			}
		}

		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "ll_ozon_pvz` SET
			city_id = '" . (int)$city_id . "',
			region_id = '" . (int)$region_id . "',
			external_id = '" . $this->db->escape((int)$pvz['id']) . "',
			objectTypeId = '" . $this->db->escape((int)$pvz['objectTypeId']) . "',
			objectTypeName = '" . $this->db->escape($pvz['objectTypeName']) . "',
			name = '" . $this->db->escape($pvz['name']) . "',
			description = '" . $this->db->escape(isset($pvz['description']) ? $pvz['description'] : null) . "',
			address = '" . $this->db->escape($pvz['address']) . "',
			region = '" . $this->db->escape(isset($pvz['region']) ? $pvz['region'] : null) . "',
			settlement = '" . $this->db->escape(isset($pvz['settlement']) ? $pvz['settlement'] : null) . "',
			streets = '" . $this->db->escape(isset($pvz['streets']) ? $pvz['streets'] : null) . "',
			placement = '" . $this->db->escape(isset($pvz['placement']) ? $pvz['placement'] : null) . "',
			fittingShoesAvailable = '" . (int)$pvz['fittingShoesAvailable'] . "',
			fittingClothesAvailable = '" . (int)$pvz['fittingClothesAvailable'] . "',
			cardPaymentAvailable = '" . (int)$pvz['cardPaymentAvailable'] . "',
			howToGet = '" . $this->db->escape(isset($pvz['howToGet']) ? $pvz['howToGet'] : null) . "',
			phone = '" . $this->db->escape(isset($pvz['phone']) ? $pvz['phone'] : null) . "',
			minWeight = '" . (float)(isset($pvz['minWeight']) ? $pvz['minWeight'] : 0) . "',
			maxWeight = '" . (float)(isset($pvz['maxWeight']) ? $pvz['maxWeight'] : 0) . "',
			minPrice = '" . (float)(isset($pvz['minPrice']) ? $pvz['minPrice'] : 0) . "',
			maxPrice = '" . (float)(isset($pvz['maxPrice']) ? $pvz['maxPrice'] : 0) . "',
			restrictionWidth = '" . (float)(isset($pvz['restrictionWidth']) ? $pvz['restrictionWidth'] : 0) . "',
			restrictionLength = '" . (float)(isset($pvz['restrictionLength']) ? $pvz['restrictionLength'] : 0) . "',
			restrictionHeight = '" . (float)(isset($pvz['restrictionHeight']) ? $pvz['restrictionHeight'] : 0) . "',
			lat = '" . $this->db->escape($pvz['lat']) . "',
			`long` = '" . $this->db->escape($pvz['long']) . "',
			returnAvailable = '" . (int)$pvz['returnAvailable'] . "',
			partialGiveOutAvailable = '" . (int)$pvz['partialGiveOutAvailable'] . "',
			dangerousAvailable = '" . (int)$pvz['dangerousAvailable'] . "',
			isCashForbidden = '" . (int)$pvz['isCashForbidden'] . "',
			code = '" . $this->db->escape(isset($pvz['code']) ? $pvz['code'] : null) . "',
			wifiAvailable = '" . (int)$pvz['wifiAvailable'] . "',
			legalEntityNotAvailable = '" . (int)$pvz['legalEntityNotAvailable'] . "',
			isRestrictionAccess = '" . (int)$pvz['isRestrictionAccess'] . "',
			utcOffsetStr = '" . $this->db->escape($pvz['utcOffsetStr']) . "',
			isPartialPrepaymentForbidden = '" . (int)$pvz['isPartialPrepaymentForbidden'] . "',
			postalCode = '" . $this->db->escape(isset($pvz['howToGet']) ? $pvz['howToGet'] : null) . "',
			workingHours = '" . $this->db->escape($workingHours) . "'
		");
	}

	public function getCityByName($name) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ll_ozon_city` WHERE name LIKE '" . $this->db->escape($name) . "'");

		return ($query->num_rows ? $query->row['city_id'] : 0);
	}

	public function addCity($name) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "ll_ozon_city` SET name = '" . $this->db->escape($name) . "', country_id = 176");

		return $this->db->getLastId();
	}

	public function updateCity($city_id, $region_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ll_ozon_city` SET region_id = '" . (int)$region_id . "' WHERE city_id = '" . (int)$city_id . "'");
	}

	public function getRegion($name) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ll_ozon_region` WHERE name LIKE '" . $this->db->escape($name) . "'");

		return ($query->num_rows ? (int)$query->row['region_id'] : 0);
	}

	public function addRegion($region) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "ll_ozon_region` SET name = '" . $this->db->escape($region) . "', country_id = 176");

		return $this->db->getLastId();
	}

	public function clearData() {
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "ll_ozon_pvz`");
	}

	public function getApi($method, $params = []) {
		$curl = $this->curl($method, $params);
		$data = json_decode($curl['data'], true);

		if ($curl['info']['http_code'] >= 200 && $curl['info']['http_code'] < 300) {
			if (!isset($data['nextPageToken'])) {
				$data = isset($data['places']) ? $data['places'] : $data['data'];
			}

			return $data;
		}
	}

	protected function curl($method, $params = []) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->api->auth(), 'Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_URL, ($this->config->get($this->ll->getPrefix() . '_test') ? $this->statics->get('api_test_url') : $this->statics->get('api_url')) . '/' . $method . '?' . http_build_query($params));

		$result['data'] = curl_exec($ch);
		$result['info'] = curl_getinfo($ch);

		curl_close($ch);

		return $result;
	}
}

class ModelModuleLLOzonExchange extends ModelExtensionModuleLLOzonExchange {}
