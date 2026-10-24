<?php
/**
 * 404 — phase 9 (project decision 30).
 *
 * Same visual language as the reference heroes (dark emerald, dot
 * grid, amber accents): giant 404, a plain-language explanation, a
 * search form, and shortcuts to the sections visitors actually want.
 * The header search bar stays the standard GET form (?s=).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_nf = erfanpro_section_texts( 'not_found_texts' );

get_header();
?>
<main class="min-h-screen bg-[#f8faf9]">
	<section class="relative bg-emerald-950 pt-28 lg:pt-36">
		<div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden"><div class="absolute inset-0 bg-[radial-gradient(1100px_520px_at_50%_-10%,rgba(16,185,129,0.22),transparent_60%)]"></div><div class="absolute inset-0 opacity-70" style="background-image: radial-gradient(rgba(255, 255, 255, 0.5) 1px, transparent 1.4px); background-size: 90px 90px; mask-image: radial-gradient(1000px 560px at 50% 20%, black, transparent 78%);"></div><span class="absolute -bottom-10 left-0 select-none whitespace-nowrap text-[10rem] font-black leading-none text-white/[0.03] lg:text-[14rem]">۴۰۴</span></div>
		<div class="relative z-10 mx-auto max-w-7xl px-5 pb-20 pt-14 text-center lg:px-8 lg:pb-24">
			<p class="bg-gradient-to-b from-amber-200 to-amber-400 bg-clip-text text-[6rem] font-black leading-none text-transparent sm:text-[8rem] lg:text-[10rem]">۴۰۴</p>
			<h1 class="mt-6 text-[2rem] font-black leading-[1.3] text-white sm:text-[2.6rem]"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'title', __( 'صفحه پیدا نشد', 'erfanpro' ) ) ); ?></h1>
			<p class="mx-auto mt-4 max-w-xl text-[15px] font-medium leading-9 text-white/70"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'desc', __( 'صفحه‌ای که دنبالش بودید وجود ندارد، منتقل شده یا نشانی آن اشتباه وارد شده است. از جستجو یا میانبرهای زیر استفاده کنید.', 'erfanpro' ) ) ); ?></p>
			<div class="mx-auto mt-8 max-w-lg">
				<?php get_search_form(); ?>
			</div>
			<div class="mt-10 flex flex-wrap justify-center gap-3">
				<a class="rounded-full bg-amber-300 px-7 py-3.5 text-[14.5px] font-black text-emerald-950 shadow-[0_16px_40px_-12px_rgba(251,191,36,0.55)] transition-all hover:bg-amber-200" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'home_button', __( 'بازگشت به صفحه اصلی', 'erfanpro' ) ) ); ?></a>
				<a class="rounded-full border border-white/20 bg-white/[0.07] px-7 py-3.5 text-[14.5px] font-bold text-white backdrop-blur transition-all hover:bg-white/[0.14]" href="<?php echo esc_url( erfanpro_archive_url( 'product' ) ); ?>"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'shop_button', __( 'فروشگاه', 'erfanpro' ) ) ); ?></a>
				<a class="rounded-full border border-white/20 bg-white/[0.07] px-7 py-3.5 text-[14.5px] font-bold text-white backdrop-blur transition-all hover:bg-white/[0.14]" href="<?php echo esc_url( erfanpro_contact_url() ); ?>"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'contact_button', __( 'تماس با ما', 'erfanpro' ) ) ); ?></a>
			</div>
		</div>
	</section>

	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
			<a class="card-light flex flex-col gap-2 rounded-3xl p-7 transition-all hover:border-emerald-300" href="<?php echo esc_url( erfanpro_archive_url( 'product' ) ); ?>">
				<span class="text-lg font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'card1_title', __( 'محصولات', 'erfanpro' ) ) ); ?></span>
				<span class="text-[13.5px] leading-7 text-slate-600"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'card1_desc', __( 'المان‌های نوری و تجهیزات روشنایی شهری', 'erfanpro' ) ) ); ?></span>
			</a>
			<a class="card-light flex flex-col gap-2 rounded-3xl p-7 transition-all hover:border-emerald-300" href="<?php echo esc_url( erfanpro_archive_url( 'projects' ) ); ?>">
				<span class="text-lg font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'card2_title', __( 'پروژه‌ها', 'erfanpro' ) ) ); ?></span>
				<span class="text-[13.5px] leading-7 text-slate-600"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'card2_desc', __( 'پروژه‌های اجرا شده در سراسر ایران', 'erfanpro' ) ) ); ?></span>
			</a>
			<a class="card-light flex flex-col gap-2 rounded-3xl p-7 transition-all hover:border-emerald-300" href="<?php echo esc_url( erfanpro_archive_url( 'services' ) ); ?>">
				<span class="text-lg font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'card3_title', __( 'خدمات', 'erfanpro' ) ) ); ?></span>
				<span class="text-[13.5px] leading-7 text-slate-600"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'card3_desc', __( 'طراحی، تولید و اجرای نورپردازی', 'erfanpro' ) ) ); ?></span>
			</a>
			<a class="card-light flex flex-col gap-2 rounded-3xl p-7 transition-all hover:border-emerald-300" href="<?php echo esc_url( erfanpro_page_url( 'about' ) ); ?>">
				<span class="text-lg font-extrabold text-slate-900"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'card4_title', __( 'درباره ما', 'erfanpro' ) ) ); ?></span>
				<span class="text-[13.5px] leading-7 text-slate-600"><?php echo esc_html( erfanpro_panel_text( $erfanpro_nf, 'card4_desc', __( 'بیش از دو دهه پیشگامی در صنعت روشنایی', 'erfanpro' ) ) ); ?></span>
			</a>
		</div>
	</section>
</main>
<?php
get_footer();
