<?php
class ControllerModuleGeoip extends Controller {
    const GEOIP_VERSION = '5.2';

	private $error = array();

	public function index() {

		$this->load->language('module/geoip');
        $this->load->model('module/geoip');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title') . ' ' . self::GEOIP_VERSION;

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_edit'] = $this->language->get('text_edit');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_add_rule'] = $this->language->get('button_add_rule');

        $data['entry_set_zone'] = $this->language->get('entry_set_zone');
        $data['entry_from_ajax'] = $this->language->get('entry_from_ajax');
        $data['entry_key'] = $this->language->get('entry_key');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_sort'] = $this->language->get('entry_sort');
        $data['entry_value'] = $this->language->get('entry_value');
        $data['entry_subdomain'] = $this->language->get('entry_subdomain');
        $data['entry_popup_active'] = $this->language->get('entry_popup_active');
        $data['entry_popup_cookie_time'] = $this->language->get('entry_popup_cookie_time');
        $data['entry_popup_view'] = $this->language->get('entry_popup_view');
        $data['entry_currency'] = $this->language->get('entry_currency');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_disable_redirect'] = $this->language->get('entry_disable_redirect');
        $data['entry_domain'] = $this->language->get('entry_domain');
        $data['entry_uid'] = $this->language->get('entry_uid');
        $data['entry_license'] = $this->language->get('entry_license');

        $data['text_popup_cities'] = $this->language->get('text_popup_cities');
        $data['text_regions_info'] = $this->language->get('text_regions_info');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_popup'] = $this->language->get('tab_popup');
        $data['tab_messages'] = $this->language->get('tab_messages');
        $data['tab_redirects'] = $this->language->get('tab_redirects');
        $data['tab_currencies'] = $this->language->get('tab_currencies');
        $data['tab_license'] = $this->language->get('tab_license');
        $data['tab_regions'] = $this->language->get('tab_regions');

        $data['error_license'] = $this->language->get('error_license');

