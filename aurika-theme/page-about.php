<?php
/**
 * Template Name: About Aurika
 *
 * @package Aurika
 */
get_header(); 

if ( have_posts() ) { the_post(); }
$about_content = trim( get_the_content() );
?>

<style>
  /* 1. ABOUT HERO */
  .about-hero {
    position: relative;
    padding: 85px 20px 75px;
    background: radial-gradient(circle at center, rgba(35, 28, 18, 0.72) 0%, rgba(17, 17, 24, 0.96) 100%),
                url('<?php echo get_template_directory_uri(); ?>/assets/images/hero-aurika.jpg') center center / cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    border-bottom: 2px solid #b8860b;
    color: #ffffff;
  }
  .about-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
  }
  .about-hero-eyebrow {
    color: #d4a017;
    font-family: var(--font-sans, 'Poppins', sans-serif);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 5px;
    text-transform: uppercase;
    margin-bottom: 12px;
    display: block;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
  }
  .about-hero-title {
    color: #ffffff;
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-weight: 700;
    font-size: 44px;
    margin: 0 0 12px;
    letter-spacing: -0.5px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.6);
  }
  .about-hero-tagline {
    color: #e8e0d5;
    font-family: var(--font-sans, 'Poppins', sans-serif);
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin: 0;
  }

  /* 2. ABOUT PAGE CONTENT WRAPPER */
  .about-page-main,
  .page-content {
    padding: 50px 0 70px;
    background: #ffffff;
  }
  .about-content-inner {
    max-width: 840px;
    margin: 0 auto;
    padding: 0 20px;
    font-family: var(--font-sans, 'Poppins', sans-serif);
    font-size: 14.5px;
    line-height: 1.85;
    color: #444444;
    text-align: center;
    box-sizing: border-box;
  }

  /* 3. CRITICAL: PREVENT GIANT SVG EXPLOSION */
  .about-content-inner svg,
  .about-page-main svg,
  .page-content svg,
  .container svg {
    max-width: 28px !important;
    max-height: 28px !important;
    width: 26px !important;
    height: 26px !important;
    display: inline-block !important;
    vertical-align: middle !important;
    fill: #b8860b !important;
    color: #b8860b !important;
    stroke: none !important;
  }
  .about-content-inner svg path,
  .about-page-main svg path,
  .page-content svg path,
  .container svg path {
    fill: #b8860b !important;
  }

  /* 4. HEADINGS & TYPOGRAPHY */
  .about-content-inner h1,
  .page-content h1 {
    font-family: var(--font-serif, 'Playfair Display', serif) !important;
    font-size: 26px !important;
    font-weight: 700 !important;
    color: #111111 !important;
    text-align: center !important;
    margin: 10px auto 16px !important;
    line-height: 1.3 !important;
  }
  .about-content-inner h2,
  .page-content h2 {
    font-family: var(--font-serif, 'Playfair Display', serif) !important;
    font-size: 22px !important;
    font-weight: 700 !important;
    color: #111111 !important;
    text-align: center !important;
    margin: 36px auto 14px !important;
    position: relative !important;
    padding-bottom: 8px !important;
  }
  .about-content-inner h2::after,
  .page-content h2::after {
    content: '' !important;
    display: block !important;
    width: 36px !important;
    height: 2px !important;
    background: #b8860b !important;
    margin: 6px auto 0 !important;
    border-radius: 2px !important;
  }
  .about-content-inner p,
  .page-content p {
    font-size: 14px !important;
    line-height: 1.8 !important;
    color: #4a4a4a !important;
    margin: 0 auto 12px !important;
    max-width: 680px !important;
    text-align: center !important;
  }
  .about-content-inner p[dir='ltr'],
  .page-content p[dir='ltr'] {
    margin-bottom: 6px !important;
  }

  /* 5. FOUNDER PROFILE CARD (CEO Image Removed) */
  .about-founder-card {
    margin: 28px auto 20px !important;
    text-align: center !important;
  }
  .about-founder-card figure,
  .about-founder-card img,
  .about-content-inner figure,
  .page-content figure {
    display: none !important;
  }
  .about-content-inner h6,
  .about-founder-card h6,
  .page-content h6 {
    font-family: var(--font-sans, 'Poppins', sans-serif) !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #111111 !important;
    text-align: center !important;
    margin: 12px 0 2px !important;
  }
  .about-content-inner h6 + p,
  .about-founder-card p,
  .page-content h6 + p {
    font-size: 11.5px !important;
    font-weight: 600 !important;
    letter-spacing: 1.5px !important;
    text-transform: uppercase !important;
    color: #b8860b !important;
    margin: 0 auto 24px !important;
    text-align: center !important;
  }

  /* 6. WHAT WE BELIEVE IN (VALUES GRID & CARDS) */
  .about-values-grid {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 20px !important;
    margin: 26px 0 36px !important;
    text-align: center !important;
  }
  .about-value-card {
    background: #faf8f5 !important;
    border: 1px solid #efeae3 !important;
    border-radius: 10px !important;
    padding: 20px 14px !important;
    text-align: center !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: flex-start !important;
    transition: all 0.3s ease !important;
    box-sizing: border-box !important;
  }
  .about-value-card:hover {
    transform: translateY(-3px) !important;
    border-color: #b8860b !important;
    box-shadow: 0 8px 24px rgba(184, 134, 11, 0.1) !important;
  }
  .about-value-icon-box {
    width: 48px !important;
    height: 48px !important;
    border-radius: 50% !important;
    background: #ffffff !important;
    border: 1.5px solid #d4af37 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 auto 10px !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03) !important;
    flex-shrink: 0 !important;
  }
  .about-value-icon-box svg,
  .about-value-icon-box svg * {
    width: 22px !important;
    height: 22px !important;
    fill: none !important;
    stroke: #b8860b !important;
    stroke-width: 1.8px !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
  }
  .about-value-title {
    font-family: var(--font-sans, 'Poppins', sans-serif) !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    color: #111111 !important;
    margin: 0 0 6px !important;
    line-height: 1.3 !important;
  }
  .about-value-desc {
    font-size: 11.5px !important;
    line-height: 1.55 !important;
    color: #555555 !important;
    margin: 0 !important;
  }

  /* Fallback pure-CSS if JS does not group */
  .about-content-inner p:has(> svg),
  .page-content p:has(> svg) {
    width: 46px !important;
    height: 46px !important;
    border-radius: 50% !important;
    background: #faf8f5 !important;
    border: 1.5px solid #d4af37 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 18px auto 6px !important;
  }
  .about-content-inner p:has(> svg) + h4,
  .page-content p:has(> svg) + h4 {
    text-align: center !important;
    font-size: 14.5px !important;
    font-weight: 700 !important;
    color: #111111 !important;
    margin: 0 0 4px !important;
  }
  .about-content-inner p:has(> svg) + h4 + p,
  .page-content p:has(> svg) + h4 + p {
    text-align: center !important;
    font-size: 12px !important;
    color: #555555 !important;
    margin: 0 auto 18px !important;
    max-width: 480px !important;
  }

  /* 7. MISSION CHECKLISTS (GREEN CHECKMARKS, 2-COL BADGES) */
  .about-content-inner ul,
  .page-content ul {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 10px 8px !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 14px auto 22px !important;
    max-width: 600px !important;
  }
  .about-content-inner ul li,
  .page-content ul li {
    background: #faf8f5 !important;
    border: 1px solid #efeae3 !important;
    border-radius: 8px !important;
    padding: 10px 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 8px !important;
    font-size: 11.5px !important;
    font-weight: 600 !important;
    color: #111111 !important;
    text-align: left !important;
    line-height: 1.25 !important;
  }
  .about-content-inner ul li br,
  .page-content ul li br {
    display: none !important;
  }
  .about-content-inner ul li svg,
  .page-content ul li svg {
    width: 16px !important;
    height: 16px !important;
    min-width: 16px !important;
    fill: #6baa64 !important;
  }
  .about-content-inner ul li svg path,
  .page-content ul li svg path {
    fill: #6baa64 !important;
  }

  /* 8. STUDIO / WORKSHOP IMAGE */
  .about-content-inner img,
  .page-content img {
    max-width: 100% !important;
    height: auto !important;
    border-radius: 10px !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
    margin: 16px auto !important;
    display: block !important;
  }

  /* 9. BOTTOM CTA BOX */
  .about-bottom-cta {
    background: #111118 !important;
    border-radius: 12px !important;
    padding: 30px 20px !important;
    margin: 34px auto 10px !important;
    text-align: center !important;
    color: #ffffff !important;
    max-width: 680px !important;
  }
  .about-bottom-cta h3 {
    font-family: var(--font-serif, 'Playfair Display', serif) !important;
    font-size: 21px !important;
    font-weight: 700 !important;
    color: #ffffff !important;
    margin: 0 0 8px !important;
  }
  .about-bottom-cta p {
    font-size: 12.5px !important;
    color: rgba(255,255,255,0.7) !important;
    margin: 0 0 16px !important;
  }
  .btn-about-cta {
    display: inline-block !important;
    background: #d4af37 !important;
    color: #111111 !important;
    font-family: var(--font-sans, 'Poppins', sans-serif) !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    letter-spacing: 2px !important;
    text-transform: uppercase !important;
    padding: 12px 30px !important;
    border-radius: 50px !important;
    text-decoration: none !important;
    box-shadow: 0 4px 14px rgba(212, 175, 55, 0.3) !important;
    transition: all 0.3s ease !important;
  }
  .btn-about-cta:hover {
    background: #ffffff !important;
    color: #111111 !important;
    transform: translateY(-2px) !important;
  }

  /* 10. MOBILE RESPONSIVENESS (<= 768px) */
  @media (max-width: 768px) {
    .about-hero {
      padding: 38px 14px 34px !important;
      min-height: 190px !important;
    }
    .about-hero-title {
      font-size: 26px !important;
      margin-bottom: 6px !important;
    }
    .about-hero-eyebrow {
      font-size: 9.5px !important;
      letter-spacing: 3px !important;
      margin-bottom: 8px !important;
    }
    .about-hero-tagline {
      font-size: 12px !important;
      letter-spacing: 1.5px !important;
    }
    .about-page-main,
    .page-content {
      padding: 22px 0 36px !important;
    }
    .about-content-inner,
    .page-content .container {
      padding: 0 12px !important;
    }
    .about-content-inner h1,
    .page-content h1 {
      font-size: 20px !important;
      margin: 8px auto 12px !important;
    }
    .about-content-inner h2,
    .page-content h2 {
      font-size: 18px !important;
      margin: 26px auto 12px !important;
    }
    .about-content-inner p,
    .page-content p {
      font-size: 12.5px !important;
      line-height: 1.65 !important;
      margin-bottom: 10px !important;
    }
    .about-values-grid {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 10px 8px !important;
      margin: 18px 0 24px !important;
    }
    .about-value-card {
      padding: 14px 10px !important;
    }
    .about-value-icon-box {
      width: 42px !important;
      height: 42px !important;
      margin-bottom: 8px !important;
    }
    .about-value-icon-box svg {
      width: 20px !important;
      height: 20px !important;
    }
    .about-value-title {
      font-size: 12px !important;
      margin-bottom: 4px !important;
    }
    .about-value-desc {
      font-size: 10.5px !important;
      line-height: 1.45 !important;
    }
    .about-founder-card figure,
    .about-founder-card img,
    .about-content-inner figure,
    .page-content figure {
      display: none !important;
    }
    .about-content-inner h6,
    .about-founder-card h6,
    .page-content h6 {
      font-size: 16px !important;
    }
    .about-content-inner ul,
    .page-content ul {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 8px 6px !important;
    }
    .about-content-inner ul li,
    .page-content ul li {
      padding: 8px 6px !important;
      font-size: 10.5px !important;
      gap: 6px !important;
    }
    .about-bottom-cta {
      padding: 24px 16px !important;
      margin-top: 24px !important;
    }
    .about-bottom-cta h3 {
      font-size: 18px !important;
    }
    .about-bottom-cta p {
      font-size: 11.5px !important;
    }
    .btn-about-cta {
      padding: 10px 24px !important;
      font-size: 10.5px !important;
    }
  }

  /* 11. ULTRA-COMPACT MOBILE BREAKPOINT (<= 480px) */
  @media (max-width: 480px) {
    .about-values-grid {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 8px 6px !important;
    }
    .about-value-card {
      padding: 12px 8px !important;
    }
    .about-value-title {
      font-size: 11.5px !important;
    }
    .about-value-desc {
      font-size: 10px !important;
    }
  }
