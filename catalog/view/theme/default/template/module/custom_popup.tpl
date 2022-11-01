<?php if ($custom_css) {?>
<style type="text/css">
<?php echo $custom_css; ?>
</style>
<?php } ?>

<div id="modal-custom-popup" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body"><?php echo $html; ?></div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#modal-custom-popup').modal('show');
	
	<?php if ($seconds_to_close) { ?>
	setTimeout(function() {
        $('#modal-custom-popup').modal('hide');
	}, <?php echo $seconds_to_close; ?>);
	<?php } ?>
});
--></script>
