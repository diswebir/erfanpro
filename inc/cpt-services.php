<?php
/**
 * Services post type (project decision 11): the blueprint has no services
 * CPT, so the theme registers one following the same pattern as the
 * JetEngine `projects` CPT. Metaboxes land in phase 1.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the `services` post type.
 */
function erfanpro_register_services_cpt() {
	$labels = array(
		'name'               => __( 'خدمات', 'erfanpro' ),
		'singular_name'      => __( 'خدمت', 'erfanpro' ),
		'menu_name'          => __( 'خدمات', 'erfanpro' ),
		'add_new'            => __( 'افزودن', 'erfanpro' ),
		'add_new_item'       => __( 'افزودن خدمت تازه', 'erfanpro' ),
		'edit_item'          => __( 'ویرایش خدمت', 'erfanpro' ),
		'new_item'           => __( 'خدمت تازه', 'erfanpro' ),
		'view_item'          => __( 'نمایش خدمت', 'erfanpro' ),
		'search_items'       => __( 'جستجوی خدمات', 'erfanpro' ),
		'not_found'          => __( 'خدمتی پیدا نشد', 'erfanpro' ),
		'not_found_in_trash' => __( 'خدمتی در زباله‌دان پیدا نشد', 'erfanpro' ),
	);

	register_post_type(
		'services',
		array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-admin-tools',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'      => array( 'slug' => 'services' ),
		)
	);
}
add_action( 'init', 'erfanpro_register_services_cpt' );
