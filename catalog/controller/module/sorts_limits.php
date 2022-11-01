<?php
class ControllerModuleSortslimits extends Controller {

public function index($input) {

	$url = '';

	$pprice = 'p.price';
	
	$sort_route = $this->request->get['route'];
	
	switch ($sort_route){
		case 'product/category':
			$sort_ids = 'path=' . $this->request->get['path'];
			break;
		case 'product/manufacturer/info':
			$sort_ids = 'manufacturer_id=' . $this->request->get['manufacturer_id'];
			break;
		case 'product/special':
			$sort_ids = ''; $pprice = 'ps.price';
			break;
		case 'product/search':
			$sort_ids = ''; $pprice = 'ps.price';
			if (isset($this->request->get['tag'])) {
				$sort_ids = 'tag=' . $this->request->get['tag'];
			} elseif (isset($this->request->get['search'])) {
				$sort_ids = 'search=' . $this->request->get['search'];
			} else {
				$sort_ids = '';
			}
			break;
		default:
			return array();
			break;
	}

	
	$url = '';
	
	$gets = 'manufacturer_id,filter';
	
	$gets .= $this->config->get('sortslimits_get') ? ',' . $this->config->get('sortslimits_get') : '';
	
	
	$list = explode(",", $gets);	

	foreach ($list as $get){
		
		if (isset($this->request->get[$get])) $url .= '&' . $get . '=' . $this->request->get[$get];

	}

	$data['sorts'] = array();

	if(version_compare(VERSION, '1.5.5.0', '<')) $limits = array();

	if ($this->config->get('sortslimits_order_ASC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.sort_order&order=ASC' . $url)
		);
	}
	if ($this->config->get('sortslimits_name_ASC')) {	
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=pd.name&order=ASC' . $url)
		);
	}
	if ($this->config->get('sortslimits_name_DESC')) {	
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=pd.name&order=DESC' . $url)
		);
	}
	if ($this->config->get('sortslimits_price_ASC')) {	
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => $pprice.'-ASC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort='.$pprice.'&order=ASC' . $url)
		);
	}
	if ($this->config->get('sortslimits_price_DESC')) {	
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => $pprice.'-DESC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort='.$pprice.'&order=DESC' . $url)
		);
	}
	if ($this->config->get('config_review_status')) {
		if ($this->config->get('sortslimits_rating_DESC')) {	
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_rating_desc'),
			'value' => 'rating-DESC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=rating&order=DESC' . $url)
		);
		}
		if ($this->config->get('sortslimits_rating_ASC')) {	
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_rating_asc'),
			'value' => 'rating-ASC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=rating&order=ASC' . $url)
		);
		}
	}
	if ($this->config->get('sortslimits_model_ASC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'p.model-ASC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.model&order=ASC' . $url)
		);
	}
	if ($this->config->get('sortslimits_model_DESC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.model&order=DESC' . $url)
		);
	}
	if ($this->config->get('sortslimits_quantity_ASC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_quantity_asc'),
			'value' => 'p.quantity-ASC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.quantity&order=ASC' . $url)
		);
	}
	if ($this->config->get('sortslimits_quantity_DESC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_quantity_desc'),
			'value' => 'p.quantity-DESC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.quantity&order=DESC' . $url)
		);
	}/*  //Новизна по дате
	if ($this->config->get('sortslimits_date_added_ASC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added_asc'),
			'value' => 'p.date_added-ASC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.date_added&order=ASC' . $url)
		);
	}
	if ($this->config->get('sortslimits_date_added_DESC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added_desc'),
			'value' => 'p.date_added-DESC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.date_added&order=DESC' . $url)
		);
	}*/
	if ($this->config->get('sortslimits_date_added_ASC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added_asc'),
			'value' => 'p.product_id-ASC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.product_id&order=ASC' . $url)
		);
	}
	if ($this->config->get('sortslimits_date_added_DESC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added_desc'),
			'value' => 'p.product_id-DESC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.product_id&order=DESC' . $url)
		);
	}
	if ($this->config->get('sortslimits_viewed_ASC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_viewed_asc'),
			'value' => 'p.viewed-ASC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.viewed&order=ASC' . $url)
		);
	}
	if ($this->config->get('sortslimits_viewed_DESC')) {
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_viewed_desc'),
			'value' => 'p.viewed-DESC',
			'href'  => $this->url->link($sort_route, $sort_ids .  '&sort=p.viewed&order=DESC' . $url)
		);
	}

	if ((float)VERSION < 1.9 ){ 
		$default = $this->config->get('config_catalog_limit');
	} elseif((float)VERSION < 2.3) {
		$default = $this->config->get('config_product_limit');
	} else {
		$default = $this->config->get($this->config->get('config_theme') . '_product_limit');
	}
	
	if ($this->config->get('sortslimits_limits')) {
		$limits = array($default) + explode(',', ','.$this->config->get('sortslimits_limits'));
	}
	
	$out['limits'] = $limits;
	
	
	
	if (version_compare(VERSION, '1.5.5.0', '<')) {
		$data['limits'] = array(array_shift($data['limits']));
		array_shift($limits);
		foreach($limits as $value){
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link($sort_route, $sort_ids . $url . '&limit=' . $value)
			);
		}
		
		$out['limits'] = $data['limits'];
		
	}

	
	$out['data']['sorts'] = $data['sorts'];
	
	return $out;

}



public function sold() {

}


}
