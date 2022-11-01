<script>
	
	var uri = location.pathname + location.search;
	var documentReady = false;
	var windowLoad = false;
	var isBodyClicked = false;
	var delay = 500;
	var timeout = 1000;
	var product_id = '<?php echo $product_id ?>';
	
	$(document).ready(function() {
		documentReady = true;
	});
	
	$(window).on('load', function() {
		windowLoad = true;
	});
	
	$.ajax({
		url: '<?php echo $url; ?>',
		type: 'POST',
		data: {'uri' : uri, product_id:product_id},
		dataType: 'json',
		success: function (response) {
			for(entry in response) {
		
				if(response[entry].match) {
					repeat = response[entry].repeat;
					popup_id = response[entry].id;

					if(response[entry].delay>0) {
						delay += (response[entry].delay*1000);
					}

					if(response[entry].timeout>0) {
						timeout += (response[entry].timeout*1000);
					} else {
						timeout = false;
					}

					if(response[entry].event == 0) { // Document ready event  		
						if (documentReady) {		
							showSmartNotificationsPopup(response[entry].popup_id, response[entry].title, response[entry].description, response[entry].template, response[entry].icon, response[entry].position, response[entry].open_animation, response[entry].close_animation,response[entry].show_icon,response[entry].icon_type,response[entry].icon_image);
						} else {
							$(document).ready(function(){   
								showSmartNotificationsPopup(response[entry].popup_id, response[entry].title, response[entry].description, response[entry].template, response[entry].icon, response[entry].position, response[entry].open_animation, response[entry].close_animation,response[entry].show_icon,response[entry].icon_type,response[entry].icon_image);
							});
						}
					}
					
					
					if(response[entry].event == 1) { // Window load event

						if(windowLoad) {

							showSmartNotificationsPopup(response[entry].popup_id, response[entry].title, response[entry].description, response[entry].template, response[entry].icon, response[entry].position, response[entry].open_animation, response[entry].close_animation,response[entry].show_icon,response[entry].icon_type,response[entry].icon_image);
						}
						else {
							$(window).on('load', function() {
								showSmartNotificationsPopup(response[entry].popup_id, response[entry].title, response[entry].description, response[entry].template, response[entry].icon, response[entry].position, response[entry].open_animation, response[entry].close_animation,response[entry].show_icon,response[entry].icon_type,response[entry].icon_image);
							});
						}
					 
					}
				 
					if(response[entry].event == 2) { // Body click event
						$('body').click(function() {
							if(isBodyClicked == false) {
								showSmartNotificationsPopup(response[entry].popup_id, response[entry].title, response[entry].description, response[entry].template, response[entry].icon, response[entry].position, response[entry].open_animation, response[entry].close_animation, response[entry].show_icon,response[entry].icon_type,response[entry].icon_image);
								isBodyClicked = true;
							}	
						});
					}

			  	}

			}
			
		}
	});
	
		var showSmartNotificationsPopup = function (popup_id, title, description, template, icon, position,open_animation,close_animation, show_icon,typeOfIcon,image) { 

		$(document).on('smartNotificationsLoaded', function() {
		setTimeout(function() {
			
			var layout;

			if (show_icon==1 && typeOfIcon == 'p' ) {
			 	layout =  '<div class="noty_message pop-activity ' + template + '"><div class="icon"><i class="fa ' + icon + '"></i></div><div class="noty_text"></div><div class="noty_close">test</div></div>'
			} else if (show_icon == 1 && typeOfIcon == 'u'){
				layout = '<div class="noty_message pop-activity ' + template + '"><div class="image"><img src="'+image+'"></div><div class="noty_text"></div><div class="noty_close">test</div></div>'
			} else  {
				layout =  '<div class="noty_message pop-activity ' + template + '"><div class="noty_text"></div><div class="noty_close">test</div></div>'
			}
		

			var n = noty({
                text        : '<h3>' + title + '</h3><p>' + description + '</p>',
                dismissQueue: true,
                layout      : position,
                closeWith   : ['click'],
                theme		: 'smartNotifications',
                timeout 	: timeout,
                template	: layout,
                maxVisible  : 10,
                animation   : {
                    open  : 'animated '+open_animation,
                    close : 'animated '+close_animation,
                    easing: 'swing',
                    speed : 1500
                }
            });							
		}, delay);
		});
		
	};
</script>