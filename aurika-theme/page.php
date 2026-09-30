<?php
/**
 * Default Page Template
 *
 * @package Aurika
 */
get_header();

global $post;
$is_shop_page = ( function_exists('is_shop') && is_shop() ) || 
                ( isset($post) && in_array( $post->post_name, array('shop', 'shop-2', 'our-shop', 'products') ) );

$is_wide = ( function_exists( 'is_account_page' ) && is_account_page() ) ||
           ( function_exists( 'is_checkout' ) && is_checkout() ) ||
           ( function_exists( 'is_cart' ) && is_cart() ) ||
           $is_shop_page;

$container_class = $is_wide ? 'container container--wide' : 'container container--narrow';

while ( have_posts() ) : the_post();
?>


<style>
  .page-hero {
    position: relative;
    padding: 85px 20px 70px;
    background: radial-gradient(circle at center, rgba(35, 28, 18, 0.75) 0%, rgba(17, 17, 24, 0.96) 100%),
                url('<?php echo get_template_directory_uri(); ?>/assets/images/hero-aurika.jpg') center center / cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    border-bottom: 3px solid var(--gold);
    color: #ffffff;
  }
  .page-hero-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
  }
  .page-hero-content {
    position: relative;
    z-index: 2;
    max-width: 750px;
  }
  .page-hero-eyebrow {
    color: #d4a017;
    font-family: var(--font-sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 5px;
    text-transform: uppercase;
    margin-bottom: 12px;
    display: block;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
  }
  .page-hero-title {
    color: #ffffff;
    font-family: var(--font-serif);
    font-size: 46px;
    margin: 0 0 10px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.6);
  }
  .page-hero-sub {
    color: #e8e0d5;
    font-family: var(--font-serif);
    font-size: 16px;
    font-style: italic;
    margin: 0;
  }
  .page-content {
    background: #fdfcfb;
    padding: 60px 0 90px;
  }
  .container--narrow {
    max-width: 850px;
    margin: 0 auto;
    padding: 0 24px;
  }
  .container--wide {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
  }
  .entry-content {
    font-family: var(--font-sans);
    font-size: 15.5px;
    line-height: 1.9;
    color: #444444;
  }
  .entry-content h1, .entry-content h2, .entry-content h3, .entry-content h4 {
    font-family: var(--font-serif);
    color: #111111;
    margin-top: 1.4em;
    margin-bottom: 0.6em;
  }
  .entry-content a {
    color: var(--gold);
    text-decoration: underline;
  }
  .entry-content a:hover {
    color: #111111;
  }
  @media (max-width: 768px) {
    .page-hero-title { font-size: 36px; }
  }
</style>

<section class="page-hero">
  <div class="page-hero-content">
    <span class="page-hero-eyebrow"><?php echo esc_html( get_theme_mod( 'aurika_page_hero_eyebrow', 'AURIKA OFFICIAL' ) ); ?></span>
    <h1 class="page-hero-title"><?php the_title(); ?></h1>
    <p class="page-hero-sub"><?php echo esc_html( get_theme_mod( 'aurika_page_hero_tagline', 'Where Art Meets Elegance.' ) ); ?></p>
  </div>
</section>

<main class="page-content">
  <div class="<?php echo esc_attr( $container_class ); ?>">
    <div class="entry-content">
      <?php 
      if ( $is_shop_page ) {
          echo '<div class="aurika-shop-pills-bar" style="margin-bottom: 35px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
            <a href="' . esc_url( aurika_shop_url() ) . '" class="aurika-cat-pill is-active"><span>✨ All Products</span></a>
            <a href="' . esc_url( home_url('/product-category/scented-candles') ) . '" class="aurika-cat-pill"><span>🕯️ Scented Candles</span></a>
            <a href="' . esc_url( home_url('/product-category/diy-art-kits') ) . '" class="aurika-cat-pill"><span>🎨 DIY Art Kits</span></a>
          </div>';
          echo do_shortcode('[products columns="4" limit="24" paginate="true"]');
      } else {
          the_content();
      }
      ?>
    </div>
    <?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
  </div>
</main>

<?php endwhile; ?>
<?php get_footer(); ?>
