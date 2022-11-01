<?php
require_once('classbase.php');
class ControllerApiClasscategory extends ControllerApiClassbase {

	public function index() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->loadAdminModel('catalog/category');

			$json['success'] = 'ok';

			$return = [];
			foreach ($this->model_admin_catalog_category->getCategories() as $value) {
				$return[] = [
					'id' => $value['category_id'],
					'parent_id' => $value['parent_id'],
					'name' => $this->model_admin_catalog_category->getCategory($value['category_id'])['name'],
				];
			}
			$json['items'] = $return;

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
			$this->loadAdminModel('catalog/category');

			if (!$this->request->get['cat_id']) {
				$json['error'] = 'fail';
			} else if (
				empty($this->model_admin_catalog_category->getCategory($this->request->get['cat_id']))
			) {
				$json['error'] = 'not found';
			} else {
				$json['success'] = 'ok';
				$json['items'] = $this->model_admin_catalog_category->getCategory($this->request->get['cat_id']);
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
			if ($this->request->post['name']) {

				$this->loadAdminModel('catalog/category');
				$json['success'] = 'ok';

				$data = $this->request->post;
				$data['category_store'][] = (int)$this->config->get('config_store_id');
				if ($data['parent_id'] == 0) {
					$data['top'] = 1;
					$data['column'] = 1;
				}
				
				$this->langProps($data, 'category_description', array_merge(
					$this->filterKeys($data, ['name'], true)
				));
				
				$json['items'] = $this->model_admin_catalog_category->addCategory($data);
			} else {
				
				$json['error'] = 'fail';
			}

		}

		$this->JSON = $json;
	}

	public  function edit() {
		$this->disableError();
		$this->load->language('api/businessruapi');

		$json = [];	

		if (!$this->checkToken()) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if ($this->request->get['cat_id']) {

				$this->loadAdminModel('catalog/category');
				$json['success'] = 'ok';

				$data = $this->request->post;
				$data['category_store'][] = (int)$this->config->get('config_store_id');
				if (empty($data['parent_id'])) {
					$data['top'] = 1;
					$data['column'] = 1;
				}

				$open_data = $this->model_admin_catalog_category->getCategory($this->request->get['cat_id']);
				$data = array_merge($open_data, $data);

				$this->langProps($data, 'category_description', array_merge(
					$this->filterKeys($data, ['name','description','meta_title','meta_h1','meta_description','meta_keyword'], true)
				));
				
				$json['items']['id'] = $this->model_admin_catalog_category->editCategory($this->request->get['cat_id'], $data);
			} else {
				
				$json['error'] = 'fail';
			}

		}

		$this->JSON = $json;
	}
}