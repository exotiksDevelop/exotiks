<?php

class ControllerPaymentYamodule extends Controller {

    private $error;

	public function index() {
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$this->load->language($for23.'payment/yamodule');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->response->redirect($this->url->link($for23.'feed/yamodule', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function sendmail (){
		$this->load->model('sale/order');
		$this->load->model('setting/setting');

		$json = array();
		$order_id = (isset($this->request->get['order_id']))?$this->request->get['order_id']:0;
		if (!$this->config->get("ya_kassa_inv")){
			$json['error']="Этот функционал отключен в настройках модуля Яндекс.Кассы";
			$this->sendResponseJson ($json);
			return true;
		}elseif ($this->config->get("ya_kassa_send_check")){
            $json['error']="Этот функционал временно недоступен при работе по 54-ФЗ. Мы работаем над его появлением в новой версии модуля.";
            $this->sendResponseJson ($json);
            return true;
        }
		$order_info = $this->model_sale_order->getOrder($order_id);
		$to_email = $order_info['email'];
		$subject = $this->config->get("ya_kassa_inv_subject");
		$insert_logo = ($this->config->get("ya_kassa_inv_logo")=='1')?true:false;
		$text_instruction = html_entity_decode($this->config->get("ya_kassa_inv_message"));
		$products = $this->model_sale_order->getOrderProducts($order_id);
		$arUrl = array(
			'shopId' => $this->config->get('ya_kassa_sid'),
			'scid' => $this->config->get('ya_kassa_scid'),
			'customerNumber' => $order_info['email'],
			'orderNumber' => $order_id,
			'sum' => number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2, '.', ''),
			//'shopSuccessURL' => $this->url->link('checkout/success', '', 'SSL'),
			//'shopFailURL' => $this->url->link('checkout/failure', '', 'SSL'),
			'cps_email' => $order_info['email'],
			'cps_phone' => preg_replace("/[-+()]/",'',$order_info['telephone']),
			'cms_name' => 'ya_opencart2_invoice'
		);
		$url = "https://money.yandex.ru/eshop.xml?".http_build_query($arUrl);
		$logo = (is_file(DIR_IMAGE . $this->config->get('config_logo')))?DIR_IMAGE . $this->config->get('config_logo'):'';
		$pattern = array(
			'%order_id%' => $order_id,
			'%shop_name%' => $order_info['store_name']
		);
		foreach ($pattern as $name => $value){
			$text_instruction = str_replace($name, $value, $text_instruction);
			$subject = str_replace($name, $value, $subject);
		}

		$link_img = ($this->request->server['HTTPS'])?HTTPS_CATALOG:HTTP_CATALOG;
		$data = array(
			'shop_name' => $order_info['store_name'],
			'shop_url' => $order_info['store_url'],
			'shop_logo' => 'cid:'.basename($logo),
			'b_logo' => $insert_logo,
			'customer_name' => $order_info['customer'],
			'order_id' => $order_id,
			'sum' => number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2, '.', ''),
			'link' => $url,
			'yandex_button' => $link_img.'image/cache/yandex_buttons.png',
			'total' => $order_info['total'],
			'shipping' => $order_info['shipping_method'],
			'products' => $products,
			'instruction' => $text_instruction
		);
		$end_tpl = (version_compare(VERSION, "2.2.0", '>='))?'':".tpl";
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$message = $this->load->view($for23.'yamodule/invoice_msg'.$end_tpl, $data);

		try{
			$mail = new Mail();

			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($to_email);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_email'));
			$mail->setSubject($subject);
			$mail->addAttachment(DIR_CATALOG . 'view/theme/default/image/yandex_buttons.png');
			if ($logo!='') $mail->addAttachment($logo);
			$mail->setHtml($message);
			$mail->send();
		}catch(Exception $e){
			$json['error']= $e->getMessage();
			$this->sendResponseJson ($json);
		}
		$json['success'] = sprintf("Счет на оплату заказа %s выставлен", $order_id);

		$this->sendResponseJson ($json);
	}
	protected function sendResponseJson ($json){
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function invoice (){
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		//API
		$this->load->model('user/api');
		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));
		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}
		//end API
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$this->load->language($for23.'payment/yamodule');
        //$this->load->language('sale/order');

		$this->document->setTitle("Выставление счета");

