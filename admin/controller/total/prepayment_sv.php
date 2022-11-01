<?php 
class ControllerTotalPrepaymentSV extends Controller { 
	private $error = array(); 
  private $_templateData = array();
	 
	public function index() { 
    $version = explode('.', VERSION);
    $version = (int)($version[0] . $version[1]);

    if ($version < 15) {
      return false;
    }
    
		$this->_templateData = $this->language->load('total/prepayment_sv');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('prepayment_sv', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
      if ($version == 15) {
        $this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
      } else {
        $this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
      }
		}
		
 		if (isset($this->error['warning'])) {
			$this->_templateData['error_warning'] = $this->error['warning'];
		} else {
			$this->_templateData['error_warning'] = '';
		}

   		$this->_templateData['breadcrumbs'] = array();

   		$this->_templateData['breadcrumbs'][] = array(
        'text'      => $this->language->get('text_home'),
        'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
        'separator' => false
   		);

   		$this->_templateData['breadcrumbs'][] = array(
        'text'      => $this->language->get('text_total'),
        'href'      => $this->url->link('extension/prepayment_sv', 'token=' . $this->session->data['token'], 'SSL'),
        'separator' => ' :: '
   		);
		
   		$this->_templateData['breadcrumbs'][] = array(
        'text'      => $this->language->get('heading_title'),
        'href'      => $this->url->link('total/prepayment_sv', 'token=' . $this->session->data['token'], 'SSL'),
        'separator' => ' :: '
   		);
		
		$this->_templateData['action'] = $this->url->link('total/prepayment_sv', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->_templateData['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['prepayment_sv_type'])) {
			$this->_templateData['prepayment_sv_type'] = $this->request->post['prepayment_sv_type'];
		} else {
			$this->_templateData['prepayment_sv_type'] = $this->config->get('prepayment_sv_type');
		}
    
		if (isset($this->request->post['prepayment_sv_value'])) {
			$this->_templateData['prepayment_sv_value'] = (float)$this->request->post['prepayment_sv_value'];
		} else {
			$this->_templateData['prepayment_sv_value'] = (float)$this->config->get('prepayment_sv_value');
		}
    
		if (isset($this->request->post['prepayment_sv_min_value'])) {
			$this->_templateData['prepayment_sv_min_value'] = (float)$this->request->post['prepayment_sv_min_value'];
		} else {
			$this->_templateData['prepayment_sv_min_value'] = (float)$this->config->get('prepayment_sv_min_value');
		}
    
		if (isset($this->request->post['prepayment_sv_min_value'])) {
			$this->_templateData['prepayment_sv_reduce_prepayment'] = (bool)$this->request->post['prepayment_sv_reduce_prepayment'];
		} else {
			$this->_templateData['prepayment_sv_reduce_prepayment'] = (bool)$this->config->get('prepayment_sv_reduce_prepayment');
		}
    
    if ($version == 15 ) {
      $this->load->model('setting/extension');
      $totals = $this->model_setting_extension->getInstalled('total');
      $shippings = $this->model_setting_extension->getInstalled('shipping');
    } else {
      $this->load->model('extension/extension');
      $totals = $this->model_extension_extension->getInstalled('total');
      $shippings = $this->model_extension_extension->getInstalled('shipping');
    }
    
    //Totals
    $files = glob(DIR_APPLICATION . 'controller/total/*.php');
    $installed = array();
    foreach ($files as $file) {
    	$extension = basename($file, '.php');
    	$key = array_search($extension, $totals);
    	if ($key !== false) {
    		$installed[$key] = $extension;
			}
		}


    $sort_order = array();
    $actived = array();
    foreach ($installed as $key => $value) {
      $sort_order[$key] = $this->config->get($value . '_sort_order');
    }
    array_multisort($sort_order, SORT_ASC, $installed);
    
    foreach ($installed as $code) {
      if ($this->config->get($code . '_status') && $code != 'prepayment_sv') {
        $this->language->load('total/' . $code);
        $actived[] = array(
          'code' => $code,
          'name' => $this->language->get('heading_title')
        );
      }
    }
    
    $this->_templateData['totals'] = $actived;
    
		if (isset($this->request->post['prepayment_sv_total'])) {
			$this->_templateData['prepayment_sv_total'] = $this->request->post['prepayment_sv_total'];
		} elseif ($this->config->get('prepayment_sv_total')) {
			$this->_templateData['prepayment_sv_total'] = $this->config->get('prepayment_sv_total');
		} else {
      $this->_templateData['prepayment_sv_total'] = array();
    }

    //Shipping
    $files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
    $installed = array();
    foreach ($files as $file) {
    	$extension = basename($file, '.php');
    	$key = array_search($extension, $shippings);
    	if ($key !== false) {
    		$installed[$key] = $extension;
			}
		}
    
    $sort_order = array();
    $actived = array();
    foreach ($installed as $key => $value) {
      $sort_order[$key] = $this->config->get($value . '_sort_order');
    }
    array_multisort($sort_order, SORT_ASC, $installed);
    
    foreach ($installed as $code) {
      if ($this->config->get($code . '_status')) {
        $this->language->load('shipping/' . $code);
        $actived[] = array(
          'code' => $code,
          'name' => $this->language->get('heading_title')
        );
      }
    }
    
    $this->_templateData['shippings'] = $actived;
    
		if (isset($this->request->post['prepayment_sv_shipping'])) {
			$this->_templateData['prepayment_sv_shipping'] = $this->request->post['prepayment_sv_shipping'];
		} else {
			$this->_templateData['prepayment_sv_shipping'] = $this->config->get('prepayment_sv_shipping');
		}

		if (isset($this->request->post['prepayment_sv_status'])) {
			$this->_templateData['prepayment_sv_status'] = $this->request->post['prepayment_sv_status'];
		} else {
			$this->_templateData['prepayment_sv_status'] = $this->config->get('prepayment_sv_status');
		}

		if (isset($this->request->post['prepayment_sv_sort_order'])) {
			$this->_templateData['prepayment_sv_sort_order'] = $this->request->post['prepayment_sv_sort_order'];
		} else {
			$this->_templateData['prepayment_sv_sort_order'] = !$this->config->get('prepayment_sv_sort_order') ? 9999 : $this->config->get('prepayment_sv_sort_order');
		}
			
    if ($version == 15) {
      $this->data = $this->_templateData;
      
      $this->template = 'total/prepayment_sv.tpl';
      $this->children = array(
        'common/header',
        'common/footer'
      );
          
      $this->response->setOutput($this->render());
    } else {
      $data = $this->_templateData;
      $data['header'] = $this->load->controller('common/header');
      $data['column_left'] = $this->load->controller('common/column_left');
      $data['footer'] = $this->load->controller('common/footer');

      $this->response->setOutput($this->load->view('total/prepayment_sv.tpl', $data));
    }
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/prepayment_sv')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!isset($this->request->post['prepayment_sv_total'])) {
			$this->error['warning'] = $this->language->get('error_total');
		}

		if (!isset($this->request->post['prepayment_sv_shipping']) || empty($this->request->post['prepayment_sv_shipping'])) {
			$this->error['warning'] = $this->language->get('error_shipping');
		}
    
    if (!isset($this->request->post['prepayment_sv_reduce_prepayment'])) {
      $this->request->post['prepayment_sv_reduce_prepayment'] = false;
    }
    
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>