<?php
class ModelModuleB24Product extends Model{
	public $PRODUCT_WO_SIZE = 0;

	public function addToDB($productId, array $result) {
		if (empty($result['result']) || (int) $productId <= 0 ) {
			trigger_error('Пустой массив или ИД продукта при добавлении товаров из Б24 '
				. print_r($productId, 1) . print_r($result, 1),
				E_USER_WARNING);
		}

		foreach ($result['result'] as $option => $b24Id) {
			// При импорте всех товаров, имя другое
			if (stripos($option, ';') > 0) {
				list($productId, $option) = explode(';', $option);
			}
			
			$fields = ['oc_product_id' => $productId, 'b24_product_id' => $b24Id, 'option' => $option];
			$this->insertToDB('b24_product', $fields);
		}
	}
	
	public function updateToDb($productId, array $result) {
		if (empty($result['result']) || (int) $productId <= 0) {
			trigger_error('Пустой массив или ИД продукта при обновлении товаров из Б24 '
				. print_r($productId, 1) . print_r($result, 1),
				E_USER_WARNING);
		}

		foreach ($result['result'] as $option => $isUpdated) {
			// При импорте всех товаров, имя другое
			if (stripos($option, ';') > 0) {
				list($productId, $option) = explode(';', $option);
			}
			
			$this->db->query("UPDATE b24_product SET `date_update` = NOW() WHERE `option` = ".$option." AND `oc_product_id` = ".$productId."");
		}
	}

	public function addProductBatch( $productId, array $dataToB24 ) {
		foreach ($dataToB24 as $option => $newData) {
			$dataToAdd[$option] = 'crm.product.add?'. http_build_query(['fields' => $newData]);
		}

		$result = $this->sendBatchQuery($dataToAdd);
		$this->addToDB($productId, $result['result']);
	}

	public function addProductsBatch() {
		$productID = $this->getProductForSync();
		if(empty($productID)){
			return;
		}
		$build = [];
		foreach($productID as $value) {
            $dataToAdd = $this->prepareDataToB24($value);
				if (1 < count($dataToAdd)) {
					//опции
					foreach ($dataToAdd as $key => $fields) {
						$build['cmd'][$value . '##' . $key] = 'crm.product.add?'. http_build_query(['fields' => $fields]);
					}
				} else {
					// товар
					foreach ($dataToAdd as $fields) {
						$build['cmd'][$value] = 'crm.product.add?'. http_build_query(['fields' => $fields]);
					}
				}	
		}
		$productChunk = array_chunk($build['cmd'], 50, true);
		foreach ($productChunk as $cmd) {
			$params = [
				'type' => 'batch',
					'params' => [
						'cmd' => $cmd
					] 
			];
			$result = $this->b24->callHook($params);
			if (!empty($result['result']['result_error'])) {
				$this->log->write(print_r($result['result']['result_error'],true));
			} else {
				$ids = $result['result']['result']; 				
				$this->addBatchToDB($ids);
			}
		}
		$return_array = [
				'add' => !empty($result['result']['result']) ? count($result['result']['result']) : 0,
		];
		return $return_array;
    }
	
	public function updateProductsBatch() {
		// Получить oc_id товаров для обновления
		$updateProduct = $this->getUpdateIDs(); 
		if(!empty($updateProduct)){
			foreach ($updateProduct as $value) {
				//$upB24product['cmd'][$id] = 'crm.product.delete?id=' . $id;
				$productID = $this->getByB24Id($value);
				$dataToAdd = $this->prepareDataToB24($productID);
				if (1 < count($dataToAdd)) {
					// если есть опции
					foreach ($dataToAdd as $key => $fields) {
						$upB24product['cmd'][$value . '##' . $key] = 'crm.product.update?'. http_build_query(['ID' => $value,'fields' => $fields]);
					}
				} else {
					// товар
					foreach ($dataToAdd as $fields) {
						$upB24product['cmd'][$value] = 'crm.product.update?'. http_build_query(['ID' => $value,'fields' => $fields]);
					}
				}		
			}
			$productChunk = array_chunk($upB24product['cmd'], 50, true);
			foreach ($productChunk as $cmd) {
				$params = [
					'type' => 'batch',
						'params' => [
							'cmd' => $cmd
						] 
				];
				$resultUpdate = $this->b24->callHook($params);
				if (!empty($resultUpdate['result']['result_error'])) {
					$this->log->write(print_r($resultUpdate['result']['result_error'],true));
				} else {                    
					$this->updateBatchToDB($updateProduct);
				}
			}
		} 
	
		// Получить массив товаров и опций для удаления
		$dellOption = $this->getDellOption();
		if(!empty($dellOption)){
			foreach ($dellOption as $id) {
				$clearB24Option['cmd'][$id] = 'crm.product.delete?id=' . $id;
			}
			$productChunk = array_chunk($clearB24Option['cmd'], 50, true);
			foreach ($productChunk as $cmd) {
				$params = [
					'type' => 'batch',
						'params' => [
							'cmd' => $cmd
						] 
				];
				$resultDell = $this->b24->callHook($params);
				if(!empty($resultDell['result']['result'])){
					foreach ($resultDell['result']['result'] as $key => $value) {
						$this->deleteRecordInDB(['b24_product_id' => $key]);
					}
				}
			}
		}
		
		$return_array = [
				'add' => !empty($resultAdd['result']['result']) ? count($resultAdd['result']['result']) : 0,
				'update' => !empty($resultUpdate['result']['result']) ? count($resultUpdate['result']['result']) : 0,
				'delete' => !empty($resultDell['result']['result']) ? count($resultDell['result']['result']) : 0,
			];
		return $return_array;
	}

