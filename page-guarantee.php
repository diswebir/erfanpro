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
 * BO-014: texts from erfanpro_options[minor_pages_texts][guarantee].
 * Section manager: body section lives in template-parts/minor/guarantee-body.php.
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---- BO-014: guarantee texts (template fallbacks) ---- */
$erfanpro_page        = erfanpro_minor_page_texts( 'guarantee' );
$erfanpro_hero_title    = erfanpro_panel_text( $erfanpro_page, 'hero_title', __( 'شرایط گارانتی', 'erfanpro' ) );
$erfanpro_hero_subtitle = erfanpro_panel_text( $erfanpro_page, 'hero_subtitle', __( 'محصولات عرفان صنعت با گارانتی معتبر و خدمات پس از فروش سراسری عرضه می‌شوند؛ شرایط پوشش، مدت گارانتی و مراحل ثبت درخواست در این برگه آمده است.', 'erfanpro' ) );
$erfanpro_hero_badge    = erfanpro_panel_text( $erfanpro_page, 'hero_badge', __( 'خدمات پس از فروش', 'erfanpro' ) );
$erfanpro_cards         = erfanpro_panel_rows_merged(
	$erfanpro_page,
	'cards',
	array(
		array( 'title' => __( 'پوشش گارانتی', 'erfanpro' ), 'desc' => __( 'عیوب ناشی از مواد اولیه، مونتاژ و عملکرد قطعات الکترونیکی (درایور، چیپ LED و اتصالات) در مدت گارانتی، بدون هزینه تعمیر یا تعویض می‌شود.', 'erfanpro' ) ),
		array( 'title' => __( 'مدت گارانتی', 'erfanpro' ), 'desc' => __( 'مدت گارانتی هر محصول در برگه مشخصات همان محصول درج شده است؛ برای استعلام مدت گارانتی یک محصول، کد محصول را از طریق فرم تماس ارسال کنید.', 'erfanpro' ) ),
		array( 'title' => __( 'موارد خارج از گارانتی', 'erfanpro' ), 'desc' => __( 'آسیب ناشی از نصب غیراستاندارد، نوسان برق خارج از محدوده مجاز، دست‌کاری و بازشدن محصول توسط افراد غیرمجاز، و بلایای طبیعی از پوشش گارانتی خارج است.', 'erfanpro' ) ),
	)
);
$erfanpro_steps_title = erfanpro_panel_text( $erfanpro_page, 'steps_title', __( 'مراحل ثبت درخواست گارانتی', 'erfanpro' ) );
$erfanpro_steps       = erfanpro_panel_text_list(
	$erfanpro_page,
	'steps',
	array(
		__( 'از طریق برگه تماس، کد محصول و شرح ایراد را ارسال کنید.', 'erfanpro' ),
		__( 'کارشناسان خدمات پس از فروش، پس از بررسی، نحوه ارسال محصول را اعلام می‌کنند.', 'erfanpro' ),
		__( 'پس از تأیید کارشناسی، محصول تعمیر یا تعویض و بازگردانده می‌شود.', 'erfanpro' ),
	)
);
$erfanpro_cta = erfanpro_panel_text( $erfanpro_page, 'cta', __( 'ثبت درخواست از طریق تماس', 'erfanpro' ) );

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<?php
	/* Section manager: order/visibility from the theme options panel. */
	erfanpro_render_page_sections(
		'guarantee',
		array(
			'hero'    => array( 'part' => 'template-parts/page/hero', 'args' => array(
				'title'    => $erfanpro_hero_title,
				'subtitle' => $erfanpro_hero_subtitle,
				'badge'    => $erfanpro_hero_badge,
			) ),
			'body'    => array( 'part' => 'template-parts/minor/guarantee-body', 'args' => array(
				'cards' => $erfanpro_cards,
				'steps_title' => $erfanpro_steps_title,
				'steps' => $erfanpro_steps,
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
