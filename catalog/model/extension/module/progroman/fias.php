<?php

/**
 * Class ModelExtensionModuleProgromanFias
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class ModelExtensionModuleProgromanFias extends Model {

    const LEVEL_COUNTRY = 0;
    const LEVEL_REGION = 1;
    const LEVEL_DISTRICT = 3;
    const LEVEL_CITY = 4;
    const LEVEL_TOWN = 6;

    static private $fias_to_zone;
    static private $fias_to_country;

    public function findFiasByName($term) {
        $term = $this->prepareSearchString($term);
        $parts = explode(' ', trim($term), 2);
        $sql = "
              SELECT CONCAT_WS(', ', CONCAT(f1.shortname, ' ', f1.offname), CONCAT(f2.offname, ' ', f2.shortname), CONCAT(f3.offname, ' ', f3.shortname)) `label`, 
                  f1.offname `name`, f1.fias_id `value`
                FROM fias f1
                LEFT JOIN fias f2 ON f2.fias_id = f1.parent_id
                LEFT JOIN fias f3 ON f3.fias_id = f2.parent_id
                WHERE (%WHERE%)
                  AND (f1.level IN (" . self::LEVEL_CITY . ", " . self::LEVEL_TOWN . ") OR (f1.level = " . self::LEVEL_REGION . " AND f1.shortname = 'г.'))
                ORDER BY f1.level, f2.level, f3.level, f1.shortname";

        $escaped_term = $this->db->escape(utf8_strtolower($term));

        if (isset($parts[1])) {
            $where = "(f1.offname LIKE '" . $this->db->escape(utf8_strtolower($parts[0])) . "%'
                    AND (f2.offname LIKE '" . $this->db->escape(utf8_strtolower($parts[1])) . "%'"
                . " OR f3.offname LIKE '" . $this->db->escape(utf8_strtolower($parts[1])) . "%'))
                    OR (f1.offname LIKE '" . $escaped_term . "%')";

            $result = $this->db->query(str_replace('%WHERE%', $where, $sql))->rows;

            if (count($result) < 10) {
                $where = "(f1.offname LIKE '%" . $this->db->escape($parts[0]) . "%'"
                    . " AND (f2.offname LIKE '" . $this->db->escape($parts[1]) . "%' OR f3.offname LIKE '" . $this->db->escape($parts[1]) . "%'))
                    AND f1.offname NOT LIKE '" . $this->db->escape($parts[0]) . "%'";

                $query = $this->db->query(str_replace('%WHERE%', $where, $sql));
                $result = array_merge($result, $query->rows);
            }
        } else {
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
        }

        return $result;
    }

    private function getFiasFields() {
        return "f1.offname f1_name, f1.shortname f1_shortname, f1.fias_id f1_fias_id, f1.level f1_level, f1.postalcode f1_postalcode, "
            . "f2.offname f2_name, f2.shortname f2_shortname, f2.fias_id f2_fias_id, f2.level f2_level, "
            . "f3.offname f3_name, f3.shortname f3_shortname, f3.fias_id f3_fias_id, f3.level f3_level, "
            . "f4.offname f4_name, f4.shortname f4_shortname, f4.fias_id f4_fias_id, f4.level f4_level, f4.parent_id f4_parent_id";
    }

    public function getFiasById($fias_id) {
        return $this->db->query("SELECT " . $this->getFiasFields() . "
                                  FROM fias f1
                                    LEFT JOIN fias f2 ON f2.fias_id = f1.parent_id
                                    LEFT JOIN fias f3 ON f3.fias_id = f2.parent_id
                                    LEFT JOIN fias f4 ON f4.fias_id = f3.parent_id
                                  WHERE f1.fias_id = " . (int)$fias_id)->row;
    }

    public function getFias($filter) {
        if (empty($filter['country_name'])) {
            return false;
        }

        if (!empty($filter['city_name'])) {
            $sql = "
                SELECT " . $this->getFiasFields() . "
                FROM fias f1
                LEFT JOIN fias f2 ON f2.fias_id = f1.parent_id
                LEFT JOIN fias f3 ON f3.fias_id = f2.parent_id
                LEFT JOIN fias f4 ON f4.fias_id = f3.parent_id
                WHERE f1.offname = '" . $this->db->escape($filter['city_name']) . "'
                    AND ((f2.offname = '" . $this->db->escape($filter['country_name']) . "' AND f2.level = " . self::LEVEL_COUNTRY . ")
                        OR (f3.offname = '" . $this->db->escape($filter['country_name']) . "' AND f3.level = " . self::LEVEL_COUNTRY . ")
                        OR (f4.offname = '" . $this->db->escape($filter['country_name']) . "' AND f4.level = " . self::LEVEL_COUNTRY . "))"
                . (!empty($filter['zone_name']) ? "
                    AND ((f1.offname = '" . $this->db->escape($filter['zone_name']) . "' AND f1.level = " . self::LEVEL_REGION . ")
                        OR (f2.offname = '" . $this->db->escape($filter['zone_name']) . "' AND f2.level = " . self::LEVEL_REGION . ")
                        OR (f3.offname = '" . $this->db->escape($filter['zone_name']) . "' AND f3.level = " . self::LEVEL_REGION . ")
                        OR (f4.offname = '" . $this->db->escape($filter['zone_name']) . "' AND f4.level = " . self::LEVEL_REGION . "))" : "") . "
                    AND f1.level IN (" . self::LEVEL_REGION . ", " . self::LEVEL_CITY . ", " . self::LEVEL_TOWN . ")
                ORDER BY f1_level";

            $result = $this->db->query($sql)->row;

            if (!$result && !empty($filter['zone_name'])) {
                unset($filter['zone_name']);
                return $this->getFias($filter);
            }

            return $result;
        } else {
            return $this->db->query("
                SELECT f1.offname f1_name, f1.level f1_level, f1.fias_id f1_fias_id
                FROM fias f1
                WHERE f1.offname = '" . $this->db->escape($filter['country_name']) . "' AND f1.level = " . self::LEVEL_COUNTRY)->row;
        }
    }

    public function findCountryAndZone($filter = []) {
        $where = [];
        $fields = ['c.country_id', 'c.name country_name'];

        if (!($filter && is_array($filter))) {
            return [];
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
            return [];
        }

        return $this->db->query(
            "SELECT " . implode(', ', $fields) . "\n"
            . "FROM " . DB_PREFIX . "zone z\n"
            . "LEFT JOIN " . DB_PREFIX . "country c ON z.country_id = c.country_id\n"
            . "WHERE " . implode(" AND ", $where)
        )->row;
    }

    public function getCountryByIsoCode($iso_code) {
        return $this->db->query(
            "SELECT * FROM " . DB_PREFIX . "country c WHERE c.iso_code_2 = '" . $this->db->escape($iso_code) . "'"
        )->row;
    }

    public function autocompleteForSimple($term, $limit) {
        $term = $this->prepareSearchString($term);
        $parts = explode(' ', trim($term), 2);
        $sql = "
            SELECT
              f1.fias_id AS id,
              CONCAT_WS(', ', CONCAT(f1.shortname, ' ', f1.offname), CONCAT(f2.offname, ' ', f2.shortname), CONCAT(f3.offname, ' ', f3.shortname), CONCAT(f4.offname, ' ', f4.shortname)) AS fullname,
              f1.offname AS name, f1.postalcode AS postcode,
              CASE
                WHEN f1.level = " . self::LEVEL_REGION . " THEN f1.fias_id
                WHEN f2.level = " . self::LEVEL_REGION . " THEN f2.fias_id
                WHEN f3.level = " . self::LEVEL_REGION . " THEN f3.fias_id
                WHEN f4.level = " . self::LEVEL_REGION . " THEN f4.fias_id
                WHEN f5.level = " . self::LEVEL_REGION . " THEN f5.fias_id
              END AS fias_zone_id,
              CASE
                WHEN f2.level = " . self::LEVEL_COUNTRY . " THEN f2.fias_id
                WHEN f3.level = " . self::LEVEL_COUNTRY . " THEN f3.fias_id
                WHEN f4.level = " . self::LEVEL_COUNTRY . " THEN f4.fias_id
                WHEN f5.level = " . self::LEVEL_COUNTRY . " THEN f5.fias_id
              END AS fias_country_id
            FROM fias f1
            LEFT JOIN fias f2 ON f2.fias_id = f1.parent_id
            LEFT JOIN fias f3 ON f3.fias_id = f2.parent_id
            LEFT JOIN fias f4 ON f4.fias_id = f3.parent_id
            LEFT JOIN fias f5 ON f5.fias_id = f4.parent_id
            WHERE (%WHERE%)
                AND (f1.level IN (" . self::LEVEL_CITY . ", " . self::LEVEL_TOWN . ") OR (f1.level = " . self::LEVEL_REGION . " AND f1.shortname = 'г.'))
            ORDER BY f1.level, f2.level, f3.level, f1.shortname
            LIMIT %LIMIT%";

        $escaped_term = $this->db->escape(utf8_strtolower($term));

        if (isset($parts[1])) {
            $where = "(f1.offname LIKE '" . $this->db->escape($parts[0]) . "%'"
                        . " AND (f2.offname LIKE '" . $this->db->escape($parts[1]) . "%' OR f3.offname LIKE '" . $this->db->escape($parts[1]) . "%'))
                    OR (f1.offname LIKE '" . $escaped_term . "%')";
            $result = $this->db->query(str_replace(['%WHERE%', '%LIMIT%'], [$where, $limit], $sql));

            if ($result->num_rows < $limit) {
                $where = "(f1.offname LIKE '%" . $this->db->escape($parts[0]) . "%'"
                    . " AND (f2.offname LIKE '" . $this->db->escape($parts[1]) . "%' OR f3.offname LIKE '" . $this->db->escape($parts[1]) . "%'))
                    AND f1.offname NOT LIKE '" . $this->db->escape($parts[0]) . "%'";

                $query = $this->db->query(str_replace(['%WHERE%', '%LIMIT%'], [$where, $limit - $result->num_rows], $sql));
                $result->rows = array_merge($result->rows, $query->rows);
                $result->num_rows += $query->num_rows;
            }
        } else {
            $where = "(f1.offname = '" . $escaped_term . "')";
            $query = $this->db->query(str_replace(['%WHERE%', '%LIMIT%'], [$where, $limit], $sql));
            $result = $query;

            if ($result->num_rows < $limit) {
                $where = "(f1.offname LIKE '" . $escaped_term . "%' AND f1.offname != '" . $escaped_term . "')";
                $query = $this->db->query(str_replace(['%WHERE%', '%LIMIT%'], [$where, $limit - $result->num_rows], $sql));
                $result->rows = array_merge($result->rows, $query->rows);
                $result->num_rows += $query->num_rows;

                if ($result->num_rows < $limit) {
                    $where = "(f1.offname LIKE '%" . $escaped_term . "%' AND f1.offname NOT LIKE '" . $escaped_term . "%')";
                    $query = $this->db->query(str_replace(['%WHERE%', '%LIMIT%'], [$where, $limit - $result->num_rows], $sql));
                    $result->rows = array_merge($result->rows, $query->rows);
                    $result->num_rows += $query->num_rows;
                }
            }
        }

        $fias_to_country = $this->getFiasToCountry();
        $fias_to_zone = $this->getFiasToZone();
        foreach ($result->rows as $key => $row) {
            $result->rows[$key]['country_id'] = isset($fias_to_country[$row['fias_country_id']]) ? $fias_to_country[$row['fias_country_id']]['country_id'] : null;
            $result->rows[$key]['zone_id'] = isset($fias_to_zone[$row['fias_zone_id']]) ? $fias_to_zone[$row['fias_zone_id']]['zone_id'] : null;
        }

        return $result;
    }

    /**
     * Убираем лишнее для поиска города по названию
     * @return string|string[]|null
     */
    private function prepareSearchString($search) {
        $search = preg_replace('#^(город|село|поселок)\s#', '', $search);
        return trim(preg_replace('#^(г|с|д|п|пос)(\.|\s)#', '', $search));
    }

    public function getFiasToCountry() {
        if (is_null(self::$fias_to_country)) {
            self::$fias_to_country = $this->cache->get('prmn.fias_to_country');
            if (!self::$fias_to_country) {
                $query_fias = $this->db->query('SELECT fias_id, offname, altnames FROM fias WHERE `level` = ' . self::LEVEL_COUNTRY);
                $altnames = [];
                $altname_to_fias = [];
                foreach ($query_fias->rows as $fias) {
                    foreach ($this->getAllAltnamesForCountry($fias) as $altname) {
                        $altnames[] = $this->db->escape($altname);
                        $altname_to_fias[$altname] = $fias['fias_id'];
                    }
                }

                $query_country = $this->db->query("SELECT country_id, name FROM " . DB_PREFIX . "country WHERE name IN ('" . implode("','", $altnames) . "')");
                self::$fias_to_country = [];
                foreach ($query_country->rows as $country) {
                    self::$fias_to_country[$altname_to_fias[utf8_strtolower($country['name'])]] = $country;
                }

                $this->cache->set('prmn.fias_to_country', self::$fias_to_country);
            }
        }

        return self::$fias_to_country;
    }

    public function getFiasToZone() {
        if (is_null(self::$fias_to_zone)) {
            self::$fias_to_zone = $this->cache->get('prmn.fias_to_zone');
            if (!self::$fias_to_zone) {
                $query_fias = $this->db->query('SELECT fias_id, offname, shortname, altnames FROM fias WHERE `level` = ' . self::LEVEL_REGION);
                $altnames = [];
                $altname_to_fias = [];
                foreach ($query_fias->rows as $fias) {
                    foreach ($this->getAllAltnamesForZone($fias) as $altname) {
                        $altname = utf8_strtolower($altname);
                        $altnames[] = $this->db->escape($altname);
                        $altname_to_fias[$altname] = $fias['fias_id'];
                    }
                }

                $query_zone = $this->db->query("SELECT zone_id, name FROM " . DB_PREFIX . "zone WHERE name IN ('" . implode("','", $altnames) . "')");
                self::$fias_to_zone = [];
                foreach ($query_zone->rows as $zone) {
                    self::$fias_to_zone[$altname_to_fias[utf8_strtolower($zone['name'])]] = $zone;
                }

                $this->cache->set('prmn.fias_to_zone', self::$fias_to_zone);
            }
        }

        return self::$fias_to_zone;
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