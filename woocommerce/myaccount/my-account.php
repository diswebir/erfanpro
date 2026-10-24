<?php
/**
 * Phase 10: WooCommerce my-account shell — rebuilt with erfanpro
 * styling. Navigation markup lives in myaccount/navigation.php and
 * endpoint content still flows through woocommerce_account_content,
 * so orders / addresses / downloads / edit-account endpoints keep
 * working inside the themed shell (their elements are styled by
 * assets/css/woocommerce.css).
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
			'title'    => __( 'حساب کاربری', 'erfanpro' ),
			'subtitle' => __( 'سفارش‌ها، نشانی‌ها و مشخصات حساب خود را از اینجا مدیریت کنید.', 'erfanpro' ),
			'badge'    => __( 'فروشگاه عرفان صنعت', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="woocommerce erfanpro-account">
			<?php
			/**
			 * Renders the styled account navigation
			 * (woocommerce/myaccount/navigation.php).
			 */
			do_action( 'woocommerce_account_navigation' );
			?>
			<div class="woocommerce-MyAccount-content">
				<?php
				/**
				 * Renders the current endpoint: dashboard, orders,
				 * downloads, addresses, edit-account, etc.
				 */
				do_action( 'woocommerce_account_content' );
				?>
			</div>
		</div>
	</section>
</div>
