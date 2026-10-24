<?php
/**
 * Phase 9 — contact form submission handler.
 *
 * The visible form lives in template-parts/contact/form.php. Both
 * submission routes (AJAX via admin-ajax.php and the no-JS fallback via
 * admin-post.php) funnel into erfanpro_save_lead() from inc/leads.php
 * — no new post type, no new hook: the lead is stored as
 * `erfanpro_lead` with form_id `contact-main`, and the
 * `erfanpro_lead_saved` action fires there for the future per-lead SMS
 * integration (decision 24 / BO-006).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validate the nonce and save the posted contact form as a lead.
 *
 * @param array $post Raw POST data (already unslashed by the caller).
 * @return int|WP_Error Lead post ID, or error.
 */
function erfanpro_contact_form_save( $post ) {
	if ( ! isset( $post['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $post['_wpnonce'] ), 'erfanpro-contact-form' ) ) {
		return new WP_Error( 'nonce', __( 'نشست منقضی شده است؛ لطفاً صفحه را تازه‌سازی کنید.', 'erfanpro' ) );
	}

	return erfanpro_save_lead(
		array(
			'name'     => isset( $post['name'] ) ? wp_unslash( $post['name'] ) : '',
			'mobile'   => isset( $post['mobile'] ) ? wp_unslash( $post['mobile'] ) : '',
			'subject'  => isset( $post['subject'] ) ? wp_unslash( $post['subject'] ) : '',
			'message'  => isset( $post['message'] ) ? wp_unslash( $post['message'] ) : '',
			'form_id'  => isset( $post['form_id'] ) ? wp_unslash( $post['form_id'] ) : '',
			'honeypot' => isset( $post['erfanpro_website'] ) ? wp_unslash( $post['erfanpro_website'] ) : '',
		)
	);
}

/**
 * AJAX handler: wp_ajax_erfanpro_contact_form (+ nopriv).
 */
function erfanpro_contact_form_ajax() {
	$result = erfanpro_contact_form_save( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified inside erfanpro_contact_form_save().
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( array( 'message' => $result->get_error_message() ) );
	}
	wp_send_json_success( array( 'message' => __( 'درخواست شما با موفقیت ثبت شد؛ کارشناسان ما به‌زودی با شما تماس می‌گیرند.', 'erfanpro' ) ) );
}
add_action( 'wp_ajax_erfanpro_contact_form', 'erfanpro_contact_form_ajax' );
add_action( 'wp_ajax_nopriv_erfanpro_contact_form', 'erfanpro_contact_form_ajax' );

/**
 * No-JS fallback handler: admin_post_erfanpro_contact_form (+ nopriv).
 * Redirects back to the referrer with a status flag.
 */
function erfanpro_contact_form_post() {
	$result   = erfanpro_contact_form_save( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified inside erfanpro_contact_form_save().
	$referrer = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$flag     = is_wp_error( $result ) ? 'error' : 'sent';
	wp_safe_redirect( add_query_arg( 'erfanpro_contact', $flag, $referrer ) );
	exit;
}
add_action( 'admin_post_erfanpro_contact_form', 'erfanpro_contact_form_post' );
add_action( 'admin_post_nopriv_erfanpro_contact_form', 'erfanpro_contact_form_post' );
