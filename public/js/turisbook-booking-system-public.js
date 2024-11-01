var perfEntries = performance.getEntriesByType("navigation");
const { __, _x, _n, _nx } = wp.i18n;
if (perfEntries[0].type === "back_forward") {
	location.reload(true);
}

var checkin = "";
var checkin_time = 0;
var checkout = "";

(function( tbs_shortcode_calendar, $, undefined ) {
	tbs_shortcode_calendar.hasCheckin = function(){return (checkin+"").trim() != "";}
	tbs_shortcode_calendar.hasCheckout = function(){return (checkout+"").trim() != "";}

	tbs_shortcode_calendar.initCalendar = function(){
		var calendar = $('#calendar_container');
		if(calendar.length==0) return false;
		if(!calendar.hasClass('search-calendar') && (calendar.attr('init') === undefined)) return false;
		if(calendar.hasClass('search-calendar') || (!calendar.hasClass('search-calendar') && parseInt(calendar.attr('init')) == 1)){
			checkin = calendar.attr('checkin');
			checkout = calendar.attr('checkout');

			var split_checkin = checkin.split('-');
			var month = parseInt(split_checkin[1])+1;
			var year = parseInt(split_checkin[2]);

			if(month > 12){
				month=1;
				year++;
			}
		}else{
			var date = new Date();
			var year = date.getFullYear();
			var month = date.getMonth() + 1;
		}

		tbs_shortcode_calendar.getCalendar(year, month, function(){
			var calendar = $('#calendar_container');
			if(!calendar.hasClass('search-calendar') &&  calendar.attr('init') == 1){
				var checkin_object = $(".day-wrap[date='"+checkin+"']");
				tbs_shortcode_calendar.setCheckin(checkin_object);
				checkout = calendar.attr('checkout');
				var checkout_object = $(".day-wrap[date='"+checkout+"']");
				tbs_shortcode_calendar.setCheckout(checkout_object);

				if(!tbs_shortcode_calendar.checkUnavailableBetween(checkout_object)){
					tbs_shortcode_calendar.unsetCheckinCheckout();
				}
			}

		});
	}

	tbs_shortcode_calendar.getCalendar = function(year, month, callback){

		if(callback === undefined) callback = function(){};

		tbs_common.showLoader();
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
			tbs_common.hideLoader();
			callback();
		});
	}

	tbs_shortcode_calendar.getRateCategories = function(checkin, checkout){
		tbs_common.showLoader();
		var unit_id = $('.unit_id').val();
		$.post(TBS.url, {action: "Turisbook_bs_getRatecategories", _ajax_nonce: TBS.nonce, checkin: checkin, checkout: checkout, unit_id:unit_id}, function(data) {
			var result = jQuery.parseJSON(data);
			if(result.status){
				$(".tbs-room-rt").html(result.html);
				tbs_book_cart.initRateCategories();
			}

			var w = window.innerWidth;
			var popover_max_width = 500;

			var popover_side = w <= 500 ? 'left' : 'top';

			$('.pop-me-hover').each(function(){
				var obj = $(this);
				var attr = $(this).attr('max_width');
				var max_width = (typeof attr !== typeof undefined && attr !== false) ? parseInt(obj.attr('max_width')) : 500;
				var popover_width = w < max_width ? 150 : max_width;

				var description = obj.attr('description');
				var name = obj.attr('name');
				var i = '<i class="fa fa-bookmark"></i> ';
				obj.fu_popover({placement:popover_side, themeName:'Theme_turisbook_green', dismissable:true,  width: popover_width+'px', content:description});
			});
			
			tbs_common.hideLoader();
		});
	}

	tbs_shortcode_calendar.unsetCheckinCheckout = function(){
		$('.selected-checkin').removeClass("selected-checkin");
		$('.selected-checkout').removeClass("selected-checkout");
		$('.day-between').removeClass("day-between");
		checkin = "";
		checkin_time = 0;
		checkout = "";
		checkout_time = 0;
	}
	tbs_shortcode_calendar.setCheckin = function(obj){
		tbs_shortcode_calendar.unsetCheckinCheckout();
		obj.addClass('selected-checkin');
		checkin = obj.attr("date");
		checkin_time = parseInt(obj.attr("time"));
		$('.tbs-booking-cart input[name="checkin"]').val(checkin);
		$('.tbs-booking-cart input[name="checkout"]').val("");
		$('.tbs-booking-cart input[name="unit_rates"]').val("[]");
		tbs_shortcode_calendar.disableCartButton();
		$('.tbs-room-rt').html("");
		$('.cart-info').hide();
	}

	tbs_shortcode_calendar.setCheckout = function(obj){
		obj.addClass('selected-checkout');
		checkout = obj.attr("date");
		checkout_time = parseInt(obj.attr("time"));
		if(tbs_shortcode_calendar.checkUnavailableBetween(obj)){
			var calendar = $('#calendar_container');

			if(checkin_time == checkout_time){
				return false;
			}

			if(calendar.hasClass('search-calendar')){
				window.location.href = calendar.attr('search-link') + '?checkin=' + checkin + '&checkout=' + checkout;
				return false;
			}else{
				$('.tbs-booking-cart input[name="checkout"]').val(checkout);
				tbs_shortcode_calendar.checkCartButton();
				tbs_shortcode_calendar.getRateCategories(checkin, checkout);

				const oneDay = 24 * 60 * 60 * 1000; // Number of milliseconds in a day
				 // Parse the date strings in "dd-mm-yyyy" format
				const startDateParts = checkin.split("-");
				const endDateParts = checkout.split("-");

  				// Create Date objects from the parsed date parts
				const startDate = new Date(
					startDateParts[2],
					startDateParts[1] - 1,
					startDateParts[0]
					);

				const endDate = new Date(
					endDateParts[2],
					endDateParts[1] - 1,
					endDateParts[0]
					);

				const diffDays = Math.round(Math.abs((startDate - endDate) / oneDay));


				$('.cart-checkin').text(checkin);
				$('.cart-checkout').text(checkout);
				$('.cart-nights').text(diffDays);
				$('.cart-info').show();
				tbs_book_cart.hideError();

			}	
		}
	}

	tbs_shortcode_calendar.checkCartButton = function(){
		var input_checkin = $('.tbs-booking-cart input[name="checkin"]').val(); 
		var input_checkout = $('.tbs-booking-cart input[name="checkout"]').val(); 
		// var input_rates = $('.tbs-booking-cart input[name="unit_rates"]').val();
		var input_rates_total = 0;

		$('.ratecategory_select .rate-select').each(function(){
			var obj = $(this);
			input_rates_total += parseInt(obj.val());
		});

		if(input_checkin.trim() != '' && input_checkout.trim() != '' && input_rates_total > 0){
			tbs_shortcode_calendar.enableCartButton();
		}else{
			tbs_shortcode_calendar.disableCartButton();
		}

	};
	tbs_shortcode_calendar.disableCartButton = function(){$('.tbs-rate-select button').addClass('disabled')};;
	tbs_shortcode_calendar.enableCartButton = function(){$('.tbs-rate-select button').removeClass('disabled');};

	tbs_shortcode_calendar.checkUnavailableBetween = function(day, withcheckout){
		withcheckout = withcheckout !== undefined ? withcheckout : false; 
		var time = parseInt(day.attr("time"));

		var check = true;

		if(tbs_shortcode_calendar.hasCheckin() && (!tbs_shortcode_calendar.hasCheckout() || withcheckout)){
			$(".week .day-wrap:not(.past)").removeClass("day-between").each(function(){
				if(check){
					var obj2 = $(this);
					var t = parseInt(obj2.attr('time'));
					if(t > checkin_time && t < time){
						if(obj2.hasClass('available')){
							if(t > checkin_time && t < time) obj2.addClass("day-between");
						} else if(obj2.hasClass('unavailable')) check=false;
					}
				}
			});
		}
		return check;
	}


}( window.tbs_shortcode_calendar = window.tbs_shortcode_calendar || {}, jQuery ));

