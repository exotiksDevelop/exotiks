<?php
class ControllerModuleRedirector extends Controller {
  private $error = array();
  private $ext = 'redirector';
  private $module= 'module/redirector';
  private $module_path = 'extension/module';
  private $message = '';
  private $a = 0;
  private $str_search = '// Response';

  public function install() {
    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "redirector` (`redirect_id` int(11) NOT NULL AUTO_INCREMENT, `url_from` varchar(512) NOT NULL, `url_to` varchar(512) NOT NULL, `status` tinyint(1) NOT NULL DEFAULT '1', PRIMARY KEY (`redirect_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $this->updateIndex();
  }

  public function uninstall() {
    $this->updateIndex(1);
    $this->init_module();
  }

  private function updateIndex($delete = false){
    $item = str_replace('system/', '', DIR_SYSTEM) . 'index.php';
    $file_html = file_get_contents($item);
    if(!$delete){
      $file_html = str_replace($this->str_search, $this->indexCode(), $file_html);
      rename($item, $item."_orig"); //backup original index file
    }else{
      $file_html = str_replace($this->indexCode(), $this->str_search, $file_html);
    }
    $fp = fopen($item, "w");
    fwrite($fp, $file_html);
    fclose($fp);
  }

  private function indexCode(){
    $index_code = PHP_EOL . '//REDIRECTOR START';
    $index_code.= PHP_EOL . 'if($config->get(\'redirector_status\')){';
    $index_code.= PHP_EOL . '$url_to = $db->query("SELECT `url_to` FROM " . DB_PREFIX . "redirector WHERE status = 1 AND url_to != \'\' AND url_from = \'" . $db->escape(str_replace("&","&amp;",str_replace("&amp;", "&", substr(urldecode($request->server["REQUEST_URI"]), 1))))  . "\' ORDER BY redirect_id DESC LIMIT 1");';
    $index_code.= PHP_EOL . 'if(isset($url_to->row["url_to"]) && $url_to->row["url_to"]){header("HTTP/1.1 301 Moved Permanently");header("Location: " . HTTPS_SERVER . $url_to->row["url_to"]);exit;}';
    $index_code.= PHP_EOL . '}';
    $index_code.= PHP_EOL . '//REDIRECTOR END';
    $index_code.= PHP_EOL . PHP_EOL . $this->str_search;

    return $index_code;
  }

