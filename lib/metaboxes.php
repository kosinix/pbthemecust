<?php

/**
 * Addes metaboxes 
 */

require_once dirname( dirname(__FILE__) ) . '/cmb2/init.php';

if(class_exists("sellosityauthlicense")){
    add_action('cmb2_admin_init', 'pb_theme_cmb2_metaboxes');
}

/**
 * Only return default value if we don't have a post ID (in the 'post' query variable)
 *
 * @param  bool  $default On/Off (true/false)
 * @return mixed          Returns true or '', the blank default
 */
function cmb2_set_checkbox_default_for_new_post( $default ) {
    return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}

/**
 * Define the metabox and field configurations.
 */
function pb_theme_cmb2_metaboxes()
{

    global $pbtheme_data;

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_selosity_';

    /**
     * Initiate the metabox
     */
    $cmb = new_cmb2_box(array(
        'id' => 'product_metabox',
        'title' => __('Sellosity settings', 'pbtheme'),
        'object_types' => array('product',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

    // Product color text field
    $cmb->add_field(array(
        'name' => 'Button Color',
        'id' => $prefix . 'product_color',
        'type' => 'colorpicker',
        'default' => $pbtheme_data['theme_color'],
    ));

    // Product font color text field
    $cmb->add_field(array(
        'name' => 'Text Color On Button',
        'id' => $prefix . 'product_color_sec',
        'type' => 'colorpicker',
        'default' => '#ffffff',
    ));

    // Add to cart text field
    $cmb->add_field(array(
        'name' => __('Button Text', 'pbtheme'),
        'id' => $prefix . 'add_cart',
        'type' => 'text',
        'default' => isset( $pbtheme_data['woo_addtocart'] ) ? $pbtheme_data['woo_addtocart'] : esc_html__('Add to cart', 'pbtheme'),
    ));
 
    $cmb->add_field(array(
        'name' => 'Enable Meta Data: SKU, Tags...',
        'desc' => 'Select your option',
        'id' => $prefix . 'enable_meta_data',
        'type' => 'checkbox',
        'default' => cmb2_set_checkbox_default_for_new_post( isset( $pbtheme_data['woo_meta_data'] ) ? $pbtheme_data['woo_meta_data'] : true ),
    ));


    $cmb->add_field(array(
        'name' => 'Enable Social Sharing',
        'desc' => 'Select your option',
        'id' => $prefix . 'enable_soc_share',
        'type' => 'checkbox',
        'default' => cmb2_set_checkbox_default_for_new_post( isset( $pbtheme_data['woo_soc_sharing'] ) ? $pbtheme_data['woo_soc_sharing'] : true ),
    ));

    // Description
    $cmb->add_field(array(
        'name' => 'Enable Description',
        'desc' => 'Select your option',
        'id' => $prefix . 'enable_descp',
        'type' => 'checkbox',
        'default' => cmb2_set_checkbox_default_for_new_post( isset( $pbtheme_data['woo_product_descp'] ) ? $pbtheme_data['woo_product_descp'] : true ),
    ));
    
    // Description
    $cmb->add_field(array(
        'name' => 'Enable Additional information',
        'desc' => 'Select your option',
        'id' => $prefix . 'enable_addinfo',
        'type' => 'checkbox',
        'default' => cmb2_set_checkbox_default_for_new_post( isset( $pbtheme_data['woo_product_addinfo'] ) ? $pbtheme_data['woo_product_addinfo'] : true ),
    ));
    
    // Reviews
    $cmb->add_field(array(
        'name' => 'Enable Reviews',
        'desc' => 'Select your option',
        'id' => $prefix . 'enable_review',
        'type' => 'checkbox',
        'default' => cmb2_set_checkbox_default_for_new_post( isset( $pbtheme_data['woo_product_reviews'] ) ? $pbtheme_data['woo_product_reviews'] : true ),
    ));

    // Related Products
    $cmb->add_field(array(
        'name' => 'Enable Related Products',
        'desc' => 'Select your option',
        'id' => $prefix . 'enable_related',
        'type' => 'checkbox',
        'default' => cmb2_set_checkbox_default_for_new_post( isset( $pbtheme_data['woo_single_related'] ) ? $pbtheme_data['woo_single_related'] : true ),
    ));
    
    // Related Products
    $cmb->add_field(array(
        'name' => esc_html__( 'Hide breadcrumbs', 'pbtheme' ),
        'desc' => esc_html__( 'Select your option', 'pbtheme' ),
        'id' => $prefix . 'enable_breadcrumbs',
        'type' => 'checkbox',
        'default' => cmb2_set_checkbox_default_for_new_post( isset( $pbtheme_data['woo_single_related'] ) ? $pbtheme_data['woo_single_related'] : true ),
    ));

    // Add other metaboxes as needed
    
    /**
     * Added by Shindiri Studio
     */
    // Single add to cart padding top
    $cmb->add_field(array(
        'name' => 'Add to cart on single product padding top',
        'desc' => __('Enter value without sufix. Example: 30', 'pbtheme'),
        'id' => $prefix . 'single_add_to_cart_pt',
        'default' => '20',
        'type' => 'text'
    ));
    
    $cmb->add_field(array(
        'name' => 'Add to cart on single product padding bottom',
        'desc' => __('Enter value without sufix. Example: 30', 'pbtheme'),
        'id' => $prefix . 'single_add_to_cart_pb',
        'default' => '20',
        'type' => 'text'
    ));

}

function js_add_custom_css_for_metabox($post_id, $cmb)
{
    
    ?>
    <style type="text/css" media="screen">
        #test_metabox .regular-text {
            width: 99%;
        }
    </style>
    <?php
}

add_action('cmb2_after_post_form_custom_css_test', 'js_add_custom_css_for_metabox', 10, 2);