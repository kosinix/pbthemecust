<?php

/**
 * Social tags for posts, pages and products.
 * Supports Google+, Facebook and Twitter
 * Google+ is suportred via og tags
 * 
 * No need to enable in options, this is automatic generation of social tags (for now)
 * 
 * @version 1.0.0
 */

class Shi_Social_Tags_Init {
    
    /**
     * Root path
     * @var string
     */
    private $root_path; 
    
    /**
     * Files to include for every network
     * @var array
     */
    private $common_files = array(
        '/inc/class-social-tags.php',
        '/inc/interface-social-tags.php',
        '/inc/class-social-tags-helper.php',
    );
    
    /**
     * Include Twitter tags
     * @var boolean
     */
    private $twitter = true;
    
    /**
     * Files to include for Twitter
     * @var array
     */
    private $twitter_files = array(
        '/inc/class-social-tags-twitter.php'
    );
    
    /**
     * Include Facebook tags
     * @var boolean
     */
    private $facebook = true;
    
    /**
     * Files to include for Facebook
     * @var array
     */
    private $facebook_files = array(
        '/inc/class-social-tags-facebook.php'
    );
    
    /**
     * Constructor
     */
    public function __construct() {
        
        $this->root_path = dirname( __FILE__ );
        // Add Twitter username in user profile
        $this->add_custom_user_fields();
        // Init files if needed on page
        add_action( 'template_redirect', array( $this, 'init' ) );
    }
    
    /**
     * Init enabled networks
     */
    public function init() {
        
        if ( ! is_singular( array( 'post', 'page', 'product' ) ) ) {
            return;
        }
        
        if ( $this->facebook ) {
            $this->require_files( $this->common_files );
            $this->require_files( $this->facebook_files );
            $facebook = Shi_Social_Tags::create_network( 'facebook' );
            $facebook->add_head_hook();
        }
        
        if ( $this->twitter ) {
            $this->require_files( $this->common_files );
            $this->require_files( $this->twitter_files );
            $twitter = Shi_Social_Tags::create_network( 'twitter' );
            $twitter->add_head_hook();
        }
        
    }
    
    /**
     * Require needed files
     */
    private function require_files( $files ) {
        
        foreach ( $files as $file ) {
            
            $path = $this->root_path . $file;
            
            if ( ! file_exists( $path ) ) {
                continue;
            }
            
            require_once $path;
        }
        
    }
    
    /**
     * Adds custom user field for Twitter
     */
    private function add_custom_user_fields() {
        require_once dirname( __FILE__ ) . '/inc/class-social-tags-twitter-fields.php';
    }
    
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(!is_plugin_active('SilentSiphon/silentsiphon.php')){
  new Shi_Social_Tags_Init();
}