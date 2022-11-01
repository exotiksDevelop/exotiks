<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"> <!-- div id="content" -->
  <div class="page-header"><!-- div class="page-header" -->
    <div class="container-fluid">
      <div class="pull-right">
	  
        <a href="javascript: $('#stay_field').attr('value', '0'); $('#form').submit();" 
		data-toggle="tooltip" 
		title="<?php echo $button_save_and_go; ?>" 
		class="btn btn-primary"><i class="fa fa-save"></i></a>
		
        <a href="javascript: set_tab(); $('#stay_field').attr('value', '1'); $('#form').submit();" 
		data-toggle="tooltip"  
		title="<?php echo $button_save_and_stay; ?>" 
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

	  <style>
	  .clist 
	  {
		border-top:  1px #ccc solid;
		border-left:  1px #ccc solid;
	  }
	  
	  .clist td
	  {
		padding: 5px;
		border-right: 1px #ccc solid;
		border-bottom: 1px #ccc solid;
	  }
	  
	  .plus
	  {
		background: green;
		text-align: center;
	  }
	  
	  .minus
	  {
		background: #F58C6C;
		text-align: center;
	  }
	  
	  .vopros
	  {
		text-align: center;
	  }
	  </style>
  <div class="container-fluid"><!-- div class="container-fluid" -->
  
    <?php if ( !empty($error_warning) ) { ?>
	
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
	
    <?php } ?>
	
	<?php if( !empty($success) ) { ?>	
    <div class="alert alert-success"><i class="fa fa-info-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
	<?php }  ?>

	
    <div class="panel panel-default"> <!--  class="panel panel-default" -->
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body"> <!--  class="panel-body" -->
	  
	<ul class="nav nav-tabs">
	
            <li class="active" style="cursor: pointer;" 
			><a href="#tab-general" data-toggle="tab" id="link-tab-general" ><?php echo $tab_general; ?></a></li>
            <li style="cursor: pointer;" 
			><a href="#tab-vkontakte" data-toggle="tab" id="link-tab-vkontakte" ><?php echo $tab_vkontakte; ?></a></li>
            <li style="cursor: pointer;" 
			><a href="#tab-facebook" data-toggle="tab" id="link-tab-facebook" ><?php echo $tab_facebook; ?></a></li>
            <li style="cursor: pointer;" 
			><a href="#tab-twitter" data-toggle="tab" id="link-tab-twitter" ><?php echo $tab_twitter; ?></a></li>
            <li style="cursor: pointer;" 
			><a href="#tab-odnoklassniki" data-toggle="tab" id="link-tab-odnoklassniki" ><?php echo $tab_odnoklassniki; ?></a></li>
			
			<?php /* start metka: a1 */ ?>
            <li style="cursor: pointer;" 
			><a href="#tab-gmail" data-toggle="tab" id="link-tab-gmail" ><?php echo $tab_gmail; ?></a></li>
            <li style="cursor: pointer;" 
			><a href="#tab-mailru" data-toggle="tab" id="link-tab-mailru" ><?php echo $tab_mailru; ?></a></li>
			<?php /* end metka: a1 */ ?>
			
            <li style="cursor: pointer;" 
			><a href="#tab-dobor" data-toggle="tab" id="link-tab-dobor" ><?php echo $tab_dobor; ?></a></li>
		
            <li id="link-tab-widget" 
			><a href="#tab-widget" data-toggle="tab"><?php echo $tab_widget; ?></a></li>
            <li id="link-tab-popup" 
			><a href="#tab-popup" data-toggle="tab"><?php echo $tab_popup; ?></a></li>
			
            <li style="cursor: pointer;" 
			><a href="#tab-design" data-toggle="tab" id="link-tab-design" ><?php echo $tab_design; ?></a></li>
            <li style="cursor: pointer;" 
			><a href="#tab-support" data-toggle="tab" id="link-tab-support" ><?php echo $tab_support; ?></a></li>
			
      </ul>

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"
	 class="form-horizontal"
	>
	<input type="hidden" name="stay" id="stay_field" value="1">
		<input type="hidden" id="hiddentab" name="tab" value="<?php echo $tab; ?>">
	  
	  