		$this->load->model('sale/order');

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_orders'),
			'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_invoice'),
			'href' => $this->url->link($for23.'payment/yamodule/invoice', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['back'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], true);

		$data['orders'] = array();

		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_sale_order->getTotalOrders($filter_data);
		$results = $this->model_sale_order->getOrders($filter_data);

		foreach ($results as $result) {
			$status_name = (version_compare(VERSION, "2.2.0", '>='))?'order_status':"status";
			$data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'order_status'  => $result[$status_name],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code']
			);
		}

		$data['heading_title'] = $this->language->get('heading_invoice');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_back'] = $this->language->get('button_back');

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, true);
		$data['sort_customer'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);
		$data['sort_total'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link($for23.'payment/yamodule/invoice', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$end_tpl = (version_compare(VERSION, "2.2.0", '>='))?'':".tpl";
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$this->response->setOutput($this->load->view($for23.'yamodule/invoice_list'.$end_tpl, $data));
	}
	public function test(){
		$data = array();
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$this->load->model('setting/setting');
        $this->load->language($for23.'feed/yamodule');
        $this->load->language($for23.'payment/yamodule');

		$sid = $this->config->get('ya_kassa_sid');
		$sсid = $this->config->get('ya_kassa_scid');
		$psw = $this->config->get('ya_kassa_pw');
		$url = str_replace("http://","https://",HTTPS_CATALOG).'index.php?route='.$for23.'payment/yamodule/callback';

		$data['zeroTest'] = new checkSetting(array('shopId'=>$sid, 'scid'=> $sсid, 'shopPassword' => $psw));
		$firstTest = new checkConnection(array('url'=>$url));
		$data['firstTest'] = &$firstTest;
		$data['listTests'] = array('zeroTest','firstTest');
		if (!empty($firstTest->resultData)) $data['listTests'][]='secondTest';
		$data['secondTest'] = new checkXmlAnswer(array('raw'=> $firstTest->resultData, 'url' => $url, 'shopId'=>$sid));
        $data['email'] = (isset($this->request->post['email']))?filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL):$this->config->get('config_email');
        $data['from_email'] = $this->config->get('config_email');
        $data['text'] = "";
        $data['can_push'] = (isset($this->request->cookie['yamodule_kassa_sendmail_timeout']))?$this->request->cookie['yamodule_kassa_sendmail_timeout']:false;
        //
        if (($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()))
        {
            $mail_cc = filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL);
            $data['email'] = $mail_cc;
            $mail_text = htmlspecialchars($this->request->post['text']);
            $data['text'] = html_entity_decode($mail_text);
            $subject = sprintf($this->language->get('sendmail_subject'), $this->config->get('config_email'), $sid);

            $message = $this->language->get('mail_intro')."<br><br>";
            $message .= "CMS Opencart ver. ".VERSION.PHP_EOL;
            $message .= "Y.CMS Opencart ver. ".$this->language->get('ya_version').PHP_EOL;
            $message .= PHP_EOL;
            $message .= "shopId ".$sid.PHP_EOL;
            $message .= "scid ".$sсid.PHP_EOL;
            $message .= "hash ".md5($psw).PHP_EOL;
            $message .= "checkUrl/avisoUrl ".$url.PHP_EOL;
            $message .= PHP_EOL;
            foreach ($this->getMinimalExt() as $name=>$val)$message .= $this->language->get('mail_ext_title').$name." - ".$val. PHP_EOL;
            $message .= PHP_EOL;
            foreach ($data['listTests'] as $clsTest)  {
                $txt_Test = ($data[$clsTest]->done)?"OK":$data[$clsTest]->getWarnText();
                $message .= $data[$clsTest]->getTitle()." ".$txt_Test.PHP_EOL;
            }
            $message .= PHP_EOL;
            $message .= $this->language->get('mail_reply').$mail_cc.PHP_EOL;
            $message .= $this->language->get('mail_message').PHP_EOL;
            $message .= $mail_text;
            $message .= PHP_EOL;
            if ($this->sendMailForm("cms@yamoney.ru", $mail_cc, $subject, $message)) {
                $data['can_push'] = time();
                setcookie("yamodule_kassa_sendmail_timeout", $data['can_push'],time()+60*60*24);
            }
        }
        foreach (array("sendmail", "email", "text") as $name_error) if (isset($this->error[$name_error])) $data['error_'.$name_error] = $this->error[$name_error];
		//
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$end_tpl = (version_compare(VERSION, "2.2.0", '>='))?"":".tpl";
		$this->response->setOutput($this->load->view($for23.'yamodule/check'.$end_tpl, $data));
	}
	private function validateForm(){
        if ((utf8_strlen($this->request->post['text']) < 10) || (utf8_strlen($this->request->post['text']) > 1024)) {
            $this->error['text'] = $this->language->get('error_text');
        }
        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)){
            $this->error['email'] = $this->language->get('error_mail');
        }
        if (isset($this->request->cookie['yamodule_kassa_sendmail_timeout'])){
            $this->error['expire'] = $this->language->get('error_expire');
        }
        return !$this->error;
    }
	private function sendMailForm($to, $replay, $subject, $message){
        try{
            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($to);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_email'));
            $mail->setReplyTo($replay);
            $mail->setSubject($subject);
            $mail->setText($message);
            $mail->send();
            return true;
        }catch(Exception $e){
            $this->error['sendmail'] = sprintf($this->language->get('fail_sendmail'), $e->getMessage());
            return false;
        }
    }
	private function getMinimalExt(){
	    $arResult = array('curl'=>"", 'openssl'=>"", 'php'=>"");
        if (extension_loaded("curl")){
            $v_curl =curl_version();
            if (extension_loaded("openssl")){
                $arResult['openssl'] = $v_curl['ssl_version'];
            }
            $arResult['curl'] = $v_curl['version'];
        }
        $arResult['php'] = phpversion();
        return $arResult;
    }

	public function install() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('yamodule_status', array('yamodule_status' => 1));
	}

	public function uninstall() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('yamodule_status', array('yamodule_status' => 0));
	}
}
/*
 * Класс для отправки запросов с предопределенными данными (методом setType)
 */
