/* Custom scrollbar for mini cart */

(function($){

    $(window).on("load",function(){
        initScrollbar();
    });
    
    function initScrollbar() {
        
        $("#div_woocart").mCustomScrollbar({
            autoHideScrollbar:true,
            theme:"dark-thin",
            live: "on"
        });
        
    }
    
})(jQuery);