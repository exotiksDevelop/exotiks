<?php 
class ControllerPaymentSbrfOnline extends Controller {
    private $error = array(); 
    private $data = array(); 

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('payment/sbrf_online');
    }

    public function index() {
        $this->load->model('localisation/language');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->_trimData(array(
                 'sbrf_online_minimal_order',
                 'sbrf_online_maximal_order'
            ));

            $this->_replaceData(',', '.', array(
                 'sbrf_online_minimal_order',
                 'sbrf_online_maximal_order'
            ));

            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('sbrf_online', $this->request->post);
            $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));
            $this->response->redirect($this->makeUrl('extension/payment'));
        }

        $host = defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTP_CATALOG;
        $this->_setData(array(
            'heading_title',
            'button_save',
            'button_cancel',
            'text_yes',
            'text_no',
            'text_enabled',
            'text_disabled',
            'text_all_zones',
            'text_edit',
            'text_bank_default',
            'entry_bank',
            'entry_page_success',
            'entry_title',
            'entry_description',
            'entry_icon',
            'entry_minimal_order',
            'entry_maximal_order',
            'entry_order_status',
            'entry_geo_zone',
            'entry_status',
            'entry_sort_order',
            'help_bank',
            'help_page_success',
            'help_title',
            'help_description',
            'help_minimal_order',
            'help_maximal_order',
            'title_default',
            'action'          => $this->makeUrl('payment/sbrf_online'),
            'cancel'          => $this->makeUrl('extension/payment'),
            'text_copyright'  => sprintf($this->language->get('text_copyright'), $this->language->get('heading_title'), date('Y', time())),
            'text_page_success_default'  => sprintf($this->language->get('text_page_success_default'), $host . 'index.php?route=account/account', $host . 'index.php?route=account/order', $host . 'index.php?route=account/download', $host . 'index.php?route=information/contact'),
            'token'           => isset($this->session->data['token']) ? $this->session->data['token'] : '',
            'error_warning'   => isset($this->error['warning']) ? $this->error['warning'] : '',
            'error_bank'      => isset($this->error['error_bank']) ? $this->error['error_bank'] : '',
            'geo_zones'       => $this->model_localisation_geo_zone->getGeoZones(),
            'order_statuses'  => $this->model_localisation_order_status->getOrderStatuses(),
            'languages'       => $this->model_localisation_language->getLanguages()
        ));
        
        $languages = $this->model_localisation_language->getLanguages();
        foreach ($languages as $language) {
            $this->data['error_bank_' . $language['language_id']] = isset($this->error['bank_' . $language['language_id']]) ? $this->error['bank_' . $language['language_id']] : '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('common/dashboard'),
            'text' => $this->language->get('text_home')
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('extension/payment'),
            'text' => $this->language->get('text_payment')
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('payment/sbrf_online'),
            'text' => $this->language->get('heading_title')
        );

        $this->_updateData(array(
            'sbrf_online_bank',
            'sbrf_online_page_success',
            'sbrf_online_icon',
            'sbrf_online_minimal_order',
            'sbrf_online_maximal_order',
            'sbrf_online_order_status_id',
            'sbrf_online_geo_zone_id',
            'sbrf_online_status',
            'sbrf_online_sort_order',
            'sbrf_online_langdata'
        ));

        $this->_setData(array(
            'header'       => $this->load->controller('common/header'),
            'column_left'  => $this->load->controller('common/column_left'),
            'footer'       => $this->load->controller('common/footer')
        ));
            
        $this->response->setOutput($this->load->view('payment/sbrf_online.tpl', $this->data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/sbrf_online')) {
          $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->model_localisation_language->getLanguages() as $language) {
            if (!isset($this->request->post['sbrf_online_bank'][$language['language_id']]) || !trim($this->request->post['sbrf_online_bank'][$language['language_id']])) {
                $this->error['warning'] = $this->error['error_bank'] = $this->language->get('error_bank');
            }
        }

        return !$this->error;
    }

    protected function _setData($values) {
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $this->data[$value] = $this->language->get($value);
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    protected function _updateData($keys, $info = array()) {
        foreach ($keys as $key) {
            if (isset($this->request->post[$key])) {
                $this->data[$key] = $this->request->post[$key];
            } elseif ($this->config->get($key)) {
                $this->data[$key] = $this->config->get($key);
            } elseif (isset($info[$key])) {
                $this->data[$key] = $info[$key];
            } else {
                $this->data[$key] = null;
            }
        }
    }

    protected function _trimData($values) {
        foreach ($values as $value) {
                if (isset($this->request->post[$value])) {
                    $this->request->post[$value] = trim($this->request->post[$value]);
                }
        }
    }

    protected function _replaceData($search, $replace, $values) {
        foreach ($values as $value) {
                if (isset($this->request->post[$value])) {
                    $this->request->post[$value] = str_replace($search, $replace, $this->request->post[$value]);
                }
        }
    }

    protected function makeUrl($route, $url = '') {
        return str_replace('&amp;', '&', $this->url->link($route, $url . '&token=' . $this->session->data['token'], 'SSL'));
    }
}
?>