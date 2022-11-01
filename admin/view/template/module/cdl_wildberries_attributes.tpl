<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<?php if ($error_warning) { ?>
				<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
			<?php if ($success) { ?>
				<div class="alert alert-success alert-dismissible" role="alert"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			<?php } ?>
			<div class="pull-right">
        <?php echo $btn_download_attr; ?>
				<div class="btn-group">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $text_menu; ?>&nbsp;<span class="caret"></span></button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="<?php echo $cancel; ?>"><?php echo $button_return_module; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_orders_wb; ?>"><?php echo $text_orders_wb; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_product; ?>"><?php echo $text_product; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_rima; ?>"><?php echo $text_rima; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_supplies; ?>" target="_blank"><?php echo $text_supplies; ?></a></li>
				  </ul>
				</div>
				<button type="submit" form="form-attributes" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
			</div>

			<h1><?php echo $heading_title_attributes; ?></h1>

			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit_attribute; ?></h3>
				</div>
				<div class="panel-body">
					<select class="form-control filter-category" id="category-select">
						<option value="*"><?php echo $text_select_catetory_wb; ?></option>
            <?php foreach ($cdl_wildberries_category as $cat_wb) { ?>
            <?php if ($cat_wb['filter_value'] == $filter_category) { ?>
            <option value="<?php echo $cat_wb['filter_value']; ?>" selected="selected"><?php echo $cat_wb['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $cat_wb['filter_value']; ?>"><?php echo $cat_wb['name']; ?></option>
            <?php } ?>
            <?php } ?>
					</select>
          <div class="row"></div>
          <div class="form-group">
            <form action="<?php echo $action; ?>" id="form-attributes" enctype="multipart/form-data" method="post" class="form-horizontal">
        		<table class="table table-striped table-bordered table-hover">
        			<thead>
        				<tr>
                  <td style="display:none;"></td>
                  <td><?php echo $text_attributes_wb; ?></td>
                  <td><?php echo $text_attributes_shop; ?></td>
                  <td style="width:200px;"><span data-toggle="tooltip" title="<?php echo $help_action; ?>"></span>&nbsp;<?php echo $text_action; ?></td>
									<td><span data-toggle="tooltip" title="<?php echo $help_attribut_is_defined; ?>"></span>&nbsp;<?php echo $text_is_defined; ?></td>
                  <td style="width:120px;"><?php echo $text_dictionary; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($wb_attributes)) { ?>
                  <?php foreach ($wb_attributes as $wb_attribute) { ?>
                    <tr>
                      <td>
												<b><?php echo $wb_attribute['type'];
													echo $wb_attribute['units'] ? ' (' . $wb_attribute['units'] . ')' : false;
													echo $wb_attribute['description'] ? '&nbsp;<i class="fa fa-info-circle" data-toggle="tooltip" title="' . $wb_attribute['description'] . '"></i>' : false;
													echo $wb_attribute['required'] ? '&nbsp;<span class="label label-warning">Обязательный</span>' : false;
													echo $wb_attribute['number'] ? '&nbsp;<span class="label label-primary" data-toggle="tooltip" title="Будут удалены все символы">Только число</span>' : false;
													echo $wb_attribute['only_dictionary'] ? '&nbsp;<span class="label label-primary">' . $text_only_dictionary . '</span>' : false;
													echo $wb_attribute['nomenclature_variation'] ? '&nbsp;<span class="label label-warning">Популярный</span>' : false;
													echo $wb_attribute['id'] ? '&nbsp;<span class="label label-danger" data-toggle="tooltip" title="Максимальное кол-во значений">' . $wb_attribute['id'] . '</span>' : false; ?></b>
											</td>
                      <td>
												<input type="text" data-id="<?php echo $wb_attribute['type']; ?>" class="form-control attr-search" value="<?php echo isset($wb_attribute['shop_name']) ? $wb_attribute['shop_name'] : false; ?>" <?php echo isset($wb_attribute['is_defined']) ? 'disabled' : false; ?> />
												<input type="hidden" name="attributes[<?php echo $wb_attribute['type']; ?>][shop]" value="<?php echo isset($wb_attribute['shop_id']) ? $wb_attribute['shop_id'] : false; ?>" />
											</td>
											<td>
												<?php if (!empty($wb_attribute['number'])) { ?>
												<div class="col-sm-12" style="padding-left:0px;padding-right:0px;">
													<div class="col-sm-7" style="padding-left:0px;padding-right:5px;">
													<select data-action="<?php echo $wb_attribute['type']; ?>" class="form-control" name="attributes[<?php echo $wb_attribute['type']; ?>][action]" <?php echo isset($wb_attribute['is_defined']) ? 'disabled' : false; ?> >
														<?php foreach ($actions as $key => $action) { ?>
															<?php if (isset($wb_attribute['action']) && $wb_attribute['action'] == $key) { ?>
																<option value="<?php echo $key; ?>" selected="selected"><?php echo $action; ?></option>
															<? } else { ?>
																<option value="<?php echo $key; ?>"><?php echo $action; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
													</div>
													<div class="col-sm-5" style="padding-left:0px;padding-right:0px;">
													<input type="text" data-action-value="<?php echo $wb_attribute['type']; ?>" class="form-control" name="attributes[<?php echo $wb_attribute['type']; ?>][action_value]" value="<?php echo isset($wb_attribute['action_value']) ? $wb_attribute['action_value'] : false; ?>" <?php echo isset($wb_attribute['is_defined']) ? 'disabled' : false; ?> />
													</div>
												</div>
												<?php } ?>
											</td>
											<td>
												<div class="col-sm-1" style="padding-left:0px;"><label class="checkbox-inline">
												<input type="checkbox" name="attributes[<?php echo $wb_attribute['type']; ?>][defined]" value="" data-ch="<?php echo $wb_attribute['type']; ?>" <?php echo isset($wb_attribute['is_defined']) ? 'checked' : false; ?> /></label>
												</div>
												<div class="col-sm-11" style="padding-left:0px;">
												<input type="text" name="attributes[<?php echo $wb_attribute['type']; ?>][value]" class="form-control" data-toggle="tooltip" title="<?php echo $text_placeholder_defined;?>" data-val="<?php echo $wb_attribute['type']; ?>" <?php echo isset($wb_attribute['value']) ? 'value="' . $wb_attribute['value'] . '"' : 'value="" disabled'; ?> />
												</div>
											</td>
                      <td>
												<?php echo ($wb_attribute['dictionary']) ? '<button type="button" class="btn btn-primary modal-dictionary" data-cat-btn="' . $filter_category . '" data-type-btn="' . $wb_attribute['type'] . '">' . $text_dictionary . '</button>' : false; ?>
												<input type="hidden" name="attributes[<?php echo $wb_attribute['type']; ?>][required]" value="<?php echo $wb_attribute['required']; ?>" />
												<input type="hidden" name="attributes[<?php echo $wb_attribute['type']; ?>][nomenclature]" value="<?php echo $wb_attribute['nomenclature']; ?>" />
												<input type="hidden" name="attributes[<?php echo $wb_attribute['type']; ?>][nomenclature_variation]" value="<?php echo $wb_attribute['nomenclature_variation']; ?>" />
												<input type="hidden" name="attributes[<?php echo $wb_attribute['type']; ?>][id]" value="<?php echo $wb_attribute['id']; ?>" />
												<input type="hidden" name="attributes[<?php echo $wb_attribute['type']; ?>][units]" value="<?php echo $wb_attribute['units']; ?>" />
												<input type="hidden" name="attributes[<?php echo $wb_attribute['type']; ?>][number]" value="<?php echo $wb_attribute['number']; ?>" />
												<input type="hidden" name="attributes[<?php echo $wb_attribute['type']; ?>][dictionary]" value="<?php echo $wb_attribute['dictionary']; ?>" />
												<input type="hidden" name="attributes[<?php echo $wb_attribute['type']; ?>][only_dictionary]" value="<?php echo $wb_attribute['only_dictionary']; ?>" />
											</td>
                    </tr>
                  <?php } ?>
									<tr><td colspan="5">
									<button type="button" class="btn btn-primary btn-sm btn-block reload-attribute"><?php echo $text_reload_attribute; ?></button></td></tr>
                <?php } else { ?>
                  <tr><td colspan="5"><?php echo $text_select_catetory_wb; ?></td></tr>
                <?php } ?>
              </tbody>
            </table>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo $footer; ?>

<script type="text/javascript"><!--
$('.download-attr').on('click', function() {
  $.ajax({
    url: '<?php echo $url_download_attr; ?>',
    type: 'post',
    data: 'pass=<?php echo $cdl_wildberries_pass; ?>',
		beforeSend: function() {
			$('.download-attr').button('loading');
		},
		success: function(html) {
			$('.container-fluid').prepend('<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> ' + html + '</div>');
			setTimeout(function(){
				location.reload();
			}, 2000);
    }
  });
});

$('.reload-attribute').on('click', function() {
  $.ajax({
    url: '<?php echo $url_reload_attribute; ?>',
    success: function(html) {
      location.reload();
    }
  });
});

$('.filter-category').change(function() {
  var category = $('.filter-category').val();
	if (category != '*') {
		url = '<?php echo $url_get_attr; ?>&category=' + encodeURIComponent(category);
    location = url;
	}
});

// Живой поиск атрибуов магазина
$('.attr-search').focus(function() {
  type = $(this).attr('data-id');
});

$('.attr-search').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        json.unshift(
          {
            name: '-- очистить --',
            attribute_id: null
          }
        );
        json.push(
          {
            attribute_group: 'Поле Opencart',
            name: 'Вес, г.',
            attribute_id: 'weight'
          },
					{
            attribute_group: 'Поле Opencart',
            name: 'Высота, см.',
            attribute_id: 'height'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'Длина, см.',
            attribute_id: 'length0'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'Ширина, см.',
            attribute_id: 'width'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'Артикул',
            attribute_id: 'sku'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'Модель',
            attribute_id: 'model'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'MPN',
            attribute_id: 'mpn'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'ISBN',
            attribute_id: 'isbn'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'EAN',
            attribute_id: 'ean'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'JAN',
            attribute_id: 'jan'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'UPC',
            attribute_id: 'upc'
          },
          {
            attribute_group: 'Поле Opencart',
            name: 'Наименование',
            attribute_id: 'name'
          }
        );
        response($.map(json, function(item) {
          return {
            category: item.attribute_group,
            label: item.name,
            value: item.attribute_id
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[data-id=\'' + window.type + '\']').val(item['label']);
    $('input[name=\'attributes[' + window.type + '][shop]\']').val(item['value']);
  }
});

// Вызываем модальное
$(function() {
	myModal = new ModalApp.ModalProcess({ id: 'myModal'});
	myModal.init();

	$('.modal-dictionary').on('click', function(e) {
		e.preventDefault();
		type = $(this).attr('data-type-btn');
		category = $(this).attr('data-cat-btn');
		shop_id = $('input[name=\'attributes[' + window.type + '][shop]\']').val();
		if (shop_id || $('input[data-ch=\'' + window.type + '\']').prop('checked')) {
			$.get('<?php echo $url_modal; ?>&type=' + encodeURIComponent(type) + '&category=' + encodeURIComponent(category) + '&shop_id=' + shop_id,
				function(data) {
				var data = JSON.parse(data);
				myModal.changeTitle(data['title']);
				myModal.changeBody(data['body']);
				myModal.changeFooter(data['footer']);
				myModal.showModal();
			});
		} else {
			alert('Выберите атрибут магазина или установите чекбокс');
		}
	});
});

// SCRIPT MODAL
var ModalApp = {};
ModalApp.ModalProcess = function (parameters) {
	this.id = parameters['id'] || 'modal';
	this.selector = parameters['selector'] || '';
	this.title = parameters['title'] || 'Заголовок модального окна';
	this.body = parameters['body'] || 'Содержимое модального окна';
	this.footer = parameters['footer'] || '<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>';
	this.content = '<div id="'+this.id+'" class="modal fade" tabindex="-1" role="dialog">'+
		'<div class="modal-dialog" role="document" style="width:80%;">'+
			'<div class="modal-content">'+
				'<div class="modal-header">'+
					'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<h4 class="modal-title">'+this.title+'</h4>'+
				'</div>'+
				'<div class="modal-body">'+this.body+'</div>'+
				'<div class="modal-footer">'+this.footer+'</div>'+
			'</div>'+
		'</div>'+
	'</div>';
	this.init = function() {
		if ($('#'+this.id).length==0) {
			$('body').prepend(this.content);
		}
		if (this.selector) {
			$(document).on('click',this.selector, $.proxy(this.showModal,this));
		}
	}
}
ModalApp.ModalProcess.prototype.changeTitle = function(content) {
	$('#' + this.id + ' .modal-title').html(content);
};
ModalApp.ModalProcess.prototype.changeBody = function(content) {
	$('#' + this.id + ' .modal-body').html(content);
};
ModalApp.ModalProcess.prototype.changeFooter = function(content) {
	$('#' + this.id + ' .modal-footer').html(content);
};
ModalApp.ModalProcess.prototype.showModal = function() {
	$('#' + this.id).modal('show');
};
ModalApp.ModalProcess.prototype.hideModal = function() {
	$('#' + this.id).modal('hide');
};
ModalApp.ModalProcess.prototype.updateModal = function() {
	$('#' + this.id).modal('handleUpdate');
};

// Активация полей
$('input[type="checkbox"]').on('change', function(){
	type = $(this).attr('data-ch');
	if (this.checked){
		$('input[data-id=\'' + window.type + '\']').prop("disabled", true);
		$('select[data-action=\'' + window.type + '\']').prop("disabled", true);
		$('input[data-action-value=\'' + window.type + '\']').prop("disabled", true);
		$('input[data-val=\'' + window.type + '\']').prop("disabled", false);
	} else {
		$('input[data-id=\'' + window.type + '\']').prop("disabled", false);
		$('select[data-action=\'' + window.type + '\']').prop("disabled", false);
		$('input[data-action-value=\'' + window.type + '\']').prop("disabled", false);
		$('input[data-val=\'' + window.type + '\']').prop("disabled", true);
	}
})

$('#category-select').select2({
	language: 'ru'
});
//--></script>
