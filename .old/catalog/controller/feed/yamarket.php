<?php
/**
 * @class ControllerFeedYamarket
 * @author Yandex.Money & Alexander Toporkov <toporchillo@gmail.com>
 *
 * @property ModelToolImage $model_tool_image
 * @property Loader $load
 * @property Config $config
 * @property ModelLocalisationCurrency $model_localisation_currency
 * @property \Cart\Currency $currency
 * @property \Cart\Tax $tax
 * @property ModelCatalogProduct $model_catalog_product
 */
class ControllerFeedYamarket extends Controller
{
    public function index()
    {
        $for23 = (version_compare(VERSION, "2.3.0", '>=')) ? 'extension/' : '';
        $this->load->model($for23.'yamodel/yamarket');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('localisation/currency');

        $categories = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket"}->getCategories();
        $allow_cat_array = explode(',', $this->config->get('ya_market_categories'));
        if (!empty($allow_cat_array) || $this->config->get('ya_market_catall')) {
            $ids_cat = ($this->config->get('ya_market_catall'))? '': implode(',', $allow_cat_array);
        } else {
            die("Need select categories");
        }
        $products = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket"}->getProducts($ids_cat, true);
        if (count($products)) {
            $products = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket"}->getProducts($ids_cat, false);
        }
        $currencies = $this->model_localisation_currency->getCurrencies();
        $shop_currency = $this->config->get('config_currency');
        $offers_currency = 'RUB';
        $currency_default = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket"}->getCurrencyByISO($offers_currency);
        if (!isset($currency_default['value'])) {
            die("Not exist RUB");
        }

        $decimal_place = 2;
        $currencies = array_intersect_key($currencies, array_flip(array('RUR', 'RUB', 'USD', 'EUR', 'UAH', 'BYN')));
        $yamarket = new YandexMarket($this->config);
        $yamarket->yml('utf-8');
        $yamarket->set_shop(
            $this->config->get('ya_market_shopname'),
            $this->config->get('config_name'),
            $this->config->get('config_url')
        );

        if ($this->config->get('ya_market_allcurrencies')) {
            foreach ($currencies as $currency) {
                if ($currency['status'] == 1) {
                    $yamarket->add_currency($currency['code'], ((float)$currency_default['value'] / (float)$currency['value']));
                }
            }
        }
        else {
            $yamarket->add_currency($currency_default['code'], ((float)$currency_default['value']));
        }

        foreach ($categories as $category) {
            if (!$this->config->get('ya_market_catall') && !in_array($category['category_id'], $allow_cat_array)) {
                continue;
            }
            $yamarket->add_category($category['name'], $category['category_id'], $category['parent_id']);
        }

        foreach ($products as $product) {
            if ($this->config->get('ya_market_available') && $product['quantity'] < 1) {
                continue;
            }

            $available = false;
            if ($this->config->get('ya_market_set_available') == 1) {
                $available = true;
            } elseif ($this->config->get('ya_market_set_available') == 2) {
                if ($product['quantity'] > 0) {
                    $available = true;
                }
            } elseif ($this->config->get('ya_market_set_available') == 3) {
                $available = true;
                if ($product['quantity'] == 0) {
                    continue;
                }
            } elseif ($this->config->get('ya_market_set_available') == 4) {
                $available = false;
            }

            $data = array();
            $data['id'] = $product['product_id'];
            $data['available'] = $available;
            $data['url'] = htmlspecialchars_decode($this->url->link('product/product', 'product_id=' . $product['product_id']));

            $data['price'] = round(floatval($product['price']), 2);
            if ($product['special'] && $product['special'] < $product['price']) {
                $data['price'] = round(floatval($product['special']), 2);
                $data['oldprice'] = round(floatval($product['price']), 2);
            }

            $data['currencyId'] = $currency_default['code'];
            $data['categoryId'] = $product['category_id'];
            $data['vendor'] = $product['manufacturer'];
            $data['vendorCode'] = $product['model'];
            $data['delivery'] = ($this->config->get('ya_market_delivery') && $product['shipping'] == '1') ? 'true' : 'false';
            $data['pickup'] = ($this->config->get('ya_market_pickup') ? 'true' : 'false');
            $data['store'] = ($this->config->get('ya_market_store') ? 'true' : 'false');
            $data['description'] = $product['description'];

            if($product['quantity'] < 1) {
                $stock_id = $product['stock_status_id'];
                $delivery_cost = $this->config->get('ya_market_stock_cost');
                $delivery_days = $this->config->get('ya_market_stock_days');
                $data['delivery-options'][] = array(
                    'cost' => $delivery_cost[$stock_id],
                    'days' => $delivery_days[$stock_id],
                );
            }
            if ($product['minimum'] > 1) {
                $data['sales_notes'] = 'Минимальное кол-во для заказа: ' . $product['minimum'];
            }
            if ($this->config->get('config_comment')) {
                $data['sales_notes'] = $this->config->get('config_comment');
            }

            $data['picture'] = array();
            if (isset($product['image'])) {
                $imageUrl = $this->getPictureUrl($product['image']);
                if (!empty($imageUrl)) {
                    $data['picture'][] = $imageUrl;
                }
            }
            foreach ($this->model_catalog_product->getProductImages($data['id']) as $pic) {
                if (count($data['picture']) > 9) {
                    break;
                }
                $imageUrl = $this->getPictureUrl($pic['image']);
                if (!empty($imageUrl)) {
                    $data['picture'][] = $imageUrl;
                }
            }

            if ($this->config->get('ya_market_prostoy')) {
                $data['price'] = number_format(
                    $this->currency->convert(
                        $this->tax->calculate(
                            $data['price'], $product['tax_class_id'], $this->config->get('config_tax')
                        ),
                        $shop_currency,
                        $offers_currency
                    ),
                    $decimal_place,
                    '.',
                    ''
                );
                $data['name'] = $product['name'];
                if ($data['price'] > 0) {
                    $yamarket->add_offer($data['id'], $data, $data['available']);
                }
            } else {
                $data['model'] = $product['name'];
                if ($product['weight'] > 0) {
                    $data['weight'] = number_format($product['weight'], 1, '.', '');
                }
                if ($this->config->get('ya_market_dimensions') && $product['length'] > 0 && $product['width'] > 0 && $product['height'] > 0) {
                    $data['dimensions'] = number_format($product['length'], 1, '.', '') . '/' . number_format($product['width'], 1, '.', '') . '/' . number_format($product['height'], 1, '.', '');
                }
                $data['downloadable'] = 'false';
                $data['rec'] = explode(',', $product['rel']);
                $data['param'][] = array(
                    'id' => 'weight',
                    'name' => 'Вес',
                    'value' => number_format($product['weight'], 1, '.', ''),
                    'unit' => $product['weight_unit']
                );
                if ($this->config->get('ya_market_features')) {
                    $attributes = $this->model_catalog_product->getProductAttributes($data['id']);
                    if (count($attributes)) {
                        foreach ($attributes as $attr) {
                            foreach ($attr['attribute'] as $val) {
                                $data['param'][] = array(
                                    'id' => $val['attribute_id'],
                                    'name' => $val['name'],
                                    'value' => $val['text']
                                );
                            }
                        }
                    }
                }

                if (!$this->makeOfferCombination($data, $product, $shop_currency, $offers_currency, $decimal_place, $yamarket)) {
                    $data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
                    if ($data['price'] > 0) {
                        $yamarket->add_offer($data['id'], $data, $data['available']);
                    }
                }
            }
        }

        $this->response->addHeader('Content-Type: application/xml; charset=utf-8');
        $this->response->setOutput($yamarket->get_xml());
    }

