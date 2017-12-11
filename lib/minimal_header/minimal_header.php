<?php

/**
 * Minimal header
 * 
 * If activated it will disable menu and topbar and add minimal header on it's place
 * 
 * Added in header.php to pbtheme_minimal_header action hook
 */

if ( ! class_exists( 'CMB2' ) ) {
    require get_template_directory() . '/cmb2/init.php';
}

class Pbtheme_Minimal_Header {
    
    private $root_url;
    
    // cmb2 fields in metabox
    private $cmb2_prefix = '_pbtheme_minimal_header_'; // CMB2 framework prefix
    private $cmb2_enabled_field; // On / Off minimal header from metabox
    private $cmb2_cart_field;
    private $cmb2_myaccount_field; 
    private $cmb2_search_field; 
    private $cmb2_categories_field; 
    private $cmb2_title_field; 
    private $cmb2_additional_text_field; 
    private $cmb2_img_field; 
    
    private $min_header_enabled = 'off'; // Check if to add filters on wp hook and set value to avoid double call later
    
    /**
     * Constructor
     */
    public function __construct() {
        
        add_action( 'init', array( $this, 'init' ) );
    }
    
    public function init() {
        
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        // Set all vars
        $this->root_url = get_template_directory_uri() . '/lib/minimal_header/';
        $this->cmb2_enabled_field = $this->cmb2_prefix . 'enabled';
        $this->cmb2_cart_field = $this->cmb2_prefix . 'cart';
        $this->cmb2_myaccount_field = $this->cmb2_prefix . 'myaccount';
        $this->cmb2_search_field = $this->cmb2_prefix . 'search';
        $this->cmb2_categories_field = $this->cmb2_prefix . 'categories';
        $this->cmb2_title_field = $this->cmb2_prefix . 'title';
        $this->cmb2_additional_text_field = $this->cmb2_prefix . 'additional_text';
        $this->cmb2_img_field = $this->cmb2_prefix . 'image';
        
        // Set metaboxes
        $this->init_cmb2();
        
        // Add frontend actions
        add_action( 'wp', array( $this, 'init_header' ) );
        
    }
    
    /**
     * Setup metaboxes
     */
    public function init_cmb2() {
        
        add_action( 'cmb2_admin_init', array( $this, 'cmb2_metaboxes' ) );
        
    }
    
