<?php
/**
 * Phase 6: project_type taxonomy archive — same layout as the
 * projects archive (hero + filter toolbar + grid + CTA); the hero
 * and toolbar seed the type filter from the queried term.
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
