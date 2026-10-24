<?php
/**
 * Home — call to action (phase 3).
 *
 * Reference CTA card: heading column + contact cards column. Phone,
 * email and address come from the `erfanpro_options` keys (phase 0
 * fields); the reference values are the defaults.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_sec     = erfanpro_home_section( 'cta' );
$erfanpro_email   = erfanpro_option( 'email', 'info@erfansanat.com' );
$erfanpro_address = erfanpro_option( 'address', 'اصفهان، شهرستان خمینی‌شهر، شهرک صنعتی برق و الکترونیک، بلوار الکترونیک، فرعی دوم، پلاک ۱۱۹' );
?>
<section id="contact" class="relative scroll-mt-24 px-5 py-24 lg:px-8 lg:py-32">
	<div class="relative mx-auto max-w-7xl overflow-hidden rounded-[2.5rem] border border-emerald-200/70 bg-white shadow-[0_28px_70px_-32px_rgba(0,80,40,0.25)]">
		<div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
			<div class="absolute -left-24 -top-32 h-80 w-80 rounded-full bg-emerald-200/50 blur-[100px]"></div>
			<div class="absolute -bottom-32 right-0 h-80 w-80 rounded-full bg-teal-100/70 blur-[100px]"></div>
			<div class="light-dot-grid absolute inset-0 opacity-50"></div>
			<div class="absolute inset-0 bg-gradient-to-l from-transparent via-white/20 to-white/45"></div>
		</div>
		<div class="relative z-10 grid gap-12 p-8 sm:p-12 lg:grid-cols-2 lg:gap-8 lg:p-16">
			<div class="flex flex-col items-start gap-7">
				<div<?php erfanpro_reveal( 0.00 ); ?>>
					<span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 py-1.5 text-[13px] font-extrabold text-emerald-800 shadow-sm">
						<span class="relative flex h-2 w-2">
							<span class="absolute h-full w-full animate-ping rounded-full bg-emerald-500 opacity-70"></span>
							<span class="relative h-2 w-2 rounded-full bg-emerald-600"></span>
						</span><?php esc_html_e( 'مشاوره و استعلام قیمت رایگان', 'erfanpro' ); ?>
					</span>
				</div>
				<div<?php erfanpro_reveal( 0.07 ); ?>>
					<?php if ( '' !== $erfanpro_sec['title'] ) : ?>
					<h2 class="text-3xl font-black leading-[1.3] text-slate-950 lg:text-[2.8rem] lg:leading-[1.28]"><?php echo esc_html( $erfanpro_sec['title'] ); ?></h2>
					<?php else : ?>
					<h2 class="text-3xl font-black leading-[1.3] text-slate-950 lg:text-[2.8rem] lg:leading-[1.28]"><?php esc_html_e( 'روشنایی را به', 'erfanpro' ); ?> <span class="text-emerald-700"><?php esc_html_e( 'شهر خود', 'erfanpro' ); ?></span> <?php esc_html_e( 'دعوت کنید', 'erfanpro' ); ?></h2>
					<?php endif; ?>
				</div>
				<div<?php erfanpro_reveal( 0.14 ); ?>>
					<p class="max-w-lg text-[15px] font-medium leading-9 text-slate-600 lg:text-base lg:leading-10"><?php echo '' !== $erfanpro_sec['subtitle'] ? esc_html( $erfanpro_sec['subtitle'] ) : esc_html__( 'کارشناسان حرفه‌ای فروش پاسخگوی شما هستند؛ برای انتخاب بهترین المان نوری متناسب با پروژه‌تان، همین حالا تماس بگیرید. ارائه مشاوره رایگان، استعلام قیمت روز و راهنمایی کامل نصب و اجرا.', 'erfanpro' ); ?></p>
				</div>
				<div<?php erfanpro_reveal( 0.21, 'inview', null, 'opacity: 1; transform: none; filter: blur(0px);' ); ?>>
					<a href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>" class="group inline-flex items-center gap-3 rounded-full bg-emerald-700 px-9 py-4 text-base font-black text-white shadow-[0_14px_50px_-10px_rgba(0,125,45,0.7)] transition-all hover:bg-emerald-800"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-5 w-5 transition-transform duration-400 group-hover:rotate-12" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1 2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg><?php esc_html_e( 'همین الان تماس بگیرید', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4.5 w-4.5 transition-transform duration-400 group-hover:-translate-x-1" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
				</div>
			</div>
			<div class="flex flex-col justify-center gap-4">
				<div<?php erfanpro_reveal( 0.00 ); ?>>
					<div class="group flex items-center gap-5 rounded-3xl border border-emerald-100 bg-emerald-50/60 p-5 transition-all hover:border-emerald-300 hover:bg-emerald-50 lg:p-6">
						<span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl border border-emerald-200 bg-white text-emerald-700 transition-transform duration-500 group-hover:scale-110"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-5 w-5" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1 2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg></span>
						<div class="flex flex-col gap-0.5">
							<span class="text-[12.5px] font-medium text-slate-500"><?php esc_html_e( 'تماس مستقیم با کارشناسان فروش', 'erfanpro' ); ?></span>
							<a href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>" class="text-lg font-extrabold text-slate-900 transition-colors hover:text-emerald-700 lg:text-xl"><?php echo esc_html( erfanpro_phone_display() ); ?></a>
							<span class="text-[11.5px] text-slate-500"><?php esc_html_e( '۱۰ خط ویژه', 'erfanpro' ); ?></span>
						</div>
					</div>
				</div>
				<div<?php erfanpro_reveal( 0.07 ); ?>>
					<div class="group flex items-center gap-5 rounded-3xl border border-emerald-100 bg-emerald-50/60 p-5 transition-all hover:border-emerald-300 hover:bg-emerald-50 lg:p-6">
						<span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl border border-emerald-200 bg-white text-emerald-700 transition-transform duration-500 group-hover:scale-110"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail h-5 w-5" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path><rect x="2" y="4" width="20" height="16" rx="2"></rect></svg></span>
						<div class="flex flex-col gap-0.5">
							<span class="text-[12.5px] font-medium text-slate-500"><?php esc_html_e( 'ایمیل شرکت', 'erfanpro' ); ?></span>
							<a href="mailto:<?php echo esc_attr( $erfanpro_email ); ?>" dir="ltr" class="text-lg font-extrabold text-slate-900 transition-colors hover:text-emerald-700 lg:text-xl"><?php echo esc_html( $erfanpro_email ); ?></a>
							<span class="text-[11.5px] text-slate-500"><?php esc_html_e( 'پاسخگویی در سریع‌ترین زمان', 'erfanpro' ); ?></span>
						</div>
					</div>
				</div>
				<div<?php erfanpro_reveal( 0.14, 'inview', null, 'opacity: 1; transform: none; filter: blur(0px);' ); ?>>
					<div class="group flex items-center gap-5 rounded-3xl border border-emerald-100 bg-emerald-50/60 p-5 transition-all hover:border-emerald-300 hover:bg-emerald-50 lg:p-6">
						<span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl border border-emerald-200 bg-white text-emerald-700 transition-transform duration-500 group-hover:scale-110"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-3 h-5 w-5" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6h4"></path></svg></span>
						<div class="flex flex-col gap-0.5">
							<span class="text-[12.5px] font-medium text-slate-500"><?php esc_html_e( 'ساعات کاری', 'erfanpro' ); ?></span>
							<span class="text-lg font-extrabold text-slate-900 lg:text-xl"><?php esc_html_e( '۷ صبح تا ۴:۳۰ بعدازظهر', 'erfanpro' ); ?></span>
							<span class="text-[11.5px] text-slate-500"><?php esc_html_e( 'شنبه تا پنجشنبه', 'erfanpro' ); ?></span>
						</div>
					</div>
				</div>
				<div<?php erfanpro_reveal( 0.21, 'inview', null, 'opacity: 1; transform: none; filter: blur(0px);' ); ?>>
					<div class="flex items-start gap-4 rounded-3xl border border-emerald-200 bg-white p-5 lg:p-6"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mt-1 h-5 w-5 shrink-0 text-emerald-600" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
						<p class="text-[13.5px] leading-7 text-slate-600"><strong class="text-slate-900"><?php esc_html_e( 'نشانی:', 'erfanpro' ); ?></strong> <?php echo esc_html( $erfanpro_address ); ?><span class="mx-2 text-slate-300">|</span><?php esc_html_e( 'کدپستی:', 'erfanpro' ); ?> <?php echo esc_html( erfanpro_fa_digits( '8418148678' ) ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
