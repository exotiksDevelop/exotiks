<?php echo $header;?><?php echo $column_left; ?>
<div id="content">
   <div class="page-header">
    <div class="container-fluid">
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
            <div class="alert alert-danger autoSlideUp"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
             <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success autoSlideUp"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <script>$('.autoSlideUp').delay(3000).fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600);</script>
 		<?php } ?>    
 	<div class="panel panel-default">
        <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i>&nbsp;<span style="vertical-align:middle;font-weight:bold;">Настройки модуля</span></h3>
            <div class="storeSwitcherWidget">
            	<div class="form-group">
                	<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-pushpin"></span>&nbsp;<?php echo $store['name']; if($store['store_id'] == 0) echo $text_default; ?>&nbsp;<span class="caret"></span><span class="sr-only">Раскрыть меню</span></button>
                	<ul class="dropdown-menu" role="menu">
                    	<?php foreach ($stores  as $st) { ?>
                    		<li><a href="index.php?route=<?php echo $modulePath; ?>&store_id=<?php echo $st['store_id'];?>&token=<?php echo $token; ?>"><?php echo $st['name']; ?></a></li>
                    	<?php } ?> 
                	</ul>
            	</div>
            </div>
        </div>
        <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"> 
                <input type="hidden" name="store_id" value="<?php echo $store['store_id']; ?>" />
                <input type="hidden" name="giftTeaser_status" value="1" />
                    <div class="tabbable">
                        <div class="tab-navigation">
                            <ul class="nav nav-tabs mainMenuTabs">
	                        <li class="active"><a href="#tab_settings" data-toggle="tab"><i class="fa fa-cogs"></i>&nbsp;Основное</a></li>
	                        <li><a href="#tab_gift" data-toggle="tab"><i class="fa fa-gift"></i>&nbsp;Настройки</a></li>
                            <li><a href="#tab_widget" data-toggle="tab"><i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;Макет</a></li>
                        </ul>
                        <div class="tab-buttons">
                            <button type="submit" class="btn btn-success save-changes"><i class="fa fa-check"></i>&nbsp;<?php echo $save_changes;?></button>
                            <a onclick="location = '<?php echo $cancel; ?>'" class="btn btn-warning"><i class="fa fa-times"></i>&nbsp;<?php echo $button_cancel;?></a>
                        </div> 
                    </div>
                    <div class="tab-content">
                     	<?php
                        if (!function_exists('modification_vqmod')) {
                        	function modification_vqmod($file) {
                        		if (class_exists('VQMod')) {
                       				return VQMod::modCheck(modification($file), $file);
                        		} else {
                        			return modification($file);
                       			}
                        	}
                        }
						?>
                        
                  	    <div class="tab-pane active" id="tab_settings"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_settings.php'); ?></div>
                    	<div class="tab-pane" id="tab_widget"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_widget.php'); ?></div>
                    	<div class="tab-pane" id="tab_gift"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_gifts.php'); ?></div>
                        <div id="support" class="tab-pane hidden"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_support.php'); ?></div>
                    </div>
                </div>
            </form>
        </div> 
    </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
	var date = new Date();
	var token = '<?php echo $token ?>';
	var store = "<?php echo $store['store_id'] ?>";
	var button_add = '<?php echo $button_add; ?>';
	var button_remove = '<?php echo $button_remove; ?>';
	var button_edit = '<?php echo $button_edit; ?>';
	var lastGiftId = parseInt(<?php echo $maxGiftId; ?>);
	var currentGiftId = lastGiftId + 1;
	var text_column_left = '<?php echo $text_column_right; ?>';
	var text_column_right = '<?php echo $text_column_right; ?>';
	var text_content_top = '<?php echo $text_content_top; ?>';
	var text_content_bottom = '<?php echo $text_content_bottom;?>';
	var text_enabled = '<?php echo $text_enabled; ?>';
	var text_disabled = '<?php echo $text_disabled;?>';
	var entry_layout = '<?php echo $entry_layout;?>';
	var entry_status = '<?php echo $entry_status; ?>';
	var entry_sort_order = '<?php echo $entry_sort_order;?>';
	var condition_data = new Array();
</script>

<script type="text/javascript">
$(document).delegate('#tab_gift .pagination a', 'click',  function(e){ 
  e.preventDefault(); 
  $.ajax({
      url: this.href,
      type:'post',
      dataType:'html',
      success: function(data) { 
	  
	  		$('#giftListHead').replaceWith($(data).find('#giftListHead')); 
    		$('#giftList').replaceWith($(data).find('#giftList'));
    		$('.pagination').replaceWith($(data).find('.pagination'));
    		tooltips = $('.giftListRow a[id^="item_"]');
    		tooltips.each(function(i,l){itemTooltip($(l), $(l).attr('data-image'), $(l).text())})
    		$('[data-toggle="tooltip"]').tooltip();
			
       // $('.giftForm').html(data);
      }
    });
});
</script>
