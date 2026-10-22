<?php
/**
 * Aurika Theme Functions
 *
 * @package Aurika
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ─────────────────────────────────────────────────────────
// INCLUDE MODULAR COMPONENTS (CUSTOMIZER & SEO ENGINE)
// ─────────────────────────────────────────────────────────
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/product-image-cropper.php';
require_once get_template_directory() . '/inc/analytics.php';

// ─────────────────────────────────────────────────────────
// 0. CUSTOM NAV WALKER
// ─────────────────────────────────────────────────────────
if ( ! class_exists( 'Aurika_Nav_Walker' ) ) {
	class Aurika_Nav_Walker extends Walker_Nav_Menu {
		public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			$output .= '<li' . $class_names . '>';

			$atts = array(
				'title'  => ! empty( $item->attr_title ) ? $item->attr_title : '',
				'target' => ! empty( $item->target )     ? $item->target     : '',
				'rel'    => ! empty( $item->xfn )        ? $item->xfn        : '',
				'href'   => ! empty( $item->url )        ? $item->url        : '',
			);
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$has_children = in_array( 'menu-item-has-children', $classes, true );
			$title = apply_filters( 'the_title', $item->title, $item->ID );
			$output .= '<a' . $attributes . '>';
			$output .= esc_html( $title );
			if ( $has_children && 0 === $depth ) {
				$output .= ' <svg class="dropdown-arrow" width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
			}
			$output .= '</a>';
		}
	}
}

// ─────────────────────────────────────────────────────────
// 1. THEME SETUP
// ─────────────────────────────────────────────────────────
function aurika_setup() {
	load_theme_textdomain( 'aurika', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );

	// WooCommerce
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width'         => 600,
		'gallery_thumbnail_image_width' => 300,
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Elementor Theme Support
	add_theme_support( 'elementor' );
	add_post_type_support( 'page', 'elementor' );
	add_post_type_support( 'product', 'elementor' );
	add_post_type_support( 'post', 'elementor' );

	register_nav_menus( array(
		'primary'                 => __( 'Primary Navigation (Header)', 'aurika' ),
		'mobile'                  => __( 'Mobile Menu (Overlay)', 'aurika' ),
		'footer_quick_links'      => __( 'Footer: Quick Links (Col 2)', 'aurika' ),
		'footer_info'             => __( 'Footer: Information (Col 3)', 'aurika' ),
		'footer_customer_service' => __( 'Footer: Customer Service (Col 4)', 'aurika' ),
	) );

	// Auto-configure static homepage if Home page exists and not set
	if ( ! get_option( 'page_on_front' ) ) {
		$home = get_page_by_title( 'Home' );
		if ( $home ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home->ID );
		}
	}
}
add_action( 'after_setup_theme', 'aurika_setup' );

// ─────────────────────────────────────────────────────────
// 1b. FAVICON — Inject SVG favicon if no custom site icon set
// ─────────────────────────────────────────────────────────
add_action( 'wp_head', function() {
	// Only inject fallback if WordPress site icon (from Customizer) is not set
	if ( ! function_exists('has_site_icon') || ! has_site_icon() ) {
		$favicon_url = get_template_directory_uri() . '/assets/images/favicon.svg';
		echo '<link rel="icon" type="image/svg+xml" href="' . esc_url($favicon_url) . '">' . "\n";
		echo '<link rel="apple-touch-icon" href="' . esc_url($favicon_url) . '">' . "\n";
	}
}, 1 );


// ─────────────────────────────────────────────────────────
// 2. ENQUEUE SCRIPTS & STYLES
// ─────────────────────────────────────────────────────────
function aurika_scripts() {
	// Google Fonts (Playfair Display + Poppins)
	wp_enqueue_style(
		'aurika-google-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;500;600;700;800&display=swap',
		array(),
		null
	);

	$theme_ver = wp_get_theme()->get( 'Version' ) ?: '6.24.0';
	$css_file  = get_stylesheet_directory() . '/style.css';
	$css_ver   = file_exists( $css_file ) ? $theme_ver . '.' . filemtime( $css_file ) : $theme_ver;

	// Primary Theme Stylesheet (style.css with dynamic cache-busting)
	wp_enqueue_style(
		'aurika-style',
		get_stylesheet_uri(),
		array(),
		$css_ver
	);

	$js_file   = get_template_directory() . '/assets/js/main.js';
	$js_ver    = file_exists( $js_file ) ? $theme_ver . '.' . filemtime( $js_file ) : $theme_ver;

	// Main JS (Deferred in footer with dynamic cache-busting)
	wp_enqueue_script(
		'aurika-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		$js_ver,
		true
	);

	wp_localize_script( 'aurika-main', 'aurika_params', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'aurika_nonce' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'aurika_scripts', 999 );

// ─────────────────────────────────────────────────────────
// 2b-1. AUTOMATIC CACHE-BUSTING & MULTI-DEVICE UNIFICATION
// ─────────────────────────────────────────────────────────
function aurika_purge_all_caches() {
	// Purge LiteSpeed desktop & mobile caches
	if ( class_exists( '\LiteSpeed\Purge' ) ) {
		\LiteSpeed\Purge::purge_all();
	}
	do_action( 'litespeed_purge_all' );

	// Purge standard WordPress object caches
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
}
add_action( 'after_switch_theme', 'aurika_purge_all_caches' );
add_action( 'upgrader_process_complete', 'aurika_purge_all_caches' );

// Allow admin to purge all cache by visiting any URL with ?purge_aurika_cache=1
add_action( 'init', function() {
	if ( isset( $_GET['purge_aurika_cache'] ) ) {
		aurika_purge_all_caches();
		if ( current_user_can( 'manage_options' ) ) {
			wp_die( '<div style="font-family:sans-serif;text-align:center;padding:50px;"><h2>⚡ Aurika Caches Cleared Everywhere!</h2><p>LiteSpeed Desktop & Mobile caches and WordPress caches have been completely purged.</p><p><a href="' . esc_url( home_url('/') ) . '" style="display:inline-block;padding:10px 20px;background:#111;color:#fff;text-decoration:none;border-radius:4px;margin-top:15px;">Return to Site</a></p></div>', 'Cache Cleared' );
		}
	}
} );

// Prevent client browsers on any device from locking HTML pages for 7 days
add_action( 'send_headers', function() {
	if ( ! is_admin() ) {
		// Enforce revalidation on client devices so updates show everywhere immediately
		header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
	}
}, 999 );

// ─────────────────────────────────────────────────────────
// 2b. SPEED & PERFORMANCE ENGINE (0ms Lag, Clean Head, Fast Fonts)
// ─────────────────────────────────────────────────────────
// Prevent LiteSpeed from renaming/combining aurika-style into temporary hashes that cause 404s
add_filter( 'style_loader_tag', function( $html, $handle ) {
	if ( 'aurika-style' === $handle || 'aurika-google-fonts' === $handle ) {
		return str_replace( '<link ', '<link data-no-optimize="1" ', $html );
	}
	return $html;
}, 10, 2 );

// Exclude theme stylesheet from LiteSpeed CSS combine/minify
add_filter( 'litespeed_optimize_css_excludes', function( $excludes ) {
	if ( ! is_array( $excludes ) ) {
		$excludes = array();
	}
	$excludes[] = 'aurika-style';
	$excludes[] = 'style.css';
	$excludes[] = 'aurika-v67';
	$excludes[] = 'aurika-v66';
	return $excludes;
} );

// Defer non-critical scripts
add_filter( 'script_loader_tag', function( $tag, $handle ) {
	if ( 'aurika-main' === $handle ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}, 10, 2 );

// Disable core WP Emojis (reduces 2 HTTP requests + JS overhead)
add_action( 'init', function() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', function( $plugins ) {
		return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
	} );
} );

// Clean head from bloatware tags (RSD, WLW, Shortlink, Generator)
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// Add DNS prefetch & preconnect for external assets
add_filter( 'wp_resource_hints', function( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$hints[] = array( 'href' => 'https://fonts.googleapis.com', 'crossorigin' => 'anonymous' );
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
	}
	return $hints;
}, 10, 2 );

// ─────────────────────────────────────────────────────────
// 3. WIDGET SIDEBARS
// ─────────────────────────────────────────────────────────
function aurika_widgets_init() {
	$shared = array(
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	);

	foreach ( array( 1, 2, 3, 4 ) as $n ) {
		register_sidebar( array_merge( $shared, array(
			'name'        => sprintf( __( 'Footer Column %d', 'aurika' ), $n ),
			'id'          => 'footer-' . $n,
			'description' => sprintf( __( 'Footer widget area — column %d.', 'aurika' ), $n ),
		) ) );
	}
}
add_action( 'widgets_init', 'aurika_widgets_init' );

// ─────────────────────────────────────────────────────────
// 4. WOOCOMMERCE SETTINGS
// ─────────────────────────────────────────────────────────
// Keep WooCommerce default CSS for cart, checkout, account pages
// Our theme CSS overrides product cards and single product page
add_filter( 'loop_shop_columns', function() { return 4; } );
add_filter( 'woocommerce_product_thumbnails_columns', function() { return 4; } );

// ─────────────────────────────────────────────────────────
// 5. HELPER FUNCTIONS & WOOCOMMERCE CART FRAGMENTS
// ─────────────────────────────────────────────────────────
function aurika_cart_count() {
	if ( function_exists( 'WC' ) && WC()->cart ) {
		return (int) WC()->cart->get_cart_contents_count();
	}
	return 0;
}

function aurika_shop_url() {
	if ( function_exists( 'wc_get_page_id' ) ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id > 0 ) {
			$url = get_permalink( $shop_id );
			if ( ! empty( $url ) ) return $url;
		}
	}
	return home_url( '/shop/' );
}

function aurika_account_url() {
	if ( function_exists( 'wc_get_page_id' ) ) {
		$acc_id = wc_get_page_id( 'myaccount' );
		if ( $acc_id > 0 ) {
			$url = get_permalink( $acc_id );
			if ( ! empty( $url ) ) return $url;
		}
	}
	$page2 = get_page_by_path( 'my-account-2' );
	if ( $page2 ) return get_permalink( $page2->ID );
	$page1 = get_page_by_path( 'my-account' );
	if ( $page1 ) return get_permalink( $page1->ID );
	return home_url( '/my-account-2/' );
}

// Native WooCommerce AJAX Cart Fragment update
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
	$fragments['span.cart-count'] = '<span class="icon-badge cart-count">' . aurika_cart_count() . '</span>';
	return $fragments;
} );

// Automatic Pack & DIY Art Kit Size Pricing & Cart Meta
add_filter( 'woocommerce_add_cart_item_data', function( $cart_item_data, $product_id ) {
	if ( ! empty( $_REQUEST['aurika_pack'] ) ) {
		$pack = intval( $_REQUEST['aurika_pack'] );
		if ( in_array( $pack, array( 1, 2, 3 ), true ) ) {
			$cart_item_data['aurika_pack'] = $pack;
			$cart_item_data['unique_key']  = md5( microtime() . rand() );
		}
	}
	if ( ! empty( $_REQUEST['aurika_diy_size'] ) ) {
		$diy_size = sanitize_text_field( wp_unslash( $_REQUEST['aurika_diy_size'] ) );
		if ( in_array( $diy_size, array( 'small', 'big' ), true ) ) {
			$cart_item_data['aurika_diy_size'] = $diy_size;
			$cart_item_data['unique_key']      = md5( microtime() . rand() );
		}
	}
	return $cart_item_data;
}, 10, 2 );

// Display Pack Size & DIY Size in Cart & Checkout
add_filter( 'woocommerce_get_item_data', function( $item_data, $cart_item ) {
	if ( ! empty( $cart_item['aurika_pack'] ) && $cart_item['aurika_pack'] > 1 ) {
		$item_data[] = array(
			'key'   => __( 'Pack Size', 'aurika' ),
			'value' => sprintf( __( '%d Pack', 'aurika' ), $cart_item['aurika_pack'] ),
		);
	}
	if ( ! empty( $cart_item['aurika_diy_size'] ) ) {
		$size_label = ( $cart_item['aurika_diy_size'] === 'big' ) ? __( 'Big Kit (Premium Set)', 'aurika' ) : __( 'Small Kit (Compact Set)', 'aurika' );
		$item_data[] = array(
			'key'   => __( 'Kit Size', 'aurika' ),
			'value' => $size_label,
		);
	}
	return $item_data;
}, 10, 2 );

// Set the adjusted price for Pack Size & DIY Size before totals calculation
add_action( 'woocommerce_before_calculate_totals', function( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

	foreach ( $cart->get_cart() as $cart_item ) {
		$product = $cart_item['data'];
		if ( ! is_object( $product ) ) continue;

		$base_price = (float) $product->get_regular_price();
		if ( ! $base_price ) {
			$base_price = (float) $product->get_price();
		}

		// Handle candle pack pricing (Dynamic discounts from Customizer)
		if ( ! empty( $cart_item['aurika_pack'] ) ) {
			$pack = (int) $cart_item['aurika_pack'];
			$d2   = (float) get_theme_mod( 'aurika_pack2_discount', 10 );
			$d3   = (float) get_theme_mod( 'aurika_pack3_discount', 15 );
			$f2   = max( 0.01, ( 100 - $d2 ) / 100 );
			$f3   = max( 0.01, ( 100 - $d3 ) / 100 );
			if ( $pack === 2 ) {
				$product->set_price( round( $base_price * 2 * $f2 ) );
			} elseif ( $pack === 3 ) {
				$product->set_price( round( $base_price * 3 * $f3 ) );
			}
		}

		// Handle DIY kit pricing (Dynamic pricing from Customizer)
		if ( ! empty( $cart_item['aurika_diy_size'] ) ) {
			$p_big   = (float) get_theme_mod( 'aurika_diy_big_price', 599 );
			$p_small = (float) get_theme_mod( 'aurika_diy_small_price', 299 );
			if ( $cart_item['aurika_diy_size'] === 'big' ) {
				$product->set_price( $p_big );
			} elseif ( $cart_item['aurika_diy_size'] === 'small' ) {
				$product->set_price( $p_small );
			}
		}
	}
}, 99 );

// Save Pack Size & DIY Size to order line item meta
add_action( 'woocommerce_checkout_create_order_line_item', function( $item, $cart_item_key, $values, $order ) {
	if ( ! empty( $values['aurika_pack'] ) && $values['aurika_pack'] > 1 ) {
		$item->add_meta_data( __( 'Pack Size', 'aurika' ), sprintf( __( '%d Pack', 'aurika' ), $values['aurika_pack'] ), true );
	}
	if ( ! empty( $values['aurika_diy_size'] ) ) {
		$label = ( $values['aurika_diy_size'] === 'big' ) ? __( 'Big Kit (Premium Set)', 'aurika' ) : __( 'Small Kit (Compact Set)', 'aurika' );
		$item->add_meta_data( __( 'Kit Size', 'aurika' ), $label, true );
	}
}, 10, 4 );

// ─────────────────────────────────────────────────────────
// Show single base price for variable products (never returns 0)
// ─────────────────────────────────────────────────────────
add_filter( 'woocommerce_variable_price_html', function( $price_html, $product ) {
    if ( ! is_object( $product ) ) return $price_html;
    $min_price = (float) $product->get_variation_price( 'min', true );
    if ( $min_price <= 0 ) {
        $min_price = (float) $product->get_price();
    }
    if ( $min_price <= 0 && method_exists( $product, 'get_available_variations' ) ) {
        foreach ( $product->get_available_variations() as $v ) {
            if ( ! empty( $v['display_price'] ) && (float) $v['display_price'] > 0 ) {
                $min_price = (float) $v['display_price'];
                break;
            }
        }
    }
    if ( $min_price > 0 ) {
        return wc_price( $min_price );
    }
    return $price_html;
}, 10, 2 );

add_filter( 'woocommerce_variable_sale_price_html', function( $price_html, $product ) {
    if ( ! is_object( $product ) ) return $price_html;
    $min_price = (float) $product->get_variation_price( 'min', true );
    if ( $min_price <= 0 ) {
        $min_price = (float) $product->get_price();
    }
    if ( $min_price <= 0 && method_exists( $product, 'get_available_variations' ) ) {
        foreach ( $product->get_available_variations() as $v ) {
            if ( ! empty( $v['display_price'] ) && (float) $v['display_price'] > 0 ) {
                $min_price = (float) $v['display_price'];
                break;
            }
        }
    }
    if ( $min_price > 0 ) {
        return wc_price( $min_price );
    }
    return $price_html;
}, 10, 2 );

// High-resolution candle placeholder for products without images
add_filter( 'woocommerce_placeholder_img_src', function( $src ) {
    return 'https://images.unsplash.com/photo-1605651202774-7d573fd3f12d?q=80&w=800';
} );

/**
 * Clean product title, tagline, excerpt, and description by removing hardcoded
 * pack/piece counts and manual price options (since the pack selector UI handles them).
 */
