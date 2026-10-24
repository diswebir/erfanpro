<?php
/**
 * Phase 4: shop archive helpers + AJAX filter/search/sort.
 *
 * - erfanpro_shop_query_args(): builds the WP_Query args from the
 *   sanitised filter state (category, pa_* attributes, sort, purchasable,
 *   live search). Decision 14: sort options include «قابل خرید»
 *   (priced products first). Decision 20: AJAX live search.
 * - erfanpro_shop_card_html(): one product card — the reference
 *   card-light layout plus the decision-3 price row
 *   (priced = «از X تومان» + «قابل خرید» badge; unpriced = «استعلام»).
 * - AJAX action erfanpro_shop_filter (logged-in + guest) with nonce.
 * - erfanpro_shop_pagination_html(): numbered pagination for the grid.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ERFANPRO_SHOP_PER_PAGE', 12 );

/**
 * Sort options for the shop toolbar (decision 14).
 *
 * @return array<string,string> value => Persian label.
 */
function erfanpro_shop_sort_options() {
	return array(
		'default'     => __( 'پیش‌فرض', 'erfanpro' ),
		'purchasable' => __( 'قابل خرید', 'erfanpro' ),
		'new'         => __( 'جدیدترین', 'erfanpro' ),
		'price-asc'   => __( 'ارزان‌ترین', 'erfanpro' ),
		'price-desc'  => __( 'گران‌ترین', 'erfanpro' ),
	);
}

/**
 * Attributes usable as shop filters: every pa_* taxonomy that has terms.
 * Uses WooCommerce helpers when available, plain taxonomy API otherwise.
 *
 * @return array<int,array{taxonomy:string,label:string,terms:array<int,array{slug:string,name:string}}}>
 */
function erfanpro_shop_attributes() {
	$taxonomies = array();
	if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
		foreach ( wc_get_attribute_taxonomies() as $attr ) {
			$taxonomies[] = 'pa_' . $attr->attribute_name;
		}
	} else {
		foreach ( get_taxonomies( array( 'public' => true ), 'objects' ) as $tax ) {
			if ( 0 === strpos( $tax->name, 'pa_' ) ) {
				$taxonomies[] = $tax->name;
			}
		}
	}

	$out = array();
	foreach ( $taxonomies as $taxonomy ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			continue;
		}
		$label = function_exists( 'wc_attribute_label' ) ? wc_attribute_label( $taxonomy ) : get_taxonomy( $taxonomy )->label;
		$rows  = array();
		foreach ( $terms as $term ) {
			$rows[] = array(
				'slug' => $term->slug,
				'name' => $term->name,
			);
		}
		$out[] = array(
			'taxonomy' => $taxonomy,
			'label'    => $label,
			'terms'    => $rows,
		);
	}
	return $out;
}

/**
 * Sanitise the raw filter state (from $_POST or the initial page load).
 *
 * @param array $raw Raw input.
 * @return array{cat:string,attrs:array<string,array<string>>,sort:string,purchasable:bool,s:string,paged:int}
 */
function erfanpro_shop_sanitize_params( $raw ) {
	$cat = isset( $raw['cat'] ) ? sanitize_text_field( (string) $raw['cat'] ) : '';
	if ( '' !== $cat && ! term_exists( $cat, 'product_cat' ) ) {
		$cat = '';
	}

	$attrs   = array();
	$allowed = array();
	foreach ( erfanpro_shop_attributes() as $group ) {
		$allowed[ $group['taxonomy'] ] = true;
	}
	if ( isset( $raw['attrs'] ) && is_array( $raw['attrs'] ) ) {
		foreach ( $raw['attrs'] as $taxonomy => $slugs ) {
			$taxonomy = sanitize_key( (string) $taxonomy );
			if ( ! isset( $allowed[ $taxonomy ] ) ) {
				continue;
			}
			$slugs = is_array( $slugs ) ? $slugs : array( $slugs );
			$clean = array();
			foreach ( $slugs as $slug ) {
				$slug = sanitize_text_field( (string) $slug );
				if ( '' !== $slug && term_exists( $slug, $taxonomy ) ) {
					$clean[] = $slug;
				}
			}
			if ( ! empty( $clean ) ) {
				$attrs[ $taxonomy ] = array_values( array_unique( $clean ) );
			}
		}
	}

	$sort    = isset( $raw['sort'] ) ? sanitize_key( (string) $raw['sort'] ) : 'default';
	$options = erfanpro_shop_sort_options();
	if ( ! isset( $options[ $sort ] ) ) {
		$sort = 'default';
	}

	$purchasable = ! empty( $raw['purchasable'] );

	$s = isset( $raw['s'] ) ? sanitize_text_field( (string) $raw['s'] ) : '';
	if ( function_exists( 'mb_substr' ) ) {
		$s = mb_substr( $s, 0, 100 );
	} else {
		$s = substr( $s, 0, 100 );
	}

	$paged = isset( $raw['paged'] ) ? absint( $raw['paged'] ) : 1;
	if ( $paged < 1 ) {
		$paged = 1;
	}

	return array(
		'cat'         => $cat,
		'attrs'       => $attrs,
		'sort'        => $sort,
		'purchasable' => $purchasable,
		's'           => $s,
		'paged'       => $paged,
	);
}

