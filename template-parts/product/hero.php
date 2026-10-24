<?php
/**
 * Phase 5 — single product hero: breadcrumb, category badge, product
 * code, title, subtitle, spec chips and the contact CTAs.
 * Pixel clone of the reference product header.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* The assembler sets $erfanpro_product (WC_Product). */
$product    = isset( $args['erfanpro_product'] ) && $args['erfanpro_product'] instanceof WC_Product ? $args['erfanpro_product'] : null;
$product_id = $product ? $product->get_id() : get_queried_object_id();
$title      = $product ? $product->get_name() : get_the_title();

$cat_name = '';
$cat_link = '';
$cats     = get_the_terms( $product_id, 'product_cat' );
if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
	$cat_name = $cats[0]->name;
	$cat_link = get_term_link( $cats[0] );
	if ( is_wp_error( $cat_link ) ) {
		$cat_link = '';
	}
}

$code = (string) get_post_meta( $product_id, 'product_id', true );
if ( '' === $code ) {
	$code = (string) $product_id;
}

$subtitle = (string) get_post_meta( $product_id, 'zireonvan', true );
if ( '' === trim( $subtitle ) && $product ) {
	$subtitle = wp_strip_all_tags( (string) $product->get_short_description() );
}

$chips = erfanpro_product_spec_chips( $product_id );
?>
<section class="relative overflow-hidden border-b border-emerald-100 bg-[#f6f8f7] pb-12 pt-28 lg:pb-14 lg:pt-32">
	<div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
		<div class="absolute inset-0" style="background-image: radial-gradient(820px 400px at 85% 0%, rgba(0, 177, 64, 0.09), transparent 62%);"></div>
		<div class="light-dot-grid absolute inset-0 opacity-40"></div>
		<div class="absolute -left-24 top-10 h-64 w-64 rounded-full bg-emerald-200/40 blur-[100px]"></div>
	</div>
	<div class="relative z-10 mx-auto max-w-7xl px-5 lg:px-8">
		<nav aria-label="<?php esc_attr_e( 'مسیر صفحه', 'erfanpro' ); ?>" class="inline-flex max-w-full flex-wrap items-center gap-x-1.5 gap-y-1.5 rounded-2xl px-4 py-2.5 text-[12.5px] font-semibold backdrop-blur border border-emerald-100 bg-white text-slate-500 shadow-[0_8px_24px_-16px_rgba(5,150,105,0.35)]"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(10px);', 'opacity: 1; transform: none;' ); ?>>
			<a class="flex shrink-0 items-center gap-1.5 rounded-lg px-1.5 py-0.5 transition-colors hover:bg-emerald-50 hover:text-emerald-700" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="grid h-6 w-6 place-items-center rounded-lg bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house lucide-home h-3.5 w-3.5" aria-hidden="true"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></span><?php esc_html_e( 'صفحه اصلی', 'erfanpro' ); ?></a>
			<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-slate-300" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><a class="truncate rounded-lg px-1.5 py-0.5 transition-colors hover:bg-emerald-50 hover:text-emerald-700" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>"><?php esc_html_e( 'محصولات', 'erfanpro' ); ?></a></span>
			<?php if ( '' !== $cat_name ) : ?>
			<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-slate-300" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><?php if ( $cat_link ) : ?><a class="truncate rounded-lg px-1.5 py-0.5 transition-colors hover:bg-emerald-50 hover:text-emerald-700" href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $cat_name ); ?></a><?php else : ?><span class="truncate rounded-lg px-1.5 py-0.5"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?></span>
			<?php endif; ?>
			<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-slate-300" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><span class="max-w-[16rem] truncate rounded-lg px-2 py-0.5 font-extrabold bg-emerald-50 text-emerald-800"><?php echo esc_html( $title ); ?></span></span>
		</nav>
		<div class="pb-2 pt-8">
			<div class="mb-6 flex flex-wrap items-center gap-3"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(14px);', 'opacity: 1; transform: none;' ); ?>>
				<?php if ( '' !== $cat_name ) : ?>
				<a class="inline-flex items-center gap-2 rounded-full bg-emerald-700 px-4 py-1.5 text-[12px] font-black text-white shadow-[0_0_24px_rgba(0,125,45,0.35)] transition-transform hover:scale-105" href="<?php echo esc_url( $cat_link ? $cat_link : '#' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag h-3.5 w-3.5" aria-hidden="true"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"></path><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle></svg><?php echo esc_html( $cat_name ); ?></a>
				<?php endif; ?>
				<span class="rounded-full border border-slate-200 bg-white px-3.5 py-1.5 text-[11.5px] font-bold tracking-wide text-slate-500 shadow-xs"><?php esc_html_e( 'کد محصول:', 'erfanpro' ); ?> <?php echo esc_html( $code ); ?></span>
			</div>
			<div class="grid gap-10 lg:grid-cols-[1.15fr_0.85fr] lg:items-end lg:gap-14">
				<div>
					<h1 class="text-[2.05rem] font-black leading-[1.28] tracking-[-0.012em] text-slate-950 sm:text-[2.65rem] lg:text-[3.05rem] lg:leading-[1.22]"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(26px);', 'opacity: 1; transform: none;' ); ?>><?php echo esc_html( $title ); ?></h1>
					<?php if ( '' !== trim( $subtitle ) ) : ?>
					<p class="mt-5 max-w-2xl border-r-2 border-emerald-400/60 pr-5 text-[15px] font-medium leading-9 text-slate-600 lg:text-[16px]"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(20px);', 'opacity: 1; transform: none;' ); ?>><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
				<dl class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-emerald-200/70 bg-emerald-100 shadow-[0_20px_50px_-24px_rgba(0,80,40,0.18)]"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(20px);', 'opacity: 1; transform: none;' ); ?>>
					<?php foreach ( $chips as $chip ) : ?>
					<div class="bg-white px-4 py-4">
						<dt class="text-[11px] font-bold text-slate-400"><?php echo esc_html( $chip['label'] ); ?></dt>
						<dd class="mt-1 text-[13.5px] font-extrabold leading-6 text-slate-800"><?php echo esc_html( $chip['value'] ); ?></dd>
					</div>
					<?php endforeach; ?>
				</dl>
			</div>
			<div class="mt-9"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(16px);', 'opacity: 1; transform: none;' ); ?>>
				<div class="flex flex-wrap gap-3">
					<a href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>" class="flex items-center gap-2.5 rounded-2xl bg-amber-300 px-6 py-3.5 text-[14px] font-black text-emerald-950 shadow-[0_16px_40px_-12px_rgba(251,191,36,0.55)] transition-all hover:bg-amber-200"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-4.5 w-4.5" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1 2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg><?php esc_html_e( 'تماس برای استعلام قیمت', 'erfanpro' ); ?></a>
					<a href="<?php echo esc_url( erfanpro_whatsapp_url() ); ?>" target="_blank" rel="noreferrer" class="flex items-center gap-2.5 rounded-2xl border border-white/20 bg-white/[0.07] px-6 py-3.5 text-[14px] font-bold text-white backdrop-blur transition-colors hover:bg-white/[0.14]"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4.5 w-4.5" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg><?php esc_html_e( 'مشاوره در واتساپ', 'erfanpro' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>
