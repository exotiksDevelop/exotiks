<?php
class ModelModuleB24Customer extends Model {
	
	const TABLE_NAME = 'b24_customer';
	
	public function __construct( $registry ){
		parent::__construct($registry);
        $this->load->model('setting/setting');
        $b24_setting = $this->model_setting_setting->getSetting('b24');
		$this->b24->setFields($b24_setting);
	}

	public function addToDB( $customerId, $b24Id, array $fields = [], $phone = ''){
		if (empty($b24Id) && ((int)$customerId > 0)){
			$this->db->query("UPDATE `b24_customer` SET `phone` = '" . $phone . "' WHERE oc_customer_id = '" . (int)$customerId ."'");
		} else {
			$fields = json_encode($fields);
			$fieldsToAdd = ['oc_customer_id' => $customerId, 'b24_contact_id' => $b24Id, 'b24_contact_field' => $fields, 'phone' => $phone];
			$this->insertToDB($fieldsToAdd);			
		}
	}

	public function addCustomer($customerId){
		$this->load->model('module/b24_order');
		$this->load->model('account/customer');

		$customer = $this->model_account_customer->getCustomer($customerId);
		
		$b24_contact_id = $this->getById($customerId);
		
		if ($b24_contact_id){
			$this->db->query("UPDATE `b24_customer` SET oc_customer_id = '" . (int)$customerId . "' WHERE b24_contact_id = '" . (int)$b24_contact_id . "'");
		}
		
		if(!$b24_contact_id) {
			$phone = preg_replace("/[^0-9]/", '', $customer['telephone']);
			$email = isset($customer['email']) ? $customer['email'] : 0;
			$result = $this->model_module_b24_order->getB24ContactList(['EMAIL' => $email]);
			$contact = isset($result[0]) ? $result[0] : '';

			// Если контакт уже есть в б24
			if (!empty($contact) ){
				$b24Id = $contact['ID'];
				$b24Fields = $contact;
			} else {
				$dataToAdd = $this->prepareDataToB24($customerId);
				// Оповещение менеджера о новом клиенте
				$dataToAdd = array_merge($dataToAdd, ['params' => ['REGISTER_SONET_EVENT' => 'Y']]);
				$params = [
					'type' => 'batch',
					'params' => [
						'cmd' => [
							'contact_id' => 'crm.contact.add?' . http_build_query($dataToAdd),
							'contact_get' => 'crm.contact.get?' . http_build_query(['id' => '$result[contact_id]'])
						]
					]
				];
				$result = $this->b24->callHook($params);
				$b24Id = $result['result']['result']['contact_id'];
				$b24Fields = $result['result']['result']['contact_get'];
			}


			if ( !empty($result['result']['result_error']) ) {
				trigger_error('Ошибка при добавлении клиента в Б24 ' . print_r($result['result_error'], 1), E_USER_WARNING);
			}

			$this->addToDB($customerId, $b24Id, $b24Fields, $phone);
		}
	}

	public function editCustomer( $customerId ){
		$this->load->model('account/customer');
		
		$customer = $this->model_account_customer->getCustomer($customerId);
		$dataToUpdate = $this->prepareDataToB24($customerId);
		$b24Row = $this->getById($customerId);
		if (isset($b24Row['b24_contact_id']) && !empty($b24Row['b24_contact_id'])){
			$b24ContactId = $b24Row['b24_contact_id'];
			$b24Field = json_decode($b24Row['b24_contact_field'], true);
			$dataToUpdate['fields']['PHONE'][0]['ID'] = isset($b24Field['PHONE'][0]['ID']) ? $b24Field['PHONE'][0]['ID'] : '';
			$dataToUpdate['fields']['EMAIL'][0]['ID'] = isset($b24Field['EMAIL'][0]['ID']) ? $b24Field['EMAIL'][0]['ID'] : '';

			$dataToUpdate = array_merge($dataToUpdate, ['id' => $b24ContactId]);

			$params = [
				'type' => 'batch',
				'params' => [
					'cmd' => [
						'contact_update' => 'crm.contact.update?' . http_build_query($dataToUpdate),
						'contact_get' => 'crm.contact.get?id=' . $b24ContactId
					]
				]
			];

			$result = $this->b24->callHook($params);

			$b24Field = isset($result['result']['result']['contact_get']) ? $result['result']['result']['contact_get'] : array('PHONE' => array('0' => array('ID' => '')), 'EMAIL' => array('0' => array('ID' => '')));
			
			$phone = (isset($this->request->post['telephone'])) ? $this->request->post['telephone'] : '';
			$phone = (isset($this->request->post['edit']['telephone'])) ? $this->request->post['edit']['telephone'] : '';

			$this->addToDB($customerId, $b24ContactId, $b24Field, $phone);
		}
	}

