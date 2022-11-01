<?php
class ControllerYamoduleMws extends Controller {
	private $error = array();
	const PREFIX_DEBUG = "";
   const ORDERNUMBER = "orderNumber";
	
	public function generate(){
		$this->load->model('setting/setting');
		$sid = $this->model_setting_setting->getSetting("ya_kassa_sid");
		$pkey="";
		$csr="";
		$mws = new YamoduleMws();
		$sign = $mws->gen_CSR_PKey(
		array(
			"countryName" => "RU",
			"stateOrProvinceName" => "Russia",
			"localityName" => "Moscow",
			"commonName" => "/business/oc2/yacms-".$sid['ya_kassa_sid'],
		),	$pkey, $csr);
		$this->model_setting_setting->editSetting("yamodule_mws", array("yamodule_mws_pkey" => $pkey, "yamodule_mws_csr" =>$csr, "yamodule_mws_csr_sign" => $sign));
		//$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput(json_encode(array('ok')));
	}
	public function upload(){
		$this->load->language('yamodule/mws');
		$json=array();
		if (!empty($this->request->files['file']['name'])) {
			if (substr($this->request->files['file']['name'], -4) != '.cer') {
				$json['error'] = $this->language->get('err_upload_type');
			}
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->request->files['file']['error'];
			}
			if (filesize($this->request->files['file']['tmp_name'])>2048) {
				$json['error'] = $this->language->get('err_upload_size');
			}
		} else {
			$json['error'] = $this->language->get('err_upload_main');
		}
		if (!isset($json['error'])){
			$this->load->model('setting/setting');
			$cert = file_get_contents($this->request->files['file']['tmp_name']);
			$conf = $this->model_setting_setting->getSetting("yamodule_mws");		
			$this->model_setting_setting->editSetting("yamodule_mws", array('yamodule_mws_cert'=>$cert, 'yamodule_mws_pkey'=>$conf['yamodule_mws_pkey']));
		}
		$this->response->addHeader('Content-Type: application/json; charset=utf-8');
		$this->response->setOutput(json_encode($json));
	}
	public function csr(){
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=csr_for_yamoney.csr');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');		
		$this->load->model('setting/setting');
		$conf = $this->model_setting_setting->getSetting("yamodule_mws");
		echo $conf['yamodule_mws_csr'];
	}

	private function log_save($logtext){
		$error_log = new Log('error.log');
		$error_log->write($logtext.PHP_EOL);
		$error_log = null;
	}

	public function index() {
		$this->load->model('sale/order');
		$this->load->model('setting/setting');
		$this->load->language('yamodule/mws');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		$is_act_return = (isset($this->request->get['act']) && $this->request->get['act']=='return');
		$order_info = $this->model_sale_order->getOrder($order_id);
		
		if ($order_info) {
			$mws = new YamoduleMws();

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['btn_return'] = $this->language->get('btn_return');
			$data['text_history_empty'] = $this->language->get('text_history_empty');
			$data['text_invoice_empty'] = $this->language->get('text_invoice_empty');
			//
			$data['text_history'] = $this->language->get('text_history');
			$data['text_return_success'] = $this->language->get('text_return_success');
			
			$data['tbl_head_date'] = $this->language->get('tbl_head_date');
			$data['tbl_head_amount'] = $this->language->get('tbl_head_amount');
			$data['tbl_head_cause'] = $this->language->get('tbl_head_cause');
			
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_cancel'] = $this->language->get('button_cancel');

			$data['tab_return'] = $this->language->get('tab_return');
			$data['text_amount'] = $this->language->get('text_amount');
			$data['text_cause'] = $this->language->get('text_cause');
			
			$data['tab_history'] = $this->language->get('tab_history');

			$data['token'] = $this->session->data['token'];
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$data['text_total'] = $this->language->get('text_total');
			$data['text_return_total'] = $this->language->get('text_return_total');
			$data['lbl_mws_inv'] = $this->language->get('lbl_mws_inv'); 
			
			$errors=array();
			$return_error = array();

			//$return = new YamoduleReturn();
			$this->load->model('yamodule/return');

			$conf = $this->model_setting_setting->getSetting("yamodule_mws");
			$sid = $this->model_setting_setting->getSetting("ya_kassa_sid");
			$kassa_active = $this->model_setting_setting->getSetting("ya_kassa_active");
			$test = $this->model_setting_setting->getSetting("ya_kassa_test");
			
			if (!isset($sid['ya_kassa_sid']) || $sid['ya_kassa_sid']<=0) $errors[]=$this->language->get('err_mws_shopid');
			if ($kassa_active['ya_kassa_active'] != true) $errors[]=$this->language->get('err_mws_kassa');
			
			$mws->demo = ($test["ya_kassa_test"]==true);
			$mws->shopId = $sid['ya_kassa_sid'];
			$mws->PkeyPem = (isset($conf['yamodule_mws_pkey']))?$conf['yamodule_mws_pkey']:'';
			$mws->CertPem = (isset($conf['yamodule_mws_cert']))?$conf['yamodule_mws_cert']:'';
			
			$payment = $mws->request('listOrders', array("orderNumber" => self::PREFIX_DEBUG.$order_id), false, false);
			if (!isset($payment['invoiceId'])) {
				$errors[]=$this->language->get('err_mws_listorder');
				//
				//$this->log_save($mws->txt_request);
				//$this->log_save($mws->txt_request);
			}
			
			if (!$errors && $this->request->server['REQUEST_METHOD'] == 'POST' && $is_act_return && isset($this->request->post['return_sum'])){
				$amount = str_replace(',','.',$this->request->post['return_sum']);
				$cause  = $this->request->post['return_cause'];
				if (strlen($cause)>100 || strlen($cause)<3) $return_error[] = $this->language->get('err_mws_cause');
				if ($amount>$payment['orderSumAmount']) $return_error[] = $this->language->get('err_mws_amount');
				if (!$return_error){
					$respPayment = $mws->request('returnPayment', array('invoiceId'=> $payment['invoiceId'], 'amount'=>	$amount, 'cause'=>$cause));
					if (isset($respPayment['status'])){
						$this->model_yamodule_return->addReturn(array(
							'amount'=>$amount,
							'cause'=>$cause,
							'request'=>$mws->txt_request || 'NULL',
							'response'=>$mws->txt_respond || 'NULL',
							'status'=>$respPayment['status'],
							'error' => $respPayment['error'],
							'invoice_id'=>$payment['invoiceId']
						));
						if($respPayment['status']==0) {
							$data['return_success'] = true;
						} else{
							$data['return_error'] = array($mws->getErr($respPayment['error']));
						}
					}
				}else{
					$data['return_error'] = $return_error;
				}
			}

			$inv = (isset($payment['invoiceId']))?$payment['invoiceId']:0;
			$inv_sum = (isset($payment['orderSumAmount']))?$payment['orderSumAmount']:0;
			$inv_type = (isset($payment['paymentType']))?$payment['paymentType']:"none";

			$returns = $this->model_yamodule_return->getSuccessReturns($inv);
			$sum_returned = (isset($returns->sum)?$returns->sum:0);
			$data['return_items'] = (isset($returns->returns)?$returns->returns:false);
			$data['invoiceId'] = $inv;
			
			$data['return_sum_symbol']	= $this->currency->getSymbolRight($order_info['currency_code']);
			$data['return_sum'] = number_format(floatval ($inv_sum - $sum_returned),2, '.', '');
			$data['order_total'] = $this->currency->format($inv_sum, $order_info['currency_code'], $order_info['currency_value']);
			$data['payment_method'] = $this->language->get('text_method_'.$inv_type)." (".$inv_type.")";
			
			$url = '';
			$data['return_total'] = $this->currency->format($sum_returned, $order_info['currency_code'], $order_info['currency_value']);
			$data['form_return_url'] = $this->url->link('yamodule/mws', 'order_id='.$order_id.'&act=return&token=' . $this->session->data['token'], 'SSL');
			$data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$data['order_id'] = $this->request->get['order_id'];
			$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
			
			$this->load->model('localisation/order_status');
			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);
			if ($order_status_info) {
				$data['order_status'] = $order_status_info['name'];
			} else {
				$data['order_status'] = '';
			}
			if (isset($this->request->get['order'])) $url .= 'order=' . $this->request->get['order'];
			
			$data['mws_order_error'] = $errors; 
			$data['breadcrumbs'] = array();
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('yamodule/mws', 'token=' . $this->session->data['token'] . $url, 'SSL')
			);
			$end_tpl = (version_compare(VERSION, "2.2.0", '>='))?"":".tpl";
			$this->response->setOutput($this->load->view('yamodule/mws_info'.$end_tpl, $data));
		
		}else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_not_found'] = $this->language->get('text_not_found');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$end_tpl = (version_compare(VERSION, "2.2.0", '>='))?"":".tpl";
			$this->response->setOutput($this->load->view('error/not_found'.$end_tpl, $data));
		}
	}
}
/*
class: ModelYamoduleMws
author: Yandex.Money
property:
	(string)	CertPem	- PEM-сертификат, полученный от Яндекс.Денег
	(string)	PkeyPem 	- приватный PEM-ключ
	(int)		shopId	- идентификатор магазина
	(bool)	demo 		- работа в тестовом режиме
methods:
	(array) request ($oper, $param, $sign = true, $is_xml = true, $fields=array())
		params list:
			(string) $oper		- имя операции для MWS
			(array) 	$param	- параметры запроса
			(bool) 	$sign 	- упаковывать в криптоконтейнер?
			(bool) 	$is_xml	- отправлять в виде xml?
			(array) 	$fields	- параметры вывода

	(bool) gen_CSR_PKey ($dn, &$privKey, &$csr_export)
		params list:
			(string) 			$dn			- параметры для запроса на сетрификат
			(var string)		&$privKey	- ссылка на переменную с результатом (приватный ключ) работы метода
			(var string)		&$csr_export- ссылка на переменную с результатом (запрос на сертификат) работы метода
example:
	request('returnPayment', array('invoiceId'=, 'amount'=, 'cause'=));
	request('listOrders', array('orderNumber'=), false, false);
	gen_CSR_PKey(
		array(
			"organizationName" => "OOO Roga and Kopyta",
			"commonName" => "/business/oc/test",
			"emailAddress" => "test@test.com"
		),	$pkey, $csr);
*/
Class YamoduleMws{
	public $CertPem;
	public $PkeyPem;
	public $shopId;
	public $demo=false;
	public $txt_request;
	public $txt_respond;

	public function request($oper, $param, $sign = true, $is_xml = true, $fields=array()){
		$prepare = $this->getDefaultArray($oper, $fields, $level);
		$data = array_merge ($prepare, $param);
		$xml = $this->sendRequest($oper, $data, $sign, $is_xml);
		$this->txt_respond = $xml;
		$info = $this->parseXML($xml, $fields, $level);
		return (array) $info;
	}

	public function gen_CSR_PKey($dn, &$privKey, &$csr_export){
		$config = array(
			"digest_alg" => "sha1",
			"private_key_bits" => 2048,
			"private_key_type" => OPENSSL_KEYTYPE_RSA,
		);
		$dn_full = array_merge(array(
			"countryName" => "RU",
			"stateOrProvinceName" => "Russia",
			"localityName" => ".",
			"organizationalUnitName" => "."
		), $dn);
		$res = openssl_pkey_new($config);
		$csr_origin = openssl_csr_new($dn_full, $res);

		$csr_full = "";
		openssl_pkey_export($res, $privKey);
		openssl_csr_export ($csr_origin, $csr_export);

		openssl_csr_export ($csr_origin, $csr_full, false);
		preg_match( '"Signature Algorithm\: (.*)-----BEGIN"ims', $csr_full, $sign);
		$sign = str_replace("\t", "", $sign);
		if ($sign) {
			$sign = $sign[1];
			$a = explode("\n", $sign);
			unset($a[0]);
			$sign = str_replace("         ", "", trim(join("\n", $a)));
		}
		return $sign;
	}
	public function getErr($id){
		$error = array(
			'0' => 'Техническая ошибка или возврат запрещен для данного способа оплаты',
			'10' => 'Ошибка синтаксического разбора XML документа. ',
			'50' => 'Невозможно открыть криптопакет PKCS#7, ошибка целостности данных криптопакета',
			'51' => 'АСП не подтверждена (данные цифровой подписи не совпадают с передаваемым документом)',
			'53' => 'Запрос подписан сертификатом, который неизвестен Яндекс.Деньгам',
			'55' => 'Истек срок действия сертификата магазина',
			'110' => 'У магазина нет прав на выполнение операции с запрошенными параметрами',
			'111' => 'Неверное значение параметра requestDT',
			'112' => 'Неверное значение параметра invoiceId',
			'113' => 'Неверное значение параметра shopId',
			'114' => 'Неверное значение параметра orderNumber',
			'115' => 'Неверное значение параметра clientOrderId',
			'117' => 'Неверное значение параметра status',
			'118' => 'Неверное значение параметра from',
			'119' => 'Неверное значение параметра till',
			'120' => 'Неверное значение параметра orderId',
			'200' => 'Неверное значение параметра outputFormat',
			'201' => 'Неверное значение параметра csvDelimiter',
			'202' => 'Неверное значение параметра orderCreatedDatetimeGreaterOrEqual',
			'203' => 'Неверное значение параметра orderCreatedDatetimeLessOrEqual',
			'204' => 'Неверное значение параметра paid',
			'205' => 'Неверное значение параметраpaymentDatetimeGreaterOrEqual',
			'206' => 'Неверное значение параметраpaymentDatetimeLessOrEqual',
			'207' => 'Неверное значение параметраoutputFields',
			'208' => 'В запросе указан пустой диапазон времени создания заказа',
			'209' => 'В запросе указан слишком большой диапазон времени создания заказа',
			'210' => 'В запросе указан пустой диапазон времени оплаты заказа',
			'211' => 'В запросе указан слишком большой диапазон времени оплаты заказа',
			'212' => 'Логическое противоречие между диапазоном времени оплаты и флагом «только оплаченные»',
			'213' => 'Не указано ни одно условие для ограничения выборки',
			'214' => 'В запросе по номеру заказа (orderNumber) не задан идентификатор магазина (shopId)',
			'215' => 'В запросе по номеру транзакции (invoiceId) не задан идентификатор магазина (shopId)',
			'216' => 'Результат содержит слишком много элементов',
			'217' => 'Неверное значение параметра partial',
			'402' => 'Неверное значение суммы',
			'403' => 'Неверное значение валюты',
			'404' => 'Неверное значение причины возврата',
			'405' => 'Неуникальный номер операции',
			'410' => 'Заказ не оплачен. Возврат невозможен',
			'411' => 'Неуспешный статус доставки уведомления о переводе',
			'412' => 'Валюта перевода отличается от заданной в запросе',
			'413' => 'Сумма возврата, заданная в запросе, превышает сумму перевода',
			'414' => 'Перевод возвращен ранее',
			'415' => 'Заказ с указанным номером транзакции (invoiceId) отсутствует',
			'416' => 'Недостаточно средств для проведения операции',
			'417' => 'Счет плательщика закрыт. Возврат средств на него невозможен.',
			'418' => 'Счет плательщика заблокирован. Возврат средств на него невозможен.',
			'419' => 'Сумма остатка после возврата части перевода меньше 1 рубля',
			'424' => 'Запрещен возврат части суммы для данного способа оплаты',
			'601' => 'Запрещено повторять платежи с банковских карт в пользу магазина',
			'602' => 'Повтор данного платежа запрещен',
			'603' => 'Для данной операции обязателен orderNumber',
			'604' => 'Неверное значение параметра cvv',
			'606' => 'Операция по данной карте запрещена',
			'607' => 'Превышен лимит. Невозможно выполнить операцию по карте',
			'608' => 'Недостаточно средств для проведения операции по карте',
			'609' => 'Техническая ошибка. Невозможно выполнить операцию по карте',
			'611' => 'Истек срок действия банковской карты',
			'612' => 'Операция по данной карте запрещена',
			'1000' => 'Техническая ошибка'
		);
		if (!isset($error[$id])) return $id;
		return $error[$id];
	}
	private function getDefaultArray($command, &$fields, &$level){
		$defArray=array();
		$defArray['shopId'] = $this->shopId;
		$defArray['requestDT'] = date('c');

		switch ($command){
			case "listOrders";
			case "listReturns";
				$fields = array('orderNumber','invoiceId','orderSumAmount', 'paymentType');
				$defArray['outputFields'] = implode(';',$fields);
				$level = true;
				break;
			case 'returnPayment':
				$defArray['currency'] = $this->getCurrencyCode();
				$defArray['clientOrderId'] = $this->getClientOrderId();
				$fields = array('status','error','techMessage','clientOrderId');
				$level = false;
				break;
		}
		return $defArray;
	}
	private function getClientOrderId(){
		return '010'.microtime(true);
	}

	private function getCurrencyCode(){
		return ($this->demo)?'10643':'643';
	}

	private function getUrlMws($command){
		$demo = ($this->demo)?'-demo':'';
		$port = ($this->demo)?':8083':'';
		$url_server="https://penelope$demo.yamoney.ru$port/webservice/mws/api/".$command;
		return $url_server;
	}

	private function sendRequest($url, $data, $crypt=true, $xml=true){
		$data = ($xml)?$this->createXml($data, $url):$data;
		$this->txt_request = $data;
		$send_data = ($crypt)? $this->sign_pkcs7($data):http_build_query($data);
		return $this->post($url, $send_data);
	}

	private function post($url, $xml){
		$ch = curl_init($this->getUrlMws($url));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14");  // useragent
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLCERT, $this->rwTmpFile($this->CertPem));
		curl_setopt($ch, CURLOPT_SSLKEY, $this->rwTmpFile($this->PkeyPem));
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}

	private function parseXML($xml, $attr=array(),$level=true){
		$answer=array();
		$doc = new DOMDocument();
		@$doc->loadXML($xml);
		if (empty($doc->firstChild)) return false;
		$order_xml=($level)?$doc->firstChild->firstChild:$doc->firstChild;
		foreach ($attr as $name) if (method_exists($order_xml,'hasAttribute') && $order_xml->hasAttribute($name)) $answer[$name]=$order_xml->getAttributeNode($name)->value; else $answer[$name]='';
		return $answer;
	}

	private function createXml($array, $operation){
		$domDocument = new DOMDocument('1.0', 'UTF-8');
		$domElement = $domDocument->createElement($operation."Request");
		foreach ($array as $name=>$val){
			$domAttribute = $domDocument->createAttribute($name);
			$domAttribute->value = $val;
			$domElement->appendChild($domAttribute);
			$domDocument->appendChild($domElement);
		}
		return (string) $domDocument->saveXML();
	}

	private function sign_pkcs7($xml){
		$dataFile = $this->rwTmpFile($xml);
		$signedFile = $this->rwTmpFile();
		if (openssl_pkcs7_sign ($dataFile , $signedFile ,$this->CertPem, $this->PkeyPem, array(), PKCS7_NOCHAIN+PKCS7_NOCERTS)){
			$signedData = explode("\n\n", file_get_contents($signedFile));
			return "-----BEGIN PKCS7-----\n".$signedData[1]."\n-----END PKCS7-----";
		}
	}
	private function rwTmpFile($data=null){
		$temp_file = tempnam(sys_get_temp_dir(), 'YaMWS');
		if ($data!==null) file_put_contents ($temp_file, $data);
		return $temp_file;
	}
}

?>