<?php
/**
 * Template Name: بازخورد مشتریان
 *
 * Phase 10: customer-feedback page — one of the simple blueprint
 * pages (plan §4 item 11, decision 28). No dedicated static
 * counterpart exists in main-erfansanat-static, and no testimonial
 * text is published in the blueprint — so this page invents no
 * named customer quotes. Instead it shows the company's own
 * published track record (the same counters as the about hero),
 * points to the executed-projects archive as the verifiable record,
 * and routes new feedback through the contact form. The page's own
 * editor content renders below via template-parts/page/content.php
 * so real feedback can be published there later.
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
			'title'    => __( 'بازخورد مشتریان', 'erfanpro' ),
			'subtitle' => __( 'نظر مشتریان، مهم‌ترین معیار کیفیت ماست؛ پروژه‌های اجراشده و تداوم همکاری با مشتریان، تصویر واقعی رضایت آن‌هاست.', 'erfanpro' ),
			'badge'    => __( 'صدای مشتری', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="grid gap-6 text-center sm:grid-cols-3">
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="text-[2rem] font-black text-emerald-700"><?php esc_html_e( '۸۵۰+', 'erfanpro' ); ?></span>
				<p class="mt-2 text-[13px] font-bold text-slate-500"><?php esc_html_e( 'پروژه تکمیل‌شده', 'erfanpro' ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="text-[2rem] font-black text-emerald-700"><?php esc_html_e( '۲۸۹+', 'erfanpro' ); ?></span>
				<p class="mt-2 text-[13px] font-bold text-slate-500"><?php esc_html_e( 'مشتری فعال', 'erfanpro' ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="text-[2rem] font-black text-emerald-700"><?php esc_html_e( '۲۵+', 'erfanpro' ); ?></span>
				<p class="mt-2 text-[13px] font-bold text-slate-500"><?php esc_html_e( 'سال تجربه درخشان', 'erfanpro' ); ?></p>
			</div>
		</div>
		<div class="mt-6 rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm lg:p-10">
			<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'نظر شما را می‌شنویم', 'erfanpro' ); ?></h2>
			<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'اگر مشتری عرفان صنعت هستید، تجربه‌تان از کیفیت محصول، نصب و خدمات پس از فروش را از طریق فرم تماس با ما در میان بگذارید؛ بازخوردهای دریافتی در بهبود تولید و خدمات به کار می‌رود.', 'erfanpro' ); ?></p>
			<p class="mt-6 flex flex-wrap gap-3">
				<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'ثبت بازخورد', 'erfanpro' ); ?></a>
				<a class="inline-flex items-center rounded-full border border-emerald-700 px-6 py-3 text-sm font-extrabold text-emerald-800 transition-colors hover:bg-emerald-50" href="<?php echo esc_url( get_post_type_archive_link( 'projects' ) ); ?>"><?php esc_html_e( 'مشاهده پروژه‌های اجراشده', 'erfanpro' ); ?></a>
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
