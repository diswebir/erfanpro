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
 * BO-014: texts from erfanpro_options[minor_pages_texts][licenses].
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---- BO-014: licenses texts (template fallbacks) ---- */
$erfanpro_page        = erfanpro_minor_page_texts( 'licenses' );
$erfanpro_hero_title    = erfanpro_panel_text( $erfanpro_page, 'hero_title', __( 'مجوزها و افتخارات', 'erfanpro' ) );
$erfanpro_hero_subtitle = erfanpro_panel_text( $erfanpro_page, 'hero_subtitle', __( 'گواهینامه‌ها و افتخاراتی که پشتوانه کیفیت تولیدات عرفان صنعت است؛ از مدیریت کیفیت بین‌المللی تا جایگاه دانش‌بنیان.', 'erfanpro' ) );
$erfanpro_hero_badge    = erfanpro_panel_text( $erfanpro_page, 'hero_badge', __( 'اعتماد و کیفیت', 'erfanpro' ) );
$erfanpro_cards         = erfanpro_panel_rows_merged(
	$erfanpro_page,
	'cards',
	array(
		array( 'badge' => __( 'ISO 9001:2015', 'erfanpro' ), 'title' => __( 'مدیریت کیفیت بین‌المللی', 'erfanpro' ), 'desc' => __( 'فرایندهای طراحی، تولید و کنترل کیفیت شرکت بر اساس استاندارد مدیریت کیفیت ISO 9001:2015 پیاده‌سازی و ممیزی می‌شوند.', 'erfanpro' ) ),
		array( 'badge' => __( 'شرکت دانش‌بنیان', 'erfanpro' ), 'title' => __( 'تأسیس ۱۳۷۸ — اصفهان', 'erfanpro' ), 'desc' => __( 'عرفان صنعت اصفهان از سال ۱۳۷۸ در طراحی، تولید و اجرای پروژه‌های نورپردازی شهری و صنعتی فعالیت می‌کند و محصولاتش در صدها پروژه در سراسر کشور به کار رفته است.', 'erfanpro' ) ),
	)
);
$erfanpro_bottom_title = erfanpro_panel_text( $erfanpro_page, 'bottom_title', __( 'پروژه‌ها، گواه اعتماد', 'erfanpro' ) );
$erfanpro_bottom_desc  = erfanpro_panel_text( $erfanpro_page, 'bottom_desc', __( 'فهرست پروژه‌های اجراشده، به‌همراه شهر و نوع پروژه، در بخش پروژه‌های سایت قابل مشاهده است و تصویر واقعی کیفیت تولیدات شرکت است.', 'erfanpro' ) );
$erfanpro_cta          = erfanpro_panel_text( $erfanpro_page, 'cta', __( 'مشاهده پروژه‌ها', 'erfanpro' ) );

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php
	get_template_part(
		'template-parts/page/hero',
		null,
		array(
			'title'    => $erfanpro_hero_title,
			'subtitle' => $erfanpro_hero_subtitle,
			'badge'    => $erfanpro_hero_badge,
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="grid gap-6 md:grid-cols-2">
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="inline-flex rounded-full bg-amber-100 px-4 py-1 text-[12px] font-extrabold text-amber-800"><?php echo esc_html( $erfanpro_cards[0]['badge'] ); ?></span>
				<h2 class="mt-4 text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_cards[0]['title'] ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_cards[0]['desc'] ); ?></p>
			</div>
			<div class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm">
				<span class="inline-flex rounded-full bg-emerald-100 px-4 py-1 text-[12px] font-extrabold text-emerald-800"><?php echo esc_html( $erfanpro_cards[1]['badge'] ); ?></span>
				<h2 class="mt-4 text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_cards[1]['title'] ); ?></h2>
				<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_cards[1]['desc'] ); ?></p>
			</div>
		</div>
		<div class="mt-6 rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm lg:p-10">
			<h2 class="text-lg font-extrabold text-slate-900"><?php echo esc_html( $erfanpro_bottom_title ); ?></h2>
			<p class="mt-3 text-[15px] leading-9 text-slate-600"><?php echo esc_html( $erfanpro_bottom_desc ); ?></p>
			<p class="mt-6">
				<a class="inline-flex items-center rounded-full bg-emerald-700 px-6 py-3 text-sm font-extrabold text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( get_post_type_archive_link( 'projects' ) ); ?>"><?php echo esc_html( $erfanpro_cta ); ?></a>
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
