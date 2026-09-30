<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="icon" type="image/svg+xml" href="<?php echo esc_url( get_template_directory_uri() . '/assets/images/favicon.svg' ); ?>">
<link rel="alternate icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/images/favicon.svg' ); ?>">
<link rel="apple-touch-icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/images/favicon.svg' ); ?>">
<?php wp_head(); ?>
<style id="aurika-critical-mobile-head">
/* Inline critical rules to ensure overlays never render inline */
.mobile-menu-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 999999; }
.mobile-menu-overlay.is-open { display: flex !important; flex-direction: column; }
.search-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9); backdrop-filter: blur(8px); z-index: 999999; align-items: center; justify-content: center; }
.search-overlay.is-open { display: flex !important; }

/* Global Logo sanity constraint - prevents 1000px blowout on any screen or unstyled page */
img.custom-logo, .custom-logo-link img, .site-logo img {
  max-height: 60px !important;
  width: auto !important;
  object-fit: contain !important;
}

/* Critical About Page SVG constraint (eliminates giant black cubes on about page) */
.about-page-main svg, .belief-card svg, .about-pillar-icon svg {
  max-width: 28px !important;
  max-height: 28px !important;
  height: auto !important;
  fill: #b8860b !important;
}

/* Ensure account page outline icons and nav icons are NEVER filled with solid gold paint */
.woocommerce-MyAccount-navigation svg,
.woocommerce-MyAccount-content svg,
.aurika-dash-card svg,
.aurika-account-card svg,
.aurika-profile-right svg,
.dash-card-icon svg,
.header-actions svg,
.mobile-menu-toggle svg {
  fill: none !important;
  stroke: currentColor !important;
}

/* Developer credit 5% opacity / 10% on hover */
.footer-dev-credit {
  opacity: 0.05 !important;
  transition: opacity 0.3s ease !important;
}
.footer-dev-credit:hover {
  opacity: 0.10 !important;
}

/* Dynamic Product Image Fit (Crop vs Contain) from Customizer */
<?php
$aurika_prod_fit = get_theme_mod( 'aurika_product_image_fit', 'contain' );
$aurika_card_fit = get_theme_mod( 'aurika_card_image_fit', 'cover' );
?>
.gallery-slide,
.product-main-image img,
.product-main-image-wrap img {
  object-fit: <?php echo esc_attr( $aurika_prod_fit ); ?> !important;
  object-position: center center !important;
  background-color: #faf8f5 !important;
}
.hunza-card-img,
ul.products li.product a img,
ul.products li.product > a > img,
.woocommerce ul.products li.product a img {
  object-fit: <?php echo esc_attr( $aurika_card_fit ); ?> !important;
  object-position: center center !important;
}

