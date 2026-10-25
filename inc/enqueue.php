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

	/* Phase 11: header live search (decision 20). The search bar is in
	 * the header on every page, so this small script loads site-wide;
	 * the GET form remains the fallback when JS or the request fails. */
	wp_enqueue_script(
		'erfanpro-header-search',
		ERFANPRO_URI . '/assets/js/header-search.js',
		array(),
		ERFANPRO_VERSION,
		true
	);
	wp_localize_script(
		'erfanpro-header-search',
		'erfanproSearch',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'erfanpro-live-search' ),
		)
	);

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

	/* Phase 4: shop AJAX filter/search/sort (decisions 14 + 20). */
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		wp_enqueue_script(
			'erfanpro-shop',
			ERFANPRO_URI . '/assets/js/shop.js',
			array(),
			ERFANPRO_VERSION,
			true
		);
		wp_localize_script(
			'erfanpro-shop',
			'erfanproShop',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'erfanpro-shop-filter' ),
			)
		);
	}
	/* Phase 6: projects archive — city + type AJAX filter. */
	if ( is_post_type_archive( 'projects' ) || is_tax( 'city' ) || is_tax( 'project_type' ) ) {
		wp_enqueue_script(
			'erfanpro-projects',
			ERFANPRO_URI . '/assets/js/projects.js',
			array(),
			ERFANPRO_VERSION,
			true
		);
		wp_localize_script(
			'erfanpro-projects',
			'erfanproProjects',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'erfanpro-projects-filter' ),
			)
		);
	}
	/* Phase 5: single product — gallery video slide + rating form (D15/D18). */
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_script(
			'erfanpro-product',
			ERFANPRO_URI . '/assets/js/product.js',
			array( 'erfanpro-main' ),
			ERFANPRO_VERSION,
			true
		);
		wp_localize_script(
			'erfanpro-product',
			'erfanproProduct',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}
	/* Phase 8: blog archive — category chips + live search + pagination (posts only). */
	if ( is_home() ) {
		wp_enqueue_style(
			'erfanpro-blog',
			ERFANPRO_URI . '/assets/css/blog.css',
			array( 'erfanpro-app' ),
			ERFANPRO_VERSION
		);
		wp_enqueue_script(
			'erfanpro-blog',
			ERFANPRO_URI . '/assets/js/blog.js',
			array(),
			ERFANPRO_VERSION,
			true
		);
		wp_localize_script(
			'erfanpro-blog',
			'erfanproBlog',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'erfanpro-blog-search' ),
			)
		);
	}
	/* Phase 9: contact page — AJAX form submission (leads infra). */
	if ( is_page() ) {
		$erfanpro_page_obj = get_queried_object();
		$erfanpro_is_contact = $erfanpro_page_obj instanceof WP_Post
			&& ( 'page-contact.php' === get_page_template_slug( $erfanpro_page_obj->ID )
				|| 'contact' === $erfanpro_page_obj->post_name );
		if ( $erfanpro_is_contact ) {
			wp_enqueue_script(
				'erfanpro-contact',
				ERFANPRO_URI . '/assets/js/contact.js',
				array(),
				ERFANPRO_VERSION,
				true
			);
			wp_localize_script(
				'erfanpro-contact',
				'erfanproContact',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'error'   => __( 'ارسال درخواست ناموفق بود؛ لطفاً دوباره تلاش کنید.', 'erfanpro' ),
				)
			);
		}
	}
	/* Phase 8: single post — article typography. */
	if ( is_singular( 'post' ) ) {
		wp_enqueue_style(
			'erfanpro-blog',
			ERFANPRO_URI . '/assets/css/blog.css',
			array( 'erfanpro-app' ),
			ERFANPRO_VERSION
		);
	}
	/* Phase 10: WooCommerce cart / checkout / account styling. */
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_style(
			'erfanpro-woocommerce',
			ERFANPRO_URI . '/assets/css/woocommerce.css',
			array( 'erfanpro-app' ),
			ERFANPRO_VERSION
		);
	}
	/* Phase 10: site FAQ accordion (page-faq.php template). */
	if ( is_page() ) {
		$erfanpro_page_obj = get_queried_object();
		$erfanpro_is_faq = $erfanpro_page_obj instanceof WP_Post
			&& 'page-faq.php' === get_page_template_slug( $erfanpro_page_obj->ID );
		if ( $erfanpro_is_faq ) {
			wp_enqueue_script(
				'erfanpro-faq',
				ERFANPRO_URI . '/assets/js/faq.js',
				array(),
				ERFANPRO_VERSION,
				true
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'erfanpro_enqueue_assets' );

/**
 * Phase 1: custom uploaded fonts (decision 34 / BO-004) override the
 * bundled Vazirmatn / Lalezar. The @font-face rules print after
 * fonts.css via the same handle, so they win the cascade.
 *
 * @return string @font-face rules for the active custom fonts ('' if none).
 */
function erfanpro_custom_fonts_css_string() {
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
	return $css;
}

function erfanpro_custom_fonts_css() {
	$css = erfanpro_custom_fonts_css_string();
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
	if ( $is_settings_screen ) {
		// Versions are file mtimes, not the theme constant: the admin
		// CSS/JS changed across builds while ERFANPRO_VERSION stayed
		// '0.1.0', so browsers kept serving stale cached files under
		// the same ?ver= URL (the tab-panel hide rules never arrived).
		// A new mtime means a new URL, so caches can never go stale.
		// Local Vazirmatn only on this settings page (fonts.css is
		// @font-face declarations only, zero external requests).
		wp_enqueue_style(
			'erfanpro-admin-fonts',
			ERFANPRO_URI . '/assets/css/fonts.css',
			array(),
			filemtime( ERFANPRO_DIR . '/assets/css/fonts.css' )
		);
		// Custom uploaded fonts also override the bundled ones here, so
		// the fonts tab's live previews render in the active font.
		$custom_fonts_css = erfanpro_custom_fonts_css_string();
		if ( $custom_fonts_css ) {
			wp_add_inline_style( 'erfanpro-admin-fonts', $custom_fonts_css );
		}
		wp_enqueue_style(
			'erfanpro-admin-options',
			ERFANPRO_URI . '/assets/css/admin-options.css',
			array( 'erfanpro-admin-fonts' ),
			filemtime( ERFANPRO_DIR . '/assets/css/admin-options.css' )
		);
		wp_enqueue_script(
			'erfanpro-admin-tabs',
			ERFANPRO_URI . '/assets/js/admin-tabs.js',
			array(),
			filemtime( ERFANPRO_DIR . '/assets/js/admin-tabs.js' ),
			true
		);
		wp_enqueue_script(
			'erfanpro-admin-sections',
			ERFANPRO_URI . '/assets/js/admin-sections.js',
			array(),
			filemtime( ERFANPRO_DIR . '/assets/js/admin-sections.js' ),
			true
		);
		wp_localize_script(
			'erfanpro-admin-tabs',
			'erfanproTabs',
			array(
				'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'erfanpro_tab_save' ),
				'save'          => __( 'ذخیرهٔ تب فعال', 'erfanpro' ),
				'saving'        => __( 'در حال ذخیره…', 'erfanpro' ),
				'saved'         => __( 'ذخیره شد ✓', 'erfanpro' ),
				'error'         => __( 'خطا در ذخیره. دوباره تلاش کنید.', 'erfanpro' ),
				'fontInvalid'   => __( 'فقط فایل‌های woff2، woff، ttf یا otf مجاز‌اند.', 'erfanpro' ),
				'fontSelected'  => __( 'فایل انتخاب‌شده: %s — هنوز ذخیره نشده است.', 'erfanpro' ),
				'fontCustom'    => __( 'سفارشی', 'erfanpro' ),
				'fontDefault'   => __( 'پیش‌فرض', 'erfanpro' ),
				'fontNotSaved'  => __( 'تب ذخیره شد، اما فونت ذخیره نشد:', 'erfanpro' ),
			)
		);
	}
	wp_enqueue_script(
		'erfanpro-admin',
		ERFANPRO_URI . '/assets/js/admin-metabox.js',
		array(),
		ERFANPRO_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'erfanpro_admin_assets' );
