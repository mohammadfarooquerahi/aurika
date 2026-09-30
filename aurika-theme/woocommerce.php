<?php
/**
 * WooCommerce Base Template
 *
 * @package Aurika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_singular( 'product' ) ) {
	get_template_part( 'single-product' );
} else {
	get_template_part( 'archive-product' );
}