	public function editProductBatch($productId, array $dataToB24) {
		if (abs($productId) <= 0) {
			$this->log->write(print_r('ID товара не определено',true));
		}

        $countToB24 = count($dataToB24);
        $listB24 = $this->getList(['oc_product_id' => $productId]);
        $countListB24 = count($listB24);

        // добавление, обновление
        if ($countToB24 >= $countListB24) {
            foreach ($dataToB24 as $optionName => $newData) {
                $b24ProductList = $this->getList(['oc_product_id' => $productId, 'option' => $optionName]);
                $b24Product = isset($b24ProductList[0]) ? $b24ProductList[0] : [];

                // добавление
                if (empty($b24Product['b24_product_id'])) {
                    $dataToAdd[$optionName] = $newData;

                    $b24ProductList = $this->getList(['oc_product_id' => $productId, 'option' => $this->PRODUCT_WO_SIZE]);
                    if (!empty($b24ProductList)) {
                        $dataToDelete[$this->PRODUCT_WO_SIZE] = 'crm.product.delete?id=' . $b24ProductList[0]['b24_product_id'];
                        $this->deleteRecordInDB(['oc_product_id' => $productId]);
                    } else {
                        if (isset($listB24[0])) {
                            $dataToDelete[$listB24[0]['option']] = 'crm.product.delete?id=' . $listB24[0]['b24_product_id'];
                            $this->deleteRecordInDB(['oc_product_id' => $productId]);
                        }
                    }
                } else {
                // обновление
                    $dataToUpdate[$optionName] = 'crm.product.update?id=' . $b24Product['b24_product_id'] . '&' . http_build_query(['fields' => $newData]);
                }
            }
        } else {
            // удаление
            foreach ($dataToB24 as $optionName => $newData) {
                foreach ($listB24 as $key => $fields) {
                    if (0 == $countListB24) {
                        $b24_product_id = $productId;
                    } else {
                        $b24_product_id = $fields['b24_product_id'];
                    }

                    if ($optionName == $fields['option']) {
                        $dataToUpdate[$optionName] = 'crm.product.update?id=' . $b24_product_id . '&' . http_build_query(['fields' => $newData]);
                        unset($listB24[$key]);
                        break;
                    }
                }
            }

            foreach ($listB24 as $key => $fields) {
                if (!isset($fields['b24_product_id'])) continue;

                $b24_product_id = $fields['b24_product_id'];
                $dataToDelete[$fields['option']] = 'crm.product.delete?id=' . $b24_product_id;
                $this->deleteRecordInDB(['b24_product_id' => $b24_product_id]);
            }
        }

        if (!empty($dataToDelete) && is_array($dataToDelete)) {
            $this->sendBatchQuery($dataToDelete);
        }

		if (!empty($dataToAdd) && is_array($dataToAdd)) {
			$this->addProductBatch( $productId, $dataToAdd );
		}

        if (!empty($dataToUpdate) && is_array($dataToUpdate)) {
            $result = $this->sendBatchQuery($dataToUpdate);
            $this->updateToDb($productId, $result['result']);
        }
	}

	public function deleteProduct($productId) {	
        $this->deleteRecordInDB(['oc_product_id' => $productId]);
		$productRows = $this->getById($productId);
		$deleteData = [];
		foreach ( $productRows as $productRow ) {
			$deleteData[] = 'crm.product.delete?id=' . $productRow['b24_product_id'];
		}

		$params = [
			'type' => 'batch',
			'params' => [
				'cmd' => $deleteData,
			]
		];

		$result = $this->b24->callHook($params);

		if ( !empty($result['result_error']) ) {
			$this->log->write(print_r($result['result_error'],true));
		}
	}

