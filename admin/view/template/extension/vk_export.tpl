<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <div class="pull-right">v<?php echo $version; ?></div>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    
    <div class="modal fade" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
          </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
      
      <?php if ($warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
      <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
      
      <?php 
        if ($vk_export_debug_mode) {
            echo '<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> Режим отладки включён. Не забудьте выключить его после отладочных действий.
            <a href="' . $get_log . '" target="_blank">Скачать лог</a>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>';
        }
      ?>
  
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
              
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $column_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $column_album; ?></label>
                <?php echo $category_select; ?>
              </div>
            </div>
            
            <?php if ($show_column_wall) { ?>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $column_wall; ?></label>
                <select name="filter_export_wall" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_export_wall) { ?>
                  <option value="1" selected="selected"><?php echo $text_vk_export_on; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_vk_export_on; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_export_wall) && !$filter_export_wall) { ?>
                  <option value="0" selected="selected"><?php echo $text_vk_export_off; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_vk_export_off; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <?php } ?>
            
            <?php if ($show_column_albums) { ?>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $column_albums; ?></label>
                <select name="filter_export_albums" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_export_albums) { ?>
                  <option value="1" selected="selected"><?php echo $text_vk_export_on; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_vk_export_on; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_export_albums) && !$filter_export_albums) { ?>
                  <option value="0" selected="selected"><?php echo $text_vk_export_off; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_vk_export_off; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <?php } ?>
            
            <?php if ($show_column_market) { ?>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $column_market; ?></label>
                <select name="filter_export_market" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_export_market) { ?>
                  <option value="1" selected="selected"><?php echo $text_vk_export_on; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_vk_export_on; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_export_market) && !$filter_export_market) { ?>
                  <option value="0" selected="selected"><?php echo $text_vk_export_off; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_vk_export_off; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <?php } ?>
            
        </div>
            <div class="row">
                <?php if ($show_column_producer) { ?> 
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="input-manufacturer"><?php echo $column_producer; ?></label>
                      <input type="text" name="filter_manufacturer_name" value="<?php echo $filter_manufacturer_name ?>" placeholder="<?php echo $column_producer; ?>" id="input-manufacturer" class="form-control" />
                  </div>
                </div>
                <?php } ?>
                
                <?php if ($show_column_model) { ?> 
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="input-model"><?php echo $column_model; ?></label>
                    <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $column_model; ?>" id="input-model" class="form-control" />
                  </div>
                </div>
                <?php } ?>
                
                <?php if ($show_column_price) { ?>  
                <div class="col-sm-2">    
                  <div class="form-group">
                    <label class="control-label" for="input-price"><?php echo $column_price; ?></label>
                    <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $column_price; ?>" id="input-price" class="form-control" />
                  </div>
                  
                </div>
                <?php } ?>
                
                <?php if ($show_column_quantity) { ?>  
                <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label" for="input-quantity"><?php echo $column_quantity; ?></label>
                    <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $column_quantity; ?>" id="input-quantity" class="form-control" />
                  </div>
                </div>
                <?php } ?>
                
                <?php if ($show_column_status) { ?>  
                <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label" for="input-status"><?php echo $column_status; ?></label>
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
                </div>
                <?php } ?>
                
                <?php if ($show_column_date_added) { ?>
                <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label" for="input-date_added"><?php echo $column_date_added; ?></label>
                    <div class="input-group date">
                        <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $column_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date_added" class="form-control" />
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                  </div>
                </div>
                <?php } ?>
                
              </div>
            <div class="row">
                <div class="col-sm-12" align="right">
                    <div class="form-group" align="right">
                        <div><button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button></div>
                  </div>
                </div>
            </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" id="form-product">
            
            <p class="text-right">
              <a id="extra_settings_button" onClick="$('#extra_settings').toggle();" class="btn btn-default btn-sm pull-left"><i class="fa fa-cog"></i> дополнительно</a>
              
              <div class="clearfix">
              
                  <?php if ($show_column_market) { ?>
                  <div class="pull-right">
                      <b>Товары:</b>
                      <div class="btn-group" role="group" aria-label="...">
                          <button id="market_action" type="button" class="btn btn-primary" title="Экспортировать"><i class="fa fa-share"></i></button>
                          <button id="delete_market_action" type="button" class="btn btn-danger" title="Удалить"><i class="fa fa-trash"></i></button>
                      </div>
                  </div>
                  <?php } ?>
                  
                  <?php if ($show_column_wall) { ?>
                  <div class="pull-right" style="padding-right:25px;">
                      <b>Стена:</b>
                      <div class="btn-group" role="group" aria-label="...">
                          <button id="wallpost_action" type="button" class="btn btn-primary" title="Экспортировать"><i class="fa fa-share"></i></button>
                          <button id="delete_wall_action" type="button" class="btn btn-danger" title="Удалить"><i class="fa fa-trash"></i></button>
                      </div>
                  </div>
                  <?php } ?>
                  
                  <?php if ($show_column_albums) { ?>
                  <div class="pull-right" style="padding-right:25px;">
                      <b>Альбомы:</b>
                      <div class="btn-group" role="group" aria-label="...">
                          <button id="export_action" type="button" class="btn btn-primary" title="Экспортировать"><i class="fa fa-share"></i></button>
                          <button id="reexport_action" type="button" class="btn btn-danger" title="Удалить"><i class="fa fa-trash"></i></button>
                      </div>
                  </div>
                  <?php } ?>
               
              </div>
            </p>
            
            <div class="well" id="extra_settings" style="<?php echo $extra_settings ?>">
                <div class="row">
                <div class="form-group">
                    <label class="col-sm-3 control-label text-right">Экспорт в произвольный альбом: </label>
                    <div class="col-sm-9">
                        <input type="text" name="extra_album" value="<?php echo $extra_album ?>" class="form-control" /> 
                        <div class="help">Здесь можно указать прямую ссылку на альбом, в который вы хотите экспортировать товары</div>
                        <a<?php echo $hide_clear_extra; ?> href="<?php echo $clear_extra; ?>" class="btn btn-default btn-sm">Очистить</a>
                        <?php if ($extra_album_error) : ?><span class="error"><?php echo $extra_album_error; ?></span><?php endif; ?>
                    </div>
                </div>
                </div>
            </div>
            
          <div class="table-responsive" id="products_div">
            <table class="table table-bordered table-hover" id="products_table">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  
                  <?php if ($show_column_id) { ?>  
                  <td class="text-left"><?php if ($sort == 'p.product_id') { ?>
                    <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>">ID</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_id; ?>">ID</a>
                    <?php } ?></td>
                  <?php } ?>
                  
                  <td class="text-center"><?php echo $column_image; ?></td>
                  
                  <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  
                  <?php if ($show_column_producer) { ?>  
                  <td class="text-left"><?php if ($sort == 'manufacturer_name') { ?>
                    <a href="<?php echo $sort_producer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_producer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_producer; ?>"><?php echo $column_producer; ?></a>
                    <?php } ?></td>
                  <?php } ?>
                  
                  <?php if ($show_column_model) { ?>  
                  <td class="text-left"><?php if ($sort == 'p.model') { ?>
                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>
                  <?php } ?>
                
                  <?php if ($show_column_price) { ?>  
                  <td class="text-left"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
                  <?php } ?>
                  
                  <?php if ($show_column_quantity) { ?>  
                  <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                    <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                    <?php } ?></td>
                  <?php } ?>
                
                  <?php if ($show_column_status) { ?>  
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <?php } ?>
                  
                  <?php if ($show_column_date_added) { ?>  
                  <td class="text-left"><?php if ($sort == 'p.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <?php } ?>
                  
                  <td class="text-left">
                    <?php echo $column_album; ?>
                   </td>
                    
                <?php if ($show_column_albums) { ?>
                  <td class="text-left"><?php if ($sort == 'export_albums') { ?>
                    <a href="<?php echo $sort_export_albums; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_albums; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_export_albums; ?>"><?php echo $column_albums; ?></a>
                    <?php } ?></td>
                <?php } ?>
                    
                <?php if ($show_column_wall) { ?>
                  <td class="text-left"><?php if ($sort == 'export_wall') { ?>
                    <a href="<?php echo $sort_export_wall; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_wall; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_export_wall; ?>"><?php echo $column_wall; ?></a>
                    <?php } ?></td>
                <?php } ?>
                
                <?php if ($show_column_market) { ?>
                  <td class="text-left"><?php if ($sort == 'export_market') { ?>
                    <a href="<?php echo $sort_export_market; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_market; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_export_market; ?>"><?php echo $column_market; ?></a>
                    <?php } ?></td>
                <?php } ?>
                
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-center"><?php if ($product['selected']) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td>
                
                <?php if ($show_column_id) { ?>
                    <td class="text-left"><?php echo $product['product_id']; ?></td>
                  <?php } ?>
                
                  <td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
                    
                  <td class="text-left"><a href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a></td>
                  
                  <?php if ($show_column_producer) { ?>
                  <td class="text-left"><?php echo $product['manufacturer_name']; ?></td>
                  <?php } ?>
                  
                  <?php if ($show_column_model) { ?>
                  <td class="text-left"><?php echo $product['model']; ?></td>
                  <?php } ?>
                  
                  <?php if ($show_column_price) { ?>
                  <td class="text-left"><?php if ($product['special']) { ?>
                    <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                    <div class="text-danger"><?php echo $product['special']; ?></div>
                    <?php } else { ?>
                    <?php echo $product['price']; ?>
                    <?php } ?></td>
                  <?php } ?>
                
                  <?php if ($show_column_quantity) { ?>
                  <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>
                  <?php } ?>
                
                  <?php if ($show_column_status) { ?>
                  <td class="text-left"><?php echo $product['status']; ?></td>
                  <?php } ?>
                
                  <?php if ($show_column_date_added) { ?>
                  <td class="text-left"><?php echo $product['date_added']; ?></td>
                  <?php } ?>
                  
                  <td class="text-left">
                    <select name="album[<?php echo $product['product_id']; ?>]" class="category_select form-control">
                      <option value="">Не выбран</option>
                      <?php foreach ($categories as $cat) { ?>
                        <?php if ($product['selected_album'] == $cat['category_id']) { ?>
                            <option value="<?php echo $cat['category_id']; ?>" selected="selected" data-vk-market-category-id="<?php echo $cat['vk_market_category_id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $cat['category_id']; ?>" data-vk-market-category-id="<?php echo $cat['vk_market_category_id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  
                  </td>
                  
                  <?php if ($show_column_albums) { ?>
                  <td class="text-left">
                      <?php if ($product['albums_export']) {
                          $total = count($product['albums_export']);
                          if ($total > 1) {
                              echo '<i class="fa fa-check-circle"></i> <b><a class="export_history" data-type="albums" data-id="' . $product['product_id'] . '" href="javascript:void(0);">Да (' . $total . ')</a></b>';
                          }
                          else {
                              echo '<i class="fa fa-check-circle"></i> Да
                              <div id="albums_export' . $product['product_id'] . '" style="width:100px;">';
                              foreach ($product['albums_export'] as $export) {
                                  ?>
                                  <div><a target="_blank" href="<?php echo $export['link']; ?>"><?php echo $export['date']; ?></a> 
                                  <a title="Удалить из альбомов" onclick="if (!confirm('Действительно удалить?')) return false;" href="<?php echo $export['delete_link'] ?>"><i class="fa fa-remove"></i></a></div>
                                  <?php
                              }
                          echo '</div>';
                          }
                          
                      }
                      else echo 'Нет';
                      ?>
                  </td>
                  <?php } ?>
                      
                  <?php if ($show_column_wall) { ?>
                  <td class="text-left">
                      <?php if ($product['wall_export']) {
                          $total = count($product['wall_export']);
                          if ($total > 1) {
                              echo '<i class="fa fa-check-circle"></i> <b><a class="export_history" data-type="wall" data-id="' . $product['product_id'] . '" href="javascript:void(0);">Да (' . $total . ')</a></b>
                              <div id="albums_export' . $product['product_id'] . '" style="display:none;width:100px;">';
                          }
                          else {
                              echo '<i class="fa fa-check-circle"></i> Да
                              <div id="albums_export' . $product['product_id'] . '" style="width:100px;">';
                          }
                          foreach ($product['wall_export'] as $export) {
                              
                              ?>
                              <div><a target="_blank" href="<?php echo $export['link']; ?>"><?php echo $export['date']; ?></a> 
                              <a title="Удалить со стены" onclick="if (!confirm('Действительно удалить?')) return false;" href="<?php echo $export['delete_link'] ?>"><i class="fa fa-remove"></i></a></div>
                              <?php
                          }
                          echo '</div>';
                      }
                      else echo 'Нет';
                          ?>
                  </td>
                  <?php } ?>
                  
                  <?php if ($show_column_market) { ?>
                  <td class="text-left">
                      <?php if ($product['market_export']) {
                          $total = count($product['market_export']);
                          if ($total > 1) {
                              echo '<i class="fa fa-check-circle"></i> <b><a class="export_history" data-type="market" data-id="' . $product['product_id'] . '" href="javascript:void(0);">Да (' . $total . ')</a></b>
                              <div id="market_export' . $product['product_id'] . '" style="display:none;width:100px;">';
                          }
                          else {
                              echo '<i class="fa fa-check-circle"></i> Да
                              <div id="market_export' . $product['product_id'] . '" style="width:100px;">';
                          }
                          foreach ($product['market_export'] as $export) {
                              ?>
                              <div><a target="_blank" href="<?php echo $export['link']; ?>"><?php echo $export['date']; ?></a> 
                              <a title="Удалить из товаров" onclick="if (!confirm('Действительно удалить?')) return false;" href="<?php echo $export['delete_link'] ?>"><i class="fa fa-remove"></i></a></div>
                              <?php
                          }
                          echo '</div>';
                      }
                      else echo 'Нет';
                          ?>
                  </td>
                  <?php } ?>
                
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
    

<div id="vk_market_category_select" style="display:none;">
    
  <select name="vk_market_category_id" class="form-control">
      <option value="0">Выберите категорию</option>
      <?php foreach ($vk_market_cats as $mcats) { ?>
          <optgroup label="<?php echo $mcats['name']; ?>">
            <?php foreach ($mcats['childs'] as $mcat) { ?>
              <option value="<?php echo $mcat->id; ?>"><?php echo $mcat->name; ?></option>
            <?php } ?>
          </optgroup>
      <?php } ?>
  </select>

</div>

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=extension/vk_export&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_manufacturer_name = $('input[name=\'filter_manufacturer_name\']').val();

	if (filter_manufacturer_name) {
		url += '&filter_manufacturer_name=' + encodeURIComponent(filter_manufacturer_name);
	}

	var filter_price = $('input[name=\'filter_price\']').val();

	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}

	var filter_quantity = $('input[name=\'filter_quantity\']').val();

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status && filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
    
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}	
    
	var filter_category = $('select[name=\'filter_category\']').val();
	
	if (filter_category != '*') {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}	
    
    var filter_export_albums = $('select[name=\'filter_export_albums\']').val();
	
	if (filter_export_albums != '*') {
		url += '&filter_export_albums=' + encodeURIComponent(filter_export_albums);
	}	
    
    var filter_export_wall = $('select[name=\'filter_export_wall\']').val();
	
	if (filter_export_wall != '*') {
		url += '&filter_export_wall=' + encodeURIComponent(filter_export_wall);
	}	
    
    var filter_export_market = $('select[name=\'filter_export_market\']').val();
	
	if (filter_export_market != '*') {
		url += '&filter_export_market=' + encodeURIComponent(filter_export_market);
	}	

	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#form-product input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<script type="text/javascript"><!--

$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=extension/vk_export/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
			url: 'index.php?route=extension/vk_export/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
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

var refresh_list = function (data) {
    $("#products_table").html($("#products_table", data).html());
    $('#products_table').show();
    $('.alert-success', data).clone().insertBefore('#products_div');
    $('.alert-danger', data).clone().insertBefore('#products_div');
    $('#loading_info').hide();
    if ($('link[rel="icon"]').attr('href') == 'view/image/vkexport/spinner.gif') {
        if ( $('.alert-danger').length ) {
            changeFavicon('view/image/vkexport/warning.png');
        }
        else {
            changeFavicon('view/image/vkexport/success.png');
        }
    }
    $('#myModal').modal('hide');
};

function getProgress() {
    $.ajax({
      url: "<?php echo htmlspecialchars_decode($vk_export_progress); ?>",
      dataType: "html",
      success: function (data) {
          $( "#progress_value" ).text(data);
      }
    });
}

function changeFavicon(href) {
    var icon = $('link[rel="icon"]');
    var cache = icon.clone()
    cache.attr('href', href);
    icon.replaceWith(cache);
}

var show_loading_info = function(total_export, title, message) {
    $('.alert-success').remove();
    $('.alert-danger').remove();
    var html = '<div id="loading_info" class="loading_info">'
        + '<img src="view/image/vkexport/spinner.gif">&nbsp;&nbsp;<span id="action_message">' + message + '</span>';
    if (total_export) {
        html += '<div id="progressbar_wrapper"><br>Экспортировано <span id="progress_value"></span> из <span id="total_export">' + total_export +'</span></div>'; 
    }
    html += '</div>';
    $('#myModal .modal-title').text(title);
    $('#myModal .modal-body').html(html);
    $('#myModal .modal-footer').hide();
    $('#myModal').modal();
    $('#progressbar_wrapper').show();
    $('#progress_value').text(0);
    $('#total_export').text(total_export);
    
    if ($('link[rel="icon"]').length == 0) {
        $('head').append('<link rel="icon" href="view/image/vkexport/spinner.gif" type="image/gif" />');
    }
    else {
        changeFavicon('view/image/vkexport/spinner.gif');
    }
};

// экспорт на стену
$(document).on('click', '#export_action', function () {
	var total_export = $('input[name="selected[]"]:checked').length;
    if (total_export < 1) {
        return alert('Вы не выбрали ни одного товара!');
    }
    
    show_loading_info(
        total_export,
        'Экспорт товаров в альбомы...',
        'Идёт процесс экспорта товаров в альбомы, пожалуйста подождите. <br>Это может занять от нескольких секунд, до нескольких минут.'
    );
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($vk_export); ?>",
      type: "POST",
      data: $("#form-product").serialize(),
      dataType: "html"
    });
    
    var progress_timer = setInterval('getProgress();', 1000);
    
    request.done(function(data) {
        clearInterval(progress_timer);

        refresh_list(data);
    });
});

