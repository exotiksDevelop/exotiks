<?php
class ModelExtensionModuleB24Product extends Model
{
	public $PROPERTY_QUANTITY = 'PROPERTY_100';
	public $PROPERTY_WAREHOUSE_STATUS = 'PROPERTY_112';
	public $PROPERTY_MODEL = 'PROPERTY_104';
	public $PROPERTY_MANUFACTURER = 'PROPERTY_106';
	public $PROPERTY_SIZE = 'PROPERTY_108';
	public $PROPERTY_OC_ID = 'PROPERTY_110';

	public $PRODUCT_WO_SIZE = 0;

	public function addToDB($productId, array $result)
	{
		if (empty($result['result']) || (int) $productId <= 0 ) {
			trigger_error('Пустой массив или ИД продукта при добавлении товаров из Б24 '
				. print_r($productId, 1) . print_r($result, 1),
				E_USER_WARNING);
		}

		foreach ($result['result'] as $size => $b24Id) {
			// При импорте всех товаров, имя другое
			if (stripos($size, ';') > 0) {
				list($productId, $size) = explode(';', $size);
			}
			
			$fields = ['oc_product_id' => $productId, 'b24_product_id' => $b24Id, 'size' => $size];
			$this->insertToDB('b24_product', $fields);
		}

		$txt = 'Добавлено : ' . print_r($result['result'], 1) . "товаров" . print_r($result, 1);
		//add2Log($txt);
	}
	
	public function updateToDb($productId, array $result)
	{
		if (empty($result['result']) || (int) $productId <= 0) {
			trigger_error('Пустой массив или ИД продукта при обновлении товаров из Б24 '
				. print_r($productId, 1) . print_r($result, 1),
				E_USER_WARNING);
		}

		foreach ($result['result'] as $sizeName => $isUpdated) {
			// При импорте всех товаров, имя другое
			if (stripos($sizeName, ';') > 0) {
				list($productId, $sizeName) = explode(';', $sizeName);
			}
			
			$sql = "UPDATE b24_product SET date_update = NOW() 
						where
						size = '$sizeName'
						AND oc_product_id = $productId";

			$this->db->query($sql);
		}
	}

	public function addProductBatch( $productId, array $dataToB24 )
	{
		//$dataToAdd = $this->prepareDataToB24($productId);

		foreach ($dataToB24 as $sizeName => $newData) {
			$dataToAdd[$sizeName] = 'crm.product.add?'. http_build_query(['fields' => $newData]);
		}

		$result = $this->sendBatchQuery($dataToAdd);

		/**
		 * Todo добиться атомарности операции. В Б24 можно добавить одинаковые товары. Если не запишется в БД,
		 * то след раз добавятся еще товары
		 */

		$this->addToDB($productId, $result['result']);
	}

