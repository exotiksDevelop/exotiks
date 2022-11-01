<?php

class ControllerModulesortslimits extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('module/sortslimits');

        $this->document->setTitle('SORTS&Limits');
        
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
        	$this->model_setting_setting->editSetting('sortslimits', $this->request->post);
        	$this->session->data['success'] = $this->language->get('text_success');
			
			
			if ((float)VERSION < 1.9){ 
				$this->redirect($this->url->link('module/sortslimits', 'token=' . $this->session->data['token'], 'SSL'));
			} else if ((float)VERSION < 2.3){ 
				$this->response->redirect($this->url->link('module/sortslimits', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->response->redirect($this->url->link('extension/module/sortslimits', 'token=' . $this->session->data['token'], 'SSL'));
			}
        }
		
        
        
        $text_strings = array(
            'heading_title',
            'text_edit',
        	'text_no',
        	'text_yes',
        	'button_save',
        	'button_cancel',
			'entry_description',
        	'help_description',
			'entry_page',
        	'help_page',			
			'header_1',
        	'header_2',
			'entry_pageh1',
        	'help_pageh1',
			'entry_sortslimits_default',
			'entry_sortslimits_default2',
        	'asc',	
			'desc',
        	'name',	
			'price',
        	'rating',			
        	'viewed',			
        	'model',			
			'quantity',
        	'date_added',			
        	'sort_order',		
        	'limits',
        	'in_stock',
        	'hide',
			'entry_sortslimits_stock_status',			
			'pop',
         );
		 
        foreach ($text_strings as $text) {
            $data[$text] = $this->language->get($text);
        }
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        $data['token'] = $this->session->data['token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/sortslimits', 'token=' . $this->session->data['token'], 'SSL'),
        );

		$data['action'] = $this->url->link('module/sortslimits', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		if ((float)VERSION >= 2.3){ 
			$data['action'] = $this->url->link('extension/module/sortslimits', 'token=' . $this->session->data['token'], 'SSL');
			$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');
		}
        
    	if (isset($this->request->post['sortslimits_default'])) {
			$data['sortslimits_default'] = $this->request->post['sortslimits_default'];
		} else if($this->config->get('sortslimits_default') !== null) {
			$data['sortslimits_default'] = $this->config->get('sortslimits_default');
		}
        else {
        	$data['sortslimits_default'] = 'sort_order';
        }
        
    	if (isset($this->request->post['sortslimits_default2'])) {
			$data['sortslimits_default2'] = $this->request->post['sortslimits_default2'];
		} else if($this->config->get('sortslimits_default2') !== null) {
			$data['sortslimits_default2'] = $this->config->get('sortslimits_default2');
		}
        else {
        	$data['sortslimits_default2'] = 'asc';
        }
		
		if (isset($this->request->post['sortslimits_order_ASC'])) {
			$data['sortslimits_order_ASC'] = $this->request->post['sortslimits_order_ASC'];
		} else if($this->config->get('sortslimits_order_ASC') !== null) {
			$data['sortslimits_order_ASC'] = $this->config->get('sortslimits_order_ASC');
		}
        else {
        	$data['sortslimits_order_ASC'] = 0;
        }
		
		if (isset($this->request->post['sortslimits_order_DESC'])) {
			$data['sortslimits_order_DESC'] = $this->request->post['sortslimits_order_DESC'];
		} else if($this->config->get('sortslimits_order_DESC') !== null) {
			$data['sortslimits_order_DESC'] = $this->config->get('sortslimits_order_DESC');
		}
        else {
        	$data['sortslimits_order_DESC'] = 0;
        }

		if (isset($this->request->post['sortslimits_name_ASC'])) {
			$data['sortslimits_name_ASC'] = $this->request->post['sortslimits_name_ASC'];
		} else if($this->config->get('sortslimits_name_ASC') !== null) {
			$data['sortslimits_name_ASC'] = $this->config->get('sortslimits_name_ASC');
		}
        else {
        	$data['sortslimits_name_ASC'] = 0;
        }
		
		if (isset($this->request->post['sortslimits_name_DESC'])) {
			$data['sortslimits_name_DESC'] = $this->request->post['sortslimits_name_DESC'];
		} else if($this->config->get('sortslimits_name_DESC') !== null) {
			$data['sortslimits_name_DESC'] = $this->config->get('sortslimits_name_DESC');
		}
        else {
        	$data['sortslimits_name_DESC'] = 0;
        }
		
		if (isset($this->request->post['sortslimits_price_ASC'])) {
			$data['sortslimits_price_ASC'] = $this->request->post['sortslimits_price_ASC'];
		} else if($this->config->get('sortslimits_price_ASC') !== null) {
			$data['sortslimits_price_ASC'] = $this->config->get('sortslimits_price_ASC');
		}
        else {
        	$data['sortslimits_price_ASC'] = 0;
        }

		if (isset($this->request->post['sortslimits_price_DESC'])) {
			$data['sortslimits_price_DESC'] = $this->request->post['sortslimits_price_DESC'];
		} else if($this->config->get('sortslimits_price_DESC') !== null) {
			$data['sortslimits_price_DESC'] = $this->config->get('sortslimits_price_DESC');
		}
        else {
        	$data['sortslimits_price_DESC'] = 0;
        }		
		
		if (isset($this->request->post['sortslimits_rating_DESC'])) {
			$data['sortslimits_rating_DESC'] = $this->request->post['sortslimits_rating_DESC'];
		} else if($this->config->get('sortslimits_rating_DESC') !== null) {
			$data['sortslimits_rating_DESC'] = $this->config->get('sortslimits_rating_DESC');
		}
        else {
        	$data['sortslimits_rating_DESC'] = 0;
        }
		
		if (isset($this->request->post['sortslimits_rating_ASC'])) {
			$data['sortslimits_rating_ASC'] = $this->request->post['sortslimits_rating_ASC'];
		} else if($this->config->get('sortslimits_rating_ASC') !== null) {
			$data['sortslimits_rating_ASC'] = $this->config->get('sortslimits_rating_ASC');
		}
        else {
        	$data['sortslimits_rating_ASC'] = 0;
		}
		
		if (isset($this->request->post['sortslimits_model_ASC'])) {
			$data['sortslimits_model_ASC'] = $this->request->post['sortslimits_model_ASC'];
		} else if($this->config->get('sortslimits_model_ASC') !== null) {
			$data['sortslimits_model_ASC'] = $this->config->get('sortslimits_model_ASC');
		}
        else {
        	$data['sortslimits_model_ASC'] = 0;
		}
		
		if (isset($this->request->post['sortslimits_model_DESC'])) {
			$data['sortslimits_model_DESC'] = $this->request->post['sortslimits_model_DESC'];
		} else if($this->config->get('sortslimits_model_DESC') !== null) {
			$data['sortslimits_model_DESC'] = $this->config->get('sortslimits_model_DESC');
		}
        else {
        	$data['sortslimits_model_DESC'] = 0;
		}
		
		if (isset($this->request->post['sortslimits_quantity_ASC'])) {
			$data['sortslimits_quantity_ASC'] = $this->request->post['sortslimits_quantity_ASC'];
		} else if($this->config->get('sortslimits_quantity_ASC') !== null) {
			$data['sortslimits_quantity_ASC'] = $this->config->get('sortslimits_quantity_ASC');
		}
        else {
        	$data['sortslimits_quantity_ASC'] = 0;
        }	
		
		if (isset($this->request->post['sortslimits_quantity_DESC'])) {
			$data['sortslimits_quantity_DESC'] = $this->request->post['sortslimits_quantity_DESC'];
		} else if($this->config->get('sortslimits_quantity_DESC') !== null) {
			$data['sortslimits_quantity_DESC'] = $this->config->get('sortslimits_quantity_DESC');
		}
        else {
        	$data['sortslimits_quantity_DESC'] = 0;
        }	
		
		if (isset($this->request->post['sortslimits_date_added_ASC'])) {
			$data['sortslimits_date_added_ASC'] = $this->request->post['sortslimits_date_added_ASC'];
		} else if($this->config->get('sortslimits_date_added_ASC') !== null) {
			$data['sortslimits_date_added_ASC'] = $this->config->get('sortslimits_date_added_ASC');
		}
        else {
        	$data['sortslimits_date_added_ASC'] = 0;
        }	
		
		if (isset($this->request->post['sortslimits_date_added_DESC'])) {
			$data['sortslimits_date_added_DESC'] = $this->request->post['sortslimits_date_added_DESC'];
		} else if($this->config->get('sortslimits_date_added_DESC') !== null) {
			$data['sortslimits_date_added_DESC'] = $this->config->get('sortslimits_date_added_DESC');
		}
        else {
        	$data['sortslimits_date_added_DESC'] = 0;
        }
		
		if (isset($this->request->post['sortslimits_viewed_ASC'])) {
			$data['sortslimits_viewed_ASC'] = $this->request->post['sortslimits_viewed_ASC'];
		} else if($this->config->get('sortslimits_viewed_ASC') !== null) {
			$data['sortslimits_viewed_ASC'] = $this->config->get('sortslimits_viewed_ASC');
		}
        else {
        	$data['sortslimits_viewed_ASC'] = 0;
        }
		
		if (isset($this->request->post['sortslimits_viewed_DESC'])) {
			$data['sortslimits_viewed_DESC'] = $this->request->post['sortslimits_viewed_DESC'];
		} else if($this->config->get('sortslimits_viewed_DESC') !== null) {
			$data['sortslimits_viewed_DESC'] = $this->config->get('sortslimits_viewed_DESC');
		}
        else {
        	$data['sortslimits_viewed_DESC'] = 0;
        }
		
		if (isset($this->request->post['sortslimits_get'])) {
			$data['sortslimits_get'] = $this->request->post['sortslimits_get'];
		} else if($this->config->get('sortslimits_get') !== null) {
			$data['sortslimits_get'] = $this->config->get('sortslimits_get');
		}
        else {
        	$data['sortslimits_get'] = '';
        }
		
		if (isset($this->request->post['sortslimits_hide'])) {
			$data['sortslimits_hide'] = $this->request->post['sortslimits_hide'];
		} else if($this->config->get('sortslimits_hide') !== null) {
			$data['sortslimits_hide'] = $this->config->get('sortslimits_hide');
		}
        else {
        	$data['sortslimits_hide'] = 0;
        }
		
		if (isset($this->request->post['sortslimits_in_stock'])) {
			$data['sortslimits_in_stock'] = $this->request->post['sortslimits_in_stock'];
		} else if($this->config->get('sortslimits_in_stock') !== null) {
			$data['sortslimits_in_stock'] = $this->config->get('sortslimits_in_stock');
		}
        else {
        	$data['sortslimits_in_stock'] = 0;
        }
		
		if (isset($this->request->post['sortslimits_limits'])) {
			$data['sortslimits_limits'] = $this->request->post['sortslimits_limits'];
		} else if($this->config->get('sortslimits_limits') !== null) {
			$data['sortslimits_limits'] = $this->config->get('sortslimits_limits');
		}
        else {
        	$data['sortslimits_limits'] = '25,50,100';
        }
		
		
		$this->load->model('localisation/stock_status');
		
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		
		$data['stock_statuses'][] = array("stock_status_id" => "0", "name" => $this->language->get('quantity')." <= 0");
		
		
		if (isset($this->request->post['sortslimits_stock_status'])) {
			$data['sortslimits_stock_status'] = $this->request->post['sortslimits_stock_status'];
		} else if($this->config->get('sortslimits_limits') !== null) {
			$data['sortslimits_stock_status'] = $this->config->get('sortslimits_stock_status');
		}
        else {
        	$data['sortslimits_stock_status'] = '0';
        }
		
		if ((float)VERSION < 2) { 
			$this->data = $this->data + $data;
			$this->load->model('design/layout');
			
			$this->data['layouts'] = $this->model_design_layout->getLayouts();

			$this->template = 'module/sortslimits.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
					
			$this->response->setOutput($this->render());
		} else {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('module/sortslimits.tpl', $data));
		}
		
    }
    

    protected function validate() {
    	if (!$this->user->hasPermission('modify', 'module/sortslimits')) {
    		$this->error['warning'] = $this->language->get('error_permission');
    	}
    
    	if (!$this->error) {
			return true;
		} else {
			return false;
		}	
    }
    
}