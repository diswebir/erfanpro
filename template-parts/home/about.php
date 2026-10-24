<?php
/**
 * Home — about (phase 3).
 *
 * Reference layout: text column (badge, headline, paragraph, checklist,
 * CTA + ISO badge) + media column (image card + 4 animated counters).
 * The counters come from the same `home_counters` option as the hero
 * strip (decision 22: real value + offset); reference statics are the
 * design fallback.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_counters = erfanpro_home_counters();
$erfanpro_about_counters = array();
if ( ! empty( $erfanpro_counters ) ) {
	foreach ( array_slice( $erfanpro_counters, 0, 4 ) as $counter ) {
		$erfanpro_about_counters[] = array(
			'value' => erfanpro_home_counter_value( $counter ),
			'label' => (string) ( $counter['label'] ?? '' ),
		);
	}
}
if ( count( $erfanpro_about_counters ) < 4 ) {
	$erfanpro_about_counters = array(
		array( 'value' => 850, 'label' => 'پروژه تکمیل‌شده' ),
		array( 'value' => 100, 'label' => 'نیروی متخصص' ),
		array( 'value' => 289, 'label' => 'مشتری فعال' ),
		array( 'value' => 90,  'label' => 'محصول متنوع' ),
	);
}
$erfanpro_delays = array( 0.00, 0.12, 0.24, 0.36 );

$erfanpro_sec = erfanpro_home_section( 'about' );
?>
<section id="about" class="relative z-10 overflow-hidden py-24 lg:py-32" style="scroll-margin-top: 5rem;">
	<div aria-hidden="true" class="absolute -left-64 top-1/2 hidden h-[560px] w-[560px] -translate-y-1/2 rounded-full border border-emerald-200/60 lg:block">
		<div class="absolute inset-12 rounded-full border border-emerald-200/70"></div>
		<div class="absolute inset-28 rounded-full border border-emerald-300/70"></div>
		<div class="absolute inset-0 animate-spin-slow rounded-full">
			<span class="absolute left-1/2 top-0 h-3 w-3 -translate-x-1/2 rounded-full bg-emerald-600 shadow-[0_0_18px_rgba(5,150,105,.5)]"></span>
		</div>
	</div>
	<div class="mx-auto grid max-w-7xl items-center gap-14 px-5 lg:grid-cols-2 lg:gap-20 lg:px-8">
		<div class="flex flex-col items-start gap-7">
			<div<?php erfanpro_reveal( 0.00 ); ?>>
				<span class="inline-flex items-center gap-2.5 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-[13px] font-bold text-emerald-800"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check h-4 w-4" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path></svg><?php esc_html_e( 'درباره شرکت دانش‌بنیان', 'erfanpro' ); ?></span>
			</div>
			<div<?php erfanpro_reveal( 0.07 ); ?>>
				<?php if ( '' !== $erfanpro_sec['title'] ) : ?>
				<h2 class="text-3xl font-extrabold leading-[1.35] text-slate-900 lg:text-[2.6rem] lg:leading-[1.32]"><?php echo esc_html( $erfanpro_sec['title'] ); ?></h2>
				<?php else : ?>
				<h2 class="text-3xl font-extrabold leading-[1.35] text-slate-900 lg:text-[2.6rem] lg:leading-[1.32]"><?php esc_html_e( 'بیش از', 'erfanpro' ); ?> <span class="text-emerald-700"><?php esc_html_e( '۲۵ سال', 'erfanpro' ); ?></span> <?php esc_html_e( 'تجربه، حاصلِ کارِ مفیدِ بی‌وقفه', 'erfanpro' ); ?></h2>
				<?php endif; ?>
			</div>
			<div<?php erfanpro_reveal( 0.14 ); ?>>
				<?php if ( '' !== $erfanpro_sec['subtitle'] ) : ?>
				<p class="text-[15px] leading-9 text-slate-600 lg:text-[16.5px] lg:leading-10"><?php echo esc_html( $erfanpro_sec['subtitle'] ); ?></p>
				<?php else : ?>
				<p class="text-[15px] leading-9 text-slate-600 lg:text-[16.5px] lg:leading-10"><strong class="text-slate-900"><?php esc_html_e( 'شرکت دانش‌بنیان عرفان صنعت اصفهان', 'erfanpro' ); ?></strong> <?php esc_html_e( 'در زمینه نورپردازی شهری، با سابقه‌ای درخشان و متخصصانی مجرب، طیف وسیعی از خدمات را به', 'erfanpro' ); ?> <strong class="text-emerald-800"><?php esc_html_e( 'شهرداری‌ها', 'erfanpro' ); ?></strong>، <strong class="text-emerald-800"><?php esc_html_e( 'سازمان‌های عمرانی', 'erfanpro' ); ?></strong> <?php esc_html_e( 'و', 'erfanpro' ); ?> <strong class="text-emerald-800"><?php esc_html_e( 'پیمانکاران پروژه‌های شهری', 'erfanpro' ); ?></strong> <?php esc_html_e( 'ارائه می‌دهد. ستاره‌ای درخشان در آسمان صنعت روشنایی ایران که با تکیه بر دانش فنی و برترین تکنولوژی‌ها، به دنبال خلق فضایی ایمن، زیبا و پایدار برای زندگی و کار شماست؛ برای ما نور، نمادی از امید، پیشرفت و زندگی است.', 'erfanpro' ); ?></p>
				<?php endif; ?>
			</div>
			<div<?php erfanpro_reveal( 0.21 ); ?>>
				<ul class="flex flex-col gap-3.5">
					<li class="flex items-center gap-3 text-[15px] font-semibold text-slate-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check lucide-check-circle-2 h-5 w-5 shrink-0 text-emerald-600" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="m16 9-5.5 5.5L8 12"></path></svg><?php esc_html_e( 'طراحی، تولید و اجرای پروژه‌های روشنایی شهری و صنعتی', 'erfanpro' ); ?></li>
					<li class="flex items-center gap-3 text-[15px] font-semibold text-slate-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check lucide-check-circle-2 h-5 w-5 shrink-0 text-emerald-600" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="m16 9-5.5 5.5L8 12"></path></svg><?php esc_html_e( 'دارای گواهینامه بین‌المللی ISO 9001:2015', 'erfanpro' ); ?></li>
					<li class="flex items-center gap-3 text-[15px] font-semibold text-slate-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check lucide-check-circle-2 h-5 w-5 shrink-0 text-emerald-600" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="m16 9-5.5 5.5L8 12"></path></svg><?php esc_html_e( '۲۴ ماه گارانتی تعویض + ۵ سال خدمات پس از فروش', 'erfanpro' ); ?></li>
				</ul>
			</div>
			<div class="flex flex-wrap items-center gap-5"<?php erfanpro_reveal( 0.28 ); ?>>
				<a class="group flex items-center gap-2.5 rounded-full bg-gradient-to-l from-emerald-700 to-emerald-500 px-7 py-3.5 text-[15px] font-extrabold text-white shadow-[0_8px_30px_-10px_rgba(5,150,105,.6)] transition-shadow duration-400 hover:shadow-[0_8px_40px_-8px_rgba(5,150,105,.7)]" href="<?php echo esc_url( '' !== $erfanpro_sec['cta_url'] ? $erfanpro_sec['cta_url'] : erfanpro_page_url( 'about' ) ); ?>"><?php echo '' !== $erfanpro_sec['cta_text'] ? esc_html( $erfanpro_sec['cta_text'] ) : esc_html__( 'کسب اطلاعات بیشتر', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4.5 w-4.5 transition-transform duration-400 group-hover:-translate-y-0.5 group-hover:-translate-x-0.5" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
				<span class="flex items-center gap-2.5 rounded-2xl border border-emerald-200 bg-white px-4 py-3 shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award h-8 w-8 text-emerald-600" aria-hidden="true"><path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path><circle cx="12" cy="8" r="6"></circle></svg><span class="flex flex-col leading-tight"><span class="text-[13px] font-bold text-slate-900">ISO 9001:2015</span><span class="text-[11.5px] text-slate-600"><?php esc_html_e( 'مدیریت کیفیت بین‌المللی', 'erfanpro' ); ?></span></span></span>
			</div>
		</div>
		<div class="relative">
			<div<?php erfanpro_reveal( 0.00 ); ?>>
				<div class="relative overflow-hidden rounded-[2rem] border border-emerald-100 shadow-lg">
					<img alt="<?php esc_attr_e( 'المان نوری عرفان صنعت', 'erfanpro' ); ?>" loading="lazy" class="h-60 w-full object-cover lg:h-64" src="<?php echo esc_url( ERFANPRO_URI . '/assets/img/light-element.jpg' ); ?>">
					<div class="absolute inset-0 bg-gradient-to-t from-emerald-950/90 to-transparent"></div>
					<span class="absolute bottom-5 right-6 text-sm font-bold text-white"><?php esc_html_e( 'المان‌های نوری اختصاصی عرفان صنعت', 'erfanpro' ); ?></span>
				</div>
			</div>
			<div class="mt-5 grid grid-cols-2 gap-5">
				<?php foreach ( $erfanpro_about_counters as $i => $counter ) : ?>
				<div<?php erfanpro_reveal( 0.07 * $i ); ?>>
					<div class="card-light group relative flex flex-col gap-2 rounded-3xl p-6 lg:p-7">
						<span class="absolute left-5 top-5 h-2 w-2 rounded-full bg-emerald-500 transition-all duration-500 group-hover:bg-emerald-700"></span>
						<span class="text-4xl font-black text-emerald-700 lg:text-[2.75rem]"<?php erfanpro_counter( $counter['value'], $erfanpro_delays[ $i ] ?? 0.00, '+' ); ?>><?php echo esc_html( erfanpro_fa_digits( $counter['value'] ) . '+' ); ?></span>
						<span class="text-sm font-semibold text-slate-700"><?php echo esc_html( $counter['label'] ); ?></span>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
