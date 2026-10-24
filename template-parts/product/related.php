<?php
/**
 * Phase 5 — «دیگر تولیدات شرکت عرفان صنعت»: manual _upsell_ids first,
 * then same-category products (decision 17), rendered with the
 * phase-4 product card (erfanpro_shop_card_html).
 *
 * The «پروژه‌های اجراشده با این محصول» block lives at the end of
 * template-parts/product/tabs.php (same container as the reference).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'product_related' );

/* The assembler sets $erfanpro_product (WC_Product). */
$product    = isset( $args['erfanpro_product'] ) && $args['erfanpro_product'] instanceof WC_Product ? $args['erfanpro_product'] : null;
$product_id = $product ? $product->get_id() : get_queried_object_id();

$related_products = $product ? erfanpro_related_products( $product_id, 3 ) : array();

if ( empty( $related_products ) ) {
	return;
}

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
?>
<section class="border-t border-emerald-100 bg-white py-16 lg:py-20">
	<div class="mx-auto max-w-7xl px-5 lg:px-8">
		<div<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
			<div class="mb-8 flex items-center justify-between">
				<div>
					<h3 class="text-2xl font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'دیگر تولیدات شرکت عرفان صنعت', 'erfanpro' ) ) ); ?></h3>
					<p class="mt-1 text-[13.5px] text-slate-500"><?php esc_html_e( 'تجهیزات نورپردازی پیشرفته شهری و صنعتی', 'erfanpro' ); ?></p>
				</div>
				<a class="group flex items-center gap-2 text-sm font-bold text-emerald-700 hover:text-emerald-800" href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'cta', __( 'مشاهده همه محصولات', 'erfanpro' ) ) ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4 transition-transform group-hover:-translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
			</div>
		</div>
		<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $related_products as $i => $rp ) : ?>
				<?php echo erfanpro_shop_card_html( $rp, $i * 0.07 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
