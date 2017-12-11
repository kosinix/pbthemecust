<?php
/**
 * Change every product metadata and set theme options chosen value
 * This is done for one setting in theme options, not for all at once 
 *
 * @since 3.1.7
 */
class Pb_Theme_Force_Product_Metadata {
    
    /**
     * Theme option value to be forced
     * 
     * @var mixed Value of the theme option
     */
    private $value;
    
    /**
     * Product meta key
     * 
     * @var string Meta key for the product to override
     */
    private $product_meta_key;
    
    /**
     * Prefix from the CMB2 init
     * 
     * @var string
     */
    private $prefix = '_selosity_';
    
    /**
     * Constructor
     * 
     * @param string Theme option id
     * @param mixed Theme option value
     */
    public function __construct( $option_id, $value ) {
        $this->value = $this->maybe_convert_value( $option_id, $value );
        $this->product_meta_key = $this->get_product_meta_key( $option_id );
    }
    
    /**
     * Update all products meta key with theme option value
     * 
     * @return boolean
     */
    public function update_value_for_products() {

        // If no meta key found
        if ( empty( $this->product_meta_key ) ) {
            return false;
        }
        
        // Get all store products ids
        $product_ids = $this->get_all_products();
        
        // Check if we have some products
        if ( ! is_array( $product_ids->posts ) || count( $product_ids->posts ) < 1 ) {
            return false;
        }
        
        // Set value for each product
        foreach ( $product_ids->posts as $product_id ) {
            $this->update_product_meta( $product_id, $this->value );
        }
        
        return true;
    }
    
    /**
     * Return product meta key equvivalent to theme option
     * 
     * @param string $option_id
     * @return mixed False if no match or meta key
     */
    private function get_product_meta_key( $option_id ) {
        
        /**
         * Enter for which theme option case to return product meta key to use
         */
        switch ( $option_id ) {
            case 'woo_meta_data':
                return $this->prefix . 'enable_meta_data';
                break;
            
            case 'woo_soc_sharing':
                return $this->prefix . 'enable_soc_share';
                break;
            
            case 'woo_product_descp':
                return $this->prefix . 'enable_descp';
                break;
            
            case 'woo_product_addinfo':
                return $this->prefix . 'enable_addinfo';
                break;
            
            case 'woo_product_reviews':
                return $this->prefix . 'enable_review';
                break;
            
            case 'woo_single_related':
                return $this->prefix . 'enable_related';
                break;
            
            case 'woo_enable_breadcrumbs':
                return $this->prefix . 'enable_breadcrumbs';
                break;
            
            case 'woo_addtocart':
                return $this->prefix . 'add_cart';
                break;

            default:
                return false;
                break;
        }
        
    }
    
    /**
     * Convert boolean true value to 'on' string needed for setting CMB2 checkbox value
     * This is limited only to predefined settings ids in function
     * Note: Checkboxes in CMB2 return 'on' or ''
     * 
     * @param type $value
     * @return type
     */
    private function maybe_convert_value( $option_id, $value ) {
        
        $checkboxes_array = array(
            'woo_meta_data',
            'woo_soc_sharing',
            'woo_product_descp',
            'woo_product_addinfo',
            'woo_product_reviews',
            'woo_single_related',
            'woo_enable_breadcrumbs',
        );
        
        if ( in_array( $option_id, $checkboxes_array ) ) {
            $value = !empty( $value ) ? 'on' : '';
        }
        
        return $value;
        
    }
    
    /**
     * Return products WP_Query
     * 
     * @return WP_Query
     */
    private function get_all_products() {
        
        $args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
            'nopaging' => true,
            'fields' => 'ids'
			);
		return new WP_Query( $args );
        
    }
    
    /**
     * Update product meta
     * 
     * @param int $product_id
     * @param mixed $value
     */
    private function update_product_meta( $product_id, $value ) {
        update_post_meta( $product_id, $this->product_meta_key, $value );
    }
    
    /**
     * Force setting button to use when declaring theme options
     * Just concatenate to "desc" param
     * 
     * @param string $text Button text (optional)
     * @return string Div holding button and loader
     */
    public static function force_settings_button( $text = '' ) {
        
        if ( empty( $text ) ) {
            $text = esc_html__( 'Force Setting', 'pbtheme' );
        }
        
        return '<div class="force_product_setting-wrapper">'
                . '<img style="display:none" src="' . ADMIN_DIR . 'assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />'
                . '<button type="button" class="of_force_product_setting button-primary">' . esc_html( $text ) . '</button>'
            . '</div>';
        
    }
    
    public static function force_settings_description() {
        
        return '<p class="of_force_settings_desc">' . esc_html__('* This field is used to setup option for newly created products.', 'pbtheme') . '</p>'
            . '<p class="of_force_settings_desc">' . esc_html__('* Use Force setting button to force this setting for all products.', 'pbtheme') . '</p>';
        
    }
}
