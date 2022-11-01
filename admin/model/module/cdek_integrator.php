<?php
class ModelModuleCdekIntegrator extends Model {
	
	public function getOrders($data = array()) {
		
		$sql  = "SELECT o.order_id, ";
		$sql .= "CONCAT(o.firstname, ' ', o.lastname) AS customer, ";
		$sql .= "(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, ";
		$sql .= "o.total, ";
		$sql .= "o.currency_code, ";
		$sql .= "o.currency_value, ";
		$sql .= "o.date_added, ";
		$sql .= "o.date_modified ";
		$sql .= "FROM `" . DB_PREFIX . "order` o";
		
		if ($conditions = $this->getOrderConditions($data)) {
			$sql .= " WHERE " . implode(" AND ", $conditions);
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total',
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
		
		$sql  = "SELECT COUNT(*) as total ";
		$sql .= "FROM `" . DB_PREFIX . "order` o";
		
		if ($conditions = $this->getOrderConditions($data)) {
			$sql .= " WHERE " . implode(" AND ", $conditions);
		}
		
		return $this->db->query($sql)->row['total'];
		
	}
	
	private function getOrderConditions($data) {
		
		$conditions = array();

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			
			if (is_array($data['filter_order_status_id'])) {
				$conditions[] = "o.order_status_id IN (" . implode(',', $data['filter_order_status_id']) . ")";
			} else {
				$conditions[] = "o.order_status_id = " . (int)$data['filter_order_status_id'] . "";
			}
			
		}
		
		if (isset($data['filter_dispatch'])) {
			$conditions[] = "(SELECT co.order_id FROM `" . DB_PREFIX . "cdek_order` co WHERE o.order_id = co.order_id LIMIT 1) IS NULL";
		}
		
		if (isset($data['filter_dispatch_number'])) {
			$conditions[] = "dispatch_number = '" . $this->db->escape($data['filter_dispatch_number']) . "'";
		}
		
		if (!empty($data['filter_order_id'])) {
			$conditions[] = "o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (!empty($data['filter_new_order'])) {
			$conditions[] = "DATEDIFF(CURRENT_DATE(), o.date_added) <= '" . (int)$data['filter_new_order'] . "'";
		}
		
		if (!empty($data['filter_shipping'])) {
			
			$shipping_condition = array();
			
			if (is_array($data['filter_shipping'])) {
				$shipping_code = $data['filter_shipping'];
			} else {
				$shipping_code = array($data['filter_shipping']);
			}
			
			foreach ($shipping_code as $code) {
				$shipping_condition[] = "LCASE(o.shipping_code) LIKE '" . $this->db->escape($code) . "%'";
			}
			
			if (!empty($shipping_condition)) {
				$conditions[] = "(" . implode(" OR ", $shipping_condition) . ")";
			}
		}
		
		if (!empty($data['filter_payment'])) {
			
			$pyament_condition = array();
			
			if (is_array($data['filter_payment'])) {
				$payment_code = $data['filter_payment'];
			} else {
				$payment_code = array($data['filter_payment']);
			}
			
			$conditions[] = "LCASE(o.payment_code) IN ('" . implode("', '", $payment_code) . "')"/*"(" . implode(' OR ', $pyament_condition) . ")"*/;				
		}

		if (!empty($data['filter_customer'])) {
			$conditions[] = "LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_customer'])) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$conditions[] = "DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$conditions[] = "ROUND(o.total,0) >= '" . round((float)$data['filter_total']) . "'";
		}
		
		if (!empty($data['filter_new_order']) && is_numeric($data['filter_new_order']) && $data['filter_new_order'] > 0) {
			$conditions[] = "DATEDIFF(CURDATE(), DATE(o.date_added)) < " . (int)$this->db->escape($data['filter_new_order']);
		}
		
		return $conditions;
	}
	
	public function getOrderProducts($order_id) {
		
		$sql  = "SELECT op.*, ";
		$sql .= "p.weight, ";
		$sql .= "p.weight_class_id ";
		$sql .= "FROM " . DB_PREFIX . "order_product op ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id) ";
		$sql .= "WHERE op.order_id = '" . (int)$order_id . "'";
		
		return $this->db->query($sql)->rows;
	}
	
	public function getOrderProductOptions($order_product_id) {
		
		$sql  = "SELECT oo.*, ";
		$sql .= "pov.weight, ";
		$sql .= "pov.weight_prefix ";
		$sql .= "FROM `" . DB_PREFIX . "order_option` oo ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (oo.product_option_value_id = pov.product_option_value_id) ";
		//$sql .= "product_option_value pov
		$sql .= "WHERE oo.order_product_id = '" . (int)$order_product_id . "'";
		
		return $this->db->query($sql)->rows;
	}
	