        $this->load->model('localisation/currency');
        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        $this->load->model('localisation/country');
        $data['countries'] = $this->model_localisation_country->getCountries();

        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/geoip', 'token=' . $this->session->data['token'], 'SSL')
		);

        $data['action_general'] = $this->url->link('module/geoip/savegeneral', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_popups'] = $this->url->link('module/geoip/savepopups', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_messages'] = $this->url->link('module/geoip/savemessages', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_redirects'] = $this->url->link('module/geoip/saveredirects', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_currencies'] = $this->url->link('module/geoip/savecurrencies', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_regions'] = $this->url->link('module/geoip/saveregions', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $data['token'] = $this->session->data['token'];

        $data['geoip_setting'] = $this->config->get('geoip_setting') ? $this->config->get('geoip_setting') : array();

        if (empty($data['geoip_setting']['main_domain'])) {
            $data['geoip_setting']['main_domain'] = preg_replace('#^http(s)?://#', '', rtrim(HTTPS_CATALOG, '/'));
        }

        $data['rules'] = $this->model_module_geoip->getRules();

        foreach ($data['rules'] as & $rule) {
            $rule['fias_name'] = $this->model_module_geoip->getFiasName($rule['fias_id']);
        }

        $data['geoip_currencies'] = $this->model_module_geoip->getCurrencies();
        $data['redirects'] = $this->model_module_geoip->getRedirects();

        foreach ($data['redirects'] as & $redirect) {
            $redirect['fias_name'] = $this->model_module_geoip->getFiasName($redirect['fias_id']);
        }

        $data['cities'] = $this->model_module_geoip->getCities();
        $data['country_fias'] = $this->model_module_geoip->getFiasCountries();
        $data['zone_fias'] = $this->model_module_geoip->getFiasRegions();

        $country_zones = array();

        foreach ($data['zone_fias'] as & $row) {
            $country_id = (int)$row['country_id'];
            $country_zones[$country_id] = $this->model_module_geoip->getZonesForCountry($country_id);
        }

        $data['country_zones'] = $country_zones;

        $data['popup_views'] = array(
            'custom' => $this->language->get('text_popup_view_custom'),
            //'bootstrap' => $this->language->get('text_popup_view_bootstrap'),
        );

        $data['check_license'] = !empty($data['geoip_setting']['license']) && GeoIP::validLicense($data['geoip_setting']['license']);

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/geoip.tpl', $data));
	}

    public function search() {
        $json = array();

        if (isset($this->request->get['term'])) {

            $this->load->model('module/geoip');

            $json = $this->model_module_geoip->findFiasByName($this->request->get['term']);
        }

        $this->response->setOutput(json_encode($json));
    }

    public function saveGeneral() {

        $json = array();

        if ($this->validate()) {
            $this->request->post['geoip_setting']['popup_cookie_time'] = (int)$this->request->post['geoip_setting']['popup_cookie_time'];

            if (isset($this->request->post['main_domain'])) {
                $this->request->post['main_domain'] = preg_replace('#^http(s)?://#', '', rtrim(HTTPS_CATALOG, '/'));
            }

            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('geoip', $this->request->post);
        }
        else {
            $json['warning'] = $this->error['warning'];
        }

        $this->response->setOutput(json_encode($json));
    }

    public function savePopups() {

        $json = array();

        if ($this->validate()) {

            $this->load->language('module/geoip');

            if (isset($this->request->post['geoip_city'])) {

                foreach ($this->request->post['geoip_city'] as $key => $value) {
                    if (!(int)$value['fias_id']) {
                        $json['errors']['cities'][$key] = $this->language->get('error_fias');
                    }
                }

                if (empty($json['errors'])) {
                    $this->load->model('module/geoip');
                    $this->model_module_geoip->deleteCities();

                    if (!empty($this->request->post['geoip_city'])) {
                        $this->model_module_geoip->editCities($this->request->post['geoip_city']);
                    }
                }
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function saveMessages() {

        $json = array();

        if ($this->validate()) {

            $this->load->language('module/geoip');

            if (isset($this->request->post['geoip_rule'])) {

                foreach ($this->request->post['geoip_rule'] as $key => $value) {

                    if (!$value['key'] || !preg_match('#^[a-zA-Z0-9_-]*$#', $value['key'])) {
                        $json['errors']['key'][$key] = $this->language->get('error_key');
                    }

                    if (!(int)$value['fias_id']) {
                        $json['errors']['fias'][$key] = $this->language->get('error_fias');
                    }
                }

                if (empty($json['errors'])) {
                    $this->load->model('module/geoip');
                    $this->model_module_geoip->deleteRules();

                    if (!empty($this->request->post['geoip_rule'])) {
                        $this->model_module_geoip->editRules($this->request->post['geoip_rule']);
                    }
                }
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function saveRedirects() {

        $json = array();

        if ($this->validate()) {

            $this->load->language('module/geoip');

            if (isset($this->request->post['geoip_redirect'])) {

                foreach ($this->request->post['geoip_redirect'] as $key => $value) {

                    if (!$value['url']
                        || !preg_match('#^http(s)?://([a-zа-яё0-9]+([\-a-zа-яё0-9]*[a-zа-яё0-9]+)?\.){0,}([a-zа-яё0-9]+([\-a-zа-яё0-9]*[a-zа-яё0-9]+)?){1,63}(\.[a-zа-яё0-9]{2,7})+/(.*/)*$#u', $value['url'])) {
                        $json['errors']['subdomain'][$key] = $this->language->get('error_subdomain');
                    }

                    if (!(int)$value['fias_id']) {
                        $json['errors']['fias'][$key] = $this->language->get('error_fias');
                    }
                }

                if (empty($json['errors'])) {
                    $this->load->model('module/geoip');
                    $this->model_module_geoip->deleteRedirects();

                    if (!empty($this->request->post['geoip_redirect'])) {
                        $this->model_module_geoip->editRedirects($this->request->post['geoip_redirect']);
                    }
                }
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function saveCurrencies() {

        $json = array();

        if ($this->validate()) {
            $this->load->language('module/geoip');

            if (isset($this->request->post['geoip_currency'])) {

                foreach ($this->request->post['geoip_currency'] as $key => $value) {

                    if (!(int)$value['country_id']) {
                        $json['errors']['country'][$key] = $this->language->get('error_currency_country');
                    }

                    if (!$value['code']) {
                        $json['errors']['code'][$key] = $this->language->get('error_currency_code');
                    }
                }

                if (empty($json['errors'])) {
                    $this->load->model('module/geoip');
                    $this->model_module_geoip->deleteCurrencies();

                    if (!empty($this->request->post['geoip_currency'])) {
                        $this->model_module_geoip->editCurrencies($this->request->post['geoip_currency']);
                    }
                }
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function saveRegions() {

        $json = array();

        if ($this->validate()) {
            $this->load->language('module/geoip');
            $this->load->model('module/geoip');

            if (!empty($this->request->post['geoip_country_fias'])) {
                $this->model_module_geoip->deleteCountryToFias();
                $this->model_module_geoip->editCountryToFias($this->request->post['geoip_country_fias']);
            }

            if (!empty($this->request->post['geoip_zone_fias'])) {
                $this->model_module_geoip->deleteZoneToFias();
                $this->model_module_geoip->editZoneToFias($this->request->post['geoip_zone_fias']);
            }

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->setOutput(json_encode($json));
    }

    private function validate() {

        if (!$this->user->hasPermission('modify', 'module/geoip')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}