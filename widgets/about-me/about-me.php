<?php

/**
 * About me / author widget
 */
class PBTheme_About_Me_Author_Widget {
    
    /**
     * Select options for admin form to select created contact in theme options
     * @var array
     */
    public $networks_select_options = array();
    
    /**
     * Created contacts in theme options
     * @var array
     */
    private $networks;
    
    public function __construct() {
        $this->networks_select_options();
    }

    /**
     * Render widget frontend view
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance The settings for the particular instance of the widget.
     * @param array $form_fields Widget admin form fields configuration array
     */
    public function render_view( $args, $instance, $form_fields ) {
        
        $widget_title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
        
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $widget_title ) ) {
            echo $args['before_title'] . esc_html( $widget_title ) . $args['after_title'];
        }
        
        /**
         * Define color vars
         */
        $bgd_color_style = isset( $instance['bgd_color'] ) && !empty( $instance['bgd_color'] ) ? $this->add_color_style_attr( 'background', $instance['bgd_color'] ) : '';
        $bgd_icons_color_style = isset( $instance['bgd_icons_color'] ) && !empty( $instance['bgd_icons_color'] ) ? $this->add_color_style_attr( 'background', $instance['bgd_icons_color'] ) : '';
        $border_color_style = isset( $instance['border_color'] ) && !empty( $instance['border_color'] ) ? ' style="border: 1px solid ' . esc_attr( $instance['border_color'] ) . ';"' : '';
        $title_color_style = isset( $instance['title_color'] ) && !empty( $instance['title_color'] ) ? $this->add_color_style_attr( 'color', $instance['title_color'] ) : '';
        $subtitle_color_style = isset( $instance['subtitle_color'] ) && !empty( $instance['subtitle_color'] ) ? $this->add_color_style_attr( 'color', $instance['subtitle_color'] ) : '';
        $text_color_style = isset( $instance['text_color'] ) && !empty( $instance['text_color'] ) ? $this->add_color_style_attr( 'color', $instance['text_color'] ) : '';
        
        /**
         * Define vars
         */
        $title = isset( $instance['title'] ) && !empty( $instance['title'] ) ? sprintf( '<h3%2$s>%1$s</h3>', strip_tags( $instance['title'] ), $title_color_style ) : '';
        $subtitle = isset( $instance['subtitle'] ) && !empty( $instance['subtitle'] ) ? sprintf( '<h4%2$s>%1$s</h4>', strip_tags( $instance['subtitle'] ), $subtitle_color_style ) : '';
        $description = isset( $instance['description'] ) && !empty( $instance['description'] ) 
                ? sprintf( '<p class="wpbtheme_about_me__content-text"%2$s>%1$s</p>', wp_kses_post( nl2br( $instance['description'] ) ), $text_color_style ) 
                : '';
        $networks = isset( $instance['networks'] ) && !empty( $instance['networks'] ) ? $instance['networks'] : '';
        
        $image_src_or_id = isset( $instance['image'] ) ? $instance['image'] : '';
        $image_type = isset( $instance['image_type'] ) && !empty( $instance['image_type'] ) ? $instance['image_type'] : $form_fields['image_type']['default'];
        $image_thumbnail_size = $image_type === 'landscape' ? 'large' : 'medium';
        // If attachment id it will be > 0, else url is provided
        $image = !empty( $image_src_or_id ) && intval( $image_src_or_id ) > 0 
                ? wp_get_attachment_image( intval( $image_src_or_id ), $image_thumbnail_size ) 
                : '<img src="' . esc_url( $image_src_or_id ) . '" alt="' . strip_tags( $title ) . '" />';
        
        $image = '<div class="wpbtheme_about_me__img"><div>' . $image . '</div></div>';
        
        // Get social icons
        $networks = $this->networks( $networks );
        
        /**
         * Classes added to widget parent wrapper
         */
        $widget_css_classes = array();
        
        // Add image type classes
        $widget_css_classes[] = 'wpbtheme_about_me__img--' . $image_type;
        
        // Add no image class
        if ( empty( $image_src_or_id ) ) {
            $widget_css_classes[] = 'wpbtheme_about_me--no-img';
        }
        

        $html = '';
        
        /**
         * Html start
         */
        
        $html .= sprintf( '<div class="wpbtheme_about_me %1$s"%2$s>', implode( ' ', array_map( 'esc_attr', $widget_css_classes ) ), $border_color_style );
            
        // Image type circle or landscape selected
        if ( ! empty( $image_src_or_id ) ) :
            $html .= $image;
        endif;
          
        if ( ! empty( $title ) || ! empty( $subtitle ) || ! empty( $description ) ) :
        $html   .= sprintf( '<div class="wpbtheme_about_me__content"%s>', $bgd_color_style );
        $html       .= $title . $subtitle . $description;
        $html   .= '</div>'; 
        endif;
          
        if ( ! empty( $networks ) ) :
        $html   .= sprintf( '<div class="wpbtheme_about_me__social"%2$s>%1$s</div>', $networks, $bgd_icons_color_style );
        endif;

        $html .= '</div>';

        /* html end */
        
        echo $html;

        echo $args['after_widget'];
    }
    
    /**
     * Set options for select in admin form for widget
     * Set created contacts in theme options
     * @global array $pbtheme_data
     */
    private function networks_select_options() {
               
        global $pbtheme_data;
        $contacts = isset( $pbtheme_data['contact'] ) && is_array( $pbtheme_data['contact'] ) ? $pbtheme_data['contact'] : array();
        $created_contacts = array();
        
        if ( isset( $contacts ) && count( $contacts ) > 0 ) {
            
            $created_contacts['none'] = esc_html__( 'Hide contacts', 'pbtheme' );
            
            foreach ( $contacts as $contact ) {
                
                if ( isset( $contact['name'] ) && !empty( $contact['name'] ) ) {
                    $created_contacts[ $this->create_network_key( $contact['name'] ) ] = strip_tags( $contact['name']  );
                }

            }
            
        }
        
        // set class vars
        $this->networks = $contacts;
        $this->networks_select_options = $created_contacts;
        
    }
    
    /**
     * Sanitize and create array key from some text input
     * @param string|int $name
     * @return string
     */
    private function create_network_key( $name ) {
        return strtolower( sanitize_file_name( $name ) );
    }
    
    /**
     * Return html for selected contact from theme options
     */
    private function networks( $selected ) {
        
        $html = '';

        if ( 'none' === $selected ) {
            return $html;
        }
        
        if ( ! is_array( $this->networks ) || empty( $this->networks ) ) {
            return $html;
        }

        foreach ( $this->networks as $network ) {

            if ( ! isset( $network['name'] ) || ( $selected !== $this->create_network_key( $network['name'] ) ) ) {
                continue;
            }

            if ( ! isset( $network['contact'] ) || ! is_array( $network['contact'] ) || empty( $network['contact'] ) ) {
                continue;
            }
            
            foreach ( $network['contact'] as $contact ) {
                
                $url = isset( $contact['socialnetworksurl'] ) ? $contact['socialnetworksurl'] : '';
                $icon = isset( $contact['socialnetworks'] ) ? $contact['socialnetworks'] : '';
                
                $html .= sprintf(
                    '<a href="%1$s"><img width="24" height="24" src="%2$s/images/socialnetworks/%3$s" /></a>', 
                    esc_url( $url ), 
                    get_template_directory_uri(), 
                    $icon 
                );
                
            }
            
            return $html;
            
        }
        
    }
    
    /**
     * Return inline stype css attribute
     */
    private function add_color_style_attr( $property, $color ) {
        return ' style="' . esc_attr( $property ) . ': ' . esc_attr( $color ) . ';"';
    }
    
}