class TestRequest {
	public static $url;
	public static $respHead;
	public static $respBody;
	public static $respInfo;
	public static $respError;

	private static $data;
	private static $varPost = array(
		"invoiceId"=>"123",
		"orderNumber"=>"1",
		"orderSumAmount"=>"10.00",
		"shopArticleId"=>"1",
		"paymentType"=>"PC",
		"action"=>"checkOrder",
		"shopId"=>"12345",
		"scid"=>"54321",
		"shopSumBankPaycash"=>"",
		"shopSumCurrencyPaycash"=>"",
		"orderSumBankPaycash"=>"",
		"orderSumCurrencyPaycash"=>"",
		"customerNumber"=>"",
		"md5"=>"",
	);

	public static function setType($type){
		$data = self::$varPost;
		switch($type){
			case "check":
				$data['action'] = 'checkOrder';
				break;
			case "aviso":
				$data['action'] = 'paymentAviso';
				break;
			default:
				break;
		}
		self::$data = $data;
	}
	public static function request(){
		$curlOpt = array(
			CURLOPT_HEADER => 1,
			CURLOPT_NOBODY => 0,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST =>  false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 1,
			CURLINFO_HEADER_OUT => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query(self::$data),
			CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
		);
		$curl = curl_init(self::$url);
		curl_setopt_array($curl, $curlOpt);

		$raw = curl_exec($curl);

		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		self::$respInfo = curl_getinfo($curl);
		self::$respHead = substr($raw, 0, $header_size);
		self::$respBody = substr($raw, $header_size);
		self::$respError = curl_error($curl);
		curl_close($curl);
		if ($raw === false) {
			return false;
		}
		return true;
	}
}

/*
 *
 */
class TestParse{
	public static $raw = '';
	public static $optFind = array();
	public static $arResult = array();

	public static function parseXML($level=false){
		$answer=array();
		$doc = new DOMDocument();
		@$doc->loadXML(self::$raw);
		if (empty($doc->firstChild)) return false;
		$order_xml=($level)?$doc->firstChild->firstChild:$doc->firstChild;
		foreach (self::$optFind as $name) if (method_exists($order_xml,'hasAttribute') && $order_xml->hasAttribute($name)) $answer[$name]=$order_xml->getAttributeNode($name)->value;
		self::$arResult = $answer;
		return count($answer)>0;
	}
}
/*
 *
 */
