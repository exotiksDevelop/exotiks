<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-album" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
  
  <?php if ($warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
  <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php } ?>
  
  <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $button_add_album; ?></h3>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="form-album" class="form-horizontal">
          
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_category; ?></label>
            <div class="col-sm-10"><?php echo $category_select?></div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label">Категория ВК</label>
            <div class="col-sm-10">
              <select name="vk_market_category_id" class="form-control">
                  <option value="0"></option>
              <?php foreach ($vk_market_categories as $mcats) { ?>
                  <optgroup label="<?php echo $mcats['name']; ?>">
                    <?php foreach ($mcats['childs'] as $mcat) { ?>
                      <option value="<?php echo $mcat->id; ?>"><?php echo $mcat->name; ?></option>
                    <?php } ?>
                  </optgroup>
              <?php } ?>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label">Подборка ВК</label>
            <div class="col-sm-10">
              <input type="text" name="vk_market_album_id" value="<?php echo (isset($this->request->post['vk_market_album_id']) ? htmlentities($this->request->post['vk_market_album_id']) : '' ); ?>" placeholder="ссылка на подборку ВК" class="form-control" />
            </div>
          </div>
          
        </form>
      </div>
    </div>
  
  </div>
</div>
<?php echo $footer; ?>