	public function getCity($name = '') {
		
		if (!$name) return FALSE;
		
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_city` WHERE LCASE(name) LIKE '" . $name . "%'")->rows;
	}
	
	public function addDispatch($data = array()) {
		
		if (!$data) return FALSE;
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "cdek_dispatch` SET `dispatch_number` = '" . $this->db->escape($data['number']) . "', `date` = '" . $this->db->escape($data['date']) . "', `server_date` = '" . $this->db->escape(time()) . "'");
	
		$dispatch_id = $this->db->getLastId();
		
		if (!empty($data['orders'])) {
		
			foreach ($data['orders'] as $order_info) {
				
				$order_id = (int)$order_info['order_id'];
				
				$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order` SET ";
				$sql .= "`order_id` = " . $order_id . ", ";
				$sql .= "`dispatch_id` = " . (int)$dispatch_id . ", ";
				
				if (!empty($order_info['city_postcode'])) {
					$sql .= "`act_number` = '" . $order_info['act_number'] . "', ";
				}
				
				$sql .= "`dispatch_number` = '" . $order_info['dispatch_number'] . "', ";
				$sql .= "`city_id` = " . (int)$order_info['city_id'] . ", ";
				$sql .= "`city_name` = '" . $this->db->escape($order_info['city_name']) . "', ";
				
				if (!empty($order_info['city_postcode'])) {
					$sql .= "`city_postcode` = '" . $this->db->escape($order_info['city_postcode']) . "', ";
				}
				
				$sql .= "`recipient_city_id` = " . (int)$order_info['recipient_city_id'] . ", ";
				$sql .= "`recipient_city_name` = '" . $this->db->escape($order_info['recipient_city_name']) . "', ";
				
				if (!empty($order_info['recipient_city_postcode'])) {
					$sql .= "`recipient_city_postcode` = '" . $this->db->escape($order_info['recipient_city_postcode']) . "', ";
				}
				
				$sql .= "`recipient_name` = '" . $this->db->escape($order_info['recipient_name']) . "', ";
				$sql .= "`recipient_email` = '" . $this->db->escape($order_info['recipient_email']) . "', ";
				$sql .= "`phone` = '" . $this->db->escape($order_info['recipient_telephone']) . "', ";
				$sql .= "`tariff_id` = " . (int)$order_info['tariff_id'] . ", ";
				$sql .= "`mode_id` = " . (int)$order_info['mode_id'] . ", ";
				$sql .= "`reason_id` = 0, "; // Доп статус
				$sql .= "`status_id` = " . (int)$order_info['status_id'] . ", ";
				$sql .= "`delivery_recipient_cost` = " . (float)$order_info['delivery_recipient_cost'] . ", ";
				$sql .= "`currency` = '" . $this->db->escape($order_info['currency']) . "', ";
				$sql .= "`currency_cod` = '" . $this->db->escape($order_info['currency_cod']) . "', ";
				$sql .= "`comment` = '" . $this->db->escape($order_info['cdek_comment']) . "', ";
				$sql .= "`seller_name` = '" . $this->db->escape($order_info['seller_name']) . "', ";
				$sql .= "`address_street` = '" . $this->db->escape($order_info['address']['street']) . "', ";
				$sql .= "`address_house` = '" . $this->db->escape($order_info['address']['house']) . "', ";
				$sql .= "`address_flat` = '" . $this->db->escape($order_info['address']['flat']) . "', ";
				$sql .= "`address_pvz_code` = '" . $this->db->escape($order_info['address']['pvz_code']) . "',";
				$sql .= "`last_exchange` = '" . time() . "'";
				
				if (!empty($order_info['delivery_cost']) && (float)$order_info['delivery_cost']) {
					$sql .= ", `delivery_cost` = '" . (float)$order_info['delivery_cost'] . "'";
				}
				
				if (!empty($order_info['delivery_last_change'])) {
					$sql .= ", `delivery_last_change` = '" . (float)$order_info['delivery_last_change'] . "'";
				}
				
				$this->db->query($sql);
				
				// Change order status
				$this->changeOrderStatus($order_info['status_id'], $this->getDispatchInfo($order_id));
				
				$status_history = reset($order_info['status_history']);
				
				foreach ($order_info['status_history'] as $status_info) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_status_history` SET ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`date` = '" . $this->db->escape($status_info['date']) . "', ";
					$sql .= "`status_id` = " . (int)$status_info['status_id'] . ", ";
					$sql .= "`description` = '" . $this->db->escape($status_info['description']) . "', ";
					$sql .= "`city_id` = " . (int)$status_info['city_code'] . ", ";
					$sql .= "`city_name` = '" . $this->db->escape($status_info['city_name']) . "';";
					
					$this->db->query($sql);
					
				}
				
				foreach ($order_info['package'] as $package_id => $package_info) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_package` SET ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`number` = " . (int)$package_id . ", ";
					$sql .= "`brcode` = '" . ($package_info['brcode'] != '' ? $package_info['brcode'] : (int)$package_id) . "', ";
					$sql .= "`weight` = " . (float)$package_info['weight'] . ", ";
					$sql .= "`size_a` = " . ($package_info['pack'] ? $package_info['size_a'] : 'NULL') . ", ";
					$sql .= "`size_b` = " . ($package_info['pack'] ? $package_info['size_b'] : 'NULL') . ", ";
					$sql .= "`size_c` = " . ($package_info['pack'] ? $package_info['size_c'] : 'NULL') . ";";
					
					$this->db->query($sql);
				
					$package_id = $this->db->getLastId();
					
					foreach ($package_info['item'] as $item_row => $item_info) {
						
						$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_package_item` SET ";
						$sql .= "`package_id` = " . $package_id . ", ";
						$sql .= "`order_id` = " . (int)$order_id . ", ";
						$sql .= "`ware_key` = " . (int)$item_info['ware_key'] . ", ";
						$sql .= "`comment` = '" . $this->db->escape($item_info['comment']) . "', ";
						$sql .= "`weight` = " . (float)$item_info['weight'] . ", ";
						$sql .= "`amount` = " . (int)$item_info['amount'] . ", ";
						$sql .= "`cost` = " . (float)$item_info['cost'] . ", ";
						$sql .= "`payment` = " . (int)$item_info['payment'] . ";";
						
						$this->db->query($sql);
						
					}
					
				}
				
