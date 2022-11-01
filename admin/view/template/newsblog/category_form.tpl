<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $entry_meta_h1; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $entry_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
                <div class="col-sm-10">
                  <select name="parent_id" class="form-control">
                    <option value="0" selected="selected"><?php echo $text_none; ?></option>
                    <?php foreach ($categories as $category) { ?>
                    <?php if ($category['category_id'] == $parent_id) { ?>
                    <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $category_store)) { ?>
                        <input type="checkbox" name="category_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="category_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $category_store)) { ?>
                        <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                  <?php if ($error_keyword) { ?>
                  <div class="text-danger"><?php echo $error_keyword; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="image_size_width"><?php echo $entry_image_size; ?></label>
                <div class="col-sm-5">
                	<input type="text" name="image_size_width" value="<?php echo $image_size_width; ?>" placeholder="<?php echo $placeholder_image_size_width; ?>" id="image_size_width" class="form-control">
                </div>
                <div class="col-sm-5">
                	<input type="text" name="image_size_height" value="<?php echo $image_size_height; ?>" placeholder="<?php echo $placeholder_image_size_height; ?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="checkbox-show_preview"><?php echo $entry_show_preview; ?></label>
                <div class="col-sm-10">
                  <div class="checkbox"><label><input name="show_preview" type="checkbox" id="checkbox-show_preview" value="1" <? if ($show_preview) { ?>checked="checked"<? } ?> /></label></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-limit"><span data-toggle="tooltip" title="<?php echo $help_limit; ?>"><?php echo $entry_limit; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_sort_by; ?></label>
                <div class="col-sm-5">
                  <select name="sort_by" class="form-control">
                    <?php foreach ($sort_by_array as $sort_item_name_mysql => $sort_item_name) { ?>
                    <?php if ($sort_item_name_mysql == $sort_by) { ?>
                    <option value="<?php echo $sort_item_name_mysql; ?>" selected="selected"><?php echo $sort_item_name; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $sort_item_name_mysql; ?>"><?php echo $sort_item_name; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-5">
                  <select name="sort_direction" class="form-control">
                    <?php foreach ($sort_direction_array as $sort_item_name_mysql => $sort_item_name) { ?>
                    <?php if ($sort_item_name_mysql == $sort_direction) { ?>
                    <option value="<?php echo $sort_item_name_mysql; ?>" selected="selected"><?php echo $sort_item_name; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $sort_item_name_mysql; ?>"><?php echo $sort_item_name; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="checkbox-show_in_top"><?php echo $entry_show_in_top; ?></label>
                <div class="col-sm-4">
                  <div class="checkbox"><label><input name="show_in_top" type="checkbox" id="checkbox-show_in_top" value="1" <? if ($show_in_top) { ?>checked="checked"<? } ?> /></label></div>
                </div>
                <label class="col-sm-2 control-label" for="checkbox-show_in_top_articles"><?php echo $entry_show_in_top_articles; ?></label>
                <div class="col-sm-4">
                  <div class="checkbox"><label><input name="show_in_top_articles" type="checkbox" id="checkbox-show_in_top_articles" value="1" <? if ($show_in_top_articles) { ?>checked="checked"<? } ?> /></label></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="checkbox-show_in_sitemap"><?php echo $entry_show_in_sitemap; ?></label>
                <div class="col-sm-4">
                  <div class="checkbox"><label><input name="show_in_sitemap" type="checkbox" id="checkbox-show_in_sitemap" value="1" <? if ($show_in_sitemap) { ?>checked="checked"<? } ?> /></label></div>
                </div>
                <label class="col-sm-2 control-label" for="checkbox-show_in_sitemap_articles"><?php echo $entry_show_in_sitemap_articles; ?></label>
                <div class="col-sm-4">
                  <div class="checkbox"><label><input name="show_in_sitemap_articles" type="checkbox" id="checkbox-show_in_sitemap_articles" value="1" <? if ($show_in_sitemap_articles) { ?>checked="checked"<? } ?> /></label></div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-design">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="template_category"><span data-toggle="tooltip" title="<?php echo $help_template_category; ?>"><?php echo $entry_template_category; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="template_category" value="<?php echo $template_category; ?>" placeholder="<?php echo $placeholder_template_category; ?>" id="template_category" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="template_article"><span data-toggle="tooltip" title="<?php echo $help_template_article; ?>"><?php echo $entry_template_article; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="template_article" value="<?php echo $template_article; ?>" placeholder="<?php echo $placeholder_template_article; ?>" id="template_article" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="images_size_width"><?php echo $entry_images_size; ?></label>
                <div class="col-sm-5">
                	<input type="text" name="images_size_width" value="<?php echo $images_size_width; ?>" placeholder="<?php echo $placeholder_image_size_width; ?>" id="images_size_width" class="form-control">
                </div>
                <div class="col-sm-5">
                	<input type="text" name="images_size_height" value="<?php echo $images_size_height; ?>" placeholder="<?php echo $placeholder_image_size_height; ?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="images_size_width_articles_big"><?php echo $entry_images_size_articles_big; ?></label>
                <div class="col-sm-2">
                	<input type="text" name="images_size_articles_big_width" value="<?php echo $images_size_articles_big_width; ?>" placeholder="<?php echo $placeholder_image_size_width; ?>" id="images_size_articles_big_width" class="form-control">
                </div>
                <div class="col-sm-2">
                	<input type="text" name="images_size_articles_big_height" value="<?php echo $images_size_articles_big_height; ?>" placeholder="<?php echo $placeholder_image_size_height; ?>" class="form-control">
                </div>
                <label class="col-sm-2 control-label" for="images_size_width_articles_small"><?php echo $entry_images_size_articles_small; ?></label>
                <div class="col-sm-2">
                	<input type="text" name="images_size_articles_small_width" value="<?php echo $images_size_articles_small_width; ?>" placeholder="<?php echo $placeholder_image_size_width; ?>" id="images_size_articles_small_width" class="form-control">
                </div>
                <div class="col-sm-2">
                	<input type="text" name="images_size_articles_small_height" value="<?php echo $images_size_articles_small_height; ?>" placeholder="<?php echo $placeholder_image_size_height; ?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="date_format"><span data-toggle="tooltip" title="<?php echo $help_date_format; ?>"><?php echo $entry_date_format; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="date_format" value="<?php echo $date_format; ?>" placeholder="<?php echo $placeholder_date_format; ?>" id="date_format" class="form-control" />
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_layout; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $text_default; ?></td>
                      <td class="text-left"><select name="category_layout[0]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                      <td class="text-left"><select name="category_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
<?php if ($ckeditor) { ?>
ckeditorInit('input-description<?php echo $language['language_id']; ?>', '<?php echo $token; ?>');
<?php } else { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({
	height: 300,
    lang:'<?php echo $lang; ?>'
});
<?php } ?>
<?php } ?>
//--></script>

<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
<?php echo $footer; ?>