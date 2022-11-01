<div class="buttons">
  <div class="pull-right">
      <a id="button-confirm"  class="btn btn-primary"><span><?php echo $button_confirm; ?></span></a>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		type: 'get',
		url: 'index.php?route=payment/rpcod2ecom/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script>