<?php
/**
 * Phase 1 — data layer: `projects` CPT + taxonomies fallback.
 *
 * On the live site these come from JetEngine (blueprint: CPT `projects`,
 * taxonomies `city` (20 terms) + `project_type` (20 terms)). The theme
 * registers them ONLY when they do not already exist, so the data layer
 * works identically with or without JetEngine — and never conflicts
 * with it. Slugs and names match the blueprint exactly.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the projects CPT + taxonomies when missing.
 */
function erfanpro_register_projects_fallback() {
	if ( ! post_type_exists( 'projects' ) ) {
		register_post_type(
			'projects',
			array(
				'labels'       => array(
					'name'          => __( 'پروژه‌ها', 'erfanpro' ),
					'singular_name' => __( 'پروژه', 'erfanpro' ),
					'menu_name'     => __( 'پروژه‌ها', 'erfanpro' ),
					'add_new_item'  => __( 'افزودن پروژه تازه', 'erfanpro' ),
					'edit_item'     => __( 'ویرایش پروژه', 'erfanpro' ),
				),
				'public'       => true,
				'has_archive'  => true,
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-portfolio',
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
				'rewrite'      => array( 'slug' => 'projects' ),
			)
		);
	}

	if ( ! taxonomy_exists( 'city' ) ) {
		register_taxonomy(
			'city',
			array( 'projects' ),
			array(
				'labels'       => array(
					'name'          => __( 'شهرها', 'erfanpro' ),
					'singular_name' => __( 'شهر', 'erfanpro' ),
				),
				'public'       => true,
				'hierarchical' => true,
				'show_in_rest' => true,
				'rewrite'      => array( 'slug' => 'project-city' ),
			)
		);
	}

	if ( ! taxonomy_exists( 'project_type' ) ) {
		register_taxonomy(
			'project_type',
			array( 'projects' ),
			array(
				'labels'       => array(
					'name'          => __( 'نوع پروژه', 'erfanpro' ),
					'singular_name' => __( 'نوع پروژه', 'erfanpro' ),
				),
				'public'       => true,
				'hierarchical' => true,
				'show_in_rest' => true,
				'rewrite'      => array( 'slug' => 'project-type' ),
			)
		);
	}
}
add_action( 'init', 'erfanpro_register_projects_fallback', 5 );