// экспорт в альбомы
$(document).on('click', '#wallpost_action', function () {
	var total_export = $('input[name="selected[]"]:checked').length;
    if (total_export < 1) {
        return alert('Вы не выбрали ни одного товара!');
    }
    
    show_loading_info(
        total_export,
        'Экспорт товаров на стену...',
        'Идёт процесс отправки товаров на стену, пожалуйста подождите. <br>Это может занять от нескольких секунд, до нескольких минут.'
    );
    
    var progress_timer = setInterval('getProgress();', 1000);
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($vk_wallpost); ?>",
      type: "POST",
      data: $("#form-product").serialize(),
      dataType: "html"
    });
    
    request.done(function(data) {
		clearInterval(progress_timer);
        refresh_list(data);
    });
});

// экспорт в товары
$(document).on('click', '#market_action', function () {
    
    var market_cats_check = true;
    var to_market_cats = {};
    $('input[name="selected[]"]:checked').each(function() {
        if ($('select[name="album[' + $(this).val() + ']"]').val() && !$('select[name="album[' + $(this).val() + ']"] option:selected').attr('data-vk-market-category-id')) {
            market_cats_check = false;
            to_market_cats[$('select[name="album[' + $(this).val() + ']"]').val()] = $('select[name="album[' + $(this).val() + ']"] option:selected').text();
        }
    });
    
    if (!market_cats_check) {
        var html = 'У этих категорий не установлено соответствие с категориями товаров ВК!<br><br>';
        html += 'Пожалуйста, укажите соответствующие категории:<br><br><table class="table">';
        $.each(to_market_cats, function(index, val) {
            var $cat_select = $('#vk_market_category_select').clone();
            $('select', $cat_select)
                .attr('name', 'vk_market_category_id[' + index + ']')
                .addClass('vk_market_category_id')
                .attr('data-category-id', index);
            html += '<tr><td>' + val + '</td><td>' + $cat_select.html() + '</td></tr>';
        });
        html += '</table>';
        
        $('#myModal .modal-title').text('Экспорт товаров в ВК');
        $('#myModal .modal-body').html(html);
        $('#myModal .modal-footer').html('<button id="process_vk_market_categories" type="button" class="btn btn-primary">Продолжить</button><button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>');
        $('#myModal .modal-footer').show();
        $('#myModal').modal();
        return false;
    }
    
	var total_export = $('input[name="selected[]"]:checked').length;
    if (total_export < 1) {
        return alert('Вы не выбрали ни одного товара!');
    }
    
    show_loading_info(
        total_export,
        'Экспорт в товары...',
        'Идёт процесс отправки товаров, пожалуйста подождите. <br>Это может занять от нескольких секунд, до нескольких минут.'
    );
    
    var progress_timer = setInterval('getProgress();', 1000);
    
    var postdata = $("#form-product").serialize();
    var category_vk_market_id = {};
    $('input[name="selected[]"]:checked').each(function() {
        category_vk_market_id[$('select[name="album[' + $(this).val() + ']"] option:selected').val()] = $('select[name="album[' + $(this).val() + ']"] option:selected').attr('data-vk-market-category-id');
    });
    $.each(category_vk_market_id, function(category_id, vk_market_category_id) {
        postdata += '&category_vk_market_id[' + category_id + ']=' + vk_market_category_id;
    });
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($vk_market); ?>",
      type: "POST",
      data: postdata,
      dataType: "html"
    });
    
    request.done(function(data) {
		clearInterval(progress_timer);
        refresh_list(data);
    });
});

