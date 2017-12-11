<?php

/**
 * MailChimp for WordPress widget integration class
 * https://wordpress.org/plugins/mailchimp-for-wp/
 */
class PBTheme_Mc4wp_Widget {
    
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
        $type = isset( $instance['type'] ) && $instance['type'] === 'default' ? 'wpbtheme-mc4wp--default' : 'wpbtheme-mc4wp--custom';
        $newsletter_title = isset( $instance['newsletter_title'] ) && !empty( $instance['newsletter_title'] ) ? $instance['newsletter_title'] : '';
        $newsletter_text = isset( $instance['newsletter_text'] ) && !empty( $instance['newsletter_text'] ) ? $instance['newsletter_text'] : '';
        $alignment = isset( $instance['alignment'] ) && $instance['alignment'] === 'center' ? 'text-align: center;' : 'text-align: left;';
        $image = isset( $instance['image'] ) && !empty( $instance['image'] ) ? 'background-image: url(\''. wp_get_attachment_url( $instance['image'] ) .'\'); background-size: cover; background-position: center;' : '';
        $newsletter_title_color = isset( $instance['newsletter_title_color'] ) && !empty( $instance['newsletter_title_color'] ) ? ' style="color: '. esc_attr( $instance['newsletter_title_color'] ) .';"' : '';
        $newsletter_text_color = isset( $instance['newsletter_text_color'] ) && !empty( $instance['newsletter_text_color'] ) ? 'style="color: '. esc_attr( $instance['newsletter_text_color'] ) .';"' : '';
        $bgd_color = isset( $instance['bgd_color'] ) && !empty( $instance['bgd_color'] ) ? 'background-color: '. esc_attr( $instance['bgd_color'] ) .';' : '';
        $border_color = isset( $instance['border_color'] ) && !empty( $instance['border_color'] ) ? 'border-color: '. esc_attr( $instance['border_color'] ) .';' : '';
        
        
        // Unique widget id used for custom inline css
        $this->widget_id = uniqid( 'wpbtheme-mc4wp-' );
                
        echo $this->inline_css( $instance );
        
        /**
         * Add wrapper classes
         */
        $classes = array();
        $classes[] = $type;
        
        /**
         * Html output start
         */
        
        echo '<div id="' . esc_attr( $this->widget_id ) . '" class="wpbtheme-mc4wp ' . implode( ' ', array_map( 'esc_attr', $classes ) ) .'" style="'. $alignment .' '. $image .' '. $bgd_color .' '. $border_color .'">';
        echo '<div class="mc4wp-title-text-holder"><p class="mc4wp-newsletter-title"'. $newsletter_title_color .'>'. esc_html( $newsletter_title ) .'</p><p class="mc4wp-newsletter-text" '. $newsletter_text_color .'>'. esc_attr( $newsletter_text ) .'<p/></div>';
        
        /**
         * Echo default mc4wp form
         */
        if ( function_exists( 'mc4wp_get_form' ) ) {
            try {
                echo mc4wp_get_form();
            } catch (Exception $ex) {
                esc_html_e( 'Please check if you have set default form for MailChimp plugin.', 'pbtheme' );
            }
            
        }
        