</style>

<section class="about-hero">
  <div class="about-hero-content">
    <span class="about-hero-eyebrow"><?php echo esc_html( get_theme_mod( 'aurika_about_eyebrow', 'OUR STORY • AURIKA OFFICIAL' ) ); ?></span>
    <h1 class="about-hero-title"><?php the_title(); ?></h1>
    <p class="about-hero-tagline"><?php echo esc_html( get_theme_mod( 'aurika_page_hero_tagline', 'Where Art Meets Elegance.' ) ); ?></p>
  </div>
</section>

<?php if ( ! empty( $about_content ) ) : ?>
<main class="about-page-main">
  <div class="about-content-inner">
    <?php the_content(); ?>

    <!-- Bottom Call to Action -->
    <div class="about-bottom-cta">
      <h3><?php echo esc_html( get_theme_mod( 'aurika_about_cta_title', 'Experience Aurika' ) ); ?></h3>
      <p><?php echo esc_html( get_theme_mod( 'aurika_about_cta_desc', 'Discover our hand-poured candles and creative DIY art kits.' ) ); ?></p>
      <a href="<?php echo esc_url( aurika_shop_url() ); ?>" class="btn-about-cta"><?php echo esc_html( get_theme_mod( 'aurika_about_cta_btn', 'EXPLORE OUR COLLECTION →' ) ); ?></a>
    </div>
  </div>
