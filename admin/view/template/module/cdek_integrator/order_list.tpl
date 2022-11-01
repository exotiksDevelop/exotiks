<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

	<div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="javascript:void(0)" id="send-orders" class="btn btn-primary"><?php echo $button_create; ?></a>
				<a href="<?php echo $cancel; ?>" class="btn btn-primary"><?php echo $button_cancel; ?></a>
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
    <div class="alert alert-success"><?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
	  	<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
	   	</div>

	   	<div class="panel-body">
	  
		  	<div class="well filter">
		  		<div class="row">
		  			<div class="col-sm-4">

		  				<div class="form-group">
		  					<label class="control-label" for="input-name"><?php echo $column_order_id; ?></label>
		  					<input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $column_order_id; ?>" id="input-name" class="form-control">
		  				</div>

		  				<div class="form-group">
		  					<label class="control-label" for="input-model"><?php echo $column_customer; ?></label>
		  					<input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $column_customer; ?>" class="form-control">
		  				</div>
		  			</div>
		  			<div class="col-sm-4">
		  				<div class="form-group">
		  					<label class="control-label"><?php echo $column_total; ?></label>
		  					<input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $column_total; ?>"class="form-control">
		  				</div>
		  				<div class="form-group">
		  					<label class="control-label"><?php echo $column_date_added; ?></label>
		  					<input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" data-date-format="YYYY-MM-DD" placeholder="<?php echo $column_date_added; ?>" class="form-control date">
		  				</div>
		  			</div>
		  			<div class="col-sm-4">
		  				<div class="form-group">
		  					<label class="control-label"><?php echo $column_status; ?></label>
		  					<select name="filter_order_status_id" class="form-control">
		  						<option value="*"></option>
		  						<?php foreach ($order_statuses as $order_status) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" <?php if ($order_status['order_status_id'] == $filter_order_status_id) echo 'selected="selected"'; ?>><?php echo $order_status['name']; ?></option>
									<?php } ?>
		  					</select>
		  				</div>
		  				<button type="button" onclick="filter();" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
		  			</div>
		  		</div>
		  	</div>

		  	<table class="list table table-bordered table-hover">
					<thead>
						<tr>
						<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
						<td class="right"><a href="<?php echo $sort_order; ?>" <?php if ($sort == 'o.order_id') echo 'class="' . $order . '"'; ?>><?php echo $column_order_id; ?></a></td>
						<td class="left"><a href="<?php echo $sort_customer; ?>" <?php if ($sort == 'customer') echo 'class="' . $order . '"'; ?>><?php echo $column_customer; ?></a></td>
						<td class="left"><a href="<?php echo $sort_status; ?>" <?php if ($sort == 'status') echo 'class="' . $order . '"'; ?>><?php echo $column_status; ?></a></td>
						<td class="right"><a href="<?php echo $sort_total; ?>" <?php if ($sort == 'o.total') echo 'class="' . $order . '"'; ?>><?php echo $column_total; ?></a></td>
						<td class="left"><a href="<?php echo $sort_date_added; ?>" <?php if ($sort == 'o.date_added') echo 'class="' . $order . '"'; ?>><?php echo $column_date_added; ?></a></td>
						<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($orders) { ?>
						<?php foreach ($orders as $order) { ?>
						<tr>
							<td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" <?php if ($order['selected']) echo 'checked="checked"'; ?> /></td>
							<td class="right"><?php echo $order['order_id']; ?></td>
							<td class="left"><?php echo $order['customer']; ?></td>
							<td class="left"><?php echo $order['status']; ?></td>
							<td class="right"><?php echo $order['total']; ?></td>
							<td class="left"><?php echo $order['date_added']; ?></td>
							<td class="right action">
								<?php foreach ($order['action'] as $action) { ?>
								<a class="btn btn-primary" data-toggle="tooltip" data-original-title="<?php echo $action['text']; ?>" href="<?php echo $action['href']; ?>"><?php echo (isset($action['icon'])) ? $action['icon'] : $action['text']; ?></a> 
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
						<?php } else { ?>
						<tr>
							<td class="center" colspan="8"><?php echo $text_no_results; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<div class="pagination">
					<?php if ($pagination) { ?>
						<div class="limit">
							<select onchange="location = $(this).val();">
								<?php foreach ($limits as $key => $url) { ?>
								<option <?php if ($limit == $key) echo 'selected="selected"'; ?> value="<?php echo $url; ?>"><?php echo $key; ?></option>
								<?php } ?>
							</select>
						</div>
					<?php } ?>	
					<?php echo $pagination; ?>
				</div>
		  </div>
	  </div>



  </div>
</div>

<script type="text/javascript"><!--

$('#send-orders').click(function(event){
	
	var orders = '';
	
	$('input[name*=\'selected\']:checked').each(function(){
		//orders.push($(this).val());
		orders += '&orders[]=' + $(this).val();
	})
	
	//alert(orders);
	
	if (orders.length) {
		window.location = '<?php echo $create; ?>' + orders;
	}
	
});

function filter() {
	url = 'index.php?route=module/cdek_integrator/order&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
	
	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();
	
	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
				
	location = url;
}
//--></script>  
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datetimepicker({
		pickTime: false
	})
});
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<script type="text/javascript"><!--
/*$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'filter_customer\']').catcomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						category: item.customer_group,
						label: item.name,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_customer\']').val(ui.item.label);
						
		return false;
	}
});*/
//--></script> 
<?php echo $footer; ?>