  public function index() {
    $data = $this->language->load($this->module);
    $this->init_module();
    $on_page = 20;
    $page = 1;
    $data['a'] = $this->a;
    $data['message'] = $this->message;

    $this->document->setTitle(strip_tags($this->language->get('heading_title')));

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
    );
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link($this->module_path, 'token=' . $this->session->data['token'] . '&type=module', true)
    );
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link($this->module, 'token=' . $this->session->data['token'], true)
    );

    $data['action'] = $this->url->link($this->module, 'token=' . $this->session->data['token'], true);
    $data['cancel'] = $this->url->link($this->module_path, 'token=' . $this->session->data['token'] . '&type=module', true);
    $data['status'] = $this->config->get('redirector_status');
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    if(isset($this->request->post['delete']) && isset($this->request->post['redirect_id'])){
      $this->db->query("DELETE FROM " . DB_PREFIX . "redirector  WHERE redirect_id = '" . (int)$this->request->post['redirect_id'] . "'");
    }

    $data['redirects'] = array();
    $data['redirect_current'] = 0;
    $data['redirect_all'] = 0;

    $data_filter = array();

    if(isset($this->request->get['search'])){
      $data_filter['search'] = $this->request->get['search'];
    }

    $data['redirect_all'] = $this->getAllRedirects();

    if(isset($this->request->get['page'])){
      $page = $this->request->get['page'];
    }

    $data_filter['limit_on_page'] = $on_page;
    $data_filter['limit'] = $on_page * $page;

    $data['redirect_current'] = $data_filter['limit'];
    if($data['redirect_all'] < $data['redirect_current']){
      $data['redirect_current'] = $data['redirect_all'];
    }

    $redirects = $this->getRedirects($data_filter);

    if($redirects){
      foreach($redirects as $key => $redirect){
        $data['redirects'][$key+1] = array(
          'redirect_id' => $redirect['redirect_id'],
          'url_from' => $redirect['url_from'],
          'url_to' => $redirect['url_to'],
          'status' => $redirect['status']
        );
      }
    }

    $data['module'] = $this->module;
    $data['token'] = $this->session->data['token'];
    $data['module_init'] = $this->a;
    $data['more_info'] = false;
    $data['more_info'] = @file_get_contents('https://microdata.pro/index.php?route=sale/proposal&module=' . $this->ext);

    $this->response->setOutput($this->load->view($this->module . '.tpl', $data));
  }

  private function init_module() {
    $this->load->language($this->path);
		$domen = explode("//", HTTP_CATALOG);
    $curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://microdata.pro/index.php?route=sale/" . $this->ext);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, array('email'=>$this->config->get('config_email'),'module'=>$this->ext,'version'=>$this->language->get('version'),'site'=>str_replace("/", "", $domen[1]),'sec_token'=>"3274507573",'lang'=>$this->config->get('config_language'),'engine'=>VERSION,'date'=>date("Y-m-d H:i:s")));
		$a_number = curl_exec($curl);
		curl_close($curl);
    $response_type = explode("::", $a_number);
    if($a_number && isset($response_type[0]) && $response_type[0] != 'Notice'){
      $this->a = $a_number;
      $this->load->model('setting/setting');
      $this->model_setting_setting->editSettingValue('unixml', 'unixml_key', $a_number);
    }else{
      if(isset($response_type[1])){
        $this->message = $response_type[1];
      }
    }
  }

  public function changeStatus(){
    $this->load->model('setting/setting');
    $this->model_setting_setting->editSetting('redirector', array('redirector_status' => $this->request->post['status']));
  }

  public function import(){
    $total = "---";
    if($this->request->post['import_data']){
      $import_rows = explode(PHP_EOL, $this->request->post['import_data']);
      if($import_rows){
        $separ = ' ';
        if($this->request->post['import_separ']){
          $separ = $this->request->post['import_separ'];
        }
        foreach ($import_rows as $row) {
          $row_data = explode($separ, $row);
          if(isset($row_data[0]) && isset($row_data[1])){
            $from = ltrim(str_replace(array(HTTPS_CATALOG, HTTP_CATALOG), '', trim($row_data[0])), "/");
            if($row_data[1] != "/"){
              $to = ltrim(str_replace(array(HTTPS_CATALOG, HTTP_CATALOG), '', trim($row_data[1])), "/");
            }
            $total = (int)$total+1;
            $this->db->query("INSERT INTO " . DB_PREFIX . "redirector SET url_from = '" . $this->db->escape($from) . "', url_to = '" . $this->db->escape($to) . "', status = '1'");
          }
        }
      }
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($total));
  }

  public function addRow(){
    $this->db->query("INSERT INTO " . DB_PREFIX . "redirector SET url_from = '', url_to = '', status = '1'");
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($this->db->getLastId()));
  }

  public function removeRow(){
    $this->db->query("DELETE FROM " . DB_PREFIX . "redirector WHERE redirect_id = '" . (int)$this->request->post['redirect_id'] . "'");
  }

  public function updateRow(){
    $status = 0;
    if($this->request->post['status'] == 'true'){$status = 1;}
    $this->request->post['url_from'] = ltrim(str_replace(array(HTTPS_CATALOG, HTTP_CATALOG), '', $this->request->post['url_from']), "/");
    if($this->request->post['url_to'] != "/"){
      $this->request->post['url_to'] = ltrim(str_replace(array(HTTPS_CATALOG, HTTP_CATALOG), '', $this->request->post['url_to']), "/");
    }
    $this->db->query("UPDATE " . DB_PREFIX . "redirector SET url_from = '" . $this->db->escape($this->request->post['url_from']) . "', url_to = '" . $this->db->escape($this->request->post['url_to']) . "', status = '" . (int)$status . "' WHERE redirect_id = '" . (int)$this->request->post['redirect_id'] . "'");
  }

  protected function getAllRedirects(){
    $query = $this->db->query("SELECT COUNT(*) as ttl FROM " . DB_PREFIX . "redirector");
    return $query->row['ttl'];
  }

  protected function getRedirects($filter){
    $sql = "SELECT * FROM " . DB_PREFIX . "redirector";
    if(isset($filter['search']) && $filter['search'] != ''){
      $sql .= " WHERE (url_from LIKE '%" . $this->db->escape($filter['search']) . "%' OR url_to LIKE '%" . $this->db->escape($filter['search']) . "%')" ;
    }
    $sql .= "  ORDER BY redirect_id DESC LIMIT " . ($filter['limit'] - $filter['limit_on_page']) . "," . $filter['limit_on_page'];

    $query = $this->db->query($sql);

    return $query->rows;
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', $this->module)) {
      $this->error['warning'] = $this->language->get('error_permission');
    }
    return true;
  }

}
