<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">

		<button type="button" id="button-add-wb" data-toggle="modal" data-target="#wb-multiply" form="form-product" formaction="<?php echo $add_product_wildberries; ?>" data-toggle="tooltip" title="Добавить в Wildberries" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> WB Добавить</button>
		<a class="btn btn-warning" id="updateProducts" href="<?php echo $syncProduct; ?>" title="Обновить цены и остатки на WB"><i class="fa fa-refresh"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
		<div class="pull-right"><a href="" id="button-add-wb" data-toggle="modal" data-target="#wb-help"><i class="fa fa-question-circle"></i></a></div>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-sku"><?php echo $entry_sku; ?></label>
                <input type="text" name="filter_sku" value="<?php echo $filter_sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-barcode">Баркод:</label>
                <input type="text" name="filter_barcode" value="<?php echo $filter_barcode; ?>" placeholder="Баркод:" id="input-barcode" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3 ">
			        <div class="form-group">
                <label class="control-label" for="input-category"><?php echo $column_category; ?></label>
                <select name="filter_category" id="input-category" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($categories as $category) { ?>
                  <?php if ($category['category_id']==$filter_category) { ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>">&nbsp;&nbsp;<?php echo $category['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group hidden">
                <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
              </div>
              <div class="form-group hidden">
                <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
              </div>
            </div>
			
            <div class="col-sm-3">
			        <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group hidden">
                <label class="control-label" for="input-image"><?php echo $entry_image; ?></label>
                <select name="filter_image" id="input-image" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_image) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_image && !is_null($filter_image)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-sm-3 ">
			        <div class="form-group">
                <label class="control-label" for="input-store"><?php echo $column_wb_store; ?></label>
                <select name="filter_store" id="input-store" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($wb_stores as $key => $store) : ?>
                  <option value="<?= $store['wb_uuid'];?>" <?= $filter_store === $store['wb_uuid'] ? 'selected="selected"' : "" ;?> ><?= $store['wb_store_name']; ?> </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="image-col text-center"><?php echo $column_image; ?></td>
                  <td class="name-col text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
				    <td class="text-left hidden">Категория на сайте</td>
                  <td class="text-left" style="width: 100px;"><?php if ($sort == 'p.model') { ?>
                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>
				    <td class="text-left" style="width: 100px;">Артикул</td>
				    <td class="text-left">Остаток</td>
            <td class="text-left">Баркоды</td>
				    <td class="text-left" style="width: 270px;">nmId</td>
                  <td class="text-right"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
                  <td class="text-left" style="width: 500px;"><h4><?php echo $product['name']; ?></h4>
					  <?php foreach ($categories as $category) { ?>
						<?php if (in_array($category['category_id'], $product['category'])) { ?>
                    <div><small><?php echo $category['name'];?></small></div>
                    <?php } ?>
                    <?php } ?></td>
                  <td class="text-left hidden">
					<?php foreach ($categories as $category) { ?>
						<?php if (in_array($category['category_id'], $product['category'])) { ?>
                    <div><?php echo $category['name'];?></div>
                    <?php } ?>
                    <?php } ?>
				  </td>
                  <td class="text-left"><?php echo $product['model']; ?></td>
                  <td class="text-left"><?php echo $product['sku']; ?></td>
                  <td class="text-left"><?php echo $product['quantity']; ?></td>
                  <td class="text-left"><?= $product['wb_brcds'];?></td>
                  <td class="text-left"><?php echo $product['wb_id'];?></td>
                  <td class="text-right"><?php if ($product['special']) { ?>
                    <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                    <div class="text-danger"><?php echo $product['special']; ?></div>
                    <?php } else { ?>
                    <?php echo $product['price']; ?>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['status']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
    </div>
  </div>
  <!-- Modal -->
		<div id="wb-multiply" class="modal fade" role="dialog">
			
			<div class="modal-dialog modal-lg">
				<input type="hidden" name="wb_product_schema" value="" />
				<input type="hidden" name="wb_nom_product_schema" value="" />
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Создать товар(ы) на Wildberries</h4>
					</div>
					<div class="modal-body">
						<div id="wb-modal-body">
							<div class="row">
							<div class="col-sm-6">
								<div class="product_data" data-spy="affix" data-offset-top="250">
									<div class="prod_atributes_list"></div>
								</div>
							</div>
							<div class="col-sm-6" id="wb-spec">
                <div>
                  <select id="wb-multiple-checkboxes" data-mdb-placeholder="Example placeholder" class="select" multiple="multiple">
                    <?php foreach ($wb_stores as $key => $store) : ?>
                    <option value="<?= $store['wb_uuid'];?>"><?= $store['wb_store_name']; ?> </option>
                    <?php endforeach; ?>
                  </select>
                </div>
								<div class="form-group first">
									<label class="control-label">Выберите категорию Wildberries</label>
									<input id="wb_object_list" class="form-control" name="wb_object" type="text" autocomplete="off" />
								</div>
							</div>
							</div>
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
						<button type="button" class="btn btn-primary" id="batch_create">Добавить на Wildberries</button>
					</div>
				</div>
			</div>
		</div>
		<div id="wb-help" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Особенности работы с товарами Wildberries</h4>
					</div>
					<div class="modal-body">
						<div id="wb-modal-body">
							Здесь будет справка по модулю
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
					</div>
				</div>

			</div>
		</div>
    <style>
    .modal-dialog {
      width: 960px;
    }
    .prod_atributes_list p {
      margin: 0;
    }
	.prod_atributes_list {
		overflow-y: auto;
		height: 600px;
    }
    .affix {
      position: fixed;
      top: 0;
      left: 0;
      width: 50%;
      padding: 15px;
      background-color: 'white';
      z-index: 9999;
      display: none;
    }
    </style>
		<link rel="stylesheet" href="view/stylesheet/jquery-ui.1.12.1.css">
		<script src="view/javascript/jquery-ui.1.12.1.js"></script>
		<script>
    var target = $('.product_data')
    $(target).after('<div class="affix" id="affix"></div>')

    var affix = $('.affix')
    affix.append(target.clone(true))

    // Show affix on scroll.
    var element = document.getElementById('affix')
    if (element !== null) {
      var position = target.position()
      console.log(position.left);
      document.getElementById('wb-multiply').addEventListener('scroll', function () {
        console.log( $(document.getElementById('wb-multiply')).scrollTop());
        var height = $(document.getElementById('wb-multiply')).scrollTop();
        affix.css('top', height);
        if (height > position.top) {
          target.css('visibility', 'hidden');
          affix.css('display', 'block');
        } else {
          affix.css('display', 'none');
          target.css('visibility', 'visible');
        }

      })
    }
		$( "#wb_object_list" ).autocomplete({
                source: function (request, response) {
                    var url = 'https://content-suppliers.wildberries.ru/ns/characteristics-configurator-api/content-configurator/api/v1/config/get/object/list?pattern=' + request.term + '&lang=ru';
                    $.ajax({
                        url: url,
                        success: function (data) {
                            var result = data.data;
                            var transformed = $.map(result, function (el) {
                                return {
                                    label: el.name,
                                    id: el.name
                                };
                            });
                            response(transformed);
                        },
                        error: function () {
                            response([]);
                        }
                    });
                },
				 select: function( event, ui ) {
                    var url = 'https://content-suppliers.wildberries.ru/ns/characteristics-configurator-api/content-configurator/api/v1/config/get/object/translated?name=' + ui.item.label + '&lang=ru'
                    $.ajax({
                        url: url,
                        success: function (data) {
                            var result = data;
                            if (result.data && result.data.addin) {
                                $("#wb-spec .form-group").not('.first').each(function(i, el) {
                                    $(el).remove();
                                });
                                $('[name=wb_product_schema]').val(JSON.stringify(result.data.addin));
                                if (result.data.nomenclature && result.data.nomenclature.addin) {
                                    $('[name=wb_nom_product_schema]').val(JSON.stringify(result.data.nomenclature.addin));
                                    result.data.addin = result.data.addin.concat(result.data.nomenclature.addin);
                                }
                                result.data.addin.push(
                                    {
                                        required: true,
                                        type: 'Артикул поставщика'
                                    }
                                );
                                result.data.addin.forEach(function(el, i) {
                                    var child = '<div class="form-group ' + (el.required ? "required" : "") + '">';
                                    var iid = el.useOnlyDictionaryValues ? 'wb_' + i + el.dictionary.replace('/', '') : el.type;
                                    var suggest = '';
                                    if (el.required) {
                                        suggest += "Обязательное к заполнению поле\n";
                                    }
                                    if (el.useOnlyDictionaryValues) {
                                        suggest += "Необходимо значение из словаря\n";
                                    }
                                    if (el.isNumber) {
                                        suggest += "Числовое значение\n";
                                    }
                                    if (el.maxCount) {
                                        suggest += "Максимальное значение " + el.maxCount + "\n";
                                    }
                                    suggest += !suggest.length ? 'Обычное строковое значение' : '';
                                    child += '<label class="control-label" for="input-points"><span data-toggle="tooltip" title="' + suggest + '">' + el.type + ':</span></label>';
                                    child += '<div><input id="' + iid + '" class="form-control" name="' + el.type + '" type="' + (el.isNumber? "number" : "text") + '" ' + (el.isNumber && el.maxCount ? 'max="' + maxCount + '"' : '') + ' autocomplete="off"></div>';
                                    if(el.useOnlyDictionaryValues) {
                                        child += "<script>";
                                        child += '$( "#' + iid + '" ).autocomplete({';
                                        child += ' source: function (request, response) {';
                                        child += '      var url = "https://content-suppliers.wildberries.ru/ns/characteristics-configurator-api/content-configurator/api/v1/directory' + el.dictionary + '?pattern=" + request.term + "&lang=ru&top=10&subject=" + $("#wb_object_list").val();';
                                        child +=        'var tnved = "' + el.dictionary.replace("/", '') + '" == "tnved";';
                                        child +=        '$.ajax({';
                                        child +=            'url: url,';
                                        child +=            'success: function (data) {'
                                        child +=                'if (data.data && data.data.length) {';
                                        child +=                'var result = data.data;';
                                        child +=                'var transformed = $.map';
                                        child +=                    '(result, function (el) {';
                                        child +=                        'return tnved ? {label:el.tnvedCode,name:el.tnvedCode} : {label: el.translate, id: el.translate};';
                                        child +=                    '});';
                                        child +=                'response(transformed);';
                                        child +=                '} else {response([])};';
                                        child +=            '},'
                                        child +=            'error: function() {response([]);}';
                                        child +=        '});';
                                        child += '}';
                                        child += '});';
                                        child += "</";
                                        child += "script>";
                                    }
                                    child += '</div>';
                                    $('#wb-spec').append(child);
                                });
                            }
                        },
                        error: function () {
                            response([]);
                        }
                    });
                }
			});
			$('#button-add-wb').on('click', function() {
				$('.prod_atributes_list').html('');
				if($('#form-product [name="selected[]"]:checked').length) {
          var ids = [];
          $('#form-product [name="selected[]"]:checked').each(function(i, el) {
            ids.push($(el).val());
          });
					var data = {
						ids: ids
					}
					//console.log(data);
					var url = "<?= htmlspecialchars_decode($wb_atributes);?>";
					$.ajax({
                        url: url,
                        type: 'post',
                        data: data,
						beforeSend: function() {
							$('.prod_atributes_list').html('Грузим');
                        },
                        success: function(data) {
                            if(data) {
								$('.prod_atributes_list').html(data);
							}
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            $('.prod_atributes_list').html('Ошибка');
                        }
                    });
				}
			});
			$('#batch_create').on('click', function() {
        if(!$('#wb-multiple-checkboxes').val()) {
          alert('Выберите wb профиль');
          return;
        }
				if (Array.from($('#wb-modal-body .required input')).every( function(el) {return el.value;}) ){
					var ids = $.map($('#form-product [name="selected[]"]:checked'), function (el) { return $(el).val(); });
					var data = {
						ids: ids,
            object_name: $('#wb_object_list').val(),
						schema: $('[name=wb_product_schema]').val(),
						nom_schema: $('[name=wb_nom_product_schema]').val(),
            wb_stores: $('#wb-multiple-checkboxes').val(),
					}
            $('#wb-modal-body .form-group input').each(function(i, el) { if($(el).val()) data[$(el).attr('name')] = $(el).val() });
					  var url = "<?= htmlspecialchars_decode($wb_create_product_url);?>";
            $.ajax({
              url: url,
              type: 'post',
              dataType: 'json',
              data: data,
              beforeSend: function() {
              },
              complete: function() {
              },
              success: function(json) {
                  if(json.error.length) {
                      json.error.forEach(function(el){
                          if ($('[name="' + el.type + '"]').length) {
                              $('[name="' + el.type + '"]').closest('.form-group').addClass('has-error');
                          } else if (el.type == 'global') {
                              $('#tab-wb-content .global-error').addClass('has-error');
                              $('#tab-wb-content .global-error').text(el.error);
                          }
                      });
                  } else if(json.result) {
                      $('#wb-multiply [data-dismiss="modal"]').eq(0).click();
                      if (json.result.nmId) {
                          $("[name='wb_nmId']").val(json.result.nmId)
                      }
                      if (json.result.chrt_id) {
                          $("[name='wb_chrt_id']").val(json.result.chrt_id)
                      }
                  }
              },
              error: function(xhr, ajaxOptions, thrownError) {
                  console.log(thrownError);
              }
          });
				} else {
					console.log('Не все обязательные поля заполнены');
				}
				
			});
		</script>
		<style>
			ul.ui-menu.ui-widget.ui-widget-content {
				z-index: 9999;
			}
			#wb-modal-body .col-sm-12 {
				float: initial !important;
			}
			.image-col{
				width:100px;
			}
			.name-col{
				width:40%;
			}
			.p5{
				padding:3px 7px 3px 7px;
				margin-bottom:3px;
			}
			.flag{
				padding-left:10px;
			}
      .delete-connection-icon {
        color: #f56b6b;
        margin-left: 10px;
        font-size: 15px;
        cursor: pointer;
      }
		</style>
  <script type="text/javascript"><!--
$('#button-update-wb').on('click', function(e) {
	$('#form-product').attr('action', this.getAttribute('formAction'));

            	if (confirm('<?php echo $text_confirm; ?>')) {
            		$('#form-product').submit();
            	} else {
            		return false;
            	}
            }); 
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/wildberries&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
 	var filter_sku = $('input[name=\'filter_sku\']').val();

	if (filter_sku) {
		url += '&filter_sku=' + encodeURIComponent(filter_sku);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

  var filter_barcode = $('input[name=\'filter_barcode\']').val();

  if (filter_barcode) {
    url += '&filter_barcode=' + encodeURIComponent(filter_barcode);
  }

	var filter_price = $('input[name=\'filter_price\']').val();

	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}

	var filter_store = $('select[name=\'filter_store\']').val();

	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}

	var filter_category = $('select[name=\'filter_category\']').val();

  if (filter_category != '*') {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}

	var filter_quantity = $('input[name=\'filter_quantity\']').val();

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_image = $('select[name=\'filter_image\']').val();

  if (filter_image != '*') {
    url += '&filter_image=' + encodeURIComponent(filter_image);
  }

	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/wildberries/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/wildberries/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
function changeQuantityFlag(event, id) {
  var checked = $(event.target).prop('checked') ? 1 : 0;
  $.ajax({
    url: 'index.php?route=catalog/wildberries/quantityflag&token=<?php echo $token; ?>',
    dataType: 'json',
    method: 'POST',
    data: {
      id: id,
      quantity_flag: checked
    },
    success: function(json) {
      console.log(json);
    }
  });
}
function deleteConnection(event, id) {
  if(confirm('Вы уверены что хотите удалить связь с товаром?')) {
    $.ajax({
      url: '<?= htmlspecialchars_decode($delete_connection);?>',
      dataType: 'json',
      method: 'POST',
      data: { wb_id: id },
      success: function(json) {
        console.log(json);
        if(json.status) {
          $('#' + id).next().remove();
          $('#' + id).hide();
        } else {
          alert('Невозможно удалить связь');
        }
      }
    });
  }
}
//--></script>
<script src="view/javascript/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="view/stylesheet/bootstrap-multiselect.css">
<script>
$(document).ready(function() {
  $('#wb-multiple-checkboxes').multiselect({
    includeSelectAllOption: false,
    nonSelectedText: '<span>Выберите аккаунты</span>',
    enableHTML: true,
  });
});
</script>
</div>
<?php echo $footer; ?>
