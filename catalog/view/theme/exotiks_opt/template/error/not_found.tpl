<?php echo $header; ?>
<div class="container">
   <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left"> 
                <?//php echo $column_left; ?>
            </div>
        </div>
    
    <div class="col-md-9 col-sm-8 col-xs-12">
                <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
    <div id="content" class="<?php echo $class; ?>">
      <h1><?php echo $heading_title; ?></h1>
      <p><?php echo $text_error; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
      </div></div></div>
</div>
</div>
<?php echo $footer; ?>