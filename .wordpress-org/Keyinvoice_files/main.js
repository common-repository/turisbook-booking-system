$( document ).ready(function() {
    function toggleClassMenu() {
      myMenu.classList.add("menu--animatable"); 
      if(!myMenu.classList.contains("menu--visible")) {   
        myMenu.classList.add("menu--visible");
      } else {
        myMenu.classList.remove('menu--visible');   
      } 
    }
    function toggleClassMenuR() {
      myMenuR.classList.add("menu--animatable"); 
      if(!myMenuR.classList.contains("menu--visible")) {   
        myMenuR.classList.add("menu--visible");
      } else {
        myMenuR.classList.remove('menu--visible');   
      } 
    }
    function OnTransitionEnd() {
      myMenu.classList.remove("menu--animatable");
    }

    var myMenu = document.querySelector(".menu");
    var oppMenu = document.querySelector(".menu-icon");

    var myMenuR = document.querySelector(".menu--right");
    var oppMenuR = document.querySelector(".menu-icon-r");

    if(myMenu) 
    {
      myMenu.addEventListener("transitionend", OnTransitionEnd, false);
      myMenu.addEventListener("click", toggleClassMenu, false);
      oppMenu.addEventListener("click", toggleClassMenu, false);
    } 
     
     if(myMenuR) 
     {
    myMenuR.addEventListener("transitionend", OnTransitionEnd, false);
    oppMenuR.addEventListener("click", toggleClassMenuR, false);
    myMenuR.addEventListener("click", toggleClassMenuR, false);

    }
    $(document).on('click','#selectskin',function(e)
    {
      $('.menu.menu--right').addClass('menu--visible');
    });

    $(".leftside-navigation .sub-menu > a").click(function(e) {
      $(".leftside-navigation ul ul").slideUp(), $(this).next().is(":visible") || $(this).next().slideDown(),
      e.stopPropagation()
    })

    $(".leftside-navigation .sub-menu > ul > li > a").click(function(e) {
      $(".leftside-navigation ul ul ul").slideUp(), $(this).next().is(":visible") || $(this).next().slideDown(),
      e.stopPropagation()
    })

    $(document).on('focus', '.control-group .controls input'  ,function () {
      $(this).parents('.control-group').find('label').addClass('focus');
    });
    $(document).on('blur', '.control-group .controls input'  ,function () {
      $(this).parents('.control-group').find('label').removeClass('focus');
    });
    $('.menu-button').on('click', function(e) {
      e.preventDefault();
      $('.offcanvas-pane').toggleClass('is-open');
      $(this).toggleClass('active');
    });

    $('.menu-user').on('click', function(e){
      e.preventDefault();
      $('.user').toggleClass('is-open');
      $(this).toggleClass('active');
    });

    $('.btn-trigger').on('click', function(e){
      $('.trigger').toggleClass('is-close');
      $('.page-header').toggleClass('is-hide');
      $('.edit-shortcuts').toggleClass('is-hide');
    });

    $('.oe_menu > li').hover(
          function() {
            $(this).children('div').stop(true, false, true).fadeIn(200);
          }, function() {
            $(this).children('div').stop(true, false, true).fadeOut(200);
          }
    );
    $(".accordion-toggle").on('click', function(){
      $(this).find('span').toggleClass('rotate');
      $(this).next().toggleClass('open');
      $(this).find('.ul--article').toggleClass('active');
      $(this).parent().toggleClass('focuz');
    });

	 $(document).on('click','.accordion-toggle-faf li:not(.col-actions.on)', function(){
	  var par=$(this).parents('.accordion-toggle-faf');
      $(par).next().toggleClass('open');
      $(par).find('ul').toggleClass('active');
    });
	
    // $(".search-toggle").on('click', function(){
    //   $('.search-content').stop(true, false, true).slideToggle(200);
    // });

    //$(".sidebar-toggle").on('click', function(){
      //$(".sidebar").toggleClass('is-visible');
    //});



    if($('.newTab').length > 0) {
      $('#content.container-fluid').removeClass('fix-sub-responsive');
      $('#content.container-fluid').addClass('fix-responsive');
    }

     if($('.active > .newSubTab').length > 0) {

      $('#content.container-fluid').removeClass('fix-responsive');
      $('#content.container-fluid').addClass('fix-sub-responsive');
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

    
     var item = ''+$(e.target).attr("href")+'';
     
     if($(item).find('.newSubTab').length > 0)
     {
       $('#content.container-fluid').removeClass('fix-responsive');
        $('#content.container-fluid').addClass('fix-sub-responsive');
          
     } 
    else{
      $('#content.container-fluid').removeClass('fix-sub-responsive');
        $('#content.container-fluid').addClass('fix-responsive');
         
    }
     // e.target // newly activated tab
     // e.relatedTarget // previous active tabref
    })

    /*$(".newTab ul > li").on('click', function(){
      if($('.tab-pane.active > .newSubTab').length > 0) {
        alert('1');
        $('#content.container-fluid').removeClass('fix-responsive');
        $('#content.container-fluid').addClass('fix-sub-responsive');
      }else {
        alert('2');
        $('#content.container-fluid').removeClass('fix-sub-responsive');
        $('#content.container-fluid').addClass('fix-responsive');
      }
    });*/
   
    
    

    $(".trigger--more").on('click', function(){
      $(".trigger--more").toggleClass('hide');
      $(".position").toggleClass('show');
    });

    

    $("#close-action--call").click( function(){
      $(".action--call").removeClass('open');
      $("#call-me-holder").html('');
      return false;
    } );

    $(".action--call").on('click', function(){
      $(this).addClass('open');
    });

    var owl = $('.owl-carousel');
    owl.owlCarousel({
      // loop: true,
      margin:10,
      // autoWidth:true,
      responsiveClass:true,
      responsive:{
          0:{
              stagePadding: 50,
              items:1
          },
          600:{
              stagePadding: 50,
              items:2
          },
          940:{
              items:3
          },
          1200:{
              items:4
          },
          1440:{
              items:5
          },
          1600:{
              items:6
          },
          1920:{
              items:7
          },
          2560:{
              items:10
          }
      }
    });
    owl.on('mousewheel', '.owl-stage', function (e) {
        if (e.deltaY>0) {
            owl.trigger('next.owl');
        } else {
            owl.trigger('prev.owl');
        }
        e.preventDefault();
    });
});
$(document).ready(function() {
  $(document).on(function(){
    $('.checkbox-ripple').rkmd_checkboxRipple();
    change_checkbox_color();
  });
});
(function($) {

  $.fn.rkmd_checkboxRipple = function() {
    var self, checkbox, ripple, size, rippleX, rippleY, eWidth, eHeight;
    self = this;
    checkbox = self.find('.input-checkbox');

    //checkbox.on('mousedown', function(e) {
	$(document).on('mousedown','.input-checkbox', function(e) {
      if(e.button === 2) {
        return false;
      }

      if($(this).find('.ripple').length === 0) {
        $(this).append('<span class="ripple"></span>');
      }
      ripple = $(this).find('.ripple');

      eWidth = $(this).outerWidth();
      eHeight = $(this).outerHeight();
      size = Math.max(eWidth, eHeight);
      ripple.css({'width': size, 'height': size});
      ripple.addClass('animated');

      $(this).on('mouseup', function() {
        setTimeout(function () {
          ripple.removeClass('animated');
        }, 200);
      });
    });
  }
}(jQuery));
function change_checkbox_color() {
  $('.color-box .show-box').on('click', function() {
    $(".color-box").toggleClass("open");
  });
  $('.colors-list a').on('click', function() {
    var curr_color = $('main').data('checkbox-color');
    var color = $(this).data('checkbox-color');
    var new_colot = 'checkbox-' + color;

    $('.rkmd-checkbox .input-checkbox').each(function(i, v) {
      var findColor = $(this).hasClass(curr_color);

      if(findColor) {
        $(this).removeClass(curr_color);
        $(this).addClass(new_colot);
      }

      $('main').data('checkbox-color', new_colot);
    });
  });
}
function MsgFix(type,msg,cls){//info danger success warning
cls = (typeof cls !== 'undefined' ? cls : '');
$("#main").after('<div class="alert alert-'+type+' alert-fix alert-dismissible fade in '+cls+'" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+msg+'</div>');
}