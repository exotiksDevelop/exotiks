<?php
class ControllerModuleLazyLoad extends Controller {
    private $version = '1.0.1';
    private $extension = 'Lazy Load Images';
    private $error = array();
	
    public function index() {
        $this->language->load('module/lazyload');
		
        $this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
        $this->load->model('setting/setting');
		
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
		
        $data['heading_title'] = $this->language->get('heading_title');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_install'] = $this->language->get('text_install');
        $data['text_uninstall'] = $this->language->get('text_uninstall');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['tab_general'] = $this->language->get('tab_general');
        $data['button_cancel'] = $this->language->get('button_cancel');
		
        $data['breadcrumbs'] = array();
		
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
        );
		
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );
		
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/lazyload', 'token=' . $this->session->data['token'], 'SSL')
        );
		
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $data['token'] = $this->session->data['token'];
        $data['status'] = $this->status();
		
        if ($data['status']) {
            $data['action'] = $this->url->link('module/lazyload/uninstall', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('module/lazyload/install', 'token=' . $this->session->data['token'], 'SSL');
        }
		
        $data['version'] = $this->version;
        $data['extension'] = $this->extension;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
		
        $this->response->setOutput($this->load->view('module/lazyload.tpl', $data));
    }
	
    private function status() {
        $path = substr_replace(DIR_SYSTEM, '', -7);
		
        if (file_exists($path . 'vqmod/xml/lazyload.xml')) {
            return true;
        } else {
            return false;
        }
    }
	
    public function install() {
        $this->language->load('module/lazyload');
		
        if ($this->user->hasPermission('modify', 'module/lazyload')) {
            $path = substr_replace(DIR_SYSTEM, '', -7);
			
            if (file_exists($path . 'vqmod/xml/lazyload.xml_')) {
                rename($path . 'vqmod/xml/lazyload.xml_', $path . 'vqmod/xml/lazyload.xml');
				
                $this->session->data['success'] = $this->language->get('text_success');
            }
        }
		
        $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
    }
	
    public function uninstall() {
        $this->language->load('module/lazyload');
		
        if ($this->user->hasPermission('modify', 'module/lazyload')) {
            $path = substr_replace(DIR_SYSTEM, '', -7);
			
            if (file_exists($path . 'vqmod/xml/lazyload.xml')) {
                rename($path . 'vqmod/xml/lazyload.xml', $path . 'vqmod/xml/lazyload.xml_');
				
                $this->session->data['success'] = $this->language->get('text_success');
            }
        }
		
        $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
    }
}