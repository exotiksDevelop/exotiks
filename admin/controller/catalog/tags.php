<?php
class ControllerCatalogTags extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/tags');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tags');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/tags');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tags');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_tags->addTag($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/tags', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/tags');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tags');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_tags->editTag($this->request->get['tag_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/tags', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/tags');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tags');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $tag_id) {
				$this->model_catalog_tags->deleteTags($tag_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/tags', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}


	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/tags', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/tags/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/tags/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['settings'] = $this->url->link('catalog/tags/settings', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['group'] = $this->url->link('catalog/tags/group', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['tags'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$tags_total = $this->model_catalog_tags->getTotalTags();

		$results = $this->model_catalog_tags->getTags($filter_data);

		foreach ($results as $result) {
			$data['tags'][] = array(
				'tag_id' => $result['tag_id'],
				'name'        => $result['name'],
				'count'	=> $result['count'],
				'edit'        => $this->url->link('catalog/tags/edit', 'token=' . $this->session->data['token'] . '&tag_id=' . $result['tag_id'] . $url, 'SSL'),
				'delete'      => $this->url->link('catalog/tags/delete', 'token=' . $this->session->data['token'] . '&tag_id=' . $result['tag_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_count'] = $this->language->get('column_count');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_settings'] = $this->language->get('button_settings');
		$data['button_group'] = $this->language->get('button_group');

		if (isset($this->error['warning'])) {
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/tags', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $tags_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/tags', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($tags_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($tags_total - $this->config->get('config_limit_admin'))) ? $tags_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $tags_total, ceil($tags_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/tags_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['tag_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_names'] = $this->language->get('entry_names');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_description_top'] = $this->language->get('entry_description_top');
		$data['entry_description_bottom'] = $this->language->get('entry_description_bottom');
		$data['entry_h1'] = $this->language->get('entry_h1');
		$data['entry_category'] = $this->language->get('entry_category');

		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_description_top'] = $this->language->get('help_description_top');
		$data['help_description_bottom'] = $this->language->get('help_description_bottom');
		$data['help_h1'] = $this->language->get('help_h1');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_names'] = $this->language->get('help_names');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/tags', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['tag_id'])) {
			$data['action'] = $this->url->link('catalog/tags/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/tags/edit', 'token=' . $this->session->data['token'] . '&tag_id=' . $this->request->get['tag_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/tags', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['tag_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tag_info = $this->model_catalog_tags->getTag($this->request->get['tag_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['tag_description'])) {
			$data['tag_description'] = $this->request->post['tag_description'];
		} elseif (isset($this->request->get['tag_id'])) {
			$data['tag_description'] = $this->model_catalog_tags->getTagDescriptions($this->request->get['tag_id']);
		} else {
			$data['tag_description'] = array();
		}


		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['tag_store'])) {
			$data['tag_store'] = $this->request->post['tag_store'];
		} elseif (isset($this->request->get['tag_id'])) {
			$data['tag_store'] = $this->model_catalog_tags->getTagStores($this->request->get['tag_id']);
		} else {
			$data['tag_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($tag_info)) {
			$data['keyword'] = $tag_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($tag_info)) {
			$data['status'] = $tag_info['status'];
		} else {
			$data['status'] = true;
		}

		/* if (isset($this->request->post['category_id'])) {
			$data['category_id'] = $this->request->post['category_id'];
		} elseif (!empty($tag_info)) {
			$data['category_id'] = $tag_info['category_id'];
		} else {
			$data['category_id'] = 0;
		} */

		/* $this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(); */
		
		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($this->request->get['tag_id'])) {
			$categories = $this->model_catalog_tags->getTagCategories($this->request->get['tag_id']);
		} else {
			$categories = array();
		}

		$data['product_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/tags_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/tags')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		foreach ($this->request->post['tag_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['tag_id']) && $url_alias_info['query'] != 'tag_id=' . $this->request->get['tag_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['tag_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
		}
		else
		{
			$this->error['keyword'] = sprintf($this->language->get('error_empty_keyword'));
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/tags')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/tags');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_tags->getTags($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'tag_id' => $result['tag_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function settings(){
		$this->load->language('catalog/tags');

		$data['heading_title'] = $this->language->get('heading_title')." - ".$this->language->get('button_settings');
		$this->document->setTitle($data['heading_title']);
		$data['action'] = $this->url->link('catalog/tags/save_settins', 'token=' . $this->session->data['token'], 'SSL');

		$data['text_form'] = $this->language->get('button_settings');

		$data['entry_etopd'] = $this->language->get('entry_etopd');
		$data['entry_ebottomd'] = $this->language->get('entry_ebottomd');
		$data['entry_only'] = $this->language->get('entry_only');
		$data['entry_ajax'] = $this->language->get('entry_ajax');
		$data['entry_scategory'] = $this->language->get('entry_scategory');
		$data['entry_count'] = $this->language->get('entry_count');
		$data['entry_related'] = $this->language->get('entry_related');

		$data['help_etopd'] = $this->language->get('help_etopd');
		$data['help_ebottomd'] = $this->language->get('help_ebottomd');
		$data['help_only'] = $this->language->get('help_only');
		$data['help_ajax'] = $this->language->get('help_ajax');
		$data['help_scategory'] = $this->language->get('help_scategory');
		$data['help_count'] = $this->language->get('help_count');
		$data['help_related'] = $this->language->get('help_related');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['tab_general'] = $this->language->get('tab_general');

		$data['setting_etopd'] = $this->config->get('newtags_etopd');
		$data['setting_ebottomd'] = $this->config->get('newtags_ebottomd');
		$data['setting_only'] = $this->config->get('newtags_only');
		$data['setting_ajax'] = $this->config->get('newtags_ajax');
		$data['setting_scategory'] = $this->config->get('newtags_category');
		$data['setting_count'] = $this->config->get('newtags_count');
		$data['setting_related'] = $this->config->get('newtags_related');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/tags', 'token=' . $this->session->data['token'] , 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/tags/settings', 'token=' . $this->session->data['token'] , 'SSL')
		);

		$data['cancel'] = $this->url->link('catalog/tags', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/tags_settings.tpl', $data));
	}

	public function save_settins() {
		$this->load->language('catalog/tags');

		$data['heading_title'] = $this->language->get('heading_title')." - ".$this->language->get('button_settings');

		$this->load->model('catalog/tags');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->config->set('newtags_etopd',$this->request->post['setting_etopd']);
			$this->config->set('newtags_ebottomd',$this->request->post['setting_ebottomd']);
			$this->config->set('newtags_only',$this->request->post['setting_only']);
			$this->config->set('newtags_ajax',$this->request->post['setting_ajax']);
			$this->config->set('newtags_category',$this->request->post['setting_scategory']);
			$this->config->set('newtags_count',$this->request->post['setting_count']);
			$this->config->set('newtags_related',$this->request->post['setting_related']);
			$this->model_catalog_tags->setSettings($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success_settings');

			$this->response->redirect($this->url->link('catalog/tags', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$this->response->redirect($this->url->link('catalog/settings', 'token=' . $this->session->data['token'] , 'SSL'));
	}

	public function group(){
		$this->load->language('catalog/tags');
		$this->load->model('catalog/tags');
		$data['heading_title'] = $this->language->get('button_group');

		$this->document->setTitle($this->language->get('button_group'));

		$data['action'] = $this->url->link('catalog/tags/group', 'token=' . $this->session->data['token'] , 'SSL');

		$data['button_run'] = $this->language->get('button_run');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_add_option'] = $this->language->get('text_add_option');
		$data['text_add_attribute'] = $this->language->get('text_add_attribute');
		$data['text_option_name'] = $this->language->get('text_option_name');
		$data['text_option_value'] = $this->language->get('text_option_value');
		$data['text_attribute_name'] = $this->language->get('text_attribute_name');
		$data['text_attribute_value'] = $this->language->get('text_attribute_value');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_tag'] = $this->language->get('text_tag');
		$data['text_run'] = $this->language->get('text_run');

		$data['text_form'] = $this->language->get('button_group');

		if (isset($this->request->post['tag_id'])){
			if ($this->request->post['tag_id'] != ''){
				$count = $this->model_catalog_tags->updateTags($this->request->post);
				$this->session->data['success'] = $count." ".$this->language->get('text_run_success');
			}
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/tags', 'token=' . $this->session->data['token'] , 'SSL')
		);

		$data['cancel'] = $this->url->link('catalog/tags', 'token=' . $this->session->data['token'] , 'SSL');

		$this->load->model('catalog/option');
		$data['options'] = $this->model_catalog_option->getOptions();

		$this->load->model('catalog/attribute');
		$data['attributes'] = $this->model_catalog_attribute->getAttributes();

		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(array('sort' => 'name'));

		$data['tags'] = $this->model_catalog_tags->getTags();

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/tags_group.tpl', $data));
	}
}