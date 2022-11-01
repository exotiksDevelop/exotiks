$(document).on('keyup', 'input[name="city"], input[name="shipping_address[city]"]', function(e) { 
	var imput_city = $(this);
	$.ajax({
		url: 'index.php?route=module/getcity',
		type: 'post',
		data: 'q=' + imput_city.val(),
		success: function(data) {			
			$('.results_cities_container').remove();
			imput_city.after(data)
			
		}
	});
});
$(document).on('click', 'input[name="city"], input[name="shipping_address[city]"]', function(e) { 
	var imput_city = $(this);
	$.ajax({
		url: 'index.php?route=module/getcity',
		type: 'post',
		data: 'q=' + imput_city.val(),
		success: function(data) {			
			$('.results_cities_container').remove();
			imput_city.after(data)
			
		}
	});
});


$(document).on('mouseup', '.results_cities_container>.result_list>ul>li', function(e) { 
	$(this).parent().parent().parent().parent().find('input[name="city"]').val($(this).attr('title'));
	$(this).parent().parent().parent().parent().find('input[name="shipping_address[city]"]').val($(this).attr('title'));
	$('[name="zone_id"]').val($(this).attr('zone_id'));
	$('[name="shipping_address[zone_id]"]').val($(this).attr('zone_id'));

	$(this).parent().parent().parent().parent().find('input[name="city"]').change();
	$('[name="zone_id"]').change();
	$(this).parent().parent().parent().parent().find('input[name="shipping_address[city]"]').change();
	$('[name="shipping_address[zone_id]"]').change();
});

jQuery(function($){
	$(document).mouseup(function (e){ 


		var input = $('input[name="city"]'); 
		if (input.length == 0) {
			var input = $('input[name="shipping_address[city]"]'); 
		}
		var container = input.parent().find('.results_cities_container>.result_list'); 
		var li = container.find('li');
		if ((!container.is(e.target) && container.has(e.target).length === 0) && (!input.is(e.target) && input.has(e.target).length === 0)) {			
			container.hide(); 
		} else {	
			if (!li.is(e.target) && li.has(e.target).length === 0) {
				$('.results_cities_container>.result_list').hide();
				container.show();
			} else {
				$('.results_cities_container>.result_list').hide();
	    		
			}
		}
	});
});




