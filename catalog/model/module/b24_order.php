<?php
class ModelModuleB24Order extends Model {
	const TABLE_NAME = 'b24_order';
	const MEASURE_PIECE = 796;
	
	public function __construct( $registry ){ 
		parent::__construct($registry);  
        $this->load->model('setting/setting');
		$b24_setting = $this->model_setting_setting->getSetting('b24');
		
		if ($registry->has('b24')){
			$this->b24->setFields($b24_setting);
		}
	}

	protected function initEditOrder($order_id) {		
        $order = $this->getOrder($order_id);
        $customerName = $order['firstname'];

        $b24Contact = $this->getContactFromDB($order['customer_id']);

        if (empty($b24Contact)) {
            $$b24Contact = $this->getContactFromB24($order['email'], 'EMAIL');
        }

        $managerId = isset($b24Contact['ASSIGNED_BY_ID']) ? $b24Contact['ASSIGNED_BY_ID'] : $this->config->get('b24_manager')['manager'];
        $b24ContactId = !empty($b24Contact['ID']) ? $b24Contact['ID'] : 0;
		
        return [
            'fields' => [
                'CONTACT_ID' => $b24ContactId,
                'ASSIGNED_BY_ID' => $managerId,
                'NAME' => $customerName
            ]
        ];
	}

	public function addToDB( $order_id, $b24Id, $type, array $fields = []){
		if( empty($b24Id) || (int) $order_id <= 0 ){
			trigger_error('Empty $b24Id or $order_id '
				.". Order ID : ". print_r($order_id, 1) .". ID B24: ".  print_r($b24Id, 1),
				E_USER_WARNING);
		}
		$fieldsToAdd = ['oc_order_id' => $order_id, 'b24_order_id' => $b24Id, 'type' => $type];
		$this->insertToDB($fieldsToAdd);
	}

	public function addOrder($order_id) {	
		$order = $this->getOrder($order_id);
		$siteName = html_entity_decode($this->config->get('config_name'),ENT_QUOTES, 'UTF-8');
		$dataToAdd = $this->prepareDataToB24($order_id);
		$dataToAdd = array_merge($dataToAdd, ['params' => ['REGISTER_SONET_EVENT' => 'Y']]);
		$productToAdd = $this->prepareProductToB24($order_id);

		$extraField = [];		$type = 2;		$typeApi = 'deal';		$typeApiRu = 'сделка';		$typeApiRu2 = 'сделки';		$typeApiUrl = '/crm/deal/details/';		$managerId = $dataToAdd['fields']['ASSIGNED_BY_ID'];        $text = 'На сайте ' . $siteName . ' полученa новая <a href="{typeApiUrl}{b24Id}/">{typeApiRu}</a> от {dataToAdd[fields][NAME]}' . '. Перейдите к просмотру новой {typeApiRu2} <a href="{typeApiUrl}{b24Id}/">{b24Id}</a>';
/*			if (empty($dataToAdd['fields']['CONTACT_ID'])) {
			$type = 1;
			$typeApi = 'lead';
            $typeApiRu = 'лид';
            $typeApiRu2 = 'лида';
            $typeApiUrl = '/crm/lead/details/';
            $managerId = $this->config->get('b24_manager')['manager'];
            $text = 'На сайте ' . $siteName . ' получен новый <a href="{typeApiUrl}{b24Id}/">{typeApiRu}</a> от {dataToAdd[fields][NAME]}' . '. Перейдите к просмотру нового {typeApiRu2} <a href="{typeApiUrl}{b24Id}/">{b24Id}</a>';
		} else {
			$type = 2;
			$typeApi = 'deal';
            $typeApiRu = 'сделка';
            $typeApiRu2 = 'сделки';
            $typeApiUrl = '/crm/deal/details/';
            $managerId = $dataToAdd['fields']['ASSIGNED_BY_ID'];
            $text = 'На сайте ' . $siteName . ' полученa новая <a href="{typeApiUrl}{b24Id}/">{typeApiRu}</a> от {dataToAdd[fields][NAME]}' . '. Перейдите к просмотру новой {typeApiRu2} <a href="{typeApiUrl}{b24Id}/">{b24Id}</a>';
		}*/
		$params = [
			'type' => 'batch',
			'params' => [
			    'cmd' => [
                    'order_add' => 'crm.' . $typeApi . '.add?' . http_build_query($dataToAdd),
                    'product_add' => 'crm.' . $typeApi . '.productrows.set?id=$result[order_add]&' . http_build_query($productToAdd)
                ]
			]
		];

		$result = $this->b24->callHook($params);
		$b24Id = $result['result']['result']['order_add'];

		if (!empty($result['result']['result_error'])) {
			$this->log->write(print_r($result['result_error'],true));
		}
		$this->addToDB($order_id, $b24Id, $type);

        $findSearch = ['{typeApiUrl}', '{b24Id}', '{typeApiRu}', '{dataToAdd[fields][NAME]}', '{typeApiRu2}'];
        $findReplace = [$typeApiUrl, $b24Id, $typeApiRu, $dataToAdd['fields']['NAME'], $typeApiRu2];

        $message = str_replace($findSearch, $findReplace, $text);

        $params2 = [
            'type' => 'batch',
            'params' => [
                'cmd' => [
                    'im_notify' => 'im.notify?' . http_build_query([
                        'to' => $managerId,
                        'message' => $message
                    ]),
                ],
            ],
        ];
        $this->b24->callHook($params2);
	}

