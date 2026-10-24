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
 * BO-014: texts from erfanpro_options[minor_pages_texts][careers].
 * Section manager: body section lives in template-parts/minor/careers-body.php.
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---- BO-014: careers texts (template fallbacks) ---- */
$erfanpro_page        = erfanpro_minor_page_texts( 'careers' );
$erfanpro_hero_title    = erfanpro_panel_text( $erfanpro_page, 'hero_title', __( 'فرصت‌های شغلی', 'erfanpro' ) );
$erfanpro_hero_subtitle = erfanpro_panel_text( $erfanpro_page, 'hero_subtitle', __( 'عرفان صنعت، تولیدکننده المان‌های نوری شهری و صنعتی در اصفهان، همواره از متخصصان علاقه‌مند به صنعت روشنایی استقبال می‌کند.', 'erfanpro' ) );
$erfanpro_hero_badge    = erfanpro_panel_text( $erfanpro_page, 'hero_badge', __( 'همکاری با ما', 'erfanpro' ) );
$erfanpro_areas         = erfanpro_panel_rows_merged(
	$erfanpro_page,
	'areas',
	array(
		array( 'title' => __( 'مهندسی و طراحی', 'erfanpro' ), 'desc' => __( 'طراحی المان‌های نوری، محاسبات روشنایی و آماده‌سازی نقشه‌های اجرایی پروژه‌ها.', 'erfanpro' ) ),
		array( 'title' => __( 'تولید و اجرا', 'erfanpro' ), 'desc' => __( 'مونتاژ، کنترل کیفیت، نصب و اجرای پروژه‌های نورپردازی در سراسر کشور.', 'erfanpro' ) ),
		array( 'title' => __( 'فروش و پشتیبانی', 'erfanpro' ), 'desc' => __( 'مشاوره فنی به مشتریان، پیگیری استعلام‌ها و پشتیبانی پس از فروش.', 'erfanpro' ) ),
	)
);
$erfanpro_resume_title = erfanpro_panel_text( $erfanpro_page, 'resume_title', __( 'ارسال رزومه', 'erfanpro' ) );
$erfanpro_resume_desc  = erfanpro_panel_text( $erfanpro_page, 'resume_desc', __( 'برای اعلام آمادگی همکاری، از طریق فرم تماس، حوزه تخصصی، سابقه کاری و راه ارتباطی خود را ارسال کنید؛ رزومه‌ها در بانک اطلاعاتی منابع انسانی شرکت ثبت و در زمان نیاز بررسی می‌شوند.', 'erfanpro' ) );
$erfanpro_cta          = erfanpro_panel_text( $erfanpro_page, 'cta', __( 'ارسال درخواست همکاری', 'erfanpro' ) );

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php
	/* Section manager: order/visibility from the theme options panel. */
	erfanpro_render_page_sections(
		'careers',
		array(
			'hero'    => array( 'part' => 'template-parts/page/hero', 'args' => array(
				'title'    => $erfanpro_hero_title,
				'subtitle' => $erfanpro_hero_subtitle,
				'badge'    => $erfanpro_hero_badge,
			) ),
			'body'    => array( 'part' => 'template-parts/minor/careers-body', 'args' => array(
				'areas' => $erfanpro_areas,
				'resume_title' => $erfanpro_resume_title,
				'resume_desc' => $erfanpro_resume_desc,
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
