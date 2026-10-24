<?php
/**
 * Phase 8: blog «آخرین مقالات» grid — header, AJAX-replaced card grid,
 * count text, pagination.
 *
 * Pixel clone of the static reference blog.html #articles section
 * (the reference shows no pagination; the numbered pagination only
 * renders when there is more than one page).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'blog_grid' );

$params = erfanpro_blog_sanitize_params( array() );
$result = erfanpro_blog_render( $params );
$total  = erfanpro_blog_total_count();

$shown = 0;
if ( $result['total'] > 0 ) {
	$shown = min( $result['total'], ERFANPRO_BLOG_PER_PAGE );
}
?>
<section id="articles" class="bg-white py-14 lg:py-16">
	<div class="mx-auto max-w-7xl scroll-mt-24 px-5 lg:px-8">
		<div<?php erfanpro_reveal( 0.0, 'inview', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
			<div class="mb-8 flex flex-wrap items-end justify-between gap-3">
				<div>
					<h2 class="text-2xl font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'آخرین مقالات', 'erfanpro' ) ) ); ?></h2>
					<p class="mt-2 text-[13.5px] font-medium text-slate-500"><span id="erfanpro-blog-count"><?php echo esc_html( erfanpro_blog_count_text( $shown, $result['total'] ) ); ?></span></p>
				</div>
			</div>
		</div>
		<div id="erfanpro-blog-grid" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
			<?php echo $result['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<div id="erfanpro-blog-pagination">
			<?php echo erfanpro_blog_pagination_html( $result['max_pages'], 1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
