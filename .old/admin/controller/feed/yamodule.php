<?php

/**
 * Class ControllerFeedYamodule
 *
 * @property ModelExtensionExtension $model_extension_extension
 */
class ControllerFeedYamodule extends Controller {

	private $error = array();
	public $fields_p2p = array(
		'ya_p2p_active',
		'ya_p2p_number',
		'ya_p2p_test',
		'ya_p2p_idapp',
		'ya_p2p_pw',
		'ya_p2p_log',
		'ya_p2p_os'
	);
	
	public $fields_kassa = array(
		'ya_kassa_active',
        'yamodule_total_sort_order',
		'ya_kassa_sid',
        'ya_kassa_scid',
        'ya_kassa_pw',
        'ya_kassa_test',
		'ya_kassa_log',
		'ya_kassa_paymode',
		'ya_kassa_paylogo',
        'ya_kassa_ym',
		'ya_kassa_cards',
		'ya_kassa_cash',
		'ya_kassa_mobile',
		'ya_kassa_sber',
		'ya_kassa_alfa',
		'ya_kassa_wm',
		'ya_kassa_pb',
		'ya_kassa_ma',
		'ya_kassa_qw',
		'ya_kassa_cr',
		'ya_kassa_os',
		'ya_kassa_cart_reset',
		'ya_kassa_create_order',
		'ya_kassa_inv',
		'ya_kassa_inv_logo',
		'ya_kassa_inv_message',
		'ya_kassa_inv_subject',
        'ya_kassa_send_check',
        'ya_kassa_tax',
        'ya_kassa_tax_default',
        'ya_kassa_show_in_footer',
	);

	public $fields_metrika = array(
		'ya_metrika_active',
		'ya_metrika_number',
		'ya_metrika_idapp',
		'ya_metrika_pw',
		'ya_metrika_webvizor',
		'ya_metrika_otkaz',
		'ya_metrika_clickmap',
		'ya_metrika_out',
		'ya_metrika_hash',
		'ya_metrika_cart',
		'ya_metrika_order',
	);
	
	public $fields_market = array(
		'ya_market_active',
		'ya_market_catall',
		'ya_market_prostoy',
		'ya_market_set_available',
		'ya_market_shopname',
		'ya_market_localcoast',
        'ya_market_localdays',
        'ya_market_stock_days',
        'ya_market_stock_cost',
		'ya_market_available',
		//'ya_market_homecarrier',
		'ya_market_combination',
		'ya_market_features',
		'ya_market_dimensions',
		'ya_market_allcurrencies',
		'ya_market_store',
		'ya_market_delivery',
		'ya_market_pickup',
		'ya_market_color_options',
		'ya_market_size_options',
		'ya_market_categories'
	);

	public $fields_pokupki = array(
		'ya_pokupki_stoken',
		'ya_pokupki_yapi',
		'ya_pokupki_number',
		'ya_pokupki_idapp',
		'ya_pokupki_pw',
		'ya_pokupki_idpickup',
		'ya_pokupki_yandex',
		'ya_pokupki_sprepaid',
		'ya_pokupki_cash',
		'ya_pokupki_bank',
		'ya_pokupki_carrier',
		'ya_pokupki_status_pickup',
		'ya_pokupki_status_cancelled',
		'ya_pokupki_status_delivery',
		'ya_pokupki_status_delivered',
		'ya_pokupki_status_processing',
		'ya_pokupki_status_unpaid'
	);

	public $fields_fast_pay = array(
	    'ya_fast_pay_active',
        'ya_fast_pay_id',
        'ya_fast_pay_description',
        'ya_fast_pay_os'
    );

	public function initErrors()
	{
		$data = array();
		$status = array();
		foreach(array('pickup','cancelled','delivery','processing','unpaid','delivered') as $val){
			$status[] = $this->config->get('ya_pokupki_status_'.$val);
		}
		$status = array_unique($status);

		if ($this->config->get('ya_pokupki_stoken') == '')
			$data['pokupki_status'][] = $this->errors_alert('Токен не заполнен!');
		if ($this->config->get('ya_pokupki_yapi') == '')
			$data['pokupki_status'][] = $this->errors_alert('URL api не заполнен');
		if ($this->config->get('ya_pokupki_number') == '')
			$data['pokupki_status'][] = $this->errors_alert('Номер кампании не заполнен');
		if ($this->config->get('ya_pokupki_idapp') == '')
			$data['pokupki_status'][] = $this->errors_alert('ID приложения не заполнен');
		if ($this->config->get('ya_pokupki_pw') == '')
			$data['pokupki_status'][] = $this->errors_alert('Пароль приложения не заполнен');
		if ($this->config->get('ya_pokupki_gtoken') == '')
			$data['pokupki_status'][] = $this->errors_alert('Токен yandex не получен!');
		if (count($status)!=6)
			$data['pokupki_status'][] = $this->errors_alert('Статусы для передачи в Яндекс.Маркет должны быть уникальными');

		if ($this->config->get('ya_market_shopname') == '')
			$data['market_status'][] = $this->errors_alert('Не введено название магазина');
		if ($this->config->get('ya_market_localcoast') == '')
			$data['market_status'][] = $this->errors_alert('Введите стоимость доставки в домашнем регионе');
        if ($this->config->get('ya_market_localdays') == '')
            $data['market_status'][] = $this->errors_alert('Введите срок доставки в домашнем регионе');

		if ($this->config->get('ya_metrika_number') == '')
			$data['metrika_status'][] = $this->errors_alert('Не заполнен номер счётчика');
		if ($this->config->get('ya_metrika_idapp') == '')
			$data['metrika_status'][] = $this->errors_alert('ID Приложения не заполнено');
		if ($this->config->get('ya_metrika_pw') == '')
			$data['metrika_status'][] = $this->errors_alert('Пароль приложения не заполнено');
		if ($this->config->get('ya_metrika_o2auth') == '')
			$data['metrika_status'][] = $this->errors_alert('Получите токен OAuth');
		
		if ($this->config->get('ya_p2p_number') == '')
			$data['p2p_status'][] = $this->errors_alert('Введите номер кошелька');
		if ($this->config->get('ya_p2p_idapp') == '')
			$data['p2p_status'][] = $this->errors_alert('Введите ID Приложения');
		if ($this->config->get('ya_p2p_pw') == '')
			$data['p2p_status'][] = $this->errors_alert('Введите секретный ключ');
		
		if ($this->config->get('ya_kassa_sid') == '')
			$data['kassa_status'][] = $this->errors_alert('ShopId не заполнен');
		if ($this->config->get('ya_kassa_scid') == '')
			$data['kassa_status'][] = $this->errors_alert('SCID Не заполнен');

		if (!$this->config->get('ya_fast_pay_id')) {
            $data['fast_pay_status'][] = $this->errors_alert('Идентификатор платежки не заполнен');
        }

        if (!$this->config->get('ya_fast_pay_description')) {
            $data['fast_pay_status'][] = $this->errors_alert('Введите назначение платежа');
        }


		if (empty($data['market_status']))
			$data['market_status'][] = '';//$this->success_alert('Все необходимые настроки заполнены!');
		if (empty($data['kassa_status']))
			$data['kassa_status'][] = '';//$this->success_alert('Все необходимые настроки заполнены!');
		if (empty($data['p2p_status']))
			$data['p2p_status'][] = '';//$this->success_alert('Все необходимые настроки заполнены!');
		if (empty($data['metrika_status']))
			$data['metrika_status'][] = '';//$this->success_alert('Все необходимые настроки заполнены!');
		if (empty($data['pokupki_status']))
			$data['pokupki_status'][] = '';//$this->success_alert('Все необходимые настроки заполнены!');
		return $data;
	}

