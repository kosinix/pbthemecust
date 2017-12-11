<?php

/**
 * Contact form 7 widget integration class
 * https://wordpress.org/plugins/contact-form-7/
 */
class PBTheme_Cf7_Widget {
    
    /**
     * Unique widget id used for custom inline css
     * @var string
     */
    private $widget_id;
    private $inline_css;
    private $root_url;
    
    public function __construct() {
        $this->root_url = get_template_directory_uri() . '/widgets/contact-form-7';
    }
    
    /**
     * Render widget frontend view
     * 
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance The settings for the particular instance of the widget.
     * @param array $form_fields Settings when configuring widget in builder
     */
    public function render_view( $args, $instance, $form_fields ) {
        
        $widget_title = isset( $instance['title'] ) ? $instance['title'] : '';
               
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        
        if ( ! empty( $widget_title ) ) {
            echo $args['before_title'] . esc_html( $widget_title ) . $args['after_title'];
        }

        /**
         * Define vars
         */
        $map = isset( $instance['map'] ) && !empty( $instance['map'] ) ? $instance['map'] : '';
        $headline = isset( $instance['headline'] ) && !empty( $instance['headline'] ) ? $instance['headline'] : '';
        $description = isset( $instance['description'] ) && !empty( $instance['description'] ) ? $instance['description'] : '';
        $shortcode = isset( $instance['shortcode'] ) && !empty( $instance['shortcode'] ) ? $instance['shortcode'] : '';
        $alignment = isset( $instance['alignment'] ) && !empty( $instance['alignment'] ) ? $instance['alignment'] : $form_fields['alignment']['default'];
        $bgd_type = isset( $instance['bgd_type'] ) && !empty( $instance['bgd_type'] ) ? $instance['bgd_type'] : $form_fields['bgd_type']['default'];
        $bgd_color_style = isset( $instance['bgd_color'] ) && !empty( $instance['bgd_color'] ) ? ' style="background-color: ' . esc_attr( $instance['bgd_color'] ) . '"' : '';
        $image_src = isset( $instance['image'] ) ? $instance['image'] : '';
        $image_bgd_style = !empty( $image_src ) && intval( $image_src ) > 0 
                ? ' style="background-image: url(\'' . esc_url( wp_get_attachment_url( $image_src ) ) . '\');"'
                : ' style="background-image: url(\'' . esc_url( $image_src ) . '\');"';
        
        $wrapper_style = '';
        if ( $bgd_type === 'image' && !empty( $image_src ) ) {
            $wrapper_style = $image_bgd_style;
        } elseif ( $bgd_type === 'color' ) {
            $wrapper_style = $bgd_color_style;   
        }

        // Unique widget id used for custom inline css
        $this->widget_id = uniqid( 'wpbtheme-cf7-' );
                
        echo $this->inline_css( $instance );
        
        /**
         * Add wrapper classes
         */
        $classes = array();
        $classes[] = 'wpbtheme-cf7--text-' . $alignment;
        $classes[] = 'wpbtheme-cf7--bgd-' . $bgd_type;
        
        /**
         * Html output start
         */
        
        echo '<div id="' . esc_attr( $this->widget_id ) . '" class="wpbtheme-cf7 ' . implode( ' ', array_map( 'esc_attr', $classes ) ) .'"' . $wrapper_style . '>';
        
            if ( !empty( $map ) ) {
                echo '<div class="wpbtheme-cf7__map">' . $this->validate_allowed_html_tags( $map ) . '</div>';
            }
            
            echo '<div class="wpbtheme-cf7__content">';
            
            if ( !empty( $headline ) ) {
                echo '<h4 class="wpbtheme-cf7__headline">' . strip_tags( $headline ) . '</h4>';
            }
            
            if ( !empty( $description ) ) {
                echo '<p class="wpbtheme-cf7__desc">' . strip_tags( $description ) . '</p>';
            }

            if ( !empty( $shortcode ) && has_shortcode( $shortcode, 'contact-form-7' ) ) {
                echo '<div class="wpbtheme-cf7__form">' . do_shortcode( $shortcode ) . '</div>';
            }
        
            echo '</div><!-- .wpbtheme-cf7__content -->';
            
        echo '</div><!-- .wpbtheme-cf7 -->';

        echo $args['after_widget'];
        
        // Add scripts
        $this->add_scripts();
        
    }
    
    /**
     * Add scripts to footer
     */
    private function add_scripts() {
        wp_enqueue_script( 'wpbtheme-cf7-widget', $this->root_url . '/assets/js/main.js', array( 'jquery' ), '1.0.0', true );
    }

