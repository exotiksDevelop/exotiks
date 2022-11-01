<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
    <div class="container-fluid">
        <div class="pull-right">
            <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_apply; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
            <a onclick="$('#form').attr('action', location + '&exit=true'); $('#form').submit()" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-default"><i class="fa fa-save"></i></a>
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
<div class="panel panel-default">
<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
</div>
<div class="panel-body">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
<ul class="nav nav-tabs">
    <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
    <li><a href="#tab-data" data-toggle="tab"><?php echo $text_methods; ?></a></li>
    <li><a href="#tab-discount" data-toggle="tab"><?php echo $tab_discount; ?></a></li>
    <?php if ($show_product_groups and isset($groups) and count($groups) > 0) { ?>
    <li><a href="#tab-pgroups" data-toggle="tab"><?php echo $tab_product_groups; ?></a></li>
    <?php } ?>
    <li><a href="#tab-zipcode" data-toggle="tab"><?php echo $tab_zipcode; ?></a></li>
    <li><a href="#tab-bubble" data-toggle="tab"><?php echo $tab_bubble; ?></a></li>
    <li><a href="#tab-backup" data-toggle="tab"><?php echo $tab_backup; ?></a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="tab-general">

        <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_name; ?>"><?php echo $entry_name; ?></span></label>
            <div class="col-sm-10">
                <?php foreach ($languages as $language) {
                    if ($language['status'] == 1) {
                ?>
                <div class="input-group pull-left"><span class="input-group-addon"><img src="<?php if (version_compare(VERSION, '2.2.0.0', '<')) { ?>view/image/flags/<?php echo $language['image']; ?><?php } else { ?>language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png<?php } ?>" title="<?php echo $language['name']; ?>" /> </span>
                <input size="50" type="text" name="<?php echo $name; ?>_name[<?php echo $language['language_id']; ?>]" value="<?php echo isset($pochtaros_name[$language['language_id']]) ? $pochtaros_name[$language['language_id']] : ''; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" /></div>
                <?php }
                } ?>
            </div>
        </div>

        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-store"><?php echo $entry_store; ?></label>

            <div class="col-sm-10">
              <div class="well well-sm" style="height: 100px; overflow: auto;">
                <?php foreach ($stores as $store) { ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="<?php echo $name; ?>_store[]" value="<?php echo $store['store_id']; ?>" <?php if (isset($pochtaros_store) and in_array($store['store_id'], $pochtaros_store)) { ?>checked="checked"<?php } ?> />
                        <?php echo $store['name']; ?>
                    </label>
                </div>
                <?php } ?>
              </div>
              <?php if (isset($error_store) and !empty($error_store)) { ?>
                <div class="text-danger"><?php echo $error_store; ?></div>
              <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-city"><span data-toggle="tooltip" title="<?php echo $help_city; ?>"><?php echo $entry_city; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="<?php echo $name; ?>_city" value="<?php if (isset($pochtaros_city)) echo $pochtaros_city; ?>" id="input-city" class="form-control" />
                <?php if (isset($error_city) and !empty($error_city)) { ?>
                <div class="text-danger"><?php echo $error_city; ?></div>
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $name; ?>_zone_id" id="input-zone" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php
                        foreach ($zones as $zone) {
                            if ($zone['status'] == 1) {
                                if ($zone['zone_id'] == $pochtaros_zone_id) { ?>
                    <option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
                    <?php
                                }
                                else {
                                ?>
                    <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
                    <?php
                                }
                                ?>
                    <?php
                            }
                        }
                        ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $name; ?>_weight_class_id" id="input-weight-class" class="form-control">
                    <?php foreach ($weight_classes as $weight_class) { ?>
                        <?php if (isset($pochtaros_weight_class_id) and $weight_class['weight_class_id'] == $pochtaros_weight_class_id) { ?>
                            <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mid-weight"><span data-toggle="tooltip" title="<?php echo $help_mid_weight; ?>"><?php echo $entry_mid_weight; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="<?php echo $name; ?>_mid_weight" value="<?php if (isset($pochtaros_mid_weight)) echo $pochtaros_mid_weight; ?>" id="input-mid-weight" class="form-control" />
                <?php if (isset($error_mid_weight) and !empty($error_mid_weight)) { ?>
                <div class="text-danger"><?php echo $error_mid_weight; ?></div>
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pmid-weight"><span data-toggle="tooltip" title="<?php echo $help_pmid_weight; ?>"><?php echo $entry_pmid_weight; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="<?php echo $name; ?>_pmid_weight" value="<?php if (isset($pochtaros_pmid_weight)) echo $pochtaros_pmid_weight; ?>" id="input-pmid-weight" class="form-control" />
                <?php if (isset($error_pmid_weight) and !empty($error_pmid_weight)) { ?>
                <div class="text-danger"><?php echo $error_pmid_weight; ?></div>
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-min-weight"><span data-toggle="tooltip" title="<?php echo $help_min_weight; ?>"><?php echo $entry_min_weight; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="<?php echo $name; ?>_min_weight" value="<?php if (isset($pochtaros_min_weight)) echo $pochtaros_min_weight; ?>" id="input-min-weight" class="form-control" />
                <?php if (isset($error_min_weight) and !empty($error_min_weight)) { ?>
                <div class="text-danger"><?php echo $error_min_weight; ?></div>
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-max-weight"><span data-toggle="tooltip" title="<?php echo $help_max_weight; ?>"><?php echo $entry_max_weight; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="<?php echo $name; ?>_max_weight" value="<?php if (isset($pochtaros_max_weight)) echo $pochtaros_max_weight; ?>" id="input-max-weight" class="form-control" />
                <?php if (isset($error_max_weight) and !empty($error_max_weight)) { ?>
                <div class="text-danger"><?php echo $error_max_weight; ?></div>
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cost"><span data-toggle="tooltip" title="<?php echo $help_cost; ?>"><?php echo $entry_cost; ?></span></label>
            <div class="col-sm-4">
                <input type="text" name="<?php echo $name; ?>_cost" value="<?php if (isset($pochtaros_cost)) echo $pochtaros_cost; ?>" id="input-cost" class="form-control" />
                <?php if (isset($error_cost) and !empty($error_cost)) { ?>
                <div class="text-danger"><?php echo $error_cost; ?></div>
                <?php } ?>
            </div>
            <div class="col-sm-4">
                <select name="<?php echo $name; ?>_cost_type" class="form-control"><option value="+" <?php if (isset($pochtaros_cost_type) and $pochtaros_cost_type == '+') { ?>selected<?php } ?> ><?php echo $text_chislo; ?></option><option value="%" <?php if (isset($pochtaros_cost_type) and $pochtaros_cost_type == '%') { ?>selected<?php } ?>>%</option></select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="<?php echo $name; ?>_total" value="<?php if (isset($pochtaros_total)) echo $pochtaros_total; ?>" id="input-total" class="form-control" />
                <?php if (isset($error_total) and !empty($error_total)) { ?>
                <div class="text-danger"><?php echo $error_total; ?></div>
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-max-total"><span data-toggle="tooltip" title="<?php echo $help_max_total; ?>"><?php echo $entry_max_total; ?></span></label>
            <div class="col-sm-10">
                <input type="text" name="<?php echo $name; ?>_max_total" value="<?php if (isset($pochtaros_max_total)) echo $pochtaros_max_total; ?>" id="input-max-total" class="form-control" />
                <?php if (isset($error_max_total) and !empty($error_max_total)) { ?>
                <div class="text-danger"><?php echo $error_max_total; ?></div>
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-upakovka"><span data-toggle="tooltip" title="<?php echo $help_upakovka; ?>"><?php echo $entry_upakovka; ?></span></label>
            <div class="col-sm-4">
                <input type="text" name="<?php echo $name; ?>_upakovka" value="<?php if (isset($pochtaros_upakovka)) echo $pochtaros_upakovka; ?>" id="input-upakovka" class="form-control" />
                <?php if (isset($error_upakovka) and !empty($error_upakovka)) { ?>
                <div class="text-danger"><?php echo $error_upakovka; ?></div>
                <?php } ?>
            </div>

            <div class="col-sm-4">
                <select name="<?php echo $name; ?>_upakovka_type" class="form-control"><option value="+" <?php if (isset($pochtaros_upakovka_type) and $pochtaros_upakovka_type == '+') { ?>selected<?php } ?> ><?php echo $text_chislo; ?></option><option value="%" <?php if (isset($pochtaros_upakovka_type) and $pochtaros_upakovka_type == '%') { ?>selected<?php } ?>>%</option></select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
            <div class="col-sm-10">
                <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                <input type="hidden" name="<?php echo $name; ?>_image" value="<?php echo $image; ?>" id="input-image" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_time; ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $name; ?>_time" class="form-control">
                    <?php foreach ($arr_show as $key => $val) { ?>
                    <option value="<?php echo $key;?>" <?php if (isset($pochtaros_time) and $pochtaros_time == $key) echo 'selected="selected"'; ?> ><?php echo ${'text_show_'.$val}; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_time_more; ?></label>
            <div class="col-sm-10"><input type="text" name="<?php echo $name; ?>_time_more" value="<?php if (isset($pochtaros_time_more)) echo $pochtaros_time_more; ?>"  class="form-control"/>
                <?php if ($error_time_more) { ?>
                <span class="error"><?php echo $error_time_more; ?></span>
                <?php } ?>
            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_fragmentation; ?>"><?php echo $entry_fragmentation; ?></span></label>
            <div class="col-sm-10">
                <label class="checkbox-inline">
                    <input type="checkbox" name="<?php echo $name; ?>_fragmentation" value="1" <?php if (isset($pochtaros_fragmentation) and $pochtaros_fragmentation) { ?>checked="checked"<?php } ?> />
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_zaglushka; ?>"><?php echo $entry_zaglushka; ?></span></label>
            <div class="col-sm-10">
                <label class="checkbox-inline">
                    <input type="checkbox" name="<?php echo $name; ?>_zaglushka" id="<?php echo $name; ?>_zaglushka" value="1" <?php if (isset($pochtaros_zaglushka) and $pochtaros_zaglushka) { ?>checked="checked"<?php } ?> />
                </label>
            </div>
        </div>

        <div class="form-group slider-content hidden">
            <label class="col-sm-2 control-label"><?php echo $entry_zaglushka_text; ?></label>
            <div class="col-sm-10">
                <?php foreach ($languages as $language) {
                    if ($language['status'] == 1) {
                ?>
                <div class="input-group pull-left"><span class="input-group-addon"><img src="<?php if (version_compare(VERSION, '2.2.0.0', '<'))  { ?>view/image/flags/<?php echo $language['image']; ?><?php } else { ?>language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png<?php } ?>" title="<?php echo $language['name']; ?>" /> </span>
                    <input size="50" type="text" name="<?php echo $name; ?>_bibbtext[<?php echo $language['language_id']; ?>]" value="<?php echo isset($pochtaros_bibbtext[$language['language_id']]) ? $pochtaros_bibbtext[$language['language_id']] : ''; ?>" id="input-bibbtext<?php echo $language['language_id']; ?>" class="form-control" placeholder="<? if ($language['language_id'] == $config_language_id) echo $text_bibb; ?>" /></div>
                <?php }
                } ?>
            </div>
        </div>

        <div class="form-group slider-content hidden">
            <label class="col-sm-2 control-label"><?php echo $entry_zaglushka_type; ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $name; ?>_zaglushka_type" class="form-control">
                    <?php if ($pochtaros_zaglushka_type == 1) { ?>
                    <option value="0"><?php echo $text_zaglushka_type_0; ?></option>
                    <option value="1" selected="selected"><?php echo $text_zaglushka_type_1; ?></option>
                    <?php } else { ?>
                    <option value="0" selected="selected"><?php echo $text_zaglushka_type_0; ?></option>
                    <option value="1"><?php echo $text_zaglushka_type_1; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total-value"><?php echo $entry_value_for_total; ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $name; ?>_total_value" id="input-total-value" class="form-control">
                    <?php
                    $total_value = (isset($pochtaros_total_value)) ? $pochtaros_total_value : 'sub_total';
                    foreach ($totals as $key => $val) {
                    ?>
                    <option value="<?php echo $key;?>" <?php if ($total_value == $key) echo 'selected="selected"'; ?> ><?php echo $val; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-round"><?php echo $entry_round; ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $name; ?>_round" id="input-round" class="form-control">
                    <option value=""><?php echo $text_noround; ?></option>
                    <option value="digit1" <?php if ($pochtaros_round == 'digit1') echo 'selected="selected"'; ?> ><?php echo $text_digit1; ?></option>
                    <option value="digit1_plus" <?php if ($pochtaros_round == 'digit1_plus') echo 'selected="selected"'; ?>><?php echo $text_digit1_plus; ?></option>
                    <option value="digit9" <?php if ($pochtaros_round == 'digit9') echo 'selected="selected"'; ?>><?php echo $text_digit9; ?></option>
                    <option value="digit10" <?php if ($pochtaros_round == 'digit10') echo 'selected="selected"'; ?>><?php echo $text_digit10; ?></option>
                    <option value="digit50" <?php if ($pochtaros_round == 'digit50') echo 'selected="selected"'; ?>><?php echo $text_digit50; ?></option>
                    <option value="digit100" <?php if ($pochtaros_round == 'digit100') echo 'selected="selected"'; ?>><?php echo $text_digit100; ?></option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-procent-price"><?php echo $entry_procent_price; ?></label>
            <div class="col-sm-4">
                <input type="text" name="<?php echo $name; ?>_procent_price" value="<?php echo $pochtaros_procent_price; ?>" placeholder="100" id="input-procent-price" class="form-control"/>
            </div>
            <div class="col-sm-4">%</div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_nalozhka; ?></label>
            <div class="col-sm-10"><input type="text" name="<?php echo $name; ?>_nalozhka" value="<?php if (isset($pochtaros_nalozhka)) echo $pochtaros_nalozhka; ?>" class="form-control"/>
                <?php if ($error_nalozhka) { ?>
                <span class="error"><?php echo $error_nalozhka; ?></span>
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_nalozhka2; ?></label>
            <div class="col-sm-10"><select name="<?php echo $name; ?>_nalozhka2" class="form-control">
                    <?php foreach ($arr_show as $key => $val) { ?>
                    <option value="<?php echo $key;?>" <?php if (isset($pochtaros_nalozhka2) and $pochtaros_nalozhka2 == $key) echo 'selected="selected"'; ?> ><?php echo ${'text_show_'.$val}; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total-value"><?php echo $entry_payment; ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $name; ?>_payment" id="input-payment" class="form-control">
                    <option value=""><?php echo $text_none; ?></option>
                    <?php
                    $payment = (isset($pochtaros_payment)) ? $pochtaros_payment : 'sub_total';
                    foreach ($payments as $key => $val) {
                    ?>
                    <option value="<?php echo $val['payment_code'];?>" <?php if ($payment == $val['payment_code']) echo 'selected="selected"'; ?> ><?php echo $val['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-nalozh-size"><?php echo $entry_show_nalozh_size; ?></label>
            <div class="col-sm-10">
                <label class="checkbox-inline">
                    <input type="checkbox" name="<?php echo $name; ?>_show_nalozh_size" value="1" <?php if (isset($pochtaros_show_nalozh_size) and $pochtaros_show_nalozh_size) { ?>checked="checked"<?php } ?> /></td>
                </label>
            </div>
        </div>

        <!--div class="form-group">
            <label class="col-sm-2 control-label" for="input-another-server"><?php echo $entry_another_server; ?></label>
            <div class="col-sm-10">
                <label class="checkbox-inline">
                    <input type="checkbox" name="<?php echo $name; ?>_another_server" value="1" <?php if (isset($pochtaros_another_server) and $pochtaros_another_server) { ?>checked="checked"<?php } ?> /></td>
                </label>
            </div>
        </div -->

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-zero-price"><?php echo $entry_zero_price; ?></label>
            <div class="col-sm-10">
                <label class="checkbox-inline">
                    <input type="checkbox" name="<?php echo $name; ?>_zero_price" value="1" <?php if (isset($pochtaros_zero_price) and $pochtaros_zero_price) { ?>checked="checked"<?php } ?> /></td>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-zaglushka-vniz"><?php echo $entry_zaglushka_vniz; ?></label>
            <div class="col-sm-10">
                <label class="checkbox-inline">
                    <input type="checkbox" name="<?php echo $name; ?>_zaglushka_vniz" value="1" <?php if (isset($pochtaros_zaglushka_vniz) and $pochtaros_zaglushka_vniz) { ?>checked="checked"<?php } ?> /></td>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-zaglushka-vniz"><?php echo $entry_corp_tarif; ?></label>
            <div class="col-sm-10">
                <label class="checkbox-inline">
                    <input type="checkbox" name="<?php echo $name; ?>_corp_tarif" value="1" <?php if (isset($pochtaros_corp_tarif) and $pochtaros_corp_tarif) { ?>checked="checked"<?php } ?> /></td>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax"><?php echo $entry_tax_class; ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $name; ?>_tax_class_id" id="input-tax" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <?php if ($tax_class['tax_class_id'] == ${$name.'_tax_class_id'}) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-4">
                <input type="text" name="<?php echo $name; ?>_sort_order" value="<?php echo $pochtaros_sort_order; ?>" placeholder="1" id="input-sort-order" class="form-control" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $name; ?>_status" id="input-status" class="form-control">
                    <?php if ($pochtaros_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="tab-data">
        <div class="row">
            <div class="col-sm-2">
                <ul class="nav nav-pills nav-stacked" id="methods">
                    <?php $i=1;
					foreach ($methods as $method) {
						$is_method_on = false;
						foreach ($languages as $language) {
							if ($language['status'] == 1) {
								if (isset($pochtaros_mstatus[$method['key']][$language['language_id']]) and $pochtaros_mstatus[$method['key']][$language['language_id']]) {
									$is_method_on = true;
									break;
								}
							}
						}
						?>
						<li <?php if ($i == 1) echo 'class="active"';?> ><a href="#tab-method-<?php echo $method['key']; ?>" data-toggle="tab" <?php if (!$is_method_on) echo "style=\"color:#000000;\""; ?>><i class="fa" onclick="$('a[href=\'#tab-method-<?php echo $method['key']; ?>\']').parent().remove(); $('#method a:first').tab('show');"></i> <?php if (isset($pochtaros_title_tab[$method['key']]) and !empty($pochtaros_title_tab[$method['key']])) echo $pochtaros_title_tab[$method['key']]; else echo $method['title']; ?></a></li>
                    <?php 
					$i++;
					} ?>
                </ul>
            </div>
            <div class="col-sm-10">
                <div class="tab-content">
                    <?php $i=1;
					foreach ($methods as $method) { ?>
                    <div class="tab-pane <?php if ($i == 1) echo 'active';?>" id="tab-method-<?php echo $method['key']; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-title-tab"><?php echo $entry_title_tab; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_title_tab[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_title_tab[$method['key']])) echo $pochtaros_title_tab[$method['key']]; ?>"" id="input-min-delivery" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-min-delivery"><?php echo $entry_min_delivery; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_min_delivery[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_min_delivery[$method['key']])) echo $pochtaros_min_delivery[$method['key']]; ?>"" id="input-min-delivery" class="form-control" />
                                <?php if (isset($errorr_min_delivery[$method['key']]) and !empty($errorr_min_delivery[$method['key']])) { ?>
                                <div class="text-danger"><?php echo $error_min_delivery[$method['key']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_price[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_price[$method['key']])) echo $pochtaros_price[$method['key']]; ?>" id="input-price" class="form-control" />
                                <?php if (isset($error_price[$method['key']]) and !empty($error_price[$method['key']])) { ?>
                                <div class="text-danger"><?php echo error_number; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-min-products"><?php echo $entry_min_products; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_min_products[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_min_products[$method['key']])) echo $pochtaros_min_products[$method['key']]; ?>" id="input-min-products" class="form-control" />
                                <?php if (isset($error_min_products[$method['key']]) and !empty($error_min_products[$method['key']])) { ?>
                                <div class="text-danger"><?php echo $error_min_products[$method['key']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-max-products"><?php echo $entry_max_products; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_max_products[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_max_products[$method['key']])) echo $pochtaros_max_products[$method['key']]; ?>" id="input-max-products" class="form-control" />
                                <?php if (isset($error_max_products[$method['key']]) and !empty($error_max_products[$method['key']])) { ?>
                                <div class="text-danger"><?php echo $error_max_products[$method['key']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-min-order"><?php echo $entry_min_order; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_min_order[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_min_order[$method['key']])) echo $pochtaros_min_order[$method['key']]; ?>" id="input-min-order" class="form-control" />
                                <?php if (isset($error_min_order[$method['key']]) and !empty($error_min_order[$method['key']])) { ?>
                                <div class="text-danger"><?php echo $error_min_order[$method['key']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-max-order"><?php echo $entry_max_order; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_max_order[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_max_order[$method['key']])) echo $pochtaros_max_order[$method['key']]; ?>" id="input-max-order" class="form-control" />
                                <?php if (isset($error_max_order[$method['key']]) and !empty($error_max_order[$method['key']])) { ?>
                                <div class="text-danger"><?php echo $error_max_order[$method['key']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-min-weight2"><?php echo $entry_min_weight2; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_min_weight2[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_min_weight2[$method['key']])) echo $pochtaros_min_weight2[$method['key']]; ?>" id="input-min-weight2" class="form-control" />
                                <?php if (isset($error_min_weight2[$method['key']]) and !empty($error_min_weight2[$method['key']])) { ?>
                                <div class="text-danger"><?php echo $error_min_weight2[$method['key']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-max-weight2"><?php echo $entry_max_weight2; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_max_weight2[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_max_weight2[$method['key']])) echo $pochtaros_max_weight2[$method['key']]; ?>" id="input-max-weight2" class="form-control" />
                                <?php if (isset($error_max_weight2[$method['key']]) and !empty($error_max_weight2[$method['key']])) { ?>
                                <div class="text-danger"><?php echo $error_max_weight2[$method['key']]; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>

                            <div class="col-sm-10">
                                <div class="well well-sm" style="height: 100px; overflow: auto;">
                                    <?php foreach ($geo_zones as $geo_zone) { ?>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="<?php echo $name; ?>_geo_zone[<?php echo $method['key'];?>][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php if (isset($pochtaros_geo_zone[$method['key']]) and in_array($geo_zone['geo_zone_id'], $pochtaros_geo_zone[$method['key']])) { ?>checked="checked"<?php } ?> />
                                            <?php echo $geo_zone['name']; ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_customer_group; ?></label>

                            <div class="col-sm-10">
                                <div class="well well-sm" style="height: 100px; overflow: auto;">
                                    <?php foreach ($customer_groups as $customer_group) {  ?>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="<?php echo $name; ?>_customer_group[<?php echo $method['key'];?>][]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php if (isset($pochtaros_customer_group[$method['key']]) and in_array($customer_group['customer_group_id'], $pochtaros_customer_group[$method['key']])) { ?>checked="checked"<?php } ?>  />
                                            <?php echo $customer_group['name']; ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-incity"><span data-toggle="tooltip" title="<?php echo $help_incity; ?>"><?php echo $entry_incity; ?></span></label>
                            <div class="col-sm-10">
                                <textarea name="<?php echo $name; ?>_incity[<?php echo $method['key'];?>]" rows="5" id="input-incity" class="form-control"><?php if (isset($pochtaros_incity[$method['key']])) echo $pochtaros_incity[$method['key']]; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-outcity"><span data-toggle="tooltip" title="<?php echo $help_outcity; ?>"><?php echo $entry_outcity; ?></span></label>
                            <div class="col-sm-10">
                                <textarea name="<?php echo $name; ?>_outcity[<?php echo $method['key'];?>]" rows="5" id="input-outcity" class="form-control"><?php if (isset($pochtaros_outcity[$method['key']])) echo $pochtaros_outcity[$method['key']]; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-description"><span data-toggle="tooltip" title="<?php echo $help_description; ?>"><?php echo $entry_description; ?></span></label>
                            <div class="col-sm-10">
                                <textarea name="<?php echo $name; ?>_description[<?php echo $method['key'];?>]" rows="5" id="input-description" class="form-control"><?php if (isset($pochtaros_description[$method['key']])) echo $pochtaros_description[$method['key']]; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_show_description; ?></label>
                            <div class="col-sm-10">
                                <input type="checkbox" name="<?php echo $name; ?>_show_description[<?php echo $method['key'];?>]" value="1" class="form-control" <?php  if (isset($pochtaros_show_description[$method['key']]) and $pochtaros_show_description[$method['key']] == 1)  { ?>checked="checked"<?php } ?> />
                            </div>
                        </div>



                        <?php if (isset($show_product_groups) and $show_product_groups == true) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="product-group-<?php echo $method['key'];?>"><?php echo $entry_group; ?></label>
                            <div class="col-sm-10">
                                <select name="<?php echo $name; ?>_product_group[<?php echo $method['key'];?>]" id="product-group-<?php echo $method['key'];?>" class="form-control">
                                    <option value="0" selected="selected"><?php echo $text_group; ?></option>
                                    <?php
                                                foreach ($groups as $group) {
                                                    if ($group['status'] == 1) {
                                                    ?>
                                    <option value="<?php echo $group['group_id']; ?>" <?php if (isset($pochtaros_product_group[$method['key']]) and ($pochtaros_product_group[$method['key']] == $group['group_id'])) { echo "selected"; } ?>><?php echo $group['name']; ?></option>
                                    <?php
                                                    }
                                                }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php }  ?>


                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-msort-order"><?php echo $entry_sort_order; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="<?php echo $name; ?>_msort_order[<?php echo $method['key'];?>]" value="<?php if (isset($pochtaros_msort_order[$method['key']])) echo $pochtaros_msort_order[$method['key']]; else echo 1; ?>" id="input-min-delivery" class="form-control" />
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                            <div class="col-sm-10">
                                <?php
                                foreach ($languages as $language) {
                                    if ($language['status'] == 1) {
                                    ?>
                                    <div class="input-group pull-left"><span class="input-group-addon"><img src="<?php if (version_compare(VERSION, '2.2.0.0', '<'))  { ?>view/image/flags/<?php echo $language['image']; ?><?php } else { ?>language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png<?php } ?>" title="<?php echo $language['name']; ?>" /> </span>
                                    <select name="<?php echo $name; ?>_mstatus[<?php echo $method['key'];?>][<?php echo $language['language_id']; ?>]" class="form-control">
                                    <?php if ($pochtaros_mstatus[$method['key']][$language['language_id']]) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php }
                                            else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select></div>
                                    <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php $i++;
					} ?>
                </div>
            </div>
        </div>
    </div>


    <div class="tab-pane" id="tab-discount">
        <table id="discount" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td class="text-left"><span data-toggle="tooltip" title="<?php echo $help_discount_value; ?>"><?php echo $column_discount_value; ?></span></td>
                    <td class="text-left"><?php echo $column_method; ?></td>
                    <td class="text-left"><?php echo $column_customer_group; ?></td>
                    <td class="text-left"><?php echo $column_geo_zone; ?></td>
                    <td class="text-left"><?php echo $column_min_cost; ?></td>
                    <td class="text-left"><?php echo $column_max_cost; ?></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php $discount_row = 0; ?>
            <?php if (count($pochtaros_discounts) > 0) { ?>
            <?php foreach ($pochtaros_discounts as $discount_row => $discount) { ?>
            <tr id="discount-row<?php echo $discount_row; ?>">
                <td class="text-left">
                    <nobr>
                        <select name="<?php echo $name; ?>_discounts[<?php echo $discount_row; ?>][prefix]" class="form-control">
                            <?php foreach (array('-', '+') as $prefix) { ?>
                            <option <?php if ($prefix == $discount['prefix']) echo 'selected="selected"'; ?> value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
                            <?php } ?>
                        </select>
                        <input type="text" name="<?php echo $name; ?>_discounts[<?php echo $discount_row; ?>][value]" value="<?php echo $discount['value']; ?>" size="3" class="form-control"/>
                        <select name="<?php echo $name; ?>_discounts[<?php echo $discount_row; ?>][mode]" class="form-control">
                            <?php foreach ($discount_type as $type => $tname) { ?>
                            <option <?php if ($type == $discount['mode']) echo 'selected="selected"'; ?> value="<?php echo $type; ?>"><?php echo $tname; ?></option>
                            <?php } ?>
                        </select>
                    </nobr>
                    <?php if (isset($error_pochtaros_discounts[$discount_row]['value'])) { ?>
                    <span class="error"><?php echo $error_pochtaros_discounts[$discount_row]['value']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <div class="well well-sm" style="height: 100px; overflow: auto;">
                        <?php foreach ($on_methods as $method) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="<?php echo $name; ?>_discounts[<?php echo $discount_row; ?>][key][]" value="<?php echo $method['key']; ?>" <?php  if (!empty($discount['key']) && is_array($discount['key']) && in_array($method['key'], $discount['key'])) echo 'checked="checked"'; ?> />
                                <?php if (isset($pochtaros_title_tab[$method['key']]) and !empty($pochtaros_title_tab[$method['key']])) echo $pochtaros_title_tab[$method['key']]; else echo $method['title']; ?>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                </td>
                <td class="text-left">
                    <div class="well well-sm" style="height: 100px; overflow: auto;">
                        <?php foreach ($customer_groups as $customer_group) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="<?php echo $name; ?>_discounts[<?php echo $discount_row; ?>][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php  if (!empty($discount['customer_group_id']) && is_array($discount['customer_group_id']) && in_array($customer_group['customer_group_id'], $discount['customer_group_id'])) echo 'checked="checked"'; ?> />
                                <?php echo $customer_group['name']; ?>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                </td>
                <td class="text-left">
                    <div class="well well-sm" style="height: 100px; overflow: auto;">
                        <?php foreach ($geo_zones as $geo_zone) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="<?php echo $name; ?>_discounts[<?php echo $discount_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php  if (isset($discount['geo_zone']) && in_array($geo_zone['geo_zone_id'], $discount['geo_zone'])) echo 'checked="checked"'; ?> />
                                <?php echo $geo_zone['name']; ?>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_discounts[<?php echo $discount_row; ?>][min_total]" value="<?php if (isset($discount['min_total'])) echo $discount['min_total']; ?>" size="3" class="form-control"/>
                    <?php if (isset($error_pochtaros_discounts[$discount_row]['min_total'])) { ?>
                    <span class="error"><?php echo $error_pochtaros_discounts[$discount_row]['min_total']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_discounts[<?php echo $discount_row; ?>][max_total]" value="<?php if (isset($discount['max_total'])) echo $discount['max_total']; ?>" size="3" class="form-control"/>
                    <?php if (isset($error_pochtaros_discounts[$discount_row]['max_total'])) { ?>
                    <span class="error"><?php echo $error_pochtaros_discounts[$discount_row]['max_total']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left"><button type="button" onclick="$('#discount-row<?php echo $discount_row; ?>').remove();return false;" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
            <?php } ?>
            <?php $discount_row++; ?>
            <?php } ?>
            </tbody>
        <tfoot>
        <tr>
            <td colspan="6"></td>
            <td class="text-left"><button type="button" onclick="addDiscount();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
            </td>
        </tr>
        </tfoot>
        </table>

    </div>


    <?php if ($show_product_groups and isset($groups) and count($groups) > 0) { ?>
    <div class="tab-pane" id="tab-pgroups">
        <table id="pgroup" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <td class="text-left"><?php echo $column_method; ?></td>
                <td class="text-left"><?php echo $column_group; ?></td>
                <td class="text-left"><?php echo $column_group_logic; ?></td>
                <td class="text-left"><?php echo $column_min_total; ?></td>
                <td class="text-left"><?php echo $column_max_total; ?></td>
                <td></td>
            </tr>
            </thead>

            <tbody>
            <?php $pgroup_row = 0; ?>
            <?php if (is_array($pochtaros_pgroups) and count($pochtaros_pgroups) > 0) { ?>
            <?php foreach ($pochtaros_pgroups as $pgroup_row => $pgroup) { ?>
            <tr id="pgroup-row<?php echo $pgroup_row; ?>">
                <td class="text-left">
                    <div class="well well-sm" style="height: 100px; overflow: auto;">
                        <?php foreach ($on_methods as $method) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="<?php echo $name; ?>_pgroups[<?php echo $pgroup_row; ?>][key][]" value="<?php echo $method['key']; ?>" <?php  if (!empty($pgroup['key']) && is_array($pgroup['key']) && in_array($method['key'], $pgroup['key'])) echo 'checked="checked"'; ?> />
                                <?php if (isset($pochtaros_title_tab[$method['key']]) and !empty($pochtaros_title_tab[$method['key']])) echo $pochtaros_title_tab[$method['key']]; else echo $method['title']; ?>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                </td>

                <td class="text-left">
                    <select name="<?php echo $name; ?>_pgroups[<?php echo $pgroup_row; ?>][filter_group_id]" class="form-control"/>
                        <option value="0" selected="selected"><?php echo $text_group; ?></option>
                        <?php
                            foreach ($groups as $group) {
                                if ($group['status'] == 1) {
                                ?>
                            <option value="<?php echo $group['group_id']; ?>" <?php if ($group['group_id'] == $pgroup['filter_group_id']) { echo "selected"; } ?>><?php echo $group['name']; ?></option>
                            <?php
                                }
                            }
                            ?>
                    </select>
                    <?php if (isset($error_pgroups[$pgroup_row]['filter_group_id'])) { ?>
                    <span class="error"><?php echo $error_pgroups[$pgroup_row]['filter_group_id']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <select name="<?php echo $name; ?>_pgroups[<?php echo $pgroup_row; ?>][logic]" id="<?php echo $name; ?>_pgroups_<?php echo $pgroup_row; ?>_logic" class="form-control"/>
                        <?php foreach ($group_logic_type as $type => $tname) { ?>
                        <option <?php if ($type == $pgroup['logic']) echo 'selected="selected"'; ?> value="<?php echo $type; ?>"><?php echo $tname; ?></option>
                        <?php } ?>
                    </select>
                    <input type="text" name="<?php echo $name; ?>_pgroups[<?php echo $pgroup_row; ?>][limit]" id="<?php echo $name; ?>_pgroups_<?php echo $pgroup_row; ?>_limit" value="<?php if (isset($pgroup['limit'])) echo $pgroup['limit']; ?>" size="7" class="form-control"/>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_pgroups[<?php echo $pgroup_row; ?>][min_total]" value="<?php echo $pgroup['min_total']; ?>" size="3" class="form-control"/>
                    <?php if (isset($error_pgroups[$pgroup_row]['min_total'])) { ?>
                    <span class="error"><?php echo $error_pgroups[$pgroup_row]['min_total']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_pgroups[<?php echo $pgroup_row; ?>][max_total]" value="<?php echo $pgroup['max_total']; ?>" size="3" class="form-control"/>
                    <?php if (isset($error_pgroups[$pgroup_row]['max_total'])) { ?>
                    <span class="error"><?php echo $error_pgroups[$pgroup_row]['max_total']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left"><button type="button" onclick="$('#pgroup-row<?php echo $pgroup_row; ?>').remove();return false;" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
            <?php } ?>
            <?php $pgroup_row++; ?>
            <?php } ?>
            </tbody>

            <tfoot>
            <tr>
                <td colspan="5"></td>
                <td class="text-left"><button type="button" onclick="addPGroup();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                </td>
            </tr>
            </tfoot>

        </table>
    </div>
    <?php } ?>

    <div class="tab-pane" id="tab-zipcode" >
        <table id="zipcode" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <td class="text-left"><?php echo $column_inzip; ?></td>
                <td class="text-left"><?php echo $column_outzip; ?></td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <?php $zipcode_row = 0; ?>
            <?php if (is_array($pochtaros_zipcode) and count($pochtaros_zipcode) > 0) { ?>
            <?php foreach ($pochtaros_zipcode as $zipcode_row => $zipcode) { ?>
            <tr id="zipcode-row<?php echo $zipcode_row; ?>">
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_zipcode[<?php echo $zipcode_row; ?>][inzip]" value="<?php echo $zipcode['inzip']; ?>" size="50" class="form-control"/>
                    <?php if (isset($error_zipcode[$zipcode_row]['inzip'])) { ?>
                    <span class="error"><?php echo $error_zipcode[$zipcode_row]['inzip']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_zipcode[<?php echo $zipcode_row; ?>][outzip]" value="<?php echo $zipcode['outzip']; ?>" size="50" class="form-control"/>
                    <?php if (isset($error_zipcode[$zipcode_row]['outzip'])) { ?>
                    <span class="error"><?php echo $error_zipcode[$zipcode_row]['outzip']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left"><button type="button" onclick="$('#zipcode-row<?php echo $zipcode_row; ?>').remove();return false;" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
            <?php } ?>
            <?php $zipcode_row++; ?>
            <?php } ?>
            </tbody>

            <tfoot>
            <tr>
                <td colspan="2"></td>
                <td class="text-left"><button type="button" onclick="addZipCode();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="tab-pane" id="tab-bubble">
        <table id="bubble" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <td class="text-left"><?php echo $column_bubble_title; ?></td>
                <td class="text-left"><?php echo $column_type; ?></td>
                <td class="text-left"><?php echo $column_method; ?></td>
                <td class="text-left"><?php echo $column_geo_zone; ?></td>
                <td class="text-left"><?php echo $column_min_cost; ?></td>
                <td class="text-left"><?php echo $column_max_cost; ?></td>
                <td class="text-left"><?php echo $column_min_weight; ?></td>
                <td class="text-left"><?php echo $column_max_weight; ?></td>
                <td class="text-left"><?php echo $column_min_all_total; ?></td>
                <td class="text-left"><?php echo $column_max_all_total; ?></td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <?php $bubble_row = 0; ?>
            <?php if (is_array($pochtaros_bubbles) and count($pochtaros_bubbles) > 0) { ?>
            <?php foreach ($pochtaros_bubbles as $bubble_row => $bubble) { ?>
            <tr id="bubble-row<?php echo $bubble_row; ?>">
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][bubble_title]" value="<?php if (isset($bubble['bubble_title'])) echo $bubble['bubble_title']; ?>" size="20" class="form-control" />
                    <?php if (isset($error_bubbles[$bubble_row]['bubble_title'])) { ?>
                    <span class="error"><?php echo $error_bubbles[$bubble_row]['bubble_title']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <select name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][logic]" id="<?php echo $name; ?>_bubbles_<?php echo $bubble_row; ?>_logic" class="form-control">
                        <?php foreach ($bubble_type as $type => $tname) { ?>
                        <option <?php if ($type == $bubble['logic']) echo 'selected="selected"'; ?> value="<?php echo $type; ?>"><?php echo $tname; ?></option>
                        <?php } ?>
                    </select>
                    <?php if (isset($error_bubbles[$bubble_row]['bubble_type'])) { ?>
                    <span class="error"><?php echo $error_bubbles[$bubble_row]['bubble_type']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <div class="well well-sm" style="height: 100px; overflow: auto;">
                        <?php foreach ($on_methods as $method) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][key][]" value="<?php echo $method['key']; ?>" <?php  if (!empty($bubble['key']) && is_array($bubble['key']) && in_array($method['key'], $bubble['key'])) echo 'checked="checked"'; ?> />
                                <?php if (isset($pochtaros_title_tab[$method['key']]) and !empty($pochtaros_title_tab[$method['key']])) echo $pochtaros_title_tab[$method['key']]; else echo $method['title']; ?>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                </td>
                <td class="text-left">
                    <div class="well well-sm" style="height: 100px; overflow: auto;">
                        <?php foreach ($geo_zones as $geo_zone) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php  if (isset($bubble['geo_zone']) && in_array($geo_zone['geo_zone_id'], $bubble['geo_zone'])) echo 'checked="checked"'; ?> />
                                <?php echo $geo_zone['name']; ?>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][min_cost]" value="<?php if (isset($bubble['min_cost'])) echo $bubble['min_cost']; ?>" size="3" class="form-control" />
                    <?php if (isset($error_bubbles[$bubble_row]['min_cost'])) { ?>
                    <span class="error"><?php echo $error_bubbles[$bubble_row]['min_cost']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][max_cost]" value="<?php if (isset($bubble['max_cost'])) echo $bubble['max_cost']; ?>" size="3" class="form-control" />
                    <?php if (isset($error_bubbles[$bubble_row]['max_cost'])) { ?>
                    <span class="error"><?php echo $error_bubbles[$bubble_row]['max_cost']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][min_weight]" value="<?php if (isset($bubble['min_weight'])) echo $bubble['min_weight']; ?>" size="3" class="form-control" />
                    <?php if (isset($error_bubbles[$bubble_row]['min_weight'])) { ?>
                    <span class="error"><?php echo $error_bubbles[$bubble_row]['min_weight']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][max_weight]" value="<?php if (isset($bubble['max_weight'])) echo $bubble['max_weight']; ?>" size="3" class="form-control" />
                    <?php if (isset($error_bubbles[$bubble_row]['max_weight'])) { ?>
                    <span class="error"><?php echo $error_bubbles[$bubble_row]['max_weight']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][min_all_total]" value="<?php if (isset($bubble['min_all_total'])) echo $bubble['min_all_total']; ?>" size="3" class="form-control" />
                    <?php if (isset($error_bubbles[$bubble_row]['min_all_total'])) { ?>
                    <span class="error"><?php echo $error_bubbles[$bubble_row]['min_all_total']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left">
                    <input type="text" name="<?php echo $name; ?>_bubbles[<?php echo $bubble_row; ?>][max_all_total]" value="<?php if (isset($bubble['max_all_total'])) echo $bubble['max_all_total']; ?>" size="3" class="form-control" />
                    <?php if (isset($error_bubbles[$bubble_row]['max_all_total'])) { ?>
                    <span class="error"><?php echo $error_bubbles[$bubble_row]['max_all_total']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-left"><button type="button" onclick="$('#bubble-row<?php echo $bubble_row; ?>').remove();return false;" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
            <?php } ?>
            <?php $bubble_row++; ?>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="10"></td>
                <td class="text-left"><button type="button" onclick="addBubble();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>


    <div class="tab-pane" id="tab-backup">
        <label class="col-sm-2 control-label" for="button-save-backup"><?php echo $text_backup; ?></label>
        <div class="col-sm-10">
            <div class="row">
                <div class="col-sm-4">
                    <button type="button" id="button-save-backup" class="btn btn-primary" onclick="location = '<?php echo $backup_link; ?>';"><i class="fa fa-save"></i> <?php echo $button_save ?></button>
                </div>
            </div>
        </div>

        <br/>&nbsp;<br/>

        <label class="col-sm-2 control-label" for="button-restore-backup"><?php echo $text_restore; ?></label>
        <div class="col-sm-10">
            <div class="row">
                <div class="col-sm-4">
                    <input name="restore" type="hidden" value="0" id="restore" /><input type="file" name="import" class="form-control" />&nbsp;<button type="button" id="button-save-backup" class="btn btn-primary" onclick="$('#restore').val(1);$('#form').submit();"><i class="fa fa-copy"></i> <?php echo $button_restore; ?></button>
                </div>
            </div>
        </div>

    </div>

</div>

<input type="hidden" name="<?php echo $name; ?>_license" size="50" value="<?php if (isset($pochtaros_license)) echo $pochtaros_license; ?>" >

</form>
</div>
</div>
</div>

<style>
    .slider-content {

    }
    .slider-content.hidden{
        display: none;
    }
</style>

<script type="text/javascript"><!--
    if ($('#pochtaros_zaglushka').is(':checked')) {
        $(".form-group.slider-content").removeClass("hidden");
    }
    else {
        $(".form-group.slider-content").addClass("hidden");
    }


    $('#pochtaros_zaglushka').change(function () {
        if ($('#pochtaros_zaglushka').is(':checked')) {
            $(".form-group.slider-content").removeClass("hidden");
        }
        else {
            $(".form-group.slider-content").addClass("hidden");
        }
    });

    <?php foreach ($languages as $language) { ?>
        $('#input-description<?php echo $language['language_id']; ?>').summernote({height: 300});
    <?php } ?>

    $('#language a:first').tab('show');
    $('#methods a:first').tab('show');


    var discount_row = <?php if (isset($discount_row)) echo $discount_row; else echo '0'; ?>;

    function addDiscount() {
        var html = '<tr id="discount-row' + discount_row + '">';

        html += '		<td class="text-left">';
        html += '			<select class="form-control" name="<?php echo $name; ?>_discounts[' + discount_row + '][prefix]">';
        <?php foreach (array('-', '+') as $prefix) { ?>
            html += '				<option value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>';
        <?php } ?>
        html += '			</select>';
        html += '			<input type="text" name="<?php echo $name; ?>_discounts[' + discount_row + '][value]" value="" size="3" class="form-control" />';
        html += '			<select class="form-control" name="<?php echo $name; ?>_discounts[' + discount_row + '][mode]">';
        <?php foreach ($discount_type as $type => $tname) { ?>
            html += '				<option value="<?php echo $type; ?>"><?php echo $tname; ?></option>';
        <?php } ?>
        html += '			</select>';
        html += '		</td>';

        html += '		<td class="text-left">';

        html += '			<div class="well well-sm" style="height: 100px; overflow: auto;">';

        <?php foreach ($on_methods as $method) { ?>
            html += '				<div class="checkbox"><label>';
            html += '				<input type="checkbox" name="<?php echo $name; ?>_discounts[' + discount_row + '][key][]" value="<?php echo $method['key']; ?>" />';
            html += '               <?php if (isset($pochtaros_title_tab[$method['key']]) and !empty($pochtaros_title_tab[$method['key']])) echo $pochtaros_title_tab[$method['key']]; else echo $method['title']; ?>';
            html += '				</label></div>';
        <?php } ?>
        html += '			</div>';
        html += '		</td>';
        html += '		<td class="text-left">';
        html += '			<div class="well well-sm" style="height: 100px; overflow: auto;">';

        <?php foreach ($customer_groups as $customer_group) { ?>
            html += '				<div class="checkbox"><label>';
            html += '				<input type="checkbox" name="<?php echo $name; ?>_discounts[' + discount_row + '][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" />';
            html += '				<?php echo $customer_group['name']; ?>';
            html += '				</label></div>';
        <?php } ?>
        html += '			</div>';
        html += '		</td>';
        html += '		<td class="text-left">';
        html += '			<div class="well well-sm" style="height: 100px; overflow: auto;">';

        <?php foreach ($geo_zones as $geo_zone) { ?>
            html += '				<div class="checkbox"><label>';
            html += '					<input type="checkbox" name="<?php echo $name; ?>_discounts[' + discount_row + '][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />';
            html += '					<?php echo $geo_zone['name']; ?>';
            html += '				</label></div>';
        <?php } ?>
        html += '			</div>';
        html += '		</td>';


        html += '		<td class="text-left">';
        html += '			<input type="text" name="<?php echo $name; ?>_discounts[' + discount_row + '][min_total]" value="" size="3" class="form-control" />';
        html += '		</td>';

        html += '		<td class="text-left">';
        html += '			<input type="text" name="<?php echo $name; ?>_discounts[' + discount_row + '][max_total]" value="" size="3" class="form-control" />';
        html += '		</td>';

        html += '		<td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();return false;" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';

        html += '</tr>';

        $('#discount tbody').append(html);

        discount_row++;
    }

    var zipcode_row = <?php if (isset($zipcode_row)) echo $zipcode_row; else echo '0'; ?>;

    function addZipCode() {
        var html = '<tr id="zipcode-row' + zipcode_row + '">';

        html += '		<td class="left">';
        html += '		<input type="text" name="<?php echo $name; ?>_zipcode[' + zipcode_row + '][inzip]" value="" size="50" class="form-control"/>';
        html += '		</td>';
        html += '		<td class="left">';
        html += '		<input type="text" name="<?php echo $name; ?>_zipcode[' + zipcode_row + '][outzip]" value="" size="50" class="form-control"/>';
        html += '		</td>';
        html += '		<td class="text-left"><button type="button" onclick="$(\'#zipcode-row' + zipcode_row + '\').remove();return false;" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '		</tr>';

        $('#zipcode tbody').append(html);
        zipcode_row++;
    }

    var bubble_row = <?php if (isset($bubble_row)) echo $bubble_row; else echo '0'; ?>;

    function addBubble() {
        var html = '<tr id="bubble-row' + bubble_row + '">';
        html += '		<td class="left">';
        html += '		<input type="text" name="<?php echo $name; ?>_bubbles[' + bubble_row + '][bubble_title]" value="" size="20" class="form-control"/>';
        html += '		</td>';
        html += '		<td class="left">';
        html += '		<select name="<?php echo $name; ?>_bubbles[' + bubble_row + '][logic]" id="<?php echo $name; ?>_bubbles_' + bubble_row + '_logic" class="form-control">';
    <?php foreach ($bubble_type as $type => $tname) { ?>
            html += '		<option value="<?php echo $type; ?>"><?php echo $tname; ?></option>';
        <?php } ?>
        html += '		</select>';
        html += '		</td>';

        html += '		<td class="text-left">';
        html += '		<div class="well well-sm" style="height: 100px; overflow: auto;">';
        <?php  foreach ($on_methods as $method) { ?>
            html += '		<div class="checkbox">';
            html += '		<label>';
            html += '		<input type="checkbox" name="<?php echo $name; ?>_bubbles[' + bubble_row + '][key][]" value="<?php echo $method['key']; ?>" />';
            html += '       <?php if (isset($pochtaros_title_tab[$method['key']]) and !empty($pochtaros_title_tab[$method['key']])) echo $pochtaros_title_tab[$method['key']]; else echo $method['title']; ?>';
            html += '		</label>';
            html += '		</div>';
        <?php }  ?>

        html += '		</div>';
        html += '		</td>';


        html += '		<td class="text-left">';
        html += '		<div class="well well-sm" style="height: 100px; overflow: auto;">';
        <?php foreach ($geo_zones as $geo_zone) { ?>
            html += '		<div class="checkbox">';
            html += '		<label>';
            html += '		<input type="checkbox" name="<?php echo $name; ?>_bubbles[' + bubble_row + '][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />';
            html += '		<?php echo $geo_zone['name']; ?>';
            html += '		</label>';
            html += '		</div>';
            <?php } ?>
        html += '		</div>';
        html += '		</td>';

        html += '		<td class="left">';
        html += '			<input type="text" name="<?php echo $name; ?>_bubbles[' + bubble_row + '][min_cost]" value="" size="3" class="form-control"/>';
        html += '		</td>';
        html += '		<td class="left">';
        html += '			<input type="text" name="<?php echo $name; ?>_bubbles[' + bubble_row + '][max_cost]" value="" size="3" class="form-control"/>';
        html += '		</td>';
        html += '		<td class="left">';
        html += '			<input type="text" name="<?php echo $name; ?>_bubbles[' + bubble_row + '][min_weight]" value="" size="3" class="form-control"/>';
        html += '		</td>';
        html += '		<td class="left">';
        html += '			<input type="text" name="<?php echo $name; ?>_bubbles[' + bubble_row + '][max_weight]" value="" size="3" class="form-control"/>';
        html += '		</td>';
        html += '		<td class="left">';
        html += '			<input type="text" name="<?php echo $name; ?>_bubbles[' + bubble_row + '][min_all_total]" value="" size="3" class="form-control"/>';
        html += '		</td>';
        html += '		<td class="left">';
        html += '			<input type="text" name="<?php echo $name; ?>_bubbles[' + bubble_row + '][max_all_total]" value="" size="3" class="form-control"/>';
        html += '		</td>';
        html += '		<td class="text-left"><button type="button" onclick="$(\'#bubble-row' + bubble_row + '\').remove();return false;" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '		</tr>';

        $('#bubble tbody').append(html);

        bubble_row++;
    }


    <?php if ($show_product_groups and isset($groups) and count($groups) > 0) { ?>
        var group_row = <?php if (isset($pgroup_row)) echo (int)$pgroup_row; else echo '0'; ?>;

        function addPGroup() {
            var html = '<tr id="pgroup-row' + group_row + '">';
            html += '		<td class="text-left">';

            html += '		<div class="well well-sm" style="height: 100px; overflow: auto;">';
            <?php  foreach ($on_methods as $method) { ?>
                html += '		<div class="checkbox">';
                html += '		<label>';
                  html += '		<input type="checkbox" name="<?php echo $name; ?>_pgroups[' + group_row + '][key][]" value="<?php echo $method['key']; ?>" />';
                    html += '<?php if (isset($pochtaros_title_tab[$method['key']]) and !empty($pochtaros_title_tab[$method['key']])) echo $pochtaros_title_tab[$method['key']]; else echo $method['title']; ?>';
                   html += '		</label>';
                  html += '		</div>';
              <?php }  ?>

            html += '		</div>';
            html += '		</td>';

            html += '		<td class="text-left">';
            html += '		<select name="<?php echo $name; ?>_pgroups[' + group_row + '][filter_group_id]"  class="form-control">';
            html += '		<option value="0" selected="selected"><?php echo $text_group; ?></option>';
        <?php
                foreach ($groups as $group) {
                if ($group['status'] == 1) {
                        ?>
                    html += '		<option value="<?php echo $group['group_id']; ?>"><?php echo $group['name']; ?></option>';
                <?php
                }
            }
                ?>
            html += '		</select>';
            html += '		</td>';
            html += '		<td class="text-left">';
            html += '		<select name="<?php echo $name; ?>_pgroups[' + group_row + '][logic]" id="<?php echo $name; ?>_pgroups_' + group_row + '_logic" class="form-control">';
            <?php foreach ($group_logic_type as $type => $tname) { ?>
                html += '		<option value="<?php echo $type; ?>"><?php echo $tname; ?></option>';
            <?php } ?>
            html += '		</select>';
            html += '		<input type="text" name="<?php echo $name; ?>_pgroups[' + group_row + '][limit]" id="<?php echo $name; ?>_pgroups_' + group_row + '_limit" value="" disabled size="7" class="form-control" />';
            html += '		</td>';

            html += '		<td class="text-left">';
            html += '		<input type="text" name="<?php echo $name; ?>_pgroups[' + group_row + '][min_total]" value="" size="3" class="form-control" />';
            html += '		</td>';
            html += '		<td class="text-left">';
            html += '		<input type="text" name="<?php echo $name; ?>_pgroups[' + group_row + '][max_total]" value="" size="3" class="form-control" />';
            html += '		</td>';

            html += '		<td class="text-left"><button type="button" onclick="$(\'#pgroup-row' + group_row + '\').remove();return false;" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
            html += '		</tr>';


            $('#pgroup tbody').append(html);

            $('#<?php echo $name; ?>_pgroups_' + group_row + '_logic').change(function () {
                if ($('#<?php echo $name; ?>_pgroups_' + (group_row - 1) + '_logic').val() == 'spec_number' ||
                    $('#<?php echo $name; ?>_pgroups_' + (group_row - 1) + '_logic').val() == 'spec_number2'
                ) {
                    $('#<?php echo $name; ?>_pgroups_' + (group_row - 1) + '_limit').prop('disabled', false);
                }
                else {
                    $('#<?php echo $name; ?>_pgroups_' + (group_row - 1) + '_limit').prop('disabled', true);
                }
            });

            group_row++;
        }

    <?php for ($i=0; $i<=$pgroup_row; $i++ ) { ?>
            $('#<?php echo $name; ?>_pgroups_<?php echo $i; ?>_logic').change(function () {
                if ($('#<?php echo $name; ?>_pgroups_<?php echo $i; ?>_logic').val() == 'spec_number' ||
                    $('#<?php echo $name; ?>_pgroups_<?php echo $i; ?>_logic').val() == 'spec_number2'
                ) {
                    $('#<?php echo $name; ?>_pgroups_<?php echo $i; ?>_limit').prop('disabled', false);
                }
                else {
                    $('#<?php echo $name; ?>_pgroups_<?php echo $i; ?>_limit').prop('disabled', true);
                }
            });

            if ($('#<?php echo $name; ?>_pgroups_<?php echo $i; ?>_logic').val() == 'spec_number' ||
                $('#<?php echo $name; ?>_pgroups_<?php echo $i; ?>_logic').val() == 'spec_number2'
            ) {
                $('#<?php echo $name; ?>_pgroups_<?php echo $i; ?>_limit').prop('disabled', false);
            }
            else {
                $('#<?php echo $name; ?>_pgroups_<?php echo $i; ?>_limit').prop('disabled', true);
            }
        <?php } ?>
    <?php } ?>
    
//--></script></div>

<?php echo $footer; ?>