<?php
/**
 * Phase 8: blog archive hero — breadcrumb, magazine badge, title,
 * description, blog-scoped live search box, category chips, and the
 * «منتخب سردبیر» featured card (latest post).
 *
 * Pixel clone of the static reference blog.html hero.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'blog_hero' );

$total      = erfanpro_blog_total_count();
$categories = erfanpro_blog_categories();
$pick       = erfanpro_blog_editors_pick();
?>
<section class="relative overflow-hidden bg-emerald-950 pt-28 lg:pt-36">
	<div aria-hidden="true" class="pointer-events-none absolute inset-0">
		<div class="absolute inset-0 bg-[radial-gradient(1100px_520px_at_50%_-10%,rgba(16,185,129,0.22),transparent_60%)]"></div>
		<div class="absolute -left-40 top-1/4 h-96 w-96 rounded-full bg-amber-400/[0.07] blur-[110px]"></div>
		<div class="absolute -right-32 bottom-0 h-80 w-80 rounded-full bg-emerald-400/[0.12] blur-[110px]"></div>
		<div class="absolute inset-0 opacity-70" style="background-image: radial-gradient(rgba(255, 255, 255, 0.5) 1px, transparent 1.4px), radial-gradient(rgba(110, 231, 183, 0.55) 1px, transparent 1.4px); background-size: 90px 90px, 130px 130px; background-position: 0px 0px, 45px 60px; mask-image: radial-gradient(1000px 560px at 50% 20%, black, transparent 78%);"></div>
		<div class="absolute left-[8%] top-0 h-full w-px bg-gradient-to-b from-transparent via-emerald-300/25 to-transparent"></div>
		<div class="absolute right-[12%] top-0 h-full w-px bg-gradient-to-b from-transparent via-amber-200/20 to-transparent"></div>
	</div>
	<div class="relative z-10 mx-auto max-w-7xl px-5 lg:px-8">
		<nav aria-label="<?php esc_attr_e( 'مسیر صفحه', 'erfanpro' ); ?>" class="inline-flex max-w-full flex-wrap items-center gap-x-1.5 gap-y-1.5 rounded-2xl px-4 py-2.5 text-[12.5px] font-semibold backdrop-blur border border-white/15 bg-white/[0.07] text-white/70 shadow-[0_8px_24px_-12px_rgba(0,0,0,0.6)]"<?php erfanpro_reveal( 0.0, 'mount', 'opacity: 0; transform: translateY(10px);', 'opacity: 1; transform: none;' ); ?>>
			<a class="flex shrink-0 items-center gap-1.5 rounded-lg px-1.5 py-0.5 transition-colors hover:bg-white/10 hover:text-emerald-200" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="grid h-6 w-6 place-items-center rounded-lg bg-emerald-400/15 text-emerald-200"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house lucide-home h-3.5 w-3.5" aria-hidden="true"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></span><?php esc_html_e( 'صفحه اصلی', 'erfanpro' ); ?>
			</a>
			<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-white/30" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><span class="max-w-[16rem] truncate rounded-lg px-2 py-0.5 font-extrabold bg-emerald-400/15 text-emerald-200"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'وبلاگ', 'erfanpro' ) ) ); ?></span></span>
		</nav>
		<div id="erfanpro-blog" class="grid items-center gap-12 pb-16 pt-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-14 lg:pb-20 lg:pt-12" data-initial-cat="">
			<div>
				<div class="mb-6 inline-flex items-center gap-2.5 rounded-full border border-amber-200/25 bg-amber-300/10 py-1.5 pl-5 pr-1.5 backdrop-blur"<?php erfanpro_reveal( 0.0 ); ?>>
					<span class="grid h-8 w-8 place-items-center rounded-full bg-amber-300 text-emerald-950 shadow-[0_0_20px_rgba(251,191,36,0.45)]"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper h-4 w-4" aria-hidden="true"><path d="M15 18h-5"></path><path d="M18 14h-8"></path><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2"></path><rect width="8" height="4" x="10" y="6" rx="1"></rect></svg></span>
					<span class="text-[13px] font-extrabold text-amber-100"><?php echo esc_html( sprintf( __( 'مجله فنی و آموزشی عرفان صنعت · %s مقاله', 'erfanpro' ), erfanpro_fa_digits( $total ) ) ); ?></span>
				</div>
				<h1 class="text-[2.5rem] font-black leading-[1.25] tracking-[-0.015em] text-white sm:text-[3.3rem] lg:text-[3.8rem] lg:leading-[1.18]"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(28px);', 'opacity: 1; transform: none;' ); ?>><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'وبلاگ', 'erfanpro' ) ) ); ?><br><span class="bg-gradient-to-l from-amber-200 via-amber-300 to-emerald-200 bg-clip-text text-transparent drop-shadow-[0_0_28px_rgba(251,191,36,0.35)]"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'subtitle', __( 'نورپردازی شهری', 'erfanpro' ) ) ); ?></span></h1>
				<p class="mt-6 max-w-[34rem] text-[15.5px] font-medium leading-9 text-white/70"<?php erfanpro_reveal( 0.18, 'mount', 'opacity: 0; transform: translateY(22px);', 'opacity: 1; transform: none;' ); ?>><?php esc_html_e( 'مقالات تخصصی، راهنماهای خرید و دانش فنی صنعت روشنایی شهری و صنعتی؛ نوشته‌شده توسط کارشناسان فنی شرکت دانش‌بنیان عرفان صنعت اصفهان', 'erfanpro' ); ?></p>
				<div class="relative mt-8 max-w-[30rem]"<?php erfanpro_reveal( 0.27, 'mount', 'opacity: 0; transform: translateY(16px);', 'opacity: 1; transform: none;' ); ?>>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute right-5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" aria-hidden="true"><path d="m21 21-4.34-4.34"></path><circle cx="11" cy="11" r="8"></circle></svg>
					<input id="erfanpro-blog-search" type="text" value="" placeholder="<?php echo esc_attr( erfanpro_panel_text( $erfanpro_st, 'search_placeholder', __( 'جستجو در مقالات… (مثلاً تونل نوری، والواشر)', 'erfanpro' ) ) ); ?>" aria-label="<?php echo esc_attr( erfanpro_panel_text( $erfanpro_st, 'search_label', __( 'جستجو در مقالات', 'erfanpro' ) ) ); ?>" class="w-full rounded-2xl border border-white/20 bg-white py-4 pl-5 pr-13 text-[14px] font-medium text-slate-800 shadow-[0_20px_50px_-20px_rgba(0,0,0,0.6)] outline-none transition-all placeholder:text-slate-400 focus:border-amber-300 focus:ring-4 focus:ring-amber-300/20">
				</div>
				<?php if ( ! empty( $categories ) ) : ?>
				<div class="mt-5 flex flex-wrap gap-2" data-blog-chips<?php erfanpro_reveal( 0.36, 'mount', 'opacity: 0; transform: translateY(16px);', 'opacity: 1; transform: none;' ); ?>>
					<button type="button" data-blog-cat="" class="rounded-xl px-4 py-2.5 text-[12.5px] font-bold transition-all bg-amber-300 text-emerald-950 shadow-[0_0_24px_rgba(251,191,36,0.35)]"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'all', __( 'همه', 'erfanpro' ) ) ); ?></button>
					<?php foreach ( $categories as $cat ) : ?>
					<button type="button" data-blog-cat="<?php echo esc_attr( $cat['slug'] ); ?>" class="rounded-xl px-4 py-2.5 text-[12.5px] font-bold transition-all border border-white/15 bg-white/[0.06] text-white/75 backdrop-blur hover:border-emerald-300/40 hover:text-emerald-100"><?php echo esc_html( $cat['name'] ); ?></button>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
			<?php if ( $pick instanceof WP_Post ) : ?>
				<?php echo erfanpro_blog_editors_pick_html( $pick->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
		</div>
	</div>
	<div aria-hidden="true" class="relative z-10 -mb-px h-10 rounded-t-[2.5rem] bg-white sm:h-12"></div>
</section>