function aurika_clean_product_text( $text ) {
	if ( empty( $text ) ) return '';

	// Remove price/piece options like: "1 pc (Rs. 80) / 2 pcs (Rs. 150) / 3 pcs (Rs. 250)"
	// or "| 1 Piece (Rs. 199) or 3 Pieces (Rs. 499)"
	$text = preg_replace( '/\s*\|\s*\d+\s*(?:pc|pcs|piece|pieces)\s*\([^)]*\)(?:\s*(?:or|\/)\s*\d+\s*(?:pc|pcs|piece|pieces)\s*\([^)]*\))*/i', '', $text );
	$text = preg_replace( '/(?:\s*(?:or|\/|\.)\s*)?\b\d+\s*(?:pc|pcs|piece|pieces)\s*\([^)]*\)(?:\s*(?:or|\/)\s*\d+\s*(?:pc|pcs|piece|pieces)\s*\([^)]*\))*/i', '', $text );

	// Remove "Available in 1 Piece or 2 Pieces Pack"
	$text = preg_replace( '/\s*\|\s*available in\s*\d+\s*(?:piece|pieces|pc|pcs)[^•|\n<]*/i', '', $text );
	$text = preg_replace( '/(?:\s*•\s*)?available in\s*\d+\s*(?:piece|pieces|pc|pcs)[^•|\n<]*/i', '', $text );

	// Remove bullet items with piece pricing: "• 1 Piece: Rs. 599..." or "• 2 Pieces: Rs..."
	$text = preg_replace( '/\s*•\s*\d+\s*(?:piece|pieces|pc|pcs)\s*:\s*Rs\.?\s*[\d,]+(?:\s*\([^)]*\))?/i', '', $text );
	$text = preg_replace( '/\s*•\s*\d+\s*Candles\s*\+\s*\d+\s*Matching Tray/i', '', $text );

	// Remove "(2 Pieces with Tray)", "(2 Pieces)", "(3 Pieces Pack)", etc.
	$text = preg_replace( '/\s*\(\s*\d+\s*(?:pieces?|pcs?)[^)]*\)/i', '', $text );
	$text = preg_replace( '/\s*[-–]\s*\d+\s*(?:pieces?|pcs?)\s*(?:pack|set)?\s*$/i', '', $text );
	$text = preg_replace( '/\s*\(\s*single\s*\)/i', '', $text );
	$text = preg_replace( '/\s*\(\s*set of \d+\s*\)/i', '', $text );

	// Clean "two-piece" -> ""
	$text = preg_replace( '/\btwo-piece\s+/i', '', $text );

	// Clean "Includes 2 Sea Shell Candles" -> "Includes Sea Shell Candles"
	$text = preg_replace( '/Includes\s+\d+\s+Sea Shell Candles/i', 'Includes Sea Shell Candles', $text );

	// Clean trailing pipes, bullets, and normalize whitespace
	$text = preg_replace( '/\s*\|\s*$/', '', $text );
	$text = preg_replace( '/\s*•\s*$/', '', $text );
	$text = preg_replace( '/\s{2,}/', ' ', $text );

	return trim( $text );
}

// ─────────────────────────────────────────────────────────
// 5c. REMOVE COUPONS GLOBALLY ACROSS ALL PAGES
// ─────────────────────────────────────────────────────────
add_filter( 'woocommerce_coupons_enabled', '__return_false', 999 );
add_action( 'init', function() {
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
}, 99 );

// Automatically enable customer registration on My Account & allow setting password
add_filter( 'pre_option_woocommerce_enable_myaccount_registration', function() { return 'yes'; } );
add_filter( 'pre_option_woocommerce_registration_generate_password', function() { return 'no'; } );
add_filter( 'pre_option_woocommerce_enable_signup_and_login_from_checkout', function() { return 'yes'; } );

// ─────────────────────────────────────────────────────────
// 5d. ENFORCE STRICT SHIPPING RULES:
// Rs. 350 FLAT RATE UNDER RS. 5,000 | FREE SHIPPING ABOVE RS. 5,000
// ─────────────────────────────────────────────────────────
add_filter( 'woocommerce_shipping_free_shipping_is_available', function( $is_available, $package, $shipping_method ) {
	$threshold = (float) get_theme_mod( 'aurika_shipping_free_threshold', 5000 );
	$subtotal  = 0;
	if ( function_exists('WC') && WC()->cart ) {
		$subtotal = (float) WC()->cart->get_subtotal();
	}
	if ( $subtotal < $threshold ) {
		return false;
	}
	return true;
}, 999, 3 );