<div class="tab-content"><!--  class="tab-content" -->
	  
		
      
	  
	  <div id="tab-general" class="tab-pane active">
	  
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_status; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_status" class="form-control" >
                  <?php if ( $socnetauth2_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
			
        </div>
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_version; ?>
			</label>
            <div class="col-sm-10" style="padding-top: 9px;">
				2.17
			</div>
			
        </div>
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_format; ?>
			</label>
            <div class="col-sm-10">
				<style>
				.format_table td
				{
					border-bottom: 1px #ccc solid;
					padding: 3px;
				}
				</style>
				<table class="format_table" cellpadding=0 cellspacing=0>
				<tr>
					<td></td>
					<td><b><?php echo $text_format_kvadrat; ?></b></td>
					<td><b><?php echo $text_format_bline; ?></b></td>
					<td><b><?php echo $text_format_lline; ?></b></td>
				</tr>
				
				<tr>
					<td></td>
					<td valign=top><img src="view/image/socnetauth2/kvadrat.gif" style="width: 250px;"></td>
					<td valign=top><img src="view/image/socnetauth2/bline.gif" style="width: 250px;"></td>
					<td valign=top><img src="view/image/socnetauth2/lline.gif" style="width: 250px;"></td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_account_page; ?></b>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_account_format" 
							value="kvadrat" 
							id="socnetauth2_account_format_kvadrat"
							<?php if( $socnetauth2_account_format=='kvadrat' ) { ?> checked <?php } ?>
							><label for="socnetauth2_account_format_kvadrat"
							><?php echo $text_format_kvadrat; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_account_format" 
							value="bline" 
							id="socnetauth2_account_format_bline"
							<?php if( $socnetauth2_account_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_account_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_account_format" 
							value="lline" 
							id="socnetauth2_account_format_lline"
							<?php if( $socnetauth2_account_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_account_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_checkout_page; ?></b>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_checkout_format" 
							value="kvadrat" 
							id="socnetauth2_checkout_format_kvadrat"
							<?php if( $socnetauth2_checkout_format=='kvadrat' ) { ?> checked <?php } ?>
							><label for="socnetauth2_checkout_format_kvadrat"
							><?php echo $text_format_kvadrat; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_checkout_format" 
							value="bline" 
							id="socnetauth2_checkout_format_bline"
							<?php if( $socnetauth2_checkout_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_checkout_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_checkout_format" 
							value="lline" 
							id="socnetauth2_checkout_format_lline"
							<?php if( $socnetauth2_checkout_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_checkout_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_simple_page; ?></b>
					</td>
					<td>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_simple_format" 
							value="bline" 
							id="socnetauth2_simple_format_bline"
							<?php if( $socnetauth2_simple_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_simple_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_simple_format" 
							value="lline" 
							id="socnetauth2_simple_format_lline"
							<?php if( $socnetauth2_simple_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_simple_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_reg_page; ?></b>
					</td>
					<td>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_reg_format" 
							value="bline" 
							id="socnetauth2_reg_format_bline"
							<?php if( $socnetauth2_reg_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_reg_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_reg_format" 
							value="lline" 
							id="socnetauth2_reg_format_lline"
							<?php if( $socnetauth2_reg_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_reg_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_simplereg_page; ?></b>
					</td>
					<td>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_simplereg_format" 
							value="bline" 
							id="socnetauth2_simplereg_format_bline"
							<?php if( $socnetauth2_simplereg_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_simplereg_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_simplereg_format" 
							value="lline" 
							id="socnetauth2_simplereg_format_lline"
							<?php if( $socnetauth2_simplereg_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_simplereg_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				</table>
			</div>
			
        </div>
	  
		<div id="showtype_block" style="display: none;">
		
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_showtype; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_showtype" class="form-control">
					<option value="window"
					<?php if( $socnetauth2_showtype=='window' ) { ?> selected <?php } ?>
					><?php echo $text_showtype_window; ?></option>
					<option value="redirect"
					<?php if( $socnetauth2_showtype=='redirect' ) { ?> selected <?php } ?>
					><?php echo $text_showtype_redirect; ?></option>
				</select>
				<div><i><?php echo $text_showtype_notice; ?></i></div>
			</div>
			
        </div>
		</div>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_label; ?>
			</label>
            <div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
				<p>
					<input type="text"  class="form-control" name="socnetauth2_label[<?php echo $language['language_id']; ?>]" value="<?php if( !empty($socnetauth2_label[ $language['language_id'] ]) ) echo $socnetauth2_label[ $language['language_id'] ]; ?>" >&nbsp;<img 
					src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" />
				</p>
				<?php } ?>
			
			</div>
        </div>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_save_to_addr; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_save_to_addr" class="form-control">
					<option value="customer_only"
					<?php if( $socnetauth2_save_to_addr=='customer_only' ) { ?> selected <?php } ?>
					><?php echo $text_customer_only; ?></option>
					<option value="customer_addr"
					<?php if( $socnetauth2_save_to_addr=='customer_addr' ) { ?> selected <?php } ?>
					><?php echo $text_customer_addr; ?></option>
				</select>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_shop_folder; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" name="socnetauth2_shop_folder" class="form-control"  
						value="<?php echo $socnetauth2_shop_folder; ?>" />
			</div>
        </div>
		
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_email_auth; ?>
			</label>
            <div class="col-sm-10">
				<table width=100%>
				<tr>
					<td style="padding-right: 10px;">
						<input type="radio" name="socnetauth2_email_auth" value="none" 
					   id="socnetauth2_email_auth_none"
					   <?php if( $socnetauth2_email_auth == 'none' ) { ?> checked <?php } ?>
					   >
					 </td>
					 <td>
						<label for="socnetauth2_email_auth_none">
							<?php echo $entry_email_auth_none; ?>
					   </label>
					 </td>
				</tr>
				<tr>
					<td style="padding-right: 10px;">
						<input type="radio" name="socnetauth2_email_auth" value="confirm" 
					   id="socnetauth2_email_auth_confirm"
					   <?php if( $socnetauth2_email_auth == 'confirm' ) { ?> checked <?php } ?>
					   >
					 </td>
					 <td>
					   <label for="socnetauth2_email_auth_confirm">
						<?php echo $entry_email_auth_confirm; ?>
					   </label>
					 </td>
				</tr>
				<tr>
					<td style="padding-right: 10px;">
						<input type="radio" name="socnetauth2_email_auth" value="noconfirm" 
					   id="socnetauth2_email_auth_noconfirm"
					   <?php if( $socnetauth2_email_auth == 'noconfirm' ) { ?> checked <?php } ?>
					   >
					</td>
					<td>
					   <label for="socnetauth2_email_auth_noconfirm">
						<?php echo $entry_email_auth_noconfirm; ?>
					   </label>
					</td>
				 </tr>
				 </table>
			</div>
        </div>
		
			
			<?php /* start kin update: r1 */ ?>
			
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_vkontakte_retargeting; ?>
			</label>
            <div class="col-sm-10" style="padding-top: 9px;">
				<a href="<?php echo $vkontakte_retargeting; ?>"><?php echo $text_download_link; ?></a>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_facebook_retargeting; ?>
			</label>
            <div class="col-sm-10" style="padding-top: 9px;">
				<a href="<?php echo $facebook_retargeting; ?>"><?php echo $text_download_link; ?></a>
			</div>
        </div>
		
			<?php /* end kin update: r1 */ ?>
		
	<!--
		<p>
		<b><?php echo $entry_admin_header; ?></b>
		</p>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_admin_customer; ?>
			</label>
            <div class="col-sm-10" style="padding-top: 20px;">
				<input type="checkbox" name="socnetauth2_admin_customer" value="1"
				<?php if($socnetauth2_admin_customer) { ?> checked <?php } ?>
			>
			</div>
        </div>
			
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_admin_customer_list; ?>
			</label>
            <div class="col-sm-10" style="padding-top: 20px;">
				<input type="checkbox" name="socnetauth2_admin_customer_list" value="1"
				<?php if($socnetauth2_admin_customer_list) { ?> checked <?php } ?>
			>
			</div>
        </div>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_admin_order; ?>
			</label>
            <div class="col-sm-10" style="padding-top: 20px;">
				<input type="checkbox" name="socnetauth2_admin_order" value="1"
				<?php if($socnetauth2_admin_order) { ?> checked <?php } ?>
			>
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_admin_order_list; ?>
			</label>
            <div class="col-sm-10" style="padding-top: 20px;">
				<input type="checkbox" name="socnetauth2_admin_order_list" value="1"
				<?php if($socnetauth2_admin_order_list) { ?> checked <?php } ?>
			>
			</div>
        </div>
	-->
			
	  </div>
	  
	  
	  
	  <div id="tab-vkontakte" class="tab-pane">
	  
	  
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_vkontakte_status; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_vkontakte_status" class="form-control">
                  <?php if ( $socnetauth2_vkontakte_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* start r3 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_debug; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_vkontakte_debug" class="form-control">
                  <?php if ( $socnetauth2_vkontakte_debug ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* end r3 */ ?>
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_vkontakte_appid; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control" name="socnetauth2_vkontakte_appid"  value="<?php echo $socnetauth2_vkontakte_appid; ?>" />
			</div>
        </div>
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_vkontakte_appsecret; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control" name="socnetauth2_vkontakte_appsecret"  value="<?php echo $socnetauth2_vkontakte_appsecret; ?>" />
			</div>
        </div>
		
		
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_vkontakte_customer_group_id; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_vkontakte_customer_group_id" class="form-control" >
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_vkontakte_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
			</div>
        </div>
		
		
		
		<iframe width=100% height=700 src="https://opencart2x.ru/manual/vk.html" 
				border=0 
				frameborder="0" 
				style="border: 1px #ccc solid;"></iframe>
			
		
	  </div>
	  <div id="tab-facebook" class="tab-pane">
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_facebook_status; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_facebook_status" class="form-control">
                  <?php if ( $socnetauth2_facebook_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* start r3 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_debug; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_facebook_debug" class="form-control">
                  <?php if ( $socnetauth2_facebook_debug ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* end r3 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_facebook_appid; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control" name="socnetauth2_facebook_appid"  value="<?php echo $socnetauth2_facebook_appid; ?>" />
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_facebook_appsecret; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control" name="socnetauth2_facebook_appsecret"  value="<?php echo $socnetauth2_facebook_appsecret; ?>" />
			</div>
        </div>
		
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_facebook_customer_group_id; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_facebook_customer_group_id" class="form-control" >
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_facebook_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
			</div>
        </div>
	  
		<iframe width=100% height=700 src="https://opencart2x.ru/manual/fb.html" 
				border=0 
				frameborder="0" 
				style="border: 1px #ccc solid;"></iframe>
			
	  </div>
	  <div id="tab-twitter" class="tab-pane">
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_twitter_status; ?>
			</label>
            <div class="col-sm-10">
				
				<select name="socnetauth2_twitter_status" class="form-control">
                  <?php if ( $socnetauth2_twitter_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* start r3 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_debug; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_twitter_debug" class="form-control">
                  <?php if ( $socnetauth2_twitter_debug ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* end r3 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_twitter_consumer_key; ?>
			</label>
            <div class="col-sm-10">
				
				<input type="text" class="form-control" name="socnetauth2_twitter_consumer_key"  value="<?php echo $socnetauth2_twitter_consumer_key; ?>" />
			</div>
        </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_twitter_consumer_secret; ?>
			</label>
            <div class="col-sm-10">
				
				<input type="text" class="form-control" name="socnetauth2_twitter_consumer_secret"  value="<?php echo $socnetauth2_twitter_consumer_secret; ?>" />
			</div>
        </div>
		
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_twitter_customer_group_id; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_twitter_customer_group_id" class="form-control" >
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_twitter_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
			</div>
        </div>
		<iframe width=100% height=700 src="https://opencart2x.ru/manual/twitter.html" 
				border=0 
				frameborder="0" 
				style="border: 1px #ccc solid;"></iframe>
	  
	  </div>
	  <div id="tab-odnoklassniki" class="tab-pane">
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_odnoklassniki_status; ?>
			</label>
            <div class="col-sm-10">
			
				<select name="socnetauth2_odnoklassniki_status" class="form-control">
                  <?php if ( $socnetauth2_odnoklassniki_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* start r3 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_debug; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_odnoklassniki_debug" class="form-control">
                  <?php if ( $socnetauth2_odnoklassniki_debug ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* end r3 */ ?>
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_odnoklassniki_application_id; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control" name="socnetauth2_odnoklassniki_application_id"  value="<?php echo $socnetauth2_odnoklassniki_application_id; ?>" />
			</div>
        </div>
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_odnoklassniki_public_key; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control" name="socnetauth2_odnoklassniki_public_key"  value="<?php echo $socnetauth2_odnoklassniki_public_key; ?>" />
			</div>
        </div>
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_odnoklassniki_secret_key; ?>
			</label>
            <div class="col-sm-10">
				<input type="text" class="form-control" name="socnetauth2_odnoklassniki_secret_key"  value="<?php echo $socnetauth2_odnoklassniki_secret_key; ?>" />
			</div>
        </div>
		
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_odnoklassniki_customer_group_id; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_odnoklassniki_customer_group_id" class="form-control" >
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_odnoklassniki_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
			</div>
        </div>
		
		<iframe width=100% height=700 src="https://opencart2x.ru/manual/ok.html" 
				border=0 
				frameborder="0" 
				style="border: 1px #ccc solid;"></iframe>

		
	  </div>
	  
	  
	  
	  <?php /* start metka: a1 */ ?>
	  <div id="tab-gmail" class="tab-pane">
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_gmail_status; ?>
			</label>
            <div class="col-sm-10">
			  <select name="socnetauth2_gmail_status" class="form-control">
                  <?php if ( $socnetauth2_gmail_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* start r3 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_debug; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_gmail_debug" class="form-control">
                  <?php if ( $socnetauth2_gmail_debug ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* end r3 */ ?>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_gmail_client_id; ?>
			</label>
            <div class="col-sm-10">
			  <input type="text" name="socnetauth2_gmail_client_id"  class="form-control" 
				value="<?php echo $socnetauth2_gmail_client_id; ?>" />
			</div>
        </div>
		
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_gmail_client_secret; ?>
			</label>
            <div class="col-sm-10">
			  <input type="text" name="socnetauth2_gmail_client_secret"  class="form-control" 
				value="<?php echo $socnetauth2_gmail_client_secret; ?>" />
			</div>
        </div>
		
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_gmail_customer_group_id; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_gmail_customer_group_id" class="form-control" >
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_gmail_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
			</div>
        </div>
		
	  
		<iframe width=100% height=700 src="https://opencart2x.ru/manual/google.html" 
				border=0 
				frameborder="0" 
				style="border: 1px #ccc solid;"></iframe>

		
	  </div>
	  
	  
	  <div id="tab-mailru" class="tab-pane">
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_mailru_status; ?>
			</label>
            <div class="col-sm-10">
			  <select name="socnetauth2_mailru_status" class="form-control">
                  <?php if ( $socnetauth2_mailru_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* start r3 */ ?>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_debug; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_mailru_debug" class="form-control">
                  <?php if ( $socnetauth2_mailru_debug ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>
        </div>
		<?php /* end r3 */ ?>
	    <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_mailru_id; ?>
			</label>
            <div class="col-sm-10">
			   <input type="text" name="socnetauth2_mailru_id"  class="form-control" 
				value="<?php echo $socnetauth2_mailru_id; ?>" />
			</div>
        </div>
	    <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_mailru_private; ?>
			</label>
            <div class="col-sm-10">
			   <input type="text" name="socnetauth2_mailru_private"  class="form-control" 
				value="<?php echo $socnetauth2_mailru_private; ?>" />
			</div>
        </div>
	    <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_mailru_secret; ?>
			</label>
            <div class="col-sm-10">
			   <input type="text" name="socnetauth2_mailru_secret"  class="form-control" 
				value="<?php echo $socnetauth2_mailru_secret; ?>" />
			</div>
        </div>
		
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_mailru_customer_group_id; ?>
			</label>
            <div class="col-sm-10">
				<select name="socnetauth2_mailru_customer_group_id" class="form-control" >
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_mailru_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
			</div>
        </div>
		
		<iframe width=100% height=700 src="https://opencart2x.ru/manual/mailru.html" 
				border=0 
				frameborder="0" 
				style="border: 1px #ccc solid;"></iframe>

		
	  </div>
	  <?php /* end metka: a1 */ ?>
	  
	  <div id="tab-dobor" class="tab-pane">
	  
        <p><b><?php echo $entry_confirm_data; ?></b></p>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_dobortype; ?>
			</label>
            <div class="col-sm-10">
			
				<select  class="form-control" name="socnetauth2_dobortype">
					<option value="one"
						<?php if( $socnetauth2_dobortype == 'one' ) { ?> selected <?php } ?>
					><?php   echo $entry_dobortype_one; ?></option>
					<option value="every"
						<?php if( $socnetauth2_dobortype == 'every' ) { ?> selected <?php } ?>
					><?php echo $entry_dobortype_every; ?></option>
				</select>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_firstname; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_firstname_status">
                  <?php if ( $socnetauth2_confirm_firstname_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_firstname_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                </select>
				<input type="checkbox" name="socnetauth2_confirm_firstname_required" value="1"
				id="socnetauth2_confirm_firstname_required"
				<?php if( $socnetauth2_confirm_firstname_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_firstname_required"><?php echo $text_required;?></label>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_lastname; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_lastname_status">
                <?php if ( $socnetauth2_confirm_lastname_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_lastname_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input type="checkbox" name="socnetauth2_confirm_lastname_required" value="1"
				id="socnetauth2_confirm_lastname_required"
				<?php if( $socnetauth2_confirm_lastname_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_lastname_required"><?php echo $text_required;?></label>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_email; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_email_status">
                <?php if ( $socnetauth2_confirm_email_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_email_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                </select>
				<input type="checkbox" name="socnetauth2_confirm_email_required" value="1"
				id="socnetauth2_confirm_email_required"
				<?php if( $socnetauth2_confirm_email_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_email_required"><?php echo $text_required;?></label>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_phone; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_telephone_status">
                 <?php if ( $socnetauth2_confirm_telephone_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_telephone_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input type="checkbox" name="socnetauth2_confirm_telephone_required" value="1"
				id="socnetauth2_confirm_telephone_required"
				<?php if( $socnetauth2_confirm_telephone_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_telephone_required"><?php echo $text_required;?></label>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_company; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_company_status">
                 <?php if ( $socnetauth2_confirm_company_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_company_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input type="checkbox" name="socnetauth2_confirm_company_required" value="1"
				id="socnetauth2_confirm_company_required"
				<?php if( $socnetauth2_confirm_company_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_company_required"><?php echo $text_required;?></label>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_postcode; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_postcode_status">
                 <?php if ( $socnetauth2_confirm_postcode_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_postcode_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input type="checkbox" name="socnetauth2_confirm_postcode_required" value="1"
				id="socnetauth2_confirm_postcode_required"
				<?php if( $socnetauth2_confirm_postcode_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_postcode_required"><?php echo $text_required;?></label>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_country; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_country_status">
                 <?php if ( $socnetauth2_confirm_country_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_country_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input type="checkbox" name="socnetauth2_confirm_country_required" value="1"
				id="socnetauth2_confirm_country_required"
				<?php if( $socnetauth2_confirm_country_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_country_required"><?php echo $text_required;?></label>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_zone; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_zone_status">
                 <?php if ( $socnetauth2_confirm_zone_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_zone_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input type="checkbox" name="socnetauth2_confirm_zone_required" value="1"
				id="socnetauth2_confirm_zone_required"
				<?php if( $socnetauth2_confirm_zone_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_zone_required"><?php echo $text_required;?></label>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_city; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_city_status">
                 <?php if ( $socnetauth2_confirm_city_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_city_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input type="checkbox" name="socnetauth2_confirm_city_required" value="1"
				id="socnetauth2_confirm_city_required"
				<?php if( $socnetauth2_confirm_city_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_city_required"><?php echo $text_required;?></label>
			</div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_confirm_address_1; ?>
			</label>
            <div class="col-sm-10">
			<select  class="form-control" name="socnetauth2_confirm_address_1_status">
                 <?php if ( $socnetauth2_confirm_address_1_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_address_1_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input type="checkbox" name="socnetauth2_confirm_address_1_required" value="1"
				id="socnetauth2_confirm_address_1_required"
				<?php if( $socnetauth2_confirm_address_1_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_address_1_required"><?php echo $text_required;?></label>
			</div>
        </div>
	  </div>
	  <!-- /////////////////////////////////////////////////////////////////////////////// -->
	  <div id="tab-widget" class="tab-pane">
	  
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_widget_name; ?>
			</label>
            <div class="col-sm-10">
			
	  		<?php foreach ($languages as $language) { ?>
			<p>
			<input type="text"  class="form-control"  name="socnetauth2_widget_name[<?php echo $language['language_id']; ?>]" value="<?php if( !empty($socnetauth2_widget_name[ $language['language_id'] ]) ) echo $socnetauth2_widget_name[ $language['language_id'] ]; ?>" >&nbsp;<img 
			src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" />
			</p>
			<?php } ?>
			</div>
        </div>
		
		
		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_widget_format; ?>
			</label>
            <div class="col-sm-10">
			<table>
				<tr>
					<td width=200><input type="radio" name="socnetauth2_widget_format" 
					value="kvadrat" 
					onClick="show_widget_showtype_block()"
					id="socnetauth2_widget_format_kvadrat"
					<?php if( $socnetauth2_widget_format=='kvadrat' ) { ?> checked <?php } ?> 
					><label for="socnetauth2_widget_format_kvadrat"><?php echo $text_format_kvadrat; ?></label></td>
					
					<td width=200><input type="radio" name="socnetauth2_widget_format" 
					onClick="show_widget_showtype_block()"
					value="lline" id="socnetauth2_widget_format_lline"
					<?php if( $socnetauth2_widget_format=='lline' ) { ?> checked <?php } ?>
					><label for="socnetauth2_widget_format_lline"><?php echo $text_format_lline; ?></label></td>
				</tr>
				<tr>
					<td valign=top><img src="view/image/socnetauth2/kvadrat.gif"></td>
					<td valign=top><img src="view/image/socnetauth2/lline.gif"></td>
				</tr>
				</table>
			</div>
        </div>

		<div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_widget_after; ?>
			</label>
            <div class="col-sm-10">
				<select  class="form-control" name="socnetauth2_widget_after">
					<option value="hide"
					<?php if( $socnetauth2_widget_after=='hide' ) { ?> selected <?php } ?>
					><?php echo $text_widget_after_hide; ?></option>
					<option value="show"
					<?php if( $socnetauth2_widget_after=='show' ) { ?> selected <?php } ?>
					><?php echo $text_widget_after_show; ?></option>
				</select>
			</div>
        </div>
	  </div>
	  <!-- /////////////////////////////////////////////////////////////////////////////// -->
	  
	  <div id="tab-popup" class="tab-pane">
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_mobile_control; ?>
			</label>
            <div class="col-sm-10"><select name="socnetauth2_mobile_control" class="form-control" >
                  <?php if ( $socnetauth2_mobile_control ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			</div>			
        </div>
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret">
				<?php echo $entry_widget_status; ?>
			</label>
            <div class="col-sm-10">
				<select  class="form-control" name="socnetauth2_popup_status" class="form-control">
                  <?php if ($socnetauth2_popup_status) { ?>
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
				<?php echo $entry_popup_name; ?>
			</label>
            <div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
				<p>
					<input type="text" class="form-control"  name="socnetauth2_popup_name[<?php echo $language['language_id']; ?>]" value="<?php if( !empty($socnetauth2_popup_name[ $language['language_id'] ]) ) echo $socnetauth2_popup_name[ $language['language_id'] ]; ?>" >&nbsp;<img 
					src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" />
				</p>
				<?php } ?>
			</div>
        </div>
		
	  </div>
	
	  <!-- /////////////////////////////////////////////////////////////////////////////// -->
	  
	  
	  
	  <div id="tab-design" class="tab-pane">
	  <p><?php echo $text_design_notice2; ?></p>
	  
		<table class="list">
		<thead>
		<tr>
			<td class="left" width=33%><b><?php echo $text_design_col_element; ?></b></td>
			<td class="left" width=33%><b><?php echo $text_design_col_file; ?></b></td>
			<td class="left" width=34%><b><?php echo $text_design_col_comment; ?></b></td>
		</tr>
		</thead>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_account; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_account.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_checkout; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_checkout.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_simple; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_simple.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_simplereg; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_simplereg.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_reg; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_reg.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_popup; ?></td>
			<td class="left">/catalog/view/theme/default/template/module/socnetauth2_popup.tpl</td>
			<td class="left"></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_confirm; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_confirm.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_frame; ?></td>
			<td class="left">/catalog/view/theme/default/template/account/socnetauth2_frame.tpl</td>
			<td class="left"></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_frame_success; ?></td>
			<td class="left">/catalog/view/theme/default/template/account/socnetauth2_frame_success.tpl</td>
			<td class="left"></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_module_socnetauth2; ?></td>
			<td class="left">/catalog/view/theme/default/template/module/socnetauth2.tpl</td>
			<td class="left"></td>
		</tr>
		</table>
		
		
		
	  </div>
	  
	  
	  
	  
	  <div id="tab-support" class="tab-pane">
	  
			<p><?php echo $text_frame; ?></p>
			<iframe width=100% height=700 src="https://opencart2x.ru/manual/socnetauth2.html" border=0 frameborder="0" style="border: 1px #ccc solid;"></iframe>
			<?php echo $text_contact; ?>
	  </div>
      </form>
<? /*
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="socnetauth2_modules[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="socnetauth2_modules[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="socnetauth2_modules[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="socnetauth2_modules[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}

var popup_row = <?php echo $popup_row; ?>;

function addPopup() {	
	html  = '<tbody id="popup-row' + popup_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="socnetauth2_popups[' + popup_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="socnetauth2_popups[' + popup_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="left"><a onclick="$(\'#popup-row' + popup_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#popup tfoot').before(html);
	
	popup_row++;
}
//--></script> 
*/ ?>
    </div>
  </div>
</div>
	  
	<script>
		function set_tab()
		{
			if( $('#link-tab-general').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-general';
			}
			
			if( $('#link-tab-vkontakte').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-vkontakte';
			}
			
			if( $('#link-tab-facebook').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-facebook';
			}
			
			if( $('#link-tab-twitter').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-twitter';
			}
			
			if( $('#link-tab-odnoklassniki').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-odnoklassniki';
			}
			
			if( $('#link-tab-dobor').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-dobor';
			}
			
			if( $('#link-tab-widget').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-widget';
			}
			
			if( $('#link-tab-popup').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-popup';
			}
			
			if( $('#link-tab-design').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-design';
			}
			
			if( $('#link-tab-support').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-support';
			}
			
			/* start metka: a1 */
			if( $('#link-tab-gmail').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-gmail';
			}
			
			if( $('#link-tab-mailru').attr('aria-expanded') == 'true' )
			{
				document.getElementById('hiddentab').value = 'link-tab-mailru';
			}
			/* end metka: a1 */
			
		}
		
		$('#<?php echo $tab; ?>').click();
		
	</script>
<?php echo $footer; ?>