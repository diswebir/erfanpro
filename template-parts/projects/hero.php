<?php
/**
 * Phase 6: projects archive hero — pixel-faithful to projects.html.
 *
 * Breadcrumb, badge, h1, subtitle, stats, project_type pills (drives
 * the AJAX filter), and the three-image rotated collage. Types come
 * from the live `project_type` taxonomy; the reference names are the
 * fallback when the taxonomy is empty.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'projects_hero' );

$erfanpro_proj_types = get_terms(
	array(
		'taxonomy'   => 'project_type',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
if ( is_wp_error( $erfanpro_proj_types ) || empty( $erfanpro_proj_types ) ) {
	$erfanpro_proj_types = array();
	foreach ( array( 'نورپردازی میدان', 'تونل نوری', 'المان نوری', 'نورپردازی پل' ) as $fallback_name ) {
		$term        = new stdClass();
		$term->slug = '';
		$term->name = $fallback_name;
		$erfanpro_proj_types[] = $term;
	}
}

$erfanpro_hero_pill_active   = 'flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-[12px] font-bold transition-all bg-emerald-700 text-white shadow-md shadow-emerald-700/25';
$erfanpro_hero_pill_inactive = 'flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-[12px] font-bold transition-all border border-slate-200 bg-white text-slate-600 hover:border-emerald-400 hover:text-emerald-700';

/* On a project_type taxonomy archive, the matching pill starts active. */
$erfanpro_hero_type = '';
if ( is_tax( 'project_type' ) ) {
	$erfanpro_qo = get_queried_object();
	if ( $erfanpro_qo instanceof WP_Term ) {
		$erfanpro_hero_type = $erfanpro_qo->slug;
	}
}
?>
<section class="relative overflow-hidden bg-[#f6f8f7] pb-12 pt-28 lg:pb-16 lg:pt-36">
	<div aria-hidden="true" class="pointer-events-none absolute inset-0"><div class="light-dot-grid absolute inset-0 opacity-50"></div><div class="absolute -left-40 top-1/4 h-80 w-80 rounded-full bg-emerald-200/45 blur-[110px]"></div><div class="absolute -right-28 bottom-0 h-72 w-72 rounded-full bg-teal-100/60 blur-[110px]"></div><div class="absolute left-[8%] top-0 h-full w-px bg-gradient-to-b from-transparent via-emerald-300/30 to-transparent"></div><div class="absolute right-[12%] top-0 h-full w-px bg-gradient-to-b from-transparent via-emerald-200/30 to-transparent"></div></div>
	<div class="relative z-10 mx-auto max-w-7xl px-5 lg:px-8">
		<nav aria-label="<?php esc_attr_e( 'مسیر صفحه', 'erfanpro' ); ?>" class="inline-flex max-w-full flex-wrap items-center gap-x-1.5 gap-y-1.5 rounded-2xl px-4 py-2.5 text-[12.5px] font-semibold backdrop-blur border border-emerald-100 bg-white text-slate-500 shadow-[0_8px_24px_-16px_rgba(5,150,105,0.35)]"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(10px);', 'opacity: 1; transform: none;' ); ?>>
			<a class="flex shrink-0 items-center gap-1.5 rounded-lg px-1.5 py-0.5 transition-colors hover:bg-emerald-50 hover:text-emerald-700" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="grid h-6 w-6 place-items-center rounded-lg bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house lucide-home h-3.5 w-3.5" aria-hidden="true"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></span><?php esc_html_e( 'صفحه اصلی', 'erfanpro' ); ?></a>
			<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-slate-300" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><span class="max-w-[16rem] truncate rounded-lg px-2 py-0.5 font-extrabold bg-emerald-50 text-emerald-800"><?php esc_html_e( 'پروژه‌ها', 'erfanpro' ); ?></span></span>
		</nav>
		<div class="grid items-center gap-12 pb-8 pt-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-8 lg:pb-12 lg:pt-12">
			<div>
				<div class="mb-6 inline-flex items-center gap-2.5 rounded-full border border-emerald-200 bg-white py-1.5 pl-5 pr-1.5 shadow-sm"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(14px);', 'opacity: 1; transform: none;' ); ?>>
					<span class="grid h-8 w-8 place-items-center rounded-full bg-emerald-600 text-white shadow-md shadow-emerald-600/25"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles h-4 w-4" aria-hidden="true"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path></svg></span>
					<span class="text-[13px] font-extrabold text-emerald-800"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'kicker', __( 'نمونه‌کارهای اجراشده', 'erfanpro' ) ) ); ?></span>
				</div>
				<h1 class="text-[2.5rem] font-black leading-[1.25] tracking-[-0.015em] text-slate-950 sm:text-[3.3rem] lg:text-[3.9rem] lg:leading-[1.18]"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(28px);', 'opacity: 1; transform: none;' ); ?>><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title_a', __( 'پروژه‌های', 'erfanpro' ) ) ); ?><br><span class="bg-gradient-to-l from-emerald-800 via-emerald-600 to-teal-700 bg-clip-text text-transparent"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title_b', __( 'نورپردازی شهری', 'erfanpro' ) ) ); ?></span></h1>
				<p class="mt-6 max-w-[34rem] text-[15.5px] font-medium leading-9 text-slate-600 lg:text-[16.5px]"<?php erfanpro_reveal( 0.18, 'mount', 'opacity: 0; transform: translateY(22px);', 'opacity: 1; transform: none;' ); ?>><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'desc', __( 'روایتی از هنر، مهندسی و نور در سراسر ایران؛ از تونل‌های نوری و میادین اصلی تا نورپردازی پل‌ها و المان‌های اختصاصی شهرها', 'erfanpro' ) ) ); ?></p>
				<div class="mt-8 flex flex-wrap items-center gap-6"<?php erfanpro_reveal( 0.27, 'mount', 'opacity: 0; transform: translateY(16px);', 'opacity: 1; transform: none;' ); ?>>
					<div class="flex items-center gap-3">
						<span class="text-[2rem] font-black leading-none text-emerald-700"><?php echo esc_html( erfanpro_fa_digits( '850+' ) ); ?></span>
						<span class="max-w-[9rem] text-[12.5px] font-bold leading-5 text-slate-600"><?php esc_html_e( 'پروژه تکمیل‌شده در سراسر کشور', 'erfanpro' ); ?></span>
					</div>
					<span class="hidden h-10 w-px bg-slate-200 sm:block"></span>
					<div class="flex flex-col gap-0.5">
						<span class="text-[14px] font-extrabold text-slate-900"><?php esc_html_e( 'سراسر ایران', 'erfanpro' ); ?></span>
						<span class="text-[12px] font-medium text-slate-500"><?php esc_html_e( 'از ۱۳۷۸ تاکنون', 'erfanpro' ); ?></span>
					</div>
				</div>
				<div class="mt-8 rounded-3xl border border-emerald-100 bg-white p-4 shadow-[0_20px_50px_-24px_rgba(0,80,40,0.18)]"<?php erfanpro_reveal( 0.36, 'mount', 'opacity: 0; transform: translateY(16px);', 'opacity: 1; transform: none;' ); ?>>
					<div class="mb-3 flex items-center justify-between px-1 text-[12px] font-extrabold tracking-wide text-slate-500">
						<span><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'type_label', __( 'نوع سازه:', 'erfanpro' ) ) ); ?></span>
					</div>
					<div class="flex flex-wrap gap-2" data-projects-hero-types>
						<button type="button" data-projects-type="" class="<?php echo esc_attr( '' === $erfanpro_hero_type ? $erfanpro_hero_pill_active : $erfanpro_hero_pill_inactive ); ?>"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'all_types', __( 'همه سازه‌ها', 'erfanpro' ) ) ); ?></button>
						<?php foreach ( $erfanpro_proj_types as $term ) : ?>
						<button type="button" data-projects-type="<?php echo esc_attr( $term->slug ); ?>" class="<?php echo esc_attr( '' !== $term->slug && $term->slug === $erfanpro_hero_type ? $erfanpro_hero_pill_active : $erfanpro_hero_pill_inactive ); ?>"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span><?php echo esc_html( $term->name ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="relative mx-auto hidden h-[30rem] w-full max-w-[28rem] sm:block lg:h-[32rem]"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: scale(0.96);', 'opacity: 1; transform: none;' ); ?>>
				<div aria-hidden="true" class="absolute left-1/2 top-1/2 h-[80%] w-[80%] -translate-x-1/2 -translate-y-1/2 rounded-full bg-emerald-200/50 blur-[90px]"></div>
				<div class="absolute left-0 top-6 h-[72%] w-[60%] overflow-hidden rounded-3xl border-4 border-white shadow-[0_30px_60px_-20px_rgba(0,80,40,0.35)]"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(40px) rotate(-8deg);', 'opacity: 1; transform: rotate(-8deg);' ); ?>>
					<img alt="" class="h-full w-full object-cover" src="<?php echo esc_url( ERFANPRO_URI . '/assets/img/light-tunnel.jpg' ); ?>">
					<div class="absolute inset-0 rounded-3xl ring-1 ring-inset ring-white/40"></div>
				</div>
				<div class="absolute right-0 top-0 h-[52%] w-[46%] overflow-hidden rounded-3xl border-4 border-white shadow-[0_30px_60px_-20px_rgba(0,80,40,0.35)]"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(40px) rotate(6deg);', 'opacity: 1; transform: rotate(6deg);' ); ?>>
					<img alt="" class="h-full w-full object-cover" src="<?php echo esc_url( ERFANPRO_URI . '/assets/img/light-sphere.jpg' ); ?>">
					<div class="absolute inset-0 rounded-3xl ring-1 ring-inset ring-white/40"></div>
				</div>
				<div class="absolute bottom-0 right-[8%] h-[46%] w-[52%] overflow-hidden rounded-3xl border-4 border-white shadow-[0_30px_60px_-20px_rgba(0,80,40,0.35)]"<?php erfanpro_reveal( 0.18, 'mount', 'opacity: 0; transform: translateY(40px) rotate(-3deg);', 'opacity: 1; transform: rotate(-3deg);' ); ?>>
					<img alt="" class="h-full w-full object-cover" src="<?php echo esc_url( ERFANPRO_URI . '/assets/img/light-element.jpg' ); ?>">
					<div class="absolute inset-0 rounded-3xl ring-1 ring-inset ring-white/40"></div>
					<span class="absolute bottom-3 right-4 rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold text-emerald-800 backdrop-blur"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'caption', __( 'آثار اجراشده', 'erfanpro' ) ) ); ?></span>
				</div>
			</div>
		</div>
	</div>
	<div aria-hidden="true" class="relative z-10 -mb-px h-10 rounded-t-[2.5rem] bg-white sm:h-12"></div>
</section>
