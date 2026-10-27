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
	<?php
	/* Section manager: order/visibility from the theme options panel. */
	/* NOTE: single-product is the one assembler whose sections were
	 * separated by literal HTML whitespace (blank lines + tabs) rather
	 * than a single PHP block. The showcase/related callbacks echo that
	 * whitespace explicitly so default output stays byte-identical. */
	erfanpro_render_page_sections(
		'product',
		array(
			'hero'     => array( 'part' => 'template-parts/product/hero', 'args' => array( 'erfanpro_product' => $erfanpro_product ) ),
			'showcase' => array(
				'callback' => function ( $args ) {
					/* Gaps reproduce the base assembler exactly: the \n after
					 * a ?> is eaten by PHP, and hero/tabs parts end with
					 * their own \n — so hero→showcase is \n\t (not \n\n\t),
					 * gallery→order is \t\t\t, order→grid-close is \t\t. */
					echo "\n\t" . '<section class="mx-auto max-w-7xl px-5 py-8 lg:px-8 lg:py-12">' . "\n\t\t" . '<div class="grid gap-10 lg:grid-cols-12 lg:items-start">' . "\n\t\t\t";
					get_template_part( 'template-parts/product/gallery', null, $args );
					echo "\t\t\t";
					get_template_part( 'template-parts/product/order-card', null, $args );
					echo "\t\t" . '</div>' . "\n\t" . '</section>' . "\n\n\t";
				},
			),
			'tabs'     => array( 'part' => 'template-parts/product/tabs', 'args' => array( 'erfanpro_product' => $erfanpro_product ) ),
			'related'  => array(
				'callback' => function ( $args ) {
					/* Tabs part ends with its own \n, so tabs→related is \n\t. */
					echo "\n\t";
					get_template_part( 'template-parts/product/related', null, $args );
				},
			),
		),
		/* Default $args for the callbacks (showcase/related): hero and
		 * tabs carry their own explicit 'args' and are unaffected. */
		array( 'erfanpro_product' => $erfanpro_product )
	);
	?>
</main>
<?php
get_footer();
