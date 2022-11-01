$(document).ready(function(){
 	now = new Date();
 	date = now.getDate() + '.' + now.getMonth() + '.' + now.getFullYear() + ' | ' + now.getHours() + ':' + now.getMinutes();
 	currentList = "index.php?route=module/giftTeaser/giftList&token=" + token + "&store_id=" + store;
 	giftList(currentList);	
	tofbkeCSScheckbox();
	createBinds();

    if (window.localStorage && window.localStorage['currentTab']) {
        $('.mainMenuTabs a[href='+window.localStorage['currentTab']+']').trigger('click');  
    }
    if (window.localStorage && window.localStorage['currentSubTab']) {
        $('a[href='+window.localStorage['currentSubTab']+']').trigger('click');  
    }
    $('.fadeInOnLoad').css('visibility','visible'); 
    $('.mainMenuTabs a[data-toggle="tab"]').click(function() {
        if (window.localStorage) {
            window.localStorage['currentTab'] = $(this).attr('href');
        }
    });

    $('a[data-toggle="tab"]:not(.mainMenuTabs a[data-toggle="tab"])').click(function() {
        if (window.localStorage) {
            window.localStorage['currentSubTab'] = $(this).attr('href');
        }
    });

    $(document).on('change', 'select[name="selectStore"]', function(e) {
    $('.btn.btn-primary.save-changes').trigger('click');
        window.location = "index.php?route=module/giftTeaser&store_id=" + $('select[name="selectStore"]').val() + "&token=" + token;
    });
    
   	$(document).on('click','.pagination a', function(e) {
		e.preventDefault();
		currentList = this.href;
	});
	
	$(document).on('click','#giftListHead a', function(e) {
		e.preventDefault();
		currentList = this.href;
		giftList(currentList);
	});
});
var editors = new Array();
var tofbkeCSScheckbox = function() {
  $('input[type=checkbox][id^=buttonPosCheckbox]').each(function(index, element) {
    if ($(this).is(':checked')) {
      $($(this).attr('data-textinput')).removeAttr('disabled');
    } else {
      $($(this).attr('data-textinput')).attr('disabled','disabled');
    }
  });
}

var createBinds = function() {
  $('input[type=checkbox][id^=buttonPosCheckbox]').unbind('change').bind('change', function() {
    tofbkeCSScheckbox();
  });
  
  $('.widgetPositionOptionBox').unbind('change').bind('çhange', function() {
    $($(this).attr('data-checkbox')).removeAttr('checked');
    tofbkeCSScheckbox();
  });
};



function giftList(href){
	$.ajax({
    	url: href,
    	dataType: "html",
    	success: function(data) {
    		$('#giftListHead').replaceWith($(data).find('#giftListHead')); 
    		$('#giftList').replaceWith($(data).find('#giftList'));
    		$('.pagination').replaceWith($(data).find('.pagination'));
    		tooltips = $('.giftListRow a[id^="item_"]');
    		tooltips.each(function(i,l){itemTooltip($(l), $(l).attr('data-image'), $(l).text())})
    		$('[data-toggle="tooltip"]').tooltip();
    	}
   });
}

function removeGift(ob, refreshList) {
	row = ob.parents('tr');
	bootbox.confirm('Are you sure you want to delete this gift?', function(result){
		if(result){ 
			row.remove();
			$.ajax({
				url: "index.php?route=module/giftTeaser/removeGift&token=" + token,
				data: { gift_id: ob.attr('gift-id')},
				type: 'POST',
				dataType: "html"
			});
			if(refreshList){
				giftList(currentList);
			}
		}
	});
}

function editItem(gift_id, item_id, item_name) {
 	link = './index.php?route=module/giftTeaser/giftForm&gift_id=' + gift_id + '&item_id=' + item_id + '&item_name=' + encodeURIComponent(item_name) + '&token=' + token;
	$('#modal').load(link, function() {	
		$('#modal').modal();
		conditionOption($('select[name="selectCondition"]').val());
		autocompleteProduct($('#some-product-selector input[name="product"]'), 'some-gift-product');
		autocompleteCategory($('#category-selector input'), 'gift-category');
		autocompleteProduct($('#certain-product-selector input[name="product"]'), 'certain-gift-product');
		autocompleteManufacturer($('#manufacturer-selector input[name="manufacturer"]'), 'gift-manufacturer');
 		$(document).on('click', '.removeIcon', function(){
 		 	$(this).parent().remove();
 		});
 	});
	$(document).on('change', 'select[name="selectCondition"]', function(){
		conditionOption($('select[name="selectCondition"]').val());
	});	
}

