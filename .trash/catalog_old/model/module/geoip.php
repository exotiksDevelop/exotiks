<?php
    class ModelModuleGeoip extends Model {

        public function findFiasByName($term) {

            $parts = explode(' ', $term, 2);

            $sql = "SELECT CONCAT_WS(', ', CONCAT(f1.shortname, ' ', f1.offname), CONCAT(f2.offname, ' ', f2.shortname),
                                CONCAT(f3.offname, ' ', f3.shortname)) label, CONCAT(f1.shortname, ' ', f1.offname) value,
                                            f1.fias_id
                    FROM fias f1
                        LEFT JOIN fias f2 ON f2.fias_id = f1.parent_id
                        LEFT JOIN fias f3 ON f3.fias_id = f2.parent_id
                        WHERE (%WHERE%)
                      AND (f1.level = 6 OR f1.level = 4 OR (f1.level = 1 AND f1.shortname = 'г.'))
                      ORDER BY f1.level, f2.level, f3.level, f1.shortname";

            $escaped_term = $this->db->escape(utf8_strtolower($term));

            if (isset($parts[1])) {

                $where = "(f1.offname LIKE '" . $this->db->escape(utf8_strtolower($parts[0])) . "%'
                        AND (f2.offname LIKE '" . $this->db->escape(utf8_strtolower($parts[1])) . "%'"
                    . " OR f3.offname LIKE '" . $this->db->escape(utf8_strtolower($parts[1])) . "%'))
                        OR (f1.offname LIKE '" . $escaped_term . "%')";

                return $this->db->query(str_replace('%WHERE%', $where, $sql))->rows;
            }
            else {

                $where = "(f1.offname = '" . $escaped_term . "')";

                $query = $this->db->query(str_replace('%WHERE%', $where, $sql));

                $result = $query->rows;

                $where = "(f1.offname LIKE '" . $escaped_term . "%' AND f1.offname != '" . $escaped_term . "')";

                $query = $this->db->query(str_replace('%WHERE%', $where, $sql));

                $result = array_merge($result, $query->rows);

                if (count($result) < 100) {

                    $where = "(f1.offname LIKE '%" . $escaped_term . "%' AND f1.offname NOT LIKE '" . $escaped_term . "%')";

                    $result = array_merge($result, $this->db->query(str_replace('%WHERE%', $where, $sql))->rows);
                }

                return $result;
            }
        }

        public function getFiasById($fias_id) {

            return $this->db->query("SELECT f1.postalcode f1_postalcode, f1.offname f1_name,
                                            f1.level f1_level, f1.fias_id f1_fias_id, f1.shortname f1_shortname,
                                          CONCAT(f2.offname, ' ', f2.shortname) f2_name,
                                            f2.level f2_level, f2.fias_id f2_fias_id,
                                          CONCAT(f3.offname, ' ', f3.shortname) f3_name,
                                            f3.level f3_level, f3.fias_id f3_fias_id,
                                          CONCAT(f4.offname, ' ', f4.shortname) f4_name,
                                            f4.level f4_level, f4.fias_id f4_fias_id
                                      FROM fias f1
                                        LEFT JOIN fias f2 ON f2.fias_id = f1.parent_id
                                        LEFT JOIN fias f3 ON f3.fias_id = f2.parent_id
                                        LEFT JOIN fias f4 ON f4.fias_id = f3.parent_id
                                      WHERE f1.fias_id = " . (int)$fias_id)->row;
        }

        public function findCountryAndZone($filter = array()) {

            $where = array();
            $fields = array('c.country_id', 'c.name country_name');

            if (!($filter && is_array($filter))) {
                return array();
            }

            if (!empty($filter['iso_code_2'])) {
                $where[] = 'c.iso_code_2 = "' . $this->db->escape(utf8_strtolower($filter['iso_code_2'])) . '"';
            }

            if (!empty($filter['country_id'])) {
                $where[] = 'c.country_id = "' . $this->db->escape(utf8_strtolower($filter['country_id'])) . '"';
            }

            if (!empty($filter['zone_name'])) {
                $fields[] = 'z.zone_id, z.name zone_name';
                $where[] = 'z.name = "' . $this->db->escape(utf8_strtolower($filter['zone_name'])) . '"';
            }

            if (!empty($filter['zone_id'])) {
                $fields[] = 'z.zone_id, z.name zone_name';
                $where[] = 'z.zone_id = "' . (int)$filter['zone_id'] . '"';
            }

            if (!$where) {
                return array();
            }

            return $this->db->query('SELECT ' . implode(', ', $fields) . ' FROM ' . DB_PREFIX . 'zone z
                                            LEFT JOIN ' . DB_PREFIX . 'country c ON z.country_id = c.country_id
                                        WHERE ' . implode(' AND ', $where))->row;
        }

        public function getCountryByIsoCode($iso_code) {

            return $this->db->query('SELECT *
                                        FROM ' . DB_PREFIX . 'country c
                                        WHERE c.iso_code_2 = "' . $this->db->escape($iso_code) . '"')->row;
        }

        public function getCountryById($country_id) {

            $country = $this->db->query('SELECT name country_name
                                        FROM ' . DB_PREFIX . 'country
                                        WHERE country_id = ' . (int)$country_id);

            return $country->row;
        }

        public function getZoneIdByFiasId($fias_id) {

            $row = $this->db->query('SELECT zone_id FROM zone_to_fias WHERE fias_id = ' . (int)$fias_id)->row;

            return $row ? $row['zone_id'] : 0;
        }

        public function getCountryIdByFiasId($fias_id) {

            $row = $this->db->query('SELECT country_id FROM country_to_fias WHERE fias_id = ' . (int)$fias_id)->row;

            return $row ? $row['country_id'] : 0;
        }

        public function getFiasZoneIdByZoneId($zone_id) {

            $row = $this->db->query('SELECT fias_id FROM zone_to_fias WHERE zone_id = ' . (int)$zone_id)->row;

            return $row ? $row['fias_id'] : 0;
        }

        public function getFiasCountryIdByCountryId($country_id) {

            $row = $this->db->query('SELECT fias_id FROM country_to_fias WHERE country_id = ' . (int)$country_id)->row;

            return $row ? $row['fias_id'] : 0;
        }

        public function getFias($filter) {

            if (empty($filter['country_name'])) {
                return false;
            }

            if (!empty($filter['city_name'])) {
                return $this->db->query("SELECT f1.postalcode f1_postalcode, f1.offname f1_name,
                                            f1.level f1_level, f1.fias_id f1_fias_id, f1.shortname f1_shortname,
                                          CONCAT(f2.offname, ' ', f2.shortname) f2_name,
                                            f2.level f2_level, f2.fias_id f2_fias_id,
                                          CONCAT(f3.offname, ' ', f3.shortname) f3_name,
                                            f3.level f3_level, f3.fias_id f3_fias_id,
                                          CONCAT(f4.offname, ' ', f4.shortname) f4_name,
                                            f4.level f4_level, f4.fias_id f4_fias_id
                                      FROM fias f1
                                        LEFT JOIN fias f2 ON f2.fias_id = f1.parent_id
                                        LEFT JOIN fias f3 ON f3.fias_id = f2.parent_id
                                        LEFT JOIN fias f4 ON f4.fias_id = f3.parent_id
                                      WHERE f1.offname = '" . $this->db->escape($filter['city_name']) . "'
                                        AND (f3.offname = '" . $this->db->escape($filter['country_name'])
                                            . "' OR f2.offname = '" . $this->db->escape($filter['country_name'])
                                            . "' OR f4.offname = '" . $this->db->escape($filter['country_name']) . "')
                                        AND (f1.level = 4 OR (f1.level = 1 AND f1.shortname = 'г.'))")->row;
            }
            else {
                return $this->db->query("SELECT f1.offname f1_name, f1.level f1_level, f1.fias_id f1_fias_id
                                      FROM fias f1
                                      WHERE f1.offname = '" . $this->db->escape($filter['country_name']) . "' AND f1.level = 0")->row;
            }
        }

        public function getCities() {

            $query = $this->db->query("SELECT * FROM geoip_city ORDER BY sort, name");

            return $query->rows;
        }

        public function getRules() {

            $query = $this->db->query("SELECT * FROM geoip_rule");

            return $query->rows;
        }

        public function getRedirects() {

            $query = $this->db->query("SELECT * FROM geoip_redirect");

            return $query->rows;
        }

        public function getCurrencyForCountry($country_id) {

            $row = $this->db->query("SELECT code FROM geoip_currency WHERE country_id = " . (int)$country_id)->row;

            return $row ? $row['code'] : false;
        }

    }