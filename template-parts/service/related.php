<?php
/**
 * Phase 7: single service related projects — «نمونه پروژه‌های مرتبط».
 *
 * Manual service_projects selection first, then latest projects
 * (decision 17, automatic). Card style matches the reference
 * card-light project cards.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'service_related' );

$erfanpro_id      = get_queried_object_id();
$erfanpro_related = erfanpro_service_related_projects( $erfanpro_id, 3 );

if ( empty( $erfanpro_related ) ) {
	return;
}
?>
<section class="mx-auto max-w-7xl scroll-mt-24 px-5 pb-14 lg:px-8 lg:pb-18">
	<div class="mb-8 flex items-center justify-between"<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<h3 class="text-2xl font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'نمونه پروژه‌های مرتبط', 'erfanpro' ) ) ); ?></h3>
		<a class="group flex items-center gap-2 text-sm font-bold text-emerald-700" href="<?php echo esc_url( erfanpro_archive_url( 'projects' ) ); ?>"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'cta', __( 'همه پروژه‌ها', 'erfanpro' ) ) ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4 transition-transform group-hover:-translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
	</div>
	<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
		<?php foreach ( $erfanpro_related as $erfanpro_i => $erfanpro_pid ) :
			$erfanpro_ptitle = get_the_title( $erfanpro_pid );
			$erfanpro_purl   = get_permalink( $erfanpro_pid );
			$erfanpro_pimg   = get_the_post_thumbnail_url( $erfanpro_pid, 'large' );
			if ( ! $erfanpro_pimg ) {
				$erfanpro_pimg = ERFANPRO_URI . '/assets/img/light-element.jpg';
			}
			$erfanpro_ptype = erfanpro_project_term_name( $erfanpro_pid, 'project_type' );
			$erfanpro_pcity = erfanpro_project_term_name( $erfanpro_pid, 'city' );
			$erfanpro_pyear = erfanpro_project_year( $erfanpro_pid );
		?>
		<div<?php erfanpro_reveal( 0.07 * $erfanpro_i, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
			<a class="card-light group block overflow-hidden rounded-[1.5rem]" href="<?php echo esc_url( $erfanpro_purl ); ?>">
				<div class="relative h-48 overflow-hidden">
					<img alt="<?php echo esc_attr( $erfanpro_ptitle ); ?>" loading="lazy" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-108" src="<?php echo esc_url( $erfanpro_pimg ); ?>">
					<?php if ( '' !== $erfanpro_ptype ) : ?>
					<span class="absolute right-4 top-4 rounded-full bg-emerald-950/75 px-3 py-1 text-[11px] font-bold text-emerald-100 backdrop-blur-md"><?php echo esc_html( $erfanpro_ptype ); ?></span>
					<?php endif; ?>
				</div>
				<div class="p-5">
					<h4 class="text-[15px] font-extrabold text-slate-900 transition-colors group-hover:text-emerald-700"><?php echo esc_html( $erfanpro_ptitle ); ?></h4>
					<p class="mt-2 flex items-center gap-1.5 text-[12px] font-semibold text-slate-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-3.5 w-3.5 text-emerald-600" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg><?php echo esc_html( trim( $erfanpro_pcity . ' — ' . sprintf( __( 'سال %s', 'erfanpro' ), $erfanpro_pyear ), ' —' ) ); ?></p>
				</div>
			</a>
		</div>
		<?php endforeach; ?>
	</div>
</section>
