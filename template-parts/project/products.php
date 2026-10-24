<?php
/**
 * Phase 6: «محصولات استفاده‌شده در این پروژه» — blueprint
 * `mortabet_mahsole_2` (then `product-in-projects`), rendered with
 * the phase-4 product card (erfanpro_shop_card_html), like the
 * phase-5 related-products block.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'project_products' );

$erfanpro_project_id     = get_queried_object_id();
$erfanpro_used_products  = erfanpro_project_used_products( $erfanpro_project_id, 3 );

if ( empty( $erfanpro_used_products ) ) {
	return;
}
?>
<section class="mx-auto max-w-7xl px-5 pb-8 lg:px-8 lg:pb-12">
	<div<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<h3 class="mb-2 text-2xl font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'محصولات استفاده‌شده در این پروژه', 'erfanpro' ) ) ); ?></h3>
		<p class="mb-6 text-[13.5px] text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'subtitle', __( 'تجهیزات و المان‌های نوری تولیدشده توسط عرفان صنعت که در اجرای این پروژه نصب شده‌اند', 'erfanpro' ) ) ); ?></p>
	</div>
	<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
		<?php foreach ( $erfanpro_used_products as $i => $product ) : ?>
			<?php echo erfanpro_shop_card_html( $product, $i * 0.07 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endforeach; ?>
	</div>
</section>
