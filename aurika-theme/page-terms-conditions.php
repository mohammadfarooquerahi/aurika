<?php
/**
 * Template Name: Terms & Conditions
 *
 * @package Aurika
 */

get_header();

// Setup post data if available
if ( have_posts() ) { the_post(); }
$page_content = trim( get_the_content() );
?>

<style>
  .legal-page-hero {
    position: relative;
    padding: 90px 20px 70px;
    background: #111111;
    text-align: center;
    color: #ffffff;
    border-bottom: 3px solid var(--gold);
  }
  .legal-hero-eyebrow {
    color: var(--gold);
    font-family: var(--font-sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 5px;
    text-transform: uppercase;
    margin-bottom: 12px;
    display: block;
  }
  .legal-hero-title {
    color: #ffffff;
    font-family: var(--font-serif);
    font-size: 46px;
    margin: 0 0 12px;
  }
  .legal-hero-tagline {
    color: rgba(255, 255, 255, 0.7);
    font-family: var(--font-sans);
    font-size: 14px;
    margin: 0;
  }
  .legal-content-wrap {
    padding: 80px 0 100px;
    background: #ffffff;
  }
  .legal-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 24px;
  }
  .legal-meta-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 24px;
    margin-bottom: 40px;
    border-bottom: 1px solid #efeae3;
    font-family: var(--font-sans);
    font-size: 13px;
    color: #777;
  }
  .legal-meta-badge {
    background: #faf8f5;
    border: 1px solid #e8e0d5;
    padding: 6px 14px;
    border-radius: 50px;
    font-weight: 600;
    color: #111;
  }
  .legal-intro-box {
    background: #faf8f5;
    border-left: 4px solid var(--gold);
    border-radius: 0 10px 10px 0;
    padding: 24px 28px;
    margin-bottom: 45px;
    font-size: 15px;
    line-height: 1.8;
    color: #333333;
  }
  .legal-section-block {
    margin-bottom: 42px;
    padding-bottom: 35px;
    border-bottom: 1px solid #f0ebe4;
  }
  .legal-section-block:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
  }
  .legal-clause-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 16px;
  }
  .legal-clause-num {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--gold);
    color: #ffffff;
    font-family: var(--font-sans);
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .legal-clause-title {
    font-family: var(--font-serif);
    font-size: 22px;
    font-weight: 700;
    color: #111111;
    margin: 0;
  }
  .legal-clause-body {
    font-family: var(--font-sans);
    font-size: 14.5px;
    line-height: 1.85;
    color: #4a4a4a;
    padding-left: 46px;
  }
  .legal-clause-body p {
    margin: 0 0 14px;
  }
  .legal-clause-body ul {
    margin: 12px 0 16px;
    padding-left: 20px;
  }
  .legal-clause-body li {
    margin-bottom: 8px;
    line-height: 1.7;
  }
  .legal-highlight-box {
    background: #fffdf9;
    border: 1px solid #e8dec8;
    border-radius: 8px;
    padding: 18px 22px;
    margin: 15px 0;
  }
  .legal-highlight-box strong {
    color: #b8860b;
  }
  .legal-contact-card {
    margin-top: 50px;
    background: #111118;
    border-radius: 12px;
    padding: 35px;
    color: #ffffff;
    text-align: center;
  }
  .legal-contact-card h3 {
    font-family: var(--font-serif);
    font-size: 24px;
    margin: 0 0 10px;
    color: #ffffff;
  }
  .legal-contact-card p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 14px;
    margin: 0 0 22px;
  }
  .legal-contact-btns {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
  }
  .btn-legal-whatsapp {
    background: #25d366;
    color: #ffffff;
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: transform 0.2s;
  }
  .btn-legal-email {
    background: var(--gold);
    color: #ffffff;
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: transform 0.2s;
  }
  .btn-legal-whatsapp:hover, .btn-legal-email:hover {
    transform: translateY(-2px);
    color: #ffffff;
  }

  @media (max-width: 768px) {
    .legal-hero-title { font-size: 34px; }
    .legal-clause-body { padding-left: 0; margin-top: 12px; }
    .legal-meta-bar { flex-direction: column; gap: 10px; text-align: center; }
  }