	public function addProductsBatch(){
        $this->load->model('setting/setting');
        $b24_setting = $this->model_setting_setting->getSetting('b24_hook_key');
		$this->b24->setFields($b24_setting);
		
        $sql = 'SELECT `product_id` FROM `' . DB_PREFIX . 'product` WHERE `product_id`';
        $rows = $this->db->query($sql);

        if (1 > $rows->num_rows) {
            return;
        }

        $count_products = $rows->num_rows;
        $products = $rows->rows;
        $limit = 0;
        $build = [];

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $dataToAdd = $this->prepareDataToB24($productId);

            if (1 < count($dataToAdd)) {
                // с опциями
                foreach ($dataToAdd as $key => $fields) {
                    $build['cmd'][$productId . '##' . $key] = 'crm.product.add?'. http_build_query(['fields' => $fields]);
                    $limit++;
                }
            } else {
                // товар
                foreach ($dataToAdd as $fields) {
                    $build['cmd'][$productId] = 'crm.product.add?'. http_build_query(['fields' => $fields]);
                    $limit++;
                }
            }

            if (/*($limit == $count_products && $count_products <= 45) || 45 <= $limit || 45 <= */count($build['cmd'])) {
                $params = [
                    'type' => 'batch',
                    'params' => $build
                ];

                $result = $this->b24->callHook($params);

                if (!empty($result['result']['result_error'])) {
                    trigger_error('Ошибка при запросе sendBatchQuery ' . print_r($result['result']['result_error'], 1));
                } else {
                    $ids = $result['result']['result'];                    
					$this->addBatchToDB($ids);
                }

                $build['cmd'] = [];

                $limit = 0;
            }
        }
    }

    // пакетная вставка в БД товаров
    public function addBatchToDB($batchToDB = array())
    {
        $sql = 'INSERT INTO `b24_product` (`oc_product_id`, `b24_product_id`, `size`, `date_update`) VALUES';
        $values = '';
        foreach ($batchToDB as $key => $b24_id) {
            $dataToDB = explode('##', $key);

            $size = isset($dataToDB[1]) ? $dataToDB[1] : 0;

            $values .= ' (' . $dataToDB[0] . ',' . $b24_id .',"' . $size . '", NOW()),';
        }

        $query = $sql . rtrim($values, ',');

        $this->db->query($query);
    }

	public function editProductBatch($productId, array $dataToB24)
	{
		if (abs($productId) <= 0) {
			trigger_error('$productId Must be integer', E_USER_WARNING);
		}

        $countToB24 = count($dataToB24);

        $listB24 = $this->getList(['oc_product_id' => $productId]);

        $countListB24 = count($listB24);

        // добавление, обновление
        if ($countToB24 >= $countListB24) {
            foreach ($dataToB24 as $sizeName => $newData) {
                $b24ProductList = $this->getList(['oc_product_id' => $productId, 'size' => $sizeName]);
                $b24Product = isset($b24ProductList[0]) ? $b24ProductList[0] : [];

                // добавление
                if (empty($b24Product['b24_product_id'])) {
                    $dataToAdd[$sizeName] = $newData;

                    $b24ProductList = $this->getList(['oc_product_id' => $productId, 'size' => $this->PRODUCT_WO_SIZE]);
                    if (!empty($b24ProductList)) {
                        $dataToDelete[$this->PRODUCT_WO_SIZE] = 'crm.product.delete?id=' . $b24ProductList[0]['b24_product_id'];
                        $this->deleteRecordInDB(['oc_product_id' => $productId]);
                    } else {
                        if (isset($listB24[0])) {
                            $dataToDelete[$listB24[0]['size']] = 'crm.product.delete?id=' . $listB24[0]['b24_product_id'];
                            $this->deleteRecordInDB(['oc_product_id' => $productId]);
                        }
                    }
                } else {
                // обновление
                    $dataToUpdate[$sizeName] = 'crm.product.update?id=' . $b24Product['b24_product_id'] . '&' . http_build_query(['fields' => $newData]);
                }
            }
        } else {
            // удаление
            foreach ($dataToB24 as $sizeName => $newData) {
                foreach ($listB24 as $key => $fields) {
                    if (0 == $countListB24) {
                        $b24_product_id = $productId;
                    } else {
                        $b24_product_id = $fields['b24_product_id'];
                    }

                    if ($sizeName == $fields['size']) {
                        $dataToUpdate[$sizeName] = 'crm.product.update?id=' . $b24_product_id . '&' . http_build_query(['fields' => $newData]);
                        unset($listB24[$key]);
                        break;
                    }
                }
            }

            foreach ($listB24 as $key => $fields) {
                if (!isset($fields['b24_product_id'])) continue;

                $b24_product_id = $fields['b24_product_id'];
                $dataToDelete[$fields['size']] = 'crm.product.delete?id=' . $b24_product_id;

                $this->deleteRecordInDB(['b24_product_id' => $b24_product_id]);
            }
        }

        if (!empty($dataToDelete) && is_array($dataToDelete)) {

            $this->sendBatchQuery($dataToDelete);
        }

		if (!empty($dataToAdd) && is_array($dataToAdd))
		{
			$this->addProductBatch( $productId, $dataToAdd );
		}

		/*$result = {array} [1]
			 result = {array} [4]
			 	 result = {array} [1]
			  		 116,120 = true
			  result_error = {array} [0]
			  result_next = {array} [0]
			  result_total = {array} [0]
		*/
        if (!empty($dataToUpdate) && is_array($dataToUpdate)) {

            $result = $this->sendBatchQuery($dataToUpdate);

            // Timestamp update
            $this->updateToDb($productId, $result['result']);
        }
	}

	public function deleteRecordInDB($fields = [])
    {
        $where = $this->prepareFields($fields);
        $sql = 'DELETE FROM `b24_product` WHERE ' . $where;
        $this->db->query($sql);
    }

	public function sendBatchQuery( array $dataToB24 ){
        $this->load->model('setting/setting');
        $b24_setting = $this->model_setting_setting->getSetting('b24_hook_key');
		$this->b24->setFields($b24_setting);
		
		$params = [
			'type' => 'batch',
			'params' => [
			    'cmd' => $dataToB24
            ]
		];

		$result = $this->b24->callHook($params);

		if (!empty($result['result']['result_error'])) {
			trigger_error('Ошибка при запросе sendBatchQuery ' . print_r($result['result']['result_error'], 1));
		}

		return $result;
	}

	public function deleteProduct($productId) {
        $this->load->model('setting/setting');
        $b24_setting = $this->model_setting_setting->getSetting('b24_hook_key');
		$this->b24->setFields($b24_setting);
		
		$productRows = $this->getById($productId);

        $this->deleteRecordInDB(['oc_product_id' => $productId]);

		$deleteData = [];
		foreach ( $productRows as $productRow )
		{
			$deleteData[] = 'crm.product.delete?id=' . $productRow['b24_product_id'];
		}

		$params = [
			'type' => 'batch',
			'params' => ['cmd' => $deleteData]
		];

		$result = $this->b24->callHook($params);

		if ( !empty($result['result_error']) )
		{
			trigger_error('Ошибка при удалении товаров в Б24: ' . print_r($result['result_error'], 1), E_USER_WARNING);
		}
	}

	public function prepareDataToB24($productId)
	{
		$this->load->model('catalog/product');
		$this->load->model('catalog/manufacturer');
		$this->load->model('localisation/stock_status');
		$this->load->model('extension/module/b24_category');

        $lastId = 0;

        $minMaxParentIds = $this->model_extension_module_b24_category->getMinMaxParentIds();

        $minParentId = min($minMaxParentIds);

        if (isset($minParentId['parent_id']) && is_numeric($minParentId['parent_id']) && 0 <= $minParentId['parent_id']) {
            $fields = $this->model_extension_module_b24_category->getById($minParentId['parent_id']);

            $lastId = isset($fields['b24_category_id']) ? $fields['b24_category_id'] : 0;
        }

		// Category GET
		$categoryList = $this->model_catalog_product->getProductCategories($productId);

		if (empty($categoryList)) {
            $categoryList[0] = 0;
        }

		$b24Category = $this->model_extension_module_b24_category->getById($categoryList[0]);
		
		$parentId = isset($b24Category['b24_category_id']) ? abs($b24Category['b24_category_id']) : $lastId;
		// Category GET

		// DATA
		$product = $this->model_catalog_product->getProduct($productId);

		$productPrice = $product['price'];
		$productModel = !empty($product['model']) ? $product['model'] . ' | ' : '';
        $productSKU = !empty($product['sku']) ? $product['sku'] . ' | ' : '';

        $description = $this->model_catalog_product->getProductDescriptions($productId);
		$productDescription = strip_tags(html_entity_decode($description[1]['description']));

		$manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['manufacturer_id']);
		$manufacturerName = isset($manufacturer['name']) ? $manufacturer['name'] : '';

		$stockStatus = $this->model_localisation_stock_status->getStockStatus($product['stock_status_id']);
		$productLocation = isset($stockStatus['name']) ? $stockStatus['name'] : '';
		// DATA

		// Option SIZE
		$optionList = $this->model_catalog_product->getProductOptions($productId);
		$productSize = [];
        $optionName0 = '';

		if (isset($optionList[0])) {
            $productSize = $optionList[0];
            $optionName0 = $productSize['name'];
        }

		// HACK For product without size
		if (empty($productSize['product_option_value'])) {
			$productSize['product_option_value'][] = [
				'quantity' => $product['quantity'],
				'name' => $this->PRODUCT_WO_SIZE,
			];
		}
		// HACK For product without size

		// Option SIZE
		
		$dataToB24 = [];
		foreach ($productSize['product_option_value'] as $sizeOption) {
			$productPrice = $product['price'];
			$quantity = $sizeOption['quantity'];

            $productOptionValue = [];
			if (isset($sizeOption['product_option_value_id'])) {
                $productOptionValue = $this->model_catalog_product->getProductOptionValue($productId, $sizeOption['product_option_value_id']);
            }

			$sizeName = isset($productOptionValue['name']) ? $productOptionValue['name'] : $sizeOption['name'];

            $optionName1 = $optionName0 . (!empty($sizeName) ? ': ' . $sizeName : '');

			$productName = rtrim(html_entity_decode(trim($description[1]['name']) . ' | ' . $productModel . $productSKU . $optionName1), ' | ');

			$productPrice = isset($sizeOption['price']) && abs($sizeOption['price']) > 0 ? ($sizeOption['price_prefix'] == '+') ? ($productPrice + $sizeOption['price']) : ($productPrice - $sizeOption['price']) : $productPrice;

			$newData = [
				"NAME" => $productName,
				'SECTION_ID' => $parentId,
				'DESCRIPTION' => $productDescription,
				"CURRENCY_ID" => $this->config->get('config_currency'),
				"PRICE" => $productPrice,
				"MEASURE" => 9,
				$this->PROPERTY_QUANTITY => $quantity,
				$this->PROPERTY_MODEL => rtrim($productModel , ' | '),
				$this->PROPERTY_SIZE => $sizeName,
				$this->PROPERTY_OC_ID => $productId,
				$this->PROPERTY_WAREHOUSE_STATUS => $productLocation,
				$this->PROPERTY_MANUFACTURER => $manufacturerName,
                'ACTIVE' => $product['status'] == 1 ? 'Y' : 'N'
			];
			$dataToB24[$sizeName] = $newData;
		}

		return $dataToB24;
	}
	
	public function insertToDB($tableName, array $fields)
	{
		$db = $this->db;
		
		$sql = 'INSERT INTO ' . $tableName . ' SET ' . $this->prepareFields($fields) . ';';

		$db->query($sql);

		$lastId = $this->db->getLastId();
		return $lastId;
	}
	
	public function prepareFields(array $fields)
	{
		$sql = '';
		$index = 0;
		foreach ( $fields as $columnName => $value )
		{
			$glue = $index === 0 ? ' ' : ', ';
			$sql .= $glue . "`$columnName`" . ' = "' . $this->db->escape($value) . '"';
			$index++;
		}

		return $sql;
	}

	public function getById($product_id)
	{
		$db = $this->db;
		$sql = 'Select * from b24_product WHERE oc_product_id = "' . $db->escape($product_id) . '"';
		$query = $db->query($sql);

		return $query->rows;
	}

	public function getList(array $filter)
	{
		$db = $this->db;
		$where = ' WHERE ';
		$index = 0;
		foreach ( $filter as $columnName => $value )
		{
			$glue = $index === 0 ? ' ' : ' AND ';
			$where .= $glue . $columnName. ' = "' . $db->escape($value) . '"';
			$index++;
		}

		$sql = 'Select * from b24_product ' . $where . ';';
		$query = $db->query($sql);

		return $query->rows;
	}

    public function getProductRows()
    {
        $db = $this->db;
        $sql = 'SELECT * FROM `b24_product` HAVING COUNT(*) >= 1';
        $query = $db->query($sql);

        if (0 < $query->num_rows) {
            return true;
        }

        return false;
    }
}
