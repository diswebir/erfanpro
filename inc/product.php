<?php
/**
 * Phase 5 — single product page helpers.
 *
 * Data readers for woocommerce/single-product.php + template-parts/product/*:
 * gallery slides (Woo gallery + blueprint galerry_produ/gallery-filed/
 * image-2..image-6 + product_video), spec chips from Woo attributes,
 * catalog file with site-wide fallback, automatic related products
 * (upsells, then same category — decision 17), related projects
 * (project-ids, then latest), and the user rating form (decision 15).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WhatsApp chat URL from the `whatsapp` option.
 *
 * @return string
 */
function erfanpro_whatsapp_url() {
	$digits = preg_replace( '/\D+/', '', (string) erfanpro_option( 'whatsapp', '' ) );
	if ( '' === $digits ) {
		return 'https://wa.me/989137976915'; // reference fallback.
	}
	if ( str_starts_with( $digits, '0' ) ) {
		$digits = '98' . substr( $digits, 1 );
	} elseif ( ! str_starts_with( $digits, '98' ) && 10 === strlen( $digits ) ) {
		$digits = '98' . $digits;
	}
	return 'https://wa.me/' . $digits;
}

/**
 * Gallery slides for a product, in display order:
 * featured image, _product_image_gallery, blueprint galerry_produ,
 * gallery-filed, image-2..image-6, then the product_video slide.
 *
 * @param int $product_id Product ID.
 * @return array<int, array{type:string,url:string,id?:int}>
 */
function erfanpro_product_slides( $product_id ) {
	$ids = array();

	$thumb = get_post_thumbnail_id( $product_id );
	if ( $thumb ) {
		$ids[] = (int) $thumb;
	}
	$ids = array_merge( $ids, erfanpro_get_id_list( $product_id, '_product_image_gallery' ) );
	$ids = array_merge( $ids, erfanpro_get_id_list( $product_id, 'galerry_produ' ) );
	$ids = array_merge( $ids, erfanpro_get_id_list( $product_id, 'gallery-filed' ) );
	for ( $n = 2; $n <= 6; $n++ ) {
		$one = absint( get_post_meta( $product_id, 'image-' . $n, true ) );
		if ( $one ) {
			$ids[] = $one;
		}
	}
	$ids = array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );

	$slides = array();
	foreach ( $ids as $id ) {
		$url = wp_get_attachment_image_url( $id, 'large' );
		if ( $url ) {
			$slides[] = array(
				'type' => 'image',
				'id'   => $id,
				'url'  => $url,
			);
		}
	}

	$video = get_post_meta( $product_id, 'product_video', true );
	if ( is_string( $video ) && '' !== trim( $video ) ) {
		$slides[] = array(
			'type' => 'video',
			'url'  => esc_url_raw( $video ),
		);
	}

	return $slides;
}

/**
 * The four spec chips under the title (reference layout), filled from
 * the matching WooCommerce attributes; «کنترل» has no attribute in the
 * blueprint and keeps the reference «—».
 *
 * @param int $product_id Product ID.
 * @return array<int, array{label:string,value:string}>
 */
function erfanpro_product_spec_chips( $product_id ) {
	$map = array(
		array(
			'label'    => __( 'درجه حفاظت', 'erfanpro' ),
			'tax'      => 'pa_ip-rating',
			'fallback' => 'IP67',
		),
		array(
			'label'    => __( 'کنترل', 'erfanpro' ),
			'tax'      => '',
			'fallback' => '—',
		),
		array(
			'label'    => __( 'گارانتی', 'erfanpro' ),
			'tax'      => 'pa_warranty',
			'fallback' => __( '۲۴ ماه تعویض', 'erfanpro' ),
		),
		array(
			'label'    => __( 'خدمات پس از فروش', 'erfanpro' ),
			'tax'      => 'pa_after-sales-support',
			'fallback' => __( '۵ سال', 'erfanpro' ),
		),
	);

	$chips = array();
	foreach ( $map as $row ) {
		$value = $row['fallback'];
		if ( '' !== $row['tax'] && taxonomy_exists( $row['tax'] ) ) {
			$terms = wc_get_product_terms( $product_id, $row['tax'], array( 'fields' => 'names' ) );
			if ( is_array( $terms ) && ! empty( $terms ) ) {
				$value = implode( '، ', $terms );
			}
		}
		$chips[] = array(
			'label' => $row['label'],
			'value' => $value,
		);
	}
	return $chips;
}

/**
 * Product catalog file (decision 31).
 *
 * @param int $product_id Product ID.
 * @return array{url:string,name:string,ext:string}|null
 */
