<?php
/**
 * Template Name: فرصت‌های شغلی
 *
 * Phase 10: careers page — one of the simple blueprint pages
 * (plan §4 item 11, decision 28). No dedicated static counterpart
 * exists in main-erfansanat-static and no live vacancy list is
 * published in the blueprint, so this page states the standing
 * hiring areas of a lighting manufacturer (engineering, production,
 * sales) and routes every application through the contact form —
 * it invents no specific vacancy, deadline, or salary. The page's
 * own editor content renders below via template-parts/page/content.php
 * so real vacancies can be published there later.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php
	get_template_part(
		'template-parts/page/hero',
		null,
		array(
			'title'    => __( 'فرصت‌های شغلی', 'erfanpro' ),
			'subtitle' => __( 'عرفان صنعت، تولیدکننده المان‌های نوری شهری و صنعتی در اصفهان، همواره از متخصصان علاقه‌مند به صنعت روشنایی استقبال می‌کند.', 'erfanpro' ),
			'badge'    => __( 'همکاری با ما', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="grid gap-6 md:grid-cols-3">
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'مهندسی و طراحی', 'erfanpro' ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'طراحی المان‌های نوری، محاسبات روشنایی و آماده‌سازی نقشه‌های اجرایی پروژه‌ها.', 'erfanpro' ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'تولید و اجرا', 'erfanpro' ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'مونتاژ، کنترل کیفیت، نصب و اجرای پروژه‌های نورپردازی در سراسر کشور.', 'erfanpro' ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'فروش و پشتیبانی', 'erfanpro' ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'مشاوره فنی به مشتریان، پیگیری استعلام‌ها و پشتیبانی پس از فروش.', 'erfanpro' ); ?></p>
			</div>
		</div>
		<div class="mt-6 rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm lg:p-10">
			<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'ارسال رزومه', 'erfanpro' ); ?></h2>
			<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'برای اعلام آمادگی همکاری، از طریق فرم تماس، حوزه تخصصی، سابقه کاری و راه ارتباطی خود را ارسال کنید؛ رزومه‌ها در بانک اطلاعاتی منابع انسانی شرکت ثبت و در زمان نیاز بررسی می‌شوند.', 'erfanpro' ); ?></p>
			<p class="mt-6">
				<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'ارسال درخواست همکاری', 'erfanpro' ); ?></a>
			</p>
		</div>
	</section>
	<?php
	get_template_part( 'template-parts/page/content' );
	get_template_part( 'template-parts/home/cta' );
	?>
</main>
<?php
get_footer();
