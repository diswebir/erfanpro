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
		if ( in_array( $key, array( 'trust_badges', 'home_counters', 'home_sections', 'home_hero', 'shop_texts' ), true ) ) {
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
