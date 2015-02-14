(function($){
    $('#header__icon').click(function(e){
        e.preventDefault();
        $('body').toggleClass('with--sidebar');
        $('#menu').toggleClass('is_active');
    });

    $('#site-cache').click(function(e){
        $('body').removeClass('with--sidebar');
        $('#menu').removeClass('is_active');
    })
})(jQuery);