function erfanpro_product_catalog( $product_id ) {
	$id = absint( get_post_meta( $product_id, 'product-catalog-file', true ) );
	if ( ! $id ) {
		return null;
	}
	$url = wp_get_attachment_url( $id );
	if ( ! $url ) {
		return null;
	}
	$path = get_attached_file( $id );
	$ext  = $path ? strtoupper( pathinfo( $path, PATHINFO_EXTENSION ) ) : '';
	return array(
		'url'  => $url,
		'name' => get_the_title( $id ) ? get_the_title( $id ) : __( 'کاتالوگ محصول', 'erfanpro' ),
		'ext'  => '' !== $ext ? $ext : 'PDF',
	);
}

/**
 * Related products: manual _upsell_ids first, then same product_cat
 * (decision 17, automatic).
 *
 * @param int $product_id Product ID.
 * @param int $limit      Max products.
 * @return WC_Product[]
 */
function erfanpro_related_products( $product_id, $limit = 3 ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return array();
	}
	$found = array();
	$seen  = array( $product_id );

	foreach ( erfanpro_get_id_list( $product_id, '_upsell_ids' ) as $id ) {
		if ( in_array( $id, $seen, true ) ) {
			continue;
		}
		$p = wc_get_product( $id );
		if ( $p && 'publish' === $p->get_status() ) {
			$found[] = $p;
			$seen[]  = $id;
		}
		if ( count( $found ) >= $limit ) {
			break;
		}
	}

	if ( count( $found ) < $limit ) {
		$cats = function_exists( 'wc_get_product_term_ids' )
			? wc_get_product_term_ids( $product_id, 'product_cat' )
			: array();
		$q = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => ( $limit - count( $found ) ) * 3,
				'post__not_in'   => $seen,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'tax_query'      => ! empty( $cats ) ? array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => array_slice( array_map( 'absint', (array) $cats ), 0, 3 ),
					),
				) : array(),
			)
		);
		foreach ( $q->posts as $id ) {
			$p = wc_get_product( $id );
			if ( $p ) {
				$found[] = $p;
			}
			if ( count( $found ) >= $limit ) {
				break;
			}
		}
		wp_reset_postdata();
	}

	return array_slice( $found, 0, $limit );
}

/**
 * Related projects: manual project-ids first, then latest projects.
 *
 * @param int $product_id Product ID.
 * @param int $limit      Max projects.
 * @return int[] Post IDs.
 */
function erfanpro_related_projects( $product_id, $limit = 3 ) {
	if ( ! post_type_exists( 'projects' ) ) {
		return array();
	}
	$out = array();
	foreach ( erfanpro_get_id_list( $product_id, 'project-ids' ) as $id ) {
		if ( 'projects' === get_post_type( $id ) && 'publish' === get_post_status( $id ) ) {
			$out[] = $id;
		}
		if ( count( $out ) >= $limit ) {
			break;
		}
	}

	if ( count( $out ) < $limit ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'projects',
				'posts_per_page' => $limit * 2,
				'post__not_in'   => $out,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		foreach ( $q->posts as $id ) {
			if ( ! in_array( $id, $out, true ) ) {
				$out[] = $id;
			}
			if ( count( $out ) >= $limit ) {
				break;
			}
		}
		wp_reset_postdata();
	}

	return array_slice( $out, 0, $limit );
}

/**
 * Rating display + submission form (decision 15, second half).
 *
 * @param WC_Product $product Product.
 * @return string
 */
