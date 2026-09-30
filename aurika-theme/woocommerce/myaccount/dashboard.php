<?php
/**
 * My Account Dashboard
 *
 * @package Aurika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="aurika-account-dashboard-wrap">

	<div class="aurika-dashboard-header">
		<h2>Hello, <span><?php echo esc_html( $current_user->display_name ); ?></span></h2>
		<p class="aurika-dashboard-intro">
			<?php
			/* translators: 1: user display name 2: logout url */
			printf(
				__( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ),
				esc_url( wc_get_endpoint_url( 'orders' ) ),
				esc_url( wc_get_endpoint_url( 'edit-address' ) ),
				esc_url( wc_get_endpoint_url( 'edit-account' ) )
			);
			?>
		</p>
	</div>

	<!-- 3 QUICK ACTION CARDS -->
	<div class="aurika-dashboard-cards-grid">

		<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="aurika-dash-card">
			<div class="dash-card-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
			</div>
			<h4>My Orders</h4>
			<p>View your order history, delivery status, and receipts.</p>
			<span class="dash-card-link">View Orders &rarr;</span>
		</a>

		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="aurika-dash-card">
			<div class="dash-card-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
			</div>
			<h4>Addresses</h4>
			<p>Manage your default shipping and billing addresses.</p>
			<span class="dash-card-link">Manage Addresses &rarr;</span>
		</a>

		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="aurika-dash-card">
			<div class="dash-card-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
			</div>
			<h4>Account Details</h4>
			<p>Update your personal info, email, or account password.</p>
			<span class="dash-card-link">Edit Details &rarr;</span>
		</a>

	</div>

	<!-- SUPPORT STRIP -->
	<div class="aurika-account-help-strip">
		<div class="help-strip-text">
			<h4>Need help with an order or product?</h4>
			<p>Our Aurika customer care team is available on WhatsApp daily.</p>
		</div>
		<a href="https://wa.me/923211632101" target="_blank" rel="noopener" class="help-strip-btn">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
			WhatsApp Support
		</a>
	</div>

</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );
?>
