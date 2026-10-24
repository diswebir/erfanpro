<?php
/**
 * Home — hero (phase 3).
 *
 * Pixel clone of the static reference hero: parallax background
 * (data-plx-bg / data-plx-copy, runtime in anim.js), brand mark, badge,
 * headline, CTAs, the WooCommerce featured-product card (decision 21)
 * and the counters strip (decision 22: real value + admin offset via
 * the `home_counters` option; reference statics as design fallback).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---- Featured product card (D21: WooCommerce featured product) ---- */
$erfanpro_fp          = null;
$erfanpro_fp_image    = '';
$erfanpro_fp_cat      = '';
$erfanpro_fp_title    = '';
$erfanpro_fp_url      = '#';
$erfanpro_fp_specs    = array();
$erfanpro_fp_has_data = false;

if ( function_exists( 'wc_get_products' ) ) {
	$fp_ids = wc_get_products(
		array(
			'featured' => true,
			'limit'    => 1,
			'status'   => 'publish',
			'return'   => 'ids',
		)
	);
	if ( ! empty( $fp_ids ) ) {
		$erfanpro_fp = wc_get_product( $fp_ids[0] );
	}
}

if ( $erfanpro_fp instanceof WC_Product ) {
	$erfanpro_fp_has_data = true;
	$erfanpro_fp_title    = $erfanpro_fp->get_name();
	$erfanpro_fp_url      = $erfanpro_fp->get_permalink();
	$img_id               = $erfanpro_fp->get_image_id();
	if ( $img_id ) {
		$erfanpro_fp_image = wp_get_attachment_image_url( $img_id, 'large' );
	}
	$cats = wc_get_product_term_ids( $erfanpro_fp->get_id(), 'product_cat' );
	if ( ! empty( $cats ) ) {
		$term = get_term( $cats[0], 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$erfanpro_fp_cat = $term->name;
		}
	}
	foreach ( $erfanpro_fp->get_attributes() as $attr ) {
		$label = wc_attribute_label( $attr->get_name() );
		if ( $attr->is_taxonomy() ) {
			$values = wc_get_product_terms( $erfanpro_fp->get_id(), $attr->get_name(), array( 'fields' => 'names' ) );
			$values = is_array( $values ) ? $values : array();
		} else {
			$values = $attr->get_options();
		}
		if ( $values ) {
			$erfanpro_fp_specs[] = array( $label, implode( '، ', array_slice( $values, 0, 3 ) ) );
		}
		if ( count( $erfanpro_fp_specs ) >= 4 ) {
			break;
		}
	}
}

/* Reference design fallback when no featured product exists. */
if ( ! $erfanpro_fp_image ) {
	$erfanpro_fp_image = ERFANPRO_URI . '/assets/img/light-tree.jpg';
}
if ( '' === $erfanpro_fp_cat ) {
	$erfanpro_fp_cat = 'المان نوری هوشمند';
}
if ( '' === $erfanpro_fp_title ) {
	$erfanpro_fp_title = 'درخت نوری آرتام · ۱۷۲۸ پیکسل';
}
if ( count( $erfanpro_fp_specs ) < 4 ) {
	$erfanpro_fp_specs = array(
		array( 'رنگ', '۱۶ میلیون' ),
		array( 'کنترل', 'WiFi / NRF' ),
		array( 'استاندارد', 'IP67' ),
		array( 'گارانتی', '۲۴ ماه' ),
	);
}

