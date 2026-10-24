<?php
/**
 * Phase 6: single project details — description, spec table
 * («شناسنامه و مشخصات فنی»), feature chips, and the aside
 * (summary box, CTA, guarantee cards), mirroring the reference
 * single-project layout.
 *
 * Data rows come from real meta/terms; rows the blueprint has no
 * meta key for (client, duration, control, IP rating, warranty)
 * keep the reference fallback, like the phase-5 spec chips.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_pd = erfanpro_section_texts( 'project_details' );

$erfanpro_project_id = get_queried_object_id();
$erfanpro_city       = erfanpro_project_term_name( $erfanpro_project_id, 'city' );
$erfanpro_type       = erfanpro_project_term_name( $erfanpro_project_id, 'project_type' );
$erfanpro_year       = erfanpro_project_year( $erfanpro_project_id );
$erfanpro_used       = erfanpro_project_used_products( $erfanpro_project_id, 100 );
$erfanpro_used_count = count( $erfanpro_used );

$erfanpro_content = get_the_content( null, false, $erfanpro_project_id );
if ( '' === trim( wp_strip_all_tags( (string) $erfanpro_content ) ) ) {
	$erfanpro_content = (string) get_post_meta( $erfanpro_project_id, 'info-project', true );
}

$erfanpro_specs = array(
	array(
		'label' => erfanpro_panel_text( $erfanpro_pd, 'spec_location', __( 'محل اجرای پروژه', 'erfanpro' ) ),
		'value' => '' !== $erfanpro_city ? $erfanpro_city : __( '—', 'erfanpro' ),
	),
	array(
		'label' => erfanpro_panel_text( $erfanpro_pd, 'spec_client', __( 'کارفرما / سازمان', 'erfanpro' ) ),
		'value' => __( 'سازمان زیباسازی', 'erfanpro' ),
	),
	array(
		'label' => erfanpro_panel_text( $erfanpro_pd, 'spec_duration', __( 'مدت زمان اجرا', 'erfanpro' ) ),
		'value' => __( '۲ هفته', 'erfanpro' ),
	),
	array(
		'label' => erfanpro_panel_text( $erfanpro_pd, 'spec_year', __( 'سال اجرا و بهره‌برداری', 'erfanpro' ) ),
		'value' => sprintf( __( 'سال %s', 'erfanpro' ), $erfanpro_year ),
	),
	array(
		'label' => erfanpro_panel_text( $erfanpro_pd, 'spec_type', __( 'نوع سازه و نورپردازی', 'erfanpro' ) ),
		'value' => '' !== $erfanpro_type ? $erfanpro_type : __( '—', 'erfanpro' ),
	),
	array(
		'label' => erfanpro_panel_text( $erfanpro_pd, 'spec_control', __( 'سیستم کنترل و سناریو', 'erfanpro' ) ),
		'value' => __( 'کنترل هوشمند / سناریوی مناسبتی', 'erfanpro' ),
	),
	array(
		'label' => erfanpro_panel_text( $erfanpro_pd, 'spec_standard', __( 'استاندارد مقاومت', 'erfanpro' ) ),
		'value' => __( 'IP67 / IP68 مقاوم در برابر شرایط جوی', 'erfanpro' ),
	),
	array(
		'label' => erfanpro_panel_text( $erfanpro_pd, 'spec_warranty', __( 'گارانتی و خدمات', 'erfanpro' ) ),
		'value' => __( '۲۴ ماه گارانتی + ۵ سال خدمات پس از فروش', 'erfanpro' ),
	),
);

$erfanpro_features = array();
if ( '' !== $erfanpro_type ) {
	$erfanpro_features[] = $erfanpro_type;
}
if ( '' !== $erfanpro_city ) {
	$erfanpro_features[] = sprintf( __( 'اجرا در %s', 'erfanpro' ), $erfanpro_city );
}
$erfanpro_features[] = sprintf( __( 'بهره‌برداری سال %s', 'erfanpro' ), $erfanpro_year );
if ( $erfanpro_used_count > 0 ) {
	$erfanpro_features[] = sprintf( __( '%s دسته محصول', 'erfanpro' ), erfanpro_fa_digits( $erfanpro_used_count ) );
}
?>
<section class="mx-auto max-w-7xl px-5 pb-8 lg:px-8 lg:pb-12">
	<div class="grid gap-10 lg:grid-cols-12 lg:items-start">
		<div class="lg:col-span-8">
			<?php if ( '' !== trim( wp_strip_all_tags( (string) $erfanpro_content ) ) ) : ?>
			<div class="rounded-3xl border border-emerald-100 bg-white p-7 shadow-xs lg:p-9"<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<h2 class="mb-6 flex items-center gap-3 text-2xl font-extrabold text-slate-900"><span class="h-7 w-1.5 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'intro_title', __( 'معرفی و شرح اجرای پروژه', 'erfanpro' ) ) ); ?></h2>
				<div class="space-y-4 text-[15px] leading-9 text-slate-700 lg:text-[15.5px] lg:leading-[2.3]">
					<?php echo wp_kses_post( wpautop( $erfanpro_content ) ); ?>
				</div>
			</div>
			<?php endif; ?>

			<div class="mt-10 rounded-3xl border border-emerald-100 bg-white p-7 shadow-xs lg:p-9"<?php erfanpro_reveal( 0.07, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<div class="mb-6 flex items-center gap-3">
					<span class="grid h-11 w-11 place-items-center rounded-2xl bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-check-2 h-5 w-5" aria-hidden="true"><path d="M10.5 22H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.706.706l3.588 3.588A2.4 2.4 0 0 1 20 8v6"></path><path d="M14 2v5a1 1 0 0 0 1 1h5"></path><path d="m14 20 2 2 4-4"></path></svg></span>
					<div>
						<h3 class="text-xl font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'identity_title', __( 'شناسنامه و مشخصات فنی پروژه', 'erfanpro' ) ) ); ?></h3>
						<p class="mt-1 text-[12.5px] text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'identity_desc', __( 'اطلاعات ثبتی، مشخصات اجرایی و استانداردهای فنی پیاده‌سازی‌شده', 'erfanpro' ) ) ); ?></p>
					</div>
				</div>
				<div class="grid divide-y divide-slate-100 sm:grid-cols-2 sm:gap-x-8">
					<?php foreach ( $erfanpro_specs as $spec ) : ?>
					<div class="flex items-start justify-between gap-4 py-4">
						<span class="text-[13px] font-bold text-slate-500"><?php echo esc_html( $spec['label'] ); ?></span>
						<span class="text-right text-[13.5px] font-extrabold text-slate-800"><?php echo esc_html( $spec['value'] ); ?></span>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( ! empty( $erfanpro_features ) ) : ?>
			<div class="mt-10"<?php erfanpro_reveal( 0.14, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
				<h3 class="mb-5 flex items-center gap-3 text-xl font-extrabold text-slate-900"><span class="h-7 w-1.5 rounded-full bg-gradient-to-b from-emerald-500 to-emerald-700"></span><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'features_title', __( 'ویژگی‌های برجسته و دستاوردهای پروژه', 'erfanpro' ) ) ); ?></h3>
				<ul class="grid gap-3.5 sm:grid-cols-2">
					<?php foreach ( $erfanpro_features as $feature ) : ?>
					<li class="flex items-start gap-3 rounded-xl bg-white p-4 shadow-2xs">
						<span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-3.5 w-3.5" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg></span>
						<span class="text-[13.5px] font-extrabold leading-6 text-slate-800"><?php echo esc_html( $feature ); ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>

		<aside class="lg:col-span-4">
			<div class="space-y-6 lg:sticky lg:top-24">
				<div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-xs"<?php erfanpro_reveal( 0.00, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
					<h4 class="mb-4 text-base font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'summary_title', __( 'خلاصه پروژه', 'erfanpro' ) ) ); ?></h4>
					<div class="space-y-3.5 divide-y divide-slate-100 text-[13.5px]">
						<div class="flex justify-between pt-2"><span class="text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'info_client', __( 'کارفرما:', 'erfanpro' ) ) ); ?></span><span class="font-extrabold text-slate-800"><?php esc_html_e( 'سازمان زیباسازی', 'erfanpro' ); ?></span></div>
						<div class="flex justify-between"><span class="text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'info_location', __( 'موقعیت:', 'erfanpro' ) ) ); ?></span><span class="font-extrabold text-slate-800"><?php echo esc_html( '' !== $erfanpro_city ? $erfanpro_city : __( '—', 'erfanpro' ) ); ?></span></div>
						<div class="flex justify-between"><span class="text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'info_duration', __( 'زمان اجرا:', 'erfanpro' ) ) ); ?></span><span class="font-extrabold text-slate-800"><?php esc_html_e( '۲ هفته', 'erfanpro' ); ?></span></div>
						<div class="flex justify-between"><span class="text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'info_year', __( 'سال بهره‌برداری:', 'erfanpro' ) ) ); ?></span><span class="font-extrabold text-slate-800"><?php echo esc_html( sprintf( __( 'سال %s', 'erfanpro' ), $erfanpro_year ) ); ?></span></div>
						<div class="flex justify-between"><span class="text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'info_equipment', __( 'تعداد تجهیزات:', 'erfanpro' ) ) ); ?></span><span class="font-extrabold text-slate-800"><?php echo esc_html( sprintf( __( '%s دسته محصول', 'erfanpro' ), erfanpro_fa_digits( $erfanpro_used_count ) ) ); ?></span></div>
					</div>
				</div>

				<div class="rounded-3xl bg-emerald-800 p-6 text-white shadow-lg"<?php erfanpro_reveal( 0.07, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
					<h4 class="text-lg font-extrabold leading-8"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'similar_title', __( 'پروژه مشابهی در ذهن دارید؟', 'erfanpro' ) ) ); ?></h4>
					<p class="mt-2 text-[13.5px] leading-7 text-emerald-100"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'similar_desc', __( 'کارشناسان فنی عرفان صنعت آماده مشاوره رایگان، بازدید از محل پروژه و ارائه طرح سه‌بعدی اختصاصی هستند.', 'erfanpro' ) ) ); ?></p>
					<div class="mt-5 flex flex-col gap-3">
						<a class="flex items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-[13.5px] font-black text-emerald-800 transition-colors hover:bg-emerald-50" href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'call_label', __( 'تماس مستقیم: ', 'erfanpro' ) ) ); ?><?php echo esc_html( erfanpro_phone_display() ); ?></a>
						<a class="flex items-center justify-center gap-2 rounded-full border border-white/30 px-5 py-3 text-[13.5px] font-black text-white transition-colors hover:bg-white/10" href="<?php echo esc_url( erfanpro_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'whatsapp_label', __( 'ارسال درخواست در واتساپ', 'erfanpro' ) ) ); ?></a>
					</div>
				</div>

				<div class="grid grid-cols-2 gap-3"<?php erfanpro_reveal( 0.14, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
					<div class="rounded-2xl border border-emerald-100 bg-white p-4 text-center shadow-xs">
						<span class="block text-[15px] font-black text-emerald-700"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'warranty_title', __( '۲۴ ماه گارانتی', 'erfanpro' ) ) ); ?></span>
						<span class="mt-1 block text-[11px] text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'warranty_sub', __( 'تعویض بی‌قید و شرط', 'erfanpro' ) ) ); ?></span>
					</div>
					<div class="rounded-2xl border border-emerald-100 bg-white p-4 text-center shadow-xs">
						<span class="block text-[15px] font-black text-emerald-700"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'service_title', __( '۵ سال خدمات', 'erfanpro' ) ) ); ?></span>
						<span class="mt-1 block text-[11px] text-slate-500"><?php echo esc_html( erfanpro_panel_text( $erfanpro_pd, 'service_sub', __( 'پشتیبانی و تأمین قطعات', 'erfanpro' ) ) ); ?></span>
					</div>
				</div>
			</div>
		</aside>
	</div>
</section>
