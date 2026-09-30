<?php
/**
 * 404 Error page template.
 *
 * @package Aurika
 */

get_header();
?>

<main class="error-404">
	<div class="container error-container">
		<span class="error-number">404</span>
		<h1><?php echo esc_html( get_theme_mod( 'aurika_404_title', 'Page Not Found' ) ); ?></h1>
		<p><?php echo esc_html( get_theme_mod( 'aurika_404_message', "The page you're looking for doesn't exist. Let's get you back home." ) ); ?></p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-solid">
			<?php echo esc_html( get_theme_mod( 'aurika_404_btn_text', 'Back to Home' ) ); ?>
		</a>
	</div>
</main>

<?php get_footer(); ?>
