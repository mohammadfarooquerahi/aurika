<?php
/**
 * Aurika Complete Built-in SEO Engine
 * Provides dynamic meta tags, OpenGraph (WhatsApp/Facebook previews),
 * Twitter Cards, Canonical URLs, and Google Schema.org JSON-LD Structured Data.
 *
 * @package Aurika
 */

defined( 'ABSPATH' ) || exit;

// ─────────────────────────────────────────────────────────
// 1. DYNAMIC DOCUMENT TITLE (OPTIMIZED FOR GOOGLE SERP)
// ─────────────────────────────────────────────────────────
add_filter( 'document_title_parts', 'aurika_seo_document_title_parts' );
function aurika_seo_document_title_parts( $title_parts ) {
	if ( is_front_page() ) {
		$title_parts['title']   = 'Aurika';
		$title_parts['tagline'] = 'Hand-Poured Scented Candles & DIY Art Kits | Luxury Home Fragrance Pakistan';
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$title_parts['site'] = 'Aurika — Handcrafted in Pakistan';
	} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$title_parts['site'] = 'Aurika Online Store';
	}
	return $title_parts;
}

// ─────────────────────────────────────────────────────────
// 2. META TAGS (DESCRIPTION, CANONICAL, ROBOTS, OPEN GRAPH)
// ─────────────────────────────────────────────────────────
add_action( 'wp_head', 'aurika_seo_meta_tags', 1 );
function aurika_seo_meta_tags() {
	// Skip if a third-party SEO plugin is active to prevent duplicates
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || class_exists( 'All_in_One_SEO_Pack' ) || defined( 'SEOPRESS_VERSION' ) ) {
		return;
	}

	global $post;

	$site_name   = get_bloginfo( 'name' );
	$site_url    = home_url( '/' );
	$og_type     = 'website';
	$meta_desc   = '';
	$canonical   = '';
	$og_title    = '';
	$og_image    = get_theme_mod( 'aurika_seo_og_image', get_template_directory_uri() . '/assets/images/hero-aurika.jpg' );
	$og_img_w    = '1200';
	$og_img_h    = '630';
	$robots      = 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';

	// Determine Context
	if ( is_front_page() || is_home() ) {
		$og_title  = $site_name . ' — Where Art Meets Elegance | Hand-Poured Scented Candles & DIY Art Kits';
		$meta_desc = get_theme_mod( 'aurika_seo_meta_desc', 'Discover Aurika — handcrafted luxury scented candles, aromatherapy candles, and screen-free DIY art kits for children in Pakistan. Cash on Delivery nationwide.' );
		$canonical = $site_url;
	} elseif ( function_exists( 'is_product' ) && is_product() && $post ) {
		$product   = wc_get_product( $post->ID );
		$og_type   = 'product';
		$og_title  = get_the_title() . ' — Hand-Poured Scented Candle | Aurika';
		
		// Use short description or excerpt
		$short_desc = $product ? $product->get_short_description() : '';
		if ( ! empty( $short_desc ) ) {
			$meta_desc = wp_strip_all_tags( $short_desc );
		} else {
			$meta_desc = wp_strip_all_tags( $post->post_content );
		}
		$meta_desc = wp_trim_words( $meta_desc, 30, '...' );
		$canonical = get_permalink();

		// Featured Image
		if ( has_post_thumbnail( $post->ID ) ) {
			$img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
			if ( $img_src ) {
				$og_image = $img_src[0];
				$og_img_w = $img_src[1];
				$og_img_h = $img_src[2];
			}
		}
	} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$current_cat = get_queried_object();
		$og_title    = $current_cat->name . ' — Buy Online at Aurika Pakistan';
		$meta_desc   = ! empty( $current_cat->description ) ? wp_strip_all_tags( $current_cat->description ) : 'Shop ' . esc_html( $current_cat->name ) . ' handcrafted with premium natural wax and luxury fragrances. Cash on delivery across Pakistan.';
		$canonical   = get_term_link( $current_cat );
	} elseif ( function_exists( 'is_cart' ) && is_cart() || function_exists( 'is_checkout' ) && is_checkout() || function_exists( 'is_account_page' ) && is_account_page() ) {
		$robots    = 'noindex, follow';
		$og_title  = get_the_title() . ' — ' . $site_name;
		$canonical = get_permalink();
	} elseif ( is_page() && $post ) {
		$og_title  = get_the_title() . ' — ' . $site_name;
		if ( ! empty( $post->post_excerpt ) ) {
			$meta_desc = wp_strip_all_tags( $post->post_excerpt );
		} else {
			$meta_desc = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '...' );
		}
		$canonical = get_permalink();

		if ( has_post_thumbnail( $post->ID ) ) {
			$img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
			if ( $img_src ) {
				$og_image = $img_src[0];
			}
		}
	} else {
		$og_title  = wp_get_document_title();
		$canonical = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	}

	// Clean up meta description
	$meta_desc = esc_attr( trim( preg_replace( '/\s+/', ' ', $meta_desc ) ) );
	if ( empty( $meta_desc ) ) {
		$meta_desc = esc_attr( get_theme_mod( 'aurika_seo_meta_desc', 'Aurika — Hand-poured scented candles and DIY Art Kits for kids. Handcrafted in Pakistan with premium wax and fragrances. Cash on Delivery available.' ) );
	}

	$keywords = esc_attr( get_theme_mod( 'aurika_seo_meta_keywords', 'scented candles pakistan, hand poured candles, aromatherapy candles, DIY art kits for kids, luxury home decor, scented candle online shopping, cash on delivery candles pakistan, Aurika' ) );

	echo "\n<!-- Aurika SEO Engine -->\n";
	echo '<meta name="robots" content="' . esc_attr( $robots ) . '">' . "\n";
	echo '<meta name="description" content="' . $meta_desc . '">' . "\n";
	if ( ! empty( $keywords ) ) {
		echo '<meta name="keywords" content="' . $keywords . '">' . "\n";
	}
	if ( ! empty( $canonical ) ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
	}

	// Open Graph (WhatsApp, Facebook, LinkedIn, Pinterest)
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . $meta_desc . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $canonical ?: $site_url ) . '">' . "\n";
	echo '<meta property="og:locale" content="en_PK">' . "\n";
	if ( ! empty( $og_image ) ) {
		echo '<meta property="og:image" content="' . esc_url( $og_image ) . '">' . "\n";
		echo '<meta property="og:image:width" content="' . esc_attr( $og_img_w ) . '">' . "\n";
		echo '<meta property="og:image:height" content="' . esc_attr( $og_img_h ) . '">' . "\n";
		echo '<meta property="og:image:alt" content="' . esc_attr( $og_title ) . '">' . "\n";
	}

	// WooCommerce Product Specific OpenGraph
	if ( function_exists( 'is_product' ) && is_product() && isset( $product ) && $product ) {
		echo '<meta property="product:price:amount" content="' . esc_attr( $product->get_price() ) . '">' . "\n";
		echo '<meta property="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '">' . "\n";
		echo '<meta property="product:availability" content="' . ( $product->is_in_stock() ? 'instock' : 'outofstock' ) . '">' . "\n";
		echo '<meta property="product:condition" content="new">' . "\n";
	}

	// Twitter Cards
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $og_title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . $meta_desc . '">' . "\n";
	if ( ! empty( $og_image ) ) {
		echo '<meta name="twitter:image" content="' . esc_url( $og_image ) . '">' . "\n";
	}
	echo "<!-- / Aurika SEO Engine -->\n\n";
}

