<?php
class ControllerModuleNewsBlogArticles extends Controller {

	public function index($setting) {

		$this->load->language('module/newsblog_articles');

		$this->load->model('newsblog/article');

		if($setting['show_title']){
      		$data['heading_title'] = $setting['name'];
		}else{
      		$data['heading_title'] = false;
		}

		$data['text_more'] = $this->language->get('text_more');

		$data['text_date_added'] = $this->language->get('text_date_added');

		$data['articles'] = array();

		$category_id	= $setting['main_category_id'];
		$sort			= $setting['sort_by'];
		$order			= $setting['sort_direction'];

		$filter_data = array(
					'filter_category_id' => $category_id,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => 0,
					'limit'              => $setting['limit']
		);

		$data['link_to_category']=false;
		if ($category_id) $data['link_to_category']=$this->url->link('newsblog/category', 'newsblog_path=' . $category_id);

		$results = $this->model_newsblog_article->getArticles($filter_data);

		$this->load->model('tool/image');

		foreach ($results as $result) {
			if ($result['image']) {
 				$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
 			} else {
 				$image = false;
 			}

 			$mainCategoryId =  $this->model_newsblog_article->getArticleMainCategoryId($result['article_id']);

			$data['articles'][] = array(
				'title'        		=> $result['name'],
				'thumb' 			=> $image,
				'viewed' 			=> sprintf($this->language->get('text_viewed'), $result['viewed']),
				'description'  		=> utf8_substr(strip_tags(html_entity_decode($result['preview'], ENT_QUOTES, 'UTF-8')), 0, $setting['desc_limit']),
				'href'         		=> $this->url->link('newsblog/article', 'newsblog_path=' . $mainCategoryId . '&newsblog_article_id=' . $result['article_id']),
				'posted'   			=> date($this->language->get('date_format_short'), strtotime($result['date_available']))
			);
		}

		$template='newsblog_articles.tpl';
		if ($setting['template']) $template=$setting['template'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/'.$template)) {
			return $this->load->view($this->config->get('config_template') . '/template/module/'.$template, $data);
		} else {
			return $this->load->view('default/template/module/'.$template, $data);
		}

	}
}
