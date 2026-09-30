<?php
/**
 * Main Index Template — Aurika
 *
 * @package Aurika
 */

get_header();

// If viewing the front page / home root, render the full luxury homepage
if ( is_front_page() || ( is_home() && ! get_option( 'page_for_posts' ) ) ) {
	if ( function_exists( 'aurika_get_home_template_html' ) ) {
		echo do_shortcode( aurika_get_home_template_html() );
	}
	get_footer();
	return;
}
?>

<main class="blog-page" style="padding: 80px 0;">
	<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
		<h1 class="page-heading" style="font-family: 'Playfair Display', serif; font-size: 36px; text-align: center; margin-bottom: 40px; color: #111;"><?php esc_html_e( 'Latest Posts', 'aurika' ); ?></h1>

		<div class="posts-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<article <?php post_class( 'post-card' ); ?> style="background: #faf8f5; border: 1px solid #efeae3; border-radius: 12px; overflow: hidden; padding: 25px;">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
							<?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%; height:220px; object-fit:cover; border-radius:8px; margin-bottom:15px;' ) ); ?>
						</a>
					<?php endif; ?>

					<div class="post-info">
						<span class="post-date" style="font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 6px;"><?php echo esc_html( get_the_date() ); ?></span>
						<h2 class="post-title" style="font-family: 'Playfair Display', serif; font-size: 20px; margin: 0 0 10px;">
							<a href="<?php the_permalink(); ?>" style="color: #111; text-decoration: none;"><?php the_title(); ?></a>
						</h2>
						<div class="post-excerpt" style="font-size: 14px; color: #666; line-height: 1.7; margin-bottom: 15px;"><?php the_excerpt(); ?></div>
						<a href="<?php the_permalink(); ?>" class="read-more" style="color: #b8860b; font-size: 13px; font-weight: 600; text-decoration: none;">
							<?php esc_html_e( 'Read More', 'aurika' ); ?> &rarr;
						</a>
					</div>
				</article>

			<?php endwhile; else : ?>
				<p class="no-posts"><?php esc_html_e( 'No posts found.', 'aurika' ); ?></p>
			<?php endif; ?>
		</div>

		<?php the_posts_pagination( array(
			'mid_size'  => 2,
			'prev_text' => '&larr; ' . __( 'Previous', 'aurika' ),
			'next_text' => __( 'Next', 'aurika' ) . ' &rarr;',
		) ); ?>
	</div>
</main>

<?php get_footer(); ?>
