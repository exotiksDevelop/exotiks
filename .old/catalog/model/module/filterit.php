<?php
/*
@author  Dmitriy Kubarev
@link  http://www.simpleopencart.com
*/

class ModelModuleFilterit extends Model {
    public function getProductsByName($name) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "product p
            LEFT JOIN
                " . DB_PREFIX . "product_description pd
            ON
                (p.product_id = pd.product_id)
            WHERE
                pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND
                (pd.name LIKE '" . $this->db->escape($name) . "%' OR p.model LIKE '" . $this->db->escape($name) . "%')
            GROUP BY
                p.product_id
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getProductsByIds($ids) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "product p
            LEFT JOIN
                " . DB_PREFIX . "product_description pd
            ON
                (p.product_id = pd.product_id)
            WHERE
                pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND
                p.product_id IN (" . implode(',', $ids) . ")
            GROUP BY
                p.product_id
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getCategoriesByName($name) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "category c
            LEFT JOIN
                " . DB_PREFIX . "category_description cd
            ON
                (c.category_id = cd.category_id)
            WHERE
                cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND
                cd.name LIKE '" . $this->db->escape($name) . "%'
            GROUP BY
                c.category_id
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getCategoriesByIds($ids) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "category c
            LEFT JOIN
                " . DB_PREFIX . "category_description cd
            ON
                (c.category_id = cd.category_id)
            WHERE
                cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND
                c.category_id IN (" . implode(',', $ids) . ")
            GROUP BY
                c.category_id
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getAttributesByName($name) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "attribute a
            LEFT JOIN
                " . DB_PREFIX . "attribute_description ad
            ON
                (a.attribute_id = ad.attribute_id)
            WHERE
                ad.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND
                ad.name LIKE '" . $this->db->escape($name) . "%'
            GROUP BY
                a.attribute_id
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getAttributesByIds($ids) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "attribute a
            LEFT JOIN
                " . DB_PREFIX . "attribute_description ad
            ON
                (a.attribute_id = ad.attribute_id)
            WHERE
                ad.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND
                a.attribute_id IN (" . implode(',', $ids) . ")
            GROUP BY
                a.attribute_id
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getManufacturersByName($name) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "manufacturer m
            WHERE
                m.name LIKE '" . $this->db->escape($name) . "%'
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getManufacturersByIds($ids) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "manufacturer m
            WHERE
                m.manufacturer_id IN (" . implode(',', $ids) . ")
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getOptionValuesByName($name) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "option_value ov
            LEFT JOIN
                " . DB_PREFIX . "option_value_description ovd
            ON
                ov.option_value_id = ovd.option_value_id
            WHERE
                ovd.name LIKE '" . $this->db->escape($name) . "%'
            AND
                ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            GROUP BY
                ov.option_value_id
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getOptionValuesByIds($ids) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "option_value ov
            LEFT JOIN
                " . DB_PREFIX . "option_value_description ovd
            ON
                ov.option_value_id = ovd.option_value_id
            WHERE
                ov.option_value_id IN (" . implode(',', $ids) . ")
            AND
                ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            GROUP BY
                ov.option_value_id
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getCountriesByName($name) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "country c
            WHERE
                c.name LIKE '" . $this->db->escape($name) . "%'
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getCountriesByIds($ids) {
        $query = $this->db->query("SELECT
                *
            FROM
                " . DB_PREFIX . "country c
            WHERE
                c.country_id IN (" . implode(',', $ids) . ")
            ORDER BY
                name ASC");

        return $query->rows;
    }

    public function getZonesByName($name) {
        $query = $this->db->query("SELECT
                z.zone_id,
                z.name AS zone_name,
                c.name AS country_name
            FROM
                " . DB_PREFIX . "zone z
            LEFT JOIN
                " . DB_PREFIX . "country c
            ON
                z.country_id = c.country_id
            WHERE
                z.name LIKE '" . $this->db->escape($name) . "%'
            ORDER BY
                z.name ASC");

        return $query->rows;
    }

    public function getZonesByIds($ids) {
        $query = $this->db->query("SELECT
                z.zone_id,
                z.name AS zone_name,
                c.name AS country_name
            FROM
                " . DB_PREFIX . "zone z
            LEFT JOIN
                " . DB_PREFIX . "country c
            ON
                z.country_id = c.country_id
            WHERE
                z.zone_id IN (" . implode(',', $ids) . ")
            ORDER BY
                z.name ASC");

        return $query->rows;
    }

    public function getAddresses($term) {
        $address_data = array();

        $query = $this->db->query("SELECT
                country_id, zone_id, city, postcode
            FROM
                " . DB_PREFIX . "address
            WHERE
                city LIKE '%" . $this->db->escape($term) . "%'
            OR
                postcode LIKE '%" . $this->db->escape($term) . "%'
            GROUP BY
                country_id, zone_id, city, postcode");

        foreach ($query->rows as $result) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");

            if ($country_query->num_rows) {
                $country = $country_query->row['name'];
                $iso_code_2 = $country_query->row['iso_code_2'];
                $iso_code_3 = $country_query->row['iso_code_3'];
                $address_format = $country_query->row['address_format'];
            } else {
                $country = '';
                $iso_code_2 = '';
                $iso_code_3 = '';
                $address_format = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

            if ($zone_query->num_rows) {
                $zone = $zone_query->row['name'];
                $zone_code = $zone_query->row['code'];
            } else {
                $zone = '';
                $zone_code = '';
            }

            $address_data[] = array(
                'postcode'       => $result['postcode'],
                'city'           => $result['city'],
                'zone_id'        => $result['zone_id'],
                'zone'           => $zone,
                'zone_code'      => $zone_code,
                'country_id'     => $result['country_id'],
                'country'        => $country,
                'address_format' => $address_format
            );
        }

        return $address_data;
    }

    public function getCustomersByName($term) {
        $query = $this->db->query("SELECT
                *,
                CONCAT(c.firstname, ' ', c.lastname) AS name
            FROM
                " . DB_PREFIX . "customer c
            WHERE
                LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($term)) . "%'
            ORDER BY
                name");

        return $query->rows;
    }

    public function getCustomersByIds($ids) {
        $query = $this->db->query("SELECT
                *,
                CONCAT(c.firstname, ' ', c.lastname) AS name
            FROM
                " . DB_PREFIX . "customer c
            WHERE
                customer_id IN (". implode(',', $ids) .")
            ORDER BY
                customer_id");

        return $query->rows;
    }

    public function getOrderStatusesByName($term) {
        $query = $this->db->query("SELECT
                order_status_id,
                name
            FROM
                " . DB_PREFIX . "order_status
            WHERE
                name LIKE '" . $this->db->escape(utf8_strtolower($term)) . "%'
            AND
                language_id = '" . (int)$this->config->get('config_language_id') . "'
            ORDER BY
                name");

        return $query->rows;
    }

    public function getOrderStatusesByIds($ids) {
        $query = $this->db->query("SELECT
                order_status_id,
                name
            FROM
                " . DB_PREFIX . "order_status
            WHERE
                order_status_id IN (". implode(',', $ids) .")
            ORDER BY
                name");

        return $query->rows;
    }

    public function getProductColumns() {
        $columns = array();
        $columns_query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product");

        $allowed_columns = array('model','sku','upc','ean','jan','isbn','mpn','location');
        foreach ($columns_query->rows as $column) {
            if (in_array($column['Field'], $allowed_columns)) {
                $columns[] = $column['Field'];
            }
        }

        return $columns;
    }

    public function getStockStatuses() {
        $query = $this->db->query("SELECT
                stock_status_id AS id,
                name AS name
            FROM
                " . DB_PREFIX . "stock_status
            WHERE
                language_id = '" . (int)$this->config->get('config_language_id') . "'
            ORDER BY
                name");

        return $query->rows;
    }

    public function getLanguages() {
        $query = $this->db->query("SELECT
                language_id AS id,
                name AS name
            FROM
                " . DB_PREFIX . "language
            ORDER BY
                name");

        return $query->rows;
    }

    public function getCurrencies() {
        $query = $this->db->query("SELECT
                code AS id,
                title AS name
            FROM
                " . DB_PREFIX . "currency
            ORDER BY
                name");

        return $query->rows;
    }

    public function getGeoZones() {
        $query = $this->db->query("SELECT
                geo_zone_id AS id,
                name
            FROM
                " . DB_PREFIX . "geo_zone
            ORDER BY
                name");

        return $query->rows;
    }

    public function getCustomerGroups() {
        $values = array();

        $version = explode('.', VERSION);
        $version = floatval($version[0].$version[1].$version[2].'.'.(isset($version[3]) ? $version[3] : 0));

        $requiredGroupId = 0;

        if (file_exists(DIR_APPLICATION . 'model/account/customer_group.php') && $version >= 153) {
            $this->load->model('account/customer_group');

            if (method_exists($this->model_account_customer_group, 'getCustomerGroups') || property_exists($this->model_account_customer_group, 'getCustomerGroups')) {
                $customerGroups = $this->model_account_customer_group->getCustomerGroups();

                $displayedGroups = $this->config->get('config_customer_group_display');

                if (!empty($displayedGroups) && is_array($displayedGroups)) {
                    foreach ($customerGroups as $customerGroup) {
                        if (in_array($customerGroup['customer_group_id'], $displayedGroups) || $customerGroup['customer_group_id'] == $requiredGroupId) {
                            $values[] = array(
                                'id'   => $customerGroup['customer_group_id'],
                                'name' => $customerGroup['name']
                            );
                        }
                    }
                } else {
                    foreach ($customerGroups as $customerGroup) {
                        $values[] = array(
                            'id'   => $customerGroup['customer_group_id'],
                            'name' => $customerGroup['name']
                        );
                    }
                }
            }
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group");

            $displayedGroups = $this->config->get('simple_customer_group_display');

            if (!empty($displayedGroups) && is_array($displayedGroups)) {
                foreach ($query->rows as $row) {
                    if (in_array($row['customer_group_id'], $displayedGroups) || $row['customer_group_id'] == $requiredGroupId) {
                        $values[] = array(
                            'id'   => $row['customer_group_id'],
                            'name' => $row['name']
                        );
                    }
                }
            } else {
                foreach ($query->rows as $row) {
                    $values[] = array(
                        'id'   => $row['customer_group_id'],
                        'name' => $row['name']
                    );
                }
            }
        }

        return $values;
    }

}

class ModelExtensionModuleFilterit extends ModelModuleFilterit {
}