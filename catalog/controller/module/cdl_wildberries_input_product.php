<?php
class ControllerModuleCdlWildberriesInputProduct extends Controller
{
  public function token()
  {
    if ($this->request->get['token'] == $this->config->get('cdl_wildberries_general_token')) {
      $inputJSON = file_get_contents('php://input');
			$data = json_decode($inputJSON, true);
      switch ($this->request->get['zapros']) {
        case 'download_products':
          $dp = $this->downloadProducts($data);
          echo json_encode($dp);
          break;
        case 'download_attributes':
          $dp = $this->downloadAttributes($data);
          echo json_encode($dp);
          break;

        default:
          // code...
          break;
      }
    }
  }

  // download_attributes
  private function downloadAttributes($attributes)
  {
    $this->load->model('module/cdl_wildberries');
    foreach ($attributes as $attribute) {
      $this->model_module_cdl_wildberries->saveWbAttributes($attribute['id'], $attribute['category'], $attribute['number'], $attribute['required'], $attribute['only_dictionary'], $attribute['type'], $attribute['units'], $attribute['dictionary'], $attribute['nomenclature'], $attribute['nomenclature_variation'], $attribute['description']);
    }
    return 'Успешно';
  }

  //download_products
  private function downloadProducts($wb_products)
  {
    if (!empty($wb_products)) {
      $this->load->model('module/cdl_wildberries');
      $added = 0;
      $add = 0;
      $add_code = '';
      $skip = 0;
      $skip_code = '';
      $wb_categorys = $this->config->get('cdl_wildberries_category');
      foreach ($wb_products as $wb_product) {
        foreach ($wb_product['sizes'][0]['skus'] as $barcode) {
          $search_product = $this->model_module_cdl_wildberries->getProductByBarcode($barcode);
          if (!empty($search_product)) {
            $added++;
            continue 2;
          }
        }
        $search_db = $wb_product['vendorCode'];
        $search_shop_product = $this->model_module_cdl_wildberries->getShopProductByVendorCode($search_db);
        // WB склеивал артикулы для номенклатур
        if (empty($search_shop_product)) {
          $vcode_str = strlen($search_db);
          if (($vcode_str % 2) == 0){
            $vcode_str /= 2;
            $search_db = substr($search_db, 0, $vcode_str);
            $search_shop_product = $this->model_module_cdl_wildberries->getShopProductByVendorCode($search_db);
          }
        }
        if (empty($search_shop_product)) {
          $skip++;
          $skip_code .= ' ' . $search_db . ' ';
        } else {
          $product_id = $search_shop_product[0]['product_id'];
          $model = $search_shop_product[0]['model'];
          $sku = $search_shop_product[0]['sku'];
          $product_categorys = $this->model_module_cdl_wildberries->getProductCategory($product_id);
          $shop_category_id = 0;
          if (!empty($product_categorys)) {
            foreach ($product_categorys as $product_category) {
              foreach ($wb_categorys as $wb_category) {
                if ($wb_category['shop'] == $product_category['category_id']) {
                  $shop_category_id = $wb_category['shop'];
                }
              }
            }
          }
          $sub_category = $wb_product['object'];
          $get_category = $this->model_module_cdl_wildberries->getCategoryBySub($sub_category);
          $category = $get_category[0]['category'];
          $imt_id = '';
          $chrt_id = '';
          $nm_id = $wb_product['nmID'];
          $this->model_module_cdl_wildberries->saveExportProduct($product_id, $model, $sku, $imt_id, $nm_id, $barcode, $status = 'Создан', $error = '', $category, $sub_category, $shop_category_id, $chrt_id);
          $add++;
          $add_code .= ' ' . $search_db . ' ';
        }
      }
      if (!empty($add_code)) {
        $this->log('Связка товаров. Связаны: ' . $add . ' шт: ' . $add_code);
      }
      if (!empty($skip_code)) {
        $this->log('Связка товаров. Не найдены в Opencart ' . $skip . ' шт: ' . $skip_code);
      }
      if (!empty($add_code) || !empty($skip_code)) {
        return 'Из них уже были в модуле ' . $added . ' шт. Связаны: ' . $add . ' шт. Не найдены в Opencart ' . $skip . ' шт. Подробнее в логе модуля.<br />';
      } else {
        return 'Из них уже были в модуле ' . $added . ' шт.<br />';
      }
    }
  }

  // Лог
  private function log($msg) {
		$fp = fopen(DIR_LOGS . 'cdl_wildberries.log', 'a');
		fwrite($fp, date('Y-m-d H:i:s').': '.str_replace("\n", '', $msg)."\n");
		fclose($fp);
	}
}