	public function editOrderStatus($order_id, $order_status_id) {
		$order = $this->getOrder($order_id);
		$dataToB24 = $this->initEditOrder($order_id);	 
		$typeApi = ($dataToB24['fields']['CONTACT_ID'] == 0) ? 'lead' : 'deal';
		
		$b24OrderStatusById = $this->getStatusById($order_status_id, $order['customer_id'], $order_id);
			
		if ($typeApi == 'lead') {	
			$b24OrderStatusId = !empty($b24OrderStatusById['b24_status_id']) ? $b24OrderStatusById['b24_status_id'] : 'NEW';
			$dataToB24['fields']['STATUS_ID'] =  $b24OrderStatusId;
		} elseif ($typeApi == 'deal') {	
			$b24OrderStatusId = !empty($b24OrderStatusById['b24_stage_id']) ? $b24OrderStatusById['b24_stage_id'] : 'NEW';
			$dataToB24['fields']['STAGE_ID'] =  $b24OrderStatusId;
		}
	 
		$b24OrderById = $this->getById($order_id);
		$b24OrderId = !empty($b24OrderById['b24_order_id']) ? $b24OrderById['b24_order_id'] : 0;		

        $params = [
            'type' => 'batch',
            'params' => [
                'cmd' => [
                    'order_update' => 'crm.' . $typeApi . '.update?id='. $b24OrderId . http_build_query($dataToB24),
                ]
            ]
        ];

        $result = $this->b24->callHook($params);
	}
	
