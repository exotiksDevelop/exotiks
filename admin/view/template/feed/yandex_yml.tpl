<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
  <div class="container-fluid">
    <div class="pull-right">
        <button type="submit" form="form-edit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo  $text_edit; ?></h3> <a href="http://sourcedistillery.com/market_yml_faq.html" target="_blank">F.A.Q.</a>
      </div>
      <div class="panel-body">
	  
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-edit" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-available" data-toggle="tab"><?php echo $tab_available; ?></a></li>
            <li><a href="#tab-categories" data-toggle="tab"><?php echo $tab_categories; ?></a></li>
            <li><a href="#tab-attributes" data-toggle="tab"><?php echo $tab_attributes; ?></a></li>
            <li><a href="#tab-tailor" data-toggle="tab"><?php echo $tab_tailor; ?></a></li>
            <li><a href="#tab-promo" data-toggle="tab">Промоакции</a></li>
          </ul>

        <div class="tab-content">
		
        <div class="tab-pane active" id="tab-general">
				
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-9">
				  <select name="yandex_yml_status" id="input-status" class="form-control">
					<?php if ($yandex_yml_status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_token_help; ?>"><?php echo $entry_token; ?></span></label>
				<div class="col-sm-9">
					<input type="text" name="yandex_yml_token" value="<?php echo $yandex_yml_token; ?>" id="input-token" class="form-control" />
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_data_feed_help; ?>"><?php echo $entry_data_feed; ?></span></label>
				<div class="col-sm-9">
					<b><a href="<?php echo $data_feed; ?><?php echo $yandex_yml_token ? '&token='.$yandex_yml_token : ''; ?>" target="_blank" id="yml_feed_url"><?php echo $data_feed; ?><?php echo $yandex_yml_token ? '&token='.$yandex_yml_token : ''; ?></a></b>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_cron_run_help; ?>"><?php echo $entry_cron_run; ?></span></label>
				<div class="col-sm-9">
					<b><?php echo $cron_path; ?></b> <sup style="color: red;">Только для OpenCart 2.1.x</sup>
				</div>
			</div>			
			
			<div class="form-group">
				<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_export_url_help; ?>"><?php echo $entry_export_url; ?></span></label>
				<div class="col-sm-9">
					<b id="yml_static_file"><?php echo $export_url . $CONFIG_PREFIX . $yandex_yml_token; ?>.xml</b>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-ocstore"><span data-toggle="tooltip" title="<?php echo $entry_ocstore_help; ?>"><?php echo $entry_ocstore; ?></span></label>
				<div class="col-sm-9">
				<div class="checkbox">
				<label><input type="checkbox" name="yandex_yml_ocstore" id="input-ocstore" value="1"<?php echo ($yandex_yml_ocstore ? ' checked="checked"' : ''); ?>></label>
				</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-datamodel"><span data-toggle="tooltip" title="<?php echo $entry_datamodel_help; ?>"><?php echo $entry_datamodel; ?></span></label>
				<div class="col-sm-9">
					<select name="yandex_yml_datamodel" id="input-datamodel" class="form-control">
					<?php foreach ($datamodels as $key=>$datamodel) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_datamodel ? ' selected="selected"' : ''); ?>>
					<?php echo $datamodel; ?>
					</option>
					<?php } ?>
					</select>
				</div>
			</div>
				
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-name-field"><?php echo $entry_name_field; ?></label>
				<div class="col-sm-9">
					<select name="yandex_yml_name_field" id="input-name-field" class="form-control">
					<?php foreach ($oc_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_name_field ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
					<a href="https://yandex.ru/support/partnermarket/elements/vendor-name-model.html" target="_blank">подробнее</a>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-model-field"><?php echo $entry_model_field; ?></label>
				<div class="col-sm-9">
					<select name="yandex_yml_model_field" id="input-model-field" class="form-control">
					<option value=""><?php echo $entry_dont_export; ?></option>
					<?php foreach ($oc_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_model_field ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
                    <span style="color: red;">При vendor.model model будет выгружаться</span>
				</div>
			</div>				
				
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-vendorcode-field"><?php echo $entry_vendorcode_field; ?></label>
				<div class="col-sm-9">
					<select name="yandex_yml_vendorcode_field" id="input-vendorcode-field" class="form-control">
					<option value=""><?php echo $entry_dont_export; ?></option>
					<?php foreach ($oc_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_vendorcode_field ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-typeprefix-field"><?php echo $entry_typeprefix_field; ?></label>
				<div class="col-sm-9">
					<select name="yandex_yml_typeprefix_field" id="input-typeprefix-field" class="form-control">
					<option value=""><?php echo $entry_dont_export; ?></option>
					<?php foreach ($oc_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_typeprefix_field ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-barcode-field"><?php echo $entry_barcode_field; ?></label>
				<div class="col-sm-9">
					<select name="yandex_yml_barcode_field" id="input-barcode-field" class="form-control">
					<option value=""><?php echo $entry_dont_export; ?></option>
					<?php foreach ($oc_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_barcode_field ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
				</div>
			</div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="input-sales_notesattr" data-toggle="tooltip" title="sales_notes в первую очередь берется из атрибута, во вторую из настроек категорий и производителей, иначе из поля &quot;Примечания sales_notes&quot;"><span>Брать тэг sales_notes из атрибута</span></label>
                <div class="col-sm-9">
                    <select name="yandex_yml_sales_notesattr" id="input-sales_notesattr" class="form-control">
                    <option value="0">Атрибуты не учитывать</option>
                    <?php
                    $attr_group_id = -1;
                    foreach ($attributes as $key=>$attribute) {
                        if ($attr_group_id != $attribute['attribute_group_id']) {
                            echo '<optgroup label="'.$attribute['attribute_group'].'">';
                            $attr_group_id = $attribute['attribute_group_id'];
                        }
                        echo '<option value="'.$attribute['attribute_id'].'"'.($yandex_yml_sales_notesattr == $attribute['attribute_id'] ? ' selected="selected"' : '').'>'.$attribute['name'].'</option>';
                        if (!isset($attributes[$key+1]) || ($attr_group_id != $attributes[$key+1]['attribute_group_id'])) {
                            echo '</optgroup>';
                        }
                    }
                    ?>
                    </select>
                    <a href="https://yandex.ru/support/partnermarket/elements/sales_notes.html" target="_blank">подробнее</a>
                </div>
            </div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-sales-notes"><span data-toggle="tooltip" title="<?php echo $entry_sales_notes_help; ?>"><?php echo $entry_sales_notes; ?></span></label>
				<div class="col-sm-9">
					<input type="text" name="yandex_yml_sales_notes" value="<?php echo $yandex_yml_sales_notes; ?>" id="input-sales-notes" maxlength="50" class="form-control" />
				</div>
			</div>
            

			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-keyword-field"><?php echo $entry_keywords_field; ?></label>
				<div class="col-sm-9">
					<select name="yandex_yml_keywords_field" id="input-keyword-field" class="form-control">
					<option value=""><?php echo $entry_dont_export; ?></option>
					<?php foreach ($oc_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_keywords_field ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
                    <span style="color: red;">Для Яндекс.Маркет не выгружать (для prom.ua, satu.kz)</span>
				</div>
			</div>
            
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-shop-sku">Выгружать shop-sku из product_id</label>
				<div class="col-sm-9">
                    <div class="checkbox">
                    <label>
					<input type="checkbox" name="yandex_yml_shop_sku" value="1" id="input-shop-sku"<?php echo ($yandex_yml_shop_sku ? ' checked="checked"' : ''); ?>>
                    </label>
                    <span style="color: red;">Выгружать для "Беру"</span> <a href="https://yandex.ru/support/marketplace/catalog/yml-simple.html" target="_blank">подробнее</a>
                    </div>
				</div>
			</div>
            
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-market-sku-field">Брать тэг market-sku из поля</label>
				<div class="col-sm-9">
					<select name="yandex_yml_market_sku_field" id="input-market-sku-field" class="form-control">
					<option value=""><?php echo $entry_dont_export; ?></option>
					<?php foreach ($oc_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_market_sku_field ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
                    <span style="color: red;">Выгружать для "Беру"</span> <a href="https://yandex.ru/support/marketplace/catalog/yml-simple.html" target="_blank">подробнее</a>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-manufacturer-field">Брать тэг manufacturer из поля</label>
				<div class="col-sm-9">
					<select name="yandex_yml_manufacturer_field" id="nput-manufacturer-field" class="form-control">
					<option value=""><?php echo $entry_dont_export; ?></option>
					<option value="manufacturer"<?php echo ('manufacturer'==$yandex_yml_manufacturer_field ? ' selected="selected"' : ''); ?>>Производитель - manufacturer</option>
					<?php foreach ($oc_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_manufacturer_field ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
                    <span style="color: red;">Выгружать для "Беру"</span> <a href="https://yandex.ru/support/marketplace/catalog/yml-simple.html" target="_blank">подробнее</a>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-description-field"><?php echo $entry_description_field; ?></label>
				<div class="col-sm-9">
					<select name="yandex_yml_description_field" id="input-description-field" class="form-control">
					<option value=""><?php echo $entry_dont_export; ?></option>
					<?php foreach ($oc_desc_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_description_field ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
				</div>
			</div>
				
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-export_tags"><span data-toggle="tooltip" title="<?php echo $entry_export_tags_help; ?>"><?php echo $entry_export_tags; ?></span></label>
				<div class="col-sm-9">
				<div class="checkbox">
				<label><input type="checkbox" name="yandex_yml_export_tags" id="input-export_tags" value="1"<?php echo ($yandex_yml_export_tags ? ' checked="checked"' : ''); ?>></label>
				</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-utm_label"><span data-toggle="tooltip" title="<?php echo $entry_utm_label_help; ?>"><?php echo $entry_utm_label; ?></span></label>
				<div class="col-sm-9">
				<input type="text" name="yandex_yml_utm_label" value="<?php echo $yandex_yml_utm_label; ?>" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-currency"><span data-toggle="tooltip" title="<?php echo $entry_currency_help; ?>"><?php echo $entry_currency; ?></span></label>
				<div class="col-sm-9">
				<select name="yandex_yml_currency" id="input-currency" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $yandex_yml_currency) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } ?>
				<?php } ?>
				</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-oldprice"><span data-toggle="tooltip" title="<?php echo $entry_oldprice_help; ?>"><?php echo $entry_oldprice; ?></span></label>
				<div class="col-sm-9">
				<div class="checkbox">
				<label><input type="checkbox" name="yandex_yml_oldprice" id="input-oldprice" value="1"<?php echo ($yandex_yml_oldprice ? ' checked="checked"' : ''); ?>></label>
				<a href="https://yandex.ru/support/partnermarket/elements/oldprice.html" target="_blank">подробнее</a>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-price_old"><span data-toggle="tooltip" title="<?php echo $entry_oldprice_help; ?>">Выгружать тэг price_old</span></label>
				<div class="col-sm-9">
				<div class="checkbox">
				<label><input type="checkbox" name="yandex_yml_price_old" id="input-price_old" value="1"<?php echo ($yandex_yml_price_old ? ' checked="checked"' : ''); ?>></label>
				<span style="color: red;">Для Яндекс.Маркет не включать (для rozetka.ua)</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-price_promo"><span data-toggle="tooltip" title="В тэге price будет цена без скидки">Выгружать тэг price_promo</span></label>
				<div class="col-sm-9">
				<div class="checkbox">
				<label><input type="checkbox" name="yandex_yml_price_promo" id="input-price_promo" value="1"<?php echo ($yandex_yml_price_promo ? ' checked="checked"' : ''); ?>></label>
				<span style="color: red;">Для Яндекс.Маркет не включать (для aliexpress.com)</span>
				</div>
				</div>
			</div>
				
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-groupprice"><span data-toggle="tooltip" title="<?php echo $entry_groupprice_help; ?>"><?php echo $entry_groupprice; ?></span></label>
				<div class="col-sm-9">
				<select name="yandex_yml_groupprice" id="input-groupprice" class="form-control">
				<?php foreach ($customer_groups as $customer_group) { ?>
				<?php if ($customer_group['customer_group_id'] == $yandex_yml_groupprice) { ?>
				<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
				<?php } ?>
				<?php } ?>
				</select>
				</div>
			</div>
            
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-purchase_price">Выгружать закупочные цены в тэге purchase_price из поля</label>
				<div class="col-sm-9">
                    <?php 
                        $purchase_price_fields = array(
                            'ean' => 'EAN - ean',
                            'jan' => 'JAN - jan',
                            'isbn' => 'ISBN - isbn',
                            'mpn' => 'MPN - mpn',
                            'cost' => 'Закупочная цена - cost'
                        );
                    ?>
					<select name="yandex_yml_purchase_price" id="input-purchase_price" class="form-control">
					<option value=""><?php echo $entry_dont_export; ?></option>
					<?php foreach ($purchase_price_fields as $key=>$name) { ?>
					<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_purchase_price ? ' selected="selected"' : ''); ?>>
					<?php echo $name; ?>
					</option>
					<?php } ?>
					</select>
                    <a href="https://yandex.ru/support/pricelabs/rate/rate-marg.html" target="_blank">подробнее</a>
				</div>
			</div>
                        
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-changeprice"><span data-toggle="tooltip" title="<?php echo $entry_changeprice_help; ?>"><?php echo $entry_changeprice; ?></span></label>
				<div class="col-sm-9">
				<label><input type="text" class="form-control" name="yandex_yml_changeprice" id="input-changeprice" value="<?php echo $yandex_yml_changeprice; ?>"></label>
				</div>
			</div>
				
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-numpictures"><span data-toggle="tooltip" title="<?php echo $entry_numpictures_help; ?>"><?php echo $entry_numpictures; ?></span></label>
				<div class="col-sm-9">
					<label><input type="text" name="yandex_yml_numpictures" value="<?php echo $yandex_yml_numpictures; ?>" id="input-numpictures" class="form-control" /></label>
				</div>
			</div>
				
		</div>

		<div class="tab-pane" id="tab-available">
		
			<div class="form-group">
				<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_store_help; ?>"><?php echo $entry_store; ?></span></label>
				<div class="col-sm-9">
					<label class="radio-inline">
						<?php if ($yandex_yml_store) { ?>
						<input type="radio" name="yandex_yml_store" value="1" checked="checked" />
						<?php echo $text_yes; ?>
						<?php } else { ?>
						<input type="radio" name="yandex_yml_store" value="1" />
						<?php echo $text_yes; ?>
						<?php } ?>
						</label>
						<label class="radio-inline">
						<?php if (!$yandex_yml_store) { ?>
						<input type="radio" name="yandex_yml_store" value="0"  checked="checked" />
						<?php echo $text_no; ?>
						<?php } else { ?>
						<input type="radio" name="yandex_yml_store" value="0" />
						<?php echo $text_no; ?>
						<?php } ?>
					</label>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="unavailable"><span data-toggle="tooltip" title="<?php echo $entry_unavailable_help; ?>"><?php echo $entry_unavailable; ?></span></label>
				<div class="col-sm-9">
				<div class="checkbox">
				<label><input type="checkbox" id="unavailable" name="yandex_yml_unavailable" value="1" <?php echo ($yandex_yml_unavailable ? 'checked="checked"' : ''); ?> /></label>
				</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="in-stock"><span data-toggle="tooltip" title="<?php echo $entry_in_stock_help; ?>"><?php echo $entry_in_stock; ?></span></label>
				<div class="col-sm-9">
					<select name="yandex_yml_in_stock[]" id="in_stock" <?php echo ($yandex_yml_unavailable ? 'disabled="disabled"' : ''); ?> class="form-control" multiple="true" size="4">
					<?php foreach ($stock_statuses as $stock_status) { ?>
					<?php if (in_array($stock_status['stock_status_id'], $yandex_yml_in_stock)) { ?>
					<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-out-of-stock"><span data-toggle="tooltip" title="<?php echo $entry_out_of_stock_help; ?>"><?php echo $entry_out_of_stock; ?></span></label>
				<div class="col-sm-9">
					<select name="yandex_yml_out_of_stock[]" id="input-out-of-stock" class="form-control" multiple="true" size="4">
						<?php foreach ($stock_statuses as $stock_status) { ?>
						<?php if (in_array($stock_status['stock_status_id'], $yandex_yml_out_of_stock)) { ?>
						<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
			
				<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_pickup_help; ?>"><?php echo $entry_pickup; ?></span></label>
				<div class="col-sm-9">
					<label class="radio-inline">
						<?php if ($yandex_yml_pickup) { ?>
						<input type="radio" name="yandex_yml_pickup" value="1" checked="checked" />
						<?php echo $text_yes; ?>
						<?php } else { ?>
						<input type="radio" name="yandex_yml_pickup" value="1" />
						<?php echo $text_yes; ?>
						<?php } ?>
					</label>
					<label class="radio-inline">
						<?php if (!$yandex_yml_pickup) { ?>
						<input type="radio" name="yandex_yml_pickup" value="0" checked="checked"/>
						<?php echo $text_no; ?>
						<?php } else { ?>
						<input type="radio" name="yandex_yml_pickup" value="0"  />
						<?php echo $text_no; ?>
						<?php } ?>
					</label>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-delivery-cost"><span data-toggle="tooltip" title="<?php echo $entry_delivery_cost_help; ?>"><?php echo $entry_delivery_cost; ?></span></label>
				<div class="col-sm-9">
					<label><input type="text" name="yandex_yml_delivery_cost" value="<?php echo $yandex_yml_delivery_cost; ?>" id="input-delivery-cost" class="form-control" /></label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-delivery-days"><span data-toggle="tooltip" title="<?php echo $entry_delivery_days_help; ?>"><?php echo $entry_delivery_days; ?></span></label>
				<div class="col-sm-9">
					<label><input type="text" name="yandex_yml_delivery_days" value="<?php echo $yandex_yml_delivery_days; ?>" id="input-delivery-days" class="form-control" /></label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-delivery-before"><span data-toggle="tooltip" title="<?php echo $entry_delivery_before_help; ?>"><?php echo $entry_delivery_before; ?></span></label>
				<div class="col-sm-9">
					<label><input type="text" name="yandex_yml_delivery_before" value="<?php echo $yandex_yml_delivery_before; ?>" id="input-delivery-before" class="form-control" /></label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-local_delivery"><?php echo $entry_local_delivery; ?></label>
				<div class="col-sm-9">
				<div class="checkbox">
					<label><input type="checkbox" id="input-local_delivery" name="yandex_yml_local_delivery" value="1"<?php echo ($yandex_yml_local_delivery ? ' checked="checked"' : ''); ?>/></label>
					<span style="color: red;">Для Яндекс.Маркет не включать (для torg.mail.ru)</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-quantity">Выгружать остаток товара в тэге quantity</label>
				<div class="col-sm-9">
				<div class="checkbox">
					<label><input type="checkbox" id="input-quantity" name="yandex_yml_quantity" value="1"<?php echo ($yandex_yml_quantity ? ' checked="checked"' : ''); ?>/></label>
					<span style="color: red;">Для Яндекс.Маркет не включать (для Aliexpress.com)</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-stock_quantity"><?php echo $entry_stock_quantity; ?></label>
				<div class="col-sm-9">
				<div class="checkbox">
					<label><input type="checkbox" id="input-stock_quantity" name="yandex_yml_stock_quantity" value="1"<?php echo ($yandex_yml_stock_quantity ? ' checked="checked"' : ''); ?>/></label>
					<span style="color: red;">Для Яндекс.Маркет не включать (для rozetka.ua)</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-count">Выгружать остаток товара в тэге count</label>
				<div class="col-sm-9">
				<div class="checkbox">
					<label><input type="checkbox" id="input-count" name="yandex_yml_count" value="1"<?php echo ($yandex_yml_count ? ' checked="checked"' : ''); ?>/></label>
					<span style="color: red;">Выгружать для "Беру"</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-quantity_in_stock">Выгружать остаток товара в тэге quantity_in_stock</label>
				<div class="col-sm-9">
				<div class="checkbox">
					<label><input type="checkbox" id="input-quantity_in_stock" name="yandex_yml_quantity_in_stock" value="1"<?php echo ($yandex_yml_quantity_in_stock ? ' checked="checked"' : ''); ?>/></label>
					<span style="color: red;">Для Яндекс.Маркет не включать (для prom.ua, satu.kz)</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-quantity_in_stock">Выгружать минимальный заказ в тэгах<br>min-quantity и step-quantity</label>
				<div class="col-sm-9">
				<div class="checkbox">
					<label><input type="checkbox" id="input-min_quantity" name="yandex_yml_min_quantity" value="1"<?php echo ($yandex_yml_min_quantity ? ' checked="checked"' : ''); ?>/></label>
                    <a href="https://yandex.ru/support/partnermarket/elements/min-quantity.html" target="_blank">подробнее</a>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-order_quantity">Выгружать минимальный заказ в тэге<br>minimum_order_quantity﻿</label>
				<div class="col-sm-9">
				<div class="checkbox">
					<label><input type="checkbox" id="input-order_quantity" name="yandex_yml_order_quantity" value="1"<?php echo ($yandex_yml_order_quantity ? ' checked="checked"' : ''); ?>/></label>
					<span style="color: red;">Для Яндекс.Маркет не включать (для prom.ua)</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-dimensions">Выгружать тэг dimensions</label>
				<div class="col-sm-9">
				<div class="checkbox">
					<label><input type="checkbox" id="input-dimensions" name="yandex_yml_dimensions" value="1"<?php echo ($yandex_yml_dimensions ? ' checked="checked"' : ''); ?>/></label>
					<span style="color: red;">Выгружать для "Беру"</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="input-length_width_height">Выгружать тэги length, width, height</label>
				<div class="col-sm-9">
				<div class="checkbox">
					<label><input type="checkbox" id="input-length_width_height" name="yandex_yml_length_width_height" value="1"<?php echo ($yandex_yml_length_width_height ? ' checked="checked"' : ''); ?>/></label>
					<span style="color: red;">Для Яндекс.Маркет не включать (для Aliexpress.com)</span>
				</div>
				</div>
			</div>


		</div>
		
		<div class="tab-pane" id="tab-categories">
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label">
					<span data-toggle="tooltip" title="<?php echo $entry_category_help; ?>"><?php echo $entry_category; ?></span>
				</label>
				<div class="col-sm-9">
					<div class="well well-sm" style="height: 465px; overflow: auto;">
					
					<?php foreach ($categories as $category) { ?>
					<div class="checkbox row-fluid">
					
						<?php if (in_array($category['category_id'], $yandex_yml_categories)) { ?>
						<label><input type="checkbox" name="yandex_yml_categories[]" value="<?php echo $category['category_id']; ?>" checked="checked" class="categ-cb" /></label>
						<?php echo $category['name']; ?>
						<?php } else { ?>
						<label><input type="checkbox" name="yandex_yml_categories[]" value="<?php echo $category['category_id']; ?>" class="categ-cb" /></label>
						<?php echo $category['name']; ?>
						<?php } ?>
						<i class="fa fa-toggle-down expand-categ pull-right" rel="#categ_ctrls_<?php echo $category['category_id']; ?>"> </i>
						<div>
						<table class="table table-striped table-bordered table-hover categ-ctrls"  id="categ_ctrls_<?php echo $category['category_id']; ?>"  style="display: none;">
						<tr>
						<td class="text-left">sales_notes:<input type="text" name="yandex_yml_categ_sales_notes[<?php echo $category['category_id']; ?>]" value="<?php echo (isset($yandex_yml_categ_sales_notes[$category['category_id']]) ? $yandex_yml_categ_sales_notes[$category['category_id']] : ''); ?>"  size="40" maxlength="50" class="form-control categ-ctrl input-sm" /></td>
						<td class="text-left">typePrefix:<input type="text" name="yandex_yml_categ_type_prefix[<?php echo $category['category_id']; ?>]" value="<?php echo (isset($yandex_yml_categ_type_prefix[$category['category_id']]) ? $yandex_yml_categ_type_prefix[$category['category_id']] : ''); ?>"  size="10" maxlength="50" class="form-control categ-ctrl input-sm" /></td>
						<td class="text-left" width="120">Стоим. доставки:<input type="text" name="yandex_yml_categ_delivery_cost[<?php echo $category['category_id']; ?>]" value="<?php echo (isset($yandex_yml_categ_delivery_cost[$category['category_id']]) ? $yandex_yml_categ_delivery_cost[$category['category_id']] : ''); ?>" size="5" class="form-control categ-ctrl input-sm" style="width: 100px;" /></td>
						<td class="text-left" width="120">Срок:<input type="text" name="yandex_yml_categ_delivery_days[<?php echo $category['category_id']; ?>]" value="<?php echo (isset($yandex_yml_categ_delivery_days[$category['category_id']]) ? $yandex_yml_categ_delivery_days[$category['category_id']] : ''); ?>" size="5" class="form-control categ-ctrl input-sm" style="width: 100px;" /></td>
						<td class="text-left" width="70">portal_id:<input type="text" name="yandex_yml_categ_portal_id[<?php echo $category['category_id']; ?>]" value="<?php echo (isset($yandex_yml_categ_portal_id[$category['category_id']]) ? $yandex_yml_categ_portal_id[$category['category_id']] : ''); ?>" size="5" class="form-control categ-ctrl input-sm" style="width: 65px;" /></td>
						</tr>
						</table>
						</div>
					</div>
					<?php } ?>
				</div>
				<a onclick="$(this).parent().find('.categ-cb').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find('.categ-cb').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
				</div>
			</div>
			
				
				<div class="form-group">
					<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_manufacturers_help; ?>"><?php echo $entry_manufacturers; ?></span></label>
					<div class="col-sm-9">
					<div class="well well-sm" style="height: 465px; overflow: auto;">
					<?php foreach ($manufacturers as $manufacturer) { ?>
					<div>
						<div class="checkbox"><?php if (in_array($manufacturer['manufacturer_id'], $yandex_yml_manufacturers)) { ?>
						<label><input type="checkbox" name="yandex_yml_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" /></label>
						<?php } else { ?>
						<label><input type="checkbox" name="yandex_yml_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" /></label>
						<?php } ?>
						<?php echo $manufacturer['name']; ?>
						<i class="fa fa-toggle-down expand-categ pull-right" rel="#manuf_ctrls_<?php echo $manufacturer['manufacturer_id']; ?>"> </i>
						</div>
						<div>
						<table class="table table-striped table-bordered table-hover categ-ctrls"  id="manuf_ctrls_<?php echo $manufacturer['manufacturer_id']; ?>"  style="display: none;">
						<tr>
						<td class="text-left">sales_notes:<input type="text" name="yandex_yml_manuf_sales_notes[<?php echo $manufacturer['manufacturer_id']; ?>]" value="<?php echo (isset($yandex_yml_manuf_sales_notes[$manufacturer['manufacturer_id']]) ? $yandex_yml_manuf_sales_notes[$manufacturer['manufacturer_id']] : ''); ?>"  size="50" maxlength="50" class="form-control categ-ctrl input-sm" /></td>
						<td class="text-left" width="120">Стоим. доставки:<input type="text" name="yandex_yml_manuf_delivery_cost[<?php echo $manufacturer['manufacturer_id']; ?>]" value="<?php echo (isset($yandex_yml_manuf_delivery_cost[$manufacturer['manufacturer_id']]) ? $yandex_yml_manuf_delivery_cost[$manufacturer['manufacturer_id']] : ''); ?>" size="5" class="form-control categ-ctrl input-sm" style="width: 100px;" /></td>
						<td class="text-left" width="120">Срок:<input type="text" name="yandex_yml_manuf_delivery_days[<?php echo $manufacturer['manufacturer_id']; ?>]" value="<?php echo (isset($yandex_yml_manuf_delivery_days[$manufacturer['manufacturer_id']]) ? $yandex_yml_manuf_delivery_days[$manufacturer['manufacturer_id']] : ''); ?>" size="5" class="form-control categ-ctrl input-sm" style="width: 100px;" /></td>
						</tr>
						</table>
						</div>
					</div>
					<?php } ?>
				    </div>
				    <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
					</div>
				</div>
				
						

				<div class="form-group">
					<label class="col-sm-3 control-label" for="blacklist-type-select"><?php echo $entry_blacklist_type; ?></label>
					<div class="col-sm-9">
					<select name="yandex_yml_blacklist_type" id="blacklist-type-select" class="form-control">
						<option value="black"<?php echo ($yandex_yml_blacklist_type == 'black' ? ' selected' : ''); ?>><?php echo $text_blacklist; ?></option>
						<option value="white"<?php echo ($yandex_yml_blacklist_type == 'white' ? ' selected' : ''); ?>><?php echo $text_whitelist; ?></option>
					  </select>
					</div>
				</div>

		        <div class="form-group">
					<label class="col-sm-3 control-label" for="input-yandex-yml-product-blacklist">&nbsp;</label>
					<div class="col-sm-9">
					<input type="text" name="yandex_yml_product_blacklist" value="" id="input-yandex-yml-product-blacklist" class="form-control"/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">
						<div id="blacklist-product-label"><span data-toggle="tooltip" title="<?php echo $entry_blacklist_help; ?>"><?php echo $entry_blacklist; ?></span></div>
						<div id="whitelist-product-label"><span data-toggle="tooltip" title="<?php echo $entry_whitelist_help; ?>"><?php echo $entry_whitelist; ?></span></div>
					</label>
					<div class="col-sm-9">
						<div id="blacklist-product" class="well well-sm" style="height: 465px; overflow: auto;">
						<?php foreach ($blacklist as $product_bl) { ?>
						<div id="blacklist-product<?php echo $product_bl['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_bl['name']; ?>
						<input type="hidden" name="yandex_yml_blacklist[]" value="<?php echo $product_bl['product_id']; ?>" />
						</div>
						<?php } ?>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-pricefrom"><span data-toggle="tooltip" title="<?php echo $entry_pricefrom_help; ?>"><?php echo $entry_pricefrom; ?></span></label>
					<div class="col-sm-9">
						<label>
							<input name="yandex_yml_pricefrom" value="<?php echo floatval($yandex_yml_pricefrom); ?>" id="input-pricefrom" class="form-control" />
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-priceto"><span data-toggle="tooltip" title="<?php echo $entry_priceto_help; ?>"><?php echo $entry_priceto; ?></span></label>
					<div class="col-sm-9">
						<label>
							<input name="yandex_yml_priceto" value="<?php echo $yandex_yml_priceto; ?>" id="input-priceto" class="form-control" />
						</label>
					</div>
				</div>
                
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-image_mandatory"><span><?php echo $entry_image_mandatory; ?></span></label>
					<div class="col-sm-9">
                        <label><input type="checkbox" id="input-image_mandatory" name="yandex_yml_image_mandatory" value="1"<?php echo ($yandex_yml_image_mandatory ? ' checked="checked"' : ''); ?>/></label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-exportattr"><span>Выгружать только товары с атрибутом</span></label>
					<div class="col-sm-9">
					<select name="yandex_yml_exportattr" id="input-exportattr" class="form-control">
					<option value="0">Атрибуты не учитывать</option>
					<?php
					$attr_group_id = -1;
					foreach ($attributes as $key=>$attribute) {
						if ($attr_group_id != $attribute['attribute_group_id']) {
							echo '<optgroup label="'.$attribute['attribute_group'].'">';
							$attr_group_id = $attribute['attribute_group_id'];
						}
						echo '<option value="'.$attribute['attribute_id'].'"'.($yandex_yml_exportattr == $attribute['attribute_id'] ? ' selected="selected"' : '').'>'.$attribute['name'].'</option>';
						if (!isset($attributes[$key+1]) || ($attr_group_id != $attributes[$key+1]['attribute_group_id'])) {
							echo '</optgroup>';
						}
					}
					?>
					</select>
					</div>
				</div>
			
			</div>

			<div class="tab-pane" id="tab-attributes">
			
				<div class="col-sm-12"><?php echo $tab_attributes_description; ?>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-attributes"><span data-toggle="tooltip" title="<?php echo $entry_attributes_help; ?>"><?php echo $entry_attributes; ?></span></label>
					<div class="col-sm-9">
						<div class="well well-sm" style="height: 465px; overflow: auto;">
						<?php $attr_group_id = -1; ?>
						<?php foreach ($attributes as $attribute) {
							if ($attr_group_id != $attribute['attribute_group_id']) {
								echo '<div><b>'.$attribute['attribute_group'].'</b></div>';
								$attr_group_id = $attribute['attribute_group_id'];
							}
						?>
						<div>
						<div class="checkbox">
							<?php if (in_array($attribute['attribute_id'], $yandex_yml_attributes)) { ?>
							<label><input type="checkbox" name="yandex_yml_attributes[]" value="<?php echo $attribute['attribute_id']; ?>" checked="checked" /></label>
							<?php echo $attribute['name']; ?>
							<?php } else { ?>
							<label><input type="checkbox" name="yandex_yml_attributes[]" value="<?php echo $attribute['attribute_id']; ?>" /></label>
							<?php echo $attribute['name']; ?>
							<?php } ?>
						</div>
						</div>
						<?php } ?>
					</div>
					<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
					</div>
				</div>
				
				
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-all-adult"><span data-toggle="tooltip" title="<?php echo $entry_all_adult_help; ?>"><?php echo $entry_all_adult; ?></span></label>
					<div class="col-sm-9">
					<div class="checkbox">
						<label><input type="checkbox" id="input-all-adult" name="yandex_yml_all_adult" value="1"<?php echo ($yandex_yml_all_adult ? ' checked="checked"' : ''); ?>/></label>
					</div>
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-adult"><span data-toggle="tooltip" title="<?php echo $entry_adult_help; ?>"><?php echo $entry_adult; ?></span></label>
					<div class="col-sm-9">
						<select name="yandex_yml_adult" id="input-adult" class="form-control">
							<option value="0"><?php echo $text_no; ?></option>
							<?php
							$attr_group_id = -1;
							foreach ($attributes as $key=>$attribute) {
								if ($attr_group_id != $attribute['attribute_group_id']) {
									echo '<optgroup label="'.$attribute['attribute_group'].'">';
									$attr_group_id = $attribute['attribute_group_id'];
								}
								echo '<option value="'.$attribute['attribute_id'].'"'.($yandex_yml_adult == $attribute['attribute_id'] ? ' selected="selected"' : '').'>'.$attribute['name'].'</option>';
								if (!isset($attributes[$key+1]) || ($attr_group_id != $attributes[$key+1]['attribute_group_id'])) {
									echo '</optgroup>';
								}
							}
							?>
							</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-all-manufacturer-warranty"><span data-toggle="tooltip" title="<?php echo $entry_all_manufacturer_warranty_help; ?>"><?php echo $entry_all_manufacturer_warranty; ?></span></label>
					<div class="col-sm-9">
					<div class="checkbox">
						<label><input type="checkbox" id="input-all-manufacturer-warranty" name="yandex_yml_all_manufacturer_warranty" value="1"<?php echo ($yandex_yml_all_manufacturer_warranty ? ' checked="checked"' : ''); ?>/></label>
					</div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-manufacturer-warranty"><span data-toggle="tooltip" title="<?php echo $entry_manufacturer_warranty_help; ?>"><?php echo $entry_manufacturer_warranty; ?></span></label>
					<div class="col-sm-9">
					<select name="yandex_yml_manufacturer_warranty" id="input-manufacturer-warranty" class="form-control">
					<option value="0"><?php echo $text_no; ?></option>
					<?php
					$attr_group_id = -1;
					foreach ($attributes as $key=>$attribute) {
						if ($attr_group_id != $attribute['attribute_group_id']) {
							echo '<optgroup label="'.$attribute['attribute_group'].'">';
							$attr_group_id = $attribute['attribute_group_id'];
						}
						echo '<option value="'.$attribute['attribute_id'].'"'.($yandex_yml_manufacturer_warranty == $attribute['attribute_id'] ? ' selected="selected"' : '').'>'.$attribute['name'].'</option>';
						if (!isset($attributes[$key+1]) || ($attr_group_id != $attributes[$key+1]['attribute_group_id'])) {
							echo '</optgroup>';
						}
					}
					?>
					</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label" for="select-country-of-origin"><span data-toggle="tooltip" title="<?php echo $entry_country_of_origin_help; ?>"><?php echo $entry_country_of_origin; ?></span></label>
					<div class="col-sm-9">
					<select name="yandex_yml_country_of_origin" id="select-country-of-origin" class="form-control">
					<option value="0"><?php echo $text_no; ?></option>
					<?php
					$attr_group_id = -1;
					foreach ($attributes as $key=>$attribute) {
						if ($attr_group_id != $attribute['attribute_group_id']) {
							echo '<optgroup label="'.$attribute['attribute_group'].'">';
							$attr_group_id = $attribute['attribute_group_id'];
						}
						echo '<option value="'.$attribute['attribute_id'].'"'.($yandex_yml_country_of_origin == $attribute['attribute_id'] ? ' selected="selected"' : '').'>'.$attribute['name'].'</option>';
						if (!isset($attributes[$key+1]) || ($attr_group_id != $attributes[$key+1]['attribute_group_id'])) {
							echo '</optgroup>';
						}
					}
					?>
					</select>
					<span style="color: red;">Выгружать для "Беру"</span> <a href="https://yandex.ru/support/marketplace/catalog/yml-simple.html" target="_blank">подробнее</a>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label" for="select-tn-ved-codes"><span data-toggle="tooltip" title="При наличии у товара этого атрибута, товар будет экспортироваться с тэгом tn-ved-codes">Атрибут, содержащий ТН ВЭД</span></label>
					<div class="col-sm-9">
					<select name="yandex_yml_tn_ved_codes" id="select-tn-ved-codes" class="form-control">
					<option value="0"><?php echo $text_no; ?></option>
					<?php
					$attr_group_id = -1;
					foreach ($attributes as $key=>$attribute) {
						if ($attr_group_id != $attribute['attribute_group_id']) {
							echo '<optgroup label="'.$attribute['attribute_group'].'">';
							$attr_group_id = $attribute['attribute_group_id'];
						}
						echo '<option value="'.$attribute['attribute_id'].'"'.($yandex_yml_tn_ved_codes == $attribute['attribute_id'] ? ' selected="selected"' : '').'>'.$attribute['name'].'</option>';
						if (!isset($attributes[$key+1]) || ($attr_group_id != $attributes[$key+1]['attribute_group_id'])) {
							echo '</optgroup>';
						}
					}
					?>
					</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-product-rel"><span data-toggle="tooltip" title="<?php echo $entry_product_rel_help; ?>"><?php echo $entry_product_rel; ?></span></label>
					<div class="col-sm-9">
					<div class="checkbox">
						<label><input type="checkbox" id = "input-product-rel" name="yandex_yml_product_rel"<?php echo ($yandex_yml_product_rel ? ' checked="checked"' : ''); ?>/></label>
					</div>
					</div>
				</div>
				
			</div>
			
	        <div class="tab-pane" id="tab-tailor">
				<div class="col-sm-12">
					<?php echo $tab_tailor_description; ?>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_color_option_help; ?>"><?php echo $entry_color_option; ?></span></label>
					<div class="col-sm-9">
						<div class="well well-sm" style="height: 465px; overflow: auto;">
					<?php foreach ($options as $option) { ?>
					<div >
					<div class="checkbox">
						<?php if (in_array($option['option_id'], $yandex_yml_color_options)) { ?>
						<label><input type="checkbox" name="yandex_yml_color_options[]" value="<?php echo $option['option_id']; ?>" checked="checked" /></label>
						<?php echo $option['name']; ?>
						<?php } else { ?>
						<label><input type="checkbox" name="yandex_yml_color_options[]" value="<?php echo $option['option_id']; ?>" /></label>
						<?php echo $option['name']; ?>
						<?php } ?>
					</div>
					</div>
					<?php } ?>
				</div>
				<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_size_option_help; ?>"><?php echo $entry_size_option; ?></span><br/><span data-toggle="tooltip" title="<?php echo $entry_size_unit_help; ?>"><?php echo $entry_size_unit; ?></span></label>
					<div class="col-sm-9">
						<div class="well well-sm" style="height: 465px; overflow-y: auto; overflow-x: hidden;">
							<?php foreach ($options as $option) { ?>
							
								<div class="checkbox row-fluid">
								
									<?php if (in_array($option['option_id'], $yandex_yml_size_options)) { ?>
									<label><input type="checkbox" name="yandex_yml_size_options[]" value="<?php echo $option['option_id']; ?>" checked="checked" /></label>
									<?php echo $option['name']; ?>
									<?php } else { ?>
									<label><input type="checkbox" name="yandex_yml_size_options[]" value="<?php echo $option['option_id']; ?>" /></label>
									<?php echo $option['name']; ?>
									<?php } ?>
								
								
								<select name="yandex_yml_size_units[<?php echo $option['option_id']; ?>]" style="float:right;">
								<?php $yandex_yml_size_unit = (isset($yandex_yml_size_units[$option['option_id']]) ? $yandex_yml_size_units[$option['option_id']] : ''); ?>
								<option value="" <?php echo ($yandex_yml_size_unit == '' ? ' selected="selected"' : ''); ?>><?php echo $text_no; ?></option>
								<?php foreach ($size_units_orig as $key=>$item) { ?>
								<option value="<?php echo $key; ?>" <?php echo ($yandex_yml_size_unit == $key ? ' selected="selected"' : ''); ?>><?php echo $item; ?></option>
								<?php } ?>
								<?php foreach ($size_units_type as $key=>$item) { ?>
								<option value="<?php echo $key; ?>" <?php echo ($yandex_yml_size_unit == $key ? ' selected="selected"' : ''); ?>><?php echo $item; ?></option>
								<?php } ?>
								</select>
								
								</div>
							
							<?php } ?>
						</div>
						<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_optioned_name_help; ?>"><?php echo $entry_optioned_name; ?></span></label>
					<div class="col-sm-9">
						<label><input type="radio" name="yandex_yml_optioned_name" value="no" <?php echo (!$yandex_yml_optioned_name || ($yandex_yml_optioned_name == 'no') ? ' checked="checked"' : ''); ?>>
						<?php echo $optioned_name_no; ?></label><br />
						
						<label><input type="radio" name="yandex_yml_optioned_name" value="short" <?php echo ($yandex_yml_optioned_name == 'short' ? ' checked="checked"' : ''); ?>>
						<?php echo $optioned_name_short; ?></label><br />
						
						<label><input type="radio" name="yandex_yml_optioned_name" value="long" <?php echo ($yandex_yml_optioned_name == 'long' ? ' checked="checked"' : ''); ?>>
						<?php echo $optioned_name_long; ?></label>
					</div>
				</div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="input-option_image"><?php echo $entry_option_image; ?></label>
                    <div class="col-sm-9">
                    <div class="checkbox">
                    <label><input type="checkbox" name="yandex_yml_option_image" id="input-option_image" value="1"<?php echo ($yandex_yml_option_image ? ' checked="checked"' : ''); ?>></label>
                    </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="input-option_image-pro"><span data-toggle="tooltip" title="Только если модуль установлен">Брать картинки из модуля <a href="http://liveopencart.ru/opencart-moduli-shablony/moduli/vneshniy-vid/izobrajeniya-optsiy-pro">Изображения опций PRO</a></span></label>
                    <div class="col-sm-9">
                    <div class="checkbox">
                    <label><input type="checkbox" name="yandex_yml_option_image_pro" id="input-option_image-pro" value="1"<?php echo ($yandex_yml_option_image_pro ? ' checked="checked"' : ''); ?>></label>
                    </div>
                    </div>
                </div>
				
			</div>

	        <div class="tab-pane" id="tab-promo">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="input-auto_discounts"><span data-toggle="tooltip" title="Автоматический расчет скидок Яндекс.Маркетом">На все товары enable_auto_discounts</span></label>
                    <div class="col-sm-9">
                    <div class="checkbox">
                    <label><input type="checkbox" name="yandex_yml_auto_discounts" id="input-auto_discounts" value="1"<?php echo ($yandex_yml_auto_discounts ? ' checked="checked"' : ''); ?>></label>
					<a href="https://yandex.ru/support/partnermarket/elements/shop-enable_auto_discounts.html" target="_blank">подробнее</a>
                    </div>
                    </div>
                </div>
                
				<div class="form-group">
					<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="Купоны">Выгружать промокоды</span></label>
					<div class="col-sm-9">
                        <a href="https://yandex.ru/support/partnermarket/elements/promo-code.html" target="_blank">подробнее</a><br>
                        <div class="well well-sm" style="height: 200px;  overflow-y: auto; overflow-x: hidden;">
                        <?php foreach ($coupons as $coupon) {
                                if (!$coupon['status']) {
                                    continue;
                                }
                        ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                    <?php if (in_array($coupon['coupon_id'], $yandex_yml_coupons)) { ?>
                                        <label><input type="checkbox" name="yandex_yml_coupons[]" value="<?php echo $coupon['coupon_id']; ?>" checked="checked" />
                                        <?php echo $coupon['name']; ?>
                                        </label>
                                    <?php } else { ?>
                                        <label><input type="checkbox" name="yandex_yml_coupons[]" value="<?php echo $coupon['coupon_id']; ?>" />
                                        <?php echo $coupon['name']; ?>
                                        </label>
                                    <?php } ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <input name="yandex_yml_coupon_urls[<?php echo $coupon['coupon_id']; ?>]" value="<?php echo $yandex_yml_coupon_urls[$coupon['coupon_id']]; ?>" placeholder="URL страницы акции" class="form-control input-sm" />
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                    </div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="При покупке товара дарят подарок. Один товар участвует в одной акции.">Промоакции "подарок при покупке"</span></label>
					<div class="col-sm-9" id="promo-gifts">
						<a href="https://yandex.ru/support/partnermarket/elements/promo-gift.html" target="_blank">подробнее</a>
						<i class="fa fa-plus-circle add-gift" style="float:right;"></i>
						<?php foreach ($yandex_yml_gift_promo_gift as $gift_id=>$gift_name) { ?>
						<div class="gift-block">
						  <table class="gift-ctrl" width="100%">
							<tbody><tr>
								<td width="15%" style="padding: 0 5px 0 0;">Название&nbsp;акции: <input type="text" name="yandex_yml_gift_promo_name[]" value="<?php echo $yandex_yml_gift_promo_name[$gift_id]; ?>" size="40" class="form-control input-sm" /></td>
								<td width="20%" style="padding: 0 5px 0 0;">URL&nbsp;страницы: <input type="text" name="yandex_yml_gift_promo_url[]" value="<?php echo $yandex_yml_gift_promo_url[$gift_id]; ?>" size="40" class="form-control input-sm" /></td>
								<td width="15%" style="padding: 0 5px 0 0;">Подарок: <input type="text" name="yandex_yml_gift_promo_gift[]" value="<?php echo $yandex_yml_gift_promo_gift[$gift_id]; ?>" size="30" class="form-control input-sm" /></td>
								<td style="padding: 0 5px 0 0;"><img class="gift-image" src="<?php echo ($yandex_yml_gift_promo_img[$gift_id] ? $yandex_yml_gift_promo_img[$gift_id] : '/image/no_image.png'); ?>" width="40" height="40" /></td>
								<td width="20%" style="padding: 0 5px 0 0;">Фото&nbsp;подарка: <input type="text" class="form-control gift-image-url input-sm" name="yandex_yml_gift_promo_img[]" value="<?php echo $yandex_yml_gift_promo_img[$gift_id]; ?>" placeholder="<?php echo HTTPS_CATALOG; ?>..." size="40" /></td>
								<td width="15%" style="padding: 0 5px 0 0;">Товары,&nbsp;у&nbsp;которых&nbsp;в&nbsp;поле: 
							<select name="yandex_yml_gift_promo_field[]" class="form-control input-sm">
									<option value="p.product_id"<?php echo ('p.product_id'==$yandex_yml_gift_promo_field[$gift_id] ? ' selected="selected"' : ''); ?>>product_id</option>
								<?php foreach ($oc_fields as $key=>$name) { ?>
									<option value="<?php echo $key; ?>"<?php echo ($key==$yandex_yml_gift_promo_field[$gift_id] ? ' selected="selected"' : ''); ?>>
										<?php echo $name; ?>
									</option>
								<?php } ?>
							</select></td>
							<td width="10%" style="padding: 0 5px 0 0;">сохранено&nbsp;значение: <input type="text" name="yandex_yml_gift_promo_val[]" value="<?php echo $yandex_yml_gift_promo_val[$gift_id]; ?>" size="20" class="form-control input-sm" /></td>
							<td><i class="fa fa-minus-circle delete-gift"></i></td>
							</tr></tbody>
						  </table>
						</div>
						<?php } ?>
                    </div>
				</div>
				
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span data-toggle="tooltip" title="Один или несколько XML-тэгов promo c промоакциями">Свой тэг &lt;promo&gt;</span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" style="height: 465px; overflow: auto;" name="yandex_yml_custom_promo" placeholder="&lt;promo id=&quot;идентификатор акции&quot; type=&quot;gift with purchase&quot;&gt;
      &lt;start-date&gt;начало акции&lt;/start-date&gt;
      &lt;end-date&gt;завершение акции&lt;/end-date&gt;
      &lt;description&gt;краткое описание&lt;/description&gt;
      &lt;url&gt;акция на сайте магазина&lt;/url&gt;
      &lt;purchase&gt;
        &lt;required-quantity&gt;количество товаров за полную стоимость&lt;/required-quantity&gt;
        &lt;product offer-id=&quot;идентификатор предложения, участвующего в акции&quot;/&gt;
        ...
        &lt;product category-id=&quot;идентификатор категории, участвующей в акции&quot;/&gt;
        ...
      &lt;/purchase&gt;
      &lt;promo-gifts&gt;
        подарки, участвующие в акции
      &lt;/promo-gifts&gt;
   &lt;/promo&gt;"><?php echo $yandex_yml_custom_promo; ?></textarea>
                        <span style="color: red;">Невалидный XML в этом поле может испортить весь экспорт. Проверяйте YML в браузере.</span>
                        <a href="https://yandex.ru/support/partnermarket/elements/promos.html" target="_blank">подробнее</a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span data-toggle="tooltip" title="Один или несколько XML-тэгов gift c подарками, которые не размещаются на Маркете">Свой тэг &lt;gift&gt;</span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" style="height: 200px; overflow: auto;" name="yandex_yml_custom_gifts" placeholder="&lt;gift id=&quot;id товара&quot;&gt;
    &lt;name&gt;название товара&lt;/name&gt;
    &lt;picture&gt;ссылка на изображение&lt;/picture&gt;
  &lt;/gift&gt;"><?php echo $yandex_yml_custom_gifts; ?></textarea>
                        <span style="color: red;">Невалидный XML в этом поле может испортить весь экспорт. Проверяйте YML в браузере.</span>
                        <a href="https://yandex.ru/support/partnermarket/elements/promo-gift.html" target="_blank">подробнее</a>
                    </div>
                </div>
                
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-condition-used"><span data-toggle="tooltip" title="Значение атрибута - причина уценки">Аттрибут уцененного б/у товара</span></label>
					<div class="col-sm-9">
					<select name="yandex_yml_condition_used" id="input-condition-used" class="form-control">
					<option value="0"><?php echo $text_no; ?></option>
					<?php
					$attr_group_id = -1;
					foreach ($attributes as $key=>$attribute) {
						if ($attr_group_id != $attribute['attribute_group_id']) {
							echo '<optgroup label="'.$attribute['attribute_group'].'">';
							$attr_group_id = $attribute['attribute_group_id'];
						}
						echo '<option value="'.$attribute['attribute_id'].'"'.($yandex_yml_condition_used == $attribute['attribute_id'] ? ' selected="selected"' : '').'>'.$attribute['name'].'</option>';
						if (!isset($attributes[$key+1]) || ($attr_group_id != $attributes[$key+1]['attribute_group_id'])) {
							echo '</optgroup>';
						}
					}
					?>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-condition-likenew"><span data-toggle="tooltip" title="Значение атрибута - причина уценки">Аттрибут уцененного нового товара</span></label>
					<div class="col-sm-9">
					<select name="yandex_yml_condition_likenew" id="input-condition-likenew" class="form-control">
					<option value="0"><?php echo $text_no; ?></option>
					<?php
					$attr_group_id = -1;
					foreach ($attributes as $key=>$attribute) {
						if ($attr_group_id != $attribute['attribute_group_id']) {
							echo '<optgroup label="'.$attribute['attribute_group'].'">';
							$attr_group_id = $attribute['attribute_group_id'];
						}
						echo '<option value="'.$attribute['attribute_id'].'"'.($yandex_yml_condition_likenew == $attribute['attribute_id'] ? ' selected="selected"' : '').'>'.$attribute['name'].'</option>';
						if (!isset($attributes[$key+1]) || ($attr_group_id != $attributes[$key+1]['attribute_group_id'])) {
							echo '</optgroup>';
						}
					}
					?>
					</select>
					</div>
				</div>
                
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="input-opt_discount"><span data-toggle="tooltip" title="Из вкладки &quot;Скидки&quot; товара">Выгружать оптовые цены</span></label>
                    <div class="col-sm-9">
                    <div class="checkbox">
                    <label><input type="checkbox" name="yandex_yml_opt_discount" id="input-opt_discount" value="1"<?php echo ($yandex_yml_opt_discount ? ' checked="checked"' : ''); ?>></label>
                    <span style="color: red;">Для Яндекс.Маркет не включать (для prom.ua)</span>
                    </div>
                    </div>
                </div>
                
			</div>
            
		  </div>
		  <input type="submit" id="submitting_submit" style="display: none;" />
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
$('#input-token').change(function() {
	var token = $(this).val().replace(/[^A-z0-9]/g, '');
	$(this).val(token);
	var url_tail = token ? '&token=' + token : '';
	$('#yml_feed_url').text('<?php echo $data_feed; ?>' + url_tail);
	$('#yml_feed_url').attr('href', '<?php echo $data_feed; ?>' + url_tail);
	
	$('#yml_static_file').text('<?php echo $export_url . $CONFIG_PREFIX; ?>' + token + '.xml');
})
$('#form-edit').submit(function() {
	$('.categ-ctrl').each(function() {
		if ($(this).val() == '')
			$(this).attr('disabled', 'disabled');
	})
	return true;
});
$('#unavailable').change(function() {
	if ($(this).attr('checked')) {
		$('#in_stock').attr('disabled', 'disabled');
	}
	else {
		$('#in_stock').attr('disabled', false);
		$(this).removeAttr('disabled');
	}
})

$('.categ-ctrls').each(function() {
	var tbl = $(this);
	$(this).find('input[type="text"]').each(function() {
		if ($(this).val() != '') {
			tbl.show();
		}
	})
	$(this).find('input[type="checkbox"]:checked').each(function() {
		tbl.show();
	})
})

$('.expand-categ').click(function() {
	var rel = $($(this).attr('rel'));
	rel.toggle();
})

$('#blacklist-type-select').change(function() {
	if ($(this).val() == 'black') {
		$('#blacklist-product-label').show();
		$('#whitelist-product-label').hide();
	}
	else {
		$('#whitelist-product-label').show();
		$('#blacklist-product-label').hide();
	}
})
$('#blacklist-type-select').trigger('change');

$('input[name="yandex_yml_product_blacklist"]').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(item) {
		$('#blacklist-product' + item.value).val('');
		$('#blacklist-product' + item.value).remove();

		$('#blacklist-product').append('<div id="blacklist-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="yandex_yml_blacklist[]" value="' + item['value'] + '" /></div>');	

		$('input[name="yandex_yml_product_blacklist"]').val('');
		
		return false;
	}
});

$('#blacklist-product').on('click', 'i', function() {
    $(this).parent().remove();
});

$('.add-gift').click(function() {
    var gift_block = $('#gift-seed').clone();
    gift_block.removeAttr('id');
    $('#promo-gifts').append(gift_block);
    gift_block.show();
})

$('#promo-gifts').on('click', '.delete-gift', function() {
    $(this).parents('.gift-block').remove();
})
$('#promo-gifts').on('change', '.gift-image-url', function() {
    $(this).parents('.gift-block').find('.gift-image').attr('src', $(this).val());
})
//--></script>

<div id="gift-seed" style="display:none;" class="gift-block">
  <table class="gift-ctrl" width="100%">
    <tbody><tr>
        <td width="15%" style="padding: 0 5px 0 0;">Название&nbsp;акции: <input type="text" name="yandex_yml_gift_promo_name[]" value="" size="40" class="form-control input-sm" /></td>
        <td width="20%" style="padding: 0 5px 0 0;">URL&nbsp;акции: <input type="text" name="yandex_yml_gift_promo_url[]" value="" size="40" class="form-control input-sm" /></td>
        <td width="15%" style="padding: 0 5px 0 0;">Подарок: <input type="text" name="yandex_yml_gift_promo_gift[]" value="" size="30" class="form-control input-sm" /></td>
        <td style="padding: 0 5px 0 0;"><img class="gift-image" src="/image/no_image.png" width="40" height="40" /></td>
        <td width="20%" style="padding: 0 5px 0 0;">Фото&nbsp;подарка: <input type="text" class="gift-image-url form-control input-sm" name="yandex_yml_gift_promo_img[]" value="" placeholder="<?php echo HTTPS_CATALOG; ?>..." size="40" /></td>
        <td width="15%" style="padding: 0 5px 0 0;">Товары,&nbsp;у&nbsp;которых&nbsp;в&nbsp;поле: 
    <select name="yandex_yml_gift_promo_field[]" class="form-control input-sm">
            <option value="p.product_id">product_id</option>
        <?php foreach ($oc_fields as $key=>$name) { ?>
            <option value="<?php echo $key; ?>">
                <?php echo $name; ?>
            </option>
        <?php } ?>
    </select></td>
    <td width="10%" style="padding: 0 5px 0 0;">сохранено&nbsp;значение: <input type="text" name="yandex_yml_gift_promo_val[]" value="" size="20" class="form-control input-sm" /></td>
    <td><i class="fa fa-minus-circle delete-gift"></i></td>
    </tr></tbody>
  </table>
</div>
<?php echo $footer; ?>
