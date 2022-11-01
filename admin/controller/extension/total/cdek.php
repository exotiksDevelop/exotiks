<?php 
class ControllerExtensionTotalCdek extends Controller {
	private $error = array(); 
	 
	public function index() 
	{	
		$this->load->model('tool/cdektool');
		$this->checkInstall();

		if(!$this->model_tool_cdektool->check()) 
		{ 
			$this->response->redirect($this->url->link('tool/cdektool', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		
		$this->load->language('extension/total/cdek');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			$this->model_setting_setting->editSetting('total_cdek', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=total', 'SSL'));
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
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true)
		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/total/cdek', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('extension/total/cdek', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true);
		
		if (isset($this->request->post['total_cdek_title'])) {
			$data['total_cdek_title'] = $this->request->post['total_cdek_title'];
		} else {
			$data['total_cdek_title'] = $this->config->get('total_cdek_title');
		}

		if (isset($this->request->post['total_cdek_sort_order'])) {
			$data['total_cdek_sort_order'] = $this->request->post['total_cdek_sort_order'];
		} else {
			$data['total_cdek_sort_order'] = $this->config->get('total_cdek_sort_order');
		}
		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/total/cdek', $data));
	}

	public function checkInstall() {
		$this->model_tool_cdektool->checkInstalled('total', 'cdek');
	}

	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'extension/total/cdek')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
}
?>