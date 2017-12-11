<?php

/**
 * Manipulate header top bar widget area
 */
class Pbtheme_Topbar_Widget_Area implements SplObserver {
    
    private $color;
    private $css;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->set_data();
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
     * Get user selection
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_data() {
        
        global $pbtheme_data;
        
        $this->color = isset( $pbtheme_data[ 'widgets_style_topbar_warea_bgd' ] ) ? $pbtheme_data[ 'widgets_style_topbar_warea_bgd' ] : false;
        $this->css = $this->set_css();
        
    }
    
    /**
     * Return custom css
     * 
     * @return string
     */
    private function set_css() {
        
        $css = '';

        if ( ! empty( $this->color ) ) {
            $css .= '.pbtheme_header_widgets{ background-color: ' . esc_attr( $this->color ) . ';}';
        }

        return $css;
    }
}