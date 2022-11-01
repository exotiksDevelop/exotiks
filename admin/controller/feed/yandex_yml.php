<?php
/**
* Yandex.YML data feed for OpenCart (ocStore) 2.x-3.x
*
* Controller to save module settings
*
* @author Yesvik http://opencartforum.ru/user/6876-yesvik/
* @author Alexander Toporkov <toporchillo@gmail.com>
* @copyright (C) 2013- Alexander Toporkov
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*
* Official version of this module: http://opencartforum.ru/files/file/670-eksport-v-iandeksmarket/
*/

/**
 * Класс YML экспорта
 * YML (Yandex Market Language) - стандарт, разработанный "Яндексом"
 * для принятия и публикации информации в базе данных Яндекс.Маркет
 * YML основан на стандарте XML (Extensible Markup Language)
 * описание формата YML http://partner.market.yandex.ru/legal/tt/
 */
class ControllerFeedYandexYml extends Controller {
    protected $tdata = array();
    
    protected $error = array();
    
    protected $allowedCurrencies = array('RUR', 'RUB', 'USD', 'EUR', 'BYR', 'BYN', 'KZT', 'UAH');
    
    protected $CONFIG_PREFIX = 'yandex_yml_';
    
    protected function preparePostData() {
        if (isset($this->request->post['yandex_yml_in_stock'])) {
            $this->request->post['yandex_yml_in_stock'] = implode(',', array_map('intval', $this->request->post['yandex_yml_in_stock']));
        }
        if (isset($this->request->post['yandex_yml_out_of_stock'])) {
            $this->request->post['yandex_yml_out_of_stock'] = implode(',', array_map('intval', $this->request->post['yandex_yml_out_of_stock']));
        }
    
        if (isset($this->request->post['yandex_yml_categories'])) {
            $this->request->post['yandex_yml_categories'] = implode(',', array_map('intval', $this->request->post['yandex_yml_categories']));
        }
        if (isset($this->request->post['yandex_yml_manufacturers'])) {
            $this->request->post['yandex_yml_manufacturers'] = implode(',', $this->request->post['yandex_yml_manufacturers']);
        }
        
        if (isset($this->request->post['yandex_yml_categ_sales_notes'])) {
            $this->request->post['yandex_yml_categ_sales_notes'] = serialize(array_filter($this->request->post['yandex_yml_categ_sales_notes'], 'strlen'));
        }
        if (isset($this->request->post['yandex_yml_categ_type_prefix'])) {
            $this->request->post['yandex_yml_categ_type_prefix'] = serialize(array_filter($this->request->post['yandex_yml_categ_type_prefix'], 'strlen'));
        }        
        if (isset($this->request->post['yandex_yml_categ_delivery_cost'])) {
            $this->request->post['yandex_yml_categ_delivery_cost'] = serialize(array_filter($this->request->post['yandex_yml_categ_delivery_cost'], 'strlen'));
        }
        if (isset($this->request->post['yandex_yml_categ_delivery_days'])) {
            $this->request->post['yandex_yml_categ_delivery_days'] = serialize(array_filter($this->request->post['yandex_yml_categ_delivery_days'], 'strlen'));
        }
        if (isset($this->request->post['yandex_yml_categ_portal_id'])) {
            $this->request->post['yandex_yml_categ_portal_id'] = serialize(array_filter($this->request->post['yandex_yml_categ_portal_id'], 'strlen'));
        }
        if (isset($this->request->post['yandex_yml_manuf_sales_notes'])) {
            $this->request->post['yandex_yml_manuf_sales_notes'] = serialize(array_filter($this->request->post['yandex_yml_manuf_sales_notes'], 'strlen'));
        }
        if (isset($this->request->post['yandex_yml_manuf_delivery_cost'])) {
            $this->request->post['yandex_yml_manuf_delivery_cost'] = serialize(array_filter($this->request->post['yandex_yml_manuf_delivery_cost'], 'strlen'));
        }
        if (isset($this->request->post['yandex_yml_manuf_delivery_days'])) {
            $this->request->post['yandex_yml_manuf_delivery_days'] = serialize(array_filter($this->request->post['yandex_yml_manuf_delivery_days'], 'strlen'));
        }
        
        if (isset($this->request->post['yandex_yml_blacklist'])) {
            $this->request->post['yandex_yml_blacklist'] = implode(',', $this->request->post['yandex_yml_blacklist']);
        }
        if (isset($this->request->post['yandex_yml_pricefrom'])) {
            $this->request->post['yandex_yml_pricefrom'] = floatval($this->request->post['yandex_yml_pricefrom']);
        }
        if (isset($this->request->post['yandex_yml_priceto'])) {
            $this->request->post['yandex_yml_priceto'] = $this->request->post['yandex_yml_priceto'];
        }
        
        if (isset($this->request->post['yandex_yml_attributes'])) {
            $this->request->post['yandex_yml_attributes'] = implode(',', $this->request->post['yandex_yml_attributes']);
        }
        if (isset($this->request->post['yandex_yml_color_options'])) {
            $this->request->post['yandex_yml_color_options'] = implode(',', $this->request->post['yandex_yml_color_options']);
        }
        if (isset($this->request->post['yandex_yml_size_options'])) {
            $this->request->post['yandex_yml_size_options'] = implode(',', $this->request->post['yandex_yml_size_options']);
        }
        if (isset($this->request->post['yandex_yml_size_units'])) {
            $this->request->post['yandex_yml_size_units'] = serialize($this->request->post['yandex_yml_size_units']);
        }
        if (isset($this->request->post['yandex_yml_coupons'])) {
            $this->request->post['yandex_yml_coupons'] = implode(',', $this->request->post['yandex_yml_coupons']);
        }
        if (isset($this->request->post['yandex_yml_coupon_urls'])) {
            $this->request->post['yandex_yml_coupon_urls'] = serialize(array_filter($this->request->post['yandex_yml_coupon_urls'], 'strlen'));
        }
        
        if (isset($this->request->post['yandex_yml_gift_promo_gift'])) {
            if (is_array($this->request->post['yandex_yml_gift_promo_gift'])) {
                foreach($this->request->post['yandex_yml_gift_promo_gift'] as $gift_id=>$gift_name) {
                    if (!$gift_name) {
                        unset($this->request->post['yandex_yml_gift_promo_name'][$gift_id]);
                        unset($this->request->post['yandex_yml_gift_promo_url'][$gift_id]);
                        unset($this->request->post['yandex_yml_gift_promo_gift'][$gift_id]);
                        unset($this->request->post['yandex_yml_gift_promo_field'][$gift_id]);
                        unset($this->request->post['yandex_yml_gift_promo_val'][$gift_id]);
                        unset($this->request->post['yandex_yml_gift_promo_img'][$gift_id]);
                    }
                }
                $this->request->post['yandex_yml_gift_promo_name'] = serialize($this->request->post['yandex_yml_gift_promo_name']);
                $this->request->post['yandex_yml_gift_promo_url'] = serialize($this->request->post['yandex_yml_gift_promo_url']);
                $this->request->post['yandex_yml_gift_promo_gift'] = serialize($this->request->post['yandex_yml_gift_promo_gift']);
                $this->request->post['yandex_yml_gift_promo_field'] = serialize($this->request->post['yandex_yml_gift_promo_field']);
                $this->request->post['yandex_yml_gift_promo_val'] = serialize($this->request->post['yandex_yml_gift_promo_val']);
                $this->request->post['yandex_yml_gift_promo_img'] = serialize($this->request->post['yandex_yml_gift_promo_img']);
                
            }
            else {
                unset($this->request->post['yandex_yml_gift_promo_name']);
                unset($this->request->post['yandex_yml_gift_promo_url']);
                unset($this->request->post['yandex_yml_gift_promo_gift']);
                unset($this->request->post['yandex_yml_gift_promo_field']);
                unset($this->request->post['yandex_yml_gift_promo_val']);
                unset($this->request->post['yandex_yml_gift_promo_img']);
            }
        }
    }

