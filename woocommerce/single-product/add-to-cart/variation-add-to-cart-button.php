<?php
// cmb2 fields
$sec_color = get_post_meta( $product->id, '_selosity_product_color_sec', true ) ? get_post_meta( $product->id, '_selosity_product_color_sec', true ) : 'white';
$add_text = get_post_meta( $product->id, '_selosity_add_cart', true ) ? get_post_meta( $product->id, '_selosity_add_cart', true ) : $pbtheme_data['woo_addtocart'] ;
?>

<div class="woocommerce-variation-add-to-cart variations_button">
	<button type="submit" class="single_add_to_cart_button button alt" style="background: <?php echo $product_color;?>; color: <?php echo $sec_color; ?>;"><?php echo esc_html( $add_text ); ?></button>
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>