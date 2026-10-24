<?php
/**
 * Phase 8: single post assembler — article hero (breadcrumb, category
 * badge, title, author / Jalali date / reading time meta, hashtag
 * chips, featured image), article body + tags/share row + aside
 * (TOC, contact CTA), related posts, comments.
 *
 * Pixel clone of the static reference blog/*.html. View counting is
 * automatic (inc/view-counter.php, decision 16 — posts are already
 * a tracked post type). The reference has no prev/next navigation,
 * so none is rendered.
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
	while ( have_posts() ) {
		the_post();
		/* Section manager: order/visibility from the theme options panel. */
		erfanpro_render_page_sections(
			'post',
			array(
				'article_hero' => 'template-parts/blog/article-hero',
				'article'      => 'template-parts/blog/article',
				'related'      => 'template-parts/blog/related',
				'comments'     => array(
					'callback' => function () {
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
					},
				),
			)
		);
	}
	?>
</main>
<?php
get_footer();