        echo '</div><!-- .wpbtheme-mc4wp -->';

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
            $css .= $widget_id . ' label '
                    . '{color:' . esc_attr( $instance['title_color'] ) . ';}';
        }
        
        // Custom alert color
        if ( isset( $instance['newsletter_text_color'] ) && !empty( $instance['newsletter_text_color'] ) ) {
            $css .= $widget_id . ' .mc4wp-alert'
                    . '{color:' . esc_attr( $instance['newsletter_text_color'] ) . ';}';
        }
        
        if ( isset( $instance['input_field_color'] ) && !empty( $instance['input_field_color'] ) ) {
            $css .= $widget_id . ' .mc4wp-form-fields input,'
                    . $widget_id . ' .mc4wp-form-fields select,'
                    . $widget_id . ' .mc4wp-form-fields textarea'
                    . '{background-color:' . esc_attr( $instance['input_field_color'] ) . ';}';
        }
        if ( isset( $instance['input_field_border_color'] ) && !empty( $instance['input_field_border_color'] ) ) {
            $css .= $widget_id . ' .mc4wp-form-fields input, '
                    . $widget_id . ' .mc4wp-form-fields select,'
                    . $widget_id . ' .mc4wp-form-fields textarea '
                    . '{border: 1px solid ' . esc_attr( $instance['input_field_border_color'] ) . ' !important;}';
        }
        if ( isset( $instance['input_field_text_color'] ) && !empty( $instance['input_field_text_color'] ) ) {
            $css .= $widget_id . ' .mc4wp-form-fields input,'
                    . $widget_id . ' .mc4wp-form-fields select,'
                    . $widget_id . ' .mc4wp-form-fields textarea,'
                    . $widget_id . ' .mc4wp-form-fields label '
                    . '{color:' . esc_attr( $instance['input_field_text_color'] ) . ';}';
        }
        if ( isset( $instance['button_color'] ) && !empty( $instance['button_color'] ) ) {
            $css .= $widget_id . ' .mc4wp-form-fields input[type="submit"] {background-color:' . esc_attr( $instance['button_color'] ) . '; border: 1px solid ' . esc_attr( $instance['button_color'] ) . ';}';
        }
        if ( isset( $instance['button_text_color'] ) && !empty( $instance['button_text_color'] ) ) {
            $css .= $widget_id . ' .mc4wp-form-fields input[type="submit"] {color:' . esc_attr( $instance['button_text_color'] ) . ';}';
        }
        
        
        if ( ! empty( $css ) ) {
            return '<style type="text/css">' . $css . '</style>';
        }
        
        return $css;  
    }   
}

/**
 * Map and init MailChimp for WP widget
 */
$pbtheme_mc4wp_widget = array(
    
    // Core configuration
    'base_id' => 'pbtheme_mc4wp_widget',
    'name' => esc_html__('+ PBTheme MailChimp for WordPress', 'pbtheme'),
    'callback' => array( new PBTheme_Mc4wp_Widget(), 'render_view' ),

    'widget_ops' => array(
        'classname' => 'pbtheme-mc4wp-widget',
        'description' => esc_html__( 'MailChimp signin form with customizing options.', 'pbtheme' ),
        'customize_selective_refresh' => false,
    ),
    
    'form_fields' => array(
        
        'widget_title' => array(
            'type' => 'text',
            'label' => esc_html__( 'Widget title:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter widget title here', 'pbtheme' ),
        ),
        'type' => array(
            'type' => 'select',
            'label' => esc_html__( 'Form style. Use custom if any changes to form.', 'pbtheme' ),
            'options' => array(
                'default' => esc_html__( 'Default form css', 'pbtheme' ),
                'custom' => esc_html__( 'Custom form css', 'pbtheme' ),
            ),
            'default' => 'circle'
        ),
        'newsletter_title' => array(
            'type' => 'text',
            'label' => esc_html__( 'Title:', 'pbtheme' ),
        ),
        'newsletter_title_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Title color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'newsletter_text' => array(
            'type' => 'textarea',
            'label' => esc_html__( 'Description:', 'pbtheme' )
        ),
        'newsletter_text_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Description text color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'alignment' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Title & description alignment', 'pbtheme' ),
            'options' => array(
                'left' => esc_html__( 'Left', 'pbtheme' ),
                'center' => esc_html__( 'Center', 'pbtheme' )
            ),
        ),
        'image' => array(
            'type' => 'uploader_image',
            'label' => esc_html__( 'Select background image:', 'pbtheme' ),
        ),
        'bgd_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Background color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'border_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Border color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'input_field_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Field color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'input_field_border_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Field border color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'input_field_text_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Field text color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'button_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Button color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'button_text_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Button text color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),  
    )
    
);
if ( defined( 'MC4WP_VERSION' ) && function_exists( '_mc4wp_load_plugin' ) ) {
    predic_widget()->add_widget( $pbtheme_mc4wp_widget );
}