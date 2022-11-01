<?php echo $header;?>
<?php echo $column_left;?>
<div id="content" class="OrderFollowUp">
<script type="text/javascript">
		NProgress.configure({
			showSpinner: false,
			ease: 'ease',
			speed: 500,
			trickleRate: 0.2,
			trickleSpeed: 200 
		});
	</script>
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
                <div class="storeSwitcherWidget">
                    <div>
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo $store['name']; if($store['store_id'] == 0) echo " <strong>(".$text_default.")</strong>"; ?>&nbsp;<span class="caret"></span><span class="sr-only">Навигация</span></button>
                        <ul class="dropdown-menu" role="menu">
                            <?php foreach ($stores  as $st) { ?>
                                <li><a href="index.php?route=<?php echo $modulePath ?>&store_id=<?php echo $st['store_id'];?>&token=<?php echo $token; ?>"><?php echo $st['name']; ?></a></li>
                            <?php } ?> 
                        </ul>
                    </div>
                </div>
                <h3 class="panel-title"><i class="fa fa-list"></i>&nbsp;<span style="vertical-align:middle;font-weight:bold;">Настройки модуля</span></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"> 
                    <input type="hidden" name="store_id" value="<?php echo $store['store_id']; ?>" />
                    <input type="hidden" name="<?php echo $moduleNameSmall; ?>_status" value="1" />
                    <div class="tabbable">
                        <div class="tab-navigation form-inline">
                            <ul class="nav nav-tabs mainMenuTabs">
                                <li><a href="#control_panel" data-toggle="tab"><i class="fa fa-power-off"></i>&nbsp;Настройки</a></li>
                            </ul>
                            <div class="tab-buttons">
                                <button type="submit" class="btn btn-primary save-changes"><i class="fa fa-check"></i>&nbsp;<?php echo $save_changes?></button>
                                <a onclick="location = '<?php echo $cancel; ?>'" class="btn btn-warning"><i class="fa fa-times"></i>&nbsp;<?php echo $button_cancel?></a>
                            </div> 
                        </div><!-- /.tab-navigation --> 
                        <div class="tab-content"> 
                            <div id="control_panel" class="tab-pane fade"><?php require_once(DIR_APPLICATION.'view/template/' . $modulePath . '/tab_controlpanel.php'); ?></div>
                            <div class="tab-pane" hidden><?php require_once(DIR_APPLICATION.'view/template/' . $modulePath . '/tab_support.php'); ?></div>
                        </div> <!-- /.tab-content --> 
                    </div><!-- /.tabbable -->
                </form>
            </div> 
        </div>
	</div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
	$('.mainMenuTabs a:first').tab('show'); // Select first tab
	$('.popup-list').children().last().children('a').click();
	if (window.localStorage && window.localStorage['currentTab']) {
		$('.mainMenuTabs a[href="'+window.localStorage['currentTab']+'"]').tab('show');
	}
	if (window.localStorage && window.localStorage['currentSubTab']) {
		$('a[href="'+window.localStorage['currentSubTab']+'"]').tab('show');
	}
	$('.fadeInOnLoad').css('visibility','visible');
	$('.mainMenuTabs a[data-toggle="tab"]').click(function() {
		if (window.localStorage) {
			window.localStorage['currentTab'] = $(this).attr('href');
		}
	});
	$('a[data-toggle="tab"]:not(.mainMenuTabs a[data-toggle="tab"], .popup_tabs a[data-toggle="tab"])').click(function() {
		if (window.localStorage) {
			window.localStorage['currentSubTab'] = $(this).attr('href');
		}
	});
</script>


