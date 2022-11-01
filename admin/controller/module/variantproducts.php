<?php
class ControllerModuleVariantproducts extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('module/variantproducts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/variantproducts');

		$this->getList();
	}

	public function insert() {
		$this->language->load('module/variantproducts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/variantproducts');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_module_variantproducts->addVariantproduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_insert');

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

			$this->response->redirect($this->url->link('module/variantproducts', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('module/variantproducts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/variantproducts');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_module_variantproducts->editVariantproduct($this->request->get['variantproduct_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_update');

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

			$this->response->redirect($this->url->link('module/variantproducts', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	

	
	
	public function delete() {
		$this->language->load('module/variantproducts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/variantproducts');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $variantproduct_id) {
				$this->model_module_variantproducts->deleteVariantproduct($variantproduct_id);
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

			$this->response->redirect($this->url->link('module/variantproducts', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	    private function getList() {		
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		/*if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}*/
		
   		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/variantproducts', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$data['insert'] = $this->url->link('module/variantproducts/insert', 'token=' . $this->session->data['token'], 'SSL');
		$data['delete'] = $this->url->link('module/variantproducts/delete', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['variantproducts'] = array();
		
    	$filter_data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);
       


	    $result_total = $this->model_module_variantproducts->getTotalVariantproducts();		
		$results = $this->model_module_variantproducts->getVariantproducts($filter_data);
	
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/variantproducts/update', 'token=' . $this->session->data['token'] . '&variantproduct_id=' . $result['variantproduct_id'], 'SSL')
			);

			$data['variantproducts'][] = array(
				'variantproduct_id'    => $result['variantproduct_id'],
				'title'       => $result['title'],
				'label'       => $result['label'],				
				'sort_order'  => $result['sort_order'],
                'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    => isset($this->request->post['selected']) && in_array($result['variantproduct_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
        $data['text_success'] = $this->language->get('text_success');
        $data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_label'] = $this->language->get('text_label');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
        $data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_delete'] = $this->language->get('button_delete');
        $data['button_edit'] = $this->language->get('button_edit');
		$data['edit_module'] = $this->language->get('edit_module');
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
		$pagination = new Pagination();
		$pagination->total = $result_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('module/variantproducts', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($result_total - $this->config->get('config_limit_admin'))) ? $result_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $result_total, ceil($result_total / $this->config->get('config_limit_admin')));
        
		
		$data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('module/variantproducts_list.tpl', $data));

	}

	private function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_label'] = $this->language->get('entry_label');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
        $data['entry_product'] = $this->language->get('entry_product');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
        $data['text_label'] = $this->language->get('text_label');
		$data['edit_group'] = $this->language->get('edit_group');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/variantproducts', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['variantproduct_id'])) {
			$data['action'] = $this->url->link('module/variantproducts/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/variantproducts/update', 'token=' . $this->session->data['token'] . '&variantproduct_id=' . $this->request->get['variantproduct_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('module/variantproducts', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['variantproduct_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$variantproduct_info = $this->model_module_variantproducts->getVariantproduct($this->request->get['variantproduct_id']);
    	}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['variantproduct_description'])) {
			$data['variantproduct_description'] = $this->request->post['variantproduct_description'];
		} elseif (isset($this->request->get['variantproduct_id'])) {
			$data['variantproduct_description'] = $this->model_module_variantproducts->getVariantproductDescriptions($this->request->get['variantproduct_id']);
		} else {
			$data['variantproduct_description'] = array();
		}
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($variantproduct_info)) {
			$data['sort_order'] = $variantproduct_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($variantproduct_info)) {
			$data['status'] = $variantproduct_info['status'];
		} else {
			$data['status'] = 1;
		}

		$this->load->model('catalog/product');

		$data['products'] = $this->model_catalog_product->getProducts(array('sort' => 'pd.name'));

        if (isset($this->request->post['product'])) {
			$data['product_variantproduct'] = $this->request->post['product'];
		} elseif (!empty($this->request->get['variantproduct_id'])) {
			$data['product_variantproduct'] = $this->model_module_variantproducts->getVariantproductProducts($this->request->get['variantproduct_id']);
		} else {
			$data['product_variantproduct'] = array();
		}
	
		
		
		
		
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('module/variantproducts_form.tpl', $data));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/variantproducts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['variantproduct_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 2) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/variantproducts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "variantproducts` (
  `variantproduct_id` int(11) NOT NULL AUTO_INCREMENT,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`variantproduct_id`)
)");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "variantproducts_description` (
		  `variantproduct_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_bin NOT NULL,
  `label` varchar(180) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`variantproduct_id`,`language_id`)
		)");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "variantproducts_to_product` (
  `variantproduct_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`variantproduct_id`,`product_id`)
		)");	
        
    }
}
?>