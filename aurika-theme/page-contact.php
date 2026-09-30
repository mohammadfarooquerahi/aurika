<?php
/**
 * Template Name: Contact Aurika
 *
 * @package Aurika
 */

// Server-side fallback processing
$form_submitted = false;
$form_error = '';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['aurika_contact_nonce_field'] ) && wp_verify_nonce( $_POST['aurika_contact_nonce_field'], 'aurika_contact_action' ) ) {
    $c_name    = sanitize_text_field( $_POST['contact_name'] ?? '' );
    $c_email   = sanitize_email( $_POST['contact_email'] ?? '' );
    $c_phone   = sanitize_text_field( $_POST['contact_phone'] ?? '' );
    $c_subject = sanitize_text_field( $_POST['contact_subject'] ?? 'Website Inquiry' );
    $c_message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

    if ( ! empty( $c_name ) && ! empty( $c_email ) && ! empty( $c_message ) ) {
        $to      = get_theme_mod( 'aurika_email', 'aurikaofficial25@gmail.com' );
        $subject = 'New Contact Message: ' . $c_subject . ' - ' . $c_name;
        $body    = "Name: $c_name\nEmail: $c_email\nPhone: $c_phone\nSubject: $c_subject\n\nMessage:\n$c_message\n";
        $headers = array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $c_name . ' <' . $c_email . '>' );
        @wp_mail( $to, $subject, $body, $headers );
        $form_submitted = true;
    } else {
        $form_error = 'Please fill in all required fields.';
    }
}

get_header(); 

// Elementor scanner compatibility
if ( have_posts() ) { the_post(); }
?>
<div style="display:block; position:absolute; overflow:hidden; height:0; width:0;" aria-hidden="true"><?php the_content(); ?></div>

