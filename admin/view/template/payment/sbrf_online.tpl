<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-sbrf_online" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><img src="view/image/payment/sbrf.png" width="26" height="25"> <?php echo $heading_title; ?></h1>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-sbrf_online" class="form-horizontal">
          <?php foreach ($languages as $language) { ?>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-sbrf_online_bank<?php echo $language['language_id']; ?>"><?php echo $entry_bank; ?></label>
            <div class="col-sm-10">
              <span class="help-block"><?php echo $help_bank; ?></span>
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="sbrf_online_bank[<?php echo $language['language_id']; ?>]" rows="10"
                          id="input-sbrf_online_bank<?php echo $language['language_id']; ?>"
                          ><?php echo isset($sbrf_online_bank[$language['language_id']]) ? $sbrf_online_bank[$language['language_id']] : $text_bank_default; ?></textarea>
              </div>
              <?php if ($error_bank) { ?>
              <div class="text-danger"><?php echo $error_bank; ?></div>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
          <?php foreach ($languages as $language) { ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sbrf_online_page_success<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_page_success; ?>"><?php echo $entry_page_success; ?></span></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="sbrf_online_page_success[<?php echo $language['language_id']; ?>]" rows="10"
                          id="input-sbrf_online_page_success<?php echo $language['language_id']; ?>"
                          ><?php echo isset($sbrf_online_page_success[$language['language_id']]) ? $sbrf_online_page_success[$language['language_id']] : $text_page_success_default; ?></textarea>
              </div>
            </div>
          </div>
          <?php } ?>
          <?php foreach ($languages as $language) { ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sbrf_online_title<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_title; ?>"><?php echo $entry_title; ?></span></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
              <input type="text" name="sbrf_online_langdata[<?php echo $language['language_id']; ?>][title]"
                     value="<?php echo !empty($sbrf_online_langdata[$language['language_id']]['title'])
                                    ? $sbrf_online_langdata[$language['language_id']]['title'] : $title_default; ?>"
                     placeholder="<?php echo $entry_title; ?>" id="input-sbrf_online_title<?php echo $language['language_id']; ?>" class="form-control" />
              </div>
            </div>
          </div>
          <?php } ?>
          <?php foreach ($languages as $language) { ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sbrf_online_description<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_description; ?>"><?php echo $entry_description; ?></span></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="sbrf_online_langdata[<?php echo $language['language_id']; ?>][description]"
                          id="input-sbrf_online_description<?php echo $language['language_id']; ?>" rows="10" class="form-control"
                          ><?php echo isset($sbrf_online_langdata[$language['language_id']]['description']) ? $sbrf_online_langdata[$language['language_id']]['description'] : ''; ?></textarea>
              </div>
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sbrf_online_icon"><?php echo $entry_icon; ?></label>
            <div class="col-sm-10">
                <label class="radio-inline">
                    <input type="radio" name="sbrf_online_icon"
                           value="1" <?php echo $sbrf_online_icon ? 'checked="checked" ' : ''; ?>/> <?php echo $text_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="sbrf_online_icon"
                           value="0" <?php echo !$sbrf_online_icon ? 'checked="checked" ' : ''; ?>/> <?php echo $text_no; ?>
                </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sbrf_online_minimal_order"><span data-toggle="tooltip" title="<?php echo $help_minimal_order; ?>"><?php echo $entry_minimal_order; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="sbrf_online_minimal_order" value="<?php echo $sbrf_online_minimal_order; ?>"
                     placeholder="<?php echo $entry_minimal_order; ?>" id="input-sbrf_online_minimal_order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sbrf_online_maximal_order"><span data-toggle="tooltip" title="<?php echo $help_maximal_order; ?>"><?php echo $entry_maximal_order; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="sbrf_online_maximal_order" value="<?php echo $sbrf_online_maximal_order; ?>"
                     placeholder="<?php echo $entry_maximal_order; ?>" id="input-sbrf_online_maximal_order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sbrf_online_order_status_id"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="sbrf_online_order_status_id" id="input-sbrf_online_order_status_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $sbrf_online_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sbrf_online_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="sbrf_online_geo_zone_id" id="input-sbrf_online_geo_zone_id" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $sbrf_online_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sbrf_online_status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="sbrf_online_status" id="input-sbrf_online_status" class="form-control">
                <?php if ($sbrf_online_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-sbrf_online_sort_order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sbrf_online_sort_order" value="<?php echo $sbrf_online_sort_order; ?>"
                     placeholder="<?php echo $entry_sort_order; ?>" id="input-sbrf_online_sort_order" class="form-control" />
            </div>
          </div>
        </form>
        <div style="padding: 15px 15px; border:1px solid #ccc; margin-top: 15px; box-shadow:0 0px 5px rgba(0,0,0,0.1);"><?php echo $text_copyright; ?></div>
      </div><!-- </div class="panel-body"> -->
    </div><!-- </div class="panel panel-default"> -->
  </div><!-- </div class="container-fluid"> -->
</div><!-- </div id="content"> -->
<script type="text/javascript"><!--
  <?php foreach ($languages as $language) { ?>
      $('#input-sbrf_online_bank<?php echo $language['language_id']; ?>').summernote({
        height: 300
      });
      $('#input-sbrf_online_page_success<?php echo $language['language_id']; ?>').summernote({
        height: 300
      });
  <?php } ?>
//--></script>
<?php echo $footer; ?>