<?php
/**
 * Theme options page — a single `erfanpro_options` key in wp_options.
 *
 * Phase 0: general fields (phone, WhatsApp, catalog fallback, footer,
 * socials). Phase 1 adds the admin-coverage build orders:
 * - BO-001 trust badges repeater (decision 27)
 * - BO-002 home counters with real value + offset (decision 22)
 * - BO-003 home section texts (6 sections)
 * - BO-004 custom font uploads replacing Vazirmatn/Lalezar (decision 34)
 * - BO-010 city marquee (source / manual list / speed)
 * - BO-013 about + contact page texts (about_texts / contact_texts)
 * - BO-014 five simple page texts (minor_pages_texts)
 *
 * Repeaters share the markup/JS convention of the post metaboxes
 * (assets/js/admin-metabox.js, data-erfanpro-repeater).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the options page under Appearance.
 */
function erfanpro_options_menu() {
	add_theme_page(
		__( 'تنظیمات قالب erfanpro', 'erfanpro' ),
		__( 'تنظیمات erfanpro', 'erfanpro' ),
		'edit_theme_options',
		'erfanpro-settings',
		'erfanpro_options_render'
	);
}
add_action( 'admin_menu', 'erfanpro_options_menu' );

/**
 * Register the setting and its fields.
 */
