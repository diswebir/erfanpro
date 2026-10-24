<?php
/**
 * Shared simple-page hero (phase 10).
 *
 * Used by the phase-10 blueprint page templates (guarantee, licenses,
 * FAQ, careers, feedback). Same emerald-950 hero language as the
 * phase-9 about hero, but generic: title / subtitle / badge come from
 * the template's $args so no section is duplicated five times.
 *
 * Args: title (string), subtitle (string), badge (string).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_hero_title    = isset( $args['title'] ) ? (string) $args['title'] : get_the_title();
$erfanpro_hero_subtitle = isset( $args['subtitle'] ) ? (string) $args['subtitle'] : '';
$erfanpro_hero_badge    = isset( $args['badge'] ) ? (string) $args['badge'] : '';
?>
<section class="relative bg-emerald-950 pt-28 lg:pt-36">
	<div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
		<div class="absolute inset-0 bg-[radial-gradient(1100px_520px_at_50%_-10%,rgba(16,185,129,0.22),transparent_60%)]"></div>
		<div class="absolute -left-40 top-1/4 h-96 w-96 rounded-full bg-amber-400/[0.07] blur-[110px]"></div>
		<div class="absolute -right-32 bottom-0 h-80 w-80 rounded-full bg-emerald-400/[0.12] blur-[110px]"></div>
		<span class="absolute -bottom-10 left-0 select-none whitespace-nowrap text-[10rem] font-black leading-none text-white/[0.03] lg:text-[14rem]"><?php esc_html_e( 'عرفان صنعت', 'erfanpro' ); ?></span>
	</div>
	<div class="relative z-10 mx-auto max-w-7xl px-5 pb-16 lg:px-8 lg:pb-20">
		<nav aria-label="<?php esc_attr_e( 'مسیر صفحه', 'erfanpro' ); ?>" class="inline-flex max-w-full flex-wrap items-center gap-x-1.5 gap-y-1.5 rounded-2xl border border-white/15 bg-white/[0.07] px-4 py-2.5 text-[12.5px] font-semibold text-white/70 shadow-[0_8px_24px_-12px_rgba(0,0,0,0.6)] backdrop-blur">
			<a class="rounded-lg px-1.5 py-0.5 transition-colors hover:bg-white/10 hover:text-emerald-200" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'صفحه اصلی', 'erfanpro' ); ?></a>
			<span aria-hidden="true" class="text-white/30">‹</span>
			<span class="rounded-lg bg-emerald-400/15 px-2 py-0.5 font-extrabold text-emerald-200"><?php echo esc_html( $erfanpro_hero_title ); ?></span>
		</nav>
		<?php if ( '' !== $erfanpro_hero_badge ) : ?>
			<div class="mb-6 mt-10 inline-flex items-center gap-2 rounded-full border border-amber-200/25 bg-amber-300/10 px-4 py-1.5 text-[13px] font-extrabold text-amber-100"><?php echo esc_html( $erfanpro_hero_badge ); ?></div>
		<?php endif; ?>
		<h1 class="text-[2.5rem] font-black leading-[1.25] tracking-[-0.015em] text-white sm:text-[3.3rem] lg:text-[4rem] lg:leading-[1.18]"><?php echo esc_html( $erfanpro_hero_title ); ?></h1>
		<?php if ( '' !== $erfanpro_hero_subtitle ) : ?>
			<p class="mt-6 max-w-[36rem] border-r-2 border-amber-300/50 pr-5 text-[15.5px] font-medium leading-9 text-white/70 lg:text-[16.5px] lg:leading-10"><?php echo esc_html( $erfanpro_hero_subtitle ); ?></p>
		<?php endif; ?>
	</div>
</section>
