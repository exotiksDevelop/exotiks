<?php
class ControllerApiB24 extends Controller {
	
	public function __construct( $registry ){
		parent::__construct($registry);
		
        $this->load->model('setting/setting');
        $b24_setting = $this->model_setting_setting->getSetting('b24');
		$this->b24->setFields($b24_setting);
	}
	
	public function index(){
        $this->load->model('setting/setting');
		
		$this->getHook();
	    if (isset($this->request->server['REQUEST_METHOD']) && 'POST' == $this->request->server['REQUEST_METHOD']) {
			$post = $this->request->post;
			$event = !empty($post['event']) ? $post['event'] : '';
			$token = !empty($post['auth']['application_token']) ? $post['auth']['application_token'] : '';
				
			$setting = $this->model_setting_setting->getSetting('b24');
			/*есть настройки хуков и есть хук для ивента и он совпадает с пришедшим*/		
			if (!empty($setting['b24_out_hooks']) && isset($setting['b24_out_hooks'][$event]) && ($setting['b24_out_hooks'][$event] == $token)) {
				$this->$event($post['data']);
			}
		}	
	}
	
	/*Создание счета*/
	protected function ONCRMINVOICEADD($data){
	}
	
	/*Обновление счета*/
	protected function ONCRMINVOICEUPDATE($data){
	}
	
	/*Удаление счета*/
	protected function ONCRMINVOICEDELETE($data){
	}
	
	/*Обновление статуса счета*/
	protected function ONCRMINVOICESETSTATUS($data){
	}
	
	/*Создание лида*/
	protected function ONCRMLEADADD($data){
	}
	
	/*Обновление лида*/
	protected function ONCRMLEADUPDATE($data){	
		$this->load->model('module/b24_order');
		$this->load->model('checkout/order');
	
		$b24_order_id = $data['FIELDS']['ID'];		
		$oc_order_id = $this->getOrderById($b24_order_id, 1); // Находим ID заказа
		if (!empty($oc_order_id)){
			$oc_order = $this->model_module_b24_order->getOrder($oc_order_id);
			$params = [
				'type' => 'crm.lead.list',
				'params' => [
					'filter' => ["ID" => $b24_order_id]
				]
			];		
			$result = $this->b24->callHook($params);			
			$b24_order = $result['result'][0];

			if ($b24_order['STATUS_ID'] != 'CONVERTED'){
				$order_status_id = $this->config->get('b24_status')['in']['lead'][$b24_order['STATUS_ID']];		
			} else {
				$order_status_id = $this->config->get('b24_status')['in']['lead']['NEW'];
				
				$params = [
					'type' => 'crm.deal.list',
					'params' => [
						'filter' =>  ["LEAD_ID" => $b24_order_id] 
					]
				];		
				$result_new = $this->b24->callHook($params);
				
				$new_deal_id = $result_new['result'][0]['ID'];
				$this->updateIdLeadToDeal($b24_order_id, $new_deal_id);
			}
			
			if ($oc_order['order_status_id'] != $order_status_id){
				$this->model_checkout_order->addOrderHistory($oc_order_id, $order_status_id, $comment = '', $notify = $this->config->get('b24_order')['order_notify'], $override = false);
			}
		}
					
	}
	
	/*Удаление лида*/
	protected function ONCRMLEADDELETE($data){
	
		$b24_order_id = $data['FIELDS']['ID'];		
		$oc_order_id = $this->getOrderById($b24_order_id, 1); // Находим ID заказа
		
		if (!empty($oc_order_id)){
			$this->db->query("DELETE FROM b24_order WHERE `oc_order_id` = ".$oc_order_id."");
		}
	}
	
	/*Создание сделки*/
	protected function ONCRMDEALADD($data){
	}
	
	/*Обновление сделки*/
	protected function ONCRMDEALUPDATE($data){
		$this->load->model('checkout/order');
		$this->load->model('module/b24_order');
		$b24_order_id = $data['FIELDS']['ID'];		
		$oc_order_id = $this->getOrderById($b24_order_id, 2);
		if (!empty($oc_order_id)){
			$oc_order = $this->model_module_b24_order->getOrder($oc_order_id);
			$params = [
				'type' => 'crm.deal.get',
				'params' => [
					'id' => $b24_order_id
				]
			];	
			
			$result = $this->b24->callHook($params);		
			$b24_order = $result['result'];
			$order_status_id = $this->config->get('b24_status')['in']['deal'][$b24_order['STAGE_ID']];
			if ($oc_order['order_status_id'] != $order_status_id){
				$this->model_checkout_order->addOrderHistory($oc_order_id, $order_status_id, $comment = '', $notify = $this->config->get('b24_order')['order_notify'], $override = false);
			}
		}
		
	}
	
