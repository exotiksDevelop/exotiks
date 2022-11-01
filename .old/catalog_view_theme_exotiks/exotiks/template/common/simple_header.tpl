<?=$header; ?>
<div class="container">
  <a class="back position" onclick="javascript:history.back();">
    <i class="back-ico"></i>Назад
  </a>


  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>

  <div class="row">

    <div id="content" class="col-12">

      <h1 class="heading-title position">
        <?= $heading_title; ?>
      </h1>
      
      <!-- <div class="nav-panel" id="navPanel"></div> -->