function erfanpro_options_init() {
	register_setting(
		'erfanpro_options_group',
		'erfanpro_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'erfanpro_options_sanitize',
			'default'           => array(),
		)
	);

	/* ---- general (phase 0) ---- */
	add_settings_section(
		'erfanpro_general',
		__( 'تنظیمات عمومی', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);

	$fields = array(
		'phone'            => __( 'تلفن تماس (مثال: 031-91091011)', 'erfanpro' ),
		'whatsapp'         => __( 'شماره واتساپ', 'erfanpro' ),
		'email'            => __( 'ایمیل', 'erfanpro' ),
		'address'          => __( 'نشانی', 'erfanpro' ),
		'catalog_full_url' => __( 'لینک کاتالوگ کامل سایت (فال‌بک کاتالوگ محصول)', 'erfanpro' ),
		'footer_about'     => __( 'متن معرفی کوتاه فوتر', 'erfanpro' ),
		'instagram'        => __( 'لینک اینستاگرام', 'erfanpro' ),
		'telegram'         => __( 'لینک تلگرام', 'erfanpro' ),
		'aparat'           => __( 'لینک آپارات', 'erfanpro' ),
		'linkedin'         => __( 'لینک لینکدین', 'erfanpro' ),
	);

	foreach ( $fields as $key => $label ) {
		add_settings_field(
			'erfanpro_' . $key,
			$label,
			'erfanpro_options_field',
			'erfanpro-settings',
			'erfanpro_general',
			array( 'key' => $key )
		);
	}

	/* ---- BO-001 trust badges ---- */
	add_settings_section(
		'erfanpro_trust',
		__( 'نمادهای اعتماد فوتر', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_trust_badges',
		__( 'نمادها', 'erfanpro' ),
		'erfanpro_options_trust_badges_field',
		'erfanpro-settings',
		'erfanpro_trust'
	);

	/* ---- BO-002 home counters ---- */
	add_settings_section(
		'erfanpro_counters',
		__( 'شمارنده‌های صفحه خانه', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_home_counters',
		__( 'شمارنده‌ها', 'erfanpro' ),
		'erfanpro_options_home_counters_field',
		'erfanpro-settings',
		'erfanpro_counters'
	);

	/* ---- BO-003 home section texts ---- */
	add_settings_section(
		'erfanpro_home_sections',
		__( 'متن‌های سکشن‌های صفحه خانه', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_home_sections_fields',
		__( 'سکشن‌ها', 'erfanpro' ),
		'erfanpro_options_home_sections_field',
		'erfanpro-settings',
		'erfanpro_home_sections'
	);

	/* ---- BO-004 custom fonts ---- */
	add_settings_section(
		'erfanpro_fonts',
		__( 'فونت‌های سفارشی', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_font_files',
		__( 'آپلود فونت', 'erfanpro' ),
		'erfanpro_options_fonts_field',
		'erfanpro-settings',
		'erfanpro_fonts'
	);

	/* ---- BO-009 home hero + featured product ---- */
	add_settings_section(
		'erfanpro_home_hero',
		__( 'هیروی صفحه خانه', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_home_hero_fields',
		__( 'هیرو', 'erfanpro' ),
		'erfanpro_options_home_hero_field',
		'erfanpro-settings',
		'erfanpro_home_hero'
	);

	/* ---- BO-011 why-us section texts ---- */
	add_settings_section(
		'erfanpro_why_us',
		__( 'سکشن «چرا عرفان صنعت؟»', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_why_us_fields',
		__( 'متن‌ها', 'erfanpro' ),
		'erfanpro_options_why_us_field',
		'erfanpro-settings',
		'erfanpro_why_us'
	);

	/* ---- BO-012 shop hero + toolbar texts ---- */
	add_settings_section(
		'erfanpro_shop_texts',
		__( 'متن‌های فروشگاه', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_shop_texts_fields',
		__( 'متن‌ها', 'erfanpro' ),
		'erfanpro_options_shop_texts_field',
		'erfanpro-settings',
		'erfanpro_shop_texts'
	);

	/* ---- BO-010 city marquee ---- */
	add_settings_section(
		'erfanpro_city_marquee',
		__( 'نوار شهرها', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_city_marquee_fields',
		__( 'نوار شهرها', 'erfanpro' ),
		'erfanpro_options_city_marquee_field',
		'erfanpro-settings',
		'erfanpro_city_marquee'
	);

	/* ---- BO-013 about + contact texts ---- */
	add_settings_section(
		'erfanpro_about_contact',
		__( 'متن‌های درباره و تماس', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_about_texts_fields',
		__( 'درباره ما', 'erfanpro' ),
		'erfanpro_options_text_groups_field',
		'erfanpro-settings',
		'erfanpro_about_contact',
		array( 'option_key' => 'about_texts' )
	);
	add_settings_field(
		'erfanpro_contact_texts_fields',
		__( 'تماس با ما', 'erfanpro' ),
		'erfanpro_options_text_groups_field',
		'erfanpro-settings',
		'erfanpro_about_contact',
		array( 'option_key' => 'contact_texts' )
	);

	/* ---- BO-014 five simple page texts ---- */
	add_settings_section(
		'erfanpro_minor_pages',
		__( 'متن‌های برگه‌های ساده', 'erfanpro' ),
		'__return_false',
		'erfanpro-settings'
	);
	add_settings_field(
		'erfanpro_minor_pages_fields',
		__( 'گارانتی، مجوزها، سوالات، استخدام، بازخورد', 'erfanpro' ),
		'erfanpro_options_text_groups_field',
		'erfanpro-settings',
		'erfanpro_minor_pages',
		array( 'option_key' => 'minor_pages_texts' )
	);
}
add_action( 'admin_init', 'erfanpro_options_init' );

/**
 * Render one text field.
 *
 * @param array $args Field args ({key}).
 */
function erfanpro_options_field( $args ) {
	$key   = $args['key'];
	$value = erfanpro_option( $key, '' );
	printf(
		'<input type="text" class="regular-text" name="erfanpro_options[%1$s]" value="%2$s" />',
		esc_attr( $key ),
		esc_attr( (string) $value )
	);
}

/**
 * BO-001: trust badges repeater (label, image, url).
 */
function erfanpro_options_trust_badges_field() {
	$badges = erfanpro_option( 'trust_badges', array() );
	if ( ! is_array( $badges ) || empty( $badges ) ) {
		$badges = array( array() );
	}
	?>
	<div data-erfanpro-repeater>
		<div class="erfanpro-repeater-rows" data-erfanpro-rows>
			<?php foreach ( $badges as $i => $badge ) : ?>
				<?php erfanpro_options_badge_row( $i, is_array( $badge ) ? $badge : array() ); ?>
			<?php endforeach; ?>
		</div>
		<script type="text/html" data-erfanpro-template><?php erfanpro_options_badge_row( '__i__', array() ); ?></script>
		<button type="button" class="button" data-erfanpro-add><?php esc_html_e( 'افزودن نماد', 'erfanpro' ); ?></button>
	</div>
	<?php
}

/**
 * One trust-badge row.
 *
 * @param int|string $i     Row index.
 * @param array      $badge Row values.
 */
function erfanpro_options_badge_row( $i, $badge ) {
	$image   = absint( $badge['image'] ?? 0 );
	$preview = $image ? wp_get_attachment_image( $image, 'thumbnail' ) : '';
	?>
	<div class="erfanpro-repeater-row" data-erfanpro-row data-erfanpro-index="<?php echo esc_attr( (string) $i ); ?>">
		<div class="erfanpro-field">
			<label><?php esc_html_e( 'برچسب', 'erfanpro' ); ?></label>
			<input type="text" name="erfanpro_options[trust_badges][<?php echo esc_attr( (string) $i ); ?>][label]" value="<?php echo esc_attr( (string) ( $badge['label'] ?? '' ) ); ?>" />
		</div>
		<div class="erfanpro-field" data-erfanpro-media="single">
			<label><?php esc_html_e( 'تصویر/آیکون', 'erfanpro' ); ?></label>
			<input type="hidden" data-erfanpro-media-id name="erfanpro_options[trust_badges][<?php echo esc_attr( (string) $i ); ?>][image]" value="<?php echo esc_attr( $image ? (string) $image : '' ); ?>" />
			<div class="erfanpro-media-row">
				<button type="button" class="button" data-erfanpro-media-select><?php esc_html_e( 'انتخاب', 'erfanpro' ); ?></button>
				<button type="button" class="button-link" data-erfanpro-media-remove><?php esc_html_e( 'حذف', 'erfanpro' ); ?></button>
			</div>
			<div data-erfanpro-media-preview style="<?php echo $preview ? '' : 'display:none'; ?>"><?php echo $preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
		<div class="erfanpro-field">
			<label><?php esc_html_e( 'لینک', 'erfanpro' ); ?></label>
			<input type="url" name="erfanpro_options[trust_badges][<?php echo esc_attr( (string) $i ); ?>][url]" value="<?php echo esc_attr( (string) ( $badge['url'] ?? '' ) ); ?>" dir="ltr" placeholder="https://" />
		</div>
		<button type="button" class="button erfanpro-remove" data-erfanpro-remove aria-label="<?php esc_attr_e( 'حذف ردیف', 'erfanpro' ); ?>">✕</button>
	</div>
	<?php
}

/**
 * BO-002: home counters repeater (label, real_source, manual_value, offset).
 */
function erfanpro_options_home_counters_field() {
	$counters = erfanpro_option( 'home_counters', array() );
	if ( ! is_array( $counters ) || empty( $counters ) ) {
		$counters = array( array() );
	}
	$sources = array(
		'manual'     => __( 'دستی', 'erfanpro' ),
		'woocommerce' => __( 'تعداد محصولات (ووکامرس)', 'erfanpro' ),
		'posts'      => __( 'تعداد نوشته‌ها', 'erfanpro' ),
		'projects'   => __( 'تعداد پروژه‌ها', 'erfanpro' ),
	);
	?>
	<div data-erfanpro-repeater>
		<div class="erfanpro-repeater-rows" data-erfanpro-rows>
			<?php foreach ( $counters as $i => $counter ) : ?>
				<?php erfanpro_options_counter_row( $i, is_array( $counter ) ? $counter : array(), $sources ); ?>
			<?php endforeach; ?>
		</div>
		<script type="text/html" data-erfanpro-template><?php erfanpro_options_counter_row( '__i__', array(), $sources ); ?></script>
		<button type="button" class="button" data-erfanpro-add><?php esc_html_e( 'افزودن شمارنده', 'erfanpro' ); ?></button>
	</div>
	<p class="description"><?php esc_html_e( 'عدد نهایی = عدد واقعی منبع + آفست (آفست می‌تواند منفی باشد).', 'erfanpro' ); ?></p>
	<?php
}

/**
 * One home-counter row.
 *
 * @param int|string $i       Row index.
 * @param array      $counter Row values.
 * @param array      $sources Source options.
 */
function erfanpro_options_counter_row( $i, $counter, $sources ) {
	$base = 'erfanpro_options[home_counters][' . $i . ']';
	?>
	<div class="erfanpro-repeater-row" data-erfanpro-row data-erfanpro-index="<?php echo esc_attr( (string) $i ); ?>">
		<div class="erfanpro-field">
			<label><?php esc_html_e( 'برچسب', 'erfanpro' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $base ); ?>[label]" value="<?php echo esc_attr( (string) ( $counter['label'] ?? '' ) ); ?>" />
		</div>
		<div class="erfanpro-field">
			<label><?php esc_html_e( 'منبع عدد واقعی', 'erfanpro' ); ?></label>
			<select name="<?php echo esc_attr( $base ); ?>[real_source]">
				<?php foreach ( $sources as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( (string) ( $counter['real_source'] ?? 'manual' ), $key ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="erfanpro-field">
			<label><?php esc_html_e( 'عدد دستی', 'erfanpro' ); ?></label>
			<input type="number" name="<?php echo esc_attr( $base ); ?>[manual_value]" value="<?php echo esc_attr( (string) (int) ( $counter['manual_value'] ?? 0 ) ); ?>" />
		</div>
		<div class="erfanpro-field">
			<label><?php esc_html_e( 'آفست (+/-)', 'erfanpro' ); ?></label>
			<input type="number" name="<?php echo esc_attr( $base ); ?>[offset]" value="<?php echo esc_attr( (string) (int) ( $counter['offset'] ?? 0 ) ); ?>" />
		</div>
		<button type="button" class="button erfanpro-remove" data-erfanpro-remove aria-label="<?php esc_attr_e( 'حذف ردیف', 'erfanpro' ); ?>">✕</button>
	</div>
	<?php
}

/**
 * BO-003: home section texts — 6 fixed sections.
 */
function erfanpro_options_home_sections_field() {
	$sections = array(
		'products' => __( 'محصولات منتخب', 'erfanpro' ),
		'services' => __( 'خدمات', 'erfanpro' ),
		'projects' => __( 'پروژه‌های برگزیده', 'erfanpro' ),
		'about'    => __( 'درباره', 'erfanpro' ),
		'blog'     => __( 'آخرین مقالات', 'erfanpro' ),
		'cta'      => __( 'دعوت به اقدام', 'erfanpro' ),
	);
	$values   = erfanpro_option( 'home_sections', array() );
	if ( ! is_array( $values ) ) {
		$values = array();
	}
	foreach ( $sections as $key => $label ) {
		$sec  = isset( $values[ $key ] ) && is_array( $values[ $key ] ) ? $values[ $key ] : array();
		$base = 'erfanpro_options[home_sections][' . $key . ']';
		?>
		<div style="border:1px solid #dcdcde;border-radius:6px;padding:12px;margin-bottom:12px;background:#fff;">
			<h3 style="margin:0 0 10px;"><?php echo esc_html( $label ); ?></h3>
			<div class="erfanpro-metabox-grid">
				<div class="erfanpro-metabox-field">
					<label><?php esc_html_e( 'تیتر', 'erfanpro' ); ?></label>
					<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[title]" value="<?php echo esc_attr( (string) ( $sec['title'] ?? '' ) ); ?>" />
				</div>
				<div class="erfanpro-metabox-field">
					<label><?php esc_html_e( 'زیرتیتر', 'erfanpro' ); ?></label>
					<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[subtitle]" value="<?php echo esc_attr( (string) ( $sec['subtitle'] ?? '' ) ); ?>" />
				</div>
				<div class="erfanpro-metabox-field">
					<label><?php esc_html_e( 'متن دکمه', 'erfanpro' ); ?></label>
					<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[cta_text]" value="<?php echo esc_attr( (string) ( $sec['cta_text'] ?? '' ) ); ?>" placeholder="<?php esc_attr_e( 'مشاهده همه', 'erfanpro' ); ?>" />
				</div>
				<div class="erfanpro-metabox-field">
					<label><?php esc_html_e( 'لینک دکمه', 'erfanpro' ); ?></label>
					<input type="url" class="widefat" dir="ltr" name="<?php echo esc_attr( $base ); ?>[cta_url]" value="<?php echo esc_attr( (string) ( $sec['cta_url'] ?? '' ) ); ?>" />
				</div>
			</div>
		</div>
		<?php
	}
}

/**
 * BO-004: custom font uploads (woff2/woff/ttf/otf).
 */
function erfanpro_options_fonts_field() {
	foreach ( array(
		'font_primary_file' => __( 'فونت اصلی (جایگزین وزیرمتن)', 'erfanpro' ),
		'font_display_file' => __( 'فونت نمایشی (جایگزین لاله‌زار)', 'erfanpro' ),
	) as $key => $label ) {
		$current = erfanpro_option( $key, '' );
		?>
		<div class="erfanpro-metabox-field" style="margin-bottom:16px;">
			<label><strong><?php echo esc_html( $label ); ?></strong></label>
			<?php if ( $current ) : ?>
				<span class="description">
					<?php esc_html_e( 'فعلی:', 'erfanpro' ); ?>
					<code dir="ltr"><?php echo esc_html( wp_basename( (string) $current ) ); ?></code>
				</span><br />
			<?php endif; ?>
			<input type="file" name="erfanpro_<?php echo esc_attr( $key ); ?>" accept=".woff2,.woff,.ttf,.otf" />
			<?php if ( $current ) : ?>
				<label style="font-weight:400;">
					<input type="checkbox" name="erfanpro_options[<?php echo esc_attr( $key ); ?>_remove]" value="1" />
					<?php esc_html_e( 'حذف فونت سفارشی و بازگشت به پیش‌فرض', 'erfanpro' ); ?>
				</label>
			<?php endif; ?>
		</div>
		<?php
	}
	echo '<p class="description">' . esc_html__( 'فونت‌ها در پوشه uploads/erfanpro-fonts ذخیره می‌شوند و فقط همان فونت جایگزین را بازنویسی می‌کنند.', 'erfanpro' ) . '</p>';
}

/**
 * BO-009: home hero fields (decision 21).
 */
function erfanpro_options_home_hero_field() {
	$hero = erfanpro_home_hero();
	$base = 'erfanpro_options[home_hero]';

	$products = array();
	if ( function_exists( 'wc_get_products' ) ) {
		foreach ( wc_get_products( array( 'status' => 'publish', 'limit' => -1, 'orderby' => 'title', 'order' => 'ASC' ) ) as $p ) {
			$products[ $p->get_id() ] = $p->get_name();
		}
	}

	$bg_id      = absint( $hero['hero_bg'] );
	$bg_preview = $bg_id ? wp_get_attachment_image( $bg_id, 'thumbnail' ) : '';
	?>
	<div class="erfanpro-metabox-grid">
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'تیتر هیرو', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[hero_title]" value="<?php echo esc_attr( $hero['hero_title'] ); ?>" />
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'زیرتیتر هیرو', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[hero_subtitle]" value="<?php echo esc_attr( $hero['hero_subtitle'] ); ?>" />
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'متن دکمه', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[hero_cta_text]" value="<?php echo esc_attr( $hero['hero_cta_text'] ); ?>" placeholder="<?php esc_attr_e( 'مشاهده محصولات', 'erfanpro' ); ?>" />
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'لینک دکمه', 'erfanpro' ); ?></label>
			<input type="url" class="widefat" dir="ltr" name="<?php echo esc_attr( $base ); ?>[hero_cta_url]" value="<?php echo esc_attr( $hero['hero_cta_url'] ); ?>" />
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'محصول شاخص (کارت هیرو)', 'erfanpro' ); ?></label>
			<select name="<?php echo esc_attr( $base ); ?>[featured_product]">
				<option value="0"><?php esc_html_e( '— محصول ویژه ووکامرس (خودکار) —', 'erfanpro' ); ?></option>
				<?php foreach ( $products as $id => $name ) : ?>
				<option value="<?php echo esc_attr( (string) $id ); ?>" <?php selected( (int) $hero['featured_product'], $id ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="erfanpro-metabox-field" data-erfanpro-media="single">
			<label><?php esc_html_e( 'پس‌زمینه هیرو (تصویر)', 'erfanpro' ); ?></label>
			<input type="hidden" data-erfanpro-media-id name="<?php echo esc_attr( $base ); ?>[hero_bg]" value="<?php echo esc_attr( $bg_id ? (string) $bg_id : '' ); ?>" />
			<div class="erfanpro-media-row">
				<button type="button" class="button" data-erfanpro-media-select><?php esc_html_e( 'انتخاب تصویر', 'erfanpro' ); ?></button>
				<button type="button" class="button-link" data-erfanpro-media-remove><?php esc_html_e( 'حذف', 'erfanpro' ); ?></button>
			</div>
			<div data-erfanpro-media-preview style="<?php echo $bg_preview ? '' : 'display:none'; ?>"><?php echo $bg_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</div>
	<p class="description"><?php esc_html_e( 'خالی = مرجع استاتیک. محصول شاخص خالی = محصول ویژه ووکامرس.', 'erfanpro' ); ?></p>
	<?php
}

/**
 * BO-011: why-us section texts (stored as home_sections[why_us]).
 */
function erfanpro_options_why_us_field() {
	$sec  = erfanpro_why_us_section();
	$base = 'erfanpro_options[home_sections][why_us]';
	?>
	<div class="erfanpro-metabox-grid">
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'نشان‌واره (kicker)', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[kicker]" value="<?php echo esc_attr( $sec['kicker'] ); ?>" placeholder="<?php esc_attr_e( 'چرا عرفان صنعت؟', 'erfanpro' ); ?>" />
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'تیتر — بخش اول', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[title_a]" value="<?php echo esc_attr( $sec['title_a'] ); ?>" placeholder="<?php esc_attr_e( 'تعهد ما به', 'erfanpro' ); ?>" />
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'تیتر — بخش رنگی', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[title_b]" value="<?php echo esc_attr( $sec['title_b'] ); ?>" placeholder="<?php esc_attr_e( 'کیفیت و دوام', 'erfanpro' ); ?>" />
		</div>
		<div class="erfanpro-metabox-field full">
			<label><?php esc_html_e( 'توضیح', 'erfanpro' ); ?></label>
			<textarea class="widefat" rows="2" name="<?php echo esc_attr( $base ); ?>[desc]"><?php echo esc_textarea( $sec['desc'] ); ?></textarea>
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'تیتر مراحل — بخش اول', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[steps_title_a]" value="<?php echo esc_attr( $sec['steps_title_a'] ); ?>" placeholder="<?php esc_attr_e( 'مسیر همکاری با ما؛', 'erfanpro' ); ?>" />
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'تیتر مراحل — بخش رنگی', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[steps_title_b]" value="<?php echo esc_attr( $sec['steps_title_b'] ); ?>" placeholder="<?php esc_attr_e( ' از ایده تا درخشش', 'erfanpro' ); ?>" />
		</div>
	</div>
	<div data-erfanpro-repeater>
		<label><strong><?php esc_html_e( 'کارت‌های ویژگی (۴ مورد)', 'erfanpro' ); ?></strong></label>
		<div class="erfanpro-repeater-rows" data-erfanpro-rows>
			<?php foreach ( $sec['features'] as $i => $feat ) : ?>
			<div class="erfanpro-repeater-row" data-erfanpro-row data-erfanpro-index="<?php echo esc_attr( (string) $i ); ?>">
				<div class="erfanpro-field">
					<label><?php esc_html_e( 'تیتر', 'erfanpro' ); ?></label>
					<input type="text" name="<?php echo esc_attr( $base ); ?>[features][<?php echo esc_attr( (string) $i ); ?>][title]" value="<?php echo esc_attr( (string) ( $feat['title'] ?? '' ) ); ?>" />
				</div>
				<div class="erfanpro-field">
					<label><?php esc_html_e( 'توضیح', 'erfanpro' ); ?></label>
					<input type="text" name="<?php echo esc_attr( $base ); ?>[features][<?php echo esc_attr( (string) $i ); ?>][desc]" value="<?php echo esc_attr( (string) ( $feat['desc'] ?? '' ) ); ?>" />
				</div>
				<button type="button" class="button erfanpro-remove" data-erfanpro-remove aria-label="<?php esc_attr_e( 'حذف ردیف', 'erfanpro' ); ?>">✕</button>
			</div>
			<?php endforeach; ?>
		</div>
		<script type="text/html" data-erfanpro-template>
			<div class="erfanpro-repeater-row" data-erfanpro-row data-erfanpro-index="__i__">
				<div class="erfanpro-field">
					<label><?php esc_html_e( 'تیتر', 'erfanpro' ); ?></label>
					<input type="text" name="<?php echo esc_attr( $base ); ?>[features][__i__][title]" value="" />
				</div>
				<div class="erfanpro-field">
					<label><?php esc_html_e( 'توضیح', 'erfanpro' ); ?></label>
					<input type="text" name="<?php echo esc_attr( $base ); ?>[features][__i__][desc]" value="" />
				</div>
				<button type="button" class="button erfanpro-remove" data-erfanpro-remove aria-label="<?php esc_attr_e( 'حذف ردیف', 'erfanpro' ); ?>">✕</button>
			</div>
		</script>
		<button type="button" class="button" data-erfanpro-add><?php esc_html_e( 'افزودن کارت', 'erfanpro' ); ?></button>
	</div>
	<?php
}

/**
 * BO-012: shop hero + toolbar texts.
 */
function erfanpro_options_shop_texts_field() {
	$texts = erfanpro_shop_texts();
	$base  = 'erfanpro_options[shop_texts]';
	?>
	<div class="erfanpro-metabox-grid">
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'تیتر هیروی فروشگاه', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[hero_title]" value="<?php echo esc_attr( $texts['hero_title'] ); ?>" placeholder="<?php esc_attr_e( 'محصولات', 'erfanpro' ); ?>" />
		</div>
		<div class="erfanpro-metabox-field full">
			<label><?php esc_html_e( 'زیرتیتر هیروی فروشگاه', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[hero_subtitle]" value="<?php echo esc_attr( $texts['hero_subtitle'] ); ?>" />
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'متن جای‌نگهدار جستجو', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[search_placeholder]" value="<?php echo esc_attr( $texts['search_placeholder'] ); ?>" placeholder="<?php esc_attr_e( 'جستجو در محصولات…', 'erfanpro' ); ?>" />
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'برچسب «فقط قابل خرید»', 'erfanpro' ); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[purchasable_label]" value="<?php echo esc_attr( $texts['purchasable_label'] ); ?>" placeholder="<?php esc_attr_e( 'فقط قابل خرید', 'erfanpro' ); ?>" />
		</div>
	</div>
	<?php
}

/**
 * BO-013 / BO-014: field spec for the panel-editable page texts.
 *
 * Shape: option key -> group -> {label, fields, repeaters}. A field is
 * text or textarea; a repeater row is an assoc array of text subfields.
 * Templates keep the current literals as fallbacks, so this spec only
 * defines the admin form and the sanitize whitelist — never defaults.
 *
 * @return array
 */
function erfanpro_panel_text_specs() {
	$hero_fields = array(
		'hero_title'    => array( 'label' => __( 'تیتر هیرو', 'erfanpro' ), 'type' => 'text' ),
		'hero_subtitle' => array( 'label' => __( 'زیرتیتر هیرو', 'erfanpro' ), 'type' => 'textarea' ),
		'hero_badge'    => array( 'label' => __( 'نشان هیرو', 'erfanpro' ), 'type' => 'text' ),
	);
	return array(
		'about_texts'       => array(
			'hero'   => array(
				'label'     => __( 'هیروی درباره', 'erfanpro' ),
				'fields'    => array(
					'kicker'     => array( 'label' => __( 'نشان‌واره', 'erfanpro' ), 'type' => 'text' ),
					'title_a'    => array( 'label' => __( 'تیتر — بخش اول', 'erfanpro' ), 'type' => 'text' ),
					'title_b'    => array( 'label' => __( 'تیتر — بخش رنگی', 'erfanpro' ), 'type' => 'text' ),
					'quote'      => array( 'label' => __( 'نقل‌قول زیر تیتر', 'erfanpro' ), 'type' => 'textarea' ),
					'caption'    => array( 'label' => __( 'متن روی کارت کارخانه', 'erfanpro' ), 'type' => 'text' ),
					'since'      => array( 'label' => __( 'نشان «از ۱۳۷۸»', 'erfanpro' ), 'type' => 'text' ),
					'iso_title'  => array( 'label' => __( 'تیتر نشان ISO', 'erfanpro' ), 'type' => 'text' ),
					'iso_sub'    => array( 'label' => __( 'زیرتیتر نشان ISO', 'erfanpro' ), 'type' => 'text' ),
					'team_title' => array( 'label' => __( 'تیتر نشان تیم', 'erfanpro' ), 'type' => 'text' ),
					'team_sub'   => array( 'label' => __( 'زیرتیتر نشان تیم', 'erfanpro' ), 'type' => 'text' ),
				),
				'repeaters' => array(
					'hero_counters' => array(
						'label'  => __( 'شمارنده‌های هیرو', 'erfanpro' ),
						'add'    => __( 'افزودن شمارنده', 'erfanpro' ),
						'fields' => array(
							'value' => __( 'عدد', 'erfanpro' ),
							'label' => __( 'برچسب', 'erfanpro' ),
						),
					),
				),
			),
			'story'  => array(
				'label'     => __( 'داستان شرکت', 'erfanpro' ),
				'fields'    => array(
					'kicker'        => array( 'label' => __( 'نشان‌واره', 'erfanpro' ), 'type' => 'text' ),
					'title_a'       => array( 'label' => __( 'تیتر — بخش اول', 'erfanpro' ), 'type' => 'text' ),
					'title_b'       => array( 'label' => __( 'تیتر — بخش رنگی', 'erfanpro' ), 'type' => 'text' ),
					'para1_bold'    => array( 'label' => __( 'پاراگراف اول — عبارت پررنگ', 'erfanpro' ), 'type' => 'text' ),
					'para1'         => array( 'label' => __( 'پاراگراف اول — ادامه', 'erfanpro' ), 'type' => 'textarea' ),
					'para2'         => array( 'label' => __( 'پاراگراف دوم', 'erfanpro' ), 'type' => 'textarea' ),
					'factory_title' => array( 'label' => __( 'تیتر نشان کارخانه', 'erfanpro' ), 'type' => 'text' ),
					'factory_sub'   => array( 'label' => __( 'زیرتیتر نشان کارخانه', 'erfanpro' ), 'type' => 'text' ),
				),
				'repeaters' => array(
					'story_stats' => array(
						'label'  => __( 'کارت‌های آمار', 'erfanpro' ),
						'add'    => __( 'افزودن کارت آمار', 'erfanpro' ),
						'fields' => array(
							'value' => __( 'عدد', 'erfanpro' ),
							'label' => __( 'برچسب', 'erfanpro' ),
						),
					),
				),
			),
			'iso'    => array(
				'label'     => __( 'گواهینامه ISO', 'erfanpro' ),
				'fields'    => array(
					'kicker'     => array( 'label' => __( 'نشان‌واره', 'erfanpro' ), 'type' => 'text' ),
					'title_a'    => array( 'label' => __( 'تیتر — بخش اول', 'erfanpro' ), 'type' => 'text' ),
					'title_b'    => array( 'label' => __( 'تیتر — بخش رنگی', 'erfanpro' ), 'type' => 'text' ),
					'para'       => array( 'label' => __( 'پاراگراف', 'erfanpro' ), 'type' => 'textarea' ),
					'card_title' => array( 'label' => __( 'تیتر کارت ISO', 'erfanpro' ), 'type' => 'text' ),
					'card_sub1'  => array( 'label' => __( 'زیرتیتر اول کارت', 'erfanpro' ), 'type' => 'text' ),
					'card_sub2'  => array( 'label' => __( 'زیرتیتر دوم کارت (انگلیسی)', 'erfanpro' ), 'type' => 'text' ),
					'card_badge' => array( 'label' => __( 'نشان کارت', 'erfanpro' ), 'type' => 'text' ),
				),
				'repeaters' => array(
					'iso_bullets' => array(
						'label'  => __( 'موارد فهرست', 'erfanpro' ),
						'add'    => __( 'افزودن مورد', 'erfanpro' ),
						'fields' => array(
							'text' => __( 'متن', 'erfanpro' ),
						),
					),
				),
			),
			'values' => array(
				'label'     => __( 'اصول بنیادین', 'erfanpro' ),
				'fields'    => array(
					'title_a'      => array( 'label' => __( 'تیتر — بخش اول', 'erfanpro' ), 'type' => 'text' ),
					'title_b'      => array( 'label' => __( 'تیتر — بخش رنگی', 'erfanpro' ), 'type' => 'text' ),
					'banner_title' => array( 'label' => __( 'تیتر بنر سراسری', 'erfanpro' ), 'type' => 'text' ),
					'banner_para'  => array( 'label' => __( 'پاراگراف بنر', 'erfanpro' ), 'type' => 'textarea' ),
					'banner_cta'   => array( 'label' => __( 'متن دکمه بنر', 'erfanpro' ), 'type' => 'text' ),
				),
				'repeaters' => array(
					'value_cards' => array(
						'label'  => __( 'کارت‌های اصول', 'erfanpro' ),
						'add'    => __( 'افزودن کارت', 'erfanpro' ),
						'fields' => array(
							'title' => __( 'تیتر', 'erfanpro' ),
							'desc'  => __( 'توضیح', 'erfanpro' ),
						),
					),
				),
			),
		),
		'contact_texts'     => array(
			'hero'       => array(
				'label'     => __( 'هیروی تماس', 'erfanpro' ),
				'fields'    => array(
					'kicker'           => array( 'label' => __( 'نشان‌واره', 'erfanpro' ), 'type' => 'text' ),
					'title_a'          => array( 'label' => __( 'تیتر — بخش اول', 'erfanpro' ), 'type' => 'text' ),
					'title_b'          => array( 'label' => __( 'تیتر — بخش رنگی', 'erfanpro' ), 'type' => 'text' ),
					'desc'             => array( 'label' => __( 'توضیح زیر تیتر', 'erfanpro' ), 'type' => 'textarea' ),
					'caption'          => array( 'label' => __( 'متن روی کارت تصویر', 'erfanpro' ), 'type' => 'text' ),
					'since'            => array( 'label' => __( 'نشان «از ۱۳۷۸»', 'erfanpro' ), 'type' => 'text' ),
					'phone_suffix'     => array( 'label' => __( 'پسوند دکمه تلفن', 'erfanpro' ), 'type' => 'text' ),
					'whatsapp_label'   => array( 'label' => __( 'متن دکمه واتساپ', 'erfanpro' ), 'type' => 'text' ),
					'badge_sales_title' => array( 'label' => __( 'تیتر نشان فروش', 'erfanpro' ), 'type' => 'text' ),
					'badge_hours_title' => array( 'label' => __( 'تیتر نشان ساعات', 'erfanpro' ), 'type' => 'text' ),
					'badge_hours_sub'  => array( 'label' => __( 'زیرتیتر نشان ساعات', 'erfanpro' ), 'type' => 'text' ),
				),
				'repeaters' => array(
					'hero_counters' => array(
						'label'  => __( 'شمارنده‌های هیرو', 'erfanpro' ),
						'add'    => __( 'افزودن شمارنده', 'erfanpro' ),
						'fields' => array(
							'value' => __( 'عدد', 'erfanpro' ),
							'label' => __( 'برچسب', 'erfanpro' ),
						),
					),
				),
			),
			'cards'      => array(
				'label'     => __( 'کارت‌های اطلاعات', 'erfanpro' ),
				'fields'    => array(
					'card1_label' => array( 'label' => __( 'برچسب کارت تلفن', 'erfanpro' ), 'type' => 'text' ),
					'card1_sub'   => array( 'label' => __( 'زیرتیتر کارت تلفن', 'erfanpro' ), 'type' => 'text' ),
					'card2_label' => array( 'label' => __( 'برچسب کارت ایمیل', 'erfanpro' ), 'type' => 'text' ),
					'card2_value' => array( 'label' => __( 'ایمیل', 'erfanpro' ), 'type' => 'text' ),
					'card2_sub'   => array( 'label' => __( 'زیرتیتر کارت ایمیل', 'erfanpro' ), 'type' => 'text' ),
					'card3_label' => array( 'label' => __( 'برچسب کارت ساعات', 'erfanpro' ), 'type' => 'text' ),
					'card3_value' => array( 'label' => __( 'ساعات کاری', 'erfanpro' ), 'type' => 'text' ),
					'card3_sub'   => array( 'label' => __( 'زیرتیتر کارت ساعات', 'erfanpro' ), 'type' => 'text' ),
					'card4_label' => array( 'label' => __( 'برچسب کارت آدرس', 'erfanpro' ), 'type' => 'text' ),
					'card4_value' => array( 'label' => __( 'آدرس', 'erfanpro' ), 'type' => 'text' ),
					'card4_sub'   => array( 'label' => __( 'زیرتیتر کارت آدرس', 'erfanpro' ), 'type' => 'text' ),
				),
				'repeaters' => array(),
			),
			'form_card'  => array(
				'label'     => __( 'کارت فرم مشاوره', 'erfanpro' ),
				'fields'    => array(
					'form_title' => array( 'label' => __( 'تیتر', 'erfanpro' ), 'type' => 'text' ),
					'form_desc'  => array( 'label' => __( 'توضیح', 'erfanpro' ), 'type' => 'textarea' ),
				),
				'repeaters' => array(),
			),
			'expert'     => array(
				'label'     => __( 'کارت مشاور فروش', 'erfanpro' ),
				'fields'    => array(
					'expert_title'         => array( 'label' => __( 'تیتر', 'erfanpro' ), 'type' => 'text' ),
					'expert_name'          => array( 'label' => __( 'نام مشاور', 'erfanpro' ), 'type' => 'text' ),
					'expert_role'          => array( 'label' => __( 'سمت', 'erfanpro' ), 'type' => 'text' ),
					'expert_mobile_display' => array( 'label' => __( 'شماره نمایشی (ارقام فارسی)', 'erfanpro' ), 'type' => 'text' ),
					'expert_mobile_tel'    => array( 'label' => __( 'شماره tel (ارقام لاتین)', 'erfanpro' ), 'type' => 'text' ),
					'btn_call'             => array( 'label' => __( 'متن دکمه تماس', 'erfanpro' ), 'type' => 'text' ),
					'btn_whatsapp'         => array( 'label' => __( 'متن دکمه واتساپ', 'erfanpro' ), 'type' => 'text' ),
				),
				'repeaters' => array(),
			),
			'map_design' => array(
				'label'     => __( 'نقشه و یادداشت طراحی', 'erfanpro' ),
				'fields'    => array(
					'map_address'     => array( 'label' => __( 'متن آدرس روی نقشه', 'erfanpro' ), 'type' => 'text' ),
					'map_postcode'    => array( 'label' => __( 'متن کدپستی', 'erfanpro' ), 'type' => 'text' ),
					'design_title'    => array( 'label' => __( 'تیتر یادداشت', 'erfanpro' ), 'type' => 'text' ),
					'design_para_a'   => array( 'label' => __( 'پاراگراف — بخش اول', 'erfanpro' ), 'type' => 'textarea' ),
					'design_para_bold' => array( 'label' => __( 'عبارت پررنگ', 'erfanpro' ), 'type' => 'text' ),
					'design_para_b'   => array( 'label' => __( 'پاراگراف — بخش دوم', 'erfanpro' ), 'type' => 'textarea' ),
				),
				'repeaters' => array(),
			),
			'form'       => array(
				'label'     => __( 'متن‌های فرم تماس', 'erfanpro' ),
				'fields'    => array(
					'status_sent'       => array( 'label' => __( 'پیام موفقیت', 'erfanpro' ), 'type' => 'text' ),
					'status_error'      => array( 'label' => __( 'پیام خطا', 'erfanpro' ), 'type' => 'text' ),
					'honeypot_label'    => array( 'label' => __( 'برچسب فیلد مخفی', 'erfanpro' ), 'type' => 'text' ),
					'label_name'        => array( 'label' => __( 'برچسب نام', 'erfanpro' ), 'type' => 'text' ),
					'placeholder_name'  => array( 'label' => __( 'جای‌نگهدار نام', 'erfanpro' ), 'type' => 'text' ),
					'label_mobile'      => array( 'label' => __( 'برچسب موبایل', 'erfanpro' ), 'type' => 'text' ),
					'placeholder_mobile' => array( 'label' => __( 'جای‌نگهدار موبایل', 'erfanpro' ), 'type' => 'text' ),
					'label_org'         => array( 'label' => __( 'برچسب سازمان', 'erfanpro' ), 'type' => 'text' ),
					'placeholder_org'   => array( 'label' => __( 'جای‌نگهدار سازمان', 'erfanpro' ), 'type' => 'text' ),
					'label_message'     => array( 'label' => __( 'برچسب توضیحات', 'erfanpro' ), 'type' => 'text' ),
					'placeholder_message' => array( 'label' => __( 'جای‌نگهدار توضیحات', 'erfanpro' ), 'type' => 'text' ),
					'submit'            => array( 'label' => __( 'متن دکمه ارسال', 'erfanpro' ), 'type' => 'text' ),
				),
				'repeaters' => array(),
			),
		),
		'minor_pages_texts' => array(
			'guarantee' => array(
				'label'     => __( 'شرایط گارانتی', 'erfanpro' ),
				'fields'    => array_merge(
					$hero_fields,
					array(
						'steps_title' => array( 'label' => __( 'تیتر مراحل', 'erfanpro' ), 'type' => 'text' ),
						'cta'         => array( 'label' => __( 'متن دکمه', 'erfanpro' ), 'type' => 'text' ),
					)
				),
				'repeaters' => array(
					'cards' => array(
						'label'  => __( 'کارت‌ها', 'erfanpro' ),
						'add'    => __( 'افزودن کارت', 'erfanpro' ),
						'fields' => array(
							'title' => __( 'تیتر', 'erfanpro' ),
							'desc'  => __( 'توضیح', 'erfanpro' ),
						),
					),
					'steps' => array(
						'label'  => __( 'مراحل', 'erfanpro' ),
						'add'    => __( 'افزودن مرحله', 'erfanpro' ),
						'fields' => array(
							'text' => __( 'متن', 'erfanpro' ),
						),
					),
				),
			),
			'licenses'  => array(
				'label'     => __( 'مجوزها و افتخارات', 'erfanpro' ),
				'fields'    => array_merge(
					$hero_fields,
					array(
						'bottom_title' => array( 'label' => __( 'تیتر بخش پایانی', 'erfanpro' ), 'type' => 'text' ),
						'bottom_desc'  => array( 'label' => __( 'توضیح بخش پایانی', 'erfanpro' ), 'type' => 'textarea' ),
						'cta'          => array( 'label' => __( 'متن دکمه', 'erfanpro' ), 'type' => 'text' ),
					)
				),
				'repeaters' => array(
					'cards' => array(
						'label'  => __( 'کارت‌ها', 'erfanpro' ),
						'add'    => __( 'افزودن کارت', 'erfanpro' ),
						'fields' => array(
							'badge' => __( 'نشان', 'erfanpro' ),
							'title' => __( 'تیتر', 'erfanpro' ),
							'desc'  => __( 'توضیح', 'erfanpro' ),
						),
					),
				),
			),
			'faq'       => array(
				'label'     => __( 'سوالات متداول', 'erfanpro' ),
				'fields'    => array_merge(
					$hero_fields,
					array(
						'cta' => array( 'label' => __( 'متن دکمه', 'erfanpro' ), 'type' => 'text' ),
					)
				),
				'repeaters' => array(
					'items' => array(
						'label'  => __( 'پرسش‌ها', 'erfanpro' ),
						'add'    => __( 'افزودن پرسش', 'erfanpro' ),
						'fields' => array(
							'q' => __( 'پرسش', 'erfanpro' ),
							'a' => __( 'پاسخ', 'erfanpro' ),
						),
					),
				),
			),
			'careers'   => array(
				'label'     => __( 'فرصت‌های شغلی', 'erfanpro' ),
				'fields'    => array_merge(
					$hero_fields,
					array(
						'resume_title' => array( 'label' => __( 'تیتر بخش رزومه', 'erfanpro' ), 'type' => 'text' ),
						'resume_desc'  => array( 'label' => __( 'توضیح بخش رزومه', 'erfanpro' ), 'type' => 'textarea' ),
						'cta'          => array( 'label' => __( 'متن دکمه', 'erfanpro' ), 'type' => 'text' ),
					)
				),
				'repeaters' => array(
					'areas' => array(
						'label'  => __( 'حوزه‌ها', 'erfanpro' ),
						'add'    => __( 'افزودن حوزه', 'erfanpro' ),
						'fields' => array(
							'title' => __( 'تیتر', 'erfanpro' ),
							'desc'  => __( 'توضیح', 'erfanpro' ),
						),
					),
				),
			),
			'feedback'  => array(
				'label'     => __( 'بازخورد مشتریان', 'erfanpro' ),
				'fields'    => array_merge(
					$hero_fields,
					array(
						'listen_title' => array( 'label' => __( 'تیتر بخش شنیدن نظر', 'erfanpro' ), 'type' => 'text' ),
						'listen_desc'  => array( 'label' => __( 'توضیح بخش شنیدن نظر', 'erfanpro' ), 'type' => 'textarea' ),
						'cta1'         => array( 'label' => __( 'دکمه اول', 'erfanpro' ), 'type' => 'text' ),
						'cta2'         => array( 'label' => __( 'دکمه دوم', 'erfanpro' ), 'type' => 'text' ),
					)
				),
				'repeaters' => array(
					'counters' => array(
						'label'  => __( 'شمارنده‌ها', 'erfanpro' ),
						'add'    => __( 'افزودن شمارنده', 'erfanpro' ),
						'fields' => array(
							'value' => __( 'عدد', 'erfanpro' ),
							'label' => __( 'برچسب', 'erfanpro' ),
						),
					),
				),
			),
		),
	);
}

/**
 * Generic admin field for one panel-text option key (BO-013/014).
 *
 * Renders one bordered box per spec group: scalar inputs/textareas
 * plus repeater blocks (same data-erfanpro-repeater convention as
 * BO-001/002/011). Saved values only — empty means "template fallback".
 *
 * @param array $args Field args ({option_key}).
 */
function erfanpro_options_text_groups_field( $args ) {
	$key  = $args['option_key'];
	$spec = erfanpro_panel_text_specs();
	if ( ! isset( $spec[ $key ] ) ) {
		return;
	}
	$saved = erfanpro_option( $key, array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}
	foreach ( $spec[ $key ] as $group_key => $group ) {
		$values = isset( $saved[ $group_key ] ) && is_array( $saved[ $group_key ] ) ? $saved[ $group_key ] : array();
		$base   = 'erfanpro_options[' . $key . '][' . $group_key . ']';
		?>
		<div style="border:1px solid #dcdcde;border-radius:6px;padding:12px;margin-bottom:12px;background:#fff;">
			<h3 style="margin:0 0 10px;"><?php echo esc_html( $group['label'] ); ?></h3>
			<div class="erfanpro-metabox-grid">
				<?php foreach ( $group['fields'] as $fkey => $field ) : ?>
					<div class="erfanpro-metabox-field<?php echo 'textarea' === $field['type'] ? ' full' : ''; ?>">
						<label><?php echo esc_html( $field['label'] ); ?></label>
						<?php if ( 'textarea' === $field['type'] ) : ?>
							<textarea class="widefat" rows="3" name="<?php echo esc_attr( $base ); ?>[<?php echo esc_attr( $fkey ); ?>]"><?php echo esc_textarea( (string) ( $values[ $fkey ] ?? '' ) ); ?></textarea>
						<?php else : ?>
							<input type="text" class="widefat" name="<?php echo esc_attr( $base ); ?>[<?php echo esc_attr( $fkey ); ?>]" value="<?php echo esc_attr( (string) ( $values[ $fkey ] ?? '' ) ); ?>" />
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $group['repeaters'] as $rkey => $repeater ) : ?>
				<?php erfanpro_options_repeater_block( $base . '[' . $rkey . ']', isset( $values[ $rkey ] ) && is_array( $values[ $rkey ] ) ? $values[ $rkey ] : array(), $repeater ); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

/**
 * One repeater block (admin), following the BO-001/002 convention.
 *
 * @param string $base     Input name base (without row index).
 * @param array  $rows     Saved rows.
 * @param array  $repeater Repeater spec ({label, add, fields}).
 */
function erfanpro_options_repeater_block( $base, $rows, $repeater ) {
	if ( ! is_array( $rows ) || empty( $rows ) ) {
		$rows = array( array() );
	}
	?>
	<div data-erfanpro-repeater style="margin-top:12px;">
		<label><strong><?php echo esc_html( $repeater['label'] ); ?></strong></label>
		<div class="erfanpro-repeater-rows" data-erfanpro-rows>
			<?php foreach ( $rows as $i => $row ) : ?>
				<?php erfanpro_options_repeater_row( $base, $i, is_array( $row ) ? $row : array(), $repeater['fields'] ); ?>
			<?php endforeach; ?>
		</div>
		<script type="text/html" data-erfanpro-template><?php erfanpro_options_repeater_row( $base, '__i__', array(), $repeater['fields'] ); ?></script>
		<button type="button" class="button" data-erfanpro-add><?php echo esc_html( $repeater['add'] ); ?></button>
	</div>
	<?php
}

/**
 * One repeater row (admin).
 *
 * @param string     $base   Input name base.
 * @param int|string $i      Row index.
 * @param array      $row    Row values.
 * @param array      $fields Subfield key => label.
 */
function erfanpro_options_repeater_row( $base, $i, $row, $fields ) {
	?>
	<div class="erfanpro-repeater-row" data-erfanpro-row data-erfanpro-index="<?php echo esc_attr( (string) $i ); ?>">
		<?php foreach ( $fields as $fkey => $flabel ) : ?>
			<div class="erfanpro-field">
				<label><?php echo esc_html( $flabel ); ?></label>
				<input type="text" name="<?php echo esc_attr( $base ); ?>[<?php echo esc_attr( (string) $i ); ?>][<?php echo esc_attr( $fkey ); ?>]" value="<?php echo esc_attr( (string) ( $row[ $fkey ] ?? '' ) ); ?>" />
			</div>
		<?php endforeach; ?>
		<button type="button" class="button erfanpro-remove" data-erfanpro-remove aria-label="<?php esc_attr_e( 'حذف ردیف', 'erfanpro' ); ?>">✕</button>
	</div>
	<?php
}

/**
 * BO-010: city marquee fields (source / manual list / speed).
 */
function erfanpro_options_city_marquee_field() {
	$mq     = erfanpro_city_marquee();
	$base   = 'erfanpro_options[city_marquee]';
	$source = $mq['source'];
	?>
	<div class="erfanpro-metabox-grid">
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'منبع فهرست شهرها', 'erfanpro' ); ?></label>
			<select name="<?php echo esc_attr( $base ); ?>[source]">
				<option value="auto" <?php selected( $source, 'auto' ); ?>><?php esc_html_e( 'خودکار — ترم‌های تاکسونومی city', 'erfanpro' ); ?></option>
				<option value="manual" <?php selected( $source, 'manual' ); ?>><?php esc_html_e( 'دستی — فهرست زیر', 'erfanpro' ); ?></option>
			</select>
		</div>
		<div class="erfanpro-metabox-field">
			<label><?php esc_html_e( 'سرعت حرکت (ثانیه)', 'erfanpro' ); ?></label>
			<input type="number" min="1" name="<?php echo esc_attr( $base ); ?>[speed]" value="<?php echo esc_attr( $mq['speed'] ? (string) $mq['speed'] : '' ); ?>" placeholder="38" />
		</div>
	</div>
	<?php
	erfanpro_options_repeater_block(
		$base . '[manual_cities]',
		$mq['manual_cities'],
		array(
			'label'  => __( 'شهرهای دستی (فقط وقتی منبع دستی است)', 'erfanpro' ),
			'add'    => __( 'افزودن شهر', 'erfanpro' ),
			'fields' => array( 'text' => __( 'نام شهر', 'erfanpro' ) ),
		)
	);
	?>
	<p class="description"><?php esc_html_e( 'خالی = سرعت فعلی قالب (۳۸ ثانیه). وقتی منبع دستی است و فهرست خالی باشد، فهرست خودکار نمایش داده می‌شود.', 'erfanpro' ); ?></p>
	<?php
}

/**
 * Sanitize all option values (whitelist-based, incl. nested repeaters).
 *
 * @param mixed $input Raw input.
 * @return array
 */
function erfanpro_options_sanitize( $input ) {
	$clean = array();
	if ( ! is_array( $input ) ) {
		$input = array();
	}
	$prev = get_option( 'erfanpro_options', array() );
	if ( ! is_array( $prev ) ) {
		$prev = array();
	}

	/* simple text/url fields */
	$url_keys = array( 'catalog_full_url', 'instagram', 'telegram', 'aparat', 'linkedin' );
	foreach ( $input as $key => $value ) {
		$key = sanitize_key( $key );
		if ( in_array( $key, array( 'trust_badges', 'home_counters', 'home_sections', 'home_hero', 'shop_texts', 'city_marquee', 'about_texts', 'contact_texts', 'minor_pages_texts' ), true ) ) {
			continue; // handled below.
		}
		if ( str_ends_with( $key, '_remove' ) || str_starts_with( $key, 'font_' ) ) {
			continue; // handled below.
		}
		if ( in_array( $key, $url_keys, true ) ) {
			$clean[ $key ] = esc_url_raw( (string) $value );
		} elseif ( 'email' === $key ) {
			$clean[ $key ] = sanitize_email( (string) $value );
		} else {
			$clean[ $key ] = sanitize_text_field( (string) $value );
		}
	}

	/* BO-001 trust badges */
	$badges = array();
	if ( isset( $input['trust_badges'] ) && is_array( $input['trust_badges'] ) ) {
		foreach ( $input['trust_badges'] as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$badge = array(
				'label' => sanitize_text_field( (string) ( $row['label'] ?? '' ) ),
				'image' => absint( $row['image'] ?? 0 ),
				'url'   => esc_url_raw( (string) ( $row['url'] ?? '' ) ),
			);
			if ( '' !== $badge['label'] || $badge['image'] || '' !== $badge['url'] ) {
				$badges[] = $badge;
			}
		}
	}
	$clean['trust_badges'] = $badges;

	/* BO-002 home counters */
	$counters    = array();
	$valid_src   = array( 'manual', 'woocommerce', 'posts', 'projects' );
	if ( isset( $input['home_counters'] ) && is_array( $input['home_counters'] ) ) {
		foreach ( $input['home_counters'] as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$src = (string) ( $row['real_source'] ?? 'manual' );
			if ( ! in_array( $src, $valid_src, true ) ) {
				$src = 'manual';
			}
			$counter = array(
				'label'        => sanitize_text_field( (string) ( $row['label'] ?? '' ) ),
				'real_source'  => $src,
				'manual_value' => (int) ( $row['manual_value'] ?? 0 ),
				'offset'       => (int) ( $row['offset'] ?? 0 ),
			);
			if ( '' !== $counter['label'] || 0 !== $counter['manual_value'] || 0 !== $counter['offset'] ) {
				$counters[] = $counter;
			}
		}
	}
	$clean['home_counters'] = $counters;

	/* BO-003 home sections (fixed keys) */
	$section_keys = array( 'products', 'services', 'projects', 'about', 'blog', 'cta' );
	$sections     = array();
	if ( isset( $input['home_sections'] ) && is_array( $input['home_sections'] ) ) {
		foreach ( $section_keys as $skey ) {
			$row = isset( $input['home_sections'][ $skey ] ) && is_array( $input['home_sections'][ $skey ] )
				? $input['home_sections'][ $skey ]
				: array();
			$sections[ $skey ] = array(
				'title'    => sanitize_text_field( (string) ( $row['title'] ?? '' ) ),
				'subtitle' => sanitize_text_field( (string) ( $row['subtitle'] ?? '' ) ),
				'cta_text' => sanitize_text_field( (string) ( $row['cta_text'] ?? '' ) ),
				'cta_url'  => esc_url_raw( (string) ( $row['cta_url'] ?? '' ) ),
			);
		}
		/* BO-011 why_us (same key family, different shape). */
		$wu  = isset( $input['home_sections']['why_us'] ) && is_array( $input['home_sections']['why_us'] )
			? $input['home_sections']['why_us']
			: array();
		$wu_features = array();
		if ( isset( $wu['features'] ) && is_array( $wu['features'] ) ) {
			foreach ( $wu['features'] as $f ) {
				if ( ! is_array( $f ) ) {
					continue;
				}
				$row_f = array(
					'title' => sanitize_text_field( (string) ( $f['title'] ?? '' ) ),
					'desc'  => sanitize_text_field( (string) ( $f['desc'] ?? '' ) ),
				);
				if ( '' !== $row_f['title'] || '' !== $row_f['desc'] ) {
					$wu_features[] = $row_f;
				}
			}
		}
		$sections['why_us'] = array(
			'kicker'        => sanitize_text_field( (string) ( $wu['kicker'] ?? '' ) ),
			'title_a'       => sanitize_text_field( (string) ( $wu['title_a'] ?? '' ) ),
			'title_b'       => sanitize_text_field( (string) ( $wu['title_b'] ?? '' ) ),
			'desc'          => sanitize_text_field( (string) ( $wu['desc'] ?? '' ) ),
			'steps_title_a' => sanitize_text_field( (string) ( $wu['steps_title_a'] ?? '' ) ),
			'steps_title_b' => sanitize_text_field( (string) ( $wu['steps_title_b'] ?? '' ) ),
			'features'      => $wu_features,
		);
	}
	$clean['home_sections'] = $sections;

	/* BO-009 home hero */
	$hero_in          = isset( $input['home_hero'] ) && is_array( $input['home_hero'] ) ? $input['home_hero'] : array();
	$clean['home_hero'] = array(
		'hero_title'       => sanitize_text_field( (string) ( $hero_in['hero_title'] ?? '' ) ),
		'hero_subtitle'    => sanitize_text_field( (string) ( $hero_in['hero_subtitle'] ?? '' ) ),
		'hero_cta_text'    => sanitize_text_field( (string) ( $hero_in['hero_cta_text'] ?? '' ) ),
		'hero_cta_url'     => esc_url_raw( (string) ( $hero_in['hero_cta_url'] ?? '' ) ),
		'featured_product' => absint( $hero_in['featured_product'] ?? 0 ),
		'hero_bg'          => absint( $hero_in['hero_bg'] ?? 0 ),
	);

	/* BO-012 shop texts */
	$shop_in            = isset( $input['shop_texts'] ) && is_array( $input['shop_texts'] ) ? $input['shop_texts'] : array();
	$clean['shop_texts'] = array(
		'hero_title'         => sanitize_text_field( (string) ( $shop_in['hero_title'] ?? '' ) ),
		'hero_subtitle'      => sanitize_text_field( (string) ( $shop_in['hero_subtitle'] ?? '' ) ),
		'search_placeholder' => sanitize_text_field( (string) ( $shop_in['search_placeholder'] ?? '' ) ),
		'purchasable_label'  => sanitize_text_field( (string) ( $shop_in['purchasable_label'] ?? '' ) ),
	);

	/* BO-010 city marquee */
	$mq_in             = isset( $input['city_marquee'] ) && is_array( $input['city_marquee'] ) ? $input['city_marquee'] : array();
	$mq_source         = (string) ( $mq_in['source'] ?? 'auto' );
	$mq_cities         = array();
	if ( isset( $mq_in['manual_cities'] ) && is_array( $mq_in['manual_cities'] ) ) {
		foreach ( $mq_in['manual_cities'] as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$city = sanitize_text_field( (string) ( $row['text'] ?? '' ) );
			if ( '' !== $city ) {
				$mq_cities[] = array( 'text' => $city );
			}
		}
	}
	$clean['city_marquee'] = array(
		'source'        => in_array( $mq_source, array( 'auto', 'manual' ), true ) ? $mq_source : 'auto',
		'manual_cities' => $mq_cities,
		'speed'         => max( 0, (int) ( $mq_in['speed'] ?? 0 ) ),
	);

	/* BO-013 / BO-014 panel texts (spec-driven) */
	$specs = erfanpro_panel_text_specs();
	foreach ( array( 'about_texts', 'contact_texts', 'minor_pages_texts' ) as $tkey ) {
		$clean[ $tkey ] = erfanpro_sanitize_text_groups(
			isset( $input[ $tkey ] ) && is_array( $input[ $tkey ] ) ? $input[ $tkey ] : array(),
			$specs[ $tkey ]
		);
	}

	/* BO-004 font uploads */
	foreach ( array( 'font_primary_file', 'font_display_file' ) as $fkey ) {
		if ( ! empty( $input[ $fkey . '_remove' ] ) ) {
			erfanpro_delete_custom_font( (string) ( $prev[ $fkey ] ?? '' ) );
			$clean[ $fkey ] = '';
			continue;
		}
		$file_key = 'erfanpro_' . $fkey;
		if ( isset( $_FILES[ $file_key ] ) && is_array( $_FILES[ $file_key ] ) && UPLOAD_ERR_OK === ( $_FILES[ $file_key ]['error'] ?? UPLOAD_ERR_NO_FILE ) ) {
			$url = erfanpro_handle_font_upload( $_FILES[ $file_key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( ! is_wp_error( $url ) ) {
				erfanpro_delete_custom_font( (string) ( $prev[ $fkey ] ?? '' ) );
				$clean[ $fkey ] = $url;
				continue;
			}
		}
		$clean[ $fkey ] = isset( $prev[ $fkey ] ) ? esc_url_raw( (string) $prev[ $fkey ] ) : '';
	}

	return $clean;
}

/**
 * Sanitize one panel-text option key from its spec (BO-013/014).
 *
 * Scalar text fields use sanitize_text_field, textarea fields use
 * sanitize_textarea_field, repeater rows keep only spec'd subfields and
 * fully-empty rows are dropped. Unknown keys are never stored.
 *
 * @param mixed $input Raw group input.
 * @param array $spec  Group spec from erfanpro_panel_text_specs().
 * @return array
 */
function erfanpro_sanitize_text_groups( $input, $spec ) {
	$clean = array();
	if ( ! is_array( $input ) ) {
		$input = array();
	}
	foreach ( $spec as $group_key => $group ) {
		$row_in = isset( $input[ $group_key ] ) && is_array( $input[ $group_key ] ) ? $input[ $group_key ] : array();
		$clean_group = array();
		foreach ( $group['fields'] as $fkey => $field ) {
			$value = (string) ( $row_in[ $fkey ] ?? '' );
			$clean_group[ $fkey ] = 'textarea' === $field['type'] ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
		}
		foreach ( $group['repeaters'] as $rkey => $repeater ) {
			$rows = array();
			if ( isset( $row_in[ $rkey ] ) && is_array( $row_in[ $rkey ] ) ) {
				foreach ( $row_in[ $rkey ] as $row ) {
					if ( ! is_array( $row ) ) {
						continue;
					}
					$clean_row = array();
					foreach ( $repeater['fields'] as $fkey => $flabel ) {
						$clean_row[ $fkey ] = sanitize_text_field( (string) ( $row[ $fkey ] ?? '' ) );
					}
					if ( '' !== implode( '', $clean_row ) ) {
						$rows[] = $clean_row;
					}
				}
			}
			$clean_group[ $rkey ] = $rows;
		}
		$clean[ $group_key ] = $clean_group;
	}
	return $clean;
}

/**
 * Handle a font file upload into uploads/erfanpro-fonts/.
 *
 * @param array $file One $_FILES entry.
 * @return string|WP_Error File URL.
 */
function erfanpro_handle_font_upload( $file ) {
	$allowed = array( 'woff2', 'woff', 'ttf', 'otf' );
	$check   = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], array_fill_keys( $allowed, 'font/*' ) );
	if ( empty( $check['ext'] ) || ! in_array( $check['ext'], $allowed, true ) ) {
		return new WP_Error( 'font_type', __( 'فقط فایل‌های woff2/woff/ttf/otf مجاز‌اند.', 'erfanpro' ) );
	}

	$uploads = wp_upload_dir();
	$dir     = trailingslashit( $uploads['basedir'] ) . 'erfanpro-fonts';
	if ( ! wp_mkdir_p( $dir ) ) {
		return new WP_Error( 'font_dir', __( 'ساخت پوشه فونت ممکن نشد.', 'erfanpro' ) );
	}

	// Keep the font inside our own directory even if wp_handle_upload wants elsewhere.
	add_filter( 'upload_dir', 'erfanpro_font_upload_dir' );
	$moved = wp_handle_upload(
		$file,
		array(
			'test_form' => false,
			'mimes'     => array_fill_keys( $allowed, 'font/*' ),
		)
	);
	remove_filter( 'upload_dir', 'erfanpro_font_upload_dir' );

	if ( isset( $moved['error'] ) ) {
		return new WP_Error( 'font_upload', $moved['error'] );
	}
	return esc_url_raw( $moved['url'] );
}

/**
 * Point uploads at uploads/erfanpro-fonts during a font upload.
 *
 * @param array $dirs Upload dirs.
 * @return array
 */
function erfanpro_font_upload_dir( $dirs ) {
	$dirs['subdir'] = '/erfanpro-fonts';
	$dirs['path']   = $dirs['basedir'] . '/erfanpro-fonts';
	$dirs['url']    = $dirs['baseurl'] . '/erfanpro-fonts';
	return $dirs;
}

/**
 * Delete a previously uploaded custom font file.
 *
 * @param string $url Font URL.
 */
function erfanpro_delete_custom_font( $url ) {
	if ( ! $url ) {
		return;
	}
	$uploads = wp_upload_dir();
	$baseurl = trailingslashit( $uploads['baseurl'] ) . 'erfanpro-fonts/';
	if ( str_starts_with( $url, $baseurl ) ) {
		$path = trailingslashit( $uploads['basedir'] ) . 'erfanpro-fonts/' . wp_basename( $url );
		if ( file_exists( $path ) ) {
			wp_delete_file( $path );
		}
	}
}

/**
 * Render the options page.
 */
function erfanpro_options_render() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'تنظیمات قالب erfanpro', 'erfanpro' ); ?></h1>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php
			settings_fields( 'erfanpro_options_group' );
			do_settings_sections( 'erfanpro-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Reader helpers for templates (phases 2+)
 * ---------------------------------------------------------------------- */

/**
 * Trust badges (BO-001).
 *
 * @return array<int, array{label:string,image:int,url:string}>
 */
function erfanpro_trust_badges() {
	$badges = erfanpro_option( 'trust_badges', array() );
	return is_array( $badges ) ? $badges : array();
}

/**
 * Home counters (BO-001... BO-002).
 *
 * @return array<int, array{label:string,real_source:string,manual_value:int,offset:int}>
 */
function erfanpro_home_counters() {
	$counters = erfanpro_option( 'home_counters', array() );
	return is_array( $counters ) ? $counters : array();
}

/**
 * Final display value of one home counter: real source value + offset.
 *
 * @param array $counter Counter row.
 * @return int
 */
function erfanpro_home_counter_value( $counter ) {
	$src = $counter['real_source'] ?? 'manual';
	switch ( $src ) {
		case 'woocommerce':
			$real = post_type_exists( 'product' ) ? (int) wp_count_posts( 'product' )->publish : 0;
			break;
		case 'posts':
			$real = (int) wp_count_posts( 'post' )->publish;
			break;
		case 'projects':
			$real = post_type_exists( 'projects' ) ? (int) wp_count_posts( 'projects' )->publish : 0;
			break;
		case 'manual':
		default:
			$real = (int) ( $counter['manual_value'] ?? 0 );
			break;
	}
	return $real + (int) ( $counter['offset'] ?? 0 );
}

/**
 * Texts of one home section (BO-003).
 *
 * @param string $key Section key.
 * @return array{title:string,subtitle:string,cta_text:string,cta_url:string}
 */
function erfanpro_home_section( $key ) {
	$sections = erfanpro_option( 'home_sections', array() );
	$sec      = isset( $sections[ $key ] ) && is_array( $sections[ $key ] ) ? $sections[ $key ] : array();
	return array(
		'title'    => (string) ( $sec['title'] ?? '' ),
		'subtitle' => (string) ( $sec['subtitle'] ?? '' ),
		'cta_text' => (string) ( $sec['cta_text'] ?? '' ),
		'cta_url'  => (string) ( $sec['cta_url'] ?? '' ),
	);
}

/**
 * BO-009: home hero values (decision 21).
 *
 * @return array{hero_title:string,hero_subtitle:string,hero_cta_text:string,hero_cta_url:string,featured_product:int,hero_bg:int}
 */
function erfanpro_home_hero() {
	$hero = erfanpro_option( 'home_hero', array() );
	if ( ! is_array( $hero ) ) {
		$hero = array();
	}
	return array(
		'hero_title'       => (string) ( $hero['hero_title'] ?? '' ),
		'hero_subtitle'    => (string) ( $hero['hero_subtitle'] ?? '' ),
		'hero_cta_text'    => (string) ( $hero['hero_cta_text'] ?? '' ),
		'hero_cta_url'     => (string) ( $hero['hero_cta_url'] ?? '' ),
		'featured_product' => (int) ( $hero['featured_product'] ?? 0 ),
		'hero_bg'          => (int) ( $hero['hero_bg'] ?? 0 ),
	);
}

/**
 * BO-011: why-us section values (same home_sections family as BO-003).
 *
 * @return array{kicker:string,title_a:string,title_b:string,desc:string,steps_title_a:string,steps_title_b:string,features:array}
 */
function erfanpro_why_us_section() {
	$sections = erfanpro_option( 'home_sections', array() );
	$sec      = isset( $sections['why_us'] ) && is_array( $sections['why_us'] ) ? $sections['why_us'] : array();
	$features = isset( $sec['features'] ) && is_array( $sec['features'] ) ? array_values( $sec['features'] ) : array();
	return array(
		'kicker'        => (string) ( $sec['kicker'] ?? '' ),
		'title_a'       => (string) ( $sec['title_a'] ?? '' ),
		'title_b'       => (string) ( $sec['title_b'] ?? '' ),
		'desc'          => (string) ( $sec['desc'] ?? '' ),
		'steps_title_a' => (string) ( $sec['steps_title_a'] ?? '' ),
		'steps_title_b' => (string) ( $sec['steps_title_b'] ?? '' ),
		'features'      => $features,
	);
}

/**
 * BO-012: shop hero + toolbar texts.
 *
 * @return array{hero_title:string,hero_subtitle:string,search_placeholder:string,purchasable_label:string}
 */
function erfanpro_shop_texts() {
	$texts = erfanpro_option( 'shop_texts', array() );
	if ( ! is_array( $texts ) ) {
		$texts = array();
	}
	return array(
		'hero_title'         => (string) ( $texts['hero_title'] ?? '' ),
		'hero_subtitle'      => (string) ( $texts['hero_subtitle'] ?? '' ),
		'search_placeholder' => (string) ( $texts['search_placeholder'] ?? '' ),
		'purchasable_label'  => (string) ( $texts['purchasable_label'] ?? '' ),
	);
}

/**
 * BO-010: city marquee values.
 *
 * Speed 0 means "unset" — the template then emits no inline duration
 * and the compiled CSS duration (38s) applies, keeping output
 * byte-identical to before this change.
 *
 * @return array{source:string,manual_cities:array,speed:int}
 */
function erfanpro_city_marquee() {
	$m = erfanpro_option( 'city_marquee', array() );
	if ( ! is_array( $m ) ) {
		$m = array();
	}
	$source = (string) ( $m['source'] ?? 'auto' );
	if ( ! in_array( $source, array( 'auto', 'manual' ), true ) ) {
		$source = 'auto';
	}
	return array(
		'source'        => $source,
		'manual_cities' => erfanpro_panel_rows( $m, 'manual_cities' ),
		'speed'         => max( 0, (int) ( $m['speed'] ?? 0 ) ),
	);
}

/**
 * BO-013: raw about texts (template applies reference fallbacks).
 *
 * @return array
 */
function erfanpro_about_texts() {
	$texts = erfanpro_option( 'about_texts', array() );
	return is_array( $texts ) ? $texts : array();
}

/**
 * BO-013: raw contact texts (template applies reference fallbacks).
 *
 * @return array
 */
function erfanpro_contact_texts() {
	$texts = erfanpro_option( 'contact_texts', array() );
	return is_array( $texts ) ? $texts : array();
}

/**
 * BO-014: raw texts of one simple page (template applies fallbacks).
 *
 * @param string $page Page key: guarantee|licenses|faq|careers|feedback.
 * @return array
 */
function erfanpro_minor_page_texts( $page ) {
	$all = erfanpro_option( 'minor_pages_texts', array() );
	if ( ! is_array( $all ) ) {
		return array();
	}
	return isset( $all[ $page ] ) && is_array( $all[ $page ] ) ? $all[ $page ] : array();
}

/**
 * One panel text with reference fallback (BO-013/014 pattern).
 *
 * Empty saved value means "not edited" and returns the fallback, so
 * templates render byte-identical output until an admin saves a value.
 *
 * @param array  $group    Saved group array (or empty).
 * @param string $key      Field key.
 * @param string $fallback Reference literal.
 * @return string
 */
function erfanpro_panel_text( $group, $key, $fallback ) {
	if ( is_array( $group ) && isset( $group[ $key ] ) && '' !== (string) $group[ $key ] ) {
		return (string) $group[ $key ];
	}
	return $fallback;
}

/**
 * Saved repeater rows of one group, fully-empty rows dropped.
 *
 * @param array  $group Saved group array (or empty).
 * @param string $key   Repeater key.
 * @return array
 */
function erfanpro_panel_rows( $group, $key ) {
	if ( ! is_array( $group ) || ! isset( $group[ $key ] ) || ! is_array( $group[ $key ] ) ) {
		return array();
	}
	$rows = array();
	foreach ( $group[ $key ] as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		if ( '' !== implode( '', array_map( 'strval', $row ) ) ) {
			$rows[] = $row;
		}
	}
	return $rows;
}

/**
 * Fallback rows merged with saved rows, positionally and per-field.
 *
 * A saved row replaces its fallback row field-by-field; missing saved
 * rows keep their fallback; extra saved rows are appended. Templates
 * that index fixed positions (counters, cards) use this so a partial
 * save can never blank out the remaining positions.
 *
 * @param array  $group    Saved group array (or empty).
 * @param string $key      Repeater key.
 * @param array  $fallback Fallback rows.
 * @return array
 */
function erfanpro_panel_rows_merged( $group, $key, $fallback ) {
	$merged = $fallback;
	foreach ( erfanpro_panel_rows( $group, $key ) as $i => $row ) {
		if ( isset( $merged[ $i ] ) && is_array( $merged[ $i ] ) ) {
			foreach ( $row as $rk => $rv ) {
				if ( '' !== (string) $rv ) {
					$merged[ $i ][ $rk ] = $rv;
				}
			}
		} else {
			$merged[ $i ] = $row;
		}
	}
	return $merged;
}

/**
 * Fallback string list merged with saved single-text rows.
 *
 * Same positional, skip-empty semantics as
 * erfanpro_panel_rows_merged(), for fixed lists rendered as separate
 * elements (ISO bullets, guarantee steps).
 *
 * @param array  $group    Saved group array (or empty).
 * @param string $key      Repeater key (rows with a `text` subfield).
 * @param array  $fallback Fallback strings.
 * @return array
 */
function erfanpro_panel_text_list( $group, $key, $fallback ) {
	$merged = $fallback;
	foreach ( erfanpro_panel_rows( $group, $key ) as $i => $row ) {
		$text = trim( (string) ( $row['text'] ?? '' ) );
		if ( '' !== $text ) {
			$merged[ $i ] = $text;
		}
	}
	return $merged;
}
