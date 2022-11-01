<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
*/

class ModelToolSimpleApi extends Model {
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
                name ASC
            LIMIT 0, 20");

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
                z.name LIKE '%" . $this->db->escape($name) . "%'
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

    public function updateAbandonedCart($data) {
        $simple_cart_id = 0;

        if ($data['simple_cart_id']) {
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_cart` WHERE simple_cart_id = '" . (int)$data['simple_cart_id'] . "'");
        
            if (!$query->num_rows) {
                $simple_cart_id = 0;
            } else {
                $simple_cart_id = $data['simple_cart_id'];
            }
        } 

        if ($simple_cart_id) {
            $sql = "
                UPDATE 
                    `" . DB_PREFIX . "simple_cart` 
                SET ";

            $parts = array();

            if (!empty($data['customer_id'])) {
                $parts[] = "customer_id = '" . (int)$data['customer_id'] . "'";
            }

            if (!empty($data['email'])) {
                $parts[] = "email = '" . $this->db->escape($data['email']) . "'";
            }

            if (!empty($data['firstname'])) {
                $parts[] = "firstname = '" . $this->db->escape($data['firstname']) . "'";
            }

            if (!empty($data['lastname'])) {
                $parts[] = "lastname = '" . $this->db->escape($data['lastname']) . "'";
            }

            if (!empty($data['telephone'])) {
                $parts[] = "telephone = '" . $this->db->escape($data['telephone']) . "'";
            }

            if (!empty($data['products'])) {
                $parts[] = "products = '" . $this->db->escape(json_encode($data['products'])) . "'";
            }

            if (count($parts)) {
                $sql .= implode(',', $parts);

                $sql .= " WHERE simple_cart_id = '" . (int)$simple_cart_id . "'";
            }

            $this->db->query($sql); 

            return $simple_cart_id;
        } else {
            $this->db->query("
                INSERT INTO 
                    `" . DB_PREFIX . "simple_cart` 
                SET 
                    store_id = '" . (int)$data['store_id'] . "', 
                    customer_id = '" . (int)$data['customer_id'] . "', 
                    email = '" . $this->db->escape($data['email']) . "', 
                    firstname = '" . $this->db->escape($data['firstname']) . "', 
                    lastname = '" . $this->db->escape($data['lastname']) . "', 
                    telephone = '" . $this->db->escape($data['telephone']) . "', 
                    products = '" . $this->db->escape(json_encode($data['products'])) . "', 
                    date_added = NOW()
            ");

            return $this->db->getLastId();
        }
    }

    public function deleteAbandonedCart($simple_cart_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "simple_cart` WHERE simple_cart_id = '" . (int)$simple_cart_id . "'");
    }
}