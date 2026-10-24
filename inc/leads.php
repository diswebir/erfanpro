<?php
/**
 * Phase 1 — data layer: contact leads (admin build order BO-006,
 * project decision 24).
 *
 * Leads are stored in the database as the internal `erfanpro_lead` post
 * type (never public, admin UI only). Each form carries its own unique
 * `form_id` (e.g. `contact-main`). After a lead is saved the
 * `erfanpro_lead_saved` action fires so the SMS integration can hook in
 * later (per-lead configuration). Captcha is NOT implemented now
 * (decision 39) — the `erfanpro_captcha_html` filter and its injection
 * point are ready for it.
 *
 * The visible contact form + its AJAX handler land in phase 9 with the
 * contact page template; this file is the storage infrastructure it
 * will call via erfanpro_save_lead().
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the internal leads post type.
 */
function erfanpro_register_lead_cpt() {
	register_post_type(
		'erfanpro_lead',
		array(
			'labels'              => array(
				'name'          => __( 'لیدهای تماس', 'erfanpro' ),
				'singular_name' => __( 'لید', 'erfanpro' ),
				'menu_name'     => __( 'لیدهای تماس', 'erfanpro' ),
			),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'       => false,
			'menu_icon'           => 'dashicons-feedback',
			'menu_position'       => 30,
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		)
	);
}
add_action( 'init', 'erfanpro_register_lead_cpt' );

/**
 * Admin columns for leads.
 *
 * @param array $columns Columns.
 * @return array
 */
function erfanpro_lead_columns( $columns ) {
	return array(
		'cb'      => $columns['cb'],
		'title'   => __( 'نام', 'erfanpro' ),
		'mobile'  => __( 'موبایل', 'erfanpro' ),
		'subject' => __( 'موضوع', 'erfanpro' ),
		'form_id' => __( 'شناسه فرم', 'erfanpro' ),
		'date'    => $columns['date'],
	);
}
add_filter( 'manage_erfanpro_lead_posts_columns', 'erfanpro_lead_columns' );

/**
 * Render lead columns.
 *
 * @param string $column Column name.
 * @param int    $post_id Post ID.
 */
function erfanpro_lead_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'mobile':
			echo esc_html( (string) get_post_meta( $post_id, '_erfanpro_lead_mobile', true ) );
			break;
		case 'subject':
			echo esc_html( (string) get_post_meta( $post_id, '_erfanpro_lead_subject', true ) );
			break;
		case 'form_id':
			echo '<code>' . esc_html( (string) get_post_meta( $post_id, '_erfanpro_lead_form_id', true ) ) . '</code>';
			break;
	}
}
add_action( 'manage_erfanpro_lead_posts_custom_column', 'erfanpro_lead_column_content', 10, 2 );

/**
 * Normalize Persian/Arabic digits to Latin.
 *
 * @param string $s Input.
 * @return string
 */
function erfanpro_normalize_digits( $s ) {
	$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	$ar = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
	$en = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
	return str_replace( array_merge( $fa, $ar ), array_merge( $en, $en ), (string) $s );
}

/**
 * Save a contact lead.
 *
 * @param array $data {
 *     @type string $name     Required.
 *     @type string $mobile   Required, Iranian mobile (09…).
 *     @type string $subject  Optional.
 *     @type string $message  Optional.
 *     @type string $form_id  Required, unique per form (e.g. 'contact-main').
 *     @type string $honeypot Must be empty (spam trap).
 * }
 * @return int|WP_Error Lead post ID, or error.
 */
function erfanpro_save_lead( $data ) {
	$data = is_array( $data ) ? $data : array();

	if ( ! empty( $data['honeypot'] ) ) {
		return new WP_Error( 'spam', __( 'درخواست نامعتبر.', 'erfanpro' ) );
	}

	$name    = sanitize_text_field( (string) ( $data['name'] ?? '' ) );
	$mobile  = erfanpro_normalize_digits( sanitize_text_field( (string) ( $data['mobile'] ?? '' ) ) );
	$mobile  = preg_replace( '/[^0-9]/', '', $mobile );
	$subject = sanitize_text_field( (string) ( $data['subject'] ?? '' ) );
	$message = sanitize_textarea_field( (string) ( $data['message'] ?? '' ) );
	$form_id = sanitize_key( (string) ( $data['form_id'] ?? '' ) );

	if ( '' === $name ) {
		return new WP_Error( 'name', __( 'نام الزامی است.', 'erfanpro' ) );
	}
	if ( ! preg_match( '/^09\d{9}$/', $mobile ) ) {
		return new WP_Error( 'mobile', __( 'شماره موبایل معتبر نیست.', 'erfanpro' ) );
	}
	if ( '' === $form_id ) {
		return new WP_Error( 'form_id', __( 'شناسه فرم نامشخص است.', 'erfanpro' ) );
	}

	$lead_id = wp_insert_post(
		array(
			'post_type'   => 'erfanpro_lead',
			'post_title'  => $name,
			'post_status' => 'publish',
			'meta_input'  => array(
				'_erfanpro_lead_mobile'  => $mobile,
				'_erfanpro_lead_subject' => $subject,
				'_erfanpro_lead_message' => $message,
				'_erfanpro_lead_form_id' => $form_id,
				'_erfanpro_lead_date'   => current_time( 'mysql' ),
			),
		),
		true
	);

	if ( is_wp_error( $lead_id ) ) {
		return $lead_id;
	}

	/**
	 * Fires after a lead is stored. Hook the per-lead SMS sender here.
	 *
	 * @param int   $lead_id Lead post ID.
	 * @param array $data    Sanitized lead data (name, mobile, subject, message, form_id).
	 */
	do_action(
		'erfanpro_lead_saved',
		$lead_id,
		array(
			'name'    => $name,
			'mobile'  => $mobile,
			'subject' => $subject,
			'message' => $message,
			'form_id' => $form_id,
		)
	);

	return $lead_id;
}

/**
 * Captcha injection point for forms (decision 39: architecture ready,
 * not implemented). Phase-9 form templates call:
 * `echo apply_filters( 'erfanpro_captcha_html', '', $form_id );`
 *
 * @param string $html    Default empty.
 * @param string $form_id Form ID.
 * @return string
 */
function erfanpro_captcha_passthrough( $html, $form_id ) {
	return $html;
}
add_filter( 'erfanpro_captcha_html', 'erfanpro_captcha_passthrough', 10, 2 );
