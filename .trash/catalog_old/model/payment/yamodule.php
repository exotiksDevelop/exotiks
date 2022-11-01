<?php
class ModelPaymentYamodule extends Model {
	public function getMethod($address, $total) {
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$this->load->language($for23.'payment/yamodule');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('worldpay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('yamodule_status')) {
			$status = false;
		//} elseif ($this->config->get('yamodule_total') > 0 && $this->config->get('yamodule_total') > $total) {
		//	$status = false;
		} elseif (!$this->config->get('yamodule_total_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
		    if($this->config->get('ya_kassa_active')) {
                $name = 'kassa_title';
            } else if($this->config->get('ya_p2p_active')) {
                $name = 'p2p_title';
            } else if($this->config->get('ya_fast_pay_active')) {
                $name = 'fast_pay_title';
            }

			$method_data = array(
				'code'       => 'yamodule',
				'title'      => $this->language->get($name),
				'terms'      => '',
				'sort_order' => $this->config->get('yamodule_total_sort_order')
			);
		}

		return $method_data;
	}
}

class ModelExtensionPaymentYamodule extends ModelPaymentYamodule {}