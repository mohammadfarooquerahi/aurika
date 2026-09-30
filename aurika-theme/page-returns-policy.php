<?php
/**
 * Template Name: Returns Policy
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
  .returns-callout {
    background: #faf8f5;
    border-left: 4px solid var(--gold);
    border-radius: 0 10px 10px 0;
    padding: 24px 28px;
    margin-bottom: 40px;
    font-size: 15px;
    line-height: 1.8;
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
    .legal-hero-title { font-size: 34px; }
  }
</style>

<section class="legal-page-hero">
  <div class="legal-hero-content">
    <span class="legal-hero-eyebrow">AURIKA STORE POLICIES</span>
    <h1 class="legal-hero-title">Return &amp; Resolution Policy</h1>
    <p class="legal-hero-tagline">Fair and transparent resolution for your orders</p>
  </div>
</section>

<main class="legal-content-wrap">
  <div class="legal-container">

    <div class="legal-meta-bar">
      <span><strong>Brand:</strong> Aurika</span>
      <span class="legal-meta-badge">Updated: <?php echo date('F Y'); ?></span>
    </div>

    <?php if ( ! empty( $page_content ) ) : ?>
      <!-- Custom User-Edited Content from WordPress Admin Editor -->
      <div class="entry-content" style="font-family: var(--font-sans); font-size: 15px; line-height: 1.85; color: #444;">
        <?php the_content(); ?>
      </div>
    <?php else : ?>

    <div class="returns-callout">
      At Aurika, every product is hand-poured, packed, and inspected with the highest standards. We are committed to making sure your order reaches you safely.
    </div>

    <div class="legal-section-block">
      <h3>1. Damaged or Defective Items</h3>
      <p>If an item arrives damaged or broken during transit, please notify us within <strong>3 days</strong> of receiving your package.</p>
      <p>To request a replacement, please send:</p>
      <ul>
        <li>Your Order Number &amp; Customer Name.</li>
        <li>Clear photos or a brief unboxing video showing the damaged product and outer packaging.</li>
        <li>Send details via WhatsApp: <strong><?php echo esc_html( get_theme_mod( 'aurika_whatsapp', '+92 321 1632101' ) ); ?></strong> or Email: <strong><?php echo esc_html( get_theme_mod( 'aurika_email', 'aurikaofficial25@gmail.com' ) ); ?></strong>.</li>
      </ul>
      <p>Once verified, we will arrange a complimentary replacement for the damaged product.</p>
    </div>

    <div class="legal-section-block">
      <h3>2. Handmade Variations</h3>
      <p>All candles and art kits are individually handcrafted. Slight variations in color shade, texture, or small surface markings are natural hallmarks of artisanal craftsmanship and are not considered defects.</p>
    </div>

    <div class="legal-section-block">
      <h3>3. Change of Mind Policy</h3>
      <p>Due to the delicate nature of hand-poured candles and creative art kits, <strong>change-of-mind returns or exchanges are not accepted</strong> once an order has been confirmed and delivered.</p>
    </div>

    <div class="legal-section-block">
      <h3>4. Need Assistance?</h3>
      <p>For any inquiries regarding your delivery or product support, reach out directly to our team via WhatsApp at <strong><?php echo esc_html( get_theme_mod( 'aurika_whatsapp', '+92 321 1632101' ) ); ?></strong>.</p>
    </div>

    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