    /**
     * Setup frontend minimal header on product page
     */
    public function init_header() {

        if ( !is_admin() && is_product() ) {
            
            $this->min_header_enabled = get_post_meta( get_the_ID(), $this->cmb2_enabled_field, true );
        
            if ( $this->min_header_enabled !== 'on' ) {
                return;
            }  
            
            add_filter( 'pbtheme_header_elements_myaccount_text', array( $this, 'myaccount_text' ) );
            add_filter( 'pbtheme_hide_menu', array( $this, 'hide_menu' ) );
            add_filter( 'pbtheme_hide_topbar', array( $this, 'hide_topbar' ) );

            add_action( 'pbtheme_minimal_header', array( $this, 'render_content' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 1005 );
        }
        
    }
    
    /**
     * Remove my account text
     */
    public function myaccount_text( $text ) {
        return '';
    }
    
    /**
     * Override and hide menu
     */
    public function hide_menu( $text ) {
        return '1';
    }
    
    /**
     * Override and hide topbar
     */
    public function hide_topbar( $text ) {
        return '1';
    }
    
    /**
     * Render minimal header
     */
    public function render_content() {
        
        global $pbtheme_data;

        $pbtheme_data['header-top-right'] = array(
            'disabled' => array(
                'placebo' => 'placebo',
                'login-link' => 'login-link',
                'language-bar' => 'language-bar',
                'menu' => 'menu',
                'network-icons' => 'network-icons',
                'tagline' => 'tagline',
                'tagline-alt' => 'tagline-alt',
                'woo-login-link' => 'woo-login-link',
                'woo-cart' => 'woo-cart'
            ),
            "enabled" => array(
                'woo-login-link' => 'woo-login-link',
                'woo-cart' => 'woo-cart'
            )
        );

        // Boolean
        $cart_enabled = get_post_meta( get_the_ID(), $this->cmb2_cart_field, true ) === 'on';
        $myaccount_enabled = get_post_meta( get_the_ID(), $this->cmb2_myaccount_field, true ) === 'on';
        $search_enabled = get_post_meta( get_the_ID(), $this->cmb2_search_field, true ) === 'on';
        $categories_enabled = get_post_meta( get_the_ID(), $this->cmb2_categories_field, true ) === 'on';
        $title_enabled = get_post_meta( get_the_ID(), $this->cmb2_title_field, true ) === 'on';
        
        // Values
        $additional_text = get_post_meta( get_the_ID(), $this->cmb2_additional_text_field, true );
        $image = get_post_meta( get_the_ID(), $this->cmb2_img_field, true );
        
        
        // Process built in elements
        if ( ! $cart_enabled ) {
            unset( $pbtheme_data['header-top-right']['enabled']['woo-cart'] );
        }
        
        if ( ! $myaccount_enabled ) {
            unset( $pbtheme_data['header-top-right']['enabled']['woo-login-link'] );
        }
        

        /* Start output */
        ?>
        <div class="pbtheme-min-header">

            <div class="pbtheme_container_max">
                <div class="pbtheme-min-header__top pbtheme_header_font">

                    <div class="pbtheme-min-header__left pbtheme_top_left">
                        <div class="pbtheme-min-header__left-img">
                            <?php 
                            // If image selected in metbox
                            if ( !empty( $image ) ) { ?>
                            <img src="<?php echo esc_url( $image ); ?>" />
                            <?php } ?>
                        </div>
                        <div class="pbtheme-min-header__left-text">
                            <h2>
                                <?php 
                                // The product title
                                if ( $title_enabled ) {
                                    the_title(); 
                                }
                                ?>
                            </h2>
                            <h3>
                                <?php  
                                // If custom text entered in metabox
                                echo esc_html( $additional_text ); ?>
                            </h3>
                        </div>

                        <div class="clearfix"></div>
                    </div>

                    <div class="pbtheme-min-header__right pbtheme_top_right">
                        
                    <?php
                    // If search enabled in metabox
                    if ( $categories_enabled ) { ?>
                    <div id="pbtheme-min-header-cats" class="float_right">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        <?php echo $this->create_categories(); ?>
                    </div>
                    
                    <?php } ?>
                    
                    <?php
                    // If search enabled in metabox
                    if ( $search_enabled ) { ?>
                    <div id="pbtheme-min-header-search"  class="float_right">
                        <span><i class="fa fa-search" aria-hidden="true"></i></span>
                        <div class="pbtheme_search">
                           <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                               <div>
                                   <input type="text" value="<?php echo esc_html( get_search_query() ); ?>" name="s" id="s" placeholder="<?php esc_html_e( 'Search the products', 'pbtheme' ); ?>" />
                                   <button type="submit" value="Search"><i class="divicon-search"></i></button>
                                   <input type="hidden" name="post_type" value="product"/>
                               </div>
                           </form>
                       </div>
                    </div><!-- #pbtheme-min-header-search -->
                    <?php } ?>
                    
                    <?php
                    // Elements used from the theme are checked above if enabled
                    pbtheme_header_elements('right'); 
                    ?>
                       
                    </div><!-- .pbtheme-min-header-right -->
                    <div class="clearfix"></div>
                </div><!-- .pbtheme_top -->
            </div><!-- .pbtheme_container_max -->
        </div><!-- .header_wrapper -->
        <?php
        
    }
    
    /**
     * Return categories html for popup on burger click
     */
    private function create_categories() {

        $output = '';

        $product_cats = get_terms('product_cat', array('hide_empty' => 0, 'parent' => 0)); 

        $output = '<div class="pbtheme-min-header-cats">';
        $output     .= '<div class="pbtheme-min-header-cats-wrapper">';
        
        foreach( $product_cats as $category ) {
            $output .= '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
        }
        
        $output     .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
    * Define the metabox and field configurations.
    */
    public function cmb2_metaboxes() {
        
        /**
         * Initiate metabox
         */
        $cmb = new_cmb2_box(array(
            'id' => 'pbtheme_minimal_header_metabox',
            'title' => __('Minimal header options', 'pbtheme'),
            'object_types' => array( 'product' ), // Post type
            'context' => 'side',
            'priority' => 'default',
            'show_names' => true, // Show field names on the left
        ));

        $cmb->add_field(array(
            'name' => __('Enable or disable minimal header', 'pbtheme'),
            'desc' => __('This will override remove menu and top bar.', 'pbtheme'),
            'id' => $this->cmb2_enabled_field,
            'type' => 'radio_inline',
            'default' => 'off',
            'options' => array(
                'on' => __('Enable', 'pbtheme'),
                'off' => __('Disable', 'pbtheme')
            ),
        ));
        
        $cmb->add_field(array(
            'name' => __('Cart', 'pbtheme'),
            'id' => $this->cmb2_cart_field,
            'type' => 'radio_inline',
            'default' => 'on',
            'options' => array(
                'on' => __('Enable', 'pbtheme'),
                'off' => __('Disable', 'pbtheme')
            ),
        ));
        
        $cmb->add_field(array(
            'name' => __('My account', 'pbtheme'),
            'id' => $this->cmb2_myaccount_field,
            'type' => 'radio_inline',
            'default' => 'on',
            'options' => array(
                'on' => __('Enable', 'pbtheme'),
                'off' => __('Disable', 'pbtheme')
            ),
        ));
        
        $cmb->add_field(array(
            'name' => __('Search', 'pbtheme'),
            'id' => $this->cmb2_search_field,
            'type' => 'radio_inline',
            'default' => 'on',
            'options' => array(
                'on' => __('Enable', 'pbtheme'),
                'off' => __('Disable', 'pbtheme')
            ),
        ));
        
        $cmb->add_field(array(
            'name' => __('Categories', 'pbtheme'),
            'id' => $this->cmb2_categories_field,
            'type' => 'radio_inline',
            'default' => 'on',
            'options' => array(
                'on' => __('Enable', 'pbtheme'),
                'off' => __('Disable', 'pbtheme')
            ),
        ));
        
        $cmb->add_field(array(
            'name' => __('Title', 'pbtheme'),
            'id' => $this->cmb2_title_field,
            'type' => 'radio_inline',
            'default' => 'on',
            'options' => array(
                'on' => __('Enable', 'pbtheme'),
                'off' => __('Disable', 'pbtheme')
            ),
        ));
        
        $cmb->add_field(array(
            'name' => __('Additional text', 'pbtheme'),
            'desc' => __('Additional text below title in minimal header', 'pbtheme'),
            'id' => $this->cmb2_additional_text_field,
            'type' => 'text',
            'default' => '',
        ));
        
        $cmb->add_field(array(
            'name' => __('Custom image', 'pbtheme'),
            'desc' => __('Custom image left of title in minimal header', 'pbtheme'),
            'id' => $this->cmb2_img_field,
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Add image' // Change upload button text. Default: "Add or Upload File"
            ),
        ));

    }
    
    /**
     * Add scripts
     */
    public function scripts() {
        wp_enqueue_script( 'pb-theme-minimal-header', $this->root_url . 'assets/main.js', array( 'pb-theme-child-custom-scrollbar' ), '1.0.0', true );
    }
    
}
new Pbtheme_Minimal_Header();