/**
 * Build WP_Query args from the sanitised filter state.
 *
 * @param array $params Sanitised params (erfanpro_shop_sanitize_params).
 * @return array WP_Query args.
 */
function erfanpro_shop_query_args( $params ) {
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => ERFANPRO_SHOP_PER_PAGE,
		'paged'          => $params['paged'],
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);

	$tax_query = array();
	if ( '' !== $params['cat'] ) {
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => array( $params['cat'] ),
		);
	}
	foreach ( $params['attrs'] as $taxonomy => $slugs ) {
		$tax_query[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $slugs,
			'operator' => 'IN',
		);
	}
	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	if ( ! empty( $tax_query ) ) {
		$args['tax_query'] = $tax_query;
	}

	$meta_query = array();
	if ( $params['purchasable'] ) {
		/* Decision 3: priced = purchasable (WooCommerce _price set). */
		$meta_query[] = array(
			'key'     => '_price',
			'value'   => '',
			'compare' => '!=',
		);
	}

	switch ( $params['sort'] ) {
		case 'new':
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			break;
		case 'price-asc':
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'ASC';
			break;
		case 'price-desc':
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
			break;
		case 'purchasable':
			/* Priced products first, then newest. */
			$meta_query['price_exists'] = array(
				'key'     => '_price',
				'compare' => 'EXISTS',
			);
			$args['orderby'] = array(
				'price_exists' => 'DESC',
				'date'         => 'DESC',
			);
			unset( $args['order'] );
			break;
		default:
			$args['orderby'] = array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			);
			unset( $args['order'] );
			break;
	}

	if ( count( $meta_query ) > 1 ) {
		$meta_query['relation'] = 'AND';
	}
	if ( ! empty( $meta_query ) ) {
		$args['meta_query'] = $meta_query;
	}

	if ( '' !== $params['s'] ) {
		$args['s'] = $params['s'];
	}

	return $args;
}

/**
 * Star-rating display for the shop card (decision 15: ratings are
 * shown; submitting a rating lives on the single-product page).
 * Uses the WooCommerce reviews API; renders nothing when the product
 * has no ratings yet.
 *
 * @param float $average Average rating (0-5).
 * @param int   $count   Number of ratings.
 * @return string Stars HTML.
 */
function erfanpro_shop_stars_html( $average, $count ) {
	if ( $count < 1 || $average <= 0 ) {
		return '';
	}
	$pct  = max( 0, min( 100, $average / 5 * 100 ) );
	$star = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star h-3.5 w-3.5 shrink-0" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
	$row  = str_repeat( $star, 5 );

	return sprintf(
		'<span class="inline-flex items-center gap-1.5" title="%1$s"><span class="relative inline-flex shrink-0 text-slate-300" dir="ltr"><span class="flex gap-0.5">%2$s</span><span class="absolute inset-y-0 left-0 overflow-hidden text-amber-400" style="width: %3$s%%"><span class="flex w-max gap-0.5">%2$s</span></span></span><span class="text-[12px] font-bold text-slate-500">%4$s (%5$s)</span></span>',
		esc_attr( sprintf( __( 'امتیاز %s از ۵', 'erfanpro' ), erfanpro_fa_digits( number_format( $average, 1 ) ) ) ),
		$row,
		esc_attr( rtrim( rtrim( number_format( $pct, 1, '.', '' ), '0' ), '.' ) ),
		esc_html( erfanpro_fa_digits( number_format( $average, 1 ) ) ),
		esc_html( erfanpro_fa_digits( $count ) )
	);
}

