<?php
/**
 * Page section manager — visibility + order per page type.
 *
 * Design (user-ordered, 2026-10-24): from the theme options page, every
 * page's sections can be edited, enabled/disabled, and reordered
 * (up/down, movable among the other sections of the same page).
 *
 * Key rules:
 * - Layouts are keyed by PAGE TYPE / template (e.g. `about`, `contact`),
 *   never by numeric page id, so the Persian and Latin about/contact
 *   pages share one layout.
 * - Storage: the single `erfanpro_options` key, sub-key `page_sections`:
 *   [ page_key => [ section_id => [ 'enabled' => bool, 'order' => int ] ] ].
 * - Golden rule: when no layout is saved for a page, the renderer emits
 *   exactly the assembler's original section order, all enabled — so
 *   output is byte-identical to the pre-manager templates.
 * - Up/down + reset are plain form submits (no JS dependency): the
 *   buttons submit the options form and the move/reset is applied in
 *   the sanitize step. Drag-and-drop (assets/js/admin-sections.js)
 *   reorders the rows in the DOM and renumbers the hidden order
 *   inputs, so the saved shape is unchanged and the same sanitize
 *   step stores the new order; without JS the up/down buttons and
 *   the toggle checkboxes keep working exactly as before.
 * - Section texts for page families without a BO text group live in
 *   `section_texts` (same spec shape as BO-013/014, rendered by the
 *   generic text-groups field, sanitized by the same spec sanitizer).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Section registry: page key => [ label, sections: id => label ].
 *
 * The array order of `sections` IS the default order. Keep ids stable:
 * saved layouts reference them; renaming an id orphans saved rows
 * (they are dropped by the layout merger, and the section falls back
 * to enabled at its default position).
 *
 * @return array
 */
