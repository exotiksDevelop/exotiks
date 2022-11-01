<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
					<i class="fa fa-save"></i>
				</button>
				<a onclick="$('#form input[name=apply]').val(1); $('#form').submit();" class="btn btn-primary"><?php echo $button_apply; ?></a>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default">
					<i class="fa fa-reply"></i>
				</a>
			</div>
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
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
					<input type="hidden" name="apply" value="">
					<ul class="nav nav-tabs">
			            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
			            <li><a href="#tab-auth" data-toggle="tab"><?php echo $tab_auth; ?></a></li>
			            <li><a href="#tab-tariff" data-toggle="tab"><?php echo $tab_tariff; ?></a></li>
			            <li><a href="#tab-discount" data-toggle="tab"><?php echo $tab_discount; ?></a></li>
			            <li><a href="#tab-package" data-toggle="tab"><?php echo $tab_package; ?></a></li>
			            <li><a href="#tab-additional" data-toggle="tab"><?php echo $tab_additional; ?></a></li>
			            <li><a href="#tab-empty" data-toggle="tab"><?php echo $tab_empty; ?></a></li>
		          	</ul>
		          	<div class="tab-content">
		          		<div class="tab-pane active" id="tab-general">
		          			<div class="form-group">
				                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_title; ?></label>
				                <div class="col-sm-10">
				                	<?php foreach ($languages as $language) { ?>
				                	<div class="input-group">
					                	<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
					                	<input class="form-control" type="text" name="cdek_title[<?php echo $language['language_id']; ?>]" value="<?php echo isset($cdek_title[$language['language_id']]) ? $cdek_title[$language['language_id']] : ''; ?>">
              						</div>
									<?php } ?>
				                </div>
				             </div>

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-tax-class-id"><?php echo $entry_tax_class; ?></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-tax-class-id" name="cdek_tax_class_id">
										<option value="0"><?php echo $text_none; ?></option>
										<?php foreach ($tax_classes as $tax_class) { ?>
										<?php if ($tax_class['tax_class_id'] == $cdek_tax_class_id) { ?>
										<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-cdek-log"><span data-toggle="tooltip" title="<?php echo $entry_log_help; ?>"><?php echo $entry_log; ?></span></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-cdek-log" name="cdek_log">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_log == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_store; ?></label>
				                <div class="col-sm-10">
				                	<div class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($stores as $store) { ?>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="cdek_store[]" value="<?php echo $store['store_id']; ?>" <?php  if (isset($cdek_store) && in_array($store['store_id'], $cdek_store)) echo 'checked="checked"'; ?>>
												<?php echo $store['name']; ?>
											</label>
										</div>
										<?php } ?>
									</div>
				                </div>
				            </div>

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-status"><?php echo $entry_status; ?></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-status" name="cdek_status">
										<?php foreach (array($text_disabled, $text_enabled) as $key => $value) { ?>
										<option value="<?php echo $key; ?>" <?php if ($cdek_status == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-sort-order"><?php echo $entry_sort_order; ?></label>
				                <div class="col-sm-10">
				                	<input class="form-control" id="cdek-sort-order" type="text" name="cdek_sort_order" value="<?php echo $cdek_sort_order; ?>" size="1" />
									<?php if (isset($error['cdek_sort_order'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_sort_order']; ?></div>
									<?php } ?>
				                </div>
				            </div>
		          		</div>

		          		<div class="tab-pane" id="tab-data">

		          			<div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-default-size"><?php echo $entry_default_size; ?></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-default-size" name="cdek_default_size[use]">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if (!empty($cdek_default_size['use']) && $cdek_default_size['use'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <?php
				            	$style['cdek-default-size_parameters'] = '';
				            	if($cdek_default_size['use'] == '0' || !$cdek_default_size['use'])
				            		$style['cdek-default-size_parameters'] = 'style="display:none"';
				            ?>
				            <div id="cdek-default-size_parameters" class="col-sm-offset-2 col-sm-10" <?php echo $style['cdek-default-size_parameters'];?>>
				            	<div class="form-group">
					            	<label class="col-sm-2 control-label" for=""><?php echo $entry_default_size_type; ?></label>
					                <div class="col-sm-10">
					                	<select class="form-control" id="cdek-default-size-type" name="cdek_default_size[type]">
											<?php foreach($size_types as $key => $size_type) { ?>
											<option <?php if (!empty($cdek_default_size['type']) && $cdek_default_size['type'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $size_type; ?></option>
											<?php } ?>
										</select>
					                </div>
				            	</div>

				            	<?php
					            	$style['cdek-default-size-type-volume'] = '';
					            	$style['cdek-default-size-type_size'] = '';
					            	if($cdek_default_size['type'] == 'volume') {
					            		$style['cdek-default-size-type_size'] = 'style="display:none"';
					            	} else if ($cdek_default_size['type'] == 'size') {
					            		$style['cdek-default-size-type-volume'] = 'style="display:none"';
					            	}
				            	?>

				            	<div class="form-group required" id="cdek-default-size-type_volume" <?php echo $style['cdek-default-size-type-volume'];?>>
					            	<label class="col-sm-2 control-label" for=""><?php echo $entry_volume; ?></label>
					                <div class="col-sm-10 form-inline">
					                	<input class="form-control" id="cdek-default-size-type-volume" type="text" name="cdek_default_size[volume]" value="<?php if (!empty($cdek_default_size['volume'])) echo $cdek_default_size['volume']; ?>" size="1" /> м³
										<?php if (isset($error['cdek_default_size']['volume'])) { ?>
										<div class="text-danger"><?php echo $error['cdek_default_size']['volume']; ?></div>
										<?php } ?>
					                </div>
				            	</div>
				            	<div class="form-group required" id="cdek-default-size-type_size" <?php echo $style['cdek-default-size-type_size'];?>>
					            	<label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $entry_size_help; ?>"><?php echo $entry_size; ?></span></label>
					                <div class="col-sm-10 form-inline">
					                	<input class="form-control" id="cdek-default-size-type-size-a" type="text" placeholder="<?php echo $text_short_length; ?>" name="cdek_default_size[size_a]" value="<?php if (!empty($cdek_default_size['size_a'])) echo $cdek_default_size['size_a']; ?>" size="2" /> x
										<input class="form-control" type="text" placeholder="<?php echo $text_short_width; ?>" name="cdek_default_size[size_b]" value="<?php if (!empty($cdek_default_size['size_b'])) echo $cdek_default_size['size_b']; ?>" size="2" /> x
										<input class="form-control" type="text" placeholder="<?php echo $text_short_height; ?>" name="cdek_default_size[size_c]" value="<?php if (!empty($cdek_default_size['size_c'])) echo $cdek_default_size['size_c']; ?>" size="2" />
										<?php if (isset($error['cdek_default_size']['size'])) { ?>
										<div class="text-danger"><?php echo $error['cdek_default_size']['size']; ?></div>
										<?php } ?>
					                </div>
				            	</div>
				            	<div class="form-group">
					            	<label class="col-sm-2 control-label" for=""><?php echo $entry_default_size_work_mode; ?></label>
					                <div class="col-sm-10">
					                	<select class="form-control" id="cdek-default-size-work-mode" name="cdek_default_size[work_mode]">
											<?php foreach($default_work_mode as $key => $mode) { ?>
											<option <?php if (!empty($cdek_default_size['work_mode']) && $cdek_default_size['work_mode'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $mode; ?></option>
											<?php } ?>
										</select>
					                </div>
				            	</div>
				            </div>

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-default-weight"><?php echo $entry_default_weight_use; ?></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-default-weight" name="cdek_default_weight[use]">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if (!empty($cdek_default_weight['use']) && $cdek_default_weight['use'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <?php
				            	$style['cdek-default-weight_parameters'] = '';
				            	if($cdek_default_weight['use'] == '0' || !$cdek_default_weight['use'])
				            		$style['cdek-default-weight_parameters'] = 'style="display:none"';
				            ?>

				            <div id="cdek-default-weight_parameters" class="col-sm-offset-2 col-sm-10" <?php echo $style['cdek-default-weight_parameters'];?>>
				            	<div class="form-group required">
					            	<label class="col-sm-2 control-label" for=""><?php echo $entry_default_weight; ?></label>
					                <div class="col-sm-10 form-inline">
					                	<input class="form-control" id="cdek-default-weight-value" type="text" name="cdek_default_weight[value]" value="<?php if (!empty($cdek_default_weight['value'])) echo $cdek_default_weight['value']; ?>" size="1" /> кг.
										<?php if (isset($error['cdek_default_weight']['value'])) { ?>
										<div class="text-danger"><?php echo $error['cdek_default_weight']['value']; ?></div>
										<?php } ?>
					                </div>
				            	</div>
				            	<div class="form-group">
					            	<label class="col-sm-2 control-label" for=""><?php echo $entry_default_weight_work_mode; ?></label>
					                <div class="col-sm-10">
					                	<select class="form-control" id="cdek-default-weight-work-mode" name="cdek_default_weight[work_mode]">
											<?php foreach($default_work_mode as $key => $mode) { ?>
											<option <?php if (!empty($cdek_default_weight['work_mode']) && $cdek_default_weight['work_mode'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $mode; ?></option>
											<?php } ?>
										</select>
					                </div>
				            	</div>
				            </div>

		          			<div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-append-day"><?php echo $entry_date_execute; ?></label>
				                <div class="col-sm-10 form-inline">
				                	<?php echo $text_date_current; ?> + <input class="form-control" id="cdek-append-day" type="text" name="cdek_append_day" value="<?php echo $cdek_append_day; ?>" size="1" /> <?php echo $text_day; ?>
									<?php if (isset($error['cdek_append_day'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_append_day']; ?></div>
									<?php } ?>
				                </div>
				            </div>

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-cache-on-delivery"><?php echo $entry_cache_on_delivery; ?></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-cache-on-delivery" name="cdek_cache_on_delivery">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_cache_on_delivery == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-weight-limit"><?php echo $entry_weight_limit; ?></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-weight-limit" name="cdek_weight_limit">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_weight_limit == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-use-postcode"><span data-toggle="tooltip" title="<?php echo $entry_use_region_russia_help; ?>"><?php echo $entry_use_region_russia; ?></span></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-use-region-russia" name="cdek_use_region_russia">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_use_region_russia == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <input type="hidden" name="cdek_use_region" value="0">

				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-use-postcode"><span data-toggle="tooltip" title="<?php echo $entry_use_postcode_help; ?>"><?php echo $entry_use_postcode; ?></span></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-use-postcode" name="cdek_use_postcode">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_use_postcode == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <div class="form-group required">
				            	<label class="col-sm-2 control-label" for="cdek-city-from"><?php echo $entry_city_from; ?></label>
				                <div class="col-sm-10">
				                	<input type="hidden" id="cdek-city-from-id" name="cdek_city_from_id" value="<?php echo $cdek_city_from_id; ?>"/>
									<a class="js city-from" <?php if (!$cdek_city_from_id) echo 'style="display:none"'; ?>><?php echo $cdek_city_from; ?></a>
									<input class="form-control" type="text" id="cdek-city-from" name="cdek_city_from" value="<?php echo $cdek_city_from; ?>" <?php if ($cdek_city_from_id) echo 'style="display:none"'; ?> />
									<?php if (isset($error['cdek_city_from'])) { ?>
										<div class="text-danger"><?php echo $error['cdek_city_from']; ?></div>
									<?php } ?>
				                </div>
				            </div>

				            <div class="form-group required">
				            	<label class="col-sm-2 control-label" for="cdek-length-class-id"><span data-toggle="tooltip" title="<?php echo $entry_length_class_help; ?>"><?php echo $entry_length_class; ?></span></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-length-class-id" name="cdek_length_class_id">
										<?php foreach ($length_classes as $length_class) { ?>
										<option value="<?php echo $length_class['length_class_id']; ?>" <?php if ($length_class['length_class_id'] == $cdek_length_class_id) echo 'selected="selected"'; ?>><?php echo $length_class['title']; ?></option>
										<?php } ?>
									</select>
									<?php if (isset($error['length_class_id'])) { ?>
									<div class="text-danger"><?php echo $error['length_class_id']; ?></div>
									<?php } ?>
				                </div>
				            </div>

				            <div class="form-group required">
				            	<label class="col-sm-2 control-label" for="cdek-weight-class-id"><span data-toggle="tooltip" title="<?php echo $entry_weight_class_help; ?>"><?php echo $entry_weight_class; ?></span></label>
				                <div class="col-sm-10">
				                	<select class="form-control" id="cdek-weight-class-id" name="cdek_weight_class_id">
										<?php foreach ($weight_classes as $weight_class) { ?>
										<option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if ($weight_class['weight_class_id'] == $cdek_weight_class_id) echo 'selected="selected"'; ?>><?php echo $weight_class['title']; ?></option>
										<?php } ?>
									</select>
									<?php if (isset($error['cdek_weight_class_id'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_weight_class_id']; ?></div>
									<?php } ?>
				                </div>
				            </div>
		          		</div>

		          		<div class="tab-pane" id="tab-auth">
		          			<div class="form-group col-sm-12">
		          				<span class="help"><?php echo $text_help_auth; ?></span>
		          			</div>
		          			<div class="form-group col-sm-12">
		          				<p><?php echo $text_testing_api_keys; ?></p>
		          			</div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-login"><?php echo $entry_login; ?></label>
				                <div class="col-sm-10">
				                	<input class="form-control" id="cdek-login" type="text" name="cdek_login" value="<?php echo $cdek_login; ?>" />
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-password"><?php echo $entry_password; ?></label>
				                <div class="col-sm-10">
				                	<input class="form-control" id="cdek-password" type="text" name="cdek_password" value="<?php echo $cdek_password; ?>" />
				                </div>
				            </div>
		          		</div>

		          		<div class="tab-pane" id="tab-tariff">
		          			<div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-work-mode"><span data-toggle="tooltip" title="<?php echo $entry_work_mode_help; ?>"><?php echo $entry_work_mode; ?></span></label>
				                <div class="col-sm-10">
				                	<div class="alert alert-warning" id="cdek-work-mode_parameters"><?php echo $text_more_attention; ?></div>
				                	<select id="cdek-work-mode" class="form-control work-mode" name="cdek_work_mode">
										<?php foreach ($work_mode as $mode_id => $mode_name) { ?>
										<option <?php if ($mode_id == $cdek_work_mode) echo 'selected="selected"'; ?> value="<?php echo $mode_id; ?>"><?php echo $mode_name; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>

				            <input type="hidden" name="cdek_show_pvz" value="1">
				            <input type="hidden" name="cdek_pvz_more_one" value="merge">

				            <?php
				            	$style['cdek-show-pvz_parameters'] = '';
				            	if($cdek_show_pvz == 0)
				            		$style['cdek-show-pvz_parameters'] = 'style="display:none"';
				            ?>

				            <?php if (isset($error['tariff_list'])) { ?>
				            <div class="text-danger tariff_list"><?php echo $error['tariff_list']; ?></div>
							<?php } ?>

							<table class="table table-striped list">
								<thead>
									<tr>
										<td class="left" colspan="2"><?php echo $column_tariff; ?></td>
										<td class="left"><span data-toggle="tooltip" title="<?php echo $column_title_help; ?>"><?php echo $column_title; ?></span></td>
										<td class="left"><?php echo $column_customer_group; ?></td>
										<td class="left"><span data-toggle="tooltip" title="<?php echo $column_geo_zone_help; ?>"><?php echo $column_geo_zone; ?></span></td>
										<td class="left"><?php echo $column_limit; ?></td>
										<td class="left"></td>
									</tr>
								</thead>
								<tbody>
									<?php $tariff_row = 0; ?>
									<?php foreach ($cdek_custmer_tariff_list as $tariff_row => $tariff_info) { ?>
									<tr id="tariff-<?php echo $tariff_row; ?>">
										<td class="drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>
										<td class="col-md-3">
											<nobr><?php echo $tariff_info['tariff_name']; ?></nobr><br><b><?php echo $column_mode; ?></b>: <?php echo $tariff_info['mode_name']; ?>
											<?php if (isset($error['tariff_list_item'][$tariff_row]['exists'])) { ?>
											<div class="text-danger"><?php echo $error['tariff_list_item'][$tariff_row]['exists']; ?></div>
											<?php } ?>
											<input type="hidden" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][sort_order]" value="<?php echo $tariff_info['sort_order']; ?>" class="sort_order" />
											<input type="hidden" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][tariff_id]" value="<?php echo $tariff_info['tariff_id']; ?>" />
											<input type="hidden" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][mode_id]" value="<?php echo $tariff_info['mode_id']; ?>" />
										</td>
										<td class="col-md-3 form-inline">
											<?php foreach ($languages as $language) { ?>
												<div class="input-group">
													<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
													<input type="text" class="form-control" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($tariff_info['title'][$language['language_id']]) && is_array($tariff_info['title'])) ? $tariff_info['title'][$language['language_id']] : (($language['language_id'] == 1 && is_scalar($tariff_info['title'])) ? $tariff_info['title'] : ''); ?>" />
												</div>
											<?php } ?>
										</td>
										<td class="left">
											<a href="#" onclick="$('#groupsBlock-<?php echo $tariff_row; ?>').toggle(); return false;"><?php echo $column_customer_group; ?></a>
											<div class="scrollbox" id="groupsBlock-<?php echo $tariff_row; ?>" style="display: none;">
												<?php $class = 'even'; ?>
												<?php foreach ($customer_groups as $customer_group) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
												<input type="checkbox" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php  if (!empty($tariff_info['customer_group_id']) && is_array($tariff_info['customer_group_id']) && in_array($customer_group['customer_group_id'], $tariff_info['customer_group_id'])) echo 'checked="checked"'; ?> />
												<?php echo $customer_group['name']; ?>
												</div>
												<?php } ?>
											</div>
										</td>
										<td class="left">
											<a href="#" onclick="$('#geozoneBlock-<?php echo $tariff_row; ?>').toggle(); return false;"><?php echo $column_geo_zone; ?></a>
											<div class="scrollbox" id="geozoneBlock-<?php echo $tariff_row; ?>" style="display: none;">
												<?php $class = 'even'; ?>
												<?php foreach ($geo_zones as $geo_zone) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
												<input type="checkbox" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php  if (isset($tariff_info['geo_zone']) && in_array($geo_zone['geo_zone_id'], $tariff_info['geo_zone'])) echo 'checked="checked"'; ?> />
												<?php echo $geo_zone['name']; ?>
												</div>
												<?php } ?>
											</div>
										</td>
										<td class="left">
											<a href="#" onclick="$('#limitBlock-<?php echo $tariff_row; ?>').toggle(); return false;"><?php echo $column_limit; ?></a>
											<div id="limitBlock-<?php echo $tariff_row; ?>" style="display: none;">
								            	<table class="form limit">
													<tbody>
														<tr>
															<td><label for="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-min-weight"><span data-toggle="tooltip" title="<?php echo $entry_min_weight_help; ?>"><?php echo $entry_min_weight; ?></span></label></td>
															<td>
																<input id="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-min-weight" type="text" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][min_weight]" value="<?php if (isset($tariff_info['min_weight'])) echo $tariff_info['min_weight']; ?>" size="3" />
																<?php if (isset($error['tariff_list_item'][$tariff_row]['min_weight'])) { ?>
																<div class="text-danger"><?php echo $error['tariff_list_item'][$tariff_row]['min_weight']; ?></div>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><label for="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-max-weight"><span data-toggle="tooltip" title="<?php echo $entry_max_weight_help; ?>"><?php echo $entry_max_weight; ?></span></label></td>
															<td>
																<input id="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-max-weight" type="text" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][max_weight]" value="<?php if (isset($tariff_info['max_weight'])) echo $tariff_info['max_weight']; ?>" size="3" />
																<?php if (isset($error['tariff_list_item'][$tariff_row]['max_weight'])) { ?>
																<div class="text-danger"><?php echo $error['tariff_list_item'][$tariff_row]['max_weight']; ?></div>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><label for="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-min-total"><span data-toggle="tooltip" title="<?php echo $entry_min_total_help; ?>"><?php echo $entry_min_total; ?></span></label></td>
															<td>
																<input id="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-min-total" type="text" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][min_total]" value="<?php if (isset($tariff_info['min_total'])) echo $tariff_info['min_total']; ?>" size="3" />
																<?php if (isset($error['tariff_list_item'][$tariff_row]['min_total'])) { ?>
																<div class="text-danger"><?php echo $error['tariff_list_item'][$tariff_row]['min_total']; ?></div>
																<?php } ?>
															</td>
														</tr>
														<tr class="last">
															<td><label for="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-max-total"><span data-toggle="tooltip" title="<?php echo $entry_max_total_help; ?>"><?php echo $entry_max_total; ?></span></label></td>
															<td>
																<input id="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-max-total" type="text" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][max_total]" value="<?php if (isset($tariff_info['max_total'])) echo $tariff_info['max_total']; ?>" size="3" />
																<?php if (isset($error['tariff_list_item'][$tariff_row]['max_total'])) { ?>
																<div class="text-danger"><?php echo $error['tariff_list_item'][$tariff_row]['max_total']; ?></div>
																<?php } ?>
															</td>
														</tr>
													</tbody>
												</table>
								            </div>
										</td>
										<td class="left"><a onclick="removeTariff(<?php echo $tariff_row; ?>);" class="button"><?php echo $button_remove; ?></a></td>
									</tr>
									<?php } ?>
									<?php $tariff_row++; ?>
								</tbody>
							</table>

							<div class="form-group">
				            	<label class="col-sm-2 control-label" for="cdek-pvz-more-one"><?php echo $text_tariff; ?></label>
				                <div class="col-sm-10">
				                	<select class="form-control cdek-tariff">
										<option value="0"><?php echo $text_select; ?></option>
										<?php foreach ($tariff_list as $tariff_id => $tariff_info) { ?>
										<option rel="<?php echo $tariff_info['mode_id']; ?>" value="<?php echo $tariff_id; ?>"><?php echo $tariff_info['title'] . (isset($tariff_info['im']) ? ' ***' : ''); ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>
				            <a onclick="simtariffs();">Добавить все тарифы</a>
							<p class="help"><?php echo $text_help_im; ?></p>
		          		</div>

		          		<div class="tab-pane" id="tab-discount">
							<p class="help"><?php echo $text_discount_help; ?></p>
							<table class="table" id="discount">
								<thead>
									<tr>
										<td class="left"><span data-toggle="tooltip" title="<?php echo $column_total_help; ?>"><?php echo $column_total; ?></span></td>
										<td class="left"><?php echo $column_tariff; ?></td>
										<td class="left"><?php echo $column_tax_class; ?></td>
										<td class="left"><?php echo $column_customer_group; ?></td>
										<td class="left"><?php echo $column_geo_zone; ?></td>
										<td class="left"><?php echo $column_discount_value; ?></td>
										<td></td>
									</tr>
								</thead>
								<tbody>
									<?php $discount_row = 0; ?>
									<?php if ($cdek_discounts) { ?>
									<?php foreach ($cdek_discounts as $discount_row => $discount) { ?>
									<tr id="discount-row<?php echo $discount_row; ?>">
										<td class="left">
											<input type="text" class="form-control" name="cdek_discounts[<?php echo $discount_row; ?>][total]" value="<?php echo $discount['total']; ?>" size="3" />
											<?php if (isset($error['cdek_discounts'][$discount_row]['total'])) { ?>
											<div class="text-danger"><?php echo $error['cdek_discounts'][$discount_row]['total']; ?></div>
											<?php } ?>
										</td>
										<td class="left">
											<div class="well well-sm" style="height: 150px; overflow: auto;">
												<?php $class = 'even'; ?>
												<?php foreach ($tariff_list as $tariff_id => $tariff_info) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
												<input type="checkbox" name="cdek_discounts[<?php echo $discount_row; ?>][tariff_id][]" value="<?php echo $tariff_id; ?>" <?php  if (!empty($discount['tariff_id']) && is_array($discount['tariff_id']) && in_array($tariff_id, $discount['tariff_id'])) echo 'checked="checked"'; ?> />
												<?php echo $tariff_info['title']; ?>
												</div>
												<?php } ?>
											</div>
										</td>
										<td class="left">
											<select class="form-control" name="cdek_discounts[<?php echo $discount_row; ?>][tax_class_id]">
												<option value="0"><?php echo $text_none; ?></option>
												<?php foreach ($tax_classes as $tax_class) { ?>
												<option <?php if ($tax_class['tax_class_id'] == $discount['tax_class_id']) echo 'selected="selected"'; ?> value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
												<?php } ?>
											</select>
										</td>
										<td class="left">
											<div class="scrollbox">
												<?php $class = 'even'; ?>
												<?php foreach ($customer_groups as $customer_group) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
												<input type="checkbox" name="cdek_discounts[<?php echo $discount_row; ?>][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php  if (!empty($discount['customer_group_id']) && is_array($discount['customer_group_id']) && in_array($customer_group['customer_group_id'], $discount['customer_group_id'])) echo 'checked="checked"'; ?> />
												<?php echo $customer_group['name']; ?>
												</div>
												<?php } ?>
											</div>
										</td>
										<td class="left">
											<div class="scrollbox">
												<?php $class = 'even'; ?>
												<?php foreach ($geo_zones as $geo_zone) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
												<input type="checkbox" name="cdek_discounts[<?php echo $discount_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php  if (isset($discount['geo_zone']) && in_array($geo_zone['geo_zone_id'], $discount['geo_zone'])) echo 'checked="checked"'; ?> />
												<?php echo $geo_zone['name']; ?>
												</div>
												<?php } ?>
											</div>
										</td>
										<td class="left form-inline">
											<select class="form-control" name="cdek_discounts[<?php echo $discount_row; ?>][prefix]">
												<?php foreach (array('-', '+') as $prefix) { ?>
												<option <?php if ($prefix == $discount['prefix']) echo 'selected="selected"'; ?> value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
												<?php } ?>
											</select>
											<input type="text" class="form-control" name="cdek_discounts[<?php echo $discount_row; ?>][value]" value="<?php echo $discount['value']; ?>" size="3" />
											<select class="form-control" name="cdek_discounts[<?php echo $discount_row; ?>][mode]">
												<?php foreach ($discount_type as $type => $name) { ?>
												<option <?php if ($type == $discount['mode']) echo 'selected="selected"'; ?> value="<?php echo $type; ?>"><?php echo $name; ?></option>
												<?php } ?>
											</select>
											<?php if (isset($error['cdek_discounts'][$discount_row]['value'])) { ?>
											<div class="text-danger"><?php echo $error['cdek_discounts'][$discount_row]['value']; ?></div>
											<?php } ?>
										</td>
										<td class="left"><a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();return FALSE;" class="button"><?php echo $button_remove; ?></a></td>
									</tr>
									<?php } ?>
									<?php $discount_row++; ?>
									<?php } ?>
								</tbody>
							</table>
							<a class="button" onclick="addDiscount();"><?php echo $button_add_discount; ?></a>
		          		</div>

		          		<div class="tab-pane" id="tab-package">
		          			<div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_packing_min_weight; ?></label>
				                <div class="col-sm-10 form-inline">
				                	<input class="form-control" id="cdek-packing-min-weight" type="text" name="cdek_packing_min_weight" value="<?php echo $cdek_packing_min_weight; ?>" size="1" />
									<select class="form-control" name="cdek_packing_weight_class_id">
										<?php foreach ($weight_classes as $weight_class) { ?>
										<option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if ($weight_class['weight_class_id'] == $cdek_packing_weight_class_id) echo 'selected="selected"'; ?>><?php echo $weight_class['title']; ?></option>
										<?php } ?>
									</select>
									<?php if (isset($error['cdek_packing_min_weight'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_packing_min_weight']; ?></div>
									<?php } ?>
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $entry_packing_additional_weight_help; ?>"><?php echo $entry_packing_additional_weight; ?></span></label>
				                <div class="col-sm-10 form-inline">
				                	<select class="form-control" name="cdek_packing_prefix">
										<?php foreach (array('+', '-') as $prefix) { ?>
										<option <?php if ($prefix == $cdek_packing_prefix) echo 'selected="selected"'; ?> value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
										<?php } ?>
									</select>
									<input class="form-control" id="cdek-packing-value" type="text" name="cdek_packing_value" value="<?php echo $cdek_packing_value; ?>" size="1" />
									<select class="form-control" name="cdek_packing_mode">
										<?php foreach($additional_weight_mode as $key => $value) { ?>
										<option <?php if ($cdek_packing_mode == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
										<?php } ?>
									</select>
									<?php if (isset($error['cdek_packing_value'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_packing_value']; ?></div>
									<?php } ?>
				                </div>
				            </div>
		          		</div>

		          		<div class="tab-pane" id="tab-additional">
		          			<div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_more_days; ?></label>
				                <div class="col-sm-10 form-inline">
				                	 + <input class="form-control" id="cdek-more-days" type="text" name="cdek_more_days" value="<?php echo $cdek_more_days; ?>" size="1" /> <?php echo $text_day; ?>
									<?php if (isset($error['cdek_more_days'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_more_days']; ?></div>
									<?php } ?>
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_period; ?></label>
				                <div class="col-sm-10 form-inline">
				                	<select class="form-control" id="cdek-period" name="cdek_period">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_period == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_delivery_data; ?></label>
				                <div class="col-sm-10 form-inline">
				                	<select class="form-control" id="cdek-delivery-data" name="cdek_delivery_data">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_delivery_data == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $entry_empty_address_help; ?>"><?php echo $entry_empty_address; ?></span></label>
				                <div class="col-sm-10 form-inline">
				                	<select class="form-control" id="cdek-empty-address" name="cdek_empty_address">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_empty_address == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_min_weight; ?></label>
				                <div class="col-sm-10 form-inline">
				                	<input class="form-control" id="cdek-min-weight" type="text" name="cdek_min_weight" value="<?php echo $cdek_min_weight; ?>" />
									<?php if (isset($error['cdek_min_weight'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_min_weight']; ?></div>
									<?php } ?>
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_max_weight; ?></label>
				                <div class="col-sm-10 form-inline">
				                	<input class="form-control" id="cdek-max-weight" type="text" name="cdek_max_weight" value="<?php echo $cdek_max_weight; ?>" />
									<?php if (isset($error['cdek_max_weight'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_max_weight']; ?></div>
									<?php } ?>
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_min_total; ?></label>
				                <div class="col-sm-10 form-inline">
				                	<input class="form-control" id="cdek-min-total" type="text" name="cdek_min_total" value="<?php echo $cdek_min_total; ?>" />
									<?php if (isset($error['cdek_min_total'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_min_total']; ?></div>
									<?php } ?>
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_max_total; ?></label>
				                <div class="col-sm-10 form-inline">
				                	<input class="form-control" id="cdek-max-total" type="text" name="cdek_max_total" value="<?php echo $cdek_max_total; ?>" />
									<?php if (isset($error['cdek_max_total'])) { ?>
									<div class="text-danger"><?php echo $error['cdek_max_total']; ?></div>
									<?php } ?>
				                </div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $entry_city_ignore_help; ?>"><?php echo $entry_city_ignore; ?></span></label>
				                <div class="col-sm-10 form-inline">
				                	<textarea class="form-control" id="cdek-city-ignore" name="cdek_city_ignore" rows="4" cols="50"><?php echo $cdek_city_ignore; ?></textarea>
				                </div>
				            </div>
		          		</div>

		          		<div class="tab-pane" id="tab-empty">
		          			<div class="form-group">
				            	<label class="col-sm-2 control-label" for=""><?php echo $entry_empty; ?></label>
				                <div class="col-sm-10 form-inline">
				                	<select class="form-control" id="cdek-empty" name="cdek_empty[use]">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if (!empty($cdek_empty['use']) && $cdek_empty['use'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
				                </div>
				            </div>
				            <?php
				            	$style['cdek-empty_parameters'] = '';
				            	if($cdek_empty['use'] == 0)
				            		$style['cdek-empty_parameters'] = 'style="display:none"';
				            ?>
				            <div id="cdek-empty_parameters" <?php echo $style['cdek-empty_parameters'];?>>
					            <div class="form-group">
					            	<label class="col-sm-2 control-label" for=""><?php echo $entry_title; ?></label>
					                <div class="col-sm-10">
					                	<?php foreach ($languages as $language) { ?>
					                	<div class="input-group">
											<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
											<input class="form-control" type="text" name="cdek_empty[title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($cdek_empty['title'][$language['language_id']]) ? $cdek_empty['title'][$language['language_id']] : ''; ?>" />
										</div>
										<?php } ?>
					                </div>
					            </div>

					            <div class="form-group">
					            	<label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $entry_empty_cost_help; ?>"><?php echo $entry_empty_cost; ?></span></label>
					                <div class="col-sm-10 form-inline">
					                	<td>
											<input class="form-control" id="cdek-empty-cost" type="text" name="cdek_empty[cost]" value="<?php if (!empty($cdek_empty['cost'])) echo $cdek_empty['cost']; ?>" />
											<?php if (isset($error['cdek_empty']['cost'])) { ?>
											<div class="text-danger"><?php echo $error['cdek_empty']['cost']; ?></div>
											<?php } ?>
										</td>
					                </div>
					            </div>
				            </div>
		          		</div>
		          	</div>
				</form>
			</div>
		</div>

	</div>

</div>

<script type="text/javascript"><!--

$('#cdek-default-size').change(function(event) {
	var val = $(this).val();
	if (val == '1') {
		$('#cdek-default-size_parameters').show();
	} else {
		$('#cdek-default-size_parameters').hide();
	}
});

$('#cdek-default-weight').change(function(event) {
	var val = $(this).val();
	if (val == '1') {
		$('#cdek-default-weight_parameters').show();
	} else {
		$('#cdek-default-weight_parameters').hide();
	}
});

$('#cdek-show-pvz').change(function(event) {
	var val = $(this).val();
	if (val == '1') {
		$('#cdek-show-pvz_parameters').show();
	} else {
		$('#cdek-show-pvz_parameters').hide();
	}
});

$('#cdek-empty').change(function(event) {
	var val = $(this).val();
	if (val == '1') {
		$('#cdek-empty_parameters').show();
	} else {
		$('#cdek-empty_parameters').hide();
	}
});

$('#cdek-default-size-type').change(function(event){

	var type = $(this).val();

	if (type == 'volume') {
		$('#cdek-default-size-type_volume').show();
		$('#cdek-default-size-type_size').hide();
	} else {
		$('#cdek-default-size-type_size').show();
		$('#cdek-default-size-type_volume').hide();
	}

});

var mode_list = [];

<?php foreach ($tariff_mode as $mode_id => $mode_name) { ?>
	mode_list[<?php echo $mode_id; ?>] = '<?php echo $mode_name; ?>';
<?php } ?>

var tariff_list = [];

<?php foreach ($tariff_list as $tariff_id => $tariff_info) { ?>
	tariff_list[<?php echo $tariff_id; ?>] = '<?php echo $tariff_info['title'];?>';
<?php } ?>


var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {

	var html = '<tr id="discount-row' + discount_row + '">';
	html += '		<td class="left">';
	html += '			<input type="text" class="form-control" name="cdek_discounts[' + discount_row + '][total]" value="" size="3" />';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<div class="well well-sm" style="height: 150px; overflow: auto;">';
	<?php $class = 'even'; ?>
	<?php foreach ($tariff_list as $tariff_id => $tariff_info) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '				<input type="checkbox" name="cdek_discounts[' + discount_row + '][tariff_id][]" value="<?php echo $tariff_id; ?>" />';
	html += '				<?php echo $tariff_info['title']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<select class="form-control" name="cdek_discounts[' + discount_row + '][tax_class_id]">';
	html += '				<option value="0"><?php echo $text_none; ?></option>';
	<?php foreach ($tax_classes as $tax_class) { ?>
	html += '				<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>';
	<?php } ?>
	html += '			</select>';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<div class="scrollbox">';
	<?php $class = 'even'; ?>
	<?php foreach ($customer_groups as $customer_group) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '				<input type="checkbox" name="cdek_discounts[' + discount_row + '][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" />';
	html += '				<?php echo $customer_group['name']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<div class="scrollbox">';
	<?php $class = 'even'; ?>
	<?php foreach ($geo_zones as $geo_zone) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '					<input type="checkbox" name="cdek_discounts[' + discount_row + '][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />';
	html += '					<?php echo $geo_zone['name']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left"><div class="form-inline">';
	html += '			<select class="form-control" name="cdek_discounts[' + discount_row + '][prefix]">';
	<?php foreach (array('-', '+') as $prefix) { ?>
	html += '				<option value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>';
	<?php } ?>
	html += '			</select>';
	html += '			<input class="form-control" type="text" name="cdek_discounts[' + discount_row + '][value]" value="" size="3" />';
	html += '			<select class="form-control" name="cdek_discounts[' + discount_row + '][mode]">';
	<?php foreach ($discount_type as $type => $name) { ?>
	html += '				<option value="<?php echo $type; ?>"><?php echo $name; ?></option>';
	<?php } ?>
	html += '			</select>';
	html += '		</div></td>';
	html += '		<td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();return FALSE;" class="button"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';

	$('#discount tbody').append(html);

	discount_row++;

}

var tariff_row = <?php echo $tariff_row; ?>;

function simtariffs()
{
    $.each( tariff_list, function( key, value ) {
    	if (value != null) {
    		$('.cdek-tariff').val(key).trigger('change');
		}
	});
}

$('.cdek-tariff').on('change', function(event){
	event.preventDefault();

	var tariff_id = $(this).val();
	var tariff_name = $(".cdek-tariff option:selected").text();
	console.log(tariff_name);
	if (tariff_id == 0) return;

	var parent = $('#tab-tariff');

	var option = $('select.cdek-tariff option[value=' + tariff_id + ']', parent);
	var mode_id = $(option).attr('rel');

	var sort_orde = 0;

	$('table.list tr', parent).each(function(){

		var order = $('input.sort_order', this).val();

		if (order > sort_orde) {
			sort_orde = order;
		}

	});

	sort_orde++;

	var html = '<tr id="tariff-' + tariff_row + '">';
	html += '		<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>';
	html += '		<td class="left"><nobr>' + $(option).text() + '</nobr><span class="help"><b><?php echo $column_mode; ?></b>: ' + mode_list[mode_id] + '</span><input class="sort_order" type="hidden" name="cdek_custmer_tariff_list[' + tariff_row + '][sort_order]" value="' + sort_orde + '" /><input type="hidden" name="cdek_custmer_tariff_list[' + tariff_row + '][tariff_id]" value="' + tariff_id + '" /><input type="hidden" name="cdek_custmer_tariff_list[' + tariff_row + '][mode_id]" value="' + mode_id + '" /></td>';
	html += '		<td class="left col-md-3 form-inline">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group">';
	html += '<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input type="text" class="form-control" name="cdek_custmer_tariff_list[' + tariff_row + '][title][<?php echo $language['language_id']; ?>]" value="'+tariff_name+'" />';
	html += '</div>';
	<?php } ?>
	html += '		</td>';
	html += '		<td class="left">';
	html += ' 			<a href="#" onclick="$(\'#groupsBlock-'+ tariff_row +'\').toggle(); return false;"><?php echo $column_customer_group; ?></a>';
	html += '			<div class="scrollbox" id="groupsBlock-'+ tariff_row +'" style="display: none;">';
	<?php $class = 'even'; ?>
	<?php foreach ($customer_groups as $customer_group) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '				<input type="checkbox" name="cdek_custmer_tariff_list[' + tariff_row + '][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" />';
	html += '				<?php echo $customer_group['name']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left">';
	html += ' 			<a href="#" onclick="$(\'#geozoneBlock-'+ tariff_row +'\').toggle(); return false;"><?php echo $column_geo_zone; ?></a>';
	html += '			<div class="scrollbox" id="geozoneBlock-' + tariff_row + '" style="display: none;">';
	<?php $class = 'even'; ?>
	<?php foreach ($geo_zones as $geo_zone) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '					<input type="checkbox" name="cdek_custmer_tariff_list[' + tariff_row + '][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />';
	html += '					<?php echo $geo_zone['name']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left">';
	html += ' 		<a href="#" onclick="$(\'#limitBlock-'+ tariff_row +'\').toggle(); return false;"><?php echo $column_limit; ?></a>';
	html += ' 		<div id="limitBlock-'+ tariff_row +'" style="display: none;">';
	html += '			<table class="form limit">';
	html += '				<tbody>';
	html += '					<tr>';
	html += '						<td><label for="cdek-custmer-tariff-list-' + tariff_row + '-min-weight"><?php echo $entry_min_weight; ?></label></td>';
	html += '						<td><input id="cdek-custmer-tariff-list-' + tariff_row + '-min-weight" type="text" name="cdek_custmer_tariff_list[' + tariff_row + '][min_weight]" value="" size="3" /></td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td><label for="cdek-custmer-tariff-list-' + tariff_row + '-max-weight"><?php echo $entry_max_weight; ?></label></td>';
	html += '						<td><input id="cdek-custmer-tariff-list-' + tariff_row + '-max-weight" type="text" name="cdek_custmer_tariff_list[' + tariff_row + '][max_weight]" value="" size="3" /></td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td><label for="cdek-custmer-tariff-list-' + tariff_row + '-min-total"><?php echo $entry_min_total; ?></label></td>';
	html += '						<td><input id="cdek-custmer-tariff-list-' + tariff_row + '-min-total" type="text" name="cdek_custmer_tariff_list[' + tariff_row + '][min_total]" value="" size="3" /></td>';
	html += '					</tr>';
	html += '					<tr class="last">';
	html += '						<td><label for="cdek-custmer-tariff-list-' + tariff_row + '-max-total"><?php echo $entry_max_total; ?></label></td>';
	html += '						<td><input id="cdek-custmer-tariff-list-' + tariff_row + '-max-total" type="text" name="cdek_custmer_tariff_list[' + tariff_row + '][max_total]" value="" size="3" /></td>';
	html += '					</tr>';
	html += '				</tbody>';
	html += '			</table>';
	html += '		</div>';
	html += '		</td>';
	html += '		<td class="left"><a onclick="removeTariff(' + tariff_row + ');" class="button"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';

	$('table.list tbody:first', parent).append(html);

	$('select.cdek-tariff option', parent).removeAttr('selected');

	tariff_row++;
});

function removeTariff(tariff_row) {
	$('#tariff-' + tariff_row).remove();
	$('select.cdek-tariff option[value=' + tariff_row + ']').show();
}

$('.js.city-from').click(function(){
	 $("#cdek-city-from").show().focus().trigger('keydown');
	 $(this).hide();
});

$("#cdek-city-from").blur(function(){
	if ($('#cdek-city-from-id').val() != '') {
		$('.js.city-from').show();
		$(this).hide();
	}
});

$("#cdek-city-from").change(function(){
	$('#cdek-city-from-id').val('');
});

$(function() {
  $("#cdek-city-from").autocomplete({
	source: function(request,response) {
	  $.ajax({
		url: "//api.cdek.ru/city/getListByTerm/jsonp.php?callback=?",
		dataType: "jsonp",
		data: {
			q: function () { return $("#cdek-city-from").val() },
			name_startsWith: function () { return $("#cdek-city-from").val() }
		},
		success: function(data) {
			console.log(data);
		  response($.map(data.geonames, function(item) {
			return {
			  label: item.name,
			  value: item.name,
			  id: item.id
			}
		  }));
		}
	  });
	},
	minLength: 1,
	select: function(event) {
		$('#cdek-city-from-id').parent().find('.error').remove();
		$('#cdek-city-from-id').val(event.id);
		$("#cdek-city-from").val(event.label);
		$('.js.city-from').text(event.label).show();
		$("#cdek-city-from").hide();
	}
  });

});

//--></script>

<?php echo $footer; ?>