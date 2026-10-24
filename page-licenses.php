<?php
/**
 * Template Name: مجوزها و افتخارات
 *
 * Phase 10: licenses page — one of the simple blueprint pages
 * (plan §4 item 11, decision 28). No dedicated static counterpart
 * exists in main-erfansanat-static; the entries below reuse only
 * credentials already stated elsewhere in this theme (about hero:
 * ISO 9001:2015, knowledge-based company, founded 1378) and are
 * rendered as badge cards — no certificate images are invented.
 * The page's own editor content renders below via
 * template-parts/page/content.php.
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
			'title'    => __( 'مجوزها و افتخارات', 'erfanpro' ),
			'subtitle' => __( 'گواهینامه‌ها و افتخاراتی که پشتوانه کیفیت تولیدات عرفان صنعت است؛ از مدیریت کیفیت بین‌المللی تا جایگاه دانش‌بنیان.', 'erfanpro' ),
			'badge'    => __( 'اعتماد و کیفیت', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="grid gap-6 md:grid-cols-2">
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="inline-flex rounded-full bg-amber-100 px-4 py-1 text-[12px] font-extrabold text-amber-800"><?php esc_html_e( 'ISO 9001:2015', 'erfanpro' ); ?></span>
				<h2 class="mt-4 text-lg font-extrabold text-slate-900"><?php esc_html_e( 'مدیریت کیفیت بین‌المللی', 'erfanpro' ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'فرایندهای طراحی، تولید و کنترل کیفیت شرکت بر اساس استاندارد مدیریت کیفیت ISO 9001:2015 پیاده‌سازی و ممیزی می‌شوند.', 'erfanpro' ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="inline-flex rounded-full bg-emerald-100 px-4 py-1 text-[12px] font-extrabold text-emerald-800"><?php esc_html_e( 'شرکت دانش‌بنیان', 'erfanpro' ); ?></span>
				<h2 class="mt-4 text-lg font-extrabold text-slate-900"><?php esc_html_e( 'تأسیس ۱۳۷۸ — اصفهان', 'erfanpro' ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'عرفان صنعت اصفهان از سال ۱۳۷۸ در طراحی، تولید و اجرای پروژه‌های نورپردازی شهری و صنعتی فعالیت می‌کند و محصولاتش در صدها پروژه در سراسر کشور به کار رفته است.', 'erfanpro' ); ?></p>
			</div>
		</div>
		<div class="mt-6 rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm lg:p-10">
			<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'پروژه‌ها، گواه اعتماد', 'erfanpro' ); ?></h2>
			<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'فهرست پروژه‌های اجراشده، به‌همراه شهر و نوع پروژه، در بخش پروژه‌های سایت قابل مشاهده است و تصویر واقعی کیفیت تولیدات شرکت است.', 'erfanpro' ); ?></p>
			<p class="mt-6">
				<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( get_post_type_archive_link( 'projects' ) ); ?>"><?php esc_html_e( 'مشاهده پروژه‌ها', 'erfanpro' ); ?></a>
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