function erfanpro_sections_registry() {
	return array(
		'home'          => array(
			'label'    => __( 'صفحه خانه', 'erfanpro' ),
			'sections' => array(
				'hero'     => __( 'هیرو و محصول ویژه', 'erfanpro' ),
				'marquee'  => __( 'نوار شهرها', 'erfanpro' ),
				'projects' => __( 'پروژه‌های برگزیده', 'erfanpro' ),
				'about'    => __( 'درباره شرکت', 'erfanpro' ),
				'products' => __( 'محصولات منتخب', 'erfanpro' ),
				'services' => __( 'خدمات', 'erfanpro' ),
				'why_us'   => __( 'چرا عرفان صنعت؟', 'erfanpro' ),
				'cta'      => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'about'         => array(
			'label'    => __( 'درباره ما', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'story'   => __( 'داستان شرکت', 'erfanpro' ),
				'content' => __( 'محتوای ادیتور برگه', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'contact'       => array(
			'label'    => __( 'تماس با ما', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'body'    => __( 'کارت‌های اطلاعات و فرم', 'erfanpro' ),
				'content' => __( 'محتوای ادیتور برگه (بخش نیروهای فروش)', 'erfanpro' ),
			),
		),
		'guarantee'     => array(
			'label'    => __( 'شرایط گارانتی', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'body'    => __( 'کارت‌ها و مراحل', 'erfanpro' ),
				'content' => __( 'محتوای ادیتور برگه', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'licenses'      => array(
			'label'    => __( 'مجوزها و افتخارات', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'body'    => __( 'کارت‌ها و معرفی', 'erfanpro' ),
				'content' => __( 'محتوای ادیتور برگه', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'faq'           => array(
			'label'    => __( 'سوالات متداول', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'body'    => __( 'پرسش‌ها (آکاردئون)', 'erfanpro' ),
				'content' => __( 'محتوای ادیتور برگه', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'careers'       => array(
			'label'    => __( 'فرصت‌های شغلی', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'body'    => __( 'حوزه‌ها و رزومه', 'erfanpro' ),
				'content' => __( 'محتوای ادیتور برگه', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'feedback'      => array(
			'label'    => __( 'بازخورد مشتریان', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'body'    => __( 'شمارنده‌ها و شنیدن نظر', 'erfanpro' ),
				'content' => __( 'محتوای ادیتور برگه', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'shop'          => array(
			'label'    => __( 'فروشگاه', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'toolbar' => __( 'نوار فیلتر و شبکه محصولات', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'product'       => array(
			'label'    => __( 'تک‌محصول', 'erfanpro' ),
			'sections' => array(
				'hero'     => __( 'هیرو', 'erfanpro' ),
				'showcase' => __( 'گالری و کارت سفارش', 'erfanpro' ),
				'tabs'     => __( 'زبانه‌های توضیحات', 'erfanpro' ),
				'related'  => __( 'دیگر تولیدات', 'erfanpro' ),
			),
		),
		'projects'      => array(
			'label'    => __( 'آرشیو پروژه‌ها', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'toolbar' => __( 'نوار فیلتر و شبکه', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'projects_city' => array(
			'label'    => __( 'آرشیو پروژه‌های یک شهر', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'toolbar' => __( 'نوار فیلتر و شبکه', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'projects_type' => array(
			'label'    => __( 'آرشیو پروژه‌های یک نوع', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'toolbar' => __( 'نوار فیلتر و شبکه', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'services'      => array(
			'label'    => __( 'آرشیو خدمات', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'list'    => __( 'فهرست خدمات', 'erfanpro' ),
				'process' => __( 'مسیر همکاری', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'service'       => array(
			'label'    => __( 'تک‌خدمت', 'erfanpro' ),
			'sections' => array(
				'hero'    => __( 'هیرو', 'erfanpro' ),
				'details' => __( 'جزئیات و ویژگی‌ها', 'erfanpro' ),
				'faq'     => __( 'پرسش‌های متداول', 'erfanpro' ),
				'related' => __( 'پروژه‌های مرتبط', 'erfanpro' ),
				'others'  => __( 'سایر خدمات', 'erfanpro' ),
				'cta'     => __( 'دعوت به اقدام', 'erfanpro' ),
			),
		),
		'project'       => array(
			'label'    => __( 'تک‌پروژه', 'erfanpro' ),
			'sections' => array(
				'hero'     => __( 'هیرو', 'erfanpro' ),
				'gallery'  => __( 'گالری و کارت اطلاعات', 'erfanpro' ),
				'details'  => __( 'جزئیات و مشخصات', 'erfanpro' ),
				'products' => __( 'محصولات استفاده‌شده', 'erfanpro' ),
				'related'  => __( 'پروژه‌های مرتبط', 'erfanpro' ),
			),
		),
		'blog'          => array(
			'label'    => __( 'وبلاگ (آرشیو)', 'erfanpro' ),
			'sections' => array(
				'hero' => __( 'هیرو', 'erfanpro' ),
				'grid' => __( 'شبکه مقالات', 'erfanpro' ),
			),
		),
		'post'          => array(
			'label'    => __( 'تک‌نوشته وبلاگ', 'erfanpro' ),
			'sections' => array(
				'article_hero' => __( 'هیروی مقاله', 'erfanpro' ),
				'article'      => __( 'متن مقاله', 'erfanpro' ),
				'related'      => __( 'مقالات مرتبط', 'erfanpro' ),
				'comments'     => __( 'دیدگاه‌ها', 'erfanpro' ),
			),
		),
	);
}

/**
 * Effective layout for one page: sorted [ id => [ enabled, order ] ].
 *
 * No saved layout => registry order, all enabled (golden rule).
 * Unknown saved ids are dropped; known sections missing from a saved
 * layout fall back to enabled at their default position.
 *
 * @param string $page_key Registry page key.
 * @return array
 */
function erfanpro_sections_layout( $page_key ) {
	$registry = erfanpro_sections_registry();
	if ( ! isset( $registry[ $page_key ] ) ) {
		return array();
	}
	$defaults = array();
	$index    = array();
	$i        = 0;
	foreach ( $registry[ $page_key ]['sections'] as $id => $label ) {
		$defaults[ $id ] = array(
			'enabled' => true,
			'order'   => $i,
		);
		$index[ $id ] = $i++;
	}
	$saved = erfanpro_option( 'page_sections', array() );
	if ( ! is_array( $saved ) || ! isset( $saved[ $page_key ] ) || ! is_array( $saved[ $page_key ] ) ) {
		return $defaults;
	}
	$layout = $defaults;
	foreach ( $saved[ $page_key ] as $id => $row ) {
		if ( ! isset( $defaults[ $id ] ) || ! is_array( $row ) ) {
			continue;
		}
		$layout[ $id ] = array(
			'enabled' => ! empty( $row['enabled'] ),
			'order'   => isset( $row['order'] ) ? (int) $row['order'] : $defaults[ $id ]['order'],
		);
	}
	uasort(
		$layout,
		function ( $a, $b ) use ( $index ) {
			// Order first; default index breaks ties deterministically.
			if ( $a['order'] !== $b['order'] ) {
				return $a['order'] <=> $b['order'];
			}
			return 0;
		}
	);
	// Re-key sort stability: uasort keeps keys; ties keep insertion order
	// (registry order), which is the default index order. Good enough.
	return $layout;
}

/**
 * Short admin-only description of one section, to tell same-named
 * sections apart (e.g. the sixteen «هیرو» rows). Labels that are
 * already specific get no description (empty string = render none).
 *
 * @param string $page_key Registry page key.
 * @param string $id       Section id.
 * @return string
 */
function erfanpro_section_desc( $page_key, $id ) {
	$overrides = array(
		'home:hero'           => __( 'بنر اصلی و محصول ویژه‌ی صفحه‌ی خانه', 'erfanpro' ),
		'product:hero'        => __( 'بنر بالای تک‌محصول', 'erfanpro' ),
		'product:related'     => __( 'دیگر تولیدات شرکت', 'erfanpro' ),
		'service:related'     => __( 'پروژه‌های مرتبط با این خدمت', 'erfanpro' ),
		'project:related'     => __( 'پروژه‌های مرتبط', 'erfanpro' ),
		'post:related'        => __( 'مقالات مرتبط', 'erfanpro' ),
		'shop:toolbar'        => __( 'فیلتر، مرتب‌سازی و شبکه‌ی محصولات', 'erfanpro' ),
		'projects:toolbar'    => __( 'فیلتر و شبکه‌ی پروژه‌ها', 'erfanpro' ),
		'projects_city:toolbar' => __( 'فیلتر و شبکه‌ی پروژه‌های این شهر', 'erfanpro' ),
		'projects_type:toolbar' => __( 'فیلتر و شبکه‌ی پروژه‌های این نوع', 'erfanpro' ),
	);
	$key = $page_key . ':' . $id;
	if ( isset( $overrides[ $key ] ) ) {
		return $overrides[ $key ];
	}
	$generic = array(
		'hero'    => __( 'بنر و تیتر بالای برگه', 'erfanpro' ),
		'cta'     => __( 'دعوت به اقدام و تماس', 'erfanpro' ),
		'content' => __( 'متن و محتوای ادیتور برگه', 'erfanpro' ),
		'toolbar' => __( 'نوار فیلتر و شبکه‌ی موارد', 'erfanpro' ),
		'related' => __( 'موارد مرتبط', 'erfanpro' ),
		'body'    => __( 'بدنه‌ی اصلی برگه', 'erfanpro' ),
	);
	return $generic[ $id ] ?? '';
}

/**
 * Render one page's sections in saved order, skipping disabled ones.
 *
 * Section definitions:
 * - string: template-part slug (get_template_part with $args).
 * - [ 'part' => slug, 'args' => [...] ]: part with its own args
 *   (falls back to $args when 'args' is absent).
 * - [ 'callback' => callable ]: invoked with $args (for sections that
 *   are not a single template part: the product gallery+order-card
 *   grid wrapper, the conditional comments block).
 *
 * Adds no wrapper markup of its own.
 *
 * @param string $page_key Registry page key.
 * @param array  $sections Ordered map id => definition.
 * @param array  $args     Default template-part args.
 * @return void
 */
function erfanpro_render_page_sections( $page_key, $sections, $args = array() ) {
	$layout = erfanpro_sections_layout( $page_key );
	foreach ( $layout as $id => $row ) {
		if ( empty( $row['enabled'] ) ) {
			continue;
		}
		if ( ! isset( $sections[ $id ] ) ) {
			continue;
		}
		$def = $sections[ $id ];
		if ( is_string( $def ) ) {
			get_template_part( $def, null, $args );
		} elseif ( isset( $def['callback'] ) && is_callable( $def['callback'] ) ) {
			call_user_func( $def['callback'], $args );
		} elseif ( isset( $def['part'] ) ) {
			$part_args = isset( $def['args'] ) && is_array( $def['args'] ) ? $def['args'] : $args;
			get_template_part( $def['part'], null, $part_args );
		}
	}
}

/**
 * Raw section texts of one group (template applies reference fallbacks).
 *
 * @param string $group Group key inside `section_texts`.
 * @return array
 */
function erfanpro_section_texts( $group ) {
	$all = erfanpro_option( 'section_texts', array() );
	if ( ! is_array( $all ) ) {
		return array();
	}
	return isset( $all[ $group ] ) && is_array( $all[ $group ] ) ? $all[ $group ] : array();
}

/**
 * Sanitize the page_sections sub-key (called from erfanpro_options_sanitize).
 *
 * The manager UI posts a hidden marker (`erfanpro_sections_present`);
 * when it is absent the form predates the manager, so the previous
 * value is kept untouched instead of being wiped.
 *
 * Move/reset buttons submit the same form: their values are read from
 * $_POST here and applied to the submitted layout before it is saved.
 *
 * @param array $input Raw erfanpro_options input.
 * @param array $prev  Previously saved erfanpro_options.
 * @return array
 */
function erfanpro_sections_sanitize( $input, $prev ) {
	$prev_layout = isset( $prev['page_sections'] ) && is_array( $prev['page_sections'] ) ? $prev['page_sections'] : array();
	if ( ! isset( $_POST['erfanpro_sections_present'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- marker only; capability checked by options.php.
		return $prev_layout;
	}
	$registry  = erfanpro_sections_registry();
	$submitted = isset( $input['page_sections'] ) && is_array( $input['page_sections'] ) ? $input['page_sections'] : array();
	$clean     = array();
	foreach ( $registry as $page_key => $page ) {
		$collected = array();
		$default_i = 0;
		foreach ( $page['sections'] as $id => $label ) {
			$row_in = isset( $submitted[ $page_key ][ $id ] ) && is_array( $submitted[ $page_key ][ $id ] ) ? $submitted[ $page_key ][ $id ] : array();
			$collected[] = array(
				'id'        => $id,
				'enabled'   => ! empty( $row_in['enabled'] ),
				'order'     => isset( $row_in['order'] ) ? (int) $row_in['order'] : 999,
				'default_i' => $default_i++,
			);
		}
		usort(
			$collected,
			function ( $a, $b ) {
				if ( $a['order'] !== $b['order'] ) {
					return $a['order'] <=> $b['order'];
				}
				return $a['default_i'] <=> $b['default_i'];
			}
		);
		/* Move: swap the requested row with its neighbour. */
		$move_raw = isset( $_POST['erfanpro_section_move'] ) ? sanitize_text_field( wp_unslash( $_POST['erfanpro_section_move'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- move is applied inside the already-capability-checked options save.
		if ( '' !== $move_raw ) {
			$parts = explode( ':', $move_raw, 3 );
			if ( 3 === count( $parts ) && $parts[0] === $page_key ) {
				$move_id  = sanitize_key( $parts[1] );
				$move_dir = 'down' === $parts[2] ? 1 : -1;
				$pos      = null;
				foreach ( $collected as $i => $row ) {
					if ( $row['id'] === $move_id ) {
						$pos = $i;
						break;
					}
				}
				if ( null !== $pos ) {
					$swap = $pos + $move_dir;
					if ( isset( $collected[ $swap ] ) ) {
						$tmp                 = $collected[ $pos ];
						$collected[ $pos ]  = $collected[ $swap ];
						$collected[ $swap ] = $tmp;
					}
				}
			}
		}
		$rows = array();
		foreach ( $collected as $i => $row ) {
			$rows[ $row['id'] ] = array(
				'enabled' => $row['enabled'],
				'order'   => $i,
			);
		}
		$clean[ $page_key ] = $rows;
	}
	/* Reset: drop the requested page so it falls back to defaults. */
	$reset_raw = isset( $_POST['erfanpro_section_reset'] ) ? sanitize_key( wp_unslash( $_POST['erfanpro_section_reset'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- same options save as above.
	if ( '' !== $reset_raw && isset( $clean[ $reset_raw ] ) ) {
		unset( $clean[ $reset_raw ] );
	}
	return $clean;
}

/**
 * Admin field: the section manager (one collapsible card per page type).
 *
 * Rendered inside the main options form: enabled checkboxes + hidden
 * order inputs submit with it; up/down + reset are plain submit
 * buttons whose values the sanitizer applies. The row markup keeps
 * the exact same field names as before, so the sanitize/move/reset
 * logic and the AJAX tab save are untouched.
 *
 * The sticky summary bar (counts, jump links, search) and the
 * collapsible cards are progressive enhancement: without JS every
 * group is open, the search box and drag handles are hidden by CSS,
 * and the toggle switches still work as plain checkboxes.
 */
function erfanpro_options_sections_field() {
	$registry       = erfanpro_sections_registry();
	$total_pages    = count( $registry );
	$total_sections = 0;
	$disabled       = 0;
	$layouts        = array();
	foreach ( $registry as $page_key => $page ) {
		$layouts[ $page_key ] = erfanpro_sections_layout( $page_key );
		$total_sections      += count( $page['sections'] );
		foreach ( $layouts[ $page_key ] as $row ) {
			if ( empty( $row['enabled'] ) ) {
				$disabled++;
			}
		}
	}
	?>
	<div class="erfanpro-sm-manager" data-erfanpro-sm-manager>
		<input type="hidden" name="erfanpro_sections_present" value="1">
		<div class="erfanpro-sm-summary" data-erfanpro-sm-summary>
			<p class="erfanpro-sm-summary-counts">
				<span><?php echo esc_html( erfanpro_fa_digits( $total_pages ) ); ?> <?php esc_html_e( 'برگه', 'erfanpro' ); ?></span>
				<span aria-hidden="true"> · </span>
				<span><?php echo esc_html( erfanpro_fa_digits( $total_sections ) ); ?> <?php esc_html_e( 'سکشن', 'erfanpro' ); ?></span>
				<span aria-hidden="true"> · </span>
				<span><span data-erfanpro-sm-disabled-count><?php echo esc_html( erfanpro_fa_digits( $disabled ) ); ?></span> <?php esc_html_e( 'غیرفعال', 'erfanpro' ); ?></span>
			</p>
			<nav class="erfanpro-sm-jumps" aria-label="<?php esc_attr_e( 'پرش به برگه', 'erfanpro' ); ?>">
				<?php foreach ( $registry as $page_key => $page ) : ?>
					<a href="#erfanpro-sm-group-<?php echo esc_attr( $page_key ); ?>" data-erfanpro-sm-jump="<?php echo esc_attr( $page_key ); ?>"><?php echo esc_html( $page['label'] ); ?></a>
				<?php endforeach; ?>
			</nav>
			<input type="search" class="erfanpro-sm-search" data-erfanpro-sm-search placeholder="<?php esc_attr_e( 'جستجوی سکشن یا برگه…', 'erfanpro' ); ?>" aria-label="<?php esc_attr_e( 'جستجوی سکشن یا برگه', 'erfanpro' ); ?>">
		</div>
		<p class="description"><?php esc_html_e( 'هر سکشن را با کشیدن دستگیره جابه‌جا کنید یا با دکمه‌های بالا/پایین؛ سوییچ هر ردیف یعنی نمایش آن سکشن. «بازنشانی» چیدمان همان برگه را به حالت پیش‌فرض برمی‌گرداند. تا وقتی چیزی ذخیره نشده باشد، همه سکشن‌ها فعال و به ترتیب فعلی نمایش داده می‌شوند.', 'erfanpro' ); ?></p>
		<?php
		foreach ( $registry as $page_key => $page ) :
			$layout = $layouts[ $page_key ];
			$base   = 'erfanpro_options[page_sections][' . $page_key . ']';
			$count  = count( $page['sections'] );
			$on     = 0;
			foreach ( $layout as $row ) {
				if ( ! empty( $row['enabled'] ) ) {
					$on++;
				}
			}
			?>
			<div class="erfanpro-sm-group" id="erfanpro-sm-group-<?php echo esc_attr( $page_key ); ?>" data-erfanpro-sm-group="<?php echo esc_attr( $page_key ); ?>" data-page-label="<?php echo esc_attr( $page['label'] ); ?>">
				<div class="erfanpro-sm-group-head">
					<button type="button" class="erfanpro-sm-group-toggle" data-erfanpro-sm-toggle aria-expanded="true">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6" /></svg>
						<span class="erfanpro-sm-group-title"><?php echo esc_html( $page['label'] ); ?></span>
						<span class="erfanpro-sm-group-meta"><?php echo esc_html( erfanpro_fa_digits( $count ) ); ?> <?php esc_html_e( 'سکشن', 'erfanpro' ); ?> · <span data-erfanpro-sm-group-status><?php echo esc_html( erfanpro_fa_digits( $on ) ); ?> <?php esc_html_e( 'از', 'erfanpro' ); ?> <?php echo esc_html( erfanpro_fa_digits( $count ) ); ?> <?php esc_html_e( 'فعال', 'erfanpro' ); ?></span></span>
					</button>
					<button type="submit" class="button erfanpro-sm-reset" name="erfanpro_section_reset" value="<?php echo esc_attr( $page_key ); ?>"><?php esc_html_e( 'بازنشانی', 'erfanpro' ); ?></button>
				</div>
				<ul class="erfanpro-sm-list" data-erfanpro-sm-list>
					<?php
					$pos = 0;
					foreach ( $layout as $id => $row ) :
						$pos++;
						$desc = erfanpro_section_desc( $page_key, $id );
						?>
						<li class="erfanpro-sm-row<?php echo $row['enabled'] ? '' : ' is-disabled'; ?>" draggable="true" data-section-id="<?php echo esc_attr( $id ); ?>">
							<span class="erfanpro-sm-handle" title="<?php esc_attr_e( 'بکشید تا جابه‌جا شود', 'erfanpro' ); ?>" aria-hidden="true"><svg width="10" height="16" viewBox="0 0 10 16" fill="currentColor" aria-hidden="true"><circle cx="2.5" cy="2.5" r="1.5" /><circle cx="7.5" cy="2.5" r="1.5" /><circle cx="2.5" cy="8" r="1.5" /><circle cx="7.5" cy="8" r="1.5" /><circle cx="2.5" cy="13.5" r="1.5" /><circle cx="7.5" cy="13.5" r="1.5" /></svg></span>
							<span class="erfanpro-sm-num" data-erfanpro-sm-num><?php echo esc_html( erfanpro_fa_digits( $pos ) ); ?></span>
							<span class="erfanpro-sm-text">
								<span class="erfanpro-sm-name"><?php echo esc_html( $page['sections'][ $id ] ); ?></span>
								<?php if ( '' !== $desc ) : ?>
									<span class="erfanpro-sm-desc"><?php echo esc_html( $desc ); ?></span>
								<?php endif; ?>
							</span>
							<span class="erfanpro-sm-badge"<?php echo $row['enabled'] ? ' hidden' : ''; ?>><?php esc_html_e( 'غیرفعال', 'erfanpro' ); ?></span>
							<label class="erfanpro-sm-switch" title="<?php esc_attr_e( 'فعال / غیرفعال', 'erfanpro' ); ?>">
								<input type="checkbox" name="<?php echo esc_attr( $base ); ?>[<?php echo esc_attr( $id ); ?>][enabled]" value="1" <?php checked( $row['enabled'] ); ?>>
								<span class="erfanpro-sm-slider" aria-hidden="true"></span>
								<span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'نمایش سکشن «%s»', 'erfanpro' ), $page['sections'][ $id ] ) ); ?></span>
							</label>
							<input type="hidden" name="<?php echo esc_attr( $base ); ?>[<?php echo esc_attr( $id ); ?>][order]" value="<?php echo esc_attr( (string) (int) $row['order'] ); ?>" data-erfanpro-sm-order>
							<span class="erfanpro-sm-moves">
								<button type="submit" class="button erfanpro-sm-move" name="erfanpro_section_move" value="<?php echo esc_attr( $page_key . ':' . $id . ':up' ); ?>" title="<?php esc_attr_e( 'یک ردیف بالاتر', 'erfanpro' ); ?>" aria-label="<?php esc_attr_e( 'یک ردیف بالاتر', 'erfanpro' ); ?>"<?php echo 1 === $pos ? ' disabled' : ''; ?>>↑</button>
								<button type="submit" class="button erfanpro-sm-move" name="erfanpro_section_move" value="<?php echo esc_attr( $page_key . ':' . $id . ':down' ); ?>" title="<?php esc_attr_e( 'یک ردیف پایین‌تر', 'erfanpro' ); ?>" aria-label="<?php esc_attr_e( 'یک ردیف پایین‌تر', 'erfanpro' ); ?>"<?php echo $pos === $count ? ' disabled' : ''; ?>>↓</button>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php
		endforeach;
		?>
	</div>
	<?php
}
