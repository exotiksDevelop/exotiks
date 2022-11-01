<?php
class ControllerModuleSocnetauth2popup extends Controller {
	private $data;
	
	public function index($setting) {
	
		$this->language->load('module/socnetauth2');
		
		if ($this->customer->isLogged()) {
	  		return false;
    	}
		
		if( !$this->config->get('socnetauth2_status') ) return false;
		
		if( empty( $_COOKIE['show_socauth2_popup'] ) )
		{
			$this->data['show_socauth2_popup'] = 1;
		}
		else
		{
			$this->data['show_socauth2_popup'] = 0;
		}
		
		$this->data['socnetauth2_mobile_control'] = $this->config->get('socnetauth2_mobile_control');
		
		
      	$this->data['socnetauth2_vkontakte_status'] = $this->config->get('socnetauth2_vkontakte_status');
      	$this->data['socnetauth2_odnoklassniki_status'] = $this->config->get('socnetauth2_odnoklassniki_status');
      	$this->data['socnetauth2_facebook_status'] = $this->config->get('socnetauth2_facebook_status');
      	$this->data['socnetauth2_twitter_status'] = $this->config->get('socnetauth2_twitter_status');
      	$this->data['socnetauth2_gmail_status'] = $this->config->get('socnetauth2_gmail_status');
      	$this->data['socnetauth2_mailru_status'] = $this->config->get('socnetauth2_mailru_status');
		
      	$this->data['heading_title1'] = $this->language->get('heading_title1');
      	$this->data['heading_title2'] = $this->language->get('heading_title2');
      	$this->data['text_skip'] = $this->language->get('text_skip');
		
	
		if(  version_compare(VERSION, '2.2.0.0') >= 0 )
		{
			$this->response->setOutput($this->load->view('module/socnetauth2_popup', $this->data));
		}
		else
		{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/socnetauth2_popup.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/socnetauth2_popup.tpl', $this->data);
			} else {
				return $this->load->view('default/template/module/socnetauth2_popup.tpl', $this->data);
			}
				
		}
		
		
		
	}
}
?>