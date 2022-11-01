<?php

use \progroman\CityManager\CityManager;
use \progroman\CityManager\Driver\Sypex;
use progroman\Common\DocumentProxy;

/**
 * Class ControllerExtensionModuleProgromanCityManager
 * @property ModelExtensionModuleProgromanFias $model_extension_module_progroman_fias
 * @property ModelExtensionModuleProgromanCityManager $model_extension_module_progroman_citymanager
 * @property \progroman\CityManager\CityManager $progroman_citymanager
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class ControllerExtensionModuleProgromanCityManager extends Controller {

    public function index($params = []) {
        if (!$this->isModuleEnabled()) {
            return '';
        }

        $this->language->load('extension/module/progroman/citymanager');
        $city = $this->getCityName();
        $url = isset($params['url']) ? $params['url'] : $this->request->server['REQUEST_URI'];
        $data['city'] = $city ? $city : $this->language->get('text_unknown');
        $data['text_zone'] = $this->language->get('text_zone');
        $data['confirm'] = $this->loadController('confirm', ['url' => $url]);

        return $this->loadView('content', $data);
    }

    public function init() {
        $json = [];
        $json['content'] = $this->loadController('', ['url' => $this->request->get['url']]);
        $json['messages'] = $this->progroman_citymanager->getMessages();
        $this->sendJson($json);
    }

    public function confirm($params) {
        $this->language->load('extension/module/progroman/citymanager');
        $text_your_city = $this->language->get('text_your_city');
        $city_name = $this->progroman_citymanager->getCityName();

        if (!$city_name) {
            $city_name = $this->progroman_citymanager->getZoneName('', true);
            $text_your_city = $this->language->get('text_your_region');
        }

        if (!$city_name) {
            $city_name = $this->progroman_citymanager->getCountryName();
            $text_your_city = $this->language->get('text_your_region');
        }

        $key = $this->progroman_citymanager->getSessionKey();
        $cookie_key = $this->progroman_citymanager->getCookieKey('confirm');

        if ($this->progroman_citymanager->setting('popup_cookie_time') == 'always') {
            $show_confirm = true;
        } elseif ($this->progroman_citymanager->setting('popup_cookie_time') == 'disabled') {
            $show_confirm = false;
        } else {
            $show_confirm = $city_name && empty($this->session->data[$key]['shown_confirm']) && empty($this->request->cookie[$cookie_key]);
        }

        if ($show_confirm) {
            $data = [
                'city' => $city_name,
                'text_your_city' => $text_your_city,
                'text_yes' => $this->language->get('text_yes'),
                'text_no' => $this->language->get('text_no'),
                'confirm_redirect' => $this->progroman_citymanager->getRedirectUrlForManual($params['url'])
            ];

            return $this->loadView('confirm', $data);
        }
    }

    public function cities() {
        $this->language->load('extension/module/progroman/citymanager');
        $data['text_search'] = $this->language->get('text_search');
        $data['text_your_city'] = $this->language->get('text_your_city');

        $this->load->model('extension/module/progroman/citymanager');
        $cities = $this->model_extension_module_progroman_citymanager->getCities(['redirect']);
        $count_columns = 3;
        $data['columns'] = $cities ? array_chunk($cities, ceil(count($cities) / $count_columns)) : [];

        $city = $this->getCityName();
        $data['city'] = $city ? $city : $this->language->get('text_unknown');

        $view = $this->loadView('cities', $data);
        $this->response->setOutput($view);

        // Для загрузки напрямую, не через ajax.
        // Возвращать значение нужно только в OC 2+, в младших версиях вызывает ошибку.
        if (VERSION >= 2) {
            return $view;
        }
    }

    public function search() {
        $json = [];
        if (!empty($this->request->get['term'])) {
            $this->load->model('extension/module/progroman/fias');
            $json = $this->model_extension_module_progroman_fias->findFiasByName($this->request->get['term']);
        }

        $this->sendJson($json);
    }

    public function save() {
        $fias_id = isset($this->request->get['fias_id']) ? $this->request->get['fias_id'] : 0;
        $success = $fias_id && $this->progroman_citymanager->setFias($fias_id) ? 1 : 0;
        $this->sendJson(['success' => $success]);
    }

    /**
     * Отмечаем, что "Угадали" показан
     * @param bool $force Принудительно, без учета значения popup_cookie_time
     */
    public function confirmShown($force = false) {
        $json = ['success' => false, 'force' => $force ? 'yes' : 'no'];
        $time = $this->progroman_citymanager->setting('popup_cookie_time');

        if ((is_numeric($time) && !$this->progroman_citymanager->setting('popup_user_answer')) || $force) {
            $this->session->data[$this->progroman_citymanager->getSessionKey()]['shown_confirm'] = 1;
            $this->progroman_citymanager->setCookie($this->progroman_citymanager->getCookieKey('confirm'), 1, (int)$time);
            $json['success'] = true;
        }

        $this->sendJson($json);
    }

    public function confirmClick(){
        $this->confirmShown(true);
    }

    private function getCityName() {
        if ($popup_city_name = $this->progroman_citymanager->getPopupCityName()) {
            return $popup_city_name;
        }

        if ($city_name = $this->progroman_citymanager->getCityName()) {
            return $city_name;
        }

        if ($zone_name = $this->progroman_citymanager->getZoneName('', true)) {
            return $zone_name;
        }

        if ($country_name = $this->progroman_citymanager->getCountryName()) {
            return $country_name;
        }

        return false;
    }

    /**
     * Включаем модуль
     */
    public function startup() {
        \progroman\Common\Registry::instance()->setRegistry($this->registry);
        $citymanager = CityManager::instance();
        $this->registry->set('progroman_city_manager', $citymanager);
        $this->registry->set('progroman_citymanager', $citymanager);

        if ($this->isModuleEnabled()) {
            // Включаем определение по IP
            if ($this->progroman_citymanager->setting('use_geoip')) {
                // Для теста: Москва 94.25.169.110, Тамбов 193.34.14.221, Воронеж 217.118.95.92, Казань 217.66.24.13, Раменское 91.214.97.249
                $sypex = new Sypex();
                $sypex->setSxgeoPath(CityManager::getSxgeoPath());
                CityManager::addDriver($sypex);
            }

            $citymanager->defineLocation();
            $citymanager->setCurrency();
            $citymanager->saveInSession();
        }
    }

    /**
     * Загружаем скрипты и шаблоны
     */
    public function load() {
        if ($this->isModuleEnabled()) {
            // Отключаем замену поля город для авторизованных
            if ($this->customer->isLogged()) {
                $this->progroman_citymanager->setting('replace_input_city', false);
            }

            // Загружаем контроллер модуля для передачи в шаблоны
            $this->registry->set('prmn_cmngr', !$this->progroman_citymanager->setting('use_ajax') ? $this->loadController() : '');

            // Включаем замену в title
            if ($this->progroman_citymanager->setting('replace_blanks')) {
                $document = new DocumentProxy();
                $document->copy($this->document);
                $this->registry->set('document', $document);
            }

            $version_script = CityManager::VERSION . '-0';
            if (VERSION >= 2) {
                $this->document->addScript('catalog/view/javascript/progroman/jquery.progroman.autocomplete.js?v=' . $version_script);
            }

            $this->document->addScript('catalog/view/javascript/progroman/jquery.progroman.citymanager.js?v=' . $version_script);
            $this->document->addStyle('catalog/view/javascript/progroman/progroman.citymanager.css?v=' . $version_script);
        } else {
            $this->registry->set('prmn_cmngr', '');
        }
    }

    private function isModuleEnabled() {
        return php_sapi_name() != 'cli' && $this->config->get('module_progroman_citymanager_status');
    }

    private function loadView($view, $data = []) {
        $view = 'extension/module/progroman/citymanager/' . $view;

        if (VERSION < 2) {
            $view = '/template/' . $view . '.tpl';
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $view)) {
                $this->template = $this->config->get('config_template') . $view;
            } else {
                $this->template = 'default' . $view;
            }

            $this->data = $data;
            return $this->render();
        } elseif (VERSION < '2.2') {
            $view = '/template/' . $view . '.tpl';
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $view)) {
                return $this->load->view($this->config->get('config_template') . $view, $data);
            } else {
                return $this->load->view('default' . $view, $data);
            }
        } else {
            return $this->load->view($view, $data);
        }
    }

    private function loadController($name = '', $data = []) {
        $path = 'extension/module/progroman/citymanager' . ($name ? '/' . $name : '');

        if (VERSION < 2) {
            return $this->getChild($path, $data);
        } else {
            return $this->load->controller($path, $data);
        }
    }

    private function sendJson($json) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}