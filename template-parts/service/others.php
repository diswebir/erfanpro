<?php
/**
 * Phase 7: single service «سایر خدمات نورپردازی شهری» — mini cards
 * linking the other services, archive order.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'service_others' );

$erfanpro_id     = get_queried_object_id();
$erfanpro_others = erfanpro_service_others( $erfanpro_id );

if ( empty( $erfanpro_others ) ) {
	return;
}
?>
<section class="mx-auto max-w-7xl scroll-mt-24 px-5 pb-16 lg:px-8 lg:pb-20">
	<div<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<h3 class="mb-6 flex items-center gap-2.5 text-xl font-extrabold text-slate-900 lg:text-2xl"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check lucide-check-circle-2 h-5 w-5 text-emerald-600" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="m16 9-5.5 5.5L8 12"></path></svg><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'title', __( 'سایر خدمات نورپردازی شهری', 'erfanpro' ) ) ); ?></h3>
		<div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
			<?php foreach ( $erfanpro_others as $erfanpro_oid ) :
				$erfanpro_otitle = get_the_title( $erfanpro_oid );
			?>
			<a class="group flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-3 transition-all hover:border-emerald-300 hover:bg-emerald-50/60" href="<?php echo esc_url( get_permalink( $erfanpro_oid ) ); ?>">
				<img alt="<?php echo esc_attr( $erfanpro_otitle ); ?>" loading="lazy" class="h-12 w-12 shrink-0 rounded-xl object-cover" src="<?php echo esc_url( erfanpro_service_image( $erfanpro_oid, 'thumbnail' ) ); ?>">
				<span class="text-[13px] font-extrabold leading-5 text-slate-800 transition-colors group-hover:text-emerald-800"><?php echo esc_html( $erfanpro_otitle ); ?></span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
