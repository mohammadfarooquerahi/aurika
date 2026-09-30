<?php
/**
 * Login & Registration Form
 *
 * @package Aurika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_customer_login_form' );
?>

<div class="aurika-auth-container" id="customer_login">

	<div class="aurika-auth-grid">

		<!-- 1. LOGIN CARD -->
		<div class="aurika-auth-card aurika-login-card">
			<div class="auth-card-header">
				<span class="auth-card-eyebrow">RETURNING CUSTOMER</span>
				<h2 class="auth-card-title"><?php esc_html_e( 'Sign In', 'woocommerce' ); ?></h2>
				<p class="auth-card-desc">Welcome back! Sign in to view your orders, track shipments, and manage your account.</p>
			</div>

			<form class="woocommerce-form woocommerce-form-login login aurika-luxury-form" method="post">

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required placeholder="e.g. farooq@example.com" />
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required placeholder="Enter your password" />
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<div class="form-row auth-actions-row">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> 
						<span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
					</label>
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="lost-password-link">
						<?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?>
					</a>
				</div>

				<div class="form-row">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit btn-auth-submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">
						<?php esc_html_e( 'LOG IN', 'woocommerce' ); ?> &rarr;
					</button>
				</div>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>
		</div>

		<!-- 2. REGISTER CARD -->
		<div class="aurika-auth-card aurika-register-card">
			<div class="auth-card-header">
				<span class="auth-card-eyebrow">NEW TO AURIKA?</span>
				<h2 class="auth-card-title"><?php esc_html_e( 'Create Account', 'woocommerce' ); ?></h2>
				<p class="auth-card-desc">Register for an account to enjoy faster checkout, save multiple delivery addresses, and track order deliveries.</p>
			</div>

			<form method="post" class="woocommerce-form woocommerce-form-register register aurika-luxury-form" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required placeholder="e.g. customer@example.com" />
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_password"><?php esc_html_e( 'Choose Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required placeholder="Choose a secure password" />
				</p>

				<div class="register-benefits-list">
					<div class="benefit-item">✓ Fast Cash on Delivery checkout</div>
					<div class="benefit-item">✓ Real-time order tracking</div>
					<div class="benefit-item">✓ Exclusive launch offers &amp; discounts</div>
				</div>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<p class="woocommerce-privacy-policy-text" style="font-size: 12px; color: #777; line-height: 1.6; margin: 18px 0 20px;">
					Your personal data will be used to support your experience throughout this website, to manage access to your account, and for purposes described in our <a href="<?php echo esc_url( home_url('/privacy-policy') ); ?>" target="_blank" style="color:#b8860b; text-decoration:underline;">privacy policy</a>.
				</p>

				<div class="woocommerce-form-row form-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn-auth-submit btn-auth-register" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
						<?php esc_html_e( 'CREATE ACCOUNT', 'woocommerce' ); ?> &rarr;
					</button>
				</div>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>
		</div>

	</div>

</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