/* ---- Counters strip (D22) ---- */
$erfanpro_counters = erfanpro_home_counters();
$erfanpro_hero_counters = array();
if ( ! empty( $erfanpro_counters ) ) {
	foreach ( array_slice( $erfanpro_counters, 0, 4 ) as $counter ) {
		$erfanpro_hero_counters[] = array(
			'value' => erfanpro_fa_digits( erfanpro_home_counter_value( $counter ) ) . '+',
			'label' => (string) ( $counter['label'] ?? '' ),
		);
	}
}
if ( count( $erfanpro_hero_counters ) < 4 ) {
	$erfanpro_hero_counters = array(
		array( 'value' => '۲۵+',  'label' => 'سال تجربه درخشان' ),
		array( 'value' => '۸۵۰+', 'label' => 'پروژه تکمیل‌شده' ),
		array( 'value' => '۲۸۹+', 'label' => 'مشتری فعال' ),
		array( 'value' => '۹۰+',  'label' => 'محصول متنوع' ),
	);
}
?>
<section id="home" class="relative isolate flex min-h-svh flex-col overflow-hidden bg-[#f6f8f7] pt-32 lg:pt-36">
	<div class="absolute inset-0 -z-10" style="transform: translateY(0%) scale(1.02);" data-plx-bg="">
		<img alt="نورپردازی شهری عرفان صنعت اصفهان" class="h-full w-full object-cover object-center" src="<?php echo esc_url( ERFANPRO_URI . '/assets/img/hero.jpg' ); ?>">
	</div>
	<div aria-hidden="true" class="absolute inset-0 -z-10" style="background: linear-gradient(270deg, rgba(246, 248, 247, 0.99) 0%, rgba(246, 248, 247, 0.97) 30%, rgba(246, 248, 247, 0.72) 56%, rgba(246, 248, 247, 0.16) 82%, rgba(246, 248, 247, 0) 100%), linear-gradient(rgba(246, 248, 247, 0.55) 0%, transparent 22%, transparent 62%, rgb(246, 248, 247) 100%);"></div>
	<div class="light-dot-grid absolute inset-0 -z-10 opacity-40" aria-hidden="true"></div>
	<div aria-hidden="true" class="absolute -left-32 top-1/4 -z-10 h-80 w-80 rounded-full bg-emerald-200/50 blur-[110px]"></div>
	<div aria-hidden="true" class="absolute -right-24 bottom-16 -z-10 h-72 w-72 rounded-full bg-teal-100/60 blur-[100px]"></div>
	<div class="relative z-10 mx-auto flex w-full max-w-7xl flex-1 items-center px-5 pb-20 lg:px-8" style="opacity: 1; transform: translateY(0%);" data-plx-copy="">
		<div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-14">
			<div class="lg:col-span-7">
				<div class="mb-7"<?php erfanpro_reveal( 0.00, 'mount' ); ?>>
					<span class="inline-flex items-center gap-3">
						<span class="relative inline-flex shrink-0 items-center justify-center px-2 py-1">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 540 200" fill="none" class="h-14 w-[9.6rem]" aria-label="<?php esc_attr_e( 'لوگوی عرفان صنعت اصفهان ESE ERFAN', 'erfanpro' ); ?>"><path d="M100 79 L172 79 C248 56 346 46 398 63 C426 72 429 95 407 112 C442 97 451 65 414 45 C358 16 226 35 100 79 Z" fill="#00A83E"></path><path d="M78 96 C44 121 42 158 86 174 C150 197 280 176 396 135 L330 135 C244 156 148 166 98 150 C65 140 61 115 78 96 Z" fill="#A8B0B8"></path><g transform="translate(106, 0) skewX(-14)"><path d="M0 86 H96 V97 H35 V119 H96 V130 H0 V120 H11 V96 H0 V86 Z" fill="#00A83E"></path><rect x="41" y="103" width="37" height="10" fill="#00A83E"></rect><path d="M108 100 C108 90 116 86 130 86 H202 V97 H135 C130 97 128 99 128 101 C128 103 130 104 136 104 H182 C196 104 203 109 203 118 C203 126 196 130 181 130 H108 V119 H176 C181 119 183 118 183 115 C183 113 181 112 175 112 H129 C114 112 108 108 108 100 Z" fill="#00A83E"></path><path d="M215 86 H311 V97 H250 V119 H311 V130 H215 V120 H226 V96 H215 V86 Z" fill="#B4BCC4"></path><rect x="256" y="103" width="37" height="10" fill="#B4BCC4"></rect></g><text x="393" y="130" fill="#B4BCC4" font-family="Georgia, 'Times New Roman', serif" font-weight="900" font-style="italic" font-size="33" letter-spacing="1.5">RFAN</text></svg>
						</span>
						<span class="flex flex-col leading-tight">
							<span class="text-[16.5px] font-extrabold text-slate-900"><?php esc_html_e( 'عرفان', 'erfanpro' ); ?> <span class="text-emerald-600"><?php esc_html_e( 'صنعت', 'erfanpro' ); ?></span> <?php esc_html_e( 'اصفهان', 'erfanpro' ); ?></span>
							<span class="text-[10.5px] font-bold tracking-[0.14em] text-slate-500">ESE · ERFAN SANAT</span>
						</span>
					</span>
				</div>
				<div class="mb-5 inline-flex items-center gap-3 rounded-full border border-emerald-200 bg-white px-4 py-2 shadow-sm"<?php erfanpro_reveal( 0.09, 'mount', 'opacity: 0; transform: translateY(16px);' ); ?>>
					<span class="relative flex h-2 w-2">
						<span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-70"></span>
						<span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
					</span>
					<span class="text-[12px] font-extrabold tracking-[0.14em] text-emerald-800"><?php esc_html_e( 'شرکت دانش‌بنیان · ISO 9001', 'erfanpro' ); ?></span>
				</div>
				<h1 class="text-[2.6rem] font-black leading-[1.2] tracking-[-0.03em] text-slate-950 sm:text-6xl sm:leading-[1.14] lg:text-[4.4rem] lg:leading-[1.1]">
					<span class="block overflow-hidden pb-[0.08em]">
						<span class="block"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(100%);', 'opacity: 1; transform: none;' ); ?>><?php esc_html_e( 'شهر را با نور،', 'erfanpro' ); ?></span>
					</span>
					<span class="block overflow-hidden pb-[0.08em]">
						<span class="block"<?php erfanpro_reveal( 0.10, 'mount', 'opacity: 0; transform: translateY(100%);', 'opacity: 1; transform: none;' ); ?>><span class="text-emerald-700"><?php esc_html_e( 'زنده می‌کنیم.', 'erfanpro' ); ?></span></span>
					</span>
				</h1>
				<p class="mt-6 max-w-xl text-[15.5px] font-medium leading-9 text-slate-600 lg:text-[17px] lg:leading-10"<?php erfanpro_reveal( 0.18, 'mount', 'opacity: 0; transform: translateY(20px);' ); ?>><?php esc_html_e( 'با بیش از دو دهه تجربه، پیشگام در طراحی، تولید و اجرای پروژه‌های روشنایی شهری و صنعتی؛ روشنایی‌بخش خیابان‌ها، پارک‌ها، میادین، ساختمان‌ها و صنایع کشور هستیم.', 'erfanpro' ); ?></p>
				<div class="mt-9 flex flex-wrap items-center gap-4"<?php erfanpro_reveal( 0.27, 'mount', 'opacity: 0; transform: translateY(20px);' ); ?>>
					<a class="group inline-flex items-center gap-2.5 rounded-full bg-emerald-700 px-8 py-4 text-[15px] font-extrabold text-white shadow-[0_14px_40px_-10px_rgba(0,125,45,0.7)] transition-all hover:bg-emerald-800" href="<?php echo esc_url( erfanpro_archive_url( 'projects' ) ); ?>"><?php esc_html_e( 'مشاهده پروژه‌های نورپردازی', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down h-4.5 w-4.5 transition-transform duration-400 group-hover:translate-y-1" aria-hidden="true"><path d="M12 5v14"></path><path d="m19 12-7 7-7-7"></path></svg></a>
					<a class="group inline-flex items-center gap-2.5 rounded-full border border-emerald-300 bg-white px-8 py-4 text-[15px] font-bold text-emerald-900 shadow-sm transition-all hover:border-emerald-500 hover:bg-emerald-50" href="<?php echo esc_url( erfanpro_page_url( 'contact' ) ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-5 w-5 text-emerald-600" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg><?php esc_html_e( 'دریافت مشاوره رایگان', 'erfanpro' ); ?></a>
				</div>
			</div>
			<div class="lg:col-span-5">
				<div class="relative mx-auto max-w-sm"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateX(30px) translateY(20px);' ); ?>>
					<div aria-hidden="true" class="absolute -inset-3 rounded-[2.25rem] border border-emerald-200/60"></div>
					<div class="relative overflow-hidden rounded-[2rem] border border-emerald-100 bg-white shadow-[0_30px_70px_-24px_rgba(0,80,40,0.3)]">
						<div class="relative h-56 overflow-hidden">
							<img alt="<?php echo esc_attr( $erfanpro_fp_title ); ?>" class="h-full w-full object-cover" src="<?php echo esc_url( $erfanpro_fp_image ); ?>">
							<div class="absolute inset-0 bg-gradient-to-t from-emerald-950/45 via-transparent to-transparent"></div>
							<span class="absolute right-4 top-4 flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-[10.5px] font-black text-emerald-800 backdrop-blur"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles lucide-stars h-3.5 w-3.5" aria-hidden="true"><path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"></path><path d="M20 2v4"></path><path d="M22 4h-4"></path><circle cx="4" cy="20" r="2"></circle></svg><?php esc_html_e( 'محصول شاخص', 'erfanpro' ); ?></span>
						</div>
						<div class="p-6">
							<p class="text-[11px] font-bold text-emerald-600"><?php echo esc_html( $erfanpro_fp_cat ); ?></p>
							<h3 class="mt-1.5 text-lg font-black text-slate-900"><?php echo esc_html( $erfanpro_fp_title ); ?></h3>
							<div class="mt-4 grid grid-cols-2 gap-2.5">
								<?php foreach ( $erfanpro_fp_specs as $spec ) : ?>
								<div class="rounded-xl bg-emerald-50 px-3 py-2.5">
									<span class="block text-[10px] text-slate-500"><?php echo esc_html( $spec[0] ); ?></span>
									<span class="mt-0.5 block text-[12.5px] font-extrabold text-emerald-900" dir="auto"><?php echo esc_html( $spec[1] ); ?></span>
								</div>
								<?php endforeach; ?>
							</div>
							<a class="group mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-[13px] font-black text-white transition-colors hover:bg-emerald-800" href="<?php echo esc_url( $erfanpro_fp_url ); ?>"><?php esc_html_e( 'مشاهده محصول', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down h-4 w-4 transition-transform group-hover:translate-y-0.5" aria-hidden="true"><path d="M12 5v14"></path><path d="m19 12-7 7-7-7"></path></svg></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="relative z-10 mx-auto w-full max-w-6xl px-5 pb-8 lg:px-8"<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; transform: translateY(30px);' ); ?>>
		<div class="grid grid-cols-2 divide-x divide-x-reverse divide-emerald-100 overflow-hidden rounded-3xl border border-emerald-100 bg-white/90 shadow-[0_20px_50px_-28px_rgba(0,80,40,0.25)] backdrop-blur-xl lg:grid-cols-4">
			<?php foreach ( $erfanpro_hero_counters as $counter ) : ?>
			<div class="group relative flex flex-col items-center gap-1.5 px-4 py-6 transition-colors hover:bg-emerald-50/50">
				<span class="text-2xl font-black text-emerald-700 lg:text-[2rem]"><?php echo esc_html( $counter['value'] ); ?></span>
				<span class="text-[12.5px] font-semibold text-slate-500 lg:text-sm"><?php echo esc_html( $counter['label'] ); ?></span>
				<span class="absolute bottom-0 h-px w-0 bg-gradient-to-l from-transparent via-emerald-500 to-transparent transition-all duration-700 group-hover:w-full"></span>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<a href="#projects" class="absolute bottom-24 left-1/2 z-10 hidden -translate-x-1/2 lg:flex" aria-label="<?php esc_attr_e( 'اسکرول', 'erfanpro' ); ?>" style="opacity: 1;">
		<span class="grid h-11 w-11 place-items-center rounded-full border border-emerald-200 bg-white text-emerald-700 shadow-md"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-5 w-5" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg></span>
	</a>
</section>
