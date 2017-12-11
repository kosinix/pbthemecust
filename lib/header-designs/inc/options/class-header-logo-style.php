<?php

/**
 * Manipulate header logo
 */
class Pbtheme_Header_Logo_Style implements SplObserver {
    
    private $width;
    private $overflow;
    private $css;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->width = $this->set_width();
        $this->overflow = $this->set_overflow();
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
     * Get user selected data for width option
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_width() {
        
        global $pbtheme_data;
        
        return isset( $pbtheme_data[ 'header_logo_width' ] ) ? $pbtheme_data[ 'header_logo_width' ] : false;
        
    }
    
    /**
     * Get user selected data for overflow option
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_overflow() {
        
        global $pbtheme_data;
        
        return isset( $pbtheme_data[ 'header_logo_overflow' ] ) ? $pbtheme_data[ 'header_logo_overflow' ] : false;
        
    }
    
    /**
     * Return css to apply changes
     * 
     * @return string
     */
    private function set_css() {
        
        $css = '';

        if ( ! empty( $this->width ) ) {
            
            // Left small and right small layout
            $css .=  '.header_wrapper:not(.sticky-header) .menu_wrapper:not(.pbtheme-responsive-menu) .div_mainlogoimg {  
                max-width: ' . esc_attr( $this->width ) . 'px;
                width: 100%;
                height: auto !important;
                top: 50%;
                transform: translate(0, -50%);
                -webkit-transform: translate(0, -50%);
                -moz-transform: translate(0, -50%);
            }';
            
            // Layout central news
            $css .=  'body:not(.div_responsive) .header_wrapper.layout-news-central:not(.sticky-header) .div_mainlogoimg {  
                max-width: ' . esc_attr( $this->width ) . 'px;
            }';
        
        }
        
        if ( ! empty( $this->overflow ) ) {
            
            // Left small and right small layout
            $css .=  '.header_wrapper:not(.sticky-header) .menu_wrapper:not(.pbtheme-responsive-menu) .div_mainlogoimg {  
                width: auto;
                top: 0;
                height: auto !important;
                transform: none;
                -moz-transform: none;
                -webkit-transform: none;
            }';
            
            // Layout news central
            $css .=  'body:not(.div_responsive) .header_wrapper.layout-news-central:not(.sticky-header) .header_main_nav:not(.pbtheme_container_max) {  
                position: absolute;
                display: inline-block;
                top: 0;
                left: 50%;
                transform: translate(-50%, 0);
                -webkit-transform: translate(-50%, 0);
                -moz-transform: translate(-50%, 0);
                border: none;
                background: none;
            }';
            
            
            
            $css .=  'body:not(.div_responsive) .header_wrapper.layout-news-central:not(.sticky-header) .header_main_nav .header_holder {  
                position: relative;
                padding: 0;
                border: none;
            }';
            
            $css .=  'body:not(.div_responsive) .header_wrapper.layout-news-central:not(.sticky-header) .header_main_nav .header_holder .logo{  
                position: relative;
            }';
            
            $css .=  'body:not(.div_responsive) .header_wrapper.layout-news-central:not(.sticky-header) .div_mainlogoimg {  
                max-height: none !important;
            }';
            
            // Disable only main menu, not sticky and responsive
            $css .=  'body:not(.div_responsive) .header_wrapper.layout-news-central:not(.sticky-header) #div_header_menu {  
                display: none !important;
            }';
        
        }
        
        // Return created css
        return $css;

    }
    
}