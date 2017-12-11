<?php

/**
 * Manipulate with header menu style
 */
class Pbtheme_Header_Menu_Style implements SplObserver {
    
    private $height;
    private $font_size;
    private $distance_links;
    private $subitem_bgd;
    private $subitem_color;
    private $subitem_hover_color;
    private $css;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->set_vars();
        $this->css = $this->get_css();
    }

    /**
     * Update event - add css to dynamic css
     * 
     * @param \SplSubject $subject
     */
    public function update(SplSubject $subject) {
        
        if ( ! empty( $this->css ) ) {
            echo $this->css;
        }

    }
    
    /**
     * Set class vars
     * 
     * @global string $pbtheme_data
     */
    private function set_vars() {
        
        global $pbtheme_data;
        
        $this->height = isset( $pbtheme_data[ 'header_menu_height' ] ) ? $pbtheme_data[ 'header_menu_height' ] : false;
        $this->font_size = isset( $pbtheme_data[ 'header_menu_font_size' ] ) ? $pbtheme_data[ 'header_menu_font_size' ] : false;
        $this->distance_links = isset( $pbtheme_data[ 'header_menu_distance_links' ] ) ? $pbtheme_data[ 'header_menu_distance_links' ] : false;
        $this->subitem_bgd = isset( $pbtheme_data[ 'header_menu_subitem_bgd_color' ] ) ? $pbtheme_data[ 'header_menu_subitem_bgd_color' ] : false;
        $this->subitem_color = isset( $pbtheme_data[ 'header_menu_subitem_color' ] ) ? $pbtheme_data[ 'header_menu_subitem_color' ] : false;
        $this->subitem_hover_color = isset( $pbtheme_data[ 'header_menu_subitem_hover_color' ] ) ? $pbtheme_data[ 'header_menu_subitem_hover_color' ] : false;
        
    }
    
    /**
     * Return css to apply height
     * 
     * @return string
     */
    private function get_css() {
        
        $css = '';

        if ( $this->height === 'medium' ) {
            $css .= '.header_wrapper:not(.sticky-header) .menu_wrapper > ul > li > a {
                        padding: 5px 8px;
                    }
                    .header_wrapper:not(.sticky-header) .menu_wrapper > ul > li {
                        padding: 27px 8px;
                    }';
        }
        
        if ( ! empty( $this->font_size ) ) {
            $css .= '.header_wrapper:not(.sticky-header) .menu_wrapper > ul > li.menu-item > a,
                    .header_wrapper:not(.sticky-header) .menu_wrapper > ul > li#search-trigger > a > .divicon-search,
                    .header_wrapper:not(.sticky-header) .menu_wrapper > ul > li#widgets-trigger i {
                    font-size: ' . esc_attr( $this->font_size ) . 'px;
                    line-height: 1.7;
                }
                .header_wrapper:not(.sticky-header) .menu_wrapper > ul > li#widgets-trigger i {
                    vertical-align: middle;
                }';
        }
        
        if ( ! empty( $this->distance_links ) ) {
            $css .= '.header_wrapper:not(.sticky-header) .menu_wrapper > ul > li {
                        margin-left: ' . esc_attr( $this->distance_links ) . 'px !important;
                    }
                    .header_wrapper.layout-small-left:not(.sticky-header) .menu_wrapper > ul > li {
                        margin-left: 0 !important;
                        margin-right: ' . esc_attr( $this->distance_links ) . 'px !important;
                    }
                    .pbtheme-primary-menu-widgets-trigger #div_header_menu li#search-trigger {
                        width: calc(100% - 40px - ' . esc_attr( $this->distance_links ) . 'px);
                    }';
        }
        
        if ( ! empty( $this->subitem_bgd ) ) {
            $css .= '.header_wrapper .menu_wrapper:not(.pbtheme-responsive-menu) > ul > li > ul li {
                        background-color: ' . esc_attr( $this->subitem_bgd ) . ';
                    }';
        }
        
        if ( ! empty( $this->subitem_color ) ) {
            $css .= '.header_wrapper .menu_wrapper:not(.pbtheme-responsive-menu) > ul > li > ul li a {
                        color: ' . esc_attr( $this->subitem_color ) . ' !important;
                    }';
        }
        
        if ( ! empty( $this->subitem_hover_color ) ) {
            $css .= '.header_wrapper .menu_wrapper:not(.pbtheme-responsive-menu) > ul > li > ul li a:hover {
                        color: ' . esc_attr( $this->subitem_hover_color ) . ' !important;
                    }';
        }

        // Return created css
        return $css;

    }
    
}