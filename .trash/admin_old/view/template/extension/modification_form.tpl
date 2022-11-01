<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-modification" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <?php if($refresh) { ?><button type="submit" onclick="$('#form-modification').attr('action', '<?php echo $refresh; ?>').submit()" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" class="btn btn-primary"><i class="fa fa-save"></i> + <i class="fa fa-refresh"></i></button><?php } ?>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
        <?php if ($modification['status']) { ?>
          <i class="fa fa-check-circle-o fa-2x pull-right text-success" style="font-size:2em" data-toggle="tooltip" title="<?php echo $text_enabled; ?>"></i>
        <?php } else { ?>
          <i class="fa fa-times-circle-o fa-2x pull-right text-danger" style="font-size:2em" data-toggle="tooltip" title="<?php echo $text_disabled; ?>"></i>
        <?php } ?>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-modification" class="form-horizontal">
          <?php if (isset($error_xml)) { ?><div class="text-danger"><?php echo $error_xml; ?></div><?php } ?>
          <textarea name="xml" id="input-xml" class="form-control"><?php echo htmlentities($modification['xml'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </form>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
#input-xml + .CodeMirror {
	height: auto;
}
</style>
<script type="text/javascript"><!--
var el = document.getElementById("input-xml");
if (el) {
	editor = CodeMirror.fromTextArea(el, {
		mode: "xml",
		integer: 2,
		lineNumbers: true,
		viewportMargin: Infinity,
		autofocus: true,
		alignCDATA: true,
		lineWrapping: true,
		indentWithTabs: true,
		indentUnit: 2,
		styleActiveLine: true,
		autoCloseTags: true
	});
	
	if (editor) {
		var editorChanged = false; 
		
		editor.on('change', function(){
			if (editorChanged == false) {
				window.onbeforeunload = function() {
				    return 'Confirm that you want to leave - data you have entered may not be saved';
				}
			}
			
			editorChanged = true;
		});

	    $('#form-modification').bind('submit',function() {
	    	$(this).addClass('form-saving');
	    	
	    	if (editorChanged) {
	    		window.onbeforeunload = null;
	    	}
	    });
	}
}
//--></script>
<?php echo $footer; ?>