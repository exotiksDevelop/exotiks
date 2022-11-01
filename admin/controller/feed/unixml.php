<?php

class ControllerFeedUnixml extends Controller {

  private $error = array();
  private $ext = 'unixml';
  private $path = 'feed/unixml';
  private $module_path = 'extension/module';
  private $lang = 1;
  private $a = 0;
  private $message = '';
  private $insert_tables = array('attributes' => array('attribute_id','xml_name'),'replace_name' => array('name_from','name_to'),'additional_params' => array('param_name','param_text'),'category_match' => array('category_id','xml_name','markup','custom'),'markup' => array('name','products','markup'));

  public function feeds() {
    $feeds = array();
    $unixml_dat = explode(PHP_EOL, file_get_contents($this->request->server['DOCUMENT_ROOT'] . '/price/' . $this->ext . '.dat'));
    foreach(explode(',', $unixml_dat[0]) as $feed){
      if($feed){
        $feeds[] = trim($feed);
      }
    }
    return $feeds;
  }

  public function fields() { //for all feeds (function => field)
    $fields = array('getAttributeList'=>'attributes', 'getReplaceNameList'=>'replace_name', 'getAdditionalParamList'=>'additional_params', 'getReplaceCategory'=>'category_match' , 'getMarkup'=>'product_markup');
    $unixml_dat = explode(PHP_EOL, file_get_contents($this->request->server['DOCUMENT_ROOT'] . '/price/' . $this->ext . '.dat'));
    foreach(explode(',', $unixml_dat[1]) as $field){
      if($field){
        $fields[] = trim($field);
      }
    }

    return $fields;
  }

  public function install() {
    foreach($this->insert_tables as $insert_table => $insert_fields){
      $fields_db = "";
      foreach($insert_fields as $field_item){
        $field_len = '255';
        if($field_item == 'markup'){$field_len == '11';}
        if($field_item == 'custom'){$field_len == '2048';}
        $fields_db .= "`" . $this->db->escape($field_item) . "` varchar(" . $field_len . ") NOT NULL, ";
      }
      $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->ext . "_" . $insert_table . "` (`item_id` int(11) NOT NULL AUTO_INCREMENT,`feed` varchar(64) NOT NULL, " . $fields_db . "PRIMARY KEY (`item_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }
    $this->db->query("ALTER TABLE `" . DB_PREFIX . $this->ext . "_markup` CHANGE `products` `products` VARCHAR(20000)");
    $this->init_module();
  }

  private function table($table) {
    return DB_PREFIX . $this->ext . '_' . $table;
  }

  private function init_module() {
    $this->load->language($this->path);
		$domen = explode("//", HTTP_CATALOG);

    $curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, "https://exotiks.ru/index.php?route=sale/" . $this->ext);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, array('email'=>$this->config->get('config_email'),'module'=>$this->ext,'version'=>$this->language->get('version'),'site'=>str_replace("/", "", $domen[1]),'sec_token'=>"3274507573",'lang'=>$this->config->get('config_language'),'engine'=>VERSION,'date'=>date("Y-m-d H:i:s")));
    $a_number = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if(!$code){
      $domen = explode("//", HTTP_CATALOG);
      $pd = array('email'=>$this->config->get('config_email'),'module'=>$this->ext,'version'=>$this->language->get('version'),'site'=>str_replace("/", "", $domen[1]),'sec_token'=>"3274507573",'lang'=>$this->config->get('config_language'),'engine'=>VERSION,'date'=>date("Y-m-d H:i:s"));
      $urlp = "https://microdata.pro/index.php?route=sale/" . $this->ext;
      foreach ($pd as $key => $value) {$urlp .= '&' . $key . '=' . $value;}
      $a_number = file_get_contents(str_replace(" ", "---", $urlp));
    }