(function( tbs_booking_form, $, undefined ) {

	tbs_booking_form.validateEmail = function(email){
		if (email === undefined || email === null) return false;
		const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email.val());
	}

	tbs_booking_form.cardNumberIsValid = function(card_type){
		var card_types = {'visa':1,'mastercard':2};
		for (var key in card_types) {
			$('.tbs-payment-cc').removeClass('tbs-payment-' + key);
		}
		$('.tbs-payment-cc').attr('tbs-payment',card_types[card_type]).addClass('tbs-payment-'+card_type);

	}

	tbs_booking_form.cardNumberIsInvalid = function(card_type){
		var card_types = {'visa':1,'mastercard':2};
		for (var key in card_types) {
			$('.tbs-payment-cc').removeClass('tbs-payment-' + key);
		}
		$('.tbs-payment-cc').attr('tbs-payment',0);

	}



	tbs_booking_form.invalidInput = function(input){
		let group = input.closest('.tbs-form-field-group');
		group.addClass('tbs-form-field-group-error');
	}
	tbs_booking_form.validInput = function(input){
		let group = input.closest('.tbs-form-field-group');
		group.removeClass('tbs-form-field-group-error');
	}

	tbs_booking_form.validateCard = function(number){
		var status = false;

		var re = {
			visa: /^4[0-9]{12}(?:[0-9]{3})?$/, // 4111111111111111
			mastercard: /^5[1-5][0-9]{14}$/, // 5555555555554444
		};

		if (re.visa.test(number)) {
			tbs_booking_form.cardNumberIsValid('visa');
			status = true;
		} else if (re.mastercard.test(number)) {
			tbs_booking_form.cardNumberIsValid('mastercard');
			status = true;
		}else{
			tbs_booking_form.cardNumberIsInvalid();
		}
		return status;
	}

	tbs_booking_form.validateInput = function(input){
		var status = true;
		if (input === undefined || input === null) return false;
		var input_type = input.is('input') ? input.attr('type') : '';
			//email
		if(input_type=='email' && input.val().trim() != "") status = status && tbs_booking_form.validateEmail(input.val());

		if(input_type=='text' && input.attr('tbs-validate-card')){
			var result = tbs_booking_form.validateCard(input.val());
			status = status && result;
		}

			// Required
		if(input.attr('tbs-required')){
			if(input.is('input')){
				switch(input_type){
				case 'text':
					if(input.hasClass('cc_validation')){
						var p = input.closest('.tbs-payment');
						if(p.hasClass('selected') && parseInt(p.attr('tbs-payment')) == 0) status = status && input.val().trim() != "";
					}else{
						status = status && input.val().trim() != "";
					}
					break;
				case 'email':
					status = status && input.val().trim() != "";
				case 'checkbox':
					status = status && input.is(':checked');
					break;
				default:
					status = status && false;
				}
			}else if(input.is('textarea')){
				status = status && input.val().trim() != "";
			}else if(input.is('select')){
				status = status && input.val() != 0;
			}else{
				status = status && false
			}
		}

		if(!status) tbs_booking_form.invalidInput(input);
		else tbs_booking_form.validInput(input);

		return status;
	}

	tbs_booking_form.validateForm = function(){
		var status = true;

		$('#tbs-booking-form *').filter(':input[tbs-required="true"]').each(function(){
			let input = $(this);
			let result = tbs_booking_form.validateInput(input);
			status = status && result;
		});

		return status;
	}

	tbs_booking_form.validateCardexForm = function(){
		var status = true;

		$('#tbs-cardex-form *').filter(':input[tbs-required="true"]').each(function(){
			let input = $(this);
			let result = tbs_booking_form.validateInput(input);
			status = status && result;
		});

		return status;
	}

	tbs_booking_form.checkGroupInputs = function(group){

		var isFilled = false;

		group.find('input, textarea').each(function(){
			var input = $(this);

			if(input.attr('type') != 'checkbox'){

				if(input.val().trim().length > 0){
					isFilled = true;
				}else{
					input.val('');
				}
			}
		});

		if(group.find('select').length > 0)
			isFilled = true;

		if(isFilled){
			group.addClass('filled');
		}else{
			group.removeClass('filled');
		}
	}

	tbs_booking_form.checkGroups = function(){
		$('.tbs-form-field-group').each(function(){
			var group = $(this);
			tbs_booking_form.checkGroupInputs(group);
		});
	}


	tbs_booking_form.book = function(){
		tbs_common.showLoader();
		var form = $('#tbs-booking-form');
		var payment = $('.tbs-payment.selected').attr('tbs-payment');
		var data = form.serialize() + "&action=Turisbook_bs_book&_ajax_none=" + TBS.nonce + "&payment="+payment;
		let valor = $('.tbs-booking-total .price').text().replace(',','');



		if(tbs_booking_form.validateForm()){
			$.post(TBS.url, data, function(data, textStatus, xhr) {
				if(TBS.analytics_booking_goal_enabled==1){
					gtag('event', 'submit', { event_category: 'turisbook'});
				}					
				// tbs_common.hideLoader();
				window.location.href = form.attr('action');
			},'json');
		}else{
			tbs_common.hideLoader();
			console.log('Erro');
		}
		return false;
	};


	tbs_booking_form.sendCardex = function(){
		tbs_common.showLoader();
		var form = $('#tbs-cardex-form');
		var data = form.serialize() + "&action=Turisbook_bs_sendcardex&_ajax_none=" + TBS.nonce;

		if(tbs_booking_form.validateCardexForm()){
			$.post(TBS.url, data, function(data, textStatus, xhr) {
				window.location.href = window.location.href + '&thankyou';
			},'json');
		}else{
			tbs_common.hideLoader();
			console.log('Erro');
		}
		return false;
	};


	tbs_booking_form.applyCupon = function(){
		var cupon_input = $('.tbs-cupon');
		var cupon = cupon_input.val();

		if(cupon.trim() != ""){
			tbs_common.showLoader();
			$('.tbs-invalid-cupon').text("").addClass('hidden');
			$.post(TBS.url, {action: 'Turisbook_bs_apply_cupon', _ajax_none: TBS.nonse, cupon:cupon }, function(data, textStatus, xhr) {
				if(data.cupon_id > 0){
					$('.tbs-cupon').attr('disabled',true);
					$('.tbs-cupon-btn').attr('disabled',true);
					$('input[name="cupon"]').val(data.cupon_id);
					$('.tbs-booking-total .price').text(data.total.toFixed(2));
					$('.tbs-booking-total-price .price').text(data.total.toFixed(2));
					$('.tbs-booking-prepay-price .price').text(data.prepay.toFixed(2));
					$('.tbs-cupon-btn').addClass('hidden');
					$('.tbs-price-resume').html(data.html);
				}else{
					$('.tbs-invalid-cupon').text(data.msg).removeClass('hidden');
				}
				tbs_common.hideLoader();
			},'json');
		}
		return false;
	};


	tbs_booking_form.cancelCupon = function(){


		return false;
	};

	tbs_booking_form.updateLines = function(line){

		tbs_common.showLoader();
		var room_count = line.attr('room_count');
		var line_id = line.attr('line_id');
		var room_id = line.attr('room_id');

			// choose beds
		var choose_beds = line.find('.line_choose_beds:checked');
		var choose_bed_value = choose_beds.length > 0 ? choose_beds.val() : 1;

		var options = {
			row_count: room_count,
			choose_bed_type: choose_bed_value,
		};

		$.post(TBS.url, {action: 'Turisbook_bs_update_line', _ajax_none: TBS.nonse, line_id:line_id, room_id:room_id, options:JSON.stringify(options) }, function(data, textStatus, xhr) {
			$('.tbs-price-resume').html(data.html);
			tbs_common.hideLoader();
		},'json');

		return false;
	};



}( window.tbs_booking_form = window.tbs_booking_form || {}, jQuery ));


