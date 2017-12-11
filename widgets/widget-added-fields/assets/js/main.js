/**
 * Init widgets additional fields
 */

(function($){
    
    "use strict";

    // Handle widget admin screen init
    $(document).ready(function(){

        // Init widgets on page load
        $('.pbtheme-widgets-added-fields').each(function(){
            
            var id = '#' + $(this).attr('id');

            // Avoid widget base with __i__
            if ( id.indexOf('__i__') === -1 ) {
                $(id).find('.color-field').wpColorPicker();
            } 

        });
        
    });

    /**
     * Handle customizer init, adding and update widget form
     * Handle widget admin screen update and adding widgets
     * @see wp-admin/js/widgets.js
     */
    $(document).on('widget-added widget-updated', function( event, $widget ) {
        $widget.find('.color-field').wpColorPicker();
    });

    
})(jQuery);