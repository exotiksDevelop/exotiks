$(document).on('slickLoaded', function() {
	setTimeout(function() {
		$('.slider__slick').slick({
		  prevArrow: '<i class="slider__box-left-arrow arrow"></i>',
		  nextArrow: '<i class="slider__box-right-arrow arrow"></i>'
		});
		
		$('.product-detail__slider').slick({
		  prevArrow: '<i class="product-detail__slider-arrow-left"></i>',
		  nextArrow: '<i class="product-detail__slider-arrow-right"></i>'
		});
	}, 500);
});
