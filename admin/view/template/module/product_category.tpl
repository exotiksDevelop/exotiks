<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
          <!--<div class="form-group">
            <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="category" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
              <div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($categories as $category) { ?>
                <div id="featured-product<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
                  <input type="hidden" value="<?php echo $category['category_id']; ?>" />
                </div>
                <?php } ?>
              </div>
              <input type="hidden" name="product_category_product" value="<?php echo $product_category_product; ?>" class="form-control" />
            </div>
          </div>-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="product_category_status" id="input-status" class="form-control">
                <?php if ($product_category_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>          
          <table id="module" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-right">#</td>
                <td class="text-left"><?php echo $entry_category; ?></td>
                <td class="text-left"><?php echo $entry_name; ?></td>
                <td class="text-left"><?php echo $entry_limit; ?></td>
                <td class="text-left"><?php echo $entry_image; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
			<?php // var_dump($categories); die;?>
			
              <?php $module_row = 1; ?>
              <?php foreach ($product_category_modules as $product_category_module) { ?>
              <tr id="module-row<?php echo $product_category_module['key']; ?>">
                <td class="text-right"><?php echo $module_row; ?></td>
                <td class="text-left"><i>(autocomplete)</i><input type="text" data-token="<?php echo $product_category_module['key']; ?>" name="category_<?php echo $product_category_module['key']; ?>" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" value="<?php echo $product_category_module['label'] ?>"/>
              <input type="hidden" data-autocomplete="yes" name="product_category_module[<?php echo $product_category_module['key']; ?>][category]" value="<?php echo $product_category_module['category'] ?>" class="form-control" /></td>
                <td class="text-left"><input type="text" name="product_category_module[<?php echo $product_category_module['key']; ?>][name]" value="<?php echo $product_category_module['name']; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" /></td>
                <td class="text-left"><input type="text" name="product_category_module[<?php echo $product_category_module['key']; ?>][limit]" value="<?php echo $product_category_module['limit']; ?>" placeholder="<?php echo $entry_limit; ?>" class="form-control" /></td>
                <td class="text-left"><input type="text" name="product_category_module[<?php echo $product_category_module['key']; ?>][width]" value="<?php echo $product_category_module['width']; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
                  <input type="text" name="product_category_module[<?php echo $product_category_module['key']; ?>][height]" value="<?php echo $product_category_module['height']; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  <?php if (isset($error_image[$product_category_module['key']])) { ?>
                  <div class="text-danger"><?php echo $error_image[$product_category_module['key']]; ?></div>
                  <?php } ?></td>
                <td class="text-left"><button type="button" onclick="$('#module-row<?php echo $product_category_module['key']; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $module_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="5"></td>
                <td class="text-left"><button type="button" onclick="addModule();" data-toggle="tooltip" title="<?php echo $button_module_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
function autocomplete(field, token)
{
	$(field).autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',			
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			
			/*$('#featured-product_' + item['value']).remove();
			
			$('#featured-product_' + token).append('<div id="featured-product_' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" value="' + item['value'] + '" /></div>');	
		
			data = $.map($('#featured-product input'), function(element) {
				return $(element).attr('value');
			});*/
			
			$(field).val(item['label']);
			
			$('input[name=\'product_category_module[' + token + '][category]\']').attr('value', item['value']);	
		}	
	});

	/*$('#featured-product_' + token).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	
		data = $.map($('#featured-product input'), function(element) {
			return $(element).attr('value');
		});
						
		$('input[name=\'product_category_product[' + token + '][category]\']\']').attr('value', data.join());	
	});*/

}

//autocomplete('input[data-autocomplete="yes"]', $('input#input-product').attr('data-token'));

$('input#input-product').each(function() {
  
  autocomplete('input[name="' + $(this).attr('name') + '"]', $(this).attr('data-token'));
  
});

//--></script> 
  <script type="text/javascript"><!--
function addModule() {
	var token = Math.random().toString(36).substr(2);
			
	html  = '<tr id="module-row' + token + '">';
	html += '  <td class="text-right">' + ($('tbody tr').length + 1) + '</td>';

	html += '<td class="text-left"><i>(autocomplete)</i><input data-token="' + token + '" type="text" name="product_category_module_' + token + '" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />';
    //html += '<div id="featured-product_' + token + '" class="well well-sm" style="height: 150px; overflow: auto;">';
    //html += '<input type="hidden" value="' + token + '" />';
    html += '</div>';
    html += '<input type="hidden" name="product_category_module[' + token + '][category]" value="" class="form-control" /></td>';
    html += '<td class="text-left"><input type="text" name="product_category_module[' + token + '][name]" placeholder="<?php echo $entry_name; ?>" class="form-control" /></td>';

	html += '  <td class="text-left"><input type="text" name="product_category_module[' + token + '][limit]" value="5" placeholder="<?php echo $entry_limit; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><input type="text" name="product_category_module[' + token + '][width]" value="200" placeholder="<?php echo $entry_width; ?>" class="form-control" /> <input type="text" name="product_category_module[' + token + '][height]" value="200" placeholder="<?php echo $entry_height; ?>" class="form-control" /></td>';	
	html += '  <td class="text-left"><button type="button" onclick="$(\'#module-row' + token + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#module tbody').append(html);

	autocomplete('input[name="product_category_module_' + token + '"]', token);
}
//--></script></div>
<?php echo $footer; ?>