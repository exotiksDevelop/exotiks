<?php
class ControllerModuleOCABackToTop extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/oca_back_to_top');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('oca_back_to_top', $this->request->post);

			$output  = '<?php' . "\n";
			$output .= '$background = ' . "'" .  $this->request->post['oca_back_to_top_background'] . "'" . ";\n";
			$output .= '$button_width = ' . "'" .  $this->request->post['oca_back_to_top_width'] . "'" . ";\n";
			$output .= '$button_height = ' . "'" .  $this->request->post['oca_back_to_top_height'] . "'" . ";\n";
			$output .= '$margin_right = ' . "'" .  $this->request->post['oca_back_to_top_margin_right'] . "'" . ";\n";
			$output .= '$margin_bottom = ' . "'" .  $this->request->post['oca_back_to_top_margin_bottom'] . "'" . ";\n";
			$output .= '$mobile_tablet = ' . "'" .  $this->request->post['oca_back_to_top_mobile_tablet'] . "'" . ";\n";
			$output .= '?>';

			$file = fopen(DIR_CATALOG . 'view/javascript/oca_back_to_top/oca_back_to_top_var.php', 'w');

			fwrite($file, $output);

			fclose($file);	

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_background'] = $this->language->get('entry_background');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_margin_right'] = $this->language->get('entry_margin_right');
		$data['entry_margin_bottom'] = $this->language->get('entry_margin_bottom');
		$data['entry_mobile_tablet'] = $this->language->get('entry_mobile_tablet');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['background'])) {
			$data['error_background'] = $this->error['background'];
		} else {
			$data['error_background'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}

		if (isset($this->error['margin_right'])) {
			$data['error_margin_right'] = $this->error['margin_right'];
		} else {
			$data['error_margin_right'] = '';
		}

		if (isset($this->error['margin_bottom'])) {
			$data['error_margin_bottom'] = $this->error['margin_bottom'];
		} else {
			$data['error_margin_bottom'] = '';
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

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/oca_back_to_top', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/oca_back_to_top', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['oca_back_to_top_background'])) {
			$data['oca_back_to_top_background'] = $this->request->post['oca_back_to_top_background'];
		} elseif ($this->config->get('oca_back_to_top_background')) {
			$data['oca_back_to_top_background'] = $this->config->get('oca_back_to_top_background');
		} else {
			$data['oca_back_to_top_background'] = 'e86256';
		}

		if (isset($this->request->post['oca_back_to_top_width'])) {
			$data['oca_back_to_top_width'] = $this->request->post['oca_back_to_top_width'];
		} elseif ($this->config->get('oca_back_to_top_width')) {
			$data['oca_back_to_top_width'] = $this->config->get('oca_back_to_top_width');
		} else {
			$data['oca_back_to_top_width'] = 60;
		}

		if (isset($this->request->post['oca_back_to_top_height'])) {
			$data['oca_back_to_top_height'] = $this->request->post['oca_back_to_top_height'];
		} elseif ($this->config->get('oca_back_to_top_height')) {
			$data['oca_back_to_top_height'] = $this->config->get('oca_back_to_top_height');
		} else {
			$data['oca_back_to_top_height'] = 60;
		}

		if (isset($this->request->post['oca_back_to_top_margin_right'])) {
			$data['oca_back_to_top_margin_right'] = $this->request->post['oca_back_to_top_margin_right'];
		} elseif ($this->config->get('oca_back_to_top_margin_right')) {
			$data['oca_back_to_top_margin_right'] = $this->config->get('oca_back_to_top_margin_right');
		} else {
			$data['oca_back_to_top_margin_right'] = 20;
		}

		if (isset($this->request->post['oca_back_to_top_margin_bottom'])) {
			$data['oca_back_to_top_margin_bottom'] = $this->request->post['oca_back_to_top_margin_bottom'];
		} elseif ($this->config->get('oca_back_to_top_margin_bottom')) {
			$data['oca_back_to_top_margin_bottom'] = $this->config->get('oca_back_to_top_margin_bottom');
		} else {
			$data['oca_back_to_top_margin_bottom'] = 20;
		}

		if (isset($this->request->post['oca_back_to_top_mobile_tablet'])) {
			$data['oca_back_to_top_mobile_tablet'] = $this->request->post['oca_back_to_top_mobile_tablet'];
		} else {
			$data['oca_back_to_top_mobile_tablet'] = $this->config->get('oca_back_to_top_mobile_tablet');
		}

		if (isset($this->request->post['oca_back_to_top_status'])) {
			$data['oca_back_to_top_status'] = $this->request->post['oca_back_to_top_status'];
		} else {
			$data['oca_back_to_top_status'] = $this->config->get('oca_back_to_top_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/oca_back_to_top.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/oca_back_to_top')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['oca_back_to_top_width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['oca_back_to_top_height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		if (!$this->request->post['oca_back_to_top_margin_right']) {
			$this->error['margin_right'] = $this->language->get('error_margin_right');
		}

		if (!$this->request->post['oca_back_to_top_margin_bottom']) {
			$this->error['margin_bottom'] = $this->language->get('error_margin_bottom');
		}

		if (!$this->request->post['oca_back_to_top_background']) {
			$this->error['background'] = $this->language->get('error_background');
		}

		return !$this->error;
	}
}