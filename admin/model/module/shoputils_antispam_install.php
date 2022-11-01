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
class ModelModuleShoputilsAntispamInstall extends Model {
    public function deleteModification($code) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE code = '" . $this->db->escape($code) . "'");
    }
}
?>