/**
 * One product card — reference card-light layout + decision-3 price row.
 *
 * @param WC_Product|int $product Product object or ID.
 * @param float          $delay   Reveal stagger delay in seconds.
 * @return string Card HTML.
 */
function erfanpro_shop_card_html( $product, $delay = 0.0 ) {
	if ( is_int( $product ) ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product ) : null;
	}
	if ( ! $product instanceof WC_Product ) {
		return '';
	}

	$title = $product->get_name();

	$image = wp_get_attachment_image_url( $product->get_image_id(), 'large' );
	if ( ! $image && function_exists( 'wc_placeholder_img_src' ) ) {
		$image = wc_placeholder_img_src( 'large' );
	}

	$cat_name = '';
	$cats     = get_the_terms( $product->get_id(), 'product_cat' );
	if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
		$cat_name = $cats[0]->name;
	}

	$excerpt = $product->get_short_description();
	if ( '' === trim( wp_strip_all_tags( (string) $excerpt ) ) ) {
		$excerpt = wp_trim_words( $product->get_description(), 22, '…' );
	} else {
		$excerpt = wp_trim_words( wp_strip_all_tags( (string) $excerpt ), 22, '…' );
	}

	$chips = array();
	$tags  = get_the_terms( $product->get_id(), 'product_tag' );
	if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
		foreach ( array_slice( $tags, 0, 4 ) as $tag ) {
			$chips[] = $tag->name;
		}
	}

	/* Decision 3: priced = purchasable, unpriced = quote. */
	$purchasable = $product->is_purchasable();
	$price_html  = '';
	if ( $purchasable ) {
		$raw = $product->is_type( 'variable' ) ? $product->get_variation_price( 'min' ) : $product->get_price();
		if ( '' !== (string) $raw && null !== $raw ) {
			$price_html = sprintf(
				'<span class="text-[15px] font-extrabold text-emerald-700">%s %s</span>',
				esc_html__( 'از', 'erfanpro' ),
				esc_html( erfanpro_fa_digits( number_format( (float) $raw ) ) . ' ' . __( 'تومان', 'erfanpro' ) )
			);
		}
	}
	if ( '' === $price_html ) {
		$purchasable = false;
		$price_html  = sprintf(
			'<span class="inline-flex items-center gap-1.5 text-[13.5px] font-extrabold text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call h-4 w-4" aria-hidden="true"><path d="M13 2a9 9 0 0 1 9 9"></path><path d="M13 6a5 5 0 0 1 5 5"></path><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>%s</span>',
			esc_html__( 'استعلام قیمت', 'erfanpro' )
		);
	}

	/* Decision 15: show the average star rating when ratings exist. */
	$rating_html = erfanpro_shop_stars_html(
		(float) $product->get_average_rating(),
		(int) $product->get_rating_count()
	);

	$delay_s = number_format( (float) $delay, 2, '.', '' );

	ob_start();
	?>
	<div data-anim="" data-anim-init="opacity: 0; transform: translateY(42px); filter: blur(6px);" data-anim-final="opacity: 1; filter: blur(0px); transform: none;" data-anim-kind="inview" data-anim-delay="<?php echo esc_attr( $delay_s ); ?>" style="opacity: 0; transform: translateY(42px); filter: blur(6px); --anim-delay: <?php echo esc_attr( $delay_s ); ?>s;">
		<a class="card-light group flex h-full flex-col overflow-hidden rounded-[1.5rem]" href="<?php echo esc_url( $product->get_permalink() ); ?>">
			<div class="relative h-56 overflow-hidden">
				<?php if ( $image ) : ?>
				<img alt="<?php echo esc_attr( $title ); ?>" loading="lazy" class="h-full w-full object-cover transition-transform duration-[1.2s] ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-110" src="<?php echo esc_url( $image ); ?>">
				<?php endif; ?>
				<?php if ( '' !== $cat_name ) : ?>
				<span class="absolute right-4 top-4 rounded-full bg-emerald-950/75 px-3.5 py-1.5 text-[11px] font-bold text-emerald-100 backdrop-blur-md"><?php echo esc_html( $cat_name ); ?></span>
				<?php endif; ?>
				<?php if ( $purchasable ) : ?>
				<span class="absolute left-4 top-4 z-10 inline-flex items-center gap-1.5 rounded-full bg-emerald-600 px-3.5 py-1.5 text-[11px] font-bold text-white shadow-lg shadow-emerald-600/25 backdrop-blur-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart h-3.5 w-3.5" aria-hidden="true"><path d="m2.05 2.05 1.099-.028a1 1 0 0 1 1.008.815l2.69 14.347A1 1 0 0 0 7.83 18H18"></path><path d="M4.563 5h16.435a1 1 0 0 1 .981 1.204l-1.026 6.226A2 2 0 0 1 18.962 14H6.25"></path><circle cx="18" cy="20" r="2"></circle><circle cx="8" cy="20" r="2"></circle></svg><?php esc_html_e( 'قابل خرید', 'erfanpro' ); ?></span>
				<?php endif; ?>
			</div>
			<div class="flex flex-1 flex-col gap-3 p-6">
				<div class="flex items-start justify-between gap-3">
					<h3 class="text-lg font-extrabold text-slate-900 transition-colors group-hover:text-emerald-700"><?php echo esc_html( $title ); ?></h3>
					<span class="grid h-9 w-9 shrink-0 place-items-center rounded-full border border-emerald-200 bg-emerald-50 text-emerald-700 transition-all duration-400 group-hover:rotate-45 group-hover:bg-emerald-600 group-hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4 w-4" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></span>
				</div>
				<?php if ( '' !== $excerpt ) : ?>
				<p class="text-[13px] leading-7 text-slate-600"><?php echo esc_html( $excerpt ); ?></p>
				<?php endif; ?>
				<div class="flex items-center justify-between gap-2">
					<?php echo $price_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo $rating_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php if ( ! empty( $chips ) ) : ?>
				<div class="mt-auto flex flex-wrap gap-2 border-t border-slate-100 pt-4">
					<?php foreach ( $chips as $chip ) : ?>
					<span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-800"><?php echo esc_html( $chip ); ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		</a>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Grid HTML for a filter state (cards or the empty state).
 *
 * @param array $params Sanitised params.
 * @return array{html:string,total:int,max_pages:int}
 */