add_filter( 'woocommerce_package_rates', function( $rates, $package ) {
	$threshold = (float) get_theme_mod( 'aurika_shipping_free_threshold', 5000 );
	$flat_cost = (float) get_theme_mod( 'aurika_shipping_flat_rate', 350 );
	$subtotal  = 0;
	if ( function_exists('WC') && WC()->cart ) {
		$subtotal = (float) WC()->cart->get_subtotal();
	}

	if ( $subtotal >= $threshold ) {
		// FREE SHIPPING (Order >= threshold)
		$free_rates = array();
		foreach ( $rates as $rate_id => $rate ) {
			if ( 'free_shipping' === $rate->method_id || 0 == $rate->cost ) {
				$rate->label = 'Free Shipping';
				$rate->cost  = 0;
				$rate->taxes = array();
				$free_rates[$rate_id] = $rate;
				break;
			}
		}
		if ( empty( $free_rates ) ) {
			$free_rates['aurika_free_shipping'] = new WC_Shipping_Rate(
				'aurika_free_shipping',
				'Free Shipping',
				0,
				array(),
				'free_shipping'
			);
		}
		return $free_rates;
	} else {
		// FLAT DELIVERY (Order < threshold)
		$flat_rates = array();
		foreach ( $rates as $rate_id => $rate ) {
			if ( 'free_shipping' !== $rate->method_id ) {
				$rate->label = 'Flat Rate Shipping';
				$rate->cost  = $flat_cost;
				$rate->taxes = array();
				$flat_rates[$rate_id] = $rate;
				break;
			}
		}
		if ( empty( $flat_rates ) ) {
			$flat_rates['aurika_flat_rate'] = new WC_Shipping_Rate(
				'aurika_flat_rate',
				'Flat Rate Shipping',
				$flat_cost,
				array(),
				'flat_rate'
			);
		}
		return $flat_rates;
	}
}, 999, 2 );

// Force clearing shipping calculation cache when cart updates
add_action( 'woocommerce_checkout_update_order_review', function() {
	if ( function_exists('WC') && WC()->session ) {
		WC()->session->set( 'shipping_for_package_0', '' );
	}
} );


// ─────────────────────────────────────────────────────────
// 5b. FAIL-SAFE FOOTER GUARANTEE & RECEIPT
// ─────────────────────────────────────────────────────────
add_action( 'wp_footer', function() {
	if ( ! defined( 'AURIKA_FOOTER_RENDERED' ) && ! is_admin() ) {
		if ( file_exists( get_template_directory() . '/footer.php' ) ) {
			include get_template_directory() . '/footer.php';
		}
	}
}, 0 );

// Override the CartFlows blank thank-you step with full WooCommerce receipt output
add_action( 'wp_footer', function() {
	if ( ! isset( $_GET['wcf-order'] ) || ! isset( $_GET['wcf-key'] ) ) return;
	if ( ! function_exists( 'wc_get_order' ) ) return;

	$order_id  = absint( $_GET['wcf-order'] );
	$order_key = sanitize_text_field( $_GET['wcf-key'] );
	$order     = wc_get_order( $order_id );
	if ( ! $order || $order->get_order_key() !== $order_key ) return;

	// Build receipt HTML
	$items_html = '';
	foreach ( $order->get_items() as $item ) {
		$product = $item->get_product();
		$items_html .= '<tr>
			<td style="padding:14px 16px;border-bottom:1px solid #efeae3;">' . esc_html( $item->get_name() ) . '</td>
			<td style="padding:14px 16px;border-bottom:1px solid #efeae3;text-align:center;">' . esc_html( $item->get_quantity() ) . '</td>
			<td style="padding:14px 16px;border-bottom:1px solid #efeae3;text-align:right;font-weight:700;">Rs. ' . number_format( $item->get_total() ) . '</td>
		</tr>';
	}

	$shipping_addr = $order->get_formatted_shipping_address();
	$home_url_safe = esc_url( home_url('/') );

	echo '<div id="aurika-order-receipt" style="position:fixed;inset:0;background:rgba(0,0,0,0.82);z-index:999999;display:flex;align-items:center;justify-content:center;padding:20px;">
	<div style="background:#fff;border-radius:16px;max-width:700px;width:100%;max-height:90vh;overflow-y:auto;padding:50px 40px;font-family:\'Poppins\',sans-serif;position:relative;box-shadow:0 12px 40px rgba(0,0,0,0.4);">
		<a href="' . $home_url_safe . '" style="position:absolute;top:18px;right:20px;background:none;border:none;font-size:24px;cursor:pointer;color:#888;text-decoration:none;font-weight:700;">&times;</a>
		<div style="text-align:center;background:#f0fbf1;border:1px solid #c3e6c5;border-radius:10px;padding:22px 20px;margin-bottom:35px;">
			<div style="font-size:32px;margin-bottom:8px;">✅</div>
			<h2 style="font-family:\'Playfair Display\',serif;font-size:24px;color:#111;margin:0 0 6px;">Thank you for your order!</h2>
			<p style="color:#555;font-size:13.5px;margin:0;">Your order has been confirmed successfully. We will contact you shortly.</p>
		</div>

		<div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:space-between;background:#faf8f5;border:1px solid #efeae3;border-radius:10px;padding:20px 24px;margin-bottom:30px;">
			<div style="text-align:center;min-width:100px;">
				<div style="font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:#888;margin-bottom:5px;">Order #</div>
				<strong style="font-size:15px;color:#111;">' . esc_html( $order->get_order_number() ) . '</strong>
			</div>
			<div style="text-align:center;min-width:100px;">
				<div style="font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:#888;margin-bottom:5px;">Date</div>
				<strong style="font-size:15px;color:#111;">' . esc_html( $order->get_date_created()->date('M d, Y') ) . '</strong>
			</div>
			<div style="text-align:center;min-width:100px;">
				<div style="font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:#888;margin-bottom:5px;">Payment</div>
				<strong style="font-size:15px;color:#111;">Cash on Delivery</strong>
			</div>
			<div style="text-align:center;min-width:100px;">
				<div style="font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:#888;margin-bottom:5px;">Total</div>
				<strong style="font-size:16px;color:#b8860b;">Rs. ' . number_format( $order->get_total() ) . '</strong>
			</div>
		</div>

		<h3 style="font-size:14px;text-transform:uppercase;letter-spacing:1.5px;color:#111;margin:0 0 14px;padding-bottom:10px;border-bottom:2px solid #b8860b;">Order Summary</h3>
		<table style="width:100%;border-collapse:collapse;margin-bottom:28px;">
			<thead>
				<tr style="background:#faf8f5;">
					<th style="padding:10px 16px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#888;">Product</th>
					<th style="padding:10px 16px;text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#888;">Qty</th>
					<th style="padding:10px 16px;text-align:right;font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#888;">Price</th>
				</tr>
			</thead>
			<tbody>' . $items_html . '</tbody>
		</table>

		' . ( $shipping_addr ? '<div style="background:#faf8f5;border:1px solid #efeae3;border-radius:10px;padding:20px 24px;">
			<h4 style="font-size:12px;text-transform:uppercase;letter-spacing:1.5px;color:#888;margin:0 0 10px;">Shipping Address</h4>
			<address style="font-style:normal;font-size:13.5px;color:#444;line-height:1.8;">' . wp_kses_post( $shipping_addr ) . '</address>
		</div>' : '' ) . '

		<div style="text-align:center;margin-top:32px;">
			<a href="' . $home_url_safe . '" style="display:inline-block;background:#111111;color:#fff;padding:16px 48px;font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase;text-decoration:none;border-radius:50px;box-shadow:0 4px 18px rgba(0,0,0,0.25);transition:all 0.3s ease;">CONTINUE SHOPPING (BACK TO HOME) &rarr;</a>
		</div>
	</div>
</div>';
}, 5 );



// ─────────────────────────────────────────────────────────
// 6. REMOVE ASTRA / HFE / DEMO INJECTIONS (SAFE & COMPATIBLE)
// ─────────────────────────────────────────────────────────
add_filter( 'astra_advanced_footer_disable', '__return_true', 9999 );
add_filter( 'astra_above_footer_disable', '__return_true', 9999 );
add_filter( 'astra_primary_footer_disable', '__return_true', 9999 );
add_filter( 'enable_hfe_render_header', '__return_false', 9999 );
add_filter( 'enable_hfe_render_footer', '__return_false', 9999 );
add_filter( 'enable_hfe_render_before_footer', '__return_false', 9999 );
add_filter( 'hfe_footer_enabled', '__return_false', 9999 );
add_filter( 'hfe_header_enabled', '__return_false', 9999 );
add_filter( 'hfe_render_footer', '__return_false', 9999 );
add_filter( 'hfe_render_header', '__return_false', 9999 );
add_filter( 'cartflows_show_header_footer', '__return_true', 9999 );
add_filter( 'cartflows_hide_header_footer', '__return_false', 9999 );

// Auto-delete stale Astra / HFE demo header-footer templates from database
add_action( 'init', function() {
	if ( ! is_admin() && ! current_user_can( 'manage_options' ) ) return;
	$demo_hfe = get_posts( array(
		'post_type'   => array( 'elementor-hf', 'hfe-footer', 'hfe-header' ),
		'numberposts' => -1,
		'post_status' => 'any',
	) );
	if ( ! empty( $demo_hfe ) ) {
		foreach ( $demo_hfe as $hf_post ) {
			wp_delete_post( $hf_post->ID, true );
		}
	}
}, 5 );

// Clean frontend removal of Astra & HFE extra hooks
add_action( 'after_setup_theme', function() {
	remove_action( 'astra_footer_before', 'astra_footer_above_markup' );
	remove_action( 'astra_footer', 'astra_footer_markup' );
	remove_action( 'astra_header', 'astra_header_markup' );
	remove_action( 'astra_entry_content_after', 'astra_cta_after_content_markup' );
}, 99 );