	public function editCustomerAddress($addressId){
		$address = $this->prepareAddress($addressId, $this->customer->getId());
		$b24Row = $this->getById($this->customer->getId());
		$b24ContactId = $b24Row['b24_contact_id'];

		$dataToB24 = [
			'id' => $b24ContactId,
			'fields' => $address
		];

		$params = [
			'type' => 'crm.contact.update',
			'params' => $dataToB24
		];

		$result = $this->b24->callHook($params);

	}

	public function prepareAddress( $addressId, $customer_id ){

		$address = $this->getAddress($addressId, $customer_id);
		$street = $address['address_1'];
		$city = $address['city'];
		$postCode = $address['postcode'];
		$country = $address['country'];
		$zone = $address['zone'];

		$addressToB24 = [
			'ADDRESS' => $street,
			'ADDRESS_CITY' => $city,
			'ADDRESS_PROVINCE' => $zone,
			'ADDRESS_COUNTRY' => $country,
			'ADDRESS_POSTAL_CODE' => $postCode,
		];

		return $addressToB24;
	}

	public function prepareDataToB24($customerId){
		$this->load->model('account/customer');
		$customer = $this->model_account_customer->getCustomer($customerId);
		$customerName = isset($customer['firstname']) ? $customer['firstname'] : '';
		$customerLastname = isset($customer['lastname']) ? $customer['lastname'] : '';
		$customerEmail = isset($customer['email']) ? $customer['email'] : '';
		$customerPhone = isset($customer['telephone']) ? $customer['telephone'] : '';

		$b24Row = $this->getById($customerId);
		$b24Field = isset($b24Row['b24_contact_field']) ? json_decode($b24Row['b24_contact_field'], 1) : array();
		$managerId = !empty($b24Field['ASSIGNED_BY_ID']) ? $b24Field['ASSIGNED_BY_ID'] : $this->config->get('b24_manager')['manager'];

		$dataToB24 = [];
		$dataToB24 = [
			'fields' => [
				'NAME' => $customerName,
				'LAST_NAME' => $customerLastname,
				'OPENED' => isset($this->config->get('b24_customer')['settings']['customer_open']) ? $this->config->get('b24_customer')['settings']['customer_open'] : 'N',
				'ASSIGNED_BY_ID' => $managerId,
				'CREATED_BY_ID' => isset($this->config->get('b24_manager')['created']) ? $this->config->get('b24_manager')['created'] : 1,
				'TYPE_ID' => isset($this->config->get('b24_customer')['settings']['RETAIL']) ? $this->config->get('b24_customer')['settings']['RETAIL'] : 'CLIENT',
				'SOURCE_ID' => isset($this->config->get('b24_customer')['settings']['SOURCE']) ? $this->config->get('b24_customer')['settings']['SOURCE'] : 'WEB',
				'PHONE' => [['VALUE' => $customerPhone, "VALUE_TYPE" => "WORK"]],
				'EMAIL' => filter_var($customerEmail, FILTER_VALIDATE_EMAIL) ? [['VALUE' => $customerEmail, "VALUE_TYPE" => "WORK"]] : '',
		]];

		if(!empty($customer['address_id'])){
			$address = $this->prepareAddress($customer['address_id'], $customerId);
			$dataToB24['fields'] = array_merge($dataToB24['fields'], $address);
		}

		return $dataToB24;
	}

	public function insertToDB(array $fields){
		$db = $this->db;
		$sql = 'REPLACE INTO `b24_customer` SET ' . $this->prepareFields($fields) . ';';
		$db->query($sql);

		$lastId = $this->db->getLastId();

		return $lastId;
	}

	public function prepareFields(array $fields){
		$sql = '';
		$index = 0;
		foreach ( $fields as $columnName => $value )
		{
			$glue = $index === 0 ? ' ' : ', ';
			$sql .= $glue . "`$columnName`" . ' = "' . $this->db->escape($value) . '"';
			$index++;
		}

		return $sql;
	}

	public function getById($customerId){
		if(abs($customerId) <= 0){ trigger_error('Customer ID must be integer', E_USER_WARNING);}

		$db = $this->db;
		$sql = 'Select * from `b24_customer` WHERE oc_customer_id = "' . $db->escape($customerId) . '"';
		$query = $db->query($sql);

		return $query->row;
	}
	
	public function getByPhone($phone){
		$query = $this->db->query("SELECT * FROM `b24_customer` WHERE phone = '" . $phone . "'");

		return ($query->num_rows) ? $query->row['b24_contact_id'] : false;
	}

	public function getList(array $filter){
		$db = $this->db;
		$where = ' WHERE ';
		$index = 0;
		foreach ( $filter as $columnName => $value )
		{
			$glue = $index === 0 ? ' ' : ' AND ';
			$where .= $glue . $columnName. ' = "' . $db->escape($value) . '"';
			$index++;
		}

		$sql = 'Select * from ' . self::TABLE_NAME . $where . ';';
		$query = $db->query($sql);

		return $query->rows;
	}

	public function getAddress($address_id, $customer_id) {
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$customer_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id'     => $address_query->row['address_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($address_query->row['custom_field'], true)
			);

			return $address_data;
		} else {
			return false;
		}
	}
}

