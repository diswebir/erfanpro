<?php
/**
 * Phase 6: single project gallery + info card.
 *
 * Gallery mirrors the phase-5 product gallery (main.js binds to
 * `img.cursor-zoom-in` + «تصویر N» thumbs + prev/next + counter
 * automatically). The info card holds the reference rows: location,
 * client, duration, completion year — the last two keep the reference
 * fallback, like the phase-5 spec chips, because the blueprint has no
 * meta key for them.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_project_id = get_queried_object_id();
$erfanpro_title      = get_the_title( $erfanpro_project_id );
$erfanpro_slides     = erfanpro_project_slides( $erfanpro_project_id );

$erfanpro_city = erfanpro_project_term_name( $erfanpro_project_id, 'city' );
$erfanpro_year = erfanpro_project_year( $erfanpro_project_id );

$erfanpro_info_rows = array(
	array(
		'label' => __( 'موقعیت پروژه', 'erfanpro' ),
		'value' => '' !== $erfanpro_city ? $erfanpro_city : __( '—', 'erfanpro' ),
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>',
	),
	array(
		'label' => __( 'کارفرما', 'erfanpro' ),
		'value' => __( 'سازمان زیباسازی', 'erfanpro' ),
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building-2 h-4 w-4" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>',
	),
	array(
		'label' => __( 'مدت اجرا', 'erfanpro' ),
		'value' => __( '۲ هفته', 'erfanpro' ),
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock h-4 w-4" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
	),
	array(
		'label' => __( 'سال بهره‌برداری', 'erfanpro' ),
		'value' => sprintf( __( 'سال %s', 'erfanpro' ), $erfanpro_year ),
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check h-4 w-4" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="m9 16 2 2 4-4"></path></svg>',
	),
);
?>
<section class="mx-auto max-w-7xl px-5 py-8 lg:px-8 lg:py-12" id="erfanpro-project-gallery">
	<div class="grid gap-10 lg:grid-cols-12 lg:items-start">
		<?php if ( ! empty( $erfanpro_slides ) ) : ?>
		<div class="lg:col-span-7">
			<div<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<div class="group relative overflow-hidden rounded-[1.75rem] border border-emerald-100 shadow-lg">
					<img alt="<?php echo esc_attr( sprintf( __( '%s — تصویر ۱', 'erfanpro' ), $erfanpro_title ) ); ?>" class="h-72 w-full cursor-zoom-in object-cover sm:h-96 lg:h-[27rem] anim-run gal-fade" src="<?php echo esc_url( $erfanpro_slides[0]['url'] ); ?>" data-anim="" data-anim-init="opacity: 0; transform: scale(1.04);" data-anim-final="opacity: 1; transform: none;" data-anim-kind="mount" data-anim-delay="0.00">
					<button type="button" aria-label="<?php esc_attr_e( 'نمایش بزرگ‌تر', 'erfanpro' ); ?>" class="absolute left-5 top-5 grid h-10 w-10 place-items-center rounded-full border border-white/30 bg-emerald-950/70 text-white backdrop-blur-md transition-all hover:bg-emerald-600"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-expand h-4.5 w-4.5" aria-hidden="true"><path d="m15 15 6 6"></path><path d="m15 9 6-6"></path><path d="M21 16v5h-5"></path><path d="M21 8V3h-5"></path><path d="M3 16v5h5"></path><path d="m3 21 6-6"></path><path d="M3 8V3h5"></path><path d="M9 9 3 3"></path></svg></button>
					<button type="button" aria-label="<?php esc_attr_e( 'تصویر قبلی', 'erfanpro' ); ?>" class="absolute right-4 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full border border-white/25 bg-emerald-950/60 text-white opacity-0 backdrop-blur-md transition-all hover:bg-emerald-600 group-hover:opacity-100"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-5 w-5" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg></button>
					<button type="button" aria-label="<?php esc_attr_e( 'تصویر بعدی', 'erfanpro' ); ?>" class="absolute left-4 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full border border-white/25 bg-emerald-950/60 text-white opacity-0 backdrop-blur-md transition-all hover:bg-emerald-600 group-hover:opacity-100"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-5 w-5" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg></button>
					<span class="pointer-events-none absolute bottom-4 left-5 rounded-full bg-emerald-950/75 px-3.5 py-1.5 text-[11.5px] font-bold text-white backdrop-blur-md"><?php echo esc_html( erfanpro_fa_digits( '1' ) . ' / ' . erfanpro_fa_digits( (string) count( $erfanpro_slides ) ) ); ?></span>
				</div>
				<?php if ( count( $erfanpro_slides ) > 1 ) : ?>
				<div class="mt-4 grid grid-cols-4 gap-3 sm:grid-cols-5 lg:grid-cols-6">
					<?php foreach ( $erfanpro_slides as $i => $slide ) : ?>
					<button type="button" aria-label="<?php echo esc_attr( sprintf( __( 'تصویر %s', 'erfanpro' ), erfanpro_fa_digits( $i + 1 ) ) ); ?>" class="relative h-16 overflow-hidden rounded-xl border-2 transition-all duration-300 sm:h-20 <?php echo 0 === $i ? 'border-emerald-500 shadow-[0_4px_16px_-4px_rgba(5,150,105,.45)]' : 'border-transparent opacity-65 hover:opacity-100'; ?>">
						<img alt="" loading="lazy" class="h-full w-full object-cover" src="<?php echo esc_url( $slide['url'] ); ?>">
						<?php if ( 0 === $i ) : ?><span class="absolute inset-0 ring-1 ring-inset ring-emerald-400/50"></span><?php endif; ?>
					</button>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>

		<div class="<?php echo ! empty( $erfanpro_slides ) ? 'lg:col-span-5' : 'lg:col-span-12'; ?>">
			<div class="overflow-hidden rounded-[1.75rem] border border-emerald-100 bg-white shadow-lg"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(28px);', 'opacity: 1; transform: none;' ); ?>>
				<div class="border-b border-slate-100 px-6 py-5">
					<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'اطلاعات پروژه', 'erfanpro' ); ?></h2>
					<p class="mt-1 text-[12.5px] text-slate-500"><?php esc_html_e( 'مشخصات اجرایی ثبت‌شده برای این پروژه', 'erfanpro' ); ?></p>
				</div>
				<div class="divide-y divide-slate-100">
					<?php foreach ( $erfanpro_info_rows as $row ) : ?>
					<div class="flex items-center gap-3.5 bg-white px-5 py-5">
						<span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-700"><?php echo $row['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="flex min-w-0 flex-col">
							<span class="text-[11px] font-bold text-slate-400"><?php echo esc_html( $row['label'] ); ?></span>
							<span class="truncate text-[13.5px] font-extrabold text-slate-800"><?php echo esc_html( $row['value'] ); ?></span>
						</span>
					</div>
					<?php endforeach; ?>
				</div>
				<div class="flex flex-col gap-3 border-t border-slate-100 bg-emerald-50/60 px-6 py-5">
					<a class="flex items-center justify-center gap-2 rounded-full bg-emerald-700 px-5 py-3 text-[13.5px] font-black text-white shadow-md shadow-emerald-700/25 transition-colors hover:bg-emerald-800" href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>"><?php esc_html_e( 'تماس مستقیم: ', 'erfanpro' ); ?><?php echo esc_html( erfanpro_phone_display() ); ?></a>
					<a class="flex items-center justify-center gap-2 rounded-full border border-emerald-200 bg-white px-5 py-3 text-[13.5px] font-black text-emerald-800 transition-colors hover:bg-emerald-50" href="<?php echo esc_url( erfanpro_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'ارسال درخواست در واتساپ', 'erfanpro' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>