// CSS Blocker — hide ALL demo/Astra/Elementor footers & force Aurika footer visible
add_action( 'wp_head', function() {
	if ( is_admin() ) return;
	echo '<style id="aurika-footer-nuke">
	/* KILL: every demo/Astra/Elementor footer & header inject */
	.ast-separate-container .ast-article-single,
	.site-above-footer-wrap, .ast-above-footer, .ast-footer-overlay,
	.ast-small-footer, .ast-builder-footer-grid-columns,
	.site-footer-primary-section, .ast-above-footer-wrap,
	.ast-footer, .ast-builder-grid-row-container,
	.hfe-before-footer, .hfe-footer, .hfe-header,
	.elementor-location-footer,
	div[class*="urban-jungle"], div[class*="perfect-plant"],
	div[class*="skin-care"], div[class*="skincare"],
	div[class*="body-lotion"], .urban-jungle-footer,
	a[href*="body-lotion"], a[href*="sunscreen"], a[href*="cleanser"],
	a[href*="moisturizer"], a[href*="indoor-plants"], a[href*="woocommerce-placeholder"],
	/* KILL COUPON CODE BOXES & BUTTONS EVERYWHERE */
	.woocommerce-form-coupon-toggle,
	.woocommerce-form-coupon,
	.checkout_coupon,
	.wcf-coupon-wrap,
	.wcf-coupon-section,
	.wcf-show-coupon,
	.wcf-coupon-col,
	.wcf-field-coupon_code,
	.wcf-coupon-field-wrap,
	.wcf-coupon-row,
	.wcf-coupon-input-wrap,
	.wcf-coupon-form,
	button[name="apply_coupon"],
	input[name="coupon_code"],
	.cart-collaterals .coupon,
	.woocommerce-cart-form .coupon,
	div[class*="coupon"],
	button[class*="coupon"],
	a[class*="coupon"] {
		display: none !important;
		visibility: hidden !important;
		height: 0 !important;
		width: 0 !important;
		opacity: 0 !important;
		margin: 0 !important;
		padding: 0 !important;
		pointer-events: none !important;
	}

	/* FORCE PLACE ORDER BUTTON VISIBILITY & LUXURY STYLING */
	#place_order,
	.wcf-btn-place-order,
	.wcf-place-order-btn,
	button#place_order,
	#wcf-embed-checkout-form #place_order,
	.wcf-embed-checkout-form button#place_order,
	.woocommerce-checkout #place_order,
	.woocommerce-checkout button#place_order,
	.wcf-embed-checkout-form .wcf-checkout-place-order-btn,
	button[name="woocommerce_checkout_place_order"],
	.wcf-payment-button-wrap button {
		background: #111111 !important;
		color: #ffffff !important;
		-webkit-text-fill-color: #ffffff !important;
		font-family: \'Poppins\', sans-serif !important;
		font-size: 15px !important;
		font-weight: 700 !important;
		letter-spacing: 2px !important;
		text-transform: uppercase !important;
		padding: 18px 40px !important;
		min-height: 56px !important;
		border-radius: 50px !important;
		border: none !important;
		width: 100% !important;
		display: block !important;
		text-align: center !important;
		line-height: normal !important;
		opacity: 1 !important;
		visibility: visible !important;
		box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3) !important;
		transition: all 0.3s ease !important;
		cursor: pointer !important;
		margin-top: 20px !important;
	}
	#place_order:hover,
	.wcf-btn-place-order:hover,
	button#place_order:hover {
		background: #b8860b !important;
		color: #ffffff !important;
		-webkit-text-fill-color: #ffffff !important;
		transform: translateY(-2px) !important;
		box-shadow: 0 8px 25px rgba(184, 134, 11, 0.4) !important;
	}

	/* FORCE: Aurika footer always on top and visible */
	footer.site-footer,
	body footer.site-footer,
	.woocommerce-checkout footer.site-footer,
	body[class*="cartflows"] footer.site-footer,
	.wcf-checkout-step footer.site-footer {
		display: block !important;
		visibility: visible !important;
		opacity: 1 !important;
		width: 100% !important;
		max-width: 100% !important;
		position: relative !important;
		z-index: 9999 !important;
		background: #111118 !important;
		border-top: 3px solid #b8860b !important;
		font-family: \'Poppins\', -apple-system, BlinkMacSystemFont, sans-serif !important;
		color: rgba(255,255,255,0.7) !important;
		clear: both !important;
		margin: 0 !important;
		padding: 0 !important;
		box-sizing: border-box !important;
	}

	/* Reset Lists & Links in Footer */
	footer.site-footer ul,
	footer.site-footer ol,
	footer.site-footer li,
	.footer-links,
	.footer-links li {
		list-style: none !important;
		list-style-type: none !important;
		padding: 0 !important;
		margin: 0 !important;
		background: none !important;
	}

	footer.site-footer a {
		color: rgba(255,255,255,0.65) !important;
		text-decoration: none !important;
		transition: all 0.25s ease !important;
		font-family: \'Poppins\', sans-serif !important;
	}

	footer.site-footer a:hover {
		color: #b8860b !important;
		text-decoration: none !important;
	}

	/* Trust Strip */
	.footer-trust-strip {
		background: #ffffff !important;
		border-bottom: 1px solid #e8e0d5 !important;
		padding: 40px 0 !important;
		width: 100% !important;
		box-sizing: border-box !important;
	}

	.footer-trust-container {
		max-width: 1340px !important;
		margin: 0 auto !important;
		padding: 0 30px !important;
		display: flex !important;
		justify-content: space-around !important;
		align-items: center !important;
		flex-wrap: wrap !important;
		gap: 25px !important;
		box-sizing: border-box !important;
	}

	.footer-trust-item {
		display: flex !important;
		align-items: center !important;
		gap: 16px !important;
		flex: 1 !important;
		min-width: 220px !important;
		text-align: left !important;
	}

	.footer-trust-icon {
		width: 52px !important;
		height: 52px !important;
		border-radius: 50% !important;
		background: #faf8f5 !important;
		border: 1px solid #e8e0d5 !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		flex-shrink: 0 !important;
		box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important;
		transition: all 0.3s ease !important;
	}

	.footer-trust-item:hover .footer-trust-icon {
		transform: translateY(-3px) !important;
		border-color: #b8860b !important;
		box-shadow: 0 6px 18px rgba(184,134,11,0.15) !important;
	}

	.footer-trust-info h4 {
		font-family: \'Poppins\', sans-serif !important;
		font-size: 13px !important;
		font-weight: 700 !important;
		letter-spacing: 1.5px !important;
		text-transform: uppercase !important;
		color: #111111 !important;
		margin: 0 0 4px !important;
		padding: 0 !important;
		border: none !important;
	}

	.footer-trust-info p {
		font-family: \'Poppins\', sans-serif !important;
		font-size: 12px !important;
		color: #666666 !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	/* Footer Main 5-Column Grid */
	.footer-top {
		background: #111118 !important;
		padding: 70px 0 50px !important;
		width: 100% !important;
		box-sizing: border-box !important;
	}

	.footer-container {
		max-width: 1340px !important;
		margin: 0 auto !important;
		padding: 0 30px !important;
		display: grid !important;
		grid-template-columns: 1.8fr 1fr 1.1fr 1.2fr 1.4fr !important;
		gap: 40px !important;
		box-sizing: border-box !important;
		text-align: left !important;
	}

	.footer-col {
		display: flex !important;
		flex-direction: column !important;
	}

	.footer-col-title {
		font-family: \'Poppins\', sans-serif !important;
		font-size: 13px !important;
		font-weight: 700 !important;
		color: #ffffff !important;
		letter-spacing: 2px !important;
		text-transform: uppercase !important;
		margin: 0 0 22px !important;
		padding: 0 0 12px !important;
		border-bottom: 1px solid rgba(255,255,255,0.1) !important;
		border-top: none !important;
		border-left: none !important;
		border-right: none !important;
	}

	.footer-brand-desc {
		font-family: \'Poppins\', sans-serif !important;
		font-size: 13px !important;
		line-height: 1.85 !important;
		color: rgba(255,255,255,0.55) !important;
		margin: 0 0 20px !important;
	}

	.footer-contact-item {
		display: flex !important;
		align-items: center !important;
		gap: 10px !important;
		margin-bottom: 12px !important;
		font-size: 13px !important;
		color: rgba(255,255,255,0.7) !important;
	}

	.footer-contact-item svg {
		flex-shrink: 0 !important;
		stroke: #b8860b !important;
	}

	.footer-contact-item a {
		color: rgba(255,255,255,0.7) !important;
	}

	.footer-contact-item a:hover {
		color: #b8860b !important;
	}

	.footer-social-row {
		display: flex !important;
		gap: 12px !important;
		margin-top: 18px !important;
	}

	.footer-social-row a {
		color: rgba(255,255,255,0.6) !important;
		width: 36px !important;
		height: 36px !important;
		border: 1px solid rgba(255,255,255,0.18) !important;
		border-radius: 50% !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		transition: all 0.25s ease !important;
	}

	.footer-social-row a:hover {
		color: #b8860b !important;
		border-color: #b8860b !important;
		transform: translateY(-2px) !important;
	}

	.footer-links li a {
		font-family: \'Poppins\', sans-serif !important;
		font-size: 13px !important;
		color: rgba(255,255,255,0.55) !important;
		line-height: 2.3 !important;
		display: block !important;
		transition: all 0.2s ease !important;
	}

	.footer-links li a:hover {
		color: #b8860b !important;
		padding-left: 6px !important;
	}

	.footer-shipping-note {
		margin-top: 18px !important;
		padding-top: 14px !important;
		border-top: 1px solid rgba(255,255,255,0.08) !important;
	}

	.footer-shipping-note p {
		font-family: \'Poppins\', sans-serif !important;
		font-size: 12px !important;
		margin: 4px 0 !important;
		color: rgba(255,255,255,0.45) !important;
	}

	.footer-newsletter-desc {
		font-family: \'Poppins\', sans-serif !important;
		font-size: 13px !important;
		line-height: 1.8 !important;
		color: rgba(255,255,255,0.55) !important;
		margin: 0 0 16px !important;
	}

	.footer-newsletter-form {
		display: flex !important;
		flex-direction: column !important;
		gap: 10px !important;
		margin-bottom: 22px !important;
	}

	.footer-email-input {
		width: 100% !important;
		padding: 12px 16px !important;
		background: rgba(255,255,255,0.06) !important;
		border: 1px solid rgba(255,255,255,0.2) !important;
		border-radius: 4px !important;
		color: #ffffff !important;
		font-family: \'Poppins\', sans-serif !important;
		font-size: 13px !important;
		outline: none !important;
		box-sizing: border-box !important;
	}

	.footer-email-input:focus {
		border-color: #b8860b !important;
	}

	.footer-subscribe-btn {
		padding: 12px 20px !important;
		background: #ffffff !important;
		color: #111111 !important;
		font-family: \'Poppins\', sans-serif !important;
		font-size: 11px !important;
		font-weight: 700 !important;
		letter-spacing: 2px !important;
		text-transform: uppercase !important;
		border: none !important;
		border-radius: 4px !important;
		cursor: pointer !important;
		transition: all 0.25s ease !important;
		width: 100% !important;
		display: block !important;
		text-align: center !important;
	}

	.footer-subscribe-btn:hover {
		background: #b8860b !important;
		color: #ffffff !important;
	}

	.footer-brand-name {
		font-family: \'Playfair Display\', Georgia, serif !important;
		font-size: 24px !important;
		font-weight: 700 !important;
		color: #ffffff !important;
		display: block !important;
		margin-top: 10px !important;
	}

	.footer-brand-tag {
		font-family: \'Playfair Display\', Georgia, serif !important;
		font-size: 12px !important;
		color: #b8860b !important;
		font-style: italic !important;
		display: block !important;
	}

	/* Footer Bottom Copyright */
	.footer-bottom {
		background: #0a0a0f !important;
		border-top: 1px solid rgba(255,255,255,0.06) !important;
		padding: 22px 30px !important;
		text-align: center !important;
		font-family: \'Poppins\', sans-serif !important;
		font-size: 12px !important;
		color: rgba(255,255,255,0.45) !important;
		letter-spacing: 0.5px !important;
	}

	.footer-bottom p {
		margin: 0 !important;
		padding: 0 !important;
		color: rgba(255,255,255,0.45) !important;
	}

	.footer-bottom strong {
		color: #ffffff !important;
	}

	/* Responsive Breakpoints */
	@media (max-width: 1024px) {
		.footer-container {
			grid-template-columns: 1fr 1fr !important;
			gap: 35px !important;
		}
	}

	@media (max-width: 640px) {
		.footer-container {
			grid-template-columns: 1fr !important;
			gap: 30px !important;
		}
		.footer-trust-container {
			flex-direction: column !important;
			align-items: flex-start !important;
		}
	}
	</style>';
}, 999 );

