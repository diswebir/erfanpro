<?php
/**
 * Tabbed settings: AJAX save for one tab at a time.
 *
 * The options page is one form (all tabs in one DOM), but the floating
 * save button stores only the ACTIVE tab via admin-ajax. The merge rule
 * keeps every other tab untouched:
 *
 * - The tab's submitted values overlay the stored option.
 * - `section_texts` merges per group, because its groups live in
 *   several tabs; every other key is replaced wholesale, because each
 *   lives in exactly one tab (proven by the admin test's tab-isolation
 *   check).
 * - The merged input then runs through the existing full sanitizer
 *   (erfanpro_options_sanitize), so tab saves sanitize exactly like
 *   full-form saves — including the section-manager marker rule: tabs
 *   other than the manager tab never send the marker, so
 *   erfanpro_sections_sanitize keeps the stored page_sections.
 * - Checkboxes inside the saved tab that arrive unchecked are sent by
 *   the JS as '0', which the sanitizers read as off (via ! empty()).
 *   Fields from other tabs are never sent, so they are never offed.
 * - The fonts tab's success payload also carries the saved fonts'
 *   status (name/format/size/custom-or-default) plus any font-upload
 *   rejection messages, so its cards refresh without a reload.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Overlay one tab's submitted input over the stored option.
 *
 * @param array $stored    Previously saved erfanpro_options.
 * @param array $tab_input Submitted erfanpro_options subtree for one tab.
 * @return array Merged input, ready for the full sanitizer.
 */
function erfanpro_ajax_merge_tab_input( $stored, $tab_input ) {
	if ( ! is_array( $stored ) ) {
		$stored = array();
	}
	if ( ! is_array( $tab_input ) ) {
		$tab_input = array();
	}
	$merged = $stored;
	foreach ( $tab_input as $key => $value ) {
		$key = sanitize_key( $key );
		if ( 'section_texts' === $key && is_array( $value ) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
			foreach ( $value as $group_key => $group_value ) {
				$merged[ $key ][ sanitize_key( $group_key ) ] = $group_value;
			}
		} else {
			$merged[ $key ] = $value;
		}
	}
	return $merged;
}

/**
 * Save one tab without dying (core logic, used by the AJAX endpoint
 * and by tests).
 *
 * @param string $tab       Tab id from erfanpro_options_groups().
 * @param array  $tab_input Submitted erfanpro_options subtree for the tab.
 * @return array { ok: bool, message: string }.
 */
function erfanpro_ajax_save_tab_data( $tab, $tab_input ) {
	$groups = erfanpro_options_groups();
	if ( ! isset( $groups[ $tab ] ) ) {
		return array(
			'ok'      => false,
			'message' => __( 'تب نامعتبر است.', 'erfanpro' ),
		);
	}
	if ( ! is_array( $tab_input ) ) {
		return array(
			'ok'      => false,
			'message' => __( 'داده‌ی تب نامعتبر است.', 'erfanpro' ),
		);
	}
	$stored = get_option( 'erfanpro_options', array() );
	if ( ! is_array( $stored ) ) {
		$stored = array();
	}
	$merged = erfanpro_ajax_merge_tab_input( $stored, $tab_input );
	$clean  = erfanpro_options_sanitize( $merged );
	update_option( 'erfanpro_options', $clean );
	return array(
		'ok'      => true,
		'message' => __( 'تغییرات این تب ذخیره شد.', 'erfanpro' ),
	);
}

/**
 * AJAX endpoint: erfanpro_save_tab.
 *
 * POST: nonce (erfanpro_tab_save), tab (group id), erfanpro_options
 * (nested fields of the active tab only). Replies JSON with a Persian
 * message; never dies with a bare -1/0.
 */
function erfanpro_ajax_save_tab() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return wp_send_json_error(
			array( 'message' => __( 'شما اجازه‌ی ذخیره‌ی تنظیمات قالب را ندارید.', 'erfanpro' ) ),
			403
		);
	}
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified below with wp_verify_nonce.
	if ( ! wp_verify_nonce( $nonce, 'erfanpro_tab_save' ) ) {
		return wp_send_json_error(
			array( 'message' => __( 'نشانه‌ی امنیتی نامعتبر است. صفحه را تازه‌سازی کنید و دوباره تلاش کنید.', 'erfanpro' ) ),
			403
		);
	}
	$tab       = isset( $_POST['tab'] ) ? sanitize_key( wp_unslash( $_POST['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified above.
	$tab_input = isset( $_POST['erfanpro_options'] ) && is_array( $_POST['erfanpro_options'] ) ? wp_unslash( $_POST['erfanpro_options'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified above.
	$result    = erfanpro_ajax_save_tab_data( $tab, $tab_input );
	if ( ! $result['ok'] ) {
		return wp_send_json_error(
			array( 'message' => $result['message'] ),
			400
		);
	}
	$data = array( 'message' => $result['message'] );
	if ( 'fonts' === $tab ) {
		/* The fonts tab's cards refresh from this without a reload:
		 * new file name/format/size, custom/default badge, and the
		 * URL the live preview's @font-face points at. */
		$data['fonts'] = erfanpro_fonts_status();
	}
	/* A rejected font upload keeps the previous font (see the
	 * sanitizer); the tab itself still saved, so report the reason
	 * alongside the success instead of failing silently. */
	$font_errors = $GLOBALS['erfanpro_font_errors'] ?? array();
	if ( $font_errors ) {
		$data['font_errors'] = array_values( array_unique( (array) $font_errors ) );
	}
	return wp_send_json_success( $data );
}
add_action( 'wp_ajax_erfanpro_save_tab', 'erfanpro_ajax_save_tab' );
