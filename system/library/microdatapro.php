<?php
//microdatapro 7.3
class MicrodataPro {
	public function opencart_version($d){
		$opencart_version = explode(".", VERSION);
		return $opencart_version[$d];
	}

	public function module_info($key, $admin = false){
		$domen = explode("//", $admin?HTTP_CATALOG:HTTP_SERVER);
		$information = array(
			'main_host'	=> str_replace("/", "", $domen[1]),
			'engine' 	=> VERSION,
			'version' 	=> '7.3',
			'module' 	=> 'MicrodataPro',
			'sys_key'	=> '327450',
			'sys_keyf'  => '7473'
		);
		return $information[$key];
	}

	public function clear($text = '') {
		if(is_string($text) && $text){
			$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8'); //переводим в теги
			$text = str_replace("><", "> <", $text); //что бы не слипался текст если есть пробел между тегами
			$text = str_replace(array("<br />", "<br>"), " ", $text); //fix br
			$text = strip_tags($text); //удаляем теги
			$find = array(PHP_EOL, "\r\n", "\r", "\n", "\t", '  ', '  ', '    ', '    ', '"', "'", "\\", '&varr;', '&nbsp;', '&pound;', '&euro;', '&para;', '&sect;', '&copy;', '&reg;', '&trade;', '&deg;', '&plusmn;', '&frac14;', '&frac12;', '&frac34;', '&times;', '&divide;', '&fnof;', '&Alpha;', '&Beta;', '&Gamma;', '&Delta;', '&Epsilon;', '&Zeta;', '&Eta;', '&Theta;', '&Iota;', '&Kappa;', '&Lambda;', '&Mu;', '&Nu;', '&Xi;', '&Omicron;', '&Pi;', '&Rho;', '&Sigma;', '&Tau;', '&Upsilon;', '&Phi;', '&Chi;', '&Psi;', '&Omega;', '&alpha;', '&beta;', '&gamma;', '&delta;', '&epsilon;', '&zeta;', '&eta;', '&theta;', '&iota;', '&kappa;', '&lambda;', '&mu;', '&nu;', '&xi;', '&omicron;', '&pi;', '&rho;', '&sigmaf;', '&sigma;', '&tau;', '&upsilon;', '&phi;', '&chi;', '&psi;', '&omega;', '&larr;', '&uarr;', '&rarr;', '&darr;', '&harr;', '&spades;', '&clubs;', '&hearts;', '&diams;', '&quot;', '&amp;', '&lt;', '&gt;', '&hellip;', '&prime;', '&Prime;', '&ndash;', '&mdash;', '&lsquo;', '&rsquo;', '&sbquo;', '&ldquo;', '&rdquo;', '&bdquo;', '&laquo;', '&raquo;'); //что чистим
			$text = str_replace($find, ' ', $text); //чистим текст
		}
		return $text;
	}

	public function check_variable($data_array, $key) {
		$opencart_variables = array(
			'category_manufacturer' => array(
				'breadcrumbs' => false,
				'microdatapro_data' => array(
					'results' => array()
				),
			),
			'product' => array(
				'breadcrumbs' => false,
				'heading_title' => "",
				'popup' => "",
				'thumb' => "",
				'images' => array(
					'thumb' => false,
					'popup' => false
				),
				'manufacturer' => "",
				'model' => "",
				'description' => "",
				'special' => 0,
				'price' => 0,
				'options' => false,
				'microdatapro_data' => array(
					'quantity' => 0,
					'reviews' => 0,
					'rating' => 0,
					'sku' => 0,
					'upc' => 0,
					'ean' => 0,
					'isbn' => 0,
					'date_added' => 0,
					'mpn' => 0,
					'results' => array()
				),
				'product_id' => 0,
				'attribute_groups' => false,
				'products' => array()
			),
			'information' => array(
			  'breadcrumbs' => false,
			  'heading_title' => "",
			  'description' => "",
			),
			'tc_og' => array(
				'microdatapro_data' => array(
					'meta_description' => 0,
					'image' => '',
				),
				'description' => "",
				'heading_title' => "",
				'breadcrumbs' => false,
			)
		);

		foreach($opencart_variables[$key] as $variable => $replacement){
			if(is_array($replacement)){
				foreach($replacement as $var => $rep){
					if(!isset($data_array[$variable][$var])){
						$data_array[$variable][$var] = $rep;
					}
				}
			}else{
				if(!isset($data_array[$variable])){
					$data_array[$variable] = $replacement;
				}
			}
		}
		return $data_array;
	}

