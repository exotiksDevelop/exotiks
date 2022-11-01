<?php
//microdatapro 7.3

require_once(DIR_SYSTEM . 'library/microdatapro.php');

class ControllerModuleMicrodataPro extends Controller {

	private $path = 'module/microdatapro'; //extension/module/microdatapro =>2.3
	private $module = 'extension/module'; //extension/extension =>2.3

	public function __construct($registry) {
		parent::__construct($registry);
		$this->microdatapro = new Microdatapro($this->registry);
	}

	public function install() {
		$response = $this->send();
		if($response['status'] && $response['content']){
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSettingValue('microdatapro', "microdatapro_license_key", $response['content']);
		}
 		$this->response->redirect($this->url->link($this->path, 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function index() {$a = 0;

		$this->load->model('setting/setting');
		$this->load->model('setting/store');

		$this->transfer(); //transfer data to new version

		$response = $this->send();
		if($response['status'] && $response['content'] && $this->microdatapro->key($response['content'],1)){
			$a = 1;
			$this->model_setting_setting->editSettingValue('microdatapro', "microdatapro_license_key", $response['content']);
		}

		$data = $this->language->load($this->path);
		$data['href_old'] = $this->path;

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_setting_setting->editSetting('microdatapro', $this->request->post);
			if(isset($this->request->get['success'])){ //7.3
				$this->response->redirect($this->url->link($this->path . "&success=1", 'token=' . $this->session->data['token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link($this->path, 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		//7.3
		$data['success'] = false;
		if(isset($this->request->get['success'])){
			$data['success'] = true;
		}

		$heading_title_array = explode(" [", $this->language->get('heading_title'));
		$data['token'] = $this->session->data['token'];
		$data['heading_title'] = $heading_title_array[0] . ' ' . $this->microdatapro->module_info('version');
		$data['action'] = $this->url->link($this->path . '&success=1', 'token=' . $this->session->data['token'], 'SSL'); //7.3
		$data['cancel'] = $this->url->link($this->module, 'token=' . $this->session->data['token'], 'SSL');
		$data['site_url'] = str_replace(array("https://", "http://", "/"), "", HTTP_CATALOG);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link($this->module, 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => "MicrodataPRO " . $this->microdatapro->module_info('version'),
			'href' => $this->url->link($this->path, 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['mirodatapro_version'] = $this->microdatapro->module_info('version'); //7.3

		$data['version2'] = false;
		if($this->microdatapro->opencart_version(0) == 2){
			$data['version2'] = true;
		}

		$vars = array(
			'microdatapro_license_key',
			'microdatapro_status',
			'microdatapro_opengraph',
			'microdatapro_opengraph_meta',
			'microdatapro_twitter_account',
			'microdatapro_company',
			'microdatapro_company_type',
			'microdatapro_store_type',
			'microdatapro_hcard',
			'microdatapro_company_syntax',
			'microdatapro_email',
			'microdatapro_oh_1',
			'microdatapro_oh_2',
			'microdatapro_oh_3',
			'microdatapro_oh_4',
			'microdatapro_oh_5',
			'microdatapro_oh_6',
			'microdatapro_oh_7',
			'microdatapro_phones',
			'microdatapro_groups',
			'microdatapro_locations',
			'microdatapro_map',
			'microdatapro_product',
			'microdatapro_product_syntax',
			'microdatapro_product_breadcrumb',
			'microdatapro_product_gallery',
			'microdatapro_hide_price',
			'microdatapro_sku',
			'microdatapro_upc',
			'microdatapro_ean',
			'microdatapro_mpn',
			'microdatapro_isbn',
			'microdatapro_product_reviews',
			'microdatapro_product_related',
			'microdatapro_product_attribute',
			'microdatapro_product_in_stock',
			'microdatapro_in_stock_status_id',
			'microdatapro_category',
			'microdatapro_category_syntax',
			'microdatapro_category_range',
			'microdatapro_category_review',
			'microdatapro_category_gallery',
			'microdatapro_manufacturer',
			'microdatapro_manufacturer_syntax',
			'microdatapro_information',
			'microdatapro_information_syntax',
			'microdatapro_age_group',
			'microdatapro_target_gender',
			'microdatapro_profile_id',
			'microdatapro_attr_color',
			'microdatapro_attr_material',
			'microdatapro_attr_size'
		);

		//add multistore vars
		$store_results = $this->model_setting_store->getStores();
		foreach ($store_results as $result) {
			$vars[] = 'microdatapro_phones'.$result['store_id'];
			$vars[] = 'microdatapro_groups'.$result['store_id'];
			$vars[] = 'microdatapro_locations'.$result['store_id'];
			$vars[] = 'microdatapro_map'.$result['store_id'];
		}

 		foreach($vars as $var){
			if (isset($this->request->post[$var])) {
				$data[$var] = $this->request->post[$var];
			} else {
				$data[$var] = $this->config->get($var);
			}
		}

		$data['email'] 			= $this->config->get('config_email');
		$data['store_name'] = $this->config->get('config_name');
		$data['stores'] = array();
		foreach ($store_results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'microdatapro_phones' => $data['microdatapro_phones'.$result['store_id']],
				'microdatapro_groups' => $data['microdatapro_groups'.$result['store_id']],
				'microdatapro_locations' => $data['microdatapro_locations'.$result['store_id']],
				'microdatapro_map' => $data['microdatapro_map'.$result['store_id']]
			);
		}

		$this->load->model('localisation/stock_status');
		$data['stock_statuses'] =  $this->model_localisation_stock_status->getStockStatuses();
		$data['stock_status_id'] = $this->config->get('microdatapro_in_stock_status_id');

		$data['old_microdata'] = $this->find_old();

		//7.3

		$this->load->model('catalog/attribute');
		$data['all_attributes'] = $this->model_catalog_attribute->getAttributes(array('start'=>0,'limit'=>9999,'sort'=>'ad.name','order'=>'ASC'));

		$data['lhref'] = "https://exotiks.ru/status/?module=microdatapro&domain=" . $this->microdatapro->module_info('main_host', 1);
		$data['old_count'] = count($data['old_microdata']);
		$data['mod_files'] = $this->mod_files();
		$data['mod_errors'] = $this->mod_files(1);
		$data['other_modules'] = $this->find_other();

		$data['count_errors'] = 0;
		if($data['old_microdata']) $data['count_errors']++;
		if($data['mod_errors']) $data['count_errors']++;
		if($data['other_modules']) $data['count_errors']++;

		$data['link_main'] = HTTPS_CATALOG;
		$data['link_category'] = false;
		$category_query = $this->db->query("SELECT category_id FROM " . DB_PREFIX ."category WHERE status = 1 LIMIT 0,1");
		if($category_query->num_rows){
			$data['link_category'] = HTTPS_CATALOG . 'index.php%3Froute=product/category%26path%3D' . $category_query->row['category_id'];
		}
		$data['link_product'] = false;
		$product_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX ."product WHERE status = 1 LIMIT 0,1");
		if($product_query->num_rows){
			$data['link_product'] = HTTPS_CATALOG . 'index.php%3Froute=product/product%26product_id%3D' . $product_query->row['product_id'];
		}
		$data['link_manufacturer'] = false;
		$manufacturer_query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX ."manufacturer ORDER BY manufacturer_id DESC LIMIT 0,1");
		if($manufacturer_query->num_rows){
			$data['link_manufacturer'] = HTTPS_CATALOG . 'index.php%3Froute=product/manufacturer/info%26manufacturer_id%3D' . $manufacturer_query->row['manufacturer_id'];
		}
		$data['link_information'] = false;
		$information_query = $this->db->query("SELECT information_id FROM " . DB_PREFIX ."information WHERE status = 1 LIMIT 0,1");
		if($information_query->num_rows){
			$data['link_information'] = HTTPS_CATALOG . 'index.php%3Froute=information/information%26information_id%3D' . $information_query->row['information_id'];
		}
		//7.3

		if($response['status'] && $response['content'] && empty($data['microdatapro_license_key'])){
			$this->model_setting_setting->editSettingValue('microdatapro', "microdatapro_license_key", $response['content']);
			$data['microdatapro_license_key'] = $response['content'];
		}
		$data['microdatapro_license_key'] = $a?$data['microdatapro_license_key']:false;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->path . '.tpl', $data));
	}

	//7.3
	public function theme_dir() {
		if($this->microdatapro->opencart_version(1) >= 2 && $this->microdatapro->opencart_version(0) == 2){ //IF OC > 2.2
			$theme_dir = $this->config->get('config_theme');
			if($this->config->get('theme_default_directory') && $this->microdatapro->opencart_version(1) != 3){
				$theme_dir = $this->config->get('theme_default_directory');
			}
		}else{
			$theme_dir = $this->config->get('config_template');
		}
		return $theme_dir;
	}

	public function find_other() {
		$old_microdata = 0;
		foreach($this->microdatapro->getModFiles() as $file => $strings){
			$old_microdata += $this->file_scan($old_microdata, $file);
		}
		foreach($this->microdatapro->getMoreFiles() as $file => $strings){
			$old_microdata += $this->file_scan($old_microdata, $file);
		}

		return $old_microdata;
	}

	public function file_scan($old_microdata, $file) {

		$file_full = DIR_MODIFICATION . str_replace("{theme}", $this->theme_dir(), $file);
		$file_content = "";
		if(is_file($file_full)){
		  $file_content = @file_get_contents($file_full);
		}

		foreach($this->microdatapro->find_old() as $tag){
			$variants = array(
				$tag,
				str_replace("http", "https", $tag),
				str_replace("=", " = ", $tag),
				str_replace('"', "'", $tag),
				str_replace('"', "'", str_replace("http", "https", $tag)),
			);
			foreach($variants as $variant){
				if (stripos($file_content, $variant)){
					$old_microdata++;
				}
			}
		}

		return $old_microdata;
	}

	public function mod_files($key = 0) {
		$mod_files = array();

		$all_modified_files = $this->microdatapro->getModFiles();
		$mod_errors = count($all_modified_files)*2;

		foreach($all_modified_files as $file => $strings){
			$file = str_replace("{theme}", $this->theme_dir(), $file);

			foreach($strings as $string){
				$string = str_replace("&&&", "$", $string);

				$file_full = str_replace("system/", "", DIR_SYSTEM) . str_replace("{theme}", $this->theme_dir(), $file);
				$file_ocmod = DIR_MODIFICATION . str_replace("{theme}", $this->theme_dir(), $file);

				//fix
				if(!is_file($file_full)){
					$file_full = str_replace($this->theme_dir(), 'default', $file_full);
				}
				if(!is_file($file_ocmod)){
					$file_ocmod = str_replace($this->theme_dir(), 'default', $file_ocmod);
				}
				//fix

				if (strpos(file_get_contents($file_full), $string)){ //если есть строка для привязки
					$mod_errors--;
					$mod_files[$file] = array(
						'string' => $string,
						'status' => true,
					);
					$file_ocmod_content = @file_get_contents($file_ocmod);
					if (strpos($file_ocmod_content, $string) && strpos($file_ocmod_content, "//microdatapro")){ //если есть строка и модуль в  модификаторах
						$mod_errors--;
						$mod_files[$file]['ocmod'] = true;
					}else{
						$mod_files[$file]['ocmod'] = false;
					}
					break;
				}else{
					$mod_files[$file] = array(
						'string' => str_replace("&&&", "$", $strings),
						'status' => false,
						'ocmod'  => false
					);
				}
			}

		}

		if($key == 0){
			return $mod_files;
		}
		if($key == 1){
			return $mod_errors;
		}

	}

	public function find_old($original = false) {
		$old_microdata = array();
		$all_variants = array();

		foreach($this->microdatapro->getModFiles() as $file => $string){
			$file_full = str_replace("system/", "", DIR_SYSTEM) . str_replace("{theme}", $this->theme_dir(), $file);
			$file_content = "";
			if(is_file($file_full)){
			  $file_content = @file_get_contents($file_full);
			}
			$file = str_replace("{theme}", $this->theme_dir(), $file);

			foreach($this->microdatapro->find_old() as $tag){
				$variants = array(
					$tag,
					str_replace("http", "https", $tag),
					str_replace("=", " = ", $tag),
					str_replace('"', "'", $tag),
					str_replace('"', "'", str_replace("http", "https", $tag)),
				);
				foreach($variants as $variant){
					$all_variants[] = $variant;
					if (stripos($file_content, $variant)){
						if($original){
							$file = $file_full;
						}
						$old_microdata[$file] = $file;
					}
				}
			}
		}

		foreach($this->microdatapro->getMoreFiles() as $file => $string){
			$file_full = str_replace("system/", "", DIR_SYSTEM) . str_replace("{theme}", $this->theme_dir(), $file);
			$file_content = "";
			if(is_file($file_full)){
			  $file_content = @file_get_contents($file_full);
			}
			$file = str_replace("{theme}", $this->theme_dir(), $file);

			foreach($this->microdatapro->find_old() as $tag){
				$variants = array(
					$tag,
					str_replace("http", "https", $tag),
					str_replace("=", " = ", $tag),
					str_replace('"', "'", $tag),
					str_replace('"', "'", str_replace("http", "https", $tag)),
				);
				foreach($variants as $variant){
					$all_variants[] = $variant;
					if (stripos($file_content, $variant)){
						if($original){
							$file = $file_full;
						}
						$old_microdata[$file] = $file;
					}
				}
			}
		}

		if(!$original){
			return $old_microdata;
		}else{
			return array($old_microdata, $all_variants);
		}
	}

	public function clear_old(){
		$find_files_data = $this->find_old(1);
		$find_files = $find_files_data[0];
		$find_tags = $find_files_data[1];
		if($find_files){
			$this->log->write("============================================================");
			$this->log->write("MicrodataPro " . $this->microdatapro->module_info('version') . " начало очистки шаблона от старых элементов разметки");
			foreach($find_files as $item){
				$file_html = file_get_contents($item);
				$file_html = preg_replace('/<meta property=(|"|\')og:(.*?)\/>/im', "", $file_html); //clear og:
				$file_data = str_ireplace($find_tags, "", $file_html);
				rename($item, $item."_mdb");
				$fp = fopen($item, "w");
				fwrite($fp, $file_data);
				fclose($fp);
				$this->log->write("microdatapro очищенный файл: " . $item);
				$this->log->write("microdatapro оригинальный файл: " . $item . "_mdb");
			}
			$this->log->write("MicrodataPro " . $this->microdatapro->module_info('version') . " завершение чистки шаблона, всего очищено (" . count($find_files) . ") файлов");
			$this->log->write("============================================================");
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(count($find_files)));
	}
	// 7.3

	public function send() {
	  $prepare_data = array('email' =>$this->config->get('config_email'),'module' =>  $this->microdatapro->module_info('module') . " " . $this->microdatapro->module_info('version'),'site' => $this->microdatapro->module_info('main_host', true),'sec_token' => "3274507573",'method' => 'POST','lang' => $this->config->get('config_language'),'engine' => $this->microdatapro->module_info('engine'),'date' => date("Y-m-d H:i:s"));
	  if($curl = curl_init()) { //POST CURL
	    curl_setopt($curl, CURLOPT_URL, "https://microdata.pro/index.php?route=sale/sale");
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $prepare_data);
	    $register_number = curl_exec($curl);
	    curl_close($curl);
	    $response['content'] = $register_number;
	    $response['status'] = true;
	    $pos = stripos($register_number, "error");
			if(!$register_number){
				$pos = true;
			}
	    if ($pos === false) {
	    }else{
	      $url = "https://exotiks.ru/index.php?route=sale/sale&sec_token=3274507573";
	      $url.= "&email=" . $this->config->get('config_email');
	      $url.= "&module=" . $this->microdatapro->module_info('module') . " " . $this->microdatapro->module_info('version');
	      $url.= "&site=" . $this->microdatapro->module_info('main_host', true);
	      $url.= "&method=GET&lang=" . $this->config->get('config_language');
	      $url.= "&engine=" . $this->microdatapro->module_info('engine');
	      $url.= "&date=" . date("Y-m-d H:i:s");
	      $url = str_replace(" ", "%20", $url);
	      $response['content'] = file_get_contents($url);
	      $response['status'] = true;
	    }
	  }
	  return $response;
	}

	public function transfer(){
		if(!$this->config->get('microdatapro_new_version')){
			$this->load->model('setting/setting');
			$this->load->model('setting/store');
			$all_vars = array( //new => old
				'microdatapro_license_key' => 'config_microdata_license_key',
				'microdatapro_status' => 'config_microdata_status',
				'microdatapro_opengraph' => 'config_microdata_opengraph',
				'microdatapro_opengraph_meta' => 'config_description_meta',
				'microdatapro_twitter_account' => 'config_microdata_twitter_account',
				'microdatapro_company' => 'config_company',
				'microdatapro_hcard' => 'config_hcard',
				'microdatapro_company_syntax' => 'config_company_syntax',
				'microdatapro_email' => 'config_microdata_email',
				'microdatapro_oh_1' => 'config_microdata_oh_1',
				'microdatapro_oh_2' => 'config_microdata_oh_2',
				'microdatapro_oh_3' => 'config_microdata_oh_3',
				'microdatapro_oh_4' => 'config_microdata_oh_4',
				'microdatapro_oh_5' => 'config_microdata_oh_5',
				'microdatapro_oh_6' => 'config_microdata_oh_6',
				'microdatapro_oh_7' => 'config_microdata_oh_7',
				'microdatapro_phones' => 'config_microdata_phones',
				'microdatapro_groups' => 'config_microdata_groups',
				'microdatapro_locations' => 'config_microdata_locations',
				'microdatapro_map' => 'config_microdata_map',
				'microdatapro_product' => 'config_product_page',
				'microdatapro_product_syntax' => 'config_product_syntax',
				'microdatapro_product_breadcrumb' => 'config_product_breadcrumb',
				'microdatapro_hide_price' => 'config_microdata_hide_price',
				'microdatapro_sku' => 'config_microdata_sku',
				'microdatapro_upc' => 'config_microdata_upc',
				'microdatapro_ean' => 'config_microdata_ean',
				'microdatapro_mpn' => 'config_microdata_mpn',
				'microdatapro_isbn' => 'config_microdata_isbn',
				'microdatapro_product_reviews' => 'config_product_reviews',
				'microdatapro_product_related' => 'config_product_related',
				'microdatapro_product_attribute' => 'config_product_attribute',
				'microdatapro_product_in_stock' => 'config_product_in_stock',
				'microdatapro_in_stock_status_id' => 'config_in_stock_status_id',
				'microdatapro_category' => 'config_category',
				'microdatapro_category_syntax' => 'config_category_syntax',
				'microdatapro_manufacturer' => 'config_manufacturer',
				'microdatapro_manufacturer_syntax' => 'config_manufacturer_syntax',
				'microdatapro_information' => 'config_information_page',
				'microdatapro_information_syntax' => 'config_information_syntax'
			);
			//add multistore vars
			$store_results = $this->model_setting_store->getStores();
			foreach ($store_results as $result) {
				$all_vars['microdatapro_phones'.$result['store_id']] = 'config_microdata_phones'.$result['store_id'];
				$all_vars['microdatapro_groups'.$result['store_id']] = 'config_microdata_groups'.$result['store_id'];
				$all_vars['microdatapro_locations'.$result['store_id']] = 'config_microdata_locations'.$result['store_id'];
				$all_vars['microdatapro_map'.$result['store_id']] = 'config_microdata_map'.$result['store_id'];
			}

			$key_value = array();
			foreach($all_vars as $new_variable => $old_variable){
				$key_value[$new_variable] = $this->config->get($old_variable);
			}
			$key_value['microdatapro_new_version'] = 1;
			$this->model_setting_setting->editSetting('microdatapro', $key_value);
			$this->response->redirect($this->url->link($this->path, 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
}