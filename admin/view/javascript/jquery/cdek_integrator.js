$(document).ready(function() {
	
	$(".setting-city-name:not(.ui-autocomplete-input)").each(function(){
		
		var context = $(this).closest('div');
	
		$(this).autocomplete({
			source: function(request,response) {
				$.ajax({
					url: "//api.cdek.ru/city/getListByTerm/jsonp.php?callback=?",
					dataType: "jsonp",
					data: {
						q: function () { return request },
						name_startsWith: function () {
							return request;
						}
					},
					success: function(data) {
						if(data.geonames) {
							response($.map(data.geonames, function(item) {
								return {
									label: item.name,
									value: item.name,
									id: item.id
								}
							}));
						}
						
					}
				});
				
			},
			minLength: 1,
			select: function(event) {
				$('.setting-city-id', context).parent().find('.error').remove();
				$('.setting-city-id', context).val(event.id);
				$('.setting-city-name', context).val(event.value);
				$('.js.city-from', context).text(event.label).show();
				$('.setting-city-name', context).hide();
				
				$('.setting-city-name', context).trigger('cityselect');
				
				$('.setting-city-id', context).trigger('change');
				
			}
		});
			
	});
	
	$('.js.city-from').click(function(){

		 $('.setting-city-name', $(this).parent()).show().focus().trigger('keydown');
		 //console.log($('.setting-city-name', $(this).parent()));
		 $(this).hide();
	});
	
	$(".setting-city-name").blur(function(){
		
		var context = $(this).parent();
		
		if ($('.setting-city-id', context).val() != '') {
			$('.js.city-from', context).show();
			$(this).hide();
		}
	});
	
	$(".setting-city-name").change(function(){
		$('.setting-city-id', $(this).parent()).val('');
	});
						   
	$('.slider').on('change', function(event){
		var parent = $(this).closest('.parent');
		$(this).closest('tr').next('tr').find('.slider-content:first').slideToggle('fast', function(){
			var icon = ($(this).is(':visible')) ? '&#9650;' : '&#9660;';
			$('.status', parent).html(icon);
		});
	});

	/*$('.toggle').on('change', function(event) {
		var toggleTarget = $(this).data('toggletarget');
		console.log('toggle',toggleTarget);
		$(toggleTarget).toggle();
	});*/
	$(document).on('change', '.toggle', function() { 
		var toggleTarget = $(this).data('toggletarget');
		console.log('toggle',toggleTarget);
		$(toggleTarget).toggle();
	});
	
	$('p.link a.js').on('click', function(event){
		event.preventDefault();
		$(this).parent().next('.content').slideToggle('fast');
	});

});

function ajaxSend(el, data) {
	
	var url = $(el).attr('href');
	var context = $(el).closest('td');
	
	if ($(el).data('is_active') == 1) return FALSE;
	
	$.ajax({
		url: url,
		dataType: "json",
		beforeSend: function(jqXHR, settings){
			
			$('.success, .warning, .attention, .error').remove();
			
			if (data.beforeSend) {
				data.beforeSend(context);
			} else {
				$(context).append('<img class="loader" src="view/image/cdek_integrator/loader.gif" alt="Загрузка..." title="Загрузка..." />');
			}
			
			$(self).data('is_active', 1);
			
		},
		complete: function(jqXHR, textStatus) {
			
			if (data.complete) {
				data.complete(context);
			} else {
				$('.loader', context).remove();
			}
			
			$(self).data('is_active', 0);
		},
		success: function(json) {
			
			/*var type = (json.status == 'error') ? 'warning' : 'success';
			$('.box').before('<div class="' + type + '">' + json.message + '</div>');*/
			if (data.callback) {
				data.callback(el, json);
			}
		}
	});
	
}