// назначение категорий товаров ВК
$(document).on('click', '#process_vk_market_categories', function () {
    $('.vk_market_category_id').each(function() {
        $('.category_select option[value="' + $(this).attr('data-category-id') + '"]').attr('data-vk-market-category-id', $(this).val());
        $('#market_action').click();
    });
});

// удаление из альбомов
$(document).on('click', '#reexport_action', function () {
	if (!confirm('Действительно удалить?')) return false;
    if ($('input[name="selected[]"]:checked').length < 1) {
        return alert('Вы не выбрали ни одного товара!');
    }
    
    show_loading_info(
        false,
        'Удаление товаров из альбомов...',
        'Идёт процесс удаления товаров из альбомов, пожалуйста подождите. <br>Это может занять от нескольких секунд, до нескольких минут.'
    );
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($vk_delete); ?>",
      type: "POST",
      data: $("#form-product").serialize(),
      dataType: "html"
    });
    
    request.done(function(data) {
        refresh_list(data);
    });
});

// удаление со стены
$(document).on('click', '#delete_wall_action', function () {
	if (!confirm('Действительно удалить?')) return false;
    if ($('input[name="selected[]"]:checked').length < 1) {
        return alert('Вы не выбрали ни одного товара!');
    }
    
    show_loading_info(
        false,
        'Удаление товаров со стены...',
        'Идёт процесс удаления товаров со стены, пожалуйста подождите. <br>Это может занять от нескольких секунд, до нескольких минут.'
    );
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($vk_delete_wall); ?>",
      type: "POST",
      data: $("#form-product").serialize(),
      dataType: "html"
    });
    
    request.done(function(data) {
        refresh_list(data);
    });
});

