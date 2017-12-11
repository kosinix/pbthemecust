/**
 * Init blog post slider widgets
 */
(function($){
    
    "use strict";

    $(document).ready(function(){
        
        var sliders = [];
        
        $('.wpbtheme_bps').each(function( index, val ){
            
            var $self = $(this);
            var $slidesNo = $self.data('count'); 
            $self.addClass('wpbtheme_bps' + index);
            
            var $container = $('.wpbtheme_bps' + index + ' .swiper-container');
            
            var slider = new Swiper( '.wpbtheme_bps' + index + ' .swiper-container', {
                paginationClickable: true,
                autoHeight: true,
                spaceBetween: 0,
                //autoplay: 2500,
                //autoplayDisableOnInteraction: false,
                loop: $slidesNo > 1 ? true : false,
            });

            $self.find('.swiper-button-prev').on('click', function(e){
              e.preventDefault()
              slider.swipePrev()
            })
            $self.find('.swiper-button-next').on('click', function(e){
              e.preventDefault()
              slider.swipeNext()
            })

            sliders.push(slider);
            
        });

    });

})(jQuery);