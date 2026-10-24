<?php
/**
 * Phase 6: projects archive helpers + AJAX filter, single-project
 * readers, and related items.
 *
 * - erfanpro_projects_query_args(): WP_Query args from the sanitised
 *   filter state (city + project_type, plan §4.5). Pagination mirrors
 *   the shop (12 per page).
 * - erfanpro_project_card_html(): one project card — the reference
 *   dark card (image, project_type badge, arrow button, title,
 *   city + year bar).
 * - AJAX action erfanpro_projects_filter (logged-in + guest) with nonce.
 * - erfanpro_project_slides(): gallery in display order — featured
 *   image, blueprint `gallery-projects` (CSV IDs), then
 *   second-image..six-image (note the blueprint spelling `foure-image`).
 * - erfanpro_project_used_products(): blueprint `mortabet_mahsole_2`
 *   (JSON array of product IDs), then `product-in-projects`.
 * - erfanpro_related_projects_for_project(): same city first, then same
 *   project_type, then latest (decision 17, automatic).
 * - View counting is automatic: inc/view-counter.php already tracks
 *   the `projects` type on `number-viewc` (decision 16).
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ERFANPRO_PROJECTS_PER_PAGE', 12 );

/**
 * First term name of a project for a taxonomy.
 *
 * @param int    $post_id  Project ID.
 * @param string $taxonomy Taxonomy slug.
 * @return string Term name or ''.
 */
function erfanpro_project_term_name( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}
	return (string) $terms[0]->name;
}

/**
 * First term slug of a project for a taxonomy.
 *
 * @param int    $post_id  Project ID.
 * @param string $taxonomy Taxonomy slug.
 * @return string Term slug or ''.
 */
function erfanpro_project_term_slug( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}
	return (string) $terms[0]->slug;
}

/**
 * Project year: the Jalali year from blueprint `finish-pro-time`
 * («1404/01/01» -> 1404), falling back to the post year.
 *
 * @param int $post_id Project ID.
 * @return string Persian-digit year.
 */
function erfanpro_project_year( $post_id ) {
	$finish = (string) get_post_meta( $post_id, 'finish-pro-time', true );
	if ( preg_match( '/^\s*(\d{4})/', $finish, $m ) ) {
		return erfanpro_fa_digits( $m[1] );
	}
	return erfanpro_fa_digits( get_the_date( 'Y', $post_id ) );
}

/**
 * Sanitise the raw filter state (from $_POST or the initial page load).
 *
 * @param array $raw Raw input.
 * @return array{city:string,type:string,paged:int}
 */
function erfanpro_projects_sanitize_params( $raw ) {
	$city = isset( $raw['city'] ) ? sanitize_text_field( (string) $raw['city'] ) : '';
	if ( '' !== $city && ! term_exists( $city, 'city' ) ) {
		$city = '';
	}

	$type = isset( $raw['type'] ) ? sanitize_text_field( (string) $raw['type'] ) : '';
	if ( '' !== $type && ! term_exists( $type, 'project_type' ) ) {
		$type = '';
	}

	$paged = isset( $raw['paged'] ) ? absint( $raw['paged'] ) : 1;
	if ( $paged < 1 ) {
		$paged = 1;
	}

	return array(
		'city'  => $city,
		'type'  => $type,
		'paged' => $paged,
	);
}

/**
 * Build WP_Query args from the sanitised filter state.
 *
 * @param array $params Sanitised params (erfanpro_projects_sanitize_params).
 * @return array WP_Query args.
 */
