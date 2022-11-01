<?php
class ModelModuleGetcity extends Model {

	public function getZoneByVKZone($vkzone) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_vkzone WHERE name = '" . $this->db->escape($vkzone) . "' LIMIT 1");

		return $query->row;

	}
}