				if ($order_info['courier']['call']) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_courier` SET ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`date` = '" . $this->db->escape(strtotime($order_info['courier']['date'])) . "', ";
					$sql .= "`time_beg` = '" . $order_info['courier']['time_beg'] . "', ";
					$sql .= "`time_end` = '" . $order_info['courier']['time_end'] . "', ";
					$sql .= "`lunch_beg` = " . ($order_info['courier']['lunch_beg'] ? "'" . $order_info['courier']['lunch_beg'] . "'" : "NULL") . ", ";
					$sql .= "`lunch_end` = " . ($order_info['courier']['lunch_end'] ? "'" . $order_info['courier']['lunch_end'] . "'" : "NULL") . ", ";
					$sql .= "`city_id` = " . (int)$order_info['courier']['city_id'] . ", ";
					$sql .= "`city_name` = '" . $this->db->escape($order_info['courier']['city_name']) . "', ";
					$sql .= "`send_phone` = '" . $this->db->escape($order_info['courier']['send_phone']) . "', ";
					$sql .= "`sender_name` = '" . $this->db->escape($order_info['courier']['sender_name']) . "', ";
					$sql .= "`address_street` = '" . $this->db->escape($order_info['courier']['street']) . "', ";
					$sql .= "`address_house` = '" . $this->db->escape($order_info['courier']['house']) . "', ";
					$sql .= "`address_flat` = '" . $this->db->escape($order_info['courier']['flat']) . "', ";
					$sql .= "`comment` = '" . $this->db->escape($order_info['courier']['comment']) . "';";
					
					$this->db->query($sql);
					
				}
				
				if (!empty($order_info['schedule'])) {
					
					foreach ($order_info['schedule'] as $attempt_id => $attempt_info) {
						
						$sql = "INSERT INTO `" . DB_PREFIX . "cdek_order_schedule` SET ";
						$sql .= "`attempt_id` = " . ((int)$order_id . (int)$attempt_id) . ", ";
						$sql .= "`order_id` = " . (int)$order_id . ", ";
						$sql .= "`date` = '" . $this->db->escape(strtotime($attempt_info['date'])) . "', ";
						$sql .= "`time_beg` = '" . $attempt_info['time_beg'] . "', ";
						$sql .= "`time_end` = '" . $attempt_info['time_end'] . "', ";
						$sql .= "`phone` = '" . $this->db->escape($attempt_info['phone']) . "', ";
						$sql .= "`recipient_name` = '" . $this->db->escape($attempt_info['recipient_name']) . "', ";
						$sql .= "`address_street` = '" . $this->db->escape($attempt_info['street']) . "', ";
						$sql .= "`address_house` = '" . $this->db->escape($attempt_info['house']) . "', ";
						$sql .= "`address_flat` = '" . $this->db->escape($attempt_info['flat']) . "', ";
						$sql .= "`address_pvz_code` = '" . $this->db->escape($attempt_info['pvz_code']) . "', ";
						$sql .= "`comment` = '" . $this->db->escape($attempt_info['comment']) . "';";
						
						$this->db->query($sql);
						
					}
					
				}
				
				if (!empty($order_info['add_service'])) {
					
					foreach ($order_info['add_service'] as $service_id => $service_info) {
						
						$sql = "INSERT INTO `" . DB_PREFIX . "cdek_order_add_service` SET ";
						$sql .= "`service_id` = " . $service_info['service_id'] . ", ";
						$sql .= "`order_id` = " . (int)$order_id;
						
						if (!empty($service_info['description'])) {
							$sql .= ", `description` = '" . $this->db->escape($service_info['description']) .  "'";
						}
						
						if (!empty($service_info['price']) && (float)$service_info['price']) {
							$sql .= ", `price` = '" . (float)$service_info['price'] .  "'";
						}
						
						
						$this->db->query($sql);
					}
					
				}
				
			}
		
		}
	}
	
	public function editDispatch($order_id, $data) {
		
		if ($dispatch_info = $this->getDispatchInfo($order_id)) 
		{
			
			$sql  = "UPDATE `" . DB_PREFIX . "cdek_order` SET last_exchange = " . time();

			if (!empty($data['status_id'])) {
				
				$sql .= ", status_id = " . (int)$data['status_id'];
				// Change order status
				$this->changeOrderStatus($data['status_id'], $dispatch_info);
			}
			
			if (!empty($data['reason_id'])) {
				$sql .= ", reason_id = " . (int)$data['reason_id'];
			}
			
			if (!empty($data['act_number'])) {
				$sql .= ", act_number = '" . $this->db->escape($data['act_number']) . "'";
			}
			
			if (!empty($data['delay_id'])) {
				$sql .= ", delay_id = " . (int)$data['delay_id'];
			}
			
			if (!empty($data['delivery_cost'])) {
				$sql .= ", delivery_cost = " . (float)$data['delivery_cost'];
			}
			
			if (isset($data['cod'])) {
				$sql .= ", cod = " . (float)$data['cod'];
			}
			
			if (isset($data['cod_fact'])) {
				$sql .= ", cod_fact = " . (float)$data['cod_fact'];
			}
			
			if (!empty($data['city_postcode'])) {
				$sql .= ", city_postcode = '" . $this->db->escape($data['city_postcode']) . "'";
			}
			
			if (!empty($data['delivery_date'])) {
				$sql .= ", delivery_date = '" . $this->db->escape($data['delivery_date']) . "'";
			}
			
			if (!empty($data['delivery_recipient_name'])) {
				$sql .= ", delivery_recipient_name = '" . $this->db->escape($data['delivery_recipient_name']) . "'";
			}
			
			if (!empty($data['recipient_city_postcode'])) {
				$sql .= ", recipient_city_postcode = '" . $this->db->escape($data['recipient_city_postcode']) . "'";
			}
			
			$sql .= " WHERE order_id = " . (int)$order_id;
			
			$this->db->query($sql);
			
			if (!empty($data['status_history'])) {
				
				$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_status_history` WHERE order_id = '" . (int)$order_id . "'");
				
