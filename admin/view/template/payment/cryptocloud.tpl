<?php echo $header; ?><?php echo $column_left; ?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
                </div>
                <img src="view/image/payment/cryptocloud.png" alt="<?php echo $heading_title; ?>">
                <ul class="breadcrumb">
                    <?php foreach($breadcrumbs as $_key => $breadcrumb) { ?>
                        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit; ?></h3>
                </div>
                <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">

                        <div class="panel-body">
                            <div class="col-sm-2" style="text-align: right;margin-left: -15px;margin-right: -15px;"><b> </b></div>
                            <div class="col-sm-10" style="padding-left: 25px;">
                                <a href="https://cryptocloud.pro/app/registration" target="_blank">Зарегистрировать сайт в платежной системе CRYPTOCLOUD >>></a>

                            </div>
                        </div>



                        <div class="form-group required<?php if ($error_apikey) { ?> has-error<?php } ?>">
                            <label class="col-sm-2 control-label"><?php echo $entry_apikey; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="cryptocloud_apikey" value="<?php echo $cryptocloud_apikey; ?>" class="form-control">
                                <?php if ($error_apikey) { ?>
                                    <div class="text-danger"><?php echo $error_apikey; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required<?php if ($error_merchant_id) { ?> has-error<?php } ?>">
                            <label class="col-sm-2 control-label"><?php echo $entry_merchant_id; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="cryptocloud_merchant_id" value="<?php echo $cryptocloud_merchant_id; ?>" class="form-control">
                                <?php if ($error_merchant_id) { ?>
                                    <div class="text-danger"><?php echo $error_merchant_id; ?></div>
                                <?php } ?>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="cryptocloud_title" value="<?php echo $cryptocloud_title; ?>" placeholder="<?php echo $heading_title; ?>" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_order_status; ?></label>
                            <div class="col-sm-10">
                                <select name="cryptocloud_order_status_id" class="form-control">
                                    <?php foreach($order_statuses as $_key => $order_status) { ?>
                                        <?php if ($order_status['order_status_id'] == $cryptocloud_order_status_id) { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>"
                                                    selected="selected"><?php echo $order_status['name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                            <div class="col-sm-10">
                                <select name="cryptocloud_status" class="form-control">
                                    <?php if ($cryptocloud_status) { ?>
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
                            <label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="cryptocloud_sort_order" value="<?php echo $cryptocloud_sort_order; ?>" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php echo $footer; ?>