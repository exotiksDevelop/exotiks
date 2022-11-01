<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"> <!-- div id="content" -->
  <div class="page-header"><!-- div class="page-header" -->
    <div class="container-fluid">
      <div class="pull-right">
        <a href="javascript: $('#form').submit();" 
		data-toggle="tooltip" 
		title="<?php echo $button_setlicense; ?>" 
		class="btn btn-primary"><i class="fa fa-save"></i></a>
		
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
	
    <div class="panel panel-default"> <!--  class="panel panel-default" -->
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body"> <!--  class="panel-body" -->
	  
   <form  autocomplete="off" action="<?php echo $action; ?>" method="post"  
	  autocomplete="off" enctype="multipart/form-data" id="form" class="form-horizontal">
    <input type="hidden" name="stay" id="stay_field" value="1">
	
	
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-access-secret" style="padding-top: 30px;">
				<?php echo $entry_license; ?>
			</label>
            <div class="col-sm-10" style="padding: 20px">
			
			<input type="text" name="code" value="" style="width: 300px;" class="form-control">
				<div><?php echo $entry_license_notice; ?></div>
			
			</div>
        </div>
      </form>
	</div> <!--  class="panel-body" -->
	</div> <!--  class="panel panel-default" -->
  </div><!-- div class="container-fluid" -->
</div> <!-- div id="content" -->

<?php echo $footer; ?> 