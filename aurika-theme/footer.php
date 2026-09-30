<?php if ( ! defined( 'AURIKA_FOOTER_RENDERED' ) ) { define( 'AURIKA_FOOTER_RENDERED', true ); } ?>
<footer class="site-footer" id="aurika-site-footer">

  <!-- =============================================
       1. GLOBAL TRUST STRIP (ACROSS ALL PAGES)
  ============================================= -->
  <div class="footer-trust-strip">
    <div class="footer-trust-container">
      <div class="footer-trust-item">
        <div class="footer-trust-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6baa64" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <div class="footer-trust-info">
          <h4><?php echo esc_html( get_theme_mod( 'aurika_trust1_title', 'Free Shipping > Rs. 5k' ) ); ?></h4>
          <p><?php echo esc_html( get_theme_mod( 'aurika_trust1_desc', 'On orders above Rs. 5,000' ) ); ?></p>
        </div>
      </div>

      <div class="footer-trust-item">
        <div class="footer-trust-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b8860b" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </div>
        <div class="footer-trust-info">
          <h4><?php echo esc_html( get_theme_mod( 'aurika_trust2_title', 'CASH ON DELIVERY' ) ); ?></h4>
          <p><?php echo esc_html( get_theme_mod( 'aurika_trust2_desc', 'Available across Pakistan' ) ); ?></p>
        </div>
      </div>

      <div class="footer-trust-item">
        <div class="footer-trust-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#cd5c5c" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <div class="footer-trust-info">
          <h4><?php echo esc_html( get_theme_mod( 'aurika_trust3_title', 'HAND CRAFTED' ) ); ?></h4>
          <p><?php echo esc_html( get_theme_mod( 'aurika_trust3_desc', 'Made with pure care & love' ) ); ?></p>
        </div>
      </div>

      <div class="footer-trust-item">
        <div class="footer-trust-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6495ed" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="footer-trust-info">
          <h4><?php echo esc_html( get_theme_mod( 'aurika_trust4_title', '100% SECURE' ) ); ?></h4>
          <p><?php echo esc_html( get_theme_mod( 'aurika_trust4_desc', 'Safe checkout & support' ) ); ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- =============================================
       2. FOOTER MAIN: 5 COLUMNS
  ============================================= -->
  <div class="footer-top">
    <div class="footer-container">

      <!-- COL 1: Get in Touch -->
      <?php
      $f_desc = get_theme_mod( 'aurika_footer_brand_desc', 'We create candles that bring warmth, calm, and a touch of elegance to every home — with pure craftsmanship and meaningful care.' );
      $f_wa   = get_theme_mod( 'aurika_whatsapp', '+92 321 1632101' );
      $f_wa_n = preg_replace( '/[^0-9]/', '', $f_wa );
      $f_em   = get_theme_mod( 'aurika_email', 'aurikaofficial25@gmail.com' );
      $f_ig   = get_theme_mod( 'aurika_instagram', 'https://instagram.com/aurikaofficial' );
      $f_fb   = get_theme_mod( 'aurika_facebook', 'https://facebook.com/aurikaofficial' );
      $f_tt   = get_theme_mod( 'aurika_tiktok', 'https://tiktok.com/@aurikaofficial' );
      ?>
      <div class="footer-col footer-col-brand">
        <h4 class="footer-col-title"><?php echo esc_html( get_theme_mod( 'aurika_footer_col1_heading', 'Get in Touch' ) ); ?></h4>
        <p class="footer-brand-desc"><?php echo esc_html( $f_desc ); ?></p>
        <?php if ( $f_wa ) : ?>
        <div class="footer-contact-item">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.64 12a19.79 19.79 0 01-3.07-8.67A2 2 0 013.55 1h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L7.91 8.5a16 16 0 005.9 5.9l.87-.87a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
          <span>WhatsApp: <a href="https://wa.me/<?php echo esc_attr( $f_wa_n ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $f_wa ); ?></a></span>
        </div>
        <?php endif; ?>
        <?php if ( $f_em ) : ?>
        <div class="footer-contact-item">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span><a href="mailto:<?php echo esc_attr( $f_em ); ?>"><?php echo esc_html( $f_em ); ?></a></span>
        </div>
        <?php endif; ?>
        <div class="footer-social-row">
          <?php if ( $f_fb ) : ?>
          <a href="<?php echo esc_url( $f_fb ); ?>" target="_blank" rel="noopener" aria-label="Facebook">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
          </a>
          <?php endif; ?>
          <?php if ( $f_ig ) : ?>
          <a href="<?php echo esc_url( $f_ig ); ?>" target="_blank" rel="noopener" aria-label="Instagram">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
          </a>
          <?php endif; ?>
          <?php if ( $f_tt ) : ?>
          <a href="<?php echo esc_url( $f_tt ); ?>" target="_blank" rel="noopener" aria-label="TikTok">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12a4 4 0 104 4V4a5 5 0 005 5"/></svg>
          </a>
          <?php endif; ?>
          <?php if ( $f_wa_n ) : ?>
          <a href="https://wa.me/<?php echo esc_attr( $f_wa_n ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
          </a>
          <?php endif; ?>
        </div>
      </div>

      <!-- COL 2: Quick Links -->
      <div class="footer-col footer-col-links footer-col-quick-links">
        <h4 class="footer-col-title"><?php echo esc_html( get_theme_mod( 'aurika_footer_col2_heading', 'Quick Links' ) ); ?></h4>
        <?php
        $use_custom_menu = get_theme_mod( 'aurika_use_custom_menu', false );
        if ( $use_custom_menu && has_nav_menu( 'footer_quick_links' ) ) {
          wp_nav_menu( array(
            'theme_location' => 'footer_quick_links',
            'menu_class'     => 'footer-links',
            'container'      => false,
            'fallback_cb'    => false,
          ) );
        } else {
        ?>
        <ul class="footer-links">
          <li><a href="<?php echo esc_url( home_url('/') ); ?>">Home</a></li>
          <li><a href="<?php echo esc_url( aurika_shop_url() ); ?>">Shop All</a></li>
          <li><a href="<?php echo esc_url( home_url('/product-category/scented-candles/') ); ?>">Scented Candles</a></li>
          <li><a href="<?php echo esc_url( home_url('/product-category/diy-art-kits/') ); ?>">DIY Art Kits</a></li>
          <li><a href="<?php echo esc_url( home_url('/about/') ); ?>">About Us</a></li>
          <li><a href="<?php echo esc_url( home_url('/contact/') ); ?>">Contact Us</a></li>
        </ul>
        <?php } ?>
      </div>

      <!-- COL 3: Information -->
      <div class="footer-col footer-col-links footer-col-info">
        <h4 class="footer-col-title"><?php echo esc_html( get_theme_mod( 'aurika_footer_col3_heading', 'Information' ) ); ?></h4>
        <?php
        if ( $use_custom_menu && has_nav_menu( 'footer_info' ) ) {
          wp_nav_menu( array(
            'theme_location' => 'footer_info',
            'menu_class'     => 'footer-links',
            'container'      => false,
            'fallback_cb'    => false,
          ) );
        } else {
        ?>
        <ul class="footer-links">
          <li><a href="<?php echo esc_url( home_url('/privacy-policy/') ); ?>">Privacy Policy</a></li>
          <li><a href="<?php echo esc_url( home_url('/terms-conditions/') ); ?>">Terms &amp; Conditions</a></li>
          <li><a href="<?php echo esc_url( home_url('/shipping-policy/') ); ?>">Shipping Policy</a></li>
          <li><a href="<?php echo esc_url( home_url('/returns-policy/') ); ?>">Returns Policy</a></li>
          <li><a href="<?php echo esc_url( home_url('/about/') ); ?>">About Aurika</a></li>
          <li><a href="<?php echo esc_url( home_url('/contact/') ); ?>">Contact Us</a></li>
        </ul>
        <?php } ?>
      </div>

      <!-- COL 4: Customer Service -->
      <div class="footer-col footer-col-links footer-col-customer">
        <h4 class="footer-col-title"><?php echo esc_html( get_theme_mod( 'aurika_footer_col4_heading', 'Customer Service' ) ); ?></h4>
        <?php
        if ( $use_custom_menu && has_nav_menu( 'footer_customer_service' ) ) {
          wp_nav_menu( array(
            'theme_location' => 'footer_customer_service',
            'menu_class'     => 'footer-links',
            'container'      => false,
            'fallback_cb'    => false,
          ) );
        } else {
        ?>
        <ul class="footer-links">
          <li><a href="<?php echo esc_url( home_url('/shipping-policy/') ); ?>">Shipping &amp; Delivery</a></li>
          <li><a href="<?php echo esc_url( home_url('/returns-policy/') ); ?>">Returns &amp; Exchange</a></li>
          <li><a href="<?php echo esc_url( home_url('/contact/') ); ?>">Help &amp; FAQs</a></li>
          <li><a href="<?php echo esc_url( home_url('/contact/') ); ?>">Track Your Order</a></li>
        </ul>
        <?php } ?>
        <div class="footer-shipping-note">
          <p><?php echo esc_html( get_theme_mod( 'aurika_footer_shipping_cod', '💳 Cash on Delivery Nationwide' ) ); ?></p>
          <p><?php echo esc_html( get_theme_mod( 'aurika_footer_shipping_free', '🚚 Free delivery on orders above Rs. 5,000' ) ); ?></p>
          <p><?php echo esc_html( get_theme_mod( 'aurika_footer_shipping_flat', '📦 Rs. 350 flat shipping under Rs. 5,000' ) ); ?></p>
        </div>
      </div>

      <!-- COL 5: Newsletter -->
      <div class="footer-col footer-col-newsletter">
        <h4 class="footer-col-title"><?php echo esc_html( get_theme_mod( 'aurika_footer_col5_heading', 'Newsletter' ) ); ?></h4>
        <p class="footer-newsletter-desc"><?php echo esc_html( get_theme_mod( 'aurika_newsletter_desc', 'Receive our latest updates, offers, and new product launches.' ) ); ?></p>
        <form class="footer-newsletter-form" onsubmit="return false;">
          <input type="email" placeholder="Your email address" class="footer-email-input" aria-label="Email address">
          <button type="submit" class="footer-subscribe-btn"><?php echo esc_html( get_theme_mod( 'aurika_subscribe_btn_text', 'Subscribe' ) ); ?></button>
        </form>
        <div class="footer-logo-area">
          <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-brand-name"><?php bloginfo('name'); ?></a>
          <span class="footer-brand-tag"><?php bloginfo('description') ?: 'Where Art Meets Elegance.'; ?></span>
        </div>
      </div>

    </div>
  </div>

  <!-- =============================================
       3. FOOTER BOTTOM (COPYRIGHT + BIC MUET + DEV)
  ============================================= -->
  <div class="footer-bottom">
    <!-- BIC MUET Incubation Badge -->
    <div class="footer-bic-badge">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/BIC-MUET-logo.png' ); ?>" alt="BIC MUET Logo" class="bic-muet-logo">
      <span><?php echo esc_html( get_theme_mod( 'aurika_footer_bic_text', 'Incubated at BIC MUET Jamshoro' ) ); ?></span>
    </div>

    <p><?php echo esc_html( get_theme_mod( 'aurika_footer_copyright', 'Copyright © ' . date('Y') . ' Aurika — Where Art Meets Elegance. All Rights Reserved.' ) ); ?></p>

    <!-- Developer Credit -->
    <span class="footer-dev-credit">Developed by Mohammad Farooque</span>
  </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
