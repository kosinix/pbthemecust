<?php

/**
 * Custom css per page / post
 */
class Pbtheme_Custom_Singular_Css {
    
    private $cmb2_prefix = '_pbtheme_custom_css_'; // CMB2 framework prefix
    private $cmb2_field; // Field id
    
    /**
     * Constructor
     */
    public function __construct() {
        
        $this->cmb2_field = $this->cmb2_prefix . 'textarea';
        add_action( 'cmb2_admin_init', array( $this, 'cmb2_metaboxes' ) );
        add_action( 'wp_head', array( $this, 'add_custom_css' ), 1009 );
        
    }
    
    /**
    * Define the metabox and field configurations.
    */
    public function cmb2_metaboxes() {
        
        /**
         * Initiate metabox
         */
        $cmb = new_cmb2_box(array(
            'id' => 'pbtheme_custom_css',
            'title' => __('Custom css', 'pbtheme'),
            'object_types' => array( 'page', 'post' ), // Post type
            'context' => 'normal',
            'priority' => 'default',
            'show_names' => true, // Show field names on the left
            'cmb_styles' => false, // false to disable the CMB stylesheet
        ));

        $cmb->add_field(array(
            'name' => false,
            'desc' => __('Enter custom css here.', 'pbtheme'),
            'id' => $this->cmb2_field,
            'type' => 'textarea_code',
            'default' => '',
            'attributes'  => array(
                'placeholder' => 'Enter custom css here',
                'rows'        => 10,
                'style'    => 'width: 100%;',
            )
        ));

    }
    
    /**
     * Add custom css via wp_head action hook
     */
    public function add_custom_css() {
        
        if ( !is_singular( array( 'post', 'page' ) ) ) {
            return;
        }
        
        $css = $this->get_custom_css();
        
        echo "<style>{$css}</style>";

    }
    
    /**
     * Retrieve custom css from database for current post
     * 
     * @return string Custom css
     */
    private function get_custom_css() {
        
        $id = get_the_ID();
        $css = get_post_meta( $id, $this->cmb2_field, true );
        
        if ( empty( $css ) ) {
            return '';
        }
        
        return apply_filters( 'pbtheme_singular_custom_css', $css, $id );
        
    }
    
}
new Pbtheme_Custom_Singular_Css();