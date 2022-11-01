<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-total" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-prepayment-sv" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-prepayment-sv-type"><?php echo $entry_type; ?></label>
            <div class="col-sm-9">
              <select name="prepayment_sv_type" id="input-prepayment-sv-type" class="form-control">
                <?php if ($prepayment_sv_type == 'P') { ?>
                <option value="P" selected="selected"><?php echo $text_precent; ?></option>
                <option value="F"><?php echo $text_fixed; ?></option>
                <?php } else { ?>
                <option value="P"><?php echo $text_precent; ?></option>
                <option value="F" selected="selected"><?php echo $text_fixed; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-prepayment-sv-value"><?php echo $entry_value; ?></label>
            <div class="col-sm-9">
              <input type="text" name="prepayment_sv_value" value="<?php echo $prepayment_sv_value; ?>" placeholder="<?php echo $entry_value; ?>" id="input-prepayment-sv-value" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-prepayment-sv-min-value"><span data-toggle="tooltip" title="<?php echo $help_min_value; ?>"><?php echo $entry_min_value; ?></span></label>
            <div class="col-sm-9">
              <input type="text" name="prepayment_sv_min_value" value="<?php echo $prepayment_sv_min_value; ?>" placeholder="<?php echo $entry_min_value; ?>" id="input-prepayment-sv-min-value" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-prepayment-sv-reduce-prepayment"><span data-toggle="tooltip" title="<?php echo $help_reduce_prepayment; ?>"><?php echo $entry_reduce_prepayment; ?></span></label>
            <div class="col-sm-9">
              <?php if ((bool)$prepayment_sv_reduce_prepayment) { ?>
              <input type="checkbox" name="prepayment_sv_reduce_prepayment" id="input-prepayment-sv-reduce-prepayment" value="true" checked="checked" class="form-control" />
              <?php } else { ?>
              <input type="checkbox" name="prepayment_sv_reduce_prepayment" id="input-prepayment-sv-reduce-prepayment" value="false" class="form-control" />
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-9">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($totals as $total) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($total['code'], $prepayment_sv_total)) { ?>
                    <input type="checkbox" name="prepayment_sv_total[]" value="<?php echo $total['code']; ?>" checked="checked">
                    <?php } else { ?>
                    <input type="checkbox" name="prepayment_sv_total[]" value="<?php echo $total['code']; ?>" />
                    <?php } ?>
                    <?php echo $total['name']; ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo $entry_shipping; ?></label>
            <div class="col-sm-9">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($shippings as $shipping) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (isset($prepayment_sv_shipping[$shipping['code']])) { ?>
                    <input type="checkbox" name="prepayment_sv_shipping[<?php echo $shipping['code']; ?>]" value="<?php echo $shipping['code']; ?>" checked="checked">
                    <?php } else { ?>
                    <input type="checkbox" name="prepayment_sv_shipping[<?php echo $shipping['code']; ?>]">
                    <?php } ?>
                    <?php echo $shipping['name']; ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-prepayment-sv-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-9">
              <select name="prepayment_sv_status" id="input-prepayment-sv-status" class="form-control">
                <?php if ($prepayment_sv_status) { ?>
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
            <label class="col-sm-3 control-label" for="input-prepayment-sv-sort-order"><span data-toggle="tooltip" title="<?php echo $help_sort_order; ?>"><?php echo $entry_sort_order; ?></span></label>
            <div class="col-sm-9">
              <input type="text" name="prepayment_sv_sort_order" value="<?php echo $prepayment_sv_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-prepayment-sv-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>