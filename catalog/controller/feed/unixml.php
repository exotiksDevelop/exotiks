<?php
class ControllerFeedUnixml extends Controller {

  public $ext = 'unixml';
  public $path = 'feed/unixml';
  public $model_path = 'model_feed_unixml';
  public $feed = '';
  public $secret = '';
  public $start = '';
  public $mem_start = '';
  public $step = '10000';
  public $final_xml = '';
  public $log = false;
  public $count_product = 0;

  public function feeds() {
    $feeds = array();
    $unixml_dat = explode(PHP_EOL, file_get_contents($this->request->server['DOCUMENT_ROOT'] . '/price/' . $this->ext . '.dat'));
    foreach(explode(',', $unixml_dat[0]) as $feed){
      if($feed){
        $feeds[] = trim($feed);
      }
    }
    return $feeds;
  }

  public function index() { //вывод всех выгрузок
    $html = '<div style="border:2px dashed red;padding:15px;margin:100px auto;max-width:500px;text-align:center;background:gold;">';
      $html .= '<h3>Модуль выгрузок UniXML</h3>';
      $html .= '<ul style="text-align:left;">';
        foreach($this->feeds() as $feed_item){
          $html .= '<li><a style="color:#333;" href="' . $this->url->link($this->path . '/' . $feed_item) . '" title="Открыть выгрузку' . $feed_item . '">Выгрузка xml в <b>' . $feed_item . '</b></a></li>';
        }
      $html .= '</ul>';
    $html .= '</div>';

    $this->response->setOutput($html);
  }

  private function varname($var) {
    return $this->ext . '_' . $this->feed . '_' . $var;
  }

  public function startup($only_product = array()) {

    if(!$only_product){
      $this->start = microtime(true);
      $this->mem_start = memory_get_usage();
    }

    if($this->feed){

      set_time_limit(12000);
      ini_set('max_execution_time', 12000);

      $this->load->model($this->path);
      $mp = $this->{$this->model_path};

      foreach($mp->getAllVars() as $config_item){
        $data[$config_item] = ${$this->varname($config_item)} = $this->config->get($this->varname($config_item));
      }

      $data['status'] = ${$this->varname('status')};

      if (${$this->varname('status')}) {
        $categories = array();

        if(${$this->varname('products')} && !${$this->varname('products_mode')}){ //если выбраны только товары
          ${$this->varname('categories')} = $mp->getProductsCategories(${$this->varname('products')}, ${$this->varname('quantity')});
        }

        if(${$this->varname('manufacturers')} && ${$this->varname('andor')}){ //если выбраны бренды + плюсовать категории брендов
          $manufacturer_cats = $mp->getProductsManufacturers(${$this->varname('manufacturers')}, ${$this->varname('quantity')});

          if(${$this->varname('andor')} && $manufacturer_cats){ //+ выбранные категории брендов
            $cats = array();

            if(${$this->varname('categories')}){
              $cats_array = explode(',', ${$this->varname('categories')});
              foreach($cats_array as $cat){
                $cats[$cat] = $cat;
              }
            }

            if($manufacturer_cats){
              $cats_array = explode(',', $manufacturer_cats);
              $cats = array();
              foreach($cats_array as $cat){
                $cats[$cat] = $cat;
              }
            }

            ${$this->varname('categories')} = implode(",", $cats);

          } //+ выбранные категории брендов

        }

        //fix empty categories
        if(!${$this->varname('categories')}){
          ${$this->varname('categories')} = $mp->getAllCategories();
        }
        //fix empty categories

        $data['category_match'] = ${$this->varname('category_match')};

        $data['currency'] = $mp->getCurrencyCode(${$this->varname('currency')});

        $data['categories'] = array();
        if(${$this->varname('categories')}){
          $data['categories'] = $mp->getCategories(${$this->varname('categories')}, ${$this->varname('language')}, ${$this->varname('category_match')}, $this->feed);
        }

        $filter_data = array(
          'allowed_categories' => ${$this->varname('categories')},
          'allowed_manufacturers' => ${$this->varname('manufacturers')},
          'custom_products' => ${$this->varname('products')},
          'products_mode' => ${$this->varname('products_mode')},
          'lang' => ${$this->varname('language')},
          'quantity' => ${$this->varname('quantity')},
          'multiply_options_status' => ${$this->varname('option_multiplier_status')},
          'option_multiplier_id' => ${$this->varname('option_multiplier_id')},
          'genname' => ${$this->varname('genname')},
          'gendesc' => ${$this->varname('gendesc')},
          'gendesc_mode' => ${$this->varname('gendesc_mode')},
          'currency' => ${$this->varname('currency')},
          'stock' => ${$this->varname('stock')},
          'attribute_status' => ${$this->varname('attribute_status')},
          'andor' => ${$this->varname('andor')},
          'images' => ${$this->varname('images')},
          'image' => ${$this->varname('image')},
          'markup' => ${$this->varname('markup')},
          'fields' => ${$this->varname('fields')},
          'seopro' => ${$this->varname('seopro')},
          'utm' => ${$this->varname('utm')},
          'field_price' => ${$this->varname('field_price')},
          'field_id' => ${$this->varname('field_id')},
          'clear_desc' => ${$this->varname('clear_desc')},
          'only_product' => $only_product,
          'feed' => $this->feed
        );
        $data['products'] = $mp->getProducts($filter_data);

        if($only_product){
          return $data['products'];
        }

        $this->step = (int)${$this->varname('step')};
        if($this->step < 1){
          $this->step = 1;
        }

        if(${$this->varname('log')}){
          $this->log = ${$this->varname('log')};
        }

        if(${$this->varname('secret')}){ //если есть защита по get параметру
          if(!isset($this->request->get['key'])){
            $this->request->get['key'] = '';
          }
          if($this->request->get['key'] != ${$this->varname('secret')}){
            if($this->request->get['key']){
              $this->log->write("Выгрузка UniXML - неправильный секретный ключ " . $this->request->get['key'] . " к выгрузке");
            }
            header("Location: /",TRUE,302);
            exit();
          }
        }

      }

      return $data;
    }
  }

  private function to_log($flag) {
    if($this->log){
      if($flag == "start"){
        $log_text = 'Выгрузка: ' . $this->feed . ' (xml успешно сформирован)' . "\r\n";
        $log_text.= 'Время запуска: ' . date('d.m.Y h:i') . "\r\n";
        $log_text.= 'Память до: '.round(($this->mem_start/1024/1024), 3).' МБ.' . "\r\n";
      }
      if($flag == "finish"){
        $log_text = 'Память во время: '.round(((memory_get_usage())/1024/1024), 3).' МБ.' . "\r\n";
        $log_text.= 'Время генерации xml: '.round(microtime(true) - $this->start, 4).' сек.' . "\r\n";
        $log_text.= 'Всего товаров в файле: '.$this->count_product . "\r\n";
        $log_text.= 'Память для генерации: '.round(((memory_get_usage() - $this->mem_start)/1024/1024), 3).' МБ.' . "\r\n-------\r\n";
      }
      $log_file = fopen(DIR_LOGS . $this->log, 'a');
      fwrite($log_file, $log_text);
      fclose($log_file);
    }
  }

