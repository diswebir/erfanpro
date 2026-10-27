<?php
/**
 * Phase 5 — order card (decision 3, automatic hybrid):
 * priced product → price + WooCommerce add-to-cart;
 * unpriced → the reference inquiry card (phone + WhatsApp).
 * Below it: the rating box (decision 15) and trust chips.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$erfanpro_st = erfanpro_section_texts( 'product_order' );
$erfanpro_badges = erfanpro_section_texts( 'product_badges' );

/* The assembler sets $erfanpro_product (WC_Product). */
$product    = isset( $args['erfanpro_product'] ) && $args['erfanpro_product'] instanceof WC_Product ? $args['erfanpro_product'] : null;
if ( ! $product && function_exists( 'wc_get_product' ) ) {
	/* Defense in depth: if the assembler did not pass the product,
	 * load it from the queried object instead of rendering inquiry. */
	$product = wc_get_product( get_queried_object_id() );
}
$product_id = $product ? $product->get_id() : get_queried_object_id();

$purchasable = false;
$price_html  = '';
if ( $product ) {
	$raw = $product->is_type( 'variable' ) ? $product->get_variation_price( 'min' ) : $product->get_price();
	if ( '' !== (string) $raw && null !== $raw ) {
		$purchasable = true;
		$price_html  = $product->get_price_html();
	}
}

$in_stock = $product ? $product->is_in_stock() : true;

$trust = array(
	array( erfanpro_panel_text( $erfanpro_badges, 'trust1_title', __( '۲۴ ماه گارانتی', 'erfanpro' ) ), erfanpro_panel_text( $erfanpro_badges, 'trust1_sub', __( 'تعویض بی‌قید', 'erfanpro' ) ), 'shield-check' ),
	array( erfanpro_panel_text( $erfanpro_badges, 'trust2_title', __( '۵ سال خدمات', 'erfanpro' ) ), erfanpro_panel_text( $erfanpro_badges, 'trust2_sub', __( 'تأمین قطعات', 'erfanpro' ) ), 'wrench' ),
	array( erfanpro_panel_text( $erfanpro_badges, 'trust3_title', __( 'استاندارد IP67', 'erfanpro' ) ), erfanpro_panel_text( $erfanpro_badges, 'trust3_sub', __( 'مقاوم در برابر آب', 'erfanpro' ) ), 'droplets' ),
	array( erfanpro_panel_text( $erfanpro_badges, 'trust4_title', __( 'خرید مستقیم', 'erfanpro' ) ), erfanpro_panel_text( $erfanpro_badges, 'trust4_sub', __( 'از کارخانه', 'erfanpro' ) ), 'factory' ),
);
$trust_svgs = array(
	'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path>',
	'wrench'       => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z"></path>',
	'droplets'     => '<path d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 6.75 7 5.3c-.29 1.45-1.14 2.84-2.29 3.76S3 11.1 3 12.25c0 2.22 1.8 4.05 4 4.05z"></path><path d="M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97"></path>',
	'factory'      => '<path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 7v-7H8v7l-6-7z"></path><path d="M17 18h1"></path><path d="M12 18h1"></path><path d="M7 18h1"></path>',
);
?>
<div class="space-y-6 lg:col-span-5">
	<div<?php erfanpro_reveal( 0.00, 'mount', 'opacity: 0; filter: blur(6px); transform: translateY(42px);', 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm lg:p-7">
			<div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-5">
				<div>
					<span class="text-[12px] font-bold text-slate-400"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'price_label', __( 'قیمت محصول', 'erfanpro' ) ) ); ?></span>
					<?php if ( $purchasable ) : ?>
					<div class="text-xl font-black text-emerald-800 sm:text-2xl"><?php echo $price_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php else : ?>
					<div class="text-xl font-black text-emerald-800 sm:text-2xl"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'call_label', __( 'تماس بگیرید', 'erfanpro' ) ) ); ?></div>
					<?php endif; ?>
				</div>
				<?php if ( $in_stock ) : ?>
				<span class="rounded-full bg-emerald-50 px-3.5 py-1.5 text-[11.5px] font-bold text-emerald-700"><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'stock_label', __( 'آماده تحویل', 'erfanpro' ) ) ); ?></span>
				<?php endif; ?>
			</div>
			<?php if ( $purchasable && $product ) : ?>
				<?php
				/* WooCommerce add-to-cart (quantity + button, or the
				 * variation form) in the reference card style. */
				woocommerce_template_single_add_to_cart();
				?>
			<?php else : ?>
				<p class="mb-5 text-[12.5px] font-medium text-slate-500"><?php esc_html_e( 'قیمت وابسته به متراژ، تعداد شعله و رنگ انتخابی', 'erfanpro' ); ?></p>
				<div class="flex flex-col gap-3">
					<a href="tel:<?php echo esc_attr( erfanpro_phone_tel() ); ?>" class="flex items-center justify-center gap-2.5 rounded-2xl bg-emerald-800 px-6 py-4 text-[14.5px] font-black text-white shadow-md shadow-emerald-800/20 transition-all hover:bg-emerald-900 hover:shadow-lg"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-4.5 w-4.5" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1 2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg><?php echo esc_html( erfanpro_panel_text( $erfanpro_st, 'inquiry_text', __( 'تماس برای استعلام قیمت و سفارش', 'erfanpro' ) ) ); ?></a>
					<a href="<?php echo esc_url( erfanpro_whatsapp_url() ); ?>" target="_blank" rel="noreferrer" class="flex items-center justify-center gap-2.5 rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-3.5 text-[14px] font-bold text-emerald-800 transition-colors hover:bg-emerald-100"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4.5 w-4.5" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg><?php esc_html_e( 'مشاوره و خرید در واتساپ', 'erfanpro' ); ?></a>
				</div>
				<p class="mt-4 text-center text-[11.5px] font-medium text-slate-400"><?php esc_html_e( 'پاسخگویی مستقیم:', 'erfanpro' ); ?> <?php echo esc_html( erfanpro_phone_display() ); ?> (<?php esc_html_e( '۱۰ خط ویژه', 'erfanpro' ); ?>)</p>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( $product ) : ?>
	<div<?php erfanpro_reveal( 0.07, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<?php echo erfanpro_product_rating_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php endif; ?>
	<div<?php erfanpro_reveal( 0.07, 'inview', null, 'opacity: 1; filter: blur(0px); transform: none;' ); ?>>
		<div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
			<?php foreach ( $trust as $chip ) : ?>
			<div class="flex flex-col items-center gap-1 rounded-2xl border border-slate-200/80 bg-white p-3.5 text-center shadow-2xs">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide h-5 w-5 text-emerald-600" aria-hidden="true"><?php echo $trust_svgs[ $chip[2] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></svg>
				<span class="text-[12px] font-bold text-slate-800"><?php echo esc_html( $chip[0] ); ?></span>
				<span class="text-[10.5px] text-slate-500"><?php echo esc_html( $chip[1] ); ?></span>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
