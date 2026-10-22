<?php
/**
 * Aurika Complete Google Analytics 4 (GA4) & Performance Tracking Engine
 *
 * Provides full, turnkey Google Analytics 4 integration with:
 * - Official Google Tag (gtag.js) for pageviews, visitors, sessions, and traffic sources
 * - Full WooCommerce Enhanced eCommerce tracking (view_item, view_item_list, add_to_cart, begin_checkout, purchase)
 * - Anti-duplicate purchase revenue protection
 * - Admin visit exclusion (prevents store owner from skewing visitor stats)
 * - Optional Google Tag Manager (GTM) support
 * - Custom Header & Footer tracking script injection (Meta Pixel, TikTok Pixel, Ads tags)
 * - Admin settings page under Settings → Google Analytics & Customizer controls
 *
 * @package Aurika
 */

defined( 'ABSPATH' ) || exit;

// ─────────────────────────────────────────────────────────────
// 1. HELPER FUNCTIONS
// ─────────────────────────────────────────────────────────────

/**
 * Get sanitized GA4 Measurement ID
 */
function aurika_get_ga4_id() {
	$id = get_theme_mod( 'aurika_ga4_measurement_id', '' );
	$id = strtoupper( trim( (string) $id ) );
	// Valid GA4 IDs typically start with G- followed by alphanumeric characters
	if ( ! empty( $id ) && preg_match( '/^G-[A-Z0-9]+$/i', $id ) ) {
		return $id;
	}
	// Also support legacy UA- if entered
	if ( ! empty( $id ) && preg_match( '/^UA-\d+-\d+$/i', $id ) ) {
		return $id;
	}
	return ! empty( $id ) ? $id : '';
}

/**
 * Check if the current visitor is an admin / store manager who should be excluded
 */
function aurika_ga4_should_exclude_current_user() {
	$exclude_admin = get_theme_mod( 'aurika_ga4_exclude_admin', true );
	if ( ! $exclude_admin ) {
		return false;
	}
	if ( is_user_logged_in() && ( current_user_can( 'manage_woocommerce' ) || current_user_can( 'administrator' ) || current_user_can( 'editor' ) ) ) {
		return true;
	}
	return false;
}

// ─────────────────────────────────────────────────────────────
// 2. INJECT GOOGLE ANALYTICS (GTAG.JS) & GTM IN <HEAD>
// ─────────────────────────────────────────────────────────────
add_action( 'wp_head', 'aurika_inject_google_analytics_head', 1 );
function aurika_inject_google_analytics_head() {
	$ga4_id   = aurika_get_ga4_id();
	$gtm_id   = strtoupper( trim( (string) get_theme_mod( 'aurika_gtm_id', '' ) ) );
	$is_admin = aurika_ga4_should_exclude_current_user();

	// Custom Head Tracking Scripts (runs regardless of GA4, unless user excluded)
	$custom_head = get_theme_mod( 'aurika_custom_head_tracking', '' );
	if ( ! empty( $custom_head ) && ! $is_admin ) {
		echo "\n<!-- Aurika Custom Head Scripts -->\n" . $custom_head . "\n<!-- /Aurika Custom Head Scripts -->\n";
	}

	// GTM Container if provided
	if ( ! empty( $gtm_id ) && preg_match( '/^GTM-[A-Z0-9]+$/i', $gtm_id ) && ! $is_admin ) {
		?>
<!-- Google Tag Manager (Aurika) -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js( $gtm_id ); ?>');</script>
<!-- End Google Tag Manager -->
		<?php
	}

	// If no GA4 ID set, nothing more to do
	if ( empty( $ga4_id ) ) {
		return;
	}

	// If logged in store admin and exclude toggle is ON
	if ( $is_admin ) {
		echo "\n<!-- Aurika Google Analytics (GA4: " . esc_html( $ga4_id ) . "): Excluded for Logged-in Administrator -->\n";
		return;
	}

	$track_ecommerce = get_theme_mod( 'aurika_ga4_track_ecommerce', true );
	?>
<!-- Google tag (gtag.js) - Aurika GA4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga4_id ); ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo esc_js( $ga4_id ); ?>', {
    'send_page_view': true,
    'cookie_flags': 'SameSite=None;Secure'
  });