    $response_type = explode("::", $a_number);
    if($a_number && isset($response_type[0]) && $response_type[0] != 'Notice'){
      $this->a = $a_number;
      $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($a_number) . "', serialized = '0'  WHERE `" . (((int)VERSION >= 2)?'code':'group') . "` = 'unixml' AND `key` = 'unixml_key' AND store_id = '0'");
    }else{
      if(isset($response_type[1])){
        $this->message = $response_type[1];
      }
    }
  }

  private function varname($var, $feed = false) {
    return $this->ext . ($feed?('_' . $feed):'') . '_' . $var;
  }

  private function seop_pro_exist(){
    $seopro = false;
    $seopro_query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_to_category LIKE 'main_category'");
    if($seopro_query->num_rows){
      $seopro = true;
    }

    return $seopro;
  }

  private function updateUniXMLController($code_from, $code_to){
    $item = str_replace("system/", "catalog/model/", DIR_SYSTEM) . $this->path . '.php';

    $file_php = str_replace($code_from, $code_to, file_get_contents($item));

    $fp = fopen($item, "w");
    fwrite($fp, $file_php);
    fclose($fp);
  }

  public function index() {
    $data = $this->load->language($this->path);

    $this->install();

    $data['a'] = $this->a;
    $data['message'] = $this->message;
    $data['miv'] = (int)ini_get('max_input_vars');

    $this->document->setTitle(strip_tags($this->language->get('heading_title')));
    $heading_title_module = explode('</span>', $this->language->get('heading_title'));
    $data['heading_title_module'] = $heading_title_module[0] . ' ' . $this->language->get('version');

    $this->load->model('setting/setting');
    $this->load->model('catalog/category');
    $this->load->model('catalog/product');
    $this->load->model('catalog/manufacturer');
    $this->load->model('localisation/language');
    $this->load->model('localisation/currency');
    $this->load->model('localisation/stock_status');

    $data['token'] = $this->session->data['token'];
    $data['feeds'] = $this->feeds();
    $data['path'] = $this->path;
    $data['unixml_hide'] = $this->config->get('unixml_hide');
    if(!$this->config->get('unixml_hide')){
      $data['unixml_hide'] = $this->feeds();
    }
    $data['count_feeds'] = count($this->feeds());
    $data['count_active_feeds'] = count($data['unixml_hide']);
    $data['seopro'] = $this->seop_pro_exist();

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

      // + replaces
      foreach($this->feeds() as $feed){

        foreach(array('products','categories','manufacturers','option_multiplier_id') as $field){
          if (isset($this->request->post[$this->varname($field, $feed)])) {
            $this->request->post[$this->varname($field, $feed)] = implode(',', $this->request->post[$this->varname($field, $feed)]);
          }
        }

        //custom_xml
        if (isset($this->request->post[$this->varname('custom_xml', $feed)])) {
          $flag_data = '//custom_code_xml_start';

          $config_data = PHP_EOL . PHP_EOL . 'if($feed == "' . $feed . '"){ //' . $feed . '_custom_code_xml_start';
          $config_data.= PHP_EOL . $this->config->get($this->varname('custom_xml', $feed));
          $config_data.= PHP_EOL . '} //' . $feed . '_custom_code_xml_end';
          $this->updateUniXMLController(html_entity_decode($config_data, ENT_QUOTES, 'UTF-8'), ""); //clear old rules

          $post_data = PHP_EOL . PHP_EOL . 'if($feed == "' . $feed . '"){ //' . $feed . '_custom_code_xml_start';
          $post_data.= PHP_EOL . $this->request->post[$this->varname('custom_xml', $feed)];
          $post_data.= PHP_EOL . '} //' . $feed . '_custom_code_xml_end';
          $this->updateUniXMLController($flag_data, $flag_data . html_entity_decode($post_data, ENT_QUOTES, 'UTF-8')); //from - to
        }
        //custom_xml

        foreach($this->insert_tables as $insert_table => $insert_fields){
          $this->db->query("DELETE FROM " . $this->table($insert_table) . " WHERE feed = '" . $this->db->escape($feed) . "'");
          if (!empty($this->request->post[$this->varname($insert_table, $feed)]) && is_array($this->request->post[$this->varname($insert_table, $feed)])) {
            $attributes = $this->request->post[$this->varname($insert_table, $feed)];
            foreach ($attributes as $attribute) {
              $fields_db = "";
              foreach($insert_fields as $field_item){
                $fields_db .= " " . $field_item . " = '" . $attribute[$field_item] . "',";
              }
              $this->db->query("INSERT INTO " . $this->table($insert_table) . " SET " . $fields_db . " feed = '" . $this->db->escape($feed) . "'");
            }
          }
        }

        //product_markup
        if (isset($this->request->post[$this->varname('product_markup', $feed)])) {
          foreach($this->request->post[$this->varname('product_markup', $feed)] as $markup_item){
            $this->db->query("INSERT INTO " . $this->table('markup') . " SET name = '" . $this->db->escape($markup_item['name']) . "', products = '" . $this->db->escape(implode(',', array_unique($markup_item['products']))) . "', markup = '" . $this->db->escape($markup_item['markup']) . "', feed = '" . $this->db->escape($feed) . "'");
          }
        }
        //product_markup

      }
      // - replaces

      $this->model_setting_setting->editSetting($this->ext, $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link($this->path, 'token=' . $data['token'] . '&type=feed', true));

    }

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    $data['breadcrumbs'] = array();
    foreach(array($this->language->get('text_home') => 'common/dashboard',$this->language->get('text_extension') => $this->module_path,$data['heading_title_module'] => $this->path) as $breadcrumb_text => $breadcrumb_link){
      $data['breadcrumbs'][] = array(
        'text' => $breadcrumb_text,
        'href' => $this->url->link($breadcrumb_link, 'token=' . $this->session->data['token'], true)
      );
    }

    $data['action'] = $this->url->link($this->path, 'token=' . $this->session->data['token'], true);
    $data['cancel'] = $this->url->link($this->module_path, 'token=' . $this->session->data['token'] . '&type=feed', true);

    $data['categories'] = $this->getCategories();
    $data['category_all'] = count($data['categories']);

    $data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers(0);
    $data['languages'] = $this->model_localisation_language->getLanguages();
    $data['currencies'] = $this->model_localisation_currency->getCurrencies();
    $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

    $fields = $this->fields();

    foreach($this->feeds() as $feed){

      $field = 'products';
      if (isset($this->request->post[$this->varname($field, $feed)])) {
        $data[$this->varname($field, $feed)] = $this->request->post[$this->varname($field, $feed)];
      } elseif ($this->config->get($this->varname($field, $feed)) != '') {
        $data_unixml_products = explode(',', $this->config->get($this->varname($field, $feed)));
        foreach($data_unixml_products as $data_unixml_product_id){
          $product_info = $this->model_catalog_product->getProduct($data_unixml_product_id);
          $data[$this->varname($field, $feed)][] = array(
            'name' => $product_info['name'],
            'product_id' => $data_unixml_product_id
          );
        }
      } else {
        $data[$this->varname($field, $feed)] = array();
      }

      foreach($fields as $func => $field){

        if($field == 'categories' or $field == 'manufacturers' or $field == 'option_multiplier_id'){

          if (isset($this->request->post[$this->varname($field, $feed)])) {
            $data[$this->varname($field, $feed)] = $this->request->post[$this->varname($field, $feed)];
          } elseif ($this->config->get($this->varname($field, $feed)) != '') {
            $data[$this->varname($field, $feed)] = explode(',', $this->config->get($this->varname($field, $feed)));
          } else {
            $data[$this->varname($field, $feed)] = array();
          }

        }elseif($field == 'field_price'){

          if (isset($this->request->post[$this->varname($field, $feed)])) {
            $data[$this->varname($field, $feed)] = $this->request->post[$this->varname($field, $feed)];
          } elseif ($this->config->get($this->varname($field, $feed)) != '') {
            $data[$this->varname($field, $feed)] = $this->config->get($this->varname($field, $feed));
          } else {
            $data[$this->varname($field, $feed)] = 'p.price';
          }

        }elseif($field == 'field_id'){

          if (isset($this->request->post[$this->varname($field, $feed)])) {
            $data[$this->varname($field, $feed)] = $this->request->post[$this->varname($field, $feed)];
          } elseif ($this->config->get($this->varname($field, $feed)) != '') {
            $data[$this->varname($field, $feed)] = $this->config->get($this->varname($field, $feed));
          } else {
            $data[$this->varname($field, $feed)] = 'p.product_id';
          }

        }elseif($field == 'attributes' or $field == 'replace_name' or $field == 'additional_params' or $field == 'category_match' or $field == 'product_markup'){

          $data[$this->varname($field, $feed)] = array();

          if (isset($this->request->post[$this->varname($field, $feed)])) {
            $data[$this->varname($field, $feed)] = $this->request->post[$this->varname($field, $feed)];
          }  else {
            $data[$this->varname($field, $feed)] = $this->$func($feed);
          }

        }else{

          if (isset($this->request->post[$this->varname($field, $feed)])) {
            $data[$this->varname($field, $feed)] = $this->request->post[$this->varname($field, $feed)];
          } else {
            $data[$this->varname($field, $feed)] = $this->config->get($this->varname($field, $feed));
          }

        }

      }

      $data['fields_product'] = array();
      $data['fields_product_description'] = array();

      $fileds_query = $this->db->query("SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = '" . $this->db->escape(DB_DATABASE) . "' and table_name = '" . DB_PREFIX ."product'");
      foreach($fileds_query->rows as $row){
        $data['fields_product'][] = 'p.' . $row['COLUMN_NAME'];
      }

      $fileds_query = $this->db->query("SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = '" . $this->db->escape(DB_DATABASE) . "' and table_name = '" . DB_PREFIX ."product_description'");
      foreach($fileds_query->rows as $row){
        $data['fields_product_description'][] = 'pd.' . $row['COLUMN_NAME'];
      }

      $this->lang = $data[$this->varname('language', $feed)];

      $data[$this->varname('options', $feed)] = $this->getOptionList($data[$this->varname('language', $feed)]);
      $data[$feed . '_data_feed'] = HTTPS_CATALOG . 'index.php?route=' . $this->path . '/' . $feed;

    }
    //all feeds

    if (isset($this->request->post[$this->varname('active_tab')])) {
      $data[$this->varname('active_tab')] = $this->request->post[$this->varname('active_tab')];
    } elseif ($this->config->get($this->varname('active_tab')) != '') {
      $data[$this->varname('active_tab')] = $this->config->get($this->varname('active_tab'));
    }  else {
      $data[$this->varname('active_tab')] = false;
    }

    $data['success'] = false;
    if(isset($this->session->data['success'])){
      $data['success'] = $this->session->data['success'];
      unset($this->session->data['success']);
    }

		//more info
		$data['more_info'] = false;
    $data['more_info'] = @file_get_contents('https://exotiks.ru/index.php?route=sale/proposal&module=' . $this->ext);
    $data_info = @file_get_contents('https://exotiks.ru/index.php?route=sale/' . $this->ext . 'info');
    if($data_info){
      $info_final = unserialize($data_info);
    }
    foreach($this->feeds() as $feed){
      $data[$feed . '_info'] = '';
      if(isset($info_final[$feed])){
        $data[$feed . '_info'] = $info_final[$feed];
      }
    }
		//more info

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
    $data['module_init'] = $this->a;

    $data['export_setting'] = $this->url->link($this->path . '/export_setting', 'token=' . $data['token'], true);
    $data['import_setting'] = $this->url->link($this->path . '/import_setting', 'token=' . $data['token'], true);

    $this->response->setOutput($this->load->view($this->path . '.tpl', $data));

  }

  public function getCategories() {
		$sql = "SELECT cp.category_id AS category_id,
            GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name,
            c1.parent_id, c1.sort_order, c1.status
            FROM " . DB_PREFIX . "category_path cp
            LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id)
            LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id)
            LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id)
            LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id)
            WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (isset($this->request->get['search']) && $this->request->get['search']) {
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($this->request->get['search']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id ORDER BY cd1.name ASC";

		$query = $this->db->query($sql);
    if (isset($this->request->get['search'])) {
      $cats = array();
      foreach($query->rows as $row){
        $cats[] = $row['category_id'];
      }
      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($cats));
    }else{
      return $query->rows;
    }
	}

  public function export_setting(){
    $feed = $this->request->get['feed'];

    $settings = 'Backup setting for ' . $feed . ' from UniXML Pro (Support: info@microdata.pro) | Time: ' . date('d.m.Y [H:i]') . PHP_EOL . PHP_EOL;
    $setting_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` LIKE '%" . $this->db->escape($this->ext . "_" . $feed) . "%'");
    if($setting_query->num_rows){
      $settings .= base64_encode(serialize($setting_query->rows));
    }
    foreach($this->insert_tables as $table => $fields){
      $table_data_query = $this->db->query("SELECT * FROM " . $this->table($table) . " WHERE feed = '" . $this->db->escape($feed) . "'");
      $settings .= PHP_EOL . PHP_EOL . base64_encode(serialize($table_data_query->rows));
    }
    $this->response->addHeader('Content-disposition: attachment; filename=' . $this->ext . "_" . $feed . "_" . date('d') . date('m') . date('Y') . '.txt');
    $this->response->addHeader('Content-type: text/plain');
    $this->response->setOutput($settings);
  }

  public function import_setting(){
    $feed = $this->request->get['feed'];

    $json = array();
    if(isset($this->request->files['file'])){
      $file = file_get_contents($this->request->files['file']['tmp_name'], 'rb');
      if($file){
        $file_data = explode(PHP_EOL, $file);
        if(isset($file_data[0]) && isset($file_data[1]) && isset($file_data[2])){
          if(substr($file_data[0], 0, 6) == "Backup" && empty($file_data[1]) && !empty($file_data[2])){
            $feed_in_file_array = explode(" from", str_replace("Backup setting for ", "", $file_data[0]));
            if(isset($feed_in_file_array[0])){
              $feed_in_file = $feed_in_file_array[0];

              //setting
              $settings = unserialize(base64_decode($file_data[2]));
              if($settings){
                $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` LIKE '%" . $this->db->escape($this->ext . "_" . $feed) . "%'");
                foreach($settings as $setting_row){
                  $query_fields = "";
                  foreach($setting_row as $data_key => $data_value){
                    if($data_key != 'setting_id'){
                      $query_fields .= "`" . $data_key . "` = '" . $this->db->escape($data_value) . "', ";
                    }
                  }
                  $sql = "INSERT INTO " . DB_PREFIX . "setting SET " . rtrim(trim($query_fields), ',');
                  $sql = str_replace($feed_in_file, $feed, $sql);
                  $this->db->query($sql);
                }
              }
              //setting

              //tables
              $start_table_row = 4;
              foreach($this->insert_tables as $table => $fields){
                if(isset($file_data[$start_table_row])){
                  $table_data = unserialize(base64_decode($file_data[$start_table_row]));
                  if($table_data){
                    $this->db->query("DELETE FROM " . $this->table($table) . " WHERE feed = '" . $this->db->escape($feed) . "'");
                    foreach($table_data as $data_row){
                      $query_fields = "";
                      foreach($data_row as $data_key => $data_value){
                        if($data_key != 'item_id'){
                          $query_fields .= "`" . $data_key . "` = '" . $this->db->escape($data_value) . "', ";
                        }
                      }
                      $sql = "INSERT INTO " . $this->table($table) . " SET " . rtrim(trim($query_fields), ',');
                      $sql = str_replace($feed_in_file, $feed, $sql);
                      $this->db->query($sql);
                    }
                  }
                }

                $start_table_row += 2;
              }
              //tables
              $json['success'] = true;
            }
          }else{
            $json['error'] = "В файле есть ошибки. Импорт не удался.";
          }
        }else{
          $json['error'] = "В файле нет необходимых данных. Импорт не удался.";
        }
      }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function googleCategory() {
    $categories = array();

    $categories_data = $this->cache->get('google.categories');
    if(!$categories_data){
      $categories_data = @file_get_contents('https://www.google.com/basepages/producttype/taxonomy-with-ids.ru-RU.txt');
      $this->cache->set('google.categories', $categories_data);
    }

    $category_rows = explode(PHP_EOL, $categories_data);
    foreach($category_rows as $category_item){
      $category_data = explode(' - ', $category_item);
      if(isset($category_data[0]) && isset($category_data[1])){
        if (isset($this->request->get['filter_name'])) {
          if(trim($this->request->get['filter_name']) != ''){
            $pos = strpos(mb_strtolower($category_data[0] . $category_data[1], 'UTF-8'), mb_strtolower($this->request->get['filter_name'], 'UTF-8'));
            if ($pos !== false) { //если что-то найдено
              $categories[$category_data[1]] = array(
                'category_id' => $category_data[0],
                'name' => $category_data[0] . ' - ' . $category_data[1]
              );
            }
          }
        }else{
          $categories[$category_data[1]] = array(
            'category_id' => $category_data[0],
            'name' => $category_data[0] . ' - ' . $category_data[1]
          );
        }
      }
    }

    ksort($categories);

    $categories = array_slice($categories, 0, 20);

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($categories));
  }

  public function kidstaffCategory() {
    $categories = array();

    $categories_data = $this->cache->get('kidstaff.categories');
    if(!$categories_data){
      $categories_data = @simplexml_load_file('https://www.kidstaff.com.ua/categories.xml');
      $this->cache->set('kidstaff.categories', $categories_data);
    }

    foreach ($categories_data['category'] as $category_item) {

      if(isset($category_item['id']) && isset($category_item['name'])){
        if (isset($this->request->get['filter_name'])) {
          $pos = strpos(mb_strtolower($category_item['id'] . $category_item['name'], 'UTF-8'), mb_strtolower($this->request->get['filter_name'], 'UTF-8'));
          if ($pos !== false) { //если что-то найдено
            $categories[$category_item['name']] = array(
              'category_id' => $category_item['id'],
              'name' => $category_item['id'] . ' - ' . $category_item['name']
            );
          }
        }else{
          $categories[$category_item['name']] = array(
            'category_id' => $category_item['id'],
            'name' => $category_item['id'] . ' - ' . $category_item['name']
          );
        }
      }

    }

    ksort($categories);

    $categories = array_slice($categories, 0, 20);

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($categories));
  }

  public function autocomplete_product() {
    $json = array();

    if (isset($this->request->get['filter_name']) && strlen($this->request->get['filter_name']) > 0) {

      $sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE";
      $sql .= " pd.name LIKE '%" . $this->db->escape($this->request->get['filter_name']) . "%' OR p.model LIKE '%" . $this->db->escape($this->request->get['filter_name']) . "%'";
      $sql .= " GROUP BY p.product_id ORDER BY pd.name ASC LIMIT 0,10";

      $query = $this->db->query($sql);

      foreach ($query->rows as $result) {
        $name = $result['name'];
        if($result['model']){
          $name = $result['model'] . ' - ' . $result['name'];
        }
        $json[] = array(
          'product_id' => $result['product_id'],
          'name'       => strip_tags(html_entity_decode($name, ENT_QUOTES, 'UTF-8')),
          'model'      => $result['model'],
          'price'      => $result['price']
        );
      }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function autocomplete_category() {
    $json = array();

    if (isset($this->request->get['filter_name'])) {

      $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order, c1.status,(select count(product_id) as product_count from " . DB_PREFIX . "product_to_category pc where pc.category_id = c1.category_id) as product_count FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
  		if (!empty($this->request->get['filter_name'])) {
  			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($this->request->get['filter_name']) . "%'";
  		}
  		$sql .= " GROUP BY cp.category_id ORDER BY name ASC LIMIT 0, 10";

  		$query = $this->db->query($sql);

      foreach ($query->rows as $result) {
        $json[] = array(
          'category_id' => $result['category_id'],
          'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
        );
      }
    }

    $sort_order = array();

    foreach ($json as $key => $value) {
      $sort_order[$key] = $value['name'];
    }

    array_multisort($sort_order, SORT_ASC, $json);

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  private function getOptionList($language = 1){
    $options = array();

    $query = $this->db->query("SELECT option_id, name FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$language . "'");

    foreach($query->rows as $row){
      $options[] = array(
        'option_id' => $row['option_id'],
        'name'      => $row['name']
      );
    }

    return $options;
  }

  private function getAttributeList($feed){
    $attributes = array();
    $query = $this->db->query("SELECT * FROM " . $this->table('attributes') . " WHERE feed = '" . $this->db->escape($feed) . "' ORDER BY item_id ASC");
    foreach($query->rows as $row){
      $attribute_name_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$row['attribute_id'] . "' AND language_id = '" . (int)$this->lang . "'");
      if($attribute_name_query->num_rows){
        $attribute_name = $attribute_name_query->row['name'];
      }else{
        $attribute_name = "Внимание! Атрибут не найден.";
      }

      $attributes[] = array(
        'attribute_id'   => $row['attribute_id'],
        'attribute_name' => $attribute_name,
        'xml_name'   => $row['xml_name']
      );
    }
    return $attributes;
  }

  private function getReplaceNameList($feed){
    $replace = array();
    $query = $this->db->query("SELECT * FROM " . $this->table('replace_name') . " WHERE feed = '" . $this->db->escape($feed) . "' ORDER BY item_id ASC");
    foreach($query->rows as $row){
      $replace[] = array(
        'name_from'   => $row['name_from'],
        'name_to'    => $row['name_to'],
      );
    }
    return $replace;
  }

  private function getReplaceCategory($feed){
    $categories = array();
    $query = $this->db->query("SELECT * FROM " . $this->table('category_match') . " WHERE feed = '" . $this->db->escape($feed) . "' ORDER BY item_id ASC");
    foreach($query->rows as $row){

      $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order, c1.status,(select count(product_id) as product_count from " . DB_PREFIX . "product_to_category pc where pc.category_id = c1.category_id) as product_count FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cp.category_id LIKE '" . $this->db->escape($row['category_id']) . "' GROUP BY cp.category_id ORDER BY name ASC LIMIT 0, 1";

      $query = $this->db->query($sql);
      if(isset($query->row['name']) && $query->row['name']){
        $category_name = strip_tags(html_entity_decode($query->row['name'], ENT_QUOTES, 'UTF-8'));
      }else{
        $category_name = 'Внимание! Категория не найдена!';
      }

      $categories[] = array(
        'category_id'   => $row['category_id'],
        'category_name' => $category_name,
        'xml_name'   => $row['xml_name'],
        'markup'   => $row['markup'],
        'custom'   => $row['custom']
      );
    }
    return $categories;
  }

  private function getMarkup($feed){
    $this->load->model('catalog/product');
    $markups = array();
    $query = $this->db->query("SELECT * FROM " . $this->table('markup') . " WHERE feed = '" . $this->db->escape($feed) . "' ORDER BY item_id ASC");
    foreach($query->rows as $row){

      $products = array();
      if($row['products']){
        foreach(explode(',', $row['products']) as $product_id){
          $product_info = $this->model_catalog_product->getProduct($product_id);
          $products[] = array(
            'product_id' => $product_info['product_id'],
            'name' => $product_info['name'],
            'model' => $product_info['model']
          );
        }
      }

      $markups[] = array(
        'name' => $row['name'],
        'markup'   => $row['markup'],
        'products'   => $products
      );
    }

    return $markups;
  }

  public function select_country(){
    $feeds = array();

    $key = 0;
    $country = $this->request->get['country'];
    if($country == 'ru'){$key = 2;}
    if($country == 'ua'){$key = 3;}
    if($country == 'kz'){$key = 4;}

    $unixml_dat = explode(PHP_EOL, file_get_contents($this->request->server['DOCUMENT_ROOT'] . '/price/' . $this->ext . '.dat'));
    $feeds_data = explode('==', $unixml_dat[$key]);
    foreach(explode(',', isset($feeds_data[1])?$feeds_data[1]:$feeds_data[0]) as $feed){
      if($feed){
        $feeds[] = trim($feed);
      }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($feeds));
  }

  private function getAdditionalParamList($feed){
    $attributes = array();
    $query = $this->db->query("SELECT * FROM " . $this->table('additional_params') . " WHERE feed = '" . $this->db->escape($feed) . "' ORDER BY item_id ASC");
    foreach($query->rows as $row){
      $attributes[] = array(
        'param_name' => $row['param_name'],
        'param_text'   => $row['param_text']
      );
    }
    return $attributes;
  }

  public function autocomplete() {
    $json = array();

    if (isset($this->request->get['filter_name'])) {
      $this->load->model('catalog/attribute');

      $filter_data = array(
        'filter_name' => $this->request->get['filter_name'],
        'start'       => 0,
        'limit'       => 5
      );

      $results = $this->model_catalog_attribute->getAttributes($filter_data);

      foreach ($results as $result) {
        $json[] = array(
          'attribute_id'    => $result['attribute_id'],
          'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
          'attribute_group' => $result['attribute_group'],
          'language_id'     => $result['language_id']
        );
      }
    }

    $sort_order = array();

    foreach ($json as $key => $value) {
      $sort_order[$key] = $value['name'];
    }

    array_multisort($sort_order, SORT_ASC, $json);

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function import_product() {
    $this->load->model('catalog/product');
    $json = array();
    $json['error'] = 0;
    $json['success'] = 0;

    $field = 'product_id';
    if(isset($this->request->post['import_field']) && $this->request->post['import_field']){
      $field = trim($this->request->post['import_field']);
    }else{
      $json['error'] = 'Вы не заполнили что является данными в товаре';
    }

    if(isset($this->request->post['products']) && $this->request->post['products']){
      $products = $this->request->post['products'];
    }else{
      $json['error'] = 'Вы не заполнили поле товар';
    }

    if(!$json['error']){
      $separator = PHP_EOL;
      if(isset($this->request->post['import_serapator']) && $this->request->post['import_serapator']){
        $separator = $this->request->post['import_serapator'];
      }

      $products_array = explode($separator, trim($products));
      if($products_array){
        $json['count'] = count($products_array);
        foreach ($products_array as $product_item) {
          $product_id_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE " . $this->db->escape($field) . " = '" . $this->db->escape(trim($product_item)) . "' LIMIT 1");
          if(isset($product_id_query->row['product_id']) && $product_id_query->row['product_id']){
            $product_info = $this->model_catalog_product->getProduct($product_id_query->row['product_id']);
            if($product_info){
              $json['products'][] = array('product_id' => $product_info['product_id'],'name' => $product_info['model'] . ' - ' . $product_info['name']);
              $json['success']++;
            }
          }
        }
      }else{
        $json['error'] = 'Не удалось выделить ни один товар';
      }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  private function validate() {
    if (!$this->user->hasPermission('modify', $this->path)) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (!$this->error) {
      return true;
    } else {
      return false;
    }
  }

  public function init_key($key){
    $domen = explode("//", HTTP_CATALOG);
    $f = false;
    $a=0;if(isset($key) && !empty($key)){ $key_array = explode("327450", base64_decode(strrev(substr($key, 0, -7))));if($key_array[0] == base64_encode(str_replace("/", "", $domen[1])) && $key_array[1] == base64_encode(3274507473+100)){$a= 1;}}
    return $f=str_replace($key,str_replace("/", "", $domen[1]),$a);
  }

}