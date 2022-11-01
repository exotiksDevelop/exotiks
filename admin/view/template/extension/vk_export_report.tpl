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
      
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab">Альбомы</a></li>
            <li><a href="#tab2" data-toggle="tab">Стена</a></li>
            <li><a href="#tab3" data-toggle="tab">Обновление в альбомах</a></li>
            <li><a href="#tab4" data-toggle="tab">Товары</a></li>
            <li><a href="#tab5" data-toggle="tab">Обновление товаров</a></li>
          </ul>
          <div class="tab-content">

            <div class="tab-pane active" id="tab1">
                <pre><?php echo $export; ?></pre>
            </div>
            <div class="tab-pane" id="tab2">
                <pre><?php echo $wall; ?></pre>
            </div>
            <div class="tab-pane" id="tab3">
                <pre><?php echo $update; ?></pre>
            </div>
            <div class="tab-pane" id="tab4">
                <pre><?php echo $market; ?></pre>
            </div>
            <div class="tab-pane" id="tab5">
                <pre><?php echo $market_update; ?></pre>
            </div>
        </div>
        
      </div>
    </div>
  
  </div>
</div>
<?php echo $footer; ?>

