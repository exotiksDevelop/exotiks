<?php
class ControllerModuleCustomPopup extends Controller {
	public function index($setting) {
		$data['html'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
        $data['custom_css'] = $setting['css'];
        $data['seconds_to_close'] = $setting['seconds_to_close'] * 1000;
        
        $id = 'custom_popup_' . $setting['uid'];
		
		if (!isset($this->session->data[$id])) {
			$this->session->data[$id] = 0;
		}
        
        if (!$setting['display_times'] || ($this->session->data[$id] <= $setting['display_times'])) {
			++$this->session->data[$id];
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/custom_popup.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/custom_popup.tpl', $data);
			} else {
				return $this->load->view('default/template/module/custom_popup.tpl', $data);
			}
		}
	}
}