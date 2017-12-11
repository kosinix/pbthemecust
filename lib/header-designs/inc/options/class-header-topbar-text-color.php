<?php

/**
 * Manipulate top bar text color
 */
class Pbtheme_Header_Topbar_Text_Color implements SplObserver {
    
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
        
        return isset( $pbtheme_data[ 'header_topbar_text_color' ] ) ? $pbtheme_data[ 'header_topbar_text_color' ] : false;
        
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

        return  '.pbtheme_top .pbtheme_top_right, '
        . '.pbtheme_top .pbtheme_top_left,'
        . '.pbtheme_top .cart-contents span,'
        . '.pbtheme_top .cart-contents i'
        . '{color:' . esc_attr( $this->color ) . ';}';

    }
    
}