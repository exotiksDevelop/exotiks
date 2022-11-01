<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
    <div class="container-fluid">
        <div class="pull-right">
            <button type="submit" form="form-bb" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
<div class="panel-body">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-bb" class="form-horizontal">
<div class="row">
<div class="col-sm-2">
    <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
        <?php foreach ($geo_zones as $geo_zone) { ?>
        <li><a href="#tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>" data-toggle="tab"><?php echo $geo_zone['name']. ' ('; echo (${'bb_' . $geo_zone['geo_zone_id'] . '_status'}) ? $text_enabled . ')' : $text_disabled. ')'; ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="col-sm-10">
<div class="tab-content">
<div class="tab-pane active" id="tab-general">
    <table class="table table-bordered">
        <tr>
            <td><?php echo $text_license_name; ?></td>
            <td><input onchange="gen_license_id(); return false;" type="text" name="bb_license_name" id="license_name" value="<?php echo $bb_license_name; ?>"></td>
        </tr>
        <tr>
            <td><?php echo $text_license_id; ?></td>
            <td><input id="license_id" type="text" style="width: 450px;" onclick="this.setSelectionRange(0, this.value.length);" readonly name="bb_license_id"><br><?php echo $text_license_id_hint; ?></td>
        </tr>
        <tr>
            <td><?php echo $text_license_info; ?></td>
            <td><textarea id="license_info" style="width: 450px;" rows="7" name="bb_license_info"><?php echo $bb_license_info; ?></textarea><br><?php echo $text_license_info_hint; ?></td>
        </tr>
        <tr>
            <td><?php echo $entry_api_token; ?></td>
            <td><input type="text" name="bb_api_token" value="<?php echo $bb_api_token; ?>" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_targetstart; ?></td>
            <td><input type="text" name="bb_targetstart" value="<?php echo $bb_targetstart; ?>" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_calc_type; ?></td>
            <td>
                <select id="bb-calc-type" name="bb_calc_type">
                    <option value="0" <?php if ($bb_calc_type == 0) echo 'selected="selected"'; ?>><?php echo $text_calc_api; ?></option>
                    <option value="1" <?php if ($bb_calc_type == 1) echo 'selected="selected"'; ?>><?php echo $text_manually; ?></option>
                    <option value="2" <?php if ($bb_calc_type == 2) echo 'selected="selected"'; ?>><?php echo $text_tariff_zones; ?></option>
                </select>

                <div style="display: inline;" class="fix-rate-block-main">
                    <input type="text" id="bb-fix-rate" name="bb_fix_rate" value="<?php echo $bb_fix_rate; ?>" />
                    <span><?php echo $entry_fix_delivery_period; ?></span>
                    <input type="text" name="bb_fix_delivery_period" value="<?php echo $bb_fix_delivery_period; ?>" size="3"/>
                </div>

            </td>
        </tr>
        <tr>
            <td/>
            <td>
                <table id="table-tariff-zones">
                    <tr align="center">
                        <td><?php echo $text_zone_label; ?> 1</td>
                        <td><?php echo $text_zone_label; ?> 2</td>
                        <td><?php echo $text_zone_label; ?> 3</td>
                        <td><?php echo $text_zone_label; ?> 4</td>
                        <td><?php echo $text_zone_label; ?> 5</td>
                        <td><?php echo $text_zone_label; ?> 6</td>
                        <td><?php echo $text_zone_label; ?> 7</td>
                        <td><?php echo $text_zone_label; ?> 8</td>
                        <td><?php echo $text_zone_label; ?> 9</td>
                    </tr>
                    <tr>
                        <td><input style="width: 60px;" type="text" name="bb_tariff_zone_1" id="bb-tariff-zone-1" value="<?php echo $bb_tariff_zone_1; ?>" /></td>
                        <td><input style="width: 60px;" type="text" name="bb_tariff_zone_2" id="bb-tariff-zone-2" value="<?php echo $bb_tariff_zone_2; ?>" /></td>
                        <td><input style="width: 60px;" type="text" name="bb_tariff_zone_3" id="bb-tariff-zone-3" value="<?php echo $bb_tariff_zone_3; ?>" /></td>
                        <td><input style="width: 60px;" type="text" name="bb_tariff_zone_4" id="bb-tariff-zone-4" value="<?php echo $bb_tariff_zone_4; ?>" /></td>
                        <td><input style="width: 60px;" type="text" name="bb_tariff_zone_5" id="bb-tariff-zone-5" value="<?php echo $bb_tariff_zone_5; ?>" /></td>
                        <td><input style="width: 60px;" type="text" name="bb_tariff_zone_6" id="bb-tariff-zone-6" value="<?php echo $bb_tariff_zone_6; ?>" /></td>
                        <td><input style="width: 60px;" type="text" name="bb_tariff_zone_7" id="bb-tariff-zone-7" value="<?php echo $bb_tariff_zone_7; ?>" /></td>
                        <td><input style="width: 60px;" type="text" name="bb_tariff_zone_8" id="bb-tariff-zone-8" value="<?php echo $bb_tariff_zone_8; ?>" /></td>
                        <td><input style="width: 60px;" type="text" name="bb_tariff_zone_9" id="bb-tariff-zone-9" value="<?php echo $bb_tariff_zone_9; ?>" /></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="background: lightyellow">
            <td><?php echo $entry_processing_days; ?></td>
            <td><input size="3" type="text" name="bb_processing_days" id="bb-processing-days" value="<?php echo $bb_processing_days; ?>" /></td>
        </tr>
        <tr style="background: lightyellow">
            <td><?php echo $entry_delivery_period; ?></td>
            <td>
                <label><input type="radio" name="bb_show_delivery_period" value="1" <?php echo $bb_show_delivery_period ? 'checked="checked"' : '' ?>><?php echo $text_yes; ?></label>
                <label><input type="radio" name="bb_show_delivery_period" value="0" <?php echo !$bb_show_delivery_period ? 'checked="checked"' : '' ?>><?php echo $text_no; ?></label>
            </td>
        </tr>
        <tr style="display:none;background: lightyellow">
            <td><?php echo $entry_delivery_date; ?></td>
            <td>
                <label><input type="radio" name="bb_show_delivery_date" value="1" <?php echo $bb_show_delivery_date ? 'checked="checked"' : '' ?>><?php echo $text_yes; ?></label>
                <label><input type="radio" name="bb_show_delivery_date" value="0" <?php echo !$bb_show_delivery_date ? 'checked="checked"' : '' ?>><?php echo $text_no; ?></label>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_cost; ?></td>
            <td><input type="text" name="bb_rate_value" id="bb-rate-value" value="<?php echo $bb_rate_value; ?>" />
                <select name="bb_rate_option" id="bb-rate-option">
                    <option value="0" <?php if ($bb_rate_option == 0) echo 'selected="selected"'; ?>><?php echo $text_add_cost_type_fixed; ?></option>
                    <option value="1" <?php if ($bb_rate_option == 1) echo 'selected="selected"'; ?>><?php echo $text_add_cost_type_percent; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_package_weight; ?></td>
            <td><input type="text" name="bb_package_weight" value="<?php echo $bb_package_weight; ?>" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_check_weight; ?></td>
            <td>
                <label><input type="radio" name="bb_check_weight" value="1" <?php echo $bb_check_weight ? 'checked="checked"' : '' ?>><?php echo $text_yes; ?></label>
                <label><input type="radio" name="bb_check_weight" value="0" <?php echo !$bb_check_weight ? 'checked="checked"' : '' ?>><?php echo $text_no; ?></label>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_package_size; ?></td>
            <td>
                <table id="package_size_table">
                    <tr>
                        <td>
                            <label><input type="radio" name="bb_package_size_calc_type" value="0" <?php echo $bb_package_size_calc_type == 0 ? 'checked="checked"' : '' ?>><?php echo $entry_package_size_auto; ?></label>
                        </td>
                        <td/>
                    </tr>
                    <tr>
                        <td>
                            <label><input type="radio" name="bb_package_size_calc_type" value="1" <?php echo $bb_package_size_calc_type == 1 ? 'checked="checked"' : '' ?>><?php echo $entry_package_size_manual; ?></label>
                        </td>
                        <td>
                            <input name="bb_package_width" style="width: 50px;" value="<?php echo $bb_package_width; ?>" type="text"><?php echo $entry_package_width; ?><input name="bb_package_height" style="width: 50px;" value="<?php echo $bb_package_height; ?>" type="text"><?php echo $entry_package_height; ?><input name="bb_package_depth" style="width: 50px;" value="<?php echo $bb_package_depth; ?>" type="text"><?php echo $entry_package_depth; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_free_ship; ?></td>
            <td><input type="text" id="bb-free-ship" name="bb_free_ship" value="<?php echo $bb_free_ship; ?>" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_kd_free_too; ?></td>
            <td><input type="checkbox" name="bb_kd_free_too" id="bb-kd-free-too" value="1" <?php if( $bb_kd_free_too == 1) { echo 'checked="checked"'; }?>/></td>
        </tr>
        <tr>
            <td><?php echo $entry_free_total; ?></td>
            <td><input type="text" id="bb-free-total" name="bb_free_total" value="<?php echo $bb_free_total; ?>" /><?php echo $entry_free_total_to; ?><input type="text" id="bb-free-total-to" name="bb_free_total_to" value="<?php echo $bb_free_total_to; ?>" /><?php echo $entry_free_total_to_hint; ?></td>
        </tr>

                        <tr>
                            <td><?php echo $entry_total_type; ?></td>
                            <td>
                                <select name="bb_total_type" id="bb-total-type">
                                    <option value="0" <?php if ($bb_total_type == 0) echo 'selected="selected"'; ?>><?php echo $text_free_subtotal; ?></option>
                                    <option value="1" <?php if ($bb_total_type == 1) echo 'selected="selected"'; ?>><?php echo $text_free_total; ?></option>
                                </select>
                            </td>
                        </tr>

        <tr>
            <td><?php echo $entry_shipping_type; ?></td>
            <td>
                <select name="bb_shipping_type" id="bb-shipping-type">
                    <option value="0" <?php if ($bb_shipping_type == 0) echo 'selected="selected"'; ?>><?php echo $text_shipping_type_no_delivery; ?></option>
                    <option value="1" <?php if ($bb_shipping_type == 1) echo 'selected="selected"'; ?>><?php echo $text_shipping_type_all; ?></option>
                    <option value="2" <?php if ($bb_shipping_type == 2) echo 'selected="selected"'; ?>><?php echo $text_shipping_type_pickup; ?></option>
                    <option value="3" <?php if ($bb_shipping_type == 3) echo 'selected="selected"'; ?>><?php echo $text_shipping_type_kd; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_round; ?></td>
            <td>
                <select name="bb_round" id="bb-round">
                    <option value="" <?php if ($bb_round == "") echo 'selected="selected"'; ?>><?php echo $text_round_no_round; ?></option>
                    <option value="0" <?php if ($bb_round == "0") echo 'selected="selected"'; ?>><?php echo $text_round_integer; ?></option>
                    <option value="1" <?php if ($bb_round == "1") echo 'selected="selected"'; ?>><?php echo $text_round_10; ?></option>
                    <option value="2" <?php if ($bb_round == "2") echo 'selected="selected"'; ?>><?php echo $text_round_100; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_allow_cod; ?></td>
            <td><input type="checkbox" name="bb_allow_cod" id="bb-allow-cod" value="1" <?php if( $bb_allow_cod == 1) { echo 'checked="checked"'; }?>/></td>
        </tr>
        <tr>
            <td><?php echo $entry_prepaid_pvz_only; ?></td>
            <td><input type="checkbox" name="bb_prepaid_pvz_only" id="bb-bb-prepaid-pvz-only" value="1" <?php if( $bb_prepaid_pvz_only == 1) { echo 'checked="checked"'; }?>/></td>
        </tr>
        <tr>
            <td><?php echo $entry_show_icons; ?></td>
            <td>
                <label><input type="radio" name="bb_show_icons" value="1" <?php echo $bb_show_icons ? 'checked="checked"' : '' ?>><?php echo $text_yes; ?></label>
                <label><input type="radio" name="bb_show_icons" value="0" <?php echo !$bb_show_icons ? 'checked="checked"' : '' ?>><?php echo $text_no; ?></label>
            </td>
        </tr>
        <tr>
            <td><?php echo $text_pvz_select_method; ?></td>
            <td>
                <select name="bb_select_pvz" id="bb-select-pvz">
                    <option value="0" <?php if ($bb_select_pvz == 0) echo 'selected="selected"'; ?>><?php echo $entry_pvz_select_map; ?></option>
                    <option value="1" <?php if ($bb_select_pvz == 1) echo 'selected="selected"'; ?>><?php echo $entry_pvz_select_list; ?></option>
                    <option value="2" <?php if ($bb_select_pvz == 2) echo 'selected="selected"'; ?>><?php echo $entry_pvz_select_both; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_country; ?></td>
            <td>
                <select name="bb_country_id" >
                    <option value="">---</option>
                    <?php foreach ($countries as $country) { ?>
                    <option value="<?php echo $country['country_id']; ?>" <?php if ($country['country_id'] == $bb_country_id) { ?>selected="selected"<?php } ?>><?php echo $country['name']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr style="background: lightcyan">
            <td><?php echo $entry_foreign; ?></td>
            <td>
                <label><input type="radio" name="bb_foreign_mode" value="1" <?php echo $bb_foreign_mode ? 'checked="checked"' : '' ?>><?php echo $text_yes; ?></label>
                <label><input type="radio" name="bb_foreign_mode" value="0" <?php echo !$bb_foreign_mode ? 'checked="checked"' : '' ?>><?php echo $text_no; ?></label>
            </td>
        </tr>
        <tr style="background: lightcyan">
            <td><?php echo $entry_currency; ?></td>
            <td><input type="text" name="bb_foreign_currency" value="<?php echo $bb_foreign_currency; ?>" size="3" /></td>
        </tr>
        <tr style="background: lightcyan">
            <td><?php echo $entry_insurance; ?></td>
            <td>
                <label><input type="radio" name="bb_foreign_insurance" value="1" <?php echo $bb_foreign_insurance ? 'checked="checked"' : '' ?>><?php echo $text_yes; ?></label>
                <label><input type="radio" name="bb_foreign_insurance" value="0" <?php echo !$bb_foreign_insurance ? 'checked="checked"' : '' ?>><?php echo $text_no; ?></label>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_tax_class; ?></td>
            <td><select name="bb_tax_class_id">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <?php if ($tax_class['tax_class_id'] == $bb_tax_class_id) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select></td>
        </tr>
        <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="bb_status">
                    <?php if ($bb_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                </select></td>
        </tr>
        <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="bb_sort_order" value="<?php echo $bb_sort_order; ?>" size="1" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_debug_mode; ?></td>
            <td>
                <label><input type="radio" name="bb_debug_mode" value="1" <?php echo $bb_debug_mode ? 'checked="checked"' : '' ?>><?php echo $text_yes; ?></label>
                <label><input type="radio" name="bb_debug_mode" value="0" <?php echo !$bb_debug_mode ? 'checked="checked"' : '' ?>><?php echo $text_no; ?></label>
            </td>
        </tr>
        <tr><td/><td><a id="btn-copy" onclick="copy_settings(); return false" data-toggle="tooltip" class="btn btn-success"><?php echo $button_copy_settings; ?></a></td></tr>
    </table>
</div>
<?php foreach ($geo_zones as $geo_zone) { ?>
<div class="tab-pane" id="tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>">
    <table class="table table-bordered">
        <tr class="zone-calc-type-row">
            <td><?php echo $entry_calc_type; ?></td>
            <td>
                <select zone="<?php echo $geo_zone['geo_zone_id']; ?>" id="bb-<?php echo $geo_zone['geo_zone_id']; ?>-calc-type" class="zone-calc-type" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_calc_type">
                    <option value="0" <?php if (${'bb_' . $geo_zone['geo_zone_id'] . '_calc_type'} == 0) echo 'selected="selected"'; ?>><?php echo $text_calc_api; ?></option>
                    <option value="1" <?php if (${'bb_' . $geo_zone['geo_zone_id'] . '_calc_type'} == 1) echo 'selected="selected"'; ?>><?php echo $text_manually; ?></option>
                </select>
                <div style="display: inline;" class="fix-rate-block" zone="<?php echo $geo_zone['geo_zone_id']; ?>">
                    <input type="text" id="bb-<?php echo $geo_zone['geo_zone_id']; ?>-fix-rate" class="zone-fix-rate" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_fix_rate" value="<?php echo ${'bb_' . $geo_zone['geo_zone_id'] . '_fix_rate'}; ?>" />
                    <span><?php echo $entry_fix_delivery_period; ?></span>
                    <input type="text" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_fix_delivery_period" value="<?php echo ${'bb_' . $geo_zone['geo_zone_id'] . '_fix_delivery_period'}; ?>" size="3"/>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_cost; ?></td>
            <td>
                <input type="text" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_rate_value" class="zone-rate-value" value="<?php echo ${'bb_' . $geo_zone['geo_zone_id'] . '_rate_value'}; ?>" />
                <select class="zone-rate-option" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_rate_option">
                    <option value="0" <?php if (${'bb_' . $geo_zone['geo_zone_id'] . '_rate_option'} == 0) echo 'selected="selected"'; ?>><?php echo $text_add_cost_type_fixed; ?></option>
                    <option value="1" <?php if (${'bb_' . $geo_zone['geo_zone_id'] . '_rate_option'} == 1) echo 'selected="selected"'; ?>><?php echo $text_add_cost_type_percent; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_shipping_type; ?></td>
            <td>
                <select class="zone-shipping-type" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_shipping_type">
                    <option value="0" <?php if (${'bb_' . $geo_zone['geo_zone_id'] . '_shipping_type'} == 0) echo 'selected="selected"'; ?>><?php echo $text_shipping_type_no_delivery; ?></option>
                    <option value="1" <?php if (${'bb_' . $geo_zone['geo_zone_id'] . '_shipping_type'} == 1) echo 'selected="selected"'; ?>><?php echo $text_shipping_type_all; ?></option>
                    <option value="2" <?php if (${'bb_' . $geo_zone['geo_zone_id'] . '_shipping_type'} == 2) echo 'selected="selected"'; ?>><?php echo $text_shipping_type_pickup; ?></option>
                    <option value="3" <?php if (${'bb_' . $geo_zone['geo_zone_id'] . '_shipping_type'} == 3) echo 'selected="selected"'; ?>><?php echo $text_shipping_type_kd; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $entry_allow_cod?></td>
            <td><input type="checkbox" class="zone-allow-cod" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_allow_cod" value="1" <?php if( ${'bb_' . $geo_zone['geo_zone_id'] . '_allow_cod'} == 1) { echo 'checked="checked"'; }?>></td>
        </tr>
        <tr>
            <td><?php echo $entry_free_ship?></td>
            <td><input class="zone-free-ship" type="text" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_free_ship" value="<?php echo ${'bb_' . $geo_zone['geo_zone_id'] . '_free_ship'}; ?>" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_kd_free_too; ?></td>
            <td><input type="checkbox" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_kd_free_too" value="1" <?php if( ${'bb_' . $geo_zone['geo_zone_id'] . '_kd_free_too'} == 1) { echo 'checked="checked"'; }?>></td>
        </tr>
        <tr>
            <td><?php echo $entry_free_total?></td>
            <td><input class="zone-free-total" type="text" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_free_total" value="<?php echo ${'bb_' . $geo_zone['geo_zone_id'] . '_free_total'}; ?>" /><?php echo $entry_free_total_to; ?><input class="zone-free-total-to" type="text" name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_free_total_to" value="<?php echo ${'bb_' . $geo_zone['geo_zone_id'] . '_free_total_to'}; ?>" /><?php echo $entry_free_total_to_hint; ?></td>
        </tr>
        <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="bb_<?php echo $geo_zone['geo_zone_id']; ?>_status">
                    <?php if (${'bb_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                </select></td>

        </tr>
    </table>
</div>
<?php } ?>
</div>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
<script type="text/javascript"><!--

    function copy_settings() {
        var calc_type = $('#bb-calc-type').val();
        var fix_rate = $('#bb-fix-rate').val();
        var rate_val = $('#bb-rate-value').val();
        var rate_option = $('#bb-rate-option').val();
        var shipping_type = $('#bb-shipping-type').val();
        var free_ship = $('#bb-free-ship').val();
        var free_total = $('#bb-free-total').val();
        var free_total_to = $('#bb-free-total-to').val();
        var cod = $('#bb-allow-cod').prop('checked');
        $('.zone-calc-type').each(function() {
            $(this).val(calc_type);
        }).trigger('change');
        $('.zone-fix-rate').each(function() {
            $(this).val(fix_rate);
        });
        $('.zone-rate-value').each(function() {
            $(this).val(rate_val);
        });
        $('.zone-rate-option').each(function() {
            $(this).val(rate_option);
        });
        $('.zone-shipping-type').each(function() {
            $(this).val(shipping_type);
        });
        $('.zone-allow-cod').each(function() {
            $(this).prop('checked', cod);
        });
        $('.zone-free-ship').each(function() {
            $(this).val(free_ship);
        });
        $('.zone-free-total').each(function() {
            $(this).val(free_total);
        });
        $('.zone-free-total-to').each(function() {
            $(this).val(free_total_to);
        });
    }
    function checkVisibility() {
        if ($('#bb-calc-type').val() != 1) $('.fix-rate-block-main').hide(); else $('.fix-rate-block-main').show();
        if ($('#bb-calc-type').val() != 2) {
            $('#table-tariff-zones').hide();
            $('.zone-calc-type-row').show();
        } else  {
            $('#table-tariff-zones').show();
            $('.zone-calc-type-row').hide();
        }
    <?php if (empty($geo_zones)) echo '$("#btn-copy").hide();' ?>
    }
    $(function() {
        gen_license_id();
        checkVisibility();
        $('.zone-calc-type').each(function() {
            var zone = $(this).attr('zone');
            var ed = ($('.fix-rate-block[zone="'+zone+'"]'));
            if ($(this).val() == 0) ed.hide(); else ed.show();
        });
    });
    $('#bb-calc-type').change(function() {
        checkVisibility();
    });
    $('.zone-calc-type').change(function(event) {
        var zone = $(this).attr('zone');
        var ed = ($('.fix-rate-block[zone="'+zone+'"]'));
        if ($(this).val() == 0) {
            ed.hide();
        }
        else {
            ed.show();
        }
    });
    function gen_license_id() {
        $.ajax({
            type:  'POST',
            cache:  false ,
            dataType: 'json',
            url:  'index.php?route=<?php echo $route; ?>/license&token=<?php echo $token; ?>',
            data:  { 'license_name' : $("#license_name").val()},
            success: function(json) {
                $("#license_id").val(json.id);
                $("#license_name").val(json.name);
            }
        });
    }
    //--></script>
<?php echo $footer; ?>