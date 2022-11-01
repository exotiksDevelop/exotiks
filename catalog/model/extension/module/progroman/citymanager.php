<?php

/**
 * Class ModelExtensionModuleProgromanCityManager
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class ModelExtensionModuleProgromanCityManager extends Model {

    public function getCities($ext = []) {
        $join = '';
        $fields = 'c.*';

        $ext = array_flip($ext);
        if (isset($ext['redirect'])) {
            $join .= "LEFT JOIN prmn_cm_redirect r USING (fias_id)\n";
            $fields .= ', r.url';
        }

        return $this->db->query("SELECT " . $fields . " FROM prmn_cm_city c\n" . $join . "ORDER BY sort, name")->rows;
    }

    public function getCityById($fias_id) {
        return $this->db->query("SELECT * FROM prmn_cm_city WHERE fias_id = " . (int)$fias_id)->row;
    }

    public function getMessages($fias_ids = []) {
        if ($fias_ids) {
            $where = $fias_ids ? ' WHERE fias_id IN (' . implode(',', $fias_ids) . ')' : '';
            return $this->db->query("SELECT * FROM prmn_cm_message" . $where)->rows;
        }

        return [];
    }

    public function getRedirects() {
        return $this->db->query("SELECT * FROM prmn_cm_redirect")->rows;
    }

    public function getCurrencyForCountry($country_id) {
        $row = $this->db->query("SELECT code FROM prmn_cm_currency WHERE country_id = " . (int)$country_id)->row;
        return $row ? $row['code'] : false;
    }
}