<?php
/**
 * Add search widget to header primary menu
 */
add_filter('wp_nav_menu_items', 'add_search_box_to_menu', 10, 2);
function add_search_box_to_menu($items, $args)
{
    global $pbtheme_data;

    $hidetopbar = get_post_meta(get_the_ID(), 'pbtheme_hidetopbar', true);
    if ($args->theme_location == 'pbtheme_primary') {

        $items .= '<li id="search-trigger">';

        if ( $pbtheme_data['header_search_custom'] == 0 ) {
            if ($pbtheme_data['header_search_style'] == 0) {
                $items .= '<a href="#"><i class="divicon-search"></i></a><div class="pbtheme_search">';
            } elseif ($pbtheme_data['header_search_style'] == 1) {
                $items .= '<div class="pbtheme_search_inline">';
            }

            if ($pbtheme_data['header_search_type'] == 0) {
                // $items .= do_shortcode( '[pw-ajax-live-search id="15779"]' );

                $items .= '<form action="' . home_url('/') . '" method="get"><input name="s" type="text" placeholder="Search the website"/>
                            <button type="submit"><i class="divicon-search"></i></button>
                            <div class="clearfix"></div>
                            </form>';

            } elseif ($pbtheme_data['header_search_type'] == 1) {

                $items .= '<form role="search" method="get" action="' . home_url('/') . '">
                <div>
                    <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="Search the products" />
                    <button type="submit" value="Search"><i class="divicon-search"></i></button>
                    <input type="hidden" name="post_type" value="product"/>
                </div>
            </form>';

            }
        } elseif( $pbtheme_data['header_search_custom'] == 1 && $pbtheme_data['header_mega_search_shortcode'] != '' ) {
            $shortcode_id = '"' . $pbtheme_data["header_mega_search_shortcode"] . '"';

            $items .= do_shortcode( $pbtheme_data["header_mega_search_shortcode"] ) ;
        }


        $items .= '</div></li>';
        if ($hidetopbar != 1 && isset($pbtheme_data['header-widgets-before']) && $pbtheme_data['header-widgets-before'] != 'none') {
            
            // Check if at least one sidebar area
            if ( intval( $pbtheme_data['header-widgets-before'] ) > 0 ) {
                
                for ( $i = 1; $i <= $pbtheme_data['header-widgets-before']; $i++ ) {
                    // If any sidebar area has widgets that add icon
                    if ( is_active_sidebar('header-widgets-before-' . $i) ) {
                        $items .= '<li id="widgets-trigger"><a href="#"><i class="divicon-plus cl2"></i></a></li>';
                        break;
                    }

                }
                
            }
        }
    }

    return $items;

}


/**
 * Add custom style to setup single product add to cart paddings from product metabox
 * It is related only to single product page
 */
function pbt_theme_sp_add_to_cart_padding() {
    
    if ( ! class_exists( 'WooCommerce' )  ) {
        return;
    }
    
    if ( ! is_product()  ) {
        return;
    }
    
    $prefix = '_selosity_';
    
    $padding_top = intval( get_post_meta( get_the_ID(), $prefix . 'single_add_to_cart_pt', true ) );
    $padding_bottom = intval( get_post_meta( get_the_ID(), $prefix . 'single_add_to_cart_pb', true ) );
    
    if ( empty( $padding_top ) || empty( $padding_bottom ) ) {
        return;
    }
    
    echo '<style type="text/css">';
    
    if ( !empty( $padding_top ) && $padding_top > 0 ) {
        echo '.single-product .single_add_to_cart_button {
            padding-top: ' . $padding_top . 'px !important;
        }';
    }
    
    if ( !empty( $padding_bottom ) && $padding_bottom > 0 ) {
        echo '.single-product .single_add_to_cart_button {
            padding-bottom: ' . $padding_bottom . 'px !important;
        }';
    }
    
    echo '</style>';
}
// add_action('wp_head', 'pbt_theme_sp_add_to_cart_padding', 1005);

/**
 * PBTheme Header Elements
 * 
 * Add element into top bar in theme options => Header settings => Top Heade left or Top header right
 */
