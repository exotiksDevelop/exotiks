<div class="buttons">
    <div class="pull-right">
        <input type="button" value="<?php echo $button_confirm; ?>" id="bb_confirm" class="btn btn-primary" />
    </div>
</div>
<script type="text/javascript"><!--
$('#bb_confirm').click(function() {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/bb_payment/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}
	});
});
//--></script>