// Also hook into CartFlows specific head and footer actions
add_action( 'cartflows_wp_head', function() {
	if ( function_exists( 'aurika_scripts' ) ) {
		aurika_scripts();
	}
}, 999 );


// ─────────────────────────────────────────────────────────
// 7. SYNC ELEMENTOR HOME CONTENT (ON-DEMAND VIA ADMIN)
// ─────────────────────────────────────────────────────────
function aurika_get_home_template_html() {
	$shop_url  = aurika_shop_url();

	// Hero settings
	$slide1_img    = get_theme_mod( 'aurika_hero_slide1_img', get_template_directory_uri() . '/assets/images/hero-aurika.jpg' );
	$slide2_img    = get_theme_mod( 'aurika_hero_slide2_img', get_template_directory_uri() . '/assets/images/hero-aurika.jpg' );
	$hero_btn_text = get_theme_mod( 'aurika_hero_btn_text', 'SHOP NOW' );
	$hero_btn_url  = get_theme_mod( 'aurika_hero_btn_url', $shop_url );

	// Trust strip settings
	$t1_title = get_theme_mod( 'aurika_trust1_title', 'Free Shipping > Rs. 5k' );
	$t1_desc  = get_theme_mod( 'aurika_trust1_desc', 'Rs. 350 flat rate under Rs. 5,000' );
	$t2_title = get_theme_mod( 'aurika_trust2_title', 'Cash on Delivery' );
	$t2_desc  = get_theme_mod( 'aurika_trust2_desc', 'Available across Pakistan' );
	$t3_title = get_theme_mod( 'aurika_trust3_title', '100% Handcrafted' );
	$t3_desc  = get_theme_mod( 'aurika_trust3_desc', 'Natural wax & lead-free wicks' );
	$t4_title = get_theme_mod( 'aurika_trust4_title', 'Safe & Verified' );
	$t4_desc  = get_theme_mod( 'aurika_trust4_desc', 'Instant WhatsApp support' );

	// DIY Feature settings
	$diy_eyebrow  = get_theme_mod( 'aurika_diy_eyebrow', 'FOR CHILDREN AGES 3–12' );
	$diy_title    = get_theme_mod( 'aurika_diy_title', "Spark Creativity.<br>Beyond the Screen." );
	$diy_desc     = get_theme_mod( 'aurika_diy_desc', 'Our DIY Art Kits come with everything your child needs to paint, create, and imagine. Premium concrete figures, vibrant paints, and a brush — all in one beautiful kit.' );
	$diy_img      = get_theme_mod( 'aurika_diy_img', '' ) ?: 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?q=80&w=1000&auto=format&fit=crop';
	$diy_btn_text = get_theme_mod( 'aurika_diy_btn_text', 'EXPLORE DIY KITS →' );
	$diy_btn_url  = get_theme_mod( 'aurika_diy_btn_url', '/product-category/diy-art-kits' );

	// Testimonials settings
	$rev_eyebrow = get_theme_mod( 'aurika_reviews_eyebrow', 'TESTIMONIALS' );
	$rev_title   = get_theme_mod( 'aurika_reviews_title', 'HAPPY CUSTOMERS' );
	$rev1_quote  = get_theme_mod( 'aurika_rev1_quote', 'The Lavender candle fills my entire living room within minutes. Burns clean with zero black smoke. The packaging was so aesthetic — will definitely order more as gifts!' );
	$rev1_author = get_theme_mod( 'aurika_rev1_author', 'Ayesha Khan' );
	$rev1_prod   = get_theme_mod( 'aurika_rev1_product', 'Lavender Scented Candle' );

	$rev2_quote  = get_theme_mod( 'aurika_rev2_quote', 'Ordered the Sea Shell candle set with tray. The ocean breeze notes are refreshing and long-lasting. Looked exactly like the photos and delivered safely in 2 days.' );
	$rev2_author = get_theme_mod( 'aurika_rev2_author', 'Sara Malik' );
	$rev2_prod   = get_theme_mod( 'aurika_rev2_product', 'Sea Shell Ocean Candle with Tray' );

	$rev3_quote  = get_theme_mod( 'aurika_rev3_quote', 'Got the 8-toy DIY kit for my daughter. She was completely screen-free for the entire weekend painting each figure. Safe materials and vibrant paints. 10/10 recommend!' );
	$rev3_author = get_theme_mod( 'aurika_rev3_author', 'Fatima Riaz' );
	$rev3_prod   = get_theme_mod( 'aurika_rev3_product', 'DIY Art Kit (8 Concrete Toys)' );

	$rev4_quote  = get_theme_mod( 'aurika_rev4_quote', 'The peony shape is a work of art. The subtle rose and lily scent is calming without giving a headache. Fast delivery and lovely unboxing experience.' );
	$rev4_author = get_theme_mod( 'aurika_rev4_author', 'Zainab Ali' );
	$rev4_prod   = get_theme_mod( 'aurika_rev4_product', 'Peony Flower Scented Candle' );

	$rev5_quote  = get_theme_mod( 'aurika_rev5_quote', 'Ordered 3 candles with the bundle discount. Arrived in protective packaging with zero damage. Premium wax quality and long burn hours. Very happy!' );
	$rev5_author = get_theme_mod( 'aurika_rev5_author', 'Bilal Sheikh' );
	$rev5_prod   = get_theme_mod( 'aurika_rev5_product', 'Rose Heart Candle (Pack of 3)' );

	$rev6_quote  = get_theme_mod( 'aurika_rev6_quote', 'From the craftsmanship to the fragrance intensity, Aurika matches top international brands. Proud to support a local Pakistani brand doing luxury right.' );
	$rev6_author = get_theme_mod( 'aurika_rev6_author', 'Hina Tariq' );
	$rev6_prod   = get_theme_mod( 'aurika_rev6_product', 'Daisy Flower Scented Candle' );

	// Offer banner settings
	$offer_tag      = get_theme_mod( 'aurika_offer_tag', 'LAUNCH PROMOTION' );
	$offer_title    = get_theme_mod( 'aurika_offer_title', 'Free Shipping on Orders Above Rs. 5,000' );
	$offer_desc     = get_theme_mod( 'aurika_offer_desc', 'Rs. 350 flat rate delivery on orders under Rs. 5,000 across Pakistan • Cash on Delivery Available.' );
	$offer_btn_text = get_theme_mod( 'aurika_offer_btn_text', 'SHOP NOW →' );
	$offer_btn_url  = get_theme_mod( 'aurika_offer_btn_url', '/shop' );

	$html = '<!-- HERO SECTION: Full-bleed Slider, 2 slides, 5s auto-advance -->
<div class="aurika-hero-wrap" id="aurikaHeroSlider">

  <!-- Slide Track -->
  <div class="hero-slide-track" id="heroSlideTrack">

    <!-- Slide 1 -->
    <div class="hero-slide">
      <img
        src="' . esc_url($slide1_img) . '"
        alt="Aurika — Hand-Poured Scented Candles &amp; DIY Art Kits"
        class="aurika-hero-img"
        loading="eager"
      />
      <div class="aurika-hero-overlay"></div>
    </div>

    <!-- Slide 2 -->
    <div class="hero-slide">
      <img
        src="' . esc_url($slide2_img) . '"
        alt="Aurika — DIY Art Kits &amp; Scented Candles"
        class="aurika-hero-img"
        loading="eager"
      />
      <div class="aurika-hero-overlay"></div>
    </div>

  </div>

  <!-- Hero Content (always on top) -->
  <div class="aurika-hero-content">
    <a href="' . esc_url($hero_btn_url) . '" class="btn-hero-hunza">' . esc_html($hero_btn_text) . '</a>
    <div class="aurika-hero-dots">
      <span class="hero-dot is-active" data-slide="0"></span>
      <span class="hero-dot" data-slide="1"></span>
    </div>
  </div>

</div>

<script>
(function(){
  var track  = document.getElementById("heroSlideTrack");
  var dots   = document.querySelectorAll(".aurika-hero-dots .hero-dot");
  var slides = track ? track.querySelectorAll(".hero-slide") : [];
  var current = 0;
  var total   = slides.length;
  if (!track || total < 2) return;

  function goTo(n) {
    current = (n + total) % total;
    track.style.transform = "translateX(-" + (current * 100) + "%)";
    dots.forEach(function(d, i) {
      d.classList.toggle("is-active", i === current);
    });
  }

  dots.forEach(function(dot, i) {
    dot.addEventListener("click", function() { goTo(i); });
  });

  setInterval(function() { goTo(current + 1); }, 5000);
})();
</script>

<!-- TRUST STRIP (HUNZA CANDLE STYLE) -->
<div class="trust-strip">
  <div class="trust-container">
    <div class="trust-grid">
      <div class="trust-item">
        <div class="trust-icon-box">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <div class="trust-info">
          <strong class="trust-title">' . esc_html($t1_title) . '</strong>
          <span class="trust-desc">' . esc_html($t1_desc) . '</span>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-icon-box">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </div>
        <div class="trust-info">
          <strong class="trust-title">' . esc_html($t2_title) . '</strong>
          <span class="trust-desc">' . esc_html($t2_desc) . '</span>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-icon-box">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="trust-info">
          <strong class="trust-title">' . esc_html($t3_title) . '</strong>
          <span class="trust-desc">' . esc_html($t3_desc) . '</span>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-icon-box">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <div class="trust-info">
          <strong class="trust-title">' . esc_html($t4_title) . '</strong>
          <span class="trust-desc">' . esc_html($t4_desc) . '</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TRENDING PRODUCTS -->
<div class="trending-section">
  <div class="trending-inner">
    <h2 class="section-title">' . esc_html( get_theme_mod( 'aurika_trending_title', 'TRENDING NOW' ) ) . '</h2>
    <p class="section-subtitle">' . esc_html( get_theme_mod( 'aurika_trending_subtitle', 'Our Most-Loved Creations' ) ) . '</p>
    [products limit="' . absint( get_theme_mod( 'aurika_trending_count', 8 ) ) . '" columns="4" orderby="popularity"]
    <div class="trending-btn-wrap">
      <a href="' . esc_url($shop_url) . '" class="btn-hunza-outline">' . esc_html( get_theme_mod( 'aurika_trending_btn_text', 'View All Products →' ) ) . '</a>
    </div>
  </div>
</div>

<!-- CATEGORIES -->
<div class="categories-section">
  <div class="categories-inner">
    <h2 class="section-title">' . esc_html( get_theme_mod( 'aurika_category_title', 'SHOP BY CATEGORY' ) ) . '</h2>
    <p class="section-subtitle">' . esc_html( get_theme_mod( 'aurika_category_subtitle', 'Explore Our Collections' ) ) . '</p>
    <div class="categories-grid">';

	// Dynamic WooCommerce product categories
	$cat_cards = '';
	$product_cats = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'exclude'    => array( get_option( 'default_product_cat' ) ),
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );

	if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) {
		foreach ( $product_cats as $cat ) {
			$cat_link  = get_term_link( $cat );
			$cat_thumb = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$cat_img   = $cat_thumb ? wp_get_attachment_url( $cat_thumb ) : '';
			$img_style = $cat_img ? ' style="background-image: url(' . esc_url($cat_img) . '); background-size: cover; background-position: center;"' : '';
			$img_class = 'category-img-box';
			$slug = $cat->slug;
			if ( strpos($slug, 'candle') !== false ) $img_class .= ' category-img-candles';
			elseif ( strpos($slug, 'diy') !== false || strpos($slug, 'art') !== false ) $img_class .= ' category-img-diy';

			$cat_cards .= '
      <div class="category-card">
        <a href="' . esc_url($cat_link) . '">
          <div class="' . esc_attr($img_class) . '"' . $img_style . '></div>
          <h3 class="category-title">' . esc_html($cat->name) . '</h3>
          <span class="category-link">Shop Collection &rarr;</span>
        </a>
      </div>';
		}
	}

	return $html . $cat_cards . '
    </div>
  </div>