// удаление из товаров
$(document).on('click', '#delete_market_action', function () {
	if (!confirm('Действительно удалить?')) return false;
    if ($('input[name="selected[]"]:checked').length < 1) {
        return alert('Вы не выбрали ни одного товара!');
    }
    
    show_loading_info(
        false,
        'Удаление товаров...',
        'Идёт процесс удаления товаров, пожалуйста подождите. <br>Это может занять от нескольких секунд, до нескольких минут.'
    );
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($vk_market_delete); ?>",
      type: "POST",
      data: $("#form-product").serialize(),
      dataType: "html"
    });
    
    request.done(function(data) {
        refresh_list(data);
    });
});

// отправка капчи
$(document).on('click', '#send_captcha', function () {
    
    show_loading_info(
        false,
        'Отправка капчи...',
        'Идёт отправка капчи, пожалуйста подождите...'
    );
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($send_captcha); ?>",
      type: "POST",
      data: $("input[name^='captcha']").serialize(),
      dataType: "json"
    });
    
    request.done(function(data) {
		$('.alert-success').hide();
		$('.alert-danger').hide();
        $('#myModal').modal('hide');

        if (data.success) {
			$('.alert-success').html(data.success);
			$('.alert-success').show();
		}
        if (data.warning) {
			$('.alert-danger').html(data.warning);
			$('.alert-danger').show();
			changeFavicon('view/image/vkexport/warning.png');
		}
		else {
			changeFavicon('view/image/vkexport/success.png');
		}
    });
});

