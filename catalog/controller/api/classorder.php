<?php
require_once('classbase.php');
class ControllerApiClassorder extends ControllerApiClassbase {

	public function index() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('sale/order');

			$json['success'] = 'ok';

			$return = [];
			$params = [
				'limit' => 100,
			];
			$params['start'] = ((isset($this->request->get['page'])) ? $this->request->get['page']:0) * $params['limit'];
			$params['filter_date_modified_start'] = date('Y-m-d', strtotime($this->request->get['from_date_modifed']));
			$json['total'] = (int)$this->getTotalOrders($params);
			foreach ($this->getOrders($params) as $value) {

				$items = [];
				foreach ( $this->model_admin_sale_order->getOrderProducts($value['order_id']) as $val) {
					$val['options'] = $this->model_admin_sale_order->getOrderOptions($value['order_id'], $val['order_product_id']);
					// print_r($val);
					$items[] = $val;
				}
				$order = $this->getOrderInfo((int)$value['order_id']);
				if ($order['customer_id'] == '0') $order['customer_id'] = 'guest';
				$order['ship_price'] = (array)$this->db->query("SELECT value FROM " . DB_PREFIX . "order_total WHERE order_id= '" . $order['order_id'] . "' AND code='shipping' LIMIT 1");
				$order['ship_price'] = (int)$order['ship_price']['row']['value'];
				$order['items'] = $items;
				$return[] = $order;
			}

