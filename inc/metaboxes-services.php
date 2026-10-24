<?php
/**
 * Phase 1 — data layer: `services` CPT metaboxes (admin build order BO-008).
 *
 * Mirrors the projects pattern: gallery, features repeater, steps
 * repeater, FAQ repeater, related projects. Keys are theme-owned
 * (`service_*`) since the blueprint has no services CPT.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register services metaboxes.
 */
function erfanpro_services_metaboxes() {
	add_meta_box(
		'erfanpro-service-main',
		__( 'جزئیات خدمت', 'erfanpro' ),
		'erfanpro_service_main_box',
		'services',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'erfanpro_services_metaboxes' );

/**
 * Services main box.
 */
function erfanpro_service_main_box( $post ) {
	erfanpro_meta_nonce_field();
	$id = $post->ID;

	erfanpro_field_media_multi(
		'service_gallery',
		__( 'گالری خدمت', 'erfanpro' ),
		erfanpro_get_id_list( $id, 'service_gallery' )
	);
	erfanpro_field_repeater(
		'service_features',
		__( 'ویژگی‌ها', 'erfanpro' ),
		erfanpro_get_repeater( $id, 'service_features' ),
		array(
			'title' => array(
				'label' => __( 'عنوان', 'erfanpro' ),
				'type'  => 'text',
			),
			'desc'  => array(
				'label' => __( 'توضیح', 'erfanpro' ),
				'type'  => 'textarea',
			),
		),
		__( 'افزودن ویژگی', 'erfanpro' )
	);
	erfanpro_field_repeater(
		'service_steps',
		__( 'مراحل اجرا', 'erfanpro' ),
		erfanpro_get_repeater( $id, 'service_steps' ),
		array(
			'number' => array(
				'label' => __( 'شماره', 'erfanpro' ),
				'type'  => 'text',
			),
			'title'  => array(
				'label' => __( 'عنوان', 'erfanpro' ),
				'type'  => 'text',
			),
			'desc'   => array(
				'label' => __( 'توضیح', 'erfanpro' ),
				'type'  => 'textarea',
			),
		),
		__( 'افزودن مرحله', 'erfanpro' )
	);
	erfanpro_field_repeater(
		'service_faq',
		__( 'پرسش‌های متداول خدمت', 'erfanpro' ),
		erfanpro_get_repeater( $id, 'service_faq' ),
		array(
			'question' => array(
				'label' => __( 'سؤال', 'erfanpro' ),
				'type'  => 'text',
			),
			'answer'   => array(
				'label' => __( 'پاسخ', 'erfanpro' ),
				'type'  => 'html',
			),
		),
		__( 'افزودن پرسش', 'erfanpro' )
	);

	$projects = array();
	if ( post_type_exists( 'projects' ) ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'projects',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		foreach ( $q->posts as $pid ) {
			$projects[ $pid ] = get_the_title( $pid );
		}
		wp_reset_postdata();
	}
	erfanpro_field_checklist(
		'service_projects',
		__( 'پروژه‌های مرتبط', 'erfanpro' ),
		$projects,
		erfanpro_get_id_list( $id, 'service_projects' )
	);
	erfanpro_field_text(
		'service_short_name',
		__( 'نام کوتاه (چیپ‌های آرشیو)', 'erfanpro' ),
		get_post_meta( $id, 'service_short_name', true ),
		__( 'پیش‌فرض: عنوان بدون پیشوند «نورپردازی»', 'erfanpro' )
	);
	erfanpro_field_text(
		'service_kicker',
		__( 'نشان‌واره ردیف آرشیو', 'erfanpro' ),
		get_post_meta( $id, 'service_kicker', true ),
		__( 'بج بالای عنوان در آرشیو خدمات؛ پیش‌فرض: خلاصه', 'erfanpro' )
	);
	erfanpro_field_text(
		'service_subtitle',
		__( 'زیرعنوان (هیروی تکی)', 'erfanpro' ),
		get_post_meta( $id, 'service_subtitle', true ),
		__( 'بج هیرو + کپشن تصویر؛ پیش‌فرض: خلاصه', 'erfanpro' )
	);
	erfanpro_field_text(
		'service_badge',
		__( 'بج تصویر هیرو', 'erfanpro' ),
		get_post_meta( $id, 'service_badge', true ),
		__( 'پیش‌فرض: IP67/IP68', 'erfanpro' )
	);
	erfanpro_field_text(
		'service_note',
		__( 'جعبه «نکته مهم» زیر ویژگی‌ها', 'erfanpro' ),
		get_post_meta( $id, 'service_note', true )
	);
}

/**
 * Save services meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function erfanpro_save_services_meta( $post_id, $post ) {
	$data = erfanpro_meta_save_guard( $post_id );
	if ( null === $data ) {
		return;
	}

	erfanpro_update_csv_ids( $post_id, 'service_gallery', explode( ',', (string) ( $data['service_gallery'] ?? '' ) ) );
	erfanpro_update_repeater(
		$post_id,
		'service_features',
		erfanpro_sanitize_repeater_input( $data['service_features'] ?? array(), array( 'title' => 'text', 'desc' => 'textarea' ) )
	);
	erfanpro_update_repeater(
		$post_id,
		'service_steps',
		erfanpro_sanitize_repeater_input( $data['service_steps'] ?? array(), array( 'number' => 'text', 'title' => 'text', 'desc' => 'textarea' ) )
	);
	erfanpro_update_repeater(
		$post_id,
		'service_faq',
		erfanpro_sanitize_repeater_input( $data['service_faq'] ?? array(), array( 'question' => 'text', 'answer' => 'html' ) )
	);
	erfanpro_update_id_array( $post_id, 'service_projects', $data['service_projects'] ?? array() );
	foreach ( array( 'service_short_name', 'service_kicker', 'service_subtitle', 'service_badge', 'service_note' ) as $key ) {
		update_post_meta( $post_id, $key, sanitize_text_field( (string) ( $data[ $key ] ?? '' ) ) );
	}
}
add_action( 'save_post_services', 'erfanpro_save_services_meta', 10, 2 );

/**
 * Read helpers for templates (phases 7+).
 */

/**
 * Service gallery attachment IDs.
 *
 * @param int $post_id Service post ID.
 * @return int[]
 */
function erfanpro_service_gallery( $post_id ) {
	return erfanpro_get_id_list( $post_id, 'service_gallery' );
}

/**
 * Service feature/step/FAQ items.
 *
 * @param int    $post_id Service post ID.
 * @param string $key     service_features|service_steps|service_faq.
 * @return array
 */
function erfanpro_service_items( $post_id, $key ) {
	return erfanpro_get_repeater( $post_id, $key );
}

/**
 * Related project IDs of a service.
 *
 * @param int $post_id Service post ID.
 * @return int[]
 */
function erfanpro_service_projects( $post_id ) {
	return erfanpro_get_id_list( $post_id, 'service_projects' );
}
