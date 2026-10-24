<?php
/**
 * The global header — one-to-one with the static reference:
 * fixed bar, logo, flat desktop nav (the 'mega' menu location,
 * decision 19), phone pill (theme option, decision: phone default
 * 031-91091011), mobile hamburger. anim.js swaps the header classes
 * on scroll through the data-cls-top / data-cls-scrolled attributes,
 * exactly like the reference; main.js builds the animated mobile
 * panel from the hidden source nav (see mobile-source.php).
 *
 * Additions mandated by the locked decisions but absent from the
 * static reference markup: the language switcher (decision 9) and
 * the search toggle/form (plan §4 header includes search; AJAX is a
 * later phase) — both reuse reference classes only.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_hd = erfanpro_section_texts( 'header_texts' );

$erfanpro_header_top      = 'fixed inset-x-0 top-0 z-50 border-b bg-white/95 backdrop-blur-xl transition-all duration-500 border-transparent shadow-[0_1px_0_0_rgba(5,150,105,0.04)]';
$erfanpro_header_scrolled = 'fixed inset-x-0 top-0 z-50 border-b bg-white/95 backdrop-blur-xl transition-all duration-500 border-emerald-100 shadow-[0_8px_30px_-20px_rgba(0,168,62,.35)]';
?>
<header class="<?php echo esc_attr( $erfanpro_header_top ); ?>" style="opacity: 1; transform: none;" data-cls-top="<?php echo esc_attr( $erfanpro_header_top ); ?>" data-cls-scrolled="<?php echo esc_attr( $erfanpro_header_scrolled ); ?>">
	<?php get_template_part( 'template-parts/header/mobile-source' ); ?>
	<div class="mx-auto flex h-[4.75rem] max-w-7xl items-center justify-between gap-2 px-4 lg:px-8">
		<?php get_template_part( 'template-parts/header/logo', null, array( 'link_class' => 'group flex items-center' ) ); ?>
		<nav class="hidden items-center gap-0.5 xl:flex" aria-label="<?php echo esc_attr( erfanpro_panel_text( $erfanpro_hd, 'nav_aria', __( 'ناوبری اصلی', 'erfanpro' ) ) ); ?>">
			<?php erfanpro_render_main_nav(); ?>
		</nav>
		<div class="flex items-center gap-2">
			<?php get_template_part( 'template-parts/header/lang-switcher' ); ?>
			<button type="button" class="grid h-11 w-11 place-items-center rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800" aria-label="<?php echo esc_attr( erfanpro_panel_text( $erfanpro_hd, 'search_button', __( 'جستجو', 'erfanpro' ) ) ); ?>" aria-expanded="false" aria-controls="erfanpro-search-bar" data-erfanpro-search-toggle>
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-5 w-5" aria-hidden="true"><path d="m21 21-4.34-4.34"></path><circle cx="11" cy="11" r="8"></circle></svg>
			</button>
			<a href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>" class="group relative hidden items-center gap-2 overflow-hidden rounded-full bg-emerald-600 px-4 py-2.5 text-xs font-extrabold text-white shadow-sm transition-all duration-400 hover:bg-emerald-700 sm:flex">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-3.5 w-3.5 transition-transform duration-400 group-hover:rotate-12" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
				<span class="tracking-wide"><?php echo esc_html( erfanpro_phone_display() ); ?></span>
			</a>
			<button type="button" class="grid h-11 w-11 place-items-center rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 xl:hidden" aria-label="<?php echo esc_attr( erfanpro_panel_text( $erfanpro_hd, 'menu_aria', __( 'منو', 'erfanpro' ) ) ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu h-5 w-5" aria-hidden="true"><path d="M4 5h16"></path><path d="M4 12h16"></path><path d="M4 19h16"></path></svg>
			</button>
		</div>
	</div>
	<?php get_template_part( 'template-parts/header/search-bar' ); ?>
</header>
