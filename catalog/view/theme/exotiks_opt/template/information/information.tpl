<?= $header; ?>

<!-- <div class="row"> -->

  <div class="container">
  <div class="information">
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <div id="content">
        <?//php echo $content_top; ?>
        <h1 class="heading-title">
          <?php echo $heading_title; ?>
        </h1>
        <div class="cont-page information__content">
          <?php echo $description; ?>
          <?php echo $content_bottom; ?>
        </div>
      </div>
    </div><!-- /.information -->
  </div><!-- /.container -->
<!-- </div> -->
<!-- /.row -->

<?php echo $footer; ?>