<?php
/**
 * Template Name: Privacy Policy
 *
 * @package Aurika
 */

get_header();
?>

<style>
  .legal-page-hero {
    position: relative;
    padding: 80px 20px 65px;
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
    font-size: 42px;
    margin: 0 0 10px;
  }
  .legal-hero-tagline {
    color: rgba(255, 255, 255, 0.7);
    font-family: var(--font-sans);
    font-size: 14px;
    margin: 0;
  }
  .legal-content-wrap {
    padding: 70px 0 90px;
    background: #ffffff;
  }
  .legal-container {
    max-width: 920px;
    margin: 0 auto;
    padding: 0 24px;
  }
  .legal-meta-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    margin-bottom: 35px;
    border-bottom: 1px solid #efeae3;
    font-family: var(--font-sans);
    font-size: 13px;
    color: #777;
    flex-wrap: wrap;
    gap: 10px;
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
    padding: 22px 26px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 40px;
    font-family: var(--font-sans);
    font-size: 14.5px;
    line-height: 1.8;
    color: #333;
  }
  .legal-section-block {
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 1px solid #f0ebe4;
  }
  .legal-section-block:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
  }
  .legal-section-block h3 {
    font-family: var(--font-serif);
    font-size: 22px;
    font-weight: 700;
    color: #111;
    margin: 0 0 14px;
  }
  .legal-section-block h4 {
    font-family: var(--font-sans);
    font-size: 16px;
    font-weight: 700;
    color: #222;
    margin: 18px 0 8px;
  }
  .legal-section-block p, .legal-section-block li {
    font-family: var(--font-sans);
    font-size: 14.5px;
    line-height: 1.85;
    color: #4a4a4a;
  }
  .legal-section-block ul {
    padding-left: 22px;
    margin: 12px 0 16px;
  }
  .legal-section-block li {
    margin-bottom: 6px;
  }
  @media (max-width: 768px) {
    .legal-hero-title { font-size: 32px; }
  }
</style>

<section class="legal-page-hero">
  <div class="legal-hero-content">
    <span class="legal-hero-eyebrow">CUSTOMER TRUST &amp; DATA PRIVACY</span>
    <h1 class="legal-hero-title">Privacy Policy</h1>
    <p class="legal-hero-tagline">Welcome to Aurika &mdash; Where Art Meets Elegance.</p>
  </div>
</section>

