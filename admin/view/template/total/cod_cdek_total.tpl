<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-shipping" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-shipping" class="form-horizontal">
        	<div class="form-group">
        		<div class="col-sm-offset-1 col-sm-10">
        		<p>Чтобы учитывать стоимость наложного платежа СДЕК добавьте в модуле оплаты во вкладке Дополнительно наценку/скидку</p>
        		</div>
        	</div>

        	<div class="form-group">
        		<label class="col-sm-2 control-label" for=""><?php echo $entry_title; ?></label>
        		<div class="col-sm-10">
        			<div class="input-group">
        				<?php foreach ($languages as $language) { ?>
        				<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
        				<input class="form-control" type="text" name="cod_cdek_total_title[<?php echo $language['language_id']; ?>]" value="<?php echo isset($cod_cdek_total_title[$language['language_id']]) ? $cod_cdek_total_title[$language['language_id']] : ''; ?>" />
        				<?php } ?>
        			</div>
        		</div>
        	</div>
        	<div class="form-group">
        		<label class="col-sm-2 control-label" for=""><?php echo $entry_sort_order; ?></label>
        		<div class="col-sm-10">
        			<input class="form-control" type="text" name="cod_cdek_total_sort_order" value="<?php echo $cod_cdek_total_sort_order; ?>" size="1" />
        		</div>
        	</div>
        	<input type="hidden" name="cod_cdek_total_status" value="1" />
		
          
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>