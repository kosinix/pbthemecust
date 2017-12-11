<?php

/**
 * Class responsible for Facebook and Google+ Open Graph tags
 * 
 * @link http://ogp.me/
 * @link https://developers.facebook.com/docs/sharing/webmasters#markup
 */
class Shi_Social_Tags_Facebook implements Shi_Social_Tags_Interface {
    
    /**
     * Helper class instance
     * @var object
     */
    private $helper;
    
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
    private $description_length = 297;
    
    /**
     * Object type: Product, Article or Page
     * @var string
     */
    private $type;
    
    /**
     * Post type permalink
     * @var string
     */
    private $url;
    
    /**
     * Return site name from General settings
     * @return string
     */
    private $site_name;
    
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
          add_filter( 'language_attributes', array( $this, 'add_opengraph_namespace' ), 15 );
        }
    }
    
    /**
     * Echo meta tags in head
     */
    public function add_to_head() {
        
        $fragments = $this->get_fragments();
        $meta_html = '<!-- Open Graph data -->';
        
        foreach ( $fragments as $fragment ) {
            $meta_html .= $this->create_meta( $fragment[ 'property' ], $fragment[ 'content' ] );
        }

        echo $meta_html;
        
    }
    
    /**
     * Create meta tag
     * 
     * @param string $property Property value for meta tag
     * @param string $content Content value for meta tag
     * @return string
     */
    private function create_meta( $property, $content ) {
        return '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '" >';
    }
    
    /**
     * Set class properties and all atts for meta tags creation
     * @return array
     */
    private function get_fragments() {
        global $post;
        
        $fragments = array();
        $this->set_title();
        $this->set_description();
        $this->set_type();
        $this->set_url();
        $this->set_site_name();
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
        
        
        
        if ( !empty( $this->title ) ) {
            $fragments[] = array(
                'property'  => 'og:title',
                'content'   => $this->title
            );
        }
        
        if ( !empty( $this->description ) ) {
            $fragments[] = array(
                'property'  => 'og:description',
                'content'   => $this->description
            );
        }
        
        if ( !empty( $this->type ) ) {
            $fragments[] = array(
                'property'  => 'og:type',
                'content'   => $this->type
            );
        }
        
        if ( !empty( $this->url ) ) {
            $fragments[] = array(
                'property'  => 'og:url',
                'content'   => $this->url
            );
        }
        
        if ( !empty( $this->site_name ) ) {
            $fragments[] = array(
                'property'  => 'og:site_name',
                'content'   => $this->site_name
            );
        }
        
        if ( !empty( $this->image ) ) {
            $fragments[] = array(
                'property'  => 'og:image',
                'content'   => $this->image
            );
        }
        
        return $fragments;
        
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
        $this->description = $this->helper->set_description( $this->description_length );
    }
    
    /**
     * Set Open Graph object type
     */
    private function set_type() {
        
        switch ( get_post_type() ) {
            
            case 'post':
                $this->type = 'article';
                break;
            
            case 'product':
                $this->type = 'product';
                break;

            default:
                $this->type = 'page';
                break;
            
        }
        
    }
    
    /**
     * Set url
     */
    private function set_url() {
        $this->url = $this->helper->set_url();
    }
    
    /**
     * Set site name
     */
    private function set_site_name() {
        $this->site_name = $this->helper->set_site_name();
    }
    
    /**
     * Set image
     */
    private function set_image() {
        $this->image = $this->helper->set_image();
    }
    
    /**
	 * Filter for the namespace, adding the OpenGraph namespace.
	 *
	 * @link https://developers.facebook.com/docs/web/tutorials/scrumptious/open-graph-object/
	 *
	 * @param string $input The input namespace string.
	 *
	 * @return string
	 */
	public function add_opengraph_namespace( $input ) {

		$namespaces = array(
			'og: http://ogp.me/ns#',
		);

		$namespace_string = implode( ' ', $namespaces );

		if ( strpos( $input, ' prefix=' ) !== false ) {
			$regex   = '`prefix=([\'"])(.+?)\1`';
			$replace = 'prefix="$2 ' . $namespace_string . '"';
			$input   = preg_replace( $regex, $replace, $input );
		}
		else {
			$input .= ' prefix="' . $namespace_string . '"';
		}

		return $input;
	}

}