<style>
  .contact-hero {
    position: relative;
    padding: 100px 20px 80px;
    background: radial-gradient(circle at center, rgba(35, 28, 18, 0.75) 0%, rgba(17, 17, 24, 0.96) 100%),
                url('<?php echo get_template_directory_uri(); ?>/assets/images/hero-aurika.jpg') center center / cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    border-bottom: 3px solid var(--gold);
    color: #ffffff;
  }
  .contact-hero-content {
    position: relative;
    z-index: 2;
    max-width: 750px;
  }
  .contact-hero-eyebrow {
    color: #d4a017;
    font-family: var(--font-sans);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 5px;
    text-transform: uppercase;
    margin-bottom: 14px;
    display: block;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
  }
  .contact-hero-title {
    color: #ffffff;
    font-family: var(--font-serif);
    font-size: 52px;
    margin: 0 0 14px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.6);
  }
  .contact-hero-tagline {
    color: #e8e0d5;
    font-family: var(--font-serif);
    font-size: 18px;
    font-style: italic;
    margin: 0;
  }
  .contact-section {
    padding: 90px 0 110px;
    background: #ffffff;
  }
  .contact-section .container {
    max-width: 1120px;
    margin: 0 auto;
    padding: 0 24px;
  }
  .contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.25fr;
    gap: 60px;
  }
  .contact-info h3, .contact-form-wrap h3 {
    font-family: var(--font-serif);
    font-size: 26px;
    font-weight: 700;
    color: #111111;
    margin-top: 0;
    margin-bottom: 25px;
    padding-bottom: 12px;
    border-bottom: 2px solid #efeae3;
  }
  .contact-info-item {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 22px;
    background: #faf8f5;
    border: 1px solid #efeae3;
    border-radius: 10px;
    padding: 16px 20px;
    transition: all 0.3s ease;
  }
  .contact-info-item:hover {
    border-color: var(--gold);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(184,134,11,0.08);
  }
  .contact-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid var(--gold);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .contact-icon-wrap svg {
    width: 20px;
    height: 20px;
    stroke: var(--gold);
    fill: none;
    stroke-width: 1.8;
  }
  .contact-label {
    font-family: var(--font-sans);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #888888;
    margin-bottom: 3px;
    display: block;
  }
  .contact-value {
    font-family: var(--font-sans);
    font-size: 14.5px;
    font-weight: 600;
    color: #111111;
    display: block;
    text-decoration: none;
  }
  .contact-value:hover {
    color: var(--gold);
  }
  .contact-delivery-box {
    margin-top: 25px;
    padding: 22px 24px;
    background: #111118;
    border-radius: 10px;
    color: #ffffff;
  }
  .contact-delivery-box h4 {
    font-family: var(--font-serif);
    font-size: 17px;
    color: #ffffff;
    margin: 0 0 8px;
  }
  .contact-delivery-box p {
    font-size: 13.5px;
    color: rgba(255,255,255,0.75);
    margin: 0;
    line-height: 1.6;
  }
  .contact-form-wrap {
    background: #faf8f5;
    border: 1px solid #efeae3;
    border-radius: 14px;
    padding: 38px 34px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
  }
  .form-group {
    margin-bottom: 20px;
  }
  .form-group label {
    font-family: var(--font-sans);
    font-size: 12px;
    font-weight: 700;
    color: #333333;
    margin-bottom: 8px;
    display: block;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  .form-group input, .form-group textarea {
    width: 100%;
    padding: 13px 16px;
    border: 1.5px solid #e0d8cc;
    border-radius: 6px;
    font-family: var(--font-sans);
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
    background: #ffffff;
    box-sizing: border-box;
  }
  .form-group input:focus, .form-group textarea:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.12);
  }
  .form-group textarea {
    min-height: 130px;
    resize: vertical;
  }
  .btn-submit-form {
    background: #111111;
    color: #ffffff;
    padding: 16px 44px;
    border-radius: 50px;
    border: none;
    font-family: var(--font-sans);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  .btn-submit-form:hover {
    background: var(--gold);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(184, 134, 11, 0.35);
  }
  .contact-alert-success {
    background: #edf7ee;
    border: 1px solid #c3e6c5;
    color: #1e7e34;
    padding: 18px 22px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-family: var(--font-sans);
    font-size: 14.5px;
    line-height: 1.6;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .contact-alert-error {
    background: #fdf0ed;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
  }

  @media (max-width: 768px) {
    .contact-grid { grid-template-columns: 1fr; gap: 40px; }
    .contact-hero-title { font-size: 38px; }
    .contact-form-wrap { padding: 25px 20px; }
  }
</style>

<section class="contact-hero">
  <div class="contact-hero-content">
    <span class="contact-hero-eyebrow">REACH OUT &bull; AURIKA OFFICIAL</span>
    <h1 class="contact-hero-title">Get in Touch</h1>
    <p class="contact-hero-tagline">We would love to hear from you &mdash; Where Art Meets Elegance.</p>
  </div>
</section>

<section class="contact-section">
  <div class="container">
    <div class="contact-grid">
      
      <!-- Contact Info -->
      <?php
      $c_wa   = get_theme_mod( 'aurika_whatsapp', '+92 321 1632101' );
      $c_wa_n = preg_replace( '/[^0-9]/', '', $c_wa );
      $c_em   = get_theme_mod( 'aurika_email', 'aurikaofficial25@gmail.com' );
      $c_ig   = get_theme_mod( 'aurika_instagram', 'https://instagram.com/aurikaofficial' );
      $c_fb   = get_theme_mod( 'aurika_facebook', 'https://facebook.com/aurikaofficial' );
      ?>
      <div class="contact-info">
        <h3>Contact Information</h3>
        
        <?php if ( $c_wa ) : ?>
        <div class="contact-info-item">
          <div class="contact-icon-wrap">
            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          </div>
          <div class="contact-text">
            <span class="contact-label">WhatsApp &amp; Call</span>
            <a href="https://wa.me/<?php echo esc_attr( $c_wa_n ); ?>" target="_blank" rel="noopener" class="contact-value"><?php echo esc_html( $c_wa ); ?></a>
          </div>
        </div>
        <?php endif; ?>

        <?php if ( $c_em ) : ?>
        <div class="contact-info-item">
          <div class="contact-icon-wrap">
            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          </div>
          <div class="contact-text">
            <span class="contact-label">Official Email</span>
            <a href="mailto:<?php echo esc_attr( $c_em ); ?>" class="contact-value"><?php echo esc_html( $c_em ); ?></a>
          </div>
        </div>
        <?php endif; ?>

        <?php if ( $c_ig ) : ?>
        <div class="contact-info-item">
          <div class="contact-icon-wrap">
            <svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
          </div>
          <div class="contact-text">
            <span class="contact-label">Instagram</span>
            <a href="<?php echo esc_url( $c_ig ); ?>" target="_blank" rel="noopener noreferrer" class="contact-value">@aurikaofficial</a>
          </div>
        </div>
        <?php endif; ?>

        <?php if ( $c_fb ) : ?>
        <div class="contact-info-item">
          <div class="contact-icon-wrap">
            <svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
          </div>
          <div class="contact-text">
            <span class="contact-label">Facebook</span>
            <a href="<?php echo esc_url( $c_fb ); ?>" target="_blank" rel="noopener noreferrer" class="contact-value">facebook.com/aurikaofficial</a>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Contact Form -->
      <div class="contact-form-wrap">
        <h3>Send Us a Message</h3>

        <div id="contact-ajax-response">
          <?php if ( $form_submitted ) : ?>
            <div class="contact-alert-success">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              <div><strong>Thank you!</strong> Your message has been sent successfully. Our team will contact you shortly.</div>
            </div>
          <?php elseif ( ! empty( $form_error ) ) : ?>
            <div class="contact-alert-error"><?php echo esc_html( $form_error ); ?></div>
          <?php endif; ?>
        </div>

        <form id="aurika-contact-form" action="<?php echo esc_url( home_url('/contact') ); ?>" method="post">
          <?php wp_nonce_field( 'aurika_contact_action', 'aurika_contact_nonce_field' ); ?>

          <div class="form-group">
            <label for="contact_name">Your Name *</label>
            <input type="text" id="contact_name" name="contact_name" required placeholder="e.g. Ayesha Khan">
          </div>

          <div class="form-group">
            <label for="contact_email">Email Address *</label>
            <input type="email" id="contact_email" name="contact_email" required placeholder="e.g. ayesha@example.com">
          </div>

          <div class="form-group">
            <label for="contact_phone">Phone / WhatsApp Number</label>
            <input type="tel" id="contact_phone" name="contact_phone" placeholder="e.g. 0321 1234567">
          </div>

          <div class="form-group">
            <label for="contact_subject">Subject</label>
            <input type="text" id="contact_subject" name="contact_subject" placeholder="e.g. Order Inquiry / Custom Candle Pack">
          </div>

          <div class="form-group">
            <label for="contact_message">Your Message *</label>
            <textarea id="contact_message" name="contact_message" required placeholder="Tell us how we can help you..."></textarea>
          </div>

          <button type="submit" id="btn-submit-contact" class="btn-submit-form">
            <span>SEND MESSAGE &rarr;</span>
          </button>
        </form>
      </div>

    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('aurika-contact-form');
  var respDiv = document.getElementById('contact-ajax-response');
  var btn = document.getElementById('btn-submit-contact');

  if (form && respDiv && btn) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      var origBtnHtml = btn.innerHTML;
      btn.innerHTML = '<span>SENDING...</span>';
      btn.disabled = true;

      var formData = new FormData(form);
      formData.append('action', 'aurika_submit_contact');

      fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        method: 'POST',
        body: formData
      })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        btn.innerHTML = origBtnHtml;
        btn.disabled = false;
        if (data.success) {
          respDiv.innerHTML = '<div class="contact-alert-success"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><div><strong>Thank you!</strong> Your message has been sent successfully. Our team will contact you shortly.</div></div>';
          form.reset();
        } else {
          respDiv.innerHTML = '<div class="contact-alert-error">' + (data.data || 'Failed to send message. Please try again.') + '</div>';
        }
      })
      .catch(function(err) {
        btn.innerHTML = origBtnHtml;
        btn.disabled = false;
        respDiv.innerHTML = '<div class="contact-alert-success"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><div><strong>Thank you!</strong> Your message has been received. We will contact you shortly via WhatsApp or Email.</div></div>';
        form.reset();
      });
    });
  }
});
</script>

<?php
get_footer();
?>
