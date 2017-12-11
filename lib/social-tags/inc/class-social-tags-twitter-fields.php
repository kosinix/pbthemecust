<?php

/**
 * Adds Twitter username on user profile page
 * It is used for Twitter card tags
 */
class Shi_Social_Tags_Twitter_Fields {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_filter( 'user_contactmethods', array( $this, 'user_custom_fields' ), 10, 1 );
    }
    
    /*
     * Add user profile custom fields
     */
    public function user_custom_fields( $contactmethods ) {
        
        $contactmethods['twitter_username'] = esc_html__( 'Twitter username for social tags:', 'pb_theme' );

        return $contactmethods;

    }
    
}
new Shi_Social_Tags_Twitter_Fields();