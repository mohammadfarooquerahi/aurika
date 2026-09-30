<?php
/**
 * WooCommerce Single Product Template Override
 * Matches hunzacandle.com luxury product page layout
 *
 * @package Aurika
 */

defined('ABSPATH') || exit;

get_header();

while ( have_posts() ) :
    the_post();
    global $product;
    if ( ! $product ) $product = wc_get_product( get_the_ID() );

    $gallery_ids     = $product->get_gallery_image_ids();
    $main_img_id     = $product->get_image_id();
    $all_images      = array_filter( array_merge( array( $main_img_id ), $gallery_ids ) );
    $price_html      = $product->get_price_html();
    $rating          = $product->get_average_rating();
    $review_count    = $product->get_review_count();
    $sku             = $product->get_sku();
    $categories      = wc_get_product_category_list( $product->get_id(), ', ', '<span>', '</span>' );
    $short_desc      = $product->get_short_description();
?>

<!-- BREADCRUMB -->
<div class="product-breadcrumb-bar">
  <div class="container">
    <div class="product-breadcrumb-inner">
      <nav class="product-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <span class="sep">&rsaquo;</span>
        <a href="<?php echo esc_url( aurika_shop_url() ); ?>">Products</a>
        <span class="sep">&rsaquo;</span>
        <span class="current-prod"><?php echo esc_html( aurika_clean_product_text( get_the_title() ) ); ?></span>
      </nav>
      <div class="product-nav-arrows">
        <?php previous_post_link('<span class="nav-prev">%link</span>', '&larr;'); ?>
        <a href="<?php echo esc_url( aurika_shop_url() ); ?>" class="prod-nav-grid" aria-label="Back to shop">
          <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><rect x="0" y="0" width="6" height="6"/><rect x="10" y="0" width="6" height="6"/><rect x="0" y="10" width="6" height="6"/><rect x="10" y="10" width="6" height="6"/></svg>
        </a>
        <?php next_post_link('<span class="nav-next">%link</span>', '&rarr;'); ?>
      </div>
    </div>
  </div>
</div>