	public function editOrder($order_id) {
		$order = $this->getOrder($order_id);
		$customerPhone = isset($order['telephone']) ? preg_replace("/[^0-9]/", '', $order['telephone']) : '';
		$customerName = isset($order['firstname']) ? $order['firstname'] : '';
        $get_b24_order = $this->getById($order_id);
        $b24OrderId = !empty($get_b24_order['b24_order_id']) ? $get_b24_order['b24_order_id'] : '';
		if(empty($b24OrderId)){
			return;
		}
        
		// Поиск ID клиента
		if(!empty($this->getContactFromDB($order['customer_id']))){
			$b24Contact = $this->getContactFromB24($this->getContactFromDB($order['customer_id']), 'ID');
		} elseif (!empty($order['email']) && !empty($this->getContactFromB24($order['email'], 'EMAIL'))){
			$b24Contact = $this->getContactFromB24($order['email'], 'EMAIL');
		} elseif (!empty($customerPhone) && !empty($this->getContactFromB24($customerPhone, 'PHONE'))) {
			$b24Contact = $this->getContactFromB24($customerPhone, 'PHONE');
		} else {
			$b24Contact = '';
		}
 
        $managerId = !empty($b24Contact) ? $b24Contact['ASSIGNED_BY_ID'] : $this->config->get('b24_manager')['manager'];
        $b24ContactId = !empty($b24Contact['ID']) ? $b24Contact['ID'] : '';
        $typeApi = $order['customer_id'] == 0 && $get_b24_order['type'] == 1 ? 'lead' : 'deal';
		$orderName = html_entity_decode($order['store_name'],ENT_QUOTES, 'UTF-8') . '. Заказ № ' . $order['order_id'];
		$status_id = $this->getStatusById($order['order_status_id'], $order['customer_id'], $order_id);
		$productToAdd = $this->prepareProductToB24($order_id);

        $dataToB24 = [
            'fields' => [
                'ID' => $b24OrderId,
                'TITLE' => $orderName,
                'CONTACT_ID' => $b24ContactId,
                'ASSIGNED_BY_ID' => $managerId,
				'STAGE_ID' => $status_id,
                'STATUS_ID' => $status_id,
                'NAME' => $customerName,
            ]
        ];

        $params = [
            'type' => 'batch',
            'params' => [
                'cmd' => [
                    'order_update' => 'crm.' . $typeApi . '.update?id='. $b24OrderId . http_build_query($dataToB24),
					'product_update' => 'crm.' . $typeApi . '.productrows.set?id='. $b24OrderId .'&' . http_build_query($productToAdd)
                ]
            ]
        ];

        $result = $this->b24->callHook($params);


		if (!empty($result['result']['result_error']['order_update'])) {
            $this->log->write(print_r('Ошибка ' .$result['result']['result_error']['order_update']['error_description'].' при обновлении товаров заказа '.$b24OrderId.' в Битрикс 24 ',true));
        }
		if (!empty($result['result']['result_error']['product_update'])) {
			$this->log->write(print_r('Ошибка ' .$result['result']['result_error']['product_update']['error_description'].' при обновлении товаров заказа '.$b24OrderId.' в Битрикс 24 ',true));
        }
 	}

	public function prepareProductToB24( $order_id ){
		$this->load->model('account/order');
		$productToAdd = [];
		$productRows = $this->model_account_order->getOrderProducts($order_id);
		foreach ( $productRows as $product )
		{
			$productId = $product['product_id'];

			$orderOption = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);
			$productOptions = '';
			foreach ($orderOption as $option) {
				$productOptions .= ' | ' .  $option['name'] . ': ' . $option['value'];
				$product_option_value_id = $option['product_option_value_id'];
			}
			$productName = html_entity_decode(trim($product['name'] . $productOptions));
			$taxRate = ($product['tax']/$product['price']) * 100;
			$price = $product['price'] + $product['tax'];
			//$b24_product_id = $this->getb24product($productId, $product_option_value_id);
				$productToAdd['rows'][] = [
					//'PRODUCT_ID' => $b24_product_id[0]['b24_product_id'],
					'PRODUCT_NAME' => $productName,
					'PRICE' => $price,
					'TAX_RATE' => $taxRate,
					'TAX_INCLUDED' => 'N',
					'QUANTITY' => $product['quantity'],
					'MEASURE_CODE' => self::MEASURE_PIECE, // piece
				];
		}

		$productToAdd = $this->addDeliveryCost($order_id, $productToAdd);