</div>

<!-- DIY FEATURE -->
<div class="diy-feature">
  <div class="diy-feature-inner">
    <div class="diy-feature-img" style="background-image: url(\'' . esc_url($diy_img) . '\');"></div>
    <div class="diy-feature-text">
      <span class="diy-eyebrow">' . esc_html($diy_eyebrow) . '</span>
      <h2 class="diy-title">' . wp_kses_post($diy_title) . '</h2>
      <p class="diy-desc">' . esc_html($diy_desc) . '</p>
      <a href="' . esc_url($diy_btn_url) . '" class="btn-diy-hunza">' . esc_html($diy_btn_text) . '</a>
    </div>
  </div>
</div>

<!-- HAPPY CUSTOMERS (SLIDING 1 REVIEW AT A TIME ON MOBILE AFTER 5 SECS) -->
<section class="reviews-section">
  <div class="reviews-inner">
    <span class="reviews-eyebrow">' . esc_html($rev_eyebrow) . '</span>
    <h2 class="section-title">' . esc_html($rev_title) . '</h2>

    <div class="reviews-slider-wrap" id="aurikaReviewsSlider">
      <div class="reviews-track" id="aurikaReviewsTrack">
        <!-- Review 1 -->
        <div class="review-slide">
          <div class="review-card">
            <div class="review-stars-gold">★★★★★</div>
            <p class="review-text">&ldquo;' . esc_html($rev1_quote) . '&rdquo;</p>
            <div class="review-meta">
              <strong class="review-author">' . esc_html($rev1_author) . '</strong>
              <span class="review-product">' . esc_html($rev1_prod) . '</span>
            </div>
          </div>
        </div>

        <!-- Review 2 -->
        <div class="review-slide">
          <div class="review-card">
            <div class="review-stars-gold">★★★★★</div>
            <p class="review-text">&ldquo;' . esc_html($rev2_quote) . '&rdquo;</p>
            <div class="review-meta">
              <strong class="review-author">' . esc_html($rev2_author) . '</strong>
              <span class="review-product">' . esc_html($rev2_prod) . '</span>
            </div>
          </div>
        </div>

        <!-- Review 3 -->
        <div class="review-slide">
          <div class="review-card">
            <div class="review-stars-gold">★★★★★</div>
            <p class="review-text">&ldquo;' . esc_html($rev3_quote) . '&rdquo;</p>
            <div class="review-meta">
              <strong class="review-author">' . esc_html($rev3_author) . '</strong>
              <span class="review-product">' . esc_html($rev3_prod) . '</span>
            </div>
          </div>
        </div>

        <!-- Review 4 -->
        <div class="review-slide">
          <div class="review-card">
            <div class="review-stars-gold">★★★★★</div>
            <p class="review-text">&ldquo;' . esc_html($rev4_quote) . '&rdquo;</p>
            <div class="review-meta">
              <strong class="review-author">' . esc_html($rev4_author) . '</strong>
              <span class="review-product">' . esc_html($rev4_prod) . '</span>
            </div>
          </div>
        </div>

        <!-- Review 5 -->
        <div class="review-slide">
          <div class="review-card">
            <div class="review-stars-gold">★★★★★</div>
            <p class="review-text">&ldquo;' . esc_html($rev5_quote) . '&rdquo;</p>
            <div class="review-meta">
              <strong class="review-author">' . esc_html($rev5_author) . '</strong>
              <span class="review-product">' . esc_html($rev5_prod) . '</span>
            </div>
          </div>
        </div>

        <!-- Review 6 -->
        <div class="review-slide">
          <div class="review-card">
            <div class="review-stars-gold">★★★★★</div>
            <p class="review-text">&ldquo;' . esc_html($rev6_quote) . '&rdquo;</p>
            <div class="review-meta">
              <strong class="review-author">' . esc_html($rev6_author) . '</strong>
              <span class="review-product">' . esc_html($rev6_prod) . '</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Dots Indicator (Mobile Only) -->
      <div class="reviews-dots" id="aurikaReviewsDots">
        <span class="review-dot is-active" data-index="0"></span>
        <span class="review-dot" data-index="1"></span>
        <span class="review-dot" data-index="2"></span>
        <span class="review-dot" data-index="3"></span>
        <span class="review-dot" data-index="4"></span>
        <span class="review-dot" data-index="5"></span>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  var track = document.getElementById("aurikaReviewsTrack");
  var dotsContainer = document.getElementById("aurikaReviewsDots");
  if (!track || !dotsContainer) return;
  var slides = track.querySelectorAll(".review-slide");
  var dots = dotsContainer.querySelectorAll(".review-dot");
  var current = 0;
  var total = slides.length;
  var timer = null;

  function isMobile() {
    return window.innerWidth <= 768;
  }

  function goTo(n) {
    if (!isMobile()) {
      track.style.transform = "";
      return;
    }
    current = (n + total) % total;
    track.style.transform = "translateX(-" + (current * 100) + "%)";
    dots.forEach(function(d, i) {
      d.classList.toggle("is-active", i === current);
    });
  }

  dots.forEach(function(dot, i) {
    dot.addEventListener("click", function() {
      goTo(i);
      resetTimer();
    });
  });

  var startX = 0;
  var diffX = 0;
  track.addEventListener("touchstart", function(e) {
    startX = e.touches[0].clientX;
    diffX = 0;
  }, { passive: true });

  track.addEventListener("touchmove", function(e) {
    diffX = e.touches[0].clientX - startX;
  }, { passive: true });

  track.addEventListener("touchend", function() {
    if (Math.abs(diffX) > 35) {
      if (diffX < 0) {
        goTo(current + 1);
      } else {
        goTo(current - 1);
      }
      resetTimer();
    }
    diffX = 0;
  });

  function startTimer() {
    timer = setInterval(function() {
      if (isMobile()) {
        goTo(current + 1);
      }
    }, 5000);
  }

  function resetTimer() {
    if (timer) clearInterval(timer);
    startTimer();
  }

  startTimer();

  window.addEventListener("resize", function() {
    if (!isMobile()) {
      track.style.transform = "";
    } else {
      goTo(current);
    }
  });
})();
</script>

<!-- OFFER BANNER -->
<div class="offer-section">
  <span class="offer-tag">' . esc_html($offer_tag) . '</span>
  <h2 class="offer-title">' . esc_html($offer_title) . '</h2>
  <p class="offer-desc">' . esc_html($offer_desc) . '</p>
  <a href="' . esc_url($offer_btn_url) . '" class="btn-offer-hunza">' . esc_html($offer_btn_text) . '</a>
</div>';
}

function aurika_sync_home_elementor_content() {
	$home_id = (int) get_option( 'page_on_front' );
	if ( ! $home_id ) {
		$home = get_page_by_title( 'Home' );
		if ( $home ) {
			$home_id = $home->ID;
		}
	}
	if ( ! $home_id ) {
		return;
	}

	$new_content = aurika_get_home_template_html();

	// Update page content
	wp_update_post( array(
		'ID'           => $home_id,
		'post_content' => $new_content,
	) );

	// Wipe stale Astra Elementor postmeta
	delete_post_meta( $home_id, '_elementor_data' );
	delete_post_meta( $home_id, '_elementor_version' );
	delete_post_meta( $home_id, '_elementor_edit_mode' );
	delete_post_meta( $home_id, '_elementor_css' );
	delete_post_meta( $home_id, '_elementor_controls_usage' );
	delete_post_meta( $home_id, '_elementor_page_settings' );

	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	delete_transient( 'elementor_404_' . $home_id );
}

// ─────────────────────────────────────────────────────────
// 8. ONE-CLICK OFFICIAL PRODUCTS AUTO-SETUP & HOME SYNC
// ─────────────────────────────────────────────────────────
add_action( 'after_switch_theme', 'aurika_create_official_products' );

add_action( 'admin_menu', function() {
	add_theme_page(
		'Aurika Setup',
		'Aurika Setup',
		'manage_options',
		'aurika-setup-products',
		'aurika_render_product_setup_page'
	);
} );

