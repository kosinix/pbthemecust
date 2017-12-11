<?php

/**
 * Manipulate top bar border bottom
 */
class Pbtheme_Header_Topbar_Border implements SplObserver {
    
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
        
        $this->state = isset( $pbtheme_data[ 'header_topbar_border' ] ) ? $pbtheme_data[ 'header_topbar_border' ] : 1;
        $this->color = isset( $pbtheme_data[ 'header_topbar_border_color' ] ) ? $pbtheme_data[ 'header_topbar_border_color' ] : false;
        
    }
    
    /**
     * Return css to apply color
     * 
     * @return string
     */
    private function set_css() {
        
        $css = '';
        
        if ( empty( $this->state ) ) {
            
            $css .= '.header_wrapper .pbtheme_dark_border:not(.header_pbtheme_bottom)'
            . '{border: none;}';
            
            $css .=  '.header_holder {  
                border-top: none;
            }';
            
        }

        if ( ! empty( $this->state ) && ! empty( $this->color ) ) {

            $css .= '.header_wrapper .pbtheme_dark_border:not(.header_pbtheme_bottom)'
            . '{border-color:' . esc_attr( $this->color ) . ' !important;}';
            
            $css .=  '.header_wrapper.layout-news-central:not(.sticky-header) .header_holder {  
                border-top: 1px solid ' . esc_attr( $this->color ) . ' !important;
            }';

        }
        
        return $css;

    }
    
}