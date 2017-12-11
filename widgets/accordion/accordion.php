<?php

/**
 * Accordion widget class
 */
class PBTheme_Accordion_Widget {
    
    /**
     * Selection between radio and checkbox input type
     * Radio input type - only one tab can be opened
     * Checkboxes input type - multiple tabs can be opened
     * @var string
     */
    private $input_type;
    
    /**
     * Unique widget id used for custom inline css
     * @var string
     */
    private $widget_id;
    
    /**
     * Render widget frontend view
     * 
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance The settings for the particular instance of the widget.
     */
    public function render_view( $args, $instance ) {
        
        $widget_title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
        
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        
        if ( ! empty( $widget_title ) ) {
            echo $args['before_title'] . esc_html( $widget_title ) . $args['after_title'];
        }
        
        /**
         * Define vars
         */
        
        // Unique widget id used for custom inline css
        $this->widget_id = uniqid( 'wpbtheme-accordion-' );
                
        // Open multiple tabs or only one
        $this->input_type = isset( $instance['type'] ) && $instance['type'] === 'one' ? 'radio' : 'checkbox';
        // Border
        $border_css_class = isset( $instance['border'] ) && $instance['border'] === 'off' ? 'wpbtheme-accordion--no-border' : 'wpbtheme-accordion--border';
        
        
        /**
         * Html output start
         */
        
        $tabs = array();
        
        for ( $x = 1; $x <= 4; $x++ ) {
            
            // Set unique id if more than one widget on same page
            $id = uniqid( 'wpbtheme-accordion__tab_' );
            
            // Get user defined headlines and contents
            $label = isset( $instance[ 'headline_' . $x ] ) ? $instance[ 'headline_' . $x ] : '';
            $content = isset( $instance[ 'content_' . $x ] ) ? $instance[ 'content_' . $x ] : '';
            
            if ( empty( $content ) ) {
                continue;
            }

            $tabs[] = '<div class="wpbtheme-accordion__tab">
                            <input id="' . $id . '" type="' . $this->input_type . '" name="tabs" style="color: #ff0000">
                            <label for="' . $id . '">' . strip_tags( $label ) . '</label>
                            <div class="wpbtheme-accordion__content">
                                <p>
                                    ' . wp_kses_post( nl2br( $content ) ) . '
                                </p>
                            </div>
                        </div>';
            
        }
        
        if ( ! empty( $tabs ) ) {
            // Custom colors
            echo $this->inline_css( $instance );
            // Accordion
            printf( '<div id="%2$s" class="wpbtheme-accordion %1$s">', $border_css_class, $this->widget_id );
            echo implode( '', $tabs);
            echo '</div>';
        }

        echo $args['after_widget'];
        
    }
    
    /**
     * Return inline css to include for widget custom colors
     * @param array $instance
     * @return string
     */
    private function inline_css( $instance ) {
        
        $css = '';
        $widget_id = '#' . $this->widget_id;
        
        // Custom label color
        if ( isset( $instance['title_color'] ) && !empty( $instance['title_color'] ) ) {
            $css .= $widget_id . ' label{color:' . esc_attr( $instance['title_color'] ) . ';}';
        }
        
        // Active label color
        if ( isset( $instance['active_title_color'] ) && !empty( $instance['active_title_color'] ) ) {
            $css .= $widget_id . ' input:checked + label,
                    ' . $widget_id . ' input:checked + label::before
                    {color:' . esc_attr( $instance['active_title_color'] ) . ';}';
        }
        
        // Content text color
        if ( isset( $instance['text_color'] ) && !empty( $instance['text_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme-accordion__content p{color:' . esc_attr( $instance['text_color'] ) . ';}';
        }
        
        // Border color
        if ( isset( $instance['border_color'] ) && !empty( $instance['border_color'] ) ) {
            $css .= $widget_id . '.wpbtheme-accordion--border{border-color:' . esc_attr( $instance['border_color'] ) . ';}';
        }
        
        // Background color
        if ( isset( $instance['bgd_color'] ) && !empty( $instance['bgd_color'] ) ) {
            $css .= $widget_id . '.wpbtheme-accordion
                    {background-color:' . esc_attr( $instance['bgd_color'] ) . ';}';
        }
        
        // Active element background color
        if ( isset( $instance['active_bgd_color'] ) && !empty( $instance['active_bgd_color'] ) ) {
            $css .= $widget_id . ' input:checked + label,
                    ' . $widget_id . ' input:checked ~ .wpbtheme-accordion__content
                    {background-color:' . esc_attr( $instance['active_bgd_color'] ) . ';}';
        }
        
        if ( ! empty( $css ) ) {
            return '<style type="text/css">' . $css . '</style>';
        }
        
        return $css;
        
    }
    
    
    
}

/**
 * Map and init accordion widget
 */
$pbtheme_accordion_widget = array(
    
    // Core configuration
    'base_id' => 'pbtheme_accordion_widget',
    'name' => esc_html__('+ PBTheme Accordion', 'pbtheme'),
    'callback' => array( new PBTheme_Accordion_Widget(), 'render_view' ),

    'widget_ops' => array(
        'classname' => 'pbtheme-accordion-widget',
        'description' => esc_html__( 'Display accordion with custom content.', 'pbtheme' ),
        'customize_selective_refresh' => false,
    ),
    
    'form_fields' => array(
        
        'widget_title' => array(
            'type' => 'text',
            'label' => esc_html__( 'Widget title:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter widget title here', 'pbtheme' ),
        ),
        'headline_1' => array(
            'type' => 'text',
            'label' => esc_html__( 'First headline:', 'pbtheme' ),
        ),
        'content_1' => array(
            'type' => 'textarea',
            'label' => esc_html__( 'First content:', 'pbtheme' )
        ),
        'headline_2' => array(
            'type' => 'text',
            'label' => esc_html__( 'Second headline:', 'pbtheme' ),
        ),
        'content_2' => array(
            'type' => 'textarea',
            'label' => esc_html__( 'Second content:', 'pbtheme' )
        ),
        'headline_3' => array(
            'type' => 'text',
            'label' => esc_html__( 'Third headline:', 'pbtheme' ),
        ),
        'content_3' => array(
            'type' => 'textarea',
            'label' => esc_html__( 'Third content:', 'pbtheme' )
        ),
        'headline_4' => array(
            'type' => 'text',
            'label' => esc_html__( 'Fourth headline:', 'pbtheme' ),
        ),
        'content_4' => array(
            'type' => 'textarea',
            'label' => esc_html__( 'Fourth content:', 'pbtheme' )
        ),
        'type' =>array(
            'type' => 'select',
            'label' => esc_html__( 'How manu tabs can be opened at once?', 'pbtheme' ),
            'options' => array(
                'one' => esc_html__( 'One', 'pbtheme' ),
                'multiple' => esc_html__( 'Multiple', 'pbtheme' ),
            ),
            'default' => 'circle'
        ),
        'title_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Title color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'active_title_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Active title color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'text_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Text color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'border' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Border on / off', 'pbtheme' ),
            'options' => array(
                'on' => esc_html__( 'On', 'pbtheme' ),
                'off' => esc_html__( 'Off', 'pbtheme' ),
            ),
            'default' => 'circle'
        ),
        'border_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Border color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'bgd_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Background color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'active_bgd_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Active background color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        
    )
    
);
predic_widget()->add_widget( $pbtheme_accordion_widget );