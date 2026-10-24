<?php
/**
 * Phase 10: WooCommerce account navigation — rebuilt with erfanpro
 * styling. Same items and endpoints as the WooCommerce default,
 * rendered as the theme's rounded-card vertical menu; the active
 * endpoint gets the emerald treatment.
 *
 * @package Erfanpro
 */

defined( 'ABSPATH' ) || exit;
?>
<nav class="woocommerce-MyAccount-navigation erfanpro-account-nav" aria-label="<?php esc_attr_e( 'منوی حساب کاربری', 'woocommerce' ); ?>">
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $erfanpro_endpoint => $erfanpro_label ) : ?>
			<li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $erfanpro_endpoint ) ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $erfanpro_endpoint ) ); ?>"><?php echo esc_html( $erfanpro_label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
