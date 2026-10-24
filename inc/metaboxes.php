<?php
/**
 * Phase 1 — data layer: product + project metaboxes.
 *
 * Field map is the blueprint's JetEngine definition (meta-4/meta-5/meta-8
 * on `product`, meta-2 on `projects`), plus the legacy keys the plan's
 * data map requires (`usage_product`, `product-feature`,
 * `product-feature2_copy`, `image-2`…`image-6`, `gallery-filed`,
 * `zireonvan`, `english_titl`, `product-in-projects`). Every meta key is
 * written under its EXACT blueprint name — typos included — via the
 * helpers in inc/meta-format.php.
 *
 * Shared field renderers live here; inc/metaboxes-services.php reuses them.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Shared renderers
 * ---------------------------------------------------------------------- */

/**
 * Nonce field printed once per metabox.
 */
function erfanpro_meta_nonce_field() {
	wp_nonce_field( 'erfanpro_save_meta', 'erfanpro_meta_nonce' );
}

/**
 * Text input row.
 */
function erfanpro_field_text( $name, $label, $value, $desc = '', $placeholder = '' ) {
	printf(
		'<div class="erfanpro-metabox-field"><label for="erfanpro-%1$s">%2$s</label>' .
		'<input type="text" id="erfanpro-%1$s" name="erfanpro_meta[%1$s]" value="%3$s" class="widefat" placeholder="%4$s" />%5$s</div>',
		esc_attr( $name ),
		esc_html( $label ),
		esc_attr( (string) $value ),
		esc_attr( $placeholder ),
		$desc ? '<span class="description">' . esc_html( $desc ) . '</span>' : ''
	);
}

/**
 * WYSIWYG row (stored as plain text/html meta, like JetEngine [text]).
 */
function erfanpro_field_editor( $name, $label, $value, $desc = '' ) {
	echo '<div class="erfanpro-metabox-field full"><label>' . esc_html( $label ) . '</label>';
	wp_editor(
		(string) $value,
		'erfanpro_' . preg_replace( '/[^a-z0-9_]/i', '_', $name ),
		array(
			'textarea_name' => 'erfanpro_meta[' . $name . ']',
			'textarea_rows' => 6,
			'media_buttons' => false,
			'teeny'         => true,
		)
	);
	if ( $desc ) {
		echo '<span class="description">' . esc_html( $desc ) . '</span>';
	}
	echo '</div>';
}

/**
 * Single media picker (image or any file type — the frame allows all
 * types for `single`, images only for `multiple`).
 */
function erfanpro_field_media_single( $name, $label, $attachment_id, $desc = '' ) {
	$attachment_id = absint( $attachment_id );
	$preview       = '';
	if ( $attachment_id ) {
		if ( wp_attachment_is_image( $attachment_id ) ) {
			$preview = wp_get_attachment_image( $attachment_id, 'thumbnail' );
		} else {
			$preview = '<span class="erfanpro-file-name">' . esc_html( basename( get_attached_file( $attachment_id ) ) ) . '</span>';
		}
	}
	?>
	<div class="erfanpro-metabox-field" data-erfanpro-media="single">
		<label><?php echo esc_html( $label ); ?></label>
		<div class="erfanpro-media-row">
			<input type="hidden" data-erfanpro-media-id name="erfanpro_meta[<?php echo esc_attr( $name ); ?>]" value="<?php echo esc_attr( $attachment_id ? (string) $attachment_id : '' ); ?>" />
			<button type="button" class="button" data-erfanpro-media-select data-title="<?php echo esc_attr( $label ); ?>"><?php esc_html_e( 'انتخاب فایل', 'erfanpro' ); ?></button>
			<button type="button" class="button-link" data-erfanpro-media-remove><?php esc_html_e( 'حذف', 'erfanpro' ); ?></button>
		</div>
		<div data-erfanpro-media-preview style="<?php echo $preview ? '' : 'display:none'; ?>"><?php echo $preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php if ( $desc ) : ?><span class="description"><?php echo esc_html( $desc ); ?></span><?php endif; ?>
	</div>
	<?php
}

/**
 * Multiple media picker (stores comma-separated IDs, gallery shape).
 *
 * @param string $name  Meta key.
 * @param string $label Label.
 * @param int[]  $ids   Attachment IDs.
 */
