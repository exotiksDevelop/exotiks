<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cod" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cod" class="form-horizontal">
        	<ul class="nav nav-tabs">
	            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
	            <li><a href="#tab-additional" data-toggle="tab"><?php echo $tab_additional; ?></a></li>
	        </ul>
            <div class="tab-content">
	          	<div class="tab-pane active" id="tab-general">
	          		<div class="form-group">
	          			<label class="col-sm-2 control-label" for=""><?php echo $entry_title; ?></label>
	          			<div class="col-sm-10">	
	          				<?php foreach ($languages as $language) { ?>
	          				<div class="input-group"> 
	          				<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
							<input class="form-control" type="text" name="cod_cdek_title[<?php echo $language['language_id']; ?>]" value="<?php echo isset($cod_cdek_title[$language['language_id']]) ? $cod_cdek_title[$language['language_id']] : ''; ?>" />
							</div>
							<?php } ?>	    
	          			</div>
	          		</div>

	          		<div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $entry_cache_on_delivery_help; ?>"><?php echo $entry_cache_on_delivery; ?></span></label>
		                <div class="col-sm-10">
		                	<select class="form-control" id="cdek-cache-on-delivery" name="cod_cdek_cache_on_delivery">
								<?php foreach($boolean_variables as $key => $variable) { ?>
								<option <?php if ($cod_cdek_cache_on_delivery == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
								<?php } ?>
							</select>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_view_mode; ?></label>
		                <div class="col-sm-10">
		                	<select class="form-control" id="cod-cdek-view-mode" name="cod_cdek_mode">
								<?php foreach ($view_mode as $key => $value) { ?>
								<option value="<?php echo $key; ?>" <?php if ($cod_cdek_mode == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
		                </div>
		            </div>

		            <?php 
		            	$style['cod-cdek-view-mode_parameters'] = '';
		            	if($cod_cdek_mode == 'all')
		            		$style['cod-cdek-view-mode_parameters'] = 'style="display:none"';
		            ?>

		            <div class="form-group" id="cod-cdek-view-mode_parameters" <?php echo $style['cod-cdek-view-mode_parameters'];?>>
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_view_mode_cdek; ?></label>
		                <div class="col-sm-10">
		                	<select class="form-control" id="cod-cdek-view-mode-cdek" name="cod_cdek_mode_cdek">
								<?php foreach ($view_mode_cdek as $key => $value) { ?>
								<option value="<?php echo $key; ?>" <?php if ($cod_cdek_mode_cdek == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_order_status; ?></label>
		                <div class="col-sm-10">
		                	<select class="form-control" id="order-status-id" name="cod_cdek_order_status_id">
								<?php foreach ($order_statuses as $order_status) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" <?php if ($order_status['order_status_id'] == $cod_cdek_order_status_id) echo 'selected="selected"'; ?>><?php echo $order_status['name']; ?></option>
								<?php } ?>
							</select>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_status; ?></label>
		                <div class="col-sm-10">
		                	<select class="form-control" id="status" name="cod_cdek_status">
								<?php foreach ($status_variables as $key => $text) { ?>
								<option value="<?php echo $key; ?>" <?php if ($cod_cdek_status == $key) echo 'selected="selected"'; ?>><?php echo $text; ?></option>
								<?php } ?>
							</select>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_sort_order; ?></label>
		                <div class="col-sm-10">
		                	<input class="form-control" id="sort-order" type="text" name="cod_cdek_sort_order" value="<?php echo $cod_cdek_sort_order; ?>" size="1" />
							<?php if (isset($error['cod_cdek_sort_order'])) { ?>
							<span class="error"><?php echo $error['cod_cdek_sort_order']; ?></span>
							<?php } ?>
		                </div>
		            </div>

	          	</div>
	          	<div class="tab-pane" id="tab-additional">

	          		<div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_active; ?></label>
		                <div class="col-sm-10">
		                	<select class="form-control" id="active" name="cod_cdek_active">
								<?php foreach ($boolean_variables as $key => $text) { ?>
								<option value="<?php echo $key; ?>" <?php if ($cod_cdek_active == $key) echo 'selected="selected"'; ?>><?php echo $text; ?></option>
								<?php } ?>
							</select>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $entry_min_total_help; ?>"><?php echo $entry_min_total; ?></span></label>
		                <div class="col-sm-10">
		                	<input class="form-control" id="cod-cdek-min-total" type="text" name="cod_cdek_min_total" value="<?php echo $cod_cdek_min_total; ?>" />
							<?php if (isset($error['cod_cdek_min_total'])) { ?>
							<span class="error"><?php echo $error['cod_cdek_min_total']; ?></span>
							<?php } ?>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $entry_max_total_help; ?>"><?php echo $entry_max_total; ?></span></label>
		                <div class="col-sm-10">
		                	<input class="form-control" id="cod-cdek-max-total" type="text" name="cod_cdek_max_total" value="<?php echo $cod_cdek_max_total; ?>" />
							<?php if (isset($error['cod_cdek_max_total'])) { ?>
							<span class="error"><?php echo $error['cod_cdek_max_total']; ?></span>
							<?php } ?>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_price; ?></label>
		                <div class="col-sm-10 form-inline">
		                	<select class="form-control" name="cod_cdek_price[prefix]">
								<?php foreach (array('-', '+') as $prefix) { ?>
								<option <?php if (!empty($cod_cdek_price) && $cod_cdek_price['prefix'] == $prefix) echo 'selected="selected"'; ?> value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
								<?php } ?>
							</select>
							<input class="form-control" id="cod-cdek-price-value" type="text" name="cod_cdek_price[value]" value="<?php if (!empty($cod_cdek_price)) echo $cod_cdek_price['value']; ?>" size="3" />
							<select class="form-control" name="cod_cdek_price[mode]">
								<?php foreach ($discount_type as $type => $name) { ?>
								<option <?php if (!empty($cod_cdek_price) && $cod_cdek_price['mode'] == $type) echo 'selected="selected"'; ?> value="<?php echo $type; ?>"><?php echo $name; ?></option>
								<?php } ?>
							</select>
							<?php if (isset($error['cod_cdek_price']['value'])) { ?>
							<span class="error"><?php echo $error['cod_cdek_price']['value']; ?></span>
							<?php } ?>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_geo_zone; ?></label>
		                <div class="col-sm-10">
		                	<div class="well well-sm" style="height: 150px; overflow: auto;">
							<?php foreach ($geo_zones as $geo_zone) { ?>
							<div class="checkbox">
			                    <label>
									<input type="checkbox" name="cod_cdek_geo_zone_id[]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php  if (!empty($cod_cdek_geo_zone_id) && in_array($geo_zone['geo_zone_id'], $cod_cdek_geo_zone_id)) echo 'checked="checked"'; ?> />
									<?php echo $geo_zone['name']; ?>
								</label>
								</div>
							<?php } ?>
							</div>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_store; ?></label>
		                <div class="col-sm-10">
		                	<div class="well well-sm" style="height: 150px; overflow: auto;">
			                	<?php foreach ($stores as $store) { ?>
			                    <div class="checkbox">
			                      <label>
			                        <input type="checkbox" name="cod_cdek_store[]" value="<?php echo $store['store_id']; ?>" <?php  if (isset($cod_cdek_store) && in_array($store['store_id'], $cod_cdek_store)) echo 'checked="checked"'; ?> />
									<?php echo $store['name']; ?>
			                      </label>
			                    </div>
			                    <?php } ?>
		                    </div>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><?php echo $entry_customer_group; ?></label>
		                <div class="col-sm-10">
		                	<div class="well well-sm" style="height: 150px; overflow: auto;">
							<?php foreach ($customer_groups as $customer_group) { ?>
								<label>
									<input type="checkbox" name="cod_cdek_customer_group_id[]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php  if (!empty($cod_cdek_customer_group_id) && in_array($customer_group['customer_group_id'], $cod_cdek_customer_group_id)) echo 'checked="checked"'; ?> />
									<?php echo $customer_group['name']; ?>
								</label>
							<?php } ?>
							</div>
		                </div>
		            </div>

		            <div class="form-group">
		            	<label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $entry_city_ignore_help; ?>"><?php echo $entry_city_ignore; ?></span></label>
		                <div class="col-sm-10">
		                	<textarea class="form-control" id="cod-cdek-city-ignore" name="cod_cdek_city_ignore" rows="4" cols="50"><?php echo $cod_cdek_city_ignore; ?></textarea>
		                </div>
		            </div>
	          	</div>
	        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	
	$('#cod-cdek-view-mode').change(function(event) {
		var val = $(this).val();
		if (val == 'cdek') {
			$('#cod-cdek-view-mode_parameters').show();
		} else {
			$('#cod-cdek-view-mode_parameters').hide();
		}
	});

</script>

<?php echo $footer; ?> 