<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
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
    <?php if (isset($success)) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tag" class="form-horizontal">
          <div class="col-sm-12">
            <div class="form-group">
              <button class="btn btn-primary" id="add_option" title="<?php echo $text_add_option; ?>" data-toggle="tooltip" type="button" data-original-title="<?php echo $text_add_option; ?>">
                <i class="fa fa-plus-circle"></i>&nbsp;<?php echo $text_add_option; ?>
              </button>
              <button class="btn btn-primary" id="add_attribute" title="<?php echo $text_add_attribute; ?>" data-toggle="tooltip" type="button" data-original-title="<?php echo $text_add_attribute; ?>">
                <i class="fa fa-plus-circle"></i>&nbsp;<?php echo $text_add_attribute; ?>
              </button>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label class="col-sm-1 control-label" for="category_id"><?php echo $text_category; ?></label>
              <div class="col-sm-2">
                <select class="form-control" name="category_id" id="category_id">
                  <option></option>
                  <?php foreach ($categories as $category) { ?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="col-sm-12" id="filter">

          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label class="col-sm-1 control-label" for="category_id"><?php echo $text_tag; ?></label>
              <div class="col-sm-2">
                <select class="form-control" name="tag_id" id="tag_id">
                  <option></option>
                  <?php foreach ($tags as $tag) { ?>
                    <option value="<?php echo $tag['tag_id']; ?>"><?php echo $tag['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-sm-1">
                <button class="btn btn-primary" id="run" title="<?php echo $text_run; ?>" data-toggle="tooltip" type="button" data-original-title="<?php echo $text_run; ?>" onClick="$('#form-tag').submit()">
                  <i class="fa fa-pencil"></i>&nbsp;<?php echo $text_run; ?>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  var i = 1; 
  $(function(){
    $("#add_option").on('click', function(){
      html = '<div class="form-group" id="item_'+i+'">';
        html += '<label class="col-sm-1 control-label" for="filter_'+i+'">';
          html += '<?php echo $text_option_name; ?>';
        html += '</label>';
        html += '<div class="col-sm-2">';
          html += '<select class="form-control opt" name="filter['+i+']" id="filter_'+i+'">';
            html += '<option></option>';
            <?php foreach($options as $option) { ?>
              html += '<option value="<?php echo $option['option_id'] ?>"><?php echo $option['name'] ?></option>';
            <?php } ?>
          html += '</select>';
        html += '</div>';
        html += '<label class="col-sm-1 control-label" for="filterv_'+i+'">';
          html += '<?php echo $text_option_value; ?>';
        html += '</label>';
        html += '<div class="col-sm-2">';
          html += '<select class="form-control optv" name="filterv['+i+']" id="filterv_'+i+'">';
            html += '<option></option>';
          html += '</select>';
        html += '</div>';
        html += '<div class="col-sm-1">';
          html += '<div">';
            html += '<button class="btn btn-danger remove" id="'+i+'" type="button">';
              html += '<i class="fa fa-minus-circle"></i>';
            html += '</button>';
          html += '</div>';
        html += '</div>';
      html += '</div>';
      $('#filter').append(html);
      i++;
    });

    $("body").on('change', '.opt', function(){
      option_id = $(this).val();
      opt = $(this).attr('id');
      opt = opt.split('_');
      opt = opt[1];
      $("#filterv_"+opt).empty();
      $.ajax({
        url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=',
        dataType: 'json',
        success: function(json) {
          for (k in json){
            if (json[k]['option_id'] == option_id){
              console.log(k);
              console.log(json[k]);
              for (oopt in json[k]['option_value']){
                $("#filterv_"+opt).append('<option value="'+json[k]['option_value'][oopt]['option_value_id']+'">'+json[k]['option_value'][oopt]['name']+'</option>');
              }
            }
          }
        }
      });
    });

    $("#add_attribute").on('click', function(){
      html = '<div class="form-group" id="item_'+i+'">';
        html += '<label class="col-sm-1 control-label" for="filter_'+i+'">';
          html += '<?php echo $text_attribute_name; ?>';
        html += '</label>';
        html += '<div class="col-sm-2">';
          html += '<select class="form-control attr" name="filtera['+i+']" id="filter_'+i+'">';
            html += '<option></option>';
            <?php foreach($attributes as $attribute) { ?>
              html += '<option value="<?php echo $attribute['attribute_id'] ?>"><?php echo $attribute['name'] ?></option>';
            <?php } ?>
          html += '</select>';
        html += '</div>';
        html += '<label class="col-sm-1 control-label" for="filterv_'+i+'">';
          html += '<?php echo $text_attribute_value; ?>';
        html += '</label>';
        html += '<div class="col-sm-2">';
          html += '<input type="text" class="form-control attrv" name="filterva['+i+']" id="filterv_'+i+'">';
        html += '</div>';
        html += '<div class="col-sm-1">';
          html += '<div">';
            html += '<button class="btn btn-danger remove" id="'+i+'" type="button">';
              html += '<i class="fa fa-minus-circle"></i>';
            html += '</button>';
          html += '</div>';
        html += '</div>';
      html += '</div>';
      $('#filter').append(html);
      i++;
    });

    $("body").on('click','.remove', function(){
      $("#item_"+$(this).attr('id')).remove();
    })
  })
</script>
<?php echo $footer; ?> 