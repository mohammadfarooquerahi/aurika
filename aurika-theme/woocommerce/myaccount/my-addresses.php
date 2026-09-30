<?php
/**
 * My Account — Addresses Page (Aurika Custom Layout)
 *
 * Overrides: woocommerce/myaccount/my-addresses.php
 * @package Aurika
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$address_types = wc_get_account_orders_actions();
	$columns       = 2;
} else {
	$columns = 1;
}

$address_types = apply_filters(
	'woocommerce_my_account_get_addresses',
	array(
		'billing'  => __( 'Billing address', 'woocommerce' ),
		'shipping' => __( 'Shipping address', 'woocommerce' ),
	)
);
?>

<style>
/* ─── Aurika Addresses Page Layout ─── */
.aurika-addresses-wrapper {
  max-width: 820px;
  margin: 0 auto;
}

.aurika-addresses-intro {
  font-family: var(--font-sans, 'Poppins', sans-serif);
  font-size: 13.5px;
  color: #666666;
  margin: 0 0 32px;
  padding: 16px 20px;
  background: #fff8e7;
  border-left: 3px solid #b8860b;
  border-radius: 6px;
}

.aurika-addresses-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}

@media (max-width: 680px) {
  .aurika-addresses-grid {
    grid-template-columns: 1fr;
  }
}

/* Address Card */
.aurika-address-card {
  background: #ffffff;
  border: 1.5px solid #efeae3;
  border-radius: 16px;
  overflow: hidden;
  transition: box-shadow 0.25s ease, border-color 0.25s ease;
}

.aurika-address-card:hover {
  border-color: #b8860b;
  box-shadow: 0 6px 24px rgba(184,134,11,0.1);
}

/* Card Header */
.aurika-address-card-header {
  background: #111118;
  padding: 18px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.aurika-address-card-title {
  font-family: var(--font-serif, 'Playfair Display', serif);
  font-size: 17px;
  font-weight: 700;
  color: #ffffff;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.aurika-address-card-title .addr-icon {
  font-size: 18px;
  line-height: 1;
}

.aurika-address-edit-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-family: var(--font-sans, 'Poppins', sans-serif);
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #b8860b;
  background: rgba(184,134,11,0.12);
  border: 1px solid rgba(184,134,11,0.3);
  padding: 6px 14px;
  border-radius: 50px;
  text-decoration: none;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.aurika-address-edit-btn:hover {
  background: #b8860b;
  color: #ffffff;
  border-color: #b8860b;
}

/* Card Body */
.aurika-address-card-body {
  padding: 24px;
}

.aurika-address-card-body address {
  font-style: normal;
  font-family: var(--font-sans, 'Poppins', sans-serif);
  font-size: 14.5px;
  line-height: 1.9;
  color: #444444;
}

.aurika-address-card-body address p {
  margin: 0;
  line-height: 1.9;
}

/* Empty address state */
.aurika-address-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 32px 20px;
  text-align: center;
  gap: 12px;
}

.aurika-address-empty-icon {
  font-size: 32px;
  line-height: 1;
  opacity: 0.5;
}

.aurika-address-empty p {
  font-size: 13.5px;
  color: #999999;
  margin: 0;
  font-style: italic;
}

.aurika-address-add-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-family: var(--font-sans, 'Poppins', sans-serif);
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #111111;
  background: #f5f0eb;
  border: 1.5px solid #dfd8cc;
  padding: 10px 22px;
  border-radius: 50px;
  text-decoration: none;
  transition: all 0.25s ease;
  margin-top: 4px;
}

.aurika-address-add-btn:hover {
  background: #111111;
  color: #ffffff;
  border-color: #111111;
}
</style>

<div class="aurika-addresses-wrapper">

  <p class="aurika-addresses-intro">
    📍 The addresses below will be used on the checkout page by default.
  </p>

  <div class="aurika-addresses-grid">

    <?php foreach ( $address_types as $name => $label ) :
      $address = wc_get_account_addresses_to_display( $name );
      $edit_url = wc_get_endpoint_url( 'edit-address', $name, wc_get_page_permalink( 'myaccount' ) );
      $icon     = ( $name === 'billing' ) ? '🧾' : '📦';
    ?>

      <div class="aurika-address-card">

        <!-- Card Header -->
        <div class="aurika-address-card-header">
          <h3 class="aurika-address-card-title">
            <span class="addr-icon"><?php echo esc_html( $icon ); ?></span>
            <?php echo esc_html( $label ); ?>
          </h3>
          <a href="<?php echo esc_url( $edit_url ); ?>" class="aurika-address-edit-btn">
            ✏️ Edit
          </a>
        </div>

        <!-- Card Body -->
        <div class="aurika-address-card-body">
          <?php if ( $address ) : ?>
            <address>
              <?php echo wp_kses_post( $address ); ?>
            </address>
          <?php else : ?>
            <div class="aurika-address-empty">
              <span class="aurika-address-empty-icon"><?php echo esc_html( $icon ); ?></span>
              <p>You haven't set a <?php echo esc_html( strtolower( $label ) ); ?> yet.</p>
              <a href="<?php echo esc_url( $edit_url ); ?>" class="aurika-address-add-btn">
                + Add Address
              </a>
            </div>
          <?php endif; ?>
        </div>

      </div>

    <?php endforeach; ?>

  </div>

</div>
