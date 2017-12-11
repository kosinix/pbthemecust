<?php

/**
 * Add post subtitle
 */
class Pbtheme_Post_Subtitle {
    
    private $cmb2_prefix = '_pbtheme_post_subtitle_'; // CMB2 framework prefix
    private $cmb2_field; // Field id
    
    /**
     * Constructor
     */
    public function __construct() {
        
        $this->cmb2_field = $this->cmb2_prefix . 'text';
        add_action( 'cmb2_admin_init', array( $this, 'cmb2_metaboxes' ) );
        
        if ( !is_admin() ) {
            add_action( 'pbtheme_post_subtitle_text', array( $this, 'show_text' ), 20 );
        }
        
    }
    
    /**
    * Define the metabox and field configurations.
    */
    public function cmb2_metaboxes() {
        
        /**
         * Initiate metabox
         */
        $cmb = new_cmb2_box(array(
            'id' => 'pbtheme_post_subtitle',
            'title' => __('Post subtitle', 'pbtheme'),
            'object_types' => array( 'post' ), // Post type
            'context' => 'normal',
            'priority' => 'core',
            'show_names' => true, // Show field names on the left
            'cmb_styles' => false, // false to disable the CMB stylesheet
        ));

        $cmb->add_field(array(
            'name' => false,
            'desc' => __('Enter post subtitle here.', 'pbtheme'),
            'id' => $this->cmb2_field,
            'type' => 'textarea_small',
            'default' => '',
            'attributes'  => array(
                'placeholder' => 'Enter post subtitle here',
                'rows'        => 3,
                'style'    => 'width: 100%;',
            )
        ));

    }
    
    /**
     * Echo post subtitle text
     */
    public function show_text() {
        
        $post_subtitle_text = $this->get_subtitle( get_the_ID() );
        
        if ( ! empty( $post_subtitle_text ) ) {
            echo '<h3 class="entry-subtitle">' . wp_kses_post( $post_subtitle_text ) . '</h3>';
        }
        
    }
    
    /**
     * Retrieve subtitle text
     * 
     * @param int $post_id Post id
     * @return mixed Metabox text or false
     */
    public function get_subtitle( $post_id ) {

        return get_post_meta( $post_id, $this->cmb2_field, true );
        
    }
    
}
new Pbtheme_Post_Subtitle();