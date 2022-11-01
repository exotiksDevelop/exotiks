<?php
class ModelExtensionModuleB24Customer extends Model{	
	public function __construct( $registry ){
		parent::__construct($registry);
		
        $this->load->model('setting/setting');

        $b24_setting = $this->model_setting_setting->getSetting('b24_hook_key');
		$this->b24->setFields($b24_setting);
	}
	
	public function getContacts(){
		$result = array();
		
		$query = $this->db->query("SELECT b24_contact_id FROM b24_customer WHERE 1");
		foreach ($query->rows as $row){
			array_push($result, $row['b24_contact_id']);
		}
		
		return $result;
	}
	
	public function cynchContacts($oc_contacts = array()){
		$params = [
			'type' => 'crm.contact.list',
			'params' => [
				'start' => 0,
				'select' => ['ID', 'PHONE', 'EMAIL'],
				'filter' => [],
			]
		];
		
		do {
            $result = $this->b24->callHook($params);
			$this->log->write(print_r($result,true));
			$b24_contacts = $result['result'];
			foreach ($b24_contacts as $contact){
				if (!in_array($contact['ID'], $oc_contacts) && isset($contact['PHONE'])){
					$phone = preg_replace("/[^0-9]/", '', $contact['PHONE'][0]['VALUE']);
					
					$this->db->query("INSERT INTO `b24_customer`(`b24_contact_id`, `phone`, `b24_contact_field`) VALUES ('" . (int)$contact['ID'] . "', '" . $phone . "', '" . json_encode($contact) . "')");
				}
			}
			$params['params']['start'] = isset($result['next']) ? $result['next'] : 0;
        } 
		while(!empty($result['next']));

		
	}
}