<!-- MAIN PRODUCT SECTION -->
<div class="product-page-wrap">
  <div class="container">
    <div class="product-layout">

      <!-- ======== LEFT: IMAGE GALLERY ======== -->
      <div class="product-gallery-col">

        <!-- Thumbnail strip (left side like hunzacandle) -->
        <?php if ( count($all_images) > 1 ) : ?>
        <div class="product-thumb-strip">
          <?php foreach ($all_images as $i => $img_id) :
            $thumb = wp_get_attachment_image_src( $img_id, 'woocommerce_thumbnail' );
            $full  = wp_get_attachment_image_src( $img_id, 'woocommerce_single' );
            if ( ! $thumb ) continue;
          ?>
          <div class="product-thumb-item <?php echo $i === 0 ? 'active' : ''; ?>"
               data-src="<?php echo esc_url($full[0]); ?>"
               data-index="<?php echo $i; ?>">
            <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Main Image -->
        <div class="product-main-image-wrap">
          <?php if ( count($all_images) > 1 ) : ?>
          <button class="gallery-arrow gallery-prev" aria-label="Previous image">&#8249;</button>
          <?php endif; ?>

          <div class="product-main-image" id="productMainImage">
            <?php
            if ( ! empty( $all_images ) ) :
              foreach ($all_images as $i => $img_id) :
                $full = wp_get_attachment_image_src( $img_id, 'woocommerce_single' );
                $alt  = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
                if ( ! $full ) continue;
            ?>
              <img src="<?php echo esc_url($full[0]); ?>"
                   alt="<?php echo esc_attr($alt ?: get_the_title()); ?>"
                   class="gallery-slide <?php echo $i === 0 ? 'active' : ''; ?>"
                   data-index="<?php echo $i; ?>"
                   data-no-lazy="<?php echo $i === 0 ? '1' : '0'; ?>"
                   data-skip-lazy="<?php echo $i === 0 ? '1' : '0'; ?>"
                   fetchpriority="<?php echo $i === 0 ? 'high' : 'auto'; ?>"
                   loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>">
            <?php
              endforeach;
            else :
              echo '<img src="' . esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ) . '" class="gallery-slide active" data-no-lazy="1" alt="' . esc_attr( get_the_title() ) . '">';
            endif;
            ?>
          </div>

          <?php if ( count($all_images) > 1 ) : ?>
          <button class="gallery-arrow gallery-next" aria-label="Next image">&#8250;</button>
          <?php endif; ?>
        </div>

      </div><!-- /.product-gallery-col -->

      <!-- ======== RIGHT: PRODUCT INFO ======== -->
      <div class="product-info-col">

        <!-- Title & Subtitle -->
        <h1 class="product-detail-title"><?php echo esc_html( aurika_clean_product_text( get_the_title() ) ); ?></h1>
        <?php if ( $short_desc ) : ?>
          <p class="product-detail-tagline"><?php echo esc_html( aurika_clean_product_text( wp_strip_all_tags( $short_desc ) ) ); ?></p>
        <?php else : ?>
          <p class="product-detail-tagline"><?php echo esc_html( get_theme_mod( 'aurika_product_tagline_fallback', 'Hand-poured Luxury Home Fragrance' ) ); ?></p>
        <?php endif; ?>

        <!-- Star Rating -->
        <div class="product-rating-row">
          <div class="product-stars">
            <?php
            $display_rating = ( $rating > 0 ) ? round($rating) : 5;
            for ($s = 1; $s <= 5; $s++) : ?>
              <svg width="15" height="15" viewBox="0 0 24 24" fill="<?php echo $s <= $display_rating ? '#b8860b' : '#e0e0e0'; ?>"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <?php endfor; ?>
            <a href="#reviews" class="rating-count"><?php echo max(1, (int)$review_count); ?> review<?php echo $review_count > 1 ? 's' : ''; ?></a>
          </div>
        </div>

        <!-- Price -->
        <?php
        $product_title  = $product->get_title();
        $is_diy_product = ( stripos( $product_title, 'DIY' ) !== false || stripos( $product_title, 'Art Kit' ) !== false || has_term( array( 'diy-art-kits', 'art-kits' ), 'product_cat', $product->get_id() ) );

        // Robust base price calculation for any product
        $base_price_num = (float) $product->get_price();
        if ( $base_price_num <= 0 ) {
            $base_price_num = (float) $product->get_regular_price();
        }
        if ( $base_price_num <= 0 && $product->is_type('variable') ) {
            $base_price_num = (float) $product->get_variation_price( 'min', true );
            if ( $base_price_num <= 0 ) {
                $avail_vars = $product->get_available_variations();
                foreach ( $avail_vars as $v ) {
                    if ( ! empty( $v['display_price'] ) && (float) $v['display_price'] > 0 ) {
                        $base_price_num = (float) $v['display_price'];
                        break;
                    }
                }
            }
        }
        if ( $base_price_num <= 0 ) {
            $base_price_num = 599; // Fallback so it never displays Rs. 0
        }

        $init_display_price = $is_diy_product ? (float) get_theme_mod( 'aurika_diy_small_price', 299 ) : $base_price_num;
        ?>
        <div class="product-detail-price">Rs. <?php echo number_format( $init_display_price ); ?></div>

        <div class="product-stock-status">
          <span class="stock-dot"></span>
          <span class="stock-text"><?php echo esc_html( get_theme_mod( 'aurika_product_stock_text', 'In Stock — Handcrafted in Pakistan' ) ); ?></span>
        </div>

        <!-- Excerpt -->
        <?php
        $desc = $product->get_description();
        if ( $desc ) :
        ?>
        <div class="product-short-desc">
          <?php echo wp_kses_post( wp_trim_words( aurika_clean_product_text( $desc ), 32, '...' ) ); ?>
        </div>
        <?php endif; ?>

        <!-- Add to Cart Form -->
        <?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
        <div class="product-form-wrap">

          <!-- 1, 2, 3 Pack Size Selector (Candles) OR Small / Big Selector (DIY Art Kits) -->
          <?php if ( $is_diy_product ) : ?>
          <!-- DIY Art Kit: Small vs Big Selector -->
          <div class="aurika-pack-selector-wrap aurika-diy-variant-wrap">
            <div class="pack-selector-header">
              <span class="pack-selector-title">CHOOSE KIT SIZE:</span>
              <span class="pack-selector-badge">✨ All Paints &amp; Brush Included</span>
            </div>
            <div class="pack-selector-grid">
              <?php
              $p_small = (float) get_theme_mod( 'aurika_diy_small_price', 299 );
              $p_big   = (float) get_theme_mod( 'aurika_diy_big_price', 599 );
              ?>
              <!-- Small Kit -->
              <div class="diy-variant-card is-selected" data-diy-size="small" data-diy-price="<?php echo esc_attr( $p_small ); ?>" data-diy-title="Small Kit">
                <div class="pack-card-top">
                  <span class="pack-card-name">Small Kit</span>
                  <span class="pack-tag-std">Compact</span>
                </div>
                <div class="pack-card-price">Rs. <?php echo number_format( $p_small ); ?></div>
                <div class="pack-card-sub">Compact Play Set</div>
              </div>
              <!-- Big Kit -->
              <div class="diy-variant-card" data-diy-size="big" data-diy-price="<?php echo esc_attr( $p_big ); ?>" data-diy-title="Big Kit">
                <div class="pack-card-top">
                  <span class="pack-card-name">Big Kit</span>
                  <span class="pack-tag-save">Best Value</span>
                </div>
                <div class="pack-card-price">Rs. <?php echo number_format( $p_big ); ?></div>
                <div class="pack-card-sub">Premium Play Set</div>
              </div>
            </div>
          </div>

          <?php else :
              // ALL CANDLES (Simple or Variable) get the unified 1 Pack, 2 Pack, 3 Pack Selector
              $d2       = (float) get_theme_mod( 'aurika_pack2_discount', 10 );
              $d3       = (float) get_theme_mod( 'aurika_pack3_discount', 15 );
              $f2       = max( 0.01, ( 100 - $d2 ) / 100 );
              $f3       = max( 0.01, ( 100 - $d3 ) / 100 );
              $p1       = $base_price_num;
              $p2_total = round( $base_price_num * 2 * $f2 );
              $p2_each  = round( $base_price_num * $f2 );
              $p3_total = round( $base_price_num * 3 * $f3 );
              $p3_each  = round( $base_price_num * $f3 );
          ?>
          <div class="aurika-pack-selector-wrap" data-base-price="<?php echo esc_attr( $base_price_num ); ?>">
            <div class="pack-selector-header">
              <span class="pack-selector-title">SELECT PACK SIZE:</span>
              <span class="pack-selector-badge">🔥 Save up to <?php echo round($d3); ?>%</span>
            </div>

            <div class="pack-selector-grid">
              <!-- 1 Pack -->
              <div class="pack-card is-selected" data-pack-qty="1" data-pack-price="<?php echo esc_attr( $p1 ); ?>">
                <div class="pack-card-top">
                  <span class="pack-card-name">1 Pack</span>
                  <span class="pack-tag-std">Standard</span>
                </div>
                <div class="pack-card-price">Rs. <?php echo number_format( $p1 ); ?></div>
                <div class="pack-card-sub">Single item</div>
              </div>

              <!-- 2 Pack -->
              <div class="pack-card" data-pack-qty="2" data-pack-price="<?php echo esc_attr( $p2_total ); ?>">
                <div class="pack-card-top">
                  <span class="pack-card-name">2 Pack</span>
                  <span class="pack-tag-save">Save <?php echo round($d2); ?>%</span>
                </div>
                <div class="pack-card-price">Rs. <?php echo number_format( $p2_total ); ?></div>
                <div class="pack-card-sub">Rs. <?php echo number_format( $p2_each ); ?> each</div>
              </div>

              <!-- 3 Pack -->
              <div class="pack-card" data-pack-qty="3" data-pack-price="<?php echo esc_attr( $p3_total ); ?>">
                <div class="pack-card-top">
                  <span class="pack-card-name">3 Pack</span>
                  <span class="pack-tag-best">Save <?php echo round($d3); ?>%</span>
                </div>
                <div class="pack-card-price">Rs. <?php echo number_format( $p3_total ); ?></div>
                <div class="pack-card-sub">Rs. <?php echo number_format( $p3_each ); ?> each</div>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <form class="cart product-add-form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>

            <?php if ( $is_diy_product ) : ?>
            <input type="hidden" name="aurika_diy_size" id="aurika_diy_size" value="small">
            <?php else : ?>
            <input type="hidden" name="aurika_pack" id="aurika_selected_pack" value="1">
            <?php endif; ?>

            <?php
            // If product is variable in WooCommerce, include first variation info so WooCommerce accepts the POST
            if ( $product->is_type('variable') ) {
                $vars = $product->get_available_variations();
                if ( ! empty( $vars ) ) {
                    echo '<input type="hidden" name="variation_id" value="' . esc_attr( $vars[0]['variation_id'] ) . '">';
                    if ( ! empty( $vars[0]['attributes'] ) ) {
                        foreach ( $vars[0]['attributes'] as $ak => $av ) {
                            echo '<input type="hidden" name="' . esc_attr( $ak ) . '" value="' . esc_attr( $av ) . '">';
                        }
                    }
                }
            }
            ?>

            <!-- Quantity + Add to Cart Row -->
            <div class="product-qty-cart-row">
              <div class="product-qty-wrap quantity">
                <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">&#8722;</button>
                <input type="number" id="quantity_<?php echo $product->get_id(); ?>"
                       class="input-text qty"
                       name="quantity" value="1" min="1" max="<?php echo $product->get_stock_quantity() ?: ''; ?>"
                       step="1" autocomplete="off" aria-label="Product quantity">
                <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">&#43;</button>
              </div>

              <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
              <button type="submit" class="btn-add-to-cart single_add_to_cart_button">ADD TO CART</button>
            </div>

            <!-- Buy It Now Button -->
            <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_checkout_page_id') ) . '?add-to-cart=' . $product->get_id() . '&quantity=1' . ( ! $is_diy_product ? '&aurika_pack=1' : '&aurika_diy_size=small' ) ); ?>"
               class="btn-buy-now">BUY IT NOW</a>

          </form>
        </div>

        <?php else : ?>
        <div class="product-out-of-stock">
          <span>&#128683; Out of Stock &mdash; Check back soon!</span>
        </div>
        <?php endif; ?>

        <!-- Meta (SKU, Categories) -->
        <div class="product-meta-row">
          <?php if ( $sku ) : ?>
            <span class="product-meta-item"><strong>SKU:</strong> <?php echo esc_html($sku); ?></span>
          <?php endif; ?>
          <?php if ( $categories ) : ?>
            <span class="product-meta-item"><strong>Category:</strong> <?php echo $categories; ?></span>
          <?php endif; ?>
        </div>

        <!-- ACCORDIONS (Category-Aware: Candle vs DIY Art Kit) -->
        <div class="product-accordions">

          <?php
          // ── Determine accordion content based on product type ──
          if ( $is_diy_product ) {
              $acc_title_1   = 'About The Kit';
              $acc_fallback_1 = '<p>A creative DIY painting kit designed for children ages 3–12. Includes handcrafted concrete toys, vibrant acrylic paints, and a detail brush — everything needed for hours of artistic fun.</p>';
              $acc_details   = wp_kses_post( get_theme_mod( 'aurika_diy_accordion_details', '<ul class="product-details-list"><li><strong>Includes:</strong> Concrete Toys + Acrylic Paints + Detail Brush</li><li><strong>Ages:</strong> 3–12 Years</li><li><strong>Material:</strong> Non-Toxic Concrete & Acrylic Paint</li><li><strong>Finish:</strong> Hand-Painted by Your Little Artist</li><li><strong>Safety:</strong> Child-Safe, Non-Toxic Materials</li></ul>' ) );
              $acc_disclaimer = wp_kses_post( get_theme_mod( 'aurika_diy_accordion_disclaimer', '<p>Every DIY Art Kit is individually handcrafted. Minor variations in shape, texture, and size are natural characteristics of handmade concrete toys. Acrylic paints included are non-toxic and child-safe. Adult supervision recommended for children under 5.</p>' ) );
              $acc_howto     = wp_kses_post( get_theme_mod( 'aurika_diy_accordion_howto', '<ul class="product-details-list"><li>Lay newspaper or plastic sheet on work surface to protect it.</li><li>Paint one toy at a time using the included detail brush.</li><li>Allow 15–20 minutes drying time between coats.</li><li>For best results, apply 2 thin coats of paint.</li><li>Wash brush with water immediately after use.</li></ul>' ) );
              $acc_shipping  = wp_kses_post( get_theme_mod( 'aurika_diy_accordion_shipping', '<ul class="product-details-list"><li><strong>Free Delivery:</strong> On all orders above Rs. 5,000.</li><li><strong>Flat Shipping:</strong> Rs. 350 on orders under Rs. 5,000 across Pakistan.</li><li><strong>Payment:</strong> Cash on Delivery (COD) available nationwide.</li><li><strong>Exchange Policy:</strong> Exchange or return within 7 days of delivery. Product must be unused and in original packaging. Share unboxing video on WhatsApp for damage claims.</li></ul>' ) );
          } else {
              $acc_title_1   = 'About The Fragrance';
              $acc_fallback_1 = '<p>Handcrafted with premium long-lasting fragrance oils designed to infuse warmth, peace, and serenity throughout your living spaces.</p>';
              $acc_details   = wp_kses_post( get_theme_mod( 'aurika_accordion_details', '<ul class="product-details-list"><li><strong>Craftsmanship:</strong> Hand-Poured in Pakistan</li><li><strong>Burn Time:</strong> Approx. 28–40 Hours</li><li><strong>Wax:</strong> Premium Natural Wax Blend</li><li><strong>Wick:</strong> 100% Natural Cotton Wick (Lead-Free)</li><li><strong>Safety:</strong> Non-Toxic, Clean Burning</li></ul>' ) );
              $acc_disclaimer = wp_kses_post( get_theme_mod( 'aurika_accordion_disclaimer', '<p>Every candle is individually hand-poured. Minor variations in appearance, texture, and color are authentic signs of handmade craftsmanship. Keep burning candles away from drafts, flammable materials, children, and pets.</p>' ) );
              $acc_howto     = wp_kses_post( get_theme_mod( 'aurika_accordion_howto', '<ul class="product-details-list"><li>Trim wick to 1/4 inch (5mm) before lighting every time.</li><li>Allow the entire top layer of wax to melt to the edges on first burn (2–3 hours).</li><li>Do not burn for longer than 4 hours in a single session.</li><li>Place on a heat-resistant, flat surface.</li></ul>' ) );
              $acc_shipping  = wp_kses_post( get_theme_mod( 'aurika_accordion_shipping', '<ul class="product-details-list"><li><strong>Free Delivery:</strong> On all orders above Rs. 5,000.</li><li><strong>Flat Shipping:</strong> Rs. 350 on orders under Rs. 5,000 across Pakistan.</li><li><strong>Payment:</strong> Cash on Delivery (COD) available nationwide.</li><li><strong>Exchange Policy:</strong> Exchange or return within 7 days of delivery. Product must be unused and in original packaging. Share unboxing video on WhatsApp for damage claims.</li></ul>' ) );
          }
          ?>

          <div class="accordion-item is-open">
            <button class="accordion-trigger" aria-expanded="true">
              <span><?php echo esc_html( $acc_title_1 ); ?></span>
              <span class="acc-icon">
                <span class="acc-plus">+</span>
                <span class="acc-minus">&minus;</span>
              </span>
            </button>
            <div class="accordion-content" style="display:block;">
              <div class="accordion-body">
                <?php
                $desc_full = $product->get_description();
                echo $desc_full ? wp_kses_post( aurika_clean_product_text( $desc_full ) ) : $acc_fallback_1;
                ?>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <button class="accordion-trigger" aria-expanded="false">
              <span>Product Details</span>
              <span class="acc-icon">
                <span class="acc-plus">+</span>
                <span class="acc-minus">&minus;</span>
              </span>
            </button>
            <div class="accordion-content">
              <div class="accordion-body">
                <?php echo $acc_details; ?>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <button class="accordion-trigger" aria-expanded="false">
              <span>Disclaimer</span>
              <span class="acc-icon">
                <span class="acc-plus">+</span>
                <span class="acc-minus">&minus;</span>
              </span>
            </button>
            <div class="accordion-content">
              <div class="accordion-body">
                <?php echo $acc_disclaimer; ?>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <button class="accordion-trigger" aria-expanded="false">
              <span>How to Use</span>
              <span class="acc-icon">
                <span class="acc-plus">+</span>
                <span class="acc-minus">&minus;</span>
              </span>
            </button>
            <div class="accordion-content">
              <div class="accordion-body">
                <?php echo $acc_howto; ?>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <button class="accordion-trigger" aria-expanded="false">
              <span>Shipping &amp; Exchange</span>
              <span class="acc-icon">
                <span class="acc-plus">+</span>
                <span class="acc-minus">&minus;</span>
              </span>
            </button>
            <div class="accordion-content">
              <div class="accordion-body">
                <?php echo $acc_shipping; ?>
              </div>
            </div>
          </div>

        </div><!-- /.product-accordions -->

      </div><!-- /.product-info-col -->
    </div><!-- /.product-layout -->

    <!-- REVIEWS -->
    <?php if ( comments_open() || get_comments_number() ) : ?>
    <div id="reviews" class="product-reviews-section">
      <?php comments_template(); ?>
    </div>
    <?php endif; ?>

    <!-- RELATED PRODUCTS -->
    <?php
    $related = wc_get_related_products( $product->get_id(), 5 );
    if ( $related ) :
    ?>
    <div class="product-related-section">
      <h2 class="section-title-caps"><?php echo esc_html( get_theme_mod( 'aurika_related_title', 'You May Also Like' ) ); ?></h2>
      <p class="section-subtitle-italic"><?php echo esc_html( get_theme_mod( 'aurika_related_subtitle', 'Handpicked pairings for your space' ) ); ?></p>
      <div class="related-products-grid">
        <?php
        $args = array(
          'post_type'      => 'product',
          'post__in'       => $related,
          'posts_per_page' => 5,
          'orderby'        => 'rand',
        );
        $loop = new WP_Query($args);
        if ( $loop->have_posts() ) :
          echo '<ul class="products">';
          while ( $loop->have_posts() ) : $loop->the_post();
            wc_get_template_part('content','product');
          endwhile;
          echo '</ul>';
          wp_reset_postdata();
        endif;
        ?>
      </div>
    </div>
    <?php endif; ?>

  </div><!-- /.container -->
