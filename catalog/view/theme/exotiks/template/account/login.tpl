<?php echo $header; ?>
<div class="container">
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<div id="content"><?php echo $content_top; ?>
  <div class="row">
    <div class="col-sm-6">
      <div class="well">
        <h2><?php echo $text_new_customer; ?></h2>
        <p><strong><?php echo $text_register; ?></strong></p>
        <p><?php echo $text_register_account; ?></p>
        <a href="<?php echo $register; ?>" class="button"><?php echo $button_continue; ?></a>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="well">
        <h2><?php echo $text_returning_customer; ?></h2>
        <p><strong><?php echo $text_i_am_returning_customer; ?></strong></p>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
            <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>"
              id="input-email" class="form-control" />
          </div>
          <div class="form-group">
            <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
            <input type="password" name="password" value="<?php echo $password; ?>"
              placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
            <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
          <input type="submit" value="<?php echo $button_login; ?>" class="button" />
          <?php if ($redirect) { ?>
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
          <?php } ?>
        </form>
      </div>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
</div><!-- /.container -->

<?php echo $footer; ?>