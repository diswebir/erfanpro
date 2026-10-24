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
		get_template_part( 'template-parts/blog/article-hero' );
		get_template_part( 'template-parts/blog/article' );
		get_template_part( 'template-parts/blog/related' );
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
	?>
</main>
<?php
get_footer();
