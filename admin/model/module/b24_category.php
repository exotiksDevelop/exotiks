<?php
class ModelModuleB24Category extends Model{
    public function synchronizationcategories(){
        $this->load->model('catalog/category');
		$rows = $this->db->query("SELECT b24_category_id FROM b24_category WHERE oc_category_id = 0;");
        $minMaxParentIds = $this->getMinMaxParentIds();

        $this->minMaxParentIds = $minMaxParentIds;

        $minParentId = min($minMaxParentIds);

        if (isset($minParentId['parent_id']) && $rows->num_rows < 1 && is_numeric($minParentId['parent_id']) && (int)$minParentId['parent_id'] >= 0) {
			// Если коневой категории нет в базе то добавляем ее и все категории магазина
			$params = [
                'type' => 'crm.productsection.add',
                'params' => [
                    'fields' => [
                        'NAME' => html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . ' (' . $this->request->server['SERVER_NAME'] . ')',
                    ]
                ]
            ];

            $result = $this->b24->callHook($params);
			if (!empty($result['error_description'])) {
				$this->b24->writeLog('Ошибка синхронизации категории каталога в Битрикс24');
				$this->b24->writeLog($result['error_description']);
			}

            $lastId = $result['result'];

            $data = ['oc_category_id' => $minParentId['parent_id'], 'b24_category_id' => $lastId];
            $this->addToDB($data);
			$this->addCategoriesBatch($lastId);
        } else {
			//Если корневая категория есть то добавляем категории которых нет в базе.
			$this->addCategoriesBatch($minParentId['parent_id']);  
        }
    }

    public function addCategoriesBatch($parentCategory = 0){
        $this->load->model('catalog/category');
		// проверяем есть ли не синхронизированные категории
        $rows = $this->getCatNoSync();
		
        if (1 > $rows) {
            return;
        }
		
		$build = [];

        $minMaxParentId = $this->minMaxParentIds;

        foreach ($minMaxParentId as $minMaxIds) {
            $parentId = $minMaxIds['parent_id'];
            $categoriesInfo = $this->getCategoriesByParentId($parentId);

            $recod = $this->getById($parentId);

            $parentId = !empty($recod)? $recod['b24_category_id'] : $parentCategory;

            foreach ($categoriesInfo as $info) {
                $categoryId = $info['category_id'];
                $categoryName = $info['name'];

                $fields = [
                    'CATALOG_ID' => $categoryId,
                    'NAME'=> $categoryName,
                    'SECTION_ID'=> $parentId,
                ];

                $build['cmd'][$categoryId] = 'crm.productsection.add?'. http_build_query(['fields' => $fields]);
            }

            if (!empty($build['cmd'])) {
                $params = [
                    'type' => 'batch',
                    'params' => $build
                ];

                $result = $this->b24->callHook($params);
				
				if (!empty($result['error_description'])) {
					$this->b24->writeLog('Ошибка при запросе addCategoriesBatch');
					$this->b24->writeLog($result['error_description']);
				} else {
                    $ids = $result['result']['result'];
                    $this->addBatchToDB($ids);
                }

                $build['cmd'] = [];
            }
        }
    }
	//NEW
	
	public function getById($category_id) {
		$query = $this->db->query("Select * from b24_category WHERE oc_category_id = ". $this->db->escape($category_id) .";");
		return $query->row;
	}
	
	public function addToDB($data) {
		$this->db->query("INSERT INTO b24_category SET ". $this->prepareFields($data) .";");
		$this->log->write(print_r('Module Bitrix24 - Добавлена связь с категорией '.$data['oc_category_id'].'',true));
	}
	
	public function dellToDB($categoryId) {
		$this->db->query("DELETE FROM b24_category WHERE oc_category_id = ".$categoryId.";");
		$this->log->write(print_r('Module Bitrix24 - Удалена связь с категорией '.$categoryId.'',true));
	}

	public function prepareFields(array $data) {
		$sql = '';
		$index = 0;
		foreach ($data as $key => $value) {
			$glue = $index === 0 ? '' : ', ';

			$sql .= $glue . "`$key`" . ' = ' . $this->db->escape($value);

			$index++;
		}

		return $sql;
	}

	public function getCategoryRows() {
        $minMaxParentIds = $this->getMinMaxParentIds();
        $minParentId = min($minMaxParentIds);
		$query = $this->db->query("SELECT * FROM b24_category WHERE oc_category_id != ". $minParentId['parent_id'] .";");

        if (0 < $query->num_rows) {
            return true;
        }

        return false;
    }

    // пакетная вставка в БД категорий
    public function addBatchToDB($batchToDB = array()) {
        $sql = 'INSERT INTO `b24_category` (`oc_category_id`, `b24_category_id`) VALUES';
        $values = '';
        foreach ($batchToDB as $key => $b24_id) {
            $values .= ' (' . $key . ',' . $b24_id .'),';
        }

        $query = $sql . rtrim($values, ',');

        $this->db->query($query);
    }

    // значения родительских категорий
    public function getMinMaxParentIds() {
        $query = $this->db->query("SELECT DISTINCT parent_id FROM ". DB_PREFIX ."category;");

        if (0 < $query->num_rows) {
            return $query->rows;
        }

        return ['parent_id' => 0];
    }
	
	public function getCatNoSync() {
		$sql = 'SELECT '. DB_PREFIX .'category.category_id
				FROM '. DB_PREFIX .'category 
				LEFT JOIN b24_category ON '. DB_PREFIX .'category.category_id=b24_category.oc_category_id
				WHERE b24_category.oc_category_id IS NULL';
        $rows = $this->db->query($sql);
		return $rows->num_rows;
	}
	
	public function getCategoriesByParentId($parent_id = 0) {
		$query = $this->db->query("SELECT *, (SELECT COUNT(parent_id) FROM " . DB_PREFIX . "category WHERE parent_id = c.category_id) AS children FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name");

		return $query->rows;
	}
}