function erfanpro_product_rating_html( $product ) {
	$product_id = $product->get_id();
	$avg        = (float) $product->get_average_rating();
	$count      = (int) $product->get_rating_count();
	$rated      = isset( $_COOKIE[ 'erfanpro_rated_' . $product_id ] );

	$star_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star h-6 w-6" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';

	ob_start();
	?>
	<div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs" id="erfanpro-rating" data-product-id="<?php echo esc_attr( (string) $product_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'erfanpro-product-rating' ) ); ?>">
		<div class="flex items-center justify-between gap-3">
			<h4 class="text-[14px] font-extrabold text-slate-900"><?php esc_html_e( 'امتیاز کاربران', 'erfanpro' ); ?></h4>
			<?php echo erfanpro_shop_stars_html( $avg, $count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php if ( $rated ) : ?>
			<p class="mt-3 text-[12.5px] font-bold text-emerald-700"><?php esc_html_e( 'شما قبلاً به این محصول امتیاز داده‌اید.', 'erfanpro' ); ?></p>
		<?php else : ?>
			<div class="mt-3 border-t border-slate-100 pt-4">
				<p class="mb-2 text-[12.5px] font-bold text-slate-600"><?php esc_html_e( 'امتیاز شما:', 'erfanpro' ); ?></p>
				<div class="flex items-center gap-1" data-rating-input dir="ltr" role="radiogroup" aria-label="<?php esc_attr_e( 'ثبت امتیاز', 'erfanpro' ); ?>">
					<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
					<button type="button" data-rate="<?php echo esc_attr( (string) $i ); ?>" class="text-slate-300 transition-colors hover:text-amber-400" aria-label="<?php echo esc_attr( sprintf( __( '%d ستاره', 'erfanpro' ), $i ) ); ?>"><?php echo $star_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					<?php endfor; ?>
				</div>
				<button type="button" class="mt-3 inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-5 py-2.5 text-[13px] font-black text-white transition-colors hover:bg-emerald-800" data-rating-submit disabled>
					<?php esc_html_e( 'ثبت امتیاز', 'erfanpro' ); ?>
				</button>
				<p class="mt-2 text-[12px] font-medium text-slate-500" data-rating-msg aria-live="polite"></p>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * AJAX: submit a product rating (decision 15). One vote per visitor
 * (cookie + IP/UA transient, 1 year).
 */
function erfanpro_ajax_submit_rating() {
	check_ajax_referer( 'erfanpro-product-rating', 'nonce' );

	$product_id = absint( $_POST['product_id'] ?? 0 );
	$rating     = min( 5, max( 1, absint( $_POST['rating'] ?? 0 ) ) );

	if ( ! function_exists( 'wc_get_product' ) ) {
		wp_send_json_error( array( 'message' => __( 'فروشگاه در دسترس نیست.', 'erfanpro' ) ), 500 );
	}
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		wp_send_json_error( array( 'message' => __( 'محصول یافت نشد.', 'erfanpro' ) ), 404 );
	}

	$cookie = 'erfanpro_rated_' . $product_id;
	if ( isset( $_COOKIE[ $cookie ] ) ) {
		wp_send_json_error( array( 'message' => __( 'شما قبلاً به این محصول امتیاز داده‌اید.', 'erfanpro' ) ), 400 );
	}
	$ip_hash = md5( (string) wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) . '|' . (string) wp_unslash( $_SERVER['HTTP_USER_AGENT'] ?? '' ) . '|' . $product_id );
	if ( get_transient( 'erfanpro_rated_' . $ip_hash ) ) {
		wp_send_json_error( array( 'message' => __( 'شما قبلاً به این محصول امتیاز داده‌اید.', 'erfanpro' ) ), 400 );
	}

	$user = wp_get_current_user();
	$comment_id = wp_insert_comment(
		array(
			'comment_post_ID'      => $product_id,
			'comment_author'       => $user->exists() ? $user->display_name : __( 'مهمان', 'erfanpro' ),
			'comment_author_email' => $user->exists() ? $user->user_email : '',
			'comment_content'      => sprintf( __( 'امتیاز ثبت‌شده: %s از ۵', 'erfanpro' ), erfanpro_fa_digits( $rating ) ),
			'comment_type'         => 'review',
			'comment_parent'       => 0,
			'user_id'              => $user->exists() ? $user->ID : 0,
			'comment_approved'     => get_option( 'comment_moderation' ) ? 0 : 1,
		)
	);
	if ( ! $comment_id ) {
		wp_send_json_error( array( 'message' => __( 'ثبت امتیاز ناموفق بود.', 'erfanpro' ) ), 500 );
	}

	add_comment_meta( $comment_id, 'rating', (int) $rating, true );

	/* Keep Woo's rating aggregates in sync (WC hooks this to
	 * `comment_post`, which wp_insert_comment does not fire). */
	if ( class_exists( 'WC_Comments' ) && method_exists( 'WC_Comments', 'add_avg_rating' ) ) {
		WC_Comments::add_avg_rating( $comment_id );
	}

	setcookie( $cookie, '1', time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
	set_transient( 'erfanpro_rated_' . $ip_hash, 1, YEAR_IN_SECONDS );

	$fresh = wc_get_product( $product_id );
	wp_send_json_success(
		array(
			'message' => get_option( 'comment_moderation' )
				? __( 'امتیاز شما ثبت شد و پس از تأیید نمایش داده می‌شود.', 'erfanpro' )
				: __( 'امتیاز شما با موفقیت ثبت شد.', 'erfanpro' ),
			'average' => $fresh ? (float) $fresh->get_average_rating() : 0,
			'count'   => $fresh ? (int) $fresh->get_rating_count() : 0,
		)
	);
}
add_action( 'wp_ajax_erfanpro_submit_rating', 'erfanpro_ajax_submit_rating' );
add_action( 'wp_ajax_nopriv_erfanpro_submit_rating', 'erfanpro_ajax_submit_rating' );
