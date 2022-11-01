<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<style>
select { 
    min-width: !important 100px;
}
</style>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  
		<?php /* start metka-1 */ ?>
        <a href="<?php echo $clear_cache; ?>" 
		data-toggle="tooltip" 
		title="<?php echo $button_save_go; ?>" 
		class="btn btn-primary"><?php echo $button_clear_cache; ?></a>
		<?php /* end metka-1 */ ?>	
	
	  
	  
        <a href="javascript: set_tab(); $('#stay_field').attr('value', '0');  saveByTabs();" 
		data-toggle="tooltip" 
		title="<?php echo $button_save_go; ?>" 
		class="btn btn-primary"><i class="fa fa-save"></i></a>
		
        <a href="javascript: set_tab(); $('#stay_field').attr('value', '1');  saveByTabs();" 
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
  </div>
  <div class="container-fluid">
    
    <?php if ($errors) { ?>
	
	<?php foreach($errors as $err) { ?>
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
	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
		<a href="https://softpodkluch.ru/russianpost2-start" 
		target=_blank style="float: right; background: yellow; padding: 4px;"><?php echo $text_general_instruction; ?></a>
		
      </div>
      <div class="panel-body">
	  
		<ul class="nav nav-tabs">
		
            <li class="active" 
			><a href="#tab-general" style="cursor: pointer;" id="link-tab-general"  data-toggle="tab"><?php echo $tab_general; ?></a></li>
			
			<li><a href="#tab-methods" style="cursor: pointer;" id="link-tab-methods"  data-toggle="tab"><?php echo $tab_methods; ?></a></li>
					
			<li><a href="#tab-service" style="cursor: pointer;" id="link-tab-service"  data-toggle="tab"><?php echo $tab_service; ?></a></li>
			
			<?php /* start 2801 */ ?>
			<li><a href="#tab-customs" style="cursor: pointer;" id="link-tab-customs"  
			data-toggle="tab"><?php echo $tab_customs; ?></a></li>
			<?php /* end 2801 */ ?>
			<li><a href="#tab-delivery_types" style="cursor: pointer;" id="link-tab-delivery_types"  data-toggle="tab"><?php echo $tab_delivery_types; ?></a></li>
			<?php /* start 112 */ ?>
			<li><a href="#tab-packs" style="cursor: pointer;" id="link-tab-packs"  
			data-toggle="tab"><?php echo $tab_packs; ?></a></li>
			<?php /* end 112 */ ?>				
			<li><a href="#tab-customsrok" style="cursor: pointer;" id="link-tab-packs"  
			data-toggle="tab"><?php echo $tab_customsrok; ?></a></li>
			
			<li><a href="#tab-filters" style="cursor: pointer;" id="link-tab-filters"  data-toggle="tab"><?php echo $tab_filters; ?></a></li>
					
			<li><a href="#tab-adds" style="cursor: pointer;" id="link-tab-adds"  data-toggle="tab"><?php echo $tab_adds; ?></a></li>
			
			<li><a href="#tab-regions" style="cursor: pointer;" id="link-tab-regions" data-toggle="tab"><?php echo $tab_regions; ?></a></li>
			
			<li><a href="#tab-synx" style="cursor: pointer;" id="link-tab-synx"  data-toggle="tab"><?php echo $tab_synx; ?></a></li>
			
			<li><a href="#tab-cod" style="cursor: pointer;" id="link-tab-cod"  data-toggle="tab"><?php echo $tab_cod; ?></a></li>
			
            <li><a href="#tab-support" style="cursor: pointer;" id="link-tab-support" data-toggle="tab" ><?php echo $tab_support; ?></a></li>
		</ul>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-russianpost2" class="form-horizontal" >
		<input type="hidden" name="stay" id="stay_field" value="1">
		<input type="hidden" id="hiddentab" name="tab" value="<?php echo $tab; ?>">
		<input type="hidden" id="hiddensubtab" name="subtab" value="<?php 
		if( !empty($subtab) ) echo $subtab; 
		?>">
		<input type="hidden" id="hiddensubtab2" name="subtab2" value="<?php 
		if( !empty($subtab2) ) echo $subtab2; 
		?>">
		
		<?php $submethod_row = array(); ?>
		
<div class="tab-content"><!--  class="tab-content" -->
		<div id="tab-general" class="tab-pane active">
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_module_version; ?>
			</label>
            <div class="col-sm-10" style="padding-top: 9px;">
				<?php echo $module_version; ?> &nbsp;&nbsp;&nbsp; <?php echo $update_status; ?>
			</div>
        </div>
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_sfp_version; ?>
			</label>
            <div class="col-sm-10" >
				<?php echo $sfp_version; ?> &nbsp;&nbsp;&nbsp; <span class="text_update_status"
							></span>  &nbsp;&nbsp;&nbsp; 
							<a class="btn btn-primary" onclick="window.location.href='<?php echo $update_action; ?>'" 
							
							><span><?php echo $button_update_tarif; ?></span></a>
			</div>
        </div>
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_status; ?>
			</label>
            <div class="col-sm-10">
				<select class="form-control"  name="russianpost2_status">
              <?php if ($russianpost2_status) { ?>
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
				<?php echo $entry_debug; ?>
			</label>
            <div class="col-sm-10">
			<?php /* start 2012 */ ?>
				<select class="form-control"  name="russianpost2_debug">
                
                <option <?php if (!$russianpost2_debug) { ?> selected="selected" <?php } ?> 
				value="0"
				><?php echo $text_disabled; ?></option>
				
                <option <?php if ($russianpost2_debug=='log') { ?> selected="selected" <?php } ?> 
				value="log"
				><?php echo $entry_debug_log; ?></option>
				
                <option <?php if ($russianpost2_debug=='print') { ?> selected="selected" <?php } ?> 
				value="print"
				><?php echo $entry_debug_print; ?></option>
              </select>
			<?php /* end 2012 */ ?>
			</div>
        </div>
		
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_notifyme; ?>
			</label>
            <div class="col-sm-10">
				<select class="form-control"  name="russianpost2_notifyme">
                <?php if ($russianpost2_notifyme) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
				<div><?php echo $entry_notifyme_notice; ?></div>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_notifyme_email; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control"  name="russianpost2_notifyme_email" value="<?php echo $russianpost2_notifyme_email; ?>" />
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_from_region; ?>
			</label>
            <div class="col-sm-10">
				<select class="form-control"  name="russianpost2_from_region">
				<?php foreach($zones as $zone) { ?>
				<option value="<?php echo $zone['zone_id']; ?>" <?php if($russianpost2_from_region==$zone['zone_id']) { ?> selected="selected" <?php } ?> ><?php echo $zone['name']; ?></option>
				<?php } ?>
				</select>
			</div>
        </div>
		<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_from_city; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control"  name="russianpost2_from_city" value="<?php echo $russianpost2_from_city; ?>" />
		  
			</div>
        </div>
		<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_from_postcode; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control"  name="russianpost2_from_postcode" value="<?php echo $russianpost2_from_postcode; ?>" />
		  
		<?php /* start 1511 */ ?>
		  <div><?php echo $entry_from_postcode_notice; ?></div>
		<?php /* end 1511 */ ?>
			</div>
        </div>
		
		<?php /* start 0805 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_if_nosrok; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_if_nosrok">
				<option value="tariff" 
				<?php if($russianpost2_if_nosrok=='tariff') { ?> 
				selected="selected" <?php } ?> ><?php echo $entry_if_nosrok_tariff; ?></option>
				
				
				<option value="postcalc" 
				<?php if($russianpost2_if_nosrok=='postcalc') { ?> 
				selected="selected" <?php } ?> ><?php echo $entry_if_nosrok_postcalc ?></option>
				<option value="none" 
				<?php if($russianpost2_if_nosrok=='none') { ?> 
				selected="selected" <?php } ?> ><?php echo $entry_if_nosrok_none; ?></option>
            </select>
			<div><?php echo $entry_if_nosrok_notice; ?></div>
			</div>
        </div>
		<?php /* end 0805 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_insurance_base; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_insurance_base">
				<option value="total" 
				<?php if($russianpost2_insurance_base=='total') { ?> 
				selected="selected" <?php } ?> ><?php echo $entry_insurance_base_total; ?></option>
				<option value="products" 
				<?php if($russianpost2_insurance_base=='products') { ?> 
				selected="selected" <?php } ?> ><?php echo $entry_insurance_base_products; ?></option>
            </select>
			</div>
        </div>
		
		
	   <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_printpost_api_status; ?>
			</label>
            <div class="col-sm-10">
				<select class="form-control"  name="russianpost2_printpost_api_status">
					<?php if ($russianpost2_printpost_api_status) { ?>
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
				<?php echo $entry_ifnocountry; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_ifnocountry">
				<option value="hide" 
				<?php if($russianpost2_ifnocountry=='hide') { ?> 
				selected="selected" <?php } ?> ><?php echo $text_hide_method; ?></option>
				<option value="default" 
				<?php if($russianpost2_ifnocountry=='default') { ?> 
				selected="selected" <?php } ?> ><?php echo $text_use_default_country; ?></option>
            </select>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_ifnoregion; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_ifnoregion">
				<option value="hide" 
				<?php if($russianpost2_ifnoregion=='hide') { ?> 
				selected="selected" <?php } ?> ><?php echo $text_hide_method; ?></option>
				<option value="default" 
				<?php if($russianpost2_ifnoregion=='default') { ?> 
				selected="selected" <?php } ?> ><?php echo $text_use_default_region; ?></option>
            </select>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_default_region; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_default_region">
				<?php foreach($zones as $zone) { ?>
				<option value="<?php echo $zone['zone_id']; ?>" 
				<?php if($russianpost2_default_region==$zone['zone_id']) { ?> selected="selected" <?php } ?> 
				><?php echo $zone['name']; ?></option>
				<?php } ?>
            </select>
			</div>
        </div>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_russianpost2_ifnopostcode; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_ifnopostcode"> 
              <?php if ($russianpost2_ifnopostcode == 'on') { ?>
              <option value="on" selected="selected"><?php echo $entry_russianpost2_ifnopostcode_on; ?></option>
              <option value="off"><?php echo $entry_russianpost2_ifnopostcode_off; ?></option>
              <?php } else { ?>
              <option value="on"><?php echo $entry_russianpost2_ifnopostcode_on; ?></option>
              <option value="off" selected="selected"><?php echo $entry_russianpost2_ifnopostcode_off; ?></option>
              <?php } ?>
            </select> 
			</div>
        </div>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_russianpost2_ifnouserpostcode; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_ifnouserpostcode"> 
              <?php if ($russianpost2_ifnouserpostcode) { ?>
              <option value="1" selected="selected"><?php echo $entry_russianpost2_ifnouserpostcode_usedetected; ?></option>
              <option value="0"><?php echo $entry_russianpost2_ifnouserpostcode_skip; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $entry_russianpost2_ifnouserpostcode_usedetected; ?></option>
              <option value="0" selected="selected"><?php echo $entry_russianpost2_ifnouserpostcode_skip; ?></option>
              <?php } ?>
            </select> 
			<div><?php echo $entry_russianpost2_ifnouserpostcode_notice; ?></div>
			</div>
        </div>
		<?php /* start 0802 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_calc_by_region_for_remote; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_calc_by_region_for_remote"> 
              <?php if ($russianpost2_calc_by_region_for_remote) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
			<div><?php echo $entry_calc_by_region_for_remote_notice; ?></div>
			</div>
        </div>
		<?php /* end 0802 */ ?>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_is_ignore_user_postcode; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_is_ignore_user_postcode"> 
               <option value="0" 
				  <?php if( !$russianpost2_is_ignore_user_postcode ) { ?> selected="selected" <?php } ?>
				  ><?php echo $text_disabled; ?></option>
				  
				  <option value="1" 
				  <?php if( $russianpost2_is_ignore_user_postcode == 1 ) { ?> selected="selected" <?php } ?>
				  ><?php echo $text_enabled; ?></option>
				  
				  <option value="2" 
				  <?php if( $russianpost2_is_ignore_user_postcode == 2 ) { ?> selected="selected" <?php } ?>
				  ><?php echo $entry_is_ignore_user_postcode_byregion; ?></option>
				
            </select> 
			</div>
        </div> 
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_ifnocity; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_ifnocity">
				<option value="hide" 
				<?php if($russianpost2_ifnocity=='hide') { ?> 
				selected="selected" <?php } ?> ><?php echo $text_hide_method; ?></option>
				<option value="default" 
				<?php if($russianpost2_ifnocity=='default') { ?> 
				selected="selected" <?php } ?> ><?php echo $text_use_default_city; ?></option>
            </select>
			</div>
        </div>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_default_city; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control"  name="russianpost2_default_city" 
					value="<?php echo $russianpost2_default_city; ?>" />
			</div>
        </div>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_pvz_sorttype; ?>
			</label>
            <div class="col-sm-10">
				<select class="form-control"  name="russianpost2_pvz_sorttype">
					<option value="abc" 
					<?php if($russianpost2_pvz_sorttype=='abc') { ?> 
					selected="selected" <?php } ?> ><?php echo $entry_pvz_sorttype_abc; ?></option>
					<option value="brand" 
					<?php if($russianpost2_pvz_sorttype=='brand') { ?> 
					selected="selected" <?php } ?> ><?php echo $entry_pvz_sorttype_brand; ?></option>
				</select>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_weight_source; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_weight_source">
              
			  <option value="product"
			  <?php if ($russianpost2_weight_source=='product') { ?> selected="selected" <?php } ?>
			  ><?php echo $entry_weight_source_product; ?></option>
			  
			  <option value="cart"
			  <?php if( $russianpost2_weight_source=='cart' ) { ?> selected="selected" <?php } ?>
			  ><?php echo $entry_weight_source_cart; ?></option>
			  
			  
            </select>
			</div>
        </div> 
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_use_max_product_weight; ?>
			</label>
            <div class="col-sm-10">
				<select class="form-control"  name="russianpost2_use_max_product_weight">
              <?php if ($russianpost2_use_max_product_weight) { ?>
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
				<?php echo $entry_product_nullweight; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_product_nullweight">
              
			  <option value="setdefault"
			  <?php if ($russianpost2_product_nullweight=='setdefault') { ?> selected="selected" <?php } ?>
			  ><?php echo $text_product_nullweight_setdefault; ?></option>
			  
			  <option value="setnull"
			  <?php if( $russianpost2_product_nullweight=='setnull' ) { ?> selected="selected" <?php } ?>
			  ><?php echo $text_product_nullweight_setnull; ?></option>
			  
			  
            </select>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_product_default_weight; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control"  name="russianpost2_product_default_weight" 
				value="<?php echo $russianpost2_product_default_weight; ?>">
			</div>
        </div>
		<?php /* start 3005 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_product_replace_weight; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_product_replace_weight">
              <?php if ($russianpost2_product_replace_weight) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?> 
            </select>
			</div>
        </div>
		<?php /* end 3005 */ ?>
		
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_order_nullweight; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_order_nullweight">
              
			  <option value="setdefault"
			  <?php if ($russianpost2_order_nullweight=='setdefault') { ?> selected="selected" <?php } ?>
			  ><?php echo $text_nullweight_show; ?></option>
			  
			  <option value="hide"
			  <?php if( $russianpost2_order_nullweight=='hide' ) { ?> selected="selected" <?php } ?>
			  ><?php echo $text_nullweight_hide; ?></option>
			  
			  
            </select>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_order_default_weight; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control"  name="russianpost2_order_default_weight" 
				value="<?php echo $russianpost2_order_default_weight; ?>">
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_order_replace_weight; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_order_replace_weight">
              <?php if ($russianpost2_order_replace_weight) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?> 
            </select>
			<div><b><?php echo $entry_order_replace_weight_notice; ?></b></div>
			</div>
        </div>
		
		<!-- -->
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_product_nullsize; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_product_nullsize">
               
			  <option value="setdefault"
			  <?php if ($russianpost2_product_nullsize=='setdefault') { ?> selected="selected" <?php } ?>
			  ><?php echo $text_product_nullsize_setdefault; ?></option>
			  
			  <option value="setnull"
			  <?php if( $russianpost2_product_nullsize=='setnull' ) { ?> selected="selected" <?php } ?>
			  ><?php echo $text_product_nullsize_setnull; ?></option>
			  
			  
            </select>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_product_default_size; ?>
			</label>
            <div class="col-sm-10">
				<table >
				<tr>
					<td>
						<input type="text" class="form-control"  name="russianpost2_product_default_width" 
						value="<?php echo $russianpost2_product_default_width; ?>" style="width: 50px;">
					</td>
					<td>x</td>
					<td>
						<input type="text" class="form-control"  name="russianpost2_product_default_height" 
						value="<?php echo $russianpost2_product_default_height; ?>" style="width: 50px;">
					</td>
					<td>x</td>
					<td>
						<input type="text" class="form-control"  name="russianpost2_product_default_length" 
						value="<?php echo $russianpost2_product_default_length; ?>" style="width: 50px;">
					</td>
				</tr>	
				</table>		
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_product_replace_size; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_product_replace_size">
              <?php if ($russianpost2_product_replace_size) { ?>
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
				<?php echo $entry_order_nullsize; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_order_nullsize">
              
			  <option value="setdefault"
			  <?php if ($russianpost2_order_nullsize=='setdefault') { ?> selected="selected" <?php } ?>
			  ><?php echo $text_order_nullsize_setdefault; ?></option>
			  
			  <option value="setnull"
			  <?php if( $russianpost2_order_nullsize=='hide' ) { ?> selected="selected" <?php } ?>
			  ><?php echo $text_nullsize_hide; ?></option>
			  
			  
            </select>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_order_default_size; ?>
			</label>
            <div class="col-sm-10">
				<table >
				<tr>
					<td>
						<input type="text" class="form-control"  name="russianpost2_order_default_width" 
						value="<?php echo $russianpost2_order_default_width; ?>" style="width: 50px;">
					</td>
					<td>x</td>
					<td>
						<input type="text" class="form-control"  name="russianpost2_order_default_height" 
						value="<?php echo $russianpost2_order_default_height; ?>" style="width: 50px;">
					</td>
					<td>x</td>
					<td>
						<input type="text" class="form-control"  name="russianpost2_order_default_length" 
						value="<?php echo $russianpost2_order_default_length; ?>" style="width: 50px;">
					</td>
				</tr>	
				</table>		
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_order_replace_size; ?>
			</label>
            <div class="col-sm-10">
			<select class="form-control"  name="russianpost2_order_replace_size">
              <?php if ($russianpost2_order_replace_size) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?> 
            </select>
			<div><b><?php echo $entry_order_replace_size_notice; ?></b></div>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_is_custom_calc_function; ?>
			</label>
            <div class="col-sm-10">
				<select class="form-control"  name="russianpost2_is_custom_calc_function">
              <?php if ($russianpost2_is_custom_calc_function) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
			<div><?php echo $entry_is_custom_calc_function_notice; ?></div>
			</div>
        </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
            <div class="col-sm-10">
              <select name="russianpost2_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $russianpost2_tax_class_id ) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"
			><?php echo $entry_is_no_insurance_limit; ?></label>
            <div class="col-sm-10">
              <select name="russianpost2_is_no_insurance_limit" class="form-control">
                <option value="0"
				<?php if( !$russianpost2_is_no_insurance_limit ) { ?> selected <?php } ?>
				><?php echo $entry_is_no_insurance_limit_hide; ?></option> 
                 <option value="1"
				<?php if( $russianpost2_is_no_insurance_limit == 1 ) { ?> selected <?php } ?>
				><?php echo $entry_is_no_insurance_limit_show; ?></option> 
                <option value="2"
				<?php if( $russianpost2_is_no_insurance_limit == 2 ) { ?> selected <?php } ?>
				><?php echo $entry_is_no_insurance_limit_show2; ?></option> 
              </select>
            </div>
          </div>
		  
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"
			><?php echo $entry_is_pack_limit; ?></label>
            <div class="col-sm-10">
              <select name="russianpost2_is_pack_limit" class="form-control">
                <option value="nopack"
				<?php if( $russianpost2_is_pack_limit == 'nopack' ) { ?> selected <?php } ?>
				><?php echo $entry_is_pack_limit_nopack; ?></option> 
                <option value="hide"
				<?php if( $russianpost2_is_pack_limit == 'hide' ) { ?> selected <?php } ?>
				><?php echo $entry_is_pack_limit_hide; ?></option> 
              </select>
            </div>
          </div>
		  
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_is_nds; ?></label>
            <div class="col-sm-10">
			
				<select class="form-control"  name="russianpost2_is_nds">
				  <?php if ($russianpost2_is_nds) { ?>
				  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				  <option value="0"><?php echo $text_disabled; ?></option>
				  <?php } else { ?>
				  <option value="1"><?php echo $text_enabled; ?></option>
				  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				  <?php } ?>
				</select>
			
            </div>
          </div>
		  
		  
<?php		/* start 510 */ ?>
		  
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"
			><?php echo $entry_okrugl; ?></label>
            <div class="col-sm-10">
			
				<select class="form-control"  name="russianpost2_okrugl">
				  <option value="" 
				  <?php if (!$russianpost2_okrugl) { ?> selected="selected" <?php } ?>
				  ><?php echo $entry_okrugl_no; ?></option>
				  <option value="round" 
				  <?php if ($russianpost2_okrugl == 'round') { ?> selected="selected" <?php } ?>
				  ><?php echo $entry_okrugl_round; ?></option>
				  <option value="ceil" 
				  <?php if ($russianpost2_okrugl == 'ceil') { ?> selected="selected" <?php } ?>
				  ><?php echo $entry_okrugl_ceil; ?></option>
				  <option value="floor" 
				  <?php if ($russianpost2_okrugl == 'floor') { ?> selected="selected" <?php } ?>
				  ><?php echo $entry_okrugl_floor; ?></option>
				  <?php		/* start 901 */ ?>
				  <option value="10ceil" 
				  <?php if ($russianpost2_okrugl == '10ceil') { ?> selected="selected" <?php } ?>
				  ><?php echo $entry_okrugl_10ceil; ?></option>
				  <?php		/* end 901 */ ?>
				  
				</select>
			
            </div>
          </div>
<?php		/* end 510 */ ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
			<?php /* start 2602 */ ?>
				<input type="text" class="form-control"  name="russianpost2_sort_order" 
				value="<?php echo $russianpost2_sort_order; ?>"
				id="russianpost2_sort_order"
				<?php if( $russianpost2_sort_order_type == 'absolute' ) { ?>
				disabled
				<?php } ?>
				>
				
				<select class="form-control"  name="russianpost2_sort_order_type"
				onchange="if( this.value == 'absolute' ) $('#russianpost2_sort_order').prop('disabled', true); else $('#russianpost2_sort_order').prop('disabled', false);"
				>
				  <option value="relative" 
				  <?php if ($russianpost2_sort_order_type == 'relative') { ?> selected="selected" <?php } ?>
				  ><?php echo $entry_sort_order_relative; ?></option> 
				  <option value="absolute" 
				  <?php if ($russianpost2_sort_order_type == 'absolute') { ?> selected="selected" <?php } ?>
				  ><?php echo $entry_sort_order_absolute; ?></option> 
				</select>
				<?php /* end 2602 */ ?>
			
            </div>
          </div>
		<!-- -->  
		</div>
		<div id="tab-methods" class="tab-pane">
		
				  <p><?php echo $text_russianpost2_method_service; ?></p>
		<div style="background-color: #fef8f4; border: 1px #ddd solid; padding: 10px;"><?php echo $text_tags_notice; ?></div>
<hr>
		
		<div id="methods_list">
		
		
         <?php $method_row = 0; ?>
         <?php foreach ($russianpost2_methods as $method) { 
		 
		 
		 ?>
		
          <table id="methods<?php echo $method_row; ?>" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-center" style="text-transform: uppercase;" colspan=7><?php echo $text_method_group; ?><?php echo $method['name']; ?> (<?php echo $method['code']; ?>)</td>
              </tr>
              <tr>
                <td class="text-left"><?php echo $col_method_code; ?></td>
                <td class="text-left"><?php echo $col_method_image; ?></td>
                <td class="text-left"><?php echo $col_method_title; ?></td>
                <td class="text-left"><?php echo $col_method_sort_order; ?></td>
                <td class="text-left"><?php echo $col_method_filter; ?></td>
                <td class="text-left"><?php echo $col_method_status; ?></td>
                <td class="text-left"></td>
              </tr>
            </thead>
            <tbody>
              <tr id="method-row<?php echo $method_row; ?>">
				  <td class="text-left"> 
					<?php echo $method['code']; ?>
					<input type="hidden" name="russianpost2_methods[<?php echo $method_row; ?>][code]" value="<?php echo $method['code']; ?>" id="<?php echo $method['code']; ?>">
				  </td>
				  
				  <td class="text-left" style="width: 150px;">
					<div style="margin-bottom: 5px;">
					<a href="" id="thumb-image<?php echo $method_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $method['thumb']; ?>" alt="" title="" data-placeholder="" /></a>
					<input type="hidden" name="russianpost2_methods[<?php echo $method_row; ?>][image]" value="<?php echo $method['image']; ?>" id="input-image<?php echo $method_row; ?>" />
					</div>
					
					
					<div>
					<input type="text" name="russianpost2_methods[<?php echo $method_row; ?>][image_width]" value="<?php echo $method['image_width']; ?>" class="form-control" style="width: 50px; float: left;" /><span style="float: left; padding: 5px;"> x </span><input type="text" name="russianpost2_methods[<?php echo $method_row; ?>][image_height]" value="<?php echo $method['image_height']; ?>" class="form-control" style="width: 50px; float: left;"  />
					</div>
					<br style="clear: both;">
					<div><input type="checkbox" name="russianpost2_methods[<?php echo $method_row; ?>][is_show_image]" id="is_show_image_<?php echo $method_row; ?>" 
					<?php if( !empty( $method['is_show_image'] ) ) { ?>  checked <?php } ?>
					><label for="is_show_image_<?php echo $method_row; ?>"><?php echo $text_show_image; ?></label></div>
				  </td>
				  
				<td class="text-left"><?php foreach ($languages as $language) { ?>
                  <div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
                    <input type="text" name="russianpost2_methods[<?php echo $method_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($method['title'][$language['language_id']]) ? $method['title'][$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_method_title; ?>" class="form-control" />
                  </div>
                  <?php if (isset($error_method[$method_row][$language['language_id']])) { ?>
                  <div class="text-danger"><?php echo $error_method[$method_row][$language['language_id']]; ?></div>
                  <?php } ?>
                  <?php } ?></td>
				  <td  class="text-left" style="width: 15%;"><input type="text" name="russianpost2_methods[<?php echo $method_row; ?>][sort_order]" value="<?php echo $method['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />


<?php /* start 2602 */ ?> 
					<table border=0 width=100% 
					id="sort_orders<?php echo $method_row; ?>"  
class="table table-striped table-bordered table-hover">
					<tbody class="tbody2_class">
<?php if( !empty($method['sort_orders']) ) { 
	foreach($method['sort_orders'] as $x=>$sort_order) { ?>	

					<tr id="sort_orders<?php echo $method_row; ?>-<?php echo $x; ?>">
						<td style="padding: 5px;">
							<input type="text" 
name="russianpost2_methods[<?php echo $method_row; ?>][sort_orders][<?php echo $x; ?>][sort_order]" 
value="<?php echo $sort_order['sort_order']; ?>" 
placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
						 
						<select 
name="russianpost2_methods[<?php echo $method_row; ?>][sort_orders][<?php echo $x; ?>][filter]" 
class="form-control"> 
<?php foreach($russianpost2_order_filters as $ft) { ?>
					<option value="<?php echo $ft['filter_id']; ?>"
					<?php if( $ft['filter_id'] == $sort_order['filter'] ) { ?> selected <?php } ?>
					><?php echo $ft['filtername']; ?></option>
<?php } ?>
	</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" 
onclick="$('#sort_orders<?php echo $method_row; ?>-<?php echo $x; ?>, .tooltip').remove();" 
						data-toggle="tooltip" 
						class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } ?>					
					</tbody>
					<tfoot>
					<tr>
						<td colspan=3 style="padding: 5px;"><button type="button" 
						onclick="addMethodsSort('<?php echo $method_row; ?>');" 
						data-toggle="tooltip" title="<?php echo $button_add_sort; ?>" 
						class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
					</tr>
					</tfoot>
				</table>

<?php /* end 2602 */ ?>		

</td>
				  
				  <td class="text-left">
				  
				  
					<table border=0 width=100% id="method_filters<?php echo $method_row; ?>"  class="table table-striped table-bordered table-hover">
					<tbody class="tbody2_class">
<?php if( !empty($method['filters']) ) { foreach($method['filters'] as $x=>$filter_id) { ?>	

					<tr id="method_filters<?php echo $method_row; ?>-<?php echo $x; ?>">
						<td style="padding: 5px;">
		
					<select name="russianpost2_methods[<?php echo $method_row; ?>][filters][]" class="form-control"><option value=""><?php echo $text_select_filter; ?></option>

<?php foreach($russianpost2_order_filters as $ft) { ?>
					<option value="<?php echo $ft['filter_id']; ?>"
					<?php if( $ft['filter_id'] == $filter_id ) { ?> selected <?php } ?>
					><?php echo $ft['filtername']; ?></option>
<?php } ?>
					</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" onclick="$('#method_filters<?php echo $method_row; ?>-<?php echo $x; ?>, .tooltip').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } ?>					
					</tbody>
					<tfoot>
					<tr>
						<td colspan=2 style="padding: 5px;"><button type="button" onclick="addOrdersFilterBlock('method_filters<?php echo $method_row; ?>', 'russianpost2_methods', '<?php echo $method_row; ?>');" data-toggle="tooltip" title="<?php echo $button_add_filter; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
					</tr>
					</tfoot>
				</table>
				  
				  </td>
				  <td class="text-left" > 
					<?php if( $method['code'] == 'russianpost' ) { ?>
					<select name="russianpost2_methods[<?php echo $method_row; ?>][status]" class="form-control">
						<?php if( !empty( $method['status']) ) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
					<?php } else { ?>
					<input type="hidden" name="russianpost2_methods[<?php echo $method_row; ?>][status]" value="1">
					<?php echo $text_enabled; ?>
					<?php } ?>
				  </td>
					<td>
						<button type="button" onclick="$('#methods<?php echo $method_row; ?>').remove();" data-toggle="tooltip" title="" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
              </tr>
			  <tr>
			  <td colspan="7" style="padding: 10px;">
				<table id="submethods<?php echo $method_row; ?>" class="table table-striped table-bordered table-hover">
				<tbody class="tbody"> 
				
					<?php if( !empty($method['submethods']) ) { 
					/* start 2002 */
					$submethod_row[$method_row] = 1;
					
					foreach($method['submethods'] as $submethod) { 
					$submethod['code'] = $method['code'].'.rp'.$submethod_row[$method_row];
					/* end 2002 */
					?>
				
					<tr id="submethod-row<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>">
						  <td class="text-left" rowspan=2> 
							<?php echo $submethod['code']; ?>
							<input type="hidden" name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][code]" value="<?php echo $submethod['code']; ?>" id="<?php echo $submethod['code']; ?>">
						  </td>
						  
						  <td class="text-left">
							<div style="margin-bottom: 5px; width: 150px;">
							<a href="" id="thumb-image<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $submethod['thumb']; ?>" alt="" title="" data-placeholder="" /></a>
							<input type="hidden" name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][image]"

							id="input-image<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>"
							
							value="<?php echo $submethod['image']; ?>" />
							</div>
							<div>
							<input type="text" name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][image_width]" value="<?php echo $submethod['image_width']; ?>" class="form-control" style="width: 50px; float: left;" /><span style="float: left; padding: 5px;"> x </span><input type="text" name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][image_height]" value="<?php echo $submethod['image_height']; ?>" class="form-control" style="width: 50px; float: left;"  />
							</div>					
							
							<br style="clear: both;">

							<div><input type="checkbox" name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][is_show_image]" id="is_show_image_<?php echo $method_row; ?>_<?php echo $submethod_row[$method_row]; ?>" 
							<?php if( !empty( $submethod['is_show_image'] ) ) { ?>  checked <?php } ?>
							><label for="is_show_image_<?php echo $method_row; ?>_<?php echo $submethod_row[$method_row]; ?>"><?php echo $text_show_image; ?></label></div>
							
							
						  </td>
						  
						<td class="text-left"><?php foreach ($languages as $language) { ?>
						  <div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
							<input type="text" name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($submethod['title'][$language['language_id']]) ? $submethod['title'][$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_method_title; ?>" class="form-control" />
						  </div>
						  <?php if (isset($error_method[$method_row][$language['language_id']])) { ?>
						  <div class="text-danger"><?php echo $error_method[$method_row][$language['language_id']]; ?></div>
						  <?php } ?>
						  <?php } ?>
						  
						  
				<div  id="desclink<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>">
				<a href="javascript: $('#desc<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>').show(); $('#desclink<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>').hide();"><?php echo $text_description_link; ?></a>
				</div>
<div><?php echo $text_tags_notice2; ?></div>
				<div style="display: none;" id="desc<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>" >
				<div><?php echo $text_description; ?></div>
				  <?php foreach ($languages as $language) { ?>
                  <div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
                  <textarea rows=3 name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][desc][<?php echo $language['language_id']; ?>]" 
				   class="form-control" ><?php echo isset($submethod['desc'][$language['language_id']]) ? $submethod['desc'][$language['language_id']] : ''; ?></textarea>
				  </div>
                  <?php } ?>
				</div>
						  
						  
						  </td>
						  <td  class="text-left" style="width: 10%;"><input type="text" name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][sort_order]" value="<?php echo $submethod['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
						 
						  </td>
						  
						  <td class="text-left" style="min-width: 200px;">
						  
						  
						  
					<table border=0 width=100% id="submethod_filters<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>"  class="table table-striped table-bordered table-hover">
					<tbody class="tbody2_class">
<?php if( !empty($submethod['filters']) ) { foreach($submethod['filters'] as $x=>$filter_id) { ?>	

					<tr id="submethod_filters<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>-<?php echo $x; ?>">
						<td style="padding: 5px;">
		
					<select name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][filters][]" class="form-control"><option value=""><?php echo $text_select_filter; ?></option>

<?php foreach($russianpost2_order_filters as $ft) { ?>
					<option value="<?php echo $ft['filter_id']; ?>"
					<?php if( $ft['filter_id'] == $filter_id ) { ?> selected <?php } ?>
					><?php echo $ft['filtername']; ?></option>
<?php } ?>
					</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" onclick="$('#submethod_filters<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>-<?php echo $x; ?>, .tooltip').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } ?>					
					</tbody>
					<tfoot>
					<tr>
						<td colspan=2 style="padding: 5px;"><button type="button" onclick="addOrdersFilterBlock('submethod_filters<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>', 'russianpost2_methods[<?php echo $method_row; ?>][submethods]', '<?php echo $submethod_row[$method_row]; ?>');" data-toggle="tooltip" title="<?php echo $button_add_filter; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
					</tr>
					</tfoot>
				</table>
				  
						  
						  </td>
						  
						  
						  <td class="text-left" > 
							<select name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][status]" class="form-control">
								<?php if( !empty( $submethod['status']) ) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						  </td>
						  
						  
						<td class="text-left">
						<button type="button" onclick="$('#submethod-row<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>, .tooltip').remove(); $('#submethod-row<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>-line2, .tooltip').remove(); " data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
						</td>
					</tr>
					
					<tr id="submethod-row<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>-line2">
					<td class="text-right" > <?php echo $text_services; ?></td>

					<td class="text-right" >
				<table border=0 width=100% id="servtab<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>">
					<tbody>
<?php if( !empty($submethod['services']) ) { foreach($submethod['services'] as $x=>$service) { ?>	

					<tr id="serv<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>-<?php echo $x; ?>">
						<td style="padding: 5px;">
		
					<select name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][services][<?php echo $x; ?>][service]" class="form-control"><option value=""><?php echo $text_add_service; ?></option>

					<?php /* start 2801 */ ?>
					<option value="" disabled
					>-------------</option>
					<option value="free"
					<?php if( $service['service'] == 'free' ) { ?> selected <?php } ?>
					><?php echo $text_free_service; ?></option>
<?php if( !empty($russianpost2_customs) ) { foreach($russianpost2_customs as $custom) { ?>
					<option value="<?php echo $custom['custom_id']; ?>"
					<?php if( $service['service'] == $custom['custom_id'] ) { ?> selected <?php } ?>
					><?php echo $custom['name']; ?></option>
<?php } }?>
					<option value="" disabled
					>-------------</option>
					<?php /* end 2801 */ ?>
<?php foreach($services_list as $serv) { ?>
					<option value="<?php echo $serv['service_key']; ?>"
					<?php if( $serv['service_key'] == $service['service'] ) { ?> selected <?php } ?>
					><?php echo $serv['service_name']; ?></option>
<?php } ?>
					</select>
					</td>
					<td>
						<select name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][services][<?php echo $x; ?>][filter]" class="form-control"><option value=""><?php echo $text_no_filter; ?></option>
<?php foreach($russianpost2_order_filters as $ft) { ?>
					<option value="<?php echo $ft['filter_id']; ?>"
					<?php if( $ft['filter_id'] == $service['filter'] ) { ?> selected <?php } ?>
					><?php echo $ft['filtername']; ?></option>
<?php } ?>
	</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" onclick="$('#serv<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>-<?php echo $x; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } else { $x = 0; ?> 
<tr id="serv<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>-0">
						<td style="padding: 5px;">
<select name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][services][<?php echo $x; ?>][service]" class="form-control">

					<option value=""><?php echo $text_add_service; ?></option>

<?php foreach($services_list as $serv) { ?>
					<option value="<?php echo $serv['service_key']; ?>"
					><?php echo $serv['service_name']; ?></option>
<?php } ?>
					</select></td>
					<td style="padding: 5px;">
						<select name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][services][<?php echo $x; ?>][filter]" class="form-control"><option value=""><?php echo $text_no_filter; ?></option>
<?php foreach($russianpost2_order_filters as $ft) { ?>
					<option value="<?php echo $ft['filter_id']; ?>"><?php echo $ft['filtername']; ?></option>
<?php } ?>
	</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" onclick="$('#serv<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>-0, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>

<?php } ?>			
			</tbody>
			<tfoot>
			<tr>
				<td colspan=3 style="padding: 5px;"><button type="button" onclick="addServiceBlock('<?php echo $method_row; ?>', '<?php echo $submethod_row[$method_row]; ?>');" data-toggle="tooltip" title="<?php echo $button_add_service; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
			</tr>
			</tfoot>
		</table>
					
					</td><?php /* start metka-407 */ ?>
					<td>
						
						  <?php /* start 1606 */ ?>
						  <div>
						  <?php echo $text_is_show_cod; ?><br>
						   <select name="russianpost2_methods[<?php echo $method_row; 
						  ?>][submethods][<?php echo $submethod_row[$method_row]; 
						  ?>][is_show_cod]" class="form-control"  style="max-width: 250px;" >
							  <?php if( !empty( $submethod['is_show_cod'] ) ) { ?>
							  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							  <option value="0"><?php echo $text_disabled; ?></option>
							  <?php } else { ?>
							  <option value="1"><?php echo $text_enabled; ?></option>
							  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							  <?php } ?>
							</select>
						  
						  </div>
						<?php echo $text_services_sorttype; ?>
						<select name="russianpost2_methods[<?php echo $method_row; ?>][submethods][<?php echo $submethod_row[$method_row]; ?>][services_sorttype]" class="form-control">
						
						<option value="minprice" <?php if( $submethod['services_sorttype'] == 'minprice' ) { ?> selected <?php } ?>><?php echo $text_services_sorttype_minprice; ?></option>
						<option value="minsrok" <?php if( $submethod['services_sorttype'] == 'minsrok' ) { ?> selected <?php } ?>><?php echo $text_services_sorttype_minsrok; ?></option>
						
						<option value="order" <?php if( $submethod['services_sorttype'] == 'order' ) { ?> selected <?php } ?>><?php echo $text_services_sorttype_order; ?></option>
						</select>
					
						  <?php /* end 1606 */ ?>
						  
					</td>
					<td colspan=3>
					
						  <?php /* start 1606 */ ?> 
						  <div><?php echo $text_is_pack; ?></div>
						  
						  <select name="russianpost2_methods[<?php echo $method_row; 
						  ?>][submethods][<?php echo $submethod_row[$method_row]; 
						  ?>][is_pack]" class="form-control" style="max-width: 300px;" >
						  
						  
							<option value=""><?php echo $text_disabled; ?></option>
							<option value="" disabled>-------------</option> 
							<option value="1" 
							<?php if( !empty($submethod['is_pack']) && $submethod['is_pack'] == 1 ) { ?>
							selected
							<?php } ?>
							><?php echo $text_autoselect; ?></option>
							<option value="" disabled>-------------</option> 
							<?php if( $custom_packs ) { ?>
							<?php foreach($custom_packs as $i=>$custom_pack) { ?>
							<option value="c<?php echo $i; ?>" 
							<?php if(  !empty($submethod['is_pack']) && $submethod['is_pack'] == 'c'.$i ) { ?> selected <?php } ?>
							><?php echo $custom_pack['name']; ?></option> 
							<?php } ?>
							<option value="" disabled>-------------</option> 
							<?php } ?>
							<?php foreach($packs as $pack) { ?>
						  <option value="<?php echo $pack['pack_key']; ?>" 
							<?php if( !empty($submethod['is_pack']) && $submethod['is_pack'] == $pack['pack_key'] ) { ?> selected <?php } ?>
							><?php echo $pack['name']; ?></option>
							<?php } ?>
						  </select>
					
						<?php echo $text_services_adds; ?>
						
						<?php /* start 0110 */ ?>
						 
					<table border=0 width=100% 
					id="submethod_adds<?php echo $method_row; ?>-<?php echo $submethod_row[$method_row]; ?>"  class="table table-striped table-bordered table-hover">
					<tbody class="tbody2_class">
<?php if( !empty($submethod['adds']) ) { foreach($submethod['adds'] as $x=>$adds_id) { ?>	

					<tr id="submethod_adds<?php echo $method_row; 
					?>-<?php echo $submethod_row[$method_row]; ?>-<?php echo $x; ?>">
						<td style="padding: 5px;">
		
					<select name="russianpost2_methods[<?php echo $method_row; 
					?>][submethods][<?php echo $submethod_row[$method_row]; ?>][adds][]"
					class="form-control"><option value=""><?php echo $text_select_adds; ?></option>

<?php foreach($russianpost2_method_adds as $ad) { ?>
					<option value="<?php echo $ad['adds_id']; ?>"
					<?php if( $ad['adds_id'] == $adds_id ) { ?> selected <?php } ?>
					><?php echo $ad['name']; ?></option>
<?php } ?>
					</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" onclick="$('#submethod_adds<?php 
						echo $method_row; ?>-<?php echo $submethod_row[$method_row]; 
						?>-<?php echo $x; ?>, .tooltip').remove();" 
						data-toggle="tooltip" class="btn btn-danger"><i 
						class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } ?>					
					</tbody>
					<tfoot>
					<tr>
						<td colspan=2 style="padding: 5px;"><button type="button" 
						onclick="addMethodsAddsBlock('submethod_adds<?php echo $method_row; 
						?>-<?php echo $submethod_row[$method_row]; ?>', 'russianpost2_methods[<?php 
						echo $method_row; ?>][submethods]', '<?php echo $submethod_row[$method_row]; 
						?>');" data-toggle="tooltip" title="<?php echo $button_add_adds; ?>" 
						class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
					</tr>
					</tfoot>
				</table>
						<?php /* end 0110 */ ?> 
					
					
					</td>
					</tr>
					
					<?php $submethod_row[$method_row]++; } } ?>
					
				</tbody>
				<tfoot>
				  <tr>
					<td colspan="7" class="text-right"><button type="button" onclick="addSubMethod(<?php echo $method_row; ?>, '<?php echo $method['code']; ?>');" data-toggle="tooltip" title="<?php echo $button_method_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?php echo $button_method_add; ?></button></td>
				  </tr>
				</tfoot>
				</table>
			  
			  </td>
			  </tr>
            </tbody>
          </table>
              <?php $method_row++; ?>
              <?php } ?>
		  </div>
		  
		  <button type="button" onclick="addMethod();" data-toggle="tooltip" title="<?php echo $button_groupmethod_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?php echo $button_groupmethod_add; ?></button>
			<br><br>
		  <p><?php echo $text_methods_notice; ?></p>
		  <p><?php echo $text_split_notice; ?></p>
		
			<h2><?php echo $text_tags_header; ?></h2>
			<table border=0 width=100%  class="table table-bordered table-hover">
			<thead>
				<tr>
					<td><?php echo $col_tag_name; ?></td>
					<td><?php echo $col_tag_description; ?></td>
					<td><?php echo $col_tag_field; ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{service_name}</td>
					<td><?php echo $text_tag_service_name; ?></td>
					<td style="width: 50%;"><?php echo $text_tag_service_name_example; ?></td>
				</tr>
				<tr>
					<td>{service_name_z}</td>
					<td><?php echo $text_tag_service_name_z; ?></td>
					<td><?php echo $text_tag_service_name_z_example; ?></td>
				</tr>
				<tr>
					<td>{from_region}</td>
					<td><?php echo $text_tag_from_region; ?></td>
					<td><?php echo $text_tag_from_region_example; ?></td>
				</tr>
				<tr>
					<td>{from_city}</td>
					<td><?php echo $text_tag_from_city; ?></td>
					<td><?php echo $text_tag_from_city_example; ?></td>
				</tr>
				<tr>
					<td>{to_country}</td>
					<td><?php echo $text_tag_to_country; ?></td>
					<td><?php echo $text_tag_to_country_example; ?></td>
				</tr>
				<tr>
					<td>{country_block}</td>
					<td><?php echo $text_tag_country_block; ?></td>
					<td>
						<?php foreach ($languages as $language) { ?>
						<div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
							<input type="text" name="russianpost2_tag_country_block[<?php echo $language['language_id']; ?>]" value="<?php echo isset($russianpost2_tag_country_block[$language['language_id']]) ? $russianpost2_tag_country_block[$language['language_id']] : ''; ?>" class="form-control" />
						</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>{to_region}</td>
					<td><?php echo $text_tag_to_region; ?></td>
					<td><?php echo $text_tag_to_region_example; ?></td>
				</tr>
				<tr>
					<td>{to_city}</td>
					<td><?php echo $text_tag_to_city; ?></td>
					<td><?php echo $text_tag_to_city_example; ?></td>
				</tr>
				
				<tr>
					<td>{insurance}</td>
					<td><?php echo $text_tag_insurance; ?></td>
					<td><?php echo $text_tag_insurance_example; ?></td>
				</tr>
				
				<tr>
					<td>{insurance_block}</td>
					<td><?php echo $text_tag_insurance_block; ?></td>
					<td>
					<?php foreach ($languages as $language) { ?>
                  <div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
                    <input type="text" name="russianpost2_tag_insurance_block[<?php echo $language['language_id']; ?>]" value="<?php echo isset($russianpost2_tag_insurance_block[$language['language_id']]) ? $russianpost2_tag_insurance_block[$language['language_id']] : ''; ?>" class="form-control" />
                  </div>
                  <?php } ?></td>
				</tr>
				<tr>
					<td>{commission}</td>
					<td><?php echo $text_tag_commission; ?></td>
					<td><?php echo $text_tag_commission_example; ?></td>
				</tr>
				<tr>
					<td>{commission_block}</td>
					<td><?php echo $text_tag_commission_block; ?></td>
					<td>
					<?php foreach ($languages as $language) { ?>
                  <div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
                    <input type="text" name="russianpost2_tag_commission_block[<?php echo $language['language_id']; ?>]" value="<?php echo isset($russianpost2_tag_commission_block[$language['language_id']]) ? $russianpost2_tag_commission_block[$language['language_id']] : ''; ?>" class="form-control" />
                  </div>
                  <?php } ?></td>
				</tr>
				<tr>
					<td>{srok}</td>
					<td><?php echo $text_tag_srok; ?></td>
					<td><?php echo $text_tag_srok_example; ?></td>
				</tr>
				<tr>
					<td>{srok_block}</td>
					<td><?php echo $text_tag_srok; ?></td>
					<td>
					<?php foreach ($languages as $language) { ?>
                  <div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
                    <input type="text" name="russianpost2_tag_srok_block[<?php echo $language['language_id']; ?>]" value="<?php echo isset($russianpost2_tag_srok_block[$language['language_id']]) ? $russianpost2_tag_srok_block[$language['language_id']] : ''; ?>" class="form-control" />
                  </div>
                  <?php } ?>
				  </td>
				</tr>
				<?php /* start 2308 */ ?>
				<tr>
					<td>{shipping_cost}</td>
					<td><?php echo $text_tag_shipping_cost; ?></td>
					<td><?php echo $text_tag_shipping_cost_example; ?></td>
				</tr>
				<?php /* end 2308 */ ?>
				<?php /* start 0510 */ ?>
				<tr>
					<td>{weight_kg}</td>
					<td><?php echo $text_tag_weight_kg; ?></td>
					<td><?php echo $text_tag_weight_kg_example; ?></td>
				</tr>
				<tr>
					<td>{weight_g}</td>
					<td><?php echo $text_tag_weight_g; ?></td>
					<td><?php echo $text_tag_weight_g_example; ?></td>
				</tr>
				<tr>
					<td>{dimensions_cm}</td>
					<td><?php echo $text_tag_dimensions_cm; ?></td>
					<td><?php echo $text_tag_dimensions_cm_example; ?></td>
				</tr>
				<tr>
					<td>{pvz_block}</td>
					<td><?php echo $text_tag_pvz_block; ?></td>
					<td>
						<?php foreach ($languages as $language) { ?>
							<div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
								<input type="text" name="russianpost2_tag_pvz_block[<?php echo $language['language_id']; ?>]" value="<?php echo isset($russianpost2_tag_pvz_block[$language['language_id']]) ? $russianpost2_tag_pvz_block[$language['language_id']] : ''; ?>" class="form-control" />
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>{pvz_partners_block}</td>
					<td><?php echo $text_tag_pvz_partners_block; ?></td>
					<td>
						<?php foreach ($languages as $language) { ?>
							<div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
								<input type="text" name="russianpost2_tag_pvz_partners_block[<?php echo $language['language_id']; ?>]" value="<?php echo isset($russianpost2_tag_pvz_partners_block[$language['language_id']]) ? $russianpost2_tag_pvz_partners_block[$language['language_id']] : ''; ?>" class="form-control" />
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>{pvz_rupost_block}</td>
					<td><?php echo $text_tag_pvz_rupost_block; ?></td>
					<td>
						<?php foreach ($languages as $language) { ?>
							<div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
								<input type="text" name="russianpost2_tag_pvz_rupost_block[<?php echo $language['language_id']; ?>]" value="<?php echo isset($russianpost2_tag_pvz_rupost_block[$language['language_id']]) ? $russianpost2_tag_pvz_rupost_block[$language['language_id']] : ''; ?>" class="form-control" />
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>{ops_block}</td>
					<td><?php echo $text_tag_ops_block; ?></td>
					<td>
						<?php foreach ($languages as $language) { ?>
							<div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> 
								<input type="text" name="russianpost2_tag_ops_block[<?php echo $language['language_id']; ?>]" value="<?php echo isset($russianpost2_tag_ops_block[$language['language_id']]) ? $russianpost2_tag_ops_block[$language['language_id']] : ''; ?>" class="form-control" />
							</div>
						<?php } ?>
					</td>
				</tr>
				
			</tbody>
			</table>
			<h2><?php echo $text_image_header; ?></h2>
			<p><?php echo $text_image_notice; ?></p>
				
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_method_image_html; ?>
				</label>
				<div class="col-sm-10">
					<textarea name="russianpost2_method_image_html"  class="form-control"
					><?php echo $russianpost2_method_image_html; ?></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_submethod_image_html; ?>
				</label>
				<div class="col-sm-10">
					<textarea name="russianpost2_submethod_image_html"  class="form-control"
					><?php echo $russianpost2_submethod_image_html; ?></textarea>
				</div>
			</div>
			
			<script>
			function show_hide_inname_block(value)
			{
				if( value == 'inname' ) {
					$('#inname_block').show();
				} 
				else
				{
					$('#inname_block').hide();
				}
				
			}
			</script>
			
			<div class="form-group">
				<label class="col-sm-2  control-label">
					<?php echo $entry_icons_format; ?>
				</label>
				<div class="col-sm-10">
				
			  <table>
			  <tr>
				<td valign=top style="padding-right: 15px;"><input type="radio" name="russianpost2_icons_format" 
				value="inname" 
					id="russianpost2_icons_format_inname"
					<?php if( $russianpost2_icons_format == 'inname' ) { ?> checked <?php } ?>
					onclick="show_hide_inname_block('inname')"
					>
				</td>
				<td>
					<label for="russianpost2_icons_format_inname" style="font-weight: normal;"
					><?php echo $entry_icons_format_inname; ?></label><br><br>
				</td>
			  </tr>
			  <tr>
				<td valign=top style="padding-right: 15px;"><input type="radio" name="russianpost2_icons_format" 
				value="inimage" 
					id="russianpost2_icons_format_inimage"
					<?php if( $russianpost2_icons_format == 'inimage' ) { ?> checked <?php } ?>
					onclick="show_hide_inname_block('inimage')"
					>
				</td>
				<td>
					<label for="russianpost2_icons_format_inimage" style="font-weight: normal;"
					><?php echo $entry_icons_format_inimage; ?></label><br><br>
				</td>
			  </tr>
			  </table>
				
				
				
				</div>
			  </div>
			
			<div class="form-group"  id="inname_block"
				<?php if( $russianpost2_icons_format == 'inimage' ) { 
				?> style="display: none;" <?php } ?> 
			>
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_submethod_image_html; ?>
				</label>
				<div class="col-sm-10">
					<textarea name="russianpost2_submethod_image_html"  class="form-control"
					><?php echo $russianpost2_submethod_image_html; ?></textarea>
				</div>
			</div>
			
			<h2><?php echo $header_pvz; ?></h2>
			<p><?php echo $header_pvz_notice; ?></p>

			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_hide_map_js; ?>
				</label>
				<div class="col-sm-10">
					<select class="form-control"  name="russianpost2_hide_map_js">
					  <?php if ($russianpost2_hide_map_js) { ?>
					  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					  <option value="0"><?php echo $text_disabled; ?></option>
					  <?php } else { ?>
					  <option value="1"><?php echo $text_enabled; ?></option>
					  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					  <?php } ?>
					</select>
					
					<div><?php echo $entry_hide_map_js_notice; ?></div>
				</div>
			</div>
			
			<h2><?php echo $text_pvz_header; ?></h2>
			<p><?php echo $text_pvz_notice; ?></p>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_selectblock; ?>
				</label>
				<div class="col-sm-10"> 
					<?php foreach ($languages as $language) { ?>
					  <textarea cols=40 class="form-control" rows=7
					  name="russianpost2_pvz_selectblock[<?php echo $language['language_id']; ?>]"
					  ><?php echo isset($russianpost2_pvz_selectblock[$language['language_id']]) ? $russianpost2_pvz_selectblock[$language['language_id']] : '';
					  ?></textarea><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_descblock; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <textarea cols=40 class="form-control"  rows=7
					  name="russianpost2_pvz_descblock[<?php echo $language['language_id']; ?>]"
					  ><?php echo isset($russianpost2_pvz_descblock[$language['language_id']]) ? $russianpost2_pvz_descblock[$language['language_id']] : '';
					  ?></textarea><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_selecttitle; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_selecttitle[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_selecttitle[$language['language_id']]) ? $russianpost2_pvz_selecttitle[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_cod; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_cod[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_cod[$language['language_id']]) ? $russianpost2_pvz_cod[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_cod_all; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_cod_all[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_cod_all[$language['language_id']]) ? $russianpost2_pvz_cod_all[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_cod_cashonly; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_cod_cashonly[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_cod_cashonly[$language['language_id']]) ? $russianpost2_pvz_cod_cashonly[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_cod_cardonly; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_cod_cardonly[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_cod_cardonly[$language['language_id']]) ? $russianpost2_pvz_cod_cardonly[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_cod_none; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_cod_none[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_cod_none[$language['language_id']]) ? $russianpost2_pvz_cod_none[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_worktime_block; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_worktime_block[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_worktime_block[$language['language_id']]) ? $russianpost2_pvz_worktime_block[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_worktime_workline_nodinner; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_worktime_workline_nodinner[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_worktime_workline_nodinner[$language['language_id']]) ? $russianpost2_pvz_worktime_workline_nodinner[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_worktime_workline_withdinner; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_worktime_workline_withdinner[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_worktime_workline_withdinner[$language['language_id']]) ? $russianpost2_pvz_worktime_workline_withdinner[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_pvz_worktime_weekendline; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_pvz_worktime_weekendline[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_pvz_worktime_weekendline[$language['language_id']]) ? $russianpost2_pvz_worktime_weekendline[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			
			
			<h2><?php echo $text_ops_header; ?></h2>
			<p><?php echo $text_ops_notice; ?></p>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_ops_selectblock; ?>
				</label>
				<div class="col-sm-10"> 
					<?php foreach ($languages as $language) { ?>
					  <textarea cols=40 class="form-control" rows=7
					  name="russianpost2_ops_selectblock[<?php echo $language['language_id']; ?>]"
					  ><?php echo isset($russianpost2_ops_selectblock[$language['language_id']]) ? $russianpost2_ops_selectblock[$language['language_id']] : '';
					  ?></textarea><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_ops_descblock; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <textarea cols=40 class="form-control"  rows=7
					  name="russianpost2_ops_descblock[<?php echo $language['language_id']; ?>]"
					  ><?php echo isset($russianpost2_ops_descblock[$language['language_id']]) ? $russianpost2_ops_descblock[$language['language_id']] : '';
					  ?></textarea><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_ops_selecttitle; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_ops_selecttitle[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_ops_selecttitle[$language['language_id']]) ? $russianpost2_ops_selecttitle[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_ops_worktime_block; ?>
				</label>
				<div class="col-sm-10">
				
					<?php foreach ($languages as $language) { ?>
					  <input type="text" class="form-control" 
					  name="russianpost2_ops_worktime_block[<?php echo $language['language_id']; ?>]"
					  value="<?php echo isset($russianpost2_ops_worktime_block[$language['language_id']]) ? $russianpost2_ops_worktime_block[$language['language_id']] : '';
					  ?>"
					  ><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					 <?php } ?>
					  
				</div>
			</div> 
		</div>
		
		<div id="tab-service" class="tab-pane">
		
		<table width=100%>
		<tr>
			<td style="width: 200px; vertical-align: top;  ">
			
				<ul class="nav nav-pills nav-stacked">
				
					<li class="<?php if( ( empty($subtab) ) || (!empty($subtab) && $subtab == 'general' ) ) { ?>active<?php } ?> service_links" id="tablink_general"><a href="javascript: showTab('general');"> <?php echo $text_general_options; ?> </a></li>
					
					<?php foreach( $top_services_list as $i=>$service ) { ?>
						<li class="<?php if( !empty($subtab) && $service['service_key'] == $subtab ) { ?>active<?php } ?> service_links" id="tablink_<?php echo $service['service_key']; ?>"><a href="javascript: showTab('<?php echo $service['service_key']; ?>');"> <?php echo $service['service_name']; ?> </a></li>
					<?php } ?>
                  </ul>
			</td>
			<td style="vertical-align: top;  padding-left: 10px;">
				
					<div id="tab_general" class="service_tabs"  <?php if( ( empty($subtab) ) || (!empty($subtab) && $subtab == 'general' ) ) { } else { ?> style="display: none;" <?php } ?>>
					
				  <p><?php echo $text_russianpost2_method_service; ?></p>
							<hr>
					
					
					<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td><?php echo $col_service_name; ?></td>
							<td><?php echo $col_service_name_z; ?></td>
							<td><?php echo $col_service_postcode; ?></td>
							<td><?php echo $col_source; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach( $services2api_list as $i=>$service ) { ?>
						<tr>
							<td><?php echo $service['service_name']; ?></td>
							<td><input type="text" name="russianpost2_services2api_list[<?php echo $service['service_key']; ?>][service_name_z]" class="form-control"
							value="<?php echo $service['service_name_z']; ?>"
							></td>
							<td><input type="text" name="russianpost2_services2api_list[<?php echo $service['service_key']; ?>][postcode]" class="form-control"
							value="<?php echo $service['postcode']; ?>"
							></td>
							<td><select name="russianpost2_services2api_list[<?php echo $service['service_key']; ?>][source]" class="form-control">
								
								<?php foreach( $service['sources'] as $source ) {?>
								<option value="<?php echo $source['source_key']; ?>"
								<?php if( $source['source_key'] == $service['source'] ) { ?> selected <?php } ?>
								><?php echo $source['name']; ?></option>
								<?php } ?>
								
								</select></td>
						</tr>
						<?php } ?>
					</tbody>
					</table>
					
					</div>
				<?php foreach( $top_services_list as $i=>$service ) { ?>
					<div id="tab_<?php echo $service['service_key']; ?>" class="service_tabs"  <?php if( !empty($subtab) && $service['service_key'] == $subtab ) { } else { ?> style="display: none;" <?php } ?>>
					<h3><?php echo $service['service_name']; ?></h3>
					<?php /* start 112 */ 
					if( $service['doclink'] ) {?>
					<p>
					 <a href="<?php echo $service['doclink']; ?>" target=_blank><?php echo $text_tarif_link; ?></a>
					</p>
					<?php } /* end 112 */ ?>
					<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td><?php echo $col_option_name; ?></td>
							<td><?php echo $col_option_status; ?></td>
							<?php /* start 112 * / ?>
							<td><?php echo $col_option_ismethod; ?></td>
							<?php /* end 112 */ ?>
							<td><?php echo $col_option_cost; ?></td>
							<td><?php echo $col_option_condition; ?></td>
							<td style="width: 30%;"><?php echo $col_option_comment; ?></td>
						</tr>
					</thead>
					<tbody>
					<?php  foreach( $service['options'] as $option ) { ?>
						<tr>
							<td><?php echo $option['option_name']; ?>
							<?php /* start 112 */ ?>
							<input type="hidden" 
							name="russianpost2_options[<?php echo $option['service_key']; 
							?>][<?php echo $option['fieldname']; ?>][is_dedicated]" value="1"
							>
							<input type="hidden" 
							name="russianpost2_options[<?php echo $option['service_key']; 
							?>][<?php echo $option['fieldname']; ?>][status]" value=""
							>
							
							<input type="hidden" name="russianpost2_options[<?php 
							echo $option['service_key']; 
							?>][<?php echo $option['fieldname']; ?>][available_tariff_key]"
							
							value="<?php echo $option['available_tariff_key']; ?>"
							>
							<input type="hidden" name="russianpost2_options[<?php
							echo $option['service_key']; 
							?>][<?php echo $option['fieldname']; ?>][tariff_service_id]"
							
							value="<?php echo $option['tariff_service_id']; ?>"
							>
							
							<?php /* end 112 */ ?></td>
							<td class="text-center"><?php if( $option['fieldtype'] == 'checkbox' ) { ?> 
							<input type="checkbox" name="russianpost2_options[<?php echo $option['service_key']; ?>][<?php echo $option['fieldname']; ?>][status]" value="1"
							<?php if( !empty( $option['value'] ) ) { ?> checked <?php } ?>
							>
							<?php /* start 112 */ ?>
							<?php } elseif( $option['fieldtype'] == 'text' ) {  ?>
							
							<input type="text" name="russianpost2_options[<?php 
							echo $option['service_key']; 
							?>][<?php echo $option['fieldname']; ?>][status]" 
							value="<?php if( !empty( $option['value'] ) ) {  echo $option['value']; } ?>"
							>
							<?php /* end 112 */ ?>
							
							<?php } elseif( $option['fieldtype'] == 'select' ) {  ?>
							<select name="russianpost2_options[<?php echo $option['service_key']; ?>][<?php echo $option['fieldname']; ?>][status]">
								<?php foreach($option['values'] as $val) { ?>
								<option value="<?php echo $val; ?>"
								<?php if( $option['value'] && $option['value'] == $val ) { ?> selected <?php } ?>
								><?php echo $val; ?></option>
								<?php } ?>
							</select>
							<?php } ?></td>
							<td class="text-center"><?php if( !empty($option['option_cost']) ) 
									  echo $option['option_cost']; 
							?></td>
							<td><?php if( !empty($option['condition']) ) 
									  echo $option['condition']; 
							?></td>
							<td><?php if( !empty($option['comment']) ) 
									  echo $option['comment']; 
							?></td>
						</tr>
					
					<?php } ?>
					</tbody>
					</table>
					</div>
				<?php } ?>
			</td>
		</tr>
		</table>
		
		</div>
		
		<?php /* start 2801 */ ?>
		<div id="tab-customs" class="tab-pane">
		
		
		<?php echo $text_customs_notice; ?>
		<table id="customs" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td class="text-left"><?php echo $col_customs_name; ?></td>
						<td class="text-left"><?php echo $col_customs_price; ?></td>
						<td class="text-left"><?php echo $col_customs_type; ?></td>
						<td class="text-left"><?php echo $col_customs_status; ?></td>
						<td class="text-left"></td>
					</tr>
				</thead>
				<tbody class="tbody_class">
				<?php $customs_row = 0; ?>
				<?php if( !empty($russianpost2_customs) ) { 
				foreach($russianpost2_customs as $custom) { ?>
				<tr id="customs-row<?php echo $customs_row; ?>">
					<td>
						<input type="hidden" class="form-control" name="russianpost2_customs[<?php 
						echo $customs_row; 
						?>][custom_id]" value="<?php  if( !empty($custom['custom_id']) ) 
														echo $custom['custom_id']; ?>">
						<input type="text" class="form-control" name="russianpost2_customs[<?php 
						echo $customs_row; 
						?>][name]" value="<?php  if( !empty($custom['name']) ) 
														echo $custom['name']; ?>">
					</td>
				
					<td>
						<div class="form-group">
							<div class="col-sm-9">
								<input type="text" class="form-control" 
								name="russianpost2_customs[<?php 
								echo $customs_row; 
								?>][price]" value="<?php  if( !empty($custom['price']) ) 
																echo $custom['price']; ?>">
							</div>
							<div class="col-sm-3"> 
								<select name="russianpost2_customs[<?php 
								echo $customs_row; 
								?>][currency]" class="form-control">
								<?php foreach( $currencies as $currency ) { ?>
									<option value="<?php echo $currency['code']; ?>"
									<?php if( $custom['currency'] == $currency['code'] ) { ?> selected <?php } ?>
									><?php echo $currency['code']; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
							
					</td>
					<td>
						<select class="form-control"  name="russianpost2_customs[<?php echo 
						$customs_row; ?>][type]">
						  <option value="single" 
						  <?php if ( !empty( $custom['type'] ) && $custom['type'] == 'single' ) { ?>
						  selected="selected"
						  <?php } ?>
						  ><?php echo $text_customs_type_single; ?></option>
						  <option value="bycount" 
						  <?php if ( !empty( $custom['type'] ) && $custom['type'] == 'bycount' ) { ?>
						  selected="selected"
						  <?php } ?>
						  ><?php echo $text_customs_type_bycount; ?></option>
						</select>
					</td>
					
					<td>
						<select class="form-control"  name="russianpost2_customs[<?php echo 
						$customs_row; ?>][status]">
						  <?php if( !empty( $custom['status'] ) ) { ?>
						  <option value="1" selected="selected"
						  ><?php echo $text_enabled; ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						  <?php } ?>
						</select>
					</td>
					<td><button type="button" onclick="$('#customs-row<?php echo $customs_row; 
					?>, .tooltip').remove();" data-toggle="tooltip" 
					title="<?php echo $button_remove; ?>" 
					class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
				</tr>
                <?php $customs_row++; ?>
				<?php } ?>
				<?php } ?>
				</tbody>
				<tfoot>
				<tr>
					<td colspan=5 class="text-right">
						<button type="button" onclick="addCustoms();" data-toggle="tooltip" 
						title="<?php echo $button_customs_add; ?>" class="btn btn-primary"
						><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?php echo $button_customs_add; 
						?></button>
					</td>
				</tr>
				</tfoot>
				</table>
		</div>
		<?php /* end 2801 */ ?>
			
		<!-- ------------------------- -->
		<div id="tab-delivery_types" class="tab-pane">
		<h3><?php echo $text_delivery_types_header; ?></h3>
		<script>
		function change_custom(type_key, field_key, value)
		{	
			if( field_key == 'maxlength' )
			{
				if( value == 'auto' )
				{
					$('#delivery_types_'+type_key+'_maxlength').prop('disabled', true);
					$('#delivery_types_'+type_key+'_maxwidth').prop('disabled', true);
					$('#delivery_types_'+type_key+'_maxheight').prop('disabled', true);
				}
				else
				{
					$('#delivery_types_'+type_key+'_maxlength').prop('disabled', false);
					$('#delivery_types_'+type_key+'_maxwidth').prop('disabled', false);
					$('#delivery_types_'+type_key+'_maxheight').prop('disabled', false);
				}
			}
			else
			{
				if( value == 'auto' )
				{
					$('#delivery_types_'+type_key+'_'+field_key).prop('disabled', true);
				}
				else
				{
					$('#delivery_types_'+type_key+'_'+field_key).prop('disabled', false);
				}
			}
		}
		
		</script>
		<table id="product_filters" class="table table-striped table-bordered table-hover">
				<thead>
				  <tr>
					<td class="text-left"><?php echo $text_col_deliverytypes_type_key; ?></td>
					<td class="text-left"><?php echo $text_col_deliverytypes_type_name; ?></td>
					<td class="text-left"><?php echo $text_col_deliverytypes_type_name_z; ?></td>
					<td class="text-left"><?php echo $text_col_deliverytypes_content; ?></td>
					<td class="text-left"><?php echo $text_col_deliverytypes_maxweight; ?></td>
					<td class="text-left"><?php echo $text_col_deliverytypes_maxlength; ?></td>
					<td class="text-left"><?php echo $text_col_deliverytypes_maxsum; ?></td>
					<!--
					<td class="text-left"><?php echo $text_col_deliverytypes_status; ?></td>
					-->
				  </tr>
				</thead>
				<tbody class="tbody_class"> 
				<?php foreach($russianpost2_delivery_types as $delivery_type) { ?>
				<tr>
					<td>
						<input type="hidden" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][status]" value="1">
					
					
						<input type="hidden" class="form-control" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][type_key]"  value="<?php if( !empty( $delivery_type['type_key'] ) ) echo $delivery_type['type_key']; ?>"><?php if( !empty( $delivery_type['type_key'] ) ) echo $delivery_type['type_key']; ?>
					</td>
					<td>
						<?php echo $delivery_type['type_name']; ?><input type="hidden" class="form-control" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][type_name]"  value="<?php if( !empty( $delivery_type['type_name'] ) ) echo $delivery_type['type_name']; ?>">
					</td>
					<td>
						<input type="text" class="form-control" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][type_name_z]"  value="<?php if( !empty( $delivery_type['type_name_z'] ) ) echo $delivery_type['type_name_z']; ?>">
					</td>
					<td>
						<?php if( empty($delivery_type['doclink']) ) { ?>
							<?php echo $delivery_type['content']; ?>
						<?php } else { ?> 
							<a href="<?php echo $delivery_type['doclink']; ?>" target=_blank><?php echo $delivery_type['content']; ?></a>
						<?php } ?>
					</td>
					<td>
					
						<?php echo $delivery_type['text_maxweight_bydefault']; ?><br>
						
						<select name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][maxweight_mode]" class="form-control"
							onchange="change_custom('<?php echo $delivery_type['type_key']; ?>', 'maxweight', this.value);" style="width: 120px;">
							<option value="auto"
							<?php if( $delivery_type['maxweight_mode']=='auto' ) 
								echo 'selected'; ?>
							><?php echo $text_tarif_auto; ?></option>
							<option value="custom"
							<?php if( $delivery_type['maxweight_mode']=='custom' ) 
								echo 'selected'; ?>
							><?php echo $text_tarif_custom; ?></option>
						</select>
						
						<input type="hidden" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][data_maxweight]" class="form-control"
						value="<?php echo $delivery_type['data_maxweight']; ?>"
						>
						
						<input type="textbox" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][maxweight]" class="form-control" id="delivery_types_<?php echo $delivery_type['type_key']; ?>_maxweight" 
						value="<?php echo $delivery_type['maxweight']; ?>"
						
							<?php if( $delivery_type['maxweight_mode']=='auto' ) { ?> disabled <?php } ?>
						>
					
					</td>
					
					
					<td>
					
						<?php echo $delivery_type['text_maxlength_bydefault']; ?><br>
						
						<select name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][maxlength_mode]" class="form-control"
							onchange="change_custom('<?php echo $delivery_type['type_key']; ?>', 'maxlength', this.value);">
							<option value="auto"
							<?php if( $delivery_type['maxlength_mode']=='auto' ) 
								echo 'selected'; ?>
							><?php echo $text_tarif_auto; ?></option>
							<option value="custom"
							<?php if( $delivery_type['maxlength_mode']=='custom' ) 
								echo 'selected'; ?>
							><?php echo $text_tarif_custom; ?></option>
						</select>
						
						<table >
						<tr>
							<td>
								<input type="text" class="form-control"  name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][maxlength]" 
								value="<?php echo $delivery_type['maxlength']; ?>"
								 id="delivery_types_<?php echo $delivery_type['type_key']; ?>_maxlength" 
									<?php if( $delivery_type['maxlength_mode']=='auto' ) { ?> disabled <?php } ?>
								>
							</td>
							<td>x</td>
							<td>
								<input type="text" class="form-control"  name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][maxwidth]" 
								 id="delivery_types_<?php echo $delivery_type['type_key']; ?>_maxwidth" 
								value="<?php echo $delivery_type['maxwidth']; ?>"
								
									<?php if( $delivery_type['maxlength_mode']=='auto' ) { ?> disabled <?php } ?>
								>
							</td>
							<td>x</td>
							<td>
								<input type="text" class="form-control"  name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][maxheight]" 
								 id="delivery_types_<?php echo $delivery_type['type_key']; ?>_maxheight" 
								value="<?php echo $delivery_type['maxheight']; ?>"
								
									<?php if( $delivery_type['maxlength_mode']=='auto' ) { ?> disabled <?php } ?>
								>
							</td>
						</tr>	
						</table>	
						
						<input type="hidden" style="width: 30px;" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][data_maxlength]" class="form-control"
						value="<?php echo $delivery_type['data_maxlength']; ?>"
						>
						<input type="hidden" style="width: 30px;" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][data_maxwidth]" class="form-control"
						value="<?php echo $delivery_type['data_maxwidth']; ?>"
						>
						<input type="hidden" style="width: 30px;" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][data_maxheight]" class="form-control"
						value="<?php echo $delivery_type['data_maxheight']; ?>"
						>
						
					</td>
					<td>
						<?php echo $delivery_type['text_maxsum_bydefault']; ?><br>
						
						<select name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][maxsum_mode]" class="form-control"
							onchange="change_custom('<?php echo $delivery_type['type_key']; ?>', 'maxsum', this.value);">
							<option value="auto"
							<?php if( $delivery_type['maxsum_mode']=='auto' ) 
								echo 'selected'; ?>
							><?php echo $text_tarif_auto; ?></option>
							<option value="custom"
							<?php if( $delivery_type['maxsum_mode']=='custom' ) 
								echo 'selected'; ?>
							><?php echo $text_tarif_custom; ?></option>
						</select>
						
						<input type="hidden" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][data_maxsum]" class="form-control"
						value="<?php echo $delivery_type['data_maxsum']; ?>"
						>
						
						<input type="textbox" name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][maxsum]" class="form-control"
						 id="delivery_types_<?php echo $delivery_type['type_key']; ?>_maxsum" 
						value="<?php echo $delivery_type['maxsum']; ?>"
						
							<?php if( $delivery_type['maxsum_mode']=='auto' ) { ?> disabled <?php } ?>
						>
					
					</td>
					<!--
					<td>
						<select class="form-control"  name="russianpost2_delivery_types[<?php echo $delivery_type['type_key']; ?>][status]"  class="form-control" style="width: 130px;">
						  <?php if ($delivery_type['status']) { ?>
						  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						  <?php } ?>
						</select>
					</td>
					-->
				</tr>
				<?php } ?>
				</tbody>
			</table>
		
			<p><?php echo $text_delivery_types_notice; ?></p>
			
		</div>
		
		<!-- ------------------------- -->
		
		<!-- ------------------------- -->
		<?php /* start 112 */ ?>
		<div id="tab-packs" class="tab-pane">
			<p><?php echo $text_russianpost2_packs; ?></p><hr>
			
			<h3><?php echo $text_custom_packs_header; ?></h3>	
			<table id="custom_packs" class="table table-striped table-bordered table-hover">
				<thead>
				  <tr>
					<td class="text-left"><?php echo $col_packs_name; ?></td>
					<td class="text-left"><?php echo $col_packs_sizes; ?></td>
					<td class="text-left"><?php echo $col_packs_price; ?></td>
					<td class="text-left"><?php echo $col_packs_weight; ?></td>
					<td class="text-left"><?php echo $col_packs_status; ?></td>
					<td class="text-left"></td>
				  </tr>
				</thead>
				<tbody class="tbody_class"> 
				<?php $custom_pack_row = 0; ?>
				<?php foreach($custom_packs as $custom_pack) { ?>
				<tr id="custom_pack-row<?php echo $custom_pack_row; ?>">
					<td>
						<input type="text" class="form-control" name="russianpost2_custom_packs[<?php
						echo $custom_pack_row; ?>][name]" value="<?php 
							if( isset($custom_pack['name']) ) echo $custom_pack['name'];
						?>" >
					</td>
					
					<td>
						<table>
						<tr> 
						<td><input type="text" class="form-control" name="russianpost2_custom_packs[<?php
						echo $custom_pack_row; ?>][length]" value="<?php 
							if( isset($custom_pack['length']) ) echo $custom_pack['length'];
						?>" style="width: 50px;"></td><td>x</td><td>
						<input type="text" class="form-control" name="russianpost2_custom_packs[<?php
						echo $custom_pack_row; ?>][width]" value="<?php 
							if( isset($custom_pack['width']) ) echo $custom_pack['width'];
						?>" style="width: 50px;"></td><td>x</td><td>
						<input type="text" class="form-control" name="russianpost2_custom_packs[<?php
						echo $custom_pack_row; ?>][height]" value="<?php 
							if( isset($custom_pack['height']) ) echo $custom_pack['height'];
						?>" style="width: 50px;"> </td></tr></table>
					</td>
					<td>
						<input type="text" class="form-control" name="russianpost2_custom_packs[<?php
						echo $custom_pack_row; ?>][price]" value="<?php 
							if( isset($custom_pack['price']) ) echo $custom_pack['price'];
						?>" >
					</td>
					<td>
						<input type="text" class="form-control" name="russianpost2_custom_packs[<?php
						echo $custom_pack_row; ?>][dopweight]" value="<?php 
							if( isset($custom_pack['dopweight']) ) echo $custom_pack['dopweight'];
						?>" >
					</td>
					<td>
						<select class="form-control"  name="russianpost2_custom_packs[<?php echo 
						$custom_pack_row; ?>][status]">
						  <?php if ( !empty( $custom_pack['status'] ) ) { ?>
						  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						  <?php } ?>
						</select>
					</td>
					<td><button type="button" onclick="$('#custom_pack-row<?php echo $custom_pack_row; 
					?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" 
					class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
				</tr>
                <?php $custom_pack_row++; ?>
				<?php } ?>
				<tfoot>
				<tr>
					<td colspan=6 class="text-right">
						<button type="button" onclick="addCustomPack();" data-toggle="tooltip" 
						title="<?php echo $button_custom_pack_add; ?>" class="btn btn-primary"
						><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?php echo $button_custom_pack_add; 
						?></button>
					</td>
				</tr>
				</tfoot>
				</tbody>
			</table>
		
		
			<h3><?php echo $text_russianpost_packs_header; ?></h3>			
			
			
			<table class="table table-striped table-bordered table-hover">
				<thead>
				  <tr>
					<td class="text-left"><?php echo $col_packs_name; ?></td>
					<td class="text-left"><?php echo $col_packs_sizes; ?></td>
					<td class="text-left"><?php echo $col_packs_price; ?></td>
					<td class="text-left"><?php echo $col_packs_weight; ?></td>
					<td class="text-left"><?php echo $col_packs_status; ?></td>
				  </tr>
				</thead>
				<tbody class="tbody_class"> 
				<?php foreach($packs as $pack) { ?>
				<tr>
					<td>
						<?php echo $pack['name']; ?>
						
						<input type="hidden" name="russianpost2_packs[<?php
						echo $pack['pack_key']; ?>][length]" 
						value="<?php echo $pack['length']; ?>">
						
						<input type="hidden" name="russianpost2_packs[<?php
						echo $pack['pack_key']; ?>][width]" 
						value="<?php echo $pack['width']; ?>">
						
						<input type="hidden" name="russianpost2_packs[<?php
						echo $pack['pack_key']; ?>][height]" 
						value="<?php echo $pack['height']; ?>">
						
						<input type="hidden" name="russianpost2_packs[<?php
						echo $pack['pack_key']; ?>][tariff_pack_id]" 
						value="<?php echo $pack['tariff_pack_id']; ?>">
						
					</td>
					
					<td>
						<table>
						<tr> 
						<td><?php echo $pack['length']; ?></td><td>x</td>
						<td><?php echo $pack['width']; ?></td><td>x</td>
						<td><?php echo $pack['height']; ?></td></tr></table>
					</td>
					<td>
						<input type="text" class="form-control" name="russianpost2_packs[<?php
						echo $pack['pack_key']; ?>][price]" value="<?php 
							if( isset($pack['price']) ) echo $pack['price'];
						?>" >
					</td>
					<td>
						<input type="text" class="form-control" name="russianpost2_packs[<?php
						echo $pack['pack_key']; ?>][dopweight]" value="<?php 
							if( isset($pack['dopweight']) ) echo $pack['dopweight'];
						?>" >
					</td>
					<td>
						<select class="form-control"  name="russianpost2_packs[<?php
						echo $pack['pack_key']; ?>][status]">
						  <?php if ( !empty( $pack['status'] ) ) { ?>
						  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						  <?php } ?>
						</select>
					</td>
				</tr>
				<?php } ?>
				</tbody>
			</table>
		
		</div>
		
		<?php /* start 0503 */ ?>
		<div id="tab-customsrok" class="tab-pane">
			<h3><?php echo $text_customsrok_header; ?></h3>
			<p><?php echo $text_customsrok_notice; ?></p>
			
			<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<td class="text-left"><b><?php echo $col_russianpost_region_name; ?></b></td>
					<td class="text-left"><b><?php echo $col_avia_srok_capital; ?></b></td>
					<td class="text-left"><b><?php echo $col_avia_srok_region; ?></b></td>
					<td class="text-left"><b><?php echo $col_surface_srok_capital; ?></b></td>
					<td class="text-left"><b><?php echo $col_surface_srok_region; ?></b></td>
				</tr>
			</thead>
			<tbody>
				<?php foreach($current_regions as $i=>$region ) { ?>
				<tr>
					<td class="text-left" style="width: 200px;"
					><?php echo $region['name']; ?></td>
					<td class="text-left" >
						<?php echo $text_from; ?>
						<input type="text" class="form-control"  
						name="russianpost2_customsrok[<?php echo $region['ems_code']; ?>][avia_capital_from]"  
						value="<?php echo $region['avia_capital_from']; ?>"
						>
						
						<?php echo $text_to; ?>
						<input type="text" class="form-control" 
						name="russianpost2_customsrok[<?php echo $region['ems_code']; ?>][avia_capital_to]"  
						value="<?php echo $region['avia_capital_to']; ?>"
						></td>
					</td>
					<td class="text-left" >
						<?php echo $text_from; ?>
						<input type="text" class="form-control"  
						name="russianpost2_customsrok[<?php echo $region['ems_code']; ?>][avia_region_from]"  
						value="<?php echo $region['avia_region_from']; ?>">
						
						<?php echo $text_to; ?>
						<input type="text" class="form-control" 
						name="russianpost2_customsrok[<?php echo $region['ems_code']; ?>][avia_region_to]"  
						value="<?php echo $region['avia_region_to']; ?>">
						
						</td>
					</td>
					<td class="text-left" >
						<?php echo $text_from; ?>
						<input type="text" class="form-control" 
						name="russianpost2_customsrok[<?php echo $region['ems_code']; ?>][surface_capital_from]"  
						value="<?php echo $region['surface_capital_from']; ?>">
						
						<?php echo $text_to; ?>
						<input type="text" class="form-control" 
						name="russianpost2_customsrok[<?php echo $region['ems_code']; ?>][surface_capital_to]"  
						value="<?php echo $region['surface_capital_to']; ?>"></td>
					</td>
					<td class="text-left" >
						<?php echo $text_from; ?>
						<input type="text" class="form-control" 
						name="russianpost2_customsrok[<?php echo $region['ems_code']; ?>][surface_region_from]"  
						value="<?php echo $region['surface_region_from']; ?>">
						
						<?php echo $text_to; ?>
						<input type="text" class="form-control" 
						name="russianpost2_customsrok[<?php echo $region['ems_code']; ?>][surface_region_to]"  
						value="<?php echo $region['surface_region_to']; ?>"></td>
					</td>
				</tr>
				<?php } ?>
			</tbody>
			</table>
			
			
		</div>
		<?php /* end 0503 */ ?>	
		
		<div id="tab-filters" class="tab-pane">
		
				  <p><?php echo $text_russianpost2_filters_dops; ?></p>
							<hr>
		
		
			<h3><?php echo $text_filters_product_header; ?></h3>
			<table id="product_filters" class="table table-striped table-bordered table-hover">
				<thead>
				  <tr>
					<td class="text-left"><?php echo $col_filter_filtername; ?></td>
					<td class="text-left"><?php echo $col_filter_category; ?></td>
					<td class="text-left"><?php echo $col_filter_manufacturer; ?></td>
					<td class="text-left"><?php echo $col_filter_name; ?></td>
					<td class="text-left"><?php echo $col_filter_sizes; ?></td>
					<td class="text-left"><?php echo $col_filter_sort_order; ?></td>
					<td class="text-left"></td>
				  </tr>
				</thead>
				<tbody class="tbody_class"> 
				<?php $filter_row = 0; ?>
				<?php foreach($russianpost2_product_filters as $filter) { ?>
				<tr id="filter-row<?php echo $filter_row; ?>">
					<td>
					<input type="hidden" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][filter_id]"  value="<?php if( !empty( $filter['filter_id'] ) ) echo $filter['filter_id']; ?>">
					
					
					<input type="hidden" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][type]"  value="product">
					
					
					<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][filtername]"  value="<?php echo $filter['filtername']; ?>"></td>
					
					<td> 
					  <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category<?php echo $filter_row; ?>" class="form-control filter_category" />
					  <div id="filter-category<?php echo $filter_row; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
						<?php if( !empty($filter['filter_category']) ) { foreach ($filter['filter_category'] as $filter_category) { ?>
						<div id="filter-category<?php echo $filter_row; ?>-<?php echo $filter_category['category_id']; ?>"><i class="fa fa-minus-circle"  onclick="$(this).parent().remove();"></i> <?php echo $filter_category['name']; ?>
						  <input type="hidden" name="russianpost2_product_filters[<?php echo $filter_row; ?>][filter_category][]" value="<?php echo $filter_category['category_id']; ?>" />
						</div>
						<?php } } ?>
					  </div>
					</td>
					<td>
					  <input type="text" name="manufacturer" value="" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer<?php echo $filter_row; ?>" class="form-control filter_manufacturer" />
					  <div id="filter-manufacturer<?php echo $filter_row; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
						<?php if( !empty($filter['filter_manufacturer']) ) { foreach ($filter['filter_manufacturer'] as $filter_manufacturer) { ?>
						<div id="filter-manufacturer<?php echo $filter_row; ?>-<?php echo $filter_manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle" onclick="$(this).parent().remove();"></i> <?php echo $filter_manufacturer['name']; ?>
						  <input type="hidden" name="russianpost2_product_filters[<?php echo $filter_row; ?>][filter_manufacturer][]" value="<?php echo $filter_manufacturer['manufacturer_id']; ?>" />
						</div>
						<?php } } ?>
					  </div>
					</td>
					<td>
						<b><?php echo $text_productname_header; ?></b><br>
						
						<select name="russianpost2_product_filters[<?php echo $filter_row; ?>][filter_productname_searchtype]" class="form-control">
							<option value="sub" <?php if( !empty( $filter['filter_productname_searchtype'] ) && $filter['filter_productname_searchtype'] == 'sub' ) { ?> selected <?php } ?>><?php echo $text_search_sub; ?></option>
							<option value="sub_noright" <?php if( !empty( $filter['filter_productname_searchtype'] ) && $filter['filter_productname_searchtype'] == 'sub_noright' ) { ?> selected <?php } ?>><?php echo $text_search_sub_noright; ?></option>
							<option value="sub_noleft" <?php if( !empty( $filter['filter_productname_searchtype'] ) && $filter['filter_productname_searchtype'] == 'sub_noleft' ) { ?> selected <?php } ?>><?php echo $text_search_sub_noleft; ?></option>
							<option value="strict" <?php if( !empty( $filter['filter_productname_searchtype'] ) && $filter['filter_productname_searchtype'] == 'strict' ) { ?> selected <?php } ?>><?php echo $text_search_strict; ?></option>
						</select>
						<br>
						<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][filter_productname]" value="<?php if( !empty($filter['filter_productname']) ) echo $filter['filter_productname']; ?>">
						
						<!-- ------------------- -->
						<b><?php echo $text_productmodel_header; ?></b><br>
						<select name="russianpost2_product_filters[<?php echo $filter_row; ?>][filter_productmodel_searchtype]" class="form-control">
							<option value="sub" <?php if( !empty( $filter['filter_productmodel_searchtype'] ) && $filter['filter_productmodel_searchtype'] == 'sub' ) { ?> selected <?php } ?>><?php echo $text_search_sub; ?></option>
							<option value="sub_noright" <?php if( !empty( $filter['filter_productmodel_searchtype'] ) && $filter['filter_productmodel_searchtype'] == 'sub_noright' ) { ?> selected <?php } ?>><?php echo $text_search_sub_noright; ?></option>
							<option value="sub_noleft" <?php if( !empty( $filter['filter_productmodel_searchtype'] ) && $filter['filter_productmodel_searchtype'] == 'sub_noleft' ) { ?> selected <?php } ?>><?php echo $text_search_sub_noleft; ?></option>
							<option value="strict" <?php if( !empty( $filter['filter_productmodel_searchtype'] ) && $filter['filter_productmodel_searchtype'] == 'strict' ) { ?> selected <?php } ?>><?php echo $text_search_strict; ?></option>
						</select>
						<br>
						<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][filter_productmodel]" value="<?php  if( !empty($filter['filter_productmodel']) ) echo $filter['filter_productmodel']; ?>">
					</td>
					
					
					<td> 
						<b><?php echo $text_product_price; ?></b><br>
						<table>
						<tr>
						<td>
						<select name="russianpost2_product_filters[<?php echo $filter_row; ?>][price_type]" class="form-control">
							<option value="more" <?php if( !empty( $filter['price_type'] ) && $filter['price_type'] == 'more' ) { ?> selected <?php } ?>><?php echo $text_more; ?></option>
							
							<option value="less" <?php if( !empty( $filter['price_type'] ) && $filter['price_type'] == 'less' ) { ?> selected <?php } ?>><?php echo $text_less; ?></option>
							
						</select>
						</td>
						<td>&nbsp;</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][price]" value="<?php  if( !empty($filter['price']) ) echo $filter['price']; ?>">
						</td>
						</tr>
						</table>
						
						<br>
						<b><?php echo $text_product_weight; ?></b><br>
						
						<table>
						<tr>
						<td>
						<select name="russianpost2_product_filters[<?php echo $filter_row; ?>][weight_type]" class="form-control">
							<option value="more" <?php if( !empty( $filter['weight_type'] ) && $filter['weight_type'] == 'more' ) { ?> selected <?php } ?>><?php echo $text_more; ?></option>
							
							<option value="less" <?php if( !empty( $filter['weight_type'] ) && $filter['weight_type'] == 'less' ) { ?> selected <?php } ?>><?php echo $text_less; ?></option>
							
						</select>
						</td>
						<td>&nbsp;</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][weight]" value="<?php  if( !empty($filter['weight']) ) echo $filter['weight']; ?>">
						</td>
						</tr>
						</table>
						
						<br>
						<b><?php echo $text_product_sizes; ?></b><br>
						<table>
						<tr>
						<td>
						
						<select name="russianpost2_product_filters[<?php echo $filter_row; ?>][length_type]" class="form-control">
							<option value="more" <?php if( !empty( $filter['length_type'] ) && $filter['length_type'] == 'more' ) { ?> selected <?php } ?>><?php echo $text_more; ?></option>
							
							<option value="less" <?php if( !empty( $filter['length_type'] ) && $filter['length_type'] == 'less' ) { ?> selected <?php } ?>><?php echo $text_less; ?></option>
							
						</select>
						</td>
						<td>&nbsp;</td>
						<td>
						
						<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][length]" value="<?php  if( !empty($filter['length']) ) echo $filter['length']; ?>" style="width: 50px;">
						</td>
						<td>
						x
						</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][width]" value="<?php  if( !empty($filter['width']) ) echo $filter['width']; ?>" style="width: 50px;">
						</td>
						<td>
						x
						</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][height]" value="<?php  if( !empty($filter['height']) ) echo $filter['height']; ?>" style="width: 50px;">
						</td>
						</tr>
						</table>
						 
						<br>
						<b><?php echo $text_count_products; ?></b><br>
						
						<table class="noborder">
						<tr>
						<td>
						<select name="russianpost2_product_filters[<?php echo $filter_row; ?>][count_products_type]" class="form-control">
							<option value="more" <?php if( !empty( $filter['count_products_type'] ) && $filter['count_products_type'] == 'more' ) { ?> selected <?php } ?>><?php echo $text_more; ?></option>
							
							<option value="less" <?php if( !empty( $filter['count_products_type'] ) && $filter['count_products_type'] == 'less' ) { ?> selected <?php } ?>><?php echo $text_less; ?></option>
							
						</select>
						</td>
						<td>&nbsp;</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][count_products]" value="<?php  if( !empty($filter['count_products']) ) echo $filter['count_products']; ?>">
						</td>
						</tr>
						</table> 
					</td>
					
					
					<td>
					
						<b><?php echo $text_status; ?></b><br>
						<select class="form-control"  name="russianpost2_product_filters[<?php echo $filter_row; ?>][status]">
						  <?php if ($filter['status']) { ?>
						  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						  <?php } ?>
						</select><br>
			
						<b><?php echo $text_sort_order; ?></b><br>
					<input type="text" class="form-control" name="russianpost2_product_filters[<?php echo $filter_row; ?>][sort_order]" value="<?php  if( !empty($filter['sort_order']) ) echo $filter['sort_order']; ?>">
					
					
					</td>
					<td><button type="button" onclick="$('#filter-row<?php echo $filter_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
				</tr>
                <?php $filter_row++; ?>
				<?php } ?>
				<tfoot>
				<tr>
					<td colspan=7 class="text-right">
						<button type="button" onclick="addFilter();" data-toggle="tooltip" title="<?php echo $button_filter_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?php echo $button_filter_add; ?></button>
					</td>
				</tr>
				</tfoot>
				</tbody>
			</table>
			
			<p><?php echo $text_product_filters_notice; ?></p>
			
			
			
			<h3><?php echo $text_filters_order_header; ?></h3>
			<table id="order_filters" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td class="text-left"><?php echo $col_filter_filtername; ?></td>
						<td class="text-left"><?php echo $col_filter_filterproduct; ?></td>
						<td class="text-left"><?php echo $col_filter_region; ?></td>
						<td class="text-left"><?php echo $col_filter_sizes; ?></td>
						<td class="text-left"><?php echo $col_filter_sort_order; ?></td>
						<td class="text-left"></td>
					</tr>
				</thead>
				<tbody class="tbody_class">
				
				<?php /* start 0711 */ ?>
				<?php $cg_row = array(); ?>
				<?php /* end 0711 */ ?>
				<?php $filter2_row = 0; ?>
				<?php if( !empty($russianpost2_order_filters) ) { foreach($russianpost2_order_filters as $filter) { ?>
				
				<?php /* start 0711 */ ?>
				<?php $cg_row[$filter2_row] = 1; ?>
				<?php /* end 0711 */ ?>
				<tr id="filter2-row<?php echo $filter2_row; ?>">
					<td>
						<input type="hidden" class="form-control" name="russianpost2_order_filters[<?php echo $filter2_row; ?>][filter_id]"  value="<?php if( !empty( $filter['filter_id'] ) ) echo $filter['filter_id']; ?>">
						
						
						<input type="hidden" class="form-control" name="russianpost2_order_filters[<?php echo $filter2_row; ?>][type]"  value="order">
						
						
						<input type="text" class="form-control" name="russianpost2_order_filters[<?php echo $filter2_row; ?>][filtername]"  value="<?php echo $filter['filtername']; ?>"></td>
					</td>
					<td>
						<select name="russianpost2_order_filters[<?php echo $filter2_row; ?>][productfilter]" class="form-control" >
						<option value=""><?php echo $text_select_filter; ?></option>
						<?php if( !empty($russianpost2_product_filters) ) { 
						foreach($russianpost2_product_filters as $ft) { ?>
						
						<option value="<?php echo $ft['filter_id']; ?>"
						<?php if( !empty( $filter['productfilter'] ) && $filter['productfilter'] == $ft['filter_id'] ) { ?> selected <?php } ?>
						><?php echo $ft['filtername']; ?></option>
						
						<?php } } ?>
						</select>
						<br>
						<select name="russianpost2_order_filters[<?php echo $filter2_row; ?>][productfilter_type]" style="width: 170px;" class="form-control" >
						<option value="all" <?php if( !empty($filter['productfilter_type']) && $filter['productfilter_type'] == 'all' ) { ?> selected <?php } ?>><?php echo $text_all_product; ?></option>
						<option value="one" <?php if( !empty($filter['productfilter_type']) && $filter['productfilter_type'] == 'one' ) { ?> selected <?php } ?>><?php echo $text_one_product; ?></option>
						<option value="except" <?php if( !empty($filter['productfilter_type']) && $filter['productfilter_type'] == 'except' ) { ?> selected <?php } ?>><?php echo $text_except_product; ?></option>
						</select>
						<?php /* start 0711 */ ?>
						 <br>
						<div><b><?php echo $text_customer_group; ?></b></div>
						<table border=0 width=100% id="cgtab<?php echo $filter2_row; 
						?>">
					<tbody>
<?php if( !empty($filter['customer_groups']) ) { 
	  foreach($filter['customer_groups'] as $x2=>$customer_group_id) { ?>	
				<?php $cg_row[$filter2_row]++; ?>

					<tr id="cg<?php echo $filter2_row; ?>-<?php echo $cg_row[$filter2_row]; 
						?>">
						<td style="padding: 5px;">
		
					<select name="russianpost2_order_filters[<?php echo $filter2_row; 
					?>][customer_groups][<?php echo $cg_row[$filter2_row]; 
					?>]" class="form-control"
					>
					
<?php foreach($customer_groups as $customer_group) { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>"
					<?php if( $customer_group['customer_group_id'] == $customer_group_id ) { 
					?> selected <?php } ?>
					><?php echo $customer_group['name']; ?></option>
<?php } ?>
					</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" onclick="$('#cg<?php echo $filter2_row; ?>-<?php echo $cg_row[$filter2_row]; 
						?>, .tooltip').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } ?>			
			</tbody>
			<tfoot>
			<tr>
				<td colspan=3 style="padding: 5px;"><button type="button" 
				onclick="addCustomerGroupBlock('<?php echo $filter2_row; ?>');" data-toggle="tooltip" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
			</tr>
			</tfoot>
		</table> 
						<?php /* end 0711 */ ?>
					</td>
					<td>
						<?php /* start metka-707 */ ?>
						<?php echo $text_filter_regions_type; ?>
						<select name="russianpost2_order_filters[<?php echo $filter2_row; ?>][filter_regions_type]"
						 class="form-control">
							<option value="include_only"
							<?php if( $filter['filter_regions_type'] == 'include_only' ) { ?> selected <?php } ?>
							><?php echo $text_filter_regions_type_include_only; ?></option>
							<option value="exclude"
							<?php if( $filter['filter_regions_type'] == 'exclude' ) { ?> selected <?php } ?>
							><?php echo $text_filter_regions_type_exclude; ?></option>
						
						</select><br><br>
						<?php /* end metka-707 */ ?>
					<!-- --------------------------- -->
					<table border=0 width=100% id="filter_regions<?php echo $filter2_row; ?>"  class="table table-striped table-bordered table-hover">
					<thead>
					<tr>
						<td class="text-left"><?php echo $col_text_region_geozone; ?></td>
						<td class="text-left"><?php echo $col_text_city; ?></td>
						<td class="text-left"></td>
					</tr>
					</thead>
					<tbody class="tbody2_class">
<?php if( !empty($filter['filter_regions']) ) { foreach($filter['filter_regions'] as $x=>$filter_region) { ?>	

					<tr id="filter_regions<?php echo $filter2_row; ?>-<?php echo $x; ?>">
						<td  class="text-left">
					
					<select name="russianpost2_order_filters[<?php echo $filter2_row; ?>][filter_regions][<?php echo $x; ?>][ems_code]" class="form-control" style="width: 200px;">
					
					
					<option value=""><?php echo $text_select_region_geozone; ?></option>
					
					<?php foreach ($geo_zones as $geo_zone) { ?>
							<?php if ( 'geozone_'.$geo_zone['geo_zone_id'] == $filter_region['ems_code'] ) { ?>
							<option value="geozone_<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $text_geozone.' '.$geo_zone['name']; ?></option>
							<?php } else { ?>
							<option value="geozone_<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $text_geozone.' '.$geo_zone['name']; ?></option>
							<?php } ?>
							<?php } ?>
					

<?php foreach($current_regions as $region ) { ?>
					<option value="<?php echo $region['ems_code']; ?>"
					<?php if( $region['ems_code'] == $filter_region['ems_code'] ) { ?> selected <?php } ?>
					><?php echo $region['name']; ?></option>
<?php } ?>
					</select>
					</td>
					<td  class="text-left">
					<textarea name="russianpost2_order_filters[<?php echo $filter2_row; ?>][filter_regions][<?php echo $x; ?>][cities]" cols="40" rows=3
					><?php echo $filter_region['cities']; ?></textarea>
					</td>
					
					
					<td  class="text-left">
						<button type="button" onclick="$('#filter_regions<?php echo $filter2_row; ?>-<?php echo $x; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_del_region; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } ?>			
			</tbody>
			<tfoot>
			<tr>
				<td colspan=3 style="padding: 5px;"><button type="button" onclick="addRegionBlock('<?php echo $filter2_row; ?>');" data-toggle="tooltip" title="<?php echo $button_add_region; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
			</tr>
			</tfoot>
		</table>
					<!-- --------------------------- -->
					</td>
					<td> 
						<b><?php echo $text_order_price; ?></b><br>
						<table>
						<tr>
						<td>
						<select name="russianpost2_order_filters[<?php echo $filter2_row; ?>][price_type]" class="form-control">
							<option value="more" <?php if( !empty( $filter['price_type'] ) && $filter['price_type'] == 'more' ) { ?> selected <?php } ?>><?php echo $text_more; ?></option>
							
							<option value="less" <?php if( !empty( $filter['price_type'] ) && $filter['price_type'] == 'less' ) { ?> selected <?php } ?>><?php echo $text_less; ?></option>
							
						</select>
						</td>
						<td>&nbsp;</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_order_filters[<?php echo $filter2_row; ?>][price]" value="<?php  if( !empty($filter['price']) ) echo $filter['price']; ?>">
						</td>
						</tr>
						</table>
						
						<br>
						<b><?php echo $text_order_weight; ?></b><br>
						
						<table>
						<tr>
						<td>
						<select name="russianpost2_order_filters[<?php echo $filter2_row; ?>][weight_type]" class="form-control">
							<option value="more" <?php if( !empty( $filter['weight_type'] ) && $filter['weight_type'] == 'more' ) { ?> selected <?php } ?>><?php echo $text_more; ?></option>
							
							<option value="less" <?php if( !empty( $filter['weight_type'] ) && $filter['weight_type'] == 'less' ) { ?> selected <?php } ?>><?php echo $text_less; ?></option>
							
						</select>
						</td>
						<td>&nbsp;</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_order_filters[<?php echo $filter2_row; ?>][weight]" value="<?php  if( !empty($filter['weight']) ) echo $filter['weight']; ?>">
						</td>
						</tr>
						</table>
						
						<br>
						<b><?php echo $text_order_sizes; ?></b><br>
						<table>
						<tr>
						<td>
						
						<select name="russianpost2_order_filters[<?php echo $filter2_row; ?>][length_type]" class="form-control">
							<option value="more" <?php if( !empty( $filter['length_type'] ) && $filter['length_type'] == 'more' ) { ?> selected <?php } ?>><?php echo $text_more; ?></option>
							
							<option value="less" <?php if( !empty( $filter['length_type'] ) && $filter['length_type'] == 'less' ) { ?> selected <?php } ?>><?php echo $text_less; ?></option>
							
						</select>
						</td>
						<td>&nbsp;</td>
						<td>
						
						<input type="text" class="form-control" name="russianpost2_order_filters[<?php echo $filter2_row; ?>][length]" value="<?php  if( !empty($filter['length']) ) echo $filter['length']; ?>" style="width: 50px;">
						</td>
						<td>
						x
						</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_order_filters[<?php echo $filter2_row; ?>][width]" value="<?php  if( !empty($filter['width']) ) echo $filter['width']; ?>" style="width: 50px;">
						</td>
						<td>
						x
						</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_order_filters[<?php echo $filter2_row; ?>][height]" value="<?php  if( !empty($filter['height']) ) echo $filter['height']; ?>" style="width: 50px;">
						</td>
						</tr>
						</table>
					</td>
					
					
					<td>
					
						<b><?php echo $text_status; ?></b><br>
						<select class="form-control"  name="russianpost2_order_filters[<?php echo $filter2_row; ?>][status]">
						  <?php if ($filter['status']) { ?>
						  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						  <?php } ?>
						</select><br>
			
						<b><?php echo $text_sort_order; ?></b><br>
					<input type="text" class="form-control" name="russianpost2_order_filters[<?php echo $filter2_row; ?>][sort_order]" value="<?php  if( !empty($filter['sort_order']) ) echo $filter['sort_order']; ?>">
					
					
					</td>
					<td><button type="button" onclick="$('#filter2-row<?php echo $filter2_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
					</tr>
				<?php $filter2_row++; ?>
				<?php } } ?>
				</tbody>
				<tfoot>
				<tr>
					<td colspan=6 class="text-right">
						<button type="button" onclick="addFilter2();" data-toggle="tooltip" title="<?php echo $button_filter_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?php echo $button_filter_add; ?></button>
					</td>
				</tr>
				</tfoot>
			</table>
			<p><?php echo $text_order_filters_notice; ?></p>
			
			
		</div>
		<div id="tab-adds" class="tab-pane">
		
				  <p><?php echo $text_russianpost2_filters_dops; ?></p>
							<hr>
		
			<h3><?php echo $text_adds_product_header; ?></h3>
				
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_product_adds_type; ?>
				</label>
				<div class="col-sm-10">
					<table>
					<tr>
						<td>
							<input type="radio" name="russianpost2_product_adds_type" value="one"
							<?php if($russianpost2_product_adds_type == 'one') { ?> checked <?php } ?>
							id="russianpost2_product_adds_type_one"
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_product_adds_type_one" style="font-weight: normal;" 
							><?php echo $text_adds_type_one; ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="russianpost2_product_adds_type" value="all"
							<?php if($russianpost2_product_adds_type == 'all') { ?> checked <?php } ?>
							id="russianpost2_product_adds_type_all"
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_product_adds_type_all" style="font-weight: normal;" 
							><?php echo $text_adds_type_all; ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="russianpost2_product_adds_type" value="byfilter"
							<?php if($russianpost2_product_adds_type == 'byfilter') { ?> checked <?php } ?>
							id="russianpost2_product_adds_type_byfilter" 
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_product_adds_type_byfilter" style="font-weight: normal;" 
							><?php echo $text_adds_type_byfilter; ?></label>
						</td>
					</tr>
					</table>
						
				</div>
			</div>
			<div>
				<?php echo $text_product_adds_notice; ?>
			</div><br>
			<table id="product_adds" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td class="text-left"><?php echo $col_adds_filter; ?></td>
						<td class="text-left"><?php echo $col_adds_weight; ?></td>
						<td class="text-left"><?php echo $col_adds_sizes; ?></td>
						<td class="text-left"><?php echo $col_adds_caution; ?></td>
						<td class="text-left"><?php echo $col_adds_sort_order; ?></td>
						<td class="text-left"><?php echo $col_adds_status; ?></td>
						<td class="text-left"></td>
					</tr>
				</thead>
				<tbody class="tbody_class">
				<?php $adds_row = 0; ?>
				<?php if( !empty($russianpost2_product_adds) ) { foreach($russianpost2_product_adds as $adds) { ?>
				<tr id="adds-row<?php echo $adds_row; ?>">
					<td>
						<input type="hidden" class="form-control" name="russianpost2_product_adds[<?php echo $adds_row; ?>][adds_id]" value="<?php  if( !empty($adds['adds_id']) ) echo $adds['adds_id']; ?>">
						<input type="hidden" class="form-control" name="russianpost2_product_adds[<?php echo $adds_row; ?>][type]" value="product">
					
					<table border=0 width=100% id="product_adds<?php echo $adds_row; ?>"  class="table table-striped table-bordered table-hover">
					<tbody class="tbody2_class">
<?php if( !empty($adds['filters']) ) { foreach($adds['filters'] as $x=>$filter_id) { ?>	

					<tr id="product_adds<?php echo $adds_row; ?>-<?php echo $x; ?>">
						<td style="padding: 5px;">
		
					<select name="russianpost2_product_adds[<?php echo $adds_row; ?>][filters][]" class="form-control"><option value=""><?php echo $text_select_filter; ?></option>

<?php foreach($russianpost2_product_filters as $ft) { ?>
					<option value="<?php echo $ft['filter_id']; ?>"
					<?php if( $ft['filter_id'] == $filter_id ) { ?> selected <?php } ?>
					><?php echo $ft['filtername']; ?></option>
<?php } ?>
					</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" onclick="$('#product_adds<?php echo $adds_row; ?>-<?php echo $x; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } ?>					
			</tbody>
			<tfoot>
			<tr>
				<td colspan=2 style="padding: 5px;"><button type="button" onclick="addFilterBlock('<?php echo $adds_row; ?>');" data-toggle="tooltip" title="<?php echo $button_add_filter; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
			</tr>
			</tfoot>
		</table>
				
					</td>
					<td>
					
						<input type="text" class="form-control" name="russianpost2_product_adds[<?php echo $adds_row; ?>][weight]" value="<?php  if( !empty($adds['weight']) ) echo $adds['weight']; ?>">
						
					</td>
					<td><table>
						<tr>
						<td>
						
						<input type="text" class="form-control" name="russianpost2_product_adds[<?php echo $adds_row; ?>][length]" value="<?php  if( !empty($adds['length']) ) echo $adds['length']; ?>" style="width: 50px;">
						</td>
						<td>
						x
						</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_product_adds[<?php echo $adds_row; ?>][width]" value="<?php  if( !empty($adds['width']) ) echo $adds['width']; ?>" style="width: 50px;">
						</td>
						<td>
						x
						</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_product_adds[<?php echo $adds_row; ?>][height]" value="<?php  if( !empty($adds['height']) ) echo $adds['height']; ?>" style="width: 50px;">
						</td>
						</tr>
						</table>
					</td>
					<td>
						<select class="form-control"  name="russianpost2_product_adds[<?php echo $adds_row; ?>][caution]">
						  <?php if ($adds['caution']) { ?>
						  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						  <?php } ?>
						</select>
					</td>
					<td>
						<input type="text" class="form-control" name="russianpost2_product_adds[<?php echo $adds_row; ?>][sort_order]" value="<?php  if( !empty($adds['sort_order']) ) echo $adds['sort_order']; ?>">
					
					
					</td>
					<td>
						<select class="form-control"  name="russianpost2_product_adds[<?php echo $adds_row; ?>][status]">
						  <?php if ($adds['status']) { ?>
						  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						  <?php } ?>
						</select>
					</td>
					<td><button type="button" onclick="$('#adds-row<?php echo $adds_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
				</tr>
				<?php $adds_row++; ?>
				<?php } } ?>
				</tbody>
				<tfoot>
				<tr>
					<td colspan=7 class="text-right">
					
						<?php /* start metka-407 */ ?>
						<button type="button" onclick="addAdds();" 
						data-toggle="tooltip" 
						title="<?php echo $button_add_adds; ?>" 
						class="btn btn-primary"><i class="fa fa-plus-circle"></i
						>&nbsp;&nbsp;<?php echo $button_add_adds; ?></button>
						<?php /* end metka-407 */ ?></td>
				</tr>
				</tfoot>
			</table>
			
			<h3><?php echo $text_adds_order_header; ?></h3>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_order_adds_type; ?>
				</label>
				<div class="col-sm-10">
					<table>
					<tr>
						<td>
							<input type="radio" name="russianpost2_order_adds_type" value="one"
							<?php if($russianpost2_order_adds_type == 'one') { ?> checked <?php } ?>
							id="russianpost2_order_adds_type_one"
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_order_adds_type_one" style="font-weight: normal;" 
							><?php echo $text_adds_type_one; ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="russianpost2_order_adds_type" value="all"
							<?php if($russianpost2_order_adds_type == 'all') { ?> checked <?php } ?>
							id="russianpost2_order_adds_type_all"
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_order_adds_type_all" style="font-weight: normal;" 
							><?php echo $text_adds_type_all; ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="russianpost2_order_adds_type" value="byfilter"
							<?php if($russianpost2_order_adds_type == 'byfilter') { ?> checked <?php } ?>
							id="russianpost2_order_adds_type_byfilter" 
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_order_adds_type_byfilter" style="font-weight: normal;" 
							><?php echo $text_adds_type_byfilter; ?></label>
						</td>
					</tr>
					</table>
						
				</div>
			</div>
			<table id="order_adds" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td class="text-left"><?php echo $col_adds_filter; ?></td>
						<td class="text-left"><?php echo $col_adds_service; ?></td>
						<td class="text-left"><?php echo $col_adds_cost; ?></td>
						<td class="text-left"><?php echo $col_adds_weight; ?></td>
						<td class="text-left"><?php echo $col_adds_sizes; ?></td>
						<td class="text-left"><?php echo $col_adds_srok; ?></td>
						<td class="text-left"><?php echo $col_adds_sort_order; ?></td>
						<td class="text-left"><?php echo $col_adds_status; ?></td>
						<td class="text-left"></td>
					</tr>
				</thead>
				<tbody class="tbody_class">
				<?php $adds2_row = 0; ?>
				<?php if( !empty($russianpost2_order_adds) ) { foreach($russianpost2_order_adds as $adds) { ?>
				<tr id="adds2-row<?php echo $adds2_row; ?>">
					<td>
						<input type="hidden" class="form-control" name="russianpost2_order_adds[<?php echo $adds2_row; ?>][adds_id]" value="<?php  if( !empty($adds['adds_id']) ) echo $adds['adds_id']; ?>">
						<input type="hidden" class="form-control" name="russianpost2_order_adds[<?php echo $adds2_row; ?>][type]" value="order">
					
					<table border=0 width=100% id="order_adds<?php echo $adds2_row; ?>"  class="table table-striped table-bordered table-hover">
					<tbody class="tbody2_class">
<?php if( !empty($adds['filters']) ) { foreach($adds['filters'] as $x=>$filter_id) { ?>	

					<tr id="order_adds<?php echo $adds2_row; ?>-<?php echo $x; ?>">
						<td style="padding: 5px;">
		
					<select name="russianpost2_order_adds[<?php echo $adds2_row; ?>][filters][]" class="form-control"><option value=""><?php echo $text_select_filter; ?></option>
<?php foreach($russianpost2_order_filters as $ft) { ?>
					<option value="<?php echo $ft['filter_id']; ?>"
					<?php if( $ft['filter_id'] == $filter_id ) { ?> selected <?php } ?>
					><?php echo $ft['filtername']; ?></option>
<?php } ?>
					</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" onclick="$('#order_adds<?php echo $adds2_row; ?>-<?php echo $x; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } ?>					
			</tbody>
			<tfoot>
			<tr>
				<td colspan=2 style="padding: 5px;"><button type="button" onclick="addFilterBlock2('<?php echo $adds2_row; ?>');" data-toggle="tooltip" title="<?php echo $button_add_filter; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
			</tr>
			</tfoot>
		</table>
				
					</td>
					
					<td>
					
					<!-- ------- -->
					
					<table border=0 width=100% id="order_service_adds<?php echo $adds2_row; ?>"  class="table table-striped table-bordered table-hover">
					<tbody class="tbody2_class">
<?php if( !empty($adds['services']) ) { foreach($adds['services'] as $x=>$service_key) { ?>	

					<tr id="order_service_adds<?php echo $adds2_row; ?>-<?php echo $x; ?>">
						<td style="padding: 5px;">
		
					<select name="russianpost2_order_adds[<?php echo $adds2_row; ?>][services][]" class="form-control" style="width: 150px;"><option value=""><?php echo $text_select_filter; ?></option>
<?php foreach($services_list as $serv) { ?>
					<option value="<?php echo $serv['service_key']; ?>"
					<?php if( $serv['service_key'] == $service_key ) { ?> selected <?php } ?>
					><?php echo $serv['service_name']; ?></option>
<?php } ?>
					</select>
					</td>
					<td style="padding: 5px;">
						<button type="button" onclick="$('#order_service_adds<?php echo $adds2_row; ?>-<?php echo $x; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
					</td>
					</tr>
<?php } } ?>					
			</tbody>
			<tfoot>
			<tr>
				<td colspan=2 style="padding: 5px;"><button type="button" onclick="addServiceBlock2('<?php echo $adds2_row; ?>');" data-toggle="tooltip" title="<?php echo $button_add_service; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
			</tr>
			</tfoot>
		</table>
				
				<!-- --------- -->
					
					</td>
					
					<td>
					
						<input type="text" class="form-control" name="russianpost2_order_adds[<?php echo $adds2_row; ?>][cost]" value="<?php  if( !empty($adds['cost']) ) echo $adds['cost']; ?>"
						>
						<?php /* start 2009 */ ?>
						<select name="russianpost2_order_adds[<?php echo $adds2_row; ?>][costtype]"
						 class="form-control" >
							<option value="fix"
							<?php if( !empty($adds['costtype']) && $adds['costtype'] == 'fix' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_fix; ?></option>
							<?php /* start 2401 */ ?>
							<option value="fix2products"
							<?php if( !empty($adds['costtype']) && $adds['costtype'] == 'fix2products' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_fix2products; ?></option>
							<?php /* end 2401 */ ?>
							<option value="products_perc"
							<?php if(  !empty($adds['costtype']) && $adds['costtype'] == 'products_perc' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_products_perc; ?></option>
							<option value="delivery_perc"
							<?php if(  !empty($adds['costtype']) && $adds['costtype'] == 'delivery_perc' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_delivery_perc; ?></option>
						</select>
						<?php /* end 2009 */ ?>
					</td>
					<td>
					
						<input type="text" class="form-control" name="russianpost2_order_adds[<?php echo $adds2_row; ?>][weight]" value="<?php  if( !empty($adds['weight']) ) echo $adds['weight']; ?>">
						
					</td>
					<td><table>
						<tr>
						<td>
						
						<input type="text" class="form-control" name="russianpost2_order_adds[<?php echo $adds2_row; ?>][length]" value="<?php  if( !empty($adds['length']) ) echo $adds['length']; ?>" style="width: 50px;">
						</td>
						<td>
						x
						</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_order_adds[<?php echo $adds2_row; ?>][width]" value="<?php  if( !empty($adds['width']) ) echo $adds['width']; ?>" style="width: 50px;">
						</td>
						<td>
						x
						</td>
						<td>
						<input type="text" class="form-control" name="russianpost2_order_adds[<?php echo $adds2_row; ?>][height]" value="<?php  if( !empty($adds['height']) ) echo $adds['height']; ?>" style="width: 50px;">
						</td>
						</tr>
						</table>
					</td>
					<td>
						<input type="text" class="form-control" name="russianpost2_order_adds[<?php echo $adds2_row; ?>][srok]" value="<?php  if( !empty($adds['srok']) ) echo $adds['srok']; ?>">
					
					
					</td>
					<td>
						<input type="text" class="form-control" name="russianpost2_order_adds[<?php echo $adds2_row; ?>][sort_order]" value="<?php  if( !empty($adds['sort_order']) ) echo $adds['sort_order']; ?>">
					
					
					</td>
					<td>
						<select class="form-control"  name="russianpost2_order_adds[<?php echo $adds2_row; ?>][status]">
						  <?php if ($adds['status']) { ?>
						  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						  <?php } ?>
						</select>
					</td>
					<td><button type="button" onclick="$('#adds2-row<?php echo $adds2_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
				</tr>
				<?php $adds2_row++; ?>
				<?php } } ?>
				</tbody>
				<tfoot>
				<tr>
					<td colspan=9 class="text-right">
					
						<?php /* start metka-407 */ ?>
						<button type="button" onclick="addAdds2();" data-toggle="tooltip" 
						title="<?php echo $button_add_adds; ?>" class="btn btn-primary"
						><i class="fa fa-plus-circle"
						></i>&nbsp;&nbsp;<?php echo $button_add_adds; ?></button>
						<?php /* end metka-407 */ ?></td>
				</tr>
				</tfoot>
			</table>
			
			<?php /* start metka-407 */ ?>
			
			<h3><?php echo $text_adds_method_header; ?></h3>
			<div><?php echo $text_adds_method_header_notice; ?></div><br>
			<table id="method_adds" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td class="text-left"><?php echo $col_adds_method_name; ?></td>
						<td class="text-left"><?php echo $col_adds_method_cost; ?></td>
						<td class="text-left"><?php echo $col_adds_method_srok; ?></td>
						<td class="text-left"><?php echo $col_adds_method_status; ?></td>
						<td class="text-left"></td>
					</tr>
				</thead>
				<tbody class="tbody_class">
				<?php $adds3_row = 0; ?>
				<?php if( !empty($russianpost2_method_adds) ) { 
					foreach($russianpost2_method_adds as $adds) { ?>
				<tr id="adds3-row<?php echo $adds3_row; ?>">
					<td>
						<input type="hidden" class="form-control" 
						name="russianpost2_method_adds[<?php echo $adds3_row; ?>][adds_id]"
						value="<?php  if( !empty($adds['adds_id']) ) echo $adds['adds_id']; ?>">
						
						<input type="hidden" class="form-control"
						name="russianpost2_method_adds[<?php echo $adds3_row; ?>][type]" 
						value="method">
	
						<input type="hidden" class="form-control"
						name="russianpost2_method_adds[<?php echo $adds3_row; ?>][sort_order]" 
						value="0">
						
						<input type="text" class="form-control" 
						name="russianpost2_method_adds[<?php echo $adds3_row; ?>][name]" 
						value="<?php  if( !empty($adds['name']) ) echo $adds['name']; ?>">
					</td>
					<td>
						<input type="text" class="form-control" 
						name="russianpost2_method_adds[<?php echo $adds3_row; ?>][cost]" 
						value="<?php  if( !empty($adds['cost']) ) echo $adds['cost']; ?>">
						
						<?php /* start 2009 */ ?>
						<select name="russianpost2_method_adds[<?php echo $adds3_row; ?>][costtype]"
						 class="form-control">
							<option value="fix"
							<?php if( !empty($adds['costtype']) && $adds['costtype'] == 'fix' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_fix; ?></option>
							<?php /* start 2401 */ ?>
							<option value="fix2products"
							<?php if( !empty($adds['costtype']) && $adds['costtype'] == 'fix2products' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_fix2products; ?></option>
							<?php /* end 2401 */ ?>
							<option value="products_perc"
							<?php if(  !empty($adds['costtype']) && $adds['costtype'] == 'products_perc' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_products_perc; ?></option>
							<option value="delivery_perc"
							<?php if(  !empty($adds['costtype']) && $adds['costtype'] == 'delivery_perc' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_delivery_perc; ?></option>
							<option value="total_perc"
							<?php if(  !empty($adds['costtype']) && $adds['costtype'] == 'total_perc' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_total_perc; ?></option>
							<option value="minvalue"
							<?php if(  !empty($adds['costtype']) && $adds['costtype'] == 'minvalue' ) { ?> selected <?php } ?>
							><?php echo $text_order_adds_cost_minvalue; ?></option>
						</select>
						<?php /* end 2009 */ ?>
					</td>
					<td>
						<input type="text" class="form-control" 
						name="russianpost2_method_adds[<?php echo $adds3_row; ?>][srok]" 
						value="<?php  if( !empty($adds['srok']) ) echo $adds['srok']; ?>">
					
					
					</td>
					<td>
						<select class="form-control"  
						name="russianpost2_method_adds[<?php echo $adds3_row; ?>][status]">
						  <?php if ($adds['status']) { ?>
						  <option value="1" selected="selected"><?php echo $text_enabled; 
						  ?></option>
						  <option value="0"><?php echo $text_disabled; ?></option>
						  <?php } else { ?>
						  <option value="1"><?php echo $text_enabled; ?></option>
						  <option value="0" selected="selected"><?php echo $text_disabled; 
						  ?></option>
						  <?php } ?>
						</select>
					</td>
					<td><button type="button" 
					onclick="$('#adds3-row<?php echo $adds3_row; ?>, .tooltip').remove();" 
					data-toggle="tooltip" title="<?php echo $button_remove; ?>" 
					class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
				</tr>
				<?php $adds3_row++; ?>
				<?php } } ?>
				</tbody>
				<tfoot>
				<tr>
					<td colspan=7 class="text-right">
						<button type="button" onclick="addAdds3();" 
						data-toggle="tooltip" title="<?php echo $button_add_adds; ?>"
						class="btn btn-primary"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?php echo $button_add_adds; ?></button>
					</td>
				</tr>
				</tfoot>
			</table>
			<?php /* end metka-407 */ ?>
		</div>
		<div id="tab-regions" class="tab-pane">
		
		<?php /* start 20092 */ ?>
			<table width=100%>
			<tr>
			<td  width=50% valign=top style="padding-right: 20px;">
			
		<?php /* start 3110 */ ?>
			<script>
			var allZonesNumbers = [];
			var allZonesHash = [];
			
			<?php foreach($zones as $zone) { ?>
			allZonesNumbers[ allZonesNumbers.length ] = <?php echo $zone['zone_id']; ?>;
			allZonesHash[<?php echo $zone['zone_id']; ?> ] = {
				"zone_id": "<?php echo $zone['zone_id']; ?>",
				"name": "<?php echo $zone['name']; ?>",
				"is_assigned": <?php 
					if( empty($zones_to_select_hash[$zone['zone_id']]) ) 
						echo "1"; 
					else
						echo "0";
				?>
			};
			<?php } ?>
			
			function getToSelectHTML()
			{	
				var options = '';
				for(var i = 0; i< allZonesNumbers.length; i++)
				{
					if( allZonesHash[ allZonesNumbers[i] ]['is_assigned'] == 0 )
					{
						options += '<option value="'+allZonesHash[ allZonesNumbers[i] ].zone_id+'|'+allZonesHash[ allZonesNumbers[i] ].name+'">'+allZonesHash[ allZonesNumbers[i] ].name+'</option>';
					}
				}
				
				if( options == '' ) return false;
				
				html = '<select onchange="addZone(this);"  style="width: 100px;"><option value="">Добавить</option>';
				html += options;
				html += '</select>';
				
				return html;
			}
			
			function delZone(zone_id, block_id)
			{
				allZonesHash[zone_id]['is_assigned'] = 0;
				
				$('#'+block_id).remove();
				
				$('.addzoneselec').html(getToSelectHTML());
				$('.addzoneselec').show();
			}
			
			function addZone(th)
			{
				var ems_code = $(th).parent(".addzoneselec").attr("ems_code");
				var zone_id = th.value.split("|")[0];
				var name = th.value.split("|")[1];
				
				allZonesHash[zone_id] = {
					"zone_id": allZonesHash[zone_id].zone_id,
					"name": allZonesHash[zone_id].name,
					"is_assigned": 1
				};
				
				var html = getToSelectHTML();
				
				if( !html )
				$('.addzoneselec').hide();
				$('.addzoneselec').html(html);
				
				
				html = '<div id="zone_block_'+ems_code+'_'+zone_id+'">';
				html += '<input type="hidden" name="russianpost2_regions2zones['+ems_code+'][]" ';
				html += ' value="'+zone_id+'" ';
				html += ' >'+name+' <a href="javascript: delZone(\''+zone_id+'\', \'zone_block_'+ems_code+'_'+zone_id+'\');">Убрать</a></div>';
				
				$("#region_block_"+ems_code).append(html);
			}
			</script>
			<div><?php echo $text_russianpost_regions_notice; ?></div>
			<br>
			<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<td class="text-left"><b><?php echo $col_russianpost_region_name; ?></b></td>
					<td class="text-left"><b><?php echo $col_russianpost_region_select; ?></b></td>
				</tr>
			</thead>
			<tbody>
				<?php $zones_hash = array(); foreach($current_regions as $region ) { ?>
				<tr>
					<td class="text-left" style="width: 200px;"
					><?php echo $region['name']; ?></td>
					<td class="text-left" >
						<div style="float: left;"
						id="region_block_<?php echo $region['ems_code_formatted']; ?>">
						<?php if( $region['zones'] ) { ?>
							<?php foreach($region['zones'] as $zone) { 
								  $zones_hash[ $zone['zone_id'] ] = 1;
							?>
								<div
								id="zone_block_<?php echo $region['ems_code_formatted']; ?>_<?php echo $zone['zone_id']; ?>">
								<input type="hidden" name="russianpost2_regions2zones[<?php echo $region['ems_code_formatted']; ?>][]" 
									   value="<?php echo $zone['zone_id']; ?>"
								><?php echo $zone['name']; ?> <a href="javascript: delZone(<?php echo $zone['zone_id']; ?>, 'zone_block_<?php echo $region['ems_code_formatted']; ?>_<?php echo $zone['zone_id']; ?>');">Убрать</a></div>
							<?php } ?>
						<?php } ?>
						</div>
					<div class="addzoneselec" style="float: right; <?php if( !$zones_to_select ) { ?>display: none;<?php } ?>" ems_code="<?php echo $region['ems_code_formatted']; ?>" 
					>
						<select onchange="addZone(this);" style="width: 100px;">
					
							<option value="">Добавить</option>
							<?php foreach ($zones_to_select as $zone) { ?>
							<option value="<?php echo $zone['zone_id']; ?>|<?php echo $zone['name']; ?>"
								><?php echo $zone['name']; ?></option>
							<?php } ?>
							
						</select>
					</div>
					<br style="clear: both;">
					</td>
				</tr>
				<?php } ?>
			</tbody>
			</table>
		<?php /* end 3110 */ ?>
			
			</td>
			<td valign=top style="padding-left: 20px;">
			
			<?php if( !empty($skipped_countries) ) { ?>
			<div><?php echo $text_skipped_notice; ?></div>
			
			<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<td class="text-left"><b><?php echo $col_russianpost_country_name2; ?></b></td>
					<td class="text-left"><b>Iso Code (2)</b></td>
				</tr>
			</thead>
			<tbody>
			<?php foreach($skipped_countries as $row) { ?>
			<tr>
				<td><?php echo $row['country_name']; ?></td>
				<td><?php echo $row['iso_code']; ?></td>
			</tr>
			<?php } ?>
			</tbody>
			</table>
			<?php } ?>
			
			<br>
			<div><?php echo $text_russianpost_country_notice; ?></div>
			
			
			<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<td class="text-left"><b><?php echo $col_russianpost_country_select; ?></b></td>
					<td class="text-left"><b><?php echo $col_russianpost_country_name; ?></b></td>
				</tr>
			</thead>
			<tbody>
				<?php foreach($current_countries as $country_id=>$country ) { ?>
				<tr>
					<td class="text-left" style="width: 200px;"><?php
					echo $country['name']; ?> (<?php echo $country['iso_code_2']; ?>)</td>
					<td class="text-left" 
					id="country_td<?php echo $country['country_id']; ?>">
					<input type="hidden" name="russianpost2_countries_list[<?php echo $country['country_id']; ?>]"
					id="country_field<?php echo $country['country_id']; ?>" value="<?php
					if( !empty($russianpost2_countries_list[ $country['country_id'] ]) )
						echo $russianpost2_countries_list[ $country['country_id'] ]['id'];
					?>"
					>
					<?php
						if( !empty($russianpost2_countries_list[ $country['country_id'] ]) )
						{ ?>
							<span class="country_name"
							><?php echo $russianpost2_countries_list[ $country['country_id'] ]['name']; ?></span>
							&nbsp;&nbsp;<a href="javascript: setCountry(<?php echo $country['country_id']; ?>);"
							><?php echo $text_set_country; ?></a>
							<?php
						}
						else
						{
					?>
					
						<span class="country_name"
						><b><font color=red><?php echo $text_no_defined; 
						?></font></b></span>&nbsp;&nbsp;<a href="javascript: setCountry(<?php echo $country['country_id']; ?>);"><?php echo $text_set_country; 
						?></a>
						
					<?php	
						}
					?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
			</table>
		</td>
		</tr>
		</table>
		</div>
		
		<div id="tab-synx" class="tab-pane">
			
				  <p><?php echo $text_russianpost2_api; ?></p>
							<hr>
					
		<table width=100%>
		<tr>
			<td style="width: 220px; vertical-align: top;">
				<ul class="nav nav-pills nav-stacked">
					<?php $i = 0; foreach( $api_list as $api ) { $i++; ?>
						<li class="<?php if( ( empty($subtab2) && $i == 1 ) || (!empty($subtab2) && $api['api_key'] == $subtab2 ) ) { ?>active<?php } ?> api_links" id="apilink_<?php echo $api['api_key']; ?>"><a href="javascript: showApiTab('<?php echo $api['api_key']; ?>');"> <?php echo $api['api_name']; ?> </a></li>
					<?php } ?>
                </ul>
			</td>
			<td style="vertical-align: top;  padding-left: 10px;">
				
				<?php /* start 112 */ ?>
				<div id="apitab_tariff" class="api_tabs" 
				<?php if( !empty( $subtab2 ) && $subtab2 != 'tariff' ) { 
				?> style="display: none;" <?php } ?> >
				<h3><?php echo $api_list['tariff']['api_name']; ?></h3>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_info; ?>
						</label>
						<div class="col-sm-10" style="padding-top: 9px;">
							<?php echo $api_list['tariff']['info']; ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_condition; ?>
						</label>
						<div class="col-sm-10" style="padding-top: 9px;">
							<?php echo $api_list['tariff']['condition']; ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_status; ?>
						</label>
						<div class="col-sm-10">
							<select class="form-control"  name="russianpost2_api_tariff_status">
						  <?php if ($russianpost2_api_tariff_status) { ?>
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
							<?php echo $entry_api_sort_order; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="russianpost2_api_tariff_sort_order"
								value="<?php echo $russianpost2_api_tariff_sort_order; ?>"
							>
						</div>
					</div>
					
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_tariff_cache; ?>
						</label>
						<div class="col-sm-10">
							<select class="form-control"  name="russianpost2_api_tariff_cache">
							  <?php if ($russianpost2_api_tariff_cache) { ?>
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
							<?php echo $entry_tariff_cache_lifetime; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" 
							name="russianpost2_tariff_cache_lifetime"
								value="<?php echo $russianpost2_tariff_cache_lifetime; ?>"
							>
							
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_tariff_curl_lifetime; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" 
							name="russianpost2_tariff_curl_lifetime"
								value="<?php echo $russianpost2_tariff_curl_lifetime; ?>"
							>
							
						</div>
					</div>
					
				</div>
				
				<div id="apitab_postcalc" class="api_tabs"  <?php if( empty( $subtab2 ) || $subtab2 != 'postcalc' ) { ?> style="display: none;" <?php } ?>>
					<h3><?php echo $api_list['postcalc']['api_name']; ?></h3>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_info; ?>
						</label>
						<div class="col-sm-10" style="padding-top: 9px;">
							<?php echo $api_list['postcalc']['info']; ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_condition; ?>
						</label>
						<div class="col-sm-10" style="padding-top: 9px;">
							<?php echo $api_list['postcalc']['condition']; ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_status; ?>
						</label>
						<div class="col-sm-10">
							<select class="form-control"  name="russianpost2_api_postcalc_status">
							  <?php if ($russianpost2_api_postcalc_status) { ?>
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
							<?php echo $entry_postcalc_cache; ?>
						</label>
						<div class="col-sm-10">
							<select class="form-control"  name="russianpost2_api_postcalc_cache">
							  <?php if ($russianpost2_api_postcalc_cache) { ?>
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
							<?php echo $entry_postcalc_cache_lifetime; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="russianpost2_postcalc_cache_lifetime"
								value="<?php echo $russianpost2_postcalc_cache_lifetime; ?>"
							>
							
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_postcalc_curl_lifetime; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" 
							name="russianpost2_postcalc_curl_lifetime"
								value="<?php echo $russianpost2_postcalc_curl_lifetime; ?>"
							>
							
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_postcalc_email; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="russianpost2_api_postcalc_email"
								value="<?php echo $russianpost2_api_postcalc_email; ?>"
							>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_postcalc_key; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="russianpost2_api_postcalc_key"
								value="<?php echo $russianpost2_api_postcalc_key; ?>"
							>
							<div><?php echo $entry_postcalc_key_notice; ?></div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_sort_order; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="russianpost2_api_postcalc_sort_order"
								value="<?php echo $russianpost2_api_postcalc_sort_order; ?>"
							>
						</div>
					</div>
					
					
				</div>
					
				<div id="apitab_otpravka" class="api_tabs"   <?php if( empty( $subtab2 ) || $subtab2 != 'otpravka' ) { ?> style="display: none;" <?php } ?>>
					<h3><?php echo $api_list['otpravka']['api_name']; ?></h3>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_info; ?>
						</label>
						<div class="col-sm-10" style="padding-top: 9px;">
							<?php echo $api_list['otpravka']['info']; ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_condition; ?>
						</label>
						<div class="col-sm-10" style="padding-top: 9px;">
							<?php echo $api_list['otpravka']['condition']; ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_status; ?>
						</label>
						<div class="col-sm-10">
							<select class="form-control"  name="russianpost2_api_otpravka_status">
							  <?php if ($russianpost2_api_otpravka_status) { ?>
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
							<?php echo $entry_api_otpravka_token; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="russianpost2_api_otpravka_token"
								value="<?php echo $russianpost2_api_otpravka_token; ?>"
							>
							<div><?php echo $entry_api_otpravka_token_notice; ?></div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_otpravka_key; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="russianpost2_api_otpravka_key"
								value="<?php echo $russianpost2_api_otpravka_key; ?>"
							>
							<div><?php echo $entry_api_otpravka_key_notice; ?></div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_otpravka_cache; ?>
						</label>
						<div class="col-sm-10">
							<select class="form-control"  name="russianpost2_api_otpravka_cache">
							  <?php if ($russianpost2_api_otpravka_cache) { ?>
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
							<?php echo $entry_otpravka_cache_lifetime; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="russianpost2_otpravka_cache_lifetime"
								value="<?php echo $russianpost2_otpravka_cache_lifetime; ?>"
							>
							
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_otpravka_curl_lifetime; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" 
							name="russianpost2_otpravka_curl_lifetime"
								value="<?php echo $russianpost2_otpravka_curl_lifetime; ?>"
							>
							
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_api_sort_order; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="russianpost2_api_otpravka_sort_order"
								value="<?php echo $russianpost2_api_otpravka_sort_order; ?>"
							>
						</div>
					</div>
					
					
					<h3><?php echo $header_otprvka_pvz ?></h3>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_optravka_pvz; ?>
						</label>
						<div class="col-sm-10">
							<a class="btn btn-primary" 
							onclick="window.location.href='<?php echo $update_pvz_action; ?>'" 
							><span><?php echo $button_pvz; ?></span></a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_optravka_pvz_mode; ?>
						</label>
						<div class="col-sm-10">
							<select name="russianpost2_optravka_pvz_mode" class="form-control">
							  <option value="each_day" <?php if( $russianpost2_optravka_pvz_mode == 'each_day' ) { ?> selected="selected" <?php } ?>><?php echo $entry_optravka_pvz_mode_each_day; ?></option>
							  <option value="each_week" <?php if( $russianpost2_optravka_pvz_mode == 'each_week' ) { ?> selected="selected" <?php } ?>><?php echo $entry_optravka_pvz_mode_each_week; ?></option>
							  <option value="each_month" <?php if( $russianpost2_optravka_pvz_mode == 'each_month' ) { ?> selected="selected" <?php } ?>><?php echo $entry_optravka_pvz_mode_each_month; ?></option>
							  <option value="by_button" <?php if( $russianpost2_optravka_pvz_mode == 'by_button' ) { ?> selected="selected" <?php } ?>><?php echo $entry_optravka_pvz_mode_button; ?></option>
							  <option value="by_cron" <?php if( $russianpost2_optravka_pvz_mode == 'by_cron' ) { ?> selected="selected" <?php } ?>><?php echo $entry_optravka_pvz_mode_cron; ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_otpravka_pvz_cron; ?>
						</label>
						<div class="col-sm-10">
							<?php echo $russianpost2_pvz_cron_command; ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_otpravka_pvz_date; ?>
						</label>
						<div class="col-sm-10">
							<?php echo $russianpost2_pvz_date; ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-access-secret">
							<?php echo $entry_otpravka_pvz_curl_lifetime; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" 
								name="russianpost2_otpravka_pvz_curl_lifetime"
								value="<?php echo $russianpost2_otpravka_pvz_curl_lifetime; ?>"
							>
						</div>
					</div>
					
				</div>
				<?php /* end metka-2006 */ ?>
			</td>
		</tr>
		</table>
		
		
		
		</div>
		
		<div id="tab-cod" class="tab-pane">
		
		
				  <p><?php echo $text_russianpost2_cod; ?></p>
		<hr>
		
		<p><?php echo $text_cod_notice; ?></p>
		
		
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_cod_script; ?>
				</label>
				<div class="col-sm-10">
					<table>
					<tr>
						<td>
							<input type="radio" name="russianpost2_cod_script" value="full"
							<?php if($russianpost2_cod_script == 'full') { ?> checked <?php } ?>
							id="russianpost2_cod_script_full"
							onclick="showCodBlock(this.value);"
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_cod_script_full" style="font-weight: normal;" 
							><?php echo $text_cod_script_full; ?></label>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<b><?php echo $col_cod_instruction; ?>:</b><br>
							<?php echo $text_cod_script_full_notice; ?>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="russianpost2_cod_script" value="onlyshipping"
							<?php if($russianpost2_cod_script == 'onlyshipping') { ?> checked <?php } ?>
							onclick="showCodBlock(this.value);"
							id="russianpost2_cod_script_onlyshipping"
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_cod_script_onlyshipping" style="font-weight: normal;" 
							><?php echo $text_cod_script_onlyshipping; ?></label>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<b><?php echo $col_cod_instruction; ?>:</b><br>
							<?php echo $text_cod_script_onlyshipping_notice; ?>
						</td>
					</tr>
					</table>
				</div>
			</div>
			
		
			<div class="form-group" id="cod_block_onlyshipping">
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_cod_is_double; ?>
				</label>
				<div class="col-sm-10">
					<select class="form-control"  name="russianpost2_cod_is_double">
					  <?php if ($russianpost2_cod_is_double) { ?>
					  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					  <option value="0"><?php echo $text_disabled; ?></option>
					  <?php } else { ?>
					  <option value="1"><?php echo $text_enabled; ?></option>
					  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					  <?php } ?>
					</select>
					<p><?php echo $entry_cod_is_double_notice; ?></p>
				</div>
			</div>
			
			
			<div class="form-group" >
				<label class="col-sm-2 control-label" for="input-access-secret">
					<?php echo $entry_cod_is_cod_included; ?><span  id="cod_block_full"></span>
				</label>
				<div class="col-sm-10">
					<table>
					<tr>
						<td>
							<input type="radio" name="russianpost2_is_cod_included" value="inmod"
							<?php if($russianpost2_is_cod_included == 'inmod') { ?> checked <?php } ?>
							id="russianpost2_is_cod_included_inmod"
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_is_cod_included_inmod" style="font-weight: normal;" 
							><?php echo $entry_cod_is_cod_included_inmod; ?></label>
						</td>
					</tr>
					<!--
					<tr>
						<td>
							<input type="radio" name="russianpost2_is_cod_included" value="none"
							<?php if($russianpost2_is_cod_included == 'none') { ?> checked <?php } ?>
							id="russianpost2_is_cod_included_none"
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_is_cod_included_none" style="font-weight: normal;" 
							><?php echo $entry_cod_is_cod_included_none; ?></label>
						</td>
					</tr>
					-->
					<tr>
						<td>
							<input type="radio" name="russianpost2_is_cod_included" value="incost"
							<?php if($russianpost2_is_cod_included == 'incost') { ?> checked <?php } ?>
							id="russianpost2_is_cod_included_incost"
							>
						</td>
						<td style="padding-top: 5px; padding-left: 10px;">
							<label for="russianpost2_is_cod_included_incost" style="font-weight: normal;" 
							><?php echo $entry_cod_is_cod_included_incost; ?></label>
						</td>
					</tr>
					</table>
				</div>
			</div>
			
			
			
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_cod_tariftype; ?>
			</label>
            <div class="col-sm-10">
			
			<select name="russianpost2_cod_tariftype" class="form-control"  onchange="showBlock(this.value);">
                <?php if ( empty($russianpost2_cod_tariftype) || $russianpost2_cod_tariftype == 'default') { ?>
                <option value="default" selected="selected"><?php echo $entry_cod_tariftype_default; ?></option>
                <option value="set"><?php echo $entry_cod_tariftype_set; ?></option>
                <?php } else { ?>
                <option value="default"><?php echo $entry_cod_tariftype_default; ?></option>
                <option value="set" selected="selected"><?php echo $entry_cod_tariftype_set; ?></option>
                <?php } ?>
              </select>
			  </div>
        </div>
		  
		  
		<script>
		
		function showCodBlock(value)
		{
			if( value == 'full' )
			{
				document.getElementById('cod_block_full').style.display = 'block';
				document.getElementById('cod_block_onlyshipping').style.display = 'none';
			}
			else
			{
				document.getElementById('cod_block_onlyshipping').style.display = 'block';
				document.getElementById('cod_block_full').style.display = 'none';
			}
		}
		
		showCodBlock('<?php echo $russianpost2_cod_script; ?>');
		
		function showBlock(value)
		{
			if( value == 'default' )
				document.getElementById('tarif_block').style.display = 'none';
			else
				document.getElementById('tarif_block').style.display = 'block';
				
		}
		
		</script>
		<div id="tarif_block" <?php if( empty($russianpost2_cod_tariftype) || 
		$russianpost2_cod_tariftype == 'default' ) { ?> style="display: none;" <?php } ?>>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_cod_tariftype_percent; ?>
			</label>
            <div class="col-sm-10">
			
				<input type="text" name="russianpost2_cod_tariftype_percent" 
				value="<?php echo $russianpost2_cod_tariftype_percent; ?>" class="form-control"  />
			
			</div>
        </div>
		
		<?php /* start 2901 */ ?>
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_cod_tariftype_minvalue; ?>
			</label>
            <div class="col-sm-10">
			
				<input type="text" name="russianpost2_cod_tariftype_minvalue" 
				value="<?php echo $russianpost2_cod_tariftype_minvalue; ?>" class="form-control"  />
			
			</div>
        </div>
		
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_cod_tariftype_ems_percent; ?>
			</label>
            <div class="col-sm-10">
			
				<input type="text" name="russianpost2_cod_tariftype_ems_percent" 
				value="<?php echo $russianpost2_cod_tariftype_ems_percent; ?>" class="form-control"  />
			
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_cod_tariftype_ems_minvalue; ?>
			</label>
            <div class="col-sm-10">
			
				<input type="text" name="russianpost2_cod_tariftype_ems_minvalue" 
				value="<?php echo $russianpost2_cod_tariftype_ems_minvalue; ?>" class="form-control"  />
			
			</div>
        </div>
		<?php /* end 2901 */ ?>
			
			
		</div>
		
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_rpcod_title; ?>
			</label>
            <div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
              <textarea cols=40 class="form-control" 
			  name="russianpost2_rpcod_title[<?php echo $language['language_id']; ?>]"
			  ><?php echo isset($russianpost2_rpcod_title[$language['language_id']]) ? $russianpost2_rpcod_title[$language['language_id']] : '';
			  ?></textarea><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
			 <?php } ?>
			</div>
        </div>
		
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_rpcod_ecom_title; ?>
			</label>
            <div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
              <textarea cols=40 class="form-control" 
			  name="russianpost2_rpcod_ecom_title[<?php echo $language['language_id']; ?>]"
			  ><?php echo isset($russianpost2_rpcod_ecom_title[$language['language_id']]) ? $russianpost2_rpcod_ecom_title[$language['language_id']] : '';
			  ?></textarea><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
			 <?php } ?>
			</div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_rpcodtotal_title; ?>
			</label>
            <div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
              <textarea cols=40 class="form-control" 
			  name="russianpost2_rpcodtotal_title[<?php echo $language['language_id']; ?>]"
			  ><?php echo isset($russianpost2_rpcodtotal_title[$language['language_id']]) ? $russianpost2_rpcodtotal_title[$language['language_id']] : '';
			  ?></textarea><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
			 <?php } ?>
			</div>
        </div>
		
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_rpcod_ems_title; ?>
			</label>
            <div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
              <textarea cols=40 class="form-control" 
			  name="russianpost2_rpcod_ems_title[<?php echo $language['language_id']; ?>]"
			  ><?php echo isset($russianpost2_rpcod_ems_title[$language['language_id']]) ? $russianpost2_rpcod_ems_title[$language['language_id']] : '';
			  ?></textarea><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
			 <?php } ?>
			</div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_rpcodonly_title; ?>
			</label>
            <div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
              <textarea cols=40 class="form-control" 
			  name="russianpost2_rpcodonly_title[<?php echo $language['language_id']; ?>]"
			  ><?php echo isset($russianpost2_rpcodonly_title[$language['language_id']]) ? $russianpost2_rpcodonly_title[$language['language_id']] : '';
			  ?></textarea><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
			 <?php } ?>
			</div>
        </div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_cod_mintotal; ?>
			</label>
            <div class="col-sm-10">
			  <input type="text"  class="form-control" name="russianpost2_cod_mintotal" value="<?php echo $russianpost2_cod_mintotal; ?>" />
			</div>
        </div>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_cod_maxtotal; ?>
			</label>
            <div class="col-sm-10">
			  <input type="text" class="form-control"  name="russianpost2_cod_maxtotal" value="<?php echo $russianpost2_cod_maxtotal; ?>" />
			</div>
        </div>
		
</div>
		
		<div id="tab-support" class="tab-pane">
			<?php echo $text_support; ?>
		</div>
		
		
        </form>
      </div>
    </div>
  </div>
</div>

  <script type="text/javascript"><!--
var method_row = <?php echo $method_row; ?>;



function getMethoCode() 
{
	for(var i=1; i<10; i++)
	{
		if( !$('#russianpost2f'+i).val() )
		{
			return i;
		}
	}
	
	return false;
}

function addMethod() {

var new_sort_order = method_row+1;

var method_code = getMethoCode();

if( !method_code ) {
	alert("<?php echo $text_none_code; ?>");
	return;
}
html  = '';
html  += '<table id="methods'+method_row+'" class="table table-striped table-bordered table-hover">';
html  += '<thead>';
html  += '<tr>';
html  += '<td class="text-center" style="text-transform: uppercase;" colspan=7><?php echo $text_method_group; ?><?php echo $method['name']; ?> (<?php echo $method['code']; ?>)</td>';
html  += '</tr>';
html  += '<tr>';
html  += '<td class="text-left"><?php echo $col_method_code; ?></td>';
html  += '<td class="text-left"><?php echo $col_method_image; ?></td>';
html  += '<td class="text-left"><?php echo $col_method_title; ?></td>';
html  += '<td class="text-left"><?php echo $col_method_sort_order; ?></td>';
html  += '<td class="text-left"><?php echo $col_method_filter; ?></td>';
html  += '<td class="text-left"><?php echo $col_method_status; ?></td>';
html  += '<td></td>';
html  += '</tr>';
html  += '</thead>';
html  += '<tbody>';
html  += '<tr id="method-row' + method_row + '">';
html  += '<td class="text-left" > russianpost2f'+method_code+' <input type="hidden" name="russianpost2_methods['+method_row+'][code]" id="russianpost2f'+method_code+'" value="russianpost2f'+method_code+'"></td>';
					
html  += '<td class="text-left">';
html  += '<div style="margin-bottom: 5px; width: 150px;">';
html  += '<a href="" id="thumb-image' + method_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $default_thumb; ?>" alt="" title="" data-placeholder="" /></a>';
html  += '<input type="hidden" name="russianpost2_methods[' + method_row + '][image]" value="<?php echo $default_image; ?>" id="input-image' + method_row + '" />';
html  += '</div>';
					
html  += '<div>';
html  += '<input type="text" name="russianpost2_methods[' + method_row + '][image_width]" value="<?php echo $default_width; ?>" class="form-control" style="width: 50px; float: left;" /><span style="float: left; padding: 5px;"> x </span><input type="text" name="russianpost2_methods[' + method_row + '][image_height]" value="<?php echo $default_height; ?>" class="form-control" style="width: 50px; float: left;"  />';
html  += '</div>';

html  += '<br style="clear: both;">';
html  += '<div><input type="checkbox" name="russianpost2_methods[' + method_row + '][is_show_image]" id="is_show_image_' + method_row + '" ';
html  += '><label for="is_show_image_' + method_row + '"><?php echo $text_show_image; ?></label></div>';


html  += '</td>';
				  
html  += '<td class="text-left">';
<?php foreach ($languages as $language) { ?>
html  += '<div class="input-group pull-left"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> ';
html  += '<input type="text" name="russianpost2_methods[' + method_row + '][title][<?php echo $language['language_id']; ?>]" value="" placeholder="<?php echo $entry_method_title; ?>" class="form-control" />';
html  += '</div>';
<?php } ?>
				  
html  += '</td>';

html  += '<td  class="text-left" style="width: 15%;"><input type="text" name="russianpost2_methods[' + method_row + '][sort_order]" value="'+new_sort_order+'" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />';
html  += '<table border=0 width=100% ';
html  += ' id="sort_orders' + method_row + '"  ';
html  += ' class="table table-striped table-bordered table-hover">';
html  += '<tbody class="tbody2_class">  </tbody>';
html  += '<tfoot>';
html  += '<tr>';
html  += '<td colspan=3 style="padding: 5px;"><button type="button" ';
html  += ' onclick="addMethodsSort(\'' + method_row + '\');" ';
html  += ' data-toggle="tooltip" title="<?php echo $button_add_sort; ?>" ';
html  += ' class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
html  += '</tr>';
html  += '</tfoot>';
html  += '</table></td>';


html  += '<td class="text-left">';
html  += '<table border=0 width=100% id="method_filters' + method_row + '"  class="table table-striped table-bordered table-hover">';
html  += '<tbody class="tbody2_class">';
html  += '</tbody>';
html  += '<tfoot>';
html  += '<tr>';
html  += '<td colspan=2 style="padding: 5px;"><button type="button" onclick="addFilterBlock3(\'' + method_row + '\');" data-toggle="tooltip" title="<?php echo $button_add_filter; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
html  += '</tr>';
html  += '</tfoot>';
html  += '</table>';
				  

  
html  += '</td>';


				
html  += '<td class="text-left" > ';
html  += '<select name="russianpost2_methods[' + method_row + '][status]" class="form-control">';
html  += '<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
html  += '<option value="0"><?php echo $text_disabled; ?></option>';
html  += '</select>';

html  += '</td>';
				  
				  
html  += '<td class="text-left">';
html  += '<button type="button" onclick="$(\'#method-row' + method_row + ', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
html  += '</td>';
html  += '</tr>';

html  += '<tr>';
html  += ' <td colspan="7">';
html  += '<table id="submethods' + method_row + '" class="table table-striped table-bordered table-hover">';
html  += '<tbody class="tbody"> ';

html  += '</tbody>';
html  += '<tfoot>';
html  += '<tr>';
html  += '<td colspan="7" class="text-right">';
html  += '<button type="button" onclick="addSubMethod(' + method_row + ', \'russianpost2f'+method_code+'\');" data-toggle="tooltip" title="<?php echo $button_method_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?php echo $button_method_add; ?></button></td>';
html  += '</tr>';
html  += '</tfoot>';
html  += '</table>';
			  
html  += '</td>';
html  += '</tr>';

	$('#methods_list').append(html);
	
	submethod_row[method_row] = 1;
	addSubMethod(method_row, 'russianpost2f'+method_code);
	
	sorts_hash[method_row] = 0;
	method_row++;
}

// ------------------------------------------

var submethod_row = new Array();
<?php foreach( $submethod_row as $method_row=>$sort_order ) { ?> 
submethod_row[<?php echo $method_row; ?>] = <?php echo $sort_order; ?>;
<?php } ?>

// -----

function addSubMethod(method_row, method_code) {

	var rand = Math.floor((Math.random() * 100000000) + 1);
var new_sort_order = submethod_row[method_row];

var submethod_code = method_code+'.rp'+submethod_row[method_row];

var html = '';
html  += '<tr id="submethod-row' + method_row + '-'+submethod_row[method_row]+'">';
html  += '<td class="text-left" rowspan=2> ';
html  += submethod_code;
html  += '<input type="hidden" name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][code]" value="'+submethod_code+'" id="'+submethod_code+'">';
html  += '</td>';
						  
html  += '<td class="text-left">';
html  += '<div style="margin-bottom: 5px; width: 150px;">';
html  += '<a href="" id="thumb-image' + method_row + '-'+submethod_row[method_row]+'" data-toggle="image" class="img-thumbnail"><img src="<?php echo $default_thumb; ?>" alt="" title="" data-placeholder="" /></a>';
html  += '<input type="hidden" name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][image]" value="<?php echo $default_image; ?>" id="input-image' + method_row + '-'+submethod_row[method_row]+'" />';
html  += '</div>';
							
html  += '<div>';
html  += '<input type="text" name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][image_width]" value="<?php echo $default_width; ?>" class="form-control" style="width: 50px; float: left;" /><span style="float: left; padding: 5px;"> x </span><input type="text" name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][image_height]" value="<?php echo $default_height; ?>" class="form-control" style="width: 50px; float: left;"  />';
html  += '</div>';
html  += '<br style="clear: both;"> ';

html  += '<div><input type="checkbox" name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][is_show_image]" id="is_show_image_' + method_row + '_'+submethod_row[method_row]+'" ';
html  += '><label for="is_show_image_' + method_row + '_'+submethod_row[method_row]+'"><?php echo $text_show_image; ?></label></div> ';

html  += '</td>';
						  
html  += '<td class="text-left">';
<?php foreach ($languages as $language) { ?>
html  += '<div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> ';
html  += '<input type="text" name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][title][<?php echo $language['language_id']; ?>]" value="" placeholder="<?php echo $entry_method_title; ?>" class="form-control" />';
html  += '</div>';
<?php } ?>


html  += '<div  id="desclink' + method_row + '-'+submethod_row[method_row]+'">';
html  += '<a href="javascript: $(\'#desc' + method_row + '-'+submethod_row[method_row]+'\').show(); $(\'#desclink' + method_row + '-'+submethod_row[method_row]+'\').hide();"><?php echo $text_description_link; ?></a>';
html  += '</div>';
html  += '<div><?php echo $text_tags_notice2; ?></div>';
html  += '<div style="display: none;" id="desc' + method_row + '-'+submethod_row[method_row]+'" >';
html  += '<div><?php echo $text_description; ?></div>';
				  <?php foreach ($languages as $language) { ?>
html  += '<div class="input-group pull-left" style="width: 100%;"><span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span> ';
html  += '<textarea rows=3 name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][desc][<?php echo $language['language_id']; ?>]" ';
html  += 'class="form-control" ></textarea>';
html  += '</div>';
                  <?php } ?>
html  += '</div>';

html  += '</td>';
/* start 112 */

/* start 1606 */
html  += '<td  class="text-left" style="width: 10%;"><input type="text" name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][sort_order]" value="'+new_sort_order+'" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />';
/* end 1606 */

html  += '</td>';
/* end 112 */		  
						  
html  += '<td class="text-left" style="min-width: 200px;">';


html  += '<table border=0 width=100% id="submethod_filters' + method_row + '-'+submethod_row[method_row]+'"  class="table table-striped table-bordered table-hover">';
html  += '<tbody class="tbody2_class">';		
html  += '</tbody>';
html  += '<tfoot>';
html  += '<tr>';
html  += '<td colspan=2 style="padding: 5px;"><button type="button" onclick="addOrdersFilterBlock(\'submethod_filters' + method_row + '-'+submethod_row[method_row]+'\', \'russianpost2_methods[' + method_row + '][submethods]\', \''+submethod_row[method_row]+'\');" data-toggle="tooltip" title="<?php echo $button_add_filter; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
html  += '</tr>';
html  += '</tfoot>';
html  += '</table>';


  
html  += '</td>';
						  
						  
html  += '<td class="text-left" > ';
html  += '<select name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][status]" class="form-control">';
html  += '<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
html  += '<option value="0"><?php echo $text_disabled; ?></option>';
							
html  += '</select>';
html  += '</td>';
						  
						  
html  += '<td class="text-left"><button type="button" onclick="$(\'#submethod-row'+method_row+'-'+submethod_row[method_row]+', .tooltip\').remove(); $(\'#submethod-row'+method_row+'-'+submethod_row[method_row]+'-line2, .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
html  += '</tr>';

html  += '<tr id="submethod-row' + method_row + '-'+submethod_row[method_row]+'-line2">';
html  += '<td class="text-right" > <?php echo $text_services; ?></td>';

html  += '<td class="text-right" >';

html  += '<table border=0 width=100% id="servtab' + method_row + '-'+submethod_row[method_row]+'">';
html  += '					<tbody>';
html  += '<tr id="serv' + method_row + '-'+submethod_row[method_row]+'-0">';
html  += '						<td style="padding: 5px;">';
html  += '<select name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][services][0][service]" class="form-control">';

html  += '<option value=""><?php echo $text_add_service; ?></option>';

<?php /* start 2801 */ ?>
html  += '<option value="" disabled>-------------</option> ';
html  += '<option value="free" ';
html  += '><?php echo $text_free_service; ?></option> ';
<?php if( !empty($russianpost2_customs) ) { foreach($russianpost2_customs as $custom) { ?>
html  += '<option value="<?php echo $custom['custom_id']; ?>"  ';
html  += '><?php echo $custom['name']; ?></option> ';
<?php } }?>
html  += '<option value="" disabled >-------------</option> ';
<?php /* end 2801 */ ?>
<?php foreach($services_list as $serv) { ?>
html  += '<option value="<?php echo $serv['service_key']; ?>"><?php echo $serv['service_name']; ?></option>';
<?php } ?>
html  += '</select></td>';

	html += '<td style="padding: 5px;"><select name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][services][0][filter]" class="form-control"><option value=""><?php echo $text_no_filter; ?></option>';

<?php foreach($russianpost2_order_filters as $ft) { ?>
	html += '<option value="<?php echo $ft['filter_id']; ?>"><?php echo $ft['filtername']; ?></option>';
<?php } ?>
	html += '</select></td>';


html  += '<td style="padding: 5px;">';
html  += '<button type="button" onclick="$(\'#serv' + method_row + '-'+submethod_row[method_row]+'-0, .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
html  += '</td>';
html  += '</tr>';		
html  += '</tbody>';
html  += '<tfoot>';
html  += '<tr>';
html  += '<td colspan=3 style="padding: 5px;"><button type="button" onclick="addServiceBlock(\'' + method_row + '\', \''+submethod_row[method_row]+'\');" data-toggle="tooltip" title="<?php echo $button_add_service; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
html  += '</tr>';
html  += '</tfoot>';
html  += '</table>';


html  += '</td>';

html  += '<td>';
<?php /* start 1606 */ ?>

html  += '<div>';
html  += '<?php echo $text_is_show_cod; ?><br>';
html  += '<select name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][is_show_cod]" class="form-control"  style="max-width: 250px;" >';
html  += '<option value="1"><?php echo $text_enabled; ?></option>';
html  += '<option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
html  += '</select>';
						  
html  += '</div>';
html  += '<?php echo $text_services_sorttype; ?>';
html  += '<select name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][services_sorttype]" class="form-control"><option value="minprice"><?php echo $text_services_sorttype_minprice; ?></option><option value="minsrok"><?php echo $text_services_sorttype_order; ?></option><option value="order"><?php echo $text_services_sorttype_minsrok; ?></option></select>';

html  += '</td>';
html  += '<td colspan=3>';


html  += '<div><?php echo $text_is_pack; ?></div>';
						  
html  += '<select name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row[method_row]+'][is_pack]" class="form-control" style="max-width: 300px;" >';
						  
						  
html  += '<option value=""><?php echo $text_disabled; ?></option>';
html  += '<option value="" disabled>-------------</option> ';
html  += '<option value="1" ><?php echo $text_autoselect; ?></option>';
html  += '<option value="" disabled>-------------</option> ';
							<?php if( $custom_packs ) { ?>
							<?php foreach($custom_packs as $i=>$custom_pack) { ?>
html  += '<option value="c<?php echo $i; ?>" ><?php echo $custom_pack['name']; ?></option> ';
							<?php } ?>
html  += '<option value="" disabled>-------------</option> ';
							<?php } ?>
							<?php foreach($packs as $pack) { ?>
html  += '<option value="<?php echo $pack['pack_key']; ?>" ';
html  += '><?php echo $pack['name']; ?></option>';
							<?php } ?>
html  += '</select>';

html  += '<?php echo $text_services_adds; ?>';

/* start 0110 */
html  += '<table border=0 width=100% ';
html  += ' id="submethod_adds' + method_row + '-'+submethod_row[method_row]+'"  class="table table-striped table-bordered table-hover"> ';
html  += ' <tbody class="tbody2_class"></tbody> ';
html  += ' <tfoot> ';
html  += ' <tr> ';
html  += ' <td colspan=2 style="padding: 5px;"><button type="button"  ';
html  += ' onclick="addMethodsAddsBlock(\'submethod_adds' + method_row + '-'+submethod_row[method_row]+'\', \'russianpost2_methods[' + method_row + '][submethods]\', \''+submethod_row[method_row]+'\');" data-toggle="tooltip" title="<?php echo $button_add_adds; ?>"  ';
html  += ' class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td> ';
html  += ' </tr> ';
html  += ' </tfoot> ';
html  += ' </table> ';
/* end 0110 */


<?php /* end 1606 */ ?>

html  += '</td>';
html  += '</tr>';
html  += '</table>';

	$('#submethods'+method_row+' .tbody').append(html);
	
	submethod_row[method_row]++;
}

/* start 0711 */
var cg_row = new Array();
<?php foreach( $cg_row as $filter2_row=>$sort_order ) { ?> 
cg_row[<?php echo $filter2_row; ?>] = <?php echo $sort_order; ?>;
<?php } ?>

function addCustomerGroupBlock(filter2_row )
{
	cg_row[filter2_row]++;
	var html = '';
	html += '<tr id="cg'+filter2_row+'-'+cg_row[filter2_row]+'">';
	html += '<td style="padding: 5px;">';
		
	html += '<select name="russianpost2_order_filters['+filter2_row+'][customer_groups]['+cg_row[filter2_row]+']" class="form-control">';
					
<?php foreach($customer_groups as $customer_group) { ?>
	html += '<option value="<?php echo $customer_group['customer_group_id']; ?>" ';
	html += '><?php echo $customer_group['name']; ?></option>';
<?php } ?>
	html += '</select>';
	html += '</td>';
	html += '<td style="padding: 5px;">';
	html += '<button type="button" onclick="$(\'#cg'+filter2_row+'-'+cg_row[filter2_row]+', .tooltip\').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
	html += '</td>';
	html += '</tr>';
	
	$('#cgtab'+filter2_row+' tbody').append(html);
}

/* end 0711 */

function addServiceBlock(method_row, submethod_row2)
{
	var rand = Math.floor((Math.random() * 100000000) + 1);
	var html = '<tr id="serv'+method_row+'-'+submethod_row2+'-'+rand+'"><td style="padding: 5px;">';
	html += '<select name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row2+'][services]['+rand+'][service]" class="form-control"><option value=""><?php echo $text_add_service; ?></option>';
 
<?php /* start 2801 */ ?>
	html  += '<option value="" disabled>-------------</option> ';
	html  += '<option value="free" ';
	html  += '><?php echo $text_free_service; ?></option> ';
	<?php if( !empty($russianpost2_customs) ) { foreach($russianpost2_customs as $custom) { ?>
	html  += '<option value="<?php echo $custom['custom_id']; ?>"  ';
	html  += '><?php echo $custom['name']; ?></option> ';
	<?php } }?>
	html  += '<option value="" disabled >-------------</option> ';
<?php /* end 2801 */ ?>
					
	<?php foreach($services_list as $serv) { ?>
	html  += '<option value="<?php echo $serv['service_key']; ?>"><?php echo $serv['service_name']; ?></option>';
	<?php } ?>
	html  += '</select></td>';
	
	html  += '<td style="padding: 5px;">';
	
	html  += '<select name="russianpost2_methods[' + method_row + '][submethods]['+submethod_row2+'][services]['+rand+'][filter]" class="form-control"><option value=""><?php echo $text_no_filter; ?></option>';
<?php foreach($russianpost2_order_filters as $ft) { ?>
	html  += '<option value="<?php echo $ft['filter_id']; ?>"><?php echo $ft['filtername']; ?></option>';
<?php } ?>
	html  += '</select>';
	
	
	html  += '</td><td style="padding: 5px;">';
	html  += '<button type="button" onclick="$(\'#serv'+method_row+'-'+submethod_row2+'-'+rand+', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
	html  += '</td></tr>';
	
	
	$('#servtab'+method_row+'-'+submethod_row2+' tbody').append(html);
	
}

function addServiceBlock2(method_row)
{
	var rand = Math.floor((Math.random() * 100000000) + 1);
	var html = '<tr id="order_service_adds'+method_row+'-'+rand+'"><td style="padding: 5px;">';
	html += '<select name="russianpost2_order_adds[' + method_row + '][services][]" class="form-control" style="width: 150px;"><option value=""><?php echo $text_add_service; ?></option>';
 
	<?php foreach($services_list as $serv) { ?>
	html  += '<option value="<?php echo $serv['service_key']; ?>"><?php echo $serv['service_name']; ?></option>';
	<?php } ?>
	html  += '</select></td>';
	
	html  += '<td style="padding: 5px;">';
	html  += '<button type="button" onclick="$(\'#order_service_adds'+method_row+'-'+rand+', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
	html  += '</td></tr>';
	
	
	$('#order_service_adds'+method_row+' .tbody2_class').append(html);
	
}


function addRegionBlock(filter2_row)
{
	var rand = Math.floor((Math.random() * 100000000) + 1);
	var html = '<tr id="filter_regions'+filter2_row+'-'+rand+'">';
	html += '<td style="padding: 5px;">';
					
	html += '<select name="russianpost2_order_filters['+filter2_row+'][filter_regions]['+rand+'][ems_code]" class="form-control" style="width: 200px;">';
					
	html += '<option value=""><?php echo $text_select_region_geozone; ?></option>';

	
	<?php foreach ($geo_zones as $geo_zone) { ?>
	html += '<option value="geozone_<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $text_geozone.' '.$geo_zone['name']; ?></option>';
	<?php } ?>
					

	
	
<?php foreach($current_regions as $region ) { ?>
	html += '<option value="<?php echo $region['ems_code']; ?>"><?php echo str_replace("'", "\'", $region['name']); ?></option>';
<?php } ?>
	html += '</select>';
	html += '</td>';
	html += '<td style="padding: 5px;">';
	html += '<textarea name="russianpost2_order_filters['+filter2_row+'][filter_regions]['+rand+'][cities]" cols="40" rows=3 ';
	html += '></textarea>';
	html += '</td>';
					
	html += '<td style="padding: 5px;">';
	html += '<button type="button" onclick="$(\'#filter_regions'+filter2_row+'-'+rand+', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_del_region; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
	html += '</td>';
	html += '</tr>';
	
	$('#filter_regions'+filter2_row+' .tbody2_class').append(html);
	
}

// ------------------------------------------

function set_tab()
{
	if( $('#link-tab-general').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-general';
	}
	
	if( $('#link-tab-methods').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-methods';
	}
	
	if( $('#link-tab-service').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-service';
	}
	
	if( $('#link-tab-delivery_types').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-delivery_types';
	}
	
	if( $('#link-tab-filters').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-filters';
	}
	
	/* start 112 */
	if( $('#link-tab-packs').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-packs';
	}
	/* end 112 */
	
	
	if( $('#link-tab-adds').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-adds';
	}
	
	if( $('#link-tab-regions').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-regions';
	}
	
	if( $('#link-tab-synx').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-synx';
	}
	
	if( $('#link-tab-cod').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-cod';
	}
	
	if( $('#link-tab-support').attr('aria-expanded') == 'true' )
	{
		document.getElementById('hiddentab').value = 'link-tab-support';
	}
	
}
		
$('#<?php echo $tab; ?>').click();
		
function showTab( key )
{
	$('.service_tabs').hide();
	$("#tab_"+key).show();
	
	$( ".service_links" ).each(function( index ) {
		$(this).removeClass("active");
	});
	
	$("#tablink_"+key).addClass("active");
	
	$('#hiddensubtab').val( key );
}
		
function showApiTab( key )
{
	$('.api_tabs').hide();
	$("#apitab_"+key).show();
	
	$( ".api_links" ).each(function( index ) {
		$(this).removeClass("active");
	});
	
	$("#apilink_"+key).addClass("active");
	
	$('#hiddensubtab2').val( key );
}


/* start 112 */
var custom_pack_row = <?php echo $custom_pack_row; ?>;
function addCustomPack()
{
	html  = '';
	html  += '<tr id="custom_pack-row'+custom_pack_row+'">';
	html  += '<td class="text-left">';
	html  += '<input type="text" class="form-control" ';
	html  += ' name="russianpost2_custom_packs['+custom_pack_row+'][name]" value="" >';
	html  += '</td>';
					
	html  += '<td class="text-left"> ';
	
	
	html  += '<table> ';
	html  += '<tr> ';
	html  += '<td><input type="text" class="form-control"  ';
	html  += ' name="russianpost2_custom_packs['+custom_pack_row+'][length]" style="width: 50px;"></td><td>x</td><td> ';
	html  += '<input type="text" class="form-control"  ';
	html  += ' name="russianpost2_custom_packs['+custom_pack_row+'][width]" style="width: 50px;"></td><td>x</td><td>';
	html  += '<input type="text" class="form-control"  ';
	html  += ' name="russianpost2_custom_packs['+custom_pack_row+'][height]" style="width: 50px;"> </td>';
	html  += '</tr></table> ';
	
	html  += '</td>';
	html  += '<td class="text-left">';
	html  += '<input type="text" class="form-control" name="russianpost2_custom_packs['+custom_pack_row+'][price]" value="" >';
	html  += '</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_custom_packs['+custom_pack_row+'][dopweight]"  >';
	html  += '</td>';
	
	
	html  += '<td class="text-left">';
	html  += '<select class="form-control"  name="russianpost2_custom_packs['+custom_pack_row+'][status]">';
	html  += '<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
	html  += '<option value="0"><?php echo $text_disabled; ?></option>';
	html  += '</select>';
	html  += '</td>';
	html  += '<td class="text-left"><button type="button" onclick="$(\'#custom_pack-row'+custom_pack_row+', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" ';
	html  += 'class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html  += '</tr>';
	
	$('#custom_packs .tbody_class').append(html);
	
	custom_pack_row++;
}		
/* end 112 */


var filter_row = <?php echo $filter_row; ?>;

function addFilter() 
{
	html  = '';
	html  += '<tr id="filter-row' + filter_row + '">';
	html  += '<td><input type="hidden" class="form-control" name="russianpost2_product_filters[' + filter_row + '][filter_id]"  value=""><input type="hidden" class="form-control" name="russianpost2_product_filters[' + filter_row + '][type]"  value="product"><input type="text" class="form-control" name="russianpost2_product_filters['+filter_row+'][filtername]"  value=""></td>';
	html  += '<td>';
	html  += '<input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category'+filter_row+'" class="form-control filter_category" />';
	html  += '<div id="filter-category'+filter_row+'" class="well well-sm" style="height: 150px; overflow: auto;">';
	html  += '</div>';
	html  += '</td>';
	html  += '<td>';
	html  += '<input type="text" name="manufacturer" value="" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer'+filter_row+'" class="form-control filter_manufacturer" />';
	html  += '<div id="filter-manufacturer'+filter_row+'" class="well well-sm" style="height: 150px; overflow: auto;">';
	html  += '</div>';
	html  += '</td>';
	
	html  += '<td>';
	
	html  += '<b><?php echo $text_productname_header; ?></b><br>';
	
	html  += '<select name="russianpost2_product_filters[' + filter_row + '][filter_productname_searchtype]" class="form-control">';
	html  += '<option value="sub"><?php echo $text_search_sub; ?></option>';
	html  += '<option value="sub_noright"><?php echo $text_search_sub_noright; ?></option>';
	html  += '<option value="sub_noleft"><?php echo $text_search_sub_noleft; ?></option>';
	html  += '<option value="strict"><?php echo $text_search_strict; ?></option>';
	html  += '</select>';
	html  += '<br>';
	html  += '<input type="text" class="form-control" name="russianpost2_product_filters[' + filter_row + '][filter_productname]" value="">';
	
	html  += '<br>';
	
	html  += '<b><?php echo $text_productmodel_header; ?></b><br>';
	html  += '<select name="russianpost2_product_filters[' + filter_row + '][filter_productmodel_searchtype]" class="form-control">';
	html  += '<option value="sub"><?php echo $text_search_sub; ?></option>';
	html  += '<option value="sub_noright"><?php echo $text_search_sub_noright; ?></option>';
	html  += '<option value="sub_noleft"><?php echo $text_search_sub_noleft; ?></option>';
	html  += '<option value="strict"><?php echo $text_search_strict; ?></option>';
	html  += '</select>';
	html  += '<br>';
	html  += '<input type="text" class="form-control" name="russianpost2_product_filters[' + filter_row + '][filter_productmodel]" value="">';
	
	html  += '</td>';
	
	
	html  += '<td>';
	html  += '<b><?php echo $text_product_price; ?></b><br>';
	html  += '<table>';
	html  += '<tr>';
	html  += '<td>';
	html  += '<select name="russianpost2_product_filters[' + filter_row + '][price_type]" class="form-control">';
	html  += '<option value="more"><?php echo $text_more; ?></option>';
							
	html  += '<option value="less"><?php echo $text_less; ?></option>';
							
	html  += '</select>';
	html  += '</td>';
	html  += '<td>&nbsp;</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_product_filters[' + filter_row + '][price]" value="">';
	html  += '</td>';
	html  += '</tr>';
	html  += '</table>';
						
	html  += '<br>';
	html  += '<b><?php echo $text_product_weight; ?></b><br>';
						
	html  += '<table>';
	html  += '<tr>';
	html  += '<td>';
	html  += '<select name="russianpost2_product_filters[' + filter_row + '][weight_type]" class="form-control">';
	html  += '<option value="more"><?php echo $text_more; ?></option>';
							
	html  += '<option value="less"><?php echo $text_less; ?></option>';
							
	html  += '</select>';
	html  += '</td>';
	html  += '<td>&nbsp;</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_product_filters[' + filter_row + '][weight]" value="">';
	html  += '</td>';
	html  += '</tr>';
	html  += '</table>';
						
	html  += '<br>';
	html  += '<b><?php echo $text_product_sizes; ?></b><br>';
	html  += '<table>';
	html  += '<tr>';
	html  += '<td>';
						
	html  += '<select name="russianpost2_product_filters[' + filter_row + '][length_type]" class="form-control">';
	html  += '<option value="more"><?php echo $text_more; ?></option>';
							
	html  += '<option value="less"><?php echo $text_less; ?></option>';
							
	html  += '</select>';
	html  += '</td>';
	html  += '<td>&nbsp;</td>';
	html  += '<td>';
						
	html  += '<input type="text" class="form-control" name="russianpost2_product_filters[' + filter_row + '][length]" value="" style="width: 50px;">';
	html  += '</td>';
	html  += '<td>x</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_product_filters[' + filter_row + '][width]" value="" style="width: 50px;">';
	html  += '</td>';
	html  += '<td>x</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_product_filters[' + filter_row + '][height]" value="" style="width: 50px;">';
	html  += '</td>';
	html  += '</tr>';
	html  += '</table>';
	
	
	html  += '<br>';
	html  += '<b><?php echo $text_count_products; ?></b><br>';
						
	html  += '<table class="noborder">';
	html  += '<tr>';
	html  += '<td>';
	html  += '<select name="russianpost2_product_filters[' + filter_row + '][count_products_type]" class="form-control">';
	html  += '<option value="more" ><?php echo $text_more; ?></option>';
	html  += '<option value="less" ><?php echo $text_less; ?></option>';
							
	html  += '</select>';
	html  += '</td>';
	html  += '<td>&nbsp;</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_product_filters[' + filter_row + '][count_products]" value="">';
	html  += '</td>';
	html  += '</tr>';
	html  += '</table>';
	
	
	html  += '</td>';
	
	html  += '<td><b><?php echo $text_status; ?></b><br>';
	html  += '		<select class="form-control"  name="russianpost2_product_filters[' + filter_row + '][status]">';
	html  += ' <option value="1"><?php echo $text_enabled; ?></option>';
	html  += ' <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
	
	html  += '</select><br>';
			
	html  += '<b><?php echo $text_sort_order; ?></b><br>';
						
	html  += '<input type="text" class="form-control" name="russianpost2_product_filters[' + filter_row + '][sort_order]" value=""></td>';
		
	html  += '<td><button type="button" onclick="$(\'#filter-row' + filter_row + ', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html  += '</tr>';

	$('#product_filters .tbody_class').append(html);

	filterautocomplete(filter_row+' ');

	filter_row++;
}


var filter2_row = <?php echo $filter2_row; ?>;

function addFilter2() 
{
	<?php /* start 0711 */ ?>
	filter2_row++;
	cg_row[filter2_row] = 1;
	<?php /* end 0711 */ ?>
	html = '';
	html  += '<tr id="filter2-row'+filter2_row+'">';
	html  += '<td>';
	html  += '<input type="hidden" class="form-control" name="russianpost2_order_filters['+filter2_row+'][filter_id]">';
						
						
	html  += '<input type="hidden" class="form-control" name="russianpost2_order_filters['+filter2_row+'][type]"  value="order">';
						
						
	html  += '<input type="text" class="form-control" name="russianpost2_order_filters['+filter2_row+'][filtername]" ></td>';
	html  += '</td>';
	html  += '<td>';
	
	
	html  += '<select name="russianpost2_order_filters[<?php echo $filter2_row; ?>][productfilter]" class="form-control" >';
	html  += '<option value=""><?php echo $text_select_filter; ?></option>';
						<?php if( !empty($russianpost2_product_filters) ) { 
						foreach($russianpost2_product_filters as $ft) { ?>
						
	html  += '<option value="<?php echo $ft['filter_id']; ?>"><?php echo $ft['filtername']; ?></option>';
						
						<?php } } ?>
	html  += '</select>';
	html  += '<br>';
	html  += '<select name="russianpost2_order_filters[<?php echo $filter2_row; ?>][productfilter_type]" style="width: 170px;" class="form-control" >';
	html  += '<option value="all"><?php echo $text_all_product; ?></option>';
	html  += '<option value="one"><?php echo $text_one_product; ?></option>';
	html  += '<option value="except"><?php echo $text_except_product; ?></option>';
	html  += '</select>';
	
	<?php /* start 0711 */ ?>
	
	html  += '<br>';
	html  += '<div><b><?php echo $text_customer_group; ?></b></div>';
	html  += '<table border=0 width=100% id="cgtab'+filter2_row+'">';
	html  += '<tbody>			';
	html  += '</tbody>';
	html  += '<tfoot>';
	html  += '<tr>';
	html  += '<td colspan=3 style="padding: 5px;"><button type="button" ';
	html  += 'onclick="addCustomerGroupBlock(\''+filter2_row+'\');" data-toggle="tooltip" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
	html  += '</tr>';
	html  += '</tfoot>';
	html  += '</table> ';
	<?php /* end 0711 */ ?>
	
	html  += '</td>';
	html  += '<td>';
	
	<?php /* start metka-707 */ ?>
	html += '<?php echo $text_filter_regions_type; ?>';
						
	html += '<select name="russianpost2_order_filters['+filter2_row+'][filter_regions_type]" ';
	html += ' class="form-control">';
	html += '<option value="include_only"><?php echo $text_filter_regions_type_include_only; ?></option>';
	html += '<option value="exclude"><?php echo $text_filter_regions_type_exclude; ?></option>';
						
	html += '</select><br><br>';
	
	<?php /* end metka-707 */ ?>		
	html  += '<table border=0 width=100% id="filter_regions'+filter2_row+'"  class="table table-striped table-bordered table-hover">';
	html  += '<thead>';
	html  += '<tr>';
	html  += '<td class="text-left"><?php echo $col_text_region_geozone; ?></td>';
	html  += '<td class="text-left"><?php echo $col_text_city; ?></td>';
	html  += '<td class="text-left"></td>';
	html  += '</tr>';
	html  += '</thead>';
	html  += '<tbody class="tbody2_class">';
	html  += '</tbody>';
	html  += '<tfoot>';
	html  += '<tr>';
	html  += '<td colspan=3 style="padding: 5px;"><button type="button" onclick="addRegionBlock(\''+filter2_row+'\');" data-toggle="tooltip" title="<?php echo $button_add_region; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
	html  += '</tr>';
	html  += '</tfoot>';
	html  += '</table>';
	
	
	
	html  += '</td>';
	html  += '<td> ';
	html  += '<b><?php echo $text_order_price; ?></b><br>';
	html  += '<table>';
	html  += '<tr>';
	html  += '<td>';
	html  += '<select name="russianpost2_order_filters['+filter2_row+'][price_type]" class="form-control">';
	html  += '<option value="more"><?php echo $text_more; ?></option>';
							
	html  += '<option value="less"><?php echo $text_less; ?></option>';
							
	html  += '</select>';
	html  += '</td>';
	html  += '<td>&nbsp;</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_order_filters['+filter2_row+'][price]" value="">';
	html  += '</td>';
	html  += '</tr>';
	html  += '</table>';
						
	html  += '<br>';
	html  += '<b><?php echo $text_order_weight; ?></b><br>';
						
	html  += '<table>';
	html  += '<tr>';
	html  += '<td>';
	html  += '<select name="russianpost2_order_filters['+filter2_row+'][weight_type]" class="form-control">';
	html  += '<option value="more"><?php echo $text_more; ?></option>';
							
	html  += '<option value="less"><?php echo $text_less; ?></option>';
							
	html  += '</select>';
	html  += '</td>';
	html  += '<td>&nbsp;</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_order_filters['+filter2_row+'][weight]" value="">';
	html  += '</td>';
	html  += '</tr>';
	html  += '</table>';
						
	html  += '<br>';
	html  += '<b><?php echo $text_order_sizes; ?></b><br>';
	html  += '<table>';
	html  += '<tr>';
	html  += '<td>';
						
	html  += '<select name="russianpost2_order_filters['+filter2_row+'][length_type]" class="form-control">';
	html  += '<option value="more"><?php echo $text_more; ?></option>';
							
	html  += '<option value="less"><?php echo $text_less; ?></option>';
							
	html  += '</select>';
	html  += '</td>';
	html  += '<td>&nbsp;</td>';
	html  += '<td>';
						
	html  += '<input type="text" class="form-control" name="russianpost2_order_filters['+filter2_row+'][length]" value="" style="width: 50px;">';
	html  += '</td>';
	html  += '<td>x</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_order_filters['+filter2_row+'][width]" value="" style="width: 50px;">';
	html  += '</td>';
	html  += '<td>x</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_order_filters['+filter2_row+'][height]" value="" style="width: 50px;">';
	html  += '</td>';
	html  += '</tr>';
	html  += '</table>';
	html  += '</td>';
					
					
	html  += '<td>';
					
	html  += '<b><?php echo $text_status; ?></b><br>';
	html  += '<select class="form-control"  name="russianpost2_order_filters['+filter2_row+'][status]">';
	html  += '<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
	html  += '<option value="0"><?php echo $text_disabled; ?></option>';
	html  += '</select><br>';
			
	html  += '<b><?php echo $text_sort_order; ?></b><br>';
	html  += '<input type="text" class="form-control" name="russianpost2_order_filters['+filter2_row+'][sort_order]" value="">';
					
					
	html  += '</td>';
	html  += '<td><button type="button" onclick="$(\'#filter2-row'+filter2_row+', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html  += '</tr>';
	
	
	$('#order_filters .tbody_class').append(html);
}

function filterautocomplete(filter_row)
{
	filter_row = filter_row.toString();
	var res = filter_row.replace(" ", "");
	// Category
	$('#input-category'+filter_row).autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
	
			$('#filter-category'+res+'-' + item['value']).remove();

			$('#filter-category'+filter_row).append('<div id="filter-category' + res + '-' + item['value'] + '"><i class="fa fa-minus-circle" onclick="$(this).parent().remove();"></i> ' + item['label'] + '<input type="hidden" name="russianpost2_product_filters['+res+'][filter_category][]" value="' + item['value'] + '" /></div>');
		}
	});
	
	// manufacturer
	$('#input-manufacturer'+filter_row).autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'manufacturer\']').val('');

			$('#filter-manufacturer'+filter_row+'-' + item['value']).remove();

			$('#filter-manufacturer'+filter_row).append('<div id="filter-manufacturer' + filter_row + '-' + item['value'] + '"><i class="fa fa-minus-circle" onclick="$(this).parent().remove();"></i> ' + item['label'] + '<input type="hidden" name="russianpost2_product_filters['+filter_row+'][filter_manufacturer][]" value="' + item['value'] + '" /></div>');
		}
	});
	
	
	
	
	
}

<?php if( !empty($russianpost2_product_filters) ) {  foreach($russianpost2_product_filters as $i=>$filter) { ?>
filterautocomplete(<?php echo $i.' '; ?>);
<?php } } ?>

var adds_row = <?php echo $adds_row; ?>;

function addAdds()
{
	html = '';
	html  += '<tr id="adds-row'+adds_row+'">';
	html  += '<td>';
	
	html  += '<input type="hidden" class="form-control" name="russianpost2_product_adds['+adds_row+'][type]" value="product">';
	
	html  += '<table border=0 width=100% id="product_adds'+adds_row+'"  class="table table-striped table-bordered table-hover">';
	html  += '<tbody class="tbody2_class">';
	html  += '</tbody>';
	html  += '<tfoot>';
	html  += '<tr>';
	html  += '<td colspan=2 style="padding: 5px;"><button type="button" onclick="addFilterBlock(\''+adds_row+'\');" data-toggle="tooltip" title="<?php echo $button_add_filter; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
	html  += '</tr>';
	html  += '</tfoot>';
	html  += '</table>';
	
	
	html  += '</td>';
	html  += '<td>';
					
	html  += '<input type="text" class="form-control" name="russianpost2_product_adds['+adds_row+'][weight]">';
					
	html  += '</td>';
	html  += '<td><table>';
	html  += '<tr>';
	html  += '<td>';
						
	html  += '<input type="text" class="form-control" name="russianpost2_product_adds['+adds_row+'][length]" style="width: 50px;">';
	html  += '</td>';
	html  += '<td>x</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_product_adds['+adds_row+'][width]" style="width: 50px;">';
	html  += '</td>';
	html  += '<td>x</td>';
	html  += '<td>';
	html  += '<input type="text" class="form-control" name="russianpost2_product_adds['+adds_row+'][height]" style="width: 50px;">';
	html  += '</td>';
	html  += '</tr>';
	html  += '</table>';
	html  += '</td>';
	html  += '<td>';
	html  += '<select class="form-control"  name="russianpost2_product_adds['+adds_row+'][caution]">';
	html  += '<option value="1" ><?php echo $text_enabled; ?></option>';
	html  += '<option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
	html  += '</select>';
	html  += '</td>';
	html  += '<td>';
	
	html  += '<input type="text" class="form-control" name="russianpost2_product_adds['+adds_row+'][sort_order]">';
					
					
	html  += '</td>';
	html  += '<td>';
					
	html  += '<select class="form-control"  name="russianpost2_product_adds['+adds_row+'][status]">';
	html  += '<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
	html  += '<option value="0"><?php echo $text_disabled; ?></option>';
	html  += '</select>';
	
	html  += '</td>';
	html  += '<td><button type="button" onclick="$(\'#adds-row'+adds_row+', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html  += '</tr>';
	
	$('#product_adds .tbody_class').append(html);
	adds_row++;
}

// ------------------------------------------

function addFilterBlock(adds_row)
{
	var rand = Math.floor((Math.random() * 100000000) + 1);
	var html = '';
	
	html += '<tr id="product_adds'+adds_row+'-'+rand+'">';
	html += '<td style="padding: 5px;">';
	html += '<select name="russianpost2_product_adds['+adds_row+'][filters][]" class="form-control"><option value=""><?php echo $text_select_filter; ?></option>';

<?php foreach($russianpost2_product_filters as $ft) { ?>
	html += '<option value="<?php echo $ft['filter_id']; ?>"><?php echo str_replace("'", "\'", $ft['filtername']); ?></option>';
<?php } ?>
	html += '</select>';
	html += '</td>';
	html += '<td style="padding: 5px;">';
	html += '<button type="button" onclick="$(\'#product_adds'+adds_row+'-'+rand+', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
	html += '</td>';
	html += '</tr>';
	
	$('#product_adds'+adds_row+' .tbody2_class').append(html);
}


function addFilterBlock2(adds2_row)
{
	var rand = Math.floor((Math.random() * 100000000) + 1);
	var html = '';
	
	html += '<tr id="order_adds'+adds2_row+'-'+rand+'">';
	html += '<td style="padding: 5px;">';
		
	html += '<select name="russianpost2_order_adds['+adds2_row+'][filters][]" class="form-control"><option value=""><?php echo $text_select_filter; ?></option>';
	<?php foreach($russianpost2_order_filters as $ft) { ?>
	html += '<option value="<?php echo $ft['filter_id']; ?>"><?php echo $ft['filtername']; ?></option>';
	<?php } ?>
	html += '</select>';
	html += '</td>';
	html += '<td style="padding: 5px;">';
	html += '<button type="button" onclick="$(\'#order_adds'+adds2_row+'-'+rand+', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_del_service; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
	html += '</td>';
	html += '</tr>';
	
	
	$('#order_adds'+adds2_row+' .tbody2_class').append(html);
}
					

var adds2_row = <?php echo $adds2_row; ?>;

function addAdds2()
{
	html = '';
					
	html += '<tr id="adds2-row'+adds2_row+'">';
	html += '<td>';
	html += '<input type="hidden" class="form-control" name="russianpost2_order_adds['+adds2_row+'][adds_id]" value="">';
	html += '<input type="hidden" class="form-control" name="russianpost2_order_adds['+adds2_row+'][type]" value="order">';
					
	html += '<table border=0 width=100% id="order_adds'+adds2_row+'"  class="table table-striped table-bordered table-hover">';
	html += '<tbody class="tbody2_class">';
					
	html += '</tbody>';
	html += '<tfoot>';
	html += '<tr>';
	html += '<td colspan=2 style="padding: 5px;"><button type="button" onclick="addFilterBlock2(\''+adds2_row+'\');" data-toggle="tooltip" title="<?php echo $button_add_filter; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
	html += '</tr>';
	html += '</tfoot>';
	html += '</table>';
				
	html += '</td>';
	
	
	html += '<td>';
			
	html += '<table border=0 width=100% id="order_service_adds'+adds2_row+'"  class="table table-striped table-bordered table-hover">';
	html += '<tbody class="tbody2_class">';
	html += '</tbody>';
	html += '<tfoot>';
	html += '<tr>';
	html += '<td colspan=2 style="padding: 5px;"><button type="button" onclick="addServiceBlock2(\''+adds2_row+'\');" data-toggle="tooltip" title="<?php echo $button_add_service; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
	html += '</tr>';
	html += '</tfoot>';
	html += '</table>';
		
	html += '</td>';
	
	
	html += '<td>';
					
	html += '<input type="text" class="form-control" name="russianpost2_order_adds['+adds2_row+'][cost]" value="">';
	<?php /* start 2009 */ ?>
	html += '<select name="russianpost2_order_adds['+adds2_row+'][costtype]" class="form-control" >';
	html += '<option value="fix"><?php echo $text_order_adds_cost_fix; ?></option>';
	<?php /* start 2401 */ ?>
	html += '<option value="fix2products"><?php echo $text_order_adds_cost_fix2products; ?></option>';
	<?php /* end 2401 */ ?>
	html += '<option value="products_perc"><?php echo $text_order_adds_cost_products_perc; ?></option>';
	html += '<option value="delivery_perc"><?php echo $text_order_adds_cost_delivery_perc; ?></option>';
	html += '</select>';
	<?php /* end 2009 */ ?>
	
	html += '</td>';
	html += '<td>';
					
	html += '<input type="text" class="form-control" name="russianpost2_order_adds['+adds2_row+'][weight]" value="">';
						
	html += '</td>';
	html += '<td><table>';
	html += '<tr>';
	html += '<td>';
						
	html += '<input type="text" class="form-control" name="russianpost2_order_adds['+adds2_row+'][length]" value="" style="width: 50px;">';
	html += '</td>';
	html += '<td>x</td>';
	html += '<td>';
	html += '<input type="text" class="form-control" name="russianpost2_order_adds['+adds2_row+'][width]" value="" style="width: 50px;">';
	html += '</td>';
	html += '<td>x</td>';
	html += '<td>';
	html += '<input type="text" class="form-control" name="russianpost2_order_adds['+adds2_row+'][height]" value="" style="width: 50px;">';
	html += '</td>';
	html += '</tr>';
	html += '</table>';
	html += '</td>';
	
	
	html += '<td>';
	html += '<input type="text" class="form-control" name="russianpost2_order_adds['+adds2_row+'][srok]" value="">';
	
	html += '</td>';
	
	
	html += '<td>';
	html += '<input type="text" class="form-control" name="russianpost2_order_adds['+adds2_row+'][sort_order]" value="">';
					
					
	html += '</td>';
	html += '<td>';
	html += '<select class="form-control"  name="russianpost2_order_adds['+adds2_row+'][status]">';
	html += '<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
	html += '<option value="0"><?php echo $text_disabled; ?></option>';
						  
	html += '</select>';
	html += '</td>';
	html += '<td><button type="button" onclick="$(\'#adds2-row'+adds2_row+', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
				
	$('#order_adds .tbody_class').append(html);
	adds2_row++;
}

/* start 2801 */
var customs_row = <?php echo $customs_row; ?>;

function addCustoms()
{
	html = '';
	html += '<tr id="customs-row'+customs_row+'">';
	html += '<td>';
	html += '<input type="hidden" class="form-control" name="russianpost2_customs['+customs_row+'][custom_id]">';
	html += '<input type="text" class="form-control" name="russianpost2_customs['+customs_row+'][name]">';
	html += '</td>';
				
	html += '<td>';
	
	html += '<div class="form-group">';
	html += '<div class="col-sm-9">';
	html += '<input type="text" class="form-control" ';
	html += ' name="russianpost2_customs['+customs_row+'][price]" ';
	html += ' value="">';
	html += ' </div>';
	html += ' <div class="col-sm-3"> ';
	html += ' <select name="russianpost2_customs['+customs_row+'][currency]" class="form-control">';
									<?php foreach( $currencies as $currency ) { ?>
	html += ' <option value="<?php echo $currency['code']; ?>"';
	html += ' ><?php echo $currency['code']; ?></option>';
									<?php } ?>
	html += ' </select>';
	html += ' </div>';
	html += ' </div>';
							
	html += '</td>';
	html += '<td>';
	html += '<select class="form-control"  name="russianpost2_customs['+customs_row+'][type]">';
	html += '<option value="single" ><?php echo $text_customs_type_single; ?></option>';
	html += '<option value="bycount"><?php echo $text_customs_type_bycount; ?></option>';
	html += '</select>';
	html += '</td>';
	html += '<td>';
	html += '<select class="form-control"  name="russianpost2_customs['+customs_row+'][status]">';
	html += '<option value="1" selected="selected" ';
	html += '><?php echo $text_enabled; ?></option>';
	html += '<option value="0"><?php echo $text_disabled; ?></option>';
	html += '</select>';
	html += '</td>';
	html += '<td><button type="button" onclick="$(\'#customs-row'+customs_row+', .tooltip\').remove();" data-toggle="tooltip" ';
	html += ' title="<?php echo $button_remove; ?>" ';
	html += ' class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	customs_row++;
	$('#customs .tbody_class').append(html);
	
}
/* end 2801 */
<?php /* start metka-407 */ ?>
var adds3_row = <?php echo $adds3_row; ?>;

function addAdds3()
{
	html = '';

	html += ' <tr id="adds3-row'+adds3_row+'">';
	html += ' <td>';
	
	html += ' <input type="hidden" class="form-control" ';
	html += ' name="russianpost2_method_adds['+adds3_row+'][type]" ';
	html += ' value="method"> ';
	
	html += ' <input type="hidden" class="form-control" ';
	html += ' name="russianpost2_method_adds['+adds3_row+'][sort_order]" ';
	html += ' value="0"> ';
	
	html += ' <input type="text" class="form-control" ';
	html += ' name="russianpost2_method_adds['+adds3_row+'][name]" ';
	html += ' value="">';
	html += ' </td>';
	html += ' <td>';
	html += ' <input type="text" class="form-control" ';
	html += ' name="russianpost2_method_adds['+adds3_row+'][cost]" ';
	html += ' value="">';
	<?php /* start 2009 */ ?>
	html += '<select name="russianpost2_method_adds['+adds3_row+'][costtype]" class="form-control" >';
	html += '<option value="fix"><?php echo $text_order_adds_cost_fix; ?></option>';
	<?php /* start 2401 */ ?>
	html += '<option value="fix2products"><?php echo $text_order_adds_cost_fix2products; ?></option>';
	<?php /* end 2401 */ ?>
	html += '<option value="products_perc"><?php echo $text_order_adds_cost_products_perc; ?></option>';
	html += '<option value="delivery_perc"><?php echo $text_order_adds_cost_delivery_perc; ?></option>';
	html += '<option value="total_perc"><?php echo $text_order_adds_cost_total_perc; ?></option>';
	html += '<option value="minvalue"><?php echo $text_order_adds_cost_minvalue; ?></option>';
	html += '</select>';
	<?php /* end 2009 */ ?>
	
	html += ' </td>';
	html += ' <td>';
	html += ' <input type="text" class="form-control" ';
	html += ' name="russianpost2_method_adds['+adds3_row+'][srok]" ';
	html += ' value="">';
	html += ' </td>';
	html += ' <td>';
	html += ' <select class="form-control"  ';
	html += ' name="russianpost2_method_adds['+adds3_row+'][status]">';
	html += ' <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
	html += ' <option value="0"><?php echo $text_disabled; ?></option>';
	html += ' </select>';
	html += ' </td>';
	html += ' <td><button type="button" ';
	html += ' onclick="$(\'#adds3-row'+adds3_row+', .tooltip\').remove();" ';
	html += ' data-toggle="tooltip" title="<?php echo $button_remove; ?>" ';
	html += ' class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
					
				
	$('#method_adds .tbody_class').append(html);
	adds3_row++;
}
<?php /* end metka-407 */ ?>
<?php /* start 0110 */ ?>
function addMethodsAddsBlock(ID, NAME, row)
{
	var rand = Math.floor((Math.random() * 100000000) + 1);
	var html = '';
	
	html += '<tr id="'+ID+'-'+rand+'">';
	html += '<td style="padding: 5px;">';
	html += '<select name="'+NAME+'['+row+'][adds][]" class="form-control"><option value=""><?php echo $text_select_adds; ?></option>';

<?php foreach($russianpost2_method_adds as $ad) { ?>
	html += '<option value="<?php echo $ad['adds_id']; 
	?>"><?php echo $ad['name']; ?></option>';
<?php } ?>
	html += '</select>';
	html += '</td>';
	html += '<td style="padding: 5px;">';
	html += '<button type="button" onclick="$(\'#'+ID+'-'+rand+', .tooltip\').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
	html += '</td>';
	html += '</tr>';
	
	$('#'+ID+' .tbody2_class').append(html);
}
<?php /* end 0110 */ ?>
function addOrdersFilterBlock(ID, NAME, row)
{
	var rand = Math.floor((Math.random() * 100000000) + 1);
	var html = '';
	
	html += '<tr id="'+ID+'-'+rand+'">';
	html += '<td style="padding: 5px;">';
	html += '<select name="'+NAME+'['+row+'][filters][]" class="form-control"><option value=""><?php echo $text_select_filter; ?></option>';

<?php foreach($russianpost2_order_filters as $ft) { ?>
	html += '<option value="<?php echo $ft['filter_id']; ?>"><?php echo $ft['filtername']; ?></option>';
<?php } ?>
	html += '</select>';
	html += '</td>';
	html += '<td style="padding: 5px;">';
	html += '<button type="button" onclick="$(\'#'+ID+'-'+rand+', .tooltip\').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
	html += '</td>';
	html += '</tr>';
	
	$('#'+ID+' .tbody2_class').append(html);
}

function setCountryId(value, country_id)
{
	if( value )
	{
		$('#country_field'+country_id).val(value.split("|")[0]);
		$('#country_td'+country_id+' .country_name').html(value.split("|")[1]);
		$('#country_select'+country_id).remove();
	}
	else
	{
		$('#country_field'+country_id).val('');
		$('#country_td'+country_id+' .country_name').html("<b><font color=red><?php echo $text_no_defined; ?></font></b>");
		$('#country_select'+country_id).remove();
	}
}

function setCountry(country_id) {
	
	$('#country_select'+country_id).remove();
	html = '<select onchange="setCountryId(this.value, '+country_id+');" id="country_select'+country_id+'">';
	html += '<option value=""></option>';
	html += '<option value="" onchange="setCountryId(\'\', '+country_id+');"><?php echo $text_no_defined; ?></option>';
	<?php foreach($russianpost2_countries_id_hash as $id=>$row) { ?>
	html += '<option value="<?php echo $id.'|'.$row['country_name']; 
	?>"><?php echo $row['country_name']; 
	?></option>';
	<?php } ?>

	html += '</select>';
	
	$('#country_td'+country_id).append(html);
	
}


function saveTab(tab_id, is_end)
{
	$.ajax({
		url: 'index.php?route='+encodeURIComponent('shipping/russianpost2/saveTab')+'&token=<?php echo $token; ?>'+
		'&tab_id='+tab_id+
		'&is_end='+is_end+'&stay='+$('#stay_field').val()+'&subtab='+$('#hiddensubtab').val()
		+'&tab='+$('#hiddentab').val()
		+'&subtab2='+$('#hiddensubtab2').val(),
        type: 'post',
        data: $('#'+tab_id+' input[type="text"], #'+tab_id+' input[type="textbox"],  #'+tab_id+' input[type="hidden"], #'+tab_id+' input[type="radio"]:checked, #'+tab_id+' input[type="checkbox"]:checked, #'+tab_id+' textarea, #'+tab_id+' select'),
        dataType: 'json',
        success: function(json) {
			console.log('saved '+tab_id);
			if( json['redirect'] && is_end ) {
				
				location = json['redirect'].replace(/&amp;/g, '&');
				
			}
        },
        error: function(xhr, ajaxOptions, thrownError) {
			
			
			console.log("log: "+thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			
        }
    });
}
	
function saveByTabs()
{ 
	$('.success').remove();
	saveTab('tab-general', 0);
	saveTab('tab-methods', 0);
	saveTab('tab-service', 0);
	/* start 2801 */
	saveTab('tab-customs', 0);
	saveTab('tab-customsrok', 0);
	/* end 2801 */
	saveTab('tab-delivery_types', 0);
	saveTab('tab-packs', 0);
	saveTab('tab-filters', 0);
	saveTab('tab-adds', 0);
	saveTab('tab-regions', 0);
	saveTab('tab-synx');
	saveTab('tab-cod', 1);
}


/* start 0310 */
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=shipping/russianpost2/getVersionStatus&token=<?php echo $token; ?>',
		dataType: 'html',	
		success: function(html) {
			
			$('.text_update_status').html(html);
			
		},
		error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});	
/* end 0310 */

var sorts_hash = new Array();

<?php foreach ($russianpost2_methods as $i=>$method) { ?>
sorts_hash[<?php echo $i; ?>] = <?php echo isset( $method['sort_orders'] ) ? count($method['sort_orders']) : '0 '; ?>;
<?php } ?>

/* start 2602 */
function addMethodsSort( current_method_row )
{
	var html = '';
	key = current_method_row+'-'+sorts_hash[current_method_row];
	 
	html += '<tr id="sort_orders'+key+'">';
	html += '<td style="padding: 5px;">';
	html += '<input type="text" ';
	html += 'name="russianpost2_methods['+current_method_row+'][sort_orders]['+sorts_hash[current_method_row]+'][sort_order]" ';
 	html += 'placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />';
 	 
 	html += '<select ';
 	html += 'name="russianpost2_methods['+current_method_row+'][sort_orders]['+sorts_hash[current_method_row]+'][filter]" ';
 	html += 'class="form-control"> ';
<?php foreach($russianpost2_order_filters as $ft) { ?>
 	html += '<option value="<?php echo $ft['filter_id']; ?>" ';
 	html += '><?php echo $ft['filtername']; ?></option>';
<?php } ?>
 	html += '</select>';
	html += '</td>';
	html += '<td style="padding: 5px;">';
	html += '<button type="button" ';
	html += ' onclick="$(\'#sort_orders'+key+', .tooltip\').remove();" ';
	html += ' data-toggle="tooltip" class="btn btn-danger"><i ';
	html += ' class="fa fa-minus-circle"></i></button>';
	html += ' </td>';
	html += ' </tr>';
	
	$('#sort_orders'+current_method_row+' .tbody2_class').append(html);

	sorts_hash[current_method_row]++;
}
//--></script>

<?php echo $footer; ?> 