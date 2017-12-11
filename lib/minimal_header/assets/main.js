/*** Minimal herader ***/
(function($){
    
    $(document).ready(function(){
        
        // Show search in minimal header
        $('#pbtheme-min-header-search > span').click(function(){
            $('#pbtheme-min-header-search .pbtheme_search').fadeToggle(350);
        });

        $('#pbtheme-min-header-cats > i').click(function(){
            $('.pbtheme-min-header-cats').slideToggle(350);
        });
        
    });
    

    $(window).on("load",function(){
        
        // Show categories
        $(".pbtheme-min-header-cats").mCustomScrollbar({
            autoHideScrollbar:true,
            theme:"dark-thin",
        });
    });
    
})(jQuery);