<?php
class ModelTotalGiftTeaser extends Model {
	private $moduleName;
	private $moduleTotal;
    private $modulePath;
	private $moduleModel;
	private $moduleVersion;
    private $extensionsLink;
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
	
  public function getTotal(&$total_data, &$total, &$taxes) {

    $this->load->model('setting/setting');

    $setting = $this->model_setting_setting->getSetting($this->moduleName, $this->config->get('config_store_id'));

    if($setting[$this->moduleName]['Enabled'] && $setting[$this->moduleName]['Enabled'] == 'yes' ) {
      $amount = 0;

      $title = $this->language->get('text_gift_teaser');
      $text_refresh_gifts = $this->language->get('text_refresh_gifts');

      if (!empty($this->session->data['gift_teaser_exclude'])) {

        if (!empty($this->request->get['route']) && $this->request->get['route'] == 'module/cart') {
          $title .= ' (<a onclick="jQuery.ajax({url:\'index.php?route='.$this->modulePath.'/refresh_gifts\', success: function() { location = location; }});">' . $text_refresh_gifts . '</a>)';
        } else if (!empty($this->request->get['route']) && stripos($this->request->get['route'], 'cart') !== false) {
          $title .= ' (<a onclick="jQuery.ajax({url:\'index.php?route='.$this->modulePath.'/refresh_gifts\', success: function() { location = location; }});">' . $text_refresh_gifts . '</a>)';
        }
      }

      $cart_products = $this->cart->getProducts(); 
	  $show = false;
      foreach ($cart_products as $key => $cart_product) {
        if (!empty($cart_product['gift_teaser'])) {
          $amount += $cart_product['quantity'] * (float)$this->tax->calculate($cart_product['real_price'], $cart_product['real_tax_class_id'], $this->config->get('config_tax'));
	      $show = true;
        }
      }
		if ($show==true || !empty($this->session->data['gift_teaser_exclude'])) {
		  $total_data[] = array(
			'code'       => $this->moduleTotal,
			'title'      => $title,
			'text'       => '' . $this->currency->format($amount),
			'value'      => $amount,
			'sort_order' => $this->config->get($this->moduleTotal.'_sort_order')
		  );
		}
    }
  }

}
?>