  private function filesize($file){
    if(!file_exists($file)) return "Файл  не найден";
    $filesize = filesize($file);
    if($filesize > 1024){
      $filesize = ($filesize/1024);
      if($filesize > 1024){
        $filesize = ($filesize/1024);
        if($filesize > 1024) {
          $filesize = ($filesize/1024);
          $filesize = round($filesize, 1);
          return $filesize." ГБ";
        } else {
          $filesize = round($filesize, 1);
          return $filesize." MБ";
        }
      } else {
        $filesize = round($filesize, 1);
        return $filesize." Кб";
      }
    } else {
      $filesize = round($filesize, 1);
      return $filesize." байт";
    }
  }

  private function to_xml($xml, $flag = false, $xml_type = false) {

    if($xml){
      $xml = str_replace('><', '>' . PHP_EOL . '<', $xml);

      if(!isset($this->request->get['cron'])){ //view in browser
        if($flag == "start"){
          $this->final_xml = $xml;
          $this->to_log("start");
        }else{
          $this->final_xml .= $xml;
        }

        if($flag == "finish"){
          $this->to_log("finish");

          $xml_type = 'xml';

          $this->response->addHeader('Content-Type: application/' . $xml_type);
          $this->response->setOutput($this->final_xml);
        }
      }else{ //write to file
        if($flag == "start"){
          echo '<div style="font:15px/22px Arial;border:2px dashed red;padding:15px;margin:100px auto;max-width:500px;text-align:center;background:#FAF9EF;">';
          echo "<h3>xml для " . $this->feed . " успешно сформирован!</h3>";
          echo 'Память до генерации: '.round(($this->mem_start/1024/1024), 3).' МБ.<br>';
          $this->to_log("start");
        }

        $mode = 'a';
        if($flag == "start"){
          $mode = 'w';
        }

        $directory_xml = str_replace('system/', 'price', DIR_SYSTEM);

        if (!file_exists($directory_xml)) { //если нет директории создаем
					mkdir($directory_xml, 0777, true);
				}

        $xml_file = fopen($directory_xml . '/' . $this->feed . '.xml', $mode);
        fwrite($xml_file, $xml);
        fclose($xml_file);

        if($flag == "finish"){
          $filelink = HTTP_SERVER . 'price/' . $this->feed . '.xml';
          echo 'Время генерации xml: '.round(microtime(true) - $this->start, 4).' сек.<br>';
          echo 'Потребляемая опаративная память: '.round(((memory_get_usage())/1024/1024), 3).' МБ.<br>';
          echo 'Память для генерации: '.round(((memory_get_usage() - $this->mem_start)/1024/1024), 3).' МБ.<br>';
          echo 'Всего товаров в файле: '.$this->count_product.'<br>';
          echo 'Файл выгрузки: <a style="color:#555;" href="' . $filelink . '" title="Откроется в новом окне" target="_blank">' . $filelink . '</a><br>';
          echo 'Размер файла: ' . $this->filesize(str_replace('system/', 'price/', DIR_SYSTEM) . $this->feed . '.xml') . '<br>';
          echo '<hr>';
          echo 'Поддержка модуля info@microdata.pro<br><br>';
          echo '</div>';
          $this->to_log("finish");
        }
      }
    } else {
      $this->response->setOutput("<div style='border:2px dashed red;padding:15px;margin:100px auto;max-width:500px;text-align:center;background:gold;'><h2>Выгрузка " . $this->feed . " выключена в настройках модуля UNIXML</h2></div>"); //выключена выгрузка
    }
  }

  //1 rozetka xml
  public function rozetka() {
    $this->feed = 'rozetka';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<name>' . $startup['name'] . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<platform>Opencart</platform>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }
      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              if($product['special']){
                $xml .= '<price_old>' . $product['price'] . '</price_old>';
                $xml .= '<price>' . $product['special'] . '</price>';
              }else{
                $xml .= '<price>' . $product['price'] . '</price>';
              }
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<stock_quantity>' . $product['quantity'] .  '</stock_quantity>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = PHP_EOL . '</offers>' . PHP_EOL;
      $xml .= '</shop>' . PHP_EOL;
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //1 rozetka xml

  //2 hotline xml
  public function hotline() {
    $this->feed = 'hotline';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<price>';
      $xml .= '<date>' . date('Y-m-d H:i', time()) . '</date>';
      $xml .= '<firmName>' . $startup['name'] . '</firmName>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          $xml .= '<category>';
          $xml .= '<id>' . $category['category_id'] . '</id>';
          if($category['parent_id']){
            $xml .= '<parentId>' . $category['parent_id'] . '</parentId>';
          }
          $xml .= '<name>' . $category['name'] . '</name>';
          $xml .= '</category>';
        }
        $xml .= '</categories>';
      }

