<?php
/**
 * Phase 6: city taxonomy archive — same layout as the projects
 * archive (hero + filter toolbar + grid + CTA); the toolbar seeds
 * the city filter from the queried term.
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
		'projects_city',
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
