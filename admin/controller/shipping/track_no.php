<?php
/**
* Order Track Number for OpenCart (ocStore) 2.x
*
* @author Alexander Toporkov <toporchillo@gmail.com>
* @copyright (C) 2014- Alexander Toporkov
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
class ControllerShippingTrackNo extends Controller {
	protected $error = array();
	public $LOG_LEVEL = 4;
	
	public $CONFIG = array(
		'ignore_security'=>0,
		
		'change_status'=>1,
		'order_status'=>2,
		//'order_history'=>'Заказ отправлен, трек-номер: {track_no}.',
		
		'email_notify'=>1,
		'email_text'=>'Уважаемый {shipping_firstname} {shipping_lastname}, ваш заказ №{order_id} передан службе доставки, трек-номер: {track_no}.',

		'sms_notify'=>0,
		'sms_text'=>'Ваш заказ №{order_id} передан службе доставки, трек-номер: {track_no}.',
		
		'export_liveinform'=>0,
		'liveinform_api_id'=>'',
		'liveinform_sync'=>0,
		'shipping_status'=>2,
		'postoffice_status'=>2,
		'issued_status'=>3,
		'return_status'=>8,
		'sync_comment'=>'Статус доставки: "{operation}, {operation_text}"; Почтовое отделение: "{geo}, {index}"'
	);

	private function setConfig() {
		if ($this->config->get('track_no_set')) {
			foreach($this->CONFIG as $key=>$conf) {
				$this->CONFIG[$key] = $this->config->get('track_no_'.$key);
			}
		}
	}
	
	public function install() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` LIMIT 1");
		if (!isset($query->row['track_no'])) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD  `track_no` VARCHAR(32) NOT NULL AFTER `order_id`");
		}
	}

	public function uninstall() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code`='track_no'");
	}
	
	public function index() {
		$this->load->language('shipping/track_no');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['track_no_order_statuses'] = isset($this->request->post['track_no_order_statuses']) ? implode(',', $this->request->post['track_no_order_statuses']) : '';
			$this->model_setting_setting->editSetting('track_no', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}
		$data = array();
		
		$data['liveinform_sync_url'] = HTTP_CATALOG.'index.php?route=api/track_no/update_liveinform';
		
		$this->setConfig();
		if (isset($this->request->post['track_no_order_statuses'])) {
			$data['track_no_order_statuses'] = $this->request->post['track_no_order_statuses'];
		} else {
			$data['track_no_order_statuses'] = explode(',', $this->config->get('track_no_order_statuses'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/track_no', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('shipping/track_no', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'); 		
		
		
		$this->load->model('localisation/order_status');
    	$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		
		if (isset($this->request->post['track_no_status'])) {
			$data['track_no_status'] = $this->request->post['track_no_status'];
		} else {
			$data['track_no_status'] = $this->config->get('track_no_status');
		}
		
		foreach($this->CONFIG as $key=>$conf) {
			if (isset($this->request->post['track_no_'.$key])) {
				$data['track_no_'.$key] = $this->request->post['track_no_'.$key];
			} else {
				$data['track_no_'.$key] = $conf;
			}
		}

		$data['sms_on'] = $this->config->get('config_sms_alert');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$data['store'] = HTTPS_CATALOG;
		$data['token'] = $this->session->data['token'];

		// API login

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
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		
		$this->response->setOutput($this->load->view('shipping/track_no.tpl', $data));		
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/track_no')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		else if (isset($this->request->post['track_no_export_liveinform']) && $this->request->post['track_no_export_liveinform'] && !$this->request->post['track_no_liveinform_api_id']) {
			$this->error['warning'] = 'Вы выбрали экспортировать заказы в LiveInform.ru, но не указали API ID.';
		}
		else if (isset($this->request->post['track_no_liveinform_sync']) && $this->request->post['track_no_liveinform_sync'] && !$this->request->post['track_no_liveinform_api_id']) {
			$this->error['warning'] = 'Вы выбрали синхронизировать статусы с LiveInform.ru, но не указали API ID.';
		}
		else if (isset($this->request->post['track_no_liveinform_sync']) && !isset($this->request->post['track_no_order_statuses'])) {
			$this->error['warning'] = 'Укажите один или несколько статусов заказа, которые не надо отслеживать.';
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	/**
	Импорт трек-номеров из CSV-файла. 
	В первой колонке order_id, во второй трек-номер
	*/
	public function import() {
		$json = array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				$fp = fopen($_FILES['file']['tmp_name'], 'r');
				$i = 0;
				while (($str = fgets($fp, 4096)) !== false) {
					$i++;
					$matches = array();
					if (preg_match('/^(\d+)[,;]([\d\w]+)/i', $str, $matches)) {
						$order_id = $matches[1];
						$track_no = $matches[2];
						
						$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_id='".(int)$order_id."'");
						if ($query->rows) {
							$out = $this->setTrackNo($order_id, $track_no);
							try {
								$jsonout = json_decode($out, true);
							}
							catch(Exception $e) {
								$json[] = array('error'=>'Строка №'.$i.', заказ #'.$order_id.': не удалось обновить историю заказа');
								continue;
							}
							if (isset($jsonout['success'])) {
								$jsonout['success'] = 'Строка №'.$i.', заказ #'.$order_id.': '.$jsonout['success'];
							}
							if (isset($jsonout['error'])) {
								$jsonout['error'] = 'Строка №'.$i.', заказ #'.$order_id.': '.$jsonout['error'];
							}
							$json[] = $jsonout;
							
						}
						else {
							$json[] = array('error'=>'Строка №'.$i.', заказ #'.$order_id.': не найден');
						}
					}
					//Если в первой строке разобрать не получилось, значит эта строка - заголовок = все в порядке
					elseif ($i>1) {
						$json[] = array('error'=>'Строка №'.$i.' в CSV-файле не содержит ID заказа и трек-номер');
					}
				}				
				fclose($fp);
			}
			else {
				$json['error'] = 'Не удалось загрузить файл';
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
    /**
     * Сохраним токен сначала при помощи запроса
     */
    private function setTrackNo($order_id, $track_no) {
        $ch = curl_init();
        $link = HTTPS_CATALOG.'index.php?route=api/track_no/save&token='.$this->session->data['token'].'&order_id='.$order_id;
        $req_data = array('track_no'=>$track_no);
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET track_no='".$this->db->escape($track_no)."' WHERE order_id='".(int)$order_id."'");
		return $out;
    }
}
