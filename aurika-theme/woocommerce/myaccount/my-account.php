<?php
/**
 * My Account page — 1-Column Luxury Layout
 *
 * @package Aurika
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="aurika-myaccount-one-column">

	<?php
	/**
	 * My Account navigation tabs & header.
	 */
	do_action( 'woocommerce_account_navigation' );
	?>

	<div class="woocommerce-MyAccount-content aurika-account-main-content">
		<?php
		/**
		 * My Account active tab content.
		 */
		do_action( 'woocommerce_account_content' );
		?>
	</div>

</div>
