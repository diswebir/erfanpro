<?php
/**
 * Phase 11: header live search (decision 20, JetSearch-style).
 *
 * The header search bar keeps its plain GET form as the no-JS fallback;
 * when JavaScript is available, typing 2+ characters fetches matching
 * products, projects and posts through admin-ajax and shows them in a
 * dropdown under the input. Read-only and public (no capability or
 * content is exposed beyond what the public search page already shows).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX handler: wp_ajax_erfanpro_live_search (+ nopriv).
 *
 * @return void
 */
function erfanpro_live_search_ajax() {
	check_ajax_referer( 'erfanpro-live-search', 'nonce' );

	$query = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified above.
	if ( mb_strlen( $query ) < 2 ) {
		wp_send_json_success( array( 'results' => array() ) );
	}

	$results = array();

	$types = array(
		'product'  => __( 'محصول', 'erfanpro' ),
		'projects' => __( 'پروژه', 'erfanpro' ),
		'post'     => __( 'مقاله', 'erfanpro' ),
	);
	$limits = array(
		'product'  => 5,
		'projects' => 3,
		'post'     => 3,
	);

	foreach ( $types as $post_type => $label ) {
		/* Skip post types that are not registered (e.g. no WooCommerce). */
		if ( ! post_type_exists( $post_type ) ) {
			continue;
		}
		$found = new WP_Query(
			array(
				'post_type'      => $post_type,
				's'              => $query,
				'posts_per_page' => $limits[ $post_type ],
				'post_status'    => 'publish',
				'no_found_rows'  => true,
			)
		);
		foreach ( $found->posts as $found_post ) {
			$results[] = array(
				'title' => get_the_title( $found_post ),
				'url'   => get_permalink( $found_post ),
				'type'  => $label,
			);
		}
		wp_reset_postdata();
	}

	wp_send_json_success( array( 'results' => $results ) );
}
add_action( 'wp_ajax_erfanpro_live_search', 'erfanpro_live_search_ajax' );
add_action( 'wp_ajax_nopriv_erfanpro_live_search', 'erfanpro_live_search_ajax' );
