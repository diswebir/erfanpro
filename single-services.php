<?php
/**
 * Phase 7: single service page assembler (classic theme).
 *
 * Layout (pixel clone of the static reference
 * services/bridge-lighting.html):
 * 1. hero — breadcrumb, «خدمت N از ۵» badge, title, subtitle,
 *    description, CTAs, prev/next links, image card
 * 2. details — features card + aside (steps timeline, phone CTA)
 * 3. FAQ accordion (plan section 4.7; service_faq repeater)
 * 4. related projects (manual selection, then latest)
 * 5. other services mini cards
 * 6. contact CTA
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
	get_template_part( 'template-parts/service/hero' );
	get_template_part( 'template-parts/service/details' );
	get_template_part( 'template-parts/service/faq' );
	get_template_part( 'template-parts/service/related' );
	get_template_part( 'template-parts/service/others' );
	get_template_part( 'template-parts/home/cta' );
	?>
</main>
<?php
get_footer();
