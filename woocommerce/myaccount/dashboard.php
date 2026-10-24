<?php
/**
 * Phase 10: WooCommerce account dashboard — rebuilt with erfanpro
 * styling. Same links as the WooCommerce default (orders, addresses,
 * account details) plus the hybrid-model note: unpriced products are
 * bought via inquiry, not from this account area.
 *
 * @package Erfanpro
 */

defined( 'ABSPATH' ) || exit;

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>
<div class="erfanpro-account-dashboard">
	<p>
		<?php
		printf(
			/* translators: 1: user display name 2: logout url */
			wp_kses( __( 'سلام %1$s (حساب کاربری شما نیست؟ <a href="%2$s">خروج</a>)', 'woocommerce' ), $allowed_html ),
			'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
			esc_url( wc_logout_url() )
		);
		?>
	</p>
	<p><?php esc_html_e( 'از پیشخوان حساب کاربری می‌توانید سفارش‌های اخیر را ببینید، نشانی‌های ارسال و صورت‌حساب را مدیریت کنید و رمز عبور و جزئیات حساب را ویرایش کنید.', 'woocommerce' ); ?></p>
	<div class="erfanpro-account-cards">
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>">
			<strong><?php esc_html_e( 'سفارش‌ها', 'woocommerce' ); ?></strong>
			<span><?php esc_html_e( 'مشاهده و پیگیری سفارش‌های اخیر', 'erfanpro' ); ?></span>
		</a>
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>">
			<strong><?php esc_html_e( 'نشانی‌ها', 'woocommerce' ); ?></strong>
			<span><?php esc_html_e( 'نشانی صورت‌حساب و ارسال', 'erfanpro' ); ?></span>
		</a>
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>">
			<strong><?php esc_html_e( 'جزئیات حساب', 'woocommerce' ); ?></strong>
			<span><?php esc_html_e( 'نام، ایمیل و رمز عبور', 'erfanpro' ); ?></span>
		</a>
	</div>
	<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );
	?>
	<?php
	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );
	?>
	<?php
	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );
	?>
	<p class="erfanpro-account-note"><?php esc_html_e( 'توجه: محصولات بدون قیمت از طریق استعلام فروخته می‌شوند و در سبد خرید قرار نمی‌گیرند؛ برای آن‌ها از کارت استعلام همان محصول یا فرم تماس استفاده کنید.', 'erfanpro' ); ?></p>
</div>