</main>
<?php else : ?>
<main class="about-page-main">
  <div class="about-content-inner">
    <h1>Bringing Calm &amp; Creativity to Every Home</h1>
    <p>At Aurika, we believe that your home deserves more &mdash; more warmth, more calm, more meaningful moments. We hand-pour every candle with premium fragrance oils and natural wax, creating scents that transform your living spaces into sanctuaries of peace.</p>
    <p>For the little ones, we craft DIY Art Kits that replace screen time with creative play &mdash; paint-your-own shapes, animals, and more. Because every child deserves to create with their hands, and every parent deserves a moment of calm.</p>

    <h2>What We Believe In</h2>
    <div class="about-values-grid">
      <div class="about-value-card">
        <div class="about-value-icon-box">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#b8860b" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3 class="about-value-title">Handmade with Care</h3>
        <p class="about-value-desc">Every candle is hand-poured and every art kit hand-packed — nothing mass-produced, nothing rushed.</p>
      </div>

      <div class="about-value-card">
        <div class="about-value-icon-box">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#b8860b" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
        </div>
        <h3 class="about-value-title">Screen-Free Creativity</h3>
        <p class="about-value-desc">DIY Art Kits designed to turn screen time into creative time for children — hands busy, minds creating.</p>
      </div>

      <div class="about-value-card">
        <div class="about-value-icon-box">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#b8860b" stroke-width="1.8"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <h3 class="about-value-title">Calm for Your Home</h3>
        <p class="about-value-desc">Our candles bring warmth, fragrance, and a little moment of calm to everyday spaces.</p>
      </div>

      <div class="about-value-card">
        <div class="about-value-icon-box">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#b8860b" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <h3 class="about-value-title">Thoughtful Design</h3>
        <p class="about-value-desc">From ocean-inspired scents to floral shapes, every product is designed with intention, not just decoration.</p>
      </div>

      <div class="about-value-card">
        <div class="about-value-icon-box">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#b8860b" stroke-width="1.8"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <h3 class="about-value-title">Real Quality</h3>
        <p class="about-value-desc">Premium wax, long-lasting fragrance, and child-safe materials — quality you can trust in every piece.</p>
      </div>

      <div class="about-value-card">
        <div class="about-value-icon-box">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#b8860b" stroke-width="1.8"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <h3 class="about-value-title">Art Meets Elegance</h3>
        <p class="about-value-desc">Where craftsmanship meets everyday life — that’s the promise behind everything we make.</p>
      </div>
    </div>

    <!-- Bottom Call to Action -->
    <div class="about-bottom-cta">
      <h3>Experience Aurika</h3>
      <p>Discover our hand-poured candles and creative DIY art kits.</p>
      <a href="<?php echo esc_url( aurika_shop_url() ); ?>" class="btn-about-cta">EXPLORE OUR COLLECTION &rarr;</a>
    </div>
  </div>
