<?php
/**
 * Single variation cart button
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

global $pbtheme_data;        
                        
// cmb2 fields     
$product_color = !empty( get_post_meta( $product->id, '_selosity_product_color', true ) ) ? get_post_meta( $product->id, '_selosity_product_color', true ) : $pbtheme_data['theme_color'];
$sec_color = !empty( get_post_meta( $product->id, '_selosity_product_color_sec', true ) ) ? get_post_meta( $product->id, '_selosity_product_color_sec', true ) : 'white';
$add_text = !empty( get_post_meta( $product->id, '_selosity_add_cart', true ) ) ? get_post_meta( $product->id, '_selosity_add_cart', true ) : $pbtheme_data['woo_addtocart'] ;
                
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php if ( ! $product->is_sold_individually() ) : ?>
		<?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
	<?php endif; ?>
	<button type="submit" class="single_add_to_cart_button button alt" style="background: <?php echo $product_color;?>; color: <?php echo $sec_color; ?>;"><?php echo esc_html( $add_text ); ?></button>
	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->id ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->id ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>