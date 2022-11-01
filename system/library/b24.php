<?php
class B24 {
	private $token = '';
	private $domain = '';
	private $client_id = '';

	public function setFields($settings) {
		$this->token = isset($settings['b24_key_api']) ? $settings['b24_key_api'] : '';
		$this->domain = isset($settings['b24_key_domain']) ? $settings['b24_key_domain'] : '';
		$this->client_id = isset($settings['b24_key_id']) ? $settings['b24_key_id'] : '';
	}
	
	public function callHook($fields) {
		$method = $fields['type'];
		$params = $fields['params'];
		
		$queryUrl  = 'https://' . $this->domain . '/rest/' . $this->client_id . '/' . $this->token . '/' . $method;
		$queryData = http_build_query($params);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_POST           => 1,
			CURLOPT_HEADER         => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL            => $queryUrl,
		
			CURLOPT_POSTFIELDS     => $queryData,
			CURLOPT_VERBOSE         => 1
		));
		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($result, 1);

		if (!empty($result['error']) || !empty($result['result']['result_error'])) {
			$this->writeLog($result['result']['result_error']);
		}
		
		return $result;
	}
	
	public function writeLog($message) {
		if (file_exists(DIR_LOGS .'b24.log')) {
			$filename = fopen(DIR_LOGS .'b24.log', 'a');
		} else {
			$filename = fopen(DIR_LOGS .'b24.log', 'w');
		}
		
		fwrite($filename, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
		fclose($filename);
	}
}
?>