</main>
<?php endif; ?>

<!-- DOM Enhancer: Groups raw WordPress belief paragraphs into responsive cards -->
<script>
(function() {
  function formatAboutContent() {
    var content = document.querySelector(".about-content-inner");
    if (!content) return;

    // 1. Group "What We Believe In" items into cards and grid
    var headings = content.querySelectorAll("h2");
    headings.forEach(function(h2) {
      if (h2.textContent.toLowerCase().indexOf("believe") !== -1) {
        var next = h2.nextElementSibling;
        var cards = [];
        var nodesToRemove = [];

        while (next && next.tagName !== "H2") {
          var current = next;
          next = next.nextElementSibling;

          var svg = current.querySelector ? current.querySelector("svg") : null;
          if (svg) {
            var titleEl = null;
            var descEl = null;

            if (next && next.tagName === "H4") {
              titleEl = next;
              nodesToRemove.push(titleEl);
              next = next.nextElementSibling;
              if (next && next.tagName === "P") {
                descEl = next;
                nodesToRemove.push(descEl);
                next = next.nextElementSibling;
              }
            }

            nodesToRemove.push(current);

            cards.push({
              svgHtml: svg.outerHTML,
              title: titleEl ? titleEl.innerHTML.replace(/<br\s*[\/]?>/gi, "").trim() : "",
              desc: descEl ? descEl.innerHTML.trim() : ""
            });
          }
        }

        if (cards.length > 0) {
          var grid = document.createElement("div");
          grid.className = "about-values-grid";
          cards.forEach(function(card) {
            var cardEl = document.createElement("div");
            cardEl.className = "about-value-card";
            cardEl.innerHTML = 
              '<div class="about-value-icon-box">' + card.svgHtml + '</div>' +
              '<h3 class="about-value-title">' + card.title + '</h3>' +
              '<p class="about-value-desc">' + card.desc + '</p>';
            grid.appendChild(cardEl);
          });

          nodesToRemove.forEach(function(node) {
            if (node && node.parentNode) node.parentNode.removeChild(node);
          });

          h2.parentNode.insertBefore(grid, h2.nextSibling);
        }
      }
    });

    // 2. Wrap founder title into a card (CEO image removed)
    var ceoEl = content.querySelector("h6");
    if (ceoEl && ceoEl.textContent.toLowerCase().indexOf("benaifa") !== -1) {
      var prev = ceoEl.previousElementSibling;
      while (prev && (prev.tagName === "FIGURE" || prev.tagName === "IMG" || prev.querySelector("img"))) {
        var toRemove = prev;
        prev = prev.previousElementSibling;
        if (toRemove && toRemove.parentNode) toRemove.parentNode.removeChild(toRemove);
      }
      var sub = ceoEl.nextElementSibling;
      var founderCard = document.createElement("div");
      founderCard.className = "about-founder-card";
      ceoEl.parentNode.insertBefore(founderCard, ceoEl);
      founderCard.appendChild(ceoEl);
      if (sub && sub.tagName === "P") {
        founderCard.appendChild(sub);
      }
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", formatAboutContent);
  } else {
    formatAboutContent();
  }
})();
</script>

<?php
get_footer();
?>
