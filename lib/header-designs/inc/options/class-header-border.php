<?php

/**
 * Manipulate header border bottom
 */
class Pbtheme_Header_Border implements SplObserver {
    
    private $color;
    /**
     * Borders enabled / disabled
     * @var int
     */
    private $state;
    private $css;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->set_vars();
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
     * Get user selected data
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_vars() {
        
        global $pbtheme_data;
        
        $this->state = isset( $pbtheme_data[ 'header_border' ] ) ? $pbtheme_data[ 'header_border' ] : 1;
        $this->color = isset( $pbtheme_data[ 'header_border_color' ] ) ? $pbtheme_data[ 'header_border_color' ] : false;
        
    }
    
    /**
     * Return css to apply color
     * 
     * @return string
     */
    private function set_css() {
        
        $css = '';

        if ( empty( $this->state ) ) {
            
            $css .=  '.header_wrapper {  
                border: none;
            }';
            
            $css .=  '.header_holder {  
                border-bottom: none;
            }';
            
        }

        if ( ! empty( $this->state ) && ! empty( $this->color ) ) {

            $css .= '.header_wrapper,'
            // Enabled on header layout: news-central
            . '.header_wrapper .pbtheme_dark_border.header_pbtheme_bottom,'
            . '.header_wrapper .header_holder' 
            . '{border-color:' . esc_attr( $this->color ) . ' !important;}';
        
        }
        
        return $css;

    }
    
}