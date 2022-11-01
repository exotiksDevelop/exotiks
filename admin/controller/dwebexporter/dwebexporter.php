<?php

class ControllerDwebExporterDwebExporter extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('catalog/product');
        $this->load->language('dwebexporter/dwebexporter');
        $this->document->setTitle('Exporting');
        $this->load->model('dwebexporter/dwebexporter');

        $this->getList();
    }

    // <editor-fold desc="Add" defaultstate="collapsed">

    public function add()
    {
        $this->load->language('catalog/product');
        $this->load->language('dwebexporter/dwebexporter');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('dwebexporter/dwebexporter');

        // <editor-fold desc="POST" defaultstate="collapsed">

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
        {
            $this->model_dwebexporter_dwebexporter->addExporting($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('dwebexporter/dwebexporter', 'token=' . $this->session->data['token'], true));
        }

        // </editor-fold>

        $this->getForm();
    }

    // </editor-fold>

    /* ============================================== */

    // <editor-fold desc="Edit" defaultstate="collapsed">

    public function edit()
    {
        $this->load->language('catalog/product');
        $this->load->language('dwebexporter/dwebexporter');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('dwebexporter/dwebexporter');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
        {
            $this->model_dwebexporter_dwebexporter->editExporting($this->request->get['id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $url = '';

            $this->response->redirect($this->url->link('dwebexporter/dwebexporter', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getForm();
    }

    // </editor-fold>

    /* ============================================== */

    // <editor-fold desc="Delete" defaultstate="collapsed">

    public function delete()
    {
        $this->load->language('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('dwebexporter/dwebexporter');

        if (isset($this->request->post['selected']))
        {
            foreach ($this->request->post['selected'] as $id)
            {
                $this->model_dwebexporter_dwebexporter->deleteExporting($id);
            }
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('dwebexporter/dwebexporter', 'token=' . $this->session->data['token'], true));
        }

        $this->getList();
    }

    // </editor-fold>

    /* ============================================== */

    // <editor-fold desc="Get List" defaultstate="collapsed">

    protected function getList()
    {
        $url = '';
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('dwebexporter/dwebexporter', 'token=' . $this->session->data['token'] . $url, true)
        );

        $data['add'] = $this->url->link('dwebexporter/dwebexporter/add', 'token=' . $this->session->data['token'] . $url, true);
        $data['delete'] = $this->url->link('dwebexporter/dwebexporter/delete', 'token=' . $this->session->data['token'] . $url, true);

        $data['exportings'] = array();
        $results = $this->model_dwebexporter_dwebexporter->getExportings(array());

        foreach ($results as $result)
        {

            $data['exportings'][] = array(
                'id' => $result['id'],
                'name' => $result['name'],
                'export_id' => $result['export_id'],
                'edit' => $this->url->link('dwebexporter/dwebexporter/edit', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, true)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('heading_title');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_action'] = $this->language->get('column_action');
        $data['entry_name'] = $this->language->get('entry_name');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning']))
        {
            $data['error_warning'] = $this->error['warning'];
        }
        else
        {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success']))
        {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else
        {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected']))
        {
            $data['selected'] = (array) $this->request->post['selected'];
        }
        else
        {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['page']))
        {
            $page = $this->request->get['page'];
        }
        else
        {
            $page = 1;
        }

        $records_total = count($results);
        $pagination = new Pagination();
        $pagination->total = $records_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('dwebexporter/dwebexporter', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($records_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($records_total - $this->config->get('config_limit_admin'))) ? $records_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $records_total, ceil($records_total / $this->config->get('config_limit_admin')));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('dwebexporter/dwebexporter', $data));
    }

    // </editor-fold>

    /* ============================================== */

    // <editor-fold desc="Get Form & Validate" defaultstate="collapsed">


    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_plus'] = $this->language->get('text_plus');
        $data['text_minus'] = $this->language->get('text_minus');
        $data['text_min_qty'] = $this->language->get('text_min_qty');
        $data['text_language'] = $this->language->get('text_language');
        $data['text_use_all'] = $this->language->get('text_use_all');
        $data['text_export_id'] = $this->language->get('text_export_id');
        $data['text_custom_mapper'] = $this->language->get('text_export_id');
        $data['text_custom_parser'] = $this->language->get('text_custom_parser');
        $data['text_use_custom_parser'] = $this->language->get('text_use_custom_parser');

        $data['help_export_id'] = $this->language->get('help_export_id');
        $data['help_use_all'] = $this->language->get('help_use_all');
        $data['help_categories'] = $this->language->get('help_categories');
        $data['help_min_qty'] = $this->language->get('help_min_qty');

        $data['entry_name'] = $this->language->get('column_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_category'] = $this->language->get('entry_category');

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

        $url = '';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('dwebexporter/dwebexporter', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (!isset($this->request->get['id']))
        {
            $data['action'] = $this->url->link('dwebexporter/dwebexporter/add', 'token=' . $this->session->data['token'] . $url, true);
        }
        else
        {
            $data['action'] = $this->url->link('dwebexporter/dwebexporter/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('dwebexporter/dwebexporter', 'token=' . $this->session->data['token'] . $url, true);

        $exporting = array();
        if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST'))
        {
            $exporting = $this->model_dwebexporter_dwebexporter->getExporting($this->request->get['id']);
        }

        $data['token'] = $this->session->data['token'];
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['name'] = $this->getField('name', $exporting);
        $data['export_id'] = $this->getField('export_id', $exporting);
        $data['all'] = $this->getField('all', $exporting);
        $data['language'] = $this->getField('language', $exporting);
        $data['min_qty'] = $this->getField('min_qty', $exporting);
        $data['use_custom_parser'] = $this->getField('use_custom_parser', $exporting);
        $data['custom_parser'] = $this->getField('custom_parser', $exporting);

        $data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';
        $data['error_export_id'] = isset($this->error['export_id']) ? $this->error['name'] : '';

        $data['export_url'] = HTTPS_CATALOG . 'index.php?route=dwebexporter/exporting&id=' . $data['export_id'];
        $data['export_base_url'] = HTTPS_CATALOG . 'index.php?route=dwebexporter/exporting&id=';


        // <editor-fold desc="Categories" defaultstate="collapsed">

        $this->load->model('catalog/category');
        $categories = array();

        if (isset($this->request->post['exporting_category']))
        {
            $categories = $this->request->post['exporting_category'];
        }
        elseif (isset($this->request->get['id']) && isset($exporting['categories']))
        {
            $categories = explode(',', $exporting['categories']);
        }
        else
        {
            $categories = array();
        }

        $data['exporting_categories'] = array();

        foreach ($categories as $category_id)
        {
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info)
            {
                $data['exporting_categories'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                );
            }
        }
        // </editor-fold>

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('dwebexporter/dwebexporter_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'dwebexporter/dwebexporter'))
        {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($this->error && !isset($this->error['warning']))
        {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if ($this->request->post['name'] == '')
        {
            $this->error['name'] = $this->language->get('entry_required');
        }
        
         if ($this->request->post['export_id'] == '')
        {
            $this->error['export_id'] = $this->language->get('entry_required');
        }

        return !$this->error;
    }

    // </editor-fold>

    /* ============================================== */

    // <editor-fold desc="Categories Autocomplete" defaultstate="collapsed">

    public function categoriesautocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name']))
        {
            $this->load->model('catalog/category');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'sort' => 'name',
                'order' => 'ASC',
                'start' => 0,
                'limit' => 15
            );

            $results = $this->model_catalog_category->getCategories($filter_data);

            foreach ($results as $result)
            {
                $json[] = array(
                    'category_id' => $result['category_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value)
        {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    // </editor-fold>

    /* ============================================== */

    // <editor-fold desc="Reset Parser" defaultstate="collapsed">

    public function resetparser()
    {
        $json = array();
        $content = "";

        if (file_exists(DIR_APPLICATION . '/model/dwebexporter/defaultParser.xml'))
        {
            $content = file_get_contents(DIR_APPLICATION . '/model/dwebexporter/defaultParser.xml');
        }

        $json = array('content' => $content);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    // </editor-fold>

    /* ============================================== */

    // <editor-fold desc="Get Field" defaultstate="collapsed">

    private function getField($name, $exporting)
    {
        if (isset($this->request->post[$name]))
        {
            return $this->request->post[$name];
        }
        elseif (isset($this->request->get['id']) && isset($exporting[$name]))
        {
            return $exporting[$name];
        }
        else
        {
            return '';
        }
    }

    // </editor-fold>
}
