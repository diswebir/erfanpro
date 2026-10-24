<?php
/**
 * Mobile menu source nav — visually hidden on purpose.
 *
 * assets/js/main.js (the reference runtime, initMobileMenu) builds the
 * animated mobile panel from the anchors of the first <nav> inside
 * <header>; this nav therefore carries the 'mobile' location menu
 * (blueprint «فهرست موبایل») so the panel opens with the full mobile
 * menu and the reference open/close animation. Do not give this nav
 * the id `static-mobile-menu` — main.js reserves that id for the
 * panel it generates.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="hidden" aria-label="<?php esc_attr_e( 'منوی موبایل', 'erfanpro' ); ?>" data-erfanpro-mobile-source>
	<?php erfanpro_render_mobile_source(); ?>
</nav>
