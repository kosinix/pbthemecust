<?php

/**
 * Class responsible for promo widget
 */
class PBTheme_Promo_Img_Widget {
    
    /**
     * Render widget frontend view
     * 
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance The settings for the particular instance of the widget.
     */
    public function render_view( $args, $instance ) {
        
        $widget_title = isset( $instance['title'] ) ? $instance['title'] : '';
               
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        
        if ( ! empty( $widget_title ) ) {
            echo $args['before_title'] . esc_html( $widget_title ) . $args['after_title'];
        }
            
        /**
         * Define vars
         */
        $link = isset( $instance['link'] ) && !empty( $instance['link'] ) ? $instance['link'] : '#';
        $target = isset( $instance['target'] ) && $instance['target'] === 'new' ? '_blank' : '_self';
        $image_src_or_id = isset( $instance['image'] ) ? $instance['image'] : '';
        $image = $this->get_image( $image_src_or_id );
        $image_src_or_id_hover = isset( $instance['image_hover'] ) ? $instance['image_hover'] : '';
        $image_hover = $this->get_image( $image_src_or_id_hover, 'wpbtheme-promo-img__hover' );
        
        /**
         * Html output start
         */
        
        echo '<div class="wpbtheme-promo-img">';
        
        echo '<a href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">';
            if ( !empty( $image_src_or_id ) ) {
                echo $image;
            }
            
            if ( !empty( $image_src_or_id_hover ) ) {
                echo $image_hover;
            }
            
        echo '</a>';
            
        echo '</div><!-- .wpbtheme-cf7 -->';

        echo $args['after_widget'];
        
    }
    
    private function get_image( $image_src_or_id ) {
        
        // If attachment id it will be > 0, else url is provided
        return !empty( $image_src_or_id ) && intval( $image_src_or_id ) > 0 
                ? wp_get_attachment_image( intval( $image_src_or_id ), 'full' ) 
                : '<img src="' . esc_url( $image_src_or_id ) . '" alt="Promo image" />';
    }
}

/**
 * Map and init widget
 */
$pbtheme_promo_img_widget = array(
    
    // Core configuration
    'base_id' => 'pbtheme_promo_img_widget',
    'name' => esc_html__('+ PBTheme Promo image', 'pbtheme'),
    'callback' => array( new PBTheme_Promo_Img_Widget(), 'render_view' ),

    'widget_ops' => array(
        'classname' => 'pbtheme-promo-img-widget',
        'description' => esc_html__( 'Promo image as link, that display another image on hover.', 'pbtheme' ),
        'customize_selective_refresh' => false,
    ),
    
    'form_fields' => array(
        
        'title' => array(
            'type' => 'text',
            'label' => esc_html__( 'Widget title:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter widget title here', 'pbtheme' ),
        ),
        'link' => array(
            'type' => 'text',
            'label' => esc_html__( 'Link:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter link url here', 'pbtheme' ),
        ),
        'target' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Open link in:', 'pbtheme' ),
            'options' => array(
                'new' => esc_html__( 'New window / tab', 'pbtheme' ),
                'self' => esc_html__( 'Same window / tab', 'pbtheme' )
            ),
            'default' => 'self'
        ),
        'image' => array(
            'type' => 'uploader_image',
            'label' => esc_html__( 'Image:', 'pbtheme' ),
        ),
        'image_hover' => array(
            'type' => 'uploader_image',
            'label' => esc_html__( 'Hover image:', 'pbtheme' ),
        ),
    )
    
);
predic_widget()->add_widget( $pbtheme_promo_img_widget );