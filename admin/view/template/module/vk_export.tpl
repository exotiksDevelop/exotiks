<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div id="dialog-modal" style="dispay:none;"></div>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    
    <div class="panel panel-default">
      <div class="panel-body">
        
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-albums" data-toggle="tab"><?php echo $tab_albums; ?></a></li>
            <li><a href="#tab-wall" data-toggle="tab"><?php echo $tab_wall; ?></a></li>
            <li><a href="#tab-market" data-toggle="tab"><?php echo $tab_market; ?></a></li>
            <li><a href="#tab-cron" data-toggle="tab"><?php echo $tab_cron; ?></a></li>
            <li><a href="#tab-vk" data-toggle="tab"><?php echo $tab_vk; ?></a></li>
            <li><a href="#tab-license" data-toggle="tab"><?php echo $tab_license; ?></a></li>
          </ul>
          <div class="tab-content">
              
            <div class="tab-pane active" id="tab-general">
                
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_image_mode; ?></label>
                <div class="col-sm-10">
                    <label class="radio-inline"><input type="radio" name="vk_export_image_mode" value="1" <?php echo $vk_export_image_mode == 1 ? 'checked="checked"' : ''; ?> /><?php echo $text_image_orig ?></label><br />
                    <label class="radio-inline"><input type="radio" name="vk_export_image_mode" value="2" <?php echo $vk_export_image_mode == 2 ? 'checked="checked"' : ''; ?> /><?php echo $text_image_resize ?></label><br />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_attributes_tpl; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="vk_export_attributes_tpl" value="<?php echo $vk_export_attributes_tpl ?>" class="form-control" />
                    <div class="help"><?php echo $text_attributes_tpl ?></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_attributes_delimeter; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="vk_export_attributes_delimeter" value="<?php echo $vk_export_attributes_delimeter ?>" class="form-control" />
                    <div class="help"><?php echo $text_attributes_delimeter_help ?></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_http_catalog; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="vk_export_http_catalog" value="<?php echo $vk_export_http_catalog ?>" class="form-control" />
                    <div class="help"><?php echo $text_desc_http_catalog ?></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_products_per_page; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="vk_export_products_per_page" value="<?php echo $vk_export_products_per_page ?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_colums; ?></label>
                <div class="col-sm-10">
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_id" value="1" <?php echo $vk_export_column_id == 1 ? 'checked="checked"' : ''; ?> /> ID</label>
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_model" value="1" <?php echo $vk_export_column_model == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $text_model; ?></label>
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_price" value="1" <?php echo $vk_export_column_price == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $text_price; ?></label>
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_quantity" value="1" <?php echo $vk_export_column_quantity == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $text_quantity; ?></label>
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_status" value="1" <?php echo $vk_export_column_status == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $text_status; ?></label>
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_date_added" value="1" <?php echo $vk_export_column_date_added == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $text_date_added; ?></label>
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_producer" value="1" <?php echo $vk_export_column_producer == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $text_producer; ?></label>
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_albums" value="1" <?php echo $vk_export_column_albums == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $text_albums; ?></label>
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_wall" value="1" <?php echo $vk_export_column_wall == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $text_wall; ?></label>
                    <label class="radio-inline"><input type="checkbox" name="vk_export_column_market" value="1" <?php echo $vk_export_column_market == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $text_market; ?></label>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_user_replacements; ?></label>
                <div class="col-sm-10">
                    <div style="float:left;">
                        <div class="help"><?php echo  $text_search ?></div>
                        <textarea name="vk_export_user_replacements_keys" /><?php echo $vk_export_user_replacements_keys ?></textarea>
                    </div>
                    <div style="">
                        <div class="help"><?php echo  $text_replacement ?></div>
                        <textarea name="vk_export_user_replacements_values" /><?php echo $vk_export_user_replacements_values ?></textarea>
                    </div>
                    <div class="help"><?php echo  $text_replacements_desc ?></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_price_coef; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="vk_export_price_coef" value="<?php echo $vk_export_price_coef ?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_debug_mode; ?></label>
                <div class="col-sm-10">
                    <select name="vk_export_debug_mode" class="form-control">
                        <option value="0" <?php echo $vk_export_debug_mode == 0 ? 'selected="selected"' : ''; ?>><?php echo $text_disabled ?></option>
                        <option value="1" <?php echo $vk_export_debug_mode == 1 ? 'selected="selected"' : ''; ?>><?php echo $text_enabled ?></option>
                    </select>
                </div>
              </div>
              
            </div>
            
            <div class="tab-pane" id="tab-albums">
                
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_export_mode; ?></label>
                <div class="col-sm-10">
                    <label class="radio-inline"><input type="radio" name="vk_export_mode" value="1" <?php echo $vk_export_mode == 1 ? 'checked="checked"' : ''; ?> /><?php echo $text_export_mode_user ?></label>
                    <label class="radio-inline"><input type="radio" name="vk_export_mode" value="2" <?php echo $vk_export_mode == 2 ? 'checked="checked"' : ''; ?> /><?php echo $text_export_mode_group ?></label>
                    <label class="radio-inline"><input type="radio" name="vk_export_mode" value="3" <?php echo $vk_export_mode == 3 ? 'checked="checked"' : ''; ?> /><?php echo $text_export_mode_both ?></label>
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_album_name_mode; ?></label>
                <div class="col-sm-10">
                    <label class="radio-inline"><input type="radio" name="vk_export_album_name_mode" value="1" <?php echo $vk_export_album_name_mode == 1 ? 'checked="checked"' : ''; ?> /><?php echo $text_album_name_orig ?></label><br />
                    <label class="radio-inline"><input type="radio" name="vk_export_album_name_mode" value="2" <?php echo $vk_export_album_name_mode == 2 ? 'checked="checked"' : ''; ?> /><?php echo $text_album_name_path ?></label><br />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label><input type="checkbox" name="vk_export_root_cat" value="1" <?php echo $vk_export_root_cat == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $entry_export_root_cat; ?></label>
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label><input type="checkbox" name="vk_export_album_only" value="1" <?php echo $vk_export_album_only == 1 ? 'checked="checked"' : ''; ?> /> <?php echo $entry_album_only; ?></label>
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_mode_desc; ?></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label><input type="checkbox" name="vk_export_mode_desc" value="1" <?php echo $vk_export_mode_desc == 1 ? 'checked="checked"' : ''; ?> /></label>
                    </div>
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_desc_tpl; ?></label>
                <div class="col-sm-10">
                    <textarea name="vk_export_desc_tpl" style="float:left;margin-right:20px;" cols="50" rows="10" /><?php echo $vk_export_desc_tpl ?></textarea>
                    <div class="export_tpl_info_block"><a href="javascript:void(0);" class="btn btn-primary export_tpl_info_link"><?php echo $text_var_list ?></a>
                    <div class="export_tpl_info" style="display:none;"><?php echo $export_tpl_info ?></div></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_mode_comment; ?></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label><input type="checkbox" name="vk_export_mode_comment" value="1" <?php echo $vk_export_mode_comment == 1 ? 'checked="checked"' : ''; ?> /></label>
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_comment_tpl; ?></label>
                <div class="col-sm-10">
                    <textarea name="vk_export_comment_tpl" style="float:left;margin-right:20px;" cols="50" rows="10" /><?php echo $vk_export_comment_tpl ?></textarea>
                    <div class="export_tpl_info_block"><a href="javascript:void(0);" class="btn btn-primary export_tpl_info_link"><?php echo $text_var_list ?></a>
                    <div class="export_tpl_info" style="display:none;"><?php echo $export_tpl_info ?></div></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_group_photo_comment_from; ?></label>
                <div class="col-sm-10">
                    <label class="radio-inline"><input type="radio" name="vk_export_group_photo_comment_from" value="0" <?php echo $vk_export_group_photo_comment_from == 0 ? 'checked="checked"' : ''; ?> /><?php echo $text_wallpost_from_user ?></label><br />
                    <label class="radio-inline"><input type="radio" name="vk_export_group_photo_comment_from" value="1" <?php echo $vk_export_group_photo_comment_from == 1 ? 'checked="checked"' : ''; ?> /><?php echo $text_wallpost_from_group ?></label><br />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_photos_count; ?></label>
                <div class="col-sm-10">
                    <select name="vk_export_photos_count" class="form-control">
                        <option value="1" <?php echo $vk_export_photos_count == 1 ? 'selected="selected"' : ''; ?>>1</option>
                        <option value="2" <?php echo $vk_export_photos_count == 2 ? 'selected="selected"' : ''; ?>>2</option>
                        <option value="3" <?php echo $vk_export_photos_count == 3 ? 'selected="selected"' : ''; ?>>3</option>
                        <option value="4" <?php echo $vk_export_photos_count == 4 ? 'selected="selected"' : ''; ?>>4</option>
                        <option value="5" <?php echo $vk_export_photos_count == 5 ? 'selected="selected"' : ''; ?>>5</option>
                    </select>
                    <div class="help"><?php echo $entry_photos_count_help ?></div>
                </div>
              </div>
                
            </div>
            
            <div class="tab-pane" id="tab-wall">
                
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_wallpost_tpl; ?></label>
                <div class="col-sm-10">
                    <textarea name="vk_export_wallpost_tpl" style="float:left;margin-right:20px;" cols="50" rows="10" /><?php echo $vk_export_wallpost_tpl ?></textarea>
                    <div class="export_tpl_info_block">
                        <a href="javascript:void(0);" class="btn btn-primary export_tpl_info_link"><?php echo $text_var_list ?></a>
                        <div class="export_tpl_info" style="display:none;"><?php echo $export_tpl_info ?></div>
                    </div>
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_group_wallpost_from; ?></label>
                <div class="col-sm-10">
                    <label class="radio-inline"><input type="radio" name="vk_export_group_wallpost_from" value="1" <?php echo $vk_export_group_wallpost_from == 1 ? 'checked="checked"' : ''; ?> /><?php echo $text_wallpost_from_user ?></label><br />
                    <label class="radio-inline"><input type="radio" name="vk_export_group_wallpost_from" value="2" <?php echo $vk_export_group_wallpost_from == 2 ? 'checked="checked"' : ''; ?> /><?php echo $text_wallpost_from_group ?></label><br />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_wallpost_photos_count; ?></label>
                <div class="col-sm-10">
                    <select name="vk_export_wallpost_photos_count" class="form-control">
                        <option value="1" <?php echo $vk_export_wallpost_photos_count == 1 ? 'selected="selected"' : ''; ?>>1</option>
                        <option value="2" <?php echo $vk_export_wallpost_photos_count == 2 ? 'selected="selected"' : ''; ?>>2</option>
                        <option value="3" <?php echo $vk_export_wallpost_photos_count == 3 ? 'selected="selected"' : ''; ?>>3</option>
                        <option value="4" <?php echo $vk_export_wallpost_photos_count == 4 ? 'selected="selected"' : ''; ?>>4</option>
                        <option value="5" <?php echo $vk_export_wallpost_photos_count == 5 ? 'selected="selected"' : ''; ?>>5</option>
                        <option value="all" <?php echo $vk_export_wallpost_photos_count == 'all' ? 'selected="selected"' : ''; ?>><?php echo $text_all ?></option>
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_wall_export_services; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="vk_export_wall_export_services" value="<?php echo $vk_export_wall_export_services ?>" class="form-control" /> 
                    <div class="help"><?php echo $entry_wall_export_services_help ?></div>
                </div>
              </div>
                
            </div>
            
            <div class="tab-pane" id="tab-market">
                
                
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_create_market_albums; ?></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label><input type="checkbox" name="vk_export_create_market_albums" value="1" <?php echo $vk_export_create_market_albums == 1 ? 'checked="checked"' : ''; ?> /></label>
                    </div>
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_market_product_desc_tpl; ?></label>
                <div class="col-sm-10">
                    <textarea name="vk_export_market_product_desc_tpl" style="float:left;margin-right:20px;" cols="50" rows="10" /><?php echo $vk_export_market_product_desc_tpl ?></textarea>
                    <div class="export_tpl_info_block">
                        <a href="javascript:void(0);" class="btn btn-primary export_tpl_info_link"><?php echo $text_var_list ?></a>
                        <div class="export_tpl_info" style="display:none;"><?php echo $export_tpl_info ?></div>
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_market_photos_count; ?></label>
                <div class="col-sm-10">
                    <select name="vk_export_market_photos_count" class="form-control">
                        <option value="1" <?php echo $vk_export_market_photos_count == 1 ? 'selected="selected"' : ''; ?>>1</option>
                        <option value="2" <?php echo $vk_export_market_photos_count == 2 ? 'selected="selected"' : ''; ?>>2</option>
                        <option value="3" <?php echo $vk_export_market_photos_count == 3 ? 'selected="selected"' : ''; ?>>3</option>
                        <option value="4" <?php echo $vk_export_market_photos_count == 4 ? 'selected="selected"' : ''; ?>>4</option>
                        <option value="5" <?php echo $vk_export_market_photos_count == 5 ? 'selected="selected"' : ''; ?>>5</option>
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label">Единица измерений в миллиметрах</label>
                <div class="col-sm-10">
                    <select name="vk_export_mm_class_id" class="form-control">
                        <?php foreach ($length_classes as $class) { ?>
                            <?php if ($class['length_class_id'] == $vk_export_mm_class_id) { ?>
                            <option value="<?php echo $class['length_class_id'] ?>" selected="selected"><?php echo $class['title'] ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $class['length_class_id'] ?>"><?php echo $class['title'] ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label">Единица веса в граммах</label>
                <div class="col-sm-10">
                    <select name="vk_export_gramm_class_id" class="form-control">
                        <?php foreach ($weight_classes as $class) { ?>
                            <?php if ($class['weight_class_id'] == $vk_export_gramm_class_id) { ?>
                            <option value="<?php echo $class['weight_class_id'] ?>" selected="selected"><?php echo $class['title'] ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $class['weight_class_id'] ?>"><?php echo $class['title'] ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
              </div>
                
            </div>
            
            <div class="tab-pane" id="tab-cron">
                <div class="alert alert-info"><?php echo $text_cron_notice ?></div>
                
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    
                    
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_cron_setup">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_cron_setup" aria-expanded="true" aria-controls="collapse_cron_setup">
                          <?php echo $entry_cron_setup; ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_cron_setup" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_cron_setup">
                      <div class="panel-body">
                        
                        <ul>
                          <li>Скопируйте папку с файлами <font color="green"><b>cron/</b></font> из архива с модулем в директорию <font color="green"><b>admin/</b></font> вашего магазина<br><br></li>
                          
                          <li>Для запрета несанкционированного запуска скрипта создайте файл <font color="green"><b>admin/cron/.htaccess</b></font> с содержимым:<br>
<pre>
&lt;Files *.php&gt;
    Order Deny,Allow
    Deny from all 
&lt;/Files&gt;
</pre></li>
                          
                          <li>Через панель управление хостингом сайта создайте задание в cron (обратитесь к справке вашего хостинга, при необходимости)<br><br></li>
                          
                          <li>Создайте команду запуска: <br><br>
                          Путь к php <input type="text" id="php_path" value="/usr/bin/php"><br>
                          <input type="radio" name="cmd_type" class="cmd_type" id="cmd_type1" value="vk_export_cron_albums.php"> <label for="cmd_type1">экспорт в альбомы</label><br>
                          <input type="radio" name="cmd_type" class="cmd_type" id="cmd_type3" value="vk_export_cron_update.php"> <label for="cmd_type3">обновление товаров в альбомах</label><br>
                          <input type="radio" name="cmd_type" class="cmd_type" id="cmd_type2" value="vk_export_cron_wall.php"> <label for="cmd_type2">экспорт на стену</label><br>
                          <input type="radio" name="cmd_type" class="cmd_type" id="cmd_type4" value="vk_export_cron_market.php" checked="checked"> <label for="cmd_type4">экспорт в товары</label><br>
                          <input type="radio" name="cmd_type" class="cmd_type" id="cmd_type5" value="vk_export_cron_market_update.php"> <label for="cmd_type5">обновление в товарах</label><br>
                          <br>
                          <div id="cron_cmd">
                          Ваша команда: <pre><span id="cmd_php_path">/usr/bin/php</span> <?php echo DIR_APPLICATION; ?>cron/<span id="cmd_param">vk_export_cron_albums.php</span></pre>
                          Укажите её в задании cron.<br><br>
                          </div>
                          </li>
                          <li>Укажите расписание запуска команды. Можно, например, назначить запуск ежедневно в 3:00 часа ночи, в период минимальной активности посетителей на сайте.
                          <div class="alert alert-warning">Не ставьте все задания на одно время! Все задания нужно ставить на разные часы!</div><br><br></li>
                          <li>Следует включить отправку отчетов на e-mail. Лог будет располагаться в файле <font color="#8B6914">system/logs/vkExport_cron.txt</font></li>
                        </ul>
                        
                      </div>
                    </div>
                  </div>
                    
                    
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_auth">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_auth" aria-expanded="true" aria-controls="collapse_auth">
                            Авторизация
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_auth" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_auth">
                      <div class="panel-body">
                
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_cron_user; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="vk_export_cron_user" value="<?php echo $vk_export_cron_user ?>" class="form-control" /> 
                                <div class="alert alert-warning"><?php echo $text_cron_user_help ?></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_cron_pass; ?></label>
                            <div class="col-sm-10">
                                <input type="password" name="vk_export_cron_pass" class="form-control" /> 
                            </div>
                        </div>
                        </div>
                    </div>
                  </div>
                
                    
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_shared_settings">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_shared_settings" aria-expanded="true" aria-controls="collapse_shared_settings">
                          <?php echo $entry_shared_settings; ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_shared_settings" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_shared_settings">
                      <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_export_only_instock; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="vk_export_only_instock" value="1" <?php echo $vk_export_only_instock == 1 ? 'checked="checked"' : ''; ?> /></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_export_only_enabled; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="vk_export_only_enabled" value="1" <?php echo $vk_export_only_enabled == 1 ? 'checked="checked"' : ''; ?> /></label>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_albums_settings">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_albums_settings" aria-expanded="true" aria-controls="collapsealbums_settings">
                          <?php echo $entry_albums_settings; ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_albums_settings" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_albums_settings">
                      <div class="panel-body">
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_export_albums_only_specials; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="vk_export_albums_only_specials" value="1" <?php echo $vk_export_albums_only_specials == 1 ? 'checked="checked"' : ''; ?> /></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_num_products_for_cron; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="vk_export_num_products_for_cron" value="<?php echo $vk_export_num_products_for_cron ?>" class="form-control" /> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_num_products_for_cron_albums_update; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="vk_export_num_products_for_cron_albums_update" value="<?php echo $vk_export_num_products_for_cron_albums_update ?>" class="form-control" /> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_category_autoexport; ?></span></label>
                            <div class="col-sm-10">
                                <div class="well well-sm" style="height: 150px; overflow: auto;">
                                      <?php foreach ($categories as $category_id => $category_name) { ?>
                                      <div class="checkbox">
                                          <label>
                                            <?php if (in_array($category_id, $vk_export_autoexport_category)) { ?>
                                                <input type="checkbox" name="vk_export_autoexport_category[]" value="<?php echo $category_id; ?>" checked="checked" />
                                                <?php echo $category_name; ?>
                                            <?php } else { ?>
                                                <input type="checkbox" name="vk_export_autoexport_category[]" value="<?php echo $category_id; ?>" />
                                                <?php echo $category_name; ?>
                                            <?php } ?>
                                          </label>
                                      </div>
                                      <?php } ?>
                                </div>
                                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_delete_out_of_stock; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="vk_export_cron_delete_out_of_stock" value="1" <?php echo $vk_export_cron_delete_out_of_stock == 1 ? 'checked="checked"' : ''; ?> /></label>
                                    <div class="help"><?php echo $entry_delete_out_of_stock_desc ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_delete_disabled; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="vk_export_cron_delete_disabled" value="1" <?php echo $vk_export_cron_delete_disabled == 1 ? 'checked="checked"' : ''; ?> /></label>
                                    <div class="help"><?php echo $entry_delete_out_of_stock_desc ?></div>
                                </div>
                            </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                  
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_wall_settings">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_wall_settings" aria-expanded="true" aria-controls="collapse_wall_settings">
                          <?php echo $entry_wall_settings; ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_wall_settings" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_wall_settings">
                      <div class="panel-body">
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_export_wall_only_specials; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="vk_export_wall_only_specials" value="1" <?php echo $vk_export_wall_only_specials == 1 ? 'checked="checked"' : ''; ?> /></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_export_wall_unique; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="vk_export_wall_unique" value="1" <?php echo $vk_export_wall_unique == 1 ? 'checked="checked"' : ''; ?> /></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_num_wallpost_for_cron; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="vk_export_cron_wallpost_max" value="<?php echo $vk_export_cron_wallpost_max ?>" class="form-control" /> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_category_autoexport_wall; ?></span></label>
                            <div class="col-sm-10">
                                <div class="well well-sm" style="height: 150px; overflow: auto;">
                                      <?php foreach ($categories as $category_id => $category_name) { ?>
                                      <div class="checkbox">
                                          <label>
                                            <?php if (in_array($category_id, $vk_export_autoexport_category_wall)) { ?>
                                                <input type="checkbox" name="vk_export_autoexport_category_wall[]" value="<?php echo $category_id; ?>" checked="checked" />
                                                <?php echo $category_name; ?>
                                            <?php } else { ?>
                                                <input type="checkbox" name="vk_export_autoexport_category_wall[]" value="<?php echo $category_id; ?>" />
                                                <?php echo $category_name; ?>
                                            <?php } ?>
                                          </label>
                                      </div>
                                      <?php } ?>
                                </div>
                                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                            </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                  
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_market_settings">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_market_settings" aria-expanded="true" aria-controls="collapse_market_settings">
                          <?php echo $entry_market_settings; ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_market_settings" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_market_settings">
                      <div class="panel-body">
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_export_market_only_specials; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="vk_export_market_only_specials" value="1" <?php echo $vk_export_market_only_specials == 1 ? 'checked="checked"' : ''; ?> /></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_num_products_for_cron; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="vk_export_market_num_products_for_cron" value="<?php echo $vk_export_market_num_products_for_cron ?>" class="form-control" /> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_num_products_for_cron_market_update; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="vk_export_num_products_for_cron_market_update" value="<?php echo $vk_export_num_products_for_cron_market_update ?>" class="form-control" /> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_market_category_autoexport; ?></span></label>
                            <div class="col-sm-10">
                                <div class="well well-sm" style="height: 150px; overflow: auto;">
                                      <?php foreach ($categories as $category_id => $category_name) { ?>
                                      <div class="checkbox">
                                          <label>
                                            <?php if (in_array($category_id, $vk_export_market_autoexport_category)) { ?>
                                                <input type="checkbox" name="vk_export_market_autoexport_category[]" value="<?php echo $category_id; ?>" checked="checked" />
                                                <?php echo $category_name; ?>
                                            <?php } else { ?>
                                                <input type="checkbox" name="vk_export_market_autoexport_category[]" value="<?php echo $category_id; ?>" />
                                                <?php echo $category_name; ?>
                                            <?php } ?>
                                          </label>
                                      </div>
                                      <?php } ?>
                                </div>
                                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_market_action_out_of_stock; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="radio" name="vk_export_market_cron_action_out_of_stock" value="delete" <?php echo $vk_export_market_cron_action_out_of_stock == 'delete' ? 'checked="checked"' : ''; ?> /> <?php echo $entry_delete; ?></label><br>
                                    <label><input type="radio" name="vk_export_market_cron_action_out_of_stock" value="not_avaible" <?php echo $vk_export_market_cron_action_out_of_stock == 'not_avaible' ? 'checked="checked"' : ''; ?> /> <?php echo $entry_status_not_avaible; ?></label><br>
                                    <label><input type="radio" name="vk_export_market_cron_action_out_of_stock" value="none" <?php echo $vk_export_market_cron_action_out_of_stock == 'none' ? 'checked="checked"' : ''; ?> /> ничего не делать</label><br><br>
                                    <div class="help"><?php echo $entry_market_action_out_of_stock_desc ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_market_action_disabled; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="radio" name="vk_export_market_cron_action_disabled" value="delete" <?php echo $vk_export_market_cron_action_disabled == 'delete' ? 'checked="checked"' : ''; ?> /> <?php echo $entry_delete; ?></label><br>
                                    <label><input type="radio" name="vk_export_market_cron_action_disabled" value="not_avaible" <?php echo $vk_export_market_cron_action_disabled == 'not_avaible' ? 'checked="checked"' : ''; ?> /> <?php echo $entry_status_not_avaible; ?></label><br>
                                    <label><input type="radio" name="vk_export_market_cron_action_disabled" value="none" <?php echo $vk_export_market_cron_action_disabled == 'none' ? 'checked="checked"' : ''; ?> /> ничего не делать</label><br><br>
                                    <div class="help"><?php echo $entry_market_action_out_of_stock_desc ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_delete_market_copies; ?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="vk_export_delete_market_copies" value="1" <?php echo $vk_export_delete_market_copies == 1 ? 'checked="checked"' : ''; ?> /></label>
                                </div>
                            </div>
                        </div>
                        
                        
                      </div>
                    </div>
                  </div>
                  
                  
                </div>
                
                
            </div>
            
            <div class="tab-pane" id="tab-vk">
                <?php if (!$license_status) { ?>
                    Сначала необходимо зарегистрировать вашу лицензию на модуль. Перейдите на вкладку "Лицензия".
                <?php } else { ?>
                    <h2><?php echo $text_account_setup ?></h2>
                    <div id="setup_steps">
                        <div class="steps" id="step1"<?php echo ($vk_export_user_id ? ' style="display:none;"' : ''); ?>>
                            <?php echo $text_account_setup_desc; ?><br><br>
                            <b>Шаг 1 из 7</b><br><br>
                            <?php echo $text_account_step1 ?><br><br>
                            <img src="view/image/vkexport/step_1.jpg" alt="" /><br>
                            <i><?php echo $text_account_step1_1 ?></i>
                        </div>
                        <div class="steps" id="step2" style="display:none;">
                            <b>Шаг 2 из 7</b><br><br>
                            <?php echo $text_account_step2 ?><br><br>
                            <img src="view/image/vkexport/step_2.jpg" alt="" />
                        </div>
                        <div class="steps" id="step3" style="display:none;">
                            <b>Шаг 3 из 7</b><br><br>
                            <?php echo $text_account_step3 ?><br><br>
                            <img src="view/image/vkexport/step_3.jpg" alt="" />
                        </div>
                        <div class="steps" id="step4" style="display:none;">
                            <b>Шаг 4 из 7</b><br><br>
                            <?php echo $text_account_step4 ?>
                            <input type="text" id="client_id" value="">
                        </div>
                        <div class="steps" id="step5" style="display:none;">
                            <b>Шаг 5 из 7</b><br><br>
                            <?php echo $text_account_step5 ?>
                        </div>
                        <div class="steps" id="step_check_app_security" style="display:none;">
                            <b>Шаг 6 из 7</b><br><br>
                            <?php echo $text_account_step6 ?>
                            <br>
                            <input type="text" id="vk_export_access_token" name="vk_export_access_token" value="" style="width:90%;">
                        </div>
                        <div class="steps"<?php echo ($vk_export_user_id ? '' : ' style="display:none;"'); ?>>
                            <div class="no_setup"<?php echo ($vk_export_user_id ? ' style="display:none;"' : ''); ?>>
                                <b>Шаг 7 из 7</b><br><br>
                                <?php echo $text_account_step7 ?>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_user_id; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="vk_export_user_id" value="<?php echo $vk_export_user_id ?>" class="form-control" />
                                    <div class="help"><?php echo $text_user_id_example ?></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_group_id; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="vk_export_group_id" value="<?php echo $vk_export_group_id ?>" class="form-control" />
                                    <div class="help"><?php echo $text_group_owner ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <a href="javascript:void(0);" id="prev_step" class="btn btn-primary" style="display:none;">Назад</a>
                    <a href="javascript:void(0);" id="next_step" class="btn btn-primary"<?php echo ($vk_export_user_id ? ' style="display:none;"' : ''); ?>>Далее</a>
                    
                    <div class="setup_done"<?php echo ($vk_export_user_id ? '' : ' style="display:none;"'); ?>>
                        <div class="alert alert-success"><?php echo $text_setup_done ?></div>
                    </div>
                    
                    <?php if ($vk_export_user_id) { ?>
                        <a href="javascript:void(0);" id="new_setup" class="btn btn-primary">Настроить заново</a>
                    <?php } ?>
                  
                <?php } ?>
            </div>
            
            <div class="tab-pane" id="tab-license">
                Для работы модуля требуется зарегистрировать лицензию.<br>
                Одна лицензия допускает использовать модуль только на одном домене.
                <br><br>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ключ вашей лицензии</label>
                    <div class="col-sm-5">
                        <input type="text" id="license_key" value="<?php echo $license_key; ?>" class="form-control" disabled="disabled"/> 
                    </div>
                </div>
                
                <?php if ($license_login) { ?> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ваш логин покупателя на <a href="https://opencartforum.com" target="_blank">opencartforum.com</a></label>
                    <div class="col-sm-5">
                        <input type="text" value="<?php echo htmlspecialchars($license_login); ?>" class="form-control"  disabled="disabled"/> 
                    </div>
                </div>
                <?php } ?>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ваш e-mail</label>
                    <div class="col-sm-5">
                        <input type="text" value="<?php echo htmlspecialchars($license_email); ?>" class="form-control" disabled="disabled"/> 
                    </div>
                </div>
                
                    <a class="btn btn-primary" href="javascript:void(0);" id="send_license">Зарегистрировать</a>
                    <br>
                
                <?php if ($license_status == 1) { ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> Ваша лицензия уже зарегистрирована.</div>
                <?php } else {?>
                    <br><?php echo $text_buy_module; ?>
                <?php } ?>
            </div>
            
          </div>
        
      </div>
    </div>
  </div>
</div> 

<script type="text/javascript"><!--
// метки описания
$('.export_tpl_info_link').click(function() {
    $(this).next().slideToggle();
});
    
// настройка приложения
$('#next_step').click(function() {
    var current = $('#setup_steps div.steps:visible');
    if ($(current).attr('id') == 'step_check_app_security') {
        return check_app_security();
    }
    next_step();
});

var next_step = function() {
    var next = $('#setup_steps div.steps:visible').next('div');
    if (next.length) {
        $('#setup_steps div.steps:visible').hide();
        $(next).show();
    }
    next = $('#setup_steps div.steps:visible').next('div');
    if (!next.length) {
        $('#next_step').hide()
    }
    $('#prev_step').show();
};

$('#prev_step').click(function() {
    var prev = $('#setup_steps div.steps:visible').prev('div');
    if (prev.length) {
        $('#setup_steps div.steps:visible').hide();
        $(prev).show();
    }
    prev = $('#setup_steps div.steps:visible').prev('div');
    if (!prev.length) {
        $('#prev_step').hide()
    }
    $('#next_step').show();
});

$('#new_setup').click(function() {
    $(this).hide();
    $('#setup_steps div.steps:visible').hide();
    $('#setup_steps div.steps:first').show();
    $('#next_step').show();
    $('.setup_done').hide();
    $('.no_setup').show();
});

// cron
$('.cmd_type').click(function () {
    $('#cmd_param').text($('.cmd_type:checked').val());
    $('#cmd_php_path').text($('#php_path').val());
    $('#cron_cmd').show();
});

// id приложения
$('#client_id').change(function (e) {
    $('#token_link').attr('href', 'https://oauth.vk.com/authorize?client_id=' + $('#client_id').val() + '&scope=photos,wall,groups,market,offline&redirect_uri=https://oauth.vk.com/blank.html&display=page&v=5.3&response_type=token&test_redirect_uri=1');
});


var check_app_security = function () {
    
    if (!$("#vk_export_access_token").val()) {
        alert('Вы не вставили строку!');
        return false;
    }
    
    $('#next_step').after('<span class="spinner"><img src="view/image/vkexport/spinner.gif"> пожалуйста, подождите...</span>');
    
    $('#next_step').hide();
    $('#prev_step').hide();
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($ckapseci); ?>",
      type: "POST",
      data: $("#vk_export_access_token").serialize(),
      dataType: "html"
    });
    
    request.done(function(data) {
        
        $('#next_step').show();
        $('#prev_step').show();
        $('#next_step').next('span.spinner').remove();
        
console.log(data.trim());
        if (data.trim() == 'success') {
            return next_step();
        }

        $('#myModal .modal-title').text('Проверка доступа');
        $('#myModal .modal-body').html(data);
        $('#myModal .modal-footer').hide();
        $('#myModal').modal();

    });
};

$(document).on('click', '#check_phone_number', function () {
    
    var request = $.ajax({
      url: "<?php echo htmlspecialchars_decode($cekfnbr); ?>",
      type: "POST",
      data: $('input[name="code"], #security_url').serialize(),
      dataType: "html"
    });
    
    request.done(function(data) {
        
        if (data == 'success') {
            return next_step();
        }

        $('#myModal .modal-title').text('Проверка доступа');
        $('#myModal .modal-body').html(data);
        $('#myModal .modal-footer').hide();
        $('#myModal').modal();
        
    });
});

// лицензия
$(document).on('click', '#send_license', function () {
    
    // сделать прогресс-спиннер 
    $(this).prop('disabled', true).css('opacity', '0.5');
    $(this).after('<span class="spinner"><img src="view/image/vkexport/spinner.gif"> пожалуйста, подождите...</span>');
    
    // old msg
    $('#send_license').prev('div.alert').remove();
    $.getJSON('<?php echo $get_license_url . '&token=' . $token; ?>', 
        function(data) {
            
            if (data.result && data.result == 'ok') {
                $('#send_license').before('<div class="alert alert-success">' + data.message + '</div>');
                $('#send_license').next('span.spinner').remove();
                $('#send_license').remove();
            }
            else {
                $('#send_license').before('<div class="alert alert-danger">' + data.message + '</div>');
                $('#send_license').next('span.spinner').remove();
                $('#send_license').prop('disabled', false).css('opacity', 1);
            }
    })
    .fail(function(data) {
        console.log( 'send_license error: ' + data );
        $('#send_license').before('<div class="alert alert-danger">Неизвестная ошибка</div>');
        $('#send_license').next('span.spinner').remove();
        $('#send_license').prop('disabled', false).css('opacity', 1);
    });
    
});


//--></script> 

<?php echo $footer; ?>