/**
 * Map and init widget
 */
$pbtheme_about_me_widget_instance = new PBTheme_About_Me_Author_Widget();
$pbtheme_about_me_widget = array(
    'base_id' => 'wpbtheme_about_me',
    'name' => esc_html__('+ PBTheme About me / author', 'pbtheme'),
    'callback' => array( $pbtheme_about_me_widget_instance, 'render_view' ),

    'widget_ops' => array(
        'classname' => 'pbtheme-about-me-widget',
        'description' => esc_html__( 'Display custom information about a person.', 'pbtheme' ),
        'customize_selective_refresh' => false,
    ),

    'form_fields' => array(
        'widget_title' => array(
            'type' => 'text',
            'label' => esc_html__( 'Widget title:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter widget title here', 'pbtheme' )
        ),
        'image' => array(
            'type' => 'uploader_image',
            'label' => esc_html__( 'Select image:', 'pbtheme' ),
        ),
        'image_type' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Select image type:', 'pbtheme' ),
            'options' => array(
                'circle' => esc_html__( 'Circle', 'pbtheme' ),
                'circle_top' => esc_html__( 'Circle top', 'pbtheme' ),
                'landscape' => esc_html__( 'Landscape', 'pbtheme' ),
            ),
            'default' => 'circle'
        ),
        'title' => array(
            'type' => 'text',
            'label' => esc_html__( 'Title:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter title here', 'pbtheme' )
        ),
        'subtitle' => array(
            'type' => 'text',
            'label' => esc_html__( 'Subtitle:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter subtitle here', 'pbtheme' )
        ),
        'description' => array(
            'type' => 'textarea',
            'label' => esc_html__( 'Description:', 'pbtheme' ),
            'placeholder' => esc_html__( 'Enter description here', 'pbtheme' )
        ),
        'networks' =>array(
            'type' => 'select',
            'label' => esc_html__( 'Select contact from theme options:', 'pbtheme' ),
            'options' => $pbtheme_about_me_widget_instance->networks_select_options,
        ),
        'bgd_color' => array(
            'type' => 'color',
            'label' => esc_html__( 'Background color:', 'pbtheme' ),
            'alpha' => true, 
            'default' => ''
        ),
        'bgd_icons_color' => array(
            'type' => 'color',
            'label' => esc_html__( 'Background icons color:', 'pbtheme' ),
            'alpha' => true, 
            'default' => ''
        ),
        'border_color' => array(
            'type' => 'color',
            'label' => esc_html__( 'Border color:', 'pbtheme' ),
            'alpha' => true, 
            'default' => ''
        ),
        'title_color' => array(
            'type' => 'color',
            'label' => esc_html__( 'Title color:', 'pbtheme' ),
            'alpha' => true, 
            'default' => ''
        ),
        'subtitle_color' => array(
            'type' => 'color',
            'label' => esc_html__( 'Subtitle color:', 'pbtheme' ),
            'alpha' => true, 
            'default' => ''
        ),
        'text_color' => array(
            'type' => 'color',
            'label' => esc_html__( 'Text color:', 'pbtheme' ),
            'alpha' => true, 
            'default' => ''
        ),
    ),

);
predic_widget()->add_widget( $pbtheme_about_me_widget );