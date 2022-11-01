<?php

class ControllerExtensionModuleDwebExporter extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('extension/module/dwebexporter');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
        {
            $this->model_setting_setting->editSetting('dwebexporter', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning']))
        {
            $data['error_warning'] = $this->error['warning'];
        }
        else
        {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/dwebexporter', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/module/dwebexporter', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

        if (isset($this->request->post['dwebexporter_status']))
        {
            $data['dwebexporter_status'] = $this->request->post['dwebexporter_status'];
        }
        else
        {
            $data['dwebexporter_status'] = $this->config->get('dwebexporter_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/dwebexporter', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/dwebexporter'))
        {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function install()
    {
        $this->load->model('extension/extension');
        $this->model_extension_extension->install('dwebexporter', $this->request->get['extension']);

        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'dwebexporter/dwebexporter/' . $this->request->get['extension']);
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'dwebexporter/dwebexporter/' . $this->request->get['extension']);

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "dweb_exporting` (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) DEFAULT NULL,
                `export_id` varchar(255) DEFAULT NULL,
                `categories` varchar(255) DEFAULT NULL,
                `min_qty` int(10) DEFAULT 0,
                `language` int(10),
                `all` tinyint(1) DEFAULT '0',
                `use_custom_parser` tinyint(1) DEFAULT '0',
                `custom_parser` text,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");

        // Call install method if it exsits
        $this->load->controller('extension/dwebexporter/' . $this->request->get['extension'] . '/install');

        $this->session->data['success'] = $this->language->get('text_success');
    }

    public function uninstall()
    {
        $this->db->query("DROP TABLE `" . DB_PREFIX . "dweb_exporting`");
        $this->cache->delete('dwebexporter');
    }

}
