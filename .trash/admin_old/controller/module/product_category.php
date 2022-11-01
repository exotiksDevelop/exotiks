<?php
class ControllerModuleProductCategory extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/product_category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('product_category', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_category'] = $this->language->get('entry_category');

		$data['help_product'] = $this->language->get('help_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['image'])) {
			$data['error_image'] = $this->error['image'];
		} else {
			$data['error_image'] = array();
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
			'href' => $this->url->link('module/product_category', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/product_category', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		/*if (isset($this->request->post['product_category_product'])) {
			$data['product_category_product'] = $this->request->post['product_category_product'];
		} else {
			$data['product_category_product'] = $this->config->get('product_category_product');
		}*/

		//var_dump($data['product_category_product']);die;

		$this->load->model('catalog/category');

		/*if (isset($this->request->post['product_category_product'])) {
			$categories = explode(',', $this->request->post['product_category_product']);
		} else {
			$categories = explode(',', $this->config->get('product_category_product'));
		}

		$data['categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'       => $category_info['name']
				);
			}
		}*/
		
		//var_dump($data['categories']);die;
		
		
		if (isset($this->request->post['product_category_status'])) {
			$data['product_category_status'] = $this->request->post['product_category_status'];
		} else {
			$data['product_category_status'] = $this->config->get('product_category_status');
		}
				
		if (isset($this->request->post['product_category_module'])) {
			$modules = $this->request->post['product_category_module'];
		} elseif ($this->config->has('product_category_module')) {
			$modules = $this->config->get('product_category_module');
		} else {
			$modules = array();
		}
		
		$data['product_category_modules'] = array();
		
		foreach ($modules as $key => $module) {

			$category_info = $this->model_catalog_category->getCategory($module['category']);
			$label = $category_info['name'];

			$data['product_category_modules'][] = array(
				'key'    => $key,
				'label'  => $label,
				'category'  => $module['category'],
				'name'  => $module['name'],
				'limit'  => $module['limit'],
				'width'  => $module['width'],
				'height' => $module['height']
			);
		}
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/product_category.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/product_category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['product_category_module'])) {
			foreach ($this->request->post['product_category_module'] as $key => $value) {
				if (!$value['width'] || !$value['height']) {
					$this->error['image'][$key] = $this->language->get('error_image');
				}
			}
		}

		return !$this->error;
	}
}
