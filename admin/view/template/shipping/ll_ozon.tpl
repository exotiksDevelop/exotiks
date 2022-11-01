<?php 
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a onclick="$('#form').attr('action', '<?php echo $action; ?>'); $('#form').submit()" class="btn btn-success" id="button_save"><i class="fa fa-save"></i> <?php echo $button_save; ?></a>
        <a data-toggle="tooltip" title="<?php echo $button_shipping; ?>" class="btn btn-default active" disabled><i class="fa fa-truck"></i></a>
        <a href="<?php echo $exchange; ?>" data-toggle="tooltip" title="<?php echo $button_exchange; ?>" class="btn btn-default"><i class="fa fa-exchange"></i></a>
        <a href="<?php echo $order; ?>" data-toggle="tooltip" title="<?php echo $button_order; ?>" class="btn btn-default"><i class="fa fa-shopping-cart"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (${$m . '_license'}) { ?>
    <div class="panel panel-primary">
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <input type="hidden" name="<?php echo $m; ?>_update" value="<?php echo ${$m . '_update'}; ?>" />
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-api" data-toggle="tab"><i class="fa fa-terminal"></i> <?php echo $tab_api; ?></a></li>
            <li><a href="#tab-log" data-toggle="tab"><i class="fa fa-bars"></i> <?php echo $tab_log; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><i class="fa fa-database"></i> <?php echo $tab_data; ?></a></li>
            <li><a href="#tab-general" data-toggle="tab"><i class="fa fa-cog"></i> <?php echo $tab_general; ?></a></li>
            <li><a href="#tab-delivery" data-toggle="tab"><i class="fa fa-truck"></i> <?php echo $tab_delivery; ?></a></li>
            <li><a href="#tab-stop" data-toggle="tab"><i class="fa fa-lock"></i> <?php echo $tab_stop; ?></a></li>
            <li><a href="#tab-cost" data-toggle="tab"><i class="fa fa-rub"></i> <?php echo $tab_cost; ?></a></li>
            <li><a href="#tab-map" data-toggle="tab"><i class="fa fa-map-marker"></i> <?php echo $tab_map; ?></a></li>
            <li><a href="#tab-cap" data-toggle="tab"><i class="fa fa-arrows-alt"></i> <?php echo $tab_cap; ?></a></li>
            <li><a href="#tab-support" data-toggle="tab"><i class="fa fa-life-ring"></i> <?php echo $tab_support; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-api">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_client_id; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="<?php echo $m; ?>_client_id" value="<?php echo ${$m . '_client_id'}; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_client_secret; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="<?php echo $m; ?>_client_secret" value="<?php echo ${$m . '_client_secret'}; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_test; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_test'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_test" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_test" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_test" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_test" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_round; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_round'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_round" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_round" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_round" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_round" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_cash; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_cash'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_cash" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_cash" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_cash" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_cash" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_cache; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_cache'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_cache" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_cache" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_cache" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_cache" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_timeout; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="<?php echo $m; ?>_timeout" value="<?php echo ${$m . '_timeout'}; ?>" class="form-control" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-log">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_logging; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_logging'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_logging" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_logging" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_logging" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_logging" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <div class="col-sm-12">
                  <button type="button" onclick="updateData();" class="btn btn-warning btn-block" id="ll_update_data"><i class="fa fa-repeat"></i> <?php echo $button_update; ?></button>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_data; ?></label>
                <div class="col-sm-10">
                  <ul class="list-group">
                    <li class="list-group-item"><?php echo $text_total_countries; ?> <span class="label label-default"><?php echo $total_countries; ?></span></li>
                    <li class="list-group-item"><?php echo $text_total_regions; ?> <span class="label label-default"><?php echo $total_regions; ?></span></li>
                    <li class="list-group-item"><?php echo $text_total_cities; ?> <span class="label label-default"><?php echo $total_cities; ?></span></li>
                    <li class="list-group-item"><?php echo $text_total_pvzs; ?> <span class="label label-default"><?php echo $total_pvzs; ?></span></li>
                    <li class="list-group-item"><?php echo $text_total_places; ?> <span class="label label-default"><?php echo $total_places; ?></span></li>
                    <li class="list-group-item"><?php echo $text_total_pickups; ?> <span class="label label-default"><?php echo $total_pickups; ?></span></li>
                  </ul>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_pickup_cities; ?></label>
                <div class="col-sm-10">
                  <table id="cities" class="table table-striped table-bordered table-hover">
                    <tbody>
                      <?php $city_row = 0; ?>
                      <?php foreach (${$m . '_pickup_cities'} as ${$m . '_pickup_city'}) { ?>
                      <tr id="city-row-<?php echo $city_row; ?>">
                        <td class="text-left">
                          <select name="<?php echo $m; ?>_pickup_cities[]" class="<?php echo $m; ?>_pickup_cities form-control" id="<?php echo $m; ?>_pickup_cities_<?php echo $city_row; ?>">
                            <option><?php echo $text_select_place; ?></option>
                            <?php if (!empty($places)) { ?>
                            <?php foreach ($places as $city) { ?>
                              <option value="<?php echo $city['id']; ?>" <?php if (${$m . '_pickup_cities'}[$city_row] == $city['id']) { ?>selected="selected"<?php } ?>><?php echo $city['address']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </td>
                        <td class="text-right">
                          <button type="button" onclick="$('#city-row-<?php echo $city_row; ?>').remove();" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button>
                        </td>
                      </tr>
                      <?php $city_row++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="4">
                          <button type="button" onclick="addCity();" class="btn btn-success btn-block btn-sm"><i class="fa fa-plus-circle"></i> <?php echo $button_add; ?></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_consider; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_consider'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_consider" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_consider" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_consider" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_consider" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_matching; ?></label>
                <div class="col-sm-10">
                  <select name="country_id" class="form-control">
                    <option value="0"><?php echo $text_select_country; ?></option>
                    <?php if (!empty($countries)) { ?>
                    <?php foreach ($countries as $country) { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <table id="regions" class="table table-striped table-bordered table-hover"></table>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="<?php echo $m; ?>_title" value="<?php echo ${$m . '_title'}; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="<?php echo $m; ?>_sort_order" value="<?php echo ${$m . '_sort_order'}; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_weight_class; ?></label>
                <div class="col-sm-10">
                  <select name="<?php echo $m; ?>_weight_class_id" class="form-control">
                    <?php foreach ($weight_classes as $weight_class) { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if ($weight_class['weight_class_id'] == ${$m . '_weight_class_id'}) { ?>selected="selected"<?php } ?>><?php echo $weight_class['title']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_length_class; ?></label>
                <div class="col-sm-10">
                  <select name="<?php echo $m; ?>_length_class_id" class="form-control">
                    <?php foreach ($length_classes as $length_class) { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>" <?php if ($length_class['length_class_id'] == ${$m . '_length_class_id'}) { ?>selected="selected"<?php } ?>><?php echo $length_class['title']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_default_type; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_default_type'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_default_type" value="0" autocomplete="off"><?php echo $text_product_one; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_default_type" value="1" autocomplete="off" checked="checked"><?php echo $text_product_all; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_default_type" value="0" autocomplete="off" checked="checked"><?php echo $text_product_one; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_default_type" value="1" autocomplete="off"><?php echo $text_product_all; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_default_weight; ?></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <input type="text" name="<?php echo $m; ?>_default_weight" value="<?php echo ${$m . '_default_weight'}; ?>" class="form-control" />
                    <div class="input-group-addon"><?php echo $text_kg; ?></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_default_dimension; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon"><?php echo $entry_default_length; ?></div>
                        <input type="text" name="<?php echo $m; ?>_default_length" value="<?php echo ${$m . '_default_length'}; ?>" class="form-control" />
                        <div class="input-group-addon"><?php echo $text_sm; ?></div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon"><?php echo $entry_default_width; ?></div>
                        <input type="text" name="<?php echo $m; ?>_default_width" value="<?php echo ${$m . '_default_width'}; ?>" class="form-control" />
                        <div class="input-group-addon"><?php echo $text_sm; ?></div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon"><?php echo $entry_default_height; ?></div>
                        <input type="text" name="<?php echo $m; ?>_default_height" value="<?php echo ${$m . '_default_height'}; ?>" class="form-control" />
                        <div class="input-group-addon"><?php echo $text_sm; ?></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_box_weight; ?></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <input type="text" name="<?php echo $m; ?>_box_weight" value="<?php echo ${$m . '_box_weight'}; ?>" class="form-control" />
                    <div class="input-group-addon"><?php echo $text_kg; ?></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_box_dimension; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon"><?php echo $entry_default_length; ?></div>
                        <input type="text" name="<?php echo $m; ?>_box_length" value="<?php echo ${$m . '_box_length'}; ?>" class="form-control" />
                        <div class="input-group-addon"><?php echo $text_sm; ?></div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon"><?php echo $entry_default_width; ?></div>
                        <input type="text" name="<?php echo $m; ?>_box_width" value="<?php echo ${$m . '_box_width'}; ?>" class="form-control" />
                        <div class="input-group-addon"><?php echo $text_sm; ?></div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon"><?php echo $entry_default_height; ?></div>
                        <input type="text" name="<?php echo $m; ?>_box_height" value="<?php echo ${$m . '_box_height'}; ?>" class="form-control" />
                        <div class="input-group-addon"><?php echo $text_sm; ?></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_calc_type; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if (${$m . '_calc_type'} == 0) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_calc_type" value="0" autocomplete="off" <?php if (${$m . '_calc_type'} == 0) { ?>checked="checked"<?php } ?>><?php echo $text_width; ?></label>
                    <label class="btn btn-default <?php if (${$m . '_calc_type'} == 1) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_calc_type" value="1" autocomplete="off" <?php if (${$m . '_calc_type'} == 1) { ?>checked="checked"<?php } ?>><?php echo $text_length; ?></label>
                    <label class="btn btn-default <?php if (${$m . '_calc_type'} == 2) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_calc_type" value="2" autocomplete="off" <?php if (${$m . '_calc_type'} == 2) { ?>checked="checked"<?php } ?>><?php echo $text_height; ?></label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_custom_sizes; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_custom_sizes'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_custom_sizes" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_custom_sizes" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_custom_sizes" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_custom_sizes" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_tax_class; ?></label>
                <div class="col-sm-10">
                  <select name="<?php echo $m; ?>_tax_class_id" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>" <?php if ($tax_class['tax_class_id'] == ${$m . '_tax_class_id'}) { ?>selected="selected"<?php } ?>><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_status'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_status" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_status" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_status" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_status" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-delivery">
              <div class="row">
                <div class="col-sm-2">
                  <ul class="nav nav-pills nav-stacked" id="delivery">
                    <?php foreach ($variants as $variant) { ?>
                    <li><a href="#tab-delivery-<?php echo $variant['code']; ?>" data-toggle="tab"><?php echo $variant['name']; ?></a></li>
                    <?php } ?>
                  </ul>
                </div>
                <div class="col-sm-10">
                  <div class="tab-content">
                    <?php foreach ($variants as $variant) { ?>
                    <div class="tab-pane active" id="tab-delivery-<?php echo $variant['code']; ?>">
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_code; ?></label>
                        <div class="col-sm-10">
                          <input type="text" value="<?php echo $m; ?>.<?php echo $m; ?>_<?php echo $variant['code']; ?>" class="form-control" readonly />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="<?php echo $m; ?>_quote_title_<?php echo $variant['code']; ?>" value="<?php echo ${$m . '_quote_title_' . $variant['code']}; ?>" class="form-control" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_description; ?></label>
                        <div class="col-sm-10">
                          <textarea name="<?php echo $m; ?>_quote_description_<?php echo $variant['code']; ?>" rows="3" class="form-control"><?php echo ${$m . '_quote_description_' . $variant['code']}; ?></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_add_day; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group">
                            <input type="text" name="<?php echo $m; ?>_add_day_<?php echo $variant['code']; ?>" value="<?php echo ${$m . '_add_day_' . $variant['code']}; ?>" class="form-control" />
                            <div class="input-group-addon"><?php echo $text_dni; ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="<?php echo $m; ?>_sort_order_<?php echo $variant['code']; ?>" value="<?php echo ${$m . '_sort_order_' . $variant['code']}; ?>" class="form-control" />
                        </div>
                      </div>
                      <?php if (in_array($variant['code'], $variants_map)) { ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_list; ?></label>
                        <div class="col-sm-10">
                          <div class="btn-group" data-toggle="buttons">
                            <?php if (${$m . '_list_' . $variant['code']}) { ?>
                            <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_list_<?php echo $variant['code']; ?>" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                            <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_list_<?php echo $variant['code']; ?>" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                            <?php } else { ?>
                            <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_list_<?php echo $variant['code']; ?>" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                            <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_list_<?php echo $variant['code']; ?>" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                          <div class="btn-group" data-toggle="buttons">
                            <?php if (${$m . '_status_' . $variant['code']}) { ?>
                            <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_status_<?php echo $variant['code']; ?>" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                            <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_status_<?php echo $variant['code']; ?>" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                            <?php } else { ?>
                            <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_status_<?php echo $variant['code']; ?>" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                            <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_status_<?php echo $variant['code']; ?>" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-stop">
              <div class="form-group">
                <div class="col-sm-12">
                  <table id="stops" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-center"><?php echo $column_variant; ?></td>
                        <td class="text-center"><?php echo $column_customer; ?></td>
                        <td class="text-center"><?php echo $column_geo_zone; ?></td>
                        <td class="text-center"><?php echo $column_city; ?></td>
                        <td class="text-center"><?php echo $column_weight; ?></td>
                        <td class="text-center"><?php echo $column_cost_order; ?></td>
                        <td width="1"></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $stop_row = 0; ?>
                      <?php foreach (${$m . '_stops'} as $stop) { ?>
                        <tr id="stop-row-<?php echo $stop_row; ?>">
                          <td class="text-left">
                            <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">
                              <?php foreach ($variants as $variant) { ?>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="<?php echo $m; ?>_stops[<?php echo $stop_row; ?>][variant][]" value="<?php echo $variant['code']; ?>" <?php if (isset($stop['variant']) && in_array($variant['code'], $stop['variant'])) { ?>checked="checked"<?php } ?> />
                                  <?php echo $variant['name']; ?>
                                </label>
                              </div>
                              <?php } ?>
                            </div>
                          </td>
                          <td class="text-left">
                            <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">
                              <?php if (!empty($customer_groups)) { ?>
                              <?php foreach ($customer_groups as $customer_group) { ?>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="<?php echo $m; ?>_stops[<?php echo $stop_row; ?>][customer_group][]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php if (isset($stop['customer_group']) && in_array($customer_group['customer_group_id'], $stop['customer_group'])) { ?>checked="checked"<?php } ?> />
                                  <?php echo $customer_group['name']; ?>
                                </label>
                              </div>
                              <?php } ?>
                              <?php } ?>
                            </div>
                          </td>
                          <td class="text-left">
                            <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">
                              <?php if (!empty($geo_zones)) { ?>
                              <?php foreach ($geo_zones as $geo_zone) { ?>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="<?php echo $m; ?>_stops[<?php echo $stop_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php if (isset($stop['geo_zone']) && in_array($geo_zone['geo_zone_id'], $stop['geo_zone'])) { ?>checked="checked"<?php } ?> />
                                  <?php echo $geo_zone['name']; ?>
                                </label>
                              </div>
                              <?php } ?>
                              <?php } ?>
                            </div>
                          </td>
                          <td class="text-left">
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_only_from; ?></div>
                              <input type="text" name="<?php echo $m; ?>_stops[<?php echo $stop_row; ?>][city_only]" value="<?php echo $stop['city_only']; ?>" class="form-control" />
                            </div>
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_only_exclude; ?></div>
                              <input type="text" name="<?php echo $m; ?>_stops[<?php echo $stop_row; ?>][city_exclude]" value="<?php echo $stop['city_exclude']; ?>" class="form-control" />
                            </div>
                          </td>
                          <td class="text-left">
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_from; ?></div>
                              <input type="text" name="<?php echo $m; ?>_stops[<?php echo $stop_row; ?>][weight_min]" value="<?php echo $stop['weight_min']; ?>" class="form-control" />
                              <div class="input-group-addon"><?php echo $text_kg; ?></div>
                            </div>
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_to; ?></div>
                              <input type="text" name="<?php echo $m; ?>_stops[<?php echo $stop_row; ?>][weight_max]" value="<?php echo $stop['weight_max']; ?>" class="form-control" />
                              <div class="input-group-addon"><?php echo $text_kg; ?></div>
                            </div>
                          </td>
                          <td class="text-left">
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_from; ?></div>
                              <input type="text" name="<?php echo $m; ?>_stops[<?php echo $stop_row; ?>][total_min]" value="<?php echo $stop['total_min']; ?>" class="form-control" />
                              <div class="input-group-addon"><?php echo $text_rub; ?></div>
                            </div>
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_to; ?></div>
                              <input type="text" name="<?php echo $m; ?>_stops[<?php echo $stop_row; ?>][total_max]" value="<?php echo $stop['total_max']; ?>" class="form-control" />
                              <div class="input-group-addon"><?php echo $text_rub; ?></div>
                            </div>
                          </td>
                          <td class="text-right">
                            <button type="button" onclick="$('#stop-row-<?php echo $stop_row; ?>').remove();" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button>
                          </td>
                        </tr>
                        <?php $stop_row++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="7">
                          <button type="button" onclick="addStop();" class="btn btn-success btn-block btn-sm"><i class="fa fa-plus-circle"></i> <?php echo $button_add; ?></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-cost">
              <div class="form-group">
                <div class="col-sm-12">
                  <table id="costs" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-center"><?php echo $column_variant; ?></td>
                        <td class="text-center" colspan="2"><?php echo $column_cost; ?></td>
                        <td class="text-center"><?php echo $column_customer; ?></td>
                        <td class="text-center"><?php echo $column_geo_zone; ?></td>
                        <td class="text-center"><?php echo $column_city; ?></td>
                        <td class="text-center" colspan="3"><?php echo $column_mod; ?></td>
                        <td class="text-center"><?php echo $column_position; ?></td>
                        <td width="1"></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $cost_row = 0; ?>
                      <?php foreach (${$m . '_costs'} as $cost) { ?>
                        <tr id="cost-row-<?php echo $cost_row; ?>">
                          <td class="text-left">
                            <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">
                              <?php foreach ($variants as $variant) { ?>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][variant][]" value="<?php echo $variant['code']; ?>" <?php if (isset($cost['variant']) && in_array($variant['code'], $cost['variant'])) { ?>checked="checked"<?php } ?> />
                                  <?php echo $variant['name']; ?>
                                </label>
                              </div>
                              <?php } ?>
                            </div>
                          </td>
                          <td class="text-left" style="border-right: none;">
                            <div class="btn-group btn-group-vertical" data-toggle="buttons">
                              <label class="btn btn-default <?php if ($cost['cost_type'] == 0) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][cost_type]" value="0" autocomplete="off" <?php if ($cost['cost_type'] == 0) { ?>checked="checked"<?php } ?>><?php echo $text_order; ?></label>
                              <label class="btn btn-default <?php if ($cost['cost_type'] == 1) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][cost_type]" value="1" autocomplete="off" <?php if ($cost['cost_type'] == 1) { ?>checked="checked"<?php } ?>><?php echo $text_product; ?></label>
                              <label class="btn btn-default <?php if ($cost['cost_type'] == 2) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][cost_type]" value="2" autocomplete="off" <?php if ($cost['cost_type'] == 2) { ?>checked="checked"<?php } ?>><?php echo $text_shipping; ?></label>
                            </div>
                          </td>
                          <td class="text-left" style="border-left: none;">
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_from; ?></div>
                              <input type="text" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][cost_from]" value="<?php echo $cost['cost_from']; ?>" class="form-control" />
                            </div>
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_to; ?></div>
                              <input type="text" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][cost_to]" value="<?php echo $cost['cost_to']; ?>" class="form-control" />
                            </div>
                          </td>
                          <td class="text-left">
                            <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">
                              <?php if (!empty($customer_groups)) { ?>
                              <?php foreach ($customer_groups as $customer_group) { ?>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][customer_group][]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php if (isset($cost['customer_group']) && in_array($customer_group['customer_group_id'], $cost['customer_group'])) { ?>checked="checked"<?php } ?> />
                                  <?php echo $customer_group['name']; ?>
                                </label>
                              </div>
                              <?php } ?>
                              <?php } ?>
                            </div>
                          </td>
                          <td class="text-left">
                            <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">
                              <?php if (!empty($geo_zones)) { ?>
                              <?php foreach ($geo_zones as $geo_zone) { ?>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php if (isset($cost['geo_zone']) && in_array($geo_zone['geo_zone_id'], $cost['geo_zone'])) { ?>checked="checked"<?php } ?> />
                                  <?php echo $geo_zone['name']; ?>
                                </label>
                              </div>
                              <?php } ?>
                              <?php } ?>
                            </div>
                          </td>
                          <td class="text-left">
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_only_from; ?></div>
                              <input type="text" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][city_only]" value="<?php echo $cost['city_only']; ?>" class="form-control" />
                            </div>
                            <div class="input-group">
                              <div class="input-group-addon"><?php echo $text_only_exclude; ?></div>
                              <input type="text" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][city_exclude]" value="<?php echo $cost['city_exclude']; ?>" class="form-control" />
                            </div>
                          </td>
                          <td class="text-left" style="border-right: none;">
                            <div class="btn-group btn-group-vertical" data-toggle="buttons">
                              <label class="btn btn-default <?php if ($cost['action'] == '+') { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][action]" value="+" autocomplete="off" <?php if ($cost['action'] == '+') { ?>checked="checked"<?php } ?>>+</label>
                              <label class="btn btn-default <?php if ($cost['action'] == '-') { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][action]" value="-" autocomplete="off" <?php if ($cost['action'] == '-') { ?>checked="checked"<?php } ?>>-</label>
                              <label class="btn btn-default <?php if ($cost['action'] == '=') { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][action]" value="=" autocomplete="off" <?php if ($cost['action'] == '=') { ?>checked="checked"<?php } ?>>=</label>
                            </div>
                          </td>
                          <td class="text-left" style="border-left: none; border-right: none;">
                            <input type="text" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][value]" value="<?php echo $cost['value']; ?>" class="form-control" />
                          </td>
                          <td class="text-left" style="border-left: none;">
                            <div class="btn-group btn-group-vertical btn-group-sm" data-toggle="buttons">
                              <label class="btn btn-default <?php if ($cost['source'] == 0) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][source]" value="0" autocomplete="off" <?php if ($cost['source'] == 0) { ?>checked="checked"<?php } ?>><?php echo $text_rub; ?></label>
                              <label class="btn btn-default <?php if ($cost['source'] == 1) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][source]" value="1" autocomplete="off" <?php if ($cost['source'] == 1) { ?>checked="checked"<?php } ?>><?php echo $text_percent_order; ?></label>
                              <label class="btn btn-default <?php if ($cost['source'] == 2) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][source]" value="2" autocomplete="off" <?php if ($cost['source'] == 2) { ?>checked="checked"<?php } ?>><?php echo $text_percent_product; ?></label>
                              <label class="btn btn-default <?php if ($cost['source'] == 3) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][source]" value="3" autocomplete="off" <?php if ($cost['source'] == 3) { ?>checked="checked"<?php } ?>><?php echo $text_percent_shipping; ?></label>
                            </div>
                          </td>
                          <td class="text-left">
                            <div class="btn-group btn-group-vertical" data-toggle="buttons">
                              <label class="btn btn-default <?php if ($cost['position'] == 0) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][position]" value="0" autocomplete="off" <?php if ($cost['position'] == 0) { ?>checked="checked"<?php } ?>><?php echo $text_dostavka; ?></label>
                              <label class="btn btn-default <?php if ($cost['position'] == 1) { ?>active<?php } ?>"><input type="radio" name="<?php echo $m; ?>_costs[<?php echo $cost_row; ?>][position]" value="1" autocomplete="off" <?php if ($cost['position'] == 1) { ?>checked="checked"<?php } ?>><?php echo $text_total; ?></label>
                            </div>
                          </td>
                          <td class="text-right">
                            <button type="button" onclick="$('#cost-row-<?php echo $cost_row; ?>').remove();" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button>
                          </td>
                        </tr>
                        <?php $cost_row++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="11">
                          <button type="button" onclick="addCost();" class="btn btn-success btn-block btn-sm"><i class="fa fa-plus-circle"></i> <?php echo $button_add; ?></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-map">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_map_key; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="<?php echo $m; ?>_map_key" value="<?php echo ${$m . '_map_key'}; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_map_status'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_map_status" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_map_status" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_map_status" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_map_status" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_map_type; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_map_type'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_map_type" value="0" autocomplete="off"><?php echo $text_map_overall; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_map_type" value="1" autocomplete="off" checked="checked"><?php echo $text_map_individual; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_map_type" value="0" autocomplete="off" checked="checked"><?php echo $text_map_overall; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_map_type" value="1" autocomplete="off"><?php echo $text_map_individual; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_map_controls; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto; margin: 0;">
                    <?php foreach ($map_controls as $control) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($control['code'], ${$m . '_map_control'})) { ?>
                        <input type="checkbox" name="<?php echo $m; ?>_map_control[]" value="<?php echo $control['code']; ?>" checked="checked" />
                        <?php echo $control['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="<?php echo $m; ?>_map_control[]" value="<?php echo $control['code']; ?>" />
                        <?php echo $control['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_map_button; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="<?php echo $m; ?>_map_button" value="<?php echo ${$m . '_map_button'}; ?>" class="form-control" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-cap">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_cap_status'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_cap_status" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_cap_status" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_cap_status" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_cap_status" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_cap_error; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <?php if (${$m . '_cap_error'}) { ?>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_cap_error" value="0" autocomplete="off"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_cap_error" value="1" autocomplete="off" checked="checked"><?php echo $text_enabled; ?></label>
                    <?php } else { ?>
                    <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_cap_error" value="0" autocomplete="off" checked="checked"><?php echo $text_disabled; ?></label>
                    <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_cap_error" value="1" autocomplete="off"><?php echo $text_enabled; ?></label>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="<?php echo $m; ?>_cap_title" value="<?php echo ${$m . '_cap_title'}; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_cap_cost; ?></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <input type="text" name="<?php echo $m; ?>_cap_cost" value="<?php echo ${$m . '_cap_cost'}; ?>" class="form-control" />
                    <div class="input-group-addon"><?php echo $text_rub; ?></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-support">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_license; ?></label>
                <div class="col-sm-10">
                  <input type="hidden" value="<?php echo $host; ?>" />
                  <input type="text" name="<?php echo $m; ?>_license" value="<?php echo ${$m . '_license'}; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_developer; ?></label>
                <div class="col-sm-10"><a href="mailto:<?php echo $email; ?>" class="btn"><?php echo $email; ?></a></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_site; ?></label>
                <div class="col-sm-10"><a href="<?php echo $site; ?>" target="_blank" class="btn"><?php echo $site; ?></a></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_module_docs; ?></label>
                <div class="col-sm-10"><a href="<?php echo $module_docs; ?>" target="_blank" class="btn"><?php echo $module_docs; ?></a></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_delivery; ?></label>
                <div class="col-sm-10"><a href="<?php echo $delivery; ?>" target="_blank" class="btn"><?php echo $delivery; ?></a></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_api_docs; ?></label>
                <div class="col-sm-10"><a href="<?php echo $api_docs; ?>" target="_blank" class="btn"><?php echo $api_docs; ?></a></div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="panel-footer">
        <img src="../image/catalog/<?php echo $m; ?>/ll.png" alt="<?php echo $heading_title; ?>" class="pull-right">
        <span class="label label-default"><?php echo $m; ?></span>
        <span class="label label-default"><?php echo $version; ?></span>
      </div>
    </div>
    <?php } else { ?>
    <div class="panel panel-danger">
      <div class="panel-heading"><?php echo $heading_license; ?></div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_license; ?></label>
            <div class="col-sm-10">
              <input type="hidden" value="<?php echo $host; ?>" />
              <input type="text" name="<?php echo $m; ?>_license" value="<?php echo ${$m . '_license'}; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_developer; ?></label>
            <div class="col-sm-10"><a href="mailto:<?php echo $email; ?>" class="btn"><?php echo $email; ?></a></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_site; ?></label>
            <div class="col-sm-10"><a href="<?php echo $site; ?>" target="_blank" class="btn"><?php echo $site; ?></a></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_module_docs; ?></label>
            <div class="col-sm-10"><a href="<?php echo $module_docs; ?>" target="_blank" class="btn"><?php echo $module_docs; ?></a></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_delivery; ?></label>
            <div class="col-sm-10"><a href="<?php echo $delivery; ?>" target="_blank" class="btn"><?php echo $delivery; ?></a></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_api_docs; ?></label>
            <div class="col-sm-10"><a href="<?php echo $api_docs; ?>" target="_blank" class="btn"><?php echo $api_docs; ?></a></div>
          </div>
        </form>
      </div>
      <div class="panel-footer">
        <img src="../image/catalog/<?php echo $m; ?>/ll.png" alt="<?php echo $heading_title; ?>" class="pull-right">
        <span class="label label-default"><?php echo $m; ?></span>
        <span class="label label-default"><?php echo $version; ?></span>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<?php if (${$m . '_license'}) { ?>
<script>
$('#delivery li:not(".hidden") a:first').tab('show');

var city_row = <?php echo $city_row; ?>;

function addCity() {
  html  = '<tr id="city-row-' + city_row + '">';
  html += '  <td class="text-left">';
  html += '    <select name="<?php echo $m; ?>_pickup_cities[]" class="<?php echo $m; ?>_pickup_cities form-control" id="<?php echo $m; ?>_pickup_cities_' + city_row + '">';
  html += '      <option><?php echo $text_select_place; ?></option>';
  <?php if (!empty($places)) { ?>
  <?php foreach ($places as $city) { ?>
  html += '      <option value="<?php echo $city['id']; ?>"><?php echo $city['address']; ?></option>';
  <?php } ?>
  <?php } ?>
  html += '    </select>';
  html += '  </td>';
  html += '  <td class="text-right"><button type="button" onclick="$(\'#city-row-' + city_row  + '\').remove();" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#cities tbody').append(html);

  city_row++;
}

function updateData(current = 0, type = 0, next = 0) {
  $.ajax({
    url: 'index.php?route=shipping/<?php echo $m; ?>/updateData&token=<?php echo $token; ?>',
    type: 'post',
    data: 'current=' + current + '&type=' + type + '&next=' + next,
    dataType: 'json',
    beforeSend: function() {
      $('#ll_update_data').button('loading');
    },
    success: function(json) {
      $('.alert,.progress').remove();

      if (json['success']) {
        var percent = Math.round(json['current'] / (json['total'] / 100));

        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        $('#content > .container-fluid').prepend('<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' + percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + percent + '%;">' + percent + '%</div></div>');

        if (json['next']) {
          updateData(json['current'], json['type'], json['next']);
        } else {
          updateData(json['current'], json['type']);
        }
      }

      if (json['finish']) {
        if (json['type']) {
          $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['finish'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          $('#content > .container-fluid').prepend('<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div></div>');

          updateData(0, json['type']);
        } else {
          $('#ll_update_data').button('reset');
          $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['finish'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          $('#content > .container-fluid').prepend('<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div></div>');
        }
      }

      if (json['error']) {
        $('#ll_update_data').button('reset');
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

$('select[name=\'country_id\']').on('change', function() {
  var country_id = this.value;

  if (country_id > 0) {
    $.ajax({
      url: 'index.php?route=shipping/<?php echo $m; ?>/getRegionsTable&token=<?php echo $token; ?>&country_id=' + encodeURIComponent(country_id),
      dataType: 'json',
      success: function(json) {
        if (json['zones'] && json['zones'] != '' && json['regions'] && json['regions'] != '') {
          html = '<tbody><thead><tr><td><?php echo $text_region_shipping; ?></td><td><?php echo $text_region_opencart; ?></td></tr></thead>';

          for (r = 0; r < json['regions'].length; r++) {
            if (json['regions'][r]['name'] == '') continue;

            html += ' <tr>';
            html += '  <td class="text-left">';
            html += '    <input type="text" value="' + json['regions'][r]['name'] + '" class="form-control" disabled />';
            html += '  </td>';
            html += '  <td class="text-left">';
            html += '    <select name="<?php echo $m; ?>_regions[' + json['regions'][r]['region_id'] + '][]" class="form-control <?php echo $m; ?>_regions" multiple style="height: 300px !important;">';

            for (i = 0; i < json['zones'].length; i++) {
              html += '      <option value="' + json['zones'][i]['zone_id'] + '"';

              if (json['regions'][r]['zones'].includes(json['zones'][i]['zone_id'])) {
                html += ' selected="selected"';
              }

              html += '>' + json['zones'][i]['name'] + '</option>';
            }

            html += '    </select>';
            html += '  </td>';
            html += ' </tr>';
          }

          html += '</tbody>';
          html += '<tfoot>';
          html += ' <tr>';
          html += '   <td colspan="2">';
          html += '     <button type="button" onclick="updateRegionToZone(' + country_id + ');" class="btn btn-success btn-block btn-sm"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>';
          html += '   </td>';
          html += ' </tr>';
          html += '</tfoot>';

          $('#regions').html(html);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  } else {
    $('#regions').html('');
  }
});

function updateRegionToZone(country_id) {
  $.ajax({
    url: 'index.php?route=shipping/<?php echo $m; ?>/updateRegionToZone&token=<?php echo $token; ?>&country_id=' + encodeURIComponent(country_id) + '&' + $('.<?php echo $m; ?>_regions').serialize(),
    dataType: 'json',
    success: function(json) {
      $('.alert').remove();
      $('#regions').html('');
      $('select[name=\'country_id\']').val(0);

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }

      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

var stop_row = <?php echo $stop_row; ?>;

function addStop() {
  html  = '<tr id="stop-row-' + stop_row + '">';
  html += '  <td class="text-left">';
  html += '    <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">';
  <?php foreach ($variants as $variant) { ?>
  html += '      <div class="checkbox"><label><input type="checkbox" name="<?php echo $m; ?>_stops[' + stop_row + '][variant][]" value="<?php echo $variant['code']; ?>" /> <?php echo $variant['name']; ?></label></div>';
  <?php } ?>
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left">';
  html += '    <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">';
  <?php if (!empty($customer_groups)) { ?>
  <?php foreach ($customer_groups as $customer_group) { ?>
  html += '      <div class="checkbox"><label><input type="checkbox" name="<?php echo $m; ?>_stops[' + stop_row + '][customer_group][]" value="<?php echo $customer_group['customer_group_id']; ?>" /> <?php echo $customer_group['name']; ?></label></div>';
  <?php } ?>
  <?php } ?>
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left">';
  html += '    <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">';
  <?php if (!empty($geo_zones)) { ?>
  <?php foreach ($geo_zones as $geo_zone) { ?>
  html += '      <div class="checkbox"><label><input type="checkbox" name="<?php echo $m; ?>_stops[' + stop_row + '][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" /> <?php echo $geo_zone['name']; ?></label></div>';
  <?php } ?>
  <?php } ?>
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left">';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_only_from; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_stops[' + stop_row + '][city_only]" value="" class="form-control" />';
  html += '    </div>';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_only_exclude; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_stops[' + stop_row + '][city_exclude]" value="" class="form-control" />';
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left">';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_from; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_stops[' + stop_row + '][weight_min]" value="" class="form-control" />';
  html += '      <div class="input-group-addon"><?php echo $text_kg; ?></div>';
  html += '    </div>';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_to; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_stops[' + stop_row + '][weight_max]" value="" class="form-control" />';
  html += '      <div class="input-group-addon"><?php echo $text_kg; ?></div>';
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left">';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_from; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_stops[' + stop_row + '][total_min]" value="" class="form-control" />';
  html += '      <div class="input-group-addon"><?php echo $text_rub; ?></div>';
  html += '    </div>';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_to; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_stops[' + stop_row + '][total_max]" value="" class="form-control" />';
  html += '      <div class="input-group-addon"><?php echo $text_rub; ?></div>';
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-right"><button type="button" onclick="$(\'#stop-row-' + stop_row + '\').remove();" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#stops tbody').append(html);

  stop_row++;
}

var cost_row = <?php echo $cost_row; ?>;

function addCost() {
  html  = '<tr id="cost-row-' + cost_row + '">';
  html += '  <td class="text-left">';
  html += '    <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">';
  <?php foreach ($variants as $variant) { ?>
  html += '      <div class="checkbox"><label><input type="checkbox" name="<?php echo $m; ?>_costs[' + cost_row + '][variant][]" value="<?php echo $variant['code']; ?>" /> <?php echo $variant['name']; ?></label></div>';
  <?php } ?>
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left" style="border-right: none;">';
  html += '    <div class="btn-group btn-group-vertical" data-toggle="buttons">';
  html += '      <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][cost_type]" value="0" autocomplete="off" checked="checked"><?php echo $text_order; ?></label>';
  html += '      <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][cost_type]" value="1" autocomplete="off"><?php echo $text_product; ?></label>';
  html += '      <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][cost_type]" value="2" autocomplete="off"><?php echo $text_shipping; ?></label>';
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left" style="border-left: none;">';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_from; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_costs[' + cost_row + '][cost_from]" value="" class="form-control" />';
  html += '    </div>';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_to; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_costs[' + cost_row + '][cost_to]" value="" class="form-control" />';
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left">';
  html += '    <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">';
  <?php if (!empty($customer_groups)) { ?>
  <?php foreach ($customer_groups as $customer_group) { ?>
  html += '      <div class="checkbox"><label><input type="checkbox" name="<?php echo $m; ?>_costs[' + cost_row + '][customer_group][]" value="<?php echo $customer_group['customer_group_id']; ?>" /> <?php echo $customer_group['name']; ?></label></div>';
  <?php } ?>
  <?php } ?>
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left">';
  html += '    <div class="well well-sm" style="height: 100px; overflow: auto; margin: 0;">';
  <?php if (!empty($geo_zones)) { ?>
  <?php foreach ($geo_zones as $geo_zone) { ?>
  html += '      <div class="checkbox"><label><input type="checkbox" name="<?php echo $m; ?>_costs[' + cost_row + '][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" /> <?php echo $geo_zone['name']; ?></label></div>';
  <?php } ?>
  <?php } ?>
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left">';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_only_from; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_costs[' + cost_row + '][city_only]" value="" class="form-control" />';
  html += '    </div>';
  html += '    <div class="input-group">';
  html += '      <div class="input-group-addon"><?php echo $text_only_exclude; ?></div>';
  html += '      <input type="text" name="<?php echo $m; ?>_costs[' + cost_row + '][city_exclude]" value="" class="form-control" />';
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left" style="border-right: none;">';
  html += '    <div class="btn-group btn-group-vertical" data-toggle="buttons">';
  html += '      <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][action]" value="+" autocomplete="off" checked="checked">+</label>';
  html += '      <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][action]" value="-" autocomplete="off">-</label>';
  html += '      <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][action]" value="=" autocomplete="off">=</label>';
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left" style="border-left: none; border-right: none;">';
  html += '    <input type="text" name="<?php echo $m; ?>_costs[' + cost_row + '][value]" value="" class="form-control" />';
  html += '  </td>';
  html += '  <td class="text-left" style="border-left: none;">';
  html += '    <div class="btn-group btn-group-vertical btn-group-sm" data-toggle="buttons">';
  html += '      <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][source]" value="0" autocomplete="off" checked="checked"><?php echo $text_rub; ?></label>';
  html += '      <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][source]" value="1" autocomplete="off""><?php echo $text_percent_order; ?></label>';
  html += '      <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][source]" value="2" autocomplete="off"><?php echo $text_percent_product; ?></label>';
  html += '      <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][source]" value="3" autocomplete="off"><?php echo $text_percent_shipping; ?></label>';
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-left">';
  html += '    <div class="btn-group btn-group-vertical" data-toggle="buttons">';
  html += '      <label class="btn btn-default active"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][position]" value="0" autocomplete="off" checked="checked"><?php echo $text_dostavka; ?></label>';
  html += '      <label class="btn btn-default"><input type="radio" name="<?php echo $m; ?>_costs[' + cost_row + '][position]" value="1" autocomplete="off""><?php echo $text_total; ?></label>';
  html += '    </div>';
  html += '  </td>';
  html += '  <td class="text-right"><button type="button" onclick="$(\'#cost-row-' + cost_row + '\').remove();" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#costs tbody').append(html);

  cost_row++;
}
</script>
<?php } ?>
<?php echo $footer; ?> 
