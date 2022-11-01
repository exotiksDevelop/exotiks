<?php echo $header; ?>
<link href="view/javascript/bootstrap/css/bootstrap-switch.css" rel="stylesheet">
<script src="view/javascript/bootstrap/js/bootstrap-switch.js"></script>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-quick_product_edit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-quick_product_edit" class="form-horizontal">
					<div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="quick_product_edit_status" id="input-status" class="form-control">
                <?php if ($quick_product_edit_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-open"><?php echo $entry_open; ?></label>
            <div class="col-sm-10">
              <select name="quick_product_edit_open" id="input-open" class="form-control">
                <option value="tab_general" <?php echo ($quick_product_edit_open == 'tab_general') ? 'selected="selected"' : ''; ?>><?php echo $tab_general; ?></option>
                <option value="tab_data" <?php echo ($quick_product_edit_open == 'tab_data') ? 'selected="selected"' : ''; ?>><?php echo $tab_data; ?></option>
                <option value="tab_links" <?php echo ($quick_product_edit_open == 'tab_links') ? 'selected="selected"' : ''; ?>><?php echo $tab_links; ?></option>
                <option value="tab_attribute" <?php echo ($quick_product_edit_open == 'tab_attribute') ? 'selected="selected"' : ''; ?>><?php echo $tab_attribute; ?></option>
                <option value="tab_option" <?php echo ($quick_product_edit_open == 'tab_option') ? 'selected="selected"' : ''; ?>><?php echo $tab_option; ?></option>
                <option value="tab_discount" <?php echo ($quick_product_edit_open == 'tab_discount') ? 'selected="selected"' : ''; ?>><?php echo $tab_discount; ?></option>
                <option value="tab_special" <?php echo ($quick_product_edit_open == 'tab_special') ? 'selected="selected"' : ''; ?>><?php echo $tab_special; ?></option>
                <option value="tab_image" <?php echo ($quick_product_edit_open == 'tab_image') ? 'selected="selected"' : ''; ?>><?php echo $tab_image; ?></option>
                <option value="tab_reward" <?php echo ($quick_product_edit_open == 'tab_reward') ? 'selected="selected"' : ''; ?>><?php echo $tab_reward; ?></option>
              </select>
            </div>
          </div>
					 <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
            <li><a href="#tab-attribute" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
            <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
            <li><a href="#tab-discount" data-toggle="tab"><?php echo $tab_discount; ?></a></li>
            <li><a href="#tab-special" data-toggle="tab"><?php echo $tab_special; ?></a></li>
            <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
            <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tab_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[general][status]" <?php echo (!empty($quick_product_edit_tabs['general']['status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[general][name]" <?php echo (!empty($quick_product_edit_tabs['general']['name'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_description; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[general][description]" <?php echo (!empty($quick_product_edit_tabs['general']['description'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_title; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[general][meta_title]" <?php echo (!empty($quick_product_edit_tabs['general']['meta_title'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_description; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[general][meta_description]" <?php echo (!empty($quick_product_edit_tabs['general']['meta_description'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_keyword; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[general][meta_keyword]" <?php echo (!empty($quick_product_edit_tabs['general']['meta_keyword'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tag; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[general][tag]" <?php echo (!empty($quick_product_edit_tabs['general']['tag'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-data">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tab_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][status]" <?php echo (!empty($quick_product_edit_tabs['data']['status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][image]" <?php echo (!empty($quick_product_edit_tabs['data']['image'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_model; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][model]" <?php echo (!empty($quick_product_edit_tabs['data']['model'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_sku; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][sku]" <?php echo (!empty($quick_product_edit_tabs['data']['sku'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_upc; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][upc]" <?php echo (!empty($quick_product_edit_tabs['data']['upc'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_ean; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][ean]" <?php echo (!empty($quick_product_edit_tabs['data']['ean'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_jan; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][jan]" <?php echo (!empty($quick_product_edit_tabs['data']['jan'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_isbn; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][isbn]" <?php echo (!empty($quick_product_edit_tabs['data']['isbn'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_mpn; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][mpn]" <?php echo (!empty($quick_product_edit_tabs['data']['mpn'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_location; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][location]" <?php echo (!empty($quick_product_edit_tabs['data']['location'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_price; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][price]" <?php echo (!empty($quick_product_edit_tabs['data']['price'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tax_class; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][tax_class]" <?php echo (!empty($quick_product_edit_tabs['data']['tax_class'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_quantity; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][quantity]" <?php echo (!empty($quick_product_edit_tabs['data']['quantity'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_minimum; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][minimum_quantity]" <?php echo (!empty($quick_product_edit_tabs['data']['minimum_quantity'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_subtract; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][subtract_stock]" <?php echo (!empty($quick_product_edit_tabs['data']['subtract_stock'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_stock_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][out_of_stock]" <?php echo (!empty($quick_product_edit_tabs['data']['out_of_stock'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_shipping; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][shipping]" <?php echo (!empty($quick_product_edit_tabs['data']['shipping'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_keyword; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][keyword]" <?php echo (!empty($quick_product_edit_tabs['data']['keyword'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_date_available; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][date_available]" <?php echo (!empty($quick_product_edit_tabs['data']['date_available'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_dimension; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][dimension]" <?php echo (!empty($quick_product_edit_tabs['data']['dimension'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_length; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][length]" <?php echo (!empty($quick_product_edit_tabs['data']['length'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_weight; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][weight]" <?php echo (!empty($quick_product_edit_tabs['data']['weight'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_weight_class; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][weight_class]" <?php echo (!empty($quick_product_edit_tabs['data']['weight_class'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_product_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][product_status]" <?php echo (!empty($quick_product_edit_tabs['data']['product_status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[data][sort_order]" <?php echo (!empty($quick_product_edit_tabs['data']['sort_order'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-links">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tab_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[links][status]" <?php echo (!empty($quick_product_edit_tabs['links']['status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_manufacturer; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[links][manufacturer]" <?php echo (!empty($quick_product_edit_tabs['links']['manufacturer'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_category; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[links][category]" <?php echo (!empty($quick_product_edit_tabs['links']['category'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_filter; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[links][filter]" <?php echo (!empty($quick_product_edit_tabs['links']['filter'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[links][store]" <?php echo (!empty($quick_product_edit_tabs['links']['store'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_download; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[links][download]" <?php echo (!empty($quick_product_edit_tabs['links']['download'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_related; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[links][product_related]" <?php echo (!empty($quick_product_edit_tabs['links']['product_related'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-attribute">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tab_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[attribute][status]" <?php echo (!empty($quick_product_edit_tabs['attribute']['status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-option">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tab_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[option][status]" <?php echo (!empty($quick_product_edit_tabs['option']['status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-discount">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tab_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[discount][status]" <?php echo (!empty($quick_product_edit_tabs['discount']['status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-special">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tab_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[special][status]" <?php echo (!empty($quick_product_edit_tabs['special']['status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-image">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tab_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[image][status]" <?php echo (!empty($quick_product_edit_tabs['image']['status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-reward">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tab_status; ?></label>
								<div class="col-sm-10">
										<input type="checkbox" value="1" name="quick_product_edit_tabs[reward][status]" <?php echo (!empty($quick_product_edit_tabs['reward']['status'])) ? 'checked="checked"' : ''; ?> />
								</div>
							</div>
						</div>
					</div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript">
$("[type='checkbox']").bootstrapSwitch();
</script>
</div>
<?php echo $footer; ?>