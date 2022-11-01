<?php
class ControllerNewsBlogCategory extends Controller {
	public function index() {
		$this->load->language('newsblog/category');

		$this->load->model('newsblog/category');
		$this->load->model('newsblog/article');

		$this->load->model('tool/image');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['newsblog_path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['newsblog_path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_newsblog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('newsblog/category', 'newsblog_path=' . $path)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_newsblog_category->getCategory($category_id);

		if ($category_info) {

			if ($category_info['meta_title']) {
				$this->document->setTitle($category_info['meta_title']);
			} else {
				$this->document->setTitle($category_info['name']);
			}

			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			if ($category_info['meta_h1']) {
				$data['heading_title'] = $category_info['meta_h1'];
			} else {
				$data['heading_title'] = $category_info['name'];
			}

			$data['text_empty'] = $this->language->get('text_empty');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_attributes'] = $this->language->get('text_attributes');

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('newsblog/category', 'newsblog_path=' . $this->request->get['newsblog_path'])
			);

			if ($category_info['image']) {
				$data['image'] = '/image/'.$category_info['image'];
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$data['image'] = '';
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

			$data['categories'] = array();

			$categories = $this->model_newsblog_category->getCategories($category_id);

			foreach ($categories as $category) {
				$image = $this->model_tool_image->resize($category['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));

				$data['categories'][] = array(
					'name' 			=> $category['name'],
					'image'			=> $image,
					'href' 			=> $this->url->link('newsblog/category', 'newsblog_path=' . $this->request->get['newsblog_path'] . '_' . $category['category_id'])
				);
			}




			$data['articles'] = array();

			$limit = $category_info['limit'];

			if ($limit>0) {

				$sort = $category_info['sort_by'];
				$order = $category_info['sort_direction'];

				$filter_data = array(
					'filter_category_id' => $category_id,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page - 1) * $limit,
					'limit'              => $limit
				);

				$article_total = $this->model_newsblog_article->getTotalArticles($filter_data);

				$results = $this->model_newsblog_article->getArticles($filter_data);

				foreach ($results as $result) {

					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					}


					$data['articles'][] = array(
						'article_id'  => $result['article_id'],
						'date'		  => date($this->language->get('date_format_short'), strtotime($result['date_available'])),
						'thumb'       => $image,
						'name'        => $result['name'],
						'preview'     => html_entity_decode($result['preview'], ENT_QUOTES, 'UTF-8'),
						'attributes'  => $result['attributes'],
						'href'        => $this->url->link('newsblog/article', 'newsblog_path=' . $this->request->get['newsblog_path'] . '&newsblog_article_id=' . $result['article_id'])
					);
				}


				$pagination = new Pagination();
				$pagination->total = $article_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->url = $this->url->link('newsblog/category', 'newsblog_path=' . $this->request->get['newsblog_path'] . '&page={page}');

				$data['pagination'] = $pagination->render();

				$data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

				// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
				if ($page == 1) {
				    $this->document->addLink($this->url->link('newsblog/category', 'newsblog_path=' . $category_info['category_id'], 'SSL'), 'canonical');
				} elseif ($page == 2) {
				    $this->document->addLink($this->url->link('newsblog/category', 'newsblog_path=' . $category_info['category_id'], 'SSL'), 'prev');
				} else {
				    $this->document->addLink($this->url->link('newsblog/category', 'newsblog_path=' . $category_info['category_id'] . '&page='. ($page - 1), 'SSL'), 'prev');
				}

				if ($limit && ceil($article_total / $limit) > $page) {
				    $this->document->addLink($this->url->link('newsblog/category', 'newsblog_path=' . $category_info['category_id'] . '&page='. ($page + 1), 'SSL'), 'next');
				}
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$template_default='category.tpl';
			if ($category_info['template_category']) $template_default=$category_info['template_category'];

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/newsblog/'.$template_default)) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/newsblog/'.$template_default, $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/newsblog/'.$template_default, $data));
			}
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('newsblog/category')
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

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
}