	/*Удаление сделки*/
	protected function ONCRMDEALDELETE($data){
		
		$b24_order_id = $data['FIELDS']['ID'];		
		$oc_order_id = $this->getOrderById($b24_order_id, 2); // Находим ID заказа
		
		if (!empty($oc_order_id)){
			$this->db->query("DELETE FROM b24_order WHERE `oc_order_id` = ".$oc_order_id."");
		}
	}
	
	/*Создание компании*/
	protected function ONCRMCOMPANYADD($data){
	}
	
	/*Обновление компании*/
	protected function ONCRMCOMPANYUPDATE($data){
	}
	
	/*Удаление компании*/
	protected function ONCRMCOMPANYDELETE($data){
	}
	
	/*Создание контакта*/
	protected function ONCRMCONTACTADD($data){
		/*
	$params = [
			'type' => 'crm.contact.get',
			'params' => [
				'id' => $data['FIELDS']['ID']
			]
		];

		$result = $this->b24->callHook($params);
		$contact = $result['result'];
		//$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		//$password = sha1($salt . sha1($salt . sha1($result['result']['UF_CRM_1517393208530'])));
		
		if (isset($contact['PHONE'])){
			$phone = preg_replace("/[^0-9]/", '', $contact['PHONE'][0]['VALUE']);
			$oc_customer_id	= $this->getCustomerByPhone($phone);
			if ($oc_customer_id){
				$this->db->query("UPDATE `b24_customer` SET `b24_contact_id` = '" . (int)$data['FIELDS']['ID'] . "', `phone` = '" . $phone . "', `b24_contact_field` = '" . json_encode($contact) . "' WHERE `oc_customer_id` = '" . (int)$oc_customer_id . "'");
				$this->db->query("UPDATE `oc_customer` SET `firstname`='" . $contact['NAME'] . " " . $contact['SECOND_NAME'] . "', `lastname`='".$contact['LAST_NAME']."', `email`='".$contact['EMAIL'][0]['VALUE']."', `phone` = '" . $phone . "' WHERE `customer_id` = '" . $oc_customer_id . "'");
			} else {
				$this->db->query("INSERT INTO ".DB_PREFIX."customer (`customer_group_id`, `language_id`, `firstname`, `lastname`, `email`, `telephone`, `status`, `approved`, `safe`, `date_added`) VALUES ('1', '1', '".$result['result']['NAME']." ".$result['result']['SECOND_NAME']."', '".$result['result']['LAST_NAME']."', '".$result['result']['EMAIL'][0]['VALUE']."', '".$phone."', '1', '1', '0', '".$result['result']['DATE_CREATE']."');");
				$get_last_customer_id = $this->db->getLastId();
				$this->db->query("INSERT INTO `b24_customer`(`oc_customer_id`, `b24_contact_id`, `phone`, `b24_contact_field`) VALUES ('".(int)$get_last_customer_id."', '" . (int)$data['FIELDS']['ID']. "', '" . $phone . "', '" . json_encode($contact) . "')");
			}
		} else{
				$this->db->query("INSERT INTO ".DB_PREFIX."customer (`customer_group_id`, `language_id`, `firstname`, `lastname`, `email`, `telephone`, `status`, `approved`, `safe`, `date_added`) VALUES ('1', '1', '".$result['result']['NAME']." ".$result['result']['SECOND_NAME']."', '".$result['result']['LAST_NAME']."', '".$result['result']['EMAIL'][0]['VALUE']."', '', '1', '1', '0', '".$result['result']['DATE_CREATE']."');");
				$get_last_customer_id = $this->db->getLastId();
				$this->db->query("INSERT INTO `b24_customer`(`oc_customer_id`, `b24_contact_id`, `phone`, `b24_contact_field`) VALUES ('".(int)$get_last_customer_id."', '" . (int)$data['FIELDS']['ID']. "', '', '" . json_encode($contact) . "')");
		}
*/
	}
	
