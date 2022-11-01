<?php  
class ControllerModulePosts extends Controller {
	public function index() {
		$this->language->load('module/posts');
		$this->load->model('extension/posts');
		
		$filter_data = array(
			'page' => 1,
			'limit' => 5,
			'start' => 0,
		);
	 
		$data['heading_title'] = $this->language->get('heading_title');
	 
		$all_posts = $this->model_extension_posts->getModulePosts($filter_data);
	 
		$data['all_posts'] = array();
	 
		foreach ($all_posts as $posts) {
			$data['all_posts'][] = array (
				'title' 		=> $posts['title'],
				'description' 	=> (strlen(strip_tags(html_entity_decode($posts['short_description']))) > 50 ? substr(strip_tags(html_entity_decode($posts['short_description'])), 0, 50) . '...' : strip_tags(html_entity_decode($posts['short_description']))),
				'view' 			=> $this->url->link('information/posts/posts', 'posts_id=' . $posts['posts_id']),
				'date_added' 	=> date($this->language->get('date_format_short'), strtotime($posts['date_added']))
			);
		}
	 
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/posts.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/posts.tpl', $data);
		} else {
			return $this->load->view('default/template/module/posts.tpl', $data);
		}
	}
}