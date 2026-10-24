<?php
/**
 * Phase 10: WooCommerce checkout — rebuilt with erfanpro styling.
 *
 * Keeps the WooCommerce default checkout structure and every hook
 * (customer details, order review, payment) untouched so payment
 * gateways keep working; only the wrapper and layout classes are
 * the theme's own. The customer-details columns become a responsive
 * grid via assets/css/woocommerce.css instead of the default
 * float-based .col2-set.
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
			'title'    => __( 'تسویه حساب', 'erfanpro' ),
			'subtitle' => __( 'مشخصات صورت‌حساب و ارسال را وارد کنید و سفارش خود را نهایی کنید.', 'erfanpro' ),
			'badge'    => __( 'فروشگاه عرفان صنعت', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<?php
		if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
			echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'برای تسویه حساب باید وارد حساب کاربری خود شوید.', 'woocommerce' ) ) );
		} else {
			?>
			<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'تسویه حساب', 'woocommerce' ); ?>">
				<?php if ( $checkout->get_checkout_fields() ) : ?>
					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
					<div class="col2-set erfanpro-checkout-columns" id="customer_details">
						<div class="col-1">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>
						<div class="col-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>
					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
				<?php endif; ?>
				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
				<h3 id="order_review_heading"><?php esc_html_e( 'سفارش شما', 'woocommerce' ); ?></h3>
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>
				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</form>
			<?php
		}
		?>
		<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
	</section>
</div>
