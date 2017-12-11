<?php

/**
 * Class responsible for Twitter card tags
 * 
 * @link https://dev.twitter.com/cards/types/summary
 */
class Shi_Social_Tags_Twitter implements Shi_Social_Tags_Interface {
    
    /**
     * Helper class instance
     * @var object
     */
    private $helper;
    
    /**
     * Card type
     * @var string
     */
    private $card;
    
    /**
     * Twitter username for linked analytics
     * @var string
     */
    private $site;
    
    /**
     * Post type title
     * @var string
     */
    private $title;
    
    /**
     * Post excerpt or content. If product than product short description or content
     * @var string
     */
    private $description;
    
    /**
     * Allowed description lenght in characters
     * @var int
     */
    private $description_length = 157;
    
    /**
     * Post type thumbanil url
     * @var string
     */
    private $image;
    
    /**
     * Set helper and add wp_head hook
     */
    public function add_head_hook() {
        global $pbtheme_data,$post;
        $post_og_option = get_post_meta($post->ID,'pbtheme_ogmeta',true);
        if($post_og_option == 'enabled' || ($post_og_option != 'disabled' && $pbtheme_data['enable_ogmeta'] == 1) ){
          $this->helper = new Shi_Social_Tags_Helper();
          add_action( 'wp_head', array( $this, 'add_to_head' ), 1 );
        }
    }
    
    /**
     * Echo meta tags in head
     */
    public function add_to_head() {
        
        $fragments = $this->get_fragments();
        $meta_html = '<!-- Twitter Card data -->';
        
        foreach ( $fragments as $fragment ) {
            $meta_html .= $this->create_meta( $fragment[ 'name' ], $fragment[ 'content' ] );
        }

        echo $meta_html;
        
    }
    
    /**
     * Create meta tag
     * 
     * @param string $name Name value for meta tag
     * @param string $content Content value for meta tag
     * @return string
     */
    private function create_meta( $name, $content ) {
        return '<meta name="' . esc_attr( $name ) . '" content="' . strip_tags( $content ) . '">';
    }
    
    /**
     * Set class properties and all atts for meta tags creation
     * @return array
     */
    private function get_fragments() {
        global $post;
        
        $fragments = array();
        $this->set_card();
        $this->set_site();
        $this->set_title();
        $this->set_description();
        $this->set_image();
        
        $post_og_title_custom = get_post_meta($post->ID,'pbtheme_ogmeta_title',true);
        if(strlen($post_og_title_custom)>0){
          $this->title=$post_og_title_custom;
        }
        $post_og_desc_custom = get_post_meta($post->ID,'pbtheme_ogmeta_description',true);
        if(strlen($post_og_desc_custom)>0){
          $this->description=$post_og_desc_custom;
        }
        $post_og_image_custom = get_post_meta($post->ID,'pbtheme_ogmeta_image',true);
        if(strlen($post_og_image_custom)>0){
          $this->image=$post_og_image_custom;
        }
        
        
        if ( !empty( $this->card ) ) {
            $fragments[] = array(
                'name'  => 'twitter:card',
                'content'   => $this->card
            );
        }
        
        if ( !empty( $this->site ) ) {
            $fragments[] = array(
                'name'  => 'twitter:site',
                'content'   => $this->site
            );
        }
        
        if ( !empty( $this->title ) ) {
            $fragments[] = array(
                'name'  => 'twitter:title',
                'content'   => $this->title
            );
        }
        
        if ( !empty( $this->description ) ) {
            $fragments[] = array(
                'name'  => 'twitter:description',
                'content'   => $this->description
            );
        }
        
        if ( !empty( $this->image ) ) {
            $fragments[] = array(
                'name'  => 'twitter:image',
                'content'   => $this->image
            );
        }
        
        return $fragments;
        
    }
    
    /**
     * Set cart type
     */
    private function set_card() {
        $this->card = 'summary';
    }
    
    /**
     * The Twitter @username the card should be attributed to. 
     * 
     * @return string Twitter username from user meta
     */
    private function set_site() {
        
        global $post;
        // Return empty string if not found user_meta key
        $username = get_the_author_meta( 'twitter_username', $post->post_author );
        if ( strpos( $username, '@') === false ) {
            $this->site = '@' . $username;
        } else {
            $this->site = $username;
        }
        

    }
    
    /**
     * Set title
     */
    private function set_title() {
        $this->title = $this->helper->set_title();
    }
    
    /**
     * Set description
     */
    private function set_description() {
        $this->description = $this->helper->set_description(  $this->description_length  );
    }

    /**
     * Set image
     */
    private function set_image() {
        $this->image = $this->helper->set_image();
    }

}