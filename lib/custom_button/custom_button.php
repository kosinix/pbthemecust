<?php

/*
 * Class for custom button shortcode
 * 
 * Some buttons animations inspiration
 * http://tympanus.net/Development/ButtonStylesInspiration/
 */
class Sellocity_Custom_Button {
    
    /**
     * Shortcode slug
     * @var string
     */
    public static $shortcode_slug = 'sellocity-button';
    
    /**
     * Body css class
     * @var string
     */
    private $body_class = 'sellocity-custom-button--active';
    
    /**
     * Constructor
     */
    public function __construct() {
        
        add_shortcode( 'sellocity-button', array(  $this, 'content' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
        add_filter( 'body_class', array( $this, 'body_classes' ) );
        
    }

    /**
     * Generate custom button html
     */
    public static function content($atts, $content = null) {
        
        $atts = shortcode_atts( array(
            'btn_text' => esc_html__('Button', 'pbtheme'),
            'hover_btn_type' => 'hover_none', // Some animation disabled for this integration
            'btn_text_size' => 'btn_med', 
            'font_size_p' => '',
            'letter_spacing' => '0',
            'btn_type' => 'btn_crcl',
            'btn_link_url' => '#',
            'btn_icon_type' => 'icon_no',
            'icon_class' => '',
            'btn_float' => 'center', // Disabled for this integration 
            'btn_back_color' => '#34495e',
            'btn_hover_color' => '#182a38',
            'btn_text_color' => '#ffffff',
            'btn_text_color_hover' => '#ffffff',
            'border_width' => '1',
            'btn_border_color' => '',
            'btn_border_color_hover' => '',
            'css_class' => 'sellocity-custom-btn-medium',
        ), $atts, self::$shortcode_slug );
        extract( $atts );
        
        // Check if atts are not empty
        $btn_text = !empty( $atts['btn_text'] ) ? $atts['btn_text'] : '';
        $btn_link_url = !empty( $btn_link_url ) ? $btn_link_url : '#';
        $btn_back_color = !empty( $btn_back_color ) ? $btn_back_color : 'transparent';
        $btn_hover_color = !empty( $btn_hover_color ) ? $btn_hover_color : 'transparent';
        $btn_text_color = !empty( $btn_text_color ) ? $btn_text_color : 'white';
        $btn_text_color_hover = !empty( $btn_text_color_hover ) ? $btn_text_color_hover : 'white';
        $btn_border_color = !empty( $btn_border_color ) ? $btn_border_color : 'transparent';
        $btn_border_color_hover = !empty( $btn_border_color_hover ) ? $btn_border_color_hover : 'transparent';
        $btn_float = !empty( $btn_float ) ? $btn_float : 'center';
        
        if( $btn_type === 'btn_rnd' ){
            $radius = 'border-radius: 10px;';
        } elseif( $btn_type === 'btn_crcl' ){
            $radius = 'border-radius: 36px;';
        } else{
            $radius = '';
        }

        if( !empty( $font_size_p ) ){
            $font_size = 'font-size: ' . $font_size_p . 'px;';
        } else {
            if( $btn_text_size === 'btn_lrg' ){
                $font_size = 'font-size: 18px;';
            } elseif( $btn_text_size === 'btn_smll' ){
                $font_size = 'font-size: 12px;';
            } else{
                $font_size = '';
            }
        }  
        
        $border_width_px = 'border-width: ' . $border_width . 'px;';
        $antiman_width = $border_width + 2;
        
        $spacing = 'letter-spacing: ' . $letter_spacing . 'px;';
        
        // Pick up icon class
        $iconClass = isset( $icon_class ) ? $icon_class : '';
        $unique_id = uniqid(); 
        
        if( $btn_icon_type === 'icon_hov_left' || $btn_icon_type === 'icon_hov_right' ){
            $hover_btn_class = 'btn_hovered';
        } else {
            $hover_btn_class = '';
        }
        
        if( $btn_icon_type === 'icon_hov_left' ){
            $ico_hover = 'left_ico_hover';
        } elseif( $btn_icon_type === 'icon_hov_right' ){
            $ico_hover = 'right_ico_hover';
        } else {
            $ico_hover = '';
        }
        
        // We need global filled with inline scripts to be printed in footer hook
        global $shindiri_custom_button;
        
        // Check if not defined set as empty
        if ( ! isset( $shindiri_custom_button ) || empty( $shindiri_custom_button ) ) {
            $shindiri_custom_button = '';
        }

        // Add all inline scripts to be printed in footer
        $shindiri_custom_button .= '<script type="text/javascript">
                    jQuery(".custom-button-' . $unique_id . '").find("a").hover(function(){
                       jQuery(".custom-button-' . $unique_id . ' a").css("background-color", jQuery(".custom-button-' . $unique_id . ' a").data("hover")); 
                       jQuery(".custom-button-' . $unique_id . ' a").css("border-color", jQuery(".custom-button-' . $unique_id . ' a").data("borderhover")); 
                       jQuery(".custom-button-' . $unique_id . ' a").css("color", jQuery(".custom-button-' . $unique_id . ' a").data("texthover")); 
                    }, function(){
                        jQuery(".custom-button-' . $unique_id . ' a").css("background-color", jQuery(".custom-button-' . $unique_id . ' a").data("background")); 
                        jQuery(".custom-button-' . $unique_id . ' a").css("border-color", jQuery(".custom-button-' . $unique_id . ' a").data("border")); 
                        jQuery(".custom-button-' . $unique_id . ' a").css("color", jQuery(".custom-button-' . $unique_id . ' a").data("text")); 
                    }
                    );
                    
                    jQuery(document).ready(function(){
                        var padTop = jQuery(".custom-button-' . $unique_id . '").find("a").css("padding-top");
                        var padLeft = jQuery(".custom-button-' . $unique_id . '").find("a").css("padding-left");
                        var iconWidth = jQuery(".custom-button-' . $unique_id . ' a").find("i").width();
                            
                        if( jQuery(".custom-button-' . $unique_id . ' a").find("i").hasClass("hover-icon")){
                            jQuery(".custom-button-' . $unique_id . ' a").find("i").css("top", padTop);
                        }
                            
                        if( jQuery(".custom-button-' . $unique_id . ' a").find("i").hasClass("hover-icon") && jQuery(".custom-button-' . $unique_id . ' a").find("i").hasClass("icon-left") ){
                            jQuery(".custom-button-' . $unique_id . ' a").find("i").css("left", parseInt(padLeft, 10));
                        }
                        
                        if( jQuery(".custom-button-' . $unique_id . ' a").find("i").hasClass("hover-icon") && jQuery(".custom-button-' . $unique_id . ' a").find("i").hasClass("icon-right") ){
                            jQuery(".custom-button-' . $unique_id . ' a").find("i").css("right", parseInt(padLeft, 10));
                        }
                        
                        jQuery(".custom-button-' . $unique_id . '").find("a.right_ico_hover").on("mouseenter, mouseover", function(){
                            var iconWidth = jQuery(".custom-button-' . $unique_id . ' a").find("i").width();
                            var pixels = "translate(" + -((iconWidth+10)/2) + "px, 0)";
                            var icoPixels = "translate(" + ((iconWidth+10)/2) + "px, 0)";
                            var pixelsWinona = "translate(" + -((iconWidth+10)/2) + "px, -50%)";
                            var pixelSaqui = "translate(" + -((iconWidth+10)/2) + "px, -50%) rotate3d(0, 0, 1, 0deg)";
                            
                            jQuery(this).find("span.main-text").css("transform", pixels);
                            jQuery(this).find("span.winona-text").css("transform", pixelsWinona);
                            jQuery(this).find("span.saqui-text").css("transform", pixelSaqui);
                            jQuery(this).find("i").css("transform", icoPixels);
                        });
                        
                        jQuery(".custom-button-' . $unique_id . '").find("a.right_ico_hover").on("mouseleave", function(){
                            var pixels = "translate(0, 0)";
                            var pixelsWinona = "translate(0, -50%)";
                            var pixelSaqui = "translate(0, -50%) rotate3d(0, 0, 1, 45deg)";
                            
                            jQuery(this).find("span.main-text").css("transform", pixels);
                            jQuery(this).find("span.winona-text").css("transform", pixelsWinona);
                            jQuery(this).find("span.saqui-text").css("transform", pixelSaqui);
                            jQuery(this).find("i").css("transform", pixels);
                        });
                        
                        jQuery(".custom-button-' . $unique_id . '").find("a.left_ico_hover").on("mouseenter, mouseover", function(){
                            var iconWidth = jQuery(".custom-button-' . $unique_id . ' a").find("i").width();             
                            var pixels = "translate(" + ((iconWidth+10)/2) + "px, 0)";
                            var pixelsWinona = "translate(" + ((iconWidth+10)/2) + "px, -50%)";
                            var pixelSaqui = "translate(" + ((iconWidth+10)/2) + "px, -50%) rotate3d(0, 0, 1, 0deg)";
                            var icoPixels = "translate(" + -((iconWidth+10)/2) + "px, 0)";
                            
                            jQuery(this).find("span.main-text").css("transform", pixels);
                            jQuery(this).find("span.winona-text").css("transform", pixelsWinona);
                            jQuery(this).find("span.saqui-text").css("transform", pixelSaqui);
                            jQuery(this).find("i").css("transform", icoPixels);
                        });
                        
                        jQuery(".custom-button-' . $unique_id . '").find("a.left_ico_hover").on("mouseleave", function(){
                            var pixels = "translate(0, 0)";
                            var pixelsWinona = "translate(0, -50%)";
                            var pixelSaqui = "translate(0, -50%) rotate3d(0, 0, 1, 45deg)";
                            
                            jQuery(this).find("span.main-text").css("transform", pixels);
                            jQuery(this).find("span.winona-text").css("transform", pixelsWinona);
                            jQuery(this).find("span.saqui-text").css("transform", pixelSaqui);
                            jQuery(this).find("i").css("transform", pixels);
                        });
                    });
            </script>';
        
        // Add all scripts printed in footer after swiper.min.js
        add_action( 'wp_footer', array( 'Sellocity_Custom_Button', 'shindiri_custom_button' ), 1002);
        
        // Set output shortcode
        $output =  '<div class="sellocity-custom-button custom-button custom-button-' . $unique_id . ' ' . $hover_btn_class . '" style="text-align: ' . esc_attr( $btn_float ) . ';">';
        $output .= '    <a class="' . $ico_hover . ' ' . $css_class . ' ' . $hover_btn_type . '" href="' . esc_url( $btn_link_url ) . '" data-border="' . esc_attr( $btn_border_color ) . '" data-background="' . esc_attr( $btn_back_color ) . '" data-hover="' . esc_attr( $btn_hover_color ) . '" data-borderhover="' . esc_attr( $btn_border_color_hover ) . '" data-text="' . esc_attr( $btn_text_color ) . '" data-texthover="' . esc_attr( $btn_text_color_hover ) . '" style="' . $spacing . ' ' . $font_size . ' ' . $radius . ' color: ' . esc_attr( $btn_text_color ) . '; border: ' . $border_width . 'px solid ' . esc_attr( $btn_border_color ) . '; background: ' . esc_attr( $btn_back_color ) . ';">';
        
        if( $hover_btn_type === 'hover_winona' ){
            $output .= '<span class="winona-text ' . $css_class . ' ' . $btn_icon_type . '" style="' . $spacing . ' ' . $font_size . ' ' . $radius . ' padding-top: 0 !important; padding-bottom: 0 !important;">' . wp_kses_post( $btn_text ) . '</span>';        
        }
        
        if( $hover_btn_type === 'hover_saqui' ){
            $output .= '<span class="saqui-text ' . $css_class . ' ' . $btn_icon_type . '" style="' . $spacing . ' ' . $font_size . ' ' . $radius . ' padding-top: 0 !important; padding-bottom: 0 !important;">' . wp_kses_post( $btn_text ) . '</span>';
        }
        
        if( $hover_btn_type === 'hover_antiman' ){
            $output .= '<span class="antiman-text ' . $css_class . '" style="' . $spacing . ' ' . $font_size . ' ' . $radius . ' padding-top: 0 !important; padding-bottom: 0 !important; border: ' . $antiman_width . 'px solid ' . $btn_border_color_hover . '; top: -' . $border_width . 'px; left: -' . $border_width . 'px; width: calc(100% + ' . $border_width*2 . 'px); height: calc(100% + ' . $border_width*2 . 'px);"></span>';
        }
        
        if( $hover_btn_type === 'hover_isi' ){
            $output .= '<span class="isi-text ' . $css_class . '" style="' . $spacing . ' ' . $font_size . ' ' . $radius . ' padding-top: 0 !important; padding-bottom: 0 !important; background: ' . $btn_hover_color . '"></span>';
        }
        
        if( $hover_btn_type === 'hover_moema' ){
            $output .= '<span class="moema-text ' . $css_class . '" style="' . $spacing . ' ' . $font_size . ' ' . $radius . ' padding-top: 0 !important; padding-bottom: 0 !important;"></span>';
        }
        
        if( $hover_btn_type === 'hover_wapasha' ){
            $output .= '<span class="wapasha-text ' . $css_class . '" style="' . $spacing . ' ' . $font_size . ' ' . $radius . ' padding-top: 0 !important; padding-bottom: 0 !important; border: ' . $border_width . 'px solid ' . $btn_border_color_hover . ';"></span>';
        }
        
        if( $btn_icon_type === 'icon_vis_left' ){
            $output .= '        <i class="icon-left ' . $iconClass . '"></i>';
        }
        
        if( $btn_icon_type === 'icon_hov_left' ){
            $output .= '        <i class="icon-left hover-icon ' . $iconClass . '"></i>';
        }
        
        $output .= '<span class="main-text ' . $hover_btn_type . '">' . wp_kses_post( $btn_text ) . '</span>';
        
        if( $btn_icon_type === 'icon_vis_right' ){
            $output .= '        <i class="icon-right ' . $iconClass . '"></i>';
        }
        
        if( $btn_icon_type === 'icon_hov_right' ){
            $output .= '        <i class="icon-right hover-icon ' . $iconClass . '"></i>';
        }
        
        $output .= '    </a>';
        
        $output .= '    <div class="clearfix"></div>';
        $output .= '</div>';

        return $output;
    }  
    
    // Print footer script
    public static function shindiri_custom_button(){
        
        global $shindiri_custom_button;
        
        if ( isset( $shindiri_custom_button ) && ! empty( $shindiri_custom_button ) ) {
            echo $shindiri_custom_button;
        }
    }
    
    /**
     * Return html from do_shortcode function with parameters from theme options
     * 
     * @return string
     */
    public static function do_shortcode() {
        
        // Theme options
        global $pbtheme_data;
        
        // Set shortcode values
        $atts = array(
            'btn_text' => isset( $pbtheme_data['sellocity_btn_text'] ) ? $pbtheme_data['sellocity_btn_text'] : esc_html__('BUTTON', 'pbtheme'),
            'hover_btn_type' => isset( $pbtheme_data['sellocity_btn_hover_btn_type'] ) ? $pbtheme_data['sellocity_btn_hover_btn_type'] : 'hover_none',
            'css_class' => isset( $pbtheme_data['sellocity_btn_css_class'] ) ? $pbtheme_data['sellocity_btn_css_class'] : 'sellocity-custom-btn-medium',
            'btn_text_size' => isset( $pbtheme_data['sellocity_btn_text_size'] ) ? $pbtheme_data['sellocity_btn_text_size'] : 'btn_smll',
            'font_size_p' => isset( $pbtheme_data['sellocity_btn_font_size_p'] ) ? $pbtheme_data['sellocity_btn_font_size_p'] : '',
            'letter_spacing' => isset( $pbtheme_data['sellocity_btn_letter_spacing'] ) ? $pbtheme_data['sellocity_btn_letter_spacing'] : '2',
            'btn_type' => isset( $pbtheme_data['sellocity_btn_type'] ) ? $pbtheme_data['sellocity_btn_type'] : 'btn_crcl',
            'btn_link_url' => isset( $pbtheme_data['sellocity_btn_link_url'] ) ? $pbtheme_data['sellocity_btn_link_url'] : '#',
            'btn_icon_type' => isset( $pbtheme_data['sellocity_btn_icon_type'] ) ? $pbtheme_data['sellocity_btn_icon_type'] : 'icon_no',
            'icon_class' => isset( $pbtheme_data['sellocity_btn_icon_class'] ) && $pbtheme_data['sellocity_btn_icon_class'] !== 'none' ? 'fa fa-' . str_replace( '.png', '', $pbtheme_data['sellocity_btn_icon_class']) : '',
            'btn_back_color' => isset( $pbtheme_data['sellocity_btn_back_color'] ) ? $pbtheme_data['sellocity_btn_back_color'] : '#34495e',
            'btn_hover_color' => isset( $pbtheme_data['sellocity_btn_hover_color'] ) ? $pbtheme_data['sellocity_btn_hover_color'] : '#182a38',
            'btn_text_color' => isset( $pbtheme_data['sellocity_btn_text_color'] ) ? $pbtheme_data['sellocity_btn_text_color'] : '#ffffff',
            'btn_text_color_hover' => isset( $pbtheme_data['sellocity_btn_text_color_hover'] ) ? $pbtheme_data['sellocity_btn_text_color_hover'] : '#ffffff',
            'border_width' => isset( $pbtheme_data['sellocity_btn_border_width'] ) ? $pbtheme_data['sellocity_btn_border_width'] : '1',
            'btn_border_color' => isset( $pbtheme_data['sellocity_btn_border_color'] ) ? $pbtheme_data['sellocity_btn_border_color'] : '',
            'btn_border_color_hover' => isset( $pbtheme_data['sellocity_btn_border_color_hover'] ) ? $pbtheme_data['sellocity_btn_border_color_hover'] : '',
        );
        
        // Build shortcode tag
        $shortcode = '[' . self::$shortcode_slug;

        foreach ( $atts as $key => $value ) {
           $shortcode .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"'; 
        }
        
        $shortcode .= ']';
        
        return do_shortcode( $shortcode );
        
    }
    
    /**
     * Url to the class folder without end backslash
     * 
     * @return string
     */
    public static function get_root_url() {
        return get_template_directory_uri() . '/lib/custom_button';
    }
    
    /**
     * Path to the class folder without end backslash
     * 
     * @return string
     */
    public static function get_root_path() {
        return get_template_directory() . '/lib/custom_button';
    }
    
    public function add_scripts() {
    
        wp_enqueue_style( 'sellocity-custom-button', self::get_root_url() . '/assets/custom_button.css' );

    }
    
    /**
     * Return array of name => image for admin theme option select
     * 
     * @return array
     */
    public static function get_fontAwsome_png_array() {
        
        /**
         * Icons source
         * https://github.com/encharm/Font-Awesome-SVG-PNG
         */
        
        $png_array = array();
        $png_icons = array();
        $png_icons = glob( self::get_root_path() . '/assets/images/fontAwsome/*' );
        $png_icons = array_filter( $png_icons, 'is_file' );
        $png_icons = array_map( 'basename', $png_icons );
        $png_icons = array_combine( $png_icons, $png_icons );
        
        if ( empty( $png_icons ) ) {
            return $png_array;
        }
        
        $png_icons =  array( 'none' => 'none' ) + $png_icons;
        
        foreach ( $png_icons as $icon ) {
            $key = ucfirst( str_replace( array( '.png', '-' ), array( '', ' ' ), $icon ) );
            $png_array[$icon] = $key;
        }
        
        return $png_array;
        
    }
    
    /**
     * Check if button active in theme options
     * 
     * @global array $pbtheme_data
     * @return boolean
     */
    public static function is_button_active() {
        
        global $pbtheme_data;
        
        if ( $pbtheme_data['sellocity_btn_enabled'] ) {
            return true;
        }
        
        return false;
        
    }

    /**
     * Add body class if button activated in theme options
     * 
     * @global array $pbtheme_data
     * @param array $classes
     * @return array
     */
    public function body_classes( $classes ) {
        
        global $pbtheme_data;
        
        if ( self::is_button_active() ) {
            $classes[] = $this->body_class;
        }
        return $classes;
    }
    
}
new Sellocity_Custom_Button();

/**
 * Add button in header after menu
 * 
 * @param type $items
 * @param type $args
 * @return string
 */
function pbtheme_add_custom_button_to_menu( $items, $args ) {
    
    /**
     * If any other menu than pbtheme_primary skip
     */
    if ( isset( $args->theme_location ) && $args->theme_location !== 'pbtheme_primary' ) {
        return $items;
    }
    
    if ( class_exists( 'Sellocity_Custom_Button' ) && Sellocity_Custom_Button::is_button_active() ) {
        $items .= '<li id="menu-custom-button">';
        $items .= Sellocity_Custom_Button::do_shortcode();
        $items .= '</li>';
    }

    return $items;
}
add_filter('wp_nav_menu_items', 'pbtheme_add_custom_button_to_menu', 5, 2);