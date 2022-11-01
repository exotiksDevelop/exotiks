<?php 
class ControllerTotalCodCdekTotal extends Controller { 
	private $error = array(); 
	 
	public function index() 
	{	
		$this->load->model('tool/cdektool');

		$this->checkInstall();

		if(!$this->model_tool_cdektool->check()) 
		{ 
			$this->response->redirect($this->url->link('tool/cdektool', 'token=' . $this->session->data['token'], 'SSL')); 
		}
		
		$this->load->language('total/cod_cdek_total');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			$this->model_setting_setting->editSetting('cod_cdek_total', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_title'] = $this->language->get('entry_title');
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/cod_cdek_total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('total/cod_cdek_total', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['cod_cdek_total_title'])) {
			$data['cod_cdek_total_title'] = $this->request->post['cod_cdek_total_title'];
		} else {
			$data['cod_cdek_total_title'] = $this->config->get('cod_cdek_total_title');
		}

		if (isset($this->request->post['cod_cdek_total_sort_order'])) {
			$data['cod_cdek_total_sort_order'] = $this->request->post['cod_cdek_total_sort_order'];
		} else {
			$data['cod_cdek_total_sort_order'] = $this->config->get('cod_cdek_total_sort_order');
		}
		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('total/cod_cdek_total.tpl', $data));
	}

	public function checkInstall() {
		$this->model_tool_cdektool->checkInstalled('payment', 'cod_cdek');
	}

	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'total/cod_cdek_total')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
}
?>