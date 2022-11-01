<?php echo $header; ?>
<style>
.tab-content span[data-toggle="tooltip"]:after{
    font-family: FontAwesome;
    color: #1E91CF;
    content: "\f059";
    margin-left: 4px;
    line-height: 14px;
}
.btn-group > label:not(.active):not(:hover){
    color: #333 !important;
    background-color: #fff !important;
    border-color: #ccc !important;
}
.btn-group > label.active span[data-toggle="tooltip"]:after{
    color: #fff !important;
}
.btn-group > label:not(.active):hover{
    color: #333 !important;
    background-color: #e6e6e6 !important;
    border-color: #adadad !important;
}
</style>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-forgotten-cart" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-forgotten-cart" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li <?php if ($open_tab == "tab-general") { ?>class="active"<?php } ?>><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li <?php if ($open_tab == "tab-messages") { ?>class="active"<?php } ?>><a href="#tab-messages" data-toggle="tab"><?php echo $tab_messages; ?></a></li>
            <li <?php if ($open_tab == "tab-related") { ?>class="active"<?php } ?>><a href="#tab-related" data-toggle="tab"><?php echo $tab_related; ?></a></li>
            <li <?php if ($open_tab == "tab-customers") { ?>class="active"<?php } ?>><a href="#tab-customers" data-toggle="tab"><?php echo $tab_customers; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane <?php if ($open_tab == "tab-general") { ?>active<?php } ?>" id="tab-general">
              <div class="form-group">
                <label class="col-md-2 col-sm-3 control-label"><?php echo $entry_status; ?></label>
                <div class="col-md-10 col-sm-9">
                    <div class="btn-group" data-toggle="buttons">
                      <?php if ($forgotten_cart_status) { ?>
                      <label class="btn btn-success active">
                        <input type="radio" name="forgotten_cart_status" value="1" checked="checked"> <?php echo $text_enabled; ?>
                      </label>
                      <label class="btn btn-danger">
                        <input type="radio" name="forgotten_cart_status" value="0"> <?php echo $text_disabled; ?>
                      </label>
                      <?php } else { ?>
                      <label class="btn btn-success">
                        <input type="radio" name="forgotten_cart_status" value="1"> <?php echo $text_enabled; ?>
                      </label>
                      <label class="btn btn-danger active">
                        <input type="radio" name="forgotten_cart_status" value="0" checked="checked"> <?php echo $text_disabled; ?>
                      </label>
                      <?php } ?>
                    </div>
                </div>
              </div>
              
              <div class="form-group ">
                <label class="col-md-2 col-sm-3 control-label"><?php echo $entry_auto_send_message; ?></label>
                <div class="col-md-10 col-sm-9">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-primary <?php if ($forgotten_cart_auto_send == 1) { ?>active<?php } ?>">
                        <input type="radio" name="forgotten_cart_auto_send" value="1" <?php if ($forgotten_cart_auto_send == 1) { ?> checked="checked" <?php } ?>> <span data-toggle="tooltip" title="<?php echo $help_javascript; ?>">JavaScript</span>
                      </label>
                      <label class="btn btn-primary <?php if ($forgotten_cart_auto_send == 2) { ?>active<?php } ?>">
                        <input type="radio" name="forgotten_cart_auto_send" value="2" <?php if ($forgotten_cart_auto_send == 2) { ?> checked="checked" <?php } ?> /> <span data-toggle="tooltip" title="<?php echo $help_cron; ?>">Cron</span>
                      </label>
                      <label class="btn btn-primary <?php if (!$forgotten_cart_auto_send) { ?>active<?php } ?>">
                        <input type="radio" name="forgotten_cart_auto_send" value="0"  <?php if (!$forgotten_cart_auto_send) { ?> checked="checked" <?php } ?> /> <?php echo $text_no; ?>
                      </label>
                    </div>
                </div>
              </div>
              
              <div class="form-group auto_send_cron">
                <label class="col-md-2 col-sm-3" style="text-align: right;"><?php echo $text_cron_link; ?></label>
                <div class="col-md-10 col-sm-9">
                    <div class="input-group col-sm-10"><?php echo $cron_link; ?></div>
                </div>
              </div>
              
              <div class="form-group auto_send">
                <label class="col-md-2 col-sm-3 control-label"><?php echo $entry_manager_notifi; ?></label>
                <div class="col-md-10 col-sm-9">
                    <div class="btn-group" data-toggle="buttons">
                    <?php if ($forgotten_cart_manager_notifi) { ?>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_manager_notifi" value="1" checked="checked" /> <?php echo $text_yes; ?>
                      </label>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_manager_notifi" value="0" /> <?php echo $text_no; ?>
                      </label>
                    <?php } else { ?>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_manager_notifi" value="1"  /> <?php echo $text_yes; ?>
                      </label>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_manager_notifi" value="0" checked="checked" /> <?php echo $text_no; ?>
                      </label>
                    <?php } ?>
                    </div>
                </div>
              </div>
              
              <div class="form-group auto_send manager_notifi required">
                <label class="col-md-2 col-sm-3 control-label" for="input-manager_notifi_email"><span data-toggle="tooltip" title="<?php echo $help_manager_email; ?>"><?php echo $entry_manager_notifi_email; ?></span></label>
                <div class="col-md-3 col-sm-4">
                    <div class="input-group col-sm-12">
                      <div class="input-group-addon"><i class="fa fa-envelope-o"></i></div>
                      <input type="text" class="form-control" name="forgotten_cart_manager_notifi_email" id="input-manager_notifi_email" value="<?php echo $forgotten_cart_manager_notifi_email; ?>">
                    </div>
                    <?php if (isset($error_manager_notifi_email)) { ?>
                    <div class="text-danger"><?php echo $error_manager_notifi_email; ?></div>
                    <?php } ?>
                </div>
              </div>
              
              <div class="form-group auto_send manager_notifi required">
                <label class="col-md-2 col-sm-3 control-label" for="input-manager_notifi_time"><?php echo $entry_manager_notifi_time; ?></label>
                <div class="col-md-3 col-sm-4">
                    <div class="input-group col-sm-12">
                      <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                      <input type="text" class="form-control" name="forgotten_cart_manager_notifi_time" id="input-manager_notifi_time" value="<?php echo $forgotten_cart_manager_notifi_time; ?>">
                      <div class="input-group-addon"><?php echo $text_hour; ?></div>
                    </div>
                    <?php if (isset($error_manager_notifi_time)) { ?>
                    <div class="text-danger"><?php echo $error_manager_notifi_time; ?></div>
                    <?php } ?>
                </div>
              </div>
              
              <div class="form-group auto_send">
                <label class="col-md-2 col-sm-3 control-label"><?php echo $entry_customer_notifi; ?></label>
                <div class="col-md-10 col-sm-9">
                    <div class="btn-group" data-toggle="buttons">
                    <?php if ($forgotten_cart_customer_notifi) { ?>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_customer_notifi" value="1" checked="checked" /> <?php echo $text_yes; ?>
                      </label>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_customer_notifi" value="0" /> <?php echo $text_no; ?>
                      </label>
                    <?php } else { ?>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_customer_notifi" value="1"  /> <?php echo $text_yes; ?>
                      </label>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_customer_notifi" value="0" checked="checked" /> <?php echo $text_no; ?>
                      </label>
                    <?php } ?>
                    </div>
                </div>
              </div>
              
              <div class="form-group auto_send customer_notifi required">
                <label class="col-md-2 col-sm-3 control-label" for="input-general_message_time"><?php echo $entry_general_message_time; ?></label>
                <div class="col-md-3 col-sm-4">
                    <div class="input-group col-sm-12">
                      <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                      <input type="text" class="form-control" name="forgotten_cart_general_message_time" id="input-general_message_time" value="<?php echo $forgotten_cart_general_message_time; ?>">
                      <div class="input-group-addon"><?php echo $text_hour; ?></div>
                    </div>
                    <?php if (isset($error_general_message_time)) { ?>
                    <div class="text-danger"><?php echo $error_general_message_time; ?></div>
                    <?php } ?>
                </div>
              </div>
              
              <div class="form-group auto_send customer_notifi">
                <label class="col-md-2 col-sm-3 control-label"><?php echo $entry_repeated_message; ?></label>
                <div class="col-md-10 col-sm-9">
                    <div class="btn-group" data-toggle="buttons">
                    <?php if ($forgotten_cart_repeated_message) { ?>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_repeated_message" value="1" checked="checked" /> <?php echo $text_yes; ?>
                      </label>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_repeated_message" value="0" /> <?php echo $text_no; ?>
                      </label>
                    <?php } else { ?>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_repeated_message" value="1"  /> <?php echo $text_yes; ?>
                      </label>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_repeated_message" value="0" checked="checked" /> <?php echo $text_no; ?>
                      </label>
                    <?php } ?>
                    </div>
                </div>
              </div>
              
              <div class="form-group auto_send repeated_message customer_notifi required">
                <label class="col-md-2 col-sm-3 control-label" for="input-repeated_message_time"><?php echo $entry_repeated_message_time; ?></label>
                <div class="col-md-3 col-sm-4">
                    <div class="input-group col-sm-12">
                      <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                      <input type="text" class="form-control" name="forgotten_cart_repeated_message_time" id="input-repeated_message_time" value="<?php echo $forgotten_cart_repeated_message_time; ?>">
                      <div class="input-group-addon"><?php echo $text_hour; ?></div>
                    </div>
                    <?php if (isset($error_repeated_message_time)) { ?>
                    <div class="text-danger"><?php echo $error_repeated_message_time; ?></div>
                    <?php } ?>
                </div>
              </div>
              <fieldset>
                <legend><?php echo $text_discount_setting; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-discount-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="forgotten_cart_discount_status" id="input-discount-status" class="form-control">
                      <option value="0" <?php if ($forgotten_cart_discount_status == 0) { ?>selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
                      <option value="1" <?php if ($forgotten_cart_discount_status == 1) { ?>selected="selected"<?php } ?>><?php echo $text_discount_message; ?></option>
                      <option value="2" <?php if ($forgotten_cart_discount_status == 2) { ?>selected="selected"<?php } ?>><?php echo $text_discount_repeated_message; ?></option>
                      <option value="3" <?php if ($forgotten_cart_discount_status == 3) { ?>selected="selected"<?php } ?>><?php echo $text_discount_messages; ?></option>
                    </select>
                  </div>
                </div>
              </fieldset>
              
              <div class="table-responsive">
                <table id="discount" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left" style="width: 20%;"><span data-toggle="tooltip" title="<?php echo $help_sum; ?>"><?php echo $entry_sum; ?></span></td>
                      <td class="text-left"><?php echo $entry_type; ?></td>
                      <td class="text-left"><?php echo $entry_discount; ?></td>
                      <td class="text-center" style="width: 15%;"><?php echo $entry_shipping; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $discount_row = 0; ?>
                    <?php if (is_array($forgotten_cart_discounts)) { ?>
                    <?php foreach ($forgotten_cart_discounts as $discount) { ?>
                    <tr id="discount-row<?php echo $discount_row; ?>">
                      <td class="text-left"><div class="input-group col-sm-12"><div class="input-group-addon"><?php echo $text_from; ?></div><input type="text" name="forgotten_cart_discounts[<?php echo $discount_row; ?>][sum]" value="<?php echo (float)$discount['sum']; ?>" class="form-control" /></div></td>
                      <td class="text-left">
                          <select name="forgotten_cart_discounts[<?php echo $discount_row; ?>][type]" class="form-control">
                            <?php if ($discount['type'] == 'P') { ?>
                            <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                            <?php } else { ?>
                            <option value="P"><?php echo $text_percent; ?></option>
                            <?php } ?>
                            <?php if ($discount['type'] == 'F') { ?>
                            <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                            <?php } else { ?>
                            <option value="F"><?php echo $text_amount; ?></option>
                            <?php } ?>
                          </select>
                      </td>
                      <td class="text-left">
                          <input type="text" name="forgotten_cart_discounts[<?php echo $discount_row; ?>][discount]" value="<?php echo (float)$discount['discount']; ?>" class="form-control" />
                      </td>
                      <td class="text-center">
                          <label class="radio-inline">
                            <?php if ($discount['shipping']) { ?>
                            <input type="radio" name="forgotten_cart_discounts[<?php echo $discount_row; ?>][shipping]" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                            <?php } else { ?>
                            <input type="radio" name="forgotten_cart_discounts[<?php echo $discount_row; ?>][shipping]" value="1" />
                            <?php echo $text_yes; ?>
                            <?php } ?>
                          </label>
                          <label class="radio-inline">
                            <?php if (!$discount['shipping']) { ?>
                            <input type="radio" name="forgotten_cart_discounts[<?php echo $discount_row; ?>][shipping]" value="0" checked="checked" />
                            <?php echo $text_no; ?>
                            <?php } else { ?>
                            <input type="radio" name="forgotten_cart_discounts[<?php echo $discount_row; ?>][shipping]" value="0" />
                            <?php echo $text_no; ?>
                            <?php } ?>
                          </label>
                      </td>
                      <td class="text-left"><button type="button" onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_discount_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $discount_row++; ?>
                    <?php } ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="4"></td>
                      <td class="text-left"><button type="button" onclick="addDiscount();" data-toggle="tooltip" title="<?php echo $button_discount_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              
            </div>
            
            <div class="tab-pane <?php if ($open_tab == "tab-messages") { ?>active<?php } ?>" id="tab-messages">
                <ul class="nav nav-tabs" id="language">
                  <?php foreach ($languages as $language) { ?>
                  <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                  <?php } ?>
                </ul>
                <div class="tab-content">
                  <?php foreach ($languages as $language) { ?>
                  <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                      <fieldset>
                        <legend><?php echo $text_message_title; ?></legend>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-subject<?php echo $language['language_id']; ?>"><?php echo $entry_subject; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][subject]" value="<?php echo isset($forgotten_cart_messages[$language['language_id']]['subject']) ? $forgotten_cart_messages[$language['language_id']]['subject'] : $text_subject; ?>" id="input-subject<?php echo $language['language_id']; ?>" class="form-control">
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitename; ?>">[%site_name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitephone; ?>">[%site_phone%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_customername; ?>">[%customer_name%]</span></span>
                              <?php if (isset($error_subject[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_subject[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-message<?php echo $language['language_id']; ?>"><?php echo $entry_message; ?></label>
                            <div class="col-sm-10" id="message">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][message]" rows="8" id="input-message<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['message']) ? $forgotten_cart_messages[$language['language_id']]['message'] : $text_message; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitename; ?>">[%site_name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitephone; ?>">[%site_phone%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_logo; ?>">[%logo%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_customername; ?>">[%customer_name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_cart_link; ?>">[%cart_link%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_products; ?>">[%template_products%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_coupon; ?>">[%template_coupon%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_shipping; ?>">[%template_shipping%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_related_block; ?>">[%related_block%]</span></span>
                              <?php if (isset($error_message[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_message[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-template-products<?php echo $language['language_id']; ?>"><?php echo $entry_template_products; ?></label>
                            <div class="col-sm-10" id="template-products">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_products]" rows="8" id="input-template-products<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_products']) ? $forgotten_cart_messages[$language['language_id']]['template_products'] : $text_template_products; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_name; ?>">[%name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_model; ?>">[%model%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_options; ?>">[%options%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_image; ?>">[%image%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_quantity; ?>">[%quantity%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_price; ?>">[%price%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_sum; ?>">[%sum%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_link; ?>">[%link%]</span></span>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-template-coupon<?php echo $language['language_id']; ?>"><?php echo $entry_template_coupon; ?></label>
                            <div class="col-sm-10" id="template-coupon">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_coupon]" rows="8" id="input-template-coupon<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_coupon']) ? $forgotten_cart_messages[$language['language_id']]['template_coupon'] : $text_template_coupon; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_coupon_code; ?>">[%coupon%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_coupon_discount; ?>">[%discount%]</span></span>
                              <?php if (isset($error_template_coupon[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_template_coupon[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-template-shipping<?php echo $language['language_id']; ?>"><?php echo $entry_template_shipping; ?></label>
                            <div class="col-sm-10" id="template-shipping">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_shipping]" rows="8" id="input-template-shipping<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_shipping']) ? $forgotten_cart_messages[$language['language_id']]['template_shipping'] : $text_template_shipping; ?></textarea>
                              <?php if (isset($error_template_shipping[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_template_shipping[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                      </fieldset>
                      <fieldset>
                        <legend><?php echo $text_message_repeated_title; ?></legend>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-subject_repeated<?php echo $language['language_id']; ?>"><?php echo $entry_subject; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][subject_repeated]" value="<?php echo isset($forgotten_cart_messages[$language['language_id']]['subject_repeated']) ? $forgotten_cart_messages[$language['language_id']]['subject_repeated'] : $text_subject; ?>" id="input-subject_repeated<?php echo $language['language_id']; ?>" class="form-control">
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitename; ?>">[%site_name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitephone; ?>">[%site_phone%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_customername; ?>">[%customer_name%]</span></span>
                              <?php if (isset($error_subject_repeated[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_subject_repeated[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-message_repeated<?php echo $language['language_id']; ?>"><?php echo $entry_message; ?></label>
                            <div class="col-sm-10" id="message_repeated">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][message_repeated]" rows="8" id="input-message_repeated<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['message_repeated']) ? $forgotten_cart_messages[$language['language_id']]['message_repeated'] : $text_message; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitename; ?>">[%site_name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitephone; ?>">[%site_phone%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_logo; ?>">[%logo%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_customername; ?>">[%customer_name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_cart_link; ?>">[%cart_link%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_products; ?>">[%template_products%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_coupon; ?>">[%template_coupon%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_shipping; ?>">[%template_shipping%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_related_block; ?>">[%related_block%]</span></span>
                              <?php if (isset($error_message_repeated[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_message_repeated[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-template-products_repeated<?php echo $language['language_id']; ?>"><?php echo $entry_template_products; ?></label>
                            <div class="col-sm-10" id="template-products_repeated">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_products_repeated]" rows="8" id="input-template-products_repeated<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_products_repeated']) ? $forgotten_cart_messages[$language['language_id']]['template_products_repeated'] : $text_template_products; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_name; ?>">[%name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_model; ?>">[%model%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_options; ?>">[%options%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_image; ?>">[%image%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_quantity; ?>">[%quantity%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_price; ?>">[%price%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_sum; ?>">[%sum%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_link; ?>">[%link%]</span></span>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-template-coupon_repeated<?php echo $language['language_id']; ?>"><?php echo $entry_template_coupon; ?></label>
                            <div class="col-sm-10" id="template-coupon_repeated">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_coupon_repeated]" rows="8" id="input-template-coupon_repeated<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_coupon_repeated']) ? $forgotten_cart_messages[$language['language_id']]['template_coupon_repeated'] : $text_template_coupon; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_coupon_code; ?>">[%coupon%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_coupon_discount; ?>">[%discount%]</span></span>
                              <?php if (isset($error_template_coupon_repeated[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_template_coupon_repeated[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-template-shipping_repeated<?php echo $language['language_id']; ?>"><?php echo $entry_template_shipping; ?></label>
                            <div class="col-sm-10" id="template-shipping_repeated">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_shipping_repeated]" rows="8" id="input-template-shipping_repeated<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_shipping_repeated']) ? $forgotten_cart_messages[$language['language_id']]['template_shipping_repeated'] : $text_template_shipping; ?></textarea>
                              <?php if (isset($error_template_shipping_repeated[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_template_shipping_repeated[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                      </fieldset>
                      <fieldset>
                        <legend><?php echo $entry_manager_notifi; ?></legend>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-subject_manager<?php echo $language['language_id']; ?>"><?php echo $entry_subject; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][subject_manager]" value="<?php echo isset($forgotten_cart_messages[$language['language_id']]['subject_manager']) ? $forgotten_cart_messages[$language['language_id']]['subject_manager'] : $text_subject_manager; ?>" id="input-subject_manager<?php echo $language['language_id']; ?>" class="form-control">
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitename; ?>">[%site_name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_customername; ?>">[%customer_name%]</span></span>
                              <?php if (isset($error_subject_manager[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_subject_manager[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-message_manager<?php echo $language['language_id']; ?>"><?php echo $entry_message; ?></label>
                            <div class="col-sm-10" id="message_manager">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][message_manager]" rows="8" id="input-message_manager<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['message_manager']) ? $forgotten_cart_messages[$language['language_id']]['message_manager'] : $text_message_manager; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_sitename; ?>">[%site_name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_logo; ?>">[%logo%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_customername; ?>">[%customer_name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_customer_email; ?>">[%customer_email%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_customer_phone; ?>">[%customer_phone%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_products; ?>">[%template_products%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_coupon; ?>">[%template_coupon%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_shipping; ?>">[%template_shipping%]</span></span>
                              <?php if (isset($error_message_manager[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_message_manager[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-template-products_manager<?php echo $language['language_id']; ?>"><?php echo $entry_template_products; ?></label>
                            <div class="col-sm-10" id="template-products_manager">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_products_manager]" rows="8" id="input-template-products_manager<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_products_manager']) ? $forgotten_cart_messages[$language['language_id']]['template_products_manager'] : $text_template_products; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_name; ?>">[%name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_model; ?>">[%model%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_options; ?>">[%options%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_image; ?>">[%image%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_quantity; ?>">[%quantity%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_price; ?>">[%price%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_sum; ?>">[%sum%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_link; ?>">[%link%]</span></span>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-template-coupon_manager<?php echo $language['language_id']; ?>"><?php echo $entry_template_coupon; ?></label>
                            <div class="col-sm-10" id="template-coupon_manager">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_coupon_manager]" rows="8" id="input-template-coupon_manager<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_coupon_manager']) ? $forgotten_cart_messages[$language['language_id']]['template_coupon_manager'] : $text_template_coupon_manager; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_coupon_code; ?>">[%coupon%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_coupon_discount; ?>">[%discount%]</span></span>
                              <?php if (isset($error_template_coupon_manager[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_template_coupon_manager[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-template-shipping_manager<?php echo $language['language_id']; ?>"><?php echo $entry_template_shipping; ?></label>
                            <div class="col-sm-10" id="template-shipping_manager">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_shipping_manager]" rows="8" id="input-template-shipping_manager<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_shipping_manager']) ? $forgotten_cart_messages[$language['language_id']]['template_shipping_manager'] : $text_template_shipping; ?></textarea>
                              <?php if (isset($error_template_shipping_manager[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_template_shipping_manager[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                      </fieldset>
                  </div>
                  <?php } ?>
                </div>
            </div>
            
            <div class="tab-pane <?php if ($open_tab == "tab-related") { ?>active<?php } ?>" id="tab-related">
              <div class="form-group">
                <label class="col-md-2 col-sm-3 control-label"><?php echo $entry_related_status; ?></label>
                <div class="col-md-10 col-sm-9">
                    <div class="btn-group" data-toggle="buttons">
                      <?php if ($forgotten_cart_related_status) { ?>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_related_status" value="1" checked="checked"> <?php echo $text_enabled; ?>
                      </label>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_related_status" value="0"> <?php echo $text_disabled; ?>
                      </label>
                      <?php } else { ?>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_related_status" value="1"> <?php echo $text_enabled; ?>
                      </label>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_related_status" value="0" checked="checked"> <?php echo $text_disabled; ?>
                      </label>
                      <?php } ?>
                    </div>
                </div>
              </div>
              <div class="form-group related_status">
                <label class="col-md-2 col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $help_condition; ?>"><?php echo $entry_related_attribute_condition; ?></span></label>
                <div class="col-md-10 col-sm-9">
                    <div class="btn-group" data-toggle="buttons">
                    <?php if ($forgotten_cart_related_attribute_condition) { ?>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_related_attribute_condition" value="1" checked="checked" /> <?php echo $text_or; ?>
                      </label>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_related_attribute_condition" value="0" /> <?php echo $text_and; ?>
                      </label>
                    <?php } else { ?>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_related_attribute_condition" value="1"  /> <?php echo $text_or; ?>
                      </label>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_related_attribute_condition" value="0" checked="checked" /> <?php echo $text_and; ?>
                      </label>
                    <?php } ?>
                    </div>
                </div>
              </div>
              <div class="form-group related_status">
                <label class="col-md-2 col-sm-3 control-label" for="input-related-attribute"><?php echo $entry_related_attribute; ?></label>
                <div class="col-md-10 col-sm-9">
                  <input type="text" value="" id="input-related-attribute" class="form-control" />
                  <div id="related-attribute" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($related_attributes as $related_attribute) { ?>
                    <div id="related-attribute<?php echo $related_attribute['attribute_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $related_attribute['attribute_group']; ?> > <?php echo $related_attribute['name']; ?>
                      <input type="hidden" name="forgotten_cart_related_attribute[]" value="<?php echo $related_attribute['attribute_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group related_status">
                <label class="col-md-2 col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $help_condition; ?>"><?php echo $entry_related_option_condition; ?></span></label>
                <div class="col-md-10 col-sm-9">
                    <div class="btn-group" data-toggle="buttons">
                    <?php if ($forgotten_cart_related_option_condition) { ?>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_related_option_condition" value="1" checked="checked" /> <?php echo $text_or; ?>
                      </label>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_related_option_condition" value="0" /> <?php echo $text_and; ?>
                      </label>
                    <?php } else { ?>
                      <label class="btn btn-primary">
                        <input type="radio" name="forgotten_cart_related_option_condition" value="1"  /> <?php echo $text_or; ?>
                      </label>
                      <label class="btn btn-primary active">
                        <input type="radio" name="forgotten_cart_related_option_condition" value="0" checked="checked" /> <?php echo $text_and; ?>
                      </label>
                    <?php } ?>
                    </div>
                </div>
              </div>
              <div class="form-group related_status">
                <label class="col-md-2 col-sm-3 control-label" for="input-related-option"><?php echo $entry_related_option; ?></label>
                <div class="col-md-10 col-sm-9">
                  <input type="text" value="" id="input-related-option" class="form-control" />
                  <div id="related-option" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($related_options as $related_option) { ?>
                    <div id="related-option<?php echo $related_option['option_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $related_option['name']; ?>
                      <input type="hidden" name="forgotten_cart_related_option[]" value="<?php echo $related_option['option_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group related_status">
                <label class="col-md-2 col-sm-3 control-label" for="input-related_price_step"><span data-toggle="tooltip" title="<?php echo $help_price_step; ?>"><?php echo $entry_related_price_step; ?></span></label>
                <div class="col-md-3 col-sm-4">
                    <div class="input-group col-sm-12">
                      <input type="text" class="form-control" name="forgotten_cart_related_price_step" id="input-related_price_step" value="<?php echo $forgotten_cart_related_price_step; ?>">
                      <div class="input-group-addon"><?php echo $currency_code; ?></div>
                    </div>
                </div>
              </div>
              <div class="form-group related_status">
                <label class="col-md-2 col-sm-3 control-label" for="input-related_limit"><?php echo $entry_related_limit; ?></label>
                <div class="col-md-3 col-sm-4">
                    <input type="text" class="form-control" name="forgotten_cart_related_limit" id="input-related_limit" value="<?php echo $forgotten_cart_related_limit; ?>">
                </div>
              </div>
              <fieldset class="related_status">
                <legend><?php echo $text_related_template_setting; ?></legend>
                    <ul class="nav nav-tabs" id="language_related">
                      <?php foreach ($languages as $language) { ?>
                      <li><a href="#language_related<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                      <?php } ?>
                    </ul>
                    <div class="tab-content">
                      <?php foreach ($languages as $language) { ?>
                      <div class="tab-pane" id="language_related<?php echo $language['language_id']; ?>">
                        <div class="form-group required">
                            <label class="col-md-2 col-sm-3 control-label" for="input-related_block<?php echo $language['language_id']; ?>"><?php echo $entry_related_block; ?></label>
                            <div class="col-md-10 col-sm-9" id="related_block">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][related_block]" rows="8" id="input-related_block<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['related_block']) ? $forgotten_cart_messages[$language['language_id']]['related_block'] : $text_related_block; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_products_count; ?>">[%products_count%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $entry_template_products; ?>">[%template_products%]</span>
                              <?php if (isset($error_related_block[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_related_block[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-md-2 col-sm-3 control-label" for="input-template-products_related<?php echo $language['language_id']; ?>"><?php echo $entry_template_products; ?></label>
                            <div class="col-md-10 col-sm-9" id="template-products_related">
                              <textarea name="forgotten_cart_messages[<?php echo $language['language_id']; ?>][template_products_related]" rows="8" id="input-template-products_related<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($forgotten_cart_messages[$language['language_id']]['template_products_related']) ? $forgotten_cart_messages[$language['language_id']]['template_products_related'] : $text_template_products_related; ?></textarea>
                              <span style="font-size: 11px;" id="help_el"><?php echo $text_help_el; ?>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_name; ?>">[%name%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_model; ?>">[%model%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_image; ?>">[%image%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_price; ?>">[%price%]</span>&nbsp;&nbsp; <span data-toggle="tooltip" title="<?php echo $text_help_product_link; ?>">[%link%]</span></span>
                              <?php if (isset($error_template_products_related[$language['language_id']])) { ?>
                              <div class="text-danger"><?php echo $error_template_products_related[$language['language_id']]; ?></div>
                              <?php } ?>
                            </div>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
              </fieldset>
            </div>
            
            <div class="tab-pane <?php if ($open_tab == "tab-customers") { ?>active<?php } ?>" id="tab-customers">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <td class="text-left"><?php echo $column_customer; ?></td>
                          <td class="text-left"><?php echo $column_cart; ?></td>
                          <td class="text-center"><?php echo $column_action; ?></td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($customers) { ?>
                        <?php foreach ($customers as $customer) { ?>
                        <tr>
                          <td class="text-left">
                            <table class="table table-bordered">
                              <tbody>
                                <tr>
                                    <td><i class="fa fa-user" data-toggle="tooltip" title="<?php echo $text_help_customername; ?>"></i> <?php echo $customer['name']; ?>
                                    <td><?php if ($customer['first_visit']) { ?><i class="fa fa-sign-in" data-toggle="tooltip" title="<?php echo $text_first_visit; ?>"></i> <?php echo $customer['first_visit']; ?><?php } ?></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope" data-toggle="tooltip" title="<?php echo $text_help_customer_email; ?>"></i> <?php echo $customer['email']; ?></td>
                                    <td><i class="fa fa-sign-out" data-toggle="tooltip" title="<?php echo $text_last_visit; ?>"></i> <?php echo $customer['last_visit']; ?></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-phone"  data-toggle="tooltip" title="<?php echo $text_help_customer_phone; ?>"></i> <?php echo $customer['telephone']; ?></td>
                                    <td><?php if ($customer['time_spent']) { ?><i class="fa fa-clock-o" data-toggle="tooltip" title="<?php echo $text_time_spent; ?>"></i> <?php echo $customer['time_spent']; ?><?php } ?></td>
                                </tr>
                                <?php if ($customer['last_page']) { ?>
                                <tr>
                                    <td colspan="2" style="word-wrap: break-word;"><i class="fa fa-share-square-o"  data-toggle="tooltip" title="<?php echo $column_last_page; ?>"></i> <a href="<?php echo $customer['last_page']; ?>" target="_blank"><?php echo $customer['last_page']; ?></a></td>
                                </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                          </td>
                          <td class="text-left">
                            <table class="table table-bordered">
                              <tbody>
                              <?php foreach ($customer['products'] as $product) { ?>
                                <tr>
                                  <td>
                                    <a href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a><br>
                                    <small><?php echo $product['options']; ?></small>
                                  </td>
                                  <td style="white-space: nowrap;">x <?php echo $product['quantity']; ?></td>
                                  <td style="white-space: nowrap;"><?php echo $product['total']; ?></td>
                                </tr>
                              <?php } ?>
                                <tr>
                                  <td colspan="2"><?php echo $text_total; ?></td>
                                  <td style="white-space: nowrap;"><?php echo $customer['total']; ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                          <td class="text-center" style="width: 185px;">
                            <?php if ($customer['mail_status'] == 0) { ?>
                            <a href="javascript://" onclick="$('#customer_id').val('<?php echo $customer['customer_id']; ?>');$('#mail_type').val('1');$('#form-forgotten-cart-mail').submit();" data-toggle="tooltip" title="" class="btn btn-warning" data-original-title="<?php echo $text_send_message; ?>"><i class="fa fa-envelope"></i></a>
                            <button type="button" class="btn btn-warning" data-toggle="tooltip" data-original-title="<?php echo $text_send_message_repeated; ?>" disabled><i class="fa fa-envelope-o"></i></button>
                            <?php } elseif ($customer['mail_status'] == 1) { ?>
                            <button type="button" class="btn btn-warning" data-toggle="tooltip" data-original-title="<?php echo $text_send_message; ?>" disabled><i class="fa fa-envelope"></i></button>
                            <a href="javascript://" onclick="$('#customer_id').val('<?php echo $customer['customer_id']; ?>');$('#mail_type').val('2');$('#form-forgotten-cart-mail').submit();" data-toggle="tooltip" title="" class="btn btn-warning" data-original-title="<?php echo $text_send_message_repeated; ?>"><i class="fa fa-envelope-o"></i></a>
                            <?php } else { ?>
                            <button type="button" class="btn btn-warning" data-toggle="tooltip" data-original-title="<?php echo $text_send_message; ?>" disabled><i class="fa fa-envelope"></i></button>
                            <button type="button" class="btn btn-warning" data-toggle="tooltip" data-original-title="<?php echo $text_send_message_repeated; ?>" disabled><i class="fa fa-envelope-o"></i></button>
                            <?php } ?>
                            <a href="<?php echo $customer['edit']; ?>" target="_blank" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="<?php echo $text_customer_edit; ?>"><i class="fa fa-eye"></i></a>
                            <a href="<?php echo $customer['remove']; ?>" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="<?php echo $text_remove; ?>"><i class="fa fa-trash"></i></a></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                          <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                </div>
                <div class="row">
                  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div style="text-align: center;"><?php echo $license_content; ?></div>
</div>
              <form action="<?php echo $action_mail; ?>" method="post" enctype="multipart/form-data" id="form-forgotten-cart-mail">
              <input type="hidden" id="customer_id" name="customer_id" value="">
              <input type="hidden" id="mail_type" name="mail_type" value="">
              </form>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript"><!--
$('textarea.summernote').each(function(i) {
    $(this).summernote({height: 300});
});

$('#language a:first').tab('show');
$('#language_related a:first').tab('show');
//--></script>
  <script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
    html  = '<tr id="discount-row' + discount_row + '">';
    html += '  <td class="text-left"><div class="input-group col-sm-12"><div class="input-group-addon"><?php echo $text_from; ?></div><input type="text" name="forgotten_cart_discounts[' + discount_row + '][sum]" value="" class="form-control" /></div></td>';
    html += '  <td class="text-left">';
    html += '      <select name="forgotten_cart_discounts[' + discount_row + '][type]" class="form-control">';
    html += '          <option value="P"><?php echo $text_percent; ?></option>';
    html += '          <option value="F"><?php echo $text_amount; ?></option>';
    html += '      </select>';
    html += '  </td>';
    html += '  <td class="text-left">';
    html += '      <input type="text" name="forgotten_cart_discounts[' + discount_row + '][discount]" value="0" class="form-control" />';
    html += '  </td>';
    html += '  <td class="text-center">';
    html += '      <label class="radio-inline"><input type="radio" name="forgotten_cart_discounts[' + discount_row + '][shipping]" value="1" /> <?php echo $text_yes; ?></label>';
    html += '      <label class="radio-inline"><input type="radio" name="forgotten_cart_discounts[' + discount_row + '][shipping]" value="0" checked="checked" /> <?php echo $text_no; ?></label>';
    html += '  </td>';
    html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_discount_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';
    
    $('#discount tbody').append(html);
    
    discount_row++;
}

// Related attribute
$('input#input-related-attribute').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=module/forgotten_cart/attribute_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['attribute_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input#input-related-attribute').val('');

        $('#related-attribute' + item['value']).remove();

        $('#related-attribute').append('<div id="related-attribute' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="forgotten_cart_related_attribute[]" value="' + item['value'] + '" /></div>');
    }
});

$('#related-attribute').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});

// Related option
$('input#input-related-option').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=module/forgotten_cart/option_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['option_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input#input-related-option').val('');

        $('#related-option' + item['value']).remove();

        $('#related-option').append('<div id="related-option' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="forgotten_cart_related_option[]" value="' + item['value'] + '" /></div>');
    }
});

$('#related-option').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});
//--></script>
<style>
span#help_el span{
    cursor: pointer;
}
</style>
<script type="text/javascript">
    $("span#help_el span").click(function(event) {
        var range = document.createRange();
        range.selectNode(this);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    });
    
    function auto_send(){
        if($('input[name=\'forgotten_cart_auto_send\']:checked').val() == 1){
            $(".auto_send").show();
            $(".auto_send_cron").hide();
        }else if($('input[name=\'forgotten_cart_auto_send\']:checked').val() == 2){
            $(".auto_send").show();
            $(".auto_send_cron").show();
        }else{
            $(".auto_send").hide();
            $(".auto_send_cron").hide();
        }
    }
    
    function repeated_message(){
        if($('input[name=\'forgotten_cart_repeated_message\']:checked').val() == 1 && $('input[name=\'forgotten_cart_auto_send\']:checked').val() != 0 && $('input[name=\'forgotten_cart_customer_notifi\']:checked').val() != 0){
            $(".repeated_message").show();
        }else{
            $(".repeated_message").hide();
        }
    }
    
    function manager_notifi(){
        if($('input[name=\'forgotten_cart_manager_notifi\']:checked').val() == 1 && $('input[name=\'forgotten_cart_auto_send\']:checked').val() != 0){
            $(".manager_notifi").show();
        }else{
            $(".manager_notifi").hide();
        }
    }
    
    function customer_notifi(){
        if($('input[name=\'forgotten_cart_customer_notifi\']:checked').val() == 1 && $('input[name=\'forgotten_cart_auto_send\']:checked').val() != 0){
            $(".customer_notifi").show();
        }else{
            $(".customer_notifi").hide();
        }
    }
    
    function related_status(){
        if($('input[name=\'forgotten_cart_related_status\']:checked').val() == 1){
            $(".related_status").show();
        }else{
            $(".related_status").hide();
        }
    }
    
    $("form#form-forgotten-cart input[type=\'radio\']").change(function() {
        auto_send();
        manager_notifi();
        customer_notifi();
        repeated_message();
        related_status();
    });

    auto_send();
    manager_notifi();
    customer_notifi();
    repeated_message();
    related_status();
</script>
<?php echo $footer; ?>