<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
class ModelShippingLLOzon extends Model {
	private $m = 'll_ozon';
	private $api_auth_url = 'https://xapi.ozon.ru/principal-auth-api/connect/token';
	private $api_auth_url_test = 'https://api-stg.ozonru.me/principal-auth-api/connect/token';
	private $api_url = 'https://xapi.ozon.ru/principal-integration-api/v1/delivery/';
	private $api_url_test = 'https://api-stg.ozonru.me/principal-integration-api/v1/delivery/';

	public function getTotalCountries() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . $this->m . "_country`");

		return $query->row['total'];
	}

	public function getTotalRegions() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . $this->m . "_region`");

		return $query->row['total'];
	}

	public function getTotalCities() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . $this->m . "_city`");

		return $query->row['total'];
	}

	public function getTotalPvzs() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . $this->m . "_pvz`");

		return $query->row['total'];
	}

	public function getTotalPlaces() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . $this->m . "_place`");

		return $query->row['total'];
	}

	public function getTotalPikups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . $this->m . "_pickup`");

		return $query->row['total'];
	}

	public function getCountries() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_country`");

		return $query->rows;
	}

	public function getRegions($country_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_region` WHERE country_id = '" . (int)$country_id . "' ORDER BY name ASC");

		return $query->rows;
	}

	public function getRegion($name) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_region` WHERE name LIKE '" . $this->db->escape($name) . "'");

		return ($query->num_rows ? (int)$query->row['region_id'] : 0);
	}

	public function addRegion($region) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . $this->m . "_region` SET name = '" . $this->db->escape($region) . "', country_id = 176");

		return $this->db->getLastId();
	}

	public function getRegionById($region_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_region` WHERE region_id = '" . (int)$region_id . "'");

		return $query->row;
	}

	public function getRegionsToZones($country_id) {
		$data = [];
		$query = $this->db->query("SELECT region_id, name FROM `" . DB_PREFIX . $this->m . "_region` WHERE country_id = '" . (int)$country_id . "' ORDER BY name ASC");

		foreach ($query->rows as $region) {
			$zones_by_region = $this->getRegionsToZonesByRegionId($region['region_id']);
			$zones = [];

			if (!empty($zones_by_region)) {
				foreach ($zones_by_region as $zone) {
					$zones[] = $zone['zone_id'];
				}
			}

			$data[] = [
				'region_id' => $region['region_id'],
				'name'      => $region['name'],
				'zones'     => $zones,
			];
		}

		return $data;
	}

	public function getRegionsToZonesByRegionId($region_id) {
		$query = $this->db->query("SELECT zone_id FROM `" . DB_PREFIX . $this->m . "_region_to_zone` WHERE region_id = '" . (int)$region_id . "'");

		return $query->rows;
	}

	public function updateRegionToZone($region_id, $values) {
		$this->clearRegionToZone($region_id);

		foreach ($values as $zone_id) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . $this->m . "_region_to_zone` SET region_id = '" . (int)$region_id . "', zone_id = '" . (int)$zone_id . "'");
		}
	}

	public function clearRegionToZone($region_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . $this->m . "_region_to_zone` WHERE region_id = '" . (int)$region_id . "'");
	}

	public function getCities() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_city` ORDER BY name ASC");

		return $query->rows;
	}

	public function getNextCityById($city_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_city` WHERE city_id > '" . (int)$city_id . "' ORDER BY city_id ASC");

		return ($query->num_rows ? $query->row : 0);
	}

	public function getCity($name) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_city` WHERE name LIKE '" . $this->db->escape($name) . "'");

		return ($query->num_rows ? $query->row['city_id'] : 0);
	}

	public function prepareCity($name) {
		$result = $this->getCity($name);

		if (!$result && $name != '') {
			$this->addCity($name);
		}
	}

	public function addCity($name) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . $this->m . "_city` SET name = '" . $this->db->escape($name) . "', country_id = 176");

		return $this->db->getLastId();
	}

	public function updateCity($city_id, $region_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . $this->m . "_city` SET region_id = '" . (int)$region_id . "' WHERE city_id = '" . (int)$city_id . "'");
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

		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . $this->m . "_pvz` SET
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

	public function getPlaces() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->m . "_place`");

		return $query->rows;
	}

	public function addPlace($place) {
		if ($place['id'] != '') {
			$this->db->query("INSERT INTO `" . DB_PREFIX . $this->m . "_place` SET
				id = '" . $this->db->escape($place['id']) . "',
				name = '" . $this->db->escape($place['name']) . "',
				city = '" . $this->db->escape($place['city']) . "',
				address = '" . $this->db->escape($place['address']) . "',
				utcOffset = '" . $this->db->escape($place['utcOffset']) . "'
			");
		}
	}

	public function addPickup($pickup) {
		if ($pickup['id'] != '') {
			$this->db->query("INSERT INTO `" . DB_PREFIX . $this->m . "_pickup` SET
				id = '" . $this->db->escape($pickup['id']) . "',
				name = '" . $this->db->escape($pickup['name']) . "',
				city = '" . $this->db->escape($pickup['city']) . "',
				address = '" . $this->db->escape($pickup['address']) . "',
				storage = '" . $this->db->escape($pickup['storage']) . "'
			");
		}
	}

	protected function getApiToken() {
		$data = $this->getCache('token');

		if ($data && $data['expire'] > date('Y-m-d H:i:s')) {
			$token = $data['token'];
		} else {
			$params = [
				'grant_type'    => 'client_credentials',
				'client_id'     => $this->config->get($this->m . '_client_id'),
				'client_secret' => $this->config->get($this->m . '_client_secret'),
			];

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
			curl_setopt($ch, CURLOPT_URL, ($this->config->get($this->m . '_test') ? $this->api_auth_url_test : $this->api_auth_url));
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

			$result = json_decode(curl_exec($ch), true);

			curl_close($ch);

			if (isset($result['access_token'])) {
				$token = $result['access_token'];
				$date = new \DateTime('+' . $result['expires_in'] . ' seconds');
				$expire = $date->format('Y-m-d H:i:s');

				$this->addLog(1, 'token', $params, 'Успешное получение токена api');

				$this->setCache('token', '', ['token' => $token, 'expire' => $expire], true);
			} else {
				$this->addLog(0, 'token', $params, $result);

				return;
			}
		}

		return $token;
	}

	public function getApi($method, $params = []) {
		$curl = $this->curl($method, $params);
		$data = json_decode($curl['data'], true);

		if ($curl['info']['http_code'] < 200 || $curl['info']['http_code'] >= 300) {
			$this->addLog(0, $method, $params, $data);
		} else {
			if (!isset($data['nextPageToken'])) {
				$data = isset($data['places']) ? $data['places'] : $data['data'];
			}

			$this->addLog(1, $method, $params, count($data));

			return $data;
		}
	}

	public function curl($method, $params = []) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->getApiToken(), 'Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_URL, ($this->config->get($this->m . '_test') ? $this->api_url_test : $this->api_url) . $method . '?' . http_build_query($params));

		$result['data'] = curl_exec($ch);
		$result['info'] = curl_getinfo($ch);

		curl_close($ch);

		return $result;
	}

	protected function getCache($method, $postfix = '') {
		return $this->cache->get($this->m . '.' . $method . '.' . base64_encode($postfix));
	}

	protected function setCache($method, $postfix = '', $data) {
		$this->cache->set($this->m . '.' . $method . '.' . base64_encode($postfix), $data);
	}

	protected function addLog($type, $method, $request, $response = []) {
		if ($this->config->get($this->m . '_logging')) {
			switch ($type) {
				case 0:
					$type = 'error';
					break;
				case 1:
					$type = 'success';
					break;
				case 2:
					$type = 'info';
					break;
			}

			$this->log->write('[' . $this->m . '][' . $type . '][' . $method . '][request:' . serialize($request) . '][response:' . serialize($response) . ']');
		}
	}

	public function clearData() {
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . $this->m . "_pickup`");
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . $this->m . "_place`");
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . $this->m . "_pvz`");
	}

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->m . "_country` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`country_id` int(11) NOT NULL,
			`code` varchar(255) NOT NULL,
			`name` varchar(255) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("INSERT INTO `" . DB_PREFIX . $this->m . "_country` (`id`, `country_id`, `code`, `name`) VALUES
			(1, 176, 'RU', 'Россия'),
			(2, 20, 'BY', 'Беларусь'),
			(3, 109, 'KZ', 'Казахстан')"
		);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->m . "_region` (
			`region_id` int(11) NOT NULL AUTO_INCREMENT,
			`country_id` int(11) NOT NULL,
			`name` varchar(255) NOT NULL UNIQUE,
			PRIMARY KEY (`region_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->m . "_region_to_zone` (
			`region_id` int(11) NOT NULL,
			`zone_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("INSERT INTO `" . DB_PREFIX . $this->m . "_region_to_zone` (`region_id`, `zone_id`) VALUES
			(66, 2760),
			(69, 2738),
			(50, 2726),
			(5, 2724),
			(47, 2725),
			(29, 2794),
			(55, 2727),
			(13, 2730),
			(32, 2799),
			(24, 2801),
			(4, 2802),
			(41, 2803),
			(20, 2759),
			(3, 2741),
			(22, 2765),
			(44, 2763),
			(11, 2743),
			(70, 2736),
			(15, 2744),
			(21, 2733),
			(10, 2776),
			(56, 2747),
			(72, 2747),
			(71, 2747),
			(35, 2804),
			(6, 2787),
			(1, 2750),
			(17, 2751),
			(40, 2752),
			(77, 2731),
			(65, 2754),
			(43, 2755),
			(9, 2735),
			(42, 2757),
			(61, 2808),
			(27, 2782),
			(74, 2761),
			(46, 2722),
			(8, 2762),
			(33, 2766),
			(62, 2767),
			(48, 2768),
			(38, 2769),
			(31, 2771),
			(53, 2770),
			(45, 2773),
			(34, 2774),
			(57, 2777),
			(16, 2778),
			(54, 2779),
			(28, 2781),
			(73, 2785),
			(76, 2783),
			(25, 2783),
			(36, 2807),
			(19, 2798),
			(12, 2784),
			(18, 2786),
			(23, 2788),
			(26, 2746),
			(7, 2792),
			(58, 2789),
			(14, 2790),
			(37, 2793),
			(60, 2742),
			(51, 2795),
			(39, 2721),
			(52, 2749),
			(59, 2749),
			(30, 2732),
			(67, 2739),
			(64, 2731),
			(68, 2731),
			(63, 2780),
			(2, 2806)"
		);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->m . "_city` (
			`city_id` int(11) NOT NULL AUTO_INCREMENT,
			`region_id` int(11),
			`country_id` int(11) NOT NULL,
			`name` varchar(255) UNIQUE,
			PRIMARY KEY (`city_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->m . "_pvz` (
			`pvz_id` int(11) NOT NULL AUTO_INCREMENT,
			`city_id` int(11),
			`region_id` int(11),
			`external_id` varchar(255) NOT NULL UNIQUE,
			`objectTypeId` varchar(255),
			`objectTypeName` varchar(255),
			`name` varchar(255),
			`description` varchar(255),
			`address` varchar(255),
			`region` varchar(255),
			`settlement` varchar(255),
			`streets` varchar(255),
			`placement` varchar(255),
			`fittingShoesAvailable` tinyint(1),
			`fittingClothesAvailable` tinyint(1),
			`cardPaymentAvailable` tinyint(1),
			`howToGet` text,
			`phone` varchar(255),
			`minWeight` decimal(15,4),
			`maxWeight` decimal(15,4),
			`minPrice` decimal(15,4),
			`maxPrice` decimal(15,4),
			`restrictionWidth` decimal(15,4),
			`restrictionLength` decimal(15,4),
			`restrictionHeight` decimal(15,4),
			`lat` varchar(255),
			`long` varchar(255),
			`returnAvailable` tinyint(1),
			`partialGiveOutAvailable` tinyint(1),
			`dangerousAvailable` tinyint(1),
			`isCashForbidden` tinyint(1),
			`code` varchar(255),
			`wifiAvailable` tinyint(1),
			`legalEntityNotAvailable` tinyint(1),
			`isRestrictionAccess` tinyint(1),
			`utcOffsetStr` varchar(255),
			`isPartialPrepaymentForbidden` tinyint(1),
			`postalCode` varchar(255),
			`workingHours` text,
			PRIMARY KEY (`pvz_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->m . "_place` (
			`id` varchar(255) NOT NULL UNIQUE,
			`name` varchar(255) NOT NULL,
			`city` varchar(255),
			`address` varchar(255) NOT NULL,
			`utcOffset` varchar(255),
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->m . "_pickup` (
			`id` varchar(255) NOT NULL UNIQUE,
			`name` varchar(255) NOT NULL,
			`city` varchar(255),
			`address` varchar(255) NOT NULL,
			`storage` varchar(255),
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_total` MODIFY `title` VARCHAR(255);");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->m . "_country`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->m . "_region`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->m . "_region_to_zone`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->m . "_city`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->m . "_pvz`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->m . "_place`;");
	}

	public function checkUpdate() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->m . "_pickup` (
			`id` varchar(255) NOT NULL UNIQUE,
			`name` varchar(255) NOT NULL,
			`city` varchar(255),
			`address` varchar(255) NOT NULL,
			`storage` varchar(255),
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);
	}
}
