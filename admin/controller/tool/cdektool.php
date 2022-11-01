<?php
class ControllerToolCdektool extends Controller {

	public function index() {
		$this->load->model('tool/cdektool');

		if(isset($this->request->post['cdekLicense_user']) && isset($this->request->post['cdekLicense_password']))
		{
			$this->load->model('setting/setting');
			$user = $this->request->post['cdekLicense_user'];
			$password = $this->request->post['cdekLicense_password'];
			$this->model_setting_setting->editSetting('cdekLicense', array('cdekLicense_user'=>$user, 'cdekLicense_password'=>$password));
		}		

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('Лицензия СДЭК'),
			'href' => $this->url->link('tool/cdektool', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->document->setTitle('Лицензия СДЭК');
		$data['heading_title'] = 'Лицензия СДЭК';
		$data['text_list'] = 'Параметры лицензии СДЭК';

		$license = $this->model_tool_cdektool->chechLicense();

		if($license['status'])
			$data['license_alert'] = 'success';
		else
			$data['license_alert'] = 'danger';

		$data['license_status'] = $license['message'];

		$data['action'] = $this->url->link('tool/cdektool', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/cdektool.tpl', $data));
	}

}