<?php
/**
 * Template Name: Shipping Policy
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
  .shipping-perks-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 45px;
  }
  .shipping-perk-card {
    background: #faf8f5;
    border: 1px solid #efeae3;
    border-radius: 10px;
    padding: 24px 20px;
    text-align: center;
  }
  .shipping-perk-card h4 {
    font-family: var(--font-sans);
    font-size: 14px;
    font-weight: 700;
    color: #111;
    margin: 10px 0 6px;
  }
  .shipping-perk-card p {
    font-size: 13px;
    color: #666;
    margin: 0;
  }
  .legal-section-block {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #f0ebe4;
  }
  .legal-section-block h3 {
    font-family: var(--font-serif);
    font-size: 22px;
    font-weight: 700;
    color: #111;
    margin: 0 0 14px;
  }
  .legal-section-block p, .legal-section-block li {
    font-family: var(--font-sans);
    font-size: 14.5px;
    line-height: 1.85;
    color: #4a4a4a;
  }
  .legal-section-block ul {
    padding-left: 20px;
    margin: 12px 0;
  }
  @media (max-width: 768px) {
    .shipping-perks-grid { grid-template-columns: 1fr; }
    .legal-hero-title { font-size: 34px; }
  }
</style>

<section class="legal-page-hero">
  <div class="legal-hero-content">
    <span class="legal-hero-eyebrow">DELIVERY INFORMATION</span>
    <h1 class="legal-hero-title">Shipping &amp; Delivery Policy</h1>
    <p class="legal-hero-tagline">Fast, reliable, and secure delivery across Pakistan</p>
  </div>
</section>

<main class="legal-content-wrap">
  <div class="legal-container">

    <div class="legal-meta-bar">
      <span><strong>Coverage:</strong> All Cities Across Pakistan</span>
      <span class="legal-meta-badge">Updated: <?php echo date('F Y'); ?></span>
    </div>

    <?php if ( ! empty( $page_content ) ) : ?>
      <!-- Custom User-Edited Content from WordPress Admin Editor -->
      <div class="entry-content" style="font-family: var(--font-sans); font-size: 15px; line-height: 1.85; color: #444;">
        <?php the_content(); ?>
      </div>
    <?php else : ?>

    <?php
    $free_thresh = (int) get_theme_mod( 'aurika_shipping_free_threshold', 5000 );
    $flat_cost   = (int) get_theme_mod( 'aurika_shipping_flat_rate', 350 );
    ?>
    <div class="shipping-perks-grid">
      <div class="shipping-perk-card">
        <div style="font-size: 28px;">🚚</div>
        <h4>FREE SHIPPING</h4>
        <p>On all orders above Rs. <?php echo number_format($free_thresh); ?></p>
      </div>
      <div class="shipping-perk-card">
        <div style="font-size: 28px;">📦</div>
        <h4>FLAT RATE RS. <?php echo number_format($flat_cost); ?></h4>
        <p>On orders under Rs. <?php echo number_format($free_thresh); ?></p>
      </div>
      <div class="shipping-perk-card">
        <div style="font-size: 28px;">💳</div>
        <h4>CASH ON DELIVERY</h4>
        <p>Pay upon delivery safely</p>
      </div>
    </div>

    <div class="legal-section-block">
      <h3>1. Order Processing &amp; Dispatch</h3>
      <p>All Aurika scented candles and DIY Art Kits are handcrafted with care. Orders are typically processed and packed within <strong>24 to 48 hours</strong> following order confirmation via phone or WhatsApp.</p>
    </div>

    <div class="legal-section-block">
      <h3>2. Delivery Timelines</h3>
      <ul>
        <li><strong>Major Cities (Lahore, Karachi, Islamabad, Rawalpindi):</strong> 2 to 4 business days.</li>
        <li><strong>Other Cities &amp; Regions:</strong> 3 to 6 business days.</li>
      </ul>
      <p>Delivery times are estimates and may occasionally vary during high-volume sales events, public holidays, or unforeseen weather delays.</p>
    </div>

    <div class="legal-section-block">
      <h3>3. Delivery Charges</h3>
      <ul>
        <li><strong>Orders Above Rs. 5,000:</strong> FREE Nationwide Delivery.</li>
        <li><strong>Orders Below Rs. 5,000:</strong> Flat shipping charge of <strong>Rs. 350</strong>.</li>
      </ul>
    </div>

    <div class="legal-section-block">
      <h3>4. Order Tracking &amp; Inquiries</h3>
      <p>Once your order is dispatched, you will receive courier tracking details. For questions or urgent delivery assistance, please contact us on WhatsApp at <strong>+92 321 1632101</strong> or email <strong>aurikaofficial25@gmail.com</strong>.</p>
    </div>

    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
