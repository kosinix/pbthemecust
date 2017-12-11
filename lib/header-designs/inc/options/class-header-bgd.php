<?php

/**
 * Manipulate header background color
 */
class Pbtheme_Header_Bgd implements SplObserver {
    
    /**
     * User selection between color or pattern background
     * @var string
     */
    private $type;
    private $color;
    private $opacity;
    /**
     * Image url
     * @var string
     */
    private $pattern;
    private $css;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->type = $this->set_type();
        $this->color = $this->set_color();
        $this->opacity = $this->set_opacity();
        $this->pattern = $this->set_pattern();
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
     * Get user selection between pattern or color
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_type() {
        
        global $pbtheme_data;
        
        return isset( $pbtheme_data[ 'header_bgd_color_pattern' ] ) ? $pbtheme_data[ 'header_bgd_color_pattern' ] : 'color';
        
    }
    
    /**
     * Get user selected color
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_color() {
        
        global $pbtheme_data;
        
        return isset( $pbtheme_data[ 'theme_color_header' ] ) ? $pbtheme_data[ 'theme_color_header' ] : false;
        
    }
    
    /**
     * Get user selected opacity
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_opacity() {
        
        global $pbtheme_data;
        
        return isset( $pbtheme_data[ 'header_bgd_opacity' ] ) ? $pbtheme_data[ 'header_bgd_opacity' ] : false;
        
    }
    
    /**
     * Get user selected pattern
     * 
     * @global string $pbtheme_data
     * @return string
     */
    private function set_pattern() {
        
        global $pbtheme_data;
        
        return isset( $pbtheme_data[ 'header_bgd_pattern' ] ) ? $pbtheme_data[ 'header_bgd_pattern' ] : false;
        
    }
    
    /**
     * Return css to apply color
     * 
     * @return string
     */
    private function set_css() {
        
        if ( $this->type === 'pattern' ) {
            
            if ( empty( $this->pattern ) ) {
                return '';
            }

            return  '.header_main_nav {'
                . 'background-image: url("' . esc_url( $this->pattern ) . '");'
                . 'background-position: top;'
                . 'background-repeat: repeat;'
                . 'background-size: contain;'
            . '}'
            . '.menu_wrapper > ul > li.has_children > a:after {'
                . 'display: none;'
            . '}';
            
        } 
        
        if ( $this->type === 'color' ) {
        
            if ( empty( $this->color ) ) {
                return '';
            }
            
            $opacity =  floatval( $this->opacity / 10 );

            if ( $opacity != 1 ) {
                $this->color = $this->hextorgba( $this->color, $opacity );
            }

            return  '.header_main_nav{background-color:' . esc_attr( $this->color ) . ';}'
                    . '.header_wrapper {background-color: transparent;}'
                    . '.menu_wrapper > ul > li.has_children.hovered > a:after {border-top-color:' . esc_attr( $this->color ) . ';}';
        
        }
    }
    
    /**
    * Convert hex to rgba or rgb color
    *
    * @param string    $hex   Hex color with #
    * @param string/int    $alpha   Desired alpha
    * 
    * @return  string  Return rgba / rgb color from hex
    */
    private function hextorgba( $hex, $alpha = false ) {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 6) {
            $rgb['r'] = hexdec(substr($hex, 0, 2));
            $rgb['g'] = hexdec(substr($hex, 2, 2));
            $rgb['b'] = hexdec(substr($hex, 4, 2));
        } else if (strlen($hex) == 3) {
            $rgb['r'] = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $rgb['g'] = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $rgb['b'] = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $rgb['r'] = '0';
            $rgb['g'] = '0';
            $rgb['b'] = '0';
        }
        if ($alpha !== false) {
            $rgb['a'] = $alpha;
        }
        return implode('', array_keys($rgb)) . '(' . implode(', ', $rgb) . ')';
    }
    
}