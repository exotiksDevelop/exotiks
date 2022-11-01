<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
      <h3><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h3>
    </div>
    <div class="panel-body">
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-mileage_tax_class_id"><?php echo $entry_tax_class; ?></label>
				<div class="col-sm-2">
				  <select name="mileage_tax_class_id" id="input-mileage_tax_class_id" class="form-control">
					<option value="0"><?php echo $text_none; ?></option>
					<?php foreach ($tax_classes as $tax_class) { ?>
					<?php if ($tax_class['tax_class_id'] == $mileage_tax_class_id) { ?>
					<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-geo_zone"><?php echo $entry_geo_zone; ?></label>
					<div class="col-sm-2">
						<select name="mileage_geo_zone_id" id="geo_zone_id" class="form-control">
						<option value="0"><?php echo $text_all_zones; ?></option>
						<?php foreach ($geo_zones as $geo_zone) { ?>
						<?php if ($geo_zone['geo_zone_id'] == $mileage_geo_zone_id) { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
						<?php } ?>
						<?php } ?>
						</select>
					</div>
			</div>
            
            <div class="form-group">
			  <label class="col-sm-2 control-label" for="input-mileage_city"><?php echo $entry_mileage_city; ?></label>
				<div class="col-sm-5">
				  <input type="text" name="mileage_city" value="<?php echo ${'mileage_city'}; ?>" id="input-city" class="form-control" />
				</div>
			</div>
			
            <div class="form-group">
				<label class="col-sm-2 control-label" for="input-mileage_store"><?php echo $entry_mileage_store; ?></label>
				<div class="col-sm-3">
				  <input type="text" name="mileage_store" value="<?php echo ${'mileage_store'}; ?>" id="input-store" class="form-control" />
				</div>
			</div>
            
            <div class="form-group">
			<label class="col-sm-2 control-label" for="input-mileage_city_rate"><?php echo $entry_mileage_city_rate; ?></label>
				<div class="col-sm-3">
				<textarea name="mileage_city_rate" cols="40" rows="5"><?php echo ${'mileage_city_rate'}; ?></textarea>
				</div>
            </div>
            <div class="form-group">
				<label class="col-sm-2 control-label" for="input-mileage_oblast_rate"><?php echo $entry_mileage_oblast_rate; ?></label>
				  <div class="col-sm-3">
				  <textarea name="mileage_oblast_rate" cols="40" rows="5"><?php echo ${'mileage_oblast_rate'}; ?></textarea>
				</div>
            </div>
            <div class="form-group">
				<label class="col-sm-2 control-label" for="input-mileage_max_distance"><?php echo $entry_max_distance; ?></label>
					<div class="col-sm-3">
					<input type="text" name="mileage_max_distance" value="<?php echo $mileage_max_distance; ?>" />
					</div>
            </div>
            <div class="form-group">
				<label class="col-sm-2 control-label" for="input-mileage_hide_map"><?php echo $entry_hide_map; ?></label>
              <div class="col-sm-1">
              <input type="checkbox" name="mileage_hide_map" value="1" <?php echo ($mileage_hide_map == 1 ? ' checked="checked"' : ''); ?> />
			  
				</div>
            </div>
            
            <div class="form-group">
				<label class="col-sm-2 control-label" for="input-mileage_sort_order"><?php echo $entry_sort_order; ?></label>
              <div class="col-sm-1">
              <input type="text" name="mileage_sort_order" value="<?php echo $mileage_sort_order; ?>" size="1" />
				</div>
            </div>
            
            <div class="form-group">
			<label class="col-sm-2 control-label" for="input-mileage_status"><?php echo $entry_status; ?></label>
              <div class="col-sm-1">
			  <select name="mileage_status" id="mileage_status" class="form-control">
                  <?php if (${'mileage_status'}) { ?>
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
<?php echo $footer; ?> 
