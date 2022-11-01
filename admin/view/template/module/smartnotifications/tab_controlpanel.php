<div class="tabbable tabs-left" id="popup_tabs">
	
    <ul class="nav nav-tabs popup-list">
        <li class="static"><a class="addNewPopUp"><i class="fa fa-plus"></i> Добавить уведомление</a></li>
        <?php if (isset($moduleData['SmartNotifications'])) { ?>
            <?php foreach ($moduleData['SmartNotifications'] as $popup) { ?>
            <li><a href="#popup_<?php echo $popup['id']; ?>" data-toggle="tab" data-popup-id="<?php echo $popup['id']; ?>"><i class="fa fa-list-alt"></i> Уведомление <?php echo $popup['id']; ?> <i class="fa fa-minus-circle removePopUp"></i>
                <input type="hidden" name="<?php echo $moduleNameSmall; ?>[SmartNotifications][<?php echo $popup['id']; ?>][id]" value="<?php echo $popup['id']; ?>" />
                </a> </li>
            <?php } ?>
        <?php } ?>
    </ul>
    <div class="tab-content popup-settings">
            <?php if (isset($moduleData['SmartNotifications'])) { ?>
            <?php foreach ($moduleData['SmartNotifications'] as $popup) { 
                require(DIR_APPLICATION.'view/template/' . $modulePath . '/tab_popuptab.tpl');
				
            } ?>
        <?php } ?>
    </div>
</div>
        
<script type="text/javascript"><!--
// Add PopUp
function addNewPopUp() {
	count = $('.popup-list li:last-child > a').data('popup-id') + 1 || 1;
	var ajax_data = {};
	ajax_data.token = '<?php echo $token; ?>';
	ajax_data.store_id = '<?php echo $store['store_id']; ?>';
	ajax_data.popup_id = count;
	
	$.ajax({
		url: 'index.php?route=<?php echo $modulePath; ?>/get_smartnotifications_settings',
		data: ajax_data,
		dataType: 'html',
		beforeSend: function() {
			NProgress.start();
		},
		success: function(settings_html) {
		$('.popup-settings').append(settings_html);
	
			if (count == 1) { $('a[href="#popup_'+ count +'"]').tab('show'); }
			tpl 	= '<li>';
			tpl 	+= '<a href="#popup_'+ count +'" data-toggle="tab" data-popup-id="'+ count +'">';
			tpl 	+= '<i class="fa fa-list-alt"></i> Уведомление '+ count;
			tpl 	+= '<i class="fa fa-minus-circle removePopUp"></i>';
			tpl 	+= '<input type="hidden" name="<?php echo $moduleNameSmall; ?>[SmartNotifications]['+ count +'][id]" value="'+ count +'"/>';
			tpl 	+= '</a>';
			tpl	+= '</li>';
			
			$('.popup-list').append(tpl);
			
			NProgress.done();
			$('.popup-list').children().last().children('a').trigger('click');
			window.localStorage['currentSubTab'] = $('.popup-list').children().last().children('a').attr('href');
			$('.removePopUp').on('click', function(e) { removePopUp(this); });
		}
	});
}

// Remove PopUp
function removePopUp(e) {
	tab_link = $(e).parent();
	tab_pane_id = tab_link.attr('href');
	
	var confirmRemove = confirm('Действительно удалить ' + tab_link.text().trim() + '?');
	
	if (confirmRemove == true) {
		tab_link.parent().remove();
		$(tab_pane_id).remove();
		
		if ($('.popup-list').children().length > 1) {
			$('.popup-list > li:nth-child(2) a').tab('show');
			window.localStorage['currentSubTab'] = $('.popup-list > li:nth-child(2) a').attr('href');
		}
	}
	
}

// Events for the Add and Remove buttons
$(document).ready(function() {
	// Add New Label
	$('.addNewPopUp').click(function(e) { addNewPopUp(); });
	// Remove Label
	$('.removePopUp').on('click', function(e) { removePopUp(this); });
});



