<?php echo $header; ?>
<div class="container">
  
  <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left"> 
                <?php echo $column_left; ?>
            </div>
        </div>
    
    <div class="col-md-8 col-sm-8 col-xs-12">
                <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
<?php echo $microdatapro; $microdatapro_main_flag = 1; //microdatapro 7.3 - 1 - main ?>
      <div class="cont-page"><?php echo $description; ?><?php echo $content_bottom; ?></div>
    </div>
    
    </div>
  </div>

<?php if(!isset($microdatapro_main_flag)){echo $microdatapro;} //microdatapro 7.3 - 2 - extra ?>
<?php echo $footer; ?>
</div>