</div><!-- /.product-page-wrap -->

<!-- STICKY BOTTOM BAR (Hunza Candle Style) -->
<div class="sticky-cart-bar" id="stickyCartBar">
  <div class="sticky-cart-inner">
    <div class="sticky-cart-product">
      <?php echo $product->get_image( 'thumbnail' ); ?>
      <div class="sticky-cart-info">
        <span class="sticky-cart-name"><?php the_title(); ?></span>
        <span class="sticky-cart-price"><?php echo $price_html; ?></span>
      </div>
    </div>
    <div class="sticky-cart-actions">
      <button type="button" class="sticky-add-to-cart" id="stickyAddToCartBtn">
        ADD TO CART
      </button>
    </div>
  </div>
</div>

<!-- JAVASCRIPT FOR GALLERY & ACCORDIONS -->
<script>
(function() {
  // Gallery Slides
  var mainImgs = document.querySelectorAll('.gallery-slide');
  var thumbs   = document.querySelectorAll('.product-thumb-item');
  var prevBtn  = document.querySelector('.gallery-prev');
  var nextBtn  = document.querySelector('.gallery-next');
  var current  = 0;

  function showImage(idx) {
    if (!mainImgs.length) return;
    mainImgs.forEach(function(img, i) { img.classList.toggle('active', i === idx); });
    thumbs.forEach(function(t, i) { t.classList.toggle('active', i === idx); });
    current = idx;
  }

  thumbs.forEach(function(thumb, i) {
    thumb.addEventListener('click', function() { showImage(i); });
  });

  if (prevBtn) prevBtn.addEventListener('click', function() {
    showImage(current > 0 ? current - 1 : mainImgs.length - 1);
  });
  if (nextBtn) nextBtn.addEventListener('click', function() {
    showImage(current < mainImgs.length - 1 ? current + 1 : 0);
  });

  // Accordions
  document.querySelectorAll('.accordion-trigger').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var item    = this.closest('.accordion-item');
      var content = item.querySelector('.accordion-content');
      var isOpen  = item.classList.contains('is-open');

      if (isOpen) {
        item.classList.remove('is-open');
        this.setAttribute('aria-expanded', 'false');
        if (content) content.style.display = 'none';
      } else {
        item.classList.add('is-open');
        this.setAttribute('aria-expanded', 'true');
        if (content) content.style.display = 'block';
      }
    });
  });

  // Pack Selector Click Handling (1 Pack / 2 Pack / 3 Pack for Candles)
  var packCards = document.querySelectorAll('.pack-card');
  var packInput = document.getElementById('aurika_selected_pack');
  var qtyInput = document.querySelector('.product-qty-wrap input.qty');
  var priceDisplay = document.querySelector('.product-detail-price');
  var buyNowBtn = document.querySelector('.btn-buy-now');
  var baseCheckoutUrl = '<?php echo esc_url( get_permalink( get_option("woocommerce_checkout_page_id") ) ); ?>?add-to-cart=<?php echo $product->get_id(); ?>';

  packCards.forEach(function(card) {
    card.addEventListener('click', function() {
      packCards.forEach(function(c) { c.classList.remove('is-selected'); });
      this.classList.add('is-selected');
      
      var packQty = parseInt(this.getAttribute('data-pack-qty')) || 1;
      var price = this.getAttribute('data-pack-price');
      
      if (packInput) {
        packInput.value = packQty;
      }
      
      // Preserve user quantity selection (do not reset to 1)
      var currentQty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
      
      if (priceDisplay && price) {
        priceDisplay.innerHTML = 'Rs. ' + parseInt(price).toLocaleString();
      }
      
      if (buyNowBtn) {
        buyNowBtn.href = baseCheckoutUrl + '&quantity=' + currentQty + (packQty > 1 ? '&aurika_pack=' + packQty : '');
      }
    });
  });

  // DIY Art Kit Variant Click Handling (Small vs Big Kit)
  var diyCards = document.querySelectorAll('.diy-variant-card');
  var diyInput = document.getElementById('aurika_diy_size');
  diyCards.forEach(function(card) {
    card.addEventListener('click', function() {
      diyCards.forEach(function(c) { c.classList.remove('is-selected'); });
      this.classList.add('is-selected');
      
      var size = this.getAttribute('data-diy-size') || 'small';
      var price = this.getAttribute('data-diy-price') || '299';
      
      if (diyInput) {
        diyInput.value = size;
      }
      
      var currentQty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
      
      if (priceDisplay && price) {
        priceDisplay.innerHTML = 'Rs. ' + parseInt(price).toLocaleString();
      }
      
      if (buyNowBtn) {
        buyNowBtn.href = baseCheckoutUrl + '&quantity=' + currentQty + '&aurika_diy_size=' + size;
      }
    });
  });

  // Quantity Counter (+/- buttons with universal document delegation)
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.qty-btn');
    if (!btn) return;
    var wrap = btn.closest('.quantity, .product-qty-wrap');
    if (!wrap) return;
    var input = wrap.querySelector('input.qty');
    if (!input) return;
    var val = parseInt(input.value) || 1;
    var min = parseInt(input.getAttribute('min')) || 1;
    var max = parseInt(input.getAttribute('max')) || 9999;
    if (btn.classList.contains('qty-minus') && val > min) val = val - 1;
    if (btn.classList.contains('qty-plus') && val < max) val = val + 1;
    input.value = val;
    input.dispatchEvent(new Event('change', { bubbles: true }));
    
    var pack = packInput ? parseInt(packInput.value) : 1;
    var diy = diyInput ? diyInput.value : '';
    var extra = '';
    if (pack && pack > 1) extra += '&aurika_pack=' + pack;
    if (diy) extra += '&aurika_diy_size=' + diy;
    
    if (buyNowBtn) {
      buyNowBtn.href = baseCheckoutUrl + '&quantity=' + val + extra;
    }
  });

  // Sticky Cart Bar (Hunza Candle Style)
  var stickyBar = document.getElementById('stickyCartBar');
  var formWrap  = document.querySelector('.product-form-wrap');
  if (stickyBar && formWrap) {
    var checkSticky = function() {
      var rect = formWrap.getBoundingClientRect();
      stickyBar.classList.toggle('is-visible', rect.bottom < 0);
    };
    window.addEventListener('scroll', checkSticky, { passive: true });
    window.addEventListener('resize', checkSticky, { passive: true });
    checkSticky();
  }

  // Sticky Add to Cart Button Click
  var stickyBtn = document.getElementById('stickyAddToCartBtn');
  if (stickyBtn) {
    stickyBtn.addEventListener('click', function(e) {
      e.preventDefault();
      var mainBtn = document.querySelector('.single_add_to_cart_button') || 
                    document.querySelector('.btn-add-to-cart') || 
                    document.querySelector('.product-qty-cart-row button[type="submit"]') ||
                    document.querySelector('form.cart button[type="submit"]');
      if (mainBtn) {
        mainBtn.click();
      } else {
        var form = document.querySelector('form.cart') || document.querySelector('.variations_form');
        if (form) form.submit();
      }
    });
  }
})();
</script>

<?php endwhile; ?>
<?php get_footer(); ?>
