<?php
/**
 * Product quantity inputs
 *
 * This template overrides the default WooCommerce global/quantity-input.php.
 * It provides the Aurika luxury horizontal [ − ] 1 [ + ] pill across all product types (simple, variable) and cart.
 *
 * @package Aurika
 * @version 7.4.0
 */

defined( 'ABSPATH' ) || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );

?>
<div class="quantity product-qty-wrap">
	<?php
	/**
	 * Hook to output something before the quantity input field
	 *
	 * @since 7.2.0
	 */
	do_action( 'woocommerce_before_quantity_input_field' );
	?>
	<button type="button" class="qty-btn qty-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'aurika' ); ?>">&#8722;</button>
	<input
		type="<?php echo esc_attr( $type ); ?>"
		<?php echo $readonly ? 'readonly="readonly"' : ''; ?>
		id="<?php echo esc_attr( $input_id ); ?>"
		class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
		name="<?php echo esc_attr( $input_name ); ?>"
		value="<?php echo esc_attr( $input_value ); ?>"
		aria-label="<?php echo esc_attr( $label ); ?>"
		size="4"
		min="<?php echo esc_attr( $min_value ); ?>"
		max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
		<?php if ( ! $readonly ) : ?>
			step="<?php echo esc_attr( $step ); ?>"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			inputmode="<?php echo esc_attr( $inputmode ); ?>"
			autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
		<?php endif; ?>
	/>
	<button type="button" class="qty-btn qty-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'aurika' ); ?>">&#43;</button>
	<?php
	/**
	 * Hook to output something after the quantity input field
	 *
	 * @since 7.2.0
	 */
	do_action( 'woocommerce_after_quantity_input_field' );
	?>
</div>