/* Critical Mobile Styling (Instantly rendered on phones even before external CSS loads) */
@media (max-width: 768px) {
  .site-header {
    position: sticky !important;
    top: 0 !important;
    z-index: 9999 !important;
    background: #111111 !important;
    border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.4) !important;
  }
  .header-row-a {
    background: #111111 !important;
    padding: 0 10px !important;
    height: 52px !important;
    border-bottom: none !important;
  }
  .header-inner {
    height: 52px !important;
    padding: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
  }
  .mobile-menu-toggle {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 36px !important;
    height: 36px !important;
    padding: 0 !important;
    margin: 0 6px 0 0 !important;
    background: transparent !important;
    border: none !important;
    cursor: pointer !important;
    flex-shrink: 0 !important;
  }
  .mobile-menu-toggle svg {
    stroke: #ffffff !important;
    width: 22px !important;
    height: 22px !important;
    stroke-width: 2px !important;
  }
  .site-logo {
    flex: none !important;
    text-align: left !important;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    margin: 0 auto 0 0 !important;
    padding: 0 !important;
  }
  .site-logo .site-name-link {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    text-decoration: none !important;
    color: #ffffff !important;
  }
  .site-logo .logo-circle {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 30px !important;
    height: 30px !important;
    background: #d4af37 !important;
    color: #111111 !important;
    border-radius: 50% !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    line-height: 30px !important;
  }
  .site-name-text {
    font-size: 16px !important;
    font-weight: 700 !important;
    letter-spacing: 2px !important;
    color: #ffffff !important;
    text-transform: uppercase !important;
  }
  .site-tagline {
    display: none !important;
  }
  .site-logo img,
  .custom-logo {
    max-height: 44px !important;
    width: auto !important;
  }
  .header-actions {
    display: flex !important;
    align-items: center !important;
    gap: 2px !important;
    margin-left: auto !important;
    flex-shrink: 0 !important;
  }
  .header-account-btn,
  .header-social,
  .header-row-b {
    display: none !important;
  }
  .header-icon-btn {
    width: 32px !important;
    height: 36px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    background: transparent !important;
    border: none !important;
  }
  .header-search-btn svg,
  .cart-link svg {
    stroke: #ffffff !important;
    width: 20px !important;
    height: 20px !important;
    stroke-width: 1.8px !important;
  }
  .site-footer .footer-grid {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 16px !important;
  }
  .site-footer .footer-col.footer-col-brand {
    grid-column: 1 / -1 !important;
  }
  /* Mobile Single Product Gallery */
  .product-gallery-col {
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    margin-bottom: 14px !important;
    position: static !important;
  }
  .product-main-image-wrap {
    order: 1 !important;
    width: 100% !important;
    height: 350px !important;
    max-height: 380px !important;
    min-height: 280px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    position: relative !important;
    background: #faf8f5 !important;
    border: 1px solid #f0ebe4 !important;
    border-radius: 10px !important;
    overflow: hidden !important;
    margin-bottom: 10px !important;
  }
  .product-main-image {
    width: 100% !important;
    height: 100% !important;
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
  }
  .product-main-image .gallery-slide {
    width: 100% !important;
    height: 100% !important;
    object-fit: <?php echo esc_attr( $aurika_prod_fit ); ?> !important;
    object-position: center center !important;
    display: none !important;
  }
  .product-main-image .gallery-slide.active {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    position: relative !important;
  }
  .product-thumb-strip {
    order: 2 !important;
    display: flex !important;
    flex-direction: row !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 8px !important;
    width: 100% !important;
    margin: 0 !important;
    overflow-x: auto !important;
  }
  .product-thumb-item {
    width: 52px !important;
    height: 52px !important;
    flex-shrink: 0 !important;
    border-radius: 6px !important;
    overflow: hidden !important;
  }
}

