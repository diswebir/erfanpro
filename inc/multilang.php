<?php
/**
 * Phase 1 — data layer: manual en/ar links + hreflang (admin build order
 * BO-005, project decision 9).
 *
 * Each fa page can point at its hand-written en/ar counterparts via a
 * sidebar metabox (`erfanpro_link_en`, `erfanpro_link_ar`). The header
 * language switcher (phase 2) and the SEO hreflang tags read these.
 * The existing `hrefmeta_*` keys in the database are NEVER touched.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the language-links metabox on every public post type.
 */
function erfanpro_lang_metabox() {
	foreach ( get_post_types( array( 'public' => true ), 'names' ) as $type ) {
		if ( 'attachment' === $type ) {
			continue;
		}
		add_meta_box(
			'erfanpro-lang-links',
			__( 'نسخه‌های زبانی', 'erfanpro' ),
			'erfanpro_lang_box',
			$type,
			'side',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'erfanpro_lang_metabox' );

/**
 * Render the box.
 *
 * @param WP_Post $post Post object.
 */
function erfanpro_lang_box( $post ) {
	wp_nonce_field( 'erfanpro_save_lang_' . $post->ID, 'erfanpro_lang_nonce' );
	$en = get_post_meta( $post->ID, 'erfanpro_link_en', true );
	$ar = get_post_meta( $post->ID, 'erfanpro_link_ar', true );
	?>
	<p>
		<label for="erfanpro-link-en"><strong><?php esc_html_e( 'نسخه انگلیسی', 'erfanpro' ); ?></strong></label><br />
		<input type="url" id="erfanpro-link-en" name="erfanpro_link_en" value="<?php echo esc_attr( (string) $en ); ?>" class="widefat" dir="ltr" placeholder="https://" />
	</p>
	<p>
		<label for="erfanpro-link-ar"><strong><?php esc_html_e( 'نسخه عربی', 'erfanpro' ); ?></strong></label><br />
		<input type="url" id="erfanpro-link-ar" name="erfanpro_link_ar" value="<?php echo esc_attr( (string) $ar ); ?>" class="widefat" dir="ltr" placeholder="https://" />
	</p>
	<p class="description"><?php esc_html_e( 'خالی = فقط نسخه فارسی. این لینک‌ها در سوییچر زبان هدر و تگ‌های hreflang استفاده می‌شوند.', 'erfanpro' ); ?></p>
	<?php
}

/**
 * Save the language links.
 *
 * @param int $post_id Post ID.
 */
function erfanpro_save_lang_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['erfanpro_lang_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['erfanpro_lang_nonce'] ) ), 'erfanpro_save_lang_' . $post_id )
	) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, 'erfanpro_link_en', esc_url_raw( (string) ( wp_unslash( $_POST['erfanpro_link_en'] ?? '' ) ) ) );
	update_post_meta( $post_id, 'erfanpro_link_ar', esc_url_raw( (string) ( wp_unslash( $_POST['erfanpro_link_ar'] ?? '' ) ) ) );
}
add_action( 'save_post', 'erfanpro_save_lang_meta' );

/**
 * URL of a language version of the current (or given) post.
 * fa = the page itself.
 *
 * @param string   $lang    fa|en|ar.
 * @param int|null $post_id Post ID; null = current.
 * @return string URL (may be empty for en/ar when not set).
 */
function erfanpro_lang_url( $lang, $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_queried_object_id();
	}
	if ( 'fa' === $lang ) {
		return $post_id ? (string) get_permalink( $post_id ) : home_url( '/' );
	}
	if ( ! $post_id || ! in_array( $lang, array( 'en', 'ar' ), true ) ) {
		return '';
	}
	return (string) get_post_meta( $post_id, 'erfanpro_link_' . $lang, true );
}

/**
 * Print hreflang alternates in <head> — only for languages with a manual
 * URL set. Neutral toward RankMath/SEOPress: no SEO meta is duplicated.
 */
function erfanpro_hreflang_head() {
	if ( ! is_singular() ) {
		return;
	}
	$post_id = get_queried_object_id();
	if ( ! $post_id ) {
		return;
	}
	$links = array( 'fa' => (string) get_permalink( $post_id ) );
	foreach ( array( 'en', 'ar' ) as $lang ) {
		$url = erfanpro_lang_url( $lang, $post_id );
		if ( $url ) {
			$links[ $lang ] = $url;
		}
	}
	foreach ( $links as $lang => $url ) {
		printf(
			'<link rel="alternate" hreflang="%s" href="%s" />' . "\n",
			esc_attr( $lang ),
			esc_url( $url )
		);
	}
}
add_action( 'wp_head', 'erfanpro_hreflang_head', 5 );
