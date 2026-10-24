<?php
/**
 * Phase 1 — data layer: unique-visit view counter (admin build order BO-007,
 * project decision 16).
 *
 * Counts on the blueprint's own keys — `number-viewc` on post / product /
 * projects / services, plus `number-view` on product — without renaming
 * anything. A visit counts once per visitor per 24h (cookie, with an
 * IP+UA transient fallback for cookie-less clients). Each post shows its
 * current values in a sidebar metabox with an AJAX reset button
 * (nonce + edit_post capability).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post types tracked by the counter.
 *
 * @return string[]
 */
function erfanpro_counter_post_types() {
	$types = array( 'post', 'product', 'services' );
	if ( post_type_exists( 'projects' ) ) {
		$types[] = 'projects';
	}
	return apply_filters( 'erfanpro_counter_post_types', $types );
}

/**
 * Current view count for a post (blueprint key `number-viewc`).
 *
 * @param int $post_id Post ID.
 * @return int
 */
function erfanpro_get_views( $post_id ) {
	return absint( get_post_meta( $post_id, 'number-viewc', true ) );
}

/**
 * Count one unique visit on singular views.
 */
function erfanpro_count_unique_view() {
	if ( is_admin() || ! is_singular( erfanpro_counter_post_types() ) ) {
		return;
	}
	$post_id = get_queried_object_id();
	if ( ! $post_id ) {
		return;
	}

	$cookie = 'erfanpro_vc_' . $post_id;
	if ( isset( $_COOKIE[ $cookie ] ) ) {
		return;
	}
	// Fallback for cookie-less clients: IP + user-agent, 24h window.
	$hash = md5( (string) wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) . '|' . (string) wp_unslash( $_SERVER['HTTP_USER_AGENT'] ?? '' ) . '|' . $post_id );
	if ( get_transient( 'erfanpro_vc_' . $hash ) ) {
		return;
	}

	$views = erfanpro_get_views( $post_id ) + 1;
	update_post_meta( $post_id, 'number-viewc', $views );
	if ( 'product' === get_post_type( $post_id ) ) {
		update_post_meta( $post_id, 'number-view', $views );
	}

	setcookie( $cookie, '1', time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
	set_transient( 'erfanpro_vc_' . $hash, 1, DAY_IN_SECONDS );
}
add_action( 'template_redirect', 'erfanpro_count_unique_view' );

/**
 * Sidebar metabox: current values + reset button.
 */
function erfanpro_views_metabox() {
	foreach ( erfanpro_counter_post_types() as $type ) {
		add_meta_box(
			'erfanpro-views',
			__( 'شمارنده بازدید', 'erfanpro' ),
			'erfanpro_views_box',
			$type,
			'side',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'erfanpro_views_metabox' );

/**
 * Render the views box.
 *
 * @param WP_Post $post Post object.
 */
function erfanpro_views_box( $post ) {
	$id    = $post->ID;
	$views = erfanpro_get_views( $id );
	wp_nonce_field( 'erfanpro_reset_views_' . $id, 'erfanpro_views_nonce' );
	?>
	<div class="erfanpro-views-box">
		<p>
			<span class="description"><?php esc_html_e( 'بازدید یکتا (number-viewc)', 'erfanpro' ); ?></span><br />
			<span class="erfanpro-view-num"><?php echo esc_html( number_format_i18n( $views ) ); ?></span>
		</p>
		<?php if ( 'product' === $post->post_type ) : ?>
			<p>
				<span class="description"><?php esc_html_e( 'number-view', 'erfanpro' ); ?></span><br />
				<span class="erfanpro-view-num"><?php echo esc_html( number_format_i18n( absint( get_post_meta( $id, 'number-view', true ) ) ) ); ?></span>
			</p>
		<?php endif; ?>
		<button type="button" class="button" id="erfanpro-reset-views" data-post-id="<?php echo esc_attr( (string) $id ); ?>">
			<?php esc_html_e( 'ریست شمارنده', 'erfanpro' ); ?>
		</button>
		<span id="erfanpro-reset-views-result" aria-live="polite"></span>
	</div>
	<script>
	(function () {
		var btn = document.getElementById('erfanpro-reset-views');
		if (!btn || btn.dataset.bound) return;
		btn.dataset.bound = '1';
		btn.addEventListener('click', function () {
			var result = document.getElementById('erfanpro-reset-views-result');
			result.textContent = '…';
			var data = new FormData();
			data.append('action', 'erfanpro_reset_views');
			data.append('post_id', btn.dataset.postId);
			data.append('nonce', document.getElementById('erfanpro_views_nonce').value);
			fetch(ajaxurl, { method: 'POST', body: data, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if (res.success) {
						result.textContent = '<?php echo esc_js( __( 'صفر شد.', 'erfanpro' ) ); ?>';
						btn.closest('.erfanpro-views-box').querySelectorAll('.erfanpro-view-num').forEach(function (el) {
							el.textContent = '۰';
						});
					} else {
						result.textContent = '<?php echo esc_js( __( 'خطا', 'erfanpro' ) ); ?>';
					}
				})
				.catch(function () { result.textContent = '<?php echo esc_js( __( 'خطا', 'erfanpro' ) ); ?>'; });
		});
	})();
	</script>
	<?php
}

/**
 * AJAX: reset the view counter for one post.
 */
function erfanpro_ajax_reset_views() {
	$post_id = absint( $_POST['post_id'] ?? 0 );
	$nonce   = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : '';

	if ( ! $post_id || ! wp_verify_nonce( $nonce, 'erfanpro_reset_views_' . $post_id ) ) {
		wp_send_json_error( array( 'message' => 'bad nonce' ), 403 );
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( array( 'message' => 'forbidden' ), 403 );
	}

	update_post_meta( $post_id, 'number-viewc', 0 );
	if ( 'product' === get_post_type( $post_id ) ) {
		update_post_meta( $post_id, 'number-view', 0 );
	}
	wp_send_json_success();
}
add_action( 'wp_ajax_erfanpro_reset_views', 'erfanpro_ajax_reset_views' );
