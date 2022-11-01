<?php
require_once('classbase.php');
class ControllerApiClassoption extends ControllerApiClassbase {

	public function index() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	
		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/option');

			$json['success'] = 'ok';

			$params = [
				'limit' => 100,
			];
			$params['start'] = ((isset($this->request->get['page'])) ? $this->request->get['page']:0) * $params['limit'];
			$params['sort'] = 'p.sort_order';
			$json['items'] = [];
			foreach ($this->model_admin_catalog_option->getOptions($params) as $value) {
				$json['items'][] = $value;
			}
		}

		$this->JSON = $json;
	}

	public function get() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	
		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/option');

			$option_id = $this->request->get['option_id'];

			$json['success'] = 'ok';
			$json['items'] = $this->model_admin_catalog_option->getOption($option_id);
			if (!empty($json['items'])) {
				$json['items']['option_value'] = $this->model_admin_catalog_option->getOptionValues($option_id);
			} else {
				$json = ['error' => 'not found'];
			}
		}

		$this->JSON = $json;
	}

	public function add() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	
		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/option');

			$data = $this->request->post;

			
			if (empty($data)) {
				$json = ['error' => 'empty_post'];
			} else if (!isset($data['type'])) {
				$json = ['error' => 'null_type'];
			} else {
				$json['success'] = 'ok';

				$data = $this->formatOptionObject($data);

				$json['items']['id'] = $this->model_admin_catalog_option->addOption($data);
				// $json = $data;
			}
		}
		
		$this->JSON = $json;
	}

	private function formatOptionObject($obj) {

		if (isset($obj['name'])) {
			$this->langProp($obj['option_description'], [
				'name' => $obj['name'],
			]);
			unset($obj['name']);
		}
		if (isset($obj['option_value'])) foreach ($obj['option_value'] as &$option_value) {
			if (isset($option_value['name'])) {
				$this->langProp($option_value['option_value_description'], [
					'name' => $option_value['name'],
				]);
				unset($option_value['name']);
			}
		}

		return $obj;
	}

	public function upd() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	
		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/option');

			$data = $this->request->post;
			$option_id = $this->request->get['option_id'];
			// unset($data['type']);
			
			if (empty($data)) {
				$json = ['error' => 'empty_post'];
			} else if (empty($option_id)) {
				$json = ['error' => 'empty_option_id'];
			} else {
				$json['success'] = 'ok';

				$data = $this->formatOptionObject($data);

				$this->model_admin_catalog_option->editOption($option_id, $data);
				$json['items']['id'] = $option_id;
				// $json = $data;
			}
		}
		
		$this->JSON = $json;
	}

	public function del() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	
		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/option');

			$option_id = $this->request->get['option_id'];

			if (empty($option_id)) {
				$json = ['error' => 'empty_option_id'];
			} else {
				$this->model_admin_catalog_option->deleteOption($option_id);
				$json['success'] = 'ok';
			}
		}
		
		$this->JSON = $json;
	}
}