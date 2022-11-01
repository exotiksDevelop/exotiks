<?php
class ControllerInformationInformation extends Controller {
	public function index() {
		$this->load->language('information/information');

		$this->load->model('catalog/information');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$this->document->setTitle($information_info['meta_title']);
			$this->document->setDescription($information_info['meta_description']);
			$this->document->setKeywords($information_info['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $information_info['title'],
				'href' => $this->url->link('information/information', 'information_id=' .  $information_id)
			);

			$data['heading_title'] = $information_info['title'];

			$data['button_continue'] = $this->language->get('button_continue');


					//microdatapro 7.3 start - 1 - main
					$data['microdatapro_data'] = $information_info;
					$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
					$this->document->setTc_og($this->load->controller('module/microdatapro/tc_og', $data));
					$this->document->setTc_og_prefix($this->load->controller('module/microdatapro/tc_og_prefix'));
					$data['microdatapro'] = $this->load->controller('module/microdatapro/information', $data);
					$microdatapro_main_flag = 1;
					//microdatapro 7.3 start - 1 - main
				
			$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('common/home');


					//microdatapro 7.3 start - 2 - extra
					if(!isset($microdatapro_main_flag)){
						if(isset($information_info) && $information_info){
							$data['microdatapro_data'] = $information_info;
							$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
							$this->document->setTc_og($this->load->controller('module/microdatapro/tc_og', $data));
							$this->document->setTc_og_prefix($this->load->controller('module/microdatapro/tc_og_prefix'));
							$data['microdatapro'] = $this->load->controller('module/microdatapro/information', $data);
						}
					}
					//microdatapro 7.3 end - 2 - extra
				
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/information.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/information.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/information/information.tpl', $data));
			}
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('information/information', 'information_id=' . $information_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');


					//microdatapro 7.3 start - 2 - extra
					if(!isset($microdatapro_main_flag)){
						if(isset($information_info) && $information_info){
							$data['microdatapro_data'] = $information_info;
							$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
							$this->document->setTc_og($this->load->controller('module/microdatapro/tc_og', $data));
							$this->document->setTc_og_prefix($this->load->controller('module/microdatapro/tc_og_prefix'));
							$data['microdatapro'] = $this->load->controller('module/microdatapro/information', $data);
						}
					}
					//microdatapro 7.3 end - 2 - extra
				
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function agree() {
		$this->load->model('catalog/information');

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$output = '';

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
		}

		$this->response->setOutput($output);
	}
}