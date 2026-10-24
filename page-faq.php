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
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_faq_items = array(
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

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php
	get_template_part(
		'template-parts/page/hero',
		null,
		array(
			'title'    => __( 'سوالات متداول', 'erfanpro' ),
			'subtitle' => __( 'پاسخ پرسش‌های پرتکرار درباره سفارش، قیمت، گارانتی، ارسال و تولید سفارشی؛ اگر پاسخ سؤالتان را پیدا نکردید، از فرم تماس بپرسید.', 'erfanpro' ),
			'badge'    => __( 'راهنمای خرید', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="mx-auto max-w-3xl space-y-4">
			<?php foreach ( $erfanpro_faq_items as $erfanpro_faq_index => $erfanpro_faq_item ) : ?>
				<div class="overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white shadow-sm">
					<button type="button" class="erfanpro-faq-question flex w-full items-center justify-between gap-4 px-6 py-5 text-right text-[15px] font-extrabold text-slate-900" aria-expanded="false" aria-controls="erfanpro-faq-panel-<?php echo esc_attr( $erfanpro_faq_index ); ?>">
						<span><?php echo esc_html( $erfanpro_faq_item['q'] ); ?></span>
						<span aria-hidden="true" class="shrink-0 text-emerald-700">＋</span>
					</button>
					<div id="erfanpro-faq-panel-<?php echo esc_attr( $erfanpro_faq_index ); ?>" hidden class="px-6 pb-6 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_faq_item['a'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="mt-10 text-center">
			<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'سؤال دیگری دارم — تماس با ما', 'erfanpro' ); ?></a>
		</p>
	</section>
	<?php
	get_template_part( 'template-parts/page/content' );
	get_template_part( 'template-parts/home/cta' );
	?>
</main>
<?php
get_footer();
