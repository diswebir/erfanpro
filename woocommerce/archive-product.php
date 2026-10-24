<?php
/**
 * Phase 4: WooCommerce product archive — shop page.
 *
 * Pixel-faithful to the static reference products.html:
 * hero (template-parts/shop/hero.php), filter toolbar + grid
 * (template-parts/shop/toolbar.php), contact CTA (shared with home).
 * WooCommerce template loader picks this file up from
 * <theme>/woocommerce/archive-product.php.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main">
	<?php
	/* Section manager: order/visibility from the theme options panel. */
	erfanpro_render_page_sections(
		'shop',
		array(
			'hero'    => 'template-parts/shop/hero',
			'toolbar' => 'template-parts/shop/toolbar',
			'cta'     => 'template-parts/home/cta',
		)
	);
	?>
</main>
<?php
get_footer();
