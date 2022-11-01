<?php
class ControllerModuleCustomPopup extends Controller {
	private $error = array();
    private $version = '2.0';

	public function index() {
		$this->load->language('module/custom_popup');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('custom_popup', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}
						
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title') . ' v' . $this->version;

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_css'] = $this->language->get('entry_css');
		$data['entry_display_times'] = $this->language->get('entry_display_times');
		$data['entry_seconds_to_close'] = $this->language->get('entry_seconds_to_close');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_css'] = $this->language->get('help_css');
        $data['help_display_times'] = $this->language->get('help_display_times');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
        
        if (isset($this->error['update'])) {
			$data['update'] = $this->error['update'];
		} else {
			$data['update'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/custom_popup', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/custom_popup', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);			
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/custom_popup', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/custom_popup', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}
		
		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
        
        if (isset($this->request->post['module_description'])) {
			$data['module_description'] = $this->request->post['module_description'];
		} elseif (!empty($module_info)) {
			$data['module_description'] = $module_info['module_description'];
		} else {
			$data['module_description'] = '';
		}
        
        if (isset($this->request->post['css'])) {
			$data['css'] = $this->request->post['css'];
		} elseif (!empty($module_info)) {
			$data['css'] = $module_info['css'];
		} else {
			$data['css'] = '';
		}
        
        if (isset($this->request->post['display_times'])) {
			$data['display_times'] = $this->request->post['display_times'];
		} elseif (!empty($module_info)) {
			$data['display_times'] = $module_info['display_times'];
		} else {
			$data['display_times'] = '';
		}
        
        if (isset($this->request->post['seconds_to_close'])) {
			$data['seconds_to_close'] = $this->request->post['seconds_to_close'];
		} elseif (!empty($module_info)) {
			$data['seconds_to_close'] = $module_info['seconds_to_close'];
		} else {
			$data['seconds_to_close'] = '';
		}
        
        if (isset($this->request->post['uid'])) {
			$data['uid'] = $this->request->post['uid'];
		} elseif (!empty($module_info)) {
			$data['uid'] = $module_info['uid'];
		} else {
			$data['uid'] = date('YmdHis');
		}
        
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();		
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/custom_popup.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/custom_popup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		return !$this->error;
	}
    
}