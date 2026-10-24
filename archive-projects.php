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
	get_template_part( 'template-parts/projects/hero' );
	get_template_part( 'template-parts/projects/toolbar' );
	get_template_part( 'template-parts/home/cta' );
	?>
</main>
<?php
get_footer();