class TestBaseClass{
	public $resultData='';
	public $name = "Original";
	public $done = false;
	public $readyNext = true;
	protected $conf = array();
	protected $warning = array();
	protected $successText = "<span class=\"label label-success\">ОК</span>";
	protected $failText = "";

	protected function runTest(){
		return true;
	}
	public function getWarn(){
		if (count($this->warning)>0) return $this->warning;
		return array();
	}

    public function getWarnText(){
        $sResult = "";
        if (count($this->warning)>0) foreach ($this->warning as $item => $value) $sResult.=$value['text'].PHP_EOL;
        return $sResult;
    }

	public function getTitle(){
		return $this->name;
	}

	public function getWarnHtml(){
		$html = "<div class=''>";
		foreach ($this->warning as $item){
			$html.= sprintf("<div class=\"alert alert-warning\"><b>%s</b><p>%s</p></div>", $item['text'], $item['action']);
		}
		return $html."</div>";
	}

	public function getResult(){
		//$this->runTest();
		if ($this->done == true){
			return $this->successText;
		}
		return $this->failText;
	}
}

class checkSetting extends TestBaseClass{
	public $name;

	const NAME = "Проверка параметров магазина";

	const ERROR_SHOPID = "Ошибка в идентификаторе магазина";
	const ERROR_SHOPID_ACTION = "Параметр в поле Shop ID указан неправильно или не указан вообще.
    Чтобы не ошибиться, скопируйте ваш Shop ID в <a href='https://wiki.yamoney.ru/money.yandex.ru/my'>личном кабинете Яндекс.Кассы</a> и вставьте его в настройки модуля.
    Затем повторите проверку.";

	const ERROR_SCID = "Ошибка в номере витрины магазина";
	const ERROR_SCID_ACTION = "Параметр в поле scid указан неправильно или не указан вообще.
	Чтобы не ошибиться, скопируйте ваш scid в <a href='https://wiki.yamoney.ru/money.yandex.ru/my'>личном кабинете Яндекс.Кассы</a> и вставьте его в настройки модуля.
	Затем повторите проверку.";

	const ERROR_SHOPPSW = "Ошибка в секретном слове магазина";
	const ERROR_SHOPPSW_ACTION = "Параметр в поле ShopPassword указан неправильно или не указан вообще.
	Чтобы не ошибиться, скопируйте ваш ShopPassword в <a href='https://wiki.yamoney.ru/money.yandex.ru/my'>личном кабинете Яндекс.Кассы</a> и вставьте его в настройки модуля.
	Затем повторите проверку.";

	public function __construct($conf){
		$this->conf = $conf;
		$this->name = self::NAME;
		$this->runTest();
	}
	public function getTitle(){
		return $this->name;
	}
	protected function runTest(){
		if (intval($this->conf['shopId']) != $this->conf['shopId'] || $this->conf['shopId']<=0){
			$this->warning[]=array('text'=> self::ERROR_SHOPID , 'action'=> self::ERROR_SHOPID_ACTION);
		}
		if ($this->conf['scid']<=0){
			$this->warning[]=array('text'=> self::ERROR_SCID, 'action'=> self::ERROR_SCID_ACTION);
		}
		if (strlen($this->conf['shopPassword'])>20){
			$this->warning[]=array('text'=> self::ERROR_SHOPPSW, 'action'=>self::ERROR_SHOPPSW_ACTION);
		}

		if (count($this->warning)==0) $this->done = true;
	}
}

/**
 * Created by PhpStorm.
 * User: ivkarmanov
 * Date: 20.05.2016
 * Time: 12:27
 */

class checkConnection extends TestBaseClass{
	public $name;

	const NAME = "Соединение по checkURL/avisoURL";

	const ERROR_30x = "Ошибка: запрос сервера был перенаправлен";
	const ERROR_30x_ACTION = "Убедитесь, что ваш сервер работает по протоколу HTTPS, и проверьте правила обработки запросов (mod_rewrite, htaccess, rewrite).
        Отключите заглушки, которые могут перенаправлять запросы сервера на другие страницы вашего сайта. Затем повторите проверку.
        Информация для вебмастеров вашего сайта:
        Ответ веб-сервера на POST-запрос был выполнен с кодом %s и перенаправлением на адрес %s.";

