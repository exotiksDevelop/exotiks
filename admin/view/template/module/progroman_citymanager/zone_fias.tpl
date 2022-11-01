<div class="row">
<?php if ($countries || $zones) { ?>
  <div class="alert alert-warning col-xs-12">
      <?= $text_regions_info; ?>
  </div>
<?php } ?>
<?php if ($countries) { ?>
  <h4><?= $text_no_relative_countries ?></h4>
  <?php foreach ($countries as $country) { ?>
    <div class="col-sm-4 col-xs-6"><?= $country['offname'] ?></div>
  <?php } ?>
  <br><br>
<?php } ?>
<?php if ($zones) { ?>
  <h4><?= $text_no_relative_zones ?></h4>
  <?php foreach ($zones as $zone) { ?>
    <div class="col-sm-4 col-xs-6"><?= $zone['offname'], ' ', $zone['shortname'], ', ', $zone['parent_name'] ?></div>
  <?php } ?>
<?php } else { ?>
  <div class="alert alert-success col-xs-12">
      <?= $text_regions_info_success; ?>
  </div>
<?php } ?>
</div>
