<?php

/**
 * Manipulate header link color
 */
class Pbtheme_Header_Link_Color implements SplObserver {
    
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
        
        return isset( $pbtheme_data[ 'header_link_color' ] ) ? $pbtheme_data[ 'header_link_color' ] : false;
        
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

        /**
         * Also responsive menu is affected
         */
        return  '#div_header_menu ul li:not(#menu-custom-button) a {'
                . 'color:' . esc_attr( $this->color ) . ';'
            . '}'
            // Responsive menu icons and submenu icons
            . '.div_responsive_icons a,'
            . '.div_responsive #div_header_menu li.has_children > a:after, .div_responsive #div_header_menu li.menu-item-has-children > a:after {'
                . 'color:' . esc_attr( $this->color ) . ';'
                . 'border-color: ' . esc_attr( $this->color ) . ';'
            . '}';

    }
    
}