      $xml .= '<items>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<item>';
              $xml .= '<id>' . $product_id . '</id>';
              if($startup['option_multiplier_status'] && $startup['option_multiplier_id'] && $product['product_option_id']){
                $xml .= '<group_id>' . $product['product_original_id'] . '</group_id>';
              }
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<code>' . $product['model'] . '</code>';
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<image>' . $product['image'] .  '</image>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<image>' . $image .  '</image>';
                }
              }
              if($product['stock']){
                $xml .= '<stock>' . $product['stock'] .  '</stock>';
              }
              if($product['special']){
                $xml .= '<priceRUAH>' . $product['special'] .  '</priceRUAH>';
                $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
              }else{
                $xml .= '<priceRUAH>' . $product['price'] .  '</priceRUAH>';
              }
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</item>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</items>' . PHP_EOL;
      $xml .= '</price>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //2 hotline xml

  //3 price xml
  public function price() {
    $this->feed = 'price';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<price date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<name>' . $startup['name'] . '</name>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentID="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      $xml .= '<items>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<item id="' . $product_id . '">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<priceuah>' . ($product['special']?$product['special']:$product['price']) .  '</priceuah>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<image>' . $product['image'] .  '</image>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<image>' . $image .  '</image>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</item>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</items>' . PHP_EOL;
      $xml .= '</price>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //3 price xml

  //4 nadavi xml
  public function nadavi() {
    $this->feed = 'nadavi';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0" encoding="utf-8"?>';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<name>' . $startup['name'] . '</name>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<catalog>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</catalog>';
      }

      $xml .= '<items>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<item id="' . $product_id . '">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<image>' . $product['image'] .  '</image>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<image>' . $image .  '</image>';
                }
              }
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              $xml .= '</item>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</items>';
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //4 nadavi xml

  //5 google xml
  public function google() {
    $this->feed = 'google';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0"?>';
      $xml .= '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">';
      $xml .= '<title>' . $startup['name'] . '</title>';
      $xml .= '<link>' . HTTPS_SERVER . '</link>';
      $xml .= '<updated>' . date('Y-m-d H:i', time()) . '</updated>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){

              if($startup['category_match'] && isset($startup['categories'][$product['category_id']]['name'])){ //replace on google category
                $product_category_data = $startup['categories'][$product['category_id']]['name'];
                $product_category_id = explode(' - ', $product_category_data);
                if(isset($product_category_id[0])){
                  $product['category_id'] = (int)$product_category_id[0];
                }
              }else{
                $product['category_id'] = false;
              }

              $xml .= '<entry>';
              $xml .= '<g:title>' . $product['name'] . '</g:title>';
              $xml .= '<g:link>' . $product['url'] .  '</g:link>';
              $xml .= '<g:id>' . $product_id . '</g:id>';
              if($product['special']){
                $xml .= '<g:price>' . $product['price'] . ' ' . $startup['currency'] . '</g:price>';
                $xml .= '<g:sale_price>' . $product['special'] . ' ' . $startup['currency'] . '</g:sale_price>';
              }else{
                $xml .= '<g:price>' . $product['price'] . ' ' . $startup['currency'] . '</g:price>';
              }
              $xml .= '<g:description><![CDATA[' . $product['description'] .  ']]></g:description>';
              if($product['category_id']){
                $xml .= '<g:google_product_category>' . $product['category_id'] . '</g:google_product_category>';
              }
              $xml .= '<g:brand>' . html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8') . '</g:brand>';
              $xml .= '<g:condition>new</g:condition>';
              $xml .= '<g:image_link>' . $product['image'] .  '</g:image_link>';
              if($product['images']){
                $product['images'] = array_slice($product['images'], 0, 10);
                foreach($product['images'] as $image){
                  $xml .= '<g:additional_image_link>' . $image .  '</g:additional_image_link>';
                }
              }
              if(isset($product['mpn']) && $product['mpn']){
  							$xml .= '<g:mpn><![CDATA[' . $product['mpn'] . ']]></g:mpn>' ;
  						}
              if(isset($product['upc']) && $product['upc']){
  							$xml .= '  <g:upc>' . $product['upc'] . '</g:upc>';
  						}
              if(isset($product['ean']) && $product['ean']){
  							$xml .= '  <g:ean>' . $product['ean'] . '</g:ean>';
  						}
              if(isset($product['weight']) && isset($product['weight_class_id'])){
                $xml .= '<g:weight>' . $this->weight->format($product['weight'], $product['weight_class_id']) . '</g:weight>';
              }
  						$xml .= '<g:availability>' . ($product['quantity'] ? 'in stock' : 'out of stock') . '</g:availability>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['name'] . '>';
              }
              $xml .= '</entry>' . PHP_EOL;
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</feed>';

      $this->to_xml($xml, "finish", true);
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //5 google xml

  //6 prom xml
  public function prom() {
    $this->feed = 'prom';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd"> ';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $seltype = 'r';
              if(isset($product['prices'])){$seltype = 'u';}
              $group_id = '';
              if($startup['option_multiplier_status'] && $startup['option_multiplier_id'] && $product['product_option_id']){
                $group_id = 'group_id="' . $product['product_original_id'] . '"';
              }
              $stock = $product['stock']?'true':'';
              if(isset($product['custom_stock'])){
                $stock = $product['custom_stock'];
              }
              $xml .= '<offer id="' . $product_id . '" available="' . $stock .'" selling_type="' . $seltype .'" ' . $group_id . '>'; // ' . ($product['stock']?'presence_sure="true"':'') .'
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<vendorCode>' . str_replace(array('10000-','20000-','30000-','40000-','50000-','60000-','70000-','80000-','90000-',),'',$product['model']) .  '</vendorCode>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>UAH</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';

              if($product['special']){
                $xml .= '<price>' . $product['special'] .  '</price>';
                $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
              }else{
                $xml .= '<price>' . $product['price'] .  '</price>';
              }

              if(isset($product['prices'])){
                $xml .= '<prices>';
                foreach($product['prices'] as $price){
                  $xml .= '<price>';
                    $xml .= '<value>' . $price['price'] . '</value>';
                    $xml .= '<quantity>' . $price['quantity'] . '</quantity>';
                  $xml .= '</price>';
                }
                $xml .= '</prices>';
              }

              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              $xml .= '<quantity_in_stock>' . $product['quantity'] .  '</quantity_in_stock>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //6 prom xml

  //7 yandex xml
  public function yandex() {
    $this->feed = 'yandex';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      if($startup['delivery_cost']) {
        $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              if($product['special']){
                $xml .= '<price>' . $product['special'] .  '</price>';
                $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
              }else{
                $xml .= '<price>' . $product['price'] .  '</price>';
              }
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                array_splice($product['images'], 9);
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              if($startup['delivery_cost']) {
                $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
              }
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //7 yandex xml

  //8 cdek xml
  public function cdek() {
    $this->feed = 'cdek';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<?xml version="1.0" encoding="utf-8"?>';
      $xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<platform>Opencart</platform>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<model>' . $product['model'] .  '</model>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              if($product['special']){
                $xml .= '<price>' . $product['special'] .  '</price>';
                $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
              }else{
                $xml .= '<price>' . $product['price'] .  '</price>';
              }
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //8 cdek xml

  //9 goods xml
  public function goods() {
    $this->feed = 'goods';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';
      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      if($startup['delivery_time'] or $startup['delivery_jump']) {
        $xml .= '<shipment-options><option days="' . $startup['delivery_time'] . '" order-before="' . $startup['delivery_jump'] . '"/></shipment-options>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              $xml .= '<outlets>';
              $xml .= '<outlet id="1" instock="' . $product['quantity'] . '"/>';
              $xml .= '</outlets>';
              if($startup['delivery_time'] or $startup['delivery_jump']) {
                $xml .= '<shipment-options><option days="' . $startup['delivery_time'] . '" order-before="' . $startup['delivery_jump'] . '"/></shipment-options>';
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<model>' . $product['model'] . '</model>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //9 goods xml

  //10 mobilluck xml
  public function mobilluck() {
    $this->feed = 'mobilluck';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<name>' . $startup['name'] . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<platform>Opencart</platform>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }
      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<code>' . $product['model'] .  '</code>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<stock_quantity>' . $product['quantity'] .  '</stock_quantity>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = PHP_EOL . '</offers>' . PHP_EOL;
      $xml .= '</shop>' . PHP_EOL;
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //10 mobilluck xml

  //11 allo xml
  public function allo() {
    $this->feed = 'allo';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<name>' . $startup['name'] . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<platform>Opencart</platform>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }
      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<stock_quantity>' . $product['quantity'] .  '</stock_quantity>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = PHP_EOL . '</offers>' . PHP_EOL;
      $xml .= '</shop>' . PHP_EOL;
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //11 allo xml

  //12 fotos xml
  public function fotos() {
    $this->feed = 'fotos';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<price>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentID="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      $xml .= '<items>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<item>';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<art>' . $product_id .  '</art>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              if($product['special']){
                $xml .= '<price>' . $product['special'] .  '</price>';
                $xml .= '<old>' . $product['price'] .  '</old>';
              }else{
                $xml .= '<price>' . $product['price'] .  '</price>';
              }
              $xml .= '<priceCurrency>' . $startup['currency'] . '</priceCurrency>';
              $xml .= '<amount>' . $product['quantity'] .  '</amount>';
              $xml .= '<image>' . $product['image'] .  '</image>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<extraimage>' . $image .  '</extraimage>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</item>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</items>' . PHP_EOL;
      $xml .= '</price>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //12 fotos xml

  //13 privat xml
  public function privat() {
    $this->feed = 'privat';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<name>' . $startup['name'] . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }
      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<stock_quantity>' . $product['quantity'] .  '</stock_quantity>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = PHP_EOL . '</offers>' . PHP_EOL;
      $xml .= '</shop>' . PHP_EOL;
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //13 privat xml

  //14 joom xml
  public function joom() {
    $this->feed = 'joom';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      if($startup['delivery_cost']) {
        $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              if($startup['delivery_cost']) {
                $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
              }
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //14 joom xml

  //15 olx xml
  public function olx() {
    $this->feed = 'olx';
    $xml = false;
    $startup = $this->startup();

    /* все что нашел - жду информацию
    <adverts>
    <advert>
    <category_id>642</category_id>
    <title>String - Advert title, enclosed in CDATA tag</title>
    <description>String - Advert description, enclosed in CDATA tag</description>
    <external_id>String - Id from external system</external_id>
    <external_partner_code>String - Code provided to OLX partners</external_partner_code>
    <region_id>Integer - Id of advert region</region_id>
    <city_id>Integer - Id of advert city</city_id>
    <district_id>Integer - Id of advert district</district_id>
    <coordinates>
    <longitude>String - Longitude</longitude>
    <latitude>String - Latitude</latitude>
    <zoom_level>Integer - Zoom level for the map</zoom_level>
    </coordinates>
    <advertiser_type>business</advertiser_type>
    <contact>
    <person>String person name</person>
    <phone_numbers>Phone numbers(comma separated)</phone_numbers>
    </contact>
    <images>
    <image>String - URL with image</image>
    </images>
    <params>
    <param code="price" required="1" label="Цена">
    <type>*values</type>
    <type_values>
    <value key="free" label="Бесплатно"/>
    <value key="arranged" label="Договорная"/>
    <value key="exchange" label="Обмен"/>
    <value key="price" label="Цена"/>
    </type_values>
    <value>Integer - Advert price</value>
    <currency>*values</currency>
    <currency_values>
    <value key="UAH" label="UAH"/>
    <value key="USD" label="USD"/>
    <value key="EUR" label="EUR"/>
    </currency_values>
    </param>
    <param code="currency" multy="0" required="0">value</param>
    <param code="state" multy="0" required="0" label="Состояние">*values</param>
    <state_values>
    <value key="used" label="Б/у"/>
    <value key="new" label="Новый"/>
    </state_values>
    </params>
    </advert>
    </adverts>
    */

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      if($startup['delivery_cost']) {
        $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              if($startup['delivery_cost']) {
                $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
              }
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //15 olx xml

  //16 beru xml
  public function beru() {
    $this->feed = 'beru';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      if($startup['delivery_cost']) {
        $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              if(isset($product['sku'])){
                $xml .= '<shop-sku>' . $product['sku'] .  '</shop-sku>';
              }
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              if($startup['delivery_cost']) {
                $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
              }
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //16 beru xml

  //17 kidstaff xml
  public function kidstaff() {
    $this->feed = 'kidstaff';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){

              if($startup['category_match'] && isset($startup['categories'][$product['category_id']]['name'])){ //replace on kidstaff category
                $product_category_data = $startup['categories'][$product['category_id']]['name'];
                $product_category_id = explode(' - ', $product_category_data);
                if(isset($product_category_id[0])){
                  $product['category_id'] = (int)$product_category_id[0];
                }
              }else{
                $product['category_id'] = false;
              }

              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<model>' . $product['model'] .  '</model>';
              $xml .= '<warranty>true</warranty>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<picture main="true">' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture main="false">' . $image .  '</picture>';
                }
              }
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              if($product['attributes']){
                $xml .= '<characteristics>';
                foreach($product['attributes'] as $attribute){
                  $xml .= '<characteristic name="' . $attribute['name'] . '">' . $attribute['text'] .  '</characteristic>';
                }
                $xml .= '</characteristics>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //17 kidstaff xml

  //18 bigl xml
  public function bigl() {
    $this->feed = 'bigl';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd"> ';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">'; // presence_sure="true"
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>UAH</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              $xml .= '<available>' . ($product['stock']?'true':'false') .  '</available>';
              $xml .= '<quantity_in_stock>' . $product['quantity'] .  '</quantity_in_stock>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //18 bigl xml

  //19 froot xml
  public function froot() {
    $this->feed = 'froot';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<thefroot_catalog>';
      $xml .= '<date>' . date('Y-m-d H:i', time()) . '</date>';
      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer>';
              $xml .= '<model>' . $product['name'] .  '</model>';
              $xml .= '<sku>' . $product['model'] .  '</sku>';
              $xml .= '<brand>' . $product['manufacturer'] .  '</brand>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<amount>' . $product['quantity'] .  '</amount>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</thefroot_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //19 froot xml

  //20 regmarkets xml
  public function regmarkets() {
    $this->feed = 'regmarkets';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      if($startup['delivery_cost']) {
        $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              if($startup['delivery_cost']) {
                $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
              }
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //20 regmarkets xml

  //21 besplatka xml
  public function besplatka() {
    $this->feed = 'besplatka';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $seltype = 'r';
              if(isset($product['prices'])){$seltype = 'u';}
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'" selling_type="' . $seltype .'">'; // presence_sure="true"
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<vendorCode>' . $product['model'] .  '</vendorCode>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>UAH</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';

              if($product['special']){
                $xml .= '<price>' . $product['special'] .  '</price>';
                $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
              }else{
                $xml .= '<price>' . $product['price'] .  '</price>';
              }

              if(isset($product['prices'])){
                $xml .= '<prices>';
                foreach($product['prices'] as $price){
                  $xml .= '<price>';
                    $xml .= '<value>' . $price['price'] . '</value>';
                    $xml .= '<quantity>' . $price['quantity'] . '</quantity>';
                  $xml .= '</price>';
                }
                $xml .= '</prices>';
              }

              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              $xml .= '<available>' . ($product['stock']?'true':'false') .  '</available>';
              $xml .= '<quantity_in_stock>' . $product['quantity'] .  '</quantity_in_stock>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //21 besplatka xml

  //22 skidochnik xml
  public function skidochnik() {
    $this->feed = 'skidochnik';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<?xml version="1.0" encoding="utf-8"?>';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      if($startup['delivery_cost']) {
        $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              if($product['special']){
                $xml .= '<oldprice>' . $product['price'] . '</oldprice>';
                $xml .= '<price>' . $product['special'] . '</price>';
              }else{
                $xml .= '<price>' . $product['price'] . '</price>';
              }
              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              if($startup['delivery_cost']) {
                $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
              }
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //22 skidochnik xml

  //23 metamarket xml
  public function metamarket() {
    $this->feed = 'metamarket';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<?xml version="1.0" encoding="utf-8"?>';
      $xml .= '<!DOCTYPE e-shop SYSTEM "http://market.meta.ua/market.dtd">';
      $xml .= '<e-shop name="' . $this->config->get('config_name') . '">';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parent="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      $xml .= '<itemlist>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<item id="' . $product_id . '" category="' . $product['category_id'] .  '" priority="777">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<link img="' . $product['image'] .  '" click="' . $product['url'] .  '" />';
              $xml .= '<price cid="' . $startup['currency'] . '">' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              $xml .= '</item>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</itemlist>' . PHP_EOL;
      $xml .= '</e-shop>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //23 metamarket xml

  //24 vcene xml
  public function vcene() {
    $this->feed = 'vcene';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<price date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<name>' . $startup['name'] . '</name>';

      if($startup['categories']) {
        $xml .= '<catalog>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category><id>' . $category['category_id'] .'</id><parentId>' . $category['parent_id'] . '</parentId><name>' . $category['name'] .'</name></category>';
          } else{
            $xml .= '<category><id>' . $category['category_id'] .'</id><name>' . $category['name'] .'</name></category>';
          }
        }
        $xml .= '</catalog>';
      }

      $xml .= '<items>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<item id="' . $product_id . '">';
              $xml .= '<name><![CDATA[' . $product['name'] .  ']]></name>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              if($product['special']){
                $xml .= '<price_old>' . $product['price'] . '</price_old>';
                $xml .= '<price>' . $product['special'] . '</price>';
              }else{
                $xml .= '<price>' . $product['price'] . '</price>';
              }
              $xml .= '<picture><![CDATA[' . $product['image'] .  ']]></picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture><![CDATA[' . $image .  ']]></picture>';
                }
              }
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              $xml .= '</item>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</items>';
      $xml .= '</price>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //24 vcene xml

  //25 obyava xml
  public function obyava() {
    $this->feed = 'obyava';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<name>' . $this->config->get('config_name') . '</name>';
      $xml .= '<company>' . $startup['name'] . '</company>';
      $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';

      if($startup['categories']) {
        $xml .= '<categories>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</categories>';
      }

      $xml .= '<offers>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $seltype = 'r';
              if(isset($product['prices'])){$seltype = 'u';}
              $group_id = '';
              if($startup['option_multiplier_status'] && $startup['option_multiplier_id'] && $product['product_option_id']){
                $group_id = 'group_id="' . $product['product_original_id'] . '"';
              }
              $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'') .'" selling_type="' . $seltype .'" ' . $group_id . '>'; // ' . ($product['stock']?'presence_sure="true"':'') .'
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<vendorCode>' . str_replace(array('10000-','20000-','30000-','40000-','50000-','60000-','70000-','80000-','90000-',),'',$product['model']) .  '</vendorCode>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<currencyId>UAH</currencyId>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';

              if($product['special']){
                $xml .= '<price>' . $product['special'] .  '</price>';
                $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
              }else{
                $xml .= '<price>' . $product['price'] .  '</price>';
              }

              if(isset($product['prices'])){
                $xml .= '<prices>';
                foreach($product['prices'] as $price){
                  $xml .= '<price>';
                    $xml .= '<value>' . $price['price'] . '</value>';
                    $xml .= '<quantity>' . $price['quantity'] . '</quantity>';
                  $xml .= '</price>';
                }
                $xml .= '</prices>';
              }

              $xml .= '<picture>' . $product['image'] .  '</picture>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<picture>' . $image .  '</picture>';
                }
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              $xml .= '<available>' . ($product['stock']?'true':'') .  '</available>';
              $xml .= '<quantity_in_stock>' . $product['quantity'] .  '</quantity_in_stock>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</offer>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</offers>' . PHP_EOL;
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //25 obyava xml

  //26 ekatalog xml
  public function ekatalog() {
    $this->feed = 'ekatalog';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0" encoding="utf-8"?>';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<name>' . $startup['name'] . '</name>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories']) {
        $xml .= '<catalog>';
        foreach($startup['categories'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
          }
        }
        $xml .= '</catalog>';
      }

      $xml .= '<items>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<item id="' . $product_id . '">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<image>' . $product['image'] .  '</image>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<image>' . $image .  '</image>';
                }
              }
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              $xml .= '</item>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</items>';
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //26 ekatalog xml

  //27 facebook xml
  public function facebook() {
    $this->feed = 'facebook';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml  = '<?xml version="1.0"?>';
      $xml .= '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">';
      $xml .= '<title>' . $startup['name'] . '</title>';
      $xml .= '<link rel="self">' . HTTPS_SERVER . '</link>';

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){

              if($startup['category_match'] && isset($startup['categories'][$product['category_id']]['name'])){ //replace on google category
                $product_category_data = $startup['categories'][$product['category_id']]['name'];
                $product_category_id = explode(' - ', $product_category_data);
                if(isset($product_category_id[0])){
                  $product['category_id'] = (int)$product_category_id[0];
                }
              }else{
                $product['category_id'] = false;
              }

              $xml .= '<entry>';
              $xml .= '<g:title>' . $product['name'] . '</g:title>';
              $xml .= '<g:link>' . $product['url'] .  '</g:link>';
              $xml .= '<g:id>' . $product_id . '</g:id>';
              if($product['special']){
                $xml .= '<g:price>' . $product['price'] . ' ' . $startup['currency'] . '</g:price>';
                $xml .= '<g:sale_price>' . $product['special'] . ' ' . $startup['currency'] . '</g:sale_price>';
              }else{
                $xml .= '<g:price>' . $product['price'] . ' ' . $startup['currency'] . '</g:price>';
              }
              $xml .= '<g:description><![CDATA[' . $product['description'] .  ']]></g:description>';
              if($product['category_id']){
                $xml .= '<g:google_product_category>' . $product['category_id'] . '</g:google_product_category>';
              }
              $xml .= '<g:brand>' . html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8') . '</g:brand>';
              $xml .= '<g:condition>new</g:condition>';
              $xml .= '<g:image_link>' . $product['image'] .  '</g:image_link>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<g:image_link>' . $image .  '</g:image_link>';
                }
              }
              if(isset($product['mpn']) && $product['mpn']){
  							$xml .= '<g:mpn><![CDATA[' . $product['mpn'] . ']]></g:mpn>' ;
  						}
              if(isset($product['upc']) && $product['upc']){
  							$xml .= '  <g:upc>' . $product['upc'] . '</g:upc>';
  						}
              if(isset($product['ean']) && $product['ean']){
  							$xml .= '  <g:ean>' . $product['ean'] . '</g:ean>';
  						}
              if(isset($product['weight']) && isset($product['weight_class_id'])){
                $xml .= '<g:weight>' . $this->weight->format($product['weight'], $product['weight_class_id']) . '</g:weight>';
              }
  						$xml .= '<g:availability>' . ($product['quantity'] ? 'in stock' : 'out of stock') . '</g:availability>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['name'] . '>';
              }
              $xml .= '</entry>' . PHP_EOL;
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</feed>';

      $this->to_xml($xml, "finish", true);
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //27 facebook xml

  //28 salidzini xml
  public function salidzini() {
    $this->feed = 'salidzini';
    $xml = false;
    $startup = $this->startup();

    //status
    if($startup['status']){

      //headerXML
      $xml .= '<?xml version="1.0" encoding="UTF-8" ?>';
      $xml .= '<root>' . PHP_EOL;

      $this->to_xml($xml, "start");
      //headerXML

      //generateXML
      if($startup['products']){

        for($i=0; $i<10000000/$this->step; $i++){
          $xml = '';

          $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

          $this->count_product += count($products);

          if($products){
            foreach($products as $product_id => $product){
              $xml .= '<item>';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<model>' . $product['model'] .  '</model>';
              $xml .= '<link>' . $product['url'] .  '</link>';
              $xml .= '<category_link>' . $this->url->link('product/category', 'path=' . $product['category_id']) .  '</category_link>';
              $xml .= '<category_full>' . $product['category_full'] .  '</category_full>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<image>' . $product['image'] .  '</image>';
              $xml .= '<manufacturer>' . $product['manufacturer'] .  '</manufacturer>';
              foreach($product['attributes_full'] as $attribute){
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              $xml .= '</item>';
            }
          }else{
            break;
          }

          $this->to_xml($xml);

        }

      }
      //generateXML

      //footerXML
      $xml = '</root>';

      $this->to_xml($xml, "finish");
      //footerXML

    }else{
      $this->to_xml($xml);
    }
    //status

  }
  //28 salidzini xml


    //29 yandex turbo xml
    public function turbo() {
      $this->feed = 'turbo';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
        $xml .= '<shop>';
        $xml .= '<url>' . HTTPS_SERVER . '</url>';
        $xml .= '<name>' . $this->config->get('config_name') . '</name>';
        $xml .= '<company>' . $startup['name'] . '</company>';
        $xml .= '<currencies>';
        $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
        $xml .= '</currencies>';

        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }

        if($startup['delivery_cost']) {
          $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
        }

        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<offer id="' . $product_id . '"  type="vendor.model">';
                $xml .= '<model>' . $product['name'] .  '</model>';
                $xml .= '<url>' . $product['url'] .  '</url>';
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] .  '</price>';
                  $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
                }else{
                  $xml .= '<price>' . $product['price'] .  '</price>';
                }
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  array_splice($product['images'], 9);
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }
                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                if($startup['delivery_cost']) {
                  $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
                }
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = '</offers>' . PHP_EOL;
        $xml .= '</shop>';
        $xml .= '</yml_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //29 yandex turbo xml

    //30 tiu xml
    public function tiu() {
      $this->feed = 'tiu';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
        $xml .= '<shop>';
        $xml .= '<url>' . HTTPS_SERVER . '</url>';
        $xml .= '<name>' . $this->config->get('config_name') . '</name>';
        $xml .= '<company>' . $startup['name'] . '</company>';
        $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
        $xml .= '<currencies>';
        $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
        $xml .= '</currencies>';

        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }

        if($startup['delivery_cost']) {
          $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
        }

        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
                $xml .= '<name>' . $product['name'] .  '</name>';
                $xml .= '<url>' . $product['url'] .  '</url>';
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] .  '</price>';
                  $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
                }else{
                  $xml .= '<price>' . $product['price'] .  '</price>';
                }
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  array_splice($product['images'], 9);
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }
                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                if($startup['delivery_cost']) {
                  $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
                }
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = '</offers>' . PHP_EOL;
        $xml .= '</shop>';
        $xml .= '</yml_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //30 tiu xml

    //31 priceru xml
    public function priceru() {
      $this->feed = 'priceru';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<priceru_feed date="' . date('Y-m-d H:i', time()) . '">';
        $xml .= '<shop>';
        $xml .= '<url>' . HTTPS_SERVER . '</url>';
        $xml .= '<company>' . $startup['name'] . '</company>';
        $xml .= '<currencies>';
        $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
        $xml .= '</currencies>';

        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }

        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
                $xml .= '<name>' . $product['name'] .  '</name>';
                $xml .= '<model>' . $product['model'] .  '</model>';
                $xml .= '<url>' . $product['url'] .  '</url>';
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] .  '</price>';
                  $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
                }else{
                  $xml .= '<price>' . $product['price'] .  '</price>';
                }
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  array_splice($product['images'], 9);
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }
                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                if($startup['delivery_cost']) {
                  $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
                }
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = '</offers>' . PHP_EOL;
        $xml .= '</shop>' . PHP_EOL;
        $xml .= '</priceru_feed>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //31 priceru xml

    //32 tomasby xml
    public function tomasby() {
      $this->feed = 'tomasby';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
        $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
        $xml .= '<shop>';
        $xml .= '<name>' . $startup['name'] . '</name>';
        $xml .= '<company>' . $startup['name'] . '</company>';
        $xml .= '<platform>Opencart</platform>';
        $xml .= '<url>' . HTTPS_SERVER . '</url>';
        $xml .= '<currencies>';
        $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
        $xml .= '</currencies>';

        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }
        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
                $xml .= '<name>' . $product['name'] .  '</name>';
                $xml .= '<url><![CDATA[' . $product['url'] .  ']]></url>';
                if($product['special']){
                  $xml .= '<price_old>' . $product['price'] . '</price_old>';
                  $xml .= '<price>' . $product['special'] . '</price>';
                }else{
                  $xml .= '<price>' . $product['price'] . '</price>';
                }
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }
                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<stock_quantity>' . $product['quantity'] .  '</stock_quantity>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = PHP_EOL . '</offers>' . PHP_EOL;
        $xml .= '</shop>' . PHP_EOL;
        $xml .= '</yml_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //32 tomasby xml

    //33 kaspi
    public function kaspi() {
      $this->feed = 'kaspi';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml  = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<kaspi_catalog date="' . date('Y-m-d H:i', time()) . '" xmlns="kaspiShopping" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="kaspiShopping http://kaspi.kz/kaspishopping.xsd">';
        $xml .= '<company>' . $this->config->get('config_name') . '</company>';
        $xml .= '<merchantid>' . $startup['name'] . '</merchantid>';
        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<offer sku="' . $product['sku'] . '">'; // available="' . ($product['stock']?'true':'false') .'"
                $xml .= '<model>' . $product['name'] .  '</model>';
                $xml .= '<brand>' . $product['manufacturer'] .  '</brand>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] . '</price>';
                }else{
                  $xml .= '<price>' . $product['price'] . '</price>';
                }
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = PHP_EOL . '</offers>' . PHP_EOL;
        $xml .= '</kaspi_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //33 kaspi

    //34 autoru xml
    public function autoru() {
      $this->feed = 'autoru';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml .= '<parts>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<part>';
                $xml .= '<id>' . $product_id . '</id>';
                $xml .= '<title>' . $product['name'] .  '</title>';
                if(isset($product['store'])){
                  $xml .= '<stores><store>' . $product['store'] . '</store></stores>';
                }
                $xml .= '<offer_url>' . $product['url'] .  '</offer_url>';
                $xml .= '<part_number>' . $product['mpn'] .  '</part_number>';
                if(isset($product['cars'])){
                  $xml .= '<compatibility>';
                  foreach($product['cars'] as $car){
                    $xml .= '<car>' . $car . '</car>';
                  }
                  $xml .= '</compatibility>';
                }
                $xml .= '<manufacturer>' . $product['manufacturer'] .  '</manufacturer>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] .  '</price>';
                  $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
                }else{
                  $xml .= '<price>' . $product['price'] .  '</price>';
                }
                $xml .= '<availability>';
                  $xml .= '<isAvailable>' . ($product['stock']?'True':'False') .'</isAvailable>';
                  if(isset($product['daysFrom'])){
                    $xml .= '<daysFrom>' . $product['daysFrom'] .'</daysFrom>';
                  }
                  if(isset($product['daysTo'])){
                    $xml .= '<daysTo>' . $product['daysTo'] .'</daysTo>';
                  }
                $xml .= '</availability>';
                $xml .= '<images>';
                $xml .= '<image>' . $product['image'] .  '</image>';
                if($product['images']){
                  foreach($product['images'] as $image){
                    $xml .= '<image>' . $image .  '</image>';
                  }
                }
                $xml .= '</images>';
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                if($product['attributes']){
                  $xml .= '<properties>';
                  foreach($product['attributes'] as $attribute){
                    $xml .= '<property name="' . $attribute['name'] . '">' . $attribute['text'] .  '</property>';
                  }
                  $xml .= '</properties>';
                }
                $xml .= '</part>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = '</parts>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //34 autoru xml

    //35 drom xml
    public function drom() {
      $this->feed = 'drom';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
        $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
        $xml .= '<shop>';
        $xml .= '<url>' . HTTPS_SERVER . '</url>';
        $xml .= '<name>' . $this->config->get('config_name') . '</name>';
        $xml .= '<company>' . $startup['name'] . '</company>';
        $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
        $xml .= '<currencies>';
        $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
        $xml .= '</currencies>';

        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }

        if($startup['delivery_cost']) {
          $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
        }

        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
                $xml .= '<name>' . $product['name'] .  '</name>';
                $xml .= '<url>' . $product['url'] .  '</url>';
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] .  '</price>';
                  $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
                }else{
                  $xml .= '<price>' . $product['price'] .  '</price>';
                }
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  array_splice($product['images'], 9);
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }
                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                if($startup['delivery_cost']) {
                  $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
                }
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = '</offers>' . PHP_EOL;
        $xml .= '</shop>';
        $xml .= '</yml_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //35 drom xml

    //36 domby xml
    public function domby() {
      $this->feed = 'domby';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
        $xml .= '<shop>';
        $xml .= '<url>' . HTTPS_SERVER . '</url>';
        $xml .= '<name>' . $this->config->get('config_name') . '</name>';
        $xml .= '<company>' . $startup['name'] . '</company>';
        $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
        $xml .= '<currencies>';
        $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
        $xml .= '</currencies>';

        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }

        if($startup['delivery_cost']) {
          $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
        }

        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
                $xml .= '<name>' . $product['name'] .  '</name>';
                $xml .= '<url>' . $product['url'] .  '</url>';
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] .  '</price>';
                  $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
                }else{
                  $xml .= '<price>' . $product['price'] .  '</price>';
                }
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  array_splice($product['images'], 9);
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }
                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                if($startup['delivery_cost']) {
                  $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
                }
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = '</offers>' . PHP_EOL;
        $xml .= '</shop>';
        $xml .= '</yml_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //36 domby xml

    //37 zakupkimos xml
    public function zakupkimos() {
      $this->feed = 'zakupkimos';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '" xmlns="http://market.zakupki.mos.ru/spIntegration/Yml/1.0">';
        $xml .= '<shop>';
        $xml .= '<url>' . HTTPS_SERVER . '</url>';
        $xml .= '<name>' . $this->config->get('config_name') . '</name>';
        $xml .= '<company>' . $startup['name'] . '</company>';
        $xml .= '<phone>' . $this->config->get('config_telephone') . '</phone>';
        $xml .= '<currencies>';
        $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
        $xml .= '</currencies>';
        $xml .= '</shop>';

        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }

        if($startup['delivery_cost']) {
          $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
        }

        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
                $xml .= '<name>' . $product['name'] .  '</name>';
                $xml .= '<url>' . $product['url'] .  '</url>';
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] .  '</price>';
                  $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
                }else{
                  $xml .= '<price>' . $product['price'] .  '</price>';
                }
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  array_splice($product['images'], 9);
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }
                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                if($startup['delivery_cost']) {
                  $xml .= '<delivery-options><option cost="' . $startup['delivery_cost'] . '" days="' . ($startup['delivery_time']?$startup['delivery_time']:1) . '" order-before="' . ($startup['delivery_jump']?$startup['delivery_jump']:18) . '"/></delivery-options>';
                }
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                if(isset($product['regions'])){
                  $xml .= '<regions>';
                  foreach($product['regions'] as $region){
                    $xml .= '<region>' . $region .  '</region>';
                  }
                  $xml .= '</regions>';
                }
                if(isset($product['okei'])){
                  $xml .= '<okei id="' . $product['okei_id'] .  '">' . $product['okei'] .  '</okei>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = '</offers>' . PHP_EOL;
        $xml .= '</yml_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //37 zakupkimos xml

    //38 aliexpress xml
    public function aliexpress() {
      $this->feed = 'aliexpress';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml .= '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<yml_catalog>';
        $xml .= '<shop>';

        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }

        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $group_id = '';
                if($startup['option_multiplier_status'] && $startup['option_multiplier_id'] && $product['product_option_id']){
                  $group_id = 'group_id="' . $product['product_original_id'] . '"';
                }
                $stock = $product['stock']?'true':'';
                if(isset($product['custom_stock'])){
                  $stock = $product['custom_stock'];
                }
                $xml .= '<offer id="' . $product_id . '" ' . $group_id . '>';
                $xml .= '<name>' . $product['name'] .  '</name>';
                $xml .= '<url>' . $product['url'] .  '</url>';
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] .  '</price>';
                  $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
                }else{
                  $xml .= '<price>' . $product['price'] .  '</price>';
                }
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  array_splice($product['images'], 9);
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }
                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = '</offers>' . PHP_EOL;
        $xml .= '</shop>';
        $xml .= '</yml_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //38 aliexpress xml

    //39 youla xml
    public function youla() {
      $this->feed = 'youla';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
        $xml .= '<shop>';

        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }

        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                // $group_id = '';
                // if($startup['option_multiplier_status'] && $startup['option_multiplier_id'] && $product['product_option_id']){
                //   $group_id = 'group_id="' . $product['product_original_id'] . '"';
                // }
                $stock = $product['stock']?'true':'';
                if(isset($product['custom_stock'])){
                  $stock = $product['custom_stock'];
                }
                $xml .= '<offer id="' . $product_id . '">';
                $xml .= '<name>' . $product['name'] .  '</name>';
                $xml .= '<url>' . $product['url'] .  '</url>';
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                if($product['special']){
                  $xml .= '<price>' . $product['special'] .  '</price>';
                  $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
                }else{
                  $xml .= '<price>' . $product['price'] .  '</price>';
                }
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  array_splice($product['images'], 9);
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }

                if(isset($product['youlaCategoryId'])){
                  $xml .= '<youlaCategoryId>' . $product['youlaCategoryId'] .  '</youlaCategoryId>';
                }
                if(isset($product['youlaSubcategoryId'])){
                  $xml .= '<youlaSubcategoryId>' . $product['youlaSubcategoryId'] .  '</youlaCategoryId>';
                }
                if(isset($product['address'])){
                  $xml .= '<address>' . $product['address'] .  '</address>';
                }
                if(isset($product['phone'])){
                  $xml .= '<phone>' . $product['phone'] .  '</phone>';
                }
                if(isset($product['managerName'])){
                  $xml .= '<managerName>' . $product['managerName'] .  '</managerName>';
                }

                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = '</offers>' . PHP_EOL;
        $xml .= '</shop>';
        $xml .= '</yml_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //39 youla xml

    //40 allbiz xml
    public function allbiz() {
      $this->feed = 'allbiz';
      $xml = false;
      $startup = $this->startup();

      //status
      if($startup['status']){

        //headerXML
        $xml  = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
        $xml .= '<yml_catalog>';
        $xml .= '<shop>';
        $xml .= '<currencies>';
        $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
        $xml .= '</currencies>';
        if($startup['categories']) {
          $xml .= '<categories>';
          foreach($startup['categories'] as $category) {
            if($category['parent_id']){
              $xml .= '<category id="' . $category['category_id'] .'" parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
            } else{
              $xml .= '<category id="' . $category['category_id'] .'">' . $category['name'] .'</category>';
            }
          }
          $xml .= '</categories>';
        }
        $xml .= '<offers>' . PHP_EOL;

        $this->to_xml($xml, "start");
        //headerXML

        //generateXML
        if($startup['products']){

          for($i=0; $i<10000000/$this->step; $i++){
            $xml = '';

            $products = $this->startup(array('start' => $this->step * $i, 'finish' => $this->step));

            $this->count_product += count($products);

            if($products){
              foreach($products as $product_id => $product){
                $xml .= '<offer id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
                $xml .= '<name>' . $product['name'] .  '</name>';
                $xml .= '<url>' . $product['url'] .  '</url>';
                if($product['special']){
                  $xml .= '<price_old>' . $product['price'] . '</price_old>';
                  $xml .= '<price>' . $product['special'] . '</price>';
                }else{
                  $xml .= '<price>' . $product['price'] . '</price>';
                }
                if(isset($product['prices'])){
                  $xml .= '<prices>';
                  foreach($product['prices'] as $price){
                    $xml .= '<price>';
                      $xml .= '<value>' . $price['price'] . '</value>';
                      $xml .= '<quantity>' . $price['quantity'] . '</quantity>';
                    $xml .= '</price>';
                  }
                  $xml .= '</prices>';
                }
                $xml .= '<currencyId>' . $startup['currency'] . '</currencyId>';
                $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
                $xml .= '<picture>' . $product['image'] .  '</picture>';
                if($product['images']){
                  foreach($product['images'] as $image){
                    $xml .= '<picture>' . $image .  '</picture>';
                  }
                }
                $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
                $xml .= '<vendorCode>' . $product['model'] .  '</vendorCode>';
                $xml .= '<stock_quantity>' . $product['quantity'] .  '</stock_quantity>';
                $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
                foreach($product['attributes_full'] as $attribute){
                  $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
                }
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
                $xml .= '</offer>';
              }
            }else{
              break;
            }

            $this->to_xml($xml);

          }

        }
        //generateXML

        //footerXML
        $xml = PHP_EOL . '</offers>' . PHP_EOL;
        $xml .= '</shop>' . PHP_EOL;
        $xml .= '</yml_catalog>';

        $this->to_xml($xml, "finish");
        //footerXML

      }else{
        $this->to_xml($xml);
      }
      //status

    }
    //40 allbiz xml

}