if (!function_exists('pbtheme_header_elements')) :

    function pbtheme_header_elements($position = '', $location = 'header') {
        global $pbtheme_data;
        foreach ($pbtheme_data[$location . '-top-' . $position]['enabled'] as $n => $t) :
            if ($n == 'placebo')
                continue;
            printf('<div class="float_%2$s element-%1$s a-inherit pbtheme_pale_border">', $n, $position);
            switch ($n) :
                case 'login-link':
                    printf('<a href="%1$s" title="%2$s">%2$s</a>', wp_login_url(get_permalink()), __('Login', 'pbtheme'));
                    break;
                case 'language-bar':
                    require_once(ABSPATH . 'wp-admin/includes/admin.php');
                    $path = 'transposh-translation-filter-for-wordpress/transposh.php';
                    if(isset($pbtheme_data['transposh_enable']) && $pbtheme_data['transposh_enable'] == 1 && is_plugin_active(plugin_basename($path))){
                        $transposh = &$GLOBALS['my_transposh_plugin'];
                        $plugpath = parse_url($transposh->transposh_plugin_url, PHP_URL_PATH);
                        //include_once $transposh->transposh_plugin_dir."transposh.php";

                        $clean_page_url = $transposh->get_clean_url();
                        $page_url = '';
                        if (is_404()) {
                            $clean_page_url = transposh_utils::cleanup_url($transposh->home_url, $transposh->home_url, true);
                        }

                        $languages = $transposh->options->get_sorted_langs();
                        $langrecord = $languages[$transposh->target_language];
                        list ($langname, $language, $flag) = explode(',', $langrecord);
                        printf('<a href="#" class="language_selected"><span>%1$s</span>&nbsp;<img src="%2$s.png" /><i class="fa fa-angle-down"></i></a><ul>', $langname, $plugpath."/img/flags/".$flag);

                        foreach ($languages as $code => $langrecord) {
                            list ($langname, $language, $flag) = explode(',', $langrecord);

                            // Only send languages which are active
                            if ($transposh->options->is_active_language($code) || ($transposh->options->is_default_language($code))) {
                                // now we alway do this... maybe cache this to APC/Memcache
                                if ($transposh->options->enable_url_translate && !$transposh->options->is_default_language($code)) {
                                    $page_url = transposh_utils::translate_url($clean_page_url, '', $code, array(&$transposh->database, 'fetch_translation'));
                                } else {
                                    $page_url = $clean_page_url;
                                }

                                // clean $code in default lanaguge
                                $page_url = transposh_utils::rewrite_url_lang_param($page_url, $transposh->home_url, $transposh->enable_permalinks_rewrite, $transposh->options->is_default_language($code) ? '' : $code, $transposh->edit_mode);

                                printf('<li><a href="%2$s">%1$s</a><img src="%3$s.png" /></li>', $langname, htmlentities($page_url), $plugpath."/img/flags/".$flag);
//
//                                    $widget_args[] = array(
//                                        'lang' => $langname,
//                                        'langorig' => $language,
//                                        'flag' => $flag,
//                                        'isocode' => $code,
//                                        'url' => htmlentities($page_url), // fix that XSS
//                                        'active' => ($transposh->target_language == $code));
                            }
                        }
                        echo '</ul>';
                        //the_widget('transposh_plugin_widget');
                    }else{
                        $languages = $pbtheme_data['language'];
                        printf('<a href="#" class="language_selected"><span>%1$s</span><i class="fa fa-angle-down"></i></a><ul>', $languages[1]['language']);
                        foreach ($languages as $language) {
                            $flag = (isset($language['flag']) ? $language['flag'] : '' );
                            $lang = $language['language'];
                            $langurl = $language['langurl'];
                            printf('<li><a href="%2$s">%1$s</a><img src="%3$s" /></li>', $lang, $langurl, get_template_directory_uri() . '/images/flags/' . $flag);
                        }
                        echo '</ul>';
                    }
                    break;
                case 'network-icons':
                    $networks = @$pbtheme_data['contact'][$pbtheme_data[$location . '_networks']]['contact'];
                    if(is_array($networks)) foreach ($networks as $network) {
                        printf('<a href="%1$s" class="inline-block"><img width="24" height="24" src="%2$s/images/socialnetworks/%3$s" class="block" /></a>', $network['socialnetworksurl'], get_bloginfo('template_directory'), $network['socialnetworks'], $position);
                    }

                    break;
                case 'tagline':
                    echo $pbtheme_data[$location . '_tagline'];
                    break;
                case 'to-the-top':
                    printf('<a href="#pbtheme_wrapper" title="%1$s">%2$s</a>', __('UP!', 'pbtheme'), ( $pbtheme_data['footer_up_text'] !== '' ? $pbtheme_data['footer_up_text'] : '<i class="fa fa-angle-up"></i>'));
                    break;
                case 'tagline-alt':
                    echo $pbtheme_data[$location . '_tagline_alt'];
                    break;
                case 'menu':
                    if ($pbtheme_data[$location . '_menu'] !== 'none') {
                        ?>
                        <nav class="a-inherit">
                                <?php echo wp_nav_menu(array('menu' => $pbtheme_data[$location . '_menu'], 'depth' => '1', 'fallback_cb' => 'pbtheme_list_pages', 'container' => false, 'menu_id' => '', 'menu_class' => 'list_style')); ?>
                        </nav>
                                <?php
                    }
                    break;
                case 'woo-login-link':
                    if (DIVWP_WOOCOMMERCE === true) :
                        global $woocommerce;
                        $myaccount_page_id = get_option('woocommerce_myaccount_page_id');
                        if ($myaccount_page_id) {
                            $myaccount_page_url = get_permalink($myaccount_page_id);
                        } else {
                            $myaccount_page_url = get_permalink();
                        }
                        if (is_user_logged_in()) {
                            printf('<a href="%1$s" title="%2$s">%2$s</a>', $myaccount_page_url, apply_filters( 'pbtheme_header_elements_myaccount_text', esc_html__( 'My Account', 'pbtheme' ) ));
                        } else {
                            printf('<a href="%1$s" title="%2$s">%2$s</a>', $myaccount_page_url, apply_filters( 'pbtheme_header_elements_myaccount_text', esc_html__('Log in', 'pbtheme') ));
                        }
                    endif;
                    break;
                case 'woo-cart':
                    
                    /**
                     * Merged html from here and form div_woocommerce_header_add_to_cart_fragment 
                     * as it was the same but at two places and hard to edit twice
                     * Moved into separate function pb_theme_top_header_mini_cart
                     */
                    echo pb_theme_top_header_mini_cart();

                    break;
            endswitch;
          echo '</div>';
      endforeach;
  }

