(function($){
    "use strict";
    $(document).ready( function() {

        // console.log( $('.woocommerce-product-search') );

        $("input[type='submit']" ).change(function() {

        });

        $('.woocommerce-product-search').find("input[type='submit']" ).change(function() {
            $(this).innerHTML = 'Ovo je samo proba';
        });

    });

    $(window).load( function () {
        var chk_width = $('.woocommerce .woocommerce-checkout').width();
        // console.log( chk_width );

        if ( chk_width < 900 ) {
            $('.woocommerce .woocommerce-checkout').find('#customer_details').css("cssText", "width: 100% !important;");
            $('.woocommerce .woocommerce-checkout').find('#order_review').width('calc(100% - 64px)');
        }
    });

})(jQuery);