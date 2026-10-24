<?php
/**
 * Template Name: سوالات متداول
 *
 * Phase 10: site FAQ page — one of the simple blueprint pages
 * (plan §4 item 11, decision 28). No dedicated static counterpart
 * exists in main-erfansanat-static; the questions below cover the
 * site-level topics (ordering, hybrid sales model, warranty,
 * delivery, custom projects) in the theme's own visual language.
 * Product-specific questions stay on each product page (phase 5
 * faq repeater). Accordion behaviour: assets/js/faq.js, enqueued
 * for this template in inc/enqueue.php. The page's own editor
 * content renders below via template-parts/page/content.php.
 *
 * BO-014: texts from erfanpro_options[minor_pages_texts][faq].
 * Section manager: body section lives in template-parts/minor/faq-body.php.
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---- BO-014: FAQ texts (template fallbacks) ---- */
$erfanpro_page        = erfanpro_minor_page_texts( 'faq' );
$erfanpro_hero_title    = erfanpro_panel_text( $erfanpro_page, 'hero_title', __( 'سوالات متداول', 'erfanpro' ) );
$erfanpro_hero_subtitle = erfanpro_panel_text( $erfanpro_page, 'hero_subtitle', __( 'پاسخ پرسش‌های پرتکرار درباره سفارش، قیمت، گارانتی، ارسال و تولید سفارشی؛ اگر پاسخ سؤالتان را پیدا نکردید، از فرم تماس بپرسید.', 'erfanpro' ) );
$erfanpro_hero_badge    = erfanpro_panel_text( $erfanpro_page, 'hero_badge', __( 'راهنمای خرید', 'erfanpro' ) );
$erfanpro_cta           = erfanpro_panel_text( $erfanpro_page, 'cta', __( 'سؤال دیگری دارم — تماس با ما', 'erfanpro' ) );

$erfanpro_faq_fallback = array(
	array(
		'q' => __( 'چطور سفارش ثبت کنم؟', 'erfanpro' ),
		'a' => __( 'محصولات دارای قیمت را می‌توانید مستقیم از فروشگاه به سبد خرید اضافه و تسویه کنید. برای محصولات بدون قیمت، از کارت استعلام همان محصول یا فرم تماس استفاده کنید تا کارشناسان فروش پیش‌فاکتور صادر کنند.', 'erfanpro' ),
	),
	array(
		'q' => __( 'چرا بعضی محصولات قیمت ندارند؟', 'erfanpro' ),
		'a' => __( 'قیمت این محصولات به ابعاد، تعداد و شرایط اجرای پروژه وابسته است؛ پس از ثبت استعلام، قیمت دقیق بر اساس مشخصات درخواستی شما اعلام می‌شود.', 'erfanpro' ),
	),
	array(
		'q' => __( 'محصولات چه گارانتی‌ای دارند؟', 'erfanpro' ),
		'a' => __( 'همه محصولات با گارانتی معتبر و خدمات پس از فروش عرضه می‌شوند؛ شرایط کامل پوشش در برگه «شرایط گارانتی» آمده و مدت گارانتی هر محصول در برگه همان محصول درج شده است.', 'erfanpro' ),
	),
	array(
		'q' => __( 'ارسال سفارش‌ها چطور انجام می‌شود؟', 'erfanpro' ),
		'a' => __( 'پس از نهایی‌شدن سفارش، زمان آماده‌سازی و ارسال بر اساس نوع و تعداد محصول اعلام می‌شود؛ برای پروژه‌های بزرگ، هماهنگی حمل و نصب با تیم اجرایی انجام می‌گیرد.', 'erfanpro' ),
	),
	array(
		'q' => __( 'آیا تولید سفارشی هم انجام می‌دهید؟', 'erfanpro' ),
		'a' => __( 'بله؛ عرفان صنعت طراح و تولیدکننده است و المان‌های نوری سفارشی برای پروژه‌های شهری و صنعتی را بر اساس نقشه و نیاز کارفرما تولید می‌کند. نمونه پروژه‌های اجراشده را در بخش پروژه‌ها ببینید.', 'erfanpro' ),
	),
	array(
		'q' => __( 'کاتالوگ محصولات را از کجا دانلود کنم؟', 'erfanpro' ),
		'a' => __( 'در برگه هر محصول، دکمه دانلود کاتالوگ همان محصول قرار دارد و اگر محصولی کاتالوگ اختصاصی نداشت، کاتالوگ کامل سایت جایگزین آن می‌شود.', 'erfanpro' ),
	),
);
$erfanpro_faq_items = erfanpro_panel_rows( $erfanpro_page, 'items' );
if ( empty( $erfanpro_faq_items ) ) {
	$erfanpro_faq_items = $erfanpro_faq_fallback;
}

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php
	/* Section manager: order/visibility from the theme options panel. */
	erfanpro_render_page_sections(
		'faq',
		array(
			'hero'    => array( 'part' => 'template-parts/page/hero', 'args' => array(
				'title'    => $erfanpro_hero_title,
				'subtitle' => $erfanpro_hero_subtitle,
				'badge'    => $erfanpro_hero_badge,
			) ),
			'body'    => array( 'part' => 'template-parts/minor/faq-body', 'args' => array(
				'items' => $erfanpro_faq_items,
				'cta' => $erfanpro_cta,
			) ),
			'content' => 'template-parts/page/content',
			'cta'     => 'template-parts/home/cta',
		)
	);
	?>
</main>
<?php
get_footer();
