<?php
class ControllerNewsBlogArticle extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('newsblog/article');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->load->model('newsblog/category');

		$category_info = false;
		$settings = false;

		if (isset($this->request->get['newsblog_path'])) {
			$newsblog_path = '';

			$parts = explode('_', (string)$this->request->get['newsblog_path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $newsblog_path_id) {

				if (!$newsblog_path) {
					$newsblog_path = $newsblog_path_id;
				} else {
					$newsblog_path .= '_' . $newsblog_path_id;
				}

				$category_info = $this->model_newsblog_category->getCategory($newsblog_path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('newsblog/category', 'newsblog_path=' . $newsblog_path)
					);
				}
			}

			$images_size_articles_big=array($this->config->get('config_image_popup_width'),$this->config->get('config_image_popup_height'));
		  	$images_size_articles_small=array($this->config->get('config_image_thumb_width'),$this->config->get('config_image_thumb_height'));

			// Set the last category breadcrumb
			$category_info = $this->model_newsblog_category->getCategory($category_id);

			if ($category_info) {
				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('newsblog/category', 'newsblog_path=' . $this->request->get['newsblog_path'])
				);

	            //for no errors with versions < 20160920
				if ($category_info['settings']) {
					$settings=unserialize($category_info['settings']);

		            $images_size_articles_big=array($settings['images_size_articles_big_width'],$settings['images_size_articles_big_height']);
		            $images_size_articles_small=array($settings['images_size_articles_small_width'],$settings['images_size_articles_small_height']);
	            }
			}
		}

		if (isset($this->request->get['newsblog_article_id'])) {
			$newsblog_article_id = (int)$this->request->get['newsblog_article_id'];
		} else {
			$newsblog_article_id = 0;
		}

		$this->load->model('newsblog/article');

		$article_info = $this->model_newsblog_article->getArticle($newsblog_article_id);

		if ($article_info) {
			$url = '';

			if (isset($this->request->get['newsblog_path'])) {
				$url .= '&newsblog_path=' . $this->request->get['newsblog_path'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $article_info['name'],
				'href' => $this->url->link('newsblog/article', $url . '&newsblog_article_id=' . $newsblog_article_id)
			);

			if ($article_info['meta_title']) {
				$this->document->setTitle($article_info['meta_title']);
			} else {
				$this->document->setTitle($article_info['name']);
			}

			$this->document->setDescription($article_info['meta_description']);
			$this->document->setKeywords($article_info['meta_keyword']);
			$this->document->addLink($this->url->link('newsblog/article', 'newsblog_article_id=' . $newsblog_article_id), 'canonical');

			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');

			if ($article_info['meta_h1']) {
				$data['heading_title'] = $article_info['meta_h1'];
			} else {
				$data['heading_title'] = $article_info['name'];
			}

			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_related_products'] = $this->language->get('text_related_products');
			$data['text_attributes'] = $this->language->get('text_attributes');

			//for related products
			$this->load->language('product/product');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');

			if ($settings && $settings['show_preview'])
			$data['preview'] = html_entity_decode($article_info['preview'], ENT_QUOTES, 'UTF-8');
			else
			$data['preview'] = '';

			$data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');

			$this->load->model('tool/image');

			if ($article_info['image']) {
				$data['original']	= HTTP_SERVER.'image/'.$article_info['image'];
				$data['popup'] 		= $this->model_tool_image->resize($article_info['image'], $images_size_articles_big[0], $images_size_articles_big[1]);
				$data['thumb'] 		= $this->model_tool_image->resize($article_info['image'], $images_size_articles_small[0], $images_size_articles_small[1]);
			} else {
				$data['original'] 	= '';
				$data['popup'] 		= '';
				$data['thumb'] 		= '';
			}

			$data['images'] = array();

			$results = $this->model_newsblog_article->getArticleImages($newsblog_article_id);

			foreach ($results as $result) {
				$data['images'][] = array(
					'original'	=> HTTP_SERVER.'image/'.$result['image'],
					'popup' 	=> $this->model_tool_image->resize($result['image'], $images_size_articles_big[0], $images_size_articles_big[1]),
					'thumb' 	=> $this->model_tool_image->resize($result['image'], $images_size_articles_small[0], $images_size_articles_small[1])
				);
			}


            $data['attributes'] = $article_info['attributes'];

			$data['articles'] = array();

			$results = $this->model_newsblog_article->getArticleRelated($newsblog_article_id);

			foreach ($results as $result) {

				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}

				$mainCategoryId =  $this->model_newsblog_article->getArticleMainCategoryId($result['article_id']);

				$data['articles'][] = array(
					'article_id'  => $result['article_id'],
					'date'		  => date($this->language->get('date_format_short'), strtotime($result['date_available'])),
					'thumb'       => $image,
					'name'        => $result['name'],
					'preview'     => html_entity_decode($result['preview'], ENT_QUOTES, 'UTF-8'),
					'attributes'  => $result['attributes'],
					'href'        => $this->url->link('newsblog/article', 'newsblog_path=' . $mainCategoryId . '&newsblog_article_id=' . $result['article_id'])
				);
			}

			$data['products'] = array();

            $this->load->model('catalog/product');
			$results = $this->model_newsblog_article->getArticleRelatedProducts($newsblog_article_id);

            foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			$data['tags'] = array();

			if ($article_info['tag']) {
				$tags = explode(',', $article_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}


			$this->model_newsblog_article->updateViewed($this->request->get['newsblog_article_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$template_default='article.tpl';
			if ($settings && $settings['template_article']) $template_default=$settings['template_article'];

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/newsblog/'.$template_default)) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/newsblog/'.$template_default, $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/newsblog/'.$template_default, $data));
			}
		} else {
			$url = '';

			if (isset($this->request->get['newsblog_path'])) {
				$url .= '&newsblog_path=' . $this->request->get['newsblog_path'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('newsblog/article', $url . '&newsblog_article_id=' . $newsblog_article_id)
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

	public function review() {
		//not used in 201607

		$this->load->language('newsblog/article');

		$this->load->model('catalog/review');

		$data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['newsblog_article_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['newsblog_article_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('newsblog/article/review', 'newsblog_article_id=' . $this->request->get['newsblog_article_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/review.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
		}
	}

	public function write() {
		$this->load->language('newsblog/article');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['newsblog_article_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getRecurringDescription() {
		$this->language->load('newsblog/article');
		$this->load->model('newsblog/article');

		if (isset($this->request->post['newsblog_article_id'])) {
			$newsblog_article_id = $this->request->post['newsblog_article_id'];
		} else {
			$newsblog_article_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$article_info = $this->model_newsblog_article->getArticle($newsblog_article_id);
		$recurring_info = $this->model_newsblog_article->getProfile($newsblog_article_id, $recurring_id);

		$json = array();

		if ($article_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $article_info['tax_class_id'], $this->config->get('config_tax')));
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $article_info['tax_class_id'], $this->config->get('config_tax')));

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