(function( tbs_unit_slider, $, undefined ) {
	tbs_unit_slider.init = function(){
		$('.tbs-slider').each(function(){
			var slider = $(this);
			var n = 1;
			var id = slider.attr('id');

			slider.find('.tbs-slide').each(function(){
				var obj = $(this);
				obj.attr('pos',n).addClass('tbs-slide-'+n);
				n++;
			});

		});
	}

	tbs_unit_slider.nextSlide = function(slider){
		tbs_unit_slider.showSlide(slider,1);
	}

	tbs_unit_slider.prevSlide = function(slider){
		tbs_unit_slider.showSlide(slider,-1);
	}

	tbs_unit_slider.showSlide = function(slider, n){
		var slides = slider.find('.tbs-slide');
		var count_slides = slides.length;
		if(count_slides > 1){
			var selected = slider.find('.tbs-slide.selected');
			var actual_pos = parseInt(selected.attr('pos'));
			var next_pos = actual_pos + n;

			if(next_pos <= 0) next_pos = count_slides;
			if(next_pos > count_slides ) next_pos = 1;

			selected.removeClass('selected');
			slider.find('.tbs-slide-' + next_pos).addClass('selected');
		}
	}

}( window.tbs_unit_slider = window.tbs_unit_slider || {}, jQuery ));

