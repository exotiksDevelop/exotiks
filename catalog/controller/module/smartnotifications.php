<?php
class ControllerModuleSmartNotifications extends Controller
{
    // Module Unifier
    private $moduleName;
    private $moduleNameSmall;
    private $modulePath;
    private $callModel;
    private $moduleModel;
    private $data = array();
    // Module Unifier
    
    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->config->load('isenselabs/smartnotifications');
        
        /* OC version-specific declarations - Begin */
        $this->moduleName      = $this->config->get('smartnotifications_name');
        $this->moduleNameSmall = $this->config->get('smartnotifications_name_small');
        $this->modulePath      = $this->config->get('smartnotifications_path');
        /* OC version-specific declarations - End */
        
        /* Module-specific declarations - Begin */
        $this->load->language($this->modulePath);
        $this->load->model($this->modulePath);
        $this->callModel   = $this->config->get('smartnotifications_model_call');
        $this->moduleModel = $this->{$this->callModel};
        /* Module-specific declarations - End */
    }
    
    public function index($setting)
    {
        
        if (version_compare(VERSION, '2.2.0.0', '<')) {
            $curent_template = $this->config->get('config_template');
        } else {
            $curent_template = $this->config->get($this->config->get('config_theme') . '_directory');
        }
        
        $this->data['url'] = preg_replace('/https?\:/', '', $this->url->link($this->modulePath . "/getPopup", "", "SSL"));
        
        if (file_exists(DIR_TEMPLATE . $curent_template . '/stylesheet/' . $this->moduleNameSmall . '/animate.css')) {
            //$this->document->addStyle('catalog/view/theme/' . $curent_template . '/stylesheet/' . $this->moduleNameSmall . '/animate.css');
        } else {
            //$this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->moduleNameSmall . '/animate.css');
        }
        
        //$this->document->addScript('catalog/view/javascript/' . $this->moduleNameSmall . '/noty/packaged/jquery.noty.packaged.js');
        //$this->document->addScript('catalog/view/javascript/' . $this->moduleNameSmall . '/noty/themes/smart-notifications.js');
        
        if (file_exists(DIR_TEMPLATE . $curent_template . '/stylesheet/' . $this->moduleNameSmall . '/' . $this->moduleNameSmall . '.css')) {
            //$this->document->addStyle('catalog/view/theme/' . $curent_template . '/stylesheet/' . $this->moduleNameSmall . '/' . $this->moduleNameSmall . '.css');
        } else {
            //$this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->moduleNameSmall . '/' . $this->moduleNameSmall . '.css');
        }
        
        $direction = $this->language->get('direction');
        
        if ($direction == 'rtl') {
            if (file_exists(DIR_TEMPLATE . $curent_template . '/stylesheet/' . $this->moduleNameSmall . '/animate.css')) {
                $this->document->addStyle('catalog/view/theme/' . $curent_template . '/stylesheet/' . $this->moduleNameSmall . '/' . $this->moduleNameSmall . '_rtl.css');
            } else {
                $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->moduleNameSmall . '/' . $this->moduleNameSmall . '_rtl.css');
            }
        }
        
        if (isset($this->request->get['product_id'])) {
            $this->data['product_id'] = $this->request->get['product_id'];
        } else {
            $this->data['product_id'] = 0;
        }
        
        if (version_compare(VERSION, '2.2.0.0', "<")) {
            if (file_exists(DIR_TEMPLATE . $curent_template . '/template/' . $this->modulePath . '.tpl')) {
                return $this->load->view($curent_template . '/template/' . $this->modulePath . '.tpl', $this->data);
            } else {
                return $this->load->view('default/template/' . $this->modulePath . '.tpl', $this->data);
            }
        } else {
            return $this->load->view($this->modulePath . '.tpl', $this->data);
        }
        
    }
    
    protected function showPopup($popup_id)
    {
        if (!isset($this->session->data['popups_repeat']) || !in_array($popup_id, $this->session->data['popups_repeat'])) {
            $this->session->data['popups_repeat'][] = $popup_id;
            return true;
        } else {
            return false;
        }
    }
    
    public function cookieCheck($days, $popup_id)
    {
        if (!isset($_COOKIE["smartnotifications" . $popup_id])) {
            setcookie("smartnotifications" . $popup_id, true, time() + 3600 * 24 * $days);
            return true;
        } else {
            return false;
        }
    }
    
    public function checkCustomerGroup($popup)
    {
        $popup_customer_group = $popup['customerGroups'];
        $customer_group_id    = !is_null($this->customer->getGroupId()) ? $this->customer->getGroupId() : 0;
        return array_key_exists($customer_group_id, $popup_customer_group);
    }
    
    public function timeIsBetween($from, $to, $enabled)
    {
        $date = 'now';
        $date = is_int($date) ? $date : strtotime($date); // convert non timestamps
        $from = is_int($from) ? $from : strtotime($from); // ..
        $to   = is_int($to) ? $to : strtotime($to);
        if ($enabled == "0")
            return true;
        else // ..
            return ($date > $from) && ($date < $to); // extra parens for clarity
    }
    
    private function isHome($uri)
    {
        $parsedURI = parse_url($uri);
        if ((strcmp(HTTP_SERVER, $uri) === 0) || (strcmp(HTTPS_SERVER, $uri) === 0) || (isset($parsedURI['query']) && $parsedURI['query'] == 'route=common/home') || (!isset($parsedURI['query']) && $parsedURI['path'] == '/')) {
            return true;
        } else
            return false;
    }
    
    private function checkRepeatConditions($popup)
    {
        return ($popup['repeat'] == 0) || ($popup['repeat'] == 1 && $this->showPopup($popup['id'], $popup['repeat'])) || ($popup['repeat'] == 2 && $this->cookieCheck($popup['days'], $popup['id']));
    }
    
    public function getPopup()
    {
        header('Access-Control-Allow-Origin: *');
        
        if (isset($this->request->post['product_id'])) {
            $product_id = $this->request->post['product_id'];
        } else {
            $product_id = 0;
        }
        
        if (isset($this->request->post['uri'])) {
            $uri = $this->request->post['uri'];
        } else {
            $uri = "";
        }
        
        if (!isset($this->session->data['popups_repeat']))
            $this->session->data['popups_repeat'] = array();
        
        $date = date('H:i', time());
        $data = $this->config->get('smartnotifications');
        
        $uri = htmlspecialchars_decode((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $this->request->post['uri']);
        
        $this->load->model('catalog/product');
        $categories = $this->model_catalog_product->getCategories($product_id);
        
        $json = array();
        
        if (!empty($data['SmartNotifications'])) {
            foreach ($data['SmartNotifications'] as $popup) {
                
                if ($popup['Enabled'] == "yes" && $this->checkCustomerGroup($popup)) {
                    $range                                                          = explode(",", $popup['random_range']);
                    $popup['title'][$this->config->get('config_language_id')]       = str_replace('%random_value%', rand($range[0], $range[1]), $popup['title'][$this->config->get('config_language_id')]);
                    $popup['description'][$this->config->get('config_language_id')] = str_replace('%random_value%', rand($range[0], $range[1]), $popup['description'][$this->config->get('config_language_id')]);
                    
                    if ($popup['method'] == "0") { // On Homepage method
                        if ($this->timeIsBetween($popup['start_time'], $popup['end_time'], $popup['time_interval'])) {
                            $parsedURI = parse_url($uri);
                            if ($this->isHome($uri)) {
                                if ($this->checkRepeatConditions($popup)) {
                                    $temp['match']           = true;
                                    $temp['popup_id']        = $popup['id'];
                                    $temp['title']           = html_entity_decode($popup['title'][$this->config->get('config_language_id')]);
                                    $temp['description']     = html_entity_decode($popup['description'][$this->config->get('config_language_id')]);
                                    $temp['position']        = $popup['position'];
                                    $temp['event']           = $popup['event'];
                                    $temp['delay']           = $popup['delay'];
                                    $temp['timeout']         = $popup['timeout'];
                                    $temp['open_animation']  = $popup['open_animation'];
                                    $temp['close_animation'] = $popup['close_animation'];
                                    $temp['random_range']    = $popup['random_range'];
                                    $temp['template']        = $popup['template'];
                                    $temp['icon']            = $popup['icon'];
                                    $temp['show_icon']       = $popup['show_icon'];
                                    $temp['icon_type']       = $popup['icon_type'];
                                    $this->load->model('tool/image');
                                    $temp['icon_image'] = !empty($popup['icon_image']) ? $this->model_tool_image->resize($popup['icon_image'], 50, 50) : '';
                                    $json[]             = $temp;
                                    
                                }
                            }
                        }
                    }
                    
                    if ($popup['method'] == "1") { // All pages method
                        if ($this->timeIsBetween($popup['start_time'], $popup['end_time'], $popup['time_interval'])) {
                            $excludedURLs = array();
                            $excludedURLs = array_map("urldecode", preg_split("/\\r\\n|\\r|\\n/", html_entity_decode($popup['excluded_urls'])));
                            if (($this->checkRepeatConditions($popup)) && !in_array($uri, $excludedURLs)) {
                                $temp['match']           = true;
                                $temp['popup_id']        = $popup['id'];
                                $temp['title']           = html_entity_decode($popup['title'][$this->config->get('config_language_id')]);
                                $temp['description']     = html_entity_decode($popup['description'][$this->config->get('config_language_id')]);
                                $temp['position']        = $popup['position'];
                                $temp['event']           = $popup['event'];
                                $temp['delay']           = $popup['delay'];
                                $temp['timeout']         = $popup['timeout'];
                                $temp['open_animation']  = $popup['open_animation'];
                                $temp['close_animation'] = $popup['close_animation'];
                                $temp['random_range']    = $popup['random_range'];
                                $temp['template']        = $popup['template'];
                                $temp['icon']            = $popup['icon'];
                                $temp['show_icon']       = $popup['show_icon'];
                                $temp['icon_type']       = $popup['icon_type'];
                                $this->load->model('tool/image');
                                $temp['icon_image'] = !empty($popup['icon_image']) ? $this->model_tool_image->resize($popup['icon_image'], 50, 50) : '';
                                $json[]             = $temp;
                            }
                        }
                    }
                    
                    if ($popup['method'] == "2") { // Specific URLs method
                        if ($this->timeIsBetween($popup['start_time'], $popup['end_time'], $popup['time_interval'])) {
                            $URLs         = array();
                            $URLs         = array_map("urldecode", preg_split("/\\r\\n|\\r|\\n/", html_entity_decode($popup['url'])));
                            $popup['url'] = htmlspecialchars_decode($popup['url']);
                            foreach ($URLs as $url) {
                                if (strpos($uri, $url) !== false) {
                                    if ($this->checkRepeatConditions($popup)) {
                                        $temp['match']           = true;
                                        $temp['popup_id']        = $popup['id'];
                                        $temp['title']           = html_entity_decode($popup['title'][$this->config->get('config_language_id')]);
                                        $temp['description']     = html_entity_decode($popup['description'][$this->config->get('config_language_id')]);
                                        $temp['position']        = $popup['position'];
                                        $temp['event']           = $popup['event'];
                                        $temp['delay']           = $popup['delay'];
                                        $temp['timeout']         = $popup['timeout'];
                                        $temp['open_animation']  = $popup['open_animation'];
                                        $temp['close_animation'] = $popup['close_animation'];
                                        $temp['random_range']    = $popup['random_range'];
                                        $temp['template']        = $popup['template'];
                                        $temp['icon']            = $popup['icon'];
                                        $temp['show_icon']       = $popup['show_icon'];
                                        $temp['icon_type']       = $popup['icon_type'];
                                        $this->load->model('tool/image');
                                        $temp['icon_image'] = !empty($popup['icon_image']) ? $this->model_tool_image->resize($popup['icon_image'], 50, 50) : '';
                                        $json[]             = $temp;
                                    }
                                }
                            }
                        }
                    }
                    $children = array();
                    
                    if ($popup['method'] == "3") { // Category
                        $cat_match = false;
                        if ($this->timeIsBetween($popup['start_time'], $popup['end_time'], $popup['time_interval'])) {
                            foreach ($categories as $cat) {
                                foreach ($popup['product_category'] as $allowed_cat) {
                                    $this->moduleModel->getChildren($allowed_cat['category_id'], $children);
                                    array_push($children, $allowed_cat['category_id']);
                                    if (in_array($cat['category_id'], $children)) {
                                        $cat_match = true;
                                    }
                                }
                            }
                        }
                        
                        if ($cat_match && $this->checkRepeatConditions($popup)) {
                            $temp['match']           = true;
                            $temp['popup_id']        = $popup['id'];
                            $temp['title']           = html_entity_decode($popup['title'][$this->config->get('config_language_id')]);
                            $temp['description']     = html_entity_decode($popup['description'][$this->config->get('config_language_id')]);
                            $temp['position']        = $popup['position'];
                            $temp['event']           = $popup['event'];
                            $temp['delay']           = $popup['delay'];
                            $temp['timeout']         = $popup['timeout'];
                            $temp['open_animation']  = $popup['open_animation'];
                            $temp['close_animation'] = $popup['close_animation'];
                            $temp['random_range']    = $popup['random_range'];
                            $temp['template']        = $popup['template'];
                            $temp['icon']            = $popup['icon'];
                            $temp['show_icon']       = $popup['show_icon'];
                            $temp['icon_type']       = $popup['icon_type'];
                            $this->load->model('tool/image');
                            $temp['icon_image'] = !empty($popup['icon_image']) ? $this->model_tool_image->resize($popup['icon_image'], 50, 50) : '';
                            $json[]             = $temp;
                        }
                    }
                }
                
            }
        }
        
        $this->response->setOutput(json_encode($json));
    }
}
