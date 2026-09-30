<?php
/**
 * Archive Product — Shop & Category Pages
 * Matches hunzacandle.com collection page style
 */
get_header();

// Get current page title
if ( is_shop() ) {
    $page_title = get_theme_mod( 'aurika_shop_title', 'Our Shop' );
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
    $page_desc  = '';
}
?>

<!-- =============================================
     SHOP PAGE HEADER (dark, like hunzacandle)
============================================= -->
<div class="shop-page-header">
  <?php
  // Category image if set
  if ( is_product_category() ) {
      $term      = get_queried_object();
      $thumbnail = get_term_meta( $term->term_id, 'thumbnail_id', true );
      if ( $thumbnail ) {
          $img_url = wp_get_attachment_url( $thumbnail );
          echo '<div class="shop-header-bg" style="background-image:url(' . esc_url($img_url) . ')"></div>';
          echo '<div class="shop-header-overlay"></div>';
      }
  }
  ?>
  <div class="shop-header-content">
    <h1 class="shop-page-title"><?php echo esc_html( $page_title ); ?></h1>
    <?php if ( $page_desc ) : ?>
      <p class="shop-page-desc"><?php echo wp_kses_post( $page_desc ); ?></p>
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
     SHOP MAIN CONTENT
============================================= -->
<div class="shop-page-wrap">
  <div class="container">

    <?php if ( woocommerce_product_loop() ) : ?>

    <!-- TOOLBAR: Filter + Grid + Sort -->
    <div class="shop-toolbar">
      <div class="shop-toolbar-left">
        <button class="shop-filter-btn" id="shopFilterToggle">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="20" y2="12"/><line x1="12" y1="18" x2="20" y2="18"/></svg>
          Filter
        </button>
        <span class="shop-result-count">
          <?php woocommerce_result_count(); ?>
        </span>
      </div>
      <div class="shop-toolbar-right">
        <!-- Grid toggle buttons (like hunzacandle) -->
        <div class="grid-toggle" role="group" aria-label="Product grid layout">
          <button class="grid-btn active" data-cols="5" aria-label="5 columns">
            <svg width="18" height="14" viewBox="0 0 20 14" fill="currentColor"><rect x="0" y="0" width="3" height="14"/><rect x="4" y="0" width="3" height="14"/><rect x="8" y="0" width="3" height="14"/><rect x="12" y="0" width="3" height="14"/><rect x="16" y="0" width="3" height="14"/></svg>
          </button>
          <button class="grid-btn" data-cols="4" aria-label="4 columns">
            <svg width="16" height="14" viewBox="0 0 17 14" fill="currentColor"><rect x="0" y="0" width="3" height="14"/><rect x="5" y="0" width="3" height="14"/><rect x="10" y="0" width="3" height="14"/><rect x="15" y="0" width="3" height="14"/></svg>
          </button>
          <button class="grid-btn" data-cols="3" aria-label="3 columns">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor"><rect x="0" y="0" width="3" height="14"/><rect x="5" y="0" width="3" height="14"/><rect x="11" y="0" width="3" height="14"/></svg>
          </button>
          <button class="grid-btn" data-cols="2" aria-label="2 columns">
            <svg width="10" height="14" viewBox="0 0 10 14" fill="currentColor"><rect x="0" y="0" width="4" height="14"/><rect x="6" y="0" width="4" height="14"/></svg>
          </button>
        </div>
        <!-- Sort -->
        <div class="shop-sort-wrap">
          <?php woocommerce_catalog_ordering(); ?>
        </div>
      </div>
    </div>

    <!-- FILTER SIDEBAR (collapsible) -->
    <div class="shop-filter-sidebar" id="shopFilterSidebar">
      <div class="shop-filter-inner">
        <!-- Category Filter -->
        <div class="filter-group">
          <h4 class="filter-group-title">Categories</h4>
          <ul class="filter-cat-list">
            <?php
            $cats = get_terms( array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'exclude'    => array( get_option('default_product_cat') ),
            ) );
            if ( $cats && ! is_wp_error( $cats ) ) :
                foreach ( $cats as $cat ) :
                    $active = ( is_product_category( $cat->slug ) ) ? ' class="active"' : '';
                    echo '<li' . $active . '><a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . ' <span>(' . $cat->count . ')</span></a></li>';
                endforeach;
            endif;
            ?>
          </ul>
        </div>
        <!-- Price Filter -->
        <div class="filter-group">
          <h4 class="filter-group-title">Price Range</h4>
          <?php the_widget( 'WC_Widget_Price_Filter' ); ?>
        </div>
      </div>
    </div>

    <!-- PRODUCT GRID -->
    <div class="shop-products-grid" id="shopProductGrid" data-cols="5">
      <?php
      woocommerce_product_loop_start();

      while ( have_posts() ) :
          the_post();
          wc_get_template_part( 'content', 'product' );
      endwhile;

      woocommerce_product_loop_end();
      ?>
    </div>

    <!-- PAGINATION -->
    <div class="shop-pagination">
      <?php woocommerce_pagination(); ?>
    </div>

    <?php else : ?>
      <!-- No products found -->
      <div class="shop-empty">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57l1.65-8.42H6"/></svg>
        <h3>No products found</h3>
        <p>Try browsing our full shop.</p>
        <a href="<?php echo esc_url( aurika_shop_url() ); ?>" class="btn-dark">View All Products</a>
      </div>
    <?php endif; ?>

  </div>
</div>

<!-- Grid toggle JS -->
<script>
(function() {
  var gridBtns = document.querySelectorAll('.grid-btn');
  var grid     = document.getElementById('shopProductGrid');

  gridBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      var cols = this.getAttribute('data-cols');
      gridBtns.forEach(function(b) { b.classList.remove('active'); });
      this.classList.add('active');
      if (grid) {
        grid.setAttribute('data-cols', cols);
        grid.style.setProperty('--shop-cols', cols);
      }
    });
  });

  // Filter sidebar toggle
  var filterBtn  = document.getElementById('shopFilterToggle');
  var filterSide = document.getElementById('shopFilterSidebar');
  if (filterBtn && filterSide) {
    filterBtn.addEventListener('click', function() {
      filterSide.classList.toggle('is-open');
      filterBtn.classList.toggle('active');
    });
  }
})();
</script>

<?php get_footer(); ?>
