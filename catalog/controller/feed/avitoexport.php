<?php
	class ControllerFeedAvitoexport extends Controller {
   		public function index() {			   
			if ($this->config->get('avitoexport_status')) {
				$this->load->model('feed/avitoexport');
				$this->load->model('catalog/product');

				$region = $this->model_feed_avitoexport->getRegion($this->config->get('avitoexport_location_region'));
				if($this->config->get('avitoexport_location_city')){
					$city = $this->model_feed_avitoexport->getCity($this->config->get('avitoexport_location_city'));
				}
				if($this->config->get('avitoexport_location_district')){
					$district = $this->model_feed_avitoexport->getCityChild($this->config->get('avitoexport_location_district'));
				} elseif($this->config->get('avitoexport_location_subway')){
					$subway = $this->model_feed_avitoexport->getCityChild($this->config->get('avitoexport_location_subway'));
				}
				$manager = $this->config->get('avitoexport_contact_name');
				$phone = $this->config->get('avitoexport_contact_phone');
				$status = $this->config->get('avitoexport_service');
				$ad_type = $this->config->get('avitoexport_adtype') ? 'Товар от производителя' : 'Товар приобретен на продажу';
				
				if($this->config->get('avitoexport_ignore')){
					$ignoreList = explode(',',$this->config->get('avitoexport_ignore'));
				}
				if($this->config->get('avitoexport_delete')){
					$deleteList = explode(',',$this->config->get('avitoexport_delete'));
				}

				$productsByCat = $this->getProduts();
				$paths = $this->getAvitoPath($this->config->get('avitoexport_dependence_to'));
				
				$output  = '<?xml version="1.0" encoding="UTF-8"?>';
				$output .= '<Ads formatVersion="3" target="Avito.ru">';	
				if(isset($deleteList) && strtolower($deleteList[0]) == 'stop_all') {
					$output .= '<Ad><Id>STOP</Id></Ad>';
				} else {		
					foreach ($productsByCat as $key => $products) {
						$category = $paths[$key][0];
						if(isset($paths[$key][1])){$good_type = $paths[$key][1];} else {$good_type = '';}
						if(isset($paths[$key][2])){$apparel = $paths[$key][2];} else {$apparel = '';}
						foreach ($products as $p) {
							if(isset($ignoreList) && in_array($p['product_id'],$ignoreList)) continue;
							$output .= '<Ad>';
								$output .= '<Id>' . $this->transliterate($p['category_name']) . '_' . $p['product_id'] . '</Id>';
								$output .= '<ListingFee>' . $this->config->get('avitoexport_listing_fee') . '</ListingFee>';
								$output .= '<AdStatus>' . $status . '</AdStatus>';
								$output .= '<AllowEmail>' . ($this->config->get('avitoexport_contact_mail') ? 'Да' : 'Нет') . '</AllowEmail>';

								if(isset($deleteList) && in_array($p['product_id'],$deleteList)){
									$output .= '<DataEnd>2017-06-08</DataEnd>';
								}
			
								$output .= '<Address>' . $region . (isset($city) ? ', '.$city : '') . (isset($district) ? ', '.$district : '') . (isset($subway) ? ', '.$subway : '') . '</Address>';

								$output .= '<ManagerName>' . $manager . '</ManagerName>';
								$output .= '<ContactPhone>' . $phone . '</ContactPhone>';
								$output .= '<Category>' . $category . '</Category>';
								$output .= '<GoodsType>' . $good_type . '</GoodsType>';
								$output .= '<AdType>' . $ad_type . '</AdType>';
								if ($apparel) {
									$output .= '<Apparel>' . $apparel . '</Apparel>';
									if ($apparel == 'Обувь' && $good_type == 'Женская одежда'){
										$output .= '<Size><![CDATA[< 35]]></Size><Size>36</Size><Size>37</Size><Size>38</Size><Size>39</Size><Size>40</Size><Size><![CDATA[> 41]]></Size>';
									} elseif ($apparel == 'Обувь' && $good_type == 'Мужская одежда'){
										$output .= '<Size><![CDATA[< 40]]></Size><Size>41</Size><Size>42</Size><Size>43</Size><Size>44</Size><Size>45</Size><Size><![CDATA[> 46]]></Size>';
									} else {
										$output .= '<Size>Без размера</Size>';
									}
								}
								
								$output .= '<Condition>Новый</Condition>';
								
								$output .= '<Title>' . $p['name'] . '</Title>';
								$output .= '<Description><![CDATA[' . utf8_substr(strip_tags(html_entity_decode($p['description'], ENT_QUOTES, 'UTF-8')), 0, 3000) . ']]></Description>';
								
								if(isset($p['image'])&&$p['image']!=''){
									$output .= '<Images>';
									$output .= '<Image url="' . HTTP_SERVER . 'image/' . $p['image'] . '" />';
									$Images = $this->model_catalog_product->getProductImages($p['product_id']);
									if(count($Images) > 0){
										foreach ($Images as $key => $image) {
											if($key > 9) break;
											$output .= '<Image url="' . HTTP_SERVER . 'image/' . $image['image'] . '" />';
										}
									}
									$output .= '</Images>';
								}
								
								$output .= '<Price>' . ($p['special'] ? intval($p['special']) : intval($p['price'])) . '</Price>';
							$output .= '</Ad>';
						}
					}
				}
		 		$output .= '</Ads>';
		 		$this->response->addHeader('Content-Type: application/xml');
		 		$this->response->setOutput($output);
	  		}
  		}
		  
   		private function getProduts() {
	  		$output = '';

			$tpm = array();
	  		
			if($this->config->get('avitoexport_dependence_id_from')){
	  			$categories = $this->config->get('avitoexport_dependence_id_from');
				$stock = $this->config->get('avitoexport_stock');
				foreach ($categories as $key => $category) {
					array_push($tpm,$this->model_feed_avitoexport->getProductsByCategoryId($category,!empty($stock)));
				}
			}
			
	  		return $tpm;
   		}

		private function getAvitoPath($data){
			$output = array();			
			if(isset($data)){
				foreach ($data as $key => $p) {
					$tmp = explode(' / ',$p);
					array_push($output,$tmp);
				}
			}
			return $output;
		}

		private function transliterate($input){
			$gost = array(
				"Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"-","є"=>"ye","ѓ"=>"g",
				"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ё"=>"YO",
				"Ж"=>"ZH","З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L","М"=>"M",
				"Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T","У"=>"U",
				"Ф"=>"F","Х"=>"X","Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
				"Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
				"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"yo","ж"=>"zh","з"=>"z",
				"и"=>"i","й"=>"j","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o",
				"п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
				"ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"","ы"=>"y","ь"=>"",
				"э"=>"e","ю"=>"yu","я"=>"ya"," "=>"_","—"=>"_",","=>"_","!"=>"_",
				"@"=>"_","#"=>"-","$"=>"","%"=>"","^"=>"","&"=>"","*"=>"","("=>"",
				")"=>"","+"=>"","="=>"",";"=>"",":"=>"","'"=>"","\""=>"","~"=>"",
				"`"=>"","?"=>"","/"=>"","\\"=>"","["=>"","]"=>"","{"=>"","}"=>"","|"=>""
			);

			return strtr($input, $gost);
		}
	}
?>