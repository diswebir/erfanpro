<?php
/**
 * Phase 6: single project page assembler (classic theme, no Elementor).
 *
 * Layout (pixel clone of the static reference):
 * 1. hero — breadcrumb, type badge, title, subtitle, city + year chips
 * 2. gallery + info card (gallery from blueprint gallery-projects +
 *    second..six images; info rows from city + finish-pro-time)
 * 3. details — description / spec table / features + aside
 *    (summary box, CTA, guarantee cards)
 * 4. used products (mortabet_mahsole_2 — decision 17 manual link)
 * 5. related projects (same city, then same type — decision 17)
 *
 * View counting is automatic (inc/view-counter.php, decision 16).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php
	get_template_part( 'template-parts/project/hero' );
	get_template_part( 'template-parts/project/gallery' );
	get_template_part( 'template-parts/project/details' );
	get_template_part( 'template-parts/project/products' );
	get_template_part( 'template-parts/project/related' );
	?>
</main>
<?php
get_footer();
