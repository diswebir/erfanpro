<?php
/**
 * Template Name: شرایط گارانتی
 *
 * Phase 10: guarantee page — one of the simple blueprint pages
 * (plan §4 item 11, decision 28). No dedicated static counterpart
 * exists in main-erfansanat-static, so the sections below are built
 * from the blueprint description in the theme's own visual language:
 * emerald hero, white cards on #f8faf9, amber accents. The page's
 * own editor content, when present, renders below via
 * template-parts/page/content.php so the wording stays editable.
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
			'title'    => __( 'شرایط گارانتی', 'erfanpro' ),
			'subtitle' => __( 'محصولات عرفان صنعت با گارانتی معتبر و خدمات پس از فروش سراسری عرضه می‌شوند؛ شرایط پوشش، مدت گارانتی و مراحل ثبت درخواست در این برگه آمده است.', 'erfanpro' ),
			'badge'    => __( 'خدمات پس از فروش', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="grid gap-6 md:grid-cols-3">
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'پوشش گارانتی', 'erfanpro' ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'عیوب ناشی از مواد اولیه، مونتاژ و عملکرد قطعات الکترونیکی (درایور، چیپ LED و اتصالات) در مدت گارانتی، بدون هزینه تعمیر یا تعویض می‌شود.', 'erfanpro' ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'مدت گارانتی', 'erfanpro' ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'مدت گارانتی هر محصول در برگه مشخصات همان محصول درج شده است؛ برای استعلام مدت گارانتی یک محصول، کد محصول را از طریق فرم تماس ارسال کنید.', 'erfanpro' ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'موارد خارج از گارانتی', 'erfanpro' ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php esc_html_e( 'آسیب ناشی از نصب غیراستاندارد، نوسان برق خارج از محدوده مجاز، دست‌کاری و بازشدن محصول توسط افراد غیرمجاز، و بلایای طبیعی از پوشش گارانتی خارج است.', 'erfanpro' ); ?></p>
			</div>
		</div>
		<div class="mt-6 rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm lg:p-10">
			<h2 class="text-lg font-extrabold text-slate-900"><?php esc_html_e( 'مراحل ثبت درخواست گارانتی', 'erfanpro' ); ?></h2>
			<ol class="mt-4 list-decimal space-y-2 pr-5 text-[15px] leading-9 text-slate-600">
				<li><?php esc_html_e( 'از طریق برگه تماس، کد محصول و شرح ایراد را ارسال کنید.', 'erfanpro' ); ?></li>
				<li><?php esc_html_e( 'کارشناسان خدمات پس از فروش، پس از بررسی، نحوه ارسال محصول را اعلام می‌کنند.', 'erfanpro' ); ?></li>
				<li><?php esc_html_e( 'پس از تأیید کارشناسی، محصول تعمیر یا تعویض و بازگردانده می‌شود.', 'erfanpro' ); ?></li>
			</ol>
			<p class="mt-6">
				<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'ثبت درخواست از طریق تماس', 'erfanpro' ); ?></a>
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
