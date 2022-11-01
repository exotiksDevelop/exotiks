<?php
class ControllerModuleSmsAlert extends Controller {
	public function index($idata) {
		$this->load->language('module/sms_alert');

		$this->load->model('module/sms_alert');

		$status = $this->model_module_sms_alert->getOrder($idata);

		if (in_array($status, $this->config->get('sms_alert_processing_status'))) {
		
			file_get_contents("http://sms.ru/sms/send?api_id=" . $this->config->get('sms_alert_id') . "&to=" . $this->config->get('sms_alert_tel') . "&text=".urlencode($this->language->get('text_order') . $idata));
		
		}
		
		
	}
}