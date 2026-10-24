<?php
/**
 * Home — featured products (phase 3).
 *
 * Five WooCommerce products (featured first, then latest) in the
 * reference card layout. Per-product data: image, title, tags/attribute
 * chips, attribute bullets, purchasable badge + from-price (decision 3:
 * priced = purchasable, unpriced = no badge). The reference static
 * cards are the design fallback when WooCommerce has no products.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_cards = array();

if ( function_exists( 'wc_get_products' ) ) {
	$ids = wc_get_products(
		array(
			'featured' => true,
			'limit'    => 5,
			'status'   => 'publish',
			'return'   => 'ids',
		)
	);
	if ( count( $ids ) < 5 ) {
		$more = wc_get_products(
			array(
				'limit'    => 5 - count( $ids ),
				'status'   => 'publish',
				'exclude'  => $ids,
				'orderby'  => 'date',
				'order'    => 'DESC',
				'return'   => 'ids',
			)
		);
		$ids = array_merge( $ids, $more );
	}

	foreach ( $ids as $pid ) {
		$product = wc_get_product( $pid );
		if ( ! $product instanceof WC_Product ) {
			continue;
		}

		$image = wp_get_attachment_image_url( $product->get_image_id(), 'large' );

		$chips = array();
		$tags  = get_the_terms( $pid, 'product_tag' );
		if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
			foreach ( array_slice( $tags, 0, 4 ) as $tag ) {
				$chips[] = $tag->name;
			}
		}

		$bullets = array();
		foreach ( $product->get_attributes() as $attr ) {
			if ( $attr->is_taxonomy() ) {
				$values = wc_get_product_terms( $pid, $attr->get_name(), array( 'fields' => 'names' ) );
				$values = is_array( $values ) ? $values : array();
			} else {
				$values = $attr->get_options();
			}
			foreach ( $values as $v ) {
				$v = trim( (string) $v );
				if ( '' !== $v ) {
					$bullets[] = $v;
				}
				if ( count( $bullets ) >= 3 ) {
					break 2;
				}
			}
		}
		if ( empty( $chips ) ) {
			$chips = array_slice( $bullets, 0, 4 );
		}

		$purchasable = $product->is_purchasable();
		$price_text  = '';
		if ( $purchasable ) {
			$raw = $product->is_type( 'variable' ) ? $product->get_variation_price( 'min' ) : $product->get_price();
			if ( '' !== (string) $raw && null !== $raw ) {
				$price_text = 'از ' . erfanpro_fa_digits( number_format( (float) $raw ) ) . ' تومان';
			}
		}

		$erfanpro_cards[] = array(
			'title'       => $product->get_name(),
			'image'       => $image ? $image : '',
			'chips'       => $chips,
			'bullets'     => $bullets,
			'purchasable' => $purchasable,
			'price'       => $price_text,
			'url'         => $product->get_permalink(),
		);
	}
}

/* Reference design fallback: the five static cards verbatim. */
if ( count( $erfanpro_cards ) < 5 ) {
	$erfanpro_cards = array(
		array(
			'title'       => 'درختان نوری هوشمند',
			'image'       => ERFANPRO_URI . '/assets/img/light-tree.jpg',
			'chips'       => array( 'دلسا', 'آرتام', 'آتریسا', 'ایلومینا' ),
			'bullets'     => array( '۱۶ میلیون رنگ', 'کنترل با اپلیکیشن', 'پیکسل RGB' ),
			'purchasable' => false,
			'price'       => '',
			'url'         => erfanpro_archive_url( 'product' ),
		),
		array(
			'title'       => 'خورشید‌نما',
			'image'       => ERFANPRO_URI . '/assets/img/sun-light.jpg',
			'chips'       => array( '۳۲ پره', '۴۸ پره' ),
			'bullets'     => array( 'قطر تا ۳.۳ متری', 'پروتکل NRF', '+۲۳۰۰ پیکسل' ),
			'purchasable' => false,
			'price'       => '',
			'url'         => erfanpro_archive_url( 'product' ),
		),
		array(
			'title'       => 'لوستر فضای باز',
			'image'       => ERFANPRO_URI . '/assets/img/chandelier.jpg',
			'chips'       => array( 'فروغ', 'شمسه' ),
			'bullets'     => array( 'ارتفاع تا ۷ متر', 'ریسه سوزنی LED' ),
			'purchasable' => false,
			'price'       => '',
			'url'         => erfanpro_archive_url( 'product' ),
		),
		array(
			'title'       => 'ریسه‌های LED',
			'image'       => ERFANPRO_URI . '/assets/img/string-lights.jpg',
			'chips'       => array( 'فندقی', 'بلوطی', 'سوزنی', 'فلکسی' ),
			'bullets'     => array( 'ضدضربه پلی‌کربنات', 'IP67' ),
			'purchasable' => true,
			'price'       => 'از ۱,۲۰۰,۰۰۰ تومان',
			'url'         => erfanpro_archive_url( 'product' ),
		),
		array(
			'title'       => 'پوینت لایت پیکسل',
			'image'       => '',
			'chips'       => array( 'LED 4 سانتی‌متری' ),
			'bullets'     => array( 'فول‌کالر هوشمند', 'قاب نگهدارنده PLT', 'IP68' ),
			'purchasable' => false,
			'price'       => '',
			'url'         => erfanpro_archive_url( 'product' ),
		),
	);
}

