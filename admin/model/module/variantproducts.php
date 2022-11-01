<?php
class ModelModuleVariantproducts extends Model {
	public function addVariantproduct($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "variantproducts SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "'");

		$variantproduct_id = $this->db->getLastId();

		foreach ($data['variantproduct_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "variantproducts_description SET variantproduct_id = '" . (int)$variantproduct_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "',  label = '" . $this->db->escape($value['label']) ."'");
		}

		if (isset($data['product'])  &&  is_array($data['product'])) {
			foreach ($data['product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "variantproducts_to_product SET variantproduct_id = '" . (int)$variantproduct_id . "', product_id = '" . (int)$product_id . "'");
			}
		}
	}

	public function editVariantproduct($variantproduct_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "variantproducts SET  sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE variantproduct_id = '" . (int)$variantproduct_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "variantproducts_description WHERE variantproduct_id = '" . (int)$variantproduct_id . "'");

		foreach ($data['variantproduct_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "variantproducts_description SET variantproduct_id = '" . (int)$variantproduct_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', label = '" . $this->db->escape($value['label']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "variantproducts_to_product WHERE variantproduct_id = '" . (int)$variantproduct_id . "'");

		if (isset($data['product'])  &&  is_array($data['product'])) {
			foreach ($data['product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "variantproducts_to_product SET variantproduct_id = '" . (int)$variantproduct_id . "', product_id = '" . (int)$product_id . "'");
			}
		}		
	}

	public function deleteVariantproduct($variantproduct_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "variantproducts WHERE variantproduct_id = '" . (int)$variantproduct_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "variantproducts_description WHERE variantproduct_id = '" . (int)$variantproduct_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "variantproducts_to_product WHERE variantproduct_id = '" . (int)$variantproduct_id . "'");
	}

	public function getVariantproduct($variantproduct_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "variantproducts v LEFT JOIN " . DB_PREFIX . "variantproducts_description vd ON (v.variantproduct_id = vd.variantproduct_id) LEFT JOIN " . DB_PREFIX . "variantproducts_to_product v2p ON (v.variantproduct_id = v2p.variantproduct_id) WHERE v.variantproduct_id = '" . (int)$variantproduct_id . "' AND vd.language_id = '" . $this->config->get('config_language_id') . "'");

		return $query->row;
	}



	
	public function getVariantproducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "variantproducts v LEFT JOIN " . DB_PREFIX . "variantproducts_description vd ON (v.variantproduct_id = vd.variantproduct_id) WHERE vd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY v.variantproduct_id ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function getVariantproductDescriptions($variantproduct_id) {
		$variantproduct_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "variantproducts_description WHERE variantproduct_id = '" . (int)$variantproduct_id . "'");

		foreach ($query->rows as $result) {
			$variantproduct_description_data[$result['language_id']] = array(
				'title'        => $result['title'],
				'label'        => $result['label']
			);
		}

		return $variantproduct_description_data;
	}

    public function getVariantproductProducts($variantproduct_id) {
        $product_id = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "variantproducts_to_product WHERE variantproduct_id = '" . (int)$variantproduct_id . "'");

        foreach ($query->rows as $result) {
            $product_id[] = $result['product_id'];
        }

        return $product_id;
    }
	public function getTotalVariantproducts() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "variantproducts");

		return $query->row['total'];
	}
}
