<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" class="btn btn-info"><i class="fa fa-refresh"></i></a>
      	<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
      	<a href="<?php echo $clear; ?>" data-toggle="tooltip" title="<?php echo $button_clear; ?>" class="btn btn-warning"><i class="fa fa-eraser"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-modification').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_refresh; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-log" data-toggle="tab"><?php echo $tab_log; ?></a></li>
          <?php if ($modified_files) { ?><li><a href="#tab-files" data-toggle="tab"><?php echo $tab_files; ?></a></li><?php } ?>
          <?php if ($error_log) { ?><li><a href="#tab-error" data-toggle="tab"><?php echo $tab_error; ?></a></li><?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <form id="form-filter" method="get" class="well">
		      <div class="row">
		        <div class="col-sm-4">
		          <div class="form-group">
		            <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
	                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
	              </div>
	            </div>
	            <div class="col-sm-4">
	              <div class="form-group">
	                <label class="control-label" for="input-author"><?php echo $entry_author; ?></label>
	                <input type="text" name="filter_author" value="<?php echo $filter_author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-author" class="form-control" />
	              </div>
	            </div>
	            <div class="col-sm-4">
	              <div class="form-group">
	                <label class="control-label" for="input-xml"><?php echo $entry_xml; ?></label>
	                <input type="text" name="filter_xml" value="<?php echo $filter_xml; ?>" placeholder="<?php echo $entry_xml; ?>" id="input-xml" class="form-control" />
	              </div>
	            </div>
	            <div class="col-sm-12">
	              <div class="btn-group pull-right">
		            <button type="submit" id="button-filter" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_filter; ?>"><i class="fa fa-search"></i></button>
		            <?php if (!empty($filter_name) || !empty($filter_author) || !empty($filter_xml)) { ?><a href="<?php echo $filter_action; ?>" id="button-filter" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_reset; ?>"><i class="fa fa-times"></i></a><?php } ?>
		          </div>
	            </div>
	          </div>
	        </form>
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-modification">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th bgcolor="#f5f5f5" style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                      <th bgcolor="#f5f5f5" class="text-left"><?php if ($sort == 'name') { ?>
                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                        <?php } ?></th>
                      <th bgcolor="#f5f5f5" class="text-left"><?php if ($sort == 'author') { ?>
                        <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                        <?php } ?></th>
                      <th bgcolor="#f5f5f5" class="text-center"><?php if ($sort == 'version') { ?>
                        <a href="<?php echo $sort_version; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_version; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_version; ?>"><?php echo $column_version; ?></a>
                        <?php } ?></th>
                      <th bgcolor="#f5f5f5" class="text-center"><?php if ($sort == 'status') { ?>
                        <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                        <?php } ?></th>
                      <th bgcolor="#f5f5f5" class="text-center"><?php if ($sort == 'date_added') { ?>
                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                        <?php } ?></th>
                      <th bgcolor="#f5f5f5" class="text-center"><?php if ($sort == 'date_modified') { ?>
                        <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                        <?php } ?></th>
                      <th bgcolor="#f5f5f5" class="text-right"><?php echo $column_action; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($modifications) { ?>
                    <?php foreach ($modifications as $modification) { ?>
                    <tr>
                      <td class="text-center"><?php if (in_array($modification['modification_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $modification['modification_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $modification['modification_id']; ?>" />
                        <?php } ?></td>
                      <td class="text-left"><?php echo $modification['name']; ?></td>
                      <td class="text-left"><?php echo $modification['author']; ?></td>
                      <td class="text-center"><?php echo $modification['version']; ?></td>
                      <td class="text-center"><i class="fa fa-<?php echo ($modification['enabled']) ? 'check-circle text-success' : 'times-circle text-warning' ?> fa-2x"></i></td>
                      <td class="text-center"><?php echo $modification['date_added']; ?></td>
                      <td class="text-center"><?php echo $modification['date_modified']; ?></td>
                      <td class="text-right"><?php if ($modification['edit']) { ?>
        	            <a href="<?php echo $modification['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      <?php } else { ?>
			            <button type="button" class="btn btn-info" disabled="disabled"><i class="fa fa-link"></i></button>
		              <?php } ?>
      	              <?php if ($modification['link']) { ?>
                        <a href="<?php echo $modification['link']; ?>" data-toggle="tooltip" title="<?php echo $button_link; ?>" class="btn btn-info" target="_blank"><i class="fa fa-link"></i></a>
                      <?php } else { ?>
                        <button type="button" class="btn btn-info" disabled="disabled"><i class="fa fa-link"></i></button>
                      <?php } ?>
                      <?php if (!$modification['enabled']) { ?>
                        <a href="<?php echo $modification['enable']; ?>" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                      <?php } else { ?>
                        <a href="<?php echo $modification['disable']; ?>" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                      <?php } ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </form>
            <div class="row">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
          </div>
          <?php if ($error_log) { ?>
          <div class="tab-pane" id="tab-error">
            <p>
              <textarea wrap="off" rows="15" class="form-control"><?php echo $error_log ?></textarea>
            </p>
            <div class="text-right"><a href="<?php echo $clear_log; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i> <?php echo $button_clear ?></a></div>
          </div>
          <?php } ?>
          <?php if ($modified_files) { ?>
          <div class="tab-pane" id="tab-files">
          	<div class="table-responsive">
            <table class="table table-bordered table-condensed">
            	<thead><tr>
            		<th bgcolor="#f5f5f5" class="text-center">File</th>
            		<th bgcolor="#f5f5f5" class="text-center">Modified By</th>
            	</tr></thead>
            	<tbody>
            	<?php foreach($modified_files as $modified_file) { ?>
            	<tr>
            		<td style="vertical-align:top"><?php echo $modified_file['file']; ?></td>
            		<td><?php if($modified_file['modifications']){ ?>
            			<?php $i = 0; foreach($modified_file['modifications'] as $modified_file_modification){ ?>
            				<?php if ($i){ echo '<br />'; } ?>
            				<b><?php echo $modified_file_modification['name']; ?></b> by: <?php echo $modified_file_modification['author']; ?>
            			<?php $i++; } ?>
            		<?php } ?></td>
            	</tr>
            	<?php } ?>
            	</tbody>
            </table>
            </div>
            <div class="text-right"><a href="<?php echo $clear_log; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i> <?php echo $button_clear ?></a></div>
          </div>
          <?php } ?>
          <div class="tab-pane" id="tab-log">
            <p>
              <textarea wrap="off" rows="15" class="form-control"><?php echo $log ?></textarea>
            </p>
            <div class="text-right"><a href="<?php echo $clear_log; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i> <?php echo $button_clear ?></a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#form-filter').on('submit', function() {
	var url = 'index.php?route=extension/modification&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_xml = $('input[name=\'filter_xml\']').val();
	if (filter_xml) {
		url += '&filter_xml=' + encodeURIComponent(filter_xml);
	}

	var filter_author = $('input[name=\'filter_author\']').val();
	if (filter_author) {
		url += '&filter_author=' + encodeURIComponent(filter_author);
	}

	location = url;

	return false;
});
//--></script>
<?php echo $footer; ?>