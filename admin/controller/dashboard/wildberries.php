<?php
class ControllerDashboardWildberries extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('dashboard/wildberries');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_wildberries', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_width'] = $this->language->get('entry_width');
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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('dashboard/wildberries', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('dashboard/wildberries', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_wildberries_width'])) {
			$data['dashboard_wildberries_width'] = $this->request->post['dashboard_wildberries_width'];
		} else {
			$data['dashboard_wildberries_width'] = $this->config->get('dashboard_wildberries_width');
		}
	
		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_wildberries_status'])) {
			$data['dashboard_wildberries_status'] = $this->request->post['dashboard_wildberries_status'];
		} else {
			$data['dashboard_wildberries_status'] = $this->config->get('dashboard_wildberries_status');
		}

		if (isset($this->request->post['dashboard_wildberries_sort_order'])) {
			$data['dashboard_wildberries_sort_order'] = $this->request->post['dashboard_wildberries_sort_order'];
		} else {
			$data['dashboard_wildberries_sort_order'] = $this->config->get('dashboard_wildberries_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('dashboard/wildberries_form.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/analytics/google_analytics')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function dashboard() {
		$this->load->language('dashboard/wildberries');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		// Total Product
		$this->load->model('module/wildberries');

		$data['total'] = $this->model_module_wildberries->getTotalWbProduct();

		$data['wildberries'] = $this->url->link('catalog/wildberries', 'token=' . $this->session->data['token'], true);

		return $this->load->view('dashboard/wildberries_info.tpl', $data);
	}
}