function saveItem(ob) {
	
	form = ob.parents('.modal')
 	type = form.find('select[name="selectCondition"] option:selected').val();
    select_total = form.find('select[name="select_total"] option:selected').val();
 	total_amount = form.find('input[name="total_amount"]').val();
    total_amount_max = form.find('input[name="total_amount_max"]').val();
	someProducts = scrollboxToJson(form.find('#some-product-selector .scrollbox'));
	certainProducts = scrollboxToJson(form.find('#certain-product-selector .scrollbox'));
	categories = scrollboxToJson(form.find('#category-selector .scrollbox'));
	manufacturer = scrollboxToJson(form.find('#manufacturer-selector .scrollbox'));
	gift_id = form.find('input[type="hidden"][name="gift-parameters"]').attr('gift-id'); 
	descriptions = {};
	$('textarea[id^="desc_"]').each(function(index, element) {
        descriptions[$(element).attr('id')] = $(element).val();
    });
	
	customer_groups = new Array(); 
	$("[name*='customer_group']").each(function() {
		if(this.checked){
			customer_groups.push(this.id);
		} 
	});

	
	sort_order = form.find('input[type="number"][name="sort_order"]').val();
	some_product_quantity = form.find('input[type="number"][name="some_product_quantity"]').val();
	certain_product_quantity = form.find('input[type="number"][name="certain_product_quantity"]').val();
	
	giftData = {
		'gift_id': gift_id,
		'item_id': form.find('input#itemParams').attr('item-id'),
		'type':type,
		'properties':{ 'select_total':select_total,'total':total_amount, 'total_max':total_amount_max, 'some':someProducts, 'certain':certainProducts, 'categories':categories,'some_product_quantity': some_product_quantity, 'certain_product_quantity': certain_product_quantity, 'customer_group':customer_groups, 'manufacturer': manufacturer},
		'start_date': form.find('input[name="startDate"]').val(),
		'end_date': form.find('input[name="endDate"]').val(),
		'descriptions': descriptions,
		'sort_order': sort_order,
		'store_id': store,
	};

	$.ajax({   
		url: 'index.php?route=module/giftTeaser/giftForm&token=' + token,
		type: 'post',
		dataType:'html', 
		data: giftData, 
		success: function(data) {
			giftList(currentList); 
			$('.modal').modal('hide'); 
		}
	});
}

function autocompleteProduct(ob, action) { 
	ob.autocomplete({
		delay: 500,
		source: function (request, response) {
			$.ajax({
				url: 'index.php?route=module/giftTeaser/autocompleteProduct&token=' + getURLVar('token'),
				dataType: 'json',
				type: 'POST',
				data: {filter_name: request, store_id: store},
				success: function (json) {
					response($.map(json, function (item) {
						return {
							label: item['name'],
							id: item['product_id'],
							quantity: item['quantity'],
							price: item['price'],
							viewed: item['viewed'],
							image: item['image'],
							link: item['link'],
							date_added: item['date_added'],
							value: item['product_id']
						}
					}));
				}
			});
		},
		select: function (item) {  
	 		switch(action){ 
	 			case 'form': editItem(-1, item.id); break;
	 			case 'some-gift-product': addScrollBoxItem($('#some-gift-product'), item.id, item.label); break;
	 			case 'certain-gift-product': addScrollBoxItem($('#certain-gift-product'), item.id, item.label); break;
	 		}
			return false;
		}
	});
	
	$('#certain-gift-product').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});	
	$('#some-gift-product').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});	
}

function addScrollBoxItem(scrollbox, id, name) {
 	scrollbox.append('<div id="' + id + '"><i class="fa fa-minus-circle"></i>' + name + '<input type="hidden" value="' + id + '" item-name="' + name + '"/></div>');
}



function autocompleteCategory(ob, action) {
	ob.autocomplete({
		delay: 500,
		source: function (request, response) {
			$.ajax({
				url: 'index.php?route=module/giftTeaser/autocompleteCategory&token=' + getURLVar('token')  + '&store_id=' + store,
				dataType: 'json',
				type: 'POST',
				data: {filter_name: request, store_id: store},
				success: function (json) { 
					response($.map(json, function (item) {
						return {
							label: item['name'],
							id: item['category_id'],
							quantity: '',
							price: '',
							viewed: '',
							image: '',
							link: item['link'],
							date_added: item['date_added'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		select: function (item) {
			$('input[name=\'category\']').val('');
			switch(action){
	 			case 'addRow': addRow(item, button_add, button_remove);
	 			case 'gift-category': addScrollBoxItem($('#gift-category'), item.id, item.label); break;
	 		}
			return false;
		}		
	});
	
	$('#gift-category').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});	
}

function autocompleteManufacturer(ob, action) {
	ob.autocomplete({
		delay: 500,
		source: function (request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=' + getURLVar('token')  + '&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				type: 'POST',
				success: function (json) { 
					response($.map(json, function (item) {
						return {
							id: item['manufacturer_id'],
							label: item['name']
						}
					}));
				}
			});
		},
		select: function (item) { 
			//$('input[name=\'manufacturer\']').val(''); 
	 		switch(action){ 
	 			case 'form': editItem(-1, item.id); break;
	 			case 'gift-manufacturer': addScrollBoxItem($('#gift-manufacturer'), item.id, item.label); break;
	 		}
			return false;
		}
						
			});
	
	$('#gift-manufacturer').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});	
}




function isFloat(n) {
    return n === +n && n !== (n|0);
}

function isInteger(n) {
    return n === +n && n === (n|0);
}

function isPInt(n) {
   return n > 0 && n % 1 === 0;
}

function conditionOption(option) {
	switch(option){
		case '1': $('.option-widget').hide(); $('.option-widget#total_amount').show(); $('.option-widget#total_subtotal').show();		
		break;
 		case '2': $('.option-widget').hide(); $('.option-widget#certain-product-selector').show();
 		break;
 		case '3': $('.option-widget').hide(); $('.option-widget#some-product-selector').show();
 		break;
 		case '4': $('.option-widget').hide(); $('.option-widget#category-selector').show();
 		break;
 		case '5': $('.option-widget').hide(); $('.option-widget#manufacturer-selector').show();
		default:  $('#option-widget').hide();
	}
}

function validateNumber(input) {  
  	var regex = /[0-9]|\./; 
  	if(!regex.test(input.val())) { 
    	input.css({'background':'#f2dede'}); 
    	return false; 
  	} else { 
    	input.css({'background':'#fff'}); 
    	return true; 
  	} 
}  

function scrollboxToJson(scrollbox){
 	scrollboxElements = scrollbox.children('div');
 	elements = new Array(); 
 	jQuery.each(scrollboxElements, function(i, val) { 
 		elements.push($(val).attr('id'));
 	});
	return elements;
}

