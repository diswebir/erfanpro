<?php
/**
 * Phase 7: single service details — «اقلام اجرایی این خدمت» features
 * card (service_features repeater) + the «نکته مهم» note, and the aside
 * with the «مسیر اجرای خدمت» steps timeline (service_steps repeater)
 * and the phone CTA box.
 *
 * Pixel clone of the static reference #service-details grid.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_sd = erfanpro_section_texts( 'service_details' );

$erfanpro_id = get_queried_object_id();
?>
<section id="service-details" class="mx-auto max-w-7xl scroll-mt-24 px-5 py-14 lg:px-8 lg:py-18"><div class="grid gap-8 lg:grid-cols-[1fr_340px]"><div style="opacity: 1; filter: blur(0px); transform: none;" class="anim-run" data-anim="" data-anim-init="opacity: 0; filter: blur(6px); transform: translateY(42px);" data-anim-final="opacity: 1; filter: blur(0px); transform: none;" data-anim-kind="mount" data-anim-delay="0.00"><div class="rounded-3xl border border-emerald-100 bg-white p-7 shadow-sm lg:p-9"><h2 class="mb-6 flex items-center gap-3 text-xl font-extrabold text-slate-900 lg:text-2xl"><span class="h-7 w-1.5 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span><?php echo esc_html( erfanpro_panel_text( $erfanpro_sd, 'items_title', __( 'اقلام اجرایی این خدمت', 'erfanpro' ) ) ); ?></h2><?php $erfanpro_features = erfanpro_service_items( $erfanpro_id, 'service_features' ); ?>
						<?php if ( ! empty( $erfanpro_features ) ) : ?>
						<ul class="grid gap-4 sm:grid-cols-2">
							<?php foreach ( $erfanpro_features as $erfanpro_fi => $erfanpro_f ) :
								$erfanpro_ft = trim( (string) ( $erfanpro_f['title'] ?? '' ) );
								if ( '' === $erfanpro_ft ) {
									continue;
								}
								$erfanpro_fd   = trim( (string) ( $erfanpro_f['desc'] ?? '' ) );
								$erfanpro_fnum = trim( (string) ( $erfanpro_f['number'] ?? '' ) );
								if ( '' === $erfanpro_fnum ) {
									$erfanpro_fnum = (string) ( $erfanpro_fi + 1 );
								}
							?>
							<li class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 text-[14px] font-bold leading-7 text-slate-700"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-emerald-700 text-[13px] font-black text-white"><?php echo esc_html( erfanpro_fa_digits( $erfanpro_fnum ) ); ?></span><?php echo esc_html( $erfanpro_ft ); ?><?php if ( '' !== $erfanpro_fd ) : ?><span class="mt-1 block text-[12px] font-medium leading-6 text-slate-500"><?php echo esc_html( $erfanpro_fd ); ?></span><?php endif; ?></li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?><div class="mt-7 rounded-2xl bg-emerald-50/70 p-5 text-[13.5px] font-medium leading-8 text-slate-600"><span class="font-extrabold text-emerald-800"><?php echo esc_html( erfanpro_panel_text( $erfanpro_sd, 'note_title', __( 'نکته مهم: ', 'erfanpro' ) ) ); ?></span><?php echo esc_html( erfanpro_service_note( $erfanpro_id ) ); ?></div></div></div><div class="flex flex-col gap-6"><div style="opacity: 1; filter: blur(0px); transform: none;" class="anim-run" data-anim="" data-anim-init="opacity: 0; filter: blur(6px); transform: translateY(42px);" data-anim-final="opacity: 1; filter: blur(0px); transform: none;" data-anim-kind="mount" data-anim-delay="0.00"><div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm"><h3 class="mb-5 text-[16px] font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_sd, 'process_title', __( 'مسیر اجرای خدمت', 'erfanpro' ) ) ); ?></h3><ol class="relative space-y-1">
								<span aria-hidden="true" class="absolute bottom-5 right-[1.1rem] top-5 w-0.5 rounded-full bg-emerald-100"></span>
								<?php foreach ( erfanpro_service_steps( $erfanpro_id ) as $erfanpro_si => $erfanpro_step ) : ?>
								<li class="relative flex items-center gap-3.5 py-2"><span class="relative z-10 grid h-9 w-9 shrink-0 place-items-center rounded-full border-2 border-emerald-200 bg-white text-emerald-700"><?php echo erfanpro_service_step_icon( $erfanpro_si ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><span class="flex flex-col"><span class="text-[13px] font-extrabold text-slate-800"><?php echo esc_html( $erfanpro_step['title'] ); ?></span><?php if ( '' !== $erfanpro_step['desc'] ) : ?><span class="text-[11.5px] text-slate-500"><?php echo esc_html( $erfanpro_step['desc'] ); ?></span><?php endif; ?></span><?php if ( 0 === $erfanpro_si ) : ?><span class="mr-auto rounded-full bg-emerald-50 px-2.5 py-1 text-[10.5px] font-black text-emerald-700"><?php echo esc_html( erfanpro_panel_text( $erfanpro_sd, 'step_start_badge', __( 'شروع', 'erfanpro' ) ) ); ?></span><?php endif; ?></li>
								<?php endforeach; ?>
							</ol></div></div><div style="opacity: 0; filter: blur(6px); transform: translateY(42px); --anim-delay: 0.07s;" data-anim="" data-anim-init="opacity: 0; filter: blur(6px); transform: translateY(42px);" data-anim-final="opacity: 1; filter: blur(0px); transform: none;" data-anim-kind="inview" data-anim-delay="0.07"><div class="relative overflow-hidden rounded-3xl bg-emerald-950 p-6 text-center shadow-xl"><div class="absolute -top-12 left-1/2 h-32 w-32 -translate-x-1/2 rounded-full bg-emerald-400/20 blur-[60px]"></div><h4 class="relative text-[15px] font-extrabold text-white"><?php echo esc_html( erfanpro_panel_text( $erfanpro_sd, 'cta_title', __( 'همین خدمت را برای شهر خود بخواهید', 'erfanpro' ) ) ); ?></h4><a href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>" class="relative mt-4 flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-[13.5px] font-black text-emerald-900 transition-colors hover:bg-emerald-50"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-4 w-4" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg><?php echo esc_html( erfanpro_phone_display() ); ?></a></div></div></div></div>
</section>
