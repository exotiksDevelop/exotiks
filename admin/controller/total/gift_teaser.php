<?php
class ControllerTotalGiftTeaser extends Controller {
 	private $moduleName;
	private $moduleTotal;
    private $modulePath;
	private $totalPath;
	private $moduleModel;
	private $moduleVersion;
    private $extensionsLink;
	private $totalLink;
    private $callModel;
    private $error = array(); 
    private $data = array();

    public function __construct($registry) {
        parent::__construct($registry);
        
        // Config Loader
        $this->config->load('ocmod/giftteaser');
        
        // Module Constants
        $this->moduleName           = $this->config->get('giftteaser_name');
        $this->moduleTotal      	= $this->config->get('giftteaser_total');
		$this->moduleName           = $this->config->get('giftteaser_name');
        $this->callModel            = $this->config->get('giftteaser_model');
        $this->modulePath           = $this->config->get('giftteaser_path');
		$this->totalPath         	= $this->config->get('giftteaser_total_path');
	    $this->moduleVersion        = $this->config->get('giftteaser_version');   
		$this->moduleData_module    = $this->config->get('giftteaser_module_data');        
        $this->totalLink      		= $this->url->link($this->config->get('giftteaser_total_link'), 'token=' . $this->session->data['token'].$this->config->get('giftteaser_total_link_params'), 'SSL');

        // Load Language
        $this->load->language($this->totalPath);
        
        // Load Model
        $this->load->model($this->modulePath);
		        
        // Model Instance
        $this->moduleModel          = $this->{$this->callModel};
		

        // Global Variables      
        $this->data['moduleName']  		 = $this->moduleName;
		$this->data['moduleNameSmall']   = $this->moduleNameSmall;
        $this->data['modulePath']   	 = $this->modulePath;
		$this->data['feedPath']   	 	 = $this->feedPath;
		$this->data['moduleData_module'] = $this->moduleData_module;
		$this->data['moduleModel'] 		 = $this->moduleModel;
    }	

  public function index() { 

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting($this->moduleTotal, $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->totalLink);
    }

    $this->data['heading_title'] = $this->language->get('heading_title');

    $this->data['text_enabled'] = $this->language->get('text_enabled');
    $this->data['text_disabled'] = $this->language->get('text_disabled');

    $this->data['entry_status'] = $this->language->get('entry_status');
    $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

    $this->data['button_save'] = $this->language->get('button_save');
    $this->data['button_cancel'] = $this->language->get('button_cancel');

	
	if (isset($this->error['warning'])) { 
		$this->data['error_warning'] = $this->error['warning'];
	} else {
		$this->data['error_warning'] = '';
	}

    $this->data['breadcrumbs'] = array();

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
    ); 

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_total'),
      'href'      => $this->totalLink,
      'separator' => ' :: '
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link($this->totalPath, 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $this->data['action'] = $this->url->link($this->totalPath, 'token=' . $this->session->data['token'], 'SSL');

    $this->data['cancel'] = $this->totalLink;

    if (isset($this->request->post[$this->moduleTotal.'_status'])) {
      $this->data[$this->moduleTotal.'_status'] = $this->request->post[$this->moduleTotal.'_status'];
    } else {
      $this->data[$this->moduleTotal.'_status'] = $this->config->get($this->moduleTotal.'_status');
    }

    if (isset($this->request->post[$this->moduleTotal.'_sort_order'])) {
      $this->data[$this->moduleTotal.'_sort_order'] = $this->request->post[$this->moduleTotal.'_sort_order'];
    } else {
      $this->data[$this->moduleTotal.'_sort_order'] = $this->config->get($this->moduleTotal.'_sort_order');
    }
 
        $this->data['header']                 = $this->load->controller('common/header');
        $this->data['column_left']            = $this->load->controller('common/column_left');
        $this->data['footer']                 = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view($this->totalPath.'.tpl', $this->data));
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', $this->totalPath)) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

	return !$this->error;
  }
}
?>