	public function getModFiles(){
		return array( //file = str; &&& => $
			'system/library/document.php' => array("public function setTitle"),
			'catalog/controller/common/header.php' => array("&&&data['title']", "&&&data['name']"),
			'catalog/view/theme/{theme}/template/common/header.tpl' => array("<?php foreach (&&&analytics", "</head>", "<body"),
			'catalog/controller/common/home.php' => array("&&&this->document->setTitle"),
			'catalog/controller/common/footer.php' => array("&&&data['contact']", "&&&data['powered']"),
			'catalog/view/theme/{theme}/template/common/footer.tpl' => array("</footer>", "</body>", "</html>"),
			'catalog/controller/product/product.php' => array("&&&this->model_catalog_product->updateViewed", "&&&data['column_left']"),
			'catalog/view/theme/{theme}/template/product/product.tpl' => array("<?php echo &&&content_bottom", "<?php echo &&&footer"),
			'catalog/controller/product/category.php' => array("&&&pagination = new", "&&&data['column_left']"),
			'catalog/view/theme/{theme}/template/product/category.tpl' => array("<?php echo &&&content_bottom", "<?php echo &&&footer"),
			'catalog/controller/product/manufacturer.php' => array("&&&pagination = new", "&&&data['column_left']"),
			'catalog/view/theme/{theme}/template/product/manufacturer_info.tpl' => array("<?php echo &&&content_bottom", "<?php echo &&&footer"),
			'catalog/controller/information/information.php' => array("&&&data['description']", "&&&data['column_left']"),
			'catalog/view/theme/{theme}/template/information/information.tpl' => array("<?php echo &&&content_bottom", "<?php echo &&&footer")
		);
	}

	public function getMoreFiles(){
		return array(
			'catalog/view/theme/{theme}/template/common/home.tpl' => array(),
			'catalog/view/theme/{theme}/template/information/contact.tpl' => array(),
			'catalog/view/theme/{theme}/template/product/manufacturer_list.tpl' => array(),
			'catalog/view/theme/{theme}/template/product/review.tpl' => array(),
			'catalog/view/theme/{theme}/template/product/special.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/alltabs.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/bestseller.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/bestsellerpercategory.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/featured.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/latest.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/popular.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/product_categorytabs.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/product_tab.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/productany.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/productviewed.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/special.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/specialpercategory.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/anylist.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/product_review.tpl' => array(),
			'catalog/view/theme/{theme}/template/module/product_viewed.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/alltabs.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/bestseller.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/bestsellerpercategory.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/featured.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/latest.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/popular.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/product_categorytabs.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/product_tab.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/productany.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/productviewed.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/special.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/specialpercategory.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/anylist.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/product_review.tpl' => array(),
			'catalog/view/theme/{theme}/template/extension/module/product_viewed.tpl' => array(),
		);
	}

	public function store_type($type = false) {
		$types = array(
			'AutoPartsStore',
			'BikeStore',
			'BookStore',
			'ClothingStore',
			'ComputerStore',
			'ConvenienceStore',
			'DepartmentStore',
			'ElectronicsStore',
			'Florist',
			'FurnitureStore',
			'GardenStore',
			'GroceryStore',
			'HardwareStore',
			'HobbyShop',
			'HomeGoodsStore',
			'JewelryStore',
			'LiquorStore',
			'MensClothingStore',
			'MobilePhoneStore',
			'MovieRentalStore',
			'MusicStore',
			'OfficeEquipmentStore',
			'OutletStore',
			'PawnShop',
			'PetStore',
			'ShoeStore',
			'SportingGoodsStore',
			'TireShop',
			'ToyStore',
			'WholesaleStore'
		);
		if($type){
			return $types[$type-1];
		}else{
			return $types;
		}
	}

