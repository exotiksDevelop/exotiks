<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-ms_integration" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach($breadcrumbs as $breadcrumb): ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if($error_warning): ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>
        <?php if($success): ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
                    <?php if($ms): ?>
                    <li><a href="#tab-products" data-toggle="tab"><?php echo $tab_products; ?></a></li>
                    <li><a href="#tab-category" data-toggle="tab"><?php echo $tab_category; ?></a></li>
                    <li><a href="#tab-orders" data-toggle="tab"><?php echo $tab_orders; ?></a></li>
                    <li><a href="#tab-import" data-toggle="tab"><?php echo $tab_import; ?></a></li>
                    <?php endif; ?>
                </ul>
                <form action="<?php echo $delete_links; ?>" method="post" enctype="multipart/form-data" id="form-ms_delete_links" class="form-horizontal">
                </form>
                <form action="<?php echo $delete_links_2; ?>" method="post" enctype="multipart/form-data" id="form-ms_delete_links_2" class="form-horizontal">
                </form>
                <form action="<?php echo $delete_links_3; ?>" method="post" enctype="multipart/form-data" id="form-ms_delete_links_3" class="form-horizontal">
                </form>
                <form action="<?php echo $link_products; ?>" method="post" enctype="multipart/form-data" id="form-ms_integration_link_product" class="form-horizontal">
                </form>
                <form action="<?php echo $link_categories; ?>" method="post" enctype="multipart/form-data" id="form-ms_category_link" class="form-horizontal">
                </form>
                <form action="<?php echo $link_orders; ?>" method="post" enctype="multipart/form-data" id="form-ms_orders_link" class="form-horizontal">
                </form>
                <form action="<?php echo $link_repair_db; ?>" method="post" enctype="multipart/form-data" id="form-repair_db" class="form-horizontal">
                </form>
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ms_integration" class="form-horizontal">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-setting">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_status" id="input-status" class="form-control">
                                        <?php if($settings["ms_integration_status"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_status; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms-login"><?php echo $entry_ms_login; ?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="ms_integration_ms_login" id="input-ms-login" class="form-control" value="<?php echo $settings['ms_integration_ms_login']; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_login; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms-password"><?php echo $entry_ms_password; ?></label>
                                <div class="col-sm-4">
                                    <input type="password" name="ms_integration_ms_password" id="input-ms-password" class="form-control" value="<?php echo $settings['ms_integration_ms_password']; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_password; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-license"><?php echo $entry_licence; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="ms_integration_licence" id="input-license" class="form-control" value="<?php echo $settings['ms_integration_licence']; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <h4><?php echo $check_licence; ?></h4>
                                </div>
                            </div>

                            <?php if($ms): ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms-store"><?php echo $entry_ms_store; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_store" id="input-ms-store" class="form-control">
                                        <option value="">По всем складам</option>
                                        <?php foreach($setting_store_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_ms_store"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_store; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-cron-time"><?php echo $entry_cron_time; ?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="ms_integration_cron_time" id="input-cron-time" class="form-control" value="<?php echo $settings['ms_integration_cron_time']; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_cron_time; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-language"><?php echo $entry_language; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_language" id="input-language" class="form-control">
                                        <?php foreach($setting_language_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_language"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_language; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if($ms): ?>
                        <div class="tab-pane" id="tab-products">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms-id"><?php echo $entry_ms_id; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_id" id="input-ms-id" class="form-control">
                                        <?php foreach($setting_ms_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_ms_id"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_id; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-oc-id"><?php echo $entry_oc_id; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_oc_id" id="input-oc-id" class="form-control">
                                        <?php foreach($setting_oc_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_oc_id"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_oc_id; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-create-product"><?php echo $entry_create_product; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_create_product" id="input-create-product" class="form-control">
                                        <?php if($settings["ms_integration_create_product"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_create_product; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-stock"><?php echo $entry_stock; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_stock" id="input-stock" class="form-control">
                                        <?php if($settings["ms_integration_stock"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_stock; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-stock_out_status"><?php echo $entry_stock_out_status; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_stock_out_status" id="input-stock_out_status" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_stock_status_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_stock_out_status"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_stock_out_status; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-name_update"><?php echo $entry_name_update; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_name_update" id="input-name_update" class="form-control">
                                        <?php if($settings["ms_integration_name_update"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_name_update; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-description_update"><?php echo $entry_description_update; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_description_update" id="input-description_update" class="form-control">
                                        <?php if($settings["ms_integration_description_update"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_description_update; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-price_update"><?php echo $entry_price_update; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_price_update" id="input-price_update" class="form-control">
                                        <?php if($settings["ms_integration_price_update"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_price_update; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms_price_type"><?php echo $entry_ms_price_type; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_price_type" id="input-ms_price_type" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_price_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_ms_price_type"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_price_type; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-weight_update"><?php echo $entry_weight_update; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_weight_update" id="input-weight_update" class="form-control">
                                        <?php if($settings["ms_integration_weight_update"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_weight_update; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ean_update"><?php echo $entry_ean_update; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ean_update" id="input-ean_update" class="form-control">
                                        <?php if($settings["ms_integration_ean_update"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ean_update; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-image_update"><?php echo $entry_image_update; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_image_update" id="input-image_update" class="form-control">
                                        <?php if($settings["ms_integration_image_update"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_image_update; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-subtract_create"><?php echo $entry_subtract_create; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_subtract_create" id="input-subtract_create" class="form-control">
                                        <?php if($settings["ms_integration_subtract_create"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_subtract_create; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-shipping_create"><?php echo $entry_shipping_create; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_shipping_create" id="input-shipping_create" class="form-control">
                                        <?php if($settings["ms_integration_shipping_create"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_shipping_create; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-stock_status_create"><?php echo $entry_stock_status_create; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_stock_status_create" id="input-stock_status_create" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_stock_status_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_stock_status_create"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_stock_status_create; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-modifications"><?php echo $entry_modifications; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_modifications" id="input-modifications" class="form-control">
                                        <?php if($settings["ms_integration_modifications"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_modifications; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-create_product_option"><?php echo $entry_create_product_option; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_create_product_option" id="input-create_product_option" class="form-control">
                                        <?php if($settings["ms_integration_create_product_option"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_create_product_option; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-create_option"><?php echo $entry_create_option; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_create_option" id="input-create_option" class="form-control">
                                        <?php if($settings["ms_integration_create_option"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_create_option; ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-category">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-categories"><?php echo $entry_categories; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_categories" id="input-categories" class="form-control">
                                        <?php if($settings["ms_integration_categories"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_categories; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-category_link"><?php echo $entry_category_link; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_category_link" id="input-category_link" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_category_link as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_category_link"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_category_link; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-create_category"><?php echo $entry_create_category; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_create_category" id="input-create_category" class="form-control">
                                        <?php if($settings["ms_integration_create_category"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_create_category; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-category_name_update"><?php echo $entry_category_name_update; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_category_name_update" id="input-category_name_update" class="form-control">
                                        <?php if($settings["ms_integration_category_name_update"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_category_name_update; ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-orders">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms-organization"><?php echo $entry_ms_organization; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_organization" id="input-ms-organization" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_organization_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_ms_organization"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_organization; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms-order-store"><?php echo $entry_ms_store; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_order_store" id="input-ms-order-store" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_store_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_ms_order_store"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_store; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms-agent"><?php echo $entry_ms_agent; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_agent" id="input-ms-agent" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_agent_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_ms_agent"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_agent; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-agent-search"><?php echo $entry_agent_search; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_agent_search" id="input-agent-search" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_agent_search_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_agent_search"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_agent_search; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms-state"><?php echo $entry_ms_state; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_state" id="input-ms-state" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_state_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_ms_state"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_state; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-reserve"><?php echo $entry_reserve; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_reserve" id="input-reserve" class="form-control">
                                        <?php if($settings["ms_integration_reserve"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_reserve; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-vatE"><?php echo $entry_ms_vatE; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_vatE" id="input-vatE" class="form-control">
                                        <?php if($settings["ms_integration_ms_vatE"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_vatE; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-vatI"><?php echo $entry_ms_vatI; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_vatI" id="input-vatI" class="form-control">
                                        <?php if($settings["ms_integration_ms_vatI"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_vatI; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-applicable"><?php echo $entry_ms_applicable; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_applicable" id="input-applicable" class="form-control">
                                        <?php if($settings["ms_integration_ms_applicable"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_applicable; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-order-prefix"><?php echo $entry_order_prefix; ?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="ms_integration_order_prefix" id="input-order-prefix" class="form-control" value="<?php echo $settings['ms_integration_order_prefix']; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_order_prefix; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-order-suffix"><?php echo $entry_order_suffix; ?></label>
                                <div class="col-sm-4">
                                    <input type="text" name="ms_integration_order_suffix" id="input-order-suffix" class="form-control" value="<?php echo $settings['ms_integration_order_suffix']; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_order_suffix; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-ms_shipping"><?php echo $entry_ms_shipping; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_ms_shipping" id="input-ms_shipping" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_shipping_select as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_ms_shipping"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_ms_shipping; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-order_link"><?php echo $entry_order_link; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_order_link" id="input-order_link" class="form-control">
                                        <option value=""></option>
                                        <?php foreach($setting_order_link as $key=>$name): ?>
                                        <?php if($key==$settings["ms_integration_order_link"]): ?>
                                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $name; ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_order_link; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-old_order_create"><?php echo $entry_old_order_create; ?></label>
                                <div class="col-sm-4">
                                    <select name="ms_integration_old_order_create" id="input-old_order_create" class="form-control">
                                        <?php if($settings["ms_integration_old_order_create"]): ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php else: ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $help_old_order_create; ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-import">
                            <div class="form-group">
                                <h3 class="col-sm-12">
                                    <?php echo $help_save_setting; ?>
                                </h3>
                            </div>
                            <?php if(integrationOn): ?>
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <button id="link-btn" class="btn btn-primary" form="form-ms_integration_link_product"><?php echo $import_text; ?></button>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" style="text-align: left" for="link-btn"><?php echo $help_link_product; ?></label><br>
                                    <?php echo $products_count; ?>
                                    <?php echo $modifications_count; ?>
                                    <?php echo $bundles_count; ?>
                                </div>
                                <div class="col-sm-2">
                                    <button id="delete-btn" class="btn btn-danger" onclick="if (confirm('Удалить?')) return true; else return false" form="form-ms_delete_links"><?php echo $delete_text; ?></button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <button id="category-btn" class="btn btn-primary" form="form-ms_category_link"><?php echo $category_text; ?></button>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" style="text-align: left" for="category-btn"><?php echo $help_link_category; ?></label><br>
                                    <?php echo $category_count; ?>
                                </div>
                                <div class="col-sm-2">
                                    <button id="delete-btn-2" class="btn btn-danger" onclick="if (confirm('Удалить?')) return true; else return false" form="form-ms_delete_links_2"><?php echo $delete_text; ?></button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <h4><?php echo $help_cron; ?></h4>
                                    <?php echo $cron_link; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <button id="orders-btn" class="btn btn-primary" form="form-ms_orders_link"><?php echo $orders_text; ?></button>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" style="text-align: left" for="orders-btn"><?php echo $help_link_orders; ?></label><br>
                                    <?php echo $orders_count; ?>
                                </div>
                                <div class="col-sm-2">
                                    <button id="delete-btn-3" class="btn btn-danger" onclick="if (confirm('Удалить?')) return true; else return false" form="form-ms_delete_links_3"><?php echo $delete_text; ?></button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <h4><?php echo $help_cron_2; ?></h4>
                                    <?php echo $cron_link_2; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <button id="repair-btn" class="btn btn-primary" onclick="if (confirm('Уверены?')) return true; else return false" form="form-repair_db"><?php echo $repair_text; ?></button>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" style="text-align: left" for="repair-btn"><?php echo $help_repair_db; ?></label>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </form>
                <h4 class="col-sm-12" style="text-align: center">
                    <?php echo $version; ?>
                </h4>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>