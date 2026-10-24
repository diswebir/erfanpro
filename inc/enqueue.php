<?php
/**
 * Asset enqueues. Zero external requests: every font and asset is local.
 *
 * Main stylesheet: the Tailwind build (assets/css/app.build.css) when it
 * exists; otherwise the compiled reference CSS
 * (assets/css/reference-app.css) is loaded as the parity fallback —
 * per project decision 33 each element is checked against the reference
 * and any mismatch falls back to the reference CSS.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end styles and scripts.
 */
function erfanpro_enqueue_assets() {
	wp_enqueue_style(
		'erfanpro-fonts',
		ERFANPRO_URI . '/assets/css/fonts.css',
		array(),
		ERFANPRO_VERSION
	);

	$built = ERFANPRO_DIR . '/assets/css/app.build.css';
	if ( file_exists( $built ) ) {
		wp_enqueue_style(
			'erfanpro-app',
			ERFANPRO_URI . '/assets/css/app.build.css',
			array( 'erfanpro-fonts' ),
			ERFANPRO_VERSION
		);
	} else {
		wp_enqueue_style(
			'erfanpro-app',
			ERFANPRO_URI . '/assets/css/reference-app.css',
			array( 'erfanpro-fonts' ),
			ERFANPRO_VERSION
		);
	}

	wp_enqueue_style(
		'erfanpro-anim',
		ERFANPRO_URI . '/assets/css/anim.css',
		array( 'erfanpro-app' ),
		ERFANPRO_VERSION
	);

	wp_enqueue_script(
		'erfanpro-anim',
		ERFANPRO_URI . '/assets/js/anim.js',
		array(),
		ERFANPRO_VERSION,
		true
	);
	wp_enqueue_script(
		'erfanpro-main',
		ERFANPRO_URI . '/assets/js/main.js',
		array(),
		ERFANPRO_VERSION,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/* Phase 3: services accordion on the front page only. */
	if ( is_front_page() ) {
		wp_enqueue_script(
			'erfanpro-home',
			ERFANPRO_URI . '/assets/js/home.js',
			array(),
			ERFANPRO_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'erfanpro_enqueue_assets' );

/**
 * Phase 1: custom uploaded fonts (decision 34 / BO-004) override the
 * bundled Vazirmatn / Lalezar. The @font-face rules print after
 * fonts.css via the same handle, so they win the cascade.
 */
function erfanpro_custom_fonts_css() {
	$map = array(
		'font_primary_file' => 'Vazirmatn',
		'font_display_file' => 'Lalezar',
	);
	$css = '';
	foreach ( $map as $opt_key => $family ) {
		$url = erfanpro_option( $opt_key, '' );
		if ( ! $url ) {
			continue;
		}
		$ext    = strtolower( pathinfo( (string) wp_parse_url( $url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
		$format = array(
			'woff2' => 'woff2',
			'woff'  => 'woff',
			'ttf'   => 'truetype',
			'otf'   => 'opentype',
		)[ $ext ] ?? 'woff2';
		$css   .= sprintf(
			"@font-face{font-family:'%s';font-style:normal;font-weight:100 900;font-display:swap;src:url('%s') format('%s');}\n",
			$family,
			esc_url( $url ),
			$format
		);
	}
	if ( $css ) {
		wp_add_inline_style( 'erfanpro-fonts', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'erfanpro_custom_fonts_css', 20 );

/**
 * Phase 1: admin assets for metaboxes + theme settings
 * (repeaters, media pickers).
 *
 * @param string $hook Current admin page hook.
 */
function erfanpro_admin_assets( $hook ) {
	$is_post_screen     = in_array( $hook, array( 'post.php', 'post-new.php' ), true );
	$is_settings_screen = 'appearance_page_erfanpro-settings' === $hook;
	if ( ! $is_post_screen && ! $is_settings_screen ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style(
		'erfanpro-admin',
		ERFANPRO_URI . '/assets/css/admin-metabox.css',
		array(),
		ERFANPRO_VERSION
	);
	wp_enqueue_script(
		'erfanpro-admin',
		ERFANPRO_URI . '/assets/js/admin-metabox.js',
		array(),
		ERFANPRO_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'erfanpro_admin_assets' );