	public function find_old() {
		return array(
	    'itemscope',
	    'itemscope=""',
	    'content="http://schema.org/InStock"',
			'itemtype="http://schema.org/Organization"',
			'itemtype="http://schema.org/Store"',
			'itemprop="priceRange"',
			'itemprop="hasMap"',
			'itemprop="telephone"',
			'itemprop="sameAs"',
			'itemprop="address"',
			'itemprop="addressLocality"',
			'itemprop="postalCode"',
			'itemprop="streetAddress"',
			'itemprop="geo"',
			'itemprop="latitude"',
			'itemprop="longitude"',
			'itemprop="location"',
			'itemprop="potentialAction"',
			'itemprop="target"',
			'itemprop="query-input"',
			'itemprop="openingHoursSpecification"',
			'itemprop="dayOfWeek"',
			'itemprop="opens"',
			'itemprop="closes"',
			'itemprop="brand"',
			'itemprop="manufacturer"',
			'itemprop="model"',
			'itemprop="gtin12"',
			'itemprop="category"',
			'itemprop="ratingCount"',
			'itemprop="itemCondition"',
			'itemprop="review"',
			'itemprop="author"',
			'itemprop="datePublished"',
			'itemprop="dateModified"',
			'itemprop="reviewRating"',
			'itemprop="additionalProperty"',
			'itemprop="value"',
			'itemprop="isRelatedTo"',
			'itemtype="http://schema.org/NewsArticle"',
			'itemprop="mainEntityOfPage"',
			'itemprop="headline"',
			'itemprop="author"',
			'itemprop="contentUrl"',
			'itemprop="width"',
			'itemprop="height"',
			'itemprop="publisher"',
			'itemprop="logo"',
	    'itemprop="itemListElement"',
	    'itemprop="itemListOrder"',
	    'itemprop="numberOfItems"',
	    'itemtype="http://schema.org/ListItem"',
	    'itemtype="http://schema.org/BreadcrumbList"',
	    'itemtype="http://schema.org/Thing"',
	    'itemtype="http://data-vocabulary.org/Breadcrumb"',
	    'itemprop="item"',
	    'itemprop="title"',
	    'itemprop="name"',
	    'itemprop="position"',
	    'itemprop="description"',
	    'itemtype="http://schema.org/Product"',
	    'itemprop="url"',
	    'itemprop="image"',
	    'itemprop="aggregateRating"',
			'itemtype="http://schema.org/AggregateRating"',
	    'itemprop="reviewCount"',
	    'itemprop="ratingValue"',
	    'itemprop="bestRating"',
	    'itemprop="worstRating"',
	    'itemtype="http://schema.org/Offer"',
	    'itemprop="offers"',
	    'itemprop="price"',
	    'itemprop="priceCurrency"',
	    'itemtype="http://schema.org/ItemList"',
	    'itemprop="propertiesList"',
	    'itemprop="availability"',
			'vocab="http://schema.org/"',
			'typeof="BreadcrumbList"',
			'property="itemListElement"',
			'typeof="ListItem"',
			'property="item"',
			'typeof="WebPage"',
			'property="name"',
			'property="position"',
			'itemtype="http://schema.org/AggregateOffer"',
			'itemprop="offerCount"',
			'itemprop="highPrice"',
			'itemprop="lowPrice"',
			'itemprop="priceCurrency"',
			'xmlns:v="http://rdf.data-vocabulary.org/#"',
			'typeof="v:Breadcrumb"',
			'rel="v:url"',
			'property="v:title"',
			'itemprop="email"',
			'itemprop="openingHours"',
			'property="og:',
			'itemtype="http://schema.org/PostalAddress"',
			'itemprop="addressCountry"'
	  );
	}

	public function mbCutString($str, $length, $encoding='UTF-8'){
		if (function_exists('mb_strlen') && (mb_strlen($str, $encoding) <= $length)) {
			return $str;
		}
		if (function_exists('mb_substr')){
			$tmp = mb_substr($str, 0, $length, $encoding);
			return mb_substr($tmp, 0, mb_strripos($tmp, ' ', 0, $encoding), $encoding);
		}else{
			return $str;
		}
	}

	public function key($key, $admin = false){
		$license = false;$a=0;if(isset($key) && !empty($key)){ $key_array = explode("327450", base64_decode(strrev(substr($key, 0, -7))));if($key_array[0] == base64_encode($this->module_info('main_host',$admin)) && $key_array[1] == base64_encode($this->module_info('sys_key').$this->module_info('sys_keyf')+100)){$a= 1;}}return $license=str_replace($key,$this->module_info('main_host',$admin),$a);
	}
}
