<?php
/**
 * Phase 6: projects archive — hero, filter toolbar + grid, contact CTA.
 *
 * Pixel-faithful to the static reference projects.html. The CPT
 * `projects` comes from JetEngine on the live site (or the theme's own
 * fallback registration in inc/cpt-projects-fallback.php).
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
		'projects',
		array(
			'hero'    => 'template-parts/projects/hero',
			'toolbar' => 'template-parts/projects/toolbar',
			'cta'     => 'template-parts/home/cta',
		)
	);
	?>
</main>
<?php
get_footer();
