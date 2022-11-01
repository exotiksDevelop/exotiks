<?php
/*
 * Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.2.0.x-2.1.x-2.2.x.RUS.TXT
 * Так же лицензионное соглашение можно найти по адресу:
 * https://opencart.market/LICENSE.2.0.x-2.1.x-2.2.x.RUS.TXT
 * 
 * =================================================================
 * OPENCART/ocStore 2.0.x-2.1.x-2.2.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart/ocStore 2.0.x-2.1.x-2.2.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart/ocStore, кроме Opencart/ocStore 2.0.x-2.1.x-2.2.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart/ocStore.
 * =================================================================
*/

class ControllerModuleShoputilsAntispam extends Controller {
    private $error = array();
    protected $version            = '2.3';
    const MAX_LAST_LOG_LINES      = 500;
    const FILE_NAME_LOG           = 'antispam_contact.log';
    const FILE_NAME_REGISTR_LOG   = 'antispam_registration.log';
    const FILE_NAME_AFFILIATE_LOG = 'antispam_affiliate.log';
    const FILE_NAME_LIC           = 'shoputils_antispam.lic';

    public function index() {
        if (!is_file(DIR_APPLICATION . self::FILE_NAME_LIC)) {
            $this->response->redirect($this->url->link('module/shoputils_antispam/lic', '&token=' . $this->session->data['token'], 'SSL'));
        }

        register_shutdown_function(array($this, 'licShutdownHandler'));
        $this->load->model('module/shoputils_antispam');

        $this->load->language('module/shoputils_antispam');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('m_shoputils_antispam', $this->request->post);
            $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));
            $this->response->redirect($this->model_module_shoputils_antispam->makeUrl('extension/module'));
        }

        $this->document->setTitle($this->getHeadingTitle());
        $this->document->addStyle('view/stylesheet/shoputils_antispam.css');

        $data = $this->_setData(array(
            'button_save',
            'button_cancel',
            'button_clear',
            'button_download',
            'tab_general',
            'tab_log',
            'text_enabled',
            'text_disabled',
            'text_confirm',
            'text_loading',
            'text_contact',
            'text_registr',
            'text_affiliate',
            'entry_status',
            'entry_registr_status',
            'entry_affiliate_status',
            'entry_word',
            'entry_ip',
            'entry_not_found',
            'entry_log',
            'entry_log_file',
            'help_word',
            'help_ip',
            'help_not_found',
            'help_log'              => sprintf($this->language->get('help_log'), self::FILE_NAME_LOG),
            'help_registr_log'      => sprintf($this->language->get('help_registr_log'), self::FILE_NAME_REGISTR_LOG),
            'help_affiliate_log'    => sprintf($this->language->get('help_affiliate_log'), self::FILE_NAME_AFFILIATE_LOG),
            'help_log_file'         => sprintf($this->language->get('help_log_file'), self::MAX_LAST_LOG_LINES),
            'heading_title'         => $this->getHeadingTitle(),
            'action'                => $this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam'),
            'cancel'                => $this->model_module_shoputils_antispam->makeUrl('extension/module'),
            'download'              => $this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam/downloadLog'),
            'registr_download'      => $this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam/downloadRegistrLog'),
            'affiliate_download'    => $this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam/downloadAffiliateLog'),
            'clear_log'             => $this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam/clearLog'),
            'registr_clear_log'     => $this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam/clearRegistrLog'),
            'affiliate_clear_log'   => $this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam/clearAffiliateLog'),
            'text_copyright'        => sprintf($this->language->get('text_copyright'), $this->getHeadingTitle(), date('Y', time())),
            'error_warning'         => isset($this->error['warning']) ? $this->error['warning'] : '',
            'version'               => sprintf('%s (%s)', $this->version, defined('ANTISPAM_SETTING') ? (int)strpos(@file_get_contents(DIR_APPLICATION.ANTISPAM_SETTING), 'shoputils_antispam') : 'beta'),
            'icon'                  => 'view/image/module/shoputils_antispam.png',
            'log_filename'          => self::FILE_NAME_LOG,
            'log_registr_filename'  => self::FILE_NAME_REGISTR_LOG,
            'log_affiliate_filename'  => self::FILE_NAME_AFFILIATE_LOG,
            'log_lines'             => $this->readLastLines(DIR_LOGS . self::FILE_NAME_LOG, self::MAX_LAST_LOG_LINES),
            'log_registr_lines'     => $this->readLastLines(DIR_LOGS . self::FILE_NAME_REGISTR_LOG, self::MAX_LAST_LOG_LINES),
            'log_affiliate_lines'   => $this->readLastLines(DIR_LOGS . self::FILE_NAME_AFFILIATE_LOG, self::MAX_LAST_LOG_LINES)
        ));

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }

        $data['logs'] = array(
            '0' => $this->language->get('text_log_off'),
            '1' => $this->language->get('text_log_spam'),
            '2' => $this->language->get('text_log_full'),
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->model_module_shoputils_antispam->makeUrl('common/dashboard'),
            'text'      => $this->language->get('text_home')
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->model_module_shoputils_antispam->makeUrl('extension/module'),
            'text'      => $this->language->get('text_extension')
        );

        $data['breadcrumbs'][] = array(
           'href'      => $this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam'),
           'text'      => $this->getHeadingTitle()
        );

        $data = array_merge($data, $this->_updateData(
            array(
                 'm_shoputils_antispam_contact_status',
                 'm_shoputils_antispam_registr_status',
                 'm_shoputils_antispam_affiliate_status',
                 'm_shoputils_antispam_word',
                 'm_shoputils_antispam_ip',
                 'm_shoputils_antispam_not_found',
                 'm_shoputils_antispam_contact_log',
                 'm_shoputils_antispam_registr_log',
                 'm_shoputils_antispam_affiliate_log'
            )
        ));

        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('module/shoputils_antispam.tpl', $data));
    }

    public function lic() {
        $this->load->language('module/shoputils_antispam');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (!$this->user->hasPermission('modify', 'module/shoputils_antispam')) {
                $this->session->data['warning'] = sprintf($this->language->get('error_permission'), $this->getHeadingTitle());
            } elseif (!empty($this->request->post['lic_data'])) {
                if (!is_writable(DIR_APPLICATION)) {
                    if (function_exists('chmod')) {
                        $perms = fileperms(DIR_APPLICATION);
                        chmod(DIR_APPLICATION, 0777);
                    }
                }

                $lic = '------ LICENSE FILE DATA -------' . "\n";
                $lic .= trim($this->request->post['lic_data']) . "\n";
                $lic .= '--------------------------------' . "\n";
                $file = DIR_APPLICATION . self::FILE_NAME_LIC;
                $handle = @fopen($file, 'w'); 
                fwrite($handle, $lic);
                fclose($handle); 
                if (isset($perms)) {
                    chmod(DIR_APPLICATION, $perms);
                }

                if (!is_file($file)) {
                    $this->session->data['warning'] = sprintf($this->language->get('error_dir_perm'), DIR_APPLICATION);
                    $this->response->redirect($this->url->link('module/shoputils_antispam/lic', '&token=' . $this->session->data['token'], 'SSL'));
                }

                register_shutdown_function(array($this, 'licShutdownHandler'));
                $this->load->model('module/shoputils_antispam');

                $this->response->redirect($this->url->link('module/shoputils_antispam', '&token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $this->document->setTitle($this->getHeadingTitle());

        $domain = str_replace('http://', '', HTTP_SERVER);
        $domain = explode('/', str_replace('https://', '', $domain));

        $loader_min_version = '5.0';
        $loader_version =  function_exists('ioncube_loader_version') ? ioncube_loader_version() : '0';
        $loader_compare = version_compare($loader_version, $loader_min_version, '>=');
        $loader = (bool)$loader_compare;

        $php_min_version = '5.4';
        $php_version  = phpversion();
        $php_compare  = version_compare($php_version, $php_min_version, '>=');
        $file_warning = version_compare($php_version, '7.0', '>=');

        $data = $this->_setData(array(
            'button_save',
            'button_cancel',
            'text_ok',
            'text_error',
            'text_get_key',
            'entry_key',
            'error_key',
            'error_php_version',
            'error_loader'          => sprintf($this->language->get('error_loader'), $loader_min_version),
            'error_loader_version'  => sprintf($this->language->get('error_loader_version'), $loader_min_version),
            'error'                 => !($loader && $loader_compare && $php_compare),
            'heading_title'         => $this->getHeadingTitle(),
            'text_domain'           => sprintf($this->language->get('text_domain'), $domain[0]),
            'text_loader'           => sprintf($this->language->get('text_loader'), $loader_version, $loader_min_version),
            'text_php'              => sprintf($this->language->get('text_php'), $php_version, $php_min_version),
            'text_file_warning'     => sprintf($this->language->get('text_file_warning'), version_compare($php_version, '7.1.0', '<') ? $this->language->get('text_php70') : $this->language->get('text_php71')),
            'action'                => $this->url->link('module/shoputils_antispam/lic', '&token=' . $this->session->data['token'], 'SSL'),
            'cancel'                => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'loader'                => $loader,
            'file_warning'          => $file_warning,
            'icon'                  => 'view/image/module/shoputils_antispam.png',
            'loader_compare'        => $loader_compare,
            'php_compare'           => $php_compare
        ));
        
        if (isset($this->session->data['warning'])) {
          $data['error_warning'] = $this->session->data['warning'];
          unset($this->session->data['warning']);
          if (is_file(DIR_APPLICATION . self::FILE_NAME_LIC)) {
              @unlink(DIR_APPLICATION . self::FILE_NAME_LIC);
          }
        } else {
          $data['error_warning'] = '';
        }

        $data = array_merge($data, $this->_setData(
            array(
                 'header'       => $this->load->controller('common/header'),
                 'column_left'  => $this->load->controller('common/column_left'),
                 'footer'       => $this->load->controller('common/footer')
            )
        ));
        
        $this->response->setOutput($this->load->view('module/shoputils_antispam_lic.tpl', $data));
    }

    public function install() {
        //Uninstall & Del old files from the previous free version 2.2
        $this->load->model('module/shoputils_antispam_install');
        $this->load->model('setting/setting');

        $this->model_setting_setting->deleteSetting('m_shoputils_antispam_contact');
        $this->model_module_shoputils_antispam_install->deleteModification('shoputils_antispam_contact');

        $this->_delFiles(array(
             DIR_APPLICATION . 'controller/module/shoputils_antispam_contact.php',
             DIR_APPLICATION . 'language/en-gb/module/shoputils_antispam_contact.php',
             DIR_APPLICATION . 'language/english/module/shoputils_antispam_contact.php',
             DIR_APPLICATION . 'language/ru-ru/module/shoputils_antispam_contact.php',
             DIR_APPLICATION . 'language/russian/module/shoputils_antispam_contact.php',
             DIR_APPLICATION . 'view/template/module/shoputils_antispam_contact.tpl',
             DIR_CATALOG . 'model/extension/module/shoputils_antispam.php'
        ));
    }

    public function clearLog() {
        $this->load->language('module/shoputils_antispam');
        $this->load->model('module/shoputils_antispam');

        $json = array();

        if ($this->model_module_shoputils_antispam->validatePermission()) {
            if (is_file(DIR_LOGS . self::FILE_NAME_LOG)) {
                @unlink(DIR_LOGS . self::FILE_NAME_LOG);
            }
            $json['success'] = $this->language->get('text_clear_log_success');
        } else {
            $json['error'] = $this->language->get('error_clear_log');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function clearRegistrLog() {
        $this->load->language('module/shoputils_antispam');
        $this->load->model('module/shoputils_antispam');

        $json = array();

        if ($this->model_module_shoputils_antispam->validatePermission()) {
            if (is_file(DIR_LOGS . self::FILE_NAME_REGISTR_LOG)) {
                @unlink(DIR_LOGS . self::FILE_NAME_REGISTR_LOG);
            }
            $json['success'] = $this->language->get('text_clear_log_success');
        } else {
            $json['error'] = $this->language->get('error_clear_log');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function clearAffiliateLog() {
        $this->load->language('module/shoputils_antispam');
        $this->load->model('module/shoputils_antispam');

        $json = array();

        if ($this->model_module_shoputils_antispam->validatePermission()) {
            if (is_file(DIR_LOGS . self::FILE_NAME_AFFILIATE_LOG)) {
                @unlink(DIR_LOGS . self::FILE_NAME_AFFILIATE_LOG);
            }
            $json['success'] = $this->language->get('text_clear_log_success');
        } else {
            $json['error'] = $this->language->get('error_clear_log');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function downloadLog() {
        $file = DIR_LOGS . self::FILE_NAME_LOG;

        if (is_file($file) && filesize($file) > 0) {
            $this->response->addheader('Pragma: public');
            $this->response->addheader('Expires: 0');
            $this->response->addheader('Content-Description: File Transfer');
            $this->response->addheader('Content-Type: application/octet-stream');
            $this->response->addheader('Content-Disposition: attachment; filename="' . self::FILE_NAME_LOG . '"');
            $this->response->addheader('Content-Transfer-Encoding: binary');

            $this->response->setOutput(@file_get_contents($file, FILE_USE_INCLUDE_PATH, null));
        } else {
            $this->load->model('module/shoputils_antispam');
            $this->load->language('module/shoputils_antispam');
            $this->session->data['error'] = sprintf($this->language->get('error_warning'), basename($file));
            $this->response->redirect($this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam'));
        }
    }

    public function downloadRegistrLog() {
        $file = DIR_LOGS . self::FILE_NAME_REGISTR_LOG;

        if (is_file($file) && filesize($file) > 0) {
            $this->response->addheader('Pragma: public');
            $this->response->addheader('Expires: 0');
            $this->response->addheader('Content-Description: File Transfer');
            $this->response->addheader('Content-Type: application/octet-stream');
            $this->response->addheader('Content-Disposition: attachment; filename="' . self::FILE_NAME_REGISTR_LOG . '"');
            $this->response->addheader('Content-Transfer-Encoding: binary');

            $this->response->setOutput(@file_get_contents($file, FILE_USE_INCLUDE_PATH, null));
        } else {
            $this->load->model('module/shoputils_antispam');
            $this->load->language('module/shoputils_antispam');
            $this->session->data['error'] = sprintf($this->language->get('error_warning'), basename($file));
            $this->response->redirect($this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam'));
        }
    }

    public function downloadAffiliateLog() {
        $file = DIR_LOGS . self::FILE_NAME_AFFILIATE_LOG;

        if (is_file($file) && filesize($file) > 0) {
            $this->response->addheader('Pragma: public');
            $this->response->addheader('Expires: 0');
            $this->response->addheader('Content-Description: File Transfer');
            $this->response->addheader('Content-Type: application/octet-stream');
            $this->response->addheader('Content-Disposition: attachment; filename="' . self::FILE_NAME_AFFILIATE_LOG . '"');
            $this->response->addheader('Content-Transfer-Encoding: binary');

            $this->response->setOutput(@file_get_contents($file, FILE_USE_INCLUDE_PATH, null));
        } else {
            $this->load->model('module/shoputils_antispam');
            $this->load->language('module/shoputils_antispam');
            $this->session->data['error'] = sprintf($this->language->get('error_warning'), basename($file));
            $this->response->redirect($this->model_module_shoputils_antispam->makeUrl('module/shoputils_antispam'));
        }
    }

    protected function validate() {
        if (!$this->model_module_shoputils_antispam->validatePermission()) {
            $this->error['warning'] = sprintf($this->language->get('error_permission'), $this->getHeadingTitle());
        }

        return !$this->error;
    }

    protected function getHeadingTitle() {
        return sprintf('%s%s', $this->language->get('heading_title'), ', v' . $this->version);
    }

    function licShutdownHandler() {
        if (@is_array($e = @error_get_last())) {
            $code = isset($e['type']) ? $e['type'] : 0;
            $msg = isset($e['message']) ? $e['message'] : '';
            if (($code > 0) && (strpos($msg, 'requires a license file') || strpos($msg, 'is not valid for this server') || strpos($msg, 'is corrupt'))) {
                $this->session->data['warning'] = $this->language->get('error_key');
                $this->response->redirect($this->url->link('module/shoputils_antispam/lic', '&token=' . $this->session->data['token'], 'SSL'));
            }
        }
    }

    protected function _setData($values) {
        $data = array();
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $data[$value] = $this->language->get($value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    protected function _updateData($keys, $info = array()) {
        $data = array();
        foreach ($keys as $key) {
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } elseif (isset($info[$key])) {
                $data[$key] = $info[$key];
            } else {
                $data[$key] = $this->config->get($key);
            }
        }
        return $data;
    }

    protected function _delFiles($files) {
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    protected function readLastLines($filename, $lines) {
        if (!is_file($filename)) {
            return array();
        }
        $handle = @fopen($filename, "r");
        if (!$handle) {
            return array();
        }
        $linecounter = $lines;
        $pos = -1;
        $beginning = false;
        $text = array();

        while ($linecounter > 0) {
            $t = " ";

            while ($t != "\n") {
                /* if fseek() returns -1 we need to break the cycle*/
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }

            $linecounter--;

            if ($beginning) {
                rewind($handle);
            }

            $text[$lines - $linecounter - 1] = fgets($handle);

            if ($beginning) {
                break;
            }
        }
        fclose($handle);

        return array_reverse($text);
    }
}
?>