$(document).ready(function(){
	call_forgotten_cart();
});

function call_forgotten_cart(){
	$.ajax({
	  url: "index.php?route=module/forgotten_cart/ajax"
	}).done(function(){
		setTimeout(call_forgotten_cart, 15000);
	});
}