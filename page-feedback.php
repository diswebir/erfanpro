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
 * BO-014: texts from erfanpro_options[minor_pages_texts][feedback].
 * Section manager: body section lives in template-parts/minor/feedback-body.php.
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---- BO-014: feedback texts (template fallbacks) ---- */
$erfanpro_page        = erfanpro_minor_page_texts( 'feedback' );
$erfanpro_hero_title    = erfanpro_panel_text( $erfanpro_page, 'hero_title', __( 'بازخورد مشتریان', 'erfanpro' ) );
$erfanpro_hero_subtitle = erfanpro_panel_text( $erfanpro_page, 'hero_subtitle', __( 'نظر مشتریان، مهم‌ترین معیار کیفیت ماست؛ پروژه‌های اجراشده و تداوم همکاری با مشتریان، تصویر واقعی رضایت آن‌هاست.', 'erfanpro' ) );
$erfanpro_hero_badge    = erfanpro_panel_text( $erfanpro_page, 'hero_badge', __( 'صدای مشتری', 'erfanpro' ) );
$erfanpro_counters      = erfanpro_panel_rows_merged(
	$erfanpro_page,
	'counters',
	array(
		array( 'value' => __( '۸۵۰+', 'erfanpro' ), 'label' => __( 'پروژه تکمیل‌شده', 'erfanpro' ) ),
		array( 'value' => __( '۲۸۹+', 'erfanpro' ), 'label' => __( 'مشتری فعال', 'erfanpro' ) ),
		array( 'value' => __( '۲۵+', 'erfanpro' ), 'label' => __( 'سال تجربه درخشان', 'erfanpro' ) ),
	)
);
$erfanpro_listen_title = erfanpro_panel_text( $erfanpro_page, 'listen_title', __( 'نظر شما را می‌شنویم', 'erfanpro' ) );
$erfanpro_listen_desc  = erfanpro_panel_text( $erfanpro_page, 'listen_desc', __( 'اگر مشتری عرفان صنعت هستید، تجربه‌تان از کیفیت محصول، نصب و خدمات پس از فروش را از طریق فرم تماس با ما در میان بگذارید؛ بازخوردهای دریافتی در بهبود تولید و خدمات به کار می‌رود.', 'erfanpro' ) );
$erfanpro_cta1         = erfanpro_panel_text( $erfanpro_page, 'cta1', __( 'ثبت بازخورد', 'erfanpro' ) );
$erfanpro_cta2         = erfanpro_panel_text( $erfanpro_page, 'cta2', __( 'مشاهده پروژه‌های اجراشده', 'erfanpro' ) );

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php
	/* Section manager: order/visibility from the theme options panel. */
	erfanpro_render_page_sections(
		'feedback',
		array(
			'hero'    => array( 'part' => 'template-parts/page/hero', 'args' => array(
				'title'    => $erfanpro_hero_title,
				'subtitle' => $erfanpro_hero_subtitle,
				'badge'    => $erfanpro_hero_badge,
			) ),
			'body'    => array( 'part' => 'template-parts/minor/feedback-body', 'args' => array(
				'counters' => $erfanpro_counters,
				'listen_title' => $erfanpro_listen_title,
				'listen_desc' => $erfanpro_listen_desc,
				'cta1' => $erfanpro_cta1,
				'cta2' => $erfanpro_cta2,
			) ),
			'content' => 'template-parts/page/content',
			'cta'     => 'template-parts/home/cta',
		)
	);
	?>
</main>
<?php
get_footer();
