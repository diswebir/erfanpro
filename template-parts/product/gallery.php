<?php
/**
 * Phase 5 — product gallery: main image, thumbnails, prev/next,
 * counter, lightbox (via main.js), plus the product_video slide
 * (decision 18; handled by assets/js/product.js).
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

$slides = erfanpro_product_slides( $product_id );
if ( empty( $slides ) ) {
	return;
}

$images = array_values( array_filter( $slides, function ( $s ) { return 'image' === $s['type']; } ) );
$video  = null;
foreach ( $slides as $s ) {
	if ( 'video' === $s['type'] ) {
		$video = $s;
		break;
	}
}
if ( empty( $images ) && ! $video ) {
	return;
}
$video_only = empty( $images ) && $video;
if ( $video_only ) {
	$images = array();
} else {
	$first    = $images[0];
}
$num      = $video_only ? 1 : count( $images ); // image slides; the video thumb is extra.
$erfanpro_badges = erfanpro_section_texts( 'product_badges' );
$warranty = erfanpro_panel_text( $erfanpro_badges, 'gallery_warranty', __( '۲۴ ماه گارانتی تعویض', 'erfanpro' ) );
?>
<div class="lg:col-span-7">
	<div<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<div class="group relative overflow-hidden rounded-[1.75rem] border border-emerald-100 shadow-lg" id="erfanpro-product-gallery">
			<?php if ( ! $video_only ) : ?>
			<img alt="<?php echo esc_attr( sprintf( __( '%s — تصویر ۱', 'erfanpro' ), $title ) ); ?>" class="h-72 w-full cursor-zoom-in object-cover sm:h-96 lg:h-[27rem] anim-run gal-fade" src="<?php echo esc_url( $first['url'] ); ?>" data-anim="" data-anim-init="opacity: 0; transform: scale(1.04);" data-anim-final="opacity: 1; transform: none;" data-anim-kind="mount" data-anim-delay="0.00">
			<?php endif; ?>
			<?php if ( $video ) : ?>
			<video data-erfanpro-video class="h-72 w-full object-cover sm:h-96 lg:h-[27rem]"<?php echo $video_only ? '' : ' style="display:none"'; ?> controls preload="metadata" playsinline src="<?php echo esc_url( $video['url'] ); ?>"></video>
			<?php endif; ?>
			<span class="pointer-events-none absolute right-5 top-5 rounded-full bg-emerald-950/80 px-4 py-2 text-[12px] font-bold text-emerald-100 backdrop-blur-md"><?php echo esc_html( $warranty ); ?></span>
			<button type="button" aria-label="<?php esc_attr_e( 'نمایش بزرگ‌تر', 'erfanpro' ); ?>" class="absolute left-5 top-5 grid h-10 w-10 place-items-center rounded-full border border-white/30 bg-emerald-950/70 text-white backdrop-blur-md transition-all hover:bg-emerald-600"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-expand h-4.5 w-4.5" aria-hidden="true"><path d="m15 15 6 6"></path><path d="m15 9 6-6"></path><path d="M21 16v5h-5"></path><path d="M21 8V3h-5"></path><path d="M3 16v5h5"></path><path d="m3 21 6-6"></path><path d="M3 8V3h5"></path><path d="M9 9 3 3"></path></svg></button>
			<button type="button" aria-label="<?php esc_attr_e( 'تصویر قبلی', 'erfanpro' ); ?>" class="absolute right-4 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full border border-white/25 bg-emerald-950/60 text-white opacity-0 backdrop-blur-md transition-all hover:bg-emerald-600 group-hover:opacity-100"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-5 w-5" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg></button>
			<button type="button" aria-label="<?php esc_attr_e( 'تصویر بعدی', 'erfanpro' ); ?>" class="absolute left-4 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full border border-white/25 bg-emerald-950/60 text-white opacity-0 backdrop-blur-md transition-all hover:bg-emerald-600 group-hover:opacity-100"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-5 w-5" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg></button>
			<span class="pointer-events-none absolute bottom-4 left-5 rounded-full bg-emerald-950/75 px-3.5 py-1.5 text-[11.5px] font-bold text-white backdrop-blur-md"><?php echo esc_html( erfanpro_fa_digits( '1' ) . ' / ' . erfanpro_fa_digits( (string) $num ) ); ?></span>
		</div>
		<div class="mt-4 grid grid-cols-4 gap-3 sm:grid-cols-5 lg:grid-cols-6">
			<?php foreach ( $images as $i => $img ) : ?>
			<button type="button" aria-label="<?php echo esc_attr( sprintf( __( 'تصویر %s', 'erfanpro' ), erfanpro_fa_digits( $i + 1 ) ) ); ?>" class="relative h-16 overflow-hidden rounded-xl border-2 transition-all duration-300 sm:h-20 <?php echo 0 === $i ? 'border-emerald-500 shadow-[0_4px_16px_-4px_rgba(5,150,105,.45)]' : 'border-transparent opacity-65 hover:opacity-100'; ?>">
				<img alt="" loading="lazy" class="h-full w-full object-cover" src="<?php echo esc_url( $img['url'] ); ?>">
				<?php if ( 0 === $i ) : ?><span class="absolute inset-0 ring-1 ring-inset ring-emerald-400/50"></span><?php endif; ?>
			</button>
			<?php endforeach; ?>
			<?php if ( $video ) : ?>
			<button type="button" aria-label="<?php esc_attr_e( 'ویدیو محصول', 'erfanpro' ); ?>" data-erfanpro-video-thumb class="relative h-16 overflow-hidden rounded-xl border-2 transition-all duration-300 sm:h-20 border-transparent opacity-65 hover:opacity-100">
				<?php if ( ! empty( $images ) ) : ?>
				<img alt="" loading="lazy" class="h-full w-full object-cover" src="<?php echo esc_url( $images[0]['url'] ); ?>">
				<?php endif; ?>
				<span class="absolute inset-0 grid place-items-center bg-emerald-950/45">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play h-7 w-7 text-white" aria-hidden="true"><polygon points="6 3 20 12 6 21 6 3"></polygon></svg>
				</span>
			</button>
			<?php endif; ?>
		</div>
	</div>
</div>