		return $productToAdd;
	}

	public function addDeliveryCost($order_id, array $productToAdd){
		$this->load->model('account/order');
		$orderTotalList = $this->model_account_order->getOrderTotals($order_id);

		foreach ($orderTotalList as $orderTotal) {
			if ($orderTotal['code'] == 'shipping' || $orderTotal['code'] == 'reward' || $orderTotal['code'] == 'coupon') {
				$productToAdd['rows'][] = [
					'PRODUCT_ID' => 0,
					'PRICE' => $orderTotal['value'],
					'PRODUCT_NAME' => $orderTotal['title'],
					'QUANTITY' => 1,
					'MEASURE_CODE' => self::MEASURE_PIECE, // piece
				];
			}
		}

		return $productToAdd;
	}
	
	public function prepareDataToB24($order_id){
		$order = $this->getOrder($order_id);
        $orderName = html_entity_decode($order['store_name'],ENT_QUOTES, 'UTF-8') . '. Заказ № ' . $order['order_id'];
		$orderComment = isset($order['comment']) ? $order['comment'] : '';
		$customerLastname = isset($order['lastname']) ? $order['lastname'] : '';
		$customerName = isset($order['firstname']) ? $order['firstname'] : '';
		$customerEmail = isset($order['email']) ? $order['email'] : '';
		$customerPhone = preg_replace("/[^0-9]/", '', $order['telephone']);
		$customerId = $order['customer_id'];

		// Поиск ID клиента
		if(!empty($this->getContactFromDB($order['customer_id']))){
			$b24Contact = $this->getContactFromB24($this->getContactFromDB($order['customer_id']), 'ID');
		} elseif (!empty($order['email']) && !empty($this->getContactFromB24($order['email'], 'EMAIL'))){
			$b24Contact = $this->getContactFromB24($order['email'], 'EMAIL');
		} elseif (!empty($customerPhone) && !empty($this->getContactFromB24($customerPhone, 'PHONE'))) {
			$b24Contact = $this->getContactFromB24($customerPhone, 'PHONE');
			
		} else {
			$b24Contact = '';
		}

        $managerId = !empty($b24Contact) ? $b24Contact['ASSIGNED_BY_ID'] : $this->config->get('b24_manager')['manager'];
        $b24ContactId = !empty($b24Contact['ID']) ? $b24Contact['ID'] : '';
		
		$status_id = $this->getStatusById($order['order_status_id'],$order['customer_id'], $order_id);
		$roistat = isset($_COOKIE['roistat_visit']) ? $_COOKIE['roistat_visit'] : '';
		$pay_field = $order['customer_id'] == 0 ? $this->config->get('b24_order')['fieldpay']['lead'] : $this->config->get('b24_order')['fieldpay']['deal'];

		$dataToB24 = [];
		$dataToB24 = [
		    'fields' => [
                'TITLE' => $orderName,
				'STAGE_ID' => $status_id,
				'STATUS_ID' => $status_id,
                'CURRENCY_ID' => $this->config->get('config_currency'),
                'SOURCE_ID' => isset($this->config->get('b24_order')['source']) ? $this->config->get('b24_order')['source'] : 'WEB',
                'OPENED' => isset($this->config->get('b24_manager')['order_open']) ? $this->config->get('b24_manager')['order_open'] : 'N',
                'ASSIGNED_BY_ID' => $managerId,
                'CONTACT_ID' => $b24ContactId,
                'COMMENTS' => $orderComment .'<br><hr>Имя клиента: ' .$customerName .'<br>Фамилия клиента: ' .$customerLastname 
				.'<br><hr>Адрес клиента: ' .$order['payment_country'].','. $order['payment_zone'].','. $order['payment_postcode'] .','. $order['payment_city'] .',<br>Телефон: '. $customerPhone .'<br>Email: '.$customerEmail .'<br><hr>Способ оплаты: '.$order['payment_method'],
                'UTM_SOURCE' => isset($_COOKIE['utm_source']) ? $_COOKIE['utm_source'] : '',
				'UTM_MEDIUM' => isset($_COOKIE['utm_medium']) ? $_COOKIE['utm_medium'] : '',
				'UTM_CAMPAIGN' => isset($_COOKIE['utm_campaign']) ? $_COOKIE['utm_campaign'] : '',
				'UTM_CONTENT' => isset($_COOKIE['utm_content']) ? $_COOKIE['utm_content'] : '',
				'UTM_TERM' => isset($_COOKIE['utm_term']) ? $_COOKIE['utm_term'] : '',

                'NAME' => $customerName,
                'LAST_NAME' => $customerLastname,
                'ADDRESS' => $order['payment_address_1'],
                'ADDRESS_COUNTRY' => $order['payment_country'],
                'ADDRESS_PROVINCE' => $order['payment_zone'],
                'ADDRESS_CITY' => $order['payment_city'],
                'ADDRESS_POSTAL_CODE' => $order['payment_postcode'],
                'PHONE' => [['VALUE' => $customerPhone, "VALUE_TYPE" => "WORK"]],
                'EMAIL' => [['VALUE' => $customerEmail, "VALUE_TYPE" => "WORK"]],
				$pay_field => isset($order['payment_method']) ? $order['payment_method'] : '',
				]
			];
		return $dataToB24;
		
	} 
	
	// GET data
	public function getContactFromDB($customerId){
		$this->load->model('module/b24_customer');
		if (abs($customerId) <= 0) {
		    return [];
		}

		$b24Row = $this->model_module_b24_customer->getById($customerId);
		$b24Contact = isset($b24Row['b24_contact_id']) ? $b24Row['b24_contact_id'] : '';

		return $b24Contact;
	}
	
	public function getContactFromDBByPhone($phone){
		$result = array();
		
		$query = $this->db->query("SELECT * FROM b24_customer WHERE phone = '" . $phone . "'");
		
		if ($query->num_rows){
			$result = $query->row['b24_contact_id'];
		}

		return $result;
	}

	public function getContactFromB24($contactEmail, $key){
        $B24ContactList = $this->getB24ContactList([$key => $contactEmail]);
        $b24Contact = isset($B24ContactList[0]) ? $B24ContactList[0] : '';

		return $b24Contact;
	}


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

	public function insertToDB(array $fields){
		$db = $this->db;

		$sql = 'REPLACE INTO `b24_order` SET ' . $this->prepareFields($fields) . ';';
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

	public function getStatusById($order_status_id, $customer_id, $order_id = NULL) {		
		$get_b24_order = $this->getById($order_id);
		if($customer_id == 0 && $get_b24_order['type'] == 1){
			$status_map = $this->config->get('b24_status')['out']['lead'][$order_status_id];
		} else {
			$status_map = $this->config->get('b24_status')['out']['deal'][$order_status_id];
		}
		return $status_map; 
	}

	public function getById($order_id) {
		if (abs($order_id ) <= 0) {
		    trigger_error('ID must be integer', E_USER_WARNING);
		}

		$db = $this->db;
		$sql = 'SELECT * FROM `b24_order` WHERE oc_order_id = "' . $db->escape($order_id ) . '"';
		$query = $db->query($sql);

		return $query->row;
	}

	public function getList(array $filter){
		$db = $this->db;
		$where = ' WHERE ';
		$index = 0;
		foreach ($filter as $columnName => $value) {
			$glue = $index === 0 ? ' ' : ' AND ';
			$where .= $glue . $columnName. ' = "' . $db->escape($value) . '"';
			$index++;
		}

		$sql = 'SELECT * FROM ' . self::TABLE_NAME . $where . ';';
		$query = $db->query($sql);

		return $query->rows;
	}
	
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

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
			
			$this->load->model('localisation/language');
			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => json_decode($order_query->row['payment_custom_field'], true),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'commission'              => $order_query->row['commission'],
				'language_code'           => $language_code,
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return false;
		}
	}
	
	public function getb24product($product_id, $product_option_value_id) {
		$db = $this->db;
		$sql = 'SELECT * FROM b24_product WHERE oc_product_id = "' . $db->escape($product_id) . '" AND option = "'.$db->escape($product_option_value_id).'"';
		$query = $db->query($sql);

		return $query->rows;
	}
	
}