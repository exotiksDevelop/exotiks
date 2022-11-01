<?php

/**
 * Class ModelExtensionModuleProgromanCityManager
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class ModelExtensionModuleProgromanCityManager extends Model {

    const LEVEL_COUNTRY = 0;
    const LEVEL_REGION = 1;

    public function getCities() {
        return $this->db->query("SELECT * FROM prmn_cm_city ORDER BY sort, name")->rows;
    }

    public function getMessages($data) {
        $sql = "SELECT * FROM prmn_cm_message ORDER BY `key`";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        return $this->db->query($sql)->rows;
    }

    public function getTotalMessages() {
        return $this->db->query("SELECT COUNT(*) total FROM prmn_cm_message")->row['total'];
    }

    public function getRedirects() {
        return $this->db->query("SELECT * FROM prmn_cm_redirect")->rows;
    }

    public function getCurrencies() {
        return $this->db->query("SELECT * FROM prmn_cm_currency")->rows;
    }

    public function getInstalledCountries() {
        $countries = [];
        foreach ($this->db->query("SELECT fias_id FROM fias WHERE level = 0")->rows as $row) {
            $countries[] = $row['fias_id'];
        }

        return $countries;
    }

    public function countCitiesRu() {
        return $this->db->query("SELECT COUNT(*) total FROM fias WHERE fias_id < 300000")->row['total'];
    }

    public function editCities($cities) {
        $query = "INSERT INTO prmn_cm_city (`fias_id`, `name`, `sort`) VALUES\n";
        $values = [];

        foreach ($cities as $city) {
            $values[] = "(" . (int)$city['fias_id'] . ", '" . $this->db->escape($city['name']) . "', " . (int)$city['sort'] . ")";
        }

        $this->db->query($query . implode(", ", $values) . "\nON DUPLICATE KEY UPDATE name = name");
    }

    public function addMessage($fias_id, $key, $value) {
        $this->db->query("INSERT INTO prmn_cm_message (`fias_id`, `key`, `value`) VALUES (" . (int)$fias_id . ", '" . $this->db->escape($key) . "', '" . $this->db->escape($value) . "')");
    }

    public function editMessage($id, $fias_id, $key, $value) {
        $this->db->query("UPDATE prmn_cm_message SET `fias_id` = '" . (int)$fias_id . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "' WHERE id = " . (int)$id);
    }

    public function removeMessage($id) {
        $this->db->query("DELETE FROM prmn_cm_message WHERE id = " . (int)$id);
    }

    public function editRedirects($redirects) {
        $query = "INSERT INTO prmn_cm_redirect (`fias_id`, `url`) VALUES\n";
        $values = [];

        foreach ($redirects as $redirect) {
            $values[] = "(" . (int)$redirect['fias_id'] . ", '" . $this->db->escape($redirect['url']) . "')";
        }

        $this->db->query($query . implode(", ", $values));
    }

    public function editCurrencies($currencies) {
        $query = "INSERT INTO prmn_cm_currency (`country_id`, `code`) VALUES\n";
        $values = [];

        foreach ($currencies as $currency) {
            $values[] = "(" . (int)$currency['country_id'] . ", '" . $this->db->escape($currency['code']) . "')";
        }

        $this->db->query($query . implode(", ", $values));
    }

    public function clearCities() {
        $this->db->query("TRUNCATE prmn_cm_city");
    }

    public function clearRedirects() {
        $this->db->query("TRUNCATE prmn_cm_redirect");
    }

    public function clearCurrencies() {
        $this->db->query("TRUNCATE prmn_cm_currency");
    }

    public function getFiasName($fiasId) {
        $row = $this->db->query("SELECT CONCAT_WS(' ', shortname, offname) name FROM fias WHERE fias_id = " . (int)$fiasId)->row;
        return $row ? $row['name'] : null;
    }

    public function findFiasByName($term, $short) {
        $parts = explode(' ', $term, 2);
        $where = '';

        if (isset($parts[1])) {
            $where .= "(f1.offname LIKE '%" . $this->db->escape(utf8_strtolower($parts[0])) . "%'
                    AND (f2.offname LIKE '%" . $this->db->escape(utf8_strtolower($parts[1])) . "%' OR f3.offname LIKE '%" . $this->db->escape(utf8_strtolower($parts[1])) . "%')) OR ";
        }

        $where .= "(f1.offname LIKE '%" . $this->db->escape(utf8_strtolower($term)) . "%')";
        $field_name = $short ? "f1.offname" : "CONCAT_WS(' ', f1.shortname, f1.offname)";


        $query = $this->db->query("SELECT CONCAT_WS(', ',
                                                CONCAT_WS(' ', f1.shortname, f1.offname),
                                                CONCAT(f2.offname, ' ', f2.shortname),
                                                CONCAT(f3.offname, ' ', f3.shortname)) label,
                                        " . $field_name . " `name`,
                                        f1.fias_id `value`
                                    FROM fias f1
                                        LEFT JOIN fias f2 ON f2.fias_id = f1.parent_id
                                        LEFT JOIN fias f3 ON f3.fias_id = f2.parent_id
                                    WHERE (" . $where . ")
                                        AND f1.level IN (0, 1, 4, 6)
                                    ORDER BY f1.level, f2.level, f3.level
                                    LIMIT 100");

        return $query->rows;
    }

    public function install() {
        $this->installPrmnTables();
        $this->installDemoPrmnTables();
        $this->installFias();
        $this->installSetting();

        // Удаляем таблицы для прошлых версий
        $this->db->query("DROP TABLE IF EXISTS `country_to_fias`");
        $this->db->query("DROP TABLE IF EXISTS `zone_to_fias`");
    }

    private function installPrmnTables() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `prmn_cm_city` (
              `id` INT(11) NOT NULL AUTO_INCREMENT, 
              `fias_id` INT(11) NOT NULL, 
              `name` VARCHAR(255) NOT NULL, 
              `sort` SMALLINT(6) DEFAULT NULL, 
              PRIMARY KEY (`id`), 
              UNIQUE KEY `fias_id` (`fias_id`)
            ) ENGINE = MyISAM DEFAULT CHARSET = utf8;");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `prmn_cm_redirect` (
              `id` INT(11) NOT NULL AUTO_INCREMENT, 
              `fias_id` INT(11) NOT NULL, 
              `url` VARCHAR(255) NOT NULL, 
              PRIMARY KEY (`id`), 
              KEY `fias_id` (`fias_id`)
            ) ENGINE = MyISAM DEFAULT CHARSET = utf8;");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `prmn_cm_message` (
              `id` INT(11) NOT NULL AUTO_INCREMENT, 
              `fias_id` INT(11) NOT NULL, 
              `key` VARCHAR(255) NOT NULL, 
              `value` TEXT NOT NULL, 
              PRIMARY KEY (`id`), 
              KEY `fias_id` (`fias_id`)
            ) ENGINE = MyISAM DEFAULT CHARSET = utf8;");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `prmn_cm_currency` (
              `id` INT(11) NOT NULL AUTO_INCREMENT, 
              `country_id` INT(11) NOT NULL, 
              `code` VARCHAR(3) NOT NULL, 
              PRIMARY KEY (`id`)
            ) ENGINE = MyISAM DEFAULT CHARSET = utf8;");
    }

    private function installDemoPrmnTables() {
        if (empty($this->db->query("SELECT COUNT(*) total FROM prmn_cm_city")->row['total'])) {
            $this->db->query("
                INSERT INTO `prmn_cm_city` (`id`, `fias_id`, `name`) VALUES
                    (1, 41, 'Москва'), (8, 86, 'Санкт-Петербург'), (3, 4187, 'Ростов-на-Дону'), (4, 3737, 'Саратов'), 
                    (5, 3187, 'Екатеринбург'), (6, 5033, 'Владивосток'), (7, 2638, 'Хабаровск'), 
                    (2, 3145, 'Воронеж'), (9, 5147, 'Новосибирск'), (10, 2990, 'Нижний Новгород'), 
                    (11, 4006, 'Казань'), (12, 2782, 'Самара'), (13, 3704, 'Омск'), (14, 4778, 'Челябинск'), 
                    (15, 6125, 'Уфа'), (16, 3734, 'Волгоград'), (17, 3753, 'Красноярск'), (18, 4131, 'Пермь')");
        }
    }

    private function installFias() {
        // Если fias существует
        if (!empty($this->db->query("SHOW TABLES LIKE 'fias'")->row)) {
            $rows = [];
            foreach ($this->db->query("DESCRIBE `fias`")->rows as $row) {
                $rows[] = $row['Field'];
            }

            // Если структура изменилась (добавлены поля)
            if (array_diff(['fias_id', 'parent_id', 'postalcode', 'offname', 'shortname', 'level', 'altnames'], $rows)) {
                $this->db->query("DROP TABLE IF EXISTS `fias`");
            }
        }

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `fias` (
              `fias_id` INT(11) NOT NULL AUTO_INCREMENT, 
              `parent_id` INT(11) NOT NULL, 
              `postalcode` VARCHAR(6) DEFAULT NULL, 
              `offname` VARCHAR(120) DEFAULT NULL, 
              `shortname` VARCHAR(10) DEFAULT NULL, 
              `level` TINYINT(1) NOT NULL,
              `altnames` VARCHAR(500) DEFAULT NULL,
              PRIMARY KEY (`fias_id`), 
              KEY `postalcode` (`postalcode`), 
              KEY `offname` (`offname`), 
              KEY `level` (`level`), 
              KEY `parent_id` (`parent_id`), 
              KEY `osl` (`offname`, `shortname`, `level`)
            ) ENGINE = MYISAM DEFAULT CHARSET = utf8");
    }

    private function installSetting() {
        // Настройки по-умолчанию
        // Если еще нет настроек
        if (!$this->config->get('module_progroman_citymanager_setting')) {
            $this->load->model('setting/setting');

            // Настройки от версии до 7.3
            $setting = $this->config->get('progroman_cm_setting');
            if ($setting) {
                $this->model_setting_setting->deleteSetting('progroman_cm');
            } else {
                $setting = [
                    'use_geoip' => 1,
                    'disable_autoredirect' => 1,
                ];
            }

            $this->model_setting_setting->editSetting('module_progroman_citymanager',
                ['module_progroman_citymanager_setting' => $setting, 'module_progroman_citymanager_status' => 1]);
        }
    }

    /**
     * Удаляет "кусок" из fias
     * @param $start
     * @param $end
     */
    public function removeSliceFias($start, $end) {
        $this->db->query("DELETE FROM fias WHERE fias_id BETWEEN " . (int)$start . " AND " . (int)$end);
    }

    public function getNoRelativeCountries() {
        $query_fias = $this->db->query('SELECT fias_id, offname, altnames FROM fias WHERE `level` = ' . self::LEVEL_COUNTRY);
        $altnames = [];
        $altnames_to_fias = [];
        $fias = [];
        foreach ($query_fias->rows as $fias_row) {
            $fias[$fias_row['fias_id']] = $fias_row;

            foreach ($this->getAllAltnamesForCountry($fias_row) as $altname) {
                $altnames[] = $this->db->escape($altname);
                $altnames_to_fias[$altname] = $fias_row['fias_id'];
            }
        }

        $query_country = $this->db->query("SELECT country_id, name FROM " . DB_PREFIX . "country WHERE name IN ('" . implode("','", $altnames) . "')");
        foreach ($query_country->rows as $country) {
            unset($fias[$altnames_to_fias[utf8_strtolower($country['name'])]]);
        }

        return $fias;
    }

    public function getNoRelativeZones() {
        $query_fias = $this->db->query("
            SELECT f1.fias_id, f1.offname, f1.shortname, f1.altnames, f2.offname parent_name
            FROM fias f1
            LEFT JOIN fias f2 ON f1.parent_id = f2.fias_id 
            WHERE f1.`level` = " . self::LEVEL_REGION);
        $altnames = [];
        $altnames_to_fias = [];
        $fias = [];
        foreach ($query_fias->rows as $fias_row) {
            $fias[$fias_row['fias_id']] = $fias_row;

            foreach ($this->getAllAltnamesForZone($fias_row) as $altname) {
                $altnames[] = $this->db->escape($altname);
                $altnames_to_fias[$altname] = $fias_row['fias_id'];
            }
        }

        $query_zone = $this->db->query("SELECT zone_id, name FROM " . DB_PREFIX . "zone WHERE name IN ('" . implode("','", $altnames) . "')");
        foreach ($query_zone->rows as $zone) {
            unset($fias[$altnames_to_fias[utf8_strtolower($zone['name'])]]);
        }

        return $fias;
    }

    private function getAllAltnamesForCountry($fias) {
        $names = $fias['altnames'] ? explode(';', utf8_strtolower($fias['altnames'])) : [];
        $names[] = utf8_strtolower($fias['offname']);

        return $names;
    }

    private function getAllAltnamesForZone($fias) {
        $fias['offname'] = utf8_strtolower($fias['offname']);
        $fias['shortname'] = utf8_strtolower($fias['shortname']);

        $names = $fias['altnames'] ? explode(';', utf8_strtolower($fias['altnames'])) : [];
        $names[] = $fias['offname'];
        $names[] = $fias['offname'] . ' ' . $fias['shortname'];

        if ($fias['shortname'] == 'обл.') {
            $names[] = $fias['offname'] . ' область';
        } elseif (strcasecmp($fias['shortname'], 'респ.') == 0) {
            $names[] = 'республика ' . $fias['offname'];
            $names[] = $fias['offname'] . ' республика';
        }

        return $names;
    }
}