			$json['items'] = $return;

		}

		$this->JSON = $json;
	}

	public function get() {
		$this->disableError();
		$this->load->language('api/class');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('sale/order');

			$json['success'] = 'ok';

			$return = [];
				
			$items = [];
			foreach ( $this->model_admin_sale_order->getOrderProducts($this->request->get['order_id']) as $val) {
				$val['options'] = $this->model_admin_sale_order->getOrderOptions($val['order_id'], $val['order_product_id']);
				// print_r($val);
				$items[] = $val;
			}
			$order = $this->getOrderInfo((int)$this->request->get['order_id']);
			$order['ship_price'] = (array)$this->db->query("SELECT value FROM " . DB_PREFIX . "order_total WHERE order_id= '" . $order['order_id'] . "' AND code='shipping' LIMIT 1");
				$order['ship_price'] = (int)$order['ship_price']['row']['value'];
			if (empty($order)) {
				$json['error'] = 'fail';
				$json['msg'] = $this->language->get('not_found');
			} else {
				if ($order['customer_id'] == '0') $order['customer_id'] = 'guest';
				$order['items'] = $items;
				$json['items'] = $order;
			}

		}

		$this->JSON = $json;
	}

	public function statuses() {
		$this->disableError();
		$this->load->language('api/class');

		$json['success'] = 'ok';
		$statuses = (array)$this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE language_id= '" . $this->config->get('config_language_id')."'");
		foreach ($statuses['rows'] as $value) {
			$json['items'][] = $value['name'];
		}

		$this->JSON = $json;
	}

	protected function getOrderInfo($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}
			
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			/*$coupon = (array)$this->db->query("SELECT ch.amount FROM ".DB_PREFIX."coupon_history  ch
				WHERE ch.order_id = '".(int)$order_id ."'")->row;

			$voucher = (array)$this->db->query("SELECT v.amount FROM ".DB_PREFIX."voucher_history  vh
				LEFT JOIN
				".DB_PREFIX."voucher v ON vh.voucher_id = v.voucher_id
				WHERE vh.order_id = '".(int)$order_id ."'")->row;*/

			$price_change = (float)$this->db->query("SELECT ABS( SUM( value ) ) AS amount FROM ".DB_PREFIX."order_total where order_id = ".(int)$order_id ." AND code NOT IN ( 'total', 'sub_total', 'shipping') ")->row['amount'];

			return array(
				'order_id'					=> $order_query->row['order_id'],
				'invoice_no'				=> $order_query->row['invoice_no'],
				'invoice_prefix'			=> $order_query->row['invoice_prefix'],
				'store_id'					=> $order_query->row['store_id'],
				'store_name'				=> $order_query->row['store_name'],
				'store_url'					=> $order_query->row['store_url'],
				'customer_id'				=> $order_query->row['customer_id'],
				'customer'					=> $order_query->row['customer'],
				'customer_group_id'			=> $order_query->row['customer_group_id'],
				'firstname'					=> $order_query->row['firstname'],
				'lastname'					=> $order_query->row['lastname'],
				'email'						=> $order_query->row['email'],
				'telephone'					=> $order_query->row['telephone'],
				'fax'						=> $order_query->row['fax'],
				'custom_field'				=> json_decode($order_query->row['custom_field'], true),
				'payment_firstname'			=> $order_query->row['payment_firstname'],
				'payment_lastname'			=> $order_query->row['payment_lastname'],
				'payment_company'			=> $order_query->row['payment_company'],
				'payment_address_1'			=> $order_query->row['payment_address_1'],
				'payment_address_2'			=> $order_query->row['payment_address_2'],
				'payment_postcode'			=> $order_query->row['payment_postcode'],
				'payment_city'				=> $order_query->row['payment_city'],
				'payment_zone_id'			=> $order_query->row['payment_zone_id'],
				'payment_zone'				=> $order_query->row['payment_zone'],
				'payment_zone_code'			=> $payment_zone_code,
				'payment_country_id'		=> $order_query->row['payment_country_id'],
				'payment_country'			=> $order_query->row['payment_country'],
				'payment_iso_code_2'		=> $payment_iso_code_2,
				'payment_iso_code_3'		=> $payment_iso_code_3,
				'payment_address_format'	=> $order_query->row['payment_address_format'],
				'payment_custom_field'		=> json_decode($order_query->row['payment_custom_field'], true),
				'payment_method'			=> $order_query->row['payment_method'],
				'payment_code'				=> $order_query->row['payment_code'],
				'shipping_firstname'		=> $order_query->row['shipping_firstname'],
				'shipping_lastname'			=> $order_query->row['shipping_lastname'],
				'shipping_company'			=> $order_query->row['shipping_company'],
				'shipping_address_1'		=> $order_query->row['shipping_address_1'],
				'shipping_address_2'		=> $order_query->row['shipping_address_2'],
				'shipping_postcode'			=> $order_query->row['shipping_postcode'],
				'shipping_city'				=> $order_query->row['shipping_city'],
				'shipping_zone_id'			=> $order_query->row['shipping_zone_id'],
				'shipping_zone'				=> $order_query->row['shipping_zone'],
				'shipping_zone_code'		=> $shipping_zone_code,
				'shipping_country_id'		=> $order_query->row['shipping_country_id'],
				'shipping_country'			=> $order_query->row['shipping_country'],
				'shipping_iso_code_2'		=> $shipping_iso_code_2,
				'shipping_iso_code_3'		=> $shipping_iso_code_3,
				'shipping_address_format'	=> $order_query->row['shipping_address_format'],
				'shipping_custom_field'		=> json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'			=> $order_query->row['shipping_method'],
				'shipping_code'				=> $order_query->row['shipping_code'],
				'comment'					=> $order_query->row['comment'],
				'total'						=> $order_query->row['total'],
				'reward'					=> $reward,
				'order_status_id'			=> $order_query->row['order_status_id'],
				'order_status'				=> $order_query->row['order_status'],
				'affiliate_id'				=> $order_query->row['affiliate_id'],
				'affiliate_firstname'		=> $affiliate_firstname,
				'affiliate_lastname'		=> $affiliate_lastname,
				'commission'				=> $order_query->row['commission'],
				'language_id'				=> $order_query->row['language_id'],
				'language_code'				=> $language_code,
				'currency_id'				=> $order_query->row['currency_id'],
				'currency_code'				=> $order_query->row['currency_code'],
				'currency_value'			=> $order_query->row['currency_value'],
				'ip'						=> $order_query->row['ip'],
				'forwarded_ip'				=> $order_query->row['forwarded_ip'],
				'user_agent'				=> $order_query->row['user_agent'],
				'accept_language'			=> $order_query->row['accept_language'],
				'date_added'				=> $order_query->row['date_added'],
				'date_modified'				=> $order_query->row['date_modified'],
				// 'coupon'					=> $coupon,
				// 'voucher'					=> $voucher,
				'price_change'				=> $price_change
			);
		} else {
			return;
		}
	}

	protected function getOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_date_modified_start'])) {
			$sql .= " AND DATE(o.date_modified) >= DATE('" . $this->db->escape($data['filter_date_modified_start']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'order_status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_date_modified_start'])) {
			$sql .= " AND DATE(date_modified) >= DATE('" . $this->db->escape($data['filter_date_modified_start']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}