	const ERROR_x = "Ошибка: ваш сайт не отвечает или отвечает неправильно";
	const ERROR_x_ACTION = "Убедитесь, что ваш сервер работает по протоколу HTTPS, и проверьте правила обработки запросов (mod_rewrite, htaccess, rewrite).
        Отключите заглушки, которые могут влиять на соединение с сайтом. Затем повторите проверку.
        Информация для вебмастеров вашего сайта:
        Код ответа сервера (%d) на POST-запрос не равен правильному коду (200).";

	const ERROR_0 = "Не получилось установить соединение с вашим сайтом";
	const ERROR_0_ACTION = "Чтобы закончить проверку, включите «Тестовый режим» в настройках модуля и сделайте тестовый платеж.
        Если он пройдет успешно, модуль работает правильно, и вы можете принимать реальные платежи.
        Если в процессе тестового платежа возникнет ошибка, напишите о ней специалистам Яндекс.Кассы на yamoney_shop@yamoney.ru.";


	public function __construct($conf){
		$this->conf = $conf;
		$this->name = self::NAME;
		$this->runTest();
	}
	public function getTitle(){
		return $this->name;
	}
	protected function runTest(){
		TestRequest::$url = $this->conf['url'];
		TestRequest::setType('check');
		if (TestRequest::request() === true){
			if (TestRequest::$respInfo['http_code']==200){
				$this->done = true;
				$this->resultData = TestRequest::$respBody;
			}elseif(!empty(TestRequest::$respInfo['redirect_url'])){
				$this->warning[]=array('text'=> self::ERROR_30x, 'action' => sprintf( self::ERROR_30x_ACTION, TestRequest::$respInfo['http_code'], TestRequest::$respInfo['redirect_url']));
			}else{
				$this->warning[]=array('text'=>self::ERROR_x, 'action' => sprintf(self::ERROR_x_ACTION, TestRequest::$respInfo['http_code']));
			}
		}else{
			$this->warning[]=array('text'=>self::ERROR_0, 'action' => self::ERROR_0_ACTION);
		}
	}
}

class checkXmlAnswer extends TestBaseClass{
	public $name;

	const NAME = "Соединение между сервером магазина и сервером Яндекс.Кассы";
	const ERROR_BAD_ATTR = "Ошибка в ответе вашего сервера";
	const ERROR_BAD_ATTR_ACTION = "Напишите об этом специалистам Яндекс.Кассы на yamoney_shop@yamoney.ru.
        В тему поставьте «Ошибки автотеста, Opencart 2, Shop Id %d».
        В письмо скопируйте следующий текст:
        «На тестовый POST-запрос по адресу %s был получен ответ, который не содержит обязательных параметров для работы с сервисом «Яндекс.Касса».
        Полный текст ответа: %s».";

	const ERROR_BAD_XML = "Сайт отвечает с ошибкой";
	const ERROR_BAD_XML_ACTION = "Убедитесь, что:
        — ваш сервер работает по протоколу HTTPS,
        — в ответе нет лишних символов,
        — в панели управления нет модулей, которые принудительно выводят информацию на все страницы сайта (например, панели чатов или рекламу хостинга).
        Затем повторите проверку.
        Информация для вебмастеров вашего сайта:
        При POST-запросе на адрес %s был получен документ, который отличается от xml-документа. Документ начинается со следующих символов (первые 20): %s.";

	public function __construct($conf){
		$this->name = self::NAME;
		$this->conf = $conf;
		$this->runTest();
	}

	protected function runTest(){
		TestParse::$raw = $this->conf['raw'];
		TestParse::$optFind = array('code', 'invoiceId', 'shopId');
		if (TestParse::parseXML()=== true){
			$arResult = TestParse::$arResult;
			if (isset($arResult['code']) && isset($arResult['invoiceId']) && isset($arResult['shopId'])){
				$this->done = true;
			}else{
				$this->warning[]= sprintf(self::ERROR_BAD_ATTR, $this->conf['shopId'], $this->conf['url'], $this->conf['raw']);
			}
		}else{
			$sLen = (strlen($this->conf['raw'])>=20)?20:strlen($this->conf['raw']);
			$this->warning[]=array(
				"text" => self::ERROR_BAD_XML,
				"action" => sprintf(self::ERROR_BAD_XML_ACTION, $this->conf['url'], substr($this->conf['raw'], 0, $sLen))
			);
		}
	}
}

class ControllerExtensionPaymentYamodule extends ControllerPaymentYamodule {}