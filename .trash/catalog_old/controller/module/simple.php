<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerModuleSimple extends SimpleController {
    private $_templateData = array();

    public function index($setting) {
        if (empty($setting)) {
            return;
        }

        $opencartVersion = explode('.', VERSION);
        $opencartVersion = floatval($opencartVersion[0].$opencartVersion[1].$opencartVersion[2].'.'.(isset($opencartVersion[3]) ? $opencartVersion[3] : 0));


        $route = '';

        if (!empty($setting['page'])) {
            if ($setting['page'] == 'checkout') {
                $route = 'checkout/simplecheckout';
            } elseif ($setting['page'] == 'register') {
                $route = 'account/simpleregister';
            }
        }

        if ($route && empty($setting['scripts'])) {
            $this->_templateData['simple_content'] = $this->getChildController($route, array('module' => true, 'group' => (!empty($setting['settingsId']) ? $setting['settingsId'] : $this->config->get('simple_default_checkout_group'))));

            return $this->setOutputContent($this->renderPage($opencartVersion < 230 ? 'module/simple' : 'extension/module/simple', $this->_templateData));
        } elseif (!empty($setting['scripts'])) {
            if ($route == 'checkout/simplecheckout') {
                $this->loadLibrary('simple/simplecheckout');
                SimpleCheckout::getInstance($this->registry, (!empty($setting['settingsId']) ? $setting['settingsId'] : $this->config->get('simple_default_checkout_group')));
            } elseif ($route == 'account/simpleregister') {
                $this->loadLibrary('simple/simpleregister');
                SimpleRegister::getInstance($this->registry);
            }
        }
    }
}

class ControllerExtensionModuleSimple extends ControllerModuleSimple {}