// Display & Hide the log tab
$(function() {
    var $typeSelector = $('#LogChecker');
    var $toggleArea = $('#log_tab');
	 if ($typeSelector.val() === 'yes') {
            $toggleArea.show(); 
        }
        else {
            $toggleArea.hide(); 
        }
    $typeSelector.change(function(){
        if ($typeSelector.val() === 'yes') {
            $toggleArea.show(300); 
        }
        else {
            $toggleArea.hide(300); 
        }
    });
});



// Selectors for discount
function selectorsForPopups() {
	$(document).find('.popups').find('#startTime').datetimepicker({pickDate: false});
    $(document).find('.popups').find('#endTime').datetimepicker({pickDate: false});

	$('.methodTypeSelect').each(function() {
		if($(this).val() == 0) {
			$(this).parents('.popups').find('.specURL').hide();
			$(this).parents('.popups').find('.excludeURL').hide();
			$(this).parents('.popups').find('.cssSelector').hide();
		}
		else if($(this).val() == 1) {
			$(this).parents('.popups').find('.specURL').hide();
			$(this).parents('.popups').find('.excludeURL').show();
			$(this).parents('.popups').find('.cssSelector').hide();
		}
		else if($(this).val() == 2) {
			$(this).parents('.popups').find('.specURL').show();
			$(this).parents('.popups').find('.excludeURL').hide();
			$(this).parents('.popups').find('.cssSelector').hide();
		}
		else if($(this).val() == 3) {
			$(this).parents('.popups').find('.specURL').hide();
			$(this).parents('.popups').find('.excludeURL').hide();
			$(this).parents('.popups').find('.cssSelector').show();
		}
	});

	$('.methodTypeSelect').on('change', function(e){ 
		if($(this).val() == 0) {
			$(this).parents('.popups').find('.specURL').hide(200);
			$(this).parents('.popups').find('.excludeURL').hide(200);
			$(this).parents('.popups').find('.cssSelector').hide(200);
		}
		else if($(this).val() == 1) {
			$(this).parents('.popups').find('.specURL').hide(200);
			$(this).parents('.popups').find('.excludeURL').show(200);
			$(this).parents('.popups').find('.cssSelector').hide(200);
		}
		else if($(this).val() == 2) {
			$(this).parents('.popups').find('.specURL').show(200);
			$(this).parents('.popups').find('.excludeURL').hide(200);
			$(this).parents('.popups').find('.cssSelector').hide(200);
		}
		else if($(this).val() == 3) {
			$(this).parents('.popups').find('.specURL').hide(200);
			$(this).parents('.popups').find('.excludeURL').hide(200);
			$(this).parents('.popups').find('.cssSelector').show(200);
		}
	});

	$('.timeIntervalSelect').each(function(e){ 
		if($(this).val() == 0) {
			$(this).parents('.popups').find('.timeInterval').hide();
		}
		else {
			$(this).parents('.popups').find('.timeInterval').show();
		}
	});

	$('.timeIntervalSelect').on('change', function(e){ 
		if($(this).val() == 0) {
			$(this).parents('.popups').find('.timeInterval').hide(200);
		}
		else {
			$(this).parents('.popups').find('.timeInterval').show(200);
		}
	});

	$('.repeatSelect').each(function(e){ 
		if($(this).val() == 2) {
			$(this).parents('.popups').find('.daysPicker').show();
		}
		else {
			$(this).parents('.popups').find('.daysPicker').hide();
		}
	});

	$('.repeatSelect').on('change', function(e){ 
		if($(this).val() == 2) {
			$(this).parents('.popups').find('.daysPicker').show(200);
		}
		else {
			$(this).parents('.popups').find('.daysPicker').hide(200);
		}
	});

	$('.random_slider').each(function(e){ 
		$(this).slider({});
	});

	$('.icon-picker').each(function() {
		$(this).iconpicker();
	});
}

// Initialize selector for discount
$(function() {
	selectorsForPopups();
});
</script>

