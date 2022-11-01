<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <?php if( !isset($license_error) ) { ?>
                <button type="submit" name="action" value="save" form="form" data-toggle="tooltip"
                        title="<?php echo $button_save; ?>" class="btn btn-primary"><i
                            class="fa fa-save"></i> <?php echo $button_save; ?></button>
                <button type="submit" name="action" value="save_and_close" form="form" data-toggle="tooltip"
                        title="<?php echo $button_save_and_close; ?>" class="btn btn-default"><i
                            class="fa fa-save"></i> <?php echo $button_save_and_close; ?></button>
                <?php } else { ?>
                <a href="<?php echo $recheck; ?>" data-toggle="tooltip" title="<?php echo $button_recheck; ?>"
                   class="btn btn-primary"/><i class="fa fa-check"></i> <?php echo $button_recheck; ?></a>
                <?php } ?>
                <a href="<?php echo $close; ?>" data-toggle="tooltip" title="<?php echo $button_close; ?>"
                   class="btn btn-default"><i class="fa fa-close"></i> <?php echo $button_close; ?></a>
            </div>

            <img width="36" height="36" style="float:left" src="view/image/neoseo.png" alt=""/>
            <h1><?php echo $heading_title_raw . " " . $text_module_version; ?></h1>

            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>

        </div>
    </div>

    <div class="container-fluid">

        <?php if ($error_warning) { ?>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <?php if (isset($success) && $success) { ?>
        <div class="alert alert-success">
            <i class="fa fa-check-circle"></i>
            <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-body">

                <ul class="nav nav-tabs">

                    <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    <?php if( !isset($license_error) ) { ?>
                    <li><a href="#tab-contact" data-toggle="tab"><?php echo $tab_contact; ?></a></li>
                    <li><a href="#tab-lead" data-toggle="tab"><?php echo $tab_lead; ?></a></li>
                    <li><a href="#tab-deal" data-toggle="tab"><?php echo $tab_deal; ?></a></li>
                    <li><a href="#tab-logs" data-toggle="tab"><?php echo $tab_logs; ?></a></li>
                    <?php } ?>
                    <li><a href="#tab-support" data-toggle="tab"><?php echo $tab_support; ?></a></li>
                    <li><a href="#tab-license" data-toggle="tab"><?php echo $tab_license; ?></a></li>
                </ul>

                <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <?php if( !isset($license_error) ) { ?>
                            <?php $widgets->dropdown('status',array( 0 => $text_disabled, 1 => $text_enabled)); ?>
                            <?php $widgets->dropdown('domain', $domains); ?>
                            <?php $widgets->input('portal_name'); ?>
                            <?php $widgets->input('id_user'); ?>
                            <?php $widgets->input('secret_code'); ?>
                            <?php } else { ?>
                            <?php echo $license_error; ?>
                            <?php } ?>
                        </div>

                        <div class="tab-pane" id="tab-contact">
                            <?php if( !isset($license_error) ) { ?>
                            <?php $widgets->dropdown('add_contact',array( 0 => $text_disabled, 1 => $text_enabled)); ?>
                            <?php $widgets->dropdown('contact_user_id', $users); ?>
                            <?php $widgets->dropdown('source_contact', $sources); ?>
                            <?php $widgets->dropdown('type_contact_default', $contact_types); ?>
                            <legend><?php echo $text_match_table_contact_type; ?></legend>
                            <table id="contact-to-customer"
                                   class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td class="text-left"><?php echo $column_customer_group; ?></td>
                                    <td class="text-left"><?php echo $column_type_contact; ?></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $type_contact_to_customer_group_row = 0; ?>
                                <?php foreach ($group_to_contact as $customer_group_id => $contact_type) { ?>
                                <tr id="contact-to-customer-row<?php echo $type_contact_to_customer_group_row; ?>">
                                    <td class="text-left">
                                        <select name="group_to_contact[<?php echo $type_contact_to_customer_group_row; ?>][customer_group_id]"
                                               class="form-control">
                                            <?php foreach ($customer_group as $key => $group_name) { ?>
                                            <option value="<?php echo $key; ?>"
                                            <?php  if ($customer_group_id == $key) { ?> selected="selected" <?php } ?>><?php echo $group_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="text-left">
                                        <select name="group_to_contact[<?php echo $type_contact_to_customer_group_row; ?>][contact_type]"
                                                class="form-control">
                                            <?php foreach ($contact_types as $key => $type) { ?>
                                            <option value="<?php echo $key; ?>"
                                            <?php  if ($contact_type == $key) { ?> selected="selected" <?php } ?>><?php echo $type; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="text-left">
                                        <button type="button"
                                                onclick="$('#contact-to-customer-row<?php echo $type_contact_to_customer_group_row; ?>').remove();"
                                                data-toggle="tooltip" title="<?php echo $button_remove; ?>"
                                                class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                                    </td>
                                </tr>
                                <?php $type_contact_to_customer_group_row ++; ?>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-left">
                                        <button type="button" onclick="addTypeContactCustomerGroup();"
                                                data-toggle="tooltip" title="<?php echo $button_add; ?>"
                                                class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                            <?php } else { ?>
                            <?php echo $license_error; ?>
                            <?php } ?>
                        </div>

                        <div class="tab-pane" id="tab-lead">
                            <?php if( !isset($license_error) ) { ?>
                            <?php $widgets->dropdown('add_lead_register',array( 0 => $text_disabled, 1 => $text_enabled)); ?>
                            <?php $widgets->dropdown('lead_user_id', $users); ?>
                            <?php $widgets->dropdown('source_lead_register', $sources); ?>
                            <?php $widgets->dropdown('add_lead_neoseo_catch_contacts',array( 0 => $text_disabled, 1 =>
                            $text_enabled)); ?>
                            <?php $widgets->dropdown('source_lead_neoseo_catch_contacts', $sources); ?>
                            <?php $widgets->dropdown('add_lead_neoseo_notify_when_available',array( 0 => $text_disabled,
                            1 => $text_enabled)); ?>
                            <?php $widgets->dropdown('source_lead_neoseo_notify_when_available', $sources); ?>
                            <?php } else { ?>
                            <?php echo $license_error; ?>
                            <?php } ?>
                        </div>

                        <div class="tab-pane" id="tab-deal">
                            <?php if( !isset($license_error) ) { ?>
                            <?php $widgets->dropdown('add_deal_order',array( 0 => $text_disabled, 1 => $text_enabled)); ?>
                            <?php $widgets->dropdown('deal_user_id', $users); ?>
                            <?php $widgets->dropdown('deal_stage_default', $deal_stage); ?>
                            <?php $widgets->dropdown('deal_type_default', $deal_types); ?>
                            <?php $widgets->textarea('deal_extra_property'); ?>
                            <?php $widgets->checklist('unload_options', $options); ?>
                            <?php $widgets->checklist('unload_order_status', $order_statuses); ?>
                            <legend><?php echo $text_match_table_deal_stage; ?></legend>
                            <table id="order-to-deal"
                                   class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td class="text-left"><?php echo $column_order_status; ?></td>
                                    <td class="text-left"><?php echo $column_deal_stage; ?></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $order_status_to_deal_row = 0; ?>
                                <?php foreach ($order_status_to_deal_stage as $order_status_id => $stage_deal) { ?>
                                <tr id="order-to-deal-row<?php echo $order_status_to_deal_row; ?>">
                                    <td class="text-left">
                                        <select name="order_status_to_deal_stage[<?php echo $order_status_to_deal_row; ?>][order_status_id]"
                                                class="form-control">
                                            <?php foreach ($order_statuses as $key => $status) { ?>
                                            <option value="<?php echo $key; ?>"
                                            <?php  if ($order_status_id == $key) { ?> selected="selected" <?php } ?>><?php echo $status; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="text-left">
                                        <select name="order_status_to_deal_stage[<?php echo $order_status_to_deal_row; ?>][deal_stage]"
                                                class="form-control">
                                            <?php foreach ($deal_stage as $key => $deal) { ?>
                                            <option value="<?php echo $key; ?>"
                                            <?php  if ($stage_deal == $key) { ?> selected="selected" <?php } ?>><?php echo $deal; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="text-left">
                                        <button type="button"
                                                onclick="$('#order-to-deal-row<?php echo $order_status_to_deal_row; ?>').remove();"
                                                data-toggle="tooltip" title="<?php echo $button_remove; ?>"
                                                class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                                    </td>
                                </tr>
                                <?php $order_status_to_deal_row ++; ?>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-left">
                                        <button type="button" onclick="addOrderStatusToDealStage();"
                                                data-toggle="tooltip" title="<?php echo $button_add; ?>"
                                                class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                            <legend><?php echo $text_match_table_deal_type; ?></legend>
                            <table id="category-to-deal"
                                   class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td class="text-left"><?php echo $column_category; ?></td>
                                    <td class="text-left"><?php echo $column_deal_type; ?></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $category_to_deal_row = 0; ?>
                                <?php foreach ($category_to_deal_type as $category_id => $deal_type) { ?>
                                <tr id="category-to-deal-row<?php echo $category_to_deal_row; ?>">
                                    <td class="text-left">
                                        <select name="category_to_deal_type[<?php echo $category_to_deal_row; ?>][category_id]"
                                                class="form-control">
                                            <?php foreach ($categories as $key => $category) { ?>
                                            <option value="<?php echo $key; ?>"
                                            <?php  if ($category_id == $key) { ?> selected="selected" <?php } ?>><?php echo $category; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="text-left">
                                        <select name="category_to_deal_type[<?php echo $category_to_deal_row; ?>][deal_type]"
                                                class="form-control">
                                            <?php foreach ($deal_types as $key => $type) { ?>
                                            <option value="<?php echo $key; ?>"
                                            <?php  if ($deal_type == $key) { ?> selected="selected" <?php } ?>><?php echo $type; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="text-left">
                                        <button type="button"
                                                onclick="$('#category-to-deal-row<?php echo $category_to_deal_row; ?>').remove();"
                                                data-toggle="tooltip" title="<?php echo $button_remove; ?>"
                                                class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                                    </td>
                                </tr>
                                <?php $category_to_deal_row ++; ?>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-left">
                                        <button type="button" onclick="addCategoryToDealType();"
                                                data-toggle="tooltip" title="<?php echo $button_add; ?>"
                                                class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                            <?php } else { ?>
                            <?php echo $license_error; ?>
                            <?php } ?>
                        </div>

                        <?php if( !isset($license_error) ) { ?>
                        <div class="tab-pane" id="tab-logs">
                            <?php $widgets->debug_download_logs('debug',array( 0 => $text_disabled, 1 => $text_enabled),
                            $clear, $download, $button_clear_log, $button_download_log); ?>
                            <textarea
                                    style="width: 100%; height: 300px; padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF; overflow: scroll;"><?php echo $logs; ?></textarea>
                        </div>
                        <?php } ?>

                        <div class="tab-pane" id="tab-support">
                            <?php echo $mail_support; ?>
                        </div>

                        <div class="tab-pane" id="tab-license">
                            <?php echo $module_licence; ?>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>
<script type="text/javascript"><!--
    var category_to_deal_row = '<?php echo $category_to_deal_row; ?>';

    function addCategoryToDealType() {
        html = '<tr id="category-to-deal-row' + category_to_deal_row + '">';
        html += '<td class="text-left">';
        html += '<select name="category_to_deal_type[' + category_to_deal_row + '][category_id]" class="form-control">';
    <?php foreach($categories as $key => $category) { ?>
            html += '<option value="<?php echo $key; ?>"><?php echo $category; ?></option>';
        <?php } ?>
        html += '</select>';
        html += '</td>';
        html += '<td class="text-left">';
        html += '<select name="category_to_deal_type[' + category_to_deal_row + '][deal_type]" class="form-control">';
    <?php foreach($deal_types as $key => $deal) { ?>
            html += '<option value="<?php echo $key; ?>"><?php echo $deal; ?></option>';
        <?php } ?>
        html += '</select>';
        html += '</td>';
        html += '  <td class="text-left">';
        html += '<button type="button" onclick="$(\'#category-to-deal-row' + category_to_deal_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
        html += '</td>';
        html += '</tr>';

        $('#category-to-deal tbody').append(html);

        order_status_to_deal_row++;
    }
    //--></script>
<script type="text/javascript"><!--
    var order_status_to_deal_row = '<?php echo $order_status_to_deal_row; ?>';

    function addOrderStatusToDealStage() {
        html = '<tr id="order-to-deal-row' + order_status_to_deal_row + '">';
        html += '<td class="text-left">';
        html += '<select name="order_status_to_deal_stage[' + order_status_to_deal_row + '][order_status_id]" class="form-control">';
    <?php foreach($order_statuses as $key => $status) { ?>
            html += '<option value="<?php echo $key; ?>"><?php echo $status; ?></option>';
        <?php } ?>
        html += '</select>';
        html += '</td>';
        html += '<td class="text-left">';
        html += '<select name="order_status_to_deal_stage[' + order_status_to_deal_row + '][deal_stage]" class="form-control">';
    <?php foreach($deal_stage as $key => $deal) { ?>
            html += '<option value="<?php echo $key; ?>"><?php echo $deal; ?></option>';
        <?php } ?>
        html += '</select>';
        html += '</td>';
        html += '  <td class="text-left">';
        html += '<button type="button" onclick="$(\'#order-to-deal-row' + order_status_to_deal_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
        html += '</td>';
        html += '</tr>';

        $('#order-to-deal tbody').append(html);

        order_status_to_deal_row++;
    }
    //--></script>
<script type="text/javascript"><!--
    var type_contact_to_customer_group_row = '<?php echo $type_contact_to_customer_group_row; ?>';

    function addTypeContactCustomerGroup() {
        html = '<tr id="contact-to-customer-row' + type_contact_to_customer_group_row + '">';
        html += '<td class="text-left">';
        html += '<select name="group_to_contact[' + type_contact_to_customer_group_row + '][customer_group_id]" class="form-control">';
        <?php foreach($customer_group as $key => $group_name) { ?>
            html += '<option value="<?php echo $key; ?>"><?php echo $group_name; ?></option>';
        <?php } ?>
        html += '</select>';
        html += '</td>';
        html += '<td class="text-left">';
        html += '<select name="group_to_contact[' + type_contact_to_customer_group_row + '][contact_type]" class="form-control">';
        <?php foreach($contact_types as $key => $type) { ?>
            html += '<option value="<?php echo $key; ?>"><?php echo $type; ?></option>';
        <?php } ?>
        html += '</select>';
        html += '</td>';
        html += '  <td class="text-left">';
        html += '<button type="button" onclick="$(\'#contact-to-customer-row' + type_contact_to_customer_group_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
        html += '</td>';
        html += '</tr>';

        $('#contact-to-customer tbody').append(html);

        type_contact_to_customer_group_row++;
    }
    //--></script>
<script type="text/javascript"><!--
    if (window.location.hash.indexOf('#tab') == 0 && $("[href=" + window.location.hash + "]").length) {
        $(".panel-body > .nav-tabs li").removeClass("active");
        $("[href=" + window.location.hash + "]").parents('li').addClass("active");
        $(".panel-body:first .tab-content:first .tab-pane:first").removeClass("active");
        $(window.location.hash).addClass("active");
    }
    $(".nav-tabs li a").click(function () {
        var url = $(this).prop('href');
        window.location.hash = url.substring(url.indexOf('#'));
    });

    // Специальный фикс системной функции, поскольку даниель понятия не имеет о том что в url может быть еще и hash
    // и по итогу этот hash становится частью token
    function getURLVar(key) {
        var value = [];

        var url = String(document.location);
        if (url.indexOf('#') != -1) {
            url = url.substring(0, url.indexOf('#'));
        }
        var query = url.split('?');

        if (query[1]) {
            var part = query[1].split('&');

            for (i = 0; i < part.length; i++) {
                var data = part[i].split('=');

                if (data[0] && data[1]) {
                    value[data[0]] = data[1];
                }
            }

            if (value[key]) {
                return value[key];
            } else {
                return '';
            }
        }
    }
    //--></script>
<?php echo $footer; ?>