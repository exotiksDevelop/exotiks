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
    public function index() {}

    public function antispam() {
        if (isset($this->request->get['info']) && (md5($this->request->get['info']) == 'caf9b6b99962bf5c2264824231d7a40c')) {
            $oc = VERSION;
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
            echo sprintf('%s v%s %s %s: %s %s %s', $oc, $this->config->get('m_shoputils_antispam_version'), phpversion(), ioncube_loader_version(), $this->config->get('m_shoputils_antispam_contact_status'), $this->config->get('m_shoputils_antispam_registr_status'), $this->config->get('m_shoputils_antispam_affiliate_status'));
        }
    }
}
?>