/* Universal Single Product Variation Swatches (Piece Count Buttons) */
table.variations { display: block !important; width: 100% !important; border: none !important; margin: 0 0 12px !important; }
table.variations tbody, table.variations tr { display: block !important; width: 100% !important; border: none !important; margin: 0 0 10px !important; }
table.variations th.label { display: block !important; width: 100% !important; text-align: left !important; padding: 0 0 6px !important; border: none !important; font-size: 11.5px !important; font-weight: 700 !important; letter-spacing: 1.5px !important; text-transform: uppercase !important; color: #111111 !important; }
table.variations td.value { display: block !important; width: 100% !important; padding: 0 !important; border: none !important; }
.cfvsw-swatches-container, .variations .variable-items-wrapper { display: flex !important; flex-wrap: wrap !important; gap: 8px !important; align-items: center !important; margin: 4px 0 !important; }
.cfvsw-swatches-container .cfvsw-swatches-option, .cfvsw-swatches-option.cfvsw-label-option, .variations .variable-items-wrapper .variable-item { display: inline-flex !important; align-items: center !important; justify-content: center !important; min-width: 80px !important; width: auto !important; height: 40px !important; min-height: 40px !important; padding: 0 16px !important; border: 1.5px solid #d4af37 !important; border-radius: 50px !important; background: #faf8f5 !important; color: #111111 !important; cursor: pointer !important; box-sizing: border-box !important; margin: 0 !important; }
.cfvsw-swatches-container .cfvsw-swatches-option .cfvsw-swatch-inner, .cfvsw-label-option .cfvsw-swatch-inner, .variations .variable-items-wrapper .variable-item .variable-item-span { font-size: 12px !important; font-weight: 600 !important; color: #111111 !important; line-height: 1 !important; white-space: nowrap !important; }
.cfvsw-swatches-container .cfvsw-swatches-option.cfvsw-selected-attribute, .cfvsw-swatches-container .cfvsw-swatches-option.selected, .cfvsw-swatches-option.cfvsw-label-option.cfvsw-selected-attribute, .variations .variable-items-wrapper .variable-item.selected { background: #111111 !important; color: #ffffff !important; border-color: #111111 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.18) !important; }
.cfvsw-swatches-container .cfvsw-swatches-option.cfvsw-selected-attribute .cfvsw-swatch-inner, .cfvsw-swatches-option.cfvsw-label-option.cfvsw-selected-attribute .cfvsw-swatch-inner, .variations .variable-items-wrapper .variable-item.selected .variable-item-span { color: #ffffff !important; font-weight: 700 !important; }
.reset_variations { display: inline-block !important; font-size: 11px !important; color: #888 !important; text-decoration: underline !important; margin-top: 4px !important; }

/* Universal Horizontal Quantity Pill & Buttons */
.quantity, .product-qty-wrap { display: inline-flex !important; flex-direction: row !important; align-items: center !important; justify-content: center !important; height: 46px !important; width: 106px !important; min-width: 106px !important; max-width: 106px !important; border: 1.5px solid #111111 !important; border-radius: 50px !important; background: #ffffff !important; overflow: hidden !important; padding: 0 !important; margin: 0 !important; box-sizing: border-box !important; flex-shrink: 0 !important; }
.quantity .qty-btn, .product-qty-wrap .qty-btn { width: 33px !important; height: 100% !important; background: transparent !important; border: none !important; font-size: 18px !important; font-weight: 700 !important; color: #111111 !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important; padding: 0 !important; margin: 0 !important; line-height: 1 !important; flex-shrink: 0 !important; }
.quantity input.qty, .product-qty-wrap input.qty { width: 40px !important; min-width: 40px !important; max-width: 40px !important; height: 100% !important; border: none !important; background: transparent !important; text-align: center !important; font-size: 15px !important; font-weight: 700 !important; color: #111111 !important; padding: 0 !important; margin: 0 !important; outline: none !important; box-shadow: none !important; -moz-appearance: textfield !important; }
.quantity input.qty::-webkit-outer-spin-button, .quantity input.qty::-webkit-inner-spin-button { -webkit-appearance: none !important; margin: 0 !important; }

/* Add to Cart & Buy It Now Buttons - Simple & Variable Unified */
.product-qty-cart-row { display: flex !important; flex-direction: row !important; align-items: center !important; gap: 8px !important; width: 100% !important; margin-top: 12px !important; margin-bottom: 8px !important; }
.woocommerce-variation-add-to-cart { display: flex !important; flex-wrap: wrap !important; flex-direction: row !important; align-items: center !important; gap: 8px !important; width: 100% !important; margin-top: 12px !important; margin-bottom: 8px !important; }
.woocommerce-variation-add-to-cart .quantity { flex: 0 0 106px !important; width: 106px !important; max-width: 106px !important; height: 46px !important; margin: 0 !important; order: 1 !important; }
.woocommerce-variation-add-to-cart .single_add_to_cart_button, .product-qty-cart-row .btn-add-to-cart, .btn-add-to-cart { flex: 1 1 auto !important; height: 46px !important; background: #111111 !important; color: #ffffff !important; border: none !important; border-radius: 50px !important; font-size: 12px !important; font-weight: 700 !important; letter-spacing: 1.5px !important; text-transform: uppercase !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; padding: 0 16px !important; white-space: nowrap !important; order: 2 !important; margin: 0 !important; opacity: 1 !important; }
.btn-buy-now, .woocommerce-variation-add-to-cart .btn-buy-now { display: flex !important; align-items: center !important; justify-content: center !important; width: 100% !important; flex: 0 0 100% !important; height: 46px !important; background: #b8860b !important; color: #ffffff !important; border: none !important; border-radius: 50px !important; font-size: 12px !important; font-weight: 700 !important; letter-spacing: 1.5px !important; text-transform: uppercase !important; text-decoration: none !important; white-space: nowrap !important; margin-top: 8px !important; margin-bottom: 14px !important; order: 3 !important; }

/* Sticky Cart Bar Critical State: hidden by default until scrolled */
.sticky-cart-bar { transform: translateY(115%) !important; visibility: hidden !important; pointer-events: none !important; }
.sticky-cart-bar.is-visible { transform: translateY(0%) !important; visibility: visible !important; pointer-events: auto !important; }

</style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- =============================================
     1. SCROLLING MARQUEE BAR (HUNZA CANDLE STYLE)
============================================= -->
<?php if ( get_theme_mod( 'aurika_show_marquee', true ) ) : 
  $marquee_text = get_theme_mod( 'aurika_marquee_text', 'HAND-POURED SCENTED CANDLES & DIY ART KITS • FREE SHIPPING ON ORDERS ABOVE RS. 5,000 • AURIKA — WHERE ART MEETS ELEGANCE • CASH ON DELIVERY AVAILABLE' );
?>
<div class="marquee-bar">
  <div class="marquee-track">
    <span class="marquee-content">
      <?php echo esc_html( $marquee_text ); ?> &nbsp;&bull;&nbsp; <?php echo esc_html( $marquee_text ); ?> &nbsp;&bull;&nbsp;
    </span>
    <span class="marquee-content" aria-hidden="true">
      <?php echo esc_html( $marquee_text ); ?> &nbsp;&bull;&nbsp; <?php echo esc_html( $marquee_text ); ?> &nbsp;&bull;&nbsp;
    </span>
  </div>
</div>
<?php endif; ?>

<!-- =============================================
     2. MAIN HEADER (2 ROWS LIKE HUNZA CANDLE)
============================================= -->
<header class="site-header" id="site-header">

  <!-- ROW A: Social | Centered Logo | Right Action Icons -->
  <div class="header-row-a">
    <div class="header-inner">

      <!-- MOBILE ONLY: Hamburger Menu on Far Left (Hunza Candle Style) -->
      <button class="mobile-menu-toggle" id="openMobileMenuBtn" aria-label="Open Menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>

      <!-- LEFT: Social Icons (Desktop only) -->
      <?php
      $ig_url = get_theme_mod( 'aurika_instagram', 'https://instagram.com/aurikaofficial' );
      $fb_url = get_theme_mod( 'aurika_facebook', 'https://facebook.com/aurikaofficial' );
      $tt_url = get_theme_mod( 'aurika_tiktok', 'https://tiktok.com/@aurikaofficial' );
      $wa_num = preg_replace( '/[^0-9]/', '', get_theme_mod( 'aurika_whatsapp', '+923211632101' ) );
      ?>
      <div class="header-social">
        <?php if ( $ig_url ) : ?>
        <a href="<?php echo esc_url( $ig_url ); ?>" target="_blank" rel="noopener" aria-label="Instagram">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
        <?php endif; ?>
        <?php if ( $fb_url ) : ?>
        <a href="<?php echo esc_url( $fb_url ); ?>" target="_blank" rel="noopener" aria-label="Facebook">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <?php endif; ?>
        <?php if ( $tt_url ) : ?>
        <a href="<?php echo esc_url( $tt_url ); ?>" target="_blank" rel="noopener" aria-label="TikTok">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
        </a>
        <?php endif; ?>
        <?php if ( $wa_num ) : ?>
        <a href="https://wa.me/<?php echo esc_attr( $wa_num ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        </a>
        <?php endif; ?>
      </div>

      <!-- CENTER: Logo -->
      <div class="site-logo">
        <?php if ( has_custom_logo() ) :
          the_custom_logo();
        else : ?>
          <a href="<?php echo esc_url( home_url('/') ); ?>" class="site-name-link" rel="home">
            <span class="logo-circle">A</span>
            <span class="site-name-text"><?php bloginfo('name'); ?></span>
            <span class="site-tagline"><?php bloginfo('description') ?: 'Where Art Meets Elegance.'; ?></span>
          </a>
        <?php endif; ?>
      </div>

      <!-- RIGHT: Action Icons (Search, Account, Cart) -->
      <div class="header-actions">

        <!-- Search -->
        <button class="header-icon-btn header-search-btn" id="openSearchBtn" aria-label="Search">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </button>

        <!-- Account -->
        <a href="<?php echo esc_url( aurika_account_url() ); ?>" class="header-icon-btn header-account-btn" aria-label="My Account" title="My Account / Sign In">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </a>

        <!-- Cart -->
        <a href="<?php echo esc_url( function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart') ); ?>" class="header-icon-btn cart-link" aria-label="Shopping Cart">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
          <span class="icon-badge cart-count"><?php echo aurika_cart_count(); ?></span>
        </a>

      </div><!-- /.header-actions -->
    </div><!-- /.header-inner -->
  </div><!-- /.header-row-a -->

  <!-- ROW B: Navigation Menu (Always Complete 6 Links) -->
  <div class="header-row-b">
    <nav class="main-nav" aria-label="Primary Navigation">
      <?php
      $use_custom_menu = get_theme_mod( 'aurika_use_custom_menu', false );
      if ( $use_custom_menu && has_nav_menu( 'primary' ) ) :
          wp_nav_menu( array(
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'nav-menu',
              'fallback_cb'    => false,
              'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
          ) );
      else :
      ?>
      <ul class="nav-menu">
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li><a href="<?php echo esc_url(aurika_shop_url()); ?>">Shop</a></li>
        <li><a href="<?php echo esc_url(home_url('/product-category/scented-candles')); ?>">Scented Candles</a></li>
        <li><a href="<?php echo esc_url(home_url('/product-category/diy-art-kits')); ?>">DIY Art Kits</a></li>
        <li><a href="<?php echo esc_url(home_url('/about')); ?>">About Us</a></li>
        <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact Us</a></li>
      </ul>
      <?php endif; ?>
    </nav>
  </div><!-- /.header-row-b -->

</header><!-- /.site-header -->

<!-- =============================================
     MOBILE MENU OVERLAY (HIDDEN BY DEFAULT)
============================================= -->
<div class="mobile-menu-overlay" id="mobile-menu-overlay">
  <div class="mobile-menu-header" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; border-bottom: 1px solid #333;">
    <a href="<?php echo esc_url(home_url('/')); ?>" style="color: #fff; font-family: var(--font-serif); font-size: 20px; font-weight: 700; text-decoration: none;"><?php bloginfo('name'); ?></a>
    <button class="mobile-menu-close" id="closeMobileMenuBtn" style="background: none; border: none; color: #fff; font-size: 32px; cursor: pointer;">&times;</button>
  </div>
  <nav class="mobile-nav">
    <?php
    if ( $use_custom_menu && has_nav_menu( 'mobile' ) ) :
        wp_nav_menu( array(
            'theme_location' => 'mobile',
            'container'      => false,
            'menu_class'     => 'mobile-nav-menu',
            'fallback_cb'    => false,
            'items_wrap'     => '<ul class="%2$s" style="list-style: none; padding: 20px 25px; margin: 0;">%3$s</ul>',
        ) );
    else :
    ?>
    <ul class="mobile-nav-menu" style="list-style: none; padding: 20px 25px; margin: 0;">
      <li style="margin-bottom: 15px;"><a href="<?php echo esc_url(home_url('/')); ?>" style="color: #fff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none;">Home</a></li>
      <li style="margin-bottom: 15px;"><a href="<?php echo esc_url(aurika_shop_url()); ?>" style="color: #fff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none;">Shop</a></li>
      <li style="margin-bottom: 15px;"><a href="<?php echo esc_url(home_url('/product-category/scented-candles')); ?>" style="color: #fff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none;">Scented Candles</a></li>
      <li style="margin-bottom: 15px;"><a href="<?php echo esc_url(home_url('/product-category/diy-art-kits')); ?>" style="color: #fff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none;">DIY Art Kits</a></li>
      <li style="margin-bottom: 15px;"><a href="<?php echo esc_url(aurika_account_url()); ?>" style="color: #fff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none;">My Account / Sign In</a></li>
      <li style="margin-bottom: 15px;"><a href="<?php echo esc_url(home_url('/about')); ?>" style="color: #fff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none;">About Us</a></li>
      <li style="margin-bottom: 15px;"><a href="<?php echo esc_url(home_url('/contact')); ?>" style="color: #fff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none;">Contact Us</a></li>
    </ul>
    <?php endif; ?>
  </nav>
  <div style="padding: 20px 25px; margin-top: auto; border-top: 1px solid #333; display: flex; gap: 15px;">
    <?php if ( $ig_url ) : ?><a href="<?php echo esc_url( $ig_url ); ?>" target="_blank" style="color: #bbb; text-decoration: none; font-size: 13px;">Instagram</a><?php endif; ?>
    <?php if ( $fb_url ) : ?><a href="<?php echo esc_url( $fb_url ); ?>" target="_blank" style="color: #bbb; text-decoration: none; font-size: 13px;">Facebook</a><?php endif; ?>
    <?php if ( $tt_url ) : ?><a href="<?php echo esc_url( $tt_url ); ?>" target="_blank" style="color: #bbb; text-decoration: none; font-size: 13px;">TikTok</a><?php endif; ?>
  </div>
</div>

<!-- =============================================
     SEARCH OVERLAY (HIDDEN BY DEFAULT)
============================================= -->
<div class="search-overlay" id="search-overlay">
  <button class="search-overlay-close" id="closeSearchBtn" aria-label="Close Search">&times;</button>
  <div class="search-overlay-inner">
    <p class="search-label">What are you looking for?</p>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="search" class="search-field" placeholder="<?php echo esc_attr( get_theme_mod( 'aurika_search_placeholder', 'Search candles, art kits...' ) ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
      <button type="submit" class="search-submit">Search</button>
    </form>
  </div>
</div>
