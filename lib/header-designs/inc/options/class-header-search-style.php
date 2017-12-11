<?php

/**
 * Manipulate header search style
 */
class Pbtheme_Header_Search_Style implements SplObserver {
    
    private $color;
    private $default_text_color;
    private $active_text_color;
    private $icon_color;
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
        
        $this->color = isset( $pbtheme_data[ 'header_search_color' ] ) ? $pbtheme_data[ 'header_search_color' ] : false;
        $this->icon_color = isset( $pbtheme_data[ 'header_search_icon_color' ] ) ? $pbtheme_data[ 'header_search_icon_color' ] : false;
        $this->default_text_color = isset( $pbtheme_data[ 'header_search_def_text_color' ] ) ? $pbtheme_data[ 'header_search_def_text_color' ] : false;
        $this->active_text_color = isset( $pbtheme_data[ 'header_search_active_text_color' ] ) ? $pbtheme_data[ 'header_search_active_text_color' ] : false;
        
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
            $css .= '#div_header_menu #search-trigger  .pbtheme_search_inline,'
                . '#div_header_menu #search-trigger  .pbtheme_search'
                . '{border: 1px solid' . esc_attr( $this->color ) . ' !important;}'
                . '#div_header_menu #search-trigger  .pbtheme_search_inline form button,'
                . '#div_header_menu #search-trigger  .pbtheme_search form button'
                . '{background-color: ' . esc_attr( $this->color ) . ' !important;}';
        }
        
        if ( ! empty( $this->icon_color ) ) {
            $css .= '#div_header_menu .pbtheme_search_inline form button i,
                    #div_header_menu .pbtheme_search form button i{
                        color: ' . esc_attr( $this->icon_color ) . ';
                    }';
        }
        
        if ( ! empty( $this->default_text_color ) ) {
            $css .= '#div_header_menu .pbtheme_search_inline form input::-webkit-input-placeholder,
                    #div_header_menu .pbtheme_search form input::-webkit-input-placeholder {
                        color: ' . esc_attr( $this->default_text_color ) . ';
                    }
                    #div_header_menu .pbtheme_search_inline form input:-moz-placeholder,
                    #div_header_menu .pbtheme_search form input:-moz-placeholder { /* Firefox 18- */
                        color: ' . esc_attr( $this->default_text_color ) . ';  
                    }
                    #div_header_menu .pbtheme_search_inline form input::-moz-placeholder,
                    #div_header_menu .pbtheme_search form input::-moz-placeholder {  /* Firefox 19+ */
                        color: ' . esc_attr( $this->default_text_color ) . ';  
                    }
                    #div_header_menu .pbtheme_search_inline form input:-ms-input-placeholder,
                    #div_header_menu .pbtheme_search form input:-ms-input-placeholder {  
                        color: ' . esc_attr( $this->default_text_color ) . ';  
                    }';
        }
        
        if ( ! empty( $this->active_text_color ) ) {
            $css .= '#div_header_menu .pbtheme_search_inline input,
                    #div_header_menu .pbtheme_search form input {
                        color: ' . esc_attr( $this->active_text_color ) . ';
                    }';
        }
        
        return $css;

    }
    
}