endif;

/**
 * Ensure cart contents update when products are added to the cart via AJAX
 */
if (!function_exists('div_woocommerce_header_add_to_cart_fragment')) {

    function div_woocommerce_header_add_to_cart_fragment($fragments) {

        pb_theme_top_header_mini_cart();
        $fragments['div.woo_shopping.woo_shopping_cart'] = ob_get_clean();

        return $fragments;
    }
}
add_filter('add_to_cart_fragments', 'div_woocommerce_header_add_to_cart_fragment');



/**
 * Return top bar mini cart contents
 */
if ( !function_exists( 'pb_theme_top_header_mini_cart' ) ) {
    function pb_theme_top_header_mini_cart() {

        if (DIVWP_WOOCOMMERCE === true) {

            global $woocommerce;

            ob_start();

            printf('<div class="woo_shopping woo_shopping_cart float_right"><a class="cart-contents" href="%1$s" title="%2$s"><i class="divicon-cart"></i> <span class="pbtheme-cart-items-no">%3$s -</span> %4$s</a>', $woocommerce->cart->get_cart_url(), __('View your shopping cart', 'pbtheme'), sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'pbtheme'), $woocommerce->cart->cart_contents_count), $woocommerce->cart->get_cart_total());

            if (sizeof($woocommerce->cart->get_cart()) > 0) {
                echo '<div id="div_woocart" class="pbtheme_shopping_cart">';
                foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
                    $_product = $values['data'];
                    if ($_product->exists() && $values['quantity'] > 0) {
                        ?>
                        <div class = "pbtheme_cart_item">

                            <div class="div-cart-remove a-inherit">
                                <?php
                                echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url($woocommerce->cart->get_remove_url($cart_item_key)), __('Remove this item', 'pbtheme')), $cart_item_key);
                                ?>
                            </div>

                            <div class="div-cart-thumbnail">
                                <?php
                                $thumbnail = apply_filters('pbtheme-square', $_product->get_image(), $values, $cart_item_key);

                                if (!$_product->is_visible() || (!empty($_product->variation_id) && !$_product->parent_is_visible() ))
                                    echo $thumbnail;
                                else
                                    printf('<a href="%s">%s</a>', esc_url(get_permalink(apply_filters('woocommerce_in_cart_product_id', $values['product_id']))), $thumbnail);
                                ?>
                            </div>

                            <div class="div-cart-name a-inherit pbtheme_header_font">
                                <?php
                                if (!$_product->is_visible() || (!empty($_product->variation_id) && !$_product->parent_is_visible() ))
                                    echo apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key);
                                else
                                    printf('<a href="%s">%s</a>', esc_url(get_permalink(apply_filters('woocommerce_in_cart_product_id', $values['product_id']))), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key));

                                // Meta data
                                echo $woocommerce->cart->get_item_data($values);

                                // Backorder notification
                                if ($_product->backorders_require_notification() && $_product->is_on_backorder($values['quantity']))
                                    echo '<p class="backorder_notification">' . __('Available on backorder', 'pbtheme') . '</p>';
                                ?>
                            </div>


                            <div class="div-cart-subtotal pbtheme_header_font div-cart-quantity">
                                <?php
                                echo apply_filters('woocommerce_cart_item_subtotal', $woocommerce->cart->get_product_subtotal($_product, $values['quantity']), $values, $cart_item_key);

                                echo ' x ';

                                if ($_product->is_sold_individually()) {
                                    $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                } else {

                                    $product_quantity = esc_attr($values['quantity']);
                                }

                                echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key);
                                ?>

                            </div>

                        </div>
                    <?php
                    }
                }
                
                printf('<a href="%2$s" class="pbtheme_cart_button div_view_cart float_left">%1$s</a>', __('View cart', 'pbtheme'), $woocommerce->cart->get_cart_url());
                printf('<a href="%2$s" class="pbtheme_cart_button div_checkout float_right">%1$s</a>', __('Checkout', 'pbtheme'), $woocommerce->cart->get_checkout_url());
                echo '</div></div>';
                
            } else {
                $shop = wc_get_page_id('shop') ? get_the_title(wc_get_page_id('shop')) : '';
                echo '<div id="div_woocart" class="pbtheme_shopping_cart pbtheme_empty_cart">
                            <h3>' . __('Your shopping cart is empty', 'pbtheme') . '</h3>
                            <span>' . __('Why not add some items in our', 'pbtheme') . ' <a href="' . get_permalink(wc_get_page_id('shop')) . '">' . __('Shop', 'pbtheme') . '</a></span>
                            </div></div>';
            }

        }

    }
}


