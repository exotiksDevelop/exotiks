<?php
class BBPayment extends Controller {

    private $error = array();
    private $_data = array();

    private function getLink($ov, $url) {
        if ($ov >= 230)
            return $this->url->link($url, 'token=' . $this->session->data['token'] . '&type=payment', true);
        return ($ov < 220) ?
            $this->url->link($url, 'token=' . $this->session->data['token'], 'SSL') :
            $this->url->link($url, 'token=' . $this->session->data['token'], true);
    }

    private function getHomeLink($ov) {
        if ($ov < 200) return $this->getLink($ov, 'common/home'); else return $this->getLink($ov, 'common/dashboard');
    }

    private function initField($field_name, $default_value = '') {
        if (isset($this->request->post[$field_name])) {
            $this->_data[$field_name] = $this->request->post[$field_name];
        } elseif (!is_null($this->config->get($field_name))) {
            $this->_data[$field_name] = $this->config->get($field_name);
        } else {
            $this->_data[$field_name] = $default_value;
        }
    }

    private function getModuleRoute($ov) {
        return ($ov < 230) ? "payment/bb_payment" : "extension/payment/bb_payment";
    }

    private function getModulesRoute($ov) {
        return ($ov < 230) ? "extension/payment" : "extension/extension";
    }

    private function doRedirect($ov, $url) {
        if ($ov < 200) {
            $this->redirect($url);
        } else {
            $this->response->redirect($url);
        }
    }

    private function renderPage($ov, $template) {

        $this->_data['old'] = ($ov < 200);

        if ($this->_data['old']) {

            $this->data = array_merge($this->data, $this->_data);

            $this->template = $template.'.tpl';

            $this->children = array(
                'common/header',
                'common/footer'
            );

            $this->response->setOutput($this->render());

        } else
        {

            $this->_data['header']      = $this->load->controller('common/header');
            $this->_data['column_left'] = $this->load->controller('common/column_left');
            $this->_data['footer']      = $this->load->controller('common/footer');

            if ($ov < 220) $template .= '.tpl';

            $this->response->setOutput($this->load->view($template, $this->_data));
        }
    }

    public function index() {
        $ov = explode('.', VERSION);
        $opencartVersion = floatval($ov[0].$ov[1].$ov[2].'.'.(isset($ov[3]) ? $ov[3] : 0));

        $this->language->load($this->getModuleRoute($opencartVersion));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate($opencartVersion))) {
            $this->model_setting_setting->editSetting('bb_payment', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->doRedirect($opencartVersion, $this->getLink($opencartVersion, $this->getModulesRoute($opencartVersion)));
        }

        $this->_data['heading_title']      = $this->language->get('heading_title');

        $this->_data['text_enabled']       = $this->language->get('text_enabled');
        $this->_data['text_disabled']      = $this->language->get('text_disabled');

        $this->_data['entry_order_status'] = $this->language->get('entry_order_status');
        $this->_data['entry_status']       = $this->language->get('entry_status');
        $this->_data['entry_sort_order']   = $this->language->get('entry_sort_order');
        $this->_data['entry_use_total']    = $this->language->get('entry_use_total');
        $this->_data['entry_total_from_hint']  = $this->language->get('entry_total_from_hint');
        $this->_data['entry_total_to_hint']    = $this->language->get('entry_total_to_hint');

        $this->_data['button_save']        = $this->language->get('button_save');
        $this->_data['button_cancel']      = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->_data['error_warning'] = $this->error['warning'];
        } else {
            $this->_data['error_warning'] = '';
        }

        $this->_data['breadcrumbs'] = array();

        $this->_data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->getHomeLink($opencartVersion),
            'separator' => false
        );

        $this->_data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->getLink($opencartVersion, $this->getModulesRoute($opencartVersion)),
            'separator' => ' :: '
        );

        $this->_data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->getLink($opencartVersion, $this->getModuleRoute($opencartVersion)),
            'separator' => ' :: '
        );

        $this->_data['action'] = $this->getLink($opencartVersion, $this->getModuleRoute($opencartVersion));

        $this->_data['cancel'] = $this->getLink($opencartVersion, $this->getModulesRoute($opencartVersion));

        $this->load->model('localisation/order_status');

        $this->_data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->initField('bb_payment_order_status_id');
        $this->initField('bb_payment_status');
        $this->initField('bb_payment_sort_order');
        $this->initField('bb_payment_total_to', 0);
        $this->initField('bb_payment_total_from', 0);
        if ($opencartVersion < 230)
            $this->renderPage($opencartVersion, 'payment/bb');
        else
            $this->renderPage($opencartVersion, 'extension/payment/bb');
    }

    private function validate($ov) {
        if (!$this->user->hasPermission('modify', $this->getModuleRoute($ov))) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
class ControllerExtensionPaymentBBPayment extends BBPayment {
}
class ControllerPaymentBBPayment extends BBPayment {
}
?>