function aurika_render_product_setup_page() {
	if ( isset( $_POST['aurika_do_sync_home'] ) && check_admin_referer( 'aurika_import_action', 'aurika_import_nonce' ) ) {
		aurika_sync_home_elementor_content();
		echo '<div class="notice notice-success is-dismissible" style="margin-top:20px; padding:12px 16px;"><p style="font-size:15px;"><strong>🎉 Homepage synced with the new 7-section luxury layout!</strong></p></div>';
	}

	if ( isset( $_POST['aurika_do_import'] ) && check_admin_referer( 'aurika_import_action', 'aurika_import_nonce' ) ) {
		$count = aurika_create_official_products();
		echo '<div class="notice notice-success is-dismissible" style="margin-top:20px; padding:12px 16px;"><p style="font-size:15px;"><strong>🎉 Success! ' . esc_html( $count ) . ' Aurika products created and published!</strong></p></div>';
	}

	// ---- NEW: Clean demo categories and demo pages ----
	if ( isset( $_POST['aurika_do_clean_cats'] ) && check_admin_referer( 'aurika_import_action', 'aurika_import_nonce' ) ) {
		$demo_cats = array( 'Body lotion', 'body-lotion', 'Cleanser', 'cleanser', 'Indoor Plants', 'indoor-plants', 'Moisturizer', 'moisturizer', 'Sunscreens', 'sunscreens', 'Sunscreen', 'sunscreen', 'Bundles', 'bundles', 'Candles', 'candles' );
		$deleted = 0;
		foreach ( $demo_cats as $cat_name ) {
			$term = get_term_by( 'name', $cat_name, 'product_cat' );
			if ( ! $term ) $term = get_term_by( 'slug', $cat_name, 'product_cat' );
			if ( $term && ! is_wp_error( $term ) ) {
				// Reassign products in this category to Scented Candles (if any)
				$products_in_cat = get_posts( array( 'post_type' => 'product', 'numberposts' => -1, 'tax_query' => array( array( 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $term->term_id ) ) ) );
				foreach ( $products_in_cat as $p ) {
					wp_delete_post( $p->ID, true );
				}
				wp_delete_term( $term->term_id, 'product_cat' );
				$deleted++;
			}
		}
		// Ensure correct Aurika categories exist
		foreach ( array( 'Scented Candles', 'DIY Art Kits for Children' ) as $cat ) {
			if ( ! get_term_by( 'name', $cat, 'product_cat' ) ) {
				wp_insert_term( $cat, 'product_cat' );
			}
		}

		// Clean up any pages containing the Astra skincare footer/content (e.g. shop-2)
		$all_pages = get_posts( array( 'post_type' => 'page', 'numberposts' => -1 ) );
		$cleaned_pages = 0;
		foreach ( $all_pages as $page_item ) {
			$raw = get_post_meta( $page_item->ID, '_elementor_data', true );
			if ( strpos( $raw, '123 Demo St' ) !== false || strpos( $raw, 'San Francisco' ) !== false || strpos( $raw, 'body-lotion' ) !== false || $page_item->post_name === 'shop-2' ) {
				if ( $page_item->post_name === 'shop-2' || strpos( strtolower( $page_item->post_title ), 'shop' ) !== false ) {
					wp_update_post( array(
						'ID'           => $page_item->ID,
						'post_content' => '[products columns="4" limit="16" paginate="true"]',
					) );
					delete_post_meta( $page_item->ID, '_elementor_data' );
					delete_post_meta( $page_item->ID, '_elementor_edit_mode' );
					delete_post_meta( $page_item->ID, '_elementor_css' );
					$cleaned_pages++;
				}
			}
		}

		if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		echo '<div class="notice notice-success is-dismissible" style="margin-top:20px; padding:12px 16px;"><p style="font-size:15px;"><strong>🧹 Cleaned! Removed ' . $deleted . ' demo categories and cleaned ' . $cleaned_pages . ' demo pages. Shop & footer are now 100% official Aurika!</strong></p></div>';
	}

	// ---- NEW: Create / Sync Policy Pages ----
	if ( isset( $_POST['aurika_do_policy_pages'] ) && check_admin_referer( 'aurika_import_action', 'aurika_import_nonce' ) ) {
		$created = aurika_ensure_policy_pages();
		echo '<div class="notice notice-success is-dismissible" style="margin-top:20px; padding:12px 16px;"><p style="font-size:15px;"><strong>📜 Success! Terms &amp; Conditions, Shipping Policy, Returns Policy, and Privacy Policy pages are active!</strong></p></div>';
	}
	?>
	<div class="wrap" style="max-width: 850px; margin-top: 30px; background: #ffffff; padding: 35px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e8e0d5;">
		<h1 style="font-size: 26px; margin-bottom: 8px; color: #111111; font-weight: 700;">🕯️ Aurika Store Management, Setup &amp; SEO Hub</h1>
		<p style="font-size: 14.5px; color: #555555; line-height: 1.7; margin-bottom: 25px;">
			Welcome to the Aurika control center. You can manage and edit 100% of your website text, images, products, navigation menus, and SEO directly using the tools below.
		</p>

		<!-- QUICK MANAGEMENT HUBS -->
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 15px; margin-bottom: 35px;">
			<a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=aurika_theme_panel') ); ?>" style="display: block; padding: 18px 20px; background: #faf8f5; border: 1px solid #e8e0d5; border-radius: 10px; text-decoration: none; color: #111; transition: all 0.2s ease;">
				<strong style="font-size: 15px; color: #b8860b; display: block; margin-bottom: 5px;">🎨 Edit Website Text &amp; Images</strong>
				<span style="font-size: 12.5px; color: #666; line-height: 1.5; display: block;">Live edit hero slider, trust badges, offer banners, reviews, WhatsApp &amp; contact info.</span>
			</a>
			<a href="<?php echo esc_url( admin_url('edit.php?post_type=product') ); ?>" style="display: block; padding: 18px 20px; background: #faf8f5; border: 1px solid #e8e0d5; border-radius: 10px; text-decoration: none; color: #111; transition: all 0.2s ease;">
				<strong style="font-size: 15px; color: #111; display: block; margin-bottom: 5px;">🛍️ Edit Products &amp; Prices</strong>
				<span style="font-size: 12.5px; color: #666; line-height: 1.5; display: block;">Manage all candles and DIY art kits, change prices, edit descriptions and photos.</span>
			</a>
			<a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>" style="display: block; padding: 18px 20px; background: #faf8f5; border: 1px solid #e8e0d5; border-radius: 10px; text-decoration: none; color: #111; transition: all 0.2s ease;">
				<strong style="font-size: 15px; color: #111; display: block; margin-bottom: 5px;">🧭 Header &amp; Footer Menus</strong>
				<span style="font-size: 12.5px; color: #666; line-height: 1.5; display: block;">Add, edit, or reorganize all links in your header navigation and 3 footer columns.</span>
			</a>
			<a href="<?php echo esc_url( admin_url('edit.php?post_type=page') ); ?>" style="display: block; padding: 18px 20px; background: #faf8f5; border: 1px solid #e8e0d5; border-radius: 10px; text-decoration: none; color: #111; transition: all 0.2s ease;">
				<strong style="font-size: 15px; color: #111; display: block; margin-bottom: 5px;">📑 Edit About, Contact &amp; Policies</strong>
				<span style="font-size: 12.5px; color: #666; line-height: 1.5; display: block;">Easily update your About Us story, Contact info, Privacy Policy, and Terms.</span>
			</a>
			<a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=aurika_seo_section') ); ?>" style="display: block; padding: 18px 20px; background: #faf8f5; border: 1px solid #e8e0d5; border-radius: 10px; text-decoration: none; color: #111; transition: all 0.2s ease;">
				<strong style="font-size: 15px; color: #27ae60; display: block; margin-bottom: 5px;">🔍 SEO &amp; WhatsApp Previews</strong>
				<span style="font-size: 12.5px; color: #666; line-height: 1.5; display: block;">Customize Google search description, keywords, and WhatsApp social share image.</span>
			</a>
		</div>

		<hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

		<h2 style="font-size: 18px; margin-bottom: 18px; color: #111;">⚡ 1-Click Store Tools &amp; Data Sync</h2>

		<form method="post" style="margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px solid #eee;">
			<?php wp_nonce_field( 'aurika_import_action', 'aurika_import_nonce' ); ?>
			<h3 style="margin-top:0;">1. Sync Homepage to Elementor</h3>
			<p style="color:#666; font-size:13.5px;">Replaces old demo containers with the new 7 luxury sections.</p>
			<button type="submit" name="aurika_do_sync_home" class="button button-primary button-hero" style="background: #b8860b; border-color: #b8860b; border-radius: 50px; padding: 10px 36px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; font-size: 13px;">
				🔄 Sync Homepage to Elementor
			</button>
		</form>

		<form method="post" style="margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px solid #eee;">
			<?php wp_nonce_field( 'aurika_import_action', 'aurika_import_nonce' ); ?>
			<h3 style="margin-top:0;">2. Create All 14 Official Products</h3>
			<p style="color:#666; font-size:13.5px;">Creates all 14 official Aurika products (12 Candles + 2 Art Kits) with prices, categories, and descriptions.</p>
			<button type="submit" name="aurika_do_import" class="button button-primary button-hero" style="background: #111111; border-color: #111111; border-radius: 50px; padding: 10px 36px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; font-size: 13px;">
				🚀 1-Click Setup All 14 Products
			</button>
		</form>

		<form method="post" style="margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px solid #eee;">
			<?php wp_nonce_field( 'aurika_import_action', 'aurika_import_nonce' ); ?>
			<h3 style="margin-top:0; color: #c0392b;">3. 🧹 Clean Demo Categories &amp; Products</h3>
			<p style="color:#666; font-size:13.5px;">Removes <strong>Body Lotion, Cleanser, Indoor Plants, Moisturizer, Sunscreens, Bundles</strong> (old Astra skincare demo) and their products. Keeps only <strong>Scented Candles</strong> and <strong>DIY Art Kits for Children</strong>.</p>
			<button type="submit" name="aurika_do_clean_cats" class="button button-primary button-hero" style="background: #c0392b; border-color: #c0392b; border-radius: 50px; padding: 10px 36px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; font-size: 13px;">
				🧹 Delete Demo Categories &amp; Products
			</button>
		</form>

		<form method="post" style="margin-bottom: 10px;">
			<?php wp_nonce_field( 'aurika_import_action', 'aurika_import_nonce' ); ?>
			<h3 style="margin-top:0; color: #2c3e50;">4. 📜 Create / Sync Store Policy Pages</h3>
			<p style="color:#666; font-size:13.5px;">Publishes <strong>Terms &amp; Conditions, Shipping Policy, Returns Policy, and Privacy Policy</strong> with official Aurika data.</p>
			<button type="submit" name="aurika_do_policy_pages" class="button button-primary button-hero" style="background: #2c3e50; border-color: #2c3e50; border-radius: 50px; padding: 10px 36px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; font-size: 13px;">
				📜 Create / Sync Policy Pages
			</button>
		</form>
	</div>
	<?php
}

// ─────────────────────────────────────────────────────────
// 7b. PREVENT 404 ON FORM SUBMISSION & CONTACT AJAX HANDLER
// ─────────────────────────────────────────────────────────
// Prevent WordPress from misinterpreting form input name="name" as a post slug query variable (which triggers 404)
add_action( 'parse_request', function( $wp ) {
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['name'] ) && ! isset( $_GET['name'] ) ) {
		unset( $wp->query_vars['name'] );
	}
}, 1 );

add_filter( 'request', function( $query_vars ) {
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $query_vars['name'] ) && isset( $_POST['name'] ) && ! isset( $_GET['name'] ) ) {
		unset( $query_vars['name'] );
	}
	return $query_vars;
}, 1 );

// Contact Form AJAX Handler
add_action( 'wp_ajax_aurika_submit_contact', 'aurika_handle_contact_ajax' );
add_action( 'wp_ajax_nopriv_aurika_submit_contact', 'aurika_handle_contact_ajax' );

function aurika_handle_contact_ajax() {
	$name    = sanitize_text_field( $_POST['contact_name'] ?? $_POST['name'] ?? '' );
	$email   = sanitize_email( $_POST['contact_email'] ?? $_POST['email'] ?? '' );
	$phone   = sanitize_text_field( $_POST['contact_phone'] ?? $_POST['phone'] ?? '' );
	$subject = sanitize_text_field( $_POST['contact_subject'] ?? $_POST['subject'] ?? 'Website Contact Inquiry' );
	$message = sanitize_textarea_field( $_POST['contact_message'] ?? $_POST['message'] ?? '' );

	if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
		wp_send_json_error( 'Please fill in all required fields (Name, Email, Message).' );
	}

	$to      = get_theme_mod( 'aurika_email', 'aurikaofficial25@gmail.com' );
	$mail_sub= 'New Contact Message from ' . $name . ' (' . $subject . ')';
	$body    = "You have received a new contact message via Aurika:\n\n"
	         . "Name: $name\n"
	         . "Email: $email\n"
	         . "Phone / WhatsApp: $phone\n"
	         . "Subject: $subject\n\n"
	         . "Message:\n$message\n\n"
	         . "--\nSent from Aurika (https://aurika.online)";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	$sent = @wp_mail( $to, $mail_sub, $body, $headers );

	wp_send_json_success( 'Message sent successfully! Our team will get back to you shortly.' );
}