	public function prepareDataToB24($productId) {
		$this->load->model('catalog/product');
		$this->load->model('catalog/manufacturer');
		$this->load->model('localisation/stock_status');
		$this->load->model('module/b24_category');
		
		// Получить ID корневой категории в битрикс
		$rootB24Id =  $this->model_module_b24_category->getById(0)['b24_category_id'];
		// Получить главную категорию
		$mainCategory = $this->model_catalog_product->getProductMainCategoryId($productId);
		// Получить все категории товара
		$categoryList = $this->model_catalog_product->getProductCategories($productId);
		if (empty($categoryList)) {
            $categoryList[0] = 0;
        }
		if ($mainCategory <= 0){
			$b24Category = $this->model_module_b24_category->getById($categoryList[0]);
		} else {
			$b24Category = $this->model_module_b24_category->getById($mainCategory);
		}
		$sectionId = isset($b24Category['b24_category_id']) ? abs($b24Category['b24_category_id']) : $rootB24Id;
		
		$curency = $this->currency->getValue($this->config->get('config_currency'));
		$lang = $this->config->get('config_language_id');

		$product = $this->model_catalog_product->getProduct($productId);

		$productPrice = $product['price']*$curency;
		$productModel = !empty($product['model']) ? $product['model'] . ' | ' : '';
        $SKU = !empty($product['sku']) ? $product['sku'] : '';

        $description = $this->model_catalog_product->getProductDescriptions($productId);
		$productDescription = strip_tags(html_entity_decode($description[$lang]['description']));

		$manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['manufacturer_id']);
		$manufacturerName = isset($manufacturer['name']) ? $manufacturer['name'] : '';

		$stockStatus = $this->model_localisation_stock_status->getStockStatus($product['stock_status_id']);
		$productLocation = isset($stockStatus['name']) ? $stockStatus['name'] : '';

		// Option product
		$optionList = $this->model_catalog_product->getProductOptions($productId);
		$product_option = isset($optionList[0]) ? $optionList[0] : '';
        $optionName = '';

		if (isset($optionList[0])) {
            $optionName = $optionList[0]['name']; // Название опции
        }

		if (empty($product_option['product_option_value'])) {
			$product_option['product_option_value'][] = [
				'quantity' => $product['quantity'],
				'name' => $this->PRODUCT_WO_SIZE,
			];
		}
		
		$dataToB24 = [];
		foreach ($product_option['product_option_value'] as $option) {
			$productPrice = $product['price'];
			$quantity = $option['quantity'];

			$productOptionValue = [];
			if (isset($option['product_option_value_id'])) {
                $productOptionValue = $this->model_catalog_product->getProductOptionValue($productId, $option['product_option_value_id']);
            }
				
			$option_value_name = isset($productOptionValue['name']) ? $productOptionValue['name'] : $optionName;

			$product_option_value_name = $optionName . (!empty($option_value_name) ? ': ' . $option_value_name : '');
				
			$productName = rtrim(html_entity_decode(trim($description[$lang]['name']) . ' | ' . $productModel . $product_option_value_name), ' | ');

			$productPrice = isset($option['price']) && abs($option['price']) > 0 ? ($option['price_prefix'] == '+') ? ($productPrice + $option['price']) : ($productPrice - $option['price']) : $productPrice;
			
			$props = $this->config->get('b24_productprops');

			$params = [
					'NAME' => $productName,
					'SECTION_ID' => $sectionId,
					'DESCRIPTION' => $productDescription,
					'CURRENCY_ID' => $this->config->get('config_currency'),
					'PRICE' => $productPrice,
					'MEASURE' => 9,
					'XML_ID' => $productId,
					'PROPERTY_'.$props['PROPERTY_QUANTITY'] => $quantity,
					'PROPERTY_'.$props['PROPERTY_MODEL'] => rtrim($productModel , ' | '),
					'PROPERTY_'.$props['PROPERTY_SKU'] => $SKU,
					'PROPERTY_'.$props['PROPERTY_OPTION'] => $option_value_name,
					'PROPERTY_'.$props['PROPERTY_WAREHOUSE_STATUS'] => $productLocation,
					'PROPERTY_'.$props['PROPERTY_MANUFACTURER'] => $manufacturerName,
					'ACTIVE' => $product['status'] == 1 ? 'Y' : 'N'
				];
				if (isset($option['product_option_value_id'])) {
					$dataToB24[$option['product_option_value_id']] = $params;
				} else {
					$dataToB24[0] = $params;
				}
			}
	