function erfanpro_shop_render( $params ) {
	$query = new WP_Query( erfanpro_shop_query_args( $params ) );

	$html = '';
	$i    = 0;
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
			if ( $product instanceof WC_Product ) {
				$html .= erfanpro_shop_card_html( $product, 0.07 * ( $i % 6 ) );
				++$i;
			}
		}
		wp_reset_postdata();
	}

	if ( '' === $html ) {
		$html = sprintf(
			'<div class="col-span-full flex flex-col items-center gap-4 rounded-[1.5rem] border border-dashed border-slate-300 bg-white/70 px-6 py-16 text-center"><span class="grid h-14 w-14 place-items-center rounded-full bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-search h-6 w-6" aria-hidden="true"><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"></path><path d="m7.5 4.27 9 5.15"></path><path d="M3.29 7 12 12l8.71-5"></path><path d="M12 22V12"></path><circle cx="18.5" cy="15.5" r="2.5"></circle><path d="m20.27 17.27 1.7 1.7"></path></svg></span><p class="text-[15px] font-extrabold text-slate-800">%s</p><p class="max-w-sm text-[13px] leading-7 text-slate-500">%s</p><button type="button" data-shop-reset class="rounded-full bg-emerald-600 px-6 py-2.5 text-[13px] font-bold text-white shadow-md shadow-emerald-600/25 transition-all hover:bg-emerald-700">%s</button></div>',
			esc_html__( 'محصولی با این مشخصات پیدا نشد', 'erfanpro' ),
			esc_html__( 'فیلترها یا عبارت جستجو را تغییر دهید تا نتایج بیشتری ببینید.', 'erfanpro' ),
			esc_html__( 'حذف همه فیلترها', 'erfanpro' )
		);
	}

	return array(
		'html'      => $html,
		'total'     => (int) $query->found_posts,
		'max_pages' => (int) $query->max_num_pages,
	);
}

