<?php
/**
 * Template Name: Elementor Full Width
 * Template Post Type: post, page
 *
 * @package Aurika
 */

get_header();

while ( have_posts() ) : the_post();
    the_content();
endwhile;

get_footer();