function erfanpro_field_media_multi( $name, $label, $ids, $desc = '' ) {
	$ids     = array_values( array_filter( array_map( 'absint', (array) $ids ) ) );
	$preview = '';
	foreach ( $ids as $id ) {
		$img = wp_get_attachment_image( $id, 'thumbnail' );
		if ( $img ) {
			$preview .= $img;
		}
	}
	?>
	<div class="erfanpro-metabox-field full" data-erfanpro-media="multiple">
		<label><?php echo esc_html( $label ); ?></label>
		<div class="erfanpro-media-row">
			<input type="hidden" data-erfanpro-media-id name="erfanpro_meta[<?php echo esc_attr( $name ); ?>]" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>" />
			<button type="button" class="button" data-erfanpro-media-select data-title="<?php echo esc_attr( $label ); ?>"><?php esc_html_e( 'انتخاب تصاویر', 'erfanpro' ); ?></button>
			<button type="button" class="button-link" data-erfanpro-media-remove><?php esc_html_e( 'حذف همه', 'erfanpro' ); ?></button>
		</div>
		<div data-erfanpro-media-preview style="<?php echo $preview ? '' : 'display:none'; ?>"><?php echo $preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php if ( $desc ) : ?><span class="description"><?php echo esc_html( $desc ); ?></span><?php endif; ?>
	</div>
	<?php
}

/**
 * Repeater field (JetEngine item-N shape).
 *
 * @param string $key       Meta key.
 * @param string $label     Label.
 * @param array  $items     Current items (list of assoc arrays).
 * @param array  $subfields Map name => array('label'=>, 'type'=> text|textarea|html).
 * @param string $add_label Add-button label.
 */
function erfanpro_field_repeater( $key, $label, $items, $subfields, $add_label = '' ) {
	if ( ! $add_label ) {
		$add_label = __( 'افزودن ردیف', 'erfanpro' );
	}
	if ( empty( $items ) ) {
		$items = array( array() );
	}

	$render_row = function ( $index, $item ) use ( $key, $subfields ) {
		$ph = is_string( $index ) ? $index : (string) $index; // '__i__' in template.
		echo '<div class="erfanpro-repeater-row" data-erfanpro-row data-erfanpro-index="' . esc_attr( $ph ) . '">';
		foreach ( $subfields as $sub => $cfg ) {
			$val   = isset( $item[ $sub ] ) ? $item[ $sub ] : '';
			$fname = 'erfanpro_meta[' . $key . '][' . $ph . '][' . $sub . ']';
			echo '<div class="erfanpro-field"><label>' . esc_html( $cfg['label'] ) . '</label>';
			if ( 'textarea' === $cfg['type'] || 'html' === $cfg['type'] ) {
				echo '<textarea name="' . esc_attr( $fname ) . '" rows="3">' . esc_textarea( (string) $val ) . '</textarea>';
			} else {
				echo '<input type="text" name="' . esc_attr( $fname ) . '" value="' . esc_attr( (string) $val ) . '" />';
			}
			echo '</div>';
		}
		echo '<button type="button" class="button erfanpro-remove" data-erfanpro-remove aria-label="' . esc_attr__( 'حذف ردیف', 'erfanpro' ) . '">✕</button>';
		echo '</div>';
	};
	?>
	<div class="erfanpro-metabox-field full" data-erfanpro-repeater>
		<label><?php echo esc_html( $label ); ?></label>
		<div class="erfanpro-repeater-rows" data-erfanpro-rows>
			<?php
			foreach ( $items as $i => $item ) {
				$render_row( $i, is_array( $item ) ? $item : array() );
			}
			?>
		</div>
		<script type="text/html" data-erfanpro-template><?php $render_row( '__i__', array() ); ?></script>
		<button type="button" class="button" data-erfanpro-add><?php echo esc_html( $add_label ); ?></button>
	</div>
	<?php
}

/**
 * Scrollable checkbox list for post multi-selects.
 *
 * @param string $name     Meta key (posted as erfanpro_meta[$name][]).
 * @param string $label    Label.
 * @param array  $options  Map ID => title.
 * @param array  $selected Selected IDs.
 */
