<?php
/**
 * Archive Product — Shop & Category Pages (Hunza Candle Luxury Style)
 *
 * @package Aurika
 */

get_header();

// Title & Descriptions
if ( is_shop() ) {
    $page_title = get_theme_mod( 'aurika_shop_title', 'The Aurika Collection' );
    $page_desc  = get_theme_mod( 'aurika_shop_subtitle', 'Hand-poured Scented Candles & DIY Art Kits' );
} elseif ( is_product_category() ) {
    $term       = get_queried_object();
    $page_title = $term->name;
    $page_desc  = $term->description ? $term->description : 'Handcrafted with love — Aurika';
} elseif ( is_product_tag() ) {
    $page_title = single_tag_title( '', false );
    $page_desc  = '';
} else {
    $page_title = get_the_archive_title();
    $page_desc  = 'Handcrafted with love — Aurika';
}

$hero_img = get_template_directory_uri() . '/assets/images/hero-aurika.jpg';
if ( is_product_category() ) {
    $term      = get_queried_object();
    $thumbnail = get_term_meta( $term->term_id, 'thumbnail_id', true );
    if ( $thumbnail ) {
        $custom_img = wp_get_attachment_url( $thumbnail );
        if ( $custom_img ) $hero_img = $custom_img;
    }
}
?>

<!-- =============================================
     1. SHOP PAGE HEADER (Dark Luxury Style)
============================================ -->
<div class="shop-page-header" style="background-image: radial-gradient(circle at center, rgba(30, 24, 15, 0.78) 0%, rgba(15, 15, 20, 0.96) 100%), url('<?php echo esc_url($hero_img); ?>'); background-size: cover; background-position: center;">
  <div class="shop-header-content">
    <span class="page-hero-eyebrow" style="color: var(--gold); font-size: 11px; font-weight: 700; letter-spacing: 5px; text-transform: uppercase; margin-bottom: 12px; display: block;"><?php echo esc_html( get_theme_mod( 'aurika_shop_hero_eyebrow', 'WHERE ART MEETS ELEGANCE' ) ); ?></span>
    <h1 class="shop-page-title" style="font-family: var(--font-serif); font-size: 42px; color: #ffffff; margin: 0 0 10px;"><?php echo esc_html( $page_title ); ?></h1>
    <?php if ( $page_desc ) : ?>
      <p class="shop-page-desc" style="font-family: var(--font-serif); font-style: italic; font-size: 17px; color: rgba(255,255,255,0.75); margin: 0 0 18px;"><?php echo wp_kses_post( $page_desc ); ?></p>
    <?php endif; ?>
    <!-- Breadcrumb -->
    <nav class="shop-breadcrumb" aria-label="Breadcrumb">
      <a href="<?php echo esc_url( home_url('/') ); ?>">Home</a>
      <span>&rsaquo;</span>
      <?php if ( is_product_category() ) : ?>
        <a href="<?php echo esc_url( aurika_shop_url() ); ?>">Shop</a>
        <span>&rsaquo;</span>
        <span><?php echo esc_html( $page_title ); ?></span>
      <?php else : ?>
        <span>Shop</span>
      <?php endif; ?>
    </nav>
  </div>
</div>

<!-- =============================================
     2. CATEGORY PILLS BAR (Quick Switcher)
============================================= -->
<div class="aurika-shop-pills-bar">
  <div style="max-width: 1340px; margin: 0 auto; display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 10px;">
    <a href="<?php echo esc_url( aurika_shop_url() ); ?>" class="aurika-cat-pill <?php echo is_shop() ? 'is-active' : ''; ?>">
      <span>✨ All Products</span>
    </a>
    <?php
    $shop_cats = get_terms( array(
      'taxonomy'   => 'product_cat',
      'hide_empty' => true,
      'exclude'    => array( get_option( 'default_product_cat' ) ),
      'orderby'    => 'count',
      'order'      => 'DESC',
    ) );
    if ( ! empty( $shop_cats ) && ! is_wp_error( $shop_cats ) ) :
      foreach ( $shop_cats as $scat ) :
        $is_curr = is_product_category( $scat->term_id );
        $icon = ( strpos( $scat->slug, 'candle' ) !== false ) ? '🕯️ ' : ( ( strpos( $scat->slug, 'diy' ) !== false || strpos( $scat->slug, 'art' ) !== false ) ? '🎨 ' : '✨ ' );
    ?>
    <a href="<?php echo esc_url( get_term_link( $scat ) ); ?>" class="aurika-cat-pill <?php echo $is_curr ? 'is-active' : ''; ?>">
      <span><?php echo esc_html( $icon . $scat->name ); ?></span>
    </a>
    <?php
      endforeach;
    endif;
    ?>
  </div>
</div>

<!-- =============================================
     3. SHOP MAIN CONTENT (1340px 4-Column Grid)
============================================= -->
<div class="shop-page-wrap">
  <div class="shop-container" style="max-width: 1340px; margin: 0 auto; padding: 0 24px;">

    <?php if ( woocommerce_product_loop() ) : ?>

    <!-- TOOLBAR: Filter + Result Count + Sort -->
    <div class="shop-toolbar">
      <div class="shop-toolbar-left">
        <span class="shop-result-count">
          <?php woocommerce_result_count(); ?>
        </span>
      </div>
      <div class="shop-toolbar-right">
        <!-- Sort -->
        <div class="shop-sort-wrap">
          <?php woocommerce_catalog_ordering(); ?>
        </div>
      </div>
    </div>

    <!-- PRODUCTS GRID -->
    <div class="shop-products-grid" data-cols="4">
      <?php
      woocommerce_product_loop_start();

      if ( wc_get_loop_prop( 'total' ) ) {
          while ( have_posts() ) {
              the_post();
              do_action( 'woocommerce_shop_loop' );
              wc_get_template_part( 'content', 'product' );
          }
      }

      woocommerce_product_loop_end();
      ?>
    </div>

    <!-- PAGINATION -->
    <div class="shop-pagination" style="margin-top: 50px; text-align: center;">
      <?php woocommerce_pagination(); ?>
    </div>

    <?php else : ?>

      <!-- NO PRODUCTS FOUND -->
      <div class="shop-empty" style="text-align: center; padding: 90px 20px;">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#b8860b" stroke-width="1.5" style="margin-bottom: 20px;"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
        <h3 style="font-family: var(--font-serif); font-size: 24px; color: #111; margin-bottom: 8px;">No Products Found</h3>
        <p style="color: #777; margin-bottom: 25px;">We couldn't find any products in this collection.</p>
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn-solid" style="display: inline-block; background: #111; color: #fff; padding: 14px 38px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 12px; letter-spacing: 2px; text-transform: uppercase;">Return to Home</a>
      </div>

    <?php endif; ?>

  </div>
</div>

<?php get_footer(); ?>