	public function Sget($name)
	{
		$query = $this->db->query('SELECT * FROM '.DB_PREFIX.'setting WHERE `key` = "'.$name.'"');
		return (isset($query->row['value']) ? $query->row['value'] : '');
	}

	public function sendStatistics()
	{
		$headers = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$this->load->language($for23.'feed/yamodule');
		
		$array = array(
			'url' => HTTP_CATALOG,
			'cms' => 'opencart2',
			'version' => VERSION,
			'ver_mod' => $this->language->get('ya_version'),
			'email' => $this->Sget('config_email'),
			'shopid' => $this->Sget('ya_kassa_sid'),
			'settings' => array(
				'kassa' => (bool) $this->Sget('ya_kassa_active'),
                'kassa_epl' => (bool) $this->Sget('ya_kassa_paymode'),
				'p2p' => (bool) $this->Sget('ya_p2p_active'),
				'metrika' =>(bool) $this->Sget('ya_metrika_active'),
			)
		);

		$key_crypt = gethostbyname($_SERVER['HTTP_HOST']);
		$array_crypt = base64_encode(serialize($array));

		$url = 'https://statcms.yamoney.ru/v2/';
		$curlOpt = array(
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLINFO_HEADER_OUT => true,
			CURLOPT_POST => true,
		);

		$curlOpt[CURLOPT_HTTPHEADER] = $headers;
		$curlOpt[CURLOPT_POSTFIELDS] = http_build_query(array('data' => $array_crypt, 'lbl'=>0));

		$curl = curl_init($url);
		curl_setopt_array($curl, $curlOpt);
		$rbody = curl_exec($curl);
		$errno = curl_errno($curl);
		$error = curl_error($curl);
		$rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		
		$json=json_decode($rbody);
		if ($rcode==200 && isset($json->new_version)){
			return sprintf($this->language->get('text_need_update'), $json->new_version);
		}else{
			return false;
		}
	}

	public function saveData($source)
    {
        foreach ($source as $s) {
            $false = 0;
            if (in_array($s, array('ya_market_color_options', 'ya_market_size_options'))) {
                $false = array(0);
            }

            if (isset($this->request->post[$s]) && !empty($this->request->post[$s])) {
                if ($s === 'ya_market_categories') {
                    $data = array($s => implode(',', $this->request->post[$s]));
                } else {
                    $data = array($s => $this->request->post[$s]);
                }
                $this->model_setting_setting->editSetting($s, $data);
            } else {
                $this->model_setting_setting->editSetting($s, array($s => $false));
            }
        }
    }

