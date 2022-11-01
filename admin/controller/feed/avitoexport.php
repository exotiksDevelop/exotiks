<?php
class ControllerFeedAvitoexport extends Controller
{
	private $error = array();
	private $warning = array();
    private $xml;
	private $avito_good_type = array(
			"Ремонт и строительство" => array(
				"Двери",
				"Инструменты",
				"Камины и обогреватели",
				"Окна и балконы",
				"Потолки",
				"Садовая техника",
				"Сантехника и сауна",
				"Стройматериалы"
			),
			"Мебель и интерьер" => array(
				"Компьютерные столы и кресла",
				"Кровати, диваны и кресла",
				"Кухонные гарнитуры",
				"Освещение",
				"Подставки и тумбы",
				"Предметы интерьера, искусство",
				"Столы и стулья",
				"Текстиль и ковры",
				"Шкафы и комоды",
				"Другое"
			),
			"Бытовая техника" => array(
				"Пылесосы",
				"Стиральные машины",
				"Утюги",
				"Швейные машины",
				"Бритвы и триммеры",
				"Машинки для стрижки",
				"Фены и приборы для укладки",
				"Эпиляторы",
				"Вытяжки",
				"Мелкая кухонная техника",
				"Микроволновые печи",
				"Плиты",
				"Посудомоечные машины",
				"Холодильники и морозильные камеры",
				"Вентиляторы",
				"Кондиционеры",
				"Обогреватели",
				"Очистители воздуха",
				"Термометры и метеостанции",
				"Другое"
			),
			"Посуда и товары для кухни" => array(
				"Посуда",
				"Товары для кухни"
			),
			"Растения" => array(),
			"Телефоны" => array(
				"Acer",
				"Alcatel",
				"ASUS",
				"Blackberry",
				"BQ",
				"DEXP",
				"Explay",
				"Fly",
				"Highscreen",
				"HTC",
				"Huawei",
				"iPhone",
				"Lenovo",
				"LG",
				"Meizu",
				"Micromax",
				"Microsoft",
				"Motorola",
				"MTS",
				"Nokia",
				"Panasonic",
				"Philips",
				"Prestigio",
				"Samsung",
				"Siemens",
				"SkyLink",
				"Sony",
				"teXet",
				"Vertu",
				"Xiaomi",
				"ZTE",
				"Другие марки",
				"Номера и SIM-карты",
				"Рации",
				"Стационарные телефоны",
				"Аккумуляторы",
				"Гарнитуры и наушники",
				"Зарядные устройства",
				"Кабели и адаптеры",
				"Модемы и роутеры",
				"Запчасти",
				"Чехлы и плёнки"
			),
			"Аудио и видео" => array(
				"MP3-плееры",
				"Акустика, колонки, сабвуферы",
				"Видео, DVD и Blu-ray плееры",
				"Видеокамеры",
				"Кабели и адаптеры",
				"Микрофоны",
				"Музыка и фильмы",
				"Музыкальные центры, магнитолы",
				"Наушники",
				"Телевизоры и проекторы",
				"Усилители и ресиверы",
				"Аксессуары"
				),
			"Товары для компьютера" => array(
				"Акустика",
				"Веб-камеры",
				"Джойстики и рули",
				"Клавиатуры и мыши",
				"CD, DVD и Blu-ray приводы",
				"Блоки питания",
				"Видеокарты",
				"Жёсткие диски",
				"Звуковые карты",
				"Контроллеры",
				"Корпусы",
				"Материнские платы",
				"Оперативная память",
				"Процессоры",
				"Системы охлаждения",
				"Мониторы",
				"Переносные жёсткие диски",
				"Сетевое оборудование",
				"ТВ-тюнеры",
				"Флэшки и карты памяти",
				"Аксессуары"
			),	
			"Фототехника" => array(
				"Компактные фотоаппараты",
				"Зеркальные фотоаппараты",
				"Плёночные фотоаппараты",
				"Бинокли и телескопы",
				"Объективы",
				"Оборудование и аксессуары"
			),
			"Игры, приставки и программы" => array(
				"Игровые приставки",
				"Игры для приставок",
				"Программы",
				"Компьютерные игры"
			),
			"Оргтехника и расходники" => array(
				"МФУ, копиры и сканеры",
				"Принтеры",
				"Телефония",
				"ИБП, сетевые фильтры",
				"Уничтожители бумаг",
				"Блоки питания и батареи",
				"Болванки",
				"Бумага",
				"Кабели и адаптеры",
				"Картриджи",
				"Канцелярия"
			),		
			"Планшеты и электронные книги" => array(
				"Планшеты",
				"Электронные книги",
				"Аксессуары"
			),
			"Ноутбуки" => array(
				"Acer",
				"Apple",
				"ASUS",
				"Compaq",
				"Dell",
				"Fujitsu",
				"HP",
				"Huawei",
				"Lenovo",
				"MSI",
				"Packard Bell",
				"Microsoft",
				"Samsung",
				"Sony",
				"Toshiba",
				"Xiaomi",
				"Другой"
			),
			"Настольные компьютеры" => array(),
			"Одежда, обувь, аксессуары" => array(
				"Женская одежда / Брюки",
				"Женская одежда / Верхняя одежда",
				"Женская одежда / Джинсы",
				"Женская одежда / Купальники",
				"Женская одежда / Нижнее бельё",
				"Женская одежда / Обувь",
				"Женская одежда / Пиджаки и костюмы",
				"Женская одежда / Платья и юбки",
				"Женская одежда / Рубашки и блузки",
				"Женская одежда / Свадебные платья",
				"Женская одежда / Топы и футболки",
				"Женская одежда / Трикотаж",
				"Женская одежда / Другое",
				"Мужская одежда / Брюки",
				"Мужская одежда / Верхняя одежда",
				"Мужская одежда / Джинсы",
				"Мужская одежда / Обувь",
				"Мужская одежда / Пиджаки и костюмы",
				"Мужская одежда / Рубашки",
				"Мужская одежда / Трикотаж и футболки",
				"Мужская одежда / Другое",
				"Аксессуары"
			),
			"Детская одежда и обувь" => array(
				"Для девочек / Брюки",
				"Для девочек / Верхняя одежда",
				"Для девочек / Комбинезоны и боди",
				"Для девочек / Обувь",
				"Для девочек / Пижамы",
				"Для девочек / Платья и юбки",
				"Для девочек / Трикотаж",
				"Для девочек / Шапки, варежки, шарфы",
				"Для девочек / Другое",
				"Для мальчиков / Брюки",
				"Для мальчиков / Верхняя одежда",
				"Для мальчиков / Комбинезоны и боди",
				"Для мальчиков / Обувь",
				"Для мальчиков / Пижамы",
				"Для мальчиков / Трикотаж",
				"Для мальчиков / Шапки, варежки, шарфы",
				"Для мальчиков / Другое"
			),
			"Товары для детей и игрушки" => array(
				"Автомобильные кресла",
				"Велосипеды и самокаты",
				"Детская мебель",
				"Детские коляски",
				"Игрушки",
				"Постельные принадлежности",
				"Товары для кормления",
				"Товары для купания",
				"Товары для школы"
			),
			"Часы и украшения" => array(
				"Бижутерия",
				"Часы",
				"Ювелирные изделия"
			),
			"Красота и здоровье" => array(
				"Косметика",
				"Парфюмерия",
				"Приборы и аксессуары",
				"Средства гигиены",
				"Средства для волос",
				"Средства для похудения",
				"Биологически активные добавки"
			)
		);
    public function index()
    {
        $this->load->language('feed/avitoexport');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
		$this->load->model('feed/avitoexport');
		
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('avitoexport', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_update_success');
            $this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        $data['heading_title']        		  = $this->language->get('heading_title');
        $data['text_module']    			  = $this->language->get('text_module');
        
        $data['button_save']  				  = $this->language->get('button_save');
        $data['button_cancel'] 		          = $this->language->get('button_cancel');
        $data['text_yes']     				  = $this->language->get('text_yes');
        $data['text_no']       				  = $this->language->get('text_no');
        $data['text_enabled']     				  = $this->language->get('text_enabled');
        $data['text_disabled']       				  = $this->language->get('text_disabled');

		$data['text_avitoexport_head']  		= $this->language->get('text_avitoexport_head');
		$data['text_avitoexport_enable']        = $this->language->get('text_avitoexport_enable');
		$data['text_avitoexport_allowEmail']    = $this->language->get('text_avitoexport_allowEmail');
		$data['text_avitoexport_name']          = $this->language->get('text_avitoexport_name');
		$data['text_avitoexport_phone']         = $this->language->get('text_avitoexport_phone');
		$data['text_avitoexport_region']        = $this->language->get('text_avitoexport_region');
		$data['text_avitoexport_subway']        = $this->language->get('text_avitoexport_subway');
		$data['text_avitoexport_district']      = $this->language->get('text_avitoexport_district');
		$data['text_avitoexport_city']          = $this->language->get('text_avitoexport_city');
		$data['text_avitoexport_categ']         = $this->language->get('text_avitoexport_categ');
		$data['text_avitoexport_stock']         = $this->language->get('text_avitoexport_stock');
		$data['text_avitoexport_package']       = $this->language->get('text_avitoexport_package');
		$data['text_avitoexport_stat']          = $this->language->get('text_avitoexport_stat');
		$data['text_avitoexport_ignore']        = $this->language->get('text_avitoexport_ignore');
		$data['text_avitoexport_delete']        = $this->language->get('text_avitoexport_delete');
		$data['text_avitoexport_feed'] 		  	= $this->language->get('text_avitoexport_feed');
		$data['text_avitoexport_adtype'] 		= $this->language->get('text_avitoexport_adtype');

		$data['val_avitoexport_undefined']      = $this->language->get('val_avitoexport_undefined');

		$data['section_avitoexport_contact']    = $this->language->get('section_avitoexport_contact');
		$data['section_avitoexport_location']   = $this->language->get('section_avitoexport_location');
		$data['section_avitoexport_categories'] = $this->language->get('section_avitoexport_categories');
		$data['section_avitoexport_settings']   = $this->language->get('section_avitoexport_settings');

		$data['step_avitoexport_one']        	  = $this->language->get('step_avitoexport_one');
		$data['step_avitoexport_two']           = $this->language->get('step_avitoexport_two');
		$data['step_avitoexport_three']         = $this->language->get('step_avitoexport_three');

		$data['hint_avitoexport_categ']         = $this->language->get('hint_avitoexport_categ');
		$data['hint_avitoexport_pack_package']  = $this->language->get('hint_avitoexport_pack_package');
		$data['hint_avitoexport_pack_pSingle']  = $this->language->get('hint_avitoexport_pack_pSingle');
		$data['hint_avitoexport_pack_single']   = $this->language->get('hint_avitoexport_pack_single');
		$data['hint_avitoexport_ignore']        = $this->language->get('hint_avitoexport_ignore');
		$data['hint_avitoexport_delete']        = $this->language->get('hint_avitoexport_delete');
        
		$data['feed']  	 	  = HTTP_CATALOG . 'index.php?route=feed/avitoexport';
        $data['regions']  	  = $this->model_feed_avitoexport->getRegions();	
		
		$data["avito_category"] = array(
			"Для дома и дачи" 	=> array(
				"Ремонт и строительство",
				"Мебель и интерьер",
				"Бытовая техника",
				"Посуда и товары для кухни",
				"Растения"),
			"Бытовая электроника"	=> array(
				"Телефоны",
				"Аудио и видео",
				"Товары для компьютера",
				"Фототехника",
				"Игры, приставки и программы",
				"Оргтехника и расходники",
				"Планшеты и электронные книги",
				"Ноутбуки",
				"Настольные компьютеры"),
			"Личные вещи"	=> array(
				"Одежда, обувь, аксессуары",
				"Детская одежда и обувь",
				"Товары для детей и игрушки",
				"Часы и украшения",
				"Красота и здоровье")
		);
		$data['categories']     = $this->model_feed_avitoexport->getCategories();
		$data['subcategories']  = array();

		foreach($data['categories'] as $category){
			$data['subcategories'][$category['category_id']] = $this->model_feed_avitoexport->getCategories($category['category_id']);
		}

		$data['subsubcategories']  = array();
		foreach($data['subcategories'] as $subcategory){
			foreach($subcategory as $subcat){
				$data['subsubcategories'][$subcat['category_id']] = $this->model_feed_avitoexport->getCategories($subcat['category_id']);
			}
		}
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('feed/avitoexport', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        		
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

		if (isset($this->error['error_name'])) {
            $data['error_name'] = $this->error['error_name'];
        } else {
            $data['error_name'] = '';
        }

		if (isset($this->error['error_phone'])) {
            $data['error_phone'] = $this->error['error_phone'];
        } else {
            $data['error_phone'] = '';
        }
		
		if (isset($this->error['error_region'])) {
            $data['error_region'] = $this->error['error_region'];
        } else {
            $data['error_region'] = '';
        }
		
		if (isset($this->error['error_location_city'])) {
            $data['error_location_city'] = $this->error['error_location_city'];
        } else {
            $data['error_location_city'] = '';
        }
		
		if (isset($this->error['error_location_subway'])) {
            $data['error_location_subway'] = $this->error['error_location_subway'];
        } else {
            $data['error_location_subway'] = '';
        }

		if (isset($this->warning['warning_category'])) {
            $data['warning_category'] = $this->warning['warning_category'];
        } else {
            $data['warning_category'] = '';
        }
        
        if (isset($this->request->post['avitoexport_status'])) {
            $data['avitoexport_status'] = $this->request->post['avitoexport_status'];
        } else {
            $data['avitoexport_status'] = $this->config->get('avitoexport_status');
        }	
		
        if (isset($this->request->post['avitoexport_contact_mail'])) {
            $data['avitoexport_contact_mail'] = $this->request->post['avitoexport_contact_mail'];
        } elseif (!is_null($this->config->get('avitoexport_contact_mail'))){
			$data['avitoexport_contact_mail'] = $this->config->get('avitoexport_contact_mail');
		} else {
			$data['avitoexport_contact_mail'] = 1;			
        }

        if (isset($this->request->post['avitoexport_contact_name'])) {
            $data['avitoexport_contact_name'] = $this->request->post['avitoexport_contact_name'];
        } else {
            $data['avitoexport_contact_name'] = $this->config->get('avitoexport_contact_name');
        }

        if (isset($this->request->post['avitoexport_contact_phone'])) {
            $data['avitoexport_contact_phone'] = $this->request->post['avitoexport_contact_phone'];
        } else {
            $data['avitoexport_contact_phone'] = $this->config->get('avitoexport_contact_phone');
        }

        if (isset($this->request->post['avitoexport_location_region'])) {
            $data['avitoexport_location_region'] = $this->request->post['avitoexport_location_region'];
        } else {
            $data['avitoexport_location_region'] = $this->config->get('avitoexport_location_region');
        }

        if (isset($this->request->post['avitoexport_adtype'])) {
            $data['avitoexport_adtype'] = $this->request->post['avitoexport_adtype'];
        } else {
            $data['avitoexport_adtype'] = $this->config->get('avitoexport_adtype');
        }

        if (isset($this->request->post['avitoexport_location_city'])) {
            $data['avitoexport_location_city'] = $this->request->post['avitoexport_location_city'];
			$data['cities'] = $this->model_feed_avitoexport->getCities($data['avitoexport_location_region']);
			
        } else {
            $data['avitoexport_location_city'] = $this->config->get('avitoexport_location_city');
			$data['cities'] = $this->model_feed_avitoexport->getCities($data['avitoexport_location_region']);
        }

        if (isset($this->request->post['avitoexport_location_district'])) {
            $data['avitoexport_location_district'] = $this->request->post['avitoexport_location_district'];
			$data['city_child'] = $this->model_feed_avitoexport->getCChilds($data['avitoexport_location_city']);
        } else {
            $data['avitoexport_location_district'] = $this->config->get('avitoexport_location_district');
			$data['city_child'] = $this->model_feed_avitoexport->getCChilds($data['avitoexport_location_city']);
        }

        if (isset($this->request->post['avitoexport_location_subway'])) {
            $data['avitoexport_location_subway'] = $this->request->post['avitoexport_location_subway'];
        } else if(in_array($data['avitoexport_location_region'],array("637640","653240"))) {
            $data['avitoexport_location_subway'] = $this->config->get('avitoexport_location_subway');
			$data['city_child'] = $this->model_feed_avitoexport->getCChilds($data['avitoexport_location_region']);			
		} else {
            $data['avitoexport_location_subway'] = $this->config->get('avitoexport_location_subway');
			$data['city_child'] = $this->model_feed_avitoexport->getCChilds($data['avitoexport_location_city']);			
        }

		if (isset($this->request->post['avitoexport_listing_fee'])) {
            $data['avitoexport_listing_fee'] = $this->request->post['avitoexport_listing_fee'];
        } else {
            $data['avitoexport_listing_fee'] = $this->config->get('avitoexport_listing_fee');
        }

		if (isset($this->request->post['avitoexport_service'])) {
            $data['avitoexport_service'] = $this->request->post['avitoexport_service'];
        } else {
            $data['avitoexport_service'] = $this->config->get('avitoexport_service');
        }

		if (isset($this->request->post['avitoexport_stock'])) {
            $data['avitoexport_stock'] = $this->request->post['avitoexport_stock'];
        } else {
			$data['avitoexport_stock'] = $this->config->get('avitoexport_stock');			
        }		

		if (isset($this->request->post['avitoexport_dependence_name_from'])) {
            $data['avitoexport_dependence_name_from'] = $this->request->post['avitoexport_dependence_name_from'];
        } elseif(!is_null($this->config->get('avitoexport_dependence_name_from'))){
            $data['avitoexport_dependence_name_from'] = $this->config->get('avitoexport_dependence_name_from');
        } else {
			$data['warning_category'] = $this->language->get('warning_category');
		}

		if (isset($this->request->post['avitoexport_dependence_id_from'])) {
            $data['avitoexport_dependence_id_from'] = $this->request->post['avitoexport_dependence_id_from'];
        } else {
            $data['avitoexport_dependence_id_from'] = $this->config->get('avitoexport_dependence_id_from');
        }

		if (isset($this->request->post['avitoexport_dependence_to'])) {
            $data['avitoexport_dependence_to'] = $this->request->post['avitoexport_dependence_to'];
        } else {
            $data['avitoexport_dependence_to'] = $this->config->get('avitoexport_dependence_to');
        }

		if (isset($this->request->post['avitoexport_ignore'])) {
            $data['avitoexport_ignore'] = $this->request->post['avitoexport_ignore'];
        } else {
            $data['avitoexport_ignore'] = $this->config->get('avitoexport_ignore');
        }

		if (isset($this->request->post['avitoexport_delete'])) {
            $data['avitoexport_delete'] = $this->request->post['avitoexport_delete'];
        } else {
            $data['avitoexport_delete'] = $this->config->get('avitoexport_delete');
        }

        $data['action'] = $this->url->link('feed/avitoexport', 'token=' . $this->session->data['token'], 'SSL');
        $data['getLocation'] = $this->url->link('feed/avitoexport/getLocation', 'token=' . $this->session->data['token'], 'SSL');
		$data['getTypeFromCategory'] = $this->url->link('feed/avitoexport/getTypeFromCategory', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
        $this->response->setOutput($this->load->view('feed/avitoexport.tpl', $data));
    }
	public function getTypeFromCategory(){
		$types = $this->avito_good_type[$this->request->post['categoryKey']];
		$response = "";
		if(!empty($types))
			foreach($types as $key => $type_name){
				$response .= '<option ' . ($key == 0 ? "selected" : "") . '>' . $type_name . '</option>';
			} 
		else $response = false;
		$this->response->setOutput($response);
	}
    public function getLocation(){
		$this->load->model('feed/avitoexport');
		$response = ""; 
		$request = isset($this->request->post['Id']) && in_array($this->request->post['Id'],array("637640","653240")) ? "city" : $this->request->post['type']; 
		switch ($request){
			case "region": 

				$regionId = $this->request->post['Id'];
				$cities = ($this->model_feed_avitoexport->getCities($regionId));
				$response =<<<HTML
					
						<div class="form-group" id="city">
							<label class="col-sm-2 control-label">Город: </label>
							<div class="col-sm-10">
								<select class="form-control" name="avitoexport_location_city" type="text">
									<option value="0" selected> -- Не выбрано --</option>
HTML;
				foreach($cities as $city){
					$response .= "<option value=\"" . $city['CityID'] . "\">" . $city['CityName'] . "</option>";
				}
				$response .=<<<HTML
								</select>
							</div>
						</div>
HTML;
				break;
			case "city":
				$cityId = $this->request->post['Id'];
				$cc = ($this->model_feed_avitoexport->getCChilds($cityId));
				if(!count($cc)) $response = "";
				else {
					$type = strtolower($cc[0]["CityChildType"]);
					$text = $type == "district" ? "Район: " : "Ближайшая станция метро: ";
					
					$response =<<<HTML
						
						<div class="form-group" id="cityChild">
							<label class="col-sm-2 control-label" >{$text}</label>
							<div class="col-sm-10">
									<select class="form-control" name="avitoexport_location_{$type}" type="text">
										<option value="0" selected> -- Не выбрано --</option>
HTML;
					foreach($cc as $c){
						$response .= "<option value=\"" . $c['CityChildID'] . "\">" . $c['CityChildName'] . "</option>";
					}
					$response .=<<<HTML
									</select>
							</div>
						</div>
HTML;
				}
				break;
		}
        $this->response->setOutput($response);
    }
	public function install(){
		$this->load->model('feed/avitoexport');
		$creatingTables = $this->model_feed_avitoexport->createTable();
		if($creatingTables){
			$error_text = '<meta charset="utf-8">';
			$error_text .= '<span style="font-size:17px;color:red;">При установке произошла ошибка! bd2d9</span>';
			$xmlString = file_get_contents('http://autoload.avito.ru/format/Locations.xml');
			
			$xml = simplexml_load_string($xmlString);
			foreach ($xml->Region as $region) {
				if(isset($region->City)) {
					foreach($region->City as $city){
						if(count ($city) != 0 ){
							if(isset($city->District)){
								foreach($city->District as $d){
									$flag = $this->model_feed_avitoexport->put($region["Name"],$region["Id"],$city['Id'],$city['Name'],$d['Id'],'District',$d['Name']);
									if(!$flag) {
										echo $error_text; exit;
									}
								}
							}
							if(isset($city->Subway)){
								foreach($city->Subway as $s){
									$flag = $this->model_feed_avitoexport->put($region["Name"],$region["Id"],$city['Id'],$city['Name'],$s['Id'],'Subway',$s['Name']);
									if(!$flag) {
										echo $error_text; exit;
									}
								}
							} 
							if(isset($city->DirectionRoad)){
								foreach($city->DirectionRoad as $dr){
									$flag = $this->model_feed_avitoexport->put($region["Name"],$region["Id"],$city['Id'],$city['Name'],$dr['Id'],'DirectionRoad',$dr['Name']);
									if(!$flag) {
										echo $error_text; exit;
									}
								}
							}
						} else{
							$flag = $this->model_feed_avitoexport->put($region["Name"],$region["Id"],$city['Id'],$city['Name'],-1,'none','none');
							if(!$flag) {
								echo $error_text; exit;
							}
						}
					}
				}
						if(isset($region->District)){
							foreach($region->District as $d){
								$flag = $this->model_feed_avitoexport->put($region["Name"],$region["Id"],$region["Id"],'except',$d['Id'],'District',$d['Name']);
								if(!$flag) {
									echo $error_text; exit;
								}
							}
						}
						if(isset($region->Subway)){
							foreach($region->Subway as $s){
								$flag = $this->model_feed_avitoexport->put($region["Name"],$region["Id"],$region["Id"],'except',$s['Id'],'Subway',$s['Name']);
								if(!$flag) {
									echo $error_text; exit;
								}
							}
						} 
						if(isset($region->DirectionRoad)){
							foreach($region->DirectionRoad as $dr){
								$flag = $this->model_feed_avitoexport->put($region["Name"],$region["Id"],$region["Id"],'except',$dr['Id'],'DirectionRoad',$dr['Name']);
								if(!$flag) {
									echo $error_text; exit;
								}
							}
						}
					
			}
			
		} else {
			echo '<span class="error">Ошибка при создании таблиц!</span>';exit;
		}
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('avitoexport',array('avitoexport_status'=>0));
	}
	public function uninstall(){
		$this->load->model('feed/avitoexport');
		$this->model_feed_avitoexport->deleteTable();
		
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('avitoexport',array('avitoexport_status'=>0));
	}
    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'feed/avitoexport')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

		if(empty($this->request->post['avitoexport_contact_name'])){
			$this->error['error_name'] = $this->language->get('error_name');
		}
		if(strlen($this->request->post['avitoexport_contact_phone']) < 18){
			$this->error['error_phone'] = $this->language->get('error_phone');
		}
		if(empty($this->request->post['avitoexport_location_region'])){
			$this->error['error_region'] = $this->language->get('error_region');
		} 
		if(empty($this->request->post['avitoexport_dependence_name_from'])){
			$this->warning['warning_category'] = $this->language->get('warning_category');
		}

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