<main class="legal-content-wrap">
  <div class="legal-container">

    <div class="legal-meta-bar">
      <span><strong>Brand:</strong> Aurika (aurika.online)</span>
      <span class="legal-meta-badge">Last Updated: <?php echo date('F j, Y'); ?></span>
    </div>

      <?php if ( get_the_content() ) : ?>
        <div class="policy-content">
          <?php the_content(); ?>
        </div>
      <?php else : ?>
      <!-- Official Client 10-Section Final Privacy Policy -->
      <div class="legal-intro-box">
        <p style="margin: 0 0 10px;"><strong>Last Updated: <?php echo date('F j, Y'); ?></strong></p>
        <p style="margin: 0;">At Aurika, we respect your privacy and are committed to protecting the personal information you share with us. This Privacy Policy explains what information we may collect when you visit or use our website, place an order, contact us, or otherwise interact with Aurika, and how we use and protect that information.</p>
      </div>

      <div class="legal-section-block">
        <h3>1. Information We Collect</h3>
        <p>We may collect information that you provide directly to us when you:</p>
        <ul>
          <li>Place an order through our website</li>
          <li>Create or use an account on our website, where available</li>
          <li>Contact us through our website forms or other available communication channels</li>
          <li>Contact us through WhatsApp or other customer-support channels</li>
          <li>Provide information in connection with an order, inquiry, or request</li>
        </ul>
        <p>Depending on how you interact with our website, this information may include:</p>
        <ul>
          <li>Full name</li>
          <li>Phone number</li>
          <li>Email address</li>
          <li>Delivery address</li>
          <li>Billing or order-related information</li>
          <li>Product and order details</li>
          <li>Information you share with us when contacting customer support</li>
        </ul>
        <p>We do not collect sensitive payment information directly on our website. Payments made via Cash on Delivery (COD) do not require bank or card details on our site. If any online payment options are introduced in the future, payment details will be processed securely by the relevant payment service provider.</p>
      </div>

      <div class="legal-section-block">
        <h3>2. How We Use Your Information</h3>
        <p>We use the information we collect for business and operational purposes, including:</p>
        <ul>
          <li>Processing, fulfilling, and delivering your orders</li>
          <li>Communicating with you about your orders, deliveries, inquiries, or support requests</li>
          <li>Managing customer accounts, where available</li>
          <li>Sending important updates regarding your order status</li>
          <li>Improving our website, products, and overall customer experience</li>
          <li>Preventing fraudulent transactions and ensuring website security</li>
          <li>Complying with applicable legal and business requirements</li>
        </ul>
      </div>

      <div class="legal-section-block">
        <h3>3. Abandoned Cart Communications</h3>
        <p>If you add items to your cart and provide your email address during checkout, but do not complete your order, we may send you automated email reminders to help you complete your purchase.</p>
        <ul>
          <li>These abandoned-cart reminders are sent only via email.</li>
          <li>We do not send automated cart-recovery reminders through WhatsApp or SMS.</li>
          <li>You can choose to stop receiving these reminders at any time using the unsubscribe or opt-out link provided in the email.</li>
        </ul>
      </div>

      <div class="legal-section-block">
        <h3>4. Cookies and Similar Technologies</h3>
        <p>Our website may use cookies and similar technologies to improve your browsing experience. Cookies help us:</p>
        <ul>
          <li>Remember items in your shopping cart</li>
          <li>Keep essential website features working properly</li>
          <li>Understand how visitors interact with our website so we can improve its performance</li>
        </ul>
        <p>You can choose to disable or manage cookies through your browser settings. However, doing so may affect certain website functions, such as your shopping cart or checkout process.</p>
      </div>

      <div class="legal-section-block">
        <h3>5. Sharing of Information</h3>
        <p>We do not sell, rent, or trade your personal information to third parties.</p>
        <p>We may share your information only in the following limited situations:</p>
        <ul>
          <li><strong>Delivery and Logistics Partners:</strong> To deliver your orders, we share necessary details (such as your name, delivery address, and phone number) with our shipping and courier partners.</li>
          <li><strong>Service Providers:</strong> We may work with third-party service providers who assist us in operating our website, managing communications, or supporting business operations. These providers have access only to the information necessary to perform their services.</li>
          <li><strong>Legal Requirements:</strong> We may disclose information if required to do so by law, regulation, legal process, or to protect the rights, property, or safety of Aurika, our customers, or others.</li>
        </ul>
      </div>

      <div class="legal-section-block">
        <h3>6. Website and Third-Party Services</h3>
        <ul>
          <li><strong>Product Visibility via Google:</strong> We use Google for WooCommerce solely to showcase Aurika’s products on Google and Google Shopping. This connection is used only for displaying products and does not include Google Analytics or other active tracking tools.</li>
          <li><strong>Customer Support via WhatsApp:</strong> When you choose to contact us via WhatsApp, communications take place through WhatsApp’s platform and are subject to WhatsApp’s terms and privacy policies.</li>
        </ul>
      </div>

      <div class="legal-section-block">
        <h3>7. Data Security</h3>
        <p>We take reasonable technical and organizational precautions to protect your personal information against unauthorized access, loss, misuse, or alteration. While we strive to protect your data, no online transmission or storage system can be guaranteed to be 100% secure.</p>
      </div>

      <div class="legal-section-block">
        <h3>8. Data Retention</h3>
        <p>We retain your personal information only for as long as necessary to fulfill the purposes outlined in this Privacy Policy, complete orders, handle inquiries, resolve disputes, and comply with legal or business obligations.</p>
      </div>

      <div class="legal-section-block">
        <h3>9. Changes to This Privacy Policy</h3>
        <p>We may update this Privacy Policy from time to time to reflect changes in our practices, services, or legal obligations. Any updates will be posted on this page with a revised "Last Updated" date. We encourage you to review this policy periodically.</p>
      </div>

      <div class="legal-section-block">
        <h3>10. Contact Us</h3>
        <p>If you have questions, feedback, or concerns regarding this Privacy Policy or how your information is handled, please contact us:</p>
        <ul>
          <li><strong>Email:</strong> <a href="mailto:<?php echo esc_attr( get_theme_mod( 'aurika_email', 'aurikaofficial25@gmail.com' ) ); ?>"><?php echo esc_html( get_theme_mod( 'aurika_email', 'aurikaofficial25@gmail.com' ) ); ?></a></li>
          <li><strong>WhatsApp:</strong> <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', get_theme_mod( 'aurika_whatsapp', '+923211632101' ) ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( get_theme_mod( 'aurika_whatsapp', '+92 321 1632101' ) ); ?></a></li>
          <li><strong>Website:</strong> <a href="<?php echo esc_url( home_url('/') ); ?>">aurika.online</a></li>
        </ul>
        <p style="margin-top: 20px; font-weight: 700; color: #111;">
          Aurika<br>
          <span style="font-family: var(--font-serif); font-weight: normal; font-style: italic; color: var(--gold);">Where Art Meets Elegance.</span>
        </p>
      </div>
      <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
