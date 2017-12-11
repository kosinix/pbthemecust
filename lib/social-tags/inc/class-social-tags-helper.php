<?php

/**
 * Class responsible for common functions used in all social networks
 */
class Shi_Social_Tags_Helper {
    
    /**
     * Return any post type title
     * @return string
     */
    public function set_title() {
        return strip_tags( get_the_title() );
    }
    
    /**
     * Return any post type description or excerpt.
     * If it is product than short product descriptions is returned instead of excerpt
     * @return string
     */
    public function set_description( $length ) {
        
        global $post;
        
        if ( is_singular( 'product' ) && !empty( $post->post_excerpt ) ) {
            $description = strip_tags( apply_filters( 'woocommerce_short_description', $post->post_excerpt ) );
        } else {
            $description = strip_tags( strip_shortcodes( get_the_excerpt() ) );
        }

        if ( strlen( $description ) >= $length ) { 
            $description = substr( $description, 0, $length ) . '...'; 
        }
        
        return trim( $description );
        
    }
    
    /**
     * Return any post type url
     * @return string
     */
    public function set_url() {
        return esc_url( get_permalink() );
    }
    
    /**
     * Return any post type thumbnail url
     * @return string
     */
    public function set_image() {
        return esc_url( get_the_post_thumbnail_url() );
    }
    
    /**
     * Return site name from General settings
     * @return string
     */
    public function set_site_name() {
        return strip_tags( get_bloginfo( 'name' ) );
    }
    
}