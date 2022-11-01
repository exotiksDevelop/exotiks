<?php
require_once('classbase.php');
class ControllerApiClasscustomer extends ControllerApiClassbase {

	public function index() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if ( !$this->checkOldVersion() )
				$this->loadAdminModel('customer/customer');

			$json['success'] = 'ok';

			$params = [
				'limit' => 1,
			];
			$params['start'] = ((isset($this->request->get['page'])) ? $this->request->get['page']:0) * $params['limit'];

			if ( $this->checkOldVersion() )
				$return = $this->getCustomers($params);
			else
				$return = $this->model_admin_customer_customer->getCustomers($params);

			$return[] = [
				"customer_id"		=>	'guest',
				"customer_group_id"	=>	"1",
				"store_id"			=>	(int)$this->config->get('config_store_id'),
				"firstname"			=>	"Guest",
				"lastname"			=>	"",
				"email"				=>	"",
				"telephone"			=>	"",
				"fax"				=>	"",
				"newsletter"		=>	"0",
				"address_id"		=>	"",
				"custom_field"		=>	"",
				"status"			=>	"1",
				"approved"			=>	"1",
				"safe"				=>	"0",
				"date_added"		=>	"2016-06-24 16:57:36",
			];
			$json['items'] = $return;

		}

		$this->JSON = $json;
	}

	public function get() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {

			if ( !$this->checkOldVersion() )
				$this->loadAdminModel('customer/customer');

			if ((int)$this->request->get['customer_id'] == 0) {
				$json['items'] = [
					"customer_id"		=>	'guest',
					"customer_group_id"	=>	"1",
					"store_id"			=>	(int)$this->config->get('config_store_id'),
					"firstname"			=>	"Guest",
					"lastname"			=>	"",
					"email"				=>	"",
					"telephone"			=>	"",
					"fax"				=>	"",
					"newsletter"		=>	"0",
					"address_id"		=>	"",
					"custom_field"		=>	"",
					"status"			=>	"1",
					"approved"			=>	"1",
					"safe"				=>	"0",
					"date_added"		=>	"2016-06-24 16:57:36",
				];

			} else {
				if ( $this->checkOldVersion() )
					$json['items'] = $this->getCustomer($this->request->get['customer_id']);
				else
					$json['items'] = $this->model_admin_customer_customer->getCustomer($this->request->get['customer_id']);
				
			}

			
		}

		$this->JSON = $json;
	}

	protected function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	protected function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.approved',
			'c.ip',
			'c.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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


}