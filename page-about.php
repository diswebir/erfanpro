<?php
/**
 * Template Name: درباره ما
 *
 * Phase 9: about page assembler — pixel clone of the static reference
 * about.html (hero, story + stats, ISO section, values, CTA). The
 * page's own editor content, when present, renders as an extra section
 * via template-parts/page/content.php.
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
	/* Section manager: order/visibility from the theme options panel. */
	erfanpro_render_page_sections(
		'about',
		array(
			'hero'    => 'template-parts/about/hero',
			'story'   => 'template-parts/about/story',
			'content' => 'template-parts/page/content',
			'cta'     => 'template-parts/home/cta',
		)
	);
	?>
</main>
<?php
get_footer();
