<?php
/**
 * Phase 4: shop archive hero — pixel-faithful to products.html.
 *
 * Breadcrumb, factory badge, h1, subtitle, category pills (drives the
 * AJAX filter), feature chips, and the hero image card. Categories come
 * from the live `product_cat` taxonomy; the reference names are the
 * fallback when the taxonomy is empty.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_shop_cats = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
if ( is_wp_error( $erfanpro_shop_cats ) || empty( $erfanpro_shop_cats ) ) {
	$erfanpro_shop_cats = array();
	foreach ( array( 'درختان نوری هوشمند', 'المان‌های نوری خورشیدنما', 'لوسترهای دکوراتیو فضای باز', 'ریسه‌های LED', 'پوینت لایت پیکسل LED', 'والواشر نورپردازی', 'المان‌های نوری شهری' ) as $fallback_name ) {
		$term        = new stdClass();
		$term->slug = '';
		$term->name = $fallback_name;
		$erfanpro_shop_cats[] = $term;
	}
}

$erfanpro_hero_pill_active   = 'rounded-xl px-3.5 py-2 text-[12.5px] font-bold transition-all bg-emerald-700 text-white shadow-md shadow-emerald-700/25';
$erfanpro_hero_pill_inactive = 'rounded-xl px-3.5 py-2 text-[12.5px] font-bold transition-all border border-slate-200 bg-slate-50 text-slate-600 hover:border-emerald-400 hover:text-emerald-700';

/* On a product-category archive, the matching pill starts active. */
$erfanpro_hero_cat = '';
if ( function_exists( 'is_product_category' ) && is_product_category() ) {
	$erfanpro_qo = get_queried_object();
	if ( $erfanpro_qo instanceof WP_Term ) {
		$erfanpro_hero_cat = $erfanpro_qo->slug;
	}
}
?>
<section class="relative overflow-hidden bg-[#f6f8f7] pb-12 pt-28 lg:pb-16 lg:pt-36">
	<div aria-hidden="true" class="pointer-events-none absolute inset-0"><div class="absolute inset-0" style="background-image: radial-gradient(1000px 500px at 78% -6%, rgba(0, 177, 64, 0.1), transparent 60%);"></div><div class="light-dot-grid absolute inset-0 opacity-50"></div><div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-emerald-200/50 blur-[110px]"></div><div class="absolute -left-28 bottom-10 h-72 w-72 rounded-full bg-teal-100/60 blur-[110px]"></div><div class="absolute left-[10%] top-0 h-full w-px bg-gradient-to-b from-transparent via-emerald-300/25 to-transparent"></div></div>
	<div class="relative z-10 mx-auto max-w-7xl px-5 lg:px-8">
		<nav aria-label="<?php esc_attr_e( 'مسیر صفحه', 'erfanpro' ); ?>" class="inline-flex max-w-full flex-wrap items-center gap-x-1.5 gap-y-1.5 rounded-2xl px-4 py-2.5 text-[12.5px] font-semibold backdrop-blur border border-emerald-100 bg-white text-slate-500 shadow-[0_8px_24px_-16px_rgba(5,150,105,0.35)]"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(10px);', 'opacity: 1; transform: none;' ); ?>>
			<a class="flex shrink-0 items-center gap-1.5 rounded-lg px-1.5 py-0.5 transition-colors hover:bg-emerald-50 hover:text-emerald-700" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="grid h-6 w-6 place-items-center rounded-lg bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house lucide-home h-3.5 w-3.5" aria-hidden="true"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></span><?php esc_html_e( 'صفحه اصلی', 'erfanpro' ); ?></a>
			<span class="flex min-w-0 items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-3.5 w-3.5 shrink-0 text-slate-300" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg><span class="max-w-[16rem] truncate rounded-lg px-2 py-0.5 font-extrabold bg-emerald-50 text-emerald-800"><?php esc_html_e( 'محصولات', 'erfanpro' ); ?></span></span>
		</nav>
		<div class="grid items-center gap-12 pb-8 pt-10 lg:grid-cols-[1.02fr_0.98fr] lg:gap-14 lg:pb-12 lg:pt-12">
			<div>
				<div class="mb-6 inline-flex items-center gap-2.5 rounded-full border border-emerald-200 bg-white py-1.5 pl-5 pr-1.5 shadow-sm"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(14px);', 'opacity: 1; transform: none;' ); ?>>
					<span class="grid h-8 w-8 place-items-center rounded-full bg-emerald-600 text-white shadow-md shadow-emerald-600/25"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-4 w-4" aria-hidden="true"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path><path d="M15 18H9"></path><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path><circle cx="17" cy="18" r="2"></circle><circle cx="7" cy="18" r="2"></circle></svg></span>
					<span class="text-[13px] font-extrabold text-emerald-800"><?php esc_html_e( 'تولیدات کارخانه عرفان صنعت', 'erfanpro' ); ?></span>
				</div>
				<h1 class="text-[2.5rem] font-black leading-[1.25] tracking-[-0.015em] text-slate-950 sm:text-[3.3rem] lg:text-[3.9rem] lg:leading-[1.18]"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(28px);', 'opacity: 1; transform: none;' ); ?>><?php esc_html_e( 'محصولات', 'erfanpro' ); ?><br><span class="bg-gradient-to-l from-emerald-800 via-emerald-600 to-teal-700 bg-clip-text text-transparent"><?php esc_html_e( 'نورپردازی شهری', 'erfanpro' ); ?></span></h1>
				<p class="mt-6 max-w-[34rem] text-[15.5px] font-medium leading-9 text-slate-600 lg:text-[16.5px]"<?php erfanpro_reveal( 0.18, 'mount', 'opacity: 0; transform: translateY(22px);', 'opacity: 1; transform: none;' ); ?>><?php esc_html_e( 'مجموعه‌ای کامل از تجهیزات نورپردازی پیشرفته؛ از پوینت لایت پیکسل و ریسه‌های LED تا درختان نوری هوشمند و خورشیدنماهای ۳۲ و ۴۸ پره', 'erfanpro' ); ?></p>
				<div class="mt-8 rounded-3xl border border-emerald-100 bg-white p-4 shadow-[0_20px_50px_-24px_rgba(0,80,40,0.18)]"<?php erfanpro_reveal( 0.27, 'mount', 'opacity: 0; transform: translateY(16px);', 'opacity: 1; transform: none;' ); ?>>
					<div class="mb-3 flex items-center justify-between px-1 text-[12px] font-extrabold tracking-wide text-slate-500">
						<span><?php esc_html_e( 'دسته‌بندی محصولات:', 'erfanpro' ); ?></span>
					</div>
					<div class="flex flex-wrap gap-2" data-shop-hero-pills>
						<button type="button" data-shop-cat="" class="<?php echo esc_attr( '' === $erfanpro_hero_cat ? $erfanpro_hero_pill_active : $erfanpro_hero_pill_inactive ); ?>"><?php esc_html_e( 'همه دسته‌ها', 'erfanpro' ); ?></button>
						<?php foreach ( $erfanpro_shop_cats as $term ) : ?>
						<button type="button" data-shop-cat="<?php echo esc_attr( $term->slug ); ?>" class="<?php echo esc_attr( '' !== $term->slug && $term->slug === $erfanpro_hero_cat ? $erfanpro_hero_pill_active : $erfanpro_hero_pill_inactive ); ?>"><?php echo esc_html( $term->name ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4"<?php erfanpro_reveal( 0.36, 'mount', 'opacity: 0; transform: translateY(16px);', 'opacity: 1; transform: none;' ); ?>>
					<?php
					$erfanpro_chips = array( 'استاندارد IP67 / IP68', 'چیپ LED اپیستار', '۲۴ ماه گارانتی تعویض', '۵ سال خدمات پس از فروش' );
					foreach ( $erfanpro_chips as $chip ) :
					?>
					<div class="flex items-center gap-2.5 rounded-2xl border border-emerald-100 bg-white px-3 py-2.5 shadow-xs">
						<span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check h-4 w-4" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path></svg></span>
						<span class="text-[11.5px] font-extrabold leading-4 text-slate-700"><?php esc_html_e( $chip, 'erfanpro' ); ?></span>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="relative mx-auto w-full max-w-[30rem]"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(36px) rotate(2deg);', 'opacity: 1; transform: rotate(2deg);' ); ?>>
				<div aria-hidden="true" class="absolute left-1/2 top-1/2 h-[70%] w-[70%] -translate-x-1/2 -translate-y-1/2 rounded-full bg-emerald-200/50 blur-[80px]"></div>
				<div class="relative overflow-hidden rounded-[2.5rem] border-4 border-white shadow-[0_40px_80px_-30px_rgba(0,80,40,0.35)]">
					<img alt="" class="h-[22rem] w-full object-cover sm:h-[25rem] lg:h-[28rem]" src="<?php echo esc_url( ERFANPRO_URI . '/assets/img/light-tree.jpg' ); ?>">
					<div class="absolute inset-0 bg-gradient-to-t from-emerald-950/45 via-transparent to-transparent"></div>
					<div class="absolute bottom-4 inset-x-4 flex items-center justify-between rounded-2xl border border-white/50 bg-white/85 px-4 py-3 backdrop-blur-md">
						<div class="flex flex-col">
							<span class="text-[11px] font-bold text-slate-500"><?php esc_html_e( 'پیشنهاد ویژه کارخانه', 'erfanpro' ); ?></span>
							<span class="text-[14px] font-black text-slate-900"><?php esc_html_e( 'استعلام قیمت روز + مشاوره رایگان', 'erfanpro' ); ?></span>
						</div>
						<a class="rounded-xl bg-emerald-700 px-3.5 py-2 text-[12.5px] font-black text-white shadow-lg" href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>"><?php esc_html_e( 'تماس بگیرید', 'erfanpro' ); ?></a>
					</div>
				</div>
				<span class="absolute -top-4 right-8 flex items-center gap-2 rounded-2xl border border-emerald-100 bg-white px-4 py-2.5 shadow-xl"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: scale(0.85);', 'opacity: 1; transform: none;' ); ?>>
					<span class="relative flex h-2.5 w-2.5">
						<span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-70"></span>
						<span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
					</span>
					<span class="text-[12.5px] font-extrabold text-slate-900"><?php esc_html_e( 'موجود در انبار کارخانه', 'erfanpro' ); ?></span>
				</span>
				<span class="absolute -bottom-5 left-6 flex items-center gap-2.5 rounded-2xl bg-emerald-800 px-4 py-3 text-white shadow-[0_20px_40px_-16px_rgba(0,80,40,0.5)]"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: scale(0.85) rotate(-12deg);', 'opacity: 1; transform: rotate(-8deg);' ); ?>>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check lucide-verified h-5 w-5 text-amber-300" aria-hidden="true"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"></path><path d="m16 9-5.5 5.5L8 12"></path></svg>
					<span class="flex flex-col leading-tight">
						<span class="text-[13px] font-black"><?php esc_html_e( '۲۴ ماه گارانتی', 'erfanpro' ); ?></span>
						<span class="text-[10.5px] font-medium text-emerald-200"><?php esc_html_e( '۵ سال خدمات پس از فروش', 'erfanpro' ); ?></span>
					</span>
				</span>
			</div>
		</div>
	</div>
	<div aria-hidden="true" class="relative z-10 -mb-px h-10 rounded-t-[2.5rem] bg-white sm:h-12"></div>
</section>