function erfanpro_field_checklist( $name, $label, $options, $selected, $desc = '' ) {
	$selected = array_map( 'absint', (array) $selected );
	?>
	<div class="erfanpro-metabox-field full">
		<label><?php echo esc_html( $label ); ?></label>
		<div style="max-height:180px;overflow:auto;border:1px solid #dcdcde;border-radius:4px;padding:8px;background:#fff;">
			<?php foreach ( $options as $id => $title ) : ?>
				<label style="display:block;padding:3px 0;">
					<input type="checkbox" name="erfanpro_meta[<?php echo esc_attr( $name ); ?>][]" value="<?php echo esc_attr( (string) $id ); ?>" <?php checked( in_array( (int) $id, $selected, true ) ); ?> />
					<?php echo esc_html( $title ); ?>
				</label>
			<?php endforeach; ?>
			<?php if ( empty( $options ) ) : ?>
				<span class="description"><?php esc_html_e( 'موردی برای انتخاب نیست.', 'erfanpro' ); ?></span>
			<?php endif; ?>
		</div>
		<?php if ( $desc ) : ?><span class="description"><?php echo esc_html( $desc ); ?></span><?php endif; ?>
	</div>
	<?php
}

/**
 * Guard for the shared save routine.
 *
 * @param int $post_id Post ID.
 * @return array|null Unslashed erfanpro_meta input, or null to abort.
 */