$erfanpro_sec = erfanpro_home_section( 'products' );
?>
<section id="products" class="relative z-10 py-24 lg:py-32" style="scroll-margin-top: 5rem;">
	<div class="mx-auto max-w-7xl px-5 lg:px-8">
		<div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
			<div class="flex max-w-3xl flex-col gap-4 items-start text-right">
				<div<?php erfanpro_reveal( 0.00 ); ?>>
					<span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/25 bg-emerald-50 px-4 py-1.5 text-[13px] font-bold tracking-wide text-emerald-800 shadow-xs">
						<span class="relative flex h-2 w-2">
							<span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-60"></span>
							<span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
						</span><?php esc_html_e( 'محبوب‌ترین تولیدات شرکت', 'erfanpro' ); ?>
					</span>
				</div>
				<div<?php erfanpro_reveal( 0.07 ); ?>>
					<?php if ( '' !== $erfanpro_sec['title'] ) : ?>
					<h2 class="text-3xl font-extrabold leading-[1.35] text-slate-900 sm:text-4xl lg:text-[2.6rem] lg:leading-[1.3]"><?php echo esc_html( $erfanpro_sec['title'] ); ?></h2>
					<?php else : ?>
					<h2 class="text-3xl font-extrabold leading-[1.35] text-slate-900 sm:text-4xl lg:text-[2.6rem] lg:leading-[1.3]"><?php esc_html_e( 'برخی از', 'erfanpro' ); ?> <span class="bg-gradient-to-l from-emerald-800 via-emerald-600 to-teal-700 bg-clip-text text-transparent"><?php esc_html_e( 'محصولات عرفان صنعت', 'erfanpro' ); ?></span></h2>
					<?php endif; ?>
				</div>
				<div<?php erfanpro_reveal( 0.14 ); ?>>
					<p class="text-base leading-8 text-slate-600 lg:text-[17px] lg:leading-9"><?php echo '' !== $erfanpro_sec['subtitle'] ? esc_html( $erfanpro_sec['subtitle'] ) : esc_html__( 'با بهره‌گیری از به‌روزترین تجهیزات و کارشناسان متخصص، مجموعه‌ای بی‌نظیر از محصولات نورپردازی پیشرفته را عرضه می‌کنیم؛ روشنایی را به شهر خود دعوت کنید.', 'erfanpro' ); ?></p>
				</div>
			</div>
			<div class="shrink-0"<?php erfanpro_reveal( 0.00 ); ?>>
				<div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-white px-5 py-4 shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wifi h-8 w-8 text-emerald-600" aria-hidden="true"><path d="M12 20h.01"></path><path d="M2 8.82a15 15 0 0 1 20 0"></path><path d="M5 12.859a10 10 0 0 1 14 0"></path><path d="M8.5 16.429a5 5 0 0 1 7 0"></path></svg><div class="flex flex-col"><span class="text-sm font-bold text-slate-900"><?php esc_html_e( 'کنترل هوشمند', 'erfanpro' ); ?></span><span class="text-[12px] text-slate-600"><?php esc_html_e( 'WiFi و پروتکل NRF در تمامی محصولات', 'erfanpro' ); ?></span></div></div>
			</div>
		</div>
		<div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
			<?php foreach ( $erfanpro_cards as $i => $card ) : ?>
				<?php
				$is_first = 0 === $i;
				$is_light = 4 === $i;
				$wrapper  = $is_first ? 'sm:col-span-2' : '';
				?>
			<div class="<?php echo esc_attr( $wrapper ); ?>"<?php erfanpro_reveal( 0.07 * $i ); ?>>
				<?php if ( $is_light ) : ?>
				<a class="card-glow group relative flex h-full min-h-[300px] flex-col justify-end overflow-hidden rounded-[1.75rem] border border-emerald-100 bg-white shadow-sm" href="<?php echo esc_url( $card['url'] ); ?>">
					<div class="absolute -top-20 left-1/2 h-64 w-64 -translate-x-1/2 rounded-full blur-[90px] transition-opacity duration-700 bg-emerald-200/60 opacity-70 group-hover:opacity-100"></div>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cpu absolute top-8 right-8 h-12 w-12 text-emerald-600 transition-transform duration-500 group-hover:scale-110" aria-hidden="true"><path d="M12 20v2"></path><path d="M12 2v2"></path><path d="M17 20v2"></path><path d="M17 2v2"></path><path d="M2 12h2"></path><path d="M2 17h2"></path><path d="M2 7h2"></path><path d="M20 12h2"></path><path d="M20 17h2"></path><path d="M20 7h2"></path><path d="M7 20v2"></path><path d="M7 2v2"></path><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="8" y="8" width="8" height="8" rx="1"></rect></svg>
					<span class="absolute top-8 left-8 text-5xl font-black text-emerald-900/[0.06]">ES</span>
					<div class="relative z-10 flex flex-col gap-3.5 p-7">
						<div class="flex items-start justify-between gap-3">
							<h3 class="text-xl font-extrabold lg:text-2xl text-slate-900"><?php echo esc_html( $card['title'] ); ?></h3>
							<span class="grid h-10 w-10 shrink-0 place-items-center rounded-full border backdrop-blur-md transition-all duration-500 group-hover:bg-emerald-600 border-emerald-200 bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4.5 w-4.5" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></span>
						</div>
						<?php if ( ! empty( $card['chips'] ) ) : ?>
						<div class="flex flex-wrap gap-2">
							<?php foreach ( $card['chips'] as $chip ) : ?>
							<span class="rounded-full border px-3 py-1 text-[11.5px] font-bold border-emerald-200 bg-emerald-50 text-emerald-800"><?php echo esc_html( $chip ); ?></span>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
						<?php if ( ! empty( $card['bullets'] ) ) : ?>
						<div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 border-t pt-3.5 border-slate-200">
							<?php foreach ( $card['bullets'] as $bullet ) : ?>
							<span class="flex items-center gap-1.5 text-[12px] font-semibold text-slate-600"><span class="h-1 w-1 rounded-full bg-emerald-500"></span><?php echo esc_html( $bullet ); ?></span>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
					</div>
				</a>
				<?php else : ?>
				<a class="card-glow group relative flex h-full min-h-[300px] flex-col justify-end overflow-hidden rounded-[1.75rem] border border-emerald-100 bg-slate-900<?php echo $is_first ? ' min-h-[430px]' : ''; ?>" href="<?php echo esc_url( $card['url'] ); ?>">
					<?php if ( $card['image'] ) : ?>
					<img alt="<?php echo esc_attr( $card['title'] ); ?>" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-transform duration-[1.3s] ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-108" src="<?php echo esc_url( $card['image'] ); ?>">
					<?php endif; ?>
					<div class="image-overlay absolute inset-0"></div>
					<?php if ( $card['purchasable'] ) : ?>
					<span class="absolute left-4 top-4 z-10 rounded-full bg-emerald-600 px-3.5 py-1.5 text-[11.5px] font-bold text-white shadow-lg shadow-emerald-600/25 backdrop-blur-sm">🛒 <?php esc_html_e( 'قابل خرید', 'erfanpro' ); ?></span>
					<?php endif; ?>
					<div class="relative z-10 flex flex-col gap-3.5 p-7">
						<div class="flex items-start justify-between gap-3">
							<h3 class="text-xl font-extrabold lg:text-2xl text-white [text-shadow:0_2px_12px_rgba(0,0,0,.8)]"><?php echo esc_html( $card['title'] ); ?></h3>
							<span class="grid h-10 w-10 shrink-0 place-items-center rounded-full border backdrop-blur-md transition-all duration-500 group-hover:bg-emerald-600 border-white/30 bg-emerald-950/70 text-white"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4.5 w-4.5" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></span>
						</div>
						<?php if ( ! empty( $card['chips'] ) ) : ?>
						<div class="flex flex-wrap gap-2">
							<?php foreach ( $card['chips'] as $chip ) : ?>
							<span class="rounded-full border px-3 py-1 text-[11.5px] font-bold border-emerald-300/40 bg-emerald-950/55 text-emerald-100"><?php echo esc_html( $chip ); ?></span>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
						<?php if ( ! empty( $card['bullets'] ) ) : ?>
						<div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 border-t pt-3.5 border-white/20">
							<?php foreach ( $card['bullets'] as $bullet ) : ?>
							<span class="flex items-center gap-1.5 text-[12px] font-semibold text-white/85"><span class="h-1 w-1 rounded-full bg-emerald-500"></span><?php echo esc_html( $bullet ); ?></span>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
						<?php if ( '' !== $card['price'] ) : ?>
						<div class="mt-1 text-[14px] font-extrabold text-emerald-300"><?php echo esc_html( $card['price'] ); ?></div>
						<?php endif; ?>
					</div>
				</a>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
			<div class="sm:col-span-2"<?php erfanpro_reveal( 0.35 ); ?>>
				<div class="card-light relative flex h-full min-h-[300px] flex-col items-start justify-end gap-4 overflow-hidden rounded-[1.75rem] p-8">
					<div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-emerald-200/70 blur-[80px]"></div>
					<div class="absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-teal-100 blur-[70px]"></div>
					<h3 class="relative z-10 text-2xl font-extrabold leading-[1.5] text-slate-900 lg:text-[1.7rem]"><?php esc_html_e( 'طراحی و ساخت سفارشی', 'erfanpro' ); ?><span class="block text-emerald-700"><?php esc_html_e( 'مطابق طرح اختصاصی شما', 'erfanpro' ); ?></span></h3>
					<p class="relative z-10 max-w-md text-[13.5px] leading-7 text-slate-600"><?php esc_html_e( 'اگر طرح خاصی در ذهن دارید، تیم مهندسی ما آن را به صورت کامل طراحی، تولید و پیاده‌سازی می‌کند؛ از ایده تا اجرای نهایی.', 'erfanpro' ); ?></p>
					<a class="relative z-10 mt-1 inline-flex items-center gap-2.5 rounded-full bg-emerald-600 px-6 py-3 text-sm font-extrabold text-white transition-all duration-400 hover:bg-emerald-700 hover:shadow-[0_8px_30px_-8px_rgba(5,150,105,.5)]" href="<?php echo esc_url( erfanpro_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'شروع سفارش اختصاصی', 'erfanpro' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></a>
				</div>
			</div>
		</div>
	</div>
</section>