// отправка проверки номера
$(document).on('click', '#send_security', function () {
    
    show_loading_info(
        false,
        'Отправка номера...',
        'Идёт отправка номера, пожалуйста подождите...'
    );
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($send_security); ?>",
      type: "POST",
      data: $("input[name='code'], #security_url").serialize(),
      dataType: "json"
    });
    
    request.done(function(data) {
		$('.alert-success').hide();
		$('.alert-danger').hide();
        $('#myModal').modal('hide');
        
        if (data.success) {
			$('.alert-success').html(data.success);
			$('.alert-success').show();
		}
        if (data.warning) {
			$('.alert-danger').html(data.warning);
			$('.alert-danger').show();
			changeFavicon('view/image/vkexport/warning.png');
		}
		else {
			changeFavicon('view/image/vkexport/success.png');
		}
    });
});

$(document).on('click', '.export_history', function () {
     var type = $(this).attr('data-type');
     var product_id = $(this).attr('data-id');
     var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($export_history); ?>",
      type: "GET",
      dataType: "html",
      data: {type: type, product_id: product_id}
    });
    if (type == 'albums') {
         var title = "История экспорта товара в альбомы";
    }
    else if (type == 'albums') {
        var title = 'История экспорта товара на стену';
    }
    else if (type == 'market') {
        var title = 'История экспорта в товары';
    }
    
    request.done(function(data) {

        $('#myModal .modal-title').text(title);
        $('#myModal .modal-body').html(data);
        $('#myModal .modal-footer').show();
        $('#myModal').modal();
        
    });
});

$('.date').datetimepicker({
	pickTime: false
});

// Manufacturer
$('input[name=\'filter_manufacturer_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {

				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['name']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_manufacturer_name\']').val(item['value']);
	}
});

//--></script> 
  </div>
</div>
<?php echo $footer; ?>
