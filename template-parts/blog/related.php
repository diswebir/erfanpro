<?php
/**
 * Phase 8: «مقالات مرتبط» — same-category posts first, then latest,
 * reusing the blog card. Pixel clone of the reference related section.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'post_related' );

$related = erfanpro_related_posts( get_the_ID(), 3 );
if ( empty( $related ) ) {
	return;
}
?>
<section class="border-t border-emerald-100 bg-white py-14 lg:py-16">
	<div class="mx-auto max-w-7xl px-5 lg:px-8">
		<div<?php erfanpro_reveal( 0.0, 'inview', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
			<div class="mb-8 flex items-center justify-between">
				<h3 class="text-2xl font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'مقالات مرتبط', 'erfanpro' ) ) ); ?></h3>
				<a class="group flex items-center gap-2 text-sm font-bold text-emerald-700 hover:text-emerald-800" href="<?php echo esc_url( erfanpro_blog_index_url() ); ?>"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'cta', __( 'همه مقالات', 'erfanpro' ) ) ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4 transition-transform duration-400 group-hover:-translate-y-0.5 group-hover:-translate-x-0.5" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
			</div>
		</div>
		<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			$i = 0;
			foreach ( $related as $post ) {
				echo erfanpro_blog_card_html( $post->ID, 0.07 * $i ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				++$i;
			}
			?>
		</div>
	</div>
</section>