    private function getPictureUrl($localName)
    {
        $url = str_replace('&amp;', '&', $this->model_tool_image->resize($localName, 600, 600));
        if (!empty($url)) {
            if (strncmp('http', $url, 4) !== 0) {
                if ($this->request->server['HTTPS']) {
                    $url = $this->config->get('config_ssl') . $url;
                } else {
                    $url = $this->config->get('config_url') . $url;
                }
            }
        }
        return $url;
    }

	public function makeOfferCombination($data, $product, $shop_currency, $offers_currency, $decimal_place, $object)
	{
		$colors = array();
		$sizes = array();
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
		if (count($this->config->get('ya_market_color_options')))
			$colors = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket"}->getProductOptions($this->config->get('ya_market_color_options'), $product['product_id']);
		if (count($this->config->get('ya_market_size_options')))
			$sizes = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket"}->getProductOptions($this->config->get('ya_market_size_options'), $product['product_id']);
		if (!count($colors) && !count($sizes))
			return false;

		if(count($colors))
		{
			foreach ($colors as $option)
			{
				$data_temp = $data;
				$data_temp['model'].= ', '.$option['option_name'].' '.$option['name'];
				$data_temp['param'][] = array('name' => $option['option_name'], 'value' => $option['name']);
				$data_temp['id'] = $product['product_id'].'c'.$option['option_value_id'];
				$data_temp['available'] = $data['available'];
				if ($option['price_prefix'] == '+') {
					$data_temp['price']+= $option['price'];
					if (isset($data_temp['oldprice']))
						$data_temp['oldprice']+= $option['price'];
				}
				elseif ($option['price_prefix'] == '-') {
					$data_temp['price']-= $option['price'];
					if (isset($data_temp['oldprice']))
						$data_temp['oldprice']-= $option['price'];
				}
				elseif ($option['price_prefix'] == '=') {
					$data_temp['price'] = $option['price'];
				}
				$data_temp = $this->setOptionedWeight($data_temp, $option);
				$data_temp['url'].= '#'.$option['product_option_value_id'];
				$colors_array[] = $data_temp;
			}
		}
		else
		{
			$colors_array[] = $data;
		}

		unset($data_temp);
		unset($option);
		foreach($colors_array as $i => $data)
			if(count($sizes))
			{
				foreach ($sizes as $option)
				{
					$data_temp = $data;
					$data_temp['id'] .= 'c'.$option['option_value_id'];
					$data_temp['model'].= ', '.$option['option_name'].' '.$option['name'];
					$data_temp['param'][] = array('name' => $option['option_name'], 'value' => $option['name']);
					$data_temp['available'] = $data['available'];
					if ($option['price_prefix'] == '+') {
						$data_temp['price']+= $option['price'];
						if (isset($data_temp['oldprice']))
							$data_temp['oldprice']+= $option['price'];
					}
					elseif ($option['price_prefix'] == '-') {
						$data_temp['price']-= $option['price'];
						if (isset($data_temp['oldprice']))
							$data_temp['oldprice']-= $option['price'];
					}
					elseif ($option['price_prefix'] == '=') {
						$data_temp['price'] = $option['price'];
					}

					$data_temp = $this->setOptionedWeight($data_temp, $option);
					if (count($colors))
						$data_temp['url'].= '-'.$option['product_option_value_id'];
					else
						$data_temp['url'].= '#'.$option['product_option_value_id'];

					$data_temp['price'] = number_format($this->currency->convert($this->tax->calculate($data_temp['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
					if (isset($data_temp['oldprice']))
						$data_temp['oldprice'] = number_format($this->currency->convert($this->tax->calculate($data_temp['oldprice'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
					if ($data['price'] > 0) {
						$object->add_offer($data_temp['id'], $data_temp, $data_temp['available'], $product['product_id']);
					}
					unset($data_temp);
				}
			}
			else
			{
				$data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
				if ($data['price'] > 0) {
					$object->add_offer($data['id'], $data, $data['available'], $product['product_id']);
				}
			}

		return true;
	}

	protected function setOptionedWeight($product, $option) {
		if (isset($option['weight']) && isset($option['weight_prefix'])) {
			foreach ($product['param'] as $i=>$param) {
				if (isset($param['id']) && ($param['id'] == 'WEIGHT')) {
					if ($option['weight_prefix'] == '+')
						$product['param'][$i]['value']+= $option['weight'];
					elseif ($option['weight_prefix'] == '-')
						$product['param'][$i]['value']-= $option['weight'];
					break;
				}
			}
		}
		return $product;
	}
}

class YandexMarket{
	private $config;
	var $from_charset = 'windows-1251';
	var $shop = array('name' => '', 'company' => '', 'url' => '', 'platform' => 'ya_opencart');
	var $currencies = array();
	var $categories = array();
	var $offers = array();

	public function __construct(&$config){
		$this->config = $config;
	}

	function yml($from_charset = 'windows-1251')
	{
		$this->from_charset = trim(strtolower($from_charset));
	}


	function convert_array_to_tag($arr)
	{
		$s = '';
		foreach($arr as $tag => $val)
		{
			if($tag == 'weight' && (int)$val == 0)
				continue;

			if($tag == 'picture')
			{
				foreach ($val as $v){
					$s .= '<'.$tag.'>'.$v.'</'.$tag.'>';
					$s .= PHP_EOL;
				}
			}
			elseif($tag == 'param')
			{
				foreach ($val as $v){
					$s .= '<param name="'.$this->prepare_field($v['name']).'">'.$this->prepare_field($v['value']).'</param>';
					$s .= PHP_EOL;
				}
            }elseif($tag == 'delivery-options'){
                foreach ($val as $v){
                    $s .= '<delivery-options>'.PHP_EOL.
                        '<option cost="'.$v['cost'].'" days="'.$v['days'].'"/>'.PHP_EOL.
                        '</delivery-options>'.PHP_EOL;
                }
            }else{
				$s .= '<'.$tag.'>'.$val.'</'.$tag.'>';
				$s .= PHP_EOL;
			}
		}

		return $s;
	}

	function convert_array_to_attr($arr, $tagname, $tagvalue = '')
	{
		$s = '<'.$tagname.' ';
		foreach($arr as $attrname=>$attrval)
			$s .= $attrname . '="'.$attrval.'" ';

		$s .= ($tagvalue!='') ? '>'.$tagvalue.'</'.$tagname.'>' : '/>';
		$s .= PHP_EOL;
		return $s;
	}

	function prepare_field($s)
	{
		$from = array('"', '&', '>', '<', '\'');
		$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;');
		$s = str_replace($from, $to, $s);
		$s=preg_replace('!<[^>]*?>!', ' ', $s);
		// if ($this->from_charset!='windows-1251') $s = iconv($this->from_charset, 'windows-1251', $s);
		$s = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $s);
		return trim($s);
	}

	function set_shop($name, $company, $url){
		$this->shop['name'] = $this->prepare_field($name);
		$this->shop['name'] = mb_substr(mb_convert_encoding($this->shop['name'], "UTF-8"), 0, 20);
		$this->shop['company'] = $this->prepare_field($company);
		$this->shop['url'] = $this->prepare_field($url);
	}

	function add_currency($id, $rate = 'CBRF', $plus = 0)
	{
		$rate = strtoupper($rate);
		$plus = str_replace(',', '.', $plus);
		if ($rate=='CBRF' && $plus>0)
			$this->currencies[] = array('id'=>$this->prepare_field(strtoupper($id)), 'rate'=>'CBRF', 'plus'=>(float)$plus);
		else
		{
			$rate = str_replace(',', '.', $rate);
			$this->currencies[] = array('id'=>$this->prepare_field(strtoupper($id)), 'rate'=>(float)$rate);
		}
		return true;
	}

	function add_category($name, $id, $parent_id = -1)
	{
		if ((int)$id<1||trim($name)=='') return false;
		if ((int)$parent_id>0)
			$this->categories[] = array('id'=>(int)$id, 'parentId'=>(int)$parent_id, 'name'=>$this->prepare_field($name));
		else
			$this->categories[] = array('id'=>(int)$id, 'name'=>$this->prepare_field($name));
		return true;
	}

    function add_offer($id, $data, $available = true, $group_id = 0)
    {
        $allowed = array(
            'url', 'price', 'oldprice', 'currencyId', 'categoryId', 'picture', 'store', 'pickup', 'delivery',
            'name', 'vendor', 'vendorCode', 'model', 'description', 'sales_notes','oldprice',
            'delivery-options','downloadable', 'weight', 'dimensions', 'param', 'country_of_origin'
        );
        foreach($data as $k => $v)
        {
            if(!in_array($k, $allowed)) unset($data[$k]);
            if(!in_array($k, array('picture','param','rec','description','delivery-options')))
                $data[$k] = strip_tags($this->prepare_field($v));
            if ($k=='description') {
                $data[$k] = preg_replace('|<[/]?[^>]+?>|', '', trim(html_entity_decode ($v)));
                $data[$k] = preg_replace("/&#?[a-z0-9]+;/i", '', $data[$k]);
                if (strlen($data[$k])>=3000) {
                    $iCut = strpos($data[$k], ' ', 2950);
                    $data[$k] = substr($data[$k], 0, $iCut);
                }
            }
        }
        $tmp = $data;
        $data = array();
        foreach($allowed as $key)
            if (isset($tmp[$key]) && !empty($tmp[$key]))
                $data[$key] = $tmp[$key];

        $out = array('id' => $id, 'data' => $data, 'available' => ($available) ? 'true' : 'false');
        if ($group_id>0) $out['group_id'] = $group_id;
        if(!$this->config->get('ya_market_prostoy'))
            $out['type'] = 'vendor.model';
        $this->offers[] = $out;
    }

	function get_xml_header()
	{
		return '<?xml version="1.0" encoding="utf-8"?>'.
		'<yml_catalog date="'.date('Y-m-d H:i').'">';
	}

	function get_xml_shop()
	{
		$s = '<shop>' . PHP_EOL;
		$s .= $this->convert_array_to_tag($this->shop);
		$s .= '<currencies>' . PHP_EOL;
		foreach($this->currencies as $currency)
			$s .= $this->convert_array_to_attr($currency, 'currency');

		$s .= '</currencies>' . PHP_EOL;
		$s .= '<categories>' . PHP_EOL;
		foreach($this->categories as $category)
		{
			$category_name = $category['name'];
			unset($category['name']);
			$s .= $this->convert_array_to_attr($category, 'category', $category_name);
		}
        $s .= '</categories>' . PHP_EOL;

        $localShippingCost = explode (';', $this->config->get('ya_market_localcoast'));
        $localShippingDays = explode (';', $this->config->get('ya_market_localdays'));
        if (count($localShippingCost) != count ($localShippingDays)) throw new Exception("'Стоимость доставки в домашнем регионе' и/или 'Срок доставки в домашнем регионе' заполнены с ошибкой");
        $s .= '<delivery-options>'. PHP_EOL;
        foreach ($localShippingCost as $key=>$value){
            $s .= '<option cost="'.$value.'" days="'.$localShippingDays[$key].'"/>'. PHP_EOL;
        }
        $s .=  '</delivery-options>' . PHP_EOL;

        $s .= '<offers>' . PHP_EOL;
		foreach($this->offers as $offer)
		{
			$data = $offer['data'];
			unset($offer['data']);
			$s .= $this->convert_array_to_attr($offer, 'offer', $this->convert_array_to_tag($data));
		}
		$s .= '</offers>' . PHP_EOL;
		$s .= '</shop>';
		return $s;
	}

	function get_xml_footer()
	{
		return '</yml_catalog>';
	}

	function get_xml()
	{
		$xml = $this->get_xml_header();
		$xml .= $this->get_xml_shop();
		$xml .= $this->get_xml_footer();
		return $xml;
	}
}

class ControllerExtensionFeedYamarket extends ControllerFeedYamarket {}
