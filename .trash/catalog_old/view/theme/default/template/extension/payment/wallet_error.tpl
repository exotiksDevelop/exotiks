<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div class="container"><?php echo $content_top; ?>
	 <div class="panel-body">
		<?php foreach ($error as $e) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $e; ?>
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
      </div>
	<div class="buttons">
    <div class="pull-left">
	  <a href="<?php echo $back; ?>" class="btn btn-primary"><?php echo $text_back; ?></a>
    </div>
  </div>
</div>
<?php echo $footer; ?>