<?php
/**
 * Phase 8: single post hero — breadcrumb (home / blog / category /
 * title), category badge, title, meta (author, Jalali date, reading
 * time), hashtag chips, featured image.
 *
 * Pixel clone of the static reference blog/*.html hero.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'post_hero' );
$erfanpro_blog_st = erfanpro_section_texts( 'blog_hero' );

$post_id = get_the_ID();
$title   = get_the_title();

$cat_name = '';
$cats     = get_the_category( $post_id );
if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
	$cat_name = $cats[0]->name;
}

$author = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
if ( '' === trim( (string) $author ) ) {
	$author = erfanpro_panel_text( $erfanpro_st, 'author', __( 'کارشناسان فنی عرفان صنعت', 'erfanpro' ) );
}

$tags = get_the_tags( $post_id );
if ( is_wp_error( $tags ) ) {
	$tags = false;
}

$image = get_the_post_thumbnail_url( $post_id, 'full' );
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
	<div class="relative z-10 mx-auto max-w-4xl px-5 lg:px-8">
		<div class="flex justify-center">
			<nav aria-label="<?php esc_attr_e( 'مسیر صفحه', 'erfanpro' ); ?>" class="inline-flex max-w-full flex-wrap items-center gap-x-1.5 gap-y-1.5 rounded-2xl px-4 py-2.5 text-[12.5px] font-semibold backdrop-blur border border-white/15 bg-white/[0.07] text-white/70 shadow-[0_8px_24px_-12px_rgba(0,0,0,0.6)]"<?php erfanpro_reveal( 0.0, 'mount', 'opacity: 0; transform: translateY(10px);', 'opacity: 1; transform: none;' ); ?>>
				<a class="flex shrink-0 items-center gap-1.5 rounded-lg px-1.5 py-0.5 transition-colors hover:bg-white/10 hover:text-emerald-200" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="grid h-6 w-6 place-items-center rounded-lg bg-emerald-400/15 text-emerald-200"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house lucide-home h-3.5 w-3.5" aria-hidden="true"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></span><?php esc_html_e( 'صفحه اصلی', 'erfanpro' ); ?>
				</a>
				<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-white/30" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><a class="truncate rounded-lg px-1.5 py-0.5 transition-colors hover:bg-white/10 hover:text-emerald-200" href="<?php echo esc_url( erfanpro_blog_index_url() ); ?>"><?php echo esc_html( erfanpro_panel_text( $erfanpro_blog_st, 'title', __( 'وبلاگ', 'erfanpro' ) ) ); ?></a></span>
				<?php if ( '' !== $cat_name ) : ?>
				<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-white/30" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><span class="max-w-[16rem] truncate rounded-lg px-2 py-0.5 font-extrabold bg-emerald-400/15 text-emerald-200"><?php echo esc_html( $cat_name ); ?></span></span>
				<?php endif; ?>
				<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-white/30" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><span class="max-w-[16rem] truncate rounded-lg px-2 py-0.5 font-extrabold bg-emerald-400/15 text-emerald-200"><?php echo esc_html( $title ); ?></span></span>
			</nav>
		</div>
		<?php if ( '' !== $cat_name ) : ?>
		<div class="mt-8 flex items-center justify-center gap-3"<?php erfanpro_reveal( 0.0 ); ?>>
			<span class="h-px w-8 bg-amber-300/60"></span>
			<span class="rounded-full bg-amber-300 px-4 py-1.5 text-[12.5px] font-black text-emerald-950 shadow-[0_0_24px_rgba(251,191,36,0.4)]"><?php echo esc_html( $cat_name ); ?></span>
			<span class="h-px w-8 bg-amber-300/60"></span>
		</div>
		<?php endif; ?>
		<h1 class="mt-6 text-center text-[1.9rem] font-black leading-[1.5] tracking-[-0.012em] text-white sm:text-[2.5rem] lg:text-[2.9rem] lg:leading-[1.4]"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(26px);', 'opacity: 1; transform: none;' ); ?>><?php echo esc_html( $title ); ?></h1>
		<div class="mx-auto mt-8 flex max-w-2xl flex-wrap items-center justify-center gap-x-7 gap-y-3 rounded-3xl border border-white/12 bg-white/[0.06] px-6 py-4 shadow-[0_24px_50px_-24px_rgba(0,0,0,0.6)] backdrop-blur"<?php erfanpro_reveal( 0.18, 'mount', 'opacity: 0; transform: translateY(16px);', 'opacity: 1; transform: none;' ); ?>>
			<span class="flex items-center gap-2 text-[13px] font-bold text-white/85"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round lucide-user-2 h-4 w-4 text-emerald-300" aria-hidden="true"><circle cx="12" cy="8" r="5"></circle><path d="M20 21a8 8 0 0 0-16 0"></path></svg><?php echo esc_html( $author ); ?></span>
			<span class="hidden h-8 w-px bg-white/10 sm:block"></span>
			<span class="flex items-center gap-2 text-[13px] font-bold text-white/85"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days h-4 w-4 text-emerald-300" aria-hidden="true"><path d="M8 2v3"></path><path d="M16 2v3"></path><rect x="3" y="3" width="18" height="18" rx="2"></rect><path d="M3 9h18"></path><path d="M8 13h.01"></path><path d="M12 13h.01"></path><path d="M16 13h.01"></path><path d="M8 17h.01"></path><path d="M12 17h.01"></path><path d="M16 17h.01"></path></svg><?php echo esc_html( erfanpro_post_jalali_date( $post_id ) ); ?></span>
			<span class="hidden h-8 w-px bg-white/10 sm:block"></span>
			<span class="flex items-center gap-2 text-[13px] font-bold text-white/85"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-3 h-4 w-4 text-emerald-300" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6h4"></path></svg><?php echo esc_html( erfanpro_reading_time_label( $post_id ) ); ?></span>
		</div>
		<?php if ( $tags ) : ?>
		<div class="mt-5 flex flex-wrap items-center justify-center gap-2"<?php erfanpro_reveal( 0.36, 'mount', 'opacity: 0;', 'opacity: 1;' ); ?>>
			<?php foreach ( $tags as $tag ) : ?>
			<span class="rounded-full border border-white/12 bg-white/[0.06] px-3.5 py-1.5 text-[12px] font-bold text-emerald-100 backdrop-blur">#<?php echo esc_html( $tag->name ); ?></span>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
	<?php if ( $image ) : ?>
	<div class="relative z-10 mx-auto mt-10 max-w-5xl px-5 lg:px-8"<?php erfanpro_reveal( 0.0, 'mount', 'opacity: 0; transform: translateY(40px);', 'opacity: 1; transform: none;' ); ?>>
		<div class="overflow-hidden rounded-[2rem] border border-white/20 shadow-[0_40px_80px_-32px_rgba(0,0,0,0.7)]">
			<img alt="<?php echo esc_attr( $title ); ?>" class="h-64 w-full object-cover sm:h-96" src="<?php echo esc_url( $image ); ?>">
		</div>
	</div>
	<?php endif; ?>
	<div aria-hidden="true" class="relative z-10 mt-12 h-10 rounded-t-[2.5rem] bg-[#f8faf9] sm:h-12"></div>
</section>
