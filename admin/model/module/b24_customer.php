<?php
class ModelModuleB24Customer extends Model {
	public function __construct( $registry ){
		parent::__construct($registry);
		
        $this->load->model('setting/setting');
        $b24_setting = $this->model_setting_setting->getSetting('b24');
		$this->b24->setFields($b24_setting);
	}
	
	//Получение клиента по номеру телефона
	public function getByPhone($phone){
		$query = $this->db->query("SELECT b24_contact_id FROM b24_customer WHERE phone = '" . $phone . "'");
		return ($query->num_rows) ? $query->row['b24_contact_id'] : false;
	}
	
	//Получаем список клиентов Б24
	public function getB24ContactList($filter) {
		if (empty($filter)) {
		    trigger_error('Empty filter', E_USER_WARNING);
		}

		foreach ($filter as $value) {
			if (empty($value)) {
			    return false;
			}
		}

		$params = [
			'type' => 'crm.contact.list',
			'params' => [
				'filter' => $filter
			]
		];

		$result = $this->b24->callHook($params);

		return $result['result'];
	}
	
	//Добавляем клиента в битрикс и записываем это в бд
	public function addCustomer($customerId){
		$this->load->model('customer/customer');
		$customer = $this->model_customer_customer->getCustomer($customerId);
		
		$phone = preg_replace("/[^0-9]/", '', $customer['telephone']);
		$b24_contact_id = $this->getByPhone($phone);
		if ($phone && $b24_contact_id){
			$this->db->query("UPDATE b24_customer SET oc_customer_id = '" . (int)$customerId . "' WHERE b24_contact_id = '" . (int)$b24_contact_id . "'");
		}
		
		/*old version code*/
		if(!$b24_contact_id) {
			$email = $customer['email'];
			$result = $this->getB24ContactList(['EMAIL' => $email]);
			$contact = isset($result[0]) ? $result[0] : '';

			// If user already registered in B24
			if ( !empty($contact) ){
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
							'contact_get' => 'crm.contact.get?' . http_build_query(['id' => '$result[contact_id]'])  // No quotes in array key!!!
						]
					]
				];
				$result = $this->b24->callHook($params);
				$b24Id = $result['result']['result']['contact_id'];
				$b24Fields = $result['result']['result']['contact_get'];
			}


			if ( !empty($result['result']['result_error']) ) {
				$this->log->write(print_r($result['result_error'],true));
			}

			$this->addToDB($customerId, $b24Id, $b24Fields, $phone);
		}
	}
	
	//Добавляем в бд информацию о клиенте в Б24
	public function addToDB( $customerId, $b24Id, array $fields = [], $phone = ''){
		if (empty($b24Id) && ((int)$customerId > 0)){
			$this->db->query("UPDATE `b24_customer` SET `phone` = '" . $phone . "' WHERE oc_customer_id = '" . (int)$customerId ."'");
		} else {
			$fields = json_encode($fields);
			$fieldsToAdd = ['oc_customer_id' => $customerId, 'b24_contact_id' => $b24Id, 'b24_contact_field' => $fields, 'phone' => $phone];
			$this->insertToDB($fieldsToAdd);			
		}
	}
	
	//Вставляем в бд данные из Б24 
	public function insertToDB(array $fields) {
		$this->db->query("REPLACE INTO b24_customer SET " . $this->prepareFields($fields) . "");
		$lastId = $this->db->getLastId();

		return $lastId;
	}
	
	//Готовим данные для вставки в БД 
	public function prepareFields(array $fields) {
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
	
	public function getContacts(){
		$result = array();
		
		$query = $this->db->query("SELECT b24_contact_id FROM b24_customer WHERE 1");
		foreach ($query->rows as $row){
			array_push($result, $row['b24_contact_id']);
		}
		
		return $result;
	}
	
	//Инициализация пакетного добавления клиентов из Opencart в Битрикс24
	public function AddCustomerToB24($customer)	{

        if (1 > $customer) {
            return;
        }

		foreach ($customer as $key => $IDs) {
			foreach ($IDs as $id) {
				$customerID[$key] = $id;
			}	
		}
		$customerChunk = array_chunk($customerID, 20);	
		
        $build = [];
		
		foreach($customerChunk as $Chunk) {
				foreach($Chunk as $value) {
					$dataToAdd = $this->prepareDataToB24($value);
					$build['cmd']['contact_id_'.$value.''] = 'crm.contact.add?'. http_build_query($dataToAdd);
					
					$build['cmd']['contact_get_'.$value.''] = 'crm.contact.get?'. http_build_query([
									'ID' => '$result[contact_id_'.$value.']'
					]);
						
					
				}
				$params = [
						'type' => 'batch',
						'params' => $build 
				];
				
				$result = $this->b24->callHook($params);
				
				foreach($Chunk as $value) {
					$b24Id = $result['result']['result']['contact_id_'.$value.''];
					$b24Fields = $result['result']['result']['contact_get_'.$value.''];
					$phone = $result['result']['result']['contact_get_'.$value.'']['PHONE'][0]['VALUE'];
					$this->addToDB($value, $b24Id, $b24Fields, $phone);
					$send_id[] = $value;
				}
				
			$build['cmd'] = [];
		}
		return $send_id;

    }
	

	public function prepareDataToB24($customerId) {
		$this->load->model('customer/customer');

		$customer = $this->model_customer_customer->getCustomer($customerId);
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
				'OPENED' => isset($this->config->get('b24_manager')['settings']['customer_open']) ? $this->config->get('b24_manager')['settings']['customer_open'] : 'N',
				'ASSIGNED_BY_ID' => $managerId,
				'CREATED_BY_ID' => isset($this->config->get('b24_manager')['created']) ? $this->config->get('b24_manager')['created'] : 1,
				'TYPE_ID' => isset($this->config->get('b24_customer')['settings']['RETAIL']) ? $this->config->get('b24_customer')['settings']['RETAIL'] : 'CLIENT',
				'SOURCE_ID' => isset($this->config->get('b24_customer')['settings']['SOURCE']) ? $this->config->get('b24_customer')['settings']['SOURCE'] : 'WEB',
				'PHONE' => [['VALUE' => $customerPhone, "VALUE_TYPE" => "WORK"]],
				'EMAIL' => filter_var($customerEmail, FILTER_VALIDATE_EMAIL) ? [['VALUE' => $customerEmail, "VALUE_TYPE" => "WORK"]] : '',
		]];
		

		if(!empty($customer['address_id']))
		{
			$address = $this->prepareAddress($customer['address_id'], $customerId);
			$dataToB24['fields'] = array_merge($dataToB24['fields'], $address);
		}

		return $dataToB24;
		
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
	
	public function prepareAddress($addressId, $customer_id)
	{
		$address = $this->getAddress($addressId, $customer_id);
		$street = isset($address['address_1']) ? $address['address_1'] : '';
		$city = isset($address['city']) ? $address['city'] : '';
		$postCode = isset($address['postcode']) ? $address['postcode'] : '';
		$country = isset($address['country']) ? $address['country'] : '';
		$zone = isset($address['zone']) ? $address['zone'] : '';

		$addressToB24 = [
			'ADDRESS' => $street,
			'ADDRESS_CITY' => $city,
			'ADDRESS_PROVINCE' => $zone,
			'ADDRESS_COUNTRY' => $country,
			'ADDRESS_POSTAL_CODE' => $postCode,
		];

		return $addressToB24;
	}
	
	public function getById($customerId)
	{
		if(abs($customerId) <= 0){ trigger_error('Customer ID must be integer', E_USER_WARNING);}

		$db = $this->db;
		$sql = 'Select * from b24_customer WHERE oc_customer_id = "' . $db->escape($customerId) . '"';
		$query = $db->query($sql);

		return $query->row;
	}

	public function getCustomerForSync(){
        $customerId = $this->db->query("SELECT ". DB_PREFIX ."customer.customer_id
				FROM ". DB_PREFIX ."customer LEFT JOIN b24_customer ON ". DB_PREFIX ."customer.customer_id=b24_customer.oc_customer_id
				WHERE b24_customer.oc_customer_id IS NULL;");
		if (1 > $customerId->num_rows) {
            return;
        }
		return $customerId->rows;
	}
}