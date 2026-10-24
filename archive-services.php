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
	get_template_part( 'template-parts/services/hero' );
	get_template_part( 'template-parts/services/list' );
	get_template_part( 'template-parts/services/process' );
	get_template_part( 'template-parts/home/cta' );
	?>
</main>
<?php
get_footer();
