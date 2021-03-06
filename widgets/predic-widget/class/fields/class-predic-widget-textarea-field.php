<?php

/**
 * Class responsible for widget admin form textarea field
 */
class Predic_Widget_Textarea_Field extends Predic_Widget_Form_Field {
    
    private $id;
    private $name;
    private $label;
    private $placeholder;
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
        $this->placeholder = isset( $atts['placeholder'] ) && !empty( $atts['placeholder'] ) ? $atts['placeholder'] : ''; // Placeholder
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
        }

        // If value NULL (only first time before save) set default value if passed
        $value = NULL === $this->value && '' !== $this->default ? $this->default : $this->value;
        
        $html .= '<textarea class="widefat" '
                . 'id="' . esc_attr( $this->id ) . '" '
                . 'name="' . esc_attr( $this->name ) . '" '
                . 'type="textarea" '
                . 'rows="5" ';
        
        if ( !empty( $this->placeholder ) ) {
            $html .= 'placeholder="' . esc_attr( $this->placeholder ) . '" ';
        }
        
        $html .= '>'
                . wp_kses_post( $value )
                . '</textarea>';
        
        return $html;
    }
    
}
