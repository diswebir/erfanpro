<?php
/**
 * Phase 8: blog archive (posts index) assembler — hero (breadcrumb,
 * magazine badge, title, blog-scoped live search, category chips,
 * editor's pick) + «آخرین مقالات» grid with AJAX pagination.
 *
 * Pixel clone of the static reference blog.html. The reference has
 * no sidebar and no pagination; pagination only renders when there
 * is more than one page. Search is scoped to posts (AJAX); the
 * site-wide search page is phase 9.
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
		'blog',
		array(
			'hero' => 'template-parts/blog/hero',
			'grid' => 'template-parts/blog/grid',
		)
	);
	?>
</main>
<?php
get_footer();