	/*Обновление контакта*/
	protected function ONCRMCONTACTUPDATE($data){
		$params = [
			'type' => 'crm.contact.get',
			'params' => [
				'id' => $data['FIELDS']['ID']
			]
		];

		$result = $this->b24->callHook($params);
		$contact = $result['result'];
		//$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		//$password = sha1($salt . sha1($salt . sha1('pasword')));
		
		if (isset($contact['PHONE'])){
			$phone = preg_replace("/[^0-9]/", '', $contact['PHONE'][0]['VALUE']);
			
			$this->db->query("UPDATE `b24_customer` SET `phone` = '" . $phone . "', `b24_contact_field` = '" . json_encode($contact) . "' WHERE `b24_contact_id` = '" . (int)$contact['ID'] . "'");
			
			$query = $this->db->query("SELECT * FROM `b24_customer` WHERE b24_contact_id = '" . (int)$contact['ID'] . "'");
			foreach ($query->rows as $row) {
				$oc_customer_id = $row['oc_customer_id'];
			}
			$this->db->query("UPDATE ".DB_PREFIX."customer SET `firstname`='".$result['result']['NAME']." ".$result['result']['SECOND_NAME']."', `lastname`='".$result['result']['LAST_NAME']."', `email`='".$result['result']['EMAIL'][0]['VALUE']."',`fax`='', `telephone` = '" . $phone . "' WHERE `customer_id` = '".(int)$oc_customer_id."'");
		}
	}
	
	/*Удаление контакта*/
	protected function ONCRMCONTACTDELETE($data){
		$this->db->query("DELETE FROM `b24_customer` WHERE 	`b24_contact_id` = '" . (int)$data['FIELDS']['ID'] . "'");
	}
	
	/*Создание валюты*/
	protected function ONCRMCURRENCYADD($data){}
	
	/*Обновление валюты*/
	protected function ONCRMCURRENCYUPDATE($data){}
	
	/*Удаление валюты*/
	protected function ONCRMCURRENCYDELETE($data){}
	
	/*Создание товара*/
	protected function ONCRMPRODUCTADD($data){}
	
	/*Обновление товара*/
	protected function ONCRMPRODUCTUPDATE($data){}
	
	/*Удаление товара*/
	protected function ONCRMPRODUCTDELETE($data){
		if (isset($data['FIELDS']['ID'])){
			$this->db->query("DELETE FROM `b24_product` WHERE 	`b24_product_id` = '" . (int)$data['FIELDS']['ID'] . "'");
		}
	}
	
	/*Создание дела*/
	protected function ONCRMACTIVITYADD($data){}
	
	/*Обновление дела*/
	protected function ONCRMACTIVITYUPDATE($data){}
	
	/*Удаление дела*/
	protected function ONCRMACTIVITYDELETE($data){}

	private function getOrderById($order_id, $type = 0) {
		if (abs($order_id ) <= 0) {
		    trigger_error('ID must be integer', E_USER_WARNING);
		}

		$query = $this->db->query("SELECT oc_order_id FROM b24_order WHERE b24_order_id = '" . (int)($order_id ) . "' AND type = '" . (int)($type) . "'");
		
		return ($query->num_rows) ? $query->row['oc_order_id'] : false;
		
	}
	
	
	private function getCustomerByPhone($phone){
		$query = $this->db->query("SELECT * FROM b24_customer WHERE phone = '" . $phone . "'");

		return ($query->num_rows) ? $query->row['oc_customer_id'] : false;
	}
	
	
	private function updateIdLeadToDeal($b24_order_id, $new_deal_id){
		$query = $this->db->query("UPDATE b24_order set b24_order_id = '" . $new_deal_id . "', type = 2  WHERE b24_order_id = '" . $b24_order_id . "'");
	}
	
	private function getHook(){
		
		if (isset($this->request->server['REQUEST_METHOD']) && 'GET' == $this->request->server['REQUEST_METHOD']) {
			if(isset($this->request->get['token']) && $this->request->get['token'] === md5($this->request->server['HTTP_HOST'].'bitrix24')){
				header('Content-type: application/json');
				if(!empty($this->b24_module)) {
					echo json_encode( $this->b24_module );
				} else {
					$b24_setting = $this->model_setting_setting->getSetting('b24');
					echo json_encode( $b24_setting['b24_in_hook'] );
				}
			}
		}
	}
}