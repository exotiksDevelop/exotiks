<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-google-hangouts" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
	  
	 <div class="row">
	  <div class="col-sm-6">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-sms-alert" class="form-horizontal">
         
          <div class="form-group required">
            <label class="col-sm-4 control-label" for="sms_alert_tel"><?php echo $entry_sms_alert_tel; ?></label>
            <div class="col-sm-8">
              <input type="text" name="sms_alert_tel" value="<?php echo $sms_alert_tel; ?>" placeholder="79272000000" id="sms_alert_tel" class="form-control" />
              <?php if ($error_sms_alert_tel) { ?>
              <div class="text-danger"><?php echo $error_sms_alert_tel; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-4 control-label" for="sms_alert_id"><?php echo $entry_sms_alert_id; ?></label>
            <div class="col-sm-8">
              <input type="text" name="sms_alert_id" value="<?php echo $sms_alert_id; ?>" placeholder="4a1dc787-5f12-8814-fd11-XXXXXXXXXXXX" id="sms_alert_id" class="form-control" />
              <?php if ($error_sms_alert_id) { ?>
              <div class="text-danger"><?php echo $error_sms_alert_id; ?></div>
              <?php } ?>
            </div>
          </div>
		  
		<div class="form-group">
		  <label class="col-sm-4 control-label" for="input-process-status"><?php echo $entry_processing_status; ?></label>
		  <div class="col-sm-8">
			<div class="well well-sm" style="height: 150px; overflow: auto;">
			  <?php foreach ($order_statuses as $order_status) { ?>
			  <div class="checkbox">
				<label>
				  <?php if (in_array($order_status['order_status_id'], $sms_alert_processing_status)) { ?>
				  <input type="checkbox" name="sms_alert_processing_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
				  <?php echo $order_status['name']; ?>
				  <?php } else { ?>
				  <input type="checkbox" name="sms_alert_processing_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
				  <?php echo $order_status['name']; ?>
				  <?php } ?>
				</label>
			  </div>
			  <?php } ?>
			</div>
			<?php if ($error_sms_alert_processing_status) { ?>
			<div class="text-danger"><?php echo $error_sms_alert_processing_status; ?></div>
			<?php } ?>
		  </div>
		</div>  

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-8">
              <select name="sms_alert_status" id="input-status" class="form-control">
                <?php if ($sms_alert_status) { ?>
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
		
		<div class="col-sm-6">		
			<div class="alert alert-info" role="alert">
			<?php echo $sms_alert_help; ?>
			</div>
		</div>
		
	</div>
	
		
      </div>
    </div>
  </div>
</div>

<!-- opencart-russia.ru -->

<?php echo $footer; ?>