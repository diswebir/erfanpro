<?php
/**
 * Phase 7: services archive — hero, alternating service rows,
 * process steps, contact CTA.
 *
 * Pixel clone of the static reference services.html. The `services`
 * CPT comes from the theme (inc/cpt-services.php, decision 11).
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
		'services',
		array(
			'hero'    => 'template-parts/services/hero',
			'list'    => 'template-parts/services/list',
			'process' => 'template-parts/services/process',
			'cta'     => 'template-parts/home/cta',
		)
	);
	?>
</main>
<?php
get_footer();
