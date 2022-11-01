<?php
class ControllerCommonCountGoods extends Controller
{
	public function index()
	{

		$data['count_goods'] = sprintf($this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/count_goods.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/count_goods.tpl', $data);
		} else {
			return $this->load->view($this->config->get('config_template') . '/template/common/count_goods.tpl', $data);
		}
	}
}
