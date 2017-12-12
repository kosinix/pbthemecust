<?php

/**
 * Use inline css to add theme color and other dynamic css
 */


/** Add theme color to Custom Css **/
if (!function_exists('shindiri_styles')) :
    function shindiri_styles()
    {

        global $pbtheme_data;

        echo '<style id="shindiri_styles" type="text/css">';

        if (DIVWP_WOOCOMMERCE === true) {
            echo '
				.pbtheme_shopping_cart .pbtheme_cart_button.div_checkout {
				    background-color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				/* inline Search Box*/
				.pbtheme_search_inline, .pbtheme_search {
				    border: 1px solid ' . $pbtheme_data['theme_color'] . ' !important;
				}
				.pbtheme_search_inline form button {
				    background: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				.woocommerce div.product p.price, .woocommerce-page div.product p.price, .woocommerce #content div.product p.price, .woocommerce-page #content div.product p.price {
				    color: #8FB73A;
				}
				.woocommerce div.product form.cart .button, .woocommerce-page div.product form.cart .button, .woocommerce #content div.product form.cart .button, .woocommerce-page #content div.product form.cart .button {
				    background-color: #8FB73A;
				}
				.woocommerce div.product span.price ins,
				.woocommerce-page div.product span.price ins,
				.woocommerce #content div.product span.price ins,
				.woocommerce-page #content div.product span.price ins,
				.woocommerce div.product p.price ins,
				.woocommerce-page div.product p.price ins,
				.woocommerce #content div.product p.price ins,
				.woocommerce-page #content div.product p.price ins {
				    color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				.woocommerce #content .quantity input.qty,
				.woocommerce .quantity input.qty,
				.woocommerce-page #content .quantity input.qty,
				.woocommerce-page .quantity input.qty {
				    border-color: ' . $pbtheme_data['theme_color'] . ' !important;
				    border-right: 1px solid ' . $pbtheme_data['theme_color'] . ' !important;
				}
				/* Price color */
				.woocommerce ul.products li.product .price ins,
				.woocommerce-page ul.products li.product .price ins {
				    color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
                /* Rewiews on single product */
                #review_form #submit {
                    background: ' . $pbtheme_data['theme_color'] . ' !important;
                }
				/* widget color fix*/
				.woocommerce-product-search input[type="submit"] {
				    background-color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				.woocommerce .widget_price_filter .price_slider_amount .button, .woocommerce-page .widget_price_filter .price_slider_amount .button {
				    background-color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				
				.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {
				    background: ' . $pbtheme_data['theme_color'] . ' !important;
				    border-color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				
				.coupon .input-text:focus {
                    box-shadow: 0 0 0 2px ' . $pbtheme_data['theme_color'] . ';
                }

				
				.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content, .woocommerce-page .widget_price_filter .price_slider_wrapper .ui-widget-content {
				    background: ' . $pbtheme_data['theme_color'] . ';
				}
				
				.woocommerce a.button,
				.woocommerce-page a.button {
				    background-color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				
				/* Cart fix */
				.woocommerce a.button, .woocommerce a.button.alt,
				.woocommerce-page a.button, .woocommerce-page a.button.alt {
				    background-color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				
				/* Checkout page */
				.woocommerce form .form-row, .woocommerce-page form .form-row {
				    border-color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				
				.woocommerce #payment #place_order, .woocommerce-page #payment #place_order {
				    background-color: ' . $pbtheme_data['theme_color'] . ' !important;
				}
				
				.woocommerce form .form-row input.input-text:focus,
                .woocommerce-page form .form-row input.input-text:focus,
                .woocommerce form .form-row textarea:focus,
                .woocommerce-page form .form-row textarea:focus,
                .select2-container .select2-choice:focus {
                    box-shadow: 0 0 0 2px ' . $pbtheme_data['theme_color'] . ';
                }
				
				.select2-drop-active {
					border: 1px solid ' . $pbtheme_data['theme_color'] . ' !important;
				}
				
				.woocommerce div.product span.price ins,
                .woocommerce-page div.product span.price ins,
                .woocommerce #content div.product span.price ins,
                .woocommerce-page #content div.product span.price ins,
                .woocommerce div.product p.price ins,
                .woocommerce-page div.product p.price ins,
                .woocommerce #content div.product p.price ins,
                .woocommerce-page #content div.product p.price ins {
                        color: ' . $pbtheme_data['theme_color'] . ' !important;
                }
                
                .woocommerce-checkout-review-order {
                    border: 2px solid ' . $pbtheme_data['theme_color'] . ';
                }
                
                /* Admin notice for woocommerce */
                .woocommerce-info,
                .woocommerce-eror,
                .woocommerce-message {
                    border-top: 3px solid ' . $pbtheme_data['theme_color'] . ';
                    background-color: ' . $pbtheme_data['wc_message_body_color'] . ';
                    color: ' . $pbtheme_data['wc_message_text_color'] . ';
                }
                
                .woocommerce-info a.button,
                .woocommerce-eror a.button,
                .woocommerce-message a.button {
                    color: ' . $pbtheme_data['wc_message_text_color'] . ';
                }
                
                .woocommerce-info a:not(.button),
                .woocommerce-eror a:not(.button),
                .woocommerce-message a:not(.button) {
                    color: ' . $pbtheme_data['wc_message_link_color'] . ';
                }
                
                .woocommerce-info:before,
                .woocommerce-eror:before,
                .woocommerce-message:before {
                    background-color: ' . $pbtheme_data['theme_color'] . ';
                    color: ' . $pbtheme_data['wc_message_text_color'] . ';
                }
			';
            
            /**
             * Add custom icon color if any set and override 
             */
            if ( isset( $pbtheme_data['wc_message_icon_color'] ) && !empty( $pbtheme_data['wc_message_icon_color'] ) ) {
                echo '
                    .woocommerce-info:before,
                    .woocommerce-eror:before,
                    .woocommerce-message:before {
                        color: ' . $pbtheme_data['wc_message_icon_color'] . ';
                    }';
            }
            
            /**
             * Hide product categories on product archives
             */
            if ( $pbtheme_data['woo_disable_product_category'] ) {
                echo '.woocommerce .products .product .posted_in {
                    display: none;
                }';
            }
            
            /**
             * add single product add to cart button custom padding top and bottm
             */
            if ( $pbtheme_data['woo_single_add_to_cart_pt'] != 0 ) {
                echo '.single-product .single_add_to_cart_button {
                    padding-top: ' . $pbtheme_data['woo_single_add_to_cart_pt'] . 'px !important;
                }';
            }
            if ( $pbtheme_data['woo_single_add_to_cart_pb'] != 0 ) {
                echo '.single-product .single_add_to_cart_button {
                    padding-bottom: ' . $pbtheme_data['woo_single_add_to_cart_pb'] . 'px !important;
                }';
            }
            
            /**
             * Custom price color to override theme color if selected
             */
            if ( isset( $pbtheme_data['wc_price_color'] ) && !empty( $pbtheme_data['wc_price_color'] ) ) {
                echo '
                    .woocommerce-Price-amount span,
                    .woocommerce-Price-amount {
                        color: ' . $pbtheme_data['wc_price_color'] . ';
                    }
                ';
            }
            // end add code
        }
        
        /**
         * Added by shindiri studio
         * Style search widgets to appear with theme color
         */
        echo '
            /* Search widget */
            
            .widget .search-form .input_field,
            .woocommerce-product-search input,
            .woocommerce-product-search .search-field,
            .widget_search input {
                border: 1px solid ' . $pbtheme_data['theme_color'] . ' !important;
            }

            #search-trigger .pbtheme_search_inline form button,
            .widget .search-form .button,
            .woocommerce-product-search button,
            .widget_search button {
                background: ' . $pbtheme_data['theme_color'] . ' !important;
            }
            ';
        
        echo '
            /* Content width on set resolution */
            @media(max-width: ' . $pbtheme_data['content_width'] . 'px) {
                .single-product #content {
                    padding: 15px 15px 0 15px;
                }
            }
            ';
        
        // end add code

        echo '</style>';
    }
endif;
// add_action('wp_head', 'shindiri_styles', 99);
