<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
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
    <?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_how_get_license; ?></h3>
        </div>
        <div class="panel-body">
            <?php echo $text_license; ?>
        </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_activation; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-forgotten-cart" class="form-horizontal">
          <div class="form-group required">
            <div class="col-sm-12">
                <textarea class="form-control" name="forgotten_cart_license_key" placeholder="<?php echo $entry_license_key; ?>"><?php echo $forgotten_cart_license_key; ?></textarea>
            </div>
          </div>
          <input type="submit" class="btn btn-primary" value="<?php echo $button_save; ?>" />
        </form>
      </div>
    </div>
  </div>
  <div style="text-align: center;"><?php echo $license_content; ?></div>
</div>
<?php echo $footer; ?>