/**
 * Function to change placeholder on Checkout page with label
 *
 * @param $args
 * @param $key
 * @param $value
 * @return mixed
 */
function change_placeholder( $args, $key=false, $value=false ) {

    if ( $args['required'] != 1 ) {
        $args['placeholder'] = $args['label'] . __( ' (optional)', 'woocommerce' );

    } else {
        $args['placeholder'] = $args['label'];
    }

    return $args;
}
add_filter( 'woocommerce_form_field_args', 'change_placeholder', 30 );


/**
 * Optimize not WooCommerce pages
 */
function pb_theme_manage_woocommerce_styles() {
    //remove generator meta tag
    if ( isset( $GLOBALS['woocommerce'] ) ) {
        remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
    }
  
    //first check that woo exists to prevent fatal errors
    if ( function_exists( 'is_woocommerce' ) ) {
        //dequeue scripts and styles
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }
 }
add_action( 'wp_enqueue_scripts', 'pb_theme_manage_woocommerce_styles', 99 );

if ( ! function_exists( 'pbtheme_page_image_background' ) ) :
/**
 * Handles page image background from page settings metabox
 */
function pbtheme_page_image_background() {
     
    if ( ! is_page() ) {
        return;
    }
    
    /**
     * Logic is:
     * If no background image, set color as fallback but only for that resolution for particular image
     */
    
    $desktop_image = get_post_meta(get_the_ID(), 'pbtheme_page_image', true);
    $tablet_image = get_post_meta(get_the_ID(), 'pbtheme_page_tablet_image', true);
    $tablet_image_fallback = get_post_meta(get_the_ID(), 'pbtheme_tablet_page_img_fallback', true);
    $mobile_image = get_post_meta(get_the_ID(), 'pbtheme_page_mobile_image', true);
    $mobile_image_fallback = get_post_meta(get_the_ID(), 'pbtheme_mobile_page_img_fallback', true);
    $color = get_post_meta(get_the_ID(), 'pbtheme_page_image_fallback_color', true);
    $color_css = !empty( $color ) ? '.pbtheme_page_image_bg { background-color: ' . esc_attr( $color ) . '; background-image: none; }' : '';
    $bg_size = get_post_meta(get_the_ID(), 'pbtheme_page_image_bg_size', true);
    $bg_align = get_post_meta(get_the_ID(), 'pbtheme_page_image_bg_align', true);
     
    // Add control for desktop image / background
    if ( !empty( $desktop_image ) ) {
        echo '.pbtheme_page_image_bg { background-image: url("' . esc_url( $desktop_image ) . '"); background-color: transparent; }'; 
    } else {
        echo $color_css;
    }
    
    // Add control for tablet image / background
    if ( !empty( $tablet_image ) ) {
        echo '@media(max-width: 1024px) and (min-width: 481px) {
            .pbtheme_page_image_bg { background-image: url("' . esc_url( $tablet_image ) . '"); background-color: transparent; }
        }';
    } elseif ( $tablet_image_fallback === 'color' ) {
        echo '@media(max-width: 1024px) and (min-width: 481px) {
            ' . $color_css . '
        }';
    }
    
    // Add control for mobile image / background
    if ( !empty( $mobile_image ) ) {
        echo '@media(max-width: 480px) {
            .pbtheme_page_image_bg { background-image: url("' . esc_url( $mobile_image ) . '"); background-color: transparent; }
        }';
    } elseif ( $mobile_image_fallback === 'color' ) {
        echo '@media(max-width: 480px) {
            ' . $color_css . '
        }';
    }
    
    // Add size
    if ( !empty( $bg_size ) ) {

        switch ( $bg_size ) {
            case 'tile':
                echo '.pbtheme_page_image_bg { background-repeat: repeat; background-size: auto; }'; 
                break;
            
            case 'tile_x':
                echo '.pbtheme_page_image_bg { background-repeat: repeat-x; background-size: auto; }'; 
                break;
            
            case 'tile_y':
                echo '.pbtheme_page_image_bg { background-repeat: repeat-y; background-size: auto; }'; 
                break;
            
            case 'stretch':
                echo '.pbtheme_page_image_bg { background-size: 100% 100%; }'; 
                break;

            default:
                echo '.pbtheme_page_image_bg { background-size: ' . esc_attr( $bg_size ) . '; }'; 
                break;
        }
        
    }
    
    // Add align
    if ( !empty( $bg_align ) ) {
        echo '.pbtheme_page_image_bg { background-position: ' . esc_attr(str_replace( '_', ' ', $bg_align ) ) . '; }'; 
    }
     
     
}
endif;
add_action( 'pbtheme_dynamic_css', 'pbtheme_page_image_background', 20 );


if ( ! function_exists( 'pbtheme_page_video_image_until_loaded' ) ) :
/**
 * Add image background until video loaded for page video iframe or embed
 */
function pbtheme_page_video_image_until_loaded() {
    
    if ( ! is_page() ) {
        return;
    }
    
    $allowed = array( 'videoembed', 'vimeo', 'html5video' );
    $selection = get_post_meta( get_the_ID(), 'pbtheme_page_bg', true );
    
    if ( ! in_array( $selection, $allowed ) ) {
        return;
    }
    
    $desktop_image = get_post_meta(get_the_ID(), 'pbtheme_page_image', true);
    $color = get_post_meta(get_the_ID(), 'pbtheme_page_image_fallback_color', true);
 
    if ( !empty( $desktop_image ) ) {
        echo '.pbtheme_page_bg--video {
            background-image: url("' . esc_url( $desktop_image ) . '");
        }';
    } elseif ( !empty ( $color ) ) {
        echo '.pbtheme_page_bg--video,
        .pbtheme_page_bg--video video {
            background-color: ' . esc_attr( $color ) . ';
        }';
    }
}
endif;
add_action( 'pbtheme_dynamic_css', 'pbtheme_page_video_image_until_loaded', 20 );