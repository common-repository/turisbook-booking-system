(function ( $ ) {

    $.fn.tbsStickyAnchor = function( options ) {
        var settings = $.extend({
        }, options );


        $.fn.tbsStickyAnchor.relocate = function(element){
            var window_top = $(window).scrollTop();
            var window_width = $(window).width();


            $('.tbs-sticky-anchor').each(function(){
                var anchor = $(this);
                var target_ident = anchor.attr('anchor-target');

                if(target_ident !== undefined){

                    var target = $(target_ident);

                    if(window_width > 768 || (window_width <= 768 && target.hasClass('sticky-mobile'))){

                        var offset = target.attr('offset');

                        if(offset === undefined) offset = 0;

                        var offset_float = parseFloat(offset);
                        var offset_abs = Math.abs(offset);

                        var div_top = anchor.offset().top + offset_float;

                        var sticky_height = target.attr('height');
                        var sticky_width = target.attr('width');

                        if (window_top > div_top) {
                            target.addClass('stick');
                            if(offset != 0) target.css('top', offset_abs + 'px');
                            anchor.height(sticky_height);
                            target.width(sticky_width);
                        } else {
                            target.removeClass('stick').css('top', '0px');
                            anchor.height(0);
                        }

                    }else{
                        target.removeClass('stick');
                    }
                }
            });
        }


        var $jquery = this;
        var anchor_count = 0;
        var output = {
            'init': function(){
                setTimeout(function(){
                    var elements = $('.tbs-sticky');
                    elements.each(function() {
                        var element = $(this);
                        anchor_count++;
                        var eclass = 'element-to-stick-' + anchor_count;
                        element.attr('height',element.height());
                        element.attr('width',element.width());
                        element.addClass(eclass);
                        $('<div />').addClass('tbs-sticky-anchor').attr('anchor-target','.'+eclass).insertBefore(element);
                        $(window).scroll(function(){
                            $.fn.tbsStickyAnchor.relocate();
                        });
                    });
                },250);
            }
        }
        output.init();

        return output
    };
    $.fn.tbsStickyAnchor();
}( jQuery ));