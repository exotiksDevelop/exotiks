<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-banner" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb): ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning): ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-html" class="form-horizontal">                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="ulogin_status" id="input-status" class="form-control">
                                <?php if ($ulogin_status): ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                <?php else: ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for=""><?php echo $text_template; ?></label>
                        <div class="col-sm-10">
                            <select name="ulogin_type" class="form-control">
                                <?php foreach ($ulogin['type'] as $value): ?>
                                    <option value="<?php echo $value ?>" <?php echo (isset($module['type'])) ? ($value == $module['type']) ? 'selected="selected"' : '' : '' ?>><?php echo $value ?></option>
                                <?php endforeach; ?>                        
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for=""><?php echo $text_displayed?></label>
                        <div class="col-sm-10">
                            <?php foreach ($ulogin['providers'] as $value): ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="ulogin_providers[]" <?php echo (isset($providers)) ? (in_array($value, $providers)) ? 'checked="checked"' : '' : '' ?> value="<?php echo $value ?>">
                                        <?php echo $value ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for=""><?php echo $text_displayed_when_the_mouse?></label>
                        <div class="col-sm-10">
                            <?php foreach ($ulogin['providers'] as $value): ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="ulogin_hidden[]" <?php echo (isset($hidden)) ? (in_array($value, $hidden)) ? 'checked="checked"' : '' : '' ?> value="<?php echo $value ?>">
                                        <?php echo $value ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript"></script></div>
<?php echo $footer; ?>