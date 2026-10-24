<?php
/**
 * Phase 8: single post body — article content (with auto TOC anchors),
 * tags + share row, and the aside (TOC «سرفصل‌های مقاله» + the
 * «مشاوره فنی رایگان پروژه» CTA box).
 *
 * Pixel clone of the static reference blog/*.html article section.
 * The TOC is built from the real h2 headings of the post content.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'post_article' );

$post_id = get_the_ID();
$toc     = erfanpro_post_toc( get_the_content() );

$tags = get_the_tags( $post_id );
if ( is_wp_error( $tags ) ) {
	$tags = false;
}
?>
<section class="mx-auto max-w-7xl px-5 py-12 lg:px-8 lg:py-16">
	<div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_300px]">
		<article class="min-w-0">
			<div<?php erfanpro_reveal( 0.0, 'inview', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<div class="erfanpro-post-content">
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered the_content with TOC anchors.
					echo apply_filters( 'the_content', $toc['content'] );
					?>
				</div>
			</div>
			<div<?php erfanpro_reveal( 0.07, 'inview', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<div class="mt-12 flex flex-col gap-5 rounded-3xl border border-emerald-100 bg-white p-6 shadow-xs sm:flex-row sm:items-center sm:justify-between">
					<?php if ( $tags ) : ?>
					<div class="flex flex-wrap items-center gap-2">
						<span class="text-[13px] font-bold text-slate-700"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'tags_label', __( 'برچسب‌ها:', 'erfanpro' ) ) ); ?></span>
						<?php foreach ( $tags as $tag ) : ?>
						<span class="rounded-full bg-emerald-50 px-3 py-1.5 text-[12px] font-bold text-emerald-800"><?php echo esc_html( $tag->name ); ?></span>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					<div class="flex items-center gap-2.5">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-share-2 h-4.5 w-4.5 text-slate-400" aria-hidden="true"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line></svg>
						<span class="text-[13px] font-bold text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'share_label', __( 'اشتراک‌گذاری مقاله', 'erfanpro' ) ) ); ?></span>
					</div>
				</div>
			</div>
		</article>
		<aside class="flex flex-col gap-6">
			<?php if ( ! empty( $toc['items'] ) ) : ?>
			<div<?php erfanpro_reveal( 0.0, 'inview', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<nav class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-xs" aria-label="<?php echo esc_attr( erfanpro_panel_text( $erfanpro_st, 'toc_title', __( 'سرفصل‌های مقاله', 'erfanpro' ) ) ); ?>">
					<h4 class="mb-4 flex items-center gap-2 text-[15px] font-extrabold text-slate-900"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list h-4.5 w-4.5 text-emerald-700" aria-hidden="true"><path d="M3 5h.01"></path><path d="M3 12h.01"></path><path d="M3 19h.01"></path><path d="M8 5h13"></path><path d="M8 12h13"></path><path d="M8 19h13"></path></svg><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'toc_title', __( 'سرفصل‌های مقاله', 'erfanpro' ) ) ); ?></h4>
					<ul class="space-y-2.5 border-r border-emerald-100 pr-4">
						<?php foreach ( $toc['items'] as $item ) : ?>
						<li><a href="#<?php echo esc_attr( $item['id'] ); ?>" class="block text-[13px] font-medium leading-6 text-slate-600 transition-colors hover:text-emerald-700"><?php echo esc_html( $item['text'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			</div>
			<?php endif; ?>
			<div<?php erfanpro_reveal( 0.07, 'inview', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<div class="relative overflow-hidden rounded-3xl bg-emerald-950 p-6 text-center shadow-xl">
					<div class="absolute -top-12 left-1/2 h-32 w-32 -translate-x-1/2 rounded-full bg-emerald-400/20 blur-[60px]"></div>
					<h4 class="relative text-[15.5px] font-extrabold text-white"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'consult_title', __( 'مشاوره فنی رایگان پروژه', 'erfanpro' ) ) ); ?></h4>
					<p class="relative mt-2.5 text-[13px] leading-7 text-white/80"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'consult_desc', __( 'برای انتخاب بهترین تجهیزات نورپردازی و دریافت استعلام قیمت روز با کارشناسان ما تماس بگیرید.', 'erfanpro' ) ) ); ?></p>
					<a href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>" class="relative mt-5 flex items-center justify-center gap-2.5 rounded-2xl bg-white px-5 py-3.5 text-[13.5px] font-black text-emerald-900 transition-colors hover:bg-emerald-50"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-4.5 w-4.5" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg><?php echo esc_html( erfanpro_phone_display() ); ?></a>
					<a href="<?php echo esc_url( erfanpro_whatsapp_url() ); ?>" target="_blank" rel="noreferrer" class="relative mt-3 flex items-center justify-center gap-2.5 rounded-2xl border border-emerald-300/40 bg-emerald-300/10 px-5 py-3 text-[13px] font-bold text-emerald-100 transition-colors hover:bg-emerald-300/20"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4.5 w-4.5" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'whatsapp_text', __( 'گفتگو در واتساپ', 'erfanpro' ) ) ); ?></a>
				</div>
			</div>
		</aside>
	</div>
</section>
