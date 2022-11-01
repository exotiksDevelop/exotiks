<?php
class ControllerModuleTags extends Controller {
	public function index() {
		$this->load->language('module/tags');

		$data['heading_title'] = $this->language->get('heading_title');

		//$this->document->addStyle('catalog/view/javascript/jqcloud/jqcloud.css');
		//$this->document->addScript('catalog/view/javascript/jqcloud/jqcloud-1.0.4.min.js');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = false;
		}

		$data = array();

		if ($parts){
			$data['category_id'] = $parts[count($parts)-1];
		}

		$this->load->model('catalog/tags');

		$this->load->model('catalog/category');

		$data['tags'] = array();

		$tags = $this->model_catalog_tags->getCloudTags($data);
		
		foreach ($tags as $tag) {
			$href = $this->url->link('product/tags', 'tag_id=' . $tag['tag_id']);
        	if ($tag['category_id']){
        		$path = $tag['category_id'];
        		$c = $this->model_catalog_category->getCategory($tag['category_id']);
        		if ($c['parent_id']){
        			$path = $c['parent_id']."_".$path;
        		}
        		$href = $this->url->link('product/tags', 'path='.$path.'&tag_id=' . $tag['tag_id']);
        	}
			$data['tags'][] = array(
				'tag_id' 	  => $tag['tag_id'],
				'name'        => $tag['name'],
				'count'		  => $tag['count'],
				'href'        => $href
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/tags.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/tags.tpl', $data);
		} else {
			return $this->load->view('default/template/module/tags.tpl', $data);
		}
	}
}