/**
 * Numbered pagination for the shop grid (AJAX-driven, href fallback).
 *
 * @param int   $total_pages Total pages.
 * @param int   $current     Current page.
 * @param array $params      Sanitised params (for future query strings).
 * @return string Pagination HTML (empty when a single page).
 */
function erfanpro_shop_pagination_html( $total_pages, $current, $params = array() ) {
	if ( $total_pages < 2 ) {
		return '';
	}

	$base_btn = 'grid h-10 min-w-10 place-items-center rounded-full px-3 text-[13px] font-bold transition-all duration-300 border ';
	$inactive = $base_btn . 'border-slate-200 bg-white text-slate-600 hover:border-emerald-400 hover:text-emerald-700';
	$active   = $base_btn . 'bg-emerald-600 text-white border-emerald-600 shadow-md shadow-emerald-600/25';

	ob_start();
	?>
	<nav class="mt-10 flex items-center justify-center gap-2" aria-label="<?php esc_attr_e( 'صفحه‌بندی محصولات', 'erfanpro' ); ?>">
		<?php if ( $current > 1 ) : ?>
		<a href="<?php echo esc_url( get_pagenum_link( $current - 1 ) ); ?>" data-shop-page="<?php echo esc_attr( $current - 1 ); ?>" class="<?php echo esc_attr( $inactive ); ?>" aria-label="<?php esc_attr_e( 'صفحه قبلی', 'erfanpro' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-4 w-4" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg></a>
		<?php endif; ?>
		<?php for ( $i = 1; $i <= $total_pages; $i++ ) : ?>
		<a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" data-shop-page="<?php echo esc_attr( $i ); ?>" class="<?php echo esc_attr( 1 === $i && 1 === $current || $i === $current ? $active : $inactive ); ?>"<?php echo $i === $current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( erfanpro_fa_digits( $i ) ); ?></a>
		<?php endfor; ?>
		<?php if ( $current < $total_pages ) : ?>
		<a href="<?php echo esc_url( get_pagenum_link( $current + 1 ) ); ?>" data-shop-page="<?php echo esc_attr( $current + 1 ); ?>" class="<?php echo esc_attr( $inactive ); ?>" aria-label="<?php esc_attr_e( 'صفحه بعدی', 'erfanpro' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-4 w-4" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg></a>
		<?php endif; ?>
	</nav>
	<?php
	return (string) ob_get_clean();
}

/**
 * Result-count text: «نمایش X از Y محصول».
 *
 * @param int $shown Shown on this page.
 * @param int $total Total found.
 * @return string
 */
function erfanpro_shop_count_text( $shown, $total ) {
	return sprintf(
		/* translators: 1: shown count, 2: total count */
		__( 'نمایش %1$s از %2$s محصول', 'erfanpro' ),
		erfanpro_fa_digits( $shown ),
		erfanpro_fa_digits( $total )
	);
}

/**
 * AJAX handler: shop filter/search/sort/pagination.
 */
function erfanpro_shop_ajax_filter() {
	check_ajax_referer( 'erfanpro-shop-filter', 'nonce' );

	$params = erfanpro_shop_sanitize_params( wp_unslash( $_POST ) );
	$result = erfanpro_shop_render( $params );

	$shown = 0;
	if ( $result['total'] > 0 ) {
		$shown = min( $result['total'], ERFANPRO_SHOP_PER_PAGE * $params['paged'] ) - ERFANPRO_SHOP_PER_PAGE * ( $params['paged'] - 1 );
		$shown = max( 0, $shown );
	}

	wp_send_json_success(
		array(
			'html'       => $result['html'],
			'pagination' => erfanpro_shop_pagination_html( $result['max_pages'], $params['paged'], $params ),
			'count_text' => erfanpro_shop_count_text( $shown, $result['total'] ),
			'total'      => $result['total'],
			'paged'      => $params['paged'],
			'max_pages'  => $result['max_pages'],
		)
	);
}
add_action( 'wp_ajax_erfanpro_shop_filter', 'erfanpro_shop_ajax_filter' );
add_action( 'wp_ajax_nopriv_erfanpro_shop_filter', 'erfanpro_shop_ajax_filter' );
