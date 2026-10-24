<?php
/**
 * Home — why us (phase 3).
 *
 * Reference section: «چرا عرفان صنعت؟» — four trust cards plus the
 * five-step cooperation path («از ایده تا درخشش»). Fixed design
 * content, cloned from the reference.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="relative z-10 py-24 lg:py-32">
	<div class="mx-auto max-w-7xl px-5 lg:px-8">
		<div class="flex max-w-3xl flex-col gap-4 items-center text-center mx-auto">
			<div<?php erfanpro_reveal( 0.00 ); ?>>
				<span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/25 bg-emerald-50 px-4 py-1.5 text-[13px] font-bold tracking-wide text-emerald-800 shadow-xs">
					<span class="relative flex h-2 w-2">
						<span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-60"></span>
						<span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
					</span><?php esc_html_e( 'چرا عرفان صنعت؟', 'erfanpro' ); ?>
				</span>
			</div>
			<div<?php erfanpro_reveal( 0.07 ); ?>>
				<h2 class="text-3xl font-extrabold leading-[1.35] text-slate-900 sm:text-4xl lg:text-[2.6rem] lg:leading-[1.3]"><?php esc_html_e( 'تعهد ما به', 'erfanpro' ); ?> <span class="bg-gradient-to-l from-emerald-800 via-emerald-600 to-teal-700 bg-clip-text text-transparent"><?php esc_html_e( 'کیفیت و دوام', 'erfanpro' ); ?></span></h2>
			</div>
			<div<?php erfanpro_reveal( 0.14 ); ?>>
				<p class="text-base leading-8 text-slate-600 lg:text-[17px] lg:leading-9"><?php esc_html_e( 'نورپردازی شهری سرمایه‌گذاری بلندمدت است؛ به همین دلیل هر محصول ما حاصل استانداردهای سخت‌گیرانه و تعهد به رضایت شماست.', 'erfanpro' ); ?></p>
			</div>
		</div>
		<div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
			<div<?php erfanpro_reveal( 0.00 ); ?>>
				<div class="card-light group relative flex h-full flex-col gap-4 overflow-hidden rounded-3xl p-7">
					<div class="absolute -left-10 -top-10 h-32 w-32 rounded-full blur-[60px] transition-opacity duration-700 group-hover:opacity-100 bg-emerald-200/70 opacity-60"></div>
					<span class="relative grid h-13 w-13 place-items-center rounded-2xl border transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6 border-emerald-200 bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check lucide-verified h-6 w-6" aria-hidden="true"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"></path><path d="m16 9-5.5 5.5L8 12"></path></svg></span>
					<h3 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'گواهی ISO 9001:2015', 'erfanpro' ); ?></h3>
					<p class="text-[13.5px] font-medium leading-7 text-slate-600"><?php esc_html_e( 'مهر تأیید بین‌المللی بر سیستم مدیریت کیفیت در تمامی فرآیندهای سازمان', 'erfanpro' ); ?></p>
					<span class="mt-auto h-px w-full bg-gradient-to-l from-transparent via-emerald-400/50 to-transparent"></span>
				</div>
			</div>
			<div<?php erfanpro_reveal( 0.07 ); ?>>
				<div class="card-light group relative flex h-full flex-col gap-4 overflow-hidden rounded-3xl p-7">
					<div class="absolute -left-10 -top-10 h-32 w-32 rounded-full blur-[60px] transition-opacity duration-700 group-hover:opacity-100 bg-teal-200/60 opacity-50"></div>
					<span class="relative grid h-13 w-13 place-items-center rounded-2xl border transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6 border-teal-200 bg-teal-50 text-teal-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cloud-rain h-6 w-6" aria-hidden="true"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"></path><path d="M16 14v6"></path><path d="M8 14v6"></path><path d="M12 16v6"></path></svg></span>
					<h3 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'استاندارد IP67 / IP68', 'erfanpro' ); ?></h3>
					<p class="text-[13.5px] font-medium leading-7 text-slate-600"><?php esc_html_e( 'مقاومت کامل در برابر آب، گرد و غبار؛ از باران‌های شمال تا کویر', 'erfanpro' ); ?></p>
					<span class="mt-auto h-px w-full bg-gradient-to-l from-transparent via-teal-400/50 to-transparent"></span>
				</div>
			</div>
			<div<?php erfanpro_reveal( 0.14 ); ?>>
				<div class="card-light group relative flex h-full flex-col gap-4 overflow-hidden rounded-3xl p-7">
					<div class="absolute -left-10 -top-10 h-32 w-32 rounded-full blur-[60px] transition-opacity duration-700 group-hover:opacity-100 bg-teal-200/60 opacity-50"></div>
					<span class="relative grid h-13 w-13 place-items-center rounded-2xl border transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6 border-teal-200 bg-teal-50 text-teal-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-radio-tower h-6 w-6" aria-hidden="true"><path d="M4.9 16.1C1 12.2 1 5.8 4.9 1.9"></path><path d="M7.8 4.7a6.14 6.14 0 0 0-.8 7.5"></path><circle cx="12" cy="9" r="2"></circle><path d="M16.2 4.8c2 2 2.26 5.11.8 7.47"></path><path d="M19.1 1.9a9.96 9.96 0 0 1 0 14.1"></path><path d="M9.5 18h5"></path><path d="m8 22 4-11 4 11"></path></svg></span>
					<h3 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'کنترل هوشمند', 'erfanpro' ); ?></h3>
					<p class="text-[13.5px] font-medium leading-7 text-slate-600"><?php esc_html_e( 'مدیریت رنگ و افکت‌های نوری از راه دور با WiFi و پروتکل NRF', 'erfanpro' ); ?></p>
					<span class="mt-auto h-px w-full bg-gradient-to-l from-transparent via-teal-400/50 to-transparent"></span>
				</div>
			</div>
			<div<?php erfanpro_reveal( 0.21 ); ?>>
				<div class="card-light group relative flex h-full flex-col gap-4 overflow-hidden rounded-3xl p-7">
					<div class="absolute -left-10 -top-10 h-32 w-32 rounded-full blur-[60px] transition-opacity duration-700 group-hover:opacity-100 bg-emerald-200/70 opacity-60"></div>
					<span class="relative grid h-13 w-13 place-items-center rounded-2xl border transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6 border-emerald-200 bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check h-6 w-6" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path></svg></span>
					<h3 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( '۲۴ ماه گارانتی + ۵ سال خدمات', 'erfanpro' ); ?></h3>
					<p class="text-[13.5px] font-medium leading-7 text-slate-600"><?php esc_html_e( 'گارانتی تعویض و خدمات پس از فروش؛ چون اعتماد شما بزرگ‌ترین سرمایه ماست', 'erfanpro' ); ?></p>
					<span class="mt-auto h-px w-full bg-gradient-to-l from-transparent via-emerald-400/50 to-transparent"></span>
				</div>
			</div>
		</div>
		<div class="mt-20"<?php erfanpro_reveal( 0.00 ); ?>>
			<div class="relative overflow-hidden rounded-[2rem] border border-emerald-100 bg-white p-8 shadow-sm lg:p-12">
				<div class="absolute -top-24 left-1/2 h-56 w-[32rem] -translate-x-1/2 rounded-full bg-emerald-100 blur-[100px]"></div>
				<h3 class="relative mb-10 text-center text-xl font-extrabold text-slate-900 lg:text-2xl"><?php esc_html_e( 'مسیر همکاری با ما؛', 'erfanpro' ); ?><span class="text-emerald-700"><?php esc_html_e( ' از ایده تا درخشش', 'erfanpro' ); ?></span></h3>
				<div class="relative grid gap-8 sm:grid-cols-3 lg:grid-cols-5 lg:gap-4">
					<div class="absolute right-[10%] left-[10%] top-7 hidden h-px bg-gradient-to-l from-transparent via-emerald-400/50 to-transparent lg:block"></div>
					<div class="relative flex flex-col items-center gap-3.5 text-center">
						<span class="relative z-10 grid h-14 w-14 place-items-center rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm transition-transform duration-500 hover:scale-110 hover:border-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-6 w-6 text-emerald-700" aria-hidden="true"><path d="m21 21-4.34-4.34"></path><circle cx="11" cy="11" r="8"></circle></svg><span class="absolute -right-2 -top-2 grid h-6 w-6 place-items-center rounded-full bg-emerald-600 text-[11px] font-black text-white"><?php echo esc_html( erfanpro_fa_digits( '1' ) ); ?></span></span>
						<span class="text-[13.5px] font-bold leading-6 text-slate-700"><?php esc_html_e( 'بازدید و مشاوره رایگان', 'erfanpro' ); ?></span>
					</div>
					<div class="relative flex flex-col items-center gap-3.5 text-center">
						<span class="relative z-10 grid h-14 w-14 place-items-center rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm transition-transform duration-500 hover:scale-110 hover:border-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-ruler h-6 w-6 text-emerald-700" aria-hidden="true"><path d="M13 7 8.7 2.7a2.41 2.41 0 0 0-3.4 0L2.7 5.3a2.41 2.41 0 0 0 0 3.4L7 13"></path><path d="m8 6 2-2"></path><path d="m18 16 2-2"></path><path d="m17 11 4.3 4.3c.94.94.94 2.46 0 3.4l-2.6 2.6c-.94.94-2.46.94-3.4 0L11 17"></path><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg><span class="absolute -right-2 -top-2 grid h-6 w-6 place-items-center rounded-full bg-emerald-600 text-[11px] font-black text-white"><?php echo esc_html( erfanpro_fa_digits( '2' ) ); ?></span></span>
						<span class="text-[13.5px] font-bold leading-6 text-slate-700"><?php esc_html_e( 'طراحی اختصاصی', 'erfanpro' ); ?></span>
					</div>
					<div class="relative flex flex-col items-center gap-3.5 text-center">
						<span class="relative z-10 grid h-14 w-14 place-items-center rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm transition-transform duration-500 hover:scale-110 hover:border-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench h-6 w-6 text-emerald-700" aria-hidden="true"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z"></path></svg><span class="absolute -right-2 -top-2 grid h-6 w-6 place-items-center rounded-full bg-emerald-600 text-[11px] font-black text-white"><?php echo esc_html( erfanpro_fa_digits( '3' ) ); ?></span></span>
						<span class="text-[13.5px] font-bold leading-6 text-slate-700"><?php esc_html_e( 'تولید با متریال درجه‌یک', 'erfanpro' ); ?></span>
					</div>
					<div class="relative flex flex-col items-center gap-3.5 text-center">
						<span class="relative z-10 grid h-14 w-14 place-items-center rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm transition-transform duration-500 hover:scale-110 hover:border-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hard-hat h-6 w-6 text-emerald-700" aria-hidden="true"><path d="M10 10V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5"></path><path d="M14 6a6 6 0 0 1 6 6v3"></path><path d="M4 15v-3a6 6 0 0 1 6-6"></path><rect x="2" y="15" width="20" height="4" rx="1"></rect></svg><span class="absolute -right-2 -top-2 grid h-6 w-6 place-items-center rounded-full bg-emerald-600 text-[11px] font-black text-white"><?php echo esc_html( erfanpro_fa_digits( '4' ) ); ?></span></span>
						<span class="text-[13.5px] font-bold leading-6 text-slate-700"><?php esc_html_e( 'نصب و اجرای حرفه‌ای', 'erfanpro' ); ?></span>
					</div>
					<div class="relative flex flex-col items-center gap-3.5 text-center">
						<span class="relative z-10 grid h-14 w-14 place-items-center rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm transition-transform duration-500 hover:scale-110 hover:border-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lightbulb h-6 w-6 text-emerald-700" aria-hidden="true"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path><path d="M9 18h6"></path><path d="M10 22h4"></path></svg><span class="absolute -right-2 -top-2 grid h-6 w-6 place-items-center rounded-full bg-emerald-600 text-[11px] font-black text-white"><?php echo esc_html( erfanpro_fa_digits( '5' ) ); ?></span></span>
						<span class="text-[13.5px] font-bold leading-6 text-slate-700"><?php esc_html_e( 'تحویل و پشتیبانی دائم', 'erfanpro' ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
