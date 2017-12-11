<?php

/**
 * Class responsible for color field in widget admin form
 */
class Predic_Widget_Color_Field extends Predic_Widget_Form_Field {
    
    private $id;
    private $name;
    private $label;
    private $alpha;
    private $default;
    private $value;
    
    /**
     * Return field for admin form
     * @param array $atts User passed config array
     * @param string $id Generated field id
     * @param string $name Generated form field name
     * @param mixed $value Field value. Before save the value is NULL
     */
    public function __construct( $atts, $id, $name, $value ) {
        $this->id = $id; // Id
        $this->name = $name; // Name
        $this->label = isset( $atts['label'] ) && !empty( $atts['label'] ) ? $atts['label'] : ''; // Label
        $this->alpha = isset( $atts['alpha'] ) && $atts['alpha'] === true ? $atts['alpha'] : false; // Alpha
        $this->default = isset( $atts['default'] ) && !empty( $atts['default'] ) ? $atts['default'] : ''; // Default
        $this->value = $value; // Value
    }
    
    
    public function field() {
        
        $html = '';
        
        if ( empty( $this->name ) ) {
            return $html;
        }
        
        if ( $this->label ) {
            $html .= '<label for="' . esc_attr( $this->id ) . '">' . strip_tags( $this->label ) . '</label>';
            $html .= '<br />';
        }
        
        // If value NULL (only first time before save) set empty string to init color picker
        $value = NULL === $this->value ? '' : $this->value;
        
        $html .= '<input class="widefat predic-widget-color__field" '
                . 'data-id="' . esc_js( $this->id ) . '" '
                . 'id="' . esc_attr( $this->id ) . '" '
                . 'name="' . esc_attr( $this->name ) . '" '
                . 'type="text" '
                . 'value="' . esc_attr( $value ) . '" ';
 
        if ( !empty( $this->default ) ) {
            $html .= 'data-default-color="' . esc_js( $this->default ) . '" ';
        }
        
        // User selection do enable / disable alpha
        if ( $this->alpha ) {
            $html .= 'data-alpha="true" ';
        } else {
            $html .= 'data-alpha="false" ';
        }
        
        $html .= '/>';
        
        $this->admin_scripts();
        
        return $html;
    }
    
    /**
     * Add script
     * 
     * We are not using admin_enqueue_scripts hook as it is already too late to hook in
     */
    private function admin_scripts() {
   
        // Default scripts
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' ); 
        
        /**
         * Overwrite Automattic Iris for enabled Alpha Channel in wpColorPicker
         * @see https://github.com/23r9i0/wp-color-picker-alpha
         */
        if ( $this->alpha ) {
            wp_enqueue_script( 'wp-color-picker-alpha', PREDIC_WIDGET_ASSETS_URL . '/vendor/wp-color-picker-alpha/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), false, true );
        }
        
        // Color picker init
        wp_enqueue_script( 'predic-widget-color-field', PREDIC_WIDGET_ASSETS_URL . '/js/fields/color-field.js', array( 'wp-color-picker' ), false, true );
        
    }
    
}
