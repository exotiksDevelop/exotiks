<?php 
class ControllerPaymentrpcod2ems extends Controller {
	private $error = array(); 
	private $data = array(); 
	private $code = 'sf345d456iopwertad';
	
	public function index() { 
		$this->language->load('payment/rpcod2ems');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('rpcod2ems', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			if( !empty($this->request->post['stay']) )
			{
				$this->response->redirect( $this->url->link('payment/rpcod2ems', 'token=' . $this->session->data['token'], 'SSL') );
			}
			else
			{
				$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		
		$this->data['entry_tariftype'] = $this->language->get('entry_tariftype');
		$this->data['entry_tariftype_default'] = $this->language->get('entry_tariftype_default');
		$this->data['entry_tariftype_set'] = $this->language->get('entry_tariftype_set');	
		$this->data['entry_tariftype_min'] = $this->language->get('entry_tariftype_min');	
		$this->data['entry_tariftype_percent'] = $this->language->get('entry_tariftype_percent');	
		
		$this->data['tab_shipping'] 	= $this->language->get('tab_shipping');
		$this->data['tab_payment'] 	= $this->language->get('tab_payment');
		$this->data['tab_support'] 	= $this->language->get('tab_support');
		
		$this->data['text_rpcod2ems_insum_notice'] = $this->language->get('text_rpcod2ems_insum_notice');
		
		$this->data['text_frame']   = $this->language->get('text_frame');
		$this->data['text_contact'] = $this->language->get('text_contact');
		
		
		$this->data['entry_stop_for_avia'] = $this->language->get('entry_stop_for_avia');
		
				
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		
		$this->data['entry_mintotal'] = $this->language->get('entry_mintotal');	
		$this->data['entry_maxtotal'] = $this->language->get('entry_maxtotal');	
		
		$this->data['entry_insum'] = $this->language->get('entry_insum');	
		$this->data['entry_insum_yes'] = $this->language->get('entry_insum_yes');	
		$this->data['entry_insum_no'] = $this->language->get('entry_insum_no');	
		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_total_title'] = $this->language->get('entry_total_title');
		
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['button_save_go'] = $this->language->get('button_save_go');
		$this->data['button_save_stay'] = $this->language->get('button_save_stay');
		
		$this->data['text_annotation_header'] = $this->language->get('text_annotation_header');
		$this->data['text_annotation'] = $this->language->get('text_annotation');
		
		$this->data['entry_debug'] = $this->language->get('entry_debug');

		$this->data['text_shipping_button'] = $this->language->get('text_shipping_button');
		
		if (isset($this->request->post['rpcod2ems_mintotal'])) {
			$this->data['rpcod2ems_mintotal'] = $this->request->post['rpcod2ems_mintotal'];
		} else {
			$this->data['rpcod2ems_mintotal'] = $this->config->get('rpcod2ems_mintotal');
		}
		
		/* start 0104 */
		
		$this->data['entry_order_filters'] = $this->language->get('entry_order_filters');
		
		$rpcod2_order_filters = array();
		if( $this->config->get('rpcod2ems_order_filters') )
			$rpcod2_order_filters = $this->config->get('rpcod2ems_order_filters');
		
		$this->load->model('shipping/russianpost2');
		$russianpost2_order_filters = $this->model_shipping_russianpost2->getFilters('order');
		
		foreach($russianpost2_order_filters as $order_filter)
		{
			if( !empty($rpcod2_order_filters[ $order_filter['filter_id'] ]) )
				$order_filter['status'] = 1;
			else
				$order_filter['status'] = 0;
			
			$this->data['rpcod2ems_order_filters'][] = $order_filter;
		} 
		
		/* end 0104 */
		
		if (isset($this->request->post['rpcod2ems_maxtotal'])) {
			$this->data['rpcod2ems_maxtotal'] = $this->request->post['rpcod2ems_maxtotal'];
		} else {
			$this->data['rpcod2ems_maxtotal'] = $this->config->get('rpcod2ems_maxtotal');
		}
		
		if (isset($this->request->post['rpcod2ems_maxtotal'])) {
			$this->data['rpcod2ems_maxtotal'] = $this->request->post['rpcod2ems_maxtotal'];
		} else {
			$this->data['rpcod2ems_maxtotal'] = $this->config->get('rpcod2ems_maxtotal');
		}

		if (isset($this->request->post['rpcod2ems_tariftype'])) {
			$this->data['rpcod2ems_tariftype'] = $this->request->post['rpcod2ems_tariftype'];
		} else {
			$this->data['rpcod2ems_tariftype'] = $this->config->get('rpcod2ems_tariftype');
		}

		if (isset($this->request->post['rpcod2ems_tariftype_min'])) {
			$this->data['rpcod2ems_tariftype_min'] = $this->request->post['rpcod2ems_tariftype_min'];
		} else {
			$this->data['rpcod2ems_tariftype_min'] = $this->config->get('rpcod2ems_tariftype_min');
		}

		if (isset($this->request->post['rpcod2ems_tariftype_percent'])) {
			$this->data['rpcod2ems_tariftype_percent'] = $this->request->post['rpcod2ems_tariftype_percent'];
		} else {
			$this->data['rpcod2ems_tariftype_percent'] = $this->config->get('rpcod2ems_tariftype_percent');
		}
		
		
		
		
		
		
		
		
		if (isset($this->request->post['rpcod2ems_maxtotal'])) {
			$this->data['rpcod2ems_maxtotal'] = $this->request->post['rpcod2ems_maxtotal'];
		} else {
			$this->data['rpcod2ems_maxtotal'] = $this->config->get('rpcod2ems_maxtotal');
		}
		
		if (isset($this->request->post['rpcod2ems_insum'])) {
			$this->data['rpcod2ems_insum'] = $this->request->post['rpcod2ems_insum'];
		} elseif( $this->config->has('rpcod2ems_insum') ) {
			$this->data['rpcod2ems_insum'] = $this->config->get('rpcod2ems_insum');
		} else {
			$this->data['rpcod2ems_insum'] = 1;
		}
		
		
		if (isset($this->request->post['rpcod2ems_debug'])) {
			$this->data['rpcod2ems_debug'] = $this->request->post['rpcod2ems_debug'];
		} else {
			$this->data['rpcod2ems_debug'] = $this->config->get('rpcod2ems_debug');
		}
		
		$this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();
		
		if( !isset($results['RUB']) && !isset($results['RUR']) )
		{
			$this->error[] = $this->language->get('err_not_rub');
		}

 		if (!empty($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

 		if (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		}
		
		if( !empty($this->session->data['success']) )
		{
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		else
		{
			$this->data['success'] = '';
		}
		
		$this->data['shipping_mod'] = $this->url->link('shipping/russianpost', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['action_shipping'] = $this->url->link('shipping/russianpost', 'token=' . $this->session->data['token'], 'SSL');

		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/rpcod2ems', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('payment/rpcod2ems', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	
		
		if (isset($this->request->post['rpcod2ems_order_status'])) {
			$this->data['rpcod2ems_order_status'] = $this->request->post['rpcod2ems_order_status'];
		} else {
			$this->data['rpcod2ems_order_status'] = $this->config->get('rpcod2ems_order_status'); 
		} 
		
		if (isset($this->request->post['rpcod2ems_mode'])) {
			$this->data['rpcod2ems_mode'] = $this->request->post['rpcod2ems_mode'];
		} elseif( $this->config->has('rpcod2ems_mode') ) {
			$this->data['rpcod2ems_mode'] = $this->config->get('rpcod2ems_mode');
		} else {
			$this->data['rpcod2ems_mode'] = 'api';
		}
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['rpcod2ems_geo_zone_id'])) {
			$this->data['rpcod2ems_geo_zone_id'] = $this->request->post['rpcod2ems_geo_zone_id'];
		} else {
			$this->data['rpcod2ems_geo_zone_id'] = $this->config->get('rpcod2ems_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');						
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['rpcod2ems_status'])) {
			$this->data['rpcod2ems_status'] = $this->request->post['rpcod2ems_status'];
		} else {
			$this->data['rpcod2ems_status'] = $this->config->get('rpcod2ems_status');
		}
		
		if (isset($this->request->post['rpcod2ems_sort_order'])) {
			$this->data['rpcod2ems_sort_order'] = $this->request->post['rpcod2ems_sort_order'];
		} else {
			$this->data['rpcod2ems_sort_order'] = $this->config->get('rpcod2ems_sort_order');
		}
		
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/rpcod2ems.tpl', $this->data));
		
	}
	
	
	private function validate() 
	{
		if (!$this->user->hasPermission('modify', 'payment/rpcod2ems')) {
			$this->error[] = $this->language->get('error_permission');
		}
		
				
		$this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();
		
		$err = 0;
			
		if( !isset($results['RUB']) && !isset($results['RUR']) )
		{
			$err = 1;
		}
		
		if (!$this->error && !$err) 
		{
			if( isset( $this->request->post['rpcod2ems_title'] ) )
			{
				$this->request->post['rpcod2ems_title'] = serialize($this->request->post['rpcod2ems_title']);
			}
			
			if( isset( $this->request->post['rpcod2ems_total_title'] ) )
			{
				$this->request->post['rpcod2ems_total_title'] = serialize($this->request->post['rpcod2ems_total_title']);
			}
			
			if( !isset($this->request->post['rpcod2ems_stop_for_avia']) )
			$this->request->post['rpcod2ems_stop_for_avia'] = 0;
			
			return true;
		} else {
			return false;
		}	
	}
	
	private function custom_unserialize($s)
	{
		if(
			stristr($s, '{' ) != false &&
			stristr($s, '}' ) != false &&
			stristr($s, ';' ) != false &&
			stristr($s, ':' ) != false
		){
			return unserialize($s);
		}else{
			return $s;
		}

	}

}
?>