(function ( $ ) {
    setTimeout(function(){
        $('[tbs-modal]').each(function(){
            var obj = $(this);
            var target = obj.data('tbsModalTarget');
            obj.on('click',function(){
                console.log('enter');
                $(target).stop().fadeIn();
                return false;
            });
        });
    }, 500);

    $(document).on('click','.tbs-modal .close',function(){
        var obj = $(this);
        var modal = obj.closest('.tbs-modal');
        modal.stop().fadeOut();
        return false;
    });


}( jQuery ));