// ─────────────────────────────────────────────────────────
// 3. SCHEMA.ORG JSON-LD STRUCTURED DATA (GOOGLE RICH RESULTS)
// ─────────────────────────────────────────────────────────
add_action( 'wp_footer', 'aurika_seo_schema_jsonld', 99 );
function aurika_seo_schema_jsonld() {
	if ( ! get_theme_mod( 'aurika_seo_schema_enabled', true ) ) {
		return;
	}

	$site_name = get_bloginfo( 'name' );
	$site_url  = home_url( '/' );
	$phone     = get_theme_mod( 'aurika_whatsapp', '+92 321 1632101' );
	$logo_id   = get_theme_mod( 'custom_logo' );
	$logo_url  = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : get_template_directory_uri() . '/assets/images/hero-aurika.jpg';

	// Organization Schema
	$org_schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Organization',
		'name'          => $site_name,
		'alternateName' => 'Aurika Candles & DIY Art Kits',
		'url'           => $site_url,
		'logo'          => esc_url( $logo_url ),
		'description'   => get_theme_mod( 'aurika_seo_meta_desc', 'Hand-poured scented candles and DIY Art Kits for kids in Pakistan.' ),
		'telephone'     => $phone,
		'sameAs'        => array_filter( array(
			get_theme_mod( 'aurika_instagram', 'https://instagram.com/aurikaofficial' ),
			get_theme_mod( 'aurika_facebook', 'https://facebook.com/aurikaofficial' ),
			get_theme_mod( 'aurika_tiktok', 'https://tiktok.com/@aurikaofficial' ),
		) ),
		'contactPoint'  => array(
			'@type'             => 'ContactPoint',
			'telephone'         => $phone,
			'contactType'       => 'Customer Service',
			'areaServed'        => 'PK',
			'availableLanguage' => array( 'English', 'Urdu' ),
		),
	);

	// WebSite Searchbox Schema
	$website_schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'WebSite',
		'name'            => $site_name,
		'url'             => $site_url,
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => esc_url( $site_url ) . '?s={search_term_string}&post_type=product',
			'query-input' => 'required name=search_term_string',
		),
	);

	// Developer Schema (Mohammad Farooque — Computer Science Graduate & Web App Developer)
	$dev_schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Person',
		'name'        => 'Mohammad Farooque',
		'jobTitle'    => 'Web Application Developer',
		'description' => 'Computer Science Graduate & Web Application Developer',
		'url'         => 'https://www.linkedin.com/in/mohammadfarooque/',
		'sameAs'      => array( 'https://www.linkedin.com/in/mohammadfarooque/' ),
		'alumniOf'    => array(
			'@type' => 'EducationalOrganization',
			'name'  => 'Computer Science Graduate',
		),
		'knowsAbout'  => array( 'Web Application Development', 'WordPress', 'WooCommerce', 'PHP', 'Full Stack Development' ),
	);

	echo "\n" . '<script type="application/ld+json">' . "\n" . wp_json_encode( $org_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "\n" . '</script>' . "\n";
	echo '<script type="application/ld+json">' . "\n" . wp_json_encode( $website_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "\n" . '</script>' . "\n";
	echo '<script type="application/ld+json">' . "\n" . wp_json_encode( $dev_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "\n" . '</script>' . "\n";

	// Single Product Schema
	if ( function_exists( 'is_product' ) && is_product() ) {
		global $post;
		$product = wc_get_product( $post->ID );
		if ( $product ) {
			$product_img = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
			if ( empty( $product_img ) ) {
				$gallery = $product->get_gallery_image_ids();
				if ( ! empty( $gallery ) ) {
					$product_img = wp_get_attachment_image_url( $gallery[0], 'full' );
				}
			}
			if ( empty( $product_img ) ) {
				$product_img = 'https://images.unsplash.com/photo-1605651202774-7d573fd3f12d?q=80&w=800';
			}

			$prod_price = (float) $product->get_price();
			if ( $prod_price <= 0 ) {
				$prod_price = (float) $product->get_regular_price();
			}
			if ( $prod_price <= 0 && method_exists( $product, 'get_variation_price' ) ) {
				$prod_price = (float) $product->get_variation_price( 'min', true );
			}
			if ( $prod_price <= 0 ) {
				$prod_price = 599;
			}

			$prod_title = function_exists( 'aurika_clean_product_text' ) ? aurika_clean_product_text( $product->get_name() ) : $product->get_name();
			$prod_desc  = function_exists( 'aurika_clean_product_text' ) ? aurika_clean_product_text( wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() ) ) : wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() );

			$product_schema = array(
				'@context'    => 'https://schema.org',
				'@type'       => 'Product',
				'name'        => $prod_title,
				'image'       => esc_url( $product_img ),
				'description' => $prod_desc,
				'sku'         => $product->get_sku() ?: (string) $product->get_id(),
				'brand'       => array(
					'@type' => 'Brand',
					'name'  => 'Aurika',
				),
				'offers'      => array(
					'@type'           => 'Offer',
					'url'             => get_permalink( $product->get_id() ),
					'priceCurrency'   => 'PKR',
					'price'           => $prod_price,
					'priceValidUntil' => gmdate( 'Y-12-31', strtotime( '+1 year' ) ),
					'itemCondition'   => 'https://schema.org/NewCondition',
					'availability'    => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
					'seller'          => array(
						'@type' => 'Organization',
						'name'  => 'Aurika',
					),
				),
			);

			if ( $product->get_rating_count() > 0 ) {
				$product_schema['aggregateRating'] = array(
					'@type'       => 'AggregateRating',
					'ratingValue' => $product->get_average_rating(),
					'reviewCount' => $product->get_rating_count(),
				);
			}

			echo '<script type="application/ld+json">' . "\n" . wp_json_encode( $product_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "\n" . '</script>' . "\n";
		}
	}
}

// ─────────────────────────────────────────────────────────
// 4. VIRTUAL ROBOTS.TXT FILTER
// ─────────────────────────────────────────────────────────
add_filter( 'robots_txt', 'aurika_seo_robots_txt', 10, 2 );
function aurika_seo_robots_txt( $output, $public ) {
	if ( '0' === (string) $public ) {
		return $output;
	}

	$site_url = home_url( '/' );
	$custom   = "User-agent: *\n";
	$custom  .= "Disallow: /wp-admin/\n";
	$custom  .= "Allow: /wp-admin/admin-ajax.php\n";
	$custom  .= "Disallow: /cart/\n";
	$custom  .= "Disallow: /checkout/\n";
	$custom  .= "Disallow: /my-account/\n";
	$custom  .= "Disallow: /*add-to-cart=*\n";
	$custom  .= "Allow: /wp-content/uploads/\n";
	$custom  .= "\nSitemap: " . esc_url( $site_url ) . "wp-sitemap.xml\n";

	return $custom;
}