				foreach ($data['status_history'] as $status_info) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_status_history` SET ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`date` = '" . $this->db->escape($status_info['date']) . "', ";
					$sql .= "`status_id` = " . (int)$status_info['status_id'] . ", ";
					$sql .= "`description` = '" . $this->db->escape($status_info['description']) . "', ";
					$sql .= "`city_id` = " . (int)$status_info['city_code'] . ", ";
					$sql .= "`city_name` = '" . $this->db->escape($status_info['city_name']) . "';";
					
					$this->db->query($sql);
					
				}				
				
			}
			
			if (!empty($data['reason_history'])) {
				
				$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_reason` WHERE  order_id = '" . (int)$order_id . "'");
				
				foreach ($data['reason_history'] as $reason_info) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_reason` SET ";
					$sql .= "`reason_id` = " . (int)$reason_info['reason_id'] . ", ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`date` = '" . $this->db->escape($reason_info['date']) . "', ";
					$sql .= "`description` = '" . $this->db->escape($reason_info['description']) . "';";
					
					$this->db->query($sql);
					
				}
				
			}
			
			if (!empty($data['delay_history'])) {
				
				$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_delay_history` WHERE order_id = '" . (int)$order_id . "'");
				
				foreach ($data['delay_history'] as $delay_info) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_delay_history` SET ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`delay_id` = " . (int)$delay_info['delay_id'] . ", ";
					$sql .= "`date` = '" . $this->db->escape($delay_info['date']) . "', ";
					$sql .= "`description` = '" . $this->db->escape($delay_info['description']) . "';";
					
					$this->db->query($sql);
					
				}
				
			}
			
			if (!empty($data['attempt'])) {
				
				$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_schedule_delay` WHERE order_id = '" . (int)$order_id . "'");
				
				foreach ($data['attempt'] as $attempt_info) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_schedule_delay` SET ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`attempt_id` = " . (int)$attempt_info['attempt_id'] . ", ";
					$sql .= "`delay_id` = " . (int)$attempt_info['delay_id'] . ", ";
					$sql .= "`description` = '" . $this->db->escape($attempt_info['description']) . "';";
					
					$this->db->query($sql);
					
				}
				
			}
			
			if (!empty($data['call']['good'])) {
				
				$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_call_history_good` WHERE order_id = '" . (int)$order_id . "'");
				
				foreach ($data['call']['good'] as $call_info) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_call_history_good` SET ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`date` = '" . $this->db->escape($call_info['date']) . "', ";
					$sql .= "`date_deliv` = '" . $this->db->escape($call_info['date_deliv']) . "';";
					
					$this->db->query($sql);
					
				}
				
			}
			
			if (!empty($data['call']['fail'])) {
				
				$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_call_history_fail` WHERE order_id = '" . (int)$order_id . "'");
				
				foreach ($data['call']['fail'] as $call_info) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_call_history_fail` SET ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`fail_id` = " . (int)$attempt_info['fail_id'] . ", ";
					$sql .= "`date` = '" . $this->db->escape($call_info['date']) . "', ";
					$sql .= "`description` = '" . $this->db->escape($call_info['description']) . "';";
					
					$this->db->query($sql);
					
				}
				
			}
			
			if (!empty($data['call']['delay'])) {
				
				$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_call_history_delay` WHERE order_id = '" . (int)$order_id . "'");
				