</style>

<section class="legal-page-hero">
  <div class="legal-hero-content">
    <span class="legal-hero-eyebrow">AURIKA STORE POLICIES</span>
    <h1 class="legal-hero-title">Terms &amp; Conditions</h1>
    <p class="legal-hero-tagline">Where Art Meets Elegance &mdash; Official Terms of Service</p>
  </div>
</section>

<main class="legal-content-wrap">
  <div class="legal-container">

    <div class="legal-meta-bar">
      <span><strong>Brand:</strong> Aurika (aurika.online)</span>
      <span class="legal-meta-badge">Last Updated: <?php echo date('F Y'); ?></span>
    </div>

    <?php if ( ! empty( $page_content ) ) : ?>
      <!-- Custom User-Edited Content from WordPress Admin Editor -->
      <div class="entry-content" style="font-family: var(--font-sans); font-size: 15px; line-height: 1.85; color: #444;">
        <?php the_content(); ?>
      </div>
    <?php else : ?>

    <div class="legal-intro-box">
      <strong>Welcome to Aurika &mdash; Where Art Meets Elegance.</strong><br>
      By accessing or using our website or placing an order, you agree to the following Terms &amp; Conditions. Please read them carefully before making a purchase.
    </div>

    <!-- 1. Products & Product Information -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">1</span>
        <h2 class="legal-clause-title">Products &amp; Product Information</h2>
      </div>
      <div class="legal-clause-body">
        <p>We make every effort to ensure that product descriptions, images, prices, sizes, colors, fragrances, and other information displayed on our website are accurate.</p>
        <p>All Aurika products are handmade with care. Therefore, minor variations in appearance, color, texture, or finish may occur from one product to another. These variations are a natural characteristic of handmade products and are not considered defects.</p>
        <p>Product images may also appear slightly different depending on your device or screen settings.</p>
      </div>
    </div>

    <!-- 2. Orders & Payments -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">2</span>
        <h2 class="legal-clause-title">Orders &amp; Payments</h2>
      </div>
      <div class="legal-clause-body">
        <ul>
          <li>Customers are responsible for providing accurate name, phone number, and delivery information.</li>
          <li>Orders are subject to product availability and confirmation.</li>
          <li>Available payment methods (Cash on Delivery &amp; Bank Transfer) and applicable charges will be displayed during checkout.</li>
          <li>Aurika reserves the right to cancel or decline an order where necessary, including in cases of incorrect pricing, unavailable stock, or unsuccessful order verification.</li>
        </ul>
      </div>
    </div>

    <!-- 3. Pricing -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">3</span>
        <h2 class="legal-clause-title">Pricing</h2>
      </div>
      <div class="legal-clause-body">
        <p>All prices are listed in <strong>Pakistani Rupees (PKR)</strong>.</p>
        <p>Aurika reserves the right to update product prices, offers, or availability at any time without prior notice.</p>
      </div>
    </div>

    <!-- 4. Shipping & Delivery -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">4</span>
        <h2 class="legal-clause-title">Shipping &amp; Delivery</h2>
      </div>
      <div class="legal-clause-body">
        <ul>
          <li>We currently offer nationwide delivery across Pakistan.</li>
          <li><strong>Free Shipping</strong> applies on orders above Rs. 5,000. A flat shipping fee of <strong>Rs. 350</strong> applies on orders under Rs. 5,000.</li>
          <li>Delivery charges and estimated delivery times will be communicated during checkout or order confirmation.</li>
          <li>Delivery times are estimates and may vary depending on destination, courier service, weather, holidays, or circumstances beyond Aurika's control.</li>
          <li>Customers are responsible for providing a complete and accurate delivery address and contact number.</li>
        </ul>
      </div>
    </div>

    <!-- 5. Handle With Care -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">5</span>
        <h2 class="legal-clause-title">Handle With Care</h2>
      </div>
      <div class="legal-clause-body">
        <p>Aurika products are handmade and may contain delicate details. Please handle, store, and use products carefully and according to the instructions provided.</p>
      </div>
    </div>

    <!-- 6. Candle Safety -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">6</span>
        <h2 class="legal-clause-title">Candle Safety</h2>
      </div>
      <div class="legal-clause-body">
        <div class="legal-highlight-box">
          <p><strong>For your safety and best experience:</strong></p>
          <ul>
            <li>Never leave a burning candle unattended.</li>
            <li>Keep candles away from children, pets, flammable materials, and drafts.</li>
            <li>Place candles on a stable, heat-resistant surface.</li>
            <li>Do not move a candle while it is burning or while the wax is hot.</li>
            <li>Follow the candle-care and safety instructions provided with the product.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- 7. DIY Art Kit Safety -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">7</span>
        <h2 class="legal-clause-title">DIY Art Kit Safety</h2>
      </div>
      <div class="legal-clause-body">
        <p>DIY Art Kits should be used with appropriate adult supervision where necessary.</p>
        <p>Keep small components and art materials away from young children when not in use. Follow any safety instructions provided with the kit.</p>
      </div>
    </div>

    <!-- 8. Returns & Resolution -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">8</span>
        <h2 class="legal-clause-title">Returns &amp; Resolution</h2>
      </div>
      <div class="legal-clause-body">
        <p>If a product arrives damaged, defective, or incorrect, please refer to our <a href="<?php echo esc_url(home_url('/returns-policy')); ?>" style="color:var(--gold); font-weight:600; text-decoration:underline;">Return &amp; Resolution Policy</a> for the applicable procedure and requirements.</p>
        <p><strong>Please Note:</strong> Change-of-mind returns or exchanges are not accepted.</p>
      </div>
    </div>

    <!-- 9. Intellectual Property -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">9</span>
        <h2 class="legal-clause-title">Intellectual Property</h2>
      </div>
      <div class="legal-clause-body">
        <p>All Aurika branding, logos, photographs, product images, written content, graphics, and other website materials are owned by or used with permission by Aurika.</p>
        <p>No content may be copied, reproduced, modified, or used commercially without prior written permission.</p>
      </div>
    </div>

    <!-- 10. Website Information -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">10</span>
        <h2 class="legal-clause-title">Website Information</h2>
      </div>
      <div class="legal-clause-body">
        <p>We make reasonable efforts to keep our website accurate and available. However, occasional errors, omissions, or temporary interruptions may occur.</p>
        <p>Aurika reserves the right to correct errors and update website content, products, prices, and availability when necessary.</p>
      </div>
    </div>

    <!-- 11. Changes to These Terms -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">11</span>
        <h2 class="legal-clause-title">Changes to These Terms</h2>
      </div>
      <div class="legal-clause-body">
        <p>Aurika may update these Terms &amp; Conditions from time to time. Any changes will be posted on this page.</p>
      </div>
    </div>

    <!-- 12. Contact Us -->
    <div class="legal-section-block">
      <div class="legal-clause-header">
        <span class="legal-clause-num">12</span>
        <h2 class="legal-clause-title">Contact Us</h2>
      </div>
      <div class="legal-clause-body">
        <p>If you have any questions regarding these Terms &amp; Conditions, please contact us:</p>
      </div>

      <div class="legal-contact-card">
        <h3>Have Questions About Our Terms?</h3>
        <p>Our team is always here to assist you with your orders and inquiries.</p>
        <div class="legal-contact-btns">
          <a href="https://wa.me/923211632101" target="_blank" rel="noopener" class="btn-legal-whatsapp">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
            WhatsApp Support: +92 321 1632101
          </a>
          <a href="mailto:aurikaofficial25@gmail.com" class="btn-legal-email">
            Email: aurikaofficial25@gmail.com
          </a>
        </div>
      </div>

    </div>

    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