function erfanpro_meta_save_guard( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return null;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return null;
	}
	if ( ! isset( $_POST['erfanpro_meta_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['erfanpro_meta_nonce'] ) ), 'erfanpro_save_meta' )
	) {
		return null;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return null;
	}
	if ( ! isset( $_POST['erfanpro_meta'] ) || ! is_array( $_POST['erfanpro_meta'] ) ) {
		return null;
	}
	return wp_unslash( $_POST['erfanpro_meta'] );
}

/* -------------------------------------------------------------------------
 * Product metaboxes
 * ---------------------------------------------------------------------- */

/**
 * Register product metaboxes.
 */
function erfanpro_product_metaboxes() {
	if ( ! post_type_exists( 'product' ) ) {
		return;
	}
	add_meta_box(
		'erfanpro-product-main',
		__( 'اطلاعات تکمیلی محصول (بلوپرینت)', 'erfanpro' ),
		'erfanpro_product_main_box',
		'product',
		'normal',
		'default'
	);
	add_meta_box(
		'erfanpro-product-gallery',
		__( 'گالری محصول (بلوپرینت)', 'erfanpro' ),
		'erfanpro_product_gallery_box',
		'product',
		'normal',
		'default'
	);
	add_meta_box(
		'erfanpro-product-faq',
		__( 'پرسش‌های متداول محصول', 'erfanpro' ),
		'erfanpro_product_faq_box',
		'product',
		'normal',
		'default'
	);
	add_meta_box(
		'erfanpro-product-usage',
		__( 'کاربردها و ویژگی‌ها', 'erfanpro' ),
		'erfanpro_product_usage_box',
		'product',
		'normal',
		'default'
	);
	add_meta_box(
		'erfanpro-product-relations',
		__( 'ارتباط‌ها', 'erfanpro' ),
		'erfanpro_product_relations_box',
		'product',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'erfanpro_product_metaboxes' );

/**
 * Main product box: texts + codes + catalog file.
 */
function erfanpro_product_main_box( $post ) {
	erfanpro_meta_nonce_field();
	$id = $post->ID;
	echo '<div class="erfanpro-metabox-grid">';
	erfanpro_field_text( 'zireonvan', __( 'زیرعنوان', 'erfanpro' ), get_post_meta( $id, 'zireonvan', true ) );
	erfanpro_field_text( 'english_titl', __( 'عنوان انگلیسی', 'erfanpro' ), get_post_meta( $id, 'english_titl', true ) );
	erfanpro_field_editor( 'about-productt', __( 'درباره محصول (تب توضیحات تکمیلی)', 'erfanpro' ), get_post_meta( $id, 'about-productt', true ) );
	erfanpro_field_text( 'before-sale-c', __( 'توضیح قبل از فروش', 'erfanpro' ), get_post_meta( $id, 'before-sale-c', true ) );
	foreach ( array(
		'product_id'    => __( 'کد محصول (ایران‌کد)', 'erfanpro' ),
		'gs1_id'        => __( 'GS1', 'erfanpro' ),
		'national_code' => __( 'کد ملی کالا', 'erfanpro' ),
		'hs_id'         => __( 'HS', 'erfanpro' ),
		'isic_id'       => __( 'ISIC', 'erfanpro' ),
		'cpc_id'        => __( 'CPC', 'erfanpro' ),
	) as $key => $label ) {
		erfanpro_field_text( $key, $label, get_post_meta( $id, $key, true ) );
	}
	erfanpro_field_media_single(
		'product-catalog-file',
		__( 'فایل کاتالوگ محصول', 'erfanpro' ),
		get_post_meta( $id, 'product-catalog-file', true ),
		__( 'اگر خالی باشد، در قالب دکمه کاتالوگ کامل سایت نمایش داده می‌شود (تصمیم ۳۱).', 'erfanpro' )
	);
	echo '</div>';
}

/**
 * Product gallery box.
 */
function erfanpro_product_gallery_box( $post ) {
	erfanpro_meta_nonce_field();
	$id = $post->ID;
	erfanpro_field_media_multi(
		'galerry_produ',
		__( 'گالری محصول (galerry_produ)', 'erfanpro' ),
		erfanpro_get_id_list( $id, 'galerry_produ' )
	);
	erfanpro_field_media_multi(
		'gallery-filed',
		__( 'گالری دوم (gallery-filed)', 'erfanpro' ),
		erfanpro_get_id_list( $id, 'gallery-filed' )
	);
	erfanpro_field_text(
		'product_video',
		__( 'ویدیوی محصول (URL)', 'erfanpro' ),
		get_post_meta( $id, 'product_video', true ),
		__( 'لینک مستقیم فایل ویدیو (mp4) — در گالری صفحه محصول به‌عنوان اسلاید آخر نمایش داده می‌شود (پاسخ ۱۸).', 'erfanpro' )
	);
	echo '<div class="erfanpro-metabox-grid">';
	for ( $n = 2; $n <= 6; $n++ ) {
		erfanpro_field_media_single(
			'image-' . $n,
			sprintf( __( 'تصویر %d', 'erfanpro' ), $n ),
			get_post_meta( $id, 'image-' . $n, true )
		);
	}
	echo '</div>';
}

/**
 * Product FAQ box.
 */
function erfanpro_product_faq_box( $post ) {
	erfanpro_meta_nonce_field();
	$id     = $post->ID;
	$fields = array(
		'name_question'   => array(
			'label' => __( 'سؤال', 'erfanpro' ),
			'type'  => 'text',
		),
		'answer_question' => array(
			'label' => __( 'پاسخ', 'erfanpro' ),
			'type'  => 'html',
		),
	);
	erfanpro_field_repeater( 'faq', __( 'پرسش‌های متداول (faq)', 'erfanpro' ), erfanpro_get_repeater( $id, 'faq' ), $fields, __( 'افزودن پرسش', 'erfanpro' ) );
	erfanpro_field_repeater( 'mfaq', __( 'پرسش‌های متداول دوم (mfaq)', 'erfanpro' ), erfanpro_get_repeater( $id, 'mfaq' ), $fields, __( 'افزودن پرسش', 'erfanpro' ) );
}

/**
 * Product usage/features box.
 */
function erfanpro_product_usage_box( $post ) {
	erfanpro_meta_nonce_field();
	$id = $post->ID;
	erfanpro_field_repeater(
		'usage_product',
		__( 'آموزش نصب / کاربردها (usage_product)', 'erfanpro' ),
		erfanpro_get_repeater( $id, 'usage_product' ),
		array( 'add-new-usage' => array( 'label' => __( 'مورد کاربرد', 'erfanpro' ), 'type' => 'text' ) ),
		__( 'افزودن مورد', 'erfanpro' )
	);
	erfanpro_field_repeater(
		'product-feature',
		__( 'ویژگی‌ها (product-feature)', 'erfanpro' ),
		erfanpro_get_repeater( $id, 'product-feature' ),
		array( 'add-new-feature' => array( 'label' => __( 'ویژگی', 'erfanpro' ), 'type' => 'text' ) ),
		__( 'افزودن ویژگی', 'erfanpro' )
	);
	erfanpro_field_repeater(
		'product-feature2_copy',
		__( 'ویژگی‌های متغیر (product-feature2_copy)', 'erfanpro' ),
		erfanpro_get_repeater( $id, 'product-feature2_copy' ),
		array(
			'add-new-feature'   => array(
				'label' => __( 'عنوان', 'erfanpro' ),
				'type'  => 'text',
			),
			'vaule_of_variable' => array(
				'label' => __( 'مقدار', 'erfanpro' ),
				'type'  => 'text',
			),
		),
		__( 'افزودن ویژگی', 'erfanpro' )
	);
}

/**
 * Product relations box.
 */
function erfanpro_product_relations_box( $post ) {
	erfanpro_meta_nonce_field();
	$id = $post->ID;

	$projects = array();
	if ( post_type_exists( 'projects' ) ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'projects',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		foreach ( $q->posts as $pid ) {
			$projects[ $pid ] = get_the_title( $pid );
		}
		wp_reset_postdata();
	}
	erfanpro_field_checklist(
		'project-ids',
		__( 'پروژه‌های مرتبط (project-ids)', 'erfanpro' ),
		$projects,
		erfanpro_get_id_list( $id, 'project-ids' )
	);
	erfanpro_field_text(
		'usage_countr',
		__( 'شناسه‌های usage_countr (جدا با ویرگول)', 'erfanpro' ),
		implode( ',', erfanpro_get_id_list( $id, 'usage_countr' ) ),
		__( 'در دیتابیس به‌صورت آرایه شناسه ذخیره می‌شود.', 'erfanpro' )
	);
}

/**
 * Save product meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function erfanpro_save_product_meta( $post_id, $post ) {
	$data = erfanpro_meta_save_guard( $post_id );
	if ( null === $data ) {
		return;
	}

	foreach ( array( 'zireonvan', 'english_titl', 'before-sale-c', 'product_id', 'gs1_id', 'national_code', 'hs_id', 'isic_id', 'cpc_id' ) as $key ) {
		update_post_meta( $post_id, $key, sanitize_text_field( (string) ( $data[ $key ] ?? '' ) ) );
	}
	update_post_meta( $post_id, 'about-productt', wp_kses_post( (string) ( $data['about-productt'] ?? '' ) ) );

	update_post_meta( $post_id, 'product-catalog-file', absint( $data['product-catalog-file'] ?? 0 ) );
	update_post_meta( $post_id, 'product_video', esc_url_raw( (string) ( $data['product_video'] ?? '' ) ) );
	erfanpro_update_csv_ids( $post_id, 'galerry_produ', explode( ',', (string) ( $data['galerry_produ'] ?? '' ) ) );
	erfanpro_update_csv_ids( $post_id, 'gallery-filed', explode( ',', (string) ( $data['gallery-filed'] ?? '' ) ) );
	for ( $n = 2; $n <= 6; $n++ ) {
		update_post_meta( $post_id, 'image-' . $n, absint( $data[ 'image-' . $n ] ?? 0 ) );
	}

	$qa = array(
		'name_question'   => 'text',
		'answer_question' => 'html',
	);
	erfanpro_update_repeater( $post_id, 'faq', erfanpro_sanitize_repeater_input( $data['faq'] ?? array(), $qa ) );
	erfanpro_update_repeater( $post_id, 'mfaq', erfanpro_sanitize_repeater_input( $data['mfaq'] ?? array(), $qa ) );
	erfanpro_update_repeater( $post_id, 'usage_product', erfanpro_sanitize_repeater_input( $data['usage_product'] ?? array(), array( 'add-new-usage' => 'text' ) ) );
	erfanpro_update_repeater( $post_id, 'product-feature', erfanpro_sanitize_repeater_input( $data['product-feature'] ?? array(), array( 'add-new-feature' => 'text' ) ) );
	erfanpro_update_repeater(
		$post_id,
		'product-feature2_copy',
		erfanpro_sanitize_repeater_input(
			$data['product-feature2_copy'] ?? array(),
			array(
				'add-new-feature'   => 'text',
				'vaule_of_variable' => 'text',
			)
		)
	);

	erfanpro_update_id_array( $post_id, 'project-ids', $data['project-ids'] ?? array() );
	erfanpro_update_id_array( $post_id, 'usage_countr', explode( ',', (string) ( $data['usage_countr'] ?? '' ) ) );
}
add_action( 'save_post_product', 'erfanpro_save_product_meta', 10, 2 );

/* -------------------------------------------------------------------------
 * Project metaboxes
 * ---------------------------------------------------------------------- */

/**
 * Register project metaboxes (only when the CPT exists — JetEngine or the
 * theme's own fallback from inc/cpt-projects-fallback.php).
 */
function erfanpro_project_metaboxes() {
	if ( ! post_type_exists( 'projects' ) ) {
		return;
	}
	add_meta_box(
		'erfanpro-project-main',
		__( 'اطلاعات پروژه (بلوپرینت)', 'erfanpro' ),
		'erfanpro_project_main_box',
		'projects',
		'normal',
		'default'
	);
	add_meta_box(
		'erfanpro-project-gallery',
		__( 'گالری پروژه', 'erfanpro' ),
		'erfanpro_project_gallery_box',
		'projects',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'erfanpro_project_metaboxes' );

/**
 * Project main box.
 */
function erfanpro_project_main_box( $post ) {
	erfanpro_meta_nonce_field();
	$id = $post->ID;
	erfanpro_field_editor( 'info-project', __( 'اطلاعات پروژه', 'erfanpro' ), get_post_meta( $id, 'info-project', true ) );
	echo '<div class="erfanpro-metabox-grid">';
	erfanpro_field_text(
		'finish-pro-time',
		__( 'زمان اتمام پروژه', 'erfanpro' ),
		get_post_meta( $id, 'finish-pro-time', true ),
		'',
		'1404/01/01'
	);
	echo '</div>';

	$products = array();
	if ( post_type_exists( 'product' ) ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		foreach ( $q->posts as $pid ) {
			$products[ $pid ] = get_the_title( $pid );
		}
		wp_reset_postdata();
	}
	erfanpro_field_checklist(
		'mortabet_mahsole_2',
		__( 'محصولات به‌کاررفته (mortabet_mahsole_2)', 'erfanpro' ),
		$products,
		erfanpro_get_id_list( $id, 'mortabet_mahsole_2' )
	);
	erfanpro_field_checklist(
		'product-in-projects',
		__( 'محصولات مرتبط (product-in-projects)', 'erfanpro' ),
		$products,
		erfanpro_get_id_list( $id, 'product-in-projects' )
	);
}

/**
 * Project gallery box.
 */
function erfanpro_project_gallery_box( $post ) {
	erfanpro_meta_nonce_field();
	$id = $post->ID;
	erfanpro_field_media_multi(
		'gallery-projects',
		__( 'گالری پروژه (gallery-projects)', 'erfanpro' ),
		erfanpro_get_id_list( $id, 'gallery-projects' )
	);
	echo '<div class="erfanpro-metabox-grid">';
	foreach ( array(
		'second-image' => __( 'تصویر دوم', 'erfanpro' ),
		'three-image'  => __( 'تصویر سوم', 'erfanpro' ),
		'foure-image'  => __( 'تصویر چهارم', 'erfanpro' ),
		'five-image'   => __( 'تصویر پنجم', 'erfanpro' ),
		'six-image'    => __( 'تصویر ششم', 'erfanpro' ),
	) as $key => $label ) {
		erfanpro_field_media_single( $key, $label, get_post_meta( $id, $key, true ) );
	}
	echo '</div>';
}

/**
 * Save project meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function erfanpro_save_project_meta( $post_id, $post ) {
	$data = erfanpro_meta_save_guard( $post_id );
	if ( null === $data ) {
		return;
	}

	update_post_meta( $post_id, 'info-project', wp_kses_post( (string) ( $data['info-project'] ?? '' ) ) );
	update_post_meta( $post_id, 'finish-pro-time', sanitize_text_field( (string) ( $data['finish-pro-time'] ?? '' ) ) );

	erfanpro_update_csv_ids( $post_id, 'gallery-projects', explode( ',', (string) ( $data['gallery-projects'] ?? '' ) ) );
	foreach ( array( 'second-image', 'three-image', 'foure-image', 'five-image', 'six-image' ) as $key ) {
		update_post_meta( $post_id, $key, absint( $data[ $key ] ?? 0 ) );
	}

	erfanpro_update_id_array( $post_id, 'mortabet_mahsole_2', $data['mortabet_mahsole_2'] ?? array() );
	erfanpro_update_id_array( $post_id, 'product-in-projects', $data['product-in-projects'] ?? array() );
}
add_action( 'save_post_projects', 'erfanpro_save_project_meta', 10, 2 );