</script>
<!-- End Google tag (gtag.js) -->
	<?php

	// Contextual E-Commerce Data on Single Product
	if ( $track_ecommerce && function_exists( 'is_product' ) && is_product() ) {
		global $post;
		$product = wc_get_product( $post->ID );
		if ( $product ) {
			$prod_price = (float) $product->get_price();
			$prod_name  = $product->get_name();
			$prod_id    = (string) $product->get_id();
			$prod_cats  = wp_strip_all_tags( wc_get_product_category_list( $product->get_id(), ', ' ) );
			?>
<script>
  window.aurikaProductId = <?php echo json_encode( $prod_id ); ?>;
  window.aurikaProductName = <?php echo json_encode( $prod_name ); ?>;
  window.aurikaProductPrice = <?php echo (float) $prod_price; ?>;
  window.aurikaProductCategory = <?php echo json_encode( $prod_cats ); ?>;

  gtag('event', 'view_item', {
    currency: 'PKR',
    value: <?php echo (float) $prod_price; ?>,
    items: [{
      item_id: <?php echo json_encode( $prod_id ); ?>,
      item_name: <?php echo json_encode( $prod_name ); ?>,
      item_category: <?php echo json_encode( $prod_cats ); ?>,
      price: <?php echo (float) $prod_price; ?>,
      quantity: 1
    }]
  });
</script>
			<?php
		}
	}

	// Contextual E-Commerce Data on Checkout Page
	if ( $track_ecommerce && function_exists( 'is_checkout' ) && is_checkout() && ! is_order_received_page() ) {
		if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) {
			$cart_items = array();
			$cart_total = (float) WC()->cart->get_total( 'edit' );
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				$cart_prod = $cart_item['data'];
				if ( $cart_prod ) {
					$cart_items[] = array(
						'item_id'   => (string) $cart_prod->get_id(),
						'item_name' => $cart_prod->get_name(),
						'price'     => (float) $cart_prod->get_price(),
						'quantity'  => (int) $cart_item['quantity'],
					);
				}
			}
			if ( ! empty( $cart_items ) ) {
				?>
<script>
  gtag('event', 'begin_checkout', {
    currency: 'PKR',
    value: <?php echo (float) $cart_total; ?>,
    items: <?php echo json_encode( $cart_items ); ?>
  });
</script>
				<?php
			}
		}
	}
}

// ─────────────────────────────────────────────────────────────
// 3. INJECT GTM NOSCRIPT IN <BODY>
// ─────────────────────────────────────────────────────────────
add_action( 'wp_body_open', 'aurika_inject_google_analytics_body_open' );
function aurika_inject_google_analytics_body_open() {
	if ( aurika_ga4_should_exclude_current_user() ) {
		return;
	}
	$gtm_id = strtoupper( trim( (string) get_theme_mod( 'aurika_gtm_id', '' ) ) );
	if ( ! empty( $gtm_id ) && preg_match( '/^GTM-[A-Z0-9]+$/i', $gtm_id ) ) {
		?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm_id ); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<?php
	}
}

