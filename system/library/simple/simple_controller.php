<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

class SimpleController extends Controller {
    static $_childContent = '';
    private $_opencartVersion = 0;
    private $_currentRoute = '';

    public function __construct($registry) {
        $opencartVersion = explode('.', VERSION);
        $this->_opencartVersion = floatval($opencartVersion[0].$opencartVersion[1].$opencartVersion[2].'.'.(isset($opencartVersion[3]) ? $opencartVersion[3] : 0));

        $this->_currentRoute = isset($_GET['route']) ? $_GET['route'] : (isset($_GET['_route_']) ? $_GET['_route_'] : '');

        parent::__construct($registry);
    }

    protected function loadLibrary($library) {
        $file = DIR_SYSTEM . 'library/' . $library . '.php';

        if (file_exists($file)) {
            if ($this->_opencartVersion < 200) {
                include_once($file);
            } else {
                include_once(modification($file));
            }
        } else {
            trigger_error('Error: Could not load library ' . $file . '!');
            exit();
        }
    }

    public function renderPage($template, $templateData, $childrens = array()) {
        if ($this->_opencartVersion < 200) {
            $this->data = array_merge((isset($this->data) && is_array($this->data)) ? $this->data : array(), $templateData);

            $this->data['current_page_route'] = $this->_currentRoute;

            if (!empty($childrens)) {
                $this->children = $childrens;
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$template.'.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/'.$template.'.tpl';
            } else {
                $this->template = 'default/template/'.$template.'.tpl';
            }

            return $this->render();
        } else {
            foreach ($childrens as $child) {
                $templateData[substr($child, strpos($child, '/') + 1)] = $this->load->controller($child);
            }

            $templateData['current_page_route'] = $this->_currentRoute;

            if ($this->_opencartVersion < 220) {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$template.'.tpl')) {
                    return $this->load->view($this->config->get('config_template') . '/template/'.$template.'.tpl', $templateData);
                } else {
                    return $this->load->view('default/template/'.$template.'.tpl', $templateData);
                }
            } else {
                return $this->load->view($template, $templateData);
            }
        }
    }

    public function setOutputContent($content) {
        $this->response->setOutput($content);

        self::$_childContent = $content;

        return $content;
    }

    public function getChildController($action, $params = array()) {
        self::$_childContent = '';
        $returnContent = '';

        if ($this->_opencartVersion < 200) {
            $returnContent = $this->getChild($action, $params);
        } elseif ($this->_opencartVersion < 230) {
            $returnContent = $this->load->controller($action, $params);
        } else {
            if (strpos($action, 'module/') === 0 || strpos($action, 'payment/') === 0) {
                $action = 'extension/'.$action;
            }

            $returnContent = $this->load->controller($action, $params);
        }

        if ($returnContent && !self::$_childContent) {
            self::$_childContent = $returnContent;
        }

        $tmp = self::$_childContent;
        self::$_childContent = '';

        return $tmp;
    }
}