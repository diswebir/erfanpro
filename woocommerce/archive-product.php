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
	get_template_part( 'template-parts/shop/hero' );
	get_template_part( 'template-parts/shop/toolbar' );
	get_template_part( 'template-parts/home/cta' );
	?>
</main>
<?php
get_footer();