// ─────────────────────────────────────────────────────────────
// 4. INJECT FOOTER TRACKING & INTERACTIVE EVENT LISTENERS
// ─────────────────────────────────────────────────────────────
add_action( 'wp_footer', 'aurika_inject_google_analytics_footer' );
function aurika_inject_google_analytics_footer() {
	$is_admin = aurika_ga4_should_exclude_current_user();

	// Custom Footer Scripts
	$custom_footer = get_theme_mod( 'aurika_custom_body_tracking', '' );
	if ( ! empty( $custom_footer ) && ! $is_admin ) {
		echo "\n<!-- Aurika Custom Footer Scripts -->\n" . $custom_footer . "\n<!-- /Aurika Custom Footer Scripts -->\n";
	}

	$ga4_id = aurika_get_ga4_id();
	if ( empty( $ga4_id ) || $is_admin ) {
		return;
	}

	$track_ecommerce = get_theme_mod( 'aurika_ga4_track_ecommerce', true );
	if ( ! $track_ecommerce ) {
		return;
	}
	?>
<!-- Aurika GA4 eCommerce Interactive Event Listeners -->
<script>
(function() {
  if (typeof gtag !== 'function') return;

  document.addEventListener('DOMContentLoaded', function() {
    // 1. Single Product Add to Cart Click
    var addToCartBtn = document.querySelector('.single_add_to_cart_button, .btn-add-to-cart');
    if (addToCartBtn && window.aurikaProductId) {
      addToCartBtn.addEventListener('click', function() {
        var qtyInput = document.querySelector('input.qty');
        var qty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
        var price = window.aurikaProductPrice || 0;
        
        gtag('event', 'add_to_cart', {
          currency: 'PKR',
          value: price * qty,
          items: [{
            item_id: String(window.aurikaProductId),
            item_name: window.aurikaProductName || 'Candle / Art Kit',
            item_category: window.aurikaProductCategory || '',
            price: price,
            quantity: qty
          }]
        });
      });
    }

    // 2. Buy It Now Direct Checkout Click
    var buyNowBtn = document.querySelector('.btn-buy-now, #aurika-var-buy-now');
    if (buyNowBtn && window.aurikaProductId) {
      buyNowBtn.addEventListener('click', function() {
        var price = window.aurikaProductPrice || 0;
        gtag('event', 'begin_checkout', {
          currency: 'PKR',
          value: price,
          items: [{
            item_id: String(window.aurikaProductId),
            item_name: window.aurikaProductName || 'Candle / Art Kit',
            price: price,
            quantity: 1
          }]
        });
      });
    }

    // 3. Shop & Archive Add to Cart Buttons
    document.querySelectorAll('.ajax_add_to_cart, .btn-card-add').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var prodId = this.getAttribute('data-product_id') || this.getAttribute('data-product-id');
        var prodName = this.getAttribute('data-product_name') || this.getAttribute('aria-label') || 'Product';
        gtag('event', 'add_to_cart', {
          currency: 'PKR',
          items: [{
            item_id: String(prodId || ''),
            item_name: prodName,
            quantity: 1
          }]
        });
      });
    });

    // 4. WhatsApp Quick Inquiries
    document.querySelectorAll('a[href*="wa.me"], a[href*="whatsapp.com"]').forEach(function(waLink) {
      waLink.addEventListener('click', function() {
        gtag('event', 'contact', {
          method: 'WhatsApp',
          content_type: 'Inquiry'
        });
      });
    });
  });
})();
</script>
	<?php
}

