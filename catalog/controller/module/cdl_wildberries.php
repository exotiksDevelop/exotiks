<?php
/*
* OpenCart 2.1
* @author Dmitri Cheremisin
*/
spl_autoload_register('autoload');
function autoload($nameClass)
{
  require DIR_SYSTEM . 'library/cdl/class.'. $nameClass . '.php';
}

class ControllerModuleCdlWildberries extends Controller
{
  private $api = 'https://suppliers-api.wildberries.ru/api/';
  private $api_content = 'https://suppliers-api.wildberries.ru/content/';
  private $api_card = 'https://suppliers-api.wildberries.ru/card/';
  private $api_public = 'https://suppliers-api.wildberries.ru/public/api/v1/';
  private $api_ms = 'https://online.moysklad.ru/api/remap/1.2/';

  // Проверка пароля
  public function pass()
  {
    if (isset($this->request->post['pass']) && $this->request->post['pass'] == $this->config->get('cdl_wildberries_pass')) {
      switch ($this->request->get['request']) {
        case 'export':
          $this->export($this->request->get['filter_category'], $this->request->get['start'], $this->request->get['limit'], $this->request->get['manufacturer']);
          break;
        case 'getparentlist':
          $this->getParentList();
          break;
        case 'downloadattributes':
          $q = $this->downloadAttributes();
          echo $q;
          break;
        case 'checknoimt':
          $this->checkErrorProduct();
          $this->checkNoImt();
          break;
        case 'warehouse':
          $warehouses = $this->warehouse();
          echo $warehouses;
          break;
        case 'updateproducts':
          if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $product_id) {
              $update = $this->updateProduct($product_id);
              echo $update;
            }
          }
          break;
        case 'download_products':
          if (!empty($this->config->get('cdl_wildberries_card_version'))) {
            $download = $this->downloadProducts();
          } else {
            $download = $this->downloadProductsNew();
          }
          echo $download;
          break;
        case 'checkimg':
          if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $product_id) {
              $this->checkImg($product_id);
            }
          }
          break;
        default:
          false;
          break;
      }
    }
    if (isset($this->request->get['pass']) && $this->request->get['pass'] == $this->config->get('cdl_wildberries_pass')) {
      switch ($this->request->get['request']) {
        case 'price':
          $this->priceUpdate();
          break;
        case 'stock':
          $this->updateStock();
          break;
        case 'statusms':
          $this->statusIdMs();
          break;
        case 'inputidms':
          $this->inputIdMs();
          break;
        case 'webhookcreate':
          $this->webhookCreate();
          break;
        case 'webhookdelete':
          $this->webhookDelete();
          break;
        default:
          false;
          break;
      }
    }
  }

  // Экспорт товаров
  private function export($filter_category, $start, $limit, $filter_manufacturer)
  {
    header('Content-Type: text/html; charset=utf-8');
    $url = $this->api_content . 'v1/cards/upload';
    echo '=== ' . $this->config->get('cdl_wildberries_version') . ' - экспорт товаров ===<br />';
    echo '=== ' . $url . ' ===<br /><br />';

    $this->load->model('catalog/product');
    $this->load->model('tool/image');
    $this->load->model('module/cdl_wildberries');

    // язык по-умолчанию
    $this->load->model('setting/setting');
    $language_config = $this->model_setting_setting->getSetting('config', $this->config->get('config_store_id'));
    $language = $language_config['config_language'];
    $language_id = $this->model_module_cdl_wildberries->getLanguage($language);

    if (!empty($this->config->get('cdl_wildberries_manufacturer_stop'))) {
      $stop_manufacturer = implode(",", $this->config->get('cdl_wildberries_manufacturer_stop'));
    } else {
      $stop_manufacturer = 0;
    }

    $shop_product_input = array(
      'weight'  => 'Вес',
      'height'  => 'Высота',
      'length0' => 'Длина',
      'width'   => 'Ширина',
      'sku'     => 'Артикул',
      'model'   => 'Модель',
      'mpn'     => 'MPN',
      'isbn'    => 'ISBN',
      'ean'     => 'EAN',
      'jan'     => 'JAN',
      'upc'     => 'UPC',
      'name'    => 'Наименование'
    );

    $wb_categorys = $this->config->get('cdl_wildberries_category');

    foreach ($wb_categorys as $wb_category) {
      if ($filter_category != 'Все категории') {
        if ($wb_category['wb'] != $filter_category) {
          continue;
        }
      }

      if (isset($wb_category['stop'])) {
        echo 'Категория ' . $wb_category['wb'] . ' на стопе<br />';
        continue;
      }
      // Фильтр товаров из категории
      if ($wb_category['filter_select'] != 'all') {
        if ($wb_category['filter_select'] == 'attr') {
          $filter_export_id = $wb_category['filter-attr-id'];
          $filter_export_value = $wb_category['filter-value'];
        }
      }

      $shop_category_id = $wb_category['shop'];
      $sub_category = $wb_category['wb'];
      $get_parent_category = $this->model_module_cdl_wildberries->getCategoryBySub($sub_category);
      $parent_category = $get_parent_category[0]['category'];

      if (!empty($this->config->get('cdl_wildberries_blacklist'))) {
        $blacklist = $this->config->get('cdl_wildberries_blacklist');
      } else {
        $blacklist = array();
      }

      $filter_data = array(
        'filter_category_id' => $shop_category_id,
        'filter_stop_manufacturer' => $stop_manufacturer,
        'filter_manufacturer_id' => $filter_manufacturer,
        'blacklist' => $blacklist,
        'filter_sub_category'=> false, //выводить товары из подкатегорий
        'start'	=> $start,
        'limit' => $limit
      );

      $products = $this->model_module_cdl_wildberries->getProducts($filter_data);

      if (empty($products)) {
        continue;
      } else {
        if ($this->config->get('cdl_wildberries_barcode') == 'gener' && empty($this->config->get('cdl_wildberries_test_export'))) {
          $wb_barcodes = $this->getBarcodes(count($products));
          $barcodes_stop = array();
        }
      }
      // Массив с товарами
      $data = array();
      $table_product_export = array();

      foreach ($products as $product) {

        if ($wb_category['filter_select'] != 'all') {
          if ($wb_category['filter_select'] == 'attr') {
            $product_filter = $this->model_module_cdl_wildberries->getProductAttribute($product['product_id'], $filter_export_id, $language_id);
            if (!empty($product_filter) && $product_filter[0]['text'] != $filter_export_value) {
              continue;
            }
          }
        }

        if (empty($product['image'])) {
          echo 'Товар ' . $vendor_code . ' не создан. Отсутствует изображение<br />';
          $this->log('Товар ' . $vendor_code . ' не создан. Отсутствует изображение.');
          continue;
        }

        // vendorCode
        $vendor_code = $product[$this->config->get('cdl_wildberries_relations')];
        if (empty($vendor_code)) {
          echo 'Товар ' . $product['name'] . ' не создан. Не заполнен ' . $this->config->get('cdl_wildberries_relations') . '<br />';
          $this->log('Товар ' . $product['name'] . ' не создан. Не заполнен ' . $this->config->get('cdl_wildberries_relations'));
          continue;
        }

        // Размеры
        if ($product['weight_class_id'] == $this->config->get('cdl_wildberries_weight')) {
          if ($product['weight'] == 0) {
            $weight = $wb_category['weight'];
          } else {
            $weight = $product['weight'];
          }
        } else {
          if ($product['weight'] == 0) {
            $weight = $wb_category['weight'];
          } else {
            $weight = $product['weight'] * 1000;
          }
        }

        if ($product['length_class_id'] == $this->config->get('cdl_wildberries_length')) {
          if ($product['length'] == 0) {
            $length = $wb_category['length'];
          } else {
            $length = round($product['length'], PHP_ROUND_HALF_UP);
          }
          if ($product['width'] == 0) {
            $width = $wb_category['width'];
          } else {
            $width = round($product['width'], PHP_ROUND_HALF_UP);
          }
          if ($product['height'] == 0) {
            $height = $wb_category['height'];
          } else {
            $height = round($product['height'], PHP_ROUND_HALF_UP);
          }
        } else {
          if ($product['length'] == 0) {
            $length = $wb_category['length'];
          } else {
            $length = $product['length'] / 10;
          }
          if ($product['width'] == 0) {
            $width = $wb_category['width'];
          } else {
            $width = $product['width'] / 10;
          }
          if ($product['height'] == 0) {
            $height = $wb_category['height'];
          } else {
            $height = $product['height'] / 10;
          }
        }

        // Бренд
        $wb_manufacturer = $this->model_module_cdl_wildberries->getManufacture($product['manufacturer_id']);

        if (!empty($wb_manufacturer)) {
          $brend = $wb_manufacturer[0]['dictionary_value'];
          $country = $wb_manufacturer[0]['country'];
        } else {
          echo 'Товар ' . $vendor_code . ' не создан, т.к. не сопоставлен производитель<br />';
          $this->log('Товар ' . $vendor_code . ' не создан, т.к. не сопоставлен производитель.');
          continue;
        }

        // Штрих-код
        if ($this->config->get('cdl_wildberries_barcode') == 'gener') {
          if (!empty($wb_barcodes)) {
            foreach ($wb_barcodes as $wb_barcode) {
              if (!in_array($wb_barcode, $barcodes_stop)) {
                $barcodes_stop[] = $wb_barcode;
                $barcode = $wb_barcode;
                break;
              }
            }
          } elseif ($this->config->get('cdl_wildberries_test_export')) {
            $barcode = 'Будет получен при экспорте';
          } else {
            echo 'Товар ' . $vendor_code . ' не создан: не получен ШК из WB<br />';
            $this->log('Товар ' . $vendor_code . ' не создан: не получен ШК из WB');
            continue;
          }
        } else {
          $barcode = $product[$this->config->get('cdl_wildberries_barcode')];
        }

        // Атрибуты из системы
        $attribute_export = array(
          array('Предмет' => $sub_category),
          array('Cтрана производства' => array($country)),
          array('Бренд' => $brend)
        );

        // Описание
        if ($this->config->get('cdl_wildberries_description')) {
          $description = $this->description($product['description']);
        } else {
          $description = ' ';
        }

        if ($this->config->get('cdl_wildberries_attributes_description')) {
          $attributes_in_description = ' ' . $product['name'];
          $attributes_product = $this->model_catalog_product->getProductAttributes($product['product_id']);
          if (!empty($attributes_product)) {
            foreach ($attributes_product as $attribute_product) {
              foreach ($attribute_product['attribute'] as $attribute) {
                $attribute_name = html_entity_decode($attribute['name'], ENT_NOQUOTES);
                $attribute_text = html_entity_decode($attribute['text'], ENT_NOQUOTES);
                $attributes_in_description .= ' | ' . str_replace('&quot;', '\'', $attribute_name) . ': ' . str_replace('&quot;', '\'', $attribute_text);
              }
            }
          }

          $attributes_in_description = mb_strimwidth($attributes_in_description, 0, 4990, '... ');
          $width_description = mb_strwidth($description);
          $width_attributes_in_description = mb_strwidth($attributes_in_description);
          $width_summ_description = $width_description + $width_attributes_in_description;
          if ($width_summ_description > 5000) {
            $width_delete = $width_summ_description - 4990;
            $width_delete = $width_description - $width_delete;
            if ($width_attributes_in_description >= 4990) {
              $description = $attributes_in_description;
            } else {
              $description = mb_strimwidth($description, 0, $width_delete, '... ');
              $description = $description . $attributes_in_description;
            }
          } else {
            $description = $description . $attributes_in_description;
          }
        }
        $attribute_export[] = array('Описание' => $description);

        // Сопоставленные атрибуты
        $attributes_to_shop = $this->model_module_cdl_wildberries->getAttributesToShop($sub_category, $shop_category_id);

        $en_size = '';
        $ru_size = '';

        foreach ($attributes_to_shop as $attr_to_shop) {
          if (empty($attr_to_shop['shop_id']) && empty($attr_to_shop['value'])) {
            $this->log('Товар ' . $vendor_code . ' не указан атрибут магазина для атрибута WB ' . $attr_to_shop['type'] . '. Снимите галку, либо заполните значение.');
            echo 'Товар ' . $vendor_code . ' не указан атрибут магазина для атрибута WB ' . $attr_to_shop['type'] . '. Снимите галку, либо заполните значение.<br />';
          } else {
            if ($attr_to_shop['is_defined']) {
              $pre_value = explode(',', $attr_to_shop['value']);
              $value = array();
              foreach ($pre_value as $pre_val) {
                if ($attr_to_shop['number']) {
                  $value[] = (float)$pre_val;
                } else {
                  $value[] = (string)$pre_val;
                }
              }
              if ($attr_to_shop['type'] == 'Наименование') {
                $name = $this->nameProduct($value[0], $brend);
                $attribute_export[] = array($attr_to_shop['type'] => $name);
                continue;
              }
              if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
                $ru_size = $value[0];
                continue;
              }
              if (empty($attr_to_shop['units'])) {
                $attribute_export[] = array($attr_to_shop['type'] => $value);
              } else {
                $attribute_export[] = array($attr_to_shop['type'] => $value[0]);
              }
            } else {
              if (array_key_exists($attr_to_shop['shop_id'], $shop_product_input)) {
                $value = array();
                switch ($attr_to_shop['shop_id']) {
                  case 'weight':
                    $pre_value = $weight;
                    break;
                  case 'height':
                    $pre_value = $height;
                    break;
                  case 'length0':
                    $pre_value = $length;
                    break;
                  case 'width':
                    $pre_value = $width;
                    break;
                  default:
                    $pre_value = $product[$attr_to_shop['shop_id']];
                    break;
                }
                if ($attr_to_shop['type'] == 'Наименование') {
                  $name = $this->nameProduct($pre_value, $brend);
                  $attribute_export[] = array($attr_to_shop['type'] => $name);
                  continue;
                }
                if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
                  $ru_size = (string)$pre_value;
                  continue;
                }
                if (!empty($attr_to_shop['action'])) {
                  $pre_value = $this->action($pre_value, $attr_to_shop['action'], $attr_to_shop['action_value']);
                }
                if (!empty($attr_to_shop['units'])) {
                  $value = (float)$pre_value;
                } else {
                  $value[] = (string)$pre_value;
                }
                $attribute_export[] = array($attr_to_shop['type'] => $value);
                continue;
              }

              $product_attr = $this->model_module_cdl_wildberries->getProductAttribute($product['product_id'], $attr_to_shop['shop_id'], $language_id);

              if (!empty($product_attr)) {
                // Значения атрибутов магазина и WB из справочника
                if ($attr_to_shop['dictionary'] || $attr_to_shop['only_dictionary']) {
                  $dictionary_value = $this->model_module_cdl_wildberries->getDictionarExport($sub_category, $shop_category_id, $attr_to_shop['shop_id'], $product_attr[0]['text'], $attr_to_shop['type']);
                  $value = array();
                  if (!empty($dictionary_value)) {
                    $pre_value = explode('^', $dictionary_value[0]['dictionary_value']);
                    foreach ($pre_value as $pre_val) {
                      if ($attr_to_shop['number']) {
                        $pre_val = (float)$pre_val;
                      } else {
                        $pre_val = htmlspecialchars_decode($pre_val, ENT_COMPAT);
                      }
                      if (!empty($attr_to_shop['units'])) {
                        $value = (float)$pre_val;
                      } else {
                        $value[] = (string)$pre_val;
                      }
                    }
                  } elseif (empty($dictionary_value) && empty($attr_to_shop['only_dictionary'])) {
                    // Не сопоставленные значения атрибутов не с обязательным справочником берем напрямую из карточки
                    $pre_value = explode('^', $product_attr[0]['text']);
                    foreach ($pre_value as $pre_val) {
                      if ($attr_to_shop['number']) {
                        $pre_val = preg_replace("/[^0-9\.\,]/", '', $pre_val);
                        $pre_val = str_replace(',', '.', $pre_val);
                        $pre_val = (float)$pre_val;
                      } else {
                        $pre_val = htmlspecialchars_decode($pre_val, ENT_COMPAT);
                      }
                      if (!empty($attr_to_shop['action'])) {
                        $pre_val = $this->action($pre_val, $attr_to_shop['action'], $attr_to_shop['action_value']);
                      }
                      if (!empty($attr_to_shop['units'])) {
                        $value = (float)$pre_val;
                      } else {
                        $value[] = (string)$pre_val;
                      }
                    }
                  }
                  $attribute_export[] = array($attr_to_shop['type'] => $value);

                } elseif (empty($attr_to_shop['only_dictionary'] && empty($dictionary_value))) {
                  // Значения атрибутов магазина без справочника
                  $value = array();
                  if (!empty($this->config->get('cdl_wildberries_delimiter'))) {
                    $delimiter = $this->config->get('cdl_wildberries_delimiter');
                    $pre_value = explode($delimiter, $product_attr[0]['text']);
                    foreach ($pre_value as $pre_val) {
                      if ($attr_to_shop['number']) {
                        $pre_val = preg_replace("/[^0-9\.\,]/", '', $pre_val);
                        $pre_val = str_replace(',', '.', $pre_val);
                        $pre_val = (float)$pre_val;
                      } else {
                        $pre_val = $pre_val;
                      }
                      if (!empty($attr_to_shop['action'])) {
                        $pre_val = $this->action($pre_val, $attr_to_shop['action'], $attr_to_shop['action_value']);
                      }
                      if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
                        $ru_size = (string)$product_attr[0]['text'];
                        continue;
                      }
                      if (!empty($attr_to_shop['units'])) {
                        $value = (float)$pre_val;
                      } else {
                        $value[] = (string)$pre_val;
                      }
                    }
                  } else {
                    if ($attr_to_shop['number']) {
                      $p_value = preg_replace("/[^0-9\.\,]/", '', $product_attr[0]['text']);
                      $p_value = str_replace(',', '.', $p_value);
                      $p_value = (float)$p_value;
                      if (!empty($attr_to_shop['action'])) {
                        $p_value = $this->action($p_value, $attr_to_shop['action'], $attr_to_shop['action_value']);
                      }
                      if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
                        $ru_size = (string)$p_value;
                        continue;
                      }
                      if (!empty($attr_to_shop['units'])) {
                        $value = (float)$p_value;
                      } else {
                        $value[] = (string)$p_value;
                      }
                    } else {
                      if (!empty($attr_to_shop['action'])) {
                        $p_value = $this->action($product_attr[0]['text'], $attr_to_shop['action'], $attr_to_shop['action_value']);
                        $product_attr[0]['text'] = $p_value;
                      }
                      if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
                        $ru_size = (string)$product_attr[0]['text'];
                        continue;
                      }
                      $value[] = $product_attr[0]['text'];
                    }
                  }
                  $attribute_export[] = array($attr_to_shop['type'] => $value);
                }
              }
            }
          }
        }

        // Цена
        if ($product['special']) {
          $price = $this->price($product['special'], $product['category_id'], $sub_category, $product['manufacturer_id'], $product['product_id']);
        } else {
          $price = $this->price($product['price'], $product['category_id'], $sub_category, $product['manufacturer_id'], $product['product_id']);
        }

        $sizes = array(
          'price' => (int)$price,
          'skus' => array($barcode),
          'techSize' => $en_size,
          'wbSize' => $ru_size
        );

        // Карточка товара
        $data[] = array(array(
          'vendorCode' => $vendor_code,
          'characteristics' => $attribute_export,
          'sizes' => array($sizes)
        ));

        // Товары попавшие в экспорт
        $table_product_export[] = array(
          'product_id' => $product['product_id'],
          'model' => $product['model'],
          'sku' => $product['sku'],
          'barcode' => $barcode
        );
      }
      // Конец итерации массива товаров

      if (empty($data)) {
        continue;
      }

      if ($this->config->get('cdl_wildberries_test_export')) {
        echo '=== Запрос не будет отправлен в Wildberries. Чтобы выгрузить товары отключите тест экспорта ===<br /><br />';
        echo '<pre>';
        print_r(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo '<br />';
      } else {
        $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
        $status = 'Создается';
        if (!empty($response['error'])) {
          echo 'Экспорт товаров не удался из-за следующих ошибок:<br />' . $response['errorText'] . '<br />' . $response['additionalErrors'] . '<br />';
          $this->log('Экспорт товаров: ' . $response['errorText'] . $response['additionalErrors']);
        } else {
          foreach ($table_product_export as $table) {
            $this->model_module_cdl_wildberries->saveExportProduct($table['product_id'], $table['model'], $table['sku'], $imt_id = '', $nm_id = '', $table['barcode'], $status, $error = '', $parent_category, $sub_category, $shop_category_id, $chrt_id = '');
          }
          echo '+ Товары из категории ' . $sub_category . ' выгружены!<br />';
        }
        if ($this->config->get('cdl_wildberries_log')) {
          $this->log('Экспорт товаров запрос: ' . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
          $this->log('Экспорт товаров ответ: ' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
      }
    }
    if (!$this->config->get('cdl_wildberries_test_export')) {
      echo '<br />=== Перезагрузите страницу и не забудьте обновить статусы товаров! ===<br />';
    }
  }

  // Подготовка наименования
  private function nameProduct($name, $manufacturer)
  {
    $name = $this->description($name);
    $name = str_replace($manufacturer, '', $name);
    $name_strlen = mb_strlen($name,'UTF-8');
    if ($name_strlen > 40) {
      if (!empty($this->config->get('cdl_wildberries_name_crop'))) {
        $name = mb_strimwidth($name, 0, 39, '');
      }
    }
    return $name;
  }

  // Получить ШК
  private function getBarcodes($quantity)
  {
    $url = $this->api_content . 'v1/barcodes';
    $data = array(
      'count' => (int)$quantity
    );
    $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
    return $response['data'];
  }

  // Подготовка текста
  private function description($str)
  {
    $del = array("\r\n", "\n", "\r", "：", "`", "«", "»", "°", "`", "…", "ø", "●", "Ø", "®");
    $str = strip_tags(html_entity_decode($str, ENT_NOQUOTES), '</p> <br> <style> </style> <li> <td>');
    $str = preg_replace('/\s?<style>*?>.*?<\/style>\s?/', ' ', $str);
    $str = preg_replace('/<li(?:([\'"]).*?\1|.)*?>/', '<li>', $str);
    $str = preg_replace('/<td(?:([\'"]).*?\1|.)*?>/', '<td>', $str);
    $str = str_replace('&quot;','"', $str);
    $str = str_replace('=','-', $str);
    $str = str_replace(array('&ndash;', '–'), '-', $str);
    $str = str_replace('*','x', $str);
    $str = str_replace(array('&nbsp;', '&bull;', '<li>', '<td>'), ' ', $str);
    $str = str_replace($del, '', $str);
    $str = str_replace(array('</p>', "<br>", '<br />', '</li>', '</td>'), " ", $str);
    $str = preg_replace('/[\t]+/','', $str);
    $str = trim($str);
    $str = mb_strimwidth($str, 0, 990, '... ');
    return $str;
  }

  // Обновить товар
  private function updateProduct($product_id)
  {
    $this->load->model('catalog/product');
    $this->load->model('tool/image');
    $this->load->model('module/cdl_wildberries');
    $this->load->model('setting/setting');

    $error = '';
    $language_config = $this->model_setting_setting->getSetting('config', $this->config->get('config_store_id'));
    $language = $language_config['config_language'];
    $language_id = $this->model_module_cdl_wildberries->getLanguage($language);

    $product = $this->model_module_cdl_wildberries->getProduct($product_id);
    $imported_product = $this->model_module_cdl_wildberries->getImportedProduct($product_id);
    $wb_categorys = $this->config->get('cdl_wildberries_category');

    $shop_product_input = array(
      'weight'  => 'Вес',
      'height'  => 'Высота',
      'length0' => 'Длина',
      'width'   => 'Ширина',
      'sku'     => 'Артикул',
      'model'   => 'Модель',
      'mpn'     => 'MPN',
      'isbn'    => 'ISBN',
      'ean'     => 'EAN',
      'jan'     => 'JAN',
      'upc'     => 'UPC',
      'name'    => 'Наименование'
    );

    $sub_category = $imported_product[0]['sub_category'];
    $parent_category = $imported_product[0]['category'];
    $shop_category_id = $imported_product[0]['category_shop'];

    foreach ($wb_categorys as $wb_category) {
      if ($wb_category['wb'] == $sub_category) {
        // $shop_category_id = $wb_category['shop'];
        if ($wb_category['filter_select'] != 'all') {
          if ($wb_category['filter_select'] == 'attr') {
            $filter_export_id = $wb_category['filter-attr-id'];
            $filter_export_value = $wb_category['filter-value'];
            $product_filter = $this->model_module_cdl_wildberries->getProductAttribute($product['product_id'], $filter_export_id, $language_id);
            if (!empty($product_filter) && $product_filter[0]['text'] != $filter_export_value) {
              continue;
            }
          }
        }
        break;
      }
    }

    // vendorCode
    $vendor_code = $product[$this->config->get('cdl_wildberries_relations')];
    if (empty($vendor_code)) {
    $error .= 'Товар ' . $product['name'] . ' не обновлен. Не заполнен ' . $this->config->get('cdl_wildberries_relations') . ' в карточке товара<br />';
    }

    // Размеры
    if ($product['weight_class_id'] == $this->config->get('cdl_wildberries_weight')) {
      if ($product['weight'] == 0) {
        $weight = $wb_category['weight'];
      } else {
        $weight = $product['weight'];
      }
    } else {
      if ($product['weight'] == 0) {
        $weight = $wb_category['weight'];
      } else {
        $weight = $product['weight'] * 1000;
      }
    }

    if ($product['length_class_id'] == $this->config->get('cdl_wildberries_length')) {
      if ($product['length'] == 0) {
        $length = $wb_category['length'];
      } else {
        $length = round($product['length'], PHP_ROUND_HALF_UP);
      }
      if ($product['width'] == 0) {
        $width = $wb_category['width'];
      } else {
        $width = round($product['width'], PHP_ROUND_HALF_UP);
      }
      if ($product['height'] == 0) {
        $height = $wb_category['height'];
      } else {
        $height = round($product['height'], PHP_ROUND_HALF_UP);
      }
    } else {
      if ($product['length'] == 0) {
        $length = $wb_category['length'];
      } else {
        $length = $product['length'] / 10;
      }
      if ($product['width'] == 0) {
        $width = $wb_category['width'];
      } else {
        $width = $product['width'] / 10;
      }
      if ($product['height'] == 0) {
        $height = $wb_category['height'];
      } else {
        $height = $product['height'] / 10;
      }
    }

    // Бренд
    $wb_manufacturer = $this->model_module_cdl_wildberries->getManufacture($product['manufacturer_id']);

    if (!empty($wb_manufacturer)) {
      $brend = $wb_manufacturer[0]['dictionary_value'];
      $country = $wb_manufacturer[0]['country'];
    } else {
      echo 'Товар ' . $vendor_code . ' не создан, т.к. не сопоставлен производитель<br />';
      $this->log('Товар ' . $vendor_code . ' не создан, т.к. не сопоставлен производитель.');
    }

    // Атрибуты из системы
    $attribute_export = array(
      array('Предмет' => $sub_category),
      array('Cтрана производства' => array($country)),
      array('Бренд' => $brend)
    );

    // Описание
    if ($this->config->get('cdl_wildberries_description')) {
      $description = $this->description($product['description']);
    } else {
      $description = ' ';
    }

    if ($this->config->get('cdl_wildberries_attributes_description')) {
      $attributes_in_description = ' ' . $product['name'];
      $attributes_product = $this->model_catalog_product->getProductAttributes($product['product_id']);
      if (!empty($attributes_product)) {
        foreach ($attributes_product as $attribute_product) {
          foreach ($attribute_product['attribute'] as $attribute) {
            $attribute_name = html_entity_decode($attribute['name'], ENT_NOQUOTES);
            $attribute_text = html_entity_decode($attribute['text'], ENT_NOQUOTES);
            $attributes_in_description .= ' | ' . str_replace('&quot;', '\'', $attribute_name) . ': ' . str_replace('&quot;', '\'', $attribute_text);
          }
        }
      }

      $attributes_in_description = mb_strimwidth($attributes_in_description, 0, 990, '... ');
      $width_description = mb_strwidth($description);
      $width_attributes_in_description = mb_strwidth($attributes_in_description);
      $width_summ_description = $width_description + $width_attributes_in_description;
      if ($width_summ_description > 1000) {
        $width_delete = $width_summ_description - 990;
        $width_delete = $width_description - $width_delete;
        if ($width_attributes_in_description >= 990) {
          $description = $attributes_in_description;
        } else {
          $description = mb_strimwidth($description, 0, $width_delete, '... ');
          $description = $description . $attributes_in_description;
        }
      } else {
        $description = $description . $attributes_in_description;
      }
    }
    $attribute_export[] = array('Описание' => $description);

    // Сопоставленные атрибуты
    $attributes_to_shop = $this->model_module_cdl_wildberries->getAttributesToShop($sub_category, $shop_category_id);

    $en_size = '';
    $ru_size = '';

    foreach ($attributes_to_shop as $attr_to_shop) {
      if (empty($attr_to_shop['shop_id']) && empty($attr_to_shop['value'])) {
        $this->log('Товар ' . $vendor_code . ' не указан атрибут магазина для атрибута WB ' . $attr_to_shop['type'] . '. Снимите галку, либо заполните значение.');
        echo 'Товар ' . $vendor_code . ' не указан атрибут магазина для атрибута WB ' . $attr_to_shop['type'] . '. Снимите галку, либо заполните значение.<br />';
      } else {
        if ($attr_to_shop['is_defined']) {
          $pre_value = explode(',', $attr_to_shop['value']);
          $value = array();
          foreach ($pre_value as $pre_val) {
            if ($attr_to_shop['number']) {
              $value[] = (float)$pre_val;
            } else {
              $value[] = (string)$pre_val;
            }
          }
          if ($attr_to_shop['type'] == 'Наименование') {
            $name = $this->nameProduct($value[0], $brend);
            $attribute_export[] = array($attr_to_shop['type'] => $name);
            continue;
          }
          if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
            $ru_size = $value[0];
            continue;
          }
          if (empty($attr_to_shop['units'])) {
            $attribute_export[] = array($attr_to_shop['type'] => $value);
          } else {
            $attribute_export[] = array($attr_to_shop['type'] => $value[0]);
          }
        } else {
          if (array_key_exists($attr_to_shop['shop_id'], $shop_product_input)) {
            $value = array();
            switch ($attr_to_shop['shop_id']) {
              case 'weight':
                $pre_value = $weight;
                break;
              case 'height':
                $pre_value = $height;
                break;
              case 'length0':
                $pre_value = $length;
                break;
              case 'width':
                $pre_value = $width;
                break;
              default:
                $pre_value = $product[$attr_to_shop['shop_id']];
                break;
            }
            if ($attr_to_shop['type'] == 'Наименование') {
              $name = $this->nameProduct($pre_value, $brend);
              $attribute_export[] = array($attr_to_shop['type'] => $name);
              continue;
            }
            if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
              $ru_size = (string)$pre_value;
              continue;
            }
            if (!empty($attr_to_shop['action'])) {
              $pre_value = $this->action($pre_value, $attr_to_shop['action'], $attr_to_shop['action_value']);
            }
            if (!empty($attr_to_shop['units'])) {
              $value = (float)$pre_value;
            } else {
              $value[] = (string)$pre_value;
            }
            $attribute_export[] = array($attr_to_shop['type'] => $value);
            continue;
          }

          $product_attr = $this->model_module_cdl_wildberries->getProductAttribute($product['product_id'], $attr_to_shop['shop_id'], $language_id);

          if (!empty($product_attr)) {
            // Значения атрибутов магазина и WB из справочника
            if ($attr_to_shop['dictionary'] || $attr_to_shop['only_dictionary']) {
              $dictionary_value = $this->model_module_cdl_wildberries->getDictionarExport($sub_category, $shop_category_id, $attr_to_shop['shop_id'], $product_attr[0]['text'], $attr_to_shop['type']);
              $value = array();
              if (!empty($dictionary_value)) {
                $pre_value = explode('^', $dictionary_value[0]['dictionary_value']);
                foreach ($pre_value as $pre_val) {
                  if ($attr_to_shop['number']) {
                    $pre_val = (float)$pre_val;
                  } else {
                    $pre_val = htmlspecialchars_decode($pre_val, ENT_COMPAT);
                  }
                  if (!empty($attr_to_shop['units'])) {
                    $value = (float)$pre_val;
                  } else {
                    $value[] = (string)$pre_val;
                  }
                }
              } elseif (empty($dictionary_value) && empty($attr_to_shop['only_dictionary'])) {
                // Не сопоставленные значения атрибутов не с обязательным справочником берем напрямую из карточки
                $pre_value = explode('^', $product_attr[0]['text']);
                foreach ($pre_value as $pre_val) {
                  if ($attr_to_shop['number']) {
                    $pre_val = preg_replace("/[^0-9\.\,]/", '', $pre_val);
                    $pre_val = str_replace(',', '.', $pre_val);
                    $pre_val = (float)$pre_val;
                  } else {
                    $pre_val = htmlspecialchars_decode($pre_val, ENT_COMPAT);
                  }
                  if (!empty($attr_to_shop['action'])) {
                    $pre_val = $this->action($pre_val, $attr_to_shop['action'], $attr_to_shop['action_value']);
                  }
                  if (!empty($attr_to_shop['units'])) {
                    $value = (float)$pre_val;
                  } else {
                    $value[] = (string)$pre_val;
                  }
                }
              }
              $attribute_export[] = array($attr_to_shop['type'] => $value);

            } elseif (empty($attr_to_shop['only_dictionary'] && empty($dictionary_value))) {
              // Значения атрибутов магазина без справочника
              $value = array();
              if (!empty($this->config->get('cdl_wildberries_delimiter'))) {
                $delimiter = $this->config->get('cdl_wildberries_delimiter');
                $pre_value = explode($delimiter, $product_attr[0]['text']);
                foreach ($pre_value as $pre_val) {
                  if ($attr_to_shop['number']) {
                    $pre_val = preg_replace("/[^0-9\.\,]/", '', $pre_val);
                    $pre_val = str_replace(',', '.', $pre_val);
                    $pre_val = (float)$pre_val;
                  } else {
                    $pre_val = $pre_val;
                  }
                  if (!empty($attr_to_shop['action'])) {
                    $pre_val = $this->action($pre_val, $attr_to_shop['action'], $attr_to_shop['action_value']);
                  }
                  if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
                    $ru_size = (string)$product_attr[0]['text'];
                    continue;
                  }
                  if (!empty($attr_to_shop['units'])) {
                    $value = (float)$pre_val;
                  } else {
                    $value[] = (string)$pre_val;
                  }
                }
              } else {
                if ($attr_to_shop['number']) {
                  $p_value = preg_replace("/[^0-9\.\,]/", '', $product_attr[0]['text']);
                  $p_value = str_replace(',', '.', $p_value);
                  $p_value = (float)$p_value;
                  if (!empty($attr_to_shop['action'])) {
                    $p_value = $this->action($p_value, $attr_to_shop['action'], $attr_to_shop['action_value']);
                  }
                  if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
                    $ru_size = (string)$p_value;
                    continue;
                  }
                  if (!empty($attr_to_shop['units'])) {
                    $value = (float)$p_value;
                  } else {
                    $value[] = (string)$p_value;
                  }
                } else {
                  if (!empty($attr_to_shop['action'])) {
                    $p_value = $this->action($product_attr[0]['text'], $attr_to_shop['action'], $attr_to_shop['action_value']);
                    $product_attr[0]['text'] = $p_value;
                  }
                  if ($attr_to_shop['type'] == 'Рос. размер' || $attr_to_shop['type'] == 'Размер') {
                    $ru_size = (string)$product_attr[0]['text'];
                    continue;
                  }
                  $value[] = $product_attr[0]['text'];
                }
              }
              $attribute_export[] = array($attr_to_shop['type'] => $value);
            }
          }
        }
      }
    }

    // Цена
    if ($product['special']) {
      $price = $this->price($product['special'], $product['category_id'], $sub_category, $product['manufacturer_id'], $product['product_id']);
    } else {
      $price = $this->price($product['price'], $product['category_id'], $sub_category, $product['manufacturer_id'], $product['product_id']);
    }

    $imt_id = $imported_product[0]['imt_id'];
    $nm_id = $imported_product[0]['nm_id'];
    $barcode = $imported_product[0]['barcode'];
    $chrt_id = $imported_product[0]['chrt_id'];

    $sizes = array(
      'chrtID' => (int)$chrt_id,
      'price' => (int)$price,
      'skus' => array($barcode),
      'techSize' => $en_size,
      'wbSize' => $ru_size
    );

    // Карточка товара
    $data = array(array(
      'nmID' => (int)$nm_id,
      'vendorCode' => $vendor_code,
      'characteristics' => $attribute_export,
      'sizes' => array($sizes)
    ));

    $url = $this->api_content . 'v1/cards/update';
    $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);

    if (!empty($response['error'])) {
      $error .= $response['errorText'] . '<br />';
    } else {
      $this->model_module_cdl_wildberries->updateNewProduct($vendor_code, $imt_id, $nm_id, $barcode, $status = 'Создается', $error, $chrt_id);
    }

    if ($this->config->get('cdl_wildberries_log')) {
      $this->log('Обновление товара запрос: ' . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
      $this->log('Обновление товара ответ: ' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
      // ответ пустой, если нет ошибок
    }
    return 'OK';
  }

  // Действие со значением атрибута
  private function action($value, $action, $action_value)
  {
    switch ($action) {
      case '/':
        $value /= $action_value;
        break;
      case '*':
        $value *= $action_value;
        break;
      case '-':
        $value -= $action_value;
        break;
      case '+':
        $value += $action_value;
        break;

      default:
        false;
        break;
    }
    return (int)$value;
  }

  // Проверить товары после экспорта
  private function checkNoImt()
  {
    $this->load->model('module/cdl_wildberries');
    $start = 0;
    $relations = array('mpn', 'upc', 'ean', 'jan', 'isbn');
    do {
      $filter_data = array(
        'start'	=> $start,
        'limit' => 100
      );
      $db_products = $this->model_module_cdl_wildberries->getNoImtProduct($filter_data);

      if (!empty($db_products)) {
        $search = array();
        foreach ($db_products as $db_product) {
          if (in_array($this->config->get('cdl_wildberries_relations'), $relations)) {
            $relations_search = $this->model_module_cdl_wildberries->getProduct($db_product['product_id']);
            $vendor_code = $relations_search[$this->config->get('cdl_wildberries_relations')];
          } else {
            $vendor_code = $db_product[$this->config->get('cdl_wildberries_relations')];
          }
          $search[] = $vendor_code;
        }
        $data = array('vendorCodes' => $search);
        $wb_products = $this->cardsFilter($data);

        if (!empty($wb_products['data'])) {
          foreach ($wb_products['data'] as $product_wb) {
            if (empty($product_wb['mediaFiles'])) {
              $status = 'Без фото';
              $errors = 'Товар создан. Теперь загрузите фото.';
            } else {
              $status = 'Создан';
              $errors = '';
            }
            if (empty($product_wb['imtID']) || empty($product_wb['nmID'])) {
              $this->log('Обновить статусы товара: ошибка не получена номенклатура, пропускаю');
            } else {
              $this->model_module_cdl_wildberries->updateNewProduct($product_wb['vendorCode'], $product_wb['imtID'], $product_wb['nmID'], $product_wb['sizes'][0]['skus'][0], $status, $errors, $product_wb['sizes'][0]['chrtID']);
            }
          }
        }
        $start += 100;
      }
    } while (count($db_products) == 100);
  }

  // Загрузить в таблицу товары из WB OLD!
  private function downloadProducts()
  {
    $this->load->model('module/cdl_wildberries');
    $offset = 0;
    $add = 0;
    $add_code = '';
    $skip = 0;
    $skip_code = '';
    $skip_barcode = '';
    do {
      $id = md5(HTTPS_SERVER . date("YmdHis"));
      $data = array(
        'id' => $id,
        'jsonrpc' => '2.0',
        'params' => array(
          'filter' => array(
            'find' => array(),
            'order' => array(
                'column' => 'createdAt',
                'order' => 'asc'
            )
          ),
          'query' => array(
              'limit' => 100,
              'offset' => $offset
          )
        )
      );
      $wb_products = $this->searchProduct($data);
      $wb_categorys = $this->config->get('cdl_wildberries_category');
      $offset += 100;
      if (!empty($wb_products['result']['cards'])) {
        foreach ($wb_products['result']['cards'] as $created_card) {
          $imt_id = $created_card['imtId'];
          $category = $created_card['parent'];
          $sub_category = $created_card['object'];
          if (!empty($imt_id)) {
            foreach ($created_card['nomenclatures'] as $nomenclature) {
              foreach ($nomenclature['variations'] as $variation) {
                foreach ($variation['barcodes'] as $barcode) {
                  $search_product = $this->model_module_cdl_wildberries->getProductByBarcode($barcode);
                  if (!empty($search_product)) {
                    continue 2;
                  }
                }

                if (count($nomenclature['variations']) == 1) {
                  $search_db = $nomenclature['vendorCode'];
                  $barcode = $nomenclature['variations'][0]['barcodes'][0];
                  $search_shop_product = $this->model_module_cdl_wildberries->getShopProductByVendorCode($search_db);
                } else {
                  foreach ($variation['barcodes'] as $barcode) {
                    $search_shop_product = $this->model_module_cdl_wildberries->getShopProductByBarcode($barcode);
                    if (!empty($search_shop_product)) {
                      if (count($search_shop_product) > 1) {
                        $skip++;
                        $skip_barcode .= ' ' . $barcode . ': ' . serialize($search_shop_product) . ' ';
                        unset($search_shop_product);
                        continue;
                      } else {
                        break;
                      }
                    }
                  }
                }

                if (!empty($search_shop_product[0])) {
                  $product_id = $search_shop_product[0]['product_id'];
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
                  $model = $search_shop_product[0]['model'];
                  $sku = $search_shop_product[0]['sku'];
                  $nm_id = $nomenclature['nmId'];
                  $chrt_id = $variation['chrtId'];
                  $this->model_module_cdl_wildberries->saveExportProduct($product_id, $model, $sku, $imt_id, $nm_id, $barcode, $status = 'Создан', $error = '', $category, $sub_category, $shop_category_id, $chrt_id);
                  $add++;
                  $add_code .= ' ' . $search_db . ' ';
                } else {
                  $skip++;
                  $skip_code .= ' ' . $search_db . ' ';
                }
              }
            }
          }
        }
      }
    } while (count($wb_products['result']['cards']) == 100);
    $msg = 'Связка ранее созданных товаров. Связано: ' . $add . '. Не найдено в Opencart: ' . $skip . '. Больше информации в логе модуля.';
    if (!empty($add_code)) {
      $this->log('Связка товаров. Связаны: ' . $add_code);
    }
    if (!empty($skip_code)) {
      $this->log('Связка товаров. Не найдены в Opencart: ' . $skip_code);
    }
    if (!empty($skip_barcode)) {
      $this->log('Связка товаров. Следующие ШК из ВБ принадлежат нескольким товарам в Opencart: ' . $skip_code);
    }
    return $msg;
  }

  // Загрузить в таблицу товары из WB NEW!
  private function downloadProductsNew()
  {
    $cdl = new WbCdlRequest;
    $request = $cdl->cdlRequest('download_products', $this->config->get('cdl_wildberries_general_token'));
    return $request;
  }

  // Найти товары без фото
  private function checkImg($product_id)
  {
    $this->load->model('module/cdl_wildberries');
    $images = $this->model_module_cdl_wildberries->getImages($product_id);
    $load_images = array();
    if (!empty($images)) {
      foreach ($images as $image) {
        if (!in_array($image['general'], $load_images)) {
          $load_images[] = HTTPS_SERVER. 'image/' . $image['general'];
        }
        if (!empty($image['image']) && !in_array($image['image'], $load_images)) {
          $load_images[] = HTTPS_SERVER. 'image/' . $image['image'];
        }
      }
      $product = $this->model_module_cdl_wildberries->getImportedProduct($product_id);
      $vendor_code = $product[0][$this->config->get('cdl_wildberries_relations')];
      $data = array('vendorCode' => $vendor_code, 'data' => $load_images);
      $url = $this->api_content . 'v1/media/save';
      $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
      if (empty($response['error'])) {
        $this->model_module_cdl_wildberries->updateStatusProduct('Создан', $product_id);
      } else {
        $this->log(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
      }
      if ($this->config->get('cdl_wildberries_log')) {
        $this->log('Обновление фото запрос: ' . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->log('Обновление фото ответ: ' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        // ответ пустой, если нет ошибок
      }
    }
  }

  // Поиск товара в WB
  private function searchProduct($data)
  {
    $url = $this->api_card . 'list';
    $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
    return $response;
  }

  // Поиск товара в WB NEW
  private function cardsFilter($data)
  {
    $url = $this->api_content . 'v1/cards/filter';
    $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
    return $response;
  }

  private function checkErrorProduct()
  {
    $products = $this->getErrorProduct();
    if (!empty($products['data'])) {
      $this->load->model('module/cdl_wildberries');
      $errors = '';
      foreach ($products['data'] as $product) {
        if (!empty($product['errors'])) {
          foreach ($product['errors'] as $error) {
            $errors .= $error . '<br />';
          }
        }
        $this->model_module_cdl_wildberries->updateNewProduct($product['vendorCode'], $imt_id = '', $nm_id = '', $barcode = '', $status = 'Не создан', $errors, $chrt_id = '');
      }
    }
  }

  // Проверить товары в черновиках
  private function getErrorProduct()
  {
    $url = $this->api_content . 'v1/cards/error/list';
    $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data);
    return $response;
  }

  private function getProductByImtId($imt_id)
  {
    $url = $this->api_card . 'cardByImtID';
    $id = md5(HTTPS_SERVER . date("YmdHis"));
    $data = array(
      'id' => $id,
      'jsonrpc' => '2.0',
      'params' => array(
        'imtID' => (int)$imt_id,
      )
    );
    $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
    return $response;
  }

  // Скачать категории WB
  private function getParentList()
  {
    $url = $this->api_content . 'v1/object/all?top=10000';
    $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');
    if (!empty($response['data'])) {
      $this->load->model('module/cdl_wildberries');
      $this->model_module_cdl_wildberries->deleteCategory();
      foreach ($response['data'] as $cat) {
        if (!empty($cat['isVisible'])) {
          $category = $cat['parentName'];
          $sub_category = $cat['objectName'];
          $this->model_module_cdl_wildberries->saveCategory($category, $sub_category);
        } else {
          $this->log('Загрузка категорий: категория ' . $cat['name'] . ' не активна - пропускаю');
        }
      }
    }
    $this->session->data['success'] = 'Категории успешно загружены';
  }

  // Загрузка атрибутов
  private function downloadAttributes()
  {
    $this->load->model('module/cdl_wildberries');
    $categorys = $this->config->get('cdl_wildberries_category');
    $send_categorys = array();
    foreach ($categorys as $category) {
      if (!empty($category['wb'])) {
        $check = $this->model_module_cdl_wildberries->getAttrWbBySubCategory($category['wb']);
        if (empty($check)) {
          $send_categorys[] = $category['wb'];
        }
      }
    }
    if (!empty($send_categorys)) {
      $cdl = new WbCdlRequest;
      $request = $cdl->cdlRequest('download_attributes', $this->config->get('cdl_wildberries_general_token'), $send_categorys);
      return $request;
    }
  }

  // Автозаполнение из справочника атрибутов
  public function autocompleteDictionary()
  {
    if (isset($this->request->post['pass']) && $this->request->post['pass'] == $this->config->get('cdl_wildberries_pass')) {
      $url = $this->api_content .'v1/directory/' . $this->request->get['dictionary'];
      if (!empty($this->request->get['dictionary']) && $this->request->get['dictionary'] == 'tnved') {
        $url .= '?objectName=' . $this->request->get['objectName'];
      } else {
        $url .= '?top=300';
      }
      if (!empty($this->request->get['pattern'])) {
        $url .= '&pattern=' . rawurlencode($this->request->get['pattern']);
      }
      $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');
      $output = array();
      if (!empty($response['data'])) {
        foreach ($response['data'] as $dictionary) {
          switch ($this->request->get['dictionary']) {
            case 'tnved':
              $output[] = array($dictionary['tnvedName']);
              break;
            case 'brands':
              $output[] = array($dictionary);
              break;
            case 'seasons':
              $output[] = array($dictionary);
              break;
            case 'kinds':
              $output[] = array($dictionary);
              break;
            case 'consists':
              $output[] = array($dictionary['name']);
              break;
            case 'contents':
              $output[] = array($dictionary['name']);
              break;
            case 'collections':
              $output[] = array($dictionary['name']);
              break;

            default:
              $output[] = array('Обратитесь к разработчику модуля');
              break;
          }
        }
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
      }
    }
  }

  // Обновление цен
  private function priceUpdate()
  {
    header('Content-Type: text/html; charset=utf-8');
    echo '<pre>';
    $this->load->model('module/cdl_wildberries');
    $start = 0;
    $limit = 1000;
    do {
      $filter_data = array(
        'start' => $start,
        'limit' => $limit
      );
      $products = $this->model_module_cdl_wildberries->getExportProducts($filter_data);
      $start += $limit;
      $no_duble = array();
      $duble = '';
      $data = array();
      foreach ($products as $product) {
        if (empty($product['price'])) {
          continue;
        }
        // Черный список цен
				if ($this->config->get('cdl_wildberries_product_npu')) {
					$products_npu = $this->config->get('cdl_wildberries_product_npu');
          if (in_array($product['product_id'], $products_npu)) {
            continue;
          }
				}
        if (!empty($product['special'])) {
          $price = $this->price($product['special'], $product['category_id'], $product['sub_category'], $product['manufacturer_id'], $product['product_id']);
        } else {
          $price = $this->price($product['price'], $product['category_id'], $product['sub_category'], $product['manufacturer_id'], $product['product_id']);
        }
        $data[] = array(
          'nmId' => (int)$product['nmId'],
          'price' => (float)$price
        );
        if (!in_array($product['nmId'], $no_duble)) {
          $no_duble[] = $product['nmId'];
        } else {
          $duble .= $product['nmId'] . ' ';
        }
      }
      $url = $this->api_public . 'prices';
      echo '=== ' . $this->config->get('cdl_wildberries_version') . ' ===<br />';
      echo '=== ' . $url . ' ===<br />';
      if ($this->config->get('cdl_wildberries_test_export')) {
        echo '=== Включен тест формирования цен ===<br />';
        echo '=== Запрос не будет отправлен в Wildberries ===<br />';
        echo '=== Чтобы выгрузить цены отключите тест ===<br />';
        print_r(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo '<br />';
      } else {
        $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
        echo '=== Выгрузка цен в Wildberries ===<br />';
        if (empty($response['errors'])) {
          echo '=== Успешно! ===<br />';
        } else {
          if (!empty($duble)) {
            $this->log('Цены: модулем обнаружены дубли номенклатур ' . $duble);
            echo 'Модулем обнаружены дубли номенклатур: ' . $duble . '<br />';
          }
          $this->log('Цены: ' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
          print_r(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
      }
    } while (count($products) == $limit);
  }

  // Расчет цены
  private function price($price, $category_id, $category_wb, $manufacturer_id, $product_id)
  {
    // Надбавки по категориям
    $this->load->model('module/cdl_wildberries');
    if (!empty($category_wb)) {
      $category_rate = $this->model_module_cdl_wildberries->getRateByCategory($category_wb);
      if (!empty($category_rate[0])) {
        $price += $category_rate[0]['rub'];
        if (!empty($category_rate[0]['rate'])) {
          $price *= $category_rate[0]['rate'];
        }
      }
    }

    // Дополнительные наценки
    if (!empty($this->config->get('cdl_wildberries_prices'))) {
      $new_price = 0;
			foreach ($this->config->get('cdl_wildberries_prices') as $prices) {
        $values = explode('-', $prices['value']);
        if ($prices['els'] == 'price' && !empty($values[1])) {
          if ($price >= $values[0] && $price <= $values[1]) {
            $new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
          }
        } elseif ($prices['els'] == 'price' && empty($values[1])) {
          if ($price = $values[0]) {
            $new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
          }
        } elseif ($prices['els'] == 'manufacturer_id' && $manufacturer_id == $values[0]) {
          $new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
        } elseif ($prices['els'] == 'category_id' && $category_id == $values[0]) {
          $new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
        } elseif ($prices['els'] == 'product_id' && $product_id == $values[0]) {
          $new_price += $this->actionPrice($prices['action'], $price, $prices['rate']);
        }
      }
      $price += $new_price;
		}
    switch ($this->config->get('cdl_wildberries_price_round')) {
      case 'st':
        $price = round($price, -1);
        break;
      case 'ten':
        $price = ceil($price / 10) * 10;
        break;
      case 'st_so':
        $price = round($price, -2);
        break;
      case 'so':
        $price = ceil($price / 100) * 100;
        break;
      case 'fifty':
        $price = ceil($price / 50) * 50;
        break;
      default:
        $price;
        break;
    }
    return $price;
  }

  // Действие с наценкой
  private function actionPrice($action, $price, $rate)
	{
    switch ($action) {
			case '*':
				$action_price = $price * $rate;
				$action_price = $action_price - $price;
				break;
			case '-':
				$action_price = $price - $rate;
				$action_price = $action_price - $price;
				break;
			case '+':
				$action_price = $price + $rate;
				$action_price = $action_price - $price;
				break;

			default:
				false;
				break;
		}
		return $action_price;
	}

  // Обновить остатки
  private function updateStock()
  {
    header('Content-Type: text/html; charset=utf-8');
    echo '<pre>';
    echo '=== ' . $this->config->get('cdl_wildberries_version') . ' ===<br />';
    echo '=== Выгрузка остатков в Wildberries ===<br />';
    $this->load->model('module/cdl_wildberries');
    $this->load->model('catalog/product');
    $wb_categorys = $this->config->get('cdl_wildberries_category');
    $warehouses = $this->config->get('cdl_wildberries_warehouses');
    foreach ($warehouses as $warehous) {
      $stok_product_update = 0;
			$stok_in = 0;
      $start = 0;
      $limit = 1000;
      do {
        $filter_data = array(
          'start' => $start,
          'limit' => $limit
        );
        $products = $this->model_module_cdl_wildberries->getExportProducts($filter_data);
        $start += $limit;
        $data = array();

        foreach ($products as $product) {
          if (empty($product) || empty($product[$this->config->get('cdl_wildberries_relations')])) {
						continue;
					}
          if (empty($product['category_id'])) {
            echo 'Не могу обновить остатки для товара: ' . $product[$this->config->get('cdl_wildberries_relations')] . '. Отсутствует категория в админке товара.<br />';
            continue;
          }

          $default_weight = 0;
          $default_length = 0;
          $default_width = 0;
          $default_height = 0;

          foreach ($wb_categorys as $wb_category) {
            if ($product['category_id'] == $wb_category['shop']) {
              $default_weight = $wb_category['weight'];
              $default_length = $wb_category['length'];
              $default_width = $wb_category['width'];
              $default_height = $wb_category['height'];
              break;
            }
          }

          // Размеры
          if ($product['weight_class_id'] == $this->config->get('cdl_wildberries_weight')) {
            if ($product['weight'] == 0) {
              $weight = $default_weight;
            } else {
              $weight = $product['weight'];
            }
          } else {
            if ($product['weight'] == 0) {
              $weight = $default_weight;
            } else {
              $weight = $product['weight'] * 1000;
            }
          }

          if ($product['length_class_id'] == $this->config->get('cdl_wildberries_length')) {
            if ($product['length'] == 0) {
              $length = $default_length;
            } else {
              $length = round($product['length'], PHP_ROUND_HALF_UP);
            }
            if ($product['width'] == 0) {
              $width = $default_width;
            } else {
              $width = round($product['width'], PHP_ROUND_HALF_UP);
            }
            if ($product['height'] == 0) {
              $height = $default_height;
            } else {
              $height = round($product['height'], PHP_ROUND_HALF_UP);
            }
          } else {
            if ($product['length'] == 0) {
              $length = $default_length;
            } else {
              $length = $product['length'] / 10;
            }
            if ($product['width'] == 0) {
              $width = $default_width;
            } else {
              $width = $product['width'] / 10;
            }
            if ($product['height'] == 0) {
              $height = $default_height;
            } else {
              $height = $product['height'] / 10;
            }
          }

          $product_stock = $product['quantity'];
          if ($product_stock < 0) {
						$product_stock = 0;
					}
          $price = $this->price($product['price'], $product['category_id'], $product['sub_category'], $product['manufacturer_id'], $product['product_id']);

					// Фильтр настройки складов
					if (!empty($warehous['null'])) {
						$product_stock = 0;
					}
					if (!empty($warehous['stock']) && $product_stock < $warehous['stock']) {
						$product_stock = 0;
					}
					if (!empty($warehous['price']) && $price < $warehous['price']) {
						$product_stock = 0;
					}
					if (!empty($warehous['price_do']) && $price > $warehous['price_do']) {
						$product_stock = 0;
					}
					if (!empty($warehous['weight']) && $weight < $warehous['weight']) {
						$product_stock = 0;
					}
					if (!empty($warehous['weight_do']) && $weight > $warehous['weight_do']) {
						$product_stock = 0;
					}
					if (!empty($warehous['manufacture']) && in_array($product['manufacturer_id'], $warehous['manufacture'])) {
						$product_stock = 0;
					}
					if (!empty($warehous['white_list']) && in_array($product['product_id'], $warehous['white_list'])) {
						$product_stock = $product['quantity'];
					}
					if (!empty($warehous['black_list']) && in_array($product['product_id'], $warehous['black_list'])) {
						$product_stock = 0;
					}
					if (!empty($warehous['day']) && !array_key_exists(date('N'), $warehous['day'])) {
						$product_stock = 0;
					}
          if (!empty($warehous['black_list_category']) && in_array($product['category_id'], $warehous['black_list_category'])) {
						$product_stock = 0;
					}

          // Фильтр по атрибутам
          if (!empty($warehous['attribute'])) {
            $attributes_product = $this->model_catalog_product->getProductAttributes($product['product_id']);
            if (!empty($attributes_product)) {
              foreach ($warehous['attribute'] as $warehous_attr) {
                foreach ($attributes_product as $attr_product) {
                  foreach ($attr_product['attribute'] as $attr_prod) {
                    if ($warehous_attr['id'] == $attr_prod['attribute_id'] && $warehous_attr['value'] == $attr_prod['text']) {
                      $product_stock = 0;
                    }
                  }
                }
              }
            }
          }

					if ($product_stock > 0) {
						$stok_in++;
					}

					$data[] = array(
						'barcode' => $product['barcode'],
						'stock' => (int)$product_stock,
						'warehouseId' => (int)$warehous['sklad_id']
					);
        }

        $url = $this->api . 'v2/stocks';
        if ($this->config->get('cdl_wildberries_test_export')) {
          echo '=== Включен тест формирования остатков ===<br />';
          echo '=== Запрос не будет отправлен в Wildberries ===<br />';
          echo '=== Чтобы выгрузить остатки отключите тест ===<br />';
          print_r(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
          echo '<br />';
        } else {
          $response = $this->makeRequest($url, $request = 'POST', $api = 'wb', $data);
          if (!empty($response['error'])) {
            $this->log('Остатки: ' . json_encode($response['data']['errors'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            print_r(json_encode($response['data']['errors'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
          }
        }
        $stok_product_update += count($data);
      } while (count($products) == $limit);
      if ($this->config->get('cdl_wildberries_test_export')) {
        echo '[ОСТАТКИ ' . $warehous['name'] . '] Товаров будет передано: ' . $stok_product_update . '. В наличии будет: ' . $stok_in . '<br />';
      } else {
        echo '[ОСТАТКИ ' . $warehous['name'] . '] Товаров передано: ' . $stok_product_update . '. В наличии: ' . $stok_in . '<br />';
      }
    }
  }

  // Получить склады
  private function warehouse()
  {
    $url = $this->api . 'v2/warehouses';
    $response = $this->makeRequest($url, $request = 'GET', $api = 'wb', $data = '');
    return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }

  // ID статусов МС
	private function statusIdMs()
  {
		header('Content-Type: text/html; charset=utf-8');
		if ($this->config->get('cdl_wildberries_ms_status')) {
			$url = $this->api_ms . 'entity/customerorder/metadata';
			$response = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');
			foreach ($response['states'] as $resp) {
				echo $resp['name'] . ' (' . $resp['id'] . ')<br /><br />';
			}
		} else {
      echo 'Интеграция с Мой склад отключена';
    }
	}

  // ID доп полей в МС
	public function inputIdMs()
  {
		header('Content-Type: text/html; charset=utf-8');
		if ($this->config->get('cdl_wildberries_ms_status')) {
			$url = $this->api_ms . 'entity/customerorder/metadata/attributes';
			$response = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');
			foreach ($response['rows'] as $resp) {
				echo $resp['name'] . ' (' . $resp['id'] . ')<br /><br />';
			}
		} else {
      echo 'Интеграция с Мой склад отключена';
    }
	}

  // Создаем Webhook
	private function webhookCreate()
  {
    header('Content-Type: text/html; charset=utf-8');
    if ($this->config->get('cdl_wildberries_ms_status')) {
  		$input_url = HTTPS_SERVER . 'index.php?route=module/cdl_wildberries_order/webhookinput';
  		$url = $this->api_ms . 'entity/webhook';
  		$response = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');

      if (!empty($response['rows'])) {
        foreach ($response['rows'] as $value) {
          if ($value['url'] == $input_url) {
            $check = true;
            echo 'Webhook отгрузки для модуля WB уже создан!';
          }
        }
      }
      if (!isset($check)) {
  			$data = array(
  				'url' => $input_url,
  				'action' => 'CREATE',
  				'entityType' => 'demand'
  			);

  			$respon = $this->makeRequest($url, $request = 'POST', $api = 'ms', $data);

  			if ($respon['enabled'] == true) {
  				echo 'Webhook отгрузки успешно создан! Demand id: ' . $respon['id'];
  			} else {
  				echo 'Webhook отгрузки не создан';
  			}
  		}
    } else {
      echo 'Интеграция с МС отключена';
    }
	}

	// Удалить Webhook
	public function webhookDelete()
  {
    header('Content-Type: text/html; charset=utf-8');
		if ($this->config->get('cdl_wildberries_ms_status')) {
      $input_url = HTTPS_SERVER . 'index.php?route=module/cdl_wildberries_order/webhookinput';
			$url = $this->api_ms . 'entity/webhook';
			$response = $this->makeRequest($url, $request = 'GET', $api = 'ms', $data = '');
			if (empty($response['rows'])) {
				echo 'No webhook';
			} else {
				foreach ($response['rows'] as $value) {
          if ($value['url'] == $input_url) {
            $url = $value['meta']['href'];
  					$respon = $this->makeRequest($url, $request = 'DELETE', $api = 'ms', $data = '');
  					echo 'Webhook отгрузки успешно удален. Удалена ссылка: ' . $input_url;
            break;
          }
				}
			}
		} else {
      echo 'Интеграция с МС отключена';
    }
	}

  private function makeRequest($url, $request, $api, $data = array())
  {
		//ini_set('serialize_precision', -1); //патч бага php7.3
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($api == 'wb') {
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: ' . $this->config->get('cdl_wildberries_general_token')
			));
		} else {
			$login = $this->config->get('cdl_wildberries_login_ms');
	    $pass = $this->config->get('cdl_wildberries_key_ms');
	    curl_setopt($ch, CURLOPT_USERPWD, $login . ":" . $pass);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	      	'Content-Type: application/json'
	    ));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		switch (mb_strtoupper($request)){
			case "GET":
				break;
			case "PUT":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				$data = json_encode($data);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
			case "POST":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				if (!empty($data)) {
					$data = json_encode($data);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				}
				break;
			case "DELETE":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
		}
		$response = curl_exec($ch);
		curl_close($ch);
		$response = @json_decode($response, true);
		return $response;
	}

  // Лог
  private function log($msg) {
		$fp = fopen(DIR_LOGS . 'cdl_wildberries.log', 'a');
		fwrite($fp, date('Y-m-d H:i:s').': '.str_replace("\n", '', $msg)."\n");
		fclose($fp);
	}

	private function log_process($msg) {
		$fp = fopen(DIR_SYSTEM . 'cdl_wildberries_process.txt', 'w+');
		fwrite($fp, str_replace("\n", '', $msg)."\n");
		fclose($fp);
	}

  // UPDATE 170 ++
  public function updateValentain()
  {
    header('Content-Type: text/html; charset=utf-8');
    $this->load->model('module/cdl_wildberries');
    $check_colomn_category_update = $this->model_module_cdl_wildberries->checkCdlProductColomnCategory();
    foreach ($check_colomn_category_update as $check_colomn_category) {
      if ($check_colomn_category['COLUMN_NAME'] == 'category') {
        $check_colomn_category_true = true;
      }
    }
    if (!isset($check_colomn_category_true)) {
      $this->model_module_cdl_wildberries->updateCdlColomnCategory();
    }
    $filter_data = array(
      'start'	=> 0,
      'limit' => 1000
    );
    $check_no_category_in_product = $this->model_module_cdl_wildberries->updateValentainCheckNoCategoryProducts($filter_data);
    if (!empty($check_no_category_in_product)) {
      $this->updateCategoryColomn($check_no_category_in_product);
    } else {
      echo 'Обновление не требуется';
    }
  }

  public function updateCategoryColomn($check_no_category_in_product)
  {
    $this->load->model('module/cdl_wildberries');
    $id = md5(HTTPS_SERVER . date("YmdHis"));
    do {
      $db_products = $this->model_module_cdl_wildberries->updateValentainCheckNoCategoryProducts();

      if (!empty($db_products)) {
        $search = array();
        foreach ($db_products as $db_product) {
          $vendor_code = $db_product[$this->config->get('cdl_wildberries_relations')];
          $search[] = $vendor_code;
        }
        $find = array(
          array(
            'column' => 'nomenclatures.vendorCode',
            'search' => $search
          )
        );
        $data = array(
          'id' => $id,
          'jsonrpc' => '2.0',
          'params' => array(
            'filter' => array(
              'find' => $find,
              'order' => array(
                  'column' => 'createdAt',
                  'order' => 'asc'
              )
            ),
            'query' => array(
                'limit' => 1000,
                'offset' => 0
            )
          )
        );
        $wb_products = $this->searchProduct($data);
        $wb_categorys = $this->config->get('cdl_wildberries_category');
        if (!empty($wb_products['result']['cards'])) {
          foreach ($wb_products['result']['cards'] as $created_card) {
            $shop_category_id = 0;
            foreach ($db_products as $db_product) {
              if ($created_card['supplierVendorCode'] == $db_product[$this->config->get('cdl_wildberries_relations')]) {
                $product_categorys = $this->model_module_cdl_wildberries->getProductCategory($db_product['product_id']);
                if (!empty($product_categorys)) {
                  foreach ($product_categorys as $product_category) {
                    foreach ($wb_categorys as $wb_category) {
                      if ($wb_category['shop'] == $product_category['category_id']) {
                        $shop_category_id = $wb_category['shop'];
                      }
                    }
                  }
                }
              }
            }
            $category = $created_card['parent'];
            $sub_category = $created_card['object'];
            echo $created_card['supplierVendorCode'] . ' ' . $sub_category . '<br />';
            if (!empty($category) && !empty($sub_category)) {
              foreach ($created_card['nomenclatures'] as $nomenclature) {
                $search_db = $nomenclature['vendorCode'];
                $nm_id = $nomenclature['nmId'];
                $this->model_module_cdl_wildberries->updateCategoryProduct($search_db, $category, $nm_id, $sub_category, $shop_category_id);
              }
            }
          }
        }
      }
    } while (count($db_products) == 1000);
    echo 'Готово';
  }
  // UPDATE 170 --

  // UPDATE 180 ++
    public function update180()
    {
      $this->load->model('module/cdl_wildberries');
      $colomns = $this->model_module_cdl_wildberries->checkCdlSupplieColomnOrder();
      foreach ($colomns as $colomn) {
        if ($colomn['COLUMN_NAME'] == 'supplie') {
          $check = true;
        }
      }
      if (!isset($check)) {
        $this->model_module_cdl_wildberries->addSupplieColomnOrder();
        echo "update 180 success";
      }
    }
  // UPDATE 180 --

  // UPDATE 190 ++
    public function update190()
    {
      $this->load->model('module/cdl_wildberries');
      $colomns = $this->model_module_cdl_wildberries->checkColomnProduct();
      foreach ($colomns as $colomn) {
        if ($colomn['COLUMN_NAME'] == 'chrt_id') {
          $check = true;
        }
      }
      if (!isset($check)) {
        $this->model_module_cdl_wildberries->addChrtIdColomn();
        echo "update 190 success";
      }
    }
  // UPDATE 190 --
}