(function( tbs_book_cart, $, undefined ) {

	tbs_book_cart.hideError = function(){$('.turisbook-cart-error').removeClass('show-dates show-rooms');}
	tbs_book_cart.showError = function(etype){
		tbs_book_cart.hideError();
		$('.turisbook-cart-error').addClass('show-'+etype);
	}

	tbs_book_cart.addRoomRow = function(unit_id, unit_name){
		var resume = $('.tbs-booking-cart-table tbody');
		var tr = resume.find('tr[unit='+unit_id+']');
		if(tr.length==0){
			tr = $('<tr />').addClass('booking-row').attr({'unit':unit_id}).appendTo(resume);
			$('<td />').html('<b>'+unit_name+'</b>').appendTo(tr);
			var td_list = $('<td />');
			$('<ul />').addClass('tbs-cart-rates').appendTo(td_list);
			td_list.appendTo(tr);
		}
		return tr;
	}

	tbs_book_cart.addRateRow = function(tr, rate, row, adults, children){
		var ul = tr.find('ul.tbs-cart-rates');
		var rateId = rate.attr('rate_id');

		var quantity = parseInt(row.val());
		var li = ul.find('li[rate='+rateId+']');

		var rtype = quantity == 1 ? TBS.typeSingle : TBS.typePlural;

		var total = parseFloat(row.attr('price').replace(',','.'));

		rate.find('.tbs_accomodation_row:not(.accomodation_inactive)').each(function(){
			var accom = $(this);
			let adult_input = accom.find('.adult-select option:selected');
			let children_input = accom.find('.children-select option:selected');

			let adults_extra_price = parseFloat(adult_input.attr('total').replace(',','.'));
			let children_extra_price = parseFloat(children_input.attr('total').replace(',','.'));

			total += adults_extra_price + children_extra_price; 
			
		});

		total = total.toFixed(2);

		if(li.length==0){
			li = $('<li />')
			.attr('room',tr.attr('room'))
			.attr('rate',rateId)
			.html('<span class="small11">'+TBS.translations.quantity+'</span> <span class="quantity"></span> <br/> <span class="total">' + total + '</span> €')
			.appendTo(ul);
		}

		if(quantity > 0){
			li.find('.quantity').text(quantity);
			li.find('.total').text(total);
			// li.find('.rtype').text('Unidade(s)');

			li.attr('rate',rateId)
			.attr('total',total);

		} else li.remove();

		if(ul.find('li').length == 0) tr.remove();
	}

	tbs_book_cart.calcCartTotal = function(){
		var sum = 0;
		$('ul.tbs-cart-rates li').each(function(){
			sum += parseFloat($(this).attr('total'));
		});

		$('.tbs-booking-total').text(sum.toFixed(2));
	}

	tbs_book_cart.addToCart = function(rate, row){
		var rate_container = rate.closest('.tbs-rt-container');
		var adults = rate.find('.adult-select').val();
		var children = rate.find('.child-select').val();
		var unit_id = rate_container.attr('unit_id');
		var unit_name = rate_container.attr('unit_name');
		var tr = tbs_book_cart.addRoomRow(unit_id, unit_name);
		tbs_book_cart.addRateRow(tr, rate, row, adults, children);
		// tbs_book_cart.calcCartTotal();
	}

	tbs_book_cart.generateRateSelect = function(args){

		var vars = $.extend({
			select:false,
			selected:0,
			start :0,
			end :0,
			extra :0,
			extra_price :0
		}, args );

		if(!vars.select) return false;

		for(var i=vars.start; i <= vars.end;i++){
			var op = $('<option />').attr({'extra':0,'total':0,'nop':i}).val(i).text(i);
			if(vars.selected==i) op.prop('selected',1);
			op.appendTo(vars.select);
		}

		for(var i=1; i <= vars.extra;i++){
			var total = (i * vars.extra_price).toFixed(2);
			var nop = i+vars.end;
			var text = nop+" (+ "+total+" €)";
			op = $('<option />').attr({'extra':i,'total':total,'nop':nop}).val(vars.end).text(text)
			if(vars.selected==nop) op.prop('selected',1);
			op.appendTo(vars.select);
		}
	}

	tbs_book_cart.initRateCategories = function(){
		$('.tbs_accomodation_row').each(function(){

			var row = $(this);

			var rt = row.closest('.tbs-rt');
			var base = parseInt(rt.attr('base'));
			var extrabed_max = parseInt(rt.attr('extrabed_max'));

			var adult_select = row.find('.adult-select');
			var adults = parseInt(adult_select.attr('lotation'));
			var extrabed_adults = parseInt(adult_select.attr('extrabed'));
			var extrabed_adults_price = parseFloat(adult_select.attr('extrabed_price'));

			var end_adult = adults > base ? base : adults;
			var extra_adult = extrabed_adults > extrabed_max ? extrabed_max : extrabed_adults;

			var vars = {'select':adult_select, 'selected':1, 'start':1, 'end':end_adult, 'extra':extra_adult, 'extra_price':extrabed_adults_price};

			tbs_book_cart.generateRateSelect(vars);

			var children_select = row.find('.children-select');
			var children = parseInt(children_select.attr('lotation'));
			var extrabed_children = parseInt(children_select.attr('extrabed'));
			var extrabed_children_price = parseFloat(children_select.attr('extrabed_price'));

			var end_children = base - 1 < children ? base - 1 : children;
			var extra_children = extrabed_children > extrabed_max ? extrabed_max : extrabed_children; 

			vars = {'select':children_select, 'selected':0, 'start':0, 'end':end_children, 'extra':extra_children, 'extra_price':extrabed_children_price};
			tbs_book_cart.generateRateSelect(vars);
		});

		var a = $('.tbs-room-rt');

		if(a.length>0){
			$('html,body').animate({scrollTop: a.offset().top - 150},'slow');
		}

	}


	tbs_book_cart.checkMaxLotation = function(input){
		var row = input.closest('.tbs_accomodation_row');
		var rt = row.closest('.tbs-rt');
		var base = parseInt(rt.attr('base'));
		var extrabed_max = parseInt(rt.attr('extrabed_max'));


		var target = input.hasClass('children-select') ? 'adult-select' : 'children-select';
		var start = input.hasClass('children-select') ? 1 : 0;
		var select_target = row.find('.' + target);

		var clicked_option = input.find('option:selected');
		var clicked_val = parseInt(clicked_option.val());
		var clicked_extra =  parseInt(clicked_option.attr('extra'));
		var clicked_extra_total =  parseInt(input.attr('extrabed'));

		var target_option = select_target.find('option:selected');
		var target_val = parseInt(target_option.val());
		var target_lotation = parseInt(target_option.attr('lotation'));
		var target_extra = parseInt(target_option.attr('extra'));
		var target_extra_total =  parseInt(select_target.attr('extrabed'));
		var target_extra_price =  parseFloat(select_target.attr('extrabed_price'));

		var selected = parseInt(target_option.attr('nop'));

		var left = base - clicked_val - target_val;
		var end = target_val + left;
		end = end > target_lotation ? target_lotation : end;


		var extra_left = extrabed_max - clicked_extra;
		extra_left =  target_extra_total < extra_left ? target_extra_total : extra_left;
		// extra_left =  target_extra < extra_left ? target_extra : extra_left;

		select_target.html('');

		vars = {'select':select_target, 'selected':selected, 'start':start, 'end':end, 'extra':extra_left, 'extra_price':target_extra_price};

		tbs_book_cart.generateRateSelect(vars);



	}

	tbs_book_cart.verifyRates = function(){
		var rows = [];

		var line_id=0;

		var lines = [];
		var count_rt = 0;

		var cart_total = 0.00;

		$('.tbs-rt-container .tbs-rt').each(function(){
			count_rt++;
			var row = $(this);
			var rt_container = row.closest('.tbs-rt-container');
			var rate = row.attr('rate_id');
			var r_id = rt_container.attr('unit_id');

			var quantity_input = row.find('.rate-select option:selected');

			var quantity = parseInt(quantity_input.val());

			if(quantity>0){
				cart_total += parseFloat(quantity_input.attr('price').replace(',','.'));

				line_id++;
				var line = {
					line_id: line_id,
					rate: rate,
					quantity: quantity,
					adults: 0,
					children: 0,
					options:[]
				};
				row.find('.tbs_accomodation_row:not(.accomodation_inactive)').each(function(){
					var obj = $(this);
					let adult_input = obj.find('.adult-select option:selected');
					let children_input = obj.find('.children-select option:selected');

					let adults = parseInt(adult_input.val());
					let adults_extra = parseInt(adult_input.attr('extra'));
					let adults_extra_price = parseFloat(adult_input.attr('total').replace(',','.'));

					let children = parseInt(children_input.val());
					let children_extra = parseInt(children_input.attr('extra'));
					let children_extra_price = parseFloat(children_input.attr('total').replace(',','.'));


					cart_total += adults_extra_price + children_extra_price;

					line.adults += adults;
					line.children += children;

					line.options.push({
						line_id: line_id,
						row_count: obj.attr('accomodation_count'),
						adults: adults,
						children: children,
						extrabed_adult: adults_extra,
						extrabed_children: children_extra,
						choose_bed_type: 1
					});

					lines.push(line);
				});

				var checkRow = false; 
				rows.forEach(function(item, index, arr){
					if(item.room == r_id){
						checkRow = true;
						arr[index].lines.push(line);
					}
				});

				if(!checkRow){
					rows.push({
						room: r_id,
						lines: [line]
					});
				}
			}
		});

		if(count_rt==0) $('.tbs-booking-cart button').addClass('disabled');
		else $('.tbs-booking-cart button').removeClass('disabled');

		$('input[name="unit_rates"]').val(JSON.stringify(rows));

		$('.tbs-booking-total').text(cart_total.toFixed(2));

	}


	tbs_book_cart.accomodationRowCheck = function(room, count){
		count = parseInt(count < 1 ? 1 : count);

		room.find('.tbs_accomodation_row').addClass('accomodation_inactive').each(function(){
			let obj = $(this);
			let val = parseInt(obj.attr('accomodation_count'));
			if(val<=count) obj.removeClass('accomodation_inactive');
		});

	}


	tbs_book_cart.rateSelect = function(input){
		var container = input.closest('.tbs-rt-container'); 
		var availability = parseInt(container.attr('availability'));
		var rate = input.closest('.tbs-rt');
		var selects = container.find('.rate-select');
		selects.find('option').attr('disabled',false);
		var sum = 0;

		tbs_book_cart.accomodationRowCheck(container,input.val());
		
		selects.each(function(){
			sum += parseInt($(this).find('option:selected').val());
		});
		var left_ava = availability - sum;
		var value = parseInt(input.val());
		input.removeClass("selected");
		if(value>0) input.addClass("selected");

		var row = input.find('option:selected');

		tbs_book_cart.addToCart(rate, row);


		selects.each(function(){
			var obj = $(this);
			var val = parseInt(obj.val());
			var left_options = val + left_ava;

			obj.find('option').filter(function () { return parseInt(this.value,10) > left_options; }).attr('disabled',true);
		});
		tbs_book_cart.verifyRates();
		tbs_shortcode_calendar.checkCartButton();


	}
	tbs_book_cart.prebooknow = function(){
		tbs_common.showLoader();
		var form = $('.tbs-rate-select')

		var data = form.serialize();
		data += "&action=Turisbook_bs_preBook";
		data += "&_ajax_nonce=" + TBS.nonce;

		$.post(TBS.url, data, function(data, textStatus, xhr) {
		// tbs_common.hideLoader();
			window.location.href=form.attr('action');
		});
	}


}( window.tbs_book_cart = window.tbs_book_cart || {}, jQuery ));


