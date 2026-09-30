<?php
/**
 * My Account navigation — 1-Column Horizontal Luxury Bar
 *
 * @package Aurika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
$user_initials = '';
if ( $current_user && $current_user->ID ) {
	$first = ! empty( $current_user->first_name ) ? substr( $current_user->first_name, 0, 1 ) : '';
	$last  = ! empty( $current_user->last_name ) ? substr( $current_user->last_name, 0, 1 ) : '';
	$user_initials = strtoupper( $first . $last );
	if ( empty( $user_initials ) ) {
		$user_initials = strtoupper( substr( $current_user->display_name, 0, 2 ) );
	}
}

do_action( 'woocommerce_before_account_navigation' );
?>

<div class="aurika-account-top-header">
	<?php if ( is_user_logged_in() && $current_user->ID ) : ?>
	<div class="aurika-account-profile-bar">
		<div class="aurika-profile-left">
			<div class="aurika-account-avatar"><?php echo esc_html( $user_initials ); ?></div>
			<div class="aurika-account-user-meta">
				<span class="aurika-account-user-greeting">HELLO &amp; WELCOME,</span>
				<h3 class="aurika-account-user-name"><?php echo esc_html( $current_user->display_name ); ?></h3>
				<span class="aurika-account-user-email"><?php echo esc_html( $current_user->user_email ); ?></span>
			</div>
		</div>
		<div class="aurika-profile-right">
			<a href="<?php echo esc_url( wc_logout_url() ); ?>" class="btn-account-logout">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
				<span>Log Out</span>
			</a>
		</div>
	</div>
	<?php endif; ?>

	<nav class="woocommerce-MyAccount-navigation aurika-account-nav-pills">
		<ul class="aurika-nav-pills-list">
			<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : 
				if ( $endpoint === 'customer-logout' ) continue; // Already in top bar
				$icon_svg = '';
				switch( $endpoint ) {
					case 'dashboard':
						$icon_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>';
						break;
					case 'orders':
						$icon_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>';
						break;
					case 'downloads':
						$icon_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
						break;
					case 'edit-address':
						$icon_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
						break;
					case 'edit-account':
						$icon_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
						break;
					default:
						$icon_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>';
						break;
				}
			?>
				<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
						<span class="nav-icon"><?php echo $icon_svg; ?></span>
						<span class="nav-label"><?php echo esc_html( $label ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
</div>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
