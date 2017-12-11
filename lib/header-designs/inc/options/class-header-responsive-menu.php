<?php

/**
 * Manipulate header responsive menu
 */
class Pbtheme_Header_Responsive_Menu implements SplObserver {
    
    private $icon_color;
    private $icon_hover_color;
    private $line_color;
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
     * Get user selected data
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_vars() {
        
        global $pbtheme_data;
        
        $this->icon_color = isset( $pbtheme_data[ 'header_resp_menu_icon_color' ] ) ? $pbtheme_data[ 'header_resp_menu_icon_color' ] : false;
        $this->icon_hover_color = isset( $pbtheme_data[ 'header_resp_menu_icon_hover_color' ] ) ? $pbtheme_data[ 'header_resp_menu_icon_hover_color' ] : false;
        $this->line_color = isset( $pbtheme_data[ 'header_resp_menu_line_color' ] ) ? $pbtheme_data[ 'header_resp_menu_line_color' ] : false;
        
    }
    
    /**
     * Return css to apply
     * 
     * @return string
     */
    private function get_css() {
        
        $css = '';

        if ( ! empty( $this->icon_color ) ) {
            $css .= '.div_responsive_icons a,'
            . '.div_responsive #div_header_menu li.has_children > a:after, .div_responsive #div_header_menu li.menu-item-has-children > a:after {'
                . 'color:' . esc_attr( $this->icon_color ) . ';'
                . 'border-color: ' . esc_attr( $this->icon_color ) . ';'
            . '}';
        }
        
        if ( ! empty( $this->icon_hover_color ) ) {
            $css .= '.div_responsive_icons a:hover {'
                . 'color:' . esc_attr( $this->icon_hover_color ) . ';'
                . 'border-color: ' . esc_attr( $this->icon_hover_color ) . ';'
            . '}';
        }
        
        if ( ! empty( $this->line_color ) ) {
            $css .= '.div_responsive #div_header_menu,'
            . '.div_responsive #div_header_menu>ul>li {'
                . 'border-color: ' . esc_attr( $this->line_color ) . ';'
            . '}';
        }

        return $css;

    }
    
}