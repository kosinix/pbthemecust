<?php

/**
 * Manipulate top bar font size
 */
class Pbtheme_Header_Topbar_Font_Size implements SplObserver {
    
    private $size;
    private $css;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->size = $this->set_font_size();
        $this->css = $this->set_css();
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
     * Get user selected font size
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_font_size() {
        
        global $pbtheme_data;
        
        return isset( $pbtheme_data[ 'header_topbar_font_size' ] ) ? $pbtheme_data[ 'header_topbar_font_size' ] : false;
        
    }
    
    /**
     * Return css to apply font size
     * 
     * @return string
     */
    private function set_css() {

        if ( empty( $this->size ) ) {
            return '';
        }

        return  '.pbtheme_top .pbtheme_top_right > .a-inherit:not(.element-woo-cart), '
        . '.pbtheme_top .pbtheme_top_left > .a-inherit:not(.element-woo-cart),'
        . '.pbtheme_top .pbtheme_top_right > .a-inherit:not(.element-woo-cart) *,'
        . '.pbtheme_top .pbtheme_top_left > .a-inherit:not(.element-woo-cart) *,'
        . '.pbtheme_top .pbtheme_top_right .cart-contents *,'
        . '.pbtheme_top .pbtheme_top_left .cart-contents *'
        . '{font-size:' . esc_attr( $this->size ) . 'px;}'
        . '.pbtheme_top .a-inherit .woo_shopping_cart i'
        . '{vertical-align: initial; line-height: ' . esc_attr( $this->size ) . 'px;}';

    }
    
}