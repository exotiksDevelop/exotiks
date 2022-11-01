<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cdekoption" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <button type="button" onclick="$('#form-cdekoption').attr('action', $('#form-cdekoption').attr('action') + '&redirect=false'); $('#form-cdekoption').submit();" class="btn btn-primary"><?php echo $button_apply; ?></button>
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
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cdekoption" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-auth" data-toggle="tab"><?php echo $tab_auth; ?></a></li>
            <li><a href="#tab-order" data-toggle="tab"><?php echo $tab_order; ?></a></li>
            <li><a href="#tab-status" data-toggle="tab"><?php echo $tab_status; ?></a></li>
            <li><a href="#tab-currency" data-toggle="tab"><?php echo $tab_currency; ?></a></li>
            <li><a href="#tab-additional" data-toggle="tab"><?php echo $tab_additional; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-data">

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="setting-city"><?php echo $entry_city; ?></label>
                <div class="col-sm-10">
                  <input type="hidden" class="form-control setting-city-id" name="cdek_integrator_setting[city_id]" value="<?php if (!empty($setting['city_id'])) echo $setting['city_id']; ?>"/>

                  <a class="js city-from<?php if (empty($setting['city_id'])) echo ' hidden'; ?>"><?php if (!empty($setting['city_name'])) echo $setting['city_name']; ?></a>

                  <input type="text" name="cdek_integrator_setting[city_name]" value="<?php if (!empty($setting['city_name'])) echo $setting['city_name']; ?>" class="setting-city-name form-control" style="<?php if (!empty($setting['city_id'])) echo 'display:none;'; ?>" />

                  <?php if (isset($error['setting']['city_id'])) { ?>
                    <div class="text-danger"><?php echo $error['setting']['city_id']; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-city-default"><?php echo $entry_city_default; ?></label>
                <div class="col-sm-10">
                  <select id="setting-city-default" name="cdek_integrator_setting[city_default]" class="form-control">
                    <?php foreach ($boolean_variables as $key => $value) { ?>
                    <option <?php if (isset($setting['city_default']) && $key == $setting['city_default']) echo 'selected="selected"'; ?>value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="setting-copy-count"><?php echo $entry_copy_count; ?></label>
                <div class="col-sm-10">
                  <select id="setting-copy-count" name="cdek_integrator_setting[copy_count]" class="form-control">
                    <?php foreach (array(1,2,3,4) as $value) { ?>
                    <option <?php if (isset($setting['copy_count']) && $setting['copy_count'] == $value) echo 'selected="selected"'; ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="setting-weight-class-id"><?php echo $entry_weight_class_id; ?></label>
                <div class="col-sm-10">
                  <select id="setting-weight-class-id" name="cdek_integrator_setting[weight_class_id]" class="form-control">
                    <?php foreach ($weight_classes as $weight_class) { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if (!empty($setting['weight_class_id']) && $setting['weight_class_id'] == $weight_class['weight_class_id']) echo 'selected="selected"'; ?>><?php echo $weight_class['title']; ?></option>
                    <?php } ?>
                  </select>
                  <?php if (isset($error['setting']['weight_class_id'])) { ?>
                  <span class="text-danger"><?php echo $error['setting']['weight_class_id']; ?></span>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="setting-length-class-id"><?php echo $entry_length_class_id; ?></label>
                <div class="col-sm-10">
                  <select id="setting-length-class-id" name="cdek_integrator_setting[length_class_id]" class="form-control">
                    <?php foreach ($length_classes as $length_class) { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>" <?php if (!empty($setting['length_class_id']) && $setting['length_class_id'] == $length_class['length_class_id']) echo 'selected="selected"'; ?>><?php echo $length_class['title']; ?></option>
                    <?php } ?>
                  </select>
                  <?php if (isset($error['setting']['length_class_id'])) { ?>
                  <span class="text-danger"><?php echo $error['setting']['length_class_id']; ?></span>
                  <?php } ?>
                </div>
              </div>

            </div>
            <div class="tab-pane" id="tab-auth">
              <div class="col-sm-12">
                <div class="alert alert-info text-center"><?php echo $text_testing_api_keys; ?></div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="setting-account"><?php echo $entry_account; ?></label>
                <div class="col-sm-10">
                  <input id="setting-account" class="form-control" type="text" name="cdek_integrator_setting[account]" value="<?php if (!empty($setting['account'])) echo $setting['account']; ?>" />
                  <?php if (isset($error['setting']['account'])) { ?>
                  <span class="text-danger"><?php echo $error['setting']['account']; ?></span>
                  <?php } ?>
                </div>
              </div>


              <div class="form-group required">
                <label class="col-sm-2 control-label" for="setting-secure-password"><?php echo $entry_secure_password; ?></label>
                <div class="col-sm-10">
                  <input id="setting-secure-password" class="form-control" type="text" name="cdek_integrator_setting[secure_password]" value="<?php if (!empty($setting['secure_password'])) echo $setting['secure_password']; ?>" />
                  <?php if (isset($error['setting']['secure_password'])) { ?>
                  <span class="text-danger"><?php echo $error['setting']['secure_password']; ?></span>
                  <?php } ?>
                </div>
              </div>

            </div>
            <div class="tab-pane" id="tab-order">

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_new_order_status_id; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($order_statuses as $order_status) { ?>
                      <div class="checkbox">
                        <label>
                        <input type="checkbox" name="cdek_integrator_setting[new_order_status_id][]" value="<?php echo $order_status['order_status_id']; ?>" <?php  if (!empty($setting['new_order_status_id']) && in_array($order_status['order_status_id'], $setting['new_order_status_id'])) echo 'checked="checked"'; ?> />
                        <?php echo $order_status['name']; ?>
                        </label>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-new-order"><?php echo $entry_new_order; ?></label>
                <div class="col-sm-10">
                  <input id="setting-new-order" type="text" class="form-control" name="cdek_integrator_setting[new_order]" value="<?php if (!empty($setting['new_order'])) echo $setting['new_order']; ?>" />
                  <?php if (isset($error['setting']['new_order'])) { ?>
                    <span class="text-danger"><?php echo $error['setting']['new_order']; ?></span>
                  <?php } ?>
                </div>
              </div>

              <?php if ($show_filter) { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_shipping_methods; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($shipping_methods as $code => $name) { ?>
                      <div class="checkbox">
                        <label>
                        <input type="checkbox" name="cdek_integrator_setting[shipping_method][]" value="<?php echo $code; ?>" <?php  if (!empty($setting['shipping_method']) && in_array($code, $setting['shipping_method'])) echo 'checked="checked"'; ?> />
                        <?php echo $name; ?>
                        </label>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_payment_methods; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($payment_methods as $code => $name) { ?>
                      <div class="checkbox">
                        <label>
                        <input type="checkbox" name="cdek_integrator_setting[payment_method][]" value="<?php echo $code; ?>" <?php  if (!empty($setting['payment_method']) && in_array($code, $setting['payment_method'])) echo 'checked="checked"'; ?> />
                          <?php echo $name; ?>
                        </label>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php } ?>

            </div>
            <div class="tab-pane" id="tab-status">
              <p class="help"><?php echo $text_help_status_rule; ?></p>
              <table class="list table">
                <thead>
                  <tr>
                    <td class="left"><?php echo $column_cdek_status; ?></td>
                    <td class="left"><?php echo $column_new_status; ?></td>
                    <td class="left"><?php echo $column_notify; ?></td>
                    <td class="left"><?php echo $column_comment; ?></td>
                    <td class="left"><?php echo $column_action; ?></td>
                  </tr>
                  <tbody>
                    <?php if (!empty($setting['order_status_rule'])) { ?>
                    <?php foreach ($setting['order_status_rule'] as $row => $rule_info) { ?>
                    <tr rel="<?php echo $row; ?>">
                      <td class="left">
                        <select class="form-control" name="cdek_integrator_setting[order_status_rule][<?php echo $row; ?>][cdek_status_id]">
                          <?php foreach ($cdek_statuses as $status_id => $cdek_status) { ?>
                          <option <?php if ($rule_info['cdek_status_id'] ==  $status_id) echo 'selected="selected"'; ?> value="<?php echo $status_id; ?>"><?php echo $cdek_status['title']; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                      <td class="left">
                        <select class="form-control" name="cdek_integrator_setting[order_status_rule][<?php echo $row; ?>][order_status_id]">
                          <?php foreach ($order_statuses as $order_status) { ?>
                          <option <?php if ($rule_info['order_status_id'] ==  $order_status['order_status_id']) echo 'selected="selected"'; ?> value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                      <td class="left">
                        <select class="form-control" id="setting-city-default" name="cdek_integrator_setting[order_status_rule][<?php echo $row; ?>][notify]">
                          <?php foreach ($boolean_variables as $key => $value) { ?>
                          <option <?php if ($rule_info['notify'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                      <td class="left">
                        <p class="mt-0 link"><a class="js slider"><?php echo $text_tokens; ?></a></p>
                        <div class="content" style="display:none">
                          <table class="list token">
                            <thead>
                              <tr>
                                <td width="30%" class="left"><?php echo $column_token; ?></td>
                                <td width="70%" class="left"><?php echo $column_value; ?></td>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="left">{dispatch_number}</td>
                                <td class="left"><?php echo $text_token_dispatch_number; ?></td>
                              </tr>
                              <tr>
                                <td class="left">{order_id}</td>
                                <td class="left"><?php echo $text_token_order_id; ?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <textarea class="form-control" name="cdek_integrator_setting[order_status_rule][<?php echo $row; ?>][comment]" rows="3" cols="50"><?php echo $rule_info['comment']; ?></textarea>
                      </td>
                      <td class="left"><a class="btn btn-primary delete">Удалить</a></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                  </tbody>
                </thead>
              </table>
              <a class="btn btn-primary" onclick="addStatusRule();">Добавить правило</a>
            </div>
            <div class="tab-pane" id="tab-currency">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-currency"><?php echo $entry_currency; ?></label>
                <div class="col-sm-10">
                  <select id="setting-currency" name="cdek_integrator_setting[currency]" class="form-control">
                    <?php foreach ($currency_list as $key => $value) { ?>
                    <option <?php if (isset($setting['currency']) && $setting['currency'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-currency-agreement"><?php echo $entry_currency_agreement; ?></label>
                <div class="col-sm-10">
                  <select id="setting-currency-agreement" name="cdek_integrator_setting[currency_agreement]" class="form-control">
                    <?php foreach ($currency_list as $key => $value) { ?>
                    <option <?php if (isset($setting['currency_agreement']) && $setting['currency_agreement'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

            </div>
            <div class="tab-pane" id="tab-additional">
              <div class="legend">Общие</div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-replace-items"><?php echo $entry_replace_items; ?></label>
                <div class="col-sm-10">
                  <select id="setting-replace-items" name="cdek_integrator_setting[replace_items]" class="form-control toggle" data-toggletarget="#setting-replace-items_params">
                    <?php foreach ($boolean_variables as $key => $value) { ?>
                    <option <?php if (isset($setting['replace_items']) && $setting['replace_items'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="toggleTarget" id="setting-replace-items_params" style="display:<?php echo (bool)$setting['replace_items'] ? 'block' : 'none'; ?>">
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="setting-replace-item-name"><?php echo $entry_replace_item_name; ?></label>
                  <div class="col-sm-10">
                    <input class="form-control" id="setting-replace-item-name" type="text" name="cdek_integrator_setting[replace_item_name]" value="<?php if (isset($setting['replace_item_name'])) echo $setting['replace_item_name']; ?>" maxlength="255" />
                    <?php if (isset($error['setting']['replace_item_name'])) { ?>
                    <span class="text-danger"><?php echo $error['setting']['replace_item_name']; ?></span>
                    <?php } ?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label" for="setting-replace-item-cost"><?php echo $entry_replace_item_cost; ?></label>
                  <div class="col-sm-10">
                    <input class="form-control" id="setting-replace-item-cost" type="text" name="cdek_integrator_setting[replace_item_cost]" value="<?php if (isset($setting['replace_item_cost'])) echo $setting['replace_item_cost']; ?>" maxlength="255" />
                    <?php if (isset($error['setting']['replace_item_cost'])) { ?>
                    <span class="text-danger"><?php echo $error['setting']['replace_item_cost']; ?></span>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="setting-replace-item-payment"><?php echo $entry_replace_item_payment; ?></label>
                  <div class="col-sm-10">
                    <input class="form-control" id="setting-replace-item-payment" type="text" name="cdek_integrator_setting[replace_item_payment]" value="<?php if (isset($setting['replace_item_payment'])) echo $setting['replace_item_payment']; ?>" maxlength="255" />
                    <?php if (isset($error['setting']['replace_item_payment'])) { ?>
                    <span class="text-danger"><?php echo $error['setting']['replace_item_payment']; ?></span>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="setting-replace-item-amount"><?php echo $entry_replace_item_amount; ?></label>
                  <div class="col-sm-10">
                    <input class="form-control" id="setting-replace-item-amount" type="text" name="cdek_integrator_setting[replace_item_amount]" value="<?php if (!empty($setting['replace_item_amount'])) echo $setting['replace_item_amount']; ?>" maxlength="255" />
                    <?php if (isset($error['setting']['replace_item_amount'])) { ?>
                    <span class="text-danger"><?php echo $error['setting']['replace_item_amount']; ?></span>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-cron"><?php echo $entry_use_cron; ?></label>
                <div class="col-sm-10">
                  <select id="setting-cron" name="cdek_integrator_setting[use_cron]" class="form-control">
                    <?php foreach ($boolean_variables as $key => $value) { ?>
                    <option <?php if (isset($setting['use_cron']) && $setting['use_cron'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-cod"><?php echo $entry_cod_default; ?></label>
                <div class="col-sm-10">
                  <select id="setting-cod" name="cdek_integrator_setting[cod]" class="form-control">
                    <?php foreach ($boolean_variables as $key => $value) { ?>
                    <option <?php if (isset($setting['cod']) && $setting['cod'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-delivery-recipient-cost"><?php echo $entry_delivery_recipient_cost; ?></label>
                <div class="col-sm-10">
                  <input id="setting-delivery-recipient-cost" type="text" name="cdek_integrator_setting[delivery_recipient_cost]" value="<?php if (!empty($setting['delivery_recipient_cost'])) echo $setting['delivery_recipient_cost']; ?>" size="2" class="form-control" />
                  <?php if (isset($error['setting']['delivery_recipient_cost'])) { ?>
                  <span class="text-danger"><?php echo $error['setting']['delivery_recipient_cost']; ?></span>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-seller-name"><?php echo $entry_seller_name; ?></label>
                <div class="col-sm-10">
                  <input id="setting-seller-name" type="text" name="cdek_integrator_setting[seller_name]" value="<?php if (!empty($setting['seller_name'])) echo $setting['seller_name']; ?>" maxlength="255" class="form-control"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_add_service; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($add_cervices as $cervice_code => $cervice_info) { ?>
                    <?php if (isset($cervice_info['hide'])) continue; ?>
                      <div class="checkbox">
                        <label>
                        <input type="checkbox" name="cdek_integrator_setting[add_service][]" value="<?php echo $cervice_code; ?>" <?php if (!empty($setting['add_service']) && in_array($cervice_code, $setting['add_service'])) echo 'checked="checked"'; ?> />
                        <span data-toggle="tooltip" title="<?php echo $cervice_info['description']; ?>"><?php echo $cervice_info['title']; ?></span>
                        </label>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <div class="legend">Дополнительный вес</div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-packing-min-weight"><?php echo $entry_packing_min_weight; ?></label>
                <div class="col-sm-10 form-inline">
                    <input id="setting-packing-min-weight" class="form-control" type="text" name="cdek_integrator_setting[packing_min_weight]" value="<?php if (!empty($setting['packing_min_weight'])) echo $setting['packing_min_weight']; ?>" size="1" />
                    <select name="cdek_integrator_setting[packing_weight_class_id]" class="form-control">
                      <?php foreach ($weight_classes as $weight_class) { ?>
                      <option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if (!empty($setting['packing_weight_class_id']) && $setting['packing_weight_class_id'] == $weight_class['weight_class_id']) echo 'selected="selected"'; ?>><?php echo $weight_class['title']; ?></option>
                      <?php } ?>
                    </select>
                    <?php if (isset($error['setting']['packing_min_weight'])) { ?>
                      <span class="text-danger"><?php echo $error['setting']['packing_min_weight']; ?></span>
                    <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="setting-packing-value"><?php echo $entry_packing_additional_weight; ?></label>
                <div class="col-sm-10 form-inline">
                    <select name="cdek_integrator_setting[packing_prefix]" class="form-control">
                      <?php foreach (array('+', '-') as $prefix) { ?>
                      <option <?php if (!empty($setting['packing_prefix']) && $setting['packing_prefix'] == $prefix) echo 'selected="selected"'; ?> value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
                      <?php } ?>
                    </select>
                    <input class="form-control" id="setting-packing-value" type="text" name="cdek_integrator_setting[packing_value]" value="<?php if (!empty($setting['packing_value'])) echo $setting['packing_value']; ?>" size="1" />
                    <select class="form-control" name="cdek_integrator_setting[packing_mode]">
                      <?php foreach($additional_weight_mode as $key => $value) { ?>
                      <option <?php if (!empty($setting['packing_mode']) && $setting['packing_mode'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                      <?php } ?>
                    </select>
                    <?php if (isset($error['setting']['packing_value'])) { ?>
                      <span class="text-danger"><?php echo $error['setting']['packing_value']; ?></span>
                    <?php } ?>
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
  $('a.btn.delete').on('click', function(){
  $(this).closest('tr').remove();
});

function addStatusRule() {

  var row = exists = -1;

  $('#tab-status table.list tr[rel]').each(function(){

    var rel = $(this).attr('rel');

    if (rel > row) {
      row = rel;
    }

  });

  row++;

  var html = '<tr rel="' + row + '">';
  html += ' <td class="left">';
  html += '   <select class="form-control" name="cdek_integrator_setting[order_status_rule][' + row + '][cdek_status_id]">';
  <?php foreach ($cdek_statuses as $status_id => $cdek_status) { ?>
  html += '     <option value="<?php echo $status_id; ?>"><?php echo $cdek_status['title']; ?></option>';
  <?php } ?>
  html += '   </select>';
  html += ' </td>';
  html += ' <td class="left">';
  html += '   <select class="form-control" name="cdek_integrator_setting[order_status_rule][' + row + '][order_status_id]">';
  <?php foreach ($order_statuses as $order_status) { ?>
  html += '     <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>';
  <?php } ?>
  html += '   </select>';
  html += ' </td>';
  html += ' <td class="left">';
  html += '   <select class="form-control" id="setting-city-default" name="cdek_integrator_setting[order_status_rule][' + row + '][notify]">';
  <?php foreach ($boolean_variables as $key => $value) { ?>
  html += '   <option value="<?php echo $key; ?>"><?php echo $value; ?></option>';
  <?php } ?>
  html += '   </select>';
  html += ' </td>';
  html += ' <td class="left">';
  html += '   <p class="mt-0 link">';
  html += '     <a class="js slider"><?php echo $text_tokens; ?></a>';
  html += '   </p>';
  html += '   <div class="content" style="display:none;">';
  html += '     <table class="list token">';
  html += '       <thead>';
  html += '         <tr>';
  html += '           <td width="30%" class="left"><?php echo $column_token; ?></td>';
  html += '           <td width="70%" class="left"><?php echo $column_value; ?></td>';
  html += '         </tr>';
  html += '       </thead>';
  html += '       <tbody>';
  html += '         <tr>';
  html += '           <td class="left">{dispatch_number}</td>';
  html += '           <td class="left"><?php echo $text_token_dispatch_number; ?></td>';
  html += '         </tr>';
  html += '         <tr>';
  html += '           <td class="left">{order_id}</td>';
  html += '           <td class="left"><?php echo $text_token_order_id; ?></td>';
  html += '         </tr>';
  html += '       </tbody>';
  html += '     </table>';
  html += '   </div>';
  html += '   <textarea class="form-control" name="cdek_integrator_setting[order_status_rule][' + row + '][comment]" rows="3" cols="50"></textarea>';
  html += ' </td>';
  html += ' <td class="left"><a class="btn btn-primary delete">Удалить</a></td>';
  html += '</tr>';

  $('#tab-status table.list tbody:first').append(html);
}
</script>

<?php echo $footer; ?>