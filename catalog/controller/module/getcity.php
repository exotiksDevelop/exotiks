<?php
class ControllerModuleGetcity extends Controller {
	public function index() {

		if (!$this->config->get('getcity_status') || !isset($this->request->post['q']) || !$this->request->post['q']) {
			exit;
		}

		$this->load->model('module/getcity');

		$q = urlencode($this->request->post['q']);	
		if ((int)$this->config->get('getcity_limit')) {
			$limit = (int)$this->config->get('getcity_limit');
		} else {
			$limit = 10;
		}	
		if ($this->config->get('getcity_token')) {
			$token = $this->config->get('getcity_token');
		} else {
			$token = "13792f7513792f7513792f75cc131f26991137913792f7548edcd07ad12941279609dc1";
		}	
		

		$url = "https://api.vk.com/method/database.getCities?country_id=1&count=" . $limit . "&lang=ru&access_token=" . $token . "&v=5.85&q=" . $q;

		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_FAILONERROR, 1); 
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);                                         
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

		$source = json_decode(curl_exec($curl));
		curl_close($curl);

		if (isset($source->error) || !$source) {
			exit;
		}

		$data['cities'] = array();

		foreach ($source->response->items as $key => $city) {

			if ($city->id == 1) {
				$city->region = 'Москва';
			}
			if ($city->id == 2) {
				$city->region = 'Санкт-Петербург';
			}
			if ($city->id == 185) {
				$city->region = 'Севастополь';
			}

			if (!isset($city->region)) {
				$city->region = "";
			}

			

			$zone = $this->model_module_getcity->getZoneByVKZone($city->region);
			if ($zone) {
				$zone_id = $zone['zone_id'];
			} else {
				$zone_id = "";
			}

			$data['cities'][] = array(
				'id'		=> $city->id,
				'name'		=> $city->title,
				'area'		=> isset($city->area) ? $city->area : "",
				'region'	=> isset($city->region) ? $city->region : "",
				'zone_id'	=> $zone_id

			);
		}
		$data['cities'] = array_unique($data['cities'], SORT_REGULAR);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/getcity.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/getcity.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/module/getcity.tpl', $data));
		}

	}
}