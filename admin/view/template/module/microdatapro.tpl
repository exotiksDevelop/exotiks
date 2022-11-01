<?php //microdatapro 7.3 ?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<script>
		$(document).ready(function(){
			$('#form-microdatapro input[type="checkbox"]').wrap('<label class="swl"></label>').after('<span></span>');
		});
	</script>
	<style>
		.swl input[type="checkbox"] {position: absolute;z-index: -1;opacity: 0;margin: 10px 0 0 20px;}
		.swl input[type="checkbox"] + span {position: relative;padding: 0 0 0 60px;cursor: pointer;}
		.swl input[type="checkbox"] + span:before {content: '';position: absolute;top: -4px;left: 0;width: 50px;height: 26px;border-radius: 13px;background: #CDD1DA;box-shadow: inset 0 2px 3px rgba(0,0,0,.2);transition: .2s;}
		.swl input[type="checkbox"] + span:after {content: '';position: absolute;top: -2px;left: 2px;width: 22px;height: 22px;border-radius: 10px;background: #FFF;box-shadow: 0 2px 5px rgba(0,0,0,.3);transition: .2s;}
		.swl input[type="checkbox"]:checked + span:before {background:#9FD468;}
		.swl input[type="checkbox"]:checked + span:after {left: 26px;}
		.swl input[type="checkbox"]:focus + span:before {box-shadow: inset 0 2px 3px rgba(0,0,0,.2), 0 0 0 3px rgba(255,255,0,.7);}
	</style>
	<style>
		#success_up{float:right;}
		#success_up i{font-size:34px;color:#00b32d;margin-top:-8px;animation:great 0.5s infinite;}
		.or:last-child{display:none;}
		#dia h3{margin-top:20px;font-size:19px;}
		#dia h3:hover{cursor:pointer;color:#14628c;}
		.mod_detail{margin-left: 11px;padding-left: 8px;border-left: 2px solid #777;}
		.file_item{margin-bottom:5px;margin-left:5px;}
		.file_item:hover{cursor:pointer;color:#14628c;}
		.active_item{color:#14628c;}
		.last_in_block{padding-bottom:13px;margin-bottom:14px;border-bottom:1px solid #eee;}
		.counter_files{position: absolute;margin: 9px 0 0 -19px;background: #fff;border-radius: 50%;display: inline-block;width: 20px;height: 20px;text-align: center;border: 1px solid #777;line-height: 18px;}
		.service_info{border:1px dashed #14628c;padding:10px;font-size:13px;line-height:20px;margin-top:25px;}
		.service_info h4{font-weight:600;}
		.mbn{margin-bottom:0;}
		#microdatapro_category_syntax .form-group{margin-left:0;margin-right:0;}
		@keyframes great{0%{font-size:34px;margin-top: -8px;}50%{font-size:26px;margin-top: -4px;}100%{font-size:34px;margin-top: -8px;}}
	</style>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-microdatapro" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
				<?php if($success){ ?>
					<span id="success_up"><i class="fa fa-thumbs-up" aria-hidden="true"></i></span>
					<script>
						setTimeout(function(){
							$('#success_up').fadeOut('300');
						}, 2000);
					</script>
				<?php } ?>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-microdatapro" class="form-horizontal">
				<?php if(!$microdatapro_license_key){ ?>
					<div style="font-size:22px;text-align:center;"><?php echo $text_no_active; ?></div>
					<div class="text-center"><iframe width="560" height="315" src="https://www.youtube.com/embed/eN-XwbwTETY" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe></div>
				<?php }else{ ?>
					<div class="form-group">
            <div class="col-sm-12"><h3 style="display:inline-block;line-height:30px;vertical-align:top;margin-right:15px;"><?php echo $text_microdata_status; ?> MicrodataPro:</h3> <input onchange="$('#microdatapro_all').slideToggle(300);" type="checkbox" name="microdatapro_status" <?php if($microdatapro_status) { ?>checked="checked"<?php } ?> value="1" class="form-control" /></div>
          </div>
					<div id="microdatapro_all" <?php if(!$microdatapro_status) { ?>style="display:none;"<?php } ?>>

					<ul class="nav nav-tabs" role="tablist">
						<?php if(!$count_errors){ ?>
				    	<li><a href="#dia" aria-controls="home" role="tab" data-toggle="tab"><?php echo $text_diagnostic; ?></a></li>
						<?php }else{ ?>
							<li class="active"><a href="#dia" aria-controls="home" role="tab" data-toggle="tab"><?php echo $text_diagnostic_e; ?> (<span style="color:#ff0000;"><?php echo $count_errors; ?></span>)</a></li>
						<?php } ?>
				    <li <?php if(!$count_errors){ ?>class="active"<?php } ?>><a href="#company" aria-controls="home" role="tab" data-toggle="tab"><?php echo $text_company; ?></a></li>
				    <li><a href="#product" aria-controls="profile" role="tab" data-toggle="tab"><?php echo $text_product_page; ?></a></li>
				    <li><a href="#social" aria-controls="messages" role="tab" data-toggle="tab"><?php echo $text_social; ?></a></li>
				    <li><a href="#other" aria-controls="settings" role="tab" data-toggle="tab"><?php echo $text_other; ?></a></li>
				    <li><a href="#info" aria-controls="settings" role="tab" data-toggle="tab"><?php echo $text_information; ?></a></li>
				  </ul>
				  <div class="tab-content">
						<div role="tabpanel" class="tab-pane fade <?php if($count_errors){ ?>in active<?php } ?>" id="dia" style="padding-left:15px;">
							<p><h4 style="font-weight:700;"><?php echo $text_for_work; ?></h4></p>
							<div class="mod_status">
								<?php if(!$mod_errors){ ?>
									<h3 class="h3_more_info">1) <i style="color:#00b32d;" class="fa fa-thumbs-o-up" aria-hidden="true"></i> <?php echo $text_succ_mod; ?> <a href="#" class="mod_more_info" title="<?php echo $text_click_view; ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a></h3>
								<?php }else{ ?>
									<h3 class="h3_more_info">1) <i style="color:#ff0000;" class="fa fa-thumbs-o-down" aria-hidden="true"></i> <?php echo $text_err_mod; ?> <span style="color:#ff0000;"><?php echo $mod_errors; ?></span>)</h3>
								<?php } ?>
								<div class="mod_detail" style="display:none;">
									<?php $counter_files = 1; foreach($mod_files as $file => $file_data){ ?>
										<div class="counter_files"><?php echo $counter_files; ?></div>
										<?php if($file_data['status'] == 1){ ?>
											<div class="file_item"><?php echo $text_file_original; ?> <span style="font-weight:600;"><?php echo $file; ?></span> <span style="color:#00b32d;"><?php echo $text_modok; ?></span> <a href="#" class="why_mod"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
												<div style="display:none; padding:10px;border:1px dashed #777;color:#111;">
													 <?php echo $text_in_file; ?> <b><?php echo $file; ?></b><br>
													 <?php echo $text_find_string; ?> <b><?php echo htmlentities($file_data['string']); ?></b><br>
													 <?php echo $text_code_in_file; ?> <b>system/storage/modification/<?php echo $file; ?></b>
												</div>
											</div>
										<?php }else{ //если нет привязки ?>
											<div class="file_item" style="color:#ff0000;">
												<span><?php echo $text_opencart_file; ?> <?php echo $file; ?> <?php echo $text_none_mode; ?></span> <a href="#" class="why_mod"><?php echo $text_why_mod; ?></a>
												<div style="display:none; padding:10px;border:1px dashed #ff0000;color:#111;">
													 <?php echo $text_in_file_strong; ?> <b><?php echo $file; ?></b><br>
													 <p class="mbn"><?php echo $text_start_string; ?>
														 <?php foreach($file_data['string'] as $file_string){ ?>
														 	<b><?php echo htmlentities($file_string); ?></b>  <span class="or"><?php echo $text_or; ?></span>
													 	 <?php } ?>
												 	 </p>
													 <?php $text_support_text; ?>
												</div>
											</div>
										<?php } ?>

										<?php if($file_data['ocmod'] == 1){ //если в модификаторах есть ?>
											<div class="file_item last_in_block"><?php echo $text_module_in_file; ?> <span style="font-weight:600;"><?php echo "system/storage/modification/" . $file; ?></span> <span style="color:#00b32d;"><?php echo $text_installed; ?></span> <a href="#" class="why_mod"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
												<div style="display:none; padding:10px;border:1px dashed #777;color:#111;">
													 <?php echo $text_in_file; ?> <b>system/storage/modification/<?php echo $file; ?></b><br>
													 <?php echo $text_added_code_before; ?> <b><?php echo htmlentities($file_data['string']); ?></b><br>
														<blockquote>
														 <small>//microdatapro <?php echo $mirodatapro_version; ?> start</small>
														 <?php echo $text_module_code; ?><br>
														 <small>//microdatapro <?php echo $mirodatapro_version; ?> end</small>
														</blockquote>
												</div>
											</div>
										<?php }else{ //если нет в модификаторах ?>
											<div class="file_item last_in_block" style="color:#ff0000;">
												<span><?php echo $text_module_in_file; ?> <?php echo "system/storage/modification/" . $file; ?> <?php echo $text_not_installed; ?></span> <a href="#" class="why_mod"><?php echo $text_what_to_do; ?></a>
												<div style="display:none; padding:10px;border:1px dashed #ff0000;color:#111;">
													 <?php echo $text_if_in_orig; ?> <b><?php echo $file; ?></b> <?php echo $text_all_good; ?>
												</div>
											</div>
										<?php } ?>

									<?php $counter_files++; } ?>
									<?php echo $text_for_good_work; ?>
								</div>
							</div>

							<?php if(!$old_microdata){ ?>
								<h3 class="h3_more_info" id="old_h3_title">2) <i style="color:#00b32d;" class="fa fa-thumbs-o-up" aria-hidden="true"></i> <?php echo $text_old_microdata_not_find; ?> <a href="#" class="mod_more_info" title="<?php echo $text_click_view; ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a></h3>
								<div class="mod_detail" style="display:none;">
									<?php echo $text_old_info; ?>
								</div>
							<?php }else{ ?>
								<h3 class="h3_more_info" id="old_h3_title">2) <i style="color:#ff0000;" class="fa fa-thumbs-o-down" aria-hidden="true"></i> <?php echo $text_old_microdata_find; ?> (<span style="color:#ff0000;"><?php echo $old_count; ?></span>) <?php echo $text_recoment_clear; ?> <a href="#" class="mod_more_info" title="<?php echo $text_click_view; ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a></h3>
								<div class="mod_detail" style="display:none;">
									<div class="alert alert-danger" id="old_microdata_block">
									  <strong><?php echo $text_old_microdata; ?></strong>
										<ul style="margin:10px 0;">
											<?php foreach($old_microdata as $file){ ?>
												<li><?php echo $file; ?></li>
											<?php } ?>
										</ul>
										<button id="clear_old" data-loading-text="<?php echo $text_clear; ?> <i class='fa fa-spinner fa-spin'></i>" type="button" class="btn btn-primary btn-sm"><?php echo $text_clear; ?></button>
									</div>
									<?php echo $text_cleared_info; ?>
								</div>
							<?php } ?>

							<?php if($other_modules){ ?>
							<h3 class="h3_more_info">3) <i style="color:#ff0000;" class="fa fa-thumbs-o-down" aria-hidden="true"></i> <?php echo $text_other_modules_find; ?> <a href="#" class="mod_more_info" title="<?php echo $text_click_view; ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a></h3>
							<div class="mod_detail" style="display:none;">
								<?php echo $text_other_modules_info; ?>
							</div>
							<?php }else{ ?>
								<h3 class="h3_more_info">3) <i style="color:#00b32d;" class="fa fa-thumbs-o-up" aria-hidden="true"></i> <?php echo $text_other_modules_not_find; ?> <a href="#" class="mod_more_info" title="<?php echo $text_click_view; ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a></h3>
								<div class="mod_detail" style="display:none;">
									<?php echo $text_not_other_modules_info; ?>
								</div>
							<?php } ?>


							<h3 class="h3_more_info">4) <i style="color:#14628c;" class="fa fa-check-square-o" aria-hidden="true"></i> <?php echo $text_google_check; ?></h3>
							<div class="mod_detail" style="display:none;">
								<?php echo $text_link_check; ?>
								<ul>
									<li><a href="https://search.google.com/structured-data/testing-tool?hl=ru#url=<?php echo $link_main; ?>" target="_blank" title="<?php echo $text_in_new_tab; ?>"><?php echo $text_main_page; ?> <i class="fa fa-external-link" aria-hidden="true"></i></a></li>
									<?php if($link_category){ ?>
										<li><a href="https://search.google.com/structured-data/testing-tool?hl=ru#url=<?php echo $link_category; ?>" target="_blank" title="<?php echo $text_in_new_tab; ?>"><?php echo $text_category_page; ?> <i class="fa fa-external-link" aria-hidden="true"></i></a></li>
									<?php }else{ ?>
										<?php echo $text_empty_category; ?>
									<?php } ?>
									<?php if($link_product){ ?>
										<li><a href="https://search.google.com/structured-data/testing-tool?hl=ru#url=<?php echo $link_product; ?>" target="_blank" title="<?php echo $text_in_new_tab; ?>"><?php echo $text_product_page; ?> <i class="fa fa-external-link" aria-hidden="true"></i></a></li>
									<?php }else{ ?>
										<?php echo $text_empty_product; ?>
									<?php } ?>
									<?php if($link_manufacturer){ ?>
										<li><a href="https://search.google.com/structured-data/testing-tool?hl=ru#url=<?php echo $link_manufacturer; ?>" target="_blank" title="<?php echo $text_in_new_tab; ?>"><?php echo $text_manufacturer_page; ?> <i class="fa fa-external-link" aria-hidden="true"></i></a></li>
									<?php }else{ ?>
										<?php echo $text_empty_brand; ?>
									<?php } ?>
									<?php if($link_information){ ?>
										<li><a href="https://search.google.com/structured-data/testing-tool?hl=ru#url=<?php echo $link_information; ?>" target="_blank" title="<?php echo $text_in_new_tab; ?>"><?php echo $text_info_page; ?> <i class="fa fa-external-link" aria-hidden="true"></i></a></li>
									<?php }else{ ?>
										<?php echo $text_empty_info; ?>
									<?php } ?>
								</ul>
								<?php echo $text_google_validator; ?>
							</div>
							<script>
								$('.refresh').on('click', function(e){
									e.preventDefault();
									$(this).after('<i style="margin-left:5px;color:red;" class="fa fa-spinner fa-spin tmp_spin" aria-hidden="true"></i>');
									$.post('index.php?route=extension/modification/refresh&token=<?php echo $token; ?>').done(function(data) {
											alert("<?php echo $text_mod_cleared; ?>");
											location.reload();
									});
								});
								$('.h3_more_info').on('click', function(e){
									e.preventDefault();
									$(this).toggleClass('active_item');
									$(this).next('.mod_detail').slideToggle('200');
								});
								$('.file_item').on('click', function(e){
									e.preventDefault();
									$(this).toggleClass('active_item');
									$(this).find('div').slideToggle('200');
								});
							</script>
							<div class="service_info">
								<?php echo $text_information_big; ?>
							</div>
						</div>
				    <div role="tabpanel" class="tab-pane fade <?php if(!$count_errors){ ?>in active<?php } ?>" id="company">
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting1" target="_blank" ><?php echo $text_microdata_status; ?></a></label>
		            <div class="col-sm-10">
									<input onchange="$('#microdatapro_store').slideToggle(300);" type="checkbox" name="microdatapro_company" <?php if($microdatapro_company) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
		            </div>
		          </div>
							<div id="microdatapro_store" <?php if(!$microdatapro_company) { ?>style="display:none;"<?php } ?>>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting2" target="_blank" ><?php echo $text_company_syntax; ?></a></label>
								<div class="col-sm-10">
									<select name="microdatapro_company_syntax" class="form-control">
										<option value="all" <?php if($microdatapro_company_syntax == 'all'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_all; ?></option>
										<option value="ld" <?php if($microdatapro_company_syntax == 'ld'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_ld; ?></option>
										<option value="md" <?php if($microdatapro_company_syntax == 'md'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_md; ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_store_type_h; ?>" target="_blank" href="https://microdata.pro/about/settings-7-0#setting3"><?php echo $text_store_type; ?></a></label>
		            <div class="col-sm-10">
									<select name="microdatapro_store_type" class="form-control">
										<option value="" <?php if($microdatapro_store_type == ''){ ?>selected="selected"<?php } ?>><?php echo $text_select; ?></option>
										<?php for($st = 1; $st < 30; $st++){ ?>
											<option value="<?php echo $st; ?>" <?php if($microdatapro_store_type == $st){ ?>selected="selected"<?php } ?>><?php echo ${'text_storetype_' . ($st-1)}; ?></option>
										<?php } ?>
									</select>
		            </div>
		          </div>
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_config_hcard_h; ?>" href="https://microdata.pro/about/settings-7-0#setting4" target="_blank"><?php echo $text_config_hcard; ?></a></label>
		            <div class="col-sm-10">
									<input type="checkbox" name="microdatapro_hcard" <?php if($microdatapro_hcard) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
		            </div>
		          </div>
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting5" target="_blank"><?php echo $text_email; ?></a></label>
		            <div class="col-sm-10">
									<input name="microdatapro_email" placeholder="<?php echo $email; ?>" value="<?php echo $microdatapro_email; ?>" class="form-control">
		            </div>
		          </div>
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting6" target="_blank"><?php echo $text_oh; ?></a></label>
		            <div class="col-sm-10" style="padding-left:0;padding-right:0;">
									<div class="col-sm-1" style="width:14.28%;"><div><?php echo $text_monday; ?></div><div><input class="form-control" type="text" name="microdatapro_oh_1" value="<?php echo $microdatapro_oh_1; ?>" placeholder="10:00-20:00"></div></div>
									<div class="col-sm-1" style="width:14.28%;"><div><?php echo $text_tuesday; ?></div><div><input class="form-control" type="text" name="microdatapro_oh_2" value="<?php echo $microdatapro_oh_2; ?>" placeholder="10:00-20:00"></div></div>
									<div class="col-sm-1" style="width:14.28%;"><div><?php echo $text_wednesday; ?></div><div><input class="form-control" type="text" name="microdatapro_oh_3" value="<?php echo $microdatapro_oh_3; ?>" placeholder="10:00-20:00"></div></div>
									<div class="col-sm-1" style="width:14.28%;"><div><?php echo $text_thursday; ?></div><div><input class="form-control" type="text" name="microdatapro_oh_4" value="<?php echo $microdatapro_oh_4; ?>" placeholder="10:00-20:00"></div></div>
									<div class="col-sm-1" style="width:14.28%;"><div><?php echo $text_friday; ?></div><div><input class="form-control" type="text" name="microdatapro_oh_5" value="<?php echo $microdatapro_oh_5; ?>" placeholder="10:00-20:00"></div></div>
									<div class="col-sm-1" style="width:14.28%;"><div><?php echo $text_saturday; ?></div><div><input class="form-control" type="text" name="microdatapro_oh_6" value="<?php echo $microdatapro_oh_6; ?>" placeholder="10:00-18:00"></div></div>
									<div class="col-sm-1" style="width:14.28%;"><div><?php echo $text_sunday; ?></div><div><input class="form-control" type="text" name="microdatapro_oh_7" value="<?php echo $microdatapro_oh_7; ?>" placeholder="10:00-18:00"></div></div>
								</div>
		          </div>
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_entry_telephone_h; ?>" href="https://microdata.pro/about/settings-7-0#setting7" target="_blank"><?php echo $text_entry_telephone; ?></a></label>
		            <div class="col-sm-10">
									<textarea name="microdatapro_phones" placeholder="+12-345-678-90-00,+34-555-678-11-11" class="form-control"><?php echo $microdatapro_phones; ?></textarea>
									<?php if($stores){ ?>
										<?php foreach($stores as $store){ ?>
											<span class="placeholder_name"><?php echo $store['name']; ?></span>
											<textarea name="microdatapro_phones<?php echo $store['store_id']; ?>" placeholder="+12-345-678-90-00,+34-555-678-11-11" class="form-control"><?php echo $store['microdatapro_phones']; ?></textarea>
										<?php } ?>
									<?php } ?>
		            </div>
		          </div>
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_entry_group_h; ?>" href="https://microdata.pro/about/settings-7-0#setting8" target="_blank"><?php echo $text_entry_group; ?></a></label>
		            <div class="col-sm-10">
									<textarea name="microdatapro_groups" placeholder="https://facebook.com/group, https://twitter.com/group" class="form-control"><?php echo $microdatapro_groups; ?></textarea>
									<?php if($stores){ ?>
										<?php foreach($stores as $store){ ?>
											<span class="placeholder_name"><?php echo $store['name']; ?></span>
											<textarea name="microdatapro_groups<?php echo $store['store_id']; ?>" placeholder="https://facebook.com/group, https://twitter.com/group" class="form-control"><?php echo $store['microdatapro_groups']; ?></textarea>
										<?php } ?>
									<?php } ?>
		            </div>
		          </div>
							<div class="form-group">
						    <label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_entry_address_h; ?>" href="https://microdata.pro/about/settings-7-0#setting9" target="_blank"><?php echo $text_entry_address; ?></a></label>
						    <div class="col-sm-10">
									<textarea name="microdatapro_locations" placeholder="50.501090;30.496714//Москва, Россия//ул. Гагарина 54//012345" class="form-control"><?php echo $microdatapro_locations; ?></textarea>
									<?php if($stores){ ?>
										<?php foreach($stores as $store){ ?>
											<span class="placeholder_name"><?php echo $store['name']; ?></span>
											<textarea name="microdatapro_locations<?php echo $store['store_id']; ?>" placeholder="50.501090;30.496714//Москва, Россия//ул. Гагарина 54//012345" class="form-control"><?php echo $store['microdatapro_locations']; ?></textarea>
										<?php } ?>
									<?php } ?>
						    </div>
						  </div>
							<div class="form-group">
						    <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting10" target="_blank" data-toggle="tooltip" title="<?php echo $text_entry_map_h; ?>"><?php echo $text_entry_map; ?></a></label>
						    <div class="col-sm-10">
									<textarea name="microdatapro_map" placeholder="https://www.google.com/maps/@48.605665,11.6267341,5z?hl=ru" class="form-control"><?php echo $microdatapro_map; ?></textarea>
									<?php if($stores){ ?>
										<?php foreach($stores as $store){ ?>
											<span class="placeholder_name"><?php echo $store['name']; ?></span>
											<textarea name="microdatapro_map<?php echo $store['store_id']; ?>" placeholder="https://www.google.com/maps/@48.605665,11.6267341,5z?hl=ru" class="form-control"><?php echo $store['microdatapro_map']; ?></textarea>
										<?php } ?>
									<?php } ?>
						    </div>
						  </div>
						  </div>
						</div>
				    <div role="tabpanel" class="tab-pane fade" id="product">
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting1" target="_blank"><?php echo $text_microdata_status; ?></a></label>
		            <div class="col-sm-10">
									<input onchange="$('#microdatapro_product_block').slideToggle(300);" type="checkbox" name="microdatapro_product" <?php if($microdatapro_product) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
		            </div>
		          </div>
							<div id="microdatapro_product_block" <?php if(!$microdatapro_product) { ?>style="display:none;"<?php } ?>>
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting2" target="_blank" ><?php echo $text_syntax; ?></a></label>
		            <div class="col-sm-10">
									<select name="microdatapro_product_syntax" class="form-control">
										<option value="all" <?php if($microdatapro_product_syntax == 'all'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_all; ?></option>
										<option value="ld" <?php if($microdatapro_product_syntax == 'ld'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_ld; ?></option>
										<option value="md" <?php if($microdatapro_product_syntax == 'md'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_md; ?></option>
									</select>
								</div>
		          </div>
							<div class="form-group">
							  <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting11" target="_blank" ><?php echo $text_breadcrumb; ?></a></label>
							  <div class="col-sm-10">
							    <input type="checkbox" name="microdatapro_product_breadcrumb" <?php if($microdatapro_product_breadcrumb) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
							  </div>
							</div>
							<div class="form-group">
							  <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting12" target="_blank" ><?php echo $text_hide_price; ?></a></label>
							  <div class="col-sm-10">
							    <input type="checkbox" name="microdatapro_hide_price" <?php if($microdatapro_hide_price) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
							  </div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting13" target="_blank" data-toggle="tooltip" title="<?php echo $text_other_data_h; ?>">sku</a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_sku" <?php if($microdatapro_sku) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting13" target="_blank" data-toggle="tooltip" title="<?php echo $text_other_data_h; ?>">upc</a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_upc" <?php if($microdatapro_upc) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting13" target="_blank" data-toggle="tooltip" title="<?php echo $text_other_data_h; ?>">ean</a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_ean" <?php if($microdatapro_ean) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting13" target="_blank" data-toggle="tooltip" title="<?php echo $text_other_data_h; ?>">mpn</a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_mpn" <?php if($microdatapro_mpn) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting13" target="_blank" data-toggle="tooltip" title="<?php echo $text_other_data_h; ?>">isbn</a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_isbn" <?php if($microdatapro_isbn) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting14" target="_blank"><?php echo $text_reviews; ?></a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_product_reviews" <?php if($microdatapro_product_reviews) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting15" target="_blank"><?php echo $text_related; ?></a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_product_related" <?php if($microdatapro_product_related) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting16" target="_blank"><?php echo $text_attribute; ?></a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_product_attribute" <?php if($microdatapro_product_attribute) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting17" target="_blank"><?php echo $text_gallery; ?></a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_product_gallery" <?php if($microdatapro_product_gallery) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting18" target="_blank" data-toggle="tooltip" title="<?php echo $text_in_stock_h; ?>"><?php echo $text_in_stock; ?></a></label>
								<div class="col-sm-10">
									<input type="checkbox" name="microdatapro_product_in_stock" <?php if($microdatapro_product_in_stock) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_in_stock_status_h; ?>" href="https://microdata.pro/about/settings-7-0#setting19" target="_blank" ><?php echo $text_in_stock_status; ?></a></label>
								<div class="col-sm-10">
									<select name="microdatapro_in_stock_status_id" class="form-control">
										<option value="0" <?php if(!$stock_status_id){ ?>selected="selected"<?php } ?>>---</option>
							      <?php foreach ($stock_statuses as $stock_status) { ?>
							      <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
							      <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
							      <?php } else { ?>
							      <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
							      <?php } ?>
							      <?php } ?>
									</select>
								</div>
							</div>
							</div>
						</div>
				    <div role="tabpanel" class="tab-pane fade" id="social">
							<div class="form-group">
								<label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_opengraph_h; ?>" href="https://microdata.pro/about/settings-7-0#setting20" target="_blank"><?php echo $text_opengraph; ?></a></label>
								<div class="col-sm-10">
									<input onchange="$('#microdatapro_opengraph').slideToggle(300);" type="checkbox" name="microdatapro_opengraph" <?php if($microdatapro_opengraph) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
								</div>
							</div>
							<div id="microdatapro_opengraph" <?php if(!$microdatapro_opengraph) { ?>style="display:none;"<?php } ?>>
								<div class="form-group">
									<label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_meta_desc_h; ?>" href="https://microdata.pro/about/settings-7-0#setting21" target="_blank"><?php echo $text_meta_desc; ?></a></label>
									<div class="col-sm-10">
										<input type="checkbox" name="microdatapro_opengraph_meta" <?php if($microdatapro_opengraph_meta) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting22" data-toggle="tooltip" title="<?php echo $text_profile_id_desc; ?>" target="_blank"><?php echo $text_profile_id; ?></a></label>
									<div class="col-sm-10">
										<input type="text" name="microdatapro_profile_id" value="<?php echo $microdatapro_profile_id; ?>" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting23" data-toggle="tooltip" title="<?php echo $text_age_group_desc; ?>" target="_blank"><?php echo $text_age_group; ?></a></label>
									<div class="col-sm-10">
										<select name="microdatapro_age_group" class="form-control">
											<option <?php if($microdatapro_age_group == "") { ?>selected="selected"<?php } ?> value=""><?php echo $text_age_default; ?></option>
											<option <?php if($microdatapro_age_group == "kids") { ?>selected="selected"<?php } ?> value="kids"><?php echo $text_age_children; ?></option>
											<option <?php if($microdatapro_age_group == "adult") { ?>selected="selected"<?php } ?> value="adult"><?php echo $text_age_adult; ?></option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting24" data-toggle="tooltip" title="<?php echo $text_target_gender_desc; ?>" target="_blank"><?php echo $text_target_gender; ?></a></label>
									<div class="col-sm-10">
										<select name="microdatapro_target_gender" class="form-control">
											<option <?php if($microdatapro_target_gender == "") { ?>selected="selected"<?php } ?> value=""><?php echo $text_select; ?></option>
											<option <?php if($microdatapro_target_gender == "female") { ?>selected="selected"<?php } ?> value="female"><?php echo $text_target_gender_female; ?></option>
											<option <?php if($microdatapro_target_gender == "male") { ?>selected="selected"<?php } ?> value="male"><?php echo $text_target_gender_male; ?></option>
											<option <?php if($microdatapro_target_gender == "unisex") { ?>selected="selected"<?php } ?> value="unisex"><?php echo $text_target_gender_unisex; ?></option>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting25" target="_blank"><?php echo $text_attr_color; ?></a></label>
									<div class="col-sm-10">
										<select name="microdatapro_attr_color" class="form-control">
											<option <?php if($microdatapro_attr_color == "") { ?>selected="selected"<?php } ?> value=""><?php echo $text_select; ?></option>
											<?php foreach($all_attributes as $attribute){ ?>
												<option <?php if($microdatapro_attr_color == $attribute['attribute_id']) { ?>selected="selected"<?php } ?> value="<?php echo $attribute['attribute_id']; ?>"><?php echo $attribute['attribute_group'] . '=>' . $attribute['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting25" target="_blank"><?php echo $text_attr_material; ?></a></label>
									<div class="col-sm-10">
										<select name="microdatapro_attr_material" class="form-control">
											<option <?php if($microdatapro_attr_material == "") { ?>selected="selected"<?php } ?> value=""><?php echo $text_select; ?></option>
											<?php foreach($all_attributes as $attribute){ ?>
												<option <?php if($microdatapro_attr_material == $attribute['attribute_id']) { ?>selected="selected"<?php } ?> value="<?php echo $attribute['attribute_id']; ?>"><?php echo $attribute['attribute_group'] . '=>' . $attribute['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting25" target="_blank"><?php echo $text_attr_size; ?></a></label>
									<div class="col-sm-10">
										<select name="microdatapro_attr_size" class="form-control">
											<option <?php if($microdatapro_attr_size == "") { ?>selected="selected"<?php } ?> value=""><?php echo $text_select; ?></option>
											<?php foreach($all_attributes as $attribute){ ?>
												<option <?php if($microdatapro_attr_size == $attribute['attribute_id']) { ?>selected="selected"<?php } ?> value="<?php echo $attribute['attribute_id']; ?>"><?php echo $attribute['attribute_group'] . '=>' . $attribute['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_twitter_account_h; ?>" href="https://microdata.pro/about/settings-7-0#setting26" target="_blank"><?php echo $text_twitter_account; ?></a></label>
								<div class="col-sm-10">
									<input type="text" name="microdatapro_twitter_account" value="<?php echo $microdatapro_twitter_account; ?>" class="form-control" />
								</div>
							</div>
						</div>
				    <div role="tabpanel" class="tab-pane fade" id="other">

							<div class="form-group">
								<div class="form-group" style="margin-left:0;">
									<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting27" target="_blank"><?php echo $text_category_page; ?></a></label>
			            <div class="col-sm-10">
										<input onchange="$('#microdatapro_category_syntax').fadeToggle(300);" type="checkbox" name="microdatapro_category" <?php if($microdatapro_category) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
			            </div>
		            </div>
								<div id="microdatapro_category_syntax" <?php if(!$microdatapro_category) { ?>style="display:none;"<?php } ?>>

									<div class="form-group">
										<label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting2" target="_blank" ><?php echo $text_syntax; ?></a></label>
										<div class="col-sm-10">
											<select name="microdatapro_category_syntax" class="form-control">
												<option value="all" <?php if($microdatapro_category_syntax == 'all'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_all; ?></option>
												<option value="ld" <?php if($microdatapro_category_syntax == 'ld'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_ld; ?></option>
												<option value="md" <?php if($microdatapro_category_syntax == 'md'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_md; ?></option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_product_agregator; ?>" href="https://microdata.pro/about/settings-7-0#setting28" target="_blank"><?php echo $text_price_from_to; ?></a></label>
										<div class="col-sm-10">
											<input type="checkbox" name="microdatapro_category_range" <?php if($microdatapro_category_range) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
				            </div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><a data-toggle="tooltip" title="<?php echo $text_category_rating; ?>" href="https://microdata.pro/about/settings-7-0#setting29" target="_blank"><?php echo $text_all_rating; ?></a></label>
										<div class="col-sm-10">
											<input type="checkbox" name="microdatapro_category_review" <?php if($microdatapro_category_review) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
				            </div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><a data-toggle="tooltip" title="" href="https://microdata.pro/about/settings-7-0#setting30" target="_blank"><?php echo $text_images_to_gallery; ?></a></label>
										<div class="col-sm-10">
											<input type="checkbox" name="microdatapro_category_gallery" <?php if($microdatapro_category_gallery) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
				            </div>
									</div>
							  </div>
		          </div>

							<div class="form-group">
		            <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting31" target="_blank"><?php echo $text_manufacturer_page; ?></a></label>
		            <div class="col-sm-2">
									<input onchange="$('#microdatapro_manufacturer_syntax').fadeToggle(300);" type="checkbox" name="microdatapro_manufacturer" <?php if($microdatapro_manufacturer) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
		            </div>
								<div id="microdatapro_manufacturer_syntax" <?php if(!$microdatapro_manufacturer) { ?>style="display:none;"<?php } ?>>
								<label class="col-sm-3 control-label"><a href="https://microdata.pro/about/settings-7-0#setting2" target="_blank" ><?php echo $text_syntax; ?></a></label>
		            <div class="col-sm-5">
									<select name="microdatapro_manufacturer_syntax" class="form-control">
										<option value="all" <?php if($microdatapro_manufacturer_syntax == 'all'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_all; ?></option>
										<option value="ld" <?php if($microdatapro_manufacturer_syntax == 'ld'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_ld; ?></option>
										<option value="md" <?php if($microdatapro_manufacturer_syntax == 'md'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_md; ?></option>
									</select>
								</div>
								</div>
		          </div>
							<div class="form-group">
		            <label class="col-sm-2 control-label"><a href="https://microdata.pro/about/settings-7-0#setting31" target="_blank"><?php echo $text_information_page; ?></a></label>
		            <div class="col-sm-2">
									<input onchange="$('#microdatapro_information_syntax').fadeToggle(300);" type="checkbox" name="microdatapro_information" <?php if($microdatapro_information) { ?>checked="checked"<?php } ?> value="1" class="form-control" />
		            </div>
								<div id="microdatapro_information_syntax" <?php if(!$microdatapro_information) { ?>style="display:none;"<?php } ?>>
								<label class="col-sm-3 control-label"><a href="https://microdata.pro/about/settings-7-0#setting2" target="_blank" ><?php echo $text_syntax; ?></a></label>
		            <div class="col-sm-5">
									<select name="microdatapro_information_syntax" class="form-control">
										<option value="all" <?php if($microdatapro_information_syntax == 'all'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_all; ?></option>
										<option value="ld" <?php if($microdatapro_information_syntax == 'ld'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_ld; ?></option>
										<option value="md" <?php if($microdatapro_information_syntax == 'md'){ ?>selected="selected"<?php } ?>><?php echo $text_company_syntax_md; ?></option>
									</select>
								</div>
								</div>
		          </div>
						</div>
				    <div role="tabpanel" class="tab-pane fade" id="info">
							<div class="form-group">
								<div class="col-sm-12">
									<?php echo $text_thanks_new; ?>
								</div>
							</div>
							<div class="form-group">
							  <div class="col-sm-3"><span data-toggle="tooltip" title="<?php echo $text_faq_h; ?>"><?php echo $text_check_license; ?></span></div>
							  <div class="col-sm-9"><a href="<?php echo $lhref; ?>" target="_blank"><i class="fa fa-key" aria-hidden="true"></i> https://microdata.pro/status/</a></div>
							</div>
							<div class="form-group">
							  <div class="col-sm-3"><span data-toggle="tooltip" title="<?php echo $text_faq_h; ?>"><?php echo $text_faq; ?></span></div>
							  <div class="col-sm-9"><a href="https://microdata.pro/faq/" target="_blank">https://microdata.pro/faq/</a></div>
							</div>
							<div class="form-group">
							  <div class="col-sm-3"><span data-toggle="tooltip" title="<?php echo $text_check_h; ?>"><b><?php echo $text_check; ?></b></span></div>
							  <div class="col-sm-9">
									<a target="_blank" href="https://search.google.com/structured-data/testing-tool/#url=<?php echo $site_url; ?>"><i class="fa fa-google" aria-hidden="true"></i> Google structured data testing tool</a><br>
									<a target="_blank" href="https://webmaster.yandex.ru/tools/microtest/"><?php echo $text_yandex_validator; ?></a><br>
									<a target="_blank" href="https://cards-dev.twitter.com/validator"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter card validator</a><br>
									<a target="_blank" href="https://developers.facebook.com/tools/debug/sharing"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook sharing debug</a>
							  </div>
							</div>
							<div class="form-group">
								<div class="col-sm-3"><span data-toggle="tooltip" title="<?php echo $text_contacts_h; ?>"><?php echo $text_contacts; ?></span></div>
							  <div class="col-sm-9">
									<?php echo $text_email; ?> <a href="mailto:info@microdata.pro">info@microdata.pro</a><br>
									<?php echo $text_site; ?> <a href="https://microdata.pro" target="_blank">https://microdata.pro</a><br>
									Opencartforum <a href="https://opencartforum.com/profile/18336-exploits/" target="_blank">https://opencartforum.com/profile/18336-exploits/</a><br>
									Facebook <a href="https://www.facebook.com/nikolay.prut" target="_blank">https://www.facebook.com/nikolay.prut</a>
							  </div>
							</div>
							<div class="form-group">
								<div class="col-sm-3"><?php echo $text_other_modules; ?></div>
							  <div class="col-sm-9">
									Easyphoto <a href="https://microdata.pro/opencart-dev/easyphoto" target="_blank">https://microdata.pro/opencart-dev/easyphoto</a><br>
									Wishlist+ <a href="https://microdata.pro/wishlist-plus/" target="_blank">https://microdata.pro/wishlist-plus/</a><br>
							  </div>
							</div>

							<div class="form-group">
								<div class="col-sm-3"><?php echo $text_reviews_mod; ?></div>
							  <div class="col-sm-9">
									<?php echo $text_on_site; ?> <a href="https://opencartforum.com/files/file/2859-microdatapro-mikrorazmetka-json-ldmicrodata/?tab=reviews" target="_blank">opencartforum.com</a><br>
									<?php echo $text_on_site; ?> <a href="https://prodelo.biz/moduli-opencart/seo/microdatapro" target="_blank">prodelo.biz</a><br>
									<?php echo $text_on_site; ?> <a href="https://liveopencart.ru/opencart-moduli-shablony/moduli/obmen-dannyimi/mikrorazmetka-application-ld-json-pro-microdata-3-0" target="_blank">liveopencart.ru</a><br>
									<?php echo $text_on_site; ?> <a href="http://shop.opencart-russia.ru/microdata-applicationldjson-pro" target="_blank">shop.opencart-russia.ru</a>
							  </div>
							</div>


						</div>
				  </div>
				<?php } ?>
				<input type="hidden" name="microdatapro_license_key" value="<?php echo $microdatapro_license_key; ?>" >
				<input type="hidden" name="microdatapro_new_version" value="1" >
			  </div>
				</form>
      </div>
    </div>
  </div>
</div>
<script>
	$('#clear_old').click(function(){
		var $this = $(this);
		$this.button('loading');
		setTimeout(function() {
			$.ajax({
				url: 'index.php?route=<?php echo $href_old; ?>/clear_old&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				success: function(succ){
					$('#old_microdata_block').text("<?php echo $text_success_removed; ?> (" + succ + ")");
					setTimeout(function(){$('#old_microdata_block').slideUp('600');}, 5000);
					$.post('index.php?route=extension/modification/refresh&token=<?php echo $token; ?>').done(function(data) {
							$('#old_h3_title').html('2) <i style="color:#00b32d;" class="fa fa-thumbs-o-up" aria-hidden="true"></i> <?php echo $text_old_microdata_deleted; ?>');
					});
				}
			});
    }, 1000);
	});
</script>
<?php echo $footer; ?>
