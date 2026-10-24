<?php
/**
 * Phase 10: WooCommerce empty cart — rebuilt with erfanpro styling.
 *
 * Shown by the [woocommerce_cart] shortcode when the cart is empty.
 * Points visitors at the two real next steps: the shop (priced
 * products) and the contact/inquiry path (unpriced products,
 * decision 3 hybrid model).
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
			'title'    => __( 'سبد خرید', 'erfanpro' ),
			'subtitle' => __( 'سبد خرید شما خالی است.', 'erfanpro' ),
			'badge'    => __( 'فروشگاه عرفان صنعت', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 text-center lg:px-8 lg:py-20">
		<div class="mx-auto max-w-xl rounded-[2rem] border border-slate-100 bg-white p-10 shadow-sm">
			<?php do_action( 'woocommerce_cart_is_empty' ); ?>
			<p class="text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'هنوز محصولی به سبد اضافه نکرده‌اید. از فروشگاه دیدن کنید یا برای محصولات بدون قیمت، استعلام ثبت کنید.', 'erfanpro' ); ?></p>
			<p class="mt-6 flex flex-wrap justify-center gap-3">
				<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'بازگشت به فروشگاه', 'erfanpro' ); ?></a>
				<a class="inline-flex items-center rounded-full border border-emerald-700 px-6 py-3 text-sm font-extrabold text-emerald-800 transition-colors hover:bg-emerald-50" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'ثبت استعلام', 'erfanpro' ); ?></a>
			</p>
		</div>
	</section>
</div>