function aurika_ensure_policy_pages() {
	$pages = array(
		'contact' => array(
			'title'    => 'Contact Us',
			'template' => 'page-contact.php',
		),
		'about' => array(
			'title'    => 'About Us',
			'template' => 'page-about.php',
		),
		'terms-conditions' => array(
			'title'    => 'Terms & Conditions',
			'template' => 'page-terms-conditions.php',
		),
		'shipping-policy' => array(
			'title'    => 'Shipping Policy',
			'template' => 'page-shipping-policy.php',
		),
		'returns-policy' => array(
			'title'    => 'Returns Policy',
			'template' => 'page-returns-policy.php',
		),
		'privacy-policy' => array(
			'title'    => 'Privacy Policy',
			'template' => 'page-privacy-policy.php',
		),
	);

	$created = 0;
	foreach ( $pages as $slug => $data ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			// Check by title fallback
			$page = get_page_by_title( $data['title'] );
		}
		if ( ! $page ) {
			$page_id = wp_insert_post( array(
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			) );
			if ( $page_id && ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', $data['template'] );
				delete_post_meta( $page_id, '_elementor_data' );
				delete_post_meta( $page_id, '_elementor_edit_mode' );
				$created++;
			}
		} else {
			update_post_meta( $page->ID, '_wp_page_template', $data['template'] );
			delete_post_meta( $page->ID, '_elementor_data' );
			delete_post_meta( $page->ID, '_elementor_edit_mode' );
			$created++;
		}
	}
	return $created;
}
add_action( 'after_switch_theme', 'aurika_ensure_policy_pages' );


function aurika_create_official_products() {
	if ( ! class_exists( 'WC_Product_Simple' ) ) {
		return 0;
	}

	$products = array(
		array(
			'title' => 'Lavender Scented Candle',
			'price' => '999',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'A beautifully crafted scented candle with a soothing lavender fragrance.',
			'desc'  => 'A beautifully crafted scented candle with a soothing lavender fragrance, designed to add warmth and elegance to your space. Hand-poured with premium wax and lead-free cotton wick.',
			'sku'   => 'AUR-LAV-01',
		),
		array(
			'title' => 'Sea Shell Ocean Candle',
			'price' => '699',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'A sea-shell inspired candle with a refreshing ocean fragrance.',
			'desc'  => 'A sea-shell inspired candle with a refreshing ocean fragrance, bringing a coastal touch to your space. Individually crafted with natural wax blend.',
			'sku'   => 'AUR-SEA-01',
		),
		array(
			'title' => 'Sea Shell Ocean Candle Set',
			'price' => '1200',
			'sale'  => '1100',
			'cat'   => 'Scented Candles',
			'short' => 'A charming sea-shell candle set with a refreshing ocean fragrance.',
			'desc'  => 'A charming sea-shell candle set with a refreshing ocean fragrance, perfect for adding a decorative touch to your space. Handcrafted with natural wax.',
			'sku'   => 'AUR-SEA-02',
		),
		array(
			'title' => 'Artistic Sea Shell Ocean Candle',
			'price' => '1799',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'An artistic sea-shell candle with refreshing ocean fragrance.',
			'desc'  => 'An artistic sea-shell candle with a refreshing ocean fragrance, designed to combine decorative appeal with a fragrant experience.',
			'sku'   => 'AUR-SEA-03',
		),
		array(
			'title' => 'Sea Shell Ocean Candle with Tray',
			'price' => '1499',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'A sea-shell candle set with a luxury tray.',
			'desc'  => 'A sea-shell candle set with a tray and refreshing ocean fragrance, perfect for gifting or elegant home décor.',
			'sku'   => 'AUR-SEA-04',
		),
		array(
			'title' => 'Sea Shell Ocean Candles Duo',
			'price' => '1299',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'A set of artistic sea-shell candles.',
			'desc'  => 'A set of artistic sea-shell candles with a refreshing ocean fragrance, adding charm and warmth to your space.',
			'sku'   => 'AUR-SEA-05',
		),
		array(
			'title' => 'Peony Flower Scented Candle',
			'price' => '199',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'A delicate peony flower-shaped candle.',
			'desc'  => 'A delicate peony flower-shaped candle available in lavender, rose, and lily fragrances, perfect for adding a floral touch to your space.',
			'sku'   => 'AUR-PEO-01',
		),
		array(
			'title' => 'Daisy Flower Candle',
			'price' => '150',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'A charming daisy-shaped floral candle.',
			'desc'  => 'A charming daisy-shaped candle available in rose, lily, lavender, and sunflower fragrances. Hand-poured with love.',
			'sku'   => 'AUR-DAI-01',
		),
		array(
			'title' => 'Mini Heart Hand Candles',
			'price' => '80',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'Small heart-shaped candles for gifting.',
			'desc'  => 'Small heart-shaped candles available in rose, ocean, and lavender fragrances — perfect for gifting and adding a decorative touch to your space.',
			'sku'   => 'AUR-HRT-01',
		),
		array(
			'title' => 'Aurika Rose Heart Candle',
			'price' => '999',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'An elegant heart-shaped candle with pure rose fragrance.',
			'desc'  => 'An elegant heart-shaped candle with a beautiful rose fragrance, designed to add warmth, charm, and elegance to your space.',
			'sku'   => 'AUR-HRT-02',
		),
		array(
			'title' => 'Rose Flower Scented Candle',
			'price' => '299',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'A decorative rose-shaped candle with delicate fragrance.',
			'desc'  => 'A decorative rose-shaped candle with a delicate rose fragrance, bringing a floral and elegant touch to your space.',
			'sku'   => 'AUR-ROS-01',
		),
		array(
			'title' => 'Star Fish Scented Candle',
			'price' => '1199',
			'sale'  => '',
			'cat'   => 'Scented Candles',
			'short' => 'An artistic starfish-shaped ocean candle.',
			'desc'  => 'An artistic starfish-shaped candle with a refreshing ocean fragrance, inspired by the beauty of the sea.',
			'sku'   => 'AUR-STR-01',
		),
		array(
			'title' => 'Aurika DIY Art Kit (8 Concrete Toys Edition)',
			'price' => '599',
			'sale'  => '',
			'cat'   => 'DIY Art Kits',
			'short' => 'A creative DIY painting kit with 8 concrete toys, paints & brush.',
			'desc'  => 'A creative DIY painting kit with 8 concrete toys, paints, and a brush, designed to encourage hands-on creativity and imaginative play for children ages 3-12. Includes brush, 8 concrete toys & paints.',
			'sku'   => 'AUR-DIY-08',
		),
		array(
			'title' => 'Aurika DIY Art Kit (4 Concrete Toys Edition)',
			'price' => '299',
			'sale'  => '',
			'cat'   => 'DIY Art Kits',
			'short' => 'A compact DIY painting kit with 4 concrete toys, paints & brush.',
			'desc'  => 'A compact DIY painting kit with 4 concrete toys, paints, and a brush, designed for creative, hands-on fun for children ages 3-12. Includes brush, 4 concrete toys & paints.',
			'sku'   => 'AUR-DIY-04',
		),
	);

	$created_count = 0;

	foreach ( $products as $item ) {
		// Check if product already exists by title
		$existing = get_page_by_title( $item['title'], OBJECT, 'product' );
		if ( $existing ) {
			continue;
		}

		// Ensure category exists
		$term = term_exists( $item['cat'], 'product_cat' );
		if ( ! $term ) {
			$term = wp_insert_term( $item['cat'], 'product_cat' );
		}
		$cat_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;

		$p = new WC_Product_Simple();
		$p->set_name( $item['title'] );
		$p->set_status( 'publish' );
		$p->set_catalog_visibility( 'visible' );
		$p->set_description( $item['desc'] );
		$p->set_short_description( $item['short'] );
		$p->set_sku( $item['sku'] );
		$p->set_regular_price( $item['price'] );
		if ( ! empty( $item['sale'] ) ) {
			$p->set_sale_price( $item['sale'] );
			$p->set_price( $item['sale'] );
		} else {
			$p->set_price( $item['price'] );
		}
		$p->set_manage_stock( false );
		$p->set_stock_status( 'instock' );
		$p->set_category_ids( array( $cat_id ) );
		$p->save();

		$created_count++;
	}

	return $created_count;
}

// ─────────────────────────────────────────────────────────
// 27. PRIVACY POLICY PAGE AUTO-CREATION & 200 FALLBACK
// ─────────────────────────────────────────────────────────
/**
 * Ensure Privacy Policy page exists in WordPress database
 */
function aurika_ensure_privacy_policy_page() {
	if ( ! get_page_by_path( 'privacy-policy' ) ) {
		wp_insert_post( array(
			'post_title'     => 'Privacy Policy',
			'post_name'      => 'privacy-policy',
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'page_template'  => 'page-privacy-policy.php',
			'comment_status' => 'closed',
		) );
	}
}
add_action( 'init', 'aurika_ensure_privacy_policy_page' );

/**
 * Intercept /privacy-policy/ and serve HTTP 200 immediately even if DB entry is pending
 */
function aurika_privacy_policy_template_fallback( $template ) {
	$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' ) : '';
	if ( $req_uri === 'privacy-policy' || is_page( 'privacy-policy' ) ) {
		status_header( 200 );
		global $wp_query;
		if ( $wp_query ) {
			$wp_query->is_404  = false;
			$wp_query->is_page = true;
		}
		$custom = get_template_directory() . '/page-privacy-policy.php';
		if ( file_exists( $custom ) ) {
			return $custom;
		}
	}
	return $template;
}
add_filter( 'template_include', 'aurika_privacy_policy_template_fallback', 99 );

// ─────────────────────────────────────────────────────────
// 28. VARIABLE PRODUCTS: UNIFIED BUY IT NOW & REDIRECT
// ─────────────────────────────────────────────────────────
/**
 * Add "BUY IT NOW" button right inside the variation form button row
 */
add_action( 'woocommerce_after_add_to_cart_button', function() {
	global $product;
	if ( $product && $product->is_type( 'variable' ) ) {
		echo '<button type="submit" name="aurika_buy_now" value="1" class="btn-buy-now aurika-var-buy-now">BUY IT NOW</button>';
	}
}, 20 );

/**
 * Redirect immediately to checkout when Buy It Now is clicked on any product
 */
add_filter( 'woocommerce_add_to_cart_redirect', function( $url ) {
	if ( ! empty( $_REQUEST['aurika_buy_now'] ) ) {
		return wc_get_checkout_url();
	}
	return $url;
}, 99 );
