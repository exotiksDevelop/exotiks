<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
	  <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-module').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <style>
	.ajaxstatus{
		cursor:pointer;
		color: #fff;
	}
	.ajaxstatus[data-value="0"]{
		background:rgb(245, 107, 107);
	}
	.ajaxstatus[data-value="1"]{
		background:rgb(117, 167, 77);
	}
  </style>
  <div class="container-fluid">
    <?php if ($error) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
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
        <h3 class="panel-title"><i class="fa fa-bars"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
			  <thead>
				<tr>
				  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				  <td class="text-left"><?php echo $text_title; ?></td>
				  <td class="text-left"><?php echo $text_short_description; ?></td>
				  <td class="text-center">В модуле</td>
				  <td class="text-left"><?php echo $text_date; ?></td>
				  <td class="text-right"><?php echo $text_action; ?></td>
				</tr>
			  </thead>
			  <tbody>
				<?php if ($all_news) { ?>
				  <?php foreach ($all_news as $news) { ?>
				  <tr>
					<td width="1" style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $news['news_id']; ?>" /></td>
					<td class="text-left"><a href="<?php echo $news['edit']; ?>"><?php echo $news['title']; ?></a></td>
					<td class="text-left"><?php echo $news['short_description']; ?></td>
					<td class="text-center ajaxstatus" id="news<?php echo $news['news_id']; ?>" data-id="<?php echo $news['news_id']; ?>" data-value="<?php echo $news['module']; ?>"><?php echo $news['module_status']; ?></td>
					<td class="text-left"><?php echo $news['date_added']; ?></td>
					<td class="text-right"><a href="<?php echo $news['edit']; ?>"><?php echo $text_edit; ?></a></td>
				  </tr>
				  <?php } ?>
				<?php } else { ?>
				  <tr>
					<td colspan="6" class="text-center"><?php echo $text_no_results; ?></td>
				  </tr>
				<?php } ?>
			  </tbody>
			</table>
          </div>
        </form>
		<div class="row">
          <div class="col-sm-12 text-center"><?php echo $pagination; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.ajaxstatus').click(function() {
	var news_id=$(this).data('id');
	var value=$(this).data('value');
	$.ajax({
		url: 'index.php?route=extension/news/setModule&token=<?php echo $token; ?>',
		type: 'get',
		data: {news_id:news_id,value:value},
		dataType: 'html',
		success: function(html) {
			if(html!=''){				
				$('#news'+news_id).html(html);
				if(value==1){
					$('#news'+news_id).data('value',0);
				} else {
					$('#news'+news_id).data('value',1);
				}
				if(value==0){ $('#news'+news_id).css('background','rgb(117, 167, 77)'); } else { $('#news'+news_id).css('background','rgb(245, 107, 107)'); }
			}
		}
	});
});
//--></script>
<?php echo $footer; ?>