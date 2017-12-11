<?php

/**
 * Manipulate header link hover effects
 */
class Pbtheme_Header_Link_Hover implements SplObserver {
    
    private $color;
    private $hover_effect;
    private $effect_color;
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
        
        $this->color = isset( $pbtheme_data[ 'header_link_hover_color' ] ) ? $pbtheme_data[ 'header_link_hover_color' ] : false;
        // Menu hover effects grouped here because they don't apply only to menu but to the other elements
        $this->hover_effect = isset( $pbtheme_data[ 'header_menu_hover_effect' ] ) ? $pbtheme_data[ 'header_menu_hover_effect' ] : false;
        $this->effect_color = isset( $pbtheme_data[ 'header_menu_hover_effect_color' ] ) ? $pbtheme_data[ 'header_menu_hover_effect_color' ] : false;
        
    }
    
    /**
     * Return css to apply
     * 
     * @return string
     */
    private function get_css() {
        
        $css = '';

        /**
         * Also responsive menu is affected
         */
        if ( ! empty( $this->color ) ) {
            $css .= '#div_header_menu ul li:not(#menu-custom-button) a:hover {'
                    . 'color:' . esc_attr( $this->color ) . ';'
                . '}'
                // Responsive menu two icons in line with logo
                . '.div_responsive_icons a:hover {'
                    . 'color:' . esc_attr( $this->color ) . ';'
                    . 'border-color: ' . esc_attr( $this->color ) . ';'
                . '}';
        }
        
        /**
         * Hover effects include only menu elements
         * Not responsive menu
         */
        switch ( $this->hover_effect ) {
            case 'baseline':
                $css .= '.menu_wrapper:not(.pbtheme-responsive-menu) > ul > li:not(.menu-item-has-children) > a:after {
                            content: "";
                            width: 100%;
                            height: 4px;
                            background: ' . esc_attr( $this->effect_color ) . ';
                            display: block;
                            visibility: hidden;
                            opacity: 0;
                            transition: all 0.3s;
                        }';
                $css .= '.menu_wrapper:not(.pbtheme-responsive-menu) > ul > li:not(.menu-item-has-children) > a:hover:after {
                            visibility: visible;
                            opacity: 1;
                        }';

                break;
            
            case 'background':
                $css .= '.menu_wrapper:not(.pbtheme-responsive-menu) > ul > li > a:hover {
                            background-color: ' . esc_url( $this->effect_color ) . ';);
                        }
                        .header_wrapper:not(.pbtheme-responsive-menu) .menu_wrapper > ul > li#search-trigger > a {
                            top: 0;
                        }
                        .header_wrapper.layout-small-right:not(.pbtheme-responsive-menu).layout-small-right .menu_wrapper > ul > li#widgets-trigger:last-child > a {
                            padding-right: 8px;
                        }
                        .header_wrapper.layout-small-left:not(.pbtheme-responsive-menu) .menu_wrapper > ul > li#widgets-trigger:last-child > a {
                            top: 0;
                        }
                        .header_wrapper.layout-small-left:not(.pbtheme-responsive-menu) .menu_wrapper > ul > li:first-child > a {
                            padding-left: 8px;
                        }';
                break;

            default:
                break;
        };
        
        return $css;

    }
    
}