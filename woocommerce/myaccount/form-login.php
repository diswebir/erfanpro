<?php
/**
 * Phase 10: WooCommerce login / register — rebuilt with erfanpro
 * styling. Same fields, nonces, and hooks as the WooCommerce
 * default so login, registration, and lost-password flows keep
 * working; only the wrapper and classes are the theme's own.
 *
 * @package Erfanpro
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="erfanpro-wc-wrap min-h-screen bg-[#f8faf9]">
	<?php
	get_template_part(
		'template-parts/page/hero',
		null,
		array(
			'title'    => __( 'ورود / ثبت‌نام', 'erfanpro' ),
			'subtitle' => __( 'برای پیگیری سفارش‌ها و تسویه سریع‌تر، وارد حساب کاربری خود شوید یا یک حساب بسازید.', 'erfanpro' ),
			'badge'    => __( 'فروشگاه عرفان صنعت', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="woocommerce erfanpro-auth">
			<?php do_action( 'woocommerce_before_customer_login_form' ); ?>
			<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
				<div class="u-columns col2-set erfanpro-auth-columns" id="customer_login">
					<div class="u-column1 col-1">
			<?php endif; ?>
			<div class="erfanpro-auth-card">
				<h2><?php esc_html_e( 'ورود', 'woocommerce' ); ?></h2>
				<form class="woocommerce-form woocommerce-form-login login" method="post">
					<?php do_action( 'woocommerce_login_form_start' ); ?>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="username"><?php esc_html_e( 'نام کاربری یا نشانی ایمیل', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="password"><?php esc_html_e( 'رمز عبور', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
					</p>
					<?php do_action( 'woocommerce_login_form' ); ?>
					<p class="form-row">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'مرا به خاطر بسپار', 'woocommerce' ); ?></span>
						</label>
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'ورود', 'woocommerce' ); ?>"><?php esc_html_e( 'ورود', 'woocommerce' ); ?></button>
					</p>
					<p class="woocommerce-LostPassword lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'رمز عبور را فراموش کرده‌اید؟', 'woocommerce' ); ?></a>
					</p>
					<?php do_action( 'woocommerce_login_form_end' ); ?>
				</form>
			</div>
			<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
					</div>
					<div class="u-column2 col-2">
						<div class="erfanpro-auth-card">
							<h2><?php esc_html_e( 'ثبت‌نام', 'woocommerce' ); ?></h2>
							<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
								<?php do_action( 'woocommerce_register_form_start' ); ?>
								<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
										<label for="reg_username"><?php esc_html_e( 'نام کاربری', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
										<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
									</p>
								<?php endif; ?>
								<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
									<label for="reg_email"><?php esc_html_e( 'نشانی ایمیل', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
									<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
								</p>
								<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
										<label for="reg_password"><?php esc_html_e( 'رمز عبور', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
										<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
									</p>
								<?php else : ?>
									<p><?php esc_html_e( 'یک رمز عبور به نشانی ایمیل شما ارسال خواهد شد.', 'woocommerce' ); ?></p>
								<?php endif; ?>
								<?php do_action( 'woocommerce_register_form' ); ?>
								<p class="woocommerce-form-row form-row">
									<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
									<button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'ثبت‌نام', 'woocommerce' ); ?>"><?php esc_html_e( 'ثبت‌نام', 'woocommerce' ); ?></button>
								</p>
								<?php do_action( 'woocommerce_register_form_end' ); ?>
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
		</div>
	</section>
</div>
