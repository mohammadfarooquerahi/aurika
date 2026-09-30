<?php
/**
 * Front Page Template — Aurika
 *
 * @package Aurika
 */

get_header();

// 1. If Elementor editor is active, allow Elementor visual builder to render
if ( class_exists( '\Elementor\Plugin' ) && ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) ) {
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	}
} else {
	// 2. Frontend view: Render the official 7 Luxury Sections for Aurika
	if ( function_exists( 'aurika_get_home_template_html' ) ) {
		echo do_shortcode( aurika_get_home_template_html() );
	}
}

get_footer();
