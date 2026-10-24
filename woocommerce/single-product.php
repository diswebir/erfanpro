<?php
/**
 * Phase 5 — single product page assembler (classic theme, no Elementor).
 *
 * Layout (pixel clone of the static reference):
 * 1. hero — breadcrumb, badges, title, spec chips, CTAs
 * 2. gallery + order card (sticky hybrid: priced → Woo add-to-cart,
 *    unpriced → inquiry card — decision 3)
 * 3. tabs — description / specs / install / catalog / FAQ
 *    (the «پروژه‌های اجراشده» block closes this section, like the ref)
 * 4. related products («دیگر تولیدات» — decision 17)
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$erfanpro_product = function_exists( 'wc_get_product' ) ? wc_get_product( get_queried_object_id() ) : null;
if ( ! $erfanpro_product ) {
	$erfanpro_product = null;
}
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php get_template_part( 'template-parts/product/hero', null, array( 'erfanpro_product' => $erfanpro_product ) ); ?>

	<section class="mx-auto max-w-7xl px-5 py-8 lg:px-8 lg:py-12">
		<div class="grid gap-10 lg:grid-cols-12 lg:items-start">
			<?php get_template_part( 'template-parts/product/gallery', null, array( 'erfanpro_product' => $erfanpro_product ) ); ?>
			<?php get_template_part( 'template-parts/product/order-card', null, array( 'erfanpro_product' => $erfanpro_product ) ); ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/product/tabs', null, array( 'erfanpro_product' => $erfanpro_product ) ); ?>

	<?php get_template_part( 'template-parts/product/related', null, array( 'erfanpro_product' => $erfanpro_product ) ); ?>
</main>
<?php
get_footer();
