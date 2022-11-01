<?php
class ControllerShippingCdek extends Controller {

	public function sessionReceiverCityId()
	{
		if(isset($this->request->post['receiverCityId']) && $this->request->post['receiverCityId'])
		{
			$receiverCityId = $this->request->post['receiverCityId']; 
			$this->session->data['receiverCityId'] = $receiverCityId;
		}
	}
	
	public function sessionAdd()
	{
		$sdek_pvz = '';
		if(isset($this->request->post['sdek_pvz']) && $this->request->post['sdek_pvz'])
			$sdek_pvz = $this->request->post['sdek_pvz']; 
		$this->session->data['sdek']['pvz'] = $sdek_pvz;

		$sdek_pvzinfo = '';
		if(isset($this->request->post['sdek_pvzinfo']) && $this->request->post['sdek_pvzinfo'])
			$sdek_pvzinfo = $this->request->post['sdek_pvzinfo']; 
		$this->session->data['sdek']['pvzinfo'] = $sdek_pvzinfo;

		$sdek_city = '';
		if(isset($this->request->post['sdek_city']) && $this->request->post['sdek_city'])
			$sdek_city = $this->request->post['sdek_city']; 
		$this->session->data['sdek']['city'] = $sdek_city;
	}

	public function chechPvz()
	{
		$return_array['status'] = 'succes';
		$return_array['message'] = 'Сессия обновлена';

		$sdek_pvz = '';
		if(isset($this->session->data['sdek']['pvz']) && !empty($this->session->data['sdek']['pvz']))
			$sdek_pvz = $this->session->data['sdek']['pvz']; 

		if(isset($this->request->post['need_pvz']) && isset($this->request->post['shipping_method']))
		{
			$shipping_method = $this->request->post['shipping_method'];
			$parts = explode('.', $shipping_method);
			$tarif = array_pop($parts);
			$need_pvz = $this->request->post['need_pvz']; 
			foreach ($need_pvz as $key => $value) 
			{
				if($value == $tarif)
				{
					if(empty($sdek_pvz))
					{
						$return_array['status'] = 'error';
						$return_array['message'] = 'Для выбранного тарифа нужно выбрать пункт выдачи заказа';
					}

				}
			}
		}	

		$return_array['debuf'] = print_r($this->request->post,1);
		$return_array['debuf'] .= print_r($sdek_pvz,1);
		echo json_encode($return_array);
	}

	public function renderTpl($data) {

		$tpl = '';

		if (version_compare(VERSION, '2.2') >= 0) {
			$tpl = $this->load->view('shipping/sdek.tpl', $data);
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shipping/sdek.tpl')) {
				$tpl = $this->load->view($this->config->get('config_template') . '/template/shipping/sdek.tpl', $data);
			} else {
				$tpl = $this->load->view('default/template/shipping/sdek.tpl', $data);
			}
		}
		

		return $tpl;
	}
}
?>