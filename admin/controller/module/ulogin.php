<?php

/*
 * @copyright 25.10.2014
 */

/**
 * Description of ulogin
 *
 * @author Bruhanda V.V. (bruhanda@gmail.com)
 */
class ControllerModuleUlogin extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('module/ulogin');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('ulogin', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_template'] = $this->language->get('text_template');
        
        $data['text_displayed'] = $this->language->get('text_displayed');
        $data['text_displayed_when_the_mouse'] = $this->language->get('text_displayed_when_the_mouse');
        
//        $data['text_content_top'] = $this->language->get('text_content_top');
//        $data['text_content_bottom'] = $this->language->get('text_content_bottom');
//        $data['text_column_left'] = $this->language->get('text_column_left');
//        $data['text_column_right'] = $this->language->get('text_column_right');
//
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_position'] = $this->language->get('entry_position');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_module_add'] = $this->language->get('button_module_add');
        $data['button_remove'] = $this->language->get('button_remove');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/ulogin', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('module/ulogin', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/ulogin', 'token=' . $this->session->data['token'], 'SSL');

        /*
         * ulogin config 
         */
        $data['ulogin']['providers'] = array(
            'yandex',
            'google',
            'vkontakte',
            'facebook',
            'twitter',
            'openid',
            'liveid',
            'soundcloud',
            'vimeo',
            'youtube',
            'mailru',
            'instagram',
            'odnoklassniki',
            'steam',
            'lastfm',
            'linkedin',
            'flickr',
            'uid',
            'livejournal',
            'webmoney',
            'foursquare',
            'tumblr',
            'googleplus',
            'dudu',
            'wargaming',
        );
        $data['ulogin']['type'] = array(
            'small',
            'panel',
            'window',
        );


        if (isset($this->request->post['ulogin_status'])) {
            $data['ulogin_status'] = $this->request->post['ulogin_status'];
        }
        elseif ($this->config->get('ulogin_status')) {
            $data['ulogin_status'] = $this->config->get('ulogin_status');
        }
        
        if (isset($this->request->post['ulogin_module'])) {
            $data['module'] = $this->request->post['ulogin_module'];
        }
        elseif ($this->config->get('ulogin_module')) {
            $data['module'] = $this->config->get('ulogin_module');
        }
        
        if (isset($this->request->post['ulogin_providers'])) {
            $data['providers'] = $this->request->post['ulogin_providers'];
        }
        elseif ($this->config->get('ulogin_providers')) {
            $data['providers'] = $this->config->get('ulogin_providers');
        }
        
        if (isset($this->request->post['ulogin_hidden'])) {
            $data['hidden'] = $this->request->post['ulogin_hidden'];
        }
        elseif ($this->config->get('ulogin_hidden')) {
            $data['hidden'] = $this->config->get('ulogin_hidden');
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/ulogin.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/ulogin')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['ulogin_module'])) {
            foreach ($this->request->post['ulogin_module'] as $key => $value) {

            }
        }

        return !$this->error;
    }

}
