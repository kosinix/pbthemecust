/* Shi Gmap shortcode */
(function($){

	"use strict";
    
	$(window).load(function(){
		
		// Add class gmap-scrolloff to row_html shortcode to activate this script
        console.log($('.wpbtheme-cf7'));
		$('.wpbtheme-cf7__map').click(function () {
			$(this).find('iframe').css("pointer-events", "auto");
		});

		$('.wpbtheme-cf7__map').mouseleave(function() {
		  $(this).find('iframe').css("pointer-events", "none"); 
		});
	});
	
})(jQuery)