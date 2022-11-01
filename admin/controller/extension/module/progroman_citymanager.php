<?php
use progroman\CityManager\CityManager;
use progroman\CityManager\DatabaseFile\BaseIP;
use progroman\CityManager\DatabaseFile\BaseCities;
use progroman\CityManager\DatabaseFileAction\DatabaseFileAction;
use progroman\MMC;

/**
 * Class ControllerExtensionModuleProgromanCityManager
 * @property \ModelSettingSetting model_setting_setting
 * @property \ModelExtensionModuleProgromanCityManager $model_citymanager
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class ControllerExtensionModuleProgromanCityManager extends Controller {
    private $error = [];

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language($this->getModulePath());
        $this->load->model('extension/module/progroman_citymanager');
        $this->model_citymanager = $this->model_extension_module_progroman_citymanager;
        \progroman\Common\Registry::instance()->setRegistry($registry);
    }

    public function index() {
        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [
            ['text' => $this->language->get('text_home'), 'href' => $this->makeUrl('common/dashboard')],
            ['text' => $this->language->get('text_module'), 'href' => $this->makeUrl($this->getExtensionPath())],
            ['text' => $this->language->get('heading_title'), 'href' => $this->makeModuleUrl()]
        ];

        $data['general'] = $this->loadController($this->getModulePath() . '/general');

        $data['cancel'] = $this->makeUrl($this->getExtensionPath());
        $data['url_search'] = $this->makeModuleUrl('search');
        $data['url_redirects'] = $this->makeModuleUrl('redirects');
        $data['url_popups'] = $this->makeModuleUrl('popups');
        $data['url_messages'] = $this->makeModuleUrl('messages');
        $data['url_currencies'] = $this->makeModuleUrl('currencies');
        $data['url_zone_fias'] = $this->makeModuleUrl('zonefias');
        $data['url_license'] = $this->makeModuleUrl('license');
        $data['url_support'] = $this->makeModuleUrl('support');

        $data['valid_license'] = $this->validLicense();

        $this->document->addStyle('view/stylesheet/progroman.citymanager.css');
        if (VERSION >= 2) {
            $this->document->addScript('/catalog/view/javascript/progroman/jquery.progroman.autocomplete.js?v=' . CityManager::VERSION . '-0');
        }

        $data['header'] = $this->loadController('common/header');
        $data['footer'] = $this->loadController('common/footer');

        if (VERSION >= 2) {
            $data['column_left'] = $this->loadController('common/column_left');
        }

        $this->initLangVariables($data);

        $this->response->setOutput($this->loadView('index', $data));
    }

    public function general() {
        $data['action_general'] = $this->makeModuleUrl('savegeneral');
        $data['settings'] = $this->config->get('module_progroman_citymanager_setting');
        $data['status'] = $this->config->get('module_progroman_citymanager_status');

        if (isset($data['settings']['default_city'])) {
            $data['settings']['default_city_name'] = $this->model_citymanager->getFiasName($data['settings']['default_city']);
        }

        $data['url_base_action'] = $this->makeModuleUrl('baseaction');
        $data['url_bases'] = $this->makeModuleUrl('bases');

        $data['use_default_city'] = isset($data['settings']['use_default_city']) ? $data['settings']['use_default_city'] : null;
        $data['use_default_city_values'] = [
            'text_not_determined' => $this->language->get('text_not_determined'),
            'any_country' => $this->language->get('text_any_country'),
            'one_country' => $this->language->get('text_one_country'),
        ];

        $this->initLangVariables($data);

        return $this->loadView('general', $data);
    }

    public function bases() {
        $data['base_ip'] = new BaseIP($this->language->all());
        $data['download_files'] = [];
        foreach (CityManager::getCitiesBaseList() as $base) {
            $base_cities = isset($base['class']) ? new $base['class']($this->language->all()) : new BaseCities($this->language->all());
            $base_cities->setName($base['name'])->setCountry($base);
            $data['download_files'][] = $base_cities;
        }

        $this->initLangVariables($data);

        $output = $this->loadView('bases', $data);
        $this->response->setOutput($output);
    }

    public function popups() {
        $data['settings'] = $this->config->get('module_progroman_citymanager_setting');
        $data['action_popups'] = $this->makeModuleUrl('savepopups');
        $data['cities'] = $this->model_citymanager->getCities();
        $data['popup_cookie_time'] = isset($data['settings']['popup_cookie_time']) ? $data['settings']['popup_cookie_time'] : 0;
        if (is_numeric($data['popup_cookie_time'])) {
            $data['popup_cookie_time'] = (int)$data['popup_cookie_time'];
        }

        $data['cookie_time_values'] = [
            0 => $this->language->get('text_every_visit'),
            86400 => $this->language->get('text_day'),
            604800 => $this->language->get('text_week'),
            2592000 => $this->language->get('text_month'),
            31536000 => $this->language->get('text_year'),
            'always' => $this->language->get('text_always'),
            'disabled' => $this->language->get('text_no'),
        ];

        $this->initLangVariables($data);

        $this->response->setOutput($this->loadView('popup', $data));
    }

    public function messages() {
        $data['action_savemessage'] = $this->makeModuleUrl('savemessage');
        $data['action_removemessage'] = $this->makeModuleUrl('removemessage');
        $data['settings'] = $this->config->get('module_progroman_citymanager_setting');

        $limit = $this->config->get(VERSION < 2 ? 'config_admin_limit' : 'config_limit_admin');
        $page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
        $filter_data = [
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
        ];

        $total_messages = $this->model_citymanager->getTotalMessages();
        $data['messages'] = $this->model_citymanager->getMessages($filter_data);
        foreach ($data['messages'] as & $message) {
            $message['fias_name'] = $this->model_citymanager->getFiasName($message['fias_id']);
        }

        $pagination = new Pagination();
        $pagination->total = $total_messages;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->makeModuleUrl('messages', '&page={page}');
        $pagination->text = $this->language->get('text_pagination');

        $data['pagination'] = $pagination->render();
        $data['results'] = $limit > 0 ? sprintf($this->language->get('text_pagination'),
            ($total_messages) ? (($page - 1) * $limit) + 1 : 0,
            ((($page - 1) * $limit) > ($total_messages - $limit)) ? $total_messages : ((($page - 1) * $limit) + $limit),
            $total_messages, ceil($total_messages / $limit)) : '';

        $this->initLangVariables($data);

        $this->response->setOutput($this->loadView('messages', $data));
    }

    public function redirects() {
        $data['action_redirects'] = $this->makeModuleUrl('saveredirects');
        $data['settings'] = $this->config->get('module_progroman_citymanager_setting');

        $data['redirects'] = $this->model_citymanager->getRedirects();
        foreach ($data['redirects'] as & $redirect) {
            $redirect['fias_name'] = $this->model_citymanager->getFiasName($redirect['fias_id']);
        }

        $this->initLangVariables($data);

        $this->response->setOutput($this->loadView('redirects', $data));
    }

    public function currencies() {
        $data['action_currencies'] = $this->makeModuleUrl('savecurrencies');
        $data['settings'] = $this->config->get('module_progroman_citymanager_setting');

        $this->load->model('localisation/currency');
        $this->load->model('localisation/country');
        $data['countries'] = $this->model_localisation_country->getCountries();
        $data['currencies'] = $this->model_localisation_currency->getCurrencies();
        $data['cm_currencies'] = $this->model_citymanager->getCurrencies();

        $this->initLangVariables($data);

        $this->response->setOutput($this->loadView('currencies', $data));
    }

    public function zoneFias() {
        $data['countries'] = $this->model_citymanager->getNoRelativeCountries();
        $data['zones'] = $this->model_citymanager->getNoRelativeZones();

        $this->initLangVariables($data);

        $this->response->setOutput($this->loadView('zone_fias', $data));
    }

    public function license() {
        $data['settings'] = $this->config->get('module_progroman_citymanager_setting');
        $data['url_get_secret'] = $this->makeModuleUrl('getsecretkey');
        $data['url_clear_secret'] = $this->makeModuleUrl('clearsecretkey');
        $data['valid_license'] = $this->validLicense();
        $this->initLangVariables($data);
        $this->response->setOutput($this->loadView('license', $data));
    }

    public function support() {
        $this->initLangVariables($data);
        $this->response->setOutput($this->loadView('support', $data));
    }

    public function getSecretKey() {
        $json = ['success' => false];
        if ($this->validLicense()) {
            $json['message'] = $this->language->get('text_license_success') . "\n" . $this->language->get('text_page_reload');
            return $this->sendJson($json);
        }

        $mmc = new MMC(PROGROMAN_CITYMANAGER_DIR . '/public.key', $this->config->get('config_admin_language'));
        $key = $mmc->setParams(CityManager::MODULE_NAME, CityManager::VERSION)->getSecretKey();

        if (!$key) {
            $json['message'] = implode("\n", $mmc->getErrors());
            return $this->sendJson($json);
        }

        $this->editSetting(['secret_key' => $key]);
        $json['message'] = $this->language->get('text_license_success');
        $json['key'] = $key;
        $json['success'] = true;

        $this->sendJson($json);
    }

    public function clearSecretKey() {
        $json = ['success' => false];
        if ($this->hasPermission()) {
            $this->editSetting(['secret_key' => '']);
            $json['message'] = $this->language->get('text_success') . "\n" . $this->language->get('text_page_reload');
            $json['success'] = true;
        } else {
            $json['message'] = $this->error['warning'];
        }

        $this->sendJson($json);
    }

    public function search() {
        $json = [];
        if (isset($this->request->get['term'])) {
            $json = $this->model_citymanager->findFiasByName($this->request->get['term'], !empty($this->request->get['short']));
        }

        $this->sendJson($json);
    }

    public function saveGeneral() {
        $json = [];
        if ($this->hasPermission()) {
            if (!empty($this->request->post['setting']['main_domain'])) {
                $this->request->post['setting']['main_domain'] = rtrim(str_replace(['http://', 'https://'], '', $this->request->post['setting']['main_domain']), '/');
            }

            $this->editSetting($this->request->post['setting'], $this->request->post['status']);
        } else {
            $json['warning'] = $this->error['warning'];
        }

        $this->sendJson($json);
    }

    public function savePopups() {
        $json = [];
        if ($this->hasPermission()) {
            if (isset($this->request->post['popup_cities'])) {
                foreach ($this->request->post['popup_cities'] as $key => $value) {
                    if (!(int)$value['fias_id']) {
                        $json['errors']['cities'][$key] = $this->language->get('error_fias');
                    }
                }

                if (empty($json['errors'])) {
                    $this->model_citymanager->clearCities();

                    if (!empty($this->request->post['popup_cities'])) {
                        $this->model_citymanager->editCities($this->request->post['popup_cities']);
                    }
                }
            } else {
                $this->model_citymanager->clearCities();
            }
        }

        $this->sendJson($json);
    }

    public function saveMessage() {
        $json = [];
        if ($this->hasPermission()) {
            if (empty($this->request->post['key']) || !preg_match('#^[a-zA-Z0-9_-]*$#', $this->request->post['key'])) {
                $json['errors']['key'] = $this->language->get('error_key');
            }

            $fias_id = isset($this->request->post['fias_id']) ? (int)$this->request->post['fias_id'] : 0;
            if (!$fias_id) {
                $json['errors']['fias']= $this->language->get('error_fias');
            }

            if (empty($json['errors'])) {
                if (!empty($this->request->post['id'])) {
                    $this->model_citymanager->editMessage($this->request->post['id'], $fias_id, $this->request->post['key'], $this->request->post['value']);
                } else {
                    $this->model_citymanager->addMessage($fias_id, $this->request->post['key'], $this->request->post['value']);
                }
            }
        }

        $this->sendJson($json);
    }

    public function removeMessage() {
        $json = [];
        if ($this->hasPermission()) {
            if (!empty($this->request->post['id'])) {
                $this->model_citymanager->removeMessage($this->request->post['id']);
            }
        }

        $this->sendJson($json);
    }

    public function saveRedirects() {
        $json = [];
        if ($this->hasPermission()) {
            if (isset($this->request->post['redirects'])) {
                $url_regex = "#^http(?:s)?://(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/(?:.*/)*$#u";
                foreach ($this->request->post['redirects'] as $key => & $value) {
                    if (!$value['url']) {
                        $json['errors']['subdomain'][$key] = $this->language->get('error_subdomain');
                    } else {
                        $value['url'] = $this->prepareDomainForRedirect($value['url']);
                        if (!preg_match($url_regex, $value['url'])) {
                            $json['errors']['subdomain'][$key] = $this->language->get('error_subdomain');
                        }
                    }

                    if (!(int)$value['fias_id']) {
                        $json['errors']['fias'][$key] = $this->language->get('error_fias');
                    }
                }

                if (empty($json['errors'])) {
                    $this->model_citymanager->clearRedirects();

                    if (!empty($this->request->post['redirects'])) {
                        $this->model_citymanager->editRedirects($this->request->post['redirects']);
                    }
                }
            } else {
                $this->model_citymanager->clearRedirects();
            }
        }

        $this->sendJson($json);
    }

    public function saveCurrencies() {
        $json = [];
        if ($this->hasPermission()) {
            if (isset($this->request->post['currencies'])) {
                foreach ($this->request->post['currencies'] as $key => $value) {
                    if (!(int)$value['country_id']) {
                        $json['errors']['country'][$key] = $this->language->get('error_currency_country');
                    }

                    if (!$value['code']) {
                        $json['errors']['code'][$key] = $this->language->get('error_currency_code');
                    }
                }

                if (empty($json['errors'])) {
                    $this->model_citymanager->clearCurrencies();

                    if (!empty($this->request->post['currencies'])) {
                        $this->model_citymanager->editCurrencies($this->request->post['currencies']);
                    }
                }
            } else {
                $this->model_citymanager->clearCurrencies();
            }
        }

        $this->sendJson($json);
    }

    private function editSetting($setting, $status = null) {
        $this->load->model('setting/setting');
        $old = $this->model_setting_setting->getSetting('module_progroman_citymanager');
        if (!empty($old['module_progroman_citymanager_setting'])) {
            $setting = array_merge($old['module_progroman_citymanager_setting'], $setting);
        }

        if (is_null($status)) {
            $status = $this->config->get('module_progroman_citymanager_status');
        }

        $this->model_setting_setting->editSetting('module_progroman_citymanager', [
            'module_progroman_citymanager_setting' => $setting,
            'module_progroman_citymanager_status' => $status
        ]);
    }

    private function prepareDomainForRedirect($domain) {
        $domain = strtolower($domain);

        if (strpos($domain, 'http') !== 0) {
            $ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
                || stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true || $_SERVER['SERVER_PORT'] == 443
                || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on');

            $domain = 'http' . ($ssl ? 's' : '') . '://' . $domain;
        }

        $domain = rtrim($domain, '/');

        if (function_exists('idn_to_ascii')) {
            $parts = parse_url($domain);
            if (isset($parts['host']) && preg_match('#[а-яё]#u', $parts['host'])) {
                $scheme = isset($parts['scheme']) ? ($parts['scheme'] . '://') : '';
                $host = idn_to_ascii($parts['host'], IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
                $port = isset($parts['port']) ? (':' . $parts['port']) : '';
                $path = isset($parts['path']) ? $parts['path'] : '';
                $query = empty($parts['query']) ? '' : ('?' . $parts['query']);
                $domain = implode('', [$scheme, $host, $port, $path, $query]);
            }
        }

        return $domain . '/';
    }

    private function hasPermission() {
        if (!$this->user->hasPermission('modify', $this->getModulePath())) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function install() {
        $this->model_citymanager->install();
    }

    /**
     * Действие для файла базы данных
     */
    public function baseAction() {
        if (!$this->hasPermission()) {
            $json['error'] = $this->error['warning'];
            return $this->sendJson($json);
        }

        if (!$this->validLicense()) {
            $json['error'] = $this->language->get('error_license');
            return $this->sendJson($json);
        }

        $parts = explode('/', $this->request->get['action']);
        $class = 'progroman\CityManager\DatabaseFileAction\\' . array_shift($parts);

        $params = [];
        if ($parts) {
            foreach (explode(',', $parts[0]) as $part) {
                $param = explode('=', $part);
                $params[$param[0]] = $param[1];
            }
        }

        /** @var DatabaseFileAction $action */
        $action = new $class($this->language->all());
        $action->setMMC(new MMC(PROGROMAN_CITYMANAGER_DIR . '/public.key', $this->config->get('config_admin_language')));
        $result = $action->step(!empty($this->request->get['step']) ? $this->request->get['step'] : false, $params);

        return $this->sendJson($result);
    }

    private function sendJson($json) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        return;
    }

    private function validLicense() {
        $settings = $this->config->get('module_progroman_citymanager_setting');
        return !empty($settings['secret_key']) && CityManager::validLicense($settings['secret_key']);
    }

    private function initLangVariables(&$args) {
        if (VERSION < 3) {
            $this->load->language($this->getModulePath());

            foreach ($this->language->all() as $key => $value) {
                if (!isset($args[$key])) {
                    $args[$key] = $value;
                }
            }
        }
    }

    private function makeUrl($route, $params = '') {
        $token = VERSION >= 3 ? 'user_token' : 'token';
        return $this->url->link($route, $token . '=' . $this->session->data[$token] . $params, 'SSL');
    }

    private function makeModuleUrl($action = '', $params = '') {
        $action = $action ? '/' . $action : '';
        return str_replace('&amp;', '&', $this->makeUrl($this->getModulePath() . $action, $params));
    }

    private function loadView($view, $data) {
        $view = $this->getModulePath() . '/' . $view;
        if (VERSION < '2.3') {
            $view .= '.tpl';
        }

        if (VERSION >= 2) {
            return $this->load->view($view, $data);
        } else {
            $this->template = $view;
            $this->data = $data;
            return $this->render();
        }
    }

    private function loadController($route) {
        if (VERSION >= 2) {
            return $this->load->controller($route);
        }

        $this->children[] = $route;

        return '';
    }

    private function getModulePath() {
        return (VERSION >= '2.3' ? 'extension/' : '') . 'module/progroman_citymanager';
    }

    private function getExtensionPath() {
        if (VERSION >= 3) {
            return 'marketplace/extension';
        } elseif (VERSION >= '2.3') {
            return 'extension/extension';
        } else {
            return 'extension/module';
        }
    }
}