(function( turisbookbs_admin, $, undefined ) {


	// turisbookbs_admin.showLoader = function(){$(".turisbook_loader").fadeIn(250);};
	// turisbookbs_admin.hideLoader = function(){$(".turisbook_loader").fadeOut(250);};


	turisbookbs_admin.ChangeAccountBlock = function(data){
		$('#Turisbook_bs_account').html(data);
	}


	turisbookbs_admin.login = function(){
		turisbookbs_admin.showLoader();

		var form = $('#form-turisbook-login');

		var token = form.find('.tb-api-token').val();
		$.post(TBS.url, {action: 'Turisbook_bs_login', _ajax_nonce:TBS.nonce , token:token}, function(result, textStatus, xhr) {
			if (result.status){
				turisbookbs_admin.ChangeAccountBlock(result.data);
			}
			turisbookbs_admin.hideLoader();
		},'json').fail(function(){
			turisbookbs_admin.hideLoader();
		});
	}

	turisbookbs_admin.lockEstablishment = function(){
		turisbookbs_admin.showLoader();

		var form = $('#turisbook-choose-establishment');
		var select = form.find('.establishment');

		var selected = select.find('option:selected');
		var name = selected.text();

		var id = selected.val();
		var unique_id = selected.attr('unique_id');
		var type = selected.attr('establishment_type');

		$.post(TBS.url, {action: 'Turisbook_bs_lockEstablishment', _ajax_nonce: TBS.nonce, id:id, unique_id: unique_id, name:name, type:type}, function(result, textStatus, xhr) {
			if (result.status){
				turisbookbs_admin.ChangeAccountBlock(result.data);
				turisbookbs_admin.hideLoader();
			}
		},'json').fail(function(){
			turisbookbs_admin.hideLoader();
		});


	}
	turisbookbs_admin.logout = function(){
		turisbookbs_admin.showLoader();
		$.post(TBS.url, {action: 'Turisbook_bs_logout', _ajax_nonce: TBS.nonce}, function(result, textStatus, xhr) {
			if (result.status){
				turisbookbs_admin.ChangeAccountBlock(result.data);
				turisbookbs_admin.hideLoader();
			}
		},'json').fail(function(){
			turisbookbs_admin.hideLoader();
		});
	}

	turisbookbs_admin.syncData = function(){
		turisbookbs_admin.showLoader();
		$.post(TBS.url, {action: 'Turisbook_bs_syncdata', _ajax_nonce: TBS.nonce}, function(result, textStatus, xhr) {
			if (result.status){
				turisbookbs_admin.hideLoader();
			}
		},'json').fail(function(){
			turisbookbs_admin.hideLoader();
		});
	}

	turisbookbs_admin.saveSettings = function(){
		turisbookbs_admin.showLoader();

		var form = $('#turisbook-booking-system-config-form');
		var data = form.serialize() + '&action=Turisbook_bs_savesettings&_ajax_nonce='+TBS.nonce;

		$.post(TBS.url, data, function(result, textStatus, xhr) {
			if (result.status){
				turisbookbs_admin.hideLoader();
			}
		},'json').fail(function(){
			turisbookbs_admin.hideLoader();
		});
	}
	turisbookbs_admin.initLoader = function(){$('body').prepend('<div id="loadingDiv"><div class="loader">Loading...</div></div>');}
	turisbookbs_admin.showLoader = function(){$('#loadingDiv').stop().fadeIn(150);}
	turisbookbs_admin.hideLoader = function(){$('#loadingDiv').stop().fadeOut(150);}
	
}( window.turisbookbs_admin = window.turisbookbs_admin || {}, jQuery ));

(function( $ ) {
	'use strict';
	setTimeout(function(){
		turisbookbs_admin.initLoader();
	},250);

	$(document).on('click','.wptbtabs a.nav-tab:not(.nav-tab-active)', function(e){
		var obj = $(this);
		obj.addClass("nav-tab-active").siblings().removeClass("nav-tab-active");
		var href = obj.attr("href");
		$(href).addClass("active").siblings().removeClass('active');
		return false;
	});
	
	$(document).on('submit','#form-turisbook-login', function(){
		turisbookbs_admin.login();
		return false;
	});


	$(document).on('submit','#turisbook-choose-establishment', function(){
		turisbookbs_admin.lockEstablishment();
		return false;
	});

	$(document).on('click','.logout-form-submit',function(){
		turisbookbs_admin.logout();
		return false;
	});

	$(document).on('click','#wp-admin-bar-turisbook-sync-data-top a,.turisbook-sync-data',function(){
		turisbookbs_admin.syncData();
		return false;
	});
	$(document).on('submit','#turisbook-booking-system-config-form',function(){
		turisbookbs_admin.saveSettings();
		return false;
	});

	$(document).on('change','.tbs-color-picker', function(){
		var obj = $(this);
		var colorText = $('.tbs-color-text');
		colorText.val(obj.val());

	});

	$(document).on('focusout','.tbs-color-text', function(){
		var obj = $(this);
		var color = obj.val();
		var reg=/^#([0-9a-f]{3}){1,2}$/i;
		var colorPicker = $('.tbs-color-picker');

		if(!reg.test(color)){
			obj.val(colorPicker.val());
		}else{
			colorPicker.val(obj.val());

		}


	});

})( jQuery );
