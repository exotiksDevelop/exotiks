<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="form-albums">
        
          <p class="text-right">
              <a href="<?php echo $add ?>" class="btn btn-primary">Добавить</a>
              <a onclick="if (!confirm('Действительно удалить отмеченные альбомы?')) return false; else $('#form-albums').submit()"  class="btn btn-danger">Удалить отмеченные</a>
              <a href="<?php echo $delete_all ?>" class="btn btn-danger" onclick="if (!confirm('Действительно удалить все альбомы?')) return false;">Удалить все</a>
          </p>
        
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td width="20"></td>
                  <td class="left"><?php echo $entry_category; ?></td>
                  <td class="left" width="300">Альбом ВК</td>
                  <td width="150" align="center">Действие</td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($albums as $key => $album) { ?>
                <tr>
                  <td><input type="checkbox" name="delete_albums[<?php echo $key ?>]" value="<?php echo $album['category_id'] ?>"></td>
                  <td class="left"><?php echo $album['category'] ?></td>
                  <td class="left">
                      <?php if ($album['vk_album_id']) { ?>
                        <a href="http://vk.com/album<?php echo $album['vk_album_id'] ?>" target="_blank" title="Просмотр альбома">http://vk.com/album<?php echo $album['vk_album_id'] ?></a>
                        <input type="hidden" name="vk_album_id[<?php echo $key ?>]" value="<?php echo $album['vk_album_id'] ?>">
                        <?php echo $album['mode_name'] ?><input type="hidden" name="mode[<?php echo $key ?>]" value="<?php echo $album['mode'] ?>">
                      <?php } ?>
                  </td>
                  <td align="center">[ <a href="<?php echo $album['edit'] ?>">Изменить</a> ]</td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  
  </div>
</div>
<?php echo $footer; ?>

