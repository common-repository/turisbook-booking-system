var checkin = "";
var checkin_time = 0;
var checkout = "";

(function( tbs_shortcode_calendar, $, undefined ) {
	tbs_shortcode_calendar.hasCheckin = function(){return checkin.trim() != "";}
	tbs_shortcode_calendar.hasCheckout = function(){return checkout.trim() != "";}

	tbs_shortcode_calendar.getCalendar = function(year, month){
		var unit_id = $('.unit_id').val();
		var hotel_id = $('.hid').val();
		$.post(TBS.url, {action: "Turisbook_bs_Calendar",_ajax_nonce: TBS.nonce, unit_id:unit_id, hotel_id:hotel_id, year:year, month:month}, function(data) {
			var result = jQuery.parseJSON(data);
			if(result.status){
				$("#calendar_container").html(result.html);

				if(tbs_shortcode_calendar.hasCheckin()){
					$(".week .day-wrap[date='"+checkin+"']").addClass("selected-checkin");
				}
			}
		});
	}

	tbs_shortcode_calendar.getRateCategories = function(checkin, checkout){
		var unit_id = $('.unit_id').val();
		$.post(TBS.url, {action: "Turisbook_bs_getRatecategories", _ajax_nonce: TBS.nonce, checkin: checkin, checkout: checkout, unit_id:unit_id}, function(data) {
			var result = jQuery.parseJSON(data);
			if(result.status){
				$(".tbs-room-rt").html(result.html);
			}

		});
	}

	tbs_shortcode_calendar.setCheckin = function(obj){
		$('.selected-checkin').removeClass("selected-checkin");
		$('.selected-checkout').removeClass("selected-checkout");
		$('.day-between').removeClass("day-between");
		obj.addClass('selected-checkin');
		checkin = obj.attr("date");
		checkin_time = parseInt(obj.attr("time"));
		checkout = "";
		checkout_time = 0;
		$('.tbs-booking-cart input[name="checkin"]').val(checkin);
		$('.tbs-booking-cart input[name="checkout"]').val("");
		$('.tbs-booking-cart input[name="unit_rates"]').val("[]");
		tbs_shortcode_calendar.disableCartButton();
		$('.tbs-room-rt').html("");
	}
	tbs_shortcode_calendar.setCheckout = function(obj){
		obj.addClass('selected-checkout');
		checkout = obj.attr("date");
		checkout_time = parseInt(obj.attr("time"));
		var calendar = $('#calendar_container');

		if(calendar.hasClass('search-calendar')){
			window.location.href = calendar.attr('search-link') + '?checkin=' + checkin + '&checkout=' + checkout;
			return false;
		}else{
			$('.tbs-booking-cart input[name="checkout"]').val(checkout);
			console.log('ai');
			tbs_shortcode_calendar.checkCartButton();
			tbs_shortcode_calendar.getRateCategories(checkin, checkout);
			$('.cart-checkin').text(checkin);
			$('.cart-checkout').text(checkout);
		}
	}

	tbs_shortcode_calendar.checkCartButton = function(){
		var input_checkin = $('.tbs-booking-cart input[name="checkin"]').val(); 
		var input_checkout = $('.tbs-booking-cart input[name="checkout"]').val(); 
		var input_rates = $('.tbs-booking-cart input[name="unit_rates"]').val();

		if(input_checkin.trim() != '' && input_checkout.trim() != '' && input_rates.trim() != '[]'){
			tbs_shortcode_calendar.enableCartButton();
		}else{
			tbs_shortcode_calendar.disableCartButton();
		}

	};
	tbs_shortcode_calendar.disableCartButton = function(){$('.tbs-rate-select button').addClass('disabled')};;
	tbs_shortcode_calendar.enableCartButton = function(){$('.tbs-rate-select button').removeClass('disabled');};

}( window.tbs_shortcode_calendar = window.tbs_shortcode_calendar || {}, jQuery ));


(function( $ ) {
	'use strict';

	$(document).on("click",".month-navigation .prev, .month-navigation .next", function(){
		var obj = $(this);
		var year = obj.attr('year');
		var month = obj.attr('month');
		tbs_shortcode_calendar.getCalendar(year, month);
	});

	var date = new Date();
	var year = date.getFullYear();
	var month = date.getMonth() + 1;



	setTimeout(function(){
		if($('#calendar_container').length>0){
			var calendar = $('#calendar_container');
			checkin = calendar.attr('checkin');
			checkout = calendar.attr('checkout');
			var split_checkin = checkin.split('-');
			var month = parseInt(split_checkin[1])+1;
			var year = parseInt(split_checkin[2]);

			if(month > 12){
				month=1;
				year++;
			}
			tbs_shortcode_calendar.getCalendar(year, month);
		}
	},1000);

	$(document).on('mouseover',".week .available",function(){
		var obj = $(this);
		var time = parseInt(obj.attr("time"));

		var week = obj.closest(".week");

		if(!tbs_shortcode_calendar.hasCheckout()){
			$(".week .day-wrap.available").removeClass("day-between").each(function(){
				var obj2 = $(this);
				var t = parseInt(obj2.attr('time'));
				if(tbs_shortcode_calendar.hasCheckin() && t > checkin_time && t < time){ obj2.addClass("day-between"); }
			});
		}
		obj.addClass('highlight');

	}).on('mouseout',".week .available",function(){
		var obj = $(this);
		obj.removeClass('highlight');

		if(!tbs_shortcode_calendar.hasCheckout()) $(".week .day-wrap.available").removeClass("day-between");
	});

	$(document).on("click",'.week .available', function(){
		var obj = $(this);

		if(tbs_shortcode_calendar.hasCheckin() && !tbs_shortcode_calendar.hasCheckout()){
			var t = parseInt(obj.attr('time'));
			if(checkin_time > t){
				tbs_shortcode_calendar.setCheckin(obj);
			}else{
				tbs_shortcode_calendar.setCheckout(obj);
			}
		} else {
			tbs_shortcode_calendar.setCheckin(obj);
		}
	});

})( jQuery );
