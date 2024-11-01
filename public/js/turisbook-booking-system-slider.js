(function ( $ ) {

    $.fn.tbsSlider = function( options ) {
        var settings = $.extend({
            height: '100vh',
            showNavigationBullets: true,
            showNavigationArrows: true,
            enableLightBox: true,
            animationTimer:5000
        }, options );

        var $jquery = this;
        var slider_count = 0;

        $(document).on('click','.tbs-slider-navigation-left', function(){
            var obj = $(this);
            var slider = obj.closest('.tbs-slider-wrap');
            $.fn.tbsSlider.changeSlide(slider,-1)
        });
        $(document).on('click','.tbs-slider-navigation-right', function(){
            var obj = $(this);
            var slider = obj.closest('.tbs-slider-wrap');
            $.fn.tbsSlider.changeSlide(slider)
        });

        $.fn.tbsSlider.changeSlide = function(slider,next){
            if (slider === undefined) return false;
            if (next === undefined) next = 1;
            var container = slider.find('.tbs-slider-slides');

            var total_slides = container.children().length;
            var aslide = container.find('.slide-in');
            var aposition = parseInt(aslide.attr('position'));
            var nposition = aposition +1;
            nposition = nposition > total_slides ? 1 : nposition;
            var nslide = container.find('[position="'+nposition+'"]');
            aslide.removeClass('slide-in').addClass('slide-out');
            nslide.removeClass('slide-out').addClass('slide-in');
        }

        var output = {
            'init': function(){
                $jquery.each(function() {
                    var slider = $(this);
                    if(!slider.hasClass('tbs-slider-wrap')){
                        var count = 0;
                        slider_count++;
                        slider.addClass('tbs-slider-wrap').attr('id','slider-' + slider_count).css({'height':settings.height});

                        var container = $('<div />').addClass('tbs-slider-slides');

                        slider.children().appendTo(container);

                        container.children().each(function(){
                            let child = $(this);
                            let src = child.data('src');
                            let background_position = child.data('background-position');
                            count++;
                            child.addClass('tbs-slider-slide').attr('position',count).attr('href',src).css({'background-image':'url('+src+')','background-position':background_position});
                            if(settings.enableLightBox){
                                child.addClass('tbs-slider-magnify');
                            }
                        });

                        if(container.find('.tbs-slider-slide.active').length==0){
                            container.find('.tbs-slider-slide:first-child').addClass('slide-in');
                        }

                        container.appendTo(slider);

                        if(settings.showNavigationArrows && count > 1){
                            $('<div />').addClass('tbs-slider-navigation-item tbs-slider-navigation-left').html('<i class="fas fa-caret-left"></i>').appendTo(slider);
                            $('<div />').addClass('tbs-slider-navigation-item tbs-slider-navigation-right').html('<i class="fas fa-caret-right"></i>').appendTo(slider);
                        }

                        setInterval(function(slider){
                            $.fn.tbsSlider.changeSlide(slider);
                        }, settings.animationTimer, slider);

                        var $gallery = new SimpleLightbox('#slider-'+slider_count+' .tbs-slider-slide', {});

                    }
                });
            },
            'nextSlide': function(){

            },
            'prevSlide': function(){

            }
        }
        output.init();

        return output

    };
}( jQuery ));