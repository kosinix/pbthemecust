<?php

/**
 * Manipulate top bar link hover color
 */
class Pbtheme_Header_Topbar_Link_Hover_Color implements SplObserver {
    
    private $color;
    private $css;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->color = $this->set_color();
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
     * Get user selected color
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_color() {
        
        global $pbtheme_data;
        
        return isset( $pbtheme_data[ 'header_topbar_link_hover_color' ] ) ? $pbtheme_data[ 'header_topbar_link_hover_color' ] : false;
        
    }
    
    /**
     * Return css to apply color
     * 
     * @return string
     */
    private function set_css() {

        if ( empty( $this->color ) ) {
            return '';
        }

        return  '.pbtheme_top .pbtheme_top_right > .a-inherit:not(.element-woo-cart) > a:hover,'
        . '.pbtheme_top .pbtheme_top_left > .a-inherit:not(.element-woo-cart) > a:hover,'
        . '.pbtheme_top .element-menu ul li a:hover'
        . '{color:' . esc_attr( $this->color ) . ';}';

    }
    
}