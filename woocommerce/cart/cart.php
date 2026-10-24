<?php
/**
 * Phase 10: WooCommerce cart — rebuilt with erfanpro styling.
 *
 * Follows the WooCommerce default cart template's hook structure
 * exactly (cart item hooks, coupon, update-cart nonce, collaterals
 * for totals + cross-sells) so every gateway/plugin hook keeps
 * working; only the wrapper and classes are the theme's own
 * (emerald hero, #f8faf9 main, white cards). Hybrid sales model
 * (decision 3): unpriced products never reach the cart — they use
 * the phase-5 inquiry card — so the note below points cart visitors
 * at the inquiry path for anything not purchasable.
 *
 * @package Erfanpro
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>
<div class="erfanpro-wc-wrap min-h-screen bg-[#f8faf9]">
	<?php
	get_template_part(
		'template-parts/page/hero',
		null,
		array(
			'title'    => __( 'سبد خرید', 'erfanpro' ),
			'subtitle' => __( 'محصولات دارای قیمت را اینجا نهایی کنید؛ برای محصولات بدون قیمت، از کارت استعلام همان محصول استفاده کنید.', 'erfanpro' ),
			'badge'    => __( 'فروشگاه عرفان صنعت', 'erfanpro' ),
		)
	);
	?>
	<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>
			<div class="overflow-hidden rounded-[2rem] border border-slate-100 bg-white shadow-sm">
				<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents erfanpro-cart-table" cellspacing="0">
					<thead>
						<tr>
							<th class="product-remove"><span class="screen-reader-text"><?php esc_html_e( 'حذف', 'woocommerce' ); ?></span></th>
							<th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e( 'تصویر', 'woocommerce' ); ?></span></th>
							<th class="product-name"><?php esc_html_e( 'محصول', 'woocommerce' ); ?></th>
							<th class="product-price"><?php esc_html_e( 'قیمت', 'woocommerce' ); ?></th>
							<th class="product-quantity"><?php esc_html_e( 'تعداد', 'woocommerce' ); ?></th>
							<th class="product-subtotal"><?php esc_html_e( 'جمع جزء', 'woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php do_action( 'woocommerce_before_cart_contents' ); ?>
						<?php
						foreach ( WC()->cart->get_cart() as $erfanpro_cart_item_key => $erfanpro_cart_item ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $erfanpro_cart_item['data'], $erfanpro_cart_item, $erfanpro_cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $erfanpro_cart_item['product_id'], $erfanpro_cart_item, $erfanpro_cart_item_key );
							if ( $_product && $_product->exists() && $erfanpro_cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $erfanpro_cart_item, $erfanpro_cart_item_key ) ) {
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $erfanpro_cart_item ) : '', $erfanpro_cart_item, $erfanpro_cart_item_key );
								?>
								<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $erfanpro_cart_item, $erfanpro_cart_item_key ) ); ?>">
									<td class="product-remove">
										<?php
										echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
												esc_url( wc_get_cart_remove_url( $erfanpro_cart_item_key ) ),
												/* translators: %s: product name. */
												esc_attr( sprintf( __( 'حذف %s از سبد خرید', 'woocommerce' ), wp_strip_all_tags( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $erfanpro_cart_item, $erfanpro_cart_item_key ) ) ) ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											),
											$erfanpro_cart_item_key
										);
										?>
									</td>
									<td class="product-thumbnail">
										<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $erfanpro_cart_item, $erfanpro_cart_item_key );
										if ( ! $product_permalink ) {
											echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
										?>
									</td>
									<td class="product-name" data-title="<?php esc_attr_e( 'محصول', 'woocommerce' ); ?>">
										<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $erfanpro_cart_item, $erfanpro_cart_item_key ) . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $erfanpro_cart_item, $erfanpro_cart_item_key ) );
										}
										do_action( 'woocommerce_after_cart_item_name', $erfanpro_cart_item, $erfanpro_cart_item_key );
										echo wc_get_formatted_cart_item_data( $erfanpro_cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $erfanpro_cart_item['quantity'] ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'موجود در انبار نیست — با تأخیر ارسال می‌شود.', 'woocommerce' ) . '</p>', $product_id ) );
										}
										?>
									</td>
									<td class="product-price" data-title="<?php esc_attr_e( 'قیمت', 'woocommerce' ); ?>">
										<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $erfanpro_cart_item, $erfanpro_cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</td>
									<td class="product-quantity" data-title="<?php esc_attr_e( 'تعداد', 'woocommerce' ); ?>">
										<?php
										if ( $_product->is_sold_individually() ) {
											$min_quantity = 1;
											$max_quantity = 1;
										} else {
											$min_quantity = 0;
											$max_quantity = $_product->get_max_purchase_quantity();
										}
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$erfanpro_cart_item_key}][qty]",
												'input_value'  => $erfanpro_cart_item['quantity'],
												'max_value'    => $max_quantity,
												'min_value'    => $min_quantity,
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);
										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $erfanpro_cart_item_key, $erfanpro_cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?>
									</td>
									<td class="product-subtotal" data-title="<?php esc_attr_e( 'جمع جزء', 'woocommerce' ); ?>">
										<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $erfanpro_cart_item['quantity'] ), $erfanpro_cart_item, $erfanpro_cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</td>
								</tr>
								<?php
							}
						}
						?>
						<?php do_action( 'woocommerce_cart_contents' ); ?>
						<tr>
							<td colspan="6" class="actions">
								<?php if ( wc_coupons_enabled() ) { ?>
									<div class="coupon">
										<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'کد تخفیف:', 'woocommerce' ); ?></label>
										<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'کد تخفیف', 'woocommerce' ); ?>" />
										<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'اعمال کد تخفیف', 'woocommerce' ); ?>"><?php esc_html_e( 'اعمال کد تخفیف', 'woocommerce' ); ?></button>
										<?php do_action( 'woocommerce_cart_coupon' ); ?>
									</div>
								<?php } ?>
								<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'به‌روزرسانی سبد خرید', 'woocommerce' ); ?>"><?php esc_html_e( 'به‌روزرسانی سبد خرید', 'woocommerce' ); ?></button>
								<?php do_action( 'woocommerce_cart_actions' ); ?>
								<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
							</td>
						</tr>
						<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					</tbody>
				</table>
			</div>
			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>
		<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
		<div class="cart-collaterals">
			<?php do_action( 'woocommerce_cart_collaterals' ); ?>
		</div>
		<div class="mt-6 rounded-[2rem] border border-amber-200/60 bg-amber-50 p-6 text-[14px] leading-8 text-amber-900">
			<?php esc_html_e( 'محصولی بدون قیمت در سبد شما نیست؟ محصولات بدون قیمت از طریق استعلام فروخته می‌شوند: در برگه همان محصول، دکمه استعلام را بزنید تا کارشناسان فروش پیش‌فاکتور صادر کنند.', 'erfanpro' ); ?>
			<a class="font-extrabold text-emerald-800 underline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'تماس با فروش', 'erfanpro' ); ?></a>
		</div>
	</section>
</div>
<?php
do_action( 'woocommerce_after_cart' );
