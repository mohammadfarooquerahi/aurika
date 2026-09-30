<?php
/**
 * Custom WooCommerce Product Card Template
 * Exactly matches Hunza Candle (hunzacandle.com)
 *
 * @package Aurika
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$product_id   = $product->get_id();
$product_link = get_permalink( $product_id );
$title        = function_exists( 'aurika_clean_product_text' ) ? aurika_clean_product_text( get_the_title() ) : get_the_title();
$price_html   = $product->get_price_html();

// Merge DIY Art Kit duplicate products into a single card
$is_diy_8 = ( stripos( $title, '8 Concrete Toys' ) !== false || strpos( $product->get_slug(), '8-concrete' ) !== false );
if ( $is_diy_8 ) {
	return; // Skip second card so DIY Art Kit shows as a single unified product
}
$is_diy = ( stripos( $title, 'DIY' ) !== false || stripos( $title, 'Art Kit' ) !== false || has_term( array( 'diy-art-kits', 'art-kits' ), 'product_cat', $product_id ) );
if ( $is_diy ) {
	$title = 'Aurika DIY Art Kit';
	$price_html = '<span class="price"><span class="woocommerce-Price-amount amount">Rs. 299 &ndash; Rs. 599</span></span>';
}

$rating       = $product->get_average_rating();
$review_count = $product->get_review_count();
$rating_val   = ( $rating > 0 ) ? round( $rating ) : 5;
$reviews_num  = max( 1, (int) $review_count );
$is_on_sale   = $product->is_on_sale();
?>
<li <?php wc_product_class( 'hunza-product-card', $product ); ?>>
	<div class="hunza-card-inner">
		
		<!-- Image Container -->
		<div class="hunza-card-image-wrap">
			<?php if ( $is_on_sale ) : ?>
				<span class="hunza-badge-sale">SALE</span>
			<?php endif; ?>

			<a href="<?php echo esc_url( $product_link ); ?>" class="hunza-card-img-link" tabindex="-1">
				<?php
				if ( has_post_thumbnail( $product_id ) ) {
					echo get_the_post_thumbnail( $product_id, 'woocommerce_thumbnail', array(
						'class'   => 'hunza-card-img',
						'loading' => 'lazy',
						'alt'     => esc_attr( $title ),
					) );
				} else {
					$card_img = '';
					$gallery = $product ? $product->get_gallery_image_ids() : array();
					if ( ! empty( $gallery ) ) {
						$card_img = wp_get_attachment_image_url( $gallery[0], 'woocommerce_thumbnail' );
					}
					if ( empty( $card_img ) ) {
						if ( stripos( $title, 'Sea Shell' ) !== false ) {
							$card_img = 'https://images.unsplash.com/photo-1605651202774-7d573fd3f12d?q=80&w=800';
						} elseif ( stripos( $title, 'DIY' ) !== false || stripos( $title, 'Art' ) !== false ) {
							$card_img = 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?q=80&w=800';
						} elseif ( stripos( $title, 'Peony' ) !== false ) {
							$card_img = 'https://images.unsplash.com/photo-1603006905003-be475563bc59?q=80&w=800';
						} elseif ( stripos( $title, 'Daisy' ) !== false ) {
							$card_img = 'https://images.unsplash.com/photo-1598880940371-c756e015fea1?q=80&w=800';
						} elseif ( stripos( $title, 'Heart' ) !== false ) {
							$card_img = 'https://images.unsplash.com/photo-1572726729207-a78d6feb18d7?q=80&w=800';
						} else {
							$card_img = 'https://images.unsplash.com/photo-1602874801007-bd458bb1b8b8?q=80&w=800';
						}
					}
					echo '<img src="' . esc_url( $card_img ) . '" class="hunza-card-img" alt="' . esc_attr( $title ) . '" loading="lazy">';
				}
				?>
			</a>

			<!-- Hover Action Buttons (Hunza Candle Style) -->
			<div class="hunza-card-actions">
				<!-- Quick View / Direct Link -->
				<a href="<?php echo esc_url( $product_link ); ?>" class="hunza-action-btn hunza-btn-view" title="View Product" aria-label="View <?php echo esc_attr( $title ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
						<circle cx="12" cy="12" r="3"></circle>
					</svg>
				</a>

				<!-- Add to Cart Button -->
				<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
					<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
					   data-quantity="1"
					   data-product_id="<?php echo esc_attr( $product_id ); ?>"
					   data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
					   class="hunza-action-btn hunza-btn-cart add_to_cart_button ajax_add_to_cart"
					   title="Add to Cart"
					   aria-label="Add <?php echo esc_attr( $title ); ?> to cart">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="9" cy="21" r="1"></circle>
							<circle cx="20" cy="21" r="1"></circle>
							<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
						</svg>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<!-- Info Container -->
		<div class="hunza-card-info">
			<h3 class="hunza-card-title">
				<a href="<?php echo esc_url( $product_link ); ?>"><?php echo esc_html( $title ); ?></a>
			</h3>

			<!-- Star Rating + Reviews Count (Hunza Candle Style) -->
			<div class="hunza-card-rating">
				<div class="hunza-stars">
					<?php for ( $s = 1; $s <= 5; $s++ ) : ?>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="<?php echo $s <= $rating_val ? '#f59e0b' : '#e5e7eb'; ?>"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
					<?php endfor; ?>
				</div>
				<span class="hunza-review-text"><?php echo esc_html( $reviews_num . ' review' . ( $reviews_num > 1 ? 's' : '' ) ); ?></span>
			</div>

			<!-- Price -->
			<div class="hunza-card-price">
				<?php echo $price_html; ?>
			</div>
		</div>

	</div>
</li>