		return $dataToB24;
	}
	
	public function insertToDB($tableName, array $fields) {
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

	public function getById($product_id) {
		$db = $this->db;
		$sql = 'Select * from b24_product WHERE oc_product_id = "' . $db->escape($product_id) . '"';
		$query = $db->query($sql);

		return $query->rows;
	}

	public function getList(array $filter) {
		$db = $this->db;
		$where = ' WHERE ';
		$index = 0;
		foreach ( $filter as $columnName => $value )
		{
			$glue = $index === 0 ? ' ' : ' AND ';
			$where .= $glue ."`$columnName`" . ' = "' . $db->escape($value) . '"';
			$index++;
		}

		$sql = 'Select * from b24_product ' . $where . ';';
		$query = $db->query($sql);

		return $query->rows;
	}

    public function getProductRows() {
        $db = $this->db;
        $sql = 'SELECT * FROM `b24_product` HAVING COUNT(*) >= 1';
        $query = $db->query($sql);

        if (0 < $query->num_rows) {
            return true;
        }

        return false;
    }
	
	public function getProductForSync(){
        $lang = $this->config->get('config_language_id');
		$productId = $this->db->query("SELECT p.product_id
							FROM ". DB_PREFIX ."product p
							LEFT JOIN ". DB_PREFIX ."product_description pd ON (p.product_id = pd.product_id) 
							LEFT JOIN b24_product b24 ON (p.product_id = b24.oc_product_id)
							WHERE b24.oc_product_id IS NULL AND pd.product_id IS NOT NULL AND pd.language_id = ".$lang." LIMIT 1000;"); 
		if (1 > $productId->num_rows) {
            return;
        }
		foreach ($productId->rows as $key => $value) {
			$add_product[] = $value['product_id'];
		}
		return !empty($add_product) ? $add_product : 0;
	}
	
	// Получаем товары для обновления
	public function getUpdateIDs(){
		$lang = $this->config->get('config_language_id');
		$b24Id = $this->db->query("SELECT b24.b24_product_id FROM b24_product b24
							LEFT JOIN ". DB_PREFIX ."product p ON (p.product_id = b24.oc_product_id) 
							LEFT JOIN ". DB_PREFIX ."product_description pd ON (p.product_id = pd.product_id) 
							WHERE p.date_modified > b24.date_update AND pd.language_id = ".$lang.";");
		
		if ($b24Id->num_rows < 1) {
            return;
        }
		foreach ($b24Id->rows as $key => $value) {
			$update_product[] = $value['b24_product_id'];
		}
		return !empty($update_product) ? $update_product : 0;
	}
	
	// получаем список b24_id (товары и опции) которые нужно удалить из битрикс
	public function getDellOption(){
		$b24Id_option = $this->db->query("SELECT `b24_product_id` FROM b24_product WHERE `option` NOT IN (SELECT product_option_value_id FROM ". DB_PREFIX ."product_option_value) AND `option` != 0;");
		$b24Id_product = $this->db->query("SELECT `b24_product_id` FROM b24_product WHERE `oc_product_id` NOT IN (SELECT product_id FROM ". DB_PREFIX ."product);");
		
		if ($b24Id_option->num_rows > 0) {
			foreach ($b24Id_option->rows as $key => $value) {
				$dell[] = $value['b24_product_id'];
			}
        }
		if ($b24Id_product->num_rows > 0) {
			foreach ($b24Id_product->rows as $key => $value) {
				$dell[] = $value['b24_product_id'];
			}
        }
		return !empty($dell) ? $dell : 0;
	}
	
	public function getByB24Id($b24id) {
		$db = $this->db;
		$sql = 'Select oc_product_id from b24_product WHERE b24_product_id = "' . $db->escape($b24id) . '"';
		$query = $db->query($sql);
		
		foreach ($query->rows as $row) {
			$id = $row['oc_product_id'];
		}
		return $id;
	}
	
	// обновление даты в b24_product
	public function updateBatchToDB($updateId){
		foreach ($updateId as $id) {
			$this->db->query("UPDATE b24_product SET date_update=NOW() WHERE b24_product_id=" . $this->db->escape($id) . ";");
		}
    }
	
	// Отправка информации в битрикс24
	public function sendBatchQuery( array $dataToB24 ){	
		$params = [
			'type' => 'batch',
			'params' => [
			    'cmd' => $dataToB24
            ]
		];

		$result = $this->b24->callHook($params);

		if (!empty($result['result']['result_error'])) {
			$this->log->write(print_r($result['result']['result_error'],true));
		}

		return $result;
	}
	
	// Удаление записи из БД
	public function deleteRecordInDB($fields = []) {
        $where = $this->prepareFields($fields);
        $sql = 'DELETE FROM `b24_product` WHERE ' . $where;
        $this->db->query($sql);
    }
	
	// пакетная вставка в БД товаров	
	public function addBatchToDB($batchToDB = array()){
		$sql = 'INSERT INTO `b24_product` (`oc_product_id`, `b24_product_id`, `option`, `date_update`) VALUES';
        $values = '';
        foreach ($batchToDB as $key => $b24_id) {
            $dataToDB = explode('##', $key);

            $option = isset($dataToDB[1]) ? $dataToDB[1] : 0;

            $values .= ' (' . $dataToDB[0] . ',' . $b24_id .',"' . $option . '", NOW()),';
        }

        $query = $sql . rtrim($values, ',');

        $this->db->query($query);
    }
}
