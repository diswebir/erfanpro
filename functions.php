<?php
/**
 * erfan2027pro — bootstrap.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ERFANPRO_VERSION', '0.1.0' );
define( 'ERFANPRO_DIR', get_template_directory() );
define( 'ERFANPRO_URI', get_template_directory_uri() );

require_once ERFANPRO_DIR . '/inc/helpers.php';
require_once ERFANPRO_DIR . '/inc/options.php';
require_once ERFANPRO_DIR . '/inc/menu-walkers.php';
require_once ERFANPRO_DIR . '/inc/enqueue.php';
require_once ERFANPRO_DIR . '/inc/meta-format.php';
require_once ERFANPRO_DIR . '/inc/cpt-services.php';
require_once ERFANPRO_DIR . '/inc/cpt-projects-fallback.php';
require_once ERFANPRO_DIR . '/inc/metaboxes.php';
require_once ERFANPRO_DIR . '/inc/metaboxes-services.php';
require_once ERFANPRO_DIR . '/inc/view-counter.php';
require_once ERFANPRO_DIR . '/inc/multilang.php';
require_once ERFANPRO_DIR . '/inc/leads.php';
require_once ERFANPRO_DIR . '/inc/shop.php';
require_once ERFANPRO_DIR . '/inc/product.php';
require_once ERFANPRO_DIR . '/inc/projects.php';
require_once ERFANPRO_DIR . '/inc/services.php';

/**
 * Theme setup: supports + menu locations matching the blueprint menus
 * (main-mega-menu, فهرست موبایل, landing, tolidat-sherkat).
 */
function erfanpro_setup() {
	load_theme_textdomain( 'erfanpro', ERFANPRO_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'mega'    => __( 'مگامنو اصلی (main-mega-menu)', 'erfanpro' ),
			'mobile'  => __( 'فهرست موبایل', 'erfanpro' ),
			'landing' => __( 'لندینگ', 'erfanpro' ),
			'company' => __( 'تولیدات شرکت', 'erfanpro' ),
		)
	);
}
add_action( 'after_setup_theme', 'erfanpro_setup' );
