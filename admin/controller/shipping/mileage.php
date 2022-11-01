<?php
class ControllerShippingMileage extends Controller { 
	private $error = array();
	
	public function index() {  
		$this->load->language('shipping/mileage');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mileage', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_none'] = $this->language->get('text_none');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		
		$data['entry_mileage_city'] = $this->language->get('entry_mileage_city');
		$data['entry_mileage_store'] = $this->language->get('entry_mileage_store');
		
		$data['entry_mileage_city_rate'] = $this->language->get('entry_mileage_city_rate');
		$data['entry_mileage_oblast_rate'] = $this->language->get('entry_mileage_oblast_rate');
		$data['entry_max_distance'] = $this->language->get('entry_max_distance');
		$data['entry_hide_map'] = $this->language->get('entry_hide_map');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
      	
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
      		
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/mileage', 'token=' . $this->session->data['token'], 'SSL')
      		
   		);
		
		$data['action'] = $this->url->link('shipping/mileage', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'); 

		$this->load->model('localisation/geo_zone');

		if (isset($this->request->post['mileage_store'])) {
			$data['mileage_store'] = $this->request->post['mileage_store'];
		} else {
			$data['mileage_store'] = $this->config->get('mileage_store');
		}

		if (isset($this->request->post['mileage_city'])) {
			$data['mileage_city'] = $this->request->post['mileage_city'];
		} else {
			$data['mileage_city'] = $this->config->get('mileage_city');
		}
		
		if (isset($this->request->post['mileage_city_rate'])) {
			$data['mileage_city_rate'] = $this->request->post['mileage_city_rate'];
		} else {
			$data['mileage_city_rate'] = $this->config->get('mileage_city_rate');
		}
		
		if (isset($this->request->post['mileage_oblast_rate'])) {
			$data['mileage_oblast_rate'] = $this->request->post['mileage_oblast_rate'];
		} else {
			$data['mileage_oblast_rate'] = $this->config->get('mileage_oblast_rate');
		}
		
		if (isset($this->request->post['mileage_max_distance'])) {
			$data['mileage_max_distance'] = $this->request->post['mileage_max_distance'];
		} else {
			$data['mileage_max_distance'] = $this->config->get('mileage_max_distance');
		}
		
		if (isset($this->request->post['mileage_hide_map'])) {
			$data['mileage_hide_map'] = $this->request->post['mileage_hide_map'];
		} else {
			$data['mileage_hide_map'] = $this->config->get('mileage_hide_map');
		}
		
		if (isset($this->request->post['mileage_status'])) {
			$data['mileage_status'] = $this->request->post['mileage_status'];
		} else {
			$data['mileage_status'] = $this->config->get('mileage_status');
		}		
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['mileage_geo_zone_id'])) {
			$data['mileage_geo_zone_id'] = $this->request->post['mileage_geo_zone_id'];
		} else {
			$data['mileage_geo_zone_id'] = $this->config->get('mileage_geo_zone_id');
		}
		
		if (isset($this->request->post['mileage_tax_class_id'])) {
			$data['mileage_tax_class_id'] = $this->request->post['mileage_tax_class_id'];
		} else {
			$data['mileage_tax_class_id'] = $this->config->get('mileage_tax_class_id');
		}
		
		if (isset($this->request->post['mileage_status'])) {
			$data['mileage_status'] = $this->request->post['mileage_status'];
		} else {
			$data['mileage_status'] = $this->config->get('mileage_status');
		}
		
		if (isset($this->request->post['mileage_sort_order'])) {
			$data['mileage_sort_order'] = $this->request->post['mileage_sort_order'];
		} else {
			$data['mileage_sort_order'] = $this->config->get('mileage_sort_order');
		}	
		
		$this->load->model('localisation/tax_class');
				
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		//$this->template = 'shipping/mileage.tpl';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('shipping/mileage.tpl', $data));
	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/mileage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
}
?>