				foreach ($data['call']['delay'] as $call_info) {
					
					$sql  = "INSERT INTO `" . DB_PREFIX . "cdek_order_call_history_delay` SET ";
					$sql .= "`order_id` = " . (int)$order_id . ", ";
					$sql .= "`date` = '" . $this->db->escape($call_info['date']) . "', ";
					$sql .= "`date_next` = '" . $this->db->escape($call_info['date_next']) . "';";
					
					$this->db->query($sql);
					
				}
				
			}
			
		} else {
			return FALSE;
		}
		
	}
	
	public function orderExists($order_id) {
		return $this->db->query("SELECT COUNT(*) total FROM `" . DB_PREFIX . "cdek_order` WHERE order_id = " . (int)$order_id)->row['total'];
	}
	
	public function getDispatchInfo($order_id) {
		
		$sql  = "SELECT o.*, ";
		$sql .= "o.dispatch_number number, ";
		$sql .= "d.date, ";
		$sql .= "d.server_date, ";
		$sql .= "d.dispatch_id, ";
		$sql .= "d.dispatch_number, ";
		$sql .= "os.status_id, ";
		$sql .= "os.date status_date, ";
		$sql .= "os.description status_description, ";
		$sql .= "os.city_id status_city_id, ";
		$sql .= "os.city_name status_city_name, ";
		$sql .= "(SELECT cor.description FROM `" . DB_PREFIX . "cdek_order_reason` cor WHERE cor.reason_id = o.reason_id AND cor.order_id = o.order_id) reason_status, ";
		$sql .= "od.date delay_date, ";
		$sql .= "od.description delay_description ";
		$sql .= "FROM `" . DB_PREFIX . "cdek_order` o ";
		$sql .= "INNER JOIN `" . DB_PREFIX . "cdek_dispatch` d ON (o.dispatch_id = d.dispatch_id)";
		//$sql .= "LEFT JOIN `" . DB_PREFIX . "cdek_order_status_history` os ON (o.status_id = os.status_id)";
		$sql .= "LEFT JOIN (SELECT * FROM `" . DB_PREFIX . "cdek_order_status_history` ORDER BY date DESC LIMIT 1) os ON (o.order_id = os.order_id AND o.status_id = os.status_id) ";
		$sql .= "LEFT JOIN (SELECT * FROM `" . DB_PREFIX . "cdek_order_delay_history` ORDER BY date DESC LIMIT 1) od ON (o.order_id = od.order_id AND o.delay_id = od.delay_id) ";
		$sql .= "WHERE o.order_id = " . (int)$order_id;
		
		return $this->db->query($sql)->row;
	}
	
	public function deleteDispatch($order_id) {
		
		$dispatch_info = $this->getDispatchInfo($order_id);
		
		if ($dispatch_info) {
			
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_add_service` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_call` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_call_history_delay` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_call_history_fail` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_call_history_good` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_courier` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_delay_history` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_package` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_package_item` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_reason` WHERE  order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_schedule` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_schedule_delay` WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_order_status_history` WHERE order_id = '" . (int)$order_id . "'");
			
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cdek_dispatch` WHERE dispatch_id = '" . (int)$dispatch_info['dispatch_id'] . "' AND (SELECT COUNT(*) FROM `" . DB_PREFIX . "cdek_order` WHERE dispatch_id = '" . (int)$dispatch_info['dispatch_id'] . "') = 0");
			
		}
		
	}
	
	public function getDispatchList($data = array()) {
		
		$sql  = "SELECT o.*, ";
		$sql .= "d.date, ";
		$sql .= "os.status_id, ";
		$sql .= "os.date as status_date, ";
		$sql .= "os.description as status_description, ";
		$sql .= "os.city_id as status_city_id, ";
		$sql .= "os.city_name as status_city_name, ";
		$sql .= "od.description as delay_status ";
		$sql .= "FROM `" . DB_PREFIX . "cdek_order` o ";
		$sql .= "INNER JOIN `" . DB_PREFIX . "cdek_dispatch` d ON (o.dispatch_id = d.dispatch_id)";
		$sql .= "LEFT JOIN (SELECT * FROM `" . DB_PREFIX . "cdek_order_status_history` ORDER BY date DESC) os ON (o.order_id = os.order_id AND o.status_id = os.status_id) ";
		$sql .= "LEFT JOIN (SELECT * FROM `" . DB_PREFIX . "cdek_order_delay_history` ORDER BY date DESC) od ON (o.order_id = od.order_id AND o.delay_id = od.delay_id) ";
		
		$filter = array();
		
		if (!empty($data['filter_order_id'])) {
			$filter[] = "o.order_id = " . (int)$data['filter_order_id'];
		}
		
		if (!empty($data['filter_dispatch_number'])) {
			$filter[] = "o.dispatch_number = " . (int)$data['filter_dispatch_number'];
		}
		
		if (!empty($data['filter_act_number'])) {
			$filter[] = "o.act_number = '" . $this->db->escape($data['filter_act_number']) . "'";
		}
		
		if (!empty($data['filter_date'])) {
			$filter[] = "DATE(FROM_UNIXTIME(d.date)) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		}
		
		if (!empty($data['filter_city_from'])) {
			$filter[] = "o.city_name LIKE '" . $this->db->escape($data['filter_city_from']) . "%'";
		}
		
		if (!empty($data['filter_city_to'])) {
			$filter[] = "o.recipient_city_name LIKE '" . $this->db->escape($data['filter_city_to']) . "%'";
		}
		
		if (!empty($data['filter_status_id'])) {
			$filter[] = "o.status_id = " . (int)$data['filter_status_id'];
		}
		
		if (!empty($data['filter_total'])) {
			$filter[] = "o.delivery_cost = " . (float)$data['filter_total'];
		}
		
		if (!empty($filter)) {
			$sql .= "WHERE " . implode(' AND ', $filter);
		}
		
		$sort_data = array(
			'o.order_id',
			'o.dispatch_number',
			'o.act_number',
			'd.date',
			'o.city_name',
			'o.recipient_city_name',
			'o.status_id',
			'o.delivery_cost'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY d.date";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			
			if (empty($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}

			if (empty($data['limit']) || $data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		return $this->db->query($sql)->rows;
	}
	
	public function getDispatchTotal($data = array()) {
		
		$sql  = "SELECT COUNT(o.order_id) as total ";
		$sql .= "FROM `" . DB_PREFIX . "cdek_order` o ";
		$sql .= "INNER JOIN `" . DB_PREFIX . "cdek_dispatch` d ON (o.dispatch_id = d.dispatch_id)";
		
		$filter = array();
		
		if (!empty($data['filter_order_id'])) {
			$filter[] = "o.order_id = " . (int)$data['filter_order_id'];
		}
		
		if (!empty($data['filter_dispatch_number'])) {
			$filter[] = "o.dispatch_number = " . (int)$data['filter_dispatch_number'];
		}
		
		if (!empty($data['filter_act_number'])) {
			$filter[] = "o.act_number = '" . $this->db->escape($data['filter_act_number']) . "'";
		}
		
		if (!empty($data['filter_date'])) {
			$filter[] = "DATE(FROM_UNIXTIME(d.date)) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		}
		
		if (!empty($data['filter_city_from'])) {
			$filter[] = "o.city_name LIKE '" . $this->db->escape($data['filter_city_from']) . "%'";
		}
		
		if (!empty($data['filter_city_to'])) {
			$filter[] = "o.recipient_city_name LIKE '" . $this->db->escape($data['filter_city_to']) . "%'";
		}
		
		if (!empty($data['filter_status_id'])) {
			$filter[] = "o.status_id = " . (int)$data['filter_status_id'];
		}
		
		if (!empty($data['filter_total'])) {
			$filter[] = "o.delivery_cost = " . (float)$data['filter_total'];
		}
		
		if (!empty($filter)) {
			$sql .= "WHERE " . implode(' AND ', $filter);
		}
		
		return $this->db->query($sql)->row['total'];
	}
	
	public function getStatusHistory($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_order_status_history` WHERE order_id = " . (int)$order_id . " ORDER BY date DESC")->rows;
	}
	
	public function getDelayHistory($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_order_delay_history` WHERE order_id = " . (int)$order_id . " ORDER BY date DESC")->rows;
	}
	
	public function getCallHistoryGood($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_order_call_history_good` WHERE order_id = " . (int)$order_id . " ORDER BY date DESC")->rows;
	}
	
	public function getCallHistoryFail($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_order_call_history_fail` WHERE order_id = " . (int)$order_id . " ORDER BY date DESC")->rows;
	}
	
	public function getCallHistoryDelay($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_order_call_history_delay` WHERE order_id = " . (int)$order_id . " ORDER BY date DESC")->rows;
	}
	
	public function getAddService($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_order_add_service` WHERE order_id = " . (int)$order_id)->rows;
	}
	
	public function getCourierCall($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_order_courier` WHERE order_id = " . (int)$order_id)->row;
	}
	
	public function getChedule($order_id) {
		
		$sql  = "SELECT sch.*, ";
		$sql .= "(SELECT sch_d.description FROM `" . DB_PREFIX . "cdek_order_schedule_delay` sch_d WHERE sch_d.attempt_id = sch.attempt_id AND sch_d.order_id = sch.order_id) as delay ";
		$sql .= "FROM `" . DB_PREFIX . "cdek_order_schedule` sch ";
		$sql .= "WHERE sch.order_id = " . (int)$order_id;
		
		return $this->db->query($sql)->rows;
	}
	
	public function getPackages($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_order_package` WHERE order_id = " . (int)$order_id)->rows;
	}
	
	public function getPackageItems($package_id, $order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_order_package_item` WHERE package_id = " . (int)$package_id . " AND order_id = " . (int)$order_id)->rows;
	}
	
	public function changeOrderStatus($cdek_status_id, $dispatch_info) {
		
		$setting = $this->getSetting();
		
		if (empty($setting['order_status_rule'])) {
			return;
		}
			
		$this->load->model('sale/order');
		
		foreach ($setting['order_status_rule'] as $rule) {
			
			if ($cdek_status_id != $rule['cdek_status_id']) {
				continue;
			}
				
			$order_info = $this->model_sale_order->getOrder($dispatch_info['order_id']);
			
			if ($order_info) {
				
				$comment = strtr($rule['comment'], array('{dispatch_number}' => $dispatch_info['number'], '{order_id}' => $dispatch_info['order_id']));

				echo "Меняем статус ".$dispatch_info['order_id']." - ".(int)$rule['order_status_id']."<BR>"; 

				$this->statusApi((int)$dispatch_info['order_id'], (int)$rule['order_status_id'], (int)$rule['notify'], $comment);
			}
			
		}
		
	}

	public function statusApi($order_id, $status_id, $notify, $comment) {
		$this->load->model('user/api');
		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		require_once DIR_SYSTEM . 'library/cdek_integrator/ocapi.php';

		$site_url = str_replace(array('http://', 'https://'), '', HTTP_CATALOG);
		$session_path = DIR_SYSTEM.'library/cdek_integrator/sessionfile';

		$oc = new OpenCart\OpenCart($site_url, $session_path);

		$token = $oc->login($api_info['key']);

		if(!$token) {
			echo 'login error'; return;
		} 

		$oc->order->setToken($token);

		$response = $oc->order->history((int)$order_id, (int)$status_id, $notify, false, $comment);

		return $response;
	}

	public function getOrderToSdek($order_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "order_to_sdek` WHERE `order_id` = '".$order_id."' LIMIT 1";
		$query = $this->db->query($sql);
		if($query->num_rows)
		{
			return $query->row;
		}
		else
		{
			return false;
		}
	}

	public function getCityById($id) {		
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "cdek_city` WHERE `id` = '".$id."'")->row;
	}
	
	public function install() {
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_city` ( ";
		$sql .= "`id` varchar(11) NOT NULL, ";
		$sql .= "`name` varchar(64) NOT NULL, ";
		$sql .= "`cityName` varchar(64) NOT NULL, ";
		$sql .= "`regionName` varchar(64) NOT NULL, ";
		$sql .= "`center` tinyint(1) NOT NULL DEFAULT '0', ";
		$sql .= "`cache_limit` float(5,4) NOT NULL, ";
		$sql .= "PRIMARY KEY (`id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_dispatch` ( ";
		$sql .= "`dispatch_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`dispatch_number` varchar(30) NOT NULL, ";
		$sql .= "`date` varchar(32) NOT NULL, ";
		$sql .= "`server_date` varchar(32) NOT NULL, ";
		$sql .= "PRIMARY KEY (`dispatch_id`) ";
		$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order` ( ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`dispatch_id` int(11) NOT NULL, ";
		$sql .= "`act_number` varchar(20) DEFAULT NULL, ";
		$sql .= "`dispatch_number` varchar(20) NOT NULL, ";
		$sql .= "`return_dispatch_number` varchar(20) NOT NULL, ";
		$sql .= "`city_id` int(11) NOT NULL, ";
		$sql .= "`city_name` varchar(128) NOT NULL, ";
		$sql .= "`city_postcode` int(6) DEFAULT NULL, ";
		$sql .= "`recipient_city_id` int(11) NOT NULL, ";
		$sql .= "`recipient_city_name` varchar(128) NOT NULL, ";
		$sql .= "`recipient_city_postcode` int(6) DEFAULT NULL, ";
		$sql .= "`recipient_name` varchar(128) NOT NULL, ";
		$sql .= "`recipient_email` varchar(255) DEFAULT NULL, ";
		$sql .= "`phone` varchar(50) NOT NULL, ";
		$sql .= "`tariff_id` int(4) NOT NULL, ";
		$sql .= "`mode_id` int(1) NOT NULL, ";
		$sql .= "`status_id` int(11) NOT NULL, ";
		$sql .= "`reason_id` int(11) DEFAULT '0', ";
		$sql .= "`delay_id` int(4) DEFAULT NULL, ";
		$sql .= "`delivery_recipient_cost` float(15,4) DEFAULT '0.0000', ";
		$sql .= "`cod` float(8,4) DEFAULT '0.0000', ";
		$sql .= "`cod_fact` float(8,4) DEFAULT '0.0000', ";
		$sql .= "`comment` varchar(255) DEFAULT NULL, ";
		$sql .= "`seller_name` varchar(255) DEFAULT NULL, ";
		$sql .= "`address_street` varchar(50) DEFAULT NULL, ";
		$sql .= "`address_house` varchar(30) DEFAULT NULL, ";
		$sql .= "`address_flat` varchar(10) DEFAULT NULL, ";
		$sql .= "`address_pvz_code` varchar(10) DEFAULT NULL, ";
		$sql .= "`delivery_cost` float(8,4) DEFAULT '0.0000', ";
		$sql .= "`delivery_last_change` varchar(32) DEFAULT NULL, ";
		$sql .= "`delivery_date` varchar(32) NOT NULL, ";
		$sql .= "`delivery_recipient_name` varchar(50) DEFAULT NULL, ";
		$sql .= "`currency` varchar(3) DEFAULT 'RUB', ";
		$sql .= "`currency_cod` varchar(3) DEFAULT 'RUB', ";
		$sql .= "`last_exchange` varchar(32) NOT NULL, ";
		$sql .= "PRIMARY KEY (`order_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_add_service` ( ";
		$sql .= "`service_id` int(4) NOT NULL, ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`description` varchar(100) DEFAULT NULL, ";
		$sql .= "`price` float(8,4) NOT NULL DEFAULT '0.0000', ";
		$sql .= "PRIMARY KEY (`service_id`,`order_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_call` ( ";
		$sql .= "`call_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`date` int(10) NOT NULL, ";
		$sql .= "`time_beg` time NOT NULL, ";
		$sql .= "`time_end` time NOT NULL, ";
		$sql .= "`phone` varchar(50) DEFAULT NULL, ";
		$sql .= "`recipient_name` varchar(128) DEFAULT NULL, ";
		$sql .= "`delivery_recipient_cost` float(15,4) DEFAULT '0.0000', ";
		$sql .= "`address_street` varchar(50) NOT NULL, ";
		$sql .= "`address_house` varchar(30) NOT NULL, ";
		$sql .= "`address_flat` varchar(10) NOT NULL, ";
		$sql .= "`comment` varchar(255) DEFAULT NULL, ";
		$sql .= "PRIMARY KEY (`call_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_call_history_delay` ( ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`date` int(10) NOT NULL, ";
		$sql .= "`date_next` int(10) NOT NULL, ";
		$sql .= "KEY `order_id` (`order_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_call_history_fail` ( ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`fail_id` int(4) NOT NULL, ";
		$sql .= "`date` int(10) NOT NULL, ";
		$sql .= "`description` varchar(255) NOT NULL, ";
		$sql .= "KEY `order_id` (`order_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_call_history_good` ( ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`date` int(10) NOT NULL, ";
		$sql .= "`date_deliv` int(10) NOT NULL, ";
		$sql .= "KEY `order_id` (`order_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_courier` ( ";
		$sql .= "`courier_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`date` int(10) NOT NULL, ";
		$sql .= "`time_beg` time NOT NULL, ";
		$sql .= "`time_end` time NOT NULL, ";
		$sql .= "`lunch_beg` time DEFAULT NULL, ";
		$sql .= "`lunch_end` time DEFAULT NULL, ";
		$sql .= "`city_id` int(11) NOT NULL, ";
		$sql .= "`city_name` varchar(128) NOT NULL, ";
		$sql .= "`send_phone` varchar(255) NOT NULL, ";
		$sql .= "`sender_name` varchar(255) NOT NULL, ";
		$sql .= "`address_street` varchar(50) NOT NULL, ";
		$sql .= "`address_house` varchar(30) NOT NULL, ";
		$sql .= "`address_flat` varchar(10) NOT NULL, ";
		$sql .= "`comment` varchar(255) DEFAULT NULL, ";
		$sql .= "PRIMARY KEY (`courier_id`) ";
		$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_delay_history` ( ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`delay_id` int(4) NOT NULL, ";
		$sql .= "`date` int(10) NOT NULL, ";
		$sql .= "`description` varchar(50) NOT NULL, ";
		$sql .= "KEY `order_id` (`order_id`,`delay_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_package` ( ";
		$sql .= "`package_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`number` varchar(20) NOT NULL, ";
		$sql .= "`brcode` varchar(20) NOT NULL, ";
		$sql .= "`weight` int(11) NOT NULL, ";
		$sql .= "`size_a` float(15,4) DEFAULT '0.0000', ";
		$sql .= "`size_b` float(15,4) DEFAULT '0.0000', ";
		$sql .= "`size_c` float(15,4) DEFAULT '0.0000', ";
		$sql .= "PRIMARY KEY (`package_id`) ";
		$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_package_item` ( ";
		$sql .= "`package_item_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`package_id` int(11) NOT NULL, ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`ware_key` varchar(20) NOT NULL, ";
		$sql .= "`comment` varchar(255) NOT NULL, ";
		$sql .= "`weight` int(8) NOT NULL DEFAULT '0', ";
		$sql .= "`amount` int(8) NOT NULL, ";
		$sql .= "`cost` float(15,4) NOT NULL DEFAULT '0.0000', ";
		$sql .= "`payment` float(15,4) NOT NULL DEFAULT '0.0000', ";
		$sql .= "PRIMARY KEY (`package_item_id`) ";
		$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_reason` ( ";
		$sql .= "`reason_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`date` int(10) NOT NULL, ";
		$sql .= "`description` varchar(100) NOT NULL, ";
		$sql .= "PRIMARY KEY (`reason_id`,`order_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_schedule` ( ";
		$sql .= "`attempt_id` int(11) NOT NULL, ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`date` int(10) NOT NULL, ";
		$sql .= "`time_beg` time NOT NULL, ";
		$sql .= "`time_end` time NOT NULL, ";
		$sql .= "`phone` varchar(50) DEFAULT NULL, ";
		$sql .= "`recipient_name` varchar(128) DEFAULT NULL, ";
		$sql .= "`address_street` varchar(50) DEFAULT NULL, ";
		$sql .= "`address_house` varchar(30) DEFAULT NULL, ";
		$sql .= "`address_flat` varchar(10) DEFAULT NULL, ";
		$sql .= "`address_pvz_code` varchar(10) DEFAULT NULL, ";
		$sql .= "`comment` varchar(255) DEFAULT NULL, ";
		$sql .= "PRIMARY KEY (`attempt_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_schedule_delay` ( ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`attempt_id` int(11) NOT NULL, ";
		$sql .= "`delay_id` int(11) NOT NULL, ";
		$sql .= "`description` varchar(50) NOT NULL, ";
		$sql .= "KEY `order_id` (`order_id`,`attempt_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$this->db->query($sql);
		
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cdek_order_status_history` ( ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`status_id` int(8) NOT NULL, ";
		$sql .= "`description` varchar(100) NOT NULL, ";
		$sql .= "`date` int(10) NOT NULL, ";
		$sql .= "`city_id` int(11) NOT NULL, ";
		$sql .= "`city_name` varchar(128) NOT NULL, ";
		$sql .= "KEY `order_id` (`order_id`,`status_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";

		$this->db->query($sql);

		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "order_to_sdek` ( ";
		$sql .= "`order_to_sdek_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`order_id` int(11) NOT NULL, ";
		$sql .= "`cityId` int(11) NOT NULL, ";
		$sql .= "`pvz_code` varchar(255) NOT NULL, ";
		$sql .= "PRIMARY KEY (`order_to_sdek_id`), ";
		$sql .= "UNIQUE KEY `order_id` (`order_id`) ";
		$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

		$this->db->query($sql);
	}
	
	public function uninstall() {
		
		/*$sql  = "DROP TABLE `" . DB_PREFIX . "cdek_dispatch`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_add_service`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_call`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_call_history_delay`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_call_history_fail`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_call_history_good`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_courier`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_delay_history`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_package`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_package_item`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_reason`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_schedule`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_schedule_delay`, ";
		$sql .= "`" . DB_PREFIX . "cdek_order_status_history`;";
		
		$this->db->query($sql);*/
	}
	
	public function getDispatchToSync() {
		
		$exchange_interval = 21600; // 6 часов

		$filter_statuses = array(
			4,	// Вручен
			5,	// Не вручен, возврат
			16,	// Возвращен на склад отправителя
			2	// Удален
		);
		
		$sql  = "SELECT o.* ";
		$sql .= "FROM `" . DB_PREFIX . "cdek_order` o ";
		$sql .= "INNER JOIN `" . DB_PREFIX . "cdek_dispatch` d ON (o.dispatch_id = d.dispatch_id)";
		$sql .= "WHERE (UNIX_TIMESTAMP() - o.last_exchange) > " . $exchange_interval . " AND ";
		$sql .= "o.status_id NOT IN (" . implode(',', $filter_statuses) . ") ";
		$sql .= "ORDER BY o.last_exchange, d.date ";
		$sql .= "LIMIT 1";
		
		
		return $this->db->query($sql)->row;
	}

	public function getDispatchesToSync($exchange_interval = 3600) {

		$filter_statuses = array(
			4,	// Вручен
			5,	// Не вручен, возврат
			2	// Удален
		);
		
		$sql  = "SELECT o.* ";
		$sql .= "FROM `" . DB_PREFIX . "cdek_order` o ";
		$sql .= "INNER JOIN `" . DB_PREFIX . "cdek_dispatch` d ON (o.dispatch_id = d.dispatch_id) ";
		$sql .= "WHERE 1 ";
		if($exchange_interval) {
			$sql .= "AND (UNIX_TIMESTAMP() - o.last_exchange) > " . $exchange_interval . " ";
		}
		$sql .= "AND o.status_id NOT IN (" . implode(',', $filter_statuses) . ") ";
		$sql .= "ORDER BY o.last_exchange, d.date";
		
		
		return $this->db->query($sql)->rows;
	}
	
	private function getSetting() {
		return $this->config->get('cdek_integrator_setting');
	}

	public function _cron_edit_status($order_id, $status_id, $notify = false, $comment = '') {
		$sql = "UPDATE " . DB_PREFIX . "cdek_order SET status_id = '".(int)$status_id."' WHERE order_id = '".(int)$order_id."'";
		$this->db->query($sql);

		$sql = "INSERT INTO " . DB_PREFIX . "cdek_order SET order_id = '".(int)$order_id."', status_id = '".(int)$status_id."'";

		if($notify) {
			//todo api
		}
	}
}
?>