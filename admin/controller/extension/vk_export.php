<?php 

/**
 * Экспорт товаров ВКонтакте для Opencart 2.0-2.2
 * Официальный сайт vkexport.allex-p.ru
 * Официальная страница дополнения https://opencartforum.com/files/file/600-экспорт-товаров-вконтакте/
 * 
 * Данная копия модуля зарегистрирована на admin@exotiks.ru
 * 
 */

class ControllerExtensionVKExport extends Controller {
    
    private $version = '5.2 b13 oc2.0-2.2';
    private $error = array(); 
    private $errorMessage;
    private $usleep = 500000;
    
    /*
     * Счетчик загруженных изображений в альбомы в процессе экспорта
     * int
     */
    private $total_pics_load = 0;
    
    /*
     * Счетчик добавленных комментариев в процессе экспорта
     * int
     */
    private $comments_count = 0;
    
    /*
     * Флаг при запуске через cron
     * boolean
     */
    private $cron = false;
     
    public function index() {
        $this->load->language('extension/vk_export');
        
        $this->document->setTitle($this->language->get('heading_title')); 

        $this->load->model('catalog/product');
        
        $this->load->model('extension/extension');
        
        $extensions = $this->model_extension_extension->getInstalled('module');
        
        if (!in_array('vk_export', $extensions)) {
            $this->not_installed();
        }
        else {
            $this->getList();
        }
      }
      
