<?php

class ModelExtensionModuleB24Category extends Model
{
    public function getById($category_id)
	{
		$db = $this->db;
		$sql = 'Select * from b24_category WHERE oc_category_id = "' . $db->escape($category_id) . '"';
		$query = $db->query($sql);

		return $query->row;
	}

	/**
	@description $data['oc_category_id']; $data['b24_category_id']
	 */
	public function addToDB($data)
	{
		//$ocId = $data['oc_category_id'];
		//$b24Id = $data['b24_category_id'];

		$db = $this->db;
		$sql = 'INSERT INTO b24_category SET ' . $this->prepareFields($data) . ';';
		$db->query($sql);
	}

	public function prepareFields(array $data)
	{
		$sql = '';
		$index = 0;
		foreach ($data as $key => $value) {
			$glue = $index === 0 ? '' : ', ';

			$sql .= $glue . "`$key`" . ' = ' . $this->db->escape($value);

			$index++;
		}

		return $sql;
	}

	public function getCategoryRows()
    {
        $minMaxParentIds = $this->getMinMaxParentIds();

        $minParentId = min($minMaxParentIds);

        $db = $this->db;
        $sql = 'SELECT * FROM `b24_category` WHERE `oc_category_id` != ' . $minParentId['parent_id'] .' HAVING COUNT(*) >= 1';
        $query = $db->query($sql);

        if (0 < $query->num_rows) {
            return true;
        }

        return false;
    }

    // пакетная вставка в БД категорий
    public function addBatchToDB($batchToDB = array())
    {
        $sql = 'INSERT INTO `b24_category` (`oc_category_id`, `b24_category_id`) VALUES';
        $values = '';
        foreach ($batchToDB as $key => $b24_id) {
            $values .= ' (' . $key . ',' . $b24_id .'),';
        }

        $query = $sql . rtrim($values, ',');

        $this->db->query($query);
    }

    // значения родительских категорий
    public function getMinMaxParentIds(){
        $db = $this->db;
        $sql = 'SELECT DISTINCT `parent_id` FROM `' . DB_PREFIX . 'category`';
        $query = $db->query($sql);

        if (0 < $query->num_rows) {
            return $query->rows;
        }

        return ['parent_id' => 0];
    }
}



