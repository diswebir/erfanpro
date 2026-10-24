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
 * Section manager: body section lives in template-parts/minor/licenses-body.php.
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
	/* Section manager: order/visibility from the theme options panel. */
	erfanpro_render_page_sections(
		'licenses',
		array(
			'hero'    => array( 'part' => 'template-parts/page/hero', 'args' => array(
				'title'    => $erfanpro_hero_title,
				'subtitle' => $erfanpro_hero_subtitle,
				'badge'    => $erfanpro_hero_badge,
			) ),
			'body'    => array( 'part' => 'template-parts/minor/licenses-body', 'args' => array(
				'cards' => $erfanpro_cards,
				'bottom_title' => $erfanpro_bottom_title,
				'bottom_desc' => $erfanpro_bottom_desc,
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