    public function not_installed() {
        $this->load->language('extension/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        
        $data['breadcrumbs'] = array();

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
           );

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'], 'SSL'),
           );        
        
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->response->setOutput($this->load->view('extension/vk_export_no.tpl', $data));
    }
    
    public function get_log() {
        
        $log_archive = DIR_LOGS . 'vkExportLog.zip';
        if (file_exists($log_archive)) {
            unlink($log_archive);
        }
        $zip = new ZipArchive();
        
        if ($zip->open($log_archive, ZIPARCHIVE::CREATE)!==TRUE) {
            exit("cannot open <$log_archive>\n");
        }
        
        $files = glob(DIR_LOGS . '.vkExportLog_*');
        foreach ($files as $file) {
            $zip->addFile($file, pathinfo($file, PATHINFO_BASENAME));
        }
        if (file_exists(DIR_LOGS . 'vkExport_cron_wall.txt')) {
            $zip->addFile(DIR_LOGS . 'vkExport_cron_wall.txt', 'vkExport_cron_wall.txt');
        }
        if (file_exists(DIR_LOGS . 'vkExport_cron.txt')) {
            $zip->addFile(DIR_LOGS . 'vkExport_cron.txt', 'vkExport_cron.txt');
        }
        if (file_exists(DIR_LOGS . 'error.txt')) {
            $zip->addFile(DIR_LOGS . 'error.txt', 'error.txt');
        }
        $zip->close();
        if (file_exists($log_archive)) {
            header('Content-Type: application/zip');
            header('Content-Length: ' . filesize($log_archive));
            header('Content-Disposition: attachment; filename="vkExportLog.zip"');
            readfile($log_archive);
        }
        else {
            $this->response->setOutput('Логи отсутствуют');
        }
    }
    
    public function export() {
        $this->load->language('extension/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        
        if (isset($this->request->post['selected']) && $this->validateVkExport()) {
            set_time_limit(count($this->request->post['selected']) * 60);
            session_write_close(); 
            $report = array();
            $success = 0;
            
            $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
            if ($this->config->get('vk_export_debug_mode')) {
                $log_file = DIR_LOGS . '.vkExportLog_' . date('d.m.y_H.i.s') . '_' . substr(md5(microtime()), 0, 7);
                $vkApi->set_log_on(true);
                $vkApi->set_log_file($log_file);
            }
            
            $total = count($this->request->post['selected']);
            $this->flushProgress();
            $this->cache->delete('vk_export_captcha_request');
            
            foreach ($this->request->post['selected'] as $key => $product_id) {
                $p = $this->model_catalog_vk_export->getProduct($product_id);
                $category_id = $this->request->post['album'][$product_id];
                
                $error = false;
                
                if (isset($this->request->post['extra_album']) && $this->request->post['extra_album']) {
                    $album_id = preg_match('/album(\-?\d+_\d+)$/', $this->request->post['extra_album'], $matches);
                    if ($album_id) {
                        $album_id = $matches[1];
                        if (!$this->makeLoadQuick($vkApi, $product_id, $album_id, $category_id, $p)) {
                            $error = true;
                        }
                        if ($errmsg = $this->errorMessage) {
                            $report[] = $errmsg;
                        }
                    }
                }
                else {
                    if (!$category_id) {
                        $report[] = 'Для товара "' . $p['name'] . '" не выбран альбом';
                        continue;
                    }
                    if (!$p['image']) {
                        $report[] = 'Товар "' . $p['name'] . '" не имеет изображения';
                        continue;
                    }                        
                    
                    /*
                     * загрузка в альбомы пользователя
                     */
                    if ($this->config->get('vk_export_mode') == 1 || $this->config->get('vk_export_mode') == 3) {
                        $this->errorMessage = '';
                        if (!$this->makeLoad($vkApi, $product_id, $category_id, $p, 'user')) {
                            $error = true;
                        }
                        else if ($total > 1){
                            $this->sleep_export();
                        }
                        if ($this->errorMessage) {
                            $report[] = $this->errorMessage;
                        }
                    }
                    /*
                     * загрузка в альбомы группы
                     */
                    if ($this->config->get('vk_export_mode') == 2 || $this->config->get('vk_export_mode') == 3) {
                        $this->errorMessage = '';
                        if (!$this->makeLoad($vkApi, $product_id, $category_id, $p, 'group')) {
                            $error = true;
                        }
                        else if ($total > 1){
                            $this->sleep_export();
                        }
                        if ($this->errorMessage) {
                            $report[] = $this->errorMessage;
                        }
                    }
                }
                
                if (!$error) {
                    $success++;
                }
                $this->writeProgress($success);
            }
            
            $msg = '';
            $vk_export_report = array();
            if ($success) {
                $msg = $this->language->get('text_vk_export_success');
                $msg = sprintf($msg, $success, $total, $this->total_pics_load);
                $vk_export_report['success'] = $msg;
            }
            if ($report) {
                $vk_export_report['warning'] = implode('<br>', $report);
                if ($this->captcha_show) {
                    $vk_export_report['warning'] .= '<br><a id="send_captcha" class="button">Отправить</a>';
                }
            }
            
            if ($vk_export_report) {
                $this->cache->set('vk_export_report', $vk_export_report);
            }
            
            $url = '';
            
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }
        
            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . $this->request->get['filter_model'];
            }
            
            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }
            
            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }    
        
            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }
        
            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }
        
            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }
            
            if (isset($this->request->get['filter_export_albums'])) {
                $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
            }
            
            if (isset($this->request->get['filter_export_wall'])) {
                $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
            }
                    
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
      }
    
    private function logging($text, $file, $flags = 0) {
        $date = date('d.m.y H:i:s');
        $msg = "Date: $date\n";
        $msg .= $text . " \n------------------------------------------------------\n";
        file_put_contents(DIR_LOGS . $file, $msg, $flags);
    }
    
    // автоматический экспорт в альбомы по крону 
    public function autoexport() {
        $logfile = 'vkExport_cron.txt';
        $this->logging('Начало процесса', $logfile, FILE_APPEND);
        $this->cron = true;
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        $this->load->language('extension/vk_export');
        
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
        if ($this->config->get('vk_export_debug_mode')) {
            $log_file = DIR_LOGS . '.vkExportLog_autoexport_' . date('d.m.y_H.i.s') . '_' . substr(md5(microtime()), 0, 7);
            $vkApi->set_log_on(true);
            $vkApi->set_log_file($log_file);
        }
        
        $query = $this->db->query('SHOW TABLES LIKE \'category_path\'');
        $category_path_table = count($query->rows);
        
        if ($category_path_table) {
            $categories = $this->model_catalog_vk_export->getCategories(0);
        }
        else {
            $categories = $this->model_catalog_vk_export->getCategories_old(0);
        }
        $categories_all = array();
        foreach ($categories as $category) {
            $categories_all[$category['category_id']] = $category;
        }

        $products = $this->model_catalog_vk_export->getProductsAutoexport();
        $total = count($products);
        set_time_limit($total * 60);
        session_write_close(); 
        $this->logging('Старт экспорта ' . $total . ' товаров', $logfile, FILE_APPEND);
        $success = 0;
        foreach ($products as $product) {
            $p = $this->model_catalog_vk_export->getProduct($product['product_id']);
            
            $error = false;
                      
            $category_id = false;
            if (method_exists($this->model_catalog_product, 'getProductMainCategoryId')) {
                $category_id = $this->model_catalog_product->getProductMainCategoryId($product['product_id']);
            }
            if (!$category_id) {
                $category_id = $product['category_id'];
            }
            
            if ($this->config->get('vk_export_root_cat')) {
                $parent_id = $categories_all[$category_id]['parent_id'];
                while ($parent_id) {
                    $category_id = $parent_id;   
                    $parent_id = $categories_all[$category_id]['parent_id'];
                }
            }
                            
            $product_id = $product['product_id'];
            if (!$category_id) {
                $this->logging('Для товара "' . $p['name'] . '" не установлен альбом', $logfile, FILE_APPEND);
                continue;
            }
            if (!$p['image'] || !file_exists(DIR_IMAGE . $p['image'])) {
                $this->logging('Товар "' . $p['name'] . '" не имеет изображения', $logfile, FILE_APPEND);
                continue;
            }               
            
            /*
             * загрузка в альбомы пользователя
             */
            if ($this->config->get('vk_export_mode') == 1 || $this->config->get('vk_export_mode') == 3) {
                $this->errorMessage = '';
                if (!$this->makeLoad($vkApi, $product_id, $category_id, $p, 'user')) {
                    $error = true;
                }
                else if ($total > 1){
                    $this->sleep_export();
                }
                if ($this->errorMessage) {
                    $this->logging($this->errorMessage, $logfile, FILE_APPEND);
                }
            }
            /*
             * загрузка в альбомы группы
             */
            if ($this->config->get('vk_export_mode') == 2 || $this->config->get('vk_export_mode') == 3) {
                $this->errorMessage = '';
                if (!$this->makeLoad($vkApi, $product_id, $category_id, $p, 'group')) {
                    $error = true;
                }
                else if ($total > 1){
                    $this->sleep_export();
                }
                if ($this->errorMessage) {
                    $this->logging($this->errorMessage, $logfile, FILE_APPEND);
                }
            }
            
            if (!$error) {
                $success++;
            }
        }
        
        $msg = '';
        if ($success) {
            $msg = $this->language->get('text_vk_export_success');
            $msg = sprintf($msg, $success, $total, $this->total_pics_load);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        echo file_get_contents(DIR_LOGS . $logfile);
      }
      
    // автоматический экспорт в товары по крону 
    public function cron_market() {
        
        $this->cron = true;
        $logfile = 'vkExport_cron_market.txt';
        $this->logfile = $logfile;
        if (!$this->config->get('vk_export_market_autoexport_category')) {
            $this->logging('Не установлены категории для автоматического экспорта в товары', $logfile, FILE_APPEND);
            return false;
        }
        
        $lang_id = $this->config->get('config_language_id');
        $this->logging('Начало процесса', $logfile, FILE_APPEND);
        $this->cron = true;
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        $this->load->language('extension/vk_export');
        
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
        if ($this->config->get('vk_export_debug_mode')) {
            $log_file = DIR_LOGS . '.vkExportLog_cron_market_' . date('d.m.y_H.i.s') . '_' . substr(md5(microtime()), 0, 7);
            $vkApi->set_log_on(true);
            $vkApi->set_log_file($log_file);
        }
        
        $query = $this->db->query('SHOW TABLES LIKE \'category_path\'');
        $category_path_table = count($query->rows);
        
        if ($category_path_table) {
            $categories = $this->model_catalog_vk_export->getCategories(0);
        }
        else {
            $categories = $this->model_catalog_vk_export->getCategories_old(0);
        }
        $categories_all = array();
        foreach ($categories as $category) {
            $categories_all[$category['category_id']] = $category;
        }

        $products = $this->model_catalog_vk_export->getProductsCronMarket();
        $total = count($products);
        set_time_limit($total * 60);
        session_write_close(); 
        $this->logging('Найдено ' . $total . ' товаров для экспорта', $logfile, FILE_APPEND);
        $success = 0;
        foreach ($products as $pk => $product) {
            $p = $this->model_catalog_vk_export->getProduct($product['product_id']);
            
            $error = false;
                      
            $category_id = false;
            if (method_exists($this->model_catalog_product, 'getProductMainCategoryId')) {
                $category_id = $this->model_catalog_product->getProductMainCategoryId($product['product_id']);
            }
            if (!$category_id) {
                $category_id = $product['category_id'];
            }
            
            if (!$category_id) {
                $this->logging('Для товара "' . $p['name'] . '" не установлена категория', $logfile, FILE_APPEND);
                continue;
            }
            
            // категория товаров в маркете ВК
            $market_album = $this->model_catalog_vk_export->getMarketAlbum($category_id);
            if (!$market_album) {
                $category = $this->model_catalog_category->getCategoryDescriptions($category_id);
                $this->logging('Для категории "' . $category[$lang_id]['name'] . '" не установлена категория товаров ВК', $logfile, FILE_APPEND);
                continue;
            }
            
            $product_id = $product['product_id'];
            
            if (!$p['image'] || !file_exists(DIR_IMAGE . $p['image'])) {
                $this->logging('Товар "' . $p['name'] . '" не имеет изображения', $logfile, FILE_APPEND);
                continue;
            }               
            
            $this->errorMessage = '';
            if (!$this->makeLoadMarket($vkApi, $product_id, $p, $category_id, $market_album['vk_market_category_id'])) {
                $error = true;
            }
            else if ($total > 1){
                $this->sleep_export();
            }
            if ($this->errorMessage) {
                $this->logging($this->errorMessage, $logfile, FILE_APPEND);
            }
            
            
            if (!$error) {
                $this->logging('Экспорт товара "[id: ' . $product['product_id'] . '] ' . $p['name'] . '": успешно', $logfile, FILE_APPEND);
                $success++;
            }
        }
        
        $msg = '';
        if ($success) {
            $msg = $this->language->get('text_vk_market_success');
            $msg = sprintf($msg, $success, $total);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        echo file_get_contents(DIR_LOGS . $logfile);
    }
    
    // автоматическое обновление описания товарав в маркете по крону
    public function cron_market_update() {
        
        $logfile = 'vkExport_cron_market_update.txt';
        $this->logging('Запуск процесса', $logfile, FILE_APPEND);
        
        if (!$this->config->get('vk_export_market_autoexport_category')) {
            $this->logging('Не установлены категории для автоматического экспорта в товары', $logfile, FILE_APPEND);
            return false;
        }
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        $this->load->language('extension/vk_export');
        
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
        if ($this->config->get('vk_export_debug_mode')) {
            $log_file = DIR_LOGS . '.vkExportLog_cron_market_update_' . date('d.m.y_H.i.s') . '_' . substr(md5(microtime()), 0, 7);
            $vkApi->set_log_on(true);
            $vkApi->set_log_file($log_file);
        }

        $products = $this->model_catalog_vk_export->getProductsToCronUpdateMarket();
        $total = count($products);
        set_time_limit($total * 60);
        session_write_close(); 
        if ($total) {
            $this->logging('Найдено ' . $total . ' товаров для обновления', $logfile, FILE_APPEND);
            $this->logging('Старт процесса', $logfile, FILE_APPEND);
        }
        else {
            $this->logging('Товары, требующие обновления не найдены. (Обновляются только те товары, дата изменения которых больше даты экспорта)', $logfile, FILE_APPEND);
        }
        $deleted_out_of_stock = 0;
        $hide_out_of_stock = 0;
        $deleted_disabled = 0;
        $hide_disabled = 0;
        $success = 0;
        foreach ($products as $product) {
            
            // статус вк "не достепен"(удален)
            $not_avaible = false;
            $deleted = false;
            $error = false;
            
            $p = $this->model_catalog_vk_export->getProduct($product['product_id']);
            $p['vk_photo_id'] = $product['vk_photo_id'];
            $p['export_category_id'] = $product['export_category_id'];
            $photo = explode('_', $product['vk_photo_id']);
            $owner_id = $photo[0];
            $item_id = $photo[1];
            $product_id = $product['product_id'];
            
            // если товара нет в наличии, удаляем его
            if ($p['quantity'] == 0 && $this->config->get('vk_export_market_cron_action_out_of_stock') == 'delete') {
                
                try {
                    $vkApi->delete_market($owner_id, $item_id);
                    $this->model_catalog_vk_export->deleteMarketExportOne($p['product_id'], $p['vk_photo_id']);
                    $deleted = true;
                }
                catch (vkApiException $e) {
                    $error = 'При удалении товара "' . $p['name'] . '" произошла ошибка: ';
                    if ($comment = $e->getCodeComment()) {
                        $error .= $comment;
                    }
                    else {
                        $error .= $e;
                    }
                    $this->logging($error, $logfile, FILE_APPEND);
                    $error = true;
                }
                
                if (!$error) {
                    $deleted_out_of_stock++;
                }
                
            }
            // если товара нет в наличии, Устанавливать статус "Товар недоступен"
            else if ($p['quantity'] == 0 && $this->config->get('vk_export_market_cron_action_out_of_stock') == 'not_avaible') {
                $not_avaible = true;
                $hide_out_of_stock++;
            }
            // если товар отключен, удаляем его
            else if ($p['status'] == 0 && $this->config->get('vk_export_market_cron_action_disabled') == 'delete') {
                try {
                    $vkApi->delete_market($owner_id, $item_id);
                    $this->model_catalog_vk_export->deleteMarketExportOne($p['product_id'], $p['vk_photo_id']);
                    $deleted = true;
                }
                catch (vkApiException $e) {
                    $error = 'При удалении товара "' . $p['name'] . '" произошла ошибка: ';
                    if ($comment = $e->getCodeComment()) {
                        $error .= $comment;
                    }
                    else {
                        $error .= $e;
                    }
                    $this->logging($error, $logfile, FILE_APPEND);
                    $error = true;
                }

                if (!$error) {
                    $deleted_disabled++;
                }
                
            }
            // если товар отключен, Устанавливать статус "Товар недоступен"
            else if ($p['status'] == 0 && $this->config->get('vk_export_market_cron_action_disabled') == 'not_avaible') {
                $not_avaible = true;
                $hide_disabled++;
            }
            
            if (!$deleted) {
                // Обновление товара
                $this->errorMessage = '';
                if (!$this->makeUpdateMarket($vkApi, $product_id, $p, $not_avaible)) {
                    $error = true;
                }
                if ($this->errorMessage) {
                    $error = true;
                    $this->logging($this->errorMessage, $logfile, FILE_APPEND);
                }
                
                if (!$error && !$not_avaible) {
                    $success++;
                }
            }
        }
        $msg = '';
        if ($success) {
            $msg = $this->language->get('text_vk_export_updated');
            $msg = sprintf($msg, $success, $total);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        if ($deleted_out_of_stock) {
            $msg = $this->language->get('text_vk_export_cron_deleted_out_of_stock');
            $msg = sprintf($msg, $deleted_out_of_stock);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        if ($hide_out_of_stock) {
            $msg = $this->language->get('text_vk_export_cron_hide_out_of_stock');
            $msg = sprintf($msg, $hide_out_of_stock);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        if ($deleted_disabled) {
            $msg = $this->language->get('text_vk_export_cron_deleted_disabled');
            $msg = sprintf($msg, $deleted_disabled);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        if ($hide_disabled) {
            $msg = $this->language->get('text_vk_export_cron_hide_disabled');
            $msg = sprintf($msg, $hide_disabled);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        echo file_get_contents(DIR_LOGS . $logfile);
    }
    
    // автоматический постинг на стену по крону
    public function autowallpost() {
        
        $logfile = 'vkExport_cron_wall.txt';
        $this->logging('Начало процесса', $logfile, FILE_APPEND);
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        $this->load->language('extension/vk_export');
        
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
        if ($this->config->get('vk_export_debug_mode')) {
            $log_file = DIR_LOGS . '.vkExportLog_autowallpost_' . date('d.m.y_H.i.s') . '_' . substr(md5(microtime()), 0, 7);
            $vkApi->set_log_on(true);
            $vkApi->set_log_file($log_file);
        }
        
        $products = $this->model_catalog_vk_export->getProductsAutoWallPost();
        $total = count($products);
        set_time_limit($total * 60);
        session_write_close(); 
        $success = 0;
        $this->logging('Старт отправки на стену ' . $total . ' товаров', $logfile, FILE_APPEND);
        foreach ($products as $product) {
            $p = $this->model_catalog_vk_export->getProduct($product['product_id']);
            
            $category_id = 0;
            if ($product['vkep_category_id']) {
                $category_id = $product['vkep_category_id'];
            }
            else if (method_exists($this->model_catalog_product, 'getProductMainCategoryId')) {
                $category_id = $this->model_catalog_product->getProductMainCategoryId($p['product_id']);
            }
            if (!$category_id) {
                $product_categories = $this->model_catalog_product->getProductCategories($p['product_id']);
                $category_id = array_pop($product_categories);
            }
            
            $error = false;
                            
            $product_id = $product['product_id'];
            if (!$p['image']) {
                $this->logging('Товар "' . $p['name'] . '" не имеет изображения', $logfile, FILE_APPEND);
                continue;
            }                
            
             /*
             * загрузка на стену пользователя
             */
            if ($this->config->get('vk_export_mode') == 1 || $this->config->get('vk_export_mode') == 3) {
                $this->errorMessage = '';
                if (!$this->makeLoadWall($vkApi, $product_id, $p, $category_id, 'user')) {
                    $error = true;
                }
                if ($this->errorMessage) {
                    $this->logging($this->errorMessage, $logfile, FILE_APPEND);
                }
            }
            /*
             * загрузка на стену группы
             */
            if ($this->config->get('vk_export_mode') == 2 || $this->config->get('vk_export_mode') == 3) {
                $this->errorMessage = '';
                if (!$this->makeLoadWall($vkApi, $product_id, $p, $category_id, 'group')) {
                    $error = true;
                }
                if ($this->errorMessage) {
                    $this->logging($this->errorMessage, $logfile, FILE_APPEND);
                }
            }
            
            if (!$error) {
                $success++;
            }
        }
        $msg = '';
        if ($success) {
            $msg = $this->language->get('text_vk_wallpost_success');
            $msg = sprintf($msg, $success, $total);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        echo file_get_contents(DIR_LOGS . $logfile);
      }
    
    // автоматическое обновление описания товара по крону
    public function autoupdate() {
        
        $logfile = 'vkExport_cron_update.txt';
        $this->logging('Начало процесса', $logfile, FILE_APPEND);
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        $this->load->language('extension/vk_export');
        
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
        if ($this->config->get('vk_export_debug_mode')) {
            $log_file = DIR_LOGS . '.vkExportLog_autoupdate_' . date('d.m.y_H.i.s') . '_' . substr(md5(microtime()), 0, 7);
            $vkApi->set_log_on(true);
            $vkApi->set_log_file($log_file);
        }

        $products = $this->model_catalog_vk_export->getProductsToAutoupdate();
        $total = count($products);
        set_time_limit($total * 60);
        session_write_close(); 
        $this->logging('Старт обновления ' . $total . ' товаров', $logfile, FILE_APPEND);
        $deleted_out_of_stock = 0;
        $deleted_disabled = 0;
        $success = 0;
        foreach ($products as $product) {
            $p = $this->model_catalog_vk_export->getProduct($product['product_id']);
            $p['vk_photo_id'] = $product['vk_photo_id'];
            $photo = explode('_', $product['vk_photo_id']);
            $owner_id = $photo[0];
            $photo_id = $photo[1];
            
            $error = false;
                      
            $product_id = $product['product_id'];
            
            // удаляем товар, если его нет в наличии
            if ($this->config->get('vk_export_cron_delete_out_of_stock') && $p['quantity'] == 0) {
                
                try {
                    $vkApi->delete_photo($owner_id, $photo_id);
                    $this->model_catalog_vk_export->deleteAlbumsExportOne($p['product_id'], $p['vk_photo_id']);
                }
                catch (vkApiException $e) {
                    $error = 'При удалении из альбома товара "' . $p['name'] . '" произошла ошибка: ';
                    if ($comment = $e->getCodeComment()) {
                        $error .= $comment;
                    }
                    else {
                        $error .= $e;
                    }
                    $this->logging($error, $logfile, FILE_APPEND);
                    $error = true;
                }
                
                if (!$error) {
                    $deleted_out_of_stock++;
                }
                
            }
            // удаляем товар, если он отключен
            else if ($this->config->get('vk_export_cron_delete_disabled') && $p['status'] == 0) {
                try {
                    $vkApi->delete_photo($owner_id, $photo_id);
                    $this->model_catalog_vk_export->deleteAlbumsExportOne($p['product_id'], $p['vk_photo_id']);
                }
                catch (vkApiException $e) {
                    $error = 'При удалении из альбома товара "' . $p['name'] . '" произошла ошибка: ';
                    if ($comment = $e->getCodeComment()) {
                        $error .= $comment;
                    }
                    else {
                        $error .= $e;
                    }
                    $this->logging($error, $logfile, FILE_APPEND);
                    $error = true;
                }

                if (!$error) {
                    $deleted_disabled++;
                }
                
            }
            // или обновляем
            else {
                $this->errorMessage = '';
                if (!$this->makeUpdate($vkApi, $p, $owner_id, $photo_id)) {
                    $error = true;
                }
                if ($this->errorMessage) {
                    $error = true;
                    $this->logging($this->errorMessage, $logfile, FILE_APPEND);
                }
                
                if (!$error) {
                    $success++;
                }
            }
        }
        $msg = '';
        if ($success) {
            $msg = $this->language->get('text_vk_export_updated');
            $msg = sprintf($msg, $success, $total);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        if ($deleted_out_of_stock) {
            $msg = $this->language->get('text_vk_export_cron_deleted_out_of_stock');
            $msg = sprintf($msg, $deleted_out_of_stock);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        if ($deleted_disabled) {
            $msg = $this->language->get('text_vk_export_cron_deleted_disabled');
            $msg = sprintf($msg, $deleted_disabled);
            $this->logging($msg, $logfile, FILE_APPEND);
        }
        echo file_get_contents(DIR_LOGS . $logfile);
    }
    
    // отправка товаров на стену
    public function wallpost() {
        $this->load->language('extension/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        
        if (isset($this->request->post['selected']) && $this->validateVkExport()) {
            session_write_close(); 
            $report = array();
            $success = 0;
            
            $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
            if ($this->config->get('vk_export_debug_mode')) {
                $log_file = DIR_LOGS . '.vkExportLog_wall_' . date('d.m.y_H.i.s') . '_' . substr(md5(microtime()), 0, 7);
                $vkApi->set_log_on(true);
                $vkApi->set_log_file($log_file);
            }
            
            $total = count($this->request->post['selected']);
            set_time_limit($total * 60);
            $this->flushProgress();
            $this->cache->delete('vk_export_captcha_request');
            
            foreach ($this->request->post['selected'] as $key => $product_id) {
                $p = $this->model_catalog_vk_export->getProduct($product_id);
                $category_id = $this->request->post['album'][$product_id];
                
                if (!$p['image']) {
                    $report[] = 'Товар "' . $p['name'] . '" не имеет изображения';
                    continue;
                }
                
                $error = false;
                /*
                 * загрузка на стену пользователя
                 */
                if ($this->config->get('vk_export_mode') == 1 || $this->config->get('vk_export_mode') == 3) {
                    $this->errorMessage = '';
                    if (!$this->makeLoadWall($vkApi, $product_id, $p, $category_id, 'user')) {
                        $error = true;
                    }
                    if ($errmsg = $this->errorMessage) {
                        $report[] = $errmsg;
                    }
                }
                /*
                 * загрузка на стену группы
                 */
                if ($this->config->get('vk_export_mode') == 2 || $this->config->get('vk_export_mode') == 3) {
                    $this->errorMessage = '';
                    if (!$this->makeLoadWall($vkApi, $product_id, $p, $category_id, 'group')) {
                        $error = true;
                    }
                    if ($this->errorMessage) {
                        $report[] = $this->errorMessage;
                    }
                }
                
                if (!$error) {
                    $success++;
                }
                $this->writeProgress($success);
            }
            $msg = '';
            $vk_export_report = array();
            if ($success) {
                $msg = $this->language->get('text_vk_wallpost_success');
                $msg = sprintf($msg, $success, $total);
                $vk_export_report['success'] = $msg;
            }
            if ($report) {
                $vk_export_report['warning'] = implode('<br>', $report);
                if ($this->captcha_show) {
                    $vk_export_report['warning'] .= '<br><a id="send_captcha" class="button">Отправить</a>';
                }
            }
            
            if ($vk_export_report) {
                $this->cache->set('vk_export_report', $vk_export_report);
            }
            
            $url = '';
            
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }
        
            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . $this->request->get['filter_model'];
            }
            
            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }
            
            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }    
        
            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }
        
            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }
        
            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }
            
            if (isset($this->request->get['filter_export_albums'])) {
                $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
            }
            
            if (isset($this->request->get['filter_export_wall'])) {
                $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
            }
                    
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }
    
    // отправка товаров в товары :) (market)
    public function market_export() {
        $this->load->language('extension/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        
        if (isset($this->request->post['selected']) && $this->validateVkExport()) {
            session_write_close(); 
            $report = array();
            $result = array();
            $success = 0;
            
            $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
            if ($this->config->get('vk_export_debug_mode')) {
                $log_file = DIR_LOGS . '.vkExportLog_market_' . date('d.m.y_H.i.s') . '_' . substr(md5(microtime()), 0, 7);
                $vkApi->set_log_on(true);
                $vkApi->set_log_file($log_file);
            }
            
            $total = count($this->request->post['selected']);
            set_time_limit($total * 60);
            $this->flushProgress();
            $this->cache->delete('vk_export_captcha_request');
            
            // категории товаров ВК
            $category_vk_market_id = array();
            if (isset($this->request->post['category_vk_market_id'])) {
                foreach ($this->request->post['category_vk_market_id'] as $category_id => $vk_market_category_id) {
                    $res = $this->model_catalog_vk_export->getMarketAlbum($category_id);
                    if (!$res) {
                        $this->model_catalog_vk_export->insertMarket($category_id, $vk_market_category_id);
                    }
                    $category_vk_market_id[$category_id] = $vk_market_category_id;
                }
            }
            
            foreach ($this->request->post['selected'] as $key => $product_id) {
                $p = $this->model_catalog_vk_export->getProduct($product_id);
                $category_id = $this->request->post['album'][$product_id];
                $vk_market_category_id = $category_vk_market_id[$category_id];
                
                if (!$p['image']) {
                    $report[] = 'Товар "' . $p['name'] . '" не имеет изображения';
                    continue;
                }
                
                $error = false;
                
                $this->errorMessage = '';
                $vk_market_item = $this->makeLoadMarket($vkApi, $product_id, $p, $category_id, $vk_market_category_id);
                if (!$vk_market_item) {
                    $error = true;
                }
                if ($errmsg = $this->errorMessage) {
                    $report[] = $errmsg;
                }
                
                if (!$error) {
                    $result[] = '<a href="https://vk.com/market-' . $this->config->get('vk_export_group_id') . '?w=product' . $vk_market_item . '" target="_new">' . $p['name'] . '</a>';
                    $success++;
                }
                $this->writeProgress($success);
            }
            $msg = '';
            $vk_export_report = array();
            if ($success) {
                $msg = $this->language->get('text_vk_market_success');
                $msg = sprintf($msg, $success, $total);
                $vk_export_report['success'] = $msg;
                $vk_export_report['success'] .= '<br>' . implode('<br>', $result);
            }
            if ($report) {
                $vk_export_report['warning'] = implode('<br>', $report);
                if ($this->captcha_show) {
                    $vk_export_report['warning'] .= '<br><a id="send_captcha" class="button">Отправить</a>';
                }
            }
            
            if ($vk_export_report) {
                $this->cache->set('vk_export_report', $vk_export_report);
            }
            
            $url = '';
            
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }
        
            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . $this->request->get['filter_model'];
            }
            
            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }
            
            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }    
        
            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }
        
            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }
        
            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }
            
            if (isset($this->request->get['filter_export_albums'])) {
                $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
            }
            
            if (isset($this->request->get['filter_export_wall'])) {
                $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
            }
                    
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }
    
    private function flushProgress() {
        $this->cache->set('vk_export_progress', 0);
    }
    
    private function writeProgress($val) {
        $this->cache->set('vk_export_progress', $val);
    }
    
    public function progress() {
        echo $this->cache->get('vk_export_progress');
        exit();
    }
    
    private function generateDescription($tpl, $vars) {
        $message = str_replace(array_keys($vars), array_values($vars), $tpl);
        $message = html_entity_decode($message, ENT_COMPAT, 'UTF-8');
        $message = str_replace(array('<br>', '<br />', '<br/>', '</li>'), PHP_EOL, $message);
        $message = strip_tags($message);
        $message = str_replace(array('&nbsp;', '&amp;'), array(' ', '&'), $message);
        $user_replacements_keys = htmlspecialchars_decode($this->config->get('vk_export_user_replacements_keys'));
        $user_replacements_keys = explode("\n", $user_replacements_keys);
        $user_replacements_values = explode("\n", $this->config->get('vk_export_user_replacements_values'));
        $message = str_replace($user_replacements_keys, $user_replacements_values, $message);
        return $message;
    }
    
    private function generatePlaceholders($product_id, $p) {
        $lang_id = $this->config->get('config_language_id');
        
        $product_descriptions = $this->model_catalog_product->getProductDescriptions($product_id);
        $product_desc = $product_descriptions[$lang_id]['description'];
        
        // аттрибуты
        $this->load->model('catalog/attribute');
        $attributes_data = $this->model_catalog_product->getProductAttributes($product_id);
        $attributes = array();
        foreach ($attributes_data as $attr) {
            // название
            $attr_desc = $this->model_catalog_attribute->getAttributeDescriptions($attr['attribute_id']);
            $attr_name = (isset($attr_desc[$lang_id])) ? $attr_desc[$lang_id] : reset($attr_desc);
            $attr_name = $attr_name['name'];
            // значение
            if (isset($attr['product_attribute_description'][$lang_id]['text'])) {
                $attr_value = $attr['product_attribute_description'][$lang_id]['text'];
            }
            else {
                $attr_value = reset($attr['product_attribute_description']);
                $attr_value = $attr_value['text'];
            }
            $attributes[] = str_replace(array('{name}', '{value}'), array($attr_name, $attr_value), $this->config->get('vk_export_attributes_tpl'));
        }
        $attributes = implode($this->config->get('vk_export_attributes_delimeter'), $attributes);
        $attributes = str_replace('{br}', PHP_EOL, $attributes);
        
        // опции
        $this->load->model('catalog/option');
        $options = $this->model_catalog_product->getProductOptions($product_id);
        $options_output = '';
        foreach ($options as $opt) {
            if (!isset($opt['product_option_value'])) {
                continue;
            }
            // название
            $option_desc = $this->model_catalog_option->getOptionDescriptions($opt['option_id']);
            $option_name = (isset($option_desc[$lang_id])) ? $option_desc[$lang_id] : reset($option_desc);
            $option_name = $option_name['name'];
            $options_output .= $option_name . ': ';
            // значения
            $option_values = array();
            foreach ($opt['product_option_value'] as $opt_val) {
                $option_values[$opt_val['option_value_id']] = $this->model_catalog_vk_export->getProductOptionValueName($opt_val['option_value_id']);
            }
            sort($option_values);
            $options_output .= implode(', ', $option_values) . PHP_EOL;
        }
        
        if ($this->config->get('vk_export_http_catalog')) {
            $http_catalog = $this->config->get('vk_export_http_catalog');
        }
        else {
            if (!$this->config->get('config_secure')) {
                $http_catalog = HTTP_CATALOG;
            }
            else {
                $http_catalog = HTTPS_CATALOG;
            }
        }
        
        // ссылка
        $link = $http_catalog . 'index.php?route=product/product&product_id=' . $p['product_id'];
        
        // seo url
        if ($this->config->get('config_seo_url') && isset($p['category_id']) && $p['keyword']){
            $link = $http_catalog;
            // параметр config_seo_url_include_path отсутсвует с 1.5.5?
            if ($this->config->get('config_seo_url_include_path') !== 0) {
                $category = $this->model_catalog_category->getCategory($p['category_id']);
                $tmpcat = array();
                if (isset($category['keyword'])) {
                    if ($category['keyword']) {
                        $tmpcat[] = urlencode($category['keyword']);
                    }
                }
                if (isset($category['parent_id'])) {
                    while ($category['parent_id']) {
                        $category = $this->model_catalog_category->getCategory($category['parent_id']);
                        if ($category['keyword']) {
                            $tmpcat[] = urlencode($category['keyword']);
                        }
                    }
                }
                if ($tmpcat) {
                    $link .= implode('/', array_reverse($tmpcat)) . '/';
                }
            }
            $link .= urlencode($p['keyword']) . $this->config->get('config_seo_url_postfix');
        }
        
        /*
        // короткая ссылка vk.com/cc
        if ($this->config->get('vk_export_vkcc')) {
            $url = $vk->baseUrl . 'cc';
            $params = array();
            $params['act'] = 'shorten';
            $params['al'] = 1;
            $params['link'] = $link;
            $res = $vk->getPage($url, $vk->build_query($params));
            if (preg_match('/<!--\d+<!>.*?<!>\d*<!>\d*<!>\d*<!>(.*)/', $res, $matches)) {
                $link = $matches[1];
            }
            else {
                $this->errorMessage = 'Ошибка получения короткой ссылки.';
            }
            usleep($this->usleep);
        }
        */
        
        // наличие
        if ($p['quantity'] <= 0) {
            $stock = $p['stock_status'];
        } elseif ($this->config->get('config_stock_display')) {
            $stock = $p['quantity'];
        } else {
            $stock = 'Есть в наличии';
        }
        
        // цена
        $coef = $this->config->get('vk_export_price_coef');
        if (!$coef) {
            $coef = 1;
        }
        $p['price'] = $p['price'] * $coef;
        // основная валюта
        $price = $this->currency->format($p['price'], $this->config->get('config_currency'));
        $price_float = round((float)$p['price'] * $this->currency->getValue($this->config->get('config_currency')), 2);
        $price_old_float = round((float)$p['price'] * $this->currency->getValue($this->config->get('config_currency')), 2);
        
        // другие валюты
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency");
        $currencies = array();
        foreach ($query->rows as $result) {
            $pc = $this->currency->convert($p['price'], $this->config->get('config_currency'), $result['code']);
            $pc = $this->currency->format($pc, $result['code'], 1);
            $currencies[$result['code']] = $pc;
        }
        
        // спец цена
        $special_price = false;
        $special_price_float = false;
        $product_specials = $this->model_catalog_product->getProductSpecials($p['product_id']);
        foreach ($product_specials  as $product_special) {
            // акционные цены только для основной группы покупателей
            if ($product_special['customer_group_id'] != $this->config->get('config_customer_group_id')) continue;
            
            if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d'))
                && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
                $special_price = $product_special['price'];
                break;
            }                    
        }
        if ($special_price) {
            $special_price = $special_price * $coef;
            $special_price_float = round((float)$special_price * $this->currency->getValue($this->config->get('config_currency')), 2);
            $special_text = $this->language->get('text_vk_export_special_price');
            
            // другие валюты
            foreach ($currencies as $code => $v) {
                $sp = $this->currency->convert($special_price, $this->config->get('config_currency'), $code);
                $sp = $this->currency->format($sp, $code, 1);
                $sp = sprintf($special_text, $sp, $v);
                $currencies[$code] = $sp;
            }
            
            $price_float = round((float)$special_price * $this->currency->getValue($this->config->get('config_currency')), 2);
            $special_price = $this->currency->format($special_price, $this->config->get('config_currency'));
            $price = sprintf($special_text, $special_price, $price);
        }
        
        // теги товара как хеш-теги
        $hashtags = array();
        $_tags = explode(',', $product_descriptions[$lang_id]['tag']);
        foreach ($_tags as $k => $v) {
            $v = trim($v);
            if (!$v) continue;
            $hashtags[] = '#' . str_replace(' ', '_', $v);
        }
        $hashtags = implode(', ', $hashtags);
        
        // единица веса
        $this->load->model('localisation/weight_class');
        $weight_class = $this->model_localisation_weight_class->getWeightClass($p['weight_class_id']);
        
        // единица меры
        $this->load->model('localisation/length_class');
        $length_class = $this->model_localisation_length_class->getLengthClass($p['length_class_id']);
        
        $vars = array(
            '{name}'       => $p['name'],
            '{model}'      => $p['model'],
            '{weight}'     => $p['weight'], // вес
            '{weight_class}' => $weight_class['unit'], // единица веса
            '{length}'     => $p['length'],
            '{width}'      => $p['width'],
            '{height}'     => $p['height'],
            '{length_class}' => $length_class['unit'], // единица меры
            '{minimum}'    => $p['minimum'], // Minimum Quantity
            '{desc}'       => $product_desc,
            '{attr}'       => $attributes,
            '{options}'    => $options_output,
            '{link}'       => $link,
            '{sku}'        => $p['sku'],
            '{stock}'      => $stock,
            '{price}'      => $price,
            '{price_float}' => $price_float,
            '{price_old_float}' => $price_old_float,
            '{special_price_float}' => $special_price_float,
            '{producer}'   => $p['manufacturer'],
            '{meta_description}' => $product_descriptions[$lang_id]['meta_description'],
            '{tags}'       => $product_descriptions[$lang_id]['tag'],
            '{hashtags}'   => $hashtags,
        );
        
        foreach ($currencies as $code => $v) {
            $vars['{price_' . $code . '}'] = $v;
        }
        
        return $vars;
    }
    
    private function makeLoad($vkApi, $product_id, $category_id, $p, $mode) {
        
        $album_id = $this->model_catalog_vk_export->getAlbumId($category_id, $mode);
        $lang_id = $this->config->get('config_language_id');
        if ($mode == 'group' && !$this->config->get('vk_export_group_id')) {
            $this->errorMessage = 'Не указан ID группы Вконтакте';
            return false;
        }
        if (!$album_id) {
            // создать альбом
            if ($mode == 'user') {
                $owner = false;
            }
            else {
                $owner = $this->config->get('vk_export_group_id');
            }
            $category = $this->model_catalog_category->getCategoryDescriptions($category_id);
            
            if ($this->config->get('vk_export_album_name_mode') == 1) {
                $album_name = $category[$lang_id]['name'];
            }
            else {
                $album_name = $this->model_catalog_vk_export->getCategoryPath($category_id);
            }
            
            if ($this->config->get('vk_export_album_only') == 1) {
                $upload_by_admins_only = 1;
            }
            else {
                $upload_by_admins_only = 0;
            }
            // создать альбом
            try {
                $res = $vkApi->create_album(htmlspecialchars_decode($album_name), $owner, $upload_by_admins_only);
                if ($res) {
                    $album_id = $res->owner_id . '_' . $res->id;
                    $this->model_catalog_vk_export->insertAlbum($category_id, $album_id, $mode);
                }
            } catch (vkApiException $e) {
                $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> произошла ошибка создания альбома:<br>' . $e;
                if ($comment = $e->getCodeComment()) {
                    $this->errorMessage .= '<br><b>' . $comment . '</b>';
                }
                return false;
            }
        }
        $album_id_int = explode('_', $album_id);
        $group_id = str_replace('-', '', $album_id_int[0]);
        if ($group_id == $this->config->get('vk_export_user_id')) {
            $group_id = false;
        }
        $album_id_int = $album_id_int[1];
        
        
        $this->load->model('tool/image');
        
        $images_in = array($p['image']);
        
        // дополнительные фото
        if ($this->config->get('vk_export_photos_count') != 1) {
            $limit = $this->config->get('vk_export_photos_count');
            $product_images = $this->model_catalog_product->getProductImages($product_id);
            $i = 1;
            foreach ($product_images as $img) {
                $i++;
                if ($i > $limit) break;
                $images_in[] = $img['image'];
            }
        }
        
        $p['category_id'] = $category_id;
        $vars = $this->generatePlaceholders($product_id, $p);
        
        // добавить переменную для вывода категории
        $category = $this->model_catalog_category->getCategoryDescriptions($category_id);
        $vars['{category}'] = $category[$lang_id]['name'];
        $vars['{category_full}'] = $this->model_catalog_vk_export->getCategoryPath($category_id);
        
        // описание
        if ($this->config->get('vk_export_mode_desc')) {
            $desc = $this->generateDescription($this->config->get('vk_export_desc_tpl'), $vars);
        }
        
        // комментарий
        if ($this->config->get('vk_export_mode_comment')) {
            $comment = $this->generateDescription($this->config->get('vk_export_comment_tpl'), $vars);
        }
    
        $images_out = array();
        foreach ($images_in as $img) {
            if ($this->config->get('vk_export_image_mode') == 1) {
                $img = DIR_IMAGE . $img;
            }
            else {
                // изменение размера фото
                $img = $this->model_tool_image->resize($img, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                // определяем физический путь к файлу
                if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                    $img = DIR_IMAGE . str_replace(HTTPS_CATALOG, '', $img);
                } else {
                    $img = DIR_IMAGE . str_replace(HTTP_CATALOG, '', $img);
                }    
                $img = str_replace('/image/image/', '/image/', $img);
            }
            if (!file_exists($img)) {
                continue;
            }
            $images_out[] = $img;
        }
        
        if (!$images_out) {
            $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> произошла ошибка: Отсутствуют изображения товара';
            return false;
        }
        
        try {
            // загрузить фотографии
            $photos_done = $vkApi->upload_photo_album($album_id_int, $group_id, $images_out, $desc);
            foreach ($photos_done as $i) {
                $photo_id = $i->owner_id . '_' . $i->id;
                
                // добавить комментарий
                if ($this->config->get('vk_export_mode_comment')) {
                    if (trim($comment)) {
                        $owner_id = $group_id ? ('-' . $group_id) : $this->config->get('vk_export_user_id');
                        $res = $vkApi->add_photo_comment($owner_id, $i->id, $comment, $this->config->get('vk_export_group_photo_comment_from'));
                        // капча
                        if (isset($res->error) && $res->error->error_code == 14 && !$this->cron) {
                            $res->error->action_comment = 'Добавление комментария к товару <b>' . $p['name'] . '</b>';
                            $this->catch_captcha($res);
                            $this->errorMessage = 'При добавлении комментария к товару <b>' . $p['name'] . '</b> получена <b>капча</b>:<br>';
                            $this->errorMessage .= '<img src="' . $res->error->captcha_img . '">';
                            $this->errorMessage .= '</pre><input class="vk_captcha" type="text" name="captcha[' . $res->error->captcha_sid . ']" value="">';
                            $this->captcha_show = true;
                        }
                        else if (isset($res->error)) {
                            $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> произошла ошибка добавления комментария:<br>';
                            $this->errorMessage .= $res->error->error_msg . ' [' . $res->error->error_code . ']';
                        }
                        if ($this->comments_count == 2) {
                            $this->sleep_comments(count($photos_done));
                            $this->comments_count = 0;
                        }
                        $this->comments_count++;
                    }
                }
                
                // сохранить экспорт
                $this->model_catalog_vk_export->setProductExport($product_id, $photo_id, $category_id);
                $this->total_pics_load++;
                
            }
        } catch (vkApiException $e) {
            $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
            if ($comment = $e->getCodeComment()) {
                $this->errorMessage .= '<br><b>' . $comment . '</b>';
            }
            else {
                $this->errorMessage .= ' ' . $e;
            }
            if ($e->getCode() == 200) {
                $this->errorMessage .= ' Возможно, альбом не существует. Альбом удалён из списка альбомов. При следующем экспорте данного товара, альбом будет создан автоматически, либо вы можете указать произвольный альбом для категории данного товара в списке альбомов.';
                $this->model_catalog_vk_export->deleteAlbum($album_id);
            }
            return false;
        } catch (antigateException $e) {
            $this->errorMessage = 'При экспорте товара "' . $p['name'] . '" произошла ошибка каптчи antigate.com:<br>' . $e;
            return false;
        }
        
        return true;
    }
    
    /*
     * Обработка капчи
     * @param    object    ответ апи
     * @param    array    функция для выполнения после успешной капчи
     */
    private function catch_captcha($response, $todo = null) {
        $cache = $this->cache->get('vk_export_captcha_request');
        if (!$cache) {
            $cache = array();
        }
        $cache[$response->error->captcha_sid] = array('error' => $response->error, 'todo' => $todo);
        $this->cache->set('vk_export_captcha_request', $cache);
    }
    
    
    /*
     * Пауза между добавлением комментариев к фото(срабатывает через каждые 2 загрузки фото)
     */
    private function sleep_comments($count) {
        $min = 4000000;
        $max = 5000000;
        $add = 0;
        if ($count > 1) {
            $add = 1000000;
        }
        if ($count > 2) {
            $add = 2500000;
        }
        if ($count > 3) {
            $add = 3500000;
        }
        if ($count > 4) {
            $add = 4500000;
        }
        $rand = rand($min + $add, $max + $add);
        usleep($rand);
    }
    
    /*
     * Пауза между экспортом одного товара
     */
    private function sleep_export() {
        $rand = rand(6000000, 7500000);
        //usleep($rand);
    }
    
    // загрузка в произвольный альбом
    private function makeLoadQuick($vkApi, $product_id, $album_id, $category_id, $p) {
        $lang_id = $this->config->get('config_language_id');
        
        $album = explode('_', $album_id);
        $group_id = false;
        
        if (strpos($album[0], '-') !== false) {
            $group_id = str_replace('-', '', $album[0]);
        }
        else {
            $user_id = $album[0];
        }
        $album_id_int = $album[1];
        
        
        $this->load->model('tool/image');
        
        $images_in = array($p['image']);
        
        // дополнительные фото
        if ($this->config->get('vk_export_photos_count') != 1) {
            $limit = $this->config->get('vk_export_photos_count');
            $product_images = $this->model_catalog_product->getProductImages($product_id);
            $i = 1;
            foreach ($product_images as $img) {
                $i++;
                if ($i > $limit) break;
                $images_in[] = $img['image'];
            }
        }
        
        $p['category_id'] = $category_id;
        $vars = $this->generatePlaceholders($product_id, $p);
        
        // описание
        if ($this->config->get('vk_export_mode_desc')) {
            $desc = str_replace(array_keys($vars), array_values($vars), $this->config->get('vk_export_desc_tpl'));
            $desc = strip_tags(html_entity_decode($desc, ENT_COMPAT, 'UTF-8'));
            $desc = str_replace(array('&nbsp;'), array(' '), $desc);
            $user_replacements_keys = htmlspecialchars_decode($this->config->get('vk_export_user_replacements_keys'));
            $user_replacements_keys = explode("\n", $user_replacements_keys);
            $user_replacements_values = explode("\n", $this->config->get('vk_export_user_replacements_values'));
            $desc = str_replace($user_replacements_keys, $user_replacements_values, $desc);
        }
        
        // комментарий
        if ($this->config->get('vk_export_mode_comment')) {
            $comment = str_replace(array_keys($vars), array_values($vars), $this->config->get('vk_export_comment_tpl'));
            $comment = strip_tags(html_entity_decode($comment, ENT_COMPAT, 'UTF-8'));
            $comment = str_replace(array('&nbsp;'), array(' '), $comment);
            $user_replacements_keys = htmlspecialchars_decode($this->config->get('vk_export_user_replacements_keys'));
            $user_replacements_keys = explode("\n", $user_replacements_keys);
            $user_replacements_values = explode("\n", $this->config->get('vk_export_user_replacements_values'));
            $comment = str_replace($user_replacements_keys, $user_replacements_values, $comment);
        }
    
        $images_out = array();
        foreach ($images_in as $img) {
            if ($this->config->get('vk_export_image_mode') == 1) {
                $img = DIR_IMAGE . $img;
            }
            else {
                // изменение размера фото
                $img = $this->model_tool_image->resize($img, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                $img = DIR_IMAGE . str_replace(HTTP_CATALOG, '', $img);
                $img = str_replace('/image/image/', '/image/', $img);
            }
            if (!file_exists($img)) {
                continue;
            }
            $images_out[] = $img;
        }
        
        if (!$images_out) {
            $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> произошла ошибка: Отсутствуют изображения товара';
            return false;
        }
        
        try {
            // загрузить фотографии
            $photos_done = $vkApi->upload_photo_album($album_id_int, $group_id, $images_out, $desc);
            foreach ($photos_done as $i) {
                $photo_id = $i->owner_id . '_' . $i->id;
                
                // добавить комментарий
                if ($this->config->get('vk_export_mode_comment')) {
                    if (trim($comment)) {
                        $res = $vkApi->add_photo_comment($i->owner_id, $i->id, $comment, $this->config->get('vk_export_group_photo_comment_from'));
                        // капча
                        if (isset($res->error) && $res->error->error_code == 14 && !$this->cron) {
                            $res->error->action_comment = 'Добавление комментария к товару <b>' . $p['name'] . '</b>';
                            $this->catch_captcha($res);
                            $this->errorMessage = 'При добавлении комментария к товару <b>' . $p['name'] . '</b> получена <b>капча</b>:<br>';
                            $this->errorMessage .= '<img src="' . $res->error->captcha_img . '">';
                            $this->errorMessage .= '</pre><input class="vk_captcha" type="text" name="captcha[' . $res->error->captcha_sid . ']" value="">';
                            $this->captcha_show = true;
                        }
                        else if (isset($res->error)) {
                            $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> произошла ошибка добавления комментария:<br>';
                            $this->errorMessage .= $res->error->error_msg . ' [' . $res->error->error_code . ']';
                        }
                        if ($this->comments_count == 2) {
                            $this->sleep_comments(count($photos_done));
                            $this->comments_count = 0;
                        }
                        $this->comments_count++;
                    }
                }
                
                // сохранить экспорт
                $this->model_catalog_vk_export->setProductExport($product_id, $photo_id, $category_id);
                $this->total_pics_load++;
                
            }
        } catch (vkApiException $e) {
            $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
            if ($comment = $e->getCodeComment()) {
                $this->errorMessage .= '<br><b>' . $comment . '</b>';
            }
            else {
                $this->errorMessage .= ' ' . $e;
            }
            if ($e->getCode() == 200) {
                $this->errorMessage .= ' Возможно, альбом не существует. Альбом удалён из списка альбомов. При следующем экспорте данного товара, альбом будет создан автоматически, либо вы можете указать произвольный альбом для категории данного товара в списке альбомов.';
                $this->model_catalog_vk_export->deleteAlbum($album_id);
            }
            return false;
        } catch (antigateException $e) {
            $this->errorMessage = 'При экспорте товара "' . $p['name'] . '" произошла ошибка каптчи antigate.com:<br>' . $e;
            return false;
        }
        
        return true;
    }
    
    // обновление описания фото в альбоме
    private function makeUpdate($vkApi, $p, $owner_id, $photo_id) {
        $product_id = $p['product_id'];
        
        $vars = $this->generatePlaceholders($product_id, $p);
        
        // обновить описание
        if ($this->config->get('vk_export_mode_desc')) {
            $desc = $this->generateDescription($this->config->get('vk_export_desc_tpl'), $vars);
            
            try {
                $res = $vkApi->edit_photo($owner_id, $photo_id, $desc);
                $this->model_catalog_vk_export->updateProductExportDate($product_id);
            } catch (vkApiException $e) {
                $this->errorMessage = 'При обновлении описания к фотографии товара "' . $p['name'] . '" произошла ошибка: ';
                if ($comment = $e->getCodeComment()) {
                    $this->errorMessage .= $comment;
                }
                else {
                    $this->errorMessage .= $e;
                }
            }
            
        }
        
        return true;
    }
    
    // реализация отправки товаров на стену
    private function makeLoadWall($vkApi, $product_id, $p, $category_id, $mode) {
        $lang_id = $this->config->get('config_language_id');
        if ($mode == 'group' && !$this->config->get('vk_export_group_id')) {
            $this->errorMessage = 'Не указан ID группы Вконтакте';
            return false;
        }
        $this->load->model('tool/image');
        
        $images_in = array($p['image']);
        
        // дополнительные фото
        if ($this->config->get('vk_export_wallpost_photos_count') != 1) {
            $limit = $this->config->get('vk_export_wallpost_photos_count');
            if ($limit == 'all') {
                $limit = 99;
            }
            $product_images = $this->model_catalog_product->getProductImages($product_id);
            $i = 1;
            foreach ($product_images as $img) {
                $i++;
                if ($i > $limit) break;
                $images_in[] = $img['image'];
            }
        }
        
        $images_out = array();
        foreach ($images_in as $img) {
            if ($this->config->get('vk_export_image_mode') == 1) {
                $img = DIR_IMAGE . $img;
            }
            else {
                // изменение размера фото
                $img = $this->model_tool_image->resize($img, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                $img = str_replace(HTTPS_CATALOG, '', $img);
                $img = str_replace(HTTP_CATALOG, '', $img);
                $img = DIR_IMAGE . $img;
                $img = str_replace('/image/image/', '/image/', $img);
            }
            if (!file_exists($img)) {
                continue;
            }
            $images_out[] = $img;
        }
        
        if (!$images_out) {
            $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> произошла ошибка: Отсутствуют изображения товара';
            return false;
        }
        
        $p['category_id'] = $category_id;
        $vars = $this->generatePlaceholders($product_id, $p);
        
        // добавить переменную для вывода категории
        $category = $this->model_catalog_category->getCategoryDescriptions($category_id);
        $vars['{category}'] = $category[$lang_id]['name'];
        $vars['{category_full}'] = $this->model_catalog_vk_export->getCategoryPath($category_id);
        
        try {
            if ($mode == 'user') {
                $post_from_group = false;
                $owner_id = $this->config->get('vk_export_user_id');
                $photoWall = $vkApi->upload_photo_wall($this->config->get('vk_export_user_id'), $images_out);
            }
            else {
                $post_from_group = ( $this->config->get('vk_export_group_wallpost_from') == 2 ) ? true : false ;
                $owner_id = '-' . $this->config->get('vk_export_group_id');
                $photoWall = $vkApi->upload_photo_wall($this->config->get('vk_export_user_id'), $images_out, $this->config->get('vk_export_group_id'));
            }
        }
        catch (vkApiException $e) {
            $this->errorMessage = 'При экспорте на стену товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
            if ($comment = $e->getCodeComment()) {
                $this->errorMessage .= '<br><b>' . $comment . '</b>';
            }
            else {
                $this->errorMessage .= ' ' . $e;
            }
            return false;
        }
        
        // сообщение
        $message = $this->generateDescription($this->config->get('vk_export_wallpost_tpl'), $vars);
        
        $attach = array();
        foreach ($photoWall as $pic) {
            $attach[] = 'photo' . $pic->owner_id . '_' . $pic->id;
        }
        $attach = implode(',', $attach);
        
        try {
            $res = $vkApi->wall_post($owner_id, $message, $attach, $post_from_group, $this->config->get('vk_export_wall_export_services'));
            // капча
            if (isset($res->error) && $res->error->error_code == 14 && !$this->cron) {
                $res->error->action_comment = 'Экспорт на стену товара <b>' . $p['name'] . '</b>';
                $todo = array(
                    'action'      => 'addWallPost', 
                    'product_id'  => $product_id,
                    'owner_id'    => $owner_id,
                    'category_id' => $category_id,
                );
                $this->catch_captcha($res, $todo);
                $this->errorMessage = 'При экспорте на стену товара <b>' . $p['name'] . '</b> получена <b>капча</b>:<br>';
                $this->errorMessage .= '<img src="' . $res->error->captcha_img . '">';
                $this->errorMessage .= '</pre><input class="vk_captcha" type="text" name="captcha[' . $res->error->captcha_sid . ']" value="">';
                $this->captcha_show = true;
            }
            else {
                $this->model_catalog_vk_export->addWallPost($product_id, $owner_id . '_' . $res->post_id, $category_id);
            }
        }
        catch (vkApiException $e) {
            $this->errorMessage = 'При экспорте на стену товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
            if ($comment = $e->getCodeComment()) {
                $this->errorMessage .= '<br><b>' . $comment . '</b>';
            }
            else {
                $this->errorMessage .= ' ' . $e;
            }
            return false;
        }
        
        
        return true;
    }
    
    // реализация отправки товаров в маркет
    private function makeLoadMarket($vkApi, $product_id, $p, $category_id, $vk_market_category_id) {
        $lang_id = $this->config->get('config_language_id');
        if (!$this->config->get('vk_export_group_id')) {
            $this->errorMessage = 'Не указан ID группы Вконтакте';
            return false;
        }
        $this->load->model('tool/image');
        
        $market_album = $this->model_catalog_vk_export->getMarketAlbum($category_id);
        $album_id = $market_album['vk_market_album_id'];
        
        $category = $this->model_catalog_category->getCategoryDescriptions($category_id);
        
        $images_in = array($p['image']);
        
        // дополнительные фото
        if ($this->config->get('vk_export_market_photos_count') != 1) {
            $limit = $this->config->get('vk_export_market_photos_count');
            if ($limit == 'all') {
                $limit = 99;
            }
            $product_images = $this->model_catalog_product->getProductImages($product_id);
            $i = 1;
            foreach ($product_images as $img) {
                $i++;
                if ($i > $limit) break;
                $images_in[] = $img['image'];
            }
        }
        
        $images_out = array();
        $images_out_index = array();
        foreach ($images_in as $img) {
            if ($this->config->get('vk_export_image_mode') == 1) {
                $_img = DIR_IMAGE . $img;
            }
            else {
                // изменение размера фото
                $_img = $this->model_tool_image->resize($img, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                $_img = str_replace(HTTPS_CATALOG, '', $_img);
                $_img = str_replace(HTTP_CATALOG, '', $_img);
                $_img = DIR_IMAGE . $_img;
                $_img = str_replace('/image/image/', '/image/', $_img);
            }
            if (!file_exists($_img)) {
                continue;
            }
            $images_out[] = $_img;
            $images_out_index[] = $img;
        }
        
        if (!$images_out) {
            $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> произошла ошибка: Отсутствуют изображения товара';
            return false;
        }
        
        $p['category_id'] = $category_id;
        $vars = $this->generatePlaceholders($product_id, $p);
        
        // добавить переменную для вывода категории
        $category = $this->model_catalog_category->getCategoryDescriptions($category_id);
        $vars['{category}'] = $category[$lang_id]['name'];
        $vars['{category_full}'] = $this->model_catalog_vk_export->getCategoryPath($category_id);
        
        // минимальный размер 400х400
        foreach ($images_out as $img_key => $img) {
            list($img_width, $img_height) = getimagesize($img);
            if ($img_width < 400 || $img_height < 400) {
                //unset($images_out[$img_key]);
                $_img = $this->model_tool_image->resize($images_out_index[$img_key], 400, 400);
                $_img = str_replace(HTTPS_CATALOG, '', $_img);
                $_img = str_replace(HTTP_CATALOG, '', $_img);
                $_img = DIR_IMAGE . $_img;
                $_img = str_replace('/image/image/', '/image/', $_img);
                $images_out[$img_key] = $_img;
            }
        }
        if (!$images_out) {
            $this->errorMessage = 'При экспорте товара <b>[id: ' . $product_id . '] ' . $p['name'] . '</b> произошла ошибка:<br>';
            $this->errorMessage .= 'Отсутствуют фотографии товара минимально допустимого размера 400х400.';
            return false;
        }
        
        // загрузка фото
        try {
            $data = array(
                'group_id'   => $this->config->get('vk_export_group_id'),
                'main_photo' => 1,
            );
            $photoMarket = $vkApi->upload_photo_market($data, $images_out);
        }
        catch (vkApiException $e) {
            $this->errorMessage = 'При экспорте товара <b>[id: ' . $product_id . '] ' . $p['name'] . '</b> произошла ошибка:<br>';
            if ($comment = $e->getCodeComment()) {
                $this->errorMessage .= '<br><b>' . $comment . '</b>';
            }
            else {
                $this->errorMessage .= ' ' . $e;
            }
            return false;
        }
        
        // описание
        $message = $this->generateDescription($this->config->get('vk_export_market_product_desc_tpl'), $vars);
        
        $main_photo_id = array_shift($photoMarket);
        $main_photo_id = $main_photo_id->id;
        
        $photo_ids = array();
        foreach ($photoMarket as $pic) {
            $photo_ids[] = $pic->id;
        }
        $photo_ids = implode(',', $photo_ids);
        
        $name = mb_substr(htmlspecialchars_decode($vars['{name}']), 0, 98, 'UTF-8');
        $sku = '';
        if (isset($p['sku']) && $p['sku']) {
            $sku = mb_substr(htmlspecialchars_decode($p['sku']), 0, 49, 'UTF-8');
        }
        
        $vk_market_item = null;
        
        // конвертировать размеры
        $vk_export_mm_class_id = $this->config->get('vk_export_mm_class_id');
        if ($vk_export_mm_class_id && $p['length_class_id'] && $p['length_class_id'] != $vk_export_mm_class_id) {
            $this->load->model('localisation/length_class');
            $product_length_class = $this->model_localisation_length_class->getLengthClass($p['length_class_id']);
            $mm_length_class = $this->model_localisation_length_class->getLengthClass($vk_export_mm_class_id);
            $p['width'] = $p['width'] / $product_length_class['value'] * $mm_length_class['value'];
            $p['height'] = $p['height'] / $product_length_class['value'] * $mm_length_class['value'];
            $p['length'] = $p['length'] / $product_length_class['value'] * $mm_length_class['value'];
        }
        
        // конвертировать размеры
        $vk_export_gramm_class_id = $this->config->get('vk_export_gramm_class_id');
        if ($vk_export_gramm_class_id && $p['weight_class_id'] && $p['weight_class_id'] != $vk_export_mm_class_id) {
            $this->load->model('localisation/weight_class');
            $product_weight_class = $this->model_localisation_weight_class->getWeightClass($p['weight_class_id']);
            $gramm_weight_class = $this->model_localisation_weight_class->getWeightClass($vk_export_mm_class_id);
            $p['weight'] = $p['weight'] / $product_weight_class['value'] * $gramm_weight_class['value'];
        }
        
        try {
            $data = array(
                'owner_id'      => '-' . $this->config->get('vk_export_group_id'),
                'name'          => $name,
                'description'   => $message,
                'category_id'   => $vk_market_category_id,
                'price'         => $vars['{price_float}'],
                'price_old_float' => $vars['{price_old_float}'],
                'special_price_float' => $vars['{special_price_float}'],
                'deleted'       => 0,
                'main_photo_id' => $main_photo_id,
                'photo_ids'     => $photo_ids,
                'url'           => $vars['{link}'],
                'sku'              => $sku,
            );
            
            if ($p['length_class_id']) {
                $data['dimension_width'] = $p['width'];
                $data['dimension_height'] = $p['height'];
                $data['dimension_length'] = $p['length'];
            }
            
            if ($p['weight_class_id']) {
                $data['weight'] = $p['weight'];
            }
            
            $res = $vkApi->market_add($data);
            $market_item_id = $res->market_item_id;
            // капча
            // не отлажено!
            if (isset($res->error) && $res->error->error_code == 14 && !$this->cron) {
                $res->error->action_comment = 'Экспорт товара <b>' . $p['name'] . '</b>';
                $todo = array(
                    'action'      => 'addMarket', // !!!!!!!!!!! тестовое значение
                    'product_id'  => $product_id,
                    'owner_id'    => $this->config->get('vk_export_group_id'),
                    'category_id' => $category_id,
                );
                $this->catch_captcha($res, $todo);
                $this->errorMessage = 'При экспорте товара <b>[id: ' . $product_id . '] ' . $p['name'] . '</b> получена <b>капча</b>:<br>';
                $this->errorMessage .= '<img src="' . $res->error->captcha_img . '">';
                $this->errorMessage .= '</pre><input class="vk_captcha" type="text" name="captcha[' . $res->error->captcha_sid . ']" value="">';
                $this->captcha_show = true;
            }
            else {
                // сохранение экспорта товара в БД
                $this->model_catalog_vk_export->addMarket(
                    $product_id, 
                    '-' . $this->config->get('vk_export_group_id') . '_' . $market_item_id, 
                    $category_id
                );
                
                $vk_market_item = '-' . $this->config->get('vk_export_group_id') . '_' . $market_item_id;
                
                /*
                 * добавить товар в подборку
                 */
                if ($this->config->get('vk_export_create_market_albums')) {
                    
                    // создать подборку
                    if (!$album_id) {

                        if ($this->config->get('vk_export_album_name_mode') == 1) {
                            $album_name = $category[$lang_id]['name'];
                        }
                        else {
                            $album_name = $this->model_catalog_vk_export->getCategoryPath($category_id);
                        }
                        
                        // загрузить обложку альбома
                        try {
                            $img = $p['image'];
                            list($img_width, $img_height) = getimagesize($img);
                            if ($img_width < 1280 || $img_height < 720) {
                                $img = $this->model_tool_image->resize($img, 1280, 720);
                                $img = str_replace(HTTPS_CATALOG, '', $img);
                                $img = str_replace(HTTP_CATALOG, '', $img);
                                $img = DIR_IMAGE . $img;
                                $img = str_replace('/image/image/', '/image/', $img);
                            }
                            
                            $market_album_photo = $vkApi->upload_market_album_photo(
                                $this->config->get('vk_export_group_id'), 
                                $img
                            );
                        }
                        catch (vkApiException $e) {
                            $this->errorMessage = 'При экспорте товара <b>[id: ' . $product_id . '] ' . $p['name'] . '</b> произошла ошибка загрузки фотографии обложки подборки товаров:<br>';
                            if ($comment = $e->getCodeComment()) {
                                $this->errorMessage .= '<br><b>' . $comment . '</b>';
                            }
                            else {
                                $this->errorMessage .= ' ' . $e;
                            }
                            return false;
                        }
                        
                        // создать альбом
                        try {
                            $res = $vkApi->create_market_album(
                                htmlspecialchars_decode($album_name), 
                                '-' . $this->config->get('vk_export_group_id'),
                                $market_album_photo[0]->id
                            );
                            if ($res) {
                                $album_id = $res->market_album_id;
                                $this->model_catalog_vk_export->editMarketAlbum($category_id, $album_id);
                            }
                        } catch (vkApiException $e) {
                            $this->errorMessage = 'При экспорте товара <b>[id: ' . $product_id . '] ' . $p['name'] . '</b> произошла ошибка создания подборки:<br>';
                            if ($comment = $e->getCodeComment()) {
                                $this->errorMessage .= '<br><b>' . $comment . '</b>';
                            }
                            $this->errorMessage .= $e;
                            return false;
                        }
                    } // end of "создать подборку"
                    
                    // добавление товара в подборку
                    try {
                        $photoMarket = $vkApi->market_add_to_album(
                            '-' . $this->config->get('vk_export_group_id'),
                            $market_item_id,
                            $album_id
                        );
                    }
                    catch (vkApiException $e) {
                        $this->errorMessage = 'При добавлении в подборку  товара <b>[id: ' . $product_id . '] ' . $p['name'] . '</b> произошла ошибка:<br>';
                        if ($comment = $e->getCodeComment()) {
                            $this->errorMessage .= '<br><b>' . $comment . '</b>';
                        }
                        else {
                            $this->errorMessage .= ' ' . $e;
                        }
                        return false;
                    }
                } // end of "добавить товар в подборку"
                
                /**
                 *  удаление копий товара (для крона)
                 */
                if ($this->cron && $this->config->get('vk_export_delete_market_copies')) {
                    $exports = $this->model_catalog_vk_export->getMarketExport($product_id);
                    $vk_photo_id = '-' . $this->config->get('vk_export_group_id') . '_' . $market_item_id;
                    $cd = 0;
                    foreach ($exports as $export) {
                        if ($export['vk_photo_id'] != $vk_photo_id) {
                            $photo = explode('_', $export['vk_photo_id']);
                            $owner_id = $photo[0];
                            $item_id = $photo[1];
                            
                            try {
                                $vkApi->delete_market($owner_id, $item_id);
                                $this->model_catalog_vk_export->deleteMarketExportOne($product_id, $export['vk_photo_id']);
                            }
                            catch (vkApiException $e) {
                                $this->errorMessage = 'При удалении копии товара <b>[id: ' . $product_id . '] ' . $p['name'] . '</b> произошла ошибка:<br>';
                                if ($comment = $e->getCodeComment()) {
                                    $this->errorMessage .= '<br><b>' . $comment . '</b>';
                                }
                                else {
                                    $this->errorMessage .= ' ' . $e;
                                }
                                return false;
                            }
                            $cd++;
                        }
                    }
                    if ($cd) {
                        $this->logging('Удалено ' . $cd . ' копий товара <b>[id: ' . $product_id . '] ' . $p['name'] . '</b>', $this->logfile, FILE_APPEND);
                    }
                }
                
            }
        }
        catch (vkApiException $e) {
            $this->errorMessage = 'При экспорте товара <b>[id: ' . $product_id . '] ' . $p['name'] . '</b> произошла ошибка:<br>';
            if ($comment = $e->getCodeComment()) {
                $this->errorMessage .= '<br><b>' . $comment . '</b>';
            }
            else {
                $this->errorMessage .= ' ' . $e;
            }
            return false;
        }
        
        
        return $vk_market_item;
    }
    
    // реализация обновления товаров в маркете
    private function makeUpdateMarket($vkApi, $product_id, $p, $deleted = false) {
        $lang_id = $this->config->get('config_language_id');
        if (!$this->config->get('vk_export_group_id')) {
            $this->errorMessage = 'Не указан ID группы Вконтакте';
            return false;
        }
        $this->load->model('tool/image');
        
        $category_id = $p['export_category_id'];
        $market_album = $this->model_catalog_vk_export->getMarketAlbum($category_id);
        $vk_market_category_id = $market_album['vk_market_category_id'];
        
        $category = $this->model_catalog_category->getCategoryDescriptions($category_id);
        
        $images_in = array($p['image']);
        
        // дополнительные фото
        if ($this->config->get('vk_export_market_photos_count') != 1) {
            $limit = $this->config->get('vk_export_market_photos_count');
            if ($limit == 'all') {
                $limit = 99;
            }
            $product_images = $this->model_catalog_product->getProductImages($product_id);
            $i = 1;
            foreach ($product_images as $img) {
                $i++;
                if ($i > $limit) break;
                $images_in[] = $img['image'];
            }
        }
        
        $images_out = array();
        $images_out_index = array();
        foreach ($images_in as $img) {
            if ($this->config->get('vk_export_image_mode') == 1) {
                $_img = DIR_IMAGE . $img;
            }
            else {
                // изменение размера фото
                $_img = $this->model_tool_image->resize($img, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                $_img = DIR_IMAGE . str_replace(HTTP_CATALOG, '', $_img);
                $_img = str_replace('/image/image/', '/image/', $_img);
            }
            if (!file_exists($_img)) {
                continue;
            }
            $images_out[] = $_img;
            $images_out_index[] = $img;
        }
        
        if (!$images_out) {
            $this->errorMessage = 'При обновлении товара <b>' . $p['name'] . '</b> произошла ошибка: Отсутствуют изображения товара';
            return false;
        }
        
        $p['category_id'] = $category_id;
        $vars = $this->generatePlaceholders($product_id, $p);
        
        // добавить переменную для вывода категории
        $vars['{category}'] = $category[$lang_id]['name'];
        $vars['{category_full}'] = $this->model_catalog_vk_export->getCategoryPath($category_id);
        
        // минимальный размер 400х400
        foreach ($images_out as $img_key => $img) {
            list($img_width, $img_height) = getimagesize($img);
            if ($img_width < 400 || $img_height < 400) {
                //unset($images_out[$img_key]);
                $_img = $this->model_tool_image->resize($images_out_index[$img_key], 400, 400);
                $_img = DIR_IMAGE . str_replace(HTTP_CATALOG, '', $_img);
                $_img = str_replace('/image/image/', '/image/', $_img);
                $images_out[$img_key] = $_img;
            }
        }
        if (!$images_out) {
            $this->errorMessage = 'При обновлении товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
            $this->errorMessage .= 'Отсутствуют фотографии товара минимально допустимого размера 400х400.';
            return false;
        }
        
        // загрузка фото
        try {
            $data = array(
                'group_id'   => $this->config->get('vk_export_group_id'),
                'main_photo' => 1,
            );
            $photoMarket = $vkApi->upload_photo_market($data, $images_out);
        }
        catch (vkApiException $e) {
            $this->errorMessage = 'При обновлении товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
            if ($comment = $e->getCodeComment()) {
                $this->errorMessage .= '<br><b>' . $comment . '</b>';
            }
            else {
                $this->errorMessage .= ' ' . $e;
            }
            return false;
        }
        
        // описание
        $message = $this->generateDescription($this->config->get('vk_export_market_product_desc_tpl'), $vars);
        
        $main_photo_id = array_shift($photoMarket);
        $main_photo_id = $main_photo_id->id;
        
        $photo_ids = array();
        foreach ($photoMarket as $pic) {
            $photo_ids[] = $pic->id;
        }
        $photo_ids = implode(',', $photo_ids);
        
        // конвертировать размеры
        $vk_export_mm_class_id = $this->config->get('vk_export_mm_class_id');
        if ($vk_export_mm_class_id && $p['length_class_id'] != $vk_export_mm_class_id) {
            $this->load->model('localisation/length_class');
            $product_length_class = $this->model_localisation_length_class->getLengthClass($p['length_class_id']);
            $mm_length_class = $this->model_localisation_length_class->getLengthClass($vk_export_mm_class_id);
            $p['width'] = $p['width'] / $product_length_class['value'] * $mm_length_class['value'];
            $p['height'] = $p['height'] / $product_length_class['value'] * $mm_length_class['value'];
            $p['length'] = $p['length'] / $product_length_class['value'] * $mm_length_class['value'];
        }
        
        // конвертировать размеры
        $vk_export_gramm_class_id = $this->config->get('vk_export_gramm_class_id');
        if ($vk_export_gramm_class_id && $p['weight_class_id'] != $vk_export_mm_class_id) {
            $this->load->model('localisation/weight_class');
            $product_weight_class = $this->model_localisation_weight_class->getWeightClass($p['weight_class_id']);
            $gramm_weight_class = $this->model_localisation_weight_class->getWeightClass($vk_export_mm_class_id);
            $p['weight'] = $p['weight'] / $product_weight_class['value'] * $gramm_weight_class['value'];
        }
        
        $name = mb_substr(htmlspecialchars_decode($vars['{name}']), 0, 98, 'UTF-8');
        $sku = '';
        if (isset($p['sku']) && $p['sku']) {
            $sku = mb_substr(htmlspecialchars_decode($p['sku']), 0, 49, 'UTF-8');
        }
        
        try {
            $vk_item_id = explode('_', $p['vk_photo_id']);
            $data = array(
                'item_id'       => $vk_item_id[1],
                'owner_id'      => '-' . $this->config->get('vk_export_group_id'),
                'name'          => $name,
                'description'   => $message,
                'category_id'   => $vk_market_category_id,
                'price'         => $vars['{price_float}'],
                'price_old_float' => $vars['{price_old_float}'],
                'special_price_float' => $vars['{special_price_float}'],
                'deleted'       => $deleted,
                'main_photo_id' => $main_photo_id,
                'photo_ids'     => $photo_ids,
                'url'           => $vars['{link}'],
                'dimension_width'  => $p['width'],
                'dimension_height' => $p['height'],
                'dimension_length' => $p['length'],
                'weight'           => $p['weight'],
                'sku'              => $sku,
            );
            
            $res = $vkApi->market_update_item($data);
            // капча
            // не отлажено!
            if (isset($res->error) && $res->error->error_code == 14 && !$this->cron) {
                $res->error->action_comment = 'Экспорт товара <b>' . $p['name'] . '</b>';
                $todo = array(
                    'action'      => 'addMarket', // !!!!!!!!!!! тестовое значение
                    'product_id'  => $product_id,
                    'owner_id'    => $this->config->get('vk_export_group_id'),
                    'category_id' => $category_id,
                );
                $this->catch_captcha($res, $todo);
                $this->errorMessage = 'При экспорте товара <b>' . $p['name'] . '</b> получена <b>капча</b>:<br>';
                $this->errorMessage .= '<img src="' . $res->error->captcha_img . '">';
                $this->errorMessage .= '</pre><input class="vk_captcha" type="text" name="captcha[' . $res->error->captcha_sid . ']" value="">';
                $this->captcha_show = true;
            }
            else {
                // сохранение экспорта товара в БД
                $this->model_catalog_vk_export->updateProductMarketExportDate($p['vk_photo_id']);
            }
        }
        catch (vkApiException $e) {
            $this->errorMessage = 'При обновлении товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
            if ($comment = $e->getCodeComment()) {
                $this->errorMessage .= '<br><b>' . $comment . '</b>';
            }
            else {
                $this->errorMessage .= ' ' . $e;
            }
            return false;
        }
        
        
        return true;
    }
    
    public function delete_single() {
        $this->load->language('extension/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        
        if (isset($this->request->get['photo_id']) 
            && isset($this->request->get['location']) 
            && isset($this->request->get['product_id']) 
            && $this->validateVkExport()
            ) {
                
            $product_id = $this->request->get['product_id'];
            $photo_id = $this->request->get['photo_id'];
            
            $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
            $p = $this->model_catalog_vk_export->getProduct($product_id);
            
            $vk_export_report = array();
            
            // удаление из альбомов
            if ($this->request->get['location'] == 'albums') {
                
                $photo = explode('_', $photo_id);
                $this->model_catalog_vk_export->deleteAlbumsExportOne($product_id, $photo_id);
                try {
                    $vkApi->delete_photo($photo[0], $photo[1]);
                    $vk_export_report['success'] = $this->language->get('text_albums_export_deleted');
                }
                catch (vkApiException $e) {
                    $error = 'При удалении из экспорта товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
                    if ($comment = $e->getCodeComment()) {
                        $error .= '<br><b>' . $comment . '</b>';
                    }
                    else {
                        $error .= ' ' . $e;
                    }
                    $error .= '<br>Товар удален из истории экспорта в альбомы, но мог остаться в альбомах ВК!';
                    $vk_export_report['warning'] = $error;
                }
            }
            // удаление со стены
            else if ($this->request->get['location'] == 'wall') {
    
                $photo = explode('_', $photo_id);
                try {
                    $vkApi->delete_wallpost($photo[0], $photo[1]);
                    $this->model_catalog_vk_export->deleteWallExportOne($product_id, $photo_id);
                    $vk_export_report['success'] = $this->language->get('text_wall_export_deleted');
                }
                catch (vkApiException $e) {
                    $error = 'При удалении товара со стены <b>' . $p['name'] . '</b> произошла ошибка:<br>';
                    if ($comment = $e->getCodeComment()) {
                        $error .= '<br><b>' . $comment . '</b>';
                    }
                    else {
                        $error .= ' ' . $e;
                    }
                    $vk_export_report['warning'] = $error;
                }
            }
            // удалить из товаров
            else if ($this->request->get['location'] == 'market') {
                
                $photo = explode('_', $photo_id);
                try {
                    $vkApi->delete_market($photo[0], $photo[1]);
                    $this->model_catalog_vk_export->deleteMarketExportOne($product_id, $photo_id);
                    $vk_export_report['success'] = $this->language->get('text_market_export_deleted');
                }
                catch (vkApiException $e) {
                    $error = 'При удалении из экспорта товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
                    if ($comment = $e->getCodeComment()) {
                        $error .= '<br><b>' . $comment . '</b>';
                    }
                    else {
                        $error .= ' ' . $e;
                    }
                    $vk_export_report['warning'] = $error;
                }
            }
                        
            if ($vk_export_report) {
                $this->cache->set('vk_export_report', $vk_export_report);
            }
            
            $url = '';
            
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }
        
            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . $this->request->get['filter_model'];
            }
            
            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }
            
            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }    
        
            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }
        
            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }
        
            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }
            
            if (isset($this->request->get['filter_export_albums'])) {
                $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
            }
            
            if (isset($this->request->get['filter_export_wall'])) {
                $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
            }
                    
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
      }
    
    public function delete() {
        $this->load->language('extension/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        
        if (isset($this->request->post['selected']) && $this->validateVkExport()) {
            session_write_close(); 
            
            $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
            $success = 0;
            $total_pics_deleted = 0;
            $total = count($this->request->post['selected']);
            $report = array();
            
            foreach ($this->request->post['selected'] as $product_id) {
                $p = $this->model_catalog_vk_export->getProduct($product_id);
                $export = $this->model_catalog_vk_export->getAlbumsExport($product_id);
                $error = false;
                foreach ($export as $photo) {
                    $error = false;
                    $pic = explode('_', $photo['vk_photo_id']);
                    try {
                        $vkApi->delete_photo($pic[0], $pic[1]);
                        $total_pics_deleted++;
                    }
                    catch (vkApiException $e) {
                        $error = 'При удалении из экспорта товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
                        if ($comment = $e->getCodeComment()) {
                            $error .= '<br><b>' . $comment . '</b>';
                        }
                        else {
                            $error .= ' ' . $e;
                        }
                        $error .= '<br>Товар удален из истории экспорта в альбомы, но мог остаться в альбомах ВК!';
                        $report[] = $error;
                    }
                }
                $this->model_catalog_vk_export->deleteAlbumsExport($product_id);
                if (!$error) {
                    $success++;
                }
              }
              
            $msg = '';
            $vk_export_report = array();
            if ($success) {
                $msg = $this->language->get('text_vk_delete_success');
                $msg = sprintf($msg, $success, $total, $total_pics_deleted);
                $vk_export_report['success'] = $msg;
            }
            if ($report) {
                $vk_export_report['warning'] = implode('<br>', $report);
            }
            
            if ($vk_export_report) {
                $this->cache->set('vk_export_report', $vk_export_report);
            }
            
            $url = '';
            
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }
        
            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . $this->request->get['filter_model'];
            }
            
            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }
            
            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }    
        
            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }
        
            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }
        
            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }
            
            if (isset($this->request->get['filter_export_albums'])) {
                $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
            }
            
            if (isset($this->request->get['filter_export_wall'])) {
                $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
            }
                    
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }
    
    public function delete_market() {
        $this->load->language('extension/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        
        if (isset($this->request->post['selected']) && $this->validateVkExport()) {
            session_write_close(); 
            
            $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
            $success = 0;
            $total_pics_deleted = 0;
            $total = count($this->request->post['selected']);
            $report = array();
            
            foreach ($this->request->post['selected'] as $product_id) {
                $p = $this->model_catalog_vk_export->getProduct($product_id);
                $export = $this->model_catalog_vk_export->getMarketExport($product_id);
                $error = false;
                foreach ($export as $photo) {
                    $error = false;
                    $pic = explode('_', $photo['vk_photo_id']);
                    try {
                        $vkApi->delete_market($pic[0], $pic[1]);
                        $total_pics_deleted++;
                    }
                    catch (vkApiException $e) {
                        $error = 'При удалении из экспорта товара <b>' . $p['name'] . '</b> произошла ошибка:<br>';
                        if ($comment = $e->getCodeComment()) {
                            $error .= '<br><b>' . $comment . '</b>';
                        }
                        else {
                            $error .= ' ' . $e;
                        }
                        $report[] = $error;
                    }
                }
                if (!$error) {
                    $this->model_catalog_vk_export->deleteMarketExport($product_id);
                    $success++;
                }
              }
              
            $msg = '';
            $vk_export_report = array();
            if ($success) {
                $msg = $this->language->get('text_vk_market_delete_success');
                $msg = sprintf($msg, $success, $total);
                $vk_export_report['success'] = $msg;
            }
            if ($report) {
                $vk_export_report['warning'] = implode('<br>', $report);
            }
            
            if ($vk_export_report) {
                $this->cache->set('vk_export_report', $vk_export_report);
            }
            
            $url = '';
            
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }
        
            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . $this->request->get['filter_model'];
            }
            
            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }
            
            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }    
        
            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }
        
            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }
        
            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }
            
            if (isset($this->request->get['filter_export_albums'])) {
                $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
            }
            
            if (isset($this->request->get['filter_export_wall'])) {
                $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
            }
                    
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
      }
    
    public function delete_wall() {
        $this->load->language('extension/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        
        if (isset($this->request->post['selected']) && $this->validateVkExport()) {
            
            $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
            
            $success = 0;
            $total = count($this->request->post['selected']);
            foreach ($this->request->post['selected'] as $product_id) {
                $p = $this->model_catalog_vk_export->getProduct($product_id);
                $export = $this->model_catalog_vk_export->getWallExport($product_id);
                foreach ($export as $photo) {
                    $vp = explode('_', $photo['vk_photo_id']);
                    try {
                        $vkApi->delete_wallpost($vp[0], $vp[1]);
                    }
                    catch (vkApiException $e) {
                        $error = 'При удалении товара со стены <b>' . $p['name'] . '</b> произошла ошибка:<br>';
                        if ($comment = $e->getCodeComment()) {
                            $error .= '<br><b>' . $comment . '</b>';
                        }
                        else {
                            $error .= ' ' . $e;
                        }
                        $vk_export_report['warning'] = $error;
                    }
                }
                $this->model_catalog_vk_export->deleteWallExport($product_id);
                $success++;
              }
            $msg = $this->language->get('text_wall_delete_success');
            $msg = sprintf($msg, $success, $total);
            $this->session->data['success'] = $msg;
            
            $url = '';
            
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }
        
            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . $this->request->get['filter_model'];
            }
            
            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }
            
            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }    
        
            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }
        
            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }
        
            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }
            
            if (isset($this->request->get['filter_export_albums'])) {
                $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
            }
            
            if (isset($this->request->get['filter_export_wall'])) {
                $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
            }
                    
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
      }
    
    public function export_history() {
        $this->load->language('extension/vk_export');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/vk_export');
        
        $url = '';
            
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
    
        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . $this->request->get['filter_model'];
        }
        
        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }
        
        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }    
    
        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }
    
        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }
    
        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }
        
        if (isset($this->request->get['filter_export_albums'])) {
            $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
        }
        
        if (isset($this->request->get['filter_export_wall'])) {
            $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
        }
                
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        if ($this->request->get['type'] == 'albums') {
            $albums_export = array();
            $export = $this->model_catalog_vk_export->getAlbumsExport($this->request->get['product_id']);
            foreach ($export as $case) {
                $albums_link = explode('_', $case['vk_photo_id']);
                $albums_link = 'https://vk.com/albums' . $albums_link[0] . '?z=photo' . $case['vk_photo_id'];
                $albums_export_date = date('d.m.y H:i', strtotime($case['date']));
                $albums_category_id = $case['category_id'];
                $category = $this->model_catalog_category->getCategory($albums_category_id);
                $albums_export[] = array(
                    'link' => $albums_link,
                    'date' => $albums_export_date,
                    'category_id' => $albums_category_id,
                    'category' => $category['path'] . ' &gt; ' . $category['name'],
                    'delete_link' => $this->url->link('extension/vk_export/delete_single', 'token=' . $this->session->data['token'] . '&location=albums&product_id=' . $this->request->get['product_id'] . '&photo_id=' . $case['vk_photo_id'] . $url, 'SSL')
                );
            }
            $data['export'] = $albums_export;
        }
        else if ($this->request->get['type'] == 'wall') {
            $wall_export = array();
            $export = $this->model_catalog_vk_export->getWallExport($this->request->get['product_id']);
            foreach ($export as $case) {
                $wall_link = 'https://vk.com/wall' . $case['vk_photo_id'];
                $wall_export_date = date('d.m.y H:i', strtotime($case['date']));
                $wall_category_id = $case['category_id'];
                $category = $this->model_catalog_category->getCategory($wall_category_id);
                $wall_export[] = array(
                    'link' => $wall_link,
                    'date' => $wall_export_date,
                    'category_id' => $wall_category_id,
                    'category' => $category['path'] . ' &gt; ' . $category['name'],
                    'delete_link' => $this->url->link('extension/vk_export/delete_single', 'token=' . $this->session->data['token'] . '&location=wall&product_id=' . $this->request->get['product_id'] . '&photo_id=' . $case['vk_photo_id'] . $url, 'SSL')
                );
            }
            $data['export'] = $wall_export;
        }
        else if ($this->request->get['type'] == 'market') {
            $market_export = array();
            $export = $this->model_catalog_vk_export->getMarketExport($this->request->get['product_id']);
            foreach ($export as $case) {
                $market_link = 'https://vk.com/market-' . $this->config->get('vk_export_group_id') . '?w=product' . $case['vk_photo_id'];
                $market_export_date = date('d.m.y H:i', strtotime($case['date']));
                $market_category_id = $case['category_id'];
                $category = $this->model_catalog_category->getCategory($market_category_id);
                $market_export[] = array(
                    'link' => $market_link,
                    'date' => $market_export_date,
                    'category_id' => $market_category_id,
                    'category' => $category['path'] . ' &gt; ' . $category['name'],
                    'delete_link' => $this->url->link('extension/vk_export/delete_single', 'token=' . $this->session->data['token'] . '&location=market&product_id=' . $this->request->get['product_id'] . '&photo_id=' . $case['vk_photo_id'] . $url, 'SSL')
                );
            }
            $data['export'] = $market_export;
        }
        
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/vk_export_history.tpl', $data));
    }
      
      // отправка каптчи
    public function send_captcha() {
        $cache = $this->cache->get('vk_export_captcha_request');
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
        if ($this->config->get('vk_export_debug_mode')) {
            $log_file = DIR_LOGS . '.vkExportLog_send_captcha_' . date('d.m.y_H.i.s') . '_' . substr(md5(microtime()), 0, 7);
            $vkApi->set_log_on(true);
            $vkApi->set_log_file($log_file);
        }
        $report = array('success' => '', 'warning' => '');
        $captcha_show = false;
        foreach ($this->request->post['captcha'] as $id => $value) {
            if (array_key_exists($id, $cache)) {
                $captcha = $cache[$id]['error'];
                $todo = $cache[$id]['todo'];
                $res = $vkApi->send_captcha($value, $captcha);
                if (isset($res->error) && $res->error->error_code == 14) {
                    $res->error->action_comment = $captcha->action_comment;
                    $this->catch_captcha($res);
                    $report['warning'] .= '<b>Ошибка!</b> ' . $captcha->action_comment . ' Введите качпу:<br>';
                    $report['warning'] .= '<img src="' . $res->error->captcha_img . '">';
                    $report['warning'] .= '<input class="vk_captcha" type="text" name="captcha[' . $res->error->captcha_sid . ']" value=""><br>';
                    $captcha_show = true;
                }
                else if (isset($res->error)) {
                    $report['warning'] .= '<b>Неизвестная ошибка капчи!</b> ' . $captcha->action_comment . '<br>';
                    $report['warning'] .= $res->error->error_msg . ' [' . $res->error->error_code . ']';
                }
                else {
                    $report['success'] .= $captcha->action_comment . ' Выполено успешно!<br>';
                    if ($todo) {
                        // сохранить запись 
                        if ($todo['action'] == 'addWallPost') {
                            $this->load->model('catalog/vk_export');
                            $this->model_catalog_vk_export->addWallPost($todo['product_id'], $todo['owner_id'] . '_' . $res->response->post_id, $todo['category_id']);
                        }
                    }
                }
            }
        }
        if ($captcha_show) {
            $report['warning'] .= '<br><a id="send_captcha" class="button">Отправить</a>';
        }
        $this->response->setOutput(json_encode($report));
      }
    
      private function getList() {                
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
        }
        
        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = null;
        }

        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        } else {
            $filter_quantity = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_category'])) {
            $filter_category = $this->request->get['filter_category'];
        } else {
            $filter_category = null;
        }
        
        if (isset($this->request->get['filter_export_albums'])) {
            $filter_export_albums = $this->request->get['filter_export_albums'];
        } else {
            $filter_export_albums = null;
        }
        
        if (isset($this->request->get['filter_export_wall'])) {
            $filter_export_wall = $this->request->get['filter_export_wall'];
        } else {
            $filter_export_wall = null;
        }
        
        if (isset($this->request->get['filter_export_market'])) {
            $filter_export_market = $this->request->get['filter_export_market'];
        } else {
            $filter_export_market = null;
        }
        
        if (isset($this->request->get['filter_manufacturer_name']) && $this->request->get['filter_manufacturer_name']) {
			$filter_manufacturer_name = $this->request->get['filter_manufacturer_name'];
		} else {
			$filter_manufacturer_name = null;
		}

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.name';
        }
        
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';
                        
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
        
        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . $this->request->get['filter_model'];
        }
        
        if (isset($this->request->get['filter_manufacturer_name'])) {
            $url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
        }
        
        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }
        
        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }        

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }
        
        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }
        
        if (isset($this->request->get['filter_export_albums'])) {
            $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
        }
        
        if (isset($this->request->get['filter_export_wall'])) {
            $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
        }
        
        if (isset($this->request->get['filter_export_market'])) {
            $url .= '&filter_export_market=' . $this->request->get['filter_export_market'];
        }
                        
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        );
       
        $data['vk_export'] = $this->url->link('extension/vk_export/export', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['vk_wallpost'] = $this->url->link('extension/vk_export/wallpost', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['vk_market'] = $this->url->link('extension/vk_export/market_export', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['vk_export_progress'] = $this->url->link('extension/vk_export/progress', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['vk_delete'] = $this->url->link('extension/vk_export/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['vk_market_delete'] = $this->url->link('extension/vk_export/delete_market', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['vk_delete_wall'] = $this->url->link('extension/vk_export/delete_wall', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['get_log'] = $this->url->link('extension/vk_export/get_log', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['export_history'] = $this->url->link('extension/vk_export/export_history', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['send_captcha'] = $this->url->link('extension/vk_export/send_captcha', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['send_security'] = $this->url->link('extension/vk_export/send_security', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['products'] = array();

        $params = array(
            'filter_name'      => $filter_name, 
            'filter_model'      => $filter_model,
            'filter_price'      => $filter_price,
            'filter_quantity' => $filter_quantity,
            'filter_status'   => $filter_status,
            'filter_date_added' => $filter_date_added,
            'filter_category' => $filter_category,
            'filter_export_albums'=> $filter_export_albums,
            'filter_export_wall'=> $filter_export_wall,
            'filter_export_market'=> $filter_export_market,
            'filter_manufacturer_name'=> $filter_manufacturer_name,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('vk_export_products_per_page'),
            'limit'           => $this->config->get('vk_export_products_per_page')
        );
        
        $this->load->model('tool/image');
        $this->load->model('catalog/vk_export');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        
        // категории товаров ВК
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
        $response = $vkApi->api('market.getCategories', ['count' => 100]);
        
        $_vk_market_cats = array();
        $vk_market_cats = array();
        foreach ($response->response->items as $c) {
            if (!isset($_vk_market_cats[$c->section->id])){
                $_vk_market_cats[$c->section->id] = array('name' => $c->section->name, 'childs' => array($c));
            }
            else {
                $_vk_market_cats[$c->section->id]['childs'][] = $c;
            }
            $vk_market_cats[$c->id] = $c->name;
        }
        $data['vk_market_cats'] = $_vk_market_cats;
        
        // сохраненные категории
        $_market_cats = $this->model_catalog_vk_export->getMarketAlbums();
        $market_cats = array();
        foreach ($_market_cats->rows as $m) {
            $market_cats[$m['category_id']] = $m['vk_market_category_id'];
        }
        
        $query = $this->db->query('SHOW TABLES LIKE \'category_path\'');
        $category_path_table = count($query->rows);
        
        if ($category_path_table) {
            $categories = $this->model_catalog_vk_export->getCategories(0);
        }
        else {
            $categories = $this->model_catalog_vk_export->getCategories_old(0);
        }
        $categories_tmp = array();
        $categories_all = array();
        foreach ($categories as $category) {
            $categories_tmp[$category['category_id']] = $category['name'];
            $categories_all[$category['category_id']] = $category;
            $categories_all[$category['category_id']]['vk_market_category_id'] = '';
            // категория товаров ВК
            if (array_key_exists($category['category_id'], $market_cats)) {
                $categories_all[$category['category_id']]['vk_market_category_id'] = $market_cats[$category['category_id']];
            }
        }
        $data['categories'] = $categories_all;
        $categories = $categories_tmp;
        unset($categories_tmp);
        $data['category_select'] = $this->createSelect($_GET, 'filter_category', array('*' => '') + $categories, false, array('attributes' => 'class="form-control"'));
        $categories = array('Не выбран') + $categories;
        $results = $this->model_catalog_vk_export->getProducts($params);
        $product_total = $this->model_catalog_vk_export->getProductsTotal($params);
        
        foreach ($results as $result) {
            
            if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
            }
    
            $special = false;
            
            $product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);
            
            foreach ($product_specials  as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d'))
                    && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
                    $special = $product_special['price'];
            
                    break;
                }                    
            }
            
            //// экспорт
            $albums_export = array();
            $wall_export = array();
            $market_export = array();
            $export = $this->model_catalog_vk_export->getExport($result['product_id']);
            $albums_category_id = false;
            foreach ($export as $case) {
                if ($case['location'] == 'albums') {
                    $albums_link = explode('_', $case['vk_photo_id']);
                    $albums_link = 'https://vk.com/albums' . $albums_link[0] . '?z=photo' . $case['vk_photo_id'];
                    $albums_export_date = date('d.m.y H:i', strtotime($case['date']));
                    if (!$albums_category_id) {
                        $albums_category_id = $case['category_id'];
                    }
                    $albums_export[] = array(
                        'link' => $albums_link,
                        'date' => $albums_export_date,
                        'category_id' => $albums_category_id,
                        'delete_link' => $this->url->link('extension/vk_export/delete_single', 'token=' . $this->session->data['token'] . '&location=albums&product_id=' . $result['product_id'] . '&photo_id=' . $case['vk_photo_id'] . $url, 'SSL')
                    );
                }
                else if ($case['location'] == 'wall') {
                    $wall_link = 'https://vk.com/wall' . $case['vk_photo_id'];
                    $wall_export_date = date('d.m.y H:i', strtotime($case['date']));
                    $wall_category_id = $case['category_id'];
                    $wall_export[] = array(
                        'link' => $wall_link,
                        'date' => $wall_export_date,
                        'category_id' => $wall_category_id,
                        'delete_link' => $this->url->link('extension/vk_export/delete_single', 'token=' . $this->session->data['token'] . '&location=wall&product_id=' . $result['product_id'] . '&photo_id=' . $case['vk_photo_id'] . $url, 'SSL')
                    );
                }
                else if ($case['location'] == 'market') {
                    $market_link = 'https://vk.com/market-' . $this->config->get('vk_export_group_id') . '?w=product' . $case['vk_photo_id'];
                    $market_export_date = date('d.m.y H:i', strtotime($case['date']));
                    $market_category_id = $case['category_id'];
                    $market_export[] = array(
                        'link' => $market_link,
                        'date' => $market_export_date,
                        'category_id' => $market_category_id,
                        'delete_link' => $this->url->link('extension/vk_export/delete_single', 'token=' . $this->session->data['token'] . '&location=market&product_id=' . $result['product_id'] . '&photo_id=' . $case['vk_photo_id'] . $url, 'SSL')
                    );
                }
            }
            //////
            
            
            $selected_album = 0;
            if ($filter_category) {
                $selected_album = $filter_category;
            }
            else if ($albums_category_id) {
                $selected_album = $albums_category_id;
            }
            else if (method_exists($this->model_catalog_product, 'getProductMainCategoryId')) {
                $selected_album = $this->model_catalog_product->getProductMainCategoryId($result['product_id']);
            }
            if (!$selected_album) {
                $product_categories = $this->model_catalog_product->getProductCategories($result['product_id']);
                $selected_album = array_pop($product_categories);
            }
            
            if ($this->config->get('vk_export_root_cat')) {
                $parent_id = $categories_all[$selected_album]['parent_id'];
                while ($parent_id) {
                    $selected_album = $parent_id;   
                    $parent_id = $categories_all[$selected_album]['parent_id'];
                }
            }
            
              $data['products'][] = array(
                'product_id' => $result['product_id'],
                'name'       => $result['name'],
                'manufacturer_name' => $result['manufacturer_name'],
                'model'      => $result['model'],
                'price'      => $result['price'],
                'date_added' => date('d.m.y', strtotime($result['date_added'])),
                'href'       => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL'),
                'special'    => $special,
                'image'      => $image,
                'quantity'   => $result['quantity'],
                'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'albums_export' => $albums_export,
                'wall_export' => $wall_export,
                'market_export' => $market_export,
                'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
                'selected_album' => $selected_album
            );
        }
        
        $data['heading_title'] = $this->language->get('heading_title');        
                
        $data['text_enabled'] = $this->language->get('text_enabled');        
        $data['text_disabled'] = $this->language->get('text_disabled');        
        $data['text_no_results'] = $this->language->get('text_no_results');        
        $data['text_image_manager'] = $this->language->get('text_image_manager');        
        $data['text_vk_export_off'] = $this->language->get('text_vk_export_off');        
        $data['text_vk_export_on'] = $this->language->get('text_vk_export_on'); 
        $data['text_list'] = $this->language->get('text_list');
            
        $data['column_image'] = $this->language->get('column_image');        
        $data['column_name'] = $this->language->get('column_name');        
        $data['column_model'] = $this->language->get('column_model');        
        $data['column_price'] = $this->language->get('column_price');        
        $data['column_quantity'] = $this->language->get('column_quantity');        
        $data['column_status'] = $this->language->get('column_status');        
        $data['column_date_added'] = $this->language->get('column_date_added');        
        $data['column_vk_export'] = $this->language->get('column_vk_export');        
        $data['column_action'] = $this->language->get('column_action');        
        $data['column_album'] = $this->language->get('column_album');        
        $data['column_albums'] = $this->language->get('column_albums');        
        $data['column_wall'] = $this->language->get('column_wall');        
        $data['column_market'] = $this->language->get('column_market');        
        $data['column_producer'] = $this->language->get('column_producer');        
                
        $data['button_delete'] = $this->language->get('button_delete');        
        $data['button_filter'] = $this->language->get('button_filter');
         
        $data['token'] = $this->session->data['token'];
        
        $data['success'] = '';
        $data['warning'] = '';
        if ($report = $this->cache->get('vk_export_report')) {
            if (isset($report['success'])) {
                $data['success'] = $report['success'];
            }
            if (isset($report['warning'])) {
                $data['warning'] = $report['warning'];
            }
            $this->cache->delete('vk_export_report');
        } 
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } 
        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } 

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
        
        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . $this->request->get['filter_model'];
        }
        
        if (isset($this->request->get['filter_manufacturer_name'])) {
            $url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
        }
        
        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }
        
        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }
        
        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }
        
        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }
        
        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }
        
        if (isset($this->request->get['filter_export_albums'])) {
            $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
        }
        
        if (isset($this->request->get['filter_export_wall'])) {
            $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
        }
        
        if (isset($this->request->get['filter_export_market'])) {
            $url .= '&filter_export_market=' . $this->request->get['filter_export_market'];
        }
                                
        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
                    
        $data['sort_id'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=p.product_id' . $url, 'SSL');
        $data['sort_producer'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=manufacturer_name' . $url, 'SSL');
        $data['sort_name'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
        $data['sort_model'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
        $data['sort_price'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
        $data['sort_quantity'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');
        $data['sort_export_albums'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=export_albums' . $url, 'SSL');
        $data['sort_export_wall'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=export_wall' . $url, 'SSL');
        $data['sort_export_market'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=export_market' . $url, 'SSL');
        $data['sort_order'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
        
        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
        
        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . $this->request->get['filter_model'];
        }
        
        if (isset($this->request->get['filter_manufacturer_name'])) {
            $url .= '&filter_manufacturer_name=' . $this->request->get['filter_manufacturer_name'];
        }
        
        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }
        
        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }
        
        if (isset($this->request->get['filter_export_albums'])) {
            $url .= '&filter_export_albums=' . $this->request->get['filter_export_albums'];
        }
        
        if (isset($this->request->get['filter_export_wall'])) {
            $url .= '&filter_export_wall=' . $this->request->get['filter_export_wall'];
        }
        
        if (isset($this->request->get['filter_export_market'])) {
            $url .= '&filter_export_market=' . $this->request->get['filter_export_market'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
                                                
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        
        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('vk_export_products_per_page');
        $pagination->url = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
            
        $data['pagination'] = $pagination->render();
        
        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('vk_export_products_per_page')) + 1 : 0, ((($page - 1) * $this->config->get('vk_export_products_per_page')) > ($product_total - $this->config->get('vk_export_products_per_page'))) ? $product_total : ((($page - 1) * $this->config->get('vk_export_products_per_page')) + $this->config->get('vk_export_products_per_page')), $product_total, ceil($product_total / $this->config->get('vk_export_products_per_page')));
    
        $data['filter_name'] = $filter_name;
        $data['filter_model'] = $filter_model;
        $data['filter_price'] = $filter_price;
        $data['filter_quantity'] = $filter_quantity;
        $data['filter_status'] = $filter_status;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_category'] = $filter_category;
        $data['filter_export_albums'] = $filter_export_albums;
        $data['filter_export_wall'] = $filter_export_wall;
        $data['filter_export_market'] = $filter_export_market;
        $data['filter_manufacturer_name'] = $filter_manufacturer_name;
        $data['sort'] = $sort;
        $data['order'] = $order;
        
        
        $data['clear_extra'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url . '&clear_extra');
        $data['hide_clear_extra'] = '';
        $data['extra_album_error'] = '';
        
        if (isset($this->error['extra_album_error'])) {
            $data['extra_album_error'] = $this->error['extra_album_error'];
        }
        
        
        if (isset($this->request->get['clear_extra'])) {
            unset($this->session->data['vk_export_extra_settings']);
            unset($this->session->data['vk_export_extra_album']);
            $this->response->redirect($this->url->link('extension/vk_export', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        
        if (isset($this->session->data['vk_export_extra_settings'])) {
            $data['extra_settings'] = '';
            $data['extra_album'] = htmlentities($this->session->data['vk_export_extra_album']);
        }
        else {
            $data['extra_album'] = '';
            $data['extra_settings'] = 'display:none;';
            $data['hide_clear_extra'] = ' style="display:none;"';
        }
        
        $data['version'] = $this->version;
        $data['show_column_model'] = $this->config->get('vk_export_column_model');
        $data['show_column_price'] = $this->config->get('vk_export_column_price');
        $data['show_column_quantity'] = $this->config->get('vk_export_column_quantity');
        $data['show_column_status'] = $this->config->get('vk_export_column_status');
        $data['show_column_date_added'] = $this->config->get('vk_export_column_date_added');
        $data['show_column_producer'] = $this->config->get('vk_export_column_producer');
        $data['show_column_albums'] = $this->config->get('vk_export_column_albums');
        $data['show_column_wall'] = $this->config->get('vk_export_column_wall');
        $data['show_column_market'] = $this->config->get('vk_export_column_market');
        $data['show_column_id'] = $this->config->get('vk_export_column_id');
        $data['vk_export_debug_mode'] = $this->config->get('vk_export_debug_mode');
                
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/vk_export.tpl', $data));
      }
    
    private function validateVkExport() {
        if (!$this->user->hasPermission('modify', 'extension/vk_export')) {
            $this->error['warning'] = $this->language->get('error_permission');  
        }
        
        if (!$this->config->get('vk_export_license_status')) {
            $this->error['warning'] = true;  
            $this->session->data['warning'] = $this->language->get('text_license_not_registred');  
        }
        
        if (isset($this->request->post['extra_album']) && $this->request->post['extra_album']) {
            if (!isset($this->session->data['vk_export_extra_album']) || $this->session->data['vk_export_extra_album'] != $this->request->post['extra_album']) {
                $this->session->data['vk_export_extra_album'] = $this->request->post['extra_album'];
                $this->session->data['vk_export_extra_settings'] = true;
                if (!preg_match('/album(\-?\d+_\d+)$/', $this->request->post['extra_album'])) {
                    $this->error['extra_album_error'] = 'Неверно указана ссылка на альбом';
                }
            }
        }
        else if (isset($this->request->post['extra_album']) && !$this->request->post['extra_album']) {
            unset($this->session->data['vk_export_extra_settings']);
            unset($this->session->data['vk_export_extra_album']);
        }
        
        if (!$this->error) {
              return true;
        } else {
              return false;
        }
      }
    
    /**
     * generate select box
     * 
     * @param   array   source array containing selected value
     * @param   string  name of form variable 
     * @param   array   select options
     * @param   string  key of value in source array
     * @param   array   extra data to set
     * @return  string
     */
    private function createSelect($source, $name, $options, $key = false, $extra = false, $default = false) {
        if (!$key) {
            $key = $name;
        }
        $output = "<select name=\"$name\" " . ((isset($extra['attributes'])) ? $extra['attributes'] : '') . ">\n";
        $current_val = (isset($source[$key])) ? $source[$key] : ($default ? $default : false);
        foreach ($options as $opt_val => $opt_name) {
            $output .= "<option value=\"$opt_val\"";
            if ($current_val == $opt_val) {
                $output .= ' selected';
            }
            $output .= ">$opt_name</option>\n";
        }
        $output .= "</select>\n";
        return $output;
    }
        
    public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
    
    public function albums() {
        $this->load->language('extension/vk_export_albums');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/vk_export');
        $this->load->model('catalog/category');
        
        // удаление всех
        if (isset($this->request->get['delete_all']) && $this->validateVkExport()) {
            $this->model_catalog_vk_export->deleteAllAlbums();
            $this->session->data['success'] = 'Все альбомы удалены';
            $this->response->redirect($this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL'));
        }
        // выборочное удаление
        if (isset($this->request->post['delete_albums']) && $this->validateVkExport()) {
            foreach ($this->request->post['delete_albums'] as $key => $category_id) {
                $this->model_catalog_vk_export->deleteAlbumByKey($category_id, $this->request->post['vk_album_id'][$key], $this->request->post['mode'][$key]);
            }
            $this->session->data['success'] = 'Отмеченные альбомы удалены';
            $this->response->redirect($this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        $data = array(
            'start'           => ($page - 1) * $this->config->get('vk_export_products_per_page'),
            'limit'           => $this->config->get('vk_export_products_per_page')
        );
        
        $data['breadcrumbs'] = array();

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => false
           );

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title_module'),
            'href'      => $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
        
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_module'] = $this->language->get('heading_title_module');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_export_to_album'] = $this->language->get('entry_export_to_album');
        $data['entry_owner_type'] = $this->language->get('entry_owner_type');
        $data['button_add_album'] = $this->language->get('button_add_album');
        $data['button_remove'] = $this->language->get('button_remove');
        
        $data['delete_all'] = $this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'] . '&delete_all', 'SSL');
        $data['add'] = $this->url->link('extension/vk_export/album_add', 'token=' . $this->session->data['token'], 'SSL');
        
        // категории
        $categories = $this->model_catalog_category->getCategories(0);
        if (!$categories) {
            $categories = array();
        }
        $categories_tmp = array();
        foreach ($categories as $category) {
            $categories_tmp[$category['category_id']] = $category['name'];
        }
        $categories = $categories_tmp;
        unset($categories_tmp);
        
        // тип
        $mode = array('user' => 'Пользователь', 'group' => 'Группа');
        
        // альбомы
        $data['albums'] = array();
        
        if (isset($this->request->post['featured_module'])) {
            $data['modules'] = $this->request->post['featured_module'];
        }
        $albums = $this->model_catalog_vk_export->getAlbums($data);
        $total_albums = $this->model_catalog_vk_export->getTotalAlbums();
        foreach ($albums->rows as $key => $album) {
            $albums->rows[$key]['category'] = $categories[$album['category_id']];
            $albums->rows[$key]['mode_name'] = $mode[$album['mode']];
            $albums->rows[$key]['edit'] = $this->url->link('extension/vk_export/album_edit', 'category_id=' . $album['category_id'] . '&mode=' . $album['mode'] . '&token=' . $this->session->data['token'], 'SSL');;
        }
        $data['albums'] = $albums->rows;
        
        $pagination = new Pagination();
        $pagination->total = $total_albums;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('vk_export_products_per_page');
        $pagination->url = $this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');
            
        $data['pagination'] = $pagination->render();
        
        $data['results'] = sprintf($this->language->get('text_pagination'), ($total_albums) ? (($page - 1) * $this->config->get('vk_export_products_per_page')) + 1 : 0, ((($page - 1) * $this->config->get('vk_export_products_per_page')) > ($total_albums - $this->config->get('vk_export_products_per_page'))) ? $total_albums : ((($page - 1) * $this->config->get('vk_export_products_per_page')) + $this->config->get('vk_export_products_per_page')), $total_albums, ceil($total_albums / $this->config->get('vk_export_products_per_page')));
        
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/vk_export_albums.tpl', $data));
    }
    
    public function album_edit() {
        $this->load->language('extension/vk_export_albums');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/vk_export');
        $this->load->model('catalog/category');
        
        // сохранение
        if (isset($this->request->post['vk_album_id']) && $this->validateVkExport()) {
            $vk_album_id = explode('album', $this->request->post['vk_album_id']);
            $new_vk_album_id = explode('album', $this->request->post['vk_album_id']);
            $this->model_catalog_vk_export->editVkAlbumId($new_vk_album_id[1], $this->request->get['category_id'], $this->request->get['mode']);
            $this->session->data['success'] = 'Изменения сохранены';
            $this->response->redirect($this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        $data['breadcrumbs'] = array();

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
                'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
           );

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title_module'),
                'href'      => $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'], 'SSL'),               
           );
        
           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
                'href'      => $this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL'),
           );
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
        
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_module'] = $this->language->get('heading_title_module');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_export_to_album'] = $this->language->get('entry_export_to_album');
        $data['entry_owner_type'] = $this->language->get('entry_owner_type');
        
        // тип
        $mode = array('user' => 'Пользователь', 'group' => 'Группа');
        
        $album = $this->model_catalog_vk_export->getAlbum($this->request->get['category_id'], $this->request->get['mode']);
        $album = $album->row;
        $album['mode_name'] = $mode[$album['mode']];
        
        $data['album'] = $album;
        $data['cancel'] = $this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        
        $data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_create_album'] = $this->language->get('text_create_album');

		$this->response->setOutput($this->load->view('extension/vk_export_album_edit.tpl', $data));
    }
    
    public function album_add() {
        $this->load->language('extension/vk_export_albums');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/vk_export');
        $this->load->model('catalog/category');
        
        // сохранение
        if (isset($this->request->post['vk_album_id']) && $this->validateVkExport()) {
            
            if (!strpos($this->request->post['vk_album_id'], 'album')) {
                $this->session->data['warning'] = 'Неверно введён альбом. Укажите ссылку на альбом ВКонтакте.';
            }
            else {
                $vk_album_id = explode('album', $this->request->post['vk_album_id']);
                $new_vk_album_id = explode('album', $this->request->post['vk_album_id']);
                if (!isset($new_vk_album_id[1])) {
                    $new_vk_album_id[1] = $this->request->post['vk_album_id'];
                }
                $res = $this->model_catalog_vk_export->insertAlbum($this->request->post['category_id'], $new_vk_album_id[1], $this->request->post['mode']);
                $this->session->data['success'] = 'Альбом добавлен';
                $this->response->redirect($this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }
        
        $data['breadcrumbs'] = array();

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => false
           );

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title_module'),
            'href'      => $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
        
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_module'] = $this->language->get('heading_title_module');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_export_to_album'] = $this->language->get('entry_export_to_album');
        $data['entry_owner_type'] = $this->language->get('entry_owner_type');
        
        // категории
        $categories = $this->model_catalog_category->getCategories(0);
        $categories_tmp = array('' => '');
        foreach ($categories as $category) {
            $categories_tmp[$category['category_id']] = $category['name'];
        }
        $categories = $categories_tmp;
        unset($categories_tmp);
        $data['category_select'] = $this->createSelect(array(), 'category_id', $categories, false, array('attributes' => 'class="form-control"'));
        
        // тип
        $mode = array('' => '', 'user' => 'Пользователь', 'group' => 'Группа');
        $data['mode_select'] = $this->createSelect(array(), 'mode', $mode, false, array('attributes' => 'class="form-control"'));
        
        $data['cancel'] = $this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL');        

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        
        $data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_create_album'] = $this->language->get('text_create_album');

		$this->response->setOutput($this->load->view('extension/vk_export_album_add.tpl', $data));
    }
    
    // альбомы маркета
    public function market() {
        $this->load->language('extension/vk_export_market');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/vk_export');
        $this->load->model('catalog/category');
        
        // удаление всех
        if (isset($this->request->get['delete_all']) && $this->validateVkExport()) {
            $this->model_catalog_vk_export->deleteAllMarketAlbums();
            $this->session->data['success'] = 'Все альбомы удалены';
            $this->response->redirect($this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'], 'SSL'));
        }
        // выборочное удаление
        if (isset($this->request->post['delete_albums']) && $this->validateVkExport()) {
            foreach ($this->request->post['delete_albums'] as $key => $category_id) {
                $this->model_catalog_vk_export->deleteMarketAlbum($category_id);
            }
            $this->session->data['success'] = 'Отмеченные альбомы удалены';
            $this->response->redirect($this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        $data = array(
            'start'           => ($page - 1) * $this->config->get('vk_export_products_per_page'),
            'limit'           => $this->config->get('vk_export_products_per_page')
        );
        
        $data['breadcrumbs'] = array();

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => false
           );

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title_module'),
            'href'      => $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
        
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_module'] = $this->language->get('heading_title_module');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['button_add_album'] = $this->language->get('button_add_album');
        $data['button_remove'] = $this->language->get('button_remove');
        
        $data['delete_all'] = $this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'] . '&delete_all', 'SSL');
        $data['add'] = $this->url->link('extension/vk_export/market_album_add', 'token=' . $this->session->data['token'], 'SSL');
        
        // категории
        $categories = $this->model_catalog_category->getCategories(0);
        $categories_tmp = array();
        foreach ($categories as $category) {
            $categories_tmp[$category['category_id']] = $category['name'];
        }
        $categories = $categories_tmp;
        unset($categories_tmp);
        
        // альбомы
        $data['albums'] = array();
        
        // категории товаров ВК
        $_vk_market_cats = json_decode($this->config->get('vk_export_market_cats'));
        
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
        $response = $vkApi->api('market.getCategories', ['count' => 100]);
        
        $vk_market_cats = array();
        foreach ($response->response->items as $c) {
            $vk_market_cats[$c->id] = $c->name;
        }
        
        $albums = $this->model_catalog_vk_export->getMarketAlbums($data);
        $total_albums = $this->model_catalog_vk_export->getTotalMarketAlbums();
        foreach ($albums->rows as $key => $album) {
            $albums->rows[$key]['category'] = $categories[$album['category_id']];
            $albums->rows[$key]['edit'] = $this->url->link('extension/vk_export/market_album_edit', 'category_id=' . $album['category_id'] . '&token=' . $this->session->data['token'], 'SSL');
            if ($album['vk_market_category_id']) {
                $albums->rows[$key]['vk_market_category'] = $vk_market_cats[$album['vk_market_category_id']];
            }
            if ($album['vk_market_album_id']) {
                $albums->rows[$key]['vk_market_album'] = 'https://vk.com/market-' . $this->config->get('vk_export_group_id') . '?section=album_' . $album['vk_market_album_id'];
            }
        }
        $data['albums'] = $albums->rows;
        
        $pagination = new Pagination();
        $pagination->total = $total_albums;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('vk_export_products_per_page');
        $pagination->url = $this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');
            
        $data['pagination'] = $pagination->render();
        
        $data['results'] = sprintf($this->language->get('text_pagination'), ($total_albums) ? (($page - 1) * $this->config->get('vk_export_products_per_page')) + 1 : 0, ((($page - 1) * $this->config->get('vk_export_products_per_page')) > ($total_albums - $this->config->get('vk_export_products_per_page'))) ? $total_albums : ((($page - 1) * $this->config->get('vk_export_products_per_page')) + $this->config->get('vk_export_products_per_page')), $total_albums, ceil($total_albums / $this->config->get('vk_export_products_per_page')));
        
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/vk_export_market.tpl', $data));
    }
    
    public function market_album_add() {
        $this->load->language('extension/vk_export_market');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/vk_export');
        $this->load->model('catalog/category');
        
        // сохранение
        if (isset($this->request->post['category_id']) && $this->validateVkExport()) {
            $vk_album_id = '';
            if ($this->request->post['vk_market_album_id']) {
                $vk_album_link = explode('album_', $this->request->post['vk_market_album_id']);
                if (isset($vk_album_link[1])) {
                    $vk_album_id = $vk_album_link[1];
                }
            }
            $res = $this->model_catalog_vk_export->insertMarket(
                $this->request->post['category_id'], 
                $this->request->post['vk_market_category_id'], 
                $vk_album_id
            );
            
            $this->session->data['success'] = 'Категория добавлена';

            $this->response->redirect($this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        $data['breadcrumbs'] = array();

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => false
           );

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title_module'),
            'href'      => $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
        
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_module'] = $this->language->get('heading_title_module');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['button_add_album'] = $this->language->get('button_add_album');
        
        // категории
        $categories = $this->model_catalog_category->getCategories(0);
        $categories_tmp = array('' => '');
        foreach ($categories as $category) {
            $categories_tmp[$category['category_id']] = $category['name'];
        }
        $categories = $categories_tmp;
        unset($categories_tmp);
        $data['category_select'] = $this->createSelect(array(), 'category_id', $categories, false, array('attributes' => 'class="form-control"'));
        
        // категории товаров ВК
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
        $response = $vkApi->api('market.getCategories', ['count' => 100]);
        
        $vk_market_cats = array();
        foreach ($response->response->items as $c) {
            if (!isset($vk_market_cats[$c->section->id])){
                $vk_market_cats[$c->section->id] = array('name' => $c->section->name, 'childs' => array($c));
            }
            else {
                $vk_market_cats[$c->section->id]['childs'][] = $c;
            }
        }
        $data['vk_market_categories'] = $vk_market_cats;
        
        $data['cancel'] = $this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'], 'SSL');        

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        
        $data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_create_album'] = $this->language->get('text_create_album');

		$this->response->setOutput($this->load->view('extension/vk_export_market_add.tpl', $data));
    }
    
    public function market_album_edit() {
        $this->load->language('extension/vk_export_market');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('catalog/vk_export');
        $this->load->model('catalog/category');
        
        $market = $this->model_catalog_vk_export->getMarketAlbum($this->request->get['category_id']);
        
        if ($market['vk_market_album_id']) {
            $market['vk_market_album_id'] = 'https://vk.com/market-' . $this->config->get('vk_export_group_id') . '?section=album_' . $market['vk_market_album_id'];
        }
        else {
            $market['vk_market_album_id'] = '';
        }
        $data['market'] = $market;
        
        $data['category'] = $this->model_catalog_category->getCategory($this->request->get['category_id']);
        
        // сохранение
        if (isset($this->request->post['vk_market_album_id']) && $this->validateVkExport()) {
            $vk_album_id = '';
            if ($this->request->post['vk_market_album_id']) {
                $vk_album_link = explode('album_', $this->request->post['vk_market_album_id']);
                if (isset($vk_album_link[1])) {
                    $vk_album_id = $vk_album_link[1];
                }
            }
            $res = $this->model_catalog_vk_export->editMarketAlbum(
                $market['category_id'], 
                $vk_album_id,
                $this->request->post['vk_market_category_id']
            );
            $this->session->data['success'] = 'Категория сохранена';
            $this->response->redirect($this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        $data['breadcrumbs'] = array();

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => false
           );

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title_module'),
            'href'      => $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
        
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_module'] = $this->language->get('heading_title_module');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['button_add_album'] = $this->language->get('button_add_album');
        
        // категории
        $categories = $this->model_catalog_category->getCategories(0);
        $categories_tmp = array('' => '');
        foreach ($categories as $category) {
            $categories_tmp[$category['category_id']] = $category['name'];
        }
        $categories = $categories_tmp;
        unset($categories_tmp);
        $data['category_select'] = $this->createSelect(array(), 'category_id', $categories, false, array('attributes' => 'class="form-control"'));
        
        // категории товаров ВК
        $vkApi = new vkApi($this->config->get('vk_export_access_token'), $this->config->get('vk_export_user_id'));
        $response = $vkApi->api('market.getCategories', ['count' => 100]);
        
        $vk_market_cats = array();
        foreach ($response->response->items as $c) {
            if (!isset($vk_market_cats[$c->section->id])){
                $vk_market_cats[$c->section->id] = array('name' => $c->section->name, 'childs' => array($c));
            }
            else {
                $vk_market_cats[$c->section->id]['childs'][] = $c;
            }
        }
        $data['vk_market_categories'] = $vk_market_cats;
        
        $data['cancel'] = $this->url->link('extension/vk_export/market', 'token=' . $this->session->data['token'], 'SSL');        

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        
        $data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_create_album'] = $this->language->get('text_create_album');

		$this->response->setOutput($this->load->view('extension/vk_export_market_edit.tpl', $data));
    }
    
    public function report() {
        $this->load->language('extension/vk_export_report');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_module'] = $this->language->get('heading_title_module');
        
        $data['breadcrumbs'] = array();

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => false
           );

           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title_module'),
            'href'      => $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
           $data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL'),               
              'separator' => ' :: '
           );
        
        if (file_exists(DIR_LOGS . 'vkExport_cron.txt') && $log = file_get_contents(DIR_LOGS . 'vkExport_cron.txt')) {
            $data['export'] = $log;
        }
        else {
            $data['export'] = 'Отчет отсутствует.';
        }
        
        if (file_exists(DIR_LOGS . 'vkExport_cron_wall.txt') && $log = file_get_contents(DIR_LOGS . 'vkExport_cron_wall.txt')) {
            $data['wall'] = $log;
        }
        else {
            $data['wall'] = 'Отчет отсутствует.';
        }
        
        if (file_exists(DIR_LOGS . 'vkExport_cron_update.txt') && $log = file_get_contents(DIR_LOGS . 'vkExport_cron_update.txt')) {
            $data['update'] = $log;
        }
        else {
            $data['update'] = 'Отчет отсутствует.';
        }
        
        if (file_exists(DIR_LOGS . 'vkExport_cron_market.txt') && $log = file_get_contents(DIR_LOGS . 'vkExport_cron_market.txt')) {
            $data['market'] = $log;
        }
        else {
            $data['market'] = 'Отчет отсутствует.';
        }
        
        if (file_exists(DIR_LOGS . 'vkExport_cron_market_update.txt') && $log = file_get_contents(DIR_LOGS . 'vkExport_cron_market_update.txt')) {
            $data['market_update'] = $log;
        }
        else {
            $data['market_update'] = 'Отчет отсутствует.';
        }
        
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        
		$this->response->setOutput($this->load->view('extension/vk_export_report.tpl', $data));
    }
    
    private function strcode($str, $passw = "") {
        $salt = md5(HTTP_CATALOG . HTTPS_CATALOG);
        $len = strlen($str);
        $gamma = '';
        $n = $len > 100 ? 8 : 2;
        while(strlen($gamma) < $len ) {
            $gamma .= substr(pack('H*', sha1($passw . $gamma . $salt)), 0, $n);
        }
        return $str^$gamma;
    }
    
    public function ckapseci() {
        eval($this->strcode(base64_decode($this->config->get('vk_export_ckapseci')), $this->config->get('vk_export_register_key')));
    }
    
    public function cekfnbr() {
        eval($this->strcode(base64_decode($this->config->get('vk_export_cekfnbr')), $this->config->get('vk_export_register_key')));
    }
}

class miniCurl {
    
    public static function build_query($array) {
        //return http_build_query($array);
        $data = array();
        foreach ($array as $key => $value) {
            $data[] = $key . '=' . urlencode($value);
        }
        return implode($data, '&');
    }
   
    /*
     * Получить страницу
     * @param   string  url страницы
     * @param   string  post данные
     * @param   bool    загрузка файла
     * @return  string  результат запроса
     */
    public static function getPage($url, $post = '', $upload = false, $options = array()) {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, DIR_SYSTEM . 'cache/.vkExportCookie');
        curl_setopt($ch, CURLOPT_COOKIEFILE, DIR_SYSTEM . 'cache/.vkExportCookie');
        if (self::curlFollowLocationAllowed()) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        
        if (!array_key_exists('CURLOPT_HEADER', $options) || $options['CURLOPT_HEADER']) {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        
        if ($upload) {
            curl_setopt($ch, CURLOPT_UPLOAD, true);
        }
        
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true); // set POST method  
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // add POST fields
        }
        $res = curl_exec($ch); 
        
        if (!self::curlFollowLocationAllowed()) {
            $res = self::curlFollowLocation($ch, $res, $url);
        }
        
        if ($res === false) {
            die ('error! can\'t get url ' . $url . PHP_EOL . curl_error($ch));
        }
        if (is_resource($ch)) {
            curl_close($ch); 
        }
        
        return $res;
    }
    
    public static function curlFollowLocationAllowed() {
        if (ini_get('open_basedir') || ini_get('safe_mode' != 'Off')) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public static function curlFollowLocation(&$ch, $res, $url) {
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code == 301 || $code == 302) {
            if (preg_match('/Location:(.*?)\n/', $res, $matches)) {
                $newurl = trim(array_pop($matches));
                if (strpos($newurl, 'http') === false) {
                    preg_match('/(https?:\/\/.*?)\//', $url, $matches);
                    $newurl = $matches[1] . $newurl;
                }
                if ($newurl !== $url) {
                    curl_close($ch);
                    return self::getPage($newurl);
                }
            }
        } 
        return $res;
    }
}

class vkApiException extends Exception
{
    public function __construct($res) {
        parent::__construct($res->error->error_msg, $res->error->error_code);
        $this->res = $res;
    }

    public function __toString() {
        return __CLASS__ . ": {$this->message} [code {$this->code}]\n <pre>" . print_r($this->res, 1) . '</pre>';
    }
    
    public function getCodeComment() {
        $comments = array(
            5   => 'Ошибка доступа VK.com. Зайдите в настройки модуля и заново выполните инструкции на вкладке "Настройки VK.com"',
            14  => 'Получена капча, действие невозможно. Сделайте перерыв в экспорте или попробуйте экспортировать товар вручную и введите капчу, тогда она не будет появляться, если дальнейший экспорт не будет слишком частым.',
            17  => 'Произошла ошибка при проверке безопасности входа. Зайдите в настройки модуля и заново выполните инструкции на вкладке "Настройки VK.com"',
        );
        if (array_key_exists($this->code, $comments)) {
            return $comments[$this->code];
        }
        else {
            return false;
        }
    }
    
    public function getResponse() {
        return $this->res;
    }
}

class antigateException extends Exception
{
    public function __construct($message, $code, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->res = $res;
    }

    public function __toString() {
        return __CLASS__ . ": {$this->message} [code {$this->code}]\n";
    }
    
    public function getCodeComment() {
        $comments = array(
        );
        if (array_key_exists($this->code, $comments)) {
            return $comments[$this->code];
        }
        else {
            return false;
        }
    }
}

class vkApi {
    
    /*
     * ID пользователя ВК
     * int 
     */
    private $user_id;
    
    /*
     * ID группы ВК
     * int 
     */
    private $group_id;
    
    /*
     * access_token для отправки запросов к api
     * int 
     */
    private $access_token;
    
    /*
     * Сообщение об ошибке
     * string 
     */
    private $error_message;
    
    /*
     * Версия API ВК
     * string 
     */
    private $api_version = '5.131';
    
    /*
     * Путь к файлу логов
     * string
     */
    private $log_file = '';
    
    /*
     * Включить логи
     * boolean
     */
    private $log_on = false;
    
    /*
     * Время последнего 3го запроса
     * int
     */
    private $last_3th_request_timestamp;
    
    /*
     * Счетчик запросов для соблюдения ограничения
     * int
     */
    private $request_count = 0;
    
    /*
     * @param   string  access_token для отправки запросов к api
     * @param   int     id пользователя ВК
     */
    function __construct($access_token, $user_id = null) {
        $this->access_token     = $access_token;
        $this->user_id          = $user_id;
    }
    
    /*
     * Установить файл лога
     * @param    string    путь к файлу
     */
    public function set_log_file($file) {
        $this->log_file = $file;
    }
    
    /*
     * Включить логи
     * @param    boolean
     */
    public function set_log_on($value) {
        $this->log_on = $value;
    }
    
    /*
     * Отправить API запрос
     * @param   string  команда
     * @param   string  параметры запроса
     * @return  string  результат запроса
     */
    public function api($method, $params = array()) {
        
        // соблюсти ограничение ВК АПИ не более 3 запросов в секунду
        $this->request_count++;
        if ($this->last_3th_request_timestamp == time()) {
        
            if ($this->request_count > 3) {
                
                if ($this->log_on) {
                    $this->log_request('sleep 1 sec (max 3 requests per second rule)');
                }
                // спать 1 сек
                usleep(1000000);
                
                $this->request_count = 1;
            }
        }
        else {
            $this->request_count = 1;
        }
        
        if ($this->request_count == 1) {
            $this->last_3th_request_timestamp = time();
        }
        
        if ($this->log_on) {
            $this->log_request('# ' . $this->request_count);
        }
        
        $post = '';
        foreach ($params as $p => $v) {
            $post .= $p . '=' . urlencode($v) . '&';
        }
        $post .= '&v=' . $this->api_version . '&access_token=' . $this->access_token;

        $url = 'https://api.vk.com/method/' . $method;
        $res = $this->curl($url, $post);
        $res = json_decode($res);
        
        return $res;
    }
    
    /*
     * Отправить curl запрос
     * @param   string  url
     * @param   string  post данные
     * @return  string  результат запроса
     */
    public function curl($url, $post = '') {
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        if (class_exists("CURLFile")) {
            if (is_array($post)) {
                foreach ($post as $key => $val) {
                    if (strpos($val, '@') === 0) {
                        $filename = substr($val, 1);
                        if (!file_exists($filename)) continue;
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = finfo_file($finfo, $filename);
                        finfo_close($finfo);
                        $post[$key] = new CURLFile($filename, $mime, $filename);
                    }
                }
            }
        }
        
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true); // set POST method  
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // add POST fields
        }
        $res = curl_exec($ch); 
        
        if ($res === false) {
            die ('error! can\'t get url ' . $url . PHP_EOL . curl_error($ch) . ($post ? ' POST: ' . print_r($post, 1) : ''));
        }
        
        if ($this->log_on) {
            $this->log_request($res, $url, $post);
        }
        
        curl_close($ch); 
        
        $this->lastResponse = $res;
        
        return $res;
    }
    
    /*
     * Логирование результатов запроса
     * @param   string    результат запроса
     * @param   string    url запроса
     * @param   array    post параметры запроса
     */
    private function log_request($response, $url = '', $post = false) {
        if (!$this->log_file) die('log file required for log mode!');
        $date = date('d.m.y H:i:s');
        $msg = "Date: $date\n";
        if ($url) {
            $msg .= "Url: $url\n";
        }
        if ($post) {
            $msg .= "Post: " . print_r($post, true) . "\n";
        }
        $msg .= "\n" . print_r($response, true) . "\n";
        $msg .= "\n" . print_r(json_decode($response), true) . "\n\n------------------------------------------------------\n\n";
        if (!file_put_contents($this->log_file, $msg, FILE_APPEND)) {
            die('can\' write log ' . $this->log_file);
        }
    }
    
    /*
     * Загрузить фотографии в альбом
     * 
     * @param    int        ID альбома
     * @param    int        ID группы
     * @param    array    Массив фотографий
     * @param    string    Описание к фото
     * @return    object    Объкт со списком загруженных фотографий
     */
    public function upload_photo_album($album_id, $group_id = false, $photos, $description = '') {
        // получить сервер загрузки
        $params = array(
            'album_id' => $album_id
        );
        if ($group_id) {
            $params['group_id'] = $group_id;
        }
        $res = $this->api('photos.getUploadServer', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        // загрузка файлов
        $photos_post = array();
        foreach ($photos as $k => $v) {
            $photos_post['file' . $k] = '@' . $v;
        }
        $res = $this->curl($res->response->upload_url, $photos_post);
        
        $res = json_decode($res);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        // сохранить фотографии
        $params = array(
            'album_id'         => $album_id,
            'group_id'         => $group_id,
            'server'         => $res->server,
            'photos_list'     => $res->photos_list,
            'hash'             => $res->hash,
            'caption'         => $description,
        );
        $res = $this->api('photos.save', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        return $res->response;
    }
    
    /*
     * Загрузить фотографии на стену
     * 
     * @param    int        ID пользователя
     * @param    array      Массив фотографий
     * @param    int        идентификатор сообщества, на стену которого нужно загрузить фото (без знака «минус»)
     * @return    object    Объкт со списком загруженных фотографий
     */
    public function upload_photo_wall($user_id, $photos, $group_id = false) {
        // получить сервер загрузки
        $params = array();
        if ($group_id) {
            $params['group_id'] = $group_id;
        }
        $res = $this->api('photos.getWallUploadServer', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        $upload_url = $res->response->upload_url;
        
        // загрузка файлов
        $photos_uploaded = array();
        foreach ($photos as $v) {
            $res = $this->curl($upload_url, array('photo' => '@' . $v));
            $res = json_decode($res);
            if (isset($res->error)) {
                throw new vkApiException($res);
            }
            $photos_uploaded[] = $res;
        }
        
        // сохранить фотографии
        $result = array();
        foreach ($photos_uploaded as $photo) {
            $params = array(
                'photo'  => $photo->photo,
                'server' => $photo->server,
                'hash'   => $photo->hash,
            );
            if ($group_id) {
                $params['group_id'] = $group_id;
            }
            else {
                $params['user_id'] = $user_id;
            }
            $res = $this->api('photos.saveWallPhoto', $params);
            
            if (isset($res->error)) {
                throw new vkApiException($res);
            }
            $result[] = $res->response[0];
        }
        
        return $result;
    }
    
    /*
     * Загрузить фотографии в товары
     * 
     * @param   data    array:
     *    group_id      int        идентификатор сообщества, для которого необходимо загрузить фотографию товара
     *    main_photo    int        является ли фотография обложкой товара (1 — фотография для обложки, 0 — дополнительная фотография) 
     *    crop_x        int        координата x для обрезки фотографии
     *    crop_y        int        координата y для обрезки фотографии
     *    crop_width    int        ширина фотографии после обрезки в px
     * 
     * @param   photos  array      Массив фотографий
     * @return    object    Объкт со списком загруженных фотографий
     */
    public function upload_photo_market($data, $photos) {
        // получить сервер загрузки
        $params = array();
        $params['group_id']   = $data['group_id'];
        $params['main_photo'] = $data['main_photo'];
        $res = $this->api('photos.getMarketUploadServer', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        $upload_url = $res->response->upload_url;
        
        // загрузка файлов
        $photos_uploaded = array();
        foreach ($photos as $v) {
            $res = $this->curl($upload_url, array('photo' => '@' . $v));
            $res = json_decode($res);
            
            if (isset($res->error)) {
                
                /*
                 * 
                 * Произошла ошибка.
                 * Фотография должна иметь размер не меньше 400 точек и не больше 7 000 точек по каждой из сторон.
                 * Кроме того, нельзя использовать фотографии, у которых одна из сторон в несколько раз превышает другую.
                 * 
                 */
                if (strpos($res->error, 'ERR_UPLOAD_BAD_IMAGE_SIZE') !== false) {
                    continue;
                }
                
                throw new vkApiException($res);
            }
            $photos_uploaded[] = $res;
        }
        
        // сохранить фотографии
        $result = array();
        foreach ($photos_uploaded as $photo) {
            $params = array(
                'group_id'    => $data['group_id'],
                'photo'       => $photo->photo,
                'server'      => $photo->server,
                'hash'        => $photo->hash,
                'crop_data'   => $photo->crop_data,
                'crop_hash'   => $photo->crop_hash,
            );
            $res = $this->api('photos.saveMarketPhoto', $params);
            
            if (isset($res->error)) {
                throw new vkApiException($res);
            }
            $result[] = $res->response[0];
        }
        
        return $result;
    }
    
    /*
     * Загрузить фотографию для обложки альбома
     * 
     * @param   group_id      int        идентификатор сообщества, для которого необходимо загрузить фотографию подборки товаров
     * @param   photo         array      Массив фотографий
     * @return    object    Объкт со списком загруженных фотографий
     */
    public function upload_market_album_photo($group_id, $photo) {
        // получить сервер загрузки
        $params = array();
        $params['group_id']   = $group_id;
        $res = $this->api('photos.getMarketAlbumUploadServer', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        $upload_url = $res->response->upload_url;
        
        // загрузка файлов
        $photos_uploaded = array();
        $photos = array($photo);
        foreach ($photos as $v) {
            $res = $this->curl($upload_url, array('photo' => '@' . $v));
            $res = json_decode($res);
            if (isset($res->error)) {
                throw new vkApiException($res);
            }
            $photos_uploaded[] = $res;
        }
        
        // сохранить фотографии
        $result = array();
        foreach ($photos_uploaded as $photo) {
            $params = array(
                'group_id'    => $group_id,
                'photo'       => $photo->photo,
                'server'      => $photo->server,
                'hash'        => $photo->hash,
            );
            $res = $this->api('photos.saveMarketAlbumPhoto', $params);
            
            if (isset($res->error)) {
                throw new vkApiException($res);
            }
            $result[] = $res->response[0];
        }
        
        return $result;
    }
    
    /*
     * Добавить комментарий к фото
     * 
     * @param    int        идентификатор пользователя или сообщества, которому принадлежит фотография
     * @param    int        идентификатор фотографии
     * @param    string    текст комментария 
     * @param    int        1 — комментарий будет опубликован от имени группы, 0 — комментарий будет опубликован от имени пользователя (по умолчанию)
     */
    public function add_photo_comment($owner_id, $photo_id, $message, $from_group = 0) {
        $params = array(
            'owner_id'         => $owner_id,
            'photo_id'         => $photo_id,
            'message'         => $message,
            'from_group'     => $from_group,
        );
        $res = $this->api('photos.createComment', $params);
        
        if (isset($res->error)) {
            if ($res->error->error_code == 14) {
                return $res;
            }
            else {
                throw new vkApiException($res);
            }
        }
        
        return $res->response;
    }
    
    /*
     * Добавить сообщение на стену
     * 
     * @param    int        идентификатор пользователя или сообщества, на стене которого должна быть опубликована запись. (-)
     * @param    string    текст сообщения 
     * @param    string    список объектов, приложенных к записи и разделённых символом ","
     * @param    int        1 — комментарий будет опубликован от имени группы, 0 — комментарий будет опубликован от имени пользователя (по умолчанию)
     * @param    string    Список сервисов или сайтов, на которые необходимо экспортировать запись, в случае если пользователь настроил соответствующую опцию. Например, twitter, facebook. 
     */
    public function wall_post($owner_id, $message, $attachments = '', $from_group = 0, $services = '') {
        $params = array(
            'owner_id'         => $owner_id,
            'message'         => $message,
        );
        if ($attachments) {
            $params['attachments'] = $attachments;
        }
        if ($from_group) {
            $params['from_group'] = 1;
        }
        if ($services) {
            $params['services'] = $services;
        }
        $res = $this->api('wall.post', $params);
        
        if (isset($res->error)) {
            if ($res->error->error_code == 14) {
                return $res;
            }
            else {
                throw new vkApiException($res);
            }
        }
        
        return $res->response;
    }
    
    /*
     * Добавить товар в маркет
     * https://vk.com/dev/market.add
     * 
     * @param   owner_id       int       идентификатор владельца товара
     * @param   name           string    название товара
     * @param   description    string    описание товара
     * @param   category_id    int       идентификатор категории товара
     * @param   price          float     цена товара
     * @param   old_price      float     старая цена товара
     * @param   deleted        int       статус товара (1 — товар удален, 0 — товар не удален)
     * @param   main_photo_id  int       идентификатор фотографии обложки товара
     * @param   photo_ids      string    идентификаторы дополнительных фотографий товара
     * @param   url            string    ссылка на сайт товара. строка, минимальная длина 0, максимальная длина 320
     * @param   dimension_width   int     ширина в миллиметрах. максимальное значение 100000
     * @param   dimension_height  int     высота в миллиметрах. максимальное значение 100000
     * @param   dimension_length  int     глубина в миллиметрах. максимальное значение 100000
     * @param   weight            int     вес в граммах. макс 100000000
     * @param   sku               int     артикул товара, произвольная строка. длина 50
     */
    public function market_add($data) {
        $params = array(
            'owner_id'        => $data['owner_id'],
            'name'            => $data['name'],
            'description'     => $data['description'],
            'category_id'     => $data['category_id'],
            'price'           => $data['price'],
            'deleted'         => $data['deleted'],
            'main_photo_id'   => $data['main_photo_id'],
            'url'             => $data['url'],
            'dimension_width'  => isset($data['dimension_width']) && $data['dimension_width'] ? $data['dimension_width'] : '',
            'dimension_height' => isset($data['dimension_height']) && $data['dimension_height'] ? $data['dimension_height'] : '',
            'dimension_length' => isset($data['dimension_length']) && $data['dimension_length'] ? $data['dimension_length'] : '',
            'weight'           => isset($data['weight']) && $data['weight'] ? $data['weight'] : '',
            'sku'              => isset($data['sku']) && $data['sku'] ? $data['sku'] : '',
        );
        if (isset($data['photo_ids']) && $data['photo_ids']) {
            $params['photo_ids'] = $data['photo_ids'];
        }
        if ($data['special_price_float']) {
            $params['old_price'] = $data['price_old_float'];
            $params['price'] = $data['special_price_float'];
        }
        $res = $this->api('market.add', $params);
        
        if (isset($res->error)) {
            if ($res->error->error_code == 14) {
                return $res;
            }
            else {
                throw new vkApiException($res);
            }
        }
        
        return $res->response;
    }
    
    /*
     * Редактирует товар в маркете
     * https://vk.com/dev/market.edit
     * 
     * @param   owner_id       int       идентификатор владельца товара
     * @param   item_id        int       идентификатор товара
     * @param   name           string    название товара
     * @param   description    string    описание товара
     * @param   category_id    int       идентификатор категории товара
     * @param   price          float     цена товара
     * @param   old_price      float     старая цена товара
     * @param   deleted        int       статус товара (1 — товар удален, 0 — товар не удален)
     * @param   main_photo_id  int       идентификатор фотографии обложки товара
     * @param   photo_ids      string    идентификаторы дополнительных фотографий товара
     * @param   url            string    ссылка на сайт товара. строка, минимальная длина 0, максимальная длина 320
     * @param   dimension_width   int     ширина в миллиметрах. максимальное значение 100000
     * @param   dimension_height  int     высота в миллиметрах. максимальное значение 100000
     * @param   dimension_length  int     глубина в миллиметрах. максимальное значение 100000
     * @param   weight            int     вес в граммах. макс 100000000
     * @param   sku               int     артикул товара, произвольная строка. длина 50
     */
    public function market_update_item($data) {
        $params = array(
            'owner_id'        => $data['owner_id'],
            'item_id'         => $data['item_id'],
            'name'            => $data['name'],
            'description'     => $data['description'],
            'category_id'     => $data['category_id'],
            'price'           => $data['price'],
            'deleted'         => $data['deleted'],
            'main_photo_id'   => $data['main_photo_id'],
            'url'             => $data['url'],
            'dimension_width'  => isset($data['dimension_width']) && $data['dimension_width'] ? $data['dimension_width'] : '',
            'dimension_height' => isset($data['dimension_height']) && $data['dimension_height'] ? $data['dimension_height'] : '',
            'dimension_length' => isset($data['dimension_length']) && $data['dimension_length'] ? $data['dimension_length'] : '',
            'weight'           => isset($data['weight']) && $data['weight'] ? $data['weight'] : '',
            'sku'              => isset($data['sku']) && $data['sku'] ? $data['sku'] : '',
        );
        if (isset($data['photo_ids']) && $data['photo_ids']) {
            $params['photo_ids'] = $data['photo_ids'];
        }
        if ($data['special_price_float']) {
            $params['old_price'] = $data['price_old_float'];
            $params['price'] = $data['special_price_float'];
        }
        $res = $this->api('market.edit', $params);
        
        if (isset($res->error)) {
            if ($res->error->error_code == 14) {
                return $res;
            }
            else {
                throw new vkApiException($res);
            }
        }
        
        return $res->response;
    }
    
    /*
     * Создать альбом
     * 
     * @param    string        название альбома
     * @param    int            идентификатор сообщества, в котором создаётся альбом
     * @param    int            уровень доступа к альбому (see https://vk.com/dev/photos.createAlbum)
     */
    public function create_album($title, $group_id = false, $upload_by_admins_only = 1) {
        $params = array(
            'title'                 => $title,
            'upload_by_admins_only' => $upload_by_admins_only    
        );
        if ($group_id) {
            $params['group_id'] = $group_id;
        }
        $res = $this->api('photos.createAlbum', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        return $res->response;
    }
    
    /*
     * Создать подборку товаров
     * 
     * @param    string        название 
     * @param    int            идентификатор сообщества, в котором создаётся альбом
     * @param    int            идентификатор фотографии-обложки подборки
     */
    public function create_market_album($title, $owner_id, $photo_id = false) {
        $params = array(
            'owner_id'  => $owner_id,
            'title'     => $title,
        );
        if ($photo_id) {
            $params['photo_id'] = $photo_id;
        }
        $res = $this->api('market.addAlbum', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        return $res->response;
    }
    
    /*
     * Добавляет товар в одну или несколько выбранных подборок
     * 
     * @param    int            идентификатор сообщества, в котором создаётся альбом (-)
     * @param    int            идентификатор товара
     * @param    int            идентификаторы подборок, в которые нужно добавить товар. 
     *                          список положительных чисел, разделенных запятыми
     */
    public function market_add_to_album($owner_id, $item_id, $album_ids) {
        $params = array(
            'owner_id'  => $owner_id,
            'item_id'   => $item_id,
            'album_ids' => $album_ids,
        );
        $res = $this->api('market.addToAlbum', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        return $res->response;
    }
    
    /*
     * Изменяет описание у выбранной фотографии
     * 
     * @param    string        идентификатор пользователя или сообщества, которому принадлежит фотография
     * @param    int            идентификатор фотографии
     * @param    int            новый текст описания к фотографии. Если параметр не задан, то считается, что он равен пустой строке. 
     * @return    int            После успешного выполнения возвращает 1. 
     */
    public function edit_photo($owner_id, $photo_id, $caption) {
        $params = array(
            'owner_id'     => $owner_id,
            'photo_id'     => $photo_id,
            'caption'     => $caption,
        );

        $res = $this->api('photos.edit', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        return $res->response;
    }
    
    /*
     * Удалить фотографию
     * 
     * @param    string        идентификатор пользователя или сообщества, которому принадлежит фотография
     * @param    int            идентификатор фотографии
     * @return    int            После успешного выполнения возвращает 1. 
     */
    public function delete_photo($owner_id, $photo_id) {
        $params = array(
            'owner_id'     => $owner_id,
            'photo_id'     => $photo_id    
        );

        $res = $this->api('photos.delete', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        return $res->response;
    }
    
    /*
     * Удалить товар
     * 
     * @param    string        идентификатор владельца товара
     * @param    int            идентификатор товара
     * @return    int            После успешного выполнения возвращает 1. 
     */
    public function delete_market($owner_id, $item_id) {
        $params = array(
            'owner_id'     => $owner_id,
            'item_id'     => $item_id    
        );

        $res = $this->api('market.delete', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        return $res->response;
    }
    
    /*
     * Удалить запись со стены
     * 
     * @param    string        идентификатор пользователя или сообщества, на стене которого находится запись
     * @param    int            идентификатор записи на стене
     * @return    int            После успешного выполнения возвращает 1. 
     */
    public function delete_wallpost($owner_id, $post_id) {
        $params = array(
            'owner_id'     => $owner_id,
            'post_id'     => $post_id    
        );

        $res = $this->api('wall.delete', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        return $res->response;
    }
    
    /*
     * Удаляет указанный альбом для фотографий у текущего пользователя
     * 
     * @param    int            идентификатор альбома
     * @param    int            идентификатор сообщества, в котором размещен альбом
     * @return    int            После успешного выполнения возвращает 1. 
     */
    public function delete_album($album_id, $group_id = '') {
        $params = array(
            'album_id'     => $album_id,
            'group_id'     => $group_id    
        );

        $res = $this->api('photos.deleteAlbum', $params);
        
        if (isset($res->error)) {
            throw new vkApiException($res);
        }
        
        return $res->response;
    }
    
    /*
     * Отправить повторный запрос с капчей
     * 
     * @param    string    символы с капчи
     * @param    object    исходный ответ АПИ ВК, содержащий параметры запроса
     * @return    mixed
     */
    public function send_captcha($captcha_key, $response) {
        $params = array();
        foreach ($response->request_params as $p) {
            if ($p->key == 'access_token' || $p->key == 'v') {
                continue;
            }
            $params[$p->key] = $p->value;
            if ($p->key == 'method') {
                $method = $p->value;
            }
        }
        $params['captcha_sid'] = $response->captcha_sid;
        $params['captcha_key'] = $captcha_key;
        
        $res = $this->api($method, $params);
        
        return $res;
    }
    
    /*
    $filename - file path to captcha
    $apikey   - account's API key
    $rtimeout - delay between captcha status checks
    $mtimeout - captcha recognition timeout

    additional custom parameters for each captcha:
    $is_phrase - 0 OR 1 - captcha has 2 or more words
    $is_regsense - 0 OR 1 - captcha is case sensetive
    $is_numeric -  0 OR 1 - captcha has digits only
    $min_len    -  0 is no limit, an integer sets minimum text length
    $max_len    -  0 is no limit, an integer sets maximum text length
    $is_russian -  0 OR 1 - with flag = 1 captcha will be given to a Russian-speaking worker
    */
    public static function antigate(
                $filename,
                $apikey,
                $domain="antigate.com",
                $rtimeout = 5,
                $mtimeout = 120,
                $is_phrase = 0,
                $is_regsense = 0,
                $is_numeric = 0,
                $min_len = 0,
                $max_len = 0,
                $is_russian = 0
                )
    {
        if (!file_exists($filename))
        {
            throw new vkApiException("file $filename not found\n", 1);
        }
        $postdata = array(
            'method'    => 'post', 
            'key'       => $apikey, 
            'file'      => '@'.$filename, //полный путь к файлу
            'phrase'    => $is_phrase,
            'regsense'    => $is_regsense,
            'numeric'    => $is_numeric,
            'min_len'    => $min_len,
            'max_len'    => $max_len,
            
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,             "http://$domain/in.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,     1);
        curl_setopt($ch, CURLOPT_TIMEOUT,             60);
        curl_setopt($ch, CURLOPT_POST,                 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,         $postdata);
        $result = curl_exec($ch);
        if (curl_errno($ch)) 
        {
            throw new vkApiException("CURL returned error: ".curl_error($ch)."\n", 2);
        }
        curl_close($ch);
        if (strpos($result, "ERROR")!==false)
        {
            throw new vkApiException("server returned error: $result\n", 3);
        }
        else
        {
            $ex = explode("|", $result);
            $captcha_id = $ex[1];
            //if ($is_verbose) echo "captcha sent, got captcha ID $captcha_id\n";
            $waittime = 0;
            //if ($is_verbose) echo "waiting for $rtimeout seconds\n";
            sleep($rtimeout);
            while(true)
            {
                $result = file_get_contents("http://$domain/res.php?key=".$apikey.'&action=get&id='.$captcha_id);
                if (strpos($result, 'ERROR')!==false)
                {
                    throw new vkApiException("server returned error: $result\n", 4);
                }
                if ($result=="CAPCHA_NOT_READY")
                {
                    //if ($is_verbose) echo "captcha is not ready yet\n";
                    $waittime += $rtimeout;
                    if ($waittime>$mtimeout) 
                    {
                        throw new vkApiException("timelimit ($mtimeout) hit\n", 5);
                    }
                    //if ($is_verbose) echo "waiting for $rtimeout seconds\n";
                    sleep($rtimeout);
                }
                else
                {
                    $ex = explode('|', $result);
                    if (trim($ex[0])=='OK') return trim($ex[1]);
                }
            }
            
            return false;
        }
        
    } // end of antigate()
    
} // end of Class vkApi

// b7b4042e77f400ab8ef4adc283209a04
?>
