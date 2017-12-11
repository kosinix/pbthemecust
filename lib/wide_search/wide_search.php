<?php

/**
 * Wide search in header primary menu
 * 
 * Adds metabox to enable / disabe wide search for product, post and page
 * If enabled, it override theme option Header settings => Search bar (Set Search bar inline) and set it to "on"
 * Applied only for page, post or product for which is enabled
 */

if ( ! class_exists( 'CMB2' ) ) {
    require get_template_directory() . '/cmb2/init.php';
}

/**
 * Class responsible for adding wide search in header menu
 */
class Pb_Theme_Header_Menu_Wide_Search {
    
    private $root_url;
    private $cmb2_prefix = '_pbtheme_header_ws_'; // CMB2 framework prefix
    private $cmb2_state_field; // Field id for state on / off
    
    /**
     * Constructor
     */
    public function __construct() {
        
        
        $this->root_url = get_template_directory_uri() . '/lib/wide_search/';
        $this->cmb2_state_field = $this->cmb2_prefix . 'state';
        
        add_action( 'cmb2_admin_init', array( $this, 'cmb2_metaboxes' ) );
        
        add_action( 'wp', array( $this, 'init' ) );
    }
    
    /**
     * Check if conditions are fullfield
     * 
     * @global array $pbtheme_data Theme options
     */
    public function init() {

        /**
         * Check if we are on the right object or return
         */
        if ( class_exists( 'WooCommerce' ) ) {
            
            if ( ! is_singular() && !is_product() ) {
                return;
            }
            
        } else {
            
            if ( ! is_singular() ) {
                return;
            }
            
        }

        /**
         * If disabled no need to add scripts
         */
        $state = get_post_meta( get_the_ID(), $this->cmb2_state_field, true );
        $state = apply_filters( 'pb_theme_header_wide_search', $state, get_the_ID() );

        if ( $state !== 'on' ) {
            return;
        } 
        
        /**
         * Add script and override theme option for adding search inline into menu
         */
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 1005 );
        
        global $pbtheme_data;
        $pbtheme_data['header_search_style'] = 1;

    }
    
    /**
    * Define the metabox and field configurations.
    */
    public function cmb2_metaboxes() {
        
        /**
         * Initiate wide search metabox
         */
        $cmb = new_cmb2_box(array(
            'id' => 'wide_search_metabox',
            'title' => __('Header wide search', 'pbtheme'),
            'object_types' => array( 'product', 'page', 'post' ), // Post type
            'context' => 'side',
            'priority' => 'default',
            'show_names' => true, // Show field names on the left
            // 'cmb_styles' => false, // false to disable the CMB stylesheet
            // 'closed'     => true, // Keep the metabox closed by default
        ));

        // Related Products
        $cmb->add_field(array(
            'name' => __('Enable or disable wide search', 'pbtheme'),
            'desc' => __('This will override inline search selection in theme options for the current page.', 'pbtheme'),
            'id' => $this->cmb2_state_field,
            'type' => 'radio_inline',
            'default' => 'off',
            'options' => array(
                'on' => __('Enable', 'pbtheme'),
                'off' => __('Disable', 'pbtheme')
            ),
        ));

    }
    
    /**
     * Add scripts
     */
    public function scripts() {
        wp_enqueue_style( 'pb-theme-header-menu-ws', $this->root_url . 'assets/styles.css', array() );
        wp_enqueue_script( 'pb-theme-header-menu-ws', $this->root_url . 'assets/main.js', array( 'jquery' ), '1.0.0', true );
    }
    
}
new Pb_Theme_Header_Menu_Wide_Search();