    protected function setLanguageData() {
        $this->tdata['breadcrumbs'] = array();

        $this->tdata['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->tdata['breadcrumbs'][] = array(
            'href'      => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_feed'),
            'separator' => ' :: '
        );

        $this->tdata['breadcrumbs'][] = array(
            'href'      => $this->url->link('feed/yandex_yml', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: '
        );
    
        $this->tdata['CONFIG_PREFIX'] = $this->CONFIG_PREFIX;
        
        $this->tdata['heading_title'] = $this->language->get('heading_title');
        $this->tdata['text_edit'] = $this->language->get('text_edit');
        

        $this->tdata['tab_general'] = $this->language->get('tab_general');
        $this->tdata['tab_available'] = $this->language->get('tab_available');
        $this->tdata['tab_categories'] = $this->language->get('tab_categories');
        $this->tdata['tab_attributes'] = $this->language->get('tab_attributes');
        $this->tdata['tab_tailor'] = $this->language->get('tab_tailor');

        $this->tdata['text_enabled'] = $this->language->get('text_enabled');
        $this->tdata['text_disabled'] = $this->language->get('text_disabled');
        $this->tdata['text_select_all'] = $this->language->get('text_select_all');
        $this->tdata['text_unselect_all'] = $this->language->get('text_unselect_all');
        $this->tdata['text_blacklist'] = $this->language->get('text_blacklist');
        $this->tdata['text_whitelist'] = $this->language->get('text_whitelist');

        $this->tdata['entry_status'] = $this->language->get('entry_status');
        $this->tdata['entry_token'] = $this->language->get('entry_token');
        $this->tdata['entry_token_help'] = $this->language->get('entry_token_help');
        $this->tdata['entry_data_feed'] = $this->language->get('entry_data_feed');
        $this->tdata['entry_data_feed_help'] = $this->language->get('entry_data_feed_help');
        
        $this->tdata['entry_ocstore'] = $this->language->get('entry_ocstore');
        $this->tdata['entry_ocstore_help'] = $this->language->get('entry_ocstore_help');
        $this->tdata['entry_datamodel'] = $this->language->get('entry_datamodel');
        $this->tdata['entry_datamodel_help'] = $this->language->get('entry_datamodel_help');
        
        $this->tdata['entry_name_field'] = $this->language->get('entry_name_field');
        $this->tdata['entry_model_field'] = $this->language->get('entry_model_field');
        $this->tdata['entry_vendorcode_field'] = $this->language->get('entry_vendorcode_field');
        $this->tdata['entry_typeprefix_field'] = $this->language->get('entry_typeprefix_field');
        $this->tdata['entry_barcode_field'] = $this->language->get('entry_barcode_field');
        $this->tdata['entry_keywords_field'] = $this->language->get('entry_keywords_field');
        $this->tdata['entry_description_field'] = $this->language->get('entry_description_field');
        $this->tdata['entry_dont_export'] = $this->language->get('entry_dont_export');
        
        $this->tdata['entry_export_tags'] = $this->language->get('entry_export_tags');
        $this->tdata['entry_export_tags_help'] = $this->language->get('entry_export_tags_help');
        $this->tdata['entry_utm_label'] = $this->language->get('entry_utm_label');
        $this->tdata['entry_utm_label_help'] = $this->language->get('entry_utm_label_help');
        
        $this->tdata['datamodels'] = $this->language->get('datamodels');
        $this->tdata['entry_delivery_cost'] = $this->language->get('entry_delivery_cost');
        $this->tdata['entry_delivery_cost_help'] = $this->language->get('entry_delivery_cost_help');
        $this->tdata['entry_delivery_days'] = $this->language->get('entry_delivery_days');
        $this->tdata['entry_delivery_days_help'] = $this->language->get('entry_delivery_days_help');
        $this->tdata['entry_delivery_before'] = $this->language->get('entry_delivery_before');
        $this->tdata['entry_delivery_before_help'] = $this->language->get('entry_delivery_before_help');
        $this->tdata['entry_local_delivery'] = $this->language->get('entry_local_delivery');
        $this->tdata['entry_stock_quantity'] = $this->language->get('entry_stock_quantity');
        
        $this->tdata['entry_category'] = $this->language->get('entry_category');
        $this->tdata['entry_category_help'] = $this->language->get('entry_category_help');
        $this->tdata['entry_manufacturers'] = $this->language->get('entry_manufacturers');
        $this->tdata['entry_manufacturers_help'] = $this->language->get('entry_manufacturers_help');
        $this->tdata['entry_blacklist_type'] = $this->language->get('entry_blacklist_type');
        $this->tdata['entry_blacklist'] = $this->language->get('entry_blacklist');
        $this->tdata['entry_blacklist_help'] = $this->language->get('entry_blacklist_help');
        $this->tdata['entry_whitelist'] = $this->language->get('entry_whitelist');
        $this->tdata['entry_whitelist_help'] = $this->language->get('entry_whitelist_help');
        $this->tdata['entry_pricefrom'] = $this->language->get('entry_pricefrom');
        $this->tdata['entry_pricefrom_help'] = $this->language->get('entry_pricefrom_help');
        $this->tdata['entry_priceto'] = $this->language->get('entry_priceto');
        $this->tdata['entry_priceto_help'] = $this->language->get('entry_priceto_help');
        $this->tdata['entry_image_mandatory'] = $this->language->get('entry_image_mandatory');
        
        $this->tdata['entry_currency'] = $this->language->get('entry_currency');
        $this->tdata['entry_currency_help'] = $this->language->get('entry_currency_help');
        $this->tdata['entry_oldprice'] = $this->language->get('entry_oldprice');
        $this->tdata['entry_oldprice_help'] = $this->language->get('entry_oldprice_help');
        $this->tdata['entry_groupprice'] = $this->language->get('entry_groupprice');
        $this->tdata['entry_groupprice_help'] = $this->language->get('entry_groupprice_help');
        $this->tdata['entry_changeprice'] = $this->language->get('entry_changeprice');
        $this->tdata['entry_changeprice_help'] = $this->language->get('entry_changeprice_help');
        $this->tdata['entry_unavailable'] = $this->language->get('entry_unavailable');
        $this->tdata['entry_unavailable_help'] = $this->language->get('entry_unavailable_help');
        $this->tdata['entry_in_stock'] = $this->language->get('entry_in_stock');
        $this->tdata['entry_in_stock_help'] = $this->language->get('entry_in_stock_help');
        $this->tdata['entry_out_of_stock'] = $this->language->get('entry_out_of_stock');
        $this->tdata['entry_out_of_stock_help'] = $this->language->get('entry_out_of_stock_help');

        $this->tdata['entry_pickup'] = $this->language->get('entry_pickup');
        $this->tdata['entry_pickup_help'] = $this->language->get('entry_pickup_help');
        $this->tdata['entry_sales_notes'] = $this->language->get('entry_sales_notes');
        $this->tdata['entry_sales_notes_help'] = $this->language->get('entry_sales_notes_help');
        $this->tdata['entry_store'] = $this->language->get('entry_store');
        $this->tdata['entry_store_help'] = $this->language->get('entry_store_help');
        $this->tdata['entry_numpictures'] = $this->language->get('entry_numpictures');
        $this->tdata['entry_numpictures_help'] = $this->language->get('entry_numpictures_help');

        $this->tdata['button_save'] = $this->language->get('button_save');
        $this->tdata['button_cancel'] = $this->language->get('button_cancel');

        $this->tdata['text_yes'] = $this->language->get('text_yes');
        $this->tdata['text_no'] = $this->language->get('text_no');
        
        $this->tdata['entry_cron_run'] = $this->language->get('entry_cron_run');
        $this->tdata['entry_cron_run_help'] = $this->language->get('entry_cron_run_help');
        $this->tdata['entry_export_url'] = $this->language->get('entry_export_url');
        $this->tdata['entry_export_url_help'] = $this->language->get('entry_export_url_help');

        //++++ Для вкладки аттрибутов ++++
        $this->tdata['tab_attributes_description'] = str_replace('%attr_url%', $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('tab_attributes_description'));
        $this->tdata['entry_attributes'] = $this->language->get('entry_attributes');
        $this->tdata['entry_attributes_help'] = $this->language->get('entry_attributes_help');
        $this->tdata['entry_adult'] = $this->language->get('entry_adult');
        $this->tdata['entry_adult_help'] = $this->language->get('entry_adult_help');
        $this->tdata['entry_all_adult'] = $this->language->get('entry_all_adult');
        $this->tdata['entry_all_adult_help'] = $this->language->get('entry_all_adult_help');
        $this->tdata['entry_manufacturer_warranty'] = $this->language->get('entry_manufacturer_warranty');
        $this->tdata['entry_manufacturer_warranty_help'] = $this->language->get('entry_manufacturer_warranty_help');
        $this->tdata['entry_all_manufacturer_warranty'] = $this->language->get('entry_all_manufacturer_warranty');
        $this->tdata['entry_all_manufacturer_warranty_help'] = $this->language->get('entry_all_manufacturer_warranty_help');
        $this->tdata['entry_country_of_origin'] = $this->language->get('entry_country_of_origin');
        $this->tdata['entry_country_of_origin_help'] = $this->language->get('entry_country_of_origin_help');
        $this->tdata['entry_product_rel'] = $this->language->get('entry_product_rel');
        $this->tdata['entry_product_rel_help'] = $this->language->get('entry_product_rel_help');
        $this->tdata['entry_product_accessory'] = $this->language->get('entry_product_accessory');
        $this->tdata['entry_product_accessory_help'] = $this->language->get('entry_product_accessory_help');

        //++++ Для магазинов одежды ++++
        $this->tdata['entry_color_option'] = $this->language->get('entry_color_option');
        $this->tdata['entry_color_option_help'] = $this->language->get('entry_color_option_help');
        $this->tdata['entry_size_option'] = $this->language->get('entry_size_option');
        $this->tdata['entry_size_option_help'] = $this->language->get('entry_size_option_help');
        $this->tdata['entry_size_unit'] = $this->language->get('entry_size_unit');
        $this->tdata['entry_size_unit_help'] = $this->language->get('entry_size_unit_help');
        $this->tdata['entry_optioned_name'] = $this->language->get('entry_optioned_name');
        $this->tdata['entry_optioned_name_help'] = $this->language->get('entry_optioned_name_help');
        $this->tdata['optioned_name_no'] = $this->language->get('optioned_name_no');
        $this->tdata['optioned_name_short1'] = $this->language->get('optioned_name_short1');
        $this->tdata['optioned_name_short'] = $this->language->get('optioned_name_short');
        $this->tdata['optioned_name_long1'] = $this->language->get('optioned_name_long1');
        $this->tdata['optioned_name_long'] = $this->language->get('optioned_name_long');
        
        $this->tdata['entry_option_image'] = $this->language->get('entry_option_image');
        
        $this->tdata['size_units_orig'] = array(
            'RU' => 'Россия (СНГ)',
            'EU' => 'Европа',
            'UK' => 'Великобритания',
            'US' => 'США',
            'INT' => 'Международная');
        $this->tdata['size_units_type'] = array(
            'INCH' => 'Дюймы',
            'Height' => 'Рост в сантиметрах',
            'Months' => 'Возраст в месяцах',
            'Years' => 'Возраст в годах',
            'Round' => 'Окружность в сантиметрах');
            
        $this->tdata['oc_fields'] = array(
            'name' => 'Название товара - name',
            'model' => 'Модель - model',
            'name+model' => 'Название + Модель - name + model',
            'sku' => 'Артикул (SKU, код производителя) - sku',
            'upc' => 'UPC - upc',
            'ean' => 'EAN - ean',
            'jan' => 'JAN - jan',
            'isbn' => 'ISBN - isbn',
            'mpn' => 'MPN - mpn',
            'meta_title' => 'HTML-тег Title - meta_title',
            'meta_h1' => 'HTML-тег H1 - meta_h1',
            'meta_description' => 'Мета-тег &quot;Описание&quot; - meta_description',
            'meta_keyword' => 'Мета-тег Keywords - meta_keyword',
            'location' => 'Расположение'
        );
        $this->tdata['oc_filter_fields'] = array(
            'model' => 'Модель - model',
            'sku' => 'Артикул (SKU, код производителя) - sku',
            'upc' => 'UPC - upc',
            'ean' => 'EAN - ean',
            'jan' => 'JAN - jan',
            'isbn' => 'ISBN - isbn',
            'mpn' => 'MPN - mpn',
            'location' => 'Расположение'
        );
        $this->tdata['oc_desc_fields'] = array(
            'description' => 'Описание',
            'meta_description' => 'Мета-тег &quot;Описание&quot;',
            'attr_vs_description' => 'Собирать из атрибутов'
        );    
    }
    
    protected $SETTINGS = array(
        //+++ Общие +++
        'status', //Статус вкл./выкл.
        'token', //Токен
        'ocstore', //Главные категории
        'datamodel', //упрощенный/vendor.model
        'name_field', //name из поля
        'model_field', //model из поля
        'vendorcode_field', //vendorcode из поля
        'typeprefix_field', //typeprefix из поля
        'barcode_field', //barcode из поля
        'sales_notesattr', //sales_notes из аттрибута
        'sales_notes', //sales_notes
        'keywords_field', //keywords из поля
        'shop_sku', //shop-sku из product_id
        'market_sku_field', //market-sku из поля
        'manufacturer_field', //manufacturer из поля
        'description_field', //description из поля
        'export_tags', //оставлять html-тэги в описании
        'utm_label', //UTM-метки
        'currency', //экспорт в валюте
        'oldprice', //старые цены в oldprice
        'price_old', //старые цены в price_old
        'price_promo', //акционные цены в price_promo
        'groupprice', //цены для группы покупателей
        'purchase_price', //закупочные цены
        'changeprice', //коэффициент наценки
        'numpictures', //кол-во картинок на товар
    
        //+++ Склад и доставка +++
        'store', //точка продаж
        'unavailable', //весь товар под заказ
        'pickup', //самовывоз
        'delivery_cost', //cтоимость доставки 
        'delivery_days', //срок доставки
        'delivery_before', //час перескока
        'local_delivery', //выгружать local_delivery_cost
        'count', //кол-во для "Беру"
        'quantity', //кол-во для Aliexpress
        'stock_quantity', //кол-во для Rozetka
        'quantity_in_stock', //кол-во для prom.ua
        'min_quantity', //минимальный заказ в тэгах min-quantity и step-quantity
        'order_quantity', //минимальный заказ в тэге minimum_order_quantity для prom.ua
        'dimensions', //размеры length/width/height для "Беру"
        'length_width_height', //размеры length, width, height для Aliexpress
    
        //+++ Что выгружать ++++
        'pricefrom', //Выгружать только если товар дороже
        'priceto', //Выгружать только если товар дешевле
        'image_mandatory', //Не выгружать без картинок
        'exportattr', //Выгружать только товар с атрибутом
        'blacklist_type', //Чёрный или белый список
    
        //++++ Для вкладки аттрибутов ++++
        'all_adult', //Все товары 18+
        'adult', //Атрибут товара 18+
        'all_manufacturer_warranty', //Все с официальной гарантией
        'manufacturer_warranty', //Атрибут официальной гарантии
        'country_of_origin', //Атрибут страны производства
        'tn_ved_codes', //Атрибут c кодом ТН ВЭД
        'product_rel', //Cопутствующие товары в тэге <rec>
    
        //++++ Для вкладки опций ++++
        'optioned_name', //Как менять имя в зав-сти от опции
        'option_image', //Картинка от опции главная
        'option_image_pro', //Совместимость с option_image_pro
    );

    protected function setFormData() {
        $this->load->model('localisation/currency');
        $currencies = $this->model_localisation_currency->getCurrencies();
        $allowed_currencies = array_flip($this->allowedCurrencies);
        $this->tdata['currencies'] = array_intersect_key($currencies, $allowed_currencies);

        if (is_file(DIR_APPLICATION.'model/customer/customer_group.php')) {
            $this->load->model('customer/customer_group');
            $this->tdata['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
        }
        elseif (is_file(DIR_APPLICATION.'model/sale/customer_group.php')) {
            $this->load->model('sale/customer_group');
            $this->tdata['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        }
        else {
            $this->tdata['customer_groups'] = array();
        }

        $this->load->model('localisation/stock_status');
        $this->tdata['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        $this->load->model('catalog/attribute');
        $results = $this->model_catalog_attribute->getAttributes(array('sort'=>'attribute_group'));
        $this->tdata['attributes'] = $results;

        foreach($this->SETTINGS as $key) {
            if (isset($this->request->post['yandex_yml_'.$key])) {
                $this->tdata['yandex_yml_'.$key] = $this->request->post['yandex_yml_'.$key];
            } else {
                $this->tdata['yandex_yml_'.$key] = $this->config->get($this->CONFIG_PREFIX.$key);
            }
        }

        if (isset($this->request->post['yandex_yml_unavailable'])) {
            $this->tdata['yandex_yml_unavailable'] = $this->request->post['yandex_yml_unavailable'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'unavailable')) {
            $this->tdata['yandex_yml_unavailable'] = $this->config->get($this->CONFIG_PREFIX.'unavailable');
        } else {
            $this->tdata['yandex_yml_unavailable'] = '';
        }

        if (isset($this->request->post['yandex_yml_in_stock'])) {
            $this->tdata['yandex_yml_in_stock'] = $this->request->post['yandex_yml_in_stock'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'in_stock')) {
            $this->tdata['yandex_yml_in_stock'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'in_stock'));
        } else {
            $this->tdata['yandex_yml_in_stock'] = array(7);
        }

        if (isset($this->request->post['yandex_yml_out_of_stock'])) {
            $this->tdata['yandex_yml_out_of_stock'] = $this->request->post['yandex_yml_out_of_stock'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'out_of_stock')) {
            $this->tdata['yandex_yml_out_of_stock'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'out_of_stock'));
        } else {
            $this->tdata['yandex_yml_out_of_stock'] = array(5);
        }

        //++++ Для вкладки что выгружать ++++

        if (isset($this->request->post['yandex_yml_blacklist'])) {
            $blacklist = $this->request->post['yandex_yml_blacklist'];
        } else {
            $blacklist = explode(',', $this->config->get($this->CONFIG_PREFIX.'blacklist'));
        }
        $this->load->model('catalog/product');
        
        $this->tdata['blacklist'] = array();
        
        foreach ($blacklist as $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);
            
            if ($product_info) {
                $this->tdata['blacklist'][] = array(
                    'product_id' => $product_info['product_id'],
                    'name'       => $product_info['name']
                );
            }
        }
        
        // Categories
        $this->load->model('catalog/category');
        $filter_data = array(
            'sort'        => 'name',
            'order'       => 'ASC'
        );
        $this->tdata['categories'] = $this->model_catalog_category->getCategories($filter_data);
        
        $this->load->model('catalog/manufacturer');
        $this->tdata['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers(0);
        $this->tdata['manufacturers'][] = array('manufacturer_id'=>'0', 'name'=>'Производитель не указан');

        if (isset($this->request->post['yandex_yml_categories'])) {
            $this->tdata['yandex_yml_categories'] = $this->request->post['yandex_yml_categories'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'categories') != '') {
            $this->tdata['yandex_yml_categories'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'categories'));
        } else {
            $this->tdata['yandex_yml_categories'] = array();
        }
        if (isset($this->request->post['yandex_yml_manufacturers'])) {
            $this->tdata['yandex_yml_manufacturers'] = $this->request->post['yandex_yml_manufacturers'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'manufacturers') != '') {
            $this->tdata['yandex_yml_manufacturers'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'manufacturers'));
        } else {
            $this->tdata['yandex_yml_manufacturers'] = array();
        }
        
        if (isset($this->request->post['yandex_yml_categ_sales_notes'])) {
            $this->tdata['yandex_yml_categ_sales_notes'] = $this->request->post['yandex_yml_categ_sales_notes'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'categ_sales_notes') != '') {
            $this->tdata['yandex_yml_categ_sales_notes'] = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_sales_notes'));
        } else {
            $this->tdata['yandex_yml_categ_sales_notes'] = array();
        }
        if (isset($this->request->post['yandex_yml_categ_type_prefix'])) {
            $this->tdata['yandex_yml_categ_type_prefix'] = $this->request->post['yandex_yml_categ_type_prefix'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'categ_type_prefix') != '') {
            $this->tdata['yandex_yml_categ_type_prefix'] = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_type_prefix'));
        } else {
            $this->tdata['yandex_yml_categ_type_prefix'] = array();
        }
        if (isset($this->request->post['yandex_yml_categ_delivery_cost'])) {
            $this->tdata['yandex_yml_categ_delivery_cost'] = $this->request->post['yandex_yml_categ_delivery_cost'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'categ_delivery_cost') != '') {
            $this->tdata['yandex_yml_categ_delivery_cost'] = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_delivery_cost'));
        } else {
            $this->tdata['yandex_yml_categ_delivery_cost'] = array();
        }
        if (isset($this->request->post['yandex_yml_categ_delivery_days'])) {
            $this->tdata['yandex_yml_categ_delivery_days'] = $this->request->post['yandex_yml_categ_delivery_days'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'categ_delivery_days') != '') {
            $this->tdata['yandex_yml_categ_delivery_days'] = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_delivery_days'));
        } else {
            $this->tdata['yandex_yml_categ_delivery_days'] = array();
        }
        if (isset($this->request->post['yandex_yml_categ_portal_id'])) {
            $this->tdata['yandex_yml_categ_portal_id'] = $this->request->post['yandex_yml_categ_portal_id'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'categ_portal_id') != '') {
            $this->tdata['yandex_yml_categ_portal_id'] = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_portal_id'));
        } else {
            $this->tdata['yandex_yml_categ_portal_id'] = array();
        }

        if (isset($this->request->post['yandex_yml_manuf_sales_notes'])) {
            $this->tdata['yandex_yml_manuf_sales_notes'] = $this->request->post['yandex_yml_manuf_sales_notes'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'manuf_sales_notes') != '') {
            $this->tdata['yandex_yml_manuf_sales_notes'] = unserialize($this->config->get($this->CONFIG_PREFIX.'manuf_sales_notes'));
        } else {
            $this->tdata['yandex_yml_manuf_sales_notes'] = array();
        }
        if (isset($this->request->post['yandex_yml_manuf_delivery_cost'])) {
            $this->tdata['yandex_yml_manuf_delivery_cost'] = $this->request->post['yandex_yml_manuf_delivery_cost'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'manuf_delivery_cost') != '') {
            $this->tdata['yandex_yml_manuf_delivery_cost'] = unserialize($this->config->get($this->CONFIG_PREFIX.'manuf_delivery_cost'));
        } else {
            $this->tdata['yandex_yml_manuf_delivery_cost'] = array();
        }
        if (isset($this->request->post['yandex_yml_manuf_delivery_days'])) {
            $this->tdata['yandex_yml_manuf_delivery_days'] = $this->request->post['yandex_yml_manuf_delivery_days'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'manuf_delivery_days') != '') {
            $this->tdata['yandex_yml_manuf_delivery_days'] = unserialize($this->config->get($this->CONFIG_PREFIX.'manuf_delivery_days'));
        } else {
            $this->tdata['yandex_yml_manuf_delivery_days'] = array();
        }        
        //---- Для вкладки что выгружать ----

        //++++ Для вкладки аттрибутов ++++
        if (isset($this->request->post['yandex_yml_attributes'])) {
            $this->tdata['yandex_yml_attributes'] = $this->request->post['yandex_yml_attributes'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'attributes') != '') {
            $this->tdata['yandex_yml_attributes'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'attributes'));
        } else {
            $this->tdata['yandex_yml_attributes'] = array();
        }
        //---- Для вкладки аттрибутов ----

        //++++ Для вкладки опций ++++
        $this->load->model('catalog/option');
        $results = $this->model_catalog_option->getOptions(array('sort' => 'name'));
        $this->tdata['options'] = $results;
        
        $this->tdata['tab_tailor_description'] = $this->language->get('tab_tailor_description');

        if (isset($this->request->post['yandex_yml_color_options'])) {
            $this->tdata['yandex_yml_color_options'] = $this->request->post['yandex_yml_color_options'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'color_options') != '') {
            $this->tdata['yandex_yml_color_options'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'color_options'));
        } else {
            $this->tdata['yandex_yml_color_options'] = array();
        }
        if (isset($this->request->post['yandex_yml_size_options'])) {
            $this->tdata['yandex_yml_size_options'] = $this->request->post['yandex_yml_size_options'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'size_options') != '') {
            $this->tdata['yandex_yml_size_options'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'size_options'));
        } else {
            $this->tdata['yandex_yml_size_options'] = array();
        }
        if (isset($this->request->post['yandex_yml_size_units'])) {
            $this->tdata['yandex_yml_size_units'] = $this->request->post['yandex_yml_size_units'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'size_units') != '') {
            $this->tdata['yandex_yml_size_units'] = unserialize($this->config->get($this->CONFIG_PREFIX.'size_units'));
        } else {
            $this->tdata['yandex_yml_size_units'] = array();
        }
        //---- Для вкладки опций ----
        
        //++++ Promo ++++
        if (isset($this->request->post['yandex_yml_auto_discounts'])) {
            $this->tdata['yandex_yml_auto_discounts'] = $this->request->post['yandex_yml_auto_discounts'];
        } else {
            $this->tdata['yandex_yml_auto_discounts'] = $this->config->get($this->CONFIG_PREFIX.'auto_discounts');
        }
        if (isset($this->request->post['yandex_yml_flash_discount'])) {
            $this->tdata['yandex_yml_flash_discount'] = $this->request->post['yandex_yml_flash_discount'];
        } else {
            $this->tdata['yandex_yml_flash_discount'] = $this->config->get($this->CONFIG_PREFIX.'flash_discount');
        }
        
        $this->load->model('marketing/coupon');
        $this->tdata['coupons'] = $this->model_marketing_coupon->getCoupons(array('sort'=>'name', 'order'=>'ASC', 'start'=>0, 'limit'=>1000));
        
        if (isset($this->request->post['yandex_yml_coupons'])) {
            $this->tdata['yandex_yml_coupons'] = $this->request->post['yandex_yml_coupons'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'coupons') != '') {
            $this->tdata['yandex_yml_coupons'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'coupons'));
        } else {
            $this->tdata['yandex_yml_coupons'] = array();
        }
        if (isset($this->request->post['yandex_yml_coupon_urls'])) {
            $this->tdata['yandex_yml_coupon_urls'] = $this->request->post['yandex_yml_coupon_urls'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'coupon_urls') != '') {
            $this->tdata['yandex_yml_coupon_urls'] = unserialize($this->config->get($this->CONFIG_PREFIX.'coupon_urls'));
        } else {
            $this->tdata['yandex_yml_coupon_urls'] = array();
        }
        
        if (isset($this->request->post['yandex_yml_gift_promo_name'])) {
            $this->tdata['yandex_yml_gift_promo_name'] = $this->request->post['yandex_yml_gift_promo_name'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'gift_promo_name') != '') {
            $this->tdata['yandex_yml_gift_promo_name'] = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_name'));
        } else {
            $this->tdata['yandex_yml_gift_promo_name'] = array();
        }
        if (isset($this->request->post['yandex_yml_gift_promo_url'])) {
            $this->tdata['yandex_yml_gift_promo_url'] = $this->request->post['yandex_yml_gift_promo_url'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'gift_promo_url') != '') {
            $this->tdata['yandex_yml_gift_promo_url'] = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_url'));
        } else {
            $this->tdata['yandex_yml_gift_promo_url'] = array();
        }
        if (isset($this->request->post['yandex_yml_gift_promo_gift'])) {
            $this->tdata['yandex_yml_gift_promo_gift'] = $this->request->post['yandex_yml_gift_promo_gift'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'gift_promo_gift') != '') {
            $this->tdata['yandex_yml_gift_promo_gift'] = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_gift'));
        } else {
            $this->tdata['yandex_yml_gift_promo_gift'] = array();
        }
        if (isset($this->request->post['yandex_yml_gift_promo_field'])) {
            $this->tdata['yandex_yml_gift_promo_field'] = $this->request->post['yandex_yml_gift_promo_field'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'gift_promo_field') != '') {
            $this->tdata['yandex_yml_gift_promo_field'] = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_field'));
        } else {
            $this->tdata['yandex_yml_gift_promo_field'] = array();
        }
        if (isset($this->request->post['yandex_yml_gift_promo_val'])) {
            $this->tdata['yandex_yml_gift_promo_val'] = $this->request->post['yandex_yml_gift_promo_val'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'gift_promo_val') != '') {
            $this->tdata['yandex_yml_gift_promo_val'] = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_val'));
        } else {
            $this->tdata['yandex_yml_gift_promo_val'] = array();
        }
        if (isset($this->request->post['yandex_yml_gift_promo_img'])) {
            $this->tdata['yandex_yml_gift_promo_img'] = $this->request->post['yandex_yml_gift_promo_img'];
        } elseif ($this->config->get($this->CONFIG_PREFIX.'gift_promo_img') != '') {
            $this->tdata['yandex_yml_gift_promo_img'] = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_img'));
        } else {
            $this->tdata['yandex_yml_gift_promo_img'] = array();
        }
        if (isset($this->request->post['yandex_yml_custom_promo'])) {
            $this->tdata['yandex_yml_custom_promo'] = $this->request->post['yandex_yml_custom_promo'];
        } else {
            $this->tdata['yandex_yml_custom_promo'] = $this->config->get($this->CONFIG_PREFIX.'custom_promo');
        }
        if (isset($this->request->post['yandex_yml_custom_gifts'])) {
            $this->tdata['yandex_yml_custom_gifts'] = $this->request->post['yandex_yml_custom_gifts'];
        } else {
            $this->tdata['yandex_yml_custom_gifts'] = $this->config->get($this->CONFIG_PREFIX.'custom_gifts');
        }
        
           if (isset($this->request->post['yandex_yml_condition_used'])) {
            $this->tdata['yandex_yml_condition_used'] = $this->request->post['yandex_yml_condition_used'];
        } else {
            $this->tdata['yandex_yml_condition_used'] = $this->config->get($this->CONFIG_PREFIX.'condition_used');
        }
           if (isset($this->request->post['yandex_yml_condition_likenew'])) {
            $this->tdata['yandex_yml_condition_likenew'] = $this->request->post['yandex_yml_condition_likenew'];
        } else {
            $this->tdata['yandex_yml_condition_likenew'] = $this->config->get($this->CONFIG_PREFIX.'condition_likenew');
        }
        if (isset($this->request->post['yandex_yml_opt_discount'])) {
            $this->tdata['yandex_yml_opt_discount'] = $this->request->post['yandex_yml_opt_discount'];
        } else {
            $this->tdata['yandex_yml_opt_discount'] = $this->config->get($this->CONFIG_PREFIX.'opt_discount');
        }
    }
    
    public function index() {
        $this->load->language('feed/yandex_yml');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate($this->request->post))) {
            $this->preparePostData();

            $this->model_setting_setting->editSetting('yandex_yml', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->setLanguageData();
        $this->tdata['token'] = $this->session->data['token'];
        $this->tdata['cron_path'] = 'php '.realpath(DIR_CATALOG.'../export/yandex_yml.php');

        $this->tdata['export_url'] = HTTP_CATALOG.'export/';

        if (isset($this->error['warning'])) {
            $this->tdata['error_warning'] = $this->error['warning'];
        } else {
            $this->tdata['error_warning'] = '';
        }

        $this->tdata['action'] = $this->url->link('feed/yandex_yml', 'token=' . $this->session->data['token'], 'SSL');

        $this->tdata['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

        $this->tdata['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/yandex_yml';
        
        $this->setFormData();
        
        $template = 'feed/yandex_yml.tpl';
        
        $this->tdata['header'] = $this->load->controller('common/header');
        $this->tdata['column_left'] = $this->load->controller('common/column_left');
        $this->tdata['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view($template, $this->tdata));
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `oc_yandex_category`;");
    }

    protected function validate($data) {
        if (!$this->user->hasPermission('modify', 'feed/yandex_yml')) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }
        $data['yandex_yml_custom_promo'] = trim($data['yandex_yml_custom_promo']);
        $data['yandex_yml_custom_gifts'] = trim($data['yandex_yml_custom_gifts']);
        if (!isset($data['yandex_yml_currency']) || !$data['yandex_yml_currency']) {
            $this->error['warning'] = $this->language->get('error_no_currency');
        }

        /*
        elseif (!empty(array_intersect($data['yandex_yml_size_options'], $data['yandex_yml_color_options']))) {
            $this->error['warning'] = $this->language->get('error_intersects_options');
        }
        */
        if (!intval($data['yandex_yml_numpictures']) && isset($data['yandex_yml_image_mandatory'])) {
            $this->error['warning'].= $this->language->get('error_image_mandatory');
        }
        else if ($data['yandex_yml_custom_promo']) {
            $doc = @simplexml_load_string('<promos>'.trim(html_entity_decode($data['yandex_yml_custom_promo'], ENT_QUOTES, 'UTF-8')).'</promos>');
            if (!$doc) {
                $this->error['warning'] = ' В поле "Свой тэг &lt;promo&gt;" во вкладке "Промоакции" невалидный XML.';
            }
        }
        else if ($data['yandex_yml_custom_gifts']) {
            $doc = @simplexml_load_string('<gifts>'.trim(html_entity_decode($data['yandex_yml_custom_gifts'], ENT_QUOTES, 'UTF-8')).'</gifts>');
            if (!$doc) {
                $this->error['warning'] = ' В поле "Свой тэг &lt;gift&gt;" во вкладке "Промоакции" невалидный XML.';
            }
        }
        

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