	public function processSave()
	{
		$this->session->data['kassa_status'] = array();
		$this->session->data['p2p_status'] = array();
		$this->session->data['metrika_status'] = array();
		$this->session->data['market_status'] = array();
		$this->session->data['pokupki_status'] = array();
        $this->session->data['fast_pay_status'] = array();
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$this->load->language($for23.'feed/yamodule');
		switch($this->request->post['type_data'])
		{
			case 'kassa':
				$this->saveData($this->fields_kassa);
				$this->session->data['kassa_status'][] = $this->success_alert($this->language->get('text_success'));
				if(isset($this->request->post['ya_kassa_active']) && $this->request->post['ya_kassa_active'] == 1){
					$testUrl = $this->url->link($for23.'payment/yamodule/test', 'token=' . $this->session->data['token'], 'SSL');
					$this->session->data['kassa_status'][] = '<div class="alert"><a  class="btn btn-success" target="_blank" href="'.$testUrl.'">Проверить работу модуля</a></div>';
					$this->model_setting_setting->editSetting('ya_p2p_active', array('ya_p2p_active' => 0));
                    $this->model_setting_setting->editSetting('ya_fast_pay_active', array('ya_fast_pay_active' => 0));
				}
				break;
			case 'p2p':
				$this->saveData($this->fields_p2p);
				$this->session->data['p2p_status'][] = $this->success_alert($this->language->get('text_success'));
				if(isset($this->request->post['ya_p2p_active']) && $this->request->post['ya_p2p_active'] == 1) {
                    $this->model_setting_setting->editSetting('ya_kassa_active', array('ya_kassa_active' => 0));
                    $this->model_setting_setting->editSetting('ya_fast_pay_active', array('ya_fast_pay_active' => 0));
                }
				break;
			case 'metrika':
				$this->saveData($this->fields_metrika);
				$this->session->data['metrika_status'][] = $this->success_alert($this->language->get('text_success'));
				$this->load->model($for23.'yamodule/metrika');
				$yaMetrika_token = $this->config->get('ya_metrika_o2auth');
				$yaMetrika_number = ($this->request->post['ya_metrika_number'] != $this->config->get('ya_metrika_number') ? $this->request->post['ya_metrika_number'] : $this->config->get('ya_metrika_number'));
				$this->{'model_'.str_replace("/", "_", $for23).'yamodule_metrika'}->processCounter($yaMetrika_number, $yaMetrika_token);
				break;
			case 'market':
				$this->session->data['market_status'][] = $this->success_alert($this->language->get('text_success'));
				$this->saveData($this->fields_market);
				break;
			case 'pokupki':
				$this->saveData($this->fields_pokupki);
				$this->session->data['pokupki_status'][] = $this->success_alert($this->language->get('text_success'));

				break;

            case 'fast_pay':
                $this->saveData($this->fields_fast_pay);
                if(isset($this->request->post['ya_fast_pay_active']) && $this->request->post['ya_fast_pay_active'] == 1) {
                    $this->model_setting_setting->editSetting('ya_kassa_active', array('ya_kassa_active' => 0));
                    $this->model_setting_setting->editSetting('ya_p2p_active', array('ya_p2p_active' => 0));
                }
                $this->session->data['fast_pay_status'][] = $this->success_alert($this->language->get('text_success'));
                break;

		}
		$updater = $this->sendStatistics();
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
        if (!$this->Sget('ya_kassa_active') && !$this->Sget('ya_p2p_active') && !$this->Sget('ya_fast_pay_active')) {
            $this->model_setting_setting->editSetting('yamodule_status', array('yamodule_status' => 0));
        } else{
            $this->model_setting_setting->editSetting('yamodule_status', array('yamodule_status' => 1));
        }
		if ($updater!==false) foreach (array('kassa','p2p','metrika','market','pokupki', 'fast_pay') as $type) $this->session->data[$type.'_status'][] = $this->success_alert($updater, 'warning');
		
	}
	public function initForm($array)
	{
	    $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
        foreach ($array as $a) {
            $data[$a] = $this->config->get($a);
        }

        $https = str_replace("http://","https://",HTTPS_CATALOG);
		$data['ya_kassa_check'] = $https.'index.php?route='.$for23.'payment/yamodule/callback';
		$data['ya_kassa_aviso'] = $https.'index.php?route='.$for23.'payment/yamodule/callback';
		$data['ya_pokupki_sapi'] = HTTPS_CATALOG.'yandexbuy';
		if ($this->config->get('config_secure'))
		{
			$data['ya_kassa_fail'] = HTTPS_CATALOG.'index.php?route=checkout/failure';
			$data['ya_kassa_success'] = HTTPS_CATALOG.'index.php?route=checkout/success';
			$data['ya_p2p_linkapp'] = HTTPS_CATALOG.'index.php?route='.$for23.'payment/yamodule/inside';
			$data['ya_market_lnk_yml'] = $https.'index.php?route='.$for23.'feed/yamarket';
		}
		else
		{
			$data['ya_kassa_fail'] = HTTP_CATALOG.'index.php?route=checkout/failure';
			$data['ya_kassa_success'] = HTTP_CATALOG.'index.php?route=checkout/success';
			$data['ya_p2p_linkapp'] = HTTP_CATALOG.'index.php?route='.$for23.'payment/yamodule/inside';
			$data['ya_market_lnk_yml'] = $https.'index.php?route='.$for23.'feed/yamarket';
		}

		$data['ya_metrika_callback_url'] = 'https://oauth.yandex.ru/authorize?response_type=code&client_id='.$this->config->get('ya_metrika_idapp').'&device_id='.md5('metrika'.$this->config->get('ya_metrika_idapp')).'&client_secret='.$this->config->get('ya_metrika_pw');
		$data['ya_metrika_callback'] = str_replace('http://', 'https://', $this->url->link($for23.'feed/yamodule/preparem', 'token='.$this->session->data['token'], true));
		$data['ya_pokupki_callback_url'] = 'https://oauth.yandex.ru/authorize?response_type=code&client_id='.$this->config->get('ya_pokupki_idapp').'&device_id='.md5('pokupki'.$this->config->get('ya_pokupki_idapp')).'&client_secret='.$this->config->get('ya_pokupki_pw');
		$data['ya_pokupki_callback'] = str_replace('http://', 'https://', $this->url->link($for23.'feed/yamodule/preparep', 'token='.$this->session->data['token'], true));
		$data['ya_pokupki_gtoken'] = $this->config->get('ya_pokupki_gtoken');
		$data['ya_metrika_o2auth'] = $this->config->get('ya_metrika_o2auth');
		$data['token_url'] = 'https://oauth.yandex.ru/token?';

		$data['mod_status'] = true;

		return $data;
	}
	
	public function preparem()
	{
		return $this->gocurl('m', 'grant_type=authorization_code&code='.$this->request->get['code'].'&client_id='.$this->config->get('ya_metrika_idapp').'&client_secret='.$this->config->get('ya_metrika_pw'));
	}
	
	public function preparep()
	{
		return $this->gocurl('p', 'grant_type=authorization_code&code='.$this->request->get['code'].'&client_id='.$this->config->get('ya_pokupki_idapp').'&client_secret='.$this->config->get('ya_pokupki_pw'));
	}