(function( tbs_common, $, undefined ) {
	tbs_common.sticky_height = 0;
	tbs_common.sticky_width = 0;

	tbs_common.stickyRelocate = function(){
		var window_top = $(window).scrollTop();
		var anchor = $('.tbs-sticky-anchor');
		var target = anchor.attr('target');

		if(target === undefined) return;

		var overview = $(target);
		var offset = overview.attr('offset');

		if(offset === undefined) offset = 0;

		var offset_float = parseFloat(offset);
		var offset_abs = Math.abs(offset);

		var div_top = anchor.offset().top + offset_float;



		if (window_top > div_top) {
			overview.addClass('stick');
			if(offset != 0) overview.css('top', offset_abs + 'px');
			anchor.height(tbs_common.sticky_height);
			overview.width(tbs_common.sticky_width);
		} else {
			overview.removeClass('stick');
			anchor.height(0);
		}
	}

	tbs_common.initLoader = function(){$('body').append('<div id="loadingDiv"><div class="loader">Loading...</div></div>');}
	tbs_common.showLoader = function(){$('#loadingDiv').stop().fadeIn(150);}
	tbs_common.hideLoader = function(){$('#loadingDiv').stop().fadeOut(150);}

}( window.tbs_common = window.tbs_common || {}, jQuery ));


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

	$(document).on('focusin','.tbs-form-field-group input, .tbs-form-field-group textarea', function(){
		var obj = $(this);
		var group = obj.closest('.tbs-form-field-group'); 
		group.addClass('focused');
	})
	.on('focusout','.tbs-form-field-group input, .tbs-form-field-group textarea', function(){
		var obj = $(this);
		var group = obj.closest('.tbs-form-field-group');

		tbs_booking_form.checkGroupInputs(group);

		group.removeClass('focused');
	});

	setTimeout(function(){
		tbs_common.initLoader();
		tbs_booking_form.checkGroups();
		$('.tbs-payment.selected .tbs-payment-body').show();
		$('.tbs-payment.selected').find('input').attr('tbs-required',true);

		tbs_unit_slider.init();

		var w = window.innerWidth;
		var popover_max_width = 500;

		var popover_side = w <= 500 ? 'left' : 'top';

		$('.pop-me-hover').each(function(){
			var obj = $(this);
			var attr = $(this).attr('max_width');
			var max_width = (typeof attr !== typeof undefined && attr !== false) ? parseInt(obj.attr('max_width')) : 500;
			var popover_width = w < max_width ? 150 : max_width;

			var description = obj.attr('description');
			var name = obj.attr('name');
			var i = '<i class="fa fa-bookmark"></i> ';
			obj.fu_popover({placement:popover_side, themeName:'Theme_turisbook_green', dismissable:true,  width: popover_width+'px', content:description});
		});

		$('.prebook_line .line_extrabed_adult').each(function(){
			var obj = $(this);
			tbs_booking_form.updateExtrabedSelects(obj);
		});
		tbs_shortcode_calendar.initCalendar();

		$('.show_partial_description').each(function(){
			var obj = $(this);
			$('<div />').addClass('readall').html('<i class="fas fa-chevron-circle-down"></i>').appendTo(obj);
			$('<div />').addClass('readall-background').appendTo(obj);
		});

		$(document).on('click','.show_partial_description .readall', function(){
			var obj = $(this);
			var parent = obj.closest('.show_partial_description').toggleClass('show');
		})

		var tbs_rate_selecct = $('.tbs-rate-select');
		var cal = $('#calendar_container');
		if(tbs_rate_selecct.length > 0 && cal.length==0){
			checkin = tbs_rate_selecct.find('input[name="checkin"]').val();
			checkout = tbs_rate_selecct.find('input[name="checkout"]').val();
		}

	}, 250);

	setTimeout(function(){
		if($('.tbs-rt').length > 0){
			tbs_book_cart.initRateCategories();
		}
	}, 1000);



	$(document).on('mouseover',".week .available",function(){
		var obj = $(this);

		var check = tbs_shortcode_calendar.checkUnavailableBetween(obj);
		if(check) obj.addClass('highlight');

	}).on('mouseout',".week .available",function(){
		var obj = $(this);
		obj.removeClass('highlight');

		if(!tbs_shortcode_calendar.hasCheckout()) $(".week .day-wrap.available").removeClass("day-between");
	});

	$(document).on("click",'.week .available, .week .unavailable', function(){
		var obj = $(this);
		var is_unavailable = obj.hasClass('unavailable');

		if(tbs_shortcode_calendar.hasCheckin() && !tbs_shortcode_calendar.hasCheckout()){
			var t = parseInt(obj.attr('time'));
			if(checkin_time == t){
				tbs_shortcode_calendar.unsetCheckinCheckout();
				return false;
			}else if(!is_unavailable && checkin_time > t){
				tbs_shortcode_calendar.setCheckin(obj);
			}else if(tbs_shortcode_calendar.checkUnavailableBetween(obj)){
				tbs_shortcode_calendar.setCheckout(obj);
			}
		} else if(!is_unavailable){
			tbs_shortcode_calendar.setCheckin(obj);
		}
	});

	$(document).on('click', '.tbs-payment-head', function(){
		let obj = $(this);
		let parent = obj.closest('.tbs-payment');
		if(!parent.hasClass('selected')){
			let container = obj.closest('.tbs-form-payments');

			container.find('input').removeAttr('tbs-required');
			obj.find('input').attr('tbs-required',true);


			container.find('.selected').removeClass('selected').find('.tbs-payment-body').stop().slideUp('250');
			parent.addClass('selected').find('.tbs-payment-body').stop().slideDown('250');

		}
	});

	$(document).on('click','.tbs-slider-right',function(){
		var obj = $(this);
		var slider = obj.closest('.tbs-slider');
		tbs_unit_slider.nextSlide(slider); 
	});
	$(document).on('click','.tbs-slider-left',function(){
		var obj = $(this);
		var slider = obj.closest('.tbs-slider');
		tbs_unit_slider.prevSlide(slider); 
	});

	$(document).on('submit','#tbs-booking-form', function(){
		tbs_booking_form.book();
		return false;
	});


	$(document).on('change','.rate-select', function(){
		var obj = $(this);
		tbs_book_cart.hideError();
		tbs_book_cart.rateSelect(obj);

	});

	$(document).on('change','.adult-select, .children-select', function(){
		var obj = $(this);
		var rate_select = obj.closest('.tbs-rt').find('.rate-select');
		tbs_book_cart.hideError();
		tbs_book_cart.checkMaxLotation(obj);
		tbs_book_cart.rateSelect(rate_select);
	});

	$(document).on('submit','.tbs-rate-select',function(){
		var form = $(this);
		var button = form.find('.tbs-cart-btn button');
		var ava = form.attr("availabilities");
		if(!button.hasClass('disabled'))
			tbs_book_cart.prebooknow();
		else{
			var etype = tbs_shortcode_calendar.hasCheckin() ? "rooms" : "dates";
			var a = $('#' + ava);
			var rt = $('.tbs-room-rt');
			if(rt.length > 0 && etype == 'rooms'){
				$('html,body').animate({scrollTop: rt.offset().top - 150},'slow');
			}else if(a.length>0){
				$('html,body').animate({scrollTop: a.offset().top - 150},'slow');
			}
			tbs_book_cart.showError(etype);
		}
		return false;
	})

	$(document).on('click','.tbs-cart-toggle',function(){
		var obj = $(this);
		var container = obj.closest('.tbs-booking-cart');
		var i = obj.find('i');
		var body = container.find('.tbs-cart-body');
		if(container.hasClass('open')){
			body.stop().slideUp(250, function(){
				container.removeClass('open');
			});
		}else{
			body.stop().slideDown(250,function(){
				container.addClass('open');
			});
		}
	});

	$(document).on('click','.tbs-resume-toggle',function(){
		var obj = $(this);
		var container = obj.closest('.tbs-booking-resume');
		var i = obj.find('i');
		var body = container.find('.tbs-booking-resume-body');
		if(container.hasClass('open')){
			body.stop().slideUp(250, function(){
				container.removeClass('open');
			});
		}else{
			body.stop().slideDown(250,function(){
				container.addClass('open');
			});
		}
	});

	$(document).on('keyup, focusout','#tbs-booking-form input', function(){
		var obj = $(this);
		tbs_booking_form.validateInput(obj);
	});

	$(document).on('click','.tbs-cupon-btn', function(){
		tbs_booking_form.applyCupon();
		return false;
	});

	$(document).on('click','.tbs-cupon-btn-cancel', function(){
		tbs_booking_form.applyCupon();
		return false;
	});

	$(document).on('keyup','input.onMaxFocusTo', function(){
		var obj = $(this);
		var max = obj.attr('maxlength');
		var nextFocus = obj.attr('nextfocus');

		if(max !== undefined && nextFocus !== undefined && obj.val().length == max){
			$(nextFocus).focus();
		}else{
		}
	});

	$(document).on('focus','input.checkPreFocus', function(){
		var obj = $(this);
		var prevFocus = obj.attr('prevfocus');

		if(prevFocus !== undefined){
			var prevFocus_obj = $(prevFocus);
			var max = prevFocus_obj.attr('maxlength');

			if(max !== undefined && prevFocus_obj.val().length < max){
				$(prevFocus).focus();
			}
		}else{
		}
	});

	$(document).on('change','.prebook_line .line_choose_beds, .prebook_line select', function(){
		var obj = $(this);
		var line = obj.closest('.prebook_line');
		tbs_booking_form.updateLines(line);
	});


	var bottomOffset = 1500, canBeLoaded = true, page=1;



	$(window).scroll(function(){
		var tbc = $('.turisbook_rooms_container');
		if(tbc.length == 1){
			if(tbc.length == 1 && $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded==true){
				canBeLoaded = false;
				page++;
				$.post(TBS.url, {action: "Turisbook_bs_Load_More",_ajax_nonce: TBS.nonce, page}, function(data, textStatus, xhr) {
					var result = jQuery.parseJSON(data);
					tbc.append(result.html);
					if(result.posts_count > 0) canBeLoaded=true;
				});
			}
		}
	});


	$(document).on('change','#tbs-cardex-form .tbs-form-input-country-residence', function(){
		var select = $(this);
		let value = select.val();
		var nif = $('#tbs-cardex-form .tbs-form-input-nif');
		var label = nif.closest('.tbs-form-field-group').find('label');

		label.find('span').remove();
		nif.attr('tbs-required'); 

		if(value == 174){
			label.append('<span class="text-red"> *</span>');
			nif.attr('tbs-required',true); 
		}

	});
	$(document).on('submit','#tbs-cardex-form', function(){
		tbs_booking_form.sendCardex();
		return false;
	});
	$(document).on('keyup, focusout','#tbs-cardex-form input, #tbs-cardex-form select', function(){
		var obj = $(this);
		tbs_booking_form.validateInput(obj);
	});



})( jQuery );
