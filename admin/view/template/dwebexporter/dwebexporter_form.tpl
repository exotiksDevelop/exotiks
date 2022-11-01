<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">

                    <div class="tab-pane active" id="tab-links">

                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="name">     
                                <?php echo $entry_name;?>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name;?>" id="name" class="form-control" />
                                <?php if ($error_name) { ?>
                                    <div class="text-danger"><?php echo $error_name; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="export_id">
                                <span data-toggle="tooltip" title="<?php echo $help_export_id;?>">
                                    <?php echo $text_export_id;?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="export_id" value="<?php echo $export_id; ?>" placeholder="<?php echo $text_export_id;?>" id="export_id" class="form-control" />
                                 <?php if ($error_export_id) { ?>
                                    <div class="text-danger"><?php echo $error_export_id; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="export-url">
                                Url
                            </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" value="<?php echo $export_url; ?>" id="export-url" class="form-control" readonly>
                                    <span class="input-group-btn">
                                        <a class="btn btn-default" type="button" href="<?php echo $export_url;?>" target="_blank">
                                            <i class='fa fa-arrow-right'></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="use_all">
                                <span data-toggle="tooltip" title="<?php echo $help_use_all;?>">
                                    <?php echo $text_use_all;?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <input type="checkbox" name="all"  id="use_all_id" class="form-control" <?php if($all=='1') echo "checked"; ?>/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-category">
                                <span data-toggle="tooltip" title="<?php echo $help_categories; ?>">
                                    <?php echo $entry_category; ?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                                <div id="exporting-category" class="well well-sm" style="height: 150px; overflow: auto;">
                                    <?php foreach ($exporting_categories as $exporting_category) { ?>
                                    <div id="exporting-category<?php echo $exporting_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $exporting_category['name']; ?>
                                        <input type="hidden" name="exporting_category[]" value="<?php echo $exporting_category['category_id']; ?>" />
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="min-qty">
                                <span data-toggle="tooltip" title="<?php echo $help_min_qty;?>">
                                    <?php echo $text_min_qty;?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="min_qty" value="<?php echo $min_qty;?>" placeholder="<?php echo $text_min_qty;?>" id="min-qty" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="language">
                                <span data-toggle="tooltip" title="<?php echo $text_language;?>">   
                                    <?php echo $text_language;?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <select class="form-control" name="language" id='language'>
                                    <?php
                                    if($languages !=null)
                                    {
                                    foreach($languages as $key=>$lang)
                                    {
                                    $selected = $language == $lang["language_id"] ? "selected" : "";
                                    echo '<option value="'.$lang["language_id"].'" '.$selected.'>'.$lang["name"].'</option>'; 
                                    }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="use-custom-parser">
                                  <?php echo $text_use_custom_parser;?>
                            </label>
                            <div class="col-sm-10">                             
                                <input type="checkbox" name="use_custom_parser"  id="use-custom-parser" class="form-control" <?php if($use_custom_parser=='1') echo "checked"; ?>/>
                                <button type="button" class='btn btn-default pull-right reset-parser <?php if($use_custom_parser != '1') echo 'hidden'; ?>'>
                                    Reset to Default
                                </button>
                            </div>
                        </div>

                        <div class="form-group custom-parser <?php if($use_custom_parser != '1') echo 'hidden'; ?>">
                            <label class="col-sm-2 control-label" for="min-qty">
                                    <?php echo $text_custom_parser;?>
                            </label>
                            <div class="col-sm-10">
                               <div class="alert alert-info">
                                   <strong>Available sources</strong> 
                                   <p>
                                    product_id, model, sku, upc, ean, jan, isbn, mpn, location, quantity, stock_status_id, image, manufacturer_id, shipping, price,points, tax_class_id, date_available,
                                    weight, weight_class_id, length, width, height, length_class_id, subtract, minimum, sort_order, status, viewed, date_added, date_modified, category, categorylink
                                   </p>
                                   
                                   <strong>mathexpression</strong> 
                                   <p>+ - / *</p>
                               </div>
                                <textarea class="form-control" rows="40" name="custom_parser" id='custom-parser'>
                                    <?php echo $custom_parser;?>
                                </textarea>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<script type="text/javascript"><!--
// Category
    var baseUrl = '<?php echo $export_base_url;?>';
    $('input[name=\'category\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?route=dwebexporter/dwebexporter/categoriesautocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item['name'],
                            value: item['category_id']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            $('input[name=\'category\']').val('');

            $('#exporting-category' + item['value']).remove();

            $('#exporting-category').append('<div id="exporting-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="exporting_category[]" value="' + item['value'] + '" /></div>');
        }
    });
    
    $('#export_id').keydown(function(){
        $('#export-url').val(baseUrl+$(this).val());
    });
    
    $('#use-custom-parser').change(function(){
       $(this).is(':checked') ? $('.custom-parser').removeClass('hidden'):$('.custom-parser').addClass('hidden');
       $(this).is(':checked') ? $('.reset-parser').removeClass('hidden'):$('.reset-parser').addClass('hidden');
    });
    
    $('.reset-parser').click(function(){
        $.ajax({
                url: 'index.php?route=dwebexporter/dwebexporter/resetparser&token=<?php echo $token; ?>',
                dataType: 'json',
                success: function (json) {
                    $('#custom-parser').val(json.content);
                }
            });
    });

    $('#exporting-category').delegate('.fa-minus-circle', 'click', function () {
        $(this).parent().remove();
    });

//--></script>

</div>
<?php echo $footer; ?>