function erfanpro_projects_query_args( $params ) {
	$args = array(
		'post_type'      => 'projects',
		'post_status'    => 'publish',
		'posts_per_page' => ERFANPRO_PROJECTS_PER_PAGE,
		'paged'          => $params['paged'],
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$tax_query = array();
	if ( '' !== $params['city'] ) {
		$tax_query[] = array(
			'taxonomy' => 'city',
			'field'    => 'slug',
			'terms'    => array( $params['city'] ),
		);
	}
	if ( '' !== $params['type'] ) {
		$tax_query[] = array(
			'taxonomy' => 'project_type',
			'field'    => 'slug',
			'terms'    => array( $params['type'] ),
		);
	}
	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	if ( ! empty( $tax_query ) ) {
		$args['tax_query'] = $tax_query;
	}

	return $args;
}

/**
 * One project card — the reference dark card from projects.html.
 *
 * @param int   $post_id Project ID.
 * @param float $delay   Reveal stagger delay in seconds.
 * @return string Card HTML.
 */
function erfanpro_project_card_html( $post_id, $delay = 0.0 ) {
	$title = get_the_title( $post_id );
	$url   = get_permalink( $post_id );

	$image = get_the_post_thumbnail_url( $post_id, 'large' );
	if ( ! $image ) {
		$slides = erfanpro_project_slides( $post_id );
		$image  = ! empty( $slides ) ? $slides[0]['url'] : '';
	}
	if ( ! $image ) {
		$image = ERFANPRO_URI . '/assets/img/light-element.jpg';
	}

	$type_name = erfanpro_project_term_name( $post_id, 'project_type' );
	$city_name = erfanpro_project_term_name( $post_id, 'city' );
	$year      = erfanpro_project_year( $post_id );

	$delay_s = number_format( (float) $delay, 2, '.', '' );

	ob_start();
	?>
	<div data-anim="" data-anim-init="opacity: 0; transform: translateY(42px); filter: blur(6px);" data-anim-final="opacity: 1; filter: blur(0px); transform: none;" data-anim-kind="inview" data-anim-delay="<?php echo esc_attr( $delay_s ); ?>" style="opacity: 0; transform: translateY(42px); filter: blur(6px); --anim-delay: <?php echo esc_attr( $delay_s ); ?>s;">
		<a class="card-glow group relative block overflow-hidden rounded-[1.5rem] border border-emerald-100 bg-slate-900 shadow-sm" href="<?php echo esc_url( $url ); ?>">
			<div class="relative h-60 overflow-hidden">
				<img alt="<?php echo esc_attr( $title ); ?>" loading="lazy" class="h-full w-full object-cover transition-transform duration-[1.2s] ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-110" src="<?php echo esc_url( $image ); ?>">
				<div class="image-overlay absolute inset-0"></div>
				<?php if ( '' !== $type_name ) : ?>
				<span class="absolute right-4 top-4 rounded-full bg-emerald-950/75 px-3.5 py-1.5 text-[11.5px] font-bold text-emerald-100 backdrop-blur-md"><?php echo esc_html( $type_name ); ?></span>
				<?php endif; ?>
				<span class="absolute left-4 top-4 grid h-10 w-10 place-items-center rounded-full border border-white/30 bg-emerald-950/70 backdrop-blur-md transition-all duration-500 group-hover:rotate-45 group-hover:bg-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-left h-4.5 w-4.5 text-white" aria-hidden="true"><path d="M7 17V7h10"></path><path d="M17 17 7 7"></path></svg></span>
				<div class="absolute inset-x-0 bottom-0 p-5">
					<h3 class="text-lg font-extrabold leading-7 text-white [text-shadow:0_2px_12px_rgba(0,0,0,.8)]"><?php echo esc_html( $title ); ?></h3>
				</div>
			</div>
			<div class="flex items-center justify-between bg-white px-5 py-4">
				<?php if ( '' !== $city_name ) : ?>
				<span class="flex items-center gap-1.5 text-[12.5px] font-semibold text-slate-600"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4 text-emerald-600" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg><?php echo esc_html( $city_name ); ?></span>
				<?php else : ?>
				<span></span>
				<?php endif; ?>
				<span class="text-[12.5px] font-bold text-emerald-700"><?php echo esc_html( sprintf( __( 'سال %s', 'erfanpro' ), $year ) ); ?></span>
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
function erfanpro_projects_render( $params ) {
	$query = new WP_Query( erfanpro_projects_query_args( $params ) );

	$html = '';
	$i    = 0;
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$html .= erfanpro_project_card_html( get_the_ID(), 0.07 * ( $i % 6 ) );
			++$i;
		}
		wp_reset_postdata();
	}

	if ( '' === $html ) {
		$html = sprintf(
			'<div class="col-span-full flex flex-col items-center gap-4 rounded-[1.5rem] border border-dashed border-slate-300 bg-white/70 px-6 py-16 text-center"><span class="grid h-14 w-14 place-items-center rounded-full bg-emerald-50 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-search h-6 w-6" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path><path d="M20 13v3a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h7"></path></svg></span><p class="text-[15px] font-extrabold text-slate-800">%s</p><p class="max-w-sm text-[13px] leading-7 text-slate-500">%s</p><button type="button" data-projects-reset class="rounded-full bg-emerald-600 px-6 py-2.5 text-[13px] font-bold text-white shadow-md shadow-emerald-600/25 transition-all hover:bg-emerald-700">%s</button></div>',
			esc_html__( 'پروژه‌ای با این مشخصات پیدا نشد', 'erfanpro' ),
			esc_html__( 'شهر یا نوع پروژه را تغییر دهید تا نتایج بیشتری ببینید.', 'erfanpro' ),
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
 * Numbered pagination for the projects grid (AJAX-driven, href fallback).
 *
 * @param int $total_pages Total pages.
 * @param int $current     Current page.
 * @return string Pagination HTML (empty when a single page).
 */
function erfanpro_projects_pagination_html( $total_pages, $current ) {
	if ( $total_pages < 2 ) {
		return '';
	}

	$base_btn = 'grid h-10 min-w-10 place-items-center rounded-full px-3 text-[13px] font-bold transition-all duration-300 border ';
	$inactive = $base_btn . 'border-slate-200 bg-white text-slate-600 hover:border-emerald-400 hover:text-emerald-700';
	$active   = $base_btn . 'bg-emerald-600 text-white border-emerald-600 shadow-md shadow-emerald-600/25';

	ob_start();
	?>
	<nav class="mt-10 flex items-center justify-center gap-2" aria-label="<?php esc_attr_e( 'صفحه‌بندی پروژه‌ها', 'erfanpro' ); ?>">
		<?php if ( $current > 1 ) : ?>
		<a href="<?php echo esc_url( get_pagenum_link( $current - 1 ) ); ?>" data-projects-page="<?php echo esc_attr( $current - 1 ); ?>" class="<?php echo esc_attr( $inactive ); ?>" aria-label="<?php esc_attr_e( 'صفحه قبلی', 'erfanpro' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-4 w-4" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg></a>
		<?php endif; ?>
		<?php for ( $i = 1; $i <= $total_pages; $i++ ) : ?>
		<a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" data-projects-page="<?php echo esc_attr( $i ); ?>" class="<?php echo esc_attr( $i === $current ? $active : $inactive ); ?>"<?php echo $i === $current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( erfanpro_fa_digits( $i ) ); ?></a>
		<?php endfor; ?>
		<?php if ( $current < $total_pages ) : ?>
		<a href="<?php echo esc_url( get_pagenum_link( $current + 1 ) ); ?>" data-projects-page="<?php echo esc_attr( $current + 1 ); ?>" class="<?php echo esc_attr( $inactive ); ?>" aria-label="<?php esc_attr_e( 'صفحه بعدی', 'erfanpro' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-4 w-4" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg></a>
		<?php endif; ?>
	</nav>
	<?php
	return (string) ob_get_clean();
}

/**
 * Result-count text: «نمایش X از Y پروژه».
 *
 * @param int $shown Shown on this page.
 * @param int $total Total found.
 * @return string
 */
function erfanpro_projects_count_text( $shown, $total ) {
	return sprintf(
		/* translators: 1: shown count, 2: total count */
		__( 'نمایش %1$s از %2$s پروژه', 'erfanpro' ),
		erfanpro_fa_digits( $shown ),
		erfanpro_fa_digits( $total )
	);
}

/**
 * AJAX handler: projects city/type filter + pagination.
 */
function erfanpro_projects_ajax_filter() {
	check_ajax_referer( 'erfanpro-projects-filter', 'nonce' );

	$params = erfanpro_projects_sanitize_params( wp_unslash( $_POST ) );
	$result = erfanpro_projects_render( $params );

	$shown = 0;
	if ( $result['total'] > 0 ) {
		$shown = min( $result['total'], ERFANPRO_PROJECTS_PER_PAGE * $params['paged'] ) - ERFANPRO_PROJECTS_PER_PAGE * ( $params['paged'] - 1 );
		$shown = max( 0, $shown );
	}

	wp_send_json_success(
		array(
			'html'       => $result['html'],
			'pagination' => erfanpro_projects_pagination_html( $result['max_pages'], $params['paged'] ),
			'count_text' => erfanpro_projects_count_text( $shown, $result['total'] ),
			'total'      => $result['total'],
			'paged'      => $params['paged'],
			'max_pages'  => $result['max_pages'],
		)
	);
}
add_action( 'wp_ajax_erfanpro_projects_filter', 'erfanpro_projects_ajax_filter' );
add_action( 'wp_ajax_nopriv_erfanpro_projects_filter', 'erfanpro_projects_ajax_filter' );

/**
 * Gallery slides for a project, in display order:
 * featured image, blueprint `gallery-projects` (CSV IDs), then
 * second-image, three-image, foure-image, five-image, six-image.
 *
 * @param int $post_id Project ID.
 * @return array<int, array{type:string,url:string,id?:int}>
 */
function erfanpro_project_slides( $post_id ) {
	$ids = array();

	$thumb = get_post_thumbnail_id( $post_id );
	if ( $thumb ) {
		$ids[] = (int) $thumb;
	}
	$ids = array_merge( $ids, erfanpro_get_id_list( $post_id, 'gallery-projects' ) );
	/* Blueprint spellings, verbatim: second, three, foure, five, six. */
	foreach ( array( 'second-image', 'three-image', 'foure-image', 'five-image', 'six-image' ) as $key ) {
		$one = absint( get_post_meta( $post_id, $key, true ) );
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
	return $slides;
}

/**
 * Products used in a project: blueprint `mortabet_mahsole_2` (JSON
 * array of product IDs) first, then `product-in-projects`.
 *
 * @param int $post_id Project ID.
 * @param int $limit   Max products.
 * @return WC_Product[]
 */
function erfanpro_project_used_products( $post_id, $limit = 3 ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return array();
	}
	$found = array();
	$seen  = array();
	foreach ( array( 'mortabet_mahsole_2', 'product-in-projects' ) as $key ) {
		foreach ( erfanpro_get_id_list( $post_id, $key ) as $id ) {
			if ( in_array( $id, $seen, true ) ) {
				continue;
			}
			$seen[] = $id;
			$p      = wc_get_product( $id );
			if ( $p && 'publish' === $p->get_status() ) {
				$found[] = $p;
			}
			if ( count( $found ) >= $limit ) {
				break 2;
			}
		}
	}
	return array_slice( $found, 0, $limit );
}

/**
 * Related projects: same city first, then same project_type, then
 * latest (decision 17, automatic).
 *
 * @param int $post_id Project ID.
 * @param int $limit   Max projects.
 * @return int[] Post IDs.
 */
function erfanpro_related_projects_for_project( $post_id, $limit = 3 ) {
	if ( ! post_type_exists( 'projects' ) ) {
		return array();
	}
	$out  = array();
	$seen = array( $post_id );

	$city_terms = get_the_terms( $post_id, 'city' );
	$city_ids   = ( ! is_wp_error( $city_terms ) && ! empty( $city_terms ) )
		? array_map( 'absint', wp_list_pluck( $city_terms, 'term_id' ) )
		: array();
	$type_terms = get_the_terms( $post_id, 'project_type' );
	$type_ids   = ( ! is_wp_error( $type_terms ) && ! empty( $type_terms ) )
		? array_map( 'absint', wp_list_pluck( $type_terms, 'term_id' ) )
		: array();

	$collect = function ( $tax_query ) use ( &$out, &$seen, $post_id, $limit ) {
		if ( count( $out ) >= $limit ) {
			return;
		}
		$q = new WP_Query(
			array(
				'post_type'      => 'projects',
				'post_status'    => 'publish',
				'posts_per_page' => ( $limit - count( $out ) ) * 3,
				'post__not_in'   => $seen,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'tax_query'      => $tax_query,
			)
		);
		foreach ( $q->posts as $id ) {
			$out[]  = $id;
			$seen[] = $id;
			if ( count( $out ) >= $limit ) {
				break;
			}
		}
		wp_reset_postdata();
	};

	if ( ! empty( $city_ids ) ) {
		$collect(
			array(
				array(
					'taxonomy' => 'city',
					'field'    => 'term_id',
					'terms'    => $city_ids,
				),
			)
		);
	}
	if ( ! empty( $type_ids ) ) {
		$collect(
			array(
				array(
					'taxonomy' => 'project_type',
					'field'    => 'term_id',
					'terms'    => $type_ids,
				),
			)
		);
	}
	$collect( array() );

	return array_slice( $out, 0, $limit );
}
