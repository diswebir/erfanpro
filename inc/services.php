<?php
/**
 * Phase 7: services archive + single helpers (decision 11).
 *
 * - erfanpro_services_all(): ordered list of published service IDs.
 * - erfanpro_service_short_name()/kicker()/subtitle()/badge()/note():
 *   admin-editable texts (BO-008 fields) with reference defaults.
 * - erfanpro_service_image(): featured image, reference-order asset fallback.
 * - erfanpro_service_steps(): service_steps repeater, reference fallback.
 * - erfanpro_service_prev_next()/others(): archive-order neighbours.
 * - erfanpro_service_related_projects(): manual service_projects meta,
 *   filled with latest projects (decision 17, automatic).
 * - erfanpro_contact_url(): contact page permalink or /contact/ fallback.
 *
 * @package Erfanpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All published services, archive order (menu_order, then date).
 *
 * @return int[] Post IDs.
 */
function erfanpro_services_all() {
	$q = new WP_Query(
		array(
			'post_type'      => 'services',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);
	$ids = $q->posts;
	wp_reset_postdata();
	return array_map( 'absint', (array) $ids );
}

/**
 * Short name for the archive chips: service_short_name meta, else the
 * title with a leading «نورپردازی » stripped (matches the reference chips).
 *
 * @param int $post_id Service ID.
 * @return string
 */
function erfanpro_service_short_name( $post_id ) {
	$short = trim( (string) get_post_meta( $post_id, 'service_short_name', true ) );
	if ( '' !== $short ) {
		return $short;
	}
	$title = get_the_title( $post_id );
	return preg_replace( '/^نورپردازی /u', '', $title );
}

/**
 * Archive row kicker badge.
 *
 * @param int $post_id Service ID.
 * @return string
 */
function erfanpro_service_kicker( $post_id ) {
	$kicker = trim( (string) get_post_meta( $post_id, 'service_kicker', true ) );
	if ( '' !== $kicker ) {
		return $kicker;
	}
	return (string) get_the_excerpt( $post_id );
}

/**
 * Single hero subtitle (badge row + image caption).
 *
 * @param int $post_id Service ID.
 * @return string
 */
function erfanpro_service_subtitle( $post_id ) {
	$sub = trim( (string) get_post_meta( $post_id, 'service_subtitle', true ) );
	if ( '' !== $sub ) {
		return $sub;
	}
	return (string) get_the_excerpt( $post_id );
}

/**
 * Single hero image badge (reference: «IP67/IP68»).
 *
 * @param int $post_id Service ID.
 * @return string
 */
function erfanpro_service_badge( $post_id ) {
	$badge = trim( (string) get_post_meta( $post_id, 'service_badge', true ) );
	return '' !== $badge ? $badge : 'IP67/IP68';
}

/**
 * «نکته مهم» box under the features list.
 *
 * @param int $post_id Service ID.
 * @return string
 */
function erfanpro_service_note( $post_id ) {
	$note = trim( (string) get_post_meta( $post_id, 'service_note', true ) );
	if ( '' !== $note ) {
		return $note;
	}
	return __( 'تمامی اقلام بالا با متریال درجه‌یک، استاندارد IP67/IP68 و ۲۴ ماه گارانتی تعویض به‌همراه ۵ سال خدمات پس از فروش اجرا می‌شوند.', 'erfanpro' );
}

/**
 * Service image: featured image, else the reference-order asset fallback
 * (light-sphere, hero, string-lights, light-element, chandelier).
 *
 * @param int    $post_id Service ID.
 * @param string $size    Image size.
 * @return string URL.
 */
function erfanpro_service_image( $post_id, $size = 'large' ) {
	$url = get_the_post_thumbnail_url( $post_id, $size );
	if ( $url ) {
		return $url;
	}
	$fallbacks = array(
		'light-sphere.jpg',
		'hero.jpg',
		'string-lights.jpg',
		'light-element.jpg',
		'chandelier.jpg',
	);
	$all   = erfanpro_services_all();
	$index = array_search( (int) $post_id, $all, true );
	if ( false === $index ) {
		$index = 0;
	}
	return ERFANPRO_URI . '/assets/img/' . $fallbacks[ $index % count( $fallbacks ) ];
}

/**
 * Steps for the single aside timeline: service_steps repeater
 * (number/title/desc), else the reference 5-step fallback.
 *
 * @param int $post_id Service ID.
 * @return array<int, array{number:string,title:string,desc:string}>
 */
function erfanpro_service_steps( $post_id ) {
	$rows  = erfanpro_get_repeater( $post_id, 'service_steps' );
	$steps = array();
	foreach ( (array) $rows as $i => $r ) {
		$title = trim( (string) ( $r['title'] ?? '' ) );
		if ( '' === $title ) {
			continue;
		}
		$steps[] = array(
			'number' => '' !== trim( (string) ( $r['number'] ?? '' ) ) ? (string) $r['number'] : (string) ( $i + 1 ),
			'title'  => $title,
			'desc'   => trim( (string) ( $r['desc'] ?? '' ) ),
		);
	}
	if ( ! empty( $steps ) ) {
		return $steps;
	}
	return array(
		array( 'number' => '۱', 'title' => __( 'بازدید و نیازسنجی', 'erfanpro' ), 'desc' => __( 'بررسی میدانی محل پروژه', 'erfanpro' ) ),
		array( 'number' => '۲', 'title' => __( 'طراحی اختصاصی', 'erfanpro' ), 'desc' => __( 'طرح سه‌بعدی و سناریوی نوری', 'erfanpro' ) ),
		array( 'number' => '۳', 'title' => __( 'تولید در کارخانه', 'erfanpro' ), 'desc' => __( 'استاندارد IP67/IP68', 'erfanpro' ) ),
		array( 'number' => '۴', 'title' => __( 'نصب و اجرا', 'erfanpro' ), 'desc' => __( 'تیم مجرب در سراسر کشور', 'erfanpro' ) ),
		array( 'number' => '۵', 'title' => __( 'تحویل و پشتیبانی', 'erfanpro' ), 'desc' => __( '۲۴ ماه گارانتی', 'erfanpro' ) ),
	);
}

/**
 * Step icon SVG for the aside timeline, by zero-based index
 * (search, pencil-ruler, wrench, hard-hat, lightbulb — reference order).
 *
 * @param int $index Step index.
 * @return string SVG markup.
 */
function erfanpro_service_step_icon( $index ) {
	$icons = array(
		'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-4 w-4" aria-hidden="true"><path d="m21 21-4.34-4.34"></path><circle cx="11" cy="11" r="8"></circle></svg>',
		'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-ruler h-4 w-4" aria-hidden="true"><path d="M13 7 8.7 2.7a2.41 2.41 0 0 0-3.4 0L2.7 5.3a2.41 2.41 0 0 0 0 3.4L7 13"></path><path d="m8 6 2-2"></path><path d="m18 16 2-2"></path><path d="m17 11 4.3 4.3c.94.94.94 2.46 0 3.4l-2.6 2.6c-.94.94-2.46.94-3.4 0L11 17"></path><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path><path d="m15 5 4 4"></path></svg>',
		'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench h-4 w-4" aria-hidden="true"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z"></path></svg>',
		'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hard-hat h-4 w-4" aria-hidden="true"><path d="M10 10V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5"></path><path d="M14 6a6 6 0 0 1 6 6v3"></path><path d="M4 15v-3a6 6 0 0 1 6-6"></path><rect x="2" y="15" width="20" height="4" rx="1"></rect></svg>',
		'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lightbulb h-4 w-4" aria-hidden="true"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path><path d="M9 18h6"></path><path d="M10 22h4"></path></svg>',
	);
	return $icons[ (int) $index % count( $icons ) ];
}

/**
 * Previous/next service in archive order.
 *
 * @param int $post_id Service ID.
 * @return array{prev:int,next:int}
 */
function erfanpro_service_prev_next( $post_id ) {
	$all   = erfanpro_services_all();
	$index = array_search( (int) $post_id, $all, true );
	if ( false === $index ) {
		return array( 'prev' => 0, 'next' => 0 );
	}
	return array(
		'prev' => $index > 0 ? $all[ $index - 1 ] : 0,
		'next' => isset( $all[ $index + 1 ] ) ? $all[ $index + 1 ] : 0,
	);
}

/**
 * Other services (all except the current one), archive order.
 *
 * @param int $post_id Service ID.
 * @return int[]
 */
function erfanpro_service_others( $post_id ) {
	return array_values(
		array_filter(
			erfanpro_services_all(),
			function ( $id ) use ( $post_id ) {
				return (int) $id !== (int) $post_id;
			}
		)
	);
}

/**
 * Related projects for a service: manual `service_projects` selection
 * first, then latest projects to fill up to $limit (decision 17).
 *
 * @param int $post_id Service ID.
 * @param int $limit   Max projects.
 * @return int[]
 */
function erfanpro_service_related_projects( $post_id, $limit = 3 ) {
	if ( ! post_type_exists( 'projects' ) ) {
		return array();
	}
	$out  = array();
	$seen = array();
	foreach ( erfanpro_service_projects( $post_id ) as $pid ) {
		if ( get_post_status( $pid ) === 'publish' ) {
			$out[]  = (int) $pid;
			$seen[] = (int) $pid;
		}
		if ( count( $out ) >= $limit ) {
			break;
		}
	}
	if ( count( $out ) < $limit ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'projects',
				'post_status'    => 'publish',
				'posts_per_page' => ( $limit - count( $out ) ) * 2,
				'post__not_in'   => $seen,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		foreach ( $q->posts as $pid ) {
			$out[] = (int) $pid;
			if ( count( $out ) >= $limit ) {
				break;
			}
		}
		wp_reset_postdata();
	}
	return array_slice( $out, 0, $limit );
}

/**
 * Contact page URL: page with the `contact` slug, else /contact/ fallback.
 *
 * @return string
 */
function erfanpro_contact_url() {
	$page = get_page_by_path( 'contact' );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/contact/' );
}