        /**
     * Return inline css to include for widget custom colors
     * @param array $instance
     * @return string
     */
    private function inline_css( $instance ) {
        
        $css = '';
        $widget_id = '#' . $this->widget_id;
        
        // headline color
        if ( isset( $instance['headline_color'] ) && !empty( $instance['headline_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme-cf7__headline{color:' . esc_attr( $instance['headline_color'] ) . ';}';
        }
        
        if ( isset( $instance['border_color'] ) && !empty( $instance['border_color'] ) ) {
            $css .= $widget_id . '.wpbtheme-cf7 {border-color:' . esc_attr( $instance['border_color'] ) . ';}';
        }
        
        if ( isset( $instance['text_color'] ) && !empty( $instance['text_color'] ) ) {
            $css .= $widget_id . ' .wpbtheme-cf7__desc,'
                    . $widget_id . ' .wpcf7-response-output '
                    . '{color:' . esc_attr( $instance['text_color'] ) . ';}';
        }
        
        if ( isset( $instance['field_color'] ) && !empty( $instance['field_color'] ) ) {
            $css .= $widget_id . ' form input,'
                    . $widget_id . ' form select,'
                    . $widget_id . ' form textarea'
                    . '{background-color:' . esc_attr( $instance['field_color'] ) . ';}';
        }
        if ( isset( $instance['field_border_color'] ) && !empty( $instance['field_border_color'] ) ) {
            $css .= $widget_id . ' form input, '
                    . $widget_id . ' form select,'
                    . $widget_id . ' form textarea'
                    . '{border: 1px solid ' . esc_attr( $instance['field_border_color'] ) . ' !important;}';
        }
        if ( isset( $instance['field_text_color'] ) && !empty( $instance['field_text_color'] ) ) {
            $css .= $widget_id . ' form input,'
                    . $widget_id . ' form select,'
                    . $widget_id . ' form textarea,'
                    . $widget_id . ' form label '
                    . '{color:' . esc_attr( $instance['field_text_color'] ) . ';}';
        }
        if ( isset( $instance['button_color'] ) && !empty( $instance['button_color'] ) ) {
            $css .= $widget_id . ' form input[type="submit"] {background-color:' . esc_attr( $instance['button_color'] ) . '; border: 1px solid ' . esc_attr( $instance['button_color'] ) . ' !important;}';
        }
        if ( isset( $instance['button_text_color'] ) && !empty( $instance['button_text_color'] ) ) {
            $css .= $widget_id . ' form input[type="submit"] {color:' . esc_attr( $instance['button_text_color'] ) . ';}';
        }
        
        if ( ! empty( $css ) ) {
            return '<style type="text/css">' . $css . '</style>';
        }
        
        return $css;  
    }   
    
    /**
     * Validate all allowed html tags in content with addition of allowed iframe and script tags
     * @global array $allowedposttags All allowed tags in WordPress post
     * @param string $content Content to validate
     * @return string
     */
    private function validate_allowed_html_tags( $content ) {
        
        global $allowedposttags; 

        $tags = array(
            'iframe' => array(
                'id' => true,
                'name' => true,
                'src' => true,
                'width' => true,
                'height' => true,
                'class' => true,
                'frameborder' => true,
                'webkitAllowFullScreen' => true,
                'mozallowfullscreen' => true,
                'allowFullScreen' => true
            ), 
            'embed' => array(
                'src' => true,
                'width' => true,
                'height' => true,
                'align' => true,
                'class' => true,
                'name' => true,
                'id' => true,
                'frameborder' => true,
                'seamless' => true,
                'srcdoc' => true,
                'sandbox' => true,
                'allowfullscreen' => true
            )
        );

        $allowed_tags = shortcode_atts( $tags, $allowedposttags );  
        
        return wp_kses( $content, $allowed_tags );
        
    }
}

/**
 * Map and init Contact form 7 widget
 */
$pbtheme_cf7_widget = array(
    
    // Core configuration
    'base_id' => 'pbtheme_cf7_widget',
    'name' => esc_html__('+ PBTheme Contact form 7', 'pbtheme'),
    'callback' => array( new PBTheme_Cf7_Widget(), 'render_view' ),

    'widget_ops' => array(
        'classname' => 'pbtheme-cf7-widget',
        'description' => esc_html__( 'Contact form 7 widget with custom options.', 'pbtheme' ),
        'customize_selective_refresh' => false,
    ),
    
    'form_fields' => array(
        
        'title' => array(
            'type' => 'text',
            'label' => esc_html__( 'Widget title:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter widget title here', 'pbtheme' ),
        ),
        'map' => array(
            'type' => 'textarea',
            'label' => esc_html__( 'Google map iframe to embead:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Paste Google map embead code here', 'pbtheme' ),
        ),
        'headline' => array(
            'type' => 'text',
            'label' => esc_html__( 'Title:', 'pbtheme' ),
        ),
        'description' => array(
            'type' => 'textarea',
            'label' => esc_html__( 'Description:', 'pbtheme' ),
        ),
        'shortcode' => array(
            'type' => 'textarea',
            'label' => esc_html__( 'Paste contact form 7 shortcode. Example: [contact-form-7 id="15977" title="Contact form 1"]', 'pbtheme' ),
        ),
        'alignment' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Title & description alignment', 'pbtheme' ),
            'options' => array(
                'left' => esc_html__( 'Left', 'pbtheme' ),
                'center' => esc_html__( 'Center', 'pbtheme' )
            ),
            'default' => 'left'
        ),
        'bgd_type' => array(
            'type' => 'select',
            'label' => esc_html__( 'Background type:', 'pbtheme' ),
            'options' => array(
                'image' => esc_html__( 'Image', 'pbtheme' ),
                'color' => esc_html__( 'Color', 'pbtheme' ),
                'none' => esc_html__( 'None', 'pbtheme' ),
            ),
            'default' => 'none'
        ),
        'image' => array(
            'type' => 'uploader_image',
            'label' => esc_html__( 'Background image:', 'pbtheme' ),
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
        'headline_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Title color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'text_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Description text color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'field_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Field color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'field_border_color' => array(
            'type' => 'color', 
            'label' => esc_html__( 'Field border color:', 'pbtheme' ),
            'alpha' => true,
            'default' => ''
        ),
        'field_text_color' => array(
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
if ( class_exists( 'WPCF7' ) ) {
    predic_widget()->add_widget( $pbtheme_cf7_widget );
}