<?php
/**
 * Phase 6: single project hero — breadcrumb, type badge, title,
 * subtitle, city + year chips, and the «مشاهده گالری» CTA.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_project_id    = get_queried_object_id();
$erfanpro_project_title = get_the_title( $erfanpro_project_id );
$erfanpro_project_type  = erfanpro_project_term_name( $erfanpro_project_id, 'project_type' );
$erfanpro_project_city  = erfanpro_project_term_name( $erfanpro_project_id, 'city' );
$erfanpro_project_year  = erfanpro_project_year( $erfanpro_project_id );
$erfanpro_project_sub   = has_excerpt( $erfanpro_project_id )
	? get_the_excerpt( $erfanpro_project_id )
	: wp_trim_words( wp_strip_all_tags( get_the_content( null, false, $erfanpro_project_id ) ), 30, '…' );
?>
<section class="relative overflow-hidden bg-[#f6f8f7] pb-12 pt-28 lg:pb-16 lg:pt-36">
	<div aria-hidden="true" class="pointer-events-none absolute inset-0"><div class="light-dot-grid absolute inset-0 opacity-50"></div><div class="absolute -left-40 top-1/4 h-80 w-80 rounded-full bg-emerald-200/45 blur-[110px]"></div><div class="absolute -right-28 bottom-0 h-72 w-72 rounded-full bg-teal-100/60 blur-[110px]"></div><div class="absolute left-[8%] top-0 h-full w-px bg-gradient-to-b from-transparent via-emerald-300/30 to-transparent"></div></div>
	<div class="relative z-10 mx-auto max-w-7xl px-5 lg:px-8">
		<nav aria-label="<?php esc_attr_e( 'مسیر صفحه', 'erfanpro' ); ?>" class="inline-flex max-w-full flex-wrap items-center gap-x-1.5 gap-y-1.5 rounded-2xl px-4 py-2.5 text-[12.5px] font-semibold backdrop-blur border border-emerald-100 bg-white text-slate-500 shadow-[0_8px_24px_-16px_rgba(5,150,105,0.35)]"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(10px);', 'opacity: 1; transform: none;' ); ?>>
			<a class="flex shrink-0 items-center gap-1.5 rounded-lg px-1.5 py-0.5 transition-colors hover:bg-emerald-50 hover:text-emerald-700" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="grid h-6 w-6 place-items-center rounded-lg bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house lucide-home h-3.5 w-3.5" aria-hidden="true"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></span><?php esc_html_e( 'صفحه اصلی', 'erfanpro' ); ?></a>
			<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-slate-300" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><a class="rounded-lg px-1.5 py-0.5 transition-colors hover:bg-emerald-50 hover:text-emerald-700" href="<?php echo esc_url( erfanpro_archive_url( 'projects' ) ); ?>"><?php esc_html_e( 'پروژه‌ها', 'erfanpro' ); ?></a></span>
			<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-slate-300" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><span class="max-w-[16rem] truncate rounded-lg px-2 py-0.5 font-extrabold bg-emerald-50 text-emerald-800"><?php echo esc_html( $erfanpro_project_title ); ?></span></span>
		</nav>
		<div class="max-w-3xl pb-8 pt-10 lg:pb-12 lg:pt-12">
			<?php if ( '' !== $erfanpro_project_type ) : ?>
			<span class="mb-5 inline-flex items-center gap-2 rounded-full bg-emerald-700 px-4 py-1.5 text-[12.5px] font-extrabold text-white shadow-md shadow-emerald-700/25"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(14px);', 'opacity: 1; transform: none;' ); ?>><?php echo esc_html( $erfanpro_project_type ); ?></span>
			<?php endif; ?>
			<h1 class="text-[2.2rem] font-black leading-[1.25] tracking-[-0.015em] text-slate-950 sm:text-[2.9rem] lg:text-[3.4rem] lg:leading-[1.18]"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(28px);', 'opacity: 1; transform: none;' ); ?>><?php echo esc_html( $erfanpro_project_title ); ?></h1>
			<?php if ( '' !== $erfanpro_project_sub ) : ?>
			<p class="mt-6 max-w-[34rem] text-[15.5px] font-medium leading-9 text-slate-600 lg:text-[16.5px]"<?php erfanpro_reveal( 0.18, 'mount', 'opacity: 0; transform: translateY(22px);', 'opacity: 1; transform: none;' ); ?>><?php echo esc_html( $erfanpro_project_sub ); ?></p>
			<?php endif; ?>
			<div class="mt-8 flex flex-wrap items-center gap-3"<?php erfanpro_reveal( 0.27, 'mount', 'opacity: 0; transform: translateY(16px);', 'opacity: 1; transform: none;' ); ?>>
				<?php if ( '' !== $erfanpro_project_city ) : ?>
				<span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-white px-4 py-2 text-[13px] font-extrabold text-slate-700 shadow-xs"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4 text-emerald-600" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg><?php echo esc_html( $erfanpro_project_city ); ?></span>
				<?php endif; ?>
				<span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-white px-4 py-2 text-[13px] font-extrabold text-slate-700 shadow-xs"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-4 w-4 text-emerald-600" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg><?php echo esc_html( sprintf( __( 'سال %s', 'erfanpro' ), $erfanpro_project_year ) ); ?></span>
				<a href="#erfanpro-project-gallery" class="inline-flex items-center gap-2 rounded-full bg-emerald-700 px-5 py-2.5 text-[13px] font-black text-white shadow-md shadow-emerald-700/25 transition-colors hover:bg-emerald-800"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down h-4 w-4" aria-hidden="true"><path d="M12 5v14"></path><path d="m19 12-7 7-7-7"></path></svg><?php esc_html_e( 'مشاهده گالری و جزئیات پروژه', 'erfanpro' ); ?></a>
			</div>
		</div>
	</div>
	<div aria-hidden="true" class="relative z-10 -mb-px h-10 rounded-t-[2.5rem] bg-white sm:h-12"></div>
</section>
