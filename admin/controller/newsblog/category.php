<?php
class ControllerNewsBlogCategory extends Controller {
	private $error = array();
	private $category_id = 0;
	private $path = array();
	private $version_newsblog = 20161030;

	public function index() {
		if (empty($this->session->data['check_version_newsblog'])) $this->session->data['check_version_newsblog']=@file_get_contents('http://nedorogoi-internet-magazin.ru/check_version.php?now='.$this->version_newsblog.'&version='.VERSION.'&site='.$_SERVER['HTTP_HOST']);

		$this->language->load('newsblog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('newsblog/category');
		$this->load->model('newsblog/article');

		$this->getList();
	}

	public function add() {
		$this->language->load('newsblog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('newsblog/category');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//serialize settings for articles
			$settings_array['date_format']=$this->request->post['date_format'];
			$settings_array['image_size_width']=$this->request->post['image_size_width'];
			$settings_array['image_size_height']=$this->request->post['image_size_height'];
			$settings_array['images_size_articles_big_width']=$this->request->post['images_size_articles_big_width'];
			$settings_array['images_size_articles_big_height']=$this->request->post['images_size_articles_big_height'];
			$settings_array['images_size_articles_small_width']=$this->request->post['images_size_articles_small_width'];
			$settings_array['images_size_articles_small_height']=$this->request->post['images_size_articles_small_height'];
			$settings_array['images_size_width']=$this->request->post['images_size_width'];
			$settings_array['images_size_height']=$this->request->post['images_size_height'];
			$settings_array['limit']=$this->request->post['limit'];
			$settings_array['show_in_sitemap']=(isset($this->request->post['show_in_sitemap']) ? (int)$this->request->post['show_in_sitemap'] : 0);
			$settings_array['show_in_sitemap_articles']=(isset($this->request->post['show_in_sitemap_articles']) ? (int)$this->request->post['show_in_sitemap_articles'] : 0);
			$settings_array['show_in_top']=(isset($this->request->post['show_in_top']) ? (int)$this->request->post['show_in_top'] : 0);
			$settings_array['show_in_top_articles']=(isset($this->request->post['show_in_top_articles']) ? (int)$this->request->post['show_in_top_articles'] : 0);
			$settings_array['show_preview']=(isset($this->request->post['show_preview']) ? (int)$this->request->post['show_preview'] : 0);
			$settings_array['sort_by']=$this->request->post['sort_by'];
			$settings_array['sort_direction']=$this->request->post['sort_direction'];
			$settings_array['template_article']=$this->request->post['template_article'];
			$settings_array['template_category']=$this->request->post['template_category'];
			$settings=serialize($settings_array);

			$this->model_newsblog_category->addCategory($this->request->post, $settings);

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

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			$this->response->redirect($this->url->link('newsblog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->language->load('newsblog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('newsblog/category');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//serialize settings for articles
			$settings_array['date_format']=$this->request->post['date_format'];
			$settings_array['image_size_width']=$this->request->post['image_size_width'];
			$settings_array['image_size_height']=$this->request->post['image_size_height'];
			$settings_array['images_size_articles_big_width']=$this->request->post['images_size_articles_big_width'];
			$settings_array['images_size_articles_big_height']=$this->request->post['images_size_articles_big_height'];
			$settings_array['images_size_articles_small_width']=$this->request->post['images_size_articles_small_width'];
			$settings_array['images_size_articles_small_height']=$this->request->post['images_size_articles_small_height'];
			$settings_array['images_size_width']=$this->request->post['images_size_width'];
			$settings_array['images_size_height']=$this->request->post['images_size_height'];
			$settings_array['limit']=$this->request->post['limit'];
			$settings_array['show_in_sitemap']=(isset($this->request->post['show_in_sitemap']) ? (int)$this->request->post['show_in_sitemap'] : 0);
			$settings_array['show_in_sitemap_articles']=(isset($this->request->post['show_in_sitemap_articles']) ? (int)$this->request->post['show_in_sitemap_articles'] : 0);
			$settings_array['show_in_top']=(isset($this->request->post['show_in_top']) ? (int)$this->request->post['show_in_top'] : 0);
			$settings_array['show_in_top_articles']=(isset($this->request->post['show_in_top_articles']) ? (int)$this->request->post['show_in_top_articles'] : 0);
			$settings_array['show_preview']=(isset($this->request->post['show_preview']) ? (int)$this->request->post['show_preview'] : 0);
			$settings_array['sort_by']=$this->request->post['sort_by'];
			$settings_array['sort_direction']=$this->request->post['sort_direction'];
			$settings_array['template_article']=$this->request->post['template_article'];
			$settings_array['template_category']=$this->request->post['template_category'];
			$settings=serialize($settings_array);

			$this->model_newsblog_category->editCategory($this->request->get['category_id'], $this->request->post, $settings);

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

			$this->response->redirect($this->url->link('newsblog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('newsblog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('newsblog/category');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_newsblog_category->deleteCategory($category_id);
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

			$this->response->redirect($this->url->link('newsblog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function repair() {
		$this->language->load('newsblog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('newsblog/category');

		if ($this->validateRepair()) {
			$this->model_newsblog_category->repairCategories();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('newsblog/category', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$url = '';

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
			$url .= '&sort=' . $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
			$url .= '&order=' . $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
			$url .= '&page=' . $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('newsblog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('newsblog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('newsblog/category/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['repair'] = $this->url->link('newsblog/category/repair', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['categories'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$category_total = $this->model_newsblog_category->getTotalCategories();

		$results = $this->model_newsblog_category->getCategories($filter_data);

		foreach ($results as $result) {
			$data['categories'][] = array(
				'category_id' 		=> $result['category_id'],
				'name'        		=> $result['name'],
				'count_elements' 	=> $this->model_newsblog_article->getTotalArticles(array('filter_category'=>$result['category_id'])),
				'sort_order'  		=> $result['sort_order'],
				'edit'        		=> $this->url->link('newsblog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, true),
				'delete'      		=> $this->url->link('newsblog/category/delete', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_count_elements'] = $this->language->get('column_count_elements');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_rebuild'] = $this->language->get('button_rebuild');

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

		$data['sort_name'] = $this->url->link('newsblog/category', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('newsblog/category', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('newsblog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('newsblog/category_list.tpl', $data));
	}

	protected function getForm() {
    //CKEditor
    if ($this->config->get('config_editor_default')) {
        $this->document->addScript('view/javascript/ckeditor/ckeditor.js');
        $this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
    }
	    $this->document->addScript('view/javascript/auto_translit.js');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_h1'] = $this->language->get('entry_meta_h1');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_parent'] = $this->language->get('entry_parent');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_image_size'] = $this->language->get('entry_image_size');
		$data['entry_show_preview'] = $this->language->get('entry_show_preview');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_sort_by'] = $this->language->get('entry_sort_by');
		$data['entry_show_in_top'] = $this->language->get('entry_show_in_top');
		$data['entry_show_in_top_articles'] = $this->language->get('entry_show_in_top_articles');
		$data['entry_show_in_sitemap'] = $this->language->get('entry_show_in_sitemap');
		$data['entry_show_in_sitemap_articles'] = $this->language->get('entry_show_in_sitemap_articles');
		$data['entry_template_category'] = $this->language->get('entry_template_category');
		$data['entry_template_article'] = $this->language->get('entry_template_article');
		$data['entry_images_size'] = $this->language->get('entry_images_size');
		$data['entry_images_size_articles_big'] = $this->language->get('entry_images_size_articles_big');
		$data['entry_images_size_articles_small'] = $this->language->get('entry_images_size_articles_small');
		$data['entry_date_format'] = $this->language->get('entry_date_format');
		$data['entry_layout'] = $this->language->get('entry_layout');

		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_limit'] = $this->language->get('help_limit');
		$data['help_template_category'] = $this->language->get('help_template_category');
		$data['help_template_article'] = $this->language->get('help_template_article');
		$data['help_date_format'] = $this->language->get('help_date_format');

		$data['placeholder_template_category'] = $this->language->get('placeholder_template_category');
		$data['placeholder_template_article'] = $this->language->get('placeholder_template_article');
		$data['placeholder_image_size_width'] = $this->language->get('placeholder_image_size_width');
		$data['placeholder_image_size_height'] = $this->language->get('placeholder_image_size_height');
		$data['placeholder_date_format'] = $this->language->get('placeholder_date_format');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_design'] = $this->language->get('tab_design');

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

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		if (isset($this->error['limit'])) {
			$data['error_limit'] = $this->error['limit'];
		} else {
			$data['error_limit'] = '';
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

		if (isset($this->request->get['path'])) {
			$url .= '&path=' . $this->request->get['path'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('newsblog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['category_id'])) {
			$data['action'] = $this->url->link('newsblog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('newsblog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('newsblog/category', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_info = $this->model_newsblog_category->getCategory($this->request->get['category_id']);

			//for no errors with versions < 20160920
			if ($category_info['settings']) {
				$settings=unserialize($category_info['settings']);
	            $category_info=array_merge($category_info,$settings);
            }
		}

		$data['token'] = $this->session->data['token'];
		$data['ckeditor'] = $this->config->get('config_editor_default');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['lang'] = $this->language->get('lang');

		if (isset($this->request->post['category_description'])) {
			$data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_description'] = $this->model_newsblog_category->getCategoryDescriptions($this->request->get['category_id']);
		} else {
			$data['category_description'] = array();
		}

		// Categories
		$categories = $this->model_newsblog_category->getAllCategories();

		$data['categories'] = $this->getAllCategories($categories);

		if (isset($category_info)) {
			unset($data['categories'][$category_info['category_id']]);
		}

		if (isset($this->request->post['parent_id'])) {
			$data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($category_info)) {
			$data['parent_id'] = $category_info['parent_id'];
		} elseif (isset($this->request->get['path'])) {
			$a=explode("_",$this->request->get['path']);
			$data['parent_id'] = end($a);
		} else {
			$data['parent_id'] = 0;
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['category_store'])) {
			$data['category_store'] = $this->request->post['category_store'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_store'] = $this->model_newsblog_category->getCategoryStores($this->request->get['category_id']);
		} else {
			$data['category_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($category_info)) {
			$data['keyword'] = $category_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($category_info)) {
			$data['image'] = $category_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($category_info) && is_file(DIR_IMAGE . $category_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['image_size_width'])) {
			$data['image_size_width'] = $this->request->post['image_size_width'];
		} elseif (!empty($category_info)) {
			$data['image_size_width'] = $category_info['image_size_width'];
		} else {
			$data['image_size_width'] = $this->config->get('config_image_category_width');
		}

		if (isset($this->request->post['image_size_height'])) {
			$data['image_size_height'] = $this->request->post['image_size_height'];
		} elseif (!empty($category_info)) {
			$data['image_size_height'] = $category_info['image_size_height'];
		} else {
			$data['image_size_height'] = $this->config->get('config_image_category_height');
		}

		if (isset($this->request->post['show_preview'])) {
			$data['show_preview'] = $this->request->post['show_preview'];
		} elseif (!empty($category_info)) {
			$data['show_preview'] = $category_info['show_preview'];
		} else {
			$data['show_preview'] = 0;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($category_info)) {
			$data['sort_order'] = $category_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$data['status'] = $category_info['status'];
		} else {
			$data['status'] = 1;
		}

		if (isset($this->request->post['limit'])) {
			$data['limit'] = intval($this->request->post['limit']);
		} elseif (!empty($category_info)) {
			$data['limit'] = $category_info['limit'];
		} else {
			$data['limit'] = '10';
		}

		$data['sort_by_array'] = array (
			'a.sort_order'		=> $this->language->get('sort_by_sort_order'),
			'a.date_available'	=> $this->language->get('sort_by_date_available'),
			'ad.name'			=> $this->language->get('sort_by_name')
		);

		if (isset($this->request->post['sort_by'])) {
			$data['sort_by'] = $this->request->post['sort_by'];
		} elseif (!empty($category_info)) {
			$data['sort_by'] = $category_info['sort_by'];
		} else {
			$data['sort_by'] = 'a.date_available';
		}

		$data['sort_direction_array'] = array (
			'desc'		=> $this->language->get('sort_direction_desc'),
			'asc'		=> $this->language->get('sort_direction_asc')
		);

		if (isset($this->request->post['sort_direction'])) {
			$data['sort_direction'] = $this->request->post['sort_direction'];
		} elseif (!empty($category_info)) {
			$data['sort_direction'] = $category_info['sort_direction'];
		} else {
			$data['sort_direction'] = 'desc';
		}

		if (isset($this->request->post['show_in_top'])) {
			$data['show_in_top'] = $this->request->post['show_in_top'];
		} elseif (!empty($category_info)) {
			$data['show_in_top'] = $category_info['show_in_top'];
		} else {
			$data['show_in_top'] = 0;
		}

		if (isset($this->request->post['show_in_top_articles'])) {
			$data['show_in_top_articles'] = $this->request->post['show_in_top_articles'];
		} elseif (!empty($category_info)) {
			$data['show_in_top_articles'] = $category_info['show_in_top_articles'];
		} else {
			$data['show_in_top_articles'] = 0;
		}

		if (isset($this->request->post['show_in_sitemap'])) {
			$data['show_in_sitemap'] = $this->request->post['show_in_sitemap'];
		} elseif (!empty($category_info)) {
			$data['show_in_sitemap'] = $category_info['show_in_sitemap'];
		} else {
			$data['show_in_sitemap'] = 1;
		}

		if (isset($this->request->post['show_in_sitemap_articles'])) {
			$data['show_in_sitemap_articles'] = $this->request->post['show_in_sitemap_articles'];
		} elseif (!empty($category_info)) {
			$data['show_in_sitemap_articles'] = $category_info['show_in_sitemap_articles'];
		} else {
			$data['show_in_sitemap_articles'] = 1;
		}

		if (isset($this->request->post['template_category'])) {
			$data['template_category'] = $this->request->post['template_category'];
		} elseif (!empty($category_info)) {
			$data['template_category'] = $category_info['template_category'];
		} else {
			$data['template_category'] = '';
		}

		if (isset($this->request->post['template_article'])) {
			$data['template_article'] = $this->request->post['template_article'];
		} elseif (!empty($category_info)) {
			$data['template_article'] = $category_info['template_article'];
		} else {
			$data['template_article'] = '';
		}

		if (isset($this->request->post['images_size_width'])) {
			$data['images_size_width'] = $this->request->post['images_size_width'];
		} elseif (!empty($category_info)) {
			$data['images_size_width'] = $category_info['images_size_width'];
		} else {
			$data['images_size_width'] = $this->config->get('config_image_product_width');
		}

		if (isset($this->request->post['images_size_height'])) {
			$data['images_size_height'] = $this->request->post['images_size_height'];
		} elseif (!empty($category_info)) {
			$data['images_size_height'] = $category_info['images_size_height'];
		} else {
			$data['images_size_height'] = $this->config->get('config_image_product_height');
		}

		if (isset($this->request->post['images_size_articles_big_width'])) {
			$data['images_size_articles_big_width'] = $this->request->post['images_size_articles_big_width'];
		} elseif (!empty($category_info)) {
			$data['images_size_articles_big_width'] = $category_info['images_size_articles_big_width'];
		} else {
			$data['images_size_articles_big_width'] = $this->config->get('config_image_popup_width');
		}

		if (isset($this->request->post['images_size_articles_big_height'])) {
			$data['images_size_articles_big_height'] = $this->request->post['images_size_articles_big_height'];
		} elseif (!empty($category_info)) {
			$data['images_size_articles_big_height'] = $category_info['images_size_articles_big_height'];
		} else {
			$data['images_size_articles_big_height'] = $this->config->get('config_image_popup_height');
		}

		if (isset($this->request->post['images_size_articles_small_width'])) {
			$data['images_size_articles_small_width'] = $this->request->post['images_size_articles_small_width'];
		} elseif (!empty($category_info)) {
			$data['images_size_articles_small_width'] = $category_info['images_size_articles_small_width'];
		} else {
			$data['images_size_articles_small_width'] = $this->config->get('config_image_thumb_width');
		}

		if (isset($this->request->post['images_size_articles_small_height'])) {
			$data['images_size_articles_small_height'] = $this->request->post['images_size_articles_small_height'];
		} elseif (!empty($category_info)) {
			$data['images_size_articles_small_height'] = $category_info['images_size_articles_small_height'];
		} else {
			$data['images_size_articles_small_height'] = $this->config->get('config_image_thumb_height');
		}

		if (isset($this->request->post['date_format'])) {
			$data['date_format'] = $this->request->post['date_format'];
		} elseif (!empty($category_info)) {
			$data['date_format'] = $category_info['date_format'];
		} else {
			$data['date_format'] = 'd.m.Y H:i';
		}

		if (isset($this->request->post['category_layout'])) {
			$data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_layout'] = $this->model_newsblog_category->getCategoryLayouts($this->request->get['category_id']);
		} else {
			$data['category_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('newsblog/category_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'newsblog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (utf8_strlen($this->request->post['keyword']) == 0) {
			$this->language->load('newsblog/seotranslit');
			$translit_array = $this->language->get('translit_array');

			foreach ($this->request->post['category_description'] as $value) {
				$name=strtolower($value['name']);
				$translit=strtr($name, $translit_array);
				$translit=preg_replace("/[\-]+/","-",$translit);

				$this->request->post['keyword']=$translit;
				break;

			}
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('newsblog/url_alias');

			$url_alias_info = $this->model_newsblog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['category_id']) && $url_alias_info['query'] != 'newsblog_category_id=' . $this->request->get['category_id']) {
				$this->request->post['keyword'].='-'.$this->request->get['category_id'];
			}

			if ($url_alias_info && !isset($this->request->get['category_id'])) {
				$this->request->post['keyword'].='-'.rand(100,999);
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'newsblog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateRepair() {
		if (!$this->user->hasPermission('modify', 'newsblog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	private function getAllCategories($categories, $parent_id = 0, $parent_name = '') {
		$output = array();

		if (array_key_exists($parent_id, $categories)) {
			if ($parent_name != '') {
				//$parent_name .= $this->language->get('text_separator');
				$parent_name .= ' &gt; ';
			}

			foreach ($categories[$parent_id] as $category) {
				$output[$category['category_id']] = array(
					'category_id' => $category['category_id'],
					'name'        => $parent_name . $category['name']
				);

				$output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
			}
		}

	    uasort($output, array($this, 'sortByName'));

		return $output;
	}

	function sortByName($a, $b) {
		return strcmp($a['name'], $b['name']);
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('newsblog/category');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_newsblog_category->getCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function update() {
		$this->language->load('newsblog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('newsblog/category');
		$this->load->model('newsblog/article');

		$this->model_newsblog_category->updateDateBase();

		$this->session->data['success'] = $this->language->get('text_update_success');

		$this->getList();
	}
}