	public function gocurl($type, $post)
	{
		$url = 'https://oauth.yandex.ru/token';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 9);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);  
		$data = json_decode($result);
		if ($status == 200) {
			if (!empty($data->access_token))
			{
				$this->load->model('setting/setting');
				if($type == 'm')
					$this->model_setting_setting->editSetting('ya_metrika_o2auth', array('ya_metrika_o2auth' => $data->access_token));
				elseif($type == 'p')
					$this->model_setting_setting->editSetting('ya_pokupki_gtoken', array('ya_pokupki_gtoken' => $data->access_token));
				$this->response->redirect($this->url->link('extension/feed/yamodule', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$this->response->redirect($this->url->link('extension/feed/yamodule', 'err='.$data->error_description.'&token=' . $this->session->data['token'], 'SSL'));
	}

	public function treeItem($id, $name)
	{
		$html = '<li class="tree-item">
						<span class="tree-item-name">
							<input type="checkbox" name="ya_market_categories[]" value="'.$id.'">
							<i class="tree-dot"></i>
							<label class="">'.$name.'</label>
						</span>
					</li>';
		return $html;
	}

	public function treeFolder($id, $name)
	{
		$html = '<li class="tree-folder">
					<span class="tree-folder-name">
						<input type="checkbox" name="ya_market_categories[]" value="'.$id.'">
						<i class="icon-folder-open"></i>
						<label class="tree-toggler">'.$name.'</label>
					</span>
					<ul class="tree" style="display: block;">'.$this->treeCat($id).'</ul>
				</li>';
		return $html;
	}

	public function treeCat($id_cat = 0)
	{
		$html = '';
		$categories = $this->getCategories($id_cat);
		foreach ($categories as $category)
		{
			$children = $this->getCategories($category['category_id']);
			if (count($children))
			{
				$html .= $this->treeFolder($category['category_id'], $category['name']);
			}
			else
			{
				$html .= $this->treeItem($category['category_id'], $category['name']);
			}
		}

		return $html;
	}

	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}

	public function carrierList()
	{
        $types = array('POST' => "Доставка почтой", 'PICKUP' => "Самовывоз", 'DELIVERY' => "Доставка курьером");
		$this->load->model('extension/extension');
		$extensions = $this->model_extension_extension->getInstalled('shipping');
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/shipping/' . $value . '.php')) {
				//$this->model_extension_extension->uninstall('shipping', $value);
				unset($extensions[$key]);
			}
		}
		$data['extensions'] = array();
		$files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
		if ($files)
		{
			foreach ($files as $file)
			{
				$extension = basename($file, '.php');
				if (in_array($extension, $extensions))
				{
                    $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
					$this->load->language($for23.'shipping/' . $extension);
					$data['extensions'][] = array(
						'name'       => $this->language->get('heading_title'),
						'sort_order' => $this->config->get($extension . '_sort_order'),
						'installed'  => in_array($extension, $extensions),
						'ext' => $extension
					);
				}
			}
		}
		$html = '';
		$save_data = $this->config->get('ya_pokupki_carrier');
		foreach ($data['extensions'] as $row)
		{
			$html .= '<div class="form-group">
							<label class="col-sm-4 control-label" for="ya_pokupki_carrier">'.$row['name'].'</label>
							<div class="col-sm-8">
								<select name="ya_pokupki_carrier['.$row['ext'].']" id="ya_pokupki carrier" class="form-control">';
                            foreach ($types as $t => $t_name)
                                $html .= '<option value="'.$t.'" '.((isset($save_data[$row['ext']]) && $save_data[$row['ext']] == $t) ? 'selected="selected"' : '').'>'.$t_name.'</option>';
							$html .= '</select>
							</div>
						</div>';
		}
		
		return $html;
	}
	private function permitMws(){
		$ext = array("openssl");
		$out = array();
		foreach ($ext as $name){
			if (!extension_loaded($name)) {
				 $out[]=$name;
			}
		}
	}

    private function tabMws(&$data)
    {
        $data['mws_starter'] = $this->language->get('mws_starter');
        $data['mws_php_exten'] = $this->permitMws();
        $data['mws_starter'] = $this->language->get('mws_starter');
        $data['mws_starter'] = $this->language->get('mws_starter');
        $oldIp = $this->config->get('ya_mws_server_ip');
        $newIp = $this->getServerIp();
        if (empty($oldIp)) {
            $oldIp = $newIp;
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('ya_mws', array('ya_mws_server_ip' => $newIp));
        }
        $data['mws_ip_same'] = ($oldIp === $newIp);
        if (!$data['mws_ip_same']) {
            $this->load->model('setting/setting');
            if (!isset($this->session->data['ya_mws_ip_counter'])) {
                $this->session->data['ya_mws_ip_counter'] = 1;
            } else {
                $this->session->data['ya_mws_ip_counter'] += 1;
                if ($this->session->data['ya_mws_ip_counter'] > 2) {
                    $this->model_setting_setting->editSetting('ya_mws', array('ya_mws_server_ip' => $newIp));
                }
            }
        } else {
            unset($this->session->data['ya_mws_ip_counter']);
        }
        $data['mws_ip_old'] = $oldIp;
        $data['mws_ip_new'] = $newIp;
    }

    private function getServerIp()
    {
        $url = 'http://ipv4.internet.yandex.net/internet/api/v0/ip';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 9);
        curl_setopt($ch, CURLOPT_POST, 0);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($status == 200) {
            $data = json_decode($result);
            if (is_string($data)) {
                return $data;
            }
        }
        return 'Не удалось определить IP адрес';
    }

	public function index()
	{
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$this->load->language($for23.'feed/yamodule');
		$this->load->model('setting/setting');
		$this->load->model('catalog/option');
		$this->load->model('localisation/order_status');
		$data['data_carrier'] = $this->carrierList();
		$data['kassa_status'] = '';
		$data['p2p_status'] = '';
		$data['metrika_status'] = '';
		$data['market_status'] = '';
		$data['pokupki_status'] = '';
		$data['fast_pay_status'] = '';
		$array_init = array_merge($this->fields_p2p, $this->fields_kassa, $this->fields_metrika, $this->fields_market, $this->fields_pokupki);
		if (($this->request->server['REQUEST_METHOD'] == 'POST'))
		{
			$this->processSave();
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link($for23.'feed/yamodule', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if(isset($this->request->get['err']))
			$data['err_token'] = $this->request->get['err'];
		else
			$data['err_token'] = '';

		// kassa
		$arLang = array(
			'kassa_ma','kassa_pb','kassa_qw','kassa_cr','kassa_wm','kassa_mobile','kassa_sber',
			'kassa_alfa','kassa_ym','kassa_method','kassa_cards','kassa_cash',
			'kassa_text_connect','kassa_text_enable','kassa_text_testmode', 'kassa_text_realmode','kassa_text_dynamic','kassa_text_help_url', 'kassa_text_help_cburl',
			'kassa_text_status','kassa_text_debug_help','kassa_text_debug_dis','kassa_text_debug_en','kassa_text_debug','kassa_text_adv_head',
			'kassa_text_paylogo_help','kassa_paylogo_text',
			'kassa_text_cart_reset','kassa_cart_reset_text',
			'kassa_text_create_order','kassa_create_order_text','kassa_text_sort_order',
			'kassa_text_pay_help','kassa_text_paymode_help','kassa_text_paymode_shop','kassa_text_paymode_kassa','kassa_text_paymode_label',
			'kassa_text_paymode_head','kassa_text_pw','kassa_text_scid','kassa_text_sid','kassa_text_get_setting','kassa_text_lk_head','kassa_sv',
			'kassa_text_inv', 'kassa_text_invhelp', 'kassa_text_inv_subj','kassa_text_inv_subjhelp','kassa_text_inv_logo','kassa_text_inv_logohelp',
			'kassa_text_inv_text', 'kassa_text_inv_texthelp','kassa_text_inv_pattern',
			'p2p_text_connect','p2p_text_enable','p2p_text_url_help','p2p_text_setting_head','p2p_text_account','p2p_text_appId','p2p_text_appWord','p2p_text_app_help',
			'p2p_text_extra_head','p2p_text_debug',	'p2p_text_off',	'p2p_text_on','p2p_text_debug_help','p2p_text_status',

            'fast_pay_text','fast_pay_os_text', 'fast_pay_desc_text', 'fast_pay_enable_label', 'fast_pay_id_label', 'fast_pay_purpose_label', 'fast_pay_os_label', 'fast_pay_os_text', 'fast_pay_desc_text', 'fast_pay_title',

			'metrika_gtoken','metrika_number','metrika_idapp','metrika_o2auth','metrika_pw','metrika_uname','metrika_upw','metrika_set','metrika_celi','metrika_callback',
			'metrika_sv','metrika_set_1','metrika_set_2','metrika_set_3','metrika_set_4','metrika_set_5','celi_cart','celi_order',
			'pokupki_gtoken','pokupki_stoken','pokupki_yapi','pokupki_number','pokupki_login','pokupki_pw','pokupki_idapp','pokupki_token',
			'pokupki_idpickup','pokupki_method','pokupki_sapi','pokupki_set_1','pokupki_set_2','pokupki_set_3','pokupki_set_4','pokupki_sv','pokupki_upw',
			'pokupki_callback','market_color_option','market_size_option','market_size_unit','text_select_all','text_unselect_all','text_no','market_set',
			'market_set_1','market_set_2','market_set_3','market_set_4','market_set_5','market_set_6','market_set_7','market_set_8','market_set_9','market_lnk_yml',
			'market_cat','market_out','market_out_sel','market_out_all','market_dostup','market_dostup_1','market_dostup_2','market_dostup_3','market_dostup_4',
            'market_s_name','market_d_cost','market_d_days','market_sv_all','market_rv_all','market_ch_all','market_unch_all','market_prostoy','market_sv','market_gen','p2p_os',
			'p2p_sv','p2p_number','p2p_idapp','p2p_pw','p2p_linkapp','lbl_mws_main','txt_mws_main','lbl_mws_alert','lbl_mws_cn','lbl_mws_orgname','lbl_mws_email',
			'lbl_mws_connect','lbl_mws_crt','lbl_mws_doc','txt_mws_doc','txt_mws_cer','tab_mws_before','tab_row_sign','tab_row_cause','tab_row_primary','btn_mws_gen',
			'btn_mws_csr','btn_mws_doc','btn_mws_crt','btn_mws_crt_load','ya_version','text_license','market','kassa','metrika','pokupki','p2p','active','active_on',
			'active_off','log','button_cancel','text_installed','button_save','button_cancel','pokupki_text_status'
		);
		foreach ($arLang as $lang_name) $data[$lang_name] = $this->language->get($lang_name);
		$data['mod_off'] = sprintf($this->language->get('mod_off'), $this->url->link('extension/payment', 'token=' . $this->session->data['token'] . '&extension=yamodule', true));

		foreach (array('pickup','cancelled','delivery','processing','unpaid','delivered') as $val) $data['pokupki_text_status_'.$val] = $this->language->get('pokupki_text_status_'.$val);
        $data['ya_market_stock_days']= $this->Sget('ya_market_stock_days');
        $data['ya_market_stock_cost']= $this->Sget('ya_market_stock_cost');
        $data['yamodule_total_sort_order']= $this->Sget('yamodule_total_sort_order');
        $data['ya_kassa_send_check'] =  $this->Sget('ya_kassa_send_check');
        $data['ya_kassa_sid'] =  $this->Sget('ya_kassa_sid');

		$data['txt_mws_connect'] = sprintf($this->language->get('txt_mws_connect'),$this->url->link('tool/mws/csr', 'token=' . $this->session->data['token'], 'SSL'));
		$data['success_mws_alert'] = sprintf($this->language->get('success_mws_alert'), $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'), $this->url->link('extension/feed/yamodule', 'token=' . $this->session->data['token'], 'SSL'));
		
		$data['mws_orgname'] = HTTP_CATALOG;
		$data['mws_cn'] = '/business/oc2/yacms-'.$this->Sget('ya_kassa_sid');
		$data['mws_email'] = $this->Sget('config_email');

        $this->load->model('localisation/stock_status');
        $stock_results = $this->model_localisation_stock_status->getStockStatuses();
        foreach ($stock_results as $result) {
            $data['stockstatuses'][] = array(
                'id' => $result['stock_status_id'],
                'name' => $result['name']
            );
        }
		//
		$data['token'] = $this->session->data['token'];
		$data['url_mws_gen'] = $this->url->link('tool/mws/generate', 'token=' . $this->session->data['token'], 'SSL');

		/** Start FastPay template variables */

        $data['ya_fast_pay_active'] = $this->Sget("ya_fast_pay_active");
        $data['ya_fast_pay_id'] = $this->Sget("ya_fast_pay_id");
        $data['ya_fast_pay_description'] = $this->Sget("ya_fast_pay_description");
        $data['ya_fast_pay_os'] = $this->Sget("ya_fast_pay_os");

		/** End FastPay template variables */

//		taxes

        //if ($this->config->get('ya_kassa_send_check')) {
            $this->load->model('localisation/tax_class');
            $this->load->model('localisation/tax_rate');
            $data['kassa_taxes'] = '<table class="table table-hover">
                            	<tbody>';

            $rates = $this->model_localisation_tax_rate->getTaxRates();

            foreach ($rates as $rate) {
                $rate_id = $rate['tax_rate_id'];
                $ya_kassa_tax = $this->config->get('ya_kassa_tax');
                $conf_rate = (isset($ya_kassa_tax[$rate_id])) ? $ya_kassa_tax[$rate_id] : 1;
                $data['kassa_taxes'] .= '
                            		<tr>
                            			<td>'.$rate['name'].'</td>
                            			<td>передавать в Яндекс.Кассу как</td>
                            			<td>
                            			    <select name="ya_kassa_tax['.$rate_id.']" id="ya_kassa_tax_'.$rate_id.'" class="form-control">
                                                            <option '.($conf_rate == 1 ? 'selected="selected"' : '').' value="1">Без НДС</option>
                                                            <option '.($conf_rate == 2 ? 'selected="selected"' : '').' value="2">0%</option>
                                                            <option '.($conf_rate == 3 ? 'selected="selected"' : '').' value="3">10%</option>
                                                            <option '.($conf_rate == 4 ? 'selected="selected"' : '').' value="4">18%</option>
                                                            <option '.($conf_rate == 5 ? 'selected="selected"' : '').' value="5">Расчётная ставка 10/110</option>
                                                            <option '.($conf_rate == 6 ? 'selected="selected"' : '').' value="6">Расчётная ставка 18/118</option>
											</select>
                            			</td>
                            		</tr>
                            	';
            }
        $data['kassa_taxes'].="</tbody></table>";
        //} else {
        //    $data['kassa_taxes'] = false;
        //}

//		taxes

        $data['mws_global_error'] = array();
		$conf = $this->model_setting_setting->getSetting("yamodule_mws");
		if (isset($conf['yamodule_mws_csr_sign'])) {
			$data['mws_sign'] = $conf['yamodule_mws_csr_sign'];
		} else {
            //MWS
            if (!extension_loaded('openssl')) {
                $data['mws_global_error'][] = $this->language->get('ext_mws_openssl');
            }
            if (!$this->Sget('ya_kassa_active')) {
                $data['mws_global_error'][] = $this->language->get('err_mws_kassa');
            }
            if (!$this->Sget('ya_kassa_sid')) {
                $data['mws_global_error'][] = $this->language->get('err_mws_shopid');
            }
            if (count($data['mws_global_error']) == 0) {
                $this->load->controller('tool/mws/generate');
            }
			//Генерация CSR
			//$this->load->controller('tool/mws/generate');
			//$this->response->redirect($this->url->link('extension/feed/yamodule', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$data['cert_loaded'] = (isset($conf['yamodule_mws_cert']) && $conf['yamodule_mws_cert']!="")?true:false;
		//
		$results = $this->model_catalog_option->getOptions(array('sort' => 'name'));
		$data['options'] = $results;
		$data['tab_general'] = $this->language->get('tab_general');
		$data['ya_market_size_options'] = array();
		$data['ya_market_color_options'] = array();
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['heading_title'] = $this->language->get('heading_title_ya').' ('.$this->language->get('ya_version').')';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link($for23.'feed/yamodule', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title_ya'),
			'separator' => ' :: '
		);
		$this->tabMws($data);
		$data['action'] = $this->url->link($for23.'feed/yamodule', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link($for23.'feed', 'token=' . $this->session->data['token'], 'SSL');
		$this->load->model('localisation/stock_status');
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(0);
		$this->document->setTitle($this->language->get('heading_title_ya'));
		if (isset($this->request->post['ya_market_categories'])) {
			$data['ya_market_categories'] = implode(',', $this->request->post['ya_market_categories']);
		} elseif ($this->config->get('ya_market_categories') != '') {
			$data['ya_market_categories'] = $this->config->get('ya_market_categories');
		} else {
			$data['ya_market_categories'] = '';
		}

		$this->load->model('localisation/currency');
		$currencies = $this->model_localisation_currency->getCurrencies();
		$allowed_currencies = array_flip(array('RUR', 'RUB', 'BYN', 'KZT', 'UAH'));
		$data['currencies'] = array_intersect_key($currencies, $allowed_currencies);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data = array_merge($data, $this->initForm($array_init));
		$data = array_merge($data, $this->initErrors());
		$data['market_cat_tree'] = $this->treeCat(0);
		if (!isset($data['ya_market_size_options']))
			$data['ya_market_size_options'] = array();
		if (!isset($data['ya_market_color_options']))
			$data['ya_market_color_options'] = array();
		if (isset($this->session->data['metrika_status']) && !empty($this->session->data['metrika_status']))
			$data['metrika_status'] = array_merge($data['metrika_status'], $this->session->data['metrika_status']);
		if (isset($this->session->data['market_status']) && !empty($this->session->data['market_status']))
			$data['market_status'] = array_merge($data['market_status'], $this->session->data['market_status']);
		if (isset($this->session->data['kassa_status']) && !empty($this->session->data['kassa_status']))
			$data['kassa_status'] = array_merge($data['kassa_status'], $this->session->data['kassa_status']);

        if (isset($this->session->data['fast_pay_status']) && !empty($this->session->data['fast_pay_status'])) {
            if( is_array($data['fast_pay_status'])) {
                $data['fast_pay_status'] = array_merge($data['fast_pay_status'], $this->session->data['fast_pay_status']);
            } else {
                $data['fast_pay_status'] = $this->session->data['fast_pay_status'];
            }
        }

        if ($data['ya_kassa_show_in_footer'] === null || $data['ya_kassa_show_in_footer'] === '') {
            $data['ya_kassa_show_in_footer'] = true;
        }


		if (isset($this->session->data['p2p_status']) && !empty($this->session->data['p2p_status']))
			$data['p2p_status'] = array_merge($data['p2p_status'], $this->session->data['p2p_status']);
		if (isset($this->session->data['pokupki_status']) && !empty($this->session->data['pokupki_status']))
			$data['pokupki_status'] = array_merge($data['pokupki_status'], $this->session->data['pokupki_status']);
		$end_tpl = (version_compare(VERSION, "2.2.0", '>='))?"":".tpl";
		$this->response->setOutput($this->load->view($for23.'feed/yamodule'.$end_tpl, $data));
	}

	public function errors_alert($text)
	{
		$html = '<div class="alert alert-danger">
						<i class="fa fa-exclamation-circle"></i> '.$text.'
							<button type="button" class="close" data-dismiss="alert">×</button>
					</div>';
		return $html;
	}

	public function success_alert($text, $class = 'success')
	{
		$html = ' <div class="alert alert-'.$class.'">
					<i class="fa fa-check-circle"></i> '.$text.'
						<button type="button" class="close" data-dismiss="alert">×</button>
					</div>';
		return $html;
	}

	public function install()
	{
		$fields = array(
			'ya_p2p_active' => 0,
			'ya_p2p_test' => 0,
			'ya_p2p_log' => 0,
            'yamodule_total_sort_order' => '0',
            'ya_kassa_active' => 1,
			'ya_kassa_log' => 0,
			'ya_kassa_paymode' => 1,
			'ya_kassa_paylogo' => 1,
			'ya_kassa_send_check' => 0,
			'ya_kassa_test' => 0,
			'ya_kassa_cart_reset' => 0,
			'ya_kassa_create_order' => 1,
			'ya_kassa_inv_logo' => 0,
			'ya_kassa_inv' => 0,
			'ya_kassa_tax_default' => 1,
			'ya_metrika_active' => 0,
			'ya_metrika_webvizor' => 1,
			'ya_metrika_otkaz' => 1,
			'ya_metrika_clickmap' => 1,
			'ya_metrika_out' => 1,
			'ya_metrika_hash' => 1,
			'ya_metrika_cart' => 1,
			'ya_metrika_order' => 1,
			'ya_market_color_options' => array(),
			'ya_market_size_options' => array(),
			'ya_market_active' => 0,
			'ya_market_prostoy' => 0,
			'ya_market_set_available' => 2,
			'ya_market_available' => 1,
			//'ya_market_homecarrier' => 1,
			'ya_market_combination' => 1,
			'ya_market_features' => 1,
			'ya_market_dimensions' => 1,
			'ya_market_allcurrencies' => 1,
			'ya_market_store' => 1,
			'ya_market_delivery' => 1,
			'ya_market_pickup' => 1,
			'ya_pokupki_yandex' => 1,
			'ya_pokupki_sprepaid' => 0,
			'ya_pokupki_cash' => 1,
			'ya_pokupki_bank' => 1,
			'ya_pokupki_status_pickup' => 16, //
			'ya_pokupki_status_cancelled' => 7, //
			'ya_pokupki_status_delivery' => 3, //
			'ya_pokupki_status_processing' => 2,
			'ya_pokupki_status_unpaid' => 3,
			'ya_pokupki_status_delivered' => 15 //
		);

		$q = $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."pokupki_orders` (
		  `id_order` int(10) NOT NULL,
		  `id_market_order` varchar(100) NOT NULL,
		  `currency` varchar(100) NOT NULL,
		  `ptype` varchar(100) NOT NULL,
		  `home` varchar(100) NOT NULL,
		  `pmethod` varchar(100) NOT NULL,
		  `outlet` varchar(100) NOT NULL,
		  PRIMARY KEY (`id_order`,`id_market_order`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $q = $this->db->query('CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'mws_return_product`
            (
                `id_order` int(10) NOT NULL,
                `order_product_id` int(10) NOT NULL,
                `quantity` int(10) NOT NULL,
                PRIMARY KEY  (`id_order`,`order_product_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');

		$q = $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."pokupki_orders` (
		  `id_order` int(10) NOT NULL,
		  `id_market_order` varchar(100) NOT NULL,
		  `currency` varchar(100) NOT NULL,
		  `ptype` varchar(100) NOT NULL,
		  `home` varchar(100) NOT NULL,
		  `pmethod` varchar(100) NOT NULL,
		  `outlet` varchar(100) NOT NULL,
		  PRIMARY KEY (`id_order`,`id_market_order`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

		$q = $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."mws_return` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `invoice_id` text NOT NULL,
		  `amount` varchar(10) NOT NULL,
		  `status` int(11) NOT NULL,
		  `techMessage` text NOT NULL,
		  `error` int(11) NOT NULL,
		  `cause` text NOT NULL,
		  `clientOrderId` text NOT NULL,
		  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `response` text NOT NULL,
		  `request` text NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

		$this->load->model('setting/setting');
		foreach ($fields as $k => $field)
			$this->model_setting_setting->editSetting($k, array($k => $field));
		$user = array();
		$user['firstname'] = 'ya-name';
		$user['lastname'] = 'ya-lastname';
		$user['address'][0]['firstname'] = 'ya-name';
		$user['address'][0]['lastname'] = 'ya-lastname';
		$user['email'] = 'test@2.ru';
		$user['telephone'] = 999999;
		$user['address'][0]['telephone'] = 999999;
		$user['address'][0]['address_1'] = 'Address';
		$user['address'][0]['postcode'] = 000000;
		$user['address'][0]['city'] = 'ya-Город';
		$user['address'][0]['country_id'] = '';
		$user['address'][0]['custom_field'] = '';
		$user['newsletter'] = '';
		$user['customer_group_id'] = 1;
		$user['custom_field'] = '';
		$user['safe'] = '';
		$user['fax'] = '';
		$user['address'][0]['fax'] = '';
		$user['address'][0]['company'] = '';
		$user['address'][0]['address_2'] = '';
		$user['address'][0]['zone_id'] = '';
		$user['status'] = true;
		$user['password'] = rand(100000, 500000);
		$customer_id = $this->addCustomer($user);
		$this->load->model('extension/extension');
		$this->model_setting_setting->editSetting('yandexbuy_customer', array('yandexbuy_customer' => $customer_id));
		$this->model_setting_setting->editSetting('yamodule_status', array('yamodule_status' => 1));
		$this->model_extension_extension->install('payment', 'yamodule');
		$this->model_extension_extension->getInstalled('payment');
		//$this->load->controller('extension/modification/refresh');
		$this->load->model('user/user_group');
		$this->installFastPaySettings();

        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $for23.'payment/yamodule');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $for23.'payment/yamodule');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $for23.'feed/yamodule');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $for23.'feed/yamodule');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'tool/mws');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'tool/mws');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $for23.'payment/test');
	}

	public function changestatus(){
		$json = array();
		$order_id = (int)$this->request->get['order_id'];
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";

		$this->load->model('sale/order');
		$this->load->model('setting/setting');
		$order_info = $this->model_sale_order->getOrder($order_id);
		//
		if ($order_info['customer_id'] == $this->config->get('yandexbuy_customer')){
			$order_status_id = $this->request->post['order_status_id'];
			$comment = $this->request->post['comment'];
			//$notify = $this->request->post['notify'];
			//$append = $this->request->post['append'];
			//$override = (bool) $this->request->post['override'];
			$this->load->model($for23.'yamodule/pokupki');
			$json = $this->{'model_'.str_replace("/","_",$for23).'yamodule_pokupki'}->sendOrder($order_id, $order_status_id, $comment);
		}
		//
		$this->response->addHeader('Content-Type: application/json; charset=utf-8');
		$this->response->setOutput(json_encode($json));
	}

	public function addCustomer($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(serialize($data['custom_field'])) . "', newsletter = '" . (int)$data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', safe = '" . (int)$data['safe'] . "', date_added = NOW()");
		$customer_id = $this->db->getLastId();
		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(serialize($address['custom_field'])) . "'");
				$address_id = $this->db->getLastId();
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
			}
		}
		return $customer_id;
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		return $query->row;
	}

	public function uninstall()
	{
		$this->load->model('setting/setting');
		$cu = $this->getCustomer($this->config->get('yandexbuy_customer'));
		$this->model_setting_setting->editSetting('yamodule_status', array('yamodule_status' => 0));
		$this->load->model('extension/extension');
        $this->model_setting_setting->deleteSetting('ya_fast_pay_active');
        $this->model_setting_setting->deleteSetting('ya_fast_pay_os');
        $this->model_setting_setting->deleteSetting('ya_fast_pay_id');
        $this->model_setting_setting->deleteSetting('ya_fast_pay_description');
		$this->model_extension_extension->uninstall('payment', 'yamodule');
		if ($cu['customer_id'] && $cu['address_id'])
		{
			$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = ".$cu['customer_id']);
			$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = ".$cu['address_id']);
		}
        $arrFiles = array(
            "admin" => array(
                "controller/feed/yamodule.php",
                "controller/payment/yamodule.php",
                "controller/tool/mws.php",
                "model/yamodule/metrika.php",
                "model/yamodule/pokupki.php",
                "model/yamodule/return.php",

                "controller/extension/feed/yamodule.php",
                "controller/extension/payment/yamodule.php",
                "controller/extension/tool/mws.php",
                "model/extension/yamodule/metrika.php",
                "model/extension/yamodule/pokupki.php",
                "model/extension/yamodule/return.php"
            ),
            "catalog" => array(
                "controller/feed/yamarket.php",
                "controller/payment/yamodule.php",
                "controller/yandexbuy/cart.php",
                "controller/yandexbuy/order.php",
                "model/payment/yamodule.php",
                "model/yamodel/pokupki.php",
                "model/yamodel/yamarket.php",
                "model/yamodel/yamoney.php",

                "controller/extension/feed/yamarket.php",
                "controller/extension/payment/yamodule.php",
                "controller/yandexbuy/cart.php",
                "controller/yandexbuy/order.php",
                "model/extension/payment/yamodule.php",
                "model/extension/yamodel/pokupki.php",
                "model/extension/yamodel/yamarket.php",
                "model/extension/yamodel/yamoney.php"
            )
        );
        $error_log = new Log('error.log');
        foreach ($arrFiles as $folder => $files){
            foreach ($files as $file) {
                if ($folder == "admin") {
                    if (file_exists(DIR_APPLICATION . $file)) {
                        if (!unlink (DIR_APPLICATION . $file)) $error_log->write(DIR_APPLICATION . $file);
                    }
                } else {
                    if (file_exists(DIR_CATALOG . $file)) {
                        if (!unlink (DIR_CATALOG . $file)) $error_log->write(DIR_CATALOG . $file);
                    }
                }
            }
        }
        $error_log = null;
	}

    private function installFastPaySettings()
    {
        $defaultStatusId = $this->config->get('config_order_status_id');
        $this->model_setting_setting->editSetting('ya_fast_pay_active', array('ya_fast_pay_active' =>  0));
        $this->model_setting_setting->editSetting('ya_fast_pay_os', array('ya_fast_pay_os' => $defaultStatusId));
        $this->model_setting_setting->editSetting('ya_fast_pay_id', array('ya_fast_pay_id' => ''));
        $this->model_setting_setting->editSetting('ya_fast_pay_description', array('ya_fast_pay_description' => 'Номер заказа %order_id%. Оплата через Яндекс.Платежку'));
        $this->model_setting_setting->editSetting('ya_fast_pay_url', array('ya_fast_pay_url' => 'https://money.yandex.ru/fastpay/confirm'));
    }
}

class ControllerExtensionFeedYamodule extends ControllerFeedYamodule{}
