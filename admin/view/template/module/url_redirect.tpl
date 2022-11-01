<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php echo $css_framework; ?>
    <div class="container-fluid" id="css_framework">

        <?php if (!empty($error_warning)) { ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times</button>
                <i class="fa fa-exclamation-circle"></i>
                <?php echo $error_warning; ?>
            </div>
        <?php } ?>

        <?php if (!empty($success)) { ?>
            <div class="alert alert-success dismissible">
                <button type="button" class="close" data-dismiss="alert">&times</button>
                <i class="fa fa-exclamation-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php } ?>

        <h4><?php echo $heading_title; ?></h4>

        <div class="panel panel-default shadow">
            <div class="panel-body">
                <!-- Begin Form data -->
                <?php if ($tpl_filename == 'url_redirect_form') { ?>
                    <div class="panel-heading border-0">
                        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
                    </div>
                    <form action="<?php echo $action; ?>" id="form-add-redirect" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="url_from"><?php echo $entry_url_from; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="url_from" value="<?php echo isset($url_from) ? $url_from : ''; ?>" placeholder="<?php echo $entry_url_from; ?>" id="url_from" class="form-control">
                                <?php if ($error_url_from) { ?>
                                    <div class="text-danger"><?php echo $error_url_from; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="url_to"><?php echo $entry_url_to; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="url_to" value="<?php echo isset($url_to) ? $url_to : ''; ?>" placeholder="<?php echo $entry_url_to; ?>" id="url_to" class="form-control">
                                <?php if ($error_url_to) { ?>
                                    <div class="text-danger"><?php echo $error_url_to; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="url_method"><?php echo $entry_url_method; ?></label>
                            <div class="col-sm-4 col-md-3 col-lg-2">
                                <select id="url_method" name="url_method" class="form-control">
                                    <option value="301" <?php if (!isset($url_method) or $url_method == '301') { ?> selected="selected" <?php } ?>>301 Permanent</option>
                                    <option value="302" <?php if (isset($url_method) and $url_method == '302') { ?> selected="selected" <?php } ?>>302 Temporary</option>
                                    <option value="307" <?php if (isset($url_method) and $url_method == '307') { ?> selected="selected" <?php } ?>>307 Temporary</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="redirect_status"><?php echo $entry_redirect_status; ?></label>
                            <div class="col-sm-4 col-md-3 col-lg-2">
                                <select id="redirect_status" name="redirect_status" class="form-control">
                                    <?php if ($redirect_status) { ?>
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
                            <label class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-10">
                                <button type="submit" form="form-add-redirect" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
                                    <i class="fa fa-save"></i> &nbsp; <?php echo $button_save; ?>
                                </button>
                                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-outline-secondary"><i class="fa fa-reply"></i>
                                    <?php echo $button_cancel; ?>
                                </a>
                            </div>
                        </div>
                    </form><!-- END Form data -->

                <?php } else { ?>
                    <script>
                        <?php if (isset($tab_selected) && $tab_selected) { ?>
                            $(document).ready(function() {
                                $('a[href="#<?php echo $tab_selected; ?>"]').tab('show');
                            });
                        <?php } ?>
                    </script>
                    <!-- Begin List data -->
                    <?php if (empty($entry_license) && $module_url_redirect_status == false) { ?>
                        <div class="alert alert-info alert-dismissible">
                            <i class="fa fa-check-circle"></i> <?php echo $text_module_disabled; ?>
                        </div>
                    <?php } ?>
                    <ul class="main-menu main-menu-left" id="main-menu">
                        <li class="menu-item active"><a href="#tab-list" class="menu-link" data-toggle="tab"><?php echo $tab_list; ?></a></li>
                        <li class="menu-item"><a href="#tab-setting" class="menu-link" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
                        <li class="menu-item"><a href="#tab-import-export" class="menu-link" data-toggle="tab"><?php echo $tab_import_export; ?></a></li>
                        <li class="menu-item"><a href="#tab-about" class="menu-link" data-toggle="tab"><?php echo $tab_about; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-list">

                            <div class="text-right" style="padding-bottom: 10px">
                                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $button_add; ?>
                                </a>
                                <button type="button" data-toggle="tooltip" title="<?php echo $text_delete_selected; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-list-redirect').submit() : false;">
                                    <i class="fa fa-trash-o"></i> <?php echo $text_delete_selected; ?></button>

                            </div>
                            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-list-redirect">
                                <div class="table table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <td style="width: 1px;" class="text-center">
                                                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                                                </td>
                                                <td class="text-left">
                                                    <?php if ($sort == 'url_from') { ?>
                                                        <a href="<?php echo $sort_url_from; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_url_from; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_url_from; ?>"><?php echo $column_url_from; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-left">
                                                    <?php if ($sort == 'url_to') { ?>
                                                        <a href="<?php echo $sort_url_to; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_url_to; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_url_to; ?>"><?php echo $column_url_to; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-left">
                                                    <?php if ($sort == 'url_method') { ?>
                                                        <a href="<?php echo $sort_url_method; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_method; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_url_method; ?>"><?php echo $column_method; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($sort == 'date_added') { ?>
                                                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($sort == 'redirect_status') { ?>
                                                        <a href="<?php echo $sort_redirect_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_redirect_status; ?></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $sort_redirect_status; ?>"><?php echo $column_redirect_status; ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center"><?php echo $column_action; ?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($redirects) { ?>
                                                <?php foreach ($redirects as $redirect) { ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php if (in_array($redirect['redirect_id'], $selected)) { ?>
                                                                <input type="checkbox" name="selected[]" value="<?php echo $redirect['redirect_id']; ?>" checked="checked">
                                                            <?php } else { ?>
                                                                <input type="checkbox" name="selected[]" value="<?php echo $redirect['redirect_id']; ?>">
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-left"><i class="fa fa-external-link"></i>
                                                            <a href="<?php echo $redirect['url_from']; ?>" target="_blank"><?php echo $redirect['url_from']; ?></a>
                                                        </td>
                                                        <td class="text-left"><i class="fa fa-external-link"></i>
                                                            <a href="<?php echo $redirect['url_to']; ?>" target="_blank"><?php echo $redirect['url_to']; ?></a>
                                                        </td>
                                                        <td class="text-left"><?php echo $redirect['url_method']; ?></td>
                                                        <td class="text-center"><?php echo $redirect['date_added']; ?></td>
                                                        <td class="text-center">
                                                            <span class="badge <?php echo $redirect['status'] ? 'bg-success' : 'bg-secondary'; ?>"><?php echo $redirect['text_status']; ?></span>
                                                        </td>
                                                        <td class="text-center" style="min-width:120px">
                                                            <?php if ($redirect['status']) { ?>
                                                                <a href="<?php echo $redirect['action']; ?>" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-outline-secondary btn-sm"><i class="fa fa-minus-circle"></i></a>
                                                            <?php } else { ?>
                                                                <a href="<?php echo $redirect['action']; ?>" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i></a>
                                                            <?php } ?>
                                                            <a href="<?php echo $redirect['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                                            <a href="javascript:{}" onclick="confirm('<?php echo $text_confirm; ?>') ? window.location.href='<?php echo $redirect['delete']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            <div class="row mt-3">
                                <div class="col-sm-6 text-left pagination-sm"><?php echo $pagination; ?></div>
                                <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-setting">
                            <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                    <div class="col-sm-10">
                                        <select name="module_url_redirect_status" id="input-status" class="form-control input-inline">
                                            <?php if ($module_url_redirect_status == 1) { ?>
                                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                                <option value="1"><?php echo $text_enabled; ?></option>
                                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
                                            <?php echo $button_save; ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab-import-export">
                            <form action="<?php echo $export; ?>" method="post" id="form-import-export" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="url_method"><?php echo $entry_type_of_export; ?></label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="type-of-export" name="type_of_export" class="form-control">
                                                <option value="csv" <?php if (!isset($type_of_export) or $type_of_export == 'csv') { ?> selected="selected" <?php } ?>>CSV file</option>
                                                <option value="opencart" <?php if (isset($type_of_export) and $type_of_export == 'opencart') { ?> selected="selected" <?php } ?>>OpenCart</option>
                                            </select>
                                            <div class="input-group-btn">
                                                <input type="submit" class="btn btn-outline-primary" value="<?php echo $button_export; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><?php echo $entry_import_data; ?></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="file" name="import" id="input-import">
                                            <p class="help-block"><?php echo $text_help_import_data; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">&nbsp;</label>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-outline-primary"><?php echo $button_import; ?></button>
                                    </div>
                                </div>
                            </form>
                            <?php if (!empty($text_import_description)) { ?>
                                <div class="well well-sm" style="margin-top: 15px">
                                    <?php echo $text_import_description; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane" id="tab-about">
                            <div class="row pb-4">

                                <div class="col-sm-12 col-md-8">
                                    <form action="<?php echo $action; ?>" method="post" id="form-license" enctype="multipart/form-data" class="form-horizontal">
                                        <div class="form-group">
                                            <?php if ($entry_license) { ?>
                                                <label for="license_key" class="col-sm-5 control-label"><?php echo $entry_license; ?></label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="license_key" id="license_key" placeholder="License Key">
                                                        <span class="input-group-btn"> <button type="submit" form="form-license" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_save; ?>"><i class="fa fa-save"></i></button> </span>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <label class="col-sm-5 control-label"><?php echo $text_license_key; ?></label>
                                                <div class="col-sm-7">
                                                    <p class="form-control-static">
                                                        <a style="cursor: pointer" onclick="prompt('<?php echo $text_license_key; ?>', '<?php echo $license_key; ?>'); return false;"><?php echo $text_show; ?></a>
                                                    </p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label"><?php echo $text_app_name; ?></label>
                                            <div class="col-sm-7">
                                                <p class="form-control-static"><?php echo $app_name; ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label"><?php echo $text_home_page; ?></label>
                                            <div class="col-sm-7">
                                                <p class="form-control-static"><?php echo $home_page; ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label"><?php echo $text_support_email; ?></label>
                                            <div class="col-sm-7">
                                                <p class="form-control-static">
                                                    <a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a>
                                                </p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> <!-- END ABOUT -->
                        </div>
                    </div><!-- End List data -->
                <?php } ?>
            </div>
            <div class="panel-footer text-left bg-light"><?php echo $text_support_message; ?></div>
        </div>
    </div>
</div>
<?php echo $footer; ?>