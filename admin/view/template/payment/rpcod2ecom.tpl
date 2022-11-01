<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"> <!-- div id="content" -->
  <div class="page-header"><!-- div class="page-header" -->
    <div class="container-fluid">
      <div class="pull-right">
	  
        <a href="javascript: $('#stay_field').attr('value', '0'); $('#form').submit();" 
		data-toggle="tooltip" 
		title="<?php echo $button_save_go; ?>" 
		class="btn btn-primary"><i class="fa fa-save"></i></a>
		
        <a href="javascript: $('#stay_field').attr('value', '1'); $('#form').submit();" 
		data-toggle="tooltip"  
		title="<?php echo $button_save_stay; ?>" 
		class="btn btn-primary"><i class="fa fa-pencil"></i></a>
		
        <a href="<?php echo $cancel; ?>" 
		data-toggle="tooltip" 
		title="<?php echo $button_cancel; ?>" 
		class="btn btn-default"><i class="fa fa-reply"></i></a>
	  </div>
		
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div><!-- div class="page-header" -->

  <div class="container-fluid"><!-- div class="container-fluid" -->

    <?php if ($error_warning) { ?>
	
	<?php foreach($error_warning as $err) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $err; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
	<?php } ?>
	
    <?php } ?>
	
	<?php if ($success) { ?>	
    <div class="alert alert-success"><i class="fa fa-info-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
	<?php }  ?>
	
    <div class="panel panel-default"> <!--  class="panel panel-default" -->
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body"> <!--  class="panel-body" -->
	  
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"
	class="form-horizontal">
	<input type="hidden" name="stay" id="stay_field" value="1">
	
<div class="tab-content"><!--  class="tab-content" -->
	  
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_status; ?>
			</label>
            <div class="col-sm-10">
				<select name="rpcod2ecom_status" class="form-control" >
                <?php if ($rpcod2ecom_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_order_status; ?>
			</label>
            <div class="col-sm-10">
				<select name="rpcod2ecom_order_status" class="form-control" >
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $rpcod2ecom_order_status) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
			</div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_geo_zone; ?>
			</label>
            <div class="col-sm-10">
				<select name="rpcod2ecom_geo_zone_id" class="form-control" >
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $rpcod2ecom_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
			</div>
        </div>
		
		<?php if( !empty($rpcod2ecom_order_filters) ) { ?>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_order_filters; ?>
			</label>
            <div class="col-sm-10">
				<?php foreach($rpcod2ecom_order_filters as $ft) { ?>
				<label for="filter_<?php echo $ft['filter_id']; ?>"
				><input type="checkbox" 
				name="rpcod2ecom_order_filters[<?php echo $ft['filter_id']; ?>]"
				value="1"
				id="filter_<?php echo $ft['filter_id']; ?>"
				<?php if( $ft['status'] ) { ?> checked <?php } ?>
				>&nbsp;&nbsp;<?php echo $ft['filtername']; ?></label><br><br>
				<?php } ?>
			</div>
        </div>
		<?php } ?> 
		
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_sort_order; ?>
			</label>
            <div class="col-sm-10">
			  <input type="text"  class="form-control" name="rpcod2ecom_sort_order" value="<?php echo $rpcod2ecom_sort_order; ?>" size="1" />
			</div>
        </div>
		  
	</div>	<!--  class="tab-content" -->
    </form>
	</div> <!--  class="panel-body" -->
	</div> <!--  class="panel panel-default" -->
		
  </div><!-- div class="container-fluid" -->
</div> <!-- div id="content" -->
<?php echo $footer; ?> 