// ─────────────────────────────────────────────────────────────
// 5. GA4 PURCHASE TRACKING (THANK YOU / ORDER RECEIVED PAGE)
// ─────────────────────────────────────────────────────────────
add_action( 'woocommerce_thankyou', 'aurika_ga4_track_purchase_event', 10, 1 );
function aurika_ga4_track_purchase_event( $order_id ) {
	if ( ! $order_id ) {
		return;
	}

	$ga4_id = aurika_get_ga4_id();
	if ( empty( $ga4_id ) || aurika_ga4_should_exclude_current_user() ) {
		return;
	}

	$track_ecommerce = get_theme_mod( 'aurika_ga4_track_ecommerce', true );
	if ( ! $track_ecommerce ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	// Anti-duplicate protection: Check if order already tracked
	$already_tracked = $order->get_meta( '_aurika_ga4_tracked' );
	if ( '1' === (string) $already_tracked ) {
		return;
	}

	// Mark as tracked
	$order->update_meta_data( '_aurika_ga4_tracked', '1' );
	$order->save();

	// Prepare Order Items
	$items = array();
	foreach ( $order->get_items() as $item_id => $item ) {
		$product = $item->get_product();
		$items[] = array(
			'item_id'   => (string) ( $product ? $product->get_id() : $item->get_product_id() ),
			'item_name' => $item->get_name(),
			'price'     => (float) $order->get_item_subtotal( $item, false ),
			'quantity'  => (int) $item->get_quantity(),
		);
	}

	$order_total    = (float) $order->get_total();
	$order_tax      = (float) $order->get_total_tax();
	$order_shipping = (float) $order->get_shipping_total();
	$currency       = $order->get_currency();
	$order_number   = (string) $order->get_order_number();
	$coupons        = implode( ', ', $order->get_coupon_codes() );
	?>
<!-- Aurika GA4 Purchase Event -->
<script>
  if (typeof gtag === 'function') {
    gtag('event', 'purchase', {
      transaction_id: <?php echo json_encode( $order_number ); ?>,
      value: <?php echo (float) $order_total; ?>,
      tax: <?php echo (float) $order_tax; ?>,
      shipping: <?php echo (float) $order_shipping; ?>,
      currency: <?php echo json_encode( $currency ); ?>,
      coupon: <?php echo json_encode( $coupons ); ?>,
      items: <?php echo json_encode( $items ); ?>
    });
  }
</script>
	<?php
}

// ─────────────────────────────────────────────────────────────
// 6. ADMIN SETTINGS PAGE: SETTINGS → GOOGLE ANALYTICS
// ─────────────────────────────────────────────────────────────
add_action( 'admin_menu', 'aurika_add_analytics_admin_menu' );
function aurika_add_analytics_admin_menu() {
	add_options_page(
		__( 'Google Analytics (Aurika)', 'aurika' ),
		__( 'Google Analytics', 'aurika' ),
		'manage_options',
		'aurika-analytics',
		'aurika_render_analytics_admin_page'
	);
}

/**
 * Handle POST save from admin page
 */
add_action( 'admin_init', 'aurika_handle_analytics_admin_save' );
function aurika_handle_analytics_admin_save() {
	if ( ! isset( $_POST['aurika_analytics_nonce'] ) || ! wp_verify_nonce( $_POST['aurika_analytics_nonce'], 'aurika_save_analytics' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['aurika_ga4_measurement_id'] ) ) {
		$raw_id = sanitize_text_field( wp_unslash( $_POST['aurika_ga4_measurement_id'] ) );
		set_theme_mod( 'aurika_ga4_measurement_id', strtoupper( trim( $raw_id ) ) );
	}

	$track_ecom = isset( $_POST['aurika_ga4_track_ecommerce'] ) ? true : false;
	set_theme_mod( 'aurika_ga4_track_ecommerce', $track_ecom );

	$exclude_adm = isset( $_POST['aurika_ga4_exclude_admin'] ) ? true : false;
	set_theme_mod( 'aurika_ga4_exclude_admin', $exclude_adm );

	if ( isset( $_POST['aurika_gtm_id'] ) ) {
		$gtm_id = sanitize_text_field( wp_unslash( $_POST['aurika_gtm_id'] ) );
		set_theme_mod( 'aurika_gtm_id', strtoupper( trim( $gtm_id ) ) );
	}

	if ( isset( $_POST['aurika_custom_head_tracking'] ) ) {
		if ( current_user_can( 'unfiltered_html' ) ) {
			set_theme_mod( 'aurika_custom_head_tracking', wp_unslash( $_POST['aurika_custom_head_tracking'] ) );
		}
	}

	if ( isset( $_POST['aurika_custom_body_tracking'] ) ) {
		if ( current_user_can( 'unfiltered_html' ) ) {
			set_theme_mod( 'aurika_custom_body_tracking', wp_unslash( $_POST['aurika_custom_body_tracking'] ) );
		}
	}

	wp_safe_redirect( add_query_arg( array( 'page' => 'aurika-analytics', 'saved' => '1' ), admin_url( 'options-general.php' ) ) );
	exit;
}

/**
 * Render the Admin Settings Page
 */
function aurika_render_analytics_admin_page() {
	$ga4_id          = get_theme_mod( 'aurika_ga4_measurement_id', '' );
	$is_connected    = ! empty( $ga4_id );
	$track_ecom      = get_theme_mod( 'aurika_ga4_track_ecommerce', true );
	$exclude_adm     = get_theme_mod( 'aurika_ga4_exclude_admin', true );
	$gtm_id          = get_theme_mod( 'aurika_gtm_id', '' );
	$custom_head     = get_theme_mod( 'aurika_custom_head_tracking', '' );
	$custom_body     = get_theme_mod( 'aurika_custom_body_tracking', '' );
	$customizer_link = admin_url( 'customize.php?autofocus[section]=aurika_analytics_section' );
	?>
	<div class="wrap" style="max-width: 900px; margin-top: 20px;">
		
		<div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); margin-bottom: 25px;">
			<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; border-bottom: 1px solid #edf2f7; padding-bottom: 20px;">
				<div>
					<h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #1a202c; display: flex; align-items: center; gap: 10px;">
						<span>📈</span> Aurika Google Analytics 4 (GA4) Integration
					</h1>
					<p style="margin: 6px 0 0; color: #718096; font-size: 14px;">
						Real-time visitor tracking, traffic sources, and WooCommerce e-commerce performance.
					</p>
				</div>
				<div>
					<?php if ( $is_connected ) : ?>
						<span style="display: inline-flex; align-items: center; gap: 6px; background: #def7ec; color: #03543f; padding: 8px 14px; border-radius: 9999px; font-weight: 600; font-size: 13px; border: 1px solid #31c48d;">
							<span style="display: inline-block; width: 8px; height: 8px; background: #0e9f6e; border-radius: 50%;"></span>
							Connected: <?php echo esc_html( $ga4_id ); ?>
						</span>
					<?php else : ?>
						<span style="display: inline-flex; align-items: center; gap: 6px; background: #feecdc; color: #92400e; padding: 8px 14px; border-radius: 9999px; font-weight: 600; font-size: 13px; border: 1px solid #f6993f;">
							⚠️ Not Connected Yet
						</span>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( isset( $_GET['saved'] ) && '1' === $_GET['saved'] ) : ?>
				<div style="background: #f0fdf4; border-left: 4px solid #22c55e; padding: 12px 18px; margin-top: 18px; border-radius: 4px; color: #166534; font-size: 14px; font-weight: 500;">
					✅ Settings saved successfully! Your Google Analytics tracking is live.
				</div>
			<?php endif; ?>

			<form method="post" action="" style="margin-top: 25px;">
				<?php wp_nonce_field( 'aurika_save_analytics', 'aurika_analytics_nonce' ); ?>

				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row" style="width: 250px;">
								<label for="aurika_ga4_measurement_id" style="font-weight: 600; color: #2d3748;">
									GA4 Measurement ID <span style="color: #e53e3e;">*</span>
								</label>
							</th>
							<td>
								<input type="text" name="aurika_ga4_measurement_id" id="aurika_ga4_measurement_id" 
									   value="<?php echo esc_attr( $ga4_id ); ?>" 
									   placeholder="G-XXXXXXXXXX" 
									   class="regular-text" 
									   style="font-family: monospace; font-size: 15px; padding: 6px 12px; width: 320px; font-weight: bold; letter-spacing: 0.5px;">
								<p class="description" style="margin-top: 6px; color: #4a5568;">
									Paste your Google Analytics 4 Measurement ID here (starts with <strong>G-</strong>).<br>
									Found in: <em>Google Analytics → Admin (gear icon) → Data Streams → Click your web stream</em>.
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row" style="font-weight: 600; color: #2d3748;">
								WooCommerce E-Commerce Tracking
							</th>
							<td>
								<label style="display: flex; align-items: center; gap: 8px; font-weight: 500; cursor: pointer;">
									<input type="checkbox" name="aurika_ga4_track_ecommerce" value="1" <?php checked( $track_ecom ); ?>>
									<span>Enable Enhanced E-Commerce Tracking (Recommended)</span>
								</label>
								<p class="description" style="margin-top: 6px; color: #4a5568;">
									Automatically tracks <strong>view_item</strong> (product views), <strong>add_to_cart</strong>, <strong>begin_checkout</strong>, and <strong>purchase</strong> with order revenue and items.
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row" style="font-weight: 600; color: #2d3748;">
								Exclude Admin Visits
							</th>
							<td>
								<label style="display: flex; align-items: center; gap: 8px; font-weight: 500; cursor: pointer;">
									<input type="checkbox" name="aurika_ga4_exclude_admin" value="1" <?php checked( $exclude_adm ); ?>>
									<span>Do not track visits from logged-in Administrators &amp; Store Managers</span>
								</label>
								<p class="description" style="margin-top: 6px; color: #4a5568;">
									Keeps your analytics accurate by preventing your own store management and browsing sessions from inflating visitor counts. (Tip: Uncheck temporarily if testing live tracking yourself while logged in).
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row" style="font-weight: 600; color: #2d3748;">
								Google Tag Manager ID <span style="font-weight: normal; color: #718096;">(Optional)</span>
							</th>
							<td>
								<input type="text" name="aurika_gtm_id" id="aurika_gtm_id" 
									   value="<?php echo esc_attr( $gtm_id ); ?>" 
									   placeholder="GTM-XXXXXXX" 
									   class="regular-text" 
									   style="font-family: monospace; font-size: 14px; padding: 6px 12px; width: 320px;">
								<p class="description" style="margin-top: 6px; color: #4a5568;">
									Only required if you manage tracking tags through Google Tag Manager container.
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row" style="font-weight: 600; color: #2d3748;">
								Custom Header Tracking Code <span style="font-weight: normal; color: #718096;">(Optional)</span>
							</th>
							<td>
								<textarea name="aurika_custom_head_tracking" rows="4" class="large-text code" placeholder="<!-- Additional tracking pixels like Meta Pixel, Pinterest, TikTok -->"><?php echo esc_textarea( $custom_head ); ?></textarea>
								<p class="description" style="margin-top: 6px; color: #4a5568;">
									Injected directly into <code>&lt;head&gt;</code> on every page.
								</p>
							</td>
						</tr>

						<tr>
							<th scope="row" style="font-weight: 600; color: #2d3748;">
								Custom Footer Tracking Code <span style="font-weight: normal; color: #718096;">(Optional)</span>
							</th>
							<td>
								<textarea name="aurika_custom_body_tracking" rows="3" class="large-text code" placeholder="<!-- Conversion or chat widget scripts before </body> -->"><?php echo esc_textarea( $custom_body ); ?></textarea>
								<p class="description" style="margin-top: 6px; color: #4a5568;">
									Injected right before <code>&lt;/body&gt;</code> on every page.
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<div style="margin-top: 30px; display: flex; align-items: center; gap: 15px;">
					<button type="submit" class="button button-primary button-hero" style="background: #111111; border-color: #111111; color: #ffffff; padding: 8px 24px; font-weight: 600;">
						💾 Save Google Analytics Settings
					</button>
					<a href="<?php echo esc_url( $customizer_link ); ?>" class="button button-secondary" style="padding: 8px 16px;">
						🎨 Open in WordPress Customizer
					</a>
				</div>
			</form>
		</div>

		<!-- Helpful Live Verification Guide Card -->
		<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px 28px;">
			<h3 style="margin-top: 0; color: #1e293b; display: flex; align-items: center; gap: 8px;">
				<span>🔍</span> How to verify Google Analytics is working in 30 seconds:
			</h3>
			<ol style="margin: 0; padding-left: 20px; line-height: 1.8; color: #475569;">
				<li>Open <a href="https://analytics.google.com" target="_blank" rel="noopener noreferrer" style="font-weight: 600; color: #2563eb;">Google Analytics</a>.</li>
				<li>Click <strong>Reports</strong> (bar chart icon) on the left sidebar, then click <strong>Realtime</strong>.</li>
				<li>Open Aurika in a new Incognito / Private browser window (or on your phone) and click on a product.</li>
				<li>Look at the Google Analytics Realtime map &mdash; you will immediately see <strong>1 active user</strong>, the page they are on, and their city!</li>
			</ol>
		</div>

	</div>
	<?php
}
