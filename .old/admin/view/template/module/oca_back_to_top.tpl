<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-back-to-top" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-back-to-top" class="form-horizontal">
          <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-placement="top" title="<?php echo $entry_background; ?>"><?php echo $entry_background; ?></span></label>
                <div class="col-sm-10">
                  <input name="oca_back_to_top_background" type="text" id="background" class="picker form-control" value="<?php echo $oca_back_to_top_background; ?>" style="border-left:30px solid #<?php echo $oca_back_to_top_background; ?>;"></input>
                 <?php if ($error_background) { ?>
                 <div class="text-danger"><?php echo $error_background; ?></div>
                 <?php } ?>
                </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" name="oca_back_to_top_width" value="<?php echo $oca_back_to_top_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
              <?php if ($error_width) { ?>
              <div class="text-danger"><?php echo $error_width; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" name="oca_back_to_top_height" value="<?php echo $oca_back_to_top_height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
              <?php if ($error_height) { ?>
              <div class="text-danger"><?php echo $error_height; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-margin-right"><?php echo $entry_margin_right; ?></label>
            <div class="col-sm-10">
              <input type="text" name="oca_back_to_top_margin_right" value="<?php echo $oca_back_to_top_margin_right; ?>" placeholder="<?php echo $oca_back_to_top_margin_right; ?>" id="input-margin-right" class="form-control" />
              <?php if ($error_margin_right) { ?>
              <div class="text-danger"><?php echo $error_margin_right; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-margin-bottom"><?php echo $entry_margin_bottom; ?></label>
            <div class="col-sm-10">
              <input type="text" name="oca_back_to_top_margin_bottom" value="<?php echo $oca_back_to_top_margin_right; ?>" placeholder="<?php echo $oca_back_to_top_margin_bottom; ?>" id="input-margin-bottom" class="form-control" />
              <?php if ($error_margin_bottom) { ?>
              <div class="text-danger"><?php echo $error_margin_bottom; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_mobile_tablet; ?></label>
            <div class="col-sm-10">
              <select name="oca_back_to_top_mobile_tablet" id="input-status" class="form-control">
                <?php if ($oca_back_to_top_mobile_tablet) { ?>
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
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="oca_back_to_top_status" id="input-status" class="form-control">
                <?php if ($oca_back_to_top_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/colpick/colpick.js"></script> 
<link rel="stylesheet" type="text/css" href="view/javascript/colpick/colpick.css"/>
<script type="text/javascript"><!--
$('#background').colpick({
  layout:'hex',
  submit:0,
  colorScheme:'dark',
  color: '<?php echo $oca_back_to_top_background; ?>' ,
  onChange:function(hsb,hex,rgb,el,bySetColor) {
    $(el).css('border-color','#'+hex);
    // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
    if(!bySetColor) $(el).val(hex);
  }
}).keyup(function(){
  $(this).colpickSetColor(this.value);
});
</script>
<?php echo $footer; ?>