<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-liqpay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_settings; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-rbs" class="form-horizontal">

                    <!-- Статус: Включен/Выключен-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            <?php echo $entry_status; ?>
                        </label>
                        <div class="col-sm-10">
                            <select name="rbs_status" class="form-control">
                                <option value="1" <?php echo $rbs_status == 1 ? 'selected="selected"' : ''; ?>><?php echo $status_enabled; ?></option>
                                <option value="0" <?php echo $rbs_status == 0 ? 'selected="selected"' : ''; ?>><?php echo $status_disabled; ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Логин продавца -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="">
                            <?php echo $entry_merchantLogin; ?>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="rbs_merchantLogin" value="<?php echo $rbs_merchantLogin; ?>" class="form-control" />
                        </div>
                    </div>

                    <!-- Пароль продавца -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="">
                            <?php echo $entry_merchantPassword; ?>
                        </label>
                        <div class="col-sm-10">
                            <input type="password" name="rbs_merchantPassword" value="<?php echo $rbs_merchantPassword; ?>" class="form-control" />
                        </div>
                    </div>

                    <!-- Режим работы модуля: Тестовый/БоевойРежим работы модуля: Тестовый/Боевой -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            <?php echo $entry_mode; ?>
                        </label>
                        <div class="col-sm-10">
                            <select name="rbs_mode" class="form-control">
                                <option value="test" <?php echo $rbs_mode == 'test' ? 'selected="selected"' : ''; ?>><?php echo $mode_test; ?></option>
                                <option value="prod" <?php echo $rbs_mode == 'prod' ? 'selected="selected"' : ''; ?>><?php echo $mode_prod; ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Стадийность платежа: одностадийный/двустадийный -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            <?php echo $entry_stage; ?>
                        </label>
                        <div class="col-sm-10">
                            <select name="rbs_stage" class="form-control">
                                <option value="one" <?php echo $rbs_stage == 'one' ? 'selected="selected"' : ''; ?>><?php echo $stage_one; ?></option>
                                <option value="two" <?php echo $rbs_stage == 'two' ? 'selected="selected"' : ''; ?>><?php echo $stage_two; ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Логирование: Включено/Выключено-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            <?php echo $entry_logging; ?>
                        </label>
                        <div class="col-sm-10">
                            <select name="rbs_logging" class="form-control">
                                <option value="1" <?php echo $rbs_logging == 1 ? 'selected="selected"' : ''; ?>><?php echo $logging_enabled; ?></option>
                                <option value="0" <?php echo $rbs_logging == 0 ? 'selected="selected"' : ''; ?>><?php echo $logging_disabled; ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Выбор валюты -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            <?php echo $entry_currency; ?>
                        </label>
                        <div class="col-sm-10">
                            <select name="rbs_currency" class="form-control">
                                <?php foreach ($currency_list as $currency) { ?>
                                    <option value="<?php echo $currency['numeric']; ?>" <?php echo $currency['numeric'] == $rbs_currency ? 'selected="selected"' : '';?>>
                                        <?php echo $currency['numeric'] == 0 ? $currency['alphabetic'] : $currency['alphabetic'] . ' (' . $currency['numeric'] . ')'; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>