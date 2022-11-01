<?php

class ModelDwebExporterDwebExporter extends Model
{

    const TBL_NAME = 'dweb_exporting';

    public function addExporting($data)
    {
        $categories = "";
        if (isset($data['exporting_category']))
        {
            $categories = implode(",", $data['exporting_category']);
        }

        $all = 0;
        if (isset($data['all']))
        {
            $all = 1;
        }

        $use_custom_parser = 0;
        if (isset($data['use_custom_parser']))
        {
            $use_custom_parser = 1;
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . self::TBL_NAME . " SET "
                . "name = '" . $this->db->escape($data['name']) . "', "
                . "export_id = '" . $this->db->escape($data['export_id']) . "',"
                . "language = '" . (int) $data['language'] . "',"
                . "min_qty = '" . (int) $data['min_qty'] . "',"
                . DB_PREFIX . "dweb_exporting.all = " . $all . ","
                . DB_PREFIX . "dweb_exporting.use_custom_parser = " . $use_custom_parser . ","
                . "custom_parser = '" . $this->db->escape($data['custom_parser']) . "', "
                . "categories = '" . $this->db->escape($categories) . "'");

        $product_id = $this->db->getLastId();
        $this->cache->delete('exporting');

        return $product_id;
    }

    public function editExporting($id, $data)
    {
        $categories = "";
        if (isset($data['exporting_category']))
        {
            $categories = implode(",", $data['exporting_category']);
        }

        $all = 0;
        if (isset($data['all']))
        {
            $all = 1;
        }

        $use_custom_parser = 0;
        if (isset($data['use_custom_parser']))
        {
            $use_custom_parser = 1;
        }

        $this->db->query("UPDATE " . DB_PREFIX . self::TBL_NAME . " SET "
                . "name = '" . $this->db->escape($data['name']) . "', "
                . "export_id = '" . $this->db->escape($data['export_id']) . "',"
                . "language = '" . (int) $data['language'] . "',"
                . "min_qty = '" . (int) $data['min_qty'] . "',"
                . DB_PREFIX . "dweb_exporting.all = " . $all . ","
                . DB_PREFIX . "dweb_exporting.use_custom_parser = " . $use_custom_parser . ","
                . "custom_parser = '" . $this->db->escape($data['custom_parser']) . "', "
                . "categories = '" . $this->db->escape($categories) . "'"
                . " WHERE id = '" . (int) $id . "'");

        $this->cache->delete('exporting');
    }

    public function deleteExporting($id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . self::TBL_NAME . " WHERE id = '" . (int) $id . "'");

        $this->cache->delete('exporting');
    }

    public function getExporting($id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . self::TBL_NAME . " WHERE id=" . (int) $id . "");

        return $query->row;
    }

    public function getExportings($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . self::TBL_NAME . " ";


        $sql .= " GROUP BY id";

        $sort_data = array(
            'pd.name',
            'p.model',
            'p.price',
            'p.quantity',
            'p.status',
            'p.sort_order'
        );

        if (isset($data['start']) || isset($data['limit']))
        {
            if ($data['start'] < 0)
            {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1)
            {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getProductsByCategoryId($category_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int) $category_id . "' ORDER BY pd.name ASC");

        return $query->rows;
    }

    public function getProductDescriptions($product_id)
    {
        $product_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int) $product_id . "'");

        foreach ($query->rows as $result)
        {
            $product_description_data[$result['language_id']] = array(
                'name' => $result['name'],
                'description' => $result['description'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
                'tag' => $result['tag']
            );
        }

        return $product_description_data;
    }

    public function getProductCategories($product_id)
    {
        $product_category_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");

        foreach ($query->rows as $result)
        {
            $product_category_data[] = $result['category_id'];
        }

        return $product_category_data;
    }

    public function exportProducts($data = array())
    {
        if (isset($data['filename']))
        {
            $sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
            $query = $this->db->query($sql);

            $products = $query->rows;

            $domtree = new DOMDocument('1.0', 'UTF-8');

            /* create the root element of the xml tree */
            $xmlRoot = $domtree->createElement("root");
            /* append it to the document created */
            $xmlRoot = $domtree->appendChild($xmlRoot);

            if (!empty($products))
            {
                foreach ($products as $product)
                {
                    $productItem = $domtree->createElement("item");
                    $productItem = $xmlRoot->appendChild($productItem);

                    $productItem->appendChild($domtree->createElement('name', $product['name']));
                    $productItem->appendChild($domtree->createElement('link', 'title of song1.mp3'));
                    $productItem->appendChild($domtree->createElement('price', 'title of song1.mp3'));
                    $productItem->appendChild($domtree->createElement('category_full', 'title of song1.mp3'));
                    $productItem->appendChild($domtree->createElement('category_link', 'title of song1.mp3'));
                    $productItem->appendChild($domtree->createElement('manufacturer', 'title of song1.mp3'));
                    $productItem->appendChild($domtree->createElement('model', 'title of song1.mp3'));
                }
            }

            /* get the xml printed */
            $domtree->save($_SERVER['DOCUMENT_ROOT'] . '/export/' . $data['filename'] . '.xml');

            return true;
        }


        return false;
    }

}
