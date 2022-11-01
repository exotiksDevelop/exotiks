<form action="/index.php?route=tool/export_import/download" method="post" enctype="multipart/form-data" id="export" class="form-horizontal">		
	<input type="hidden" name="export_type" value="p" checked="checked" />
	<input type="submit" value="<?php echo $button_export; ?>" />
</form>